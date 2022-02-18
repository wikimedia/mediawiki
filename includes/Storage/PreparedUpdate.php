<?php
namespace MediaWiki\Storage;

use Content;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\RevisionRecord;
use ParserOutput;

/**
 * An object representing a page update during an edit.
 *
 * Instances of PreparedUpdate may be passed to hook handlers to provide them with
 * access to the rendered version of a revision that is about to be saved, or has
 * just been saved.
 *
 * MCR migration note: this replaces PreparedEdit
 *
 * @since 1.38
 * @ingroup Page
 */
interface PreparedUpdate {

	/**
	 * Returns the identity of the page being updated
	 *
	 * @return PageIdentity
	 */
	public function getPage(): PageIdentity;

	/**
	 * Returns the content of the given slot, with no audience checks.
	 *
	 * @param string $role slot role name
	 *
	 * @return Content
	 * @throws PageUpdateException If the slot is neither set for update nor inherited from the
	 *        parent revision.
	 */
	public function getRawContent( string $role ): Content;

	/**
	 * Whether the page will be countable after the edit.
	 *
	 * @return bool
	 */
	public function isCountable(): bool;

	/**
	 * Whether the page will be a redirect after the edit.
	 *
	 * @return bool
	 */
	public function isRedirect(): bool;

	/**
	 * Returns the update's target revision - that is, the revision that will be the current
	 * revision after the update.
	 *
	 * @return RevisionRecord
	 */
	public function getRevision(): RevisionRecord;

	/**
	 * Returns a RenderedRevision instance acting as a lazy holder for the ParserOutput
	 * of the revision.
	 *
	 * @return RenderedRevision
	 */
	public function getRenderedRevision(): RenderedRevision;

	/**
	 * Returns the role names of the slots added or updated by the new revision.
	 * Does not include the role names of slots that are being removed.
	 *
	 * @see RevisionSlotsUpdate::getModifiedRoles
	 *
	 * @return string[]
	 */
	public function getModifiedSlotRoles(): array;

	/**
	 * Returns the role names of the slots removed by the new revision.
	 *
	 * @return string[]
	 */
	public function getRemovedSlotRoles(): array;

	/**
	 * Returns the canonical parser output.
	 *
	 * Code that does not need access to the rendered HTML should use getParserOutputForMetaData()
	 * instead.
	 *
	 * @return ParserOutput
	 */
	public function getCanonicalParserOutput(): ParserOutput;

	/**
	 * Returns the canonical parser output without requiring rendering.
	 * It may not be safe to call getText() on the resulting ParserOutput.
	 *
	 * Code that does not need to the rendered HTML should prefer this method
	 * over getCanonicalParserOutput() since it will be significantly faster for
	 * some types of content. This would typically be the case for structured data,
	 * for which extracting data is simple, but rendering may require loading
	 * additional data.
	 *
	 * @return ParserOutput
	 */
	public function getParserOutputForMetaData(): ParserOutput;

}
