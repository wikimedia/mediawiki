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
use MediaWiki\MediaWikiServices;

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

		$namespaces = MediaWikiServices::getInstance()->getContentLanguage()->getNamespaces();
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

		// T29864: if transcluded, show all pages instead of the form.
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
		$formDescriptor = [
			'prefix' => [
				'label-message' => 'allpagesprefix',
				'name' => 'prefix',
				'id' => 'nsfrom',
				'type' => 'text',
				'size' => '30',
				'default' => str_replace( '_', ' ', $from ),
			],
			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				'id' => 'namespace',
				'label-message' => 'namespace',
				'all' => null,
				'default' => $namespace,
			],
			'hidedirects' => [
				'class' => 'HTMLCheckField',
				'name' => 'hideredirects',
				'label-message' => 'allpages-hide-redirects',
			],
			'stripprefix' => [
				'class' => 'HTMLCheckField',
				'name' => 'stripprefix',
				'label-message' => 'prefixindex-strip',
			],
		];
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $context );
		$htmlForm
			->setMethod( 'get' )
			->setWrapperLegendMsg( 'prefixindex' )
			->setSubmitTextMsg( 'prefixindex-submit' );

		return $htmlForm->prepareForm()->getHTML( false );
	}

	/**
	 * @param int $namespace
	 * @param string $prefix
	 * @param string|null $from List all pages from this name (default false)
	 */
	protected function showPrefixChunk( $namespace, $prefix, $from = null ) {
		if ( $from === null ) {
			$from = $prefix;
		}

		$fromList = $this->getNamespaceKeyAndText( $namespace, $from );
		$prefixList = $this->getNamespaceKeyAndText( $namespace, $prefix );
		$namespaces = MediaWikiServices::getInstance()->getContentLanguage()->getNamespaces();
		$res = null;
		$n = 0;
		$nextRow = null;

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

			$dbr = wfGetDB( DB_REPLICA );

			$conds = [
				'page_namespace' => $namespace,
				'page_title' . $dbr->buildLike( $prefixKey, $dbr->anyString() ),
				'page_title >= ' . $dbr->addQuotes( $fromKey ),
			];

			if ( $this->hideRedirects ) {
				$conds['page_is_redirect'] = 0;
			}

			$res = $dbr->select( 'page',
				array_merge(
					[ 'page_namespace', 'page_title' ],
					LinkCache::getSelectFields()
				),
				$conds,
				__METHOD__,
				[
					'ORDER BY' => 'page_title',
					'LIMIT' => $this->maxPerPage + 1,
					'USE INDEX' => 'name_title',
				]
			);

			// @todo FIXME: Side link to previous

			if ( $res->numRows() > 0 ) {
				$out = Html::openElement( 'ul', [ 'class' => 'mw-prefixindex-list' ] );
				$linkCache = MediaWikiServices::getInstance()->getLinkCache();

				$prefixLength = strlen( $prefix );
				foreach ( $res as $row ) {
					if ( $n >= $this->maxPerPage ) {
						$nextRow = $row;
						break;
					}
					$title = Title::newFromRow( $row );
					// Make sure it gets into LinkCache
					$linkCache->addGoodLinkObjFromRow( $title, $row );
					$displayed = $title->getText();
					// Try not to generate unclickable links
					if ( $this->stripPrefix && $prefixLength !== strlen( $displayed ) ) {
						$displayed = substr( $displayed, $prefixLength );
					}
					$link = ( $title->isRedirect() ? '<div class="allpagesredirect">' : '' ) .
						$this->getLinkRenderer()->makeKnownLink(
							$title,
							$displayed
						) .
						( $title->isRedirect() ? '</div>' : '' );

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

		if ( $res && ( $n == $this->maxPerPage ) && $nextRow ) {
			$query = [
				'from' => $nextRow->page_title,
				'prefix' => $prefix,
				'hideredirects' => $this->hideRedirects,
				'stripprefix' => $this->stripPrefix,
			];

			if ( $namespace || $prefix == '' ) {
				// Keep the namespace even if it's 0 for empty prefixes.
				// This tells us we're not just a holdover from old links.
				$query['namespace'] = $namespace;
			}

			$nextLink = $this->getLinkRenderer()->makeKnownLink(
				$this->getPageTitle(),
				$this->msg( 'nextpage', str_replace( '_', ' ', $nextRow->page_title ) )->text(),
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
