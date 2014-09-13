<?php

/**
 * @group Parser
 */
class TidyTest extends MediaWikiTestCase {
<<<<<<< HEAD   (304fd6 Merge remote-tracking branch 'origin/REL1_22' into fundraisi)
	public function setUp() {
		parent::setUp();
		$check = MWTidy::tidy( '' );
		if ( strpos( $check, '<!--' ) !== false ) {
			$this->markTestSkipped( 'Tidy not found' );
		}
	}

	/**
	 * @dataProvider provideTestWrapping
	 */
	public function testTidyWrapping( $expected, $text, $msg = '' ) {
		$text = MWTidy::tidy( $text );
		// We don't care about where Tidy wants to stick is <p>s
		$text = trim( preg_replace( '#</?p>#', '', $text ) );
		// Windows, we love you!
		$text = str_replace( "\r", '', $text );
		$this->assertEquals( $expected, $text, $msg );
	}

	public function provideTestWrapping() {
		return array(
			array(
				'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
				'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
				'<mw:editsection> should survive tidy'
			),
			array(
				'<editsection page="foo" section="bar">foo</editsection>',
				'<editsection page="foo" section="bar">foo</editsection>',
				'<editsection> should survive tidy'
			),
			array( '<mw:toc>foo</mw:toc>', '<mw:toc>foo</mw:toc>', '<mw:toc> should survive tidy' ),
			array( "<link foo=\"bar\" />\nfoo", '<link foo="bar"/>foo', '<link> should survive tidy' ),
			array( "<meta foo=\"bar\" />\nfoo", '<meta foo="bar"/>foo', '<meta> should survive tidy' ),
		);
	}
}
=======

	protected function setUp() {
		parent::setUp();
		$check = MWTidy::tidy( '' );
		if ( strpos( $check, '<!--' ) !== false ) {
			$this->markTestSkipped( 'Tidy not found' );
		}
	}

	/**
	 * @dataProvider provideTestWrapping
	 */
	public function testTidyWrapping( $expected, $text, $msg = '' ) {
		$text = MWTidy::tidy( $text );
		// We don't care about where Tidy wants to stick is <p>s
		$text = trim( preg_replace( '#</?p>#', '', $text ) );
		// Windows, we love you!
		$text = str_replace( "\r", '', $text );
		$this->assertEquals( $expected, $text, $msg );
	}

	public function provideTestWrapping() {
		return array(
			array(
				'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
				'<mw:editsection page="foo" section="bar">foo</mw:editsection>',
				'<mw:editsection> should survive tidy'
			),
			array(
				'<editsection page="foo" section="bar">foo</editsection>',
				'<editsection page="foo" section="bar">foo</editsection>',
				'<editsection> should survive tidy'
			),
			array( '<mw:toc>foo</mw:toc>', '<mw:toc>foo</mw:toc>', '<mw:toc> should survive tidy' ),
			array( "<link foo=\"bar\" />\nfoo", '<link foo="bar"/>foo', '<link> should survive tidy' ),
			array( "<meta foo=\"bar\" />\nfoo", '<meta foo="bar"/>foo', '<meta> should survive tidy' ),
		);
	}
}
>>>>>>> BRANCH (f3d821 Updated release notes and version number to MediaWiki 1.23.3)
