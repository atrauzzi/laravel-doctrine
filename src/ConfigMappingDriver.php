<?php namespace Atrauzzi\LaravelDoctrine {

	use Doctrine\Common\Persistence\Mapping\ClassMetadata;
	use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
	use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;


	class ConfigMappingDriver implements MappingDriver {

		/**
		 * Loads the metadata for the specified class into the provided container.
		 *
		 * @param string $className
		 * @param ClassMetadata|\Doctrine\ORM\Mapping\ClassMetadata $metadata
		 *
		 * @return void
		 */
		public function loadMetadataForClass($className, ClassMetadata $metadata) {

			$mapping = config(sprintf('doctrine.mappings.%s', $className));

			$builder = new ClassMetadataBuilder($metadata);

			if(!empty($mapping['abstract']))
				$builder->setMappedSuperClass();

			if(!empty($mapping['table']))
				$builder->setTable($mapping['table']);

			if(!empty($mapping['indexes']))
				foreach($mapping['indexes'] as $name => $columns)
					$builder->addIndex($columns, $name);

			if(!empty($mapping['repository']))
				$builder->setCustomRepositoryClass($mapping['repository']);

			if(!empty($mapping['fields']))
				foreach($mapping['fields'] as $fieldName => $fieldConfig)
					$this->mapField($builder, $fieldName, $fieldConfig);

		}

		/**
		 * Gets the names of all mapped classes known to this driver.
		 *
		 * @return array The names of all mapped classes known to this driver.
		 */
		public function getAllClassNames() {
			return array_keys(config('doctrine.mappings', []));
		}

		/**
		 * Returns whether the class with the specified name should have its metadata loaded.
		 * This is only the case if it is either mapped as an Entity or a MappedSuperclass.
		 *
		 * @param string $className
		 *
		 * @return boolean
		 */
		public function isTransient($className) {
			return array_key_exists($className, config('doctrine.mappings', []));
		}

		//
		//
		//

		/**
		 * Parses configuration for a field and adds it to the class metadata.
		 *
		 * @param ClassMetadataBuilder $builder
		 * @param string $name
		 * @param array|string $config
		 */
		protected function mapField(ClassMetadataBuilder $builder, $name, $config) {

			if(!empty($config['name']))
				$name = $config['name'];

			if(empty($config['type']))
				$type = $config;
			else
				$type = $config['type'];

			$field = $builder->createField($name, $type);

			if(!empty($config['strategy']))
				$field->generatedValue(strtoupper($config['strategy']));

			if(!empty($config['nullable']))
				$field->nullable(true);

			$builder->mapField($config);

			$field->build();

		}

	}

}
