<?php

namespace MediaWiki\RenameUser;

use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\RequestContext;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\JobQueue\JobSpecification;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\CentralId\CentralIdLookupFactory;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\WikiMap\WikiMap;
use Psr\Log\LoggerInterface;

/**
 * Handles the backend logic of renaming users.
 *
 * @since 1.44
 */
class RenameUser {

	/** @var User */
	private $performer;
	/** @var User */
	private $target;
	/** @var string */
	private $oldName;
	/** @var string */
	private $newName;
	/** @var string */
	private $reason;

	/** @var bool */
	private $forceGlobalDetach = false;
	/** @var bool */
	private $movePages = true;
	/** @var bool */
	private $suppressRedirect = false;
	/** @var bool */
	private $derived = false;

	private CentralIdLookupFactory $centralIdLookupFactory;
	private JobQueueGroupFactory $jobQueueGroupFactory;
	private MovePageFactory $movePageFactory;
	private UserFactory $userFactory;
	private UserNameUtils $userNameUtils;
	private PermissionManager $permissionManager;
	private TitleFactory $titleFactory;
	private LoggerInterface $logger;

	/**
	 * @internal For use by RenameUserFactory
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::LocalDatabases,
	];

	private array $localDatabases;

	/**
	 * @param ServiceOptions $options
	 * @param CentralIdLookupFactory $centralIdLookupFactory
	 * @param JobQueueGroupFactory $jobQueueGroupFactory
	 * @param MovePageFactory $movePageFactory
	 * @param UserFactory $userFactory
	 * @param UserNameUtils $userNameUtils
	 * @param PermissionManager $permissionManager
	 * @param TitleFactory $titleFactory
	 * @param User $performer
	 * @param User $target
	 * @param string $oldName
	 * @param string $newName
	 * @param string $reason
	 * @param array $renameOptions
	 *    Valid options:
	 *    - forceGlobalDetach      : Force to detach from CentralAuth
	 *    - movePages              : Whether user pages should be moved
	 *    - suppressRedirect       : Whether to suppress redirects for user pages
	 *    - derived                : Whether shared tables should be updated
	 *        If derived is true, it is assumed that all shared tables have been updated.
	 */
	public function __construct(
		ServiceOptions $options,
		CentralIdLookupFactory $centralIdLookupFactory,
		JobQueueGroupFactory $jobQueueGroupFactory,
		MovePageFactory $movePageFactory,
		UserFactory $userFactory,
		UserNameUtils $userNameUtils,
		PermissionManager $permissionManager,
		TitleFactory $titleFactory,
		User $performer,
		User $target,
		string $oldName,
		string $newName,
		string $reason,
		array $renameOptions
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->localDatabases = $options->get( MainConfigNames::LocalDatabases );

		$this->centralIdLookupFactory = $centralIdLookupFactory;
		$this->movePageFactory = $movePageFactory;
		$this->jobQueueGroupFactory = $jobQueueGroupFactory;
		$this->userFactory = $userFactory;
		$this->userNameUtils = $userNameUtils;
		$this->permissionManager = $permissionManager;
		$this->titleFactory = $titleFactory;
		$this->logger = LoggerFactory::getInstance( 'RenameUser' );

		foreach ( [
			'forceGlobalDetach',
			'movePages',
			'suppressRedirect',
			'derived',
		] as $possibleOption ) {
			if ( isset( $renameOptions[ $possibleOption ] ) ) {
				$this->$possibleOption = $renameOptions[ $possibleOption ];
			}
		}

		$this->performer = $performer;
		$this->target = $target;
		$this->oldName = $oldName;
		$this->newName = $newName;
		$this->reason = $reason;
	}

