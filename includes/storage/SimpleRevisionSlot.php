<?php

namespace MediaWiki\Storage;

use Content;
use Revision;

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
 * @since 1.27
 *
 * @file
 * @ingroup Storage
 *
 * @author Daniel Kinzler
 */

/**
 * A SimpleRevisionSlot is a value object representing the meta-information about a slot.
 */
class SimpleRevisionSlot implements RevisionSlot {

	/**
	 * @var int
	 */
	private $pageId;

	/**
	 * @var int
	 */
	private $revisionId;

	/**
	 * @var string
	 */
	private $slotName;

	/**
	 * @var Content
	 */
	private $content;

	/**
	 * @var string
	 */
	private $touched;

	/**
	 * @var string[]|null
	 */
	private $restrictions;


	/**
	 * @param int $pageId
	 * @param int $revisionId
	 * @param string $slotName
	 * @param Content $content
	 * @param string $touched
	 * @param string[]|null $restrictions a list of permissions that grant read access to the slot's content.
	 *        Null means no permissions are required. Empty means no permission is sufficient.
	 */
	public function __construct( $pageId, $revisionId, $slotName, Content $content, $touched, array $restrictions = null ) {
		$this->pageId = $pageId;
		$this->revisionId = $revisionId;
		$this->slotName = $slotName;
		$this->content = $content;
		$this->touched = $touched;
		$this->restrictions = $restrictions;
	}

	/**
	 * @return string
	 */
	public function getContentModel() {
		return $this->getContent()->getModel();
	}

	/**
	 * @return string
	 */
	public function getTouched() {
		return $this->touched;
	}

	/**
	 * Returns a list of permissions that grant read access to the slot's content.
	 * The content may be read of any of the given permissions applies.
	 *
	 * @throws RevisionContentException
	 * @return string[]|null
	 */
	public function getReadRestrictions() {
		return $this->restrictions;
	}

	/**
	 * @return Content
	 */
	public function getContent() {
		return $this->content->copy();
	}

	/**
	 * @return int
	 */
	public function getPageId() {
		return $this->pageId;
	}

	/**
	 * @return int
	 */
	public function getRevisionId() {
		return $this->revisionId;
	}

	/**
	 * @return string
	 */
	public function getSlotName() {
		return $this->slotName;
	}

	/**
	 * Returns the slot content's hash.
	 *
	 * @note The cost of calling this method may vary widely, as implementations are free to
	 * calculate the hash on the fly, which may entail lazy-loading the content from storage.
	 *
	 * @return string The base36 SHA1 hash of the content.
	 */
	public function getSha1() {
		//FIXME: move base36Sha1 out of Revision
		Revision::base36Sha1( $this->content->serialize() );
	}

	/**
	 * Returns the slot content's nominal size.
	 * @see Content::getSize().
	 *
	 * @return int The content size in bogo-bytes.
	 */
	public function getSize() {
		return $this->content->getSize();
	}

}
