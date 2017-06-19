<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use AppBundle\Entity\Model;
use AppBundle\Form\ProductForm;
use AppBundle\Form\Common\SeriesModelForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\AppBundle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\ChoiceForm;


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
		return $this->get('templating')->renderResponse('AppBundle:Product:ProductHomePage.html.twig',
				array(
				 'images'	=> $images,	
				 'categories'=> $categories,
				 'series'=> $series,		
				 'models' => $models		
				));
	}
	
	public function categorylistAction()
	{
	
		// on commence par fair choisir la ctégorie de produit
	
		$em= $this->container->get('doctrine')->getManager();
		$categories = $em->getRepository('AppBundle:Category')->findAll();
		$home=$this->container->getParameter('app.URL');
		$images=$this->container->getParameter('app.images_URL');
  
	
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Product:choosecategory.html.twig',
				array(
						'home'=>$home,
						'images' => $images,
						'categories' => $categories,
				));
	
	}
	public function listAction($id=null)
	{
	
		$em= $this->container->get('doctrine')->getManager();
		$products = $em->getRepository('AppBundle:Product')->findByCategory($id);

		$cat = $em->find('AppBundle:Category',$id);
		$this->container->get('session')->set('category', $id);
		$images=$this->container->getParameter('app.images_URL');
		$products = $this->get('knp_paginator')->paginate($products,$this->get('request')->query->get('page', 1),20);
		//.'/'.$cat->getImage();;
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Product:list.html.twig',
				array(	'images' => $images,
						'category' => $cat,
						'products' => $products,
				));
		
	}
		

	public function modifyAction($id=null) {
		$message='';
        
        
		
		
		
		if(!$id)
			return new RedirectResponse($this->container->get('router')->generate('product_add'));	

	    $val=$this->getParameter('app.images_directory');    
    	$title='modify_product';
    	$em = $this->container->get('doctrine')->getManager();
    	$product= $em->find('AppBundle:Product',$id);
    	$category=$product->getCategory();
    	$series =$em->getRepository('AppBundle:Series')->findAll();
    	$models =$em->getRepository('AppBundle:Model')->findAll();
    	$request=$this->container->get('request');
    	$flashbag=$request->getSession()->getFlashBag();
    	
		


		if ($request->getMethod() == 'GET'){
			$title='modify_product';
			$product=$em->find('AppBundle:Product',$id);
			$form=$this->container->get('form.factory')->create(new ProductForm(),$product);
			$mods=$product->getModel();
			$model=$mods->getValues();
			// on commence par oter les modeles précédemment sélectionnés
			$this->get('session')->remove('models');
			// puis on ajoute les modèles actuels
			$this->get('session')->set('models',$model);
			$models =$em->getRepository('AppBundle:Model')->findAll();
//			$series =$em->getRepository('AppBundle:Series')->findAll();
			//model est un tableau de models sélectionnés
			// on est en get, on affiche le produit actuel
			$models=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($model,$models);
			
//			$this->container->get('session')->set('models',$model);
			$images=$this->container->getParameter('app.images_URL');
			
			return $this->container->get('templating')->renderResponse(
					'AppBundle:Product:edit.html.twig',
					array(
							'form' => $form->createView(),
							'message' => $message,
							'title' => $title,
							'images' => $images,
							'series' => $series,
							'models'=> $models,
							'category' => $category,
							'product'=>$product,
					));
		}
		// on est en modification et on a enregistré un nouveau produit
		$form=$this->container->get('form.factory')->create(new ProductForm(),$product);
        $form->handleRequest($request);  
        $var=$this->getErrorMessages($form);
    	if ($form->isValid()){
				// on est en modification
    	
    	
      
    	$models1=$this->get('session')->get('models');
    	$mods=$product->getModel();
    	$models=$mods->getValues();
    
    	// on test d'abord si on a eu un reset
    	// si il n'y a pas de models présent, le tableau est vide
    	if(count($models1)!=0 && $models1[0]->getId()==0){
    
    	$product=$em->find('AppBundle:Product',$id);
    	$models=$product->getModel();
    	$models=$models->toArray();
    	$val=$em->getRepository('AppBundle:Model')->findAll();	
    	$val=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($models,$val);
    		
 //   	$this->get('session')->set('models',$models);
    	$images=$this->getParameter('app.images_URL');
    	$form = $this->container->get('form.factory')->create(new ProductForm(), $product);
    	
    	return $this->container->get('templating')->renderResponse(
    				'AppBundle:Product:edit.html.twig',
    				array(
    						'form' => $form->createView(),
    						'message' => $message,
    						'title' => $title,
    						'images' => $images,
    						'series' => $series,
    						'models'=> $val,
    						'category' => $category,
    						'product'=>$product,
    				));
    		}
				
    		if(!$em->find('AppBundle:Product',$id)) {
						$flashbag->add($this->container->get('translator')->trans('no_product'));
			    } 		
		else{
			// on est en modification
		$title='modify_product';
				
		// on gère l'image qui a déja été uploadée
	    if ($product->getImage()){
	    	$getImage=$this->container->get("app.image");
	    	$oldImage=$em->find('AppBundle:Product',$id)->getImage();
	    	if($oldImage) {$getImage->removeImage($oldImage);
	    }
		
		$val=$getImage->uploadImage($product->getImage());
		$product->setImage($val);
	    }
		
		
//		$em->persist($product->getCategory());
// on s'occupe des modèles
// product->getModel() contient les models actuellement stockés dans l'entité
// $this->get('session')->get('models') contient les modèles nouvellement choisit (merci modelsetAction)


// $product vient avec des modèles erronés, on chosit de repartir propre avec
// un nouveau produit $prod
		$models1=$this->get('session')->get('models');
	    $prod=$em->find('AppBundle:Product',$id);
	    $models=$prod->getModel();
		$models=$models->toArray();
		$prod=$em->find('AppBundle:Product',$id);
		$prod->setName($product->getName());
		$prod->setDescription($product->getDescription());
		$prod->setComment($product->getComment());
		$prod->setPrice($product->getPrice());
		$prod->setDisponibility($product->getDisponibility());
		$cat=$this->container->get('session')->get('category');
		$prod->setCategory($em->find('AppBundle:Category',$cat));
		// si on a modifié l'image, on la stocke
		if($product->getImage()){
		$prod->setImage($product->getImage());
		}
		$prods=$em->find('AppBundle:Product',$id);
		$mod=$prods->getModel();
		$mods=$mod->toArray();
		for($i=0;$i<count($mods);$i++){
			$prod->addModel($mods[$i]);
		}
		$models=$prod->getModel();
		$models=$models->toArray();
		
		$product=null;			
		//d'abord, on regarde si on a entré des modèles ou si il en avait déja
		if(count($models)<>0) {
		// on commence va d'abord retirer tous les ancien modeles
 			for($i=0;$i<count($models);$i++){
				$prod->removeModel($models[$i]);
			}
	
			
			$em->flush();
		}
		if(count($models1)<>0){
		// puis on ajoute les nouveaux(peut être les mêmes)
			for($i=0;$i<count($models1);$i++) {
				$val=$em->find('AppBundle:Model',$models1[$i]);
				$prod->addModel($val);
			}


		}

		
		$em->persist($prod);
		$em->flush();
		
		$flashbag->add('success', $this->container->get('translator')->trans('modification_done'));
		$title=$this->get('translator')->trans($title);
		$images=$this->getParameter('app.images_URL');
		$cat=$this->get('session')->get('category');
		$category=$em->find('AppBundle:Category',$cat);
    	$model=$this->get('session')->get('models');
//		$series=$this->get('session')->get('serie');
    	$series =$em->getRepository('AppBundle:Series')->findAll();
		}
		$models=$em->getRepository('AppBundle:Model')->findAll();
		if($model){
		$models=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($model,$models);
		}
		
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Product:edit.html.twig',
				array(
						'form' => $form->createView(),
						'message' => $message,
						'title' => $title,
						'images' => $images,
						'series' => $series,
						'models'=> $models,
						'category' => $category,
						'product'=>$prod,
				));
		}
