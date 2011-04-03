<?php
/**
 * Group all the pieces relevant to the context of a request into one instance
 * 
 * @author IAlex
 * @author Daniel Friesen
 * @file
 */

class RequestContext {
	private $request; /// WebRequest object
	private $title;   /// Title object
	private $output;  /// OutputPage object
	private $user;    /// User object
	private $lang;    /// Language object
	private $skin;    /// Skin object

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
		if ( !isset($this->request) ) {
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
		if ( !isset($this->title) ) {
			global $wgTitle; # fallback to $wg till we can improve this
			$this->title = $wgTitle;
		}
		return $this->title;
	}

	/**
	 * Set the OutputPage object
	 *
	 * @param $u OutputPage
	 */
	public function setOutput( OutputPage $u ) {
		$this->output = $u;
	}

	/**
	 * Get the OutputPage object
	 *
	 * @return OutputPage object
	 */
	public function getOutput() {
		if ( !isset($this->output) ) {
			$this->output = new OutputPage;
			$this->output->setContext( $this );
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
		if ( !isset($this->user) ) {
			global $wgCommandLineMode;
			$this->user = $wgCommandLineMode ? new User : User::newFromSession( $this->getRequest() );
		}
		return $this->user;
	}

	/**
	 * Get the Language object
	 *
	 * @return Language
	 */
	public function getLang() {
		if ( !isset($this->lang) ) {
			global $wgLanguageCode, $wgContLang;
			$code = $this->getRequest()->getVal( 'uselang', $this->getUser()->getOption( 'language' ) );
			// BCP 47 - letter case MUST NOT carry meaning
			$code = strtolower( $code );

			# Validate $code
			if( empty( $code ) || !Language::isValidCode( $code ) || ( $code === 'qqq' ) ) {
				wfDebug( "Invalid user language code\n" );
				$code = $wgLanguageCode;
			}

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
	 * Get the Skin object
	 *
	 * @return Skin
	 */
	public function getSkin() {
		if ( !isset($this->skin) ) {
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
		if ( !isset($instance) ) {
			$instance = new self;
		}
		return $instance;
	}
}

