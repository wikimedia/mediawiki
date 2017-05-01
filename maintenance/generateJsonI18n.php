<?php

/**
 * Convert a PHP messages file to a set of JSON messages files.
 *
 * Usage:
 *    php generateJsonI18n.php ExtensionName.i18n.php i18n/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to generate JSON i18n files from a PHP i18n file.
 *
 * @ingroup Maintenance
 */
class GenerateJsonI18n extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Build JSON messages files from a PHP messages file' );

		$this->addArg( 'phpfile', 'PHP file defining a $messages array', false );
		$this->addArg( 'jsondir', 'Directory to write JSON files to', false );
		$this->addOption( 'extension', 'Perform default conversion on an extension',
			false, true );
		$this->addOption( 'supplementary', 'Find supplementary i18n files in subdirs and convert those',
			false, false );
	}

	public function execute() {
		global $IP;

		$phpfile = $this->getArg( 0 );
		$jsondir = $this->getArg( 1 );
		$extension = $this->getOption( 'extension' );
		$convertSupplementaryI18nFiles = $this->hasOption( 'supplementary' );

		if ( $extension ) {
			if ( $phpfile ) {
				$this->error( "The phpfile is already specified, conflicts with --extension.", 1 );
			}
			$phpfile = "$IP/extensions/$extension/$extension.i18n.php";
		}

		if ( !$phpfile ) {
			$this->error( "I'm here for an argument!" );
			$this->maybeHelp( true );
			// dies.
		}

		if ( $convertSupplementaryI18nFiles ) {
			if ( is_readable( $phpfile ) ) {
				$this->transformI18nFile( $phpfile, $jsondir );
			} else {
				// This is non-fatal because we might want to continue searching for
				// i18n files in subdirs even if the extension does not include a
				// primary i18n.php.
				$this->error( "Warning: no primary i18n file was found." );
			}
			$this->output( "Searching for supplementary i18n files...\n" );
			$dir_iterator = new RecursiveDirectoryIterator( dirname( $phpfile ) );
			$iterator = new RecursiveIteratorIterator(
				$dir_iterator, RecursiveIteratorIterator::LEAVES_ONLY );
			foreach ( $iterator as $path => $fileObject ) {
				if ( fnmatch( "*.i18n.php", $fileObject->getFilename() ) ) {
					$this->output( "Converting $path.\n" );
					$this->transformI18nFile( $path );
				}
			}
		} else {
			// Just convert the primary i18n file.
			$this->transformI18nFile( $phpfile, $jsondir );
		}
	}

	public function transformI18nFile( $phpfile, $jsondir = null ) {
		if ( !$jsondir ) {
			// Assume the json directory should be in the same directory as the
			// .i18n.php file.
			$jsondir = dirname( $phpfile ) . "/i18n";
		}
		if ( !is_dir( $jsondir ) ) {
			$this->output( "Creating directory $jsondir.\n" );
			$success = mkdir( $jsondir );
			if ( !$success ) {
				$this->error( "Could not create directory $jsondir", 1 );
			}
		}

		if ( !is_readable( $phpfile ) ) {
			$this->error( "Error reading $phpfile", 1 );
		}
		$messages = null;
		include $phpfile;
		$phpfileContents = file_get_contents( $phpfile );

		if ( !isset( $messages ) ) {
			$this->error( "PHP file $phpfile does not define \$messages array", 1 );
		}

		if ( !$messages ) {
			$this->error( "PHP file $phpfile contains an empty \$messages array. " .
				"Maybe it was already converted?", 1 );
		}

		if ( !isset( $messages['en'] ) || !is_array( $messages['en'] ) ) {
			$this->error( "PHP file $phpfile does not set language codes", 1 );
		}

		foreach ( $messages as $langcode => $langmsgs ) {
			$authors = $this->getAuthorsFromComment( $this->findCommentBefore(
				"\$messages['$langcode'] =",
				$phpfileContents
			) );
			// Make sure the @metadata key is the first key in the output
			$langmsgs = array_merge(
				[ '@metadata' => [ 'authors' => $authors ] ],
				$langmsgs
			);

			$jsonfile = "$jsondir/$langcode.json";
			$success = file_put_contents(
				$jsonfile,
				FormatJson::encode( $langmsgs, "\t", FormatJson::ALL_OK ) . "\n"
			);
			if ( $success === false ) {
				$this->error( "FAILED to write $jsonfile", 1 );
			}
			$this->output( "$jsonfile\n" );
		}

		$this->output(
			"All done. To complete the conversion, please do the following:\n" .
			"* Add \$wgMessagesDirs['YourExtension'] = __DIR__ . '/i18n';\n" .
			"* Remove \$wgExtensionMessagesFiles['YourExtension']\n" .
			"* Delete the old PHP message file\n" .
			"This script no longer generates backward compatibility shims! If you need\n" .
			"compatibility with MediaWiki 1.22 and older, use the MediaWiki 1.23 version\n" .
			"of this script instead, or create a shim manually.\n"
		);
	}

	/**
	 * Find the documentation comment immediately before a given search string
	 * @param string $needle String to search for
	 * @param string $haystack String to search in
	 * @return string Substring of $haystack starting at '/**' ending right before $needle, or empty
	 */
	protected function findCommentBefore( $needle, $haystack ) {
		$needlePos = strpos( $haystack, $needle );
		if ( $needlePos === false ) {
			return '';
		}
		// Need to pass a negative offset to strrpos() so it'll search backwards from the
		// offset
		$startPos = strrpos( $haystack, '/**', $needlePos - strlen( $haystack ) );
		if ( $startPos === false ) {
			return '';
		}

		return substr( $haystack, $startPos, $needlePos - $startPos );
	}

	/**
	 * Get an array of author names from a documentation comment containing @author declarations.
	 * @param string $comment Documentation comment
	 * @return array Array of author names (strings)
	 */
	protected function getAuthorsFromComment( $comment ) {
		$matches = null;
		preg_match_all( '/@author (.*?)$/m', $comment, $matches );

		return $matches && $matches[1] ? $matches[1] : [];
	}
}

$maintClass = "GenerateJsonI18n";
require_once RUN_MAINTENANCE_IF_MAIN;
