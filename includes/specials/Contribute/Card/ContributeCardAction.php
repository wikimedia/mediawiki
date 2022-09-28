<?php

namespace MediaWiki\Specials\Contribute\Card;

class ContributeCardAction {
	/** @var string */
	private $action;

	/** @var string */
	private $actionText;

	/** @var string */
	private $actionType;

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

	/**
	 * @return string
	 */
	public function getActionType(): string {
		return $this->actionType;
	}

	/**
	 * @return string
	 */
	public function getAction(): string {
		return $this->action;
	}

	/**
	 * @return string
	 */
	public function getActionText(): string {
		return $this->actionText;
	}

	/**
	 * @param string $action
	 */
	public function setAction( string $action ): void {
		$this->action = $action;
	}

	/**
	 * @param string $actionText
	 */
	public function setActionText( string $actionText ): void {
		$this->actionText = $actionText;
	}

	/**
	 * @return array
	 */
	public function toArray(): array {
		return [
			'action' => $this->action,
			'actionText' => $this->actionText,
			'actionType' => $this->actionType,
		];
	}
}
