<?php
/**
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
 */

/**
 * Form to edit user preferences.
 *
 * @since 1.32
 */
class PreferencesFormOOUI extends OOUIHTMLForm {
	// Override default value from HTMLForm
	protected $mSubSectionBeforeFields = false;

	/** @var User|null */
	private $modifiedUser;

	/** @var bool */
	private $privateInfoEditable = true;

	/** @var bool */
	private $optionsEditable = true;

	/**
	 * @param User $user
	 */
	public function setModifiedUser( $user ) {
		$this->modifiedUser = $user;
	}

	/**
	 * @return User
	 */
	public function getModifiedUser() {
		if ( $this->modifiedUser === null ) {
			return $this->getUser();
		} else {
			return $this->modifiedUser;
		}
	}

	/**
	 * @return bool
	 */
	public function isPrivateInfoEditable() {
		return $this->privateInfoEditable;
	}

	/**
	 * Whether the
	 * @param bool $editable
	 */
	public function setPrivateInfoEditable( $editable ) {
		$this->privateInfoEditable = $editable;
	}

	/**
	 * @return bool
	 */
	public function areOptionsEditable() {
		return $this->optionsEditable;
	}

	/**
	 * @param bool $optionsEditable
	 */
	public function setOptionsEditable( $optionsEditable ) {
		$this->optionsEditable = $optionsEditable;
	}

	/**
	 * Get extra parameters for the query string when redirecting after
	 * successful save.
	 *
	 * @return array
	 */
	public function getExtraSuccessRedirectParameters() {
		return [];
	}

	/**
	 * @param string $html
	 * @return string
	 */
	function wrapForm( $html ) {
		$html = Xml::tags( 'div', [ 'id' => 'preferences' ], $html );

		return parent::wrapForm( $html );
	}

	/**
	 * @return string
	 */
	function getButtons() {
		if ( !$this->areOptionsEditable() && !$this->isPrivateInfoEditable() ) {
			return '';
		}

		$html = parent::getButtons();

		if ( $this->areOptionsEditable() ) {
			$t = $this->getTitle()->getSubpage( 'reset' );

			$html .= new OOUI\ButtonWidget( [
				'infusable' => true,
				'id' => 'mw-prefs-restoreprefs',
				'label' => $this->msg( 'restoreprefs' )->text(),
				'href' => $t->getLinkURL(),
				'flags' => [ 'destructive' ],
				'framed' => false,
			] );

			$html = Xml::tags( 'div', [ 'class' => 'mw-prefs-buttons' ], $html );
		}

		return $html;
	}

	/**
	 * Separate multi-option preferences into multiple preferences, since we
	 * have to store them separately
	 * @param array $data
	 * @return array
	 */
	function filterDataForSubmit( $data ) {
		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( $field instanceof HTMLNestedFilterable ) {
				// @phan-suppress-next-next-line PhanUndeclaredProperty All HTMLForm fields have mParams,
				// but the instanceof confuses phan, which doesn't support intersections
				$info = $field->mParams;
				$prefix = $info['prefix'] ?? $fieldname;
				foreach ( $field->filterDataForSubmit( $data[$fieldname] ) as $key => $value ) {
					$data["$prefix$key"] = $value;
				}
				unset( $data[$fieldname] );
			}
		}

		return $data;
	}

	protected function wrapFieldSetSection( $legend, $section, $attributes, $isRoot ) {
		$layout = parent::wrapFieldSetSection( $legend, $section, $attributes, $isRoot );

		$layout->addClasses( [ 'mw-prefs-fieldset-wrapper' ] );
		$layout->removeClasses( [ 'oo-ui-panelLayout-framed' ] );

		return $layout;
	}

	/**
	 * Get the whole body of the form.
	 * @return string
	 */
	function getBody() {
		$tabPanels = [];
		foreach ( $this->mFieldTree as $key => $val ) {
			if ( !is_array( $val ) ) {
				wfDebug( __METHOD__ . " encountered a field not attached to a section: '$key'" );
				continue;
			}
			$label = $this->getLegend( $key );
			$content =
				$this->getHeaderText( $key ) .
				$this->displaySection(
					$this->mFieldTree[$key],
					"",
					"mw-prefsection-$key-"
				) .
				$this->getFooterText( $key );

			$tabPanels[] = new OOUI\TabPanelLayout( 'mw-prefsection-' . $key, [
				'classes' => [ 'mw-htmlform-autoinfuse-lazy' ],
				'label' => $label,
				'content' => new OOUI\FieldsetLayout( [
					'classes' => [ 'mw-prefs-section-fieldset' ],
					'id' => "mw-prefsection-$key",
					'label' => $label,
					'items' => [
						new OOUI\Widget( [
							'content' => new OOUI\HtmlSnippet( $content )
						] ),
					],
				] ),
				'expanded' => false,
				'framed' => true,
			] );
		}

		$indexLayout = new OOUI\IndexLayout( [
			'infusable' => true,
			'expanded' => false,
			'autoFocus' => false,
			'classes' => [ 'mw-prefs-tabs' ],
		] );
		$indexLayout->addTabPanels( $tabPanels );

		return new OOUI\PanelLayout( [
			'framed' => true,
			'expanded' => false,
			'classes' => [ 'mw-prefs-tabs-wrapper' ],
			'content' => $indexLayout
		] );
	}

	/**
	 * Get the "<legend>" for a given section key. Normally this is the
	 * prefs-$key message but we'll allow extensions to override it.
	 * @param string $key
	 * @return string
	 */
	function getLegend( $key ) {
		$legend = parent::getLegend( $key );
		Hooks::run( 'PreferencesGetLegend', [ $this, $key, &$legend ] );
		return $legend;
	}

	/**
	 * Get the keys of each top level preference section.
	 * @return string[] List of section keys
	 */
	function getPreferenceSections() {
		return array_keys( array_filter( $this->mFieldTree, 'is_array' ) );
	}
}
