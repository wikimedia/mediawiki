<?php
/**
 * API module to handle links table back-queries
 *
 * Copyright © 2014 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 * @since 1.24
 */

namespace MediaWiki\Api;

use MediaWiki\Deferred\LinksUpdate\ImageLinksTable;
use MediaWiki\Deferred\LinksUpdate\PageLinksTable;
use MediaWiki\Deferred\LinksUpdate\TemplateLinksTable;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * This implements prop=redirects, prop=linkshere, prop=catmembers,
 * prop=transcludedin, and prop=fileusage
 *
 * @ingroup API
 * @since 1.24
 */
class ApiQueryBacklinksprop extends ApiQueryGeneratorBase {

	/** @var array Data for the various modules implemented by this class */
	private static $settings = [
		'redirects' => [
			'code' => 'rd',
			'prefix' => 'rd',
			'linktable' => 'redirect',
			'props' => [
				'fragment',
			],
			'showredirects' => false,
			'show' => [
				'fragment',
				'!fragment',
			],
		],
		'linkshere' => [
			'code' => 'lh',
			'prefix' => 'pl',
			'linktable' => 'pagelinks',
			'indexes' => [ 'pl_namespace', 'pl_backlinks_namespace' ],
			'from_namespace' => true,
			'showredirects' => true,
			'virtualdomain' => PageLinksTable::VIRTUAL_DOMAIN,
		],
		'transcludedin' => [
			'code' => 'ti',
			'prefix' => 'tl',
			'linktable' => 'templatelinks',
			'from_namespace' => true,
			'showredirects' => true,
			'virtualdomain' => TemplateLinksTable::VIRTUAL_DOMAIN,
		],
		'fileusage' => [
			'code' => 'fu',
			'prefix' => 'il',
			'linktable' => 'imagelinks',
			'indexes' => [ 'il_to', 'il_backlinks_namespace' ],
			'from_namespace' => true,
			'to_namespace' => NS_FILE,
			'exampletitle' => 'File:Example.jpg',
			'showredirects' => true,
			'virtualdomain' => ImageLinksTable::VIRTUAL_DOMAIN,
		],
	];

