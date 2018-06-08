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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Context object that contains information about the state of a specific
 * ResourceLoader web request. Passed around to ResourceLoaderModule methods.
 *
 * @ingroup ResourceLoader
 * @since 1.17
 */
class ResourceLoaderContext implements MessageLocalizer {
	const DEFAULT_LANG = 'qqx';
	const DEFAULT_SKIN = 'fallback';

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
	/** @var ResourceLoaderImage|false */
	protected $imageObj;

	/**
	 * @param ResourceLoader $resourceLoader
	 * @param WebRequest $request
	 */
	public function __construct( ResourceLoader $resourceLoader, WebRequest $request ) {
		$this->resourceLoader = $resourceLoader;
		$this->request = $request;
		$this->logger = $resourceLoader->getLogger();

		// Optimisation: Use WebRequest::getRawVal() instead of getVal(). We don't
		// need the slow Language+UTF logic meant for user input here. (f303bb9360)

		// List of modules
		$modules = $request->getRawVal( 'modules' );
		$this->modules = $modules ? ResourceLoader::expandModuleNames( $modules ) : [];

		// Various parameters
		$this->user = $request->getRawVal( 'user' );
		$this->debug = $request->getRawVal( 'debug' ) === 'true';
		$this->only = $request->getRawVal( 'only' );
		$this->version = $request->getRawVal( 'version' );
		$this->raw = $request->getFuzzyBool( 'raw' );

		// Image requests
		$this->image = $request->getRawVal( 'image' );
		$this->variant = $request->getRawVal( 'variant' );
		$this->format = $request->getRawVal( 'format' );

		$this->skin = $request->getRawVal( 'skin' );
		$skinnames = Skin::getSkinNames();
		if ( !$this->skin || !isset( $skinnames[$this->skin] ) ) {
			// The 'skin' parameter is required. (Not yet enforced.)
			// For requests without a known skin specified,
			// use MediaWiki's 'fallback' skin for skin-specific decisions.
			$this->skin = self::DEFAULT_SKIN;
		}
	}

	/**
	 * Return a dummy ResourceLoaderContext object suitable for passing into
	 * things that don't "really" need a context.
	 *
	 * Use cases:
	 * - Unit tests (deprecated, create empty instance directly or use RLTestCase).
	 *
	 * @return ResourceLoaderContext
	 */
	public static function newDummyContext() {
		// This currently creates a non-empty instance of ResourceLoader (all modules registered),
		// but that's probably not needed. So once that moves into ServiceWiring, this'll
		// become more like the EmptyResourceLoader class we have in PHPUnit tests, which
		// is what this should've had originally. If this turns out to be untrue, change to:
		// `MediaWikiServices::getInstance()->getResourceLoader()` instead.
		return new self( new ResourceLoader(
			MediaWikiServices::getInstance()->getMainConfig(),
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
	 * @deprecated since 1.34 Use ResourceLoaderModule::getConfig instead
	 * inside module methods. Use ResourceLoader::getConfig elsewhere.
	 * @return Config
	 * @codeCoverageIgnore
	 */
	public function getConfig() {
		wfDeprecated( __METHOD__, '1.34' );
		return $this->getResourceLoader()->getConfig();
	}

	/**
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @deprecated since 1.34 Use ResourceLoaderModule::getLogger instead
	 * inside module methods. Use ResourceLoader::getLogger elsewhere.
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
				// The 'lang' parameter is required. (Not yet enforced.)
				// If omitted, localise with the dummy language code.
				$lang = self::DEFAULT_LANG;
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
			$direction = $this->getRequest()->getRawVal( 'dir' );
			if ( $direction === 'ltr' || $direction === 'rtl' ) {
				$this->direction = $direction;
			} else {
				// Determine directionality based on user language (T8100)
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
	 * @param string|string[]|MessageSpecifier $key Message key, or array of keys,
	 *   or a MessageSpecifier.
	 * @param mixed ...$params
	 * @return Message
	 */
	public function msg( $key, ...$params ) {
		return wfMessage( $key, ...$params )
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
	 * Return the replaced-content mapping callback
	 *
	 * When editing a page that's used to generate the scripts or styles of a
	 * ResourceLoaderWikiModule, a preview should use the to-be-saved version of
	 * the page rather than the current version in the database. A context
	 * supporting such previews should return a callback to return these
	 * mappings here.
	 *
	 * @since 1.32
	 * @return callable|null Signature is `Content|null func( Title $t )`
	 */
	public function getContentOverrideCallback() {
		return null;
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

	/**
	 * Get the request base parameters, omitting any defaults.
	 *
	 * @internal For use by ResourceLoaderStartUpModule only
	 * @return array
	 */
	public function getReqBase() {
		$reqBase = [];
		if ( $this->getLanguage() !== self::DEFAULT_LANG ) {
			$reqBase['lang'] = $this->getLanguage();
		}
		if ( $this->getSkin() !== self::DEFAULT_SKIN ) {
			$reqBase['skin'] = $this->getSkin();
		}
		if ( $this->getDebug() ) {
			$reqBase['debug'] = 'true';
		}
		return $reqBase;
	}

	/**
	 * Wrapper around json_encode that avoids needless escapes,
	 * and pretty-prints in debug mode.
	 *
	 * @internal
	 * @param mixed $data
	 * @return string|false JSON string, false on error
	 */
	public function encodeJson( $data ) {
		// Keep output as small as possible by disabling needless escape modes
		// that PHP uses by default.
		// However, while most module scripts are only served on HTTP responses
		// for JavaScript, some modules can also be embedded in the HTML as inline
		// scripts. This, and the fact that we sometimes need to export strings
		// containing user-generated content and labels that may genuinely contain
		// a sequences like "</script>", we need to encode either '/' or '<'.
		// By default PHP escapes '/'. Let's escape '<' instead which is less common
		// and allows URLs to mostly remain readable.
		$jsonFlags = JSON_UNESCAPED_SLASHES |
			JSON_UNESCAPED_UNICODE |
			JSON_HEX_TAG |
			JSON_HEX_AMP;
		if ( $this->getDebug() ) {
			$jsonFlags |= JSON_PRETTY_PRINT;
		}
		return json_encode( $data, $jsonFlags );
	}
}
