<?php

namespace ZdLoadTest\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zendesk\API\Client as ZendeskAPI;

abstract class BaseCommand extends Command
{
    /**
     * @var ZendeskAPI
     */
    private $zd;

    /**
     * @var string
     */
    private $zd_token;

    /**
     * @var string
     */
    private $zd_subdomain;

    /**
     * @var string
     */
    private $zd_username;

    protected function configure()
    {
        $this
            ->addOption(
                'username',
                'u',
                InputOption::VALUE_REQUIRED,
                'Specify your ZD account username'
            )
            ->addOption(
                'token',
                't',
                InputOption::VALUE_REQUIRED,
                'Specify your API token'
            )
            ->addOption(
                'subdomain',
                'd',
                InputOption::VALUE_REQUIRED,
                'Specify your ZD subdomain'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getOption('username');
        if (!$username) {
            $output->writeln("<error>Please supply the --username (-u) option.</error>");
            return 1;
        }

        $token = $input->getOption('token');
        if (!$token) {
            $output->writeln("<error>Please supply the --token (-t) option.</error>");
            return 1;
        }

        $subdomain = $input->getOption('subdomain');
        if (!$subdomain) {
            $output->writeln("<error>Please supply the --subdomain (-d) option.</error>");
            return 1;
        }
        $subdomain = preg_replace('#\.zendesk\.com$#i', '', $subdomain);

        $this->zd_token = $token;
        $this->zd_subdomain = $subdomain;
        $this->zd_username = $username;

        return $this->zdExecute($input, $output);
    }

    /**
     * @return ZendeskAPI
     */
    public function getZd()
    {
        if (!$this->zd) {
            $this->zd = new ZendeskAPI($this->zd_subdomain, $this->zd_username);
            $this->zd->setAuth('token', $this->zd_token);
        }
        return $this->zd;
    }

    /**
     * @param string $user_email
     * @return ZendeskAPI
     */
    public function getUserZd($user_email)
    {
        $zd = new ZendeskAPI($this->zd_subdomain, $user_email);
        $zd->setAuth('token', $this->zd_token);
        return $zd;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int null or 0 if everything went fine, or an error code
     */
    abstract protected function zdExecute(InputInterface $input, OutputInterface $output);
}