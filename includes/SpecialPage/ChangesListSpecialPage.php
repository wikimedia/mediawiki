<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\SpecialPage;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\RecentChanges\ChangesListBooleanFilterGroup;
use MediaWiki\RecentChanges\ChangesListFilterFactory;
use MediaWiki\RecentChanges\ChangesListFilterGroup;
use MediaWiki\RecentChanges\ChangesListFilterGroupContainer;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQueryFactory;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListResult;
use MediaWiki\RecentChanges\ChangesListStringOptionsFilterGroup;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangeFactory;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserArray;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\User\UserIdentityValue;
use OOUI\IconWidget;
use stdClass;
use Wikimedia\Rdbms\DBQueryTimeoutError;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Special page which uses a ChangesList to show query results.
 *
 * @todo Most of the functions here should be protected instead of public.
 *
 * @ingroup RecentChanges
 * @ingroup SpecialPage
 */
abstract class ChangesListSpecialPage extends SpecialPage {

	/** @var string */
	protected $rcSubpage;

	/** @var FormOptions */
	protected $rcOptions;

	protected UserIdentityUtils $userIdentityUtils;
	protected TempUserConfig $tempUserConfig;

	protected ChangesListFilterGroupContainer $filterGroups;
	protected RecentChangeFactory $recentChangeFactory;
	protected ChangesListQueryFactory $changesListQueryFactory;

	private ?ChangesListResult $queryResult = null;

	/** @var bool */
	private $mainQueryHookRegistered = false;
	/** @var bool */
	private $mainQueryHookCalled = false;

	/**
	 * @param string $name
	 * @param string $restriction
	 * @param UserIdentityUtils $userIdentityUtils
	 * @param TempUserConfig $tempUserConfig
	 * @param RecentChangeFactory $recentChangeFactory
	 * @param ChangesListQueryFactory $changesListQueryFactory
	 */
	public function __construct(
		$name,
		$restriction,
		UserIdentityUtils $userIdentityUtils,
		TempUserConfig $tempUserConfig,
		RecentChangeFactory $recentChangeFactory,
		ChangesListQueryFactory $changesListQueryFactory,
	) {
		parent::__construct( $name, $restriction );

		$this->userIdentityUtils = $userIdentityUtils;
		$this->tempUserConfig = $tempUserConfig;
		$this->recentChangeFactory = $recentChangeFactory;
		$this->changesListQueryFactory = $changesListQueryFactory;
		$this->filterGroups = new ChangesListFilterGroupContainer();
	}

