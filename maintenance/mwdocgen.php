<?php
/**
 * Generate class and file reference documentation for MediaWiki using doxygen.
 *
 * If the dot DOT language processor is available, attempt call graph
 * generation.
 *
 * Usage:
 *   php mwdocgen.php
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
 * @todo document
 * @ingroup Maintenance
 *
 * @author Antoine Musso <hashar at free dot fr>
 * @author Brion Vibber
 * @author Alexandre Emsenhuber
 * @version first release
 */

use MediaWiki\Shell\Shell;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that builds doxygen documentation.
 * @ingroup Maintenance
 */
class MWDocGen extends Maintenance {
	/** @var string */
	private $doxygen;
	/** @var string */
	private $mwVersion;
	/** @var string */
	private $output;
	/** @var string */
	private $input;
	/** @var string */
	private $inputFilter;
	/** @var string */
	private $template;
	/** @var string[] */
	private $excludes;
	/** @var bool */
	private $doDot;

	/**
	 * Prepare Maintenance class
	 */
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Build doxygen documentation' );

		$this->addOption( 'doxygen',
			'Path to doxygen',
			false, true );
		$this->addOption( 'version',
			'Pass a MediaWiki version',
			false, true );
		$this->addOption( 'file',
			"Only process given file or directory. Multiple values " .
			"accepted with comma separation. Path relative to MW_INSTALL_PATH.",
			false, true );
		$this->addOption( 'output',
			'Path to write doc to',
			false, true );
		$this->addOption( 'extensions',
			'Process the extensions/ directory as well (ignored if --file is used)' );
		$this->addOption( 'skins',
			'Process the skins/ directory as well (ignored if --file is used)' );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	protected function init() {
		global $wgPhpCli;

		$this->doxygen = $this->getOption( 'doxygen', 'doxygen' );
		$this->mwVersion = $this->getOption( 'version', 'master' );

		$this->output = $this->getOption( 'output', 'docs' );

		// Do not use wfShellWikiCmd, because mwdoc-filter.php is not
		// a Maintenance script.
		$this->inputFilter = Shell::escape( [
			$wgPhpCli,
			MW_INSTALL_PATH . '/maintenance/mwdoc-filter.php'
		] );

		$this->template = MW_INSTALL_PATH . '/maintenance/Doxyfile';
		$this->excludes = [
			'images',
			'node_modules',
			'resources',
			'static',
			'tests',
			'vendor',
		];

		$file = $this->getOption( 'file' );
		if ( $file !== null ) {
			$this->input = '';
			foreach ( explode( ',', $file ) as $input ) {
				// Doxygen inputs are space separated and double quoted
				$this->input .= " \"$input\"";
			}
		} else {
			// If no explicit --file filter is set, we're indexing all of MediaWiki core
			// in MW_INSTALL_PATH, but not extension and skin submodules (T317451).
			$this->input = '';
			if ( !$this->hasOption( 'extensions' ) ) {
				$this->excludes[] = 'extensions';
			}
			if ( !$this->hasOption( 'skins' ) ) {
				$this->excludes[] = 'skins';
			}
		}

		$this->doDot = (bool)shell_exec( 'which dot' );
	}

	public function execute() {
		$this->init();

		# Build out directories we want to exclude
		$exclude = '';
		foreach ( $this->excludes as $item ) {
			$exclude .= " $item";
		}

		$conf = strtr( file_get_contents( $this->template ),
			[
				'{{OUTPUT_DIRECTORY}}' => $this->output,
				'{{CURRENT_VERSION}}' => $this->mwVersion,
				'{{INPUT}}' => $this->input,
				'{{EXCLUDE}}' => $exclude,
				'{{HAVE_DOT}}' => $this->doDot ? 'YES' : 'NO',
				'{{INPUT_FILTER}}' => $this->inputFilter,
			]
		);

		$tmpFile = tempnam( wfTempDir(), 'MWDocGen-' );
		if ( file_put_contents( $tmpFile, $conf ) === false ) {
			$this->fatalError( "Could not write doxygen configuration to file $tmpFile\n" );
		}

		$command = $this->doxygen . ' ' . $tmpFile;
		$this->output( "Executing command:\n$command\n" );

		$exitcode = 1;
		system( $command, $exitcode );

		$this->output( <<<TEXT
---------------------------------------------------
Doxygen execution finished.
Check above for possible errors.

You might want to delete the temporary file:
 $tmpFile
---------------------------------------------------

TEXT
		);

		if ( $exitcode !== 0 ) {
			$this->fatalError( "Something went wrong (exit: $exitcode)\n", $exitcode );
		}
	}
}

$maintClass = MWDocGen::class;
require_once RUN_MAINTENANCE_IF_MAIN;
