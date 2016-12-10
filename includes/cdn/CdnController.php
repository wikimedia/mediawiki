<?php
namespace MediaWiki\Cdn;

use HttpResponse;
use OutputPage;
use Title;
use WebRequest;

/**
 * Service interface for CDN controllers.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface CdnController {

	/**
	 * @param Title $title Title of the page for which to purge dependent resources.
	 *
	 * @throws \Exception if supportsBuckets() returns false.
	 * @return void
	 */
	public function purgeDependentResources( Title $title );

	/**
	 * @param Title $requestTitle
	 * @param WebRequest $request
	 *
	 * @param OutputPage $output
	 *
	 * @return
	 */
	public function applyCacheControl( Title $requestTitle, WebRequest $request, OutputPage $output );

	/**
	 * Returns a list of URLs that may have been used to retrieve resources that depend on the
	 * given title.
	 *
	 * @warning This is supplied as a backward compatibility kludge. Application code should
	 * have no need to call this method.
	 *
	 * @param Title $title
	 *
	 * @return string[]
	 */
	public function getDependentResources( Title $title );

}
