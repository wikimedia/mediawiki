<?php

/**
 * @covers MediaWikiTestCase
 *
 * @group Database
 * @group MediaWikiTestCaseTest
 */
class MediaWikiTestCaseSchema1Test extends MediaWikiTestCase {

	public static $hasRun = false;

	public function getSchemaOverrides() {
		return [
			[ 'imagelinks', 'MediaWikiTestCaseTestTable' ],
			[ __DIR__ . '/MediaWikiTestCaseSchemaTest.sql' ]
		];
	}

	public function testMediaWikiTestCaseSchemaTestOrder() {
		// The test must be run before the second test
		self::$hasRun = true;
		$this->assertTrue( self::$hasRun );
	}

	public function testSchemaExtension() {
		// make sure we can use the MediaWikiTestCaseTestTable table

		$input = [ 'id' => '5', 'name' => 'Test' ];

		$this->db->insert(
			'MediaWikiTestCaseTestTable',
			$input
		);

		$output = $this->db->selectRow( 'MediaWikiTestCaseTestTable', array_keys( $input ), [] );
		$this->assertEquals( (object)$input, $output );
	}

	public function testSchemaOverride() {
		// make sure we can use the il_frobniz field

		$input = [
			'il_from' => '7',
			'il_from_namespace' => '0',
			'il_to' => 'Foo.jpg',
			'il_frobniz' => 'Xyzzy',
		];

		$this->db->insert(
			'imagelinks',
			$input
		);

		$output = $this->db->selectRow( 'imagelinks', array_keys( $input ), [] );
		$this->assertEquals( (object)$input, $output );
	}

}
