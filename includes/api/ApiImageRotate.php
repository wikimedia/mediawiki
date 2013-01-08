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

class ApiImageRotate extends ApiBase {

	private $mPageSet;

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	/**
	 * Add all items from $values into the result
	 * @param $result array output
	 * @param $values array values to add
	 * @param $flag string the name of the boolean flag to mark this element
	 * @param $name string if given, name of the value
	 */
	private static function addValues( array &$result, $values, $flag = null, $name = null ) {
		foreach ( $values as $val ) {
			if( $val instanceof Title ) {
				$v = array();
				ApiQueryBase::addTitleInfo( $v, $val );
			} elseif( $name !== null ) {
				$v = array( $name => $val );
			} else {
				$v = $val;
			}
			if( $flag !== null ) {
				$v[$flag] = '';
			}
			$result[] = $v;
		}
	}


	public function execute() {
		$params = $this->extractRequestParams();
		$rotation = $params[ 'rotation' ];
		$user = $this->getUser();

		if( is_null( $rotation ) || $rotation % 90 ) {
			$this->dieUsage( "Rotation: {$rotation}", 'rotation must be multiple of 90 degrees' );
		}

		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$result = array();
		$result = array();

		self::addValues( $result, $pageSet->getInvalidTitles(), 'invalid', 'title' );
		self::addValues( $result, $pageSet->getSpecialTitles(), 'special', 'title' );
		self::addValues( $result, $pageSet->getMissingPageIDs(), 'missing', 'pageid' );
		self::addValues( $result, $pageSet->getMissingRevisionIDs(), 'missing', 'revid' );
		self::addValues( $result, $pageSet->getMissingTitles(), 'missing' );
		self::addValues( $result, $pageSet->getInterwikiTitlesAsResult() );

		foreach ( $pageSet->getTitles() as $title ) {
			$file = wfFindFile( $title );

			$r = array();
			$r[ 'title' ] = $title->getFullText();
			if ( !$file ) {
				$r['missing'] = '';
				$r['result'] = 'Failure';
				$result[] = $r;
				continue;
			}
			$handler = $file->getHandler();
			if ( !$handler || !$handler->canRotate() ) {
				$r['invalid'] = '';
				$r['result'] = 'Failure';
				$result[] = $r;
				continue;
			}

			// Check whether we're allowed to rotate this file
			$this->checkPermissions( $this->getUser(), $file->getTitle() );

			$srcPath = $file->getLocalRefPath();
			$ext = strtolower( pathinfo( "$srcPath", PATHINFO_EXTENSION ) );
			$tmpFile = TempFSFile::factory( 'rotate_', $ext);
			$dstPath = $tmpFile->getPath();
			$err = $handler->rotate( $file, array(
				"srcPath" => $srcPath,
				"dstPath" => $dstPath,
				"rotation"=> $rotation
			) );
			if ( !$err ) {
				$comment = wfMessage( 'rotate-comment' )->numParams( $rotation )->text();
				$status = $file->upload( $dstPath,
					$comment, $comment, 0, false, false, $this->getUser() );
				if ( $status->isGood() ) {
					$r['result'] = 'Success';
				} else {
					$r['result'] = 'Failure';
					$r['errormessage'] = $this->getResult()->convertStatusToArray( $status );
				}
			} else {
				$r['result'] = 'Failure';
				$r['errormessage'] = $err->toText();
			}
			$result[] = $r;
		}
		$apiResult = $this->getResult();
		$apiResult->setIndexedTagName( $result, 'page' );
		$apiResult->addValue( null, $this->getModuleName(), $result );
	}

	/**
	 * Get a cached instance of an ApiPageSet object
	 * @return ApiPageSet
	 */
	private function getPageSet() {
		if ( !isset( $this->mPageSet ) ) {
			$this->mPageSet = new ApiPageSet( $this, 0, NS_FILE);
		}
		return $this->mPageSet;
	}

	/**
	 * Checks that the user has permissions to perform rotations.
	 * Dies with usage message on inadequate permissions.
	 * @param $user User The user to check.
	 */
	protected function checkPermissions( $user, $title ) {
		$permissionErrors = array_merge(
			$title->getUserPermissionsErrors( 'edit' , $user ),
			$title->getUserPermissionsErrors( 'upload' , $user )
		);

		if ( $permissionErrors ) {
			$this->dieUsageMsg( $permissionErrors[0] );
		}
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams( $flags = 0 ) {
		$pageSet = $this->getPageSet();
		$result = array(
			'rotation' => array(
				ApiBase::PARAM_DFLT => 0,
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
		);
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}
		return $result;
	}

	public function getParamDescription() {
		$pageSet = $this->getPageSet();
		return $pageSet->getParamDescription() + array(
			'rotation' => 'Degrees to rotate image, values can be 0, 90, 180 or 270',
			'token' => 'Edit token. You can get one of these through prop=info',
		);
	}

	public function getDescription() {
		return 'Rotate one or more images';
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getPossibleErrors() {
		$pageSet = $this->getPageSet();
		return array_merge(
			parent::getPossibleErrors(),
			$pageSet->getPossibleErrors()
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=imagerotate&titles=Example.jpg&rotation=90&token=+\\',
		);
	}
}
