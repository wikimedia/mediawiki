<?php
/**
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

// Prevent extensions from interfering, in case they're
// broken or something.
define( 'MW_NO_EXTENSIONS', 1 );

require_once __DIR__ . '/Maintenance.php';

class ManageExtensions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Manage downloaded and installed extensions';
		$this->addArg( 'action', 'Subcommand to execute', /* $required = */ true );
		$this->addArg( 'extension', 'Extension to act upon' );
		$this->addOption( 'no-run-updates', 'Don\'t run update.php if detected' );
	}

	protected function checkIfExists( $name ) {
		$file = $this->path( $name );
		if ( !file_exists( $file ) ) {
			$this->error( "$file does not exist", 1 );
		}
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private function path( $name ) {
		$extDir = $this->getConfig()->get( 'ExtensionDirectory' );
		return "$extDir/$name/extension.json";
	}

	public function execute() {
		$subcommand = $this->getArg( 0 );
		$extensionList = ExtensionList::getInstance();
		$runUpdate = false;
		switch ( $subcommand ) {
			case 'enable':
				$ext = $this->getArg( 1 );
				if ( !$ext ) {
					$this->error( 'No extension provided' );
				}
				$this->checkIfExists( $ext );
				$extensionList->enable( 'extensions', $ext );
				$extensionList->save();
				$this->output( "done\n" );
				// Check to see if we can run update.php
				break;
			case 'disable':
				$ext = $this->getArg( 1 );
				if ( !$ext ) {
					$this->error( 'No extension provided' );
				}
				$extensionList->disable( 'extensions', $ext );
				$extensionList->save();
				$this->output( "done\n" );
				break;
			case 'fetch':
				// Automatically download extension from
				// configured repositories...

			case 'update':
				// Check to see if there is an updated version
				// available and download/replace if applicable
			default:
				$this->error( 'Invalid subcommand provided' );
		}
	}
}

$maintClass = ManageExtensions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
