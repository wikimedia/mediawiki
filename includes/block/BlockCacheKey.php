<?php

namespace MediaWiki\Block;

use MediaWiki\Request\WebRequest;
use MediaWiki\User\UserIdentity;
use Stringable;
use WeakReference;

/**
 * @internal For use by BlockManager
 */
class BlockCacheKey implements Stringable {
	/** Whether the key includes a non-null request */
	private bool $hasRequest;
	/** A reference to the WebRequest or null */
	private ?WeakReference $requestRef;
	/** A reference to the UserIdentity */
	private WeakReference $userRef;
	/** Whether the UserIdentity has zero user ID */
	private bool $isAnon;
	/** The part of the key indicating whether to do queries against the primary DB */
	private bool $fromPrimary;

	/**
	 * @param WebRequest|null $request
	 * @param UserIdentity $user
	 * @param bool $fromPrimary
	 */
	public function __construct( ?WebRequest $request, UserIdentity $user, bool $fromPrimary ) {
		$this->requestRef = $request ? WeakReference::create( $request ) : null;
		$this->hasRequest = (bool)$request;
		$this->userRef = WeakReference::create( $user );
		$this->isAnon = $user->getId() === 0;
		$this->fromPrimary = $fromPrimary;
	}

	/**
	 * Compare the request part of the key with another key
	 *
	 * @param BlockCacheKey $other
	 * @return bool
	 */
	private function requestEquals( self $other ): bool {
		if ( $this->hasRequest !== $other->hasRequest ) {
			return false;
		} elseif ( $this->hasRequest ) {
			return $this->requestRef->get()
				&& $this->requestRef->get() === $other->requestRef->get();
		} else {
			return true;
		}
	}

	/**
	 * Determine whether a new key matches a stored key sufficiently well to
	 * allow the stored cache entry to be returned.
	 *
	 * If a WeakReference in either key has expired, that is considered as
	 * a mismatch.
	 *
	 * @param BlockCacheKey $storedKey
	 * @return bool
	 */
	public function matchesStored( self $storedKey ): bool {
		return $this->requestEquals( $storedKey )
			&& $this->userRef->get()
			&& $this->userRef->get() === $storedKey->userRef->get()
			&& (
				( $this->fromPrimary === $storedKey->fromPrimary )
				// If we have data from the primary, allow it to be returned
				// when replica data is requested.
				|| ( !$this->fromPrimary && $storedKey->fromPrimary )
			);
	}

	/**
	 * Get the bucket for the cache entry associated with this key.
	 * Entries with the same partial key will replace each other in the cache.
	 */
	public function getPartialKey(): string {
		if ( $this->hasRequest ) {
			return 'req';
		}
		return $this->isAnon ? 'anon' : 'user';
	}

	/**
	 * Determine whether the specified user is the user stored in the key. This
	 * is used for cache invalidation.
	 *
	 * If the WeakReference has expired, it is not possible to serve a cache
	 * hit for the user, so that is considered to be a mismatch.
	 *
	 * @param UserIdentity $user
	 * @return bool
	 */
	public function isUser( UserIdentity $user ): bool {
		return $this->userRef->get() === $user;
	}

	/**
	 * @param WeakReference|null $ref
	 * @return string
	 */
	private static function dumpWeakRef( ?WeakReference $ref ): string {
		if ( $ref === null ) {
			return 'none';
		} else {
			$obj = $ref->get();
			if ( $obj ) {
				return '#' . spl_object_id( $obj );
			} else {
				return 'expired';
			}
		}
	}

	/**
	 * Convert to a string, for debugging
	 */
	public function __toString(): string {
		return 'BlockCacheKey{' .
			'request=' . self::dumpWeakRef( $this->requestRef ) . ',' .
			'user=' . self::dumpWeakRef( $this->userRef ) . ',' .
			( $this->fromPrimary ? 'primary' : 'replica' ) .
			'}';
	}
}
