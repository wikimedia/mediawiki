<?php

use MediaWiki\MainConfigNames;

/**
 * @group Media
 */
class MediaHandlerIntegrationTest extends MediaWikiMediaTestCase {

	/**
	 * @covers \MediaHandler::formatTag
	 * @covers \MediaHandler::formatMetadataHelper
	 */
	public function testFormatMetadataHelper() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'en' );
		$testHandler = new class extends MediaHandler {
			public function formatMetadata( $image, $context = false ) {
				return $this->formatMetadataHelper( [
					'UnitTestOverride' => 'abc',
					'UnitTestDelete' => 'def',
					'UnitTestOther' => '1234.5678',
				], $context );
			}

			protected function formatTag( $key, $vals, $context = false ) {
				if ( $key === 'UnitTestOverride' ) {
					return 'Override';
				} elseif ( $key === 'UnitTestDelete' ) {
					return null;
				} else {
					return false;
				}
			}

			public function getParamMap() {
				throw new LogicException( 'should never get here' );
			}

			public function validateParam( $name, $value ) {
				throw new LogicException( 'should never get here' );
			}

			public function makeParamString( $params ) {
				throw new LogicException( 'should never get here' );
			}

			public function parseParamString( $str ) {
				throw new LogicException( 'should never get here' );
			}

			public function normaliseParams( $image, &$params ) {
				throw new LogicException( 'should never get here' );
			}

			public function getImageSize( $image, $path ) {
				throw new LogicException( 'should never get here' );
			}

			public function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
				throw new LogicException( 'should never get here' );
			}
		};
		$file = $this->dataFile( 'Tux.svg', 'image/svg+xml' );
		$result = $testHandler->formatMetadata( $file );
		$this->assertEqualsCanonicalizing( [
			'visible' => [
			],
			'collapsed' => [
				[
					'id' => 'exif-unittestoverride',
					'name' => 'unittestoverride',
					// Note that formatTag overrode the formatted result here
					'value' => 'Override'
				],
				[
					'id' => 'exif-unittestother',
					'name' => 'unittestother',
					// Note that this value went through Language::formatNum()
					'value' => '1,234.5678'
				],
				// Note that unittestdelete is missing as expected
			],
		], $result );
	}
}
