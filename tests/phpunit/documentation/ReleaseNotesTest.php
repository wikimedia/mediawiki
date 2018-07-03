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
			$file = file( $fileName, FILE_IGNORE_NEW_LINES );

			$this->assertFalse(
				!$file,
				"Release Notes file '$fileName' is inaccessible."
			);

			$lines = count( $file );

			for ( $i = 0; $i < $lines; $i++ ) {
				$line = $file[$i];

				$this->assertLessThanOrEqual(
					// FILE_IGNORE_NEW_LINES drops the \n at the EOL, so max length is 80 not 81.
					80,
					strlen( $line ),
					"Release notes file '$fileName' line $i is longer than 80 chars:\n\t'$line'"
				);
			}
		}
	}
}
