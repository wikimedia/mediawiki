<?php

namespace MediaWiki\Tests\Maintenance;

use DeleteSelfExternals;
use MediaWiki\MainConfigNames;

/**
 * @covers DeleteSelfExternals
 * @group Database
 * @author Dreamy Jazz
 */
class DeleteSelfExternalsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return DeleteSelfExternals::class;
	}

	public function testExecuteForInvalidServerConfig() {
		$this->overrideConfigValue( MainConfigNames::Server, '::::::' );
		$this->expectOutputRegex( '/Could not parse \$wgServer/' );
		$this->expectCallToFatalError();
		$this->maintenance->execute();
	}

	/** @dataProvider provideExecute */
	public function testExecute( $serverConfigValue, $expectedRowsAfterExecution ) {
		// Create some self-externals (by linking https://en.wikipedia.org and later setting that as our
		// server URL), as well as some external links which should be unmodified by the maintenance script.
		$firstTestPage = $this->getExistingTestPage( 'TestPage1' );
		$secondTestPage = $this->getExistingTestPage( 'TestPage2' );
		$thirdTestPage = $this->getExistingTestPage( 'TestPage3' );
		$this->editPage( $firstTestPage, '[https://en.wikipedia.org link 1] [https://de.wikipedia.org link 2]' );
		$this->editPage( $secondTestPage, '[http://en.wikipedia.org link 4]' );
		$this->editPage( $thirdTestPage, '[//en.wikipedia.org:345 link 5]' );
		// Verify that the externallinks table is set up correctly for the test
		$actualRowsBeforeExecution = $this->newSelectQueryBuilder()
			->select( 'el_to_domain_index' )
			->from( 'externallinks' )
			->fetchFieldValues();
		$this->assertArrayEquals(
			[
				'https://org.wikipedia.de.', 'http://org.wikipedia.en.', 'https://org.wikipedia.en.',
				'https://org.wikipedia.en.:345',
			],
			$actualRowsBeforeExecution
		);
		// Set wgServer to be en.wikipedia.org
		$this->overrideConfigValue( MainConfigNames::Server, $serverConfigValue );
		// Run the maintenance script
		$this->maintenance->execute();
		// Verify that the DB is as expected
		$actualRowsAfterExecution = $this->newSelectQueryBuilder()
			->select( 'el_to_domain_index' )
			->from( 'externallinks' )
			->fetchFieldValues();
		$this->assertArrayEquals( $expectedRowsAfterExecution, $actualRowsAfterExecution );
		$expectedRowsDeleted = count( $actualRowsBeforeExecution ) - count( $actualRowsAfterExecution );
		$this->expectOutputString(
			"Deleting self externals from $serverConfigValue\n" .
			"Deleting self-externals with el_id 0 to 1000\n" .
			"Deleting self-externals with el_id 1000 to 2000\n" .
			"done; deleted $expectedRowsDeleted rows\n"
		);
	}

	public static function provideExecute() {
		return [
			'wgServer specifies scheme' => [
				'https://en.wikipedia.org', [ 'https://org.wikipedia.de.', 'http://org.wikipedia.en.' ],
			],
			'wgServer does not specify scheme' => [ '//en.wikipedia.org', [ 'https://org.wikipedia.de.' ] ],
			'wgServer includes port' => [
				'//en.wikipedia.org:345',
				[ 'https://org.wikipedia.de.', 'http://org.wikipedia.en.', 'https://org.wikipedia.en.' ],
			],
		];
	}
}
