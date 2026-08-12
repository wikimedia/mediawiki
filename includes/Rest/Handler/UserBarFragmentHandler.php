<?php

namespace MediaWiki\Rest\Handler;

/**
 * Serves the user bar fragment.
 * @unstable Experimental feature, part of the fragments/v0-internal module.
 */
class UserBarFragmentHandler extends FragmentHandler {

	protected function getFragmentHtml(): string {
		return <<<HTML
<!-- Fragment served by REST API -->
HTML;
	}
}
