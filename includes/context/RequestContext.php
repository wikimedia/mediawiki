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
 * @since 1.18
 *
 * @author Alexandre Emsenhuber
 * @author Daniel Friesen
 * @file
 */

use Liuggio\StatsdClient\Factory\StatsdDataFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\ScopedCallback;

/**
 * Group all the pieces relevant to the context of a request into one instance
 */
class RequestContext implements IContextSource, MutableContext {
	/**
	 * @var WebRequest
	 */
	private $request;

	/**
	 * @var Title
	 */
	private $title;

	/**
	 * @var WikiPage
	 */
	private $wikipage;

	/**
	 * @var OutputPage
	 */
	private $output;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var Language
	 */
	private $lang;

	/**
	 * @var Skin
	 */
	private $skin;

	/**
	 * @var Timing
	 */
	private $timing;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var RequestContext
	 */
	private static $instance = null;

	/**
	 * Set the Config object
	 *
	 * @param Config $c
	 */
	public function setConfig( Config $c ) {
		$this->config = $c;
	}

	/**
	 * Get the Config object
	 *
	 * @return Config
	 */
	public function getConfig() {
		if ( $this->config === null ) {
			// @todo In the future, we could move this to WebStart.php so
			// the Config object is ready for when initialization happens
			$this->config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		}

		return $this->config;
	}

	/**
	 * Set the WebRequest object
	 *
	 * @param WebRequest $r
	 */
	public function setRequest( WebRequest $r ) {
		$this->request = $r;
	}

	/**
	 * Get the WebRequest object
	 *
	 * @return WebRequest
	 */
	public function getRequest() {
		if ( $this->request === null ) {
			global $wgCommandLineMode;
			// create the WebRequest object on the fly
			if ( $wgCommandLineMode ) {
				$this->request = new FauxRequest( [] );
			} else {
				$this->request = new WebRequest();
			}
		}

		return $this->request;
	}

	/**
	 * Get the Stats object
	 *
	 * @deprecated since 1.27 use a StatsdDataFactory from MediaWikiServices (preferably injected)
	 *
	 * @return StatsdDataFactory
	 */
	public function getStats() {
		return MediaWikiServices::getInstance()->getStatsdDataFactory();
	}

	/**
	 * Get the timing object
	 *
	 * @return Timing
	 */
	public function getTiming() {
		if ( $this->timing === null ) {
			$this->timing = new Timing( [
				'logger' => LoggerFactory::getInstance( 'Timing' )
			] );
		}
		return $this->timing;
	}

	/**
	 * Set the Title object
	 *
	 * @param Title|null $title
	 */
	public function setTitle( Title $title = null ) {
		$this->title = $title;
		// Erase the WikiPage so a new one with the new title gets created.
		$this->wikipage = null;
	}

	/**
	 * Get the Title object
	 *
	 * @return Title|null
	 */
	public function getTitle() {
		if ( $this->title === null ) {
			global $wgTitle; # fallback to $wg till we can improve this
			$this->title = $wgTitle;
			wfDebugLog(
				'GlobalTitleFail',
				__METHOD__ . ' called by ' . wfGetAllCallers( 5 ) . ' with no title set.'
			);
		}

		return $this->title;
	}

	/**
	 * Check, if a Title object is set
	 *
	 * @since 1.25
	 * @return bool
	 */
	public function hasTitle() {
		return $this->title !== null;
	}

	/**
	 * Check whether a WikiPage object can be get with getWikiPage().
	 * Callers should expect that an exception is thrown from getWikiPage()
	 * if this method returns false.
	 *
	 * @since 1.19
	 * @return bool
	 */
	public function canUseWikiPage() {
		if ( $this->wikipage ) {
			// If there's a WikiPage object set, we can for sure get it
			return true;
		}
		// Only pages with legitimate titles can have WikiPages.
		// That usually means pages in non-virtual namespaces.
		$title = $this->getTitle();
		return $title ? $title->canExist() : false;
	}

	/**
	 * Set the WikiPage object
	 *
	 * @since 1.19
	 * @param WikiPage $p
	 */
	public function setWikiPage( WikiPage $p ) {
		$pageTitle = $p->getTitle();
		if ( !$this->hasTitle() || !$pageTitle->equals( $this->getTitle() ) ) {
			$this->setTitle( $pageTitle );
		}
		// Defer this to the end since setTitle sets it to null.
		$this->wikipage = $p;
	}

