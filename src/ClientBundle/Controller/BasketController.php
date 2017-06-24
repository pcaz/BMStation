<?php

namespace ClientBundle\Controller;

use ClientBundle\Entity\Client;
use ClientBundle\Entity\Address;
use ClientBundle\Entity\Historic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ClientBundle\Form\NameBasketForm;
use Symfony\Component\HttpFoundation\RedirectResponse;


class BasketController extends Controller
{
	public function homeAction(){
		
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_USER')){
			
			$em=$this->get('doctrine')->getManager();
			$user=$this->getUser();
			$client=$em->getRepository('ClientBundle:Client')->findByEmail($user->getEmail());
			
			
			if(!$client){  // un administrateur non client essaie de se connecter
				return $this->render('ClientBundle:Basket:denied.html.twig',
						array(
								
						));
			}
			$baskets=$em->getRepository('ClientBundle\Entity\Basket')->findByClient($client);
			$result=array();
			foreach($baskets as $basket)
			{
				$res=array();
				$res['id']=$basket->getId();
				$res['name']=$basket->getName();
				$res['date']=$basket->getDateCreated();
				\array_push($result,$res);				
			}
			
			
			
			return $this->render('ClientBundle:Basket:basket.html.twig',
					array(
						'client'=>$client,
						'baskets'=>$result,	
							
					));
		}
		else{
			
			return $this->render('ClientBundle:Basket:denied.html.twig',
					array(
							
					));
		 }
	}
	
	public function setListAction(){
		
			
			$service=new \ClientBundle\Service\ProductService($this);
			$res=$service->set();
			
			$em=$this->get('doctrine')->getManager();
			$catResult=$res['category'];
			$modResult=$res['model'];
			$res=$res['result'];
			foreach($res as $id=>$product)
			{
				$result[$id]=new \ClientBundle\PseudoClasses\PseudoProduct($product, $em);
			}
			
			$csrf=$this->get('security.csrf.token_manager');
			$token=$csrf->refreshToken('basket');
			
			$images=$this->getParameter('app.images_URL');
			
			return $this->get('templating')->renderResponse(
					'ClientBundle:Basket:result.html.twig', array(
							'images'=>$images,
							'category'=>$catResult,
							'model'=>$modResult,
							'count'=>count($result),
							'products'=>$result,
							'token'=>$token,
					));
			
	}
	
	public function presentAction(){
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
	
	public function basketSetAction() {
		
		$values=$_POST;
		$token=$values['client']['_token'];
		if(!$this->isCsrfTokenValid('basket', $token)){
			//erreur de token
		}
		
		unset($values['client']);
		
		$basket=new \ClientBundle\Entity\Basket();
		$em=$this->get('doctrine')->getManager();
		$user=$this->getUser();
		$client=$em->getRepository('ClientBundle:Client')->findByEmail($user->getEmail());
		$basket->setClient($client);
		
		foreach($values as $product=>$quantity) {
			if($quantity != "") {
				$lineOrder=new \ClientBundle\Entity\LineOrder();
			    $lineOrder->setBasket($basket);
			    $product=$em->getRepository('\AppBundle\Entity\Product')->find($product);
			    $lineOrder->setProduct($product);
			    $lineOrder->setNumber($quantity);
			    $basket->addLineOrder($lineOrder);
			    $em->persist($lineOrder);			
			}
		}
		if(count($basket->getLineOrder()->toArray())==0)
		{
			return new RedirectResponse($this->get('router')->generate('user_basket_present'));
		}
		
		$em->persist($basket);
		$em->flush();
		 // ok, un nouveau panier est créé, l'utilisateur peut le sauvegarder, ou passer la commande.
		 
		 $prod=$basket->getLineOrder()->unwrap()->toArray();
		 $products=array();
		 
		 foreach($prod as $id=>$product) {
		 	$products[$id]=new \ClientBundle\PseudoClasses\PseudoProduct($product->getProduct(),$em);
		 	$products[$id]->setCommand($product->getNumber());
		 }
		 
		 $images=$this->getParameter('app.images_URL');
		 
		 return $this->get('templating')->renderResponse(
		 		'ClientBundle:Basket:basketEnd.html.twig', array(
		 				'images'=>$images,
		 				'products'=>$products,
		 				'basket'=> $basket,
	 		));
		 
	}
	
	public function nameAction(Request $request, $id=null){
		
		
		$em=$this->get('doctrine')->getManager();
		$basket=$em->getRepository('ClientBundle\Entity\Basket')->find($id);
		$form = $this->createForm(new NameBasketForm,$basket);
		
		if($request->getMethod()=='POST'){
			$form->handleRequest($request);
			if($form->isValid()){
				$em=$this->get('doctrine')->getManager();
				$basket=$em->getRepository('ClientBundle\Entity\Basket')->find($id);
				$basket->setName($form->get('name')->getData());
				$em->flush();
				
				
			}
		}
		else{
			return $this->get('templating')->renderResponse(
					'ClientBundle:Basket:nameBasket.html.twig', array(
							'form'=>$form->createView(),
					));
		}

		return new RedirectResponse($this->get('router')->generate('user_basket'));
	
	}
	
	public function basketGetAction(Request $request,$id=null){
		
		
		$em=$this->get('doctrine')->getManager();
		$basket=$em->getRepository('ClientBundle\Entity\Basket')->find($id);
		$lineOrders=$em->getRepository('ClientBundle\Entity\LineOrder')->findByBasket($id);
		
		
		foreach($lineOrders as $id=>$lineOrder) {
			$em->getRepository('AppBundle\Entity\Product')->find($lineOrder->getProduct()->getId());
			$products[$id]=new \ClientBundle\PseudoClasses\PseudoProduct($lineOrder->getProduct(),$em);		
		}
		
		
		$images=$this->getParameter('app.images_URL');
		
		return $this->get('templating')->renderResponse(
				'ClientBundle:Basket:basketEnd.html.twig', array(
						'images'=>$images,
						'products'=>$products,
						'basket'=> $basket,
				));	
	}
}
	