<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use UnexpectedValueException;

/**
 * Defines the actions that can be blocked by a partial block. They are
 * always blocked by a sitewide block.
 *
 * NB: The terms "right" and "action" are often used to describe the same
 * string, depending on the context. E.g. a querystring might contain
 * 'action=edit', but the PermissionManager will refer to the 'edit' right.
 *
 * Here, we use "action", since a user may be in a group that has a
 * certain right, while also being blocked from performing that action.
 *
 * @since 1.37
 */
class BlockActionInfo {
	private HookRunner $hookRunner;

	/** @internal Public for testing only -- use getIdFromAction() */
	public const ACTION_UPLOAD = 1;

	/** @internal Public for testing only -- use getIdFromAction() */
	public const ACTION_MOVE = 2;

	/** @internal Public for testing only -- use getIdFromAction() */
	public const ACTION_CREATE = 3;

	/**
	 * Core block actions.
	 *
	 * Each key is an action string passed to PermissionManager::checkUserBlock
	 * Each value is a class constant for that action
	 *
	 * Each key has a corresponding message with key "ipb-action-$key"
	 *
	 * Core messages:
	 * ipb-action-upload
	 * ipb-action-move
	 * ipb-action-create
	 */
	private const CORE_BLOCK_ACTIONS = [
		'upload' => self::ACTION_UPLOAD,
		'move' => self::ACTION_MOVE,
		'create' => self::ACTION_CREATE,
	];

	public function __construct( HookContainer $hookContainer ) {
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Cache the array of actions
	 * @var int[]|null
	 */
	private $allBlockActions = null;

	/**
	 * @return int[]
	 */
	public function getAllBlockActions(): array {
		// Don't run the hook multiple times in the same request
		if ( !$this->allBlockActions ) {
			$this->allBlockActions = self::CORE_BLOCK_ACTIONS;
			$this->hookRunner->onGetAllBlockActions( $this->allBlockActions );
		}
		if ( count( $this->allBlockActions ) !== count( array_unique( $this->allBlockActions ) ) ) {
			throw new UnexpectedValueException( 'Blockable action IDs not unique' );
		}
		return $this->allBlockActions;
	}

	/**
	 * @param int $actionId
	 * @return string|false
	 */
	public function getActionFromId( int $actionId ) {
		return array_search( $actionId, $this->getAllBlockActions() );
	}

	/**
	 * @param string $action
	 * @return int|bool False if the action is not in the list of blockable actions
	 */
	public function getIdFromAction( string $action ) {
		return $this->getAllBlockActions()[$action] ?? false;
	}

}
