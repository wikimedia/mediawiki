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
class SpecialPrefixindex extends SpecialAllPages {

	/**
	 * Whether to remove the searched prefix from the displayed link. Useful
	 * for inclusion of a set of sub pages in a root page.
	 */
	protected $stripPrefix = false;

	protected $hideRedirects = false;

	// Inherit $maxPerPage

	function __construct() {
		parent::__construct( 'Prefixindex' );
	}

	/**
	 * Entry point : initialise variables and call subfunctions.
	 * @param string $par Becomes "FOO" when called like Special:Prefixindex/FOO (default null)
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
		$this->hideRedirects = $request->getBool( 'hideredirects', $this->hideRedirects );
		$this->stripPrefix = $request->getBool( 'stripprefix', $this->stripPrefix );

		$namespaces = $wgContLang->getNamespaces();
		$out->setPageTitle(
			( $namespace > 0 && array_key_exists( $namespace, $namespaces ) )
				? $this->msg( 'prefixindex-namespace', str_replace( '_', ' ', $namespaces[$namespace] ) )
				: $this->msg( 'prefixindex' )
		);

		$showme = '';
		if ( $par !== null ) {
			$showme = $par;
		} elseif ( $prefix != '' ) {
			$showme = $prefix;
		} elseif ( $from != '' && $ns === null ) {
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
	 * @param int $namespace A namespace constant (default NS_MAIN).
	 * @param string $from DbKey we are starting listing at.
	 * @return string
	 */
	protected function namespacePrefixForm( $namespace = NS_MAIN, $from = '' ) {
		$out = Xml::openElement( 'div', [ 'class' => 'namespaceoptions' ] );
		$out .= Xml::openElement(
			'form',
			[ 'method' => 'get', 'action' => $this->getConfig()->get( 'Script' ) ]
		);
		$out .= Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() );
		$out .= Xml::openElement( 'fieldset' );
		$out .= Xml::element( 'legend', null, $this->msg( 'allpages' )->text() );
		$out .= Xml::openElement( 'table', [ 'id' => 'nsselect', 'class' => 'allpages' ] );
		$out .= "<tr>
				<td class='mw-label'>" .
			Xml::label( $this->msg( 'allpagesprefix' )->text(), 'nsfrom' ) .
			"</td>
				<td class='mw-input'>" .
			Xml::input( 'prefix', 30, str_replace( '_', ' ', $from ), [ 'id' => 'nsfrom' ] ) .
			"</td>
			</tr>
			<tr>
			<td class='mw-label'>" .
			Xml::label( $this->msg( 'namespace' )->text(), 'namespace' ) .
			"</td>
				<td class='mw-input'>" .
			Html::namespaceSelector( [
				'selected' => $namespace,
			], [
				'name' => 'namespace',
				'id' => 'namespace',
				'class' => 'namespaceselector',
			] ) .
			Xml::checkLabel(
				$this->msg( 'allpages-hide-redirects' )->text(),
				'hideredirects',
				'hideredirects',
				$this->hideRedirects
			) . ' ' .
			Xml::checkLabel(
				$this->msg( 'prefixindex-strip' )->text(),
				'stripprefix',
				'stripprefix',
				$this->stripPrefix
			) . ' ' .
			Xml::submitButton( $this->msg( 'prefixindex-submit' )->text() ) .
			"</td>
			</tr>";
		$out .= Xml::closeElement( 'table' );
		$out .= Xml::closeElement( 'fieldset' );
		$out .= Xml::closeElement( 'form' );
		$out .= Xml::closeElement( 'div' );

