<?php
/**
 * Created by PhpStorm.
 * User: iGusev
 * Date: 02/06/16
 * Time: 02:07
 */

namespace Command;

use iGusev\IntisTelecomCandidateTest\Command\ImportCurrencyCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCurrencyCommandTest extends \PHPUnit_Framework_TestCase
{

    const SUCCESS_IMPORT = '/Импорт успешно завершен/';
    const PARAMS_NOT_FOUND = '/Отсутствуют обязательные параметры/';
    const FILE_NOT_FOUND = '/Файл (.*) недоступен/';

    /**
     * @var Command
     */
    protected $command;
    /**
     * @var CommandTester
     */
    protected $commandTester;

    public function incorrectFilePathProvider()
    {
        return [
            ['/123.json']
        ];
    }

    public function incorrectUrlPathProvider()
    {
        return [
            ['https://google.com/123.json']
        ];
    }

    public function correctFilePathProvider()
    {
        return [
            ['tests/fixtures/rates.json']
        ];
    }

    public function correctUrlPathProvider()
    {
        return [
            ['tests/fixtures/url-rates.json']
        ];
    }

    public function setUp()
    {
        $application = new Application();
        $application->add(new ImportCurrencyCommand());

        $this->command = $application->find('import:currency');
        $this->commandTester = new CommandTester($this->command);
    }

    public function tearDown()
    {
        unset($this->commandTester);
    }

    public function testExecuteWithoutArguments()
    {
        $this->commandTester->execute([
            'command' => $this->command->getName()
        ]);

        $this->assertRegExp(static::PARAMS_NOT_FOUND, $this->commandTester->getDisplay());
    }

    /**
     * @param $incorrectFilePath
     *
     * @dataProvider incorrectFilePathProvider
     */
    public function testExecuteFileNotFoundFile($incorrectFilePath)
    {
        $this->commandTester->execute([
            'command' => $this->command->getName(),
            '--file' => $incorrectFilePath
        ]);

        $this->assertRegExp(static::FILE_NOT_FOUND, $this->commandTester->getDisplay());
    }

    /**
     * @param $incorrectUrlPath
     *
     * @dataProvider incorrectUrlPathProvider
     */
    public function testExecuteFileNotFoundUrl($incorrectUrlPath)
    {
        $this->commandTester->execute([
            'command' => $this->command->getName(),
            '--url' => $incorrectUrlPath
        ]);

        $this->assertRegExp(static::FILE_NOT_FOUND, $this->commandTester->getDisplay());
    }

    /**
     * @param $correctFilePath
     *
     * @dataProvider correctFilePathProvider
     */
    public function testExecuteWithFile($correctFilePath)
    {
        $this->commandTester->execute([
            'command' => $this->command->getName(),
            '--file' => realpath($correctFilePath)
        ]);

        $this->assertRegExp(static::SUCCESS_IMPORT, $this->commandTester->getDisplay());
    }
}
