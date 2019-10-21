<?php
/**
 * Manage storage of comments in the database
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
use Wikimedia\Rdbms\IDatabase;

/**
 * CommentStore handles storage of comments (edit summaries, log reasons, etc)
 * in the database.
 * @since 1.30
 */
class CommentStore {

	/**
	 * Maximum length of a comment in UTF-8 characters. Longer comments will be truncated.
	 * @note This must be at least 255 and not greater than floor( MAX_COMMENT_LENGTH / 4 ).
	 */
	const COMMENT_CHARACTER_LIMIT = 500;

	/**
	 * Maximum length of a comment in bytes. Longer comments will be truncated.
	 * @note This value is determined by the size of the underlying database field,
	 *  currently BLOB in MySQL/MariaDB.
	 */
	const MAX_COMMENT_LENGTH = 65535;

	/**
	 * Maximum length of serialized data in bytes. Longer data will result in an exception.
	 * @note This value is determined by the size of the underlying database field,
	 *  currently BLOB in MySQL/MariaDB.
	 */
	const MAX_DATA_LENGTH = 65535;

	/**
	 * Define fields that use temporary tables for transitional purposes
	 * @var array Keys are '$key', values are arrays with these possible fields:
	 *  - table: Temporary table name
	 *  - pk: Temporary table column referring to the main table's primary key
	 *  - field: Temporary table column referring comment.comment_id
	 *  - joinPK: Main table's primary key
	 *  - stage: Migration stage
	 *  - deprecatedIn: Version when using insertWithTempTable() was deprecated
	 */
	protected $tempTables = [
		'rev_comment' => [
			'table' => 'revision_comment_temp',
			'pk' => 'revcomment_rev',
			'field' => 'revcomment_comment_id',
			'joinPK' => 'rev_id',
			'stage' => MIGRATION_OLD,
			'deprecatedIn' => null,
		],
		'img_description' => [
			'stage' => MIGRATION_NEW,
			'deprecatedIn' => '1.32',
		],
	];

	/**
	 * @since 1.30
	 * @deprecated in 1.31
	 * @var string|null
	 */
	protected $key = null;

	/**
	 * @var int One of the MIGRATION_* constants, or an appropriate combination
	 *  of SCHEMA_COMPAT_* constants.
	 * @todo Deprecate and remove once extensions seem unlikely to need to use
	 *  it for migration anymore.
	 */
	protected $stage;

	/** @var array[] Cache for `self::getJoin()` */
	protected $joinCache = [];

	/** @var Language Language to use for comment truncation */
	protected $lang;

	/**
	 * @param Language $lang Language to use for comment truncation. Defaults
	 *  to content language.
	 * @param int $stage One of the MIGRATION_* constants, or an appropriate
	 *  combination of SCHEMA_COMPAT_* constants. Always MIGRATION_NEW for
	 *  MediaWiki core since 1.33.
	 */
	public function __construct( Language $lang, $stage ) {
		if ( ( $stage & SCHEMA_COMPAT_WRITE_BOTH ) === 0 ) {
			throw new InvalidArgumentException( '$stage must include a write mode' );
		}
		if ( ( $stage & SCHEMA_COMPAT_READ_BOTH ) === 0 ) {
			throw new InvalidArgumentException( '$stage must include a read mode' );
		}

		$this->stage = $stage;
		$this->lang = $lang;
	}

	/**
	 * Static constructor for easier chaining
	 * @deprecated in 1.31 Should not be constructed with a $key, use CommentStore::getStore
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @return CommentStore
	 */
	public static function newKey( $key ) {
		wfDeprecated( __METHOD__, '1.31' );
		$store = new CommentStore(
			MediaWikiServices::getInstance()->getContentLanguage(), MIGRATION_NEW
		);
		$store->key = $key;
		return $store;
	}

	/**
	 * @since 1.31
	 * @deprecated in 1.31 Use DI to inject a CommentStore instance into your class.
	 * @return CommentStore
	 */
	public static function getStore() {
		return MediaWikiServices::getInstance()->getCommentStore();
	}

