
/*
 * Special "test" for initial loading of the database.
 * It doesn't do the normal run-and-report thing, but it
 * it inherits from WikiTest because it needs to do the
 * same kinds of things that tests do, i.e., interacting
 * with the wiki.
 */

package com.piclab.wikitest;
import com.meterware.httpunit.*;

public class DBLoader extends WikiTest {

private WebResponse loadPageFromFile( String title )
throws WikiSuiteFailureException {
	StringBuffer url = new StringBuffer(200);
	String t = WikiTest.titleToUrl( title );

	url.append( "data/" ).append( t ).append( ".txt" );
	String text = WikiSuite.loadText( url.toString() );

	WebResponse wr = replacePage( title, text );
	WikiSuite.fine( "Loaded \"" + title + "\"" );
	return wr;
}

/*
 * Load database with initial set of pages for testing.
 */

public void initializeDatabase( WikiSuite ws, boolean f_overwrite ) {
	WebResponse wr = null;
	String text = null;

	m_suite = ws;
	try {
		wr = viewPage( "" );
	} catch ( WikiSuiteFailureException e ) {
		WikiSuite.error( "Can't find Wikipedia installation." );
		return;
	}
	if ( ! f_overwrite ) {
		try {
			text = getArticle( wr );
			if ( text.indexOf( "no text in this page" ) < 0 ) {
				WikiSuite.error( "Target wiki is not empty." );
				return;
			}
		} catch( WikiSuiteFailureException e ) {
			WikiSuite.error( "Can't access target wiki." );
			return;
		}
	}
	WikiSuite.info( "Preloading database with test pages." );

	for (int i = 0; i < WikiSuite.preloadedPages.length; ++i) {
		try {
			wr = loadPageFromFile( WikiSuite.preloadedPages[i] );
		} catch (WikiSuiteFailureException e) {
			WikiSuite.warning( "Failed to load \"" +
			  WikiSuite.preloadedPages[i] + "\"" );
		}
	}

	WikiSuite.info( "Creating test users." );
	try {
		wr = viewPage( "Special:Userlogin" );
		WebForm loginform = getFormByName( wr, "userlogin" );
		WebRequest req = loginform.getRequest( "wpCreateaccount" );
		req.setParameter( "wpName", "Fred" );
		req.setParameter( "wpPassword", "Fred" );
		req.setParameter( "wpRetype", "Fred" );
		wr = getResponse( req );

		wr = viewPage( "Special:Userlogin" );
		loginform = getFormByName( wr, "userlogin" );
		req = loginform.getRequest( "wpCreateaccount" );
		req.setParameter( "wpName", "Barney" );
		req.setParameter( "wpPassword", "Barney" );
		req.setParameter( "wpRetype", "Barney" );
		wr = getResponse( req );

		logout();
	} catch ( Exception e ) {
		WikiSuite.error( "Exception (" + e + ") parsing login form." );
	}
}

}
