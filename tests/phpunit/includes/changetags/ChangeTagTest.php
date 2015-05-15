<?php

class ChangeTagTest extends ChangeTagsTest {

	protected function setUp() {
		parent::setUp();
	}
	protected function tearDown() {
		parent::tearDown();
	}

	/**
	 * @covers ChangeTag::getHitcount
	 */
	public function testGetHitcount() {
		$changeTag = new FakeChangeTag( 'UndefinedTag', $this->fakeContext );
		$this->assertEquals( 65, $changeTag->getHitcount() );
	}

	/**
	 * @covers ChangeTag::getExtensionName
	 */
	public function testGetExtensionName() {
		$changeTag = new FakeChangeTag( 'ActiveRegisteredTag', $this->fakeContext );
		$this->assertEquals( 'myext', $changeTag->getExtensionName() );
	}

	/**
	 * @dataProvider provideCanCreate
	 * @covers ChangeTag::canCreate
	 */
	public function testCanCreate( $name, $expected ) {
		$changeTag = new FakeChangeTag( $name, $this->fakeContext );
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
		$changeTag = new FakeChangeTag( $name, $this->fakeContext );
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
		$changeTag = new ChangeTag( $name, $this->fakeContext );
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
		$changeTag = new ChangeTag( $name, $this->fakeContext );
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
