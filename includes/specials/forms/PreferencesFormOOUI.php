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

	private $modifiedUser;

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
		if ( !$this->getModifiedUser()->isAllowedAny( 'editmyprivateinfo', 'editmyoptions' ) ) {
			return '';
		}

		$html = parent::getButtons();

		if ( $this->getModifiedUser()->isAllowed( 'editmyoptions' ) ) {
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
		// to get a user visible effect, wrap the fieldset into a framed panel layout
		if ( $isRoot ) {
			// Mimic TabPanelLayout
			$wrapper = new OOUI\PanelLayout( [
				'expanded' => false,
				'scrollable' => true,
				// Framed and padded for no-JS, frame hidden with CSS
				'framed' => true,
				'infusable' => false,
				'classes' => [ 'oo-ui-stackLayout oo-ui-indexLayout-stackLayout' ]
			] );
			$layout = new OOUI\PanelLayout( [
				'expanded' => false,
				'scrollable' => true,
				'infusable' => false,
				'classes' => [ 'oo-ui-tabPanelLayout' ]
			] );
			$wrapper->appendContent( $layout );
		} else {
			$wrapper = $layout = new OOUI\PanelLayout( [
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'infusable' => false,
			] );
		}

		$layout->appendContent(
			new OOUI\FieldsetLayout( [
				'label' => $legend,
				'infusable' => false,
				'items' => [
					new OOUI\Widget( [
						'content' => new OOUI\HtmlSnippet( $section )
					] ),
				],
			] + $attributes )
		);
		return $wrapper;
	}

	/**
	 * Get the whole body of the form.
	 * @return string
	 */
	function getBody() {
		// Construct fake tabs to avoid FOUC. The structure mimics OOUI's tabPanelLayout.
		// TODO: Consider creating an infusable TabPanelLayout in OOUI-PHP.
		$fakeTabs = [];
		foreach ( $this->getPreferenceSections() as $i => $key ) {
			$fakeTabs[] =
				Html::rawElement(
					'div',
					[
						'class' =>
							'oo-ui-widget oo-ui-widget-enabled oo-ui-optionWidget ' .
							'oo-ui-tabOptionWidget oo-ui-labelElement' .
							( $i === 0 ? ' oo-ui-optionWidget-selected' : '' )
					],
					Html::element(
						'a',
						[
							'class' => 'oo-ui-labelElement-label',
							// Make this a usable link instead of a span so the tabs
							// can be used before JS runs
							'href' => '#mw-prefsection-' . $key
						],
						$this->getLegend( $key )
					)
				);
		}
		$fakeTabsHtml = Html::rawElement(
			'div',
			[ 'class' => 'oo-ui-layout oo-ui-panelLayout oo-ui-indexLayout-tabPanel' ],
			Html::rawElement(
				'div',
				[ 'class' => 'oo-ui-widget oo-ui-widget-enabled oo-ui-selectWidget ' .
					'oo-ui-selectWidget-depressed oo-ui-tabSelectWidget' ],
				implode( $fakeTabs )
			)
		);

		return Html::rawElement(
			'div',
			[ 'class' => 'oo-ui-layout oo-ui-panelLayout oo-ui-panelLayout-framed mw-prefs-faketabs' ],
			Html::rawElement(
				'div',
				[ 'class' => 'oo-ui-layout oo-ui-menuLayout oo-ui-menuLayout-static ' .
					'oo-ui-menuLayout-top oo-ui-menuLayout-showMenu oo-ui-indexLayout' ],
				Html::rawElement(
					'div',
					[ 'class' => 'oo-ui-menuLayout-menu' ],
					$fakeTabsHtml
				) .
				Html::rawElement(
					'div',
					[ 'class' => 'oo-ui-menuLayout-content mw-htmlform-autoinfuse-lazy' ],
					$this->displaySection( $this->mFieldTree, '', 'mw-prefsection-' )
				)
			)
		);
	}

	/**
	 * Get the "<legend>" for a given section key. Normally this is the
	 * prefs-$key message but we'll allow extensions to override it.
	 * @param string $key
	 * @return string
	 */
	function getLegend( $key ) {
		$aliasKey = ( $key === 'optoutwatchlist' || $key === 'optoutrc' ) ? 'opt-out' : $key;
		$legend = parent::getLegend( $aliasKey );
		Hooks::run( 'PreferencesGetLegend', [ $this, $key, &$legend ] );
		return $legend;
	}

	/**
	 * Get the keys of each top level preference section.
	 * @return array of section keys
	 */
	function getPreferenceSections() {
		return array_keys( array_filter( $this->mFieldTree, 'is_array' ) );
	}
}
