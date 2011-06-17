<?php
/**
 * Implements Special:Allpages
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * Implements Special:Allpages
 *
 * @ingroup SpecialPage
 */
class SpecialAllpages extends IncludableSpecialPage {

	/**
	 * Maximum number of pages to show on single subpage.
	 */
	protected $maxPerPage = 345;

	/**
	 * Maximum number of pages to show on single index subpage.
	 */
	protected $maxLineCount = 100;

	/**
	 * Maximum number of chars to show for an entry.
	 */
	protected $maxPageLength = 70;

	/**
	 * Determines, which message describes the input field 'nsfrom'.
	 */
	protected $nsfromMsg = 'allpagesfrom';

	function __construct( $name = 'Allpages' ){
		parent::__construct( $name );
	}

	/**
	 * Entry point : initialise variables and call subfunctions.
	 *
	 * @param $par String: becomes "FOO" when called like Special:Allpages/FOO (default NULL)
	 */
	function execute( $par ) {
		global $wgContLang;
		$request = $this->getRequest();
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();
		$out->allowClickjacking();

		# GET values
		$from = $request->getVal( 'from', null );
		$to = $request->getVal( 'to', null );
		$namespace = $request->getInt( 'namespace' );

		$namespaces = $wgContLang->getNamespaces();

		$out->setPagetitle(
			( $namespace > 0 && in_array( $namespace, array_keys( $namespaces) ) ) ?
			wfMsg( 'allinnamespace', str_replace( '_', ' ', $namespaces[$namespace] ) ) :
			wfMsg( 'allarticles' )
		);
		$out->addModuleStyles( 'mediawiki.special' );

		if( isset($par) ) {
			$this->showChunk( $namespace, $par, $to );
		} elseif( isset($from) && !isset($to) ) {
			$this->showChunk( $namespace, $from, $to );
		} else {
			$this->showToplevel( $namespace, $from, $to );
		}
	}

	/**
	 * HTML for the top form
	 *
	 * @param $namespace Integer: a namespace constant (default NS_MAIN).
	 * @param $from String: dbKey we are starting listing at.
	 * @param $to String: dbKey we are ending listing at.
	 */
	function namespaceForm( $namespace = NS_MAIN, $from = '', $to = '' ) {
		global $wgScript;
		$t = $this->getTitle();

		$out  = Xml::openElement( 'div', array( 'class' => 'namespaceoptions' ) );
		$out .= Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) );
		$out .= Html::hidden( 'title', $t->getPrefixedText() );
		$out .= Xml::openElement( 'fieldset' );
		$out .= Xml::element( 'legend', null, wfMsg( 'allpages' ) );
		$out .= Xml::openElement( 'table', array( 'id' => 'nsselect', 'class' => 'allpages' ) );
		$out .= "<tr>
	<td class='mw-label'>" .
			Xml::label( wfMsg( 'allpagesfrom' ), 'nsfrom' ) .
			"	</td>
	<td class='mw-input'>" .
			Xml::input( 'from', 30, str_replace('_',' ',$from), array( 'id' => 'nsfrom' ) ) .
			"	</td>
</tr>
<tr>
	<td class='mw-label'>" .
			Xml::label( wfMsg( 'allpagesto' ), 'nsto' ) .
			"	</td>
			<td class='mw-input'>" .
			Xml::input( 'to', 30, str_replace('_',' ',$to), array( 'id' => 'nsto' ) ) .
			"		</td>
</tr>
<tr>
	<td class='mw-label'>" .
			Xml::label( wfMsg( 'namespace' ), 'namespace' ) .
			"	</td>
			<td class='mw-input'>" .
			Xml::namespaceSelector( $namespace, null ) . ' ' .
			Xml::submitButton( wfMsg( 'allpagessubmit' ) ) .
			"	</td>
