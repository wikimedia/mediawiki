
/*
 * WikiTest is the base class for all the various
 * individual tests of the installed wiki, which
 * will be called by WikiSuite.
 */

package com.piclab.wikitest;
import com.meterware.httpunit.*;
import org.w3c.dom.*;
import java.util.regex.*;

public class WikiTest {

protected WikiSuite m_suite = null;
protected long m_start, m_stop;
protected boolean m_verboseflag = false;

/* All subclasses of WikiTest should override testName()
 * to return a useful name and runTest() to perform the actual
 * tests. runTest() should return true on success. You can
 * also overrise initTest() if you like; it gets run before
 * the individual test timer is started.
 */

public String testName() { return "Error"; }

protected int initTest() throws Exception {
	return 0;
}

protected int runTest() throws Exception {
	return 0;
}

/*
 * This is the primary entry point:
 */

public void run( WikiSuite ws ) {
	m_suite = ws;
	run();
}

private void run() {
	int result = 0;

	/* assert( m_suite != null ); */

	java.util.logging.Level ll = null;
	if ( m_verboseflag ) {
		ll = WikiSuite.setLoggingLevel( java.util.logging.Level.FINE );
	}

	try {
		result = initTest();
	} catch ( Exception e ) {
		WikiSuite.error( "Exception (" + e + ") initializing test \"" +
		  testName() + "\"" );
		e.printStackTrace();
		result = 1;
	}
	if ( result != 0 ) {
		WikiSuite.error( "Test \"" + testName() +
		  "\" failed to initialize with code " + result );
		return;
	}
	WikiSuite.info( "Started test \"" + testName() + "\"" );
	m_start = System.currentTimeMillis();

	try {
		result = runTest();
	} catch (Exception e) {
		WikiSuite.error( "Exception (" + e + ") running test \"" +
		  testName() + "\"" );
		e.printStackTrace();
		result = 2;
	}
	m_stop = System.currentTimeMillis();
	double time = (double)(m_stop - m_start) / 1000.0;

	StringBuffer sb = new StringBuffer(100);
	sb.append( "Test \"" ).append( testName() ).append( "\" " )
	  .append( (result==0) ? "Succeeded" : "Failed   " ).append( "   (" )
	  .append( WikiSuite.threeDecimals( time ) ).append( " secs)" );
	WikiSuite.info( sb.toString() );

	if ( m_verboseflag ) {
		WikiSuite.setLoggingLevel( ll );
	}
}

/*
 * General utility functions
 */

public int fail( int code ) {
	WikiSuite.error( "Test \"" + testName() + "\" failed with code " + code );
	return code;
}

public void clearCookies() { m_suite.getConv().clearContents(); }

/*
 * Encapsulate rules for converting a title to URL form; this
 * should match the equivalent function in the Wiki code.
 */

public static String titleToUrl( String title ) {
	StringBuffer sb = new StringBuffer( title.length() + 20 );

	if ( "".equals( title ) ) {
		title = WikiSuite.getMainPage();
	}

	for (int i=0; i<title.length(); ++i) {
		char c = title.charAt(i);
		if ((c >= 'A' && c <= 'Z') || (c >= 'a' && c <= 'z')) {
			sb.append(c);
		} else if (c >= '0' && c <= '9') {
			sb.append(c);
		} else if (c == '.' || c == '-' || c == '*' || c == ':' || c == '/'
		  || c == '(' || c == ')' || c == '_') {
			sb.append(c);
		} else if (c == ' ') {
			sb.append('_');
		} else {
			sb.append('%');
			String hex = "00" + Integer.toHexString((int)c);
			sb.append(hex.substring(hex.length() - 2));
		}
	}
	return sb.toString();
}

public static String viewUrl( String title, String query ) {
	StringBuffer url = new StringBuffer(200);
	String t = titleToUrl( title );

	url.append( WikiSuite.getServer() )
	  .append( WikiSuite.getScript() ).append( "?title=" ).append( t )
	  .append( "&" ).append( query );
	return url.toString();
}

public static String viewUrl( String title ) {
	StringBuffer url = new StringBuffer(200);
	String t = titleToUrl( title );
	String ap = WikiSuite.getArticlePath();

	int p = ap.indexOf( "$1" );
	if ( p >= 0 ) {
		url.append( ap );
		url.replace( p, p+2, t );
	} else {
		url.append( WikiSuite.getServer() )
		  .append( WikiSuite.getScript() ).append( "?title=" ).append( t );
	}
	return url.toString();
}

public WebResponse getResponse( String url )
throws WikiSuiteFailureException {
	WebResponse r = null;
	String msg = null;

	try {
		r = m_suite.getConv().getResponse( url );
	} catch (org.xml.sax.SAXException e) {
		msg = "Error parsing received page \"" + url + "\"";
		WikiSuite.warning( msg );
	} catch (java.net.MalformedURLException e) {
		msg = "Badly formed URL \"" + url + "\"";
		WikiSuite.fatal( msg );
		throw new WikiSuiteFailureException( msg );
	} catch (java.io.IOException e) {
		WikiSuite.warning( "I/O Error receiving page \"" + url + "\"" );
	}
	return r;
}

public WebResponse getResponse( WebRequest req ) {
	WebResponse r = null;

	try {
		r = m_suite.getConv().getResponse( req );
	} catch (org.xml.sax.SAXException e) {
		WikiSuite.warning( "Error parsing received page." );
	} catch (java.io.IOException e) {
		WikiSuite.warning( "I/O Error receiving page." );
	}
	return r;
}

public void showResponseTitle( WebResponse wr )
throws WikiSuiteFailureException {
	String t = null;

	try {
		t = wr.getTitle();
		if ( "Error".equals( t ) || "Database error".equals( t ) ) {
			throw new WikiSuiteFailureException( "Got wiki error page." );
		}
		WikiSuite.fine( "Viewing \"" + t + "\"" );
	} catch (org.xml.sax.SAXException e) {
		WikiSuite.error( "Exception (" + e + ")" );
		throw new WikiSuiteFailureException( "Couldn't parse title." );
	}
}

public WebResponse viewPage( String title )
throws WikiSuiteFailureException {
	WebResponse wr = getResponse( viewUrl( title ) );
	showResponseTitle( wr );
	return wr;
}

public WebResponse viewPage( String title, String query )
throws WikiSuiteFailureException {
	StringBuffer url = new StringBuffer( 200 );
	String t = titleToUrl( title );

	url.append( m_suite.getServer() )
	  .append( m_suite.getScript() ).append( "?title=" )
	  .append( t ).append( "&" ).append( query );

	WebResponse wr = getResponse( url.toString() );
	showResponseTitle( wr );
	return wr;
}

public WebResponse searchFor( String target, String query )
throws WikiSuiteFailureException {
	StringBuffer url = new StringBuffer( 200 );
	String t = null;
	
	try {
		t = java.net.URLEncoder.encode( target, "UTF-8" );
	} catch ( java.io.UnsupportedEncodingException e ) {
		throw new WikiSuiteFailureException( e.toString() );
	}
	url.append( m_suite.getServer() )
	  .append( m_suite.getScript() ).append( "?search=" )
	  .append( t );
	if ( ! "".equals( query ) ) {
		url.append( "&" ).append( query );
	}
	return getResponse( url.toString() );
}

public WebResponse searchFor( String target )
throws WikiSuiteFailureException {
	return searchFor( target, null );
}

public WebResponse editPage( String title )
throws WikiSuiteFailureException {
	return viewPage( title, "action=edit" );
}

public static WebForm getFormByName( WebResponse resp, String name )
throws WikiSuiteFailureException {
	String msg = null;
	WebForm[] forms = null;


	try {
		forms = resp.getForms();
	} catch ( org.xml.sax.SAXException e ) {
		msg = "Couldn't find form \"" + name + "\"";
		WikiSuite.error( msg );
		return null;
	}
	for (int i=0; i < forms.length; ++i) {
		Node formNode = forms[i].getDOMSubtree();
		NamedNodeMap nnm = formNode.getAttributes();
		Node nameNode = nnm.getNamedItem( "id" );

		if (nameNode == null) continue;
		if (nameNode.getNodeValue().equalsIgnoreCase( name )) {
			return forms[i];
		}
	}
	return null;
}

public WebResponse loginAs( String name, String password )
throws WikiSuiteFailureException {
	WebResponse wr = null;
	WebRequest req = null;

	wr = viewPage( "Special:Userlogin" );

	WebForm loginform = getFormByName( wr, "userlogin" );
	req = loginform.getRequest( "wpLoginattempt" );
	req.setParameter( "wpName", name );
	req.setParameter( "wpPassword", password );
   	wr = getResponse( req );

	WikiSuite.fine( "Logged in as " + name );
	return wr;
}

public WebResponse logout()
throws WikiSuiteFailureException {
	WebResponse wr = viewPage( "Special:Userlogout" );
	clearCookies();
	return wr;
}

public WebResponse deletePage( String title )
throws WikiSuiteFailureException {
	WebResponse wr = null;

	wr = loginAs( "WikiSysop", m_suite.getSysopPass() );
	wr = viewPage( title, "action=delete" );

	String rt = null;
	try {
		rt = wr.getTitle();
	} catch ( org.xml.sax.SAXException e ) {
		WikiSuite.error( "Could not parse response to delete request." );
		wr = logout();
		return null;
	}

	if ( rt.equals( "Internal error" ) ) {
		wr = logout();
		return null;
		/* Can't delete because it doesn't exist: no problem */
	}

	WebForm delform = getFormByName( wr, "deleteconfirm" );
	WebRequest req = delform.getRequest( "wpConfirmB" );

	req.setParameter( "wpReason", "Deletion for testing" );
	req.setParameter( "wpConfirm", "1" );

	WebResponse ret = getResponse( req );
	WikiSuite.fine( "Deleted \"" + title + "\"" );

	wr = logout();
	return ret;
}

public WebResponse replacePage( String page, String text )
throws WikiSuiteFailureException {
	WebResponse wr = editPage( page );

	WebForm editform = getFormByName( wr, "editform" );
	WebRequest req = editform.getRequest( "wpSave" );
	req.setParameter( "wpTextbox1", text );
	return getResponse( req );
}

public WebResponse addText( String page, String text )
throws WikiSuiteFailureException {
	WebResponse wr = editPage( page );

	WebForm editform = getFormByName( wr, "editform" );
	WebRequest req = editform.getRequest( "wpSave" );
	String old = req.getParameter( "wpTextbox1" );
	
	req.setParameter( "wpTextbox1", old + "\n" + text );
	req.setParameter( "wpSummary", "Wikitest addition to " + page );

	return getResponse( req );
}

public WebRequest openPrefs() throws WikiSuiteFailureException {
	WebResponse wr = viewPage( "Special:Preferences" );
    WebForm pform = getFormByName( wr, "preferences" );
	return pform.getRequest( "wpSaveprefs" );
}

private static Pattern m_startdiv = null, m_enddiv = null;

public String getArticle( WebResponse wr )
throws WikiSuiteFailureException {
	String msg = null;
	String text = null;
	Matcher m = null;

	if ( m_startdiv == null ) {
		m_startdiv = Pattern.compile( "<div\\s[^>]*id\\s*=\\s*.article[^>]*>",
		  Pattern.CASE_INSENSITIVE | Pattern.DOTALL );
		m_enddiv = Pattern.compile( "</div[^>]*>",
		  Pattern.CASE_INSENSITIVE | Pattern.DOTALL );
	}
	try {
		text = wr.getText();
	} catch ( java.io.IOException e ) {
		msg = "Error (" + e + ") parsing page.";
		WikiSuite.error( msg );
		throw new WikiSuiteFailureException( msg );
	}
	m = m_startdiv.matcher( text );
	if (! m.find()) {
		throw new WikiSuiteFailureException( "Can't find article div start." );
	}

	text = text.substring( m.end() );
	m = m_enddiv.matcher( text );
	if (! m.find()) {
		throw new WikiSuiteFailureException( "Can't find article div end." );
	}
	text = text.substring( 0, m.start() );
	return text;
}

public int checkGoodPatterns( String text, String[] pats ) {
	Pattern p = null;
	Matcher m = null;

	for ( int i = 0; i < pats.length; ++i ) {
		p = Pattern.compile( pats[i], Pattern.CASE_INSENSITIVE );
		m = p.matcher( text );

		if ( ! m.find() ) { return 1 + i; }
	}
	return 0;
}

public int checkBadPatterns( String text, String[] badpats ) {
	Pattern p = null;
	Matcher m = null;

	for ( int i = 0; i < badpats.length; ++i ) {
		p = Pattern.compile( badpats[i], Pattern.CASE_INSENSITIVE );
		m = p.matcher( text );

		if ( m.find() ) { return 1 + i; }
	}
	return 0;
}

/*
 * The main method of a subclass should be able to just create 
 * an instance of itself and call runSingle() to perform a
 * standalone test, and we'll handle the commandline and
 * setting up a suite object, etc. They are of course welcome
 * to set up a more complex main if they want.
 */

public void runSingle( String[] params ) {
	/*
	 * Do command line. For now, just verbose flag.
	 */
	for ( int i = 0; i < params.length; ++i ) {
		if ( "-v".equals( params[i].substring( 0, 2 ) ) ) {
			m_verboseflag = true;
		}
	}
	run( new WikiSuite() );
}

public static void main( String params ) {
	System.out.println( "WikiTest is not a runnable class." );
}

}
