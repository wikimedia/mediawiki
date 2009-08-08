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
	private $mFiles, $mFailures = array();

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Check syntax for all PHP files in MediaWiki";
		$this->addOption( 'with-extensions', 'Also recurse the extensions folder' );
	}

	public function execute() {
		if( !function_exists( 'parsekit_compile_file' ) ) {
			$this->error( 'Requires PHP with parsekit', true );
		}

		$this->output( "Building file list..." );
		$this->buildFileList();
		$this->output( "done.\n" );

		$this->output( "Checking syntax (this can take a really long time)...\n\n" );
		foreach( $this->mFiles as $f ) {
			$this->checkFile( $f );
		}
		$this->output( "\nDone! " . count( $this->mFiles ) . " files checked, " .
			count( $this->mFailures ) . " failures found" );
	}

	/**
	 * Build the list of files we'll check for syntax errors
	 */
	private function buildFileList() {
		global $IP;

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
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $d ), 
				RecursiveIteratorIterator::SELF_FIRST
			);
			foreach ( $iterator as $file ) {
				$ext = pathinfo( $file->getFilename(), PATHINFO_EXTENSION );
				if ( $ext == 'php' || $ext == 'inc' || $ext == 'php5' ) {
					$this->mFiles[] = $file->getRealPath();
				}
			}
		}
	}

	/**
	 * Check a file for syntax errors. Shamelessly stolen
	 * from tools/lint.php by TimStarling
	 *
	 * @param $file String Path to a file to check for syntax errors
	 */
	private function checkFile( $file ) {
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
			}
			$this->mFailures[ $file ] = $errors;
		}
		return $ret;
	}
}

$maintClass = "SyntaxChecker";
require_once( DO_MAINTENANCE );
