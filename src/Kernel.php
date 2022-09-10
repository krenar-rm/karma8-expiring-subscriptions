<?php

namespace App;

use App\Command\SendNotificationsAboutExpiringSubscription;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected $commands = [
        SendNotificationsAboutExpiringSubscription::class,
    ];
}
