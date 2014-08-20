<?php
/**
 * API module to handle links table back-queries
 *
 * Created on Aug 19, 2014
 *
 * Copyright © 2014 Brad Jorsch <bjorsch@wikimedia.org>
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
	private static $settings = array(
		'redirects' => array(
			'code' => 'rd',
			'prefix' => 'rd',
			'linktable' => 'redirect',
			'what' => 'redirects to',
			'description' => 'Returns all redirects to the given pages.',
			'props' => array(
				'fragment' => 'Fragment of each redirect, if any',
			),
			'showredirects' => false,
			'show' => array(
				'fragment' => 'Only show redirects with a fragment',
				'!fragment' => 'Only show redirects without a fragment',
			),
		),
		'linkshere' => array(
			'code' => 'lh',
			'prefix' => 'pl',
			'linktable' => 'pagelinks',
			'from_namespace' => true,
			'what' => 'pages linking to',
			'description' => 'Find all pages that link to the given pages.',
			'showredirects' => true,
		),
		'transcludedin' => array(
			'code' => 'ti',
			'prefix' => 'tl',
			'linktable' => 'templatelinks',
			'from_namespace' => true,
			'what' => 'pages transcluding',
			'description' => 'Find all pages that transclude the given pages.',
			'showredirects' => true,
		),
		'fileusage' => array(
			'code' => 'fu',
			'prefix' => 'il',
			'linktable' => 'imagelinks',
			'from_namespace' => true,
			'to_namespace' => NS_FILE,
			'what' => 'pages using',
			'exampletitle' => 'File:Example.jpg',
			'description' => 'Find all pages that use the given files.',
			'showredirects' => true,
		),
	);

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
	 * @param ApiPageSet $resultPageSet
	 */
	private function run( ApiPageSet $resultPageSet = null ) {
		$settings = self::$settings[$this->getModuleName()];

		$db = $this->getDB();
		$params = $this->extractRequestParams();
		$prop = array_flip( $params['prop'] );
		$emptyString = $db->addQuotes( '' );

		$pageSet = $this->getPageSet();
		$titles = $pageSet->getGoodTitles() + $pageSet->getMissingTitles();
		$map = $pageSet->getAllTitlesByNamespace();

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
			$map = array_intersect_key( $map, array( $bl_namespace => true ) );
		}
		$bl_from = "{$p}_from";

		if ( !$titles ) {
			return; // nothing to do
		}

		// Figure out what we're sorting by, and add associated WHERE clauses.
		// MySQL's query planner screws up if we include a field in ORDER BY
		// when it's constant in WHERE, so we have to test that for each field.
		$sortby = array();
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
			if ( empty( $settings['from_namespace'] ) && $this->getConfig()->get( 'MiserMode' ) ) {
				$miser_ns = $params['namespace'];
			} else {
				$this->addWhereFld( "{$p}_from_namespace", $params['namespace'] );
				if ( !empty( $settings['from_namespace'] ) && count( $params['namespace'] ) > 1 ) {
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
			$cont_ns = 0;
			$cont_title = '';
			foreach ( array_reverse( $sortby, true ) as $field => $type ) {
				$v = $cont[$i];
				switch ( $type ) {
					case 'ns':
						$cont_ns = (int)$v;
						/* fall through */
					case 'int':
						$v = (int)$v;
						$this->dieContinueUsageIf( $v != $cont[$i] );
						break;

					case 'title':
						$cont_title = $v;
						/* fall through */
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
		$this->addTables( array( $settings['linktable'], 'page' ) );
		$this->addWhere( "$bl_from = page_id" );

		if ( $this->getModuleName() === 'redirects' ) {
			$this->addWhere( "rd_interwiki = $emptyString OR rd_interwiki IS NULL" );
		}

		$this->addFields( array_keys( $sortby ) );
		$this->addFields( array( 'bl_namespace' => $bl_namespace, 'bl_title' => $bl_title ) );
		if ( is_null( $resultPageSet ) ) {
			$fld_pageid = isset( $prop['pageid'] );
			$fld_title = isset( $prop['title'] );
			$fld_redirect = isset( $prop['redirect'] );

			$this->addFieldsIf( 'page_id', $fld_pageid );
			$this->addFieldsIf( array( 'page_title', 'page_namespace' ), $fld_title );
			$this->addFieldsIf( 'page_is_redirect', $fld_redirect );

			// prop=redirects
			$fld_fragment = isset( $prop['fragment'] );
			$this->addFieldsIf( 'rd_fragment', $fld_fragment );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}

		$this->addFieldsIf( 'page_namespace', $miser_ns !== null );

		if ( $hasNS ) {
			$lb = new LinkBatch( $titles );
			$this->addWhere( $lb->constructSet( $p, $db ) );
		} else {
			$where = array();
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
				$this->dieUsageMsg( 'show' );
			}
			$this->addWhereIf( "rd_fragment != $emptyString", isset( $show['fragment'] ) );
			$this->addWhereIf(
				"rd_fragment = $emptyString OR rd_fragment IS NULL",
				isset( $show['!fragment'] )
			);
			$this->addWhereIf( array( 'page_is_redirect' => 1 ), isset( $show['redirect'] ) );
			$this->addWhereIf( array( 'page_is_redirect' => 0 ), isset( $show['!redirect'] ) );
		}

		// Override any ORDER BY from above with what we calculated earlier.
		$this->addOption( 'ORDER BY', array_keys( $sortby ) );

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

				$vals = array();
				if ( $fld_pageid ) {
					$vals['pageid'] = $row->page_id;
				}
				if ( $fld_title ) {
					ApiQueryBase::addTitleInfo( $vals,
						Title::makeTitle( $row->page_namespace, $row->page_title )
					);
				}
				if ( $fld_fragment && $row->rd_fragment !== null && $row->rd_fragment !== '' ) {
					$vals['fragment'] = $row->rd_fragment;
				}
				if ( $fld_redirect && $row->page_is_redirect ) {
					$vals['redirect'] = '';
				}
				$fit = $this->addPageSubItem( $id, $vals );
				if ( !$fit ) {
					$this->setContinue( $row, $sortby );
					break;
				}
			}
		} else {
			$titles = array();
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
		$cont = array();
		foreach ( $sortby as $field => $v ) {
			$cont[] = $row->$field;
		}
		$this->setContinueEnumParameter( 'continue', join( '|', $cont ) );
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		$settings = self::$settings[$this->getModuleName()];

		$ret = array(
			'prop' => array(
				ApiBase::PARAM_TYPE => array(
					'pageid',
					'title',
				),
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'pageid|title',
			),
			'namespace' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'continue' => null,
		);

		if ( !empty( $settings['showredirects'] ) ) {
			$ret['prop'][ApiBase::PARAM_TYPE][] = 'redirect';
			$ret['prop'][ApiBase::PARAM_DFLT] .= '|redirect';
		}
		if ( isset( $settings['props'] ) ) {
			$ret['prop'][ApiBase::PARAM_TYPE] = array_merge(
				$ret['prop'][ApiBase::PARAM_TYPE], array_keys( $settings['props'] )
			);
		}

		$show = array();
		if ( !empty( $settings['showredirects'] ) ) {
			$show[] = 'redirect';
			$show[] = '!redirect';
		}
		if ( isset( $settings['show'] ) ) {
			$show = array_merge( $show, array_keys( $settings['show'] ) );
		}
		if ( $show ) {
			$ret['show'] = array(
				ApiBase::PARAM_TYPE => $show,
				ApiBase::PARAM_ISMULTI => true,
			);
		}

		return $ret;
	}

	public function getParamDescription() {
		$settings = self::$settings[$this->getModuleName()];
		$p = $this->getModulePrefix();

		$ret = array(
			'prop' => array(
				'Which properties to get:',
			),
			'show' => array(
				'Show only items that meet this criteria.',
			),
			'namespace' => 'Only include pages in these namespaces',
			'limit' => 'How many to return',
			'continue' => 'When more results are available, use this to continue',
		);

		if ( empty( $settings['from_namespace'] ) && $this->getConfig()->get( 'MiserMode' ) ) {
			$ret['namespace'] = array(
				$ret['namespace'],
				"NOTE: Due to \$wgMiserMode, using this may result in fewer than \"{$p}limit\" results",
				'returned before continuing; in extreme cases, zero results may be returned.',
			);
			if ( isset( $ret['type'] ) ) {
				$ret['namespace'][] = "Note that you can use {$p}type=subcat or {$p}type=file " .
					"instead of {$p}namespace=14 or 6.";
			}
		}

		$props = array(
			'pageid' => 'Adds the ID of page',
			'title' => 'Adds the title and namespace ID of the page',
		);
		if ( !empty( $settings['showredirects'] ) ) {
			$props['redirect'] = 'Indicate if the page is a redirect';
		}
		if ( isset( $settings['props'] ) ) {
			$props += $settings['props'];
		}
		foreach ( $props as $k => $v ) {
			$ret['props'][] = sprintf( "%-9s - %s", $k, $v );
		}

		$show = array();
		if ( !empty( $settings['showredirects'] ) ) {
			$show += array(
				'redirect' => 'Only show redirects',
				'!redirect' => 'Only show non-redirects',
			);
		}
		if ( isset( $settings['show'] ) ) {
			$show += $settings['show'];
		}
		foreach ( $show as $k => $v ) {
			$ret['show'][] = sprintf( "%-9s - %s", $k, $v );
		}

		return $ret;
	}

	public function getDescription() {
		return self::$settings[$this->getModuleName()]['description'];
	}

	public function getExamples() {
		$settings = self::$settings[$this->getModuleName()];
		$name = $this->getModuleName();
		$what = $settings['what'];
		$title = isset( $settings['exampletitle'] ) ? $settings['exampletitle'] : 'Main Page';
		$etitle = rawurlencode( $title );

		return array(
			"api.php?action=query&prop={$name}&titles={$etitle}"
				=> "Get a list of $what [[$title]]",
			"api.php?action=query&generator={$name}&titles={$etitle}&prop=info"
				=> "Get information about $what [[$title]]",
		);
	}

	public function getHelpUrls() {
		$name = $this->getModuleName();
		$prefix = $this->getModulePrefix();
		return "https://www.mediawiki.org/wiki/API:Properties#{$name}_.2F_{$prefix}";
	}
}
