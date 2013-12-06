<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * reponseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class reponseRepository extends EntityRepository
{

	public function getBestReponse($QuestionId) //Récupère l'id et la note de la meilleure réponse pour une question (en paramètre)
    { 
        $query = $this->_em->createQuery('
            SELECT r.id repId, SUM(n.note) somme
            FROM
            SmartUnityAppBundle:reponse r LEFT JOIN SmartUnityAppBundle:noteReponse n WITH r.id = n.reponse
            WHERE r.question = :QuestionId
            GROUP BY r.id
            ORDER BY r.dateCertification DESC, r.dateValidation DESC, somme DESC
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

    public function getNbCertifForUser($membreId)
    {
        $query = $this->_em->createQuery('
            SELECT COUNT(r.id)
            FROM
            SmartUnityAppBundle:reponse r
            WHERE r.membre = :membreId
            AND r.dateCertification IS NOT NULL
            ')
            ->setParameter('membreId', $membreId);

        $result = $query->getScalarResult();

        return $result[0][1];
    }

    public function getNbReponses($QuestionId){
         $query = $this->_em->createQuery('
            SELECT COUNT(r.id)
            FROM SmartUnityAppBundle:reponse r
            WHERE r.question = :QuestionId')
            ->setParameter('QuestionId', $QuestionId);

        $return = $query->getScalarResult();

        return $return[0][1];
    }


    public function getReponsesWithVotes($QuestionId, $page = 1, $nbParPage = 5, $tri = 'vote')
    {

        /*
        ----- Retourne un tableau à deux dimmensions. (si vide, false)
        ----- Pour chaque ligne, on a:
        ----- array(0 => Entité, 'upVote' => upVote, 'downVote' => downVote, 'note' => note)
        */

        $offset = ($page - 1) * $nbParPage;

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addScalarResult('upVote', 'upVote');
        $rsm->addScalarResult('downVote', 'downVote');
        $rsm->addScalarResult('note', 'note');
        $rsm->addRootEntityFromClassMetadata('SmartUnityAppBundle:reponse', 'r');
        
        $sql = 'SELECT b.*, IFNULL(d.downVote,0) as downVote, IFNULL(u.upVote,0) as upVote, IFNULL(d.downVote,0) + IFNULL(u.upVote,0) as note
                    FROM
                        (SELECT r.*
                        FROM reponse r
                        WHERE 
                        r.question_id = 20
                        GROUP BY r.id) as b
                    
                    LEFT JOIN
                    
                        (SELECT SUM(n.note) as downVote, r.id as reponse_id
                        FROM reponse r, noteReponse n
                        WHERE n.reponse_id = r.id
                        AND r.question_id = 20
                        AND n.note = -1
                        GROUP BY n.reponse_id) as d 
                    ON d.reponse_id = b.id
                        
                    LEFT JOIN
                        
                        (SELECT SUM(n.note) as upVote, r.id as reponse_id
                        FROM reponse r, noteReponse n
                        WHERE n.reponse_id = r.id
                        AND r.question_id = 20
                        AND n.note = 1
                        GROUP BY n.reponse_id) as u
                     ON b.id = u.reponse_id

                ORDER BY b.dateCertification DESC, b.dateValidation DESC,';


        /* OLD REQUEST

        SELECT R.*, IFNULL(N.upVote,0) as upVote, IFNULL(N.downVote,0) as downVote, IFNULL(N.note,0) as note
                FROM reponse R
                LEFT OUTER JOIN
                    (SELECT d.downVote as downVote, u.upVote as upVote, IFNULL(u.upVote,0) + IFNULL(d.downVote,0) as note, d.reponse_id as dreponse_id, u.reponse_id as ureponse_id
                    FROM
                        (SELECT SUM(n.note) as downVote, r.id as reponse_id
                        FROM reponse r, noteReponse n
                        WHERE n.reponse_id = r.id
                        AND r.question_id = :QuestionId
                        AND n.note = -1
                        GROUP BY n.reponse_id) as d 
                        
                        RIGHT OUTER JOIN
                        
                        (SELECT SUM(n.note) as upVote, r.id as reponse_id
                        FROM reponse r, noteReponse n
                        WHERE n.reponse_id = r.id
                        AND r.question_id = :QuestionId
                        AND n.note = 1
                        GROUP BY n.reponse_id) as u
                    ON u.reponse_id = d.reponse_id) as N
                ON (N.ureponse_id = R.id OR N.dreponse_id = R.id)
                WHERE R.question_id = :QuestionId
            */

        if($tri == 'vote')
            $sql .= ' note DESC, b.date DESC';
        elseif($tri == 'date')
            $sql .= ' b.date ASC';

        $sql .= '
                LIMIT :offset, :nbParPage';

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter('QuestionId', (int) $QuestionId);
        $query->setParameter('offset', (int) $offset);
        $query->setParameter('nbParPage', (int) $nbParPage);

        $result = $query->getResult();

        if(count($result) != 0)
            return $result;
        else 
            return false;

    }

}