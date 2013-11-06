<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{

	public function getQuestionsAction($type, $page, $nbParPage){
		

		$questionRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:question');

        $noteReponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:noteReponse');



        $listeQuestion = $questionRepository->findBy(array(), 
                                        array('date'=>'desc'),
                                        $nbParPage,
                                        ($page - 1) * $nbParPage);


        $nbQuestions = $questionRepository->getNombreQuestions();


        $returnArray=array();

        array_push($returnArray, array(
            'type' => $type,
            'nbParPage'=>$nbParPage,
            'page'=>$page,
            'nbQuestions'=>$nbQuestions,
            'slug'=>'_infos'
        ));

        foreach($listeQuestion as $Question){


            $reponse = 




            array_push($returnArray, array(
            	'id'=>$Question->getId(),
            	'sujet'=>$Question->getSujet(),
            	'description'=>$Question->getDescription(),
            	'date'=>$Question->getDate(),
                'membre_nom'=>$Question->getMembre()->getNom(),
                'slug'=>$Question->getSlug()
            ));

        }

        return new Response(json_encode($returnArray));
	}



}