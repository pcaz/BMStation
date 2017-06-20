<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Model;
use AppBundle\Entity\Series;
use AppBundle\Form\UserSeriesModelForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;

class HomePageController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function homepageAction()
    {

    	
	// Get Symfony to interface with this existing session
//	$session = new Session(new PhpBridgeSessionStorage());
	
	// symfony will now interface with the existing PHP session
//	$session->start();

    	// on commence en français
    	
    	$this->container->get('session')->set('_locale','fr');

	return $this->container->get('templating')->renderResponse(
			'AppBundle::homePage.html.twig', array(
	
	
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
		$url = $this->container->get('request')->headers->get('referer');
		if(empty($url)) {
			$url = $this->container->get('router')->generate('homepageAdmin');
		}
		return new RedirectResponse($url);
	}
	

 
}
    
