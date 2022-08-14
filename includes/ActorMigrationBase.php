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

use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserIdentity;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;

/**
 * This abstract base class helps migrate core and extension code to use the
 * actor table.
 *
 * @stable to extend
 * @since 1.37
 */
class ActorMigrationBase {
	/** @var array[] Cache for `self::getJoin()` */
	private $joinCache = [];

	/** @var int One of the SCHEMA_COMPAT_READ_* values */
	private $readStage;

	/** @var int A combination of the SCHEMA_COMPAT_WRITE_* flags */
	private $writeStage;

	/** @var ActorStoreFactory */
	private $actorStoreFactory;

	/** @var array */
	private $fieldInfos;

	/** @var bool */
	private $allowUnknown;

	/**
	 * @param array $fieldInfos An array of associative arrays, giving configuration
	 *   information about fields which are being migrated. Subkeys are:
	 *    - removedVersion: The version in which the field was removed
	 *    - deprecatedVersion: The version in which the field was deprecated
	 *    - component: The component for removedVersion and deprecatedVersion.
	 *      Default: MediaWiki.
	 *    - textField: Override the old text field name. Default {$key}_text.
	 *    - actorField: Override the actor field name. Default {$key}_actor.
	 *    - tempTable: An array of information about the temp table linking
	 *      the old table to the actor table. Default: no temp table is used.
	 *      If set, the following subkeys must be present:
	 *        - table: Temporary table name
	 *        - pk: Temporary table column referring to the main table's primary key
	 *        - field: Temporary table column referring actor.actor_id
	 *        - joinPK: Main table's primary key
	 *        - extra: An array of extra field names to be copied into the
	 *          temp table for indexing. The key is the field name in the temp
	 *          table, and the value is the field name in the main table.
	 *    - formerTempTableVersion: The version of the component in which this
	 *      field used a temp table. If present, getInsertValuesWithTempTable()
	 *      still works, but issues a deprecation warning.
	 *   All subkeys are optional.
	 *
	 * @stable to override
	 * @stable to call
	 *
	 * @param int $stage The migration stage. This is a combination of
	 *   SCHEMA_COMPAT_* flags:
	 *     - SCHEMA_COMPAT_READ_OLD, SCHEMA_COMPAT_WRITE_OLD: Use the old schema,
	 *       with *_user and *_user_text fields.
	 *     - SCHEMA_COMPAT_READ_TEMP, SCHEMA_COMPAT_WRITE_TEMP: Use the new schema,
	 *       with an actor table. Normal tables are joined via a *_actor field,
	 *       whereas temp tables are joined to the actor table via an
	 *       intermediate table.
	 *     - SCHEMA_COMPAT_READ_NEW, SCHEMA_COMPAT_WRITE_NEW: Use the new
	 *       schema. Former temp tables are no longer used, and all relevant
	 *       tables join directly to the actor table.
	 *
	 * @param ActorStoreFactory $actorStoreFactory
	 * @param array $options Array of other options. May contain:
	 *   - allowUnknown: Allow fields not present in $fieldInfos. True by default.
	 */
	public function __construct(
		$fieldInfos,
		$stage,
		ActorStoreFactory $actorStoreFactory,
		$options = []
	) {
		$this->fieldInfos = $fieldInfos;
		$this->allowUnknown = $options['allowUnknown'] ?? true;

		$writeStage = $stage & SCHEMA_COMPAT_WRITE_MASK;
		$readStage = $stage & SCHEMA_COMPAT_READ_MASK;
		if ( $writeStage === 0 ) {
			throw new InvalidArgumentException( '$stage must include a write mode' );
		}
		if ( $readStage === 0 ) {
			throw new InvalidArgumentException( '$stage must include a read mode' );
		}
		if ( !in_array( $readStage,
			[ SCHEMA_COMPAT_READ_OLD, SCHEMA_COMPAT_READ_TEMP, SCHEMA_COMPAT_READ_NEW ] )
		) {
			throw new InvalidArgumentException( 'Cannot read multiple schemas' );
		}
		if ( $readStage === SCHEMA_COMPAT_READ_OLD && !( $writeStage & SCHEMA_COMPAT_WRITE_OLD ) ) {
			throw new InvalidArgumentException( 'Cannot read the old schema without also writing it' );
		}
		if ( $readStage === SCHEMA_COMPAT_READ_TEMP && !( $writeStage & SCHEMA_COMPAT_WRITE_TEMP ) ) {
			throw new InvalidArgumentException( 'Cannot read the temp schema without also writing it' );
		}
		if ( $readStage === SCHEMA_COMPAT_READ_NEW && !( $writeStage & SCHEMA_COMPAT_WRITE_NEW ) ) {
			throw new InvalidArgumentException( 'Cannot read the new schema without also writing it' );
		}
		$this->readStage = $readStage;
		$this->writeStage = $writeStage;

		$this->actorStoreFactory = $actorStoreFactory;
	}

