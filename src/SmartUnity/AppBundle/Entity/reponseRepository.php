<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * reponseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class reponseRepository extends EntityRepository
{

	public function getBestReponse($QuestionId){ //Récupère l'id et la note de la meilleure réponse pour une question (en paramètre)

        $query = $this->_em->createQuery('
            SELECT r.id repId, SUM(n.note) somme
            FROM
            SmartUnityAppBundle:reponse r LEFT JOIN SmartUnityAppBundle:noteReponse n WITH r.id = n.reponse
            WHERE r.question = :QuestionId
            GROUP BY r.id
            ORDER BY somme DESC
            ')
            ->setParameter('QuestionId', $QuestionId)
            ->setMaxResults(1)
            ->setFirstResult(0);

        $result = $query->getResult();

        if(count($result) != 0)
            return $result[0];
        else 
            return false;

        /* EQUIVALENT SQL

        SELECT reponse.id AS repId, SUM( noteReponse.note ) AS somme
        FROM reponse
        LEFT JOIN noteReponse ON reponse.id = noteReponse.reponse_id
        WHERE reponse.question_id = '1'
        GROUP BY reponse.id
        ORDER BY somme DESC
        LIMIT 0 , 1

        */
	}
}