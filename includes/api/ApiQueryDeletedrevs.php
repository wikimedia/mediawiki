<?php
/**
 * Copyright © 2007 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\Subquery;

/**
 * Query module to enumerate all deleted revisions.
 *
 * @ingroup API
 * @deprecated since 1.25
 */
class ApiQueryDeletedrevs extends ApiQueryBase {

	private CommentStore $commentStore;
	private RowCommentFormatter $commentFormatter;
	private RevisionStore $revisionStore;
	private NameTableStore $changeTagDefStore;
	private ChangeTagsStore $changeTagsStore;
	private LinkBatchFactory $linkBatchFactory;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		CommentStore $commentStore,
		RowCommentFormatter $commentFormatter,
		RevisionStore $revisionStore,
		NameTableStore $changeTagDefStore,
		ChangeTagsStore $changeTagsStore,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( $query, $moduleName, 'dr' );
		$this->commentStore = $commentStore;
		$this->commentFormatter = $commentFormatter;
		$this->revisionStore = $revisionStore;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->changeTagsStore = $changeTagsStore;
		$this->linkBatchFactory = $linkBatchFactory;
	}

	public function execute() {
		// Before doing anything at all, let's check permissions
		$this->checkUserRightsAny( 'deletedhistory' );

		$this->addDeprecation( 'apiwarn-deprecation-deletedrevs', 'action=query&list=deletedrevs' );

		$user = $this->getUser();
		$db = $this->getDB();
		$params = $this->extractRequestParams( false );
		$prop = array_fill_keys( $params['prop'], true );
		$fld_parentid = isset( $prop['parentid'] );
		$fld_revid = isset( $prop['revid'] );
		$fld_user = isset( $prop['user'] );
		$fld_userid = isset( $prop['userid'] );
		$fld_comment = isset( $prop['comment'] );
		$fld_parsedcomment = isset( $prop['parsedcomment'] );
		$fld_minor = isset( $prop['minor'] );
		$fld_len = isset( $prop['len'] );
		$fld_sha1 = isset( $prop['sha1'] );
		$fld_content = isset( $prop['content'] );
		$fld_token = isset( $prop['token'] );
		$fld_tags = isset( $prop['tags'] );

		// If we're in a mode that breaks the same-origin policy, no tokens can
		// be obtained
		if ( $this->lacksSameOriginSecurity() ||
			// If user can't undelete, no tokens
			!$this->getAuthority()->isAllowed( 'undelete' )
		) {
			$fld_token = false;
		}

		$result = $this->getResult();
		$pageSet = $this->getPageSet();
		$titles = $pageSet->getPages();

		// This module operates in three modes:
		// 'revs': List deleted revs for certain titles (1)
		// 'user': List deleted revs by a certain user (2)
		// 'all': List all deleted revs in NS (3)
		$mode = 'all';
		if ( count( $titles ) > 0 ) {
			$mode = 'revs';
		} elseif ( $params['user'] !== null ) {
			$mode = 'user';
		}

		if ( $mode == 'revs' || $mode == 'user' ) {
			// Ignore namespace and unique due to inability to know whether they were purposely set
			foreach ( [ 'from', 'to', 'prefix', /*'namespace', 'unique'*/ ] as $p ) {
				if ( $params[$p] !== null ) {
					$this->dieWithError( [ 'apierror-deletedrevs-param-not-1-2', $p ], 'badparams' );
				}
			}
		} else {
			foreach ( [ 'start', 'end' ] as $p ) {
				if ( $params[$p] !== null ) {
					$this->dieWithError( [ 'apierror-deletedrevs-param-not-3', $p ], 'badparams' );
				}
			}
		}

		if ( $params['user'] !== null && $params['excludeuser'] !== null ) {
			$this->dieWithError( 'user and excludeuser cannot be used together', 'badparams' );
		}

		$arQuery = $this->revisionStore->getArchiveQueryInfo();
		$this->addTables( $arQuery['tables'] );
		$this->addFields( $arQuery['fields'] );
		$this->addJoinConds( $arQuery['joins'] );
		$this->addFields( [ 'ar_title', 'ar_namespace' ] );

		if ( $fld_tags ) {
			$this->addFields( [
				'ts_tags' => $this->changeTagsStore->makeTagSummarySubquery( 'archive' )
			] );
		}

		if ( $fld_sha1 ) {
			$pairExpr = $db->buildGroupConcat(
				$db->buildConcat( [ 'sr.role_name', $db->addQuotes( ':' ), 'c.content_sha1' ] ),
				','
			);
			$sha1Subquery = $db->newSelectQueryBuilder()
				->select( [
					'ar_rev_id',
					'ar_deleted',
					'ar_slot_pairs' => $pairExpr,
				] )
				->from( 'archive' )
				->join( 'slots', 's', [ 'ar_rev_id = s.slot_revision_id' ] )
				->join( 'content', 'c', [ 's.slot_content_id = c.content_id' ] )
				->join( 'slot_roles', 'sr', [ 's.slot_role_id = sr.role_id' ] )
				->groupBy( [ 'ar_rev_id', 'ar_deleted' ] )
				->caller( __METHOD__ )
				->getSQL();

			$this->addTables( [ 'arsha1' => new Subquery( $sha1Subquery ) ] );
			$this->addFields( [
				'ar_deleted' => 'arsha1.ar_deleted',
				'ar_slot_pairs' => 'arsha1.ar_slot_pairs'
			] );
			$this->addJoinConds( [ 'arsha1' => [ 'LEFT JOIN', [ 'ar_rev_id = arsha1.ar_rev_id' ] ] ] );
		}

		if ( $params['tag'] !== null ) {
			$this->addTables( 'change_tag' );
			$this->addJoinConds(
				[ 'change_tag' => [ 'JOIN', [ 'ar_rev_id=ct_rev_id' ] ] ]
			);
			try {
				$this->addWhereFld( 'ct_tag_id', $this->changeTagDefStore->getId( $params['tag'] ) );
			} catch ( NameTableAccessException ) {
				// Return nothing.
				$this->addWhere( '1=0' );
			}
		}

		// This means stricter restrictions
		if ( $fld_content ) {
			$this->checkUserRightsAny( [ 'deletedtext', 'undelete' ] );
		}
		// Check limits
		$userMax = $fld_content ? ApiBase::LIMIT_SML1 : ApiBase::LIMIT_BIG1;
		$botMax = $fld_content ? ApiBase::LIMIT_SML2 : ApiBase::LIMIT_BIG2;

		$limit = $params['limit'];

		if ( $limit == 'max' ) {
			$limit = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			$this->getResult()->addParsedLimit( $this->getModuleName(), $limit );
		}

		$limit = $this->getMain()->getParamValidator()->validateValue(
			$this, 'limit', $limit, [
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => $userMax,
				IntegerDef::PARAM_MAX2 => $botMax,
				IntegerDef::PARAM_IGNORE_RANGE => true,
			]
		);

		if ( $fld_token ) {
			// Undelete tokens are identical for all pages, so we cache one here
			$token = $user->getEditToken( '', $this->getMain()->getRequest() );
		}

		$dir = $params['dir'];

		// We need a custom WHERE clause that matches all titles.
		if ( $mode == 'revs' ) {
			$lb = $this->linkBatchFactory->newLinkBatch( $titles );
			$where = $lb->constructSet( 'ar', $db );
			$this->addWhere( $where );
		} elseif ( $mode == 'all' ) {
			$this->addWhereFld( 'ar_namespace', $params['namespace'] );

			$from = $params['from'] === null
				? null
				: $this->titlePartToKey( $params['from'], $params['namespace'] );
			$to = $params['to'] === null
				? null
				: $this->titlePartToKey( $params['to'], $params['namespace'] );
			$this->addWhereRange( 'ar_title', $dir, $from, $to );

			if ( isset( $params['prefix'] ) ) {
				$this->addWhere(
					$db->expr(
						'ar_title',
						IExpression::LIKE,
						new LikeValue(
							$this->titlePartToKey( $params['prefix'], $params['namespace'] ),
							$db->anyString()
						)
					)
				);
			}
		}

		if ( $params['user'] !== null ) {
			// We already join on actor due to getArchiveQueryInfo()
			$this->addWhereFld( 'actor_name', $params['user'] );
		} elseif ( $params['excludeuser'] !== null ) {
			$this->addWhere( $db->expr( 'actor_name', '!=', $params['excludeuser'] ) );
		}

		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			// Paranoia: avoid brute force searches (T19342)
			// (shouldn't be able to get here without 'deletedhistory', but
			// check it again just in case)
			if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
				$bitmask = RevisionRecord::DELETED_USER;
			} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->addWhere( $db->bitAnd( 'ar_deleted', $bitmask ) . " != $bitmask" );
			}
		}

		if ( $params['continue'] !== null ) {
			$op = ( $dir == 'newer' ? '>=' : '<=' );
			if ( $mode == 'all' || $mode == 'revs' ) {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'string', 'timestamp', 'int' ] );
				$this->addWhere( $db->buildComparison( $op, [
					'ar_namespace' => $cont[0],
					'ar_title' => $cont[1],
					'ar_timestamp' => $db->timestamp( $cont[2] ),
					'ar_id' => $cont[3],
				] ) );
			} else {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'timestamp', 'int' ] );
				$this->addWhere( $db->buildComparison( $op, [
					'ar_timestamp' => $db->timestamp( $cont[0] ),
					'ar_id' => $cont[1],
				] ) );
			}
		}

		$this->addOption( 'LIMIT', $limit + 1 );
		if ( $mode == 'all' ) {
			if ( $params['unique'] ) {
				// @todo Does this work on non-MySQL?
				$this->addOption( 'GROUP BY', 'ar_title' );
			} else {
				$sort = ( $dir == 'newer' ? '' : ' DESC' );
				$this->addOption( 'ORDER BY', [
					'ar_title' . $sort,
					'ar_timestamp' . $sort,
					'ar_id' . $sort,
				] );
			}
		} else {
			if ( $mode == 'revs' ) {
				// Sort by ns and title in the same order as timestamp for efficiency
				$this->addWhereRange( 'ar_namespace', $dir, null, null );
				$this->addWhereRange( 'ar_title', $dir, null, null );
			}
			$this->addTimestampWhereRange( 'ar_timestamp', $dir, $params['start'], $params['end'] );
			// Include in ORDER BY for uniqueness
			$this->addWhereRange( 'ar_id', $dir, null, null );
		}
		$res = $this->select( __METHOD__ );

		$formattedComments = [];
		if ( $fld_parsedcomment ) {
			$formattedComments = $this->commentFormatter->formatItems(
				$this->commentFormatter->rows( $res )
					->indexField( 'ar_id' )
					->commentKey( 'ar_comment' )
					->namespaceField( 'ar_namespace' )
					->titleField( 'ar_title' )
			);
		}

		$pageMap = []; // Maps ns&title to (fake) pageid
		$count = 0;
		$newPageID = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've had enough
				if ( $mode == 'all' || $mode == 'revs' ) {
					$this->setContinueEnumParameter( 'continue',
						"$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
					);
				} else {
					$this->setContinueEnumParameter( 'continue', "$row->ar_timestamp|$row->ar_id" );
				}
				break;
			}

			$rev = [];
			$anyHidden = false;

			$rev['timestamp'] = wfTimestamp( TS_ISO_8601, $row->ar_timestamp );
			if ( $fld_revid ) {
				$rev['revid'] = (int)$row->ar_rev_id;
			}
			if ( $fld_parentid && $row->ar_parent_id !== null ) {
				$rev['parentid'] = (int)$row->ar_parent_id;
			}
			if ( $fld_user || $fld_userid ) {
				if ( $row->ar_deleted & RevisionRecord::DELETED_USER ) {
					$rev['userhidden'] = true;
					$anyHidden = true;
				}
				if ( RevisionRecord::userCanBitfield(
					$row->ar_deleted,
					RevisionRecord::DELETED_USER,
					$user
				) ) {
					if ( $fld_user ) {
						$rev['user'] = $row->ar_user_text;
					}
					if ( $fld_userid ) {
						$rev['userid'] = (int)$row->ar_user;
					}
				}
			}

			if ( $fld_comment || $fld_parsedcomment ) {
				if ( $row->ar_deleted & RevisionRecord::DELETED_COMMENT ) {
					$rev['commenthidden'] = true;
					$anyHidden = true;
				}
				if ( RevisionRecord::userCanBitfield(
					$row->ar_deleted,
					RevisionRecord::DELETED_COMMENT,
					$user
				) ) {
					$comment = $this->commentStore->getComment( 'ar_comment', $row )->text;
					if ( $fld_comment ) {
						$rev['comment'] = $comment;
					}
					if ( $fld_parsedcomment ) {
						$rev['parsedcomment'] = $formattedComments[$row->ar_id];
					}
				}
			}

			if ( $fld_minor ) {
				$rev['minor'] = $row->ar_minor_edit == 1;
			}
			if ( $fld_len ) {
				$rev['len'] = $row->ar_len;
			}
			if ( $fld_sha1 ) {
				if ( $row->ar_deleted & RevisionRecord::DELETED_TEXT ) {
					$rev['sha1hidden'] = true;
					$anyHidden = true;
				}
				if ( RevisionRecord::userCanBitfield(
					$row->ar_deleted,
					RevisionRecord::DELETED_TEXT,
					$user
				) ) {
					if ( $row->ar_slot_pairs !== null ) {
						$combinedBase36 = '';
						if ( $row->ar_slot_pairs !== '' ) {
							$items = explode( ',', $row->ar_slot_pairs );
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

						$rev['sha1'] = $combinedBase36 !== ''
							? \Wikimedia\base_convert( $combinedBase36, 36, 16, 40 )
							: '';
					}
				}
			}
			if ( $fld_content ) {
				if ( $row->ar_deleted & RevisionRecord::DELETED_TEXT ) {
					$rev['texthidden'] = true;
					$anyHidden = true;
				}
				if ( RevisionRecord::userCanBitfield(
					$row->ar_deleted,
					RevisionRecord::DELETED_TEXT,
					$user
				) ) {
					ApiResult::setContentValue( $rev, 'text',
						$this->revisionStore->newRevisionFromArchiveRow( $row )
							->getContent( SlotRecord::MAIN )->serialize() );
				}
			}

			if ( $fld_tags ) {
				if ( $row->ts_tags ) {
					$tags = explode( ',', $row->ts_tags );
					ApiResult::setIndexedTagName( $tags, 'tag' );
					$rev['tags'] = $tags;
				} else {
					$rev['tags'] = [];
				}
			}

			if ( $anyHidden && ( $row->ar_deleted & RevisionRecord::DELETED_RESTRICTED ) ) {
				$rev['suppressed'] = true;
			}

			if ( !isset( $pageMap[$row->ar_namespace][$row->ar_title] ) ) {
				$pageID = $newPageID++;
				$pageMap[$row->ar_namespace][$row->ar_title] = $pageID;
				$a = [ 'revisions' => [ $rev ] ];
				ApiResult::setIndexedTagName( $a['revisions'], 'rev' );
				$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );
				ApiQueryBase::addTitleInfo( $a, $title );
				if ( $fld_token ) {
					// @phan-suppress-next-line PhanPossiblyUndeclaredVariable token is set when used
					$a['token'] = $token;
				}
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], $pageID, $a );
			} else {
				$pageID = $pageMap[$row->ar_namespace][$row->ar_title];
				$fit = $result->addValue(
					[ 'query', $this->getModuleName(), $pageID, 'revisions' ],
					null, $rev );
			}
			if ( !$fit ) {
				if ( $mode == 'all' || $mode == 'revs' ) {
					$this->setContinueEnumParameter( 'continue',
						"$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
					);
				} else {
					$this->setContinueEnumParameter( 'continue', "$row->ar_timestamp|$row->ar_id" );
				}
				break;
			}
		}
		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'page' );
	}

	/** @inheritDoc */
	public function isDeprecated() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$smallLimit = $this->getMain()->canApiHighLimits() ? ApiBase::LIMIT_SML2 : ApiBase::LIMIT_SML1;
		return [
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 1, 2 ] ],
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 1, 2 ] ],
			],
			'dir' => [
				ParamValidator::PARAM_TYPE => [
					'newer',
					'older'
				],
				ParamValidator::PARAM_DEFAULT => 'older',
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'newer' => 'api-help-paramvalue-direction-newer',
					'older' => 'api-help-paramvalue-direction-older',
				],
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 1, 3 ] ],
			],
			'from' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'to' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'prefix' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'unique' => [
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'namespace' => [
				ParamValidator::PARAM_TYPE => 'namespace',
				ParamValidator::PARAM_DEFAULT => NS_MAIN,
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'modes', 3 ] ],
			],
			'tag' => null,
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'excludeuser' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'prop' => [
				ParamValidator::PARAM_DEFAULT => 'user|comment',
				ParamValidator::PARAM_TYPE => [
					'revid',
					'parentid',
					'user',
					'userid',
					'comment',
					'parsedcomment',
					'minor',
					'len',
					'sha1',
					'content',
					'token',
					'tags'
				],
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'content' => [ 'apihelp-query+deletedrevs-paramvalue-prop-content', $smallLimit ],
				],
				EnumDef::PARAM_DEPRECATED_VALUES => [
					'token' => true,
				],
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2,
				ApiBase::PARAM_HELP_MSG => [ 'apihelp-query+deletedrevs-param-limit', $smallLimit ],
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage();
		$talkTitle = $title->getTalkPageIfDefined();
		$examples = [];

		if ( $talkTitle ) {
			$title = rawurlencode( $title->getPrefixedText() );
			$talkTitle = rawurlencode( $talkTitle->getPrefixedText() );
			$examples = [
				"action=query&list=deletedrevs&titles={$title}|{$talkTitle}&" .
					'drprop=user|comment|content'
					=> 'apihelp-query+deletedrevs-example-mode1',
			];
		}

		return array_merge( $examples, [
			'action=query&list=deletedrevs&druser=Bob&drlimit=50'
				=> 'apihelp-query+deletedrevs-example-mode2',
			'action=query&list=deletedrevs&drdir=newer&drlimit=50'
				=> 'apihelp-query+deletedrevs-example-mode3-main',
			'action=query&list=deletedrevs&drdir=newer&drlimit=50&drnamespace=1&drunique='
				=> 'apihelp-query+deletedrevs-example-mode3-talk',
		] );
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Deletedrevs';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryDeletedrevs::class, 'ApiQueryDeletedrevs' );
