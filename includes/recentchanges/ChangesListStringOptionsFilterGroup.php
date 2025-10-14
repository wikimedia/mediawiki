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
 * Represents a filter group with multiple string options. They are passed to the server as
 * a single form parameter separated by a delimiter.  The parameter name is the
 * group name.  E.g. groupname=opt1;opt2 .
 *
 * If all options are selected they are replaced by the term "all".
 *
 * There is also a single DB query modification for the whole group.
 *
 * @since 1.29
 * @ingroup RecentChanges
 * @author Matthew Flaschen
 */
class ChangesListStringOptionsFilterGroup extends ChangesListFilterGroup {
	/**
	 * Type marker, used by JavaScript
	 */
	public const TYPE = 'string_options';

	/**
	 * Delimiter
	 */
	public const SEPARATOR = ';';

	/**
	 * Signifies that all options in the group are selected.
	 */
	public const ALL = 'all';

	/**
	 * Signifies that no options in the group are selected, meaning the group has no effect.
	 *
	 * For full-coverage groups, this is the same as ALL if all filters are allowed.
	 * For others, it is not.
	 */
	public const NONE = '';

	/**
	 * Default parameter value
	 *
	 * @var string
	 */
	protected $defaultValue;

	/**
	 * Callable used to do the actual query modification; see constructor
	 *
	 * @var callable|null
	 */
	protected $queryCallable;

	/**
	 * Create a new filter group with the specified configuration
	 *
	 * @param array $groupDefinition Configuration of group
	 * * $groupDefinition['name'] string Group name
	 * * $groupDefinition['title'] string i18n key for title (optional, can be omitted
	 *     only if none of the filters in the group display in the structured UI)
	 * * $groupDefinition['priority'] int Priority integer.  Higher means higher in the
	 *     group list.
	 * * $groupDefinition['filters'] array Numeric array of filter definitions, each of which
	 *     is an associative array to be passed to the filter constructor.  However,
	 *     'priority' is optional for the filters.  Any filter that has priority unset
	 *     will be put to the bottom, in the order given.
	 * * $groupDefinition['default'] string Default for group.
	 * * $groupDefinition['isFullCoverage'] bool Whether the group is full coverage;
	 *     if true, this means that checking every item in the group means no
	 *     changes list entries are filtered out.
	 * * $groupDefinition['queryCallable'] callable Callable accepting parameters:
	 * 	* string $specialPageClassName Class name of current special page
	 * 	* IContextSource $context Context, for e.g. user
	 * 	* IDatabase $dbr Database, for addQuotes, makeList, and similar
	 * 	* array &$tables Array of tables; see IDatabase::select $table
	 * 	* array &$fields Array of fields; see IDatabase::select $vars
	 * 	* array &$conds Array of conditions; see IDatabase::select $conds
	 * 	* array &$query_options Array of query options; see IDatabase::select $options
	 * 	* array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 * 	* array $selectedValues The allowed and requested values, lower-cased and sorted
	 * * $groupDefinition['whatsThisHeader'] string i18n key for header of "What's
	 *     This" popup (optional).
	 * * $groupDefinition['whatsThisBody'] string i18n key for body of "What's This"
	 *     popup (optional).
	 * * $groupDefinition['whatsThisUrl'] string URL for main link of "What's This"
	 *     popup (optional).
	 * * $groupDefinition['whatsThisLinkText'] string i18n key of text for main link of
	 *     "What's This" popup (optional).
	 */
	public function __construct( array $groupDefinition ) {
		if ( !isset( $groupDefinition['isFullCoverage'] ) ) {
			throw new InvalidArgumentException( 'You must specify isFullCoverage' );
		}

		$groupDefinition['type'] = self::TYPE;

		parent::__construct( $groupDefinition );

		$this->queryCallable = $groupDefinition['queryCallable'] ?? null;

		if ( isset( $groupDefinition['default'] ) ) {
			$this->setDefault( $groupDefinition['default'] );
		} else {
			throw new InvalidArgumentException( 'You must specify a default' );
		}
	}

	/**
	 * Sets default of filter group.
	 *
	 * @param string $defaultValue
	 */
	public function setDefault( $defaultValue ) {
		if ( !is_string( $defaultValue ) ) {
			throw new InvalidArgumentException(
				"Can't set the default of filter options group \"{$this->getName()}\"" .
				' to a value of type "' . gettype( $defaultValue ) . ': string expected' );
		}
		$this->defaultValue = $defaultValue;
	}

	/**
	 * Gets default of filter group
	 *
	 * @return string
	 */
	public function getDefault() {
		return $this->defaultValue;
	}

	/**
	 * @inheritDoc
	 */
	protected function createFilter( array $filterDefinition ) {
		return new ChangesListStringOptionsFilter( $filterDefinition );
	}

	/**
	 * Registers a filter in this group
	 *
	 * @param ChangesListStringOptionsFilter $filter
	 * @suppress PhanParamSignaturePHPDocMismatchHasParamType,PhanParamSignatureMismatch
	 */
	public function registerFilter( ChangesListStringOptionsFilter $filter ) {
		$this->filters[$filter->getName()] = $filter;
	}

	/**
	 * @inheritDoc
	 */
	public function modifyQuery( IReadableDatabase $dbr, ChangesListSpecialPage $specialPage,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds,
		FormOptions $opts, $isStructuredFiltersEnabled
	) {
		// STRING_OPTIONS filter groups are exclusively active on Structured UI
		if ( !$isStructuredFiltersEnabled ) {
			return;
		}
		if ( !$this->queryCallable ) {
			return;
		}

		$value = $opts[ $this->getName() ];
		$allowedFilterNames = [];
		foreach ( $this->filters as $filter ) {
			$allowedFilterNames[] = $filter->getName();
		}

		if ( $value === self::ALL ) {
			$selectedValues = $allowedFilterNames;
		} else {
			$selectedValues = explode( self::SEPARATOR, strtolower( $value ) );

			// remove values that are not recognized or not currently allowed
			$selectedValues = array_intersect(
				$selectedValues,
				$allowedFilterNames
			);
		}

		// If there are now no values, because all are disallowed or invalid (also,
		// the user may not have selected any), this is a no-op.

		// If everything is unchecked, the group always has no effect, regardless
		// of full-coverage.
		if ( count( $selectedValues ) === 0 ) {
			return;
		}

		sort( $selectedValues );

		( $this->queryCallable )(
			get_class( $specialPage ),
			$specialPage->getContext(),
			$dbr,
			$tables,
			$fields,
			$conds,
			$query_options,
			$join_conds,
			$selectedValues
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getJsData() {
		$output = parent::getJsData();

		$output['separator'] = self::SEPARATOR;
		$output['default'] = $this->getDefault();

		return $output;
	}

	/**
	 * @inheritDoc
	 */
	public function addOptions( FormOptions $opts, $allowDefaults, $isStructuredFiltersEnabled ) {
		$opts->add( $this->getName(), $allowDefaults ? $this->getDefault() : '' );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangesListStringOptionsFilterGroup::class, 'ChangesListStringOptionsFilterGroup' );
