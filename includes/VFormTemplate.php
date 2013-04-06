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
 * @ingroup Templates
 */

/**
 * Parent class for the vertically-stacked template design that provides
 * helper methods for some VForm-specific layouts.
 * Inherit from BaseTemplate instead of QuickTemplate for its proper
 * context-based msg() implementation.
 */
abstract class VFormTemplate extends BaseTemplate {

	/**
	 * Get the Skin object related to this object, so that BaseTemplate msg
	 * methods work.
	 *
	 * @return Skin object
	 */
	public function getSkin() {
		global $wgOut;
		return $wgOut->getSkin();
	}

	/**
	 * Convenience function to build a VForm HTML checkbox nested inside a
	 * label.  This arguably belongs in class Html, but then VForm clients
	 * would have to apply an VForm class to the label as well as attrs for the
	 * checkbox.
	 *
	 * @param $label string text for label
	 * @param $name string form element name
	 * @param $id
	 * @param $checked bool (default: false)
	 * @param $attribs array additional attributes for the input checkbox
	 *
	 * @return string HTML
	 * @see Xml:checkLabel
	 */
	public function labelledCheck( $label, $name, $id, $checked = false, $attribs = array() ) {
		return Html::rawElement(
				'label',
				array(
					'for' => $id,
					'class' => 'mw-ui-checkbox-label'
				),
				Xml::check(
					$id,
					$checked,
					array( 'id' => $id ) + $attribs
				) .
				// Html:rawElement doesn't escape contents.
				htmlspecialchars( $label )
			);
	}

}
