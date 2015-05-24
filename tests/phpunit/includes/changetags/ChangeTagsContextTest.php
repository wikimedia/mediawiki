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
		$config = RequestContext::getMain()->getConfig();
		$coreTags = $config->get( 'CoreTags' );
		$actual = $this->fakeContext->getDefined();
		$expectedNonCore = array(
			'StoredTagNoHits' => array( 'active' => true ),
			'StoredTagWithHits' => array( 'active' => true ),
			'ActiveRegisteredTag' => array(
				'active' => true, 'extName' => 'myext',
			),
			'InactiveRegisteredTag' => array(),
			);
		$expected = array_merge( $expectedNonCore, $coreTags );

		ksort( $expected );
		ksort( $actual );
		$this->assertEquals( $expected, $actual );
	}
}
