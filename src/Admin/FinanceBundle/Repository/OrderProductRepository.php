<?php 

namespace Admin\FinanceBundle\Repository;

use Doctrine\ORM\EntityRepository;

class OrderProductRepository extends EntityRepository
{
    public function findOneByIdJoinedToOrder($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p, o FROM AdminFinanceBundle:OrderProduct p
                JOIN p.product o
                WHERE p.id = :id'
            )->setParameter('id', $id);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    
    public function findAllJoinToOrder()
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p, o FROM AdminFinanceBundle:OrderProduct p
                JOIN p.product o
                WHERE p.order_id = 2'
            );

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
        
    }
    
}
