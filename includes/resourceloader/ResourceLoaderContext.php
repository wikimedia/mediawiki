<?php
/**
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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

/**
 * Object passed around to modules which contains information about the state
 * of a specific loader request
 */
class ResourceLoaderContext {

	/* Protected Members */

	protected $resourceLoader;
	protected $request;
	protected $modules;
	protected $language;
	protected $direction;
	protected $skin;
	protected $user;
	protected $debug;
	protected $only;
	protected $version;
	protected $hash;

	/* Methods */

	/**
	 * @param $resourceLoader ResourceLoader
	 * @param $request WebRequest
	 */
	public function __construct( $resourceLoader, WebRequest $request ) {
		global $wgDefaultSkin, $wgResourceLoaderDebug;

		$this->resourceLoader = $resourceLoader;
		$this->request = $request;

		// Interpret request
		// List of modules
		$modules = $request->getVal( 'modules' );
		$this->modules   = $modules ? self::expandModuleNames( $modules ) : array();
		// Various parameters
		$this->skin      = $request->getVal( 'skin' );
		$this->user      = $request->getVal( 'user' );
		$this->debug     = $request->getFuzzyBool( 'debug', $wgResourceLoaderDebug );
		$this->only      = $request->getVal( 'only' );
		$this->version   = $request->getVal( 'version' );

		$skinnames = Skin::getSkinNames();
		// If no skin is specified, or we don't recognize the skin, use the default skin
		if ( !$this->skin || !isset( $skinnames[$this->skin] ) ) {
			$this->skin = $wgDefaultSkin;
		}
	}

	/**
	 * Expand a string of the form jquery.foo,bar|jquery.ui.baz,quux to
	 * an array of module names like array( 'jquery.foo', 'jquery.bar',
	 * 'jquery.ui.baz', 'jquery.ui.quux' )
	 * @param $modules String Packed module name list
	 * @return array of module names
	 */
	public static function expandModuleNames( $modules ) {
		$retval = array();
		// For backwards compatibility with an earlier hack, replace ! with .
		$modules = str_replace( '!', '.', $modules );
		$exploded = explode( '|', $modules );
		foreach ( $exploded as $group ) {
			if ( strpos( $group, ',' ) === false ) {
				// This is not a set of modules in foo.bar,baz notation
				// but a single module
				$retval[] = $group;
			} else {
				// This is a set of modules in foo.bar,baz notation
				$pos = strrpos( $group, '.' );
				if ( $pos === false ) {
					// Prefixless modules, i.e. without dots
					$retval = explode( ',', $group );
				} else {
					// We have a prefix and a bunch of suffixes
					$prefix = substr( $group, 0, $pos ); // 'foo'
					$suffixes = explode( ',', substr( $group, $pos + 1 ) ); // array( 'bar', 'baz' )
					foreach ( $suffixes as $suffix ) {
						$retval[] = "$prefix.$suffix";
					}
				}
			}
		}
		return $retval;
	}

	/**
	 * Return a dummy ResourceLoaderContext object suitable for passing into things that don't "really" need a context
	 * @return ResourceLoaderContext
	 */
	public static function newDummyContext() {
		return new self( null, new FauxRequest( array() ) );
	}

	/**
	 * @return ResourceLoader
	 */
	public function getResourceLoader() {
		return $this->resourceLoader;
	}

	/**
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @return array
	 */
	public function getModules() {
		return $this->modules;
	}

	/**
	 * @return string
	 */
	public function getLanguage() {
		if ( $this->language === null ) {
			global $wgLang;
			$this->language  = $this->request->getVal( 'lang' );
			if ( !$this->language ) {
				$this->language = $wgLang->getCode();
			}
		}
		return $this->language;
	}

	/**
	 * @return string
	 */
	public function getDirection() {
		if ( $this->direction === null ) {
			$this->direction = $this->request->getVal( 'dir' );
			if ( !$this->direction ) {
				# directionality based on user language (see bug 6100)
				$this->direction = Language::factory( $this->language )->getDir();
			}
		}
		return $this->direction;
	}

	/**
	 * @return string|null
	 */
	public function getSkin() {
		return $this->skin;
	}

	/**
	 * @return string|null
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return bool
	 */
	public function getDebug() {
		return $this->debug;
	}

	/**
	 * @return String|null
	 */
	public function getOnly() {
		return $this->only;
	}

	/**
	 * @return String|null
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @return bool
	 */
	public function shouldIncludeScripts() {
		return is_null( $this->only ) || $this->only === 'scripts';
	}

	/**
	 * @return bool
	 */
	public function shouldIncludeStyles() {
		return is_null( $this->only ) || $this->only === 'styles';
	}

	/**
	 * @return bool
	 */
	public function shouldIncludeMessages() {
		return is_null( $this->only ) || $this->only === 'messages';
	}

	/**
	 * @return string
	 */
	public function getHash() {
		if ( !isset( $this->hash ) ) {
			$this->hash = implode( '|', array(
				$this->getLanguage(), $this->getDirection(), $this->skin, $this->user,
				$this->debug, $this->only, $this->version
			) );
		}
		return $this->hash;
	}
}
