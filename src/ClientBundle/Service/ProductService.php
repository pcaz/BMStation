<?php
namespace ClientBundle\Service;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 *
 * @author pascaz10
 * attention, cette classe peut générer des exceptions
 */

class ProductService extends Extension {
	
	
	private $control;
	
	public function __construct($control)
	{
	
		$this->control=$control;
	}
	
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('service.yml');
	}
	
	public function init($control, $em){
		$this->$control=$control;
		$this->$em=$em;
	}
	
	public function modelSearch(){
		// gestion des séries
		
		
		
		
		$request = $this->control->get('request');
			
		$serie = $request->request->get('serie');
		
		$em = $this->control->get('doctrine')->getManager();
		$allModels= $em->getRepository('AppBundle:Model')->findAll();
		$series =$em->getRepository('AppBundle:Series')->findAll();
		if($serie != 0)
		{
			$models=$this->control->getDoctrine()->getManager()->getRepository('AppBundle:Model')->findBySeries($serie);
			
		}
		else
		{
			$models=$allModels;
		}
		
		
		$model=$this->control->get('session')->get('models');
		if($model!==null) {
			$model=$this->control->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($model,$models);
		}
		
		return $models;
		
	}

	public function setModel(){
		// on ajoute des models sélectionnés
		// ceci est géré par Ajax
		
		$mods=array();
		
		$request = $this->control->get('request');
		
		
		$models = '';
		$models = $request->request->get('models');
		$mod=array();
		// on regarde d'abord si on a reçu quelque chose
		if($models==""){
			$this->control->get('session')->set('models',array());
			return;
		}
		// on teste le reset
		if($models=="0"){
			$this->control->get('session')->set('models',"0");
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
		$em = $this->control->get('doctrine')->getManager();
		
		$mod=array();
		for($i=0;$i<count($mods);$i++){
			$mod[$i]=$em->find('AppBundle:Model',$mods[$i]);
		}
		
		$this->control->get('session')->set('models',$mod);
		//	    $this->get('session')->remove('models');
		//$this->control->get('session')->set('models', $mod);
		//$mod=$this->control->getDoctrine()->getManager()->getRepository('AppBundle:Model')->getSelected($mod,$mod);
		
		return $mod;
		
	}
	
	public function setCategory(){
		
		$em = $this->control->get('doctrine')->getEntityManager();
		$request = $this->control->get('request');
		$category = $request->request->get('category');
		
		
		$cat=$em->find('AppBundle:Category',$category);
		$this->control->get('session')->set('category',$cat);
		
		return;
		
	}
	
	public function set(){
		
		$resultCategory=array();
		$resultModel = array();
		$result=array();
		$cat=false;
		$mod=false;
		
		$em = $this->control->get('doctrine')->getEntityManager();
		$category=$this->control->get('session')->get('category');
		$model=$this->control->get('session')->get('models');
		
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
				if(!$mod)
					$result=$resultCategory;
					else{
						if(!$cat)
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
							
		return array(
				'category'=>$catResult,
				'model'=>$modResult,
				'result'=>$result,
		);					
							
							
							
	}

}