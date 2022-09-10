<?php

namespace App\Repository;

use App\Entity\Emails;
use App\Entity\UsersSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Users;

/**
 * @extends ServiceEntityRepository<UsersSubscription>
 *
 * @method UsersSubscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersSubscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersSubscription[]    findAll()
 * @method UsersSubscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersSubscription::class);
    }

    public function getListExpiringSubscriptions(\DateTimeImmutable $dateTimeFrom, \DateTimeImmutable $dateTimeTo): array
    {
        $conn = $this->_em->getConnection();

        $sql = '
                SELECT 
                    u.id as user_id,
                    u.username,
                    e.id as email_id,
                    e.email,
                    u.confirmed as user_email_confirmed,
                    e.checked as email_checked,
                    e.valid as email_valid 
                FROM users_subscription us
                JOIN users u ON u.id = us.user_id
                JOIN emails e ON e.id = u.email_id
                WHERE us.validts BETWEEN :from AND :to
                AND (
                    u.confirmed = true 
                    OR e.checked = false 
                    OR e.checked = true AND e.valid = true OR e.valid IS NULL
                )
                ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'from' => $dateTimeFrom->getTimestamp(),
            'to' => $dateTimeTo->getTimestamp()
        ]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function add(UsersSubscription $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UsersSubscription $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
