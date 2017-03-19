<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Word;
use Doctrine\ORM\EntityRepository;

class WordRepository extends EntityRepository
{
    public function findPossibleWords($word)
    {
        $em = $this->getEntityManager();
        $repository = $em->getRepository('AppBundle:Word');
        $words = $repository->findBy(
            array('text'=>$word)    
        );
        return $words;
    }
}