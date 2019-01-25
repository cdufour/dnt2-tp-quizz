<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Category;
use App\Form\CategoryType;


class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
      $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy([], ['name' => 'ASC']);

      return $this->render('category/index.html.twig', [
          'categories' => $categories,
      ]);
    }

    /**
     * @Route("/category/json", name="category_json")
     */
    public function index_json()
    {
      $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAllAssoc()
        ;

      // json_encode encode le corps de la requête
      // mais n'ajoute aucun header supplémentaire
      // indiquant au client qu'il s'agit de json
      // return new Response(json_encode($categories));
      return new JsonResponse($categories);
    }

    /**
     * @Route("/category/add", name="category_add")
     */
    public function add(Request $request)
    {
      $category = new Category();
      $form = $this->createForm(CategoryType::class, $category);

      $form->handleRequest($request);
      if ($form->isSubmitted()) {
        $category = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();
        return $this->redirectToRoute('category');
      }

      return $this->render('category/form.html.twig', [
          'form' => $form->createView(),
      ]);
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
     public function delete($id)
     {
       $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category');
     }
}
