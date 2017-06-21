<?php
namespace AppBundle\Controller;

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

		$category=$this->get('session')->get('category');
		$images=$this->container->getParameter('app.images_URL');
		$products = $this->get('knp_paginator')->paginate($products,$this->get('request')->query->get('page', 1),20);
		//.'/'.$cat->getImage();;
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Product:list.html.twig',
				array(	'images' => $images,
						'category' => $category,
						'products' => $products,
				));
		
	}
		

	public function modifyAction($id=null) {
        
		
		
		if(!$id)
			// anormal, on repart sur un add
			return new RedirectResponse($this->container->get('router')->generate('product_add'));	
   
    	$em = $this->container->get('doctrine')->getManager();
       	$request=$this->container->get('request');
    	$flashbag=$request->getSession()->getFlashBag();
    	
    	// on stocke un dois pour toute l'ensemble des séries
    	$series =$em->getRepository('AppBundle:Series')->findAll();
		// et la catégorie du produit

		if ($request->getMethod() == 'GET'){
			// d'abord, on récupère le produit
			$product=$em->find('AppBundle:Product',$id);
			$form=$this->container->get('form.factory')->create(new ProductForm(),$product);
			// puis ses modèles
			$count=$product->countModel();
			
			$models=$product->getModel()->unwrap()->toArray();
			// on stocke les modèles dans la session
			
			$this->get('session')->remove('models');
			$this->container->get('session')->set('models',$models);
			// enfin, on va selectionner les modèles du produit 
			$allModels =$em->getRepository('AppBundle:Model')->findAll();
			$models=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($models,$allModels);
			// et om met à jour les dernières variables
			$images=$this->container->getParameter('app.images_URL');
		
			$category=$product->getCategory();
			// et on affiche l'écran		
			return $this->container->get('templating')->renderResponse(
					'AppBundle:Product:edit.html.twig',
					array(
							'form' => $form->createView(),
							'title' => 'modify_product',
							'images' => $images,
							'series' => $series,
							'models'=> $models,
							'category' => $category,
							'product'=>$product,
					));
		}
		
		// on est en POST
		// et on a enregistré un nouveau produit
	
		$oldProduct=$em->find('AppBundle:Product',$id);
	    $product=new Product();
		$form=$this->get('form.factory')->create(new ProductForm(),$product);
        $form->handleRequest($request);  
        $this->getErrorMessages($form);
    	if ($form->isValid()){
				// on est en modification
   
    	// l'utilisateur a modifié le contexte
    	$models=$this->get('session')->get('models');
    	
    	// on test d'abord si on a eu un reset
    	// si il n'y a pas de models présent, le tableau est vide
    	if( $models=="0"){
    	// alors, on réinitialise les valeurs aux valeurs initiales
    			$form = $this->container->get('form.factory')->create(new ProductForm(), $oldproduct);
    			$models=$product->getModel();
    			$models=$models->toArray();
    			$allModels=$em->getRepository('AppBundle:Model')->findAll();	
    			$models=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($models,$allModels);
    		
    	
    			$category=$product->getCategory();
    			$images=$this->getParameter('app.images_URL');
    			return $this->container->get('templating')->renderResponse(
    				'AppBundle:Product:edit.html.twig',
    				array(
    						'form' => $form->createView(),
    						'title' => 'modify_product',
    						'images' => $images,
    						'series' => $series,
    						'models'=> $models,
    						'category' => $category,
    						'product'=>$oldproduct,
    				));
    		}
		
    
        // bon, cette fois ci, on modifie pour de bon	
				
		// on gère l'image qui a été uploadée et on efface éventuellement l'ancienne
		// c'est peut la même mais à ce stade, on n'en sait rien
    		$newProduct=$oldProduct=$em->find('AppBundle:Product',$id);
    		if($product->getName()) $newProduct->setName($product->getName());
    		if($product->getComment()) $newProduct->setComment($product->getComment());
    		if($product->getPrice()) $newProduct->setPrice($product->getPrice());
    		if($product->getDisponibility() !== null) $newProduct->setDisponibility($product->getDisponibility());
    		else $newProduct->setDisponibility($oldProduct->getDisponibility());
	    
	    	if ($product->getImage()){
	    			$getImage=$this->container->get("app.image");
	    			$oldImage=$oldProduct->getImage();
	    			if($oldImage) $getImage->removeImage($oldImage);
		    		$image=$getImage->uploadImage($product->getImage());
		    		$newProduct->setImage($image);
	    	}
		
		

			// on s'occupe des modèles
		
				$mod=$oldProduct->getModel();
				$oldModels=$mod->toArray();
		
			// on récupère les nouveaux modèles
			$models=$this->get('session')->get('models');

				// si oui, on commence va d'abord retirer tous les ancien modeles
			
				$newProduct->removeAllModels();
				
				// et on flush pour éviter les collisions
				
//	    	$em->flush();

		
			// puis on ajoute les nouveaux
			for($i=0;$i<count($models);$i++) {
					if ($models[$i] instanceof \AppBundle\Entity\Model) 
					{  
						$model=$em->find('AppBundle:Model',$models[$i]->getId());
						$newProduct->getModel()->add($model);
					}
			}

		// bon, à présent, on peut sauvegarder le produit
		
		$em->persist($newProduct);
		$em->flush();
	
		$flashbag->add('success', $this->container->get('translator')->trans('modification_done'));
		
		
		
		// on sélectionne les modeles acquis
		
	
		$allModels=$em->getRepository('AppBundle:Model')->findAll();
		$this->container->get('session')->remove('models');
		$this->container->get('session')->set('models',$models);
		if($models){
		$models=$this->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($models,$allModels);
		}
	
		$category=$newProduct->getCategory();
		$images=$this->getParameter('app.images_URL');
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Product:edit.html.twig',
				array(
						'form' => $form->createView(),
						'title' => 'modify_product',
						'images' => $images,
						'series' => $series,
						'models'=> $models,
						'category' => $category,
						'product'=>$newProduct,
				));
		}
	// la form postée n'est pas valide (contraintes)
		
		
		$models=$this->get('session')->get('models');
		$category=$this->get('session')->get('category');
		 $images=$this->container->getParameter('app.images_URL');
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Product:edit.html.twig',
				array(
						'form' => $form->createView(),
						'title' => 'modify_product',
						'images' => $images,
						'series' => $series,
						'models'=> $models,
						'category' => $category,
						'product'=>$product,
				));
		
	}
	
	public function addAction(){
				
		
		$request = $this->get('request');
		$flashbag=$request->getSession()->getFlashBag();
		$em = $this->get('doctrine')->getManager();
		$product=new Product();
		$form=$this->get('form.factory')->create(new ProductForm(),$product);
		$series =$em->getRepository('AppBundle:Series')->findAll();
		$models =$em->getRepository('AppBundle:Model')->findAll();
		
		
		if ($request->getMethod() == 'GET'){
			if (($category=$this->get('session')->get('category'))==null){			
			$categories=$em->getRepository("AppBundle:Category")->findAll();
			$images=$this->container->getParameter('app.images_URL');
			return $this->container->get('templating')->renderResponse(
					'AppBundle:Category:chooseCategoryFrame.html.twig',
					array(
							'form' => $form->createView(),
							'title' => 'add_product',
							'images' => $images,
							'categories'=>$categories,
							
					));
			}
			else{
				$form = $this->get('form.factory')->create(new ProductForm(), $product);
				$models=$em->getRepository('AppBundle:Model')->findAll();
				$images=$this->getParameter('app.images_URL');
				$cat=$this->get('session')->get('category');
				$category=$em->find('AppBundle:Category',$cat);
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'title' => 'modify_product',
								'images' => $images,
								'series' => $series,
								'models'=> $models,
								'category' => $category,
								'product'=>$product,
						));
			}
			
			}
		
		// bon, on est en POST, on ajoute un produit
		
		$form->handleRequest($request);
		$this->getErrorMessages($form);
		if($form->isValid()){
			
			// on verifie d'abord si ce n'est pas un reset
			$mod=$this->get('session')->get('models');
			
			if( $mod == "0") {
			
				$product=new Product;
				$form = $this->container->get('form.factory')->create(new ProductForm(), $product);
				$category=$this->get('session')->get('category');
				$images=$this->getParameter('app.images_URL');
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'title' => 'add_product',
								'images' => $images,
								'series' => $series,
								'models'=> $models,
								'category' => $category,
								'product'=>$product,
						));
			}
			
			
			$validator=$this->container->get('validator');
			if(!$this->container->get('app.validate_product')->validate($validator,$product->toArray(), $flashbag))
			{
				$category=$this->get('session')->get('category');
				$images=$this->getParameter('app.images_URL');
				return $this->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'title' => 'add_product',
								'images' => $images,
								'series' => $serie,
								'models'=> $models,
								'category' => $category,
								'product'=>$product,
						));
						
			}
				
			// tout va bien, la form est validée
			// on vérifie que le nom du produit n'existe pas encore
			
			if($em->getRepository('AppBundle:Product')->findByName($product->getName())){
				$flashbag->add('error',$this->get('translator')->trans('existing_product'));
				$models=$em->getRepository("AppBundle:Model")->findAll();
				$serie=$em->getRepository("AppBundle:Series")->findAll();
				$category=$this->get('session')->get('category');
				
				$images=$this->getParameter('app.images_URL');
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'title' => 'add_product',
								'images' => $images,
								'series' => $serie,
								'models'=> $models,
								'category' => $category,
								'product'=>$product,
						));
			}
			
			
			$newProduct=new Product();
			$newProduct->setName($product->getName());
			$category=$this->get('session')->get('category');
			// on informe doctctrine
			$category=$em->find("AppBundle:Category", $category->getId());
			$newProduct->setCategory($category);
			$newProduct->setDescription($product->getDescription());
			$newProduct->setComment($product->getComment());
			$newProduct->setDisponibility($product->getDisponibility());
			$newProduct->setPrice($product->getPrice());
			
			// on s'occupe de l'image
			if ($product->getImage()){
				$getImage=$this->container->get("app.image");
				$image=$getImage->uploadImage($product->getImage());
				$newProduct->setImage($image);
			}
			
			// on s'occupe des modèles
						
			$models=$this->get('session')->get('models');
			
			if(count($models)<>0){
				for($i=0;$i<count($models);$i++) {
					$model=$em->find('AppBundle:Model', $models[$i]->getId());
					$newProduct->getModel()->add($model);
				}
			
			
			}
			
			
			// ok, on enregistre
				$output=new OutputConsole();
				$output->writeln('je suis là');
			
				// et on sort
				$flashbag->add('succes', $this->get('translator')->trans('record_done'));
				$models=$em->getRepository('AppBundle:Model')->findAll();
				$category=$this->get('session')->get('category');
                $serie=$em->getRepository('AppBundle:Category')->findAll();
                $product=new Product();
                $form=$this->get('form.factory')->create(new ProductForm(),$product);
				$title=$this->get('translator')->trans('add_product');
				$images=$this->getParameter('app.images_URL');
				return $this->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'title' => 'add_product',
								'images' => $images,
								'series' => $serie,
								'models'=> $models,
								'category' => $category,
								'product'=>$product,
						));
				
		} 
				//erreur.. la form est invalide
				
				$models=$this->get('session')->get('models');
				$serie=$this->get('session')->get('serie');
				$category=$this->get('session')->get('category');
				$images=$this->container->getParameter('app.images_URL');
				return $this->container->get('templating')->renderResponse(
						'AppBundle:Product:edit.html.twig',
						array(
								'form' => $form->createView(),
								'title' => 'add_product',
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
	
		$category=$this->get('session')->get('category');
		return new RedirectResponse($this->get('router')->generate('product_list', array('id' => $category->getId())));
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
	// on regarde d'abord si on a reçu quelque chose    
	if($models==""){
		$this->get('session')->set('models',array());
		return;
	}
	// on teste le reset	
	if($models=="0"){	
		$this->get('session')->set('models',"0");
		return;
	}	
	
	// bon, on est dans le cas normal et on doit interpréter la chaîne [x,x,x...]
	
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
		
		
		$cat=$em->find('AppBundle:Category',$category);
		$this->get('session')->set('category',$cat);
		
				
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
		$cat=false;
		$mod=false;
		
		$em = $this->container->get('doctrine')->getEntityManager();
		$category=$this->get('session')->get('category');
		$model=$this->get('session')->get('models');
		
		if($category!=null){
			$resultCategory=$em->getRepository('AppBundle:Product')->findByCategory($category->getId());
			$cat=true;
		}
		if($model!=null){
			$Models=$em->getRepository('AppBundle:Model')->find($model[0]->getId());
			$resultModel=$Models->getProduct()->toArray();
			$mod=true;
		}
		
		if(!$cat && !$mod)
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
		     	$catResult='tous';
		     else
		     	$catResult=$category->getName();
		     if($model==null)
		     	$modResult='tous';
		     else
		     	$modResult=$model[0]->getName();
		     				
		    $images=$this->container->getParameter('app.images_URL');
		     
		    return $this->get('templating')->renderResponse(
		     		'AppBundle:Product:result.html.twig', array(
		     				'images'=>$images,
		     				'category'=>$catResult,
		     				'model'=>$modResult,
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
	