	/**
	 * Get config information about a field.
	 *
	 * @stable to override
	 *
	 * @param string $key
	 * @return array
	 */
	protected function getFieldInfo( $key ) {
		if ( isset( $this->fieldInfos[$key] ) ) {
			return $this->fieldInfos[$key];
		} elseif ( $this->allowUnknown ) {
			return [];
		} else {
			throw new InvalidArgumentException( $this->getInstanceName() . ": unknown key $key" );
		}
	}

	/**
	 * Get a name for this instance to use in error messages
	 *
	 * @stable to override
	 *
	 * @return string
	 * @throws ReflectionException
	 */
	protected function getInstanceName() {
		if ( ( new ReflectionClass( $this ) )->isAnonymous() ) {
			// Mostly for PHPUnit
			return self::class;
		} else {
			return static::class;
		}
	}

	/**
	 * Issue deprecation warning/error as appropriate.
	 *
	 * @internal
	 *
	 * @param string $key
	 */
	protected function checkDeprecation( $key ) {
		$fieldInfo = $this->getFieldInfo( $key );
		if ( isset( $fieldInfo['removedVersion'] ) ) {
			$removedVersion = $fieldInfo['removedVersion'];
			$component = $fieldInfo['component'] ?? 'MediaWiki';
			throw new InvalidArgumentException(
				"Use of {$this->getInstanceName()} for '$key' was removed in $component $removedVersion"
			);
		}
		if ( isset( $fieldInfo['deprecatedVersion'] ) ) {
			$deprecatedVersion = $fieldInfo['deprecatedVersion'];
			$component = $fieldInfo['component'] ?? 'MediaWiki';
			wfDeprecated( "{$this->getInstanceName()} for '$key'", $deprecatedVersion, $component, 3 );
		}
	}

	/**
	 * Return an SQL condition to test if a user field is anonymous
	 * @param string $field Field name or SQL fragment
	 * @return string
	 */
	public function isAnon( $field ) {
		return ( $this->readStage >= SCHEMA_COMPAT_READ_TEMP ) ? "$field IS NULL" : "$field = 0";
	}

	/**
	 * Return an SQL condition to test if a user field is non-anonymous
	 * @param string $field Field name or SQL fragment
	 * @return string
	 */
	public function isNotAnon( $field ) {
		return ( $this->readStage >= SCHEMA_COMPAT_READ_TEMP ) ? "$field IS NOT NULL" : "$field != 0";
	}

	/**
	 * @param string $key A key such as "rev_user" identifying the actor
	 *  field being fetched.
	 * @return string[] [ $text, $actor ]
	 */
	private function getFieldNames( $key ) {
		$fieldInfo = $this->getFieldInfo( $key );
		$textField = $fieldInfo['textField'] ?? $key . '_text';
		$actorField = $fieldInfo['actorField'] ?? substr( $key, 0, -5 ) . '_actor';
		return [ $textField, $actorField ];
	}

	/**
	 * Convenience function for getting temp table config
	 *
	 * @param string $key
	 * @return array|null
	 */
	private function getTempTableInfo( $key ) {
		$fieldInfo = $this->getFieldInfo( $key );
		return $fieldInfo['tempTable'] ?? null;
	}

