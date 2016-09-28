<?php
/**
 * Context for ResourceLoader modules.
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
 * of a specific loader request.
 */
class ResourceLoaderContext {
	protected $resourceLoader;
	protected $request;
	protected $logger;

	// Module content vary
	protected $skin;
	protected $language;
	protected $debug;
	protected $user;

	// Request vary (in addition to cache vary)
	protected $modules;
	protected $only;
	protected $version;
	protected $raw;
	protected $image;
	protected $variant;
	protected $format;

	protected $direction;
	protected $hash;
	protected $userObj;
	protected $imageObj;

	/**
	 * @param ResourceLoader $resourceLoader
	 * @param WebRequest $request
	 */
	public function __construct( ResourceLoader $resourceLoader, WebRequest $request ) {
		$this->resourceLoader = $resourceLoader;
		$this->request = $request;
		$this->logger = $resourceLoader->getLogger();

		// Future developers: Avoid use of getVal() in this class, which performs
		// expensive UTF normalisation by default. Use getRawVal() instead.
		// Values here are either one of a finite number of internal IDs,
		// or previously-stored user input (e.g. titles, user names) that were passed
		// to this endpoint by ResourceLoader itself from the canonical value.
		// Values do not come directly from user input and need not match.

		// List of modules
		$modules = $request->getRawVal( 'modules' );
		$this->modules = $modules ? self::expandModuleNames( $modules ) : [];

		// Various parameters
		$this->user = $request->getRawVal( 'user' );
		$this->debug = $request->getFuzzyBool(
			'debug',
			$resourceLoader->getConfig()->get( 'ResourceLoaderDebug' )
		);
		$this->only = $request->getRawVal( 'only', null );
		$this->version = $request->getRawVal( 'version', null );
		$this->raw = $request->getFuzzyBool( 'raw' );

		// Image requests
		$this->image = $request->getRawVal( 'image' );
		$this->variant = $request->getRawVal( 'variant' );
		$this->format = $request->getRawVal( 'format' );

		$this->skin = $request->getRawVal( 'skin' );
		$skinnames = Skin::getSkinNames();
		// If no skin is specified, or we don't recognize the skin, use the default skin
		if ( !$this->skin || !isset( $skinnames[$this->skin] ) ) {
			$this->skin = $resourceLoader->getConfig()->get( 'DefaultSkin' );
		}
	}

	/**
	 * Expand a string of the form jquery.foo,bar|jquery.ui.baz,quux to
	 * an array of module names like [ 'jquery.foo', 'jquery.bar',
	 * 'jquery.ui.baz', 'jquery.ui.quux' ]
	 * @param string $modules Packed module name list
	 * @return array Array of module names
	 */
	public static function expandModuleNames( $modules ) {
		$retval = [];
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
					$suffixes = explode( ',', substr( $group, $pos + 1 ) ); // [ 'bar', 'baz' ]
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
		), new FauxRequest( [] ) );
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
	 * @since 1.27
	 * @return \Psr\Log\LoggerInterface
	 */
	public function getLogger() {
		return $this->logger;
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
			// Must be a valid language code after this point (T64849)
			// Only support uselang values that follow built-in conventions (T102058)
			$lang = $this->getRequest()->getRawVal( 'lang', '' );
			// Stricter version of RequestContext::sanitizeLangCode()
			if ( !Language::isValidBuiltInCode( $lang ) ) {
				wfDebug( "Invalid user language code\n" );
				$lang = $this->getResourceLoader()->getConfig()->get( 'LanguageCode' );
			}
			$this->language = $lang;
		}
		return $this->language;
	}

	/**
	 * @return string
	 */
	public function getDirection() {
		if ( $this->direction === null ) {
			$this->direction = $this->getRequest()->getRawVal( 'dir' );
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
	 * Get a Message object with context set.  See wfMessage for parameters.
	 *
	 * @since 1.27
	 * @param mixed ...
	 * @return Message
	 */
	public function msg() {
		return call_user_func_array( 'wfMessage', func_get_args() )
			->inLanguage( $this->getLanguage() )
			// Use a dummy title because there is no real title
			// for this endpoint, and the cache won't vary on it
			// anyways.
			->title( Title::newFromText( 'Dwimmerlaik' ) );
	}

	/**
	 * Get the possibly-cached User object for the specified username
	 *
	 * @since 1.25
	 * @return User
	 */
	public function getUserObj() {
		if ( $this->userObj === null ) {
			$username = $this->getUser();
			if ( $username ) {
				// Use provided username if valid, fallback to anonymous user
				$this->userObj = User::newFromName( $username ) ?: new User;
			} else {
				// Anonymous user
				$this->userObj = new User;
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
	 * @see ResourceLoaderClientHtml::makeLoad
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
	 * All factors that uniquely identify this request, except 'modules'.
	 *
	 * The list of modules is excluded here for legacy reasons as most callers already
	 * split up handling of individual modules. Including it here would massively fragment
	 * the cache and decrease its usefulness.
	 *
	 * E.g. Used by RequestFileCache to form a cache key for storing the reponse output.
	 *
	 * @return string
	 */
	public function getHash() {
		if ( !isset( $this->hash ) ) {
			$this->hash = implode( '|', [
				// Module content vary
				$this->getLanguage(),
				$this->getSkin(),
				$this->getDebug(),
				$this->getUser(),
				// Request vary
				$this->getOnly(),
				$this->getVersion(),
				$this->getRaw(),
				$this->getImage(),
				$this->getVariant(),
				$this->getFormat(),
			] );
		}
		return $this->hash;
	}
}
