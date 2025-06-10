<?php
/**
 * Creates an account and grants it rights.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 * @author Pablo Castellano <pablo@anche.no>
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Auth\AuthManager;
use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Password\PasswordError;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;

/**
 * Maintenance script to create an account and grant it rights.
 *
 * Note that, if CentralAuth is loaded and $wgCentralAuthAutomaticGlobalGroups is
 * configured, this script will not update the global groups automatically.
 *
 * @ingroup Maintenance
 */
class CreateAndPromote extends Maintenance {
	private const PERMIT_ROLES = [ 'sysop', 'bureaucrat', 'interface-admin', 'bot' ];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Create a new user account and/or grant it additional rights' );
		$this->addOption(
			'force',
			'If account exists already, just grant it rights or change password.'
		);
		foreach ( self::PERMIT_ROLES as $role ) {
			$this->addOption( $role, "Add the account to the {$role} group" );
		}

		$this->addOption(
			'custom-groups',
			'Comma-separated list of groups to add the user to',
			false,
			true
		);

		$this->addOption(
			'reason',
			'Reason for account creation and user rights assignment to log to wiki',
			false,
			true
		);

		$this->addArg( 'username', 'Username of new user' );
		$this->addArg( 'password', 'Password to set', false );
	}

	public function execute() {
		$username = $this->getArg( 0 );
		$password = $this->getArg( 1 );
		$force = $this->hasOption( 'force' );
		$inGroups = [];
		$services = $this->getServiceContainer();

		$user = $services->getUserFactory()->newFromName( $username );
		if ( !is_object( $user ) ) {
			$this->fatalError( 'invalid username.' );
		}

		if ( $services->getUserNameUtils()->isTemp( $user->getName() ) ) {
			$this->fatalError(
				'Temporary accounts cannot have groups or a password, so this script should not be used ' .
				'to create a temporary account. Temporary accounts can be created by making an edit while logged out.'
			);
		}

		$exists = ( $user->idForName() !== 0 );

		if ( $exists && !$force ) {
			$this->fatalError( 'Account exists. Perhaps you want the --force option?' );
		} elseif ( !$exists && !$password ) {
			$this->error( 'Argument <password> required!' );
			$this->maybeHelp( true );
		} elseif ( $exists ) {
			$inGroups = $services->getUserGroupManager()->getUserGroups( $user );
		}

		$groups = array_filter( self::PERMIT_ROLES, $this->hasOption( ... ) );
		if ( $this->hasOption( 'custom-groups' ) ) {
			$allGroups = array_fill_keys( $services->getUserGroupManager()->listAllGroups(), true );
			$customGroupsText = $this->getOption( 'custom-groups' );
			if ( $customGroupsText !== '' ) {
				$customGroups = explode( ',', $customGroupsText );
				foreach ( $customGroups as $customGroup ) {
					if ( isset( $allGroups[$customGroup] ) ) {
						$groups[] = trim( $customGroup );
					} else {
						$this->output( "$customGroup is not a valid group, ignoring!\n" );
					}
				}
			}
		}

		$promotions = array_diff(
			$groups,
			$inGroups
		);

		if ( $exists && !$password && count( $promotions ) === 0 ) {
			$this->output( "Account exists and nothing to do.\n" );

			return;
		} elseif ( count( $promotions ) !== 0 ) {
			$dbDomain = WikiMap::getCurrentWikiDbDomain()->getId();
			$promoText = "User:{$username} into " . implode( ', ', $promotions ) . "...\n";
			if ( $exists ) {
				$this->output( "$dbDomain: Promoting $promoText" );
			} else {
				$this->output( "$dbDomain: Creating and promoting $promoText" );
			}
		}

		if ( !$exists ) {
			// Verify the password meets the password requirements before creating.
			// This check is repeated below to account for differences between
			// the password policy for regular users and for users in certain groups.
			if ( $password ) {
				$status = $user->checkPasswordValidity( $password );

				if ( !$status->isGood() ) {
					$this->fatalError( $status );
				}
			}

			// Create the user via AuthManager as there may be various side
			// effects that are performed by the configured AuthManager chain.
			$status = $this->getServiceContainer()->getAuthManager()->autoCreateUser(
				$user,
				AuthManager::AUTOCREATE_SOURCE_MAINT,
				false
			);
			if ( !$status->isGood() ) {
				$this->fatalError( $status );
			}
		}

		if ( $promotions ) {
			// Add groups before changing password, as the password policy for certain groups has
			// stricter requirements.
			$userGroupManager = $services->getUserGroupManager();
			$userGroupManager->addUserToMultipleGroups( $user, $promotions );
			$reason = $this->getOption( 'reason' ) ?: '';
			$this->addLogEntry( $user, $inGroups, array_merge( $inGroups, $promotions ), $reason );
		}

		if ( $password ) {
			# Try to set the password
			try {
				$status = $user->changeAuthenticationData( [
					'username' => $user->getName(),
					'password' => $password,
					'retype' => $password,
				] );
				if ( !$status->isGood() ) {
					throw new PasswordError( $status->getMessage( false, false, 'en' )->text() );
				}
				if ( $exists ) {
					$this->output( "Password set.\n" );
					$user->saveSettings();
				}
			} catch ( PasswordError $pwe ) {
				$this->fatalError( 'Setting the password failed: ' . $pwe->getMessage() );
			}
		}

		if ( !$exists ) {
			# Increment site_stats.ss_users
			$ssu = SiteStatsUpdate::factory( [ 'users' => 1 ] );
			$ssu->doUpdate();
		}

		$this->output( "done.\n" );
	}

	/**
	 * Add a rights log entry for an action.
	 *
	 * @param User $user
	 * @param array $oldGroups
	 * @param array $newGroups
	 * @param string $reason
	 */
	private function addLogEntry( $user, array $oldGroups, array $newGroups, string $reason ) {
		$logEntry = new ManualLogEntry( 'rights', 'rights' );
		$logEntry->setPerformer( User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] ) );
		$logEntry->setTarget( $user->getUserPage() );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( [
			'4::oldgroups' => $oldGroups,
			'5::newgroups' => $newGroups
		] );
		$logid = $logEntry->insert();
		$logEntry->publish( $logid );
	}
}

// @codeCoverageIgnoreStart
$maintClass = CreateAndPromote::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
