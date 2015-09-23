<?php

/**
 * @covers DifferenceEngine
 *
 * @todo tests for the rest of DifferenceEngine!
 *
 * @group Database
 * @group Diff
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class DifferenceEngineTest extends MediaWikiTestCase {

	protected $context;

	private static $revisions;

	protected function setUp() {
		parent::setUp();

		$title = $this->getTitle();

		$this->context = new RequestContext();
		$this->context->setTitle( $title );

		if ( !self::$revisions ) {
			self::$revisions = $this->doEdits();
		}
	}

	/**
	 * @return Title
	 */
	protected function getTitle() {
		$namespace = $this->getDefaultWikitextNS();
		return Title::newFromText( 'Kitten', $namespace );
	}

	/**
	 * @return int[] Revision ids
	 */
	protected function doEdits() {
		$title = $this->getTitle();
		$page = WikiPage::factory( $title );

		$strings = array( "it is a kitten", "two kittens", "three kittens", "four kittens" );
		$revisions = array();

		foreach ( $strings as $string ) {
			$content = ContentHandler::makeContent( $string, $title );
			$page->doEditContent( $content, 'edit page' );
			$revisions[] = $page->getLatest();
		}

		return $revisions;
	}

	public function testMapDiffPrevNext() {
		$cases = $this->getMapDiffPrevNextCases();

		foreach ( $cases as $case ) {
			list( $expected, $old, $new, $message ) = $case;

			$diffEngine = new DifferenceEngine( $this->context, $old, $new, 2, true, false );
			$diffMap = $diffEngine->mapDiffPrevNext( $old, $new );
			$this->assertEquals( $expected, $diffMap, $message );
		}
	}

	private function getMapDiffPrevNextCases() {
		$revs = self::$revisions;

		return array(
			array( array( $revs[1], $revs[2] ), $revs[2], 'prev', 'diff=prev' ),
			array( array( $revs[2], $revs[3] ), $revs[2], 'next', 'diff=next' ),
			array( array( $revs[1], $revs[3] ), $revs[1], $revs[3], 'diff=' . $revs[3] )
		);
	}

	public function testLoadRevisionData() {
		$cases = $this->getLoadRevisionDataCases();

		foreach ( $cases as $case ) {
			list( $expectedOld, $expectedNew, $old, $new, $message ) = $case;

			$diffEngine = new DifferenceEngine( $this->context, $old, $new, 2, true, false );
			$diffEngine->loadRevisionData();

			$this->assertEquals( $diffEngine->getOldid(), $expectedOld, $message );
			$this->assertEquals( $diffEngine->getNewid(), $expectedNew, $message );
		}
	}

	private function getLoadRevisionDataCases() {
		$revs = self::$revisions;

		return array(
			array( $revs[2], $revs[3], $revs[3], 'prev', 'diff=prev' ),
			array( $revs[2], $revs[3], $revs[2], 'next', 'diff=next' ),
			array( $revs[1], $revs[3], $revs[1], $revs[3], 'diff=' . $revs[3] ),
			array( $revs[1], $revs[3], $revs[1], 0, 'diff=0' )
		);
	}

	public function testGetOldid() {
		$revs = self::$revisions;

		$diffEngine = new DifferenceEngine( $this->context, $revs[1], $revs[2], 2, true, false );
		$this->assertEquals( $revs[1], $diffEngine->getOldid(), 'diff get old id' );
	}

	public function testGetNewid() {
		$revs = self::$revisions;

		$diffEngine = new DifferenceEngine( $this->context, $revs[1], $revs[2], 2, true, false );
		$this->assertEquals( $revs[2], $diffEngine->getNewid(), 'diff get new id' );
	}

}
