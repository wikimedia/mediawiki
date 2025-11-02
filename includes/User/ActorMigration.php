<?php

namespace MediaWiki\User;

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * This is not intended to be a long-term part of MediaWiki; it will be
 * deprecated and removed once actor migration is complete.
 *
 * @since 1.31
 * @since 1.34 Use with 'ar_user', 'img_user', 'oi_user', 'fa_user',
 *  'rc_user', 'log_user', and 'ipb_by' is deprecated. Callers should
 *  reference the corresponding actor fields directly.
 * @deprecated since 1.39
 */
class ActorMigration extends ActorMigrationBase {

	/**
	 * Field information
	 * @see ActorMigrationBase::getFieldInfo()
	 */
	public const FIELD_INFOS = [
		// Deprecated since 1.39
		'rev_user' => [],
	];

	/**
	 * Static constructor
	 * @return self
	 */
	public static function newMigration() {
		return MediaWikiServices::getInstance()->getActorMigration();
	}

	/**
	 * Static constructor
	 * @return self
	 */
	public static function newMigrationForImport() {
		$migration = new self(
			MediaWikiServices::getInstance()->getActorStoreFactory()
		);
		$migration->setForImport( true );
		return $migration;
	}

	/**
	 * @internal
	 *
	 * @param ActorStoreFactory $actorStoreFactory
	 */
	public function __construct( ActorStoreFactory $actorStoreFactory ) {
		parent::__construct(
			self::FIELD_INFOS,
			SCHEMA_COMPAT_NEW,
			$actorStoreFactory
		);
	}

	/**
	 * @inheritDoc
	 * @deprecated since 1.39 Use `{table} JOIN actor ON {table_prefix}_actor = actor_id`
	 *   E.g. for key=rev_user, use `revision JOIN actor ON rev_actor = actor_id`
	 */
	public function getJoin( $key ) {
		return parent::getJoin( $key );
	}

	/**
	 * @inheritDoc
	 * @deprecated since 1.39 Use `{table_prefix}_actor IN ({list of actor IDs})`.
	 *   E.g. for key=rev_user, use `rev_actor IN ({list of actor IDs})`.
	 *   Use `MediaWikiServices::getInstance()->getActorNormalization()
	 *   ->findActorId( $user, $db )` to get the actor ID for a given user.
	 */
	public function getWhere( IReadableDatabase $db, $key, $users, $useId = true ) {
		return parent::getWhere( $db, $key, $users, $useId );
	}

	/**
	 * @inheritDoc
	 * @deprecated since 1.39 Use `[ '{table_prefix}_actor' => MediaWikiServices::getInstance()
	 *   ->getActorNormalization()->acquireActorId( $user, $dbw ) ]`
	 *   E.g. for key=log_user, use `[ 'log_actor' => ... ]`
	 */
	public function getInsertValues( IDatabase $dbw, $key, UserIdentity $user ) {
		return parent::getInsertValues( $dbw, $key, $user );
	}

}
