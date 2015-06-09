<?php

use Symfony\Component\Console\Application;
use ZdLoadTest\Command;

require __DIR__.'/bootstrap.php';

class Kernel
{
	/**
	 * @var Application
	 */
	private $app;

	public static function run()
	{
		$kernel = new self();
		return $kernel->getApp()->run();
	}

	public function __construct()
	{
		$this->app = new Application();
		$this->initCommands();
	}

	private function initCommands()
	{
        $this->app->add(new Command\LoadTicketsCommand());
	}

	public function getApp()
	{
		return $this->app;
	}
}