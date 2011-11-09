<?php
/**
 * Unit tests for wfRemoveDotSegments()
 */

class wfRemoveDotSegments extends MediaWikiTestCase {
	/** @dataProvider providePaths */
	public function testWfRemoveDotSegments( $inputPath, $outputPath ) {
		$actualPath = wfRemoveDotSegments( $inputPath );
		$message = "Testing $inputPath expands to $outputPath";
		echo $message . "\n";
		$this->assertEquals( $outputPath, $actualPath, $message );
	}

	/**
	 * Provider of URL paths for testing wfRemoveDotSegments()
	 *
	 * @return array
	 */
	public function providePaths() {
		return array(
			array( '/a/b/c/./../../g', '/a/g' ),
			array( 'mid/content=5/../6', 'mid/6' ),
			array( '/a//../b', '/a/b' ),
			array( '', '' ),
			array( '/', '/' ),
			array( '//', '//' ),
			array( '.', '' ),
			array( '..', '' ),
			array( '/.', '/' ),
			array( '/..', '/' ),
			array( './', '' ),
			array( '../', '' ),
			array( './a', 'a' ),
			array( '../a', 'a' ),
			array( '../../a', 'a' ),
			array( '.././a', 'a' ),
			array( './../a', 'a' ),
			array( '././a', 'a' ),
			array( '../../', '' ),
			array( '.././', '' ),
			array( './../', '' ),
			array( '././', '' ),
			array( '../..', '' ),
			array( '../.', '' ),
			array( './..', '' ),
			array( './.', '' ),
			array( '/../../a', '/a' ),
			array( '/.././a', '/a' ),
			array( '/./../a', '/a' ),
			array( '/././a', '/a' ),
			array( '/../../', '/' ),
			array( '/.././', '/' ),
			array( '/./../', '/' ),
			array( '/././', '/' ),
			array( '/../..', '/' ),
			array( '/../.', '/' ),
			array( '/./..', '/' ),
			array( '/./.', '/' ),
			array( 'b/../../a', '/a' ),
			array( 'b/.././a', '/a' ),
			array( 'b/./../a', '/a' ),
			array( 'b/././a', 'b/a' ),
			array( 'b/../../', '/' ),
			array( 'b/.././', '/' ),
			array( 'b/./../', '/' ),
			array( 'b/././', 'b/' ),
			array( 'b/../..', '/' ),
			array( 'b/../.', '/' ),
			array( 'b/./..', '/' ),
			array( 'b/./.', 'b/' ),
			array( '/b/../../a', '/a' ),
			array( '/b/.././a', '/a' ),
			array( '/b/./../a', '/a' ),
			array( '/b/././a', '/b/a' ),
			array( '/b/../../', '/' ),
			array( '/b/.././', '/' ),
			array( '/b/./../', '/' ),
			array( '/b/././', '/b/' ),
			array( '/b/../..', '/' ),
			array( '/b/../.', '/' ),
			array( '/b/./..', '/' ),
			array( '/b/./.', '/b/' ),
		);
	}
}
