<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Entry point : initialise variables and call subfunctions.
 * @param string $par Becomes "FOO" when called like Special:Allpages/FOO (default NULL)
 */
function wfSpecialAllpages( $par=NULL, $specialPage ) {
	global $indexMaxperpage, $toplevelMaxperpage, $wgRequest, $wgOut, $wgContLang;
	# Config
	$indexMaxperpage = 960;
	$toplevelMaxperpage = 50;
	# GET values
	$from = $wgRequest->getVal( 'from' );
	$namespace = $wgRequest->getInt( 'namespace' );
	
	$namespaces = $wgContLang->getNamespaces();

	if( !in_array($namespace, array_keys($namespaces)) )
		$namespace = 0;

	$wgOut->setPagetitle( $namespace > 0 ?
		wfMsg( 'allinnamespace', $namespaces[$namespace] ) :
		wfMsg( 'allarticles' )
		);
	
	if ( isset($par) ) {
		indexShowChunk( $namespace, $par, $specialPage->including() );
	} elseif ( isset($from) ) {
		indexShowChunk( $namespace, $from, $specialPage->including() );
	} else {
		indexShowToplevel ( $namespace, $specialPage->including() );
	}
}

/**
 * HTML for the top form
 * @param integer $namespace A namespace constant (default NS_MAIN).
 * @param string $from Article name we are starting listing at.
 */
function indexNamespaceForm ( $namespace = NS_MAIN, $from = '' ) {
	global $wgContLang, $wgScript;
	$t = Title::makeTitle( NS_SPECIAL, "Allpages" );

	$namespaceselect = HTMLnamespaceselector($namespace, null);

	$frombox = "<input type='text' size='20' name='from' id='nsfrom' value=\""
	            . htmlspecialchars ( $from ) . '"/>';
	$submitbutton = '<input type="submit" value="' . wfMsgHtml( 'allpagessubmit' ) . '" />';
	
	$out = "<div class='namespaceoptions'><form method='get' action='{$wgScript}'>";
	$out .= '<input type="hidden" name="title" value="'.$t->getPrefixedText().'" />';
	$out .= "
<table id='nsselect' class='allpages'>
	<tr>
		<td align='right'>" . wfMsgHtml('allpagesfrom') . "</td>
		<td align='left'><label for='nsfrom'>$frombox</label></td>
	</tr>
	<tr>    
		<td align='right'><label for='namespace'>" . wfMsgHtml('namespace') . "</label></td>
		<td align='left'>
			$namespaceselect $submitbutton
		</td>
	</tr>
</table>
";
	$out .= '</form></div>';
		return $out;
}

/**
 * @param integer $namespace (default NS_MAIN)
 */
function indexShowToplevel ( $namespace = NS_MAIN, $including = false ) {
	global $wgOut, $indexMaxperpage, $toplevelMaxperpage, $wgContLang, $wgRequest, $wgUser;
	$sk = $wgUser->getSkin();
	$fname = "indexShowToplevel";

	# TODO: Either make this *much* faster or cache the title index points
	# in the querycache table.

	$dbr =& wfGetDB( DB_SLAVE );
	$page = $dbr->tableName( 'page' );
	$fromwhere = "FROM $page WHERE page_namespace=$namespace";
	$order_arr = array ( 'ORDER BY' => 'page_title' );
	$order_str = 'ORDER BY page_title';
	$out = "";
	$where = array( 'page_namespace' => $namespace );

	global $wgMemc, $wgDBname;
	$key = "$wgDBname:allpages:ns:$namespace";
	$lines = $wgMemc->get( $key );
	
	if( !is_array( $lines ) ) {
		$firstTitle = $dbr->selectField( 'page', 'page_title', $where, $fname, array( 'LIMIT' => 1 ) );
		$lastTitle = $firstTitle;
		
		# This array is going to hold the page_titles in order.
		$lines = array( $firstTitle );
		
		# If we are going to show n rows, we need n+1 queries to find the relevant titles.
		$done = false;
		for( $i = 0; !$done; ++$i ) {
			// Fetch the last title of this chunk and the first of the next
			$chunk = is_null( $lastTitle )
				? '1=1'
				: 'page_title >= ' . $dbr->addQuotes( $lastTitle );
			$sql = "SELECT page_title $fromwhere AND $chunk $order_str " .
				$dbr->limitResult( 2, $indexMaxperpage - 1 );
			$res = $dbr->query( $sql, $fname );
			if ( $s = $dbr->fetchObject( $res ) ) {
				array_push( $lines, $s->page_title );
			} else {
				// Final chunk, but ended prematurely. Go back and find the end.
				$endTitle = $dbr->selectField( 'page', 'MAX(page_title)',
					array(
						'page_namespace' => $namespace,
						$chunk
					), $fname );
				array_push( $lines, $endTitle );
				$done = true;
			}
			if( $s = $dbr->fetchObject( $res ) ) {
				array_push( $lines, $s->page_title );
				$lastTitle = $s->page_title;
			} else {
				// This was a final chunk and ended exactly at the limit.
				// Rare but convenient!
				$done = true;
			}
			$dbr->freeResult( $res );
		}
		$wgMemc->add( $key, $lines, 3600 );
	}
	
	// If there are only two or less sections, don't even display them.
	// Instead, display the first section directly.
	if( count( $lines ) <= 2 ) {
		indexShowChunk( $namespace, '', false, $including );
		return;
	}

	# At this point, $lines should contain an even number of elements.
	$out .= "<table style='background: inherit;'>";
	while ( count ( $lines ) > 0 ) {
		$inpoint = array_shift ( $lines );
		$outpoint = array_shift ( $lines );
		$out .= indexShowline ( $inpoint, $outpoint, $namespace, false );
	}
	$out .= '</table>';
	
	$nsForm = indexNamespaceForm ( $namespace, '', false );
	
	# Is there more?
	if ( $including ) {
		$out2 = '';
	} else {
		$morelinks = '';
		if ( $morelinks != '' ) {
			$out2 = '<table style="background: inherit;" width="100%" cellpadding="0" cellspacing="0" border="0">';
			$out2 .= '<tr valign="top"><td align="left">' . $nsForm;
			$out2 .= '</td><td align="right" style="font-size: smaller; margin-bottom: 1em;">';
			$out2 .= $morelinks . '</td></tr></table><hr />';
		} else {
			$out2 = $nsForm . '<hr />';
		}
	}

	$wgOut->addHtml( $out2 . $out );
}

