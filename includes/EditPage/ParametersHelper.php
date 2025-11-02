<?php

namespace MediaWiki\EditPage;

use MediaWiki\Context\RequestContext;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Specials\SpecialMyLanguage;
use MediaWiki\Title\Title;

/**
 * Helper methods for resolving EditPage parameters that deal with page titles.
 *
 * @internal
 * @property SpecialPageFactory $specialPageFactory
 */
trait ParametersHelper {

	/**
	 * If the given Title is Special:MyLanguage/Foo, resolve the language chain for the
	 * actual target title desired.
	 *
	 * @param ?Title $title
	 * @return ?Title
	 */
	private function getTargetTitleIfSpecialMyLanguage( ?Title $title ): ?Title {
		if ( $title && $title->isSpecialPage() ) {
			[ $spName, $spParam ] = $this->specialPageFactory->resolveAlias( $title->getText() );
			if ( $spName ) {
				$specialPage = $this->specialPageFactory->getPage( $spName );
				if ( $specialPage instanceof SpecialMyLanguage ) {
					// TODO This should pass a language as a parameter, instead of the whole context
					$specialPage->setContext( RequestContext::getMain() );
					$title = $specialPage->findTitleForTransclusion( $spParam );
				}
			}
		}

		return $title;
	}

	/**
	 * Verify if a given title exists and the given user is allowed to view it
	 *
	 * @param PageIdentity|null $page
	 * @param Authority $performer
	 * @return bool
	 * @phan-assert-true-condition $page
	 */
	private function isPageExistingAndViewable( ?PageIdentity $page, Authority $performer ): bool {
		return $page && $page->exists() && $performer->authorizeRead( 'read', $page );
	}
}
