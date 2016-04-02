<?php
namespace MediaWiki\Parser;

/**
 * Factory for Preprocessor instances
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
 * @ingroup Parser
 */
use Parser;
use Preprocessor;
use Wikimedia\Assert\Assert;

/**
 * Factory for Preprocessor instances.
 *
 *
 * @note Since Parser and Preprocessor objects reference each other, we need this factory to
 * resolve the chicken-and-egg situation that arises when instantiating parser objects.
 *
 * @ingroup Parser
 */
class PreprocessorFactory {

	private $preprocessorClass;

	public function __construct( $preprocessorClass ) {
		Assert::parameterType( 'string', $preprocessorClass, '$preprocessorClass' );
		$this->preprocessorClass = $preprocessorClass;
	}

	/**
	 * @param Parser $parser
	 *
	 * @return Preprocessor
	 */
	public function newPreprocessor( Parser $parser ) {
		$class = $this->preprocessorClass;
		$preprocessor = new $class( $parser );

		Assert::postcondition(
			$preprocessor instanceof Preprocessor,
			"$class is not compatible with Preprocessor"
		);
		return $preprocessor;
	}
}
