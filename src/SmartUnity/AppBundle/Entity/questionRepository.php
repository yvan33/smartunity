<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * questionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class questionRepository extends EntityRepository
{
    

    /////////////////////////////
    //////       COUNT QUESTIONS
    /////////////////////////////

    //Count les questions on Fire
    public function getNombreQuestionsOnFire(){

    	$rsm = new ResultSetMappingBuilder($this->getEntityManager());

        $rsm->addScalarResult('nb_questions', 'nb');

        $sql = 'SELECT COUNT(q.id) AS nb_questions
                FROM 
                    (SELECT r.question_id AS question_id, r.dateValidation as date_v
                    FROM reponse r
                    WHERE NOT r.dateValidation <=> NULL) as c
                RIGHT JOIN question q ON q.id = c.question_id
                WHERE c.date_v <=> NULL
                AND q.date < SUBTIME(NOW(), \'0 48:00:00.0000\')
                AND q.signaler = 0';

        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getSingleScalarResult();
    }

    //Count les dernières questions
    public function getNombreLastQuestions(){

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());

        $rsm->addScalarResult('nb_questions', 'nb');

        $sql = 'SELECT COUNT(q.id) AS nb_questions
                FROM 
                    (SELECT r.question_id AS question_id, r.dateValidation as date_v
                    FROM reponse r
                    WHERE NOT r.dateValidation <=> NULL) as c
                RIGHT JOIN question q ON q.id = c.question_id
                WHERE c.date_v <=> NULL
                AND q.signaler = 0';

        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getSingleScalarResult();
    }

    //Count les dernières questions
    public function getNombreValidatedQuestions(){

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());

        $rsm->addScalarResult('nb_questions', 'nb');

        $sql = 'SELECT COUNT(q.id) AS nb_questions
                FROM 
                    (SELECT r.question_id AS question_id, r.dateValidation as date_v
                    FROM reponse r
                    WHERE NOT r.dateValidation <=> NULL) as c
                RIGHT JOIN question q ON q.id = c.question_id
                WHERE NOT c.date_v <=> NULL
                AND q.signaler = 0';

        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getSingleScalarResult();
    }


    public function getNbQuestionsForUser($membreId)
    {
        $query = $this->_em->createQuery('
            SELECT COUNT(q.id)
            FROM
            SmartUnityAppBundle:question q
            WHERE q.membre = :membreId
            AND q.signaler = 0
            ')
            ->setParameter('membreId', $membreId);

        $result = $query->getScalarResult();

        return $result[0][1];
    }


    public function isQuestionValid($questionId)
    {
        $query = $this->_em->createQuery('
            SELECT COUNT(q.id)
            FROM
            SmartUnityAppBundle:question q, SmartUnityAppBundle:reponse r
            WHERE r.question = q.id
            AND r.dateValidation IS NOT NULL
            AND q.id = :questionId
            AND q.signaler = 0
            ')
            ->setParameter('questionId', $questionId);

        $result = $query->getScalarResult();

        if($result[0][1]>0)
            return true;
        else
            return false;
    }

    public function isQuestionCertif($questionId)
    {
        $query = $this->_em->createQuery('
            SELECT COUNT(q.id)
            FROM
            SmartUnityAppBundle:question q, SmartUnityAppBundle:reponse r
            WHERE r.question = q.id
            AND r.dateCertification IS NOT NULL
            AND q.id = :questionId
            AND q.signaler = 0
            ')
            ->setParameter('questionId', $questionId);

        $result = $query->getScalarResult();

        if($result[0][1]>0)
            return true;
        else
            return false;
    }



    /////////////////////////////
    //////       LISTES QUESTIONS
    /////////////////////////////

    //Liste de questions On Fire
    public function getQuestionsOnFire($nbParPage, $page){

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());

        $rsm->addRootEntityFromClassMetadata('SmartUnityAppBundle:question', 'q');
    	
        $offset = ($page - 1) * $nbParPage;

        $sql = 'SELECT DISTINCT q.*
                FROM 
                    (SELECT r.question_id AS question_id, r.dateValidation as date_v
                    FROM reponse r
                    WHERE NOT r.dateValidation <=> NULL) as c
                RIGHT JOIN question q ON q.id = c.question_id
                WHERE c.date_v <=> NULL
                AND q.date < SUBTIME(NOW(), \'0 48:00:00.0000\')
                AND q.signaler = 0
                ORDER BY q.date ASC
                LIMIT :offset, :nbParPage';

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter('offset', (int) $offset);
        $query->setParameter('nbParPage', (int) $nbParPage);

        $result = $query->getResult();

        if(count($result) != 0)
            return $result;
        else 
            return false;
    }

    //Liste des dernières questions sans réponses validées
    public function getLastQuestions($nbParPage, $page){
        
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('SmartUnityAppBundle:question', 'q');
        
        $offset = ($page - 1) * $nbParPage;

        $sql = 'SELECT DISTINCT q.*
                FROM 
                    (SELECT r.question_id AS question_id, r.dateValidation as date_v
                    FROM reponse r
                    WHERE NOT r.dateValidation <=> NULL) as c
                RIGHT JOIN question q ON q.id = c.question_id
                WHERE c.date_v <=> NULL
                AND q.signaler = 0
                ORDER BY q.date DESC
                LIMIT :offset, :nbParPage';

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter('offset', (int) $offset);
        $query->setParameter('nbParPage', (int) $nbParPage);

        $result = $query->getResult();

        if(count($result) != 0)
            return $result;
        else 
            return false;
    }

    //Liste des questions avec une réponse validée
    public function getValidatedQuestions($nbParPage, $page){
        
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('SmartUnityAppBundle:question', 'q');
        
        $offset = ($page - 1) * $nbParPage;

        $sql = 'SELECT DISTINCT q.*
                FROM 
                    (SELECT r.question_id AS question_id, r.dateValidation as date_v
                    FROM reponse r
                    WHERE NOT r.dateValidation <=> NULL) as c
                RIGHT JOIN question q ON q.id = c.question_id
                WHERE NOT c.date_v <=> NULL
                AND q.signaler = 0
                ORDER BY q.date DESC
                LIMIT :offset, :nbParPage';

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter('offset', (int) $offset);
        $query->setParameter('nbParPage', (int) $nbParPage);

        $result = $query->getResult();

        if(count($result) != 0)
            return $result;
        else 
            return false;
    }



}