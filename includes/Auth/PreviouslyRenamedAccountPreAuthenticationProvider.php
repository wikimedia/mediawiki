<?php

namespace MediaWiki\Auth;

use MediaWiki\RenameUser\RenameuserSQL;
use MediaWiki\User\UserFactory;
use StatusValue;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @stable to extend
 */
class PreviouslyRenamedAccountPreAuthenticationProvider extends AbstractPreAuthenticationProvider {

	public function __construct(
		private readonly IConnectionProvider $dbProvider,
		private readonly UserFactory $userFactory,
		array $params = [],
	) {
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getAuthenticationRequests( $action, array $options ) {
		if ( $action === AuthManager::ACTION_CREATE ) {
			if ( $options['username'] ) {
				$creator = $this->userFactory->newFromName( $options['username'] )
					?: $this->userFactory->newAnonymous();
			} else {
				$creator = $this->userFactory->newAnonymous();
			}
			if ( $creator->isAllowed( 'createpreviouslyrenamedaccount' ) ) {
				return [ new PreviouslyRenamedAccountAuthenticationRequest() ];
			}
		}
		return [];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		/** @var ?PreviouslyRenamedAccountAuthenticationRequest $req */
		$req = AuthenticationRequest::getRequestByClass( $reqs,
			PreviouslyRenamedAccountAuthenticationRequest::class );
		if (
			$req instanceof PreviouslyRenamedAccountAuthenticationRequest &&
			$req->allowPreviouslyRenamedAccount &&
			$creator->authorizeAction( 'createpreviouslyrenamedaccount' )
		) {
			// Check overridden by the user
			return StatusValue::newGood();
		}
		return $this->previouslyRenamedAccountStatus( $user->getName(), IDBAccessObject::READ_LATEST );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function testUserForCreation( $user, $autocreate, array $options = [] ) {
		// This is used by the "cancreate" API, so don't check permissions here to allow users
		// who can override to still see whether normal registration attempt would be an error.
		if ( $autocreate || !empty( $options['creating'] ) ) {
			return StatusValue::newGood();
		}
		return $this->previouslyRenamedAccountStatus( $user->getName(), IDBAccessObject::READ_NORMAL );
	}

	/**
	 * @param string $username Username. Make sure to handle spaces and underscores correctly,
	 *   as usernames are stored in different formats in different places.
	 * @param int $flags See DBAccessObjectUtils::getDBFromRecency()
	 * @stable to override
	 */
	protected function previouslyRenamedAccountStatus( string $username, int $flags ): StatusValue {
		$db = DBAccessObjectUtils::getDBFromRecency( $this->dbProvider, $flags );
		if ( RenameuserSQL::isPreviouslyRenamedAccount( $username, $db ) ) {
			return StatusValue::newFatal( 'username-previously-renamed-account' );
		}
		return StatusValue::newGood();
	}
}
