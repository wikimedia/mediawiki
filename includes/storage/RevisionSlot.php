<?php
namespace MediaWiki\Storage;

use Content;


/**
 * A RevisionSlot provides access to meta information about a revision slot and its content,
 * as well as the content itself.
 *
 * RevisionSlot implementations may not be pure value objects, and should not be serialized.
 * In particular, implementations may include a reference to a storage service to allow
 * lazy loading of content data.
 */
interface RevisionSlot {

	/**
	 * @return int
	 */
	public function getPageId();

	/**
	 * @return int
	 */
	public function getRevisionId();

	/**
	 * @return string
	 */
	public function getSlotName();

	/**
	 * Returns the slot's content.
	 *
	 * Implementations must ensure that the returned instance is safe for modification
	 * if the content is mutable, typically by returning $content->copy().
	 *
	 * @note The cost of calling this method may vary widely, as implementations are free to
	 * apply a lazy loading strategy to fetch the content on demand.
	 *
	 * @note Implementations of this method are not generally expected to apply any
	 * permission checks. Higher level code is expected to call getReadRestrictions()
	 * and apply the appropriate checks based on the return value.
	 *
	 * @throws RevisionContentException
	 * @return Content
	 */
	public function getContent();

	/**
	 * @throws RevisionContentException
	 * @return string
	 */
	public function getContentModel();

	/**
	 * @throws RevisionContentException
	 * @return string
	 */
	public function getTouched();

	/**
	 * Returns a list of permissions that grant read access to the slot's content.
	 * The content may be read of any of the given permissions applies.
	 * Null means no permissions are required. Empty means no permission is sufficient.
	 *
	 * @throws RevisionContentException
	 * @return string[]|null
	 */
	public function getReadRestrictions();

	/**
	 * Returns the slot content's hash.
	 *
	 * @note The cost of calling this method may vary widely, as implementations are free to
	 * calculate the hash on the fly, which may entail lazy-loading the content from storage.
	 *
	 * @return string The base36 SHA1 hash of the content.
	 */
	public function getSha1();

	/**
	 * Returns the slot content's nominal size.
	 *
	 * @note The cost of calling this method may vary widely, as implementations are free to
	 * calculate the size on the fly, which may entail lazy-loading the content from storage.
	 *
	 * @see Content::getSize().
	 *
	 * @return int The content size in bogo-bytes.
	 */
	public function getSize();

}