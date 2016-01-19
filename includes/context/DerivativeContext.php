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
 * @author Daniel Friesen
 * @file
 */

/**
 * An IContextSource implementation which will inherit context from another source
 * but allow individual pieces of context to be changed locally
 * eg: A ContextSource that can inherit from the main RequestContext but have
 *     a different Title instance set on it.
 * @since 1.19
 */
class DerivativeContext extends ContextSource implements MutableContext {

	/**
	 * Constructor
	 * @param MutableContext $context MutableContext to inherit from
	 */
	public function __construct( MutableContext $context ) {
		// Clone the original context object so we can change it without changing the
		// source context one.
		$context = clone $context;

		// set this cloned context object as the context of this DerivativeContext instance,
		// so any set*-call will be done on this one, like any get*-call
		$this->setContext( $context );
	}

	/**
	 * Set the SiteConfiguration object
	 *
	 * @param Config $s
	 */
	public function setConfig( Config $s ) {
		$this->getContext()->setConfig( $s );
	}

	/**
	 * Set the WebRequest object
	 *
	 * @param WebRequest $r
	 */
	public function setRequest( WebRequest $r ) {
		$this->getContext()->setRequest( $r );
	}

	/**
	 * Set the Title object
	 *
	 * @param Title $t
	 */
	public function setTitle( Title $t ) {
		$this->getContext()->setTitle( $t );
	}

	/**
	 * Set the WikiPage object
	 *
	 * @since 1.19
	 * @param WikiPage $p
	 */
	public function setWikiPage( WikiPage $p ) {
		$this->getContext()->setWikiPage( $p );
	}

	/**
	 * Set the OutputPage object
	 *
	 * @param OutputPage $o
	 */
	public function setOutput( OutputPage $o ) {
		$this->getContext()->setOutput( $o );
	}

	/**
	 * Set the User object
	 *
	 * @param User $u
	 */
	public function setUser( User $u ) {
		$this->getContext()->setUser( $u );
	}

	/**
	 * Set the Language object
	 *
	 * @param Language|string $l Language instance or language code
	 * @throws MWException
	 * @since 1.19
	 */
	public function setLanguage( $l ) {
		$this->getContext()->setLanguage( $l );
	}

	/**
	 * Set the Skin object
	 *
	 * @param Skin $s
	 */
	public function setSkin( Skin $s ) {
		$skin = clone $s;
		$skin->setContext( $this );
		$this->getContext()->setSkin( $skin );
	}
}
