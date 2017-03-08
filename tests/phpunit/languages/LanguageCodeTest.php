<?php

/**
 * @covers LanguageCode
 *
 * @group Language
 *
 * @license GPL-2.0+
 * @author Thiemo Mättig
 */
class LanguageCodeTest extends PHPUnit_Framework_TestCase {

	public function testConstructor() {
		$instance = new LanguageCode();

		$this->assertInstanceOf( LanguageCode::class, $instance );
	}

	public function testGetDeprecatedCodeMapping() {
		$map = LanguageCode::getDeprecatedCodeMapping();

		$this->assertInternalType( 'array', $map );
		$this->assertContainsOnly( 'string', array_keys( $map ) );
		$this->assertArrayNotHasKey( '', $map );
		$this->assertContainsOnly( 'string', $map );
		$this->assertNotContains( '', $map );

		// Codes special to MediaWiki should never appear in a map of "deprecated" codes
		$this->assertArrayNotHasKey( 'qqq', $map, 'documentation' );
		$this->assertNotContains( 'qqq', $map, 'documentation' );
		$this->assertArrayNotHasKey( 'qqx', $map, 'debug code' );
		$this->assertNotContains( 'qqx', $map, 'debug code' );

		// Valid language codes that are currently not "deprecated"
		$this->assertArrayNotHasKey( 'bh', $map, 'family of Bihari languages' );
		$this->assertArrayNotHasKey( 'no', $map, 'family of Norwegian languages' );
		$this->assertArrayNotHasKey( 'simple', $map );
	}

}
