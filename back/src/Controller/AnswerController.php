<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Answer;
use App\Form\AnswerType;

class AnswerController extends AbstractController
{

    /**
     * @Route("/answer/add", name="answer_add")
     */
    public function add(Request $request)
    {
      $answer = new Answer();
      $form = $this->createForm(AnswerType::class, $answer);

      $form->handleRequest($request);
      if ($form->isSubmitted()) {
        $answer = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($answer);
        $em->flush();
      }

      return $this->render('form.html.twig', [
          'form' => $form->createView(),
      ]);
    }

    /**
     * @Route("/answer/delete/{id}", name="answer_delete")
     */
    public function delete($id)
    {
      $answer = $this->getDoctrine()
       ->getRepository(Answer::class)
       ->find($id);

       $em = $this->getDoctrine()->getManager();
       $em->remove($answer);
       $em->flush();
       return $this->redirectToRoute('question_detail',
        ['id' => $answer->getQuestion()->getId()]);
    }
}
