<?php
/**
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

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use Wikimedia\IPUtils;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\RawSQLExpression;

/**
 * Query module to enumerate all user blocks
 *
 * @ingroup API
 */
class ApiQueryBlocks extends ApiQueryBase {

	private DatabaseBlockStore $blockStore;
	private BlockActionInfo $blockActionInfo;
	private BlockRestrictionStore $blockRestrictionStore;
	private CommentStore $commentStore;
	private HideUserUtils $hideUserUtils;
	private CommentFormatter $commentFormatter;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		DatabaseBlockStore $blockStore,
		BlockActionInfo $blockActionInfo,
		BlockRestrictionStore $blockRestrictionStore,
		CommentStore $commentStore,
		HideUserUtils $hideUserUtils,
		CommentFormatter $commentFormatter
	) {
		parent::__construct( $query, $moduleName, 'bk' );
		$this->blockStore = $blockStore;
		$this->blockActionInfo = $blockActionInfo;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->commentStore = $commentStore;
		$this->hideUserUtils = $hideUserUtils;
		$this->commentFormatter = $commentFormatter;
	}

	public function execute() {
		$db = $this->getDB();
		$params = $this->extractRequestParams();
		$this->requireMaxOneParameter( $params, 'users', 'ip' );

		$prop = array_fill_keys( $params['prop'], true );
		$fld_id = isset( $prop['id'] );
		$fld_user = isset( $prop['user'] );
		$fld_userid = isset( $prop['userid'] );
		$fld_by = isset( $prop['by'] );
		$fld_byid = isset( $prop['byid'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_expiry = isset( $prop['expiry'] );
		$fld_reason = isset( $prop['reason'] );
		$fld_parsedreason = isset( $prop['parsedreason'] );
		$fld_range = isset( $prop['range'] );
		$fld_flags = isset( $prop['flags'] );
		$fld_restrictions = isset( $prop['restrictions'] );

		$result = $this->getResult();

		$this->addTables( [ 'block', 'block_target', 'block_target_user' => 'user' ] );
		$this->addJoinConds( [
			'block_target' => [ 'JOIN', 'bt_id=bl_target' ],
			'block_target_user' => [ 'LEFT JOIN', 'user_id=bt_user' ]
		] );
		$this->addFields( [ 'bt_auto', 'bl_id', 'bl_timestamp' ] );
		$this->addFieldsIf(
			[
				'bt_address',
				'bt_user',
				'bt_address_or_user_name' => 'COALESCE(bt_address, bt_user_text)'
			],
			$fld_user || $fld_userid
		);

		if ( $fld_by || $fld_byid ) {
			$this->addTables( 'actor' );
			$this->addFields( [ 'actor_user', 'actor_name' ] );
			$this->addJoinConds( [ 'actor' => [ 'JOIN', 'actor_id=bl_by_actor' ] ] );
		}
		$this->addFieldsIf( 'bl_expiry', $fld_expiry );
		$this->addFieldsIf( [ 'bt_range_start', 'bt_range_end' ], $fld_range );
		$this->addFieldsIf( [ 'bl_anon_only', 'bl_create_account', 'bl_enable_autoblock',
			'bl_block_email', 'bl_deleted', 'bl_allow_usertalk', 'bl_sitewide' ],
			$fld_flags );
		$this->addFieldsIf( 'bl_sitewide', $fld_restrictions );

		if ( $fld_reason || $fld_parsedreason ) {
			$commentQuery = $this->commentStore->getJoin( 'bl_reason' );
			$this->addTables( $commentQuery['tables'] );
			$this->addFields( $commentQuery['fields'] );
			$this->addJoinConds( $commentQuery['joins'] );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addTimestampWhereRange(
			'bl_timestamp',
			$params['dir'],
			$params['start'],
			$params['end']
		);
		// Include in ORDER BY for uniqueness
		$this->addWhereRange( 'bl_id', $params['dir'], null, null );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'timestamp', 'int' ] );
			$op = ( $params['dir'] == 'newer' ? '>=' : '<=' );
			$this->addWhere( $db->buildComparison( $op, [
				'bl_timestamp' => $db->timestamp( $cont[0] ),
				'bl_id' => $cont[1],
			] ) );
		}

		if ( $params['ids'] ) {
			$this->addWhereIDsFld( 'block', 'bl_id', $params['ids'] );
		}
		if ( $params['users'] ) {
			$addresses = [];
			$userNames = [];
			foreach ( $params['users'] as $target ) {
				if ( IPUtils::isValid( $target ) || IPUtils::isValidRange( $target ) ) {
					$addresses[] = $target;
				} else {
					$userNames[] = $target;
				}
			}
			if ( $addresses && $userNames ) {
				// Use a union, not "OR" (T360088)
				$ids = $db->newUnionQueryBuilder()
					->add( $db->newSelectQueryBuilder()
						->select( 'bt_id' )
						->from( 'block_target' )
						->where( [ 'bt_address' => $addresses ] )
					)
					->add( $db->newSelectQueryBuilder()
						->select( 'bt_id' )
						->from( 'block_target' )
						->join( 'user', null, 'user_id=bt_user' )
						->where( [ 'user_name' => $userNames ] )
					)
					->caller( __METHOD__ )
					->fetchFieldValues();
				if ( $ids ) {
					$this->addWhere( [ 'bt_id' => $ids ] );
				} else {
					$this->addWhere( '1=0' );
				}
			} elseif ( $addresses ) {
				$this->addWhere( [ 'bt_address' => $addresses ] );
			} elseif ( $userNames ) {
				$this->addWhere( [ 'block_target_user.user_name' => $userNames ] );
			} else {
				// Unreachable since $params['users'] is non-empty
				$this->addWhere( '1=0' );
			}
			$this->addWhereFld( 'bt_auto', 0 );
		}
		if ( $params['ip'] !== null ) {
			$blockCIDRLimit = $this->getConfig()->get( MainConfigNames::BlockCIDRLimit );
			if ( IPUtils::isIPv4( $params['ip'] ) ) {
				$type = 'IPv4';
				$cidrLimit = $blockCIDRLimit['IPv4'];
			} elseif ( IPUtils::isIPv6( $params['ip'] ) ) {
				$type = 'IPv6';
				$cidrLimit = $blockCIDRLimit['IPv6'];
			} else {
				$this->dieWithError( 'apierror-badip', 'param_ip' );
			}

			// Check range validity, if it's a CIDR
			[ $ip, $range ] = IPUtils::parseCIDR( $params['ip'] );
			if ( $ip !== false && $range !== false && $range < $cidrLimit ) {
				$this->dieWithError( [ 'apierror-cidrtoobroad', $type, $cidrLimit ] );
			}

			// Let IPUtils::parseRange handle calculating $upper, instead of duplicating the logic here.
			[ $lower, $upper ] = IPUtils::parseRange( $params['ip'] );

			$this->addWhere( $this->blockStore->getRangeCond( $lower, $upper ) );
			$this->addWhere( [ 'bt_auto' => 0 ] );
		}

		if ( $params['show'] !== null ) {
			$show = array_fill_keys( $params['show'], true );

			// Check for conflicting parameters.
			if ( ( isset( $show['account'] ) && isset( $show['!account'] ) )
				|| ( isset( $show['ip'] ) && isset( $show['!ip'] ) )
				|| ( isset( $show['range'] ) && isset( $show['!range'] ) )
				|| ( isset( $show['temp'] ) && isset( $show['!temp'] ) )
			) {
				$this->dieWithError( 'apierror-show' );
			}

			$this->addWhereIf( [ 'bt_user' => 0 ], isset( $show['!account'] ) );
			$this->addWhereIf( $db->expr( 'bt_user', '!=', 0 ), isset( $show['account'] ) );
			$this->addWhereIf(
				$db->expr( 'bt_user', '!=', 0 )->orExpr( new RawSQLExpression( 'bt_range_end > bt_range_start' ) ),
				isset( $show['!ip'] )
			);
			$this->addWhereIf( [ 'bt_user' => 0, 'bt_range_end = bt_range_start' ], isset( $show['ip'] ) );
			$this->addWhereIf( [ 'bl_expiry' => $db->getInfinity() ], isset( $show['!temp'] ) );
			$this->addWhereIf( $db->expr( 'bl_expiry', '!=', $db->getInfinity() ), isset( $show['temp'] ) );
			$this->addWhereIf( 'bt_range_end = bt_range_start', isset( $show['!range'] ) );
			$this->addWhereIf( 'bt_range_end > bt_range_start', isset( $show['range'] ) );
		}

		if ( !$this->getAuthority()->isAllowed( 'hideuser' ) ) {
			$this->addWhere( [ 'bl_deleted' => 0 ] );
			$this->addWhere(
				$this->hideUserUtils->getExpression( $db, 'block_target.bt_user' )
			);
		}

		// Filter out expired rows
		$this->addWhere( $db->expr( 'bl_expiry', '>', $db->timestamp() ) );

		$res = $this->select( __METHOD__ );

		$restrictions = [];
		if ( $fld_restrictions ) {
			$restrictions = $this->getRestrictionData( $res, $params['limit'] );
		}

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've had enough
				$this->setContinueEnumParameter( 'continue', "{$row->bl_timestamp}|{$row->bl_id}" );
				break;
			}
			$block = [
				ApiResult::META_TYPE => 'assoc',
			];
			if ( $fld_id ) {
				$block['id'] = (int)$row->bl_id;
			}
			if ( $fld_user && !$row->bt_auto ) {
				$block['user'] = $row->bt_address_or_user_name;
			}
			if ( $fld_userid && !$row->bt_auto ) {
				$block['userid'] = (int)$row->bt_user;
			}
			if ( $fld_by ) {
				$block['by'] = $row->actor_name;
			}
			if ( $fld_byid ) {
				$block['byid'] = (int)$row->actor_user;
			}
			if ( $fld_timestamp ) {
				$block['timestamp'] = wfTimestamp( TS_ISO_8601, $row->bl_timestamp );
			}
			if ( $fld_expiry ) {
				$block['expiry'] = ApiResult::formatExpiry( $row->bl_expiry );
				if ( wfIsInfinity( $row->bl_expiry ) ) {
					$duration = $this->msg( 'infiniteblock' )->plain();
				} else {
					$duration = $this->getLanguage()->formatDurationBetweenTimestamps(
						(int)wfTimestamp( TS_UNIX, $row->bl_timestamp ),
						(int)wfTimestamp( TS_UNIX, $row->bl_expiry )
					);
				}
				$block['duration-l10n'] = $duration;
			}
			if ( $fld_reason ) {
				$block['reason'] = $this->commentStore->getComment( 'bl_reason', $row )->text;
			}
			if ( $fld_parsedreason ) {
				$block['parsedreason'] = $this->commentFormatter->format(
					$this->commentStore->getComment( 'bl_reason', $row )->text
				);
			}
			if ( $fld_range && !$row->bt_auto && $row->bt_range_start !== null ) {
				$block['rangestart'] = IPUtils::formatHex( $row->bt_range_start );
				$block['rangeend'] = IPUtils::formatHex( $row->bt_range_end );
			}
			if ( $fld_flags ) {
				// For clarity, these flags use the same names as their action=block counterparts
				$block['automatic'] = (bool)$row->bt_auto;
				$block['anononly'] = (bool)$row->bl_anon_only;
				$block['nocreate'] = (bool)$row->bl_create_account;
				$block['autoblock'] = (bool)$row->bl_enable_autoblock;
				$block['noemail'] = (bool)$row->bl_block_email;
				$block['hidden'] = (bool)$row->bl_deleted;
				$block['allowusertalk'] = (bool)$row->bl_allow_usertalk;
				$block['partial'] = !(bool)$row->bl_sitewide;
			}

			if ( $fld_restrictions ) {
				$block['restrictions'] = [];
				if ( !$row->bl_sitewide && isset( $restrictions[$row->bl_id] ) ) {
					$block['restrictions'] = $restrictions[$row->bl_id];
				}
			}

			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $block );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', "{$row->bl_timestamp}|{$row->bl_id}" );
				break;
			}
		}
		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'block' );
	}

	/**
	 * Retrieves the restrictions based on the query result.
	 *
	 * @param IResultWrapper $result
	 * @param int $limit
	 *
	 * @return array
	 */
	private function getRestrictionData( IResultWrapper $result, $limit ) {
		$partialIds = [];
		$count = 0;
		foreach ( $result as $row ) {
			if ( ++$count <= $limit && !( $row->ipb_sitewide ?? $row->bl_sitewide ) ) {
				$partialIds[] = (int)( $row->ipb_id ?? $row->bl_id );
			}
		}

		$restrictions = $this->blockRestrictionStore->loadByBlockId( $partialIds );

		$data = [];
		$keys = [
			'page' => 'pages',
			'ns' => 'namespaces',
			'action' => 'actions',
		];

		foreach ( $restrictions as $restriction ) {
			$key = $keys[$restriction->getType()];
			$id = $restriction->getBlockId();
			switch ( $restriction->getType() ) {
				case 'page':
					$value = [ 'id' => $restriction->getValue() ];
					/** @var PageRestriction $restriction */
					'@phan-var \MediaWiki\Block\Restriction\PageRestriction $restriction';
					if ( !$restriction->getTitle() ) {
						continue 2;
					}
					self::addTitleInfo( $value, $restriction->getTitle() );
					break;
				case 'action':
					$value = $this->blockActionInfo->getActionFromId( $restriction->getValue() );
					break;
				default:
					$value = $restriction->getValue();
			}

			if ( !isset( $data[$id][$key] ) ) {
				$data[$id][$key] = [];
				ApiResult::setIndexedTagName( $data[$id][$key], $restriction->getType() );
			}
			$data[$id][$key][] = $value;
		}

		return $data;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$blockCIDRLimit = $this->getConfig()->get( MainConfigNames::BlockCIDRLimit );

		return [
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
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
			'ids' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_ISMULTI => true
			],
			'users' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'cidr' ],
				ParamValidator::PARAM_ISMULTI => true
			],
			'ip' => [
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-query+blocks-param-ip',
					$blockCIDRLimit['IPv4'],
					$blockCIDRLimit['IPv6'],
				],
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ParamValidator::PARAM_DEFAULT => 'id|user|by|timestamp|expiry|reason|flags',
				ParamValidator::PARAM_TYPE => [
					'id',
					'user',
					'userid',
					'by',
					'byid',
					'timestamp',
					'expiry',
					'reason',
					'parsedreason',
					'range',
					'flags',
					'restrictions',
				],
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'show' => [
				ParamValidator::PARAM_TYPE => [
					'account',
					'!account',
					'temp',
					'!temp',
					'ip',
					'!ip',
					'range',
					'!range',
				],
				ParamValidator::PARAM_ISMULTI => true
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=blocks'
				=> 'apihelp-query+blocks-example-simple',
			'action=query&list=blocks&bkusers=Alice|Bob'
				=> 'apihelp-query+blocks-example-users',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Blocks';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryBlocks::class, 'ApiQueryBlocks' );
