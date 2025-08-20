<?php
/**
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

namespace MediaWiki\Actions;

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\Article;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\Title;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @since 1.37
 * @author DannyS712
 */
class ActionFactory {

	/**
	 * @var array
	 * Configured actions (eg those added by extensions to $wgActions) that overrides CORE_ACTIONS
	 */
	private $actionsConfig;

	private LoggerInterface $logger;
	private ObjectFactory $objectFactory;
	private HookContainer $hookContainer;
	private HookRunner $hookRunner;
	private IContentHandlerFactory $contentHandlerFactory;

	/**
	 * Core default action specifications
	 *
	 *     'foo' => 'ClassName'    Load the specified class which subclasses Action
	 *     'foo' => a callable     Load the class returned by the callable
	 *     'foo' => true           Load the class FooAction which subclasses Action
	 *     'foo' => false          The action is disabled; show an error message
	 *     'foo' => an object      Use the specified object, which subclasses Action, useful for tests.
	 *     'foo' => an array       Slowly being used to replace the first three. The array
	 *                               is treated as a specification for an ObjectFactory.
	 */
	private const CORE_ACTIONS = [
		'delete' => true,
		'edit' => true,
		'history' => true,
		'protect' => true,
		'purge' => true,
		'render' => true,
		'submit' => true,
		'unprotect' => true,
		'view' => true,

		// Beginning of actions switched to using DI with an ObjectFactory spec
		'credits' => [
			'class' => CreditsAction::class,
			'services' => [
				'LinkRenderer',
				'UserFactory',
			],
		],
		'info' => [
			'class' => InfoAction::class,
			'services' => [
				'ContentLanguage',
				'LanguageNameUtils',
				'LinkBatchFactory',
				'LinkRenderer',
				'DBLoadBalancerFactory',
				'MagicWordFactory',
				'NamespaceInfo',
				'PageProps',
				'RepoGroup',
				'RevisionLookup',
				'MainWANObjectCache',
				'WatchedItemStore',
				'RedirectLookup',
				'RestrictionStore',
				'LinksMigration',
				'UserFactory',
			],
		],
		'markpatrolled' => [
			'class' => MarkpatrolledAction::class,
			'services' => [
				'LinkRenderer',
				'PatrolManager',
			],
		],
		'mcrundo' => [
			'class' => McrUndoAction::class,
			'services' => [
				// Same as for McrRestoreAction
				'ReadOnlyMode',
				'RevisionLookup',
				'RevisionRenderer',
				'CommentFormatter',
				'MainConfig',
			],
		],
		'mcrrestore' => [
			'class' => McrRestoreAction::class,
			'services' => [
				// Same as for McrUndoAction
				'ReadOnlyMode',
				'RevisionLookup',
				'RevisionRenderer',
				'CommentFormatter',
				'MainConfig',
			],
		],
		'raw' => [
			'class' => RawAction::class,
			'services' => [
				'Parser',
				'PermissionManager',
				'RevisionLookup',
				'RestrictionStore',
				'UserFactory',
			],
		],
		'revert' => [
			'class' => RevertAction::class,
			'services' => [
				'ContentLanguage',
				'RepoGroup',
			],
		],
		'rollback' => [
			'class' => RollbackAction::class,
			'services' => [
				'ContentHandlerFactory',
				'RollbackPageFactory',
				'UserOptionsLookup',
				'WatchlistManager',
				'CommentFormatter'
			],
		],
		'unwatch' => [
			'class' => UnwatchAction::class,
			'services' => [
				'WatchlistManager',
				'WatchedItemStore',
				'UserOptionsLookup',
			],
		],
		'watch' => [
			'class' => WatchAction::class,
			'services' => [
				'WatchlistManager',
				'WatchedItemStore',
				'UserOptionsLookup',
			],
		],
	];

	/**
	 * @param array $actionsConfig Configured actions (eg those added by extensions to $wgActions)
	 * @param LoggerInterface $logger
	 * @param ObjectFactory $objectFactory
	 * @param HookContainer $hookContainer
	 * @param IContentHandlerFactory $contentHandlerFactory
	 */
	public function __construct(
		array $actionsConfig,
		LoggerInterface $logger,
		ObjectFactory $objectFactory,
		HookContainer $hookContainer,
		IContentHandlerFactory $contentHandlerFactory
	) {
		$this->actionsConfig = $actionsConfig;
		$this->logger = $logger;
		$this->objectFactory = $objectFactory;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->contentHandlerFactory = $contentHandlerFactory;
	}

