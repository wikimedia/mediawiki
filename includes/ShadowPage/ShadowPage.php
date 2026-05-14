<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Content\Content;
use Wikimedia\Message\MessageSpecifier;

/**
 * The interface for shadow pages.
 *
 * Note that extensions should generally extend BaseShadowPage, to avoid a b/c
 * break if we decide to add more methods here.
 *
 * @since 1.47
 */
interface ShadowPage {
	/**
	 * Get content to be shown in the edit box when editing a non-existent page.
	 *
	 * @return Content|null
	 */
	public function getPreloadContent(): ?Content;

	/**
	 * Whether preload content is available
	 *
	 * @return bool
	 */
	public function hasPreloadContent(): bool;

	/**
	 * Get the content to be shown if this page is transcluded
	 *
	 * @return Content|null
	 */
	public function getContentForTransclusion(): ?Content;

	/**
	 * If this returns true, force the edit action to be labelled "edit", not
	 * "create", even though the page technically doesn't exist.
	 *
	 * @return bool
	 */
	public function existsForEdit(): bool;

	/**
	 * The label for base content when diffing against preloaded content.
	 *
	 * @return MessageSpecifier|null
	 */
	public function getDiffTitleMessage(): ?MessageSpecifier;

	/**
	 * Get page view content, or null to decline to provide such content.
	 *
	 * @return ShadowPageView|null
	 */
	public function getView(): ?ShadowPageView;
}
