<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logging\PatrolLog;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Storage\RevertedTagUpdateManager;
use MediaWiki\User\UserFactory;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @since 1.45
 */
class PatrolManager {

	public const PRC_UNPATROLLED = 0;
	public const PRC_PATROLLED = 1;
	public const PRC_AUTOPATROLLED = 2;

	/**
	 * @internal For use by ServiceWiring only
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UseRCPatrol,
		MainConfigNames::UseNPPatrol,
		MainConfigNames::UseFilePatrol,
	];

	private IConnectionProvider $connectionProvider;
	private UserFactory $userFactory;
	private HookContainer $hookContainer;
	private RevertedTagUpdateManager $revertedTagUpdateManager;

	private bool $useRCPatrol;
	private bool $useNPPatrol;
	private bool $useFilePatrol;

	public function __construct(
		ServiceOptions $options,
		IConnectionProvider $connectionProvider,
		UserFactory $userFactory,
		HookContainer $hookContainer,
		RevertedTagUpdateManager $revertedTagUpdateManager
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->connectionProvider = $connectionProvider;
		$this->userFactory = $userFactory;
		$this->hookContainer = $hookContainer;
		$this->revertedTagUpdateManager = $revertedTagUpdateManager;

		$this->useRCPatrol = $options->get( MainConfigNames::UseRCPatrol );
		$this->useNPPatrol = $options->get( MainConfigNames::UseNPPatrol );
		$this->useFilePatrol = $options->get( MainConfigNames::UseFilePatrol );
	}

	/**
	 * Mark this RecentChange as patrolled
	 *
	 * NOTE: Can also return 'rcpatroldisabled', 'hookaborted' and
	 * 'markedaspatrollederror-noautopatrol' as errors
	 *
	 * @param RecentChange $recentChange
	 * @param Authority $performer User performing the action
	 * @param string|string[]|null $tags Change tags to add to the patrol log entry
	 *   ($performer should be able to add the specified tags before this is called)
	 * @return PermissionStatus
	 */
	public function markPatrolled(
		RecentChange $recentChange,
		Authority $performer,
		$tags = null
	): PermissionStatus {
		// Fix up $tags so that the MarkPatrolled hook below always gets an array
		if ( $tags === null ) {
			$tags = [];
		} elseif ( is_string( $tags ) ) {
			$tags = [ $tags ];
		}

		// If recentchanges patrol is disabled, only new pages or new file versions
		// can be patrolled, provided the appropriate config variable is set
		if ( !$this->useRCPatrol &&
			( !$this->useNPPatrol || $recentChange->getAttribute( 'rc_source' ) != RecentChange::SRC_NEW ) &&
			( !$this->useFilePatrol || !( $recentChange->getAttribute( 'rc_source' ) == RecentChange::SRC_LOG &&
				$recentChange->getAttribute( 'rc_log_type' ) == 'upload' ) )
		) {
			return PermissionStatus::newFatal( 'rcpatroldisabled' );
		}

		// Users without the 'autopatrol' right can't patrol their own revisions
		if ( $performer->getUser()->equals( $recentChange->getPerformerIdentity() )
			&& !$performer->isAllowed( 'autopatrol' )
		) {
			return PermissionStatus::newFatal( 'markedaspatrollederror-noautopatrol' );
		}

		$status = PermissionStatus::newEmpty();
		$performer->authorizeWrite( 'patrol', $recentChange->getTitle(), $status );
		if ( !$status->isGood() ) {
			return $status;
		}

		$user = $this->userFactory->newFromAuthority( $performer );
		$hookRunner = new HookRunner( $this->hookContainer );

		if ( !$hookRunner->onMarkPatrolled( $recentChange->getAttribute( 'rc_id' ), $user, false, false, $tags ) ) {
			return PermissionStatus::newFatal( 'hookaborted' );
		}

		// If the change was patrolled already, do nothing
		if ( $recentChange->getAttribute( 'rc_patrolled' ) ) {
			return $status;
		}

		// Attempt to set the 'patrolled' flag in RC database
		$affectedRowCount = $this->reallyMarkPatrolled( $recentChange );

		if ( $affectedRowCount === 0 ) {
			// Query succeeded but no rows change, e.g. another request
			// patrolled the same change just before us.
			// Avoid duplicate log entry (T196182).
			return $status;
		}

		// Log this patrol event
		PatrolLog::record( $recentChange, false, $performer->getUser(), $tags );

		$hookRunner->onMarkPatrolledComplete( $recentChange->getAttribute( 'rc_id' ), $user, false, false );

		return $status;
	}

	/**
	 * Mark this RecentChange patrolled, without error checking
	 *
	 * @param RecentChange $recentChange
	 * @return int Number of database rows changed, usually 1, but 0 if
	 * another request already patrolled it in the mean time.
	 */
	public function reallyMarkPatrolled( RecentChange $recentChange ): int {
		$dbw = $this->connectionProvider->getPrimaryDatabase();
		$dbw->newUpdateQueryBuilder()
			->update( 'recentchanges' )
			->set( [ 'rc_patrolled' => self::PRC_PATROLLED ] )
			->where( [
				'rc_id' => $recentChange->getAttribute( 'rc_id' ),
				'rc_patrolled' => self::PRC_UNPATROLLED,
			] )
			->caller( __METHOD__ )->execute();

		$affectedRowCount = $dbw->affectedRows();

		// The change was patrolled already, do nothing
		if ( $affectedRowCount === 0 ) {
			return 0;
		}

		// Invalidate the page cache after the page has been patrolled
		// to make sure that the Patrol link isn't visible any longer!
		$recentChange->getTitle()->invalidateCache();

		// Enqueue a reverted tag update (in case the edit was a revert)
		$revisionId = $recentChange->getAttribute( 'rc_this_oldid' );
		if ( $revisionId ) {
			$this->revertedTagUpdateManager->approveRevertedTagForRevision( $revisionId );
		}

		return $affectedRowCount;
	}
}
