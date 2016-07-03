<?php
/**
 * Check syntax of all PHP files in MediaWiki
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
 * Maintenance script to check syntax of all PHP files in MediaWiki.
 *
 * @ingroup Maintenance
 */
class CheckSyntax extends Maintenance {

	// List of files we're going to check
	private $mFiles = [], $mFailures = [], $mWarnings = [];
	private $mIgnorePaths = [], $mNoStyleCheckPaths = [];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Check syntax for all PHP files in MediaWiki' );
		$this->addOption( 'with-extensions', 'Also recurse the extensions folder' );
		$this->addOption(
			'path',
			'Specific path (file or directory) to check, either with absolute path or '
				. 'relative to the root of this MediaWiki installation',
			false,
			true
		);
		$this->addOption(
			'list-file',
			'Text file containing list of files or directories to check',
			false,
			true
		);
		$this->addOption(
			'modified',
			'Check only files that were modified (requires Git command-line client)'
		);
		$this->addOption( 'syntax-only', 'Check for syntax validity only, skip code style warnings' );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		$this->buildFileList();

		$this->output( "Checking syntax (using php -l, this can take a long time)\n" );
		foreach ( $this->mFiles as $f ) {
			$this->checkFileWithCli( $f );
			if ( !$this->hasOption( 'syntax-only' ) ) {
				$this->checkForMistakes( $f );
			}
		}
		$this->output( "\nDone! " . count( $this->mFiles ) . " files checked, " .
			count( $this->mFailures ) . " failures and " . count( $this->mWarnings ) .
			" warnings found\n" );
	}

	/**
	 * Build the list of files we'll check for syntax errors
	 */
	private function buildFileList() {
		global $IP;

		$this->mIgnorePaths = [
		];

		$this->mNoStyleCheckPaths = [
			// Third-party code we don't care about
			"/activemq_stomp/",
			"EmailPage/PHPMailer",
			"FCKeditor/fckeditor/",
			'\bphplot-',
			"/svggraph/",
			"\bjsmin.php$",
			"PEAR/File_Ogg/",
			"QPoll/Excel/",
			"/geshi/",
			"/smarty/",
		];

		if ( $this->hasOption( 'path' ) ) {
			$path = $this->getOption( 'path' );
			if ( !$this->addPath( $path ) ) {
				$this->error( "Error: can't find file or directory $path\n", true );
			}

			return; // process only this path
		} elseif ( $this->hasOption( 'list-file' ) ) {
			$file = $this->getOption( 'list-file' );
			MediaWiki\suppressWarnings();
			$f = fopen( $file, 'r' );
			MediaWiki\restoreWarnings();
			if ( !$f ) {
				$this->error( "Can't open file $file\n", true );
			}
			$path = trim( fgets( $f ) );
			while ( $path ) {
				$this->addPath( $path );
			}
			fclose( $f );

			return;
		} elseif ( $this->hasOption( 'modified' ) ) {
			$this->output( "Retrieving list from Git... " );
			$files = $this->getGitModifiedFiles( $IP );
			$this->output( "done\n" );
			foreach ( $files as $file ) {
				if ( $this->isSuitableFile( $file ) && !is_dir( $file ) ) {
					$this->mFiles[] = $file;
				}
			}

			return;
		}

		$this->output( 'Building file list...', 'listfiles' );

		// Only check files in these directories.
		// Don't just put $IP, because the recursive dir thingie goes into all subdirs
		$dirs = [
			$IP . '/includes',
			$IP . '/mw-config',
			$IP . '/languages',
			$IP . '/maintenance',
			$IP . '/skins',
		];
		if ( $this->hasOption( 'with-extensions' ) ) {
			$dirs[] = $IP . '/extensions';
		}

		foreach ( $dirs as $d ) {
			$this->addDirectoryContent( $d );
		}

		// Manually add two user-editable files that are usually sources of problems
		if ( file_exists( "$IP/LocalSettings.php" ) ) {
			$this->mFiles[] = "$IP/LocalSettings.php";
		}

		$this->output( 'done.', 'listfiles' );
	}

	/**
	 * Returns a list of tracked files in a Git work tree differing from the master branch.
	 * @param string $path Path to the repository
	 * @return array Resulting list of changed files
	 */
	private function getGitModifiedFiles( $path ) {

		global $wgMaxShellMemory;

		if ( !is_dir( "$path/.git" ) ) {
			$this->error( "Error: Not a Git repository!\n", true );
		}

		// git diff eats memory.
		$oldMaxShellMemory = $wgMaxShellMemory;
		if ( $wgMaxShellMemory < 1024000 ) {
			$wgMaxShellMemory = 1024000;
		}

		$ePath = wfEscapeShellArg( $path );

		// Find an ancestor in common with master (rather than just using its HEAD)
		// to prevent files only modified there from showing up in the list.
		$cmd = "cd $ePath && git merge-base master HEAD";
		$retval = 0;
		$output = wfShellExec( $cmd, $retval );
		if ( $retval !== 0 ) {
			$this->error( "Error retrieving base SHA1 from Git!\n", true );
		}

		// Find files in the working tree that changed since then.
		$eBase = wfEscapeShellArg( rtrim( $output, "\n" ) );
		$cmd = "cd $ePath && git diff --name-only --diff-filter AM $eBase";
		$retval = 0;
		$output = wfShellExec( $cmd, $retval );
		if ( $retval !== 0 ) {
			$this->error( "Error retrieving list from Git!\n", true );
		}

		$wgMaxShellMemory = $oldMaxShellMemory;

		$arr = [];
		$filename = strtok( $output, "\n" );
		while ( $filename !== false ) {
			if ( $filename !== '' ) {
				$arr[] = "$path/$filename";
			}
			$filename = strtok( "\n" );
		}

		return $arr;
	}

	/**
	 * Returns true if $file is of a type we can check
	 * @param string $file
	 * @return bool
	 */
	private function isSuitableFile( $file ) {
		$file = str_replace( '\\', '/', $file );
		$ext = pathinfo( $file, PATHINFO_EXTENSION );
		if ( $ext != 'php' && $ext != 'inc' && $ext != 'php5' ) {
			return false;
		}
		foreach ( $this->mIgnorePaths as $regex ) {
			$m = [];
			if ( preg_match( "~{$regex}~", $file, $m ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Add given path to file list, searching it in include path if needed
	 * @param string $path
	 * @return bool
	 */
	private function addPath( $path ) {
		global $IP;

		return $this->addFileOrDir( $path ) || $this->addFileOrDir( "$IP/$path" );
	}

	/**
	 * Add given file to file list, or, if it's a directory, add its content
	 * @param string $path
	 * @return bool
	 */
	private function addFileOrDir( $path ) {
		if ( is_dir( $path ) ) {
			$this->addDirectoryContent( $path );
		} elseif ( file_exists( $path ) ) {
			$this->mFiles[] = $path;
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Add all suitable files in given directory or its subdirectories to the file list
	 *
	 * @param string $dir Directory to process
	 */
	private function addDirectoryContent( $dir ) {
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $dir ),
			RecursiveIteratorIterator::SELF_FIRST
		);
		foreach ( $iterator as $file ) {
			if ( $this->isSuitableFile( $file->getRealPath() ) ) {
				$this->mFiles[] = $file->getRealPath();
			}
		}
	}

	/**
	 * Check a file for syntax errors using php -l
	 * @param string $file Path to a file to check for syntax errors
	 * @return bool
	 */
	private function checkFileWithCli( $file ) {
		$res = exec( 'php -l ' . wfEscapeShellArg( $file ) );
		if ( strpos( $res, 'No syntax errors detected' ) === false ) {
			$this->mFailures[$file] = $res;
			$this->output( $res . "\n" );

			return false;
		}

		return true;
	}

	/**
	 * Check a file for non-fatal coding errors, such as byte-order marks in the beginning
	 * or pointless ?> closing tags at the end.
	 *
	 * @param string $file String Path to a file to check for errors
	 */
	private function checkForMistakes( $file ) {
		foreach ( $this->mNoStyleCheckPaths as $regex ) {
			$m = [];
			if ( preg_match( "~{$regex}~", $file, $m ) ) {
				return;
			}
		}

		$text = file_get_contents( $file );
		$tokens = token_get_all( $text );

		$this->checkEvilToken( $file, $tokens, '@', 'Error supression operator (@)' );
		$this->checkRegex( $file, $text, '/^[\s\r\n]+<\?/', 'leading whitespace' );
		$this->checkRegex( $file, $text, '/\?>[\s\r\n]*$/', 'trailing ?>' );
		$this->checkRegex( $file, $text, '/^[\xFF\xFE\xEF]/', 'byte-order mark' );
	}

	private function checkRegex( $file, $text, $regex, $desc ) {
		if ( !preg_match( $regex, $text ) ) {
			return;
		}

		if ( !isset( $this->mWarnings[$file] ) ) {
			$this->mWarnings[$file] = [];
		}
		$this->mWarnings[$file][] = $desc;
		$this->output( "Warning in file $file: $desc found.\n" );
	}

	private function checkEvilToken( $file, $tokens, $evilToken, $desc ) {
		if ( !in_array( $evilToken, $tokens ) ) {
			return;
		}

		if ( !isset( $this->mWarnings[$file] ) ) {
			$this->mWarnings[$file] = [];
		}
		$this->mWarnings[$file][] = $desc;
		$this->output( "Warning in file $file: $desc found.\n" );
	}
}

$maintClass = "CheckSyntax";
require_once RUN_MAINTENANCE_IF_MAIN;
