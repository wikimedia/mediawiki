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
'tog-hideminor'        => 'हाल के परिवर्तन में मामूली संपादन छुपाईं',
'tog-rememberpassword' => 'ई कम्पयुटर पर हमार खाता हर दम सक्रिय रहे',
'tog-previewonfirst'   => 'पहिलका सम्पादन पर पूर्वावलोकन देखीं',

'underline-always' => 'हमेशा',
'underline-never'  => 'कभी ना',

# Dates
'sunday'    => 'इतवार',
'monday'    => 'सोमवार',
'tuesday'   => 'मंगलवार',
'wednesday' => 'बुधवार',
'thursday'  => 'गुरुवार',
'friday'    => 'शुक्रवार',
'saturday'  => 'शनिवार',
'sun'       => 'इत',
'mon'       => 'सोम',
'tue'       => 'मंगल',
'wed'       => 'बुध',
'thu'       => 'गुरु',
'fri'       => 'शुक्र',
'sat'       => 'शनि',
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
'feb'       => 'फर',
'mar'       => 'मार्च',
'apr'       => 'अप्रिल',
'may'       => 'मई',
'jun'       => 'जून',
'jul'       => 'जुल',
'aug'       => 'अग',
'sep'       => 'सित',
'oct'       => 'अक्टू',
'nov'       => 'नव',
'dec'       => 'दिस',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|श्रेणी|श्रेणी}}',

'newwindow'  => '(नया विंडो में खोलीं)',
'cancel'     => 'निरस्त',
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

# Vector skin
'vector-action-delete' => 'मिटाईं',
'vector-action-move'   => 'स्थांतरण',

'tagline'          => '{{SITENAME}} कि ओर से',
'help'             => 'मदद',
'search'           => 'खोज',
'searchbutton'     => 'खोजीं',
'go'               => 'जाईं',
'searcharticle'    => 'जाईं',
'history'          => 'पन्ना के इतिहास',
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
'views'            => 'विचारसूची',
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
'jumpto'           => 'अहिजा जाईं:',
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
'portal'               => 'सामुदायिक पन्ना',
'privacy'              => 'गोपनीयता नीति',
'privacypage'          => 'Project:गोपनीयता नीति',

'badaccess'        => 'अनुमति त्रुटी',
'badaccess-group0' => 'रउआ जवन कार्रवाई खातिर अनुरोध कईले बानी उ के निष्पादन करे के अनुमति नईखे।',

'retrievedfrom'           => '"$1" से लियल गईल',
'youhavenewmessages'      => 'रउआ लगे बा $1 ($2).',
'newmessageslink'         => 'नया सन्देश',
'newmessagesdifflink'     => 'अन्तिम परिवर्तन',
'youhavenewmessagesmulti' => 'रउआ लगे $1 पर नया सन्देश बा',
'editsection'             => 'सम्पादन',
'editold'                 => 'सम्पादन',
'viewsourceold'           => 'स्त्रोत देखीं',
'editlink'                => 'सम्पादन',
'viewsourcelink'          => 'स्त्रोत देखीं',
'editsectionhint'         => 'सम्पादन खण्ड: $1',
'toc'                     => 'सामग्री',
'showtoc'                 => 'देखाईं',
'hidetoc'                 => 'छुपाईं',
'thisisdeleted'           => 'देखीं या भंडार करीं $1?',
'viewdeleted'             => '$1 देखब?',
'site-rss-feed'           => '$1 आर एस एस फिड',
'site-atom-feed'          => '$1 एटम फिड',
'page-rss-feed'           => '"$1" आर एस एस फिड',
'page-atom-feed'          => '"$1" एटम फिड',
'red-link-title'          => '$1 (पन्ना मौजूद नईखे)।',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'पन्ना',
'nstab-user'      => 'प्रयोगकर्ता पन्ना',
'nstab-media'     => 'मिडीया पन्ना',
'nstab-special'   => 'विशेष पन्ना',
'nstab-project'   => 'परियोजना पन्ना',
'nstab-image'     => 'फाईल',
'nstab-mediawiki' => 'सन्देश',
'nstab-template'  => 'टेम्पलेट',
'nstab-help'      => 'मदद पन्ना',
'nstab-category'  => 'श्रेणी',

# Main script and global functions
'nosuchaction'      => 'अईसन कौनो कार्रवाई नाहि',
'nosuchspecialpage' => 'अईसन कौनो ख़ाश पन्ना नाहि',

# General errors
'error'         => 'त्रुटी',
'databaseerror' => 'डेटाबेस त्रुटी',
'viewsource'    => 'स्त्रोत देखीं',

