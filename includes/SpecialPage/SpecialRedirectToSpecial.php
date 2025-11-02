<?php
/**
 * Shortcuts to construct a special page alias.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

use MediaWiki\Title\Title;

/**
 * @stable to extend
 *
 * @ingroup SpecialPage
 */
abstract class SpecialRedirectToSpecial extends RedirectSpecialPage {
	/** @var string Name of redirect target */
	protected $redirName;

	/** @var string|false Name of subpage of redirect target */
	protected $redirSubpage;

	/**
	 * @stable to call
	 *
	 * @param string $name
	 * @param string $redirName
	 * @param string|false $redirSubpage
	 * @param array $allowedRedirectParams
	 * @param array $addedRedirectParams
	 */
	public function __construct(
		$name, $redirName, $redirSubpage = false,
		$allowedRedirectParams = [], $addedRedirectParams = []
	) {
		parent::__construct( $name );
		$this->redirName = $redirName;
		$this->redirSubpage = $redirSubpage;
		$this->mAllowedRedirectParams = $allowedRedirectParams;
		$this->mAddedRedirectParams = $addedRedirectParams;
	}

	/**
	 * @param string|null $subpage
	 * @return Title|bool
	 */
	public function getRedirect( $subpage ) {
		if ( $this->redirSubpage === false ) {
			return SpecialPage::getTitleFor( $this->redirName, $subpage );
		}

		return SpecialPage::getTitleFor( $this->redirName, $this->redirSubpage );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialRedirectToSpecial::class, 'SpecialRedirectToSpecial' );
