/*
 * Test functioning of texvc math functions.
 */

package com.piclab.wikitest;
import com.meterware.httpunit.*;

public class MathTest extends WikiTest {

public String testName() { return "Math"; }

protected int initTest() throws Exception {
	logout();
	return 0;
}

protected int runTest() throws Exception {
	int c = 0;

	if ( 0 != ( c = part1() ) ) { return fail( c ); }
	return 0;
}

private int part1() throws Exception {
	String[] goodpats = {
	  "\\(1\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(2\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(3\\)\\s+&phi;\\s+\\+",
	  "\\(4\\)\\s+&phi;\\s*<sup>\\s*2",
	  "\\(5\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(6\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+\\\\approx"
	};

	WebResponse wr = viewPage( "Equations" );
	String text = getArticle( wr );

	int ret;
	if ( 0 != ( ret = checkGoodPatterns( text, goodpats ) ) ) {
		return 100 + ret;
	}

	wr = loginAs( "Barney", "Barney" );
	WebRequest req = openPrefs();
	req.setParameter( "wpMath", "0" );
	wr = getResponse( req );

	String[] goodpats0 = {
	  "\\(1\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(2\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(3\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+\\+",
	  "\\(4\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s*\\^\\s*2",
	  "\\(5\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(6\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+\\\\approx"
	};

	wr = viewPage( "Equations" );
	text = getArticle( wr );
	if ( 0 != ( ret = checkGoodPatterns( text, goodpats0 ) ) ) {
		return 110 + ret;
	}

	wr = loginAs( "Barney", "Barney" );
	req = openPrefs();
	req.setParameter( "wpMath", "2" );
	wr = getResponse( req );

	String[] goodpats2 = {
	  "\\(1\\)\\s+<table",
	  "\\(2\\)\\s+<table",
	  "\\(3\\)\\s+&phi;\\s+\\+",
	  "\\(4\\)\\s+&phi;\\s*<sup>\\s*2",
	  "\\(5\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(6\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+\\\\approx"
	};

	wr = viewPage( "Equations" );
	text = getArticle( wr );
	if ( 0 != ( ret = checkGoodPatterns( text, goodpats2 ) ) ) {
		return 120 + ret;
	}

	wr = loginAs( "Barney", "Barney" );
	req = openPrefs();
	req.setParameter( "wpMath", "3" );
	wr = getResponse( req );

	String[] goodpats3 = {
	  "\\(1\\)\\s+\\$\\s+\\\\phi\\s+=\\s+\\\\frac",
	  "\\(2\\)\\s+\\$\\s+\\\\phi\\s+=\\s+\\\\frac",
	  "\\(3\\)\\s+\\$\\s+\\\\phi\\s+\\+\\s+\\\\phi\\s*\\^\\s*2",
	  "\\(4\\)\\s+\\$\\s+\\\\phi\\s*\\^\\s*2\\s+\\+\\s+\\\\phi",
	  "\\(5\\)\\s+\\$\\s+\\\\phi\\s+=\\s+\\\\frac",
	  "\\(6\\)\\s+\\$\\s+\\\\phi\\s+\\\\approx"
	};

	wr = viewPage( "Equations" );
	text = getArticle( wr );
	if ( 0 != ( ret = checkGoodPatterns( text, goodpats3 ) ) ) {
		return 130 + ret;
	}

	wr = loginAs( "Barney", "Barney" );
	req = openPrefs();
	req.setParameter( "wpMath", "4" );
	wr = getResponse( req );

	String[] goodpats4 = {
	  "\\(1\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(2\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(3\\)\\s+&phi;\\s+\\+",
	  "\\(4\\)\\s+&phi;\\s*<sup>\\s*2",
	  "\\(5\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+=\\s+\\\\frac",
	  "\\(6\\)\\s+<img\\s[^>]*\\salt\\s*=\\s*.?\\\\phi\\s+\\\\approx"
	};

	wr = viewPage( "Equations" );
	text = getArticle( wr );
	if ( 0 != ( ret = checkGoodPatterns( text, goodpats4 ) ) ) {
		return 140 + ret;
	}
	return 0;
}

public static void main( String[] params ) {
	(new MathTest()).runSingle( params );
}

}
