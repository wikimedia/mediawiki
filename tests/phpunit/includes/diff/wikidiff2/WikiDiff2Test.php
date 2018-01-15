<?php

/**
 * @author Andrew Kostka < Andrew.Kostka@wikimedia.de >
 *
 * @group Diff
 */
class WikiDiff2Test extends MediaWikiTestCase {

	private function getTestCases() {
		return [
			[ 1, "Honeyguide" ],
			[ 2, "Rogers" ]
		];
	}

	public function testWikiDiff2Output() {
		global $wgExternalDiffEngine;

		if ( !function_exists( 'wikidiff2_do_diff' ) || $wgExternalDiffEngine !== false ) {
			$this->markTestSkipped( "Skip test, since diff2 is not configured" );
		}

		foreach ( $this->getTestCases() as $case ) {
			$oldText = file_get_contents( __DIR__ . "/files/" . $case[0] . "/old.txt" );
			$newText = file_get_contents( __DIR__ . "/files/" . $case[0] . "/new.txt" );
			$resText = file_get_contents( __DIR__ . "/files/" . $case[0] . "/res.txt" );

			$oldText = str_replace( "\r\n", "\n", $oldText );
			$newText = str_replace( "\r\n", "\n", $newText );
			$resText = str_replace( "\r\n", "\n", $resText );

			$diff = wikidiff2_do_diff(
				$oldText,
				$newText,
				2,
				0
			);

			$this->assertEquals( $resText, $diff, $case[1] );
		}
	}
}
