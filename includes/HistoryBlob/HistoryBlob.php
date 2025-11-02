<?php
/**
 * Efficient concatenated text storage.
 *
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Base class for general text storage via the "object" flag in old_flags, or
 * two-part external storage URLs. Used for represent efficient concatenated
 * storage, and migration-related pointer objects.
 */
interface HistoryBlob {
	/**
	 * Adds an item of text, returns a stub object which points to the item.
	 * You must call setLocation() on the stub object before storing it to the
	 * database
	 *
	 * @param string $text
	 *
	 * @return string The key for getItem()
	 */
	public function addItem( $text );

	/**
	 * Get item by key, or false if the key is not present
	 *
	 * @param string $key
	 *
	 * @return string|bool
	 */
	public function getItem( $key );

	/**
	 * Set the "default text"
	 * This concept is an odd property of the current DB schema, whereby each text item has a revision
	 * associated with it. The default text is the text of the associated revision. There may, however,
	 * be other revisions in the same object.
	 *
	 * Default text is not required for two-part external storage URLs.
	 *
	 * @param string $text
	 */
	public function setText( $text );

	/**
	 * Get default text.
	 *
	 * @return string|false
	 */
	public function getText();
}
