<?php
/**
 * Copyright © 2008 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

/**
 * API module that dies with an error immediately.
 *
 * Use this to disable core modules with
 * $wgAPIModules['modulename'] = 'ApiDisabled';
 *
 * To disable submodules of action=query, use ApiQueryDisabled instead
 *
 * @ingroup API
 */
class ApiDisabled extends ApiBase {

	public function execute() {
		$this->dieWithError( [ 'apierror-moduledisabled', $this->getModuleName() ] );
	}

	/** @inheritDoc */
	public function isReadMode() {
		return false;
	}

	/** @inheritDoc */
	protected function getSummaryMessage() {
		return 'apihelp-disabled-summary';
	}

	/** @inheritDoc */
	protected function getExtendedDescription() {
		return [ [
			'apihelp-disabled-extended-description',
			'api-help-no-extended-description',
		] ];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiDisabled::class, 'ApiDisabled' );
