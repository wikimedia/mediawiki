<?php

class ChangeTagTest extends MediaWikiTestCase {
	private $context;
	private $stats;
	private $stored;
	private $registered;

	protected function setUp() {
		parent::setUp();
		$this->context = $this->getMockBuilder( 'ChangeTagsContext' )->setMethods(
			array( 'getStats', 'getStored', 'getRegistered' ) )->getMock();
		$this->stats = array(
			'UndefinedTag' => 65,
			'StoredTagWithHits' => 30,
		);
		$this->registered = array(
			'ActiveRegisteredTag' => array(
				'active' => true, 'extName' => 'myext',
			),
			'InactiveRegisteredTag' => array(),
		);
		$this->stored = array(
			'StoredTagNoHits' => array( 'active' => true ),
			'StoredTagWithHits' => array( 'active' => true ),
		);
	}

	/**
	 * @covers ChangeTag::getHitcount
	 */
	public function testGetHitcount() {
		$this->context->expects( $this->once() )->method( 'getStats' )
			->will( $this->returnValue( $this->stats ) );
		$changeTag = new ChangeTag( 'UndefinedTag', $this->context );
		$this->assertEquals( 65, $changeTag->getHitcount() );
	}

	/**
	 * @covers ChangeTag::getExtensionName
	 */
	public function testGetExtensionName() {
		$this->context->expects( $this->once() )->method( 'getRegistered' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( 'ActiveRegisteredTag', $this->context );
		$this->assertEquals( 'myext', $changeTag->getExtensionName() );
	}

	/**
	 * @dataProvider provideCanCreate
	 * @covers ChangeTag::canCreate
	 */
	public function testCanCreate( $name, $expected ) {
		$this->context->expects( $this->any() )->method( 'getStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getStored' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getRegistered' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( $name, $this->context );
		$this->assertEquals( $expected, $changeTag->canCreate()->isGood() );
	}

	public function provideCanCreate() {
		return array(
			array( 'NewTag', true ),
			array( '8', false ),
			array( 'BadTagName,WithComa', false ),
			array( 'UndefinedTag', false ),
			array( 'StoredTagNoHits', false ),
			array( 'ActiveRegisteredTag', false ),
			array( 'InactiveRegisteredTag', false ),
		);
	}

	/**
	 * @dataProvider provideCanDelete
	 * @covers ChangeTag::canDelete
	 */
	public function testCanDelete( $name, $expected ) {
		$this->context->expects( $this->any() )->method( 'getStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getStored' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getRegistered' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( $name, $this->context );
		$this->assertEquals( $expected, $changeTag->canDelete()->isGood() );
	}

	public function provideCanDelete() {
		return array(
			array( 'UndefinedTag', true ),
			array( 'StoredTagNoHits', true ),
			array( 'StoredTagWithHits', true ),
			array( 'NonexistingTag', false ),
			array( 'ActiveRegisteredTag', false ),
			array( 'InactiveRegisteredTag', false ),
		);
	}

	/**
	 * @dataProvider provideCanActivate
	 * @covers ChangeTag::canActivate
	 */
	public function testCanActivate( $name, $expected ) {
		$this->context->expects( $this->any() )->method( 'getStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getStored' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getRegistered' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( $name, $this->context );
		$this->assertEquals( $expected, $changeTag->canActivate()->isGood() );
	}

	public function provideCanActivate() {
		return array(
			array( 'UndefinedTag', true ),
			array( 'StoredTagNoHits', false ),
			array( 'StoredTagWithHits', false ),
			array( 'NonexistingTag', false ),
			array( 'ActiveRegisteredTag', false ),
			array( 'InactiveRegisteredTag', false ),
		);
	}

	/**
	 * @dataProvider provideCanDeactivate
	 * @covers ChangeTag::canDeactivate
	 */
	public function testCanDeactivate( $name, $expected ) {
		$this->context->expects( $this->any() )->method( 'getStats' )
			->will( $this->returnValue( $this->stats ) );
		$this->context->expects( $this->any() )->method( 'getStored' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getRegistered' )
			->will( $this->returnValue( $this->registered ) );
		$changeTag = new ChangeTag( $name, $this->context );
		$this->assertEquals( $expected, $changeTag->canDeactivate()->isGood() );
	}

	public function provideCanDeactivate() {
		return array(
			array( 'StoredTagWithHits', true ),
			array( 'UndefinedTag', false ),
			array( 'StoredTagNoHits', false ),
			array( 'NonexistingTag', false ),
			array( 'ActiveRegisteredTag', false ),
			array( 'InactiveRegisteredTag', false ),
		);
	}
}
