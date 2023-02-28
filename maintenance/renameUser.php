<?php
/**
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
 * @author Martin Urbanec <martin.urbanec@wikimedia.cz>
 */

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\RenameUser\RenameuserSQL;

class RenameUser extends Maintenance {
	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Rename an user' );
		$this->addOption( 'oldname', 'Current username of the to-be-renamed user', true, true );
		$this->addOption( 'newname', 'New username of the to-be-renamed user', true, true );
		$this->addOption( 'performer', 'Performer of the rename action', false, true );
		$this->addOption( 'reason', 'Reason of the rename', false, true );
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$user = User::newFromName( $this->getOption( 'oldname' ) );
		if ( $user->getId() === 0 ) {
			$this->fatalError( 'The user does not exist' );
		}

		if ( User::newFromName( $this->getOption( 'newname' ) )->getId() > 0 ) {
			$this->fatalError( 'New username must be free' );
		}

		if ( $this->getOption( 'performer' ) === null ) {
			$performer = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		} else {
			$performer = User::newFromName( $this->getOption( 'performer' ) );
		}

		if ( !( $performer instanceof User ) || $performer->getId() === 0 ) {
			$this->fatalError( 'Performer does not exist.' );
		}

		'@phan-var User $performer';
		$renameJob = new RenameuserSQL(
			$user->getName(),
			$this->getOption( 'newname' ),
			$user->getId(),
			$performer,
			[
				'reason' => $this->getOption( 'reason' )
			]
		);

		if ( !$renameJob->rename() ) {
			$this->fatalError( 'Renaming failed.' );
		} else {
			$oldname = $this->getOption( 'oldname' );
			$newname = $this->getOption( 'newname' );
			$this->output( "$oldname was successfully renamed to $newname.\n" );
		}
	}
}

$maintClass = RenameUser::class;
require_once RUN_MAINTENANCE_IF_MAIN;
