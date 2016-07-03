<?php
/**
 * Data caching with dependencies.
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
 * @ingroup Cache
 */

/**
 * This class stores an arbitrary value along with its dependencies.
 * Users should typically only use DependencyWrapper::getValueFromCache(),
 * rather than instantiating one of these objects directly.
 * @ingroup Cache
 */
class DependencyWrapper {
	private $value;
	/** @var CacheDependency[] */
	private $deps;

	/**
	 * Create an instance.
	 * @param mixed $value The user-supplied value
	 * @param CacheDependency|CacheDependency[] $deps A dependency or dependency
	 *   array. All dependencies must be objects implementing CacheDependency.
	 */
	function __construct( $value = false, $deps = [] ) {
		$this->value = $value;

		if ( !is_array( $deps ) ) {
			$deps = [ $deps ];
		}

		$this->deps = $deps;
	}

	/**
	 * Returns true if any of the dependencies have expired
	 *
	 * @return bool
	 */
	function isExpired() {
		foreach ( $this->deps as $dep ) {
			if ( $dep->isExpired() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Initialise dependency values in preparation for storing. This must be
	 * called before serialization.
	 */
	function initialiseDeps() {
		foreach ( $this->deps as $dep ) {
			$dep->loadDependencyValues();
		}
	}

	/**
	 * Get the user-defined value
	 * @return bool|mixed
	 */
	function getValue() {
		return $this->value;
	}

	/**
	 * Store the wrapper to a cache
	 *
	 * @param BagOStuff $cache
	 * @param string $key
	 * @param int $expiry
	 */
	function storeToCache( $cache, $key, $expiry = 0 ) {
		$this->initialiseDeps();
		$cache->set( $key, $this, $expiry );
	}

	/**
	 * Attempt to get a value from the cache. If the value is expired or missing,
	 * it will be generated with the callback function (if present), and the newly
	 * calculated value will be stored to the cache in a wrapper.
	 *
	 * @param BagOStuff $cache A cache object
	 * @param string $key The cache key
	 * @param int $expiry The expiry timestamp or interval in seconds
	 * @param bool|callable $callback The callback for generating the value, or false
	 * @param array $callbackParams The function parameters for the callback
	 * @param array $deps The dependencies to store on a cache miss. Note: these
	 *    are not the dependencies used on a cache hit! Cache hits use the stored
	 *    dependency array.
	 *
	 * @return mixed The value, or null if it was not present in the cache and no
	 *    callback was defined.
	 */
	static function getValueFromCache( $cache, $key, $expiry = 0, $callback = false,
		$callbackParams = [], $deps = []
	) {
		$obj = $cache->get( $key );

		if ( is_object( $obj ) && $obj instanceof DependencyWrapper && !$obj->isExpired() ) {
			$value = $obj->value;
		} elseif ( $callback ) {
			$value = call_user_func_array( $callback, $callbackParams );
			# Cache the newly-generated value
			$wrapper = new DependencyWrapper( $value, $deps );
			$wrapper->storeToCache( $cache, $key, $expiry );
		} else {
			$value = null;
		}

		return $value;
	}
}

/**
 * @ingroup Cache
 */
abstract class CacheDependency {
	/**
	 * Returns true if the dependency is expired, false otherwise
	 */
	abstract function isExpired();

	/**
	 * Hook to perform any expensive pre-serialize loading of dependency values.
	 */
	function loadDependencyValues() {
	}
}

/**
 * @ingroup Cache
 */
class FileDependency extends CacheDependency {
	private $filename;
	private $timestamp;

	/**
	 * Create a file dependency
	 *
	 * @param string $filename The name of the file, preferably fully qualified
	 * @param null|bool|int $timestamp The unix last modified timestamp, or false if the
	 *        file does not exist. If omitted, the timestamp will be loaded from
	 *        the file.
	 *
	 * A dependency on a nonexistent file will be triggered when the file is
	 * created. A dependency on an existing file will be triggered when the
	 * file is changed.
	 */
	function __construct( $filename, $timestamp = null ) {
		$this->filename = $filename;
		$this->timestamp = $timestamp;
	}

	/**
	 * @return array
	 */
	function __sleep() {
		$this->loadDependencyValues();

		return [ 'filename', 'timestamp' ];
	}

	function loadDependencyValues() {
		if ( is_null( $this->timestamp ) ) {
			MediaWiki\suppressWarnings();
			# Dependency on a non-existent file stores "false"
			# This is a valid concept!
			$this->timestamp = filemtime( $this->filename );
			MediaWiki\restoreWarnings();
		}
	}

	/**
	 * @return bool
	 */
	function isExpired() {
		MediaWiki\suppressWarnings();
		$lastmod = filemtime( $this->filename );
		MediaWiki\restoreWarnings();
		if ( $lastmod === false ) {
			if ( $this->timestamp === false ) {
				# Still nonexistent
				return false;
			} else {
				# Deleted
				wfDebug( "Dependency triggered: {$this->filename} deleted.\n" );

				return true;
			}
		} else {
			if ( $lastmod > $this->timestamp ) {
				# Modified or created
				wfDebug( "Dependency triggered: {$this->filename} changed.\n" );

				return true;
			} else {
				# Not modified
				return false;
			}
		}
	}
}

/**
 * @ingroup Cache
 */
class GlobalDependency extends CacheDependency {
	private $name;
	private $value;

	function __construct( $name ) {
		$this->name = $name;
		$this->value = $GLOBALS[$name];
	}

	/**
	 * @return bool
	 */
	function isExpired() {
		if ( !isset( $GLOBALS[$this->name] ) ) {
			return true;
		}

		return $GLOBALS[$this->name] != $this->value;
	}
}

/**
 * @ingroup Cache
 */
class ConstantDependency extends CacheDependency {
	private $name;
	private $value;

	function __construct( $name ) {
		$this->name = $name;
		$this->value = constant( $name );
	}

	/**
	 * @return bool
	 */
	function isExpired() {
		return constant( $this->name ) != $this->value;
	}
}
