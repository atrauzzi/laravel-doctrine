<?php namespace Atrauzzi\LaravelDoctrine\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand as DoctrineCreateCommand;


class CreateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'doctrine:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create schema.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire() {
		echo "arararf!";
	}

}