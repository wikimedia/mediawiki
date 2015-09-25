<?php
/**
 * Resource loader module for the edit toolbar.
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
 */

/**
 * ResourceLoader module for the edit toolbar.
 *
 * @since 1.24
 */
class ResourceLoaderEditToolbarModule extends ResourceLoaderFileModule {

	/**
	 * Get language-specific LESS variables for this module.
	 *
	 * @return array
	 */
	private function getLessVars( ResourceLoaderContext $context ) {
		$language = Language::factory( $context->getLanguage() );

		// This is very conveniently formatted and we can pass it right through
		$vars = $language->getImageFiles();

		// less.php tries to be helpful and parse our variables as LESS source code
		foreach ( $vars as $key => &$value ) {
			$value = CSSMin::serializeStringValue( $value );
		}

		return $vars;
	}

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return true;
	}

	/**
	 * Get a LESS compiler instance for this module.
	 *
	 * Set our variables in it.
	 *
	 * @throws MWException
	 * @param ResourceLoaderContext $context
	 * @return Less_Parser
	 */
	protected function getLessCompiler( ResourceLoaderContext $context = null ) {
		$parser = parent::getLessCompiler();
		$parser->ModifyVars( $this->getLessVars( $context ) );
		return $parser;
	}
}
