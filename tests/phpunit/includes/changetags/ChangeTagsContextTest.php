<?php

class ChangeTagsContextTest extends MediaWikiTestCase {
	private $context;
	private $stats;
	private $stored;
	private $registered;

	protected function setUp() {
		parent::setUp();
		$this->context = $this->getMockBuilder( 'ChangeTagsContext' )->setMethods(
			[ 'getTagStats', 'getUserTags', 'getSoftwareTags' ] )->getMock();
		$this->stats = [
			'UndefinedTag' => 65,
			'StoredTag' => 30,
		];
		$this->registered = [
			'ActiveRegisteredTag' => [
				'active' => true, 'extName' => 'myext',
			],
			'InactiveRegisteredTag' => [],
		];
		$this->stored = [
			'StoredTag' => [ 'active' => true ],
		];
	}

	/**
	 * @dataProvider provideCanUpdateTags
	 * @covers ChangeTagsContext::canUpdateTags
	 */
	public function testCanUpdateTags( $added, $removed, $expected ) {
		$this->context->expects( $this->any() )->method( 'getTagStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getUserTags' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( $this->registered ) );
		$this->assertEquals( $expected,
			$this->context->canUpdateTags( $added, $removed )->isGood() );
	}

	public function provideCanUpdateTags() {
		return [
			[ [ 'ActiveRegisteredTag' ], [], false ],
			[ [ 'InactiveRegisteredTag' ], [], false ],
			[ [], [ 'ActiveRegisteredTag' ], false ],
			[ [], [ 'InactiveRegisteredTag' ], false ],
			[ [ 'StoredTag' ], [], true ],
			[ [ 'UndefinedTag' ], [], false ],
			[ [], [ 'StoredTag' ], true ],
			[ [], [ 'UndefinedTag' ], true ],
		];
	}
}
