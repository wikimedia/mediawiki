<?php
class ChangeTagsPresentationTest extends MediaWikiTestCase {
	private $context;
	private $presentation;

	public function setUp() {
		parent::setUp();
		$this->context = $this->getMockBuilder( 'RequestContext' )
			->setMethods( [ 'msg' ] )
			->getMock();
		$this->presentation = new ChangeTagsPresentation( $this->context );
	}

	/**
	 * @dataProvider provideDecodedStrippedTagDescription
	 * @covers ChangeTagsPresentation::decodedStrippedTagDescription
	 */
	public function testDecodedStrippedTagDescription( $tag, $desc, $plainDesc ) {
		$msg = new RawMessage( $desc );
		$this->context->expects( $this->any() )->method( 'msg' )
			->will( $this->returnValue( $msg ) );
		$this->assertEquals( $plainDesc, $this->presentation->decodedStrippedTagDescription( $tag ) );
	}

	public function provideDecodedStrippedTagDescription() {
		return [
			[ 'plaintag', 'Plain Tag', 'Plain Tag' ],
			[ 'linkedtag', '[[linked]] tag', 'linked tag' ],
			[ 'boldedtag', '\'\'\'bolded tag\'\'\'', 'bolded tag' ],
		];
	}

}
