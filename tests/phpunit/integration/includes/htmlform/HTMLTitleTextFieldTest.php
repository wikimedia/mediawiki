<?php

use MediaWiki\Interwiki\InterwikiLookupAdapter;
use MediaWiki\Title\Title;

/**
 * @covers HTMLTitleTextFieldTest
 */
class HTMLTitleTextFieldTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideInterwiki
	 */
	public function testInterwiki( array $config, string $value, $expected ) {
		$this->setupInterwikiTable();
		$titleFactory = $this->createMock( TitleFactory::class );
		$titleFactory->method( 'newFromTextThrow' )->willReturnCallback( static function ( $text, $ns ) {
			$ret = Title::newFromTextThrow( $text, $ns );
			// Mark the title as nonexistent to avoid DB queries.
			$ret->resetArticleID( 0 );
			return $ret;
		} );
		$this->setService( 'TitleFactory', $titleFactory );
		$htmlForm = $this->createMock( HTMLForm::class );
		$htmlForm->method( 'msg' )->willReturnCallback( 'wfMessage' );

		$field = new HTMLTitleTextField( $config + [ 'fieldname' => 'foo', 'parent' => $htmlForm ] );
		$result = $field->validate( $value, [ 'foo' => $value ] );
		if ( $result instanceof Message ) {
			$this->assertSame( $expected, $result->getKey() );
		} else {
			$this->assertSame( $expected, $result );
		}
	}

	public static function provideInterwiki() {
		return [
			'local title' => [ [ 'interwiki' => false ], 'SomeTitle', true ],
			'interwiki title, default' => [ [], 'unittest_foo:SomeTitle', 'htmlform-title-interwiki' ],
			'interwiki title, disallowed' => [ [ 'interwiki' => false ],
				'unittest_foo:SomeTitle', 'htmlform-title-interwiki' ],
			'interwiki title, allowed' => [ [ 'interwiki' => true ],
				'unittest_foo:SomeTitle', true ],
			'namespace safety check' => [ [ 'interwiki' => true, 'namespace' => NS_TALK ],
				'SomeTitle', 'htmlform-title-badnamespace' ],
			'interwiki ignores namespace' => [ [ 'interwiki' => true, 'namespace' => NS_TALK ],
				'unittest_foo:SomeTitle', true ],
			'creatable safety check' => [ [ 'interwiki' => true, 'creatable' => true ],
				'Special:Version', 'htmlform-title-not-creatable' ],
			'interwiki ignores creatable' => [ [ 'interwiki' => true, 'creatable' => true ],
				'unittest_foo:Special:Version', true ],
			'exists safety check' => [ [ 'interwiki' => true, 'exists' => true ],
				'SomeTitle', 'htmlform-title-not-exists' ],
			'interwiki ignores exists' => [ [ 'interwiki' => true, 'exists' => true ],
				'unittest_foo:SomeTitle', true ],
		];
	}

	public function testInterwiki_relative() {
		$this->expectException( InvalidArgumentException::class );
		$field = new HTMLTitleTextField( [
			'fieldname' => 'foo',
			'interwiki' => true,
			'relative' => true,
			'parent' => $this->createMock( HTMLForm::class )
		] );
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
