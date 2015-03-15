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
class SpecialAllPages extends IncludableSpecialPage {

	/**
	 * Maximum number of pages to show on single subpage.
	 *
	 * @var int $maxPerPage
	 */
	protected $maxPerPage = 345;

	/**
	 * Determines, which message describes the input field 'nsfrom'.
	 *
	 * @var string $nsfromMsg
	 */
	protected $nsfromMsg = 'allpagesfrom';

	/**
	 * Constructor
	 *
	 * @param string $name Name of the special page, as seen in links and URLs (default: 'Allpages')
	 */
	function __construct( $name = 'Allpages' ) {
		parent::__construct( $name );
	}

	/**
	 * Entry point : initialise variables and call subfunctions.
	 *
	 * @param string $par Becomes "FOO" when called like Special:Allpages/FOO (default null)
	 */
	function execute( $par ) {
		$request = $this->getRequest();
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();
		$out->allowClickjacking();

		# GET values
		$from = $request->getVal( 'from', null );
		$to = $request->getVal( 'to', null );
		$namespace = $request->getInt( 'namespace' );
		$hideredirects = $request->getBool( 'hideredirects', false );

		$namespaces = $this->getContext()->getLanguage()->getNamespaces();

		$out->setPageTitle(
			( $namespace > 0 && array_key_exists( $namespace, $namespaces ) ) ?
				$this->msg( 'allinnamespace', str_replace( '_', ' ', $namespaces[$namespace] ) ) :
				$this->msg( 'allarticles' )
		);
		$out->addModuleStyles( 'mediawiki.special' );

		if ( $par !== null ) {
			$this->showChunk( $namespace, $par, $to, $hideredirects );
		} elseif ( $from !== null && $to === null ) {
			$this->showChunk( $namespace, $from, $to, $hideredirects );
		} else {
			$this->showToplevel( $namespace, $from, $to, $hideredirects );
		}
	}

	/**
	 * HTML for the top form
	 *
	 * @param int $namespace A namespace constant (default NS_MAIN).
	 * @param string $from DbKey we are starting listing at.
	 * @param string $to DbKey we are ending listing at.
	 * @param bool $hideredirects Dont show redirects  (default false)
	 * @return string
	 */
	function namespaceForm( $namespace = NS_MAIN, $from = '', $to = '', $hideredirects = false ) {
		$t = $this->getPageTitle();

		$out = Xml::openElement( 'div', array( 'class' => 'namespaceoptions' ) );
		$out .= Xml::openElement(
			'form',
			array( 'method' => 'get', 'action' => $this->getConfig()->get( 'Script' ) )
		);
		$out .= Html::hidden( 'title', $t->getPrefixedText() );
		$out .= Xml::openElement( 'fieldset' );
		$out .= Xml::element( 'legend', null, $this->msg( 'allpages' )->text() );
		$out .= Xml::openElement( 'table', array( 'id' => 'nsselect', 'class' => 'allpages' ) );
		$out .= "<tr>
	<td class='mw-label'>" .
			Xml::label( $this->msg( 'allpagesfrom' )->text(), 'nsfrom' ) .
			"	</td>
	<td class='mw-input'>" .
			Xml::input( 'from', 30, str_replace( '_', ' ', $from ), array( 'id' => 'nsfrom' ) ) .
			"	</td>
</tr>
<tr>
	<td class='mw-label'>" .
			Xml::label( $this->msg( 'allpagesto' )->text(), 'nsto' ) .
			"	</td>
			<td class='mw-input'>" .
			Xml::input( 'to', 30, str_replace( '_', ' ', $to ), array( 'id' => 'nsto' ) ) .
			"		</td>
</tr>
<tr>
	<td class='mw-label'>" .
			Xml::label( $this->msg( 'namespace' )->text(), 'namespace' ) .
			"	</td>
			<td class='mw-input'>" .
			Html::namespaceSelector(
				array( 'selected' => $namespace ),
				array( 'name' => 'namespace', 'id' => 'namespace' )
			) . ' ' .
			Xml::checkLabel(
				$this->msg( 'allpages-hide-redirects' )->text(),
				'hideredirects',
				'hideredirects',
				$hideredirects
			) . ' ' .
			Xml::submitButton( $this->msg( 'allpagessubmit' )->text() ) .
			"	</td>
</tr>";
		$out .= Xml::closeElement( 'table' );
		$out .= Xml::closeElement( 'fieldset' );
		$out .= Xml::closeElement( 'form' );
		$out .= Xml::closeElement( 'div' );

		return $out;
	}

