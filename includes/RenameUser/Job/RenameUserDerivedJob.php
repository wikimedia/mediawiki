<?php

namespace MediaWiki\RenameUser\Job;

use MediaWiki\JobQueue\Job;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\RenameUser\RenameUserFactory;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;

/**
 * Custom job to perform user rename on wiki family using shared tables or virtual domains.
 *
 * This job only performs derived local renames, so the shared tables must be updated before
 * enqueuing this job.
 *
 * If the user table is not shared on this wiki, or the target user does not have a proper name,
 * the job will fail silently.
 *
 * Job parameters include:
 *   - oldname          : The old user name
 *   - newname          : The new user name
 *   - uid              : The user id
 *   - performer        : The renamer user id
 *   - reason           : Reason
 *   - movePages        : Whether user pages should be moved
 *   - suppressRedirect : Suppress redirect when moving user pages, when possible
 */
class RenameUserDerivedJob extends Job {
	/** @var RenameUserFactory */
	private $renameUserFactory;
	/** @var UserFactory */
	private $userFactory;

	public function __construct(
		Title $title,
		array $params,
		RenameUserFactory $renameUserFactory,
		UserFactory $userFactory
		) {
		parent::__construct( 'renameUserDerived', $title, $params );

		$this->renameUserFactory = $renameUserFactory;
		$this->userFactory = $userFactory;
	}

	public function run() {
		$oldName = $this->params['oldname'];
		$newName = $this->params['newname'];
		$uid = $this->params['uid'];
		$performerUid = $this->params['performer'];
		$reason = $this->params['reason'];
		$movePages = $this->params['movePages'] ?? true;
		$suppressRedirect = $this->params['suppressRedirect'] ?? false;

		$user = $this->userFactory->newFromId( $uid );
		$performer = $this->userFactory->newFromId( $performerUid );
		$logger = LoggerFactory::getInstance( 'RenameUser' );

		// Check if this wiki has the same shared user database as the trigger wiki.
		if ( !$this->userFactory->isUserTableShared() ) {
			return true;
		}
		if ( $user->getName() !== $newName ) {
			$logger->info(
				"User to be renamed from $oldName to $newName does not have the expected name, skipping"
			);
			return true;
		}

		// Do the rename
		$rename = $this->renameUserFactory->newDerivedRenameUser(
			$performer,
			$uid,
			$oldName,
			$newName,
			$reason,
			[
				'movePages' => $movePages,
				'suppressRedirect' => $suppressRedirect
			]
		);
		$status = $rename->renameLocal();
		if ( !$status->isGood() ) {
			$logger->error(
				"Cannot finish derived local user rename from $oldName to $newName: $status"
			);
		}

		return $status->isOK();
	}
}
