<?php
/**
 * API module to handle links table back-queries
 *
 * Copyright © 2014 Wikimedia Foundation and contributors
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
 * @since 1.24
 */

/**
 * This implements prop=redirects, prop=linkshere, prop=catmembers,
 * prop=transcludedin, and prop=fileusage
 *
 * @ingroup API
 * @since 1.24
 */
class ApiQueryBacklinksprop extends ApiQueryGeneratorBase {

	// Data for the various modules implemented by this class
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
		],
		'transcludedin' => [
			'code' => 'ti',
			'prefix' => 'tl',
			'linktable' => 'templatelinks',
			'indexes' => [ 'tl_namespace', 'tl_backlinks_namespace' ],
			'from_namespace' => true,
			'showredirects' => true,
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
		],
	];

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, self::$settings[$moduleName]['code'] );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 */
	private function run( ApiPageSet $resultPageSet = null ) {
		$settings = self::$settings[$this->getModuleName()];

		$db = $this->getDB();
		$params = $this->extractRequestParams();
		$prop = array_flip( $params['prop'] );
		$emptyString = $db->addQuotes( '' );

		$pageSet = $this->getPageSet();
		$titles = $pageSet->getGoodAndMissingTitles();
		$map = $pageSet->getGoodAndMissingTitlesByNamespace();

		// Add in special pages, they can theoretically have backlinks too.
		// (although currently they only do for prop=redirects)
		foreach ( $pageSet->getSpecialTitles() as $id => $title ) {
			$titles[] = $title;
			$map[$title->getNamespace()][$title->getDBkey()] = $id;
		}

		// Determine our fields to query on
		$p = $settings['prefix'];
		$hasNS = !isset( $settings['to_namespace'] );
		if ( $hasNS ) {
			$bl_namespace = "{$p}_namespace";
			$bl_title = "{$p}_title";
		} else {
			$bl_namespace = $settings['to_namespace'];
			$bl_title = "{$p}_to";

			$titles = array_filter( $titles, function ( $t ) use ( $bl_namespace ) {
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
			$sortby[$bl_namespace] = 'ns';
		}
		$theTitle = null;
		foreach ( $map as $nsTitles ) {
			reset( $nsTitles );
			$key = key( $nsTitles );
			if ( $theTitle === null ) {
				$theTitle = $key;
			}
			if ( count( $nsTitles ) > 1 || $key !== $theTitle ) {
				$sortby[$bl_title] = 'title';
				break;
			}
		}
		$miser_ns = null;
		if ( $params['namespace'] !== null ) {
			if ( empty( $settings['from_namespace'] ) ) {
				if ( $this->getConfig()->get( 'MiserMode' ) ) {
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
		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != count( $sortby ) );
			$where = '';
			$i = count( $sortby ) - 1;
			foreach ( array_reverse( $sortby, true ) as $field => $type ) {
				$v = $cont[$i];
				switch ( $type ) {
					case 'ns':
					case 'int':
						$v = (int)$v;
						$this->dieContinueUsageIf( $v != $cont[$i] );
						break;
					default:
						$v = $db->addQuotes( $v );
						break;
				}

				if ( $where === '' ) {
					$where = "$field >= $v";
				} else {
					$where = "$field > $v OR ($field = $v AND ($where))";
				}

				$i--;
			}
			$this->addWhere( $where );
		}

		// Populate the rest of the query
		$this->addTables( [ $settings['linktable'], 'page' ] );
		$this->addWhere( "$bl_from = page_id" );

		if ( $this->getModuleName() === 'redirects' ) {
			$this->addWhere( "rd_interwiki = $emptyString OR rd_interwiki IS NULL" );
		}

		$this->addFields( array_keys( $sortby ) );
		$this->addFields( [ 'bl_namespace' => $bl_namespace, 'bl_title' => $bl_title ] );
		if ( is_null( $resultPageSet ) ) {
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

		if ( $hasNS ) {
			// Can't use LinkBatch because it throws away Special titles.
			// And we already have the needed data structure anyway.
			$this->addWhere( $db->makeWhereFrom2d( $map, $bl_namespace, $bl_title ) );
		} else {
			$where = [];
			foreach ( $titles as $t ) {
				if ( $t->getNamespace() == $bl_namespace ) {
					$where[] = "$bl_title = " . $db->addQuotes( $t->getDBkey() );
				}
			}
			$this->addWhere( $db->makeList( $where, LIST_OR ) );
		}

		if ( $params['show'] !== null ) {
			// prop=redirects only
			$show = array_flip( $params['show'] );
			if ( isset( $show['fragment'] ) && isset( $show['!fragment'] ) ||
				isset( $show['redirect'] ) && isset( $show['!redirect'] )
			) {
				$this->dieWithError( 'apierror-show' );
			}
			$this->addWhereIf( "rd_fragment != $emptyString", isset( $show['fragment'] ) );
			$this->addWhereIf(
				"rd_fragment = $emptyString OR rd_fragment IS NULL",
				isset( $show['!fragment'] )
			);
			$this->addWhereIf( [ 'page_is_redirect' => 1 ], isset( $show['redirect'] ) );
			$this->addWhereIf( [ 'page_is_redirect' => 0 ], isset( $show['!redirect'] ) );
		}

		// Override any ORDER BY from above with what we calculated earlier.
		$this->addOption( 'ORDER BY', array_keys( $sortby ) );

		// MySQL's optimizer chokes if we have too many values in "$bl_title IN
		// (...)" and chooses the wrong index, so specify the correct index to
		// use for the query. See T139056 for details.
		if ( !empty( $settings['indexes'] ) ) {
			list( $idxNoFromNS, $idxWithFromNS ) = $settings['indexes'];
			if ( $params['namespace'] !== null && !empty( $settings['from_namespace'] ) ) {
				$this->addOption( 'USE INDEX', [ $settings['linktable'] => $idxWithFromNS ] );
			} else {
				$this->addOption( 'USE INDEX', [ $settings['linktable'] => $idxNoFromNS ] );
			}
		}

		// MySQL (or at least 5.5.5-10.0.23-MariaDB) chooses a really bad query
		// plan if it thinks there will be more matching rows in the linktable
		// than are in page. Use STRAIGHT_JOIN here to force it to use the
		// intended, fast plan. See T145079 for details.
		$this->addOption( 'STRAIGHT_JOIN' );

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$res = $this->select( __METHOD__ );

		if ( is_null( $resultPageSet ) ) {
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
				if ( $fld_pageid ) {
					$vals['pageid'] = (int)$row->page_id;
				}
				if ( $fld_title ) {
					ApiQueryBase::addTitleInfo( $vals,
						Title::makeTitle( $row->page_namespace, $row->page_title )
					);
				}
				if ( $fld_fragment && $row->rd_fragment !== null && $row->rd_fragment !== '' ) {
					$vals['fragment'] = $row->rd_fragment;
				}
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
				$titles[] = Title::makeTitle( $row->page_namespace, $row->page_title );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	private function setContinue( $row, $sortby ) {
		$cont = [];
		foreach ( $sortby as $field => $v ) {
			$cont[] = $row->$field;
		}
		$this->setContinueEnumParameter( 'continue', implode( '|', $cont ) );
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		$settings = self::$settings[$this->getModuleName()];

		$ret = [
			'prop' => [
				ApiBase::PARAM_TYPE => [
					'pageid',
					'title',
				],
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'pageid|title',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'namespace' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
			],
			'show' => null, // Will be filled/removed below
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];

		if ( empty( $settings['from_namespace'] ) && $this->getConfig()->get( 'MiserMode' ) ) {
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'api-help-param-limited-in-miser-mode',
			];
		}

		if ( !empty( $settings['showredirects'] ) ) {
			$ret['prop'][ApiBase::PARAM_TYPE][] = 'redirect';
			$ret['prop'][ApiBase::PARAM_DFLT] .= '|redirect';
		}
		if ( isset( $settings['props'] ) ) {
			$ret['prop'][ApiBase::PARAM_TYPE] = array_merge(
				$ret['prop'][ApiBase::PARAM_TYPE], $settings['props']
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
				ApiBase::PARAM_TYPE => $show,
				ApiBase::PARAM_ISMULTI => true,
			];
		} else {
			unset( $ret['show'] );
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		$settings = self::$settings[$this->getModuleName()];
		$name = $this->getModuleName();
		$path = $this->getModulePath();
		$title = $settings['exampletitle'] ?? 'Main Page';
		$etitle = rawurlencode( $title );

		return [
			"action=query&prop={$name}&titles={$etitle}"
				=> "apihelp-$path-example-simple",
			"action=query&generator={$name}&titles={$etitle}&prop=info"
				=> "apihelp-$path-example-generator",
		];
	}

	public function getHelpUrls() {
		$name = ucfirst( $this->getModuleName() );
		return "https://www.mediawiki.org/wiki/Special:MyLanguage/API:{$name}";
	}
}
