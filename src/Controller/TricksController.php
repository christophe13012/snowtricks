<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tricks;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TrickType;


class TricksController extends AbstractController
{
    /**
    * @Route("/tricks", methods={"GET","HEAD"}, name="tricks")
    */
    public function getTricks()
    {
        $tricks = $this->getDoctrine()
        ->getRepository(Tricks::class)
        ->findAll();

        foreach ($tricks as $key => $value) {
            $id = $value->getCategory();
            $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);
            $value->catName = $category->getName();
        }

        return $this->render('tricks/tricks.html.twig', [
            'tricks' => $tricks,
        ]);
    }
    /**
    * @Route("/trick", methods={"GET","HEAD"}, name="trick")
    */
    public function getTrick(Request $request)
    {
        $id = $request->query->get('id');
        $trick = $this->getDoctrine()
        ->getRepository(Tricks::class)
        ->find($id);

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($trick->getCategory());


        return $this->render('tricks/trick.html.twig', [
            'trick' => $trick,
            'category' => $category->getName()
        ]);
    }
    /**
    * @Route("/update", name="update")
    */
    public function getUpdate(Request $request)
    {
        $categoriesData = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $categories = array();

        foreach ($categoriesData as $key => $value) {
            $categories[$value->getName()] = $value->getId();
        }
        
        $id = $request->query->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $trick = $entityManager->getRepository(Tricks::class)->find($id);
        $form = $this->createForm(TrickType::class, $trick, [
            'categories' => $categories
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();
    
            return $this->redirectToRoute('tricks');
        }

        return $this->render('tricks/update.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }
}
