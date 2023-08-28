<?php

namespace MediaWiki\User;

use InvalidArgumentException;
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
	 * Constant for extensions to feature-test whether $wgActorTableSchemaMigrationStage
	 * (in MW <1.34) expects MIGRATION_* or SCHEMA_COMPAT_*
	 */
	public const MIGRATION_STAGE_SCHEMA_COMPAT = 1;

	/**
	 * Field information
	 * @see ActorMigrationBase::getFieldInfo()
	 */
	public const FIELD_INFOS = [
		// Deprecated since 1.39
		'rev_user' => [
			'tempTable' => [
				'table' => 'revision_actor_temp',
				'pk' => 'revactor_rev',
				'field' => 'revactor_actor',
				'joinPK' => 'rev_id',
				'extra' => [
					'revactor_timestamp' => 'rev_timestamp',
					'revactor_page' => 'rev_page',
				],
			],
		],

		// Deprecated since 1.34
		'ar_user' => [
			'deprecatedVersion' => '1.37',
		],
		// Deprecated since 1.34
		'img_user' => [
			'deprecatedVersion' => '1.37',
		],
		// Deprecated since 1.34
		'oi_user' => [
			'deprecatedVersion' => '1.37',
		],
		// Deprecated since 1.34
		'fa_user' => [
			'deprecatedVersion' => '1.37',
		],
		// Deprecated since 1.34
		'rc_user' => [
			'deprecatedVersion' => '1.37',
		],
		// Deprecated since 1.34
		'log_user' => [
			'deprecatedVersion' => '1.37',
		],
		// Deprecated since 1.34
		'ipb_by' => [
			'deprecatedVersion' => '1.37',
			'textField' => 'ipb_by_text',
			'actorField' => 'ipb_by_actor',
		],
	];

	/**
	 * Static constructor
	 * @return self
	 */
	public static function newMigration() {
		return MediaWikiServices::getInstance()->getActorMigration();
	}

	/**
	 * @internal
	 *
	 * @param int $stage
	 * @param ActorStoreFactory $actorStoreFactory
	 */
	public function __construct(
		$stage,
		ActorStoreFactory $actorStoreFactory
	) {
		if ( $stage & SCHEMA_COMPAT_OLD ) {
			throw new InvalidArgumentException(
				'The old actor table schema is no longer supported'
			);
		}
		parent::__construct(
			self::FIELD_INFOS,
			$stage,
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

	/**
	 * @inheritDoc
	 * @deprecated since 1.39 Use same replacement as getInsertValues().
	 */
	public function getInsertValuesWithTempTable( IDatabase $dbw, $key, UserIdentity $user ) {
		return parent::getInsertValuesWithTempTable( $dbw, $key, $user );
	}

}

/**
 * @deprecated since 1.40
 */
class_alias( ActorMigration::class, 'ActorMigration' );
