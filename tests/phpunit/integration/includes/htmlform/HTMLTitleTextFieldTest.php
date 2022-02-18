<?php

use MediaWiki\Interwiki\InterwikiLookupAdapter;

/**
 * @covers HTMLTitleTextFieldTest
 */
class HTMLTitleTextFieldTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideInterwiki
	 */
	public function testInterwiki( array $config, string $value, bool $isValid ) {
		$this->setupInterwikiTable();
		$field = new HTMLTitleTextField( $config + [ 'fieldname' => 'foo' ] );
		$result = $field->validate( $value, [ 'foo' => $value ] );
		if ( $isValid ) {
			$this->assertSame( true, $result );
		} else {
			// phpcs:ignore MediaWiki.PHPUnit.AssertEquals.True
			$this->assertNotSame( true, $result );
		}
	}

	public function provideInterwiki() {
		return [
			'local title' => [ [ 'interwiki' => false ], 'SomeTitle', true ],
			'interwiki title, default' => [ [], 'unittest_foo:SomeTitle', false ],
			'interwiki title, disallowed' => [ [ 'interwiki' => false ],
				'unittest_foo:SomeTitle', false ],
			'interwiki title, allowed' => [ [ 'interwiki' => true ],
				'unittest_foo:SomeTitle', true ],
			'namespace safety check' => [ [ 'interwiki' => true, 'namespace' => NS_TALK ],
				'SomeTitle', false ],
			'interwiki ignores namespace' => [ [ 'interwiki' => true, 'namespace' => NS_TALK ],
				'unittest_foo:SomeTitle', true ],
			'creatable safety check' => [ [ 'interwiki' => true, 'creatable' => true ],
				'Special:Version', false ],
			'interwiki ignores creatable' => [ [ 'interwiki' => true, 'creatable' => true ],
				'unittest_foo:Special:Version', true ],
			'exists safety check' => [ [ 'interwiki' => true, 'exists' => true ],
				'SomeTitle', false ],
			'interwiki ignores exists' => [ [ 'interwiki' => true, 'exists' => true ],
				'unittest_foo:SomeTitle', true ],
		];
	}

	public function testInterwiki_relative() {
		$this->expectException( InvalidArgumentException::class );
		$field = new HTMLTitleTextField( [ 'fieldname' => 'foo', 'interwiki' => true, 'relative' => true ] );
		$field->validate( 'SomeTitle', [ 'foo' => 'SomeTitle' ] );
	}

	protected function setupInterwikiTable() {
		$site = new Site( Site::TYPE_MEDIAWIKI );
		$site->setGlobalId( 'unittest_foowiki' );
		$site->addInterwikiId( 'unittest_foo' );
		$this->setService( 'InterwikiLookup', new InterwikiLookupAdapter( new HashSiteStore( [ $site ] ) ) );
		$this->assertTrue( Title::newFromText( 'unittest_foo:SomeTitle' )->isExternal() );
	}

}
