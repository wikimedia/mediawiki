<?php
/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Skin;

use MediaWiki\Config\Config;
use MediaWiki\Context\IContextSource;
use MediaWiki\Language\Language;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\WikiPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MessageLocalizer;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
class SkinComponentRegistryContext implements ComponentRegistryContext {

	/** @var Skin */
	private $skin;

	/** @var MessageLocalizer|null */
	private $localizer = null;

	public function __construct( Skin $skin ) {
		$this->skin = $skin;
	}

	public function getContextSource(): IContextSource {
		return $this->skin->getContext();
	}

	/**
	 * @inheritDoc
	 */
	public function getConfig(): Config {
		return $this->skin->getConfig();
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle(): Title {
		return $this->skin->getTitle() ?? Title::makeTitle( NS_MAIN, 'Foo' );
	}

	/**
	 * @return Title|null the "relevant" title - see Skin::getRelevantTitle
	 */
	public function getRelevantTitle() {
		return $this->skin->getRelevantTitle() ?? $this->getTitle();
	}

	public function getOutput(): OutputPage {
		return $this->skin->getOutput();
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->skin->getUser();
	}

	/**
	 * @return Language $language
	 */
	public function getLanguage() {
		return $this->skin->getLanguage();
	}

	public function getMessageLocalizer(): MessageLocalizer {
		if ( $this->localizer === null ) {
			// Cannot call getContext in constructor,
			// because Skin::class does not have a context yet.
			// But it is valid to call it now
			$this->localizer = $this->skin->getContext();
		}

		return $this->localizer;
	}

	/**
	 * @return WikiPage
	 */
	public function getWikiPage() {
		return $this->skin->getWikiPage();
	}
}
