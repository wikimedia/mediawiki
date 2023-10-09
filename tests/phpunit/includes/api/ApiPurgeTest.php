<?php

use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiPurge
 */
class ApiPurgeTest extends ApiTestCase {

	public function testPurgePage() {
		$existingPageTitle = 'TestPurgePageExists';
		$this->getExistingTestPage( $existingPageTitle );
		$nonexistingPageTitle = 'TestPurgePageDoesNotExists';
		$this->getNonexistingTestPage( $nonexistingPageTitle );

		[ $data ] = $this->doApiRequest( [
			'action' => 'purge',
			'titles' => "$existingPageTitle|$nonexistingPageTitle|%5D"
		] );

		$resultByTitle = [];
		foreach ( $data['purge'] as $entry ) {
			$key = $entry['title'];
			// Ignore localised or redundant field
			unset( $entry['invalidreason'] );
			unset( $entry['title'] );
			$resultByTitle[$key] = $entry;
		}

		$this->assertEquals(
			[
				$existingPageTitle => [ 'purged' => true, 'ns' => NS_MAIN ],
				$nonexistingPageTitle => [ 'missing' => true, 'ns' => NS_MAIN ],
				'%5D' => [ 'invalid' => true ],
			],
			$resultByTitle,
			'Result'
		);
	}

	public function testAuthorize() {
		$page1 = 'TestPage1';
		$page2 = 'TestPage2';
		$this->getExistingTestPage( $page1 );
		$this->getExistingTestPage( $page2 );

		$user = RequestContext::getMain()->getUser();

		$authority = $this->createNoOpMock(
			Authority::class,
			[
				'authorizeWrite',
				'getUser',
				'isAllowed',
				'getBlock'
			]
		);

		$authority->method( 'getUser' )->willReturn( $user );
		$authority->method( 'getBlock' )->willReturn( null );
		$authority->method( 'isAllowed' )->willReturn( true );
		$authority->method( 'authorizeWrite' )->willReturnCallback(
			static function ( string $action, PageIdentity $page, PermissionStatus $status )
				use ( $page1 )
			{
				if ( $page->getDBkey() === $page1 ) {
					$status->fatal( 'permissionserrors' );
				} else {
					$status->setRateLimitExceeded();
				}

				return false;
			}
		);

		[ $data ] = $this->doApiRequest( [
			'action' => 'purge',
			'titles' => "$page1|$page2"
		], null, false, $authority );

		$this->assertNotEmpty( $data['warnings']['purge']['warnings'] );
		$warnings = $data['warnings']['purge']['warnings'];

		$this->assertStringContainsString( 'Permission error', $warnings );
		$this->assertStringContainsString( 'exceeded your rate limit', $warnings );
	}
}
