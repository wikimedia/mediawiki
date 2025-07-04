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

namespace MediaWiki\Language;

/**
 * Wrapper for injecting a LocalizationContext with lazy initialization.
 *
 * @since 1.42
 * @ingroup Language
 */
class LazyLocalizationContext implements LocalizationContext {

	/** @var callable */
	private $instantiator;

	private ?LocalizationContext $context = null;

	public function __construct( callable $instantiator ) {
		$this->instantiator = $instantiator;
	}

	private function resolve(): LocalizationContext {
		if ( !$this->context ) {
			$this->context = ( $this->instantiator )();
		}

		return $this->context;
	}

	/** @inheritDoc */
	public function getLanguageCode() {
		return $this->resolve()->getLanguageCode();
	}

	/** @inheritDoc */
	public function msg( $key, ...$params ) {
		return $this->resolve()->msg( $key, ...$params );
	}
}
