<?php
/**
 * Special page which uses a ChangesList to show query results.
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
 * @ingroup SpecialPage
 */
use MediaWiki\Logger\LoggerFactory;
use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * Special page which uses a ChangesList to show query results.
 * @todo Way too many public functions, most of them should be protected
 *
 * @ingroup SpecialPage
 */
abstract class ChangesListSpecialPage extends SpecialPage {
	/** @var string */
	protected $rcSubpage;

	/** @var FormOptions */
	protected $rcOptions;

	/** @var array */
	protected $customFilters;

	// Order of both groups and filters is significant; first is top-most priority,
	// descending from there.
	// 'showHideSuffix' is a shortcut to and avoid spelling out
	// details specific to subclasses here.
	/**
	 * Definition information for the filters and their groups
	 *
	 * The value is $groupDefinition, a parameter to the ChangesListFilterGroup constructor.
	 * However, priority is dynamically added for the core groups, to ease maintenance.
	 *
	 * Groups are displayed to the user in the structured UI.  However, if necessary,
	 * all of the filters in a group can be configured to only display on the
	 * unstuctured UI, in which case you don't need a group title.  This is done in
	 * getFilterGroupDefinitionFromLegacyCustomFilters, for example.
	 *
	 * @var array $filterGroupDefinitions
	 */
	private $filterGroupDefinitions;

	// Same format as filterGroupDefinitions, but for a single group (reviewStatus)
	// that is registered conditionally.
	private $reviewStatusFilterGroupDefinition;

	// Single filter registered conditionally
	private $hideCategorizationFilterDefinition;

	/**
	 * Filter groups, and their contained filters
	 * This is an associative array (with group name as key) of ChangesListFilterGroup objects.
	 *
	 * @var array $filterGroups
	 */
	protected $filterGroups = [];

