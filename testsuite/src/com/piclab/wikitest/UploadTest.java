
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
	return 0;
}

private int part2() throws Exception {
	return 0;
}

public static void main( String[] params ) {
	(new UploadTest()).runSingle( params );
}

}