	/**
	 * Compat method allowing use of self::newKey until removed.
	 * @param string|null $methodKey
	 * @throws InvalidArgumentException
	 * @return string
	 */
	private function getKey( $methodKey = null ) {
		$key = $this->key ?? $methodKey;
		if ( $key === null ) {
			// @codeCoverageIgnoreStart
			throw new InvalidArgumentException( '$key should not be null' );
			// @codeCoverageIgnoreEnd
		}
		return $key;
	}

	/**
	 * Get SELECT fields for the comment key
	 *
	 * Each resulting row should be passed to `self::getCommentLegacy()` to get the
	 * actual comment.
	 *
	 * @note Use of this method may require a subsequent database query to
	 *  actually fetch the comment. If possible, use `self::getJoin()` instead.
	 *
	 * @since 1.30
	 * @since 1.31 Method signature changed, $key parameter added (with deprecated back compat)
	 * @param string|null $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @return string[] to include in the `$vars` to `IDatabase->select()`. All
	 *  fields are aliased, so `+` is safe to use.
	 */
	public function getFields( $key = null ) {
		$key = $this->getKey( $key );
		$fields = [];
		if ( ( $this->stage & SCHEMA_COMPAT_READ_BOTH ) === SCHEMA_COMPAT_READ_OLD ) {
			$fields["{$key}_text"] = $key;
			$fields["{$key}_data"] = 'NULL';
			$fields["{$key}_cid"] = 'NULL';
		} else { // READ_BOTH or READ_NEW
			if ( $this->stage & SCHEMA_COMPAT_READ_OLD ) {
				$fields["{$key}_old"] = $key;
			}

			$tempTableStage = isset( $this->tempTables[$key] )
				? $this->tempTables[$key]['stage'] : MIGRATION_NEW;
			if ( $tempTableStage & SCHEMA_COMPAT_READ_OLD ) {
				$fields["{$key}_pk"] = $this->tempTables[$key]['joinPK'];
			}
			if ( $tempTableStage & SCHEMA_COMPAT_READ_NEW ) {
				$fields["{$key}_id"] = "{$key}_id";
			}
		}
		return $fields;
	}

