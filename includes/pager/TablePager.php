<?php
/**
 * Efficient paging for SQL queries.
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
 * @ingroup Pager
 */

/**
 * Table-based display with a user-selectable sort order
 * @ingroup Pager
 */
abstract class TablePager extends IndexPager {
	protected $mSort;

	protected $mCurrentRow;

	public function __construct( IContextSource $context = null ) {
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

		parent::__construct();
	}

	/**
	 * Get the formatted result list. Calls getStartBody(), formatRow() and getEndBody(), concatenates
	 * the results and returns them.
	 *
	 * Also adds the required styles to our OutputPage object (this means that if context wasn't
	 * passed to constructor or otherwise set up, you will get a pager with missing styles).
	 *
	 * This method has been made 'final' in 1.24. There's no reason to override it, and if there exist
	 * any subclasses that do, the style loading hack is probably broken in them. Let's fail fast
	 * rather than mysteriously render things wrong.
	 *
	 * @deprecated since 1.24, use getBodyOutput() or getFullOutput() instead
	 * @return string
	 */
	final public function getBody() {
		$this->getOutput()->addModuleStyles( $this->getModuleStyles() );
		return parent::getBody();
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
		$pout->setText( $body );
		$pout->addModuleStyles( $this->getModuleStyles() );
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
		$pout->setText( $navigation . $body . $navigation );
		$pout->addModuleStyles( $this->getModuleStyles() );
		return $pout;
	}

	/**
	 * @protected
	 * @return string
	 */
	function getStartBody() {
		$sortClass = $this->getSortHeaderClass();

		$s = '';
		$fields = $this->getFieldNames();

		// Make table header
		foreach ( $fields as $field => $name ) {
			if ( strval( $name ) == '' ) {
				$s .= Html::rawElement( 'th', array(), '&#160;' ) . "\n";
			} elseif ( $this->isFieldSortable( $field ) ) {
				$query = array( 'sort' => $field, 'limit' => $this->mLimit );
				$linkType = null;
				$class = null;

				if ( $this->mSort == $field ) {
					// The table is sorted by this field already, make a link to sort in the other direction
					// We don't actually know in which direction other fields will be sorted by default…
					if ( $this->mDefaultDirection == IndexPager::DIR_DESCENDING ) {
						$linkType = 'asc';
						$class = "$sortClass TablePager_sort-descending";
						$query['asc'] = '1';
						$query['desc'] = '';
					} else {
						$linkType = 'desc';
						$class = "$sortClass TablePager_sort-ascending";
						$query['asc'] = '';
						$query['desc'] = '1';
					}
				}

				$link = $this->makeLink( htmlspecialchars( $name ), $query, $linkType );
				$s .= Html::rawElement( 'th', array( 'class' => $class ), $link ) . "\n";
			} else {
				$s .= Html::element( 'th', array(), $name ) . "\n";
			}
		}

		$tableClass = $this->getTableClass();
		$ret = Html::openElement( 'table', array(
			'class' => "mw-datatable $tableClass" )
		);
		$ret .= Html::rawElement( 'thead', array(), Html::rawElement( 'tr', array(), "\n" . $s . "\n" ) );
		$ret .= Html::openElement( 'tbody' ) . "\n";

		return $ret;
	}

	/**
	 * @protected
	 * @return string
	 */
	function getEndBody() {
		return "</tbody></table>\n";
	}

	/**
	 * @protected
	 * @return string
	 */
	function getEmptyBody() {
		$colspan = count( $this->getFieldNames() );
		$msgEmpty = $this->msg( 'table_pager_empty' )->text();
		return Html::rawElement( 'tr', array(),
			Html::element( 'td', array( 'colspan' => $colspan ), $msgEmpty ) );
	}

	/**
	 * @protected
	 * @param stdClass $row
	 * @return string HTML
	 */
	function formatRow( $row ) {
		$this->mCurrentRow = $row; // In case formatValue etc need to know
		$s = Html::openElement( 'tr', $this->getRowAttrs( $row ) ) . "\n";
		$fieldNames = $this->getFieldNames();

		foreach ( $fieldNames as $field => $name ) {
			$value = isset( $row->$field ) ? $row->$field : null;
			$formatted = strval( $this->formatValue( $field, $value ) );

			if ( $formatted == '' ) {
				$formatted = '&#160;';
			}

			$s .= Html::rawElement( 'td', $this->getCellAttrs( $field, $value ), $formatted ) . "\n";
		}

		$s .= Html::closeElement( 'tr' ) . "\n";

		return $s;
	}

	/**
	 * Get a class name to be applied to the given row.
	 *
	 * @protected
	 *
	 * @param object $row The database result row
	 * @return string
	 */
	function getRowClass( $row ) {
		return '';
	}

