<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Score;

class ScoreController extends AbstractController
{
    /**
     * @Route("/score", name="score_new", methods={"POST"})
     */
    public function new(Request $request)
    {
      $request_body = json_decode($request->getContent());
      $username = $request_body->username;
      $result = $request_body->result;
      $em = $this->getDoctrine()->getManager();
      // la date est calculée dans le constructeur de Score
      // $date = date('Y-m-d H:i:s');
      $score = new Score($username, $result);
      $em->persist($score);
      $em->flush();
      return new JsonResponse($score->getId());
    }

    /**
     * @Route("/score", name="score_list", methods={"GET"})
     */
    public function list()
    {
      $scores = $this->getDoctrine()
        ->getRepository(Score::class)
        ->findBy([], ['result' => 'DESC']);

      return $this->render('score/index.html.twig', [
        'scores' => $scores
      ]);
    }
}
