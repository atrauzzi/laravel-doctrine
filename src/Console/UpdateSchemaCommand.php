<?php namespace Atrauzzi\LaravelDoctrine\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\App;


class UpdateSchemaCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'doctrine:schema:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates your database schema to match your models.';

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

		$complete = $this->option('complete');
		$sqlOnly = $this->option('sql');

		$this->comment('ATTENTION: This operation should not be executed in a production environment.');

		$this->info('Obtaining metadata from your models...');
		$metadata = App::make('doctrine.metadata');

		$schemaTool = App::make('doctrine.schema-tool');

		$sqlToRun = $schemaTool->getUpdateSchemaSql($metadata, $complete);

		if(!count($sqlToRun)) {
			$this->info('Your database is already in sync with your model.');
			return;
		}

		if($sqlOnly) {
			$this->info('Here\'s the SQL that currently needs to run:');
			$this->info(implode(';' . PHP_EOL, $sqlToRun));
		}
		else {
			$this->info('Updating database schema...');
			$schemaTool->updateSchema($metadata, $complete);
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
			array('complete', null, InputOption::VALUE_OPTIONAL, 'If defined, all assets of the database which are not relevant to the current metadata will be dropped.', false),
			array('sql', null, InputOption::VALUE_NONE, 'Dumps the generated SQL statements to the screen (does not execute them).')
		);
	}

}
