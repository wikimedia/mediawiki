<?php

class ChangeTagTest extends ChangeTagsTest {

	/**
	 * @dataProvider provideCanCreate
	 * @covers ChangeTag::canCreate
	 */
	public function testCanCreate( $name, $expected ) {
		$changeTag = new ChangeTag( $name );
		$this->assertEquals( $changeTag->canCreate()->isGood(), $expected );
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
		$changeTag = new ChangeTag( $name );
		$this->assertEquals( $changeTag->canDelete()->isGood(), $expected );
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
		$changeTag = new ChangeTag( $name );
		$this->assertEquals( $changeTag->canActivate()->isGood(), $expected );
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

	/**
	 * @dataProvider provideCanDeactivate
	 * @covers ChangeTag::canDeactivate
	 */
	public function testCanDeactivate( $name, $expected ) {
		$changeTag = new ChangeTag( $name );
		$this->assertEquals( $changeTag->canDeactivate()->isGood(), $expected );
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
