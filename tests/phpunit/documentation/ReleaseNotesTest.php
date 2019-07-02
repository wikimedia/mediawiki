<?php

/**
 * James doesn't like having to manually fix these things.
 */
class ReleaseNotesTest extends MediaWikiTestCase {
	/**
	 * Verify that at least one Release Notes file exists, have content, and
	 * aren't overly long.
	 *
	 * @group documentation
	 * @coversNothing
	 */
	public function testReleaseNotesFilesExistAndAreNotMalformed() {
		global $wgVersion, $IP;

		$notesFiles = glob( "$IP/RELEASE-NOTES-*" );

		$this->assertGreaterThanOrEqual(
			1,
			count( $notesFiles ),
			'Repo has at least one Release Notes file.'
		);

		$versionParts = explode( '.', explode( '-', $wgVersion )[0] );
		$this->assertContains(
			"$IP/RELEASE-NOTES-$versionParts[0].$versionParts[1]",
			$notesFiles,
			'Repo has a Release Notes file for the current $wgVersion.'
		);

		foreach ( $notesFiles as $index => $fileName ) {
			$this->assertFileLength( "Release Notes", $fileName );
		}
	}

	public static function provideFilesAtRoot() {
		global $IP;

		$rootFiles = [
			"COPYING",
			"FAQ",
			"HISTORY",
			"INSTALL",
			"README",
			"SECURITY",
		];

		foreach ( $rootFiles as $rootFile ) {
			yield "$rootFile file" => [ "$IP/$rootFile" ];
		}
	}

	/**
	 * @dataProvider provideFilesAtRoot
	 * @coversNothing
	 */
	public function testRootFilesHaveProperLineLength( $fileName ) {
		$this->assertFileLength( "Help", $fileName );
	}

	private function assertFileLength( $type, $fileName ) {
		$lines = file( $fileName, FILE_IGNORE_NEW_LINES );

		$this->assertNotFalse(
			$lines,
			"$type file '$fileName' is inaccessible."
		);

		$errors = [];
		foreach ( $lines as $i => $line ) {
			$num = $i + 1;

			// FILE_IGNORE_NEW_LINES drops the \n at the EOL, so max length is 80 not 81.
			$max_length = 80;

			$length = mb_strlen( $line );
			if ( $length <= $max_length ) {
				continue;
			}
			$errors[] = "line $num: length $length > $max_length:\n$line";
		}
		# Use assertSame() instead of assertEqual(), to show the full line in the diff
		$this->assertSame(
			[],
			$errors,
			"$type file '$fileName' lines " .
			"have at most $max_length characters"
		);
	}
}