	/**
	 * @param int $namespace (default NS_MAIN)
	 * @param string $from List all pages from this name
	 * @param string $to List all pages to this name
	 * @param bool $hideredirects Dont show redirects (default false)
	 */
	function showToplevel( $namespace = NS_MAIN, $from = '', $to = '', $hideredirects = false ) {
		$from = Title::makeTitleSafe( $namespace, $from );
		$to = Title::makeTitleSafe( $namespace, $to );
		$from = ( $from && $from->isLocal() ) ? $from->getDBkey() : null;
		$to = ( $to && $to->isLocal() ) ? $to->getDBkey() : null;

		$this->showChunk( $namespace, $from, $to, $hideredirects );
	}

	/**
	 * @param int $namespace Namespace (Default NS_MAIN)
	 * @param string $from List all pages from this name (default false)
	 * @param string $to List all pages to this name (default false)
	 * @param bool $hideredirects Dont show redirects (default false)
	 */
	function showChunk( $namespace = NS_MAIN, $from = false, $to = false, $hideredirects = false ) {
		$output = $this->getOutput();

		$fromList = $this->getNamespaceKeyAndText( $namespace, $from );
		$toList = $this->getNamespaceKeyAndText( $namespace, $to );
		$namespaces = $this->getContext()->getLanguage()->getNamespaces();
		$n = 0;

		if ( !$fromList || !$toList ) {
			$out = $this->msg( 'allpagesbadtitle' )->parseAsBlock();
		} elseif ( !array_key_exists( $namespace, $namespaces ) ) {
			// Show errormessage and reset to NS_MAIN
			$out = $this->msg( 'allpages-bad-ns', $namespace )->parse();
			$namespace = NS_MAIN;
		} else {
			list( $namespace, $fromKey, $from ) = $fromList;
			list( , $toKey, $to ) = $toList;

			$dbr = wfGetDB( DB_SLAVE );
			$conds = array(
				'page_namespace' => $namespace,
				'page_title >= ' . $dbr->addQuotes( $fromKey )
			);

			if ( $hideredirects ) {
				$conds['page_is_redirect'] = 0;
			}

			if ( $toKey !== "" ) {
				$conds[] = 'page_title <= ' . $dbr->addQuotes( $toKey );
			}

			$res = $dbr->select( 'page',
				array( 'page_namespace', 'page_title', 'page_is_redirect', 'page_id' ),
				$conds,
				__METHOD__,
				array(
					'ORDER BY' => 'page_title',
					'LIMIT' => $this->maxPerPage + 1,
					'USE INDEX' => 'name_title',
				)
			);

			if ( $res->numRows() > 0 ) {
				$out = Xml::openElement( 'ul', array( 'class' => 'mw-allpages-chunk' ) );
				while ( ( $n < $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
					$t = Title::newFromRow( $s );
					if ( $t ) {
						$out .= '<li' .
							( $s->page_is_redirect ? ' class="allpagesredirect"' : '' ) .
							'>' .
							Linker::link( $t ) .
							"</li>\n";
					} else {
						$out .= '<li>[[' . htmlspecialchars( $s->page_title ) . "]]</li>\n";
					}
					$n++;
				}
				$out .= Xml::closeElement( 'ul' );
			} else {
				$out = '';
			}
		}

		if ( $this->including() ) {
			$output->addHTML( $out );
			return;
		}

		if ( $from == '' ) {
			// First chunk; no previous link.
			$prevTitle = null;
		} else {
			# Get the last title from previous chunk
			$dbr = wfGetDB( DB_SLAVE );
			$res_prev = $dbr->select(
				'page',
				'page_title',
				array( 'page_namespace' => $namespace, 'page_title < ' . $dbr->addQuotes( $from ) ),
				__METHOD__,
				array( 'ORDER BY' => 'page_title DESC',
					'LIMIT' => $this->maxPerPage, 'OFFSET' => ( $this->maxPerPage - 1 )
				)
			);

			# Get first title of previous complete chunk
			if ( $dbr->numrows( $res_prev ) >= $this->maxPerPage ) {
				$pt = $dbr->fetchObject( $res_prev );
				$prevTitle = Title::makeTitle( $namespace, $pt->page_title );
			} else {
				# The previous chunk is not complete, need to link to the very first title
				# available in the database
				$options = array( 'LIMIT' => 1 );
				if ( !$dbr->implicitOrderby() ) {
					$options['ORDER BY'] = 'page_title';
				}
				$reallyFirstPage_title = $dbr->selectField( 'page', 'page_title',
					array( 'page_namespace' => $namespace ), __METHOD__, $options );
				# Show the previous link if it s not the current requested chunk
				if ( $from != $reallyFirstPage_title ) {
					$prevTitle = Title::makeTitle( $namespace, $reallyFirstPage_title );
				} else {
					$prevTitle = null;
				}
			}
		}

		$self = $this->getPageTitle();

		$topLinks = array(
			Linker::link( $self, $this->msg( 'allpages' )->escaped() )
		);
		$bottomLinks = array();

		# Do we put a previous link ?
		if ( $prevTitle && $pt = $prevTitle->getText() ) {
			$query = array( 'from' => $prevTitle->getText() );

			if ( $namespace ) {
				$query['namespace'] = $namespace;
			}

			if ( $hideredirects ) {
				$query['hideredirects'] = $hideredirects;
			}

			$prevLink = Linker::linkKnown(
				$self,
				$this->msg( 'prevpage', $pt )->escaped(),
				array(),
				$query
			);
			$topLinks[] = $prevLink;
			$bottomLinks[] = $prevLink;
		}

		if ( $n == $this->maxPerPage && $s = $res->fetchObject() ) {
			# $s is the first link of the next chunk
			$t = Title::makeTitle( $namespace, $s->page_title );
			$query = array( 'from' => $t->getText() );

			if ( $namespace ) {
				$query['namespace'] = $namespace;
			}

			if ( $hideredirects ) {
				$query['hideredirects'] = $hideredirects;
			}

			$nextLink = Linker::linkKnown(
				$self,
				$this->msg( 'nextpage', $t->getText() )->escaped(),
				array(),
				$query
			);
			$topLinks[] = $nextLink;
			$bottomLinks[] = $nextLink;
		}

		$nsForm = $this->namespaceForm( $namespace, $from, $to, $hideredirects );
		$out2 = Xml::openElement( 'table', array( 'class' => 'mw-allpages-table-form' ) ) .
			'<tr>
						<td>' .
			$nsForm .
			'</td>
						<td class="mw-allpages-nav">' .
			$this->getLanguage()->pipeList( $topLinks ) .
			'</td></tr></table>';

		$output->addHTML( $out2 . $out );

		if ( count( $bottomLinks ) ) {
			$output->addHTML(
				Html::element( 'hr' ) .
					Html::rawElement( 'div', array( 'class' => 'mw-allpages-nav' ),
						$this->getLanguage()->pipeList( $bottomLinks )
					)
			);
		}
	}

	/**
	 * @param int $ns The namespace of the article
	 * @param string $text The name of the article
	 * @return array( int namespace, string dbkey, string pagename ) or null on error
	 */
	protected function getNamespaceKeyAndText( $ns, $text ) {
		if ( $text == '' ) {
			# shortcut for common case
			return array( $ns, '', '' );
		}

		$t = Title::makeTitleSafe( $ns, $text );
		if ( $t && $t->isLocal() ) {
			return array( $t->getNamespace(), $t->getDBkey(), $t->getText() );
		} elseif ( $t ) {
			return null;
		}

		# try again, in case the problem was an empty pagename
		$text = preg_replace( '/(#|$)/', 'X$1', $text );
		$t = Title::makeTitleSafe( $ns, $text );
		if ( $t && $t->isLocal() ) {
			return array( $t->getNamespace(), '', '' );
		} else {
			return null;
		}
	}

	protected function getGroupName() {
		return 'pages';
	}
}
