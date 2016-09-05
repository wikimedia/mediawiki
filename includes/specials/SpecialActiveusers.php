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
 * Implements Special:Activeusers
 *
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
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();

		$opts = new FormOptions();

		$opts->add( 'username', '' );
		$opts->add( 'hidebots', false, FormOptions::BOOL );
		$opts->add( 'hidesysops', false, FormOptions::BOOL );

		$opts->fetchValuesFromRequest( $this->getRequest() );

		if ( $par !== null ) {
			$opts->setValue( 'username', $par );
		}

		// Mention the level of cache staleness...
		$cacheText = '';
		$dbr = wfGetDB( DB_REPLICA, 'recentchanges' );
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
				$cacheTxt = '<br>' . $this->msg( 'cachedspecial-viewing-cached-ttl' )
					->durationParams( $secondsOld );
			}
		}

		$pager = new ActiveUsersPager( $this->getContext(), $opts );
		$usersBody = $pager->getBody();

		$days = $this->getConfig()->get( 'ActiveUserDays' );

		$formDescriptor = [
			'username' => [
				'type' => 'user',
				'name' => 'username',
				'label-message' => 'activeusers-from',
			],

			'hidebots' => [
				'type' => 'check',
				'name' => 'hidebots',
				'label-message' => 'activeusers-hidebots',
				'default' => false,
			],

			'hidesysops' => [
				'type' => 'check',
				'name' => 'hidesysops',
				'label-message' => 'activeusers-hidesysops',
				'default' => false,
			],
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setIntro( $this->msg( 'activeusers-intro' )->numParams( $days ) . $cacheText )
			->setWrapperLegendMsg( 'activeusers' )
			->setSubmitTextMsg( 'activeusers-submit' )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );

		if ( $usersBody ) {
			$out->addHTML(
				$pager->getNavigationBar() .
				Html::rawElement( 'ul', [], $usersBody ) .
				$pager->getNavigationBar()
			);
		} else {
			$out->addWikiMsg( 'activeusers-noresult' );
		}
	}

	protected function getGroupName() {
		return 'users';
	}
}
