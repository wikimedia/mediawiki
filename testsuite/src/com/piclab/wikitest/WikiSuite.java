/*
 * WikiSuite is the driver class for the wiki test suite.
 * It represents the location of the wiki, and provides
 * some common static routines for access.  When idividual
 * tests are instantiated, they are passed this object,
 * and they use its utility functions and result reporting.
 */

package com.piclab.wikitest;

import com.meterware.httpunit.*;
import java.util.prefs.*;
import java.util.logging.*;
import java.io.*;

public class WikiSuite {

private static Preferences ms_uprefs =
  Preferences.userNodeForPackage( WikiSuite.class );

/* Settings loaded from preferences:
 */
private static String ms_server = null;
private static String ms_script = null;
private static String ms_articlepath = null;
private static String ms_uploadpath = null;
private static String ms_mainpage = null;
private static String ms_sysoppass = null;

/* Primary conversation for test suite; individual
 * tests may also create their own if needed.
 */
private WebConversation m_conv;

private static Logger ms_logger = Logger.getLogger( "com.piclab.wikitest" );

static {
	/* Set logging level and properties:
	*/
	ms_logger.setUseParentHandlers( false );
	Handler h = new StreamHandler( System.out, new WikiLogFormatter() );
	h.setLevel( Level.INFO );

	ms_logger.addHandler( h );
	ms_logger.setLevel( Level.INFO );
	ms_logger.setFilter( null );
}

public static String preloadedPages[] = { "Agriculture", "Anthropology",
	"Archaeology", "Architecture", "Astronomy_and_astrophysics",
	"Biology", "Business_and_industry", "Card_game", "Chemistry",
	"Classics", "Communication", "Computer_Science", "Cooking",
	"Critical_theory", "Dance", "Earth_science", "Economics",
	"Education", "Engineering", "Entertainment",
	"Family_and_consumer_science", "Film", "Game", "Geography",
	"Handicraft", "Health_science", "History_of_science_and_technology",
	"History", "Hobby", "Language", "Law",
	"Library_and_information_science", "Linguistics", "Literature",
	"Main_Page", "Mathematics", "Music", "Opera", "Painting",
	"Philosophy", "Physics", "Poker", "Political_science", "Psychology",
	"Public_affairs", "Recreation", "Religion", "Sculpture",
	"Sociology", "Sport", "Statistics", "Technology", "Theater",
	"Tourism", "Transport", "Visual_arts_and_design",
	"World_Series_of_Poker",
	
	"Bracketvars", "Quotes", "Headings", "Blocklevels",
	"ExternalLinks", "InternalLinks", "Magics", "Equations" };

/* Suite constructor: load the prefs to determine which
 * wiki to test.
 */

public WikiSuite() {
	try {
		ms_uprefs.importPreferences(new java.io.FileInputStream(
		  "wikitest.prefs" ));
	} catch (java.io.IOException e) {
		/* File probably doesn't exist: no problem, use defaults */
	} catch (InvalidPreferencesFormatException e) {
		System.err.println( "Bad preferences file format: " + e );
	}

	ms_server = ms_uprefs.get( "server", "http://localhost" );
	ms_script = ms_uprefs.get( "script", "/wiki.phtml" );
	ms_articlepath = ms_uprefs.get( "articlepath", "" );
	ms_uploadpath = ms_uprefs.get( "uploadpath", "http://localhost/upload/" );
	ms_mainpage = ms_uprefs.get( "mainpage", "Main Page" );
	ms_sysoppass = ms_uprefs.get( "sysoppass", "adminpass" );

	m_conv = new WebConversation();
}

public WebConversation getConv() { return m_conv; }
public static String getServer() { return ms_server; }
public static String getScript() { return ms_script; }
public static String getArticlePath() { return ms_articlepath; }
public static String getUploadPath() { return ms_uploadpath; }
public static String getMainPage() { return ms_mainpage; }
public static String getSysopPass() { return ms_sysoppass; }

/*
 * Logging/reporting routines:
 */

public static void fatal( String msg ) {
	ms_logger.severe( msg );
	ms_logger.getHandlers()[0].flush();
}

public static void error( String msg ) {
	ms_logger.severe( msg );
	ms_logger.getHandlers()[0].flush();
}

public static void warning( String msg ) {
	ms_logger.warning( msg );
	ms_logger.getHandlers()[0].flush();
}

public static void info( String msg ) {
	ms_logger.info( msg );
	ms_logger.getHandlers()[0].flush();
}

public static void fine( String msg ) {
	ms_logger.fine( msg );
	ms_logger.getHandlers()[0].flush();
}

public static void finer( String msg ) {
	ms_logger.finer( msg );
	ms_logger.getHandlers()[0].flush();
}

public static Level setLoggingLevel( Level newl ) {
	Level oldl = ms_logger.getLevel();

	ms_logger.getHandlers()[0].setLevel( newl );
	ms_logger.setLevel( newl );
	return oldl;
}

public static String threeDecimals( double val ) {
	String result = "ERROR";
	java.text.DecimalFormat df =
	  (java.text.DecimalFormat)(java.text.NumberFormat.getInstance());

	df.applyPattern( "#######0.000" );
	result = df.format( val );
	return result;
}

/*
 * Utility functions for loading and saving strings from/to a file.
 */

public static void saveText( String text, String filename ) {
	try {
		PrintWriter pw = new PrintWriter( new FileOutputStream( filename ) );
		pw.write( text );
		pw.close();
	} catch( IOException e ) {
		error( "Couldn't write to \"" + filename + "\"" );
		return;
	}
	fine( "Saved \"" + filename + "\"" );
}

public static String loadText( String fname )
{
	FileInputStream fis = null;
	BufferedInputStream bis;

	try {
		fis = new FileInputStream( fname );
	} catch (FileNotFoundException e) {
		error( "File \"" + fname + "\" not found." );
		return null;
	}
	bis = new BufferedInputStream( fis );

	int r;
	StringBuffer result = new StringBuffer( 2048 );
	byte[] buf = new byte[1024];

	while (true) {
		r = -1;
		try {
			r = bis.read( buf );		
		} catch (IOException e) {
			error( "I/O Error reading \"" + fname + "\"." );
			break;
		}
		if ( r <= 0 ) break;

		try {
			result.append( new String( buf, 0, r, "ISO8859_1" ) );
		} catch ( java.io.UnsupportedEncodingException e ) {
			result.append( new String( buf, 0, r ) );
		}
	}
	try {
		bis.close();
		fis.close();
	} catch (IOException e) {
		warning( "I/O Error closing file \"" + fname + "\"." );
	}
	fine( "Loaded \"" + fname + "\"" );
	return result.toString();
}

/*
 * Start background threads that run while all the other
 * tests are going on.
 */

private WikiFetchThread m_wft;
private WikiSearchThread m_wst;

private void startBackgroundThreads() {
	info( "Starting background threads." );

	m_wft = new WikiFetchThread();
	m_wft.start();

	m_wst = new WikiSearchThread();
	m_wst.start();
}

private void stopBackgroundThreads() {
	synchronized (m_wft) {
		m_wft.requestStop();
		try {
			m_wft.wait( 30000 );
		} catch ( InterruptedException e ) {
			error( "Problem stopping background fetch thread." );
		}
	}
	synchronized (m_wst) {
		m_wst.requestStop();
		try {
			m_wst.wait( 30000 );
		} catch ( InterruptedException e ) {
			error( "Problem stopping background search thread." );
		}
	}
	info( "Stopped background threads." );

	int fetches = m_wft.getFetches();
	double time = (double)(m_wft.getTime()) / 1000.0;
	double avtime = time / (double)fetches;

	StringBuffer sb = new StringBuffer(100);
	sb.append( "Fetched " ).append( fetches ).append( " pages in " )
	  .append( threeDecimals( time ) ).append( " sec (" )
	  .append( threeDecimals( avtime ) ).append( " sec per fetch)." );
	info( sb.toString() );

	int searches = m_wst.getSearches();
	time = (double)(m_wst.getTime()) / 1000.0;
	avtime = time / (double)fetches;

	sb.setLength(0);
	sb.append( "Performed " ).append( searches ).append( " searches in " )
	  .append( threeDecimals( time ) ).append( " sec (" )
	  .append( threeDecimals( avtime ) ).append( " sec per search)." );
	info( sb.toString() );
}

/*
 * Main suite starts here.  Interpret command line, load the
 * database, then run the individual tests.
 */

private static boolean f_skipload = false;
private static boolean f_nobackground = false;
private static boolean f_overwrite = false;
private static boolean f_skipmath = false;

public static void main( String[] params ) {
	for ( int i = 0; i < params.length; ++i ) {
		if ( "-p".equals( params[i].substring( 0, 2 ) ) ) {
			f_skipload = true;
		} else if ( "-v".equals( params[i].substring( 0, 2 ) ) ) {
			setLoggingLevel( Level.ALL );
		} else if ( "-m".equals( params[i].substring( 0, 2 ) ) ) {
			f_skipmath = true;
		} else if ( "-b".equals( params[i].substring( 0, 2 ) ) ) {
			f_nobackground = true;
		} else if ( "-o".equals( params[i].substring( 0, 2 ) ) ) {
			f_overwrite = true;
		} else if ( "-h".equals( params[i].substring( 0, 2 ) )
				|| "-?".equals( params[i].substring( 0, 2 ) ) ) {
			System.out.println( "Usage: java WikiSuite [-povb]\n" +
			  "  -p : Skip preload of database\n" +
			  "  -m : Skip math test\n" +
			  "  -o : Overwrite database\n" +
			  "  -v : Verbose logging\n" +
			  "  -b : No background thread\n" );
			return;
		}
	}
	WikiSuite ws = new WikiSuite();
	if ( ! f_skipload ) {
		(new DBLoader()).initializeDatabase( ws, f_overwrite );
	}

	info( "Started Wikipedia Test Suite" );
	long start_time = System.currentTimeMillis();
	if ( ! f_nobackground ) { ws.startBackgroundThreads(); }

	/*
	 * All the actual tests go here.
	 */
	(new LinkTest()).run(ws);
	(new HTMLTest()).run(ws);
	(new EditTest()).run(ws);
	(new ParserTest()).run(ws);
	(new SpecialTest()).run(ws);
	(new UploadTest()).run(ws);
	(new SearchTest()).run(ws);
	if ( ! f_skipmath ) { (new MathTest()).run(ws); }

	/*
	 * Tests are all done. Clean up and report.
	 */
	if ( ! f_nobackground ) { ws.stopBackgroundThreads(); }
	info( "Finished Wikipedia Test Suite" );

	long elapsed_time = System.currentTimeMillis() - start_time;

	long t_hr = elapsed_time / 3600000;
	long t_min = (elapsed_time % 3600000) / 60000;
	double t_sec = (double)(elapsed_time % 60000) / 1000.0;

	StringBuffer sb = new StringBuffer(100);
	sb.append( "Total elapsed time: " ).append( t_hr ).append( " hr, " )
	  .append( t_min ).append( " min, " )
	  .append( threeDecimals( t_sec ) ).append( " sec." );
	info( sb.toString() );
}

}
