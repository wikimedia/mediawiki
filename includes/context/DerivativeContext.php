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
 * @since 1.19
 *
 * @author Daniel Friesen
 * @file
 */

/**
 * An IContextSource implementation which will inherit context from another source
 * but allow individual pieces of context to be changed locally
 * eg: A ContextSource that can inherit from the main RequestContext but have
 *     a different Title instance set on it.
 */
class DerivativeContext extends ContextSource {

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
	 * Constructor
	 * @param $context IContextSource Context to inherit from
	 */
	public function __construct( IContextSource $context ) {
		$this->setContext( $context );
	}

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
		if ( !is_null( $this->request ) ) {
			return $this->request;
		} else {
			return $this->getContext()->getRequest();
		}
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
		if ( !is_null( $this->title ) ) {
			return $this->title;
		} else {
			return $this->getContext()->getTitle();
		}
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
		if ( !is_null( $this->output ) ) {
			return $this->output;
		} else {
			return $this->getContext()->getOutput();
		}
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
		if ( !is_null( $this->user ) ) {
			return $this->user;
		} else {
			return $this->getContext()->getUser();
		}
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
		if ( !is_null( $this->lang ) ) {
			return $this->lang;
		} else {
			return $this->getContext()->getLang();
		}
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
		if ( !is_null( $this->skin ) ) {
			return $this->skin;
		} else {
			return $this->getContext()->getSkin();
		}
	}

}

