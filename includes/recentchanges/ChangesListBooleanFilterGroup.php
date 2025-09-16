<?php

namespace MediaWiki\RecentChanges;

use InvalidArgumentException;
use MediaWiki\Html\FormOptions;
use MediaWiki\SpecialPage\ChangesListSpecialPage;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * If the group is active, any unchecked filters will
 * translate to hide parameters in the URL.  E.g. if 'Human (not bot)' is checked,
 * but 'Bot' is unchecked, hidebots=1 will be sent.
 *
 * @since 1.29
 * @ingroup RecentChanges
 * @method ChangesListBooleanFilter[] getFilters()
 * @method ChangesListBooleanFilter|null getFilter( string $name )
 */
class ChangesListBooleanFilterGroup extends ChangesListFilterGroup {
	/**
	 * Type marker, used by JavaScript
	 */
	public const TYPE = 'send_unselected_if_any';

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
	 *    'priority' is optional for the filters.  Any filter that has priority unset
	 *     will be put to the bottom, in the order given.
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
		$groupDefinition['isFullCoverage'] = true;
		$groupDefinition['type'] = self::TYPE;

		parent::__construct( $groupDefinition );
	}

	/**
	 * @inheritDoc
	 */
	protected function createFilter( array $filterDefinition ) {
		return new ChangesListBooleanFilter( $filterDefinition );
	}

	/**
	 * @since 1.45
	 * @param array $defaultValue
	 */
	public function setDefault( $defaultValue ) {
		if ( !is_array( $defaultValue ) ) {
			throw new InvalidArgumentException(
				"Can't set the default of filter options group \"{$this->getName()}\"" .
				' to a value of type "' . gettype( $defaultValue ) . ': expected bool[]' );
		}
		foreach ( $defaultValue as $name => $value ) {
			if ( !is_bool( $value ) ) {
				throw new InvalidArgumentException(
					"Can't set the default of filter option \"{$this->getName()}/$name\"" .
					' to a value of type "' . gettype( $value ) . ': expected bool' );
			}
			$this->getFilter( $name )?->setDefault( $value );
		}
	}

	/**
	 * Registers a filter in this group
	 *
	 * @param ChangesListBooleanFilter $filter
	 * @suppress PhanParamSignaturePHPDocMismatchHasParamType,PhanParamSignatureMismatch
	 */
	public function registerFilter( ChangesListBooleanFilter $filter ) {
		$this->filters[$filter->getName()] = $filter;
	}

	/**
	 * @inheritDoc
	 */
	public function modifyQuery( IReadableDatabase $dbr, ChangesListSpecialPage $specialPage,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds,
		FormOptions $opts, $isStructuredFiltersEnabled
	) {
		foreach ( $this->getFilters() as $filter ) {
			if ( $filter->isActive( $opts, $isStructuredFiltersEnabled ) ) {
				$filter->modifyQuery( $dbr, $specialPage, $tables, $fields, $conds,
					$query_options, $join_conds );
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function addOptions( FormOptions $opts, $allowDefaults, $isStructuredFiltersEnabled ) {
		foreach ( $this->getFilters() as $filter ) {
			$defaultValue = $allowDefaults ? $filter->getDefault( $isStructuredFiltersEnabled ) : false;
			$opts->add( $filter->getName(), $defaultValue );
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangesListBooleanFilterGroup::class, 'ChangesListBooleanFilterGroup' );
