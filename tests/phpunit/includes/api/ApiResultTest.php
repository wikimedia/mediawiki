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
		$arr = [];

		ApiResult::setValue( $arr, 'setValue', '1' );

		ApiResult::setValue( $arr, null, 'unnamed 1' );
		ApiResult::setValue( $arr, null, 'unnamed 2' );

		ApiResult::setValue( $arr, 'deleteValue', '2' );
		ApiResult::unsetValue( $arr, 'deleteValue' );

		ApiResult::setContentValue( $arr, 'setContentValue', '3' );

		$this->assertSame( [
			'setValue' => '1',
			'unnamed 1',
			'unnamed 2',
			ApiResult::META_CONTENT => 'setContentValue',
			'setContentValue' => '3',
		], $arr );

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

		$arr = [ 'foo' => 1, 'bar' => 1 ];
		ApiResult::setValue( $arr, 'top', '2', ApiResult::ADD_ON_TOP );
		ApiResult::setValue( $arr, null, '2', ApiResult::ADD_ON_TOP );
		ApiResult::setValue( $arr, 'bottom', '2' );
		ApiResult::setValue( $arr, 'foo', '2', ApiResult::OVERRIDE );
		ApiResult::setValue( $arr, 'bar', '2', ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP );
		$this->assertSame( [ 0, 'top', 'foo', 'bar', 'bottom' ], array_keys( $arr ) );

		$arr = [];
		ApiResult::setValue( $arr, 'sub', [ 'foo' => 1 ] );
		ApiResult::setValue( $arr, 'sub', [ 'bar' => 1 ] );
		$this->assertSame( [ 'sub' => [ 'foo' => 1, 'bar' => 1 ] ], $arr );

		try {
			ApiResult::setValue( $arr, 'sub', [ 'foo' => 2, 'baz' => 2 ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame(
				'Conflicting keys (foo) when attempting to merge element sub',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$arr = [];
		$title = Title::newFromText( "MediaWiki:Foobar" );
		$obj = new stdClass;
		$obj->foo = 1;
		$obj->bar = 2;
		ApiResult::setValue( $arr, 'title', $title );
		ApiResult::setValue( $arr, 'obj', $obj );
		$this->assertSame( [
			'title' => (string)$title,
			'obj' => [ 'foo' => 1, 'bar' => 2, ApiResult::META_TYPE => 'assoc' ],
		], $arr );

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

		ApiResult::setValue( $arr, null, NAN, ApiResult::NO_VALIDATE );

		try {
			ApiResult::setValue( $arr, null, NAN, ApiResult::NO_SIZE_CHECK );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Cannot add non-finite floats to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$arr = [];
		$result2 = new ApiResult( 8388608 );
		$result2->addValue( null, 'foo', 'bar' );
		ApiResult::setValue( $arr, 'baz', $result2 );
		$this->assertSame( [
			'baz' => [
				ApiResult::META_TYPE => 'assoc',
				'foo' => 'bar',
			]
		], $arr );

		$arr = [];
		ApiResult::setValue( $arr, 'foo', "foo\x80bar" );
		ApiResult::setValue( $arr, 'bar', "a\xcc\x81" );
		ApiResult::setValue( $arr, 'baz', 74 );
		ApiResult::setValue( $arr, null, "foo\x80bar" );
		ApiResult::setValue( $arr, null, "a\xcc\x81" );
		$this->assertSame( [
			'foo' => "foo\xef\xbf\xbdbar",
			'bar' => "\xc3\xa1",
			'baz' => 74,
			0 => "foo\xef\xbf\xbdbar",
			1 => "\xc3\xa1",
		], $arr );

		$obj = new stdClass;
		$obj->{'1'} = 'one';
		$arr = [];
		ApiResult::setValue( $arr, 'foo', $obj );
		$this->assertSame( [
			'foo' => [
				1 => 'one',
				ApiResult::META_TYPE => 'assoc',
			]
		], $arr );
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

		$result->addValue( [ 'a', 'b' ], 'deleteValue', '3' );
		$result->removeValue( [ 'a', 'b', 'deleteValue' ], null, '3' );

		$result->addContentValue( null, 'setContentValue', '3' );

		$this->assertSame( [
			'setValue' => '1',
			'unnamed 1',
			'unnamed 2',
			'a' => [ 'b' => [] ],
			'setContentValue' => '3',
			ApiResult::META_TYPE => 'assoc',
			ApiResult::META_CONTENT => 'setContentValue',
		], $result->getResultData() );
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
		$this->assertSame( '99', $result->getResultData( [ 'setValue' ] ) );

		$result->addContentValue( null, 'setContentValue2', '99', ApiResult::OVERRIDE );
		$this->assertSame( 'setContentValue2',
			$result->getResultData( [ ApiResult::META_CONTENT ] ) );

		$result->reset();
		$this->assertSame( [
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData() );
		$this->assertSame( 0, $result->getSize() );

		$result->addValue( null, 'foo', 1 );
		$result->addValue( null, 'bar', 1 );
		$result->addValue( null, 'top', '2', ApiResult::ADD_ON_TOP );
		$result->addValue( null, null, '2', ApiResult::ADD_ON_TOP );
		$result->addValue( null, 'bottom', '2' );
		$result->addValue( null, 'foo', '2', ApiResult::OVERRIDE );
		$result->addValue( null, 'bar', '2', ApiResult::OVERRIDE | ApiResult::ADD_ON_TOP );
		$this->assertSame( [ 0, 'top', 'foo', 'bar', 'bottom', ApiResult::META_TYPE ],
			array_keys( $result->getResultData() ) );

		$result->reset();
		$result->addValue( null, 'foo', [ 'bar' => 1 ] );
		$result->addValue( [ 'foo', 'top' ], 'x', 2, ApiResult::ADD_ON_TOP );
		$result->addValue( [ 'foo', 'bottom' ], 'x', 2 );
		$this->assertSame( [ 'top', 'bar', 'bottom' ],
			array_keys( $result->getResultData( [ 'foo' ] ) ) );

		$result->reset();
		$result->addValue( null, 'sub', [ 'foo' => 1 ] );
		$result->addValue( null, 'sub', [ 'bar' => 1 ] );
		$this->assertSame( [
			'sub' => [ 'foo' => 1, 'bar' => 1 ],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData() );

		try {
			$result->addValue( null, 'sub', [ 'foo' => 2, 'baz' => 2 ] );
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
		$this->assertSame( [
			'title' => (string)$title,
			'obj' => [ 'foo' => 1, 'bar' => 2, ApiResult::META_TYPE => 'assoc' ],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData() );

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

		$result->addValue( null, null, NAN, ApiResult::NO_VALIDATE );

		try {
			$result->addValue( null, null, NAN, ApiResult::NO_SIZE_CHECK );
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
		$this->assertSame( [
			'limits' => [ 'foo' => 12 ],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData() );
		$result->addParsedLimit( 'foo', 13 );
		$this->assertSame( [
			'limits' => [ 'foo' => 13 ],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData() );
		$this->assertSame( null, $result->getResultData( [ 'foo', 'bar', 'baz' ] ) );
		$this->assertSame( 13, $result->getResultData( [ 'limits', 'foo' ] ) );
		try {
			$result->getResultData( [ 'limits', 'foo', 'bar' ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Path limits.foo is not an array',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		// Add two values and some metadata, but ensure metadata is not counted
		$result = new ApiResult( 100 );
		$obj = [ 'attr' => '12345' ];
		ApiResult::setContentValue( $obj, 'content', '1234567890' );
		$this->assertTrue( $result->addValue( null, 'foo', $obj ) );
		$this->assertSame( 15, $result->getSize() );

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

		$result = new ApiResult( 10 );
		$obj = new ApiResultTestSerializableObject( 'ok' );
		$obj->foobar = 'foobaz';
		$this->assertTrue( $result->addValue( null, 'foo', $obj ) );
		$this->assertSame( 2, $result->getSize() );

		$result = new ApiResult( 8388608 );
		$result2 = new ApiResult( 8388608 );
		$result2->addValue( null, 'foo', 'bar' );
		$result->addValue( null, 'baz', $result2 );
		$this->assertSame( [
			'baz' => [
				'foo' => 'bar',
				ApiResult::META_TYPE => 'assoc',
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData() );

		$result = new ApiResult( 8388608 );
		$result->addValue( null, 'foo', "foo\x80bar" );
		$result->addValue( null, 'bar', "a\xcc\x81" );
		$result->addValue( null, 'baz', 74 );
		$result->addValue( null, null, "foo\x80bar" );
		$result->addValue( null, null, "a\xcc\x81" );
		$this->assertSame( [
			'foo' => "foo\xef\xbf\xbdbar",
			'bar' => "\xc3\xa1",
			'baz' => 74,
			0 => "foo\xef\xbf\xbdbar",
			1 => "\xc3\xa1",
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData() );

		$result = new ApiResult( 8388608 );
		$obj = new stdClass;
		$obj->{'1'} = 'one';
		$arr = [];
		$result->addValue( $arr, 'foo', $obj );
		$this->assertSame( [
			'foo' => [
				1 => 'one',
				ApiResult::META_TYPE => 'assoc',
			],
			ApiResult::META_TYPE => 'assoc',
		], $result->getResultData() );
	}

	/**
	 * @covers ApiResult
	 */
	public function testMetadata() {
		$arr = [ 'foo' => [ 'bar' => [] ] ];
		$result = new ApiResult( 8388608 );
		$result->addValue( null, 'foo', [ 'bar' => [] ] );

		$expect = [
			'foo' => [
				'bar' => [
					ApiResult::META_INDEXED_TAG_NAME => 'ritn',
					ApiResult::META_TYPE => 'default',
				],
				ApiResult::META_INDEXED_TAG_NAME => 'ritn',
				ApiResult::META_TYPE => 'default',
			],
			ApiResult::META_SUBELEMENTS => [ 'foo', 'bar' ],
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => [ 'foo', 'bar' ],
			ApiResult::META_TYPE => 'array',
		];

		ApiResult::setSubelementsList( $arr, 'foo' );
		ApiResult::setSubelementsList( $arr, [ 'bar', 'baz' ] );
		ApiResult::unsetSubelementsList( $arr, 'baz' );
		ApiResult::setIndexedTagNameRecursive( $arr, 'ritn' );
		ApiResult::setIndexedTagName( $arr, 'itn' );
		ApiResult::setPreserveKeysList( $arr, 'foo' );
		ApiResult::setPreserveKeysList( $arr, [ 'bar', 'baz' ] );
		ApiResult::unsetPreserveKeysList( $arr, 'baz' );
		ApiResult::setArrayTypeRecursive( $arr, 'default' );
		ApiResult::setArrayType( $arr, 'array' );
		$this->assertSame( $expect, $arr );

		$result->addSubelementsList( null, 'foo' );
		$result->addSubelementsList( null, [ 'bar', 'baz' ] );
		$result->removeSubelementsList( null, 'baz' );
		$result->addIndexedTagNameRecursive( null, 'ritn' );
		$result->addIndexedTagName( null, 'itn' );
		$result->addPreserveKeysList( null, 'foo' );
		$result->addPreserveKeysList( null, [ 'bar', 'baz' ] );
		$result->removePreserveKeysList( null, 'baz' );
		$result->addArrayTypeRecursive( null, 'default' );
		$result->addArrayType( null, 'array' );
		$this->assertEquals( $expect, $result->getResultData() );

		$arr = [ 'foo' => [ 'bar' => [] ] ];
		$expect = [
			'foo' => [
				'bar' => [
					ApiResult::META_TYPE => 'kvp',
					ApiResult::META_KVP_KEY_NAME => 'key',
				],
				ApiResult::META_TYPE => 'kvp',
				ApiResult::META_KVP_KEY_NAME => 'key',
			],
			ApiResult::META_TYPE => 'BCkvp',
			ApiResult::META_KVP_KEY_NAME => 'bc',
		];
		ApiResult::setArrayTypeRecursive( $arr, 'kvp', 'key' );
		ApiResult::setArrayType( $arr, 'BCkvp', 'bc' );
		$this->assertSame( $expect, $arr );
	}

	/**
	 * @covers ApiResult
	 */
	public function testUtilityFunctions() {
		$arr = [
			'foo' => [
				'bar' => [ '_dummy' => 'foobaz' ],
				'bar2' => (object)[ '_dummy' => 'foobaz' ],
				'x' => 'ok',
				'_dummy' => 'foobaz',
			],
			'foo2' => (object)[
				'bar' => [ '_dummy' => 'foobaz' ],
				'bar2' => (object)[ '_dummy' => 'foobaz' ],
				'x' => 'ok',
				'_dummy' => 'foobaz',
			],
			ApiResult::META_SUBELEMENTS => [ 'foo', 'bar' ],
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => [ 'foo', 'bar', '_dummy2', 0 ],
			ApiResult::META_TYPE => 'array',
			'_dummy' => 'foobaz',
			'_dummy2' => 'foobaz!',
		];
		$this->assertEquals( [
			'foo' => [
				'bar' => [],
				'bar2' => (object)[],
				'x' => 'ok',
			],
			'foo2' => (object)[
				'bar' => [],
				'bar2' => (object)[],
				'x' => 'ok',
			],
			'_dummy2' => 'foobaz!',
		], ApiResult::stripMetadata( $arr ), 'ApiResult::stripMetadata' );

		$metadata = [];
		$data = ApiResult::stripMetadataNonRecursive( $arr, $metadata );
		$this->assertEquals( [
			'foo' => [
				'bar' => [ '_dummy' => 'foobaz' ],
				'bar2' => (object)[ '_dummy' => 'foobaz' ],
				'x' => 'ok',
				'_dummy' => 'foobaz',
			],
			'foo2' => (object)[
				'bar' => [ '_dummy' => 'foobaz' ],
				'bar2' => (object)[ '_dummy' => 'foobaz' ],
				'x' => 'ok',
				'_dummy' => 'foobaz',
			],
			'_dummy2' => 'foobaz!',
		], $data, 'ApiResult::stripMetadataNonRecursive ($data)' );
		$this->assertEquals( [
			ApiResult::META_SUBELEMENTS => [ 'foo', 'bar' ],
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => [ 'foo', 'bar', '_dummy2', 0 ],
			ApiResult::META_TYPE => 'array',
			'_dummy' => 'foobaz',
		], $metadata, 'ApiResult::stripMetadataNonRecursive ($metadata)' );

		$metadata = null;
		$data = ApiResult::stripMetadataNonRecursive( (object)$arr, $metadata );
		$this->assertEquals( (object)[
			'foo' => [
				'bar' => [ '_dummy' => 'foobaz' ],
				'bar2' => (object)[ '_dummy' => 'foobaz' ],
				'x' => 'ok',
				'_dummy' => 'foobaz',
			],
			'foo2' => (object)[
				'bar' => [ '_dummy' => 'foobaz' ],
				'bar2' => (object)[ '_dummy' => 'foobaz' ],
				'x' => 'ok',
				'_dummy' => 'foobaz',
			],
			'_dummy2' => 'foobaz!',
		], $data, 'ApiResult::stripMetadataNonRecursive on object ($data)' );
		$this->assertEquals( [
			ApiResult::META_SUBELEMENTS => [ 'foo', 'bar' ],
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => [ 'foo', 'bar', '_dummy2', 0 ],
			ApiResult::META_TYPE => 'array',
			'_dummy' => 'foobaz',
		], $metadata, 'ApiResult::stripMetadataNonRecursive on object ($metadata)' );
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
			return [
				$keyKey => $key,
				$valKey => $value,
				ApiResult::META_PRESERVE_KEYS => [ $keyKey ],
				ApiResult::META_CONTENT => $valKey,
				ApiResult::META_TYPE => 'assoc',
			];
		};
		$typeArr = [
			'defaultArray' => [ 2 => 'a', 0 => 'b', 1 => 'c' ],
			'defaultAssoc' => [ 'x' => 'a', 1 => 'b', 0 => 'c' ],
			'defaultAssoc2' => [ 2 => 'a', 3 => 'b', 0 => 'c' ],
			'array' => [ 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'array' ],
			'BCarray' => [ 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'BCarray' ],
			'BCassoc' => [ 'a', 'b', 'c', ApiResult::META_TYPE => 'BCassoc' ],
			'assoc' => [ 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ],
			'kvp' => [ 'x' => 'a', 'y' => 'b', 'z' => [ 'c' ], ApiResult::META_TYPE => 'kvp' ],
			'BCkvp' => [ 'x' => 'a', 'y' => 'b',
				ApiResult::META_TYPE => 'BCkvp',
				ApiResult::META_KVP_KEY_NAME => 'key',
			],
			'kvpmerge' => [ 'x' => 'a', 'y' => [ 'b' ], 'z' => [ 'c' => 'd' ],
				ApiResult::META_TYPE => 'kvp',
				ApiResult::META_KVP_MERGE => true,
			],
			'emptyDefault' => [ '_dummy' => 1 ],
			'emptyAssoc' => [ '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ],
			'_dummy' => 1,
			ApiResult::META_PRESERVE_KEYS => [ '_dummy' ],
		];
		$stripArr = [
			'foo' => [
				'bar' => [ '_dummy' => 'foobaz' ],
				'baz' => [
					ApiResult::META_SUBELEMENTS => [ 'foo', 'bar' ],
					ApiResult::META_INDEXED_TAG_NAME => 'itn',
					ApiResult::META_PRESERVE_KEYS => [ 'foo', 'bar', '_dummy2', 0 ],
					ApiResult::META_TYPE => 'array',
				],
				'x' => 'ok',
				'_dummy' => 'foobaz',
			],
			ApiResult::META_SUBELEMENTS => [ 'foo', 'bar' ],
			ApiResult::META_INDEXED_TAG_NAME => 'itn',
			ApiResult::META_PRESERVE_KEYS => [ 'foo', 'bar', '_dummy2', 0 ],
			ApiResult::META_TYPE => 'array',
			'_dummy' => 'foobaz',
			'_dummy2' => 'foobaz!',
		];

		return [
			[
				'BC: META_BC_BOOLS',
				[
					'BCtrue' => true,
					'BCfalse' => false,
					'true' => true,
					'false' => false,
					ApiResult::META_BC_BOOLS => [ 0, 'true', 'false' ],
				],
				[ 'BC' => [] ],
				[
					'BCtrue' => '',
					'true' => true,
					'false' => false,
					ApiResult::META_BC_BOOLS => [ 0, 'true', 'false' ],
				]
			],
			[
				'BC: META_BC_SUBELEMENTS',
				[
					'bc' => 'foo',
					'nobc' => 'bar',
					ApiResult::META_BC_SUBELEMENTS => [ 'bc' ],
				],
				[ 'BC' => [] ],
				[
					'bc' => [
						'*' => 'foo',
						ApiResult::META_CONTENT => '*',
						ApiResult::META_TYPE => 'assoc',
					],
					'nobc' => 'bar',
					ApiResult::META_BC_SUBELEMENTS => [ 'bc' ],
				],
			],
			[
				'BC: META_CONTENT',
				[
					'content' => '!!!',
					ApiResult::META_CONTENT => 'content',
				],
				[ 'BC' => [] ],
				[
					'*' => '!!!',
					ApiResult::META_CONTENT => '*',
				],
			],
			[
				'BC: BCkvp type',
				[
					'foo' => 'foo value',
					'bar' => 'bar value',
					'_baz' => 'baz value',
					ApiResult::META_TYPE => 'BCkvp',
					ApiResult::META_KVP_KEY_NAME => 'key',
					ApiResult::META_PRESERVE_KEYS => [ '_baz' ],
				],
				[ 'BC' => [] ],
				[
					$kvp( 'key', 'foo', '*', 'foo value' ),
					$kvp( 'key', 'bar', '*', 'bar value' ),
					$kvp( 'key', '_baz', '*', 'baz value' ),
					ApiResult::META_TYPE => 'array',
					ApiResult::META_KVP_KEY_NAME => 'key',
					ApiResult::META_PRESERVE_KEYS => [ '_baz' ],
				],
			],
			[
				'BC: BCarray type',
				[
					ApiResult::META_TYPE => 'BCarray',
				],
				[ 'BC' => [] ],
				[
					ApiResult::META_TYPE => 'default',
				],
			],
			[
				'BC: BCassoc type',
				[
					ApiResult::META_TYPE => 'BCassoc',
				],
				[ 'BC' => [] ],
				[
					ApiResult::META_TYPE => 'default',
				],
			],
			[
				'BC: BCkvp exception',
				[
					ApiResult::META_TYPE => 'BCkvp',
				],
				[ 'BC' => [] ],
				new UnexpectedValueException(
					'Type "BCkvp" used without setting ApiResult::META_KVP_KEY_NAME metadata item'
				),
			],
			[
				'BC: nobool, no*, nosub',
				[
					'true' => true,
					'false' => false,
					'content' => 'content',
					ApiResult::META_CONTENT => 'content',
					'bc' => 'foo',
					ApiResult::META_BC_SUBELEMENTS => [ 'bc' ],
					'BCarray' => [ ApiResult::META_TYPE => 'BCarray' ],
					'BCassoc' => [ ApiResult::META_TYPE => 'BCassoc' ],
					'BCkvp' => [
						'foo' => 'foo value',
						'bar' => 'bar value',
						'_baz' => 'baz value',
						ApiResult::META_TYPE => 'BCkvp',
						ApiResult::META_KVP_KEY_NAME => 'key',
						ApiResult::META_PRESERVE_KEYS => [ '_baz' ],
					],
				],
				[ 'BC' => [ 'nobool', 'no*', 'nosub' ] ],
				[
					'true' => true,
					'false' => false,
					'content' => 'content',
					'bc' => 'foo',
					'BCarray' => [ ApiResult::META_TYPE => 'default' ],
					'BCassoc' => [ ApiResult::META_TYPE => 'default' ],
					'BCkvp' => [
						$kvp( 'key', 'foo', '*', 'foo value' ),
						$kvp( 'key', 'bar', '*', 'bar value' ),
						$kvp( 'key', '_baz', '*', 'baz value' ),
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_KEY_NAME => 'key',
						ApiResult::META_PRESERVE_KEYS => [ '_baz' ],
					],
					ApiResult::META_CONTENT => 'content',
					ApiResult::META_BC_SUBELEMENTS => [ 'bc' ],
				],
			],

			[
				'Types: Normal transform',
				$typeArr,
				[ 'Types' => [] ],
				[
					'defaultArray' => [ 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ],
					'defaultAssoc' => [ 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'defaultAssoc2' => [ 2 => 'a', 3 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'array' => [ 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ],
					'BCarray' => [ 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ],
					'BCassoc' => [ 'a', 'b', 'c', ApiResult::META_TYPE => 'assoc' ],
					'assoc' => [ 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'kvp' => [ 'x' => 'a', 'y' => 'b',
						'z' => [ 'c', ApiResult::META_TYPE => 'array' ],
						ApiResult::META_TYPE => 'assoc'
					],
					'BCkvp' => [ 'x' => 'a', 'y' => 'b',
						ApiResult::META_TYPE => 'assoc',
						ApiResult::META_KVP_KEY_NAME => 'key',
					],
					'kvpmerge' => [
						'x' => 'a',
						'y' => [ 'b', ApiResult::META_TYPE => 'array' ],
						'z' => [ 'c' => 'd', ApiResult::META_TYPE => 'assoc' ],
						ApiResult::META_TYPE => 'assoc',
						ApiResult::META_KVP_MERGE => true,
					],
					'emptyDefault' => [ '_dummy' => 1, ApiResult::META_TYPE => 'array' ],
					'emptyAssoc' => [ '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ],
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => [ '_dummy' ],
					ApiResult::META_TYPE => 'assoc',
				],
			],
			[
				'Types: AssocAsObject',
				$typeArr,
				[ 'Types' => [ 'AssocAsObject' => true ] ],
				(object)[
					'defaultArray' => [ 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ],
					'defaultAssoc' => (object)[ 'x' => 'a',
						1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc'
					],
					'defaultAssoc2' => (object)[ 2 => 'a', 3 => 'b',
						0 => 'c', ApiResult::META_TYPE => 'assoc'
					],
					'array' => [ 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ],
					'BCarray' => [ 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ],
					'BCassoc' => (object)[ 'a', 'b', 'c', ApiResult::META_TYPE => 'assoc' ],
					'assoc' => (object)[ 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'kvp' => (object)[ 'x' => 'a', 'y' => 'b',
						'z' => [ 'c', ApiResult::META_TYPE => 'array' ],
						ApiResult::META_TYPE => 'assoc'
					],
					'BCkvp' => (object)[ 'x' => 'a', 'y' => 'b',
						ApiResult::META_TYPE => 'assoc',
						ApiResult::META_KVP_KEY_NAME => 'key',
					],
					'kvpmerge' => (object)[
						'x' => 'a',
						'y' => [ 'b', ApiResult::META_TYPE => 'array' ],
						'z' => (object)[ 'c' => 'd', ApiResult::META_TYPE => 'assoc' ],
						ApiResult::META_TYPE => 'assoc',
						ApiResult::META_KVP_MERGE => true,
					],
					'emptyDefault' => [ '_dummy' => 1, ApiResult::META_TYPE => 'array' ],
					'emptyAssoc' => (object)[ '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ],
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => [ '_dummy' ],
					ApiResult::META_TYPE => 'assoc',
				],
			],
			[
				'Types: ArmorKVP',
				$typeArr,
				[ 'Types' => [ 'ArmorKVP' => 'name' ] ],
				[
					'defaultArray' => [ 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ],
					'defaultAssoc' => [ 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'defaultAssoc2' => [ 2 => 'a', 3 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'array' => [ 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ],
					'BCarray' => [ 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ],
					'BCassoc' => [ 'a', 'b', 'c', ApiResult::META_TYPE => 'assoc' ],
					'assoc' => [ 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'kvp' => [
						$kvp( 'name', 'x', 'value', 'a' ),
						$kvp( 'name', 'y', 'value', 'b' ),
						$kvp( 'name', 'z', 'value', [ 'c', ApiResult::META_TYPE => 'array' ] ),
						ApiResult::META_TYPE => 'array'
					],
					'BCkvp' => [
						$kvp( 'key', 'x', 'value', 'a' ),
						$kvp( 'key', 'y', 'value', 'b' ),
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_KEY_NAME => 'key',
					],
					'kvpmerge' => [
						$kvp( 'name', 'x', 'value', 'a' ),
						$kvp( 'name', 'y', 'value', [ 'b', ApiResult::META_TYPE => 'array' ] ),
						[
							'name' => 'z',
							'c' => 'd',
							ApiResult::META_TYPE => 'assoc',
							ApiResult::META_PRESERVE_KEYS => [ 'name' ]
						],
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_MERGE => true,
					],
					'emptyDefault' => [ '_dummy' => 1, ApiResult::META_TYPE => 'array' ],
					'emptyAssoc' => [ '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ],
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => [ '_dummy' ],
					ApiResult::META_TYPE => 'assoc',
				],
			],
			[
				'Types: ArmorKVP + BC',
				$typeArr,
				[ 'BC' => [], 'Types' => [ 'ArmorKVP' => 'name' ] ],
				[
					'defaultArray' => [ 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ],
					'defaultAssoc' => [ 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'defaultAssoc2' => [ 2 => 'a', 3 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'array' => [ 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ],
					'BCarray' => [ 'x' => 'a', 1 => 'b', 0 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'BCassoc' => [ 'a', 'b', 'c', ApiResult::META_TYPE => 'array' ],
					'assoc' => [ 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'kvp' => [
						$kvp( 'name', 'x', '*', 'a' ),
						$kvp( 'name', 'y', '*', 'b' ),
						$kvp( 'name', 'z', '*', [ 'c', ApiResult::META_TYPE => 'array' ] ),
						ApiResult::META_TYPE => 'array'
					],
					'BCkvp' => [
						$kvp( 'key', 'x', '*', 'a' ),
						$kvp( 'key', 'y', '*', 'b' ),
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_KEY_NAME => 'key',
					],
					'kvpmerge' => [
						$kvp( 'name', 'x', '*', 'a' ),
						$kvp( 'name', 'y', '*', [ 'b', ApiResult::META_TYPE => 'array' ] ),
						[
							'name' => 'z',
							'c' => 'd',
							ApiResult::META_TYPE => 'assoc',
							ApiResult::META_PRESERVE_KEYS => [ 'name' ] ],
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_MERGE => true,
					],
					'emptyDefault' => [ '_dummy' => 1, ApiResult::META_TYPE => 'array' ],
					'emptyAssoc' => [ '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ],
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => [ '_dummy' ],
					ApiResult::META_TYPE => 'assoc',
				],
			],
			[
				'Types: ArmorKVP + AssocAsObject',
				$typeArr,
				[ 'Types' => [ 'ArmorKVP' => 'name', 'AssocAsObject' => true ] ],
				(object)[
					'defaultArray' => [ 'b', 'c', 'a', ApiResult::META_TYPE => 'array' ],
					'defaultAssoc' => (object)[ 'x' => 'a', 1 => 'b',
						0 => 'c', ApiResult::META_TYPE => 'assoc'
					],
					'defaultAssoc2' => (object)[ 2 => 'a', 3 => 'b',
						0 => 'c', ApiResult::META_TYPE => 'assoc'
					],
					'array' => [ 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ],
					'BCarray' => [ 'a', 'c', 'b', ApiResult::META_TYPE => 'array' ],
					'BCassoc' => (object)[ 'a', 'b', 'c', ApiResult::META_TYPE => 'assoc' ],
					'assoc' => (object)[ 2 => 'a', 0 => 'b', 1 => 'c', ApiResult::META_TYPE => 'assoc' ],
					'kvp' => [
						(object)$kvp( 'name', 'x', 'value', 'a' ),
						(object)$kvp( 'name', 'y', 'value', 'b' ),
						(object)$kvp( 'name', 'z', 'value', [ 'c', ApiResult::META_TYPE => 'array' ] ),
						ApiResult::META_TYPE => 'array'
					],
					'BCkvp' => [
						(object)$kvp( 'key', 'x', 'value', 'a' ),
						(object)$kvp( 'key', 'y', 'value', 'b' ),
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_KEY_NAME => 'key',
					],
					'kvpmerge' => [
						(object)$kvp( 'name', 'x', 'value', 'a' ),
						(object)$kvp( 'name', 'y', 'value', [ 'b', ApiResult::META_TYPE => 'array' ] ),
						(object)[
							'name' => 'z',
							'c' => 'd',
							ApiResult::META_TYPE => 'assoc',
							ApiResult::META_PRESERVE_KEYS => [ 'name' ]
						],
						ApiResult::META_TYPE => 'array',
						ApiResult::META_KVP_MERGE => true,
					],
					'emptyDefault' => [ '_dummy' => 1, ApiResult::META_TYPE => 'array' ],
					'emptyAssoc' => (object)[ '_dummy' => 1, ApiResult::META_TYPE => 'assoc' ],
					'_dummy' => 1,
					ApiResult::META_PRESERVE_KEYS => [ '_dummy' ],
					ApiResult::META_TYPE => 'assoc',
				],
			],
			[
				'Types: BCkvp exception',
				[
					ApiResult::META_TYPE => 'BCkvp',
				],
				[ 'Types' => [] ],
				new UnexpectedValueException(
					'Type "BCkvp" used without setting ApiResult::META_KVP_KEY_NAME metadata item'
				),
			],

			[
				'Strip: With ArmorKVP + AssocAsObject transforms',
				$typeArr,
				[ 'Types' => [ 'ArmorKVP' => 'name', 'AssocAsObject' => true ], 'Strip' => 'all' ],
				(object)[
					'defaultArray' => [ 'b', 'c', 'a' ],
					'defaultAssoc' => (object)[ 'x' => 'a', 1 => 'b', 0 => 'c' ],
					'defaultAssoc2' => (object)[ 2 => 'a', 3 => 'b', 0 => 'c' ],
					'array' => [ 'a', 'c', 'b' ],
					'BCarray' => [ 'a', 'c', 'b' ],
					'BCassoc' => (object)[ 'a', 'b', 'c' ],
					'assoc' => (object)[ 2 => 'a', 0 => 'b', 1 => 'c' ],
					'kvp' => [
						(object)[ 'name' => 'x', 'value' => 'a' ],
						(object)[ 'name' => 'y', 'value' => 'b' ],
						(object)[ 'name' => 'z', 'value' => [ 'c' ] ],
					],
					'BCkvp' => [
						(object)[ 'key' => 'x', 'value' => 'a' ],
						(object)[ 'key' => 'y', 'value' => 'b' ],
					],
					'kvpmerge' => [
						(object)[ 'name' => 'x', 'value' => 'a' ],
						(object)[ 'name' => 'y', 'value' => [ 'b' ] ],
						(object)[ 'name' => 'z', 'c' => 'd' ],
					],
					'emptyDefault' => [],
					'emptyAssoc' => (object)[],
					'_dummy' => 1,
				],
			],

			[
				'Strip: all',
				$stripArr,
				[ 'Strip' => 'all' ],
				[
					'foo' => [
						'bar' => [],
						'baz' => [],
						'x' => 'ok',
					],
					'_dummy2' => 'foobaz!',
				],
			],
			[
				'Strip: base',
				$stripArr,
				[ 'Strip' => 'base' ],
				[
					'foo' => [
						'bar' => [ '_dummy' => 'foobaz' ],
						'baz' => [
							ApiResult::META_SUBELEMENTS => [ 'foo', 'bar' ],
							ApiResult::META_INDEXED_TAG_NAME => 'itn',
							ApiResult::META_PRESERVE_KEYS => [ 'foo', 'bar', '_dummy2', 0 ],
							ApiResult::META_TYPE => 'array',
						],
						'x' => 'ok',
						'_dummy' => 'foobaz',
					],
					'_dummy2' => 'foobaz!',
				],
			],
			[
				'Strip: bc',
				$stripArr,
				[ 'Strip' => 'bc' ],
				[
					'foo' => [
						'bar' => [],
						'baz' => [
							ApiResult::META_SUBELEMENTS => [ 'foo', 'bar' ],
							ApiResult::META_INDEXED_TAG_NAME => 'itn',
						],
						'x' => 'ok',
					],
					'_dummy2' => 'foobaz!',
					ApiResult::META_SUBELEMENTS => [ 'foo', 'bar' ],
					ApiResult::META_INDEXED_TAG_NAME => 'itn',
				],
			],

			[
				'Custom transform',
				[
					'foo' => '?',
					'bar' => '?',
					'_dummy' => '?',
					'_dummy2' => '?',
					'_dummy3' => '?',
					ApiResult::META_CONTENT => 'foo',
					ApiResult::META_PRESERVE_KEYS => [ '_dummy2', '_dummy3' ],
				],
				[
					'Custom' => [ $this, 'customTransform' ],
					'BC' => [],
					'Types' => [],
					'Strip' => 'all'
				],
				[
					'*' => 'FOO',
					'bar' => 'BAR',
					'baz' => [ 'a', 'b' ],
					'_dummy2' => '_DUMMY2',
					'_dummy3' => '_DUMMY3',
					ApiResult::META_CONTENT => 'bar',
				],
			],
		];

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
		$data['baz'] = [ '_added' => 1, 'z' => 'b', 'y' => 'a' ];
		$metadata[ApiResult::META_PRESERVE_KEYS][0] = '_dummy';
		$data[ApiResult::META_CONTENT] = 'bar';
	}

	/**
	 * @covers ApiResult
	 */
	public function testAddMetadataToResultVars() {
		$arr = [
			'a' => "foo",
			'b' => false,
			'c' => 10,
			'sequential_numeric_keys' => [ 'a', 'b', 'c' ],
			'non_sequential_numeric_keys' => [ 'a', 'b', 4 => 'c' ],
			'string_keys' => [
				'one' => 1,
				'two' => 2
			],
			'object_sequential_keys' => (object)[ 'a', 'b', 'c' ],
			'_type' => "should be overwritten in result",
		];
		$this->assertSame( [
			ApiResult::META_TYPE => 'kvp',
			ApiResult::META_KVP_KEY_NAME => 'key',
			ApiResult::META_PRESERVE_KEYS => [
				'a', 'b', 'c',
				'sequential_numeric_keys', 'non_sequential_numeric_keys',
				'string_keys', 'object_sequential_keys'
			],
			ApiResult::META_BC_BOOLS => [ 'b' ],
			ApiResult::META_INDEXED_TAG_NAME => 'var',
			'a' => "foo",
			'b' => false,
			'c' => 10,
			'sequential_numeric_keys' => [
				ApiResult::META_TYPE => 'array',
				ApiResult::META_BC_BOOLS => [],
				ApiResult::META_INDEXED_TAG_NAME => 'value',
				0 => 'a',
				1 => 'b',
				2 => 'c',
			],
			'non_sequential_numeric_keys' => [
				ApiResult::META_TYPE => 'kvp',
				ApiResult::META_KVP_KEY_NAME => 'key',
				ApiResult::META_PRESERVE_KEYS => [ 0, 1, 4 ],
				ApiResult::META_BC_BOOLS => [],
				ApiResult::META_INDEXED_TAG_NAME => 'var',
				0 => 'a',
				1 => 'b',
				4 => 'c',
			],
			'string_keys' => [
				ApiResult::META_TYPE => 'kvp',
				ApiResult::META_KVP_KEY_NAME => 'key',
				ApiResult::META_PRESERVE_KEYS => [ 'one', 'two' ],
				ApiResult::META_BC_BOOLS => [],
				ApiResult::META_INDEXED_TAG_NAME => 'var',
				'one' => 1,
				'two' => 2,
			],
			'object_sequential_keys' => [
				ApiResult::META_TYPE => 'kvp',
				ApiResult::META_KVP_KEY_NAME => 'key',
				ApiResult::META_PRESERVE_KEYS => [ 0, 1, 2 ],
				ApiResult::META_BC_BOOLS => [],
				ApiResult::META_INDEXED_TAG_NAME => 'var',
				0 => 'a',
				1 => 'b',
				2 => 'c',
			],
		], ApiResult::addMetadataToResultVars( $arr ) );
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
			if ( preg_match( '/Use of ApiMain to ApiResult::__construct ' .
				'was deprecated in MediaWiki \d+.\d+\./', $errstr ) ) {
				return true;
			}
			return false;
		} );
		$reset = new ScopedCallback( 'restore_error_handler' );

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setConfig( new HashConfig( [
			'APIModules' => [],
			'APIFormatModules' => [],
			'APIMaxResultSize' => 42,
		] ) );
		$main = new ApiMain( $context );
		$result = TestingAccessWrapper::newFromObject( new ApiResult( $main ) );
		$this->assertSame( 42, $result->maxSize );
		$this->assertSame( $main->getErrorFormatter(), $result->errorFormatter );
		$this->assertSame( $main, $result->mainForContinuation );

		$result = new ApiResult( 8388608 );

		$result->addContentValue( null, 'test', 'content' );
		$result->addContentValue( [ 'foo', 'bar' ], 'test', 'content' );
		$result->addIndexedTagName( null, 'itn' );
		$result->addSubelementsList( null, [ 'sub' ] );
		$this->assertSame( [
			'foo' => [
				'bar' => [
					'*' => 'content',
				],
			],
			'*' => 'content',
		], $result->getData() );

		$arr = [];
		ApiResult::setContent( $arr, 'value' );
		ApiResult::setContent( $arr, 'value2', 'foobar' );
		$this->assertSame( [
			ApiResult::META_CONTENT => 'content',
			'content' => 'value',
			'foobar' => [
				ApiResult::META_CONTENT => 'content',
				'content' => 'value2',
			],
		], $arr );

		$result = new ApiResult( 3 );
		$formatter = new ApiErrorFormatter_BackCompat( $result );
		$result->setErrorFormatter( $formatter );
		$result->disableSizeCheck();
		$this->assertTrue( $result->addValue( null, 'foo', '1234567890' ) );
		$result->enableSizeCheck();
		$this->assertSame( 0, $result->getSize() );
		$this->assertFalse( $result->addValue( null, 'foo', '1234567890' ) );

		$arr = [ 'foo' => [ 'bar' => 1 ] ];
		$result->setIndexedTagName_recursive( $arr, 'itn' );
		$this->assertSame( [
			'foo' => [
				'bar' => 1,
				ApiResult::META_INDEXED_TAG_NAME => 'itn'
			],
		], $arr );

		$status = Status::newGood();
		$status->fatal( 'parentheses', '1' );
		$status->fatal( 'parentheses', '2' );
		$status->warning( 'parentheses', '3' );
		$status->warning( 'parentheses', '4' );
		$this->assertSame( [
			[
				'type' => 'error',
				'message' => 'parentheses',
				'params' => [
					0 => '1',
					ApiResult::META_INDEXED_TAG_NAME => 'param',
				],
			],
			[
				'type' => 'error',
				'message' => 'parentheses',
				'params' => [
					0 => '2',
					ApiResult::META_INDEXED_TAG_NAME => 'param',
				],
			],
			ApiResult::META_INDEXED_TAG_NAME => 'error',
		], $result->convertStatusToArray( $status, 'error' ) );
		$this->assertSame( [
			[
				'type' => 'warning',
				'message' => 'parentheses',
				'params' => [
					0 => '3',
					ApiResult::META_INDEXED_TAG_NAME => 'param',
				],
			],
			[
				'type' => 'warning',
				'message' => 'parentheses',
				'params' => [
					0 => '4',
					ApiResult::META_INDEXED_TAG_NAME => 'param',
				],
			],
			ApiResult::META_INDEXED_TAG_NAME => 'warning',
		], $result->convertStatusToArray( $status, 'warning' ) );
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
		$allModules = [
			new MockApiQueryBase( 'mock1' ),
			new MockApiQueryBase( 'mock2' ),
			new MockApiQueryBase( 'mocklist' ),
		];
		$generator = new MockApiQueryBase( 'generator' );

		$main = new ApiMain( RequestContext::getMain() );
		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( [ false, $allModules ], $ret );
		$result->setContinueParam( $allModules[0], 'm1continue', [ 1, 2 ] );
		$result->setContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->setGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( [
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		], $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( [
			'mock1' => [ 'm1continue' => '1|2' ],
			'mocklist' => [ 'mlcontinue' => 2 ],
			'generator' => [ 'gcontinue' => 3 ],
		], $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( [ false, $allModules ], $ret );
		$result->setContinueParam( $allModules[0], 'm1continue', [ 1, 2 ] );
		$result->setGeneratorContinueParam( $generator, 'gcontinue', [ 3, 4 ] );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( [
			'm1continue' => '1|2',
			'continue' => '||mock2|mocklist',
		], $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( [
			'mock1' => [ 'm1continue' => '1|2' ],
			'generator' => [ 'gcontinue' => '3|4' ],
		], $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( [ false, $allModules ], $ret );
		$result->setContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->setGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( [
			'mlcontinue' => 2,
			'gcontinue' => 3,
			'continue' => 'gcontinue||',
		], $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( [
			'mocklist' => [ 'mlcontinue' => 2 ],
			'generator' => [ 'gcontinue' => 3 ],
		], $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( [ false, $allModules ], $ret );
		$result->setGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( [
			'gcontinue' => 3,
			'continue' => 'gcontinue||mocklist',
		], $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( [
			'generator' => [ 'gcontinue' => 3 ],
		], $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( [ false, $allModules ], $ret );
		$result->setContinueParam( $allModules[0], 'm1continue', [ 1, 2 ] );
		$result->setContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( [
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		], $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( [
			'mock1' => [ 'm1continue' => '1|2' ],
			'mocklist' => [ 'mlcontinue' => 2 ],
		], $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( [ false, $allModules ], $ret );
		$result->setContinueParam( $allModules[0], 'm1continue', [ 1, 2 ] );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( [
			'm1continue' => '1|2',
			'continue' => '||mock2|mocklist',
		], $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( [
			'mock1' => [ 'm1continue' => '1|2' ],
		], $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( [ false, $allModules ], $ret );
		$result->setContinueParam( $allModules[2], 'mlcontinue', 2 );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( [
			'mlcontinue' => 2,
			'continue' => '-||mock1|mock2',
		], $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( [
			'mocklist' => [ 'mlcontinue' => 2 ],
		], $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( null, $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( [ false, $allModules ], $ret );
		$result->endContinuation( 'raw' );
		$result->endContinuation( 'standard' );
		$this->assertSame( null, $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );
		$this->assertSame( null, $result->getResultData( 'query-continue' ) );
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( '||mock2', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame(
			[ false, array_values( array_diff_key( $allModules, [ 1 => 1 ] ) ) ],
			$ret
		);
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		$ret = $result->beginContinuation( '-||', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame(
			[ true, array_values( array_diff_key( $allModules, [ 0 => 0, 1 => 1 ] ) ) ],
			$ret
		);
		$main->setContinuationManager( null );

		$result = new ApiResult( 8388608 );
		$result->setMainForContinuation( $main );
		try {
			$result->beginContinuation( 'foo', $allModules, [ 'mock1', 'mock2' ] );
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
		$result->beginContinuation( '||mock2', array_slice( $allModules, 0, 2 ),
			[ 'mock1', 'mock2' ] );
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
				'Module \'mocklist\' called ApiContinuationManager::addContinueParam ' .
					'but was not passed to ApiContinuationManager::__construct',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		$main->setContinuationManager( null );

	}

	public function testObjectSerialization() {
		$arr = [];
		ApiResult::setValue( $arr, 'foo', (object)[ 'a' => 1, 'b' => 2 ] );
		$this->assertSame( [
			'a' => 1,
			'b' => 2,
			ApiResult::META_TYPE => 'assoc',
		], $arr['foo'] );

		$arr = [];
		ApiResult::setValue( $arr, 'foo', new ApiResultTestStringifiableObject() );
		$this->assertSame( 'Ok', $arr['foo'] );

		$arr = [];
		ApiResult::setValue( $arr, 'foo', new ApiResultTestSerializableObject( 'Ok' ) );
		$this->assertSame( 'Ok', $arr['foo'] );

		try {
			$arr = [];
			ApiResult::setValue( $arr, 'foo', new ApiResultTestSerializableObject(
				new ApiResultTestStringifiableObject()
			) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'ApiResultTestSerializableObject::serializeForApiResult() ' .
					'returned an object of class ApiResultTestStringifiableObject',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		try {
			$arr = [];
			ApiResult::setValue( $arr, 'foo', new ApiResultTestSerializableObject( NAN ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'ApiResultTestSerializableObject::serializeForApiResult() ' .
					'returned an invalid value: Cannot add non-finite floats to ApiResult',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$arr = [];
		ApiResult::setValue( $arr, 'foo', new ApiResultTestSerializableObject(
			[
				'one' => new ApiResultTestStringifiableObject( '1' ),
				'two' => new ApiResultTestSerializableObject( 2 ),
			]
		) );
		$this->assertSame( [
			'one' => '1',
			'two' => 2,
		], $arr['foo'] );
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
