<?php

use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @group Database
 */
class MergeHistoryTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	/**
	 * Make some pages to work with
	 */
	public function addDBDataOnce() {
		// Pages that won't actually be merged
		$this->insertPage( 'Test' );
		$this->insertPage( 'Test2' );

		// Pages that will be merged
		$this->insertPage( 'Merge1' );
		$this->insertPage( 'Merge2' );

		// Exclusive for testSourceUpdateForNoRedirectSupport()
		$this->insertPage( 'Merge3' );
		$this->insertPage( 'Merge4' );
	}

	/**
	 * @dataProvider provideIsValidMerge
	 * @covers MergeHistory::isValidMerge
	 * @param string $source Source page
	 * @param string $dest Destination page
	 * @param string|bool $timestamp Timestamp up to which revisions are merged (or false for all)
	 * @param string|bool $error Expected error for test (or true for no error)
	 */
	public function testIsValidMerge( $source, $dest, $timestamp, $error ) {
		if ( $timestamp === true ) {
			// Although this timestamp is after the latest timestamp of both pages,
			// MergeHistory should select the latest source timestamp up to this which should
			// still work for the merge.
			$timestamp = time() + ( 24 * 3600 );
		}
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory(
			Title::newFromText( $source ),
			Title::newFromText( $dest ),
			$timestamp
		);
		$status = $mh->isValidMerge();
		if ( $error === true ) {
			$this->assertTrue( $status->isGood() );
		} else {
			$this->assertTrue( $status->hasMessage( $error ) );
		}
	}

	public static function provideIsValidMerge() {
		return [
			// for MergeHistory::isValidMerge
			[ 'Test', 'Test2', false, true ],
			// Timestamp of `true` is a placeholder for "in the future""
			[ 'Test', 'Test2', true, true ],
			[ 'Test', 'Test', false, 'mergehistory-fail-self-merge' ],
			[ 'Nonexistant', 'Test2', false, 'mergehistory-fail-invalid-source' ],
			[ 'Test', 'Nonexistant', false, 'mergehistory-fail-invalid-dest' ],
			[
				'Test',
				'Test2',
				'This is obviously an invalid timestamp',
				'mergehistory-fail-bad-timestamp'
			],
		];
	}

	/**
	 * Test merge revision limit checking
	 * @covers MergeHistory::isValidMerge
	 */
	public function testIsValidMergeRevisionLimit() {
		$limit = MergeHistory::REVISION_LIMIT;
		$mh = $this->getMockBuilder( MergeHistory::class )
			->onlyMethods( [ 'getRevisionCount' ] )
			->setConstructorArgs( [
				Title::newFromText( 'Test' ),
				Title::newFromText( 'Test2' ),
				null,
				$this->getServiceContainer()->getDBLoadBalancer(),
				$this->getServiceContainer()->getContentHandlerFactory(),
				$this->getServiceContainer()->getRevisionStore(),
				$this->getServiceContainer()->getWatchedItemStore(),
				$this->getServiceContainer()->getSpamChecker(),
				$this->getServiceContainer()->getHookContainer(),
				$this->getServiceContainer()->getWikiPageFactory(),
				$this->getServiceContainer()->getTitleFormatter(),
				$this->getServiceContainer()->getTitleFactory(),
			] )
			->getMock();
		$mh->expects( $this->once() )
			->method( 'getRevisionCount' )
			->willReturn( $limit + 1 );

		$status = $mh->isValidMerge();
		$this->assertTrue( $status->hasMessage( 'mergehistory-fail-toobig' ) );
		$errors = $status->getErrorsByType( 'error' );
		$params = $errors[0]['params'];
		$this->assertEquals( $params[0], Message::numParam( $limit ) );
	}

	/**
	 * Test user permission checking
	 * @covers MergeHistory::authorizeMerge
	 * @covers MergeHistory::probablyCanMerge
	 */
	public function testCheckPermissions() {
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory(
			Title::newFromText( 'Test' ),
			Title::newFromText( 'Test2' )
		);

		foreach ( [ 'authorizeMerge', 'probablyCanMerge' ] as $method ) {
			// Sysop with mergehistory permission
			$status = $mh->$method(
				$this->mockRegisteredUltimateAuthority(),
				''
			);
			$this->assertTrue( $status->isOK() );

			$status = $mh->$method(
				$this->mockRegisteredAuthorityWithoutPermissions( [ 'mergehistory' ] ),
				''
			);
			$this->assertTrue( $status->hasMessage( 'mergehistory-fail-permission' ) );
		}
	}

	/**
	 * Test merged revision count
	 * @covers MergeHistory::getMergedRevisionCount
	 */
	public function testGetMergedRevisionCount() {
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory(
			Title::newFromText( 'Merge1' ),
			Title::newFromText( 'Merge2' )
		);

		$sysop = static::getTestSysop()->getUser();
		$mh->merge( $sysop );
		$this->assertSame( 1, $mh->getMergedRevisionCount() );
	}

	/**
	 * Test update to source page for pages with
	 * content model that supports redirects
	 *
	 * @covers MergeHistory::merge
	 */
	public function testSourceUpdateWithRedirectSupport() {
		$title = Title::newFromText( 'Merge1' );
		$title2 = Title::newFromText( 'Merge2' );

		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $title, $title2 );

		$this->assertTrue( $title->exists() );

		$status = $mh->merge( static::getTestSysop()->getUser() );

		$this->assertTrue( $title->exists() );
	}

	/**
	 * Test update to source page for pages with
	 * content model that does not support redirects
	 *
	 * @covers MergeHistory::merge
	 */
	public function testSourceUpdateForNoRedirectSupport() {
		$this->setMwGlobals( [
			'wgExtraNamespaces' => [
				2030 => 'NoRedirect',
				2030 => 'NoRedirect_talk'
			],

			'wgNamespaceContentModels' => [
				2030 => 'testing'
			],
			'wgContentHandlers' => [
				// Relies on the DummyContentHandlerForTesting not
				// supporting redirects by default. If this ever gets
				// changed this test has to be fixed.
				'testing' => DummyContentHandlerForTesting::class
			]
		] );

		$title = Title::newFromText( 'Merge3' );
		$title->setContentModel( 'testing' );
		$title2 = Title::newFromText( 'Merge4' );
		$title2->setContentModel( 'testing' );

		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $title, $title2 );

		$this->assertTrue( $title->exists() );

		$status = $mh->merge( static::getTestSysop()->getUser() );

		$this->assertFalse( $title->exists() );
	}
}
