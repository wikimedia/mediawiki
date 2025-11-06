<?php

// Adapted from:
// * tests/phpunit/unit/includes/libs/Message/validateMessageValueTestData.php
// * tests/phpunit/includes/parser/validateParserCacheSerializationTestData.php

use MediaWiki\Logger\ConsoleLogger;
use MediaWiki\Maintenance\Maintenance;
use Wikimedia\Tests\SerializationTestUtils;

define( 'MW_AUTOLOAD_TEST_CLASSES', true );
define( 'MW_PHPUNIT_TEST', true );

require_once __DIR__ . '/../../../../maintenance/Maintenance.php';

// phpcs:disable MediaWiki.Files.ClassMatchesFilename.WrongCase
class ValidateMessageTestData extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addArg(
			'path',
			'Path of serialization files.',
			false
		);
		$this->addOption( 'create', 'Create missing serialization' );
		$this->addOption( 'update', 'Update mismatching serialization files' );
		$this->addOption( 'version', 'Specify version for which to check serialization. '
			. 'Also determines which files may be created or updated if '
			. 'the respective options are set.'
			. 'Unserialization is always checked against all versions. ', false, true );
	}

	public function execute() {
		$tests = [
			MessageTest::class,
		];
		foreach ( $tests as $testClass ) {
			$objClass = $testClass::getClassToTest();
			$this->validateSerialization(
				$objClass,
				$testClass::getSerializedDataPath(),
				$testClass::getSupportedSerializationFormats(),
				array_map( static function ( $testCase ) {
					return $testCase['instance'];
				}, $testClass::getTestInstancesAndAssertions() )
			);
		}
	}

	/**
	 * Ensures that objects will serialize into the form expected for the given version.
	 * If the respective options are set in the constructor, this will create missing files or
	 * update mismatching files.
	 *
	 * @param string $className
	 * @param string $defaultDirectory
	 * @param array $supportedFormats
	 * @param array $testInstances
	 */
	public function validateSerialization(
		string $className,
		string $defaultDirectory,
		array $supportedFormats,
		array $testInstances
	) {
		$ok = true;
		foreach ( $supportedFormats as $serializationFormat ) {
			$serializationUtils = new SerializationTestUtils(
				$this->getArg( 1 ) ?: $defaultDirectory,
				$testInstances,
				$serializationFormat['ext'],
				$serializationFormat['serializer'],
				$serializationFormat['deserializer']
			);
			$serializationUtils->setLogger( new ConsoleLogger( 'validator' ) );
			foreach ( $serializationUtils->getSerializedInstances() as $testCaseName => $currentSerialized ) {
				$expected = $serializationUtils
					->getStoredSerializedInstance( $className, $testCaseName, $this->getOption( 'version' ) );
				$ok = $this->validateSerializationData( $currentSerialized, $expected ) && $ok;
			}
		}
		if ( !$ok ) {
			$this->output( "\n\n" );
			$this->fatalError( "Serialization data mismatch! "
				. "If this was expected, rerun the script with the --update option "
				. "to update the expected serialization. WARNING: make sure "
				. "a forward compatible version of the code is live before deploying a "
				. "serialization change!\n"
			);
		}
	}

	private function validateSerializationData( string $data, \stdClass $fileInfo ): bool {
		if ( !$fileInfo->data ) {
			if ( $this->hasOption( 'create' ) ) {
				$this->output( 'Creating file: ' . $fileInfo->path . "\n" );
				file_put_contents( $fileInfo->path, $data );
			} else {
				$this->fatalError( "File not found: {$fileInfo->path}. "
					. "Rerun the script with the --create option set to create it."
				);
			}
		} else {
			if ( $data !== $fileInfo->data ) {
				if ( $this->hasOption( 'update' ) ) {
					$this->output( 'Data mismatch, updating file: ' . $fileInfo->currentVersionPath . "\n" );
					file_put_contents( $fileInfo->currentVersionPath, $data );
				} else {
					$this->output( 'Serialization MISMATCH: ' . $fileInfo->path . "\n" );
					return false;
				}
			} else {
				$this->output( "Serialization OK: " . $fileInfo->path . "\n" );
			}
		}
		return true;
	}
}

return ValidateMessageTestData::class;
