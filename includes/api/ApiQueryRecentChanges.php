<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\NamespaceDef;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserNameUtils;
use stdClass;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\RawSQLExpression;

/**
 * A query action to enumerate the recent changes that were done to the wiki.
 * Various filters are supported.
 *
 * @ingroup RecentChanges
 * @ingroup API
 */
class ApiQueryRecentChanges extends ApiQueryGeneratorBase {

	private CommentStore $commentStore;
	private RowCommentFormatter $commentFormatter;
	private NameTableStore $changeTagDefStore;
	private ChangeTagsStore $changeTagsStore;
	private NameTableStore $slotRoleStore;
	private SlotRoleRegistry $slotRoleRegistry;
	private UserNameUtils $userNameUtils;
	private TempUserConfig $tempUserConfig;
	private LogFormatterFactory $logFormatterFactory;

	/** @var string[] */
	private $formattedComments = [];

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		CommentStore $commentStore,
		RowCommentFormatter $commentFormatter,
		NameTableStore $changeTagDefStore,
		ChangeTagsStore $changeTagsStore,
		NameTableStore $slotRoleStore,
		SlotRoleRegistry $slotRoleRegistry,
		UserNameUtils $userNameUtils,
		TempUserConfig $tempUserConfig,
		LogFormatterFactory $logFormatterFactory
	) {
		parent::__construct( $query, $moduleName, 'rc' );
		$this->commentStore = $commentStore;
		$this->commentFormatter = $commentFormatter;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->changeTagsStore = $changeTagsStore;
		$this->slotRoleStore = $slotRoleStore;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->userNameUtils = $userNameUtils;
		$this->tempUserConfig = $tempUserConfig;
		$this->logFormatterFactory = $logFormatterFactory;
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
		$db = $this->getDB();
		$user = $this->getUser();
		/* Get the parameters of the request. */
		$params = $this->extractRequestParams();

		/* Build our basic query. Namely, something along the lines of:
		 * SELECT * FROM recentchanges WHERE rc_timestamp > $start
		 *   AND rc_timestamp < $end AND rc_namespace = $namespace
		 */
		$this->addTables( 'recentchanges' );
		$this->addTimestampWhereRange( 'rc_timestamp', $params['dir'], $params['start'], $params['end'] );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'timestamp', 'int' ] );
			$op = $params['dir'] === 'older' ? '<=' : '>=';
			$this->addWhere( $db->buildComparison( $op, [
				'rc_timestamp' => $db->timestamp( $cont[0] ),
				'rc_id' => $cont[1],
			] ) );
		}

		$order = $params['dir'] === 'older' ? 'DESC' : 'ASC';
		$this->addOption( 'ORDER BY', [
			"rc_timestamp $order",
			"rc_id $order",
		] );

		if ( $params['type'] !== null ) {
			$this->addWhereFld( 'rc_type', RecentChange::parseToRCType( $params['type'] ) );
		}

		$title = $params['title'];
		if ( $title !== null ) {
			$titleObj = Title::newFromText( $title );
			if ( $titleObj === null || $titleObj->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $title ) ] );
			} elseif ( $params['namespace'] && !in_array( $titleObj->getNamespace(), $params['namespace'] ) ) {
				$this->requireMaxOneParameter( $params, 'title', 'namespace' );
			}
			$this->addWhereFld( 'rc_namespace', $titleObj->getNamespace() );
			$this->addWhereFld( 'rc_title', $titleObj->getDBkey() );
		} else {
			$this->addWhereFld( 'rc_namespace', $params['namespace'] );
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
			$this->addWhereIf( [ 'rc_minor' => 0 ], isset( $show['!minor'] ) );
			$this->addWhereIf( $db->expr( 'rc_minor', '!=', 0 ), isset( $show['minor'] ) );
			$this->addWhereIf( [ 'rc_bot' => 0 ], isset( $show['!bot'] ) );
			$this->addWhereIf( $db->expr( 'rc_bot', '!=', 0 ), isset( $show['bot'] ) );
			if ( isset( $show['anon'] ) || isset( $show['!anon'] ) ) {
				$this->addTables( 'actor', 'actor' );
				$this->addJoinConds( [ 'actor' => [ 'JOIN', 'actor_id=rc_actor' ] ] );

				if ( $this->tempUserConfig->isKnown() ) {
					$isAnon = isset( $show['anon'] );
					$anonExpr = $db->expr( 'actor_user', $isAnon ? '=' : '!=', null );
					if ( $isAnon ) {
						$anonExpr = $anonExpr->orExpr( $this->tempUserConfig->getMatchCondition(
							$db,
							'actor_name',
							IExpression::LIKE
						) );
					} else {
						$anonExpr = $anonExpr->andExpr( $this->tempUserConfig->getMatchCondition(
							$db,
							'actor_name',
							IExpression::NOT_LIKE
						) );
					}
					$this->addWhere( $anonExpr );
				} else {
					$this->addWhereIf(
						[ 'actor_user' => null ], isset( $show['anon'] )
					);
					$this->addWhereIf(
						$db->expr( 'actor_user', '!=', null ), isset( $show['!anon'] )
					);
				}
			}
			$this->addWhereIf( [ 'rc_patrolled' => 0 ], isset( $show['!patrolled'] ) );
			$this->addWhereIf( $db->expr( 'rc_patrolled', '!=', 0 ), isset( $show['patrolled'] ) );
			$this->addWhereIf( [ 'page_is_redirect' => 1 ], isset( $show['redirect'] ) );

			if ( isset( $show['unpatrolled'] ) ) {
				// See ChangesList::isUnpatrolled
				if ( $user->useRCPatrol() ) {
					$this->addWhereFld( 'rc_patrolled', RecentChange::PRC_UNPATROLLED );
				} elseif ( $user->useNPPatrol() ) {
					$this->addWhereFld( 'rc_patrolled', RecentChange::PRC_UNPATROLLED );
					$this->addWhereFld( 'rc_type', RC_NEW );
				}
			}

			$this->addWhereIf(
				$db->expr( 'rc_patrolled', '!=', RecentChange::PRC_AUTOPATROLLED ),
				isset( $show['!autopatrolled'] )
			);
			$this->addWhereIf(
				[ 'rc_patrolled' => RecentChange::PRC_AUTOPATROLLED ],
				isset( $show['autopatrolled'] )
			);

			// Don't throw log entries out the window here
			$this->addWhereIf(
				[ 'page_is_redirect' => [ 0, null ] ],
				isset( $show['!redirect'] )
			);
		}

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );

		if ( $params['prop'] !== null ) {
			$prop = array_fill_keys( $params['prop'], true );

			/* Set up internal members based upon params. */
			$this->initProperties( $prop );
		}

		if ( $this->fld_user
			|| $this->fld_userid
			|| $params['user'] !== null
			|| $params['excludeuser'] !== null
		) {
			$this->addTables( 'actor', 'actor' );
			$this->addFields( [ 'actor_name', 'actor_user', 'rc_actor' ] );
			$this->addJoinConds( [ 'actor' => [ 'JOIN', 'actor_id=rc_actor' ] ] );
		}

		if ( $params['user'] !== null ) {
			$this->addWhereFld( 'actor_name', $params['user'] );
		}

		if ( $params['excludeuser'] !== null ) {
			$this->addWhere( $db->expr( 'actor_name', '!=', $params['excludeuser'] ) );
		}

		/* Add the fields we're concerned with to our query. */
		$this->addFields( [
			'rc_id',
			'rc_timestamp',
			'rc_namespace',
			'rc_title',
			'rc_cur_id',
			'rc_type',
			'rc_source',
			'rc_deleted'
		] );

		$showRedirects = false;
		/* Determine what properties we need to display. */
		if ( $params['prop'] !== null ) {
			if ( $this->fld_patrolled && !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'permissiondenied' );
			}

			/* Add fields to our query if they are specified as a needed parameter. */
			$this->addFieldsIf( [ 'rc_this_oldid', 'rc_last_oldid' ], $this->fld_ids );
			$this->addFieldsIf( [ 'rc_minor', 'rc_type', 'rc_bot' ], $this->fld_flags );
			$this->addFieldsIf( [ 'rc_old_len', 'rc_new_len' ], $this->fld_sizes );
			$this->addFieldsIf( [ 'rc_patrolled', 'rc_log_type' ], $this->fld_patrolled );
			$this->addFieldsIf(
				[ 'rc_logid', 'rc_log_type', 'rc_log_action', 'rc_params' ],
				$this->fld_loginfo
			);
			$showRedirects = $this->fld_redirect || isset( $show['redirect'] )
				|| isset( $show['!redirect'] );
		}
		$this->addFieldsIf( [ 'rc_this_oldid' ],
			$resultPageSet && $params['generaterevisions'] );

		if ( $this->fld_tags ) {
			$this->addFields( [
				'ts_tags' => $this->changeTagsStore->makeTagSummarySubquery( 'recentchanges' )
			] );
		}

		if ( $this->fld_sha1 ) {
			$this->addTables( 'revision' );
			$this->addJoinConds( [ 'revision' => [ 'LEFT JOIN',
				[ 'rc_this_oldid=rev_id' ] ] ] );
			$this->addFields( [ 'rev_sha1', 'rev_deleted' ] );
		}

		if ( $params['toponly'] || $showRedirects ) {
			$this->addTables( 'page' );
			$this->addJoinConds( [ 'page' => [ 'LEFT JOIN',
				[ 'rc_namespace=page_namespace', 'rc_title=page_title' ] ] ] );
			$this->addFields( 'page_is_redirect' );

			if ( $params['toponly'] ) {
				$this->addWhere( 'rc_this_oldid = page_latest' );
			}
		}

		if ( $params['tag'] !== null ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds( [ 'change_tag' => [ 'JOIN', [ 'rc_id=ct_rc_id' ] ] ] );
			try {
				$this->addWhereFld( 'ct_tag_id', $this->changeTagDefStore->getId( $params['tag'] ) );
			} catch ( NameTableAccessException ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}

		// Paranoia: avoid brute force searches (T19342)
		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
				$bitmask = RevisionRecord::DELETED_USER;
			} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere( $db->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask" );
			}
		}
		if ( $this->getRequest()->getCheck( 'namespace' ) ) {
			// LogPage::DELETED_ACTION hides the affected page, too.
			if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
				$bitmask = LogPage::DELETED_ACTION;
			} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere(
					$db->expr( 'rc_type', '!=', RC_LOG )
						->orExpr( new RawSQLExpression( $db->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask" ) )
				);
			}
		}

		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			$commentQuery = $this->commentStore->getJoin( 'rc_comment' );
			$this->addTables( $commentQuery['tables'] );
			$this->addFields( $commentQuery['fields'] );
			$this->addJoinConds( $commentQuery['joins'] );
		}

		if ( $params['slot'] !== null ) {
			try {
				$slotId = $this->slotRoleStore->getId( $params['slot'] );
			} catch ( NameTableAccessException ) {
				$slotId = null;
			}

			$this->addTables( [
				'slot' => 'slots', 'parent_slot' => 'slots'
			] );
			$this->addJoinConds( [
				'slot' => [ 'LEFT JOIN', [
					'rc_this_oldid = slot.slot_revision_id',
					'slot.slot_role_id' => $slotId,
				] ],
				'parent_slot' => [ 'LEFT JOIN', [
					'rc_last_oldid = parent_slot.slot_revision_id',
					'parent_slot.slot_role_id' => $slotId,
				] ]
			] );
			// Detecting whether the slot has been touched as follows:
			// 1. if slot_origin=slot_revision_id then the slot has been newly created or edited
			// with this revision
			// 2. otherwise if the content of a slot is different to the content of its parent slot,
			// then the content of the slot has been changed in this revision
			// (probably by a revert)
			$this->addWhere( $db->orExpr( [
				new RawSQLExpression( 'slot.slot_origin = slot.slot_revision_id' ),
				new RawSQLExpression( 'slot.slot_content_id != parent_slot.slot_content_id' ),
				$db->expr( 'slot.slot_content_id', '=', null )->and( 'parent_slot.slot_content_id', '!=', null ),
				$db->expr( 'slot.slot_content_id', '!=', null )->and( 'parent_slot.slot_content_id', '=', null ),
			] ) );
			// Only include changes that touch page content (i.e. RC_NEW, RC_EDIT)
			$changeTypes = RecentChange::parseToRCType(
				array_intersect( $params['type'], [ 'new', 'edit' ] )
			);
			if ( count( $changeTypes ) ) {
				$this->addWhereFld( 'rc_type', $changeTypes );
			} else {
				// Calling $this->addWhere() with an empty array does nothing, so explicitly
				// add an unsatisfiable condition
				$this->addWhere( [ 'rc_type' => null ] );
			}
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addOption(
			'MAX_EXECUTION_TIME',
			$this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries )
		);

		$hookData = [];
		$count = 0;
		/* Perform the actual query. */
		$res = $this->select( __METHOD__, [], $hookData );

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

		$type = (int)$row->rc_type;
		$vals['type'] = RecentChange::parseFromRCType( $type );

		$anyHidden = false;

		/* Create a new entry in the result for the title. */
		if ( $this->fld_title || $this->fld_ids ) {
			if ( $type === RC_LOG && ( $row->rc_deleted & LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( $type !== RC_LOG ||
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
					$vals['user'] = $row->actor_name;
				}

				if ( $this->fld_userid ) {
					$vals['userid'] = (int)$row->actor_user;
				}

				if ( isset( $row->actor_name ) && $this->userNameUtils->isTemp( $row->actor_name ) ) {
					$vals['temp'] = true;
				}

				if ( !$row->actor_user ) {
					$vals['anon'] = true;
				}
			}
		}

		/* Add flags, such as new, minor, bot. */
		if ( $this->fld_flags ) {
			$vals['bot'] = (bool)$row->rc_bot;
			$vals['new'] = $row->rc_type == RC_NEW;
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

		if ( $this->fld_sha1 && $row->rev_sha1 !== null ) {
			if ( $row->rev_deleted & RevisionRecord::DELETED_TEXT ) {
				$vals['sha1hidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield(
				$row->rev_deleted, RevisionRecord::DELETED_TEXT, $user
			) ) {
				if ( $row->rev_sha1 !== '' ) {
					$vals['sha1'] = \Wikimedia\base_convert( $row->rev_sha1, 36, 16, 40 );
				} else {
					$vals['sha1'] = '';
				}
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
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'excludeuser' => [
				ParamValidator::PARAM_TYPE => 'user',
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
