<?php

/**
 * Created on August 26, 2014
 *
 * Copyright © 2014 Petr Bena (benapetr@gmail.com)
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
 * API module that clears the hasmsg flag for current user
 * @ingroup API
 */
class ApiClearHasMsg extends ApiBase {
	public function execute() {
		$user = $this->getUser();
		$user->setNewtalk( false );
		$this->getResult()->addValue( null, $this->getModuleName(), 'success' );
	}

	public function isWriteMode() {
		return true;
	}

	public function mustBePosted() {
		return true;
	}

	protected function getExamplesMessages() {
		return [
			'action=clearhasmsg'
				=> 'apihelp-clearhasmsg-example-1',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:ClearHasMsg';
	}
}
