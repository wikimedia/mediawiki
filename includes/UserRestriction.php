<?php

/**
 * Object that represents a user restriction
 */
class UserRestriction {
	const PAGE = 'page';
	const NAMESPACE = 'namespace';

	private $mId, $mType, $mNamespace, $mPage, $mSubjectText, $mSubjectId,
		$mBlockerId, $mBlockerText, $mReason, $mTimestamp, $mExpiry;

	public static function newFromRow( $row ) {
		if( !$row )
			return null;

		$obj = new UserRestriction();
		$obj->mId = $row->ur_id;
		$obj->mType = $row->ur_type;
		if( $obj->mType == self::PAGE ) {
			$obj->mPage = Title::makeTitle( $row->ur_page_namespace, $row->ur_page_title );
		} elseif( $obj->mType == self::NAMESPACE ) {
			$obj->mNamespace = $row->ur_namespace;
		} else {
			throw new MWException( "Unknown user restriction type: {$row->ur_type}" );
		}

		$obj->mSubjectId = $row->ur_user;
		$obj->mSubjectText = $row->ur_user_text;
		$obj->mBlockerId = $row->ur_by;
		$obj->mBlockerText = $row->ur_by_text;
		$obj->mReason = $row->ur_reason;
		$obj->mTimestamp = wfTimestamp( TS_MW, $row->ur_timestamp );
		$obj->mExpiry = $row->ur_expiry;
		return $obj;
	}

	public static function fetchForUser( $user, $forWrite = false ) {
		$dbr = wfGetDB( $forWrite ? DB_MASTER : DB_SLAVE );
		if( is_int( $user ) )
			$query = array( 'ur_user' => $user );
		else
			$query = array( 'ur_user_text' => $user );
		$res = $dbr->select( 'user_restrictions', '*', $query, __METHOD__ );
		$result = array();
		foreach( $res as $row ) {
			$result[] = self::newFromRow( $row );
		}
		return $result;
	}

	public static function newFromId( $id, $forWrite = false ) {
		$dbr = wfGetDB( $forWrite ? DB_MASTER : DB_SLAVE );
		if( !$id || !is_numeric( $id ) )
			return null;
		$res = $dbr->selectRow( 'user_restrictions', '*', array( 'ur_id' => $id ), __METHOD__ );
		return self::newFromRow( $res );
	}

	public function getId() { return $this->mId; }
	public function setId( $v ) { $this->mId = $v; }
	public function getType() { return $this->mType; }
	public function setType( $v ) { $this->mType = $v; }
	public function getNamespace() { return $this->mNamespace; }
	public function setNamespace( $v ) { $this->mNamespace = $v; }
	public function getPage() { return $this->mPage; }
	public function setPage( $v ) { $this->mPage = $v; }
	public function getSubjectId() { return $this->mSubjectId; }
	public function setSubjectId( $v ) { $this->mSubjectId = $v; }
	public function getSubjectText() { return $this->mSubjectText; }
	public function setSubjectText( $v ) { $this->mSubjectText = $v; }
	public function getBlockerId() { return $this->mBlockerId; }
	public function setBlockerId( $v ) { $this->mBlockerId = $v; }
	public function getBlockerText() { return $this->mBlockerText; }
	public function setBlockerText( $v ) { $this->mBlockerText = $v; }
	public function getReason() { return $this->mReason; }
	public function setReason( $v ) { $this->mReason = $v; }
	public function getTimestamp() { return $this->mTimestamp; }
	public function setTimestamp( $v ) { $this->mTimestamp = $v; }
	public function getExpiry() { return $this->mExpiry; }
	public function setExpiry( $v ) { $this->mExpiry = $v; }

	public function isPage() {
		return $this->mType == self::PAGE;
	}
	public function isNamespace() {
		return $this->mType == self::NAMESPACE;
	}

	public function isExpired() {
		return is_numeric( $this->mExpiry ) && $this->mExpiry < wfTimestampNow( TS_MW );
	}

	public function deleteIfExpired() {
		if( $this->isExpired() ) {
			$this->delete();
			return true;
		} else {
			return false;
		}
	}

	public function delete() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'user_restrictions', array( 'ur_id' => $this->mId ), __METHOD__ );
		return $dbw->affectedRows();
	}

	public static function purgeExpired() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'user_restrictions', array( 'ur_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ), __METHOD__ );
	}

	public function commit() {
		$dbw = wfGetDB( DB_MASTER );
		$this->setId( $dbw->nextSequenceValue('user_restrictions_ur_id_val') );
		$row = array(
			'ur_id' => $this->mId,
			'ur_type' => $this->mType,
			'ur_user' => $this->mSubjectId,
			'ur_user_text' => $this->mSubjectText,
			'ur_by' => $this->mBlockerId,
			'ur_by_text' => $this->mBlockerText,
			'ur_reason' => $this->mReason,
			'ur_timestamp' => $dbw->timestamp( $this->mTimestamp ),
			'ur_expiry' => $this->mExpiry,
		);
		if( $this->isPage() ) {
			$row['ur_page_namespace'] = $this->mPage->getNamespace();
			$row['ur_page_title'] = $this->mPage->getDbKey();
		}
		if( $this->isNamespace() ) {
			$row['ur_namespace'] = $this->mNamespace;
		}
		$dbw->insert( 'user_restrictions', $row, __METHOD__ );
	}

	public static function formatType( $type ) {
		return wfMsg( 'userrestrictiontype-' . $type );
	}

	/**
	 * Converts expiry which user input to the internal representation.
	 * Returns false if invalid expiry is set, Block::infinity() on empty value,
	 * Block::infinity() on infinity or 14-symbol timestamp
	 */
	public static function convertExpiry( $expiry ) {
		if( !$expiry )
			return Block::infinity();
		if( in_array( $expiry, array( 'infinite', 'infinity', 'indefinite' ) ) )
			return Block::infinity();
		$unix = @strtotime( $expiry );
		if( !$unix || $unix === -1 )
			return false;
		else
			return wfTimestamp( TS_MW, $unix );
	}
}
