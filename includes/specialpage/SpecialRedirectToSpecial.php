<?php
/**
 * Shortcuts to construct a special page alias.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * @stable to extend
 *
 * @ingroup SpecialPage
 */
abstract class SpecialRedirectToSpecial extends RedirectSpecialPage {
	/** @var string Name of redirect target */
	protected $redirName;

	/** @var string Name of subpage of redirect target */
	protected $redirSubpage;

	/**
	 * @stable to call
	 *
	 * @param string $name
	 * @param string $redirName
	 * @param bool $redirSubpage
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
