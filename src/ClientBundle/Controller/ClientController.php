<?php

namespace ClientBundle\Controller;

use ClientBundle\Entity\Client;
use ClientBundle\Form\ClientForm;
use ClientBundle\Form\LostPasswordForm;
use ClientBundle\Form\PasswordForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use ClientBundle\Entity\Address;
use ClientBundle\Entity\Token;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\User\User;
use ClientBundle\Entity\Historic;

class ClientController extends Controller
{
	public function getClientAction()
	{
		$message='';
			
		
		
		$user=new Client();
		$form = $this->container->get('form.factory')->create(new ClientForm(), $user);
		
		return $this->container->get('templating')->renderResponse(
				'ClientBundle:Client:getClient.html.twig', array(
						'message'=>$message,
						'form'=> $form->createView(),
				));
		
	}
	
	public function setClientAction(){
	
		$Address=array();
		$client=array();
		$user=new Client();
		$address=new Address();
		$functions=array(
				'adress'=>false,
				'client'=>false,
				'user'=>false,
		);
		
		
		
		
		$request = $this->get('request');
		$flashbag=$request->getSession()->getFlashBag();
		$client = $request->request->get('client');
		$oldclient=$client;
		$form = $this->createForm(new ClientForm());
		$form->handlerequest($request);
		$validator=$this->get('validator');
		
		if($form->isValid()) {	
			// on teste le clientName
			
			$val1=$this->container->get('app.validate_clientname')->validate($validator,$client);
			if(count($val1) != 0 )
			{
				// on évite de doubler le message d'erreur.
				if(count($val1)==2){
					unset($val1[1]);
				}
			
			}
				
			
			if(isset($client['deliveryAddress'])){
				$Address=$client['deliveryAddress'];

    			$val2=$this->get('app.validate_address')->validate($validator,$Address);
		
				$val=array_merge($val1, $val2);
		
				if(count($val) != 0 ){
			// Une erreur est survenue lors de la validation, on rend le code erreur et on reste sur l'écran.
			
			return $this->get('templating')->renderResponse(
					'ClientBundle:Client:getClient.html.twig', array(
							'message'=>$message,
							'form'=> $form->createView(),
							'errors'=>$val,
					));

/*			$vm = new ViolationMapper();
			
			// Format should be: children[businessAddress].children[postalCode]
			$error['firstname'] = False;
			$error['message']="code postal invalide";
			// Convert error to violation.
			$constraint = new ConstraintViolation(
					$error['message'], $error['message'], array(), '','firstname','firstname', Null
					);
	
			$vm->mapViolation($constraint, $form);
		//	$message="Adresse invalide";
*/
			
			
		}
		}
 		else{
 		// Erreur, pas d'adresse	
 		throw new \Exception('Anomalie de fonctionnement: Nous navons pas reçu d\'adresse avec le client');
 		
 		}
 		
 		$address->Hydrate($Address);
 		$user->Hydrate($client);
	

		
		
		$em = $this->container->get('doctrine')->getManager();
// d'abord on regarde si l'utilisateur existe déja


		$userManager = $this->get('fos_user.user_manager');
	    $error=0;
	    if($userManager->findUserByEmail($client['user']['email'])){
	    	$flashbag->add("error", $this->get('translator')->trans('existing_email'));
	    	$error++;
	    }
	    if($userManager->findUserByUserName($client['user']['username'])){
	    	$flashbag->add("error", $this->get('translator')->trans('existing_username'));
	    	$error++;
	    }
	    if($error<>0)
	    	return $this->container->get('templating')->renderResponse(
	    			'ClientBundle:Client:getClient.html.twig', array(
	    					'form'=> $form->createView(),
	    					'errors'=>"",
	    			));
	    
	    		
		
// on sauvegarde d'abord l'adresse de livraison
		$address->setEmail(strtolower($client['user']['email']));
		$em->persist($address);
		$em->flush();
		$functions['address']=$address;
// puis le user
		$username=$client['user']['username'];
		$password=$client['user']['plainPassword']['first'];
		$email=$client['user']['email'];
		$active=false;
		$superadmin=false;
		$manipulator = $this->get('fos_user.util.user_manipulator');
		$usr=$manipulator->create($username, $password, $email, $active, $superadmin);
		$token=md5(uniqid());
		$usr->setConfirmationToken($token);
//		$manipulator->addRole($username, 'ROLE_ADMIN');
		$userManager->updateUser($usr);
		
		
		
		$functions['user']=$usr;
// enfin le client
		$user->setGender($client['gender']);
        $user->setfirstname(ucfirst( strtolower($client['firstName'])));
        $user->setLastname(strtoupper($client['lastName']));
		$user->setDeliveryAddress($address);
		$user->setUser($usr);
		$em->persist($user);
		$em->flush();
		$functions['client']=$user;


		
		$this->get('session')->set('clientAdress', $address);
		$this->get('session')->set('clientName', $user);
		$this->get('session')->set('user', $usr);

		
// on envoie l'email de validation		
		$mailer = $this->get('mailer');
		$message = new \Swift_Message();
		$urlSite=$this->getParameter('app.URL');
//		$imgUrl = $message->attach(\Swift_Attachment::fromPath('http://www.bm.usrestation.fr/images/logo.png'));
		$imgUrl = $urlSite.'/images/logo.png';
		
	
		$urlValidation=$this->get('router')->generate('validateclient',array('token' => $token));
		$urlValidation=$urlSite.$urlValidation;
		$message->setFrom('no_reply@bmstation.fr')
		->setTo($email)
		->setSubject("Inscription sur BMSTation")
		->setContentType('text/html')
		->setBody(
				$this->renderView('Emails/inscription_confirmation.html.twig',
						array(
						'url'=>$imgUrl,
						'user'=>$user,
						'team'=>'BMStation',
						'site'=>$urlSite,	
						'urlvalidation'=>$urlValidation,		
						)
				));
				/*
				 * If you also want to include a plaintext version of the message
				 ->addPart(
				 $this->renderView(
				 'Emails/registration.txt.twig',
				 array('name' => $name)
				 ),
				 'text/plain'
				 )
				 */
		
		
		$mailer->send($message);

// et on gère l'historic	
		$historic=new Historic();
		$historic->setClient($user);
		$historic->setType('Inscription');
		$historic->setEventId($user->getId());
		$historic->setDate(new \DateTime());
		$em->persist($historic);
		$em->flush();
//		
		
		
		// on redirige sur la page de login
		$this->get('session')->set( '_security.last_username',$username );
		return new RedirectResponse($this->container->get('router')->generate('fos_user_security_login'));
	}
	// la form est invalide
	$val=$form->getErrors() ;
	return $this->container->get('templating')->renderResponse(
			'ClientBundle:Client:getClient.html.twig', array(
					'form'=> $form->createView(),
					'errors'=>$val,
			));
	}
	
