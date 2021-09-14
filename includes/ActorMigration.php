<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\User\ActorStoreFactory;

/**
 * This is not intended to be a long-term part of MediaWiki; it will be
 * deprecated and removed once actor migration is complete.
 *
 * @since 1.31
 * @since 1.34 Use with 'ar_user', 'img_user', 'oi_user', 'fa_user',
 *  'rc_user', 'log_user', and 'ipb_by' is deprecated. Callers should
 *  reference the corresponding actor fields directly.
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
			]
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
			'actorField' => 'ipb_by_actor'
		]
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
				'The old actor table schema is no longer supported' );
		}
		parent::__construct(
			self::FIELD_INFOS,
			$stage,
			$actorStoreFactory
		);
	}
}
