<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tricks;
use App\Entity\Message;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TrickType;
use App\Form\MessageFormType;


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
    * @Route("/trick", name="trick")
    */
    public function getTrick(Request $request)
    {
        $user = $this->getUser();
        $id = $request->query->get('id');

        $messages = $this->getDoctrine()
            ->getRepository(Message::class)
            ->findBy(array('trickId' => $id), ['id' => 'DESC']);

        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message, [
        'user' => $user,'id'=> $id]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            return $this->redirectToRoute('trick', ['id' => $id]);
        }


        $id = $request->query->get('id');
        $trick = $this->getDoctrine()
        ->getRepository(Tricks::class)
        ->find($id);

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($trick->getCategory());


        return $this->render('tricks/trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'category' => $category->getName(),
            'messages' => $messages
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
    /**
    * @Route("/add", name="add")
    */
    public function getAdd(Request $request)
    {
        $categoriesData = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $categories = array();

        foreach ($categoriesData as $key => $value) {
            $categories[$value->getName()] = $value->getId();
        }
        $trick = new Tricks();
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

        return $this->render('tricks/add.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/delete", name="delete")
     */
    public function getDelete(Request $request)
    {
        $id = $request->query->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $trick = $entityManager->getRepository(Tricks::class)->find($id);
        return $this->render('tricks/delete.html.twig', [
            'trick' => $trick
        ]);
    }
    /**
     * @Route("/doDelete", name="doDelete")
     */
    public function getDoDelete(Request $request)
    {
        $id = $request->query->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $trick = $entityManager->getRepository(Tricks::class)->find($id);
        $entityManager->remove($trick);
        $entityManager->flush();
        return $this->redirectToRoute('tricks');
    }
}
