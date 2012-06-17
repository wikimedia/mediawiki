<?php

/**
 * Implements wrapper for user messages in the user_newtalk table.
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

/**
 * The UserMessage object represents a new message for a user.
 * This does not represent an actual message, but rather whether
 * the user has any new messages. For example, if a user has new
 * talk page messages, a UserMessage with type 'talk' is made and
 * stored in the database.
 */
class UserMessage {
	const NOTLOADED = -1;
	const NOTSET = 0;
	const SET = 1;
	
	const MSG_TALK = 0;
	const MSG_AUTH = 1;
	
	private static $typeNames = array(
		UserMessage::MSG_TALK => 'talk',
		UserMessage::MSG_AUTH => 'auth'
	);

	/**
	 * User the message is for
	 * @var User $user
	 */
	private $user;
	
	/**
	 * Type of the message
	 * @var string $type
	 */
	private $type;
	
	/**
	 * Timestamp the message was created
	 * @var string $timestamp
	 */
	private $timestamp;
	
	/**
	 * Status of the message. Can be NOTLOADED if the status has
	 * not been set yet, NOTSET if the user doesn't have this specific
	 * message type, or SET if the user has the message.
	 * @var int $status
	 */
	private $status;
	
	/**
	 * Get an array of all messages the given user has.
	 * @param User|string $user User or IP address to get messages for
	 * @return Array List of UserMessage objects
	 */
	public static function getUserMessages( $user ) {
		global $wgMemc;
		$db = wfGetDB( DB_SLAVE );
		
		if( is_string( $user ) ) {
			$field = 'user_ip';
			$value = $user;
		} else {
			$field = 'user_id';
			$value = $user->getId();
		}
		
		$messages = array();
		
		// Check memcached first.
		$memKey = wfMemcKey( 'user', 'message', $value );
		$types = $wgMemc->get( $memKey );
		if( is_array( $types ) ) {
			foreach( $types as $type => $timestamp ) {
				$messages[] = new UserMessage( $user, $type, $timestamp, UserMessage::SET );
			}
		} else {
			// Cache miss. Go to database.
			$res = $db->select(
				'user_newtalk',
				array( 'user_last_timestamp', 'user_msg_type' ),
				array( $field => $value )
			);
			
			$toCache = array();
			foreach( $res as $row ) {
				$messages[] = new UserMessage(
					$user,
					$row->user_msg_type,
					$row->user_last_timestamp,
					UserMessage::SET
				);
				$toCache[$row->user_msg_type] = $row->user_last_timestamp;
			}
			$wgMemc->set( $memKey, $toCache, 72 * 3600 );
		}
		return $messages;
	}
	
	/**
	 * Clear all messages for the user.
	 */
	public static function clearMessages( $user ) {
		global $wgMemc;
		$db = wfGetDB( DB_SLAVE );
		
		if( is_string( $user ) ) {
			$field = 'user_ip';
			$value = $user;
		} else {
			$field = 'user_id';
			$value = $user->getId();
		}
		
		// Clear memcached (but don't delete key as this will cause an unnecessary
		// DB query next time).
		$memKey = wfMemcKey( 'user', 'message', $value );
		$wgMemc->replace( $memKey, array() );

		// Clear database.
		$res = $db->delete( 'user_newtalk', array( $field => $value ) );
	}
	
	/**
	 * Put together a new UserMessage object by storing the user, type,
	 * and timestamp.
	 */
	public function __construct( $user, $type, $timestamp = false, $status = UserMessage::NOTLOADED ) {
		$this->user = $user;
		$this->type = $type;
		$this->timestamp = $timestamp;
		$this->status = $status;
	}
	
	/**
	 * Get the user associated with the message.
	 * @return User|string $user User or IP address to get messages for
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * Get the type of the message.
	 * @return string The message type
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * Get the timestamp of the message in the specified format.
	 * @param string $type Output type of the timestamp
	 * @return string Timestamp for the message
	 */
	public function getTimestamp( $type ) {
		if( $this->timestamp === false ) {
			return false;
		} else {
			return wfTimestamp( $type, $this->timestamp );
		}
	}
	
	/**
	 * Get the status of the message, i.e., whether the user has this
	 * message yet.
	 * @return int Whether the user has the message or not (see class constants)
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * Get the string message of this message, which is whatever message
	 * is stored under the key newmessages-type-$type.
	 * @return string The message
	 */
	public function getMessage() {
		$type = UserMessage::$typeNames[$this->type];
		$msg = "newmessages-type-$type";
		return RequestContext::getMain()->msg( $msg );
	}
	
	/**
	 * Load the message from the database. If the user doesn't have this
	 * specific message, set the timestamp to false and status to NOTSET.
	 * Otherwise, store the timestamp of the message and set the status to SET.
	 */
	public function load() {
		global $wgMemc;
		if( is_string( $this->user ) ) {
			$field = 'user_ip';
			$value = $this->user;
		} else {
			$field = 'user_id';
			$value = $this->user->getId();
		}
		
		// Try memcached first, then database.
		$memKey = wfMemcKey( 'user', 'message', $value );
		$types = $wgMemc->get( $memKey );
		if( is_array( $types ) ) {
			// Memcached is valid.
			if( array_key_exists( $this->type, $types ) ) {
				$this->status = self::SET;
				$this->timestamp = $types[$this->type];
			} else {
				$this->status = self::NOTSET;
				$this->timestamp = false;
			}
		} else {
			// Cache miss. Get from DB.
			$db = wfGetDB( DB_SLAVE );
			$res = $db->selectField(
				'user_newtalk',
				'user_last_timestamp',
				array( $field => $value, 'user_msg_type' => $this->type ),
				array( 'IGNORE' )
			);
			
			$this->status = $res === false ? UserMessage::NOTSET : UserMessage::SET;
			$this->timestamp = $res;
		}
	}
	
	/**
	 * Update the status of the message. If the status is updated from
	 * SET to NOTSET, delete it from the database. If vice-versa, add it
	 * to the database. Otherwise, no action.
	 * @param int Whether the user has the message or not (see class constants)
	 * @return bool True if the status changed, false otherwise
	 */
	public function update( $status ) {
		global $wgMemc;
		if( $status == $this->status ) {
			return false;
		}
		
		$this->status = $status;
		
		if( is_string( $this->user ) ) {
			$field = 'user_ip';
			$value = $this->user;
		} else {
			$field = 'user_id';
			$value = $this->user->getId();
		}
		
		$db = wfGetDB( DB_MASTER );
		$memKey = wfMemcKey( 'user', 'message', $value );
		$types = $wgMemc->get( $memKey );
		
		if( $status == UserMessage::SET ) {
			$this->timestamp = wfTimestamp( TS_MW );
			$indices = array( array( 'user_msg_type', $field ) );
			$row = array(
				$field => $value,
				'user_last_timestamp' => $this->timestamp,
				'user_msg_type' => $this->type
			);
			
			$db->replace( 'user_newtalk', $indices, $row, __METHOD__ );
			
			if( is_array( $types ) ) {
				$types[$this->type] = $this->timestamp;
			} else {
				$types = array( $this->type => $this->timestamp );
			}
		} else {
			$this->timestamp = false;
			$where = array( $field => $value, 'user_msg_type' => $this->type );
			$db->delete( 'user_newtalk', $where, __METHOD__ );
			
			if( is_array( $types ) && array_key_exists( $this->type, $types ) ) {
				unset( $types[$this->type] );
			}
		}
		
		$wgMemc->set( $memKey, $types );
		
		$this->user->invalidateCache();
		return true;
	}
}