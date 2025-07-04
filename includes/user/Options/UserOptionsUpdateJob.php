<?php
/**
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
 */

use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\User;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Job that updates a user's preferences.
 *
 * The following job parameters are required:
 *   - userId: the user ID
 *   - options: a map of (option => value)
 *
 * @since 1.33
 * @ingroup User
 */
class UserOptionsUpdateJob extends Job implements GenericParameterJob {
	public function __construct( array $params ) {
		parent::__construct( 'userOptionsUpdate', $params );
		$this->removeDuplicates = true;
	}

	/** @inheritDoc */
	public function run() {
		if ( !$this->params['options'] ) {
			return true; // nothing to do
		}

		$user = User::newFromId( $this->params['userId'] );
		$user->load( IDBAccessObject::READ_EXCLUSIVE );
		if ( !$user->isNamed() ) {
			return true;
		}

		$userOptionsManager = MediaWikiServices::getInstance()
			->getUserOptionsManager();
		foreach ( $this->params['options'] as $name => $value ) {
			$userOptionsManager->setOption( $user, $name, $value );
		}

		$user->saveSettings();

		return true;
	}
}
