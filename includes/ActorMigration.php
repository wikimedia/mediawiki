<?php
/**
 * Methods to help with the actor table migration
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
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserIdentity;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;

/**
 * This class handles the logic for the actor table migration and should
 * always be used in lieu of directly accessing database tables.
 *
 * This is not intended to be a long-term part of MediaWiki; it will be
 * deprecated and removed once actor migration is complete.
 *
 * @since 1.31
 * @since 1.34 Use with 'ar_user', 'img_user', 'oi_user', 'fa_user',
 *  'rc_user', 'log_user', and 'ipb_by' is deprecated. Callers should
 *  reference the corresponding actor fields directly.
 */
class ActorMigration {

	/**
	 * Constant for extensions to feature-test whether $wgActorTableSchemaMigrationStage
	 * (in MW <1.34) expects MIGRATION_* or SCHEMA_COMPAT_*
	 */
	public const MIGRATION_STAGE_SCHEMA_COMPAT = 1;

	/**
	 * Define fields that use temporary tables for transitional purposes
	 * Array keys are field names, values are arrays with these possible fields:
	 *  - table: Temporary table name
	 *  - pk: Temporary table column referring to the main table's primary key
	 *  - field: Temporary table column referring actor.actor_id
	 *  - joinPK: Main table's primary key
	 */
	protected const TEMP_TABLES = [
		'rev_user' => [
			'table' => 'revision_actor_temp',
			'pk' => 'revactor_rev',
			'field' => 'revactor_actor',
			'joinPK' => 'rev_id',
			'extra' => [
				'revactor_timestamp' => 'rev_timestamp',
				'revactor_page' => 'rev_page',
			],
		],
	];

	/**
	 * Fields that formerly used TEMP_TABLES
	 * Array keys are field names, values are the MediaWiki version when it was removed.
	 */
	protected const FORMER_TEMP_TABLES = [];

	/**
	 * Define fields that are deprecated for use with this class.
	 * Array keys are field names, values are null for soft deprecation
	 *  or a string naming the deprecated version for hard deprecation.
	 */
	protected const DEPRECATED = [
		'ar_user' => null, // 1.34
		'img_user' => null, // 1.34
		'oi_user' => null, // 1.34
		'fa_user' => null, // 1.34
		'rc_user' => null, // 1.34
		'log_user' => null, // 1.34
		'ipb_by' => null, // 1.34
	];

	/**
	 * Define fields that are removed for use with this class.
	 * Array keys are field names, values are the MediaWiki version in which use was removed.
	 */
	protected const REMOVED = [];

	/**
	 * Define fields that use non-standard mapping
	 * Array keys are the user id column name, values are arrays with two
	 *  elements (the user text column name and the actor id column name)
	 */
	protected const SPECIAL_FIELDS = [
		'ipb_by' => [ 'ipb_by_text', 'ipb_by_actor' ],
	];

	/** @var array[] Cache for `self::getJoin()` */
	private $joinCache = [];

	/** @var int Combination of SCHEMA_COMPAT_* constants */
	private $stage;

	/** @var ActorStoreFactory */
	private $actorStoreFactory;

	/**
	 * @param int $stage
	 * @param ActorStoreFactory $actorStoreFactory
	 * @internal
	 */
	public function __construct(
		$stage,
		ActorStoreFactory $actorStoreFactory
	) {
		if ( ( $stage & SCHEMA_COMPAT_WRITE_BOTH ) === 0 ) {
			throw new InvalidArgumentException( '$stage must include a write mode' );
		}
		if ( ( $stage & SCHEMA_COMPAT_READ_BOTH ) === 0 ) {
			throw new InvalidArgumentException( '$stage must include a read mode' );
		}
		if ( ( $stage & SCHEMA_COMPAT_READ_BOTH ) === SCHEMA_COMPAT_READ_BOTH ) {
			throw new InvalidArgumentException( 'Cannot read both schemas' );
		}
		if ( ( $stage & SCHEMA_COMPAT_READ_OLD ) && !( $stage & SCHEMA_COMPAT_WRITE_OLD ) ) {
			throw new InvalidArgumentException( 'Cannot read the old schema without also writing it' );
		}
		if ( ( $stage & SCHEMA_COMPAT_READ_NEW ) && !( $stage & SCHEMA_COMPAT_WRITE_NEW ) ) {
			throw new InvalidArgumentException( 'Cannot read the new schema without also writing it' );
		}

		$this->stage = $stage;
		$this->actorStoreFactory = $actorStoreFactory;
	}

