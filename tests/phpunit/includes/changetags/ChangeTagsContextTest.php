<?php

class ChangeTagsContextTest extends MediaWikiTestCase {
	private $context;
	private $stats;
	private $stored;
	private $registered;
	private $core;

	protected function setUp() {
		parent::setUp();
		$this->registered = [
			'ActiveRegisteredTag' => [
				'active' => true, 'extName' => 'myext',
			],
			'InactiveRegisteredTag' => [],
		];
		$this->stored = [
			'StoredTag' => [ 'active' => true ],
		];
		$this->core = [
			'mw-contentmodelchange' => [ 'active' => true ],
		];
		$config = RequestContext::getMain()->getConfig();
		$this->context = $this->getMockBuilder( 'ChangeTagsContext' )
			->setConstructorArgs( [ $config ] )
			->setMethods( [ 'fetchStored', 'fetchRegistered' ] )->getMock();
		$this->context->expects( $this->any() )->method( 'fetchStored' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'fetchRegistered' )
			->will( $this->returnValue( $this->registered ) );
	}

	/**
	 * @covers ChangeTagsContext::getDefinedTags
	 */
	public function testGetDefinedTags() {
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