# Login and logout pages
'yourname'                => 'प्रयोगकर्ता नाम',
'yourpassword'            => 'गुप्त शब्द',
'yourpasswordagain'       => 'गुप्त-शब्द पुन:डालीं:',
'login'                   => 'खाता में प्रवेश',
'nav-login-createaccount' => 'खाता प्रवेश / खाता बनाईं',
'loginprompt'             => '{{SITENAME}} में प्रवेश खातिर राउर कुकिज चालू होवे के चाहीं',
'userlogin'               => 'खाता प्रवेश / खाता बनाईं',
'userloginnocreate'       => 'खाता में प्रवेश',
'logout'                  => 'खाता से बाहर',
'userlogout'              => 'खाता से बाहर',
'notloggedin'             => 'खाता में प्रवेश नईखीं भईल',
'nologin'                 => 'का एगो खाता नईखे? $1.',
'nologinlink'             => 'एगो खाता बनाईं',
'createaccount'           => 'खाता बनाईं',
'gotaccount'              => 'का पहिले से एगो खाता बा? $1.',
'gotaccountlink'          => 'खाता में प्रवेश',
'createaccountmail'       => 'ई-मेल द्वारा',
'badretype'               => 'रउआ जौन गुप्त शब्द डालत बानी उ नईखे मेल खात।',
'userexists'              => 'ई प्रयोगकर्ता नाम पहिले से इस्तेमाल में बा। कृपया कौनो दोसर नाम चुनीं।',
'loginerror'              => 'खाता प्रवेश में त्रुटि',
'createaccounterror'      => 'ई खाता ना बन पाईल: $1',
'nocookiesnew'            => 'प्रयोगकर्ता खाता त बन गईल, बाँकी रउआ प्रवेश नईखीं भईल।
{{SITENAME}} प्रयोगकर्ता लोग के खाता में प्रवेश करावे खातिर कुकिज के प्रयोग करेला।
राउर कुकिज असक्षम बा।
कृपया उ के सक्षम करीं, उ के बाद राउर नया प्रयोगकर्ता नाम आ गुप्त शब्द के साथ प्रवेश करीं।',
'nocookieslogin'          => '{{SITENAME}} प्रयोगकर्ता लोग के खाता में प्रवेश करावे खातिर कुकिज के प्रयोग करेला।
राउर कुकिज असक्षम बा।
कृपया उ के सक्षम करीं आ फिर से कोशिश करीं',
'noname'                  => 'रउआ उपयुक्त प्रयोगकर्ता नाम नईखीं निर्दिष्ट कईले।',
'loginsuccesstitle'       => 'खाता प्रवेश में सफल',
'loginsuccess'            => "''' \"\$1\" के रुप में रउआ {{SITENAME}} में अब प्रवेश कर चुकल बानी।'''",
'nosuchuser'              => '"$1" नाम से कौनो प्रयोगकर्ता नईखन।
प्रयोगकर्ता नाम संवेदनशील मामला बा।
शब्द-वर्तनी के जाँच करीं, आ चाहे [[Special:UserLogin/signup|एगो नया खाता बनाईं]]।',
'nosuchusershort'         => 'ई नाम से कौनो प्रयोगकर्ता नईखन "<nowiki>$1</nowiki>".
आपन शब्द-वर्तनी के जाँच करीं।',
'nouserspecified'         => 'रउआ एगो प्रयोगकर्ता नाम निर्दिष्ट करे के बा।',
'login-userblocked'       => 'ई प्रयोगकर्ता के खाता निष्क्रिय हो चुकल बा। प्रवेश के आज्ञा नईखे।',
'wrongpassword'           => 'गलत गुप्त-शब्द डलले बानी।
कृपया फिर से कोशिश करीं।',
'wrongpasswordempty'      => 'गुप्त-शब्द खाली बा। कृपया फिर से कोशिश करीं।',
'passwordtooshort'        => 'गुप्त-शब्द कम से कम {{PLURAL:$1|1 अक्षर|$1 अक्षर}} के होवे के चाहीं।',
'password-name-match'     => 'राउर गुप्त-शब्द राउर प्रयोगकर्ता नाम से अलग होवे के चाहीं।',
'mailmypassword'          => 'नया गुप्त-शब्द ई-मेल पर भेजीं',
'passwordremindertitle'   => '{{SITENAME}} खातिर नया अस्थायी गुप्त-शब्द',

# Password reset dialog
'resetpass'   => 'गुप्त-शब्द बदलीं',
'oldpassword' => 'पुराना गुप्त-शब्द:',
'newpassword' => 'नया गुप्त-शब्द:',
'retypenew'   => 'नया गुप्त-शब्द पुन: डालीं:',

