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
 * @author Florian Schmidt
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
	 * @param IContextSource $context MutableContext to inherit from
	 */
	public function __construct( IContextSource $context ) {
		// we can not simply use this context and change anything like we want. This would
		// change the source context object, too. This makes sure, that a copy of the context
		// is used instead.
		// a MutableContext is the easiest way of doing it...
		if ( $context instanceof MutableContext ) {
			// ... it already has any setter and getter functions we need, so simply clone
			// it.
			$derivedContext = clone $context;
		} elseif ( $context->getContext() instanceof MutableContext ) {
			// same as MutableContext
			$derivedContext = clone $context;
		} else {
			// that's a bit more tricky. We can't use the $context directly, because it's
			// possible that the needed getter and setter functions aren't present. For this,
			// create a new MutableContext object with the given context as the source
			// and use this for our work here.
			$derivedContext = $this->getRequestContextFromOtherContext( $context );
		}

		// set this cloned context object as the context of this DerivativeContext instance,
		// so any set*-call will be done on this one, like any get*-call
		$this->setContext( $derivedContext );
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

	/**
	 * Create a new RequestContext (implements MutableContext) from an IContextSource
	 * implementation (that currently doesn't implement MutableContext) to make it
	 * mutable.
	 *
	 * @deprecated 1.27
	 *
	 * @param IContextSource $context
	 * @return RequestContext
	 */
	private function getRequestContextFromOtherContext( IContextSource $context ) {
		wfDeprecated( __METHOD__, '1.27' );

		$rq = new RequestContext();
		$rq->setRequest( $context->getRequest() );
		$rq->setConfig( $context->getConfig() );
		if ( $context->getTitle() ) {
			$rq->setTitle( $context->getTitle() );
		}
		if ( $context->canUseWikiPage() ) {
			$rq->setWikiPage( $context->getWikiPage() );
		}
		$rq->setOutput( $context->getOutput() );
		$rq->setUser( $context->getUser() );
		$rq->setLanguage( $context->getLanguage() );
		$skin = clone $context->getSkin();
		$skin->setContext( $rq );
		$rq->setSkin( $skin );
		$rq->setLanguage( $context->getLanguage() );

		return $rq;
	}
}
