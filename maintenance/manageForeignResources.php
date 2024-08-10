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
 */

use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\ResourceLoader\ForeignResourceManager;

require_once __DIR__ . '/Maintenance.php';

/**
 * Manage foreign resources registered with ResourceLoader.
 *
 * This uses the MediaWiki\ResourceLoader\ForeignResourceManager class internally.
 * See also ForeignResourceStructureTest, which runs the "verify" action in CI.
 *
 * @ingroup Maintenance
 * @ingroup ResourceLoader
 * @since 1.32
 * @see https://www.mediawiki.org/wiki/Manual:ManageForeignResources.php
 */
class ManageForeignResources extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( <<<TEXT
Manage foreign resources registered with ResourceLoader.

This helps developers with downloading, verifying, and updating local copies of
upstream libraries registered as ResourceLoader modules. See
https://www.mediawiki.org/wiki/Foreign_resources

Use the "update" action to download urls specified in foreign-resources.yaml,
and unpack them to the resources directory. This will also verify them against
the integrity hashes.

Use the "verify" action to verify the files currently in the resources directory
match what "update" would replace them with. This is effectively a dry-run and
will not change any module resources on disk.

Use the "make-sri" action to compute an integrity hash for upstreams that do not
publish one themselves. Add or update the urls foreign-resources.yaml as needed,
but omit (or leave empty) the "integrity" key. Then, run the "make-sri" action
for the module and copy the integrity into the file. Then, you can use "verify"
or "update" normally.

The "make-cdx" option generates a CycloneDX SBOM file.
TEXT
		);
		$this->addArg( 'action', 'One of "update", "verify", "make-sri" or "make-cdx"', true );
		$this->addArg( 'module', 'Name of a single module (Default: all)', false );
		$this->addOption( 'extension', 'Manage foreign resources for the given extension, instead of core',
			false, true );
		$this->addOption( 'skin', 'Manage foreign resources for the given skin, instead of core',
			false, true );
		$this->addOption( 'verbose', 'Be verbose', false, false, 'v' );
	}

	/**
	 * @return bool
	 */
	public function execute() {
		global $IP;

		$component = $this->getOption( 'extension' ) ?? $this->getOption( 'skin' ) ?? '#core';
		$foreignResourcesDirs = ExtensionRegistry::getInstance()->getAttribute( 'ForeignResourcesDir' )
			+ [ '#core' => "{$IP}/resources/lib" ];
		if ( !array_key_exists( $component, $foreignResourcesDirs ) ) {
			$this->fatalError( "Unknown component: $component\n" );
		}
		$foreignResourcesFile = "{$foreignResourcesDirs[$component]}/foreign-resources.yaml";

		$frm = new ForeignResourceManager(
			$foreignResourcesFile,
			dirname( $foreignResourcesFile ),
			function ( $text ) {
				$this->output( $text );
			},
			function ( $text ) {
				$this->error( $text );
			},
			function ( $text ) {
				if ( $this->hasOption( 'verbose' ) ) {
					$this->output( $text );
				}
			}
		);

		$action = $this->getArg( 0 );
		$module = $this->getArg( 1, 'all' );

		try {
			return $frm->run( $action, $module );
		} catch ( Exception $e ) {
			$this->fatalError( "Error: {$e->getMessage()}" );
		}
	}
}

$maintClass = ManageForeignResources::class;
require_once RUN_MAINTENANCE_IF_MAIN;
