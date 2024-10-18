<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;

class CreateSysopTask extends Task {
	/** @var UserFactory */
	private $userFactory;

	/** @var UserGroupManager */
	private $userGroupManager;

	public function getName() {
		return 'sysop';
	}

	public function getDependencies() {
		return [ 'services', 'tables' ];
	}

	public function execute(): Status {
		$this->initServices( $this->getServices() );
		$name = $this->getOption( 'AdminName' );
		$user = $this->userFactory->newFromName( $name );

		if ( !$user ) {
			// We should've validated this earlier anyway!
			return Status::newFatal( 'config-admin-error-user', $name );
		}

		if ( $user->idForName() == 0 ) {
			$user->addToDatabase();

			$password = $this->getOption( 'AdminPassword' );
			$status = $user->changeAuthenticationData( [
				'username' => $user->getName(),
				'password' => $password,
				'retype' => $password,
			] );
			if ( !$status->isGood() ) {
				return Status::newFatal( 'config-admin-error-password',
					$name, $status->getWikiText( false, false, $this->getOption( 'UserLang' ) ) );
			}

			$this->userGroupManager->addUserToGroup( $user, 'sysop' );
			$this->userGroupManager->addUserToGroup( $user, 'bureaucrat' );
			$this->userGroupManager->addUserToGroup( $user, 'interface-admin' );
			if ( $this->getOption( 'AdminEmail' ) ) {
				$user->setEmail( $this->getOption( 'AdminEmail' ) );
			}
			$user->saveSettings();

			// Update user count
			$ssUpdate = SiteStatsUpdate::factory( [ 'users' => 1 ] );
			$ssUpdate->doUpdate();
		}

		return Status::newGood();
	}

	private function initServices( MediaWikiServices $services ) {
		$this->userFactory = $services->getUserFactory();
		$this->userGroupManager = $services->getUserGroupManager();
	}

}
