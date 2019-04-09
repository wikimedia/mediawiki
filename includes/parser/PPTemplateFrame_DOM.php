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
 * @ingroup Parser
 */

/**
 * Expansion frame with template arguments
 * @deprecated since 1.34, use PPTemplateFrame_Hash
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPTemplateFrame_DOM extends PPFrame_DOM {

	public $numberedArgs, $namedArgs;

	/**
	 * @var PPFrame_DOM
	 */
	public $parent;
	public $numberedExpansionCache, $namedExpansionCache;

	/**
	 * @param Preprocessor $preprocessor
	 * @param bool|PPFrame_DOM $parent
	 * @param array $numberedArgs
	 * @param array $namedArgs
	 * @param bool|Title $title
	 */
	public function __construct( $preprocessor, $parent = false, $numberedArgs = [],
		$namedArgs = [], $title = false
	) {
		parent::__construct( $preprocessor );

		$this->parent = $parent;
		$this->numberedArgs = $numberedArgs;
		$this->namedArgs = $namedArgs;
		$this->title = $title;
		$pdbk = $title ? $title->getPrefixedDBkey() : false;
		$this->titleCache = $parent->titleCache;
		$this->titleCache[] = $pdbk;
		$this->loopCheckHash = /*clone*/ $parent->loopCheckHash;
		if ( $pdbk !== false ) {
			$this->loopCheckHash[$pdbk] = true;
		}
		$this->depth = $parent->depth + 1;
		$this->numberedExpansionCache = $this->namedExpansionCache = [];
	}

	public function __toString() {
		$s = 'tplframe{';
		$first = true;
		$args = $this->numberedArgs + $this->namedArgs;
		foreach ( $args as $name => $value ) {
			if ( $first ) {
				$first = false;
			} else {
				$s .= ', ';
			}
			$s .= "\"$name\":\"" .
				str_replace( '"', '\\"', $value->ownerDocument->saveXML( $value ) ) . '"';
		}
		$s .= '}';
		return $s;
	}

	/**
	 * @throws MWException
	 * @param string|int $key
	 * @param string|PPNode_DOM|DOMDocument $root
	 * @param int $flags
	 * @return string
	 */
	public function cachedExpand( $key, $root, $flags = 0 ) {
		if ( isset( $this->parent->childExpansionCache[$key] ) ) {
			return $this->parent->childExpansionCache[$key];
		}
		$retval = $this->expand( $root, $flags );
		if ( !$this->isVolatile() ) {
			$this->parent->childExpansionCache[$key] = $retval;
		}
		return $retval;
	}

	/**
	 * Returns true if there are no arguments in this frame
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return !count( $this->numberedArgs ) && !count( $this->namedArgs );
	}

	public function getArguments() {
		$arguments = [];
		foreach ( array_merge(
				array_keys( $this->numberedArgs ),
				array_keys( $this->namedArgs ) ) as $key ) {
			$arguments[$key] = $this->getArgument( $key );
		}
		return $arguments;
	}

	public function getNumberedArguments() {
		$arguments = [];
		foreach ( array_keys( $this->numberedArgs ) as $key ) {
			$arguments[$key] = $this->getArgument( $key );
		}
		return $arguments;
	}

	public function getNamedArguments() {
		$arguments = [];
		foreach ( array_keys( $this->namedArgs ) as $key ) {
			$arguments[$key] = $this->getArgument( $key );
		}
		return $arguments;
	}

	/**
	 * @param int $index
	 * @return string|bool
	 */
	public function getNumberedArgument( $index ) {
		if ( !isset( $this->numberedArgs[$index] ) ) {
			return false;
		}
		if ( !isset( $this->numberedExpansionCache[$index] ) ) {
			# No trimming for unnamed arguments
			$this->numberedExpansionCache[$index] = $this->parent->expand(
				$this->numberedArgs[$index],
				PPFrame::STRIP_COMMENTS
			);
		}
		return $this->numberedExpansionCache[$index];
	}

	/**
	 * @param string $name
	 * @return string|bool
	 */
	public function getNamedArgument( $name ) {
		if ( !isset( $this->namedArgs[$name] ) ) {
			return false;
		}
		if ( !isset( $this->namedExpansionCache[$name] ) ) {
			# Trim named arguments post-expand, for backwards compatibility
			$this->namedExpansionCache[$name] = trim(
				$this->parent->expand( $this->namedArgs[$name], PPFrame::STRIP_COMMENTS ) );
		}
		return $this->namedExpansionCache[$name];
	}

	/**
	 * @param int|string $name
	 * @return string|bool
	 */
	public function getArgument( $name ) {
		$text = $this->getNumberedArgument( $name );
		if ( $text === false ) {
			$text = $this->getNamedArgument( $name );
		}
		return $text;
	}

	/**
	 * Return true if the frame is a template frame
	 *
	 * @return bool
	 */
	public function isTemplate() {
		return true;
	}

	public function setVolatile( $flag = true ) {
		parent::setVolatile( $flag );
		$this->parent->setVolatile( $flag );
	}

	public function setTTL( $ttl ) {
		parent::setTTL( $ttl );
		$this->parent->setTTL( $ttl );
	}
}
