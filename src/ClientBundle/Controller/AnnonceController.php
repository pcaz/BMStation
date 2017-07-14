<?php
namespace ClientBundle\Controller;

use ClientBundle\Entity\Annonce;
use ClientBundle\Entity\Photo;
use ClientBundle\Entity\Client;
use ClientBundle\Entity\Historic;
use ClientBundle\Form\AnnonceForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ClientBundle\Form\NameBasketForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormError;


class AnnonceController extends Controller
{
	public function editAnnonceAction()
	{
	
            $user=$this->getUser();
            if($user)
                {
            
                $em=$this->get('doctrine')->getManager();
                $client=$em->getRepository('ClientBundle:Client')->findByEmail($user->getEmail());
                if($client)
                {
		         
                    $annonce = new Annonce();
                    $form = $this->createForm(new AnnonceForm,$annonce);
                    $request = $this->getRequest();
                    $form->get('email')->setData($user->getEmail());
                    $phone=$client->getDeliveryAddress()->getPhone();
                    $form->get('phone')->setData($phone);
                    $this->get('session')->set('client',$client);
                    $this->get('session')->remove('photo1');
                    $this->get('session')->remove('photo2');
                    $this->get('session')->remove('photo3');
                
                
                    return $this->get('templating')->renderResponse(
					'ClientBundle:Annonce:annonce.html.twig', array(
							'form'=>$form->createView(),
					));   
                    }
                }
                    return $this->render('ClientBundle:Annonce:denied.html.twig',
						array(
								
						));
	}
	
	public function uploadImageAction(Request $request, $id=null )
	{
	        $newPhoto=null;
                $response = new Response('error');
		if($request->isXmlHttpRequest())
		{
		    // d'abord, on recupère l'image
		    $newPhoto = $request->files->get('photo');		  
		    if ($newPhoto && $newPhoto->isValid())		
                    {
                 
                        $photo = new Photo();
                        $getImage = $this->get("app.image");
	    		$picture = $getImage->uploadImage($newPhoto);
                        $photo->setPicture($picture);
                        $directory = $this->getParameter('app.blog_photos');
                        $imageInfo=\getimagesize($directory.'/'.$picture);
                        $photo->setWidth($imageInfo[0]);
                        $photo->setHeight($imageInfo[1]);
                        $photo->setName($newPhoto->getClientOriginalName());
                        
                        $variable='photo'.$id;
                        $this->get('session')->set($variable,$photo);
//                      
		    	$response = new Response("sucess");
          
                    }
                }
                return $response;

	}
	
	public function setAnnonceAction(Request $request)
	{
	    $annonce=new Annonce();
	    $form = $this->createForm(new AnnonceForm,$annonce);
	    $request=$this->getRequest();
	    $form->handleRequest($request);
	    if($form->isValid())
	    {
                // on valide les champs
                
	        if(empty($form->get('name')->getData()))
                {
                    $form['name']->addError(new FormError($this->get('translator')->trans("annonce.The name must not be blank")));
                }
	       
                
                
                if(empty($form->get('shortdesc')->getData()))
                {
                    $form['shortdesc']->addError(new FormError($this->get('translator')->trans("annonce.Description must not be blank")));
                }
                
                 if(empty($form->get('price')->getData()))
                {
                    $form['price']->addError(new FormError($this->get('translator')->trans("annonce.Price must not be blank")));
                    
                    
                }
	             
	   if($form->isValid())
           {
            // pas d'erreurs, on enregistre
               $em=$this->get('doctrine')->getManager();
                  
               $client=$em->getRepository("ClientBundle\Entity\Client")
                       ->find($this->get('session')->get('client')->getId());
               
               $annonce->setClient($client);
               $annonce->setDate(new \DateTime());
               $annonce->setIdentifier($this->makeIdentifier($client,$annonce->getDate()->format('ymdhis')));
               $em->persist($annonce);
               
               $em->flush();
               
               for($i=1;$i<4;$i++)
               {                   
                   if($photo=$this->get('session')->get('photo'.$i))
                   {
      //                 $em->persist($photo);
                       $annonce->addPhoto($photo); 
                       $photo->setAnnonce($annonce);
                       $em->persist($photo);
                   }
                    
               }
               $em->flush();
               $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('annonce.done'));
           }     
	}
        if($ph1 = $this->get('session')->get('photo1')) {$photo1=$this->getParameter('app.blog_photos_URL').'/'.$ph1->getPicture(); }else{ $photo1=null;}
        if($ph2 = $this->get('session')->get('photo2')) {$photo2=$this->getParameter('app.blog_photos_URL').'/'.$ph2->getPicture(); }else{ $photo2=null;}
        if($ph3 = $this->get('session')->get('photo3')) {$photo3=$this->getParameter('app.blog_photos_URL').'/'.$ph3->getPicture(); }else{ $photo3=null;}       
        
        $form = $this->createForm(new AnnonceForm,$annonce);
        $email=$client->getDeliveryAddress()->getEmail();
        $form->get('email')->setData($email);
        $phone=$client->getDeliveryAddress()->getPhone();
        $form->get('phone')->setData($phone);
  
        return $this->get('templating')->renderResponse(
					'ClientBundle:Annonce:annonce.html.twig', array(
							'form'=>$form->createView(),
                                                        'photo1'=>$photo1,
                                                        'photo2'=>$photo2,
                                                        'photo3'=>$photo3,
					));

        
        }
       
         public function listAction(Request $request)
         {
             $annonces = array();
             
             $em = $this->get('doctrine')->getManager();
             $annonces = $em->getRepository('ClientBundle\Entity\Annonce')->findAll();
             
             $directory=$this->getParameter('app.blog_photos_URL');
             return $this->get('templating')->renderResponse(
                     'ClientBundle:Annonce:list.html.twig', array(
                         'directory' => $directory,
                         'annonces'=>$annonces,
                     ));
             
         }
        
        
        
        public function makeIdentifier(\ClientBundle\Entity\Client $client, $salt)
        {
            $a=substr($client->getFirstName(),0,1);
            $b=substr($client->getLastName(),0,3);
            $s=substr(password_hash($salt, PASSWORD_DEFAULT),0,5);
            
            return($a.$b.$salt);
        }
        
       
}