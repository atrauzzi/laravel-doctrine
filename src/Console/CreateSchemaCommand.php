<?php namespace Atrauzzi\LaravelDoctrine\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;


class CreateSchemaCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'doctrine:schema:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates your database schema according to your models.';

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
		$this->comment('ATTENTION: This operation should not be executed in a production environment.');
		$this->info('Obtaining metadata...');
        $metadata = $this->laravel->make('Doctrine\ORM\Mapping\ClassMetadataFactory')->getAllMetadata();
		$this->info('Creating database schema...');
        $schemaTool = $this->laravel->make('Doctrine\ORM\Tools\SchemaTool');
        $schemaTool->createSchema($metadata);
		$this->info('Database schema created successfully!');
    }

}
