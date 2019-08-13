<?php

/**
 * @group Database
 */
class MergeHistoryTest extends MediaWikiTestCase {

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
		$mh = new MergeHistory(
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
			->setMethods( [ 'getRevisionCount' ] )
			->setConstructorArgs( [
				Title::newFromText( 'Test' ),
				Title::newFromText( 'Test2' ),
			] )
			->getMock();
		$mh->expects( $this->once() )
			->method( 'getRevisionCount' )
			->will( $this->returnValue( $limit + 1 ) );

		$status = $mh->isValidMerge();
		$this->assertTrue( $status->hasMessage( 'mergehistory-fail-toobig' ) );
		$errors = $status->getErrorsByType( 'error' );
		$params = $errors[0]['params'];
		$this->assertEquals( $params[0], Message::numParam( $limit ) );
	}

	/**
	 * Test user permission checking
	 * @covers MergeHistory::checkPermissions
	 */
	public function testCheckPermissions() {
		$mh = new MergeHistory(
			Title::newFromText( 'Test' ),
			Title::newFromText( 'Test2' )
		);

		// Sysop with mergehistory permission
		$sysop = static::getTestSysop()->getUser();
		$status = $mh->checkPermissions( $sysop, '' );
		$this->assertTrue( $status->isOK() );

		// Normal user
		$notSysop = static::getTestUser()->getUser();
		$status = $mh->checkPermissions( $notSysop, '' );
		$this->assertTrue( $status->hasMessage( 'mergehistory-fail-permission' ) );
	}

	/**
	 * Test merged revision count
	 * @covers MergeHistory::getMergedRevisionCount
	 */
	public function testGetMergedRevisionCount() {
		$mh = new MergeHistory(
			Title::newFromText( 'Merge1' ),
			Title::newFromText( 'Merge2' )
		);

		$sysop = static::getTestSysop()->getUser();
		$mh->merge( $sysop );
		$this->assertEquals( $mh->getMergedRevisionCount(), 1 );
	}
}