	/**
	 * Get SELECT fields and joins for the comment key
	 *
	 * Each resulting row should be passed to `self::getComment()` to get the
	 * actual comment.
	 *
	 * @since 1.30
	 * @since 1.31 Method signature changed, $key parameter added (with deprecated back compat)
	 * @param string|null $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *  All tables, fields, and joins are aliased, so `+` is safe to use.
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getJoin( $key = null ) {
		$key = $this->getKey( $key );
		if ( !array_key_exists( $key, $this->joinCache ) ) {
			$tables = [];
			$fields = [];
			$joins = [];

			if ( ( $this->stage & SCHEMA_COMPAT_READ_BOTH ) === SCHEMA_COMPAT_READ_OLD ) {
				$fields["{$key}_text"] = $key;
				$fields["{$key}_data"] = 'NULL';
				$fields["{$key}_cid"] = 'NULL';
			} else { // READ_BOTH or READ_NEW
				$join = ( $this->stage & SCHEMA_COMPAT_READ_OLD ) ? 'LEFT JOIN' : 'JOIN';

				$tempTableStage = isset( $this->tempTables[$key] )
					? $this->tempTables[$key]['stage'] : MIGRATION_NEW;
				if ( $tempTableStage & SCHEMA_COMPAT_READ_OLD ) {
					$t = $this->tempTables[$key];
					$alias = "temp_$key";
					$tables[$alias] = $t['table'];
					$joins[$alias] = [ $join, "{$alias}.{$t['pk']} = {$t['joinPK']}" ];
					if ( ( $tempTableStage & SCHEMA_COMPAT_READ_BOTH ) === SCHEMA_COMPAT_READ_OLD ) {
						$joinField = "{$alias}.{$t['field']}";
					} else {
						// Nothing hits this code path for now, but will in the future when we set
						// $this->tempTables['rev_comment']['stage'] to MIGRATION_WRITE_NEW while
						// merging revision_comment_temp into revision.
						// @codeCoverageIgnoreStart
						$joins[$alias][0] = 'LEFT JOIN';
						$joinField = "(CASE WHEN {$key}_id != 0 THEN {$key}_id ELSE {$alias}.{$t['field']} END)";
						throw new LogicException( 'Nothing should reach this code path at this time' );
						// @codeCoverageIgnoreEnd
					}
				} else {
					$joinField = "{$key}_id";
				}

				$alias = "comment_$key";
				$tables[$alias] = 'comment';
				$joins[$alias] = [ $join, "{$alias}.comment_id = {$joinField}" ];

				if ( ( $this->stage & SCHEMA_COMPAT_READ_BOTH ) === SCHEMA_COMPAT_READ_NEW ) {
					$fields["{$key}_text"] = "{$alias}.comment_text";
				} else {
					$fields["{$key}_text"] = "COALESCE( {$alias}.comment_text, $key )";
				}
				$fields["{$key}_data"] = "{$alias}.comment_data";
				$fields["{$key}_cid"] = "{$alias}.comment_id";
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
	 * Extract the comment from a row
	 *
	 * Shared implementation for getComment() and getCommentLegacy()
	 *
	 * @param IDatabase|null $db Database handle for getCommentLegacy(), or null for getComment()
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @param object|array $row
	 * @param bool $fallback
	 * @return CommentStoreComment
	 */
	private function getCommentInternal( IDatabase $db = null, $key, $row, $fallback = false ) {
		$row = (array)$row;
		if ( array_key_exists( "{$key}_text", $row ) && array_key_exists( "{$key}_data", $row ) ) {
			$cid = $row["{$key}_cid"] ?? null;
			$text = $row["{$key}_text"];
			$data = $row["{$key}_data"];
		} elseif ( ( $this->stage & SCHEMA_COMPAT_READ_BOTH ) === SCHEMA_COMPAT_READ_OLD ) {
			$cid = null;
			if ( $fallback && isset( $row[$key] ) ) {
				wfLogWarning( "Using deprecated fallback handling for comment $key" );
				$text = $row[$key];
			} else {
				wfLogWarning(
					"Missing {$key}_text and {$key}_data fields in row with MIGRATION_OLD / READ_OLD"
				);
				$text = '';
			}
			$data = null;
		} else {
			$tempTableStage = isset( $this->tempTables[$key] )
				? $this->tempTables[$key]['stage'] : MIGRATION_NEW;
			$row2 = null;
			if ( ( $tempTableStage & SCHEMA_COMPAT_READ_NEW ) && array_key_exists( "{$key}_id", $row ) ) {
				if ( !$db ) {
					throw new InvalidArgumentException(
						"\$row does not contain fields needed for comment $key and getComment(), but "
						. "does have fields for getCommentLegacy()"
					);
				}
				$id = $row["{$key}_id"];
				$row2 = $db->selectRow(
					'comment',
					[ 'comment_id', 'comment_text', 'comment_data' ],
					[ 'comment_id' => $id ],
					__METHOD__
				);
			}
			if ( !$row2 && ( $tempTableStage & SCHEMA_COMPAT_READ_OLD ) &&
				array_key_exists( "{$key}_pk", $row )
			) {
				if ( !$db ) {
					throw new InvalidArgumentException(
						"\$row does not contain fields needed for comment $key and getComment(), but "
						. "does have fields for getCommentLegacy()"
					);
				}
				$t = $this->tempTables[$key];
				$id = $row["{$key}_pk"];
				$row2 = $db->selectRow(
					[ $t['table'], 'comment' ],
					[ 'comment_id', 'comment_text', 'comment_data' ],
					[ $t['pk'] => $id ],
					__METHOD__,
					[],
					[ 'comment' => [ 'JOIN', [ "comment_id = {$t['field']}" ] ] ]
				);
			}
			if ( $row2 === null && $fallback && isset( $row[$key] ) ) {
				wfLogWarning( "Using deprecated fallback handling for comment $key" );
				$row2 = (object)[ 'comment_text' => $row[$key], 'comment_data' => null ];
			}
			if ( $row2 === null ) {
				throw new InvalidArgumentException( "\$row does not contain fields needed for comment $key" );
			}

			if ( $row2 ) {
				$cid = $row2->comment_id;
				$text = $row2->comment_text;
				$data = $row2->comment_data;
			} elseif ( ( $this->stage & SCHEMA_COMPAT_READ_OLD ) &&
				array_key_exists( "{$key}_old", $row )
			) {
				$cid = null;
				$text = $row["{$key}_old"];
				$data = null;
			} else {
				// @codeCoverageIgnoreStart
				wfLogWarning( "Missing comment row for $key, id=$id" );
				$cid = null;
				$text = '';
				$data = null;
				// @codeCoverageIgnoreEnd
			}
		}

		$msg = null;
		if ( $data !== null ) {
			$data = FormatJson::decode( $data, true );
			if ( !is_array( $data ) ) {
				// @codeCoverageIgnoreStart
				wfLogWarning( "Invalid JSON object in comment: $data" );
				$data = null;
				// @codeCoverageIgnoreEnd
			} else {
				if ( isset( $data['_message'] ) ) {
					$msg = self::decodeMessage( $data['_message'] )
						->setInterfaceMessageFlag( true );
				}
				if ( !empty( $data['_null'] ) ) {
					$data = null;
				} else {
					foreach ( $data as $k => $v ) {
						if ( substr( $k, 0, 1 ) === '_' ) {
							unset( $data[$k] );
						}
					}
				}
			}
		}

		return new CommentStoreComment( $cid, $text, $msg, $data );
	}

