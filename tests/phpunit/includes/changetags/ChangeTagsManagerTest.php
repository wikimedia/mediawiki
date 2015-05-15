<?php

class ChangeTagsManagerTest extends MediaWikiTestCase {
	private $context;

	protected function setUp() {
		parent::setUp();
		$config = RequestContext::getMain()->getConfig();
		$this->context = $this->getMockBuilder( 'ChangeTagsContext' )
			->setConstructorArgs( [ $config ] )
			->setMethods( [ 'getTagStats', 'getUserTags', 'getSoftwareTags' ] )
			->getMock();
		$stats = [
			'UndefinedTag' => 65,
			'StoredTagWithHits' => 30,
		];
		$stored = [
			'StoredTagNoHits' => [ 'active' => true ],
			'StoredTagWithHits' => [ 'active' => true ],
		];
		$registered = [
			'ActiveRegisteredTag' => [
				'active' => true, 'extName' => 'myext',
			],
			'InactiveRegisteredTag' => [],
		];
		$this->context->expects( $this->any() )->method( 'getTagStats' )
			->will( $this->returnValue( $stats ) );
		$this->context->expects( $this->any() )->method( 'getUserTags' )
			->will( $this->returnValue( $stored ) );
		$this->context->expects( $this->any() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( $registered ) );
	}

	/**
	 * @dataProvider provideCanCreateTag
	 * @covers ChangeTagsManager::canCreateTag
	 */
	public function testCanCreateTag( $name, $expected ) {
		$manager = new ChangeTagsManager( $this->context );
		$this->assertEquals( $expected, $manager->canCreateTag( $name )->isGood() );
	}

	public function provideCanCreateTag() {
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
	 * @dataProvider provideCanDeleteTag
	 * @covers ChangeTagsManager::canDeleteTag
	 */
	public function testCanDeleteTag( $name, $expected ) {
		$manager = new ChangeTagsManager( $this->context );
		$this->assertEquals( $expected, $manager->canDeleteTag( $name )->isGood() );
	}

	public function provideCanDeleteTag() {
		return [
			[ 'UndefinedTag', true ],
			[ 'StoredTagNoHits', true ],
			[ 'StoredTagWithHits', true ],
			[ 'ActiveRegisteredTag', false ],
			[ 'InactiveRegisteredTag', false ],
		];
	}

	/**
	 * @dataProvider provideCanActivateTag
	 * @covers ChangeTagsManager::canActivateTag
	 */
	public function testCanActivateTag( $name, $expected ) {
		$manager = new ChangeTagsManager( $this->context );
		$this->assertEquals( $expected, $manager->canActivateTag( $name )->isGood() );
	}

	public function provideCanActivateTag() {
		return [
			[ 'UndefinedTag', true ],
			[ 'StoredTagNoHits', false ],
			[ 'StoredTagWithHits', false ],
			[ 'ActiveRegisteredTag', false ],
			[ 'InactiveRegisteredTag', false ],
		];
	}

	/**
	 * @dataProvider provideCanDeactivateTag
	 * @covers ChangeTagsManager::canDeactivateTag
	 */
	public function testCanDeactivateTag( $name, $expected ) {
		$manager = new ChangeTagsManager( $this->context );
		$this->assertEquals( $expected, $manager->canDeactivateTag( $name )->isGood() );
	}

	public function provideCanDeactivateTag() {
		return [
			[ 'StoredTagWithHits', true ],
			[ 'UndefinedTag', false ],
			[ 'StoredTagNoHits', true ],
			[ 'ActiveRegisteredTag', false ],
			[ 'InactiveRegisteredTag', false ],
		];
	}
}
