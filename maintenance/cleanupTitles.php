<?php
/**
 * Clean up broken, unparseable titles.
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * @author Brion Vibber <brion at pobox.com>
 * @ingroup Maintenance
 */

require_once __DIR__ . '/cleanupTable.inc';

/**
 * Maintenance script to clean up broken, unparseable titles.
 *
 * @ingroup Maintenance
 */
class TitleCleanup extends TableCleanup {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to clean up broken, unparseable titles' );
	}

	/**
	 * @param object $row
	 */
	protected function processRow( $row ) {
		global $wgContLang;
		$display = Title::makeName( $row->page_namespace, $row->page_title );
		$verified = $wgContLang->normalize( $display );
		$title = Title::newFromText( $verified );

		if ( !is_null( $title )
			&& $title->canExist()
			&& $title->getNamespace() == $row->page_namespace
			&& $title->getDBkey() === $row->page_title
		) {
			$this->progress( 0 ); // all is fine

			return;
		}

		if ( $row->page_namespace == NS_FILE && $this->fileExists( $row->page_title ) ) {
			$this->output( "file $row->page_title needs cleanup, please run cleanupImages.php.\n" );
			$this->progress( 0 );
		} elseif ( is_null( $title ) ) {
			$this->output( "page $row->page_id ($display) is illegal.\n" );
			$this->moveIllegalPage( $row );
			$this->progress( 1 );
		} else {
			$this->output( "page $row->page_id ($display) doesn't match self.\n" );
			$this->moveInconsistentPage( $row, $title );
			$this->progress( 1 );
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	protected function fileExists( $name ) {
		// XXX: Doesn't actually check for file existence, just presence of image record.
		// This is reasonable, since cleanupImages.php only iterates over the image table.
		$dbr = $this->getDB( DB_REPLICA );
		$row = $dbr->selectRow( 'image', [ 'img_name' ], [ 'img_name' => $name ], __METHOD__ );

		return $row !== false;
	}

	/**
	 * @param object $row
	 */
	protected function moveIllegalPage( $row ) {
		$legal = 'A-Za-z0-9_/\\\\-';
		$legalized = preg_replace_callback( "!([^$legal])!",
			[ $this, 'hexChar' ],
			$row->page_title );
		if ( $legalized == '.' ) {
			$legalized = '(dot)';
		}
		if ( $legalized == '_' ) {
			$legalized = '(space)';
		}
		$legalized = 'Broken/' . $legalized;

		$title = Title::newFromText( $legalized );
		if ( is_null( $title ) ) {
			$clean = 'Broken/id:' . $row->page_id;
			$this->output( "Couldn't legalize; form '$legalized' still invalid; using '$clean'\n" );
			$title = Title::newFromText( $clean );
		} elseif ( $title->exists() ) {
			$clean = 'Broken/id:' . $row->page_id;
			$this->output( "Legalized for '$legalized' exists; using '$clean'\n" );
			$title = Title::newFromText( $clean );
		}

		$dest = $title->getDBkey();
		if ( $this->dryrun ) {
			$this->output( "DRY RUN: would rename $row->page_id ($row->page_namespace," .
				"'$row->page_title') to ($row->page_namespace,'$dest')\n" );
		} else {
			$this->output( "renaming $row->page_id ($row->page_namespace," .
				"'$row->page_title') to ($row->page_namespace,'$dest')\n" );
			$dbw = $this->getDB( DB_MASTER );
			$dbw->update( 'page',
				[ 'page_title' => $dest ],
				[ 'page_id' => $row->page_id ],
				__METHOD__ );
		}
	}

	/**
	 * @param object $row
	 * @param Title $title
	 */
	protected function moveInconsistentPage( $row, $title ) {
		if ( $title->exists() || $title->getInterwiki() || !$title->canExist() ) {
			if ( $title->getInterwiki() || !$title->canExist() ) {
				$prior = $title->getPrefixedDBkey();
			} else {
				$prior = $title->getDBkey();
			}

			# Old cleanupTitles could move articles there. See bug 23147.
			$ns = $row->page_namespace;
			if ( $ns < 0 ) {
				$ns = 0;
			}

			# Namespace which no longer exists. Put the page in the main namespace
			# since we don't have any idea of the old namespace name. See bug 68501.
			if ( !MWNamespace::exists( $ns ) ) {
				$ns = 0;
			}

			$clean = 'Broken/' . $prior;
			$verified = Title::makeTitleSafe( $ns, $clean );
			if ( !$verified || $verified->exists() ) {
				$blah = "Broken/id:" . $row->page_id;
				$this->output( "Couldn't legalize; form '$clean' exists; using '$blah'\n" );
				$verified = Title::makeTitleSafe( $ns, $blah );
			}
			$title = $verified;
		}
		if ( is_null( $title ) ) {
			$this->error( "Something awry; empty title.", true );
		}
		$ns = $title->getNamespace();
		$dest = $title->getDBkey();

		if ( $this->dryrun ) {
			$this->output( "DRY RUN: would rename $row->page_id ($row->page_namespace," .
				"'$row->page_title') to ($ns,'$dest')\n" );
		} else {
			$this->output( "renaming $row->page_id ($row->page_namespace," .
				"'$row->page_title') to ($ns,'$dest')\n" );
			$dbw = $this->getDB( DB_MASTER );
			$dbw->update( 'page',
				[
					'page_namespace' => $ns,
					'page_title' => $dest
				],
				[ 'page_id' => $row->page_id ],
				__METHOD__ );
			LinkCache::singleton()->clear();
		}
	}
}

$maintClass = "TitleCleanup";
require_once RUN_MAINTENANCE_IF_MAIN;
