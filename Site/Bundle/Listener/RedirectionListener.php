<?php

namespace Machouille\Site\Bundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;


class RedirectionListener {
	public function __construct(ContainerInterface $container, Session $session){
		$this->session = $session;
		$this->router = $container->get('router');
		$this->securityContext = $container->get('security.context');
	}

	public function onKernelRequest(GetResponseEvent $event){
		
		$route = ($event->getRequest()->attributes->get('_route'));
		
		if($route == 'livraison' || $route == 'validation'){
			if($this->session->has('panier')){
				if(count($this->session->get('panier')) == 0)
					$event->setReponse(new RedirectResponse($this->routeur->generate('machouille_site_page_panier')));
			} 
			
			if(!is_objet($this->securityContext->getToken()->getUser())){
				$this->session->getFlashBag()->add('notification','Vous devez vous identifier');
				$event->setReponse(new RedirectResponse($this->routeur->generate('machouille_site_page_inscription')));
			}
		}	
	}
}