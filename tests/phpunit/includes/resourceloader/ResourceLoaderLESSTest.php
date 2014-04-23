<?php

class ResourceLoaderLESSTest extends MediaWikiTestCase {
	public static function lessProvider() {
		$result = array();
		foreach ( glob( __DIR__ . '/fixtures/*.less' ) as $file ) {
			$result[] = array( $file );
		}

		return $result;
	}

	/**
	 * @dataProvider lessProvider
	 */
	public function testLessFile( $lessFile ) {
		$cssFile = substr( $lessFile, 0, -4 ) . 'css';
		if ( !file_exists( $cssFile ) ) {
			$this->fail( "No css file found to assert equal to $lessFile" );
			return;
		}

		$expect = file_get_contents( $cssFile );
		$content = file_get_contents( $lessFile );
		$result = ResourceLoader::getLessCompiler()->compile( $content, $lessFile );
		$this->assertEquals( $expect, $result );
	}
}