	/**
	 * Get attributes to be applied to the given row.
	 *
	 * @protected
	 *
	 * @param object $row The database result row
	 * @return array Array of attribute => value
	 */
	function getRowAttrs( $row ) {
		$class = $this->getRowClass( $row );
		if ( $class === '' ) {
			// Return an empty array to avoid clutter in HTML like class=""
			return array();
		} else {
			return array( 'class' => $this->getRowClass( $row ) );
		}
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
	 * @protected
	 *
	 * @param string $field The column
	 * @param string $value The cell contents
	 * @return array Array of attr => value
	 */
	function getCellAttrs( $field, $value ) {
		return array( 'class' => 'TablePager_col_' . $field );
	}

	/**
	 * @protected
	 * @return string
	 */
	function getIndexField() {
		return $this->mSort;
	}

	/**
	 * @protected
	 * @return string
	 */
	function getTableClass() {
		return 'TablePager';
	}

	/**
	 * @protected
	 * @return string
	 */
	function getNavClass() {
		return 'TablePager_nav';
	}

	/**
	 * @protected
	 * @return string
	 */
	function getSortHeaderClass() {
		return 'TablePager_sort';
	}

	/**
	 * A navigation bar with images
	 * @return string HTML
	 */
	public function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		$labels = array(
			'first' => 'table_pager_first',
			'prev' => 'table_pager_prev',
			'next' => 'table_pager_next',
			'last' => 'table_pager_last',
		);

		$linkTexts = array();
		$disabledTexts = array();
		foreach ( $labels as $type => $label ) {
			$msgLabel = $this->msg( $label )->escaped();
			$linkTexts[$type] = "<div class='TablePager_nav-enabled'>$msgLabel</div>";
			$disabledTexts[$type] = "<div class='TablePager_nav-disabled'>$msgLabel</div>";
		}
		$links = $this->getPagingLinks( $linkTexts, $disabledTexts );

		$s = Html::openElement( 'table', array( 'class' => $this->getNavClass() ) );
		$s .= Html::openElement( 'tr' ) . "\n";
		$width = 100 / count( $links ) . '%';
		foreach ( $labels as $type => $label ) {
			// We want every cell to have the same width. We could use table-layout: fixed; in CSS,
			// but it only works if we specify the width of a cell or the table and we don't want to.
			// There is no better way. <http://www.w3.org/TR/CSS2/tables.html#fixed-table-layout>
			$s .= Html::rawElement( 'td',
				array( 'style' => "width: $width;", 'class' => "TablePager_nav-$type" ),
				$links[$type] ) . "\n";
		}
		$s .= Html::closeElement( 'tr' ) . Html::closeElement( 'table' ) . "\n";
		return $s;
	}

	/**
	 * ResourceLoader modules that must be loaded to provide correct styling for this pager
	 * @since 1.24
	 * @return string[]
	 */
	public function getModuleStyles() {
		return array( 'mediawiki.pager.tablePager' );
	}

	/**
	 * Get a "<select>" element which has options for each of the allowed limits
	 *
	 * @param string $attribs Extra attributes to set
	 * @return string HTML fragment
	 */
	public function getLimitSelect( $attribs = array() ) {
		$select = new XmlSelect( 'limit', false, $this->mLimit );
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
		$ret = array();
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
	 * blacklist, passed in the $blacklist parameter.
	 *
	 * @param array $blacklist Parameters from the request query which should not be resubmitted
	 * @return string HTML fragment
	 */
	function getHiddenFields( $blacklist = array() ) {
		$blacklist = (array)$blacklist;
		$query = $this->getRequest()->getQueryValues();
		foreach ( $blacklist as $name ) {
			unset( $query[$name] );
		}
		$s = '';
		foreach ( $query as $name => $value ) {
			$s .= Html::hidden( $name, $value ) . "\n";
		}
		return $s;
	}

	/**
	 * Get a form containing a limit selection dropdown
	 *
	 * @return string HTML fragment
	 */
	function getLimitForm() {
		return Html::rawElement(
			'form',
			array(
				'method' => 'get',
				'action' => wfScript(),
			),
			"\n" . $this->getLimitDropdown()
		) . "\n";
	}

	/**
	 * Gets a limit selection dropdown
	 *
	 * @return string
	 */
	function getLimitDropdown() {
		# Make the select with some explanatory text
		$msgSubmit = $this->msg( 'table_pager_limit_submit' )->escaped();

		return $this->msg( 'table_pager_limit' )
			->rawParams( $this->getLimitSelect() )->escaped() .
			"\n<input type=\"submit\" value=\"$msgSubmit\"/>\n" .
			$this->getHiddenFields( array( 'limit' ) );
	}

	/**
	 * Return true if the named field should be sortable by the UI, false
	 * otherwise
	 *
	 * @param string $field
	 */
	abstract function isFieldSortable( $field );

	/**
	 * Format a table cell. The return value should be HTML, but use an empty
	 * string not &#160; for empty cells. Do not include the <td> and </td>.
	 *
	 * The current result row is available as $this->mCurrentRow, in case you
	 * need more context.
	 *
	 * @protected
	 *
	 * @param string $name The database field name
	 * @param string $value The value retrieved from the database
	 */
	abstract function formatValue( $name, $value );

	/**
	 * The database field name used as a default sort order.
	 *
	 * @protected
	 *
	 * @return string
	 */
	abstract function getDefaultSort();

	/**
	 * An array mapping database field names to a textual description of the
	 * field name, for use in the table header. The description should be plain
	 * text, it will be HTML-escaped later.
	 *
	 * @return array
	 */
	abstract function getFieldNames();
}