	public function __construct( $name, $restriction ) {
		parent::__construct( $name, $restriction );

		$this->filterGroupDefinitions = [
			[
				'name' => 'registration',
				'title' => 'rcfilters-filtergroup-registration',
				'class' => ChangesListBooleanFilterGroup::class,
				'filters' => [
					[
						'name' => 'hideliu',
						'label' => 'rcfilters-filter-registered-label',
						'description' => 'rcfilters-filter-registered-description',
						// rcshowhideliu-show, rcshowhideliu-hide,
						// wlshowhideliu
						'showHideSuffix' => 'showhideliu',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_user = 0';
						},
						'cssClassSuffix' => 'liu',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_user' );
						},

					],
					[
						'name' => 'hideanons',
						'label' => 'rcfilters-filter-unregistered-label',
						'description' => 'rcfilters-filter-unregistered-description',
						// rcshowhideanons-show, rcshowhideanons-hide,
						// wlshowhideanons
						'showHideSuffix' => 'showhideanons',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_user != 0';
						},
						'cssClassSuffix' => 'anon',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return !$rc->getAttribute( 'rc_user' );
						},
					]
				],
			],

			[
				'name' => 'userExpLevel',
				'title' => 'rcfilters-filtergroup-userExpLevel',
				'class' => ChangesListStringOptionsFilterGroup::class,
				// Excludes unregistered users
				'isFullCoverage' => false,
				'filters' => [
					[
						'name' => 'newcomer',
						'label' => 'rcfilters-filter-user-experience-level-newcomer-label',
						'description' => 'rcfilters-filter-user-experience-level-newcomer-description',
						'cssClassSuffix' => 'user-newcomer',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							$performer = $rc->getPerformer();
							return $performer && $performer->isLoggedIn() &&
								$performer->getExperienceLevel() === 'newcomer';
						}
					],
					[
						'name' => 'learner',
						'label' => 'rcfilters-filter-user-experience-level-learner-label',
						'description' => 'rcfilters-filter-user-experience-level-learner-description',
						'cssClassSuffix' => 'user-learner',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							$performer = $rc->getPerformer();
							return $performer && $performer->isLoggedIn() &&
								$performer->getExperienceLevel() === 'learner';
						},
					],
					[
						'name' => 'experienced',
						'label' => 'rcfilters-filter-user-experience-level-experienced-label',
						'description' => 'rcfilters-filter-user-experience-level-experienced-description',
						'cssClassSuffix' => 'user-experienced',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							$performer = $rc->getPerformer();
							return $performer && $performer->isLoggedIn() &&
								$performer->getExperienceLevel() === 'experienced';
						},
					]
				],
				'default' => ChangesListStringOptionsFilterGroup::NONE,
				'queryCallable' => [ $this, 'filterOnUserExperienceLevel' ],
			],

			[
				'name' => 'authorship',
				'title' => 'rcfilters-filtergroup-authorship',
				'class' => ChangesListBooleanFilterGroup::class,
				'filters' => [
					[
						'name' => 'hidemyself',
						'label' => 'rcfilters-filter-editsbyself-label',
						'description' => 'rcfilters-filter-editsbyself-description',
						// rcshowhidemine-show, rcshowhidemine-hide,
						// wlshowhidemine
						'showHideSuffix' => 'showhidemine',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$user = $ctx->getUser();
							$conds[] = 'rc_user_text != ' . $dbr->addQuotes( $user->getName() );
						},
						'cssClassSuffix' => 'self',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $ctx->getUser()->equals( $rc->getPerformer() );
						},
					],
					[
						'name' => 'hidebyothers',
						'label' => 'rcfilters-filter-editsbyother-label',
						'description' => 'rcfilters-filter-editsbyother-description',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$user = $ctx->getUser();
							$conds[] = 'rc_user_text = ' . $dbr->addQuotes( $user->getName() );
						},
						'cssClassSuffix' => 'others',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return !$ctx->getUser()->equals( $rc->getPerformer() );
						},
					]
				]
			],

			[
				'name' => 'automated',
				'title' => 'rcfilters-filtergroup-automated',
				'class' => ChangesListBooleanFilterGroup::class,
				'filters' => [
					[
						'name' => 'hidebots',
						'label' => 'rcfilters-filter-bots-label',
						'description' => 'rcfilters-filter-bots-description',
						// rcshowhidebots-show, rcshowhidebots-hide,
						// wlshowhidebots
						'showHideSuffix' => 'showhidebots',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_bot = 0';
						},
						'cssClassSuffix' => 'bot',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_bot' );
						},
					],
					[
						'name' => 'hidehumans',
						'label' => 'rcfilters-filter-humans-label',
						'description' => 'rcfilters-filter-humans-description',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_bot = 1';
						},
						'cssClassSuffix' => 'human',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return !$rc->getAttribute( 'rc_bot' );
						},
					]
				]
			],

			// reviewStatus (conditional)

			[
				'name' => 'significance',
				'title' => 'rcfilters-filtergroup-significance',
				'class' => ChangesListBooleanFilterGroup::class,
				'priority' => -6,
				'filters' => [
					[
						'name' => 'hideminor',
						'label' => 'rcfilters-filter-minor-label',
						'description' => 'rcfilters-filter-minor-description',
						// rcshowhideminor-show, rcshowhideminor-hide,
						// wlshowhideminor
						'showHideSuffix' => 'showhideminor',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_minor = 0';
						},
						'cssClassSuffix' => 'minor',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_minor' );
						}
					],
					[
						'name' => 'hidemajor',
						'label' => 'rcfilters-filter-major-label',
						'description' => 'rcfilters-filter-major-description',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_minor = 1';
						},
						'cssClassSuffix' => 'major',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return !$rc->getAttribute( 'rc_minor' );
						}
					]
				]
			],

			// With extensions, there can be change types that will not be hidden by any of these.
			[
				'name' => 'changeType',
				'title' => 'rcfilters-filtergroup-changetype',
				'class' => ChangesListBooleanFilterGroup::class,
				'filters' => [
					[
						'name' => 'hidepageedits',
						'label' => 'rcfilters-filter-pageedits-label',
						'description' => 'rcfilters-filter-pageedits-description',
						'default' => false,
						'priority' => -2,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_type != ' . $dbr->addQuotes( RC_EDIT );
						},
						'cssClassSuffix' => 'src-mw-edit',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_source' ) === RecentChange::SRC_EDIT;
						},
					],
					[
						'name' => 'hidenewpages',
						'label' => 'rcfilters-filter-newpages-label',
						'description' => 'rcfilters-filter-newpages-description',
						'default' => false,
						'priority' => -3,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_type != ' . $dbr->addQuotes( RC_NEW );
						},
						'cssClassSuffix' => 'src-mw-new',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_source' ) === RecentChange::SRC_NEW;
						},
					],

					// hidecategorization

					[
						'name' => 'hidelog',
						'label' => 'rcfilters-filter-logactions-label',
						'description' => 'rcfilters-filter-logactions-description',
						'default' => false,
						'priority' => -5,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_type != ' . $dbr->addQuotes( RC_LOG );
						},
						'cssClassSuffix' => 'src-mw-log',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_source' ) === RecentChange::SRC_LOG;
						}
					],
				],
			],
		];

		$this->reviewStatusFilterGroupDefinition = [
			[
				'name' => 'reviewStatus',
				'title' => 'rcfilters-filtergroup-reviewstatus',
				'class' => ChangesListBooleanFilterGroup::class,
				'priority' => -5,
				'filters' => [
					[
						'name' => 'hidepatrolled',
						'label' => 'rcfilters-filter-patrolled-label',
						'description' => 'rcfilters-filter-patrolled-description',
						// rcshowhidepatr-show, rcshowhidepatr-hide
						// wlshowhidepatr
						'showHideSuffix' => 'showhidepatr',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_patrolled = 0';
						},
						'cssClassSuffix' => 'patrolled',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_patrolled' );
						},
					],
					[
						'name' => 'hideunpatrolled',
						'label' => 'rcfilters-filter-unpatrolled-label',
						'description' => 'rcfilters-filter-unpatrolled-description',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {

							$conds[] = 'rc_patrolled = 1';
						},
						'cssClassSuffix' => 'unpatrolled',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return !$rc->getAttribute( 'rc_patrolled' );
						},
					],
				],
			]
		];

		$this->hideCategorizationFilterDefinition = [
			'name' => 'hidecategorization',
			'label' => 'rcfilters-filter-categorization-label',
			'description' => 'rcfilters-filter-categorization-description',
			// rcshowhidecategorization-show, rcshowhidecategorization-hide.
			// wlshowhidecategorization
			'showHideSuffix' => 'showhidecategorization',
			'default' => false,
			'priority' => -4,
			'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
				&$query_options, &$join_conds ) {

				$conds[] = 'rc_type != ' . $dbr->addQuotes( RC_CATEGORIZE );
			},
			'cssClassSuffix' => 'src-mw-categorize',
			'isRowApplicableCallable' => function ( $ctx, $rc ) {
				return $rc->getAttribute( 'rc_source' ) === RecentChange::SRC_CATEGORIZE;
			},
		];
	}

	/**
	 * Check if filters are in conflict and guaranteed to return no results.
	 *
	 * @return bool
	 */
	protected function areFiltersInConflict() {
		$opts = $this->getOptions();
		/** @var ChangesListFilterGroup $group */
		foreach ( $this->getFilterGroups() as $group ) {

			if ( $group->getConflictingGroups() ) {
				wfLogWarning(
					$group->getName() .
					" specifies conflicts with other groups but these are not supported yet."
				);
			}

			/** @var ChangesListFilter $conflictingFilter */
			foreach ( $group->getConflictingFilters() as $conflictingFilter ) {
				if ( $conflictingFilter->activelyInConflictWithGroup( $group, $opts ) ) {
					return true;
				}
			}

			/** @var ChangesListFilter $filter */
			foreach ( $group->getFilters() as $filter ) {

				/** @var ChangesListFilter $conflictingFilter */
				foreach ( $filter->getConflictingFilters() as $conflictingFilter ) {
					if (
						$conflictingFilter->activelyInConflictWithFilter( $filter, $opts ) &&
						$filter->activelyInConflictWithFilter( $conflictingFilter, $opts )
					) {
						return true;
					}
				}

			}

		}

		return false;
	}

	/**
	 * Main execution point
	 *
	 * @param string $subpage
	 */
	public function execute( $subpage ) {
		$this->rcSubpage = $subpage;

		$this->setHeaders();
		$this->outputHeader();
		$this->addModules();

		$rows = $this->getRows();
		$opts = $this->getOptions();
		if ( $rows === false ) {
			if ( !$this->including() ) {
				$this->doHeader( $opts, 0 );
				$this->outputNoResults();
				$this->getOutput()->setStatusCode( 404 );
			}

			return;
		}

		$batch = new LinkBatch;
		foreach ( $rows as $row ) {
			$batch->add( NS_USER, $row->rc_user_text );
			$batch->add( NS_USER_TALK, $row->rc_user_text );
			$batch->add( $row->rc_namespace, $row->rc_title );
			if ( $row->rc_source === RecentChange::SRC_LOG ) {
				$formatter = LogFormatter::newFromRow( $row );
				foreach ( $formatter->getPreloadTitles() as $title ) {
					$batch->addObj( $title );
				}
			}
		}
		$batch->execute();
		$this->webOutput( $rows, $opts );

		$rows->free();

		if ( $this->getConfig()->get( 'EnableWANCacheReaper' ) ) {
			// Clean up any bad page entries for titles showing up in RC
			DeferredUpdates::addUpdate( new WANCacheReapUpdate(
				$this->getDB(),
				LoggerFactory::getInstance( 'objectcache' )
			) );
		}
	}

	/**
	 * Add the "no results" message to the output
	 */
	protected function outputNoResults() {
		$this->getOutput()->addHTML(
			'<div class="mw-changeslist-empty">' .
			$this->msg( 'recentchanges-noresult' )->parse() .
			'</div>'
		);
	}

	/**
	 * Get the database result for this special page instance. Used by ApiFeedRecentChanges.
	 *
	 * @return bool|ResultWrapper Result or false
	 */
	public function getRows() {
		$opts = $this->getOptions();

		$tables = [];
		$fields = [];
		$conds = [];
		$query_options = [];
		$join_conds = [];
		$this->buildQuery( $tables, $fields, $conds, $query_options, $join_conds, $opts );

		return $this->doMainQuery( $tables, $fields, $conds, $query_options, $join_conds, $opts );
	}

	/**
	 * Get the current FormOptions for this request
	 *
	 * @return FormOptions
	 */
	public function getOptions() {
		if ( $this->rcOptions === null ) {
			$this->rcOptions = $this->setup( $this->rcSubpage );
		}

		return $this->rcOptions;
	}

	/**
	 * Register all filters and their groups (including those from hooks), plus handle
	 * conflicts and defaults.
	 *
	 * You might want to customize these in the same method, in subclasses.  You can
	 * call getFilterGroup to access a group, and (on the group) getFilter to access a
	 * filter, then make necessary modfications to the filter or group (e.g. with
	 * setDefault).
	 */
	protected function registerFilters() {
		$this->registerFiltersFromDefinitions( $this->filterGroupDefinitions );

		// Make sure this is not being transcluded (we don't want to show this
		// information to all users just because the user that saves the edit can
		// patrol)
		if ( !$this->including() && $this->getUser()->useRCPatrol() ) {
			$this->registerFiltersFromDefinitions( $this->reviewStatusFilterGroupDefinition );
		}

		$changeTypeGroup = $this->getFilterGroup( 'changeType' );

		if ( $this->getConfig()->get( 'RCWatchCategoryMembership' ) ) {
			$transformedHideCategorizationDef = $this->transformFilterDefinition(
				$this->hideCategorizationFilterDefinition
			);

			$transformedHideCategorizationDef['group'] = $changeTypeGroup;

			$hideCategorization = new ChangesListBooleanFilter(
				$transformedHideCategorizationDef
			);
		}

		Hooks::run( 'ChangesListSpecialPageStructuredFilters', [ $this ] );

		$unstructuredGroupDefinition =
			$this->getFilterGroupDefinitionFromLegacyCustomFilters(
				$this->getCustomFilters()
			);
		$this->registerFiltersFromDefinitions( [ $unstructuredGroupDefinition ] );

		$userExperienceLevel = $this->getFilterGroup( 'userExpLevel' );

		$registration = $this->getFilterGroup( 'registration' );
		$anons = $registration->getFilter( 'hideanons' );

		// This means there is a conflict between any item in user experience level
		// being checked and only anons being *shown* (hideliu=1&hideanons=0 in the
		// URL, or equivalent).
		$userExperienceLevel->conflictsWith(
			$anons,
			'rcfilters-filtergroup-user-experience-level-conflicts-unregistered-global',
			'rcfilters-filtergroup-user-experience-level-conflicts-unregistered',
			'rcfilters-filter-unregistered-conflicts-user-experience-level'
		);

		$categoryFilter = $changeTypeGroup->getFilter( 'hidecategorization' );
		$logactionsFilter = $changeTypeGroup->getFilter( 'hidelog' );
		$pagecreationFilter = $changeTypeGroup->getFilter( 'hidenewpages' );

		$significanceTypeGroup = $this->getFilterGroup( 'significance' );
		$hideMinorFilter = $significanceTypeGroup->getFilter( 'hideminor' );

		// categoryFilter is conditional; see registerFilters
		if ( $categoryFilter !== null ) {
			$hideMinorFilter->conflictsWith(
				$categoryFilter,
				'rcfilters-hideminor-conflicts-typeofchange-global',
				'rcfilters-hideminor-conflicts-typeofchange',
				'rcfilters-typeofchange-conflicts-hideminor'
			);
		}
		$hideMinorFilter->conflictsWith(
			$logactionsFilter,
			'rcfilters-hideminor-conflicts-typeofchange-global',
			'rcfilters-hideminor-conflicts-typeofchange',
			'rcfilters-typeofchange-conflicts-hideminor'
		);
		$hideMinorFilter->conflictsWith(
			$pagecreationFilter,
			'rcfilters-hideminor-conflicts-typeofchange-global',
			'rcfilters-hideminor-conflicts-typeofchange',
			'rcfilters-typeofchange-conflicts-hideminor'
		);
	}

	/**
	 * Transforms filter definition to prepare it for constructor.
	 *
	 * See overrides of this method as well.
	 *
	 * @param array $filterDefinition Original filter definition
	 *
	 * @return array Transformed definition
	 */
	protected function transformFilterDefinition( array $filterDefinition ) {
		return $filterDefinition;
	}

	/**
	 * Register filters from a definition object
	 *
	 * Array specifying groups and their filters; see Filter and
	 * ChangesListFilterGroup constructors.
	 *
	 * There is light processing to simplify core maintenance.
	 */
	protected function registerFiltersFromDefinitions( array $definition ) {
		$autoFillPriority = -1;
		foreach ( $definition as $groupDefinition ) {
			if ( !isset( $groupDefinition['priority'] ) ) {
				$groupDefinition['priority'] = $autoFillPriority;
			} else {
				// If it's explicitly specified, start over the auto-fill
				$autoFillPriority = $groupDefinition['priority'];
			}

			$autoFillPriority--;

			$className = $groupDefinition['class'];
			unset( $groupDefinition['class'] );

			foreach ( $groupDefinition['filters'] as &$filterDefinition ) {
				$filterDefinition = $this->transformFilterDefinition( $filterDefinition );
			}

			$this->registerFilterGroup( new $className( $groupDefinition ) );
		}
	}

	/**
	 * Get filter group definition from legacy custom filters
	 *
	 * @param array Custom filters from legacy hooks
	 * @return array Group definition
	 */
	protected function getFilterGroupDefinitionFromLegacyCustomFilters( $customFilters ) {
		// Special internal unstructured group
		$unstructuredGroupDefinition = [
			'name' => 'unstructured',
			'class' => ChangesListBooleanFilterGroup::class,
			'priority' => -1, // Won't display in structured
			'filters' => [],
		];

		foreach ( $customFilters as $name => $params ) {
			$unstructuredGroupDefinition['filters'][] = [
				'name' => $name,
				'showHide' => $params['msg'],
				'default' => $params['default'],
			];
		}

		return $unstructuredGroupDefinition;
	}

	/**
	 * Register all the filters, including legacy hook-driven ones.
	 * Then create a FormOptions object with options as specified by the user
	 *
	 * @param array $parameters
	 *
	 * @return FormOptions
	 */
	public function setup( $parameters ) {
		$this->registerFilters();

		$opts = $this->getDefaultOptions();

		$opts = $this->fetchOptionsFromRequest( $opts );

		// Give precedence to subpage syntax
		if ( $parameters !== null ) {
			$this->parseParameters( $parameters, $opts );
		}

		$this->validateOptions( $opts );

		return $opts;
	}

	/**
	 * Get a FormOptions object containing the default options. By default, returns
	 * some basic options.  The filters listed explicitly here are overriden in this
	 * method, in subclasses, but most filters (e.g. hideminor, userExpLevel filters,
	 * and more) are structured.  Structured filters are overriden in registerFilters.
	 * not here.
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$config = $this->getConfig();
		$opts = new FormOptions();
		$structuredUI = $this->getUser()->getOption( 'rcenhancedfilters' );

		// Add all filters
		foreach ( $this->filterGroups as $filterGroup ) {
			// URL parameters can be per-group, like 'userExpLevel',
			// or per-filter, like 'hideminor'.
			if ( $filterGroup->isPerGroupRequestParameter() ) {
				$opts->add( $filterGroup->getName(), $filterGroup->getDefault() );
			} else {
				foreach ( $filterGroup->getFilters() as $filter ) {
					$opts->add( $filter->getName(), $filter->getDefault( $structuredUI ) );
				}
			}
		}

		$opts->add( 'namespace', '', FormOptions::INTNULL );
		$opts->add( 'invert', false );
		$opts->add( 'associated', false );

		return $opts;
	}

	/**
	 * Register a structured changes list filter group
	 *
	 * @param ChangesListFilterGroup $group
	 */
	public function registerFilterGroup( ChangesListFilterGroup $group ) {
		$groupName = $group->getName();

		$this->filterGroups[$groupName] = $group;
	}

	/**
	 * Gets the currently registered filters groups
	 *
	 * @return array Associative array of ChangesListFilterGroup objects, with group name as key
	 */
	protected function getFilterGroups() {
		return $this->filterGroups;
	}

	/**
	 * Gets a specified ChangesListFilterGroup by name
	 *
	 * @param string $groupName Name of group
	 *
	 * @return ChangesListFilterGroup|null Group, or null if not registered
	 */
	public function getFilterGroup( $groupName ) {
		return isset( $this->filterGroups[$groupName] ) ?
			$this->filterGroups[$groupName] :
			null;
	}

	// Currently, this intentionally only includes filters that display
	// in the structured UI.  This can be changed easily, though, if we want
	// to include data on filters that use the unstructured UI.  messageKeys is a
	// special top-level value, with the value being an array of the message keys to
	// send to the client.
	/**
	 * Gets structured filter information needed by JS
	 *
	 * @return array Associative array
	 * * array $return['groups'] Group data
	 * * array $return['messageKeys'] Array of message keys
	 */
	public function getStructuredFilterJsData() {
		$output = [
			'groups' => [],
			'messageKeys' => [],
		];

		$context = $this->getContext();

		usort( $this->filterGroups, function ( $a, $b ) {
			return $b->getPriority() - $a->getPriority();
		} );

		foreach ( $this->filterGroups as $groupName => $group ) {
			$groupOutput = $group->getJsData( $this );
			if ( $groupOutput !== null ) {
				$output['messageKeys'] = array_merge(
					$output['messageKeys'],
					$groupOutput['messageKeys']
				);

				unset( $groupOutput['messageKeys'] );
				$output['groups'][] = $groupOutput;
			}
		}

		return $output;
	}

	/**
	 * Get custom show/hide filters using deprecated ChangesListSpecialPageFilters
	 * hook.
	 *
	 * @return array Map of filter URL param names to properties (msg/default)
	 */
	protected function getCustomFilters() {
		if ( $this->customFilters === null ) {
			$this->customFilters = [];
			Hooks::run( 'ChangesListSpecialPageFilters', [ $this, &$this->customFilters ], '1.29' );
		}

		return $this->customFilters;
	}

	/**
	 * Fetch values for a FormOptions object from the WebRequest associated with this instance.
	 *
	 * Intended for subclassing, e.g. to add a backwards-compatibility layer.
	 *
	 * @param FormOptions $opts
	 * @return FormOptions
	 */
	protected function fetchOptionsFromRequest( $opts ) {
		$opts->fetchValuesFromRequest( $this->getRequest() );

		return $opts;
	}

	/**
	 * Process $par and put options found in $opts. Used when including the page.
	 *
	 * @param string $par
	 * @param FormOptions $opts
	 */
	public function parseParameters( $par, FormOptions $opts ) {
		$stringParameterNameSet = [];
		$hideParameterNameSet = [];

		// URL parameters can be per-group, like 'userExpLevel',
		// or per-filter, like 'hideminor'.

		foreach ( $this->filterGroups as $filterGroup ) {
			if ( $filterGroup->isPerGroupRequestParameter() ) {
				$stringParameterNameSet[$filterGroup->getName()] = true;
			} elseif ( $filterGroup->getType() === ChangesListBooleanFilterGroup::TYPE ) {
				foreach ( $filterGroup->getFilters() as $filter ) {
					$hideParameterNameSet[$filter->getName()] = true;
				}
			}
		}

		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			$m = [];
			if ( isset( $hideParameterNameSet[$bit] ) ) {
				// hidefoo => hidefoo=true
				$opts[$bit] = true;
			} elseif ( isset( $hideParameterNameSet["hide$bit"] ) ) {
				// foo => hidefoo=false
				$opts["hide$bit"] = false;
			} elseif ( preg_match( '/^(.*)=(.*)$/', $bit, $m ) ) {
				if ( isset( $stringParameterNameSet[$m[1]] ) ) {
					$opts[$m[1]] = $m[2];
				}
			}
		}
	}

	/**
	 * Validate a FormOptions object generated by getDefaultOptions() with values already populated.
	 *
	 * @param FormOptions $opts
	 */
	public function validateOptions( FormOptions $opts ) {
		// nothing by default
	}

	/**
	 * Sets appropriate tables, fields, conditions, etc. depending on which filters
	 * the user requested.
	 *
	 * @param array &$tables Array of tables; see IDatabase::select $table
	 * @param array &$fields Array of fields; see IDatabase::select $vars
	 * @param array &$conds Array of conditions; see IDatabase::select $conds
	 * @param array &$query_options Array of query options; see IDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 * @param FormOptions $opts
	 */
	protected function buildQuery( &$tables, &$fields, &$conds, &$query_options,
		&$join_conds, FormOptions $opts ) {

		$dbr = $this->getDB();
		$user = $this->getUser();

		$context = $this->getContext();
		foreach ( $this->filterGroups as $filterGroup ) {
			// URL parameters can be per-group, like 'userExpLevel',
			// or per-filter, like 'hideminor'.
			if ( $filterGroup->isPerGroupRequestParameter() ) {
				$filterGroup->modifyQuery( $dbr, $this, $tables, $fields, $conds,
					$query_options, $join_conds, $opts[$filterGroup->getName()] );
			} else {
				foreach ( $filterGroup->getFilters() as $filter ) {
					if ( $opts[$filter->getName()] ) {
						$filter->modifyQuery( $dbr, $this, $tables, $fields, $conds,
							$query_options, $join_conds );
					}
				}
			}
		}

		// Namespace filtering
		if ( $opts['namespace'] !== '' ) {
			$selectedNS = $dbr->addQuotes( $opts['namespace'] );
			$operator = $opts['invert'] ? '!=' : '=';
			$boolean = $opts['invert'] ? 'AND' : 'OR';

			// Namespace association (T4429)
			if ( !$opts['associated'] ) {
				$condition = "rc_namespace $operator $selectedNS";
			} else {
				// Also add the associated namespace
				$associatedNS = $dbr->addQuotes(
					MWNamespace::getAssociated( $opts['namespace'] )
				);
				$condition = "(rc_namespace $operator $selectedNS "
					. $boolean
					. " rc_namespace $operator $associatedNS)";
			}

			$conds[] = $condition;
		}
	}

	/**
	 * Process the query
	 *
	 * @param array $tables Array of tables; see IDatabase::select $table
	 * @param array $fields Array of fields; see IDatabase::select $vars
	 * @param array $conds Array of conditions; see IDatabase::select $conds
	 * @param array $query_options Array of query options; see IDatabase::select $options
	 * @param array $join_conds Array of join conditions; see IDatabase::select $join_conds
	 * @param FormOptions $opts
	 * @return bool|ResultWrapper Result or false
	 */
	protected function doMainQuery( $tables, $fields, $conds,
		$query_options, $join_conds, FormOptions $opts ) {

		$tables[] = 'recentchanges';
		$fields = array_merge( RecentChange::selectFields(), $fields );

		ChangeTags::modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$query_options,
			''
		);

		// It makes no sense to hide both anons and logged-in users. When this occurs, try a guess on
		// what the user meant and either show only bots or force anons to be shown.

		// -------

		// XXX: We're no longer doing this handling.  To preserve back-compat, we need to complete
		// T151873 (particularly the hideanons/hideliu/hidebots/hidehumans part) in conjunction
		// with merging this.

		if ( !$this->runMainQueryHook( $tables, $fields, $conds, $query_options, $join_conds,
			$opts )
		) {
			return false;
		}

		$dbr = $this->getDB();

		return $dbr->select(
			$tables,
			$fields,
			$conds,
			__METHOD__,
			$query_options,
			$join_conds
		);
	}

	protected function runMainQueryHook( &$tables, &$fields, &$conds,
		&$query_options, &$join_conds, $opts
	) {
		return Hooks::run(
			'ChangesListSpecialPageQuery',
			[ $this->getName(), &$tables, &$fields, &$conds, &$query_options, &$join_conds, $opts ]
		);
	}

	/**
	 * Return a IDatabase object for reading
	 *
	 * @return IDatabase
	 */
	protected function getDB() {
		return wfGetDB( DB_REPLICA );
	}

	/**
	 * Send output to the OutputPage object, only called if not used feeds
	 *
	 * @param ResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	public function webOutput( $rows, $opts ) {
		if ( !$this->including() ) {
			$this->outputFeedLinks();
			$this->doHeader( $opts, $rows->numRows() );
		}

		$this->outputChangesList( $rows, $opts );
	}

	/**
	 * Output feed links.
	 */
	public function outputFeedLinks() {
		// nothing by default
	}

	/**
	 * Build and output the actual changes list.
	 *
	 * @param ResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	abstract public function outputChangesList( $rows, $opts );

	/**
	 * Set the text to be displayed above the changes
	 *
	 * @param FormOptions $opts
	 * @param int $numRows Number of rows in the result to show after this header
	 */
	public function doHeader( $opts, $numRows ) {
		$this->setTopText( $opts );

		// @todo Lots of stuff should be done here.

		$this->setBottomText( $opts );
	}

	/**
	 * Send the text to be displayed before the options. Should use $this->getOutput()->addWikiText()
	 * or similar methods to print the text.
	 *
	 * @param FormOptions $opts
	 */
	public function setTopText( FormOptions $opts ) {
		// nothing by default
	}

	/**
	 * Send the text to be displayed after the options. Should use $this->getOutput()->addWikiText()
	 * or similar methods to print the text.
	 *
	 * @param FormOptions $opts
	 */
	public function setBottomText( FormOptions $opts ) {
		// nothing by default
	}

	/**
	 * Get options to be displayed in a form
	 * @todo This should handle options returned by getDefaultOptions().
	 * @todo Not called by anything in this class (but is in subclasses), should be
	 * called by something… doHeader() maybe?
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function getExtraOptions( $opts ) {
		return [];
	}

	/**
	 * Return the legend displayed within the fieldset
	 *
	 * @return string
	 */
	public function makeLegend() {
		$context = $this->getContext();
		$user = $context->getUser();
		# The legend showing what the letters and stuff mean
		$legend = Html::openElement( 'dl' ) . "\n";
		# Iterates through them and gets the messages for both letter and tooltip
		$legendItems = $context->getConfig()->get( 'RecentChangesFlags' );
		if ( !( $user->useRCPatrol() || $user->useNPPatrol() ) ) {
			unset( $legendItems['unpatrolled'] );
		}
		foreach ( $legendItems as $key => $item ) { # generate items of the legend
			$label = isset( $item['legend'] ) ? $item['legend'] : $item['title'];
			$letter = $item['letter'];
			$cssClass = isset( $item['class'] ) ? $item['class'] : $key;

			$legend .= Html::element( 'dt',
				[ 'class' => $cssClass ], $context->msg( $letter )->text()
			) . "\n" .
			Html::rawElement( 'dd',
				[ 'class' => Sanitizer::escapeClass( 'mw-changeslist-legend-' . $key ) ],
				$context->msg( $label )->parse()
			) . "\n";
		}
		# (+-123)
		$legend .= Html::rawElement( 'dt',
			[ 'class' => 'mw-plusminus-pos' ],
			$context->msg( 'recentchanges-legend-plusminus' )->parse()
		) . "\n";
		$legend .= Html::element(
			'dd',
			[ 'class' => 'mw-changeslist-legend-plusminus' ],
			$context->msg( 'recentchanges-label-plusminus' )->text()
		) . "\n";
		$legend .= Html::closeElement( 'dl' ) . "\n";

		# Collapsibility
		$legend =
			'<div class="mw-changeslist-legend">' .
				$context->msg( 'recentchanges-legend-heading' )->parse() .
				'<div class="mw-collapsible-content">' . $legend . '</div>' .
			'</div>';

		return $legend;
	}

	/**
	 * Add page-specific modules.
	 */
	protected function addModules() {
		$out = $this->getOutput();
		// Styles and behavior for the legend box (see makeLegend())
		$out->addModuleStyles( [
			'mediawiki.special.changeslist.legend',
			'mediawiki.special.changeslist',
		] );
		$out->addModules( 'mediawiki.special.changeslist.legend.js' );
	}

	protected function getGroupName() {
		return 'changes';
	}

	/**
	 * Filter on users' experience levels; this will not be called if nothing is
	 * selected.
	 *
	 * @param string $specialPageClassName Class name of current special page
	 * @param IContextSource $context Context, for e.g. user
	 * @param IDatabase $dbr Database, for addQuotes, makeList, and similar
	 * @param array &$tables Array of tables; see IDatabase::select $table
	 * @param array &$fields Array of fields; see IDatabase::select $vars
	 * @param array &$conds Array of conditions; see IDatabase::select $conds
	 * @param array &$query_options Array of query options; see IDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 * @param array $selectedExpLevels The allowed active values, sorted
	 */
	public function filterOnUserExperienceLevel( $specialPageClassName, $context, $dbr,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds, $selectedExpLevels ) {

		global $wgLearnerEdits,
			$wgExperiencedUserEdits,
			$wgLearnerMemberSince,
			$wgExperiencedUserMemberSince;

		$LEVEL_COUNT = 3;

		// If all levels are selected, all logged-in users are included (but no
		// anons), so we can short-circuit.
		if ( count( $selectedExpLevels ) === $LEVEL_COUNT ) {
			$conds[] = 'rc_user != 0';
			return;
		}

		$tables[] = 'user';
		$join_conds['user'] = [ 'LEFT JOIN', 'rc_user = user_id' ];

		$now = time();
		$secondsPerDay = 86400;
		$learnerCutoff = $now - $wgLearnerMemberSince * $secondsPerDay;
		$experiencedUserCutoff = $now - $wgExperiencedUserMemberSince * $secondsPerDay;

		$aboveNewcomer = $dbr->makeList(
			[
				'user_editcount >= ' . intval( $wgLearnerEdits ),
				'user_registration <= ' . $dbr->timestamp( $learnerCutoff ),
			],
			IDatabase::LIST_AND
		);

		$aboveLearner = $dbr->makeList(
			[
				'user_editcount >= ' . intval( $wgExperiencedUserEdits ),
				'user_registration <= ' . $dbr->timestamp( $experiencedUserCutoff ),
			],
			IDatabase::LIST_AND
		);

		if ( $selectedExpLevels === [ 'newcomer' ] ) {
			$conds[] = "NOT ( $aboveNewcomer )";
		} elseif ( $selectedExpLevels === [ 'learner' ] ) {
			$conds[] = $dbr->makeList(
				[ $aboveNewcomer, "NOT ( $aboveLearner )" ],
				IDatabase::LIST_AND
			);
		} elseif ( $selectedExpLevels === [ 'experienced' ] ) {
			$conds[] = $aboveLearner;
		} elseif ( $selectedExpLevels === [ 'learner', 'newcomer' ] ) {
			$conds[] = "NOT ( $aboveLearner )";
		} elseif ( $selectedExpLevels === [ 'experienced', 'newcomer' ] ) {
			$conds[] = $dbr->makeList(
				[ "NOT ( $aboveNewcomer )", $aboveLearner ],
				IDatabase::LIST_OR
			);
		} elseif ( $selectedExpLevels === [ 'experienced', 'learner' ] ) {
			$conds[] = $aboveNewcomer;
		}
	}
}
