<?php

namespace MediaWiki\Tests\Unit\Mail\ConfirmEmail;

use MediaWiki\Context\IContextSource;
use MediaWiki\Language\Language;
use MediaWiki\Mail\ConfirmEmail\ConfirmEmailData;
use MediaWiki\Mail\ConfirmEmail\PlaintextConfirmEmailBuilder;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Mail\ConfirmEmail\PlaintextConfirmEmailBuilder
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailData
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailContent
 */
class PlaintextConfirmEmailBuilderTest extends MediaWikiUnitTestCase {

	private function getMockContext( string $urlExpiration ) {
		$request = $this->createNoOpMock( WebRequest::class, [ 'getIP' ] );
		$request->expects( $this->once() )
			->method( 'getIP' )
			->willReturn( '1.2.3.4' );
		$language = $this->createNoOpMock(
			Language::class,
			[ 'userTimeAndDate', 'userDate', 'userTime' ]
		);
		$language->expects( $this->once() )
			->method( 'userTimeAndDate' )
			->with( $urlExpiration )
			->willReturn( 'expirationTimeAndDate' );
		$language->expects( $this->once() )
			->method( 'userDate' )
			->with( $urlExpiration )
			->willReturn( 'expirationDate' );
		$language->expects( $this->once() )
			->method( 'userTime' )
			->with( $urlExpiration )
			->willReturn( 'expirationTime' );

		$context = $this->createNoOpMock(
			IContextSource::class,
			[ 'getRequest', 'getLanguage', 'msg' ]
		);
		$context->expects( $this->once() )
			->method( 'getRequest' )
			->willReturn( $request );
		$context->expects( $this->atLeastOnce() )
			->method( 'getLanguage' )
			->willReturn( $language );
		$context->expects( $this->atLeastOnce() )
			->method( 'msg' )
			->willReturnCallback( function ( $k, ...$p ) {
				return $this->getMockMessage( print_r( [ $k => $p ], true ) );
			} );
		return $context;
	}

	public static function provideMethodNames() {
		return [
			[ 'buildEmailCreated' ],
			[ 'buildEmailChanged' ],
			[ 'buildEmailSet' ],
		];
	}

	/**
	 * @dataProvider provideMethodNames
	 */
	public function testPlaintextBuilder( string $method ) {
		$data = new ConfirmEmailData(
			new UserIdentityValue( 1, 'Admin' ),
			'https://wiki.cz/confirm',
			'https://wiki.cz/invalidate',
			'20251122091232'
		);
		$context = $this->getMockContext( $data->getUrlExpiration() );
		$builder = new PlaintextConfirmEmailBuilder( $context );

		$email = $builder->$method( $data );

		$this->assertNull( $email->getHtml() );

		$plaintext = $email->getPlaintext();
		$this->assertStringContainsString( 'https://wiki.cz/confirm', $plaintext );
		$this->assertStringContainsString( 'https://wiki.cz/invalidate', $plaintext );
		$this->assertStringContainsString( '1.2.3.4', $plaintext );
		$this->assertStringContainsString( 'expirationTimeAndDate', $plaintext );
		$this->assertStringContainsString( 'expirationTime', $plaintext );
		$this->assertStringContainsString( 'expirationDate', $plaintext );
	}
}
