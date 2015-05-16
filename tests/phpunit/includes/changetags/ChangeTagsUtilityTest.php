<?php
class ChangeTagsUtilityTest extends MediaWikiTestCase {
	private $context;
	private $utility;

	public function setUp() {
		parent::setUp();
		$this->context = RequestContext::getMain();
		$this->utility = new ChangeTagsUtility( $this->context );
	}

	/**
	 * @dataProvider provideDecodedStrippedTagDescription
	 * @covers ChangeTagsUtility::decodedStrippedTagDescription
	 */
	public function testDecodedStrippedTagDescription( $tag, $desc, $plainDesc ) {
		$this->context->expects( $this->any() )->method( 'msg' )
			->will( $this->returnValue( $desc ) );
		$this->assertEquals( $plainDesc, $this->utility->decodedStrippedTagDescription( $tag ) );
	}

	public function provideDecodedStrippedTagDescription() {
		return [
			[ 'plaintag', 'Plain Tag', 'Plain Tag' ],
			[ 'linkedtag', '[[linked]] tag', 'linked tag' ],
			[ 'boldedtag', '\'\'\'bolded tag\'\'\'', 'bolded tag' ],
		];
	}

}
