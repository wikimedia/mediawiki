<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer;

use Composer\Script\Event;
use MediaWiki\Composer\PhpUnitSplitter\PhpUnitXml;
use MediaWiki\Maintenance\ForkController;
use Shellbox\Shellbox;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../..';

require_once $basePath . '/includes/BootstrapHelperFunctions.php';
require_once $basePath . '/includes/Maintenance/ForkController.php';

/**
 * @license GPL-2.0-or-later
 */
class ComposerLaunchParallel extends ForkController {

	private const ALWAYS_EXCLUDE = [ 'Broken', 'ParserFuzz', 'Stub' ];
	private array $groups = [];
	private array $excludeGroups = [];

	public function __construct(
		array $groups,
		array $excludeGroups
	) {
		$this->groups = $groups;
		$this->excludeGroups = $excludeGroups;
		/**
		 * By default, the splitting process splits the tests into 8 groups. 7 of the groups are composed
		 * of evenly distributed test classes extracted from the `--list-tests-xml` phpunit function. The
		 * 8th group contains just the ExtensionsParserTestSuite.
		 */
		$splitGroupCount = 7;
		if ( $this->isDatabaseRun() ) {
			/**
			 * In the splitting, we put ExtensionsParserTestSuite in `split_group_7` on its own. We only
			 * need to run `split_group_7` when we run Database tests, since all Parser tests use the
			 * database. Running `split_group_7` when no matches tests get executed results in a phpunit
			 * error code.
			 */
			$splitGroupCount = 8;
		}
		parent::__construct( $splitGroupCount );
	}

	private function isDatabaseRun(): bool {
		return in_array( 'Database', $this->groups ) &&
			!in_array( 'Database', $this->excludeGroups );
	}

	/**
	 * @inheritDoc
	 */
	public function start(): string {
		$status = parent::start();
		if ( $status === 'child' ) {
			$this->runTestSuite( $this->getChildNumber() );
		}
		return $status;
	}

	protected function prepareEnvironment() {
		// Skip parent class method to avoid errors:
		// this script does not run inside MediaWiki, so there is no environment to prepare
	}

	private function runTestSuite( int $groupId ) {
		$command = "composer run --timeout=0 phpunit:entrypoint -- --testsuite split_group_"
			. $groupId . " --exclude-group " . Shellbox::escape(
				implode( ",", array_diff( $this->excludeGroups, $this->groups ) )
			);
		if ( count( $this->groups ) ) {
			$command .= " --group " . Shellbox::escape( implode( ",", $this->groups ) );
		}
		$groupName = $this->isDatabaseRun() ? "database" : "databaseless";
		$command .= " --cache-result-file=.phpunit_group_" . $groupId . "_" . $groupName . ".result.cache";
		$command .= " 2>&1";
		print( "Running command '" . $command . "' ..." . PHP_EOL );
		// Not sure about the best way to actually launch the tests here. The 'exec' here just
		// re-launches composer at the same binary location with a different set of arguments.
		// I wonder if there is a smarter way to launch a composer task from inside a composer
		// task and also supply arbitrary arguments to the subtask.
		$output = [];
		$resultCode = 0;
		// TODO: Consider using Shell::command() here instead of exec
		// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.exec
		exec( $command, $output, $resultCode );
		foreach ( $output as $line ) {
			print( $line . PHP_EOL );
		}
		exit( $resultCode );
	}

	private static function extractArgs(): array {
		$options = [];
		foreach ( [ "group", "exclude-group" ] as $argument ) {
			$groupIndex = array_search( "--" . $argument, $_SERVER['argv'] );
			if ( $groupIndex > 0 ) {
				if ( count( $_SERVER['argv'] ) > $groupIndex + 1 ) {
					$nextArg = $_SERVER['argv'][$groupIndex + 1];
					if ( strpos( $nextArg, "--" ) === 0 ) {
						throw new \InvalidArgumentException(
							"parameter " . $argument . " takes a variable - none supplied"
						);
					}
					$options[$argument] = $nextArg;
				} else {
					throw new \InvalidArgumentException(
						"parameter " . $argument . " takes a variable - not enough arguments supplied"
					);
				}
			}
		}
		return $options;
	}

	public static function launchTests( Event $event, array $groups, array $excludeGroups ): void {
		$phpUnitConfig = getcwd() . DIRECTORY_SEPARATOR . 'phpunit.xml';
		if ( !PhpUnitXml::isPhpUnitXmlPrepared( $phpUnitConfig ) ) {
			$event->getIO()->error( "phpunit.xml is not present or does not contain split test suites" );
			$event->getIO()->error( "run `composer phpunit:prepare-parallel:...` to generate the split suites" );
			exit( 1 );
		}
		$event->getIO()->info( "Running 'split_group_X' suites in parallel..." );
		$launcher = new ComposerLaunchParallel( $groups, $excludeGroups );
		$launcher->start();
		if ( $launcher->allSuccessful() ) {
			$event->getIO()->info( "All split_groups succeeded!" );
			exit( 0 );
		} else {
			$event->getIO()->warning( "Some split_groups failed - returning failure status" );
			exit( 1 );
		}
	}

	public static function launchTestsCustomGroups( Event $event ) {
		$options = self::extractArgs();
		if ( array_key_exists( 'exclude-group', $options ) ) {
			$excludeGroups = explode( ',', $options['exclude-group'] );
		} else {
			$excludeGroups = [ 'Broken', 'ParserFuzz', 'Stub', 'Standalone', 'Database' ];
		}
		if ( array_key_exists( 'group', $options ) ) {
			$groups = explode( ',', $options['group'] );
		} else {
			$groups = [];
		}
		self::launchTests( $event, $groups, $excludeGroups );
	}

	public static function launchTestsDatabase( Event $event ) {
		self::launchTests(
			$event,
			[ 'Database' ],
			array_merge( self::ALWAYS_EXCLUDE, [ 'Standalone' ] )
		);
	}

	public static function launchTestsDatabaseless( Event $event ) {
		self::launchTests(
			$event,
			[],
			array_merge( self::ALWAYS_EXCLUDE, [ 'Standalone', 'Database' ] )
		);
	}
}
