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
