<?php

/**
 * @group GlobalFunctions
 * @covers ::wfAppendQuery
 */
class WfAppendQueryTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideAppendQuery
	 */
	public function testAppendQuery( $url, $query, $expected, $message = null ) {
		$this->assertEquals( $expected, wfAppendQuery( $url, $query ), $message );
	}

	public static function provideAppendQuery() {
		return array(
			array(
				'http://www.example.org/index.php',
				'',
				'http://www.example.org/index.php',
				'No query'
			),
			array(
				'http://www.example.org/index.php',
				array( 'foo' => 'bar' ),
				'http://www.example.org/index.php?foo=bar',
				'Set query array'
			),
			array(
				'http://www.example.org/index.php?foz=baz',
				'foo=bar',
				'http://www.example.org/index.php?foz=baz&foo=bar',
				'Set query string'
			),
			array(
				'http://www.example.org/index.php?foo=bar',
				'',
				'http://www.example.org/index.php?foo=bar',
				'Empty string with query'
			),
			array(
				'http://www.example.org/index.php?foo=bar',
				array( 'baz' => 'quux' ),
				'http://www.example.org/index.php?foo=bar&baz=quux',
				'Add query array'
			),
			array(
				'http://www.example.org/index.php?foo=bar',
				'baz=quux',
				'http://www.example.org/index.php?foo=bar&baz=quux',
				'Add query string'
			),
			array(
				'http://www.example.org/index.php?foo=bar',
				array( 'baz' => 'quux', 'foo' => 'baz' ),
				'http://www.example.org/index.php?foo=bar&baz=quux&foo=baz',
				'Modify query array'
			),
			array(
				'http://www.example.org/index.php?foo=bar',
				'baz=quux&foo=baz',
				'http://www.example.org/index.php?foo=bar&baz=quux&foo=baz',
				'Modify query string'
			)
		);
	}
}
