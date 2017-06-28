<?php
namespace AppBundle\Controller;

use ClientBundle\Entity\Client;
use ClientBundle\Entity\Address;
use UserBundle\Entity\User;
use ClientBundle\Form\ClientForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Doctrine\DBAL\Types\TextType;
use ClientBundle\Entity\Historic;
use ClientBundle\Entity\Contact;


class ClientController extends Controller{
	public function listClientAction(){
			$return=$this->listClient();
	
		$return = $this->get('knp_paginator')->paginate($return,$this->get('request')->query->get('page', 1),10);
		
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Client:listClient.html.twig', array(
						'clients'=>$return,
				));
	}
	public function deleteClientAction($id=null){
	
		$em=$this->get('doctrine')->getManager();
	
	
		if($id==0) {
			throw new InvalidArgumentException('id is null.');
		}
	
		$client=$em->find('ClientBundle:Client',$id);
	
		if(!$client){
			throw new InvalidArgumentException('client not existant.');
		}
	
		// ok, le client existe, il devrait avoir une adresse de livraison et un user
	
		// d'abord, on efface 'historque (a cause des liens)
		$historics=$em->getRepository("ClientBundle\Entity\Historic")->findAllHistoric($client);
	    
		for($i=0;$i<count($historics);$i++){
			$em->remove($historics[$i]);
		}
		$em->flush();
		
		// puis, on détruit le client
	
		$em->remove($client);
		$em->flush();
	
		// puis l'adresse liée
	
		$address=$client->getDeliveryAddress();
		if($address){
			$em->remove($address);
			$em->flush();
		}
	
		// enfin, on efface le user
	
		$user=$client->getUser();
		if($user) {
			$em->remove($user);
			$em->flush();
		}
		
		
		
		
		
		
		$return=$this->listClient();
		$return = $this->get('knp_paginator')->paginate($return,$this->get('request')->query->get('page', 1),10);
		
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Client:listClient.html.twig', array(
						'clients'=>$return,
				));
		
	
	}
	
	
	private function listClient(){
		
	$list=array();
	$result=array();
	$return=array();
	
	
	$em=$this->get('doctrine')->getManager();
	
	$list=$em->getRepository('ClientBundle:Client')->findAll();
	
	for($i=0;$i<count($list);$i++){
		$result=$em->getRepository('ClientBundle:Client')->findAllValues($list[$i]);
		$return[$i]['id']=$result['id'];
		$return[$i]['firstName']=$result['firstName'];
		$return[$i]['lastName']=$result['lastName'];
		$return[$i]['email']=$result['deliveryAddress']['email'];
		$return[$i]['address']=$result['deliveryAddress']['address'];
		$return[$i]['city']=$result['deliveryAddress']['city'];
		$return[$i]['postalCode']=$result['deliveryAddress']['postalCode'];
		$return[$i]['userName']=$result['user']['username'];
		$return[$i]['enabled']=$result['user']['enabled'];
		$val=$result['user']['last_login'];
		if($val){
			$loc=$this->container->get('session')->get('_locale');
			if($loc=='en')
				$date=$val->format('m-d-Y H:i:s');
				else
					$date=$val->format('d-m-Y H:i:s');
					$return[$i]['last_login']=$date;
		}
		else
			$return[$i]['last_login']='never';
	    }
	    
	    return $return;
	}
	
	public function editClientAction($id=null){
	
		
		
	if($id==null)
		throw new InvalidArgumentException("no username");
	
		$em=$this->get('doctrine')->getManager();
		
		
		
	    if(!$client=$em->getRepository('ClientBundle:Client')->find($id)){
	    	// l'utilisateur a été détruit, on revient à la liste
	    	$return=$this->listClient();
	    	
	    	return $this->container->get('templating')->renderResponse(
	    			'AppBundle:Client:listClient.html.twig', array(
	    					'clients'=>$return,
	  
	    			));
	    }
		
	    
		$result=$em->getRepository('ClientBundle:Client')->findAllValues($id);
		$address=$em->getRepository('ClientBundle:Address')->find($client->getDeliveryAddress()->getId());
		$user=$em->getRepository('UserBundle:User')->find($client->getUser()->getId());
		$client->setDeliveryAddress($address);
		$client->setUser($user);
		$val=$user->getLastLogin();;
		if($val){
			$loc=$this->container->get('session')->get('_locale');
			if($loc=='fr')
				$date=$val->format('d-m-Y H:i:s');
				else
					$date=$val->format('m-d-Y H:i:s');
			$result['user']['last_login']=$date;
		}
		else
			$result['user']['last_login']='never';

		
				
	
		
		return $this->container->get('templating')->renderResponse(
			'AppBundle:Client:editClient.html.twig', array(
					'title'=>"edit",
					'client'=>$result,
					'return'=>$this->get('router')->generate('listclient'),
			));
	
	}
	
	public function setClientAction($id=NULL){
		
		$fields=array(
			'firstName'=>null,
			'lastName'=>null,
			'address'=>null,
			'city'=>null,
			'postalCode'=>null,
			'email'=>null,
			'phone'=>null,
			'username'=>null,
			'last_login'=>null,	
			'enabled'=>null,
		);
		
			
		
		$request=$this->container->get('request');
		$flashbag=$request->getSession()->getFlashBag();
    	$cl = $request->request->get('client');
		if($cl){
			// on est dans la partie ajax, le vrai appel va suivre
			foreach($fields as $field => $value){
			$val=strstr($cl,$field);
			//le champ est encadré par des []
			if($val){
				$close=strpos($val,']');
				$length=$close-strlen($field)-1;
				$vv=substr($val,strlen($field)+1,$length);
				$fields[$field]=$vv;
			}
		}
		// on sauvegarde le résultat et on sort
		$this->container->get('session')->set('client', $fields);
		return;
		}
	
		// ok, là on est dans le corps du contrôleur
		// d'abord, on récupère les infos stockées pas avant
		
		$val=$this->container->get('session')->get('client');
		if($val){
			foreach($val as $field=>$value){
				$fields[$field]=$val[$field];
			}
		}
		//on valide les info (a première vue, venant d'un administrateur, ce serait une erreur)
/*		$validator=$this->container->get('validator');
		if(!$this->container->get('app.validate_client')->validate($validator,$fields, $flashbag)){	
			return $this->container->get('templating')->renderResponse(
					'AppBundle:Client:editClient.html.twig', array(
							'title'=>"edit",
							'client'=>$fields,
					));
		}
*/		
		
		// à présent on récupère les infos de actuelles
		
		$em=$this->get('doctrine')->getManager();
	    $result=$em->getRepository('ClientBundle:Client')->findAllValues($id);
		
	    $client=$em->find('ClientBundle:Client',$id);
	    $address=$client->getDeliveryAddress();
	    
	    
	    if($fields['firstName']) $client->setFirstName($fields['firstName']);
	    if($fields['lastName']) $client->setLastName($fields['lastName']);
	    if($fields['address']) $address->setAddress($fields['address']);
	    if($fields['city']) $address->setCity($fields['city']);
	    if($fields['postalCode']) $address->setPostalCode($fields['postalCode']);
	    if($fields['email']) $address->setEmail($fields['email']);
	    if($fields['phone']) $address->setPhone($fields['phone']);
	    $client->setDeliveryAddress($address);
	    
	    $em->persist($client);
	    $em->flush();
	    
	    $user=$client->getUser();
	    $userManager = $this->get('fos_user.user_manager');
	    if($user->getEmail() !== $fields['email']){
	    	$user->setEmail($fields['email']);
	    	$user->setEnabled(false);
	    	$token=md5(uniqid());
	    	$user->setConfirmationToken($token);
	    	$userManager->updateUser($user);
	    	
	    	$mailer = $this->get('mailer');
	    	$message = new \Swift_Message();
	    	
	    
	    
	    	$urlValidation=$this->get('router')->generate('validateclient',array('token' => $token));
	    	$urlSite=$this->getParameter('app.URL');
	    	$urlValidation=$urlSite.$urlValidation;
	    	$imgUrl = $urlSite.'/images/logo.png';
	    	$message->setFrom('no_reply@bmstation.fr')
	    	->setTo($fields['email'])
	    	->setSubject("Changement d'email sur BMSTation")
	    	->setContentType('text/html')
	    	->setBody(
	    		$this->renderView('Emails/change_confirmation.html.twig',
	    				array(
	    						'url'=>$imgUrl,
	    						'user'=>$client,
	    						'team'=>'BMStation',
	    						'site'=>$urlSite,
	    						'urlvalidation'=>$urlValidation,
	    				)
	    				));
	    	$mailer->send($message);
	    }
	    else{
	    	if($fields['enabled']==="1"){
	    	if($user->getEnabled==false){
	    		// on gère l'historic
	    		$historic=new Historic();
	    		$historic->setClient($client);
	    		$historic->setType('Validate Client');
	    		$historic->setEventId($client->getId());
	    		$historic->setDate(new \DateTime());
	    		$em->persist($historic);
	    		$em->flush();
	    	}
	    	$user->setEnabled(true);
	    	}
	    if($fields['enabled']==="0"){
	    	if($user->getEnabled==true){
	    		// on gère l'historic
	    		$historic=new Historic();
	    		$historic->setClient($client);
	    		$historic->setType('Invalidate Client');
	    		$historic->setEventId($client->getId());
	    		$historic->setDate(new \DateTime());
	    		$em->persist($historic);
	    		$em->flush();
	    	}
	    	$user->setEnabled(false);
	    }
	    	
	    
	    	
	    $userManager->updateUser($user);
	    }
	    $return=$this->listClient();
	    $return = $this->get('knp_paginator')->paginate($return,$this->get('request')->query->get('page', 1),10);
	    
	    return $this->container->get('templating')->renderResponse(
	    		'AppBundle:Client:listClient.html.twig', array(
	    				'clients'=>$return,
	    		));
	
		
		
	}
	
	public function historicClientAction($id=null){
		
		$em=$this->get('doctrine')->getManager();
		
		if(!$client=$em->find('ClientBundle:Client', $id))
			throw new \InvalidArgumentException("not existant client");
		
	    $lines=$em->getRepository('ClientBundle:Historic')->findAllHistoric($client);
 
	    $lines=$this->get('knp_paginator')->paginate($lines,$this->get('request')->query->get('page', 1),10);
	    
	    return $this->container->get('templating')->renderResponse(
	    		'AppBundle:Client:listHistoric.html.twig', array(
	    				'lines'=>$lines,
	    		));
	}

	
	public function editHistoricLineAction($id=null){
	
		$em=$this->get('doctrine')->getManager();
		$line=$em->find('ClientBundle:Historic',$id);
	
		if(!$line)
			throw new \InvalidArgumentException('non existant historic line');
	
			$type=$line->getType();
	
			switch($type){
	
				case "Contact":
				case "Close Contact":
				case "Reopen Contact":
					$contact=$em->find('ClientBundle:Contact',$line->getEventId());
					$pseudo=new \AppBundle\PseudoClasses\PseudoContact($contact,$em);
					
					return $this->container->get('templating')->renderResponse(
							'AppBundle:Contact:editContact.html.twig',
							array(
									'title'=>$this->get('translator')->trans('editcontact'),
									'contact' => $pseudo,
									'return'=>$this->get('router')->generate('historicclient',array('id'=>$line->getClient()->getId()))
	
							));
			}
			$lines=$em->getRepository('ClientBundle:Historic')->findAllHistoric($contact->getClient());
			
			$lines=$this->get('knp_paginator')->paginate($lines,$this->get('request')->query->get('page', 1),10);
			
			return $this->container->get('templating')->renderResponse(
					'AppBundle:Client:listHistoric.html.twig', array(
							'lines'=>$lines,
					));
			return $this->container->get('templating')->renderResponse(
					'AppBundle:Client:listHistoric.html.twig', array(
							'lines'=>$lines,
					));
			
			
	}
		
	

}
