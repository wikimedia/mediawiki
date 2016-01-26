<?php

/**
 * @author Addshore
 *
 * @since 1.27
 */
interface ITitle {

	/**
	 * Get the namespace index
	 *
	 * @return int Namespace index
	 */
	public function getNamespace();

	/**
	 * Get the Title fragment (i.e.\ the bit after the #) in text form
	 *
	 * @return string Title fragment
	 */
	public function getFragment();

	/**
	 * Get the main part with underscores
	 *
	 * @return string Main part of the title, with underscores
	 */
	public function getDBkey();

	/**
	 * Returns the title in text form,
	 * without namespace prefix or fragment.
	 *
	 * This is computed from the DB key by replacing any underscores with spaces.
	 *
	 * @note To get a title string that includes the namespace and/or fragment,
	 *       use a TitleFormatter.
	 *
	 * @return string
	 */
	public function getText();

}
