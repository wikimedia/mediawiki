<?php

/**
 * @author Andrew Kostka < Andrew.Kostka@wikimedia.de >
 *
 * @group Diff
 */
class WikiDiff2Test extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		// set thresholds when applicable
		ini_set( 'wikidiff2.change_threshold', 0.15 );
		ini_set( 'wikidiff2.char_run_threshold', 7 );
	}

	/**
	 * Provider of URL paths for testing wfRemoveDotSegments()
	 *
	 * @return array
	 */
	public function provideWikiDiff2Output() {
		return [
			[ 1, "Honeyguide" ],
			[ 2, "Rogers" ],
			[ 3, "DuckDuckGo" ],
			[ 4, "Internationale Mathematik-Olympiade" ],
			[ 5, "Marbella Cup" ],
			[ 6, "Red imported fire ant" ],
			[ 7, "Benutzer:PDD/markAdmins.js" ],
			[ 8, "Bahnhof Flüelen" ],
		];
	}

	/**
	 * @dataProvider provideWikiDiff2Output
	 */
	public function testWikiDiff2OutputWithoutMoves( $fileName, $title ) {
		global $wgExternalDiffEngine;

		if ( !function_exists( 'wikidiff2_do_diff' ) || $wgExternalDiffEngine !== false ) {
			$this->markTestSkipped( "Skip test, since diff2 is not configured" );
		}

		$oldText = file_get_contents( __DIR__ . "/files/" . $fileName . "/old.txt" );
		$newText = file_get_contents( __DIR__ . "/files/" . $fileName . "/new.txt" );
		$resText = file_get_contents( __DIR__ . "/files/" . $fileName . "/res.txt" );

		$oldText = str_replace( "\r\n", "\n", $oldText );
		$newText = str_replace( "\r\n", "\n", $newText );
		$resText = str_replace( "\r\n", "\n", $resText );

		$diff = wikidiff2_do_diff(
			$oldText,
			$newText,
			2,
			0
		);

		$this->assertEquals( $resText, $diff, $title );
	}

	/**
	 * @dataProvider provideWikiDiff2Output
	 */
	public function testWikiDiff2OutputWithMoves( $fileName, $title ) {
		global $wgExternalDiffEngine;

		if ( !function_exists( 'wikidiff2_do_diff' ) || $wgExternalDiffEngine !== false ) {
			$this->markTestSkipped( "Skip test, since diff2 is not configured" );
		}

		$oldText = file_get_contents( __DIR__ . "/files/" . $fileName . "/old.txt" );
		$newText = file_get_contents( __DIR__ . "/files/" . $fileName . "/new.txt" );
		$resText = file_get_contents( __DIR__ . "/files/" . $fileName . "/res_moves.txt" );

		$oldText = str_replace( "\r\n", "\n", $oldText );
		$newText = str_replace( "\r\n", "\n", $newText );
		$resText = str_replace( "\r\n", "\n", $resText );

		$diff = wikidiff2_do_diff(
			$oldText,
			$newText,
			2,
			25
		);

		$this->assertEquals( $resText, $diff, $title );
	}
}
