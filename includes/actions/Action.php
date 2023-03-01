<?php
/**
 * Base classes for actions done on pages.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 */

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Title\Title;

/**
 * @defgroup Actions Actions
 */

/**
 * Actions are things which can be done to pages (edit, delete, rollback, etc).  They
 * are distinct from Special Pages because an action must apply to exactly one page.
 *
 * To add an action in an extension, create a subclass of Action, and add the key to
 * $wgActions.
 *
 * Actions generally fall into two groups: the show-a-form-then-do-something-with-the-input
 * format (protect, delete, move, etc), and the just-do-something format (watch, rollback,
 * patrol, etc). The FormAction and FormlessAction classes represent these two groups.
 *
 * @stable to extend
 */
abstract class Action implements MessageLocalizer {

	/**
	 * @var Article
	 * @since 1.35
	 */
	private $article;

	/**
	 * IContextSource if specified; otherwise we'll use the Context from the Page
	 * @since 1.17
	 * @var IContextSource|null
	 */
	protected $context;

	/**
	 * The fields used to create the HTMLForm
	 * @since 1.17
	 * @var array
	 */
	protected $fields;

	/** @var HookContainer|null */
	private $hookContainer;
	/** @var HookRunner|null */
	private $hookRunner;

	/**
	 * Get an appropriate Action subclass for the given action
	 * @since 1.17
	 *
	 * @param string $action
	 * @param Article $article
	 * @param IContextSource|null $context Falls back to article's context
	 * @return Action|false|null False if the action is disabled, null
	 *     if it is not recognised
	 */
	final public static function factory(
		string $action,
		Article $article,
		IContextSource $context = null
	) {
		return MediaWikiServices::getInstance()
			->getActionFactory()
			->getAction( $action, $article, $context ?? $article->getContext() );
	}

	/**
	 * Get the action that will be executed, not necessarily the one passed
	 * passed through the "action" request parameter. Actions disabled in
	 * $wgActions will be replaced by "nosuchaction".
	 *
	 * @since 1.19
	 * @param IContextSource $context
	 * @return string Action name
	 */
	final public static function getActionName( IContextSource $context ) {
		// Optimisation: Reuse/prime the cached value of RequestContext
		return $context->getActionName();
	}

	/**
	 * Check if a given action is recognised, even if it's disabled
	 *
	 * @since 1.17
	 * @deprecated since 1.38 use (bool)ActionFactory::getAction()
	 *
	 * @param string $name Name of an action
	 * @return bool
	 */
	final public static function exists( string $name ): bool {
		wfDeprecated( __METHOD__, '1.38' );
		return MediaWikiServices::getInstance()
			->getActionFactory()
			->actionExists( $name );
	}

	/**
	 * Get the IContextSource in use here
	 * @since 1.17
	 * @return IContextSource
	 */
	final public function getContext() {
		if ( $this->context instanceof IContextSource ) {
			return $this->context;
		}
		wfDebug( __METHOD__ . ": no context known, falling back to Article's context." );
		return $this->getArticle()->getContext();
	}

	/**
	 * Get the WebRequest being used for this instance
	 * @since 1.17
	 *
	 * @return WebRequest
	 */
	final public function getRequest() {
		return $this->getContext()->getRequest();
	}

	/**
	 * Get the OutputPage being used for this instance
	 * @since 1.17
	 *
	 * @return OutputPage
	 */
	final public function getOutput() {
		return $this->getContext()->getOutput();
	}

	/**
	 * Shortcut to get the User being used for this instance
	 * @since 1.17
	 *
	 * @return User
	 */
	final public function getUser() {
		return $this->getContext()->getUser();
	}

	/**
	 * Shortcut to get the Authority executing this instance
	 *
	 * @return Authority
	 * @since 1.39
	 */
	final public function getAuthority(): Authority {
		return $this->getContext()->getAuthority();
	}

	/**
	 * Shortcut to get the Skin being used for this instance
	 * @since 1.17
	 *
	 * @return Skin
	 */
	final public function getSkin() {
		return $this->getContext()->getSkin();
	}

	/**
	 * Shortcut to get the user Language being used for this instance
	 *
	 * @return Language
	 */
	final public function getLanguage() {
		return $this->getContext()->getLanguage();
	}

	/**
	 * Get a WikiPage object
	 * @since 1.35
	 *
	 * @return WikiPage
	 */
	final public function getWikiPage(): WikiPage {
		return $this->getArticle()->getPage();
	}

	/**
	 * Get a Article object
	 * @since 1.35
	 * Overriding this method is deprecated since 1.35
	 *
	 * @return Article|ImagePage|CategoryPage
	 */
	public function getArticle() {
		return $this->article;
	}

	/**
	 * Shortcut to get the Title object from the page
	 * @since 1.17
	 *
	 * @return Title
	 */
	final public function getTitle() {
		return $this->getWikiPage()->getTitle();
	}

	/**
	 * Get a Message object with context set
	 * Parameters are the same as wfMessage()
	 *
	 * @param string|string[]|MessageSpecifier $key
	 * @param mixed ...$params
	 * @return Message
	 */
	final public function msg( $key, ...$params ) {
		return $this->getContext()->msg( $key, ...$params );
	}

