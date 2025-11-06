<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Contribute\Card;

class ContributeCardAction {
	private string $action;
	private string $actionText;
	private string $actionType;

	/**
	 * @param string $action the action's url or command to be attached to card element
	 * @param string $actionText the action's text to be shown on the bottom of the card element
	 * @param string $actionType the action's type, specifying if it is a url or a function, ...
	 */
	public function __construct( string $action, string $actionText, string $actionType ) {
		$this->action = $action;
		$this->actionText = $actionText;
		$this->actionType = $actionType;
	}

	public function getActionType(): string {
		return $this->actionType;
	}

	public function getAction(): string {
		return $this->action;
	}

	public function getActionText(): string {
		return $this->actionText;
	}

	public function setAction( string $action ): void {
		$this->action = $action;
	}

	public function setActionText( string $actionText ): void {
		$this->actionText = $actionText;
	}

	public function toArray(): array {
		return [
			'action' => $this->action,
			'actionText' => $this->actionText,
			'actionType' => $this->actionType,
		];
	}
}
