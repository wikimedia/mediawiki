<?php
namespace MediaWiki\Page;

/**
 * Data record representing a page that currently exists as
 * an editable page on a wiki.
 *
 * @note This is intended to become an alias for PageRecord, once PageRecord is guaranteed
 *       to be immutable and to represent existing pages.
 *
 * @stable to type
 *
 * @since 1.36
 */
interface ExistingPageRecord extends PageRecord, ProperPageIdentity {

	/**
	 * Always true.
	 *
	 * @return bool
	 */
	public function exists(): bool;
}
