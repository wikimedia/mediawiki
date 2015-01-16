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
		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );

		$fld_displayname = isset( $prop['displayname'] );
		$fld_description = isset( $prop['description'] );
		$fld_hitcount = isset( $prop['hitcount'] );
		$fld_defined = isset( $prop['defined'] );
		$fld_source = isset( $prop['source'] );
		$fld_active = isset( $prop['active'] );

		$limit = $params['limit'];
		$result = $this->getResult();

		$extensionDefinedTags = array_fill_keys( ChangeTags::listExtensionDefinedTags(), 0 );
		$explicitlyDefinedTags = array_fill_keys( ChangeTags::listExplicitlyDefinedTags(), 0 );
		$extensionActivatedTags = array_fill_keys( ChangeTags::listExtensionActivatedTags(), 0 );

		$definedTags = array_merge( $extensionDefinedTags, $explicitlyDefinedTags );

		# Fetch defined tags that aren't past the continuation
		if ( $params['continue'] !== null ) {
			$cont = $params['continue'];
			$tags = array_filter( array_keys( $definedTags ), function ( $v ) use ( $cont ) {
				return $v >= $cont;
			} );
			$tags = array_fill_keys( $tags, 0 );
		} else {
			$tags = $definedTags;
		}

		# Merge in all used tags
		$this->addTables( 'change_tag' );
		$this->addFields( 'ct_tag' );
		$this->addFields( array( 'hitcount' => $fld_hitcount ? 'COUNT(*)' : '0' ) );
		$this->addOption( 'LIMIT', $limit + 1 );
		$this->addOption( 'GROUP BY', 'ct_tag' );
		$this->addWhereRange( 'ct_tag', 'newer', $params['continue'], null );
		$res = $this->select( __METHOD__ );
		foreach ( $res as $row ) {
			$tags[$row->ct_tag] = (int)$row->hitcount;
		}

		# Now make sure the array is sorted for proper continuation
		ksort( $tags );

		$count = 0;
		foreach ( $tags as $tagName => $hitcount ) {
			if ( ++$count > $limit ) {
				$this->setContinueEnumParameter( 'continue', $tagName );
				break;
			}

			$tag = array();
			$tag['name'] = $tagName;

			if ( $fld_displayname ) {
				$tag['displayname'] = ChangeTags::tagDescription( $tagName );
			}

			if ( $fld_description ) {
				$msg = $this->msg( "tag-$tagName-description" );
				$tag['description'] = $msg->exists() ? $msg->text() : '';
			}

			if ( $fld_hitcount ) {
				$tag['hitcount'] = $hitcount;
			}

			$isExtension = isset( $extensionDefinedTags[$tagName] );
			$isExplicit = isset( $explicitlyDefinedTags[$tagName] );

			if ( $fld_defined ) {
				$tag['defined'] = $isExtension || $isExplicit;
			}

			if ( $fld_source ) {
				$tag['source'] = array();
				if ( $isExtension ) {
					$tag['source'][] = 'extension';
				}
				if ( $isExplicit ) {
					$tag['source'][] = 'manual';
				}
			}

			if ( $fld_active ) {
				$tag['active'] = $isExplicit || isset( $extensionActivatedTags[$tagName] );
			}

			$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $tag );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $tagName );
				break;
			}
		}

		$result->addIndexedTagName( array( 'query', $this->getModuleName() ), 'tag' );
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
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
					'hitcount',
					'defined',
					'source',
					'active',
				),
				ApiBase::PARAM_ISMULTI => true
			)
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=tags&tgprop=displayname|description|hitcount|defined'
				=> 'apihelp-query+tags-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Tags';
	}
}
