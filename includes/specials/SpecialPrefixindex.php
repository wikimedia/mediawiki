<?php
/**
 * Implements Special:Prefixindex
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
 * Implements Special:Prefixindex
 *
 * @ingroup SpecialPage
 */
class SpecialPrefixindex extends SpecialAllpages {
	// Inherit $maxPerPage

	function __construct(){
		parent::__construct( 'Prefixindex' );
	}

	/**
	 * Entry point : initialise variables and call subfunctions.
	 * @param $par String: becomes "FOO" when called like Special:Prefixindex/FOO (default null)
	 */
	function execute( $par ) {
		global $wgContLang;

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );

		# GET values
		$request = $this->getRequest();
		$from = $request->getVal( 'from', '' );
		$prefix = $request->getVal( 'prefix', '' );
		$ns = $request->getIntOrNull( 'namespace' );
		$namespace = (int)$ns; // if no namespace given, use 0 (NS_MAIN).

		$namespaces = $wgContLang->getNamespaces();
		$out->setPageTitle(
			( $namespace > 0 && in_array( $namespace, array_keys( $namespaces ) ) )
				? $this->msg( 'prefixindex-namespace', str_replace( '_', ' ', $namespaces[$namespace] ) )
				: $this->msg( 'prefixindex' )
		);

		$showme = '';
		if( isset( $par ) ) {
			$showme = $par;
		} elseif( $prefix != '' ) {
			$showme = $prefix;
		} elseif( $from != '' && $ns === null ) {
			// For back-compat with Special:Allpages
			// Don't do this if namespace is passed, so paging works when doing NS views.
			$showme = $from;
		}

		// Bug 27864: if transcluded, show all pages instead of the form.
		if ( $this->including() || $showme != '' || $ns !== null ) {
			$this->showPrefixChunk( $namespace, $showme, $from );
		} else {
			$out->addHTML( $this->namespacePrefixForm( $namespace, null ) );
		}
	}

	/**
	* HTML for the top form
	* @param $namespace Integer: a namespace constant (default NS_MAIN).
	* @param $from String: dbKey we are starting listing at.
	*/
	function namespacePrefixForm( $namespace = NS_MAIN, $from = '' ) {
		global $wgScript;

		$out  = Xml::openElement( 'div', array( 'class' => 'namespaceoptions' ) );
		$out .= Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) );
		$out .= Html::hidden( 'title', $this->getTitle()->getPrefixedText() );
		$out .= Xml::openElement( 'fieldset' );
		$out .= Xml::element( 'legend', null, wfMsg( 'allpages' ) );
		$out .= Xml::openElement( 'table', array( 'id' => 'nsselect', 'class' => 'allpages' ) );
		$out .= "<tr>
				<td class='mw-label'>" .
				Xml::label( wfMsg( 'allpagesprefix' ), 'nsfrom' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::input( 'prefix', 30, str_replace('_',' ',$from), array( 'id' => 'nsfrom' ) ) .
				"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'namespace' ), 'namespace' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::namespaceSelector( $namespace, null ) . ' ' .
					Xml::submitButton( wfMsg( 'allpagessubmit' ) ) .
				"</td>
				</tr>";
		$out .= Xml::closeElement( 'table' );
		$out .= Xml::closeElement( 'fieldset' );
		$out .= Xml::closeElement( 'form' );
		$out .= Xml::closeElement( 'div' );
		return $out;
	}

	/**
	 * @param $namespace Integer, default NS_MAIN
	 * @param $prefix String
	 * @param $from String: list all pages from this name (default FALSE)
	 */
	function showPrefixChunk( $namespace = NS_MAIN, $prefix, $from = null ) {
		global $wgContLang;

		if ( $from === null ) {
			$from = $prefix;
		}

		$fromList = $this->getNamespaceKeyAndText($namespace, $from);
		$prefixList = $this->getNamespaceKeyAndText($namespace, $prefix);
		$namespaces = $wgContLang->getNamespaces();

		if ( !$prefixList || !$fromList ) {
			$out = wfMsgExt( 'allpagesbadtitle', 'parse' );
		} elseif ( !in_array( $namespace, array_keys( $namespaces ) ) ) {
			// Show errormessage and reset to NS_MAIN
			$out = wfMsgExt( 'allpages-bad-ns', array( 'parseinline' ), $namespace );
			$namespace = NS_MAIN;
		} else {
			list( $namespace, $prefixKey, $prefix ) = $prefixList;
			list( /* $fromNS */, $fromKey, ) = $fromList;

			### @todo FIXME: Should complain if $fromNs != $namespace

			$dbr = wfGetDB( DB_SLAVE );

			$res = $dbr->select( 'page',
				array( 'page_namespace', 'page_title', 'page_is_redirect' ),
				array(
					'page_namespace' => $namespace,
					'page_title' . $dbr->buildLike( $prefixKey, $dbr->anyString() ),
					'page_title >= ' . $dbr->addQuotes( $fromKey ),
				),
				__METHOD__,
				array(
					'ORDER BY'  => 'page_title',
					'LIMIT'     => $this->maxPerPage + 1,
					'USE INDEX' => 'name_title',
				)
			);

			### @todo FIXME: Side link to previous

			$n = 0;
			if( $res->numRows() > 0 ) {
				$out = Xml::openElement( 'table', array( 'border' => '0', 'id' => 'mw-prefixindex-list-table' ) );

				while( ( $n < $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
					$t = Title::makeTitle( $s->page_namespace, $s->page_title );
					if( $t ) {
						$link = ($s->page_is_redirect ? '<div class="allpagesredirect">' : '' ) .
							Linker::linkKnown(
								$t,
								htmlspecialchars( $t->getText() )
							) .
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
				$out .= Xml::closeElement( 'table' );
			} else {
				$out = '';
			}
		}

		$footer = '';
		if ( $this->including() ) {
			$out2 = '';
		} else {
			$nsForm = $this->namespacePrefixForm( $namespace, $prefix );
			$self = $this->getTitle();
			$out2 = Xml::openElement( 'table', array( 'border' => '0', 'id' => 'mw-prefixindex-nav-table' ) )  .
				'<tr>
					<td>' .
						$nsForm .
					'</td>
					<td id="mw-prefixindex-nav-form" class="mw-prefixindex-nav">';

			if( isset( $res ) && $res && ( $n == $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
				$query = array(
					'from' => $s->page_title,
					'prefix' => $prefix
				);

				if( $namespace || ($prefix == '')) {
					// Keep the namespace even if it's 0 for empty prefixes.
					// This tells us we're not just a holdover from old links.
					$query['namespace'] = $namespace;
				}
				$nextLink = Linker::linkKnown(
						$self,
						wfMsgHtml( 'nextpage', str_replace( '_',' ', htmlspecialchars( $s->page_title ) ) ),
						array(),
						$query
					);
				$out2 .= $nextLink;

				$footer = "\n" . Html::element( "hr" )
					. Html::rawElement( "div", array( "class" => "mw-prefixindex-nav" ), $nextLink );
			}
			$out2 .= "</td></tr>" .
				Xml::closeElement( 'table' );
		}

		$this->getOutput()->addHTML( $out2 . $out . $footer );
	}
}
