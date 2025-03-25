<?php

namespace MediaWiki\Tests\Api\Format;

use MediaWiki\Api\ApiFormatJson;
use MediaWiki\Api\ApiFormatRaw;
use MediaWiki\Api\ApiMain;
use MediaWiki\Exception\MWException;

/**
 * @group API
 * @covers \MediaWiki\Api\ApiFormatRaw
 */
class ApiFormatRawTest extends ApiFormatTestBase {

	/** @inheritDoc */
	protected $printerName = 'raw';

	/**
	 * Test basic encoding and missing mime and text exceptions
	 * @return array datasets
	 */
	public static function provideGeneralEncoding() {
		$options = [
			'class' => ApiFormatRaw::class,
			'factory' => static function ( ApiMain $main ) {
				return new ApiFormatRaw( $main, new ApiFormatJson( $main, 'json' ) );
			}
		];

		return [
			[
				[ 'mime' => 'text/plain', 'text' => 'foo' ],
				'foo',
				[],
				$options
			],
			[
				[ 'mime' => 'text/plain', 'text' => 'fóo' ],
				'fóo',
				[],
				$options
			],
			[
				[ 'text' => 'some text' ],
				new MWException( 'No MIME type set for raw formatter' ),
				[],
				$options
			],
			[
				[ 'mime' => 'text/plain' ],
				new MWException( 'No text given for raw formatter' ),
				[],
				$options
			],
			'test error fallback' => [
				[ 'mime' => 'text/plain', 'text' => 'some text', 'error' => 'some error' ],
				'{"mime":"text/plain","text":"some text","error":"some error"}',
				[],
				$options
			]
		];
	}

	/**
	 * Test specifying filename
	 */
	public function testFilename() {
		$printer = new ApiFormatRaw( new ApiMain );
		$printer->getResult()->addValue( null, 'filename', 'whatever.raw' );
		$this->assertSame( 'whatever.raw', $printer->getFilename() );
	}

	/**
	 * Test specifying filename with error fallback printer
	 */
	public function testErrorFallbackFilename() {
		$apiMain = new ApiMain;
		$printer = new ApiFormatRaw( $apiMain, new ApiFormatJson( $apiMain, 'json' ) );
		$printer->getResult()->addValue( null, 'error', 'some error' );
		$printer->getResult()->addValue( null, 'filename', 'whatever.raw' );
		$this->assertSame( 'api-result.json', $printer->getFilename() );
	}

	/**
	 * Test specifying mime
	 */
	public function testMime() {
		$printer = new ApiFormatRaw( new ApiMain );
		$printer->getResult()->addValue( null, 'mime', 'text/plain' );
		$this->assertSame( 'text/plain', $printer->getMimeType() );
	}

	/**
	 * Test specifying mime with error fallback printer
	 */
	public function testErrorFallbackMime() {
		$apiMain = new ApiMain;
		$printer = new ApiFormatRaw( $apiMain, new ApiFormatJson( $apiMain, 'json' ) );
		$printer->getResult()->addValue( null, 'error', 'some error' );
		$printer->getResult()->addValue( null, 'mime', 'text/plain' );
		$this->assertSame( 'application/json', $printer->getMimeType() );
	}

	/**
	 * Check that setting failWithHTTPError to true will result in 400 response status code
	 */
	public function testFailWithHTTPError() {
		$apiMain = null;

		$this->testGeneralEncoding(
			[ 'mime' => 'text/plain', 'text' => 'some text', 'error' => 'some error' ],
			'{"mime":"text/plain","text":"some text","error":"some error"}',
			[],
			[
				'class' => ApiFormatRaw::class,
				'factory' => static function ( ApiMain $main ) use ( &$apiMain ) {
					$apiMain = $main;
					$printer = new ApiFormatRaw( $main, new ApiFormatJson( $main, 'json' ) );
					$printer->setFailWithHTTPError( true );
					return $printer;
				}
			]
		);
		$this->assertEquals( 400, $apiMain->getRequest()->response()->getStatusCode() );
	}

}