	public function validateClientAction($token=null){
		
		if($token==null){
			return new RedirectResponse($this->get('router')->generate('homepage'));
		}
		$userManager = $this->get('fos_user.user_manager');
		$user=$userManager->findUserByConfirmationToken($token);
		
		if(!$user){
			return new RedirectResponse($this->get('router')->generate('homepage'));
		}
		
		$user->setEnabled(true);
		$user->setConfirmationToken(null);
		
		$userManager->updateUser($user);
		
		$em = $this->container->get('doctrine')->getManager();
		$client=$em->getRepository('\ClientBundle\Entity\Client')->findByEmail($user->getEmail());
		
		// et on gère l'historic
		$historic=new Historic();
		$historic->setClient($client);
		$historic->setType('Self Validation');
		$historic->setEventId($client->getId());
		$historic->setDate(new \DateTime());
		$em->persist($historic);
		$em->flush();
		
		return new RedirectResponse($this->get('router')->generate('fos_user_security_login'));
		
		
	}
	
	public function lostPasswordAction(){
		
		$request = $this->get('request');
		$form = $this->createForm(new LostPasswordForm());
		if($request->getMethod()=='POST'){
		
		$form->handlerequest($request);
		if($form->isValid()){
			
			$mailer = $this->get('mailer');
			$message = new \Swift_Message();
			$urlSite=$this->getParameter('app.URL');
			//		$imgUrl = $message->attach(\Swift_Attachment::fromPath('http://www.bm.usrestation.fr/images/logo.png'));
			$imgUrl = $urlSite.'/images/logo.png';
			
			$token=md5(uniqid());
			$email=$form->getData()['email'];
			$urlValidation=$this->get('router')->generate('passwordclient',array('token' => $token));
			$urlValidation=$urlSite.$urlValidation;
			$message->setFrom('no_reply@bmstation.fr')
			->setTo($email)
			->setSubject("Mot de passe perdu sur BMSTation")
			->setContentType('text/html')
			->setBody(
					$this->renderView('Emails/password_resetting.html.twig',
							array(
									'url'=>$imgUrl,
									'team'=>'BMStation',
									'site'=>$urlSite,
									'urlvalidation'=>$urlValidation,
							)
							));
			$mailer->send($message);
			
			$em=$this->get('doctrine')->getManager();
			$base=new Token();
			$base->setToken($token);
			$base->setEmail($email);
			
			$em->persist($base);
			$em->flush();
			
			
			return new RedirectResponse($this->get('router')->generate('homepage'));
			
			}
			// la form est invalide...email incohérent !
			return $this->container->get('templating')->renderResponse(
					'ClientBundle:Client:lostPassword.html.twig', array(
							'form'=> $form->createView(),
					));
							
		}
		else{
		
			return $this->container->get('templating')->renderResponse(
					'ClientBundle:Client:lostPassword.html.twig', array(
							'form'=> $form->createView(),
							
							
					));
		}
	}
	
