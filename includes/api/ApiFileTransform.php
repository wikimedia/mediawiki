<?php
/**
 *
 * Created on January 3rd, 2013
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

class ApiFileTransform extends ApiBase {
	private $mPageSet = null;

	/**
	 * Add all items from $values into the result
	 * @param array $result Output
	 * @param array $values Values to add
	 * @param string $flag The name of the boolean flag to mark this element
	 * @param string $name If given, name of the value
	 */
	private static function addValues( array &$result, $values, $flag = null, $name = null ) {
		foreach ( $values as $val ) {
			if ( $val instanceof Title ) {
				$v = array();
				ApiQueryBase::addTitleInfo( $v, $val );
			} elseif ( $name !== null ) {
				$v = array( $name => $val );
			} else {
				$v = $val;
			}
			if ( $flag !== null ) {
				$v[$flag] = true;
			}
			$result[] = $v;
		}
	}

	public function execute() {
		$params = $this->extractRequestParams();
		if ( array_key_exists( 'rotation', $params ) ) {
			$this->op = 'rotate';
			$this->rotation = $params['rotation'];
		} else {
			// @fixme report this error, must provide one of 'rotation', ....?
			// or better to use an explicit op param?
			return;
		}

		$continuationManager = new ApiContinuationManager( $this, array(), array() );
		$this->setContinuationManager( $continuationManager );

		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$result = array();

		self::addValues( $result, $pageSet->getInvalidTitlesAndReasons(), 'invalid' );
		self::addValues( $result, $pageSet->getSpecialTitles(), 'special', 'title' );
		self::addValues( $result, $pageSet->getMissingPageIDs(), 'missing', 'pageid' );
		self::addValues( $result, $pageSet->getMissingRevisionIDs(), 'missing', 'revid' );
		self::addValues( $result, $pageSet->getInterwikiTitlesAsResult() );

		foreach ( $pageSet->getTitles() as $title ) {
			$r = array();
			$r['id'] = $title->getArticleID();
			ApiQueryBase::addTitleInfo( $r, $title );
			if ( !$title->exists() ) {
				$r['missing'] = true;
			}

			$file = wfFindFile( $title, array( 'latest' => true ) );
			$transform = $this->createTransform( $file );

			$status = $transform->queue();
			if ( $status->isGood() ) {
				$r['result'] = 'Queued';
				// @fixme provide the id number ... and an api method to check its status
			} else {
				$r['result'] = 'Failure';
				$r['errormessage'] = $status->getMessage()->text();
			}

			$result[] = $r;
		}

		$apiResult = $this->getResult();
		ApiResult::setIndexedTagName( $result, 'page' );
		$apiResult->addValue( null, $this->getModuleName(), $result );

		$this->setContinuationManager( null );
		$continuationManager->setContinuationIntoResult( $apiResult );
	}

	/**
	 * @return FileTransform
	 */
	function createTransform( File $file ) {
		switch ( $this->op ) {
		case 'rotate':
			return new RotateFileTransform( $file, $this->getUser(), $this->rotation );
		default:
			// should not happen :D
			return null;
		}
	}

	/**
	 * Get a cached instance of an ApiPageSet object
	 * @return ApiPageSet
	 */
	private function getPageSet() {
		if ( $this->mPageSet === null ) {
			$this->mPageSet = new ApiPageSet( $this, 0, NS_FILE );
		}

		return $this->mPageSet;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = array(
			// at least one operation is required
			'rotation' => array(
				ApiBase::PARAM_TYPE => array( '90', '180', '270' ),
			),
			// @todo add 'crop' for visual cropping
			// @todo add 'trim' for timed-media trimming
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
		);
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return array(
			'action=filetransform&titles=File:Example.jpg&rotation=90&token=123ABC'
				=> 'apihelp-filetransform-example-simple',
			'action=filetransform&generator=categorymembers&gcmtitle=Category:Flip&gcmtype=file&' .
				'rotation=180&token=123ABC'
				=> 'apihelp-filetransform-example-generator',
		);
	}
}
