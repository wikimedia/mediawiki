<?php
/**
 * Dump a the list of files uploaded, for feeding to tar or similar.
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

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to dump a the list of files uploaded,
 * for feeding to tar or similar.
 *
 * @ingroup Maintenance
 */
class DumpUploads extends Maintenance {
	/** @var string */
	private $mBasePath;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Generates list of uploaded files which can be fed to tar or similar.
By default, outputs relative paths against the parent directory of $wgUploadDirectory.' );
		$this->addOption( 'base', 'Set base relative path instead of wiki include root', false, true );
		$this->addOption( 'local', 'List all local files, used or not. No shared files included' );
		$this->addOption( 'used', 'Skip local images that are not used' );
		$this->addOption( 'shared', 'Include images used from shared repository' );
	}

	public function execute() {
		global $IP;
		$this->mBasePath = $this->getOption( 'base', $IP );
		$shared = false;
		$sharedSupplement = false;

		if ( $this->hasOption( 'shared' ) ) {
			if ( $this->hasOption( 'used' ) ) {
				// Include shared-repo files in the used check
				$shared = true;
			} else {
				// Grab all local *plus* used shared
				$sharedSupplement = true;
			}
		}

		if ( $this->hasOption( 'local' ) ) {
			$this->fetchLocal( $shared );
		} elseif ( $this->hasOption( 'used' ) ) {
			$this->fetchUsed( $shared );
		} else {
			$this->fetchLocal( $shared );
		}

		if ( $sharedSupplement ) {
			$this->fetchUsed( true );
		}
	}

	/**
	 * Fetch a list of used images from a particular image source.
	 *
	 * @param bool $shared True to pass shared-dir settings to hash func
	 */
	private function fetchUsed( $shared ) {
		$dbr = $this->getDB( DB_REPLICA );
		$image = $dbr->tableName( 'image' );
		$imagelinks = $dbr->tableName( 'imagelinks' );

		$sql = "SELECT DISTINCT il_to, img_name
			FROM $imagelinks
			LEFT JOIN $image
			ON il_to=img_name";
		$result = $dbr->query( $sql, __METHOD__ );

		foreach ( $result as $row ) {
			$this->outputItem( $row->il_to, $shared );
		}
	}

	/**
	 * Fetch a list of all images from a particular image source.
	 *
	 * @param bool $shared True to pass shared-dir settings to hash func
	 */
	private function fetchLocal( $shared ) {
		$dbr = $this->getDB( DB_REPLICA );
		$result = $dbr->select( 'image',
			[ 'img_name' ],
			'',
			__METHOD__ );

		foreach ( $result as $row ) {
			$this->outputItem( $row->img_name, $shared );
		}
	}

	private function outputItem( $name, $shared ) {
		$file = MediaWikiServices::getInstance()->getRepoGroup()->findFile( $name );
		if ( $file && $this->filterItem( $file, $shared ) ) {
			$filename = $file->getLocalRefPath();
			$rel = wfRelativePath( $filename, $this->mBasePath );
			$this->output( "$rel\n" );
		} else {
			wfDebug( __METHOD__ . ": base file? $name" );
		}
	}

	private function filterItem( $file, $shared ) {
		return $shared || $file->isLocal();
	}
}

$maintClass = DumpUploads::class;
require_once RUN_MAINTENANCE_IF_MAIN;
