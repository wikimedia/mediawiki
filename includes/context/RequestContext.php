<?php
/**
 * Request-dependant objects containers.
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
 * @since 1.18
 *
 * @author Alexandre Emsenhuber
 * @author Daniel Friesen
 * @file
 */

/**
 * Group all the pieces relevant to the context of a request into one instance
 */
class RequestContext implements IContextSource {

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
	 * Set the WebRequest object
	 *
	 * @param $r WebRequest object
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
			global $wgRequest; # fallback to $wg till we can improve this
			$this->request = $wgRequest;
		}
		return $this->request;
	}

	/**
	 * Set the Title object
	 *
	 * @param $t Title object
	 */
	public function setTitle( Title $t ) {
		$this->title = $t;
	}

	/**
	 * Get the Title object
	 *
	 * @return Title
	 */
	public function getTitle() {
		if ( $this->title === null ) {
			global $wgTitle; # fallback to $wg till we can improve this
			$this->title = $wgTitle;
		}
		return $this->title;
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
		if ( $this->wikipage !== null ) {
			# If there's a WikiPage object set, we can for sure get it
			return true;
		}
		$title = $this->getTitle();
		if ( $title === null ) {
			# No Title, no WikiPage
			return false;
		} else {
			# Only namespaces whose pages are stored in the database can have WikiPage
			return $title->canExist();
		}
	}

	/**
	 * Set the WikiPage object
	 *
	 * @since 1.19
	 * @param $p WikiPage object
	 */
	public function setWikiPage( WikiPage $p ) {
		$this->wikipage = $p;
	}

	/**
	 * Get the WikiPage object.
	 * May throw an exception if there's no Title object set or the Title object
	 * belongs to a special namespace that doesn't have WikiPage, so use first
	 * canUseWikiPage() to check whether this method can be called safely.
	 *
	 * @since 1.19
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
	 * @param $o OutputPage
	 */
	public function setOutput( OutputPage $o ) {
		$this->output = $o;
	}

	/**
	 * Get the OutputPage object
	 *
	 * @return OutputPage object
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
	 * @param $u User
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
	 * @param $code string
	 * @return string
	 */
	public static function sanitizeLangCode( $code ) {
		global $wgLanguageCode;

		// BCP 47 - letter case MUST NOT carry meaning
		$code = strtolower( $code );

		# Validate $code
		if( empty( $code ) || !Language::isValidCode( $code ) || ( $code === 'qqq' ) ) {
			wfDebug( "Invalid user language code\n" );
			$code = $wgLanguageCode;
		}

		return $code;
	}

	/**
	 * Set the Language object
	 *
	 * @deprecated 1.19 Use setLanguage instead
	 * @param $l Mixed Language instance or language code
	 */
	public function setLang( $l ) {
		wfDeprecated( __METHOD__, '1.19' );
		$this->setLanguage( $l );
	}

	/**
	 * Set the Language object
	 *
	 * @param $l Mixed Language instance or language code
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
	 * @deprecated 1.19 Use getLanguage instead
	 * @return Language
	 */
	public function getLang() {
		wfDeprecated( __METHOD__, '1.19' );
		return $this->getLanguage();
	}

	/**
	 * Get the Language object.
	 * Initialization of user or request objects can depend on this.
	 *
	 * @return Language
	 * @since 1.19
	 */
	public function getLanguage() {
		if ( isset( $this->recursion ) ) {
			trigger_error( "Recursion detected in " . __METHOD__, E_USER_WARNING );
			$e = new Exception;
			wfDebugLog( 'recursion-guard', "Recursion detected:\n" . $e->getTraceAsString() );

			global $wgLanguageCode;
			$code = ( $wgLanguageCode ) ? $wgLanguageCode : 'en';
			$this->lang = Language::factory( $code );
		} elseif ( $this->lang === null ) {
			$this->recursion = true;

			global $wgLanguageCode, $wgContLang;

			$request = $this->getRequest();
			$user = $this->getUser();

			$code = $request->getVal( 'uselang', $user->getOption( 'language' ) );
			$code = self::sanitizeLangCode( $code );

			wfRunHooks( 'UserGetLanguageObject', array( $user, &$code, $this ) );

			if( $code === $wgLanguageCode ) {
				$this->lang = $wgContLang;
			} else {
				$obj = Language::factory( $code );
				$this->lang = $obj;
			}

			unset( $this->recursion );
		}

		return $this->lang;
	}

	/**
	 * Set the Skin object
	 *
	 * @param $s Skin
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
			wfProfileIn( __METHOD__ . '-createskin' );
			
			$skin = null;
			wfRunHooks( 'RequestContextCreateSkin', array( $this, &$skin ) );

			// If the hook worked try to set a skin from it
			if ( $skin instanceof Skin ) {
				$this->skin = $skin;
			} elseif ( is_string($skin) ) {
				$this->skin = Skin::newFromKey( $skin );
			}

			// If this is still null (the hook didn't run or didn't work)
			// then go through the normal processing to load a skin
			if ( $this->skin === null ) {
				global $wgHiddenPrefs;
				if( !in_array( 'skin', $wgHiddenPrefs ) ) {
					# get the user skin
					$userSkin = $this->getUser()->getOption( 'skin' );
					$userSkin = $this->getRequest()->getVal( 'useskin', $userSkin );
				} else {
					# if we're not allowing users to override, then use the default
					global $wgDefaultSkin;
					$userSkin = $wgDefaultSkin;
				}

				$this->skin = Skin::newFromKey( $userSkin );
			}

			// After all that set a context on whatever skin got created
			$this->skin->setContext( $this );
			wfProfileOut( __METHOD__ . '-createskin' );
		}
		return $this->skin;
	}

	/** Helpful methods **/

	/**
	 * Get a Message object with context set
	 * Parameters are the same as wfMessage()
	 *
	 * @return Message object
	 */
	public function msg() {
		$args = func_get_args();
		return call_user_func_array( 'wfMessage', $args )->setContext( $this );
	}

	/** Static methods **/

	/**
	 * Get the RequestContext object associated with the main request
	 *
	 * @return RequestContext object
	 */
	public static function getMain() {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new self;
		}
		return $instance;
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
	public static function newExtraneousContext( Title $title, $request=array() ) {
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

