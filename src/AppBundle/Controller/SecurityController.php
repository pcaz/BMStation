<?php

namespace AppBundle\Controller;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SecurityController
 *
 * @author pascal
 */
class SecurityController extends FOS\UserBundle\Controller\SecurityController {
 
    public function loginAction(Request $request)
    {
    if($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
         return new RedirectResponse($this->container->get('router')->generate('homepage', array(), UrlGeneratorInterface::ABSOLUTE_PATH), 302);
     }    
     return parent::loginAction($request);
    }
}