	/**
	 * Get the WikiPage object.
	 * May throw an exception if there's no Title object set or the Title object
	 * belongs to a special namespace that doesn't have WikiPage, so use first
	 * canUseWikiPage() to check whether this method can be called safely.
	 *
	 * @since 1.19
	 * @throws MWException
	 * @return WikiPage
	 */
	public function getWikiPage() {
		if ( $this->wikipage === null ) {
			$title = $this->getTitle();
			if ( $title === null ) {
				throw new MWException( __METHOD__ . ' called without Title object set' );
			}
			$this->wikipage = WikiPage::factory( $title );
		}

		return $this->wikipage;
	}

	/**
	 * @param OutputPage $o
	 */
	public function setOutput( OutputPage $o ) {
		$this->output = $o;
	}

	/**
	 * Get the OutputPage object
	 *
	 * @return OutputPage
	 */
	public function getOutput() {
		if ( $this->output === null ) {
			$this->output = new OutputPage( $this );
		}

		return $this->output;
	}

	/**
	 * Set the User object
	 *
	 * @param User $u
	 */
	public function setUser( User $u ) {
		$this->user = $u;
	}

	/**
	 * Get the User object
	 *
	 * @return User
	 */
	public function getUser() {
		if ( $this->user === null ) {
			$this->user = User::newFromSession( $this->getRequest() );
		}

		return $this->user;
	}

	/**
	 * Accepts a language code and ensures it's sane. Outputs a cleaned up language
	 * code and replaces with $wgLanguageCode if not sane.
	 * @param string $code Language code
	 * @return string
	 */
	public static function sanitizeLangCode( $code ) {
		global $wgLanguageCode;

		// BCP 47 - letter case MUST NOT carry meaning
		$code = strtolower( $code );

		# Validate $code
		if ( !$code || !Language::isValidCode( $code ) || $code === 'qqq' ) {
			wfDebug( "Invalid user language code\n" );
			$code = $wgLanguageCode;
		}

		return $code;
	}

	/**
	 * Set the Language object
	 *
	 * @param Language|string $l Language instance or language code
	 * @throws MWException
	 * @since 1.19
	 */
	public function setLanguage( $l ) {
		if ( $l instanceof Language ) {
			$this->lang = $l;
		} elseif ( is_string( $l ) ) {
			$l = self::sanitizeLangCode( $l );
			$obj = Language::factory( $l );
			$this->lang = $obj;
		} else {
			throw new MWException( __METHOD__ . " was passed an invalid type of data." );
		}
	}

	/**
	 * Get the Language object.
	 * Initialization of user or request objects can depend on this.
	 * @return Language
	 * @throws Exception
	 * @since 1.19
	 */
	public function getLanguage() {
		if ( isset( $this->recursion ) ) {
			trigger_error( "Recursion detected in " . __METHOD__, E_USER_WARNING );
			$e = new Exception;
			wfDebugLog( 'recursion-guard', "Recursion detected:\n" . $e->getTraceAsString() );

			$code = $this->getConfig()->get( 'LanguageCode' ) ?: 'en';
			$this->lang = Language::factory( $code );
		} elseif ( $this->lang === null ) {
			$this->recursion = true;

			global $wgContLang;

			try {
				$request = $this->getRequest();
				$user = $this->getUser();

				$code = $request->getVal( 'uselang', 'user' );
				if ( $code === 'user' ) {
					$code = $user->getOption( 'language' );
				}
				$code = self::sanitizeLangCode( $code );

				Hooks::run( 'UserGetLanguageObject', [ $user, &$code, $this ] );

				if ( $code === $this->getConfig()->get( 'LanguageCode' ) ) {
					$this->lang = $wgContLang;
				} else {
					$obj = Language::factory( $code );
					$this->lang = $obj;
				}

				unset( $this->recursion );
			}
			catch ( Exception $ex ) {
				unset( $this->recursion );
				throw $ex;
			}
		}

		return $this->lang;
	}

	/**
	 * Set the Skin object
	 *
	 * @param Skin $s
	 */
	public function setSkin( Skin $s ) {
		$this->skin = clone $s;
		$this->skin->setContext( $this );
	}

