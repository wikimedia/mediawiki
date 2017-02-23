<?php
namespace MediaWiki\Cdn;

use HttpResponse;
use MediaWiki\Linker\LinkTarget;
use OutputPage;
use Title;
use WebRequest;

/**
 * Service interface for sending cache control headers.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface CdnHeaderController {

	/**
	 * @param WebRequest $request
	 * @param OutputPage $output
	 * @param LinkTarget[] $dependsOn
	 */
	public function applyCacheControl( WebRequest $request, OutputPage $output, array $dependsOn = [] );

}
