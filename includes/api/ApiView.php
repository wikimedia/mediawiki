<?php
/**
 * Copyright (c) 2012 Tyler Romeo <tylerromeo@gmail.com>
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
 * API module that pages will call when a user views a page so that
 * analytics for the wiki can be measured.
 *
 * This class does not actually implement any analytics, as that is
 * something best left to extensions. All it does is provide an entry
 * point for which extensions that measure analytics can hook in.
 *
 * @ingroup API
 */
class ApiView extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		$user = $this->getUser();
		$params = $this->extractRequestParams();
		$page = $this->getTitleOrPageId( $params );
		$client = FormatJson::decode( $params['client'] );

		wfRunHooks( 'Analytics', array( $page, $client ) );
	}

	public function mustBePosted() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'client' => array(
				ApiBase::PARAM_TYPE => 'string',
			)
		);
	}

	public function getParamDescription() {
		return array(
			'title' => 'Title of the page that is being viewed.',
			'token' => 'JSON-encoded information about the browser.'
		);
	}

	public function getResultProperties() {
		return array();
	}

	public function getDescription() {
		return 'Register analytics for a page view';
	}

	public function getPossibleErrors() {
		return array_merge(
			parent::getPossibleErrors(),
			$this->getTitleOrPageIdErrorMessage(),
			array(
				array( 'disabled' )
			)
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}