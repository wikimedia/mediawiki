<?php
/**
 * Created on May 8, 2014
 *
 * Copyright © 2014 Brion Vibber <brion@pobox.com>
 *
 * Based on ApiWatch.php, copyright © 2008 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 */

/**
 * API module to allow users to bulk-unwatch all pages.
 *
 * @ingroup API
 * @since 1.24
 */
class ApiWatchlistClear extends ApiBase {
	public function execute() {
		$user = $this->getUser();
		if ( !$user->isLoggedIn() ) {
			$this->dieUsage( 'You must be logged-in to have a watchlist', 'notloggedin' );
		}

		if ( !$user->isAllowed( 'editmywatchlist' ) ) {
			$this->dieUsage( 'You don\'t have permission to edit your watchlist', 'permissiondenied' );
		}

		$params = $this->extractRequestParams();

		// We're going to do this in the database as a bulk operation
		// instead of one at a time, so it doesn't time out on largeish lists.
		$this->getDB()->delete( 'watchlist', array( 'wl_user' => $this->getUser()->getId() ), __METHOD__ );
		$this->getUser()->invalidateCache();

		$res = array( 'unwatch' => true );
		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return 'watch';
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = array(
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
		);

		return $result;
	}

	public function getParamDescription() {
		return array(
			'token' => 'A token previously acquired via prop=info',
		);
	}

	public function getDescription() {
		return 'Bulk-clear all items from the user\'s watchlist. This can be useful for large bot accounts.';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'notloggedin', 'info' => 'You must be logged-in to have a watchlist' ),
		) );
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:WatchlistClear';
	}
}
