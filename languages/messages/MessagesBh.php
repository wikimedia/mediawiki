<?php
/** Bihari (भोजपुरी)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ganesh
 */

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

$messages = array(
# User preference toggles
'tog-previewonfirst' => 'पहिलका सम्पादन पर पूर्वावलोकन देखीं',

# Dates
'january'   => 'जनवरी',
'february'  => 'फरवरी',
'march'     => 'मार्च',
'april'     => 'अप्रिल',
'may_long'  => 'मई',
'june'      => 'जून',
'july'      => 'जुलाई',
'august'    => 'अगस्त',
'september' => 'सितम्बर',
'october'   => 'अक्टूबर',
'november'  => 'नवम्बर',
'december'  => 'दिसम्बर',
'jan'       => 'जन',

'mytalk'     => 'हमार बात',
'navigation' => 'परिभ्रमण',

# Cologne Blue skin
'qbfind'         => 'खोज',
'qbbrowse'       => 'ब्राउज',
'qbedit'         => 'सम्पादन',
'qbpageoptions'  => 'ई पन्ना',
'qbpageinfo'     => 'प्रसंग',
'qbmyoptions'    => 'हमार पन्ना',
'qbspecialpages' => 'विशेष पन्ना',

'help'             => 'मदद',
'search'           => 'खोज',
'searchbutton'     => 'खोजीं',
'searcharticle'    => 'जाईं',
'history_short'    => 'इतिहास',
'updatedmarker'    => 'हमार अन्तिम आगमन से बदलाव',
'info_short'       => 'जानकारी',
'printableversion' => 'छापे लायक संस्करण',
'permalink'        => 'स्थायी लिंक',
'print'            => 'छापीं',
'edit'             => 'सम्पादन',
'create'           => 'बनाईं',
'editthispage'     => 'ई पन्ना के सम्पादन करीं',
'create-this-page' => 'ई पन्ना के निर्माण करीं',
'delete'           => 'मिटाईं',
'deletethispage'   => 'ई पन्ना के मिटाईं',
'newpage'          => 'नया पन्ना',
'talkpagelinktext' => 'बात-चीत',
'personaltools'    => 'ब्यक्तिगत औजार',
'talk'             => 'बात-चीत',
'toolbox'          => 'औजार-पेटी',
'userpage'         => 'प्रयोगकर्ता पन्ना देखीं',
'projectpage'      => 'परियोजना पन्ना देखीं',
'imagepage'        => 'फाईल पन्ना देखीँ',
'mediawikipage'    => 'सन्देश पन्ना देखीं',
'templatepage'     => 'टेम्पलेट पन्ना देखीं',
'viewhelppage'     => 'मदद पन्ना देखीं',
'categorypage'     => 'श्रेणी पन्ना देखीं',
'viewtalkpage'     => 'बात-चीत देखीं',
'otherlanguages'   => 'अन्य भाषा में',
'redirectedfrom'   => '($1 द्वारा पुन: निर्देशित)',
'redirectpagesub'  => 'पुन: निर्देशित पन्ना',
'lastmodifiedat'   => '$1 के $2 पर ई पन्ना पर अन्तिम बार परिवर्तन भईल।',
'protectedpage'    => 'सुरक्षित पन्ना',
'jumptonavigation' => 'परिभ्रमण',
'jumptosearch'     => 'खोजीं',
'view-pool-error'  => 'क्षमा करीं, ई समय सर्वर पर बहुत ज्यादा लोड बढ़ गईल बा।
ई पन्ना के बहुते प्रयोगकर्ता लोग देखे के कोशिश कर रहल बानी।
ई पन्ना के फिर से देखे से पहिले कृपया कुछ देर तक इन्तजार करीं।

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} के बारे में',
'aboutpage'            => 'Project:बारे में',
'copyright'            => 'सामग्री $1 के तहत उपलब्ध बा।',
'copyrightpage'        => '{{ns:project}}:लेखाधिकार',
'currentevents'        => 'हाल के घटना',
'currentevents-url'    => 'Project:हाल के घटना',
'disclaimers'          => 'अस्विकरण',
'disclaimerpage'       => 'Project:सामान्य अस्विकरण',
'mainpage'             => 'मुख्य पन्ना',
'mainpage-description' => 'पहिलका पन्ना',
'privacy'              => 'गोपनीयता नीति',
'privacypage'          => 'Project:गोपनीयता नीति',

'badaccess'        => 'अनुमति त्रुटी',
'badaccess-group0' => 'रउआ जवन कार्रवाई खातिर अनुरोध कईले बानी उ के निष्पादन करे के अनुमति नईखे।',

'showtoc'        => 'देखाईं',
'red-link-title' => '$1 (पन्ना मौजूद नईखे)।',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'    => 'पन्ना',
'nstab-user'    => 'प्रयोगकर्ता पन्ना',
'nstab-media'   => 'मिडीया पन्ना',
'nstab-special' => 'विशेष पन्ना',
'nstab-project' => 'परियोजना पन्ना',

# Login and logout pages
'yourname'     => 'प्रयोगकर्ता नाम',
'yourpassword' => 'गुप्त शब्द',
'login'        => 'खाता में प्रवेश',
'logout'       => 'खाता से बाहर',

