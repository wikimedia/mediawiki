<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use InvalidArgumentException;
use MediaWiki\Html\FormOptions;
use MediaWiki\SpecialPage\ChangesListSpecialPage;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Represents a hide-based boolean filter (used on ChangesListSpecialPage and descendants)
 *
 * @since 1.29
 * @ingroup RecentChanges
 * @author Matthew Flaschen
 */
class ChangesListBooleanFilter extends ChangesListFilter {
	/**
	 * Main unstructured UI i18n key
	 *
	 * @var string
	 */
	protected $showHide;

	/**
	 * Whether there is a feature designed to replace this filter available on the
	 * structured UI
	 *
	 * @var bool
	 */
	protected $isReplacedInStructuredUi;

	/**
	 * Default
	 *
	 * @var bool
	 */
	protected $defaultValue;

	/**
	 * Callable used to do the actual query modification; see constructor
	 *
	 * @var callable
	 */
	protected $queryCallable;

	/**
	 * Value that defined when this filter is considered active
	 *
	 * @var bool
	 */
	protected $activeValue;

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
	 * * $filterDefinition['name'] string Name.  Used as URL parameter.
	 * * $filterDefinition['group'] ChangesListFilterGroup Group.  Filter group this
	 *     belongs to.
	 * * $filterDefinition['label'] string i18n key of label for structured UI.
	 * * $filterDefinition['description'] string i18n key of description for structured
	 *     UI.
	 * * $filterDefinition['showHide'] string Main i18n key used for unstructured UI.
	 * * $filterDefinition['isReplacedInStructuredUi'] bool Whether there is an
	 *     equivalent feature available in the structured UI; this is optional, defaulting
	 *     to true.  It does not need to be set if the exact same filter is simply visible
	 *     on both.
	 * * $filterDefinition['default'] bool Default
	 * * $filterDefinition['activeValue'] bool This filter is considered active when
	 *     its value is equal to its activeValue. Default is true.
	 * * $filterDefinition['priority'] int Priority integer.  Higher value means higher
	 *     up in the group's filter list.
	 * * $filterDefinition['queryCallable'] callable Callable accepting parameters, used
	 *     to implement filter's DB query modification.  Required, except for legacy
	 *     filters that still use the query hooks directly.  Callback parameters:
	 * 	* string $specialPageClassName Class name of current special page
	 * 	* IContextSource $context Context, for e.g. user
	 * 	* IDatabase $dbr Database, for addQuotes, makeList, and similar
	 * 	* array &$tables Array of tables; see IDatabase::select $table
	 * 	* array &$fields Array of fields; see IDatabase::select $vars
	 * 	* array &$conds Array of conditions; see IDatabase::select $conds
	 * 	* array &$query_options Array of query options; see IDatabase::select $options
	 * 	* array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 */
	public function __construct( $filterDefinition ) {
		parent::__construct( $filterDefinition );

		if ( isset( $filterDefinition['showHide'] ) ) {
			$this->showHide = $filterDefinition['showHide'];
		}

		$this->isReplacedInStructuredUi = $filterDefinition['isReplacedInStructuredUi'] ?? false;

		if ( isset( $filterDefinition['default'] ) ) {
			$this->setDefault( $filterDefinition['default'] );
		} else {
			throw new InvalidArgumentException( 'You must set a default' );
		}

		if ( isset( $filterDefinition['queryCallable'] ) ) {
			$this->queryCallable = $filterDefinition['queryCallable'];
		}

		$this->activeValue = $filterDefinition['activeValue'] ?? true;
	}

	/**
	 * Get the default value
	 *
	 * @param bool $structuredUI Are we currently showing the structured UI
	 * @return bool|null Default value
	 */
	public function getDefault( $structuredUI = false ) {
		return $this->isReplacedInStructuredUi && $structuredUI ?
			!$this->activeValue :
			$this->defaultValue;
	}

	/**
	 * Sets default.  It must be a boolean.
	 *
	 * It will be coerced to boolean.
	 *
	 * @param bool $defaultValue
	 */
	public function setDefault( $defaultValue ) {
		$this->defaultValue = (bool)$defaultValue;
	}

	/**
	 * @return string Main i18n key for unstructured UI
	 */
	public function getShowHide() {
		return $this->showHide;
	}

	/**
	 * @inheritDoc
	 */
	public function displaysOnUnstructuredUi() {
		return (bool)$this->showHide;
	}

	/**
	 * @inheritDoc
	 */
	public function isFeatureAvailableOnStructuredUi() {
		return $this->isReplacedInStructuredUi ||
			parent::isFeatureAvailableOnStructuredUi();
	}

	/**
	 * Modifies the query to include the filter.  This is only called if the filter is
	 * in effect (taking into account the default).
	 *
	 * @param IReadableDatabase $dbr Database, for addQuotes, makeList, and similar
	 * @param ChangesListSpecialPage $specialPage Current special page
	 * @param array &$tables Array of tables; see IDatabase::select $table
	 * @param array &$fields Array of fields; see IDatabase::select $vars
	 * @param array &$conds Array of conditions; see IDatabase::select $conds
	 * @param array &$query_options Array of query options; see IDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 */
	public function modifyQuery( IReadableDatabase $dbr, ChangesListSpecialPage $specialPage,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds
	) {
		if ( $this->queryCallable === null ) {
			return;
		}

		( $this->queryCallable )(
			get_class( $specialPage ),
			$specialPage->getContext(),
			$dbr,
			$tables,
			$fields,
			$conds,
			$query_options,
			$join_conds
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getJsData() {
		$output = parent::getJsData();

		$output['default'] = $this->defaultValue;

		return $output;
	}

	/**
	 * @inheritDoc
	 */
	public function isSelected( FormOptions $opts ) {
		return !$opts[ $this->getName() ] &&
			array_filter(
				$this->getSiblings(),
				static function ( ChangesListBooleanFilter $sibling ) use ( $opts ) {
					return $opts[ $sibling->getName() ];
				}
			);
	}

	/**
	 * @inheritDoc
	 */
	public function isActive( FormOptions $opts, $isStructuredUI ) {
		if ( $this->isReplacedInStructuredUi && $isStructuredUI ) {
			return false;
		}

		return $opts[ $this->getName() ] === $this->activeValue;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangesListBooleanFilter::class, 'ChangesListBooleanFilter' );
