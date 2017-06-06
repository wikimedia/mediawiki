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

use Wikimedia\Rdbms\IDatabase;

/**
 * CommentStore handles storage of comments (edit summaries, log reasons, etc)
 * in the database.
 * @since 1.30
 */
class CommentStore {

	/**
	 * Define fields that use temporary tables for transitional purposes
	 * @var array Keys are '$key', values are arrays with four fields:
	 *  - table: Temporary table name
	 *  - pk: Temporary table column referring to the main table's primary key
	 *  - field: Temporary table column referring comment.comment_id
	 *  - joinPK: Main table's primary key
	 */
	protected static $tempTables = [
		'rev_comment' => [
			'table' => 'revision_comment_temp',
			'pk' => 'revcomment_rev',
			'field' => 'revcomment_comment_id',
			'joinPK' => 'rev_id',
		],
		'img_description' => [
			'table' => 'image_comment_temp',
			'pk' => 'imgcomment_name',
			'field' => 'imgcomment_description_id',
			'joinPK' => 'img_name',
		],
	];

	/**
	 * Fields that formerly used $tempTables
	 * @var array Key is '$key', value is the MediaWiki version in which it was
	 *  removed from $tempTables.
	 */
	protected static $formerTempTables = [];

	/** @var IDatabase */
	protected $db;

	/** @var int One of the MIGRATION_* constants */
	protected $stage;

	/** @var array Cache for `self::getJoin()` */
	protected $joinCache;

	/**
	 * @param IDatabase $db Database handle
	 */
	public function __construct( IDatabase $db ) {
		global $wgCommentTableSchemaMigrationStage;

		$this->db = $db;
		$this->stage = $wgCommentTableSchemaMigrationStage;
	}

	/**
	 * Get SELECT fields for a comment key
	 *
	 * Each resulting row should be passed to `self::getComment()` to get the
	 * actual comment.
	 *
	 * @note Use of this method may require a subsequent database query to
	 *  actually fetch the comment. If possible, use `self::getJoin()` instead.
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @return string[] to include in the `$vars` to `IDatabase->select()`. All
	 *  fields are aliased, so `+` is safe to use.
	 */
	public function getFields( $key ) {
		$fields = [];
		if ( $this->stage === MIGRATION_OLD ) {
			$fields["{$key}_text"] = $key;
			$fields["{$key}_data"] = 'NULL';
		} else {
			if ( $this->stage < MIGRATION_NEW ) {
				$fields["{$key}_old"] = $key;
			}
			if ( isset( self::$tempTables[$key] ) ) {
				$fields["{$key}_pk"] = self::$tempTables[$key]['joinPK'];
			} else {
				$fields["{$key}_id"] = "{$key}_id";
			}
		}
		return $fields;
	}