	public function passwordClientAction($token=null){
		
		
		

		$form = $this->createForm(new PasswordForm());
		$request = $this->get('request');
		
		if($request->getMethod()=='POST'){
			$form->handleRequest($request);
			if($form->isValid()){
				// on regarde d'abord si le token existe
				$em=$this->get('doctrine')->getManager();
				if(!$em->getRepository('ClientBundle:Token')->findByToken($token))
				{
					return $this->get('templating')->renderResponse(
							'ClientBundle::error.html.twig');
				}
				
				$data=$form->getData();
				
				$username=$data['username'];
                $password=$data['plainPassword'];					
                $user=$em->getRepository("UserBundle:User")->findOneByUsername($username);
                $oldToken=$em->getRepository('ClientBundle:Token')->findOneByToken($token);
				if(!$oldToken || !$user){
					
					return $this->get('templating')->renderResponse(
							'ClientBundle::error.html.twig');
				}
                
				$manipulator = $this->get('fos_user.util.user_manipulator');
				$manipulator->changePassword($username, $password);
				
				// on retire l'ancien token
			
				$em->remove($oldToken);
				$em->flush();
			    
			    // et on tiens à jour le user
			    
				$user->setPasswordRequestedAt(new \DateTime());
				$em->persist($user);
				$em->flush();
				
			
				return $this->container->get('templating')->renderResponse(
						'ClientBundle::success.html.twig', array(
								'username'=>$username));
				
			}
			else {
			// form invalide
				return $this->container->get('templating')->renderResponse(
						'ClientBundle:Client:password.html.twig', array(
								'form'=> $form->createView(),
								'token'=>$token,));
			}
			
		}
		else { 
		// c'est un get	
		return $this->container->get('templating')->renderResponse(
				'ClientBundle:Client:password.html.twig', array(
						'form'=> $form->createView(),
						'token'=>$token,));
		} 
	}
	
	
	
}