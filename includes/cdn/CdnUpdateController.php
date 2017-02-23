<?php
namespace MediaWiki\Cdn;

use DeferredUpdates;
use HttpResponse;
use MediaWiki\Linker\LinkTarget;
use OutputPage;
use Title;
use WebRequest;

/**
 * Service interface for purging resoures derived from a given title from a CDN.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface CdnUpdateController {

	/**
	 * @param Title $title Title of the page for which to purge dependent resources.
	 * @param boolean|int $deferred A deferred update stage (see DeferredUpdates::addUpdates),
	 *        or false for immediate execution.
	 */
	public function purgeDependentResources( Title $title, $deferred = DeferredUpdates::PRESEND );

	/**
	 * Returns a list of URLs that may have been used to retrieve resources that depend on the
	 * given title.
	 *
	 * @warning This is supplied as a backward compatibility kludge. Application code should
	 * have no need to call this method. purgeDependentResources() should be used instead.
	 *
	 * @param Title $title
	 *
	 * @return string[]
	 */
	public function getDependentResources( Title $title );

}