	private LinksMigration $linksMigration;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		LinksMigration $linksMigration
	) {
		parent::__construct( $query, $moduleName, self::$settings[$moduleName]['code'] );
		$this->linksMigration = $linksMigration;
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
	 */
	private function run( ?ApiPageSet $resultPageSet = null ) {
		$settings = self::$settings[$this->getModuleName()];

		$domain = $settings['virtualdomain'] ?? false;
		$this->setVirtualDomain( $domain );
		$db = $this->getDB();

		$params = $this->extractRequestParams();
		$prop = array_fill_keys( $params['prop'], true );

		$pageSet = $this->getPageSet();
		$titles = $pageSet->getGoodAndMissingPages();
		$map = $pageSet->getGoodAndMissingTitlesByNamespace();

		// Add in special pages, they can theoretically have backlinks too.
		// (although currently they only do for prop=redirects)
		foreach ( $pageSet->getSpecialPages() as $id => $title ) {
			$titles[] = $title;
			$map[$title->getNamespace()][$title->getDBkey()] = $id;
		}

		// Determine our fields to query on
		$p = $settings['prefix'];
		$hasNS = !isset( $settings['to_namespace'] );
		if ( $hasNS ) {
			if ( isset( $this->linksMigration::$mapping[$settings['linktable']] ) ) {
				[ $bl_namespace, $bl_title ] = $this->linksMigration->getTitleFields( $settings['linktable'] );
			} else {
				$bl_namespace = "{$p}_namespace";
				$bl_title = "{$p}_title";
			}
		} else {
			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
			$bl_namespace = $settings['to_namespace'];
			$bl_title = "{$p}_to";

			$titles = array_filter( $titles, static function ( $t ) use ( $bl_namespace ) {
				return $t->getNamespace() === $bl_namespace;
			} );
			$map = array_intersect_key( $map, [ $bl_namespace => true ] );
		}
		$bl_from = "{$p}_from";

		if ( !$titles ) {
			return; // nothing to do
		}
		if ( $params['namespace'] !== null && count( $params['namespace'] ) === 0 ) {
			return; // nothing to do
		}

		// Figure out what we're sorting by, and add associated WHERE clauses.
		// MySQL's query planner screws up if we include a field in ORDER BY
		// when it's constant in WHERE, so we have to test that for each field.
		$sortby = [];
		if ( $hasNS && count( $map ) > 1 ) {
			$sortby[$bl_namespace] = 'int';
		}
		$theTitle = null;
		foreach ( $map as $nsTitles ) {
			$key = array_key_first( $nsTitles );
			$theTitle ??= $key;
			if ( count( $nsTitles ) > 1 || $key !== $theTitle ) {
				$sortby[$bl_title] = 'string';
				break;
			}
		}
		$miser_ns = null;
		if ( $params['namespace'] !== null ) {
			if ( empty( $settings['from_namespace'] ) ) {
				if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
					$miser_ns = $params['namespace'];
				} else {
					$this->addWhereFld( 'page_namespace', $params['namespace'] );
				}
			} else {
				$this->addWhereFld( "{$p}_from_namespace", $params['namespace'] );
				if ( !empty( $settings['from_namespace'] )
					&& $params['namespace'] !== null && count( $params['namespace'] ) > 1
				) {
					$sortby["{$p}_from_namespace"] = 'int';
				}
			}
		}
		$sortby[$bl_from] = 'int';

		// Now use the $sortby to figure out the continuation
		$continueFields = array_keys( $sortby );
		$continueTypes = array_values( $sortby );
		if ( $params['continue'] !== null ) {
			$continueValues = $this->parseContinueParamOrDie( $params['continue'], $continueTypes );
			$conds = array_combine( $continueFields, $continueValues );
			$this->addWhere( $db->buildComparison( '>=', $conds ) );
		}

		// Populate the rest of the query
		[ $idxNoFromNS, $idxWithFromNS ] = $settings['indexes'] ?? [ '', '' ];
		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
		if ( isset( $this->linksMigration::$mapping[$settings['linktable']] ) ) {
			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
			$queryInfo = $this->linksMigration->getQueryInfo( $settings['linktable'] );
			$this->addTables( [ 'page', ...$queryInfo['tables'] ] );
			$this->addJoinConds( $queryInfo['joins'] );
			// TODO: Move to links migration
			if ( in_array( 'linktarget', $queryInfo['tables'] ) ) {
				$idxWithFromNS .= '_target_id';
			}
		} else {
			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
			$this->addTables( [ $settings['linktable'], 'page' ] );
		}
		$this->addWhere( "$bl_from = page_id" );

		if ( $this->getModuleName() === 'redirects' ) {
			$this->addWhereFld( 'rd_interwiki', '' );
		}

		$this->addFields( array_keys( $sortby ) );
		$this->addFields( [ 'bl_namespace' => $bl_namespace, 'bl_title' => $bl_title ] );
		if ( $resultPageSet === null ) {
			$fld_pageid = isset( $prop['pageid'] );
			$fld_title = isset( $prop['title'] );
			$fld_redirect = isset( $prop['redirect'] );

			$this->addFieldsIf( 'page_id', $fld_pageid );
			$this->addFieldsIf( [ 'page_title', 'page_namespace' ], $fld_title );
			$this->addFieldsIf( 'page_is_redirect', $fld_redirect );

			// prop=redirects
			$fld_fragment = isset( $prop['fragment'] );
			$this->addFieldsIf( 'rd_fragment', $fld_fragment );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}

		$this->addFieldsIf( 'page_namespace', $miser_ns !== null );

		if ( $hasNS && $map ) {
			// Can't use LinkBatch because it throws away Special titles.
			// And we already have the needed data structure anyway.
			$this->addWhere( $db->makeWhereFrom2d( $map, $bl_namespace, $bl_title ) );
		} else {
			$where = [];
			foreach ( $titles as $t ) {
				if ( $t->getNamespace() == $bl_namespace ) {
					$where[] = $db->expr( $bl_title, '=', $t->getDBkey() );
				}
			}
			$this->addWhere( $db->orExpr( $where ) );
		}

		if ( $params['show'] !== null ) {
			// prop=redirects only
			$show = array_fill_keys( $params['show'], true );
			if ( ( isset( $show['fragment'] ) && isset( $show['!fragment'] ) ) ||
				( isset( $show['redirect'] ) && isset( $show['!redirect'] ) )
			) {
				$this->dieWithError( 'apierror-show' );
			}
			$this->addWhereIf( $db->expr( 'rd_fragment', '!=', '' ), isset( $show['fragment'] ) );
			$this->addWhereIf( [ 'rd_fragment' => '' ], isset( $show['!fragment'] ) );
			$this->addWhereIf( [ 'page_is_redirect' => 1 ], isset( $show['redirect'] ) );
			$this->addWhereIf( [ 'page_is_redirect' => 0 ], isset( $show['!redirect'] ) );
		}

		// Override any ORDER BY from above with what we calculated earlier.
		$this->addOption( 'ORDER BY', array_keys( $sortby ) );

		// MySQL's optimizer chokes if we have too many values in "$bl_title IN
		// (...)" and chooses the wrong index, so specify the correct index to
		// use for the query. See T139056 for details.
		if ( !empty( $settings['indexes'] ) ) {
			if (
				$params['namespace'] !== null &&
				count( $params['namespace'] ) == 1 &&
				!empty( $settings['from_namespace'] )
			) {
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				$this->addOption( 'USE INDEX', [ $settings['linktable'] => $idxWithFromNS ] );
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
			} elseif ( !isset( $this->linksMigration::$mapping[$settings['linktable']] ) ) {
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				$this->addOption( 'USE INDEX', [ $settings['linktable'] => $idxNoFromNS ] );
			}
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$res = $this->select( __METHOD__ );

		if ( $resultPageSet === null ) {
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable set when used
			if ( $fld_title ) {
				$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
			}

			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinue( $row, $sortby );
					break;
				}

				if ( $miser_ns !== null && !in_array( $row->page_namespace, $miser_ns ) ) {
					// Miser mode namespace check
					continue;
				}

				// Get the ID of the current page
				$id = $map[$row->bl_namespace][$row->bl_title];

				$vals = [];
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable set when used
				if ( $fld_pageid ) {
					$vals['pageid'] = (int)$row->page_id;
				}
				if ( $fld_title ) {
					ApiQueryBase::addTitleInfo( $vals,
						Title::makeTitle( $row->page_namespace, $row->page_title )
					);
				}
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable set when used
				if ( $fld_fragment && $row->rd_fragment !== '' ) {
					$vals['fragment'] = $row->rd_fragment;
				}
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable set when used
				if ( $fld_redirect ) {
					$vals['redirect'] = (bool)$row->page_is_redirect;
				}
				$fit = $this->addPageSubItem( $id, $vals );
				if ( !$fit ) {
					$this->setContinue( $row, $sortby );
					break;
				}
			}
		} else {
			$titles = [];
			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinue( $row, $sortby );
					break;
				}

				if ( $miser_ns !== null && !in_array( $row->page_namespace, $miser_ns ) ) {
					// Miser mode namespace check
					continue;
				}

				$titles[] = Title::makeTitle( $row->page_namespace, $row->page_title );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	private function setContinue( \stdClass $row, array $sortby ) {
		$cont = [];
		foreach ( $sortby as $field => $v ) {
			$cont[] = $row->$field;
		}
		$this->setContinueEnumParameter( 'continue', implode( '|', $cont ) );
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$settings = self::$settings[$this->getModuleName()];

		$ret = [
			'prop' => [
				ParamValidator::PARAM_TYPE => [
					'pageid',
					'title',
				],
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'pageid|title',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace',
			],
			'show' => null, // Will be filled/removed below
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];

		if ( empty( $settings['from_namespace'] ) &&
		$this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'api-help-param-limited-in-miser-mode',
			];
		}

		if ( !empty( $settings['showredirects'] ) ) {
			$ret['prop'][ParamValidator::PARAM_TYPE][] = 'redirect';
			$ret['prop'][ParamValidator::PARAM_DEFAULT] .= '|redirect';
		}
		if ( isset( $settings['props'] ) ) {
			$ret['prop'][ParamValidator::PARAM_TYPE] = array_merge(
				$ret['prop'][ParamValidator::PARAM_TYPE], $settings['props']
			);
		}

		$show = [];
		if ( !empty( $settings['showredirects'] ) ) {
			$show[] = 'redirect';
			$show[] = '!redirect';
		}
		if ( isset( $settings['show'] ) ) {
			$show = array_merge( $show, $settings['show'] );
		}
		if ( $show ) {
			$ret['show'] = [
				ParamValidator::PARAM_TYPE => $show,
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			];
		} else {
			unset( $ret['show'] );
		}

		return $ret;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$settings = self::$settings[$this->getModuleName()];
		$name = $this->getModuleName();
		$path = $this->getModulePath();
		$title = $settings['exampletitle'] ?? Title::newMainPage()->getPrefixedText();
		$etitle = rawurlencode( $title );

		return [
			"action=query&prop={$name}&titles={$etitle}"
				=> "apihelp-$path-example-simple",
			"action=query&generator={$name}&titles={$etitle}&prop=info"
				=> "apihelp-$path-example-generator",
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		$name = ucfirst( $this->getModuleName() );
		return "https://www.mediawiki.org/wiki/Special:MyLanguage/API:{$name}";
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryBacklinksprop::class, 'ApiQueryBacklinksprop' );
