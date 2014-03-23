<?php

namespace Qwer\LottoDocumentsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
class ResultsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName("lotto:results");
        $this->setDescription("Парсинг результатов лотерей");
    }

    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $parsingServ = $this->getContainer()->get('lotto.result_parser');
        $parsingServ->check();
        $output->writeln("ok boss");
    }

}