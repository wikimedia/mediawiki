<?php

/**
 * @group API
 * @group Database
 * @group medium
 */
class ApiBaseTest extends ApiTestCase {

	/**
	 * @covers ApiBase::requireOnlyOneParameter
	 */
	public function testRequireOnlyOneParameterDefault() {
		$mock = new MockApi();
		$mock->requireOnlyOneParameter(
			array( "filename" => "foo.txt", "enablechunks" => false ),
			"filename", "enablechunks"
		);
		$this->assertTrue( true );
	}

	/**
	 * @expectedException UsageException
	 * @covers ApiBase::requireOnlyOneParameter
	 */
	public function testRequireOnlyOneParameterZero() {
		$mock = new MockApi();
		$mock->requireOnlyOneParameter(
			array( "filename" => "foo.txt", "enablechunks" => 0 ),
			"filename", "enablechunks"
		);
	}

	/**
	 * @expectedException UsageException
	 * @covers ApiBase::requireOnlyOneParameter
	 */
	public function testRequireOnlyOneParameterTrue() {
		$mock = new MockApi();
		$mock->requireOnlyOneParameter(
			array( "filename" => "foo.txt", "enablechunks" => true ),
			"filename", "enablechunks"
		);
	}

}
