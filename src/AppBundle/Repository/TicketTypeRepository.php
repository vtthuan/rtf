<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TicketTypeRepository extends EntityRepository
{
    public function getActiveTicketTypes()
    {
        return $this->findBy(array('enabled' => true));
    }
}


