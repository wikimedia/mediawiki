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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\RenameUser\RenameUserFactory;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;

class RenameUser extends Maintenance {
	/** @var UserFactory */
	private $userFactory;

	/** @var RenameUserFactory */
	private $renameUserFactory;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Rename a user' );
		$this->addArg( 'old-name', 'Current username of the to-be-renamed user' );
		$this->addArg( 'new-name', 'New username of the to-be-renamed user' );
		$this->addOption( 'performer', 'Performer of the rename action', false, true );
		$this->addOption( 'reason', 'Reason of the rename', false, true );
		$this->addOption( 'force-global-detach',
			'Rename the local user even if it is attached to a global account' );
		$this->addOption( 'suppress-redirect', 'Don\'t create redirects when moving pages' );
		$this->addOption( 'skip-page-moves', 'Don\'t move associated user pages' );
	}

	private function initServices() {
		$services = $this->getServiceContainer();
		$this->userFactory = $services->getUserFactory();
		$this->renameUserFactory = $services->getRenameUserFactory();
	}

	public function execute() {
		$this->initServices();

		$oldName = $this->getArg( 'old-name' );
		$newName = $this->getArg( 'new-name' );
		$reason = $this->getOption( 'reason' ) ?? '';

		$oldUser = $this->userFactory->newFromName( $oldName );
		$newUser = $this->userFactory->newFromName( $newName );

		if ( !$oldUser ) {
			$this->fatalError( 'The specified old username is invalid.' );
		} elseif ( !$oldUser->isRegistered() ) {
			$this->fatalError( 'The user does not exist.' );
		}
		if ( !$newUser ) {
			$this->fatalError( 'The specified new username is invalid.' );
		} elseif ( $newUser->isRegistered() ) {
			$this->fatalError( 'New username must be free.' );
		}

		if ( $this->getOption( 'performer' ) === null ) {
			$performer = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		} else {
			$performer = $this->userFactory->newFromName( $this->getOption( 'performer' ) );
		}

		if ( !( $performer instanceof User ) || !$performer->isRegistered() ) {
			$this->fatalError( 'Performer does not exist.' );
		}

		$rename = $this->renameUserFactory->newRenameUser( $performer, $oldUser, $newName, $reason, [
			'forceGlobalDetach' => $this->getOption( 'force-global-detach' ),
			'movePages' => !$this->getOption( 'skip-page-moves' ),
			'suppressRedirect' => $this->getOption( 'suppress-redirect' ),
		] );
		$status = $rename->renameUnsafe();

		if ( $status->isGood() ) {
			$this->output( "Successfully renamed user.\n" );
		} else {
			if ( $status->isOK() ) {
				$this->output( "Successfully renamed user with some warnings.\n" );
				foreach ( $status->getMessages() as $msg ) {
					$this->output( '  - ' . wfMessage( $msg )->plain() );
				}
			} else {
				$out = "Failed to rename user:\n";
				foreach ( $status->getMessages() as $msg ) {
					$out = $out . '  - ' . wfMessage( $msg )->plain() . "\n";
				}
				$this->fatalError( $out );
			}
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = RenameUser::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
