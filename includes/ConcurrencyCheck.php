<?php

/**
 * Class for cooperative locking of web resources
 *
 * Each resource is identified by a combination of the "resource type" (the application, the type
 * of content, etc), and the resource's primary key or some other unique numeric ID.
 *
 * Currently, a resource can only be checked out by a single user.  Other attempts to check it out result
 * in the checkout failing.  In the future, an option for multiple simulataneous checkouts could be added
 * without much trouble.
 *
 * This could be done with named locks, except then it would be impossible to build a list of all the
 * resources currently checked out for a given application.  There's no good way to construct a query
 * that answers the question, "What locks do you have starting with [foo]"  This could be done really well
 * with a concurrent, reliable, distributed key/value store, but we don't have one of those right now.
 *
 * @author Ian Baker <ian@wikimedia.org>
 */
class ConcurrencyCheck {

	protected $expirationTime;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * Constructor
	 * 
	 * @var $resourceType String The calling application or type of resource, conceptually like a namespace
	 * @var $user User object, the current user
	 * @var $expirationTime Integer (optional) How long should a checkout last, in seconds
	 */
	public function __construct( $resourceType, $user, $expirationTime = null ) {

		// All database calls are to the master, since the whole point of this class is maintaining
		// concurrency. Most reads should come from cache anyway.
		$this->dbw = wfGetDb( DB_MASTER );

		$this->user = $user;
		// TODO: create a registry of all valid resourceTypes that client app can add to.
		$this->resourceType = $resourceType;
		$this->setExpirationTime( $expirationTime );
	}

	/**
	 * Check out a resource.  This establishes an atomically generated, cooperative lock
	 * on a key.  The lock is tied to the current user.
	 *
	 * @var $record Integer containing the record id to check out
	 * @var $override Boolean (optional) describing whether to override an existing checkout
	 * @return boolean
	 */
	public function checkout( $record, $override = null ) {
		global $wgMemc;
		$this->validateId( $record );
		$dbw = $this->dbw;
		$userId = $this->user->getId();
		$cacheKey = wfMemcKey( 'concurrencycheck', $this->resourceType, $record );

		// when operating with a single memcached cluster, it's reasonable to check the cache here.
		global $wgConcurrency;
		if( $wgConcurrency['TrustMemc'] ) {
	        $cached = $wgMemc->get( $cacheKey );
			if( $cached ) {
				if( ! $override && $cached['userId'] != $userId && $cached['expiration'] > time() ) {
					// this is already checked out.
					return false;
				}
			}
		}
		
		// attempt an insert, check success (this is atomic)
		$insertError = null;
		$res = $dbw->insert(
			'concurrencycheck',
			array(
				'cc_resource_type' => $this->resourceType,
				'cc_record' => $record,
				'cc_user' => $userId,
				'cc_expiration' => wfTimestamp( TS_MW, time() + $this->expirationTime ),
			),
			__METHOD__,
			array( 'IGNORE' )
		);

		// if the insert succeeded, checkout is done.
		if( $dbw->affectedRows() === 1 ) {
			// delete any existing cache key.  can't create a new key here
			// since the insert didn't happen inside a transaction.
			$wgMemc->delete( $cacheKey );
			return true;
		}

		// if the insert failed, it's necessary to check the expiration.
		// here, check by deleting, since that permits the use of write locks
		// (SELECT..LOCK IN SHARE MODE), rather than read locks (SELECT..FOR UPDATE)
		$dbw->begin();
		$dbw->delete(
			'concurrencycheck',
			array(
				'cc_resource_type' => $this->resourceType,
				'cc_record' => $record,
				'(cc_user = ' . $userId . ' OR cc_expiration <= ' . $dbw->addQuotes(wfTimestamp( TS_MW )) . ')',  // only the owner can perform a checkin
			),
			__METHOD__,
			array()
		);
			
		// delete failed: not checked out by current user, checkout is unexpired, override is unset
		if( $dbw->affectedRows() !== 1 && ! $override) {
			// fetch the existing data to cache it
			$row = $dbw->selectRow(
				'concurrencycheck',
				array( '*' ),
				array(
					'cc_resource_type' => $this->resourceType,
					'cc_record' => $record,
				),
				__METHOD__,
				array()
			);
			
			// this was a cache miss.  populate the cache with data from the db.
			// cache is set to expire at the same time as the checkout, since it'll become invalid then anyway.
			// inside this transaction, a row-level lock is established which ensures cache concurrency
			$wgMemc->set( $cacheKey, array( 'userId' => $row->cc_user, 'expiration' => wfTimestamp( TS_UNIX, $row->cc_expiration ) ), wfTimestamp( TS_UNIX, $row->cc_expiration ) - time() );
			$dbw->rollback();
			return false;
		}

		$expiration = time() + $this->expirationTime;

		// delete succeeded, insert a new row.
		// replace is used here to support the override parameter
		$res = $dbw->replace(
			'concurrencycheck',
			array( 'cc_resource_type', 'cc_record' ),
			array(
				'cc_resource_type' => $this->resourceType,
				'cc_record' => $record,
				'cc_user' => $userId,
				'cc_expiration' => wfTimestamp( TS_MW, $expiration ),
			),
			__METHOD__
		);

		// cache the result.
		$wgMemc->set( $cacheKey, array( 'userId' => $userId, 'expiration' => $expiration ), $this->expirationTime );
		
		$dbw->commit();
		return true;
	}

