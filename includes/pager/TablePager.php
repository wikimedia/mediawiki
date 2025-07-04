<?php
/**
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
 */

namespace MediaWiki\Pager;

use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Xml\XmlSelect;
use OOUI\ButtonGroupWidget;
use OOUI\ButtonWidget;
use stdClass;

/**
 * Table-based display with a user-selectable sort order
 *
 * @stable to extend
 * @ingroup Pager
 */
abstract class TablePager extends IndexPager {
	/** @var string */
	protected $mSort;

	/** @var stdClass */
	protected $mCurrentRow;

	/**
	 * @stable to call
	 *
	 * @param IContextSource|null $context
	 * @param LinkRenderer|null $linkRenderer
	 */
	public function __construct( ?IContextSource $context = null, ?LinkRenderer $linkRenderer = null ) {
		if ( $context ) {
			$this->setContext( $context );
		}

		$this->mSort = $this->getRequest()->getText( 'sort' );
		if ( !array_key_exists( $this->mSort, $this->getFieldNames() )
			|| !$this->isFieldSortable( $this->mSort )
		) {
			$this->mSort = $this->getDefaultSort();
		}
		if ( $this->getRequest()->getBool( 'asc' ) ) {
			$this->mDefaultDirection = IndexPager::DIR_ASCENDING;
		} elseif ( $this->getRequest()->getBool( 'desc' ) ) {
			$this->mDefaultDirection = IndexPager::DIR_DESCENDING;
		} /* Else leave it at whatever the class default is */

		// Parent constructor needs mSort set, so we call it last
		parent::__construct( null, $linkRenderer );
	}

	/**
	 * Get the formatted result list.
	 *
	 * Calls getBody() and getModuleStyles() and builds a ParserOutput object. (This is a bit hacky
	 * but works well.)
	 *
	 * @since 1.24
	 * @return ParserOutput
	 */
	public function getBodyOutput() {
		$body = parent::getBody();

		$pout = new ParserOutput;
		$pout->setRawText( $body );
		return $pout;
	}

	/**
	 * Get the formatted result list, with navigation bars.
	 *
	 * Calls getBody(), getNavigationBar() and getModuleStyles() and
	 * builds a ParserOutput object. (This is a bit hacky but works well.)
	 *
	 * @since 1.24
	 * @return ParserOutput
	 */
	public function getFullOutput() {
		$navigation = $this->getNavigationBar();
		$body = parent::getBody();

		$pout = new ParserOutput;
		$pout->setRawText( $navigation . $body . $navigation );
		$pout->addModuleStyles( $this->getModuleStyles() );
		return $pout;
	}

	/**
	 * @stable to override
	 * @return string
	 */
	protected function getStartBody() {
		$sortClass = $this->getSortHeaderClass();

		$s = '';
		$fields = $this->getFieldNames();

		// Make table header
		foreach ( $fields as $field => $name ) {
			if ( strval( $name ) == '' ) {
				$s .= Html::rawElement( 'th', [], "\u{00A0}" ) . "\n";
			} elseif ( $this->isFieldSortable( $field ) ) {
				$query = [ 'sort' => $field, 'limit' => $this->mLimit ];
				$linkType = null;
				$class = null;

				if ( $this->mSort == $field ) {
					// The table is sorted by this field already, make a link to sort in the other direction
					// We don't actually know in which direction other fields will be sorted by default…
					if ( $this->mDefaultDirection == IndexPager::DIR_DESCENDING ) {
						$linkType = 'asc';
						$class = "$sortClass mw-datatable-is-sorted mw-datatable-is-descending";
						$query['asc'] = '1';
						$query['desc'] = '';
					} else {
						$linkType = 'desc';
						$class = "$sortClass mw-datatable-is-sorted mw-datatable-is-ascending";
						$query['asc'] = '';
						$query['desc'] = '1';
					}
				}

				$link = $this->makeLink( htmlspecialchars( $name ), $query, $linkType );
				$s .= Html::rawElement( 'th', [ 'class' => $class ], $link ) . "\n";
			} else {
				$s .= Html::element( 'th', [], $name ) . "\n";
			}
		}

		$ret = Html::openElement( 'table', [
			'class' => $this->getTableClass() ]
		);
		$ret .= Html::rawElement( 'thead', [], Html::rawElement( 'tr', [], "\n" . $s . "\n" ) );
		$ret .= Html::openElement( 'tbody' ) . "\n";

		return $ret;
	}

