<?php
/** Oriya (ଓଡ଼ିଆ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Jayantanth
 * @author Jose77
 * @author Odisha1
 * @author Psubhashish
 * @author Sambiwiki
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */

$digitTransformTable = array(
	'0' => '୦', # &#x0b66;
	'1' => '୧', # &#x0b67;
	'2' => '୨', # &#x0b68;
	'3' => '୩', # &#x0b69;
	'4' => '୪', # &#x0b6a;
	'5' => '୫', # &#x0b6b;
	'6' => '୬', # &#x0b6c;
	'7' => '୭', # &#x0b6d;
	'8' => '୮', # &#x0b6e;
	'9' => '୯', # &#x0b6f;
);

$messages = array(
# User preference toggles
'tog-rememberpassword' => 'ଏହି ବ୍ରାଉଜରରେ (ସବୁଠୁ ଅଧିକ ହେଲେ $1 {{PLURAL:$1|day|ଦିନ}}) ପାଇଁ ମୋ ଲଗଇନ ମନେ ରଖିଥିବେ',

'underline-always' => 'ସବୁବେଳେ',
'underline-never'  => 'କେବେନୁହେଁ',

# Font style option in Special:Preferences
'editfont-sansserif' => 'ସାନସ-ସେରିଫ ଫଣ୍ଟ',
'editfont-serif'     => 'ସେରିଫ ଫଣ୍ଟ',

# Dates
'sunday'        => 'ରବିବାର',
'monday'        => 'ସୋମବାର',
'tuesday'       => 'ମଙ୍ଗଳବାର',
'wednesday'     => 'ବୁଧବାର',
'thursday'      => 'ଗୁରୁବାର',
'friday'        => 'ଶୁକ୍ରବାର',
'saturday'      => 'ଶନିବାର',
'sun'           => 'ରବିବାର',
'mon'           => 'ସୋମବାର',
'tue'           => 'ମଙ୍ଗଳବାର',
'wed'           => 'ବୁଧବାର',
'thu'           => 'ଗୁରୁବାର',
'fri'           => 'ଶୁକ୍ରବାର',
'sat'           => 'ଶନିବାର',
'january'       => 'ଜାନୁଆରି',
'february'      => 'ଫେବ୍ରୁଆରି',
'march'         => 'ମାର୍ଚ',
'april'         => 'ଏପ୍ରିଲ',
'may_long'      => 'ମେ',
'june'          => 'ଜୁନ',
'july'          => 'ଜୁଲାଇ',
'august'        => 'ଅଗଷ୍ଟ',
'september'     => 'ସେପଟେମ୍ବର',
'october'       => 'ଅକଟୋବର',
'november'      => 'ନଭେମ୍ବର',
'december'      => 'ଡିସେମ୍ବର',
'january-gen'   => 'ଜାନୁଆରି',
'february-gen'  => 'ଫେବ୍ରୁଆରି',
'march-gen'     => 'ମାର୍ଚ',
'april-gen'     => 'ଏପ୍ରିଲ',
'may-gen'       => 'ମେ',
'june-gen'      => 'ଜୁନ',
'july-gen'      => 'ଜୁଲାଇ',
'august-gen'    => 'ଅଗଷ୍ଟ',
'september-gen' => 'ସେପ୍ଟେଁବର',
'october-gen'   => 'ଅକଟୋବର',
'november-gen'  => 'ନଭେଁବର',
'december-gen'  => 'ଡିସେଁବର',
'jan'           => 'ଜାନୁଆରି',
'feb'           => 'ଫେବୃଆରି',
'mar'           => 'ମାର୍ଚ',
'apr'           => 'ଏପ୍ରିଲ',
'may'           => 'ମେ',
'jun'           => 'ଜୁନ',
'jul'           => 'ଜୁଲାଇ',
'aug'           => 'ଅଗଷ୍ଟ',
'sep'           => 'ସେପଟେଁବର',
'oct'           => 'ଅକଟୋବର',
'nov'           => 'ନଭେଁବର',
'dec'           => 'ଡିସେଁବର',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Category|ଶ୍ରେଣୀସବୁ}}',
'category_header'          => '"$1" ବିଭାଗରେ ଥିବା ଫରଦଗୁଡ଼ିକ',
'subcategories'            => 'ସାନ ଶ୍ରେଣୀସବୁ',
'category-media-header'    => '"$1" ବିଭାଗରେ ଥିବା ଫରଦଗୁଡ଼ିକ',
'category-empty'           => "''ଏହି ଶ୍ରେଣୀ ଭିତରେ କିଛି ପୃଷ୍ଠା ବା ମାଧ୍ୟମ ନାହିଁ ।''",
'hidden-categories'        => '{{PLURAL:$1|Hidden category|ଲୁଚିଥିବା ଶ୍ରେଣୀ}}',
'hidden-category-category' => 'ଲୁଚିରହିଥିବା ଶ୍ରେଣୀ',
'category-subcat-count'    => '{{PLURAL:$2|ଏହି ଶ୍ରେଣୀଟିରେ କେବଳ ତଳେଥିବା ସାନ ଶ୍ରେଣୀଗୁଡିକ ଅଛନ୍ତି । |ଏହି ଶ୍ରେଣୀଟିରେ ସର୍ବମୋଟ $2 ରୁ ତଳେଥିବା ଏହି {{PLURAL:$1|subcategory|$1 ଶ୍ରେଣୀଗୁଡିକ}} ଅଛନ୍ତି  । }}',
'category-article-count'   => '{{PLURAL:$2|ଏହି ଶ୍ରେଣୀରେ ତଳେଥିବ ପୃଷ୍ଠାସବୁ ଅଛି ।|ସର୍ବମୋଟ $2 ରୁ ଏହି ଶ୍ରେଣୀ ଭିତରେ {{PLURAL:$1|ଟି ପୃଷ୍ଠା|$1ଟି ପୃଷ୍ଠା}} ଅଛି ।}}',
'listingcontinuesabbrev'   => 'ଆହୁରି ଅଛି..',

'about'         => 'ବାବଦରେ',
'article'       => 'ସୂଚୀପତ୍ର',
'newwindow'     => '(ଏହା ନୂଆ ଉଇଣ୍ଡୋରେ ଖୋଲିବ)',
'cancel'        => 'ନାକଚ କରିଦିଅନ୍ତୁ',
'moredotdotdot' => 'ଅଧିକ...',
'mypage'        => 'ମୋ ପୃଷ୍ଠା',
'mytalk'        => 'ମୋ ଆଲୋଚନା',
'anontalk'      => 'ଏହି ଆଇ.ପି. ଠିକଣା ଉପରେ ଆଲୋଚନା',
'navigation'    => 'ଦିଗବାରେଣି',
'and'           => '&#32;ଓ',

# Cologne Blue skin
'qbfind'         => 'ଖୋଜିବା',
'qbbrowse'       => 'ଖୋଜିବା',
'qbedit'         => 'ବଦଳାଇବା',
'qbpageoptions'  => 'ଏଇଟା ଫର୍ଦ',
'qbpageinfo'     => 'ଭିତର ଚିଜ',
'qbmyoptions'    => 'ମୋର ଫର୍ଦ',
'qbspecialpages' => 'ନିଆରା ପୃଷ୍ଠା',

# Vector skin
'vector-action-addsection'       => 'ଲେଖା ମିଶାଇବା',
'vector-action-delete'           => 'ଲିଭେଇବେ',
'vector-action-move'             => 'ଘୁଞ୍ଚାଇବେ',
'vector-action-protect'          => 'କିଳିବେ',
'vector-action-undelete'         => 'ଲିଭାଇବେ ନାହିଁ',
'vector-action-unprotect'        => 'କିଳିବେ ନାହିଁ',
'vector-simplesearch-preference' => 'ଆହୁରି ଅଧିକ ଖୋଜା ମତାମତ ଗୁଡ଼ିକ ସଚଳ କରିବେ (କେବଳ ଭେକ୍ଟର ସ୍କିନ)',
'vector-view-create'             => 'ତିଆରି',
'vector-view-edit'               => 'ବଦଳାଇବେ',
'vector-view-history'            => 'ଇତିହାସ ଦେଖିବେ',
'vector-view-view'               => 'ପଢ଼ିବେ',
'vector-view-viewsource'         => 'ଉତ୍ସ ଦେଖିବେ',
'actions'                        => 'କାମ',
'namespaces'                     => 'ନେମସ୍ପେସ',
'variants'                       => 'ନିଆରା',

