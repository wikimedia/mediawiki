<?php
/**
 * Unit tests for wfRemoveDotSegments()
 */

class wfRemoveDotSegments extends MediaWikiTestCase {
	/** @dataProvider providePaths */
	public function testWfRemoveDotSegments( $inputPath, $outputPath ) {
		$this->assertEquals(
			$outputPath,
			wfRemoveDotSegments( $inputPath ),
			"Testing $inputPath expands to $outputPath"
		);
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
			array( '/.../a', '/.../a' ),
			array( '.../a', '.../a' ),
			array( '', '' ),
			array( '/', '/' ),
			array( '//', '//' ),
			array( '.', '' ),
			array( '..', '' ),
			array( '...', '...' ),
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
