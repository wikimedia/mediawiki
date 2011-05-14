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
		global $wgRequest, $wgOut, $wgContLang;

		$this->setHeaders();
		$this->outputHeader();
		$wgOut->addModuleStyles( 'mediawiki.special' );

		# GET values
		$from = $wgRequest->getVal( 'from', '' );
		$prefix = $wgRequest->getVal( 'prefix', '' );
		$namespace = $wgRequest->getInt( 'namespace' );
		$namespaces = $wgContLang->getNamespaces();

		$wgOut->setPagetitle( ( $namespace > 0 && in_array( $namespace, array_keys( $namespaces ) ) )
			? wfMsg( 'allinnamespace', str_replace( '_', ' ', $namespaces[$namespace] ) )
			: wfMsg( 'prefixindex' )
		);

		$showme = '';
		if ( $this->including() && ( $par == '' ) ) {
			// Bug 27864: if transcluded, show all pages instead of the form
			$showme = ' ';
		} elseif( isset( $par ) ){
			$showme = $par;
		} elseif( $prefix != '' ){
			$showme = $prefix;
		} elseif( $from != '' ){
			// For back-compat with Special:Allpages
			$showme = $from;
		}
		if ($showme != '' || $namespace) {
			$this->showPrefixChunk( $namespace, $showme, $from );
		} else {
			$wgOut->addHTML( $this->namespacePrefixForm( $namespace, null ) );
		}
	}

	/**
	* HTML for the top form
	* @param $namespace Integer: a namespace constant (default NS_MAIN).
	* @param $from String: dbKey we are starting listing at.
	*/
	function namespacePrefixForm( $namespace = NS_MAIN, $from = '' ) {
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
		global $wgOut, $wgUser, $wgContLang, $wgLang;

		$sk = $wgUser->getSkin();

		if (!isset($from)) $from = $prefix;

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

			### FIXME: should complain if $fromNs != $namespace

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

			### FIXME: side link to previous

			$n = 0;
			if( $res->numRows() > 0 ) {
				$out = Xml::openElement( 'table', array( 'border' => '0', 'id' => 'mw-prefixindex-list-table' ) );

				while( ( $n < $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
					$t = Title::makeTitle( $s->page_namespace, $s->page_title );
					if( $t ) {
						$link = ($s->page_is_redirect ? '<div class="allpagesredirect">' : '' ) .
							$sk->linkKnown(
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
					<td id="mw-prefixindex-nav-form">';

			if( isset( $res ) && $res && ( $n == $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
				$query = array(
					'from' => $s->page_title,
					'prefix' => $prefix
				);

				if( $namespace ) {
					$query['namespace'] = $namespace;
				}

				$out2 = $wgLang->pipeList( array(
					$out2,
					$sk->linkKnown(
						$self,
						wfMsgHtml( 'nextpage', str_replace( '_',' ', htmlspecialchars( $s->page_title ) ) ),
						array(),
						$query
					)
				) );
			}
			$out2 .= "</td></tr>" .
				Xml::closeElement( 'table' );
		}

		$wgOut->addHTML( $out2 . $out );
	}
}