'errorpagetitle'    => 'ଭୁଲ',
'returnto'          => '$1କୁ ଫେରିଯାନ୍ତୁ ।',
'tagline'           => '{{SITENAME}} ରୁ',
'help'              => 'ସାହାଯ୍ୟ',
'search'            => 'ଖୋଜିବେ',
'searchbutton'      => 'ଖୋଜିବେ',
'go'                => 'ଯିବା',
'searcharticle'     => 'ଯିବା',
'history'           => 'ଫାଇଲ ଇତିହାସ',
'history_short'     => 'ଇତିହାସ',
'info_short'        => 'ଖବର',
'printableversion'  => 'ଛପାହୋଇପାରିବା ପୃଷ୍ଠା',
'permalink'         => 'ସବୁଦିନିଆ ଲିଙ୍କ',
'print'             => 'ପ୍ରିଣ୍ଟ କରିବା',
'edit'              => 'ବଦଳାଇବେ',
'create'            => 'ତିଆରି କରିବେ',
'editthispage'      => 'ଏହି ଫରଦଟିକୁ ବଦଳାଇବା',
'create-this-page'  => 'ଏହି ପୃଷ୍ଠାଟି ତିଆରିବେ',
'delete'            => 'ଲିଭେଇବେ',
'deletethispage'    => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇବେ',
'protect'           => 'କିଳିବେ',
'protect_change'    => 'ବଦଳାଇବା',
'newpage'           => 'ନୂଆ ପୃଷ୍ଠା',
'talkpagelinktext'  => 'କଥାଭାଷା',
'specialpage'       => 'ନିଆରା ପୃଷ୍ଠା',
'personaltools'     => 'ନିଜର ଟୁଲ',
'talk'              => 'ଆଲୋଚନା',
'views'             => 'ଦେଖା',
'toolbox'           => 'ଜନ୍ତ୍ର ପେଡ଼ି',
'otherlanguages'    => 'ଅଲଗା ଭାଷା',
'redirectedfrom'    => '($1 ରୁ ଲେଉଟି ଆସିଛି)',
'lastmodifiedat'    => 'ଏହି ପୃଷ୍ଠାଟି $1 ତାରିଖ $2 ବେଳେ ବଦଳାଯାଇଥିଲା ।',
'jumpto'            => 'ଡେଇଁଯିବେ',
'jumptonavigation'  => 'ଦିଗବାରେଣିକୁ',
'jumptosearch'      => 'ଖୋଜିବେ',
'pool-errorunknown' => 'ଅଜଣା ଅସୁବିଧା',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} ବାବଦରେ',
'aboutpage'            => 'Project:ବାବଦରେ',
'copyright'            => '$1 ରେ ସର୍ବସ୍ଵତ୍ଵ ସଂରକ୍ଷିତ',
'copyrightpage'        => '{{ns:project}}:କପିରାଇଟ',
'currentevents'        => 'ଏବେକାର ଘଟଣା',
'currentevents-url'    => 'Project:ଏବେକାର ଘଟଣା',
'disclaimers'          => 'ଆମେ ଦାୟୀ ନୋହୁଁ',
'disclaimerpage'       => 'Project:ଆମେ ଦାୟୀ ନୋହୁଁ',
'edithelp'             => 'ଲେଖା ସାହାଯ୍ୟ',
'edithelppage'         => 'Help:ବଦଳାଇବା',
'helppage'             => 'Help:ଭିତର ଚିଜ',
'mainpage'             => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'mainpage-description' => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'policy-url'           => 'Project:ନୀତି',
'portal'               => 'ସଙ୍ଘ ଆଲୋଚନା ସଭା',
'portal-url'           => 'Project:ସଙ୍ଘ ଆଲୋଚନା ସଭା',
'privacy'              => 'ଗୁମର ନୀତି',
'privacypage'          => 'Project:ଗୁମର ନୀତି',

'badaccess' => 'ଅନୁମତି ମିଳିବାରେ ଅସୁବିଧା',

'ok'                      => 'ଠିକ ଅଛି',
'retrievedfrom'           => '"$1" ରୁ ଅଣାଯାଇଅଛି',
'youhavenewmessages'      => 'ଆପଣଙ୍କର $1 ($2).',
'newmessageslink'         => 'ନୂଆ ମେସେଜ',
'newmessagesdifflink'     => 'ଶେଷ ବଦଳ',
'youhavenewmessagesmulti' => '$1 ତାରିଖରେ ନୂଆ ଚିଠିଟିଏ ଆସିଛି',
'editsection'             => 'ବଦଳାଇବେ',
'editold'                 => 'ବଦଳାଇବା',
'viewsourceold'           => 'ଉତ୍ସ ଦେଖିବେ',
'editlink'                => 'ବଦଳାଇବେ',
'viewsourcelink'          => 'ଉତ୍ସ ଦେଖିବେ',
'editsectionhint'         => '$1 ଭାଗଟିକୁ ବଦଳାଇବେ',
'toc'                     => 'ଭିତର ଚିଜ',
'showtoc'                 => 'ଦେଖାଇବେ',
'hidetoc'                 => 'ଲୁଚାଇବେ',
'site-rss-feed'           => '$1 ଆରେସେସ ଫିଡ଼',
'site-atom-feed'          => '$1 ଆଟମ ଫିଡ଼',
'page-rss-feed'           => '$1 ଟି ଆରେସେସ ଫିଡ଼',
'page-atom-feed'          => '$1 ଟି ଆଟମ ଫିଡ଼',
'red-link-title'          => ' $1 (ପୃଷ୍ଠାଟି ନାହିଁ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ପୃଷ୍ଠା',
'nstab-user'      => 'ବ୍ୟବାହରକାରୀଙ୍କର ପୃଷ୍ଠା',
'nstab-media'     => 'ମେଡିଆ ପରଦ',
'nstab-special'   => 'ନିଆରା ପୃଷ୍ଠା',
'nstab-project'   => 'ପ୍ରକଳ୍ପ ପୃଷ୍ଠା',
'nstab-image'     => 'ଫାଇଲ',
'nstab-mediawiki' => 'ଖବର',
'nstab-template'  => 'ଟେମ୍ପଲେଟ',
'nstab-category'  => 'ଶ୍ରେଣୀ:',

# General errors
'error'           => 'ଭୁଲ',
'missing-article' => 'ଡାଟାବେସଟି ଆପଣ ଖୋଜିଥିବା "$1" $2 ଶବ୍ଦଟି ପାଇଲା ନାହିଁ । .

ଯଦି ଆପଣ ଖୋଜିଥିବା ପୃଷ୍ଠାଟି କେହି ଉଡ଼ାଇ ଦେଇଥାଏ ତେବେ ଏମିତି ହୋଇପାରେ ।

ଯଦି ସେମିତି ହୋଇନଥାଏ ତେବେ ଆପଣ ଏହି ସଫଟବେରରେ କିଛି ଅସୁବିଧା ଖୋଜି ପାଇଛନ୍ତି ।
କେହି ଜଣେ ଟିକେ [[Special:ListUsers/sysop|ପରିଛା]] ଙ୍କୁ ଏହି ଇଉଆରେଲ (url) ସହ ଚିଠିଟିଏ ପଠାଇ ଦିଅନ୍ତୁ ।',
'badtitle'        => 'ଖରାପ ନାଆଁ',
'badtitletext'    => 'ଆପଣ ଅନୁରୋଧ କରିଥିବା ପୃଷ୍ଠାଟି ଭୁଲ, ଖାଲି ଅଛି ବା ବାକି ଭାଷା ସାଙ୍ଗରେ ଭୁଲରେ ଯୋଡା ଯାଇଛି ବା ଭୁଲ ଇଣ୍ଟର ଉଇକି ନାଆଁ ଦିଅଯାଇଛି ।
ଏଥିରେ ଥିବା ଗୋଟିଏ ବା ଦୁଇଟି ଅକ୍ଷର ନାଆଁ ଭାବରେ ବ୍ୟବହାର କରାଯାଇ ପାରିବ ନାହିଁ ।',
'viewsource'      => 'ଉତ୍ସ ଦେଖିବେ',

# Login and logout pages
'logouttext'              => "'''ଆପଣ ଲଗାଆଉଟ କରିଦେଲେ'''

ଆପଣ ଅଜଣା ଭାବରେ {{SITENAME}}କୁ ଯାଇପାରିବେ, କିମ୍ବା [[Special:UserLogin|ଆଉଥରେ]] ଆଗର ଇଉଜର ନାଆଁରେ/ଅଲଗା ନାଆଁରେ ଲଗଇନ କରିପାରିବେ ।
ଜାଣିରଖନ୍ତୁ, କିଛି ପୃଷ୍ଠା ଲଗାଆଉଟ କଲାପରେ ବି ଆଗପରି ଦେଖାଯାଇପାରେ, ଆପଣ ବ୍ରାଉଜର କାସକୁ ହଟାଇଲା ଯାଏଁ ଏହା ଏମିତି ରହିବ ।",
'welcomecreation'         => '== $1!, ଆପଣଙ୍କ ଖାତାଟି ତିଆରି ହୋଇଗଲା==
ତେବେ, ନିଜର [[Special:Preferences|{{SITENAME}} ପସନ୍ଦସବୁକୁ]] ବଦଳାଇବାକୁ ଭୁଲିବେ ନାହିଁ ।',
'yourname'                => 'ବ୍ୟବାହରକାରୀଙ୍କର ନାଆଁ:',
'yourpassword'            => 'ପାସବାର୍ଡ଼',
'yourpasswordagain'       => 'ପାସବାର୍ଡ଼ ଆଉଥରେ:',
'remembermypassword'      => 'ଏହି ବ୍ରାଉଜରରେ (ସବୁଠୁ ଅଧିକ ହେଲେ $1 {{PLURAL:$1|day|ଦିନ}}) ପାଇଁ ମୋ ଲଗଇନ ମନେ ରଖିଥିବେ',
'login'                   => 'ଲଗଇନ',
'nav-login-createaccount' => 'ଲଗିନ / ଖାତା ଖୋଲିବା',
'loginprompt'             => "{{SITENAME}}ରେ ଲଗ ଇନ କରିବାପାଇଁ ଆପଣଙ୍କୁ '''କୁକି''' ସଚଳ କରିବାକୁ ପଡ଼ିବ ।",
'userlogin'               => 'ଲଗିନ / ଖାତା ଖୋଲିବା',
'userloginnocreate'       => 'ଲଗଇନ',
'logout'                  => 'ଲଗଆଉଟ',
'userlogout'              => 'ଲଗ ଆଉଟ',
'nologin'                 => 'ଖାତାଟିଏ ନାହିଁ? $1।',
'nologinlink'             => 'ନୁଆ ଖାତାଟିଏ ଖୋଲିବା',
'createaccount'           => 'ନୁଆ ଖାତା ଖୋଲିବା',
'gotaccountlink'          => 'ଲଗଇନ',
'createaccountreason'     => 'କାରଣ:',
'loginerror'              => 'ଲଗ‌‌ଇନ ଭୁଲ',
'loginsuccesstitle'       => 'ଠିକଭାବେ ଲଗଇନ ହେଲା',
'loginsuccess'            => "'''ଆପଣ {{SITENAME}}ରେ \"\$1\" ଭାବରେ ଲଗଇନ କରିଛନ୍ତି ।'''",
'wrongpasswordempty'      => 'ଦିଆଯାଇଥିବା ପାସବାର୍ଡ଼ଟି ଖାଲି ଛଡ଼ାଯାଇଛି ।</br>
ଦୟାକରି ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'mailmypassword'          => 'ପାସବାର୍ଡ଼ଟିକୁ ଇମେଲ କରି ପଠାଇବେ',
'emailconfirmlink'        => 'ଆପଣଙ୍କ ଇମେଲ ଆଇ.ଡି.ଟି ଠିକ ବୋଲି ଥୟ କରନ୍ତୁ',
'accountcreated'          => 'ଖାତାଟି ଖୋଲାହୋଇଗଲା',
'accountcreatedtext'      => '$1 ପାଇଁ ନୂଆ ଖାତାଟିଏ ତିଆରି ହୋଇଗଲା ।',
'loginlanguagelabel'      => 'ଭାଷା: $1',

