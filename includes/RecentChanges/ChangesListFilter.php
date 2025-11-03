<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use InvalidArgumentException;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\FormOptions;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;

/**
 * Represents a filter (used on ChangesListSpecialPage and descendants)
 *
 * @since 1.29
 * @ingroup RecentChanges
 * @author Matthew Flaschen
 */
abstract class ChangesListFilter {
	/**
	 * Filter name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * CSS class suffix used for attribution, e.g. 'bot'.
	 *
	 * In this example, if bot actions are included in the result set, this CSS class
	 * will then be included in all bot-flagged actions.
	 *
	 * @var string|null
	 */
	protected $cssClassSuffix;

	/**
	 * Callable that returns true if and only if a row is attributed to this filter
	 *
	 * @var callable|null
	 */
	protected $isRowApplicableCallable;

	/**
	 * Group.  ChangesListFilterGroup this belongs to
	 *
	 * @var ChangesListFilterGroup
	 */
	protected $group;

	/**
	 * i18n key of label for structured UI
	 *
	 * @var string
	 */
	protected $label;

	/**
	 * i18n key of description for structured UI
	 *
	 * @var string
	 */
	protected $description;

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

	/**
	 * Array of associative arrays with subset information
	 *
	 * @var array
	 */
	protected $subsetFilters = [];

	/**
	 * Priority integer.  Higher value means higher up in the group's filter list.
	 *
	 * @var int
	 */
	protected $priority;

	/**
	 * @var string
	 */
	protected $defaultHighlightColor;

	/** @var array|null */
	private $action = null;
	/** @var array|null */
	private $highlight = null;

	private const RESERVED_NAME_CHAR = '_';

	/**
	 * Creates a new filter with the specified configuration, and registers it to the
	 * specified group.
	 *
	 * It infers which UI (it can be either or both) to display the filter on based on
	 * which messages are provided.
	 *
	 * If 'label' is provided, it will be displayed on the structured UI.  Thus,
	 * 'label', 'description', and sub-class parameters are optional depending on which
	 * UI it's for.
	 *
	 * @param array $filterDefinition ChangesListFilter definition
	 * * $filterDefinition['name'] string Name of filter; use lowercase with no
	 *     punctuation
	 * * $filterDefinition['cssClassSuffix'] string CSS class suffix, used to mark
	 *     that a particular row belongs to this filter (when a row is included by the
	 *     filter) (optional)
	 * * $filterDefinition['isRowApplicableCallable'] callable Callable taking two parameters, the
	 *     IContextSource, and the RecentChange object for the row, and returning true if
	 *     the row is attributed to this filter.  The above CSS class will then be
	 *     automatically added (optional, required if cssClassSuffix is used).
	 * * $filterDefinition['action'] array Array of parameters to pass to
	 *     ChangesListQuery::filter(). The first element is the verb "require" or "exclude", the
	 *     second element is the module name, and the optional third element is the value.
	 *     Or an array of such actions. This supersedes isRowApplicableCallable.
	 * * $filterDefinition['highlight'] array An action in the same format as
	 *    $filterDefinition['action'] to be used for highlighting. Falls back to 'action'
	 *    if not set.
	 * * $filterDefinition['group'] ChangesListFilterGroup Group.  Filter group this
	 *     belongs to.
	 * * $filterDefinition['label'] string i18n key of label for structured UI.
	 * * $filterDefinition['description'] string i18n key of description for structured
	 *     UI.
	 * * $filterDefinition['priority'] int Priority integer.  Higher value means higher
	 *     up in the group's filter list.
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{name:string,cssClassSuffix?:string,isRowApplicableCallable?:callable,group:ChangesListFilterGroup,label:string,description:string,priority:int} $filterDefinition
	 */
	public function __construct( array $filterDefinition ) {
		if ( isset( $filterDefinition['group'] ) ) {
			$this->group = $filterDefinition['group'];
		} else {
			throw new InvalidArgumentException( 'You must use \'group\' to specify the ' .
				'ChangesListFilterGroup this filter belongs to' );
		}

		if ( str_contains( $filterDefinition['name'], self::RESERVED_NAME_CHAR ) ) {
			throw new InvalidArgumentException( 'Filter names may not contain \'' .
				self::RESERVED_NAME_CHAR .
				'\'.  Use the naming convention: \'lowercase\''
			);
		}

		if ( $this->group->getFilter( $filterDefinition['name'] ) ) {
			throw new InvalidArgumentException( 'Two filters in a group cannot have the ' .
				"same name: '{$filterDefinition['name']}'" );
		}

		$this->name = $filterDefinition['name'];

		if ( isset( $filterDefinition['cssClassSuffix'] ) ) {
			$this->cssClassSuffix = $filterDefinition['cssClassSuffix'];
			$this->isRowApplicableCallable = $filterDefinition['isRowApplicableCallable'] ?? null;
		}

		if ( isset( $filterDefinition['label'] ) ) {
			$this->label = $filterDefinition['label'];
			$this->description = $filterDefinition['description'];
		}

		$this->priority = $filterDefinition['priority'];
		$this->action = $filterDefinition['action'] ?? null;
		$this->highlight = $filterDefinition['highlight'] ?? null;

		$this->group->registerFilter( $this );
	}

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
	 * Marks that the current instance is (also) a superset of the filter passed in.
	 * This can be called more than once.
	 *
	 * This means that anything in the results for the other filter is also in the
	 * results for this one.
	 *
	 * @param ChangesListFilter $other The filter the current instance is a superset of
	 */
	public function setAsSupersetOf( ChangesListFilter $other ) {
		if ( $other->getGroup() !== $this->getGroup() ) {
			throw new InvalidArgumentException( 'Supersets can only be defined for filters in the same group' );
		}

		$this->subsetFilters[] = [
			// It's always the same group, but this makes the representation
			// more consistent with conflicts.
			'group' => $other->getGroup()->getName(),
			'filter' => $other->getName(),
		];
	}

