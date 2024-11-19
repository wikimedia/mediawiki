<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiPurge
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
				'authorizeAction',
				'getUser',
				'isAllowed',
				'getBlock'
			]
		);

		$authority->method( 'getUser' )->willReturn( $user );
		$authority->method( 'getBlock' )->willReturn( null );
		$authority->method( 'isAllowed' )->willReturn( true );
		$authority->method( 'authorizeAction' )->willReturnCallback(
			static function ( string $action, PermissionStatus $status ) {
				$status->setRateLimitExceeded();

				return false;
			}
		);

		[ $data ] = $this->doApiRequest( [
			'action' => 'purge',
			'titles' => "$page1|$page2"
		], null, false, $authority );

		$this->assertNotEmpty( $data['warnings']['purge']['warnings'] );
		$warnings = $data['warnings']['purge']['warnings'];

		$this->assertStringContainsString( 'exceeded your rate limit', $warnings );
	}

	public function testAuthorizeRateLimit() {
		$page1 = 'TestPage1';
		$page2 = 'TestPage2';
		$this->getExistingTestPage( $page1 );
		$this->getExistingTestPage( $page2 );

		$authority = $this->getTestUser()->getAuthority();

		// purge is limited, linkpurge is not limited
		$this->overrideConfigValue( MainConfigNames::RateLimits,
			[ 'purge' => [ '&can-bypass' => false, 'user' => [ 1, 60 ] ] ]
		);
		[ $data ] = $this->doApiRequest( [
			'action' => 'purge',
			'titles' => "$page1|$page2",
			'forcelinkupdate' => '',
		], null, false, $authority );

		$this->assertNotEmpty( $data['warnings']['purge']['warnings'] );
		$warnings = $data['warnings']['purge']['warnings'];

		$this->assertStringContainsString( 'exceeded your rate limit', $warnings );

		// purge is not limited, linkpurge is limited
		$this->overrideConfigValue( MainConfigNames::RateLimits,
			[ 'linkpurge' => [ '&can-bypass' => false, 'user' => [ 1, 60 ] ] ]
		);
		[ $data ] = $this->doApiRequest( [
			'action' => 'purge',
			'titles' => "$page1|$page2",
			'forcelinkupdate' => '',
		], null, false, $authority );

		$this->assertNotEmpty( $data['warnings']['purge']['warnings'] );
		$warnings = $data['warnings']['purge']['warnings'];

		$this->assertStringContainsString( 'exceeded your rate limit', $warnings );
	}
}
