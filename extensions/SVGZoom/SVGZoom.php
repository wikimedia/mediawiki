<?php
/**
 * SVGZoom extension
 *
 * @file
 * @ingroup Extensions
 *
 * This file contains the main include file for the SVGZoom extension of MediaWiki.
 *
 * Usage: Add the following line to your LocalSettings.php file:
 * require_once( "$IP/extensions/SVGZoom/SVGZoom" );
 *
 * @author Trevor Parscal <tparscal@wikimedia.org>, Brad Neuberg <bradneuberg@google.com>
 * @license ?
 * @version 0.1.0
 */

/* Configuration */

// This needs to be updated before deployments
$wgSVGZoomScriptVersion = 1;

/* Setup */

// Sets Credits
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'SVGZoom',
	'author' => array( 'Trevor Parscal', 'Brad Neuberg' ),
	'version' => '0.1.0',
	'url' => 'http://www.mediawiki.org/wiki/Extension:SVGZoom',
	'descriptionmsg' => 'svgzoom-desc',
);

// Adds Autoload Classes
$wgAutoloadClasses['SVGZoomHooks'] =
	dirname( __FILE__ ) . "/SVGZoom.hooks.php";

// Adds Internationalized Messages
$wgExtensionMessagesFiles['SVGZoom'] =
	dirname( __FILE__ ) . "/SVGZoom.i18n.php";

// Registers Hooks
$wgHooks['BeforePageDisplay'][] = 'SVGZoomHooks::addResources';