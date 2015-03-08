<?php

namespace Machouille\Site\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/* AJOUT DES ENTITY */
use Machouille\Site\Bundle\Entity\Utilisateur;
use Machouille\Site\Bundle\Entity\Avis;


class MachouilleController extends Controller
{
    public function indexAction()
    {	  	
    	$em = $this	->getDoctrine()
    				->getManager();
    		
    	$query = $em->createQuery(
    			'SELECT a
				 FROM MachouilleSiteBundle:Avis a
				 ORDER BY a.id DESC'
    			)
    			->setMaxResults(3);
    		
    	$list_avis = $query->getResult();
    
    	$session = new Session();
    	$session -> start();
    	$session = $this -> get('session');
    	$session->set('id',1);
    		
    	return $this->render('MachouilleSiteBundle:Vitrine:accueil.html.twig', array(
    		'avis' => $list_avis
    	));

    }
	
    public function pageChewingFruitéAction(){
    	$em = $this	->getDoctrine()
    				->getManager();

		$uRepository = $em->getRepository('MachouilleSiteBundle:ChewingGum');
		$list_fruites = $uRepository->findBy(array('type' => "1"));
    			
		$session = $this -> get('session');
		$session -> start();
		
    	return $this->render('MachouilleSiteBundle:Vitrine:fruites.html.twig', array(
    			'list_fruites' => $list_fruites
    	));
    }
    
    public function pageInscriptionAction(){
    	return $this->render('MachouilleSiteBundle:Vitrine:inscription.html.twig');
    }

	public function pageConnexionAction(){
			
		return $this->render('MachouilleSiteBundle:Vitrine:connexion.html.twig');
	}
	
	public function pageConnexionErreurAction(){
		return $this->render('MachouilleSiteBundle:Vitrine:connexionE.html.twig');
	}
	
	public function pageCommentaireAction(){
		$session = $this -> get('session');
		$session -> start();
		
		$em = $this	->getDoctrine()
					->getManager();
		
		$query = $em->createQuery(
				'SELECT a
				 FROM MachouilleSiteBundle:Avis a
				 ORDER BY a.id DESC'
		)
		->setMaxResults(7);
		
		$list_avis = $query->getResult();
		
		return $this->render('MachouilleSiteBundle:Vitrine:commentaire.html.twig', array(
				'avis' => $list_avis
		));
	}
	
	
	
	public function planAction(){
		$session = $this -> get('session');
		
		return $this->render('MachouilleSiteBundle:Vitrine:plan.html.twig');
	}
	
	public function estConnecte(){
		if ($this->getUser() == null) {
			return false;
		}
		
		else { return true; }
	}
	
	public function inscriptionAction(Request $request) {
		
		$mail = 		$request->request->get ( 'ipt_mail' );
		$confirm_mail = $request->request->get ( 'ipt_confirm_mail' );
		$civilite = 	$request->request->get ( 'gender' );
		$prenom = 		$request->request->get ( 'ipt_prenom' );
		$nom = 			$request->request->get ( 'ipt_nom' );
		
		$jour = 		$request->request->get ( 'jour' );
		$mois = 		$request->request->get ( 'mois' );
		$annee = 		$request->request->get ( 'annee' );
		$date_naissance = $jour." ".$mois." ".$annee;
		
		$adresse =		$request->request->get ( 'ipt_adresse' );
		$adresseC = 	$request->request->get ( 'ipt_adresse+');
		$telephone = 	$request->request->get ( 'ipt_tel' );
		
		$pays = 		$request->request->get ( 'pays');
		$ville = 		$request->request->get ( 'ipt_ville');
		$codeP = 		$request->request->get ( 'ipt_codeP' );
		$mdp = 			$request->request->get ( 'ipt_mdp' );
		$confirm_mdp =  $request->request->get ( 'ipt_confirm_mdp' );
		
		if($mdp == $confirm_mdp && $mail == $confirm_mail){
			$user = new Utilisateur();
			$user -> setMail ($mail);
			$user -> setPrenom($prenom);
			$user -> setNom($nom);
			$user -> setCivilite($civilite);
			$user -> setDatedenaissance($date_naissance);
			$user -> setPays($pays);
			$user -> setVille($ville);
			$user -> setAdresse($adresse.$adresseC);
			$user -> setCodepostal($codeP);
			$user -> setTelephone ($telephone);
			$user -> setMotdepasse ($mdp);
			$user -> setStatut ('Particulier' );
			
			$em = $this	->getDoctrine()
						->getManager();
			$em->persist($user);
			$em->flush();
			
			$session = $this->getRequest()->getSession();
			$session -> clear();
			$session -> set('id', $user->getId());
			return $this->redirect($this->generateUrl('machouille_site_page_connexion'));
		}
		
		else {
			return $this->render('MachouilleSiteBundle:Vitrine:inscription.html.twig');
		}
	}
	
	
	public function ajoutAvisAction(Request $request){

		$nom = $request->request->get ( 'ipt_nom' );
		$mail = $request->request->get ( 'ipt_mail' );
		$titre = $request->request->get ( 'ipt_titre' );
		$note = $request->request->get ( 'ipt_note' );
		$contenu = $request->request->get ( 'ipt_commentaire' );
		
		$avis = new Avis ();
		$avis->setMail ( strtolower($mail) );
		$avis->setNom ( strtolower($nom) );
		$avis->setNote ( $note );
		$avis->setAvis ( $contenu );
		$avis->setTitre ( strtolower($titre) );
		
		$session = $this->get ( 'session' );
		
		if ($session) {
			$id = $session->get ( 'id' );
			$avis->setUtilisateur ();
		}
		
		$em = $this->getDoctrine ()->getManager ();
		
		$em->persist ( $avis );
		$em->flush ();
		
		return $this->redirect($this->generateUrl('machouille_site_accueil'));
	}
	
	
	public function verifIdentAction(Request $request){
		$mail = $request->request->get('ipt_mail');
		$mdp =  $request->request->get('ipt_mdp');
		
		$em = $this->getDoctrine()->getManager();
		$uRepository = $em->getRepository('MachouilleSiteBundle:Utilisateur');
		$myUtilisateur = $uRepository->findOneBy(array('mail' => $mail,'motdepasse' => $mdp));
		
		if(isset($myUtilisateur)){

			$session = new Session();
			$session->start();
			$session->clear();
			$session->set('id', 	$myUtilisateur->getId());
			$session->set('mail',	$myUtilisateur->getMail());
			
			return $this->redirect($this->generateUrl('machouille_site_accueil'));
		}
		
		else {
			return $this->redirect($this->generateUrl('machouille_site_page_connexion_erreur'));
		}
	}
	
