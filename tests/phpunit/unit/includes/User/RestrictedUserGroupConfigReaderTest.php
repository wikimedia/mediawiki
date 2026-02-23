<?php
/*
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Config\SiteConfiguration;
use MediaWiki\MainConfigNames;
use MediaWiki\User\RestrictedUserGroupConfigReader;
use MediaWiki\User\UserGroupRestrictions;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\RestrictedUserGroupConfigReader
 */
class RestrictedUserGroupConfigReaderTest extends MediaWikiUnitTestCase {

	private function setupConfigReader( array $restrictionsPerWiki ): RestrictedUserGroupConfigReader {
		$localWikiId = WikiMap::getCurrentWikiId();

		if ( isset( $restrictionsPerWiki['__local'] ) ) {
			$restrictionsPerWiki[$localWikiId] = $restrictionsPerWiki['__local'];
			unset( $restrictionsPerWiki['__local'] );
		}

		$mockSiteConfiguration = $this->createMock( SiteConfiguration::class );
		$mockSiteConfiguration->method( 'get' )
			->willReturnCallback( static function ( $setting, $wiki ) use ( $restrictionsPerWiki ) {
				if ( $setting !== 'wgRestrictedGroups' ) {
					return null;
				}

				if ( isset( $restrictionsPerWiki[$wiki] ) ) {
					return $restrictionsPerWiki[$wiki];
				}
				return null;
			} );

		global $wgConf;
		$wgConf = $mockSiteConfiguration;

		$serviceOptions = new ServiceOptions( RestrictedUserGroupConfigReader::CONSTRUCTOR_OPTIONS, [
			MainConfigNames::RestrictedGroups => $restrictionsPerWiki[$localWikiId] ?? [],
		] );
		return new RestrictedUserGroupConfigReader( $serviceOptions );
	}

	public function testGetConfig_local() {
		$configReader = $this->setupConfigReader( [
			'__local' => [ 'interface-admin' => [
				'memberConditions' => [ 'COND' ],
			] ],
			'wiki1' => [ 'sysop' => [] ],
			'wiki2' => [ 'bureaucrat' => [] ],
		] );

		$restrictedGroups = $configReader->getConfig();
		$this->assertArrayHasKey( 'interface-admin', $restrictedGroups );
		$this->assertCount( 1, $restrictedGroups );
		$this->assertInstanceOf( UserGroupRestrictions::class, $restrictedGroups['interface-admin'] );
		$this->assertEquals( [ 'COND' ], $restrictedGroups['interface-admin']->getMemberConditions() );
		$this->assertEquals( [], $restrictedGroups['interface-admin']->getUpdaterConditions() );
	}

	public function testGetConfig_remote() {
		$configReader = $this->setupConfigReader( [
			'__local' => [
				'interface-admin' => [],
				'sysop' => [
					'memberConditions' => [ 'COND_LOCAL' ],
				],
			],
			'wiki1' => [ 'sysop' => [
				'memberConditions' => [ 'COND_REMOTE' ],
			] ],
			'wiki2' => [ 'bureaucrat' => [] ],
		] );

		$restrictedGroups = $configReader->getConfig( 'wiki1' );
		$this->assertArrayHasKey( 'sysop', $restrictedGroups );
		$this->assertCount( 1, $restrictedGroups );
		$this->assertInstanceOf( UserGroupRestrictions::class, $restrictedGroups['sysop'] );
		$this->assertEquals( [ 'COND_REMOTE' ], $restrictedGroups['sysop']->getMemberConditions() );
		$this->assertEquals( [], $restrictedGroups['sysop']->getUpdaterConditions() );
	}
}
