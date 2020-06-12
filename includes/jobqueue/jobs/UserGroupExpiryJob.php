<?php

use MediaWiki\MediaWikiServices;

/**
 * Job that purges expired user group memberships.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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
 * @ingroup JobQueue
 */

class UserGroupExpiryJob extends Job implements GenericParameterJob {
	public function __construct( array $params ) {
		parent::__construct( 'userGroupExpiry', $params );
		$this->removeDuplicates = true;
	}

	/**
	 * Run the job
	 * @return bool Success
	 */
	public function run() {
		MediaWikiServices::getInstance()->getUserGroupManager()->purgeExpired();
		return true;
	}
}
