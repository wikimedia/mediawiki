<?php

namespace MediaWiki\Tests\Structure;

use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
abstract class OwnersStructureTestBase extends TestCase {
	private const HELP_MESSAGE = 'Please check the format of your OWNERS.md file against'
		. 'https://www.mediawiki.org/wiki/OWNERS.md'
		. ' if you think you are viewing this in error.';

	/**
	 * Returns a list of files or paths that do not need a OWNERS.md entries
	 *
	 * @return array of paths
	 */
	public function getUntestedFiles(): array {
		return [
			'.browserslistrc',
			'.md',
			'.eslintrc.json',
			'.htaccess',
			'.gitkeep',
			'.d.ts'
		];
	}

	/**
	 * Returns array of sections that must be present in each OWNERS.md entry.
	 */
	public function getRequiredSections(): array {
		return [ 'Contact' ];
	}

	/**
	 * @return string[] an array of folders in the base directory that
	 *  should be tested for ownership.
	 */
	abstract public function getFolders(): array;

	/**
	 * @return string Path to OWNERS.md file
	 */
	abstract public function getOwnersFile(): string;

	private static array $ownerSections;

	private ?array $ownership = null;

	protected function setUp(): void {
		parent::setUp();
		$lines = file( $this->getOwnersFile(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
		self::$ownerSections = self::parseOwnersFile( $lines );
	}

	/**
	 * Parse the OWNERS.md file into structured sections.
	 *
	 * @param string[] $lines Lines from the OWNERS.md file
	 * @return array Associative array of sections, keyed by section title
	 */
	private static function parseOwnersFile( array $lines ): array {
		$sections = [];
		$currentSection = null;
		$currentListKey = null;

		foreach ( $lines as $line ) {
			if ( self::isSectionHeader( $line ) ) {
				// Save the previous section before starting a new one
				if ( $currentSection !== null ) {
					$sections[ $currentSection['title'] ] = $currentSection;
				}
				$currentSection = self::createNewSection( $line );
				$currentListKey = null;
			} elseif ( self::isMetadataLine( $line ) ) {
				[ $currentSection, $currentListKey ] = self::processMetadataLine(
					$line,
					$currentSection,
					$currentListKey
				);
			} elseif ( self::isBulletedListItem( $line ) ) {
				$currentSection = self::addBulletedItem( $line, $currentSection, $currentListKey );
			}
		}

		// Save the final section provided it is not empty
		if ( $currentSection !== null && count( array_keys( $currentSection ) ) > 1 ) {
			$sections[ $currentSection['title'] ] = $currentSection;
		}

		return $sections;
	}

	/**
	 * Check if a line is a section header (starts with "## ").
	 */
	private static function isSectionHeader( string $line ): bool {
		return str_starts_with( $line, '## ' );
	}

	/**
	 * Check if a line is a metadata line (starts with "* ").
	 */
	private static function isMetadataLine( string $line ): bool {
		return str_starts_with( $line, '* ' ) && str_contains( $line, ':' );
	}

	/**
	 * Check if a line is a bulleted list item (starts with "  - ").
	 */
	private static function isBulletedListItem( string $line ): bool {
		return str_starts_with( $line, '  - ' );
	}

	/**
	 * Create a new section from a header line.
	 *
	 * @param string $line Section header line
	 * @return array New section array with title
	 */
	private static function createNewSection( string $line ): array {
		return [ 'title' => trim( substr( $line, 3 ) ) ];
	}

	/**
	 * Process a metadata line (e.g., "* Files:", "* Contact:", etc.).
	 *
	 * @param string $line The metadata line to process
	 * @param array|null $section Current section being built
	 * @param string|null $currentListKey Current list key being populated
	 * @return array Tuple of [updated section, updated currentListKey]
	 */
	private static function processMetadataLine(
		string $line,
		?array $section,
		?string $currentListKey
	): array {
		[ $label, $value ] = explode( ':', substr( $line, 2 ), 2 );
		$label = trim( $label );
		$value = trim( $value );

		if ( self::isListMetadata( $label ) ) {
			// Initialize array for bulleted list items
			$section[$label] = [];
			$currentListKey = $label;
			// If there's a value on the same line, include it
			if ( $value !== '' ) {
				// The value may be a list of files separated by commas.
				$values = explode( ',', $value );
				foreach ( $values as $path ) {
					$path = trim( $path );
					if ( $path ) {
						$section = self::addItem( $section, $label, $path );
					}
				}
			}
		} else {
			// Regular metadata field (Contact, Since, Phabricator, etc.)
			$section[$label] = $value;
			$currentListKey = null;
		}

		return [ $section, $currentListKey ];
	}

	/**
	 * Check if a metadata label expects a list of values.
	 */
	private static function isListMetadata( string $label ): bool {
		return in_array( $label, [ 'Files', 'Folders' ] );
	}

	/**
	 * Add a path to the current section.
	 *
	 * @param array $section Current section being built
	 * @param string $currentListKey Current list key being populated
	 * @param string $path
	 * @return array Updated section
	 */
	private static function addItem( array $section, string $currentListKey, string $path ): array {
		$path = trim( $path );
		if ( $path[ 0 ] !== '/' ) {
			$path = '/' . $path;
		}
		$section[ $currentListKey ][] = $path;
		return $section;
	}

	/**
	 * Add a bulleted list item to the current section.
	 *
	 * @param string $line The bulleted list line
	 * @param array|null $section Current section being built
	 * @param string|null $currentListKey Current list key being populated
	 * @return array|null Updated section
	 */
	private static function addBulletedItem(
		string $line,
		?array $section,
		?string $currentListKey
	): ?array {
		if ( $currentListKey !== null && $section !== null ) {
			$section = self::addItem( $section, $currentListKey, substr( $line, 4 ) );
		}
		return $section;
	}

	/**
	 * @param string $folder
	 * @return string[] of all files and directories in the folder.
	 */
	private function getFilesInFolder( string $folder ): array {
		$handle = opendir( $this->getRootFolder() . '/' . $folder );
		$files = [];
		$entry = readdir( $handle );
		while ( $entry ) {
			$path = $folder . '/' . $entry;
			if ( $entry != '.' && $entry != '..' ) {
				$files[] = $path;
			}
			$entry = readdir( $handle );
		}
		closedir( $handle );
		return $files;
	}

	/**
	 * Get the root folder for all files being tested.
	 *
	 * @return string The directory containing the OWNERS.md file,
	 *  which is the base for relative paths in the file.
	 */
	private function getRootFolder(): string {
		return dirname( $this->getOwnersFile() );
	}

	/**
	 * Parse the OWNERS.md file and return the ownership structure.
	 * The expected format is described in https://www.mediawiki.org/wiki/OWNERS.md.
	 *
	 * @return array with keys 'folders' and 'files' containing arrays of owned paths
	 */
	private function getOwnership(): array {
		if ( $this->ownership !== null ) {
			return $this->ownership;
		}
		$ownedFolders = [];
		$ownedFiles = [];

		foreach ( self::$ownerSections as $section ) {
			if ( isset( $section['Folders'] ) ) {
				foreach ( $section['Folders'] as $folder ) {
					if ( $folder ) {
						$ownedFolders[] = $folder;
					}
				}
			}

			if ( isset( $section['Files'] ) ) {
				$ownedFiles = array_merge( $ownedFiles, $section['Files'] );
			}
		}
		$this->ownership = [
			'folders' => $ownedFolders,
			'files' => $ownedFiles,
		];
		return $this->ownership;
	}

	/**
	 * @param string[] $fileEntries List of file entries to check
	 * @param string[] $ownedFiles List of owned file paths from OWNERS.md
	 * @param string[] $ownedFolders List of owned folder paths from OWNERS.md
	 * @param string $label Label for error messages (e.g., folder name)
	 * @param string $localBasePath Base path to prepend to file entries for ownership checks
	 * @return void calls fail if any file entry is not accounted for in the owned files or folders,
	 * directly or indirectly.
	 */
	private function checkFilesAreOwned(
		array $fileEntries,
		array $ownedFiles,
		array $ownedFolders,
		string $label = '',
		string $localBasePath = '',
	): void {
		foreach ( $fileEntries as $entry ) {
			$name = $entry;

			// support [ 'name' => 'file.js' ] as well as 'file.js'
			if ( is_array( $name ) ) {
				if ( !isset( $name['name'] ) ) {
					continue;
				}

				$name = $name['name'];
			}

			$relativePath = $localBasePath . '/' . $name;

			// #2: Is the resource in an owned folder?
			$found = false;

			foreach ( $ownedFolders as $ownedFolder ) {
				if ( str_starts_with( $relativePath, $ownedFolder ) ) {
					$found = true;

					break;
				}
			}

			// #3: Finally, is the resource an owned file?
			if ( !$found ) {
				foreach ( $ownedFiles as $ownedFile ) {
					if ( str_ends_with( $relativePath, $ownedFile ) ) {
						$found = true;
						break;
					}
				}
			}

			if ( !$found ) {
				foreach ( $this->getUntestedFiles() as $extension ) {
					if (
						str_ends_with( $relativePath, $extension ) ||
						str_starts_with( $relativePath, $extension )
					) {
						$found = true;
						break;
					}
				}
			}

			if ( !$found ) {
				$isDirectory = is_dir( $this->getRootFolder() . '/' . $relativePath );
				if ( $isDirectory ) {
					$this->checkFilesAreOwned(
						$this->getFilesInFolder( $name ),
						$ownedFiles,
						$ownedFolders,
						$label,
						$localBasePath
					);
					// if this succeeds all files were accounted for so not needed.
				} else {
					$this->fail(
						"Resource $relativePath ($label) isn't documented as owned in OWNERS.md.\n\n"
							. self::HELP_MESSAGE
					);
				}
			}
		}
	}

	/**
	 * Tests the OWNERS.md file ensuring:
	 * - it is not empty (in which case it skipped)
	 * - it has the required sections (as defined in self::getRequiredSections)
	 * - it has either a Files or Folders label (you can't own nothing!)
	 */
	public function testOwnersFile() {
		if ( count( self::$ownerSections ) === 0 ) {
			$this->markTestSkipped( 'OWNERS.md file is empty or has no sections.' );
		}

		$expectedResourceLabels = [ 'Folders', 'Files' ];

		$requiredSections = $this->getRequiredSections();
		foreach ( self::$ownerSections as $title => $section ) {
			foreach ( $requiredSections as $sectionLabel ) {
				$this->assertArrayHasKey( $sectionLabel, $section, "OWNERS.md § $title has $sectionLabel label" );
			}

			$actualResourceLabels = array_intersect( $expectedResourceLabels, array_keys( $section ) );

			$this->assertTrue(
				count( $actualResourceLabels ) >= 1,
				"OWNERS.md § $title has either a Files or Folders label"
			);
		}
	}

	/**
	 * Using the OWNERS.md file, check that all folders defined in self::getFolders() are owned.
	 * The test ensures that for each folder:
	 * - all files and subfolders are accounted for in the OWNERS.md file, meaning
	 *   they are either directly owned as files, or indirectly owned via an owned folder.
	 * The test is skipped if:
	 * - there are no folders defined in self::getFolders()
	 */
	public function testFoldersAreOwned() {
		$folders = $this->getFolders();
		if ( count( $folders ) === 0 ) {
			$this->markTestSkipped( 'No folders defined to check ownership for.\n\n' . self::HELP_MESSAGE );
		}

		$ownership = $this->getOwnership();
		$ownedFolders = $ownership['folders'];
		$ownedFiles = $ownership['files'];

		foreach ( $folders as $checkFolder ) {
			$this->checkFilesAreOwned(
				$this->getFilesInFolder( $checkFolder ),
				$ownedFiles,
				$ownedFolders,
				"$checkFolder folder"
			);
		}
		$this->assertTrue( true, 'OWNERS.md documents ownership of all folders.' );
	}
}