	/**
	 * Definitions for the filters and their groups.
	 *
	 * This is extended by overriding getExtraFilterGroupDefinitions() in
	 * subclasses.
	 *
	 * @see ChangesListFilterFactory::registerFiltersFromDefinitions()
	 *
	 * @return array
	 */
	private function getBaseFilterGroupDefinitions() {
		return [
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
						'action' => [ 'exclude', 'named' ],
						'isReplacedInStructuredUi' => true,
					],
					[
						'name' => 'hideanons',
						// rcshowhideanons-show, rcshowhideanons-hide,
						// wlshowhideanons
						'showHideSuffix' => 'showhideanons',
						'default' => false,
						'action' => [ 'require', 'named' ],
						'isReplacedInStructuredUi' => true,
					]
				],
			],

			[
				'name' => 'userExpLevel',
				'title' => 'rcfilters-filtergroup-user-experience-level',
				'class' => ChangesListStringOptionsFilterGroup::class,
				'isFullCoverage' => true,
				'filters' => [
					[
						'name' => 'unregistered',
						'requireConfig' => [ 'isRegistrationRequiredToEdit' => false ],
						'label' => 'rcfilters-filter-user-experience-level-unregistered-label',
						'description' => $this->tempUserConfig->isKnown() ?
							'rcfilters-filter-user-experience-level-unregistered-description-temp' :
							'rcfilters-filter-user-experience-level-unregistered-description',
						'cssClassSuffix' => 'user-unregistered',
						'action' => [ 'require', 'experience', 'unregistered' ],
					],
					[
						'name' => 'registered',
						'requireConfig' => [ 'isRegistrationRequiredToEdit' => false ],
						'label' => 'rcfilters-filter-user-experience-level-registered-label',
						'description' => 'rcfilters-filter-user-experience-level-registered-description',
						'cssClassSuffix' => 'user-registered',
						'action' => [ 'require', 'experience', 'registered' ],
						'subsets' => [ 'newcomer', 'learner', 'experienced' ],
					],
					[
						'name' => 'newcomer',
						'label' => 'rcfilters-filter-user-experience-level-newcomer-label',
						'description' => 'rcfilters-filter-user-experience-level-newcomer-description',
						'cssClassSuffix' => 'user-newcomer',
						'action' => [ 'require', 'experience', 'newcomer' ],
					],
					[
						'name' => 'learner',
						'label' => 'rcfilters-filter-user-experience-level-learner-label',
						'description' => 'rcfilters-filter-user-experience-level-learner-description',
						'cssClassSuffix' => 'user-learner',
						'action' => [ 'require', 'experience', 'learner' ],
					],
					[
						'name' => 'experienced',
						'label' => 'rcfilters-filter-user-experience-level-experienced-label',
						'description' => 'rcfilters-filter-user-experience-level-experienced-description',
						'cssClassSuffix' => 'user-experienced',
						'action' => [ 'require', 'experience', 'experienced' ],
					]
				],
				'default' => ChangesListStringOptionsFilterGroup::NONE,
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
						'action' => [ 'exclude', 'user', $this->getUser() ],
						'highlight' => [ 'require', 'user', $this->getUser() ],
						'cssClassSuffix' => 'self',
					],
					[
						'name' => 'hidebyothers',
						'label' => 'rcfilters-filter-editsbyother-label',
						'description' => 'rcfilters-filter-editsbyother-description',
						'default' => false,
						'action' => [ 'require', 'user', $this->getUser() ],
						'highlight' => [ 'exclude', 'user', $this->getUser() ],
						'cssClassSuffix' => 'others',
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
						'action' => [ 'exclude', 'bot' ],
						'highlight' => [ 'require', 'bot' ],
						'cssClassSuffix' => 'bot',
					],
					[
						'name' => 'hidehumans',
						'label' => 'rcfilters-filter-humans-label',
						'description' => 'rcfilters-filter-humans-description',
						'default' => false,
						'action' => [ 'require', 'bot' ],
						'highlight' => [ 'exclude', 'bot' ],
						'cssClassSuffix' => 'human',
					]
				]
			],

			// significance (conditional)

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
						'action' => [ 'exclude', 'minor' ],
						'highlight' => [ 'require', 'minor' ],
						'cssClassSuffix' => 'minor',
						'conflictOptions' => [
							'globalKey' => 'rcfilters-hideminor-conflicts-typeofchange-global',
							'forwardKey' => 'rcfilters-hideminor-conflicts-typeofchange',
							'backwardKey' => 'rcfilters-typeofchange-conflicts-hideminor',
						],
						'conflictsWith' => [
							'changeType' => [
								'hidecategorization' => [],
								'hidelog' => [],
								'hidenewuserlog' => [],
								'hidenewpages' => []
							],
						],
					],
					[
						'name' => 'hidemajor',
						'label' => 'rcfilters-filter-major-label',
						'description' => 'rcfilters-filter-major-description',
						'default' => false,
						'action' => [ 'require', 'minor' ],
						'highlight' => [ 'exclude', 'minor' ],
						'cssClassSuffix' => 'major',
					]
				]
			],

			[
				'name' => 'lastRevision',
				'title' => 'rcfilters-filtergroup-lastrevision',
				'class' => ChangesListBooleanFilterGroup::class,
				'priority' => -7,
				'filters' => [
					[
						'name' => 'hidelastrevision',
						'label' => 'rcfilters-filter-lastrevision-label',
						'description' => 'rcfilters-filter-lastrevision-description',
						'default' => false,
						'action' => [
							[ 'require', 'revisionType', 'old' ],
							[ 'require', 'revisionType', 'none' ],
						],
						'highlight' => [ 'require', 'revisionType', 'latest' ],
						'cssClassSuffix' => 'last',
					],
					[
						'name' => 'hidepreviousrevisions',
						'label' => 'rcfilters-filter-previousrevision-label',
						'description' => 'rcfilters-filter-previousrevision-description',
						'default' => false,
						'action' => [
							[ 'require', 'revisionType', 'latest' ],
							[ 'require', 'revisionType', 'none' ],
						],
						'highlight' => [ 'require', 'revisionType', 'old' ],
						'cssClassSuffix' => 'previous',
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
						'action' => [ 'exclude', 'source', RecentChange::SRC_EDIT ],
						'highlight' => [ 'require', 'source', RecentChange::SRC_EDIT ],
						'cssClassSuffix' => 'src-mw-edit',
					],
					[
						'name' => 'hidenewpages',
						'label' => 'rcfilters-filter-newpages-label',
						'description' => 'rcfilters-filter-newpages-description',
						'default' => false,
						'priority' => -3,
						'action' => [ 'exclude', 'source', RecentChange::SRC_NEW ],
						'highlight' => [ 'require', 'source', RecentChange::SRC_NEW ],
						'cssClassSuffix' => 'src-mw-new',
					],
					[
						'name' => 'hidecategorization',
						'label' => 'rcfilters-filter-categorization-label',
						'description' => 'rcfilters-filter-categorization-description',
						// rcshowhidecategorization-show, rcshowhidecategorization-hide.
						// wlshowhidecategorization
						'showHideSuffix' => 'showhidecategorization',
						'default' => false,
						'priority' => -4,
						'requireConfig' => [ 'RCWatchCategoryMembership' => true ],
						'action' => [ 'exclude', 'source', RecentChange::SRC_CATEGORIZE ],
						'highlight' => [ 'require', 'source', RecentChange::SRC_CATEGORIZE ],
						'cssClassSuffix' => 'src-mw-categorize',
						'conflictOptions' => [
							'globalKey' => 'rcfilters-hidecategorization-conflicts-reviewstatus-global',
							'forwardKey' => 'rcfilters-hidecategorization-conflicts-reviewstatus',
							'backwardKey' => 'rcfilters-reviewstatus-conflicts-reviewstatus',
						],
						'conflictsWith' => [
							'reviewStatus' => [
								'unpatrolled' => [],
								'manual' => [],
							],
						],
					],
					[
						'name' => 'hidelog',
						'label' => 'rcfilters-filter-logactions-label',
						'description' => 'rcfilters-filter-logactions-description',
						'default' => false,
						'priority' => -5,
						'action' => [ 'exclude', 'source', RecentChange::SRC_LOG ],
						'highlight' => [ 'require', 'source', RecentChange::SRC_LOG ],
						'cssClassSuffix' => 'src-mw-log',
					],
					[
						'name' => 'hidenewuserlog',
						'label' => 'rcfilters-filter-accountcreations-label',
						'description' => 'rcfilters-filter-accountcreations-description',
						'default' => false,
						'priority' => -6,
						'action' => [ 'exclude', 'logType', 'newusers' ],
						'highlight' => [ 'require', 'logType', 'newusers' ],
						'cssClassSuffix' => 'src-mw-newuserlog',
					],
				],
			],

			[
				'name' => 'legacyReviewStatus',
				'title' => 'rcfilters-filtergroup-reviewstatus',
				'class' => ChangesListBooleanFilterGroup::class,
				'requireConfig' => [ 'useRCPatrol' => true ],
				'filters' => [
					[
						'name' => 'hidepatrolled',
						// rcshowhidepatr-show, rcshowhidepatr-hide
						// wlshowhidepatr
						'showHideSuffix' => 'showhidepatr',
						'default' => false,
						'action' => [ 'require', 'patrolled', RecentChange::PRC_UNPATROLLED ],
						'isReplacedInStructuredUi' => true,
					],
					[
						'name' => 'hideunpatrolled',
						'default' => false,
						'action' => [ 'exclude', 'patrolled', RecentChange::PRC_UNPATROLLED ],
						'isReplacedInStructuredUi' => true,
					],
				],
			],

			[
				'name' => 'reviewStatus',
				'title' => 'rcfilters-filtergroup-reviewstatus',
				'class' => ChangesListStringOptionsFilterGroup::class,
				'isFullCoverage' => true,
				'priority' => -5,
				'requireConfig' => [ 'useRCPatrol' => true ],
				'filters' => [
					[
						'name' => 'unpatrolled',
						'label' => 'rcfilters-filter-reviewstatus-unpatrolled-label',
						'description' => 'rcfilters-filter-reviewstatus-unpatrolled-description',
						'cssClassSuffix' => 'reviewstatus-unpatrolled',
						'action' => [ 'require', 'patrolled', RecentChange::PRC_UNPATROLLED ],
					],
					[
						'name' => 'manual',
						'label' => 'rcfilters-filter-reviewstatus-manual-label',
						'description' => 'rcfilters-filter-reviewstatus-manual-description',
						'cssClassSuffix' => 'reviewstatus-manual',
						'action' => [ 'require', 'patrolled', RecentChange::PRC_PATROLLED ],
					],
					[
						'name' => 'auto',
						'label' => 'rcfilters-filter-reviewstatus-auto-label',
						'description' => 'rcfilters-filter-reviewstatus-auto-description',
						'cssClassSuffix' => 'reviewstatus-auto',
						'action' => [ 'require', 'patrolled', RecentChange::PRC_AUTOPATROLLED ],
					],
				],
				'default' => ChangesListStringOptionsFilterGroup::NONE,
			],
		];
	}

	/**
	 * This may be overridden by subclasses to add more filter groups.
	 *
	 * @see ChangesListFilterFactory::registerFiltersFromDefinitions()
	 * @return array
	 */
	protected function getExtraFilterGroupDefinitions(): array {
		return [];
	}

	/**
	 * @param string|null $subpage
	 */
	public function execute( $subpage ) {
		$this->rcSubpage = $subpage;

		if ( $this->considerActionsForDefaultSavedQuery( $subpage ) ) {
			// Don't bother rendering the page if we'll be performing a redirect (T330100).
			return;
		}

		// Enable OOUI and module for the clock icon.
		if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) && !$this->including() ) {
			$this->getOutput()->enableOOUI();
			$this->getOutput()->addModules( 'mediawiki.special.changeslist.watchlistexpiry' );
		}

		$opts = $this->getOptions();
		try {
			$result = $this->getQueryResult();
			$rows = $result->getResultWrapper();

			// Used by Structured UI app to get results without MW chrome
			if ( $this->getRequest()->getRawVal( 'action' ) === 'render' ) {
				$this->getOutput()->setArticleBodyOnly( true );
			}

			// Used by "live update" and "view newest" to check
			// if there's new changes with minimal data transfer
			if ( $this->getRequest()->getBool( 'peek' ) ) {
				$code = $rows->numRows() > 0 ? 200 : 204;
				$this->getOutput()->setStatusCode( $code );

				if ( $this->getUser()->isAnon() !==
					$this->getRequest()->getFuzzyBool( 'isAnon' )
				) {
					$this->getOutput()->setStatusCode( 205 );
				}

				return;
			}

			$services = MediaWikiServices::getInstance();
			$logFormatterFactory = $services->getLogFormatterFactory();
			$linkBatchFactory = $services->getLinkBatchFactory();
			$batch = $linkBatchFactory->newLinkBatch();
			$userNames = [];
			foreach ( $rows as $row ) {
				$batch->addUser( new UserIdentityValue( $row->rc_user ?? 0, $row->rc_user_text ) );
				$userNames[] = $row->rc_user_text;
				$batch->add( $row->rc_namespace, $row->rc_title );
				if ( $row->rc_source === RecentChange::SRC_LOG ) {
					$formatter = $logFormatterFactory->newFromRow( $row );
					foreach ( $formatter->getPreloadTitles() as $title ) {
						$batch->addObj( $title );
						if ( $title->inNamespace( NS_USER ) || $title->inNamespace( NS_USER_TALK ) ) {
							$userNames[] = $title->getText();
						}
					}
				}
			}
			$batch->execute();
			foreach ( UserArray::newFromNames( $userNames ) as $_ ) {
				// Trigger UserEditTracker::setCachedUserEditCount via User::loadFromRow
				// Preloads edit count for User::getExperienceLevel() and Linker::userToolLinks()
			}

			$this->setHeaders();
			$this->outputHeader();
			$this->addModules();
			$this->webOutput( $rows, $opts );
		} catch ( DBQueryTimeoutError $timeoutException ) {
			MWExceptionHandler::logException( $timeoutException );

			$this->setHeaders();
			$this->outputHeader();
			$this->addModules();

			$this->getOutput()->setStatusCode( 500 );
			$this->webOutputHeader( 0, $opts );
			$this->outputTimeout();
		}

		$this->includeRcFiltersApp();
	}

	/**
	 * Set the temp user config.
	 *
	 * @internal
	 * @param TempUserConfig $tempUserConfig
	 * @since 1.42
	 */
	public function setTempUserConfig( TempUserConfig $tempUserConfig ) {
		$this->tempUserConfig = $tempUserConfig;
		$this->changesListQueryFactory->setTempUserConfig( $tempUserConfig );
	}

	/**
	 * Check whether or not the page should load defaults, and if so, whether
	 * a default saved query is relevant to be redirected to. If it is relevant,
	 * redirect properly with all necessary query parameters.
	 *
	 * @param string $subpage
	 * @return bool Whether a redirect will be performed.
	 */
	protected function considerActionsForDefaultSavedQuery( $subpage ) {
		if ( !$this->isStructuredFilterUiEnabled() || $this->including() ) {
			return false;
		}

		$knownParams = $this->getRequest()->getValues(
			...array_keys( $this->getOptions()->getAllValues() )
		);

		// HACK: Temporarily until we can properly define "sticky" filters and parameters,
		// we need to exclude several parameters we know should not be counted towards preventing
		// the loading of defaults.
		$excludedParams = [ 'limit' => '', 'days' => '', 'enhanced' => '', 'from' => '' ];
		$knownParams = array_diff_key( $knownParams, $excludedParams );

		if (
			// If there are NO known parameters in the URL request
			// (that are not excluded) then we need to check into loading
			// the default saved query
			count( $knownParams ) === 0
		) {
			$prefJson = MediaWikiServices::getInstance()
				->getUserOptionsLookup()
				->getOption( $this->getUser(), $this->getSavedQueriesPreferenceName() );

			// Get the saved queries data and parse it
			$savedQueries = $prefJson ? FormatJson::decode( $prefJson, true ) : false;

			if ( $savedQueries && isset( $savedQueries[ 'default' ] ) ) {
				// Only load queries that are 'version' 2, since those
				// have parameter representation
				if ( isset( $savedQueries[ 'version' ] ) && $savedQueries[ 'version' ] === '2' ) {
					$savedQueryDefaultID = $savedQueries[ 'default' ];
					$defaultQuery = $savedQueries[ 'queries' ][ $savedQueryDefaultID ][ 'data' ];

					// Build the entire parameter list
					$query = array_merge(
						$defaultQuery[ 'params' ],
						$defaultQuery[ 'highlights' ],
						[
							'urlversion' => '2',
						]
					);
					// Add to the query any parameters that we may have ignored before
					// but are still valid and requested in the URL
					$query = array_merge( $this->getRequest()->getQueryValues(), $query );
					unset( $query[ 'title' ] );
					$this->getOutput()->redirect( $this->getPageTitle( $subpage )->getCanonicalURL( $query ) );

					// Signal that we only need to redirect to the full URL
					// and can skip rendering the actual page (T330100).
					return true;
				} else {
					// There's a default, but the version is not 2, and the server can't
					// actually recognize the query itself. This happens if it is before
					// the conversion, so we need to tell the UI to reload saved query as
					// it does the conversion to version 2
					$this->getOutput()->addJsConfigVars(
						'wgStructuredChangeFiltersDefaultSavedQueryExists',
						true
					);

					// Add the class that tells the frontend it is still loading
					// another query
					$this->getOutput()->addBodyClasses( 'mw-rcfilters-ui-loading' );
				}
			}
		}

		return false;
	}

	/**
	 * @see \MediaWiki\MainConfigSchema::RCLinkDays and \MediaWiki\MainConfigSchema::RCFilterByAge.
	 * @return int[]
	 */
	protected function getLinkDays() {
		$linkDays = $this->getConfig()->get( MainConfigNames::RCLinkDays );
		$filterByAge = $this->getConfig()->get( MainConfigNames::RCFilterByAge );
		$maxAge = $this->getConfig()->get( MainConfigNames::RCMaxAge );
		if ( $filterByAge ) {
			// Trim it to only links which are within $wgRCMaxAge.
			// Note that we allow one link higher than the max for things like
			// "age 56 days" being accessible through the "60 days" link.
			sort( $linkDays );

			$maxAgeDays = $maxAge / ( 3600 * 24 );
			foreach ( $linkDays as $i => $days ) {
				if ( $days >= $maxAgeDays ) {
					array_splice( $linkDays, $i + 1 );
					break;
				}
			}
		}

		return $linkDays;
	}

	/**
	 * Include the modules and configuration for the RCFilters app.
	 * Conditional on the user having the feature enabled.
	 *
	 * If it is disabled, add a <body> class marking that
	 */
	protected function includeRcFiltersApp() {
		$out = $this->getOutput();
		if ( $this->isStructuredFilterUiEnabled() && !$this->including() ) {
			$jsData = $this->filterGroups->getJsData();
			$messages = [];
			foreach ( $jsData['messageKeys'] as $key ) {
				$messages[$key] = $this->msg( $key )->plain();
			}

			$out->addBodyClasses( 'mw-rcfilters-enabled' );
			$collapsed = MediaWikiServices::getInstance()->getUserOptionsLookup()
				->getBoolOption( $this->getUser(), $this->getCollapsedPreferenceName() );
			if ( $collapsed ) {
				$out->addBodyClasses( 'mw-rcfilters-collapsed' );
			}

			// These config and message exports should be moved into a ResourceLoader data module (T201574)
			$out->addJsConfigVars( 'wgStructuredChangeFilters', $jsData['groups'] );
			$out->addJsConfigVars( 'wgStructuredChangeFiltersMessages', $messages );
			$out->addJsConfigVars( 'wgStructuredChangeFiltersCollapsedState', $collapsed );

			$out->addJsConfigVars(
				'StructuredChangeFiltersDisplayConfig',
				[
					'maxDays' => // Translate to days
						(int)$this->getConfig()->get( MainConfigNames::RCMaxAge ) / ( 24 * 3600 ),
					'limitArray' => $this->getConfig()->get( MainConfigNames::RCLinkLimits ),
					'limitDefault' => $this->getDefaultLimit(),
					'daysArray' => $this->getLinkDays(),
					'daysDefault' => $this->getDefaultDays(),
				]
			);

			$out->addJsConfigVars(
				'wgStructuredChangeFiltersSavedQueriesPreferenceName',
				$this->getSavedQueriesPreferenceName()
			);
			$out->addJsConfigVars(
				'wgStructuredChangeFiltersLimitPreferenceName',
				$this->getLimitPreferenceName()
			);
			$out->addJsConfigVars(
				'wgStructuredChangeFiltersDaysPreferenceName',
				$this->getDefaultDaysPreferenceName()
			);
			$out->addJsConfigVars(
				'wgStructuredChangeFiltersCollapsedPreferenceName',
				$this->getCollapsedPreferenceName()
			);
		} else {
			$out->addBodyClasses( 'mw-rcfilters-disabled' );
		}
	}

	/**
	 * Get essential data about getRcFiltersConfigVars() for change detection.
	 *
	 * @internal For use by Resources.php only.
	 * @see Module::getDefinitionSummary() and Module::getVersionHash()
	 * @param RL\Context $context
	 * @return array
	 */
	public static function getRcFiltersConfigSummary( RL\Context $context ) {
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()
			->getLanguage( $context->getLanguage() );
		return [
			// Reduce version computation by avoiding Message parsing
			'RCFiltersChangeTags' => ChangeTags::getChangeTagListSummary( $context, $lang ),
			'StructuredChangeFiltersEditWatchlistUrl' =>
				SpecialPage::getTitleFor( 'EditWatchlist' )->getLocalURL()
		];
	}

	/**
	 * Get config vars to export with the mediawiki.rcfilters.filters.ui module.
	 *
	 * @internal For use by Resources.php only.
	 * @param RL\Context $context
	 * @return array
	 */
	public static function getRcFiltersConfigVars( RL\Context $context ) {
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()
			->getLanguage( $context->getLanguage() );
		return [
			'RCFiltersChangeTags' => ChangeTags::getChangeTagList( $context, $lang ),
			'StructuredChangeFiltersEditWatchlistUrl' =>
				SpecialPage::getTitleFor( 'EditWatchlist' )->getLocalURL()
		];
	}

	/**
	 * Add the "no results" message to the output
	 */
	protected function outputNoResults() {
		$this->getOutput()->addHTML(
			Html::rawElement(
				'div',
				[ 'class' => 'mw-changeslist-empty' ],
				$this->msg( 'recentchanges-noresult' )->parse()
			)
		);
	}

	/**
	 * Add the "timeout" message to the output
	 */
	protected function outputTimeout() {
		$this->getOutput()->addHTML(
			'<div class="mw-changeslist-empty mw-changeslist-timeout">' .
			$this->msg( 'recentchanges-timeout' )->parse() .
			'</div>'
		);
	}

	/**
	 * Get the database result for this special page instance. Used by ApiFeedRecentChanges.
	 *
	 * @return IResultWrapper
	 */
	public function getRows() {
		return $this->getQueryResult()->getResultWrapper();
	}

	/**
	 * Perform and cache the main query.
	 *
	 * @return ChangesListResult
	 */
	protected function getQueryResult(): ChangesListResult {
		if ( !$this->queryResult ) {
			$opts = $this->getOptions();
			$query = $this->buildQuery( $opts );
			$this->modifyQuery( $query, $opts );
			$this->queryResult = $query->fetchResult();

			if ( $this->mainQueryHookRegistered && !$this->mainQueryHookCalled ) {
				// When an empty result set is forced, ChangesListQuery doesn't run
				// the hook, but some extensions need us to run it anyway to register
				// form options.
				// FIXME: risky to pass empty arrays here, and inefficient to
				//  call this hook when most of what it does is not needed.
				//  We need to deprecate it.
				$tables = $fields = $conds = $options = $joins = [];
				$this->runMainQueryHook( $tables, $fields, $conds, $options,
					$joins, $opts );
			}
		}
		return $this->queryResult;
	}

	/**
	 * Create a RecentChange object from a row, injecting highlights from the
	 * current ChangesListQuery.
	 *
	 * @param stdClass $row
	 * @return RecentChange
	 */
	protected function newRecentChangeFromRow( $row ) {
		$rc = $this->recentChangeFactory->newRecentChangeFromRow( $row );
		$rc->setHighlights( $this->getQueryResult()->getHighlightsFromRow( $row ) );
		return $rc;
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
	 * Get configuration to be passed to the filter factory. The values here
	 * are matched against the "requireConfig" values in the filter group
	 * definitions.
	 *
	 * @return array
	 */
	private function getBaseFilterFactoryConfig() {
		return [
			'showHidePrefix' => '',
			'isRegistrationRequiredToEdit' => !MediaWikiServices::getInstance()
				->getPermissionManager()
				->isEveryoneAllowed( "edit" ),
			'useRCPatrol' => !$this->including() && $this->getUser()->useRCPatrol(),
			'RCWatchCategoryMembership' =>
				$this->getConfig()->get( MainConfigNames::RCWatchCategoryMembership ),
		];
	}

	/**
	 * Subclasses may override this to add configuration to the filter factory.
	 *
	 * @return array
	 */
	protected function getExtraFilterFactoryConfig(): array {
		return [];
	}

	/**
	 * Subclasses may override this to provide an array of filter group defaults,
	 * overriding the defaults in the filter definitions.
	 *
	 * @return array<string,string|array<string,bool>>
	 */
	protected function getFilterDefaultOverrides(): array {
		return [];
	}

	protected function getFilterFactory(): ChangesListFilterFactory {
		return new ChangesListFilterFactory(
			$this->getExtraFilterFactoryConfig() + $this->getBaseFilterFactoryConfig()
		);
	}

	/**
	 * Register all filters and their groups (including those from hooks), plus handle
	 * conflicts and defaults.
	 */
	protected function registerFilters() {
		$filterFactory = $this->getFilterFactory();
		$filterFactory->registerFiltersFromDefinitions(
			$this->filterGroups,
			$this->getBaseFilterGroupDefinitions()
		);
		$filterFactory->registerFiltersFromDefinitions(
			$this->filterGroups,
			$this->getExtraFilterGroupDefinitions()
		);
		$this->getHookRunner()->onChangesListSpecialPageStructuredFilters( $this );
		$this->filterGroups->setDefaults( $this->getFilterDefaultOverrides() );
	}

	/**
	 * Register filters from a definition object
	 *
	 * Array specifying groups and their filters; see Filter and
	 * ChangesListFilterGroup constructors.
	 *
	 * There is light processing to simplify core maintenance.
	 * @param array $definition
	 * @phan-param array<int,array{class:class-string<ChangesListFilterGroup>,filters:array}> $definition
	 */
	protected function registerFiltersFromDefinitions( array $definition ) {
		$this->getFilterFactory()->registerFiltersFromDefinitions( $this->filterGroups, $definition );
	}

	/**
	 * Register all the filters, including legacy hook-driven ones.
	 * Then create a FormOptions object with options as specified by the user
	 *
	 * @param string $parameters
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
	 * some basic options.  The filters listed explicitly here are overridden in this
	 * method, in subclasses, but most filters (e.g. hideminor, userExpLevel filters,
	 * and more) are structured.  Structured filters are overridden in registerFilters,
	 * not here.
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$opts = new FormOptions();
		$structuredUI = $this->isStructuredFilterUiEnabled();
		// If urlversion=2 is set, ignore the filter defaults and set them all to false/empty
		$useDefaults = $this->getRequest()->getInt( 'urlversion' ) !== 2;

		$this->filterGroups->addOptions( $opts, $useDefaults, $structuredUI );

		$opts->add( 'namespace', '', FormOptions::STRING );
		$opts->add( 'subpageof', '', FormOptions::STRING );
		// TODO: Rename this option to 'invertnamespaces'?
		$opts->add( 'invert', false );
		$opts->add( 'associated', false );
		$opts->add( 'urlversion', 1 );
		$opts->add( 'tagfilter', '' );
		$opts->add( 'inverttags', false );

		$opts->add( 'days', $this->getDefaultDays(), FormOptions::FLOAT );
		$opts->add( 'limit', $this->getDefaultLimit(), FormOptions::INT );

		$opts->add( 'from', '' );

		return $opts;
	}

	/**
	 * Register a structured changes list filter group
	 */
	public function registerFilterGroup( ChangesListFilterGroup $group ) {
		$this->filterGroups->registerGroup( $group );
	}

	/**
	 * Gets a specified ChangesListFilterGroup by name.
	 *
	 * In core you can usually use a typed accessor in $this->filterGroups instead.
	 *
	 * @param string $groupName Name of group
	 *
	 * @return ChangesListFilterGroup|null Group, or null if not registered
	 */
	public function getFilterGroup( $groupName ) {
		return $this->filterGroups->getGroup( $groupName );
	}

	/**
	 * Gets structured filter information needed by JS
	 *
	 * @internal for test
	 * @return array Associative array
	 * * array $return['groups'] Group data
	 * * array $return['messageKeys'] Array of message keys
	 */
	protected function getStructuredFilterJsData() {
		return $this->filterGroups->getJsData();
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
	 * See the comment on SpecialRecentChanges::parseParameters about why this exists.
	 *
	 * @param string $par
	 * @param FormOptions $opts
	 */
	public function parseParameters( $par, FormOptions $opts ) {
		$params = $this->filterGroups->getSubpageParams();

		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			$m = [];
			if ( ( $params[$bit] ?? '' ) === 'bool' ) {
				// hidefoo => hidefoo=true
				$opts[$bit] = true;
			} elseif ( ( $params["hide$bit"] ?? '' ) === 'bool' ) {
				// foo => hidefoo=false
				$opts["hide$bit"] = false;
			} elseif ( preg_match( '/^(.*)=(.*)$/', $bit, $m ) ) {
				if ( ( $params[$m[1]] ?? '' ) === 'string' ) {
					$opts[$m[1]] = $m[2];
				}
			}
		}
	}

	/**
	 * Validate a FormOptions object generated by getDefaultOptions() with values already populated.
	 */
	public function validateOptions( FormOptions $opts ) {
		$isContradictory = $this->fixContradictoryOptions( $opts );
		$isReplaced = $this->replaceOldOptions( $opts );

		if ( $isContradictory || $isReplaced ) {
			$query = wfArrayToCgi( $this->convertParamsForLink( $opts->getChangedValues() ) );
			$this->getOutput()->redirect( $this->getPageTitle()->getCanonicalURL( $query ) );
		}

		$opts->validateIntBounds( 'limit', 0, 5000 );
		$opts->validateBounds( 'days', 0,
			$this->getConfig()->get( MainConfigNames::RCMaxAge ) / ( 3600 * 24 ) );
	}

	/**
	 * Fix invalid options by resetting pairs that should never appear together.
	 *
	 * @param FormOptions $opts
	 * @return bool True if any option was reset
	 */
	private function fixContradictoryOptions( FormOptions $opts ) {
		$fixed = $this->fixBackwardsCompatibilityOptions( $opts );
		$fixed = $this->filterGroups->fixContradictoryOptions( $opts ) || $fixed;

		// Namespace conflicts with subpageof
		if ( $opts['namespace'] !== '' && $opts['subpageof'] !== '' ) {
			$opts['namespace'] = '';
			$fixed = true;
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
	 * Replace old options with their structured UI equivalents
	 *
	 * @param FormOptions $opts
	 * @return bool True if the change was made
	 */
	public function replaceOldOptions( FormOptions $opts ) {
		if ( !$this->isStructuredFilterUiEnabled() ) {
			return false;
		}

		$changed = false;

		// At this point 'hideanons' and 'hideliu' cannot be both true,
		// because fixBackwardsCompatibilityOptions resets (at least) 'hideanons' in such case
		if ( $opts[ 'hideanons' ] ) {
			$opts->reset( 'hideanons' );
			$opts[ 'userExpLevel' ] = 'registered';
			$changed = true;
		}

		if ( $opts[ 'hideliu' ] ) {
			$opts->reset( 'hideliu' );
			$opts[ 'userExpLevel' ] = 'unregistered';
			$changed = true;
		}

		if ( $this->filterGroups->hasGroup( 'legacyReviewStatus' ) ) {
			if ( $opts[ 'hidepatrolled' ] ) {
				$opts->reset( 'hidepatrolled' );
				$opts[ 'reviewStatus' ] = 'unpatrolled';
				$changed = true;
			}

			if ( $opts[ 'hideunpatrolled' ] ) {
				$opts->reset( 'hideunpatrolled' );
				$opts[ 'reviewStatus' ] = implode(
					ChangesListStringOptionsFilterGroup::SEPARATOR,
					[ 'manual', 'auto' ]
				);
				$changed = true;
			}
		}

		return $changed;
	}

	/**
	 * Convert parameters values from true/false to 1/0
	 * so they are not omitted by wfArrayToCgi()
	 * T38524
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
	 * @param FormOptions $opts
	 * @return ChangesListQuery
	 */
	protected function buildQuery( FormOptions $opts ) {
		$dbr = $this->getDB();
		$isStructuredUI = $this->isStructuredFilterUiEnabled();

		$query = $this->changesListQueryFactory->newQuery()
			->recentChangeFields()
			->watchlistUser( $this->getUser() )
			->audience( $this->getAuthority() )
			->excludeDeletedLogAction()
			->limit( $opts['limit'] )
			->maxExecutionTime( $this->getConfig()->get(
				MainConfigNames::MaxExecutionTimeForExpensiveQueries ) )
			->caller( static::class . '::buildQuery' );

		// Main query hook
		$this->addMainQueryHook( $query, $opts );

		// Old filter groups interface
		$query->legacyMutator(
			function (
				&$tables,
				&$fields,
				&$conds,
				&$query_options,
				&$join_conds,
			) use ( $dbr, $opts, $isStructuredUI ) {
				$this->filterGroups->modifyLegacyQuery(
					$dbr,
					$this,
					$tables,
					$fields,
					$conds,
					$query_options,
					$join_conds,
					$opts,
					$isStructuredUI
				);
			}
		);

		// New filter groups interface
		$this->filterGroups->modifyChangesListQuery( $query, $opts, $isStructuredUI );

		// Namespace filtering
		if ( $opts[ 'namespace' ] !== '' ) {
			$namespaces = explode( ';', $opts[ 'namespace' ] );

			$namespaces = $this->expandSymbolicNamespaceFilters( $namespaces );

			$namespaceInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
			$namespaces = array_filter( $namespaces, [ $namespaceInfo, 'exists' ] );

			if ( $namespaces !== [] ) {
				// Namespaces are just ints, use them as int when acting with the database
				$namespaces = array_map( 'intval', $namespaces );

				if ( $opts[ 'associated' ] ) {
					$associatedNamespaces = array_map(
						[ $namespaceInfo, 'getAssociated' ],
						array_filter( $namespaces, [ $namespaceInfo, 'hasTalkNamespace' ] )
					);
					$namespaces = array_unique( array_merge( $namespaces, $associatedNamespaces ) );
				}

				if ( $opts['invert'] ) {
					$query->excludeNamespaces( $namespaces );
				} else {
					$query->requireNamespaces( $namespaces );
				}
			}
		}

		// Filtering for subpages of a given set of pages
		if ( $opts['subpageof'] !== '' ) {
			$titleParser = MediaWikiServices::getInstance()->getTitleParser();
			$basePages = explode( '|', $opts['subpageof'] );
			foreach ( $basePages as $basePageText ) {
				// Strip any trailing slash
				$basePageText = rtrim( $basePageText, '/' );
				try {
					$basePage = $titleParser->parseTitle( $basePageText );
				} catch ( MalformedTitleException ) {
					// Ignore invalid titles
					continue;
				}
				$query->requireSubpageOf( $basePage );
			}
		}

		// Change tags
		if ( $this->getConfig()->get( MainConfigNames::UseTagFilter ) ) {
			$tagFilter = $opts['tagfilter'] !== '' ? explode( '|', $opts['tagfilter'] ) : [];
			if ( $opts['inverttags'] ) {
				$query->excludeChangeTags( $tagFilter );
			} else {
				$query->requireChangeTags( $tagFilter );
			}
		}
		$query->addChangeTagSummaryField();

		// Calculate cutoff
		$cutoff_unixtime = ConvertibleTimestamp::time() - $opts['days'] * 3600 * 24;
		$cutoff = $dbr->timestamp( $cutoff_unixtime );

		$fromValid = preg_match( '/^[0-9]{14}$/', $opts['from'] );
		if ( $fromValid && $opts['from'] > wfTimestamp( TS_MW, $cutoff ) ) {
			$cutoff = $dbr->timestamp( $opts['from'] );
		} else {
			$opts->reset( 'from' );
		}

		$query->minTimestamp( $cutoff );

		// Feature flag
		if ( $this->getRequest()->getBool( 'enable_partitioning' ) ) {
			$query->enablePartitioning();
		}
		return $query;
	}

	/**
	 * Allow subclasses to modify the main query
	 *
	 * @param ChangesListQuery $query
	 * @param FormOptions $opts
	 */
	protected function modifyQuery( ChangesListQuery $query, FormOptions $opts ) {
	}

	/**
	 * @param array &$tables Array of tables to be queried
	 * @param array &$fields Array of columns to select
	 * @param array &$conds Array of WHERE conditionals for query
	 * @param array &$query_options Array of options for the database request
	 * @param array &$join_conds Join conditions for the tables
	 * @param FormOptions $opts FormOptions for this request
	 * @return bool|void True or no return value to continue or false to abort
	 */
	protected function runMainQueryHook( &$tables, &$fields, &$conds,
		&$query_options, &$join_conds, $opts
	) {
		return $this->getHookRunner()->onChangesListSpecialPageQuery(
			$this->getName(), $tables, $fields, $conds, $query_options, $join_conds, $opts );
	}

	/**
	 * @param ChangesListQuery $query
	 * @param FormOptions $opts FormOptions for this request
	 */
	protected function addMainQueryHook( $query, $opts ) {
		if ( $this->getHookContainer()->isRegistered( 'ChangesListSpecialPageQuery' ) ) {
			$this->mainQueryHookRegistered = true;
			$query->legacyMutator(
				function ( &$tables, &$fields, &$conds, &$query_options, &$join_conds )
				use ( $opts ) {
					$this->mainQueryHookCalled = true;
					return $this->runMainQueryHook( $tables, $fields, $conds,
						$query_options, $join_conds, $opts );
				}
			);
		}
	}

	/**
	 * Which database to use for read queries
	 */
	protected function getDB(): IReadableDatabase {
		return MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
	}

	/**
	 * Send header output to the OutputPage object, only called if not using feeds
	 *
	 * @param int $rowCount Number of database rows
	 * @param FormOptions $opts
	 */
	private function webOutputHeader( $rowCount, $opts ) {
		if ( !$this->including() ) {
			$this->outputFeedLinks();
			$this->doHeader( $opts, $rowCount );
		}
	}

	/**
	 * Send output to the OutputPage object, only called if not used feeds
	 *
	 * @param IResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	public function webOutput( $rows, $opts ) {
		$this->webOutputHeader( $rows->numRows(), $opts );

		$this->outputChangesList( $rows, $opts );
	}

	public function outputFeedLinks() {
		// nothing by default
	}

	/**
	 * Build and output the actual changes list.
	 *
	 * @param IResultWrapper $rows Database rows
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
	 * Send the text to be displayed before the options.
	 * Should use $this->getOutput()->addWikiTextAsInterface()
	 * or similar methods to print the text.
	 */
	public function setTopText( FormOptions $opts ) {
		// nothing by default
	}

	/**
	 * Send the text to be displayed after the options.
	 * Should use $this->getOutput()->addWikiTextAsInterface()
	 * or similar methods to print the text.
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
		$legendItems = $context->getConfig()->get( MainConfigNames::RecentChangesFlags );
		if ( !( $user->useRCPatrol() || $user->useNPPatrol() ) ) {
			unset( $legendItems['unpatrolled'] );
		}
		foreach ( $legendItems as $key => $item ) { # generate items of the legend
			$label = $item['legend'] ?? $item['title'];
			$letter = $item['letter'];
			$cssClass = $item['class'] ?? $key;

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
		// Watchlist expiry clock icon.
		if ( $context->getConfig()->get( MainConfigNames::WatchlistExpiry ) && !$this->including() ) {
			$widget = new IconWidget( [
				'icon' => 'clock',
				'classes' => [ 'mw-changesList-watchlistExpiry' ],
			] );
			// Link the image to its label for assistive technologies.
			$watchlistLabelId = 'mw-changeslist-watchlistExpiry-label';
			$widget->getIconElement()->setAttributes( [
				'role' => 'img',
				'aria-labelledby' => $watchlistLabelId,
			] );
			$legend .= Html::rawElement(
				'dt',
				[ 'class' => 'mw-changeslist-legend-watchlistexpiry' ],
				$widget->toString()
			);
			$legend .= Html::element(
				'dd',
				[ 'class' => 'mw-changeslist-legend-watchlistexpiry', 'id' => $watchlistLabelId ],
				$context->msg( 'recentchanges-legend-watchlistexpiry' )->text()
			);
		}
		$legend .= Html::closeElement( 'dl' ) . "\n";

		$legendHeading = $this->isStructuredFilterUiEnabled() ?
			$context->msg( 'rcfilters-legend-heading' )->parse() :
			$context->msg( 'recentchanges-legend-heading' )->parse();

		# Collapsible
		$collapsedState = $this->getRequest()->getCookie( 'changeslist-state' );

		$legend = Html::rawElement( 'details', [
				'class' => 'mw-changeslist-legend',
				'open' => $collapsedState !== 'collapsed' ? 'open' : null,
			],
			Html::rawElement( 'summary', [], $legendHeading ) .
				$legend
		);

		return $legend;
	}

	/**
	 * Add page-specific modules.
	 */
	protected function addModules() {
		$out = $this->getOutput();
		// Styles and behavior for the legend box (see makeLegend())
		$out->addModuleStyles( [
			'mediawiki.interface.helpers.styles',
			'mediawiki.special.changeslist.legend',
			'mediawiki.special.changeslist',
		] );
		$out->addModules( 'mediawiki.special.changeslist.legend.js' );

		if ( $this->isStructuredFilterUiEnabled() && !$this->including() ) {
			$out->addModules( 'mediawiki.rcfilters.filters.ui' );
			$out->addModuleStyles( 'mediawiki.rcfilters.filters.base.styles' );
		}
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'changes';
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

		return static::checkStructuredFilterUiEnabled( $this->getUser() );
	}

	/**
	 * Static method to check whether StructuredFilter UI is enabled for the given user
	 *
	 * @since 1.31
	 * @param UserIdentity $user
	 * @return bool
	 */
	public static function checkStructuredFilterUiEnabled( UserIdentity $user ) {
		return !MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getOption( $user, 'rcenhancedfilters-disable' );
	}

	/**
	 * Get the default value of the number of changes to display when loading
	 * the result set.
	 *
	 * @since 1.30
	 * @return int
	 */
	public function getDefaultLimit() {
		return MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getIntOption( $this->getUser(), $this->getLimitPreferenceName() );
	}

	/**
	 * Get the default value of the number of days to display when loading
	 * the result set.
	 * Supports fractional values, and should be cast to a float.
	 *
	 * @since 1.30
	 * @return float
	 */
	public function getDefaultDays() {
		return floatval( MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getOption( $this->getUser(), $this->getDefaultDaysPreferenceName() ) );
	}

	/**
	 * Getting the preference name for 'limit'.
	 *
	 * @since 1.37
	 * @return string
	 */
	abstract protected function getLimitPreferenceName(): string;

	/**
	 * Preference name for saved queries.
	 *
	 * @since 1.38
	 * @return string
	 */
	abstract protected function getSavedQueriesPreferenceName(): string;

	/**
	 * Preference name for 'days'.
	 *
	 * @since 1.38
	 * @return string
	 */
	abstract protected function getDefaultDaysPreferenceName(): string;

	/**
	 * Preference name for collapsing the active filter display.
	 *
	 * @since 1.38
	 * @return string
	 */
	abstract protected function getCollapsedPreferenceName(): string;

	/**
	 * @param array $namespaces
	 * @return array
	 */
	private function expandSymbolicNamespaceFilters( array $namespaces ) {
		$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		$symbolicFilters = [
			'all-contents' => $nsInfo->getSubjectNamespaces(),
			'all-discussions' => $nsInfo->getTalkNamespaces(),
		];
		$additionalNamespaces = [];
		foreach ( $symbolicFilters as $name => $values ) {
			if ( in_array( $name, $namespaces ) ) {
				$additionalNamespaces = array_merge( $additionalNamespaces, $values );
			}
		}
		$namespaces = array_diff( $namespaces, array_keys( $symbolicFilters ) );
		$namespaces = array_merge( $namespaces, $additionalNamespaces );
		return array_unique( $namespaces );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( ChangesListSpecialPage::class, 'ChangesListSpecialPage' );
