
/*
 * Test that basic navigation around the wiki with
 * internal links is working.
 */

package com.piclab.wikitest;

import com.meterware.httpunit.*;

public class LinkTest extends WikiTest {

public String testName() { return "Links"; }

protected int initTest() throws Exception {
	WebResponse wr = deletePage( "Talk:Poker" ); /* Will logout */
	return 0;
}

protected int runTest() throws Exception {
	int c = 0;

	if ( 0 != ( c = part1() ) ) { return fail( c ); }
	if ( 0 != ( c = part2() ) ) { return fail( c ); }
	if ( 0 != ( c = part3() ) ) { return fail( c ); }
	if ( 0 != ( c = part4() ) ) { return fail( c ); }
	if ( 0 != ( c = part5() ) ) { return fail( c ); }
	return 0;
}

private int part1() throws Exception {
	/*
	 * Check that we can click through from main page to games,
	 * card games, poker, world series.
	 */
	WebResponse wr = viewPage( "" ); /* Main page */
	WebLink l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Game" );
	if ( l == null ) { return 101; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Card" );
	if ( l == null ) { return 102; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Poker" );
	if ( l == null ) { return 103; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "World Series" );
	if ( l == null ) { return 104; }
	wr = l.click();

	return 0;
}

private int part2() throws Exception {
	/* 
	 * Poker page should have some standard links on it, and should
	 * _not_ have an upload link or user stat links on it because we
	 * aren't logged in.
	 */
	WebResponse wr = viewPage( "Poker" );
	WebLink l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Printable version" );
	if ( l == null ) { return 201; }
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Related changes" );
	if ( l == null ) { return 202; }

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Upload file" );
	if ( l != null ) { return 203; }
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "My watchlist" );
	if ( l != null ) { return 204; }
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "My contributions" );
	if ( l != null ) { return 205; }

	return 0;
}

private int part3() throws Exception {
	/*
	 * Talk:Poker was not preloaded, so we should be on an edit form
	 * when we click that link from the Poker page.  Add a comment,
	 * then check for some standard links on the new talk page and
	 * the resulting history page.
	 */
	WebResponse wr = viewPage( "Poker" );
	WebLink l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Discuss this page" );
	if ( l == null ) { return 301; }
	wr = l.click();

	WebForm editform = getFormByName( wr, "editform" );
    WebRequest req = editform.getRequest( "wpSave" );
    req.setParameter( "wpTextbox1", "Great article!" );
    wr = getResponse( req );

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "View article" );
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Older versions" );
	if ( l == null ) { return 302; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Current revision" );
	if ( l == null ) { return 303; }
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "View discussion" );
	if ( l == null ) { return 304; }
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "View article" );
	if ( l == null ) { return 305; }
	wr = l.click();

	return 0;
}

private int part4() throws Exception {
	/*
	 * Let's log in now and verify that things are changed.
	 */
	WebResponse wr = viewPage( "Poker" );
	WebLink l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Log in" );
	if ( l == null ) { return 401; }
	wr = l.click();

	wr = loginAs( "Fred", "Fred" );
	wr = viewPage( "Poker" );

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "My watchlist" );
	if ( l == null ) { return 402; }
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "My contributions" );
	if ( l == null ) { return 403; }
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "new messages" );
	if ( l != null ) { return 404; }
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Upload file" );
	if ( l == null ) { return 405; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "list of uploaded images" );
	if ( l == null ) { return 406; }
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "upload log" );
	if ( l == null ) { return 407; }

	return 0;
}

private int part5() throws Exception {
	/*
	 * Verify that the user page and user talk page are OK.
	 */
	WebResponse wr = viewPage( "" );
	WebLink l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Fred" );
	if ( l == null ) { return 501; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "User contributions" );
	if ( l == null ) { return 502; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Talk" );
	if ( l == null ) { return 503; }
	wr = l.click();

	/*
	 * Log out, clear cookies, edit talk page, then log back in and
	 * verify "new messages" link.
	 */
	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Log out" );
	if ( l == null ) { return 504; }
	wr = l.click();
	clearCookies();

	wr = editPage( "User talk:Fred" );
	WebForm editform = getFormByName( wr, "editform" );
    WebRequest req = editform.getRequest( "wpSave" );
    req.setParameter( "wpTextbox1", "Wake up!" );
    wr = getResponse( req );

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Main Page" );
	if ( l == null ) { return 505; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Log in" );
	if ( l == null ) { return 506; }
	wr = l.click();

	WebForm loginform = getFormByName( wr, "userlogin" );
	req = loginform.getRequest( "wpLoginattempt" );
	req.setParameter( "wpName", "Fred" );
	req.setParameter( "wpPassword", "Fred" );
	wr = getResponse( req );

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "new messages" );
	if ( l == null ) { return 507; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "Main Page" );
	if ( l == null ) { return 508; }
	wr = l.click();

	l = wr.getFirstMatchingLink( WebLink.MATCH_CONTAINED_TEXT, "new messages" );
	if ( l != null ) { return 509; }

	return 0;
}

public static void main( String[] params ) {
	(new LinkTest()).runSingle( params );
}

}
