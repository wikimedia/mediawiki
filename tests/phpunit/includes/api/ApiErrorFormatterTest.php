<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 */
class ApiErrorFormatterTest extends MediaWikiLangTestCase {

	/**
	 * @covers ApiErrorFormatter
	 */
	public function testErrorFormatterBasics() {
		$result = new ApiResult( 8388608 );
		$formatter = new ApiErrorFormatter( $result,
			MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'de' ), 'wikitext',
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
	 * @covers ApiErrorFormatter
	 * @covers ApiErrorFormatter_BackCompat
	 */
	public function testNewWithFormat() {
		$result = new ApiResult( 8388608 );
		$formatter = new ApiErrorFormatter( $result,
			MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'de' ), 'wikitext',
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
	 * @covers ApiErrorFormatter
	 * @dataProvider provideErrorFormatter
	 */
	public function testErrorFormatter( $format, $lang, $useDB,
		$expect1, $expect2, $expect3
	) {
		$result = new ApiResult( 8388608 );
		$formatter = new ApiErrorFormatter( $result,
			MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( $lang ), $format,
			$useDB );

		// Add default type
		$expect1[ApiResult::META_TYPE] = 'assoc';
		$expect2[ApiResult::META_TYPE] = 'assoc';
		$expect3[ApiResult::META_TYPE] = 'assoc';

		$formatter->addWarning( 'string', 'mainpage' );
		$formatter->addError( 'err', 'mainpage' );
		$this->assertEquals( $expect1, $result->getResultData(), 'Simple test' );

		$result->reset();
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'foo', [ 'parentheses', 'foobar' ] );
		$msg1 = wfMessage( 'mainpage' );
		$formatter->addWarning( 'message', $msg1 );
		$msg2 = new ApiMessage( 'mainpage', 'overriddenCode', [ 'overriddenData' => true ] );
		$formatter->addWarning( 'messageWithData', $msg2 );
		$formatter->addError( 'errWithData', $msg2 );
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
		$status->error( 'mainpage' );
		$status->error( 'parentheses', 'foobar' );
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

	public static function provideErrorFormatter() {
		$mainpageText = wfMessage( 'mainpage' )->inLanguage( 'de' )->useDatabase( false )->text();
		$parensText = wfMessage( 'parentheses', 'foobar' )->inLanguage( 'de' )
			->useDatabase( false )->text();
		$mainpageHTML = wfMessage( 'mainpage' )->inLanguage( 'en' )->parse();
		$parensHTML = wfMessage( 'parentheses', 'foobar' )->inLanguage( 'en' )->parse();
		$C = ApiResult::META_CONTENT;
		$I = ApiResult::META_INDEXED_TAG_NAME;
		$overriddenData = [ 'overriddenData' => true, ApiResult::META_TYPE => 'assoc' ];

		return [
			$tmp = [ 'wikitext', 'de', false,
				[
					'errors' => [
						[ 'code' => 'mainpage', 'text' => $mainpageText, 'module' => 'err', $C => 'text' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'text' => $mainpageText, 'module' => 'string', $C => 'text' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'overriddenCode', 'text' => $mainpageText,
							'data' => $overriddenData, 'module' => 'errWithData', $C => 'text' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'text' => $mainpageText, 'module' => 'foo', $C => 'text' ],
						[ 'code' => 'parentheses', 'text' => $parensText, 'module' => 'foo', $C => 'text' ],
						[ 'code' => 'mainpage', 'text' => $mainpageText, 'module' => 'message', $C => 'text' ],
						[ 'code' => 'overriddenCode', 'text' => $mainpageText,
							'data' => $overriddenData, 'module' => 'messageWithData', $C => 'text' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'mainpage', 'text' => $mainpageText, 'module' => 'status', $C => 'text' ],
						[ 'code' => 'parentheses', 'text' => $parensText, 'module' => 'status', $C => 'text' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'text' => $mainpageText, 'module' => 'status', $C => 'text' ],
						[ 'code' => 'parentheses', 'text' => $parensText, 'module' => 'status', $C => 'text' ],
						[ 'code' => 'overriddenCode', 'text' => $mainpageText,
							'data' => $overriddenData, 'module' => 'status', $C => 'text' ],
						$I => 'warning',
					],
				],
			],
			[ 'plaintext' ] + $tmp, // For these messages, plaintext and wikitext are the same
			[ 'html', 'en', true,
				[
					'errors' => [
						[ 'code' => 'mainpage', 'html' => $mainpageHTML, 'module' => 'err', $C => 'html' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'html' => $mainpageHTML, 'module' => 'string', $C => 'html' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'overriddenCode', 'html' => $mainpageHTML,
							'data' => $overriddenData, 'module' => 'errWithData', $C => 'html' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'html' => $mainpageHTML, 'module' => 'foo', $C => 'html' ],
						[ 'code' => 'parentheses', 'html' => $parensHTML, 'module' => 'foo', $C => 'html' ],
						[ 'code' => 'mainpage', 'html' => $mainpageHTML, 'module' => 'message', $C => 'html' ],
						[ 'code' => 'overriddenCode', 'html' => $mainpageHTML,
							'data' => $overriddenData, 'module' => 'messageWithData', $C => 'html' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'mainpage', 'html' => $mainpageHTML, 'module' => 'status', $C => 'html' ],
						[ 'code' => 'parentheses', 'html' => $parensHTML, 'module' => 'status', $C => 'html' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'html' => $mainpageHTML, 'module' => 'status', $C => 'html' ],
						[ 'code' => 'parentheses', 'html' => $parensHTML, 'module' => 'status', $C => 'html' ],
						[ 'code' => 'overriddenCode', 'html' => $mainpageHTML,
							'data' => $overriddenData, 'module' => 'status', $C => 'html' ],
						$I => 'warning',
					],
				],
			],
			[ 'raw', 'fr', true,
				[
					'errors' => [
						[
							'code' => 'mainpage',
							'key' => 'mainpage',
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
							'key' => 'mainpage',
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
							'code' => 'mainpage',
							'key' => 'mainpage',
							'params' => [ $I => 'param' ],
							'module' => 'message',
						],
						[
							'code' => 'overriddenCode',
							'key' => 'mainpage',
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
							'code' => 'overriddenCode',
							'key' => 'mainpage',
							'params' => [ $I => 'param' ],
							'data' => $overriddenData,
							'module' => 'status',
						],
						$I => 'warning',
					],
				],
			],
			[ 'none', 'fr', true,
				[
					'errors' => [
						[ 'code' => 'mainpage', 'module' => 'err' ],
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
						[ 'code' => 'mainpage', 'module' => 'message' ],
						[ 'code' => 'overriddenCode', 'data' => $overriddenData,
							'module' => 'messageWithData' ],
						$I => 'warning',
					],
				],
				[
					'errors' => [
						[ 'code' => 'mainpage', 'module' => 'status' ],
						[ 'code' => 'parentheses', 'module' => 'status' ],
						$I => 'error',
					],
					'warnings' => [
						[ 'code' => 'mainpage', 'module' => 'status' ],
						[ 'code' => 'parentheses', 'module' => 'status' ],
						[ 'code' => 'overriddenCode', 'data' => $overriddenData, 'module' => 'status' ],
						$I => 'warning',
					],
				],
			],
		];
	}

	/**
	 * @covers ApiErrorFormatter_BackCompat
	 */
	public function testErrorFormatterBC() {
		$mainpagePlain = wfMessage( 'mainpage' )->useDatabase( false )->plain();
		$parensPlain = wfMessage( 'parentheses', 'foobar' )->useDatabase( false )->plain();

		$result = new ApiResult( 8388608 );
		$formatter = new ApiErrorFormatter_BackCompat( $result );

		$this->assertSame( 'en', $formatter->getLanguage()->getCode() );
		$this->assertSame( 'bc', $formatter->getFormat() );

		$this->assertSame( [], $formatter->arrayFromStatus( Status::newGood() ) );

		$formatter->addWarning( 'string', 'mainpage' );
		$formatter->addWarning( 'raw',
			new RawMessage( 'Blah <kbd>kbd</kbd> <b>&lt;X&gt;</b> &#x1f61e;' )
		);
		$formatter->addError( 'err', 'mainpage' );
		$this->assertSame( [
			'error' => [
				'code' => 'mainpage',
				'info' => $mainpagePlain,
			],
			'warnings' => [
				'raw' => [
					'warnings' => 'Blah "kbd" <X> 😞',
					ApiResult::META_CONTENT => 'warnings',
				],
				'string' => [
					'warnings' => $mainpagePlain,
					ApiResult::META_CONTENT => 'warnings',
				],
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData(), 'Simple test' );

		$result->reset();
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'xxx+foo', [ 'parentheses', 'foobar' ] );
		$msg1 = wfMessage( 'mainpage' );
		$formatter->addWarning( 'message', $msg1 );
		$msg2 = new ApiMessage( 'mainpage', 'overriddenCode', [ 'overriddenData' => true ] );
		$formatter->addWarning( 'messageWithData', $msg2 );
		$formatter->addError( 'errWithData', $msg2 );
		$formatter->addWarning( null, 'mainpage' );
		$this->assertSame( [
			'error' => [
				'code' => 'overriddenCode',
				'info' => $mainpagePlain,
				'overriddenData' => true,
			],
			'warnings' => [
				'unknown' => [
					'warnings' => $mainpagePlain,
					ApiResult::META_CONTENT => 'warnings',
				],
				'messageWithData' => [
					'warnings' => $mainpagePlain,
					ApiResult::META_CONTENT => 'warnings',
				],
				'message' => [
					'warnings' => $mainpagePlain,
					ApiResult::META_CONTENT => 'warnings',
				],
				'foo' => [
					'warnings' => "$mainpagePlain\n$parensPlain",
					ApiResult::META_CONTENT => 'warnings',
				],
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData(), 'Complex test' );

		$this->assertSame(
			[
				'code' => 'mainpage',
				'info' => 'Main Page',
			],
			$formatter->formatMessage( $msg1 )
		);
		$this->assertSame(
			[
				'code' => 'overriddenCode',
				'info' => 'Main Page',
				'overriddenData' => true,
			],
			$formatter->formatMessage( $msg2 )
		);

		$result->reset();
		$status = Status::newGood();
		$status->warning( 'mainpage' );
		$status->warning( 'parentheses', 'foobar' );
		$status->warning( $msg1 );
		$status->warning( $msg2 );
		$status->error( 'mainpage' );
		$status->error( 'parentheses', 'foobar' );
		$formatter->addMessagesFromStatus( 'status', $status );
		$this->assertSame( [
			'error' => [
				'code' => 'mainpage',
				'info' => $mainpagePlain,
			],
			'warnings' => [
				'status' => [
					'warnings' => "$mainpagePlain\n$parensPlain",
					ApiResult::META_CONTENT => 'warnings',
				],
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData(), 'Status test' );

		$I = ApiResult::META_INDEXED_TAG_NAME;
		$this->assertSame(
			[
				[
					'message' => 'mainpage',
					'params' => [ $I => 'param' ],
					'code' => 'mainpage',
					'type' => 'error',
				],
				[
					'message' => 'parentheses',
					'params' => [ 'foobar', $I => 'param' ],
					'code' => 'parentheses',
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
					'message' => 'mainpage',
					'params' => [ $I => 'param' ],
					'code' => 'mainpage',
					'type' => 'warning',
				],
				[
					'message' => 'mainpage',
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
		$formatter->addError( 'err', 'mainpage' );
		$this->assertSame( [
			'error' => [
				'code' => 'mainpage',
				'info' => $mainpagePlain,
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData(), 'Overwrites bogus "error" value with real error' );
	}

	/**
	 * @dataProvider provideGetMessageFromException
	 * @covers ApiErrorFormatter::getMessageFromException
	 * @covers ApiErrorFormatter::formatException
	 * @param Exception $exception
	 * @param array $options
	 * @param array $expect
	 */
	public function testGetMessageFromException( $exception, $options, $expect ) {
		$result = new ApiResult( 8388608 );
		$formatter = new ApiErrorFormatter( $result,
			MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' ), 'html',
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
	 * @covers ApiErrorFormatter_BackCompat::formatException
	 * @param Exception $exception
	 * @param array $options
	 * @param array $expect
	 */
	public function testGetMessageFromException_BC( $exception, $options, $expect ) {
		$result = new ApiResult( 8388608 );
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
	 * @covers ApiErrorFormatter::addMessagesFromStatus
	 * @covers ApiErrorFormatter::addWarningOrError
	 * @covers ApiErrorFormatter::formatMessageInternal
	 */
	public function testAddMessagesFromStatus_filter() {
		$result = new ApiResult( 8388608 );
		$formatter = new ApiErrorFormatter( $result,
			MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'qqx' ),
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
	 * @covers ApiErrorFormatter::isValidApiCode
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
