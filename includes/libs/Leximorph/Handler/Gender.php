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

namespace Wikimedia\Leximorph\Handler;

/**
 * Gender
 *
 * The Gender class selects the appropriate text variant based on a provided gender value.
 * It accepts a gender identifier along with an array of text options for male, female, and other,
 * returning the corresponding text.
 *
 * Usage Example:
 * <code>
 *            echo $gender->process( 'female', [ 'he', 'she', 'they' ] );
 * </code>
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class Gender {

	/**
	 * Selects the appropriate text variant based on gender.
	 *
	 * This method converts the provided gender value to lowercase and returns the text variant
	 * from the supplied options that corresponds to 'male', 'female', or a default value for any
	 * other or unspecified gender.
	 *
	 * @param string $value The gender value (e.g., "male", "female", or another).
	 * @param string[] $options An array containing text variants for male, female, and other.
	 *                          All three elements are optional.
	 *
	 * @since 1.45
	 * @return string The text variant that matches the provided gender.
	 */
	public function process( string $value, array $options ): string {
		$male = $options[0] ?? '';
		$female = $options[1] ?? $male;
		$other = $options[2] ?? $male;

		if ( $value === 'male' ) {
			return $male;
		} elseif ( $value === 'female' ) {
			return $female;
		}

		return $other;
	}
}
