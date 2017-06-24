<?php
namespace ClientBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use AppBundle\Entity\Model;
use AppBundle\Form\ProductForm;
use AppBundle\Form\Common\SeriesModelForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\AppBundle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Application;
use ClientBundle\Service\Prod;


class ProductController extends Controller{

	
	
	
	public function choiceAction()
	{
		
		$em= $this->get('doctrine')->getManager();
		$categories = $em->getRepository('AppBundle:Category')->findAll();
		$series = $em->getRepository('AppBundle:Series')->findAll();
		$models = $em->getRepository('AppBundle:Model')->findAll();
		$images=$this->getParameter('app.images_URL');
		$this->get('session')->remove('category');
		$this->get('session')->remove('models');
		return $this->get('templating')->renderResponse('ClientBundle:Product:ProductHomePage.html.twig',
				array(
				 'images'	=> $images,	
				 'categories'=> $categories,
				 'series'=> $series,		
				 'models' => $models		
				));
	}
	
	
	public function listAction($id=null)
	{
	
		$em= $this->container->get('doctrine')->getManager();
		$products = $em->getRepository('AppBundle:Product')->findByCategory($id);

		$category=$this->get('session')->get('category');
		$images=$this->container->getParameter('app.images_URL');
		$products = $this->get('knp_paginator')->paginate($products,$this->get('request')->query->get('page', 1),20);
		//.'/'.$cat->getImage();;
		return $this->container->get('templating')->renderResponse(
				'ClientBundle:Product:list.html.twig',
				array(	'images' => $images,
						'category' => $category,
						'products' => $products,
				));
		
	}
		

	
	public function modelsearchAction(){
	// gestion des séries
	
		
		$form = $this->get('form.factory')->create(new SeriesModelForm());
		$service=new \ClientBundle\Service\ProductService($this);
		$models=$service->modelSearch();
		return $this->container->get('templating')->renderResponse(
				'ClientBundle:Common:SeriesModel.html.twig', array(
						'form'=>$form->createView(),
						'models'=>$models,
				));
	
	}
	
	public function setModelAction(){
		// on ajoute des models sélectionnés
		// ceci est géré par Ajax
		$service=new \ClientBundle\Service\ProductService($this);
		$model=$service->setModel();
	
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Common:SeriesModel.html.twig', array(
						'models'=>$models,
				));
		
	}
	
	public function setCategoryAction(){
		
		$service=new \ClientBundle\Service\ProductService($this);
		$res=$service->setCategory();
				
		$models =$em->getRepository('AppBundle:Model')->findAll();
		return $this->container->get('templating')->renderResponse(
				'ClientBundle:Common:SeriesModel.html.twig', array(
						'models'=>$models,
				));
	}
	
	public function setAction(){
		
		$service=new \ClientBundle\Service\ProductService($this);
		    $res=$service->set();
		    
		    $catResult=$res['category'];
		    $modResult=$res['model'];
		    $result=$res['result'];
		    
		    $images=$this->container->getParameter('app.images_URL');
		     
		    return $this->get('templating')->renderResponse(
		     		'ClientBundle:Product:result.html.twig', array(
		     				'images'=>$images,
		     				'category'=>$catResult,
		     				'model'=>$modResult,
		     				'count'=>count($result),
		     				'products'=>$result,
		     		));
		 
	
		
	}
}
	