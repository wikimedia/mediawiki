
/*
 * WikiFetchThread is a background thread that fetches
 * pages from the preload list until the suite is done.
 */

package com.piclab.wikitest;
import com.meterware.httpunit.*;

public class WikiFetchThread extends Thread {

private WebConversation m_conv;
private int m_totalfetches;
private long m_totaltime;
private volatile boolean m_running;


public WikiFetchThread() {
	m_conv = new WebConversation();
	m_totalfetches = 0;
	m_totaltime = 0;
}

public int getFetches() { return m_totalfetches; }
public long getTime() { return m_totaltime; }
public void requestStop() { m_running = false; }


public void run() {
	int index = 0;
	String url;
	double r;
	long start, end;

	m_running = true;
	while ( m_running ) {
		r = Math.random();
		if ( r < 0.1 ) {
			url = WikiTest.viewUrl( "" ); /* Main page */
		} else if ( r < 0.15 ) {
			url = WikiTest.viewUrl( "Special:Recentchanges" );
		} else {
			if ( ++index >= WikiSuite.preloadedPages.length ) { index = 0; }
			url = WikiTest.viewUrl( WikiSuite.preloadedPages[index] );
		}

		start = System.currentTimeMillis();
		try {
			WebResponse wr = m_conv.getResponse( url );
		} catch (Exception e) {
			WikiSuite.warning( "Error (" + e + ") fetching \"" + url + "\"" );
		}
		end = System.currentTimeMillis();

		WikiSuite.finer( "Fetched \"" + url + "\"" );
		++m_totalfetches;
		m_totaltime += ( end - start );

		try {
			Thread.sleep( 1000 );
		} catch( InterruptedException e ) {
			break;
		}
	}
	/*
	 * The main suite tells us to stop, but we wait until the
	 * current fetch is done. So we have the suite wait for us
	 * to actually stop before continuing with its final report,
	 * and we wake it up here.
	 */
	synchronized (this) { notify(); }
}

}
