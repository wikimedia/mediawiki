<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\NamespaceDef;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQueryFactory;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Title\Title;
use MediaWiki\User\UserNameUtils;
use stdClass;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A query action to enumerate the recent changes that were done to the wiki.
 * Various filters are supported.
 *
 * TODO: Factor out a common base class with ApiQueryWatchlist
 *
 * @ingroup RecentChanges
 * @ingroup API
 */
class ApiQueryRecentChanges extends ApiQueryGeneratorBase {

	private CommentStore $commentStore;
	private RowCommentFormatter $commentFormatter;
	private SlotRoleRegistry $slotRoleRegistry;
	private UserNameUtils $userNameUtils;
	private LogFormatterFactory $logFormatterFactory;
	private ChangesListQueryFactory $changesListQueryFactory;
	private RecentChangeLookup $recentChangeLookup;

	/** @var string[] */
	private $formattedComments = [];

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		CommentStore $commentStore,
		RowCommentFormatter $commentFormatter,
		SlotRoleRegistry $slotRoleRegistry,
		UserNameUtils $userNameUtils,
		LogFormatterFactory $logFormatterFactory,
		ChangesListQueryFactory $changesListQueryFactory,
		RecentChangeLookup $recentChangeLookup,
	) {
		parent::__construct( $query, $moduleName, 'rc' );
		$this->commentStore = $commentStore;
		$this->commentFormatter = $commentFormatter;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->userNameUtils = $userNameUtils;
		$this->logFormatterFactory = $logFormatterFactory;
		$this->changesListQueryFactory = $changesListQueryFactory;
		$this->recentChangeLookup = $recentChangeLookup;
	}

	private bool $fld_comment = false;
	private bool $fld_parsedcomment = false;
	private bool $fld_user = false;
	private bool $fld_userid = false;
	private bool $fld_flags = false;
	private bool $fld_timestamp = false;
	private bool $fld_title = false;
	private bool $fld_ids = false;
	private bool $fld_sizes = false;
	private bool $fld_redirect = false;
	private bool $fld_patrolled = false;
	private bool $fld_loginfo = false;
	private bool $fld_tags = false;
	private bool $fld_sha1 = false;

	/**
	 * Sets internal state to include the desired properties in the output.
	 * @param array $prop Associative array of properties, only keys are used here
	 */
	public function initProperties( $prop ) {
		$this->fld_comment = isset( $prop['comment'] );
		$this->fld_parsedcomment = isset( $prop['parsedcomment'] );
		$this->fld_user = isset( $prop['user'] );
		$this->fld_userid = isset( $prop['userid'] );
		$this->fld_flags = isset( $prop['flags'] );
		$this->fld_timestamp = isset( $prop['timestamp'] );
		$this->fld_title = isset( $prop['title'] );
		$this->fld_ids = isset( $prop['ids'] );
		$this->fld_sizes = isset( $prop['sizes'] );
		$this->fld_redirect = isset( $prop['redirect'] );
		$this->fld_patrolled = isset( $prop['patrolled'] );
		$this->fld_loginfo = isset( $prop['loginfo'] );
		$this->fld_tags = isset( $prop['tags'] );
		$this->fld_sha1 = isset( $prop['sha1'] );
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * Generates and outputs the result of this query based upon the provided parameters.
	 *
	 * @param ApiPageSet|null $resultPageSet
	 */
	public function run( $resultPageSet = null ) {
		$user = $this->getUser();
		/* Get the parameters of the request. */
		$params = $this->extractRequestParams();

		$query = $this->changesListQueryFactory->newQuery();
		$query->watchlistUser( $user )
			->audience( $this->getAuthority() );

		$sources = $this->recentChangeLookup->getAllSources();

		if ( $params['dir'] === 'newer' ) {
			$query->orderBy( ChangesListQuery::SORT_TIMESTAMP_ASC );
		}
		$startTimestamp = $params['start'];
		$end = $params['end'];
		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'timestamp', 'int' ] );
			$startTimestamp = $cont[0];
			$startId = $cont[1];
		} else {
			$startId = null;
		}
		if ( $startTimestamp !== null ) {
			$query->startAt( $startTimestamp, $startId );
		}
		if ( $end !== null ) {
			$query->endAt( $end );
		}

		if ( $params['type'] !== null ) {
			$sources = array_intersect( $sources,
				$this->recentChangeLookup->convertTypeToSources( $params['type'] ) );
		}

		$title = $params['title'];
		if ( $title !== null ) {
			$titleObj = Title::newFromText( $title );
			if ( $titleObj === null || $titleObj->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $title ) ] );
			} else {
				if ( $params['namespace'] && !in_array( $titleObj->getNamespace(), $params['namespace'] ) ) {
					$this->requireMaxOneParameter( $params, 'title', 'namespace' );
				}
				$query->requireTitle( $titleObj );
			}
		} elseif ( $params['namespace'] !== null ) {
			$query->requireNamespaces( $params['namespace'] );
		}

		if ( $params['show'] !== null ) {
			$show = array_fill_keys( $params['show'], true );

			/* Check for conflicting parameters. */
			if ( ( isset( $show['minor'] ) && isset( $show['!minor'] ) )
				|| ( isset( $show['bot'] ) && isset( $show['!bot'] ) )
				|| ( isset( $show['anon'] ) && isset( $show['!anon'] ) )
				|| ( isset( $show['redirect'] ) && isset( $show['!redirect'] ) )
				|| ( isset( $show['patrolled'] ) && isset( $show['!patrolled'] ) )
				|| ( isset( $show['patrolled'] ) && isset( $show['unpatrolled'] ) )
				|| ( isset( $show['!patrolled'] ) && isset( $show['unpatrolled'] ) )
				|| ( isset( $show['autopatrolled'] ) && isset( $show['!autopatrolled'] ) )
				|| ( isset( $show['autopatrolled'] ) && isset( $show['unpatrolled'] ) )
				|| ( isset( $show['autopatrolled'] ) && isset( $show['!patrolled'] ) )
			) {
				$this->dieWithError( 'apierror-show' );
			}

			// Check permissions
			if ( $this->includesPatrollingFlags( $show ) && !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'permissiondenied' );
			}

			/* Add additional conditions to query depending upon parameters. */
			$showActions = [
				'minor' => [ 'require', 'minor', true ],
				'!minor' => [ 'exclude', 'minor', true ],
				'bot' => [ 'require', 'bot', true ],
				'!bot' => [ 'exclude', 'bot', true ],
				'anon' => [ 'exclude', 'named' ],
				'!anon' => [ 'require', 'named' ],
				'patrolled' => [ 'exclude', 'patrolled', RecentChange::PRC_UNPATROLLED ],
				'!patrolled' => [ 'require', 'patrolled', RecentChange::PRC_UNPATROLLED ],
				'redirect' => [ 'require', 'redirect', true ],
				'!redirect' => [ 'exclude', 'redirect', true ],
				'autopatrolled' => [ 'require', 'patrolled', RecentChange::PRC_AUTOPATROLLED ],
				'!autopatrolled' => [ 'exclude', 'patrolled', RecentChange::PRC_AUTOPATROLLED ],
			];
			foreach ( $show as $name => $unused ) {
				if ( isset( $showActions[$name] ) ) {
					$query->applyAction( ...$showActions[$name] );
				}
			}

			if ( isset( $show['unpatrolled'] ) ) {
				// See ChangesList::isUnpatrolled
				if ( $user->useRCPatrol() ) {
					$query->requirePatrolled( RecentChange::PRC_UNPATROLLED );
				} elseif ( $user->useNPPatrol() ) {
					$query->requirePatrolled( RecentChange::PRC_UNPATROLLED );
					$sources = array_intersect( $sources, [ RecentChange::SRC_NEW ] );
				}
			}
		}

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

		if ( $params['prop'] !== null ) {
			$prop = array_fill_keys( $params['prop'], true );

			/* Set up internal members based upon params. */
			$this->initProperties( $prop );
		}

		if ( $this->fld_user || $this->fld_userid ) {
			$query->rcUserFields();
		}

		if ( $params['user'] !== null ) {
			$query->requireUser( $params['user'] );
		}

		if ( $params['excludeuser'] !== null ) {
			$query->excludeUser( $params['excludeuser'] );
		}

		/* Add the fields we're concerned with to our query. */
		$query->fields( [
			'rc_id',
			'rc_timestamp',
			'rc_namespace',
			'rc_title',
			'rc_cur_id',
			'rc_type',
			'rc_source',
			'rc_deleted'
		] );

		/* Determine what properties we need to display. */
		if ( $params['prop'] !== null ) {
			if ( $this->fld_patrolled && !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'permissiondenied' );
			}

			/* Add fields to our query if they are specified as a needed parameter. */
			if ( $this->fld_ids ) {
				$query->fields( [ 'rc_this_oldid', 'rc_last_oldid' ] );
			}
			if ( $this->fld_flags ) {
				$query->fields( [ 'rc_minor', 'rc_type', 'rc_bot' ] );
			}
			if ( $this->fld_sizes ) {
				$query->fields( [ 'rc_old_len', 'rc_new_len' ] );
			}
			if ( $this->fld_patrolled ) {
				$query->fields( [ 'rc_patrolled', 'rc_log_type' ] );
			}
			if ( $this->fld_loginfo ) {
				$query->fields( [ 'rc_logid', 'rc_log_type', 'rc_log_action', 'rc_params' ] );
			}
			if ( $this->fld_redirect ) {
				$query->addRedirectField();
			}
		}
		if ( $resultPageSet && $params['generaterevisions'] ) {
			$query->fields( [ 'rc_this_oldid' ] );
		}
		if ( $this->fld_tags ) {
			$query->addChangeTagSummaryField();
		}
		if ( $this->fld_sha1 ) {
			$query->sha1Fields();
		}
		if ( $params['toponly'] ) {
			$query->requireLatest();
		}
		if ( $params['tag'] !== null ) {
			$query->requireChangeTags( [ $params['tag'] ] );
		}

		// Paranoia: avoid brute force searches (T19342)
		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			$query->excludeDeletedUser();
		}
		if ( $this->getRequest()->getCheck( 'namespace' ) ) {
			$query->excludeDeletedLogAction();
		}

		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			$query->commentFields();
		}

		if ( $params['slot'] !== null ) {
			// Only include changes that touch page content (i.e. RC_NEW, RC_EDIT)
			$sources = array_intersect( $sources,
				[ RecentChange::SRC_NEW, RecentChange::SRC_EDIT ] );
			$query->requireSlotChanged( $params['slot'] );
		}

		$query->requireSources( $sources )
			->limit( $params['limit'] + 1 )
			->maxExecutionTime( $this->getConfig()->get(
				MainConfigNames::MaxExecutionTimeForExpensiveQueries ) )
			->caller( __METHOD__ );

		$hookData = [];
		if ( $this->getHookContainer()->isRegistered( 'ApiQueryBaseBeforeQuery' ) ) {
			$query->legacyMutator(
				function ( &$tables, &$fields, &$conds, &$options, &$join_conds ) use ( &$hookData ) {
					$this->getHookRunner()->onApiQueryBaseBeforeQuery(
						$this, $tables, $fields, $conds,
						$options, $join_conds, $hookData );
				}
			);
		}

		$count = 0;
		/* Perform the actual query. */
		$res = $query->fetchResult()->getResultWrapper();

		$this->getHookRunner()->onApiQueryBaseAfterQuery( $this, $res, $hookData );

		// Do batch queries
		if ( $this->fld_title && $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__, 'rc' );
		}
		if ( $this->fld_parsedcomment ) {
			$this->formattedComments = $this->commentFormatter->formatItems(
				$this->commentFormatter->rows( $res )
					->indexField( 'rc_id' )
					->commentKey( 'rc_comment' )
					->namespaceField( 'rc_namespace' )
					->titleField( 'rc_title' )
			);
		}

		$revids = [];
		$titles = [];

		$result = $this->getResult();

		/* Iterate through the rows, adding data extracted from them to our query result. */
		foreach ( $res as $row ) {
			if ( $count === 0 && $resultPageSet !== null ) {
				// Set the non-continue since the list of recentchanges is
				// prone to having entries added at the start frequently.
				$this->getContinuationManager()->addGeneratorNonContinueParam(
					$this, 'continue', "$row->rc_timestamp|$row->rc_id"
				);
			}
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "$row->rc_timestamp|$row->rc_id" );
				break;
			}

			if ( $resultPageSet === null ) {
				/* Extract the data from a single row. */
				$vals = $this->extractRowInfo( $row );

				/* Add that row's data to our final output. */
				$fit = $this->processRow( $row, $vals, $hookData ) &&
					$result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', "$row->rc_timestamp|$row->rc_id" );
					break;
				}
			} elseif ( $params['generaterevisions'] ) {
				$revid = (int)$row->rc_this_oldid;
				if ( $revid > 0 ) {
					$revids[] = $revid;
				}
			} else {
				$titles[] = Title::makeTitle( $row->rc_namespace, $row->rc_title );
			}
		}

		if ( $resultPageSet === null ) {
			/* Format the result */
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'rc' );
		} elseif ( $params['generaterevisions'] ) {
			$resultPageSet->populateFromRevisionIDs( $revids );
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/**
	 * Extracts from a single sql row the data needed to describe one recent change.
	 *
	 * @param stdClass $row The row from which to extract the data.
	 * @return array An array mapping strings (descriptors) to their respective string values.
	 */
	public function extractRowInfo( $row ) {
		/* Determine the title of the page that has been changed. */
		$title = Title::makeTitle( $row->rc_namespace, $row->rc_title );
		$user = $this->getUser();

		/* Our output data. */
		$vals = [];

		$vals['type'] = $this->recentChangeLookup->convertSourceToType( $row->rc_source );
		$isLog = $row->rc_source === RecentChange::SRC_LOG;

		$anyHidden = false;

		/* Create a new entry in the result for the title. */
		if ( $this->fld_title || $this->fld_ids ) {
			if ( $isLog && ( $row->rc_deleted & LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( !$isLog ||
				LogEventsList::userCanBitfield( $row->rc_deleted, LogPage::DELETED_ACTION, $user )
			) {
				if ( $this->fld_title ) {
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $this->fld_ids ) {
					$vals['pageid'] = (int)$row->rc_cur_id;
					$vals['revid'] = (int)$row->rc_this_oldid;
					$vals['old_revid'] = (int)$row->rc_last_oldid;
				}
			}
		}

		if ( $this->fld_ids ) {
			$vals['rcid'] = (int)$row->rc_id;
		}

		/* Add user data and 'anon' flag, if user is anonymous. */
		if ( $this->fld_user || $this->fld_userid ) {
			if ( $row->rc_deleted & RevisionRecord::DELETED_USER ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield( $row->rc_deleted, RevisionRecord::DELETED_USER, $user ) ) {
				if ( $this->fld_user ) {
					$vals['user'] = $row->rc_user_text;
				}

				if ( $this->fld_userid ) {
					$vals['userid'] = (int)$row->rc_user;
				}

				if ( isset( $row->rc_user_text ) && $this->userNameUtils->isTemp( $row->rc_user_text ) ) {
					$vals['temp'] = true;
				}

				if ( !$row->rc_user ) {
					$vals['anon'] = true;
				}
			}
		}

		/* Add flags, such as new, minor, bot. */
		if ( $this->fld_flags ) {
			$vals['bot'] = (bool)$row->rc_bot;
			$vals['new'] = $row->rc_source == RecentChange::SRC_NEW;
			$vals['minor'] = (bool)$row->rc_minor;
		}

		/* Add sizes of each revision. (Only available on 1.10+) */
		if ( $this->fld_sizes ) {
			$vals['oldlen'] = (int)$row->rc_old_len;
			$vals['newlen'] = (int)$row->rc_new_len;
		}

		/* Add the timestamp. */
		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->rc_timestamp );
		}

		/* Add edit summary / log summary. */
		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			if ( $row->rc_deleted & RevisionRecord::DELETED_COMMENT ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield(
				$row->rc_deleted, RevisionRecord::DELETED_COMMENT, $user
			) ) {
				if ( $this->fld_comment ) {
					$vals['comment'] = $this->commentStore->getComment( 'rc_comment', $row )->text;
				}

				if ( $this->fld_parsedcomment ) {
					$vals['parsedcomment'] = $this->formattedComments[$row->rc_id];
				}
			}
		}

		if ( $this->fld_redirect ) {
			$vals['redirect'] = (bool)$row->page_is_redirect;
		}

		/* Add the patrolled flag */
		if ( $this->fld_patrolled ) {
			$vals['patrolled'] = $row->rc_patrolled != RecentChange::PRC_UNPATROLLED;
			$vals['unpatrolled'] = ChangesList::isUnpatrolled( $row, $user );
			$vals['autopatrolled'] = $row->rc_patrolled == RecentChange::PRC_AUTOPATROLLED;
		}

		if ( $this->fld_loginfo && $row->rc_source == RecentChange::SRC_LOG ) {
			if ( $row->rc_deleted & LogPage::DELETED_ACTION ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( LogEventsList::userCanBitfield( $row->rc_deleted, LogPage::DELETED_ACTION, $user ) ) {
				$vals['logid'] = (int)$row->rc_logid;
				$vals['logtype'] = $row->rc_log_type;
				$vals['logaction'] = $row->rc_log_action;
				$vals['logparams'] = $this->logFormatterFactory->newFromRow( $row )->formatParametersForApi();
			}
		}

		if ( $this->fld_tags ) {
			if ( $row->ts_tags ) {
				$tags = explode( ',', $row->ts_tags );
				ApiResult::setIndexedTagName( $tags, 'tag' );
				$vals['tags'] = $tags;
			} else {
				$vals['tags'] = [];
			}
		}

		if ( $this->fld_sha1 && $row->rev_slot_pairs !== null ) {
			if ( $row->rev_deleted & RevisionRecord::DELETED_TEXT ) {
				$vals['sha1hidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield(
				$row->rev_deleted, RevisionRecord::DELETED_TEXT, $user
			) ) {
				$combinedBase36 = '';
				if ( $row->rev_slot_pairs !== '' ) {
					$items = explode( ',', $row->rev_slot_pairs );
					$slotHashes = [];
					foreach ( $items as $item ) {
						$parts = explode( ':', $item );
						$slotHashes[$parts[0]] = $parts[1];
					}
					ksort( $slotHashes );

					$accu = null;
					foreach ( $slotHashes as $slotHash ) {
						$accu = $accu === null
							? $slotHash
							: SlotRecord::base36Sha1( $accu . $slotHash );
					}
					$combinedBase36 = $accu ?? SlotRecord::base36Sha1( '' );
				}

				$vals['sha1'] = $combinedBase36 !== ''
					? \Wikimedia\base_convert( $combinedBase36, 36, 16, 40 )
					: '';
			}
		}

		if ( $anyHidden && ( $row->rc_deleted & RevisionRecord::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		return $vals;
	}

	/**
	 * @param array $flagsArray flipped array (string flags are keys)
	 * @return bool
	 */
	private function includesPatrollingFlags( array $flagsArray ) {
		return isset( $flagsArray['patrolled'] ) ||
			isset( $flagsArray['!patrolled'] ) ||
			isset( $flagsArray['unpatrolled'] ) ||
			isset( $flagsArray['autopatrolled'] ) ||
			isset( $flagsArray['!autopatrolled'] );
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		if ( isset( $params['show'] ) &&
			$this->includesPatrollingFlags( array_fill_keys( $params['show'], true ) )
		) {
			return 'private';
		}
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}
		if ( $params['prop'] !== null && in_array( 'parsedcomment', $params['prop'] ) ) {
			// MediaWiki\CommentFormatter\CommentFormatter::formatItems() calls wfMessage() among other things
			return 'anon-public-user-private';
		}

		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$slotRoles = $this->slotRoleRegistry->getKnownRoles();
		sort( $slotRoles, SORT_STRING );

		return [
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'older',
				ParamValidator::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'newer' => 'api-help-paramvalue-direction-newer',
					'older' => 'api-help-paramvalue-direction-older',
				],
			],
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace',
				NamespaceDef::PARAM_EXTRA_NAMESPACES => [ NS_MEDIA, NS_SPECIAL ],
			],
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_RETURN_OBJECT => true,
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'excludeuser' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_RETURN_OBJECT => true,
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'tag' => null,
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'title|timestamp|ids',
				ParamValidator::PARAM_TYPE => [
					'user',
					'userid',
					'comment',
					'parsedcomment',
					'flags',
					'timestamp',
					'title',
					'ids',
					'sizes',
					'redirect',
					'patrolled',
					'loginfo',
					'tags',
					'sha1',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'show' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
					'minor',
					'!minor',
					'bot',
					'!bot',
					'anon',
					'!anon',
					'redirect',
					'!redirect',
					'patrolled',
					'!patrolled',
					'unpatrolled',
					'autopatrolled',
					'!autopatrolled',
				]
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			// TODO: deprecate and use rc_source directly, here and in the result
			'type' => [
				ParamValidator::PARAM_DEFAULT => 'edit|new|log|categorize',
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => RecentChange::getChangeTypes()
			],
			'toponly' => false,
			'title' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'generaterevisions' => false,
			'slot' => [
				ParamValidator::PARAM_TYPE => $slotRoles
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=recentchanges'
				=> 'apihelp-query+recentchanges-example-simple',
			'action=query&generator=recentchanges&grcshow=!patrolled&prop=info'
				=> 'apihelp-query+recentchanges-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Recentchanges';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryRecentChanges::class, 'ApiQueryRecentChanges' );
