<?php
/** Georgian (ქართული)
  *
  * @package MediaWiki
  * @subpackage Language
  */
$namespaceNames = array(
	NS_MEDIA            => 'მედია',
	NS_SPECIAL          => 'სპეციალური',
	NS_MAIN             => '',
	NS_TALK             => 'განხილვა',
	NS_USER             => 'მომხმარებელი',
	NS_USER_TALK        => 'მომხმარებელი_განხილვა',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_განხილვა',
	NS_IMAGE            => 'სურათი',
	NS_IMAGE_TALK       => 'სურათი_განხილვა',
	NS_MEDIAWIKI        => 'მედიავიკი',
	NS_MEDIAWIKI_TALK   => 'მედიავიკი_განხილვა',
	NS_TEMPLATE         => 'თარგი',
	NS_TEMPLATE_TALK    => 'თარგი_განხილვა',
	NS_HELP             => 'დახმარება',
	NS_HELP_TALK        => 'დახმარება_განხილვა',
	NS_CATEGORY         => 'კატეგორია',
	NS_CATEGORY_TALK    => 'კატეგორია_განხილვა'
);

$linkPrefixExtension = true;

$linkTrail = '/^([a-zაბგდევზთიკლმნოპჟრსტუფქღყშჩცძწჭხჯჰ“»]+)(.*)$/sDu';