# Password reset dialog
'oldpassword' => 'ପୁରୁଣା ପାସଉଆଡ଼:',
'newpassword' => 'ନୂଆ ପାସବାର୍ଡ଼:',
'retypenew'   => 'ପାସବାର୍ଡ଼ ଆଉଥରେ ଦିଅନ୍ତୁ:',

# Edit page toolbar
'bold_sample'     => 'ବୋଲ୍ଡ ଲେଖା',
'bold_tip'        => 'ବୋଲ୍ଡ ଲେଖା',
'italic_sample'   => 'ତେରେଛା ଲେଖା',
'italic_tip'      => 'ତେରେଛା ଲେଖା',
'link_sample'     => 'ଲିଁକ ଟାଇଟଲ',
'link_tip'        => 'ଭିତର ଲିଙ୍କ',
'extlink_sample'  => 'http://www.example.com ଲିଁକ ଟାଇଟଲ',
'extlink_tip'     => 'ବାହାର ଲିଁକ (http:// ଆଗରେ ଲଗାଇବାକୁ ମନେରଖିଥିବେ)',
'headline_sample' => 'ହେଡଲାଇନ ଲେଖା',
'headline_tip'    => '୨କ ଆକାରର ମୂଳଧାଡ଼ି',
'math_sample'     => 'ଏଠି ଫରମୁଲା ପୁରାଅ',
'math_tip'        => 'ଗାଣିତିକ ସୁତର (ଲାଟେକ୍ସ)',
'nowiki_sample'   => 'ଫର୍ମାଟ ହୋଇ ନଥିବା ଲେଖା ଏଠାରେ ପୁରାଇବେ',
'nowiki_tip'      => 'ଉଇକି ଫରମାଟିଁଗକୁ ଛାଡିଦିଅ',
'image_tip'       => 'ଏମବେଡ ହୋଇ ଥିବା ଫାଇଲ',
'media_tip'       => 'ଫାଇଲର ଲିଁକ',
'sig_tip'         => 'ଲେଖାର ବେଳ ସହ ଆପଣଁକ ହସ୍ତାକ୍ଷର',
'hr_tip'          => 'ସମାନ୍ତରାଳ ରେଖା (ବେଳେବେଳେ ବ୍ୟବହାର କରିବେ)',

