<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer;

use Composer\Script\Event;
use MediaWiki\Composer\PhpUnitSplitter\PhpUnitConsoleOutputProcessingException;
use MediaWiki\Composer\PhpUnitSplitter\PhpUnitConsoleOutputProcessor;
use MediaWiki\Composer\PhpUnitSplitter\PhpUnitXml;
use MediaWiki\Composer\PhpUnitSplitter\SplitGroupExecutor;
use MediaWiki\Maintenance\ForkController;
use Shellbox\Shellbox;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../..';

require_once $basePath . '/includes/BootstrapHelperFunctions.php';
require_once $basePath . '/maintenance/includes/ForkController.php';

/**
 * Launch PHPUnit test suites in parallel.
 *
 * This class is run directly from composer.json,
 * outside of any MediaWiki context;
 * consequently, most MediaWiki code cannot be used here.
 * We extend ForkController because it's convenient to do so and ForkController still works here,
 * but we cannot use e.g. Shell::command() to run the composer sub-commands,
 * nor anything else that requires MediaWiki services or config.
 * (But we can use the underlying Shellbox library directly.)
 *
 * @license GPL-2.0-or-later
 */
class ComposerLaunchParallel extends ForkController {

	private SplitGroupExecutor $splitGroupExecutor;
	private ComposerSystemInterface $composerSystemInterface;

	private const SPLIT_GROUP_COUNT = 8;

	private const ALWAYS_EXCLUDE = [ 'Broken', 'ParserFuzz', 'Stub' ];
	public const DATABASELESS_GROUPS = [];
	public const DATABASE_GROUPS = [ 'Database' ];
	private array $groups = [];
	private array $excludeGroups = [];

	public const EXIT_STATUS_SUCCESS = 0;
	public const EXIT_STATUS_FAILURE = 1;
	public const EXIT_STATUS_PHPUNIT_LIST_TESTS_ERROR = 2;

	public function __construct(
		array $groups,
		array $excludeGroups,
		?Event $event,
		?SplitGroupExecutor $splitGroupExecutor = null,
		?ComposerSystemInterface $composerSystemInterface = null
	) {
		$this->groups = $groups;
		$this->excludeGroups = $excludeGroups;
		$this->composerSystemInterface = $composerSystemInterface ?? new ComposerSystemInterface();
		$this->splitGroupExecutor = $splitGroupExecutor ?? new SplitGroupExecutor(
			Shellbox::createUnboxedExecutor(), $event, $this->composerSystemInterface
		);

		/**
		 * By default, the splitting process splits the tests into 8 groups. 7 of the groups are composed
		 * of evenly distributed test classes extracted from the `--list-tests-xml` phpunit function. The
		 * 8th group contains just the ExtensionsParserTestSuite.
		 */
		$splitGroupCount = self::SPLIT_GROUP_COUNT - 1;
		if ( $this->isDatabaseRun() ) {
			/**
			 * In the splitting, we put ExtensionsParserTestSuite in `split_group_7` on its own. We only
			 * need to run `split_group_7` when we run Database tests, since all Parser tests use the
			 * database. Running `split_group_7` when no matches tests get executed results in a phpunit
			 * error code.
			 */
			$splitGroupCount = self::SPLIT_GROUP_COUNT;
		}
		parent::__construct( $splitGroupCount );
	}

	private function isDatabaseRun(): bool {
		return self::isDatabaseRunForGroups( $this->groups, $this->excludeGroups );
	}

	private static function isDatabaseRunForGroups( array $groups, array $excludeGroups ): bool {
		return in_array( 'Database', $groups ) &&
			!in_array( 'Database', $excludeGroups );
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
		$excludeGroups = array_diff( $this->excludeGroups, $this->groups );
		$groupName = "database";
		if ( !self::isDatabaseRunForGroups( $this->groups, $excludeGroups ) ) {
			$groupName = "databaseless";
		}
		$result = $this->splitGroupExecutor->executeSplitGroup(
			"split_group_$groupId",
			$this->groups,
			$excludeGroups,
			".phpunit_group_{$groupId}_{$groupName}.result.cache",
			$groupId
		);
		$consoleOutput = $result->getStdout();
		if ( $consoleOutput ) {
			$this->composerSystemInterface->putFileContents(
				"phpunit_output_{$groupId}_{$groupName}.log",
				$consoleOutput
			);
		}
		$this->composerSystemInterface->print( $consoleOutput );
		$this->composerSystemInterface->exit( $result->getExitCode() );
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

	/**
	 * @throws PhpUnitConsoleOutputProcessingException
	 */
	public static function launchTests( Event $event, array $groups, array $excludeGroups ): void {
		$phpUnitConfig = getcwd() . DIRECTORY_SEPARATOR . 'phpunit.xml';
		if ( !PhpUnitXml::isPhpUnitXmlPrepared( $phpUnitConfig ) ) {
			$event->getIO()->error( "phpunit.xml is not present or does not contain split test suites" );
			$event->getIO()->error( "run `composer phpunit:prepare-parallel:...` to generate the split suites" );
			exit( self::EXIT_STATUS_FAILURE );
		}
		$event->getIO()->info( "Running 'split_group_X' suites in parallel..." );
		$launcher = new ComposerLaunchParallel( $groups, $excludeGroups, $event );
		$launcher->start();
		if ( $launcher->allSuccessful() ) {
			$event->getIO()->info( "All split_groups succeeded!" );
			exit( self::EXIT_STATUS_SUCCESS );
		} else {
			$event->getIO()->write( PHP_EOL . PHP_EOL );
			$event->getIO()->warning( "Some split_groups failed - returning failure status" );
			$groupName = self::isDatabaseRunForGroups( $groups, $excludeGroups ) ? "database" : "databaseless";
			$event->getIO()->warning( "Summarizing parallel error logs for " . $groupName . " group..." );
			$event->getIO()->write( PHP_EOL );
			PhpUnitConsoleOutputProcessor::collectAndDumpFailureSummary(
				"phpunit_output_%d_{$groupName}.log",
				self::SPLIT_GROUP_COUNT,
				$event->getIO()
			);
			exit( self::EXIT_STATUS_FAILURE );
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

	public static function getDatabaseExcludeGroups(): array {
		return array_merge( self::ALWAYS_EXCLUDE, [ 'Standalone' ] );
	}

	public static function launchTestsDatabase( Event $event ) {
		self::launchTests(
			$event,
			self::DATABASE_GROUPS,
			self::getDatabaseExcludeGroups()
		);
	}

	public static function getDatabaselessExcludeGroups(): array {
		return array_merge( self::ALWAYS_EXCLUDE, [ 'Standalone', 'Database' ] );
	}

	public static function launchTestsDatabaseless( Event $event ) {
		self::launchTests(
			$event,
			self::DATABASELESS_GROUPS,
			self::getDatabaselessExcludeGroups()
		);
	}
}