/**
 * @todo Document
 * @param string $from 
 * @param integer $namespace (Default NS_MAIN)
 */
function indexShowline( $inpoint, $outpoint, $namespace = NS_MAIN ) {
	global $wgOut, $wgLang, $wgUser;
	$sk = $wgUser->getSkin();
	$dbr =& wfGetDB( DB_SLAVE );

	$inpointf = htmlspecialchars( str_replace( '_', ' ', $inpoint ) );
	$outpointf = htmlspecialchars( str_replace( '_', ' ', $outpoint ) );
	$queryparams = ($namespace ? "namespace=$namespace" : '');
	$special = Title::makeTitle( NS_SPECIAL, 'Allpages/' . $inpoint );
	$link = $special->escapeLocalUrl( $queryparams );
	
	$out = wfMsg(
		'alphaindexline',
		"<a href=\"$link\">$inpointf</a></td><td><a href=\"$link\">",
		"</a></td><td align=\"left\"><a href=\"$link\">$outpointf</a>"
	);
	return '<tr><td align="right">'.$out.'</td></tr>';
}

/**
 * @param integer $namespace (Default NS_MAIN)
 * @param string $from list all pages from this name (default FALSE)
 */
function indexShowChunk( $namespace = NS_MAIN, $from, $including = false ) {
	global $wgOut, $wgUser, $indexMaxperpage, $wgContLang;

	$fname = 'indexShowChunk';
	
	$sk = $wgUser->getSkin();

	$fromTitle = Title::newFromURL( $from );
	$fromKey = is_null( $fromTitle ) ? '' : $fromTitle->getDBkey();
	
	$dbr =& wfGetDB( DB_SLAVE );
	$res = $dbr->select( 'page',
		array( 'page_namespace', 'page_title', 'page_is_redirect' ),
		array(
			'page_namespace' => $namespace,
			'page_title >= ' . $dbr->addQuotes( $fromKey )
		),
		$fname,
		array(
			'ORDER BY'  => 'page_title',
			'LIMIT'     => $indexMaxperpage + 1,
			'USE INDEX' => 'name_title',
		)
	);

	### FIXME: side link to previous

	$n = 0;
	$out = '<table style="background: inherit;" border="0" width="100%">';
	
	$namespaces = $wgContLang->getFormattedNamespaces();
	while( ($n < $indexMaxperpage) && ($s = $dbr->fetchObject( $res )) ) {
		$t = Title::makeTitle( $s->page_namespace, $s->page_title );
		if( $t ) {
			$link = ($s->page_is_redirect ? '<div class="allpagesredirect">' : '' ) . 
				$sk->makeKnownLinkObj( $t, htmlspecialchars( $t->getText() ), false, false ) .
				($s->page_is_redirect ? '</div>' : '' );
		} else {
			$link = '[[' . htmlspecialchars( $s->page_title ) . ']]';
		}
		if( $n % 3 == 0 ) {
			$out .= '<tr>';
		}
		$out .= "<td>$link</td>";
		$n++;
		if( $n % 3 == 0 ) {
			$out .= '</tr>';
		}
	}
	if( ($n % 3) != 0 ) {
		$out .= '</tr>';
	}
	$out .= '</table>';
	
	if ( $including ) {
		$out2 = '';
	} else {
		$nsForm = indexNamespaceForm ( $namespace, $from );
		$out2 = '<table style="background: inherit;" width="100%" cellpadding="0" cellspacing="0" border="0">';
		$out2 .= '<tr valign="top"><td align="left">' . $nsForm;
		$out2 .= '</td><td align="right" style="font-size: smaller; margin-bottom: 1em;">' .
				$sk->makeKnownLink( $wgContLang->specialPage( "Allpages" ),
					wfMsg ( 'allpages' ) );
		if ( ($n == $indexMaxperpage) && ($s = $dbr->fetchObject( $res )) ) {
			$namespaceparam = $namespace ? "&namespace=$namespace" : "";
			$out2 .= " | " . $sk->makeKnownLink(
				$wgContLang->specialPage( "Allpages" ),
				wfMsg ( 'nextpage', $s->page_title ),
				"from=" . wfUrlEncode ( $s->page_title ) . $namespaceparam );
		}
		$out2 .= "</td></tr></table><hr />";
	}

	$wgOut->addHtml( $out2 . $out );
}

?>