	/**
	 * @since 1.40
	 * @internal For use by ActionFactory
	 * @param HookContainer $hookContainer
	 */
	public function setHookContainer( HookContainer $hookContainer ) {
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * @since 1.35
	 * @internal since 1.37
	 * @return HookContainer
	 */
	protected function getHookContainer() {
		if ( !$this->hookContainer ) {
			$this->hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		}
		return $this->hookContainer;
	}

	/**
	 * @since 1.35
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 * @return HookRunner
	 */
	protected function getHookRunner() {
		if ( !$this->hookRunner ) {
			$this->hookRunner = new HookRunner( $this->getHookContainer() );
		}
		return $this->hookRunner;
	}

	/**
	 * Only public since 1.21
	 *
	 * @stable to call
	 *
	 * @param Article $article
	 * @param IContextSource $context
	 */
	public function __construct( Article $article, IContextSource $context ) {
		$this->article = $article;
		$this->context = $context;
	}

	/**
	 * Return the name of the action this object responds to
	 * @since 1.17
	 *
	 * @return string Lowercase name
	 */
	abstract public function getName();

	/**
	 * Get the permission required to perform this action.  Often, but not always,
	 * the same as the action name
	 * @since 1.17
	 * @stable to override
	 *
	 * @return string|null
	 */
	public function getRestriction() {
		return null;
	}

	/**
	 * Indicates whether this action requires read rights
	 * @since 1.38
	 * @stable to override
	 * @return bool
	 */
	public function needsReadRights() {
		return true;
	}

	/**
	 * Checks if the given user (identified by an object) can perform this action.  Can be
	 * overridden by sub-classes with more complicated permissions schemes.  Failures here
	 * must throw subclasses of ErrorPageError
	 * @since 1.17
	 * @stable to override
	 *
	 * @param User $user
	 * @throws UserBlockedError|ReadOnlyError|PermissionsError
	 */
	protected function checkCanExecute( User $user ) {
		$right = $this->getRestriction();
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		if ( $right !== null ) {
			$errors = $permissionManager->getPermissionErrors( $right, $user, $this->getTitle() );
			if ( count( $errors ) ) {
				throw new PermissionsError( $right, $errors );
			}
		}

		// If the action requires an unblock, explicitly check the user's block.
		$checkReplica = !$this->getRequest()->wasPosted();
		if (
			$this->requiresUnblock() &&
			$permissionManager->isBlockedFrom( $user, $this->getTitle(), $checkReplica )
		) {
			$block = $user->getBlock();
			if ( $block ) {
				throw new UserBlockedError(
					$block,
					$user,
					$this->getLanguage(),
					$this->getRequest()->getIP()
				);
			}

			throw new PermissionsError( $this->getName(), [ 'badaccess-group0' ] );
		}

		// This should be checked at the end so that the user won't think the
		// error is only temporary when he also don't have the rights to execute
		// this action
		$readOnlyMode = MediaWikiServices::getInstance()->getReadOnlyMode();
		if ( $this->requiresWrite() && $readOnlyMode->isReadOnly() ) {
			throw new ReadOnlyError();
		}
	}

	/**
	 * Whether this action requires the wiki not to be locked
	 * @since 1.17
	 * @stable to override
	 *
	 * @return bool
	 */
	public function requiresWrite() {
		return true;
	}

	/**
	 * Whether this action can still be executed by a blocked user
	 * @since 1.17
	 * @stable to override
	 *
	 * @return bool
	 */
	public function requiresUnblock() {
		return true;
	}

	/**
	 * Set output headers for noindexing etc.  This function will not be called through
	 * the execute() entry point, so only put UI-related stuff in here.
	 * @stable to override
	 * @since 1.17
	 */
	protected function setHeaders() {
		$out = $this->getOutput();
		$out->setRobotPolicy( 'noindex,nofollow' );
		$out->setPageTitle( $this->getPageTitle() );
		$out->setSubtitle( $this->getDescription() );
		$out->setArticleRelated( true );
	}

	/**
	 * Returns the name that goes in the `<h1>` page title.
	 *
	 * @stable to override
	 * @return string
	 */
	protected function getPageTitle() {
		return $this->getTitle()->getPrefixedText();
	}

	/**
	 * Returns the description that goes below the `<h1>` element.
	 *
	 * @since 1.17
	 * @stable to override
	 * @return string HTML
	 */
	protected function getDescription() {
		return $this->msg( strtolower( $this->getName() ) )->escaped();
	}

	/**
	 * Adds help link with an icon via page indicators.
	 * Link target can be overridden by a local message containing a wikilink:
	 * the message key is: lowercase action name + '-helppage'.
	 * @param string $to Target MediaWiki.org page title or encoded URL.
	 * @param bool $overrideBaseUrl Whether $url is a full URL, to avoid MW.o.
	 * @since 1.25
	 */
	public function addHelpLink( $to, $overrideBaseUrl = false ) {
		$lang = MediaWikiServices::getInstance()->getContentLanguage();
		$target = $lang->lc( $this->getName() . '-helppage' );
		$msg = $this->msg( $target );

		if ( !$msg->isDisabled() ) {
			$title = Title::newFromText( $msg->plain() );
			if ( $title instanceof Title ) {
				$this->getOutput()->addHelpLink( $title->getLocalURL(), true );
			}
		} else {
			$this->getOutput()->addHelpLink( $to, $overrideBaseUrl );
		}
	}

	/**
	 * The main action entry point.  Do all output for display and send it to the context
	 * output.  Do not use globals $wgOut, $wgRequest, etc, in implementations; use
	 * $this->getOutput(), etc.
	 * @since 1.17
	 *
	 * @throws ErrorPageError
	 */
	abstract public function show();

	/**
	 * Call wfTransactionalTimeLimit() if this request was POSTed
	 * @since 1.26
	 */
	protected function useTransactionalTimeLimit() {
		if ( $this->getRequest()->wasPosted() ) {
			wfTransactionalTimeLimit();
		}
	}

	/**
	 * Indicates whether this action may perform database writes
	 * @return bool
	 * @since 1.27
	 * @stable to override
	 */
	public function doesWrites() {
		return false;
	}
}
