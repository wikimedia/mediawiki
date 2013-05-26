<?php
/**
 * Created on 26th May, 2013
 *
 * Copyright © 2013 Alex Monk <krenair@gmail.com>
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
 */

class ApiFileExists extends ApiBase {
	public function execute() {
		$filename = $this->getRequest()->getText( 'name' );
		$file = wfFindFile( $filename );
		if ( !$file ) {
			// Force local file so we have an object to do further checks against
			// if there isn't an exact match...
			$file = wfLocalFile( $filename );
		}
		$this->getResult()->addValue( null, 'exists', (bool) $file );
		if ( $file ) {
			$this->getResult()->addValue( null, 'html', "<div>" . SpecialUpload::getExistsWarning( UploadBase::getExistsWarning( $file ) ) . "</div>" );
		} else {
			$this->getResult()->addValue( null, 'html', '' );
		}
	}

	public function getAllowedParams() {
		return array(
			'name' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
		);
	}

	public function getParamDescription() {
		return array(
			'name' => 'The file name to check',
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'html' => array(
					ApiBase::PROP_TYPE => 'string',
				),
				'exists' => array(
					ApiBase::PROP_TYPE => 'boolean',
				),
			)
		);
	}

	public function getDescription() {
		return 'Checks whether or not a file exists';
	}

	public function getExamples() {
		return array(
			'api.php?action=fileexists&name=Test.jpg',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Fileexists';
	}
}
