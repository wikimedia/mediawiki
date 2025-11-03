<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use InvalidArgumentException;
use MediaWiki\Html\FormOptions;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;
use MediaWiki\SpecialPage\ChangesListSpecialPage;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Represents a filter group (used on ChangesListSpecialPage and descendants)
 *
 * @todo Might want to make a super-class or trait to share behavior (especially re
 * conflicts) between ChangesListFilter and ChangesListFilterGroup.
 * What to call it.  FilterStructure?  That would also let me make
 * setUnidirectionalConflict protected.
 *
 * @since 1.29
 * @ingroup RecentChanges
 * @author Matthew Flaschen
 * @method registerFilter($filter)
 */
abstract class ChangesListFilterGroup {
	/**
	 * Name (internal identifier)
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * i18n key for title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * i18n key for header of What's This?
	 *
	 * @var string|null
	 */
	protected $whatsThisHeader;

	/**
	 * i18n key for body of What's This?
	 *
	 * @var string|null
	 */
	protected $whatsThisBody;

	/**
	 * URL of What's This? link
	 *
	 * @var string|null
	 */
	protected $whatsThisUrl;

	/**
	 * i18n key for What's This? link
	 *
	 * @var string|null
	 */
	protected $whatsThisLinkText;

	/**
	 * Type, from a TYPE constant of a subclass
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Priority integer.  Higher values means higher up in the
	 * group list.
	 *
	 * @var int
	 */
	protected $priority;

	/**
	 * Associative array of filters, as ChangesListFilter objects, with filter name as key
	 *
	 * @var ChangesListFilter[]
	 */
	protected $filters;

	/**
	 * Whether this group is full coverage.  This means that checking every item in the
	 * group means no changes list (e.g. RecentChanges) entries are filtered out.
	 *
	 * @var bool
	 */
	protected $isFullCoverage;

	/**
	 * Array of associative arrays with conflict information.  See
	 * setUnidirectionalConflict
	 *
	 * @var array
	 */
	protected $conflictingGroups = [];

	/**
	 * Array of associative arrays with conflict information.  See
	 * setUnidirectionalConflict
	 *
	 * @var array
	 */
	protected $conflictingFilters = [];

	private const DEFAULT_PRIORITY = -100;

	private const RESERVED_NAME_CHAR = '_';

	/**
	 * Create a new filter group with the specified configuration
	 *
	 * @param array $groupDefinition Configuration of group
	 * * $groupDefinition['name'] string Group name; use camelCase with no punctuation
	 * * $groupDefinition['title'] string i18n key for title (optional, can be omitted
	 *     only if none of the filters in the group display in the structured UI)
	 * * $groupDefinition['type'] string A type constant from a subclass of this one
	 * * $groupDefinition['priority'] int Priority integer.  Higher value means higher
	 *     up in the group list (optional, defaults to -100).
	 * * $groupDefinition['filters'] array Numeric array of filter definitions, each of which
	 *     is an associative array to be passed to the filter constructor.  However,
	 *     'priority' is optional for the filters.  Any filter that has priority unset
	 *     will be put to the bottom, in the order given.
	 * * $groupDefinition['isFullCoverage'] bool Whether the group is full coverage;
	 *     if true, this means that checking every item in the group means no
	 *     changes list entries are filtered out.
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
		if ( str_contains( $groupDefinition['name'], self::RESERVED_NAME_CHAR ) ) {
			throw new InvalidArgumentException( 'Group names may not contain \'' .
				self::RESERVED_NAME_CHAR .
				'\'.  Use the naming convention: \'camelCase\''
			);
		}

		$this->name = $groupDefinition['name'];

		if ( isset( $groupDefinition['title'] ) ) {
			$this->title = $groupDefinition['title'];
		}

		if ( isset( $groupDefinition['whatsThisHeader'] ) ) {
			$this->whatsThisHeader = $groupDefinition['whatsThisHeader'];
			$this->whatsThisBody = $groupDefinition['whatsThisBody'];
			$this->whatsThisUrl = $groupDefinition['whatsThisUrl'];
			$this->whatsThisLinkText = $groupDefinition['whatsThisLinkText'];
		}

		$this->type = $groupDefinition['type'];
		$this->priority = $groupDefinition['priority'] ?? self::DEFAULT_PRIORITY;

		$this->isFullCoverage = $groupDefinition['isFullCoverage'];

		$this->filters = [];
		$lowestSpecifiedPriority = -1;
		foreach ( $groupDefinition['filters'] as $filterDefinition ) {
			if ( isset( $filterDefinition['priority'] ) ) {
				$lowestSpecifiedPriority = min( $lowestSpecifiedPriority, $filterDefinition['priority'] );
			}
		}

		// Convenience feature: If you specify a group (and its filters) all in
		// one place, you don't have to specify priority.  You can just put them
		// in order.  However, if you later add one (e.g. an extension adds a filter
		// to a core-defined group), you need to specify it.
		$autoFillPriority = $lowestSpecifiedPriority - 1;
		foreach ( $groupDefinition['filters'] as $filterDefinition ) {
			if ( !isset( $filterDefinition['priority'] ) ) {
				$filterDefinition['priority'] = $autoFillPriority;
				$autoFillPriority--;
			}
			$filterDefinition['group'] = $this;

			$filter = $this->createFilter( $filterDefinition );
			$this->registerFilter( $filter );
		}
	}

	/**
	 * Creates a filter of the appropriate type for this group, from the definition
	 *
	 * @param array $filterDefinition
	 * @return ChangesListFilter Filter
	 */
	abstract protected function createFilter( array $filterDefinition );

