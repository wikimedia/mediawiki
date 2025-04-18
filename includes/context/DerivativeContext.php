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

namespace MediaWiki\Context;

use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\WebRequest;
use MediaWiki\Session\CsrfTokenSet;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Timing;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * An IContextSource implementation which will inherit context from another source
 * but allow individual pieces of context to be changed locally
 * eg: A ContextSource that can inherit from the main RequestContext but have
 *     a different Title instance set on it.
 * @newable
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
	 * @var string|null|false
	 */
	private $action = false;

	/**
	 * @var OutputPage
	 */
	private $output;

	/**
	 * @var User|null
	 */
	private $user;

	/**
	 * @var Authority
	 */
	private $authority;

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
	 * @stable to call
	 * @param IContextSource $context Context to inherit from
	 */
	public function __construct( IContextSource $context ) {
		$this->setContext( $context );
	}

	public function setConfig( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @return Config
	 */
	public function getConfig() {
		return $this->config ?: $this->getContext()->getConfig();
	}

	/**
	 * @return Timing
	 */
	public function getTiming() {
		return $this->timing ?: $this->getContext()->getTiming();
	}

	public function setRequest( WebRequest $request ) {
		$this->request = $request;
	}

	/**
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->request ?: $this->getContext()->getRequest();
	}

	public function getCsrfTokenSet(): CsrfTokenSet {
		return new CsrfTokenSet( $this->getRequest() );
	}

	public function setTitle( Title $title ) {
		$this->title = $title;
		$this->action = null;
	}

	/**
	 * @return Title|null
	 */
	public function getTitle() {
		return $this->title ?: $this->getContext()->getTitle();
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
		}

		if ( $this->title !== null ) {
			return $this->title->canExist();
		}

		return $this->getContext()->canUseWikiPage();
	}

	/**
	 * @since 1.19
	 * @param WikiPage $wikiPage
	 */
	public function setWikiPage( WikiPage $wikiPage ) {
		$pageTitle = $wikiPage->getTitle();
		if ( !$this->title || !$pageTitle->equals( $this->title ) ) {
			$this->setTitle( $pageTitle );
		}
		$this->wikipage = $wikiPage;
		$this->action = null;
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
		if ( !$this->wikipage && $this->title ) {
			$this->wikipage = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $this->title );
		}

		return $this->wikipage ?: $this->getContext()->getWikiPage();
	}

	/**
	 * @since 1.38
	 * @param string $action
	 */
	public function setActionName( string $action ): void {
		$this->action = $action;
	}

	/**
	 * Get the action name for the current web request.
	 *
	 * @since 1.38
	 * @return string Action
	 */
	public function getActionName(): string {
		if ( $this->action === false ) {
			return $this->getContext()->getActionName();
		}

		$this->action ??= MediaWikiServices::getInstance()
			->getActionFactory()
			->getActionName( $this );

		return $this->action;
	}

	public function setOutput( OutputPage $output ) {
		$this->output = $output;
	}

	/**
	 * @return OutputPage
	 */
	public function getOutput() {
		return $this->output ?: $this->getContext()->getOutput();
	}

	public function setUser( User $user ) {
		$this->authority = $user;
		$this->user = $user;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		if ( !$this->user && $this->authority ) {
			// Keep user consistent by using a possible set authority
			$this->user = MediaWikiServices::getInstance()
				->getUserFactory()
				->newFromAuthority( $this->authority );
		}
		return $this->user ?: $this->getContext()->getUser();
	}

	public function setAuthority( Authority $authority ) {
		$this->authority = $authority;
		// If needed, a User object is constructed from this authority
		$this->user = null;
	}

	/**
	 * @since 1.36
	 * @return Authority
	 */
	public function getAuthority(): Authority {
		return $this->authority ?: $this->getContext()->getAuthority();
	}

	/**
	 * @param Language|string $language Language instance or language code
	 * @since 1.19
	 */
	public function setLanguage( $language ) {
		Assert::parameterType( [ Language::class, 'string' ], $language, '$language' );
		if ( $language instanceof Language ) {
			$this->lang = $language;
		} else {
			$language = RequestContext::sanitizeLangCode( $language );
			$obj = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( $language );
			$this->lang = $obj;
		}
	}

	/**
	 * @return Language
	 * @since 1.19
	 */
	public function getLanguage() {
		return $this->lang ?: $this->getContext()->getLanguage();
	}

	public function setSkin( Skin $skin ) {
		$this->skin = clone $skin;
		$this->skin->setContext( $this );
	}

	/**
	 * @return Skin
	 */
	public function getSkin() {
		return $this->skin ?: $this->getContext()->getSkin();
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
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return Message
	 */
	public function msg( $key, ...$params ) {
		// phpcs:ignore MediaWiki.Usage.ExtendClassUsage.FunctionVarUsage
		return wfMessage( $key, ...$params )->setContext( $this );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( DerivativeContext::class, 'DerivativeContext' );
