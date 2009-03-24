<?php
/*
 * Script to clean up broken page links when somebody turns on $wgCapitalLinks.
 *
 * Usage: php cleanupCaps.php [--dry-run]
 * Options:
 *   --dry-run  don't actually try moving them
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
 * @file
 * @author Brion Vibber <brion at pobox.com>
 * @ingroup maintenance
 */

$optionsWithArgs = array( 'namespace' );

require_once( 'commandLine.inc' );
require_once( 'cleanupTable.inc' );

/**
 * @ingroup Maintenance
 */
class CapsCleanup extends TableCleanup {
	function __construct( $dryrun = false, $namespace = 0 ) {
		parent::__construct( 'page', $dryrun );
		$this->namespace = intval( $namespace );
	}

	function cleanup() {
		global $wgCapitalLinks;
		if( $wgCapitalLinks ) {
			echo "\$wgCapitalLinks is on -- no need for caps links cleanup.\n";
			return false;
		}

		$this->runTable( $this->targetTable,
			'WHERE page_namespace=' . $this->namespace,
			array( &$this, 'processPage' ) );
	}

	function processPage( $row ) {
		global $wgContLang;

		$current = Title::makeTitle( $row->page_namespace, $row->page_title );
		$display = $current->getPrefixedText();
		$upper = $row->page_title;
		$lower = $wgContLang->lcfirst( $row->page_title );
		if( $upper == $lower ) {
			$this->log( "\"$display\" already lowercase." );
			return $this->progress( 0 );
		}

		$target = Title::makeTitle( $row->page_namespace, $lower );
		$targetDisplay = $target->getPrefixedText();
		if( $target->exists() ) {
			$this->log( "\"$display\" skipped; \"$targetDisplay\" already exists" );
			return $this->progress( 0 );
		}

		if( $this->dryrun ) {
			$this->log( "\"$display\" -> \"$targetDisplay\": DRY RUN, NOT MOVED" );
			$ok = true;
		} else {
			$ok = $current->moveTo( $target, false, 'Converting page titles to lowercase' );
			$this->log( "\"$display\" -> \"$targetDisplay\": $ok" );
		}
		if( $ok === true ) {
			$this->progress( 1 );

			if( $row->page_namespace == $this->namespace ) {
				$talk = $target->getTalkPage();
				$row->page_namespace = $talk->getNamespace();
				if( $talk->exists() ) {
					return $this->processPage( $row );
				}
			}
		} else {
			$this->progress( 0 );
		}
	}

}

$wgUser->setName( 'Conversion script' );
$ns = isset( $options['namespace'] ) ? $options['namespace'] : 0;
$caps = new CapsCleanup( isset( $options['dry-run'] ), $ns );
$caps->cleanup();
