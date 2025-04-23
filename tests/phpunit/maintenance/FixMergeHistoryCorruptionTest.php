<?php

namespace MediaWiki\Tests\Maintenance;

use FixMergeHistoryCorruption;
use MediaWiki\Title\Title;

/**
 * @covers FixMergeHistoryCorruption
 * @group Database
 * @author Dreamy Jazz
 */
class FixMergeHistoryCorruptionTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return FixMergeHistoryCorruption::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $expectedOutputRegex ) {
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'No options provided' => [ [], '/Either --dry-run or --delete must be specified/' ],
			'Both dry-run and delete' => [
				[ 'dry-run' => 1, 'delete' => 1 ], '/Cannot do both --dry-run and --delete/',
			],
		];
	}

	public function testExecuteWhenNoPages() {
		$this->maintenance->setOption( 'delete', 1 );
		$this->maintenance->execute();
		$this->expectOutputRegex( "/Nothing was found, no page matches the criteria/" );
	}

	public function testExecuteWhenNoBrokenPages() {
		$this->getExistingTestPage();
		$this->testExecuteWhenNoPages();
	}

	public function testExecuteWhenBrokenPageExistsButNotInSpecifiedNamespace() {
		// Simulate a broken page by manually updating the revision table row after creating a test page
		$page = $this->getExistingTestPage( Title::newFromText( 'Main Page' ) );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_page' => 12345 ] )
			->where( [ 'rev_page' => $page->getId() ] )
			->caller( __METHOD__ )
			->execute();

		$this->maintenance->setOption( 'delete', 1 );
		$this->maintenance->setOption( 'ns', 1 );
		$this->maintenance->execute();
		$this->expectOutputRegex( "/Nothing was found, no page matches the criteria/" );
	}

	public function testExecuteWhenBrokenPageExistsAndNamespaceInvalid() {
		// Simulate a broken page by manually updating the revision table row after creating a test page,
		// and also making the namespace invalid (so the page is skipped).
		$page = $this->getExistingTestPage( Title::newFromText( 'Main Page' ) );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_page' => 12345 ] )
			->where( [ 'rev_page' => $page->getId() ] )
			->caller( __METHOD__ )
			->execute();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'page' )
			->set( [ 'page_namespace' => -245 ] )
			->where( [ 'page_id' => $page->getId() ] )
			->caller( __METHOD__ )
			->execute();

		$this->maintenance->setOption( 'delete', 1 );
		$this->maintenance->execute();
		$this->expectOutputRegex( "/Skipping invalid title with page_id: {$page->getId()}/" );
	}

	/** @dataProvider provideIsDryRunValues */
	public function testExecuteWhenBrokenPagesExist( $isDryRun ) {
		// Simulate a broken page that would cause the page row to be deleted
		$firstPage = $this->getExistingTestPage( Title::newFromText( 'Main Page' ) );
		$firstPageRevId = $firstPage->getLatest();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_page' => 12345 ] )
			->where( [ 'rev_id' => $firstPageRevId ] )
			->caller( __METHOD__ )
			->execute();

		// Simulate a broken page that would cause the page row to be updated
		$secondPage = $this->getExistingTestPage( Title::newFromText( 'Main Page2' ) );
		$secondPageFirstRevId = $secondPage->getLatest();
		$this->editPage( $secondPage, 'testabc' );
		$secondPage->clear();
		$secondPageSecondRevId = $secondPage->getLatest();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_page' => 123456 ] )
			->where( [ 'rev_id' => $secondPageSecondRevId ] )
			->caller( __METHOD__ )
			->execute();

		if ( $isDryRun ) {
			$this->maintenance->setOption( 'dry-run', 1 );
		} else {
			$this->maintenance->setOption( 'delete', 1 );
		}
		$this->maintenance->execute();
		if ( $isDryRun ) {
			// Expect that no DB updates were performed as this was a dry run.
			$this->newSelectQueryBuilder()
				->select( [ 'page_id', 'page_latest' ] )
				->from( 'page' )
				->caller( __METHOD__ )
				->assertResultSet( [
					[ $firstPage->getId(), $firstPageRevId ],
					[ $secondPage->getId(), $secondPageSecondRevId ],
				] );

			$actualOutput = $this->getActualOutputForAssertion();
			$this->assertStringContainsString(
				'Would delete Main_Page with page_id: ' . $firstPage->getId(), $actualOutput
			);
			$this->assertStringContainsString(
				'Would update page_id ' . $secondPage->getId() . ' to page_latest ' . $secondPageFirstRevId,
				$actualOutput
			);
			$this->assertStringNotContainsString( 'Updated 1 row(s), deleted 1 row(s)', $actualOutput );
		} else {
			// Expect that the page table has been correctly updated
			$this->newSelectQueryBuilder()
				->select( [ 'page_id', 'page_latest' ] )
				->from( 'page' )
				->caller( __METHOD__ )
				->assertResultSet( [
					[ $secondPage->getId(), $secondPageFirstRevId ],
				] );

			$actualOutput = $this->getActualOutputForAssertion();
			$this->assertStringContainsString(
				'Deleting Main_Page with page_id: ' . $firstPage->getId(), $actualOutput
			);
			$this->assertStringContainsString(
				'Updating page_id ' . $secondPage->getId() . ' to page_latest ' . $secondPageFirstRevId,
				$actualOutput
			);
			$this->assertStringContainsString( 'Updated 1 row(s), deleted 1 row(s)', $actualOutput );
		}
	}

	public static function provideIsDryRunValues() {
		return [
			'For dry run' => [ true ],
			'For non-dry run' => [ false ],
		];
	}
}
