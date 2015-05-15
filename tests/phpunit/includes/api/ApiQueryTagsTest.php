<?php

/**
 * @group API
 */
class ApiQueryTagsTest extends ApiTestCase {

	/**
	 * @covers ApiQueryTags::fillDefinedTags
	 */
	public function testFillDefinedTags() {
		$definedTags = [ 'extTag' => [ 'active' => true, 'extName' => 'myExt' ],
			'coreTag' => [ 'active' => true ], 'userTag' => [ 'active' => false ] ];
		ApiQueryTags::fillDefinedTags( $definedTags, 'd' );
		$this->assertEquals( $definedTags, [ 'extTag' => 0, 'userTag' => 0 ] );
	}
}
