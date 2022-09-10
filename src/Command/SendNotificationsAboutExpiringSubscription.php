<?php

namespace App\Command;

use App\Repository\EmailsRepository;
use App\Repository\UsersSubscriptionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:send-notifications-about-expiring-subscription')]
class SendNotificationsAboutExpiringSubscription extends Command
{
    protected static $defaultName = 'app:send-notifications-about-expiring-subscription';

    /**
     * @var UsersSubscriptionRepository $usersSubscriptionRepository
     */
    private $usersSubscriptionRepository;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var EmailsRepository $emailRepository
     */
    private $emailRepository;

    public function __construct(EntityManagerInterface $entityManager, EmailsRepository $emailRepository, UsersSubscriptionRepository $usersSubscriptionRepository)
    {
        $this->entityManager = $entityManager;
        $this->emailRepository = $emailRepository;
        $this->usersSubscriptionRepository = $usersSubscriptionRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $from = new \DateTimeImmutable('-5 day');
        $from = $from->setTime(0, 0, 0);
        $to = new \DateTimeImmutable('- 1 day');
        $to = $to->setTime(0, 0, 0);

        $output->writeln($from->format('Y-m-d H:i:s'));
        $output->writeln($to->format('Y-m-d H:i:s'));

        foreach ($this->usersSubscriptionRepository->getListExpiringSubscriptions($from, $to) as $item) {
            if (
                $item['user_email_confirmed']
                || ($item['email_checked'] && $item['email_valid'])
            ) {
                $this->sendEmail($item['email'], $item['username']);

                $output->writeln('Отправлено письмо на адрес '.$item['email']);

                return Command::SUCCESS;
            }

            if (false === $item['email_checked']) {
                $email = $this->emailRepository->find($item['email_id']);
                if (null === $email) {
                    throw new \RuntimeException('Email not found');
                }
                if (filter_var($item['email'], FILTER_VALIDATE_EMAIL) && \check_email($item['email'])) {
                    $email->setValid(true);
                } else {
                    $email->setValid(false);
                }
                $this->sendEmail($item['email'], $item['username']);

                $output->writeln('Отправлено письмо на адрес '.$item['email']);

                $email->setChecked(true);
                $this->entityManager->flush($email);

            }
        }

        return Command::SUCCESS;
    }

    private function sendEmail(string $email, string $username): void
    {
        \send_email(
            $email,
            'Система уведомлений об истекающей подписке',
            $username,
            'Необходимо продлить подписку',
            'Для продления подписки зайдите в личный кабинет'
        );
    }
}
