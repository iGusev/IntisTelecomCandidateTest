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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCurrencyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('import:currency')
            ->setDescription('Импорт валют из различных источников')
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'Если установлен, будет произведен импорт из указанного файла'
            )
            ->addOption(
                'url',
                'u',
                InputOption::VALUE_REQUIRED,
                'Если установлен, будет произведен импорт из указанного url-адреса'
            );
    }

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
     * @throws FileNotFoundException
     */
    protected function importFromFile(string $filepath): bool
    {
        if (file_exists($filepath)) {
            return true;
        }

        throw new FileNotFoundException("Файл {$filepath} не найден");
    }

    /**
     * Импорт по урлу
     *
     * @param string $url
     *
     * @return bool
     *
     * @throws FileNotFoundException
     */
    protected function importFromUrl(string $url): bool
    {
        if (($data = @file_get_contents($url)) !== false) {
            return true;
        }

        throw new FileNotFoundException("Файл по адресу {$url} недоступен");
    }
}