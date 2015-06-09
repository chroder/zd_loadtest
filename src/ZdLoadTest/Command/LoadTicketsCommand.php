<?php

namespace ZdLoadTest\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadTicketsCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('tickets')
            ->setDescription('Generate new tickets')
            ->addArgument(
                'count',
                InputArgument::REQUIRED,
                'How many tickets to create'
            )
        ;
    }

    protected function zdExecute(InputInterface $input, OutputInterface $output)
    {
        $count = (int)$input->getArgument('count');
        if (!$count) {
            $output->writeln("<error>Please supply the `count` argument.</error>");
            return 1;
        }

        $output->writeln("<info>Loading {$count} tickets</info>");
        $ts = microtime(true);

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < $count; $i++) {
            $user_name = $faker->name;
            $user_email = $faker->safeEmail;

            $this->getZd()->tickets()->create(array(
                'subject' => $faker->realText(50),
                'comment' => array (
                    'body' => $faker->realText(400)
                ),
                'requester' => array('name' => $user_name, 'email' => $user_email)
            ));

            echo ".";
        }

        $output->writeln(sprintf("Done in %.2fs", microtime(true)-$ts));
        return 0;
    }
}