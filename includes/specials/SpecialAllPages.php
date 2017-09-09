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
 * @todo Rewrite using IndexPager
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

		$miserMode = (bool)$this->getConfig()->get( 'MiserMode' );

		// Redirects filter is disabled in MiserMode
		$hideredirects = $request->getBool( 'hideredirects', false ) && !$miserMode;

		$namespaces = $this->getLanguage()->getNamespaces();

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
	 * Outputs the HTMLForm used on this page
	 *
	 * @param int $namespace A namespace constant (default NS_MAIN).
	 * @param string $from DbKey we are starting listing at.
	 * @param string $to DbKey we are ending listing at.
	 * @param bool $hideRedirects Dont show redirects  (default false)
	 */
	protected function outputHTMLForm( $namespace = NS_MAIN,
		$from = '', $to = '', $hideRedirects = false
	) {
		$miserMode = (bool)$this->getConfig()->get( 'MiserMode' );
		$fields = [
			'from' => [
				'type' => 'text',
				'name' => 'from',
				'id' => 'nsfrom',
				'size' => 30,
				'label-message' => 'allpagesfrom',
				'default' => str_replace( '_', ' ', $from ),
			],
			'to' => [
				'type' => 'text',
				'name' => 'to',
				'id' => 'nsto',
				'size' => 30,
				'label-message' => 'allpagesto',
				'default' => str_replace( '_', ' ', $to ),
			],
			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				'id' => 'namespace',
				'label-message' => 'namespace',
				'all' => null,
				'value' => $namespace,
			],
			'hideredirects' => [
				'type' => 'check',
				'name' => 'hideredirects',
				'id' => 'hidredirects',
				'label-message' => 'allpages-hide-redirects',
				'value' => $hideRedirects,
			],
		];

		if ( $miserMode ) {
			unset( $fields['hideredirects'] );
		}

		$form = HTMLForm::factory( 'table', $fields, $this->getContext() );
		$form->setMethod( 'get' )
			->setWrapperLegendMsg( 'allpages' )
			->setSubmitTextMsg( 'allpagessubmit' )
			->prepareForm()
			->displayForm( false );
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
		$prevTitle = null;

		if ( !$fromList || !$toList ) {
			$out = $this->msg( 'allpagesbadtitle' )->parseAsBlock();
		} elseif ( !array_key_exists( $namespace, $namespaces ) ) {
			// Show errormessage and reset to NS_MAIN
			$out = $this->msg( 'allpages-bad-ns', $namespace )->parse();
			$namespace = NS_MAIN;
		} else {
			list( $namespace, $fromKey, $from ) = $fromList;
			list( , $toKey, $to ) = $toList;

			$dbr = wfGetDB( DB_REPLICA );
			$filterConds = [ 'page_namespace' => $namespace ];
			if ( $hideredirects ) {
				$filterConds['page_is_redirect'] = 0;
			}

			$conds = $filterConds;
			$conds[] = 'page_title >= ' . $dbr->addQuotes( $fromKey );
			if ( $toKey !== "" ) {
				$conds[] = 'page_title <= ' . $dbr->addQuotes( $toKey );
			}

			$res = $dbr->select( 'page',
				[ 'page_namespace', 'page_title', 'page_is_redirect', 'page_id' ],
				$conds,
				__METHOD__,
				[
					'ORDER BY' => 'page_title',
					'LIMIT' => $this->maxPerPage + 1,
					'USE INDEX' => 'name_title',
				]
			);

			$linkRenderer = $this->getLinkRenderer();
			if ( $res->numRows() > 0 ) {
				$out = Html::openElement( 'ul', [ 'class' => 'mw-allpages-chunk' ] );

				while ( ( $n < $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
					$t = Title::newFromRow( $s );
					if ( $t ) {
						$out .= '<li' .
							( $s->page_is_redirect ? ' class="allpagesredirect"' : '' ) .
							'>' .
							$linkRenderer->makeLink( $t ) .
							"</li>\n";
					} else {
						$out .= '<li>[[' . htmlspecialchars( $s->page_title ) . "]]</li>\n";
					}
					$n++;
				}
				$out .= Html::closeElement( 'ul' );

				if ( $res->numRows() > 2 ) {
					// Only apply CSS column styles if there's more than 2 entries.
					// Otherwise, rendering is broken as "mw-allpages-body"'s CSS column count is 3.
					$out = Html::rawElement( 'div', [ 'class' => 'mw-allpages-body' ], $out );
				}
			} else {
				$out = '';
			}

			if ( $fromKey !== '' && !$this->including() ) {
				# Get the first title from previous chunk
				$prevConds = $filterConds;
				$prevConds[] = 'page_title < ' . $dbr->addQuotes( $fromKey );
				$prevKey = $dbr->selectField(
					'page',
					'page_title',
					$prevConds,
					__METHOD__,
					[ 'ORDER BY' => 'page_title DESC', 'OFFSET' => $this->maxPerPage - 1 ]
				);

				if ( $prevKey === false ) {
					# The previous chunk is not complete, need to link to the very first title
					# available in the database
					$prevKey = $dbr->selectField(
						'page',
						'page_title',
						$prevConds,
						__METHOD__,
						[ 'ORDER BY' => 'page_title' ]
					);
				}

				if ( $prevKey !== false ) {
					$prevTitle = Title::makeTitle( $namespace, $prevKey );
				}
			}
		}

		if ( $this->including() ) {
			$output->addHTML( $out );
			return;
		}

		$navLinks = [];
		$self = $this->getPageTitle();

		$linkRenderer = $this->getLinkRenderer();
		// Generate a "previous page" link if needed
		if ( $prevTitle ) {
			$query = [ 'from' => $prevTitle->getText() ];

			if ( $namespace ) {
				$query['namespace'] = $namespace;
			}

			if ( $hideredirects ) {
				$query['hideredirects'] = $hideredirects;
			}

			$navLinks[] = $linkRenderer->makeKnownLink(
				$self,
				$this->msg( 'prevpage', $prevTitle->getText() )->text(),
				[],
				$query
			);

		}

		// Generate a "next page" link if needed
		if ( $n == $this->maxPerPage && $s = $res->fetchObject() ) {
			# $s is the first link of the next chunk
			$t = Title::makeTitle( $namespace, $s->page_title );
			$query = [ 'from' => $t->getText() ];

			if ( $namespace ) {
				$query['namespace'] = $namespace;
			}

			if ( $hideredirects ) {
				$query['hideredirects'] = $hideredirects;
			}

			$navLinks[] = $linkRenderer->makeKnownLink(
				$self,
				$this->msg( 'nextpage', $t->getText() )->text(),
				[],
				$query
			);
		}

		$this->outputHTMLForm( $namespace, $from, $to, $hideredirects );

		if ( count( $navLinks ) ) {
			// Add pagination links
			$pagination = Html::rawElement( 'div',
				[ 'class' => 'mw-allpages-nav' ],
				$this->getLanguage()->pipeList( $navLinks )
			);

			$output->addHTML( $pagination );
			$out .= Html::element( 'hr' ) . $pagination; // Footer
		}

		$output->addHTML( $out );
	}

	/**
	 * @param int $ns The namespace of the article
	 * @param string $text The name of the article
	 * @return array|null [ int namespace, string dbkey, string pagename ] or null on error
	 */
	protected function getNamespaceKeyAndText( $ns, $text ) {
		if ( $text == '' ) {
			# shortcut for common case
			return [ $ns, '', '' ];
		}

		$t = Title::makeTitleSafe( $ns, $text );
		if ( $t && $t->isLocal() ) {
			return [ $t->getNamespace(), $t->getDBkey(), $t->getText() ];
		} elseif ( $t ) {
			return null;
		}

		# try again, in case the problem was an empty pagename
		$text = preg_replace( '/(#|$)/', 'X$1', $text );
		$t = Title::makeTitleSafe( $ns, $text );
		if ( $t && $t->isLocal() ) {
			return [ $t->getNamespace(), '', '' ];
		} else {
			return null;
		}
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
