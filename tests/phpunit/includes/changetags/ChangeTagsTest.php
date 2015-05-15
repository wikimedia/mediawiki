<?php

class ChangeTagsTest extends MediaWikiTestCase {
	private $context;
	private $stats;
	private $stored;
	private $registered;

	protected function setUp() {
		parent::setUp();
		$config = RequestContext::getMain()->getConfig();
		$this->context = $this->getMockBuilder( 'ChangeTagsContext' )
			->setConstructorArgs( [ $config ] )
			->setMethods( [ 'getTagStats', 'getUserTags', 'getSoftwareTags' ] )->getMock();
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
	 * @dataProvider provideCanAddTagsAccompanyingChange
	 * @covers ChangeTags::canAddTagsAccompanyingChange
	 */
	public function testCanAddTagsAccompanyingChange( $added, $expected ) {
		$this->context->expects( $this->any() )->method( 'getTagStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getUserTags' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( $this->registered ) );
		$this->assertEquals( $expected,
			ChangeTags::canAddTagsAccompanyingChange( $added )->isGood() );
	}

	public function provideCanAddTagsAccompanyingChange() {
		return [
			[ [ 'ActiveRegisteredTag' ], false ],
			[ [ 'InactiveRegisteredTag' ], false ],
			[ [ 'StoredTag' ], true ],
			[ [ 'UndefinedTag' ], false ],
		];
	}
}
