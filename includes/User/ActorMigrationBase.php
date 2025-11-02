<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Help migrate core and extension code with the actor table migration.
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

	protected ActorStoreFactory $actorStoreFactory;

	/** @var array */
	private $fieldInfos;

	private bool $allowUnknown;

	private bool $forImport = false;

	/**
	 * @param array $fieldInfos An array of associative arrays, giving configuration
	 *   information about fields which are being migrated. Subkeys are:
	 *    - removedVersion: The version in which the field was removed
	 *    - deprecatedVersion: The version in which the field was deprecated
	 *    - component: The component for removedVersion and deprecatedVersion.
	 *      Default: MediaWiki.
	 *    - textField: Override the old text field name. Default {$key}_text.
	 *    - actorField: Override the actor field name. Default {$key}_actor.
	 *   All subkeys are optional.
	 *
	 * @stable to override
	 * @stable to call
	 *
	 * @param int $stage The migration stage. This is a combination of
	 *   SCHEMA_COMPAT_* flags:
	 *     - SCHEMA_COMPAT_READ_OLD, SCHEMA_COMPAT_WRITE_OLD: Use the old schema,
	 *       with *_user and *_user_text fields.
	 *     - SCHEMA_COMPAT_READ_NEW, SCHEMA_COMPAT_WRITE_NEW: Use the new
	 *       schema. All relevant tables join directly to the actor table.
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
		if ( !in_array( $readStage, [ SCHEMA_COMPAT_READ_OLD, SCHEMA_COMPAT_READ_NEW ] ) ) {
			throw new InvalidArgumentException( 'Cannot read multiple schemas' );
		}
		if ( $readStage === SCHEMA_COMPAT_READ_OLD && !( $writeStage & SCHEMA_COMPAT_WRITE_OLD ) ) {
			throw new InvalidArgumentException( 'Cannot read the old schema without also writing it' );
		}
		if ( $readStage === SCHEMA_COMPAT_READ_NEW && !( $writeStage & SCHEMA_COMPAT_WRITE_NEW ) ) {
			throw new InvalidArgumentException( 'Cannot read the new schema without also writing it' );
		}
		$this->readStage = $readStage;
		$this->writeStage = $writeStage;

		$this->actorStoreFactory = $actorStoreFactory;
	}

	/**
	 * Get an instance that allows IP actor creation
	 * @return self
	 */
	public static function newMigrationForImport() {
		throw new LogicException( __METHOD__ . " must be overridden" );
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
	 * @throws \ReflectionException
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
		return ( $this->readStage & SCHEMA_COMPAT_READ_NEW ) ? "$field IS NULL" : "$field = 0";
	}

	/**
	 * Return an SQL condition to test if a user field is non-anonymous
	 * @param string $field Field name or SQL fragment
	 * @return string
	 */
	public function isNotAnon( $field ) {
		return ( $this->readStage & SCHEMA_COMPAT_READ_NEW ) ? "$field IS NOT NULL" : "$field != 0";
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

			[ $text, $actor ] = $this->getFieldNames( $key );

			if ( $this->readStage === SCHEMA_COMPAT_READ_OLD ) {
				$fields[$key] = $key;
				$fields[$text] = $text;
				$fields[$actor] = 'NULL';
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

		[ $text, $actor ] = $this->getFieldNames( $key );
		$ret = [];
		if ( $this->writeStage & SCHEMA_COMPAT_WRITE_OLD ) {
			$ret[$key] = $user->getId();
			$ret[$text] = $user->getName();
		}
		if ( $this->writeStage & SCHEMA_COMPAT_WRITE_NEW ) {
			$ret[$actor] = $this->getActorNormalization( $dbw->getDomainID() )
				->acquireActorId( $user, $dbw );
		}
		return $ret;
	}

	/**
	 * Get WHERE condition for the actor
	 *
	 * @param IReadableDatabase $db Database to use for quoting and list-making
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
	 *   - orconds: (string[]) array of alternatives in case a union of multiple
	 *     queries would be more efficient than a query with OR. May have keys
	 *     'actor', 'userid', 'username'.
	 *     Since 1.32, this is guaranteed to contain just one alternative if
	 *     $users contains a single user.
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 *  All tables and joins are aliased, so `+` is safe to use.
	 * @phan-return array{tables:string[],conds:string,orconds:string[],joins:array}
	 */
	public function getWhere( IReadableDatabase $db, $key, $users, $useId = true ) {
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
			$what = get_debug_type( $users );
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
			$actorId = $this->getActorNormalization( $db->getDomainID() )
				->findActorId( $user, $db );

			if ( $actorId ) {
				$actors[] = $actorId;
			}
		}

		[ $text, $actor ] = $this->getFieldNames( $key );

		// Combine data into conditions to be ORed together
		if ( $this->readStage === SCHEMA_COMPAT_READ_NEW ) {
			if ( $actors ) {
				$conds['newactor'] = $db->makeList( [ $actor => $actors ], IDatabase::LIST_AND );
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

	/**
	 * @internal For use immediately after construction only
	 * @param bool $forImport
	 */
	public function setForImport( bool $forImport ): void {
		$this->forImport = $forImport;
	}

	/**
	 * @param string $domainId
	 * @return ActorNormalization
	 */
	protected function getActorNormalization( $domainId ): ActorNormalization {
		if ( $this->forImport ) {
			return $this->actorStoreFactory->getActorNormalizationForImport( $domainId );
		} else {
			return $this->actorStoreFactory->getActorNormalization( $domainId );
		}
	}
}
