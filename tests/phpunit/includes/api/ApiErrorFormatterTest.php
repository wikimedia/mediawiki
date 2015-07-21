<?php

/**
 * @group API
 */
class ApiErrorFormatterTest extends MediaWikiTestCase {

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
		$formatter->addWarning( 'foo', array( 'parentheses', 'foobar' ) );
		$msg1 = wfMessage( 'mainpage' );
		$formatter->addWarning( 'message', $msg1 );
		$msg2 = new ApiMessage( 'mainpage', 'overriddenCode', array( 'overriddenData' => true ) );
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

		return array(
			array( 'wikitext', 'de', true,
				array(
					'errors' => array(
						'err' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
							$I => 'error',
						),
					),
					'warnings' => array(
						'string' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
							$I => 'warning',
						),
					),
				),
				array(
					'errors' => array(
						'errWithData' => array(
							array( 'code' => 'overriddenCode', 'text' => $mainpageText,
								'overriddenData' => true, $C => 'text' ),
							$I => 'error',
						),
					),
					'warnings' => array(
						'messageWithData' => array(
							array( 'code' => 'overriddenCode', 'text' => $mainpageText,
								'overriddenData' => true, $C => 'text' ),
							$I => 'warning',
						),
						'message' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
							$I => 'warning',
						),
						'foo' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
							array( 'code' => 'parentheses', 'text' => $parensText, $C => 'text' ),
							$I => 'warning',
						),
					),
				),
				array(
					'errors' => array(
						'status' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
							array( 'code' => 'parentheses', 'text' => $parensText, $C => 'text' ),
							$I => 'error',
						),
					),
					'warnings' => array(
						'status' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
							array( 'code' => 'parentheses', 'text' => $parensText, $C => 'text' ),
							array( 'code' => 'overriddenCode', 'text' => $mainpageText,
								'overriddenData' => true, $C => 'text' ),
							$I => 'warning',
						),
					),
				),
			),
			array( 'raw', 'fr', true,
				array(
					'errors' => array(
						'err' => array(
							array( 'code' => 'mainpage', 'message' => 'mainpage', 'params' => array( $I => 'param' ) ),
							$I => 'error',
						),
					),
					'warnings' => array(
						'string' => array(
							array( 'code' => 'mainpage', 'message' => 'mainpage', 'params' => array( $I => 'param' ) ),
							$I => 'warning',
						),
					),
				),
				array(
					'errors' => array(
						'errWithData' => array(
							array( 'code' => 'overriddenCode', 'message' => 'mainpage', 'params' => array( $I => 'param' ),
								'overriddenData' => true ),
							$I => 'error',
						),
					),
					'warnings' => array(
						'messageWithData' => array(
							array( 'code' => 'overriddenCode', 'message' => 'mainpage', 'params' => array( $I => 'param' ),
								'overriddenData' => true ),
							$I => 'warning',
						),
						'message' => array(
							array( 'code' => 'mainpage', 'message' => 'mainpage', 'params' => array( $I => 'param' ) ),
							$I => 'warning',
						),
						'foo' => array(
							array( 'code' => 'mainpage', 'message' => 'mainpage', 'params' => array( $I => 'param' ) ),
							array( 'code' => 'parentheses', 'message' => 'parentheses', 'params' => array( 'foobar', $I => 'param' ) ),
							$I => 'warning',
						),
					),
				),
				array(
					'errors' => array(
						'status' => array(
							array( 'code' => 'mainpage', 'message' => 'mainpage', 'params' => array( $I => 'param' ) ),
							array( 'code' => 'parentheses', 'message' => 'parentheses', 'params' => array( 'foobar', $I => 'param' ) ),
							$I => 'error',
						),
					),
					'warnings' => array(
						'status' => array(
							array( 'code' => 'mainpage', 'message' => 'mainpage', 'params' => array( $I => 'param' ) ),
							array( 'code' => 'parentheses', 'message' => 'parentheses', 'params' => array( 'foobar', $I => 'param' ) ),
							array( 'code' => 'overriddenCode', 'message' => 'mainpage', 'params' => array( $I => 'param' ),
								'overriddenData' => true ),
							$I => 'warning',
						),
					),
				),
			),
			array( 'none', 'fr', true,
				array(
					'errors' => array(
						'err' => array(
							array( 'code' => 'mainpage' ),
							$I => 'error',
						),
					),
					'warnings' => array(
						'string' => array(
							array( 'code' => 'mainpage' ),
							$I => 'warning',
						),
					),
				),
				array(
					'errors' => array(
						'errWithData' => array(
							array( 'code' => 'overriddenCode', 'overriddenData' => true ),
							$I => 'error',
						),
					),
					'warnings' => array(
						'messageWithData' => array(
							array( 'code' => 'overriddenCode', 'overriddenData' => true ),
							$I => 'warning',
						),
						'message' => array(
							array( 'code' => 'mainpage' ),
							$I => 'warning',
						),
						'foo' => array(
							array( 'code' => 'mainpage' ),
							array( 'code' => 'parentheses' ),
							$I => 'warning',
						),
					),
				),
				array(
					'errors' => array(
						'status' => array(
							array( 'code' => 'mainpage' ),
							array( 'code' => 'parentheses' ),
							$I => 'error',
						),
					),
					'warnings' => array(
						'status' => array(
							array( 'code' => 'mainpage' ),
							array( 'code' => 'parentheses' ),
							array( 'code' => 'overriddenCode', 'overriddenData' => true ),
							$I => 'warning',
						),
					),
				),
			),
		);
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
		$this->assertSame( array(
			'error' => array(
				'code' => 'mainpage',
				'info' => $mainpagePlain,
			),
			'warnings' => array(
				'string' => array(
					'warnings' => $mainpagePlain,
					ApiResult::META_CONTENT => 'warnings',
				),
			),
			ApiResult::META_TYPE => 'assoc',
		), $result->getResultData(), 'Simple test' );

		$result->reset();
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'foo', 'mainpage' );
		$formatter->addWarning( 'foo', array( 'parentheses', 'foobar' ) );
		$msg1 = wfMessage( 'mainpage' );
		$formatter->addWarning( 'message', $msg1 );
		$msg2 = new ApiMessage( 'mainpage', 'overriddenCode', array( 'overriddenData' => true ) );
		$formatter->addWarning( 'messageWithData', $msg2 );
		$formatter->addError( 'errWithData', $msg2 );
		$this->assertSame( array(
			'error' => array(
				'code' => 'overriddenCode',
				'info' => $mainpagePlain,
				'overriddenData' => true,
			),
			'warnings' => array(
				'messageWithData' => array(
					'warnings' => $mainpagePlain,
					ApiResult::META_CONTENT => 'warnings',
				),
				'message' => array(
					'warnings' => $mainpagePlain,
					ApiResult::META_CONTENT => 'warnings',
				),
				'foo' => array(
					'warnings' => "$mainpagePlain\n$parensPlain",
					ApiResult::META_CONTENT => 'warnings',
				),
			),
			ApiResult::META_TYPE => 'assoc',
		), $result->getResultData(), 'Complex test' );

		$result->reset();
		$status = Status::newGood();
		$status->warning( 'mainpage' );
		$status->warning( 'parentheses', 'foobar' );
		$status->warning( $msg1 );
		$status->warning( $msg2 );
		$status->error( 'mainpage' );
		$status->error( 'parentheses', 'foobar' );
		$formatter->addMessagesFromStatus( 'status', $status );
		$this->assertSame( array(
			'error' => array(
				'code' => 'parentheses',
				'info' => $parensPlain,
			),
			'warnings' => array(
				'status' => array(
					'warnings' => "$mainpagePlain\n$parensPlain",
					ApiResult::META_CONTENT => 'warnings',
				),
			),
			ApiResult::META_TYPE => 'assoc',
		), $result->getResultData(), 'Status test' );

		$I = ApiResult::META_INDEXED_TAG_NAME;
		$this->assertSame(
			array(
				array( 'type' => 'error', 'message' => 'mainpage', 'params' => array( $I => 'param' ) ),
				array( 'type' => 'error', 'message' => 'parentheses', 'params' => array( 'foobar', $I => 'param' ) ),
				$I => 'error',
			),
			$formatter->arrayFromStatus( $status, 'error' ),
			'arrayFromStatus test for error'
		);
		$this->assertSame(
			array(
				array( 'type' => 'warning', 'message' => 'mainpage', 'params' => array( $I => 'param' ) ),
				array( 'type' => 'warning', 'message' => 'parentheses', 'params' => array( 'foobar', $I => 'param' ) ),
				array( 'message' => 'mainpage', 'params' => array( $I => 'param' ), 'type' => 'warning' ),
				array( 'message' => 'mainpage', 'params' => array( $I => 'param' ), 'type' => 'warning' ),
				$I => 'warning',
			),
			$formatter->arrayFromStatus( $status, 'warning' ),
			'arrayFromStatus test for warning'
		);
	}

}
