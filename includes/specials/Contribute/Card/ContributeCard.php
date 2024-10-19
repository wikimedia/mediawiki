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
 * @file
 */

namespace MediaWiki\Specials\Contribute\Card;

class ContributeCard {
	private string $title;
	private string $icon;
	private string $description;
	private ContributeCardAction $action;

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
