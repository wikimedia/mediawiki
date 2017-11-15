/*jshint esversion: 6,  node:true */

/**
 * Step definitions. Each step definition is bound to the World object,
 * so any methods or properties in World are available here.
 *
 * Not: Do not use the fat-arrow syntax to define step functions, because
 * Cucumber explicity binds the 'this' to 'World'. Arrow function would
 * bind `this` to the parent function instead, which is not what we want.
 */

const defineSupportCode = require('cucumber').defineSupportCode,
	SpecialVersion = require('../support/pages/special_version'),
	ArticlePage = require('../support/pages/article_page'),
	expect = require( 'chai' ).expect,
	querystring = require( 'querystring' );

// Attach extra information to assertion errors about what api call triggered the problem
function withApi( world, fn ) {
	try {
		return fn();
	} catch ( e ) {
		let request = world.apiResponse ? world.apiResponse.__request : world.apiError.request,
			qs = Object.assign( {}, request.qs, request.form ),
			href = request.uri + '?' + querystring.stringify( qs );

		e.message += `\nLast Api: ${href}`;
		if ( world.apiError ) {
			e.message += `\nError reported: ${JSON.stringify(world.apiError)}`;
		}
		throw e;
	}
}
defineSupportCode( function( {Given, When, Then} ) {

	When( /^I go to (.*)$/, function ( title ) {
		this.visit( ArticlePage.title( title ) );
	} );

	When( /^I ask suggestion API for (.*)$/, function ( query ) {
		return this.stepHelpers.suggestionSearch( query );
	} );

	When( /^I ask suggestion API at most (\d+) items? for (.*)$/, function( limit, query ) {
		return this.stepHelpers.suggestionSearch( query, limit );
	} );

	Then( /^there is a software version row for (.+)$/ , function ( name ) {
		expect( SpecialVersion.software_table_row( name ) ).not.to.equal( null );
	} );

	Then( /^the API should produce list containing (.*)/, function( term ) {
		withApi( this, () => {
			expect( this.apiResponse[ 1 ] ).to.include( term );
		} );
	} );

	Then( /^the API should produce empty list/, function() {
		withApi( this, () => {
			expect( this.apiResponse[ 1 ] ).to.have.length( 0 );
		} );
	} );

	Then( /^the API should produce list starting with (.*)/, function( term ) {
		withApi( this, () => {
			expect( this.apiResponse[ 1 ][ 0 ] ).to.equal( term );
		} );
	} );

	Then( /^the API should produce list of length (\d+)/, function( length ) {
		withApi( this, () => {
			expect( this.apiResponse[ 1 ] ).to.have.length( parseInt( length, 10 ) );
		} );
	} );

	When( /^the api returns error code (.*)$/, function ( code ) {
		withApi( this, () => {
			expect( this.apiError ).to.include( {
				code: code
			} );
		} );
	} );

	When( /^I get api suggestions for (.*?)(?: using the (.*) profile)?$/, function( search, profile ) {
		// TODO: Add step helper
		return this.stepHelpers.suggestionsWithProfile( search, profile || "fuzzy" );
	} );

	Then( /^(.+) is the (.+) api suggestion$/, function ( title, position ) {
		withApi( this, () => {
			let pos = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eigth', 'ninth', 'tenth'].indexOf( position );
			if ( title === "none" ) {
				if ( this.apiError && pos === 1 ) {
					// TODO: Why 1? maybe 0?
					return;
				} else {
					expect( this.apiResponse[1] ).to.have.lengthOf.at.most( pos );
				}
			} else {
				expect( this.apiResponse[1] ).to.have.lengthOf.at.least( pos );
				expect( this.apiResponse[1][pos] ).to.equal( title );
			}
		} );
	} );

	Then( /^(.+) is( not)? in the api suggestions$/, function ( title, should_not ) {
		withApi( this, () => {
			if ( should_not ) {
				expect( this.apiResponse[1] ).to.not.include( title );
			} else {
				expect( this.apiResponse[1] ).to.include( title );
			}
		} );
	} );

	Then( /^the api should offer to search for pages containing (.+)$/, function( term ) {
		withApi( this, () => {
			expect( this.apiResponse[0] ).to.equal( term );
		} );
	} );

	When( /^a page named (.+) exists(?: with contents (.+))?$/, function ( title, text ) {
		return this.stepHelpers.editPage( title, text || title, false );
	} );

	Then( /^I get api near matches for (.+)$/, function ( search ) {
		return this.stepHelpers.searchFor( search, { srwhat: "nearmatch" } );
	} );

	function checkApiSearchResultStep( title, in_ok, indexes ) {
		indexes = indexes.split( ' or ' ).map( ( index ) => {
			return 'first second third fourth fifth sixth seventh eighth ninth tenth'.split( ' ' ).indexOf( index );
		} );
		if ( title === "none" ) {
			expect( this.apiResponse.query.search ).to.have.lengthOf.below( 1 + Math.min.apply( null, indexes ) );
		} else {
			let found = indexes.map( pos => {
				if ( this.apiResponse.query.search[pos] ) {
					return this.apiResponse.query.search[pos].title;
				} else {
					return null;
				}
			} );
			if ( in_ok ) {
				// What exactly does this do?
				// expect(found).to include(include(title))
				throw new Error( 'Not Implemented' );
			} else {
				expect( found ).to.include(title);
			}
		}
	}
	Then( /^(.+) is( in)? the ((?:[^ ])+(?: or (?:[^ ])+)*) api search result$/, function ( title, in_ok, indexes ) {
		withApi( this, () => {
			checkApiSearchResultStep.call( this, title, in_ok, indexes );
		} );
	} );

	function apiSearchStep( enableRewrites, qiprofile, offset, lang, namespaces, search ) {
		let options = {
			srnamespace: (namespaces || "0").split(' '),
			srenablerewrites: enableRewrites ? 1 : 0,
		};
		if ( offset ) {
			options.sroffset = offset;
		}
		if ( lang ) {
			options.uselang = lang;
		}
		if ( qiprofile ) {
			options.srqiprofile = qiprofile;
		}
		// This is reset between scenarios
		if ( this.didyoumeanOptions ) {
			Object.assign(options, this.didyoumeanOptions );
		}

		// Generic string replacement of patterns stored in this.searchVars
		search = Object.keys(this.searchVars).reduce( ( str, pattern ) => str.replace( pattern, this.searchVars[pattern] ), search );
		// Replace %{\uXXXX}% with the appropriate unicode code point
		search = search.replace(/%\{\\i([\dA-Fa-f]{4,6})\}%/, ( match, codepoint ) => JSON.parse( `"\\u${codepoint}"` ) );

		return this.stepHelpers.searchFor( search, options );
	}
	When(/^I api search( with rewrites enabled)?(?: with query independent profile ([^ ]+))?(?: with offset (\d+))?(?: in the (.*) language)?(?: in namespaces? (\d+(?: \d+)*))? for (.*)$/, apiSearchStep );

	Then( /^within (\d+) seconds api searching for (.+) yields (.+) as the (.+) result$/, function( seconds, query, title, indexes ) {
		let timeout = Date.now() + ( 1000 * seconds );
		let runSteps = ( resolve, reject ) => {
			apiSearchStep.call( this, undefined, undefined, undefined, undefined, 0, query ).then( () => {
				checkApiSearchResultStep.call( this, title, false, indexes );
			} ).then( resolve, ( error ) => {
				if ( Date.now() > timeout ) {
					console.log( 'within rejected due to timeout' );
					reject( error );
				}
				console.log( 're-running within' );
				// Use process.nextTick to keep from exploding the stack.
				process.nextTick( () => runSteps( resolve, reject ) );
			} );
		};
		withApi( this, () => {
			return new Promise( runSteps );
		} );
	} );

	Then( /there are no errors reported by the api/, function () {
		withApi( this, () => {
			expect( this.apiError ).to.be.undefined; // jshint ignore:line
		} );
	} );

	Then( /there is an api search result/, function () {
		withApi( this, () => {
			expect( this.apiResponse.query.search ).to.not.have.lengthOf( 0 );
		} );
	} );

	Then( /^(.+) is( not)? in the api search results$/, function( title, not ) {
		withApi( this, () => {
			let titles = this.apiResponse.query.search.map( res => res.title );
			if ( not ) {
				expect( titles ).to.not.include( title );
			} else {
				expect( titles ).to.include( title );
			}
		} );
	} );
});