</tr>";
		$out .= Xml::closeElement( 'table' );
		$out .= Xml::closeElement( 'fieldset' );
		$out .= Xml::closeElement( 'form' );
		$out .= Xml::closeElement( 'div' );
		return $out;
	}

	/**
	 * @param $namespace Integer (default NS_MAIN)
	 * @param $from String: list all pages from this name
	 * @param $to String: list all pages to this name
	 */
	function showToplevel( $namespace = NS_MAIN, $from = '', $to = '' ) {
		$output = $this->getOutput();

		# TODO: Either make this *much* faster or cache the title index points
		# in the querycache table.

		$dbr = wfGetDB( DB_SLAVE );
		$out = "";
		$where = array( 'page_namespace' => $namespace );

		$from = Title::makeTitleSafe( $namespace, $from );
		$to = Title::makeTitleSafe( $namespace, $to );
		$from = ( $from && $from->isLocal() ) ? $from->getDBkey() : null;
		$to = ( $to && $to->isLocal() ) ? $to->getDBkey() : null;

		if( isset($from) )
			$where[] = 'page_title >= '.$dbr->addQuotes( $from );
		if( isset($to) )
			$where[] = 'page_title <= '.$dbr->addQuotes( $to );

		global $wgMemc;
		$key = wfMemcKey( 'allpages', 'ns', $namespace, $from, $to );
		$lines = $wgMemc->get( $key );

		$count = $dbr->estimateRowCount( 'page', '*', $where, __METHOD__ );
		$maxPerSubpage = intval($count/$this->maxLineCount);
		$maxPerSubpage = max($maxPerSubpage,$this->maxPerPage);

		if( !is_array( $lines ) ) {
			$options = array( 'LIMIT' => 1 );
			$options['ORDER BY'] = 'page_title ASC';
			$firstTitle = $dbr->selectField( 'page', 'page_title', $where, __METHOD__, $options );
			$lastTitle = $firstTitle;
			# This array is going to hold the page_titles in order.
			$lines = array( $firstTitle );
			# If we are going to show n rows, we need n+1 queries to find the relevant titles.
			$done = false;
			while( !$done ) {
				// Fetch the last title of this chunk and the first of the next
				$chunk = ( $lastTitle === false )
					? array()
					: array( 'page_title >= ' . $dbr->addQuotes( $lastTitle ) );
				$res = $dbr->select( 'page', /* FROM */
					'page_title', /* WHAT */
					array_merge($where,$chunk),
					__METHOD__,
					array ('LIMIT' => 2, 'OFFSET' => $maxPerSubpage - 1, 'ORDER BY' => 'page_title ASC')
				);

				$s = $dbr->fetchObject( $res );
				if( $s ) {
					array_push( $lines, $s->page_title );
				} else {
					// Final chunk, but ended prematurely. Go back and find the end.
					$endTitle = $dbr->selectField( 'page', 'MAX(page_title)',
						array_merge($where,$chunk),
						__METHOD__ );
					array_push( $lines, $endTitle );
					$done = true;
				}
				$s = $res->fetchObject();
				if( $s ) {
					array_push( $lines, $s->page_title );
					$lastTitle = $s->page_title;
				} else {
					// This was a final chunk and ended exactly at the limit.
					// Rare but convenient!
					$done = true;
				}
				$res->free();
			}
			$wgMemc->add( $key, $lines, 3600 );
		}

		// If there are only two or less sections, don't even display them.
		// Instead, display the first section directly.
		if( count( $lines ) <= 2 ) {
			if( !empty($lines) ) {
				$this->showChunk( $namespace, $from, $to );
			} else {
				$output->addHTML( $this->namespaceForm( $namespace, $from, $to ) );
			}
			return;
		}

		# At this point, $lines should contain an even number of elements.
		$out .= Xml::openElement( 'table', array( 'class' => 'allpageslist' ) );
		while( count ( $lines ) > 0 ) {
			$inpoint = array_shift( $lines );
			$outpoint = array_shift( $lines );
			$out .= $this->showline( $inpoint, $outpoint, $namespace );
		}
		$out .= Xml::closeElement( 'table' );
		$nsForm = $this->namespaceForm( $namespace, $from, $to );

		# Is there more?
		if( $this->including() ) {
			$out2 = '';
		} else {
			if( isset($from) || isset($to) ) {
				$out2 = Xml::openElement( 'table', array( 'class' => 'mw-allpages-table-form' ) ).
						'<tr>
							<td>' .
								$nsForm .
							'</td>
							<td class="mw-allpages-nav">' .
								$this->getSkin()->link( $this->getTitle(), wfMsgHtml ( 'allpages' ),
									array(), array(), 'known' ) .
							"</td>
						</tr>" .
					Xml::closeElement( 'table' );
			} else {
				$out2 = $nsForm;
			}
		}
		$output->addHTML( $out2 . $out );
	}

	/**
	 * Show a line of "ABC to DEF" ranges of articles
	 *
	 * @param $inpoint String: lower limit of pagenames
	 * @param $outpoint String: upper limit of pagenames
	 * @param $namespace Integer (Default NS_MAIN)
	 */
	function showline( $inpoint, $outpoint, $namespace = NS_MAIN ) {
		global $wgContLang;
		$inpointf = htmlspecialchars( str_replace( '_', ' ', $inpoint ) );
		$outpointf = htmlspecialchars( str_replace( '_', ' ', $outpoint ) );
		// Don't let the length runaway
		$inpointf = $wgContLang->truncate( $inpointf, $this->maxPageLength );
		$outpointf = $wgContLang->truncate( $outpointf, $this->maxPageLength );

		$queryparams = $namespace ? "namespace=$namespace&" : '';
		$special = $this->getTitle();
		$link = $special->escapeLocalUrl( $queryparams . 'from=' . urlencode($inpoint) . '&to=' . urlencode($outpoint) );

		$out = wfMsgHtml( 'alphaindexline',
			"<a href=\"$link\">$inpointf</a></td><td>",
			"</td><td><a href=\"$link\">$outpointf</a>"
		);
		return '<tr><td class="mw-allpages-alphaindexline">' . $out . '</td></tr>';
	}

	/**
	 * @param $namespace Integer (Default NS_MAIN)
	 * @param $from String: list all pages from this name (default FALSE)
	 * @param $to String: list all pages to this name (default FALSE)
	 */
	function showChunk( $namespace = NS_MAIN, $from = false, $to = false ) {
		global $wgContLang, $wgLang;
		$output = $this->getOutput();
		$sk = $this->getSkin();

		$fromList = $this->getNamespaceKeyAndText($namespace, $from);
		$toList = $this->getNamespaceKeyAndText( $namespace, $to );
		$namespaces = $wgContLang->getNamespaces();
		$n = 0;

		if ( !$fromList || !$toList ) {
			$out = wfMsgExt( 'allpagesbadtitle', 'parse' );
		} elseif ( !in_array( $namespace, array_keys( $namespaces ) ) ) {
			// Show errormessage and reset to NS_MAIN
			$out = wfMsgExt( 'allpages-bad-ns', array( 'parseinline' ), $namespace );
			$namespace = NS_MAIN;
		} else {
			list( $namespace, $fromKey, $from ) = $fromList;
			list( , $toKey, $to ) = $toList;

			$dbr = wfGetDB( DB_SLAVE );
			$conds = array(
				'page_namespace' => $namespace,
				'page_title >= ' . $dbr->addQuotes( $fromKey )
			);
			if( $toKey !== "" ) {
				$conds[] = 'page_title <= ' . $dbr->addQuotes( $toKey );
			}

			$res = $dbr->select( 'page',
				array( 'page_namespace', 'page_title', 'page_is_redirect', 'page_id' ),
				$conds,
				__METHOD__,
				array(
					'ORDER BY'  => 'page_title',
					'LIMIT'     => $this->maxPerPage + 1,
					'USE INDEX' => 'name_title',
				)
			);

			if( $res->numRows() > 0 ) {
				$out = Xml::openElement( 'table', array( 'class' => 'mw-allpages-table-chunk' ) );
				while( ( $n < $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
					$t = Title::newFromRow( $s );
					if( $t ) {
						$link = ( $s->page_is_redirect ? '<div class="allpagesredirect">' : '' ) .
							$sk->link( $t ) .
							($s->page_is_redirect ? '</div>' : '' );
					} else {
						$link = '[[' . htmlspecialchars( $s->page_title ) . ']]';
					}
					if( $n % 3 == 0 ) {
						$out .= '<tr>';
					}
					$out .= "<td style=\"width:33%\">$link</td>";
					$n++;
					if( $n % 3 == 0 ) {
						$out .= "</tr>\n";
					}
				}
				if( ($n % 3) != 0 ) {
					$out .= "</tr>\n";
				}
				$out .= Xml::closeElement( 'table' );
			} else {
				$out = '';
			}
		}

		if ( $this->including() ) {
			$out2 = '';
		} else {
			if( $from == '' ) {
				// First chunk; no previous link.
				$prevTitle = null;
			} else {
				# Get the last title from previous chunk
				$dbr = wfGetDB( DB_SLAVE );
				$res_prev = $dbr->select(
					'page',
					'page_title',
					array( 'page_namespace' => $namespace, 'page_title < '.$dbr->addQuotes($from) ),
					__METHOD__,
					array( 'ORDER BY' => 'page_title DESC',
						'LIMIT' => $this->maxPerPage, 'OFFSET' => ($this->maxPerPage - 1 )
					)
				);

				# Get first title of previous complete chunk
				if( $dbr->numrows( $res_prev ) >= $this->maxPerPage ) {
					$pt = $dbr->fetchObject( $res_prev );
					$prevTitle = Title::makeTitle( $namespace, $pt->page_title );
				} else {
					# The previous chunk is not complete, need to link to the very first title
					# available in the database
					$options = array( 'LIMIT' => 1 );
					if ( ! $dbr->implicitOrderby() ) {
						$options['ORDER BY'] = 'page_title';
					}
					$reallyFirstPage_title = $dbr->selectField( 'page', 'page_title',
						array( 'page_namespace' => $namespace ), __METHOD__, $options );
					# Show the previous link if it s not the current requested chunk
					if( $from != $reallyFirstPage_title ) {
						$prevTitle =  Title::makeTitle( $namespace, $reallyFirstPage_title );
					} else {
						$prevTitle = null;
					}
				}
			}

			$self = $this->getTitle();

			$nsForm = $this->namespaceForm( $namespace, $from, $to );
			$out2 = Xml::openElement( 'table', array( 'class' => 'mw-allpages-table-form' ) ).
						'<tr>
							<td>' .
								$nsForm .
							'</td>
							<td class="mw-allpages-nav">' .
								$sk->link( $self, wfMsgHtml ( 'allpages' ) );

			# Do we put a previous link ?
			if( isset( $prevTitle ) &&  $pt = $prevTitle->getText() ) {
				$query = array( 'from' => $prevTitle->getText() );

				if( $namespace )
					$query['namespace'] = $namespace;

				$prevLink = $sk->linkKnown(
					$self,
					wfMessage( 'prevpage', $pt )->escaped(),
					array(),
					$query
				);
				$out2 = $wgLang->pipeList( array( $out2, $prevLink ) );
			}

			if( $n == $this->maxPerPage && $s = $res->fetchObject() ) {
				# $s is the first link of the next chunk
				$t = Title::MakeTitle($namespace, $s->page_title);
				$query = array( 'from' => $t->getText() );

				if( $namespace )
					$query['namespace'] = $namespace;

				$nextLink = $sk->linkKnown(
					$self,
					wfMessage( 'nextpage', $t->getText() )->escaped(),
					array(),
					$query
				);
				$out2 = $wgLang->pipeList( array( $out2, $nextLink ) );
			}
			$out2 .= "</td></tr></table>";
		}

		$output->addHTML( $out2 . $out );

		$links = array();
		if ( isset( $prevLink ) ) $links[] = $prevLink;
		if ( isset( $nextLink ) ) $links[] = $nextLink;

		if ( count( $links ) ) {
			$output->addHTML(
				Html::element( 'hr' ) .
				Html::rawElement( 'div', array( 'class' => 'mw-allpages-nav' ),
					$wgLang->pipeList( $links )
				) );
		}

	}

	/**
	 * @param $ns Integer: the namespace of the article
	 * @param $text String: the name of the article
	 * @return array( int namespace, string dbkey, string pagename ) or NULL on error
	 */
	protected function getNamespaceKeyAndText($ns, $text) {
		if ( $text == '' )
			return array( $ns, '', '' ); # shortcut for common case

		$t = Title::makeTitleSafe($ns, $text);
		if ( $t && $t->isLocal() ) {
			return array( $t->getNamespace(), $t->getDBkey(), $t->getText() );
		} elseif ( $t ) {
			return null;
		}

		# try again, in case the problem was an empty pagename
		$text = preg_replace('/(#|$)/', 'X$1', $text);
		$t = Title::makeTitleSafe($ns, $text);
		if ( $t && $t->isLocal() ) {
			return array( $t->getNamespace(), '', '' );
		} else {
			return null;
		}
	}
}
