<?php
/** Sanskrit (संस्कृत)
 *
 * @ingroup Language
 * @file
 *
 * @author Kaustubh
 * @author Mahitgar
 */

$fallback = 'hi';

$digitTransformTable = array(
	'0' => '०', # &#x0966;
	'1' => '१', # &#x0967;
	'2' => '२', # &#x0968;
	'3' => '३', # &#x0969;
	'4' => '४', # &#x096a;
	'5' => '५', # &#x096b;
	'6' => '६', # &#x096c;
	'7' => '७', # &#x096d;
	'8' => '८', # &#x096e;
	'9' => '९', # &#x096f;
);

$linkPrefixExtension = false;

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN	            => '',
	NS_TALK	            => 'संभाषणं',
	NS_USER             => 'योजकः',
	NS_USER_TALK        => 'योजकसंभाषणं',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1संभाषणं',
	NS_FILE             => 'चित्रं',
	NS_FILE_TALK        => 'चित्रसंभाषणं',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'उपकारः',
	NS_HELP_TALK        => 'उपकारसंभाषणं',
	NS_CATEGORY         => 'वर्गः',
	NS_CATEGORY_TALK    => 'वर्गसंभाषणं',
);

$skinNames = array(
	'standard' => 'पूर्व',
	'nostalgia' => 'पुराण',
	'cologneblue' => 'नील',
	'monobook' => 'पुस्तक',
	'myskin' => 'मे चर्मन्',
	'chick' => 'Chick'
);

$messages = array(
'underline-always' => 'सदा',

# Dates
'sunday'    => 'विश्रामवासरे',
'monday'    => 'सोमवासरे',
'tuesday'   => 'मंगलवासरे',
'wednesday' => 'बुधवासरे',
'thursday'  => 'गुरुवासरे',
'friday'    => 'शुक्रवासरे',
'saturday'  => 'शनिवासरे',
'sun'       => 'विश्राम',
'mon'       => 'सोम',
'tue'       => 'मंगल',
'wed'       => 'बुध',
'thu'       => 'गुरु',
'fri'       => 'शुक्र',
'sat'       => 'शनि',
'january'   => 'पौषमाघे',
'february'  => 'फाल्गुने',
'march'     => 'फाल्गुनचैत्रे',
'april'     => 'मधुमासे',
'may_long'  => 'वैशाखज्येष्ठे',
'june'      => 'ज्येष्ठाषाढके',
'july'      => 'आषाढश्रावणे',
'august'    => 'नभस्ये',
'september' => 'भाद्रपदाश्विने',
'october'   => 'अश्विनकार्तिके',
'november'  => 'कार्तिकमार्गशीर्षे',
'december'  => 'मार्गशीर्षपौषे',

'about'         => 'विषये',
'newwindow'     => '(उद्घट् नविन पृष्ठ)',
'cancel'        => 'अपकर्ष',
'qbfind'        => 'शोध',
'qbedit'        => 'संपादयति',
'qbpageoptions' => 'इदम्‌ पृष्ठ',
'navigation'    => 'सुचालन',
'and'           => '&#32;एवम्',

'help'             => 'सहायता',
'search'           => 'शोध',
'searchbutton'     => 'शोध',
'go'               => 'गच्छति',
'searcharticle'    => 'गच्छति',
'history'          => 'पृष्ठस्य इतिहास',
'history_short'    => 'इतिहास',
'printableversion' => 'मुद्रणस्य पाठ',
'permalink'        => 'स्थायी निबन्धन',
'newpage'          => 'नविन पृष्ठ',
'talkpagelinktext' => 'संवाद',
'specialpage'      => 'विशेष पृष्ठ',
'personaltools'    => 'वैयक्तिक साधन',
'views'            => 'दृश्य',
'toolbox'          => 'साधनपेटी',
'jumptonavigation' => 'सुचालन',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}विषये',
'aboutpage'            => 'Project:विषये',
'copyrightpage'        => '{{ns:project}}:प्रताधिकार',
'currentevents'        => 'सद्य घटना',
'disclaimers'          => 'स्वाम्यत्यागं',
'edithelp'             => 'संपादनार्थं सहायता',
'faq'                  => 'अतिप्रश्नपृष्ट',
'helppage'             => 'Help:सहाय्य',
'mainpage'             => 'मुखपृष्ठं',
'mainpage-description' => 'मुखपृष्ठं',
'privacy'              => 'गोपनीयविषये नीति',

'editsection' => 'संपादयति',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'पृष्ठ',
'nstab-image'    => 'संचिका',
'nstab-template' => 'बिंबधर',

# General errors
'error'      => 'विभ्रम',
'viewsource' => 'स्रोत पश्यति',

# Login and logout pages
'login'                   => 'प्रवेश करोसि',
'nav-login-createaccount' => 'प्रवेश करोसि/ सृज् उपयोजकसंज्ञा',
'userlogout'              => 'बहिर्गच्छति',
'gotaccountlink'          => 'प्रवेश करोसि',

# Edit pages
'watchthis'   => 'इदं पृष्ठ निरीक्षा',
'savearticle' => 'पृष्ठ त्रायते',
'preview'     => 'प्रारूप प्रेक्षा',
'showpreview' => 'प्रारूप प्रेक्षा',
'newarticle'  => '(नविन)',

# History pages
'cur'        => 'अद्य',
'last'       => 'पूर्वतन',
'page_first' => 'प्रथम्‌',

# Diffs
'editundo' => 'पूर्ववत करोसि',

# Search results
'powersearch' => 'परिणत शोध',

# Recent changes
'recentchanges' => 'नवीनतम परिवर्तन',
'hide'          => 'प्रछद्',
'show'          => 'दर्शयति',

# Recent changes linked
'recentchangeslinked' => 'सम्भन्दिन् परिवर्त',

# Upload
'upload' => 'भारं न्यस्यति सञ्चिका',

# Random page
'randompage' => 'अविशिष्ट पृष्ठ',

# Miscellaneous special pages
'ancientpages' => 'प्राचीनतम् पृष्ठा',

# Book sources
'booksources-go' => 'प्रस्थानम्',

# Special:AllPages
'alphaindexline' => 'इतः $1 यावत् $2',
'allarticles'    => 'सर्व लेखा',

# Special:Categories
'categories' => 'वर्ग',

# E-mail user
'emailsubject' => 'विषयः',
'emailmessage' => 'सन्देशः',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'निरिक्षा',

# Delete
'actioncomplete' => 'कार्य समापनम्',

# What links here
'whatlinkshere' => 'किम्‌ पृष्ठ सम्बद्धं करोति',

# Block/unblock
'blocklink' => 'निषेध',

# Namespace 8 related
'allmessages'     => 'व्यवस्था सन्देशानि',
'allmessagesname' => 'नाम',

# Tooltip help for the actions
'tooltip-search'          => '{{SITENAME}} अन्वेषणं करोति',
'tooltip-n-mainpage'      => 'मुखपृष्ठ अभ्यागम',
'tooltip-n-recentchanges' => 'नविनतम परिवर्तन सूची',
'tooltip-n-randompage'    => 'अविशीष्ट लेख',
'tooltip-save'            => 'त्रायते',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'सर्व',
'monthsall'     => 'सर्व',

# Auto-summaries
'autosumm-new' => 'नवीन पृष्ठं: $1',

# Special:Version
'version' => 'आवृत्ति', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages' => 'विशेष पृष्ठ',

);
