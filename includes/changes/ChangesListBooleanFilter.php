<?php
/**
 * Represents a hide-based boolean filter (used on ChangesListSpecialPage and descendants)
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
 * @license GPL 2+
 * @author Matthew Flaschen
 */

use Wikimedia\Rdbms\IDatabase;

/**
 * An individual filter in a boolean group
 *
 * @since 1.29
 */
class ChangesListBooleanFilter extends ChangesListFilter {
	/**
	 * Name.  Used as URL parameter
	 *
	 * @var string $name
	 */

	// This can sometimes be different on Special:RecentChanges
	// and Special:Watchlist, due to the double-legacy hooks
	// (SpecialRecentChangesFilters and SpecialWatchlistFilters)

	// but there will be separate sets of ChangesListFilterGroup and ChangesListFilter instances
	// for those pages (it should work even if they're both loaded
	// at once, but that can't happen).
	/**
	 * Main unstructured UI i18n key
	 *
	 * @var string $showHide
	 */
	protected $showHide;

	/**
	 * Whether there is a feature designed to replace this filter available on the
	 * structured UI
	 *
	 * @var bool $isReplacedInStructuredUi
	 */
	protected $isReplacedInStructuredUi;

	/**
	 * Default
	 *
	 * @var bool $defaultValue
	 */
	protected $defaultValue;

	/**
	 * Callable used to do the actual query modification; see constructor
	 *
	 * @var callable $queryCallable
	 */
	protected $queryCallable;

	/**
	 * Create a new filter with the specified configuration.
	 *
	 * It infers which UI (it can be either or both) to display the filter on based on
	 * which messages are provided.
	 *
	 * If 'label' is provided, it will be displayed on the structured UI.  If
	 * 'showHide' is provided, it will be displayed on the unstructured UI.  Thus,
	 * 'label', 'description', and 'showHide' are optional depending on which UI
	 * it's for.
	 *
	 * @param array $filterDefinition ChangesListFilter definition
	 *
	 * $filterDefinition['name'] string Name.  Used as URL parameter.
	 * $filterDefinition['group'] ChangesListFilterGroup Group.  Filter group this
	 *  belongs to.
	 * $filterDefinition['label'] string i18n key of label for structured UI.
	 * $filterDefinition['description'] string i18n key of description for structured
	 *  UI.
	 * $filterDefinition['showHide'] string Main i18n key used for unstructured UI.
	 * $filterDefinition['isReplacedInStructuredUi'] bool Whether there is an
	 *  equivalent feature available in the structured UI; this is optional, defaulting
	 *  to true.  It does not need to be set if the exact same filter is simply visible
	 *  on both.
	 * $filterDefinition['default'] bool Default
	 * $filterDefinition['priority'] int Priority integer.  Higher value means higher
	 *  up in the group's filter list.
	 * $filterDefinition['queryCallable'] callable Callable accepting parameters, used
	 *  to implement filter's DB query modification.  Callback parameters:
	 *   string $specialPageClassName Class name of current special page
	 *   IContextSource $context Context, for e.g. user
	 *   IDatabase $dbr Database, for addQuotes, makeList, and similar
	 *   array &$tables Array of tables; see IDatabase::select $table
	 *   array &$fields Array of fields; see IDatabase::select $vars
	 *   array &$conds Array of conditions; see IDatabase::select $conds
	 *   array &$query_options Array of query options; see IDatabase::select $options
	 *   array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 *   Optional only for legacy filters that still use the query hooks directly
	 */
	public function __construct( $filterDefinition ) {
		parent::__construct( $filterDefinition );

		if ( isset( $filterDefinition['showHide'] ) ) {
			$this->showHide = $filterDefinition['showHide'];
		}

		if ( isset( $filterDefinition['isReplacedInStructuredUi'] ) ) {
			$this->isReplacedInStructuredUi = $filterDefinition['isReplacedInStructuredUi'];
		} else {
			$this->isReplacedInStructuredUi = false;
		}

		if ( isset( $filterDefinition['default'] ) ) {
			$this->defaultValue = $filterDefinition['default'];
		} else {
			throw new MWException( 'You must set a default' );
		}

		if ( isset( $filterDefinition['queryCallable'] ) ) {
			$this->queryCallable = $filterDefinition['queryCallable'];
		}
	}

	/**
	 * Get the default value
	 *
	 * @param bool $structuredUI Are we currently showing the structured UI
	 * @return bool|null Default value
	 */
	public function getDefault( $structuredUI = false ) {
		return $this->isReplacedInStructuredUi && $structuredUI ?
			false :
			$this->defaultValue;
	}

	/**
	 * Sets default
	 *
	 * @param bool Default value
	 */
	public function setDefault( $defaultValue ) {
		$this->defaultValue = $defaultValue;
	}

	/**
	 * @return string Main i18n key for unstructured UI
	 */
	public function getShowHide() {
		return $this->showHide;
	}

	/**
	 * @inheritdoc
	 */
	public function displaysOnUnstructuredUi() {
		return !!$this->showHide;
	}

	/**
	 * @inheritdoc
	 */
	public function isFeatureAvailableOnStructuredUi() {
		return $this->isReplacedInStructuredUi ||
			parent::isFeatureAvailableOnStructuredUi();
	}

	/**
	 * Modifies the query to include the filter.  This is only called if the filter is
	 * in effect (taking into account the default).
	 *
	 * @param IDatabase $dbr Database, for addQuotes, makeList, and similar
	 * @param ChangesListSpecialPage $specialPage Current special page
	 * @param array &$tables Array of tables; see IDatabase::select $table
	 * @param array &$fields Array of fields; see IDatabase::select $vars
	 * @param array &$conds Array of conditions; see IDatabase::select $conds
	 * @param array &$query_options Array of query options; see IDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 */
	public function modifyQuery( IDatabase $dbr, ChangesListSpecialPage $specialPage,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds ) {

		if ( $this->queryCallable === null ) {
			return;
		}

		call_user_func_array(
			$this->queryCallable,
			[
				get_class( $specialPage ),
				$specialPage->getContext(),
				$dbr,
				&$tables,
				&$fields,
				&$conds,
				&$query_options,
				&$join_conds
			]
		);
	}

	/**
	 * @inheritdoc
	 */
	public function getJsData() {
		$output = parent::getJsData();

		$output['default'] = $this->defaultValue;

		return $output;
	}

	/**
	 * @inheritdoc
	 */
	public function isSelected( FormOptions $opts ) {
		return !$opts[ $this->getName() ] &&
			array_filter( $this->getSiblings(), function ( $sibling ) use ( $opts ) {
				return $opts[ $sibling->getName() ];
			} );
	}
}