# Edit pages
'summary'                => 'ସାରକଥା:',
'subject'                => 'ବିଷୟ/ମୂଳ ଲେଖା',
'minoredit'              => 'ଏହା ଖୁବ ଛୋଟ ବଦଳଟିଏ',
'watchthis'              => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଦେଖିବେ',
'savearticle'            => 'ସାଇତିବେ',
'preview'                => 'ସାଇତିବା ଆଗରୁ ଦେଖଣା',
'showpreview'            => 'ଦେଖଣା',
'showdiff'               => 'ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'anoneditwarning'        => "'''ଜାଣିରଖନ୍ତୁ:''' ଆପଣ ଲଗଇନ କରିନାହାନ୍ତି ।
ଏହି ଫରଦର '''ଇତିହାସ''' ପୃଷ୍ଠାରେ ଆପଣଙ୍କ ଆଇପି ଠିକଣାଟି ସାଇତା ହୋଇଯିବ ।",
'summary-preview'        => 'ସାରକଥା ଦେଖଣା:',
'loginreqlink'           => 'ଲଗଇନ',
'newarticle'             => '(ନୁଆ)',
'newarticletext'         => "ଆପଣ ଖୋଲିଥିବା ଲିଙ୍କଟିରେ ଏଯାଏଁ କିଛିବି ପୃଷ୍ଠା ନାହିଁ ।
ଏହି ପୃଷ୍ଠାଟିକୁ ତିଆରି କରିବା ପାଇଁ ତଳ ବାକ୍ସରେ ଟାଇପ କରନ୍ତୁ (ଅଧିକ ଜାଣିବା ପାଇଁ [[{{MediaWiki:Helppage}}|ସାହାଯ୍ୟ ପୃଷ୍ଠା]] ଦେଖନ୍ତୁ) ।
ଯଦି ଆପଣ ଏଠାକୁ ଭୁଲରେ ଆସିଯାଇଥାନ୍ତି ତେବେ ଆପଣଙ୍କ ବ୍ରାଉଜରର '''Back''' ପତିଟି ଦବାନ୍ତୁ ।",
'noarticletext'          => 'ଏହି ପୃଷ୍ଠାଟିରେ କିଛି ବି ଲେଖା ନାହିଁ ।
ଆପଣ [[Special:Search/{{PAGENAME}}|ଏହି ଲେଖାଟିର ନାଆଁ]] ବାକି ପୃଷ୍ଠାମାନଙ୍କରେ ଖୋଜି ପାରନ୍ତି,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}ରେ ଯୋଡ଼ାଯାଇଥିବା ବାକି ପୃଷ୍ଠାସବୁକୁ ଖୋଜି ପାରନ୍ତି],
କିମ୍ବା [{{fullurl:{{FULLPAGENAME}}|action=edit}} ଏହି ପୃଷ୍ଠାଟିକୁ ବଦଳାଇ ପାରନ୍ତି]</span> ।',
'previewnote'            => "'''ଜାଣିରଖନ୍ତୁ ଯେ, ଏହା କେବଳ ଏକ ଦେଖଣା ।'''
ଆପଣ କରିଥିବା ବଦଳସବୁ ଏଯାଏଁ ସାଇତା ଯାଇନାହିଁ!",
'editing'                => '$1 କୁ ବଦଳାଉଛି',
'editingsection'         => '$1 (ଭାଗ)କୁ ବଦଳାଇବେ',
'editconflict'           => 'ବଦଳାଇବା ଦ୍ଵନ୍ଦ: $1',
'explainconflict'        => "ଆପଣ ବଦଳାଇବା ଆରମ୍ଭ କରିବା ଭିତରେ କେହିଜଣେ ଏହି ପୃଷ୍ଠାକୁ ବଦଳାଇଛନ୍ତି ।</br>
ଉପର ଲେଖା ଜାଗାଟି ଏହା ଯେମିତି ଅଛି ସେମିତି ଥିବା ଲେଖାଟି ଦେଖାଉଛି ।</br>
ତଳ ଜାଗାଟିରେ ଆପଣ କରିଥିବା ବଦଳ ଦେଖାଉଛି ।</br>
ଏବେ ଥିବା ଲେଖାରେ ଆପଣଙ୍କୁ ନିଜ ବଦଳକୁ ମିଶାଇବାକୁ ହେବ ।</br>
ଯଦି ଆପଣ \"{{int:savearticle}}\" ଦବାନ୍ତି ତେବେ '''କେବଳ''' ଉପର ଲେଖାଟି ସାଇତା ହୋଇଯିବ ।",
'titleprotectedwarning'  => "'''ଚେତାବନୀ: ଏହି ପୃଷ୍ଠାଟି କିଳାଯାଇଅଛି ଯାହାକୁ ତିଆରିବା ପାଇଁ [[Special:ListGroupRights|ବିଶେଷ କ୍ଷମତା]] ଥିବା ବ୍ୟବାହାରକାରୀ ଲୋଡ଼ା ।'''
ଆପଣଙ୍କ ସୁବିଧା ପାଇଁ ତଳେ ନଗଦ ଲଗ ପ୍ରବେଶ ଦିଆଗଲା:",
'templatesused'          => 'ଏହି ଫରଦରେ ବ୍ୟବହାର କରାଯାଇଥିବା {{PLURAL:$1|ଟେମ୍ପଲେଟ|ଟେମ୍ପଲେଟସବୁ}}:',
'templatesusedpreview'   => 'ଏହି ଫରଦରେ ବ୍ୟବହାର କରାଯାଇଥିବା {{PLURAL:$1|ଟେମ୍ପଲେଟ|ଟେମ୍ପଲେଟସବୁ}}:',
'template-protected'     => '(କିଳାଯାଇଥିବା)',
'template-semiprotected' => '(ଅଧା କିଳାଯାଇଥିବା)',
'edit-conflict'          => 'ବଦଳାଇବା ଦ୍ଵନ୍ଦ.',

# History pages
'viewpagelogs'           => 'ଏହି ପୃଷ୍ଠା ପାଇଁ ଲଗଗୁଡ଼ିକୁ ଦେଖନ୍ତୁ ।',
'currentrev-asof'        => '$1 ହୋଇଥିବା ରିଭିଜନ',
'revisionasof'           => '$1 ଅନୁସାରେ କରାଯାଇଥିବା ବଦଳ',
'previousrevision'       => 'ପୁରୁଣା ସଙ୍କଳନ',
'nextrevision'           => 'ନୂଆ ସଙ୍କଳନ',
'currentrevisionlink'    => 'ନଗଦ ସଙ୍କଳନ',
'cur'                    => 'ଦାନକର',
'next'                   => 'ପରେ',
'last'                   => 'ଆଗ',
'page_first'             => 'ପ୍ରଥମ',
'histlegend'             => "ଅଲଗା ବଛା:ସଙ୍କଳନ ସବୁର ରେଡ଼ିଓ ବାକ୍ସକୁ ବାଛି ତୁଳନା କରନ୍ତୁ ଓ ଏଣ୍ଟର ଦବାନ୍ତୁ ବା ତଳେ ଥିବା ବଟନ ଦବାନ୍ତୁ ।<br />
ସାରକଥା: '''({{int:cur}})''' = ନଗଦ ସଙ୍କଳନରେ ଥିବା ତଫାତ, '''({{int:last}})''' = ଆଗ ସଙ୍କଳନ ଭିତରେ ତଫାତ, '''{{int:minoreditletter}}''' = ଟିକେ ବଦଳ ।",
'history-fieldset-title' => 'ଇତିହାସ ଖୋଜିବା',
'histfirst'              => 'ସବୁଠୁ ପୁରୁଣା',
'histlast'               => 'ନଗଦ',
'historyempty'           => '(ଖାଲି)',

# Revision feed
'history-feed-description' => 'ଉଇକିରେ ଏହି ପୃଷ୍ଠାଟିପାଇଁ ସଙ୍କଳନ ଇତିହାସ',

# Revision deletion
'rev-delundel'   => 'ଦେଖାଇବା/ଲୁଚାଇବା',
'revdelete-log'  => 'କାରଣ:',
'revdel-restore' => 'ଦେଖଣାକୁ ବଦଳାଇବେ',

# History merging
'mergehistory-reason' => 'କାରଣ:',

# Merge log
'revertmerge' => 'ମିଶାଇବା ନାହିଁ',

# Diffs
'history-title'           => '"$1" ପାଇଁ ସଙ୍କଳନ ଇତିହାସ',
'difference'              => '(ରିଭିଜନ ଭିତରେ ଥିବା ତଫାତ)',
'lineno'                  => '$1 କ ଧାଡ଼ି:',
'compareselectedversions' => 'ବଛାହୋଇଥିବା ସଙ୍କଳନ ଗୁଡ଼ିକୁ ତଉଲ',
'editundo'                => 'ପଛକୁ ଫେରିବା',

# Search results
'searchresults'                    => 'ଖୋଜିବାରୁ ମିଳିଲା',
'searchresults-title'              => '"$1" ପାଇଁ ଖୋଜିବାରୁ ମିଳିଲା',
'searchresulttext'                 => '{{SITENAME}} ରେ ଖୋଜିବା ବାବଦରେ ଅଧିକ ଜାଣିବା ପାଇଁ,  [[{{MediaWiki:Helppage}}|{{int:help}}]] ଦେଖନ୍ତୁ',
'searchsubtitle'                   => 'ଆପଣ  \'\'\'[[:$1]]\'\'\' ପାଇଁ ([[Special:Prefixindex/$1|"$1" ନାଆଁରେ ଆରମ୍ଭ ହୋଇଥିବା ସବୁ ପୃଷ୍ଠା]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" କୁ ଯୋଡ଼ାଥିବା ସବୁତକ ପୃଷ୍ଠା]])',
'searchsubtitleinvalid'            => "ଆପଣ '''$1''' ଖୋଜିଥିଲେ",
'notitlematches'                   => 'ପୃଷ୍ଠାଟିର ନାଆଁ ମିଶୁନାହିଁ',
'notextmatches'                    => 'ପୃଷ୍ଠାଟିର ନାଆଁ ମିଶୁନାହିଁ',
'prevn'                            => '{{PLURAL:$1|$1}}ର ଆଗରୁ',
'nextn'                            => '{{PLURAL:$1|$1}} ପର',
'prevn-title'                      => 'ଆଗରୁ ମିଳିଥିବା $1ଟି  {{PLURAL:$1|result|ଫଳ}}',
'nextn-title'                      => 'ଆଗର $1ଟି  {{PLURAL:$1|result|ଫଳସବୁ}}',
'shown-title'                      => '$1 ପ୍ରତି ପୃଷ୍ଠାର {{PLURAL:$1|ଫଳାଫଳ|ଫଳାଫଳ}} ଦେଖାଇବେ ।',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2) ($3) ଟି ଦେଖିବେ',
'searchmenu-new'                   => "'''\"[[:\$1]]\"ଟି ଏହି ଉଇକିରେ ତିଆରି କରିବେ!'''",
'searchprofile-articles'           => 'ସୂଚୀ ପୃଷ୍ଠା',
'searchprofile-project'            => 'ସାହାଯ୍ୟ ଓ ପ୍ରକଳ୍ପ ପୃଷ୍ଠା',
'searchprofile-images'             => 'ମଲ୍ଟିମିଡ଼ିଆ',
'searchprofile-everything'         => 'ସବୁକିଛି',
'searchprofile-advanced'           => 'ଉନ୍ନତ',
'searchprofile-articles-tooltip'   => '$1ରେ ଖୋଜିବେ',
'searchprofile-project-tooltip'    => '$1ରେ ଖୋଜିବେ',
'searchprofile-images-tooltip'     => 'ଫାଇଲ ସବୁ ପାଇଁ ଖୋଜିବେ',
'searchprofile-everything-tooltip' => 'ପ୍ରସଙ୍ଗ ସବୁକୁ ଖୋଜିବେ (ଆଲୋଚନା ସହ)',
'searchprofile-advanced-tooltip'   => 'ନିଜେ ତିଆରିକରିହେବା ଭଳି ନେମସ୍ପେସରେ ଖୋଜିବେ',
'search-result-size'               => '$1 ({{PLURAL:$2|1 ଶବ୍ଦ|$2 ଶବ୍ଦ}})',
'search-redirect'                  => '($1 କୁ ଆଗକୁ ବଢେଇନିଅ )',
'search-section'                   => '(ଭାଗ $1)',
'search-suggest'                   => 'ଆପଣ $1 ଭାବି ଖୋଜିଥିଲେ କି?',
'search-interwiki-caption'         => 'ସାଙ୍ଗରେ ଚାଲିଥିବା ବାକି ପ୍ରକଳ୍ପସବୁ',
'search-interwiki-default'         => '$1 ଫଳାଫଳ:',
'search-interwiki-more'            => '(ଅଧିକ)',
'search-mwsuggest-enabled'         => 'ମତାମତ ସହ',
'search-mwsuggest-disabled'        => 'ମତାମତ ନାହିଁ',
'searchall'                        => 'ସବୁ',
'showingresults'                   => "ତଳେ {{PLURAL:$1|'''୧''' ଟି ଫଳ ଦେଖାଉଛୁ|'''$1'''ଟି ଫଳ}} ଯାହା #'''$2'''ରେ ଆରମ୍ଭ ହୋଇଅଛି ।",
'showingresultsheader'             => "'''$4''' ପାଇଁ {{PLURAL:$5|'''$3'''ର '''$1''' ଫଳ |'''$3'''ର '''$1 - $2''' ଫଳ }}",
'nonefound'                        => "'''ଜାଣି ରଖନ୍ତୁ''': ଆପଣ ଖାଲି କିଛି ନେମସ୍ପେସକୁ ଆପେ ଆପେ ଖୋଜିପାରିବେ ।
ସବୁ ପ୍ରକାରର ଲେଖା (ଆଲୋଚନା ପୃଷ୍ଠା, ଟେମ୍ପଲେଟ ଆଦି) ଖୋଜିବା ପାଇଁ ନିଜ ପ୍ରଶ୍ନ ଆଗରେ ''all:'' ଯୋଡ଼ି ଖୋଜନ୍ତୁ, ନହେଲେ ଦରକାରି ନେମସ୍ପେସକୁ ଲେଖାର ନାଆଁ ଆଗରେ ଯୋଡ଼ି ବ୍ୟବହାର କରନ୍ତୁ ।",
'search-nonefound'                 => 'ଆପଣ ଖୋଜିଥିବା ପ୍ରଶ୍ନ ପାଇଁ କିଛି ଫଳ ମିଳିଲା ନାହିଁ ।',
'powersearch'                      => 'ଗହିର ଖୋଜା',
'powersearch-legend'               => 'ଗହିର ଖୋଜା',
'powersearch-ns'                   => 'ନେମସ୍ପେସରେ ଖୋଜ',
'powersearch-redir'                => 'ପଛକୁ ଲେଉଟାଯାଇଥିବା ଲେଖାଗୁଡ଼ିକର ତାଲିକା',
'powersearch-field'                => 'ଖୋଜ',
'powersearch-toggleall'            => 'ସବୁ',
'powersearch-togglenone'           => 'କିଛି ନାହିଁ',

# Quickbar
'qbsettings-none' => 'କିଛି ନାହିଁ',

# Preferences page
'preferences'              => 'ପସନ୍ଦ',
'mypreferences'            => 'ମୋ ପସନ୍ଦ',
'skin-preview'             => 'ଦେଖଣା',
'searchresultshead'        => 'ଖୋଜିବା',
'timezoneregion-africa'    => 'ଆଫ୍ରିକା',
'timezoneregion-america'   => 'ଆମେରିକା',
'timezoneregion-asia'      => 'ଏସିଆ',
'timezoneregion-australia' => 'ଅଷ୍ଟ୍ରେଲିଆ',
'timezoneregion-europe'    => 'ଇଉରୋପ',
'prefs-namespaces'         => 'ନେମସ୍ପେସ',
'prefs-files'              => 'ଫାଇଲ',
'yourlanguage'             => 'ଭାଷା:',

# User rights
'userrights-reason' => 'କାରଣ:',

# Groups
'group-sysop' => 'ପରିଛାଗଣ',

'grouppage-sysop' => '{{ns:project}}:ପରିଛା (ଆଡମିନ)',

# User rights log
'rightslog' => 'ସଭ୍ୟଙ୍କ ଅଧିକାରର ଲଗ',

