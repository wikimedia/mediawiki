<?php

/**
 * @covers ComposerVersionNormalizer
 *
 * @group ComposerHooks
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ComposerVersionNormalizerTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider nonStringProvider
	 */
	public function testGivenNonString_normalizeThrowsInvalidArgumentException( $nonString ) {
		$normalizer = new ComposerVersionNormalizer();

		$this->setExpectedException( 'InvalidArgumentException' );
		$normalizer->normalizeSuffix( $nonString );
	}

	public function nonStringProvider() {
		return array(
			array( null ),
			array( 42 ),
			array( array() ),
			array( new stdClass() ),
			array( true ),
		);
	}

	/**
	 * @dataProvider simpleVersionProvider
	 */
	public function testGivenSimpleVersion_normalizeSuffixReturnsAsIs( $simpleVersion ) {
		$this->assertRemainsUnchanged( $simpleVersion );
	}

	protected function assertRemainsUnchanged( $version ) {
		$normalizer = new ComposerVersionNormalizer();

		$this->assertEquals(
			$version,
			$normalizer->normalizeSuffix( $version )
		);
	}

	public function simpleVersionProvider() {
		return array(
			array( '1.22.0' ),
			array( '1.19.2' ),
			array( '1.19.2.0' ),
			array( '1.9' ),
			array( '123.321.456.654' ),
		);
	}

	/**
	 * @dataProvider complexVersionProvider
	 */
	public function testGivenComplexVersionWithoutDash_normalizeSuffixAddsDash(
		$withoutDash, $withDash
	) {
		$normalizer = new ComposerVersionNormalizer();

		$this->assertEquals(
			$withDash,
			$normalizer->normalizeSuffix( $withoutDash )
		);
	}

	public function complexVersionProvider() {
		return array(
			array( '1.22.0alpha', '1.22.0-alpha' ),
			array( '1.22.0RC', '1.22.0-RC' ),
			array( '1.19beta', '1.19-beta' ),
			array( '1.9RC4', '1.9-RC4' ),
			array( '1.9.1.2RC4', '1.9.1.2-RC4' ),
			array( '1.9.1.2RC', '1.9.1.2-RC' ),
			array( '123.321.456.654RC9001', '123.321.456.654-RC9001' ),
		);
	}

	/**
	 * @dataProvider complexVersionProvider
	 */
	public function testGivenComplexVersionWithDash_normalizeSuffixReturnsAsIs(
		$withoutDash, $withDash
	) {
		$this->assertRemainsUnchanged( $withDash );
	}

	/**
	 * @dataProvider fourLevelVersionsProvider
	 */
	public function testGivenFourLevels_levelCountNormalizationDoesNothing( $version ) {
		$normalizer = new ComposerVersionNormalizer();

		$this->assertEquals(
			$version,
			$normalizer->normalizeLevelCount( $version )
		);
	}

	public function fourLevelVersionsProvider() {
		return array(
			array( '1.22.0.0' ),
			array( '1.19.2.4' ),
			array( '1.19.2.0' ),
			array( '1.9.0.1' ),
			array( '123.321.456.654' ),
			array( '123.321.456.654RC4' ),
			array( '123.321.456.654-RC4' ),
		);
	}

	/**
	 * @dataProvider levelNormalizationProvider
	 */
	public function testGivenFewerLevels_levelCountNormalizationEnsuresFourLevels(
		$expected, $version
	) {
		$normalizer = new ComposerVersionNormalizer();

		$this->assertEquals(
			$expected,
			$normalizer->normalizeLevelCount( $version )
		);
	}

	public function levelNormalizationProvider() {
		return array(
			array( '1.22.0.0', '1.22' ),
			array( '1.22.0.0', '1.22.0' ),
			array( '1.19.2.0', '1.19.2' ),
			array( '12345.0.0.0', '12345' ),
			array( '12345.0.0.0-RC4', '12345-RC4' ),
			array( '12345.0.0.0-alpha', '12345-alpha' ),
		);
	}

	/**
	 * @dataProvider invalidVersionProvider
	 */
	public function testGivenInvalidVersion_normalizeSuffixReturnsAsIs( $invalidVersion ) {
		$this->assertRemainsUnchanged( $invalidVersion );
	}

	public function invalidVersionProvider() {
		return array(
			array( '1.221-a' ),
			array( '1.221-' ),
			array( '1.22rc4a' ),
			array( 'a1.22rc' ),
			array( '.1.22rc' ),
			array( 'a' ),
			array( 'alpha42' ),
		);
	}
}
