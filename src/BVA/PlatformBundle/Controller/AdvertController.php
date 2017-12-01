<?php

// src/BVA/PlatformBundle/Controller/AdvertController.php

namespace BVA\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use BVA\PlatformBundle\Entity\Advert;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        // $content = $this
        //     ->get('templating')
        //     ->render('BVAPlatformBundle:Advert:index.html.twig', array('nom' => 'Zazou'))
        // ;

        // $url = $this->get('router')->generate(
        //     'bva_platform_view',
        //     array('id' => 5),
        //     UrlGeneratorInterface::ABSOLUTE_URL
        // );

        // return new Response("L'url de l'annonce d'id 5 est : ".$url);

        // ===================================================================================
        
        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        // On déclenche une exception NotFoundHttpException, cela va afficher
        // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
        if($page < 1) throw new NotFoundHttpException('Page'.$page.' inexistante');

        // On a donc accès au conteneur :
        $mailer = $this->container->get('mailer');
        //  On peut envoyer des mails etc

        // Ici, on récupérera la liste des annonces, puis on la passera au template
        $listAdverts = array(
            array(
                'title'   => 'Recherche développeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony sur Lyon, bla bla bla',
                'date'    => new \Datetime()
            ),
            array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous cherchons un webmaster capable de maintenir notre site internet, bla bla bla',
                'date'    => new \Datetime()
            ),
            array(
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner, bla bla bla',
                'date'    => new \Datetime()
            )
        );

        // Mais pour l'instant, on ne fait qu'appeler le template
        return $this->render('BVAPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

    // On injecte la requète dans les arguments de la méthode
    public function viewAction($id, Request $request)
    {
        // On récupère notre paramètre tag
        $tag = $request->query->get('tag');

        // return new Response(
        //     "Affichage de l'annonce d'id : ".$id.", avece le tag : ".$tag
        // );

        // // On utilise le raccourci : il crée un objet Response
        // // Et lui donne comme contenu le contenu du template
        // return $this->render(  //$this->get('templating')->renderResponse(
        //     'BVAPlatformBundle:Advert:view.html.twig',
        //     array('id' => $id)
        // );

        // // Permet de faire une redirection
        // return $this->redirectToRoute('bva_platform_home');

        // // Créons nous même la réponse en JSON, grâce à la fonction json_encode()
        // $response = new Response(json_encode(array('id' => $id)));
        // // Ici nous définissons le content-type pour dire au navigateur que l'on renvoie du JSON et non du HTML
        // $response->headers->set('Content-Type', 'application/json');
        // return $response;

        // // Récupération de la session
        // $session = $request->getSession();
        // // On récupère le contenu de la variable user_id
        // $userId = $session->get('user_id');
        // // On définit une nouvelle valeur pour cette variable user_id
        // $session->set('user_id', 91);
        // // On n'oublie pas de retourner une réponse
        // return new Response("Je suis une page de test, je n'ai rien à dire");

        // ============================================================================

//        // Ici on récupérera l'annonce correspondant à l'id $id
//        $advert = array(
//            'title'   => 'Recherche développeur Symfony 2',
//            'id'      => $id,
//            'author'  => 'Alexandre',
//            'content' => 'Nous recherchons un développeur Symfony2 débutant à Lyon, Blabla',
//            'date'    => new \Datetime()
//        );

//      On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('BVAPlatformBundle:Advert')
        ;

//      On récupère l'entité correspondant à l'id $id
        $advert = $repository->find($id);

//      $advert est donc une instance de BVA\PlatformBundle\Entity\Advert
//      ou null si l'id $id n'existe pas, d'où ce if
        if($advert === null){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas");
        }

        return $this->render('BVAPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert
        ));
    }

    public function viewSlugAction($year, $slug, $format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au slug ".$slug.", créée en ".$year." et au format ".$format."."
        );
    }

    public function addAction(Request $request)
    {
        // $session = $request->getSession();
        
        // // Bien sûr, cette méthode devra réellement ajouter l'annonce

        // // Mais faisons comme ci c'était le cas
        // $session->getFlashBag()->add('info', 'Annonce bien enregistrée.');

        // // Le "flagBag" est ce qui contient les message flash dans la session
        // // Il peut bien sûr contenir plusieurs messages :
        // $session->getFlashBag()->add('info', 'Oui, Oui, elle est bien enregistrée.');

        // // On redirige vers la page de visualisation de cette annonce
        // return $this->redirectToRoute('bva_platform_view', array('id' => 5));

        // ===========================================================================

        // On récupère le service
        $antispam = $this->container->get('bva_platform.antispam');

        // On part du principe que $text contient le texte d'un message quelconque.
        $text = "....";
        if($antispam->isSpam($text))
        {
            throw new \Exception('Votre message a été détecté comme spam !');
        }

        // Création de l'entité
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor('Alexandre');
        $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla...");

        // On récupère l'entity managee
        $em = $this->getDoctrine()->getManager();

        // Etape 1 : on persiste l'entité
        $em->persist($advert);

        // Etape 2 : on flush tout ce qui a été persisté avant
        $em->flush();

        // La gestion d'un formulaire est particulière mais l'idée est la suivante :
        
        // Si la requète est en POST, c'est que le visiteur a soumis le formulaire
        if($request->isMethod('POST'))
        {
            // Ici on s'occupera de la création et de la gestion du formulaire
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cette annonce
            return $this->redirectToRoute('bva_platform_view', array('id' => $advert->getId()));
        }

        // Si on est pas en POST, alors on affiche le formulaire
        return $this->render('BVAPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
    }

    public function editAction($id, Request $request)
    {
        // Ici on récupère l'annonce correspondant à $id

        // // Même mécanique que pour l'ajout
        // if($request->isMethod('POST'))
        // {
        //     $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
        //     return $this->redirectToRoute('bva_platform_view', array('id' => $id));
        // }

        // return $this->render('BVAPlatformBundle:Advert:edit.html.twig');

        $advert = array(
            'title'   => 'Recherche développeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon, Blabla',
            'date'    => new \Datetime()
        );

        return $this->render('BVAPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }

    public function deleteAction($id)
    {
        // Ici on récupérera l'annonce correspondante à $id

        return $this->render('BVAPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction()
    {
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony 2'),
            array('id' => 5, 'title' => 'Mission webmaster'),
            array('id' => 9, 'title' => 'Offre de stage web designer')
        );

        return $this->render('BVAPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }    
}