# Associated actions - in the sentence "You do not have permission to X"
'action-move-subpages' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ତାହାର ଉପପୃଷ୍ଠା ସହିତ ଘୁଞ୍ଚାଇବେ ।',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|ବଦଳ|ବଦଳସବୁ}}',
'recentchanges'                   => 'ନଗଦ ବଦଳ',
'recentchanges-legend'            => 'ଏବେ କରାଯାଇଥିବା ଅଦଳବଦଳ',
'recentchanges-feed-description'  => 'ଏହି ଫିଡ଼ଟିର ନଗଦ ବଦଳ ଦେଖାଁତୁ ।',
'recentchanges-label-newpage'     => 'ଏହି ବଦଳ ନୂଆ ଫରଦଟିଏ ତିଆରିକଲା',
'recentchanges-label-minor'       => 'ଏହା ଗୋଟିଏ ଛୋଟ ବଦଳ',
'recentchanges-label-bot'         => "ଏହି ବଦଳଟି ଜଣେ '''ବଟ'''ଙ୍କ ଦେଇ କରାଯାଇଥିଲା",
'recentchanges-label-unpatrolled' => 'ଏହି ବଦଳଟିକୁ ଏ ଯାଏଁ ପରଖା ଯାଇନାହିଁ',
'rclistfrom'                      => '$1ରୁ ଆରମ୍ଭ କରି ନୂଆ ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'rcshowhideminor'                 => '$1 ଟି ଛୋଟମୋଟ ବଦଳ',
'rcshowhidebots'                  => '$1 ଜଣ ବଟ',
'rcshowhideliu'                   => '$1 ଜଣ ନାଆଁ ଲେଖାଇଥିବା ଇଉଜର',
'rcshowhideanons'                 => '$1 ଜଣ ଅଜଣା ଇଉଜର',
'rcshowhidemine'                  => '$1 ମୁଁ କରିଥିବା ବଦଳ',
'rclinks'                         => 'ଗଲା $2 ଦିନର $1 ବଦଳଗୁଡ଼ିକୁ ଦେଖାଇବେ<br />$3',
'diff'                            => 'ଅଦଳ ବଦଳ',
'hist'                            => 'ଇତିହାସ',
'hide'                            => 'ଲୁଚାଇବେ',
'show'                            => 'ଦେଖାଇବେ',
'minoreditletter'                 => 'ଟିକେ',
'newpageletter'                   => 'ନୂଆ',
'boteditletter'                   => 'ବଟ',
'rc-enhanced-expand'              => 'ପୁରା ଦେଖାଇବେ (ଜାଭାସ୍କ୍ରିପଟ ଦରକାର)',
'rc-enhanced-hide'                => 'ବେଶି କଥାସବୁ ଲୁଚାଇଦିଅ',

# Recent changes linked
'recentchangeslinked'          => 'ଏଇମାତ୍ର ବଦଳାଯାଇଥିବା ପୃଷ୍ଠାର ଲିଙ୍କ',
'recentchangeslinked-feed'     => 'ଯୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-toolbox'  => 'ଯୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-title'    => '"$1" ସାଁଗରେ ଜୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-noresult' => 'ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠା ସବୁରେ ଏଇ ସମୟସୀମା ଭିତରେ କିଛି ବଦଳାଯାଇନାହିଁ ।',
'recentchangeslinked-summary'  => "ଏଇଟି କିଛିସମୟ ଆଗରୁ ନିର୍ଦ୍ଦିଷ୍ଟ ପୃଷ୍ଠାରୁ ଲିଙ୍କ ହୋଇଥିବା ଆଉ ବଦଳାଯାଇଥିବା (ଅବା ଗୋଟିଏ ନିର୍ଦ୍ଦିଷ୍ଟ ଶ୍ରେଣୀର) ପୃଷ୍ଠାସବୁର ତାଲିକା ।  [[Special:Watchlist|ମୋର ଦେଖାତାଲିକା]]ର ପୃଷ୍ଠା ସବୁ '''ବୋଲଡ଼'''।",
'recentchangeslinked-page'     => 'ଫରଦର ନାଆଁ',
'recentchangeslinked-to'       => 'ଦିଆଯାଇଥିବା ଫରଦରେ ଯୋଡ଼ା ବାକି ଫରଦମାନଙ୍କର ବଦଳ ସବୁ ଦେଖାନ୍ତୁ ।',

# Upload
'upload'            => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'uploadlogpage'     => 'ଲଗ ଅପଲୋଡ କରିବେ',
'filedesc'          => 'ସାରକଥା',
'fileuploadsummary' => 'ସାରକଥା:',
'uploadedimage'     => '"[[$1]]" ଅପଲୋଡ କରାଗଲା',

# Special:ListFiles
'imgfile'        => 'ଫାଇଲ',
'listfiles_name' => 'ନାମ',

# File description page
'file-anchor-link'          => 'ଫାଇଲ',
'filehist'                  => 'ଫାଇଲ ଇତିହାସ',
'filehist-help'             => 'ଏହା ଫାଇଲଟି ସେତେବେଳେ ଯେମିତି ଦିଶୁଥିଲା ତାହା ଦେଖିବା ପାଇଁ ତାରିଖ/ବେଳା ଉପରେ କ୍ଲିକ କରନ୍ତୁ',
'filehist-current'          => 'ଏବେକାର',
'filehist-datetime'         => 'ତାରିଖ/ବେଳ',
'filehist-thumb'            => 'ନଖ ଦେଖଣା',
'filehist-thumbtext'        => '$1 ପରିକା ସଙ୍କଳନର ନଖଦେଖଣା',
'filehist-user'             => 'ବ୍ୟବାହାରକାରୀ',
'filehist-dimensions'       => 'ଆକାର',
'filehist-comment'          => 'ମତାମତ',
'imagelinks'                => 'ଫାଇଲର ଲିଁକସବୁ',
'linkstoimage'              => 'ଏହି ସବୁ{{PLURAL:$1|ପୃଷ୍ଠା|$1 ପୃଷ୍ଠାସବୁ}} ଏହି ଫାଇଲଟିକୁ ଯୋଡ଼ିଥାନ୍ତି:',
'sharedupload'              => 'ଏହି ଫାଇଲଟି $1 ରୁ ଆଉ ବାକି ପ୍ରକଳ୍ପରେ ବ୍ୟବହାର କରାଯାଇପାରିବ .',
'uploadnewversion-linktext' => 'ଏହି ଫାଇଲର ନୂଆ ସଙ୍କଳନଟିଏ ଅପଲୋଡ଼ କରିବେ',

# File reversion
'filerevert-comment' => 'କାରଣ:',

# File deletion
'filedelete-comment' => 'କାରଣ:',

# Random page
'randompage' => 'ଯାହିତାହି ପୃଷ୍ଠା',

# Statistics
'statistics'       => 'ହିସାବ',
'statistics-pages' => 'ପୃଷ୍ଠା',

'brokenredirects-edit' => 'ବଦଳାଇବେ',

# Miscellaneous special pages
'nbytes'               => '$1 {{PLURAL:$1|byte|ବାଇଟ}}',
'nmembers'             => '$1 {{PLURAL:$1|member|ସଭ୍ୟ}}',
'wantedcategories'     => 'ଦରକାରୀ ଶ୍ରେଣୀ',
'wantedpages'          => 'ଦରକାରି ପୃଷ୍ଠା',
'wantedpages-badtitle' => '$1 ଉତ୍ତରସବୁରେ ଥିବା ଭୁଲ ଟାଇଟଲ',
'wantedfiles'          => 'ଦରକାରି ଫାଇଲ',
'wantedtemplates'      => 'ଦରକାରୀ ଟେମ୍ପଲେଟ',
'mostlinked'           => 'ଅଧିକ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠା',
'mostlinkedcategories' => 'ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଶ୍ରେଣୀ',
'mostlinkedtemplates'  => 'ବେଶୀ ଯୋଡ଼ାଯାଇଥିବା ଟେମ୍ପଲେଟ',
'mostcategories'       => 'ଅଧିକ ଶ୍ରେଣୀ ଥିବା ପୃଷ୍ଠା',
'mostimages'           => 'ଫାଇଲରେ ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଥିବା',
'prefixindex'          => 'ଆଗରୁ କିଛି ଯୋଡ଼ା ସହ ଥିବା ସବୁ ଫରଦସବୁ',
'newpages'             => 'ନୂଆ ପୃଷ୍ଠା',
'move'                 => 'ଘୁଞ୍ଚାଇବେ',
'movethispage'         => 'ଏଇ ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'pager-newer-n'        => '{{PLURAL:$1|ନୂଆ 1|ନୂଆ $1}}',
'pager-older-n'        => '{{PLURAL:$1|ପୁରୁଣା 1|ପୁରୁଣା $1}}',

# Book sources
'booksources'               => 'ବହିର ମୁଳ ସ୍ରୋତ',
'booksources-search-legend' => 'ବହିର ସ୍ରୋତସବୁକୁ ଖୋଜିବେ',
'booksources-go'            => 'ଯିବା',

# Special:Log
'log'      => 'ଲଗ',
'logempty' => 'ଲଗରେ ଥିବା ଲେଖା ସହ ମେଳଖାଉ ନାହିଁ ।',

# Special:AllPages
'allpages'       => 'ସବୁ ପୃଷ୍ଠା',
'alphaindexline' => '$1 ରୁ $2',
'prevpage'       => 'ଆଗ ପୃଷ୍ଠା ($1)',
'allpagesfrom'   => 'ଏହି ନାଆଁରେ ଆରମ୍ଭ ହେଉଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକୁ ଦେଖାଇବେ:',
'allpagesto'     => 'ଏହି ନାଆଁରେ ଶେଷ ହେଉଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକୁ ଦେଖାଇବେ:',
'allarticles'    => 'ସବୁ ପୃଷ୍ଠା',
'allpagessubmit' => 'ଯିବେ',

# Special:DeletedContributions
'deletedcontributions' => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ସଭ୍ୟଙ୍କ ଅବଦାନ',

# Special:LinkSearch
'linksearch'    => 'ବାହାର ଲିଙ୍କ',
'linksearch-ok' => 'ଖୋଜିବା',

# Special:Log/newusers
'newuserlogpage'          => 'ବ୍ୟବହାରକାରୀ ତିଆରି ଲଗ',
'newuserlog-create-entry' => 'ନୂଆ ବ୍ୟବହାରକାରୀଙ୍କ ଖାତା',

# E-mail user
'emailuser'    => 'ଏହି ଉଇଜରଁକୁ ଇମେଲ କର',
'emailmessage' => 'ଖବର:',

