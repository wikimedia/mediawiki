<?php

namespace MediaWiki\Tests\Parser;

use CacheTime;
use Maintenance;
use MediaWiki\Logger\ConsoleLogger;
use ParserOutput;
use Wikimedia\Tests\SerializationTestUtils;

define( 'MW_AUTOLOAD_TEST_CLASSES', true );
define( 'MW_PHPUNIT_TEST', true );

require_once __DIR__ . '/../../../../maintenance/Maintenance.php';

// phpcs:disable MediaWiki.Files.ClassMatchesFilename.WrongCase
class ValidateParserCacheSerializationTestData extends Maintenance {

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
		$this->validateSerialization(
			CacheTime::class,
			array_map( static function ( $testCase ) {
				return $testCase['instance'];
			}, ParserCacheSerializationTestCases::getCacheTimeTestCases() )
		);
		$this->validateSerialization(
			ParserOutput::class,
			array_map( static function ( $testCase ) {
				return $testCase['instance'];
			}, ParserCacheSerializationTestCases::getParserOutputTestCases() )
		);
	}

	/**
	 * Ensures that objects will serialize into the form expected for the given version.
	 * If the respective options are set in the constructor, this will create missing files or
	 * update mismatching files.
	 *
	 * @param string $className
	 * @param array $testInstances
	 */
	public function validateSerialization( string $className, array $testInstances ) {
		$supportedFormats = ParserCacheSerializationTestCases::getSupportedSerializationFormats( $className );
		foreach ( $supportedFormats as $serializationFormat ) {
			$serializationUtils = new SerializationTestUtils(
				$this->getArg( 1 ) ?: __DIR__ . '/../../data/ParserCache',
				$testInstances,
				$serializationFormat['ext'],
				$serializationFormat['serializer'],
				$serializationFormat['deserializer']
			);
			$serializationUtils->setLogger( new ConsoleLogger( 'validator' ) );
			foreach ( $serializationUtils->getSerializedInstances() as $testCaseName => $currentSerialized ) {
				$expected = $serializationUtils
					->getStoredSerializedInstance( $className, $testCaseName, $this->getOption( 'version' ) );
				$this->validateSerializationData( $currentSerialized, $expected );
			}
		}
	}

	private function validateSerializationData( $data, $fileInfo ) {
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
					$this->fatalError( "Serialization data mismatch: {$fileInfo->path}. "
						. "If this was expected, rerun the script with the --update option "
						. "to update the expected serialization. WARNING: make sure "
						. "a forward compatible version of the code is live before deploying a "
						. "serialization change!" );
				}
			} else {
				$this->output( "Serialization OK: " . $fileInfo->path . "\n" );
			}
		}
	}
}

return ValidateParserCacheSerializationTestData::class;
