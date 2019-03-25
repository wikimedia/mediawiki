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
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDatabase;

/**
 * This class handles the logic for the actor table migration.
 *
 * This is not intended to be a long-term part of MediaWiki; it will be
 * deprecated and removed along with $wgActorTableSchemaMigrationStage.
 *
 * @since 1.31
 */
class ActorMigration {

	/**
	 * Constant for extensions to feature-test whether $wgActorTableSchemaMigrationStage
	 * expects MIGRATION_* or SCHEMA_COMPAT_*
	 */
	const MIGRATION_STAGE_SCHEMA_COMPAT = 1;

	/**
	 * Define fields that use temporary tables for transitional purposes
	 * @var array Keys are '$key', values are arrays with four fields:
	 *  - table: Temporary table name
	 *  - pk: Temporary table column referring to the main table's primary key
	 *  - field: Temporary table column referring actor.actor_id
	 *  - joinPK: Main table's primary key
	 */
	private static $tempTables = [
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
	 * Fields that formerly used $tempTables
	 * @var array Key is '$key', value is the MediaWiki version in which it was
	 *  removed from $tempTables.
	 */
	private static $formerTempTables = [];

	/**
	 * Define fields that use non-standard mapping
	 * @var array Keys are the user id column name, values are arrays with two
	 *  elements (the user text column name and the actor id column name)
	 */
	private static $specialFields = [
		'ipb_by' => [ 'ipb_by_text', 'ipb_by_actor' ],
	];

	/** @var array|null Cache for `self::getJoin()` */
	private $joinCache = null;

	/** @var int Combination of SCHEMA_COMPAT_* constants */
	private $stage;

	/** @private */
	public function __construct( $stage ) {
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
	}

	/**
	 * Static constructor
	 * @return ActorMigration
	 */
	public static function newMigration() {
		return MediaWikiServices::getInstance()->getActorMigration();
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
		return self::$specialFields[$key] ?? [ $key . '_text', substr( $key, 0, -5 ) . '_actor' ];
	}

	/**
	 * Get SELECT fields and joins for the actor key
	 *
	 * @param string $key A key such as "rev_user" identifying the actor
	 *  field being fetched.
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *  All tables, fields, and joins are aliased, so `+` is safe to use.
	 */
	public function getJoin( $key ) {
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
				if ( isset( self::$tempTables[$key] ) ) {
					$t = self::$tempTables[$key];
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
	 * Get UPDATE fields for the actor
	 *
	 * @param IDatabase $dbw Database to use for creating an actor ID, if necessary
	 * @param string $key A key such as "rev_user" identifying the actor
	 *  field being fetched.
	 * @param UserIdentity $user User to set in the update
	 * @return array to merge into `$values` to `IDatabase->update()` or `$a` to `IDatabase->insert()`
	 */
	public function getInsertValues( IDatabase $dbw, $key, UserIdentity $user ) {
		if ( isset( self::$tempTables[$key] ) ) {
			throw new InvalidArgumentException( "Must use getInsertValuesWithTempTable() for $key" );
		}

		list( $text, $actor ) = self::getFieldNames( $key );
		$ret = [];
		if ( $this->stage & SCHEMA_COMPAT_WRITE_OLD ) {
			$ret[$key] = $user->getId();
			$ret[$text] = $user->getName();
		}
		if ( $this->stage & SCHEMA_COMPAT_WRITE_NEW ) {
			// We need to be able to assign an actor ID if none exists
			if ( !$user instanceof User && !$user->getActorId() ) {
				$user = User::newFromAnyId( $user->getId(), $user->getName(), null );
			}
			$ret[$actor] = $user->getActorId( $dbw );
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
		if ( isset( self::$formerTempTables[$key] ) ) {
			wfDeprecated( __METHOD__ . " for $key", self::$formerTempTables[$key] );
		} elseif ( !isset( self::$tempTables[$key] ) ) {
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
			// We need to be able to assign an actor ID if none exists
			if ( !$user instanceof User && !$user->getActorId() ) {
				$user = User::newFromAnyId( $user->getId(), $user->getName(), null );
			}
			$id = $user->getActorId( $dbw );

			if ( isset( self::$tempTables[$key] ) ) {
				$func = __METHOD__;
				$callback = function ( $pk, array $extra ) use ( $dbw, $key, $id, $func ) {
					$t = self::$tempTables[$key];
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
						[ $t['pk'] ],
						$set,
						$func
					);
				};
			} else {
				$ret[$actor] = $id;
				$callback = function ( $pk, array $extra ) {
				};
			}
		} elseif ( isset( self::$tempTables[$key] ) ) {
			$func = __METHOD__;
			$callback = function ( $pk, array $extra ) use ( $key, $func ) {
				$t = self::$tempTables[$key];
				foreach ( $t['extra'] as $to => $from ) {
					if ( !array_key_exists( $from, $extra ) ) {
						throw new InvalidArgumentException( "$func callback: \$extra[$from] is not provided" );
					}
				}
			};
		} else {
			$callback = function ( $pk, array $extra ) {
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
	 * @param UserIdentity|UserIdentity[] $users Users to test for
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
		$tables = [];
		$conds = [];
		$joins = [];

		if ( $users instanceof UserIdentity ) {
			$users = [ $users ];
		}

		// Get information about all the passed users
		$ids = [];
		$names = [];
		$actors = [];
		foreach ( $users as $user ) {
			if ( $useId && $user->getId() ) {
				$ids[] = $user->getId();
			} else {
				$names[] = $user->getName();
			}
			$actorId = $user->getActorId();
			if ( $actorId ) {
				$actors[] = $actorId;
			}
		}

		list( $text, $actor ) = self::getFieldNames( $key );

		// Combine data into conditions to be ORed together
		if ( $this->stage & SCHEMA_COMPAT_READ_NEW ) {
			if ( $actors ) {
				if ( isset( self::$tempTables[$key] ) ) {
					$t = self::$tempTables[$key];
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
