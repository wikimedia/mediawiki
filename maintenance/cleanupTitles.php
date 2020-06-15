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

use MediaWiki\MediaWikiServices;

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
		$this->setBatchSize( 1000 );
	}

	/**
	 * @param object $row
	 */
	protected function processRow( $row ) {
		$display = Title::makeName( $row->page_namespace, $row->page_title );
		$verified = MediaWikiServices::getInstance()->getContentLanguage()->normalize( $display );
		$title = Title::newFromText( $verified );

		if ( $title !== null
			&& $title->canExist()
			&& $title->getNamespace() == $row->page_namespace
			&& $title->getDBkey() === $row->page_title
		) {
			// all is fine
			$this->progress( 0 );

			return;
		}

		if ( $row->page_namespace == NS_FILE && $this->fileExists( $row->page_title ) ) {
			$this->output( "file $row->page_title needs cleanup, please run cleanupImages.php.\n" );
			$this->progress( 0 );
		} elseif ( $title === null ) {
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
		if ( $title === null ) {
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
	protected function moveInconsistentPage( $row, Title $title ) {
		if ( $title->exists( Title::READ_LATEST )
			|| $title->getInterwiki()
			|| !$title->canExist()
		) {
			$titleImpossible = $title->getInterwiki() || !$title->canExist();
			if ( $titleImpossible ) {
				$prior = $title->getPrefixedDBkey();
			} else {
				$prior = $title->getDBkey();
			}

			# Old cleanupTitles could move articles there. See T25147.
			$ns = $row->page_namespace;
			if ( $ns < 0 ) {
				$ns = 0;
			}

			# Namespace which no longer exists. Put the page in the main namespace
			# since we don't have any idea of the old namespace name. See T70501.
			if ( !MediaWikiServices::getInstance()->getNamespaceInfo()->exists( $ns ) ) {
				$ns = 0;
			}

			if ( !$titleImpossible && !$title->exists() ) {
				// Looks like the current title, after cleaning it up, is valid and available
				$clean = $prior;
			} else {
				$clean = 'Broken/' . $prior;
			}
			$verified = Title::makeTitleSafe( $ns, $clean );
			if ( !$verified || $verified->exists() ) {
				$blah = "Broken/id:" . $row->page_id;
				$this->output( "Couldn't legalize; form '$clean' exists; using '$blah'\n" );
				$verified = Title::makeTitleSafe( $ns, $blah );
			}
			$title = $verified;
		}
		if ( $title === null ) {
			$this->fatalError( "Something awry; empty title." );
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
			MediaWikiServices::getInstance()->getLinkCache()->clear();
		}
	}
}

$maintClass = TitleCleanup::class;
require_once RUN_MAINTENANCE_IF_MAIN;