		return $out;
	}

	/**
	 * @param int $namespace Default NS_MAIN
	 * @param string $prefix
	 * @param string $from List all pages from this name (default false)
	 */
	protected function showPrefixChunk( $namespace = NS_MAIN, $prefix, $from = null ) {
		global $wgContLang;

		if ( $from === null ) {
			$from = $prefix;
		}

		$fromList = $this->getNamespaceKeyAndText( $namespace, $from );
		$prefixList = $this->getNamespaceKeyAndText( $namespace, $prefix );
		$namespaces = $wgContLang->getNamespaces();
		$res = null;

		if ( !$prefixList || !$fromList ) {
			$out = $this->msg( 'allpagesbadtitle' )->parseAsBlock();
		} elseif ( !array_key_exists( $namespace, $namespaces ) ) {
			// Show errormessage and reset to NS_MAIN
			$out = $this->msg( 'allpages-bad-ns', $namespace )->parse();
			$namespace = NS_MAIN;
		} else {
			list( $namespace, $prefixKey, $prefix ) = $prefixList;
			list( /* $fromNS */, $fromKey, ) = $fromList;

			# ## @todo FIXME: Should complain if $fromNs != $namespace

			$dbr = wfGetDB( DB_SLAVE );

			$conds = [
				'page_namespace' => $namespace,
				'page_title' . $dbr->buildLike( $prefixKey, $dbr->anyString() ),
				'page_title >= ' . $dbr->addQuotes( $fromKey ),
			];

			if ( $this->hideRedirects ) {
				$conds['page_is_redirect'] = 0;
			}

			$res = $dbr->select( 'page',
				[ 'page_namespace', 'page_title', 'page_is_redirect' ],
				$conds,
				__METHOD__,
				[
					'ORDER BY' => 'page_title',
					'LIMIT' => $this->maxPerPage + 1,
					'USE INDEX' => 'name_title',
				]
			);

			// @todo FIXME: Side link to previous

			$n = 0;
			if ( $res->numRows() > 0 ) {
				$out = Html::openElement( 'ul', [ 'class' => 'mw-prefixindex-list' ] );

				$prefixLength = strlen( $prefix );
				while ( ( $n < $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
					$t = Title::makeTitle( $s->page_namespace, $s->page_title );
					if ( $t ) {
						$displayed = $t->getText();
						// Try not to generate unclickable links
						if ( $this->stripPrefix && $prefixLength !== strlen( $displayed ) ) {
							$displayed = substr( $displayed, $prefixLength );
						}
						$link = ( $s->page_is_redirect ? '<div class="allpagesredirect">' : '' ) .
							Linker::linkKnown(
								$t,
								htmlspecialchars( $displayed ),
								$s->page_is_redirect ? [ 'class' => 'mw-redirect' ] : []
							) .
							( $s->page_is_redirect ? '</div>' : '' );
					} else {
						$link = '[[' . htmlspecialchars( $s->page_title ) . ']]';
					}

					$out .= "<li>$link</li>\n";
					$n++;

				}
				$out .= Html::closeElement( 'ul' );

				if ( $res->numRows() > 2 ) {
					// Only apply CSS column styles if there's more than 2 entries.
					// Otherwise rendering is broken as "mw-prefixindex-body"'s CSS column count is 3.
					$out = Html::rawElement( 'div', [ 'class' => 'mw-prefixindex-body' ], $out );
				}
			} else {
				$out = '';
			}
		}

		$output = $this->getOutput();

		if ( $this->including() ) {
			// We don't show the nav-links and the form when included into other
			// pages so let's just finish here.
			$output->addHTML( $out );
			return;
		}

		$topOut = $this->namespacePrefixForm( $namespace, $prefix );

		if ( $res && ( $n == $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
			$query = [
				'from' => $s->page_title,
				'prefix' => $prefix,
				'hideredirects' => $this->hideRedirects,
				'stripprefix' => $this->stripPrefix,
			];

			if ( $namespace || $prefix == '' ) {
				// Keep the namespace even if it's 0 for empty prefixes.
				// This tells us we're not just a holdover from old links.
				$query['namespace'] = $namespace;
			}

			$nextLink = Linker::linkKnown(
				$this->getPageTitle(),
				$this->msg( 'nextpage', str_replace( '_', ' ', $s->page_title ) )->escaped(),
				[],
				$query
			);

			// Link shown at the top of the page below the form
			$topOut .= Html::rawElement( 'div',
				[ 'class' => 'mw-prefixindex-nav' ],
				$nextLink
			);

			// Link shown at the footer
			$out .= "\n" . Html::element( 'hr' ) .
				Html::rawElement(
					'div',
					[ 'class' => 'mw-prefixindex-nav' ],
					$nextLink
				);

		}

		$output->addHTML( $topOut . $out );
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'pages';
	}
}
