<?php

namespace App\Repository;

use App\Entity\Following;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function getFollowingUsers($user){
        $em = $this->getEntityManager();
        $following_repo = $em->getRepository(Following::class);
        $following = $following_repo->findBy(array('user'=>$user));
        $following_array = array();
        foreach($following as $follow ){
            $following_array[] = $follow->getFollowed();
        }
        $user_repo = $em->getRepository(Users::class);
        $users = $user_repo->createQueryBuilder('u')
                        ->where("u.id != :user AND u.id IN (:following)")
                        ->setParameter('user', $user->getID())
                        ->setParameter('following', $following_array)
                        ->orderBy('u.id', 'DESC');
        return $users;
    }
    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