	/**
	 * Set the default for this filter group.
	 *
	 * @since 1.45
	 * @param bool[]|string $defaultValue
	 */
	abstract public function setDefault( $defaultValue );

	/**
	 * Marks that the given ChangesListFilterGroup or ChangesListFilter conflicts with this object.
	 *
	 * WARNING: This means there is a conflict when both things are *shown*
	 * (not filtered out), even for the hide-based filters.  So e.g. conflicting with
	 * 'hideanons' means there is a conflict if only anonymous users are *shown*.
	 *
	 * @param ChangesListFilterGroup|ChangesListFilter $other
	 * @param string $globalKey i18n key for top-level conflict message
	 * @param string $forwardKey i18n key for conflict message in this
	 *  direction (when in UI context of $this object)
	 * @param string $backwardKey i18n key for conflict message in reverse
	 *  direction (when in UI context of $other object)
	 */
	public function conflictsWith( $other, string $globalKey, string $forwardKey, string $backwardKey ) {
		$this->setUnidirectionalConflict(
			$other,
			$globalKey,
			$forwardKey
		);

		$other->setUnidirectionalConflict(
			$this,
			$globalKey,
			$backwardKey
		);
	}

	/**
	 * Marks that the given ChangesListFilterGroup or ChangesListFilter conflicts with
	 * this object.
	 *
	 * Internal use ONLY.
	 *
	 * @param ChangesListFilterGroup|ChangesListFilter $other
	 * @param string $globalDescription i18n key for top-level conflict message
	 * @param string $contextDescription i18n key for conflict message in this
	 *  direction (when in UI context of $this object)
	 */
	public function setUnidirectionalConflict( $other, $globalDescription, $contextDescription ) {
		if ( $other instanceof ChangesListFilterGroup ) {
			$this->conflictingGroups[] = [
				'group' => $other->getName(),
				'groupObject' => $other,
				'globalDescription' => $globalDescription,
				'contextDescription' => $contextDescription,
			];
		} elseif ( $other instanceof ChangesListFilter ) {
			$this->conflictingFilters[] = [
				'group' => $other->getGroup()->getName(),
				'filter' => $other->getName(),
				'filterObject' => $other,
				'globalDescription' => $globalDescription,
				'contextDescription' => $contextDescription,
			];
		} else {
			throw new InvalidArgumentException(
				'You can only pass in a ChangesListFilterGroup or a ChangesListFilter'
			);
		}
	}

	/**
	 * @return string Internal name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string i18n key for title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return string Type (TYPE constant from a subclass)
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return int Priority.  Higher means higher in the group list
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @return ChangesListFilter[] Associative array of ChangesListFilter objects, with
	 *   filter name as key
	 */
	public function getFilters() {
		return $this->filters;
	}

	/**
	 * Get filter by name
	 *
	 * @param string $name Filter name
	 * @return ChangesListFilter|null Specified filter, or null if it is not registered
	 */
	public function getFilter( $name ) {
		return $this->filters[$name] ?? null;
	}

	/**
	 * Gets the JS data in the format required by the front-end of the structured UI
	 *
	 * @return array|null Associative array, or null if there are no filters that
	 *  display in the structured UI.  messageKeys is a special top-level value, with
	 *  the value being an array of the message keys to send to the client.
	 */
	public function getJsData() {
		$output = [
			'name' => $this->name,
			'type' => $this->type,
			'fullCoverage' => $this->isFullCoverage,
			'filters' => [],
			'priority' => $this->priority,
			'conflicts' => [],
			'messageKeys' => [ $this->title ]
		];

		if ( $this->whatsThisHeader !== null ) {
			$output['whatsThisHeader'] = $this->whatsThisHeader;
			$output['whatsThisBody'] = $this->whatsThisBody;
			$output['whatsThisUrl'] = $this->whatsThisUrl;
			$output['whatsThisLinkText'] = $this->whatsThisLinkText;

			array_push(
				$output['messageKeys'],
				$output['whatsThisHeader'],
				$output['whatsThisBody'],
				$output['whatsThisLinkText']
			);
		}

		usort( $this->filters, static function ( ChangesListFilter $a, ChangesListFilter $b ) {
			return $b->getPriority() <=> $a->getPriority();
		} );

		foreach ( $this->filters as $filter ) {
			if ( $filter->displaysOnStructuredUi() ) {
				$filterData = $filter->getJsData();
				$output['messageKeys'] = array_merge(
					$output['messageKeys'],
					$filterData['messageKeys']
				);
				unset( $filterData['messageKeys'] );
				$output['filters'][] = $filterData;
			}
		}

		if ( count( $output['filters'] ) === 0 ) {
			return null;
		}

		$output['title'] = $this->title;

		$conflicts = array_merge(
			$this->conflictingGroups,
			$this->conflictingFilters
		);

		foreach ( $conflicts as $conflictInfo ) {
			unset( $conflictInfo['filterObject'] );
			unset( $conflictInfo['groupObject'] );
			$output['conflicts'][] = $conflictInfo;
			array_push(
				$output['messageKeys'],
				$conflictInfo['globalDescription'],
				$conflictInfo['contextDescription']
			);
		}

		return $output;
	}

