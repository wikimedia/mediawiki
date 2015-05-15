<?php

/**
 * @group API
 */
class ApiQueryTagsTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideFillDefinedTags
	 * @covers ApiQueryTags::fillDefinedTags
	 */
	public function testFillDefinedTags( $definedTags, $continue, $expected ) {
		ApiQueryTags::fillDefinedTags( $definedTags, $continue );
		$this->assertEquals( $definedTags, $expected );
	}

	public function provideFillDefinedTags() {
		return [
			[
				[ 'extTag' => [ 'active' => true, 'extName' => 'myExt' ],
				'coreTag' => [ 'active' => true ], 'userTag' => [ 'active' => false ] ],
				'd',
				[ 'extTag' => 0, 'userTag' => 0 ]
			],
			[
				[ 'extTag' => [ 'active' => true, 'extName' => 'myExt' ],
				'coreTag' => [ 'active' => true ], 'userTag' => [ 'active' => false ] ],
				null,
				[ 'extTag' => 0, 'coreTag' => 0, 'userTag' => 0 ]
			],
			[
				[ 'extTag' => [ 'active' => true, 'extName' => 'myExt' ],
				'coreTag' => [ 'active' => true ], 'userTag' => [ 'active' => false ] ],
				w,
				[]
			],
		];
	}
}
