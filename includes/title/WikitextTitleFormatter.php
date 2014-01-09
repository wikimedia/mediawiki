<?php
/**
 * A TitleFormatter for generating titles for use in wikitext.
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
 * @license GPL 2+
 * @author Daniel Kinzler
 */

/**
 * A TitleFormatter for generating titles for use in wikitext.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 */
class WikitextTitleFormatter implements TitleFormatter {

	/**
	 * @var Language
	 */
	protected $language;

	/**
	 * @var GenderCache
	 */
	protected $genderCache;

	/**
	 * @var bool
	 */
	protected $showNamespace;

	/**
	 * @param Language $language the language object to use for localizing namespace names.
	 * @param GenderCache $genderCache the gender cache for generating gendered namespace names
	 * @param bool $showNamespace whether the namespace should be present in the formatted title
	 */
	public function __construct( Language $language, GenderCache $genderCache, $showNamespace = true ) {
		$this->language = $language;
		$this->genderCache = $genderCache;
		$this->showNamespace = $showNamespace;
	}

	/**
	 * Returns the title as a string for use in wikitext.
	 * Whether the namespace is included depends on the $showNamespace parameter
	 * passed to the constructor.
	 *
	 * @see TitleFormatter::format()
	 *
	 * @param TitleValue $title
	 * @return string
	 */
	public function format( TitleValue $title ) {
		$titleText = $title->getText();
		$section = $title->getSection();

		if ( $this->showNamespace ) {
			$namespace = $this->getNamespaceName( $title );

			if ( $namespace !== '' ) {
				$titleText = $namespace . ':' . $titleText;
			}
		}

		if ( $section !== '' ) {
			$titleText = $titleText . '#' . $section;
		}

		return str_replace( '_', ' ', $titleText );
	}

	/**
	 * Returns the name of the namespace of the given title.
	 * @note This takes into account gender sensitive namespace names.
	 *
	 * @param TitleValue $title
	 *
	 * @return String
	 */
	public function getNamespaceName( TitleValue $title ) {
		$ns = $title->getNamespace();
		$titleText = $title->getText();

		if ( $this->language->needsGenderDistinction() &&
			MWNamespace::hasGenderDistinction( $ns ) ) {

			//NOTE: we are assuming here that the title text is a user name!
			$gender = $this->genderCache->getGenderOf( $titleText, __METHOD__ );
			return $this->language->getGenderNsText( $ns, $gender );
		} else {
			return $this->language->getNsText( $ns );
		}
	}

	/**
	 * @see TitleFormatter::format()
	 *
	 * @return int TitleValue::TITLE_FORM
	 */
	public function getTargetForm() {
		return TitleValue::TITLE_FORM;
	}
}