# Watchlist
'watchlist'         => 'ଦେଖାତାଲିକା',
'mywatchlist'       => 'ମୋର ଦେଖାତାଲିକା',
'watch'             => 'ଦେଖିବେ',
'watchthispage'     => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଦେଖିବେ',
'unwatch'           => 'ଦେଖନାହିଁ',
'watchlist-details' => 'ଆପଣଙ୍କ ଦେଖଣା ତାଲିକାରେ ଆଲୋଚନା ପୃଷ୍ଠାକୁ ଛାଡ଼ି {{PLURAL:$1|$1 ଟି ପୃଷ୍ଠା|$1 ଟି ପୃଷ୍ଠା}} ଅଛି ।',
'watchlist-options' => 'ଦେଖଣା ବିକଳ୍ପସବୁ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ଦେଖୁଛି...',
'unwatching' => 'ଦେଖୁନାହିଁ...',

# Delete
'deletepage'            => 'ପୃଷ୍ଠାଟି ଲିଭାଇଦେବେ',
'confirmdeletetext'     => 'ଆପଣ ଗୋଟିଏ ପୃଷ୍ଠାର ଇତିହାସ ସହ ତାହାକୁ ଲିଭାଇବାକୁ ଯାଉଛନ୍ତି ।
ଏହା ଥୟ କରନ୍ତୁ ଯେ ଆପଣ ଏହାର ପରିଣତି ଜାଣିଛନ୍ତି ଓ ଏହା [[{{MediaWiki:Policy-url}}|ମିଡ଼ିଆଉଇକିର ନିୟମ]] ଅନୁସାରେ କରୁଛନ୍ତି ।',
'actioncomplete'        => 'କାମଟି ପୁରା ହେଲା',
'deletedtext'           => '"<nowiki>$1</nowiki>"କୁ ଲିଭାଇ ଦିଆଗଲା ।
ନଗଦ ଲିଭାଯାଇଥିବା ଫାଇଲର ଇତିହାସ $2ରେ ଦେଖନ୍ତୁ ।',
'deletedarticle'        => '"[[$1]]" ଟି ଉଡ଼ିଗଲା',
'dellogpage'            => 'ଲିଭାଇବା ଲଗ',
'deletecomment'         => 'କାରଣ:',
'deleteotherreason'     => 'ବାକିି କାରଣ:',
'deletereasonotherlist' => 'ବାକିି କାରଣ',

# Rollback
'rollbacklink' => 'ପୁରାପୁରି ପଛକୁ ଫେରିଯିବେ',

# Protect
'protectlogpage'              => 'କିଳିବା ଲଗ',
'protectedarticle'            => '"[[$1]]"ଟି କିଳାଗଲା',
'protectcomment'              => 'କାରଣ:',
'protectexpiry'               => 'ଅଚଳ ହେବ:',
'protect_expiry_invalid'      => 'ଅଚଳ ହେବାର ବେଳଟି ଭୁଲ.',
'protect_expiry_old'          => 'ଅଚଳ ହେବାର ବେଳ ଅତୀତରେ ଥିଲା.',
'protect-default'             => 'ସବୁ ଇଉଜରଙ୍କୁ ଅନୁମତି ଦିଅନ୍ତୁ',
'protect-fallback'            => '"$1" ବାଲା ଅନୁମତି ଦରକାର',
'protect-level-autoconfirmed' => 'ନୁଆ ଓ ନାଆଁ ଲେଖାଇ ନ ଥିବା ଇଉଜରମାନକୁ ଅଟକାଁତୁ',
'protect-level-sysop'         => 'କେବଳ ପରିଛାମାନଁକ ପାଇଁ',
'protect-summary-cascade'     => 'କାସକେଡ଼ ହୋଇଥିବା',
'protect-expiring'            => '$1 (ଇଉଟିସି)ରେ ଅଚଳ ହୋଇଯିବ',
'protect-cascade'             => 'ଏହି ଫରଦସହ ଜୋଡ଼ା ଫରଦସବୁକୁ କିଳିଦିଅ (କାସକେଡ଼କରା ସୁରକ୍ଷା)',
'protect-cantedit'            => 'ଆପଣ ଏହି ସୁରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇ ପାରିବେ ନାହିଁ, କାହିଁକି ନା ଏହାକୁ ବଦଳାଇବା ପାଇଁ ଆପଣଁକୁ ଅନୁମତି ନାହିଁ .',
'restriction-type'            => 'ଅନୁମତି',
'restriction-level'           => 'ପ୍ରତିରକ୍ଷା ସ୍ତର',

# Restrictions (nouns)
'restriction-edit'   => 'ବଦଳାଇବେ',
'restriction-move'   => 'ଘୁଞ୍ଚାଇବେ',
'restriction-create' => 'ତିଆରି',

# Undelete
'undeletelink'           => 'ଦେଖିବା/ଆଉଥରେ ଫେରାଇଆଣିବେ',
'undeleteviewlink'       => 'ଦେଖଣା',
'undeletecomment'        => 'କାରଣ:',
'undelete-search-submit' => 'ଖୋଜିବା',

# Namespace form on various pages
'namespace'      => 'ନେମସ୍ପେସ',
'invert'         => 'ବଛାଯାଇଥିବା ଲେଖାକୁ ଓଲଟେଇପକାଅ',
'blanknamespace' => '(ମୂଳ)',

# Contributions
'contributions'       => 'ବ୍ୟବହାରକାରିଙ୍କ ଦାନ',
'contributions-title' => '$1 ପାଇଁ ବ୍ୟବହାରକାରୀଙ୍କ ଦାନ',
'mycontris'           => 'ମୋ ଅବଦାନ',
'contribsub2'         => '$1 ($2) ପାଇଁ',
'month'               => 'ମାସରୁ (ଓ ତା ଆଗରୁ)',
'year'                => 'ବର୍ଷରୁ (ଆଉ ତା ଆଗରୁ)',

'sp-contributions-newbies'  => 'କେବଳ ନୂଆ ସଭ୍ୟମାନଙ୍କର ଅବଦାନ ଦେଖାଇବେ',
'sp-contributions-talk'     => 'କଥାଭାଷା',
'sp-contributions-search'   => 'ଅବଦାନ ପାଇଁ ଖୋଜନ୍ତୁ',
'sp-contributions-username' => 'ବ୍ୟବାହରକାରୀଙ୍କର ଆଇ.ପି. ଠିକଣା',
'sp-contributions-toponly'  => 'ନଗଦ ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'sp-contributions-submit'   => 'ଖୋଜିବା',

# What links here
'whatlinkshere'            => 'ଏଠି କଣ କଣ ଲିଙ୍କ ଅଛି',
'whatlinkshere-title'      => '"$1" କୁ ପୃଷ୍ଠା ଲିଙ୍କ',
'whatlinkshere-page'       => 'ପୃଷ୍ଠା:',
'linkshere'                => "ଏହି ପୃଷ୍ଠା ସବୁ  '''[[:$1]]''' ସହ ଯୋଡା ଯାଇଅଛି:",
'isredirect'               => 'ଆଉ ପୃଷ୍ଠାଟିଏକୁ ଯୋଡ଼ିବା ପୃଷ୍ଠା',
'istemplate'               => 'ବାହାର',
'isimage'                  => 'ଚିତ୍ର ଲିଁକ',
'whatlinkshere-prev'       => '{{PLURAL:$1|ଆଗ|ଆଗ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ପର|ପର $1}}',
'whatlinkshere-links'      => '← ଲିଁକ',
'whatlinkshere-hideredirs' => '$1 କୁ ଲେଉଟାଣି',
'whatlinkshere-hidetrans'  => '$1 ରେଫେରେଁସ ସହ ଭିତରେ ପୁରାଇବା',
'whatlinkshere-hidelinks'  => '$1 ଟି ଲିଁକ',
'whatlinkshere-filters'    => 'ଫିଲଟରସବୁ',

# Block/unblock
'blockip'                  => 'ସଭ୍ୟଙ୍କୁ ଅଟକାଇବେ',
'ipbreason'                => 'କାରଣ:',
'ipboptions'               => '୨ ଘଣ୍ଟା:2 hours,୧ ଦିନ:1 day,୩ ଦିନ:3 days,୧ ସପ୍ତାହ:1 week,୨ ସପ୍ତାହ:2 weeks,୧ ମାସ:1 month,୩ ମାସ:3 months,୬ ମାସ:6 months,୧ ବର୍ଷ:1 year,ଅସରନ୍ତି:infinite',
'ipblocklist-submit'       => 'ଖୋଜିବା',
'blocklink'                => 'ଅଟକେଇ ଦେବେ',
'unblocklink'              => 'ଛାଡ଼ିବା',
'change-blocklink'         => 'ଓଗଳାକୁ ବଦଳାଇବେ',
'contribslink'             => 'ଅବଦାନ',
'blocklogpage'             => 'ଲଗଟିକୁ ଅଟକାଇଦିଅ',
'unblocklogentry'          => 'କିଳାଯାଇନଥିବା $1',
'block-log-flags-nocreate' => 'ନୂଆ ଖାତା ଖୋଲିବାକୁ ଅଚଳ କରାଯାଇଅଛି',

# Move page
'move-page'               => '$1କୁ ଘୁଞ୍ଚାଇବେ',
'movepagetext'            => "ଏହି ଫର୍ମଟି ବ୍ୟବହାର କରି ଆପଣ ତଳ ପୃଷ୍ଠାଟିକୁ ବଦଳାଇ ପାରିବେ, ଏହାର ସବୁ ଇତିହାସ ଏକ ନୂଆ ନାଆଁକୁ ବଦଳିଯିବ ।
ପୁରୁଣା ନାଆଁଟି ଏକ ପୁରୁଣା ନାଆଁ ଭାବରେ ଏହି ପୃଷ୍ଠା ଭାବରେ ବାଟ କଢ଼ାଇବ ।
ଆପଣ ମୂଳ ଲେଖାକୁ ସେହି ପୁରୁଣା ନାଆଁ ଦେଇ ଆପେଆପେ ପଢ଼ିପାରିବେ ।
ଯଦି ଆପଣ ଏହା ଚାହାନ୍ତି ନାହିଁ ତେବେ [[Special:DoubleRedirects|ଦୁଇଥର ଥିବା ପୃଷ୍ଠା]] ବା [[Special:BrokenRedirects|ଭଙ୍ଗା ଆଗ ପୃଷ୍ଠା]] ଦେଖି ପାରିବେ ।

