<?php

namespace MediaWiki\Tests\Unit\Mail\ConfirmEmail;

use MediaWiki\Config\HashConfig;
use MediaWiki\Context\IContextSource;
use MediaWiki\Language\Language;
use MediaWiki\Mail\ConfirmEmail\ConfirmEmailData;
use MediaWiki\Mail\ConfirmEmail\HTMLConfirmEmailBuilder;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\UrlUtils;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * @covers \MediaWiki\Mail\ConfirmEmail\HTMLConfirmEmailBuilder
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailData
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailContent
 */
class HTMLConfirmEmailBuilderTest extends MediaWikiUnitTestCase {

	private function getMockContext( array $config ) {
		$config = new HashConfig( $config );
		$language = $this->createNoOpMock( Language::class, [ 'getCode' ] );
		$language->expects( $this->once() )
			->method( 'getCode' )
			->willReturn( 'cs' );

		$context = $this->createNoOpMock(
			IContextSource::class,
			[ 'getConfig', 'getLanguage', 'msg' ]
		);
		$context->expects( $this->once() )
			->method( 'getConfig' )
			->willReturn( $config );
		$context->expects( $this->once() )
			->method( 'getLanguage' )
			->willReturn( $language );
		$context->expects( $this->atLeastOnce() )
			->method( 'msg' )
			->willReturnCallback( function ( $k, ...$p ) {
				return $this->getMockMessage( print_r( [ $k => $p ], true ) );
			} );
		return $context;
	}

	public static function provideHTMLEmailBuilder() {
		$logoVariants = [
			'no logo' => [
				[],
				[
					MainConfigNames::Logo => null,
					MainConfigNames::Logos => [],
				],
			],
			'one logo' => [
				[ 'https://wiki.cz/logo.png', 'confirmemail_html_logo_alttext' ],
				[
					MainConfigNames::Logo => 'https://wiki.cz/logo.png',
					MainConfigNames::Logos => [],
				],
			],
			'only 1x' => [
				[ 'https://wiki.cz/1x.png', 'confirmemail_html_logo_alttext' ],
				[
					MainConfigNames::Logo => 'https://wiki.cz/false_logo.png',
					MainConfigNames::Logos => [
						'1x' => 'https://wiki.cz/1x.png',
					],
				]
			],
			'full logo' => [
				[
					'https://wiki.cz/icon.png',
					'https://wiki.cz/wordmark.png',
					'https://wiki.cz/tagline.png',
					'confirmemail_html_logo_alttext',
					// icon needs to have dimensions configured
					'height="50" width="50"',
				],
				[
					MainConfigNames::Logo => 'https://wiki.cz/false_logo.png',
					MainConfigNames::Logos => [
						'1x' => 'https://wiki.cz/1x.png',
						'wordmark' => [
							'src' => 'https://wiki.cz/wordmark.png',
							'width' => 135,
							'height' => 20,
						],
						'tagline' => [
							'src' => 'https://wiki.cz/tagline.png',
							'width' => 135,
							'height' => 20,
						],
						'icon' => 'https://wiki.cz/icon.png',
					],
				]
			],
			'no icon' => [
				[
					'https://wiki.cz/1x.png',
					'https://wiki.cz/wordmark.png',
					'https://wiki.cz/tagline.png',
					'confirmemail_html_logo_alttext',
				],
				[
					MainConfigNames::Logo => '/false_logo.png',
					MainConfigNames::Logos => [
						'1x' => '/1x.png',
						'wordmark' => [
							'src' => '/wordmark.png',
							'width' => 135,
							'height' => 20,
						],
						'tagline' => [
							'src' => '/tagline.png',
							'width' => 135,
							'height' => 20,
						],
					],
				]
			],
		];

		foreach ( $logoVariants as $logoVariant ) {
			yield [ ...$logoVariant, 'buildEmailCreated' ];
			yield [ ...$logoVariant, 'buildEmailChanged' ];
			yield [ ...$logoVariant, 'buildEmailSet' ];
		}
	}

	/**
	 * @dataProvider provideHTMLEmailBuilder
	 */
	public function testHTMLEmailBuilder( array $extraExpectedNeedles, array $config ) {
		$context = $this->getMockContext( $config );

		// $urlUtils is needed to correctly expand logo URLs to absolute URLs;
		// User is guaranteed to provide absolute URLs as confirmation/invalidation URLs
		$urlUtils = $this->createNoOpMock( UrlUtils::class, [ 'expand' ] );
		$urlUtils->method( 'expand' )
			->with( $this->isType( 'string' ), PROTO_CANONICAL )
			->willReturnCallback( static function ( $url ) {
				return 'https://wiki.cz' . $url;
			} );

		$builder = new HTMLConfirmEmailBuilder( $context, new HashBagOStuff(), $urlUtils );
		$email = $builder->buildEmailCreated( new ConfirmEmailData(
			new UserIdentityValue( 1, 'Admin' ),
			'https://wiki.cz/confirm',
			'https://wiki.cz/invalidate',
			'20251122091232'
		) );

		foreach ( [ $email->getHtml(), $email->getPlaintext() ] as $emailText ) {
			$this->assertIsString( $emailText );
			$this->assertStringContainsString( 'https://wiki.cz/confirm', $emailText );
			$this->assertStringContainsString( 'https://wiki.cz/invalidate', $emailText );
		}

		$htmlEmailText = $email->getHtml();
		foreach ( $extraExpectedNeedles as $needle ) {
			$this->assertStringContainsString( $needle, $htmlEmailText );
		}
	}
}
