<?php
/** Sanskrit (संस्कृत)
 *
 * @ingroup Language
 * @file
 *
 * @author Kaustubh
 * @author Mahitgar
 * @author Omnipaedista
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

$messages = array(
'underline-always' => 'सदा',
'underline-never'  => 'न जातु',

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

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|वर्ग|वर्गा}}',
'listingcontinuesabbrev' => 'आगामि.',

'about'         => 'विषये',
'newwindow'     => '(उद्घट् नविन पृष्ठ)',
'cancel'        => 'अपकर्ष',
'qbfind'        => 'शोध',
'qbedit'        => 'संपादयति',
'qbpageoptions' => 'इदम्‌ पृष्ठ',
'qbmyoptions'   => 'मदीय लिखितपृष्ठ',
'mypage'        => 'मम पृष्ठ',
'mytalk'        => 'मम लोकप्रवाद',
'navigation'    => 'सुचालन',
'and'           => '&#32;एवम्',

'tagline'          => '{{SITENAME}}त्',
'help'             => 'सहायता',
'search'           => 'शोध',
'searchbutton'     => 'शोध',
'go'               => 'गच्छति',
'searcharticle'    => 'गच्छति',
'history'          => 'पृष्ठस्य इतिहास',
'history_short'    => 'इतिहास',
'printableversion' => 'मुद्रणस्य पाठ',
'permalink'        => 'स्थायी निबन्धन',
'print'            => 'मुद्रण',
'edit'             => 'सम्पादन',
'create'           => 'सृजति',
'editthispage'     => 'इदं पृष्ठस्य सम्पादनार्थ',
'create-this-page' => 'इदं पृष्ठ सृजामि',
'delete'           => 'विलोप',
'protect'          => 'सुरक्षित करोसि',
'protect_change'   => 'सुरक्षा नियम परिवर्त',
'newpage'          => 'नविन पृष्ठ',
'talkpagelinktext' => 'संवाद',
'specialpage'      => 'विशेष पृष्ठ',
'personaltools'    => 'वैयक्तिक साधन',
'talk'             => 'संवाद',
'views'            => 'दृश्य',
'toolbox'          => 'साधनपेटी',
'jumpto'           => 'कूर्दनं करोति :',
'jumptonavigation' => 'सुचालन',
'jumptosearch'     => 'शोध',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}विषये',
'aboutpage'            => 'Project:विषये',
'copyrightpage'        => '{{ns:project}}:प्रताधिकार',
'currentevents'        => 'सद्य घटना',
'disclaimers'          => 'स्वाम्यत्यागं',
'disclaimerpage'       => 'Project:स्वाम्यत्यागं',
'edithelp'             => 'संपादनार्थं सहायता',
'faq'                  => 'अतिप्रश्नपृष्ट',
'helppage'             => 'Help:सहाय्य',
'mainpage'             => 'मुख्यपृष्ठम्',
'mainpage-description' => 'मुख्यपृष्ठम्',
'privacy'              => 'गोपनीयविषये नीति',
'privacypage'          => 'Project:गोपनीयता नीति',

'retrievedfrom'   => 'इतः "$1" निसह्वे',
'newmessageslink' => 'नूतन संदेश',
'editsection'     => 'संपादयति',
'editsectionhint' => 'विभाग संपादन: $1',
'hidetoc'         => 'लुप्य',
'feedlinks'       => 'अनुबन्ध:',
'site-rss-feed'   => '$1 आरएसएस पूरयति',
'site-atom-feed'  => '$1 ऍटम पूरयति',
'page-rss-feed'   => '"$1" आरएसएस अनुबन्ध',
'page-atom-feed'  => '"$1" ऍटम अनुबन्ध',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'पृष्ठ',
'nstab-image'    => 'संचिका',
'nstab-template' => 'बिंबधर',
'nstab-category' => 'वर्ग',

# General errors
'error'         => 'विभ्रम',
'viewsource'    => 'स्रोत पश्यति',
'viewsourcefor' => '$1 कृते',

# Login and logout pages
'yourpassword'            => 'सङ्केतशब्द:',
'login'                   => 'प्रवेश करोसि',
'nav-login-createaccount' => 'प्रवेश करोसि/ सृज् उपयोजकसंज्ञा',
'userlogin'               => 'प्रवेश करोसि/ सृज् उपयोजकसंज्ञा',
'logout'                  => 'बहिर्गच्छति',
'userlogout'              => 'बहिर्गच्छति',
'createaccount'           => 'सृज उपयोजकसंज्ञा',
'gotaccountlink'          => 'प्रवेश करोसि',
'yourlanguage'            => 'भाषा:',
'email'                   => 'विद्युत्पत्रव्यवस्था',
'loginsuccesstitle'       => 'सुस्वागतम्‌ प्रवेश यशस्वी अस्ति',

# Edit page toolbar
'italic_sample' => 'इटालिकाक्षर्‌',

# Edit pages
'summary'     => 'सारांश:',
'watchthis'   => 'इदं पृष्ठ निरीक्षा',
'savearticle' => 'पृष्ठ त्रायते',
'preview'     => 'प्रारूप प्रेक्षा',
'showpreview' => 'प्रारूप प्रेक्षा',
'newarticle'  => '(नविन)',

# History pages
'currentrevisionlink' => 'सद्य आवृत्ती',
'cur'                 => 'अद्य',
'last'                => 'पूर्वतन',
'page_first'          => 'प्रथम्‌',
'page_last'           => 'अन्तिम',

# Revision feed
'history-feed-item-nocomment' => '$1 उप $2', # user at time

# Diffs
'lineno'   => 'रेखा $1:',
'editundo' => 'पूर्ववत करोसि',

# Search results
'nextn'       => 'आगामि$1',
'powersearch' => 'परिणत शोध',

# Recent changes
'recentchanges'   => 'नवीनतम परिवर्तन',
'rcshowhideanons' => 'अनामिक योजकस्य परिवर्त $1',
'hist'            => 'इति.',
'hide'            => 'प्रछद्',
'show'            => 'दर्शयति',
'minoreditletter' => 'ल्घु',
'newpageletter'   => 'न',
'boteditletter'   => 'य',

# Recent changes linked
'recentchangeslinked' => 'सम्भन्दिन् परिवर्त',

# Upload
'upload' => 'भारं न्यस्यति सञ्चिका',

# Special:ListFiles
'imgfile' => 'संचिका',

# File description page
'filehist-deleteone' => 'विलोप',

# Random page
'randompage' => 'अविशिष्ट पृष्ठ',

# Statistics
'statistics' => 'सांख्यिकी',

# Miscellaneous special pages
'longpages'    => 'दीर्घ पृष्ठ',
'newpages'     => 'नूतन पृष्ठ',
'ancientpages' => 'प्राचीनतम् पृष्ठा',
'move'         => 'नामभेद',
'movethispage' => 'इदं पृष्ठस्य स्थानांतर',

# Book sources
'booksources-go' => 'प्रस्थानम्',

# Special:AllPages
'allpages'       => 'सर्व पृष्ठ',
'alphaindexline' => 'इतः $1 यावत् $2',
'allarticles'    => 'सर्व लेखा',
'allpagessubmit' => 'गच्छति',

# Special:Categories
'categories' => 'वर्ग',

# E-mail user
'emailsubject' => 'विषयः',
'emailmessage' => 'सन्देशः',

# Watchlist
'watch'         => 'निरीक्षति',
'watchthispage' => 'प्रतिरक्षति इदं पृष्ठ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'निरिक्षा',

# Delete
'actioncomplete' => 'कार्य समापनम्',

# Protect
'protectcomment'          => 'प्रतिक्रीया:',
'protect-level-sysop'     => 'केवल प्रबंधक',
'protect-summary-cascade' => 'निःश्रेणि',
'restriction-type'        => 'अनुमति:',

# Namespace form on various pages
'namespace'      => 'नामविश्व:',
'blanknamespace' => '(मुख्य)',

# What links here
'whatlinkshere'       => 'किम्‌ पृष्ठ सम्बद्धं करोति',
'whatlinkshere-links' => '← निबन्धन',

# Block/unblock
'blocklink'    => 'निषेध',
'contribslink' => 'योगदान',

# Namespace 8 related
'allmessages'     => 'व्यवस्था सन्देशानि',
'allmessagesname' => 'नाम',

# Thumbnails
'thumbnail-more' => 'विस्तार',

# Special:Import
'import-comment' => 'व्याखान:',

# Tooltip help for the actions
'tooltip-pt-logout'       => 'बहिर्गच्छति',
'tooltip-search'          => '{{SITENAME}} अन्वेषणं करोति',
'tooltip-p-logo'          => 'मुख्यपृष्ठम्  अभ्यागम्',
'tooltip-n-mainpage'      => 'मुख्यपृष्ठम्  अभ्यागम्',
'tooltip-n-portal'        => 'प्रकल्प विषये,भवदिय त्वां किम्‌ करोति, शोधिका',
'tooltip-n-recentchanges' => 'नविनतम परिवर्तन सूची',
'tooltip-n-randompage'    => 'अविशीष्ट लेख',
'tooltip-n-help'          => 'शोधन्‌ स्थानम्‌।',
'tooltip-t-upload'        => 'भारं न्यस्यति संचिका',
'tooltip-t-specialpages'  => 'सर्वानि विशेष पृष्ठस्य सूची',
'tooltip-save'            => 'त्रायते',

# Skin names
'skinname-standard'    => 'पूर्व',
'skinname-nostalgia'   => 'पुराण',
'skinname-cologneblue' => 'नील',
'skinname-monobook'    => 'पुस्तक',
'skinname-myskin'      => 'मे चर्मन्',
'skinname-chick'       => 'Chick',

# Special:NewFiles
'newimages' => 'नूतन संचिका दालन',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सर्व',
'namespacesall' => 'सर्व',
'monthsall'     => 'सर्व',

# Auto-summaries
'autosumm-new' => 'नवीन पृष्ठं: $1',

# Special:Version
'version' => 'आवृत्ति', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages' => 'विशेष पृष्ठ',

);
