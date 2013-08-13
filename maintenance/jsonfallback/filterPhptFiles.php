<?php
/**
 * Maintenance script to remove SKIPIF sections from a set of .phpt test cases.
 *
 * Copyright © 2013 Kevin Israel
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @file
 * @ingroup JsonFallback
 */

require_once __DIR__ . '/../Maintenance.php';

/**
 * Maintenance script to remove SKIPIF sections from a set of .phpt test cases, such as the
 * tests for the native JSON extension. This allows using the run-tests.php script from
 * the PHP source code distribution to test alternative implementations of such functions.
 *
 * Also outputs a list of test filenames to feed to run-tests.php using the -r option.
 *
 * @ingroup JsonFallback
 */
class JsonFallbackFilterPhptFiles extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Removes SKIPIF sections from a set of .phpt test cases';
		$this->addArg( 'src-dir', 'Source directory' );
		$this->addArg( 'dest-dir', 'Destination directory (default: current directory)', false );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		$srcDir = $this->getArg( 0 );
		$destDir = $this->getArg( 1, '.' );

		if ( !is_dir( $srcDir ) ) {
			if ( file_exists( $srcDir ) ) {
				$this->error( "Source $srcDir is not a directory", 1 );
			} else {
				$this->error( "Source directory $srcDir does not exist", 1 );
			}
		}

		if ( !file_exists( $destDir ) ) {
			mkdir( $destDir, 0777, true );
		}

		if ( !is_dir( $destDir ) ) {
			$this->error( "Destination $destDir is not a directory", 1 );
		}

		foreach ( new DirectoryIterator( $srcDir ) as $fi ) {
			if ( !$fi->isFile() ) {
				continue;
			}

			$name = $fi->getFilename();
			if ( !preg_match( '/\.phpt$/i', $name ) ) {
				continue;
			}

			$destName = "$destDir/$name";
			$text = file_get_contents( $fi->getPathname() );
			$text = preg_replace( '/^--SKIPIF--.*?^--/ms', '--', $text );
			file_put_contents( $destName, $text );
			$this->output( realpath( $destName ) . "\n" );
		}
	}

}

$maintClass = 'JsonFallbackFilterPhptFiles';
require_once RUN_MAINTENANCE_IF_MAIN;
