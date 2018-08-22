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
 * @author Daniel Friesen
 * @file
 */
use MediaWiki\MediaWikiServices;

/**
 * An IContextSource implementation which will inherit context from another source
 * but allow individual pieces of context to be changed locally
 * eg: A ContextSource that can inherit from the main RequestContext but have
 *     a different Title instance set on it.
 * @since 1.19
 */
class DerivativeContext extends ContextSource implements MutableContext {
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
	 * @var Config
	 */
	private $config;

	/**
	 * @var Timing
	 */
	private $timing;

	/**
	 * @param IContextSource $context Context to inherit from
	 */
	public function __construct( IContextSource $context ) {
		$this->setContext( $context );
	}

	/**
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @return Config
	 */
	public function getConfig() {
		if ( !is_null( $this->config ) ) {
			return $this->config;
		} else {
			return $this->getContext()->getConfig();
		}
	}

	/**
	 * @deprecated since 1.27 use a StatsdDataFactory from MediaWikiServices (preferably injected)
	 *
	 * @return IBufferingStatsdDataFactory
	 */
	public function getStats() {
		return MediaWikiServices::getInstance()->getStatsdDataFactory();
	}

	/**
	 * @return Timing
	 */
	public function getTiming() {
		if ( !is_null( $this->timing ) ) {
			return $this->timing;
		} else {
			return $this->getContext()->getTiming();
		}
	}

	/**
	 * @param WebRequest $request
	 */
	public function setRequest( WebRequest $request ) {
		$this->request = $request;
	}

	/**
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
	 * @param Title $title
	 */
	public function setTitle( Title $title ) {
		$this->title = $title;
	}

	/**
	 * @return Title|null
	 */
	public function getTitle() {
		if ( !is_null( $this->title ) ) {
			return $this->title;
		} else {
			return $this->getContext()->getTitle();
		}
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
			return true;
		} elseif ( $this->title !== null ) {
			return $this->title->canExist();
		} else {
			return $this->getContext()->canUseWikiPage();
		}
	}

	/**
	 * @since 1.19
	 * @param WikiPage $wikiPage
	 */
	public function setWikiPage( WikiPage $wikiPage ) {
		$this->wikipage = $wikiPage;
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
		if ( !is_null( $this->wikipage ) ) {
			return $this->wikipage;
		} else {
			return $this->getContext()->getWikiPage();
		}
	}

	/**
	 * @param OutputPage $output
	 */
	public function setOutput( OutputPage $output ) {
		$this->output = $output;
	}

	/**
	 * @return OutputPage
	 */
	public function getOutput() {
		if ( !is_null( $this->output ) ) {
			return $this->output;
		} else {
			return $this->getContext()->getOutput();
		}
	}

	/**
	 * @param User $user
	 */
	public function setUser( User $user ) {
		$this->user = $user;
	}

	/**
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
	 * @param Language|string $language Language instance or language code
	 * @throws MWException
	 * @since 1.19
	 */
	public function setLanguage( $language ) {
		if ( $language instanceof Language ) {
			$this->lang = $language;
		} elseif ( is_string( $language ) ) {
			$language = RequestContext::sanitizeLangCode( $language );
			$obj = Language::factory( $language );
			$this->lang = $obj;
		} else {
			throw new MWException( __METHOD__ . " was passed an invalid type of data." );
		}
	}

	/**
	 * @return Language
	 * @since 1.19
	 */
	public function getLanguage() {
		if ( !is_null( $this->lang ) ) {
			return $this->lang;
		} else {
			return $this->getContext()->getLanguage();
		}
	}

	/**
	 * @param Skin $skin
	 */
	public function setSkin( Skin $skin ) {
		$this->skin = clone $skin;
		$this->skin->setContext( $this );
	}

	/**
	 * @return Skin
	 */
	public function getSkin() {
		if ( !is_null( $this->skin ) ) {
			return $this->skin;
		} else {
			return $this->getContext()->getSkin();
		}
	}

	/**
	 * Get a message using the current context.
	 *
	 * This can't just inherit from ContextSource, since then
	 * it would set only the original context, and not take
	 * into account any changes.
	 *
	 * @param string|string[]|MessageSpecifier $key Message key, or array of keys,
	 *   or a MessageSpecifier.
	 * @param mixed $args,... Arguments to wfMessage
	 * @return Message
	 */
	public function msg( $key ) {
		$args = func_get_args();

		// phpcs:ignore MediaWiki.Usage.ExtendClassUsage.FunctionVarUsage
		return wfMessage( ...$args )->setContext( $this );
	}
}
