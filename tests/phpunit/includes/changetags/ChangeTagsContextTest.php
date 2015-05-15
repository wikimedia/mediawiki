<?php

class ChangeTagsContextTest extends ChangeTagsTest {

	/**
	 * @covers ChangeTagsContext::definedTags
	 */
	public function testDefinedTags() {
		$returned = array_keys( ChangeTagContext::definedTags() );
		$expected = array(
			'StoredTagNoHits',
			'StoredTagWithHits',
			'ActiveRegisteredTag',
			'InactiveRegisteredTag',
			);

		sort( $returned );
		sort( $expected );
		$this->assertEquals( $returned, $expected );
	}
}
