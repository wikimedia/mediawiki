/*
 * This exception is thrown on any general test failure.
 * It is usually fatal.
 */

package com.piclab.wikitest;

public class WikiSuiteFailureException
extends Exception {

public WikiSuiteFailureException() {
	super();
}

public WikiSuiteFailureException(Throwable t) {
	super(t);
}

public WikiSuiteFailureException(String m) {
	super(m);
}

public WikiSuiteFailureException(String m, Throwable t) {
	super(m, t);
}

}

