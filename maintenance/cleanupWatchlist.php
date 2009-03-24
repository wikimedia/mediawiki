<?php
/*
 * Script to remove broken, unparseable titles in the Watchlist.
 *
 * Usage: php cleanupWatchlist.php [--fix]
 * Options:
 *   --fix  Actually remove entries; without will only report.
 *
 * Copyright (C) 2005,2006 Brion Vibber <brion@pobox.com>
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

require_once( 'commandLine.inc' );
require_once( 'cleanupTable.inc' );

/**
 * @ingroup Maintenance
 */
class WatchlistCleanup extends TableCleanup {
	function __construct( $dryrun = false ) {
		parent::__construct( 'watchlist', $dryrun );
	}

	function processPage( $row ) {
		$current = Title::makeTitle( $row->wl_namespace, $row->wl_title );
		$display = $current->getPrefixedText();

		$verified = UtfNormal::cleanUp( $display );

		$title = Title::newFromText( $verified );

		if( $row->wl_user == 0 || is_null( $title ) || !$title->equals( $current ) ) {
			$this->log( "invalid watch by {$row->wl_user} for ({$row->wl_namespace}, \"{$row->wl_title}\")" );
			$this->removeWatch( $row );
			return $this->progress( 1 );
		}

		$this->progress( 0 );
	}
	
	function removeWatch( $row ) {
		if( !$this->dryrun ) {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->delete( 'watchlist', array(
				'wl_user'      => $row->wl_user,
				'wl_namespace' => $row->wl_namespace,
				'wl_title'     => $row->wl_title ),
			__METHOD__ );
			$this->log( '- removed' );
		}
	}
}

$wgUser->setName( 'Conversion script' );
$caps = new WatchlistCleanup( !isset( $options['fix'] ) );
$caps->cleanup();


