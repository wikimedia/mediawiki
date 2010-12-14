<?php
/**
 * Send purge requests for listed pages to squid
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class PurgeList extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Send purge requests for listed pages to squid";
		$this->addOption( 'purge', 'Whether to update page_touched.' , false, false );
	}

	public function execute() {
		$stdin = $this->getStdin();
		$urls = array();

		while ( !feof( $stdin ) ) {
			$page = trim( fgets( $stdin ) );
			if ( substr( $page, 0, 7 ) == 'http://' ) {
				$urls[] = $page;
			} elseif ( $page !== '' ) {
				$title = Title::newFromText( $page );
				if ( $title ) {
					$url = $title->getFullUrl();
					$this->output( "$url\n" );
					$urls[] = $url;
					if ( $this->getOptions( 'purge' ) ) {
						$title->invalidateCache();
					}
				} else {
					$this->output( "(Invalid title '$page')\n" );
				}
			}
		}

		$this->output( "Purging " . count( $urls ) . " urls...\n" );
		$u = new SquidUpdate( $urls );
		$u->doUpdate();

		$this->output( "Done!\n" );
	}
}

$maintClass = "PurgeList";
require_once( DO_MAINTENANCE );
