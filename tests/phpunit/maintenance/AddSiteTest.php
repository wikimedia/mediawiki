<?php

namespace MediaWiki\Tests\Maintenance;

use AddSite;
use MediaWiki\Site\HashSiteStore;
use MediaWiki\Site\MediaWikiSite;
use MediaWiki\Site\Site;

/**
 * @covers \AddSite
 * @author Dreamy Jazz
 */
class AddSiteTest extends MaintenanceBaseTestCase {

	protected function setUp(): void {
		parent::setUp();
		// Avoid needing to write to the DB for the tests.
		$this->setService( 'SiteStore', new HashSiteStore() );
	}

	protected function getMaintenanceClass() {
		return AddSite::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $globalId, $group, $expectedOutputRegex ) {
		$this->expectCallToFatalError();
		$this->maintenance->setArg( 'globalid', $globalId );
		$this->maintenance->setArg( 'group', $group );
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'Global ID is not a string' => [ null, 'test', '/Arguments globalid and group need to be strings/' ],
			'Group is not a string' => [ 'test', null, '/Arguments globalid and group need to be strings/' ],
		];
	}

	public function testExecuteWhenSiteAlreadyExists() {
		$foo = Site::newForType( Site::TYPE_UNKNOWN );
		$foo->setGlobalId( 'Foo' );
		$this->getServiceContainer()->getSiteStore()->saveSite( $foo );

		$this->testExecuteForFatalError(
			$foo->getGlobalId(), 'wikipedia', "/Site with global id {$foo->getGlobalId()} already exists/"
		);
	}

	public function testExecute() {
		// Set up the arguments for the script and then run it.
		$this->maintenance->setArg( 'globalid', 'enwiki' );
		$this->maintenance->setArg( 'group', 'wikipedia' );
		$this->maintenance->setOption( 'language', 'en' );
		$this->maintenance->setOption( 'interwiki-id', 'en' );
		$this->maintenance->setOption( 'navigation-id', 'en' );
		$this->maintenance->setOption( 'pagepath', 'http://en.wikipedia.org/wiki/' );
		$this->maintenance->setOption( 'filepath', 'http://en.wikipedia.org/w/' );
		$this->expectOutputRegex( "/Done/" );
		$this->maintenance->execute();
		// Check that that the sitestore contains the expected data.
		$actualSite = $this->getServiceContainer()->getSiteStore()->getSite( 'enwiki' );
		$this->assertNotNull( $actualSite );
		$this->assertSame( 'wikipedia', $actualSite->getGroup() );
		$this->assertSame( 'en', $actualSite->getLanguageCode() );
		$this->assertArrayEquals( [ 'en' ], $actualSite->getInterwikiIds() );
		$this->assertArrayEquals( [ 'en' ], $actualSite->getNavigationIds() );
		$this->assertSame( 'http://en.wikipedia.org/w/', $actualSite->getPath( MediaWikiSite::PATH_FILE ) );
		$this->assertSame( 'http://en.wikipedia.org/wiki/', $actualSite->getPath( MediaWikiSite::PATH_PAGE ) );
	}
}
