<?php

class ChangeTagsContextTest extends ChangeTagsTest {

	protected function setUp() {
		parent::setUp();
	}
	protected function tearDown() {
		parent::tearDown();
	}

	/**
	 * @covers ChangeTagsContext::definedTags
	 */
	public function testDefinedTags() {
		$actual = $this->fakeContext->getDefined();
		$expected = array(
			'StoredTagNoHits' => array( 'active' => true ),
			'StoredTagWithHits' => array( 'active' => true ),
			'ActiveRegisteredTag' => array(
				'active' => true, 'extName' => 'myext',
			),
			'InactiveRegisteredTag' => array(),
			);

		ksort( $expected );
		ksort( $actual );
		$this->assertEquals( $expected, $actual );
	}
}