	/**
	 * Check in a resource. Only works if the resource is checked out by the current user.
	 *
	 * @var $record Integer containing the record id to checkin
	 * @return Boolean
	 */
	public function checkin( $record ) {
		global $wgMemc;
		$this->validateId( $record );		
		$dbw = $this->dbw;
		$userId = $this->user->getId();
		$cacheKey = wfMemcKey( 'concurrencycheck', $this->resourceType, $record );

		$dbw->delete(
			'concurrencycheck',
			array(
				'cc_resource_type' => $this->resourceType,
				'cc_record' => $record,
				'cc_user' => $userId,  // only the owner can perform a checkin
			),
			__METHOD__,
			array()
		);

		// check row count (this is atomic, select would not be)
		if( $dbw->affectedRows() === 1 ) {
			$wgMemc->delete( $cacheKey );
			return true;
		}

		return false;
	}

	/**
	 * Remove all expired checkouts.
	 *
	 * @return Integer describing the number of records expired.
	 */
	public function expire() {
		// TODO: run this in a few other places that db access happens, to make sure the db stays non-crufty.
		$dbw = $this->dbw;
		$now = time();

		// remove the rows from the db.  trust memcached to expire the cache.
		$dbw->delete(
			'concurrencycheck',
			array(
				'cc_expiration <= ' . $dbw->addQuotes( wfTimestamp( TS_MW, $now ) ),
			),
			__METHOD__,
			array()
		);

		// return the number of rows removed.
		return $dbw->affectedRows();
	}

