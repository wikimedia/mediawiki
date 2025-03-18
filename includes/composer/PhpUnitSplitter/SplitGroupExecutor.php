<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use Composer\Script\Event;
use MediaWiki\Composer\ComposerLaunchParallel;
use MediaWiki\Composer\ComposerSystemInterface;
use Shellbox\Command\UnboxedExecutor;
use Shellbox\Command\UnboxedResult;

/**
 * @license GPL-2.0-or-later
 */
class SplitGroupExecutor {

	private UnboxedExecutor $executor;
	private ?Event $event;
	private ComposerSystemInterface $composerSystemInterface;
	private string $phpunitConfigFile;

	public function __construct(
		string $phpunitConfigFile,
		UnboxedExecutor $shellExecutor,
		?Event $event,
		?ComposerSystemInterface $composerSystemInterface = null
	) {
		$this->phpunitConfigFile = $phpunitConfigFile;
		$this->executor = $shellExecutor;
		$this->event = $event;
		$this->composerSystemInterface = $composerSystemInterface ?? new ComposerSystemInterface();
	}

	public function executeSplitGroup(
		string $testSuite,
		array $groups,
		array $excludeGroups,
		?string $resultsCacheFile = null,
		?int $groupId = null
	): UnboxedResult {
		$command = $this->executor->createCommand()
			->params(
				'composer', 'run',
				'--timeout=0',
				'phpunit:entrypoint',
				'--',
				'--configuration', $this->phpunitConfigFile,
				'--testsuite', $testSuite,
				'--exclude-group', implode( ",", $excludeGroups )
			);
		if ( count( $groups ) ) {
			$command->params( '--group', implode( ',', $groups ) );
		}
		if ( $resultsCacheFile ) {
			$command->params(
				"--cache-result-file=$resultsCacheFile"
			);
		}
		$command->includeStderr( true );
		if ( $groupId !== null ) {
			$command->environment( [ 'MW_PHPUNIT_SPLIT_GROUP_ID' => $groupId ] );
		}
		$this->consoleLog( "Running command '" . $command->getCommandString() . "' ..." . PHP_EOL );
		return $command->execute();
	}

	private function warning( string $warning ) {
		if ( $this->event ) {
			$this->event->getIO()->warning( $warning );
		}
	}

	private function composerLog( string $text ) {
		if ( $this->event ) {
			$this->event->getIO()->write( $text );
		}
	}

	private function consoleLog( string $outputText ) {
		$this->composerSystemInterface->print( $outputText );
	}

	public function runLinearFallback( string $testSuite ) {
		$this->warning( "Test suite splitting failed - falling back to linear run" );
		$this->composerLog( "Running " . $testSuite . " phpunit suite databaseless tests..." );
		$databaselessResult = $this->executeSplitGroup(
			$testSuite,
			ComposerLaunchParallel::DATABASELESS_GROUPS,
			ComposerLaunchParallel::getDatabaselessExcludeGroups()
		);
		$this->consoleLog( $databaselessResult->getStdout() );
		if ( $databaselessResult->getExitCode() !== 0 ) {
			return;
		}
		$this->composerLog( "Running " . $testSuite . " phpunit suite database tests..." );
		$databaseResult = $this->executeSplitGroup(
			$testSuite,
			ComposerLaunchParallel::DATABASE_GROUPS,
			ComposerLaunchParallel::getDatabaseExcludeGroups()
		);
		$this->consoleLog( $databaseResult->getStdout() );
	}

}