	/**
	 * @param string $actionName should already be in all lowercase
	 * @return class-string|callable|false|Action|array|null The spec for the action, in any valid form,
	 *   based on $this->actionsConfig, or if not included there, CORE_ACTIONS, or null if the
	 *   action does not exist.
	 */
	private function getActionSpec( string $actionName ) {
		if ( isset( $this->actionsConfig[ $actionName ] ) ) {
			$this->logger->debug(
				'{actionName} is being set in configuration rather than CORE_ACTIONS',
				[
					'actionName' => $actionName
				]
			);
			return $this->actionsConfig[ $actionName ];
		}
		return ( self::CORE_ACTIONS[ $actionName ] ?? null );
	}

	/**
	 * Get an appropriate Action subclass for the given action,
	 * taking into account Article-specific overrides
	 *
	 * @param string $actionName
	 * @param Article|PageIdentity $article The target on which the action is to be performed.
	 * @param IContextSource $context
	 * @return Action|false|null False if the action is disabled, null if not recognized
	 */
	public function getAction(
		string $actionName,
		$article,
		IContextSource $context
	) {
		// Normalize to lowercase
		$actionName = strtolower( $actionName );

		$spec = $this->getActionSpec( $actionName );
		if ( $spec === false ) {
			// The action is disabled
			return $spec;
		}

		if ( $article instanceof PageIdentity ) {
			if ( !$article->canExist() ) {
				// Encountered a non-proper PageIdentity (e.g. a special page).
				// We can't construct an Article object for a SpecialPage,
				// so give up here. Actions are only defined for proper pages anyway.
				// See T348451.
				return null;
			}

			$article = Article::newFromTitle(
				Title::newFromPageIdentity( $article ),
				$context
			);
		}

		// Check action overrides even for nonexistent actions, so that actions
		// can exist just for a single content type. For Flow's convenience.
		$overrides = $article->getActionOverrides();
		if ( isset( $overrides[ $actionName ] ) ) {
			// The Article class wants to override the action
			$spec = $overrides[ $actionName ];
			$this->logger->debug(
				'Overriding normal handler for {actionName}',
				[ 'actionName' => $actionName ]
			);
		}

		if ( !$spec ) {
			// Either no such action exists (null) or the action is disabled
			// based on the article overrides (false)
			return $spec;
		}

		if ( $spec === true ) {
			// Old-style: use Action subclass based on name
			$spec = ucfirst( $actionName ) . 'Action';
		}

		// $spec is either a class name, a callable, a specific object to use, or an
		// ObjectFactory spec. Convert to ObjectFactory spec, or return the specific object.
		if ( is_string( $spec ) ) {
			if ( !class_exists( $spec ) ) {
				$this->logger->info(
					'Missing action class {actionClass}, treating as disabled',
					[ 'actionClass' => $spec ]
				);
				return false;
			}
			// Class exists, can be used by ObjectFactory
			$spec = [ 'class' => $spec ];
		} elseif ( is_callable( $spec ) ) {
			$spec = [ 'factory' => $spec ];
		} elseif ( !is_array( $spec ) ) {
			// $spec is an object to use directly
			return $spec;
		}

		// ObjectFactory::createObject accepts an array, not just a callable (phan bug)
		// @phan-suppress-next-line PhanTypeInvalidCallableArrayKey
		$actionObj = $this->objectFactory->createObject(
			$spec,
			[
				'extraArgs' => [ $article, $context ],
				'assertClass' => Action::class
			]
		);
		$actionObj->setHookContainer( $this->hookContainer );
		return $actionObj;
	}

