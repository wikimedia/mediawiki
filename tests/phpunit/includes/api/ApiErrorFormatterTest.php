<?php

namespace MediaWiki\Tests\Api;

use Exception;
use MediaWiki\Api\ApiErrorFormatter;
use MediaWiki\Api\ApiErrorFormatter_BackCompat;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\ApiResult;
use MediaWiki\Api\IApiMessage;
use MediaWiki\Exception\LocalizedException;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\Status\Status;
use MediaWikiLangTestCase;
use RuntimeException;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 */
class ApiErrorFormatterTest extends MediaWikiLangTestCase {

	/**
	 * @covers \MediaWiki\Api\ApiErrorFormatter
	 */
	public function testErrorFormatterBasics() {
		$result = new ApiResult( 8_388_608 );
		$formatter = new ApiErrorFormatter( $result,
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'de' ), 'wikitext',
			false );
		$this->assertSame( 'de', $formatter->getLanguage()->getCode() );
		$this->assertSame( 'wikitext', $formatter->getFormat() );

		$formatter->addMessagesFromStatus( null, Status::newGood() );
		$this->assertSame(
			[ ApiResult::META_TYPE => 'assoc' ],
			$result->getResultData()
		);

		$this->assertSame( [], $formatter->arrayFromStatus( Status::newGood() ) );

