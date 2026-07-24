<?php

namespace MediaWiki\Tests\Content;

use MediaWiki\Logger\ConsoleLogger;
use MediaWiki\Maintenance\Maintenance;
use Wikimedia\Tests\SerializationTestTrait;
use Wikimedia\Tests\SerializationTestUtils;

define( 'MW_AUTOLOAD_TEST_CLASSES', true );
define( 'MW_PHPUNIT_TEST', true );

require_once __DIR__ . '/../../maintenance/Maintenance.php';

// phpcs:disable MediaWiki.Files.ClassMatchesFilename.WrongCase
class ValidateSerializationTestData extends Maintenance {

	/**
	 * Using fully-qualified names allows these tests to be thematically grouped
	 * while also being alphabetically sorted.
	 *
	 * TODO: Add an attribute so that extensions can add to this array.
	 *
	 * @phpcs-require-sorted-array
	 */
	private const CORE_TEST_CLASSES = [
		\MediaWiki\Tests\Content\CssContentTest::class,
		\MediaWiki\Tests\Content\JavaScriptContentTest::class,
		\MediaWiki\Tests\Content\WikitextContentTest::class,
		\MediaWiki\Tests\Integration\Edit\SimpleParsoidOutputStashSerializationTest::class,
		\MediaWiki\Tests\Language\MessageTest::class,
		\MediaWiki\Tests\Parser\CacheTimeTest::class,
		\MediaWiki\Tests\Parser\ParserOutputTest::class,
		\MediaWiki\Tests\Storage\PageEditStashContentsTest::class,
		\Wikimedia\Tests\Message\DataMessageValueTest::class,
		\Wikimedia\Tests\Message\ListParamTest::class,
		\Wikimedia\Tests\Message\MessageValueTest::class,
		\Wikimedia\Tests\Message\ScalarParamTest::class,
	];

	public function __construct() {
		parent::__construct();

		$this->addDescription(
			'Validate or update data files for tests that use SerializationTestTrait' );
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
		$this->addOption( 'filter', 'Only process tests matching a regex', withArg: true );
	}

	/** @inheritDoc */
	public function execute() {
		$ok = true;
		$numDone = 0;
		foreach ( self::CORE_TEST_CLASSES as $testClass ) {
			if ( !$this->matchesFilter( $testClass ) ) {
				continue;
			}
			/** @var SerializationTestTrait $testClass */
			$objClass = $testClass::getClassToTest();
			$ok = $this->validateSerialization(
				$objClass,
				$testClass::getSerializedDataPath(),
				$testClass::getSupportedSerializationFormats(),
				array_map( static function ( $testCase ) {
					return $testCase['instance'];
				}, $testClass::getTestInstancesAndAssertions() )
			) && $ok;
			$numDone++;
		}
		if ( !$numDone ) {
			$this->output( "WARNING: No tests matched the filter\n" );
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
		return $ok;
	}

	private function matchesFilter( string $className ): bool {
		$filter = $this->getOption( 'filter' );
		if ( $filter === null ) {
			return true;
		}
		return (bool)preg_match( '{' . $filter . '}', $className );
	}

	/**
	 * Ensures that objects will serialize into the form expected for the given version.
	 * If the respective options are set in the constructor, this will create missing files or
	 * update mismatching files.
	 *
	 * @param class-string $className
	 * @param string $defaultDirectory
	 * @param array $supportedFormats
	 * @param array $testInstances
	 * @return bool
	 */
	public function validateSerialization(
		string $className,
		string $defaultDirectory,
		array $supportedFormats,
		array $testInstances
	): bool {
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
		return $ok;
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

return ValidateSerializationTestData::class;
