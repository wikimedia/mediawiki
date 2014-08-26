<?php

/**
 * Created on August 26, 2014
 *
 * Copyright © 2014 Petr Bena (benapetr@gmail.com)
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
 * API interface that allows user who is logged in to flag new messages as read
 *
 * This is useful for tools that can read the new messages using some api, or some
 * other way (loading the text using different session, preload the text using
 * a buffer where it's unrevealed later if user actually did read it, or closed
 * the application before), so it's useful in situations when you need to flag
 * new messages as read in a different time than that when you actually read them
 * @ingroup API
 */
class ApiClearHasMsg extends ApiBase {
	/**
	 * Sets new messages as read
	 */
	public function execute() {
		$user = $this->getUser();
		if ( $user->isAnon() ) {
			$this->dieUsage( 'Anonymous users cannot use this api', 'notloggedin' );
		}
		$user->setNewtalk(false);
		$this->getResult()->addValue( null, $this->getModuleName(), 'success' );
	}

	public function isWriteMode() {
		return true;
	}

	public function mustBePosted() {
		return false;
	}

	public function getDescription() {
		return array( 'Flags all new messages as read.',
			'This is useful for tools that are reading new messages using api or some unusual way and need to flag them as read.'
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=clearhasmsg' => 'Mark all messages for user who is currently logged in as read',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:ClearHasMsg';
	}
}
