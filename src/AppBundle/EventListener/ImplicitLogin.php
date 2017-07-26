<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\FOSUserEvents;

class ImplicitLogin implements EventSubscriberInterface
{
    private $control; 
    
    public function __construct(\Symfony\Bundle\FrameworkBundle\Controller\Controller $control) {
        $this->control = $control;
    }
    public function onImplicitLogin(UserEvent $event)
    {
        $response= $this->control->container->get('templating')->renderResponse(
			'AppBundle:Security:login.html.twig', array(
			));
        $event->setResponse($response);
    }
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onImplicitLogin'
];
    }
}
