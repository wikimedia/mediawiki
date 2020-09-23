<?php
/**
 * Parser cache specific expiry check.
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
 * @ingroup Parser
 */

/**
 * Parser cache specific expiry check.
 *
 * @ingroup Parser
 */
class CacheTime {
	/**
	 * @var string[] ParserOptions which have been taken into account to produce output.
	 */
	private $mUsedOptions;

	/**
	 * @var true[] List of ParserOptions (stored in the keys).
	 */
	protected $mAccessedOptions = [];

	/**
	 * @var string Compatibility check
	 */
	private $mVersion = Parser::VERSION;

	/**
	 * @var string|int TS_MW timestamp when this object was generated, or -1 for not cacheable. Used
	 * in ParserCache.
	 */
	private $mCacheTime = '';

	/**
	 * @var int|null Seconds after which the object should expire, use 0 for not cacheable. Used in
	 * ParserCache.
	 */
	private $mCacheExpiry = null;

	/**
	 * @var int|null Revision ID that was parsed
	 */
	private $mCacheRevisionId = null;

	/**
	 * Deprecate access to all public properties.
	 * Do not use deprecation helper to avoid serializaing
	 * the list of deprecated methods.
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name ) {
		wfDeprecatedMsg(
			'get ' . __CLASS__ . '::' . $name, '1.36' );
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		} else {
			trigger_error( 'Inaccessible property via __get(): ' . $name, E_USER_NOTICE );
		}
	}

	/**
	 * Deprecate access to all public properties.
	 * Do not use deprecation helper to avoid serializaing
	 * the list of deprecated methods.
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set( $name, $value ) {
		wfDeprecatedMsg(
			'set ' . __CLASS__ . '::' . $name, '1.36' );
		if ( property_exists( $this, $name ) ) {
			$this->$name = $value;
		} else {
			trigger_error( 'Inaccessible property via __set(): ' . $name, E_USER_NOTICE );
		}
	}

	/**
	 * @return string TS_MW timestamp
	 */
	public function getCacheTime() {
		// NOTE: keep support for undocumented used of -1 to mean "not cacheable".
		if ( $this->mCacheTime === '' ) {
			$this->mCacheTime = MWTimestamp::now();
		}
		return $this->mCacheTime;
	}

	/**
	 * setCacheTime() sets the timestamp expressing when the page has been rendered.
	 * This does not control expiry, see updateCacheExpiry() for that!
	 * @param string $t TS_MW timestamp
	 * @return string
	 */
	public function setCacheTime( $t ) {
		// NOTE: keep support for undocumented used of -1 to mean "not cacheable".
		if ( is_string( $t ) && $t !== '-1' ) {
			$t = MWTimestamp::convert( TS_MW, $t );
		}

		return wfSetVar( $this->mCacheTime, $t );
	}

	/**
	 * @since 1.23
	 * @return int|null Revision id, if any was set
	 */
	public function getCacheRevisionId() {
		return $this->mCacheRevisionId;
	}

	/**
	 * @since 1.23
	 * @param int|null $id Revision ID
	 */
	public function setCacheRevisionId( $id ) {
		$this->mCacheRevisionId = $id;
	}

	/**
	 * Sets the number of seconds after which this object should expire.
	 *
	 * This value is used with the ParserCache.
	 * If called with a value greater than the value provided at any previous call,
	 * the new call has no effect. The value returned by getCacheExpiry is smaller
	 * or equal to the smallest number that was provided as an argument to
	 * updateCacheExpiry().
	 *
	 * Avoid using 0 if at all possible. Consider JavaScript for highly dynamic content.
	 *
	 * @param int $seconds
	 */
	public function updateCacheExpiry( $seconds ) {
		$seconds = (int)$seconds;

		if ( $this->mCacheExpiry === null || $this->mCacheExpiry > $seconds ) {
			$this->mCacheExpiry = $seconds;
		}
	}

	/**
	 * Returns the number of seconds after which this object should expire.
	 * This method is used by ParserCache to determine how long the ParserOutput can be cached.
	 * The timestamp of expiry can be calculated by adding getCacheExpiry() to getCacheTime().
	 * The value returned by getCacheExpiry is smaller or equal to the smallest number
	 * that was provided to a call of updateCacheExpiry(), and smaller or equal to the
	 * value of $wgParserCacheExpireTime.
	 * @return int
	 */
	public function getCacheExpiry() {
		global $wgParserCacheExpireTime;

		// NOTE: keep support for undocumented used of -1 to mean "not cacheable".
		if ( $this->mCacheTime < 0 ) {
			return 0;
		}

		$expire = $this->mCacheExpiry;

		if ( $expire === null ) {
			$expire = $wgParserCacheExpireTime;
		} else {
			$expire = min( $expire, $wgParserCacheExpireTime );
		}

		if ( $expire <= 0 ) {
			return 0; // not cacheable
		} else {
			return $expire;
		}
	}

	/**
	 * @return bool
	 */
	public function isCacheable() {
		return $this->getCacheExpiry() > 0;
	}

	/**
	 * Return true if this cached output object predates the global or
	 * per-article cache invalidation timestamps, or if it comes from
	 * an incompatible older version.
	 *
	 * @param string $touched The affected article's last touched timestamp
	 * @return bool
	 */
	public function expired( $touched ) {
		global $wgCacheEpoch;

		$expiry = MWTimestamp::convert( TS_MW, MWTimestamp::time() - $this->getCacheExpiry() );

		return !$this->isCacheable() // parser says it's not cacheable
			|| $this->getCacheTime() < $touched
			|| $this->getCacheTime() <= $wgCacheEpoch
			|| $this->getCacheTime() < $expiry // expiry period has passed
			|| !isset( $this->mVersion )
			|| version_compare( $this->mVersion, Parser::VERSION, "lt" );
	}

	/**
	 * Return true if this cached output object is for a different revision of
	 * the page.
	 *
	 * @todo We always return false if $this->getCacheRevisionId() is null;
	 * this prevents invalidating the whole parser cache when this change is
	 * deployed. Someday that should probably be changed.
	 *
	 * @since 1.23
	 * @param int $id The affected article's current revision id
	 * @return bool
	 */
	public function isDifferentRevision( $id ) {
		$cached = $this->getCacheRevisionId();
		return $cached !== null && $id !== $cached;
	}

	/**
	 * Returns the options from its ParserOptions which have been taken
	 * into account to produce this output.
	 * @return string[]
	 */
	public function getUsedOptions() {
		// If this is set, the CacheTime public property was used
		// to set the field, thus nothing else could've set mAccessedOptions
		if ( isset( $this->mUsedOptions ) ) {
			return $this->mUsedOptions;
		}

		if ( !isset( $this->mAccessedOptions ) ) {
			return [];
		}
		return array_keys( $this->mAccessedOptions );
	}

	/**
	 * Sets the list of accessed ParserOptions which have been taken
	 * into account to produce the output stored under this key.
	 * @param array $options the list of options
	 */
	public function setUsedOptions( array $options ) {
		$this->mAccessedOptions = array_flip( $options );
		$this->mAccessedOptions = array_fill_keys( array_keys( $this->mAccessedOptions ), true );
	}
}