ଲିଙ୍କସବୁ କେଉଁଠିକୁ ଯାଉଛି ତାହା ପାଇଁ ଆପଣ ଦାୟୀ ନୁହନ୍ତି ।

ମନେ ରଖନ୍ତୁ, ଆଗରୁ ଏହି ଏକା ନାଆଁରେ ପୃଷ୍ଠାଟିଏ ଥିଲେ ଏହି ପୃଷ୍ଠାଟି '''ଘୁଞ୍ଚିବ ନାହିଁ''' ଯେତେ ଯାଏଁ ତାହା ଖାଲି ନାହିଁ ବା ଆଗ ପୃଷ୍ଠାଟିର କୌଣସି ବଦଳ ଇତିହାସ ନାହିଁ ସେତେ ବେଳ ଯାଏଁ ଏହା ଏମିତି ରହିବ । ଏହାର ମାନେ ହେଉଛି, ଆପଣ ଗୋଟିଏ ପୃଷ୍ଠାର ନାଆଁକୁ ତାର ପୁରୁଣା ନାଆଁ ଦେଇପାରିବେ, କିନ୍ତୁ ଆଗରୁ ଥିବା ପୃଷ୍ଠାଟି ଉପରେ ନୂଆ ପୃଷ୍ଠାଟିଏ ଚାପି ଦେଇପାରିବେ ନାହିଁ ।

'''ଜାଣି ରଖନ୍ତୁ!'''
ଏହା ଏକ ଜଣାଶୁଣା ପୃଷ୍ଠାରେ ଆମୂଳଚୂଳ ଓ ଅଜଣା ବଦଳ କରିପାରେ;
ନିଶ୍ଚିତ କରନ୍ତୁ ଆପଣ ଆଗକୁ ବଢ଼ିବା ଆଗରୁ ଏହାର ଫଳ ବାବଦରେ ଜାଣିଛନ୍ତି ।",
'movepagetalktext'        => 'ଯଦି:
*ଗୋଟିଏ ଖାଲି ଆଲୋଚନା ପୃଷ୍ଠା ସେହି ନାଆଁରେ ଥାଏ
*ଆପଣ ତଳ ବାକ୍ସକୁ ନ ବାଛନ୍ତି
ତେବେ ଏହି ପ୍ରୁଷ୍ଠା ସହ ଯୋଡାଯାଇଥିବା ଆଲୋଚନା ପ୍ରୁଷ୍ଠାକୁ ଆପେ ଆପେ ଘୁଞ୍ଚାଇଦିଆଯିବ ।
ସେହି ଯାଗାରେ, ଆପଣଙ୍କୁ ପ୍ରୁଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବାକୁ/ମିଶାଇବାକୁ ପଡ଼ିବ ।',
'cant-move-user-page'     => 'ଆପଣଙ୍କୁ ଏହି ସଭ୍ୟ ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବା ଲାଗି ଅନୁମତି ମିଳିନାହିଁ, କିନ୍ତୁ ନିଜର ଉପପୃଷ୍ଠା ସବୁ ଘୁଞ୍ଚାଇ ପାରିବେ ।',
'newtitle'                => 'ନୂଆ ନାଆଁକୁ:',
'pagemovedsub'            => 'ଘୁଞ୍ଚାଇବା ସଫଳ ହେଲା',
'movepage-moved'          => '\'\'\'"$1"ରୁ "$2"\'\'\'କୁ ଘୁଞ୍ଚାଇ ଦିଆଗଲା ।',
'movepage-moved-redirect' => 'ପୃଷ୍ଠାଟିର ନାଆଁକୁ ଘୁଞ୍ଚାଇଦିଆଗଲା ।',
'move-subpages'           => 'ଉପପୃଷ୍ଠା ଗୁଡ଼ିକୁ ଘୁଞ୍ଚାଇବେ ($1 ଯାଏଁ)',
'1movedto2'               => '[[$1]]ରୁ [[$2]]କୁ ଘୁଞ୍ଚାଗଲା',
'1movedto2_redir'         => '[[$1]]ରୁ [[$2]]କୁ ଲେଉଟାଇଦିଆଗଲା',
'movelogpage'             => 'ଲଗଟିକୁ ଘୁଞ୍ଚାଇବେ',
'movenosubpage'           => 'ଏହି ପୃଷ୍ଠାର ଉପପୃଷ୍ଠା ନାହିଁ ।',
'movereason'              => 'କାରଣ:',
'revertmove'              => 'ପଛକୁ ଫେରାଇନେବେ',

# Export
'export' => 'ଫରଦସବୁ ରପ୍ତାନି କର',

# Thumbnails
'thumbnail-more' => 'ବଡ଼କର',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ଆପଣଙ୍କ ବ୍ୟବାହାରକାରୀ ପୃଷ୍ଠା',
'tooltip-pt-mytalk'               => 'ଆପଣଙ୍କ ଆଲୋଚନା ପୃଷ୍ଠା',
'tooltip-pt-preferences'          => 'ମୋ ପସନ୍ଦ',
'tooltip-pt-watchlist'            => 'ବଦଳ ପାଇଁ ଆପଣ ଦେଖାଶୁଣା କରୁଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକର ତାଲିକା',
'tooltip-pt-mycontris'            => 'ଆପଣଙ୍କ ଅବଦାନ',
'tooltip-pt-login'                => 'ଆପଣଙ୍କୁ ଲଗିନ କରିବାକୁ କୁହାଯାଉଅଛି ସିନା, ବାଧ୍ୟ କରାଯାଉନାହିଁ',
'tooltip-pt-logout'               => 'ଲଗଆଉଟ',
'tooltip-ca-talk'                 => 'ଏହି ପୃଷ୍ଠାଟି ଉପରେ ଆଲୋଚନା',
'tooltip-ca-edit'                 => 'ଆପଣ ଏହି ପୃଷ୍ଠାଟିରେ ଅଦଳ ବଦଳ କରିପାରିବେ, ତେବେ ସାଇତିବା ଆଗରୁ ଦେଖଣା ଦେଖନ୍ତୁ ।',
'tooltip-ca-addsection'           => 'ନୂଆ ବିଭାଗଟିଏ ଆରମ୍ଭ କରିବେ',
'tooltip-ca-viewsource'           => 'ଏହି ପୃଷ୍ଠାଟି କିଳାଯାଇଛି ।
ଆପଣ ଏହାର ମୂଳ ଦେଖିପାରିବେ',
'tooltip-ca-history'              => 'ଏହି ପୃଷ୍ଠାର ପୁରୁଣା ସଂସ୍କରଣ',
'tooltip-ca-protect'              => 'ଏହି ପୃଷ୍ଠାଟିକୁ କିଳିବେ',
'tooltip-ca-delete'               => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇବେ',
'tooltip-ca-move'                 => 'ଏଇ ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'tooltip-ca-watch'                => 'ଆପଣଙ୍କ ଦେଖାତାଲିକାରେ ଏଇ ଫରଦଟି ମିଶାନ୍ତୁ',
'tooltip-ca-unwatch'              => 'ନିଜ ଦେଖଣାତାଲିକାରୁ ଏହି ଫରଦଟି ବାହାର କରିଦିଅଁତୁ',
'tooltip-search'                  => '{{SITENAME}} ରେ ଖୋଜିବା',
'tooltip-search-go'               => 'ଏହି ଅବିକଳ ନାଆଁଟି ଥିଲେ ସେହି ପୃଷ୍ଠାକୁ ଯିବା',
'tooltip-search-fulltext'         => 'ଏହି ଲେଖାଟି ପାଇଁ ପୃଷ୍ଠାସବୁକୁ ଖୋଜିବା',
'tooltip-p-logo'                  => 'ପ୍ରଧାନ ପ୍ରୁଷ୍ଟ୍ଆ',
'tooltip-n-mainpage'              => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'tooltip-n-mainpage-description'  => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'tooltip-n-portal'                => 'ଏହି ପ୍ରକଳ୍ପଟିରେ ଖୋଜା ଖୋଜି ପାଇଁ ଆପଣ କେମିତି ସାହାଯ୍ୟ କରିପାରିବେ',
'tooltip-n-currentevents'         => 'ନଗଦ କାମର ପଛପଟେ ଚାଲିଥିବା କାମର ତଥ୍ୟ',
'tooltip-n-recentchanges'         => 'ବିକିରେ ଏହିମାତ୍ର କରାଯାଇଥିବା ଅଦଳ ବଦଳ',
'tooltip-n-randompage'            => 'ଯାହିତାହି ପୃଷ୍ଠାଟିଏ ଖୋଲ',
'tooltip-n-help'                  => 'ଖୋଜି ପାଇବା ଭଳି ଜାଗା',
'tooltip-t-whatlinkshere'         => 'ଏଠାରେ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠାସବୁର ତାଲିକା',
'tooltip-t-recentchangeslinked'   => 'ଏହି ପୃଷ୍ଠା ସାଗେ ଯୋଡ଼ା ଫରଦଗୁଡ଼ିକରେ ଏଇଲାଗେ କରାଯାଇଥିବା ଅଦଳବଦଳ',
'tooltip-feed-rss'                => 'ଏହି ଫରଦଟି ପାଇଁ ଆରଏସଏସ ଫିଡ',
'tooltip-feed-atom'               => 'ଏହି ଫରଦଟି ପାଇଁ ଆଟମ ଫିଡ',
'tooltip-t-contributions'         => 'ଏହି ଇଉଜରଙ୍କର ଦେଇ କରାଯାଇଥିବା ସବୁଯାକ ଦାନ ଦେଖାଇବା',
'tooltip-t-upload'                => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'tooltip-t-specialpages'          => 'ନିଆରା ପୃଷ୍ଠା ତାଲିକା',
'tooltip-t-print'                 => 'ଏହି ପୃଷ୍ଠାର ଛପାହୋଇପାରିବା ସଙ୍କଳନ',
'tooltip-t-permalink'             => 'ବଦଳାଯାଇଥିବା ଏହି ଫରଦଟିର ସ୍ଥାୟୀ ଲିଙ୍କ',
'tooltip-ca-nstab-main'           => 'ସୂଚୀ ପୃଷ୍ଠାଟି ଦେଖିବା',
'tooltip-ca-nstab-user'           => 'ଫାଇଲ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'tooltip-ca-nstab-special'        => 'ଏଇଟି ଗୋଟିଏ ନିଆରା ପୃଷ୍ଠା, ଆପଣ ଏହାକୁ ବଦଳାଇପାରିବେ ନାହିଁ',
'tooltip-ca-nstab-project'        => 'ପ୍ରକଳ୍ପ ଫରଦଟି ଦେଖିବା',
'tooltip-ca-nstab-image'          => 'ଫାଇଲ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'tooltip-ca-nstab-template'       => 'ଟେମ୍ପଲେଟଟି ଦେଖିବା',
'tooltip-ca-nstab-category'       => 'ଶ୍ରେଣୀ ପୃଷ୍ଠାଟିକୁ ଦେଖାଇବେ',
'tooltip-minoredit'               => 'ଏହାକୁ ଛୋଟ ବଦଳ ଭାବେ ଗଣ',
'tooltip-save'                    => 'ବଦଳଗୁଡ଼ିକ ସାଇତିବେ',
'tooltip-preview'                 => 'ଆପଣନ୍କ ବଦଳ ଦେଖିନିଅନ୍ତୁ, ସାଇତିବା ଆଗରୁ ଏହା ବ୍ୟ୍ଅବହାର କରନ୍ତୁ!',
'tooltip-diff'                    => 'ଏହି ଲେଖାରେ ଆପଣ କରିଥିବା ବଦଳଗୁଡିକୁ ଦେଖନ୍ତୁ ।',
'tooltip-compareselectedversions' => 'ଏହି ଫରଦର ଦୁଇଟି ବଛାଯାଇଥିବା ସଁକଳନକୁ ତଉଲିବା',
'tooltip-watch'                   => 'ଆପଣଙ୍କ ଦେଖାତାଲିକାରେ ଏଇ ଫରଦଟି ମିଶାନ୍ତୁ',
'tooltip-rollback'                => '"ଫେରିବା" ଏହି ଫରଦରେ ଶେଷ ଦାତାଙ୍କ ଦେଇ କରାଯାଇଥିବା ସବୁଯାକ ବଦଳକୁ  ଏକାଥରକରେ ପଛକୁ ଫେରାଇଦେବ',
'tooltip-undo'                    => '"କରନାହିଁ" ଆଗରୁ କରାଯାଇଥିବା ବଦଳଟିକୁ ପଛକୁ ଲେଉଟାଇଦିଏ ଆଉ ବଦଳ ଫରମଟିକୁ ଦେଖଣା ଭାବରେ ଖୋଲେ । ଏହା ଆପଣଙ୍କୁ ସାରକଥାରେ ଗୋଟିଏ କାରଣ ଲେଖିବାକୁ ଅନୁମତି ଦିଏ ।',

