<?php
/**
 * Properties of a title. Functions return bool questions about a TitleValue
 * based on its content model.
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
 * @author CSteipp
 */

class TitleProperties {

	private $mTitleValue;

	private $mContentModel;

	public function __construct( TitleValue $title, $contentModel ) {
		$this->mTitleValue = $title;
		$this->mContentModel = $contentModel;
	}

	public function getTitleValue() {
		return $this->mTitleValue;
	}

	/**
	 * Returns true if the title is inside the specified namespace.
	 *
	 * @param int $ns The namespace
	 * @return bool
	 */
	public function inNamespace( $ns ) {
		return MWNamespace::equals( $this->mTitleValue->getNamespace(), $ns );
	}


	/**
	 * Returns true if the title is inside one of the specified namespaces.
	 *
	 * @param array $namespaces the namespaces to check for
	 * @return bool
	 */
	public function inNamespaces( array $namespaces ) {
		foreach ( $namespaces as $ns ) {
			if ( $this->inNamespace( $ns ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns true if the title has the same subject namespace as the
	 * namespace specified.
	 * For example this method will take NS_USER and return true if namespace
	 * is either NS_USER or NS_USER_TALK since both of them have NS_USER
	 * as their subject namespace.
	 *
	 * This is MUCH simpler than individually testing for equivalence
	 * against both NS_USER and NS_USER_TALK, and is also forward compatible.
	 * @param int $ns
	 * @return bool
	 */
	public function hasSubjectNamespace( $ns ) {
		return MWNamespace::subjectEquals( $this->mTitleValue->getNamespace(), $ns );
	}

	/**
	 * Is this in a namespace that allows actual pages?
	 *
	 * @return bool
	 * @internal note -- uses hardcoded namespace index instead of constants
	 */
	public function canExist() {
		return $this->mTitleValue->getNamespace() >= NS_MAIN;
	}

	/**
	 * Is this a talk page of some sort?
	 *
	 * @return bool
	 */
	public function isTalkPage() {
		return MWNamespace::isTalk( $this->mTitleValue->getNamespace() );
	}

	/**
	 * Could this title have a corresponding talk page?
	 *
	 * @return bool
	 */
	public function canTalk() {
		return MWNamespace::canTalk( $this->mTitleValue->getNamespace() );
	}

	/**
	 * Is this a .js subpage of a user page?
	 *
	 * @return bool
	 */
	public function isJsSubpage() {
		return ( NS_USER == $this->mTitleValue->getNamespace() && $this->isSubpage()
			&& $this->hasContentModel( CONTENT_MODEL_JAVASCRIPT ) );
	}

	/**
	 * Is this a .css subpage of a user page?
	 *
	 * @return bool
	 */
	public function isCssSubpage() {
		return ( NS_USER == $this->mTitleValue->getNamespace() && $this->isSubpage()
			&& $this->hasContentModel( CONTENT_MODEL_CSS ) );
	}

	/**
	 * Is this a .css or .js subpage of a user page?
	 * @return bool
	 */
	public function isCssJsSubpage() {
		return ( NS_USER == $this->mTitleValue->getNamespace() && $this->isSubpage()
				&& ( $this->hasContentModel( CONTENT_MODEL_CSS )
					|| $this->hasContentModel( CONTENT_MODEL_JAVASCRIPT ) ) );
	}

	/**
	 * Is this a conversion table for the LanguageConverter?
	 *
	 * @return bool
	 */
	public function isConversionTable() {
		// @todo ConversionTable should become a separate content model.
		return $this->mTitleValue->getNamespace() == NS_MEDIAWIKI &&
			strpos( $this->mTitleValue->getText(), 'Conversiontable/' ) === 0;
	}

	/**
	 * Does that page contain wikitext, or it is JS, CSS or whatever?
	 *
	 * @return bool
	 */
	public function isWikitextPage() {
		return $this->hasContentModel( CONTENT_MODEL_WIKITEXT );
	}


	/**
	 * Is this a subpage?
	 *
	 * @return bool
	 */
	public function isSubpage() {
		return MWNamespace::hasSubpages( $this->mTitleValue->getNamespace() )
			? strpos( $this->mTitleValue->getText(), '/' ) !== false
			: false;
	}

	/**
	 * Is this Title in a namespace which contains content?
	 * In other words, is this a content page, for the purposes of calculating
	 * statistics, etc?
	 *
	 * @return bool
	 */
	public function isContentPage() {
		return MWNamespace::isContent( $this->mTitleValue->getNamespace() );
	}

	/**
	 * Returns true if this is a special page.
	 *
	 * @return bool
	 */
	public function isSpecialPage() {
		return $this->mTitleValue->getNamespace() == NS_SPECIAL;
	}

	/**
	 * Convenience method for checking a title's content model name
	 *
	 * @param string $id The content model ID (use the CONTENT_MODEL_XXX constants).
	 * @return bool True if $this->getContentModel() == $id
	 */
	public function hasContentModel( $id ) {
		return $this->mContentModel == $id;
	}

}
