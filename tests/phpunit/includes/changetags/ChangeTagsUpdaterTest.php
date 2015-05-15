<?php

class ChangeTagsUpdaterTest extends MediaWikiTestCase {
	private $context;

	protected function setUp() {
		parent::setUp();
		$registered = [
			'ActiveRegisteredTag' => [
				'active' => true, 'extName' => 'myext',
			],
			'InactiveRegisteredTag' => [],
		];
		$stored = [
			'StoredTag' => [ 'active' => true ],
		];
		$core = [
			'mw-contentmodelchange' => [ 'active' => true ],
		];
		$config = RequestContext::getMain()->getConfig();
		$this->context = $this->getMockBuilder( 'ChangeTagsContext' )
			->setConstructorArgs( [ $config ] )
			->setMethods( [ 'getUserTags', 'getSoftwareTags' ] )->getMock();
		$this->context->expects( $this->any() )->method( 'getUserTags' )
			->will( $this->returnValue( $stored ) );
		$this->context->expects( $this->any() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( array_merge( $registered, $core ) ) );
	}

	/**
	 * @dataProvider provideCanAddTagsAccompanyingChange
	 * @covers ChangeTagsUpdater::canAddTagsAccompanyingChange
	 */
	public function testCanAddTagsAccompanyingChange( $added, $expected ) {
		$lang = RequestContext::getMain()->getLanguage();
		$updater = new ChangeTagsUpdater( $this->context, null, $lang );
		$this->assertEquals( $expected,
			$updater->canAddTagsAccompanyingChange( $added )->isGood() );
	}

	public function provideCanAddTagsAccompanyingChange() {
		return [
			[ [ 'ActiveRegisteredTag' ], false ],
			[ [ 'InactiveRegisteredTag' ], false ],
			[ [ 'StoredTag' ], true ],
			[ [ 'UndefinedTag' ], false ],
		];
	}

	/**
	 * @dataProvider provideCanUpdateTags
	 * @covers ChangeTagsUpdater::canUpdateTags
	 */
	public function testCanUpdateTags( $added, $removed, $expected ) {
		$lang = RequestContext::getMain()->getLanguage();
		$updater = new ChangeTagsUpdater( $this->context, null, $lang );
		$this->assertEquals( $expected, $updater->canUpdateTags( $added, $removed )->isGood() );
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
			[ [ 'mw-contentmodelchange' ], [], false ],
			[ [], [ 'mw-contentmodelchange' ], false ],
		];
	}
}
