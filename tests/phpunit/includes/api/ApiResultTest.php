<?php

/**
 * @covers ApiResult
 * @group API
 */
class ApiResultTest extends MediaWikiTestCase {

	private function getApiResult( $context = null ) {
		if ( !$context ) {
			$context = new DerivativeContext( RequestContext::getMain() );
		}
		return new ApiResult( $context );
	}

	/**
	 * @covers ApiResult
	 */
	public function testStaticDataMethods() {
		$arr = array();

		ApiResult::setValue( $arr, 'setValue', '1' );

		ApiResult::setValue( $arr, null, 'unnamed 1' );
		ApiResult::setValue( $arr, null, 'unnamed 2' );

		ApiResult::setValue( $arr, 'deleteValue', '2' );
		ApiResult::deleteValue( $arr, 'deleteValue' );

		ApiResult::setContentValue( $arr, 'setContentValue', '3' );

		$this->assertSame( array(
			'setValue' => '1',
			'unnamed 1',
			'unnamed 2',
			ApiResult::META_CONTENT => 'setContentValue',
			'setContentValue' => '3',
		), $arr );

		try {
			ApiResult::setValue( $arr, 'setValue', '99' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				'Attempting to add element setValue=99, existing value is 1',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		try {
			ApiResult::setContentValue( $arr, 'setContentValue2', '99' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				'Attempting to set content element as setContentValue2 when setContentValue ' .
					'is already set as the content element',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		ApiResult::setValue( $arr, 'setValue', '99', ApiResult::OVERRIDE );
		$this->assertSame( '99', $arr['setValue'] );

		ApiResult::setContentValue( $arr, 'setContentValue2', '99', ApiResult::OVERRIDE );
		$this->assertSame( 'setContentValue2', $arr[ApiResult::META_CONTENT] );

		$arr = array( 'foo' => 1, 'bar' => 1 );
		ApiResult::setValue( $arr, 'top', '2', ApiResult::ADD_ON_TOP );
		ApiResult::setValue( $arr, null, '2', ApiResult::ADD_ON_TOP );
		ApiResult::setValue( $arr, 'bottom', '2' );
		ApiResult::setValue( $arr, 'foo', '2', ApiResult::OVERRIDE );
		ApiResult::setValue( $arr, 'bar', '2', ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP );
		$this->assertSame( array( 0, 'top', 'foo', 'bar', 'bottom' ), array_keys( $arr ) );

		$arr = array();
		ApiResult::setValue( $arr, 'sub', array( 'foo' => 1 ) );
		ApiResult::setValue( $arr, 'sub', array( 'bar' => 1 ) );
		$this->assertSame( array( 'sub' => array( 'foo' => 1, 'bar' => 1 ) ), $arr );

		try {
			ApiResult::setValue( $arr, 'sub', array( 'foo' => 2, 'baz' => 2 ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				'Conflicting keys (foo) when attempting to merge element sub',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$arr = array();
		$title = Title::newFromText( "MediaWiki:Foobar" );
		$obj = new stdClass;
		$obj->foo = 1;
		$obj->bar = 2;
		ApiResult::setValue( $arr, 'title', $title );
		ApiResult::setValue( $arr, 'obj', $obj );
		$this->assertSame( array(
			'title' => (string)$title,
			'obj' => array( 'foo' => 1, 'bar' => 2, ApiResult::META_TYPE => 'assoc' ),
		), $arr );

		$fh = tmpfile();
		try {
			ApiResult::setValue( $arr, 'file', $fh );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add resource(stream) to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		try {
			$obj->file = $fh;
			ApiResult::setValue( $arr, 'sub', $obj );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add resource(stream) to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		fclose( $fh );

		try {
			ApiResult::setValue( $arr, 'inf', INF );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add non-finite floats to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		try {
			ApiResult::setValue( $arr, 'nan', NAN );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add non-finite floats to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}

	}

	/**
	 * @covers ApiResult
	 */
	public function testInstanceDataMethods() {
		$result = $this->getApiResult();

		$result->addValue( null, 'setValue', '1' );

		$result->addValue( null, null, 'unnamed 1' );
		$result->addValue( null, null, 'unnamed 2' );

		$result->addValue( null, 'deleteValue', '2' );
		$result->removeValue( null, 'deleteValue' );

		$result->addContentValue( null, 'setContentValue', '3' );

		$this->assertSame( array(
			'setValue' => '1',
			'unnamed 1',
			'unnamed 2',
			ApiResult::META_CONTENT => 'setContentValue',
			'setContentValue' => '3',
		), $result->getResultData() );
		$this->assertSame( 20, $result->getSize() );

		try {
			$result->addValue( null, 'setValue', '99' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				'Attempting to add element setValue=99, existing value is 1',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		try {
			$result->addContentValue( null, 'setContentValue2', '99' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				'Attempting to set content element as setContentValue2 when setContentValue ' .
					'is already set as the content element',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$result->addValue( null, 'setValue', '99', ApiResult::OVERRIDE );
		$this->assertSame( '99', $result->getResultData( array( 'setValue' ) ) );

		$result->addContentValue( null, 'setContentValue2', '99', ApiResult::OVERRIDE );
		$this->assertSame( 'setContentValue2',
			$result->getResultData( array( ApiResult::META_CONTENT ) ) );

		$result->reset();
		$this->assertSame( array(), $result->getResultData() );
		$this->assertSame( 0, $result->getSize() );

		$result->addValue( null, 'foo', 1 );
		$result->addValue( null, 'bar', 1 );
		$result->addValue( null, 'top', '2', ApiResult::ADD_ON_TOP );
		$result->addValue( null, null, '2', ApiResult::ADD_ON_TOP );
		$result->addValue( null, 'bottom', '2' );
		$result->addValue( null, 'foo', '2', ApiResult::OVERRIDE );
		$result->addValue( null, 'bar', '2', ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP );
		$this->assertSame( array( 0, 'top', 'foo', 'bar', 'bottom' ),
			array_keys( $result->getResultData() ) );

		$result->reset();
		$result->addValue( null, 'foo', array( 'bar' => 1 ) );
		$result->addValue( array( 'foo', 'top' ), 'x', 2, ApiResult::ADD_ON_TOP );
		$result->addValue( array( 'foo', 'bottom' ), 'x', 2 );
		$this->assertSame( array( 'top', 'bar', 'bottom' ),
			array_keys( $result->getResultData( array( 'foo' ) ) ) );

		$result->reset();
		$result->addValue( null, 'sub', array( 'foo' => 1 ) );
		$result->addValue( null, 'sub', array( 'bar' => 1 ) );
		$this->assertSame( array( 'sub' => array( 'foo' => 1, 'bar' => 1 ) ),
			$result->getResultData() );

		try {
			$result->addValue( null, 'sub', array( 'foo' => 2, 'baz' => 2 ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				'Conflicting keys (foo) when attempting to merge element sub',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$result->reset();
		$title = Title::newFromText( "MediaWiki:Foobar" );
		$obj = new stdClass;
		$obj->foo = 1;
		$obj->bar = 2;
		$result->addValue( null, 'title', $title );
		$result->addValue( null, 'obj', $obj );
		$this->assertSame( array(
			'title' => (string)$title,
			'obj' => array( 'foo' => 1, 'bar' => 2, ApiResult::META_TYPE => 'assoc' ),
		), $result->getResultData() );

		$fh = tmpfile();
		try {
			$result->addValue( null, 'file', $fh );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add resource(stream) to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		try {
			$obj->file = $fh;
			$result->addValue( null, 'sub', $obj );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add resource(stream) to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		fclose( $fh );

		try {
			$result->addValue( null, 'inf', INF );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add non-finite floats to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		try {
			$result->addValue( null, 'nan', NAN );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add non-finite floats to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$result->reset();
		$result->addParsedLimit( 'foo', 12 );
		$this->assertSame( array( 'limits' => array( 'foo' => 12 ) ), $result->getResultData() );
		$result->addParsedLimit( 'foo', 13 );
		$this->assertSame( array( 'limits' => array( 'foo' => 13 ) ), $result->getResultData() );
		$this->assertSame( null, $result->getResultData( array( 'foo', 'bar', 'baz' ) ) );
		$this->assertSame( 13, $result->getResultData( array( 'limits', 'foo' ) ) );
		try {
			$result->getResultData( array( 'limits', 'foo', 'bar' ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Path limits.foo is not an array',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setConfig( new HashConfig( array( 'APIMaxResultSize' => 10 ) ) );
		$result = $this->getApiResult( $context );
		$this->assertFalse( $result->addValue( null, 'foo', '12345678901' ) );
		$this->assertTrue( $result->addValue( null, 'foo', '12345678901', ApiResult::NO_SIZE_CHECK ) );
		$this->assertSame( 0, $result->getSize() );
		$result->reset();
		$this->assertTrue( $result->addValue( null, 'foo', '1234567890' ) );
		$this->assertFalse( $result->addValue( null, 'foo', '1' ) );
		$result->removeValue( null, 'foo' );
		$this->assertTrue( $result->addValue( null, 'foo', '1' ) );
	}

	/**
	 * @dataProvider provideErrorMethods
	 * @covers ApiResult
	 */
	public function testErrorMethods( $errorFormat, $errorLang, $errorUseDb,
		$expect1, $expect2, $expect3
	) {
		$result = new ApiResult( new DerivativeContext( RequestContext::getMain() ),
			$errorFormat, $errorLang, $errorUseDb );

		$result->addWarning( 'string', 'mainpage' );
		$result->addError( 'err', 'mainpage' );
		$this->assertSame( $expect1, $result->getResultData(), 'Simple test' );

		$result->reset();
		$result->addWarning( 'foo', 'mainpage' );
		$result->addWarning( 'foo', 'mainpage' );
		$result->addWarning( 'foo', array( 'parentheses', 'foobar' ) );
		$msg1 = wfMessage( 'mainpage' );
		$result->addWarning( 'message', $msg1 );
		$msg2 = wfMessage( 'mainpage' );
		$msg2->apiMessageCode = 'overriddenCode';
		$msg2->apiMessageData = array( 'overriddenData' => true );
		$result->addWarning( 'messageWithData', $msg2 );
		$result->addError( 'errWithData', $msg2 );
		$this->assertSame( $expect2, $result->getResultData(), 'Complex test' );

		$result->reset();
		$status = Status::newGood();
		$status->warning( 'mainpage' );
		$status->warning( 'parentheses', 'foobar' );
		$status->warning( $msg1 );
		$status->warning( $msg2 );
		$status->error( 'mainpage' );
		$status->error( 'parentheses', 'foobar' );
		$result->addMessagesFromStatus( 'status', $status );
		$this->assertSame( $expect3, $result->getResultData(), 'Status test' );
	}

	public static function provideErrorMethods() {
		$mainpagePlain = wfMessage( 'mainpage' )->useDatabase( false )->plain();
		$parensPlain = wfMessage( 'parentheses', 'foobar' )->useDatabase( false )->plain();
		$mainpageText = wfMessage( 'mainpage' )->inLanguage( 'de' )->text();
		$parensText = wfMessage( 'parentheses', 'foobar' )->inLanguage( 'de' )->text();
		$C = ApiResult::META_CONTENT;

		return array(
			array( 'bc', 'en', false,
				array(
					'error' => array(
						'code' => 'mainpage',
						'info' => $mainpagePlain,
					),
					'warnings' => array(
						'string' => array(
							'warnings' => $mainpagePlain,
							$C => 'warnings',
						),
					),
				),
				array(
					'error' => array(
						'code' => 'overriddenCode',
						'info' => $mainpagePlain,
						'overriddenData' => true,
					),
					'warnings' => array(
						'messageWithData' => array(
							'warnings' => $mainpagePlain,
							$C => 'warnings',
						),
						'message' => array(
							'warnings' => $mainpagePlain,
							$C => 'warnings',
						),
						'foo' => array(
							'warnings' => "$mainpagePlain\n$parensPlain",
							$C => 'warnings',
						),
					),
				),
				array(
					'error' => array(
						'code' => 'parentheses',
						'info' => $parensPlain,
					),
					'warnings' => array(
						'status' => array(
							'warnings' => "$mainpagePlain\n$parensPlain",
							$C => 'warnings',
						),
					),
				),
			),
			array( 'wikitext', 'de', true,
				array(
					'errors' => array(
						'err' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
						),
					),
					'warnings' => array(
						'string' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
						),
					),
				),
				array(
					'errors' => array(
						'errWithData' => array(
							array( 'code' => 'overriddenCode', 'text' => $mainpageText, $C => 'text',
								'overriddenData' => true ),
						),
					),
					'warnings' => array(
						'messageWithData' => array(
							array( 'code' => 'overriddenCode', 'text' => $mainpageText, $C => 'text',
								'overriddenData' => true ),
						),
						'message' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
						),
						'foo' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
							array( 'code' => 'parentheses', 'text' => $parensText, $C => 'text' ),
						),
					),
				),
				array(
					'errors' => array(
						'status' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
							array( 'code' => 'parentheses', 'text' => $parensText, $C => 'text' ),
						),
					),
					'warnings' => array(
						'status' => array(
							array( 'code' => 'mainpage', 'text' => $mainpageText, $C => 'text' ),
							array( 'code' => 'parentheses', 'text' => $parensText, $C => 'text' ),
							array( 'code' => 'overriddenCode', 'text' => $mainpageText, $C => 'text',
								'overriddenData' => true ),
						),
					),
				),
			),
			array( 'raw', 'fr', true,
				array(
					'errors' => array(
						'err' => array(
							array( 'code' => 'mainpage', 'key' => 'mainpage', 'params' => array() ),
						),
					),
					'warnings' => array(
						'string' => array(
							array( 'code' => 'mainpage', 'key' => 'mainpage', 'params' => array() ),
						),
					),
				),
				array(
					'errors' => array(
						'errWithData' => array(
							array( 'code' => 'overriddenCode', 'key' => 'mainpage', 'params' => array(),
								'overriddenData' => true ),
						),
					),
					'warnings' => array(
						'messageWithData' => array(
							array( 'code' => 'overriddenCode', 'key' => 'mainpage', 'params' => array(),
								'overriddenData' => true ),
						),
						'message' => array(
							array( 'code' => 'mainpage', 'key' => 'mainpage', 'params' => array() ),
						),
						'foo' => array(
							array( 'code' => 'mainpage', 'key' => 'mainpage', 'params' => array() ),
							array( 'code' => 'parentheses', 'key' => 'parentheses', 'params' => array( 'foobar' ) ),
						),
					),
				),
				array(
					'errors' => array(
						'status' => array(
							array( 'code' => 'mainpage', 'key' => 'mainpage', 'params' => array() ),
							array( 'code' => 'parentheses', 'key' => 'parentheses', 'params' => array( 'foobar' ) ),
						),
					),
					'warnings' => array(
						'status' => array(
							array( 'code' => 'mainpage', 'key' => 'mainpage', 'params' => array() ),
							array( 'code' => 'parentheses', 'key' => 'parentheses', 'params' => array( 'foobar' ) ),
							array( 'code' => 'overriddenCode', 'key' => 'mainpage', 'params' => array(),
								'overriddenData' => true ),
						),
					),
				),
			),
			array( 'none', 'fr', true,
				array(
					'errors' => array(
						'err' => array(
							array( 'code' => 'mainpage' ),
						),
					),
					'warnings' => array(
						'string' => array(
							array( 'code' => 'mainpage' ),
						),
					),
				),
				array(
					'errors' => array(
						'errWithData' => array(
							array( 'code' => 'overriddenCode', 'overriddenData' => true ),
						),
					),
					'warnings' => array(
						'messageWithData' => array(
							array( 'code' => 'overriddenCode', 'overriddenData' => true ),
						),
						'message' => array(
							array( 'code' => 'mainpage' ),
						),
						'foo' => array(
							array( 'code' => 'mainpage' ),
							array( 'code' => 'parentheses' ),
						),
					),
				),
				array(
					'errors' => array(
						'status' => array(
							array( 'code' => 'mainpage' ),
							array( 'code' => 'parentheses' ),
						),
					),
					'warnings' => array(
						'status' => array(
							array( 'code' => 'mainpage' ),
							array( 'code' => 'parentheses' ),
							array( 'code' => 'overriddenCode', 'overriddenData' => true ),
						),
					),
				),
			),
		);
	}

	/**
	 * @covers ApiResult
	 */
	public function testMetadata() {
		$arr = array( 'foo' => array( 'bar' => array() ) );
		$result = $this->getApiResult();
		$result->addValue( null, 'foo', array( 'bar' => array() ) );

		$expect = array(
			'foo' => array(
				'bar' => array(
					ApiResult::META_INDEXED_TAG_NAME => 'ritn',
					ApiResult::META_TYPE => 'default',
				),
				ApiResult::META_INDEXED_TAG_NAME => 'ritn',
				ApiResult::META_TYPE => 'default',
			),
			ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => array( 'foo', 'bar' ),
			ApiResult::META_TYPE => 'array',
		);

		ApiResult::setSubelements( $arr, 'foo' );
		ApiResult::setSubelements( $arr, array( 'bar', 'baz' ) );
		ApiResult::unsetSubelements( $arr, 'baz' );
		ApiResult::setIndexedTagName( $arr, 'itn' );
		ApiResult::setIndexedTagNameOnSubarrays( $arr, 'ritn' );
		ApiResult::setPreserveKeys( $arr, 'foo' );
		ApiResult::setPreserveKeys( $arr, array( 'bar', 'baz' ) );
		ApiResult::unsetPreserveKeys( $arr, 'baz' );
		ApiResult::setArrayTypeRecursive( $arr, 'default' );
		ApiResult::setArrayType( $arr, 'array' );
		$this->assertSame( $expect, $arr );

		$result->defineSubelements( null, 'foo' );
		$result->defineSubelements( null, array( 'bar', 'baz' ) );
		$result->undefineSubelements( null, 'baz' );
		$result->defineIndexedTagName( null, 'itn' );
		$result->defineIndexedTagNameOnSubarrays( null, 'ritn' );
		$result->definePreserveKeys( null, 'foo' );
		$result->definePreserveKeys( null, array( 'bar', 'baz' ) );
		$result->undefinePreserveKeys( null, 'baz' );
		$result->defineArrayTypeRecursive( null, 'default' );
		$result->defineArrayType( null, 'array' );
		$this->assertSame( $expect, $result->getResultData() );

		$arr = array( 'foo' => array( 'bar' => array() ) );
		$expect = array(
			'foo' => array(
				'bar' => array(
					ApiResult::META_TYPE => 'kvp',
					ApiResult::META_KVP_KEY_NAME => 'key',
				),
				ApiResult::META_TYPE => 'kvp',
				ApiResult::META_KVP_KEY_NAME => 'key',
			),
			ApiResult::META_TYPE => 'BCkvp',
			ApiResult::META_KVP_KEY_NAME => 'bc',
		);
		ApiResult::setArrayTypeRecursive( $arr, 'kvp', 'key' );
		ApiResult::setArrayType( $arr, 'BCkvp', 'bc' );
		$this->assertSame( $expect, $arr );
	}

	/**
	 * @covers ApiResult
	 */
	public function testContinuation() {
		$allModules = array(
			new MockApiQueryBase( 'mock1' ),
			new MockApiQueryBase( 'mock2' ),
			new MockApiQueryBase( 'mocklist' ),
		);
		$generator = new MockApiQueryBase( 'generator' );

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->addContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$result->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->addGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'mock1' => array( 'm1continue' => '1|2' ),
			'mocklist' => array( 'mlcontinue' => 2 ),
			'generator' => array( 'gcontinue' => 3 ),
		), $result->getResultData( 'query-continue' ) );

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->addContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$result->addGeneratorContinueParam( $generator, 'gcontinue', array( 3, 4 ) );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'm1continue' => '1|2',
			'continue' => '||mock2|mocklist',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'mock1' => array( 'm1continue' => '1|2' ),
			'generator' => array( 'gcontinue' => '3|4' ),
		), $result->getResultData( 'query-continue' ) );

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->addGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'mlcontinue' => 2,
			'gcontinue' => 3,
			'continue' => 'gcontinue||',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( '', $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'mocklist' => array( 'mlcontinue' => 2 ),
			'generator' => array( 'gcontinue' => 3 ),
		), $result->getResultData( 'query-continue' ) );

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->addGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'gcontinue' => 3,
			'continue' => 'gcontinue||mocklist',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( '', $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'generator' => array( 'gcontinue' => 3 ),
		), $result->getResultData( 'query-continue' ) );

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->addContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$result->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'mock1' => array( 'm1continue' => '1|2' ),
			'mocklist' => array( 'mlcontinue' => 2 ),
		), $result->getResultData( 'query-continue' ) );

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->addContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'm1continue' => '1|2',
			'continue' => '||mock2|mocklist',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'mock1' => array( 'm1continue' => '1|2' ),
		), $result->getResultData( 'query-continue' ) );

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'mlcontinue' => 2,
			'continue' => '-||mock1|mock2',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( '', $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'mocklist' => array( 'mlcontinue' => 2 ),
		), $result->getResultData( 'query-continue' ) );

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( null, $result->getResultData( 'continue' ) );
		$this->assertSame( '', $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( null, $result->getResultData( 'query-continue' ) );

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( '||mock2', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame(
			array( false, array_values( array_diff_key( $allModules, array( 1 => 1 ) ) ) ),
			$ret
		);

		$result = $this->getApiResult();
		$ret = $result->beginContinuation( '-||', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame(
			array( true, array_values( array_diff_key( $allModules, array( 0 => 0, 1 => 1 ) ) ) ),
			$ret
		);

		$result = $this->getApiResult();
		try {
			$result->beginContinuation( 'foo', $allModules, array( 'mock1', 'mock2' ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UsageException $ex ) {
			$this->assertSame(
				'Invalid continue param. You should pass the original value returned by the previous query',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$result = $this->getApiResult();
		$result->beginContinuation( '||mock2', array_slice( $allModules, 0, 2 ), array( 'mock1', 'mock2' ) );
		try {
			$result->addContinueParam( $allModules[1], 'm2continue', 1 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Module \'mock2\' was not supposed to have been executed, but it was executed anyway',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		try {
			$result->addContinueParam( $allModules[2], 'mlcontinue', 1 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Module \'mocklist\' called ApiResult::addContinueParam but was not passed to ApiResult::beginContinuation',
				$ex->getMessage(),
				'Expected exception'
			);
		}

	}

	/**
	 * @covers ApiResult
	 */
	public function testUtilityFunctions() {
		$result = $this->getApiResult();
		$result->addValue( null, 'foo', "foo\x80bar" );
		$result->addValue( null, 'bar', "a\xcc\x81" );
		$result->addValue( null, 'baz', 74 );
		$result->cleanUpUTF8();
		$this->assertSame( array(
			'foo' => "foo\xef\xbf\xbdbar",
			'bar' => "\xc3\xa1",
			'baz' => 74,
		), $result->getResultData() );

		$arr = array(
			'foo' => array(
				'bar' => array( '_dummy' => 'foobaz' ),
				'bar2' => (object)array( '_dummy' => 'foobaz' ),
				'x' => 'ok',
				'_dummy' => 'foobaz',
			),
			'foo2' => (object)array(
				'bar' => array( '_dummy' => 'foobaz' ),
				'bar2' => (object)array( '_dummy' => 'foobaz' ),
				'x' => 'ok',
				'_dummy' => 'foobaz',
			),
			ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => array( 'foo', 'bar', '_dummy2', 0 ),
			ApiResult::META_TYPE => 'array',
			'_dummy' => 'foobaz',
			'_dummy2' => 'foobaz!',
		);
		$this->assertEquals( array(
			'foo' => array(
				'bar' => array(),
				'bar2' => (object)array(),
				'x' => 'ok',
			),
			'foo2' => (object)array(
				'bar' => array(),
				'bar2' => (object)array(),
				'x' => 'ok',
			),
			'_dummy2' => 'foobaz!',
		), ApiResult::removeMetadata( $arr ), 'ApiResult::removeMetadata' );

		$metadata = array();
		$data = ApiResult::removeMetadataNonRecursive( $arr, $metadata );
		$this->assertEquals( array(
			'foo' => array(
				'bar' => array( '_dummy' => 'foobaz' ),
				'bar2' => (object)array( '_dummy' => 'foobaz' ),
				'x' => 'ok',
				'_dummy' => 'foobaz',
			),
			'foo2' => (object)array(
				'bar' => array( '_dummy' => 'foobaz' ),
				'bar2' => (object)array( '_dummy' => 'foobaz' ),
				'x' => 'ok',
				'_dummy' => 'foobaz',
			),
			'_dummy2' => 'foobaz!',
		), $data, 'ApiResult::removeMetadataNonRecursive ($data)' );
		$this->assertEquals( array(
			ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => array( 'foo', 'bar', '_dummy2', 0 ),
			ApiResult::META_TYPE => 'array',
			'_dummy' => 'foobaz',
		), $metadata, 'ApiResult::removeMetadataNonRecursive ($metadata)' );

		$metadata = null;
		$data = ApiResult::removeMetadataNonRecursive( (object)$arr, $metadata );
		$this->assertEquals( (object)array(
			'foo' => array(
				'bar' => array( '_dummy' => 'foobaz' ),
				'bar2' => (object)array( '_dummy' => 'foobaz' ),
				'x' => 'ok',
				'_dummy' => 'foobaz',
			),
			'foo2' => (object)array(
				'bar' => array( '_dummy' => 'foobaz' ),
				'bar2' => (object)array( '_dummy' => 'foobaz' ),
				'x' => 'ok',
				'_dummy' => 'foobaz',
			),
			'_dummy2' => 'foobaz!',
		), $data, 'ApiResult::removeMetadataNonRecursive on object ($data)' );
		$this->assertEquals( array(
			ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => array( 'foo', 'bar', '_dummy2', 0 ),
			ApiResult::META_TYPE => 'array',
			'_dummy' => 'foobaz',
		), $metadata, 'ApiResult::removeMetadataNonRecursive on object ($metadata)' );

		$arr = ApiResult::removeMetadata( ApiResult::transformForBC( array(
			'nobc' => array(
				'true' => true,
				'false' => false,
				ApiResult::META_BC_BOOLS => array( 0 ),
			),
			'bc' => array(
				'true' => true,
				'false' => false,
				ApiResult::META_BC_BOOLS => array( 'true', 'false' ),
			),
			'subelements' => array(
				'bc' => 'foo',
				'nobc' => 'bar',
				ApiResult::META_BC_SUBELEMENTS => array( 'bc' ),
			),
			'kvp' => array(
				'foo' => 'foo value',
				'bar' => 'bar value',
				'_baz' => 'baz value',
				ApiResult::META_TYPE => 'BCkvp',
				ApiResult::META_KVP_KEY_NAME => 'key',
				ApiResult::META_PRESERVE_KEYS => array( '_baz' ),
			),
			'content' => '!!!',
			ApiResult::META_CONTENT => 'content',
		) ) );
		$this->assertSame( array(
			'nobc' => array( 'true' => '' ),
			'bc' => array( 'true' => true, 'false' => false ),
			'subelements' => array(
				'bc' => array( '*' => 'foo' ),
				'nobc' => 'bar',
			),
			'kvp' => array(
				array( 'key' => 'foo', '*' => 'foo value' ),
				array( 'key' => 'bar', '*' => 'bar value' ),
				array( 'key' => '_baz', '*' => 'baz value' ),
			),
			'*' => '!!!'
		), $arr, 'ApiResult::transformForBC' );

		$this->assertSame( array(
			ApiResult::META_TYPE => 'default',
		), ApiResult::transformForBC( array(
			ApiResult::META_TYPE => 'BCarray',
		) ), 'ApiResult::transformForBC' );

		$this->assertSame( array(
			ApiResult::META_TYPE => 'default',
		), ApiResult::transformForBC( array(
			ApiResult::META_TYPE => 'BCassoc',
		) ), 'ApiResult::transformForBC' );

		try {
			ApiResult::transformForBC( array(
				ApiResult::META_TYPE => 'BCkvp',
			) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Type "BCkvp" used without setting ApiResult::META_KVP_KEY_NAME metadata item',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$arr = array(
			'defaultArray' => array( 2 => 'a', 0 => 'b', 1 => 'c' ),
			'defaultAssoc' => array( 'x' => 'a', 1 => 'b', 0 => 'c' ),
			'defaultAssoc2' => array( 2 => 'a', 3 => 'b', 0 => 'c' ),
			'array' => array( 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'array' ),
			'BCarray' => array( 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'BCarray' ),
			'BCassoc' => array( 'a', 'b', 'c', ApiResult::META_TYPE => 'BCassoc' ),
			'assoc' => array( 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ),
			'kvp' => array( 'x' => 'a', 'y' => 'b', 'z' => array( 'c' ), ApiResult::META_TYPE => 'kvp' ),
			'BCkvp' => array( 'x' => 'a', 'y' => 'b',
				ApiResult::META_TYPE => 'BCkvp',
				ApiResult::META_KVP_KEY_NAME => 'key',
			),
			'emptyDefault' => array( '_dummy' => 1 ),
			'emptyAssoc' => array( '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ),
			'_dummy' => 1,
			ApiResult::META_PRESERVE_KEYS => array( '_dummy' ),
		);
		$this->assertSame(
			array(
				'defaultArray' => array( 'b', 'c', 'a' ),
				'defaultAssoc' => array( 'x' => 'a', 1 => 'b', 0 => 'c' ),
				'defaultAssoc2' => array( 2 => 'a', 3 => 'b', 0 => 'c' ),
				'array' => array( 'a', 'c', 'b' ),
				'BCarray' => array( 'a', 'c', 'b' ),
				'BCassoc' => array( 'a', 'b', 'c' ),
				'assoc' => array( 2 => 'a', 0 => 'b', 1 => 'c' ),
				'kvp' => array( 'x' => 'a', 'y' => 'b', 'z' => array( 'c' ) ),
				'BCkvp' => array( 'x' => 'a', 'y' => 'b' ),
				'emptyDefault' => array(),
				'emptyAssoc' => array(),
				'_dummy' => 1,
			),
			ApiResult::removeMetadata( ApiResult::transformForTypes( $arr ) ),
			'ApiResult::transformForTypes'
		);
		$this->assertEquals(
			(object)array(
				'defaultArray' => array( 'b', 'c', 'a' ),
				'defaultAssoc' => (object)array( 'x' => 'a', 1 => 'b', 0 => 'c' ),
				'defaultAssoc2' => (object)array( 2 => 'a', 3 => 'b', 0 => 'c' ),
				'array' => array( 'a', 'c', 'b' ),
				'BCarray' => array( 'a', 'c', 'b' ),
				'BCassoc' => (object)array( 'a', 'b', 'c' ),
				'assoc' => (object)array( 2 => 'a', 0 => 'b', 1 => 'c' ),
				'kvp' => (object)array( 'x' => 'a', 'y' => 'b', 'z' => array( 'c' ) ),
				'BCkvp' => (object)array( 'x' => 'a', 'y' => 'b' ),
				'emptyDefault' => array(),
				'emptyAssoc' => (object)array(),
				'_dummy' => 1,
			),
			ApiResult::removeMetadata(
				ApiResult::transformForTypes( $arr, array( 'assocAsObject' => true ) )
			),
			'ApiResult::transformForTypes + assocAsObject'
		);
		$this->assertSame(
			array(
				'defaultArray' => array( 'b', 'c', 'a' ),
				'defaultAssoc' => array( 'x' => 'a', 1 => 'b', 0 => 'c' ),
				'defaultAssoc2' => array( 2 => 'a', 3 => 'b', 0 => 'c' ),
				'array' => array( 'a', 'c', 'b' ),
				'BCarray' => array( 'a', 'c', 'b' ),
				'BCassoc' => array( 'a', 'b', 'c' ),
				'assoc' => array( 2 => 'a', 0 => 'b', 1 => 'c' ),
				'kvp' => array(
					array( 'name' => 'x', 'value' => 'a' ),
					array( 'name' => 'y', 'value' => 'b' ),
					array( 'name' => 'z', 'value' => array( 'c' ) ),
				),
				'BCkvp' => array(
					array( 'key' => 'x', 'value' => 'a' ),
					array( 'key' => 'y', 'value' => 'b' ),
				),
				'emptyDefault' => array(),
				'emptyAssoc' => array(),
				'_dummy' => 1,
			),
			ApiResult::removeMetadata(
				ApiResult::transformForTypes( $arr, array( 'armorKVP' => 'name' ) )
			),
			'ApiResult::transformForTypes + armorKVP'
		);
		$this->assertSame(
			array(
				'defaultArray' => array( 'b', 'c', 'a' ),
				'defaultAssoc' => array( 'x' => 'a', 1 => 'b', 0 => 'c' ),
				'defaultAssoc2' => array( 2 => 'a', 3 => 'b', 0 => 'c' ),
				'array' => array( 'a', 'c', 'b' ),
				'BCarray' => array( 'x' => 'a', 1 => 'b', 0 => 'c' ),
				'BCassoc' => array( 'a', 'b', 'c' ),
				'assoc' => array( 2 => 'a', 0 => 'b', 1 => 'c' ),
				'kvp' => array( 'x' => 'a', 'y' => 'b', 'z' => array( 'c' ) ),
				'BCkvp' => array(
					array( 'key' => 'x', '*' => 'a' ),
					array( 'key' => 'y', '*' => 'b' ),
				),
				'emptyDefault' => array(),
				'emptyAssoc' => array(),
				'_dummy' => 1,
			),
			ApiResult::removeMetadata(
				ApiResult::transformForTypes( $arr, array( 'BC' => true ) )
			),
			'ApiResult::transformForTypes + BC'
		);
		$this->assertEquals(
			(object)array(
				'defaultArray' => array( 'b', 'c', 'a' ),
				'defaultAssoc' => (object)array( 'x' => 'a', 1 => 'b', 0 => 'c' ),
				'defaultAssoc2' => (object)array( 2 => 'a', 3 => 'b', 0 => 'c' ),
				'array' => array( 'a', 'c', 'b' ),
				'BCarray' => array( 'a', 'c', 'b' ),
				'BCassoc' => (object)array( 'a', 'b', 'c' ),
				'assoc' => (object)array( 2 => 'a', 0 => 'b', 1 => 'c' ),
				'kvp' => array(
					(object)array( 'name' => 'x', 'value' => 'a' ),
					(object)array( 'name' => 'y', 'value' => 'b' ),
					(object)array( 'name' => 'z', 'value' => array( 'c' ) ),
				),
				'BCkvp' => array(
					(object)array( 'key' => 'x', 'value' => 'a' ),
					(object)array( 'key' => 'y', 'value' => 'b' ),
				),
				'emptyDefault' => array(),
				'emptyAssoc' => (object)array(),
				'_dummy' => 1,
			),
			ApiResult::removeMetadata(
				ApiResult::transformForTypes( $arr, array( 'armorKVP' => 'name', 'assocAsObject' => true ) )
			),
			'ApiResult::transformForTypes + armorKVP + assocAsObject'
		);

		try {
			ApiResult::transformForTypes( array(
				ApiResult::META_TYPE => 'BCkvp',
			) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Type "BCkvp" used without setting ApiResult::META_KVP_KEY_NAME metadata item',
				$ex->getMessage(),
				'Expected exception'
			);
		}

	}

	/**
	 * @covers ApiResult
	 */
	public function testDeprecatedFunctions() {
		// Ignore ApiResult deprecation warnings during this test
		set_error_handler( function ( $errno, $errstr ) use ( &$warnings ) {
			if ( preg_match( '/Use of ApiResult::\S+ was deprecated in MediaWiki \d+.\d+\./', $errstr ) ) {
				return true;
			}
			return false;
		} );
		$reset = new ScopedCallback( 'restore_error_handler' );

		$result = $this->getApiResult();

		$result->addContentValue( null, 'test', 'content' );
		$result->addContentValue( array( 'foo', 'bar' ), 'test', 'content' );
		$this->assertSame( array(
			'test' => 'content',
			'foo' => array(
				'bar' => array(
					'test' => 'content',
				),
			),
		), $result->getData() );
		$result->setRawMode();
		$this->assertSame( array(
			ApiResult::META_CONTENT => 'test',
			'test' => 'content',
			'foo' => array(
				'bar' => array(
					ApiResult::META_CONTENT => 'test',
					'test' => 'content',
				),
			),
		), $result->getData() );

		$arr = array();
		ApiResult::setContent( $arr, 'value' );
		ApiResult::setContent( $arr, 'value2', 'foobar' );
		$this->assertSame( array(
			ApiResult::META_CONTENT => 'content',
			'content' => 'value',
			'foobar' => array(
				ApiResult::META_CONTENT => 'content',
				'content' => 'value2',
			),
		), $arr );

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setConfig( new HashConfig( array( 'APIMaxResultSize' => 3 ) ) );
		$result = $this->getApiResult( $context );
		$result->disableSizeCheck();
		$this->assertTrue( $result->addValue( null, 'foo', '1234567890' ) );
		$result->enableSizeCheck();
		$this->assertSame( 0, $result->getSize() );
		$this->assertFalse( $result->addValue( null, 'foo', '1234567890' ) );
	}

	public function testObjectSerialization() {
		$arr = array();
		ApiResult::setValue( $arr, 'foo', (object)array( 'a' => 1, 'b' => 2 ) );
		$this->assertSame( array(
			'a' => 1,
			'b' => 2,
			ApiResult::META_TYPE => 'assoc',
		), $arr['foo'] );

		$arr = array();
		ApiResult::setValue( $arr, 'foo', new ApiResultTestStringifiableObject() );
		$this->assertSame( 'Ok', $arr['foo'] );

		$arr = array();
		ApiResult::setValue( $arr, 'foo', new ApiResultTestSerializableObject( 'Ok' ) );
		$this->assertSame( 'Ok', $arr['foo'] );

		try {
			$arr = array();
			ApiResult::setValue( $arr, 'foo',  new ApiResultTestSerializableObject(
				new ApiResultTestStringifiableObject()
			) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'ApiResultTestSerializableObject::serializeForApiResult() returned an object of class ApiResultTestStringifiableObject',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		try {
			$arr = array();
			ApiResult::setValue( $arr, 'foo',  new ApiResultTestSerializableObject( NAN ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'ApiResultTestSerializableObject::serializeForApiResult() returned an invalid value: Cannot add non-finite floats to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$arr = array();
		ApiResult::setValue( $arr, 'foo',  new ApiResultTestSerializableObject(
			array(
				'one' => new ApiResultTestStringifiableObject( '1' ),
				'two' => new ApiResultTestSerializableObject( 2 ),
			)
		) );
		$this->assertSame( array(
			'one' => '1',
			'two' => 2,
		), $arr['foo'] );
	}

}

class ApiResultTestStringifiableObject {
	private $ret;

	public function __construct( $ret = 'Ok' ) {
		$this->ret = $ret;
	}

	public function __toString() {
		return $this->ret;
	}
}

class ApiResultTestSerializableObject {
	private $ret;

	public function __construct( $ret ) {
		$this->ret = $ret;
	}

	public function __toString() {
		return "Fail";
	}

	public function serializeForApiResult() {
		return $this->ret;
	}
}
