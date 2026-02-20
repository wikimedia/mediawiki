<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Contribute\Card;

class ContributeCard {

	public function __construct(
		private string $title,
		private string $description,
		private string $icon,
		private ContributeCardAction $action,
	) {
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function setTitle( string $title ): void {
		$this->title = $title;
	}

	public function getIcon(): string {
		return $this->icon;
	}

	public function setIcon( string $icon ): void {
		$this->icon = $icon;
	}

	public function getDescription(): string {
		return $this->description;
	}

	public function setDescription( string $description ): void {
		$this->description = $description;
	}

	public function getAction(): ContributeCardAction {
		return $this->action;
	}

	public function setAction( ContributeCardAction $action ): void {
		$this->action = $action;
	}

	public function toArray(): array {
		return [
			'title' => $this->title,
			'icon' => $this->icon,
			'description' => $this->description,
			'action' => $this->action->toArray()
		];
	}
}
