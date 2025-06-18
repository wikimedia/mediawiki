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

namespace MediaWiki\ResourceLoader;

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserRigorOptions;
use MessageLocalizer;
use Psr\Log\LoggerInterface;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * Context object that contains information about the state of a specific
 * ResourceLoader web request. Passed around to Module methods.
 *
 * @ingroup ResourceLoader
 * @since 1.17
 */
class Context implements MessageLocalizer {
	public const DEFAULT_LANG = 'qqx';
	public const DEFAULT_SKIN = 'fallback';

	/** @internal For use in ResourceLoader classes. */
	public const DEBUG_OFF = 0;
	/** @internal For use in SpecialJavaScriptTest. */
	public const DEBUG_MAIN = 2;

	/** @var ResourceLoader */
	protected $resourceLoader;
	/** @var WebRequest */
	protected $request;
	/** @var LoggerInterface */
	protected $logger;

	// Module content vary
	/** @var string */
	protected $skin;
	/** @var string */
	protected $language;
	/** @var int */
	protected $debug;
	/** @var string|null */
	protected $user;

	// Request vary (in addition to cache vary)
	/** @var string[] */
	protected $modules;
	/** @var string|null */
	protected $only;
	/** @var string|null */
	protected $version;
	/** @var bool */
	protected $raw;
	/** @var bool */
	protected $sourcemap;
	/** @var string|null */
	protected $image;
	/** @var string|null */
	protected $variant;
	/** @var string|null */
	protected $format;

	/** @var string|null */
	protected $direction;
	/** @var string|null */
	protected $hash;
	/** @var User|null */
	protected $userObj;
	/** @var UserIdentity|null|false */
	protected $userIdentity = false;
	/** @var Image|false */
	protected $imageObj;

	/**
	 * @param ResourceLoader $resourceLoader
	 * @param WebRequest $request
	 * @param string[]|null $validSkins List of valid skin names. If not passed,
	 *   any skin name is considered valid. Invalid skins are replaced by the default.
	 */
	public function __construct(
		ResourceLoader $resourceLoader, WebRequest $request, $validSkins = null
	) {
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
		$this->debug = self::debugFromString( $request->getRawVal( 'debug' ) );
		$this->only = $request->getRawVal( 'only' );
		$this->version = $request->getRawVal( 'version' );
		$this->raw = $request->getFuzzyBool( 'raw' );
		$this->sourcemap = $request->getFuzzyBool( 'sourcemap' );

		// Image requests
		$this->image = $request->getRawVal( 'image' );
		$this->variant = $request->getRawVal( 'variant' );
		$this->format = $request->getRawVal( 'format' );

		$skin = $request->getRawVal( 'skin' );
		if (
			$skin === null
			|| ( is_array( $validSkins ) && !in_array( $skin, $validSkins ) )
		) {
			// For requests without a known skin specified,
			// use MediaWiki's 'fallback' skin for any skin-specific decisions.
			$skin = self::DEFAULT_SKIN;
		}
		$this->skin = $skin;
	}

	/**
	 * @internal For use in ResourceLoader::inDebugMode
	 * @param string|null $debug
	 * @return int
	 */
	public static function debugFromString( ?string $debug ): int {
		// The canonical way to enable debug mode is via debug=true
		if ( $debug === 'true' || $debug === '2' ) {
			$ret = self::DEBUG_MAIN;
		} else {
			$ret = self::DEBUG_OFF;
		}

		return $ret;
	}

