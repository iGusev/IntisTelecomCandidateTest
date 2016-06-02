<?php
/**
 * Created by PhpStorm.
 * User: iGusev
 * Date: 02/06/16
 * Time: 02:05
 */

namespace iGusev\IntisTelecomCandidateTest\Command;

use iGusev\IntisTelecomCandidateTest\BaseException;
use iGusev\IntisTelecomCandidateTest\FileNotFoundException;
use iGusev\IntisTelecomCandidateTest\InvalidJSONException;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;
use JsonSchema\RefResolver;
use JsonSchema\Uri\UriResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда импорта валют
 *
 * Class ImportCurrencyCommand
 * @package iGusev\IntisTelecomCandidateTest\Command
 */
class ImportCurrencyCommand extends Command
{
    /**
     * Таблица с валютами
     */
    const TABLE_CURRENCY = 'currency';

    /**
     * Путь к схеме импорта из файлов
     */
    const FILE_SCHEMA = 'src/config/file-schema.json';

    /**
     * Путь к схеме импорта из урлов
     */
    const URL_SCHEMA = 'src/config/url-schema.json';

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * Инициализация команды
     */
    protected function configure()
    {
        $this->setName('import:currency')
            ->setDescription('Импорт валют из различных источников')
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'Если установлен, будет произведен ' .
                'импорт из указанного файла'
            )
            ->addOption(
                'url',
                'u',
                InputOption::VALUE_REQUIRED,
                'Если установлен, будет произведен ' .
                'импорт из указанного url-адреса'
            );

        $manager = new Manager();

        $manager->addConnection([
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'intis_candidate_test',
            'username' => 'root',
            'password' => 'rootroot',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $this->connection = $manager->getConnection();
    }

    /**
     * Основная логика
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            if ($input->getOption('file')) {
                $this->importFromFile($input->getOption('file'));
                $output->writeln($input->getOption('file'));
            }
            if ($input->getOption('url')) {
                $this->importFromUrl($input->getOption('url'));
                $output->writeln($input->getOption('url'));
            }

            if (!$input->getOption('file') && !$input->getOption('url')) {
                $output->writeln("<question>Отсутствуют обязательные параметры. " .
                    "Для вызова справки используйте:\n" .
                    "bin/console help import:currency</question>");
            } else {
                $output->writeln('<info>Импорт успешно завершен</info>');
            }
        } catch (BaseException $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }
    }

    /**
     * Импорт из файла
     *
     * @param string $filepath
     *
     * @return bool
     *
     * @throws InvalidJSONException
     */
    protected function importFromFile(string $filepath): bool
    {
        $data = json_decode($this->getData($filepath));

        if ($this->validateJson($data, static::FILE_SCHEMA)) {
            foreach ($data as $item) {
                $this->setData(key($item), reset($item));
            }

            return true;
        }

        throw new InvalidJSONException("Невалидный JSON в файле {$filepath}");
    }

    /**
     * Импорт по урлу
     *
     * @param string $url
     *
     * @return bool
     *
     * @throws InvalidJSONException
     */
    protected function importFromUrl(string $url): bool
    {
        $data = json_decode($this->getData($url));

        if ($this->validateJson($data, static::URL_SCHEMA)) {
            foreach ($data->rates as $item) {
                $this->setData($item->symbol, $item->rate);
            }

            return true;
        }

        throw new InvalidJSONException("Невалидный JSON в файле {$url}");
    }

    /**
     * Получение содержимого
     *
     * @param string $path
     *
     * @return string
     *
     * @throws FileNotFoundException
     */
    protected function getData(string $path)
    {
        if (($data = @file_get_contents($path)) !== false) {
            return $data;
        }

        throw new FileNotFoundException("Файл {$path} недоступен");
    }

    /**
     * Запись в бд
     *
     * @param string $symbol
     * @param int $rate
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    protected function setData(string $symbol, int $rate): bool
    {
        return $this->connection->table(static::TABLE_CURRENCY)
            ->updateOrInsert(['symbol' => $symbol], ['rate' => $rate]);
    }

    /**
     * Валидация файлов по json-схеме
     *
     * @param $data
     * @param string $schemaPath
     *
     * @return bool
     */
    protected function validateJson($data, string $schemaPath)
    {
        $refResolver = new RefResolver(new UriRetriever(), new UriResolver());
        $schema = $refResolver->resolve('file://' . realpath($schemaPath));

        $validator = new Validator();
        $validator->check($data, $schema);

        return $validator->isValid();
    }
}
