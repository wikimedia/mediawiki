<?php

class ChangeTagTest extends MediaWikiTestCase {
	private $context;
	private $stats;
	private $stored;
	private $registered;

	protected function setUp() {
		parent::setUp();
		$config = RequestContext::getMain()->getConfig();
		$this->context = $this->getMockBuilder( 'ChangeTagsContext' )
			->setConstructorArgs( [ $config ] )
			->setMethods( [ 'getTagStats', 'getUserTags', 'getSoftwareTags' ] )
			->getMock();
		$this->stats = [
			'UndefinedTag' => 65,
			'StoredTagWithHits' => 30,
		];
		$this->registered = [
			'ActiveRegisteredTag' => [
				'active' => true, 'extName' => 'myext',
			],
			'InactiveRegisteredTag' => [],
		];
		$this->stored = [
			'StoredTagNoHits' => [ 'active' => true ],
			'StoredTagWithHits' => [ 'active' => true ],
		];
	}

	/**
	 * @covers ChangeTag::getHitcount
	 */
	public function testGetHitcount() {
		$this->context->expects( $this->once() )->method( 'getTagStats' )
			->will( $this->returnValue( $this->stats ) );
		$changeTag = new ChangeTag( 'UndefinedTag', $this->context );
		$this->assertEquals( 65, $changeTag->getHitcount() );
	}

	/**
	 * @covers ChangeTag::getExtensionName
	 */
	public function testGetExtensionName() {
		$this->context->expects( $this->once() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( 'ActiveRegisteredTag', $this->context );
		$this->assertEquals( 'myext', $changeTag->getExtensionName() );
	}

	/**
	 * @dataProvider provideCanCreate
	 * @covers ChangeTag::canCreate
	 */
	public function testCanCreate( $name, $expected ) {
		$this->context->expects( $this->any() )->method( 'getTagStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getUserTags' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( $name, $this->context );
		$this->assertEquals( $expected, $changeTag->canCreate()->isGood() );
	}

	public function provideCanCreate() {
		return [
			[ 'NewTag', true ],
			[ '8', false ],
			[ 'BadTagName,WithComa', false ],
			[ 'UndefinedTag', false ],
			[ 'StoredTagNoHits', false ],
			[ 'ActiveRegisteredTag', false ],
			[ 'InactiveRegisteredTag', false ],
		];
	}

	/**
	 * @dataProvider provideCanDelete
	 * @covers ChangeTag::canDelete
	 */
	public function testCanDelete( $name, $expected ) {
		$this->context->expects( $this->any() )->method( 'getTagStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getUserTags' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( $name, $this->context );
		$this->assertEquals( $expected, $changeTag->canDelete()->isGood() );
	}

	public function provideCanDelete() {
		return [
			[ 'UndefinedTag', true ],
			[ 'StoredTagNoHits', true ],
			[ 'StoredTagWithHits', true ],
			[ 'ActiveRegisteredTag', false ],
			[ 'InactiveRegisteredTag', false ],
		];
	}

	/**
	 * @dataProvider provideCanActivate
	 * @covers ChangeTag::canActivate
	 */
	public function testCanActivate( $name, $expected ) {
		$this->context->expects( $this->any() )->method( 'getTagStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getUserTags' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( $name, $this->context );
		$this->assertEquals( $expected, $changeTag->canActivate()->isGood() );
	}

	public function provideCanActivate() {
		return [
			[ 'UndefinedTag', true ],
			[ 'StoredTagNoHits', false ],
			[ 'StoredTagWithHits', false ],
			[ 'ActiveRegisteredTag', false ],
			[ 'InactiveRegisteredTag', false ],
		];
	}

	/**
	 * @dataProvider provideCanDeactivate
	 * @covers ChangeTag::canDeactivate
	 */
	public function testCanDeactivate( $name, $expected ) {
		$this->context->expects( $this->any() )->method( 'getTagStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getUserTags' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( $name, $this->context );
		$this->assertEquals( $expected, $changeTag->canDeactivate()->isGood() );
	}

	public function provideCanDeactivate() {
		return [
			[ 'StoredTagWithHits', true ],
			[ 'UndefinedTag', false ],
			[ 'StoredTagNoHits', false ],
			[ 'ActiveRegisteredTag', false ],
			[ 'InactiveRegisteredTag', false ],
		];
	}
}