	/**
	 * Returns an object containing information about the given action, or null if the action is not
	 * known. Currently, this will also return null if the action is known but disabled. This may
	 * change in the future.
	 *
	 * @note If $target refers to a non-proper page (such as a special page), this method will
	 * currently return null due to limitations in the way it is implemented (T346036). This
	 * will also happen when $target is null if the wiki's main page is not a proper page
	 * (e.g. Special:MyLanguage/Main_Page, see T348451).
	 *
	 * @param string $name
	 * @param Article|PageIdentity|null $target The target on which the action is to be performed,
	 *     if known. This is used to apply page-specific action overrides.
	 *
	 * @return ?ActionInfo
	 * @since 1.41
	 */
	public function getActionInfo( string $name, $target = null ): ?ActionInfo {
		$context = RequestContext::getMain();

		if ( !$target ) {
			// If no target is given, check if the action is even defined before
			// falling back to the main page. If $target is given, we can't
			// exit early, since there may be action overrides defined for the page.
			$spec = $this->getActionSpec( $name );
			if ( !$spec ) {
				return null;
			}

			$target = Title::newMainPage();
		}

		// TODO: In the future, this information should be taken directly from the action spec,
		// without the need to instantiate an action object. However, action overrides will have
		// to be taken into account if a target is given. (T346036)
		$actionObj = $this->getAction( $name, $target, $context );

		// TODO: When we no longer need to instantiate the action in order to determine the info,
		// we will be able to return info for disabled actions as well.
		if ( !$actionObj ) {
			return null;
		}

		return new ActionInfo( [
			'name' => $actionObj->getName(),
			'restriction' => $actionObj->getRestriction(),
			'needsReadRights' => $actionObj->needsReadRights(),
			'requiresWrite' => $actionObj->requiresWrite(),
			'requiresUnblock' => $actionObj->requiresUnblock(),
		] );
	}

	/**
	 * Get the name of the action that will be executed, not necessarily the one
	 * passed through the "action" request parameter. Actions disabled in
	 * $wgActions will be replaced by "nosuchaction".
	 *
	 * @param IContextSource $context
	 * @return string Action name
	 */
	public function getActionName( IContextSource $context ): string {
		// Trying to get a WikiPage for NS_SPECIAL etc. will result
		// in WikiPageFactory::newFromTitle throwing "Invalid or virtual namespace -1 given."
		// For SpecialPages et al, default to action=view.
		if ( !$context->canUseWikiPage() ) {
			return 'view';
		}

		$request = $context->getRequest();
		$actionName = $request->getRawVal( 'action' ) ?? 'view';

		// Normalize to lowercase
		$actionName = strtolower( $actionName );

		// Check for disabled actions
		if ( $this->getActionSpec( $actionName ) === false ) {
			// We could just set the action to 'nosuchaction' here and proceed,
			// but there should never be an action with the name 'nosuchaction'
			// and so getAction will return null, and then we would return
			// 'nosuchaction' anyway, so lets just return now
			return 'nosuchaction';
		}

		if ( $actionName === 'historysubmit' ) {
			// Compatibility with old URLs for no-JS form submissions from action=history (T323338, T22966).
			// (This is needed to handle diff links; other uses of 'historysubmit' are handled in MediaWiki.php.)
			$actionName = 'view';
		} elseif ( $actionName === 'editredlink' ) {
			$actionName = 'edit';
		}

		$this->hookRunner->onGetActionName( $context, $actionName );

		$action = $this->getAction(
			$actionName,
			$this->getArticle( $context ),
			$context
		);

		// Might not be an Action object if the action is not recognized (so $action could
		// be null) but should never be false because we already handled disabled actions
		// above.
		if ( $action instanceof Action ) {
			return $action->getName();
		}

		return 'nosuchaction';
	}

	/**
	 * Protected to allow overriding with a partial mock in unit tests
	 *
	 * @codeCoverageIgnore
	 *
	 * @param IContextSource $context
	 * @return Article
	 */
	protected function getArticle( IContextSource $context ): Article {
		return Article::newFromWikiPage( $context->getWikiPage(), $context );
	}

	/**
	 * Get the names of all registered actions, including the ones defined for
	 * only certain content models.
	 *
	 * @since 1.44
	 * @return string[]
	 */
	public function getAllActionNames() {
		$allActions = array_merge( array_keys( self::CORE_ACTIONS ), array_keys( $this->actionsConfig ) );
		$models = $this->contentHandlerFactory->getContentModels();
		foreach ( $models as $model ) {
			$handler = $this->contentHandlerFactory->getContentHandler( $model );
			$allActions = array_merge( $allActions, array_keys( $handler->getActionOverrides() ) );
		}
		return array_unique( $allActions );
	}

}
