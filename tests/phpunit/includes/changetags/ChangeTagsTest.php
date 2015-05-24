<?php

class FakeChangeTagsContext extends ChangeTagsContext {
	public function getStored() {
		return array(
			'StoredTagNoHits' => array( 'active' => true ),
			'StoredTagWithHits' => array( 'active' => true ),
		);
	}
	public function getRegistered() {
		return array(
			'ActiveRegisteredTag' => array(
				'active' => true, 'extName' => 'myext',
			),
			'InactiveRegisteredTag' => array(),
		);
	}
	public function getStats() {
		return array(
			'UndefinedTag' => 65,
			'StoredTagWithHits' => 30,
		);
	}
}

class FakeChangeTag extends ChangeTag {
	function __construct( $tag, FakeChangeTagsContext $context ) {
		parent::__construct( $tag, $context );
	}
}

class ChangeTagsTest extends MediaWikiTestCase {
	protected $fakeContext = null;

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( array(
			'wgUseAutoTagging' => true,
		) );
		$this->fakeContext = new FakeChangeTagsContext;
	}

	protected function tearDown() {
		parent::tearDown();
	}
}
