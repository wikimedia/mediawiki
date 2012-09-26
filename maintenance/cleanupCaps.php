<?php
/**
 * Clean up broken page links when somebody turns on $wgCapitalLinks.
 *
 * Usage: php cleanupCaps.php [--dry-run]
 * Options:
 *   --dry-run  don't actually try moving them
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
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
 * @file
 * @author Brion Vibber <brion at pobox.com>
 * @ingroup Maintenance
 */

require_once __DIR__ . '/cleanupTable.inc';

/**
 * Maintenance script to clean up broken page links when somebody turns on $wgCapitalLinks.
 *
 * @ingroup Maintenance
 */
class CapsCleanup extends TableCleanup {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Script to cleanup capitalization";
		$this->addOption( 'namespace', 'Namespace number to run caps cleanup on', false, true );
	}

	public function execute() {
		global $wgCapitalLinks, $wgUser;

		if ( $wgCapitalLinks ) {
			$this->error( "\$wgCapitalLinks is on -- no need for caps links cleanup.", true );
		}

		$wgUser = User::newFromName( 'Conversion script' );

		$this->namespace = intval( $this->getOption( 'namespace', 0 ) );
		$this->dryrun = $this->hasOption( 'dry-run' );

		$this->runTable( array(
			'table' => 'page',
			'conds' => array( 'page_namespace' => $this->namespace ),
			'index' => 'page_id',
			'callback' => 'processRow' ) );
	}

	protected function processRow( $row ) {
		global $wgContLang;

		$current = Title::makeTitle( $row->page_namespace, $row->page_title );
		$display = $current->getPrefixedText();
		$upper = $row->page_title;
		$lower = $wgContLang->lcfirst( $row->page_title );
		if ( $upper == $lower ) {
			$this->output( "\"$display\" already lowercase.\n" );
			return $this->progress( 0 );
		}

		$target = Title::makeTitle( $row->page_namespace, $lower );
		$targetDisplay = $target->getPrefixedText();
		if ( $target->exists() ) {
			$this->output( "\"$display\" skipped; \"$targetDisplay\" already exists\n" );
			return $this->progress( 0 );
		}

		if ( $this->dryrun ) {
			$this->output( "\"$display\" -> \"$targetDisplay\": DRY RUN, NOT MOVED\n" );
			$ok = true;
		} else {
			$ok = $current->moveTo( $target, false, 'Converting page titles to lowercase' );
			$this->output( "\"$display\" -> \"$targetDisplay\": $ok\n" );
		}
		if ( $ok === true ) {
			$this->progress( 1 );
			if ( $row->page_namespace == $this->namespace ) {
				$talk = $target->getTalkPage();
				$row->page_namespace = $talk->getNamespace();
				if ( $talk->exists() ) {
					return $this->processRow( $row );
				}
			}
		}
		return $this->progress( 0 );
	}
}

$maintClass = "CapsCleanup";
require_once RUN_MAINTENANCE_IF_MAIN;