	/**
	 * Static constructor
	 * @return self
	 */
	public static function newMigration() {
		return MediaWikiServices::getInstance()->getActorMigration();
	}

	/**
	 * Issue deprecation warning/error as appropriate.
	 * @param string $key
	 */
	private static function checkDeprecation( $key ) {
		if ( isset( static::REMOVED[$key] ) ) {
			throw new InvalidArgumentException(
				"Use of ActorMigration for '$key' was removed in MediaWiki " . static::REMOVED[$key]
			);
		}
		if ( !empty( static::DEPRECATED[$key] ) ) {
			wfDeprecated( "ActorMigration for '$key'", static::DEPRECATED[$key], false, 3 );
		}
	}

	/**
	 * Return an SQL condition to test if a user field is anonymous
	 * @param string $field Field name or SQL fragment
	 * @return string
	 */
	public function isAnon( $field ) {
		return ( $this->stage & SCHEMA_COMPAT_READ_NEW ) ? "$field IS NULL" : "$field = 0";
	}

	/**
	 * Return an SQL condition to test if a user field is non-anonymous
	 * @param string $field Field name or SQL fragment
	 * @return string
	 */
	public function isNotAnon( $field ) {
		return ( $this->stage & SCHEMA_COMPAT_READ_NEW ) ? "$field IS NOT NULL" : "$field != 0";
	}

	/**
	 * @param string $key A key such as "rev_user" identifying the actor
	 *  field being fetched.
	 * @return string[] [ $text, $actor ]
	 */
	private static function getFieldNames( $key ) {
		return static::SPECIAL_FIELDS[$key] ?? [ $key . '_text', substr( $key, 0, -5 ) . '_actor' ];
	}

	/**
	 * Get SELECT fields and joins for the actor key
	 *
	 * @param string $key A key such as "rev_user" identifying the actor
	 *  field being fetched.
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *  All tables, fields, and joins are aliased, so `+` is safe to use.
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getJoin( $key ) {
		self::checkDeprecation( $key );

		if ( !isset( $this->joinCache[$key] ) ) {
			$tables = [];
			$fields = [];
			$joins = [];

			list( $text, $actor ) = self::getFieldNames( $key );

			if ( $this->stage & SCHEMA_COMPAT_READ_OLD ) {
				$fields[$key] = $key;
				$fields[$text] = $text;
				$fields[$actor] = 'NULL';
			} else {
				if ( isset( static::TEMP_TABLES[$key] ) ) {
					$t = static::TEMP_TABLES[$key];
					$alias = "temp_$key";
					$tables[$alias] = $t['table'];
					$joins[$alias] = [ 'JOIN', "{$alias}.{$t['pk']} = {$t['joinPK']}" ];
					$joinField = "{$alias}.{$t['field']}";
				} else {
					$joinField = $actor;
				}

				$alias = "actor_$key";
				$tables[$alias] = 'actor';
				$joins[$alias] = [ 'JOIN', "{$alias}.actor_id = {$joinField}" ];

				$fields[$key] = "{$alias}.actor_user";
				$fields[$text] = "{$alias}.actor_name";
				$fields[$actor] = $joinField;
			}

			$this->joinCache[$key] = [
				'tables' => $tables,
				'fields' => $fields,
				'joins' => $joins,
			];
		}

		return $this->joinCache[$key];
	}

	/**
	 * Get actor ID from UserIdentity, if it exists
	 *
	 * @since 1.35.0
	 * @deprecated since 1.36. Use ActorStore::findActorId instead.
	 *
	 * @param IDatabase $db
	 * @param UserIdentity $user
	 * @return int|false
	 */
	public function getExistingActorId( IDatabase $db, UserIdentity $user ) {
		wfDeprecated( __METHOD__, '1.36' );
		// Get the correct ActorStore based on the DB passed,
		// and not on the passed $user wiki ID, since before all
		// User object are LOCAL, but the databased passed here can be
		// foreign.
		return $this->actorStoreFactory
			->getActorNormalization( $db->getDomainID() )
			->findActorId( $user, $db );
	}

