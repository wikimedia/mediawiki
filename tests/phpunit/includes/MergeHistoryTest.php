<?php

/**
 * @group Database
 */
class MergeHistoryTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideIsValidMerge
	 * @covers MergeHistory::isValidMerge
	 * @param $source string Source page
	 * @param $dest string Destination page
	 * @param $timestamp string|bool Timestamp up to which revisions are merged (or false for all)
	 * @param $error string|bool Expected error for test (or true for no error)
	 */
	public function testIsValidMove( $source, $dest, $timestamp, $error ) {
		$this->setMwGlobals( 'wgContentHandlerUseDB', false );
		$mh = new MergeHistory(
			Title::newFromText( $source ),
			Title::newFromText( $dest ),
			$timestamp
		);
		$status = $mh->isValidMerge();
		if ( $error === true ) {
			$this->assertTrue( $status->isGood() );
		} else {
			$this->assertTrue( $status->hasMessage( $error ) );
		}
	}

	/**
	 * Make some pages to work with
	 */
	public function addDBData() {
		$this->insertPage( 'Test' );
		$this->insertPage( 'Test2' );
		$this->insertPage( 'oldpage' );
		$newPage = $this->insertPage( 'newpage' );

		// Dummy edit to newpage so it has a timestamp after that of oldpage
		$wikiNewPage = WikiPage::newFromID( $newPage['id'] );
		$wikiNewPage->doEditContent(
			ContentHandler::makeContent( 'Changing stuff', $wikiNewPage->getTitle() ),
			'Changing stuff'
		);

	}

	public static function provideIsValidMerge() {
		return array(
			// for MergeHistory::isValidMerge
			array( 'Test', 'Test2', false, true ),
			array( 'Test', 'Test', false, 'mergehistory-fail-self-merge' ),
			array( 'newpage', 'oldpage', false, 'mergehistory-fail-timestamps-overlap' ),
			array( 'Nonexistant', 'Test2', false, 'mergehistory-fail-invalid-source' ),
			array( 'Test', 'Nonexistant', false, 'mergehistory-fail-invalid-dest' ),
			array(
				'Test',
				'Test2',
				'This is obviously an invalid timestamp',
				'mergehistory-fail-bad-timestamp'
			),
		);
	}
}