	/**
	 * Extract the comment from a row
	 *
	 * Use `self::getJoin()` to ensure the row contains the needed data.
	 *
	 * If you need to fake a comment in a row for some reason, set fields
	 * `{$key}_text` (string) and `{$key}_data` (JSON string or null).
	 *
	 * @since 1.30
	 * @since 1.31 Method signature changed, $key parameter added (with deprecated back compat)
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @param object|array|null $row Result row.
	 * @param bool $fallback If true, fall back as well as possible instead of throwing an exception.
	 * @return CommentStoreComment
	 */
	public function getComment( $key, $row = null, $fallback = false ) {
		// Compat for method sig change in 1.31 (introduction of $key)
		if ( $this->key !== null ) {
			$fallback = $row;
			$row = $key;
			$key = $this->getKey();
		}
		if ( $row === null ) {
			// @codeCoverageIgnoreStart
			throw new InvalidArgumentException( '$row must not be null' );
			// @codeCoverageIgnoreEnd
		}
		return $this->getCommentInternal( null, $key, $row, $fallback );
	}

	/**
	 * Extract the comment from a row, with legacy lookups.
	 *
	 * If `$row` might have been generated using `self::getFields()` rather
	 * than `self::getJoin()`, use this. Prefer `self::getComment()` if you
	 * know callers used `self::getJoin()` for the row fetch.
	 *
	 * If you need to fake a comment in a row for some reason, set fields
	 * `{$key}_text` (string) and `{$key}_data` (JSON string or null).
	 *
	 * @since 1.30
	 * @since 1.31 Method signature changed, $key parameter added (with deprecated back compat)
	 * @param IDatabase $db Database handle to use for lookup
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @param object|array|null $row Result row.
	 * @param bool $fallback If true, fall back as well as possible instead of throwing an exception.
	 * @return CommentStoreComment
	 */
	public function getCommentLegacy( IDatabase $db, $key, $row = null, $fallback = false ) {
		// Compat for method sig change in 1.31 (introduction of $key)
		if ( $this->key !== null ) {
			$fallback = $row;
			$row = $key;
			$key = $this->getKey();
		}
		if ( $row === null ) {
			// @codeCoverageIgnoreStart
			throw new InvalidArgumentException( '$row must not be null' );
			// @codeCoverageIgnoreEnd
		}
		return $this->getCommentInternal( $db, $key, $row, $fallback );
	}

