<?php

class TitleTest extends MediaWikiTestCase {

	function testLegalChars() {
		$titlechars = Title::legalChars();

		foreach ( range( 1, 255 ) as $num ) {
			$chr = chr( $num );
			if ( strpos( "#[]{}<>|", $chr ) !== false || preg_match( "/[\\x00-\\x1f\\x7f]/", $chr ) ) {
				$this->assertFalse( (bool)preg_match( "/[$titlechars]/", $chr ), "chr($num) = $chr is not a valid titlechar" );
			} else {
				$this->assertTrue( (bool)preg_match( "/[$titlechars]/", $chr ), "chr($num) = $chr is a valid titlechar" );
			}
		}
	}

	/**
	 * Helper to test getLocalURL
	 *
	 * @cover Title:getLocalURL
	 */
	function assertGetLocalURL( $expected, $dbkey, $query, $articlepath, $actionpaths = array(), $variant = false )
	{
		global $wgArticlePath;
		$wgArticlePath = $articlepath;
		global $wgActionPaths;
		$wgActionPaths = $actionpaths;

		$t = Title::newFromDBKey( $dbkey );

		$this->assertEquals( $expected, $t->getLocalURL( $query, $variant ) );
	}
	
	/**
	 * @dataProvider provider1
	 * @cover Title:getLocalURL
	 */
	function testGetLocalUrl( $expected, $dbkey, $query ) 
	{
		$this->assertGetLocalURL( $expected, $dbkey, $query, '/wiki/$1' );
	}

	function provider1()
	{
		return array(
			array( '/wiki/Recentchanges', 'Recentchanges', '' ),
			array( '/wiki/Recentchanges?foo=2', 'Recentchanges', 'foo=2' ),
			array( '/wiki/Recentchanges?foo=A&bar=1', 'Recentchanges', 'foo=A&bar=1' ),
		);
	}


	/**
	 * @dataProvider provider2
	 * @cover Title:getLocalURL
	 */
	function testGetLocalUrlWithArticlePath( $expected, $dbkey, $query, $actions ) 
	{
		$this->assertGetLocalURL( $expected, $dbkey, $query, '/wiki/view/$1', $actions );
	}

	function provider2()
	{
		return array(
			array( '/wiki/view/Recentchanges', 'Recentchanges', '', array( ) ),
			array( '/wiki/view/Recentchanges', 'Recentchanges', '', array( 'view' => 'OH MEN' ) ),
			array( '/wiki/view/Recentchanges', 'Recentchanges', '', array( 'view' => '/wiki/view/$1' ) ),
			array( '/wiki/view/Recentchanges?foo=2', 'Recentchanges', 'foo=2', array( 'view' => '/wiki/view/$1' ) ),
			array( '/wiki/view/Recentchanges?foo=A&bar=1', 'Recentchanges', 'foo=A&bar=1', array() ),
		);
	}


	/**
	 * @dataProvider provider3
	 * @cover Title:getLocalURL
	 */
	function testGetLocalUrlWithActionPaths( $expected, $dbkey, $query ) 
	{
		$actions = array( 'edit' => '/wiki/edit/$1' );
		$this->assertGetLocalURL( $expected, $dbkey, $query, '/wiki/view/$1', $actions );
	}

	function provider3() {
		return array(
			array( '/wiki/view/Recentchanges', 'Recentchanges', ''),
			array( '/wiki/edit/Recentchanges', 'Recentchanges', 'action=edit' ),
			array( '/wiki/edit/Recentchanges?foo=2', 'Recentchanges', 'action=edit&foo=2' ),
			array( '/wiki/edit/Recentchanges?foo=2', 'Recentchanges', 'foo=2&action=edit' ),
			array( '/wiki/view/Recentchanges?foo=A&bar=1', 'Recentchanges', 'foo=A&bar=1' ),
			array( '/wiki/edit/Recentchanges?foo=A&bar=1', 'Recentchanges', 'foo=A&bar=1&action=edit' ),
			array( '/wiki/edit/Recentchanges?foo=A&bar=1', 'Recentchanges', 'foo=A&action=edit&bar=1' ),
			array( '/wiki/edit/Recentchanges?foo=A&bar=1', 'Recentchanges', 'action=edit&foo=A&bar=1' ),

			# FIXME The next two are equals but need investigation:
			array( '/wiki/edit/Recentchanges', 'Recentchanges', 'action=view&action=edit' ),
			array( '/wiki/view/Recentchanges?action=edit&action=view', 'Recentchanges', 'action=edit&action=view' ),
		);
	}

	/**
	 * @dataProvider provider4
	 * @cover Title:getLocalURL
	 */
	function testGetLocalUrlWithVariantArticlePaths( $expected, $dbkey, $query )
	{
		# FIXME find a language with variants!
		$this->markTestIncomplete();

		$actions = array( 'edit' => '/wiki/edit/$1' );
		$this->assertGetLocalURL( $expected, $dbkey, $query, '/wiki/view/$1', array(), '/$2/$1' );
	}

	function provider4() {
		return array(
			array( '/wiki/view/Recentchanges', 'Recentchanges', '', ),
		);
	}


}