	/**
	 * @return string Name, e.g. hideanons
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return ChangesListFilterGroup Group this belongs to
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @return string i18n key of label for structured UI
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return string i18n key of description for structured UI
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Checks whether the filter should display on the unstructured UI
	 *
	 * @return bool Whether to display
	 */
	abstract public function displaysOnUnstructuredUi();

	/**
	 * Checks whether the filter should display on the structured UI
	 * This refers to the exact filter.  See also isFeatureAvailableOnStructuredUi.
	 *
	 * @return bool Whether to display
	 */
	public function displaysOnStructuredUi() {
		return $this->label !== null;
	}

	/**
	 * Checks whether an equivalent feature for this filter is available on the
	 * structured UI.
	 *
	 * This can either be the exact filter, or a new filter that replaces it.
	 * @return bool
	 */
	public function isFeatureAvailableOnStructuredUi() {
		return $this->displaysOnStructuredUi();
	}

	/**
	 * @return int Priority.  Higher value means higher up in the group list
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Gets the CSS class
	 *
	 * @return string|null CSS class, or null if not defined
	 */
	public function getCssClass() {
		if ( $this->cssClassSuffix !== null ) {
			return ChangesList::CSS_CLASS_PREFIX . $this->cssClassSuffix;
		} else {
			return null;
		}
	}

	/**
	 * Add CSS class if needed
	 *
	 * @param IContextSource $ctx Context source
	 * @param RecentChange $rc Recent changes object
	 * @param array &$classes Non-associative array of CSS class names; appended to if needed
	 */
	public function applyCssClassIfNeeded( IContextSource $ctx, RecentChange $rc, array &$classes ) {
		if ( $this->isRowApplicableCallable === null ) {
			return;
		}

		if ( ( $this->isRowApplicableCallable )( $ctx, $rc ) ) {
			$classes[] = $this->getCssClass();
		}
	}