	/**
	 * Checks if the rename operation is valid.
	 * @note This method doesn't check user permissions. Use 'rename' for that.
	 * @return Status Validation result.
	 *   If status is Ok with no errors, the rename can be performed.
	 *   If status is Ok with some errors,
	 */
	private function check(): Status {
		// Check if the user has a proper name
		// The wiki triggering a global rename across a wiki family using virtual domains
		// may not have the same user database as this wiki
		$expectedName = $this->oldName;
		if ( $this->derived && $this->userFactory->isUserTableShared() ) {
			$expectedName = $this->newName;
		}
		if ( $this->target->getName() !== $expectedName ) {
			return Status::newFatal( 'renameuser-error-unexpected-name' );
		}

		// Check user names valid
		$newRigor = $this->derived ? UserFactory::RIGOR_NONE : UserFactory::RIGOR_CREATABLE;
		$oldUser = $this->userFactory->newFromName( $this->oldName, UserFactory::RIGOR_NONE );
		$newUser = $this->userFactory->newFromName( $this->newName, $newRigor );
		if ( !$oldUser ) {
			return Status::newFatal( 'renameusererrorinvalid', $this->oldName );
		}
		if ( !$newUser ) {
			return Status::newFatal( 'renameusererrorinvalid', $this->newName );
		}
		$currentUser = $this->derived ? $newUser : $oldUser;
		if ( !$currentUser->isRegistered() ) {
			return Status::newFatal( 'renameusererrordoesnotexist', $currentUser->getName() );
		}
		if ( !$this->derived && $newUser->isRegistered() ) {
			return Status::newFatal( 'renameusererrorexists', $this->newName );
		}

		// Do not act on temp users
		if ( $this->userNameUtils->isTemp( $this->oldName ) ) {
			return Status::newFatal( 'renameuser-error-temp-user', $this->oldName );
		}
		if (
			$this->userNameUtils->isTemp( $this->newName ) ||
			$this->userNameUtils->isTempReserved( $this->newName )
		) {
			return Status::newFatal( 'renameuser-error-temp-user-reserved', $this->newName );
		}

		// Check global detaching
		$centralIdLookup = $this->centralIdLookupFactory->getNonLocalLookup();
		$userCentralAttached = $centralIdLookup && $centralIdLookup->isAttached( $this->target );
		if ( !$this->forceGlobalDetach && $userCentralAttached ) {
			return Status::newFatal( 'renameuser-error-global-detaching' );
		}

		return Status::newGood();
	}

	/**
	 * Performs the rename in local domain.
	 * @return Status
	 */
	public function renameLocal(): Status {
		$status = $this->check();
		if ( !$status->isOK() ) {
			return $status;
		}

		$user = $this->target;
		$performer = $this->performer;
		$oldName = $this->oldName;
		$newName = $this->newName;

		$options = [
			'reason' => $this->reason,
			'derived' => $this->derived,
		];

		// Do the heavy lifting ...
		$rename = new RenameuserSQL(
			$oldName,
			$newName,
			$user->getId(),
			$performer,
			$options
		);
		$status->merge( $rename->renameUser() );
		if ( !$status->isOK() ) {
			return $status;
		}

		// If the user is renaming themself, make sure that code below uses a proper name
		if ( $performer->getId() === $user->getId() ) {
			$performer->setName( $newName );
			$this->performer->setName( $newName );
		}

		// Move any user pages
		$status->merge( $this->moveUserPages() );

		return $status;
	}

	/**
	 * Attempts to move local user pages.
	 * @return Status
	 */
	public function moveUserPages(): Status {
		if ( $this->movePages && $this->permissionManager->userHasRight( $this->performer, 'move' ) ) {
			$suppressRedirect = $this->suppressRedirect
				&& $this->permissionManager->userHasRight( $this->performer, 'suppressredirect' );
			$oldTitle = $this->titleFactory->makeTitle( NS_USER, $this->oldName );
			$newTitle = $this->titleFactory->makeTitle( NS_USER, $this->newName );

			$status = $this->movePagesAndSubPages( $this->performer, $oldTitle, $newTitle, $suppressRedirect );
			if ( !$status->isOK() ) {
				return $status;
			}

			$oldTalkTitle = $oldTitle->getTalkPageIfDefined();
			$newTalkTitle = $newTitle->getTalkPageIfDefined();
			if ( $oldTalkTitle && $newTalkTitle ) {
				$status = $this->movePagesAndSubPages(
					$this->performer,
					$oldTalkTitle,
					$newTalkTitle,
					$suppressRedirect
				);
				if ( !$status->isOK() ) {
					return $status;
				}
			}
		}
		return Status::newGood();
	}

