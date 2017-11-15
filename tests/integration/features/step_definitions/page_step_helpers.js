/*jshint esversion: 6,  node:true */
/**
 * StepHelpers are abstracted functions that usually represent the
 * behaviour of a step. They are placed here, instead of in the actual step,
 * so that they can be used in the Hook functions as well.
 *
 * Cucumber.js considers calling steps explicitly an antipattern,
 * and therefore this ability has not been implemented in Cucumber.js even though
 * it is available in the Ruby implementation.
 * https://github.com/cucumber/cucumber-js/issues/634
 */

const expect = require( 'chai' ).expect,
	fs = require( 'fs' ),
	path = require( 'path' );

class StepHelpers {
	constructor( world, wiki ) {
		this.world = world;
		this.apiPromise = world.onWiki( wiki || world.config.wikis.default );
	}

	onWiki( wiki ) {
		return new StepHelpers( this.world, wiki );
	}

	deletePage( title ) {
		return this.apiPromise.then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.delete( title, "CirrusSearch integration test delete" )
					.catch( ( err ) => {
						// still return true if page doesn't exist
						return expect( err.message ).to.include( "doesn't exist" );
					} );
			} );
		} );
	}

	editPage( title, text, append = false ) {
		return this.apiPromise.then( ( api ) => {
			if ( text[0] === '@' ) {
				text = fs.readFileSync( path.join( __dirname, 'articles', text.substr( 1 ) ) ).toString();
			}
			return this.getWikitext( title ).then( ( fetchedText ) => {
				if ( append ) {
					text = fetchedText + text;
				}
				if ( text.trim() !== fetchedText.trim() ) {
					return api.loginGetEditToken().then( () => api.edit( title, text ) );
				}
			}, ( error ) => {
				throw error;
			} );
		} );
	}

	getWikitext( title ) {
		return this.apiPromise.then( ( api ) => {
			return api.request( {
				action: "query",
				format: "json",
				formatversion: 2,
				prop: "revisions",
				rvprop: "content",
				titles: title
			} ).then( ( response ) => {
				if ( response.query.pages[0].missing ) {
					return "";
				}
				return response.query.pages[0].revisions[0].content;
			} );
		} );
	}

	suggestionSearch( query, limit = 'max' ) {
		return this.apiPromise.then( ( api ) => {
			return api.request( {
				action: 'opensearch',
				search: query,
				cirrusUseCompletionSuggester: 'yes',
				limit: limit
			} );
		} ).then(
			( response ) => this.world.setApiResponse( response ),
			( error ) => this.world.setApiError( error ) );
	}

	suggestionsWithProfile( query, profile ) {
		return this.apiPromise.then( ( api ) => {
			return api.request( {
				action: 'opensearch',
				search: query,
				profile: profile
			} );
		} ).then(
			( response ) => this.world.setApiResponse( response ),
			( error ) => this.world.setApiError( error ) );
	}

	searchFor( query, options = {} ) {
		return this.apiPromise.then( ( api ) => {
			return api.request( Object.assign( options, {
				action: "query",
				list: "search",
				srsearch: query,
				srprop: "snippet|titlesnippet|redirectsnippet|sectionsnippet|categorysnippet|isfilematch",
				formatversion: 2
			} ) );
		} ).then(
			( response ) => this.world.setApiResponse( response ),
			( error ) => this.world.setApiError( error ) );
	}
}

module.exports = StepHelpers;
