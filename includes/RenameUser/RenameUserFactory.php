<?php

namespace MediaWiki\RenameUser;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\CentralId\CentralIdLookupFactory;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNameUtils;

/**
 * @since 1.44
 */
class RenameUserFactory {
	private ServiceOptions $options;
	private CentralIdLookupFactory $centralIdLookupFactory;
	private JobQueueGroupFactory $jobQueueGroupFactory;
	private MovePageFactory $movePageFactory;
	private UserFactory $userFactory;
	private UserNameUtils $userNameUtils;
	private PermissionManager $permissionManager;
	private TitleFactory $titleFactory;

	/**
	 * @internal Use only in ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = RenameUser::CONSTRUCTOR_OPTIONS;

	public function __construct(
		ServiceOptions $options,
		CentralIdLookupFactory $centralIdLookupFactory,
		JobQueueGroupFactory $jobQueueGroupFactory,
		MovePageFactory $movePageFactory,
		UserFactory $userFactory,
		UserNameUtils $userNameUtils,
		PermissionManager $permissionManager,
		TitleFactory $titleFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->centralIdLookupFactory = $centralIdLookupFactory;
		$this->movePageFactory = $movePageFactory;
		$this->jobQueueGroupFactory = $jobQueueGroupFactory;
		$this->userFactory = $userFactory;
		$this->userNameUtils = $userNameUtils;
		$this->permissionManager = $permissionManager;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * Creates a RenameUser for performing a new rename operation.
	 *
	 * The operation should not have been performed on any wikis yet.
	 *
	 * @param User $performer
	 * @param User $target
	 * @param string $newName
	 * @param string $reason
	 * @param array $renameOptions
	 * @return RenameUser
	 */
	public function newRenameUser(
		User $performer,
		$target,
		string $newName,
		string $reason,
		array $renameOptions = []
	): RenameUser {
		return new RenameUser(
			$this->options,
			$this->centralIdLookupFactory,
			$this->jobQueueGroupFactory,
			$this->movePageFactory,
			$this->userFactory,
			$this->userNameUtils,
			$this->permissionManager,
			$this->titleFactory,
			$performer,
			$target,
			$target->getName(),
			$newName,
			$reason,
			$renameOptions
		);
	}

	/**
	 * Creates a RenameUser for performing a local rename operation derived from a global rename.
	 *
	 * The operation must have been performed on central wiki and not locally.
	 *
	 * @param User $performer
	 * @param int $uid
	 * @param string $oldName
	 * @param string $newName
	 * @param string $reason
	 * @param array $renameOptions
	 * @return RenameUser
	 */
	public function newDerivedRenameUser(
		User $performer,
		int $uid,
		string $oldName,
		string $newName,
		string $reason,
		array $renameOptions = []
	): RenameUser {
		return new RenameUser(
			$this->options,
			$this->centralIdLookupFactory,
			$this->jobQueueGroupFactory,
			$this->movePageFactory,
			$this->userFactory,
			$this->userNameUtils,
			$this->permissionManager,
			$this->titleFactory,
			$performer,
			$this->userFactory->newFromId( $uid ),
			$oldName,
			$newName,
			$reason,
			array_merge( $renameOptions, [ 'derived' => true ] )
		);
	}

}
