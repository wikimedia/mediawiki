<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Entry point : initialise variables and call subfunctions.
 * @param string $par Becomes "FOO" when called like Special:Prefixindex/FOO (default NULL)
 */

require_once 'SpecialAllpages.php';

function wfSpecialPrefixIndex( $par=NULL, $specialPage ) {
	global $wgRequest, $wgOut, $wgContLang;

	# GET values
	$from = $wgRequest->getVal( 'from' );
	$namespace = $wgRequest->getInt( 'namespace' );

	$namespaces = $wgContLang->getNamespaces();

	$indexPage = new SpecialPrefixIndex();

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
		$wgOut->addHtml($indexPage->namespaceForm ( $namespace, $from ));
	}
}

class SpecialPrefixindex extends SpecialAllpages {
	var $maxPerPage=960;
	var $topLevelMax=50;
	var $name='Prefixindex';
	# Determines, which message describes the input field 'nsfrom', used in function namespaceForm (see superclass SpecialAllpages)
	var $nsfromMsg='allpagesprefix';

/**
 * @param integer $namespace (Default NS_MAIN)
 * @param string $from list all pages from this name (default FALSE)
 */
function showChunk( $namespace = NS_MAIN, $from, $including = false ) {
	global $wgOut, $wgUser, $wgContLang;

	$fname = 'indexShowChunk';

	$sk = $wgUser->getSkin();

	$fromTitle = Title::newFromURL( $from );
	if ($namespace == NS_MAIN and $fromTitle) {
		$namespace = $fromTitle->getNamespace();
	}

	$fromKey = is_null( $fromTitle ) ? '' : $fromTitle->getDBkey();

	$dbr =& wfGetDB( DB_SLAVE );

	$res = $dbr->select( 'page',
		array( 'page_namespace', 'page_title', 'page_is_redirect' ),
		array(
			'page_namespace' => $namespace,
			'page_title LIKE \'' . $dbr->escapeLike( $fromKey ) .'%\''
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

	if ( $including ) {
		$out2 = '';
	} else {
		$nsForm = $this->namespaceForm ( $namespace, $from );
		$out2 = '<table style="background: inherit;" width="100%" cellpadding="0" cellspacing="0" border="0">';
		$out2 .= '<tr valign="top"><td align="left">' . $nsForm;
		$out2 .= '</td><td align="right" style="font-size: smaller; margin-bottom: 1em;">' .
				$sk->makeKnownLink( $wgContLang->specialPage( $this->name ),
					wfMsg ( 'allpages' ) );
		if ( ($n == $this->maxPerPage) && ($s = $dbr->fetchObject( $res )) ) {
			$namespaceparam = $namespace ? "&namespace=$namespace" : "";
			$out2 .= " | " . $sk->makeKnownLink(
				$wgContLang->specialPage( $this->name ),
				wfMsg ( 'nextpage', $s->page_title ),
				"from=" . wfUrlEncode ( $s->page_title ) . $namespaceparam );
		}
		$out2 .= "</td></tr></table><hr />";
	}

	$wgOut->addHtml( $out2 . $out );
}
}

?>