		$wrappedFormatter = TestingAccessWrapper::newFromObject( $formatter );
		$this->assertSame(
			'Blah "kbd" <X> 😊',
			$wrappedFormatter->stripMarkup( 'Blah <kbd>kbd</kbd> <b>&lt;X&gt;</b> &#x1f60a;' ),
			'stripMarkup'
		);
	}

	/**
	 * @covers \MediaWiki\Api\ApiErrorFormatter
	 * @covers \MediaWiki\Api\ApiErrorFormatter_BackCompat
	 */
	public function testNewWithFormat() {
		$result = new ApiResult( 8_388_608 );
		$formatter = new ApiErrorFormatter( $result,
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'de' ), 'wikitext',
			false );
		$formatter2 = $formatter->newWithFormat( 'html' );

		$this->assertSame( $formatter->getLanguage(), $formatter2->getLanguage() );
		$this->assertSame( 'html', $formatter2->getFormat() );

		$formatter3 = new ApiErrorFormatter_BackCompat( $result );
		$formatter4 = $formatter3->newWithFormat( 'html' );
		$this->assertNotInstanceOf( ApiErrorFormatter_BackCompat::class, $formatter4 );
		$this->assertSame( $formatter3->getLanguage(), $formatter4->getLanguage() );
		$this->assertSame( 'html', $formatter4->getFormat() );
	}

	/**
	 * @covers \MediaWiki\Api\ApiErrorFormatter
	 * @dataProvider provideErrorFormatter
	 */
	public function testErrorFormatter( $format, $lang, $useDB,
		$expect1, $expect2, $expect3
	) {
		$result = new ApiResult( 8_388_608 );
		$formatter = new ApiErrorFormatter( $result,
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( $lang ), $format,
			$useDB );

		// Add default type
		$expect1[ApiResult::META_TYPE] = 'assoc';
		$expect2[ApiResult::META_TYPE] = 'assoc';
		$expect3[ApiResult::META_TYPE] = 'assoc';

		$formatter->addWarning( 'string', 'mainpage' );
		$formatter->addError( 'err', 'aboutpage' );
		$this->assertEquals( $expect1, $result->getResultData(), 'Simple test' );

		$result->reset();
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'foo', [ 'parentheses', 'foobar' ] );
		$msg1 = wfMessage( 'copyright' );
		$formatter->addWarning( 'message', $msg1 );
		$msg2 = new ApiMessage( 'disclaimers', 'overriddenCode', [ 'overriddenData' => true ] );
		$formatter->addWarning( 'messageWithData', $msg2 );
		$msg3 = new ApiMessage( 'edithelp', 'overriddenCode', [ 'overriddenData' => true ] );
		$formatter->addError( 'errWithData', $msg3 );
		$this->assertSame( $expect2, $result->getResultData(), 'Complex test' );

		$this->assertEquals(
			$this->removeModuleTag( $expect2['warnings'][2] ),
			$formatter->formatMessage( $msg1 ),
			'formatMessage test 1'
		);
		$this->assertEquals(
			$this->removeModuleTag( $expect2['warnings'][3] ),
			$formatter->formatMessage( $msg2 ),
			'formatMessage test 2'
		);

		$result->reset();
		$status = Status::newGood();
		$status->warning( 'mainpage' );
		$status->warning( 'parentheses', 'foobar' );
		$status->warning( $msg1 );
		$status->warning( $msg2 );
		$status->error( 'aboutpage' );
		$status->error( 'brackets', Message::sizeParam( 123 ) );
		$formatter->addMessagesFromStatus( 'status', $status );
		$this->assertSame( $expect3, $result->getResultData(), 'Status test' );

		$this->assertSame(
			array_map( [ $this, 'removeModuleTag' ], $expect3['errors'] ),
			$formatter->arrayFromStatus( $status, 'error' ),
			'arrayFromStatus test for error'
		);
		$this->assertSame(
			array_map( [ $this, 'removeModuleTag' ], $expect3['warnings'] ),
			$formatter->arrayFromStatus( $status, 'warning' ),
			'arrayFromStatus test for warning'
		);
	}

	private function removeModuleTag( $s ) {
		if ( is_array( $s ) ) {
			unset( $s['module'] );
		}
		return $s;
	}

	private static function text( $msg ) {
		return $msg->inLanguage( 'de' )->useDatabase( false )->text();
	}

	private static function html( $msg ) {
		return $msg->inLanguage( 'en' )->parse();
	}

	public static function provideErrorFormatter() {
		$aboutpage = wfMessage( 'aboutpage' );
		$mainpage = wfMessage( 'mainpage' );
		$parens = wfMessage( 'parentheses', 'foobar' );
		$brackets = wfMessage( 'brackets' )->sizeParams( 123 );
		$copyright = wfMessage( 'copyright' );
		$disclaimers = wfMessage( 'disclaimers' );
		$edithelp = wfMessage( 'edithelp' );

		$C = ApiResult::META_CONTENT;
		$I = ApiResult::META_INDEXED_TAG_NAME;
		$overriddenData = [ 'overriddenData' => true, ApiResult::META_TYPE => 'assoc' ];

		return [
			'zero' => $tmp = [ 'wikitext', 'de', false,
				[
					'errors' => [
						[ 'code' => 'aboutpage', 'text' => self::text( $aboutpage ), 'module' => 'err', $C => 'text' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'text' => self::text( $mainpage ), 'module' => 'string', $C => 'text' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'overriddenCode', 'text' => self::text( $edithelp ),
							'data' => $overriddenData, 'module' => 'errWithData', $C => 'text' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'text' => self::text( $mainpage ), 'module' => 'foo', $C => 'text' ],
						[ 'code' => 'parentheses', 'text' => self::text( $parens ), 'module' => 'foo', $C => 'text' ],
						[ 'code' => 'copyright', 'text' => self::text( $copyright ),
							'module' => 'message', $C => 'text' ],
						[ 'code' => 'overriddenCode', 'text' => self::text( $disclaimers ),
							'data' => $overriddenData, 'module' => 'messageWithData', $C => 'text' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'aboutpage', 'text' => self::text( $aboutpage ),
							'module' => 'status', $C => 'text' ],
						[ 'code' => 'brackets', 'text' => self::text( $brackets ), 'module' => 'status', $C => 'text' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'text' => self::text( $mainpage ), 'module' => 'status', $C => 'text' ],
						[ 'code' => 'parentheses', 'text' => self::text( $parens ),
							'module' => 'status', $C => 'text' ],
						[ 'code' => 'copyright', 'text' => self::text( $copyright ),
							'module' => 'status', $C => 'text' ],
						[ 'code' => 'overriddenCode', 'text' => self::text( $disclaimers ),
							'data' => $overriddenData, 'module' => 'status', $C => 'text' ],
						$I => 'warning',
					],
				],
			],
			'one' => [ 'plaintext' ] + $tmp, // For these messages, plaintext and wikitext are the same
			'two' => [ 'html', 'en', true,
				[
					'errors' => [
						[ 'code' => 'aboutpage', 'html' => self::html( $aboutpage ), 'module' => 'err', $C => 'html' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'html' => self::html( $mainpage ), 'module' => 'string', $C => 'html' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'overriddenCode', 'html' => self::html( $edithelp ),
							'data' => $overriddenData, 'module' => 'errWithData', $C => 'html' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'html' => self::html( $mainpage ), 'module' => 'foo', $C => 'html' ],
						[ 'code' => 'parentheses', 'html' => self::html( $parens ), 'module' => 'foo', $C => 'html' ],
						[ 'code' => 'copyright', 'html' => self::html( $copyright ),
							'module' => 'message', $C => 'html' ],
						[ 'code' => 'overriddenCode', 'html' => self::html( $disclaimers ),
							'data' => $overriddenData, 'module' => 'messageWithData', $C => 'html' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'aboutpage', 'html' => self::html( $aboutpage ),
							'module' => 'status', $C => 'html' ],
						[ 'code' => 'brackets', 'html' => self::html( $brackets ), 'module' => 'status', $C => 'html' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'html' => self::html( $mainpage ), 'module' => 'status', $C => 'html' ],
						[ 'code' => 'parentheses', 'html' => self::html( $parens ),
							'module' => 'status', $C => 'html' ],
						[ 'code' => 'copyright', 'html' => self::html( $copyright ),
							'module' => 'status', $C => 'html' ],
						[ 'code' => 'overriddenCode', 'html' => self::html( $disclaimers ),
							'data' => $overriddenData, 'module' => 'status', $C => 'html' ],
						$I => 'warning',
					],
				],
			],
			'three' => [ 'raw', 'fr', true,
				[
					'errors' => [
						[
							'code' => 'aboutpage',
							'key' => 'aboutpage',
							'params' => [ $I => 'param' ],
							'module' => 'err',
						],
						$I => 'error',
					],
					'warnings' => [
						[
							'code' => 'mainpage',
							'key' => 'mainpage',
							'params' => [ $I => 'param' ],
							'module' => 'string',
						],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[
							'code' => 'overriddenCode',
							'key' => 'edithelp',
							'params' => [ $I => 'param' ],
							'data' => $overriddenData,
							'module' => 'errWithData',
						],
						$I => 'error',
					],
					'warnings' => [
						[
							'code' => 'mainpage',
							'key' => 'mainpage',
							'params' => [ $I => 'param' ],
							'module' => 'foo',
						],
						[
							'code' => 'parentheses',
							'key' => 'parentheses',
							'params' => [ 'foobar', $I => 'param' ],
							'module' => 'foo',
						],
						[
							'code' => 'copyright',
							'key' => 'copyright',
							'params' => [ $I => 'param' ],
							'module' => 'message',
						],
						[
							'code' => 'overriddenCode',
							'key' => 'disclaimers',
							'params' => [ $I => 'param' ],
							'data' => $overriddenData,
							'module' => 'messageWithData',
						],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[
							'code' => 'aboutpage',
							'key' => 'aboutpage',
							'params' => [ $I => 'param' ],
							'module' => 'status',
						],
						[
							'code' => 'brackets',
							'key' => 'brackets',
							'params' => [ [ 'size' => 123 ], $I => 'param' ],
							'module' => 'status',
						],
						$I => 'error',
					],
					'warnings' => [
						[
							'code' => 'mainpage',
							'key' => 'mainpage',
							'params' => [ $I => 'param' ],
							'module' => 'status',
						],
						[
							'code' => 'parentheses',
							'key' => 'parentheses',
							'params' => [ 'foobar', $I => 'param' ],
							'module' => 'status',
						],
						[
							'code' => 'copyright',
							'key' => 'copyright',
							'params' => [ $I => 'param' ],
							'module' => 'status',
						],
						[
							'code' => 'overriddenCode',
							'key' => 'disclaimers',
							'params' => [ $I => 'param' ],
							'data' => $overriddenData,
							'module' => 'status',
						],
						$I => 'warning',
					],
				],
			],
			'four' => [ 'none', 'fr', true,
				[
					'errors' => [
						[ 'code' => 'aboutpage', 'module' => 'err' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'module' => 'string' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'overriddenCode', 'data' => $overriddenData,
							'module' => 'errWithData' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'module' => 'foo' ],
						[ 'code' => 'parentheses', 'module' => 'foo' ],
						[ 'code' => 'copyright', 'module' => 'message' ],
						[ 'code' => 'overriddenCode', 'data' => $overriddenData,
							'module' => 'messageWithData' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'aboutpage', 'module' => 'status' ],
						[ 'code' => 'brackets', 'module' => 'status' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'module' => 'status' ],
						[ 'code' => 'parentheses', 'module' => 'status' ],
						[ 'code' => 'copyright', 'module' => 'status' ],
						[ 'code' => 'overriddenCode', 'data' => $overriddenData, 'module' => 'status' ],
						$I => 'warning',
					],
				],
			],
		];
	}

	/**
	 * @covers \MediaWiki\Api\ApiErrorFormatter_BackCompat
	 */
	public function testErrorFormatterBC() {
		$aboutpage = wfMessage( 'aboutpage' );
		$mainpage = wfMessage( 'mainpage' );
		$parens = wfMessage( 'parentheses', 'foobar' );
		$copyright = wfMessage( 'copyright' );
		$disclaimers = wfMessage( 'disclaimers' );
		$edithelp = wfMessage( 'edithelp' );

		$result = new ApiResult( 8_388_608 );
		$formatter = new ApiErrorFormatter_BackCompat( $result );

		$this->assertSame( 'en', $formatter->getLanguage()->getCode() );
		$this->assertSame( 'bc', $formatter->getFormat() );

		$this->assertSame( [], $formatter->arrayFromStatus( Status::newGood() ) );

		$formatter->addWarning( 'string', 'mainpage' );
		$formatter->addWarning( 'raw',
			new RawMessage( 'Blah <kbd>kbd</kbd> <b>&lt;X&gt;</b> &#x1f61e;' )
		);
		$formatter->addError( 'err', 'aboutpage' );
		$this->assertSame( [
			'error' => [
				'code' => 'aboutpage',
				'info' => $aboutpage->useDatabase( false )->plain(),
			],
			'warnings' => [
				'raw' => [
					'warnings' => 'Blah "kbd" <X> 😞',
					ApiResult::META_CONTENT => 'warnings',
				],
				'string' => [
					'warnings' => $mainpage->useDatabase( false )->plain(),
					ApiResult::META_CONTENT => 'warnings',
				],
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData(), 'Simple test' );

		$result->reset();
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'xxx+foo', [ 'parentheses', 'foobar' ] );
		$msg1 = wfMessage( 'copyright' );
		$formatter->addWarning( 'message', $msg1 );
		$msg2 = new ApiMessage( 'disclaimers', 'overriddenCode', [ 'overriddenData' => true ] );
		$formatter->addWarning( 'messageWithData', $msg2 );
		$msg3 = new ApiMessage( 'edithelp', 'overriddenCode', [ 'overriddenData' => true ] );
		$formatter->addError( 'errWithData', $msg3 );
		$formatter->addWarning( null, 'mainpage' );
		$this->assertSame( [
			'error' => [
				'code' => 'overriddenCode',
				'info' => $edithelp->useDatabase( false )->plain(),
				'overriddenData' => true,
			],
			'warnings' => [
				'unknown' => [
					'warnings' => $mainpage->useDatabase( false )->plain(),
					ApiResult::META_CONTENT => 'warnings',
				],
				'messageWithData' => [
					'warnings' => $disclaimers->useDatabase( false )->plain(),
					ApiResult::META_CONTENT => 'warnings',
				],
				'message' => [
					'warnings' => $copyright->useDatabase( false )->plain(),
					ApiResult::META_CONTENT => 'warnings',
				],
				'foo' => [
					'warnings' => $mainpage->useDatabase( false )->plain()
						. "\n" . $parens->useDatabase( false )->plain(),
					ApiResult::META_CONTENT => 'warnings',
				],
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData(), 'Complex test' );

		$this->assertSame(
			[
				'code' => 'copyright',
				'info' => $copyright->useDatabase( false )->plain(),
			],
			$formatter->formatMessage( $msg1 )
		);
		$this->assertSame(
			[
				'code' => 'overriddenCode',
				'info' => $disclaimers->useDatabase( false )->plain(),
				'overriddenData' => true,
			],
			$formatter->formatMessage( $msg2 )
		);
		$this->assertSame(
			[
				'code' => 'overriddenCode',
				'info' => $edithelp->useDatabase( false )->plain(),
				'overriddenData' => true,
			],
			$formatter->formatMessage( $msg3 )
		);

		$result->reset();
		$status = Status::newGood();
		$status->warning( 'mainpage' );
		$status->warning( 'parentheses', 'foobar' );
		$status->warning( $msg1 );
		$status->warning( $msg2 );
		$status->error( 'aboutpage' );
		$status->error( 'brackets', Message::sizeParam( 123 ) );
		$formatter->addMessagesFromStatus( 'status', $status );
		$this->assertSame( [
			'error' => [
				'code' => 'aboutpage',
				'info' => $aboutpage->useDatabase( false )->plain(),
			],
			'warnings' => [
				'status' => [
					'warnings' => $mainpage->useDatabase( false )->plain()
						. "\n" . $parens->useDatabase( false )->plain()
						. "\n" . $copyright->useDatabase( false )->plain()
						. "\n" . $disclaimers->useDatabase( false )->plain(),
					ApiResult::META_CONTENT => 'warnings',
				],
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData(), 'Status test' );

		$I = ApiResult::META_INDEXED_TAG_NAME;
		$this->assertEquals(
			[
				[
					'message' => 'aboutpage',
					'params' => [ $I => 'param' ],
					'code' => 'aboutpage',
					'type' => 'error',
				],
				[
					'message' => 'brackets',
					'params' => [ Message::sizeParam( 123 ), $I => 'param' ],
					'code' => 'brackets',
					'type' => 'error',
				],
				$I => 'error',
			],
			$formatter->arrayFromStatus( $status, 'error' ),
			'arrayFromStatus test for error'
		);
		$this->assertSame(
			[
				[
					'message' => 'mainpage',
					'params' => [ $I => 'param' ],
					'code' => 'mainpage',
					'type' => 'warning',
				],
				[
					'message' => 'parentheses',
					'params' => [ 'foobar', $I => 'param' ],
					'code' => 'parentheses',
					'type' => 'warning',
				],
				[
					'message' => 'copyright',
					'params' => [ $I => 'param' ],
					'code' => 'copyright',
					'type' => 'warning',
				],
				[
					'message' => 'disclaimers',
					'params' => [ $I => 'param' ],
					'code' => 'overriddenCode',
					'type' => 'warning',
				],
				$I => 'warning',
			],
			$formatter->arrayFromStatus( $status, 'warning' ),
			'arrayFromStatus test for warning'
		);

		$result->reset();
		$result->addValue( null, 'error', [ 'bogus' ] );
		$formatter->addError( 'err', 'aboutpage' );
		$this->assertSame( [
			'error' => [
				'code' => 'aboutpage',
				'info' => $aboutpage->useDatabase( false )->plain(),
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData(), 'Overwrites bogus "error" value with real error' );
	}

	/**
	 * @dataProvider provideGetMessageFromException
	 * @covers \MediaWiki\Api\ApiErrorFormatter::getMessageFromException
	 * @covers \MediaWiki\Api\ApiErrorFormatter::formatException
	 * @param Exception $exception
	 * @param array $options
	 * @param array $expect
	 */
	public function testGetMessageFromException( $exception, $options, $expect ) {
		$result = new ApiResult( 8_388_608 );
		$formatter = new ApiErrorFormatter( $result,
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' ), 'html',
			false );

		$msg = $formatter->getMessageFromException( $exception, $options );
		$this->assertInstanceOf( Message::class, $msg );
		$this->assertInstanceOf( IApiMessage::class, $msg );
		$this->assertSame( $expect, [
			'text' => $msg->parse(),
			'code' => $msg->getApiCode(),
			'data' => $msg->getApiData(),
		] );

		$expectFormatted = $formatter->formatMessage( $msg );
		$formatted = $formatter->formatException( $exception, $options );
		$this->assertSame( $expectFormatted, $formatted );
	}

	/**
	 * @dataProvider provideGetMessageFromException
	 * @covers \MediaWiki\Api\ApiErrorFormatter_BackCompat::formatException
	 * @param Exception $exception
	 * @param array $options
	 * @param array $expect
	 */
	public function testGetMessageFromException_BC( $exception, $options, $expect ) {
		$result = new ApiResult( 8_388_608 );
		$formatter = new ApiErrorFormatter_BackCompat( $result );

		$msg = $formatter->getMessageFromException( $exception, $options );
		$this->assertInstanceOf( Message::class, $msg );
		$this->assertInstanceOf( IApiMessage::class, $msg );
		$this->assertSame( $expect, [
			'text' => $msg->parse(),
			'code' => $msg->getApiCode(),
			'data' => $msg->getApiData(),
		] );

		$expectFormatted = $formatter->formatMessage( $msg );
		$formatted = $formatter->formatException( $exception, $options );
		$this->assertSame( $expectFormatted, $formatted );
		$formatted = $formatter->formatException( $exception, $options + [ 'bc' => true ] );
		$this->assertSame( $expectFormatted['info'], $formatted );
	}

	public static function provideGetMessageFromException() {
		return [
			'Normal exception' => [
				new RuntimeException( '<b>Something broke!</b>' ),
				[],
				[
					'text' => '&#60;b&#62;Something broke!&#60;/b&#62;',
					'code' => 'internal_api_error_RuntimeException',
					'data' => [
						'errorclass' => 'RuntimeException',
					],
				]
			],
			'Normal exception, wrapped' => [
				new RuntimeException( '<b>Something broke!</b>' ),
				[ 'wrap' => 'parentheses', 'code' => 'some-code', 'data' => [ 'foo' => 'bar', 'baz' => 42 ] ],
				[
					'text' => '(&#60;b&#62;Something broke!&#60;/b&#62;)',
					'code' => 'some-code',
					'data' => [ 'foo' => 'bar', 'baz' => 42 ],
				]
			],
			'LocalizedException' => [
				new LocalizedException( [ 'returnto', '<b>FooBar</b>' ] ),
				[],
				[
					'text' => 'Return to <b>FooBar</b>.',
					'code' => 'returnto',
					'data' => [],
				]
			],
			'LocalizedException, wrapped' => [
				new LocalizedException( [ 'returnto', '<b>FooBar</b>' ] ),
				[ 'wrap' => 'parentheses', 'code' => 'some-code', 'data' => [ 'foo' => 'bar', 'baz' => 42 ] ],
				[
					'text' => 'Return to <b>FooBar</b>.',
					'code' => 'some-code',
					'data' => [ 'foo' => 'bar', 'baz' => 42 ],
				]
			],
		];
	}

	/**
	 * @covers \MediaWiki\Api\ApiErrorFormatter::addMessagesFromStatus
	 * @covers \MediaWiki\Api\ApiErrorFormatter::addWarningOrError
	 * @covers \MediaWiki\Api\ApiErrorFormatter::formatMessageInternal
	 */
	public function testAddMessagesFromStatus_filter() {
		$result = new ApiResult( 8_388_608 );
		$formatter = new ApiErrorFormatter( $result,
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'qqx' ),
			'plaintext', false );

		$status = Status::newGood();
		$status->warning( 'mainpage' );
		$status->warning( 'parentheses', 'foobar' );
		$status->warning( wfMessage( 'mainpage' ) );
		$status->error( 'mainpage' );
		$status->error( 'parentheses', 'foobaz' );
		$formatter->addMessagesFromStatus( 'status', $status, [ 'warning', 'error' ], [ 'mainpage' ] );
		$this->assertSame( [
			'errors' => [
				[
					'code' => 'parentheses',
					'text' => '(parentheses: foobaz)',
					'module' => 'status',
					ApiResult::META_CONTENT => 'text',
				],
				ApiResult::META_INDEXED_TAG_NAME => 'error',
			],
			'warnings' => [
				[
					'code' => 'parentheses',
					'text' => '(parentheses: foobar)',
					'module' => 'status',
					ApiResult::META_CONTENT => 'text',
				],
				ApiResult::META_INDEXED_TAG_NAME => 'warning',
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData() );
	}

	/**
	 * @dataProvider provideIsValidApiCode
	 * @covers \MediaWiki\Api\ApiErrorFormatter::isValidApiCode
	 * @param string $code
	 * @param bool $expect
	 */
	public function testIsValidApiCode( $code, $expect ) {
		$this->assertSame( $expect, ApiErrorFormatter::isValidApiCode( $code ) );
	}

	public static function provideIsValidApiCode() {
		return [
			[ 'foo-bar_Baz123', true ],
			[ 'foo bar', false ],
			[ 'foo\\bar', false ],
			[ 'internal_api_error_foo\\bar baz', true ],
		];
	}

}
