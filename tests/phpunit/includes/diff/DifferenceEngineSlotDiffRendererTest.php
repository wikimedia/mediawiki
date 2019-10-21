<?php

/**
 * @covers DifferenceEngineSlotDiffRenderer
 */
class DifferenceEngineSlotDiffRendererTest extends MediaWikiIntegrationTestCase {

	public function testGetDiff() {
		$differenceEngine = new CustomDifferenceEngine();
		$slotDiffRenderer = new DifferenceEngineSlotDiffRenderer( $differenceEngine );
		$oldContent = ContentHandler::makeContent( 'xxx', null, CONTENT_MODEL_TEXT );
		$newContent = ContentHandler::makeContent( 'yyy', null, CONTENT_MODEL_TEXT );

		$diff = $slotDiffRenderer->getDiff( $oldContent, $newContent );
		$this->assertEquals( 'xxx|yyy', $diff );

		$diff = $slotDiffRenderer->getDiff( null, $newContent );
		$this->assertEquals( '|yyy', $diff );

		$diff = $slotDiffRenderer->getDiff( $oldContent, null );
		$this->assertEquals( 'xxx|', $diff );
	}

	public function testAddModules() {
		$output = $this->getMockBuilder( OutputPage::class )
			->disableOriginalConstructor()
			->setMethods( [ 'addModules' ] )
			->getMock();
		$output->expects( $this->once() )
			->method( 'addModules' )
			->with( 'foo' );
		$differenceEngine = new CustomDifferenceEngine();
		$slotDiffRenderer = new DifferenceEngineSlotDiffRenderer( $differenceEngine );
		$slotDiffRenderer->addModules( $output );
	}
}
