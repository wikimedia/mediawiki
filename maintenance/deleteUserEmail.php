<?php
/**
 * Deletes a given user's associated email address
 * Usage: php deleteUserEmail.php <user>
 * where <user> can be either the username or user ID
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
 * @author Samuel Guebo <sguebo@wikimedia.org>
 * @see https://phabricator.wikimedia.org/T290099
 * @ingroup Maintenance
 */
use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 *
 * @since 1.38
 * @author Samuel Guebo
 */
class DeleteUserEmail extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Delete a user's email" );
		$this->addArg( 'user', 'Username or user ID, if starts with #', true );
	}

	public function execute() {
		$userFactory = MediaWikiServices::getInstance()->getUserFactory();
		$userName = $this->getArg( 0 );
		if ( preg_match( '/^#\d+$/', $userName ) ) {
			$user = $userFactory->newFromId( (int)substr( $userName, 1 ) );
		} else {
			$user = $userFactory->newFromName( $userName );
		}

		// Checking whether User object is valid and has an actual id
		if ( !$user || !$user->getId() || !$user->loadFromId() ) {
			$this->fatalError( "Error: user '$userName' could not be loaded" );
		}

		// Blank the email address
		$user->invalidateEmail();
		$user->saveSettings();
		$this->output( "Done!\n" );
	}
}

$maintClass = DeleteUserEmail::class;
require_once RUN_MAINTENANCE_IF_MAIN;