	/**
	 * Create a new CommentStoreComment, inserting it into the database if necessary
	 *
	 * If a comment is going to be passed to `self::insert()` or the like
	 * multiple times, it will be more efficient to pass a CommentStoreComment
	 * once rather than making `self::insert()` do it every time through.
	 *
	 * @note When passing a CommentStoreComment, this may set `$comment->id` if
	 *  it's not already set. If `$comment->id` is already set, it will not be
	 *  verified that the specified comment actually exists or that it
	 *  corresponds to the comment text, message, and/or data in the
	 *  CommentStoreComment.
	 * @param IDatabase $dbw Database handle to insert on. Unused if `$comment`
	 *  is a CommentStoreComment and `$comment->id` is set.
	 * @param string|Message|CommentStoreComment $comment Comment text or Message object, or
	 *  a CommentStoreComment.
	 * @param array|null $data Structured data to store. Keys beginning with '_' are reserved.
	 *  Ignored if $comment is a CommentStoreComment.
	 * @return CommentStoreComment
	 */
	public function createComment( IDatabase $dbw, $comment, array $data = null ) {
		$comment = CommentStoreComment::newUnsavedComment( $comment, $data );

		# Truncate comment in a Unicode-sensitive manner
		$comment->text = $this->lang->truncateForVisual( $comment->text, self::COMMENT_CHARACTER_LIMIT );

		if ( ( $this->stage & SCHEMA_COMPAT_WRITE_NEW ) && !$comment->id ) {
			$dbData = $comment->data;
			if ( !$comment->message instanceof RawMessage ) {
				if ( $dbData === null ) {
					$dbData = [ '_null' => true ];
				}
				$dbData['_message'] = self::encodeMessage( $comment->message );
			}
			if ( $dbData !== null ) {
				$dbData = FormatJson::encode( (object)$dbData, false, FormatJson::ALL_OK );
				$len = strlen( $dbData );
				if ( $len > self::MAX_DATA_LENGTH ) {
					$max = self::MAX_DATA_LENGTH;
					throw new OverflowException( "Comment data is too long ($len bytes, maximum is $max)" );
				}
			}

			$hash = self::hash( $comment->text, $dbData );
			$comment->id = $dbw->selectField(
				'comment',
				'comment_id',
				[
					'comment_hash' => $hash,
					'comment_text' => $comment->text,
					'comment_data' => $dbData,
				],
				__METHOD__
			);
			if ( !$comment->id ) {
				$dbw->insert(
					'comment',
					[
						'comment_hash' => $hash,
						'comment_text' => $comment->text,
						'comment_data' => $dbData,
					],
					__METHOD__
				);
				$comment->id = $dbw->insertId();
			}
		}

		return $comment;
	}

	/**
	 * Implementation for `self::insert()` and `self::insertWithTempTable()`
	 * @param IDatabase $dbw
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @param string|Message|CommentStoreComment $comment
	 * @param array|null $data
	 * @return array [ array $fields, callable $callback ]
	 */
	private function insertInternal( IDatabase $dbw, $key, $comment, $data ) {
		$fields = [];
		$callback = null;

		$comment = $this->createComment( $dbw, $comment, $data );

		if ( $this->stage & SCHEMA_COMPAT_WRITE_OLD ) {
			$fields[$key] = $this->lang->truncateForDatabase( $comment->text, 255 );
		}

		if ( $this->stage & SCHEMA_COMPAT_WRITE_NEW ) {
			$tempTableStage = isset( $this->tempTables[$key] )
				? $this->tempTables[$key]['stage'] : MIGRATION_NEW;
			if ( $tempTableStage & SCHEMA_COMPAT_WRITE_OLD ) {
				$t = $this->tempTables[$key];
				$func = __METHOD__;
				$commentId = $comment->id;
				$callback = function ( $id ) use ( $dbw, $commentId, $t, $func ) {
					$dbw->insert(
						$t['table'],
						[
							$t['pk'] => $id,
							$t['field'] => $commentId,
						],
						$func
					);
				};
			}
			if ( $tempTableStage & SCHEMA_COMPAT_WRITE_NEW ) {
				$fields["{$key}_id"] = $comment->id;
			}
		}

		return [ $fields, $callback ];
	}

