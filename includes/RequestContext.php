<?php
/**
 * Group all the pieces relevant to the context of a request into one instance
 *
 * @since 1.18
 *
 * @author IAlex
 * @author Daniel Friesen
 * @file
 */

class RequestContext implements IContextSource {

	/**
	 * @var WebRequest
	 */
	private $mRequest;

	/**
	 * @var Title
	 */
	private $mTitle;

	/**
	 * @var OutputPage
	 */
	private $mOutput;

	/**
	 * @var User
	 */
	private $mUser;

	/**
	 * @var Language
	 */
	private $mLang;

	/**
	 * @var Skin
	 */
	private $mSkin;

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
	 * Get the OutputPage object
	 *
	 * @return OutputPage object
	 */
	public function getOutput() {
		if ( !isset( $this->mOutput ) ) {
			$this->mOutput = new OutputPage( $this );
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
	 *
	 * @param $name string
	 *
	 * @return string
	 */
	public function __get( $name ) {
		wfDeprecated( 'RequestContext::__get() is deprecated; use $context->getFoo() instead' );
		if ( in_array( $name, array( 'request', 'title', 'output', 'user', 'lang', 'skin' ) ) ) {
			$fname = 'get' . ucfirst( $name );
			return $this->$fname();
		}
		trigger_error( "Undefined property {$name}", E_NOTICE );
	}

	/**
	 * @param $name string
	 * @param $value
	 * @return string
	 */
	public function __set( $name, $value ) {
		wfDeprecated( 'RequestContext::__set() is deprecated; use $context->setFoo() instead' );
		if ( in_array( $name, array( 'request', 'title', 'output', 'user', 'lang', 'skin' ) ) ) {
			$fname = 'set' . ucfirst( $name );
			return $this->$fname( $value );
		}
		trigger_error( "Undefined property {$name}", E_NOTICE );
	}
}

/**
 * Interface for objects which can provide a context on request.
 */
interface IContextSource {

	/**
	 * Get the WebRequest object
	 *
	 * @return WebRequest
	 */
	public function getRequest();

	/**
	 * Get the Title object
	 *
	 * @return Title
	 */
	public function getTitle();

	/**
	 * Get the OutputPage object
	 *
	 * @return OutputPage object
	 */
	public function getOutput();

	/**
	 * Get the User object
	 *
	 * @return User
	 */
	public function getUser();

	/**
	 * Get the Language object
	 *
	 * @return Language
	 */
	public function getLang();

	/**
	 * Get the Skin object
	 *
	 * @return Skin
	 */
	public function getSkin();
}

/**
 * The simplest way of implementing IContextSource is to hold a RequestContext as a
 * member variable and provide accessors to it.
 */
abstract class ContextSource implements IContextSource {

	/**
	 * @var RequestContext
	 */
	private $context;

	/**
	 * Get the RequestContext object
	 *
	 * @return RequestContext
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * Set the RequestContext object
	 *
	 * @param $context RequestContext
	 */
	public function setContext( RequestContext $context ) {
		$this->context = $context;
	}

	/**
	 * Get the WebRequest object
	 *
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->context->getRequest();
	}

	/**
	 * Get the Title object
	 *
	 * @return Title
	 */
	public function getTitle() {
		return $this->context->getTitle();
	}

	/**
	 * Get the OutputPage object
	 *
	 * @return OutputPage object
	 */
	public function getOutput() {
		return $this->context->getOutput();
	}

	/**
	 * Get the User object
	 *
	 * @return User
	 */
	public function getUser() {
		return $this->context->getUser();
	}

	/**
	 * Get the Language object
	 *
	 * @return Language
	 */
	public function getLang() {
		return $this->context->getLang();
	}

	/**
	 * Get the Skin object
	 *
	 * @return Skin
	 */
	public function getSkin() {
		return $this->context->getSkin();
	}
}