# Edit pages
'savearticle'          => 'पन्ना के सुरक्षित करीं',
'preview'              => 'पूर्वावलोकन',
'showpreview'          => 'पूर्वावलोकन देखाईं',
'showlivepreview'      => 'सीधा पूर्वावलोकन',
'showdiff'             => 'परिवर्तन देखाईं',
'anoneditwarning'      => "'''चेतावनी:''' रउआ आपन खाता में प्रवेश नईखीं कईले। ई पन्ना के सम्पादन इतिहास पर राउर आई पी पता दर्ज कईल जाई।",
'anonpreviewwarning'   => "''रउआ खाता में प्रवेश नईखीं भईल। सुरक्षित करेब त ई पन्ना के सम्पादन इतिहास पर राउर आई पी पता दर्ज हो जाई।\"",
'missingsummary'       => "'''स्मरणपत्र:'''रउआ एगो सारांश के सम्पादन नईखीं प्रदान कईले। अगर रउआ \"फिर से सुरक्षित करीं\" पर क्लिक करेब, त राउर सम्पादन बिना एगो सारांश के सुरक्षित हो जाई।",
'missingcommenttext'   => 'कृपया निचे एगो टिप्पणी करीं।',
'missingcommentheader' => "'''स्मरणपत्र:''' रउआ ई टिप्पणी खातिर कौनो विषय/शिर्षक प्रदान नईखीं कईले। यदि रउआ फिर से सुरक्षित करब त राउर सम्पादन बिना कौनो शिर्षक के सुरक्षित हो जाई।",
'summary-preview'      => 'सारांश पूर्वावलोकन:',
'subject-preview'      => 'विषय/शिर्षक पूर्वावलोकन:',
'blockedtitle'         => 'निष्क्रिय प्रयोगकर्ता',

# Search results
'searchresults-title'       => '$1 खातिर खोज परिणाम',
'search-suggest'            => 'का राउर मतलब बा: $1',
'search-interwiki-default'  => '$1 के परिणाम:',
'search-interwiki-more'     => '(अउर)',
'search-mwsuggest-enabled'  => 'सुझाव के साथ',
'search-mwsuggest-disabled' => 'कौनो सुझाव ना',
'search-relatedarticle'     => 'संबंधित',

# Preferences page
'mypreferences'  => 'हमार पसन्द',
'prefs-edits'    => 'सम्पादन संख्या',
'prefsnologin'   => 'खाता में प्रवेश नईखीं कईले',
'changepassword' => 'गुप्त शब्द बदलीं',
'skin-preview'   => 'पूर्वावलोकन',
'prefs-math'     => 'गणित',

# Recent changes linked
'recentchangeslinked' => 'सम्बन्धित बदलाव',

# File description page
'filehist-user'     => 'प्रयोगकर्ता',
'filehist-filesize' => 'फाईल के आकार',
'filehist-comment'  => 'टिप्पणी',
'filehist-missing'  => 'गायब फाईल',
'imagelinks'        => 'फाईल लिंक',

# Watchlist
'watch' => 'देखीं',

# Namespace form on various pages
'blanknamespace' => '(मुख्य)',

# Contributions
'contributions'       => 'प्रयोगकर्ता योगदान',
'contributions-title' => ' $1 खातिर प्रयोगकर्ता योगदान',
'mycontris'           => 'हमार योगदान',
'nocontribs'          => 'ई मानदंड से मिलत जुलत कौनो बदलाव ना मिलल।',
'uctop'               => '(शीर्ष)',
'month'               => 'महिना से (आ उ से पहिले):',
'year'                => 'साल से (आ उ से पहिले):',

'sp-contributions-newbies'        => 'खाली नया खाता के योगदान देखीं।',
'sp-contributions-newbies-sub'    => 'नया खाता खातिर',
'sp-contributions-newbies-title'  => 'नया खाता खातिर प्रयोगकर्ता के योगदान।',
'sp-contributions-blocklog'       => 'निष्क्रीय खाता',
'sp-contributions-deleted'        => 'नष्ट प्रयोगकर्ता के योगदान।',
'sp-contributions-logs'           => 'लौग',
'sp-contributions-talk'           => 'बात-चीत',
'sp-contributions-userrights'     => 'प्रयोगकर्ता अधिकार प्रबन्धन',
'sp-contributions-blocked-notice' => 'ई प्रयोगकर्ता के ई समय निष्क्रीय करल गईल बा।
नविनतम नष्ट लौग प्रविष्टी उद्धरण खातिर निचे दिहल बा:',

# Block/unblock
'contribslink' => 'योगदान',
'blocklogpage' => 'निष्क्रिय खाता',

# Tooltip help for the actions
'tooltip-n-mainpage'             => 'मुख्य पन्ना पर जाईं',
'tooltip-n-mainpage-description' => 'मुख्य पन्ना पर पधारीं',
'tooltip-n-portal'               => 'परियोजना के बारे मेँ, रउआ का कर सकत बानी, वस्तु कहाँ खोजब',
'tooltip-n-currentevents'        => 'वर्तमान के घटना पर पृष्ठभूमी जानकारी खोजीं',
'tooltip-n-recentchanges'        => 'विकि पर तुरंत भईल परिवर्तन के सूची',
'tooltip-n-randompage'           => 'बेतरतिब पन्ना लादीं (Load करीं)',
'tooltip-n-help'                 => 'जगह पता लगावे खातिर',
'tooltip-t-whatlinkshere'        => 'अहिजा लिंक होखे वाला सब विकि पन्ना के सूची',
'tooltip-t-print'                => 'ई पन्ना के छापे लायक संस्करण।',
'tooltip-t-permalink'            => 'ई पन्ना के संसोधन खातिर स्थायी लिंक।',

);
