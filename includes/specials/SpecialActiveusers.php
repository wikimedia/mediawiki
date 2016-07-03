<?php
/**
 * Implements Special:Activeusers
 *
 * Copyright © 2008 Aaron Schulz
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
 * @ingroup SpecialPage
 */

/**
 * @ingroup SpecialPage
 */
class SpecialActiveUsers extends SpecialPage {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Activeusers' );
	}

	/**
	 * Show the special page
	 *
	 * @param string $par Parameter passed to the page or null
	 */
	public function execute( $par ) {
		$days = $this->getConfig()->get( 'ActiveUserDays' );

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->wrapWikiMsg( "<div class='mw-activeusers-intro'>\n$1\n</div>",
			[ 'activeusers-intro', $this->getLanguage()->formatNum( $days ) ] );

		// Mention the level of cache staleness...
		$dbr = wfGetDB( DB_SLAVE, 'recentchanges' );
		$rcMax = $dbr->selectField( 'recentchanges', 'MAX(rc_timestamp)', '', __METHOD__ );
		if ( $rcMax ) {
			$cTime = $dbr->selectField( 'querycache_info',
				'qci_timestamp',
				[ 'qci_type' => 'activeusers' ],
				__METHOD__
			);
			if ( $cTime ) {
				$secondsOld = wfTimestamp( TS_UNIX, $rcMax ) - wfTimestamp( TS_UNIX, $cTime );
			} else {
				$rcMin = $dbr->selectField( 'recentchanges', 'MIN(rc_timestamp)' );
				$secondsOld = time() - wfTimestamp( TS_UNIX, $rcMin );
			}
			if ( $secondsOld > 0 ) {
				$out->addWikiMsg( 'cachedspecial-viewing-cached-ttl',
				$this->getLanguage()->formatDuration( $secondsOld ) );
			}
		}

		$up = new ActiveUsersPager( $this->getContext(), null, $par );

		# getBody() first to check, if empty
		$usersbody = $up->getBody();

		$out->addHTML( $up->getPageHeader() );
		if ( $usersbody ) {
			$out->addHTML(
				$up->getNavigationBar() .
				Html::rawElement( 'ul', [], $usersbody ) .
				$up->getNavigationBar()
			);
		} else {
			$out->addWikiMsg( 'activeusers-noresult' );
		}
	}

	protected function getGroupName() {
		return 'users';
	}
}
