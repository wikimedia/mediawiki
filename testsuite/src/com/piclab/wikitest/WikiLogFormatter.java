
package com.piclab.wikitest;

import java.util.logging.*;

class WikiLogFormatter extends Formatter {

java.text.DateFormat m_df;

public WikiLogFormatter() {
	m_df = new java.text.SimpleDateFormat( "HH:mm:ss " );
}

public String format( LogRecord rec ) {
	StringBuffer sb = new StringBuffer( 200 );

	sb.append( m_df.format( new java.util.Date() ) );

	Level l = rec.getLevel();
	if ( l == Level.SEVERE ) {
		sb.append( "ERROR: " );
	} else if ( l == Level.WARNING ) {
		sb.append( " WARN: " );
	} else if ( l == Level.CONFIG ) {
		sb.append( " CONF: " );
	} else if ( l == Level.INFO || l == Level.FINE ||
	  l == Level.FINER || l == Level.FINEST ) {
		sb.append( " INFO: " );
	} else {
		sb.append( "       " );
	}
	sb.append( rec.getMessage() ).append( "\n" );

	return sb.toString();
}

}

