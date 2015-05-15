<?php

class ChangeTagsContextTest extends MediaWikiTestCase {
	private $context;
	private $stored;
	private $registered;
	private $core;

	protected function setUp() {
		parent::setUp();
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
			'StoredTag' => [ 'active' => true ],
		];
		$this->core = [
			'mw-contentmodelchange' => [ 'active' => true ],
		];
		$config = RequestContext::getMain()->getConfig();
		$this->context = $this->getMockBuilder( 'ChangeTagsContext' )
			->setConstructorArgs( [ $config ] )
			->setMethods( [ 'getUserTags', 'getSoftwareTags' ] )->getMock();
		$this->context->expects( $this->any() )->method( 'getUserTags' )
			->will( $this->returnValue( $this->stored ) );
		$this->context->expects( $this->any() )->method( 'getSoftwareTags' )
			->will( $this->returnValue( array_merge( $this->registered, $this->core ) ) );
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
	 * @covers ChangeTagsContext::getExtensionName
	 */
	public function testGetExtensionName() {
		$this->assertEquals( 'myext', $this->context->getExtensionName( 'ActiveRegisteredTag' ) );
	}
}