	/**
	 * Get the Skin object
	 *
	 * @return Skin
	 */
	public function getSkin() {
		if ( $this->skin === null ) {
			$skin = null;
			Hooks::run( 'RequestContextCreateSkin', [ $this, &$skin ] );
			$factory = SkinFactory::getDefaultInstance();

			// If the hook worked try to set a skin from it
			if ( $skin instanceof Skin ) {
				$this->skin = $skin;
			} elseif ( is_string( $skin ) ) {
				// Normalize the key, just in case the hook did something weird.
				$normalized = Skin::normalizeKey( $skin );
				$this->skin = $factory->makeSkin( $normalized );
			}

			// If this is still null (the hook didn't run or didn't work)
			// then go through the normal processing to load a skin
			if ( $this->skin === null ) {
				if ( !in_array( 'skin', $this->getConfig()->get( 'HiddenPrefs' ) ) ) {
					# get the user skin
					$userSkin = $this->getUser()->getOption( 'skin' );
					$userSkin = $this->getRequest()->getVal( 'useskin', $userSkin );
				} else {
					# if we're not allowing users to override, then use the default
					$userSkin = $this->getConfig()->get( 'DefaultSkin' );
				}

				// Normalize the key in case the user is passing gibberish
				// or has old preferences (bug 69566).
				$normalized = Skin::normalizeKey( $userSkin );

				// Skin::normalizeKey will also validate it, so
				// this won't throw an exception
				$this->skin = $factory->makeSkin( $normalized );
			}

			// After all that set a context on whatever skin got created
			$this->skin->setContext( $this );
		}

		return $this->skin;
	}

	/** Helpful methods **/

	/**
	 * Get a Message object with context set
	 * Parameters are the same as wfMessage()
	 *
	 * @param mixed ...
	 * @return Message
	 */
	public function msg() {
		$args = func_get_args();

		return call_user_func_array( 'wfMessage', $args )->setContext( $this );
	}

	/** Static methods **/

