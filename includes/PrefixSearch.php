<?php

class PrefixSearch {
	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 * @param string $search
	 * @param int $limit
	 * @return array of strings
	 */
	public static function titleSearch( $search, $limit ) {
		$search = trim( $search );
		if( $search == '' ) {
			return array(); // Return empty result
		}

		$title = Title::newFromText( $search );
		if( $title && $title->getInterwiki() == '' ) {
			$ns = $title->getNamespace();
			return self::searchBackend(
				$title->getNamespace(), $title->getText(), $limit );
		}

		// Is this a namespace prefix?
		$title = Title::newFromText( $search . 'Dummy' );
		if( $title && $title->getText() == 'Dummy'
			&& $title->getNamespace() != NS_MAIN
			&& $title->getInterwiki() == '' ) {
			return self::searchBackend(
				$title->getNamespace(), '', $limit );
		}

		return self::searchBackend( 0, $search, $limit );
	}
	
	
	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 * @param string $search
	 * @param int $limit
	 * @return array of strings
	 */
	protected static function searchBackend( $ns, $search, $limit ) {
		if( $ns == NS_MEDIA ) {
			$ns = NS_IMAGE;
		} elseif( $ns == NS_SPECIAL ) {
			return self::specialSearch( $search, $limit );
		}
		
		$srchres = array();
		if( wfRunHooks( 'PrefixSearchBackend', array( $ns, $search, $limit, &$srchres ) ) ) {
			return self::defaultSearchBackend( $ns, $search, $limit );
		}
		return $srchres;
	}
	
	/**
	 * Prefix search special-case for Special: namespace.
	 */
	protected static function specialSearch( $search, $limit ) {
		global $wgContLang;
		$searchKey = $wgContLang->caseFold( $search );
		
		// Unlike SpecialPage itself, we want the canonical forms of both
		// canonical and alias title forms...
		SpecialPage::initList();
		SpecialPage::initAliasList();
		$keys = array();
		foreach( array_keys( SpecialPage::$mList ) as $page ) {
			$keys[$wgContLang->caseFold( $page )] = $page;
		}
		foreach( $wgContLang->getSpecialPageAliases() as $page => $aliases ) {
			foreach( $aliases as $alias ) {
				$keys[$wgContLang->caseFold( $alias )] = $alias;
			}
		}
		ksort( $keys );
		
		$srchres = array();
		foreach( $keys as $pageKey => $page ) {
			if( $searchKey === '' || strpos( $pageKey, $searchKey ) === 0 ) {
				$srchres[] = Title::makeTitle( NS_SPECIAL, $page )->getPrefixedText();
			}
			if( count( $srchres ) >= $limit ) {
				break;
			}
		}
		return $srchres;
	}
	
	/**
	 * Unless overridden by PrefixSearchBackend hook...
	 * This is case-sensitive except the first letter (per $wgCapitalLinks)
	 *
	 * @param int $ns Namespace to search in
	 * @param string $search term
	 * @param int $limit max number of items to return
	 * @return array of title strings
	 */
	protected static function defaultSearchBackend( $ns, $search, $limit ) {
		global $wgCapitalLinks, $wgContLang;
		
		if( $wgCapitalLinks ) {
			$search = $wgContLang->ucfirst( $search );
		}
		
		// Prepare nested request
		$req = new FauxRequest(array (
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => $ns,
			'aplimit' => $limit,
			'apprefix' => $search
		));

		// Execute
		$module = new ApiMain($req);
		$module->execute();

		// Get resulting data
		$data = $module->getResultData();

		// Reformat useful data for future printing by JSON engine
		$srchres = array ();
		foreach ($data['query']['allpages'] as & $pageinfo) {
			// Note: this data will no be printable by the xml engine
			// because it does not support lists of unnamed items
			$srchres[] = $pageinfo['title'];
		}
		
		return $srchres;
	}

}

?>