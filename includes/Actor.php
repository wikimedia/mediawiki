<?php

use Wikimedia\Rdbms\IDatabase;

class Actor {
	/**
	 * The actor_id row id for the actor table.
	 * @var int
	 */
	protected $mId;

	/**
	 * The actor_user relation to user_id, or null if not a local user.
	 * @var int|null
	 */
	protected $mUserId;

	/**
	 * The actor_text text form of the name.
	 * @var string
	 */
	protected $mText;

	/**
	 * Do not construct Actor instances manually; use the ActorStore service.
	 * @param int $id row id in the actor table
	 * @param int|null $userId matching user_id in the actor table, or null
	 * @param string $text text-form name of the user/ip/import
	 */
	public function __construct( $id, $userId, $text ) {
		$this->mId = intval( $id );
		$this->mUserId = is_null( $userId ) ? null : intval( $userId );
		$this->mText = strval( $text );
	}

	/**
	 * @return int the row id for the actor table
	 */
	public function getId() {
		return $this->mId;
	}

	/**
	 * @return int|null user_id of the matching user, or null
	 */
	public function getUserId() {
		return $this->mUserId;
	}

	/**
	 * @return string text form of the username/IP/etc
	 */
	public function getText() {
		return $this->mText;
	}

	/**
	 * @return User|bool matching User object or nothing
	 */
	public function getUser() {
		if ( $this->mId ) {
			return User::newFromId( $this->mId );
		} else {
			return User::newFromName( $this->mText, false );
		}
	}
}

class ActorStore {
	protected $mDb;

	public function __construct( IDatabase $db ) {
		$this->mDb = $db;
	}

	/**
	 * Store an actor record if necessary given an ID and text,
	 * returning the record with id.
	 *
	 * @param int|null $userId the user id
	 * @param string $text
	 * @return Actor
	 * @throws Exception may be thrown on database error
	 */
	public function store( $userId, $text ) {
		if ( $userId ) {
			$conds = [ 'actor_user' => $userId ];
		} else {
			$conds = [ 'actor_text' => $text ];
		}
		$row = $this->mDb->selectRow( 'actor',
			[ 'actor_id', 'actor_user', 'actor_text' ],
			$conds,
			__METHOD__
		);
		if ( $row ) {
			return new Actor( $row->actor_id, $row->actor_user, $row->actor_text );
		} else {
			$id = $this->mDb->nextSequenceValue( 'actor_id_seq' );
			$ok = $this->mDb->insert( 'actor',
				[
					'actor_id' => $id,
					'actor_user' => $userId,
					'actor_text' => $text
				],
				__METHOD__
			);
			if ( $ok ) {
				$id = $this->mDb->insertId();
				return new Actor( $id, $userId, $text );
			} else {
				throw new MWException( 'unexpected failure inserting actor row' );
			}
		}
	}

	/**
	 * Provide the columns that would be needed to look up an actor reference
	 * when manually doing a join to actor table.
	 *
	 * @return array of string column names
	 */
	public function selectFields() {
		return [ 'actor_id', 'actor_user', 'actor_text' ];
	}

	/**
	 * Create an Actor instance from a database row object.
	 * @param object $row a DB result row containing necessary fields
	 * @return Actor
	 */
	public function fetchFromRow( $row ) {
		return new Actor( $row->actor_id, $row->actor_user, $row->actor_text );
	}

	/**
	 * Internal wrapper for fetching individual actor references.
	 *
	 * @param array $conds
	 * @return Actor|bool matching Actor instance or false if none found
	 */
	protected function fetchByConds( array $conds ) {
		$row = $this->mDb->selectRow( 'actor',
			$this->selectFields(),
			$conds,
			__METHOD__
		);
		if ( $row ) {
			return $this->fetchFromRow( $row )
		} else {
			return false;
		}
	}

	/**
	 * Fetch an individual actor reference by id.
	 *
	 * @param array $conds
	 * @return Actor|bool matching Actor instance or false if none found
	 */
	public function fetchById( $id ) {
		return $this->fetchByConds( [ 'actor_id' => $id ] );
	}

	/**
	 * Fetch an individual actor reference by user id.
	 *
	 * @param array $conds
	 * @return Actor|bool matching Actor instance or false if none found
	 */
	public function fetchByUserId( $userId ) {
		return $this->fetchByConds( [ 'actor_user' => $userId ] );
	}

	/**
	 * Fetch an individual actor reference by text (name/IP/etc).
	 *
	 * @param array $conds
	 * @return Actor|bool matching Actor instance or false if none found
	 */
	public function fetchByText( $text ) {
		return $this->fetchByConds( [ 'actor_text' => $text ] );
	}

	public function fetchByPrefix( $prefix ) {
		return $this->fetchByConds( [ 'actor_text ' .
			$this->mDb->buildLike( $prefix, $this->mDb->anyString() ) ] );
	}
}

class ActorHelper {
}
