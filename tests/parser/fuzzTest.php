<?php

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\ScopedCallback;

require_once __DIR__ . '/../../maintenance/Maintenance.php';

define( 'MW_AUTOLOAD_TEST_CLASSES', true );

class ParserFuzzTest extends Maintenance {
	/** @var ParserTestRunner */
	private $parserTest;
	/** @var int */
	private $maxFuzzTestLength = 300;
	/** @var int */
	private $memoryLimit = 100;
	/** @var int */
	private $seed;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Run a fuzz test on the parser, until it segfaults ' .
			'or throws an exception' );
		$this->addOption( 'file', 'Use the specified file as a dictionary, ' .
			' or leave blank to use parserTests.txt', false, true, true );

		$this->addOption( 'seed', 'Start the fuzz test from the specified seed', false, true );
	}

	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		// Make RequestContext::resetMain() happy
		define( 'MW_PHPUNIT_TEST', true );

		TestSetup::applyInitialConfig();
	}

	public function execute() {
		$files = $this->getOption( 'file', [ __DIR__ . '/parserTests.txt' ] );
		$this->seed = intval( $this->getOption( 'seed', 1 ) ) - 1;
		$this->parserTest = new ParserTestRunner(
			new MultiTestRecorder,
			[] );
		$this->fuzzTest( $files );
	}

	/**
	 * Run a fuzz test series
	 * Draw input from a set of test files
	 * @param array $filenames
	 */
	public function fuzzTest( $filenames ) {
		$dict = $this->getFuzzInput( $filenames );
		$dictSize = strlen( $dict );
		$logMaxLength = log( $this->maxFuzzTestLength );

		$teardown = $this->parserTest->staticSetup();
		$teardown = $this->parserTest->setupDatabase( $teardown );
		$teardown = $this->parserTest->setupUploads( $teardown );

		$fakeTest = new ParserTest( [
			'testName' => '',
			'wikitext' => '',
			'html' => '',
			'options' => [],
			'config' => [],
		], [], '' );

		ini_set( 'memory_limit', $this->memoryLimit * 1048576 * 2 );

		$numTotal = 0;
		$numSuccess = 0;
		$user = new User;
		$opts = ParserOptions::newFromUser( $user );
		$title = Title::makeTitle( NS_MAIN, 'Parser_test' );

		while ( true ) {
			// Generate test input
			mt_srand( ++$this->seed );
			$totalLength = mt_rand( 1, $this->maxFuzzTestLength );
			$input = '';

			while ( strlen( $input ) < $totalLength ) {
				$logHairLength = mt_rand( 0, 1000000 ) / 1000000 * $logMaxLength;
				$hairLength = min( intval( exp( $logHairLength ) ), $dictSize );
				$offset = mt_rand( 0, $dictSize - $hairLength );
				$input .= substr( $dict, $offset, $hairLength );
			}

			$perTestTeardown = $this->parserTest->perTestSetup( $fakeTest );
			$parser = $this->parserTest->getParser();

			// Run the test
			try {
				$parser->parse( $input, $title, $opts );
				$numSuccess++;
			} catch ( Exception $exception ) {
				echo "Test failed with seed {$this->seed}\n";
				echo "Input:\n";
				printf( "string(%d) \"%s\"\n\n", strlen( $input ), $input );
				echo "$exception\n";
			}

			$numTotal++;
			ScopedCallback::consume( $perTestTeardown );

			if ( $numTotal % 100 == 0 ) {
				$usage = intval( memory_get_usage( true ) / $this->memoryLimit / 1048576 * 100 );
				echo "{$this->seed}: $numSuccess/$numTotal (mem: $usage%)\n";
				if ( $usage >= 100 ) {
					echo "Out of memory:\n";
					$memStats = $this->getMemoryBreakdown();

					foreach ( $memStats as $name => $usage ) {
						echo "$name: $usage\n";
					}
					return;
				}
			}
		}
	}

	/**
	 * Get a memory usage breakdown
	 * @return array
	 */
	private function getMemoryBreakdown() {
		$memStats = [];

		foreach ( $GLOBALS as $name => $value ) {
			$memStats['$' . $name] = $this->guessVarSize( $value );
		}

		$classes = get_declared_classes();

		foreach ( $classes as $class ) {
			$rc = new ReflectionClass( $class );
			$props = $rc->getStaticProperties();
			$memStats[$class] = $this->guessVarSize( $props );
			$methods = $rc->getMethods();

			foreach ( $methods as $method ) {
				$memStats[$class] += $this->guessVarSize( $method->getStaticVariables() );
			}
		}

		$functions = get_defined_functions();

		foreach ( $functions['user'] as $function ) {
			$rf = new ReflectionFunction( $function );
			$memStats["$function()"] = $this->guessVarSize( $rf->getStaticVariables() );
		}

		asort( $memStats );

		return $memStats;
	}

	/**
	 * Estimate the size of the input variable
	 * @param mixed $var
	 * @return int
	 */
	public function guessVarSize( $var ) {
		$length = 0;
		try {
			$length = strlen( @serialize( $var ) );
		} catch ( Exception $e ) {
		}
		return $length;
	}

	/**
	 * Get an input dictionary from a set of parser test files
	 * @param array $filenames
	 * @return string
	 */
	public function getFuzzInput( $filenames ) {
		$dict = '';

		foreach ( $filenames as $filename ) {
			$contents = file_get_contents( $filename );
			preg_match_all(
				'/!!\s*(input|wikitext)\n(.*?)\n!!\s*(result|html|html\/\*|html\/php)/s',
				$contents,
				$matches
			);

			foreach ( $matches[1] as $match ) {
				$dict .= $match . "\n";
			}
		}

		return $dict;
	}
}

$maintClass = ParserFuzzTest::class;
require_once RUN_MAINTENANCE_IF_MAIN;
