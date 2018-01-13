<?php
/**
 * Clean up broken page links when somebody turns on $wgCapitalLinks.
 *
 * Usage: php cleanupCaps.php [--dry-run]
 * Options:
 *   --dry-run  don't actually try moving them
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
 * Maintenance script to clean up broken page links when somebody turns
 * on or off $wgCapitalLinks.
 *
 * @ingroup Maintenance
 */
class CapsCleanup extends TableCleanup {

	private $user;
	private $namespace;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to cleanup capitalization' );
		$this->addOption( 'namespace', 'Namespace number to run caps cleanup on', false, true );
	}

	public function execute() {
		$this->user = User::newSystemUser( 'Conversion script', [ 'steal' => true ] );

		$this->namespace = intval( $this->getOption( 'namespace', 0 ) );

		if ( MWNamespace::isCapitalized( $this->namespace ) ) {
			$this->output( "Will be moving pages to first letter capitalized titles" );
			$callback = 'processRowToUppercase';
		} else {
			$this->output( "Will be moving pages to first letter lowercase titles" );
			$callback = 'processRowToLowercase';
		}

		$this->dryrun = $this->hasOption( 'dry-run' );

		$this->runTable( [
			'table' => 'page',
			'conds' => [ 'page_namespace' => $this->namespace ],
			'index' => 'page_id',
			'callback' => $callback ] );
	}

	protected function processRowToUppercase( $row ) {
		global $wgContLang;

		$current = Title::makeTitle( $row->page_namespace, $row->page_title );
		$display = $current->getPrefixedText();
		$lower = $row->page_title;
		$upper = $wgContLang->ucfirst( $row->page_title );
		if ( $upper == $lower ) {
			$this->output( "\"$display\" already uppercase.\n" );

			return $this->progress( 0 );
		}

		$target = Title::makeTitle( $row->page_namespace, $upper );
		if ( $target->exists() ) {
			// Prefix "CapsCleanup" to bypass the conflict
			$target = Title::newFromText( __CLASS__ . '/' . $display );
		}
		$ok = $this->movePage(
			$current,
			$target,
			'Converting page title to first-letter uppercase',
			false
		);
		if ( $ok ) {
			$this->progress( 1 );
			if ( $row->page_namespace == $this->namespace ) {
				$talk = $target->getTalkPage();
				$row->page_namespace = $talk->getNamespace();
				if ( $talk->exists() ) {
					return $this->processRowToUppercase( $row );
				}
			}
		}

		return $this->progress( 0 );
	}

	protected function processRowToLowercase( $row ) {
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
		if ( $target->exists() ) {
			$targetDisplay = $target->getPrefixedText();
			$this->output( "\"$display\" skipped; \"$targetDisplay\" already exists\n" );

			return $this->progress( 0 );
		}

		$ok = $this->movePage( $current, $target, 'Converting page titles to lowercase', true );
		if ( $ok === true ) {
			$this->progress( 1 );
			if ( $row->page_namespace == $this->namespace ) {
				$talk = $target->getTalkPage();
				$row->page_namespace = $talk->getNamespace();
				if ( $talk->exists() ) {
					return $this->processRowToLowercase( $row );
				}
			}
		}

		return $this->progress( 0 );
	}

	/**
	 * @param Title $current
	 * @param Title $target
	 * @param string $reason
	 * @param bool $createRedirect
	 * @return bool Success
	 */
	private function movePage( Title $current, Title $target, $reason, $createRedirect ) {
		$display = $current->getPrefixedText();
		$targetDisplay = $target->getPrefixedText();

		if ( $this->dryrun ) {
			$this->output( "\"$display\" -> \"$targetDisplay\": DRY RUN, NOT MOVED\n" );
			$ok = 'OK';
		} else {
			$mp = new MovePage( $current, $target );
			$status = $mp->move( $this->user, $reason, $createRedirect );
			$ok = $status->isOK() ? 'OK' : $status->getWikiText( false, false, 'en' );
			$this->output( "\"$display\" -> \"$targetDisplay\": $ok\n" );
		}

		return $ok === 'OK';
	}
}

$maintClass = CapsCleanup::class;
require_once RUN_MAINTENANCE_IF_MAIN;
