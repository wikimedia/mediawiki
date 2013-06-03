<?php

use \MediaWiki\RDFa;

class PrefixContextTest extends MediaWikiTestCase {

	function testPrefix() {
		$prefix = new RDFa\Prefix( 'ex', "http://example.org/" );

		$this->assertEquals( 'ex', $prefix->prefix() );
		$this->assertEquals( "http://example.org/", $prefix->iri() );
		$this->assertEquals( 'ex:FooBar', $prefix->curie( 'FooBar' ) );
		$this->assertEquals( '[ex:FooBar]', $prefix->safeCurie( 'FooBar' ) );
		$this->assertEquals( 'http://example.org/FooBar', $prefix->expand( 'FooBar' ) );
	}

	private function assertPrefix( $expectedPrefix, $expectedIRI, $actual, $msg = '' ) {
		$this->assertEquals( $expectedIRI, $actual->iri(),
			"$msg (Wrong IRI; expected {$expectedPrefix}: {$expectedIRI})" );
		$this->assertEquals( $expectedPrefix, $actual->prefix(),
			"$msg (Wrong prefix; expected {$expectedPrefix}: {$expectedIRI})" );
	}

	function testSingleContext() {
		$ctx = new RDFa\PrefixContext();

		# Registering multiple prefixes works
		$a = $ctx->prefix( 'a', "http://example.org/a/" );
		$b = $ctx->prefix( 'b', "http://example.org/b/" );
		$this->assertPrefix( 'a', "http://example.org/a/", $a, "Register first prefix" );
		$this->assertPrefix( 'b', "http://example.org/b/", $b, "Register second prefix" );

		# Re-registering the same prefix returns the previous prefix
		$a1 = $ctx->prefix( 'a', "http://example.org/a/" );
		$this->assertPrefix( 'a', "http://example.org/a/", $a1, "Re-registering a prefix" );
		$this->assertSame( $a, $a1, "Re-registering a prefix returns the same instance" );

		# By default trying to register an IRI with a different prefix returns the previous prefix instead
		$aa = $ctx->prefix( 'aa', "http://example.org/a/" );
		$this->assertPrefix( 'a', "http://example.org/a/", $aa, "Registering an already in use IRI with a new prefix" );

		# If true is passed for $keepPrefixIfPossible then instead duplicate the IRI with the new prefix
		$bb = $ctx->prefix( 'bb', "http://example.org/b/", true );
		$this->assertPrefix( 'bb', "http://example.org/b/", $bb, "Registering a duplicate IRI with a new prefix using keepPrefixIfPossible = true" );

		# Trying to register a new IRI on an already in-use prefix uses numbers
		$ax = $ctx->prefix( 'a', "http://example.org/x/" );
		$ay = $ctx->prefix( 'a', "http://example.org/y/" );
		$az = $ctx->prefix( 'a', "http://example.org/z/" );
		$this->assertPrefix( 'a1', "http://example.org/x/", $ax, "" );
		$this->assertPrefix( 'a2', "http://example.org/y/", $ay );
		$this->assertPrefix( 'a3', "http://example.org/z/", $az );

		# Trying to register an IRI already in use (keep) over a prefix that's already in use returns the
		# prefix in use for that IRI instead of registering a new prefix# style prefix.
		$c = $ctx->prefix( 'c', "http://example.org/c/" );
		$d = $ctx->prefix( 'd', "http://example.org/d/" );
		$cd = $ctx->prefix( 'c', "http://example.org/d/", true ); # /d/ in use by d, c registered with /c/
		$this->assertPrefix( 'd', "http://example.org/d/", $cd );
	}

	/**
	 * @require testSingleContext
	 */
	function testNestedContext() {
		$ctxP = new RDFa\PrefixContext();
		$ctxC = new RDFa\PrefixContext( $ctxP );

		# Basic prefix inheritance on the child
		$aP = $ctxP->prefix( 'a', "http://example.org/a/" );
		$aC = $ctxC->prefix( 'a', "http://example.org/a/" );
		$this->assertPrefix( 'a', "http://example.org/a/", $aP, "Prefix returned from parent" );
		$this->assertPrefix( 'a', "http://example.org/a/", $aC, "Prefix returned from child" );
		$this->assertSame( $aP, $aC, "Child returns the same instance as the parent." );

		# Prefix/IRI sharing when child tries to register a prefix that exists on the parent with a different prefix
		$aC2 = $ctxC->prefix( 'aa', "http://example.org/a/" );
		$this->assertPrefix( 'a', "http://example.org/a/", $aC2, "IRI sharing works across children" );
		$this->assertSame( $aP, $aC2, "IRI sharing works across children" );

		# Ensure that the child isn't registering duplicates
		$this->assertSame( 'a: http://example.org/a/', $ctxP->prefixAttribute(),
			"Registered prefix is defined on parent" );
		$this->assertSame( null, $ctxC->prefixAttribute(),
			"Registered prefix is not on child" );
	}

	/**
	 * @require testNestedContext
	 */
	function testNestedContextOverrides() {
		$ctxP = new RDFa\PrefixContext();
		$ctxC = new RDFa\PrefixContext( $ctxP );

		# Ensure child overrides the same prefix instead of using something like a1
		$aP  = $ctxP->prefix( 'a', "http://example.org/a/" );
		$aaC = $ctxC->prefix( 'a', "http://example.org/aa/" );
		$this->assertPrefix( 'a', "http://example.org/aa/", $aaC, "Child registers a prefix that overrides the parent" );

		# Ensure the prefix is registered on the child leaving the parent alone
		$this->assertSame( 'a: http://example.org/a/', $ctxP->prefixAttribute(),
			"Registered prefix on parent is left alone" );
		$this->assertSame( 'a: http://example.org/aa/', $ctxC->prefixAttribute(),
			"Overriding prefix is registered on child" );
	}

	/**
	 * @require testNestedContextOverrides
	 */
	function testNestedContextUsedOverrides() {
		$ctxP = new RDFa\PrefixContext();
		$ctxC = new RDFa\PrefixContext( $ctxP );

		# Register a prefix, use it, then register a different prefix on child
		$aP  = $ctxP->prefix( 'a', "http://example.org/a/" ); # register on parent
		$aC  = $ctxC->prefix( 'a', "http://example.org/a/" ); # use on child
		$aaC = $ctxC->prefix( 'a', "http://example.org/aa/" ); # register different prefix on child
		$aaaC = $ctxC->prefix( 'a', "http://example.org/aaa/" ); # register different prefix on child

		$this->assertPrefix( 'a1', "http://example.org/aa/", $aaC,
			"A prefix that was used but not registered is not overridden" );
		$this->assertPrefix( 'a2', "http://example.org/aaa/", $aaaC,
			"A prefix that was used but not registered is not overridden" );

		$this->assertSame( 'a: http://example.org/a/', $ctxP->prefixAttribute() );
		$this->assertSame( 'a1: http://example.org/aa/ a2: http://example.org/aaa/', $ctxC->prefixAttribute() );
	}

	function testPrefixAttribute() {
		$ctx = new RDFa\PrefixContext();

		$this->assertEquals( null, $ctx->prefixAttribute(), "Empty prefix map returns a null attribute value" );

		$ctx->prefix( 'dc',  "http://purl.org/dc/terms/" );
		$ctx->prefix( 'rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#" );
		$ctx->prefix( 'ex',  "http://example.org/" );

		$this->assertEquals(
			'dc: http://purl.org/dc/terms/ rdf: http://www.w3.org/1999/02/22-rdf-syntax-ns# ex: http://example.org/',
			$ctx->prefixAttribute() );
	}

}
