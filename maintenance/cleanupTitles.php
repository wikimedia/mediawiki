<?php
/*
 * Script to clean up broken, unparseable titles.
 *
 * Usage: php cleanupTitles.php [--fix]
 * Options:
 *   --fix  Actually clean up titles; otherwise just checks for them
 *
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
 * @author Brion Vibber <brion at pobox.com>
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/cleanupTable.inc' );

class TitleCleanup extends TableCleanup {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Script to clean up broken, unparseable titles";
	}

	protected function processRow( $row ) {
		global $wgContLang;
		$display = Title::makeName( $row->page_namespace, $row->page_title );
		$verified = $wgContLang->normalize( $display );
		$title = Title::newFromText( $verified );

		if ( !is_null( $title )
			&& $title->canExist()
			&& $title->getNamespace() == $row->page_namespace
			&& $title->getDBkey() === $row->page_title )
		{
			return $this->progress( 0 );  // all is fine
		}

		if ( $row->page_namespace == NS_FILE && $this->fileExists( $row->page_title ) ) {
			$this->output( "file $row->page_title needs cleanup, please run cleanupImages.php.\n" );
			return $this->progress( 0 );
		} elseif ( is_null( $title ) ) {
			$this->output( "page $row->page_id ($display) is illegal.\n" );
			$this->moveIllegalPage( $row );
			return $this->progress( 1 );
		} else {
			$this->output( "page $row->page_id ($display) doesn't match self.\n" );
			$this->moveInconsistentPage( $row, $title );
			return $this->progress( 1 );
		}
	}

	protected function fileExists( $name ) {
		// XXX: Doesn't actually check for file existence, just presence of image record.
		// This is reasonable, since cleanupImages.php only iterates over the image table.
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'image', array( 'img_name' ), array( 'img_name' => $name ), __METHOD__ );
		return $row !== false;
	}

	protected function moveIllegalPage( $row ) {
		$legal = 'A-Za-z0-9_/\\\\-';
		$legalized = preg_replace_callback( "!([^$legal])!",
			array( &$this, 'hexChar' ),
			$row->page_title );
		if ( $legalized == '.' ) $legalized = '(dot)';
		if ( $legalized == '_' ) $legalized = '(space)';
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
			$this->output( "DRY RUN: would rename $row->page_id ($row->page_namespace,'$row->page_title') to ($row->page_namespace,'$dest')\n" );
		} else {
			$this->output( "renaming $row->page_id ($row->page_namespace,'$row->page_title') to ($row->page_namespace,'$dest')\n" );
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'page',
				array( 'page_title' => $dest ),
				array( 'page_id' => $row->page_id ),
				__METHOD__ );
		}
	}

	protected function moveInconsistentPage( $row, $title ) {
		if ( $title->exists() || $title->getInterwiki() || !$title->canExist() ) {
			if ( $title->getInterwiki() || !$title->canExist() ) {
				$prior = $title->getPrefixedDbKey();
			} else {
				$prior = $title->getDBkey();
			}

			# Old cleanupTitles could move articles there. See bug 23147.
			$ns = $row->page_namespace;
			if ( $ns < 0 ) $ns = 0;

			$clean = 'Broken/' . $prior;
			$verified = Title::makeTitleSafe( $ns, $clean );
			if ( $verified->exists() ) {
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
			$this->output( "DRY RUN: would rename $row->page_id ($row->page_namespace,'$row->page_title') to ($ns,'$dest')\n" );
		} else {
			$this->output( "renaming $row->page_id ($row->page_namespace,'$row->page_title') to ($ns,'$dest')\n" );
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'page',
				array(
					'page_namespace' => $ns,
					'page_title' => $dest
				),
				array( 'page_id' => $row->page_id ),
				__METHOD__ );
			$linkCache = LinkCache::singleton();
			$linkCache->clear();
		}
	}
}

$maintClass = "TitleCleanup";
require_once( DO_MAINTENANCE );
