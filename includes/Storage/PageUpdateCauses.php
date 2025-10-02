<?php
/**
 * @license GPL-2.0-or-later
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