	/**
	 * Attempt to assign an actor ID to the given user.
	 * If it is already assigned, return the existing ID.
	 *
	 * @since 1.35.0
	 * @deprecated since 1.36. Use ActorStore::acquireActorId instead.
	 *
	 * @param IDatabase $dbw
	 * @param UserIdentity $user
	 *
	 * @return int The new actor ID
	 */
	public function getNewActorId( IDatabase $dbw, UserIdentity $user ) {
		wfDeprecated( __METHOD__, '1.36' );
		// Get the correct ActorStore based on the DB passed,
		// and not on the passed $user wiki ID, since before all
		// User object are LOCAL, but the databased passed here can be
		// foreign.
		return $this->actorStoreFactory
			->getActorNormalization( $dbw->getDomainID() )
			->acquireActorId( $user, $dbw );
	}

	/**
	 * Get UPDATE fields for the actor
	 *
	 * @param IDatabase $dbw Database to use for creating an actor ID, if necessary
	 * @param string $key A key such as "rev_user" identifying the actor
	 *  field being fetched.
	 * @param UserIdentity $user User to set in the update
	 * @return array to merge into `$values` to `IDatabase->update()` or `$a` to `IDatabase->insert()`
	 */
	public function getInsertValues( IDatabase $dbw, $key, UserIdentity $user ) {
		self::checkDeprecation( $key );

		if ( isset( static::TEMP_TABLES[$key] ) ) {
			throw new InvalidArgumentException( "Must use getInsertValuesWithTempTable() for $key" );
		}

		list( $text, $actor ) = self::getFieldNames( $key );
		$ret = [];
		if ( $this->stage & SCHEMA_COMPAT_WRITE_OLD ) {
			$ret[$key] = $user->getId();
			$ret[$text] = $user->getName();
		}
		if ( $this->stage & SCHEMA_COMPAT_WRITE_NEW ) {
			$ret[$actor] = $this->actorStoreFactory
				->getActorNormalization( $dbw->getDomainID() )
				->acquireActorId( $user, $dbw );
		}
		return $ret;
	}

	/**
	 * Get UPDATE fields for the actor
	 *
	 * @param IDatabase $dbw Database to use for creating an actor ID, if necessary
	 * @param string $key A key such as "rev_user" identifying the actor
	 *  field being fetched.
	 * @param UserIdentity $user User to set in the update
	 * @return array with two values:
	 *  - array to merge into `$values` to `IDatabase->update()` or `$a` to `IDatabase->insert()`
	 *  - callback to call with the primary key for the main table insert
	 *    and extra fields needed for the temp table.
	 */
	public function getInsertValuesWithTempTable( IDatabase $dbw, $key, UserIdentity $user ) {
		self::checkDeprecation( $key );

		if ( isset( static::FORMER_TEMP_TABLES[$key] ) ) {
			wfDeprecated( __METHOD__ . " for $key", static::FORMER_TEMP_TABLES[$key] );
		} elseif ( !isset( static::TEMP_TABLES[$key] ) ) {
			throw new InvalidArgumentException( "Must use getInsertValues() for $key" );
		}

		list( $text, $actor ) = self::getFieldNames( $key );
		$ret = [];
		$callback = null;
		if ( $this->stage & SCHEMA_COMPAT_WRITE_OLD ) {
			$ret[$key] = $user->getId();
			$ret[$text] = $user->getName();
		}
		if ( $this->stage & SCHEMA_COMPAT_WRITE_NEW ) {
			$id = $this->actorStoreFactory
				->getActorNormalization( $dbw->getDomainID() )
				->acquireActorId( $user, $dbw );

			if ( isset( static::TEMP_TABLES[$key] ) ) {
				$func = __METHOD__;
				$callback = static function ( $pk, array $extra ) use ( $dbw, $key, $id, $func ) {
					$t = static::TEMP_TABLES[$key];
					$set = [ $t['field'] => $id ];
					foreach ( $t['extra'] as $to => $from ) {
						if ( !array_key_exists( $from, $extra ) ) {
							throw new InvalidArgumentException( "$func callback: \$extra[$from] is not provided" );
						}
						$set[$to] = $extra[$from];
					}
					$dbw->upsert(
						$t['table'],
						[ $t['pk'] => $pk ] + $set,
						[ [ $t['pk'] ] ],
						$set,
						$func
					);
				};
			} else {
				$ret[$actor] = $id;
				$callback = static function ( $pk, array $extra ) {
				};
			}
		} elseif ( isset( static::TEMP_TABLES[$key] ) ) {
			$func = __METHOD__;
			$callback = static function ( $pk, array $extra ) use ( $key, $func ) {
				$t = static::TEMP_TABLES[$key];
				foreach ( $t['extra'] as $to => $from ) {
					if ( !array_key_exists( $from, $extra ) ) {
						throw new InvalidArgumentException( "$func callback: \$extra[$from] is not provided" );
					}
				}
			};
		} else {
			$callback = static function ( $pk, array $extra ) {
			};
		}
		return [ $ret, $callback ];
	}

