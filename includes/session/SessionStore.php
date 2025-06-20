<?php

namespace MediaWiki\Session;

/**
 * This is a session store abstraction layer, which can be used to read
 * and write sessions to configured backend(s). Backends can be single or
 * multiple, depending on the configuration of the site.
 *
 * Sessions are read or written to the store(s), depending on the session
 * type. The get(), set() and delete() methods are in charge of deciding
 * the relevant store to fetch, write, or delete content from. Sessions
 * can be strongly persistent or weakly persistent based on their type.
 *
 * Authenticated sessions have to persist longer based on a TTL, while
 * anonymous sessions are not guaranteed to persist for the duration of
 * the TTL. They'll be regenerated if not available in the backing store.
 *
 * Garbage collection of anonymous sessions is mainly done by the backing
 * store while for authenticated sessions that are expired, it's done when
 * the request is about to be completed by the SessionManager.
 *
 * @ingroup Session
 * @since 1.45
 */
interface SessionStore {

	/**
	 * Retrieves the session data for a given session ID. Can
	 * return false if the session is not found.
	 *
	 * @param SessionInfo $sessionInfo
	 *
	 * @return mixed
	 */
	public function get( SessionInfo $sessionInfo );

	/**
	 * Write the session data for a given key to the session store.
	 *
	 * @param SessionInfo $sessionInfo
	 * @param mixed $value
	 * @param int $exptime
	 * @param int $flags Should be one of BagOStuff::WRITE_* constants
	 */
	public function set( SessionInfo $sessionInfo, $value, $exptime = 0, $flags = 0 ): void;

	/**
	 * Delete the session data for a given ID from the session store.
	 *
	 * @param SessionInfo $sessionInfo
	 */
	public function delete( SessionInfo $sessionInfo ): void;

	/**
	 * Perform various cleanup and instrumentation activities on the
	 * session store object like: garbage collection (delete expired
	 * entries), do logging and monitoring, etc.
	 *
	 * @return void
	 */
	public function shutdown(): void;
}