	/**
	 * @stable to override
	 * @return string
	 */
	protected function getEndBody() {
		return "</tbody></table>\n";
	}

	/**
	 * @return string
	 */
	protected function getEmptyBody() {
		$colspan = count( $this->getFieldNames() );
		$msgEmpty = $this->msg( 'table_pager_empty' )->text();
		return Html::rawElement( 'tr', [],
			Html::element( 'td', [ 'colspan' => $colspan ], $msgEmpty ) );
	}

	/**
	 * @stable to override
	 * @param stdClass $row
	 * @return string HTML
	 */
	public function formatRow( $row ) {
		$this->mCurrentRow = $row; // In case formatValue etc need to know
		$s = Html::openElement( 'tr', $this->getRowAttrs( $row ) ) . "\n";
		$fieldNames = $this->getFieldNames();

		foreach ( $fieldNames as $field => $name ) {
			$value = $row->$field ?? null;
			$formatted = strval( $this->formatValue( $field, $value ) );

			if ( $formatted == '' ) {
				$formatted = "\u{00A0}";
			}

			$s .= Html::rawElement( 'td', $this->getCellAttrs( $field, $value ), $formatted ) . "\n";
		}

		$s .= Html::closeElement( 'tr' ) . "\n";

		return $s;
	}

	/**
	 * Get a class name to be applied to the given row.
	 *
	 * @stable to override
	 *
	 * @param stdClass $row The database result row
	 * @return string
	 */
	protected function getRowClass( $row ) {
		return '';
	}

	/**
	 * Get attributes to be applied to the given row.
	 *
	 * @stable to override
	 *
	 * @param stdClass $row The database result row
	 * @return array Array of attribute => value
	 */
	protected function getRowAttrs( $row ) {
		return [ 'class' => $this->getRowClass( $row ) ];
	}

	/**
	 * @return stdClass
	 */
	protected function getCurrentRow() {
		return $this->mCurrentRow;
	}

