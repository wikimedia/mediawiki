<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 * Copyright © 2008 Brooke Vibber <bvibber@wikimedia.org>
 * Copyright © 2014 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

/**
 * @ingroup API
 */
class ApiOpenSearchFormatJson extends ApiFormatJson {

	private bool $warningsAsError;

	public function __construct( ApiMain $main, string $format, bool $warningsAsError ) {
		parent::__construct( $main, $format );
		$this->warningsAsError = $warningsAsError;
	}

	public function execute() {
		$result = $this->getResult();
		if ( !$result->getResultData( 'error' ) && !$result->getResultData( 'errors' ) ) {
			// Ignore warnings or treat as errors, as requested
			$warnings = $result->removeValue( 'warnings', null );
			if ( $this->warningsAsError && $warnings ) {
				$this->dieWithError(
					'apierror-opensearch-json-warnings',
					'warnings',
					[ 'warnings' => $warnings ]
				);
			}

			// Ignore any other unexpected keys (e.g. from $wgDebugToolbar)
			$remove = array_keys( array_diff_key(
				$result->getResultData(),
				[ 0 => 'search', 1 => 'terms', 2 => 'descriptions', 3 => 'urls' ]
			) );
			foreach ( $remove as $key ) {
				$result->removeValue( $key, null );
			}
		}

		parent::execute();
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiOpenSearchFormatJson::class, 'ApiOpenSearchFormatJson' );
