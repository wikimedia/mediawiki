/*
 * Test functioning of various special pages. Does not bother with
 * some pages like Recentchanges and Userlogin that are adequately
 * exercised by other test, or with developer-only stuff.
 */

package com.piclab.wikitest;
import com.meterware.httpunit.*;
import java.util.regex.*;

public class SpecialTest extends WikiTest {

public String testName() { return "Special"; }

protected int initTest() throws Exception {
	WebResponse wr = deletePage( "Newly created test page" );
	wr = deletePage( "Nonsense" );
	wr = deletePage( "Religion" );

    String text = WikiSuite.loadText( "data/Religion.txt" );
	wr = replacePage( "Religion", text );
	return 0;
}

protected int runTest() throws Exception {
	int c = 0;

	if ( 0 != ( c = part1() ) ) { return fail( c ); }
	if ( 0 != ( c = part2() ) ) { return fail( c ); }
	if ( 0 != ( c = part3() ) ) { return fail( c ); }
	if ( 0 != ( c = part4() ) ) { return fail( c ); }
	if ( 0 != ( c = part5() ) ) { return fail( c ); }
	if ( 0 != ( c = part6() ) ) { return fail( c ); }
	if ( 0 != ( c = part7() ) ) { return fail( c ); }
	if ( 0 != ( c = part8() ) ) { return fail( c ); }
	if ( 0 != ( c = part9() ) ) { return fail( c ); }
	if ( 0 != ( c = part10() ) ) { return fail( c ); }
	if ( 0 != ( c = part11() ) ) { return fail( c ); }
	if ( 0 != ( c = part12() ) ) { return fail( c ); }
	if ( 0 != ( c = part13() ) ) { return fail( c ); }
	if ( 0 != ( c = part14() ) ) { return fail( c ); }
	if ( 0 != ( c = part15() ) ) { return fail( c ); }
	if ( 0 != ( c = part16() ) ) { return fail( c ); }
	if ( 0 != ( c = part17() ) ) { return fail( c ); }
	if ( 0 != ( c = part18() ) ) { return fail( c ); }
	if ( 0 != ( c = part19() ) ) { return fail( c ); }
	if ( 0 != ( c = part20() ) ) { return fail( c ); }
	return 0;
}

private String[] listHeadings = {
  "Showing below.*results starting with",
  "View \\(previous[^(]*\\(<a\\s[^>]*>next",
  "\\(<a [^>]*>20</a> | <a [^>]*>50</a> | <a [^>]*>100</a>"
};

private int part1() throws Exception {
	/*
	 * This one is in the process of changing.
	 */
	WebResponse wr = viewPage( "Special:Allpages" );
	return 0;
}

private int part2() throws Exception {
	WebResponse wr = viewPage( "Special:Booksources", "isbn=0123456789" );

	WebLink l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "AddALL" );
	if ( l == null ) return 201;
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "PriceSCAN" );
	if ( l == null ) return 202;
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Barnes" );
	if ( l == null ) return 203;
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Amazon" );
	if ( l == null ) return 204;

	return 0;
}

private int part3() throws Exception {
	WebResponse wr = loginAs( "Fred", "Fred" );
	wr = editPage( "Painting" );

	WebForm editform = getFormByName( wr, "editform" );
	WebRequest req = editform.getRequest( "wpSave" );

	String old = req.getParameter( "wpTextbox1" );
	req.setParameter( "wpTextbox1", old + "\n" + "Fred's edit." );
	req.setParameter( "wpSummary", "Wikitest addition" );
	wr = getResponse( req );

	wr = viewPage( "Special:Contributions", "target=Fred" );
	String text = getArticle( wr );

	String[] pats = {
	  "<p\\s[^>]*subtitle[^>]*>\\s*For\\s*<a\\s[^>]*User:Fred[^>]*>\\s*Fred",
	  "<ul>\\s*<li>[^<]*<a\\s[^>]*>Painting</a>\\s*<em>\\s*\\(Wikitest"
	};

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, pats ) ) ) {
		return 300 + ret;
	}
	if ( 0 != ( ret = checkGoodPatterns( text, listHeadings ) ) ) {
		return 310 + ret;
	}
	wr = logout();
	return 0;
}

