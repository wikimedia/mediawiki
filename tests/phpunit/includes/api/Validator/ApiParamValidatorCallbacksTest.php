<?php

namespace MediaWiki\Api\Validator;

use ApiBase;
use ApiMain;
use ApiMessage;
use ApiQueryBase;
use ApiUploadTestCase;
use FauxRequest;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Api\Validator\ApiParamValidatorCallbacks
 * @group API
 * @group medium
 */
class ApiParamValidatorCallbacksTest extends ApiUploadTestCase {

	private function getCallbacks( FauxRequest $request ): array {
		$context = $this->apiContext->newTestContext( $request, $this->getTestUser()->getAuthority() );
		$main = new ApiMain( $context );
		return [ new ApiParamValidatorCallbacks( $main ), $main ];
	}

	private function filePath( $fileName ) {
		return __DIR__ . '/../../../data/media/' . $fileName;
	}

	public function testHasParam(): void {
		[ $callbacks, $main ] = $this->getCallbacks( new FauxRequest( [
			'foo' => '1',
			'bar' => '',
		] ) );

		$this->assertTrue( $callbacks->hasParam( 'foo', [] ) );
		$this->assertTrue( $callbacks->hasParam( 'bar', [] ) );
		$this->assertFalse( $callbacks->hasParam( 'baz', [] ) );

		$this->assertSame(
			[ 'foo', 'bar', 'baz' ],
			TestingAccessWrapper::newFromObject( $main )->getParamsUsed()
		);
	}

	/**
	 * @dataProvider provideGetValue
	 * @param string|null $data Value from request
	 * @param mixed $default For getValue()
	 * @param mixed $expect Expected return value
	 * @param bool $normalized Whether handleParamNormalization is called
	 */
	public function testGetValue( ?string $data, $default, $expect, bool $normalized = false ): void {
		[ $callbacks, $main ] = $this->getCallbacks( new FauxRequest( [ 'test' => $data ] ) );

		$module = $this->getMockBuilder( ApiBase::class )
			->setConstructorArgs( [ $main, 'testmodule' ] )
			->onlyMethods( [ 'handleParamNormalization' ] )
			->getMockForAbstractClass();
		$options = [ 'module' => $module ];
		if ( $normalized ) {
			$module->expects( $this->once() )->method( 'handleParamNormalization' )
				->with(
					$this->identicalTo( 'test' ),
					$this->identicalTo( $expect ),
					$this->identicalTo( $data ?? $default )
				);
		} else {
			$module->expects( $this->never() )->method( 'handleParamNormalization' );
		}

		$this->assertSame( $expect, $callbacks->getValue( 'test', $default, $options ) );
		$this->assertSame( [ 'test' ], TestingAccessWrapper::newFromObject( $main )->getParamsUsed() );
	}

	public function provideGetValue() {
		$obj = (object)[];
		return [
			'Basic test' => [ 'foo', 'bar', 'foo', false ],
			'Default value' => [ null, 1234, 1234, false ],
			'Default value (2)' => [ null, $obj, $obj, false ],
			'No default value' => [ null, null, null, false ],
			'Multi separator' => [ "\x1ffoo\x1fbar", 1234, "\x1ffoo\x1fbar", false ],
			'Normalized' => [ "\x1ffoo\x1fba\u{0301}r", 1234, "\x1ffoo\x1fbár", true ],
		];
	}

	private function setupUploads(): void {
		$fileName = 'TestUploadStash.jpg';
		$mimeType = 'image/jpeg';
		$filePath = $this->filePath( 'yuv420.jpg' );
		$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath );

		$this->requestDataFiles['file2'] = [
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'size' => 0,
			'error' => UPLOAD_ERR_NO_FILE,
		];