# Edit pages
'summary'              => 'सारांश:',
'minoredit'            => 'छोट परिवर्तन',
'watchthis'            => 'ई पन्ना ध्यानसूची में डालीं',
'savearticle'          => 'पन्ना सुरक्षित करीं',
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
'yourdiff'             => 'अंतर',

# History pages
'cur' => 'हाल',

# Revision deletion
'rev-delundel' => 'दिखाईं/छुपाईं',

# Diffs
'lineno'   => 'पंक्ति $1:',
'editundo' => 'पूर्ववत',

# Search results
'searchresults'             => 'खोज परिणाम',
'searchresults-title'       => '$1 खातिर खोज परिणाम',
'searchsubtitle'            => '\'\'\'[[:$1]]\'\'\' खातिर राउर करल गईल खोज ([[Special:Prefixindex/$1| "$1" से शुरु होवे वाला सब पन्ना]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|all pages that link to "$1"]])',
'search-result-size'        => '$1 ({{PLURAL:$2|1 शब्द|$2 शब्द}})',
'search-result-score'       => 'प्रासंगिकता: $1%',
'search-redirect'           => '(पुन: निर्देशण $1)',
'search-section'            => '(खंड $1)',
'search-suggest'            => 'का राउर मतलब बा: $1',
'search-interwiki-caption'  => 'बहिन परियोजना',
'search-interwiki-default'  => '$1 के परिणाम:',
'search-interwiki-more'     => '(अउर)',
'search-mwsuggest-enabled'  => 'सुझाव के साथ',
'search-mwsuggest-disabled' => 'कौनो सुझाव ना',
'search-relatedarticle'     => 'संबंधित',
'mwsuggest-disable'         => 'AJAX सुझाव असक्षम',
'searcheverything-enable'   => 'सभन सन्दर्भ में खोजीं',
'searchrelated'             => 'संबंधित',
'searchall'                 => 'सब',
'showingresults'            => "नीचे देखावल जा रहल बा {{PLURAL:$1|'''1''' परिणाम|'''$1''' परिणाम}} #'''$2''' से शुरु होवे वाला।",
'showingresultsnum'         => "नीचे देखावल जा रहल बा {{PLURAL:$3|'''1''' परिणाम|'''$3''' परिणाम}} #'''$2''' से शुरु होवे वाला।",

# Preferences page
'preferences'    => 'वरीयता',
'mypreferences'  => 'हमार पसन्द',
'prefs-edits'    => 'सम्पादन संख्या',
'prefsnologin'   => 'खाता में प्रवेश नईखीं कईले',
'changepassword' => 'गुप्त शब्द बदलीं',
'skin-preview'   => 'पूर्वावलोकन',
'prefs-math'     => 'गणित',
'prefs-rc'       => 'तुरंत भईल परिवर्तन',

# Recent changes
'recentchanges'                     => 'तुरंत भईल परिवर्तन',
'rcshowhideminor'                   => '$1 छोट सम्पादन',
'diff'                              => 'अन्तर',
'hist'                              => 'इति',
'hide'                              => 'छुपाँई',
'show'                              => 'दिखाईं',
'minoreditletter'                   => 'छो',
'newpageletter'                     => 'न',
'boteditletter'                     => 'बो',
'number_of_watching_users_pageview' => '[$1 देखल जा रहल बा {{PLURAL:$1|प्रयोगकर्ता|प्रयोगकर्ता}}]',

# Recent changes linked
'recentchangeslinked' => 'सम्बन्धित बदलाव',

# Upload
'upload' => 'फाईल लादीं',

# File description page
'filehist'          => 'पन्ना के इतिहास',
'filehist-current'  => 'मौजूदा',
'filehist-datetime' => 'तारिख/समय',
'filehist-user'     => 'प्रयोगकर्ता',
'filehist-filesize' => 'फाईल के आकार',
'filehist-comment'  => 'टिप्पणी',
'filehist-missing'  => 'गायब फाईल',
'imagelinks'        => 'फाईल लिंक',

# Random page
'randompage' => 'अविशिष्ट पन्ना',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|बाईट|बाईट्स}}',
'move'         => 'स्थान्तरण',
'movethispage' => 'ई पन्ना के स्थांतरण करीं',

# Book sources
'booksources' => 'किताबी स्त्रोत',

# Special:AllPages
'allpagessubmit' => 'जाईं',
'allpagesprefix' => 'उपसर्ग के साथे पन्ना प्रदर्शन:',

# E-mail user
'emailuser' => 'ई प्रयोगकर्ता के ईमेल करीं',

# Watchlist
'watchlist'     => 'हमार ध्यानसूची',
'mywatchlist'   => 'हमार ध्यानसूची',
'watch'         => 'ध्यानसूची में डालीं',
'watchthispage' => 'ई पन्ना ध्यानसूची में डालीं',
'unwatch'       => 'ध्यानसूची से हटाईं',

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

