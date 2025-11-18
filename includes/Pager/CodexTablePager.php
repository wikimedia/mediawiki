<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Pager;

use InvalidArgumentException;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Navigation\CodexPagerNavigationBuilder;
use MediaWiki\Parser\ParserOutput;

/**
 * Codex Table display of sortable data with pagination.
 *
 * @stable to extend
 * @ingroup Pager
 */
abstract class CodexTablePager extends TablePager {
	protected string $mCaption;

	/**
	 * @stable to call
	 *
	 * @param string $caption Text for the caption element
	 * @param ?IContextSource $context
	 * @param ?LinkRenderer $linkRenderer
	 */
	public function __construct(
		string $caption,
		?IContextSource $context = null,
		?LinkRenderer $linkRenderer = null
	) {
		if ( trim( $caption ) === '' ) {
			throw new InvalidArgumentException( 'Table caption is required.' );
		}
		$this->mCaption = $caption;

		$this->getOutput()->addModules( 'mediawiki.pager.codex' );

		parent::__construct( $context, $linkRenderer );

		$this->getOutput()->addJsConfigVars( [
			'wgCodexTablePagerLimit' => $this->mLimit,
		] );
	}

	/**
	 * Get the entire Codex table markup, including the wrapper element, pagers, table wrapper
	 * (which enables horizontal scroll), and the table element.
	 *
	 * @since 1.44
	 */
	public function getFullOutput(): ParserOutput {
		// Pagers.
		$navigation = $this->getNavigationBar();
		// `<table>` element and its contents.
		$body = parent::getBody();
		$header = $this->getHeader();

		$pout = new ParserOutput();
		$pout->setRawText(
			Html::openElement( 'div', [ 'class' => 'cdx-table' ] ) . "\n" .
			$header . "\n" .
			$navigation . "\n" .
			Html::openElement( 'div', [ 'class' => 'cdx-table__table-wrapper' ] ) . "\n" .
			$body . "\n" .
			Html::closeElement( 'div' ) . "\n" .
			// In the future, footer content could go here.
			$navigation . "\n" .
			Html::closeElement( 'div' )
		);
		$pout->addModuleStyles( $this->getModuleStyles() );
		return $pout;
	}

	/**
	 * Generate the `<thead>` element.
	 *
	 * This creates a thead with a single tr and includes sort buttons if applicable. To customize
	 * the thead layout, override this method.
	 *
	 * @stable to override
	 */
	protected function getThead(): string {
		$theadContent = '';
		$fields = $this->getFieldNames();

		// Build each th element.
		foreach ( $fields as $field => $name ) {
			if ( $name === '' ) {
				// th with no label (not advised).
				$theadContent .= Html::rawElement( 'th', $this->getCellAttrs( $field, $name ), "\u{00A0}" ) . "\n";
			} elseif ( $this->isFieldSortable( $field ) ) {
				// Sortable column.
				$query = [ 'sort' => $field, 'limit' => $this->mLimit ];
				$sortIconClasses = [ 'cdx-table__table__sort-icon' ];

				if ( $this->mSort == $field ) {
					// Set data for the currently sorted column.
					if ( $this->mDefaultDirection == IndexPager::DIR_DESCENDING ) {
						$sortIconClasses[] = 'cdx-table__table__sort-icon--desc';
						$query['asc'] = '1';
						$query['desc'] = '';
					} else {
						$sortIconClasses[] = 'cdx-table__table__sort-icon--asc';
						$query['asc'] = '';
						$query['desc'] = '1';
					}
				} else {
					$sortIconClasses[] = 'cdx-table__table__sort-icon--unsorted';
				}

				// Build the label and icon span that go inside the link.
				$linkContents = Html::rawElement( 'span',
					[ 'class' => 'cdx-table__table__sort-label' ],
					htmlspecialchars( $name )
				) . "\n" .
				Html::rawElement( 'span',
					[ 'class' => $sortIconClasses, 'aria-hidden' => true ]
				) . "\n";

				// Build the link that goes inside the th.
				$link = Html::rawElement( 'a',
					[
						'class' => [ 'cdx-table__table__sort-button' ],
						'role' => 'button',
						'href' => $this->getTitle()->getLinkURL( $query + $this->getDefaultQuery() ),
					],
					$linkContents
				);

				// Build the th.
				$thAttrs = $this->getCellAttrs( $field, $name );
				$thAttrs[ 'class' ][] = $this->getSortHeaderClass();
				$theadContent .= Html::rawElement( 'th', $thAttrs, $link ) . "\n";
			} else {
				// Unsortable column.
				$theadContent .= Html::element( 'th', $this->getCellAttrs( $field, $name ), $name ) . "\n";
			}
		}
		return Html::rawElement( 'thead', [], Html::rawElement( 'tr', [], "\n" . $theadContent . "\n" ) );
	}

	/**
	 * Append text to the caption if any fields are sortable.
	 *
	 * @param string $captionText Caption provided for the table
	 */
	private function getFullCaption( string $captionText ): string {
		$fields = $this->getFieldNames();

		// Make table header
		foreach ( $fields as $field => $name ) {
			if ( $this->isFieldSortable( $field ) === true ) {
				return $this->msg( 'cdx-table-sort-caption', $captionText )->text();
			}
		}

		return $captionText;
	}