	/**
	 * Get groups conflicting with this filter group
	 *
	 * @return ChangesListFilterGroup[]
	 */
	public function getConflictingGroups() {
		return array_column( $this->conflictingGroups, 'groupObject' );
	}

	/**
	 * Get filters conflicting with this filter group
	 *
	 * @return ChangesListFilter[]
	 */
	public function getConflictingFilters() {
		return array_column( $this->conflictingFilters, 'filterObject' );
	}

	/**
	 * Check if any filter in this group is selected
	 *
	 * @param FormOptions $opts
	 * @return bool
	 */
	public function anySelected( FormOptions $opts ) {
		return (bool)count( array_filter(
			$this->getFilters(),
			static function ( ChangesListFilter $filter ) use ( $opts ) {
				return $filter->isSelected( $opts );
			}
		) );
	}

	/**
	 * Modifies the query to include the filter group (legacy interface).
	 *
	 * The modification is only done if the filter group is in effect.  This means that
	 * one or more valid and allowed filters were selected.
	 *
	 * @param IReadableDatabase $dbr Database, for addQuotes, makeList, and similar
	 * @param ChangesListSpecialPage $specialPage Current special page
	 * @param array &$tables Array of tables; see IDatabase::select $table
	 * @param array &$fields Array of fields; see IDatabase::select $vars
	 * @param array &$conds Array of conditions; see IDatabase::select $conds
	 * @param array &$query_options Array of query options; see IDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 * @param FormOptions $opts Wrapper for the current request options and their defaults
	 * @param bool $isStructuredFiltersEnabled True if the Structured UI is currently enabled
	 */
	public function modifyQuery( IReadableDatabase $dbr, ChangesListSpecialPage $specialPage,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds,
		FormOptions $opts, $isStructuredFiltersEnabled
	) {
	}

	/**
	 * Modifies the query to include the filter group.
	 *
	 * @param ChangesListQuery $query
	 * @param FormOptions $opts
	 * @param bool $isStructuredFiltersEnabled
	 */
	public function modifyChangesListQuery(
		ChangesListQuery $query,
		FormOptions $opts,
		$isStructuredFiltersEnabled
	) {
		foreach ( $this->getFilters() as $filter ) {
			$action = $filter->getAction();
			if ( $action !== null ) {
				if ( $filter->isActive( $opts, $isStructuredFiltersEnabled ) ) {
					if ( is_array( $action[0] ) ) {
						foreach ( $action as $singleAction ) {
							// @phan-suppress-next-line PhanParamTooFewUnpack
							$query->applyAction( ...$singleAction );
						}
					} else {
						// @phan-suppress-next-line PhanParamTooFewUnpack
						$query->applyAction( ...$action );
					}
				}
				$highlightAction = $filter->getHighlightAction();
				if ( $filter->getCssClass() !== null && $highlightAction ) {
					$name = $this->getName() . '/' . $filter->getName();
					if ( is_array( $highlightAction[0] ) ) {
						foreach ( $highlightAction as $singleAction ) {
							// @phan-suppress-next-line PhanParamTooFewUnpack
							$query->highlight( $name, ...$singleAction );
						}
					} else {
						// @phan-suppress-next-line PhanParamTooFewUnpack
						$query->highlight( $name, ...$highlightAction );
					}
				}
			}
		}
	}

	/**
	 * Add all the options represented by this filter group to $opts
	 *
	 * @param FormOptions $opts
	 * @param bool $allowDefaults
	 * @param bool $isStructuredFiltersEnabled
	 */
	abstract public function addOptions( FormOptions $opts, $allowDefaults,
		$isStructuredFiltersEnabled );
}

/** @deprecated class alias since 1.44 */
class_alias( ChangesListFilterGroup::class, 'ChangesListFilterGroup' );
