<?php

declare(strict_types=1);

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Базовый класс для выполнения команд
 */
abstract class CommandTestCase extends KernelTestCase
{
    /**
     * Приложение
     *
     * @var Application
     */
    protected $application;

    /**
     * Установка окружения
     */
    protected function setUp(): void
    {
        parent::setUp();

        $kernel = $kernel = self::bootKernel();
        $this->application = new Application($kernel);
    }

    /**
     * Сброс окружения
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->application);
    }

    /**
     * Выполнение команды по наименованию
     *
     * @param string $commandName
     * @param array  $options
     *
     * @return string
     */
    protected function executeCommand(string $commandName, array $options = []): string
    {
        $command       = $this->application->find($commandName);
        $commandTester = new CommandTester($command);

        $commandData = array_merge(
            ['command' => $command->getName()],
            $options
        );
        $commandTester->execute($commandData);

        return $commandTester->getDisplay();
    }
}