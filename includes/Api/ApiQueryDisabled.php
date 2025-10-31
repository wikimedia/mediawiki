<?php
/**
 * Copyright © 2008 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

/**
 * API module that does nothing
 *
 * Use this to disable core modules with e.g.
 * $wgAPIPropModules['modulename'] = 'ApiQueryDisabled';
 *
 * To disable top-level modules, use ApiDisabled instead
 *
 * @ingroup API
 */
class ApiQueryDisabled extends ApiQueryBase {

	public function execute() {
		$this->addWarning( [ 'apierror-moduledisabled', $this->getModuleName() ] );
	}

	/** @inheritDoc */
	public function getSummaryMessage() {
		return 'apihelp-query+disabled-summary';
	}

	/** @inheritDoc */
	public function getExtendedDescription() {
		return [ [
			'apihelp-query+disabled-extended-description',
			'api-help-no-extended-description',
		] ];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryDisabled::class, 'ApiQueryDisabled' );
