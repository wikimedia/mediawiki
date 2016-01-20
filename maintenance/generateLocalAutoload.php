<?php

/**
 * Creates a local autoload.php in the root of the MediaWiki installation dir,
 * which maps class names to files which is used for the PHP class autoloader.
 *
 * Usage:
 *    php generateLocalAutoload.php
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
 * Maintenance script to generate the local autoloader file.
 *
 * @ingroup Maintenance
 */
class GenerateLocalAutoload extends Maintenance {
	/**
	 * Holds the AutoloadGenerator object, if created.
	 * @var AutoloadGenerator|null
	 */
	private $generator = null;

	public function __construct( AutoloadGenerator $generator = null ) {
		parent::__construct();

		$this->generator = $generator;
	}

	public function execute() {
		// Mediawiki installation directory
		$base = dirname( __DIR__ );
		$generator = new AutoloadGenerator( $base, 'local' );
		$this->generator = $generator;

		// Write out the autoload
		$fileinfo = $generator->getTargetFileinfo();
		file_put_contents( $fileinfo['filename'], $this->getAutoloaderContent( $base ) );
	}

	/**
	 * Creates a new AutoloadGenerator class, set it up and runs the getAutoload
	 * function to generate the output of the autoloader file.
	 */
	public function getAutoloaderContent( $base ) {
		foreach ( array( 'includes', 'languages', 'maintenance', 'mw-config' ) as $dir ) {
			$this->generator->readDir( $base . '/' . $dir );
		}
		foreach ( glob( $base . '/*.php' ) as $file ) {
			$this->generator->readFile( $file );
		}

		// This class is not defined, but might be added by the installer
		$this->generator->forceClassPath( 'MyLocalSettingsGenerator', "$base/mw-config/overrides.php" );

		// get and return the new autoloader content
		return $this->generator->getAutoload( 'maintenance/generateLocalAutoload.php' );
	}
}

$maintClass = "GenerateLocalAutoload";
require_once RUN_MAINTENANCE_IF_MAIN;
