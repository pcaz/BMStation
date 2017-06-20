<?php

namespace ClientBundle\Controller;

use AppBundle\Entity\Model;
use AppBundle\Entity\Series;
use ClientBundle\Form\UserSeriesModelForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
		
class HomepageController extends Controller
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
	return new RedirectResponse($this->container->get('router')->generate('userchoosecategory'));
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
		$url = $this->container->get('request')->headers->get('referer');
		if(empty($url)) {
			$url = $this->container->get('router')->generate('homepage');
		}
		return new RedirectResponse($url);
	}
	
	
	public function chooseCategoryAction(){
		
	$em= $this->container->get('doctrine')->getManager();
	$categories = $em->getRepository('AppBundle:Category')->findAll();
	$home=$this->container->getParameter('app.URL');
	$images=$this->container->getParameter('app.images_URL');
	
	
	return $this->container->get('templating')->renderResponse(
			'ClientBundle:Vitrine:chooseCategory.html.twig',
			array(
					'home'=>$home,
					'images' => $images,
					'categories' => $categories,
			));
	

	}
  
    
    public function chooseModelAction($category = null)
    {
    	$message='';
    	$models='';
    	
    	$em= $this->container->get('doctrine')->getEntityManager();
    	
    	if($category)
    	{
    	$cat=$em->getRepository('AppBundle:Category')->findBy(array('id'=>$category));	
    	$this->container->get('session')->set('usercategory', $cat);
    	}


    	$series= $em->getRepository('AppBundle:Series')->findAll();
    	$models= $em->getRepository('AppBundle:Model')->findAll();
    	
    	
    	$form = $this->container->get('form.factory')->create(new UserSeriesModelForm(), $series);
 

   
    	
    	
    	return $this->container->get('templating')->renderResponse(
    			'ClientBundle:Vitrine:userModel.html.twig',
    			array(
    					'form' => $form->createView(),
    					'series'=> $series,
    					'message' => $message,
    					'models' => $models,
    			));
    }
    
    public function modelSearchAction(){
   
    	
    	$message="";
    	$request = $this->container->get('request');
    	
    	
    	$serie = '';
    	$serie = $request->request->get('serie');
    	
    	$form = $this->container->get('form.factory')->create(new UserSeriesModelForm());
    	$em = $this->container->get('doctrine')->getEntityManager();
    	
    	if($serie != 0)
    	{
    		$models = $em->getRepository('AppBundle:Model')->findBySeries($serie);
    		$series =$em->getRepository('AppBundle:Series')->findAll();
    	}
    	else
    	{
    		$models = $em->getRepository('AppBundle:Model')->findAll();
    		$series =$em->getRepository('AppBundle:Series')->findAll();
    	} 	
    	
    	if($request->getMethod() == 'POST'){
    	return $this->container->get('templating')->renderResponse(
    			'ClientBundle:Vitrine:userModel.html.twig', array(
 //   			'form'=>$form->createView(),		
    		    'message'=>$message,
    			'series'=> $series,		
    			'models'=>$models,));
    	}

    	return $this->container->get('templating')->renderResponse(
    			'ClientBundle:Vitrine:userSeriesModel.html.twig',
    			array(
    					'form' => $form->createView(),
    					'series'=> $series,
    					'message' => $message,
    					'models' => $models,
    			));

   	}
   	
   	public function modelAction(){
   		$message="";
   		$request = $this->container->get('request');
   		$modelString = $request->request->get('model');
 
   		$models=$this->getModels($modelString);

   		$this->container->get('session')->set('usermodels', $models);
   		$em = $this->container->get('doctrine')->getEntityManager();
   		
   		$models=$this->container->get('session')->get('usermodels');
   		
/*    		return $this->container->get('templating')->renderResponse(
   				'AppBundle:Vitrine:userModel.html.twig', array(
   						'message'=>$message,
   						'category'=>$category,
   						'models'=>$models,
   						
   				));
 */ 
   		return new RedirectResponse($this->container->get('router')->generate('homepage'));
   		
   	}
   	private function getModels($string){
   		
   		$models=explode(',',$string);
   		$result=array();
   		$em = $this->container->get('doctrine')->getEntityManager();
   		if($models!= null){
   			$em = $this->container->get('doctrine')->getEntityManager();
   			
   			foreach($models as $mod)
   			{
   				$val= $em->getRepository('AppBundle:Model')->findBy(array('id'=>$mod));
   				$result[$mod]=$val[0];
   			}
   		}
   		return $result;
   				
   		
   		
   		
   	}
   	public function basketAction() {
   		
   		$message="";
   		
   		$category= $this->container->get('session')->get('usercategory');
   		$models=$this->container->get('session')->get('usermodels');
   		
//   		$em = $this->container->get('doctrine')->getEntityManager();
   		
//   		$models= $em->getRepository('AppBundle:Model')->findBy(array('id'=>$mod));
   		return $this->container->get('templating')->renderResponse(
   				'ClientBundle:Vitrine:userBasket.html.twig', array(
   						'message'=>$message,
   						'category'=>$category,
   						'models'=>$models,
   			));
   	}
}
    
