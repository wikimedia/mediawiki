<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Pager
 */

namespace MediaWiki\Specials\Pager;

use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Navigation\CodexPagerNavigationBuilder;
use MediaWiki\Page\LinkBatchFactory;
use MediaWiki\Pager\CodexTablePager;
use MediaWiki\Pager\IndexPager;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialEditWatchlist;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use Wikimedia\Codex\Utility\Codex;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\ResultWrapper;

/**
 * @ingroup Pager
 */
class EditWatchlistPager extends CodexTablePager {

	/**
	 * @codeCoverageIgnore
	 * @param IContextSource $context
	 * @param Title $title
	 * @param WatchedItemStoreInterface $wis
	 * @param NamespaceInfo $namespaceInfo
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param HookRunner $hookRunner
	 * @param bool $expiryEnabled
	 */
	public function __construct(
		IContextSource $context, protected Title $title, protected WatchedItemStoreInterface $wis,
		protected NamespaceInfo $namespaceInfo, protected LinkBatchFactory $linkBatchFactory,
		protected HookRunner $hookRunner, protected bool $expiryEnabled
	) {
		parent::__construct( $this->msg( 'watchlistedit-table-title' )->text(), $context, null );
	}

	protected function createNavigationBuilder(): CodexPagerNavigationBuilder {
		return new CodexPagerNavigationBuilder( $this->getContext(), $this->getRequest()->getQueryValues() );
	}

	public function getTitle(): Title {
		return $this->title;
	}