	/**
	 * Gets the JS data required by the front-end of the structured UI
	 *
	 * @return array Associative array Data required by the front-end.  messageKeys is
	 *  a special top-level value, with the value being an array of the message keys to
	 *  send to the client.
	 */
	public function getJsData() {
		$output = [
			'name' => $this->getName(),
			'label' => $this->getLabel(),
			'description' => $this->getDescription(),
			'cssClass' => $this->getCssClass(),
			'priority' => $this->priority,
			'subset' => $this->subsetFilters,
			'conflicts' => [],
			'defaultHighlightColor' => $this->defaultHighlightColor
		];

		$output['messageKeys'] = [
			$this->getLabel(),
			$this->getDescription(),
		];

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
	 * Checks whether this filter is selected in the provided options
	 *
	 * @param FormOptions $opts
	 * @return bool
	 */
	abstract public function isSelected( FormOptions $opts );

	/**
	 * @param FormOptions $opts Query parameters merged with defaults
	 * @param bool $isStructuredUI Whether the structured UI is currently enabled
	 * @return bool Whether this filter should be considered active
	 */
	abstract public function isActive( FormOptions $opts, $isStructuredUI );

	/**
	 * Get the action to apply to the ChangesListQuery for filtering. This may
	 * also return an array of actions.
	 *
	 * @see ChangesListQuery::applyAction
	 *
	 * @return array|null
	 */
	public function getAction(): ?array {
		return $this->action;
	}

	/**
	 * Get the action to apply to the ChangesListQuery for highlighting.
	 *
	 * @return array|null
	 */
	public function getHighlightAction(): ?array {
		return $this->highlight ?? $this->action;
	}

	/**
	 * Get groups conflicting with this filter
	 *
	 * @return ChangesListFilterGroup[]
	 */
	public function getConflictingGroups() {
		return array_column( $this->conflictingGroups, 'groupObject' );
	}

	/**
	 * Get filters conflicting with this filter
	 *
	 * @return ChangesListFilter[]
	 */
	public function getConflictingFilters() {
		return array_column( $this->conflictingFilters, 'filterObject' );
	}

	/**
	 * Check if the conflict with a group is currently "active"
	 *
	 * @param ChangesListFilterGroup $group
	 * @param FormOptions $opts
	 * @return bool
	 */
	public function activelyInConflictWithGroup( ChangesListFilterGroup $group, FormOptions $opts ) {
		if ( $group->anySelected( $opts ) && $this->isSelected( $opts ) ) {
			/** @var ChangesListFilter $siblingFilter */
			foreach ( $this->getSiblings() as $siblingFilter ) {
				if ( $siblingFilter->isSelected( $opts ) && !$siblingFilter->hasConflictWithGroup( $group ) ) {
					return false;
				}
			}
			return true;
		}
		return false;
	}

	private function hasConflictWithGroup( ChangesListFilterGroup $group ): bool {
		return in_array( $group, $this->getConflictingGroups() );
	}

	/**
	 * Check if the conflict with a filter is currently "active"
	 *
	 * @param ChangesListFilter $filter
	 * @param FormOptions $opts
	 * @return bool
	 */
	public function activelyInConflictWithFilter( ChangesListFilter $filter, FormOptions $opts ) {
		if ( $this->isSelected( $opts ) && $filter->isSelected( $opts ) ) {
			/** @var ChangesListFilter $siblingFilter */
			foreach ( $this->getSiblings() as $siblingFilter ) {
				if (
					$siblingFilter->isSelected( $opts ) &&
					!$siblingFilter->hasConflictWithFilter( $filter )
				) {
					return false;
				}
			}
			return true;
		}
		return false;
	}

	private function hasConflictWithFilter( ChangesListFilter $filter ): bool {
		return in_array( $filter, $this->getConflictingFilters() );
	}

	/**
	 * Get filters in the same group
	 *
	 * @return ChangesListFilter[]
	 */
	protected function getSiblings() {
		return array_filter(
			$this->getGroup()->getFilters(),
			function ( $filter ) {
				return $filter !== $this;
			}
		);
	}

	/**
	 * @param string $defaultHighlightColor
	 */
	public function setDefaultHighlightColor( $defaultHighlightColor ) {
		$this->defaultHighlightColor = $defaultHighlightColor;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangesListFilter::class, 'ChangesListFilter' );
