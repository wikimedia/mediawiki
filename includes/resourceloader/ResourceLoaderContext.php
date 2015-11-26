<?php
/**
 * Context for resource loader modules.
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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

use MediaWiki\Logger\LoggerFactory;

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
	protected $raw;
	protected $image;
	protected $variant;
	protected $format;
	protected $userObj;
	protected $imageObj;

	/* Methods */

	/**
	 * @param ResourceLoader $resourceLoader
	 * @param WebRequest $request
	 */
	public function __construct( ResourceLoader $resourceLoader, WebRequest $request ) {
		$this->resourceLoader = $resourceLoader;
		$this->request = $request;

		// List of modules
		$modules = $request->getVal( 'modules' );
		$this->modules = $modules ? self::expandModuleNames( $modules ) : array();

		// Various parameters
		$this->user = $request->getVal( 'user' );
		$this->debug = $request->getFuzzyBool(
			'debug',
			$resourceLoader->getConfig()->get( 'ResourceLoaderDebug' )
		);
		$this->only = $request->getVal( 'only', null );
		$this->version = $request->getVal( 'version', null );
		$this->raw = $request->getFuzzyBool( 'raw' );

		// Image requests
		$this->image = $request->getVal( 'image' );
		$this->variant = $request->getVal( 'variant' );
		$this->format = $request->getVal( 'format' );

		$this->skin = $request->getVal( 'skin' );
		$skinnames = Skin::getSkinNames();
		// If no skin is specified, or we don't recognize the skin, use the default skin
		if ( !$this->skin || !isset( $skinnames[$this->skin] ) ) {
			$this->skin = $resourceLoader->getConfig()->get( 'DefaultSkin' );
		}
	}

	/**
	 * Expand a string of the form jquery.foo,bar|jquery.ui.baz,quux to
	 * an array of module names like array( 'jquery.foo', 'jquery.bar',
	 * 'jquery.ui.baz', 'jquery.ui.quux' )
	 * @param string $modules Packed module name list
	 * @return array Array of module names
	 */
	public static function expandModuleNames( $modules ) {
		$retval = array();
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
					$retval = array_merge( $retval, explode( ',', $group ) );
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
	 * Return a dummy ResourceLoaderContext object suitable for passing into
	 * things that don't "really" need a context.
	 * @return ResourceLoaderContext
	 */
	public static function newDummyContext() {
		return new self( new ResourceLoader(
			ConfigFactory::getDefaultInstance()->makeConfig( 'main' ),
			LoggerFactory::getInstance( 'resourceloader' )
		), new FauxRequest( array() ) );
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
			// Must be a valid language code after this point (bug 62849)
			$this->language = RequestContext::sanitizeLangCode( $this->getRequest()->getVal( 'lang' ) );
		}
		return $this->language;
	}

	/**
	 * @return string
	 */
	public function getDirection() {
		if ( $this->direction === null ) {
			$this->direction = $this->getRequest()->getVal( 'dir' );
			if ( !$this->direction ) {
				// Determine directionality based on user language (bug 6100)
				$this->direction = Language::factory( $this->getLanguage() )->getDir();
			}
		}
		return $this->direction;
	}

	/**
	 * @return string
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
	 * Get the possibly-cached User object for the specified username
	 *
	 * @since 1.25
	 * @return User|bool false if a valid object cannot be created
	 */
	public function getUserObj() {
		if ( $this->userObj === null ) {
			$username = $this->getUser();
			if ( $username ) {
				// Optimize: Avoid loading a new User object if possible
				global $wgUser;
				if ( is_object( $wgUser ) && $wgUser->getName() === $username ) {
					$this->userObj = $wgUser;
				} else {
					$this->userObj = User::newFromName( $username );
				}
			} else {
				$this->userObj = new User; // Anonymous user
			}
		}

		return $this->userObj;
	}

	/**
	 * @return bool
	 */
	public function getDebug() {
		return $this->debug;
	}

	/**
	 * @return string|null
	 */
	public function getOnly() {
		return $this->only;
	}

	/**
	 * @see ResourceLoaderModule::getVersionHash
	 * @see OutputPage::makeResourceLoaderLink
	 * @return string|null
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @return bool
	 */
	public function getRaw() {
		return $this->raw;
	}

	/**
	 * @return string|null
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @return string|null
	 */
	public function getVariant() {
		return $this->variant;
	}

	/**
	 * @return string|null
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * If this is a request for an image, get the ResourceLoaderImage object.
	 *
	 * @since 1.25
	 * @return ResourceLoaderImage|bool false if a valid object cannot be created
	 */
	public function getImageObj() {
		if ( $this->imageObj === null ) {
			$this->imageObj = false;

			if ( !$this->image ) {
				return $this->imageObj;
			}

			$modules = $this->getModules();
			if ( count( $modules ) !== 1 ) {
				return $this->imageObj;
			}

			$module = $this->getResourceLoader()->getModule( $modules[0] );
			if ( !$module || !$module instanceof ResourceLoaderImageModule ) {
				return $this->imageObj;
			}

			$image = $module->getImage( $this->image, $this );
			if ( !$image ) {
				return $this->imageObj;
			}

			$this->imageObj = $image;
		}

		return $this->imageObj;
	}

	/**
	 * @return bool
	 */
	public function shouldIncludeScripts() {
		return $this->getOnly() === null || $this->getOnly() === 'scripts';
	}

	/**
	 * @return bool
	 */
	public function shouldIncludeStyles() {
		return $this->getOnly() === null || $this->getOnly() === 'styles';
	}

	/**
	 * @return bool
	 */
	public function shouldIncludeMessages() {
		return $this->getOnly() === null;
	}

	/**
	 * @return string
	 */
	public function getHash() {
		if ( !isset( $this->hash ) ) {
			$this->hash = implode( '|', array(
				$this->getLanguage(), $this->getDirection(), $this->getSkin(), $this->getUser(),
				$this->getImage(), $this->getVariant(), $this->getFormat(),
				$this->getDebug(), $this->getOnly(), $this->getVersion()
			) );
		}
		return $this->hash;
	}
}
