<?php

/**
 * @author Addshore
 *
 * @since 1.27
 */
interface LinkTarget {

	/**
	 * Get the namespace index.
	 *
	 * @return int Namespace index
	 */
	public function getNamespace();

	/**
	 * Get the link fragment (i.e. the bit after the #) in text form.
	 *
	 * @return string link fragment
	 */
	public function getFragment();

	/**
	 * Whether the link target has a fragment
	 *
	 * @return bool
	 */
	public function hasFragment();

	/**
	 * Get the main part with underscores.
	 *
	 * @return string Main part of the link, with underscores (for use in href attributes)
	 */
	public function getDBkey();

	/**
	 * Returns the link in text form, without namespace prefix or fragment.
	 *
	 * This is computed from the DB key by replacing any underscores with spaces.
	 *
	 * @return string
	 */
	public function getText();

	/**
	 * Creates a new LinkTarget for a different fragment of the same page.
	 * It is expected that the same type of object will be returned, but the
	 * only requirement is that it is a LinkTarget.
	 *
	 * @param string $fragment The fragment name, or "" for the entire page.
	 *
	 * @return LinkTarget
	 */
	public function createFragmentTarget( $fragment );
}
