<?php

namespace Machouille\Site\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PanierController extends Controller 
{
	public function supprimerAction($id){
		$session = $this->getRequest()->getSession();
		$panier = $session->get('panier');
		
		if(array_key_exists($id, $panier)){
			unset($panier[$id]);
			$session->set('panier', $panier);
			$this->get('session')->getFlashBag()->add('success','Article supprimé avec succès');
		}
		
		return $this->redirect($this->generateUrl('machouille_site_page_panier'));
	}
	
	public function ajouterAction($id){
		$session = $this->getRequest()->getSession();
		
		if(!$session->has('panier')){
			$session->set('panier', array());
		}
		
		$panier = $session->get('panier');
		
		//$panier[id produit] => quantite 
		
		if(array_key_exists($id, $panier)){
			if($this->getRequest()->query->get('qte') != null)  $panier[$id] = $this->getRequest()->query->get('qte');
			$this->get('session')->getFlashBag()->add('success','Quantité modifié avec succès');
		} else {
			if($this -> getRequest()->query->get('qte') != null) 
				$panier[$id] = $this->getRequest()->query->get('qte');
			else 
				$panier[$id] = 1;
			
			$this->get('session')->getFlashBag()->add('success','Article ajouté avec succès');
		}
		
	
		$session->set('panier', $panier);

		
		return $this->redirect($this->generateUrl('machouille_site_page_panier'));
	}
	
	public function panierAction(){
		
		$session = $this->getRequest()->getSession();
		
		if(!$session->has('panier')){
			$session->set('panier', array());
		}
					
		$em = $this->getDoctrine()->getManager();
		$produits = $em -> getRepository('MachouilleSiteBundle:ChewingGum')
						-> findArray(array_keys($session->get('panier')));
		
		return $this->render('MachouilleSiteBundle:Vitrine:panier.html.twig', array(
				'produits'=>$produits,
				'panier' => $session->get('panier')
		));
	}
	
	public function validationAction(){
		$session = $this->getRequest()->getSession();
		
		if(!$session->has('panier')){
			$session->set('panier', array());
			return $this->redirect($this->generateUrl('machouille_site_page_accueil'));
		}
			
		$em = $this->getDoctrine()->getManager();
		$produits = $em -> getRepository('MachouilleSiteBundle:ChewingGum')
		-> findArray(array_keys($session->get('panier')));
		
		$uRepository = $em->getRepository('MachouilleSiteBundle:Utilisateur');
		$user = $uRepository->findOneBy(array('mail' => $session->get('mail')));
		
		$this->get('session')->getFlashBag()->add('success','Panier enregistré avec succès');
		
		return $this->render('MachouilleSiteBundle:Vitrine:livraison.html.twig', array(
				'produits'=>$produits,
				'panier' => $session->get('panier'),
				'user' => $user
		));
		
	}

	public function pageDetailAction($id){
		$session = $this->getRequest()->getSession();
		$session->start();
		
		$em = $this	->getDoctrine()
					->getManager();
		
		$uRepository = $em->getRepository('MachouilleSiteBundle:ChewingGum');
		$article = $uRepository->findOneBy(array('reference' => $id));
		
		return $this->render('MachouilleSiteBundle:Vitrine:details.html.twig', array(
				'article'=> $article
		));
	}
}