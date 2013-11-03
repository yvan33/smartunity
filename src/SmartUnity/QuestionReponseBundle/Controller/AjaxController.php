<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{

	public function getQuestionsAction($type, $page, $nbParPage){
		

		$repository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:question');

        $listeQuestion = $repository->findBy(array(), 
                                        array('date'=>'desc'),
                                        $nbParPage,
                                        ($page - 1) * $nbParPage);

        $nbQuestions = $repository->getNombreQuestions();

        $returnArray=array();

        array_push($returnArray, array(
            'type' => $type,
            'nbParPage'=>$nbParPage,
            'page'=>$page,
            'nbQuestions'=>$nbQuestions,
            'slug'=>'_infos'
        ));

        foreach($listeQuestion as $Question){

            array_push($returnArray, array(
            	'id'=>$Question->getId(),
            	'sujet'=>$Question->getSujet(),
            	'description'=>$Question->getDescription(),
            	'date'=>$Question->getDate(),
                //'membre'=>$Question->getMembre(),
                'slug'=>$Question->getSlug()
            ));

            //echo $Question->getMembre()->getEmail();
            //echo utf8_decode($Question->getReponses()->get(1)->getDescription());
            //exit();

        }

        return new Response(json_encode($returnArray));
	}



}