	private function movePagesAndSubPages(
		User $performer, Title $oldTitle, Title $newTitle, bool $suppressRedirect
	): Status {
		$status = Status::newGood();

		$movePage = $this->movePageFactory->newMovePage(
			$oldTitle,
			$newTitle,
		);
		$movePage->setMaximumMovedPages( -1 );
		$logMessage = RequestContext::getMain()->msg(
			'renameuser-move-log',
			$oldTitle->getText(),
			$newTitle->getText()
		)->inContentLanguage()->text();

		if ( $oldTitle->exists() ) {
			$status->merge( $movePage->moveIfAllowed( $performer, $logMessage, !$suppressRedirect ) );
			if ( !$status->isGood() ) {
				return $status;
			}
		}

		$batchStatus = $movePage->moveSubpagesIfAllowed( $performer, $logMessage, !$suppressRedirect );
		foreach ( $batchStatus->getValue() as $titleText => $moveStatus ) {
			$status->merge( $moveStatus );
		}
		return $status;
	}

	/**
	 * Attempts to perform the rename globally.
	 * @note This method doesn't check user permissions. Use 'rename' for that.
	 *
	 * This will first call renameLocal to complete local renaming,
	 * then enqueue RenameUserDerivedJob for all other wikis in the same
	 * wiki family.
	 *
	 * @return Status
	 */
	public function renameGlobal(): Status {
		if ( $this->derived ) {
			throw new LogicException( "Can't rename globally with a command created with newDerivedRenameUser()" );
		}
		$status = $this->renameLocal();
		if ( !$status->isGood() ) {
			return $status;
		}

		// Create jobs for other wikis if needed
		if ( $this->userFactory->isUserTableShared() ) {
			foreach ( $this->localDatabases as $database ) {
				if ( $database == WikiMap::getCurrentWikiDbDomain()->getId() ) {
					continue;
				}
				$status->merge( $this->enqueueRemoteRename( $database ) );
			}
		}

		return $status;
	}

	/**
	 * Enqueues a job to perform local rename on another wiki.
	 *
	 * Checks will not be performed during enqueuing operation.
	 *
	 * @param string $database
	 * @return Status
	 */
	private function enqueueRemoteRename( string $database ): Status {
		$jobParams = [
			'oldname' => $this->oldName,
			'newname' => $this->newName,
			'uid' => $this->target->getId(),
			'performer' => $this->performer->getId(),
			'reason' => $this->reason,
			'movePages' => $this->movePages,
			'suppressRedirect' => $this->suppressRedirect,
		];
		$oldTitle = $this->titleFactory->makeTitle( NS_USER, $this->oldName );
		$this->logger->info( "Enqueuing a rename job for domain {$database}" );
		$job = new JobSpecification( 'renameUserDerived', $jobParams, [], $oldTitle );
		$this->jobQueueGroupFactory->makeJobQueueGroup( $database )->push( $job );
		return Status::newGood();
	}

	/**
	 * Attempts to perform the rename smartly.
	 * @note This method doesn't check user permissions. Use 'rename' for that.
	 *
	 * This decides whether renameGlobal or renameLocal should be used and call the proper
	 * function.
	 *
	 * @return Status
	 */
	public function renameUnsafe(): Status {
		if ( !$this->derived && $this->userFactory->isUserTableShared() ) {
			return $this->renameGlobal();
		} else {
			return $this->renameLocal();
		}
	}

	/**
	 * Attempts to perform the rename smartly after checking the performer's rights.
	 *
	 * This decides whether renameGlobal or renameLocal should be used and call the proper
	 * function.
	 *
	 * @return Status
	 */
	public function rename(): Status {
		// renameuser is always required
		if ( !$this->permissionManager->userHasRight( $this->performer, 'renameuser' ) ) {
			return Status::newFatal( 'badaccess-groups', 'renameuser' );
		}

		// for global renames, renameuser-global is also required
		$centralIdLookup = $this->centralIdLookupFactory->getNonLocalLookup();
		$userCentralAttached = $centralIdLookup && $centralIdLookup->isAttached( $this->target );
		if ( ( $this->userFactory->isUserTableShared() || $userCentralAttached )
			&& !$this->permissionManager->userHasRight( $this->performer, 'renameuser-global' ) ) {
			return Status::newFatal( 'badaccess-groups', 'renameuser-global' );
		}

		return $this->renameUnsafe();
	}
}
