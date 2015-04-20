<?php

/**
 * @covers ApiResult
 * @group API
 */
class ApiResultTest extends MediaWikiTestCase {

	/**
	 * @covers ApiResult
	 */
	public function testStaticDataMethods() {
		$arr = array();

		ApiResult::setValue( $arr, 'setValue', '1' );

		ApiResult::setValue( $arr, null, 'unnamed 1' );
		ApiResult::setValue( $arr, null, 'unnamed 2' );

		ApiResult::setValue( $arr, 'deleteValue', '2' );
		ApiResult::unsetValue( $arr, 'deleteValue' );

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
			ApiResult::setValue( $arr, null, $fh );
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
		try {
			$obj->file = $fh;
			ApiResult::setValue( $arr, null, $obj );
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
			ApiResult::setValue( $arr, null, INF );
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
		try {
			ApiResult::setValue( $arr, null, NAN );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add non-finite floats to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$arr = array();
		$result2 = new ApiResult( 8388608 );
		$result2->addValue( null, 'foo', 'bar' );
		ApiResult::setValue( $arr, 'baz', $result2 );
		$this->assertSame( array(
			'baz' => array(
				ApiResult::META_TYPE => 'assoc',
				'foo' => 'bar',
			)
		), $arr );

		$arr = array();
		ApiResult::setValue( $arr, 'foo', "foo\x80bar" );
		ApiResult::setValue( $arr, 'bar', "a\xcc\x81" );
		ApiResult::setValue( $arr, 'baz', 74 );
		ApiResult::setValue( $arr, null, "foo\x80bar" );
		ApiResult::setValue( $arr, null, "a\xcc\x81" );
		$this->assertSame( array(
			'foo' => "foo\xef\xbf\xbdbar",
			'bar' => "\xc3\xa1",
			'baz' => 74,
			0 => "foo\xef\xbf\xbdbar",
			1 => "\xc3\xa1",
		), $arr );
	}

	/**
	 * @covers ApiResult
	 */
	public function testInstanceDataMethods() {
		$result = new ApiResult( 8388608 );

		$result->addValue( null, 'setValue', '1' );

		$result->addValue( null, null, 'unnamed 1' );
		$result->addValue( null, null, 'unnamed 2' );

		$result->addValue( null, 'deleteValue', '2' );
		$result->removeValue( null, 'deleteValue' );

		$result->addValue( array( 'a', 'b' ), 'deleteValue', '3' );
		$result->removeValue( array( 'a', 'b', 'deleteValue' ), null, '3' );

		$result->addContentValue( null, 'setContentValue', '3' );

		$this->assertSame( array(
			'setValue' => '1',
			'unnamed 1',
			'unnamed 2',
			'a' => array( 'b' => array() ),
			'setContentValue' => '3',
			ApiResult::META_TYPE => 'assoc',
			ApiResult::META_CONTENT => 'setContentValue',
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
		$this->assertSame( array(
			ApiResult::META_TYPE => 'assoc',
		), $result->getResultData() );
		$this->assertSame( 0, $result->getSize() );

		$result->addValue( null, 'foo', 1 );
		$result->addValue( null, 'bar', 1 );
		$result->addValue( null, 'top', '2', ApiResult::ADD_ON_TOP );
		$result->addValue( null, null, '2', ApiResult::ADD_ON_TOP );
		$result->addValue( null, 'bottom', '2' );
		$result->addValue( null, 'foo', '2', ApiResult::OVERRIDE );
		$result->addValue( null, 'bar', '2', ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP );
		$this->assertSame( array( 0, 'top', 'foo', 'bar', 'bottom', ApiResult::META_TYPE ),
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
		$this->assertSame( array(
			'sub' => array( 'foo' => 1, 'bar' => 1 ),
			ApiResult::META_TYPE => 'assoc',
		), $result->getResultData() );

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
			ApiResult::META_TYPE => 'assoc',
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
			$result->addValue( null, null, $fh );
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
		try {
			$obj->file = $fh;
			$result->addValue( null, null, $obj );
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
			$result->addValue( null, null, INF );
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
		try {
			$result->addValue( null, null, NAN );
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
		$this->assertSame( array(
			'limits' => array( 'foo' => 12 ),
			ApiResult::META_TYPE => 'assoc',
		), $result->getResultData() );
		$result->addParsedLimit( 'foo', 13 );
		$this->assertSame( array(
			'limits' => array( 'foo' => 13 ),
			ApiResult::META_TYPE => 'assoc',
		), $result->getResultData() );
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

		$result = new ApiResult( 10 );
		$formatter = new ApiErrorFormatter( $result, Language::factory( 'en' ), 'none', false );
		$result->setErrorFormatter( $formatter );
		$this->assertFalse( $result->addValue( null, 'foo', '12345678901' ) );
		$this->assertTrue( $result->addValue( null, 'foo', '12345678901', ApiResult::NO_SIZE_CHECK ) );
		$this->assertSame( 0, $result->getSize() );
		$result->reset();
		$this->assertTrue( $result->addValue( null, 'foo', '1234567890' ) );
		$this->assertFalse( $result->addValue( null, 'foo', '1' ) );
		$result->removeValue( null, 'foo' );
		$this->assertTrue( $result->addValue( null, 'foo', '1' ) );

		$result = new ApiResult( 8388608 );
		$result2 = new ApiResult( 8388608 );
		$result2->addValue( null, 'foo', 'bar' );
		$result->addValue( null, 'baz', $result2 );
		$this->assertSame( array(
			'baz' => array(
				'foo' => 'bar',
				ApiResult::META_TYPE => 'assoc',
			),
			ApiResult::META_TYPE => 'assoc',
		), $result->getResultData() );

		$result = new ApiResult( 8388608 );
		$result->addValue( null, 'foo', "foo\x80bar" );
		$result->addValue( null, 'bar', "a\xcc\x81" );
		$result->addValue( null, 'baz', 74 );
		$result->addValue( null, null, "foo\x80bar" );
		$result->addValue( null, null, "a\xcc\x81" );
		$this->assertSame( array(
			'foo' => "foo\xef\xbf\xbdbar",
			'bar' => "\xc3\xa1",
			'baz' => 74,
			0 => "foo\xef\xbf\xbdbar",
			1 => "\xc3\xa1",
			ApiResult::META_TYPE => 'assoc',
		), $result->getResultData() );
	}

	/**
	 * @covers ApiResult
	 */
	public function testMetadata() {
		$arr = array( 'foo' => array( 'bar' => array() ) );
		$result = new ApiResult( 8388608 );
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

		ApiResult::setSubelementsList( $arr, 'foo' );
		ApiResult::setSubelementsList( $arr, array( 'bar', 'baz' ) );
		ApiResult::unsetSubelementsList( $arr, 'baz' );
		ApiResult::setIndexedTagNameRecursive( $arr, 'ritn' );
		ApiResult::setIndexedTagName( $arr, 'itn' );
		ApiResult::setPreserveKeysList( $arr, 'foo' );
		ApiResult::setPreserveKeysList( $arr, array( 'bar', 'baz' ) );
		ApiResult::unsetPreserveKeysList( $arr, 'baz' );
		ApiResult::setArrayTypeRecursive( $arr, 'default' );
		ApiResult::setArrayType( $arr, 'array' );
		$this->assertSame( $expect, $arr );

		$result->addSubelementsList( null, 'foo' );
		$result->addSubelementsList( null, array( 'bar', 'baz' ) );
		$result->removeSubelementsList( null, 'baz' );
		$result->addIndexedTagNameRecursive( null, 'ritn' );
		$result->addIndexedTagName( null, 'itn' );
		$result->addPreserveKeysList( null, 'foo' );
		$result->addPreserveKeysList( null, array( 'bar', 'baz' ) );
		$result->removePreserveKeysList( null, 'baz' );
		$result->addArrayTypeRecursive( null, 'default' );
		$result->addArrayType( null, 'array' );
		$this->assertEquals( $expect, $result->getResultData() );

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
	public function testUtilityFunctions() {
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
		), ApiResult::stripMetadata( $arr ), 'ApiResult::stripMetadata' );

		$metadata = array();
		$data = ApiResult::stripMetadataNonRecursive( $arr, $metadata );
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
		), $data, 'ApiResult::stripMetadataNonRecursive ($data)' );
		$this->assertEquals( array(
			ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => array( 'foo', 'bar', '_dummy2', 0 ),
			ApiResult::META_TYPE => 'array',
			'_dummy' => 'foobaz',
		), $metadata, 'ApiResult::stripMetadataNonRecursive ($metadata)' );

		$metadata = null;
		$data = ApiResult::stripMetadataNonRecursive( (object)$arr, $metadata );
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
		), $data, 'ApiResult::stripMetadataNonRecursive on object ($data)' );
		$this->assertEquals( array(
			ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => array( 'foo', 'bar', '_dummy2', 0 ),
			ApiResult::META_TYPE => 'array',
			'_dummy' => 'foobaz',
		), $metadata, 'ApiResult::stripMetadataNonRecursive on object ($metadata)' );
	}

	/**
	 * @covers ApiResult
	 * @dataProvider provideTransformations
	 * @param string $label
	 * @param array $input
	 * @param array $transforms
	 * @param array|Exception $expect
	 */
	public function testTransformations( $label, $input, $transforms, $expect ) {
		$result = new ApiResult( false );
		$result->addValue( null, 'test', $input );

		if ( $expect instanceof Exception ) {
			try {
				$output = $result->getResultData( 'test', $transforms );
				$this->fail( 'Expected exception not thrown', $label );
			} catch ( Exception $ex ) {
				$this->assertEquals( $ex, $expect, $label );
			}
		} else {
			$output = $result->getResultData( 'test', $transforms );
			$this->assertEquals( $expect, $output, $label );
		}
	}

	public function provideTransformations() {
		$kvp = function ( $keyKey, $key, $valKey, $value ) {
			return array(
				$keyKey => $key,
				$valKey => $value,
				ApiResult::META_PRESERVE_KEYS => array( $keyKey ),
				ApiResult::META_CONTENT => $valKey,
				ApiResult::META_TYPE => 'assoc',
			);
		};
		$typeArr = array(
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
		$stripArr = array(
			'foo' => array(
				'bar' => array( '_dummy' => 'foobaz' ),
				'baz' => array(
					ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
					ApiResult::META_INDEXED_TAG_NAME => 'itn',
					ApiResult::META_PRESERVE_KEYS => array( 'foo', 'bar', '_dummy2', 0 ),
					ApiResult::META_TYPE => 'array',
				),
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

		return array(
			array(
				'BC: META_BC_BOOLS',
				array(
					'BCtrue' => true,
					'BCfalse' => false,
					'true' => true,
					'false' => false,
					ApiResult::META_BC_BOOLS => array( 0, 'true', 'false' ),
				),
				array( 'BC' => array() ),
				array(
					'BCtrue' => '',
					'true' => true,
					'false' => false,
					ApiResult::META_BC_BOOLS => array( 0, 'true', 'false' ),
				)
			),
			array(
				'BC: META_BC_SUBELEMENTS',
				array(
					'bc' => 'foo',
					'nobc' => 'bar',
					ApiResult::META_BC_SUBELEMENTS => array( 'bc' ),
				),
				array( 'BC' => array() ),
				array(
					'bc' => array(
						'*' => 'foo',
						ApiResult::META_CONTENT => '*',
						ApiResult::META_TYPE => 'assoc',
					),
					'nobc' => 'bar',
					ApiResult::META_BC_SUBELEMENTS => array( 'bc' ),
				),
			),
			array(
				'BC: META_CONTENT',
				array(
					'content' => '!!!',
					ApiResult::META_CONTENT => 'content',
				),
				array( 'BC' => array() ),
				array(
					'*' => '!!!',
					ApiResult::META_CONTENT => '*',
				),
			),
			array(
				'BC: BCkvp type',
				array(
					'foo' => 'foo value',
					'bar' => 'bar value',
					'_baz' => 'baz value',
					ApiResult::META_TYPE => 'BCkvp',
					ApiResult::META_KVP_KEY_NAME => 'key',
					ApiResult::META_PRESERVE_KEYS => array( '_baz' ),
				),
				array( 'BC' => array() ),
				array(
					$kvp( 'key', 'foo', '*', 'foo value' ),
					$kvp( 'key', 'bar', '*', 'bar value' ),
					$kvp( 'key', '_baz', '*', 'baz value' ),
					ApiResult::META_TYPE => 'array',
					ApiResult::META_KVP_KEY_NAME => 'key',
					ApiResult::META_PRESERVE_KEYS => array( '_baz' ),
				),
			),
			array(
				'BC: BCarray type',
				array(
					ApiResult::META_TYPE => 'BCarray',
				),
				array( 'BC' => array() ),
				array(
					ApiResult::META_TYPE => 'default',
				),
			),
			array(
				'BC: BCassoc type',
				array(
					ApiResult::META_TYPE => 'BCassoc',
				),
				array( 'BC' => array() ),
				array(
					ApiResult::META_TYPE => 'default',
				),
			),
			array(
				'BC: BCkvp exception',
				array(
					ApiResult::META_TYPE => 'BCkvp',
				),
				array( 'BC' => array() ),
				new UnexpectedValueException(
					'Type "BCkvp" used without setting ApiResult::META_KVP_KEY_NAME metadata item'
				),
			),
			array(
				'BC: nobool, no*, nosub',
				array(
					'true' => true,
					'false' => false,
					'content' => 'content',
					ApiResult::META_CONTENT => 'content',
					'bc' => 'foo',
					ApiResult::META_BC_SUBELEMENTS => array( 'bc' ),
					'BCarray' => array( ApiResult::META_TYPE => 'BCarray' ),
					'BCassoc' => array( ApiResult::META_TYPE => 'BCassoc' ),
					'BCkvp' => array(
						'foo' => 'foo value',
						'bar' => 'bar value',
						'_baz' => 'baz value',
						ApiResult::META_TYPE => 'BCkvp',
						ApiResult::META_KVP_KEY_NAME => 'key',
						ApiResult::META_PRESERVE_KEYS => array( '_baz' ),
					),
				),
				array( 'BC' => array( 'nobool', 'no*', 'nosub' ) ),
				array(
					'true' => true,
					'false' => false,
					'content' => 'content',
					'bc' => 'foo',
					'BCarray' => array( ApiResult::META_TYPE => 'default' ),
					'BCassoc' => array( ApiResult::META_TYPE => 'default' ),
					'BCkvp' => array(
						$kvp( 'key', 'foo', '*', 'foo value' ),
						$kvp( 'key', 'bar', '*', 'bar value' ),
						$kvp( 'key', '_baz', '*', 'baz value' ),
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_KEY_NAME => 'key',
						ApiResult::META_PRESERVE_KEYS => array( '_baz' ),
					),
					ApiResult::META_CONTENT => 'content',
					ApiResult::META_BC_SUBELEMENTS => array( 'bc' ),
				),
			),

			array(
				'Types: Normal transform',
				$typeArr,
				array( 'Types' => array() ),
				array(
					'defaultArray' => array( 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ),
					'defaultAssoc' => array( 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'defaultAssoc2' => array( 2 => 'a', 3 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'array' => array( 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ),
					'BCarray' => array( 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ),
					'BCassoc' => array( 'a', 'b', 'c', ApiResult::META_TYPE => 'assoc' ),
					'assoc' => array( 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'kvp' => array( 'x' => 'a', 'y' => 'b',
						'z' => array( 'c', ApiResult::META_TYPE => 'array' ),
						ApiResult::META_TYPE => 'assoc'
					),
					'BCkvp' => array( 'x' => 'a', 'y' => 'b',
						ApiResult::META_TYPE => 'assoc',
						ApiResult::META_KVP_KEY_NAME => 'key',
					),
					'emptyDefault' => array( '_dummy' => 1, ApiResult::META_TYPE => 'array' ),
					'emptyAssoc' => array( '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ),
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => array( '_dummy' ),
					ApiResult::META_TYPE => 'assoc',
				),
			),
			array(
				'Types: AssocAsObject',
				$typeArr,
				array( 'Types' => array( 'AssocAsObject' => true ) ),
				(object)array(
					'defaultArray' => array( 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ),
					'defaultAssoc' => (object)array( 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'defaultAssoc2' => (object)array( 2 => 'a', 3 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'array' => array( 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ),
					'BCarray' => array( 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ),
					'BCassoc' => (object)array( 'a', 'b', 'c', ApiResult::META_TYPE => 'assoc' ),
					'assoc' => (object)array( 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'kvp' => (object)array( 'x' => 'a', 'y' => 'b',
						'z' => array( 'c', ApiResult::META_TYPE => 'array' ),
						ApiResult::META_TYPE => 'assoc'
					),
					'BCkvp' => (object)array( 'x' => 'a', 'y' => 'b',
						ApiResult::META_TYPE => 'assoc',
						ApiResult::META_KVP_KEY_NAME => 'key',
					),
					'emptyDefault' => array( '_dummy' => 1, ApiResult::META_TYPE => 'array' ),
					'emptyAssoc' => (object)array( '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ),
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => array( '_dummy' ),
					ApiResult::META_TYPE => 'assoc',
				),
			),
			array(
				'Types: ArmorKVP',
				$typeArr,
				array( 'Types' => array( 'ArmorKVP' => 'name' ) ),
				array(
					'defaultArray' => array( 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ),
					'defaultAssoc' => array( 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'defaultAssoc2' => array( 2 => 'a', 3 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'array' => array( 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ),
					'BCarray' => array( 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ),
					'BCassoc' => array( 'a', 'b', 'c', ApiResult::META_TYPE => 'assoc' ),
					'assoc' => array( 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'kvp' => array(
						$kvp( 'name', 'x', 'value', 'a' ),
						$kvp( 'name', 'y', 'value', 'b' ),
						$kvp( 'name', 'z', 'value', array( 'c', ApiResult::META_TYPE => 'array' ) ),
						ApiResult::META_TYPE => 'array'
					),
					'BCkvp' => array(
						$kvp( 'key', 'x', 'value', 'a' ),
						$kvp( 'key', 'y', 'value', 'b' ),
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_KEY_NAME => 'key',
					),
					'emptyDefault' => array( '_dummy' => 1, ApiResult::META_TYPE => 'array' ),
					'emptyAssoc' => array( '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ),
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => array( '_dummy' ),
					ApiResult::META_TYPE => 'assoc',
				),
			),
			array(
				'Types: ArmorKVP + BC',
				$typeArr,
				array( 'BC' => array(), 'Types' => array( 'ArmorKVP' => 'name' ) ),
				array(
					'defaultArray' => array( 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ),
					'defaultAssoc' => array( 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'defaultAssoc2' => array( 2 => 'a', 3 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'array' => array( 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ),
					'BCarray' => array( 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'BCassoc' => array( 'a', 'b', 'c', ApiResult::META_TYPE => 'array' ),
					'assoc' => array( 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'kvp' => array(
						$kvp( 'name', 'x', '*', 'a' ),
						$kvp( 'name', 'y', '*', 'b' ),
						$kvp( 'name', 'z', '*', array( 'c', ApiResult::META_TYPE => 'array' ) ),
						ApiResult::META_TYPE => 'array'
					),
					'BCkvp' => array(
						$kvp( 'key', 'x', '*', 'a' ),
						$kvp( 'key', 'y', '*', 'b' ),
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_KEY_NAME => 'key',
					),
					'emptyDefault' => array( '_dummy' => 1, ApiResult::META_TYPE => 'array' ),
					'emptyAssoc' => array( '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ),
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => array( '_dummy' ),
					ApiResult::META_TYPE => 'assoc',
				),
			),
			array(
				'Types: ArmorKVP + AssocAsObject',
				$typeArr,
				array( 'Types' => array( 'ArmorKVP' => 'name', 'AssocAsObject' => true ) ),
				(object)array(
					'defaultArray' => array( 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ),
					'defaultAssoc' => (object)array( 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'defaultAssoc2' => (object)array( 2 => 'a', 3 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'array' => array( 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ),
					'BCarray' => array( 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ),
					'BCassoc' => (object)array( 'a', 'b', 'c', ApiResult::META_TYPE => 'assoc' ),
					'assoc' => (object)array( 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ),
					'kvp' => array(
						(object)$kvp( 'name', 'x', 'value', 'a' ),
						(object)$kvp( 'name', 'y', 'value', 'b' ),
						(object)$kvp( 'name', 'z', 'value', array( 'c', ApiResult::META_TYPE => 'array' ) ),
						ApiResult::META_TYPE => 'array'
					),
					'BCkvp' => array(
						(object)$kvp( 'key', 'x', 'value', 'a' ),
						(object)$kvp( 'key', 'y', 'value', 'b' ),
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_KEY_NAME => 'key',
					),
					'emptyDefault' => array( '_dummy' => 1, ApiResult::META_TYPE => 'array' ),
					'emptyAssoc' => (object)array( '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ),
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => array( '_dummy' ),
					ApiResult::META_TYPE => 'assoc',
				),
			),
			array(
				'Types: BCkvp exception',
				array(
					ApiResult::META_TYPE => 'BCkvp',
				),
				array( 'Types' => array() ),
				new UnexpectedValueException(
					'Type "BCkvp" used without setting ApiResult::META_KVP_KEY_NAME metadata item'
				),
			),

			array(
				'Strip: With ArmorKVP + AssocAsObject transforms',
				$typeArr,
				array( 'Types' => array( 'ArmorKVP' => 'name', 'AssocAsObject' => true ), 'Strip' => 'all' ),
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
			),

			array(
				'Strip: all',
				$stripArr,
				array( 'Strip' => 'all' ),
				array(
					'foo' => array(
						'bar' => array(),
						'baz' => array(),
						'x' => 'ok',
					),
					'_dummy2' => 'foobaz!',
				),
			),
			array(
				'Strip: base',
				$stripArr,
				array( 'Strip' => 'base' ),
				array(
					'foo' => array(
						'bar' => array( '_dummy' => 'foobaz' ),
						'baz' => array(
							ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
							ApiResult::META_INDEXED_TAG_NAME => 'itn',
							ApiResult::META_PRESERVE_KEYS => array( 'foo', 'bar', '_dummy2', 0 ),
							ApiResult::META_TYPE => 'array',
						),
						'x' => 'ok',
						'_dummy' => 'foobaz',
					),
					'_dummy2' => 'foobaz!',
				),
			),
			array(
				'Strip: bc',
				$stripArr,
				array( 'Strip' => 'bc' ),
				array(
					'foo' => array(
						'bar' => array(),
						'baz' => array(
							ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
							ApiResult::META_INDEXED_TAG_NAME => 'itn',
						),
						'x' => 'ok',
					),
					'_dummy2' => 'foobaz!',
					ApiResult::META_SUBELEMENTS => array( 'foo', 'bar' ),
					ApiResult::META_INDEXED_TAG_NAME => 'itn',
				),
			),

			array(
				'Custom transform',
				array(
					'foo' => '?',
					'bar' => '?',
					'_dummy' => '?',
					'_dummy2' => '?',
					'_dummy3' => '?',
					ApiResult::META_CONTENT => 'foo',
					ApiResult::META_PRESERVE_KEYS => array( '_dummy2', '_dummy3' ),
				),
				array(
					'Custom' => array( $this, 'customTransform' ),
					'BC' => array(),
					'Types' => array(),
					'Strip' => 'all'
				),
				array(
					'*' => 'FOO',
					'bar' => 'BAR',
					'baz' => array( 'a', 'b' ),
					'_dummy2' => '_DUMMY2',
					'_dummy3' => '_DUMMY3',
					ApiResult::META_CONTENT => 'bar',
				),
			),
		);

	}

	/**
	 * Custom transformer for testTransformations
	 * @param array &$data
	 * @param array &$metadata
	 */
	public function customTransform( &$data, &$metadata ) {
		// Prevent recursion
		if ( isset( $metadata['_added'] ) ) {
			$metadata[ApiResult::META_TYPE] = 'array';
			return;
		}

		foreach ( $data as $k => $v ) {
			$data[$k] = strtoupper( $k );
		}
		$data['baz'] = array( '_added' => 1, 'z' => 'b', 'y' => 'a' );
		$metadata[ApiResult::META_PRESERVE_KEYS][0] = '_dummy';
		$data[ApiResult::META_CONTENT] = 'bar';
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
			if ( preg_match( '/Use of ApiMain to ApiResult::__construct was deprecated in MediaWiki \d+.\d+\./', $errstr ) ) {
				return true;
			}
			return false;
		} );
		$reset = new ScopedCallback( 'restore_error_handler' );

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setConfig( new HashConfig( array(
			'APIModules' => array(),
			'APIFormatModules' => array(),
			'APIMaxResultSize' => 42,
		) ) );
		$main = new ApiMain( $context );
		$result = TestingAccessWrapper::newFromObject( new ApiResult( $main ) );
		$this->assertSame( 42, $result->maxSize );
		$this->assertSame( $main->getErrorFormatter(), $result->errorFormatter );
		$this->assertSame( $main, $result->mainForContinuation );

		$result = new ApiResult( 8388608 );

		$result->addContentValue( null, 'test', 'content' );
		$result->addContentValue( array( 'foo', 'bar' ), 'test', 'content' );
		$result->addIndexedTagName( null, 'itn' );
		$result->addSubelementsList( null, array( 'sub' ) );
		$this->assertSame( array(
			'foo' => array(
				'bar' => array(
					'*' => 'content',
				),
			),
			'*' => 'content',
		), $result->getData() );
		$result->setRawMode();
		$this->assertSame( array(
			'foo' => array(
				'bar' => array(
					'*' => 'content',
				),
			),
			'*' => 'content',
			'_element' => 'itn',
			'_subelements' => array( 'sub' ),
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

		$result = new ApiResult( 3 );
		$formatter = new ApiErrorFormatter_BackCompat( $result );
		$result->setErrorFormatter( $formatter );
		$result->disableSizeCheck();
		$this->assertTrue( $result->addValue( null, 'foo', '1234567890' ) );
		$result->enableSizeCheck();
		$this->assertSame( 0, $result->getSize() );
		$this->assertFalse( $result->addValue( null, 'foo', '1234567890' ) );

		$arr = array( 'foo' => array( 'bar' => 1 ) );
		$result->setIndexedTagName_recursive( $arr, 'itn' );
		$this->assertSame( array(
			'foo' => array(
				'bar' => 1,
				ApiResult::META_INDEXED_TAG_NAME => 'itn'
			),
		), $arr );

		$status = Status::newGood();
		$status->fatal( 'parentheses', '1' );
		$status->fatal( 'parentheses', '2' );
		$status->warning( 'parentheses', '3' );
		$status->warning( 'parentheses', '4' );
		$this->assertSame( array(
			array(
				'type' => 'error',
				'message' => 'parentheses',
				'params' => array(
					0 => '1',
					ApiResult::META_INDEXED_TAG_NAME => 'param',
				),
			),
			array(
				'type' => 'error',
				'message' => 'parentheses',
				'params' => array(
					0 => '2',
					ApiResult::META_INDEXED_TAG_NAME => 'param',
				),
			),
			ApiResult::META_INDEXED_TAG_NAME => 'error',
		), $result->convertStatusToArray( $status, 'error' ) );
		$this->assertSame( array(
			array(
				'type' => 'warning',
				'message' => 'parentheses',
				'params' => array(
					0 => '3',
					ApiResult::META_INDEXED_TAG_NAME => 'param',
				),
			),
			array(
				'type' => 'warning',
				'message' => 'parentheses',
				'params' => array(
					0 => '4',
					ApiResult::META_INDEXED_TAG_NAME => 'param',
				),
			),
			ApiResult::META_INDEXED_TAG_NAME => 'warning',
		), $result->convertStatusToArray( $status, 'warning' ) );
	}

	/**
	 * @covers ApiResult
	 */
	public function testDeprecatedContinuation() {
		// Ignore ApiResult deprecation warnings during this test
		set_error_handler( function ( $errno, $errstr ) use ( &$warnings ) {
			if ( preg_match( '/Use of ApiResult::\S+ was deprecated in MediaWiki \d+.\d+\./', $errstr ) ) {
				return true;
			}
			return false;
		} );

		$reset = new ScopedCallback( 'restore_error_handler' );
		$allModules = array(
			new MockApiQueryBase( 'mock1' ),
			new MockApiQueryBase( 'mock2' ),
			new MockApiQueryBase( 'mocklist' ),
		);
		$generator = new MockApiQueryBase( 'generator' );

		$main = new ApiMain( RequestContext::getMain() );
		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->setContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$result->setContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->setGeneratorContinueParam( $generator, 'gcontinue', 3 );
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
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->setContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$result->setGeneratorContinueParam( $generator, 'gcontinue', array( 3, 4 ) );
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
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->setContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->setGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'mlcontinue' => 2,
			'gcontinue' => 3,
			'continue' => 'gcontinue||',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'mocklist' => array( 'mlcontinue' => 2 ),
			'generator' => array( 'gcontinue' => 3 ),
		), $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->setGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'gcontinue' => 3,
			'continue' => 'gcontinue||mocklist',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'generator' => array( 'gcontinue' => 3 ),
		), $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->setContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$result->setContinueParam( $allModules[2], 'mlcontinue', 2 );
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
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->setContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
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
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->setContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( array(
			'mlcontinue' => 2,
			'continue' => '-||mock1|mock2',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( array(
			'mocklist' => array( 'mlcontinue' => 2 ),
		), $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( array( false, $allModules ), $ret );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( null, $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( null, $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( '||mock2', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame(
			array( false, array_values( array_diff_key( $allModules, array( 1 => 1 ) ) ) ),
			$ret
		);
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( '-||', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame(
			array( true, array_values( array_diff_key( $allModules, array( 0 => 0, 1 => 1 ) ) ) ),
			$ret
		);
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
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
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$result->beginContinuation( '||mock2', array_slice( $allModules, 0, 2 ), array( 'mock1', 'mock2' ) );
		try {
			$result->setContinueParam( $allModules[1], 'm2continue', 1 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Module \'mock2\' was not supposed to have been executed, but it was executed anyway',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		try {
			$result->setContinueParam( $allModules[2], 'mlcontinue', 1 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Module \'mocklist\' called ApiContinuationManager::addContinueParam but was not passed to ApiContinuationManager::__construct',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		$main->setContinuationManager( null );

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