	/**
	 * Get the RequestContext object associated with the main request
	 *
	 * @return RequestContext
	 */
	public static function getMain() {
		if ( self::$instance === null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Get the RequestContext object associated with the main request
	 * and gives a warning to the log, to find places, where a context maybe is missing.
	 *
	 * @param string $func
	 * @return RequestContext
	 * @since 1.24
	 */
	public static function getMainAndWarn( $func = __METHOD__ ) {
		wfDebug( $func . ' called without context. ' .
			"Using RequestContext::getMain() for sanity\n" );

		return self::getMain();
	}

	/**
	 * Resets singleton returned by getMain(). Should be called only from unit tests.
	 */
	public static function resetMain() {
		if ( !( defined( 'MW_PHPUNIT_TEST' ) || defined( 'MW_PARSER_TEST' ) ) ) {
			throw new MWException( __METHOD__ . '() should be called only from unit tests!' );
		}
		self::$instance = null;
	}

	/**
	 * Export the resolved user IP, HTTP headers, user ID, and session ID.
	 * The result will be reasonably sized to allow for serialization.
	 *
	 * @return array
	 * @since 1.21
	 */
	public function exportSession() {
		$session = MediaWiki\Session\SessionManager::getGlobalSession();
		return [
			'ip' => $this->getRequest()->getIP(),
			'headers' => $this->getRequest()->getAllHeaders(),
			'sessionId' => $session->isPersistent() ? $session->getId() : '',
			'userId' => $this->getUser()->getId()
		];
	}

	/**
	 * Import an client IP address, HTTP headers, user ID, and session ID
	 *
	 * This sets the current session, $wgUser, and $wgRequest from $params.
	 * Once the return value falls out of scope, the old context is restored.
	 * This method should only be called in contexts where there is no session
	 * ID or end user receiving the response (CLI or HTTP job runners). This
	 * is partly enforced, and is done so to avoid leaking cookies if certain
	 * error conditions arise.
	 *
	 * This is useful when background scripts inherit context when acting on
	 * behalf of a user. In general the 'sessionId' parameter should be set
	 * to an empty string unless session importing is *truly* needed. This
	 * feature is somewhat deprecated.
	 *
	 * @note suhosin.session.encrypt may interfere with this method.
	 *
	 * @param array $params Result of RequestContext::exportSession()
	 * @return ScopedCallback
	 * @throws MWException
	 * @since 1.21
	 */
	public static function importScopedSession( array $params ) {
		if ( strlen( $params['sessionId'] ) &&
			MediaWiki\Session\SessionManager::getGlobalSession()->isPersistent()
		) {
			// Sanity check to avoid sending random cookies for the wrong users.
			// This method should only called by CLI scripts or by HTTP job runners.
			throw new MWException( "Sessions can only be imported when none is active." );
		} elseif ( !IP::isValid( $params['ip'] ) ) {
			throw new MWException( "Invalid client IP address '{$params['ip']}'." );
		}

		if ( $params['userId'] ) { // logged-in user
			$user = User::newFromId( $params['userId'] );
			$user->load();
			if ( !$user->getId() ) {
				throw new MWException( "No user with ID '{$params['userId']}'." );
			}
		} else { // anon user
			$user = User::newFromName( $params['ip'], false );
		}

		$importSessionFunc = function ( User $user, array $params ) {
			global $wgRequest, $wgUser;

			$context = RequestContext::getMain();

			// Commit and close any current session
			if ( MediaWiki\Session\PHPSessionHandler::isEnabled() ) {
				session_write_close(); // persist
				session_id( '' ); // detach
				$_SESSION = []; // clear in-memory array
			}

			// Get new session, if applicable
			$session = null;
			if ( strlen( $params['sessionId'] ) ) { // don't make a new random ID
				$manager = MediaWiki\Session\SessionManager::singleton();
				$session = $manager->getSessionById( $params['sessionId'], true )
					?: $manager->getEmptySession();
			}

			// Remove any user IP or agent information, and attach the request
			// with the new session.
			$context->setRequest( new FauxRequest( [], false, $session ) );
			$wgRequest = $context->getRequest(); // b/c

			// Now that all private information is detached from the user, it should
			// be safe to load the new user. If errors occur or an exception is thrown
			// and caught (leaving the main context in a mixed state), there is no risk
			// of the User object being attached to the wrong IP, headers, or session.
			$context->setUser( $user );
			$wgUser = $context->getUser(); // b/c
			if ( $session && MediaWiki\Session\PHPSessionHandler::isEnabled() ) {
				session_id( $session->getId() );
				MediaWiki\quietCall( 'session_start' );
			}
			$request = new FauxRequest( [], false, $session );
			$request->setIP( $params['ip'] );
			foreach ( $params['headers'] as $name => $value ) {
				$request->setHeader( $name, $value );
			}
			// Set the current context to use the new WebRequest
			$context->setRequest( $request );
			$wgRequest = $context->getRequest(); // b/c
		};

		// Stash the old session and load in the new one
		$oUser = self::getMain()->getUser();
		$oParams = self::getMain()->exportSession();
		$oRequest = self::getMain()->getRequest();
		$importSessionFunc( $user, $params );

		// Set callback to save and close the new session and reload the old one
		return new ScopedCallback(
			function () use ( $importSessionFunc, $oUser, $oParams, $oRequest ) {
				global $wgRequest;
				$importSessionFunc( $oUser, $oParams );
				// Restore the exact previous Request object (instead of leaving FauxRequest)
				RequestContext::getMain()->setRequest( $oRequest );
				$wgRequest = RequestContext::getMain()->getRequest(); // b/c
			}
		);
	}

	/**
	 * Create a new extraneous context. The context is filled with information
	 * external to the current session.
	 * - Title is specified by argument
	 * - Request is a FauxRequest, or a FauxRequest can be specified by argument
	 * - User is an anonymous user, for separation IPv4 localhost is used
	 * - Language will be based on the anonymous user and request, may be content
	 *   language or a uselang param in the fauxrequest data may change the lang
	 * - Skin will be based on the anonymous user, should be the wiki's default skin
	 *
	 * @param Title $title Title to use for the extraneous request
	 * @param WebRequest|array $request A WebRequest or data to use for a FauxRequest
	 * @return RequestContext
	 */
	public static function newExtraneousContext( Title $title, $request = [] ) {
		$context = new self;
		$context->setTitle( $title );
		if ( $request instanceof WebRequest ) {
			$context->setRequest( $request );
		} else {
			$context->setRequest( new FauxRequest( $request ) );
		}
		$context->user = User::newFromName( '127.0.0.1', false );

		return $context;
	}
}
