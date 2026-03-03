<?php
/**
 * Checks if members of restricted groups are still eligible for their group memberships.
 * If not, and the group is configured with automatic demotion, the user is removed from the group.
 * Usage: php demoteIneligibleUsers.php
 * where <user> can be either the username or user ID
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\User\UserIdentityValue;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * @since 1.46
 */
class DemoteIneligibleUsers extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Demote users who no longer meet conditions for group membership' );
		$this->addOption( 'dry-run', 'Perform a dry run' );
	}

	public function execute() {
		$dryRun = $this->hasOption( 'dry-run' );
		$services = $this->getServiceContainer();
		$restrictedGroups = $services->getRestrictedUserGroupConfigReader()->getConfig();

		$demotableGroups = array_filter(
			$restrictedGroups,
			static fn ( $restriction ) => $restriction->allowsAutomaticDemotion()
		);

		if ( !$demotableGroups ) {
			$this->output( "No groups are configured for automatic demotion, exiting.\n" );
			return;
		}

		$dbr = $this->getReplicaDB();
		$groupMembers = $dbr->newSelectQueryBuilder()
			->select( [ 'user_id', 'user_name', 'ug_group' ] )
			->from( 'user' )
			->join( 'user_groups', null, 'ug_user = user_id' )
			->where( [ 'ug_group' => array_keys( $demotableGroups ) ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		// Remove all groups later at once, not to produce multiple logs for a single user; both arrays keyed by user ID
		$userIdentities = [];
		$groupsToRemove = [];

		$userRequirementsChecker = $services->getUserRequirementsConditionChecker();
		$userFactory = $services->getUserFactory();
		foreach ( $groupMembers as $member ) {
			$userIdentity = UserIdentityValue::newRegistered( $member->user_id, $member->user_name );
			$groupConditions = $demotableGroups[$member->ug_group]->getMemberConditions();

			if (
				$userRequirementsChecker->recursivelyCheckCondition( $groupConditions, $userIdentity )
				|| $userFactory->newFromUserIdentity( $userIdentity )->isSystemUser()
			) {
				continue;
			}

			$userIdentities[$userIdentity->getId()] = $userIdentity;
			$groupsToRemove[$userIdentity->getId()][] = $member->ug_group;
		}

		if ( !$userIdentities ) {
			$this->output( "No ineligible users found, exiting.\n" );
			return;
		}

		$numUsers = count( $userIdentities );
		if ( $dryRun ) {
			$this->output( "DRY RUN: $numUsers users would be affected normally\n" );
		} else {
			$this->output( "Removing groups from $numUsers users...\n" );
		}

		$performingUser = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		$performingAuthority = new UltimateAuthority( $performingUser );

		$groupAssignmentService = $services->getUserGroupAssignmentService();
		$removedText = $dryRun ? 'Would remove' : 'Removed';
		foreach ( $userIdentities as $userIdentity ) {
			$userName = $userIdentity->getName();
			$removeUserGroups = $groupsToRemove[$userIdentity->getId()];
			if ( !$dryRun ) {
				$logReason = wfMessage( 'restrictedgroups-autodemotion-log-reason' )
					->params( $userName )
					->numParams( count( $removeUserGroups ) )
					->text();
				$groupAssignmentService->saveChangesToUserGroups(
					$performingAuthority, $userIdentity, [], $removeUserGroups, [], $logReason );
			}
			$groupsList = implode( ', ', $removeUserGroups );
			$this->output( "$removedText groups from $userName: $groupsList\n" );
		}
		$this->output( "Finished processing. $removedText groups from $numUsers users\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = DemoteIneligibleUsers::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
