<?php namespace Atrauzzi\LaravelDoctrine\Console;

use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Tools\SchemaTool;
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
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * @var ClassMetadataFactory
     */
    private $classMetadataFactory;

    /**
     * Create a new command instance.
     *
     * @param SchemaTool $schemaTool
     * @param ClassMetadataFactory $classMetadataFactory
     */
	public function __construct(SchemaTool $schemaTool, ClassMetadataFactory $classMetadataFactory) {
		parent::__construct();
        $this->schemaTool = $schemaTool;
        $this->classMetadataFactory = $classMetadataFactory;
    }

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire() {

		$this->comment('ATTENTION: This operation should not be executed in a production environment.');

		$this->info('Obtaining metadata...');
		$metadata = $this->classMetadataFactory->getAllMetadata();
		$this->info('Creating database schema...');
        $this->schemaTool->createSchema($metadata);
		$this->info('Database schema created successfully!');

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()	{
		return [
		];
	}

}
