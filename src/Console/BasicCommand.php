<?php


namespace AppBundleCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BasicCommand extends Command
{
	protected function configure()
	{
		$this
		->setName('say:hello')
		->setDescription('Basic command to generate something')
		;
	}
	
	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('<comment>Hello SitePoint Folks!</comment>');
	}
}