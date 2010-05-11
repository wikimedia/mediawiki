<?php

/**
 * Created on Jul 9, 2009
 *
 * API for MediaWiki 1.8+
 *
 * Copyright © 2009
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiQueryBase.php' );
}

/**
 * Query module to enumerate change tags.
 *
 * @ingroup API
 */
class ApiQueryTags extends ApiQueryBase {

	private $limit, $result;
	private $fld_displayname = false, $fld_description = false,
			$fld_hitcount = false;

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'tg' );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );

		$this->fld_displayname = isset( $prop['displayname'] );
		$this->fld_description = isset( $prop['description'] );
		$this->fld_hitcount = isset( $prop['hitcount'] );

		$this->limit = $params['limit'];
		$this->result = $this->getResult();

		$pageSet = $this->getPageSet();
		$titles = $pageSet->getTitles();
		$data = array();

		$this->addTables( 'change_tag' );
		$this->addFields( 'ct_tag' );

		if ( $this->fld_hitcount ) {
			$this->addFields( 'count(*) AS hitcount' );
		}

		$this->addOption( 'LIMIT', $this->limit + 1 );
		$this->addOption( 'GROUP BY', 'ct_tag' );
		$this->addWhereRange( 'ct_tag', 'newer', $params['continue'], null );

		$res = $this->select( __METHOD__ );

		$ok = true;

		while ( $row = $res->fetchObject() ) {
			if ( !$ok ) {
				break;
			}
			$ok = $this->doTag( $row->ct_tag, $row->hitcount );
		}

		// include tags with no hits yet
		foreach ( ChangeTags::listDefinedTags() as $tag ) {
			if ( !$ok ) {
				break;
			}
			$ok = $this->doTag( $tag, 0 );
		}

		$this->result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'tag' );
	}

	private function doTag( $tagName, $hitcount ) {
		static $count = 0;
		static $doneTags = array();

		if ( in_array( $tagName, $doneTags ) ) {
			return true;
		}

		if ( ++$count > $this->limit ) {
			$this->setContinueEnumParameter( 'continue', $tagName );
			return false;
		}

		$tag = array();
		$tag['name'] = $tagName;

		if ( $this->fld_displayname ) {
			$tag['displayname'] = ChangeTags::tagDescription( $tagName );
		}

		if ( $this->fld_description ) {
			$msg = wfMsg( "tag-$tagName-description" );
			$msg = wfEmptyMsg( "tag-$tagName-description", $msg ) ? '' : $msg;
			$tag['description'] = $msg;
		}

		if ( $this->fld_hitcount ) {
			$tag['hitcount'] = $hitcount;
		}

		$doneTags[] = $tagName;

		$fit = $this->result->addValue( array( 'query', $this->getModuleName() ), null, $tag );
		if ( !$fit ) {
			$this->setContinueEnumParameter( 'continue', $tagName );
			return false;
		}

		return true;
	}

	public function getAllowedParams() {
		return array(
			'continue' => array(
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'prop' => array(
				ApiBase::PARAM_DFLT => 'name',
				ApiBase::PARAM_TYPE => array(
					'name',
					'displayname',
					'description',
					'hitcount'
				),
				ApiBase::PARAM_ISMULTI => true
			)
		);
	}

	public function getParamDescription() {
		return array(
			'continue' => 'When more results are available, use this to continue',
			'limit' => 'The maximum number of tags to list',
			'prop' => 'Which properties to get',
		);
	}

	public function getDescription() {
		return 'List change tags';
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&list=tags&tgprop=displayname|description|hitcount'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
