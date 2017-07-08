<?php
namespace ClientBundle\Controller;

use ClientBundle\Entity\Annonce;
use ClientBundle\Entity\Photo;
use ClientBundle\Entity\Historic;
use ClientBundle\Form\AnnonceForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ClientBundle\Form\NameBasketForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;


class AnnonceController extends Controller
{
	public function editAnnonceAction()
	{
		
		$annonce = new Annonce();
		for($i=0;$i<3;$i++)
		{
			$photo = new Photo();
			$annonce->addPhoto($photo);
		}
		$form = $this->createForm(new AnnonceForm,$annonce);
		$request = $this->getRequest();

		
		if($request->getMethod()=="GET")
		{
			return $this->get('templating')->renderResponse(
					'ClientBundle:Annonce:annonce.html.twig', array(
							'form'=>$form->createView(),
					));
		}
	}
	
	public function uploadImageAction($id)
	{
		$annonce = new Annonce();
		$request=$this->getRequest();
		if($request->isXmlHttpRequest())
		
		{
		    $file = $request->files->get('upload');
		}
		return new JsonResponse($file);

	}
	
}