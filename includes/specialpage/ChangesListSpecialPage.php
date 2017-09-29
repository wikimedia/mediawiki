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
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * Special page which uses a ChangesList to show query results.
 * @todo Way too many public functions, most of them should be protected
 *
 * @ingroup SpecialPage
 */
abstract class ChangesListSpecialPage extends SpecialPage {
	/**
	 * Preference name for saved queries. Subclasses that use saved queries should override this.
	 * @var string
	 */
	protected static $savedQueriesPreferenceName;

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
						// rcshowhideliu-show, rcshowhideliu-hide,
						// wlshowhideliu
						'showHideSuffix' => 'showhideliu',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds
						) {
							$conds[] = 'rc_user = 0';
						},
						'isReplacedInStructuredUi' => true,

					],
					[
						'name' => 'hideanons',
						// rcshowhideanons-show, rcshowhideanons-hide,
						// wlshowhideanons
						'showHideSuffix' => 'showhideanons',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds
						) {
							$conds[] = 'rc_user != 0';
						},
						'isReplacedInStructuredUi' => true,
					]
				],
			],

			[
				'name' => 'userExpLevel',
				'title' => 'rcfilters-filtergroup-userExpLevel',
				'class' => ChangesListStringOptionsFilterGroup::class,
				'isFullCoverage' => true,
				'filters' => [
					[
						'name' => 'unregistered',
						'label' => 'rcfilters-filter-user-experience-level-unregistered-label',
						'description' => 'rcfilters-filter-user-experience-level-unregistered-description',
						'cssClassSuffix' => 'user-unregistered',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return !$rc->getAttribute( 'rc_user' );
						}
					],
					[
						'name' => 'registered',
						'label' => 'rcfilters-filter-user-experience-level-registered-label',
						'description' => 'rcfilters-filter-user-experience-level-registered-description',
						'cssClassSuffix' => 'user-registered',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_user' );
						}
					],
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
							&$query_options, &$join_conds
						) {
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
							&$query_options, &$join_conds
						) {
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
							&$query_options, &$join_conds
						) {
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
							&$query_options, &$join_conds
						) {
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
							&$query_options, &$join_conds
						) {
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
							&$query_options, &$join_conds
						) {
							$conds[] = 'rc_minor = 1';
						},
						'cssClassSuffix' => 'major',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return !$rc->getAttribute( 'rc_minor' );
						}
					]
				]
			],

			[
				'name' => 'lastRevision',
				'title' => 'rcfilters-filtergroup-lastRevision',
				'class' => ChangesListBooleanFilterGroup::class,
				'priority' => -7,
				'filters' => [
					[
						'name' => 'hidelastrevision',
						'label' => 'rcfilters-filter-lastrevision-label',
						'description' => 'rcfilters-filter-lastrevision-description',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {
							$conds[] = 'rc_this_oldid <> page_latest';
						},
						'cssClassSuffix' => 'last',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_this_oldid' ) === $rc->getAttribute( 'page_latest' );
						}
					],
					[
						'name' => 'hidepreviousrevisions',
						'label' => 'rcfilters-filter-previousrevision-label',
						'description' => 'rcfilters-filter-previousrevision-description',
						'default' => false,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds ) {
							$conds[] = 'rc_this_oldid = page_latest';
						},
						'cssClassSuffix' => 'previous',
						'isRowApplicableCallable' => function ( $ctx, $rc ) {
							return $rc->getAttribute( 'rc_this_oldid' ) !== $rc->getAttribute( 'page_latest' );
						}
					]
				]
			],

			// With extensions, there can be change types that will not be hidden by any of these.
			[
				'name' => 'changeType',
				'title' => 'rcfilters-filtergroup-changetype',
				'class' => ChangesListBooleanFilterGroup::class,
				'priority' => -8,
				'filters' => [
					[
						'name' => 'hidepageedits',
						'label' => 'rcfilters-filter-pageedits-label',
						'description' => 'rcfilters-filter-pageedits-description',
						'default' => false,
						'priority' => -2,
						'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables, &$fields, &$conds,
							&$query_options, &$join_conds
						) {
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
							&$query_options, &$join_conds
						) {
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
							&$query_options, &$join_conds
						) {
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
							&$query_options, &$join_conds
						) {
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
							&$query_options, &$join_conds
						) {
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
				&$query_options, &$join_conds
			) {
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

		$rows = $this->getRows();
		$opts = $this->getOptions();
		if ( $rows === false ) {
			$rows = new FakeResultWrapper( [] );
		}

		// Used by Structured UI app to get results without MW chrome
		if ( $this->getRequest()->getVal( 'action' ) === 'render' ) {
			$this->getOutput()->setArticleBodyOnly( true );
		}

		// Used by "live update" and "view newest" to check
		// if there's new changes with minimal data transfer
		if ( $this->getRequest()->getBool( 'peek' ) ) {
			$code = $rows->numRows() > 0 ? 200 : 204;
			$this->getOutput()->setStatusCode( $code );
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

		$this->setHeaders();
		$this->outputHeader();
		$this->addModules();
		$this->webOutput( $rows, $opts );

		$rows->free();

		if ( $this->getConfig()->get( 'EnableWANCacheReaper' ) ) {
			// Clean up any bad page entries for titles showing up in RC
			DeferredUpdates::addUpdate( new WANCacheReapUpdate(
				$this->getDB(),
				LoggerFactory::getInstance( 'objectcache' )
			) );
		}

		$this->includeRcFiltersApp();
	}

	/**
	 * Include the modules and configuration for the RCFilters app.
	 * Conditional on the user having the feature enabled.
	 *
	 * If it is disabled, add a <body> class marking that
	 */
	protected function includeRcFiltersApp() {
		$out = $this->getOutput();
		if ( $this->isStructuredFilterUiEnabled() ) {
			$jsData = $this->getStructuredFilterJsData();

			$messages = [];
			foreach ( $jsData['messageKeys'] as $key ) {
				$messages[$key] = $this->msg( $key )->plain();
			}

			$out->addBodyClasses( 'mw-rcfilters-enabled' );

			$out->addHTML(
				ResourceLoader::makeInlineScript(
					ResourceLoader::makeMessageSetScript( $messages )
				)
			);

			$experimentalStructuredChangeFilters =
				$this->getConfig()->get( 'StructuredChangeFiltersEnableExperimentalViews' );

			$out->addJsConfigVars( 'wgStructuredChangeFilters', $jsData['groups'] );
			$out->addJsConfigVars(
				'wgStructuredChangeFiltersEnableExperimentalViews',
				$experimentalStructuredChangeFilters
			);

			$out->addJsConfigVars(
				'wgRCFiltersChangeTags',
				$this->buildChangeTagList()
			);
			$out->addJsConfigVars(
				'StructuredChangeFiltersDisplayConfig',
				[
					'maxDays' => (int)$this->getConfig()->get( 'RCMaxAge' ) / ( 24 * 3600 ), // Translate to days
					'limitArray' => $this->getConfig()->get( 'RCLinkLimits' ),
					'limitDefault' => $this->getDefaultLimit(),
					'daysArray' => $this->getConfig()->get( 'RCLinkDays' ),
					'daysDefault' => $this->getDefaultDays(),
				]
			);

			if ( static::$savedQueriesPreferenceName ) {
				$savedQueries = FormatJson::decode(
					$this->getUser()->getOption( static::$savedQueriesPreferenceName )
				);
				if ( $savedQueries && isset( $savedQueries->default ) ) {
					// If there is a default saved query, show a loading spinner,
					// since the frontend is going to reload the results
					$out->addBodyClasses( 'mw-rcfilters-ui-loading' );
				}
				$out->addJsConfigVars(
					'wgStructuredChangeFiltersSavedQueriesPreferenceName',
					static::$savedQueriesPreferenceName
				);
			}
		} else {
			$out->addBodyClasses( 'mw-rcfilters-disabled' );
		}
	}

	/**
	 * Fetch the change tags list for the front end
	 *
	 * @return Array Tag data
	 */
	protected function buildChangeTagList() {
		$explicitlyDefinedTags = array_fill_keys( ChangeTags::listExplicitlyDefinedTags(), 0 );
		$softwareActivatedTags = array_fill_keys( ChangeTags::listSoftwareActivatedTags(), 0 );

		// Hit counts disabled for perf reasons, see T169997
		/*
		$tagStats = ChangeTags::tagUsageStatistics();
		$tagHitCounts = array_merge( $explicitlyDefinedTags, $softwareActivatedTags, $tagStats );

		// Sort by hits
		arsort( $tagHitCounts );
		*/
		$tagHitCounts = array_merge( $explicitlyDefinedTags, $softwareActivatedTags );

		// Build the list and data
		$result = [];
		foreach ( $tagHitCounts as $tagName => $hits ) {
			if (
				// Only get active tags
				isset( $explicitlyDefinedTags[ $tagName ] ) ||
				isset( $softwareActivatedTags[ $tagName ] )
			) {
				// Parse description
				$desc = ChangeTags::tagLongDescriptionMessage( $tagName, $this->getContext() );

				$result[] = [
					'name' => $tagName,
					'label' => Sanitizer::stripAllTags(
						ChangeTags::tagDescription( $tagName, $this->getContext() )
					),
					'description' => $desc ? Sanitizer::stripAllTags( $desc->parse() ) : '',
					'cssClass' => Sanitizer::escapeClass( 'mw-tag-' . $tagName ),
					'hits' => $hits,
				];
			}
		}

		// Instead of sorting by hit count (disabled, see above), sort by display name
		usort( $result, function ( $a, $b ) {
			return strcasecmp( $a['label'], $b['label'] );
		} );

		return $result;
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
		// patrol or is logged in)
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
		$registered = $userExperienceLevel->getFilter( 'registered' );
		$registered->setAsSupersetOf( $userExperienceLevel->getFilter( 'newcomer' ) );
		$registered->setAsSupersetOf( $userExperienceLevel->getFilter( 'learner' ) );
		$registered->setAsSupersetOf( $userExperienceLevel->getFilter( 'experienced' ) );

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
	 * @param array $definition
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
	 * @param array $customFilters Custom filters from legacy hooks
	 * @return array Group definition
	 */
	protected function getFilterGroupDefinitionFromLegacyCustomFilters( array $customFilters ) {
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
		$opts = new FormOptions();
		$structuredUI = $this->isStructuredFilterUiEnabled();
		// If urlversion=2 is set, ignore the filter defaults and set them all to false/empty
		$useDefaults = $this->getRequest()->getInt( 'urlversion' ) !== 2;

		// Add all filters
		/** @var ChangesListFilterGroup $filterGroup */
		foreach ( $this->filterGroups as $filterGroup ) {
			// URL parameters can be per-group, like 'userExpLevel',
			// or per-filter, like 'hideminor'.
			if ( $filterGroup->isPerGroupRequestParameter() ) {
				$opts->add( $filterGroup->getName(), $useDefaults ? $filterGroup->getDefault() : '' );
			} else {
				/** @var ChangesListBooleanFilter $filter */
				foreach ( $filterGroup->getFilters() as $filter ) {
					$opts->add( $filter->getName(), $useDefaults ? $filter->getDefault( $structuredUI ) : false );
				}
			}
		}

		$opts->add( 'namespace', '', FormOptions::STRING );
		$opts->add( 'invert', false );
		$opts->add( 'associated', false );
		$opts->add( 'urlversion', 1 );
		$opts->add( 'tagfilter', '' );

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
		if ( $this->fixContradictoryOptions( $opts ) ) {
			$query = wfArrayToCgi( $this->convertParamsForLink( $opts->getChangedValues() ) );
			$this->getOutput()->redirect( $this->getPageTitle()->getCanonicalURL( $query ) );
		}
	}

	/**
	 * Fix invalid options by resetting pairs that should never appear together.
	 *
	 * @param FormOptions $opts
	 * @return bool True if any option was reset
	 */
	private function fixContradictoryOptions( FormOptions $opts ) {
		$fixed = $this->fixBackwardsCompatibilityOptions( $opts );

		foreach ( $this->filterGroups as $filterGroup ) {
			if ( $filterGroup instanceof ChangesListBooleanFilterGroup ) {
				$filters = $filterGroup->getFilters();

				if ( count( $filters ) === 1 ) {
					// legacy boolean filters should not be considered
					continue;
				}

				$allInGroupEnabled = array_reduce(
					$filters,
					function ( $carry, $filter ) use ( $opts ) {
						return $carry && $opts[ $filter->getName() ];
					},
					/* initialValue */ count( $filters ) > 0
				);

				if ( $allInGroupEnabled ) {
					foreach ( $filters as $filter ) {
						$opts[ $filter->getName() ] = false;
					}

					$fixed = true;
				}
			}
		}

		return $fixed;
	}

	/**
	 * Fix a special case (hideanons=1 and hideliu=1) in a special way, for backwards
	 * compatibility.
	 *
	 * This is deprecated and may be removed.
	 *
	 * @param FormOptions $opts
	 * @return bool True if this change was mode
	 */
	private function fixBackwardsCompatibilityOptions( FormOptions $opts ) {
		if ( $opts['hideanons'] && $opts['hideliu'] ) {
			$opts->reset( 'hideanons' );
			if ( !$opts['hidebots'] ) {
				$opts->reset( 'hideliu' );
				$opts['hidehumans'] = 1;
			}

			return true;
		}

		return false;
	}

	/**
	 * Convert parameters values from true/false to 1/0
	 * so they are not omitted by wfArrayToCgi()
	 * Bug 36524
	 *
	 * @param array $params
	 * @return array
	 */
	protected function convertParamsForLink( $params ) {
		foreach ( $params as &$value ) {
			if ( $value === false ) {
				$value = '0';
			}
		}
		unset( $value );
		return $params;
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
		&$join_conds, FormOptions $opts
	) {
		$dbr = $this->getDB();
		$isStructuredUI = $this->isStructuredFilterUiEnabled();

		foreach ( $this->filterGroups as $filterGroup ) {
			// URL parameters can be per-group, like 'userExpLevel',
			// or per-filter, like 'hideminor'.
			if ( $filterGroup->isPerGroupRequestParameter() ) {
				$filterGroup->modifyQuery( $dbr, $this, $tables, $fields, $conds,
					$query_options, $join_conds, $opts[$filterGroup->getName()] );
			} else {
				foreach ( $filterGroup->getFilters() as $filter ) {
					if ( $filter->isActive( $opts, $isStructuredUI ) ) {
						$filter->modifyQuery( $dbr, $this, $tables, $fields, $conds,
							$query_options, $join_conds );
					}
				}
			}
		}

		// Namespace filtering
		if ( $opts[ 'namespace' ] !== '' ) {
			$namespaces = explode( ';', $opts[ 'namespace' ] );

			if ( $opts[ 'associated' ] ) {
				$associatedNamespaces = array_map(
					function ( $ns ) {
						return MWNamespace::getAssociated( $ns );
					},
					$namespaces
				);
				$namespaces = array_unique( array_merge( $namespaces, $associatedNamespaces ) );
			}

			if ( count( $namespaces ) === 1 ) {
				$operator = $opts[ 'invert' ] ? '!=' : '=';
				$value = $dbr->addQuotes( reset( $namespaces ) );
			} else {
				$operator = $opts[ 'invert' ] ? 'NOT IN' : 'IN';
				sort( $namespaces );
				$value = '(' . $dbr->makeList( $namespaces ) . ')';
			}
			$conds[] = "rc_namespace $operator $value";
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
		$query_options, $join_conds, FormOptions $opts
	) {
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

		$legendHeading = $this->isStructuredFilterUiEnabled() ?
			$context->msg( 'rcfilters-legend-heading' )->parse() :
			$context->msg( 'recentchanges-legend-heading' )->parse();

		# Collapsible
		$legend =
			'<div class="mw-changeslist-legend">' .
				$legendHeading .
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

		if ( $this->isStructuredFilterUiEnabled() ) {
			$out->addModules( 'mediawiki.rcfilters.filters.ui' );
			$out->addModuleStyles( 'mediawiki.rcfilters.filters.base.styles' );
		}
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
	 * @param int $now Number of seconds since the UNIX epoch, or 0 if not given
	 *   (optional)
	 */
	public function filterOnUserExperienceLevel( $specialPageClassName, $context, $dbr,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds, $selectedExpLevels, $now = 0
	) {
		global $wgLearnerEdits,
			$wgExperiencedUserEdits,
			$wgLearnerMemberSince,
			$wgExperiencedUserMemberSince;

		$LEVEL_COUNT = 5;

		// If all levels are selected, don't filter
		if ( count( $selectedExpLevels ) === $LEVEL_COUNT ) {
			return;
		}

		// both 'registered' and 'unregistered', experience levels, if any, are included in 'registered'
		if (
			in_array( 'registered', $selectedExpLevels ) &&
			in_array( 'unregistered', $selectedExpLevels )
		) {
			return;
		}

		// 'registered' but not 'unregistered', experience levels, if any, are included in 'registered'
		if (
			in_array( 'registered', $selectedExpLevels ) &&
			!in_array( 'unregistered', $selectedExpLevels )
		) {
			$conds[] = 'rc_user != 0';
			return;
		}

		if ( $selectedExpLevels === [ 'unregistered' ] ) {
			$conds[] = 'rc_user = 0';
			return;
		}

		$tables[] = 'user';
		$join_conds['user'] = [ 'LEFT JOIN', 'rc_user = user_id' ];

		if ( $now === 0 ) {
			$now = time();
		}
		$secondsPerDay = 86400;
		$learnerCutoff = $now - $wgLearnerMemberSince * $secondsPerDay;
		$experiencedUserCutoff = $now - $wgExperiencedUserMemberSince * $secondsPerDay;

		$aboveNewcomer = $dbr->makeList(
			[
				'user_editcount >= ' . intval( $wgLearnerEdits ),
				'user_registration <= ' . $dbr->addQuotes( $dbr->timestamp( $learnerCutoff ) ),
			],
			IDatabase::LIST_AND
		);

		$aboveLearner = $dbr->makeList(
			[
				'user_editcount >= ' . intval( $wgExperiencedUserEdits ),
				'user_registration <= ' .
					$dbr->addQuotes( $dbr->timestamp( $experiencedUserCutoff ) ),
			],
			IDatabase::LIST_AND
		);

		$conditions = [];

		if ( in_array( 'unregistered', $selectedExpLevels ) ) {
			$selectedExpLevels = array_diff( $selectedExpLevels, [ 'unregistered' ] );
			$conditions[] = 'rc_user = 0';
		}

		if ( $selectedExpLevels === [ 'newcomer' ] ) {
			$conditions[] = "NOT ( $aboveNewcomer )";
		} elseif ( $selectedExpLevels === [ 'learner' ] ) {
			$conditions[] = $dbr->makeList(
				[ $aboveNewcomer, "NOT ( $aboveLearner )" ],
				IDatabase::LIST_AND
			);
		} elseif ( $selectedExpLevels === [ 'experienced' ] ) {
			$conditions[] = $aboveLearner;
		} elseif ( $selectedExpLevels === [ 'learner', 'newcomer' ] ) {
			$conditions[] = "NOT ( $aboveLearner )";
		} elseif ( $selectedExpLevels === [ 'experienced', 'newcomer' ] ) {
			$conditions[] = $dbr->makeList(
				[ "NOT ( $aboveNewcomer )", $aboveLearner ],
				IDatabase::LIST_OR
			);
		} elseif ( $selectedExpLevels === [ 'experienced', 'learner' ] ) {
			$conditions[] = $aboveNewcomer;
		} elseif ( $selectedExpLevels === [ 'experienced', 'learner', 'newcomer' ] ) {
			$conditions[] = 'rc_user != 0';
		}

		if ( count( $conditions ) > 1 ) {
			$conds[] = $dbr->makeList( $conditions, IDatabase::LIST_OR );
		} elseif ( count( $conditions ) === 1 ) {
			$conds[] = reset( $conditions );
		}
	}

	/**
	 * Check whether the structured filter UI is enabled
	 *
	 * @return bool
	 */
	public function isStructuredFilterUiEnabled() {
		if ( $this->getRequest()->getBool( 'rcfilters' ) ) {
			return true;
		}

		if ( $this->getConfig()->get( 'StructuredChangeFiltersShowPreference' ) ) {
			return !$this->getUser()->getOption( 'rcenhancedfilters-disable' );
		} else {
			return $this->getUser()->getOption( 'rcenhancedfilters' );
		}
	}

	/**
	 * Check whether the structured filter UI is enabled by default (regardless of
	 * this particular user's setting)
	 *
	 * @return bool
	 */
	public function isStructuredFilterUiEnabledByDefault() {
		if ( $this->getConfig()->get( 'StructuredChangeFiltersShowPreference' ) ) {
			return !$this->getUser()->getDefaultOption( 'rcenhancedfilters-disable' );
		} else {
			return $this->getUser()->getDefaultOption( 'rcenhancedfilters' );
		}
	}

	abstract function getDefaultLimit();

	/**
	 * Get the default value of the number of days to display when loading
	 * the result set.
	 * Supports fractional values, and should be cast to a float.
	 *
	 * @return float
	 */
	abstract function getDefaultDays();
}
