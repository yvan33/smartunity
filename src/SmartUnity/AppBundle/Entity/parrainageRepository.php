<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * parrainageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class parrainageRepository extends EntityRepository
{

	public function getParrainByCode($code){


      	$query = $this->_em->createQuery('
      		SELECT p
      		FROM SmartUnityAppBundle:parrainage p
      		WHERE p.code = :code')
            ->setParameter('code', $code);

            $result = $query->getResult();


            if(count($result) != 0)
                  return $result[0];
            else 
                  return false;

 

	}


}
