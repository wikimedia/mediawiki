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

namespace MediaWiki\Page\Event;

use MediaWiki\Page\PageReference;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\User\UserIdentity;

/**
 * Domain event representing page moves.
 * PageMovedEvent is a special case of an PageUpdatedEvent.
 * It exists as a separate event to accommodate listeners that are only
 * interested in the title change.
 *
 * @see PageUpdatedEvent
 *
 * @unstable until 1.45
 */
class PageMovedEvent extends PageEvent {
	public const TYPE = 'PageMoved';

	private PageReference $oldLocation;

	/**
	 * @param ProperPageIdentity $page The page identiy after the move.
	 * @param PageReference $oldLocation The page's location before the move.
	 * @param UserIdentity $performer The user performing the move.
	 */
	public function __construct(
		ProperPageIdentity $page,
		PageReference $oldLocation,
		UserIdentity $performer
	) {
		parent::__construct(
			PageUpdateCauses::CAUSE_MOVE,
			$page,
			$performer
		);

		$this->declareEventType( self::TYPE );
		$this->oldLocation = $oldLocation;
	}

	/**
	 * Returns the page identity as it would have been before the page was
	 * moved.
	 */
	public function getOldLocation(): PageReference {
		return $this->oldLocation;
	}

}
