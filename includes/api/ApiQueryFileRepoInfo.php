<?php
/**
 * Copyright © 2013 Mark Holmquist <mtraceur@member.fsf.org>
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
 * @since 1.22
 */

/**
 * A query action to return meta information about the foreign file repos
 * configured on the wiki.
 *
 * @ingroup API
 */
class ApiQueryFileRepoInfo extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'fri' );
	}

	protected function getInitialisedRepoGroup() {
		$repoGroup = RepoGroup::singleton();
		$repoGroup->initialiseRepos();

		return $repoGroup;
	}

	public function execute() {
		global $wgEnableUploads;

		$params = $this->extractRequestParams();
		$props = array_flip( $params['prop'] );

		$repos = array();

		$repoGroup = $this->getInitialisedRepoGroup();

		$repoGroup->forEachForeignRepo( function ( $repo ) use ( &$repos, $props ) {
			$repoProps = $repo->getInfo();

			if ( $wgForeignUploadTargets[$repoProps['name']] ) {
				$repoProps['canUpload'] = true;
			}

			$repos[] = array_intersect_key( $repoProps, $props );
		} );

		$localInfo = $repoGroup->getLocalRepo()->getInfo();
		$localInfo['canUpload'] = $wgEnableUploads;
		$repos[] = array_intersect_key( $localInfo, $props );

		$result = $this->getResult();
		ApiResult::setIndexedTagName( $repos, 'repo' );
		ApiResult::setArrayTypeRecursive( $repos, 'assoc' );
		ApiResult::setArrayType( $repos, 'array' );
		$result->addValue( array( 'query' ), 'repos', $repos );
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		$props = $this->getProps();

		return array(
			'prop' => array(
				ApiBase::PARAM_DFLT => join( '|', $props ),
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $props,
			),
		);
	}

	public function getProps() {
		return array(
			'name',
			'displayname',
			'rootUrl',
			'url',
			'thumbUrl',
			'initialCapital',
			'descBaseUrl',
			'scriptDirUrl',
			'fetchDescription',
			'favicon',
			'canUpload',
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&meta=filerepoinfo&friprop=apiurl|name|displayname'
				=> 'apihelp-query+filerepoinfo-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Filerepoinfo';
	}
}