		$this->requestDataFiles['file3'] = [
			'name' => 'xxx.png',
			'type' => '',
			'tmp_name' => '',
			'size' => 0,
			'error' => UPLOAD_ERR_INI_SIZE,
		];
	}

	public function testHasUpload(): void {
		$this->setupUploads();

		$request = new FauxRequest( [
			'foo' => '1',
			'bar' => '',
		] );
		$request->setUploadData( $this->requestDataFiles );
		[ $callbacks, $main ] = $this->getCallbacks( $request );

		$this->assertFalse( $callbacks->hasUpload( 'foo', [] ) );
		$this->assertFalse( $callbacks->hasUpload( 'bar', [] ) );
		$this->assertFalse( $callbacks->hasUpload( 'baz', [] ) );
		$this->assertTrue( $callbacks->hasUpload( 'file', [] ) );
		$this->assertTrue( $callbacks->hasUpload( 'file2', [] ) );
		$this->assertTrue( $callbacks->hasUpload( 'file3', [] ) );

		$this->assertSame(
			[ 'foo', 'bar', 'baz', 'file', 'file2', 'file3' ],
			TestingAccessWrapper::newFromObject( $main )->getParamsUsed()
		);
	}

	public function testGetUploadedFile(): void {
		$this->setupUploads();

		$request = new FauxRequest( [
			'foo' => '1',
			'bar' => '',
		] );
		$request->setUploadData( $this->requestDataFiles );
		[ $callbacks, $main ] = $this->getCallbacks( $request );

		$this->assertNull( $callbacks->getUploadedFile( 'foo', [] ) );
		$this->assertNull( $callbacks->getUploadedFile( 'bar', [] ) );
		$this->assertNull( $callbacks->getUploadedFile( 'baz', [] ) );

		$file = $callbacks->getUploadedFile( 'file', [] );
		$this->assertInstanceOf( \Psr\Http\Message\UploadedFileInterface::class, $file );
		$this->assertSame( UPLOAD_ERR_OK, $file->getError() );
		$this->assertSame( 'TestUploadStash.jpg', $file->getClientFilename() );

		$file = $callbacks->getUploadedFile( 'file2', [] );
		$this->assertInstanceOf( \Psr\Http\Message\UploadedFileInterface::class, $file );
		$this->assertSame( UPLOAD_ERR_NO_FILE, $file->getError() );

		$file = $callbacks->getUploadedFile( 'file3', [] );
		$this->assertInstanceOf( \Psr\Http\Message\UploadedFileInterface::class, $file );
		$this->assertSame( UPLOAD_ERR_INI_SIZE, $file->getError() );
	}

	/**
	 * @dataProvider provideRecordCondition
	 * @param DataMessageValue $message
	 * @param ApiMessage|null $expect
	 * @param bool $sensitive
	 */
	public function testRecordCondition(
		DataMessageValue $message, ?ApiMessage $expect, bool $sensitive = false
	): void {
		[ $callbacks, $main ] = $this->getCallbacks( new FauxRequest( [ 'testparam' => 'testvalue' ] ) );
		$query = $main->getModuleFromPath( 'query' );
		$warnings = [];

		$module = $this->getMockBuilder( ApiQueryBase::class )
			->setConstructorArgs( [ $query, 'test' ] )
			->onlyMethods( [ 'addWarning' ] )
			->getMockForAbstractClass();
		$module->method( 'addWarning' )->willReturnCallback(
			static function ( $msg, $code, $data ) use ( &$warnings ) {
				$warnings[] = [ $msg, $code, $data ];
			}
		);
		$query->getModuleManager()->addModule( 'test', 'meta', [
			'class' => get_class( $module ),
			'factory' => static function () use ( $module ) {
				return $module;
			}
		] );

		$callbacks->recordCondition( $message, 'testparam', 'testvalue', [], [ 'module' => $module ] );

		if ( $expect ) {
			$this->assertNotCount( 0, $warnings );
			$this->assertSame(
				$expect->inLanguage( 'qqx' )->plain(),
				$warnings[0][0]->inLanguage( 'qqx' )->plain()
			);
			$this->assertSame( $expect->getApiCode(), $warnings[0][1] );
			$this->assertSame( $expect->getApiData(), $warnings[0][2] );
		} else {
			$this->assertSame( [], $warnings );
		}

		$this->assertSame(
			$sensitive ? [ 'testparam' ] : [],
			TestingAccessWrapper::newFromObject( $main )->getSensitiveParams()
		);
	}

	public function provideRecordCondition(): \Generator {
		yield 'Deprecated param' => [
			DataMessageValue::new(
				'paramvalidator-param-deprecated', [],
				'param-deprecated',
				[ 'data' => true ]
			)->plaintextParams( 'XXtestparam', 'XXtestvalue' ),
			ApiMessage::create(
				'paramvalidator-param-deprecated',
				'deprecation',
				[ 'data' => true, 'feature' => 'action=query&meta=test&testparam' ]
			)->plaintextParams( 'XXtestparam', 'XXtestvalue' )
		];

		yield 'Deprecated value' => [
			DataMessageValue::new(
				'paramvalidator-deprecated-value', [],
				'deprecated-value'
			)->plaintextParams( 'XXtestparam', 'XXtestvalue' ),
			ApiMessage::create(
				'paramvalidator-deprecated-value',
				'deprecation',
				[ 'feature' => 'action=query&meta=test&testparam=testvalue' ]
			)->plaintextParams( 'XXtestparam', 'XXtestvalue' )
		];

		yield 'Deprecated value with custom MessageValue' => [
			DataMessageValue::new(
				'some-custom-message-value', [],
				'deprecated-value',
				[ 'xyz' => 123 ]
			)->plaintextParams( 'XXtestparam', 'XXtestvalue', 'foobar' ),
			ApiMessage::create(
				'some-custom-message-value',
				'deprecation',
				[ 'xyz' => 123, 'feature' => 'action=query&meta=test&testparam=testvalue' ]
			)->plaintextParams( 'XXtestparam', 'XXtestvalue', 'foobar' )
		];

		// See ApiParamValidator::normalizeSettings()
		yield 'Deprecated value with custom Message' => [
			DataMessageValue::new(
				'some-custom-message', [],
				'deprecated-value',
				[ '💩' => 'back-compat' ]
			)->plaintextParams( 'XXtestparam', 'XXtestvalue', 'foobar' ),
			ApiMessage::create(
				'some-custom-message',
				'deprecation',
				[ 'feature' => 'action=query&meta=test&testparam=testvalue' ]
			)->plaintextParams( 'foobar' )
		];

		yield 'Sensitive param' => [
			DataMessageValue::new( 'paramvalidator-param-sensitive', [], 'param-sensitive' )
				->plaintextParams( 'XXtestparam', 'XXtestvalue' ),
			null,
			true
		];

		yield 'Arbitrary warning' => [
			DataMessageValue::new( 'some-warning', [], 'some-code', [ 'some-data' ] )
				->plaintextParams( 'XXtestparam', 'XXtestvalue', 'foobar' ),
			ApiMessage::create( 'some-warning', 'some-code', [ 'some-data' ] )
				->plaintextParams( 'XXtestparam', 'XXtestvalue', 'foobar' ),
		];
	}

	public function testUseHighLimits(): void {
		$context = $this->apiContext->newTestContext( new FauxRequest, $this->getTestUser()->getAuthority() );
		$main = $this->getMockBuilder( ApiMain::class )
			->setConstructorArgs( [ $context ] )
			->onlyMethods( [ 'canApiHighLimits' ] )
			->getMock();

		$main->method( 'canApiHighLimits' )->will( $this->onConsecutiveCalls( true, false ) );

		$callbacks = new ApiParamValidatorCallbacks( $main );
		$this->assertTrue( $callbacks->useHighLimits( [] ) );
		$this->assertFalse( $callbacks->useHighLimits( [] ) );
	}
}
