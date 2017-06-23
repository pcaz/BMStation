<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\AdministratorForm;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use AppBundle\Service\BackupMySQL;
use Symfony\Component\Process\Process;
use AppBundle\Form\EditAdministratorForm;



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
}