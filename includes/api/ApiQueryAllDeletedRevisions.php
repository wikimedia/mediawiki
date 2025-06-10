<?php
/**
 * Copyright © 2014 Wikimedia Foundation and contributors
 *
 * Heavily based on ApiQueryDeletedrevs,
 * Copyright © 2007 Roan Kattouw <roan.kattouw@gmail.com>
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
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\UserFactory;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Query module to enumerate all deleted revisions.
 *
 * @ingroup API
 */
class ApiQueryAllDeletedRevisions extends ApiQueryRevisionsBase {

	private RevisionStore $revisionStore;
	private NameTableStore $changeTagDefStore;
	private ChangeTagsStore $changeTagsStore;
	private NamespaceInfo $namespaceInfo;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		RevisionStore $revisionStore,
		IContentHandlerFactory $contentHandlerFactory,
		ParserFactory $parserFactory,
		SlotRoleRegistry $slotRoleRegistry,
		NameTableStore $changeTagDefStore,
		ChangeTagsStore $changeTagsStore,
		NamespaceInfo $namespaceInfo,
		ContentRenderer $contentRenderer,
		ContentTransformer $contentTransformer,
		CommentFormatter $commentFormatter,
		TempUserCreator $tempUserCreator,
		UserFactory $userFactory
	) {
		parent::__construct(
			$query,
			$moduleName,
			'adr',
			$revisionStore,
			$contentHandlerFactory,
			$parserFactory,
			$slotRoleRegistry,
			$contentRenderer,
			$contentTransformer,
			$commentFormatter,
			$tempUserCreator,
			$userFactory
		);
		$this->revisionStore = $revisionStore;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->changeTagsStore = $changeTagsStore;
		$this->namespaceInfo = $namespaceInfo;
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	protected function run( ?ApiPageSet $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams( false );

		$result = $this->getResult();

		// If the user wants no namespaces, they get no pages.
		if ( $params['namespace'] === [] ) {
			if ( $resultPageSet === null ) {
				$result->addValue( 'query', $this->getModuleName(), [] );
			}
			return;
		}

		// This module operates in two modes:
		// 'user': List deleted revs by a certain user
		// 'all': List all deleted revs in NS
		$mode = 'all';
		if ( $params['user'] !== null ) {
			$mode = 'user';
		}

		if ( $mode == 'user' ) {
			foreach ( [ 'from', 'to', 'prefix', 'excludeuser' ] as $param ) {
				if ( $params[$param] !== null ) {
					$p = $this->getModulePrefix();
					$this->dieWithError(
						[ 'apierror-invalidparammix-cannotusewith', $p . $param, "{$p}user" ],
						'invalidparammix'
					);
				}
			}
		} else {
			foreach ( [ 'start', 'end' ] as $param ) {
				if ( $params[$param] !== null ) {
					$p = $this->getModulePrefix();
					$this->dieWithError(
						[ 'apierror-invalidparammix-mustusewith', $p . $param, "{$p}user" ],
						'invalidparammix'
					);
				}
			}
		}

		// If we're generating titles only, we can use DISTINCT for a better
		// query. But we can't do that in 'user' mode (wrong index), and we can
		// only do it when sorting ASC (because MySQL apparently can't use an
		// index backwards for grouping even though it can for ORDER BY, WTF?)
		$dir = $params['dir'];
		$optimizeGenerateTitles = false;
		if ( $mode === 'all' && $params['generatetitles'] && $resultPageSet !== null ) {
			if ( $dir === 'newer' ) {
				$optimizeGenerateTitles = true;
			} else {
				$p = $this->getModulePrefix();
				$this->addWarning( [ 'apiwarn-alldeletedrevisions-performance', $p ], 'performance' );
			}
		}

		if ( $resultPageSet === null ) {
			$this->parseParameters( $params );
			$arQuery = $this->revisionStore->getArchiveQueryInfo();
			$this->addTables( $arQuery['tables'] );
			$this->addJoinConds( $arQuery['joins'] );
			$this->addFields( $arQuery['fields'] );
			$this->addFields( [ 'ar_title', 'ar_namespace' ] );
		} else {
			$this->limit = $this->getParameter( 'limit' ) ?: 10;
			$this->addTables( 'archive' );
			$this->addFields( [ 'ar_title', 'ar_namespace' ] );
			if ( $optimizeGenerateTitles ) {
				$this->addOption( 'DISTINCT' );
			} else {
				$this->addFields( [ 'ar_timestamp', 'ar_rev_id', 'ar_id' ] );
			}
			if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
				$this->addTables( 'actor' );
				$this->addJoinConds( [ 'actor' => 'actor_id=ar_actor' ] );
			}
		}

		if ( $this->fld_tags ) {
			$this->addFields( [
				'ts_tags' => $this->changeTagsStore->makeTagSummarySubquery( 'archive' )
			] );
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
		if ( ( $this->fld_comment || $this->fld_parsedcomment ) &&
			!$this->getAuthority()->isAllowed( 'deletedhistory' )
		) {
			$this->dieWithError( 'apierror-cantview-deleted-comment', 'permissiondenied' );
		}
		if ( $this->fetchContent &&
			!$this->getAuthority()->isAllowedAny( 'deletedtext', 'undelete' )
		) {
			$this->dieWithError( 'apierror-cantview-deleted-revision-content', 'permissiondenied' );
		}

		$miser_ns = null;

		if ( $mode == 'all' ) {
			$namespaces = $params['namespace'] ?? $this->namespaceInfo->getValidNamespaces();
			$this->addWhereFld( 'ar_namespace', $namespaces );

			// For from/to/prefix, we have to consider the potential
			// transformations of the title in all specified namespaces.
			// Generally there will be only one transformation, but wikis with
			// some namespaces case-sensitive could have two.
			if ( $params['from'] !== null || $params['to'] !== null ) {
				$isDirNewer = ( $dir === 'newer' );
				$after = ( $isDirNewer ? '>=' : '<=' );
				$before = ( $isDirNewer ? '<=' : '>=' );
				$titleParts = [];
				foreach ( $namespaces as $ns ) {
					if ( $params['from'] !== null ) {
						$fromTitlePart = $this->titlePartToKey( $params['from'], $ns );
					} else {
						$fromTitlePart = '';
					}
					if ( $params['to'] !== null ) {
						$toTitlePart = $this->titlePartToKey( $params['to'], $ns );
					} else {
						$toTitlePart = '';
					}
					$titleParts[$fromTitlePart . '|' . $toTitlePart][] = $ns;
				}
				if ( count( $titleParts ) === 1 ) {
					[ $fromTitlePart, $toTitlePart, ] = explode( '|', key( $titleParts ), 2 );
					if ( $fromTitlePart !== '' ) {
						$this->addWhere( $db->expr( 'ar_title', $after, $fromTitlePart ) );
					}
					if ( $toTitlePart !== '' ) {
						$this->addWhere( $db->expr( 'ar_title', $before, $toTitlePart ) );
					}
				} else {
					$where = [];
					foreach ( $titleParts as $titlePart => $ns ) {
						[ $fromTitlePart, $toTitlePart, ] = explode( '|', $titlePart, 2 );
						$expr = $db->expr( 'ar_namespace', '=', $ns );
						if ( $fromTitlePart !== '' ) {
							$expr = $expr->and( 'ar_title', $after, $fromTitlePart );
						}
						if ( $toTitlePart !== '' ) {
							$expr = $expr->and( 'ar_title', $before, $toTitlePart );
						}
						$where[] = $expr;
					}
					$this->addWhere( $db->orExpr( $where ) );
				}
			}

			if ( isset( $params['prefix'] ) ) {
				$titleParts = [];
				foreach ( $namespaces as $ns ) {
					$prefixTitlePart = $this->titlePartToKey( $params['prefix'], $ns );
					$titleParts[$prefixTitlePart][] = $ns;
				}
				if ( count( $titleParts ) === 1 ) {
					$prefixTitlePart = key( $titleParts );
					$this->addWhere( $db->expr( 'ar_title', IExpression::LIKE,
						new LikeValue( $prefixTitlePart, $db->anyString() )
					) );
				} else {
					$where = [];
					foreach ( $titleParts as $prefixTitlePart => $ns ) {
						$where[] = $db->expr( 'ar_namespace', '=', $ns )
							->and( 'ar_title', IExpression::LIKE,
								new LikeValue( $prefixTitlePart, $db->anyString() ) );
					}
					$this->addWhere( $db->orExpr( $where ) );
				}
			}
		} else {
			if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
				$miser_ns = $params['namespace'];
			} else {
				$this->addWhereFld( 'ar_namespace', $params['namespace'] );
			}
			$this->addTimestampWhereRange( 'ar_timestamp', $dir, $params['start'], $params['end'] );
		}

		if ( $params['user'] !== null ) {
			// We could get the actor ID from the ActorStore, but it's probably
			// uncached at this point, and the non-generator case needs an actor
			// join anyway so adding this join here is normally free. This should
			// use the ar_actor_timestamp index.
			$this->addWhereFld( 'actor_name', $params['user'] );
		} elseif ( $params['excludeuser'] !== null ) {
			$this->addWhere( $db->expr( 'actor_name', '!=', $params['excludeuser'] ) );
		}

		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			// Paranoia: avoid brute force searches (T19342)
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
			if ( $optimizeGenerateTitles ) {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'string' ] );
				$this->addWhere( $db->buildComparison( $op, [
					'ar_namespace' => $cont[0],
					'ar_title' => $cont[1],
				] ) );
			} elseif ( $mode == 'all' ) {
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

		$this->addOption( 'LIMIT', $this->limit + 1 );

		$sort = ( $dir == 'newer' ? '' : ' DESC' );
		$orderby = [];
		if ( $optimizeGenerateTitles ) {
			// Targeting index ar_name_title_timestamp
			if ( $params['namespace'] === null || count( array_unique( $params['namespace'] ) ) > 1 ) {
				$orderby[] = "ar_namespace $sort";
			}
			$orderby[] = "ar_title $sort";
		} elseif ( $mode == 'all' ) {
			// Targeting index ar_name_title_timestamp
			if ( $params['namespace'] === null || count( array_unique( $params['namespace'] ) ) > 1 ) {
				$orderby[] = "ar_namespace $sort";
			}
			$orderby[] = "ar_title $sort";
			$orderby[] = "ar_timestamp $sort";
			$orderby[] = "ar_id $sort";
		} else {
			// Targeting index usertext_timestamp
			// 'user' is always constant.
			$orderby[] = "ar_timestamp $sort";
			$orderby[] = "ar_id $sort";
		}
		$this->addOption( 'ORDER BY', $orderby );

		$res = $this->select( __METHOD__ );

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__, 'ar' );
		}

		$pageMap = []; // Maps ns&title to array index
		$count = 0;
		$nextIndex = 0;
		$generated = [];
		foreach ( $res as $row ) {
			if ( ++$count > $this->limit ) {
				// We've had enough
				if ( $optimizeGenerateTitles ) {
					$this->setContinueEnumParameter( 'continue', "$row->ar_namespace|$row->ar_title" );
				} elseif ( $mode == 'all' ) {
					$this->setContinueEnumParameter( 'continue',
						"$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
					);
				} else {
					$this->setContinueEnumParameter( 'continue', "$row->ar_timestamp|$row->ar_id" );
				}
				break;
			}

			// Miser mode namespace check
			if ( $miser_ns !== null && !in_array( $row->ar_namespace, $miser_ns ) ) {
				continue;
			}

			if ( $resultPageSet !== null ) {
				if ( $params['generatetitles'] ) {
					$key = "{$row->ar_namespace}:{$row->ar_title}";
					if ( !isset( $generated[$key] ) ) {
						$generated[$key] = Title::makeTitle( $row->ar_namespace, $row->ar_title );
					}
				} else {
					$generated[] = $row->ar_rev_id;
				}
			} else {
				$revision = $this->revisionStore->newRevisionFromArchiveRow( $row );
				$rev = $this->extractRevisionInfo( $revision, $row );

				if ( !isset( $pageMap[$row->ar_namespace][$row->ar_title] ) ) {
					$index = $nextIndex++;
					$pageMap[$row->ar_namespace][$row->ar_title] = $index;
					$title = Title::newFromPageIdentity( $revision->getPage() );
					$a = [
						'pageid' => $title->getArticleID(),
						'revisions' => [ $rev ],
					];
					ApiResult::setIndexedTagName( $a['revisions'], 'rev' );
					ApiQueryBase::addTitleInfo( $a, $title );
					$fit = $result->addValue( [ 'query', $this->getModuleName() ], $index, $a );
				} else {
					$index = $pageMap[$row->ar_namespace][$row->ar_title];
					$fit = $result->addValue(
						[ 'query', $this->getModuleName(), $index, 'revisions' ],
						null, $rev );
				}
				if ( !$fit ) {
					if ( $mode == 'all' ) {
						$this->setContinueEnumParameter( 'continue',
							"$row->ar_namespace|$row->ar_title|$row->ar_timestamp|$row->ar_id"
						);
					} else {
						$this->setContinueEnumParameter( 'continue', "$row->ar_timestamp|$row->ar_id" );
					}
					break;
				}
			}
		}

		if ( $resultPageSet !== null ) {
			if ( $params['generatetitles'] ) {
				$resultPageSet->populateFromTitles( $generated );
			} else {
				$resultPageSet->populateFromRevisionIDs( $generated );
			}
		} else {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'page' );
		}
	}

	public function getAllowedParams() {
		$ret = parent::getAllowedParams() + [
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace',
			],
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'useronly' ] ],
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'useronly' ] ],
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
			],
			'from' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'nonuseronly' ] ],
			],
			'to' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'nonuseronly' ] ],
			],
			'prefix' => [
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'nonuseronly' ] ],
			],
			'excludeuser' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
				ApiBase::PARAM_HELP_MSG_INFO => [ [ 'nonuseronly' ] ],
			],
			'tag' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'generatetitles' => [
				ParamValidator::PARAM_DEFAULT => false
			],
		];

		if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$ret['user'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'apihelp-query+alldeletedrevisions-param-miser-user-namespace',
			];
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'apihelp-query+alldeletedrevisions-param-miser-user-namespace',
			];
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=alldeletedrevisions&adruser=Example&adrlimit=50'
				=> 'apihelp-query+alldeletedrevisions-example-user',
			'action=query&list=alldeletedrevisions&adrdir=newer&adrnamespace=0&adrlimit=50'
				=> 'apihelp-query+alldeletedrevisions-example-ns-main',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Alldeletedrevisions';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryAllDeletedRevisions::class, 'ApiQueryAllDeletedRevisions' );