	/**
	 * Get the opening table tag through the opening tbody tag.
	 *
	 * This method should generally not be overridden: use getThead() to create a custom `<thead>`
	 * and getTableClass to set additional classes on the `<table>` element.
	 *
	 * @stable to override
	 */
	protected function getStartBody(): string {
		$ret = Html::openElement( 'table', [
			'class' => $this->getTableClass() ]
		);
		$ret .= Html::element( 'caption', [], $this->getFullCaption( $this->mCaption ) );
		$ret .= $this->getThead();
		$ret .= Html::openElement( 'tbody' ) . "\n";

		return $ret;
	}

	/**
	 * Override to add a `<tfoot>` element.
	 *
	 * @stable to override
	 */
	protected function getTfoot(): string {
		return '';
	}

	/**
	 * Get the closing tbody tag through the closing table tag.
	 *
	 * @stable to override
	 */
	protected function getEndBody(): string {
		return "</tbody>" . $this->getTfoot() . "</table>\n";
	}

	/**
	 * Get markup for the "no results" UI. This is placed inside the tbody tag.
	 */
	protected function getEmptyBody(): string {
		$colspan = count( $this->getFieldNames() );
		$msgEmpty = $this->msg( 'table_pager_empty' )->text();
		return Html::rawElement( 'tr', [ 'class' => 'cdx-table__table__empty-state' ],
			Html::element(
				'td',
				[ 'class' => 'cdx-table__table__empty-state-content', 'colspan' => $colspan ],
				$msgEmpty )
			);
	}

	/**
	 * Add alignment per column.
	 *
	 * @param string $field The column
	 * @return string start (default), center, end, or number (always to the right)
	 */
	protected function getCellAlignment( string $field ): string {
		return 'start';
	}

	/**
	 * Add extra attributes to be applied to the given cell.
	 *
	 * @stable to override
	 *
	 * @param string $field The column
	 * @param string $value The cell contents
	 * @return array Array of attr => value
	 */
	protected function getCellAttrs( $field, $value ): array {
		return [
			'class' => [
				'cdx-table-pager__col--' . $field,
				'cdx-table__table__cell--align-' . $this->getCellAlignment( $field )
			]
		];
	}

	/**
	 * Class for the `<table>` element.
	 *
	 * @stable to override
	 */
	protected function getTableClass(): string {
		return 'cdx-table__table';
	}

	/**
	 * Class for the outermost element of the pager UI.
	 *
	 * @stable to override
	 */
	protected function getNavClass(): string {
		return 'cdx-table-pager';
	}

	/**
	 * Class for th elements of sortable columns.
	 *
	 * @stable to override
	 */
	protected function getSortHeaderClass(): string {
		return 'cdx-table__table__cell--has-sort';
	}

	/**
	 * Pager bar with per-page limit and pager buttons.
	 *
	 * @stable to override
	 *
	 * @return string HTML for the pager UI
	 */
	public function getNavigationBar(): string {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		return $this->getNavigationBuilder()->getHtml();
	}

	/**
	 * @inheritDoc
	 */
	protected function createNavigationBuilder(): CodexPagerNavigationBuilder {
		$builder = new CodexPagerNavigationBuilder( $this->getContext(), $this->getRequest()->getQueryValues() );
		$builder->setNavClass( $this->getNavClass() );
		return $builder;
	}

	/**
	 * @return CodexPagerNavigationBuilder
	 */
	public function getNavigationBuilder(): CodexPagerNavigationBuilder {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return parent::getNavigationBuilder();
	}

	/**
	 * Get a `<select>` element with options for items-per-page limits.
	 *
	 * @param string[] $attribs Extra attributes to set
	 * @return string HTML fragment
	 */
	public function getLimitSelect( array $attribs = [] ): string {
		$navBuilder = $this->getNavigationBuilder();
		return $navBuilder->getLimitSelect();
	}

	/**
	 * Get a list of items to show as options in the item-per-page select.
	 */
	public function getLimitSelectList(): array {
		$navBuilder = $this->getNavigationBuilder();
		return $navBuilder->getLimitSelectList();
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleStyles(): array {
		return [ 'mediawiki.pager.codex.styles' ];
	}

	/**
	 * Returns whether a visible table caption should be shown.
	 *
	 * @since 1.46
	 * @stable to override
	 */
	protected function shouldShowVisibleCaption(): bool {
		return false;
	}

	protected function getHeader(): string {
		if ( !$this->shouldShowVisibleCaption() ) {
			return '';
		}

		$captionAttributes = [
			'class' => 'cdx-table__header__caption',
			'aria-hidden' => 'true'
		];

		return Html::rawElement(
			'div',
			[ 'class' => 'cdx-table__header' ],
			Html::element(
				'div',
				$captionAttributes,
				$this->mCaption
			)
		);
	}
}

/** @deprecated class alias since 1.41 */
class_alias( CodexTablePager::class, 'CodexTablePager' );
