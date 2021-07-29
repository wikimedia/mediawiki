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

use Action;
use Article;
use IContextSource;
use McrRestoreAction;
use McrUndoAction;
use Psr\Log\LoggerInterface;
use RollbackAction;
use SpecialPageAction;
use UnwatchAction;
use WatchAction;
use Wikimedia\ObjectFactory;

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

	/** @var LoggerInterface */
	private $logger;

	/** @var ObjectFactory */
	private $objectFactory;

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
		'credits' => true,
		'delete' => true,
		'edit' => true,
		'editchangetags' => SpecialPageAction::class,
		'history' => true,
		'info' => true,
		'markpatrolled' => true,
		'mcrundo' => McrUndoAction::class,
		'mcrrestore' => McrRestoreAction::class,
		'protect' => true,
		'purge' => true,
		'raw' => true,
		'render' => true,
		'revert' => true,
		'submit' => true,
		'unprotect' => true,
		'unwatch' => true,
		'view' => true,
		'watch' => true,

		// Beginning of actions switched to using DI with an ObjectFactory spec
		'revisiondelete' => [
			'class' => SpecialPageAction::class,
		],
		'rollback' => [
			'class' => RollbackAction::class,
			'services' => [
				'ContentHandlerFactory',
				'RollbackPageFactory',
				'UserOptionsLookup',
				'WatchlistManager',
			],
		],
		'unwatch' => [
			'class' => UnwatchAction::class,
			'services' => [
				'WatchlistManager',
				'WatchedItemStore',
			],
		],
		'watch' => [
			'class' => WatchAction::class,
			'services' => [
				'WatchlistManager',
				'WatchedItemStore',
			],
		],
	];

	/**
	 * @param array $actionsConfig Configured actions (eg those added by extensions to $wgActions)
	 * @param LoggerInterface $logger
	 * @param ObjectFactory $objectFactory
	 */
	public function __construct(
		array $actionsConfig,
		LoggerInterface $logger,
		ObjectFactory $objectFactory
	) {
		$this->actionsConfig = $actionsConfig;
		$this->logger = $logger;
		$this->objectFactory = $objectFactory;
	}

	/**
	 * @param string $actionName should already be in all lowercase
	 * @return string|callable|bool|Action|array|null The spec for the action, in any valid form,
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
	 * @param Article $article
	 * @param IContextSource $context
	 * @return Action|bool|null False if the action is disabled, null if not recognized
	 */
	public function getAction(
		string $actionName,
		Article $article,
		IContextSource $context
	) {
		// Normalize to lowercase
		$actionName = strtolower( $actionName );

		$spec = $this->getActionSpec( $actionName );
		if ( $spec === null || $spec === false ) {
			// Either no such action exists (null) or the action is disabled (false)
			return $spec;
		}

		$overrides = $article->getActionOverrides();
		if ( isset( $overrides[ $actionName ] ) ) {
			// The Article class wants to override the action
			$spec = $overrides[ $actionName ];
			$this->logger->debug(
				'Overriding normal handler for {actionName}',
				[ 'actionName' => $actionName ]
			);
		}

		if ( $spec === true ) {
			// Old-style: use Action subclass based on name
			$spec = ucfirst( $actionName ) . 'Action';
		}

		// $spec is either a class name, a callable, a specific object to use, or an
		// ObjectFactory spec. Convert to ObjectFactory spec, or return the specific object.
		if ( is_string( $spec ) ) {
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
		return $actionObj;
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
		$actionName = $request->getVal( 'action', 'view' );

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

		// Workaround for T22966: inability of IE to provide an action dependent
		// on which submit button is clicked.
		if ( $actionName === 'historysubmit' ) {
			if ( $request->getBool( 'revisiondelete' ) ) {
				$actionName = 'revisiondelete';
			} elseif ( $request->getBool( 'editchangetags' ) ) {
				$actionName = 'editchangetags';
			} else {
				$actionName = 'view';
			}
		} elseif ( $actionName === 'editredlink' ) {
			$actionName = 'edit';
		}

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
	 * @param string $actionName
	 * @return bool
	 */
	public function actionExists( string $actionName ): bool {
		// Normalize to lowercase
		$actionName = strtolower( $actionName );

		// Null means no such action
		return ( $this->getActionSpec( $actionName ) !== null );
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

}
