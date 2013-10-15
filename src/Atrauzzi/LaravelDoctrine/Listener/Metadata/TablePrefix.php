<?php namespace Atrauzzi\LaravelDoctrine\Listener\Metadata;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Listen for doctrine's loadClassMetadata event that will prefix table name with the specified value
 *
 * @author vittee
 */
class TablePrefix {

	private $prefix;
	
	function __construct($prefix) {
		$this->prefix = $prefix;
	}

	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs) {
		$classMetadata = $eventArgs->getClassMetadata();
		$classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
		foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
			if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY) {
				$mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
				$classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
			}
		}
    }
}

?>
