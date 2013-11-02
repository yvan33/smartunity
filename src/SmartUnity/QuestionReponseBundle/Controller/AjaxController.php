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
                                        2,
                                        1);

        $returnArray=array();

        foreach($listeQuestion as $Question){

            array_push($returnArray, array(
            	'id'=>$Question->getId(),
            	'sujet'=>$Question->getSujet(),
            	'description'=>$Question->getDescription(),
            	'date'=>$Question->getDate()
            ));

        }

        return new Response(json_encode($returnArray));
	}



}