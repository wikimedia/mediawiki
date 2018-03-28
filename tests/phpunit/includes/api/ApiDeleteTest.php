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
	public function testDelete() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		// create new page
		$this->editPage( $name, 'Some text' );

		// test deletion
		$apiResult = $this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
		] )[0];

		$this->assertArrayHasKey( 'delete', $apiResult );
		$this->assertArrayHasKey( 'title', $apiResult['delete'] );
		$this->assertSame( $name, $apiResult['delete']['title'] );
		$this->assertArrayHasKey( 'logid', $apiResult['delete'] );

		$this->assertFalse( Title::newFromText( $name )->exists() );
	}

	public function testDeleteNonexistent() {
		$this->setExpectedException( ApiUsageException::class,
			"The page you specified doesn't exist." );

		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => 'This page deliberately left nonexistent',
		] );
	}

	public function testDeletionWithoutPermission() {
		$this->setExpectedException( ApiUsageException::class,
			'The action you have requested is limited to users in the group:' );

		$name = 'Help:' . ucfirst( __FUNCTION__ );

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
		} finally {
			$this->assertTrue( Title::newFromText( $name )->exists() );
		}
	}

	public function testDeleteWithTag() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );

		ChangeTags::defineTag( 'custom tag' );

		$this->editPage( $name, 'Some text' );

		$this->doApiRequestWithToken( [
			'action' => 'delete',
			'title' => $name,
			'tags' => 'custom tag',
		] );

		$this->assertFalse( Title::newFromText( $name )->exists() );

		$dbw = wfGetDB( DB_MASTER );
		$this->assertSame( 'custom tag', $dbw->selectField(
			[ 'change_tag', 'logging' ],
			'ct_tag',
			[
				'log_namespace' => NS_HELP,
				'log_title' => ucfirst( __FUNCTION__ ),
			],
			__METHOD__,
			[],
			[ 'change_tag' => [ 'INNER JOIN', 'ct_log_id = log_id' ] ]
		) );
	}

	public function testDeleteWithoutTagPermission() {
		$this->setExpectedException( ApiUsageException::class,
			'You do not have permission to apply change tags along with your changes.' );

		$name = 'Help:' . ucfirst( __FUNCTION__ );

		ChangeTags::defineTag( 'custom tag' );
		$this->setMwGlobals( 'wgRevokePermissions',
			[ 'user' => [ 'applychangetags' => true ] ] );

		$this->editPage( $name, 'Some text' );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'delete',
				'title' => $name,
				'tags' => 'custom tag',
			] );
		} finally {
			$this->assertTrue( Title::newFromText( $name )->exists() );
		}
	}

	public function testDeleteAbortedByHook() {
		$this->setExpectedException( ApiUsageException::class,
			'Deletion aborted by hook. It gave no explanation.' );

		$name = 'Help:' . ucfirst( __FUNCTION__ );

		$this->editPage( $name, 'Some text' );

		$this->setTemporaryHook( 'ArticleDelete',
			function () {
				return false;
			}
		);

		try {
			$this->doApiRequestWithToken( [ 'action' => 'delete', 'title' => $name ] );
		} finally {
			$this->assertTrue( Title::newFromText( $name )->exists() );
		}
	}

	public function testDeleteWatch() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$user = self::$users['sysop']->getUser();

		$this->editPage( $name, 'Some text' );
		$this->assertTrue( Title::newFromText( $name )->exists() );
		$this->assertFalse( $user->isWatched( Title::newFromText( $name ) ) );

		$this->doApiRequestWithToken( [ 'action' => 'delete', 'title' => $name, 'watch' => '' ] );

		$this->assertFalse( Title::newFromText( $name )->exists() );
		$this->assertTrue( $user->isWatched( Title::newFromText( $name ) ) );
	}

	public function testDeleteUnwatch() {
		$name = 'Help:' . ucfirst( __FUNCTION__ );
		$user = self::$users['sysop']->getUser();

		$this->editPage( $name, 'Some text' );
		$this->assertTrue( Title::newFromText( $name )->exists() );
		$user->addWatch( Title::newFromText( $name ) );
		$this->assertTrue( $user->isWatched( Title::newFromText( $name ) ) );

		$this->doApiRequestWithToken( [ 'action' => 'delete', 'title' => $name, 'unwatch' => '' ] );

		$this->assertFalse( Title::newFromText( $name )->exists() );
		$this->assertFalse( $user->isWatched( Title::newFromText( $name ) ) );
	}
}
