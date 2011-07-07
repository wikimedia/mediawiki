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

class RequestContext {

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
		return call_user_func_array( 'wfMessage', $args )->inLanguage( $this->getLang() )->title( $this->getTitle() );
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
