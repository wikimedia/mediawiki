<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkCache;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLCheckField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Implements Special:Prefixindex
 *
 * @ingroup SpecialPage
 * @ingroup Search
 */
class SpecialPrefixIndex extends SpecialAllPages {

	/**
	 * Whether to remove the searched prefix from the displayed link. Useful
	 * for inclusion of a set of subpages in a root page.
	 * @var bool
	 */
	protected $stripPrefix = false;

	/** @var bool */
	protected $hideRedirects = false;

	// Inherit $maxPerPage

	// phpcs:ignore MediaWiki.Commenting.PropertyDocumentation.WrongStyle
	private IConnectionProvider $dbProvider;
	private LinkCache $linkCache;

	public function __construct(
		IConnectionProvider $dbProvider,
		LinkCache $linkCache
	) {
		parent::__construct( $dbProvider );
		$this->mName = 'Prefixindex';
		$this->dbProvider = $dbProvider;
		$this->linkCache = $linkCache;
	}

	/**
	 * Entry point: initialise variables and call subfunctions.
	 * @param string|null $par Becomes "FOO" when called like Special:Prefixindex/FOO
	 */
	public function execute( $par ) {
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

		$namespaces = $this->getContentLanguage()->getNamespaces();
		$out->setPageTitleMsg(
			( $namespace > 0 && array_key_exists( $namespace, $namespaces ) )
				? $this->msg( 'prefixindex-namespace' )->plaintextParams(
					str_replace( '_', ' ', $namespaces[$namespace] )
				)
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
			$out->addHTML( $this->namespacePrefixForm( $namespace, '' )->getHTML( false ) );
		}
	}

	/**
	 * Prepared HTMLForm object for the top form
	 * @param int $namespace A namespace constant (default NS_MAIN).
	 * @param string $from DbKey we are starting listing at.
	 * @return HTMLForm
	 */
	protected function namespacePrefixForm( $namespace = NS_MAIN, $from = '' ): HTMLForm {
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
				'class' => HTMLCheckField::class,
				'name' => 'hideredirects',
				'label-message' => 'allpages-hide-redirects',
			],
			'stripprefix' => [
				'class' => HTMLCheckField::class,
				'name' => 'stripprefix',
				'label-message' => 'prefixindex-strip',
			],
		];

		$this->getHookRunner()->onSpecialPrefixIndexGetFormFilters( $this->getContext(), $formDescriptor );

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setMethod( 'get' )
			->setTitle( $this->getPageTitle() ) // Remove subpage
			->setWrapperLegendMsg( 'prefixindex' )
			->setSubmitTextMsg( 'prefixindex-submit' );

		return $htmlForm->prepareForm();
	}

	/**
	 * @param int $namespace
	 * @param string $prefix
	 * @param string|null $from List all pages from this name (default false)
	 */
	protected function showPrefixChunk( $namespace, $prefix, $from = null ) {
		$from ??= $prefix;

		$fromList = $this->getNamespaceKeyAndText( $namespace, $from );
		$prefixList = $this->getNamespaceKeyAndText( $namespace, $prefix );
		$namespaces = $this->getContentLanguage()->getNamespaces();
		$res = null;
		$n = 0;
		$nextRow = null;
		$preparedHtmlForm = $this->namespacePrefixForm( $namespace, $prefix );

		if ( !$prefixList || !$fromList ) {
			$out = $this->msg( 'allpagesbadtitle' )->parseAsBlock();
		} elseif ( !array_key_exists( $namespace, $namespaces ) ) {
			// Show errormessage and reset to NS_MAIN
			$out = $this->msg( 'allpages-bad-ns', $namespace )->parse();
			$namespace = NS_MAIN;
		} else {
			[ $namespace, $prefixKey, $prefix ] = $prefixList;
			[ /* $fromNS */, $fromKey, ] = $fromList;

			# ## @todo FIXME: Should complain if $fromNs != $namespace

			$dbr = $this->dbProvider->getReplicaDatabase();
			$queryBuiler = $dbr->newSelectQueryBuilder()
				->select( LinkCache::getSelectFields() )
				->from( 'page' )
				->where( [
					'page_namespace' => $namespace,
					$dbr->expr(
						'page_title',
						IExpression::LIKE,
						new LikeValue( $prefixKey, $dbr->anyString() )
					),
					$dbr->expr( 'page_title', '>=', $fromKey ),
				] )
				->orderBy( 'page_title' )
				->limit( $this->maxPerPage + 1 )
				->useIndex( 'page_name_title' );

			if ( $this->hideRedirects ) {
				$queryBuiler->andWhere( [ 'page_is_redirect' => 0 ] );
			}

			$this->getHookRunner()->onSpecialPrefixIndexQuery( $preparedHtmlForm->mFieldData, $queryBuiler );

			$res = $queryBuiler->caller( __METHOD__ )->fetchResultSet();

			// @todo FIXME: Side link to previous

			if ( $res->numRows() > 0 ) {
				$out = Html::openElement( 'ul', [ 'class' => 'mw-prefixindex-list' ] );

				$prefixLength = strlen( $prefix );
				foreach ( $res as $row ) {
					if ( $n >= $this->maxPerPage ) {
						$nextRow = $row;
						break;
					}
					$title = Title::newFromRow( $row );
					// Make sure it gets into LinkCache
					$this->linkCache->addGoodLinkObjFromRow( $title, $row );
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
					// Only apply CSS column styles if there are more than 2 entries.
					// Otherwise, rendering is broken as "mw-prefixindex-body"'s CSS column count is 3.
					$out = Html::rawElement( 'div', [ 'class' => 'mw-prefixindex-body' ], $out );
				}
			} else {
				$out = '';
			}
		}

		$output = $this->getOutput();

		if ( $this->including() ) {
			// We don't show the nav-links and the form when included in other
			// pages, so let's just finish here.
			$output->addHTML( $out );
			return;
		}

		$topOut = $preparedHtmlForm->getHTML( false );

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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pages';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPrefixIndex::class, 'SpecialPrefixindex' );
