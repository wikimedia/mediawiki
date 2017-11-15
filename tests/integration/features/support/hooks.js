/*jshint esversion: 6, node:true */

var {defineSupportCode} = require( 'cucumber' );

defineSupportCode( function( { After, Before } ) {
	let BeforeOnce = function ( options, fn ) {
		Before( options, function () {
			return this.tags.check( options.tags ).then( ( status ) => {
				if ( status === 'new' ) {
					return fn.call ( this ).then( () => this.tags.complete( options.tags ) );
				}
			} );
		} );
	};

	BeforeOnce( { tags: "@clean" }, function () {
		return this.stepHelpers.deletePage( "DeleteMeRedirect" );
	} );

	Before( { tags: "@api" }, function () {
		return true;
	} );

	BeforeOnce( { tags: "@prefix" }, function () {
		console.log( 'starting prefix hook' );
		let batchJobs = {
			edit: {
				"L'Oréal": "L'Oréal",
				"Jean-Yves Le Drian": "Jean-Yves Le Drian"
			}
		};
		return this.onWiki().then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.batch(batchJobs, 'CirrusSearch integration test edit');
			} );
		} );
	} );

	BeforeOnce( { tags: "@redirect" }, function () {
		let batchJobs = {
			edit: {
				"SEO Redirecttest": "#REDIRECT [[Search Engine Optimization Redirecttest]]",
				"Redirecttest Yikes": "#REDIRECT [[Redirecttest Yay]]",
				"User_talk:SEO Redirecttest": "#REDIRECT [[User_talk:Search Engine Optimization Redirecttest]]",
				"Seo Redirecttest": "Seo Redirecttest",
				"Search Engine Optimization Redirecttest": "Search Engine Optimization Redirecttest",
				"Redirecttest Yay": "Redirecttest Yay",
				"User_talk:Search Engine Optimization Redirecttest": "User_talk:Search Engine Optimization Redirecttest",
				"PrefixRedirectRanking 1": "PrefixRedirectRanking 1",
				"LinksToPrefixRedirectRanking 1": "[[PrefixRedirectRanking 1]]",
				"TargetOfPrefixRedirectRanking 2": "TargetOfPrefixRedirectRanking 2",
				"PrefixRedirectRanking 2": "#REDIRECT [[TargetOfPrefixRedirectRanking 2]]"
			}
		};
		return this.onWiki().then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.batch(batchJobs, 'CirrusSearch integration test edit');
			} );
		} );
	} );

	BeforeOnce( { tags: "@accent_squashing" }, function () {
		let batchJobs = {
			edit: {
				"Áccent Sorting": "Áccent Sorting",
				"Accent Sorting": "Accent Sorting"
			}
		};
		return this.onWiki().then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.batch(batchJobs, 'CirrusSearch integration test edit');
			} );
		} );
	} );

	BeforeOnce( { tags: "@accented_namespace" }, function () {
		let batchJobs = {
			edit: {
				"Mó:Test": "some text"
			}
		};
		return this.onWiki().then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.batch(batchJobs, 'CirrusSearch integration test edit');
			} );
		} );
	} );

	BeforeOnce( { tags: "@suggest" }, function () {
		let batchJobs = {
			edit: {
				"X-Men": "The X-Men are a fictional team of superheroes",
				"Xavier: Charles": "Professor Charles Francis Xavier (also known as Professor X) is the founder of [[X-Men]]",
				"X-Force": "X-Force is a fictional team of of [[X-Men]]",
				"Magneto": "Magneto is a fictional character appearing in American comic books",
				"Max Eisenhardt": "#REDIRECT [[Magneto]]",
				"Eisenhardt: Max": "#REDIRECT [[Magneto]]",
				"Magnetu": "#REDIRECT [[Magneto]]",
				"Ice": "It's cold.",
				"Iceman": "Iceman (Robert \"Bobby\" Drake) is a fictional superhero appearing in American comic books published by Marvel Comics and is...",
				"Ice Man (Marvel Comics)": "#REDIRECT [[Iceman]]",
				"Ice-Man (comics books)": "#REDIRECT [[Iceman]]",
				"Ultimate Iceman": "#REDIRECT [[Iceman]]",
				"Électricité": "This is electicity in french.",
				"Elektra": "Elektra is a fictional character appearing in American comic books published by Marvel Comics.",
				"Help:Navigation": "When viewing any page on MediaWiki...",
				"V:N": "#REDIRECT [[Help:Navigation]]",
				"Z:Navigation": "#REDIRECT [[Help:Navigation]]",
				"Venom": "Venom: or the Venom Symbiote: is a fictional supervillain appearing in American comic books published by Marvel Comics",
				"Sam Wilson": "Warren Kenneth Worthington III: originally known as Angel and later as Archangel: ... Marvel Comics like [[Venom]]. {{DEFAULTSORTKEY:Wilson: Sam}}",
				"Zam Wilson": "#REDIRECT [[Sam Wilson]]",
				"The Doors": "The Doors were an American rock band formed in 1965 in Los Angeles.",
				"Hyperion Cantos/Endymion": "Endymion is the third science fiction novel by Dan Simmons.",
				"はーい": "makes sure we do not fail to index empty tokens (T156234)"
			}
		};
		return this.onWiki().then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.batch(batchJobs, 'CirrusSearch integration test edit');
			} ).then( () => {
				return api.request( {
					action: 'cirrus-suggest-index'
				} );
			} );
		} );
	} );

	BeforeOnce( { tags: "@setup_main or @filters or @prefix or @bad_syntax or @wildcard or @exact_quotes or @phrase_prefix" }, function () {
		let batchJobs = {
			edit: {
				"Template:Template Test": "pickles [[Category:TemplateTagged]]",
				"Catapult/adsf": "catapult subpage [[Catapult]]",
				"Links To Catapult": "[[Catapult]]",
				"Catapult": "♙ asdf [[Category:Weaponry]]",
				"Amazing Catapult": "test [[Catapult]] [[Category:Weaponry]]",
				"Category:Weaponry": "Weaponry refers to any items designed or used to attack and kill or destroy other people and property.",
				"Two Words": "ffnonesenseword catapult {{Template_Test}} anotherword [[Category:TwoWords]] [[Category:Categorywith Twowords]] [[Category:Categorywith \" Quote]]",
				"AlphaBeta": "[[Category:Alpha]] [[Category:Beta]]",
				"IHaveATwoWordCategory": "[[Category:CategoryWith ASpace]]",
				"Functional programming": "Functional programming is referential transparency.",
				"वाङ्मय": "वाङ्मय",
				"वाङ्\u200dमय": "वाङ्\u200dमय",
				"वाङ्\u200cमय": "वाङ्\u200cमय",
				"ChangeMe": "foo",
				"Wikitext": "{{#tag:somebug}}",
				"Page with non ascii letters": "ἄνθρωπος, широкий"
			}
		};
		return this.onWiki().then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.batch(batchJobs, 'CirrusSearch integration test edit');
			} );
		} );
	} );

	BeforeOnce( { tags: "@setup_main or @prefix or @bad_syntax" }, function () {
		// TODO: File upload
		// And a file named File:Savepage-greyed.png exists with contents Savepage-greyed.png and description Screenshot, for test purposes, associated with https://bugzilla.wikimedia.org/show_bug.cgi?id=52908 .
		let batchJobs = {
			edit: {
				"Rdir": "#REDIRECT [[Two Words]]",
				"IHaveAVideo": "[[File:How to Edit Article in Arabic Wikipedia.ogg|thumb|267x267px]]",
				"IHaveASound": "[[File:Serenade for Strings -mvt-1- Elgar.ogg]]"
			}
		};
		return this.onWiki().then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.batch(batchJobs, 'CirrusSearch integration test edit');
			} );
		} );
	} );

	BeforeOnce( { tags: "@setup_main or @prefix or @go or @bad_syntax" }, function () {
		let batchJobs = {
			edit: {
				"África": "for testing"
			}
		};
		return this.onWiki().then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.batch(batchJobs, 'CirrusSearch integration test edit');
			} );
		} );
	} );

	BeforeOnce( { tags: "@boost_template" }, function () {
		let batchJobs = {
			edit: {
				"Template:BoostTemplateHigh": "BoostTemplateTest",
				"Template:BoostTemplateLow": "BoostTemplateTest",
				"NoTemplates BoostTemplateTest": "nothing important",
				"HighTemplate": "{{BoostTemplateHigh}}",
				"LowTemplate": "{{BoostTemplateLow}}",
			}
		};
		return this.onWiki().then( ( api ) => {
			return api.loginGetEditToken().then( () => {
				return api.batch(batchJobs, 'CirrusSearch integration test edit');
			} );
		} );
	} );
} );
