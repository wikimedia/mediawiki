<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\Util\UploadedFile;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\UploadDef
 */
class UploadDefTest extends TypeDefTestCase {

	protected static $testClass = UploadDef::class;

	protected function getCallbacks( $value, array $options ) {
		if ( $value instanceof UploadedFile ) {
			return new SimpleCallbacks( [], [ 'test' => $value ] );
		} else {
			return new SimpleCallbacks( [ 'test' => $value ] );
		}
	}

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		$ret = $this->getMockBuilder( UploadDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->setMethods( [ 'getIniSize' ] )
			->getMock();
		$ret->method( 'getIniSize' )->willReturn( $options['inisize'] ?? 2 * 1024 * 1024 );
		return $ret;
	}

	private function makeUpload( $err = UPLOAD_ERR_OK ) {
		return new UploadedFile( [
			'name' => 'example.txt',
			'type' => 'text/plain',
			'size' => 0,
			'tmp_name' => '...',
			'error' => $err,
		] );
	}

	public function testGetNoFile() {
		$typeDef = $this->getInstance(
			$this->getCallbacks( $this->makeUpload( UPLOAD_ERR_NO_FILE ), [] ),
			[]
		);

		$this->assertNull( $typeDef->getValue( 'test', [], [] ) );
		$this->assertNull( $typeDef->getValue( 'nothing', [], [] ) );
	}

	public function provideValidate() {
		$okFile = $this->makeUpload();
		$iniFile = $this->makeUpload( UPLOAD_ERR_INI_SIZE );
		$exIni = new ValidationException(
			'test', '', [], 'badupload-inisize', [ 'size' => 2 * 1024 * 1024 * 1024 ]
		);

		return [
			'Valid upload' => [ $okFile, $okFile ],
			'Not an upload' => [
				'bar',
				new ValidationException( 'test', 'bar', [], 'badupload-notupload', [] ),
			],

			'Too big (bytes)' => [ $iniFile, $exIni, [], [ 'inisize' => 2 * 1024 * 1024 * 1024 ] ],
			'Too big (k)' => [ $iniFile, $exIni, [], [ 'inisize' => ( 2 * 1024 * 1024 ) . 'k' ] ],
			'Too big (K)' => [ $iniFile, $exIni, [], [ 'inisize' => ( 2 * 1024 * 1024 ) . 'K' ] ],
			'Too big (m)' => [ $iniFile, $exIni, [], [ 'inisize' => ( 2 * 1024 ) . 'm' ] ],
			'Too big (M)' => [ $iniFile, $exIni, [], [ 'inisize' => ( 2 * 1024 ) . 'M' ] ],
			'Too big (g)' => [ $iniFile, $exIni, [], [ 'inisize' => '2g' ] ],
			'Too big (G)' => [ $iniFile, $exIni, [], [ 'inisize' => '2G' ] ],

			'Form size' => [
				$this->makeUpload( UPLOAD_ERR_FORM_SIZE ),
				new ValidationException( 'test', '', [], 'badupload-formsize', [] ),
			],
			'Partial' => [
				$this->makeUpload( UPLOAD_ERR_PARTIAL ),
				new ValidationException( 'test', '', [], 'badupload-partial', [] ),
			],
			'No tmp' => [
				$this->makeUpload( UPLOAD_ERR_NO_TMP_DIR ),
				new ValidationException( 'test', '', [], 'badupload-notmpdir', [] ),
			],
			'Can\'t write' => [
				$this->makeUpload( UPLOAD_ERR_CANT_WRITE ),
				new ValidationException( 'test', '', [], 'badupload-cantwrite', [] ),
			],
			'Ext abort' => [
				$this->makeUpload( UPLOAD_ERR_EXTENSION ),
				new ValidationException( 'test', '', [], 'badupload-phpext', [] ),
			],
			'Unknown' => [
				$this->makeUpload( -43 ), // Should be safe from ever being an UPLOAD_ERR_* constant
				new ValidationException( 'test', '', [], 'badupload-unknown', [ 'code' => -43 ] ),
			],

			'Validating null' => [
				null,
				new ValidationException( 'test', '', [], 'badupload', [] ),
			],
		];
	}

	public function provideStringifyValue() {
		return [
			'Yeah, right' => [ $this->makeUpload(), null ],
		];
	}

}