$messages = array(

# User preference toggles

# Dates
'sunday'	=> 'კვირა',
'monday'	=> 'ორშაბათი',
'tuesday'	=> 'სამშაბათი',
'wednesday'	=> 'ოთხშაბათი',
'thursday'	=> 'ხუთშაბათი',
'friday'	=> 'პარასკევი',
'saturday'	=> 'შაბათი',
'sun'		=> 'კვი',
'mon'		=> 'ორშ',
'tue'		=> 'სამ',
'wed'		=> 'ოთხ',
'thu'		=> 'ხუთ',
'fri'		=> 'პარ',
'sat'		=> 'შაბ',
'january'	=> 'იანვარი',
'february'	=> 'თებერვალი',
'march'		=> 'მარტი',
'april'		=> 'აპრილი',
'may_long'	=> 'მაისი',
'june'		=> 'ივნისი',
'july'		=> 'ივლისი',
'august'	=> 'აგვისტო',
'september'	=> 'სექტემბერი',
'october'	=> 'ოქტომბერი',
'november'	=> 'ნოემბერი',
'december'	=> 'დეკემბერი',
'january-gen'	=> 'იანვრის',
'february-gen'	=> 'თებერვლის',
'march-gen'	=> 'მარტის',
'april-gen'	=> 'აპრილის',
'may-gen'	=> 'მაისის',
'june-gen'	=> 'ივნისის',
'july-gen'	=> 'ივლისის',
'august-gen'	=> 'აგვისტოს',
'september-gen'	=> 'სექტემბრის',
'october-gen'	=> 'ოქტომბრის',
'november-gen'	=> 'ნოემბრის',
'december-gen'	=> 'დეკემბრის',
'jan'		=> 'იან',
'feb'		=> 'თებ',
'mar'		=> 'მარ',
'apr'		=> 'აპრ',
'may'		=> 'მაი',
'jun'		=> 'ივნ',
'jul'		=> 'ივლ',
'aug'		=> 'აგვ',
'sep'		=> 'სექ',
'oct'		=> 'ოქტ',
'nov'		=> 'ნოე',
'dec'		=> 'დეკ',

# Bits of text used by many pages:
'categories' => '{{PLURAL:$1|კატეგორია|კატეგორიები}}',
'category_header' => 'სტატიები კატეგორიაში "$1"',
'subcategories' => 'ქვეკატეგორიები',


'linkprefix' => '/^(.*?)(„|«)$/sD',
'mainpage'		=> 'მთავარი გვერდი',
#TODO: 'mainpagetext'	=> "<big>'''MediaWiki has been successfully installed.'''</big>",
/*TODO: 'mainpagedocfooter' => "Consult the [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] for information on using the wiki software.

== Getting started ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",*/

'portal'		=> 'საზოგადოების პორტალი',
'portal-url'		=> 'პროექტი:საზოგადოების პორტალი',
'about'			=> 'შესახებ',
#TODO: 'aboutsite'		=> 'About {{SITENAME}}',
'aboutpage'		=> 'პროექტი:შესახებ',
'article'		=> 'სტატია',
'help'			=> 'დახმარება',
#TODO: 'helppage'		=> 'Help:Contents',
#TODO: 'bugreports'	=> 'Bug reports',
#TODO: 'bugreportspage' => 'Project:Bug_reports',
#TODO: 'sitesupport'   => 'Donations',
#TODO: 'sitesupport-url' => 'Project:Site support',
#TODO: 'faq'			=> 'FAQ',
#TODO: 'faqpage'		=> 'Project:FAQ',
#TODO: 'edithelp'		=> 'Editing help',
'newwindow'		=> '(ახალ ფანჯარაში)',
#TODO: 'edithelppage'	=> 'Help:Editing',
'cancel'		=> 'გაუქმება',
#TODO: 'qbfind'		=> 'Find',
#TODO: 'qbbrowse'		=> 'Browse',
'qbedit'		=> 'რედაქტირება',
'qbpageoptions' => 'ეს გვერდი',
'qbpageinfo'	=> 'კონტექსტი',
'qbmyoptions'	=> 'ჩემი გვერდები',
'qbspecialpages'	=> 'სპეციალური გვერდები',
#TODO: 'moredotdotdot'	=> 'More...',
'mypage'		=> 'ჩემი გვერდი',
'mytalk'		=> 'ჩემი განხილვა',
#TODO: 'anontalk'		=> 'Talk for this IP',
#TODO: 'navigation' => 'Navigation',

# Metadata in edit box

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'სტატია',
'nstab-user' => 'მომხმარებლის გვერდი',
'nstab-media' => 'მედია',
'nstab-special' => 'სპეციალური',
'nstab-project' => 'პროექტის გვერდი',
'nstab-image' => 'ფაილი',
'nstab-mediawiki' => 'შეტყობინება',
'nstab-template' => 'თარგი',
'nstab-help' => 'დახმარება',
'nstab-category' => 'კატეგორია',

# Main script and global functions

# General errors

# Login and logout pages

# Edit page toolbar

# Edit pages

# History pages

# Revision deletion

# Diffs
'difference'    => '(სხვაობა ვერსიებს შორის)',
#TODO: 'loadingrev'	=> 'loading revision for diff',
'lineno'                => "ხაზი $1:",
'editcurrent'   => 'ამ გვერდის ამჟამინდელი ვერსიის რედაქტირება',
#TODO: 'selectnewerversionfordiff' => 'Select a newer version for comparison',
#TODO: 'selectolderversionfordiff' => 'Select an older version for comparison',
'compareselectedversions' => 'არჩეული ვერსიების შედარება',

# Search results

# Preferences page
'preferences'	=> 'კონფიგურაცია',
'mypreferences'	=> 'ჩემი კონფიგურაცია',
#TODO: 'prefsnologin' => 'Not logged in',
#TODO: 'prefsnologintext'	=> "You must be [[Special:Userlogin|logged in]] to set user preferences.",
#TODO: 'prefsreset'	=> 'Preferences have been reset from storage.',
#TODO: 'qbsettings'	=> 'Quickbar',
'changepassword' => 'პაროლის შეცვლა',
#TODO: 'skin'			=> 'Skin',
#TODO: 'math'			=> 'Math',
'dateformat'		=> 'თარიღის ფორმატი',
#TODO: 'datedefault'		=> 'No preference',
'datetime'		=> 'თარიღი და დრო',
#TODO: 'math_failure'		=> 'Failed to parse',
#TODO: 'math_unknown_error'	=> 'unknown error',
#TODO: 'math_unknown_function'	=> 'unknown function',
#TODO: 'math_lexing_error'	=> 'lexing error',
#TODO: 'math_syntax_error'	=> 'syntax error',
#TODO: 'math_image_error'	=> 'PNG conversion failed; check for correct installation of latex, dvips, gs, and convert',
#TODO: 'math_bad_tmpdir'	=> 'Can\'t write to or create math temp directory',
#TODO: 'math_bad_output'	=> 'Can\'t write to or create math output directory',
#TODO: 'math_notexvc'	=> 'Missing texvc executable; please see math/README to configure.',
'prefs-personal' => 'მომხმარებლის მონაცემები',
#TODO: 'prefs-rc' => 'Recent changes',
'prefs-watchlist' => 'კონტროლის სია',
#TODO: 'prefs-watchlist-days' => 'Number of days to show in watchlist:',
#TODO: 'prefs-watchlist-edits' => 'Number of edits to show in expanded watchlist:',
#TODO: 'prefs-misc' => 'Misc',
'saveprefs'		=> 'შენახვა',
'resetprefs'	=> 'გადატვირთვა',
'oldpassword'	=> 'ძველი პაროლი:',
'newpassword'	=> 'ახალი პაროლი:',
#TODO: 'retypenew'		=> 'Retype new password:',
#TODO: 'textboxsize'	=> 'Editing',
#TODO: 'rows'			=> 'Rows:',
#TODO: 'columns'		=> 'Columns:',
#TODO: 'searchresultshead' => 'Search',
#TODO: 'resultsperpage' => 'Hits per page:',
#TODO: 'contextlines'	=> 'Lines per hit:',
#TODO: 'contextchars'	=> 'Context per line:',
#TODO: 'stubthreshold' => 'Threshold for stub display:',
#TODO: 'recentchangescount' => 'Titles in recent changes:',
'savedprefs'	=> 'თქვენს მიერ შერჩეული პარამეტრები დამახსოვრებულია.',
#TODO: 'timezonelegend' => 'Time zone',
#TODO: 'timezonetext'	=> 'The number of hours your local time differs from server time (UTC).',
#TODO: 'localtime'	=> 'Local time',
#TODO: 'timezoneoffset' => 'Offset¹',
#TODO: 'servertime'	=> 'Server time',
#TODO: 'guesstimezone' => 'Fill in from browser',
#TODO: 'allowemail'		=> 'Enable e-mail from other users',
'defaultns'		=> 'სტანდარტული ძიება ამ სახელთა სივრცეებში:',
'default'		=> 'სტანდარტული',
'files'			=> 'ფაილები',

# User rights

# Groups

# Recent changes

# Upload

# Image list
'imagelist'		=> 'ფაილების სია',
#TODO: 'imagelisttext' => "Below is a list of '''$1''' {{plural:$1|file|files}} sorted $2.",
#TODO: 'imagelistforuser' => "This shows only images uploaded by $1.",
#TODO: 'getimagelist'	=> 'fetching file list',
#TODO: 'ilsubmit'		=> 'Search',
#TODO: 'showlast'		=> 'Show last $1 files sorted $2.',
#TODO: 'byname'		=> 'by name',
#TODO: 'bydate'		=> 'by date',
#TODO: 'bysize'		=> 'by size',
'imgdelete'		=> 'წაშ.',
'imgdesc'		=> 'აღწ.',
#TODO: 'imgfile'       => 'file',
#TODO: 'imglegend'		=> 'Legend: (desc) = show/edit file description.',
'imghistory'	=> 'ფაილის ისტორია',
#TODO: 'revertimg'		=> 'rev',
'deleteimg'		=> 'წაშ.',
#TODO: 'deleteimgcompletely'		=> 'Delete all revisions of this file',
/*TODO: 'imghistlegend' => 'Legend: (cur) = this is the current file, (del) = delete
this old version, (rev) = revert to this old version.
<br /><i>Click on date to see the file uploaded on that date</i>.',*/
#TODO: 'imagelinks'	=> 'Links',
#TODO: 'linkstoimage'	=> 'The following pages link to this file:',
#TODO: 'nolinkstoimage' => 'There are no pages that link to this file.',
#TODO: 'sharedupload' => 'This file is a shared upload and may be used by other projects.',
#TODO: 'shareduploadwiki' => 'Please see the $1 for further information.',
#TODO: 'shareduploadwiki-linktext' => 'file description page',
#TODO: 'shareddescriptionfollows' => '-',
#TODO: 'noimage'       => 'No file by this name exists, you can $1.',
#TODO: 'noimage-linktext'       => 'upload it',
#TODO: 'uploadnewversion-linktext' => 'Upload a new version of this file',
#TODO: 'imagelist_date' => 'Date',
#TODO: 'imagelist_name' => 'Name',
#TODO: 'imagelist_user' => 'User',
#TODO: 'imagelist_size' => 'Size (bytes)',
#TODO: 'imagelist_description' => 'Description',
#TODO: 'imagelist_search_for' => 'Search for image name:',

# Mime search

# Unwatchedpages

# List redirects

# Unused templates

# Random redirect

# Statistics

# Miscellaneous special pages

# Special:Allpages

# Special:Listusers

# E this user

# Watchlist

# Delete/protect/revert

# restrictions (nouns)

# Undelete

# Namespace form on various pages

# Contributions

# What links here

# Block/unblock IP

# Developer tools

# Make sysop

# Move page

# Export

# Namespace 8 related
'allmessages'   => 'ყველა სისტემური შეტყობინება',
'allmessagesname' => 'დასახელება',
'allmessagesdefault' => 'სტანდარტული ტექსტი',
'allmessagescurrent' => 'მიმდინარე ტექსტი',
'allmessagestext'       => 'ეს არის სახელთა სივრცე მედიავიკიში არსებული სისტემური შეტყობინებების ჩამონათვალი.',
'allmessagesnotsupportedUI' => 'თქვენს ამჟამინდელ ინტერფეისის ენას <b>$1</b> არ აქვს სპეციალური:AllMessages-ის უზრუნველყოფა ამ საიტზე.',
'allmessagesnotsupportedDB' => 'სპეციალური:AllMessages-ის უზრუნველყოფა არ ხდება, ვინაიდან wgUseDatabaseMessages გამორთულია.',
'allmessagesfilter' => 'ფილტრი შეტყობინების სახელის მიხედვით:',
'allmessagesmodified' => 'აჩვენე მხოლოდ შეცვლილი',

# Thumbnails

# Special:Import

# import log

# tooltip help for some actions, most are in Monobook.js

# stylesheets

# Metadata

# Attribution

# Spam protection

# Info page

# Math options

# Patrolling

# Monobook.js: tooltips and access keys for monobook

# image deletion

# browsing diffs

# labels for User: and Title: on Special:Log pages

# Media Warning

# Metadata

# Exif tags

# Make & model, can be wikified in order to link to the camera and model name

# Exif attributes

# external editor support

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ყველა',
'imagelistall' => 'ყველა',
'watchlistall1' => 'ყველა',
'watchlistall2' => 'ყველა',
'namespacesall' => 'ყველა',

# E-mail address confirmation

# Inputbox extension, may be useful in other contexts as well

# Scary transclusion

# Trackbacks

# delete conflict

# HTML dump

# action=purge
#TODO: 'confirm_purge' => "Clear the cache of this page?\n\n$1",
#TODO: 'confirm_purge_button' => 'OK',

'youhavenewmessagesmulti' => "თქვენ გაქვთ ახალი შეტყობინება $1-ზე",
#'newtalkseperator' => ',_',
#TODO: 'searchcontaining' => "Search for articles containing ''$1''.",
#TODO: 'searchnamed' => "Search for articles named ''$1''.",
#TODO: 'articletitles' => "Articles starting with ''$1''",
'hideresults' => 'შედეგების დამალვა',

# DISPLAYTITLE

# Multipage image navigation

# Table pager
#TODO: 'ascending_abbrev' => 'asc',
#TODO: 'descending_abbrev' => 'desc',
#TODO: 'table_pager_next' => 'Next page',
#TODO: 'table_pager_prev' => 'Previous page',
'table_pager_first' => 'პირველი გვერდი',
'table_pager_last' => 'ბოლო გვერდი',
#TODO: 'table_pager_limit' => 'Show $1 items per page',
#TODO: 'table_pager_limit_submit' => 'Go',
#TODO: 'table_pager_empty' => 'No results',


);

?>
