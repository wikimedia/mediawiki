<?php

namespace MediaWiki\ParamValidator\TypeDef;

use MediaWiki\Page\ProperPageIdentity;

/**
 * A validated title parameter, as returned by TitleDef with
 * PARAM_RETURN_OBJECT set to TitleDef::TITLE_PARAM_VALUE.
 *
 * @since 1.48
 */
class TitleParam {

	public function __construct(
		private readonly string $text,
		private readonly ?ProperPageIdentity $page,
		private readonly ?int $pageId = null
	) {
	}

	/**
	 * @return string The parameter value as given in the request, not normalized
	 */
	public function getText(): string {
		return $this->text;
	}

	/**
	 * @return ProperPageIdentity|null The page, or null if the title cannot be a page
	 *   (e.g. a special page), or if no page has the requested ID
	 */
	public function getPage(): ?ProperPageIdentity {
		return $this->page;
	}

	/**
	 * @return int|null The page ID, if the page was requested by ID ("#123")
	 */
	public function getRequestedPageId(): ?int {
		return $this->pageId;
	}

}
