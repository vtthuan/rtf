<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PostRepository extends EntityRepository {

    public function getQbIsActive() {
        $qb = $this->createQueryBuilder('p');

        return $qb->where($qb->expr()->eq('p.actif', ':actif'))
        ->setParameter(':actif', true);
//        return $qb
//                ->join('p.category', 'c')
//                ->where($qb->expr()->andX(
//                        $qb->expr()->eq('p.actif', ':actif'), 
//                        $qb->expr()->eq('c.actif', ':actif'))
//                        )
//                ->setParameter(':actif', true);
    }

}
