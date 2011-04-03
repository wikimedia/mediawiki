<?php
/**
 * Group all the pieces relevant to the context of a request into one instance
 *
 * @author IAlex
 * @author Daniel Friesen
 * @file
 */

class RequestContext {
	private $mRequest; // / WebRequest object
	private $mTitle;   // / Title object
	private $mOutput;  // / OutputPage object
	private $mUser;    // / User object
	private $mLang;    // / Language object
	private $mSkin;    // / Skin object

	/**
	 * Set the WebRequest object
	 *
	 * @param $r WebRequest object
	 */
	public function setRequest( WebRequest $r ) {
		$this->mRequest = $r;
	}

	/**
	 * Get the WebRequest object
	 *
	 * @return WebRequest
	 */
	public function getRequest() {
		if ( !isset( $this->mRequest ) ) {
			global $wgRequest; # fallback to $wg till we can improve this
			$this->mRequest = $wgRequest;
		}
		return $this->mRequest;
	}

	/**
	 * Set the Title object
	 *
	 * @param $t Title object
	 */
	public function setTitle( Title $t ) {
		$this->mTitle = $t;
	}

	/**
	 * Get the Title object
	 *
	 * @return Title
	 */
	public function getTitle() {
		if ( !isset( $this->mTitle ) ) {
			global $wgTitle; # fallback to $wg till we can improve this
			$this->mTitle = $wgTitle;
		}
		return $this->mTitle;
	}

	/**
	 * Set the OutputPage object
	 *
	 * @param $u OutputPage
	 */
	public function setOutput( OutputPage $o ) {
		$this->mOutput = $o;
	}

	/**
	 * Get the OutputPage object
	 *
	 * @return OutputPage object
	 */
	public function getOutput() {
		if ( !isset( $this->mOutput ) ) {
			$this->mOutput = new OutputPage;
			$this->mOutput->setContext( $this );
		}
		return $this->mOutput;
	}

	/**
	 * Set the User object
	 *
	 * @param $u User
	 */
	public function setUser( User $u ) {
		$this->mUser = $u;
	}

	/**
	 * Get the User object
	 *
	 * @return User
	 */
	public function getUser() {
		if ( !isset( $this->mUser ) ) {
			global $wgCommandLineMode;
			$this->mUser = $wgCommandLineMode
				? new User
				: User::newFromSession( $this->getRequest() );
		}
		return $this->mUser;
	}

	/**
	 * Get the Language object
	 *
	 * @return Language
	 */
	public function getLang() {
		if ( !isset( $this->mLang ) ) {
			global $wgLanguageCode, $wgContLang;
			$code = $this->getRequest()->getVal(
				'uselang',
				$this->getUser()->getOption( 'language' )
			);
			// BCP 47 - letter case MUST NOT carry meaning
			$code = strtolower( $code );

			# Validate $code
			if ( empty( $code ) || !Language::isValidCode( $code ) || ( $code === 'qqq' ) ) {
				wfDebug( "Invalid user language code\n" );
				$code = $wgLanguageCode;
			}

			wfRunHooks( 'UserGetLanguageObject', array( $this->getUser(), &$code ) );

			if ( $code === $wgLanguageCode ) {
				$this->mLang = $wgContLang;
			} else {
				$obj = Language::factory( $code );
				$this->mLang = $obj;
			}
		}
		return $this->mLang;
	}

	/**
	 * Get the Skin object
	 *
	 * @return Skin
	 */
	public function getSkin() {
		if ( !isset( $this->mSkin ) ) {
			wfProfileIn( __METHOD__ . '-createskin' );

			global $wgHiddenPrefs;
			if ( !in_array( 'skin', $wgHiddenPrefs ) ) {
				# get the user skin
				$userSkin = $this->getUser()->getOption( 'skin' );
				$userSkin = $this->getRequest()->getVal( 'useskin', $userSkin );
			} else {
				# if we're not allowing users to override, then use the default
				global $wgDefaultSkin;
				$userSkin = $wgDefaultSkin;
			}

			$this->mSkin = Skin::newFromKey( $userSkin );
			$this->mSkin->setContext( $this );
			wfProfileOut( __METHOD__ . '-createskin' );
		}
		return $this->mSkin;
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
		return call_user_func_array( 'wfMessage', $args )->inLanguage( $this->getLang() );
	}

	/** Static methods **/

	/**
	 * Get the RequestContext object associated with the main request
	 *
	 * @return RequestContext object
	 */
	public static function getMain() {
		static $instance = null;
		if ( !isset( $instance ) ) {
			$instance = new self;
		}
		return $instance;
	}

	/**
	 * Make these C#-style accessors, so you can do $context->user->getName() which is
	 * internally mapped to $context->__get('user')->getName() which is mapped to
	 * $context->getUser()->getName()
	 */
	public function __get( $name ) {
		if ( in_array( $name, array( 'request', 'title', 'output', 'user', 'lang', 'skin' ) ) ) {
			$fname = 'get' . ucfirst( $name );
			return $this->$fname();
		}
		trigger_error( "Undefined property {$name}", E_NOTICE );
	}

	public function __set( $name, $value ) {
		if ( in_array( $name, array( 'request', 'title', 'output', 'user', 'lang', 'skin' ) ) ) {
			$fname = 'set' . ucfirst( $name );
			return $this->$fname( $value );
		}
		trigger_error( "Undefined property {$name}", E_NOTICE );
	}
}