	public function isNavigationBarShown(): bool {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultSort() {
		return '';
	}

	/**
	 * Because of the way the db is indexed none of the fields are sortable via the UI, so
	 * always return false
	 *
	 * @param string $field
	 * @return false
	 */
	public function isFieldSortable( $field ): bool {
		return false;
	}

	/**
	 * Not used because we override reallyDoQuery(), but parent insists that it is implemented
	 *
	 * @inheritDoc
	 */
	public function getQueryInfo(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function getIndexField(): array {
		return [ [ 'wl_namespace', 'wl_title' ] ];
	}

	private function getNamespacesList(): array {
		$namespace = $this->mRequest->getIntOrNull( 'namespace' );
		if ( $namespace !== null ) {
			$namespaces = [ $namespace ];
		} else {
			$namespaces = array_values( $this->namespaceInfo->getSubjectNamespaces() );
		}
		return $namespaces;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $offset Index offset, inclusive
	 * @param int $limit Exact query limit
	 * @param bool $order IndexPager::QUERY_ASCENDING or IndexPager::QUERY_DESCENDING
	 * @return ResultWrapper
	 */
	public function reallyDoQuery( $offset, $limit, $order ) {
		$options = [
			'limit' => $limit,
			'sort' => $order == IndexPager::QUERY_DESCENDING
				? WatchedItemStoreInterface::SORT_DESC
				: WatchedItemStoreInterface::SORT_ASC,
			'namespaces' => $this->getNamespacesList(),
		];
		[ $offsetConds, ] = $this->getOffsetCondsAndSortOptions( $offset, $limit, $order );
		$options['offsetConds'] = is_array( $offsetConds ) ? $offsetConds : [ $offsetConds ];
		$watchedItems = $this->wis->getWatchedItemsForUser( $this->getUser(), $options );
		return $this->watchedItemArrayToResults( $watchedItems );
	}

	/**
	 * @param array $watchedItems
	 * @return ResultWrapper
	 */
	private function watchedItemArrayToResults( array $watchedItems ): ResultWrapper {
		$titles = [];
		foreach ( $watchedItems as $watchedItem ) {
			$titles[] = [
				'wl_namespace' => $watchedItem->getTarget()->getNamespace(),
				'wl_title' => $watchedItem->getTarget()->getDBkey(),
				'expiry' => $watchedItem->getExpiryInDaysText( $this->getContext() )
			];
		}
		return new FakeResultWrapper( $titles );
	}

	/**
	 * Prune and re-sort the results
	 *
	 * @return IResultWrapper
	 */
	public function getOrderedResult(): IResultWrapper {
		$lb = $this->linkBatchFactory->newLinkBatch();
		$rows = [];
		# Don't use any extra rows returned by the query
		$numRows = min( $this->mResult->numRows(), $this->mLimit );
		if ( $numRows ) {
			if ( $this->mIsBackwards ) {
				for ( $i = $numRows - 1; $i >= 0; $i-- ) {
					$this->mResult->seek( $i );
					$row = $this->mResult->fetchObject();
					$lb->add( $row->wl_namespace, $row->wl_title );
					$rows[] = $row;
				}
			} else {
				$this->mResult->seek( 0 );
				for ( $i = 0; $i < $numRows; $i++ ) {
					$row = $this->mResult->fetchObject();
					$lb->add( $row->wl_namespace, $row->wl_title );
					$rows[] = $row;
				}
			}
		}
		$lb->execute();
		return new FakeResultWrapper( $this->beforeFormRender( $rows ) );
	}

	/**
	 * Run the onWatchlistEditorBeforeFormRender hook
	 *
	 * @param array $rows
	 * @return array
	 */
	private function beforeFormRender( array $rows ): array {
		// Alas, this is very hacky - the format of the watchlist data passed by ref to the hook does not
		// correspond to anything in the pager so we have to convert it, and then convert it back
		$watchlistInfo = [];
		foreach ( $rows as $item ) {
			$watchlistInfo[$item->wl_namespace][$item->wl_title] = $item->expiry;
		}
		$this->hookRunner->onWatchlistEditorBeforeFormRender( $watchlistInfo );
		$rows = [];
		foreach ( $watchlistInfo as $namespace => $items ) {
			foreach ( $items as $titleString => $expiry ) {
				$rows[] = (object)[ 'wl_namespace' => $namespace, 'wl_title' => $titleString, 'expiry' => $expiry ];
			}
		}
		return $rows;
	}

	/**
	 * Wrap the table in a form tag and add action buttons in header
	 */
	public function getStartBody(): string {
		$html = $this->getNavigationBar();

		$html .= Html::openElement( 'form', [
			'method' => 'post',
			'id' => 'watchlist-edit-form',
			// It's not actually an HTMLForm, but for styling purposes it is.
			'class' => 'mw-htmlform-codex mw-editwatchlist-form',
		] );
		$html .= Html::hidden( 'wpEditToken', $this->getContext()->getCsrfTokenSet()->getToken() );
		$namespace = $this->getContext()->getRequest()->getIntOrNull( 'namespace' );
		if ( $namespace ) {
			$html .= Html::hidden( 'namespace', $namespace );
		}
		if ( $this->getOffset() ) {
			$html .= Html::hidden( 'offset', $this->getOffset() );
		}
		if ( $this->getLimit() ) {
			$html .= Html::hidden( 'limit', $this->getLimit() );
		}

		$html .= Html::openElement( 'div', [ 'class' => 'cdx-table' ] );
		$html .= $this->getHeader();

		$html .= Html::openElement( 'div', [ 'class' => 'cdx-table__table-wrapper' ] );

		$html .= Html::openElement( 'table', [ 'class' => $this->getTableClass() ] );
		$html .= $this->getThead();
		$html .= Html::openElement( 'tbody' );

		return $html;
	}

	/**
	 * Create custom table header with action buttons
	 */
	protected function getThead(): string {
		$html = Html::openElement( 'thead' );
		$html .= Html::openElement( 'tr' );
		foreach ( $this->getFieldNames() as $field => $name ) {
			if ( $field == 'checkbox' ) {
				$html .= Html::rawElement(
					'th',
					[ 'class' => 'cdx-table__table__select-rows' ],
					$this->getCheckbox(
						'select-all',
						'select-all-checkbox',
						'1',
						[]
					) . ' ' . Html::label(
						$this->msg( 'watchlistedit-normal-check-all' )->text(),
						'select-all-checkbox',
						[ 'class' => [ 'cdx-label--visually-hidden' ] ]
					)
				);
			} else {
				$html .= Html::element( 'th', [], $name );
			}
		}
		$html .= Html::closeElement( 'tr' );

		$html .= Html::closeElement( 'thead' );

		return $html;
	}

	/**
	 * Get a Codex-structured HTML checkbox.
	 *
	 * @param string $name
	 * @param string $id
	 * @param string $value
	 * @param array $class
	 *
	 * @return string HTML of the checkbox wrapper.
	 */
	protected function getCheckbox( string $name, string $id, string $value, array $class ): string {
		$checkbox = Html::check(
			$name,
			false,
			[
				'value' => $value,
				'class' => array_merge( [ 'cdx-checkbox__input' ], $class ),
				'id' => $id
			]
		);
		$checkboxIcon = Html::element( 'span', [ 'class' => 'cdx-checkbox__icon' ] );
		$checkboxWrapper = Html::rawElement(
			'div',
			[ 'class' => 'cdx-checkbox__wrapper' ],
			$checkbox . $checkboxIcon
		);
		return Html::rawElement( 'div', [ 'class' => 'cdx-checkbox' ], $checkboxWrapper );
	}

	/**
	 * @return string
	 */
	protected function getHeader(): string {
		$caption = Html::element(
			'div',
			[
				'class' => 'cdx-table__header__caption',
				'aria-hidden' => 'true'
			],
			$this->mCaption
		);

		// Action buttons
		$buttons = ( new Codex() )->button()
			->setAttributes( [ 'class' => 'mw-editwatchlist-remove-selected' ] )
			->setLabel( $this->msg( 'watchlistedit-table-remove-selected' )->text() )
			->setType( 'submit' )
			->setAction( 'destructive' )
			->build()
			->getHtml();

		return Html::rawElement(
			'div',
			[ 'class' => 'cdx-table__header' ],
			$caption . Html::rawElement(
				'div',
				[ 'class' => 'cdx-table__header__header-content' ],
				$buttons
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getEndBody(): string {
		$html = Html::closeElement( 'tbody' );
		$html .= Html::closeElement( 'table' );
		// close the cdx-table__table-wrapper div
		$html .= Html::closeElement( 'div' );
		// close the cdx-table div
		// @phan-suppress-next-line PhanPluginDuplicateAdjacentStatement
		$html .= Html::closeElement( 'div' );
		$html .= Html::closeElement( 'form' );
		$html .= $this->getNavigationBar();

		return $html;
	}

	/**
	 * @inheritDoc
	 */
	public function getEmptyBody(): string {
		if ( $this->getContext()->getRequest()->getIntOrNull( 'namespace' ) ) {
			$msgEmpty = $this->msg( 'nowatchlistnamespace' )->text();
		} else {
			$msgEmpty = $this->msg( 'nowatchlist' )->text();
		}
		$colspan = count( $this->getFieldNames() ) + 1;
		return Html::rawElement( 'tr', [ 'class' => 'cdx-table__table__empty-state' ],
			Html::element(
				'td',
				[ 'class' => 'cdx-table__table__empty-state-content', 'colspan' => $colspan ],
				$msgEmpty )
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getFieldNames() {
		$fields = [
			'checkbox' => '', // Empty header, buttons will be added separately
			'page' => $this->msg( 'watchlistedit-table-title-pages' )->text(),
		];
		if ( $this->expiryEnabled ) {
			$fields['expiry'] = $this->msg( 'watchlistedit-table-title-expiry' )->text();
		}
		return $fields;
	}

	/**
	 * @codeCoverageIgnore
	 * @inheritDoc
	 */
	public function formatRow( $row ): string {
		$this->mCurrentRow = $row;
		$title = Title::makeTitle(
			$this->mCurrentRow->wl_namespace,
			$this->mCurrentRow->wl_title
		);

		// Create checkbox cell
		$checkbox = $this->getCheckbox(
			SpecialEditWatchlist::CHECKBOX_NAME . '[]',
			$title->getPrefixedText(),
			$title->getPrefixedText(),
			[ 'watchlist-item-checkbox' ]
		);
		$cells = [ Html::rawElement( 'td', [], $checkbox ) ];

		// Add other cells
		foreach ( $this->getFieldNames() as $field => $name ) {
			if ( $field === 'checkbox' ) {
				continue;
			}
			$value = $row->$field ?? '';
			$formatted = $this->formatValue( $field, $value, $title );
			$cells[] = Html::rawElement( 'td', [], $formatted );
		}

		return Html::rawElement( 'tr', [], implode( '', $cells ) );
	}

	/**
	 * @inheritDoc
	 */
	public function formatValue( $name, $value, ?Title $title = null ): string {
		switch ( $name ) {
			case 'page':
				if ( $title ) {
					return $this->formatWatchedItem( $title );
				}
				return '';

			default:
				return htmlspecialchars( $value );
		}
	}

	/**
	 * @param Title $title
	 * @return string
	 * @throws \MediaWiki\Exception\MWException
	 */
	private function formatWatchedItem( Title $title ): string {
		$linkRenderer = $this->getLinkRenderer();
		$link = $linkRenderer->makeLink( $title );

		$tools = [];
		$tools['talk'] = $linkRenderer->makeLink(
			$title->getTalkPage(),
			$this->msg( 'talkpagelinktext' )->text()
		);

		if ( $title->exists() ) {
			$tools['history'] = $linkRenderer->makeKnownLink(
				$title,
				$this->msg( 'history_small' )->text(),
				[],
				[ 'action' => 'history' ]
			);
		}

		if ( $title->getNamespace() === NS_USER && !$title->isSubpage() ) {
			$tools['contributions'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Contributions', $title->getText() ),
				$this->msg( 'contribslink' )->text()
			);
		}

		$this->hookRunner->onWatchlistEditorBuildRemoveLine(
			$tools, $title, $title->isRedirect(), $this->getSkin(), $link );

		return Html::rawElement(
			'label',
			[ 'for' => $title->getPrefixedText() ],
			$link . ' ' . Html::openElement( 'span', [ 'class' => 'mw-changeslist-links' ] ) .
			implode(
				'',
				array_map( static function ( $tool ) {
					return Html::rawElement( 'span', [], $tool );
				}, $tools )
			) .
			Html::closeElement( 'span' )
		);
	}
}

/** @deprecated class alias since 1.46 */
class_alias( EditWatchlistPager::class, 'MediaWiki\\Pager\\EditWatchlistPager' );
