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
	 */
	private static function sanitizeLangCode( $code ) {
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
	 * @param $l Mixed Language instance or language code
	 */
	public function setLang( $l ) {
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
	 * Get the Language object
	 *
	 * @return Language
	 */
	public function getLang() {
		if ( $this->lang === null ) {
			global $wgLanguageCode, $wgContLang;
			$code = $this->getRequest()->getVal(
				'uselang',
				$this->getUser()->getOption( 'language' )
			);
			$code = self::sanitizeLangCode( $code );

			wfRunHooks( 'UserGetLanguageObject', array( $this->getUser(), &$code ) );

			if( $code === $wgLanguageCode ) {
				$this->lang = $wgContLang;
			} else {
				$obj = Language::factory( $code );
				$this->lang = $obj;
			}
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
	 * @param $title Title Title to use for the extraneous request
	 * @param $request Mixed A WebRequest or data to use for a FauxRequest
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

