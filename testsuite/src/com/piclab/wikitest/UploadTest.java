
/*
 * Test image uploading and linking.
 */

package com.piclab.wikitest;
import com.meterware.httpunit.*;

public class UploadTest extends WikiTest {

public String testName() { return "Uploads"; }

protected int initTest() throws Exception {
	logout();
	return 0;
}

protected int runTest() throws Exception {
	int c = 0;

	if ( 0 != ( c = part1() ) ) { return fail( c ); }
	if ( 0 != ( c = part2() ) ) { return fail( c ); }
	return 0;
}

private int part1() throws Exception {
	WebResponse wr = getResponse( viewUrl( "Special:Upload" ) );
	String text = getArticle( wr );

	String[] goodpats = { "<h1[^>]*>Not logged in</h1>" };
	int c = 0;

	if ( 0 != ( c = checkGoodPatterns( text, goodpats ) ) ) {
		return 100 + c;
	}
	wr = loginAs( "Fred", "Fred" );
	wr = viewPage( "Special:Upload" );
	text = getArticle( wr );

	String[] goodpats2 = {
		"<h1[^>]*>Upload file</h1>", ">image use policy<", ">upload log<"
	};
	if ( 0 != ( c = checkGoodPatterns( text, goodpats2 ) ) ) {
		return 110 + c;
	}

	WebForm wf = getFormByName( wr, "upload" );
	WebRequest req = wf.getRequest( "wpUpload" );

	req.selectFile( "wpUploadFile", new java.io.File( "./data/startrek.png" ) );
	req.setParameter( "wpUploadDescription", "Upload test" );

	wr = getResponse( req );
	text = getArticle( wr );

	String[] goodpats3 = {
		"<h1[^>]*>Upload error</h1>", "You must affirm"
	};
	if ( 0 != ( c = checkGoodPatterns( text, goodpats2 ) ) ) {
		return 120 + c;
	}

	wr = viewPage( "Special:Upload" );
	text = getArticle( wr );

	wf = getFormByName( wr, "upload" );
	req = wf.getRequest( "wpUpload" );
	req.selectFile( "wpUploadFile", new java.io.File( "./data/startrek.png" ) );
	req.setParameter( "wpUploadDescription", "Upload test" );
	req.setParameter( "wpUploadAffirm", "1" );

	wr = getResponse( req );
	text = getArticle( wr );

	String[] goodpats4 = {
		"uploaded successfully", "description page"
	};
	if ( 0 != ( c = checkGoodPatterns( text, goodpats4 ) ) ) {
		return 130 + c;
	}

	return 0;
}

private int part2() throws Exception {
	return 0;
}

public static void main( String[] params ) {
	(new UploadTest()).runSingle( params );
}

}
