<?php
/**
 * Value object representing page meta-data of an existing page.
 *
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

use MediaWiki\Linker\LinkTarget;
use Wikimedia\Assert\Assert;

/**
 * Value object representing page meta-data of an existing page.
 * Provides access to the page's current revision for convenience and efficiency.
 *
 * @since 1.31
 */
class PageRecord implements PageIdentity {

	/**
	 * @var PageIdentity
	 */
	private $mIdentity = null;

	/**
	 * @var bool
	 */
	private $mIsRedirect = false;

	/**
	 * @var int
	 */
	private $mLatest = 0;

	/**
	 * @var int
	 */
	private $mSize = 0;

	/**
	 * @var string
	 */
	private $mLanguage = null;

	/**
	 * @var string
	 */
	private $mTouched = '19700101000000';

	/**
	 * @var string
	 */
	private $mLinksUpdated = '19700101000000';

	/**
	 * @var RevisionStoreRecord|callable|null The current revision, or null if
	 * there is no current revision, or a callback that returns the current revision.
	 * Will be called with the following signature: ( PageRecord ): RevisionStoreRecord.
	 */
	private $mCurrentRevision;

	/**
	 * Constructor and clear the article
	 *
	 * @param object|null $row A row from the page table, as a raw object
	 * @param RevisionStoreRecord|callback|null $currentRevision The current revision, or null if
	 *   there is no current revision, or a callback that returns the current revision. The
	 *   callback will be called with the following signature: ( PageRecord ): RevisionStoreRecord.
	 */
	public function __construct( $row, $currentRevision ) {
		Assert::parameterType( 'object', $row, '$row' );
		Assert::parameterType(
			RevisionStoreRecord::class . '|callable|null',
			$currentRevision,
			'$currentRevision'
		);

		$this->mIdentity = PageIdentityValue::newFromDBKey(
			intval( $row->page_id ),
			intval( $row->page_namespace ),
			$row->page_title
		);

		$this->mSize = intval( $row->page_len );
		$this->mLanguage = $row->page_lang;
		$this->mTouched = wfTimestamp( TS_MW, $row->page_touched );
		$this->mLinksUpdated = wfTimestampOrNull( TS_MW, $row->page_links_updated );
		$this->mIsRedirect = (bool)intval( $row->page_is_redirect );
		$this->mLatest = intval( $row->page_latest );

		$this->mCurrentRevision = $currentRevision;

		Assert::precondition( $this->mIdentity->exists(), 'Page must exist (page_id must be > 0)!' );
	}

	/**
	 * @return LinkTarget A LinkTarget referencing this page.
	 */
	public function getAsLinkTarget() {
		return $this->mIdentity->getAsLinkTarget();
	}

	/**
	 * @return PageIdentity The PageIdentity of this page.
	 */
	public function getPageIdentity() {
		return $this->mIdentity;
	}

	/**
	 * @return RevisionStoreRecord|null
	 */
	public function getCurrentRevision() {
		if ( !$this->mCurrentRevision ) {
			return null;
		}

		if ( !$this->mCurrentRevision instanceof RevisionStoreRecord ) {
			$revision = call_user_func( $this->mCurrentRevision, $this );

			Assert::postcondition( $revision instanceof RevisionRecord,
				'Revision callback must return a RevisionRecord'
			);
			$this->mCurrentRevision = $revision;
		}
	}

	/**
	 * @return int Page ID
	 */
	public function getId() {
		return $this->mIdentity->getId();
	}

	/**
	 * Tests if the article content represents a redirect
	 *
	 * @return bool
	 */
	public function isRedirect() {
		return $this->mIsRedirect;
	}

	/**
	 * Get the page_touched field
	 * @return string Containing GMT timestamp
	 */
	public function getTouched() {
		return $this->mTouched;
	}

	/**
	 * Get the page_links_updated field
	 * @return string|null Containing GMT timestamp
	 */
	public function getLinksTimestamp() {
		return $this->mLinksUpdated;
	}

	/**
	 * Get the page_latest field
	 * @return int The rev_id of current revision
	 */
	public function getLatest() {
		return (int)$this->mLatest;
	}

	/**
	 * Get the page_len field
	 * @return int
	 */
	public function getSize() {
		return $this->mSize;
	}

	/**
	 * Get the page_lang field
	 * @return string
	 */
	public function getLanguage() {
		return $this->mLanguage;
	}

	/**
	 * @return boolean Whether the page exists
	 */
	public function exists() {
		return true;
	}

	/**
	 * Get the namespace index.
	 *
	 * @see LinkTarget::getNamespace()
	 *
	 * @return int Namespace index
	 */
	public function getNamespace() {
		$this->mIdentity->getNamespace();
	}

	/**
	 * Convenience function to test if it is in the namespace
	 *
	 * @see LinkTarget::inNamespace()
	 *
	 * @param int $ns
	 *
	 * @return bool
	 */
	public function inNamespace( $ns ) {
		$this->mIdentity->inNamespace( $ns );
	}

	/**
	 * Returns the page's title in database key form (with underscores), without namespace prefix.
	 *
	 * @see LinkTarget::getDBkey()
	 *
	 * @return string Main part of the link, with underscores (for use in href attributes)
	 */
	public function getTitleDBkey() {
		$this->mIdentity->getTitleDBkey();
	}

	/**
	 * Returns the page's title in text form (with spaces), without namespace prefix.
	 *
	 * @see LinkTarget::getText()
	 *
	 * @return string
	 */
	public function getTitleText() {
		$this->mIdentity->getTitleDBkey();
	}

	/**
	 * Returns an informative human readable representation of the page,
	 * for use in logging and debugging.
	 *
	 * @return string
	 */
	public function __toString() {
		$this->mIdentity->__toString();
	}
}
