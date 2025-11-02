<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Interwiki;

/**
 * Service interface for looking up Interwiki records.
 *
 * Default implementation is ClassicInterwikiLookup.
 *
 * @since 1.28
 */
interface InterwikiLookup {

	/**
	 * Check whether an interwiki prefix exists
	 *
	 * @param string $prefix Interwiki prefix
	 * @return bool Whether it exists
	 */
	public function isValidInterwiki( $prefix );

	/**
	 * Get the Interwiki object for a given prefix
	 *
	 * @param string $prefix Interwiki prefix
	 * @return Interwiki|null|false Null for invalid, false for not found
	 */
	public function fetch( $prefix );

	/**
	 * Returns information about all interwiki prefixes, in the form of rows
	 * of the interwiki table. Each row may have the following keys:
	 *
	 * - iw_prefix: the prefix. Always present.
	 * - iw_url: the URL to use for linking, with $1 as a placeholder for the target page.
	 *           Always present.
	 * - iw_api: the URL of the API. Optional.
	 * - iw_wikiid: the wiki ID (usually the database name for local wikis). Optional.
	 * - iw_local: whether the wiki is local, and the "magic redirect" mechanism should apply.
	 *             Defaults to false.
	 * - iw_trans: whether "scary transclusion" is allowed for this site.
	 *             Defaults to false.
	 *
	 * The order of the rows matters! The *first* row matching a
	 * given URL is used by VisualEditor/Parsoid when converting external URLs to
	 * interwiki links. If, for example, both `labsconsole:` and
	 * `wikitech:` resolve to the same URL, but you want VisualEditor to prefer
	 * `wikitech` when adding new links, then the row for `wikitech` should
	 * come before the row for `labsconsole`.
	 *
	 * @param bool|null $local If set, limit output to local or non-local interwikis
	 * @return array[] interwiki rows.
	 */
	public function getAllPrefixes( $local = null );

	/**
	 * Purge the in-process and any persistent cache (e.g. memcached) for an interwiki prefix.
	 *
	 * @param string $prefix
	 */
	public function invalidateCache( $prefix );

}
