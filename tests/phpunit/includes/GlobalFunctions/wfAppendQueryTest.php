<?php

/**
 * @group GlobalFunctions
 * @covers ::wfAppendQuery
 */
class WfAppendQueryTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideAppendQuery
	 */
	public function testAppendQuery( $expected, $url, $query ) {
		$this->assertEquals( $expected, wfAppendQuery( $url, $query ) );
	}

	public static function provideAppendQuery() {
		return array(
			// Empty query
			array(
				'http://www.example.org/index.php',
				'http://www.example.org/index.php',
				''
			),
			// Array query
			array(
				'http://www.example.org/index.php?foo=bar',
				'http://www.example.org/index.php',
				array( 'foo' => 'bar' )
			),
			// Query string already present
			array(
				'http://www.example.org/index.php?foz=baz&foo=bar',
				'http://www.example.org/index.php?foz=baz',
				'foo=bar'
			)
		);
	}
}
