<?php namespace Atrauzzi\LaravelDoctrine\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Support\Facades\App;

class DropSchemaCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'doctrine:schema:drop';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Drop the complete database schema of EntityManager Storage Connection.';

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

		$sqlOnly = $this->option('sql');

		$this->comment('ATTENTION: This operation should not be executed in a production environment.');

		$this->info('Obtaining metadata from your models...');
		$metadata = App::make('doctrine.metadata');

		$schemaTool = App::make('doctrine.schema-tool');

		$sqlToRun = $schemaTool->getDropSchemaSql($metadata);

		if(!count($sqlToRun)) {
			$this->info('None of your current models exist in the schema.');
			return;
		}

		if($sqlOnly) {
			$this->info('Here\'s the SQL to drop your models from the schema:');
			$this->info(implode(';' . PHP_EOL, $sqlToRun));
		}
		else {
			$this->info('Dropping all models from the current schema...');
			$schemaTool->dropSchema($metadata);
			$this->info('Database schema updated successfully!');
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()	{
		return array(
			array('sql', null, InputOption::VALUE_NONE, 'Dumps the generated SQL statements to the screen (does not execute them).')
		);
	}

}