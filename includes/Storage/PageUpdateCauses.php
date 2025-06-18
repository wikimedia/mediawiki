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

namespace MediaWiki\Storage;

/**
 * Constants for representing well known causes for page updates.
 * Extensions may use different causes representing their specific reason
 * for updating pages.
 *
 * This is modeled as an interface to provide easy access to these constants to
 * both the emitter and the subscriber of events, without creating unnecessary
 * dependencies: Since PageUpdater and PageLatestRevisionChangedEvent both implement this
 * interface, callers of PageUpdater do not need to know about PageLatestRevisionChangedEvent,
 * and subscribers of PageLatestRevisionChangedEvent do not need to know about PageUpdater.
 *
 * @unstable until 1.45
 */
interface PageUpdateCauses {

	/** @var string The update was a deletion. */
	public const CAUSE_DELETE = 'delete';

	/** @var string The update was an undeletion. */
	public const CAUSE_UNDELETE = 'undelete';

	/** @var string The update was an import. */
	public const CAUSE_IMPORT = 'import';

	/** @var string The update was due to a page move. */
	public const CAUSE_MOVE = 'move';

	/** @var string The update was an edit. */
	public const CAUSE_EDIT = 'edit';

	/**
	 * @var string The update was a change to the page
	 *      protection (aka restrictions).
	 */
	public const CAUSE_PROTECTION_CHANGE = 'protection_change';

	/** @var string The update was caused by a file upload */
	public const CAUSE_UPLOAD = 'upload';

	/** @var string The update was caused by the rollback action */
	public const CAUSE_ROLLBACK = 'rollback';

	/** @var string The update was caused by the undo action */
	public const CAUSE_UNDO = 'undo';

}
