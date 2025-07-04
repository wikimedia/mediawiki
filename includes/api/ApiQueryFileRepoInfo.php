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

namespace MediaWiki\Api;

use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\MainConfigNames;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * A query action to return meta information about the foreign file repos
 * configured on the wiki.
 *
 * @ingroup API
 */
class ApiQueryFileRepoInfo extends ApiQueryBase {

	private RepoGroup $repoGroup;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		RepoGroup $repoGroup
	) {
		parent::__construct( $query, $moduleName, 'fri' );
		$this->repoGroup = $repoGroup;
	}

	public function execute() {
		$conf = $this->getConfig();

		$params = $this->extractRequestParams();
		$props = array_fill_keys( $params['prop'], true );

		$repos = [];

		$foreignTargets = $conf->get( MainConfigNames::ForeignUploadTargets );

		$this->repoGroup->forEachForeignRepo(
			static function ( FileRepo $repo ) use ( &$repos, $props, $foreignTargets ) {
				$repoProps = $repo->getInfo();
				$repoProps['canUpload'] = in_array( $repoProps['name'], $foreignTargets );

				$repos[] = array_intersect_key( $repoProps, $props );
			}
		);

		$localInfo = $this->repoGroup->getLocalRepo()->getInfo();
		$localInfo['canUpload'] = $conf->get( MainConfigNames::EnableUploads );
		$repos[] = array_intersect_key( $localInfo, $props );

		$result = $this->getResult();
		ApiResult::setIndexedTagName( $repos, 'repo' );
		ApiResult::setArrayTypeRecursive( $repos, 'assoc' );
		ApiResult::setArrayType( $repos, 'array' );
		$result->addValue( [ 'query' ], 'repos', $repos );
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$props = $this->getProps();

		return [
			'prop' => [
				ParamValidator::PARAM_DEFAULT => implode( '|', $props ),
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => $props,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
		];
	}

	public function getProps(): array {
		$props = [];
		$this->repoGroup->forEachForeignRepo( static function ( FileRepo $repo ) use ( &$props ) {
			$props = array_merge( $props, array_keys( $repo->getInfo() ) );
		} );

		$propValues = array_values( array_unique( array_merge(
			$props,
			array_keys( $this->repoGroup->getLocalRepo()->getInfo() )
		) ) );

		$propValues[] = 'canUpload';

		sort( $propValues );
		return $propValues;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$examples = [];

		$props = array_intersect( [ 'apiurl', 'name', 'displayname' ], $this->getProps() );
		if ( $props ) {
			$examples['action=query&meta=filerepoinfo&friprop=' . implode( '|', $props )] =
				'apihelp-query+filerepoinfo-example-simple';
		}

		return $examples;
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Filerepoinfo';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryFileRepoInfo::class, 'ApiQueryFileRepoInfo' );
