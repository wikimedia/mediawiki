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

use MediaWiki\Page\MovePageFactory;
use MediaWiki\RenameUser\RenameuserSQL;
use MediaWiki\Title\Title;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;

class RenameUser extends Maintenance {
	/** @var UserFactory */
	private $userFactory;

	/** @var CentralIdLookup|null */
	private $centralLookup;

	/** @var MovePageFactory */
	private $movePageFactory;

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
		$this->centralLookup = $services->getCentralIdLookupFactory()->getNonLocalLookup();
		$this->movePageFactory = $services->getMovePageFactory();
	}

	public function execute() {
		$this->initServices();

		$oldName = $this->getArg( 'old-name' );
		$newName = $this->getArg( 'new-name' );

		$oldUser = $this->userFactory->newFromName( $oldName );
		if ( !$oldUser ) {
			$this->fatalError( 'The specified old username is invalid' );
		}

		if ( !$oldUser->isRegistered() ) {
			$this->fatalError( 'The user does not exist' );
		}

		if ( !$this->getOption( 'force-global-detach' )
			&& $this->centralLookup
			&& $this->centralLookup->isAttached( $oldUser )
		) {
			$this->fatalError( 'The user is globally attached. Use CentralAuth to rename this account.' );
		}

		$newUser = $this->userFactory->newFromName( $newName, UserFactory::RIGOR_CREATABLE );
		if ( !$newUser ) {
			$this->fatalError( 'The specified new username is invalid' );
		} elseif ( $newUser->isRegistered() ) {
			$this->fatalError( 'New username must be free' );
		}

		if ( $this->getOption( 'performer' ) === null ) {
			$performer = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		} else {
			$performer = $this->userFactory->newFromName( $this->getOption( 'performer' ) );
		}

		if ( !( $performer instanceof User ) || !$performer->isRegistered() ) {
			$this->fatalError( 'Performer does not exist.' );
		}

		$renamer = new RenameuserSQL(
			$oldUser->getName(),
			$newUser->getName(),
			$oldUser->getId(),
			$performer,
			[
				'reason' => $this->getOption( 'reason' )
			]
		);

		if ( !$renamer->rename() ) {
			$this->fatalError( 'Renaming failed.' );
		} else {
			$this->output( "{$oldUser->getName()} was successfully renamed to {$newUser->getName()}.\n" );
		}

		$numRenames = 0;
		if ( !$this->getOption( 'skip-page-moves' ) ) {
			$numRenames += $this->movePageAndSubpages(
				$performer,
				$oldUser->getUserPage(),
				$newUser->getUserPage(),
				'user'
			);
			$numRenames += $this->movePageAndSubpages(
				$performer,
				$oldUser->getTalkPage(),
				$newUser->getTalkPage(),
				'user talk',
			);
		}
		if ( $numRenames > 0 ) {
			$this->output( "$numRenames user page(s) renamed\n" );
		}
	}

	private function movePageAndSubpages( User $performer, Title $oldTitle, Title $newTitle, $kind ) {
		$movePage = $this->movePageFactory->newMovePage(
			$oldTitle,
			$newTitle,
		);
		$movePage->setMaximumMovedPages( -1 );
		$logMessage = wfMessage(
			'renameuser-move-log', $oldTitle->getText(), $newTitle->getText()
		)->inContentLanguage()->text();
		$createRedirect = !$this->getOption( 'suppress-redirect' );

		$numRenames = 0;
		if ( $oldTitle->exists() ) {
			$status = $movePage->move( $performer, $logMessage, $createRedirect );
			if ( $status->isGood() ) {
				$numRenames++;
			} else {
				$this->output( "Failed to rename $kind page: " .
					$status->getWikiText( false, false, 'en' ) .
					"\n" );
			}
		}

		$batchStatus = $movePage->moveSubpages( $performer, $logMessage, $createRedirect );
		foreach ( $batchStatus->getValue() as $titleText => $status ) {
			if ( $status->isGood() ) {
				$numRenames++;
			} else {
				$this->output( "Failed to rename $kind subpage \"$titleText\": " .
					$status->getWikiText( false, false, 'en' ) . "\n" );
			}
		}
		return $numRenames;
	}
}

$maintClass = RenameUser::class;
require_once RUN_MAINTENANCE_IF_MAIN;
