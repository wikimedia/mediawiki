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
	private $mFiles = array();

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Check syntax for all PHP files in MediaWiki";
		$this->addOption( 'with-extensions', 'Also recurse the extensions folder' );
	}

	public function execute() {
		$this->output( "Building file list..." );
		$this->buildFileList();
		$this->output( "done.\n" );

		$this->output( "Checking syntax (this can take a really long time)...\n\n" );
		$res = $this->checkSyntax();
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
	 * Check the files for syntax errors
	 */
	private function checkSyntax() {
		$count = $bad = 0;
		foreach( $this->mFiles as $f ) {
			$count++;
			$res = exec( 'php -l ' . $f ); 
			if( strpos( $res, 'No syntax errors detected' ) === false ) {
				$bad++;
				$this->error( $res . "\n" );
			}
		}
		$this->output( "$count files checked, $bad failures\n" );
	}
}

$maintClass = "SyntaxChecker";
require_once( DO_MAINTENANCE );
