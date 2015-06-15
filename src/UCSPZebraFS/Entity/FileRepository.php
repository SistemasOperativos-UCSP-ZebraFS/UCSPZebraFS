<?php

namespace UCSPZebraFS\Entity;

use Doctrine\ORM\EntityRepository;

class FileRepository extends EntityRepository
{
    public function getSimilarSizFiles($size)
    {
        $qb = $this->createQueryBuilder('f')
            ->where('f.status = 0')
            ->andWhere('f.size BETWEEN :lower AND :upper')
            ->setParameters(array(
                'lower' => $size - 10000,
                'upper' => $size + 10000
            ));

        return $qb->getQuery()->execute();
    }
}