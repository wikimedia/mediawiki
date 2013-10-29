<?php

/**
 * @covers DifferenceEngine
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class DifferenceEngineTest extends MediaWikiTestCase {

	protected $context;

	public function setUp() {
		parent::setUp();

		$title = $this->getTitle();

		$this->context = new RequestContext();
		$this->context->setTitle( $title );
	}

	protected function getTitle() {
		$namespace = $this->getDefaultWikitextNS();
		return Title::newFromText( 'Kitten', $namespace );
	}

	protected function doEdits() {
		$title = $this->getTitle();
		$page = WikiPage::factory( $title );
		$content = ContentHandler::makeContent( "kitten", $title );

		$strings = array( "it is a kitten", "two kittens", "three kittens", "four kittens" );
		$revisions = array();
		$i = 0;

		foreach( $strings as $string ) {
			$page->doEditContent( $content, $string );
			$revisions[] = $page->getLatest();
			$content = ContentHandler::makeContent( $strings[$i], $title );
			$i++;
		}

		return $revisions;
	}

	public function testConstructor() {
		$diffEngine = new DifferenceEngine( $this->context, 1, 2, 2, true, false );
		$this->assertTrue( $diffEngine instanceof DifferenceEngine );
	}

	/**
	 * @dataProvider mapDiffPrevNextProvider
	 */
	public function testMapDiffPrevNext( $expected, $old, $new ) {
		$diffEngine = new DifferenceEngine( $this->context, $old, $new, 2, true, false );
		$diffMap = $diffEngine->mapDiffPrevNext( $old, $new );
		$this->assertEquals( $expected, $diffMap );
	}

	public function mapDiffPrevNextProvider() {
		$revs = $this->doEdits();

		return array(
			array( array( $revs[1], $revs[2] ), $revs[2], 'prev' ),
			array( array( $revs[2], $revs[3] ), $revs[2], 'next' ),
			array( array( $revs[1], $revs[3] ), $revs[1], $revs[3] )
		);
	}

	/**
	 * @dataProvider loadRevisionDataProvider
	 */
	public function testLoadRevisionData( $expectedOld, $expectedNew, $old, $new ) {
		$diffEngine = new DifferenceEngine( $this->context, $old, $new, 2, true, false );
		$diffEngine->loadRevisionData();

		$this->assertEquals( $diffEngine->mOldid, $expectedOld );
		$this->assertEquals( $diffEngine->mNewid, $expectedNew );
	}

	public function loadRevisionDataProvider() {
		$revs = $this->doEdits();

		return array(
			array( $revs[2], $revs[3], $revs[3], 'prev' ),
			array( $revs[2], $revs[3], $revs[2], 'next' ),
			array( $revs[1], $revs[3], $revs[1], $revs[3] ),
			array( $revs[1], $revs[3], $revs[1], 0 )
		);
	}

}
