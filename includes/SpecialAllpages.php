<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Entry point : initialise variables and call subfunctions.
 * @param string $par ????? (default NULL)
 */
function wfSpecialAllpages( $par=NULL ) {
	global $indexMaxperpage, $toplevelMaxperpage, $wgRequest, $wgOut, $wgContLang;
	$indexMaxperpage = 480;
	$toplevelMaxperpage = 50;
	$from = $wgRequest->getVal( 'from' );
	$namespace = $wgRequest->getInt( 'namespace' );
	$names = $wgContLang->getNamespaces();
	if( !isset( $names[$namespace] ) ) {
		$namespace = 0;
	}
	$wgOut->setPagetitle ( $namespace > 0 ? wfMsg ( 'allpagesnamespace', $names[$namespace] )
	                                      : wfMsg ( 'allarticles' ) );

	if ( $par ) {
		indexShowChunk( $par, $namespace );
	} elseif ( $from ) {
		indexShowChunk( $from, $namespace );
	} else {
		indexShowToplevel ( $namespace );
	}
}

/**
 * HTML for the top form
 * @param integer $namespace A namespace constant (default NS_MAIN).
 * @param string $from Article name we are starting listing at.
 */
function namespaceForm ( $namespace = NS_MAIN, $from = '' ) {
	global $wgContLang, $wgScript;

	$t = Title::makeTitle( NS_SPECIAL, "Allpages" );

	$namespaceselect = '<select name="namespace">';
	$arr = $wgContLang->getNamespaces();
	for ( $i = 0; $i <= 17; $i++ ) {
		$namespacename = str_replace ( '_', ' ', $arr[$i] );
		$n = ($i == 0) ? wfMsg ( 'articlenamespace' ) : $namespacename;
		$sel = ($i == $namespace) ? ' selected="selected"' : '';
		$namespaceselect .= "<option value='{$i}'{$sel}>{$n}</option>";
	}
	$namespaceselect .= '</select>';

	$frombox = '<input type="text" size="20" name="from" value="'
	            . htmlspecialchars ( $from ) . '"/>';
	$submitbutton = '<input type="submit" value="' . wfMsg( 'allpagessubmit' ) . '" />';

	$out = "<div class='namespaceselector'><form method='get' action='{$wgScript}'>";
	$out .= '<input type="hidden" name="title" value="'.$t->getPrefixedText().'" />';
	$out .= wfMsg ( 'allpagesformtext1', $frombox ) . '<br />';
	$out .= wfMsg ( 'allpagesformtext2', $namespaceselect, $submitbutton );
	$out .= '</form></div>';
	return $out;
}

/**
 * @todo Document
 * @param integer $namespace (default NS_MAIN)
 */
