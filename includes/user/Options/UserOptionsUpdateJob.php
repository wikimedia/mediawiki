<?php
/**
 * @license GPL-2.0-or-later
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
