<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Model;
use AppBundle\Form\ModelForm;
use AppBundle\Form\ModelSearchForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ModelController extends Controller
{
	/**
	 * @Route("/model/", name="model_list")
	 */
	public function listAction()
	{
	
		$em= $this->container->get('doctrine')->getManager();
		$models =$em->getRepository('AppBundle:Model')->findById();
		$form = $this->container->get('form.factory')->create(new ModelSearchForm());
		$models = $this->get('knp_paginator')->paginate($models,$this->get('request')->query->get('page', 1),20);
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Model:frame.html.twig', array(
						'models'=>$models, 
					     'form'=>$form->createView(),
						
				));
	}
	/**
	 * @Route("/model/add", name="model_add")
	 * @Route("/model/modify", name="model_modify")
	 */
	public function editAction($id=null)
	{

		$title='';
		$request = $this->get('request');
	    $flashBag=$request->getSession()->getFlashBag();
	    
		$em=$this->get('doctrine')->getManager();

		if(isset($id))
		{
			$title=$this->get('translator')->trans('modify_model');
			$model=$em->find('AppBundle:Model',$id);

			if(!$model)
			{
				$flashBag->add('error', $this->get('translator')->trans('no_model'));
			}
		}
		else
		{
			$title=$this->container->get('translator')->trans('add_model');
			$model = new Model();
		}

		$form = $this->get('form.factory')->create(new ModelForm(), $model);
		

		if ($request->getMethod() == 'POST')
		{
			$form->handleRequest($request);

			if ($form->isValid())
			{
				$em = $this->container->get('doctrine')->getManager();
				// si je suis en ajout, et que le modele existe déjà, il y a erreur !
				if(!$id and $em->getRepository('AppBundle:Model')->findOneBy(
						array('name'=>$model->getName())))
				{
					$flashBag->add('error',$this->get('translator')->trans('existing_model'));
				}
				// sinon, j'enregistre!
				else
				{
					$em->persist($model);
					$em->flush();
					$flashBag->add('success',$this->get('translator')->trans('model_added'));
				}
//				return new RedirectResponse($this->container->get('router')->generate('model_list'));
			}

		}
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Model:edit.html.twig',
				array(
						'form' => $form->createView(),
						'title' => $title
				));
	}
	public function deleteAction($id)
	{
		$em = $this->container->get('doctrine')->getEntityManager();
		$model = $em->find('AppBundle:Model', $id);
	
		if (!$model)
		{
			throw new NotFoundHttpException("model_not_found");
		}
	
		$em->remove($model);
		$em->flush();
	
	
		return new RedirectResponse($this->get('router')->generate('model_list'));
	}
	
	public function modelSearchAction()
	{
		$title='';
		$models=null;
		$request = $this->get('request');
	
	
		$keyword = '';
		$keyword = $request->request->get('keyword');
	
		$form = $this->get('form.factory')->create(new ModelSearchForm());
		$em = $this->get('doctrine')->getEntityManager();
	
		if($keyword != '')
		{
			$models = $em->getRepository('AppBundle:Model')->findByKeyword($keyword);
		}
		else
		{
			$models = $em->getRepository('AppBundle:Model')->findAll();
		}
		$models = $this->get('knp_paginator')->paginate($models,$this->get('request')->query->get('page', 1),20);
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Model:list.html.twig', array(
						'models'=>$models,
						'title'=>$title,
						'form' => $form->createView()
				));
	}
	
	public function getProductAction($id=null){
//TODO getProduct...trouver les produits pour un modèle..n'importe quoi		

		$em = $this->container->get('doctrine')->getManager();
		$model = $em->getRepository('AppBundle:Model')->find($id);
		
		$prod=$model->getProduct();
		$products=$prod->getValues();
		$prd=array();
		for($i=0,$j=0;$i<count($products);$i++){
			    $val=$products[$i]->getCategory()->getId();
			    if($val==$id){
			    	$prd[$j]=$products[$i];
			    	$j++;
			    }
			    	
			
		}
	
		$cat=
	    
		
		$images=$this->container->getParameter('app.images_URL');
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Product:list.html.twig',
				array(	'images' => $images,
						'category' => $cat,
						'products' => $prd,
				));
	}
}