// la form postée n'est pas valide (contraintes)
		
		
		
		 $images=$this->container->getParameter('app.images_URL');
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Product:edit.html.twig',
				array(
						'form' => $form->createView(),
						'message' => $message,
						'title' => $title,
						'images' => $images,
						'series' => $series,
						'models'=> $models,
						'category' => $category,
						'product'=>$product,
				));
		
		

		
		
	}
	
	public function addAction(){
		
		
		$message="";
				
		$request = $this->container->get('request');
		$flashbag=$request->getSession()->getFlashBag();
		$em=$this->container->get('doctrine')->getManager();
		$form=$this->container->get('form.factory')->create(new ProductForm());
			
		if ($request->getMethod() == 'GET'){
			$title=$this->container->get('translator')->trans('add_product');
			$images=$this->container->getParameter('app.images_URL');
			$cat=$this->container->get('session')->get('category');
			$category = $em->find('AppBundle:Category',$cat);
			$series =$em->getRepository('AppBundle:Series')->findAll();
			$models =$em->getRepository('AppBundle:Model')->findAll();
			$product=new Product();
			return $this->container->get('templating')->renderResponse(
					'AppBundle:Product:edit.html.twig',
					array(
							'form' => $form->createView(),
							'message' => $message,
							'title' => $title,
							'images' => $images,
							'series' => $series,
							'category' => $category,
							'models' => $models,
							'product'=>$product,
							
					));
		}
		
		$form->handleRequest($request);
		if($form->isValid()){
			// on verifie d'abord si ce n'est pas un res->et
			$mod=$this->get('session')->get('models')[0];
			if( ($mod == null) || ($mod instanceof \AppBundle\Entity\Model) && $mod->getId()==0){
			
				$product=new Product;;
				
				$this->get('session')->remove('models');
				$images=$this->getParameter('app.images_URL');
				$form = $this->container->get('form.factory')->create(new ProductForm(), $product);
				$models =$em->getRepository('AppBundle:Model')->findAll();
				$series=$series =$em->getRepository('AppBundle:Series')->findAll();
				$cat=$this->get('session')->get('category');
				$category=$em->getRepository('AppBundle:Category')->find($cat);
				$title="addproduct";
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'message' => $message,
								'title' => $title,
								'images' => $images,
								'series' => $series,
								'models'=> $models,
								'category' => $category,
								'product'=>$product,
						));
			}
			
			
			$prod = $form->getData();
			
			$validator=$this->container->get('validator');
			if(!$this->container->get('app.validate_product')->validate($validator,$prod, $flashbag))
			{
				$category=$this->container->get('session')->get('category');
				$models=$this->container->get('session')->get('models');
				$serie=$this->container->get('session')->get('serie');
				$title=$this->container->get('translator')->trans('add_product');
				$images=$this->container->getParameter('app.images_URL');
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'message' => $message,
								'title' => $title,
								'images' => $images,
								'series' => $serie,
								'models'=> $models,
								'category' => $category,
								'product'=>$prod,
						));
						
			}
				
			// tout va bien, la form est valide et validée
			// on vérifie que le nom du produit n'existe pas encore
			if($em->getRepository('AppBundle:Product')->findByName($prod['name'])){
				$flashbag->add($this->get('translator')->trans('existing_product'));
				$category=$this->container->get('session')->get('category');
				$models=$this->container->get('session')->get('models');
				$serie=$this->container->get('session')->get('serie');
				$title=$this->container->get('translator')->trans('add_product');
				$images=$this->container->getParameter('app.images_URL');
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'message' => $message,
								'title' => $title,
								'images' => $images,
								'series' => $serie,
								'models'=> $models,
								'category' => $category,
								'product'=>$prod,
						));
			}
						
			$product=new Product;
			$product->setName($prod['name']);
			$product->setDescription($prod['description']);
			$product->setComment($prod['comment']);
			$product->setPrice($prod['price']);
			$product->setDisponibility($prod['disponibility']);
			$cat=$em->find('AppBundle:Category',$this->get('session')->get('category'));
			$product->setCategory($cat);
			// on s'occupe des modèles
			// $this->get('session')->get('models') contient les modèles nouvellement choisit (merci modelsetAction)
			
			$models1=$this->get('session')->get('models');
			
			if(count($models1)<>0){
				// puis on ajoute les nouveaux(peut être les mêmes)
				for($i=0;$i<count($models1);$i++) {
					$val=$em->find('AppBundle:Model',$models1[$i]);
					$product->addModel($val);
				}
			
			
			}
			
			
			// ok, on enregistre
				$em->persist($product);
				$em->flush();
				$flashbag->add('succes', $this->get('translator')->trans('record_done'));
				$models=$em->getRepository('AppBundle:Model')->findAll();
				$models=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($models1,$models);
                $category=$product->getCategory();	
                $serie=$this->container->get('session')->get('serie');
				$title=$this->container->get('translator')->trans('add_product');
				$images=$this->container->getParameter('app.images_URL');
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'message' => $message,
								'title' => $title,
								'images' => $images,
								'series' => $serie,
								'models'=> $models,
								'category' => $category,
								'product'=>$product,
						));
				
		} 
				//erreur 
				$cat=$this->container->get('session')->get('category');
				$category = $em->find('AppBundle:Category',$cat);
				$models=$this->container->get('session')->get('models');
				$serie=$this->container->get('session')->get('serie');
				$title=$this->container->get('translator')->trans('add_product');
				$images=$this->container->getParameter('app.images_URL');
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'message' => $message,
								'title' => $title,
								'images' => $images,
								'series' => $serie,
								'models'=> $models,
								'category' => $category,
								'product'=>$product,
						));
	}
	
	
	public function deleteAction($id){
		$em = $this->container->get('doctrine')->getEntityManager();
		$product = $em->find('AppBundle:Product', $id);
	
		if (!$product)
		{
			throw new NotFoundHttpException("product_not_found");
		}
	
		$em->remove($product);
		$em->flush();
	
		$cat=$this->get('session')->get('category');
		return new RedirectResponse($this->get('router')->generate('product_list', array('id' => $cat)));
	}
	
	
	
	
	public function modelsearchAction(){
	// gestion des séries
	
		$message="";
		$request = $this->container->get('request');
	
	
		$serie = '';
		$serie = $request->request->get('serie');
	
		$form = $this->container->get('form.factory')->create(new SeriesModelForm());
		$em = $this->container->get('doctrine')->getEntityManager();
		$allModels= $em->getRepository('AppBundle:Model')->findAll();
		$series =$em->getRepository('AppBundle:Series')->findAll();
		if($serie != 0)
		{
			$models=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->findBySeries($serie);
			
		}
		else
		{
			$models=$allModels;
		}
	
	
		$model=$this->container->get('session')->get('models');
 	    if($model!==null) {
		$model=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($model,$models);
 	    }

		return $this->container->get('templating')->renderResponse(
				'AppBundle:Common:SeriesModel.html.twig', array(
						'form'=>$form->createView(),
						'message'=>$message,
						//			'series'=> $series,
						'models'=>$models,
				));
	
	}
	
	public function modelSetAction(){
		// on ajoute des models sélectionnés
		// ceci est géré par Ajax
		
		$message="";
		$mods=array();
		
		$request = $this->container->get('request');
		
		
		$models = '';
		$models = $request->request->get('models');
	    $mod=array();
	if($models<>""){
		
		for($i=0;strstr($models,',',true)<>false;$i++) {
			$str=strstr($models,',',true);
			$mods[$i]=$str;
			$models=strstr($models,',',false);
			$models=substr($models,1);
			}
			//le derner item
			$mods[$i]=$models;	
			$em = $this->get('doctrine')->getEntityManager();
			if($mods[0]=="[0]"){ // on est dans le cas d'un reset
				$mod[0]=new Model(); // alors on met un élément vide à la place
			}
			else {
				$mod=array();
				for($i=0;$i<count($mods);$i++){
					$mod[$i]=$em->find('AppBundle:Model',$mods[$i]);
				}
		
		}
	}
//	    $this->get('session')->remove('models');
		$this->get('session')->set('models', $mod);
		$mod=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($mod,$mod);
	
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Common:SeriesModel.html.twig', array(
//						'form'=>$form->createView(),
						'message'=>$message,
						//			'series'=> $series,
						'models'=>$mod,
				));
		
	}
	
	public function setCategoryAction(){
		
		$em = $this->container->get('doctrine')->getEntityManager();
		$request = $this->get('request');
		$category = $request->request->get('category');
		
		if($category<>null){
			if($category== 0){
				$this->get('session')->remove('category');
				
			}
		    else {
		    	$cat=$em->find('AppBundle:Category',$category);
		    	$this->get('session')->set('category',$cat);
		    }
		}
		
		$models =$em->getRepository('AppBundle:Model')->findAll();
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Common:SeriesModel.html.twig', array(
						'models'=>$models,
				));
	}
	
	public function setAction(){
		
		$resultCategory=array();
		$resultModel = array();
		$result=array();		
		
		$em = $this->container->get('doctrine')->getEntityManager();
		$category=$this->get('session')->get('category');
		$model=$this->get('session')->get('models');
		
		if($category!=null){
			$resultCategory=$em->getRepository('AppBundle:Product')->findByCategory($category->getId());
		}
		if($model!=null){
			$Models=$em->getRepository('AppBundle:Model')->find($model[0]->getId());
			$resultModel=$Models->getProduct()->toArray();
		}
		
		if(count($resultCategory)==0 && count($resultModel)==0)
			$result=$em->getRepository('AppBundle:Product')->findAll();
		else{
			if(count($resultModel)==0)
				$result=$resultCategory;
			else{
				if(count($resultCategory)==0)
					$result=$resultModel;
				else 
				for($i=0, $j=0; $i<count($resultModel); $i++){
				  for($k=0;$k<count($resultCategory);$k++){
				  	if($resultModel[$i]==$resultCategory[$k]){
				  		$result[$j]=$resultModel[$i];
				  		$j++;
				  	}
				  		
				  }
			    }
			  }
		     }
		
		 
		     
		     if($category==null)
		     	$cat='tous';
		     else
		     	$cat=$category->getName();
		     if($model==null)
		     	$mod='tous';
		     else
		     	$mod=$model[0]->getName();
		     				
		    $images=$this->container->getParameter('app.images_URL');
		     
		    return $this->get('templating')->renderResponse(
		     		'AppBundle:Product:result.html.twig', array(
		     				'images'=>$images,
		     				'category'=>$cat,
		     				'model'=>$mod,
		     				'count'=>count($result),
		     				'products'=>$result,
		     		));
		 
	
		
	}
	
	private function getErrorMessages(\Symfony\Component\Form\Form $form) {
		$errors = array();
		
		foreach ($form->getErrors() as $error) {
			if ($form->isRoot()) {
				$errors['#'][] = $error->getMessage();
			} else {
				$errors[] = $error->getMessage();
			}
		}
		
		foreach ($form->all() as $child) {
			if (!$child->isValid()) {
				$errors[$child->getName()] = $this->getErrorMessages($child);
			}
		}
		
		return $errors;
	}

}
	