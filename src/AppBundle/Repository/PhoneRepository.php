<?php

namespace AppBundle\Repository;

/**
 * PhoneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PhoneRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllQueryBuilder($filter =' ')
    {
        $qb =  $this->createQueryBuilder('phone');

        if ($filter) {
            $qb->andWhere('phone.name LIKE :filter OR phone.brand LIKE :filter')
                ->setParameter('filter', '%'.$filter.'%');
        }

        return $qb;
    }
}
