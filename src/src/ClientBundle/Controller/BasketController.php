<?php

namespace ClientBundle\Controller;

use ClientBundle\Entity\Client;
use ClientBundle\Entity\Address;
use ClientBundle\Entity\Historic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BasketController extends Controller
{
	public function homeAction(){
		
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_USER')){
			
			$em=$this->get('doctrine')->getManager();
			$user=$this->getUser();
			$client=$em->getRepository('ClientBundle:Client')->findByEmail($user->getEmail());
			$basket=$this->get('session')->get($user->getEmail());
			
			if(!$client){  // un administrateur non client essaie de se connecter
				return $this->render('ClientBundle:Basket:denied.html.twig',
						array(
								
						));
			}
			
			return $this->render('ClientBundle:Basket:basket.html.twig',
					array(
						'client'=>$client,
						'basket'=>$basket,	
							
					));
		}
		else{
			
			return $this->render('ClientBundle:Basket:denied.html.twig',
					array(
							
					));
		 }
	}
	
	public function setAction(){
		
			
			$service=new \ClientBundle\Service\ProductService($this);
			$res=$service->set();
			
			$catResult=$res['category'];
			$modResult=$res['model'];
			$result=$res['result'];
			
			$images=$this->container->getParameter('app.images_URL');
			
			return $this->get('templating')->renderResponse(
					'ClientBundle:Basket:result.html.twig', array(
							'images'=>$images,
							'category'=>$catResult,
							'model'=>$modResult,
							'count'=>count($result),
							'products'=>$result,
					));
			
	}
	
	public function commandAction($id=null){
		$em=$this->get('doctrine')->getManager();
		$categories=$em->getRepository('AppBundle:Category')->findAll();
		$series=$em->getRepository('AppBundle:Series')->findAll();
		$models=$em->getRepository('AppBundle:Model')->findAll();
		$images=$this->getParameter('app.images_URL');
		return $this->render('ClientBundle:Basket:BasketHomePage.html.twig',
				array(
				'categories'=>$categories,
				'series'=>$series,
				'models'=>$models,
				'images'=>$images,		
						
				));
	}
	
	
}
	