private int part4() throws Exception {
	WebResponse wr = loginAs( "Fred", "Fred" );
	WebRequest req = openPrefs();
	req.setParameter( "wpEmail", "fred@nowhere.invalid" );
	wr = getResponse( req );

	wr = loginAs( "Barney", "Barney" );
	req = openPrefs();
	req.setParameter( "wpEmail", "barney@nowhere.invalid" );
	wr = getResponse( req );

	wr = viewPage( "Special:Emailuser", "target=Fred" );
	String text = getArticle( wr );
	WebForm emailform = getFormByName( wr, "emailuser" );

	if ( ! emailform.hasParameterNamed( "wpSubject" ) ) {
		return 401;
	}
	/*
	 * Actual addresses should not appear on form anywhere
	 */
	String[] badpats = {
		"fred@nowhere.invalid", "barney@nowhere.invalid"
	};
	int ret = 0;
	if ( 0 != ( ret = checkBadPatterns( text, badpats ) ) ) {
		return 401 + ret;
	}
	wr = logout();
	return 0;
}

private int part5() throws Exception {
	WebResponse wr = viewPage( "Special:Listusers" );
	String text = getArticle( wr );

	String[] pats = {
	  "<ol [^>]*start[^>]*>\\s*<li>\\s*<a [^>]*User:([^ ]+)[^>]*>\\s*\\1"
	};

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, pats ) ) ) {
		return 500 + ret;
	}
	if ( 0 != ( ret = checkGoodPatterns( text, listHeadings ) ) ) {
		return 510 + ret;
	}
	return 0;
}

private int part6() throws Exception {
	WebResponse wr = viewPage( "Special:Lonelypages" );
	String text = getArticle( wr );

	String[] pats = {
	  "<ol [^>]*start[^>]*>\\s*<li>\\s*<a [^>]*\\?title=([^ ]+)[^>]*>\\s*\\1"
	};

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, pats ) ) ) {
		return 600 + ret;
	}
	if ( 0 != ( ret = checkGoodPatterns( text, listHeadings ) ) ) {
		return 610 + ret;
	}
	return 0;
}

private int part7() throws Exception {
	WebResponse wr = viewPage( "Special:Longpages" );
	String text = getArticle( wr );

	String[] pats = {
	  "<ol [^>]*start[^>]*>\\s*<li>\\s*<a [^>]*\\?title=([^\"])[^\"]*\"[^>]*>" +
	    "\\s*\\1[^<]*</a>\\s*\\(\\d+\\s+bytes\\)"
	};

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, pats ) ) ) {
		return 600 + ret;
	}
	if ( 0 != ( ret = checkGoodPatterns( text, listHeadings ) ) ) {
		return 610 + ret;
	}
	return 0;
}

private int part8() throws Exception {
	WebResponse wr = loginAs( "Fred", "Fred" );
	wr = viewPage( "Religion" );
	String text = getArticle( wr );

	if ( text.indexOf( "<strong>religion</strong>" ) < 0 ) {
		return 801;
	}
	wr = viewPage( "Special:Movepage", "target=Religion" );
	text = getArticle( wr );
	if ( text.indexOf( "WARNING" ) < 0 ) { return 802; }

	WebForm moveform = getFormByName( wr, "movepage" );
	WebRequest req = moveform.getRequest( "wpMove" );
	req.setParameter( "wpNewTitle", "Nonsense" );
	wr = getResponse( req );

	text = getArticle( wr );
	if ( text.indexOf( ">Religion<" ) < 0 ||
	  text.indexOf( "moved to" ) < 0 ||
	  text.indexOf( ">Nonsense<" ) < 0 ) {
	  	return 803;
	}
	wr = viewPage( "Nonsense" );
	text = getArticle( wr );
	if ( text.indexOf( "<strong>religion</strong>" ) < 0 ) {
		return 804;
	}
	wr = viewPage( "Religion" );
	text = getArticle( wr );
	if ( text.indexOf( "<strong>religion</strong>" ) < 0 ||
	  text.indexOf( "(Redirected from" ) < 0 ) {
		return 805;
	}
	wr = viewPage( "Religion", "action=edit&redirect=no" );
	text = getArticle( wr );
	if ( text.indexOf( "#REDIRECT [[Nonsense]]" ) < 0 ) {
		return 806;
	}
	wr = logout();
	return 0;
}