	/**
	 * Return a dummy Context object suitable for passing into
	 * things that don't "really" need a context.
	 *
	 * Use cases:
	 * - Unit tests (deprecated, create empty instance directly or use RLTestCase).
	 */
	public static function newDummyContext(): Context {
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

	public function getResourceLoader(): ResourceLoader {
		return $this->resourceLoader;
	}

	public function getRequest(): WebRequest {
		return $this->request;
	}

	/**
	 * @deprecated since 1.34 Use Module::getLogger instead
	 * inside module methods. Use ResourceLoader::getLogger elsewhere.
	 * @since 1.27
	 * @return LoggerInterface
	 */
	public function getLogger(): LoggerInterface {
		return $this->logger;
	}

	public function getModules(): array {
		return $this->modules;
	}

	public function getLanguage(): string {
		if ( $this->language === null ) {
			// Must be a valid language code after this point (T64849)
			// Only support uselang values that follow built-in conventions (T102058)
			$lang = $this->getRequest()->getRawVal( 'lang' ) ?? '';
			// Stricter version of RequestContext::sanitizeLangCode()
			$validBuiltinCode = MediaWikiServices::getInstance()->getLanguageNameUtils()
				->isValidBuiltInCode( $lang );
			if ( !$validBuiltinCode ) {
				// The 'lang' parameter is required. (Not yet enforced.)
				// If omitted, localise with the dummy language code.
				$lang = self::DEFAULT_LANG;
			}
			$this->language = $lang;
		}
		return $this->language;
	}

	public function getDirection(): string {
		if ( $this->direction === null ) {
			// Determine directionality based on user language (T8100)
			$this->direction = MediaWikiServices::getInstance()->getLanguageFactory()
				->getLanguage( $this->getLanguage() )->getDir();
		}
		return $this->direction;
	}

	public function getSkin(): string {
		return $this->skin;
	}

	public function getUser(): ?string {
		return $this->user;
	}

	/**
	 * Get a Message object with context set.  See wfMessage for parameters.
	 *
	 * @since 1.27
	 * @param string|string[]|MessageSpecifier $key Message key, or array of keys,
	 *   or a MessageSpecifier.
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return Message
	 */
	public function msg( $key, ...$params ): Message {
		return wfMessage( $key, ...$params )
			// Do not use MediaWiki user language from session. Use the provided one instead.
			->inLanguage( $this->getLanguage() )
			// inLanguage() clears the interface flag, so we need re-enable it. (T291601)
			->setInterfaceMessageFlag( true )
			// Use a dummy title because there is no real title for this endpoint, and the cache won't
			// vary on it anyways.
			->page( PageReferenceValue::localReference( NS_SPECIAL, 'Badtitle/ResourceLoaderContext' ) );
	}

	/**
	 * Get the possibly-cached UserIdentity object for the specified username
	 *
	 * This will be null on most requests,
	 * except for load.php requests that have a 'user' parameter set.
	 *
	 * @since 1.38
	 * @return UserIdentity|null
	 */
	public function getUserIdentity(): ?UserIdentity {
		if ( $this->userIdentity === false ) {
			$username = $this->getUser();
			if ( $username === null ) {
				// Anonymous user
				$this->userIdentity = null;
			} else {
				// Use provided username if valid
				$this->userIdentity = MediaWikiServices::getInstance()
					->getUserFactory()
					->newFromName( $username, UserRigorOptions::RIGOR_VALID );
			}
		}
		return $this->userIdentity;
	}

	/**
	 * Get the possibly-cached User object for the specified username
	 *
	 * @since 1.25
	 * @return User
	 */
	public function getUserObj(): User {
		if ( $this->userObj === null ) {
			$username = $this->getUser();
			$userFactory = MediaWikiServices::getInstance()->getUserFactory();
			if ( $username ) {
				// Use provided username if valid, fallback to anonymous user
				$this->userObj = $userFactory->newFromName( $username, UserRigorOptions::RIGOR_VALID );
			}
			// Anonymous user
			$this->userObj ??= $userFactory->newAnonymous();
		}

		return $this->userObj;
	}

	public function getDebug(): int {
		return $this->debug;
	}

	public function getOnly(): ?string {
		return $this->only;
	}

	/**
	 * @see Module::getVersionHash
	 * @see ClientHtml::makeLoad
	 * @return string|null
	 */
	public function getVersion(): ?string {
		return $this->version;
	}

	public function getRaw(): bool {
		return $this->raw;
	}

	/**
	 * @since 1.41
	 * @return bool
	 */
	public function isSourceMap(): bool {
		return $this->sourcemap;
	}

	public function getImage(): ?string {
		return $this->image;
	}

	public function getVariant(): ?string {
		return $this->variant;
	}

	public function getFormat(): ?string {
		return $this->format;
	}

	/**
	 * If this is a request for an image, get the Image object.
	 *
	 * @since 1.25
	 * @return Image|false false if a valid object cannot be created
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
			if ( !$module || !$module instanceof ImageModule ) {
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
	 * WikiModule, a preview should use the to-be-saved version of
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

	public function shouldIncludeScripts(): bool {
		return $this->getOnly() === null || $this->getOnly() === 'scripts';
	}

	public function shouldIncludeStyles(): bool {
		return $this->getOnly() === null || $this->getOnly() === 'styles';
	}

	public function shouldIncludeMessages(): bool {
		return $this->getOnly() === null;
	}

	/**
	 * All factors that uniquely identify this request, except 'modules'.
	 *
	 * The list of modules is excluded here for legacy reasons as most callers already
	 * split up handling of individual modules. Including it here would massively fragment
	 * the cache and decrease its usefulness.
	 *
	 * E.g. Used by RequestFileCache to form a cache key for storing the response output.
	 */
	public function getHash(): string {
		if ( $this->hash === null ) {
			$this->hash = implode( '|', [
				// Module content vary
				$this->getLanguage(),
				$this->getSkin(),
				(string)$this->getDebug(),
				$this->getUser() ?? '',
				// Request vary
				$this->getOnly() ?? '',
				$this->getVersion() ?? '',
				(string)$this->getRaw(),
				$this->getImage() ?? '',
				$this->getVariant() ?? '',
				$this->getFormat() ?? '',
			] );
		}
		return $this->hash;
	}

	/**
	 * Get the request base parameters, omitting any defaults.
	 *
	 * @internal For use by StartUpModule only
	 * @return string[]
	 */
	public function getReqBase(): array {
		$reqBase = [];
		$lang = $this->getLanguage();
		if ( $lang !== self::DEFAULT_LANG ) {
			$reqBase['lang'] = $lang;
		}
		$skin = $this->getSkin();
		if ( $skin !== self::DEFAULT_SKIN ) {
			$reqBase['skin'] = $skin;
		}
		$debug = $this->getDebug();
		if ( $debug !== self::DEBUG_OFF ) {
			$reqBase['debug'] = strval( $debug );
		}
		return $reqBase;
	}

	/**
	 * Wrapper around json_encode that avoids needless escapes,
	 * and pretty-prints in debug mode.
	 *
	 * @since 1.34
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
			JSON_PARTIAL_OUTPUT_ON_ERROR |
			JSON_HEX_TAG |
			JSON_HEX_AMP;
		if ( $this->getDebug() ) {
			$jsonFlags |= JSON_PRETTY_PRINT;
		}
		$json = json_encode( $data, $jsonFlags );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			trigger_error( __METHOD__ . ' partially failed: ' . json_last_error_msg(), E_USER_WARNING );
		}
		return $json;
	}
}
