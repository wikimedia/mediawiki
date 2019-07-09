<?php

/**
 * @covers ComposerVersionNormalizer
 *
 * @group ComposerHooks
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ComposerVersionNormalizerTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider nonStringProvider
	 */
	public function testGivenNonString_normalizeThrowsInvalidArgumentException( $nonString ) {
		$normalizer = new ComposerVersionNormalizer();

		$this->setExpectedException( InvalidArgumentException::class );
		$normalizer->normalizeSuffix( $nonString );
	}

	public function nonStringProvider() {
		return [
			[ null ],
			[ 42 ],
			[ [] ],
			[ new stdClass() ],
			[ true ],
		];
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
		return [
			[ '1.22.0' ],
			[ '1.19.2' ],
			[ '1.19.2.0' ],
			[ '1.9' ],
			[ '123.321.456.654' ],
		];
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
		return [
			[ '1.22.0alpha', '1.22.0-alpha' ],
			[ '1.22.0RC', '1.22.0-RC' ],
			[ '1.19beta', '1.19-beta' ],
			[ '1.9RC4', '1.9-RC4' ],
			[ '1.9.1.2RC4', '1.9.1.2-RC4' ],
			[ '1.9.1.2RC', '1.9.1.2-RC' ],
			[ '123.321.456.654RC9001', '123.321.456.654-RC9001' ],
		];
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
		return [
			[ '1.22.0.0' ],
			[ '1.19.2.4' ],
			[ '1.19.2.0' ],
			[ '1.9.0.1' ],
			[ '123.321.456.654' ],
			[ '123.321.456.654RC4' ],
			[ '123.321.456.654-RC4' ],
		];
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
		return [
			[ '1.22.0.0', '1.22' ],
			[ '1.22.0.0', '1.22.0' ],
			[ '1.19.2.0', '1.19.2' ],
			[ '12345.0.0.0', '12345' ],
			[ '12345.0.0.0-RC4', '12345-RC4' ],
			[ '12345.0.0.0-alpha', '12345-alpha' ],
		];
	}

	/**
	 * @dataProvider invalidVersionProvider
	 */
	public function testGivenInvalidVersion_normalizeSuffixReturnsAsIs( $invalidVersion ) {
		$this->assertRemainsUnchanged( $invalidVersion );
	}

	public function invalidVersionProvider() {
		return [
			[ '1.221-a' ],
			[ '1.221-' ],
			[ '1.22rc4a' ],
			[ 'a1.22rc' ],
			[ '.1.22rc' ],
			[ 'a' ],
			[ 'alpha42' ],
		];
	}
}