	/**
	 * Get SELECT fields and joins for a comment key
	 *
	 * Each resulting row should be passed to `self::getComment()` to get the
	 * actual comment.
	 *
	 * @param string $key A key such as "rev_comment" identifying the comment
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

			if ( $this->stage === MIGRATION_OLD ) {
				$fields["{$key}_text"] = $key;
				$fields["{$key}_data"] = 'NULL';
			} else {
				$join = $this->stage === MIGRATION_NEW ? 'JOIN' : 'LEFT JOIN';

				if ( isset( self::$tempTables[$key] ) ) {
					$t = self::$tempTables[$key];
					$alias = "temp_$key";
					$tables[$alias] = $t['table'];
					$joins[$alias] = [ $join, [ "{$alias}.{$t['pk']} = {$t['joinPK']}" ] ];
					$joinField = "{$alias}.{$t['field']}";
				} else {
					$joinField = "{$key}_id";
				}

				$alias = "comment_$key";
				$tables[$alias] = 'comment';
				$joins[$alias] = [ $join, [ "{$alias}.comment_id = {$joinField}" ] ];

				if ( $this->stage === MIGRATION_NEW ) {
					$fields["{$key}_text"] = "{$alias}.comment_text";
				} else {
					$fields["{$key}_text"] = "COALESCE( {$alias}.comment_text, $key )";
				}
				$fields["{$key}_data"] = "{$alias}.comment_data";
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
	 * Use `self::getJoin()` (preferred) or `self::getFields()` to ensure the
	 * row contains the needed data.
	 *
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @param object|array $row Result row.
	 * @return object with the following properties
	 *  - text: (string) Text version of the comment
	 *  - message: (Message) Message version of the comment. Might be a RawMessage.
	 *  - data: (object|null) Structured data of the comment
	 */
	public function getComment( $key, $row ) {
		$row = (array)$row;
		if ( array_key_exists( "{$key}_text", $row ) && array_key_exists( "{$key}_data", $row ) ) {
			$text = $row["{$key}_text"];
			$data = $row["{$key}_data"];
		} elseif ( $this->stage === MIGRATION_OLD ) {
			// @codeCoverageIgnoreStart
			wfLogWarning( "Missing {$key}_text and {$key}_data fields in row with MIGRATION_OLD" );
			$text = '';
			$data = null;
			// @codeCoverageIgnoreEnd
		} else {
			if ( isset( self::$tempTables[$key] ) ) {
				// @codeCoverageIgnoreStart
				if ( !array_key_exists( "{$key}_pk", $row ) ) {
					throw new InvalidArgumentException( "\$row does not contain fields needed for comment $key" );
				}
				// @codeCoverageIgnoreEnd

				$t = self::$tempTables[$key];
				$id = $row["{$key}_pk"];
				$row2 = $this->db->selectRow(
					[ $t['table'], 'comment' ],
					[ 'comment_text', 'comment_data' ],
					[ $t['pk'] => $id ],
					__METHOD__,
					[],
					[ 'comment' => [ 'JOIN', [ "comment_id = {$t['field']}" ] ] ]
				);
			} else {
				// @codeCoverageIgnoreStart
				if ( !array_key_exists( "{$key}_id", $row ) ) {
					throw new InvalidArgumentException( "\$row does not contain fields needed for comment $key" );
				}
				// @codeCoverageIgnoreEnd

				$id = $row["{$key}_id"];
				$row2 = $this->db->selectRow(
					'comment',
					[ 'comment_text', 'comment_data' ],
					[ 'comment_id' => $id ],
					__METHOD__
				);
			}

			if ( $row2 ) {
				$text = $row2->comment_text;
				$data = $row2->comment_data;
			} elseif ( $this->stage < MIGRATION_NEW && array_key_exists( "{$key}_old", $row ) ) {
				$text = $row["{$key}_old"];
				$data = null;
			} else {
				// @codeCoverageIgnoreStart
				wfLogWarning( "Missing comment row for $key, id=$id" );
				$text = '';
				$data = null;
				// @codeCoverageIgnoreEnd
			}
		}

		$msg = null;
		if ( $data !== null ) {
			$data = FormatJson::decode( $data );
			if ( !is_object( $data ) ) {
				// @codeCoverageIgnoreStart
				wfLogWarning( "Invalid JSON object in comment: $data" );
				$data = null;
				// @codeCoverageIgnoreEnd
			} else {
				$data = (array)$data;
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

		return (object)[
			'text' => $text,
			'message' => $msg ?: new RawMessage( '$1', [ $text ] ),
			'data' => $data,
		];
	}

	/**
	 * @param string $key
	 * @param string|Message $text
	 * @param array|null $data
	 * @return array [ array $fields, callable $callback ]
	 */
	private function insertInternal( $key, $text, $data ) {
		global $wgContLang;

		$fields = [];
		$callback = null;

		if ( $data !== null ) {
			if ( !is_array( $data ) ) {
				throw new InvalidArgumentException( '$data must be null or an array' );
			}
			foreach ( $data as $k => $v ) {
				if ( substr( $k, 0, 1 ) === '_' ) {
					throw new InvalidArgumentException( 'Keys in $data beginning with "_" are reserved' );
				}
			}
		}

		if ( $text instanceof Message ) {
			if ( $data === null ) {
				$data = [ '_null' => true ];
			}
			$data['_message'] = self::encodeMessage( $text );
			$text = $text
				->inLanguage( $wgContLang ) // Avoid $wgForceUIMsgAsContentMsg
				->setInterfaceMessageFlag( true )
				->text();
		}
		if ( $data !== null ) {
			$data = FormatJson::encode( (object)$data, false, FormatJson::ALL_OK );
		}

		if ( $this->stage <= MIGRATION_WRITE_BOTH ) {
			$fields[$key] = $text;
		}

		if ( $this->stage >= MIGRATION_WRITE_BOTH ) {
			$this->db->startAtomic( __METHOD__ );
			$commentId = $this->db->selectField(
				'comment',
				'comment_id',
				[
					'comment_text' => $text,
					'comment_data' => $data,
				],
				__METHOD__,
				[ 'FOR UPDATE' ]
			);
			if ( !$commentId ) {
				$commentId = $this->db->nextSequenceValue( 'comment_comment_id_seq' );
				$this->db->insert(
					'comment',
					[
						'comment_id' => $commentId,
						'comment_text' => $text,
						'comment_data' => $data,
					],
					__METHOD__
				);
				$commentId = $this->db->insertId();
			}
			$this->db->endAtomic( __METHOD__ );

			if ( isset( self::$tempTables[$key] ) ) {
				$t = self::$tempTables[$key];
				$func = __METHOD__;
				$callback = function ( $id ) use ( $commentId, $t, $func ) {
					$this->db->insert(
						$t['table'],
						[
							$t['pk'] => $id,
							$t['field'] => $commentId,
						],
						$func
					);
				};
			} else {
				$fields["{$key}_id"] = $commentId;
			}
		}

		return [ $fields, $callback ];
	}

	/**
	 * Prepare for the insertion of a row with a comment
	 *
	 * @note This may insert into the database. It's recommended to include
	 *  both the call to this method and the actual insert in the same
	 *  transaction.
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being set.
	 * @param string|Message $comment Comment text or Message object
	 * @param array|null $data Structured data to store. The key 'message' is reserved.
	 * @return array Fields for the insert or update
	 */
	public function insert( $key, $text, $data = null ) {
		if ( isset( self::$tempTables[$key] ) ) {
			throw new InvalidArgumentException( "Must use insertWithTempTable() for $key" );
		}

		list( $fields ) = $this->insertInternal( $key, $text, $data );
		return $fields;
	}

	/**
	 * Prepare for the insertion of a row with a comment and temporary table
	 *
	 * This is currently needed for "rev_comment" and "img_description". In the
	 * future that requirement will be removed.
	 *
	 * @note This may insert into the database. It's recommended to include
	 *  both the call to this method and the actual insert in the same
	 *  transaction.
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being set.
	 * @param string|Message $comment Comment text or Message object
	 * @param array|null $data Structured data to store. Keys beginning with
	 *  '_' are reserved.
	 * @return array Two values:
	 *  - array Fields for the insert or update
	 *  - callable Function to call when the primary key of the row being
	 *    inserted/updated is known. Pass it that primary key.
	 */
	public function insertWithTempTable( $key, $text, $data = null ) {
		if ( isset( self::$formerTempTables[$key] ) ) {
			wfDeprecated( __METHOD__ . " for $key", self::$formerTempTables[$key] );
		} elseif ( !isset( self::$tempTables[$key] ) ) {
			throw new InvalidArgumentException( "Must use insert() for $key" );
		}

		list( $fields, $callback ) = $this->insertInternal( $key, $text, $data );
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

}
