<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\MediaWikiServices;

/**
 * Job that initializes an user's edit count.
 *
 * This is used by UserEditTracker when a user's editcount isn't yet set.
 *
 * The following job parameters are required:
 *   - userId: the user ID
 *   - editCount: new edit count to set
 *
 * @internal For use by \MediaWiki\User\UserEditTracker
 * @since 1.36
 * @ingroup User
 */
class UserEditCountInitJob extends Job implements GenericParameterJob {

	public function __construct( array $params ) {
		parent::__construct( 'userEditCountInit', $params );
		$this->removeDuplicates = true;
	}

	/** @inheritDoc */
	public function run() {
		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();

		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_editcount' => $this->params['editCount'] ] )
			->where( [
				'user_id' => $this->params['userId'],
				$dbw->expr( 'user_editcount', '=', null )->or( 'user_editcount', '<', $this->params['editCount'] )
			] )
			->caller( __METHOD__ )->execute();

		return true;
	}
}
