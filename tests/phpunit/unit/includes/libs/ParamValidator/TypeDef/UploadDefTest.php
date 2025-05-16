<?php

namespace Wikimedia\Tests\ParamValidator\TypeDef;

use InvalidArgumentException;
use UnexpectedValueException;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef\UploadDef;
use Wikimedia\ParamValidator\Util\UploadedFile;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers \Wikimedia\ParamValidator\TypeDef\UploadDef
 */
class UploadDefTest extends TypeDefTestCase {

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
			->onlyMethods( [ 'getIniSize' ] )
			->getMock();
		$ret->method( 'getIniSize' )->willReturn( $options['inisize'] ?? 2 * 1024 * 1024 );
		return $ret;
	}

	private static function makeUpload( $err = UPLOAD_ERR_OK ) {
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
			$this->getCallbacks( self::makeUpload( UPLOAD_ERR_NO_FILE ), [] ),
			[]
		);

		$this->assertNull( $typeDef->getValue( 'test', [], [] ) );
		$this->assertNull( $typeDef->getValue( 'nothing', [], [] ) );
	}

	public static function provideValidate() {
		$okFile = self::makeUpload();
		$iniFile = self::makeUpload( UPLOAD_ERR_INI_SIZE );
		$exIni = new ValidationException(
			DataMessageValue::new( 'paramvalidator-badupload-inisize', [], 'badupload', [
				'code' => 'inisize',
				'size' => 2 * 1024 * 1024 * 1024,
			] ),
			'test', '', []
		);

		return [
			'Valid upload' => [ $okFile, $okFile ],
			'Not an upload' => [
				'bar',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badupload-notupload', [], 'badupload', [
						'code' => 'notupload'
					] ),
					'test', 'bar', []
				),
			],

			'Too big (bytes)' => [ $iniFile, $exIni, [], [ 'inisize' => 2 * 1024 * 1024 * 1024 ] ],
			'Too big (k)' => [ $iniFile, $exIni, [], [ 'inisize' => ( 2 * 1024 * 1024 ) . 'k' ] ],
			'Too big (K)' => [ $iniFile, $exIni, [], [ 'inisize' => ( 2 * 1024 * 1024 ) . 'K' ] ],
			'Too big (m)' => [ $iniFile, $exIni, [], [ 'inisize' => ( 2 * 1024 ) . 'm' ] ],
			'Too big (M)' => [ $iniFile, $exIni, [], [ 'inisize' => ( 2 * 1024 ) . 'M' ] ],
			'Too big (g)' => [ $iniFile, $exIni, [], [ 'inisize' => '2g' ] ],
			'Too big (G)' => [ $iniFile, $exIni, [], [ 'inisize' => '2G' ] ],

			'Form size' => [
				self::makeUpload( UPLOAD_ERR_FORM_SIZE ),
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badupload-formsize', [], 'badupload', [
						'code' => 'formsize',
					] ),
					'test', '', []
				),
			],
			'Partial' => [
				self::makeUpload( UPLOAD_ERR_PARTIAL ),
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badupload-partial', [], 'badupload', [
						'code' => 'partial',
					] ),
					'test', '', []
				),
			],
			'No tmp' => [
				self::makeUpload( UPLOAD_ERR_NO_TMP_DIR ),
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badupload-notmpdir', [], 'badupload', [
						'code' => 'notmpdir',
					] ),
					'test', '', []
				),
			],
			'Can\'t write' => [
				self::makeUpload( UPLOAD_ERR_CANT_WRITE ),
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badupload-cantwrite', [], 'badupload', [
						'code' => 'cantwrite',
					] ),
					'test', '', []
				),
			],
			'Ext abort' => [
				self::makeUpload( UPLOAD_ERR_EXTENSION ),
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badupload-phpext', [], 'badupload', [
						'code' => 'phpext',
					] ),
					'test', '', []
				),
			],
		];
	}

	public function testValidate_badType() {
		$callbacks = $this->getCallbacks( 'foo', [] );
		$typeDef = $this->getInstance( $callbacks, [] );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$value must be UploadedFileInterface, got string' );
		$typeDef->validate( 'test', 'foo', [], [] );
	}

	public function testValidate_badType2() {
		$callbacks = $this->getCallbacks( 'foo', [] );
		$typeDef = $this->getInstance( $callbacks, [] );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$value must be UploadedFileInterface, got null' );
		$typeDef->validate( 'test', null, [], [] );
	}

	public function testValidate_unknownError() {
		// -43 should be safe from ever being a valid UPLOAD_ERR_ constant
		$callbacks = $this->getCallbacks( self::makeUpload( -43 ), [] );
		$typeDef = $this->getInstance( $callbacks, [] );
		$value = $typeDef->getValue( 'test', [], [] );

		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'Unrecognized PHP upload error value -43' );
		$typeDef->validate( 'test', $value, [], [] );
	}

	public function testValidate_unknownError2() {
		define( 'UPLOAD_ERR_UPLOADDEFTEST', -44 );
		$callbacks = $this->getCallbacks( self::makeUpload( UPLOAD_ERR_UPLOADDEFTEST ), [] );
		$typeDef = $this->getInstance( $callbacks, [] );
		$value = $typeDef->getValue( 'test', [], [] );

		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage(
			'Unrecognized PHP upload error value -44 (UPLOAD_ERR_UPLOADDEFTEST?)'
		);
		$typeDef->validate( 'test', $value, [], [] );
	}

	public static function provideCheckSettings() {
		return [
			'Basic test' => [
				[],
				self::STDRET,
				self::STDRET,
			],
			'PARAM_ISMULTI not allowed' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						ParamValidator::PARAM_ISMULTI
							=> 'PARAM_ISMULTI cannot be used for upload-type parameters',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
			],
			'PARAM_ISMULTI not allowed, but another ISMULTI issue was already logged' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				[
					'issues' => [
						ParamValidator::PARAM_ISMULTI => 'XXX',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
				[
					'issues' => [
						ParamValidator::PARAM_ISMULTI => 'XXX',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
			],
			'PARAM_DEFAULT can be null' => [
				[ ParamValidator::PARAM_DEFAULT => null ],
				self::STDRET,
				self::STDRET,
			],
			'PARAM_DEFAULT is otherwise not allowed' => [
				[
					ParamValidator::PARAM_DEFAULT => true,
				],
				[
					'issues' => [
						'X',
						ParamValidator::PARAM_DEFAULT => 'XXX',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
				[
					'issues' => [
						'X',
						ParamValidator::PARAM_DEFAULT => 'Cannot specify a default for upload-type parameters',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
			],
		];
	}

	public static function provideStringifyValue() {
		return [
			'Yeah, right' => [ self::makeUpload(), null ],
		];
	}

	public static function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[],
				[
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-upload"></message>',
				],
			],
		];
	}

}
