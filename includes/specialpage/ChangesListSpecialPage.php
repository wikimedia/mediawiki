<?php
/**
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
 */

namespace MediaWiki\SpecialPage;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Context\IContextSource;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\RecentChanges\ChangesListBooleanFilter;
use MediaWiki\RecentChanges\ChangesListBooleanFilterGroup;
use MediaWiki\RecentChanges\ChangesListFilterGroup;
use MediaWiki\RecentChanges\ChangesListStringOptionsFilterGroup;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserArray;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\User\UserIdentityValue;
use OOUI\IconWidget;
use Wikimedia\Rdbms\DBQueryTimeoutError;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\Rdbms\RawSQLValue;
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
	 * unstructured UI, in which case you don't need a group title.
	 *
	 * @var array
	 */
	private $filterGroupDefinitions;

	/**
	 * @var array Same format as filterGroupDefinitions, but for a single group (reviewStatus)
	 * that is registered conditionally.
	 */
	private $legacyReviewStatusFilterGroupDefinition;

	/** @var array Single filter group registered conditionally */
	private $reviewStatusFilterGroupDefinition;

	/** @var array Single filter group registered conditionally */
	private $hideCategorizationFilterDefinition;

	/**
	 * Filter groups, and their contained filters
	 * This is an associative array (with group name as key) of ChangesListFilterGroup objects.
	 *
	 * @var ChangesListFilterGroup[]
	 */
	protected $filterGroups = [];

	/**
	 * @param string $name
	 * @param string $restriction
	 * @param UserIdentityUtils $userIdentityUtils
	 * @param TempUserConfig $tempUserConfig
	 */
	public function __construct(
		$name,
		$restriction,
		UserIdentityUtils $userIdentityUtils,
		TempUserConfig $tempUserConfig
	) {
		parent::__construct( $name, $restriction );

		$this->userIdentityUtils = $userIdentityUtils;
		$this->tempUserConfig = $tempUserConfig;

		$nonRevisionTypes = [ RC_LOG ];
		$this->getHookRunner()->onSpecialWatchlistGetNonRevisionTypes( $nonRevisionTypes );

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
						'queryCallable' => function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds[] = $this->getRegisteredExpr( false, $dbr );
							$join_conds['recentchanges_actor'] = [ 'JOIN', 'actor_id=rc_actor' ];
						},
						'isReplacedInStructuredUi' => true,

					],
					[
						'name' => 'hideanons',
						// rcshowhideanons-show, rcshowhideanons-hide,
						// wlshowhideanons
						'showHideSuffix' => 'showhideanons',
						'default' => false,
						'queryCallable' => function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds[] = $this->getRegisteredExpr( true, $dbr );
							$join_conds['recentchanges_actor'] = [ 'JOIN', 'actor_id=rc_actor' ];
						},
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
						'label' => 'rcfilters-filter-user-experience-level-unregistered-label',
						'description' => $this->tempUserConfig->isKnown() ?
							'rcfilters-filter-user-experience-level-unregistered-description-temp' :
							'rcfilters-filter-user-experience-level-unregistered-description',
						'cssClassSuffix' => 'user-unregistered',
						'isRowApplicableCallable' => function ( IContextSource $ctx, RecentChange $rc ) {
							return !$this->userIdentityUtils->isNamed( $rc->getPerformerIdentity() );
						}
					],
					[
						'name' => 'registered',
						'label' => 'rcfilters-filter-user-experience-level-registered-label',
						'description' => 'rcfilters-filter-user-experience-level-registered-description',
						'cssClassSuffix' => 'user-registered',
						'isRowApplicableCallable' => function ( IContextSource $ctx, RecentChange $rc ) {
							return $this->userIdentityUtils->isNamed( $rc->getPerformerIdentity() );
						}
					],
					[
						'name' => 'newcomer',
						'label' => 'rcfilters-filter-user-experience-level-newcomer-label',
						'description' => 'rcfilters-filter-user-experience-level-newcomer-description',
						'cssClassSuffix' => 'user-newcomer',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							$performer = $rc->getPerformerIdentity();
							return $performer->isRegistered() &&
								MediaWikiServices::getInstance()
									->getUserFactory()
									->newFromUserIdentity( $performer )
									->getExperienceLevel() === 'newcomer';
						}
					],
					[
						'name' => 'learner',
						'label' => 'rcfilters-filter-user-experience-level-learner-label',
						'description' => 'rcfilters-filter-user-experience-level-learner-description',
						'cssClassSuffix' => 'user-learner',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							$performer = $rc->getPerformerIdentity();
							return $performer->isRegistered() &&
								MediaWikiServices::getInstance()
									->getUserFactory()
									->newFromUserIdentity( $performer )
									->getExperienceLevel() === 'learner';
						},
					],
					[
						'name' => 'experienced',
						'label' => 'rcfilters-filter-user-experience-level-experienced-label',
						'description' => 'rcfilters-filter-user-experience-level-experienced-description',
						'cssClassSuffix' => 'user-experienced',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							$performer = $rc->getPerformerIdentity();
							return $performer->isRegistered() &&
								MediaWikiServices::getInstance()
									->getUserFactory()
									->newFromUserIdentity( $performer )
									->getExperienceLevel() === 'experienced';
						},
					]
				],
				'default' => ChangesListStringOptionsFilterGroup::NONE,
				'queryCallable' => $this->filterOnUserExperienceLevel( ... ),
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
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$user = $ctx->getUser();
							$conds[] = $dbr->expr( 'actor_name', '!=', $user->getName() );
							$join_conds['recentchanges_actor'] = [ 'JOIN', 'actor_id=rc_actor' ];
						},
						'cssClassSuffix' => 'self',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $ctx->getUser()->equals( $rc->getPerformerIdentity() );
						},
					],
					[
						'name' => 'hidebyothers',
						'label' => 'rcfilters-filter-editsbyother-label',
						'description' => 'rcfilters-filter-editsbyother-description',
						'default' => false,
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$user = $ctx->getUser();
							if ( $user->isAnon() ) {
								$conds['actor_name'] = $user->getName();
							} else {
								$conds['actor_user'] = $user->getId();
							}
							$join_conds['recentchanges_actor'] = [ 'JOIN', 'actor_id=rc_actor' ];
						},
						'cssClassSuffix' => 'others',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return !$ctx->getUser()->equals( $rc->getPerformerIdentity() );
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
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds['rc_bot'] = 0;
						},
						'cssClassSuffix' => 'bot',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $rc->getAttribute( 'rc_bot' );
						},
					],
					[
						'name' => 'hidehumans',
						'label' => 'rcfilters-filter-humans-label',
						'description' => 'rcfilters-filter-humans-description',
						'default' => false,
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds['rc_bot'] = 1;
						},
						'cssClassSuffix' => 'human',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return !$rc->getAttribute( 'rc_bot' );
						},
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
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds[] = $dbr->expr( 'rc_minor', '=', 0 );
						},
						'cssClassSuffix' => 'minor',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $rc->getAttribute( 'rc_minor' );
						}
					],
					[
						'name' => 'hidemajor',
						'label' => 'rcfilters-filter-major-label',
						'description' => 'rcfilters-filter-major-description',
						'default' => false,
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds[] = $dbr->expr( 'rc_minor', '=', 1 );
						},
						'cssClassSuffix' => 'major',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return !$rc->getAttribute( 'rc_minor' );
						}
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
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) use ( $nonRevisionTypes ) {
							$conds[] = $dbr->expr( 'rc_this_oldid', '!=', new RawSQLValue( 'page_latest' ) )
								->or( 'rc_type', '=', $nonRevisionTypes );
						},
						'cssClassSuffix' => 'last',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $rc->getAttribute( 'rc_this_oldid' ) === $rc->getAttribute( 'page_latest' );
						}
					],
					[
						'name' => 'hidepreviousrevisions',
						'label' => 'rcfilters-filter-previousrevision-label',
						'description' => 'rcfilters-filter-previousrevision-description',
						'default' => false,
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) use ( $nonRevisionTypes ) {
							$conds[] = $dbr->expr( 'rc_this_oldid', '=', new RawSQLValue( 'page_latest' ) )
								->or( 'rc_type', '=', $nonRevisionTypes );
						},
						'cssClassSuffix' => 'previous',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
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
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds[] = $dbr->expr( 'rc_type', '!=', RC_EDIT );
						},
						'cssClassSuffix' => 'src-mw-edit',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $rc->getAttribute( 'rc_source' ) === RecentChange::SRC_EDIT;
						},
					],
					[
						'name' => 'hidenewpages',
						'label' => 'rcfilters-filter-newpages-label',
						'description' => 'rcfilters-filter-newpages-description',
						'default' => false,
						'priority' => -3,
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds[] = $dbr->expr( 'rc_type', '!=', RC_NEW );
						},
						'cssClassSuffix' => 'src-mw-new',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
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
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds[] = $dbr->expr( 'rc_type', '!=', RC_LOG );
						},
						'cssClassSuffix' => 'src-mw-log',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $rc->getAttribute( 'rc_source' ) === RecentChange::SRC_LOG;
						}
					],
					[
						'name' => 'hidenewuserlog',
						'label' => 'rcfilters-filter-accountcreations-label',
						'description' => 'rcfilters-filter-accountcreations-description',
						'default' => false,
						'priority' => -6,
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds[] = $dbr->expr( 'rc_log_type', '!=', 'newusers' )
								->or( 'rc_log_type', '=', null );
						},
						'cssClassSuffix' => 'src-mw-newuserlog',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $rc->getAttribute( 'rc_log_type' ) === "newusers";
						},
					],
				],
			],

		];

		$this->legacyReviewStatusFilterGroupDefinition = [
			[
				'name' => 'legacyReviewStatus',
				'title' => 'rcfilters-filtergroup-reviewstatus',
				'class' => ChangesListBooleanFilterGroup::class,
				'filters' => [
					[
						'name' => 'hidepatrolled',
						// rcshowhidepatr-show, rcshowhidepatr-hide
						// wlshowhidepatr
						'showHideSuffix' => 'showhidepatr',
						'default' => false,
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds['rc_patrolled'] = RecentChange::PRC_UNPATROLLED;
						},
						'isReplacedInStructuredUi' => true,
					],
					[
						'name' => 'hideunpatrolled',
						'default' => false,
						'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
							IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
						) {
							$conds[] = $dbr->expr( 'rc_patrolled', '!=', RecentChange::PRC_UNPATROLLED );
						},
						'isReplacedInStructuredUi' => true,
					],
				],
			]
		];

		$this->reviewStatusFilterGroupDefinition = [
			[
				'name' => 'reviewStatus',
				'title' => 'rcfilters-filtergroup-reviewstatus',
				'class' => ChangesListStringOptionsFilterGroup::class,
				'isFullCoverage' => true,
				'priority' => -5,
				'filters' => [
					[
						'name' => 'unpatrolled',
						'label' => 'rcfilters-filter-reviewstatus-unpatrolled-label',
						'description' => 'rcfilters-filter-reviewstatus-unpatrolled-description',
						'cssClassSuffix' => 'reviewstatus-unpatrolled',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $rc->getAttribute( 'rc_patrolled' ) == RecentChange::PRC_UNPATROLLED;
						},
					],
					[
						'name' => 'manual',
						'label' => 'rcfilters-filter-reviewstatus-manual-label',
						'description' => 'rcfilters-filter-reviewstatus-manual-description',
						'cssClassSuffix' => 'reviewstatus-manual',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $rc->getAttribute( 'rc_patrolled' ) == RecentChange::PRC_PATROLLED;
						},
					],
					[
						'name' => 'auto',
						'label' => 'rcfilters-filter-reviewstatus-auto-label',
						'description' => 'rcfilters-filter-reviewstatus-auto-description',
						'cssClassSuffix' => 'reviewstatus-auto',
						'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
							return $rc->getAttribute( 'rc_patrolled' ) == RecentChange::PRC_AUTOPATROLLED;
						},
					],
				],
				'default' => ChangesListStringOptionsFilterGroup::NONE,
				'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
					IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds, $selected
				) {
					if ( $selected === [] ) {
						return;
					}
					$rcPatrolledValues = [
						'unpatrolled' => RecentChange::PRC_UNPATROLLED,
						'manual' => RecentChange::PRC_PATROLLED,
						'auto' => RecentChange::PRC_AUTOPATROLLED,
					];
					// e.g. rc_patrolled IN (0, 2)
					$conds['rc_patrolled'] = array_map( static function ( $s ) use ( $rcPatrolledValues ) {
						return $rcPatrolledValues[ $s ];
					}, $selected );
				}
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
			'queryCallable' => static function ( string $specialClassName, IContextSource $ctx,
				IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
			) {
				$conds[] = $dbr->expr( 'rc_type', '!=', RC_CATEGORIZE );
			},
			'cssClassSuffix' => 'src-mw-categorize',
			'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
				return $rc->getAttribute( 'rc_source' ) === RecentChange::SRC_CATEGORIZE;
			},
		];
	}

	/**
	 * Removes registration filters from filterGroupDefinitions
	 */
	private function removeRegistrationFilterDefinitions(): void {
		foreach ( $this->filterGroupDefinitions as $key => $value ) {
			if ( $value['name'] == "userExpLevel" ) {
				$this->filterGroupDefinitions[ $key ][ 'filters' ] = array_filter(
					$this->filterGroupDefinitions[ $key ][ 'filters' ],
					static fn ( $val, $key ) => $val[ 'name' ] != 'registered'
						&& $val[ 'name' ] != 'unregistered', ARRAY_FILTER_USE_BOTH );
			}
		}
	}

	/**
	 * Check if filters are in conflict and guaranteed to return no results.
	 *
	 * @return bool
	 */
	protected function areFiltersInConflict() {
		$opts = $this->getOptions();
		foreach ( $this->getFilterGroups() as $group ) {
			if ( $group->getConflictingGroups() ) {
				wfLogWarning(
					$group->getName() .
					" specifies conflicts with other groups but these are not supported yet."
				);
			}

			foreach ( $group->getConflictingFilters() as $conflictingFilter ) {
				if ( $conflictingFilter->activelyInConflictWithGroup( $group, $opts ) ) {
					return true;
				}
			}

			foreach ( $group->getFilters() as $filter ) {
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
			$rows = $this->getRows();
			if ( $rows === false ) {
				$rows = new FakeResultWrapper( [] );
			}

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

			$rows->free();
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
			$jsData = $this->getStructuredFilterJsData();
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
	 * @return IResultWrapper|false
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
		$isRegistrationRequiredToEdit = !MediaWikiServices::getInstance()
			->getPermissionManager()
			->isEveryoneAllowed( "edit" );
		if ( $isRegistrationRequiredToEdit ) {
			$this->removeRegistrationFilterDefinitions();
		}
		$this->registerFiltersFromDefinitions( $this->filterGroupDefinitions );

		// Make sure this is not being transcluded (we don't want to show this
		// information to all users just because the user that saves the edit can
		// patrol or is logged in)
		if ( !$this->including() && $this->getUser()->useRCPatrol() ) {
			$this->registerFiltersFromDefinitions( $this->legacyReviewStatusFilterGroupDefinition );
			$this->registerFiltersFromDefinitions( $this->reviewStatusFilterGroupDefinition );
		}

		$changeTypeGroup = $this->getFilterGroup( 'changeType' );

		$categoryFilter = null;
		if ( $this->getConfig()->get( MainConfigNames::RCWatchCategoryMembership ) ) {
			$transformedHideCategorizationDef = $this->transformFilterDefinition(
				$this->hideCategorizationFilterDefinition
			);

			$transformedHideCategorizationDef['group'] = $changeTypeGroup;

			$categoryFilter = new ChangesListBooleanFilter(
				$transformedHideCategorizationDef
			);
		}

		$this->getHookRunner()->onChangesListSpecialPageStructuredFilters( $this );

		$this->registerFiltersFromDefinitions( [] );

		$userExperienceLevel = $this->getFilterGroup( 'userExpLevel' );
		if ( !$isRegistrationRequiredToEdit ) {
			$registered = $userExperienceLevel->getFilter( 'registered' );
			$registered->setAsSupersetOf( $userExperienceLevel->getFilter( 'newcomer' ) );
			$registered->setAsSupersetOf( $userExperienceLevel->getFilter( 'learner' ) );
			$registered->setAsSupersetOf( $userExperienceLevel->getFilter( 'experienced' ) );
		}

		$logactionsFilter = $changeTypeGroup->getFilter( 'hidelog' );
		$lognewuserFilter = $changeTypeGroup->getFilter( 'hidenewuserlog' );
		$pagecreationFilter = $changeTypeGroup->getFilter( 'hidenewpages' );

		$significanceTypeGroup = $this->getFilterGroup( 'significance' );
		$hideMinorFilter = $significanceTypeGroup->getFilter( 'hideminor' );

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
			$lognewuserFilter,
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
	 * @phan-param array<int,array{class:class-string<ChangesListFilterGroup>,filters:array}> $definition
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
	 * @return ChangesListBooleanFilter[] The legacy show/hide toggle filters
	 */
	protected function getLegacyShowHideFilters() {
		$filters = [];
		foreach ( $this->filterGroups as $group ) {
			if ( $group instanceof ChangesListBooleanFilterGroup ) {
				foreach ( $group->getFilters() as $key => $filter ) {
					if ( $filter->displaysOnUnstructuredUi() ) {
						$filters[ $key ] = $filter;
					}
				}
			}
		}
		return $filters;
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
	 * and more) are structured.  Structured filters are overridden in registerFilters.
	 * not here.
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$opts = new FormOptions();
		$structuredUI = $this->isStructuredFilterUiEnabled();
		// If urlversion=2 is set, ignore the filter defaults and set them all to false/empty
		$useDefaults = $this->getRequest()->getInt( 'urlversion' ) !== 2;

		/** @var ChangesListFilterGroup $filterGroup */
		foreach ( $this->filterGroups as $filterGroup ) {
			$filterGroup->addOptions( $opts, $useDefaults, $structuredUI );
		}

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
		$groupName = $group->getName();

		$this->filterGroups[$groupName] = $group;
	}

	/**
	 * Gets the currently registered filters groups
	 *
	 * @return ChangesListFilterGroup[] Associative array of ChangesListFilterGroup objects, with group name as key
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
		return $this->filterGroups[$groupName] ?? null;
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

		usort( $this->filterGroups, static function ( ChangesListFilterGroup $a, ChangesListFilterGroup $b ) {
			return $b->getPriority() <=> $a->getPriority();
		} );

		foreach ( $this->filterGroups as $group ) {
			$groupOutput = $group->getJsData();
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
			if ( $filterGroup instanceof ChangesListStringOptionsFilterGroup ) {
				$stringParameterNameSet[$filterGroup->getName()] = true;
			} elseif ( $filterGroup instanceof ChangesListBooleanFilterGroup ) {
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

		foreach ( $this->filterGroups as $filterGroup ) {
			if ( $filterGroup instanceof ChangesListBooleanFilterGroup ) {
				$filters = $filterGroup->getFilters();

				if ( count( $filters ) === 1 ) {
					// legacy boolean filters should not be considered
					continue;
				}

				$allInGroupEnabled = array_reduce(
					$filters,
					static function ( bool $carry, ChangesListBooleanFilter $filter ) use ( $opts ) {
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

		if ( $this->getFilterGroup( 'legacyReviewStatus' ) ) {
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
	 * @param array &$tables Array of tables; see IReadableDatabase::select $table
	 * @param array &$fields Array of fields; see IReadableDatabase::select $vars
	 * @param array &$conds Array of conditions; see IReadableDatabase::select $conds
	 * @param array &$query_options Array of query options; see IReadableDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IReadableDatabase::select $join_conds
	 * @param FormOptions $opts
	 */
	protected function buildQuery( &$tables, &$fields, &$conds, &$query_options,
		&$join_conds, FormOptions $opts
	) {
		$dbr = $this->getDB();
		$isStructuredUI = $this->isStructuredFilterUiEnabled();

		/** @var ChangesListFilterGroup $filterGroup */
		foreach ( $this->filterGroups as $filterGroup ) {
			$filterGroup->modifyQuery( $dbr, $this, $tables, $fields, $conds,
				$query_options, $join_conds, $opts, $isStructuredUI );
		}

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

				$operator = $opts[ 'invert' ] ? '!=' : '=';
				sort( $namespaces );
				$conds[] = $dbr->expr( 'rc_namespace', $operator, $namespaces );
			}
		}

		// Filtering for subpages of a given set of pages
		if ( $opts['subpageof'] !== '' ) {
			$titleParser = MediaWikiServices::getInstance()->getTitleParser();
			$basePages = explode( '|', $opts['subpageof'] );
			$prefixesByNs = [];
			foreach ( $basePages as $basePageText ) {
				// Strip any trailing slash
				$basePageText = rtrim( $basePageText, '/' );
				try {
					$basePage = $titleParser->parseTitle( $basePageText );
				} catch ( MalformedTitleException ) {
					// Ignore invalid titles
					continue;
				}
				$prefixesByNs[$basePage->getNamespace()][$basePage->getDBkey() . '/'] = true;
			}
			$orConds = [];
			foreach ( $prefixesByNs as $ns => $prefixes ) {
				$titleExpr = null;
				foreach ( $prefixes as $prefix => $unused ) {
					$prefixExpr = $dbr->expr(
						'rc_title',
						IExpression::LIKE,
						new LikeValue(
							$prefix,
							$dbr->anyString()
						)
					);
					$titleExpr = $titleExpr ? $titleExpr->orExpr( $prefixExpr ) : $prefixExpr;
				}
				if ( $titleExpr ) {
					$orConds[] = $dbr->expr( 'rc_namespace', '=', $ns )
						->andExpr( $titleExpr );
				}
			}
			if ( $orConds ) {
				$conds[] = $dbr->makeList( $orConds, ISQLPlatform::LIST_OR );
			}
		}

		// Calculate cutoff
		$cutoff_unixtime = ConvertibleTimestamp::time() - $opts['days'] * 3600 * 24;
		$cutoff = $dbr->timestamp( $cutoff_unixtime );

		$fromValid = preg_match( '/^[0-9]{14}$/', $opts['from'] );
		if ( $fromValid && $opts['from'] > wfTimestamp( TS_MW, $cutoff ) ) {
			$cutoff = $dbr->timestamp( $opts['from'] );
		} else {
			$opts->reset( 'from' );
		}

		$conds[] = $dbr->expr( 'rc_timestamp', '>=', $cutoff );
	}

	/**
	 * Process the query
	 *
	 * @param array $tables Array of tables; see IReadableDatabase::select $table
	 * @param array $fields Array of fields; see IReadableDatabase::select $vars
	 * @param array $conds Array of conditions; see IReadableDatabase::select $conds
	 * @param array $query_options Array of query options; see IReadableDatabase::select $options
	 * @param array $join_conds Array of join conditions; see IReadableDatabase::select $join_conds
	 * @param FormOptions $opts
	 * @return bool|IResultWrapper Result or false
	 */
	protected function doMainQuery( $tables, $fields, $conds,
		$query_options, $join_conds, FormOptions $opts
	) {
		$rcQuery = RecentChange::getQueryInfo();
		$tables = array_merge( $tables, $rcQuery['tables'] );
		$fields = array_merge( $rcQuery['fields'], $fields );
		$join_conds = array_merge( $join_conds, $rcQuery['joins'] );

		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$query_options,
			'',
			$opts[ 'inverttags' ]
		);

		if (
			!$this->runMainQueryHook( $tables, $fields, $conds, $query_options, $join_conds, $opts )
		) {
			return false;
		}

		$dbr = $this->getDB();

		return $dbr->newSelectQueryBuilder()
			->tables( $tables )
			->fields( $fields )
			->conds( $conds )
			->caller( __METHOD__ )
			->options( $query_options )
			->joinConds( $join_conds )
			->fetchResultSet();
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
				$widget
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
	 * Return expression that is true when the user is or isn't registered.
	 * @param bool $isRegistered
	 * @param IReadableDatabase $dbr
	 * @return IExpression
	 */
	private function getRegisteredExpr( $isRegistered, $dbr ): IExpression {
		$expr = $dbr->expr( 'actor_user', $isRegistered ? '!=' : '=', null );
		if ( !$this->tempUserConfig->isKnown() ) {
			return $expr;
		}
		if ( $isRegistered ) {
			return $expr->andExpr( $this->tempUserConfig->getMatchCondition( $dbr,
				'actor_name', IExpression::NOT_LIKE ) );
		} else {
			return $expr->orExpr( $this->tempUserConfig->getMatchCondition( $dbr,
				'actor_name', IExpression::LIKE ) );
		}
	}

	/**
	 * Return expression that is true when the user has reached the given experience level.
	 * @param string $level 'learner' or 'experienced'
	 * @param int $now Current time as UNIX timestamp (if 0, uses actual time)
	 * @param IReadableDatabase $dbr
	 * @param bool $asNotCondition
	 * @return IExpression
	 */
	private function getExperienceExpr( $level, $now, IReadableDatabase $dbr, $asNotCondition = false ): IExpression {
		$config = $this->getConfig();

		$configSince = [
			'learner' => $config->get( MainConfigNames::LearnerMemberSince ),
			'experienced' => $config->get( MainConfigNames::ExperiencedUserMemberSince ),
		][$level];
		if ( $now === 0 ) {
			$now = ConvertibleTimestamp::time();
		}
		$secondsPerDay = 86400;
		$timeCutoff = $now - $configSince * $secondsPerDay;

		$editCutoff = [
			'learner' => $config->get( MainConfigNames::LearnerEdits ),
			'experienced' => $config->get( MainConfigNames::ExperiencedUserEdits ),
		][$level];

		if ( $asNotCondition ) {
			return $dbr->expr( 'user_editcount', '<', intval( $editCutoff ) )
				->or( 'user_registration', '>', $dbr->timestamp( $timeCutoff ) );
		}
		return $dbr->expr( 'user_editcount', '>=', intval( $editCutoff ) )->andExpr(
			// Users who don't have user_registration set are very old, so we assume they're above any cutoff
			$dbr->expr( 'user_registration', '=', null )
				->or( 'user_registration', '<=', $dbr->timestamp( $timeCutoff ) )
		);
	}

	/**
	 * Filter on users' experience levels; this will not be called if nothing is
	 * selected.
	 *
	 * @param string $specialPageClassName Class name of current special page
	 * @param IContextSource $context Context, for e.g. user
	 * @param IReadableDatabase $dbr Database, for addQuotes, makeList, and similar
	 * @param array &$tables Array of tables; see IReadableDatabase::select $table
	 * @param array &$fields Array of fields; see IReadableDatabase::select $vars
	 * @param array &$conds Array of conditions; see IReadableDatabase::select $conds
	 * @param array &$query_options Array of query options; see IReadableDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IReadableDatabase::select $join_conds
	 * @param array $selectedExpLevels The allowed active values, sorted
	 * @param int $now Current time as UNIX timestamp (if 0, uses actual time)
	 */
	public function filterOnUserExperienceLevel( $specialPageClassName, $context, $dbr,
		&$tables, &$fields, &$conds, &$query_options, &$join_conds, $selectedExpLevels, $now = 0
	) {
		$selected = array_fill_keys( $selectedExpLevels, true );

		$isUnregistered = $this->getRegisteredExpr( false, $dbr );
		$isRegistered = $this->getRegisteredExpr( true, $dbr );
		$aboveNewcomer = $this->getExperienceExpr( 'learner', $now, $dbr );
		$notAboveNewcomer = $this->getExperienceExpr( 'learner', $now, $dbr, true );
		$aboveLearner = $this->getExperienceExpr( 'experienced', $now, $dbr );
		$notAboveLearner = $this->getExperienceExpr( 'experienced', $now, $dbr, true );

		// We need to select some range of user experience levels, from the following table:
		// | Unregistered |     --------- Registered ---------     |
		// |              |  Newcomers  |  Learners  | Experienced |
		// |<------------>|<----------->|<---------->|<----------->|
		// We just need to define a condition for each of the columns, figure out which are selected,
		// and then OR them together.
		$columnConds = [
			'unregistered' => $isUnregistered,
			'registered' => $isRegistered,
			'newcomer' => $dbr->andExpr( [ $isRegistered, $notAboveNewcomer ] ),
			'learner' => $dbr->andExpr( [ $isRegistered, $aboveNewcomer, $notAboveLearner ] ),
			'experienced' => $dbr->andExpr( [ $isRegistered, $aboveLearner ] ),
		];

		// There are some cases where we can easily optimize away some queries:
		// | Unregistered |     --------- Registered ---------     |
		// |              |  Newcomers  |  Learners  | Experienced |
		// |              |<-------------------------------------->| (1)
		// |<----------------------------------------------------->| (2)

		// (1) Selecting all of "Newcomers", "Learners" and "Experienced users" is the same as "Registered".
		if (
			isset( $selected['registered'] ) ||
			( isset( $selected['newcomer'] ) && isset( $selected['learner'] ) && isset( $selected['experienced'] ) )
		) {
			unset( $selected['newcomer'], $selected['learner'], $selected['experienced'] );
			$selected['registered'] = true;
		}
		// (2) Selecting "Unregistered" and "Registered" covers all users.
		if ( isset( $selected['registered'] ) && isset( $selected['unregistered'] ) ) {
			unset( $selected['registered'], $selected['unregistered'] );
		}

		// Combine the conditions for the selected columns.
		if ( !$selected ) {
			return;
		}
		$selectedColumnConds = array_values( array_intersect_key( $columnConds, $selected ) );
		$conds[] = $dbr->orExpr( $selectedColumnConds );

		// Add necessary tables to the queries.
		$join_conds['recentchanges_actor'] = [ 'JOIN', 'actor_id=rc_actor' ];
		if ( isset( $selected['newcomer'] ) || isset( $selected['learner'] ) || isset( $selected['experienced'] ) ) {
			$tables[] = 'user';
			$join_conds['user'] = [ 'LEFT JOIN', 'actor_user=user_id' ];
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
