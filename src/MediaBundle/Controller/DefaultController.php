<?php

namespace MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MediaBundle\Entity\Album;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use MediaBundle\Entity\Commentaire;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/" , name="index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $albums = $em->getRepository('MediaBundle:Album')->findAll();

        return $this->render('MediaBundle:Default:index.html.twig', array(
            'albums' => $albums,
        ));
    }

    /**
     * Finds and displays a album entity.
     *
     * @Route("/{id}", name="album_show")
     * @Method("GET")
     */
    public function showAction(Album $album, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaires = $em->getRepository('MediaBundle:Commentaire')->findAll();

        $commentaire = new Commentaire();
        $form = $this->createForm('MediaBundle\Form\CommentaireType', $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush($commentaire);

            return $this->redirectToRoute('commentaire_show', array('id' => $commentaire->getId()));
        }

        return $this->render('MediaBundle:Default:album.html.twig', array(
            'album' => $album,
            'commentaires' => $commentaires,
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ));
    }

}