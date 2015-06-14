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

	/**
	 * @dataProvider provideClearCachesAfterUpdate
	 * @covers ChangeTagsContext::clearCachesAfterUpdate
	 */
	public function testClearCachesAfterUpdate( $tagsToAdd, $tagsToRemove, $expected ) {
		$returned = ChangeTagsContext::clearCachesAfterUpdate( $tagsToAdd, $tagsToRemove );

		sort( $returned );
		sort( $expected );
		$this->assertEquals( $returned, $expected );
	}

	public function provideClearCachesAfterUpdate() {
		return array(
			array(
				array( 'StoredTagWithNoHits' ),
				array(),
				array( 'tag-stats-reactive', 'tag-stats-stable', 'valid-tags-hook' )
			),
			array(
				array( 'TagWithFewHits' ),
				array(),
				array( 'tag-stats-reactive' )
			),
			array(
				array( 'TagWithManyHits' ),
				array(),
				array()
			),
			array(
				array( 'TagWithManyHits' ),
				array( 'TagWithFewHits' ),
				array( 'tag-stats-reactive' )
			),
		);
	}
}