# Browsing diffs
'previousdiff' => '← ପୁରୁଣା ବଦଳ',
'nextdiff'     => 'ନୂଆ ବଦଳ →',

# Media information
'file-info-size'       => '$1 × $2 ପିକସେଲ, ଫାଇଲ ଆକାର: $3, ଏମ.ଆଇ.ଏମ.ଇର ପ୍ରକାର: $4',
'file-nohires'         => '<small>ବଡ଼ ରେଜୋଲୁସନ ନାହିଁ ।</small>',
'show-big-image'       => 'ପୁରା ବଡ଼ ଆକାରରେ',
'show-big-image-thumb' => '<small>ଦେଖଣା ଚିତ୍ରର ଆକାର: $1 × $2 ପିକସେଲ</small>',

# Special:NewFiles
'ilsubmit' => 'ଖୋଜିବା',

# Bad image list
'bad_image_list' => 'ଗଢ଼ଣଟି ଏମିତି ହେବ:

କେବଳ (ଯେଉଁ ଧାଡ଼ିଗୁଡ଼ିକ * ରୁ ଆରମ୍ଭ ହୋଇଥାଏ) ସେହି ସବୁକୁ ହିସାବକୁ ନିଆଯିବ ।
ଗୋଟିଏ ଧାଡ଼ିର ପ୍ରଥମ ଲିଙ୍କଟି ଗୋଟିଏ ଖରାପ ଫାଇଲର ଲିଙ୍କ ହୋଇଥିବା ଦରକାର ।
ପ୍ରଥମ ଲିଙ୍କ ପରର ସବୁ ଲିଙ୍କକୁ ନିଆରା ବୋଲି ଧରାଯିବ । ମାନେ, ସେଇସବୁ ପୃଷ୍ଠାଦରେ ଯେଉଁଠି ଫାଇଲଟି ଧାଡ଼ି ଭିତରେ ରହିଥିବ ।',

# Metadata
'metadata'          => 'ମେଟାଡାଟା',
'metadata-help'     => 'ଏହି ଫରଦଟିରେ ଗୁଡ଼ାଏ ଅଧିକ କଥା ଅଛି, ବୋଧହୁଏ ଡିଜିଟାଲ କାମେରା କିମ୍ବା ସ୍କାନରରେ ନିଆଯାଇଛି । ଯଦି ଫାଇଲଟି ତାର ମୂଳ ଭାଗଠୁ ବଦଳାଜାଇଥାଏ ତେବେ କିଛି ଅଁଶ ଠିକ ଭାବେ ଦେଖାଯାଇ ନପାରେ ।',
'metadata-expand'   => 'ଆହୁରି ଖୋଲିକରି ଦେଖାଇବେ',
'metadata-collapse' => 'ଆହୁରି ଖୋଲିକରି ଦେଖାଇବେ',

# External editor support
'edit-externally'      => 'ଏକ ବାହାର ଆପ୍ଲିକେସନ ବ୍ୟବହାର କରି ଏହି ଫାଇଲଟିକୁ ବଦଳାଇବା',
'edit-externally-help' => '(ଆହୁରି ବି [http://www.mediawiki.org/wiki/Manual:External_editors ସଜାଡିବା ନିର୍ଦେଶ] ଦେଖନ୍ତୁ)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ସବୁ',
'imagelistall'     => 'ସବୁ',
'watchlistall2'    => 'ସବୁ',
'namespacesall'    => 'ସବୁ',
'monthsall'        => 'ସବୁ',
'limitall'         => 'ସବୁ',

# E-mail address confirmation
'confirmemail_needlogin' => 'ଆପଣଙ୍କୁ ନିଜ ଇମେଲଟିକୁ ଥୟ କରିବା ପାଇଁ $1 କରିବାକୁ ପଡ଼ିବ ।',
'confirmemail_body'      => 'କେହିଜଣେ, ବୋଧହୁଏ ଆପଣ ହିଁ $1 ଆଇ.ପି. ଠିକଣାରୁ,
ଏହି ଇ-ମେଲ ଆଇ.ଡି.ରେ "$2" ନାଆଁରେ {{SITENAME}} ଠାରେ ଖାତାଟିଏ ଖୋଲିଛନ୍ତି ।

ଏହି ଖାତାଟି ସତରେ ଆପଣଙ୍କର ବୋଲି ଥୟ କରିବା ପାଇଁ ଓ {{SITENAME}}ରେ ଇ-ମେଲ ସୁବିଧାସବୁ ସଚଳ କରିବାପାଇଁ, ଏହି ଲିଙ୍କ୍ଟିକୁ ଆପଣଙ୍କ ବ୍ରାଉଜରରେ ଖୋଲନ୍ତୁ:

$3

ଯଦି ଆପଣ ଖାତାଟିଏ ଆଗରୁ ଖୋଲି *ନାହାନ୍ତି* ତେବେ ଏହି ଲିଙ୍କକୁ ଯାଇ ଇ-ମେଲ ଆ.ଡି. ଥୟ କରିବାକୁ ନାକଚ କରିଦିଅନ୍ତୁ:

$5

ଏହି କନଫର୍ମେସନ କୋଡ଼ $4 ବେଳେ ଅଚଳ ହୋଇଯିବ ।',

# action=purge
'confirm_purge_button' => 'ଠିକ ଅଛି',

# Multipage image navigation
'imgmultigo' => 'ଯିବା!',

# Table pager
'table_pager_next'         => 'ନୂଆ ପୃଷ୍ଠା',
'table_pager_limit_submit' => 'ଯିବା',

# Auto-summaries
'autosumm-new' => '"$1" ନାଆଁରେ ପୃଷ୍ଠାଟିଏ ତିଆରିକଲେ',

# Watchlist editor
'watchlistedit-raw-titles' => 'ନାଆଁ:',

# Watchlist editing tools
'watchlisttools-view' => 'ଦରକାରୀ ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'watchlisttools-edit' => 'ଦେଖିବା ତାଲିକାଟିକୁ ଦେଖିବେ ଓ ବଦଳାଇବେ',
'watchlisttools-raw'  => 'ଫାଙ୍କା ଦେଖା ତାଲିକାଟିକୁ ଦେଖିବେ ଓ ବଦଳାଇବେ',

# Special:FilePath
'filepath-page'   => 'ଫାଇଲ:',
'filepath-submit' => 'ଯିବା',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'ଖୋଜିବା',

# Special:SpecialPages
'specialpages'             => 'ନିଆରା ପୃଷ୍ଠା',
'specialpages-group-login' => 'ଲଗିନ / ଖାତା ଖୋଲି',

# Special:Tags
'tags-edit' => 'ବଦଳାଇବେ',

# HTML forms
'htmlform-submit' => 'ଦାଖଲକରିବା',

);
