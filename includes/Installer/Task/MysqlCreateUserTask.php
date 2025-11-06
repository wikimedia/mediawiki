<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabaseFactory;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Create the MySQL/MariaDB user
 *
 * @internal For use by the installer
 */
class MysqlCreateUserTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'user';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return 'database';
	}

	public function isSkipped(): bool {
		$dbUser = $this->getConfigVar( MainConfigNames::DBuser );
		return $dbUser == $this->getOption( 'InstallUser' )
			|| $this->getOption( 'InstallUser' ) === null;
	}

	public function execute(): Status {
		$dbUser = $this->getConfigVar( MainConfigNames::DBuser );
		$status = $this->getConnection( ITaskContext::CONN_CREATE_DATABASE );
		if ( !$status->isOK() ) {
			return $status;
		}

		$conn = $status->getDB();
		$server = $this->getConfigVar( MainConfigNames::DBserver );
		$password = $this->getConfigVar( MainConfigNames::DBpassword );
		$grantableNames = [];

		if ( $this->getOption( 'CreateDBAccount' ) ) {
			// Before we blindly try to create a user that already has access,
			try { // first attempt to connect to the database
				( new DatabaseFactory() )->create( 'mysql', [
					'host' => $server,
					'user' => $dbUser,
					'password' => $password,
					'ssl' => $this->getConfigVar( MainConfigNames::DBssl ),
					'dbname' => null,
					'flags' => 0,
					'tablePrefix' => $this->getConfigVar( MainConfigNames::DBprefix )
				] );
				$grantableNames[] = $this->buildFullUserName( $conn, $dbUser, $server );
				$tryToCreate = false;
			} catch ( DBConnectionError ) {
				$tryToCreate = true;
			}
		} else {
			$grantableNames[] = $this->buildFullUserName( $conn, $dbUser, $server );
			$tryToCreate = false;
		}

		if ( $tryToCreate ) {
			$createHostList = [
				$server,
				'localhost',
				'localhost.localdomain',
				'%'
			];

			$createHostList = array_unique( $createHostList );
			$escPass = $conn->addQuotes( $password );

			foreach ( $createHostList as $host ) {
				$fullName = $this->buildFullUserName( $conn, $dbUser, $host );
				if ( !$this->userDefinitelyExists( $conn, $host, $dbUser ) ) {
					try {
						$conn->begin( __METHOD__ );
						$conn->query( "CREATE USER $fullName IDENTIFIED BY $escPass", __METHOD__ );
						$conn->commit( __METHOD__ );
						$grantableNames[] = $fullName;
					} catch ( DBQueryError $dqe ) {
						if ( $conn->lastErrno() == 1396 /* ER_CANNOT_USER */ ) {
							// User (probably) already exists
							$conn->rollback( __METHOD__ );
							$status->warning( 'config-install-user-alreadyexists', $dbUser );
							$grantableNames[] = $fullName;
							break;
						} else {
							// If we couldn't create for some bizarre reason and the
							// user probably doesn't exist, skip the grant
							$conn->rollback( __METHOD__ );
							$status->warning( 'config-install-user-create-failed', $dbUser, $dqe->getMessage() );
						}
					}
				} else {
					$status->warning( 'config-install-user-alreadyexists', $dbUser );
					$grantableNames[] = $fullName;
					break;
				}
			}
		}

		// Try to grant to all the users we know exist or we were able to create
		$dbAllTables = $conn->addIdentifierQuotes( $this->getConfigVar( MainConfigNames::DBname ) ) . '.*';
		foreach ( $grantableNames as $name ) {
			try {
				$conn->begin( __METHOD__ );
				$conn->query( "GRANT ALL PRIVILEGES ON $dbAllTables TO $name", __METHOD__ );
				$conn->commit( __METHOD__ );
			} catch ( DBQueryError $dqe ) {
				$conn->rollback( __METHOD__ );
				$status->fatal( 'config-install-user-grant-failed', $dbUser, $dqe->getMessage() );
			}
		}

		return $status;
	}

	/**
	 * Return a formal 'User'@'Host' username for use in queries
	 * @param IMaintainableDatabase $conn
	 * @param string $name Username, quotes will be added
	 * @param string $host Hostname, quotes will be added
	 * @return string
	 */
	private function buildFullUserName( $conn, $name, $host ) {
		return $conn->addQuotes( $name ) . '@' . $conn->addQuotes( $host );
	}

	/**
	 * Try to see if the user account exists. Our "superuser" may not have
	 * access to mysql.user, so false means "no" or "maybe"
	 * @param IMaintainableDatabase $conn
	 * @param string $host Hostname to check
	 * @param string $user Username to check
	 * @return bool
	 */
	private function userDefinitelyExists( $conn, $host, $user ) {
		try {
			$res = $conn->newSelectQueryBuilder()
				->select( [ 'Host', 'User' ] )
				->from( 'mysql.user' )
				->where( [ 'Host' => $host, 'User' => $user ] )
				->caller( __METHOD__ )->fetchRow();

			return (bool)$res;
		} catch ( DBQueryError ) {
			return false;
		}
	}

}