	/**
	 * Insert a comment in preparation for a row that references it
	 *
	 * @note It's recommended to include both the call to this method and the
	 *  row insert in the same transaction.
	 *
	 * @since 1.30
	 * @since 1.31 Method signature changed, $key parameter added (with deprecated back compat)
	 * @param IDatabase $dbw Database handle to insert on
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @param string|Message|CommentStoreComment|null $comment As for `self::createComment()`
	 * @param array|null $data As for `self::createComment()`
	 * @return array Fields for the insert or update
	 */
	public function insert( IDatabase $dbw, $key, $comment = null, $data = null ) {
		// Compat for method sig change in 1.31 (introduction of $key)
		if ( $this->key !== null ) {
			$data = $comment;
			$comment = $key;
			$key = $this->key;
		}
		if ( $comment === null ) {
			// @codeCoverageIgnoreStart
			throw new InvalidArgumentException( '$comment can not be null' );
			// @codeCoverageIgnoreEnd
		}

		$tempTableStage = isset( $this->tempTables[$key] )
			? $this->tempTables[$key]['stage'] : MIGRATION_NEW;
		if ( $tempTableStage & SCHEMA_COMPAT_WRITE_OLD ) {
			throw new InvalidArgumentException( "Must use insertWithTempTable() for $key" );
		}

		list( $fields ) = $this->insertInternal( $dbw, $key, $comment, $data );
		return $fields;
	}

	/**
	 * Insert a comment in a temporary table in preparation for a row that references it
	 *
	 * This is currently needed for "rev_comment" and "img_description". In the
	 * future that requirement will be removed.
	 *
	 * @note It's recommended to include both the call to this method and the
	 *  row insert in the same transaction.
	 *
	 * @since 1.30
	 * @since 1.31 Method signature changed, $key parameter added (with deprecated back compat)
	 * @param IDatabase $dbw Database handle to insert on
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @param string|Message|CommentStoreComment|null $comment As for `self::createComment()`
	 * @param array|null $data As for `self::createComment()`
	 * @return array Two values:
	 *  - array Fields for the insert or update
	 *  - callable Function to call when the primary key of the row being
	 *    inserted/updated is known. Pass it that primary key.
	 */
	public function insertWithTempTable( IDatabase $dbw, $key, $comment = null, $data = null ) {
		// Compat for method sig change in 1.31 (introduction of $key)
		if ( $this->key !== null ) {
			$data = $comment;
			$comment = $key;
			$key = $this->getKey();
		}
		if ( $comment === null ) {
			// @codeCoverageIgnoreStart
			throw new InvalidArgumentException( '$comment can not be null' );
			// @codeCoverageIgnoreEnd
		}

		if ( !isset( $this->tempTables[$key] ) ) {
			throw new InvalidArgumentException( "Must use insert() for $key" );
		} elseif ( isset( $this->tempTables[$key]['deprecatedIn'] ) ) {
			wfDeprecated( __METHOD__ . " for $key", $this->tempTables[$key]['deprecatedIn'] );
		}

		list( $fields, $callback ) = $this->insertInternal( $dbw, $key, $comment, $data );
		if ( !$callback ) {
			$callback = function () {
				// Do nothing.
			};
		}
		return [ $fields, $callback ];
	}

	/**
	 * Encode a Message as a PHP data structure
	 * @param Message $msg
	 * @return array
	 */
	protected static function encodeMessage( Message $msg ) {
		$key = count( $msg->getKeysToTry() ) > 1 ? $msg->getKeysToTry() : $msg->getKey();
		$params = $msg->getParams();
		foreach ( $params as &$param ) {
			if ( $param instanceof Message ) {
				$param = [
					'message' => self::encodeMessage( $param )
				];
			}
		}
		array_unshift( $params, $key );
		return $params;
	}

	/**
	 * Decode a message that was encoded by self::encodeMessage()
	 * @param array $data
	 * @return Message
	 */
	protected static function decodeMessage( $data ) {
		$key = array_shift( $data );
		foreach ( $data as &$param ) {
			if ( is_object( $param ) ) {
				$param = (array)$param;
			}
			if ( is_array( $param ) && count( $param ) === 1 && isset( $param['message'] ) ) {
				$param = self::decodeMessage( $param['message'] );
			}
		}
		return new Message( $key, $data );
	}

	/**
	 * Hashing function for comment storage
	 * @param string $text Comment text
	 * @param string|null $data Comment data
	 * @return int 32-bit signed integer
	 */
	public static function hash( $text, $data ) {
		$hash = crc32( $text ) ^ crc32( (string)$data );

		// 64-bit PHP returns an unsigned CRC, change it to signed for
		// insertion into the database.
		if ( $hash >= 0x80000000 ) {
			$hash |= -1 << 32;
		}

		return $hash;
	}

}
