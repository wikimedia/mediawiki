<?php

namespace MediaWiki\Search\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ShowSearchHitHook {
	/**
	 * Customize display of search hit.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $searchPage The SpecialSearch instance.
	 * @param ?mixed $result The SearchResult to show
	 * @param ?mixed $terms Search terms, for highlighting (unreliable as search engine dependent).
	 * @param ?mixed &$link HTML of link to the matching page. May be modified.
	 * @param ?mixed &$redirect HTML of redirect info. May be modified.
	 * @param ?mixed &$section HTML of matching section. May be modified.
	 * @param ?mixed &$extract HTML of content extract. May be modified.
	 * @param ?mixed &$score HTML of score. May be modified.
	 * @param ?mixed &$size HTML of page size. May be modified.
	 * @param ?mixed &$date HTML of of page modification date. May be modified.
	 * @param ?mixed &$related HTML of additional info for the matching page. May be modified.
	 * @param ?mixed &$html May be set to the full HTML that should be used to represent the search
	 *   hit. Must include the <li> ... </li> tags. Will only be used if the hook
	 *   function returned false.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onShowSearchHit( $searchPage, $result, $terms, &$link,
		&$redirect, &$section, &$extract, &$score, &$size, &$date, &$related, &$html
	);
}
