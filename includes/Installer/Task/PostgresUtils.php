<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @internal For use by the installer
 */
class PostgresUtils {
	private const MAX_ROLE_SEARCH_DEPTH = 5;

	/** @var ITaskContext */
	private $context;

	public function __construct( ITaskContext $context ) {
		$this->context = $context;
	}

	public function canCreateAccounts(): bool {
		$perms = $this->getInstallUserPermissions();
		return ( $perms && $perms->rolsuper ) || $perms->rolcreaterole;
	}

	public function isSuperUser(): bool {
		$perms = $this->getInstallUserPermissions();
		return $perms && $perms->rolsuper;
	}

	/**
	 * Returns true if the install user is able to create objects owned
	 * by the web user, false otherwise.
	 * @return bool
	 */
	public function canCreateObjectsForWebUser() {
		if ( $this->isSuperUser() ) {
			return true;
		}

		$status = $this->context->getConnection( ITaskContext::CONN_CREATE_DATABASE );
		if ( !$status->isOK() ) {
			return false;
		}
		$conn = $status->getDB();
		$installerId = $conn->selectField( 'pg_catalog.pg_roles', 'oid',
			[ 'rolname' => $this->context->getOption( 'InstallUser' ) ], __METHOD__ );
		$webId = $conn->selectField( 'pg_catalog.pg_roles', 'oid',
			[ 'rolname' => $this->context->getConfigVar( MainConfigNames::DBuser ) ], __METHOD__ );

		return self::isRoleMember( $conn, $installerId, $webId, self::MAX_ROLE_SEARCH_DEPTH );
	}

	/** @return \stdClass|false */
	private function getInstallUserPermissions() {
		$status = $this->context->getConnection( ITaskContext::CONN_CREATE_DATABASE );
		if ( !$status->isOK() ) {
			return false;
		}
		$conn = $status->getDB();
		$superuser = $this->context->getOption( 'InstallUser' );

		$row = $conn->selectRow( 'pg_catalog.pg_roles', '*',
			[ 'rolname' => $superuser ], __METHOD__ );

		return $row;
	}

	/**
	 * Recursive helper for canCreateObjectsForWebUser().
	 * @param IMaintainableDatabase $conn
	 * @param int $targetMember Role ID of the member to look for
	 * @param int $group Role ID of the group to look for
	 * @param int $maxDepth Maximum recursive search depth
	 * @return bool
	 */
	private function isRoleMember( $conn, $targetMember, $group, $maxDepth ) {
		if ( $targetMember === $group ) {
			// A role is always a member of itself
			return true;
		}
		// Get all members of the given group
		$res = $conn->select( 'pg_catalog.pg_auth_members', [ 'member' ],
			[ 'roleid' => $group ], __METHOD__ );
		foreach ( $res as $row ) {
			if ( $row->member == $targetMember ) {
				// Found target member
				return true;
			}
			// Recursively search each member of the group to see if the target
			// is a member of it, up to the given maximum depth.
			if ( $maxDepth > 0 &&
				$this->isRoleMember( $conn, $targetMember, $row->member, $maxDepth - 1 )
			) {
				// Found member of member
				return true;
			}
		}

		return false;
	}
}