	/**
	 * Get any extra attributes to be applied to the given cell. Don't
	 * take this as an excuse to hardcode styles; use classes and
	 * CSS instead.  Row context is available in $this->mCurrentRow
	 *
	 * @stable to override
	 *
	 * @param string $field The column
	 * @param string $value The cell contents
	 * @return array Array of attr => value
	 */
	protected function getCellAttrs( $field, $value ) {
		return [ 'class' => 'TablePager_col_' . $field ];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getIndexField() {
		return $this->mSort;
	}

	/**
	 * TablePager relies on `mw-datatable` for styling, see T214208
	 *
	 * @stable to override
	 * @return string
	 */
	protected function getTableClass() {
		return 'mw-datatable';
	}

	/**
	 * @stable to override
	 * @return string
	 */
	protected function getNavClass() {
		return 'TablePager_nav';
	}

	/**
	 * @stable to override
	 * @return string
	 */
	protected function getSortHeaderClass() {
		return 'TablePager_sort';
	}

	/**
	 * A navigation bar with images
	 *
	 * @stable to override
	 * @return string HTML
	 */
	public function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		$this->getOutput()->enableOOUI();

		$types = [ 'first', 'prev', 'next', 'last' ];

		$queries = $this->getPagingQueries();

		$buttons = [];

		$title = $this->getTitle();

		foreach ( $types as $type ) {
			$buttons[] = new ButtonWidget( [
				// Messages used here:
				// * table_pager_first
				// * table_pager_prev
				// * table_pager_next
				// * table_pager_last
				'classes' => [ 'TablePager-button-' . $type ],
				'flags' => [ 'progressive' ],
				'framed' => false,
				'label' => $this->msg( 'table_pager_' . $type )->text(),
				'href' => $queries[ $type ] ?
					$title->getLinkURL( $queries[ $type ] + $this->getDefaultQuery() ) :
					null,
				'icon' => $type === 'prev' ? 'previous' : $type,
				'disabled' => $queries[ $type ] === false
			] );
		}
		return new ButtonGroupWidget( [
			'classes' => [ $this->getNavClass() ],
			'items' => $buttons,
		] );
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleStyles() {
		return array_merge(
			parent::getModuleStyles(), [ 'oojs-ui.styles.icons-movement' ]
		);
	}

	/**
	 * Get a "<select>" element which has options for each of the allowed limits
	 *
	 * @param string[] $attribs Extra attributes to set
	 * @return string HTML fragment
	 */
	public function getLimitSelect( array $attribs = [] ): string {
		$select = new XmlSelect( 'limit', false, (string)$this->mLimit );
		$select->addOptions( $this->getLimitSelectList() );
		foreach ( $attribs as $name => $value ) {
			$select->setAttribute( $name, $value );
		}
		return $select->getHTML();
	}

	/**
	 * Get a list of items to show in a "<select>" element of limits.
	 * This can be passed directly to XmlSelect::addOptions().
	 *
	 * @since 1.22
	 * @return array
	 */
	public function getLimitSelectList() {
		# Add the current limit from the query string
		# to avoid that the limit is lost after clicking Go next time
		if ( !in_array( $this->mLimit, $this->mLimitsShown ) ) {
			$this->mLimitsShown[] = $this->mLimit;
			sort( $this->mLimitsShown );
		}
		$ret = [];
		foreach ( $this->mLimitsShown as $key => $value ) {
			# The pair is either $index => $limit, in which case the $value
			# will be numeric, or $limit => $text, in which case the $value
			# will be a string.
			if ( is_int( $value ) ) {
				$limit = $value;
				$text = $this->getLanguage()->formatNum( $limit );
			} else {
				$limit = $key;
				$text = $value;
			}
			$ret[$text] = $limit;
		}
		return $ret;
	}

	/**
	 * Get \<input type="hidden"\> elements for use in a method="get" form.
	 * Resubmits all defined elements of the query string, except for a
	 * exclusion list, passed in the $noResubmit parameter.
	 * Also array values are discarded for security reasons (per WebRequest::getVal)
	 *
	 * @param array $noResubmit Parameters from the request query which should not be resubmitted
	 * @return string HTML fragment
	 */
	public function getHiddenFields( $noResubmit = [] ) {
		$noResubmit = (array)$noResubmit;
		$query = $this->getRequest()->getQueryValues();
		foreach ( $noResubmit as $name ) {
			unset( $query[$name] );
		}
		$s = '';
		foreach ( $query as $name => $value ) {
			if ( is_array( $value ) ) {
				// Per WebRequest::getVal: Array values are discarded for security reasons.
				continue;
			}
			$s .= Html::hidden( $name, $value ) . "\n";
		}
		return $s;
	}

	/**
	 * Get a form containing a limit selection dropdown
	 *
	 * @return string HTML fragment
	 */
	public function getLimitForm() {
		return Html::rawElement(
			'form',
			[
				'method' => 'get',
				'action' => wfScript(),
			],
			"\n" . $this->getLimitDropdown()
		) . "\n";
	}

	/**
	 * Gets a limit selection dropdown
	 *
	 * @return string
	 */
	private function getLimitDropdown() {
		# Make the select with some explanatory text
		$msgSubmit = $this->msg( 'table_pager_limit_submit' )->escaped();

		return $this->msg( 'table_pager_limit' )
			->rawParams( $this->getLimitSelect() )->escaped() .
			"\n<input type=\"submit\" value=\"$msgSubmit\"/>\n" .
			$this->getHiddenFields( [ 'limit' ] );
	}

	/**
	 * Return true if the named field should be sortable by the UI, false
	 * otherwise
	 *
	 * @param string $field
	 * @return bool
	 */
	abstract protected function isFieldSortable( $field );

	/**
	 * Format a table cell. The return value should be HTML, but use an empty
	 * string not &#160; for empty cells. Do not include the <td> and </td>.
	 *
	 * The current result row is available as $this->mCurrentRow, in case you
	 * need more context.
	 *
	 * @param string $name The database field name
	 * @param string|null $value The value retrieved from the database, or null if
	 *   the row doesn't contain this field
	 */
	abstract public function formatValue( $name, $value );

	/**
	 * The database field name used as a default sort order.
	 *
	 * Note that this field will only be sorted on if isFieldSortable returns
	 * true for this field. If not (e.g. paginating on multiple columns), this
	 * should return empty string, and getIndexField should be overridden.
	 *
	 * @return string
	 */
	abstract public function getDefaultSort();

	/**
	 * An array mapping database field names to a textual description of the
	 * field name, for use in the table header. The description should be plain
	 * text, it will be HTML-escaped later.
	 *
	 * @return string[]
	 */
	abstract protected function getFieldNames();
}

/** @deprecated class alias since 1.41 */
class_alias( TablePager::class, 'TablePager' );
