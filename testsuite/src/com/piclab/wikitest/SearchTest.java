
/*
 * Test basic searching functions.
 */

package com.piclab.wikitest;
import com.meterware.httpunit.*;

public class SearchTest extends WikiTest {

public String testName() { return "Search"; }

protected int initTest() throws Exception {
	logout();
	return 0;
}

protected int runTest() throws Exception {
	int c = 0;

	if ( 0 != ( c = part1() ) ) { return fail( c ); }
	if ( 0 != ( c = part2() ) ) { return fail( c ); }
	if ( 0 != ( c = part3() ) ) { return fail( c ); }
	return 0;
}

private int part1() throws Exception {
	String[] goodpats = {
	  "<h2>Article title matches</h2>\\s*<ol [^>]*>",
	  "<li><a [^>]+Cooking[^>]+>Cooking</a> \\(\\d+ bytes\\)\\s*<br>\\s*" +
		"<small>\\d+:",
	  "<h2>Article text matches</h2>\\s*<ol [^>]*>\\s*<li><a " +
		"[^>]*>[^<]+</a> \\(\\d+ bytes\\)\\s*<br>\\s*<small>\\d+:",
	  "<font [^>]*color\\s*=\\s*.red",
	  "<form [^>]*id\\s*=\\s*.?powersearch",
	  "<input [^>]*name\\s*=\\s*.?ns0[^>]*checked[^>]*>\\s*\\(",
	  "<input [^>]*type\\s*=\\s*.?checkbox",
	  "<input [^>]*name\\s*=\\s*.?redirs[^>]*checked[^>]*>",
	  "<input [^>]*name\\s*=\\s*.?searchx"
	};
	String[] badpats = {
	  "<input [^>]*name\\s*=\\s*.ns1[^>]*checked[^>]*>"
	};

	WebResponse wr = searchFor( "cooking" );
	String text = getArticle( wr );

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, goodpats ) ) ) {
		return 100 + ret;
	}
	if ( 0 != ( ret = checkBadPatterns( text, badpats ) ) ) {
		return 110 + ret;
	}

	String[] goodpats2 = {
	  "<h2>No article title matches</h2>",
	  "<h2>No article text matches</h2>",
	  "<strong>\\s*Note",
	  "<form [^>]*id\\s*=\\s*.?powersearch",
	  "<input [^>]*name\\s*=\\s*.?ns0[^>]*checked[^>]*>\\s*\\("
	};
	String[] badpats2 = {
	  "<font [^>]*color\\s*=\\s*.red"
	};
	wr = searchFor( "oyaABiJxTWMISmfE" );
	text = getArticle( wr );

	if ( 0 != ( ret = checkGoodPatterns( text, goodpats2 ) ) ) {
		return 120 + ret;
	}
	if ( 0 != ( ret = checkBadPatterns( text, badpats2 ) ) ) {
		return 130 + ret;
	}

	wr = searchFor( "cooking OR sewing" );
	text = getArticle( wr );

	if ( 0 != ( ret = checkGoodPatterns( text, goodpats ) ) ) {
		return 140 + ret;
	}
	if ( 0 != ( ret = checkBadPatterns( text, badpats ) ) ) {
		return 150 + ret;
	}

	/*
	 * If boolean searches are disabled, the following will
	 * fail, so comment it out.
	 */
	wr = searchFor( "cooking AND oyaABiJxTWMISmfE" );
	text = getArticle( wr );

	if ( 0 != ( ret = checkGoodPatterns( text, goodpats2 ) ) ) {
		return 160 + ret;
	}
	if ( 0 != ( ret = checkBadPatterns( text, badpats2 ) ) ) {
		return 170 + ret;
	}

	wr = searchFor( "cooking OR oyaABiJxTWMISmfE" );
	text = getArticle( wr );

	if ( 0 != ( ret = checkGoodPatterns( text, goodpats ) ) ) {
		return 180 + ret;
	}
	if ( 0 != ( ret = checkBadPatterns( text, badpats ) ) ) {
		return 190 + ret;
	}
	return 0;
}

/*
 * Test "powersearch" in other namespaces.
 */

private int part2() throws Exception {
	String[] goodpats = {
	  "<h2>No article title matches</h2>",
	  "<form [^>]*id\\s*=\\s*.powersearch",
	  "<input [^>]*name\\s*=\\s*.ns0[^>]*checked[^>]*>\\s*\\(",
	  "<input [^>]*value\\s*=\\s*.?Barney",
	  "<input [^>]*name\\s*=\\s*.searchx"
	};
	String[] badpats = {
	  "<input [^>]*name\\s*=\\s*.ns1[^>]*checked[^>]*>"
	};

	WebResponse wr = addText( "User:Barney",
	  "Some text added just to make sure page exists..." );
	wr = searchFor( "Barney" );
	String text = getArticle( wr );

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, goodpats ) ) ) {
		return 200 + ret;
	}
	if ( 0 != ( ret = checkBadPatterns( text, badpats ) ) ) {
		return 210 + ret;
	}

	WebForm psform = getFormByName( wr, "powersearch" );
	WebRequest req = psform.getRequest( "searchx" );

	req.setParameter( "ns2", "1" );
	wr = getResponse( req );
	text = getArticle( wr );

	String[] goodpats2 = {
	  "<h2>Article title matches</h2>",
	  "<form [^>]*id\\s*=\\s*.?powersearch",
	  "<input [^>]*name\\s*=\\s*.?ns0[^>]*checked[^>]*>\\s*\\(",
	  "<input [^>]*name\\s*=\\s*.?ns2[^>]*checked[^>]*>",
	  "<input [^>]*value\\s*=\\s*.?Barney",
	  "<input [^>]*name\\s*=\\s*.?searchx"
	};
	String[] badpats2 = {
	  "<input [^>]*name\\s*=\\s*.ns1[^>]*checked[^>]*>"
	};

	if ( 0 != ( ret = checkGoodPatterns( text, goodpats2 ) ) ) {
		return 220 + ret;
	}
	if ( 0 != ( ret = checkBadPatterns( text, badpats2 ) ) ) {
		return 230 + ret;
	}
	return 0;
}

/*
 * Test "Go" search.
 */

private int part3() throws Exception {
	String[] pagepat = { "<h1\\s[^>]*>\\s*Geography\\s*</h1>" };
	String[] srpat = { "<h1\\s[^>]*>\\s*Search results\\s*</h1>" };

	WebResponse wr = searchFor( "Geography", "go=Go" );
	String text = getArticle( wr );

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, pagepat ) ) ) {
		return 300 + ret;
	}
	if ( 0 != ( ret = checkBadPatterns( text, srpat ) ) ) {
		return 310 + ret;
	}

	wr = searchFor( "oyaABiJxTWMISmfE", "go=Go" );
	text = getArticle( wr );

	if ( 0 != ( ret = checkGoodPatterns( text, srpat ) ) ) {
		return 320 + ret;
	}
	if ( 0 != ( ret = checkBadPatterns( text, pagepat ) ) ) {
		return 330 + ret;
	}
	return 0;
}

public static void main( String[] params ) {
	(new SearchTest()).runSingle( params );
}

}