	/**
	 * Get WHERE condition for the actor
	 *
	 * @param IDatabase $db Database to use for quoting and list-making
	 * @param string $key A key such as "rev_user" identifying the actor
	 *  field being fetched.
	 * @param UserIdentity|UserIdentity[]|null|false $users Users to test for.
	 *  Passing null, false, or the empty array will return 'conds' that never match,
	 *  and an empty array for 'orconds'.
	 * @param bool $useId If false, don't try to query by the user ID.
	 *  Intended for use with rc_user since it has an index on
	 *  (rc_user_text,rc_timestamp) but not (rc_user,rc_timestamp).
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - conds: (string) to include in the `$cond` to `IDatabase->select()`
	 *   - orconds: (array[]) array of alternatives in case a union of multiple
	 *     queries would be more efficient than a query with OR. May have keys
	 *     'actor', 'userid', 'username'.
	 *     Since 1.32, this is guaranteed to contain just one alternative if
	 *     $users contains a single user.
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *  All tables and joins are aliased, so `+` is safe to use.
	 */
	public function getWhere( IDatabase $db, $key, $users, $useId = true ) {
		self::checkDeprecation( $key );

		$tables = [];
		$conds = [];
		$joins = [];

		if ( $users instanceof UserIdentity ) {
			$users = [ $users ];
		} elseif ( $users === null || $users === false ) {
			// DWIM
			$users = [];
		} elseif ( !is_array( $users ) ) {
			$what = is_object( $users ) ? get_class( $users ) : gettype( $users );
			throw new InvalidArgumentException(
				__METHOD__ . ": Value for \$users must be a UserIdentity or array, got $what"
			);
		}

		// Get information about all the passed users
		$ids = [];
		$names = [];
		$actors = [];
		foreach ( $users as $user ) {
			if ( $useId && $user->getId() ) {
				$ids[] = $user->getId();
			} else {
				// make sure to use normalized form of IP for anonymous users
				$names[] = IPUtils::sanitizeIP( $user->getName() );
			}
			$actorId = $this->actorStoreFactory
				->getActorNormalization( $db->getDomainID() )
				->findActorId( $user, $db );

			if ( $actorId ) {
				$actors[] = $actorId;
			}
		}

		list( $text, $actor ) = self::getFieldNames( $key );

		// Combine data into conditions to be ORed together
		if ( $this->stage & SCHEMA_COMPAT_READ_NEW ) {
			if ( $actors ) {
				if ( isset( static::TEMP_TABLES[$key] ) ) {
					$t = static::TEMP_TABLES[$key];
					$alias = "temp_$key";
					$tables[$alias] = $t['table'];
					$joins[$alias] = [ 'JOIN', "{$alias}.{$t['pk']} = {$t['joinPK']}" ];
					$joinField = "{$alias}.{$t['field']}";
				} else {
					$joinField = $actor;
				}
				$conds['actor'] = $db->makeList( [ $joinField => $actors ], IDatabase::LIST_AND );
			}
		} else {
			if ( $ids ) {
				$conds['userid'] = $db->makeList( [ $key => $ids ], IDatabase::LIST_AND );
			}
			if ( $names ) {
				$conds['username'] = $db->makeList( [ $text => $names ], IDatabase::LIST_AND );
			}
		}

		return [
			'tables' => $tables,
			'conds' => $conds ? $db->makeList( array_values( $conds ), IDatabase::LIST_OR ) : '1=0',
			'orconds' => $conds,
			'joins' => $joins,
		];
	}
}
