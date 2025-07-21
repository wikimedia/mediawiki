<?php

use MediaWiki\Auth\AuthManager;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\User\UserSelectQueryBuilder;
use Wikimedia\Rdbms\SelectQueryBuilder;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Expire temporary accounts that are registered for longer than `expiryAfterDays` days
 * (defined in $wgAutoCreateTempUser) by forcefully logging them out.
 *
 * Extensions can extend this class to provide their own logic of determining a list
 * of temporary accounts to expire.
 *
 * @stable to extend
 * @since 1.42
 */
class ExpireTemporaryAccounts extends Maintenance {

	protected UserIdentityLookup $userIdentityLookup;
	protected UserFactory $userFactory;
	protected AuthManager $authManager;
	protected TempUserConfig $tempUserConfig;
	protected UserIdentityUtils $userIdentityUtils;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Expire temporary accounts that exist for more than N days' );
		$this->addOption(
			'frequency',
			'How frequently the script runs [days]. When used with "expiry", determines the ' .
			'cutoff for registration of accounts to be expired. For example, if "expiry" is 90 ' .
			'days and "frequency" is 1 day, then the script will expire accounts that ' .
			'registered more than 90 days ago but not more than 90 + 1 days ago.',
			true,
			true
		);
		$this->addOption(
			'expiry',
			'Expire accounts older than this number of days. Use 0 to expire all temporary accounts',
			false,
			true
		);
		$this->addOption( 'verbose', 'Verbose logging output' );
	}

	/**
	 * Construct services the script needs to use
	 *
	 * @stable to override
	 */
	protected function initServices(): void {
		$services = $this->getServiceContainer();

		$this->userIdentityLookup = $services->getUserIdentityLookup();
		$this->userFactory = $services->getUserFactory();
		$this->authManager = $services->getAuthManager();
		$this->tempUserConfig = $services->getTempUserConfig();
		$this->userIdentityUtils = $services->getUserIdentityUtils();
	}

	/**
	 * If --verbose is passed, log to output
	 *
	 * @param string $log
	 * @return void
	 */
	protected function verboseLog( string $log ) {
		if ( $this->hasOption( 'verbose' ) ) {
			$this->output( $log );
		}
	}

	/**
	 * Return a SelectQueryBuilder that returns temp accounts to invalidate
	 *
	 * This method should return temporary accounts that registered before $registeredBeforeUnix.
	 * To avoid returning an ever-growing set of accounts, the method should skip users that were
	 * supposedly invalidated by a previous script run (script runs each $frequencyDays days).
	 *
	 * If you override this method, you probably also want to override
	 * queryBuilderToUserIdentities().
	 *
	 * @stable to override
	 * @param int $registeredBeforeUnix Cutoff Unix timestamp
	 * @param int $frequencyDays Script runs each $frequencyDays days
	 * @return SelectQueryBuilder
	 */
	protected function getTempAccountsToExpireQueryBuilder(
		int $registeredBeforeUnix,
		int $frequencyDays
	): SelectQueryBuilder {
		return $this->userIdentityLookup->newSelectQueryBuilder()
			->temp()
			->whereRegisteredTimestamp( wfTimestamp(
				TS_MW,
				$registeredBeforeUnix
			), true )
			->whereRegisteredTimestamp( wfTimestamp(
				TS_MW,
				$registeredBeforeUnix - ( 86_400 * $frequencyDays )
			), false );
	}

	/**
	 * Convert a SelectQueryBuilder into a list of user identities
	 *
	 * Default implementation expects $queryBuilder is an instance of UserSelectQueryBuilder. If
	 * you override getTempAccountsToExpireQueryBuilder() to work with a different query builder,
	 * this method should be overriden to properly convert the query builder into user identities.
	 *
	 * @throws LogicException if $queryBuilder is not UserSelectQueryBuilder
	 * @stable to override
	 * @param SelectQueryBuilder $queryBuilder
	 * @return Iterator<UserIdentity>
	 */
	protected function queryBuilderToUserIdentities( SelectQueryBuilder $queryBuilder ): Iterator {
		if ( $queryBuilder instanceof UserSelectQueryBuilder ) {
			return $queryBuilder->fetchUserIdentities();
		}

		throw new LogicException(
			'$queryBuilder is not UserSelectQueryBuilder. Did you forget to override ' .
			__METHOD__ . '?'
		);
	}

	/**
	 * Expire a temporary account
	 *
	 * Default implementation calls AuthManager::revokeAccessForUser and
	 * SessionManager::invalidateSessionsForUser.
	 *
	 * @stable to override
	 * @param UserIdentity $tempAccountUserIdentity
	 */
	protected function expireTemporaryAccount( UserIdentity $tempAccountUserIdentity ): void {
		$this->authManager->revokeAccessForUser( $tempAccountUserIdentity->getName() );
		SessionManager::singleton()->invalidateSessionsForUser(
			$this->userFactory->newFromUserIdentity( $tempAccountUserIdentity )
		);
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$this->initServices();

		if ( !$this->tempUserConfig->isKnown() ) {
			$this->output( 'Temporary accounts are disabled' . PHP_EOL );
			return;
		}

		$frequencyDays = (int)$this->getOption( 'frequency' );
		if ( $this->getOption( 'expiry' ) !== null ) {
			$expiryAfterDays = (int)$this->getOption( 'expiry' );
		} else {
			$expiryAfterDays = $this->tempUserConfig->getExpireAfterDays();
		}
		if ( $expiryAfterDays === null ) {
			$this->output( 'Temporary account expiry is not enabled' . PHP_EOL );
			return;
		}
		$registeredBeforeUnix = (int)wfTimestamp( TS_UNIX ) - ( 86_400 * $expiryAfterDays );

		$tempAccounts = $this->queryBuilderToUserIdentities( $this->getTempAccountsToExpireQueryBuilder(
			$registeredBeforeUnix,
			$frequencyDays
		)->caller( __METHOD__ ) );

		$revokedUsers = 0;
		foreach ( $tempAccounts as $tempAccountUserIdentity ) {
			if ( !$this->userIdentityUtils->isTemp( $tempAccountUserIdentity ) ) {
				// Not a temporary account, skip it.
				continue;
			}

			$this->expireTemporaryAccount( $tempAccountUserIdentity );

			$this->verboseLog(
				'Revoking access for ' . $tempAccountUserIdentity->getName() . PHP_EOL
			);
			$revokedUsers++;
		}

		$this->output( "Revoked access for $revokedUsers temporary users." . PHP_EOL );
	}
}

// @codeCoverageIgnoreStart
$maintClass = ExpireTemporaryAccounts::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
