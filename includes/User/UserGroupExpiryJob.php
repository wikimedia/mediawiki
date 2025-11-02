<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\MediaWikiServices;

/**
 * Purge expired user group memberships.
 *
 * @internal For use by \MediaWiki\User\UserGroupManager
 * @ingroup User
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