	public function verifUserAction(){
		$session = $this->getRequest()->getSession();
		if(!$session){
			return $this->redirect($this->generateUrl('machouille_site_page_inscription'));
		} else {
			return $this->redirect($this->generateUrl('machouille_site_accueil'));
		}
	}
	

	public function adresseAction(Request $request){

		$session = $this->getRequest()->getSession();
		
		$nom = $request->request->get ( 'ipt_nom' );
		$prenom = $request->request->get ( 'ipt_prenom' );
		$adresse = $request->request->get ( 'ipt_adresse' );
		$codeP = $request->request->get ( 'ipt_codeP' );
		$ville = $request->request->get ( 'ipt_ville' );
		$pays = $request->request->get ( 'ipt_pays' );
		
		$em = $this->getDoctrine()->getManager();
		$uRepository = $em->getRepository('MachouilleSiteBundle:Utilisateur');
		$user = $uRepository->findOneBy(array('mail' => $session->get('mail')));
		
		$user->setNom($nom);
		$user->setPrenom($prenom);
		$user->setAdresse($adresse);
		$user->setCodepostal($codeP);
		$user->setVille($ville);
		$user->setPays($pays);
		
		$em->persist ($user);
		$em->flush ();
		
		$new = $uRepository->findOneBy(array('mail' => $session->get('mail')));
		
		$this->get('session')->getFlashBag()->add('success','Adresse modifié avec succès ');
		
		return $this->render('MachouilleSiteBundle:Vitrine:livraison.html.twig', array(
			'user' => $new	
		));
	}
	
	public function paiementAction(){
		
		$session = $this->getRequest()->getSession();
		
		$this->get('session')->getFlashBag()->add('success','Adresse et panier confirmé');
		
		return $this->render('MachouilleSiteBundle:Vitrine:paiement.html.twig');
	}
	
	public function pageRechercheAction(){
		$em = $this->getDoctrine()->getManager();
		$uRepository = $em->getRepository('MachouilleSiteBundle:Gout');
		$gout = $uRepository->findAll();
		
		return $this->render('MachouilleSiteBundle:Vitrine:recherche.html.twig', array(
				'gout' => $gout
		));
	}
	
	public function searchAction(Request $request){
		$gout =  $request->request->get('gout');
		$type = $request->request->get('type');
		$request = $this->get('request');
	
		
		$em = $this	->getDoctrine()
					->getManager();
		
		$cg = $em->getRepository('MachouilleSiteBundle:ChewingGum')->findBy(array('type' => $type));
		
		$this->get('session')->getFlashBag()->add('success','Recherche effectuée avec succès');
		
		return $this->render('MachouilleSiteBundle:Vitrine:recherche.html.twig', array(
				'article' => $cg
		));
	}
}


