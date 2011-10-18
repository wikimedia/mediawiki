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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class CheckSyntax extends Maintenance {

	// List of files we're going to check
	private $mFiles = array(), $mFailures = array(), $mWarnings = array();
	private $mIgnorePaths = array(), $mNoStyleCheckPaths = array();

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Check syntax for all PHP files in MediaWiki";
		$this->addOption( 'with-extensions', 'Also recurse the extensions folder' );
		$this->addOption( 'path', 'Specific path (file or directory) to check, either with absolute path or relative to the root of this MediaWiki installation',
			false, true );
		$this->addOption( 'list-file', 'Text file containing list of files or directories to check', false, true );
		$this->addOption( 'modified', 'Check only files that were modified (requires SVN command-line client)' );
		$this->addOption( 'syntax-only', 'Check for syntax validity only, skip code style warnings' );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		$this->buildFileList();

		// ParseKit is broken on PHP 5.3+, disabled until this is fixed
		$useParseKit = function_exists( 'parsekit_compile_file' ) && version_compare( PHP_VERSION, '5.3', '<' );

		$str = 'Checking syntax (using ' . ( $useParseKit ?
			'parsekit' : ' php -l, this can take a long time' ) . ")\n";
		$this->output( $str );
		foreach ( $this->mFiles as $f ) {
			if ( $useParseKit ) {
				$this->checkFileWithParsekit( $f );
			} else {
				$this->checkFileWithCli( $f );
			}
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

		$this->mIgnorePaths = array(
			// Compat stuff, explodes on PHP 5.3
			"includes/NamespaceCompat.php$",
			);

		$this->mNoStyleCheckPaths = array(
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
			);

		if ( $this->hasOption( 'path' ) ) {
			$path = $this->getOption( 'path' );
			if ( !$this->addPath( $path ) ) {
				$this->error( "Error: can't find file or directory $path\n", true );
			}
			return; // process only this path
		} elseif ( $this->hasOption( 'list-file' ) ) {
			$file = $this->getOption( 'list-file' );
			wfSuppressWarnings();
			$f = fopen( $file, 'r' );
			wfRestoreWarnings();
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
			$this->output( "Retrieving list from Subversion... " );
			$parentDir = wfEscapeShellArg( dirname( __FILE__ ) . '/..' );
			$retval = null;
			$output = wfShellExec( "svn status --ignore-externals $parentDir", $retval );
			if ( $retval ) {
				$this->error( "Error retrieving list from Subversion!\n", true );
			} else {
				$this->output( "done\n" );
			}

			preg_match_all( '/^\s*[AM].{7}(.*?)\r?$/m', $output, $matches );
			foreach ( $matches[1] as $file ) {
				if ( $this->isSuitableFile( $file ) && !is_dir( $file ) ) {
					$this->mFiles[] = $file;
				}
			}
			return;
		}

		$this->output( 'Building file list...', 'listfiles' );

		// Only check files in these directories.
		// Don't just put $IP, because the recursive dir thingie goes into all subdirs
		$dirs = array(
			$IP . '/includes',
			$IP . '/mw-config',
			$IP . '/languages',
			$IP . '/maintenance',
			$IP . '/skins',
		);
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
		if ( file_exists( "$IP/AdminSettings.php" ) ) {
			$this->mFiles[] = "$IP/AdminSettings.php";
		}

		$this->output( 'done.', 'listfiles' );
	}

	/**
	 * Returns true if $file is of a type we can check
	 * @param $file string
	 * @return bool
	 */
	private function isSuitableFile( $file ) {
		$file = str_replace( '\\', '/', $file );
		$ext = pathinfo( $file, PATHINFO_EXTENSION );
		if ( $ext != 'php' && $ext != 'inc' && $ext != 'php5' )
			return false;
		foreach ( $this->mIgnorePaths as $regex ) {
			$m = array();
			if ( preg_match( "~{$regex}~", $file, $m ) )
				return false;
		}
		return true;
	}

	/**
	 * Add given path to file list, searching it in include path if needed
	 * @param $path string
	 * @return bool
	 */
	private function addPath( $path ) {
		global $IP;
		return $this->addFileOrDir( $path ) || $this->addFileOrDir( "$IP/$path" );
	}

	/**
	 * Add given file to file list, or, if it's a directory, add its content
	 * @param $path string
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
	 * @param $dir String: directory to process
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
	 * @param $file String String Path to a file to check for errors
	 * @return boolean
	 */
	private function checkForMistakes( $file ) {
		foreach ( $this->mNoStyleCheckPaths as $regex ) {
			$m = array();
			if ( preg_match( "~{$regex}~", $file, $m ) )
				return;
		}

		$text = file_get_contents( $file );
		$tokens = token_get_all( $text );

		$this->checkEvilToken( $file, $tokens, '@', 'Error supression operator (@)');
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

	private function checkEvilToken( $file, $tokens, $evilToken, $desc ) {
		if ( !in_array( $evilToken, $tokens ) ) {
			return;
		}

		if ( !isset( $this->mWarnings[$file] ) ) {
			$this->mWarnings[$file] = array();
		}
		$this->mWarnings[$file][] = $desc;
		$this->output( "Warning in file $file: $desc found.\n" );
	}
}

$maintClass = "CheckSyntax";
require_once( RUN_MAINTENANCE_IF_MAIN );

