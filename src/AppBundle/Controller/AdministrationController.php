<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\AdministratorForm;
use AppBundle\Form\AddSlideshowForm;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use AppBundle\Service\BackupMySQL;
use Symfony\Component\Process\Process;
use AppBundle\Form\EditAdministratorForm;
use AppBundle\Form\SlideshowForm;
use AppBundle\PseudoClasses\Slideshow;



class AdministrationController extends Controller{
	public function homepage(){
		
	}
	
	
	public function createAdminAction(){
		
		$user=new User();
		$form=$this->get('form.factory')->create(new AdministratorForm(), $user);;
		$request = $this->get('request');
		                  
		if($request->getMethod()=='POST'){
			$form->handlerequest($request);
			if($form->isValid()){
				$manipulator = $this->get('fos_user.util.user_manipulator');
				$usr=$manipulator->create($user->getUsername(), $user->getPassword(), $user->getEmail(),true , false);
				$manipulator->addRole($user->getUsername(), 'ROLE_ADMIN');
				$userManager = $this->get('fos_user.user_manager');
				$userManager->updateUser($usr);
				$request->getSession()->getFlashBag()->add('success',$this->get('translator')->trans("record_done"));
				$form=$this->get('form.factory')->create(new AdministratorForm());
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Administration:administrator.html.twig', array(
								'form'=>$form->createView(),
								'title'=>'createadmin',
						));
				
			 }
			else {
				// la form est invalide
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Administration:administrator.html.twig', array(
								'form'=>$form->createView(),
								'title'=>'createadmin',
						));
			}
		}
		else {
			// c'est un gets
			return $this->container->get('templating')->renderResponse(
					'AppBundle:Administration:administrator.html.twig', array(
							'form'=>$form->createView(),
							'title'=>'createadmin',
					));
			
		}
		
	}
	
	public function listAdminAction() {
		
		$return=array();
		
		    $return=$this->listAdmin();
		    
			$return = $this->get('knp_paginator')->paginate($return,$this->get('request')->query->get('page', 1),10);
			
			return $this->container->get('templating')->renderResponse(
					'AppBundle:Administration:listAdministrators.html.twig', array(
							'admins'=>$return,
					));
		}
		
	public function editAdminAction($id=null){
		
		$em=$this->get('doctrine')->getManager();
		$user=$em->find('UserBundle\Entity\User',$id);
		$olduser=$user;
		$form=$this->get('form.factory')->create(new EditAdministratorForm(), $user);
		$request = $this->get('request');
		 if ($request->getMethod()=="POST"){
		 	$form->handlerequest($request);
		 	if($form->isValid()){
		 		if($olduser->getEmail()!=$user->getEmail() &&
		 		 $em->getRepository('UserBundle\Entity\User')->findByEmail($user->getEmail())){
		 			$request->getSession()->getFlashBag()->add('error','L\'email existe déja');
		 		}
		 		else{
		 			
		 		if($password=$form->getData()->getPassword()){
		 			$username=$em->find('UserBundle:User',$id)->getUserName();
		 			$manipulator = $this->get('fos_user.util.user_manipulator');
		 			$manipulator->changePassword($username, $password);
		 			
		 		}
		 			
		 			
		 		$em->persist($user);
		 		$em->flush();
		 		
		 		
		 		$return=$this->listAdmin();
		 		
		 		$return = $this->get('knp_paginator')->paginate($return,$this->get('request')->query->get('page', 1),10);
		 		
		 		return $this->container->get('templating')->renderResponse(
		 				'AppBundle:Administration:listAdministrators.html.twig', array(
		 						'admins'=>$return,
		 				));
		 	
		   }
		 	}

		   return $this->container->get('templating')->renderResponse(
		   		'AppBundle:Administration:editAdministrator.html.twig', array(
		   				'form'=>$form->createView(),
		   				'title'=>'editadmin',
		   				'id'=>$id,
		   		));
		 }
		 else {
		 	
		 	// c'est un get
		 	return $this->container->get('templating')->renderResponse(
		 			'AppBundle:Administration:editAdministrator.html.twig', array(
		 					'form'=>$form->createView(),
		 					'title'=>'editadmin',
		 					'id'=>$id,
		 			));
		 }
		 
		 
		
	
	}
	
	public function deleteAdminAction($id=null){
		
		$em=$this->get('doctrine')->getManager();
		
		if($user=$em->find('UserBundle:User',$id))
		{
			$em->remove($user);
			$em->flush();
		}
		
		$return=$this->listAdmin();
		$return = $this->get('knp_paginator')->paginate($return,$this->get('request')->query->get('page', 1),10);
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Administration:listAdministrators.html.twig', array(
						'admins'=>$return,
				));
	}
	
	public function saveBaseAction(){

		// on gère l'ordre de sauvegarde des tables pour gérer les verrous.
		// ATTENTION...seules ces tables seront sauvegardées
		$tables=array(
		 'user'=>null,
		 'address'=>null,
		 'client'=>null,
		 'contact'=>null,
		 'historic'=>null,
		 'category'=>null,
		 'series'=>null,
		 'model'=>null,
		 'product'=>null,
		 'product_model'=>null
		 );
		
		$backup=new BackupMySQL(array(
				                    'host' => 'localhost',
									'username' => 'symfony',
									'passwd' => 'spleen',
									'dbname' => 'bmstation',
									'dossier' => 'uploads/bases/',
									'nom_fichier'=>'bmstation',
									'tables' => $tables,
								
				));
		$name=$backup->getFile();
		$file=$this->getParameter('app.URL').'/uploads/bases/'.$name;
		
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Administration:saveBase.html.twig', array(
						'title'=>'savebase',
						'url'=>$file,
				));
		
		
		
	}
	private function listAdmin(){
		
		$return=array();
		
		$em=$this->get('doctrine')->getManager();
		$users=$em->getRepository('UserBundle\Entity\User')->findAll();
		for($i=0, $j=0;$i<count($users);$i++){
			if($users[$i]->hasRole('ROLE_ADMIN') ||$users[$i]->hasRole('ROLE_SUPER_ADMIN') ){
				$return[$j]=$users[$i];
				$j++;
			}
		}
		return $return;
	}
	
	public function editSlideshowAction()
	{
		$images=array();
		$base=$this->getParameter('app.base');
		$source=$base.'/src/ClientBundle/Resources/views/slideshow.html.twig';
		
		
		$file=fopen($source,'r');
		
        while($line=fgets($file)){
        
        	$str=strstr($line,'src="');
        	$str=substr($str,5);
        	$str=strstr($str,'"',true);
        	$len=strlen($str);
        	$str=substr($str,0,$len);
        	
        	array_push($images,$str);
        }
        
        return $this->container->get('templating')->renderResponse(
        		'AppBundle:Administration:editSlideshow.html.twig', array(
        				'title'=>'editslideshow',
        				'images'=>$images,
        		));
        
		
	}
	
	public function deleteSlideshowAction($id=null)
	{
		
		$lines=array();
		$images=array();
		$base=$this->getParameter('app.base');
		$source=$base.'/src/ClientBundle/Resources/views/slideshow.html.twig';
		$dest=$base.'/app/cache/newfile.php';
		
		// d'abord, on revient aux indices de tableau de 0 à n
		if($id!=null) $id--;
		
		$request = $this->get('request');
		$flashbag=$request->getSession()->getFlashBag();
		
		$file=fopen($source,'r');
		$count=0;
		// on compte les entrées
		while($line=fgets($file)){
	        $count++;		
			array_push($lines,$line);
			$str=strstr($line,'src="');
			$str=substr($str,5);
			$str=strstr($str,'"',true);
			$len=strlen($str);
			$str=substr($str,0,$len);
			array_push($images,$str);
		}
	
		
		// on ne peut pas descendre au dessous de trois entrées
		if($count<4)
		{
			$flashbag->add('error','noless3');
			
		}
		else 
		{	
		
		//d'abord, on efface l'image.
			$root=$this->getParameter('app.root');
			unlink($root.$images[$id]);
			
       // on copie le fichier moins la ligne
			array_splice($lines, $id,1);
			array_splice($images,$id,1);
			
			fclose($file);
			
			$file=fopen($dest,'w');
			
			for($i=0;$i<count($lines);$i++)
			{
				fputs($file,$lines[$i]);
			}
			
			
			fclose($file);
			
			unlink ($source);
			rename($dest,$source);
			
		}
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Administration:editSlideshow.html.twig', array(
						'title'=>'editslideshow',
						'images'=>$images,
				));
  }
  
  public function addSlideshowAction()
  {
  	$lines=array();
  	$images=array();
  	$newimages=array();
  	$base=$this->getParameter('app.base');
  	$source=$base.'/src/ClientBundle/Resources/views/slideshow.html.twig';
  	$dest=$base.'/app/cache/newfile.php';
  	
  	$image='';
  	$format=array('jpeg', 'png', 'gif');
  	
  	      
  	$request = $this->get('request');
  	$flashbag=$request->getSession()->getFlashBag();
  	
  	$file=fopen($source,'r');
  	$count=0;
  	// on compte les entrées
  	while($line=fgets($file)){
  		$count++;
  		array_push($lines,$line);
  		$str=strstr($line,'src="');
  		$str=substr($str,5);
  		$str=strstr($str,'"',true);
  		$len=strlen($str);
  		$str=substr($str,0,$len);
  		array_push($images,$str);
  	}
  	fclose($file);
  	
  	$slideshow=new Slideshow();
  	$form=$this->get('form.factory')->create(new AddSlideshowForm(), $slideshow);
  	if($request->getMethod()=='POST')
  	{
  		$form->handleRequest($request);
  		if($form->isValid())
  		{
  			$rang=$slideshow->getRang();
  		
  		
  		  // on ne peut pas rentrer plus de dix mages
  		  if($count==10){
  		  	$flashbag->add('error','nomore10');
  		  	return $this->container->get('templating')->renderResponse(
  		  			'AppBundle:Administration:editSlideshow.html.twig', array(
  		  					'title'=>'editslideshow',
  		  					'images'=>$images,
  		  			));
  		  }
  			
  			
   		    $image=$slideshow->getImage();
   		    
  			// On vérifie l'extension du fichier (à partir du type mime)
  			if(in_array($image->guessExtension(), $format)){
  				// On génère un nom unique avant de sauvegarder le fichier
  				$fileName = md5(uniqid()).'.'.$image->guessExtension();
   				// On déplace le fichier vers le répertoire Uploads
  				$destDir= $this->container->getParameter('app.root').'/uploads/slideshow/'; 
   				$image->move(
  						  $destDir,
  						$fileName
  						);
  				// Il faut effacer le fichier de l'image precedente	
  			}
  			else{
  				// le format n'est pas accepté
  				$flashbag->add('error',$this->container->get('translator')->trans('bad_format'));
  				return $this->container->get('templating')->renderResponse(
  						'AppBundle:Administration:editSlideshow.html.twig', array(
  								'title'=>'editslideshow',
  								'images'=>$images,
  						));
  			} 
  			$longFileName='/uploads/slideshow/'.$fileName;
  			$line='<li><img src="'.$longFileName.'" alt=""/></li>';
  			$file=fopen($dest,'w');
  			
  			for($i=0;$i<$rang-1;$i++)
  			{
  				fputs($file,$lines[$i]);
  				array_push($newimages, $images[$i]);
  			}
  			fputs($file,$line);
  			array_push($newimages,$longFileName);
  			
  			for($i=$rang-1;$i<count($lines);$i++)
  			{
  				fputs($file,$lines[$i]);
  				array_push($newimages, $images[$i]);
  			}
  			
  			fclose($file);
  			
  			unlink ($source);
  			rename($dest,$source);
  
  			return $this->container->get('templating')->renderResponse(
  					'AppBundle:Administration:editSlideshow.html.twig', array(
  							'title'=>'editslideshow',
  							'images'=>$newimages,
  					));
  			
  		
  		}
  		else 
  		{// le format est invalide
  			
  			return $this->get('templating')->renderResponse(
  					'AppBundle:Administration:addSlideshow.html.twig', array(
  							'form'=>$form->createView(),
  							'title'=>'addlideshow',
  							));
   	    }
  	}
     else
     { // on est en get
   //  	$csrf=$this->get('security.csrf.token_manager');
   //  	$token=$csrf->refreshToken('slideshow');
   
     	
     	return $this->get('templating')->renderResponse(
     			'AppBundle:Administration:addSlideshow.html.twig', array(
     					'form'=>$form->createView(), 
     					'title'=>'addlideshow',
     					'images'=>$images,
     // 					'token'=>$token,
     			));
     }
     
  }
  
  
}