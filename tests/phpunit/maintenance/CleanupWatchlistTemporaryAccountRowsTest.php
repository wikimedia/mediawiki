<?php

namespace MediaWiki\Tests\Maintenance;

use CleanupWatchlistTemporaryAccountRows;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \CleanupWatchlistTemporaryAccountRows
 * @group Database
 * @author Dreamy Jazz
 */
class CleanupWatchlistTemporaryAccountRowsTest extends MaintenanceBaseTestCase {
	use TempUserTestTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->enableAutoCreateTempUser();
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		$this->overrideConfigValue( MainConfigNames::EnableWatchlistLabels, true );
	}

	/** @inheritDoc */
	protected function getMaintenanceClass(): string {
		return CleanupWatchlistTemporaryAccountRows::class;
	}

	public function testExecuteWhenTemporaryAccountsNotKnown(): void {
		$this->disableAutoCreateTempUser();
		$this->maintenance->execute();
		$this->expectOutputString(
			"Deleting watchlist table rows for temporary accounts...\n" .
			"Temporary accounts are not known, so no rows need deleting.\n"
		);
	}

	public function testExecuteWhenNoWatchlistRows(): void {
		$this->maintenance->execute();
		$this->expectOutputString(
			"Deleting watchlist table rows for temporary accounts...\n" .
			"Done. Total rows deleted: 0\n"
		);
	}

	public function testExecuteWhenOnlyNamedUserWatchlistRows(): void {
		ConvertibleTimestamp::setFakeTime( '20260102030405' );

		$user = $this->getTestUser()->getUserIdentity();
		$firstPage = $this->getExistingTestPage();
		$watchedItemStore = $this->getServiceContainer()->getWatchedItemStore();
		$watchedItemStore->addWatch( $user, $firstPage );

		$secondPage = $this->getExistingTestPage();
		$watchedItemStore->addWatch( $user, $secondPage, '20260120030405' );

		$this->maintenance->execute();
		$this->expectOutputString(
			"Deleting watchlist table rows for temporary accounts...\n" .
			"Done. Total rows deleted: 0\n"
		);

		$this->assertNotFalse( $watchedItemStore->loadWatchedItem( $user, $firstPage ) );
		$secondItem = $watchedItemStore->loadWatchedItem( $user, $secondPage );
		$this->assertNotFalse( $secondItem );
		$this->assertNotNull( $secondItem->getExpiry() );
	}

	/** @dataProvider provideExecuteWhenSomeWatchlistRowsNeedDeleting */
	public function testExecuteWhenSomeWatchlistRowsNeedDeleting(
		int $batchSize,
		string $expectedOutputString
	): void {
		ConvertibleTimestamp::setFakeTime( '20260102030405' );
		$watchedItemStore = $this->getServiceContainer()->getWatchedItemStore();

		$namedUser = $this->getTestUser()->getUserIdentity();
		$firstPage = $this->getExistingTestPage();
		$watchedItemStore->addWatch( $namedUser, $firstPage );

		$secondPage = $this->getExistingTestPage();
		$watchedItemStore->addWatch( $namedUser, $secondPage, '20260120030405' );

		$firstTempUser = $this->getServiceContainer()->getTempUserCreator()
			->create( null, new FauxRequest() )
			->getUser();
		$watchedItemStore->addWatch( $firstTempUser, $firstPage );

		$watchedItemStore->addWatch( $firstTempUser, $secondPage );

		$secondTempUser = $this->getServiceContainer()->getTempUserCreator()
			->create( null, new FauxRequest() )
			->getUser();
		$watchedItemStore->addWatch( $secondTempUser, $firstPage );

		$this->maintenance->loadWithArgv( [ '--batch-size', $batchSize ] );
		$this->maintenance->execute();
		$this->expectOutputString( $expectedOutputString );

		$this->assertNotFalse( $watchedItemStore->loadWatchedItem( $namedUser, $firstPage ) );
		$secondItem = $watchedItemStore->loadWatchedItem( $namedUser, $secondPage );
		$this->assertNotFalse( $secondItem );
		$this->assertNotNull( $secondItem->getExpiry() );
		$this->assertCount( 0, $watchedItemStore->getWatchedItemsForUser( $firstTempUser ) );
		$this->assertCount( 0, $watchedItemStore->getWatchedItemsForUser( $secondTempUser ) );
	}

	public static function provideExecuteWhenSomeWatchlistRowsNeedDeleting(): array {
		return [
			'Batch size of 1' => [
				'batchSize' => 1,
				'expectedOutputString' => "Deleting watchlist table rows for temporary accounts...\n" .
					"... Deleted 1 rows\n" .
					"... Deleted 2 rows\n" .
					"... Deleted 3 rows\n" .
					"Done. Total rows deleted: 3\n"
			],
			'Batch size of 2' => [
				'batchSize' => 2,
				'expectedOutputString' => "Deleting watchlist table rows for temporary accounts...\n" .
					"... Deleted 2 rows\n" .
					"... Deleted 3 rows\n" .
					"Done. Total rows deleted: 3\n"
			],
			'Batch size of 100' => [
				'batchSize' => 100,
				'expectedOutputString' => "Deleting watchlist table rows for temporary accounts...\n" .
					"... Deleted 3 rows\n" .
					"Done. Total rows deleted: 3\n"
			],
		];
	}
}
