<?php

namespace App\Tests\Command;

use App\Entity\Emails;
use App\Entity\Users;
use App\Entity\UsersSubscription;
use App\Repository\EmailsRepository;
use App\Repository\UsersRepository;
use App\Repository\UsersSubscriptionRepository;

class SendNotificationsAboutExpiringSubscriptionTest extends CommandTestCase
{
    /**
     * @var EmailsRepository $emailsRepository
     */
    private $emailsRepository;

    /**
     * @var UsersRepository $userRepository
     */
    private $userRepository;

    /**
     * @var UsersSubscriptionRepository $userSubscriptionRepository;
     */
    private $userSubscriptionRepository;

    public function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->emailsRepository = $container->get(EmailsRepository::class);
        $this->userRepository = $container->get(UsersRepository::class);
        $this->userSubscriptionRepository = $container->get(UsersSubscriptionRepository::class);

        parent::setUp();
    }

    public function tearDown(): void
    {
        unset(
            $this->emailsRepository,
            $this->userRepository,
            $this->userSubscriptionRepository
        );

        parent::tearDown();
    }

    public function testExecute(): void
    {
        $userSubscription = $this->createUsersSubscription();

        $output = $this->executeCommand(
            'app:send-notifications-about-expiring-subscription',
            []
        );

        $this->assertStringContainsString('Отправлено письмо на адрес '.$userSubscription->getUser()->getEmail()->getEmail(), $output);
    }

    private function createEmail(): Emails
    {
        $emails = new Emails();
        $emails
            ->setEmail('test@test.ru')
            ->setChecked(true)
            ->setValid(true);

        $this->emailsRepository->add($emails, true);

        return $emails;
    }

    private function createUserWithEmail(): Users
    {
        $user = new Users();
        $user
            ->setUsername('someUsername')
            ->setConfirmed(true)
            ->setEmail($this->createEmail());

        $this->userRepository->add($user, true);

        return $user;
    }

    private function createUsersSubscription(): UsersSubscription
    {
        $user = $this->createUserWithEmail();

        $userSubscription = new UsersSubscription();
        $userSubscription
            ->setUser($user)
            ->setValidts((new \DateTime())->modify('-2 hours')->getTimestamp());

        $this->userSubscriptionRepository->add($userSubscription, true);

        return $userSubscription;
    }
}