# What links here
'whatlinkshere'            => 'अहिजा का जुड़ी',
'whatlinkshere-title'      => 'पन्ना जौन "$1" से जुड़ेला',
'whatlinkshere-page'       => 'पन्ना:',
'linkshere'                => "नीचे के सब पन्ना '''[[:$1]]''' से जुड़ेला:",
'nolinkshere'              => "'''[[:$1]]''' से कौनो पन्ना नईखे जुड़ल।",
'nolinkshere-ns'           => "चुनल गईल सन्दर्भ में '''[[:$1]]''' से कौनो पन्ना ना जुड़ेला।",
'isredirect'               => 'पुन: निर्दिष्ट पन्ना',
'isimage'                  => 'तस्वीर लिंक',
'whatlinkshere-prev'       => '{{PLURAL:$1|पिछला|पिछला $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|अगला|अगला $1}}',
'whatlinkshere-links'      => '← लिंक',
'whatlinkshere-hideredirs' => '$1 पुन: निर्देशित',
'whatlinkshere-hidelinks'  => '$1 लिंक',
'whatlinkshere-hideimages' => '$1 तस्वीर लिंक',
'whatlinkshere-filters'    => 'फिल्टर',

# Block/unblock
'blockip'      => 'प्रतिबंधित प्रयोगकर्ता',
'blocklink'    => 'निष्क्रिय',
'contribslink' => 'योगदान',
'blocklogpage' => 'निष्क्रिय खाता',

# Move page
'movepagebtn' => 'पन्ना स्थांतरण करीं',

# Tooltip help for the actions
'tooltip-pt-login'               => 'रउआ के खाता प्रवेश खातिर प्रोत्साहित करल जा रहल बा, बाँकि ई अनिवार्य नईखे',
'tooltip-ca-talk'                => 'सामग्री पन्ना के बारे में बात-चीत',
'tooltip-ca-edit'                => 'रउआ ई पन्ना के सम्पादन कर सकत बानी। कृपया पन्ना सुरक्षित करे से पहिले पूर्वावलोकन बटन के इस्तेमाल करीं।',
'tooltip-ca-history'             => 'ई पन्ना के पिछला संशोधन',
'tooltip-ca-delete'              => 'ई पन्ना मिटाईं',
'tooltip-ca-move'                => 'ई पन्ना के स्थांतरण करीं',
'tooltip-search'                 => '{{SITENAME}} खोजीं',
'tooltip-search-go'              => 'यदि पन्ना मौजूद होई त ईहे सटीक नाम के साथ उ पन्ना पर जाईं',
'tooltip-search-fulltext'        => 'ई पाठ्य खातिर पन्ना खोजीं',
'tooltip-p-logo'                 => 'मुख्य पन्ना पर जाईं',
'tooltip-n-mainpage'             => 'मुख्य पन्ना पर जाईं',
'tooltip-n-mainpage-description' => 'मुख्य पन्ना पर पधारीं',
'tooltip-n-portal'               => 'परियोजना के बारे मेँ, रउआ का कर सकत बानी, वस्तु कहाँ खोजब',
'tooltip-n-currentevents'        => 'वर्तमान के घटना पर पृष्ठभूमी जानकारी खोजीं',
'tooltip-n-recentchanges'        => 'विकि पर तुरंत भईल परिवर्तन के सूची',
'tooltip-n-randompage'           => 'बेतरतिब पन्ना लादीं (Load करीं)',
'tooltip-n-help'                 => 'जगह पता लगावे खातिर',
'tooltip-t-whatlinkshere'        => 'अहिजा लिंक होखे वाला सब विकि पन्ना के सूची',
'tooltip-t-recentchangeslinked'  => 'ई पन्ना से जुड़ल पन्नवन पर तुरंत भईल परिवर्तन',
'tooltip-t-upload'               => 'फाईल लादीं (अपलोड )',
'tooltip-t-specialpages'         => 'ख़ाश पन्नवन के सूची',
'tooltip-t-print'                => 'ई पन्ना के छापे लायक संस्करण।',
'tooltip-t-permalink'            => 'ई पन्ना के संसोधन खातिर स्थायी लिंक।',
'tooltip-ca-nstab-main'          => 'सामग्री पन्ना देखीं',
'tooltip-ca-nstab-special'       => 'ई एगो ख़ाश पन्ना ह, रउआ ई पन्ना के सम्पादन नईखीं कर सकत',
'tooltip-save'                   => 'आपन बदलाव के सुरक्षित करीं',

# Special:SpecialPages
'specialpages' => 'ख़ाश पन्ना',

);
