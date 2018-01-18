<?php

/**
 * Tests for MediaWiki api.php?action=delete.
 *
 * @author Yifei He
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiDelete
 */
class ApiDeleteTest extends ApiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->doLogin();
	}

	public function testDelete() {
		$name = 'Help:ApiDeleteTest_testDelete';

		// test non-existing page
		try {
			$this->doApiRequestWithToken( [
				'action' => 'delete',
				'title' => $name,
			] );
			$this->fail( "Should have raised an ApiUsageException" );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( self::apiExceptionHasCode( $e, 'missingtitle' ) );
		}

		// create new page
		$this->editPage( $name, 'Some text' );

		// test deletion
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
		] );
		$apiResult = $apiResult[0];

		$this->assertArrayHasKey( 'delete', $apiResult );
		$this->assertArrayHasKey( 'title', $apiResult['delete'] );
		// Normalized $name is used
		$this->assertSame(
			'Help:ApiDeleteTest testDelete',
			$apiResult['delete']['title']
		);
		$this->assertArrayHasKey( 'logid', $apiResult['delete'] );

		$this->assertFalse( Title::newFromText( $name )->exists() );
	}

	public function testDeletionWithoutPermission() {
		$name = 'Help:ApiDeleteTest_testDeleteWithoutPermission';

		// create new page
		$this->editPage( $name, 'Some text' );

		// test deletion without permission
		try {
			$user = new User();
			$apiResult = $this->doApiRequest( [
				'action' => 'delete',
				'title' => $name,
				'token' => $user->getEditToken(),
			], null, null, $user );
			$this->fail( "Should have raised an ApiUsageException" );
		} catch ( ApiUsageException $e ) {
			$this->assertTrue( self::apiExceptionHasCode( $e, 'permissiondenied' ) );
		}

		$this->assertTrue( Title::newFromText( $name )->exists() );
	}
}
