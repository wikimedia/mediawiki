<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Entry point : initialise variables and call subfunctions.
 * @param $par String: becomes "FOO" when called like Special:Allpages/FOO (default NULL)
 * @param $specialPage @see SpecialPage object.
 */
function wfSpecialAllpages( $par=NULL, $specialPage ) {
	global $wgRequest, $wgOut, $wgContLang;

	# GET values
	$from = $wgRequest->getVal( 'from' );
	$namespace = $wgRequest->getInt( 'namespace' );

	$namespaces = $wgContLang->getNamespaces();

	$indexPage = new SpecialAllpages();

	if( !in_array($namespace, array_keys($namespaces)) )
		$namespace = 0;

	$wgOut->setPagetitle( $namespace > 0 ?
		wfMsg( 'allinnamespace', $namespaces[$namespace] ) :
		wfMsg( 'allarticles' )
		);

	if ( isset($par) ) {
		$indexPage->showChunk( $namespace, $par, $specialPage->including() );
	} elseif ( isset($from) ) {
		$indexPage->showChunk( $namespace, $from, $specialPage->including() );
	} else {
		$indexPage->showToplevel ( $namespace, $specialPage->including() );
	}
}

class SpecialAllpages {
	var $maxPerPage=960;
	var $topLevelMax=50;
	var $name='Allpages';
	# Determines, which message describes the input field 'nsfrom' (->SpecialPrefixindex.php)
	var $nsfromMsg='allpagesfrom';

/**
 * HTML for the top form
 * @param integer $namespace A namespace constant (default NS_MAIN).
 * @param string $from Article name we are starting listing at.
 */
function namespaceForm ( $namespace = NS_MAIN, $from = '' ) {
	global $wgScript;
	$t = Title::makeTitle( NS_SPECIAL, $this->name );

	$namespaceselect = HTMLnamespaceselector($namespace, null);

	$frombox = "<input type='text' size='20' name='from' id='nsfrom' value=\""
	            . htmlspecialchars ( $from ) . '"/>';
	$submitbutton = '<input type="submit" value="' . wfMsgHtml( 'allpagessubmit' ) . '" />';

	$out = "<div class='namespaceoptions'><form method='get' action='{$wgScript}'>";
	$out .= '<input type="hidden" name="title" value="'.$t->getPrefixedText().'" />';
	$out .= "
<table id='nsselect' class='allpages'>
	<tr>
		<td align='right'>" . wfMsgHtml($this->nsfromMsg) . "</td>
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
function showToplevel ( $namespace = NS_MAIN, $including = false ) {
	global $wgOut, $wgUser;
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
				? ''
				: 'page_title >= ' . $dbr->addQuotes( $lastTitle );
			$res = $dbr->select(
				'page', /* FROM */
				'page_title', /* WHAT */
				$where + array( $chunk),
				$fname,
				array ('LIMIT' => 2, 'OFFSET' => $this->maxPerPage - 1, 'ORDER BY' => 'page_title') );

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
		$this->showChunk( $namespace, '', $including );
		return;
	}

	# At this point, $lines should contain an even number of elements.
	$out .= "<table class='allpageslist' style='background: inherit;'>";
	while ( count ( $lines ) > 0 ) {
		$inpoint = array_shift ( $lines );
		$outpoint = array_shift ( $lines );
		$out .= $this->showline ( $inpoint, $outpoint, $namespace, false );
	}
	$out .= '</table>';
	$nsForm = $this->namespaceForm ( $namespace, '', false );

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
function showline( $inpoint, $outpoint, $namespace = NS_MAIN ) {
	global $wgUser;
	$sk = $wgUser->getSkin();
	$dbr =& wfGetDB( DB_SLAVE );

	$inpointf = htmlspecialchars( str_replace( '_', ' ', $inpoint ) );
	$outpointf = htmlspecialchars( str_replace( '_', ' ', $outpoint ) );
	$queryparams = ($namespace ? "namespace=$namespace" : '');
	$special = Title::makeTitle( NS_SPECIAL, $this->name . '/' . $inpoint );
	$link = $special->escapeLocalUrl( $queryparams );

	$out = wfMsgHtml(
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
function showChunk( $namespace = NS_MAIN, $from, $including = false ) {
	global $wgOut, $wgUser, $wgContLang;

	$fname = 'indexShowChunk';

	$sk = $wgUser->getSkin();

	$fromList = $this->getNamespaceKeyAndText($namespace, $from);

	if ( !$fromList ) {
		$out = wfMsgWikiHtml( 'allpagesbadtitle' );
	} else {
		list( $namespace, $fromKey, $from ) = $fromList;

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
				'LIMIT'     => $this->maxPerPage + 1,
				'USE INDEX' => 'name_title',
			)
		);

		### FIXME: side link to previous

		$n = 0;
		$out = '<table style="background: inherit;" border="0" width="100%">';

		$namespaces = $wgContLang->getFormattedNamespaces();
		while( ($n < $this->maxPerPage) && ($s = $dbr->fetchObject( $res )) ) {
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
	}

	if ( $including ) {
		$out2 = '';
	} else {
		$nsForm = $this->namespaceForm ( $namespace, $from );
		$out2 = '<table style="background: inherit;" width="100%" cellpadding="0" cellspacing="0" border="0">';
		$out2 .= '<tr valign="top"><td align="left">' . $nsForm;
		$out2 .= '</td><td align="right" style="font-size: smaller; margin-bottom: 1em;">' .
				$sk->makeKnownLink( $wgContLang->specialPage( "Allpages" ),
					wfMsgHtml ( 'allpages' ) );
		if ( isset($dbr) && $dbr && ($n == $this->maxPerPage) && ($s = $dbr->fetchObject( $res )) ) {
			$namespaceparam = $namespace ? "&namespace=$namespace" : "";
			$out2 .= " | " . $sk->makeKnownLink(
				$wgContLang->specialPage( "Allpages" ),
				wfMsgHtml ( 'nextpage', $s->page_title ),
				"from=" . wfUrlEncode ( $s->page_title ) . $namespaceparam );
		}
		$out2 .= "</td></tr></table><hr />";
	}

	$wgOut->addHtml( $out2 . $out );
}
	
/**
 * @param int $ns the namespace of the article
 * @param string $text the name of the article
 * @return array( int namespace, string dbkey, string pagename ) or NULL on error
 * @static (sort of)
 * @access private
 */
function getNamespaceKeyAndText ($ns, $text) {
	if ( $text == '' )
		return array( $ns, '', '' ); # shortcut for common case

	$t = Title::makeTitleSafe($ns, $text);
	if ( $t && $t->isLocal() )
		return array( $t->getNamespace(), $t->getDBkey(), $t->getText() );
	else if ( $t )
		return NULL;

	# try again, in case the problem was an empty pagename
	$text = preg_replace('/(#|$)/', 'X$1', $text);
	$t = Title::makeTitleSafe($ns, $text);
	if ( $t && $t->isLocal() )
		return array( $t->getNamespace(), '', '' );
	else
		return NULL;
}
}

?>
