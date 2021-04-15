<?php

namespace Wikimedia\ParamValidator;

use Psr\Http\Message\UploadedFileInterface;
use Wikimedia\Message\DataMessageValue;

/**
 * @covers Wikimedia\ParamValidator\SimpleCallbacks
 */
class SimpleCallbacksTest extends \PHPUnit\Framework\TestCase {

	public function testDataAccess() {
		$callbacks = new SimpleCallbacks(
			[ 'foo' => 'Foo!', 'bar' => null ],
			[
				'file1' => [
					'name' => 'example.txt',
					'type' => 'text/plain',
					'tmp_name' => '...',
					'error' => UPLOAD_ERR_OK,
					'size' => 123,
				],
				'file2' => [
					'name' => '',
					'type' => '',
					'tmp_name' => '',
					'error' => UPLOAD_ERR_NO_FILE,
					'size' => 0,
				],
			]
		);

		$this->assertTrue( $callbacks->hasParam( 'foo', [] ) );
		$this->assertFalse( $callbacks->hasParam( 'bar', [] ) );
		$this->assertFalse( $callbacks->hasParam( 'baz', [] ) );
		$this->assertFalse( $callbacks->hasParam( 'file1', [] ) );

		$this->assertSame( 'Foo!', $callbacks->getValue( 'foo', null, [] ) );
		$this->assertSame( null, $callbacks->getValue( 'bar', null, [] ) );
		$this->assertSame( 123, $callbacks->getValue( 'bar', 123, [] ) );
		$this->assertSame( null, $callbacks->getValue( 'baz', null, [] ) );
		$this->assertSame( null, $callbacks->getValue( 'file1', null, [] ) );

		$this->assertFalse( $callbacks->hasUpload( 'foo', [] ) );
		$this->assertFalse( $callbacks->hasUpload( 'bar', [] ) );
		$this->assertTrue( $callbacks->hasUpload( 'file1', [] ) );
		$this->assertTrue( $callbacks->hasUpload( 'file2', [] ) );
		$this->assertFalse( $callbacks->hasUpload( 'baz', [] ) );

		$this->assertNull( $callbacks->getUploadedFile( 'foo', [] ) );
		$this->assertNull( $callbacks->getUploadedFile( 'bar', [] ) );
		$this->assertInstanceOf(
			UploadedFileInterface::class, $callbacks->getUploadedFile( 'file1', [] )
		);
		$this->assertInstanceOf(
			UploadedFileInterface::class, $callbacks->getUploadedFile( 'file2', [] )
		);
		$this->assertNull( $callbacks->getUploadedFile( 'baz', [] ) );

		$file = $callbacks->getUploadedFile( 'file1', [] );
		$this->assertSame( 'example.txt', $file->getClientFilename() );
		$file = $callbacks->getUploadedFile( 'file2', [] );
		$this->assertSame( UPLOAD_ERR_NO_FILE, $file->getError() );

		$this->assertFalse( $callbacks->useHighLimits( [] ) );
		$this->assertFalse( $callbacks->useHighLimits( [ 'useHighLimits' => false ] ) );
		$this->assertTrue( $callbacks->useHighLimits( [ 'useHighLimits' => true ] ) );
	}

	public function testRecording() {
		$callbacks = new SimpleCallbacks( [] );

		$this->assertSame( [], $callbacks->getRecordedConditions() );

		$m1 = DataMessageValue::new( 'XXX-from-test-1', [], 'foo' );
		$callbacks->recordCondition( $m1, 'foo', 'Foo!', [], [] );
		$m2 = DataMessageValue::new( 'XXX-from-test-2', [], 'barbar', [ 'bAr' => 'BaR' ] );
		$callbacks->recordCondition( $m2, 'bar', null, [], [] );
		$callbacks->recordCondition( $m2, 'bar', null, [], [] );
		$this->assertSame( [
			[ 'message' => $m1, 'name' => 'foo', 'value' => 'Foo!', 'settings' => [] ],
			[ 'message' => $m2, 'name' => 'bar', 'value' => null, 'settings' => [] ],
			[ 'message' => $m2, 'name' => 'bar', 'value' => null, 'settings' => [] ],
		], $callbacks->getRecordedConditions() );

		$callbacks->clearRecordedConditions();
		$this->assertSame( [], $callbacks->getRecordedConditions() );
		$callbacks->recordCondition( $m1, 'foo', 'Foo!', [], [] );
		$this->assertSame( [
			[ 'message' => $m1, 'name' => 'foo', 'value' => 'Foo!', 'settings' => [] ],
		], $callbacks->getRecordedConditions() );
	}

}
