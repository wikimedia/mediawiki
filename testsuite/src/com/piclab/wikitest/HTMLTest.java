
/*
 * View pages with various skins and make sure they're basically
 * valid HTML structured the way we expect.  For now we're just
 * using regexes, which should be fine for the sample pages.  They
 * would probably fail on pages about HTML markup and such, though.
 * Eventualy, we should be scanning the DOM for these tests.
 */

package com.piclab.wikitest;

import com.meterware.httpunit.*;
import java.util.regex.*;
import java.io.*;
import org.w3c.dom.*;

public class HTMLTest extends WikiTest {

/* Regex patterns to look for on every page; "good" patterns should
 * be found, "bad" patterns should be absent.
 */

private String[] m_goodpats = {
	"\\A\\s*<!doctype html", "<meta\\s+[^>]*name\\s*=\\s*.robots",
	"<head[^>]*>.*<title[^>]*>.*</title>.*</head>\\s*<body",
	"<link\\s+[^>]*rel\\s*=\\s*.stylesheet", "<h1\\s+[^>]*class\\s*=.pagetitle",
	"<form\\s+[^>]*id\\s*=\\s*.search", 
	"<div\\s+[^>]*id\\s*=.content.*<div\\s+[^>]*id\\s*=.article",
};
private Pattern[] m_cgoodpats;

private String[] m_badpats = {
	"<[^>]*onclick\\s*=", "<applet", "<object", "<body.*<script.*</body"
};
private Pattern[] m_cbadpats;

/* TODO: figure out some way to check for unbalanced <ul>, etc. */

public String testName() { return "HTML"; }


protected int initTest() throws Exception {
	logout();
	/*
	 * Pre-compile the regexes.
	 */
	m_cgoodpats = new Pattern[m_goodpats.length];
	for (int i = 0; i < m_goodpats.length; ++i) {
		m_cgoodpats[i] = Pattern.compile( m_goodpats[i],
		  Pattern.CASE_INSENSITIVE | Pattern.DOTALL );
	}
	m_cbadpats = new Pattern[m_badpats.length];
	for (int i = 0; i < m_badpats.length; ++i) {
		m_cbadpats[i] = Pattern.compile( m_badpats[i],
		  Pattern.CASE_INSENSITIVE | Pattern.DOTALL );
	}
	return 0;
}

protected int runTest() throws Exception {
	int c = 0;

	if ( 0 != ( c = part1() ) ) { return fail(c); }
	if ( 0 != ( c = part2() ) ) { return fail(c); }
	return 0;
}

private int part1() throws Exception {
	WebResponse wr = loginAs( "Fred", "Fred" );
	Document doc = wr.getDOM();
	if ( ! matchesAll( wr.getText() ) ) { return 101; }

	WebRequest req = openPrefs();
	req.removeParameter( "wpOpnumberheadings" );
	req.setParameter( "wpOphighlightbroken", "1" );
	wr = getResponse( req );
	WikiSuite.fine( "Standard settings" );

	int c = 0;
	if ( 0 != ( c = part1inner() ) ) { return 110 + c; }

	req = openPrefs();
	req.setParameter( "wpOpnumberheadings", "1" );
	wr = getResponse( req );
	WikiSuite.fine( "Numbered headings" );

	if ( 0 != ( c = part1inner() ) ) { return 120 + c; }

	req = openPrefs();
	req.setParameter( "wpOphighlightbroken", "1" );
	wr = getResponse( req );
	WikiSuite.fine( "Question-mark links" );

	if ( 0 != ( c = part1inner() ) ) { return 130 + c; }
	return 0;
}

private int part1inner() throws Exception {
	WebResponse wr = viewPage( "" );
	/*
	 * Will throw exception if not parseable:
	 */
	Document doc = wr.getDOM();
	if ( ! matchesAll( wr.getText() ) ) { return 1; }

	wr = viewPage( "Opera" );
	doc = wr.getDOM();
	if ( ! matchesAll( wr.getText() ) ) { return 2; }

	wr = viewPage( "User:Fred" );
	doc = wr.getDOM();
	if ( ! matchesAll( wr.getText() ) ) { return 3; }

	wr = viewPage( "Special:Recentchanges" );
	doc = wr.getDOM();
	if ( ! matchesAll( wr.getText() ) ) { return 4; }

	wr = viewPage( "Talk:Poker" );
	doc = wr.getDOM();
	if ( ! matchesAll( wr.getText() ) ) { return 5; }

	wr = viewPage( "Wikipedia:Upload_log" );
	doc = wr.getDOM();
	if ( ! matchesAll( wr.getText() ) ) { return 6; }

	return 0;
}

private int part2() throws Exception {
	WebResponse wr = loginAs( "Barney", "Barney" );
	Document doc = wr.getDOM();
	if ( ! matchesAll( wr.getText() ) ) { return 201; }

	WebRequest req = openPrefs();
	req.removeParameter( "wpOpnumberheadings" );
	req.setParameter( "wpOphighlightbroken", "1" );
	wr = getResponse( req );

	for (int q = 0; q < 4; ++q) {
		req = openPrefs();
		req.setParameter( "wpQuickbar", String.valueOf( q ) );
		wr = getResponse( req );

		doc = wr.getDOM();
		if ( ! matchesAll( wr.getText() ) ) { return 200 + 10 * q; }
		WikiSuite.finer( "Set quickbar to " + q );

		for (int s = 0; s < 3; ++s) {
			req = openPrefs();
			req.setParameter( "wpSkin", String.valueOf( s ) );
			wr = getResponse( req );
			WikiSuite.finer( "Set skin to " + s );

			double r = Math.random();
			if ( r < .5 ) {
				wr = viewPage( WikiSuite.preloadedPages[
				  (int)(r * 100.0)] );
			} else if ( r < .6 ) {
				wr = viewPage( "User:Fred" );
			} else if ( r < .7 ) {
				wr = viewPage( "Special:Recentchanges" );
			} else if ( r < .8 ) {
				wr = editPage( "Talk:Sport" );
			} else if ( r < .9 ) {
				wr = editPage( "Wikipedia:Upload_log" );
			} else {
				wr = viewPage( "" );
			}
			doc = wr.getDOM();
			if ( ! matchesAll( wr.getText() ) ) { return 201 + 10 * q + s; }
		}
	}
	return 0;
}

private boolean matchesAll( String text ) {
	if ( m_cgoodpats[0] == null ) {
		WikiSuite.error( "Patterns not compiled." );
		return false;
	}
	for (int i = 0; i < m_goodpats.length; ++i) {
		Matcher m = m_cgoodpats[i].matcher( text );
		if ( ! m.find() ) {
			WikiSuite.error( "Failed to match pattern \"" + m_goodpats[i] + "\"" );
			return false;
		}
	}
	for (int i = 0; i < m_badpats.length; ++i) {
		Matcher m = m_cbadpats[i].matcher( text );
		if ( m.find() ) {
			WikiSuite.error( "Matched pattern \"" + m_badpats[i] + "\"" );
			return false;
		}
	}
	return true;
}

public static void main( String[] params ) {
	(new HTMLTest()).runSingle( params );
}

}
