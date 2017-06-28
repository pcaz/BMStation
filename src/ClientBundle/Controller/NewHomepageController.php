<?php

namespace ClientBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use ClientBundle\Entity\Basket;
use ClientBundle\Entity\LineOrder;
use ClientBundle\PseudoClasses\PseudoProduct;

class NewHomepageController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function homepageAction()
	{
		
		
		// Get Symfony to interface with this existing session
		$session = new Session(new PhpBridgeSessionStorage());
		
		// symfony will now interface with the existing PHP session
		$session->start();
		
		
		
		// l'écran d'accueuil présente les catégories de produits
		//return new RedirectResponse($this->container->get('router')->generate('userchoosecategory'));
		
		$em=$this->get('doctrine')->getManager();
		$products=$em->getRepository('AppBundle\Entity\Product')->findAll();
		$images=$this->getParameter('app.images_URL');
		return $this->container->get('templating')->renderResponse(
				'ClientBundle:Vitrine:newhomepage.html.twig', array(
						'images'=>$images,
						'products'=>$products,
				));
	}
	
	/**
	 * @Route("/chooseLangage/{langue}", name="chooseLangage")
	 */
	public function chooseLangageAction($langue = null)
	{
		
		if($langue != null)
		{
			
			// On enregistre la langue en session
			$this->container->get('session')->set('_locale',$langue);
		}
		
		// on tente de rediriger vers la page d'origine
		$url = $this->get('request')->headers->get('referer');
		if(empty($url)) {
			$url = $this->container->get('router')->generate('homepage');
		}
		return new RedirectResponse($url);
	}
	
	
	public function setProductAction()
	{
		// on ajoute les produits sélectionnés
		// ceci est géré par Ajax
		
		$prods=array();
		$prod=array();
		
	
		$request = $this->container->get('request');
		$products = $request->request->get('products');
	
		// on regarde d'abord si on a reçu quelque chose
		if($products==""){
			$this->get('session')->set('products',array());
			return;
		}
		// on teste le reset
		if($products=="0"){
			$prod[0]=new Product(); // alors on met un élément vide à la place
			$this->get('session')->set('products',$prod);
			return;
		}
		
		// bon, on est dans le cas normal et on doit interpréter la chaîne [x,x,x...]
		
		for($i=0;strstr($products,',',true)<>false;$i++) {
			$str=strstr($products,',',true);
			$prods[$i]=$str;
			$prods=strstr($prods,',',false);
			$products=substr($products,1);
		}
		//le derner item
		$prods[$i]=$products;
		$em = $this->get('doctrine')->getEntityManager();
		$prod=array();
		for($i=0;$i<count($prods);$i++){
			$prod[$i]=$em->find('AppBundle:Product',$prods[$i]);
		}
		
		//	    $this->get('session')->remove('models');
		$this->get('session')->set('products', $prod);
        return;
		
		
	}
	
	public function basketAction()
	{
		// d'abord, on regarde si on a affaire à un client connecte
		if($this->get('security.authorization_checker')->isGranted('ROLE_USER')){
			
			$em=$this->get('doctrine')->getManager();
			$user=$this->getUser();
			$client=$em->getRepository('ClientBundle:Client')->findByEmail($user->getEmail());
			
			
			if(!$client){  // un administrateur non client essaie de se connecter
				return $this->render('ClientBundle:Basket:denied.html.twig',
						array(
								
						));
			}
			
			$basket=new Basket();
			$basket->setClient($client);
			$products=$this->get('session')->get('products');
			
			for($i=0;$i<count($products);$i++)
			{
				$lineOrder=new LineOrder();
				$lineOrder->setBasket($basket);
				$prod=$em->find('AppBundle:Product',$products[$i]->getId());
				$lineOrder->setProduct($prod);
				$products[$i]=new PseudoProduct($prod,$em);
				$products[$i]->setCommand(1);
				$em->persist($lineOrder);
				
			}
			
			$em->persist($basket);
			$em->flush();
			
			$images=$this->getParameter('app.images_URL');
			
			return $this->get('templating')->renderResponse(
					'ClientBundle:Order:summary.html.twig', array(
							'images'=>$images,
							'products'=>$products,
							'basket'=> $basket,
					));
			
		
	}
	
			return $this->render('ClientBundle:Basket:denied.html.twig',
				array(
						
				));
	}
	
	
}