private int part9() throws Exception {
	/*
	 * Not yet implemented
	 *
	WebResponse wr = viewPage( "Special:Neglectedpages" );
	 */

	return 0;
}

private int part10() throws Exception {
	WebResponse wr = loginAs( "Barney", "Barney" );
	wr = addText( "Newly created test page", "New stuff..." );

	wr = viewPage( "Special:Newpages" );
	String text = getArticle( wr );

	String[] pats = {
	  "<ol [^>]*start[^>]*>\\s*<li>\\s*\\d\\d:\\d\\d[^<]*<a [^>]*>Newly" +
	    "[^<]*</a> \\. \\. <a [^>]*>Barney</a>"
	};

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, pats ) ) ) {
		return 1000 + ret;
	}
	if ( 0 != ( ret = checkGoodPatterns( text, listHeadings ) ) ) {
		return 1010 + ret;
	}
	return 0;
}

private int part11() throws Exception {
	WebResponse wr = viewPage( "Special:Popularpages" );
	String text = getArticle( wr );

	String[] pats = {
	  "<ol [^>]*start[^>]*>\\s*<li>\\s*<a [^>]*\\?title=([^\"])[^\"]*\"[^>]*>" +
	    "\\s*\\1[^<]*</a>\\s*\\(\\d+\\s+views\\)"
	};

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, pats ) ) ) {
		return 1100 + ret;
	}
	if ( 0 != ( ret = checkGoodPatterns( text, listHeadings ) ) ) {
		return 1110 + ret;
	}
	return 0;
}

private int part12() throws Exception {
	WebResponse wr = viewPage( "Special:Randompage" );
	return 0;
}

private int part13() throws Exception {
	/* WebResponse wr = viewPage( "Special:Recentchangeslinked" ); */
	return 0;
}

private int part14() throws Exception {
	WebResponse wr = viewPage( "Special:Shortpages" );
	String text = getArticle( wr );

	String[] pats = {
	  "<ol [^>]*start[^>]*>\\s*<li>\\s*<a [^>]*\\?title=([^\"])[^\"]*\"[^>]*>" +
	    "\\s*\\1[^<]*</a>\\s*\\(\\d+\\s+bytes\\)"
	};

	int ret = 0;
	if ( 0 != ( ret = checkGoodPatterns( text, pats ) ) ) {
		return 1400 + ret;
	}
	if ( 0 != ( ret = checkGoodPatterns( text, listHeadings ) ) ) {
		return 1410 + ret;
	}
	return 0;
}

private int part15() throws Exception {
	viewPage( "Special:Specialpages" );
	return 0;
}

private int part16() throws Exception {
	WebResponse wr = viewPage( "Special:Statistics" );
	String text = getArticle( wr );
	return 0;
}

private int part17() throws Exception {
	WebResponse wr = viewPage( "Special:Unusedimages" );
	String text = getArticle( wr );
	return 0;
}

private int part18() throws Exception {
	WebResponse wr = viewPage( "Special:Wantedpages" );
	String text = getArticle( wr );
	return 0;
}

private int part19() throws Exception {
	/* WebResponse wr = viewPage( "Special:Watchlist" ); */
	return 0;
}

private int part20() throws Exception {
	/* WebResponse wr = viewPage( "Special:Whatlinkshere" ); */
	return 0;
}

public static void main( String[] params ) {
	(new SpecialTest()).runSingle( params );
}

}