function indexShowToplevel ( $namespace = NS_MAIN ) {
	global $wgOut, $indexMaxperpage, $toplevelMaxperpage, $wgContLang, $wgRequest, $wgUser;
	$sk = $wgUser->getSkin();
	$fname = "indexShowToplevel";
	$namespace = intval ($namespace);

	# TODO: Either make this *much* faster or cache the title index points
	# in the querycache table.

	$dbr =& wfGetDB( DB_SLAVE );
	$page = $dbr->tableName( 'page' );
	$fromwhere = "FROM $page WHERE page_namespace=$namespace";
	$order_arr = array ( 'ORDER BY' => 'page_title' );
	$order_str = 'ORDER BY page_title';
	$out = "";
	$where = array( 'page_namespace' => $namespace );

	$count = $dbr->selectField( 'page', 'COUNT(*)', $where, $fname );
	$sections = ceil( $count / $indexMaxperpage );

	if ( $sections < 3 ) {
		# If there are only two or less sections, don't even display them.
		# Instead, display the first section directly.
		indexShowChunk( '', $namespace );
		return;
	}

	# We want to display $toplevelMaxperpage lines starting at $offset.
	# NOTICE: $offset starts at 0
	$offset = intval ( $wgRequest->getVal( 'offset' ) );
	if ( $offset < 0 ) { $offset = 0; }
	if ( $offset >= $sections ) { $offset = $sections - 1; }

	# Where to stop? Notice that this can take the value of $sections, but $offset can't, because if
	# we're displaying only the very last section, we still need two DB queries to find the titles
	$stopat = ( $offset + $toplevelMaxperpage < $sections )
	          ? $offset + $toplevelMaxperpage : $sections ;

	# This array is going to hold the page_titles in order.
	$lines = array();

	# If we are going to show n rows, we need n+1 queries to find the relevant titles.
	for ( $i = $offset; $i <= $stopat; $i++ ) {
		if ( $i == $sections )			# if we're displaying the last section, we need to
			$from = $count-1;			# find the last page_title in the DB
		else if ( $i > $offset )
			$from = $i * $indexMaxperpage - 1;
		else
			$from = $i * $indexMaxperpage;
		$limit = ( $i == $offset || $i == $stopat ) ? 1 : 2;
		$sql = "SELECT page_title $fromwhere $order_str " . $dbr->limitResult ( $limit, $from );
		$res = $dbr->query( $sql, $fname );
		$s = $dbr->fetchObject( $res );
		array_push ( $lines, $s->page_title );
		if ( $s = $dbr->fetchObject( $res ) ) {
			array_push ( $lines, $s->page_title );
		}
		$dbr->freeResult( $res );
	}

	# At this point, $lines should contain an even number of elements.
	$out .= "<table style='background: inherit;'>";
	while ( count ( $lines ) > 0 ) {
		$inpoint = array_shift ( $lines );
		$outpoint = array_shift ( $lines );
		$out .= indexShowline ( $inpoint, $outpoint, $namespace );
	}
	$out .= '</table>';

	$nsForm = namespaceForm ( $namespace );

	# Is there more?
	$morelinks = '';
	if ( $offset > 0 ) {
		$morelinks = $sk->makeKnownLink (
			$wgContLang->specialPage ( 'Allpages' ),
			wfMsg ( 'allpagesprev' ),
			( $offset > $toplevelMaxperpage ) ? 'offset='.($offset-$toplevelMaxperpage) : ''
		);
	}
	if ( $stopat < $sections-1 ) {
		if ( $morelinks != '' ) { $morelinks .= " | "; }
		$morelinks .= $sk->makeKnownLink (
			$wgContLang->specialPage ( 'Allpages' ),
			wfMsg ( 'allpagesnext' ),
			'offset=' . ($offset + $toplevelMaxperpage)
		);
	}

	if ( $morelinks != '' ) {
		$out2 = '<table style="background: inherit;" width="100%" cellpadding="0" cellspacing="0" border="0">';
		$out2 .= '<tr valign="top"><td align="left">' . $nsForm;
		$out2 .= '</td><td align="right" style="font-size: smaller; margin-bottom: 1em;">';
		$out2 .= $morelinks . '</td></tr></table><hr />';
	} else {
		$out2 = $nsForm . '<hr />';
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
	$queryparams = $namespace ? ('namespace='.intval($namespace)) : '';
	$special = Title::makeTitle( NS_SPECIAL, 'Allpages/' . $inpoint );
	$link = $special->escapeLocalUrl( $queryparams );
	
	$out = wfMsg(
		'alphaindexline',
		"<a href=\"$link\">$inpointf</a></td><td><a href=\"$link\">",
		"</a></td><td align=\"left\"><a href=\"$link\">$outpointf</a>"
	);
	return '<tr><td align="right">'.$out.'</td></tr>';
}

function indexShowChunk( $from, $namespace = NS_MAIN ) {
	global $wgOut, $wgUser, $indexMaxperpage, $wgContLang;
	$sk = $wgUser->getSkin();
	$maxPlusOne = $indexMaxperpage + 1;
	$namespacee = intval($namespace);

	$out = '';
	$dbr =& wfGetDB( DB_SLAVE );
	$page = $dbr->tableName( 'page' );
	
	$fromTitle = Title::newFromURL( $from );
	$fromKey = is_null( $fromTitle ) ? '' : $fromTitle->getDBkey();
	
	$sql = "SELECT page_title FROM $page WHERE page_namespace=$namespacee" .
		" AND page_title >= ".  $dbr->addQuotes( $fromKey ) .
		" ORDER BY page_title LIMIT " . $maxPlusOne;
	$res = $dbr->query( $sql, 'indexShowChunk' );

	### FIXME: side link to previous

	$n = 0;
	$out = '<table style="background: inherit;" border="0" width="100%">';
	while( ($n < $indexMaxperpage) && ($s = $dbr->fetchObject( $res )) ) {
		$t = Title::makeTitle( $namespacee, $s->page_title );
		if( $t ) {
			$link = $sk->makeKnownLinkObj( $t, $t->getText() );
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

	$nsForm = namespaceForm ( $namespace, $from );
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

	$wgOut->addHtml( $out2 . $out );
}

?>