	public function status( $keys ) {
		global $wgMemc, $wgDBtype;
		$dbw = $this->dbw;
		$now = time();

		$checkouts = array();
		$toSelect = array();

		// validate keys, attempt to retrieve from cache.
		foreach( $keys as $key ) {
			$this->validateId( $key );

			$cached = $wgMemc->get( wfMemcKey( 'concurrencycheck', $this->resourceType, $key ) );
			if( $cached && $cached['expiration'] > $now ) {
				$checkouts[$key] = array(
					'status' => 'valid',
					'cc_resource_type' => $this->resourceType,
					'cc_record' => $key,
					'cc_user' => $cached['userId'],
					'cc_expiration' => wfTimestamp( TS_MW, $cached['expiration'] ),
					'cache' => 'cached',
				);
			} else {
				$toSelect[] = $key;
			}
		}

		// if there were cache misses...
		if( $toSelect ) {
			// If it's time to go to the database, go ahead and expire old rows.
			$this->expire();
			
			
			// Why LOCK IN SHARE MODE, you might ask?  To avoid a race condition: Otherwise, it's possible for
			// a checkin and/or checkout to occur between this select and the value being stored in cache, which
			// makes for an incorrect cache.  This, in turn, could make checkout() above (which uses the cache)
			// function incorrectly.
			//
			// Another option would be to run the select, then check each row in-turn before setting the cache
			// key using either SELECT (with LOCK IN SHARE MODE) or UPDATE that checks a timestamp (and which
			// would establish the same lock). That method would mean smaller, quicker locks, but more overall
			// database overhead.
			//
			// It appears all the DBMSes we use support LOCK IN SHARE MODE, but if that's not the case, the second
			// solution above could be implemented instead.
			$queryParams = array();
			if( $wgDBtype === 'mysql' ) {
				$queryParamsp[] = 'LOCK IN SHARE MODE';

				// the transaction seems incongruous, I know, but it's to keep the cache update atomic.
				$dbw->begin();
			}
			
			$res = $dbw->select(
				'concurrencycheck',
				array( '*' ),
				array(
					'cc_resource_type' => $this->resourceType,
					'cc_record' => $toSelect,
					'cc_expiration > ' . $dbw->addQuotes( wfTimestamp( TS_MW ) ),
				),
				__METHOD__,
				$queryParams
			);

			while( $res && $record = $res->fetchRow() ) {
				$record['status'] = 'valid';
				$checkouts[ $record['cc_record'] ] = $record;

				// TODO: implement strategy #2 above, determine which DBMSes need which method.
				// for now, disable adding to cache here for databases that don't support read locking
				if( $wgDBtype !== 'mysql' ) {
					// safe to store values since this is inside the transaction
					$wgMemc->set(
						wfMemcKey( 'concurrencycheck', $this->resourceType, $record['cc_record'] ),
						array( 'userId' => $record['cc_user'], 'expiration' => wfTimestamp( TS_UNIX, $record['cc_expiration'] ) ),
						wfTimestamp( TS_UNIX, $record['cc_expiration'] ) - time()
					);
				}
			}

			if( $wgDBtype === 'mysql' ) {
				// end the transaction.
				$dbw->rollback();
			}
		}

		// if a key was passed in but has no (unexpired) checkout, include it in the
		// result set to make things easier and more consistent on the client-side.
		foreach( $keys as $key ) {
			if( ! array_key_exists( $key, $checkouts ) ) {
				$checkouts[$key]['status'] = 'invalid';
			}
		}

		return $checkouts;
	}

	public function listCheckouts() {
		// TODO: fill in the function that lets you get the complete set of checkouts for a given application.
	}

	/**
	 * @param $user user
	 */
	public function setUser( $user ) {
		$this->user = $user;
	}

	public function setExpirationTime( $expirationTime = null ) {
		global $wgConcurrency;
		
		// check to make sure the time is a number
		// negative number are allowed, though mostly only used for testing
		if( $expirationTime && (int) $expirationTime == $expirationTime ) {
			if( $expirationTime > $wgConcurrency['ExpirationMax'] ) {
				$this->expirationTime = $wgConcurrency['ExpirationMax']; // if the number is too high, limit it to the max value.
			} elseif ( $expirationTime < $wgConcurrency['ExpirationMin'] ) {
				$this->expirationTime = $wgConcurrency['ExpirationMin']; // low limit, default -1 min
			} else {
				$this->expirationTime = $expirationTime; // the amount of time before a checkout expires.
			}
		} else {
			$this->expirationTime = $wgConcurrency['ExpirationDefault']; // global default is 15 mins.
		}
	}

	/**
	 * Check to make sure a record ID is numeric, throw an exception if not.
	 *
	 * @var $record Integer
	 * @throws ConcurrencyCheckBadRecordIdException
	 * @return boolean
	 */
	private static function validateId ( $record ) {
		if( (int) $record !== $record || $record <= 0 ) {
			throw new ConcurrencyCheckBadRecordIdException( 'Record ID ' . $record . ' must be a positive integer' );
		}

		// TODO: add a hook here for client-side validation.
		return true;
	}
}

class ConcurrencyCheckBadRecordIdException extends MWException {}
