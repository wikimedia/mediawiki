( function ( mw, $ ) {
	QUnit.module( 'mediawiki.api.edit', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	QUnit.test( 'edit( title, transform String )', function ( assert ) {
		this.server.respond( function ( req ) {
			if ( /query.+titles=Sandbox/.test( req.url ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' }, JSON.stringify( {
					curtimestamp: '2016-01-02T12:00:00Z',
					query: {
						pages: [ {
							pageid: 1,
							ns: 0,
							title: 'Sandbox',
							revisions: [ {
								timestamp: '2016-01-01T12:00:00Z',
								contentformat: 'text/x-wiki',
								contentmodel: 'wikitext',
								content: 'Sand.'
							} ]
						} ]
					}
				} ) );
			}
			if ( /edit.+basetimestamp=2016-01-01.+starttimestamp=2016-01-02.+text=Box%2E/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' }, JSON.stringify( {
					edit: {
						result: 'Success',
						oldrevid: 11,
						newrevid: 13,
						newtimestamp: '2016-01-03T12:00:00Z'
					}
				} ) );
			}
		} );

		return new mw.Api()
			.edit( 'Sandbox', function ( revision ) {
				return revision.content.replace( 'Sand', 'Box' );
			} )
			.then( function ( edit ) {
				assert.equal( edit.newrevid, 13 );
			} );
	} );

	QUnit.test( 'edit( title, transform Promise )', function ( assert ) {
		this.server.respond( function ( req ) {
			if ( /query.+titles=Async/.test( req.url ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' }, JSON.stringify( {
					curtimestamp: '2016-02-02T12:00:00Z',
					query: {
						pages: [ {
							pageid: 4,
							ns: 0,
							title: 'Async',
							revisions: [ {
								timestamp: '2016-02-01T12:00:00Z',
								contentformat: 'text/x-wiki',
								contentmodel: 'wikitext',
								content: 'Async.'
							} ]
						} ]
					}
				} ) );
			}
			if ( /edit.+basetimestamp=2016-02-01.+starttimestamp=2016-02-02.+text=Promise%2E/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' }, JSON.stringify( {
					edit: {
						result: 'Success',
						oldrevid: 21,
						newrevid: 23,
						newtimestamp: '2016-02-03T12:00:00Z'
					}
				} ) );
			}
		} );

		return new mw.Api()
			.edit( 'Async', function ( revision ) {
				return $.Deferred().resolve( revision.content.replace( 'Async', 'Promise' ) );
			} )
			.then( function ( edit ) {
				assert.equal( edit.newrevid, 23 );
			} );
	} );

	QUnit.test( 'edit( title, transform Object )', function ( assert ) {
		this.server.respond( function ( req ) {
			if ( /query.+titles=Param/.test( req.url ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' }, JSON.stringify( {
					curtimestamp: '2016-03-02T12:00:00Z',
					query: {
						pages: [ {
							pageid: 3,
							ns: 0,
							title: 'Param',
							revisions: [ {
								timestamp: '2016-03-01T12:00:00Z',
								contentformat: 'text/x-wiki',
								contentmodel: 'wikitext',
								content: '...'
							} ]
						} ]
					}
				} ) );
			}
			if ( /edit.+basetimestamp=2016-03-01.+starttimestamp=2016-03-02.+text=Content&summary=Sum/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' }, JSON.stringify( {
					edit: {
						result: 'Success',
						oldrevid: 31,
						newrevid: 33,
						newtimestamp: '2016-03-03T12:00:00Z'
					}
				} ) );
			}
		} );

		return new mw.Api()
			.edit( 'Param', function () {
				return { text: 'Content', summary: 'Sum' };
			} )
			.then( function ( edit ) {
				assert.equal( edit.newrevid, 33 );
			} );
	} );

	QUnit.test( 'create( title, content )', function ( assert ) {
		this.server.respond( function ( req ) {
			if ( /edit.+text=Sand/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' }, JSON.stringify( {
					edit: {
						new: true,
						result: 'Success',
						newrevid: 41,
						newtimestamp: '2016-04-01T12:00:00Z'
					}
				} ) );
			}
		} );

		return new mw.Api()
			.create( 'Sandbox', { summary: 'Load sand particles.' }, 'Sand.' )
			.then( function ( page ) {
				assert.equal( page.newrevid, 41 );
			} );
	} );

}( mediaWiki, jQuery ) );
