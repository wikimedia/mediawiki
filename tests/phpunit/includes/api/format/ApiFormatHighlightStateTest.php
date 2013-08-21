<?php

/**
 * @covers ApiFormatHighlightState
 */
class ApiFormatHighlightStateTest extends MediaWikiTestCase {
	public function testGetMarkerRange() {
		$hs = new ApiFormatHighlightState( '' );
		$allMarkers = '';
		for ( $i = 0; $i < 400; ++$i ) {
			$allMarkers .= $hs->addMarker( '' );
			if ( $i % 20 === 19 ) {
				$hs->startNewMarkerSet();
			}
		}
		$this->assertRegExp(
			'/^[' . $hs->getMarkerRange() . ']*$/',
			count_chars( $allMarkers, 3 /* get unique chars */ )
		);
	}

	public function testStartNewMarkerSet() {
		$hs = new ApiFormatHighlightState( '' );
		$markerOrig = $hs->getMarker( 0 );
		$hs->startNewMarkerSet();
		$markerAdd = $hs->addMarker( '' );
		$markerGet = $hs->getMarker( 0 );
		$this->assertNotSame( $markerOrig, $markerAdd );
		$this->assertSame( $markerAdd, $markerGet );
	}

	public function testGetMarker() {
		$hs = new ApiFormatHighlightState( '' );
		$markerA = $hs->addMarker( '' );
		$markerB = $hs->addMarker( '' );
		$this->assertSame( $markerA, $hs->getMarker( 0 ) );
		$this->assertSame( $markerB, $hs->getMarker( 1 ) );
	}

	public function testGetMarkerWithSetOffset() {
		$hs = new ApiFormatHighlightState( '' );
		$marker = $hs->getMarker( 0 );
		$hs->startNewMarkerSet();
		$this->assertSame( $marker, $hs->getMarker( 0, -1 ) );
	}

	public function provideStrReplaceHTML() {
		return array(
			array(
				'the <b>quick</b> brown fox',
				'quick',
				'<i>quick</i>',
				'the &lt;b&gt;<i>quick</i>&lt;/b&gt; brown fox',
			),
			array(
				'the <b>quick</b> brown fox',
				'b',
				'<b>b</b>',
				'the &lt;<b>b</b>&gt;quick&lt;/<b>b</b>&gt; <b>b</b>rown fox',
			),
			array(
				'the quick brown <fox>',
				array( 'b', '<' ),
				array( '<b>b</b>', '<i>&lt;</i>' ),
				'the quick <b>b</b>rown <i>&lt;</i>fox&gt;',
			),
		);
	}

	/**
	 * @dataProvider provideStrReplaceHTML
	 */
	public function testStrReplaceHTML( $text, $search, $replace, $html ) {
		$hs = new ApiFormatHighlightState( $text );
		$hs->strReplaceHTML( $search, $replace );
		$this->assertSame( $html, $hs->getHTML() );
	}

	public function providePregReplaceHTML() {
		return array(
			array(
				'Jumps O\'ver the "Lazy" Dog',
				'/\S*([A-Z])\S*/',
				'<abbr title="$0">$1</abbr>',
				'<abbr title="Jumps">J</abbr> <abbr title="O&#039;ver">O</abbr> the ' .
					'<abbr title="&quot;Lazy&quot;">L</abbr> <abbr title="Dog">D</abbr>',
			),
			array(
				'jumps over the lazy dog',
				array( '/j/', '/ ([ot])/', '/[a-z]/' ),
				array( 'j', ' <b>$1</b>', 'x' ),
				'jxxxx <b>x</b>xxx <b>x</b>xx xxxx xxx',
			),
			array(
				'abcdefghijkl',
				'/(.)(.)(.)(.)(.)(.)(.)(.)(.)(.)(.)(.)/',
				'$12$11$10$09$8$07$6$05$4$03$2$011\012\02\3\04\5\06\7\08\9\10\11\12',
				'lkjihgfedcba1a2bcdefghijkl',
			),
			array(
				'x',
				'/(.)/',
				'${1${1}${01${01}${99}',
				'${1x${01x',
			),
			array(
				'aaabbbbb',
				array( '/(a+)(b+)/', '/bbbbbaaa/' ),
				array( '$2$1', 'it works' ),
				'it works',
			),
		);
	}

	/**
	 * @dataProvider providePregReplaceHTML
	 */
	public function testPregReplaceHTML( $text, $pattern, $replacement, $html ) {
		$hs = new ApiFormatHighlightState( $text );
		$hs->pregReplaceHTML( $pattern, $replacement );
		$this->assertSame( $html, $hs->getHTML() );
	}

	public function testPregReplaceCallback() {
		$hs = new ApiFormatHighlightState( 'the <quick> brown fox' );
		$hs->pregReplaceCallback( '/\S*q\S*/', function ( $m ) use ( $hs ) {
			return $hs->addMarker( '<b>' ) . $m[0] . $hs->addMarker( '</b>' );
		} );
		$this->assertSame( 'the <b>&lt;quick&gt;</b> brown fox', $hs->getHTML() );
	}
}
