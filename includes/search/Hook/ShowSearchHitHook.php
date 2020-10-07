<?php

namespace MediaWiki\Search\Hook;

use SearchResult;
use SpecialSearch;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ShowSearchHitHook {
	/**
	 * Use this hook to customize display of search hit.
	 *
	 * @since 1.35
	 *
	 * @param SpecialSearch $searchPage
	 * @param SearchResult $result SearchResult to show
	 * @param string[] $terms Search terms, for highlighting (unreliable as search engine dependent)
	 * @param string &$link HTML of link to the matching page. May be modified.
	 * @param string &$redirect HTML of redirect info. May be modified.
	 * @param string &$section HTML of matching section. May be modified.
	 * @param string &$extract HTML of content extract. May be modified.
	 * @param string &$score HTML of score. May be modified.
	 * @param string &$size HTML of page size. May be modified.
	 * @param string &$date HTML of of page modification date. May be modified.
	 * @param string &$related HTML of additional info for the matching page. May be modified.
	 * @param string &$html May be set to the full HTML that should be used to represent the search
	 *   hit. Must include the `<li> ... </li>` tags. Will only be used if the hook
	 *   function returned false.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onShowSearchHit( $searchPage, $result, $terms, &$link,
		&$redirect, &$section, &$extract, &$score, &$size, &$date, &$related, &$html
	);
}
