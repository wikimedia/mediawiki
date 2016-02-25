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

}
