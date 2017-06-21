<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Form\ProductCategoryForm;
use AppBundle\AppBundle;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class CategoryController extends Controller
{
	/**
	 * @
	 * @Route("/category_list/", name="category_list")
	 * @Route("/category/", name="category_list")
	 */
	public function listAction()
	{
		
		
		$em= $this->container->get('doctrine')->getManager();
		$categories= $em->getRepository('AppBundle:Category')->findAll();
        	
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Category:list.html.twig', array('categories'=>$categories,
				'directory'=>$this->container->getParameter('app.images_URL'),		
				));
	}
	
	/**
	 * @Route("/category_add/", name="category_add")
	 * @Route("/category_modify/{id}", name="category_modify")
	 */
	
	public function editAction($id=null){
		$message="";
		$title='';
		$image='';
		$format=array('jpeg', 'png', 'gif');
		
		
		
		
		$newcategory = new Category();
		$form = $this->get('form.factory')->create(new CategoryForm(), $newcategory);
		$em = $this->get('doctrine')->getManager();
		$request = $this->get('request');
		$flashbag=$request->getSession()->getFlashBag();
		$error=0;
		
		if ($request->getMethod() == 'GET'){
	/************* GET ***********************/
			// On édite le formulaire
		
			if($id){
				$category=$em->find('AppBundle:Category',$id);
			}
			else{
				$category=new Category();
			}
			$image=$this->getParameter('app.images_URL').'/'.$category->getImage();
			$form = $this->get('form.factory')->create(new CategoryForm(), $category);			
			// et on envioie l'écran de saisie
			return $this->get('templating')->renderResponse(
					'AppBundle:Category:edit.html.twig',
					array( 'form' => $form->createView(),
							'title' => $title,
							'id'=>$id,
							'image' => $image,
					));
			}
			
			// la requête est un POST,
			//on est donc en ajout ou en modification	
	
			//on recupère les données de la form
			$form->handleRequest($request);
			if ($form->isValid()){
				
				if($id){		
	/*************** Modif ******************/				
					//on est en modification
					$title=$this->container->get('translator')->trans('modify_category');
			
					$category=$em->find('AppBundle:Category',$id);
			
					if(!$category){
						//si la catégory n'existe pas, il y a une erreur interne. ça ne doit jamais arriver!
						$message= $this->container->get('translator')->trans('no_category');	
						throw new \Exception('fatal',$message);
					}
				
				     // on cherche à modifier le commentaire	
				     if($newcategory->getComment()){$category->setComment($newcategory->getComment());}

				     if($newcategory->getImage()){
					//on modifie l'immage
					//il faut récupérer l'image qui a été uploadée 
					
					      $oldimage = $category->getImage();
					      $image=$newcategory->getImage();
					
					      // On vérifie l'extension du fichier (à partir du type mime)
					      if(in_array($image->guessExtension(), $format)){
								// On génère un nom unique avant de sauvegarder le fichier
								$fileName = md5(uniqid()).'.'.$image->guessExtension();
								// On déplace le fichier vers le répertoire Uploads
								$image->move(
										$this->container->getParameter('app.images_directory'),
										$fileName
										);
								//On met à jour la catégory avec la nouvelle image.
								$category->setImage($fileName);
								// Il faut effacer le fichie de l'image precedente
								if($oldimage != null){
										unlink ($this->container->getParameter('app.images_directory').'/'.$oldimage);
								}									
						}
						else{
						// le format n'est pas accepté	
						$flashbag->add('error',$this->container->get('translator')->trans('bad_format'));
						$error++;
						}
				} //on a fini de traiter les modifs
					// et on sauvegarde la catégories modifiée
				
				$em->persist($category);
				$em->flush();
			}
		else{
	/*********** Ajout ***********/		
			//on est en ajout
				$title=$this->container->get('translator')->trans('add_category');
				//on vérifie que la catégory n'existe pas déja
			if($em->getRepository('AppBundle:Category')->findOneBy(array('name'=>$newcategory->getName()))){
						$error++;
						$flashbag->add('error',$this->container->get('translator')->trans('existing_category'));;
			}
			else {
			  
			  // tout va bien, il faut sauvegarder la catégorie	
			  // mais avant, il faut récupérer l'image qui a été uploadée si il y en au une
			  $image = $newcategory->getImage();
			  if($image<>null){
			  	// On vérifie l'extension du fichier (à partir du type mime)
			  	if(in_array($image->guessExtension(), $format))
			  	{
			  		// On génère un nom unique avant de sauvegarder le fichier
			  		$fileName = md5(uniqid()).'.'.$image->guessExtension();
			  		// On déplace le fichier vers le répertoire Uploads
			  		$image->move(
			  				$this->container->getParameter('app.images_directory'),
			  				$fileName
			  				);
			  		//On met à jour la catégory avec la nouvelle image.
			  		$newcategory->setImage($fileName);
			  	}
			  	else {
			  	//format incompatible	
			  	$flashbag->add('error',$this->container->get('translator')->trans('bad_format'));
			  	$error++;
			     }
			  } 
			  //ok, tout va bien, on enregistre avec ou sans l'image.
			  $em->persist($newcategory);
			  $em->flush();
		    }		    	
		 }
		 if($error==0)		 	
		        return new RedirectResponse($this->container->get('router')->generate('category_list'));
         else{
         	
         			
         	return $this->get('templating')->renderResponse(
         			'AppBundle:Category:edit.html.twig',
         			array(
         					'form' => $form->createView(),
         					'message' => $message,
         					'title' => $title,
         					'image' => $image,
         			));
         }         	
		}
		// Ok, finit ou la form est invalid  les enregistrement, maintenant, il faut penser à rediriger l'utilisateur:
		if($id) {
			$category=$em->find('AppBundle:Category',$id);
			$image=$this->getParameter('app.images_URL').'/'.$category->getImage();
			
		}
			return $this->get('templating')->renderResponse(
					'AppBundle:Category:edit.html.twig',
					array(
							'form' => $form->createView(),
							'message' => $message,
							'title' => $title,
							'image' => $image,
							'id' => $id
					));
		}
  

		public function deleteAction($id)
		{
			$em = $this->container->get('doctrine')->getEntityManager();
			$category = $em->find('AppBundle:Category', $id);
			$request = $this->get('request');
			$flashBag=$request->getSession()->getFlashBag();
			
			if (!$category){
				throw new NotFoundHttpException($this->get('translate')->trans("category_not_found"));
	       
			}
			else{
				//si il existe encore des produits liés à cette catégorie, il ya a erreur
			
				
				$products=$em->getRepository('AppBundle:Product')->findByCategory($id);
				if(count($products!=0)){
					$request->getSession()->getFlashBag()->add('error',$this->get('translator')->trans('existing_products'));			
					return new RedirectResponse($this->get('router')->generate('category_modify',array('id'=>$id)));
				
						
				}
				//ok..c'est bon
				// d'abord on retire l'image
				$Image=$this->get("app.image");
				$Image->removeImage($category->getImage());
				
				// puis on peut écraser la catégory

				$em->remove($category);
				$em->flush();
			}
			
		
			return new RedirectResponse($this->get('router')->generate('category_list'));
		}
	
}