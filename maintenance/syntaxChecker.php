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
 * @ingroup Maintenance
 */
 
require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class SyntaxChecker extends Maintenance {

	// List of files we're going to check
	private $mFiles = array(), $mFailures = array(), $mWarnings = array();

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Check syntax for all PHP files in MediaWiki";
		$this->addOption( 'with-extensions', 'Also recurse the extensions folder' );
		$this->addOption( 'path', 'Specific path (file or directory) to check, either with absolute path or relative to the root of this MediaWiki installation',
			false, true);
		$this->addOption( 'list-file', 'Text file containing list of files or directories to check', false, true);
		$this->addOption( 'modified', 'Check only files that were modified (requires SVN command-line client)' );
	}

	protected function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		$this->buildFileList();

		// ParseKit is broken on PHP 5.3+, disabled until this is fixed
		$useParseKit = function_exists( 'parsekit_compile_file' ) && version_compare( PHP_VERSION, '5.3', '<' );

		$this->output( "Checking syntax (this can take a really long time)...\n\n" );
		foreach( $this->mFiles as $f ) {
			if( $useParseKit ) {
				$this->checkFileWithParsekit( $f );
			} else {
				$this->checkFileWithCli( $f );
			}
			$this->checkForMistakes( $f );
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

		if ( $this->hasOption( 'path' ) ) {
			$path = $this->getOption( 'path' );
			if ( !$this->addPath( $path ) ) {
				$this->error( "Error: can't find file or directory $path\n", true );
			}
			return; // process only this path
		} elseif ( $this->hasOption( 'list-file' ) ) {
			$file = $this->getOption( 'list-file' );
			$f = @fopen( $file, 'r' );
			if ( !$f ) {
				$this->error( "Can't open file $file\n", true );
			}
			while( $path = trim( fgets( $f ) ) ) {
				$this->addPath( $path );
			}
			fclose( $f );
			return;
		} elseif ( $this->hasOption( 'modified' ) ) {
			$this->output( "Retrieving list from Subversion... " );
			$parentDir = wfEscapeShellArg( dirname( __FILE__ ) . '/..' );
			$output = wfShellExec( "svn status --ignore-externals $parentDir", $retval );
			if ( $retval ) {
				$this->error( "Error retrieving list from Subversion!\n", true );
			} else {
				$this->output( "done\n" );
			}

			preg_match_all( '/^\s*[AM]\s+(.*?)\r?$/m', $output, $matches );
			$this->mFiles = array_merge( $this->mFiles, $matches[1] );
			return;
		}

		$this->output( "Building file list..." );

		// Only check files in these directories. 
		// Don't just put $IP, because the recursive dir thingie goes into all subdirs
		$dirs = array( 
			$IP . '/includes',
			$IP . '/config',
			$IP . '/languages',
			$IP . '/maintenance',
			$IP . '/skins',
		);
		if( $this->hasOption( 'with-extensions' ) ) {
			$dirs[] = $IP . '/extensions';
		}

		foreach( $dirs as $d ) {
			$this->addDirectoryContent( $d );
		}

		// Manually add two user-editable files that are usually sources of problems
		if ( file_exists( "$IP/LocalSettings.php" ) ) {
			$this->mFiles[] = "$IP/LocalSettings.php";
		}
		if ( file_exists( "$IP/AdminSettings.php" ) ) {
			$this->mFiles[] = "$IP/AdminSettings.php";
		}

		$this->output( "done.\n" );
	}

	/**
	 * Add given path to file list, searching it in include path if needed
	 */
	private function addPath( $path ) {
		global $IP;
		return $this->addFileOrDir( $path ) || $this->addFileOrDir( "$IP/$path" );
	}

	/**
	* Add given file to file list, or, if it's a directory, add its content
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
	 * @param $dir String: directory to process
	 */
	private function addDirectoryContent( $dir ) {
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $dir ), 
			RecursiveIteratorIterator::SELF_FIRST
		);
		foreach ( $iterator as $file ) {
			$ext = pathinfo( $file->getFilename(), PATHINFO_EXTENSION );
			if ( $ext == 'php' || $ext == 'inc' || $ext == 'php5' ) {
				$this->mFiles[] = $file->getRealPath();
			}
		}
	}

	/**
	 * Check a file for syntax errors using Parsekit. Shamelessly stolen
	 * from tools/lint.php by TimStarling
	 * @param $file String Path to a file to check for syntax errors
	 * @return boolean
	 */
	private function checkFileWithParsekit( $file ) {
		static $okErrors = array(
			'Redefining already defined constructor',
			'Assigning the return value of new by reference is deprecated',
		);
		$errors = array();
		parsekit_compile_file( $file, $errors, PARSEKIT_SIMPLE );
		$ret = true;
		if ( $errors ) {
			foreach ( $errors as $error ) {
				foreach ( $okErrors as $okError ) {
					if ( substr( $error['errstr'], 0, strlen( $okError ) ) == $okError ) {
						continue 2;
					}
				}
				$ret = false;
				$this->output( "Error in $file line {$error['lineno']}: {$error['errstr']}\n" );
				$this->mFailures[$file] = $errors;
			}
		}
		return $ret;
	}

	/**
	 * Check a file for syntax errors using php -l
	 * @param $file String Path to a file to check for syntax errors
	 * @return boolean
	 */
	private function checkFileWithCli( $file ) {
		$res = exec( 'php -l ' . wfEscapeShellArg( $file ) ); 
		if( strpos( $res, 'No syntax errors detected' ) === false ) {
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
	 * @param $file String String Path to a file to check for errors
	 * @return boolean
	 */
	private function checkForMistakes( $file ) {
		$text = file_get_contents( $file );

		$this->checkRegex( $file, $text, '/^[\s\r\n]+<\?/', 'leading whitespace' );
		$this->checkRegex( $file, $text, '/\?>[\s\r\n]*$/', 'trailing ?>' );
		$this->checkRegex( $file, $text, '/^[\xFF\xFE\xEF]/', 'byte-order mark' );
	}

	private function checkRegex( $file, $text, $regex, $desc ) {
		if ( !preg_match( $regex, $text ) ) {
			return;
		}

		if ( !isset( $this->mWarnings[$file] ) ) {
			$this->mWarnings[$file] = array();
		}
		$this->mWarnings[$file][] = $desc;
		$this->output( "Warning in file $file: $desc found.\n" );
	}
}

$maintClass = "SyntaxChecker";
require_once( DO_MAINTENANCE );

