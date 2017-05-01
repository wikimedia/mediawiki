<?php

/**
 * @group API
 */
class ApiErrorFormatterTest extends MediaWikiLangTestCase {

	/**
	 * @covers ApiErrorFormatter
	 * @dataProvider provideErrorFormatter
	 */
	public function testErrorFormatter( $format, $lang, $useDB,
		$expect1, $expect2, $expect3
	) {
		$result = new ApiResult( 8388608 );
		$formatter = new ApiErrorFormatter( $result, Language::factory( $lang ), $format, $useDB );

		// Add default type
		$expect1[ApiResult::META_TYPE] = 'assoc';
		$expect2[ApiResult::META_TYPE] = 'assoc';
		$expect3[ApiResult::META_TYPE] = 'assoc';

		$formatter->addWarning( 'string', 'mainpage' );
		$formatter->addError( 'err', 'mainpage' );
		$this->assertSame( $expect1, $result->getResultData(), 'Simple test' );

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
			$expect3['errors']['status'],
			$formatter->arrayFromStatus( $status, 'error' ),
			'arrayFromStatus test for error'
		);
		$this->assertSame(
			$expect3['warnings']['status'],
			$formatter->arrayFromStatus( $status, 'warning' ),
			'arrayFromStatus test for warning'
		);
	}

	public static function provideErrorFormatter() {
		$mainpagePlain = wfMessage( 'mainpage' )->useDatabase( false )->plain();
		$parensPlain = wfMessage( 'parentheses', 'foobar' )->useDatabase( false )->plain();
		$mainpageText = wfMessage( 'mainpage' )->inLanguage( 'de' )->text();
		$parensText = wfMessage( 'parentheses', 'foobar' )->inLanguage( 'de' )->text();
		$C = ApiResult::META_CONTENT;
		$I = ApiResult::META_INDEXED_TAG_NAME;

		return [
			[ 'wikitext', 'de', true,
				[
					'errors' => [
						'err' => [
							[ 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ],
							$I => 'error',
						],
					],
					'warnings' => [
						'string' => [
							[ 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ],
							$I => 'warning',
						],
					],
				],
				[
					'errors' => [
						'errWithData' => [
							[ 'code' => 'overriddenCode', 'text' => $mainpageText,
								'overriddenData' => true, $C => 'text' ],
							$I => 'error',
						],
					],
					'warnings' => [
						'messageWithData' => [
							[ 'code' => 'overriddenCode', 'text' => $mainpageText,
								'overriddenData' => true, $C => 'text' ],
							$I => 'warning',
						],
						'message' => [
							[ 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ],
							$I => 'warning',
						],
						'foo' => [
							[ 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ],
							[ 'code' => 'parentheses', 'text' => $parensText, $C => 'text' ],
							$I => 'warning',
						],
					],
				],
				[
					'errors' => [
						'status' => [
							[ 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ],
							[ 'code' => 'parentheses', 'text' => $parensText, $C => 'text' ],
							$I => 'error',
						],
					],
					'warnings' => [
						'status' => [
							[ 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ],
							[ 'code' => 'parentheses', 'text' => $parensText, $C => 'text' ],
							[ 'code' => 'overriddenCode', 'text' => $mainpageText,
								'overriddenData' => true, $C => 'text' ],
							$I => 'warning',
						],
					],
				],
			],
			[ 'raw', 'fr', true,
				[
					'errors' => [
						'err' => [
							[
								'code' => 'mainpage',
								'key' => 'mainpage',
								'params' => [ $I => 'param' ]
							],
							$I => 'error',
						],
					],
					'warnings' => [
						'string' => [
							[
								'code' => 'mainpage',
								'key' => 'mainpage',
								'params' => [ $I => 'param' ]
							],
							$I => 'warning',
						],
					],
				],
				[
					'errors' => [
						'errWithData' => [
							[
								'code' => 'overriddenCode',
								'key' => 'mainpage',
								'params' => [ $I => 'param' ],
								'overriddenData' => true
							],
							$I => 'error',
						],
					],
					'warnings' => [
						'messageWithData' => [
							[
								'code' => 'overriddenCode',
								'key' => 'mainpage',
								'params' => [ $I => 'param' ],
								'overriddenData' => true
							],
							$I => 'warning',
						],
						'message' => [
							[
								'code' => 'mainpage',
								'key' => 'mainpage',
								'params' => [ $I => 'param' ]
							],
							$I => 'warning',
						],
						'foo' => [
							[
								'code' => 'mainpage',
								'key' => 'mainpage',
								'params' => [ $I => 'param' ]
							],
							[
								'code' => 'parentheses',
								'key' => 'parentheses',
								'params' => [ 'foobar', $I => 'param' ]
							],
							$I => 'warning',
						],
					],
				],
				[
					'errors' => [
						'status' => [
							[
								'code' => 'mainpage',
								'key' => 'mainpage',
								'params' => [ $I => 'param' ]
							],
							[
								'code' => 'parentheses',
								'key' => 'parentheses',
								'params' => [ 'foobar', $I => 'param' ]
							],
							$I => 'error',
						],
					],
					'warnings' => [
						'status' => [
							[
								'code' => 'mainpage',
								'key' => 'mainpage',
								'params' => [ $I => 'param' ]
							],
							[
								'code' => 'parentheses',
								'key' => 'parentheses',
								'params' => [ 'foobar', $I => 'param' ]
							],
							[
								'code' => 'overriddenCode',
								'key' => 'mainpage',
								'params' => [ $I => 'param' ],
								'overriddenData' => true
							],
							$I => 'warning',
						],
					],
				],
			],
			[ 'none', 'fr', true,
				[
					'errors' => [
						'err' => [
							[ 'code' => 'mainpage' ],
							$I => 'error',
						],
					],
					'warnings' => [
						'string' => [
							[ 'code' => 'mainpage' ],
							$I => 'warning',
						],
					],
				],
				[
					'errors' => [
						'errWithData' => [
							[ 'code' => 'overriddenCode', 'overriddenData' => true ],
							$I => 'error',
						],
					],
					'warnings' => [
						'messageWithData' => [
							[ 'code' => 'overriddenCode', 'overriddenData' => true ],
							$I => 'warning',
						],
						'message' => [
							[ 'code' => 'mainpage' ],
							$I => 'warning',
						],
						'foo' => [
							[ 'code' => 'mainpage' ],
							[ 'code' => 'parentheses' ],
							$I => 'warning',
						],
					],
				],
				[
					'errors' => [
						'status' => [
							[ 'code' => 'mainpage' ],
							[ 'code' => 'parentheses' ],
							$I => 'error',
						],
					],
					'warnings' => [
						'status' => [
							[ 'code' => 'mainpage' ],
							[ 'code' => 'parentheses' ],
							[ 'code' => 'overriddenCode', 'overriddenData' => true ],
							$I => 'warning',
						],
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

		$formatter->addWarning( 'string', 'mainpage' );
		$formatter->addError( 'err', 'mainpage' );
		$this->assertSame( [
			'error' => [
				'code' => 'mainpage',
				'info' => $mainpagePlain,
			],
			'warnings' => [
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
		$formatter->addWarning( 'foo', [ 'parentheses', 'foobar' ] );
		$msg1 = wfMessage( 'mainpage' );
		$formatter->addWarning( 'message', $msg1 );
		$msg2 = new ApiMessage( 'mainpage', 'overriddenCode', [ 'overriddenData' => true ] );
		$formatter->addWarning( 'messageWithData', $msg2 );
		$formatter->addError( 'errWithData', $msg2 );
		$this->assertSame( [
			'error' => [
				'code' => 'overriddenCode',
				'info' => $mainpagePlain,
				'overriddenData' => true,
			],
			'warnings' => [
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
				'code' => 'parentheses',
				'info' => $parensPlain,
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
					'type' => 'error',
					'message' => 'mainpage',
					'params' => [ $I => 'param' ]
				],
				[
					'type' => 'error',
					'message' => 'parentheses',
					'params' => [ 'foobar', $I => 'param' ]
				],
				$I => 'error',
			],
			$formatter->arrayFromStatus( $status, 'error' ),
			'arrayFromStatus test for error'
		);
		$this->assertSame(
			[
				[
					'type' => 'warning',
					'message' => 'mainpage',
					'params' => [ $I => 'param' ]
				],
				[
					'type' => 'warning',
					'message' => 'parentheses',
					'params' => [ 'foobar', $I => 'param' ]
				],
				[
					'message' => 'mainpage',
					'params' => [ $I => 'param' ],
					'type' => 'warning'
				],
				[
					'message' => 'mainpage',
					'params' => [ $I => 'param' ],
					'type' => 'warning'
				],
				$I => 'warning',
			],
			$formatter->arrayFromStatus( $status, 'warning' ),
			'arrayFromStatus test for warning'
		);
	}

}
