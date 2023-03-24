<?php

/**
 * @covers StripState
 */
class StripStateTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();
		$this->setContentLang( 'qqx' );
	}

	private function getMarker() {
		static $i;
		return Parser::MARKER_PREFIX . '-blah-' . sprintf( '%08X', $i++ ) . Parser::MARKER_SUFFIX;
	}

	private static function getWarning( $message, $max = '' ) {
		return "<span class=\"error\">($message: $max)</span>";
	}

	public function testAddNoWiki() {
		$ss = new StripState;
		$marker = $this->getMarker();
		$ss->addNoWiki( $marker, '<>' );
		$text = "x{$marker}y";
		$text = $ss->unstripGeneral( $text );
		$text = str_replace( '<', '', $text );
		$text = $ss->unstripNoWiki( $text );
		$this->assertSame( 'x<>y', $text );
	}

	public function testAddGeneral() {
		$ss = new StripState;
		$marker = $this->getMarker();
		$ss->addGeneral( $marker, '<>' );
		$text = "x{$marker}y";
		$text = $ss->unstripNoWiki( $text );
		$text = str_replace( '<', '', $text );
		$text = $ss->unstripGeneral( $text );
		$this->assertSame( 'x<>y', $text );
	}

	public function testUnstripBoth() {
		$ss = new StripState;
		$mk1 = $this->getMarker();
		$mk2 = $this->getMarker();
		$ss->addNoWiki( $mk1, '<1>' );
		$ss->addGeneral( $mk2, '<2>' );
		$text = "x{$mk1}{$mk2}y";
		$text = str_replace( '<', '', $text );
		$text = $ss->unstripBoth( $text );
		$this->assertSame( 'x<1><2>y', $text );
	}

	public static function provideUnstripRecursive() {
		return [
			[ 0, 'text' ],
			[ 1, '=text=' ],
			[ 2, '==text==' ],
			[ 3, '==' . self::getWarning( 'unstrip-depth-warning', 2 ) . '==' ],
		];
	}

	/** @dataProvider provideUnstripRecursive */
	public function testUnstripRecursive( $depth, $expected ) {
		$ss = new StripState( null, [ 'depthLimit' => 2 ] );
		$text = 'text';
		for ( $i = 0; $i < $depth; $i++ ) {
			$mk = $this->getMarker();
			$ss->addNoWiki( $mk, "={$text}=" );
			$text = $mk;
		}
		$text = $ss->unstripNoWiki( $text );
		$this->assertSame( $expected, $text );
	}

	public function testUnstripLoop() {
		$ss = new StripState( null, [ 'depthLimit' => 2 ] );
		$mk = $this->getMarker();
		$ss->addNoWiki( $mk, $mk );
		$text = $ss->unstripNoWiki( $mk );
		$this->assertSame( self::getWarning( 'parser-unstrip-loop-warning' ), $text );
	}

	public static function provideUnstripSize() {
		return [
			[ 0, 'x' ],
			[ 1, 'xx' ],
			[ 2, str_repeat( self::getWarning( 'unstrip-size-warning', 5 ), 2 ) ]
		];
	}

	/** @dataProvider provideUnstripSize */
	public function testUnstripSize( $depth, $expected ) {
		$ss = new StripState( null, [ 'sizeLimit' => 5 ] );
		$text = 'x';
		for ( $i = 0; $i < $depth; $i++ ) {
			$mk = $this->getMarker();
			$ss->addNoWiki( $mk, $text );
			$text = "$mk$mk";
		}
		$text = $ss->unstripNoWiki( $text );
		$this->assertSame( $expected, $text );
	}

	public static function provideGetLimitReport() {
		for ( $i = 1; $i < 4; $i++ ) {
			yield [ $i ];
		}
	}

	/** @dataProvider provideGetLimitReport */
	public function testGetLimitReport( $depth ) {
		$sizeLimit = 100000;
		$ss = new StripState( null, [ 'depthLimit' => 5, 'sizeLimit' => $sizeLimit ] );
		$text = 'x';
		for ( $i = 0; $i < $depth; $i++ ) {
			$mk = $this->getMarker();
			$ss->addNoWiki( $mk, $text );
			$text = "$mk$mk";
		}
		$text = $ss->unstripNoWiki( $text );
		$report = $ss->getLimitReport();
		$messages = [];
		foreach ( $report as [ $msg, $params ] ) {
			$messages[$msg] = $params;
		}
		$this->assertSame( [ $depth - 1, 5 ], $messages['limitreport-unstrip-depth'] );
		$this->assertSame(
			[
				strlen( $this->getMarker() ) * 2 * ( pow( 2, $depth ) - 2 ) + pow( 2, $depth ),
				$sizeLimit
			],
			$messages['limitreport-unstrip-size' ] );
	}

	public function testReplaceNowikis() {
		$ss = new StripState();

		// Note that unlike other uses of addNowiki, these add the original source
		// with the nowiki wrappers. When wikitext is being processed, the parser
		// uses strip markers in this fashion.
		$s1 = "[[Foo]]";
		$nowikiS1 = "<nowiki>$s1</nowiki>";
		$m1 = $this->getMarker();
		$ss->addNoWiki( $m1, $nowikiS1 );

		$s2 = "[[Foo]]";
		$nowikiS2 = "<nowiki>$s2</nowiki>";
		$m2 = $this->getMarker();
		$ss->addNoWiki( $m2, $nowikiS2 );

		$s3 = "";
		$nowikiS3 = "<nowiki />";
		$m3 = $this->getMarker();
		$ss->addNoWiki( $m3, $nowikiS3 );

		$text = "$s1; $s2; $s3";
		$strippedText = "$m1; $m2; $m3";
		$unstrippedText = "$nowikiS1; $nowikiS2; $nowikiS3";

		$this->assertSame( $ss->unstripGeneral( $strippedText ), $strippedText );
		$this->assertSame( $ss->unstripNoWiki( $strippedText ), $unstrippedText );

		$out1 = $ss->replaceNoWikis( $strippedText, static function ( $s ) {
			return $s;
		} );
		$this->assertSame( $out1, $unstrippedText );

		// Simulate Scribunto lua modules use of unstripNowiki
		$out2 = $ss->replaceNoWikis( $strippedText, static function ( $s ) {
			return preg_replace( "#</?nowiki[^>]*>#", '', $s );
		} );
		$this->assertSame( $out2, $text );
	}
}
