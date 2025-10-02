<?php
/**
 * Copyright © 2009 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * Query module to enumerate all create-protected pages.
 *
 * @ingroup API
 */
class ApiQueryProtectedTitles extends ApiQueryGeneratorBase {

	private CommentStore $commentStore;
	private RowCommentFormatter $commentFormatter;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		CommentStore $commentStore,
		RowCommentFormatter $commentFormatter
	) {
		parent::__construct( $query, $moduleName, 'pt' );
		$this->commentStore = $commentStore;
		$this->commentFormatter = $commentFormatter;
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		$this->addTables( 'protected_titles' );
		$this->addFields( [ 'pt_namespace', 'pt_title', 'pt_timestamp' ] );

		$prop = array_fill_keys( $params['prop'], true );
		$this->addFieldsIf( 'pt_user', isset( $prop['user'] ) || isset( $prop['userid'] ) );
		$this->addFieldsIf( 'pt_expiry', isset( $prop['expiry'] ) );
		$this->addFieldsIf( 'pt_create_perm', isset( $prop['level'] ) );

		if ( isset( $prop['comment'] ) || isset( $prop['parsedcomment'] ) ) {
			$commentQuery = $this->commentStore->getJoin( 'pt_reason' );
			$this->addTables( $commentQuery['tables'] );
			$this->addFields( $commentQuery['fields'] );
			$this->addJoinConds( $commentQuery['joins'] );
		}

		$this->addTimestampWhereRange( 'pt_timestamp', $params['dir'], $params['start'], $params['end'] );
		$this->addWhereFld( 'pt_namespace', $params['namespace'] );
		$this->addWhereFld( 'pt_create_perm', $params['level'] );

		// Include in ORDER BY for uniqueness
		$this->addWhereRange( 'pt_namespace', $params['dir'], null, null );
		$this->addWhereRange( 'pt_title', $params['dir'], null, null );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'timestamp', 'int', 'string' ] );
			$op = ( $params['dir'] === 'newer' ? '>=' : '<=' );
			$db = $this->getDB();
			$this->addWhere( $db->buildComparison( $op, [
				'pt_timestamp' => $db->timestamp( $cont[0] ),
				'pt_namespace' => $cont[1],
				'pt_title' => $cont[2],
			] ) );
		}

		if ( isset( $prop['user'] ) ) {
			$this->addTables( 'user' );
			$this->addFields( 'user_name' );
			$this->addJoinConds( [ 'user' => [ 'LEFT JOIN',
				'user_id=pt_user'
			] ] );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$res = $this->select( __METHOD__ );

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__, 'pt' );
			if ( isset( $prop['parsedcomment'] ) ) {
				$formattedComments = $this->commentFormatter->formatItems(
					$this->commentFormatter->rows( $res )
						->commentKey( 'pt_reason' )
						->namespaceField( 'pt_namespace' )
						->titleField( 'pt_title' )
				);
			}
		}

		$count = 0;
		$result = $this->getResult();

		$titles = [];

		foreach ( $res as $rowOffset => $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue',
					"$row->pt_timestamp|$row->pt_namespace|$row->pt_title"
				);
				break;
			}

			$title = Title::makeTitle( $row->pt_namespace, $row->pt_title );
			if ( $resultPageSet === null ) {
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, $title );
				if ( isset( $prop['timestamp'] ) ) {
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->pt_timestamp );
				}

				if ( isset( $prop['user'] ) && $row->user_name !== null ) {
					$vals['user'] = $row->user_name;
				}

				if ( isset( $prop['userid'] ) || /*B/C*/isset( $prop['user'] ) ) {
					$vals['userid'] = (int)$row->pt_user;
				}

				if ( isset( $prop['comment'] ) ) {
					$vals['comment'] = $this->commentStore->getComment( 'pt_reason', $row )->text;
				}

				if ( isset( $prop['parsedcomment'] ) ) {
					// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
					$vals['parsedcomment'] = $formattedComments[$rowOffset];
				}

				if ( isset( $prop['expiry'] ) ) {
					$vals['expiry'] = ApiResult::formatExpiry( $row->pt_expiry );
				}

				if ( isset( $prop['level'] ) ) {
					$vals['level'] = $row->pt_create_perm;
				}

				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue',
						"$row->pt_timestamp|$row->pt_namespace|$row->pt_title"
					);
					break;
				}
			} else {
				$titles[] = $title;
			}
		}

		if ( $resultPageSet === null ) {
			$result->addIndexedTagName(
				[ 'query', $this->getModuleName() ],
				$this->getModulePrefix()
			);
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		if ( $params['prop'] !== null && in_array( 'parsedcomment', $params['prop'] ) ) {
			// MediaWiki\CommentFormatter\CommentFormatter::formatItems() calls wfMessage() among other things
			return 'anon-public-user-private';
		} else {
			return 'public';
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace',
			],
			'level' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => array_diff(
					$this->getConfig()->get( MainConfigNames::RestrictionLevels ), [ '' ] )
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
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
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'timestamp|level',
				ParamValidator::PARAM_TYPE => [
					'timestamp',
					'user',
					'userid',
					'comment',
					'parsedcomment',
					'expiry',
					'level'
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=protectedtitles'
				=> 'apihelp-query+protectedtitles-example-simple',
			'action=query&generator=protectedtitles&gptnamespace=0&prop=linkshere'
				=> 'apihelp-query+protectedtitles-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Protectedtitles';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryProtectedTitles::class, 'ApiQueryProtectedTitles' );
