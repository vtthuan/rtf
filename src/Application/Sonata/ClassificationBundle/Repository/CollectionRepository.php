<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Sonata\ClassificationBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Sonata\ClassificationBundle\Entity\Collection;

class CollectionRepository extends EntityRepository
{
    public function getActiveCollections()
    {
        return $this->findBy(array('enabled' => true));
    }
}

