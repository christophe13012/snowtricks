<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tricks;
use App\Entity\Category;

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
}
