<?php

class ChangeTagsContextTest extends MediaWikiTestCase {
	private $context;
	private $stats;
	private $stored;
	private $registered;
	private $core;

	protected function setUp() {
		parent::setUp();
		$this->core = [
			'mw-coretag1' => [ 'active' => true ],
			'mw-coretag2' => [ 'active' => false ],
			'mw-coretag3' => [],
			'mw-coretag4' => null,
		];
		$this->setMwGlobals( [ 'wgCoreTags' => $this->core ] );
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
	 * @covers ChangeTagsContext::getDefinedTags
	 */
	public function testGetDefinedTags() {
		$this->context->expects( $this->any() )->method( 'fetchStored' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'fetchRegistered' )
			->will( $this->returnValue( $this->registered ) );
		$result = $this->context->getDefinedTags();
		$expected = array_merge( $this->stored, $this->registered, $this->core );
		ksort( $result );
		ksort( $expected );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * @dataProvider provideCanUpdateTags
	 * @covers ChangeTagsContext::canUpdateTags
	 */
	public function testCanUpdateTags( $added, $removed, $expected ) {
		$this->context->expects( $this->any() )->method( 'getTagStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'fetchStored' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'fetchRegistered' )
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
			[ [ 'mw-contentmodelchange' ], [], false ],
			[ [], [ 'mw-contentmodelchange' ], false ],
		];
	}
}
