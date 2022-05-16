<?php
use MediaWiki\Utils\UrlUtils;

/**
 * @coversDefaultClass \MediaWiki\Utils\UrlUtils
 * @covers ::__construct
 */
class UrlUtilsTest extends MediaWikiUnitTestCase {

	public function testConstructError(): void {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unrecognized option "unrecognized"' );
		new UrlUtils( [ 'unrecognized' => true ] );
	}

	/**
	 * @covers ::expand
	 * @dataProvider UrlUtilsProviders::provideExpandException
	 * @param array $options
	 * @param string|int|null $defaultProto
	 * @param string $expectedClass Expected class of exception
	 * @param string $expectedMsg Expected exception message
	 */
	public function testExpandException(
		array $options, $defaultProto, string $expectedClass, string $expectedMsg
	): void {
		$this->expectException( $expectedClass );
		$this->expectExceptionMessage( $expectedMsg );

		$urlUtils = new UrlUtils( $options );
		$urlUtils->expand( '/', $defaultProto );
	}

	/**
	 * @covers ::expand
	 * @dataProvider UrlUtilsProviders::provideExpand
	 * @param string $input
	 * @param array $options
	 * @param string|int|null $defaultProto
	 * @param ?string $expected
	 */
	public function testExpand(
		string $input, array $options, $defaultProto, ?string $expected
	): void {
		$urlUtils = new UrlUtils( $options );
		$this->assertSame( $expected, $urlUtils->expand( $input, $defaultProto ) );
	}

	/**
	 * @covers ::getServer
	 * @dataProvider UrlUtilsProviders::provideGetServer
	 * @param array $options
	 * @param string|int|null $defaultProto
	 * @param string $expected
	 */
	public function testGetServer( array $options, $defaultProto, string $expected ): void {
		$urlUtils = new UrlUtils( $options );
		$this->assertSame( $expected, $urlUtils->getServer( $defaultProto ) );
	}

	/**
	 * @covers ::assemble
	 * @dataProvider UrlUtilsProviders::provideAssemble
	 * @param array $bits
	 * @param string $expected
	 */
	public function testAssemble( array $bits, string $expected ): void {
		$urlUtils = new UrlUtils( [ UrlUtils::VALID_PROTOCOLS => [
			'//',
			'http://',
			'https://',
			'file://',
			'mailto:',
		] ] );
		$this->assertSame( $expected, $urlUtils->assemble( $bits ) );
	}

	/**
	 * @covers ::removeDotSegments
	 * @dataProvider UrlUtilsProviders::provideRemoveDotSegments
	 * @param string $input
	 * @param string $expected
	 */
	public function testRemoveDotSegments( string $input, string $expected ): void {
		$this->assertSame( $expected, ( new UrlUtils )->removeDotSegments( $input ) );
	}

	/**
	 * @covers ::validProtocols
	 * @covers ::validAbsoluteProtocols
	 * @covers ::validProtocolsInternal
	 * @dataProvider UrlUtilsProviders::provideValidProtocols
	 * @param string $method 'validProtocols' or 'validAbsoluteProtocols'
	 * @param array|string $validProtocols Value of option passed to UrlUtils
	 * @param string $expected
	 */
	public function testValidProtocols( string $method, $validProtocols, string $expected ): void {
		if ( !is_array( $validProtocols ) ) {
			$this->expectDeprecationAndContinue(
				'/Use of \$wgUrlProtocols that is not an array was deprecated in MediaWiki 1\.39/' );
		}
		$urlUtils = new UrlUtils( [ UrlUtils::VALID_PROTOCOLS => $validProtocols ] );
		$this->assertSame( $expected, $urlUtils->$method() );
	}

	/**
	 * @covers ::parse
	 * @dataProvider UrlUtilsProviders::provideParse
	 * @param string $url
	 * @param ?array $expected
	 */
	public function testParse( string $url, ?array $expected ): void {
		$urlUtils = new UrlUtils( [ UrlUtils::VALID_PROTOCOLS => [
			'//',
			'http://',
			'https://',
			'file://',
			'mailto:',
		] ] );
		$actual = $urlUtils->parse( $url );
		if ( $expected ) {
			ksort( $expected );
		}
		if ( $actual ) {
			ksort( $actual );
		}
		$this->assertSame( $expected, $actual );
	}

	/**
	 * @covers ::expandIRI
	 */
	public function testExpandIRI(): void {
		$this->assertSame( "https://te.wikibooks.org/wiki/ఉబుంటు_వాడుకరి_మార్గదర్శని",
			( new UrlUtils )->expandIRI( "https://te.wikibooks.org/wiki/"
				. "%E0%B0%89%E0%B0%AC%E0%B1%81%E0%B0%82%E0%B0%9F%E0%B1%81_"
				. "%E0%B0%B5%E0%B0%BE%E0%B0%A1%E0%B1%81%E0%B0%95%E0%B0%B0%E0%B0%BF_"
				. "%E0%B0%AE%E0%B0%BE%E0%B0%B0%E0%B1%8D%E0%B0%97%E0%B0%A6%E0%B0%B0"
				. "%E0%B1%8D%E0%B0%B6%E0%B0%A8%E0%B0%BF" ) );
	}

	/**
	 * @covers ::matchesDomainList
	 * @dataProvider UrlUtilsProviders::provideMatchesDomainList
	 * @param string $url
	 * @param array $domains
	 * @param bool $expected
	 */
	public function testMatchesDomainList( string $url, array $domains, bool $expected ): void {
		$this->assertSame( $expected, ( new UrlUtils )->matchesDomainList( $url, $domains ) );
	}

}