	/**
	 * Get SELECT fields and joins for the actor key
	 *
	 * @param string $key A key such as "rev_user" identifying the actor
	 *  field being fetched.
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 *  All tables, fields, and joins are aliased, so `+` is safe to use.
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getJoin( $key ) {
		$this->checkDeprecation( $key );

		if ( !isset( $this->joinCache[$key] ) ) {
			$tables = [];
			$fields = [];
			$joins = [];

			list( $text, $actor ) = $this->getFieldNames( $key );

			if ( $this->readStage === SCHEMA_COMPAT_READ_OLD ) {
				$fields[$key] = $key;
				$fields[$text] = $text;
				$fields[$actor] = 'NULL';
			} elseif ( $this->readStage === SCHEMA_COMPAT_READ_TEMP ) {
				$tempTableInfo = $this->getTempTableInfo( $key );
				if ( $tempTableInfo ) {
					$alias = "temp_$key";
					$tables[$alias] = $tempTableInfo['table'];
					$joins[$alias] = [ 'JOIN',
						"{$alias}.{$tempTableInfo['pk']} = {$tempTableInfo['joinPK']}" ];
					$joinField = "{$alias}.{$tempTableInfo['field']}";
				} else {
					$joinField = $actor;
				}

				$alias = "actor_$key";
				$tables[$alias] = 'actor';
				$joins[$alias] = [ 'JOIN', "{$alias}.actor_id = {$joinField}" ];

				$fields[$key] = "{$alias}.actor_user";
				$fields[$text] = "{$alias}.actor_name";
				$fields[$actor] = $joinField;
			} else /* SCHEMA_COMPAT_READ_NEW */ {
				$alias = "actor_$key";
				$tables[$alias] = 'actor';
				$joins[$alias] = [ 'JOIN', "{$alias}.actor_id = {$actor}" ];

				$fields[$key] = "{$alias}.actor_user";
				$fields[$text] = "{$alias}.actor_name";
				$fields[$actor] = $actor;
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
		$this->checkDeprecation( $key );

		if ( $this->getTempTableInfo( $key ) ) {
			throw new InvalidArgumentException( "Must use getInsertValuesWithTempTable() for $key" );
		}

		list( $text, $actor ) = $this->getFieldNames( $key );
		$ret = [];
		if ( $this->writeStage & SCHEMA_COMPAT_WRITE_OLD ) {
			$ret[$key] = $user->getId();
			$ret[$text] = $user->getName();
		}
		if ( $this->writeStage & SCHEMA_COMPAT_WRITE_TEMP
			|| $this->writeStage & SCHEMA_COMPAT_WRITE_NEW
		) {
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
		$this->checkDeprecation( $key );

		$fieldInfo = $this->getFieldInfo( $key );
		$tempTableInfo = $fieldInfo['tempTable'] ?? null;
		if ( isset( $fieldInfo['formerTempTableVersion'] ) ) {
			wfDeprecated( __METHOD__ . " for $key",
				$fieldInfo['formerTempTableVersion'],
				$fieldInfo['component'] ?? 'MediaWiki' );
		} elseif ( !$tempTableInfo ) {
			throw new InvalidArgumentException( "Must use getInsertValues() for $key" );
		}

		list( $text, $actor ) = $this->getFieldNames( $key );
		$ret = [];
		$callback = null;

		if ( $this->writeStage & SCHEMA_COMPAT_WRITE_OLD ) {
			$ret[$key] = $user->getId();
			$ret[$text] = $user->getName();
		}
		if ( $this->writeStage & ( SCHEMA_COMPAT_WRITE_TEMP | SCHEMA_COMPAT_WRITE_NEW ) ) {
			$id = $this->actorStoreFactory
				->getActorNormalization( $dbw->getDomainID() )
				->acquireActorId( $user, $dbw );

			if ( $tempTableInfo ) {
				if ( $this->writeStage & SCHEMA_COMPAT_WRITE_TEMP ) {
					$func = __METHOD__;
					$callback = static function ( $pk, array $extra ) use ( $tempTableInfo, $dbw, $id, $func ) {
						$set = [ $tempTableInfo['field'] => $id ];
						foreach ( $tempTableInfo['extra'] as $to => $from ) {
							if ( !array_key_exists( $from, $extra ) ) {
								throw new InvalidArgumentException( "$func callback: \$extra[$from] is not provided" );
							}
							$set[$to] = $extra[$from];
						}
						$dbw->upsert(
							$tempTableInfo['table'],
							[ $tempTableInfo['pk'] => $pk ] + $set,
							[ [ $tempTableInfo['pk'] ] ],
							$set,
							$func
						);
					};
				}
				if ( $this->writeStage & SCHEMA_COMPAT_WRITE_NEW ) {
					$ret[$actor] = $id;
				}
			} else {
				$ret[$actor] = $id;
			}
		}

		if ( $callback === null ) {
			// Make a validation-only callback if there was temp table info
			if ( $tempTableInfo ) {
				$func = __METHOD__;
				$callback = static function ( $pk, array $extra ) use ( $tempTableInfo, $func ) {
					foreach ( $tempTableInfo['extra'] as $to => $from ) {
						if ( !array_key_exists( $from, $extra ) ) {
							throw new InvalidArgumentException( "$func callback: \$extra[$from] is not provided" );
						}
					}
				};
			} else {
				$callback = static function ( $pk, array $extra ) {
				};
			}
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
	 * @return array With four keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - conds: (string) to include in the `$cond` to `IDatabase->select()` or `SelectQueryBuilder::conds`
	 *   - orconds: (array[]) array of alternatives in case a union of multiple
	 *     queries would be more efficient than a query with OR. May have keys
	 *     'actor', 'userid', 'username'.
	 *     Since 1.32, this is guaranteed to contain just one alternative if
	 *     $users contains a single user.
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 *  All tables and joins are aliased, so `+` is safe to use.
	 * @phan-return array{tables:string[],conds:string,orconds:array[],joins:array}
	 */
	public function getWhere( IDatabase $db, $key, $users, $useId = true ) {
		$this->checkDeprecation( $key );

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
			if ( $useId && $user->isRegistered() ) {
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

		list( $text, $actor ) = $this->getFieldNames( $key );

		// Combine data into conditions to be ORed together
		if ( $this->readStage === SCHEMA_COMPAT_READ_NEW ) {
			if ( $actors ) {
				$conds['newactor'] = $db->makeList( [ $actor => $actors ], IDatabase::LIST_AND );
			}
		} elseif ( $this->readStage === SCHEMA_COMPAT_READ_TEMP ) {
			if ( $actors ) {
				$tempTableInfo = $this->getTempTableInfo( $key );
				if ( $tempTableInfo ) {
					$alias = "temp_$key";
					$tables[$alias] = $tempTableInfo['table'];
					$joins[$alias] = [ 'JOIN',
						"{$alias}.{$tempTableInfo['pk']} = {$tempTableInfo['joinPK']}" ];
					$joinField = "{$alias}.{$tempTableInfo['field']}";
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
