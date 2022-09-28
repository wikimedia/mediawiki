<?php
namespace MediaWiki\Specials\Contribute\Card;

class ContributeCard {

	/** @var string */
	private $title;

	/** @var string */
	private $icon;

	/** @var string */
	private $description;

	/** @var ContributeCardAction */
	private $action;

	public function __construct( string $title, string $description, string $icon, ContributeCardAction $action ) {
		$this->title = $title;
		$this->icon = $icon;
		$this->description = $description;
		$this->action = $action;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string {
		return $this->icon;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon( string $icon ): void {
		$this->icon = $icon;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( string $description ): void {
		$this->description = $description;
	}

	/**
	 * @return ContributeCardAction
	 */
	public function getAction(): ContributeCardAction {
		return $this->action;
	}

	/**
	 * @param ContributeCardAction $action
	 */
	public function setAction( ContributeCardAction $action ): void {
		$this->action = $action;
	}

	/**
	 * @return array
	 */
	public function toArray(): array {
		return [
			'title' => $this->title,
			'icon' => $this->icon,
			'description' => $this->description,
			'action' => $this->action->toArray()
		];
	}
}
