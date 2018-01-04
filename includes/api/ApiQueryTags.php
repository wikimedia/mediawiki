<?php
/**
 *
 *
 * Created on Jul 9, 2009
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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Query module to enumerate change tags.
 *
 * @ingroup API
 */
class ApiQueryTags extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'tg' );
	}

	public function execute() {
		global $wgChangeTagsSchemaMigrationStage;
		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );

		$fld_displayname = isset( $prop['displayname'] );
		$fld_description = isset( $prop['description'] );
		$fld_hitcount = isset( $prop['hitcount'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_defined = isset( $prop['defined'] );
		$fld_source = isset( $prop['source'] );
		$fld_active = isset( $prop['active'] );

		$limit = $params['limit'];
		$result = $this->getResult();

		$softwareDefinedTags = array_fill_keys( ChangeTags::listSoftwareDefinedTags(), 0 );
		$explicitlyDefinedTags = array_fill_keys( ChangeTags::listExplicitlyDefinedTags(), 0 );
		$softwareActivatedTags = array_fill_keys( ChangeTags::listSoftwareActivatedTags(), 0 );

		if ( $wgChangeTagsSchemaMigrationStage === MIGRATION_NEW ) {
			$this->addTables( 'tag' );
			$this->addFields( [ 'tag_name', 'tag_count', 'tag_timestamp' ] );
			$this->addOption( 'LIMIT', $limit + 1 );
			$this->addWhereRange( 'tag_name', 'newer', $params['continue'], null );
			$res = $this->select( __METHOD__ );

			$hitCounts = [];
			$timestamps = [];
			foreach ( $res as $row ) {
				if ( $row->tag_count ) {
					$hitCounts[$row->tag_name] = (int)$row->tag_count;
					$timestamps[$row->tag_name] = (string)$row->tag_timestamp;
				}
			}
		} else {
			$hitCounts = ChangeTags::tagUsageStatistics();
			$timestamps = [];
		}

		$allHitCounts = array_merge( $softwareDefinedTags, $explicitlyDefinedTags, $hitCounts );
		$tags = array_keys( $allHitCounts );

		# Fetch defined tags that aren't past the continuation
		if ( $params['continue'] !== null ) {
			$cont = $params['continue'];
			$tags = array_filter( $tags, function ( $v ) use ( $cont ) {
				return $v >= $cont;
			} );
		}

		# Now make sure the array is sorted for proper continuation
		sort( $tags );

		$count = 0;
		foreach ( $tags as $tagName ) {
			if ( ++$count > $limit ) {
				$this->setContinueEnumParameter( 'continue', $tagName );
				break;
			}

			$tag = [];
			$tag['name'] = $tagName;

			if ( $fld_displayname ) {
				$tag['displayname'] = ChangeTags::tagDescription( $tagName, $this );
			}

			if ( $fld_description ) {
				$msg = $this->msg( "tag-$tagName-description" );
				$tag['description'] = $msg->exists() ? $msg->text() : '';
			}

			if ( $fld_hitcount ) {
				$tag['hitcount'] = $allHitCounts[$tagName];
			}

			if ( $fld_timestamp ) {
				$tag['timestamp'] = isset( $timestamps[$tagName] ) ?
					wfTimestamp( TS_ISO_8601, $timestamps[$tagName] ) :
					null;
			}

			$isSoftware = isset( $softwareDefinedTags[$tagName] );
			$isExplicit = isset( $explicitlyDefinedTags[$tagName] );

			if ( $fld_defined ) {
				$tag['defined'] = $isSoftware || $isExplicit;
			}

			if ( $fld_source ) {
				$tag['source'] = [];
				if ( $isSoftware ) {
					// TODO: Can we change this to 'software'?
					$tag['source'][] = 'extension';
				}
				if ( $isExplicit ) {
					$tag['source'][] = 'manual';
				}
			}

			if ( $fld_active ) {
				$tag['active'] = $isExplicit || isset( $softwareActivatedTags[$tagName] );
			}

			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $tag );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $tagName );
				break;
			}
		}

		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'tag' );
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return [
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ApiBase::PARAM_DFLT => 'name',
				ApiBase::PARAM_TYPE => [
					'name',
					'displayname',
					'description',
					'hitcount',
					'timestamp',
					'defined',
					'source',
					'active',
				],
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			]
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=tags&tgprop=displayname|description|hitcount|defined'
				=> 'apihelp-query+tags-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Tags';
	}
}
