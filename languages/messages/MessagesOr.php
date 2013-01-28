<?php
/** Oriya (ଓଡ଼ିଆ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ansumang
 * @author Jayantanth
 * @author Jnanaranjan Sahu
 * @author Jose77
 * @author MKar
 * @author Odisha1
 * @author Psubhashish
 * @author Sambiwiki
 * @author Shijualex
 * @author Shisir 1945
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

/** namespace translations from translatewiki.net 
 * @author Shijualex
 * @author Psubhashish
 */
$namespaceNames = array(
	NS_MEDIA            => 'ମାଧ୍ୟମ',
	NS_SPECIAL          => 'ବିଶେଷ',
	NS_TALK             => 'ଆଲୋଚନା',
	NS_USER             => 'ବ୍ୟବହାରକାରୀ',
	NS_USER_TALK        => 'ବ୍ୟବହାରକାରୀଙ୍କ_ଆଲୋଚନା',
	NS_PROJECT_TALK     => '$1_ଆଲୋଚନା',
	NS_FILE             => 'ଫାଇଲ',
	NS_FILE_TALK        => 'ଫାଇଲ_ଆଲୋଚନା',
	NS_MEDIAWIKI        => 'ମିଡ଼ିଆଉଇକି',
	NS_MEDIAWIKI_TALK   => 'ମିଡ଼ିଆଉଇକି_ଆଲୋଚନା',
	NS_TEMPLATE         => 'ଛାଞ୍ଚ',
	NS_TEMPLATE_TALK    => 'ଛାଞ୍ଚ_ଆଲୋଚନା',
	NS_HELP             => 'ସହଯୋଗ',
	NS_HELP_TALK        => 'ସହଯୋଗ_ଆଲୋଚନା',
	NS_CATEGORY         => 'ଶ୍ରେଣୀ',
	NS_CATEGORY_TALK    => 'ଶ୍ରେଣୀ_ଆଲୋଚନା',
);

$namespaceAliases = array(
	'ବ୍ୟବହାରକାରି'          => NS_USER,
	'ବ୍ୟବହାରକାରିଁକ_ଆଲୋଚନା' => NS_USER_TALK,
	'ବ୍ୟବାହାରକାରୀ'          => NS_USER,
	'ବ୍ୟବାହାରକାରୀଙ୍କ_ଆଲୋଚନା' => NS_USER_TALK,
	'ଉଇକିପିଡ଼ିଆ_ଆଲୋଚନା' => NS_PROJECT_TALK,
	'ଟେଁପଲେଟ'             => NS_TEMPLATE,
	'ଟେଁପଲେଟ_ଆଲୋଚନା'     => NS_TEMPLATE_TALK,
	'ଟେମ୍ପଲେଟ'             => NS_TEMPLATE,
	'ଟେମ୍ପଲେଟ_ଆଲୋଚନା'     => NS_TEMPLATE_TALK,
	'ବିଭାଗ'                => NS_CATEGORY,
	'ବିଭାଗିୟ_ଆଲୋଚନା'      => NS_CATEGORY_TALK,
	'ସାହାଯ୍ୟ'                => NS_HELP,
	'ସାହାଯ୍ୟ_ଆଲୋଚନା'      => NS_HELP_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ସଚଳସଭ୍ୟ' ),
	'Allmessages'               => array( 'ସବୁସନ୍ଦେଶ' ),
	'Allpages'                  => array( 'ସବୁପୃଷ୍ଠା' ),
	'Ancientpages'              => array( 'ପୁରୁଣାପୃଷ୍ଠା' ),
	'Badtitle'                  => array( 'ଖରାପନାମ' ),
	'Blankpage'                 => array( 'ଖାଲିପୃଷ୍ଠା' ),
	'Block'                     => array( 'ଅଟକାଇବେ', 'ଆଇପିଅଟକାଇବେ', 'ସଭ୍ୟଅଟକାଇବେ' ),
	'Blockme'                   => array( 'ମୋତେଅଟକାଇବେ' ),
	'Booksources'               => array( 'ବହିସ୍ରୋତ' ),
	'BrokenRedirects'           => array( 'ଭଙ୍ଗାଲେଉଟାଣି' ),
	'Categories'                => array( 'ଶ୍ରେଣୀ' ),
	'ChangeEmail'               => array( 'ଇମେଲବଦଳାଇବେ' ),
	'ChangePassword'            => array( 'ପାସୱାର୍ଡ଼ବଦଳାଇବେ', 'ପାସୱାର୍ଡ଼ସେଟକରିବେ' ),
	'ComparePages'              => array( 'ପୃଷ୍ଠାକୁତଉଲିବେ' ),
	'Confirmemail'              => array( 'ଇମେଲଥୟକରିବେ' ),
	'Contributions'             => array( 'ଅବଦାନ' ),
	'CreateAccount'             => array( 'ଖାତାଖୋଲିବେ' ),
	'Deadendpages'              => array( 'ଆଗକୁରାହାନଥିବାପୃଷ୍ଠା' ),
	'DeletedContributions'      => array( 'ହଟାଇଦିଆଯାଇଥିବାଅବଦାନ' ),
	'Disambiguations'           => array( 'ବହୁବିକଳ୍ପୀ' ),
	'DoubleRedirects'           => array( 'ଦୁଇଥରଲେଉଟାଣି' ),
	'EditWatchlist'             => array( 'ଧ୍ୟାନସୂଚୀବଦଳାଇବେ' ),
	'Emailuser'                 => array( 'ସଭ୍ୟଙ୍କୁମେଲକରିବେ' ),
	'Export'                    => array( 'ରପ୍ତାନି' ),
	'Fewestrevisions'           => array( 'ସବୁଠୁକମସଙ୍କଳନ' ),
	'FileDuplicateSearch'       => array( 'ଫାଇଲନକଲିଖୋଜା' ),
	'Filepath'                  => array( 'ଫାଇଲରାସ୍ତା' ),
	'Import'                    => array( 'ଆମଦାନି' ),
	'Invalidateemail'           => array( 'କାମକରୁନଥିବାଇମେଲ' ),
	'JavaScriptTest'            => array( 'ଜାଭାସ୍କ୍ରିପ୍ଟଟେଷ୍ଟ' ),
	'BlockList'                 => array( 'ତାଲିକାଅଟକାଇବେ' ),
	'LinkSearch'                => array( 'ଲିଙ୍କଖୋଜା' ),
	'Listadmins'                => array( 'ପରିଛାତାଲିକା' ),
	'Listbots'                  => array( 'ବଟତାଲିକା' ),
	'Listfiles'                 => array( 'ଫାଇଲତାଲିକା' ),
	'Listgrouprights'           => array( 'ଗୋଠନିୟମତାଲିକା' ),
	'Listredirects'             => array( 'ଲେଉଟାଣିତାଲିକା' ),
	'Listusers'                 => array( 'ସଭ୍ୟତାଲିକା' ),
	'Lockdb'                    => array( 'ଡାଟାବେସ‌କିଳିଦେବା' ),
	'Log'                       => array( 'ଲଗ' ),
	'Lonelypages'               => array( 'ଏକୁଟିଆପୃଷ୍ଠା' ),
	'Longpages'                 => array( 'ଲମ୍ବାପୃଷ୍ଠା' ),
	'MergeHistory'              => array( 'ଇତିହାସକୁମିଶାଇବେ' ),
	'MIMEsearch'                => array( 'MIME_ଖୋଜା' ),
	'Mostcategories'            => array( 'ଅଧିକଶ୍ରେଣୀଥିବା' ),
	'Mostimages'                => array( 'ଅଧିକଯୋଡ଼ାଫାଇଲ' ),
	'Mostlinked'                => array( 'ଅଧିକଯୋଡ଼ାପୃଷ୍ଠା' ),
	'Mostlinkedcategories'      => array( 'ଅଧିକଯୋଡ଼ାଶ୍ରେଣୀ' ),
	'Mostlinkedtemplates'       => array( 'ଅଧିକଯୋଡ଼ାଛାଞ୍ଚ' ),
	'Mostrevisions'             => array( 'ଅଧିକସଙ୍କଳନ' ),
	'Movepage'                  => array( 'ପୃଷ୍ଠାଘୁଞ୍ଚାଇବେ' ),
	'Mycontributions'           => array( 'ମୋଅବଦାନ' ),
	'Mypage'                    => array( 'ମୋପୃଷ୍ଠା' ),
	'Mytalk'                    => array( 'ମୋଆଲୋଚନା' ),
	'Myuploads'                 => array( 'ମୋଅପଲୋଡ଼' ),
	'Newimages'                 => array( 'ନୂଆଫାଇଲ' ),
	'Newpages'                  => array( 'ନୂଆପୃଷ୍ଠା' ),
	'PermanentLink'             => array( 'ଚିରକାଳଲିଙ୍କ' ),
	'Popularpages'              => array( 'ଜଣାଶୁଣାପୃଷ୍ଠା' ),
	'Preferences'               => array( 'ପସନ୍ଦ' ),
	'Prefixindex'               => array( 'ଆଗରେଯୋଡ଼ାହେବାଇଣ୍ଡେକ୍ସ' ),
	'Protectedpages'            => array( 'କିଳାଯାଇଥିବାପୃଷ୍ଠା' ),
	'Protectedtitles'           => array( 'କିଳାଯାଇଥିବାନାମ' ),
	'Randompage'                => array( 'ଜାହିତାହି', 'ଜାହିତାହିପୃଷ୍ଠା' ),
	'Randomredirect'            => array( 'ଜାହିତାହିଲେଉଟାଣି' ),
	'Recentchanges'             => array( 'ନଗଦବଦଳ' ),
	'Recentchangeslinked'       => array( 'ଜୋଡ଼ାଥିବାନଗଦବଦଳ', 'ପାଖାପାଖିବଦଳ' ),
	'Revisiondelete'            => array( 'ସଙ୍କଳନଲିଭାଇଦିଅଦେବେ' ),
	'RevisionMove'              => array( 'ସଙ୍କଳନ' ),
	'Search'                    => array( 'ଖୋଜନ୍ତୁ' ),
	'Shortpages'                => array( 'ଛୋଟପୃଷ୍ଠା' ),
	'Specialpages'              => array( 'ବିଶେଷପୃଷ୍ଠା' ),
	'Statistics'                => array( 'ଗଣନା' ),
	'Tags'                      => array( 'ଚିହ୍ନସମୂହ' ),
	'Unblock'                   => array( 'ଫିଟାଇଦେବେ' ),
	'Uncategorizedcategories'   => array( 'ଅସଜଡ଼ାଶ୍ରେଣୀ' ),
	'Uncategorizedimages'       => array( 'ଅସଜଡ଼ାଶ୍ରେଣୀରଫାଇଲ' ),
	'Uncategorizedpages'        => array( 'ଅସଜଡ଼ା_ଫାଇଲସବୁ' ),
	'Uncategorizedtemplates'    => array( 'ଅସଜଡ଼ାଛାଞ୍ଚ' ),
	'Undelete'                  => array( 'ଅଣଲିଭା' ),
	'Unlockdb'                  => array( 'DBଖୋଲିବା' ),
	'Unusedcategories'          => array( 'ବ୍ୟବହାରହୋଇନଥିବାଶ୍ରେଣୀ' ),
	'Unusedimages'              => array( 'ବ୍ୟବହାରହୋଇନଥିବାଫାଇଲ' ),
	'Unusedtemplates'           => array( 'ବ୍ୟବହାରହୋଇନଥିବାଛାଞ୍ଚ' ),
	'Unwatchedpages'            => array( 'ଦେଖାଯାଇନଥିବାପୃଷ୍ଠାସବୁ' ),
	'Upload'                    => array( 'ଅପଲୋଡ଼' ),
	'UploadStash'               => array( 'ଷ୍ଟାସଅପଲୋଡ଼' ),
	'Userlogin'                 => array( 'ସଭ୍ୟଲଗଇନ' ),
	'Userlogout'                => array( 'ସଭ୍ୟଲଗଆଉଟ' ),
	'Userrights'                => array( 'ସଭ୍ୟଅଧିକାର' ),
	'Version'                   => array( 'ସଂସ୍କରଣ' ),
	'Wantedcategories'          => array( 'ଦରକାରିଶ୍ରେଣୀ' ),
	'Wantedfiles'               => array( 'ଦରକାରିଫାଇଲ' ),
	'Wantedpages'               => array( 'ଦରକାରିପୃଷ୍ଠା' ),
	'Wantedtemplates'           => array( 'ଦରକାରିଛାଞ୍ଚ' ),
	'Watchlist'                 => array( 'ଦେଖଣାତାଲିକା' ),
	'Whatlinkshere'             => array( 'ଏଠାରେକଣଲିଙ୍କଅଛି' ),
	'Withoutinterwiki'          => array( 'ଇଣ୍ଟରଉଇକିବିନା' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ଲେଉଟାଣି', '#REDIRECT' ),
	'noeditsection'             => array( '0', '_ବଦଳା_ନହେବାଶ୍ରେଣୀ_', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'ଏବେକାର_ମାସ', 'ଏବେର_ମାସ୨', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'ଏବେର_ମାସ', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'ଏବେକାର_ମାସ_ନାଆଁ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'ଏବେକାର_ମାସ_ନାଆଁ_ସାଧାରଣ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'ଏବେକାର_ମାସ_ନାଆଁ_ସଂକ୍ଷିପ୍ତ', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'ଏବେକାର_ଦିନ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ଏବେକାର_ଦିନ୨', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'ଏବେକାର_ଦିନ_ନାଆଁ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ଏବେକାର_ବର୍ଷ', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'ଏବେକାର_ସମୟ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ଏବେକାର_ଘଣ୍ଟା', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'ଏବେର_ମାସ୧', 'ସ୍ଥାନୀୟ_ମାସ୨', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'ଏବେକାର_ମାସ୧', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'ମାସ୧ର_ନାଆଁ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'ସ୍ଥାନୀୟ_ମାସ୧_ନାଆଁ_ସାଧାରଣ', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'ସ୍ଥାନୀୟ_ମାସର୧_ନାଆଁ_ସଂକ୍ଷିପ୍ତ', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'Local_ଦିନ', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'ସ୍ଥାନୀୟ_ଦିନ୨', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'ଦିନ', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'ସ୍ଥାନୀୟ_ବର୍ଷ', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'ସ୍ଥାନୀୟ_ସମୟ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ସ୍ଥାନୀୟ_ଘଣ୍ଟା', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'ପୃଷ୍ଠା_ସଂଖ୍ୟା', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ଲେଖା_ସଂଖ୍ୟା', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ଫାଇଲ_ସଂଖ୍ୟା', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'ବ୍ୟବାହାରକାରୀ_ସଂଖ୍ୟା', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'ସଚଳ_ବ୍ୟବାହାରକାରୀଙ୍କ_ସଂଖ୍ୟା', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'ବଦଳ_ସଂଖ୍ୟା', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'କେତେଥର_ଦେଖାଯାଇଛି', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'ପୃଷ୍ଠା_ନାଆଁ', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'ପୃଷ୍ଠା_ନାମକାରଣକାରୀ', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ନେମସ୍ପେସ', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ନେମସ୍ପେସକାରୀ', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'ଟକସ୍ପେସ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'ଟକସ୍ପେସକାରୀ', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ବିଷୟସ୍ପେସ', 'ଲେଖାସ୍ପେସ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'msg'                       => array( '0', 'ମେସେଜ:', 'MSG:' ),
	'img_manualthumb'           => array( '1', 'ନଖଦେଖଣା=$1', 'ଦେଖଣା=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'ଡାହାଣ', 'right' ),
	'img_left'                  => array( '1', 'ବାଆଁ', 'left' ),
	'img_none'                  => array( '1', 'କିଛି_ନୁହେଁ', 'none' ),
	'img_width'                 => array( '1', '$1_ପିକସେଲ', '$1px' ),
	'img_center'                => array( '1', 'କେନ୍ଦ୍ର', 'center', 'centre' ),
	'img_framed'                => array( '1', 'ଫ୍ରେମକରା', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'ଫ୍ରେମନଥିବା', 'frameless' ),
	'img_border'                => array( '1', 'ବର୍ଡର', 'border' ),
	'img_baseline'              => array( '1', 'ବେସଲାଇନ', 'baseline' ),
	'img_top'                   => array( '1', 'ଉପର', 'top' ),
	'img_text_top'              => array( '1', 'ଲେଖା-ଉପର', 'text-top' ),
	'img_middle'                => array( '1', 'ମଝି', 'middle' ),
	'img_bottom'                => array( '1', 'ତଳ', 'bottom' ),
	'img_text_bottom'           => array( '1', 'ଲେଖା-ତଳ', 'text-bottom' ),
	'img_link'                  => array( '1', 'ଲିଙ୍କ=$1', 'link=$1' ),
	'articlepath'               => array( '0', 'ଲେଖାର_ପଥ', 'ARTICLEPATH' ),
	'server'                    => array( '0', 'ସର୍ଭର', 'SERVER' ),
	'grammar'                   => array( '0', 'ବ୍ୟାକରଣ', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'ଲିଙ୍ଗ', 'GENDER:' ),
	'plural'                    => array( '0', 'ବହୁବଚନ:', 'PLURAL:' ),
	'raw'                       => array( '0', 'କଞ୍ଚା', 'RAW:' ),
	'displaytitle'              => array( '1', 'ଦେଖଣାନାଆଁ', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '_ନୂଆବିଭାଗଲିଙ୍କ_', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '_ନୂଆ_ବିଭାଗ_ନକରିବା_ଲିଙ୍କ_', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'ନଗଦ_ରିଭିଜନ', 'CURRENTVERSION' ),
	'numberofadmins'            => array( '1', 'ପରିଛାମାନଙ୍କତାଲିକା', 'NUMBEROFADMINS' ),
	'padleft'                   => array( '0', 'ବାଆଁପ୍ୟାଡ଼', 'PADLEFT' ),
	'padright'                  => array( '0', 'ଡାହାଣପ୍ୟାଡ଼', 'PADRIGHT' ),
	'special'                   => array( '0', 'ବିଶେଷ', 'special' ),
	'filepath'                  => array( '0', 'ଫାଇଲରାହା:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'ଟାଗ', 'tag' ),
	'hiddencat'                 => array( '1', '_ଲୁଚିଥିବାବିଭାଗ_', '__HIDDENCAT__' ),
	'pagesize'                  => array( '1', 'ଫରଦଆକାର', 'PAGESIZE' ),
	'protectionlevel'           => array( '1', 'ପ୍ରତିରକ୍ଷାସ୍ତର', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'ତାରିଖରପ୍ରକାର', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'ବାଟ', 'PATH' ),
	'url_wiki'                  => array( '0', 'ଉଇକି', 'WIKI' ),
	'url_query'                 => array( '0', 'ପ୍ରଶ୍ନ', 'QUERY' ),
);

$digitGroupingPattern = "##,##,###";

$messages = array(
# User preference toggles
'tog-underline' => 'ତଳେ ଥିବା ଲିଙ୍କ:',
'tog-justify' => 'ପାରାଗ୍ରାଫଗୁଡ଼ିକର ବାଆଁ ଡାହାଣ ସମଭାବେ ସଜାଡ଼ିବେ',
'tog-hideminor' => 'ଛୋଟ ଛୋଟ ନଗଦ ବଦଳ ସବୁକୁ ଲୁଚାଇବେ',
'tog-hidepatrolled' => 'ନଗଦ ବଦଳରେ ଥିବା ଜଗାହୋଇଥିବା ବଦଳ ସବୁକୁ ଲୁଚାଇବେ',
'tog-newpageshidepatrolled' => 'ନୂଆ ପୃଷ୍ଠାତାଲିକାରୁ ଜଗାହୋଇଥିବା ବଦଳସବୁକୁ ଲୁଚାଇବେ',
'tog-extendwatchlist' => 'କେବଳ ନଗଦ ନୁହେଁ, ସବୁଯାକ ବଦଳକୁ ଦେଖାଇବା ନିମନ୍ତେ ଦେଖଣାତାଲିକାକୁ ବଢ଼ାଇବେ',
'tog-usenewrc' => 'ନଗଦ ବଦଳରେ ପୃଷ୍ଠା ଅନୁଯାୟୀ ଗୋଷ୍ଠୀ ବଦଳ ଏବଂ ଦେଖଣା (ଜାଭାସ୍କ୍ରିପ୍ଟ ଲୋଡ଼ା)',
'tog-numberheadings' => 'ଆପେଆପେ-ସଂଖ୍ୟାର ନାମଗୁଡ଼ିକ',
'tog-showtoolbar' => 'ସମ୍ପାଦନା ଟୁଲବାର ଦେଖାଇବେ (ଜାଭାସ୍କ୍ରିପ୍ଟ ସଚଳ କରିବେ)',
'tog-editondblclick' => 'ଦୁଇଥର କ୍ଲିକରେ ପୃଷ୍ଠା ବଦଳାଇବେ (ଜାଭାସ୍କ୍ରିପ୍ଟ ଲୋଡ଼ା)',
'tog-editsection' => '[ବଦଳାଇବେ] ଲିଙ୍କରେ ବିଭାଗର ସମ୍ପାଦନାକୁ ସଚଳ କରିବେ',
'tog-editsectiononrightclick' => 'ବିଭାଗ ନାମରେ ଡାହାଣ କ୍ଲିକ କରି ବିଭାଗ ସମ୍ପାଦନାକୁ ସଚଳ କରିବେ (ଜାଭାସ୍କ୍ରିପ୍ଟ ଲୋଡ଼ା)',
'tog-showtoc' => 'ସୂଚୀପତ୍ର ଦେଖାଇବେ (୩ରୁ ଅଧିକ ମୁଖ୍ୟ ନାମ ଥିଲେ)',
'tog-rememberpassword' => 'ଏହି ବ୍ରାଉଜରରେ (ସବୁଠୁ ଅଧିକ ହେଲେ $1 {{PLURAL:$1|day|ଦିନ}}) ପାଇଁ ମୋ ଲଗଇନ ମନେ ରଖିଥିବେ',
'tog-watchcreations' => 'ମୋ ତିଆରି ପୃଷ୍ଠାସବୁକୁ ଏବଂ ମୋ ଅପଲୋଡଗୁଡିକୁ ମୋର ଦେଖଣାତାଲିକାରେ ଯୋଡନ୍ତୁ',
'tog-watchdefault' => 'ମୁଁ ବଦଳେଇଥିବା ପୃଷ୍ଠା ଏବଂ ଫାଇଲଗୁଡିକୁ ମୋର ଦେଖଣାତାଲିକାରେ ଯୋଡନ୍ତୁ',
'tog-watchmoves' => 'ମୁଁ ଘୁଞ୍ଚାଇଥିବା ପୃଷ୍ଠା ଏବଂ ଫାଇଲଗୁଡିକୁ ମୋର ଦେଖଣାତାଲିକାରେ ଯୋଡନ୍ତୁ',
'tog-watchdeletion' => 'ମୁଁ ଲିଭାଇଥିବା ପୃଷ୍ଠା ଏବଂ ଫାଇଲଗୁଡିକୁ ମୋର ଦେଖଣାତାଲିକାରେ ଯୋଡନ୍ତୁ',
'tog-minordefault' => 'ସବୁଯାକ ସମ୍ପାଦନାକୁ ଛାଏଁ ଟିକେ ବଦଳ ଭାବରେ ସୂଚିତ କରିବେ',
'tog-previewontop' => 'ଏଡ଼ିଟ ବାକ୍ସ ଆଗରୁ ଦେଖଣା ଦେଖାଇବେ',
'tog-previewonfirst' => 'ପ୍ରଥମ ବଦଳର ଦେଖଣା ଦେଖାଇବେ',
'tog-nocache' => 'ବ୍ରାଉଜର ପୃଷ୍ଠା ସଂରକ୍ଷଣକୁ ଅଚଳ କରିବେ',
'tog-enotifwatchlistpages' => 'ମୋ ଦେଖଣାତାଲିକାରେ ଥିବା ପୃଷ୍ଠା ବା ଫାଇଲରେ କିଛି ବଦଳ ହେଲେ ମୋତେ ଇ-ମେଲ କରିବେ',
'tog-enotifusertalkpages' => 'ମୋର ଆଲୋଚନା ପୃଷ୍ଠାରେ କିଛି ବଦଳ ହେଲେ ମୋତେ ଇ-ମେଲ କରିବେ',
'tog-enotifminoredits' => 'ପୃଷ୍ଠାରେ ଏବଂ ଫାଇଲଗୁଡିକରେ ଛୋଟ ଛୋଟ ବଦଳ ହେଲେ ବି ମୋତେ ଇ-ମେଲ କରିବେ',
'tog-enotifrevealaddr' => 'ସୂଚନା ଇ-ମେଲ ରେ ମୋର ଇ-ମେଲ ଠିକଣା ଦେଖାଇବେ',
'tog-shownumberswatching' => 'ଦେଖୁଥିବା ବ୍ୟବହାରକାରୀଙ୍କ ସଂଖ୍ୟା ଦେଖାଇବେ',
'tog-oldsig' => 'ଏବେ ଥିବା ନାମ:',
'tog-fancysig' => 'ଦସ୍ତଖତକୁ ଉଇକିଟେକ୍ସଟ ଭାବରେ ଗଣିବେ (ଆପେଆପେ ଥିବା ଲିଙ୍କ ବିନା)',
'tog-externaleditor' => 'ବାହାର ସମ୍ପାଦକଟି ଆପଣାଛାଏଁ ବ୍ୟବହାର କରିବେ (କେବଳ ପଟୁ ସଭ୍ୟଙ୍କ ପାଇଁ, ଏଥି ନିମନ୍ତେ ଆପଣଙ୍କ କମ୍ପୁଟରରେ ବିଶେଷ ସଜାଣି ଲୋଡ଼ା । [//www.mediawiki.org/wiki/Manual:External_editors ଅଧିକ ସୂଚନା])',
'tog-externaldiff' => 'ବାହାର ବାଛିବା (external diff) ଆପଣାଛାଏଁ ବ୍ୟବହାର କରିବେ (କେବଳ ପଟୁ ସଭ୍ୟଙ୍କ ପାଇଁ, ଏଥି ନିମନ୍ତେ ଆପଣଙ୍କ କମ୍ପୁଟରରେ ବିଶେଷ ସଜାଣି ଲୋଡ଼ା । [//www.mediawiki.org/wiki/Manual:External_editors ଅଧିକ ସୂଚନା])',
'tog-showjumplinks' => '"ଡେଇଁଯିବେ" ଲିଙ୍କସବୁକୁ ସଚଳ କରିବେ',
'tog-uselivepreview' => 'ସାଥେ ସାଥେ ଚାଲିଥିବା ଦେଖଣା ବ୍ୟବହାର କରିବେ (ଜାଭାସ୍କ୍ରିପ୍ଟ ଲୋଡ଼ା)',
'tog-forceeditsummary' => 'ଖାଲି ସମ୍ପାଦନା ସାରକଥାକୁ ଯିବା ବେଳେ ମୋତେ ଜଣାଇବେ',
'tog-watchlisthideown' => 'ମୋ ଦେଖଣା ତାଲିକାରେ ମୋ ନିଜର ସମ୍ପାଦନାଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthidebots' => 'ଦେଖଣା ତାଲିକାରେ ବଟ ଦେଇ ବଦଳ ଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthideminor' => 'ଦେଖଣା ତାଲିକାରେ ଛୋଟ ଛୋଟ ବଦଳ ଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthideliu' => 'ଲଗ ଇନ କରିଥିବା ସଭ୍ୟମାନଙ୍କ ଦେଇ କରାହୋଇଥିବା ବଦଳଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthideanons' => 'ଅଜଣା ସଭ୍ୟମାନଙ୍କ ଦେଇ କରାହୋଇଥିବା ବଦଳଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthidepatrolled' => 'ମୋ ଦେଖଣା ତାଲିକାରୁ ଜଗାଯାଇଥିବା ସମ୍ପାଦନାଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-ccmeonemails' => 'ମୁଁ ଯେଉଁ ଇ-ମେଲ ସବୁ ଅନ୍ୟମାନଙ୍କୁ ପଠାଉଛି ସେସବୁର ନକଲ ମୋତେ ପଠାଇବେ ।',
'tog-diffonly' => 'ତୁଳନା ତଳେ ପୃଷ୍ଠାର ଭିତର ଭାଗ ଦେଖାନ୍ତୁ ନାହିଁ',
'tog-showhiddencats' => 'ଲୁଚାଯାଇଥିବା ଶ୍ରେଣୀଗୁଡ଼ିକ ଦେଖାଇବେ',
'tog-norollbackdiff' => 'ରୋଲବ୍ୟାକ କଲାପରେ ତୁଳନା ଦେଖାନ୍ତୁ ନାହିଁ',

'underline-always' => 'ସବୁବେଳେ',
'underline-never' => 'କେବେନୁହେଁ',
'underline-default' => 'ବ୍ରାଉଜର କିମ୍ବା ସ୍କିନରେ ଆଗରୁ ଥିବା ସୁବିଧା',

# Font style option in Special:Preferences
'editfont-style' => 'ଫଣ୍ଟ ଶୈଳୀକୁ ବଦଳାଇବେ:',
'editfont-default' => 'ବ୍ରାଉଜରରେ ଆଗରୁ ଥିବା ସୁବିଧା',
'editfont-monospace' => 'ମନୋସ୍ପେସ ଥିବା ଫଣ୍ଟ',
'editfont-sansserif' => 'ସାନସ-ସେରିଫ ଫଣ୍ଟ',
'editfont-serif' => 'ସେରିଫ ଫଣ୍ଟ',

# Dates
'sunday' => 'ରବିବାର',
'monday' => 'ସୋମବାର',
'tuesday' => 'ମଙ୍ଗଳବାର',
'wednesday' => 'ବୁଧବାର',
'thursday' => 'ଗୁରୁବାର',
'friday' => 'ଶୁକ୍ରବାର',
'saturday' => 'ଶନିବାର',
'sun' => 'ରବି',
'mon' => 'ସୋମ',
'tue' => 'ମଙ୍ଗଳ',
'wed' => 'ବୁଧ',
'thu' => 'ଗୁରୁ',
'fri' => 'ଶୁକ୍ର',
'sat' => 'ଶନି',
'january' => 'ଜାନୁଆରୀ',
'february' => 'ଫେବ୍ରୁଆରୀ',
'march' => 'ମାର୍ଚ୍ଚ',
'april' => 'ଅପ୍ରେଲ',
'may_long' => 'ମଇ',
'june' => 'ଜୁନ',
'july' => 'ଜୁଲାଇ',
'august' => 'ଅଗଷ୍ଟ',
'september' => 'ସେପ୍ଟେମ୍ବର',
'october' => 'ଅକ୍ଟୋବର',
'november' => 'ନଭେମ୍ବର',
'december' => 'ଡିସେମ୍ବର',
'january-gen' => 'ଜାନୁଆରୀ',
'february-gen' => 'ଫେବ୍ରୁଆରୀ',
'march-gen' => 'ମାର୍ଚ୍ଚ',
'april-gen' => 'ଅପ୍ରେଲ',
'may-gen' => 'ମଇ',
'june-gen' => 'ଜୁନ',
'july-gen' => 'ଜୁଲାଇ',
'august-gen' => 'ଅଗଷ୍ଟ',
'september-gen' => 'ସେପ୍ଟେମ୍ବର',
'october-gen' => 'ଅକ୍ଟୋବର',
'november-gen' => 'ନଭେମ୍ବର',
'december-gen' => 'ଡିସେମ୍ବର',
'jan' => 'ଜାନୁଆରୀ',
'feb' => 'ଫେବ୍ରୁଆରୀ',
'mar' => 'ମାର୍ଚ୍ଚ',
'apr' => 'ଅପ୍ରେଲ',
'may' => 'ମଇ',
'jun' => 'ଜୁନ',
'jul' => 'ଜୁଲାଇ',
'aug' => 'ଅଗଷ୍ଟ',
'sep' => 'ସେପ୍ଟେମ୍ବର',
'oct' => 'ଅକ୍ଟୋବର',
'nov' => 'ନଭେମ୍ବର',
'dec' => 'ଡିସେମ୍ବର',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|ଶ୍ରେଣୀ|ଶ୍ରେଣୀସମୂହ}}',
'category_header' => '"$1" ଶ୍ରେଣୀରେ ଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକ',
'subcategories' => 'ସାନ ଶ୍ରେଣୀସମୂହ',
'category-media-header' => '"$1" ଶ୍ରେଣୀରେ ଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକ',
'category-empty' => "''ଏହି ଶ୍ରେଣୀ ଭିତରେ କିଛି ପୃଷ୍ଠା ବା ମାଧ୍ୟମ ନାହିଁ ।''",
'hidden-categories' => '{{PLURAL:$1|Hidden category|ଲୁଚିଥିବା ଶ୍ରେଣୀ}}',
'hidden-category-category' => 'ଲୁଚିରହିଥିବା ଶ୍ରେଣୀ',
'category-subcat-count' => '{{PLURAL:$2|ଏହି ଶ୍ରେଣୀଟିରେ କେବଳ ତଳେଥିବା ସାନ ଶ୍ରେଣୀଗୁଡିକ ଅଛନ୍ତି । |ଏହି ଶ୍ରେଣୀଟିରେ ସର୍ବମୋଟ $2 ରୁ ତଳେଥିବା ଏହି {{PLURAL:$1|subcategory|$1 ଶ୍ରେଣୀଗୁଡିକ}} ଅଛନ୍ତି  । }}',
'category-subcat-count-limited' => 'ଏହି ଶ୍ରେଣୀ ଅଧୀନରେ ତଳଲିଖିତ {{PLURAL:$1|ସାନ ଶ୍ରେଣୀ|$1 ସାନ ଶ୍ରେଣୀସମୂହ}} ରହିଅଛନ୍ତି ।',
'category-article-count' => '{{PLURAL:$2|ଏହି ଶ୍ରେଣୀରେ ତଳେଥିବା ପୃଷ୍ଠାସବୁ ଅଛି ।|ମୋଟ $2 ରୁ ଏହି ଶ୍ରେଣୀ ଭିତରେ {{PLURAL:$1|ଟି ପୃଷ୍ଠା|$1ଟି ପୃଷ୍ଠା}} ଅଛି ।}}',
'category-article-count-limited' => 'ତଲଲିଖିତ {{PLURAL:$1|ପୃଷ୍ଠାଟି|$1ଟି ପୃଷ୍ଠା}} ଏହି ଶ୍ରେଣୀରେ ରହିଅଛି ।',
'category-file-count' => '{{PLURAL:$2|ଏହି ଶ୍ରେଣୀରେ କେବଳ ତଳେଥିବା ଫାଇଲ ଗୋଟି ଅଛି । | ମୋଟ $2 ରୁ ଏହି ଶ୍ରେଣୀ ଭିତରେ {{PLURAL:$1|ଟି ପୃଷ୍ଠା|$1ଟି ଫାଇଲ}} ଅଛି ।}}',
'category-file-count-limited' => 'ତଲଲିଖିତ {{PLURAL:$1|ଫାଇଲଟି|$1ଟି ଫାଇଲ}} ଏହି ଶ୍ରେଣୀରେ ରହିଅଛି ।',
'listingcontinuesabbrev' => 'ଆହୁରି ଅଛି..',
'index-category' => 'ସୂଚୀଥିବା ପୃଷ୍ଠାସବୁ',
'noindex-category' => 'ସୂଚୀହୀନ ପୃଷ୍ଠାସବୁ',
'broken-file-category' => 'ଭଙ୍ଗା ଫାଇଲ ଲିଙ୍କ ଥିବା ପୃଷ୍ଠାସମୂହ',

'about' => 'ବାବଦରେ',
'article' => 'ସୂଚୀପତ୍ର',
'newwindow' => '(ଏହା ନୂଆ ଉଇଣ୍ଡୋରେ ଖୋଲିବ)',
'cancel' => 'ନାକଚ',
'moredotdotdot' => 'ଅଧିକ...',
'mypage' => 'ପୃଷ୍ଠା',
'mytalk' => 'ଆଲୋଚନା',
'anontalk' => 'ଏହି ଆଇ.ପି. ଠିକଣା ଉପରେ ଆଲୋଚନା',
'navigation' => 'ଦିଗବାରେଣି',
'and' => '&#32;ଓ',

# Cologne Blue skin
'qbfind' => 'ଖୋଜନ୍ତୁ',
'qbbrowse' => 'ଖୋଜିବା',
'qbedit' => 'ଏହାକୁ ବଦଳାନ୍ତୁ',
'qbpageoptions' => 'ଏହି ପୃଷ୍ଠାଟି',
'qbpageinfo' => 'ଭିତର ଚିଜ',
'qbmyoptions' => 'ମୋ ପୃଷ୍ଠାଗୁଡ଼ିକ',
'qbspecialpages' => 'ବିଶେଷ ପୃଷ୍ଠା',
'faq' => 'ବାରମ୍ବାର ପଚରାଯାଉଥିବା ପ୍ରଶ୍ନ',
'faqpage' => 'Project:ବାରମ୍ବାର ପଚରାଯାଉଥିବା ପ୍ରଶ୍ନ',

# Vector skin
'vector-action-addsection' => 'ନୂଆ ଯୋଡ଼ନ୍ତୁ',
'vector-action-delete' => 'ଲିଭାଇବେ',
'vector-action-move' => 'ଘୁଞ୍ଚାଇବେ',
'vector-action-protect' => 'କିଳିବେ',
'vector-action-undelete' => 'ଲିଭାଇବେ ନାହିଁ',
'vector-action-unprotect' => 'କିଳିବେ ନାହିଁ',
'vector-simplesearch-preference' => 'ସହଜ ଖୋଜା ବାରଟିକୁ ସଚଳ କରିବେ (କେବଳ ଭେକ୍ଟର ସ୍କିନ)',
'vector-view-create' => 'ଗଢ଼ନ୍ତୁ',
'vector-view-edit' => 'ଏହାକୁ ବଦଳାନ୍ତୁ',
'vector-view-history' => 'ଇତିହାସ',
'vector-view-view' => 'ପଢ଼ନ୍ତୁ',
'vector-view-viewsource' => 'ମୂଳାଧାର ଦେଖିବେ',
'actions' => 'କାମ',
'namespaces' => 'ନେମସ୍ପେସ',
'variants' => 'ନିଆରା',

'errorpagetitle' => 'ଭୁଲ',
'returnto' => '$1କୁ ଫେରିଯାନ୍ତୁ ।',
'tagline' => '{{SITENAME}} ରୁ',
'help' => 'ସହଯୋଗ',
'search' => 'ଖୋଜିବେ',
'searchbutton' => 'ଖୋଜିବେ',
'go' => 'ଯିବା',
'searcharticle' => 'ଯିବା',
'history' => 'ଫାଇଲ ଇତିହାସ',
'history_short' => 'ଇତିହାସ',
'updatedmarker' => 'ମୋ ଶେଷ ଆସିବା ପରେ ଅପଡେଟ କରାଯାଇଅଛି',
'printableversion' => 'ଛପାହୋଇପାରିବା ପୃଷ୍ଠା',
'permalink' => 'ସବୁଦିନିଆ ଲିଙ୍କ',
'print' => 'ପ୍ରିଣ୍ଟ କରିବେ',
'view' => 'ଦେଖଣା',
'edit' => 'ଏହାକୁ ବଦଳାନ୍ତୁ',
'create' => 'ତିଆରି କରିବେ',
'editthispage' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ବଦଳାଇବେ',
'create-this-page' => 'ଏହି ପୃଷ୍ଠା ତିଆରି କରିବେ',
'delete' => 'ଲିଭାଇବେ',
'deletethispage' => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇବେ',
'undelete_short' => '{{PLURAL:$1|ଗୋଟିଏ ବଦଳ|$1ଟି ବଦଳ}} ଯାହା ଲିଭାସରିଛି ତାହାକୁ ପଛକୁ ଫେରାଇଦେବା',
'viewdeleted_short' => '{{PLURAL:$1|ଗୋଟିଏ ଲିଭାଯାଇଥିବା ବଦଳ|$1ଟି ଲିଭାଯାଇଥିବା ବଦଳ}} ଦେଖାଇବେ',
'protect' => 'କିଳିବେ',
'protect_change' => 'ବଦଳାଇବା',
'protectthispage' => 'ଏହି ପୃଷ୍ଠାଟିକୁ କିଳିବେ',
'unprotect' => 'ସୁରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇବେ',
'unprotectthispage' => 'ଏହି ପୃଷ୍ଠା ପାଇଁ ସୁରକ୍ଷାର ପ୍ରକାର ବଦଳାଇବେ',
'newpage' => 'ନୂଆ ପୃଷ୍ଠା',
'talkpage' => 'ପୃଷ୍ଠାକୁ ଆଲୋଚନା କରନ୍ତୁ',
'talkpagelinktext' => 'କଥାଭାଷା',
'specialpage' => 'ବିଶେଷ ପୃଷ୍ଠା',
'personaltools' => 'ନିଜର ଟୁଲ',
'postcomment' => 'ନୂଆ ଭାଗ',
'articlepage' => 'ସୂଚୀ ପୃଷ୍ଠାଟି ଦେଖାଇବେ',
'talk' => 'ଆଲୋଚନା',
'views' => 'ଦେଖା',
'toolbox' => 'ଉପକରଣ',
'userpage' => 'ବ୍ୟବହାରକାରୀଙ୍କ ପୃଷ୍ଠା ଦେଖନ୍ତୁ',
'projectpage' => 'ପ୍ରକଳ୍ପ ପୃଷ୍ଠାଟି ଦେଖାଇବା',
'imagepage' => 'ଫାଇଲ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'mediawikipage' => 'ମେସେଜ ପୃଷ୍ଠାଟି ଦେଖାଇବେ',
'templatepage' => 'ଛାଞ୍ଚ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'viewhelppage' => 'ସହାଯୋଗ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'categorypage' => 'ଶ୍ରେଣୀ ପୃଷ୍ଠାଟିକୁ ଦେଖାଇବେ',
'viewtalkpage' => 'ଆଲୋଚନାଗୁଡ଼ିକୁ ଦେଖନ୍ତୁ',
'otherlanguages' => 'ଅଲଗା ଭାଷା',
'redirectedfrom' => '($1 ରୁ ଲେଉଟି ଆସିଛି)',
'redirectpagesub' => 'ଆଉଥରେ ଫେରିବା ପୃଷ୍ଠା',
'lastmodifiedat' => 'ଏହି ପୃଷ୍ଠାଟି $1 ତାରିଖ $2 ବେଳେ ବଦଳାଯାଇଥିଲା ।',
'viewcount' => 'ଏହି ପୃଷ୍ଠାଟି {{PLURAL:$1|ଥରେ|$1 ଥର}} ଖୋଲାଯାଇଛି ।',
'protectedpage' => 'କିଳାଯାଇଥିବା ପୃଷ୍ଠା',
'jumpto' => 'ଡେଇଁଯିବେ',
'jumptonavigation' => 'ଦିଗବାରେଣିକୁ',
'jumptosearch' => 'ଖୋଜିବେ',
'view-pool-error' => 'କ୍ଷମା କରିବେ, ସର୍ଭରସବୁ ଏବେ ମନ୍ଦ ହୋଇଯାଇଅଛନ୍ତି ।
ଅନେକ ସଭ୍ୟ ଏହି ଏକା ପୃଷ୍ଠାଟି ଦେଖିବାକୁ ଚେଷ୍ଟାକରୁଅଛନ୍ତି ।
ଏହି ପୃଷ୍ଠାକୁ ଆଉଥରେ ଖୋଲିବା ଆଗରୁ ଦୟାକରି କିଛି କ୍ଷଣ ଅପେକ୍ଷା କରନ୍ତୁ ।
$1',
'pool-timeout' => 'ତାଲା ଖୋଲାଯିବା ପାଇଁ ଅପେକ୍ଷା କରୁକରୁ ସମୟ ସରିଗଲା',
'pool-queuefull' => 'ପୁଲ ଧାଡ଼ିଟି ଭରିଯାଇଅଛି',
'pool-errorunknown' => 'ଅଜଣା ଅସୁବିଧା',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} ବାବଦରେ',
'aboutpage' => 'Project:ବାବଦରେ',
'copyright' => '$1 ରେ ସର୍ବସ୍ଵତ୍ଵ ସଂରକ୍ଷିତ',
'copyrightpage' => '{{ns:project}}:କପିରାଇଟ',
'currentevents' => 'ଏବେକାର ଘଟଣା',
'currentevents-url' => 'Project:ଏବେକାର ଘଟଣା',
'disclaimers' => 'ଆମେ ଦାୟୀ ନୋହୁଁ',
'disclaimerpage' => 'Project:ଆମେ ଦାୟୀ ନୋହୁଁ',
'edithelp' => 'ଲେଖା ସାହାଯ୍ୟ',
'edithelppage' => 'Help:ବଦଳାଇବା',
'helppage' => 'Help:ସୂଚୀ',
'mainpage' => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'mainpage-description' => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'policy-url' => 'Project:ନୀତି',
'portal' => 'ସଙ୍ଘ ସୂଚନା ଫଳକ',
'portal-url' => 'Project:ସଙ୍ଘ ସୂଚନା ଫଳକ',
'privacy' => 'ଗୁମର ନୀତି',
'privacypage' => 'Project:ଗୁମର ନୀତି',

'badaccess' => 'ଅନୁମତି ମିଳିବାରେ ଅସୁବିଧା',
'badaccess-group0' => 'ଆପଣ ଅନୁରୋଷ କରିଥିବା ପୃଷ୍ଠାଟିରେ କିଛି କାମ କରିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଅନୁମତି ମିଳିନାହିଁ',
'badaccess-groups' => 'ଆପଣ ଅନୁରୋଧ କରିଥିବା କାମଟି କେବଳ {{PLURAL:$2|ଗୋଠ|ଗୋଠମାନଙ୍କ ଭିତରୁ ଗୋଟିଏ ଗୋଠ}}: $1 ର ସଭ୍ୟମାନଙ୍କ ଭିତରେ ସୀମିତ ।',

'versionrequired' => 'ମିଡ଼ିଆଉଇକି ର $1 ତମ ସଙ୍କଳନଟି ଲୋଡ଼ା',
'versionrequiredtext' => 'ଏହି ପୃଷ୍ଠାଟି ବ୍ୟବହାର କରିବା ନିମନ୍ତେ ମିଡ଼ିଆଉଇକିର $1 ତମ ସଙ୍କଳନ ଲୋଡ଼ା ।
[[Special:Version|ସଙ୍କଳନ ପୃଷ୍ଠାଟି]] ଦେଖନ୍ତୁ ।',

'ok' => 'ଠିକ ଅଛି',
'retrievedfrom' => '"$1" ରୁ ଅଣାଯାଇଅଛି',
'youhavenewmessages' => 'ଆପଣଙ୍କର $1 ($2).',
'newmessageslink' => 'ନୂଆ ମେସେଜ',
'newmessagesdifflink' => 'ଶେଷ ବଦଳ',
'youhavenewmessagesfromusers' => 'ଆପଣଙ୍କର {{PLURAL:$3|another user|$3 users}} ($2)ରୁ $1 ଅଛି ।',
'youhavenewmessagesmanyusers' => 'ଆପଣଙ୍କର ବହୁତ ବ୍ୟବହାରକାରୀ($2)ମାନଙ୍କଠାରୁ $1 ଅଛି ।',
'newmessageslinkplural' => '{{PLURAL:$1|a new message|ନୂଆ ମେସେଜ}}',
'newmessagesdifflinkplural' => 'ଶେଷ{{PLURAL:$1|change|changes}}',
'youhavenewmessagesmulti' => '$1 ତାରିଖରେ ନୂଆ ଚିଠିଟିଏ ଆସିଛି',
'editsection' => 'ସମ୍ପାଦନା',
'editold' => 'ଏହାକୁ ବଦଳାନ୍ତୁ',
'viewsourceold' => 'ମୂଳାଧାର ଦେଖିବେ',
'editlink' => 'ସମ୍ପାଦନା',
'viewsourcelink' => 'ମୂଳାଧାର ଦେଖିବେ',
'editsectionhint' => '$1 ଭାଗଟିକୁ ବଦଳାଇବେ',
'toc' => 'ଭିତର ଚିଜ',
'showtoc' => 'ଦେଖାଇବେ',
'hidetoc' => 'ଲୁଚାନ୍ତୁ',
'collapsible-collapse' => 'ଚାପିଦେବେ',
'collapsible-expand' => 'ବଢ଼ାଇବେ',
'thisisdeleted' => '$1 କୁ ଦେଖିବେ ଅବା ପୁନସ୍ଥାପନ କରିବେ?',
'viewdeleted' => 'ଦେଖିବା $1?',
'restorelink' => '{{PLURAL:$1|ଗୋଟିଏ ଲିଭାଯାଇଥିବା ବଦଳ|$1ଟି ଲିଭାଯାଇଥିବା ବଦଳ}}',
'feedlinks' => 'ଫିଡ଼:',
'feed-invalid' => 'ଅଚଳ ସବସ୍କ୍ରିପସନ ଫିଡ଼ ପ୍ରକାର ।',
'feed-unavailable' => 'ସିଣ୍ଡିକେସନ ଫିଡ଼ସବୁ ମିଳୁନାହିଁ',
'site-rss-feed' => '$1 ଆରେସେସ ଫିଡ଼',
'site-atom-feed' => '$1 ଆଟମ ଫିଡ଼',
'page-rss-feed' => '$1 ଟି ଆରେସେସ ଫିଡ଼',
'page-atom-feed' => '$1 ଟି ଆଟମ ଫିଡ଼',
'red-link-title' => ' $1 (ପୃଷ୍ଠାଟି ନାହିଁ)',
'sort-descending' => 'ବଡ଼ରୁ ସାନ କ୍ରମେ ସଜାନ୍ତୁ',
'sort-ascending' => 'ସାନରୁ ବଡ଼ କ୍ରମେ ସଜାନ୍ତୁ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'ପୃଷ୍ଠା',
'nstab-user' => 'ବ୍ୟବହାରକାରୀଙ୍କର ପୃଷ୍ଠା',
'nstab-media' => 'ମେଡିଆ ପରଦ',
'nstab-special' => 'ବିଶେଷ ପୃଷ୍ଠା',
'nstab-project' => 'ପ୍ରକଳ୍ପ ପୃଷ୍ଠା',
'nstab-image' => 'ଫାଇଲ',
'nstab-mediawiki' => 'ସନ୍ଦେଶ',
'nstab-template' => 'ଛାଞ୍ଚ',
'nstab-help' => 'ସାହାଯ୍ୟ ପୃଷ୍ଠା',
'nstab-category' => 'ଶ୍ରେଣୀ',

# Main script and global functions
'nosuchaction' => 'ସେହିଭଳି କିଛି କାମ ନାହିଁ',
'nosuchactiontext' => 'URL ଟିରେ ଦିଆଯାଇଥିବା କାମଟି ଅଚଳ ଅଟେ ।
ଆପଣ ବୋଧ ହୁଏ URL ଟି ଭୁଲ ତାଇପ କରିଥିବେ, ଅଥବା ଲିଙ୍କଟି ଭୁଲ ଥିବ ।
ଏହା ମଧ୍ୟ {{SITENAME}}ରେ ବ୍ୟବହାର କରାଯାଇଥିବା ସଫ୍ଟବେରରେ ଥିବା କିଛି ଭୁଲକୁ ସୂଚାଇପାରେ ।',
'nosuchspecialpage' => 'ସେହି ଭଳି କିଛି ବି ବିଶେଷ ପୃଷ୍ଠା ନାହିଁ',
'nospecialpagetext' => '<strong>ଆପଣ ଅଚଳ ବିଶେଷ ପୃଷ୍ଠାଟିଏ ପାଇଁ ଆବେଦନ କରିଅଛନ୍ତି ।</strong>

[[Special:SpecialPages|{{int:specialpages}}]]ରେ ଅନେକ ଗୁଡ଼ିଏ ସଚଳ ସଚଳ ବିଶେଷ ପୃଷ୍ଠା ମିଳିପାରିବ ।',

# General errors
'error' => 'ଭୁଲ',
'databaseerror' => 'ଡାଟାବେସରେ ଭୁଲ',
'dberrortext' => 'ଏହା ଏହି ସଫ୍ଟବେରରେ ଭୁଲଟିଏକୁ ମଧ୍ୟ ସୂଚାଇପାରେ ।
ଶେଷଥର ଖୋଜାଯାଇଥିବା ଡାଟାବେସ ପ୍ରଶ୍ନଟି ଥିଲା:
<blockquote><code>$1</code></blockquote>
 ଯାହାକି "<code>$2</code>"ରୁ ଥିଲା
ଡାଟାବେସରେ ହୋଇଥିବା ଭୁଲ ହେଉଛି "<samp>$3: $4</samp>"।',
'dberrortextcl' => 'ଡାଟାବେସ ପ୍ରଶ୍ନ ଖୋଜା ଭୁଲଟିଏ ହୋଇଅଛି ।
ଶେଷ ଖୋଜା ଡାଟାବେସ ପ୍ରଶ୍ନଟି ଥିଲା:
"$1"
ଯାହା "$2" ଭିତରୁ ନିଆଯାଇଥିଲା ।
ଡାଟାବେସ ଫେରନ୍ତା ଭୁଲ "$3: $4"',
'laggedslavemode' => "'''ଜାଣିରଖନ୍ତୁ:''' ପୃଷ୍ଠାଟିରେ ବୋଧ ହୁଏ ନଗଦ ବଦଳ ନ ଥାଇପାରେ ।",
'readonly' => 'ଡାଟାବେସଟିରେ ତାଲା ପଡ଼ିଅଛି',
'enterlockreason' => 'କେତେ ଦିନ ଭିତରେ ଏହା ଖୋଲାଯିବ ତାହାର ଅଟକଳ ସହିତ କଞ୍ଚି ପଡ଼ିବାର କାରଣ ଦିଅନ୍ତୁ',
'readonlytext' => 'ଏହି ଡାଟାବେସଟିରେ ଅଧୁନା ନୂଆ ପ୍ରସଙ୍ଗ ଯୋଗ ଓ ବାକି ବଦଳ ପାଇଁ ତାଲା ପଡ଼ିଅଛି, ଏହା ଡାଟାବେସର ନିତିଦିନିଆ ରକ୍ଷଣାବେକ୍ଷଣା ନିମନ୍ତେ ହୋଇଥାଇପାରେ, ଯାହା ପରେ ଏହ ପୁଣି ସାଧାରଣ ଭାବେ କାମ କରିବ ।

ଏଥିରେ ତାଲା ପକାଇଥିବା ପରିଛାଙ୍କ ତାଲା ପକାଇବାର କାରଣ: $1',
'missing-article' => 'ଡାଟାବେସଟି ଆପଣ ଖୋଜିଥିବା "$1" $2 ଶବ୍ଦଟି ପାଇଲା ନାହିଁ । .

ଯଦି ଆପଣ ଖୋଜିଥିବା ପୃଷ୍ଠାଟି କେହି ଉଡ଼ାଇ ଦେଇଥାଏ ତେବେ ଏମିତି ହୋଇପାରେ ।

ଯଦି ସେମିତି ହୋଇନଥାଏ ତେବେ ଆପଣ ଏହି ସଫ୍ଟୱେରରେ କିଛି ଅସୁବିଧା ଖୋଜି ପାଇଛନ୍ତି ।
କେହି ଜଣେ [[Special:ListUsers/sysop|ପରିଚାଳକ]] ଙ୍କୁ ଏହି ଇଉଆରଏଲ (url) ସହ ଚିଠିଟିଏ ପଠାଇ ଦିଅନ୍ତୁ ।',
'missingarticle-rev' => '(ସଙ୍କଳନ#: $1)',
'missingarticle-diff' => '(ତଫାତ: $1, $2)',
'readonly_lag' => 'ଏହି ଡାଟାବେସଟିରେ ଆପେ ଆପେ ତାଲା ପଡ଼ିଯାଇଅଛି, ଇତିମଧ୍ୟରେ ସାନ ଡାଟାବେସଟି ମୁଖ୍ୟ ଡାଟାବେସ ସହିତ ଯୋଗାଯୋଗ କରୁଅଛି',
'internalerror' => 'ଭିତରର ଭୁଲ',
'internalerror_info' => 'ଭିତରର ଭୁଲ : $1',
'fileappenderrorread' => 'ଯୋଡ଼ିବା ବେଳେ "$1"କୁ ପଢ଼ିପାରିଲୁଁ ନାହିଁ ।',
'fileappenderror' => '"$1" ସହ "$2" କୁ ଯୋଡ଼ିପାରିଲୁଁ ନାହିଁ ।',
'filecopyerror' => '"$1" ରୁ "$2" କୁ ନକଲ କରିପାରିଲୁଁ ନାହିଁ ।',
'filerenameerror' => '"$1" ରୁ "$2" କୁ ନାମ ବଦଳ କରିପାରିଲୁଁ ନାହିଁ ।',
'filedeleteerror' => '"$1" ଫାଇଲଟି ଲିଭାଇ ପାରିଲୁଁ ନାହିଁ ।',
'directorycreateerror' => '"$1" ସୂଚିଟି ତିଆରି କରିପାରିଲୁଁ ନାହିଁ ।',
'filenotfound' => '"$1" ଫାଇଲଟି ପାଇଲୁ ନାହିଁ ।',
'fileexistserror' => '"$1" ଫାଇଲଟି ଲେଖିପାରିଲୁଁ ନାହିଁ: ଏହା ଆଗରୁ ଅଛି',
'unexpected' => 'ଅଜଣା ନାମ ମିଳିଲା: "$1"="$2" ।',
'formerror' => 'ଭୁଲ: ଫର୍ମଟି ପଠାଇ ପାରିଲୁଁ ନାହିଁ',
'badarticleerror' => 'ଏହି ପୃଷ୍ଠାରେ ଏହି କାମଟି ହୋଇପାରିବ ନାହିଁ ।',
'cannotdelete' => '"$1" ପୃଷ୍ଠା ବା ଫାଇଲଟି ଲିଭାଯାଇପାରିବ ନାହିଁ । ଏହା ଆଗରୁ କାହା ଦେଇ ବୋଧେ ଲିଭାଇ ଦିଆଯାଇଛି ।',
'cannotdelete-title' => '"$1" ପୃଷ୍ଠାଟି ଲିଭଯାଇପାରିବ ନାହିଁ',
'delete-hook-aborted' => 'ସମ୍ପାଦନା ଏକ ହୁକ (hook) ଦେଇ ବାରଣ କରାଗଲା ।
ଏହା କିଛି ବି କାରଣ ଦେଇନାହିଁ ।',
'badtitle' => 'ଖରାପ ନାଆଁ',
'badtitletext' => 'ଆପଣ ଅନୁରୋଧ କରିଥିବା ପୃଷ୍ଠାଟି ଭୁଲ, ଖାଲି ଅଛି ବା ବାକି ଭାଷା ସାଙ୍ଗରେ ଭୁଲରେ ଯୋଡ଼ା ଯାଇଛି ବା ଭୁଲ ଇଣ୍ଟର ଉଇକି ନାମ ଦିଆଯାଇଛି ।
ଏଥିରେ ଥିବା ଗୋଟିଏ ବା ଦୁଇଟି ଅକ୍ଷର ଶିରୋନାମା ଭାବରେ ବ୍ୟବହାର କରାଯାଇ ପାରିବ ନାହିଁ ।',
'perfcached' => 'ତଳଲିଖିତ ତଥ୍ୟଗୁଡିକୁ ଅସ୍ଥାୟୀ ଭାବେ ରଖାଗଲା ଏବଂ ଏହା ଅପଡେଟ ନ ହୋଇପାରେ । ଅତିବେଶିରେ {{PLURAL:$1|ଫଳ|$1ଫଳଗୁଡିକ }} ଅସ୍ଥାୟୀ ରୂପେ ରହି ପାରିବ ।',
'perfcachedts' => 'ତଳଲିଖିତ ତଥ୍ୟଗୁଡିକୁ ଅସ୍ଥାୟୀ ଭାବେ ରଖାଗଲା ଏବଂ  $1ରେ ଶେଷଥର ଅପଡେଟ ହୋଇଥିଲା । ଅତିବେଶିରେ {{PLURAL:$1|ଫଳ|$1ଫଳଗୁଡିକ }} ଅସ୍ଥାୟୀ ରୂପେ ରହି ପାରିବ ।',
'querypage-no-updates' => 'ଏହି ପୃଷ୍ଠାଟି ପାଇଁ ଅପଡେଟସବୁ ଏବେ ଅଚଳ କରାଯାଇଅଛି ।
ଏଠାରେ ଥିବା ତଥ୍ୟ ସବୁ ଏବେ ସତେଜ ହୋଇପାରିବ ନାହିଁ ।',
'wrong_wfQuery_params' => 'wfQuery() ପାଇଁ ଭୁଲ ପାରାମିଟର<br />
କାମ: $1<br />
ଖୋଜା ପ୍ରଶ୍ନ: $2',
'viewsource' => 'ମୂଳାଧାର ଦେଖିବେ',
'viewsource-title' => '$1 ନିମନ୍ତେ ଆଧାର ଦେଖିବେ',
'actionthrottled' => 'କାମଟି ବନ୍ଦ କରିଦିଆଗଲା',
'actionthrottledtext' => 'ସ୍ପାମକୁ ବନ୍ଦ କରିବା ନିମନ୍ତେ ଏକ ଅଳ୍ପ ସମୟ ବିରତି ଭିତରେ ଆପଣଙ୍କୁ ଏହି କାମଟୀ ବାରମ୍ବାର କରିବାକୁ ଅନୁମତି ଦିଆଯାଉନାହିଁ ଓ ଆପଣ ସୀମା ପାର କରିଯାଇଛନ୍ତି ।
ଦୟାକରି କିଛି ସମୟ ପରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'protectedpagetext' => 'ଏହି ପୃଷ୍ଠାଟି ସମ୍ପାଦନା କିମ୍ବା ଅନ୍ୟକୌଣସି କାର୍ଯ୍ୟ କରିବାରୁ କିଳାଯାଇଛି ।',
'viewsourcetext' => 'ଆପଣ ଏହି ପୃଷ୍ଠାର ଲେଖା ଦେଖିପାରିବେ ଓ ନକଲ କରିପାରିବେ:',
'viewyourtext' => "ଆପଣ '''ଆପଣଙ୍କ ସମ୍ପାଦିତ ''' ଅଧରଟିକୁ ଦେଖିପାରିବେ ଓ ଏହି ପୃଷ୍ଠାକୁ ନକଲ କରି ପାରିବେ",
'protectedinterface' => 'ଏହି ପୃଷ୍ଠାଟି ଏହି ଉଇକିରେ ଥିବା ସଫ୍ଟୱେର ନିମନ୍ତେ ଇଣ୍ଟରଫେସ ଲେଖା ଯୋଗାଇଥାଏ ଓ ଏହା ଅବ୍ପୟବହାରକୁ ରୋକିବା ନିମନ୍ତେ କିଳାଯାଇଅଛି । ସମସ୍ତ ଉଇକିର ଅନୁବାଦକୁ ଯୋଡିବା ଏବଂ ବଦଳାଇବା ପାଇଁ ମେଡିଆଉଇକିର ସ୍ଥାନୀୟ ପ୍ରକଳ୍ପରେ ଥିବା [//translatewiki.net/ translatewiki.net]କୁ ବ୍ୟବହାର କରନ୍ତୁ ।',
'editinginterface' => "'''ଚେତାବନୀ:''' ଆପଣ ସଫ୍ଟବେରର ଇଣ୍ଟରଫେସ ଲେଖା ଯୋଗାଇବା ନିମନ୍ତେ ବ୍ୟବହାର କରାଯାଉଥିବା ଏକ ପୃଷ୍ଠାର ସମ୍ପାଦନା କରୁଅଛନ୍ତି ।
ଏହି ଉଇକିପୃଷ୍ଠାର କିଛି ବି ବଦଳ ବାକି ସଭ୍ୟମାନଙ୍କ ଇଣ୍ଟରଫେସର ଦେଖଣାକୁ ପ୍ରଭାବିତ କରିବ ।
ସମସ୍ତ ଉଇକିର ଅନୁବାଦ ନିମନ୍ତେ, ଦୟାକରି ମିଡ଼ିଆଉଇକିର ସ୍ଥାନୀୟକରଣ ପ୍ରକଳ୍ପ [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net] ବ୍ୟବହାର କରନ୍ତୁ ।",
'sqlhidden' => '(SQL ପ୍ରଶ୍ନ ଲୁଚାଯାଇଅଛି)',
'cascadeprotected' => 'ଏହି ପୃଷ୍ଠା ସମ୍ପାଦନା କରିବାରୁ କିଳାଯାଇଅଛି, କାରଣ ଏଥିରେ ତଳଲିଖିତ {{PLURAL:$1|ପୃଷ୍ଠାଟିଏ ଅଛି|ଟି ପୃଷ୍ଠା ଅଛି}} ଯାହା "କ୍ୟାସକେଡ଼ କରା" ସୁବିଧା ଦେଇ କିଳାଯାଇଅଛି ।:
$2',
'namespaceprotected' => "ଆପଣଙ୍କୁ ଏହି '''$1''' ନେମସ୍ପେସ ଥିବା ପୃଷ୍ଠାରେ ସମ୍ପାଦନା କରିବା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ ।",
'customcssprotected' => 'ଆପଣଙ୍କୁ ଏହି CSS ପୃଷ୍ଠାର ସମ୍ପାଦନା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ, କାରଣ ଏଥିରେ ଆଉଜଣେ ସଭ୍ୟଙ୍କର ବ୍ୟକ୍ତିଗତ ସଜାଣି ରହିଅଛି ।',
'customjsprotected' => 'ଆପଣଙ୍କୁ ଏହି ଜାଭାସ୍କ୍ରିପ୍ଟ ପୃଷ୍ଠାର ସମ୍ପାଦନା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ, କାରଣ ଏଥିରେ ଆଉଜଣେ ସଭ୍ୟଙ୍କର ବ୍ୟକ୍ତିଗତ ସଜାଣି ରହିଅଛି ।',
'ns-specialprotected' => 'ବିଶେଷ ପୃଷ୍ଠାସବୁକୁ ବଦଳାଯାଇପାରିବ ନାହିଁ ।',
'titleprotected' => 'ଏହି ନାମଟି [[User:$1|$1]]ଙ୍କ ଦେଇ ନୂଆ ତିଆରିହେବାରୁ କିଳାଯାଇଅଛି ।
ଏହାର କାରଣ ହେଲା "\'\'$2\'\'" ।',
'filereadonlyerror' => 'ଫାଇଲ ଧାରକ "$2"ଟି ଖାଲି ପଢିବା ହେବାଭଳି ରହିଥିବା ହେତୁ ଏଥିରେ ଥିବା $1 ପାଇଲଟିକୁ ବଦଳା ଯାଇପାରିବ ନାହିଁ ।

ଯେଉଁ ପରିଚ୍ଛା ଏହାକୁ ବନ୍ଦ କରିଛନ୍ତି ସେ ଏହି ବିବରଣୀ ଦେଇଛନ୍ତି: "$3"',
'invalidtitle-knownnamespace' => '"$2" ନେମ୍ସ୍ପେସ ଏବଂ "$3" ଲେଖାଥିବା ଅବୈଧ ଶୀର୍ଷକ ।',
'invalidtitle-unknownnamespace' => '"$1" ନେମ୍ସ୍ପେସ ଏବଂ "$2" ଲେଖାଥିବା ଅବୈଧ ଶୀର୍ଷକ ।',
'exception-nologin' => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
'exception-nologin-text' => 'ଏହା କରିବାକୁ ହେଲେ ଆପଣଙ୍କୁ ଏହି ଉଇକିରେ ଲଗଇନ କରିବାକୁ ପଡିବ ।',

# Virus scanner
'virus-badscanner' => "ମନ୍ଦ ସଂରଚନା: ଅଜଣା ଭାଇରସ ସ୍କାନର: ''$1''",
'virus-scanfailed' => 'ସ୍କାନ କରିବା ବିଫଳ ହେଲା (କୋଡ଼ $1)',
'virus-unknownscanner' => 'ଅଜଣା ଆଣ୍ଟିଭାଇରସ:',

# Login and logout pages
'logouttext' => "'''ଆପଣ ଲଗାଆଉଟ କରିଦେଲେ'''

ଆପଣ ଅଜଣା ଭାବରେ {{SITENAME}}କୁ ଯାଇପାରିବେ, କିମ୍ବା [[Special:UserLogin|ଆଉଥରେ]] ଆଗର ଇଉଜର ନାଆଁରେ/ଅଲଗା ନାଆଁରେ ଲଗଇନ କରିପାରିବେ ।
ଜାଣିରଖନ୍ତୁ, କିଛି ପୃଷ୍ଠା ଲଗାଆଉଟ କଲାପରେ ବି ଆଗପରି ଦେଖାଯାଇପାରେ, ଆପଣ ବ୍ରାଉଜର କାସକୁ ହଟାଇଲା ଯାଏଁ ଏହା ଏମିତି ରହିବ ।",
'welcomecreation' => '== $1!, ଆପଣଙ୍କ ଖାତାଟି ତିଆରି ହୋଇଗଲା==
ତେବେ, ନିଜର [[Special:Preferences|{{SITENAME}} ପସନ୍ଦସବୁକୁ]] ବଦଳାଇବାକୁ ଭୁଲିବେ ନାହିଁ ।',
'yourname' => 'ବ୍ୟବହାରକାରୀଙ୍କ ନାମ:',
'yourpassword' => 'ପାସୱାର୍ଡ଼',
'yourpasswordagain' => 'ପାସୱାର୍ଡ଼ ଆଉଥରେ:',
'remembermypassword' => 'ଏହି ବ୍ରାଉଜରରେ (ସବୁଠୁ ଅଧିକ ହେଲେ $1 {{PLURAL:$1|day|ଦିନ}}) ପାଇଁ ମୋ ଲଗଇନ ମନେ ରଖିଥିବେ',
'securelogin-stick-https' => 'ଲଗ ଇନ କଲାପରେ HTTPS ସହ ଯୋଡ଼ି ହୋଇ ରହନ୍ତୁ',
'yourdomainname' => 'ଆପଣଙ୍କ ଡୋମେନ:',
'password-change-forbidden' => 'ଆପଣ ଏହି ଉଇକିରେ ପାସୱାର୍ଡ ବଦଳାଇ ପାରିବେ ନାହିଁ ।',
'externaldberror' => 'ବୋଧ ହୁଏ ଚିହ୍ନଟ ଡାଟାବେସ ଭୁଲଟିଏ ହୋଇଥିଲା ବା ଆପଣଙ୍କୁ ନିଜର ବାହାର ଖାତା ଅପଡେଟ କରିବା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ ।',
'login' => 'ଲଗଇନ',
'nav-login-createaccount' => 'ଲଗ ଇନ /ନୂଆ ଖାତା ଖୋଲନ୍ତୁ',
'loginprompt' => "{{SITENAME}}ରେ ଲଗ ଇନ କରିବାପାଇଁ ଆପଣଙ୍କୁ '''କୁକି''' ସଚଳ କରିବାକୁ ପଡ଼ିବ ।",
'userlogin' => 'ଲଗ ଇନ /ନୂଆ ଖାତା ଖୋଲନ୍ତୁ',
'userloginnocreate' => 'ଲଗ ଇନ',
'logout' => 'ଲଗଆଉଟ',
'userlogout' => 'ଲଗ ଆଉଟ',
'notloggedin' => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
'nologin' => 'ଖାତାଟିଏ ନାହିଁ? $1।',
'nologinlink' => 'ନୂଆ ଖାତାଟିଏ ଖୋଲନ୍ତୁ',
'createaccount' => 'ନୂଆ ଖାତାଟିଏ ଖୋଲନ୍ତୁ',
'gotaccount' => 'ଆଗରୁ ଖାତାଟିଏ ଅଛି କି? $1.',
'gotaccountlink' => 'ଲଗ ଇନ',
'userlogin-resetlink' => 'ଲଗଇନ ତଥ୍ୟ ସବୁ ଭୁଲିଗେଲେକି?',
'createaccountmail' => 'ଇ-ମେଲ ରୁ',
'createaccountreason' => 'କାରଣ:',
'badretype' => 'ଆପଣ ଦେଇଥିବା ପାସବାର୍ଡ଼ଟି ମେଳଖାଉନାହିଁ ।',
'userexists' => 'ଆପଣ ଦେଇଥିବା ଇଉଜର ନାମ ଆଗରୁ ଅଛି ।
ଦୟାକରି ଅଲଗା ନାମଟିଏ ବାଛନ୍ତୁ ।',
'loginerror' => 'ଲଗ‌‌ଇନ ଭୁଲ',
'createaccounterror' => '$1 ନାମରେ ଖାତାଟିଏ ଖୋଲାଯାଇପାରିଲା ନାହିଁ',
'nocookiesnew' => 'ଇଉଜର ନାମଟି ତିଆରି କରିଦିଆଗଲା, ହେଲେ ଆପଣ ଲଗ ଇନ କରିନାହାନ୍ତି ।
{{SITENAME}} ସଭ୍ୟମାନଙ୍କୁ ଲଗ ଇନ କରିବା ନିମନ୍ତେ କୁକି ବ୍ୟବହାର କରିଥାଏ । ଆପଣଙ୍କ କୁକି ଅଚଳ କରାଯାଇଅଛି ।
ଦୟାକରି ତାହାକୁ ସଚଳ କରନ୍ତୁ ଓ ତାହା ପରେ ଆପଣଙ୍କ ନୂଆ ଇଉଜର ନାମ ଓ ପାସୱାର୍ଡ଼ ସହିତ ଲଗ ଇନ କରନ୍ତୁ ।',
'nocookieslogin' => '{{SITENAME}} ସଭ୍ୟ ମାନଙ୍କୁ ଲଗ ଇନ କରାଇବା ପାଇଁ କୁକି ବ୍ୟବହାର କରିଥାଏ ।
ଆପଣଙ୍କର କୁକି ଅଚଳ ହୋଇଅଛି ।
ଦୟାକରି ତାହାକୁ ସଚଳ କରି ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'nocookiesfornew' => 'ଯେହେତୁ ଆମ୍ଭେ ଏହାର ମୂଳାଧାର ଜାଣିପାରିଲୁ ନାହିଁ ଏହି ଇଉଜର ଖାତାଟି ତିଆରି କରାଗଲା ନାହିଁ ।
ଥୟ କରନ୍ତୁ କି ଆପଣ କୁକି ସଚଳ କରିଅଛନ୍ତି, ପୃଷ୍ଠାଟିକୁ ଆଉଥରେ ଲୋଡ଼ କରି ଚେଷ୍ଟା କରନ୍ତୁ ।',
'noname' => 'ଆପଣ ଗୋଟିଏ ବୈଧ ଇଉଜର ନାମ ଦେଇନାହାନ୍ତି ।',
'loginsuccesstitle' => 'ଠିକଭାବେ ଲଗଇନ ହେଲା',
'loginsuccess' => "'''ଆପଣ {{SITENAME}}ରେ \"\$1\" ଭାବରେ ଲଗଇନ କରିଛନ୍ତି ।'''",
'nosuchuser' => '"$1" ନାମରେ କେହି ଜଣେ ବି ସଭ୍ୟ ନାହାନ୍ତି ।
ଇଉଜର ନାମ ଇଂରାଜୀ ଛୋଟ ଓ ବଡ଼ ଅକ୍ଷର ପ୍ରତି ସମ୍ବେଦନଶୀଳ ।
ଆପଣ ନିଜର ବନାନ ପରଖି ନିଅନ୍ତୁ, ଅଥବା [[Special:UserLogin/signup|ନୂଆ ଖାତାଟିଏ ତିଆରି କରନ୍ତୁ]] ।',
'nosuchusershort' => '"$1" ନାମରେ କେହି ଜଣେ ବି ସଭ୍ୟ ନାହାନ୍ତି ।
ଆପଣ ବନାନ ପରଖି ନିଅନ୍ତୁ ।',
'nouserspecified' => 'ଆପଣଙ୍କୁ ଇଉଜର ନାମଟିଏ ଦେବାକୁ ପଡ଼ିବ ।',
'login-userblocked' => 'ଏହି ସଭ୍ୟଙ୍କୁ ଅଟକାଯାଇଛି । ଲଗ ଇନ କରିବାକୁ ଅନୁମତି ନାହିଁ ।',
'wrongpassword' => 'ଦିଆଯାଇଥିବା ପାସବାର୍ଡ଼ଟି ଭୁଲ ଅଟେ  ।
ଦୟାକରି ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'wrongpasswordempty' => 'ଦିଆଯାଇଥିବା ପାସବାର୍ଡ଼ଟି ଖାଲି ଛଡ଼ାଯାଇଛି ।
ଦୟାକରି ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'passwordtooshort' => 'ପାସବାର୍ଡ଼ଟି ଅତି କମରେ {{PLURAL:$1|ଗୋଟିଏ ଅକ୍ଷର|$1ଟି ଅକ୍ଷର}}ର ହୋଇଥିବା ଲୋଡ଼ା ।',
'password-name-match' => 'ଆପଣଙ୍କ ପାସବାର୍ଡ଼ଟି ଆପଣଙ୍କ ଇଉଜର ନାମ ଠାରୁ ଅଲଗା ହେବା ଉଚିତ ।',
'password-login-forbidden' => 'ଏହି ଇଉଜର ନାମ ଓ ପାସବାର୍ଡ଼ର ବ୍ୟବହାରକୁ ବାରଣ କରାଯାଇଅଛି ।',
'mailmypassword' => 'ପାସବାର୍ଡ଼ଟିକୁ ଇମେଲ କରି ପଠାଇବେ',
'passwordremindertitle' => '{{SITENAME}} ପାଇଁ ନୂଆ ଅଳ୍ପ କାଳର ପାସୱାର୍ଡ଼',
'passwordremindertext' => 'କେହିଜଣେ (ବୋଧେ ଆପଣ, $1 IP ଠିକଣାରୁ) 
ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ପାଇଁ {{SITENAME}} ($4) ରେ ଆବେଦନ କରିଅଛନ୍ତି । "$2"ଙ୍କ ପାଇଁ ଏକ ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼
ତିଆରି କରିଦିଆଗଲା ଓ ତାହାକୁ "$3" ପାଇଁ ଖଞ୍ଜି ଦିଆଗଲା । ଯଦି ଏହା ଆପଣଙ୍କର
ଇଛା ତେବେ ଆପଣଙ୍କୁ ଲଗ ଇନ କରି ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ଏବେ ଦେବାକୁ ପଡ଼ିବ ।
Your temporary password will expire in {{PLURAL:$5|one day|$5 days}}.

If someone else made this request, or if you have remembered your password,
and you no longer wish to change it, you may ignore this message and
continue using your old password.',
'noemail' => 'ସଭ୍ୟ "$1"ଙ୍କ ପାଇଁ କିଛି ବି ଇ-ମେଲ ଆଇ.ଡି. ସାଇତାଯାଇନାହିଁ  ।',
'noemailcreate' => 'ଆପଣଙ୍କୁ ଏକ ସଚଳ ଇ-ମେଲ ଠିକଣା ଦେବାକୁ ପଡ଼ିବ',
'passwordsent' => '"$1" ପାଇଁ ଥୟ କରାଯାଇଥିବା ଇ-ମେଲକୁ ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ପଠାଇଦିଆଗଲା ।
ତାହା ମିଳିଲା ପରେ ଆଉଥରେ ଲଗ ଇନ କରନ୍ତୁ ।',
'blocked-mailpassword' => 'ଆପଣଙ୍କ IP ଠିକଣାଟି ସମ୍ପାଦନାରେ ଭାଗ ନେବାରୁ ଅଟକାଯାଇଛି, ତେଣୁ ପାସବାର୍ଡ଼ ଫେରନ୍ତା କାମ ବ୍ୟବହାର କରି ଅବ୍ୟବହାରକୁ ରୋକିବା ଅନୁମୋଦିତ ନୁହେଁ ।',
'eauthentsent' => 'ଆପଣଙ୍କ ବଛା ଇ-ମେଲ ଠିକଣାକୁ ଏକ ଥୟ କରିବା ଇ-ମେଲଟିଏ ପଠାଇଦିଆଗଲା ।
ଖାତାଟି ଆପଣଙ୍କର ବୋଲି ଥୟ କରିବା ନିମନ୍ତେ ଆଉ କେଉଁ ଇ-ମେଲ ଆପଣଙ୍କ ଖାତାକୁ ପଠାହେବା ଆଗରୁ ଆପଣଙ୍କୁ ସେହି ଇ-ମେଲରେ ଥିବା ସୂଚନା ଅନୁସରଣ କରିବାକୁ ପଡ଼ିବ ।',
'throttled-mailpassword' => 'ଗତ {{PLURAL:$1|ଏକ ଘଣ୍ଟାରେ|$1 ଘଣ୍ଟାରେ}} ଆପଣଙ୍କୁ ଏକ ପାସବାର୍ଡ଼ ମନେକରିବା ସୂଚନାଟିଏ ପଠାଯାଇଛି ।
ଅବ୍ୟବହାରକୁ ରୋକିବା ନିମନ୍ତେ, {{PLURAL:$1|ଏକ ଘଣ୍ଟାରେ|$1 ଘଣ୍ଟାରେ}} କେବଳ ଗୋଟିଏ ପାସବାର୍ଡ଼ ହିଁ ପଠାହେବ ।',
'mailerror' => 'ମେଲ ପଠାଇବାରେ ଭୁଲ : $1',
'acct_creation_throttle_hit' => 'ଏହି ଉଇକିର ଦେଖଣାହାରୀ ମାନେ ଆପଣଙ୍କ IP ଠିକଣା ବ୍ୟବହାର କରି ବିଗତ ଦିନରେ {{PLURAL:$1|ଖାତାଟିଏ|$1 ଗୋଟି ଖାତା}} ତିଆରି କରିଛନ୍ତି ଯାହା ସେହି ସମୟସୀମା ଭିତରେ ସବୁଠାରୁ ଅଧିକ ଥିଲା ।
ତେଣୁ, ଏହି IP ଠିକଣାର ଦେଖଣାହାରୀ ଗଣ ଏବେ ଆଉ ଅଧିକ ଖାତା ଖୋଲିପାରିବେ ନାହିଁ ।',
'emailauthenticated' => '$2 ତାରିଖ $3 ଘଟିକା ବେଳେ ଆପଣଙ୍କ ଇ-ମେଲ ଠିକଣାଟି ଅନୁମୋଦିତ ହେଲା ।',
'emailnotauthenticated' => 'ଆପନଙ୍କ ଇ-ମେଲ ଠିକଣାଟି ଅନୁମୋଦିତ୍ ହୋଇନାହିଁ ।
ଏହି ସବୁ ସୁବିଧାକୁ ନେଇ କିଛି ବି ଇ-ମେଲ ଆପଣଙ୍କୁ ପଠାଯିବ ନାହିଁ ।',
'noemailprefs' => 'ଆପଣଙ୍କ ପସନ୍ଦ ଭିତରେ ଏକ ଇ-ମେଲ ଠିକଣା ଦିଅନ୍ତୁ ଯାହା ଏହି ସବୁ ସୁବିଧାକୁ ସଚଳ କରାଇବ ।',
'emailconfirmlink' => 'ଆପଣଙ୍କ ଇମେଲ ଆଇ.ଡି.ଟି ଠିକ ବୋଲି ଥୟ କରନ୍ତୁ',
'invalidemailaddress' => 'ଏହି ଇ-ମେଲ ଠିକଣାଟି ସଠିକ ସଜାଣିରେ ନଥିବାରୁ ଏହାକୁ ଗ୍ରହଣ କରାଯାଇପାରିବ ନାହିଁ ।
ଦୟାକରି ଏକ ସଚଳ ଓ ଠିକ ସଜାଣିରେ ଥିବା ଇ-ମେଲ ଠିକଣା ଦିଅନ୍ତୁ ।',
'cannotchangeemail' => 'ଖାତାରେ ଥିବା ଇମେଲ ଏହି ଉଇକିରେ ବଦଳାଯାଇପାରିବ ନାହିଁ ।',
'emaildisabled' => 'ଏହି ସାଇଟ ଇ-ମେଲ ପଠାଇ ପାରିବ ନାହିଁ ।',
'accountcreated' => 'ଖାତାଟି ଖୋଲାହୋଇଗଲା',
'accountcreatedtext' => '$1 ପାଇଁ ନୂଆ ଖାତାଟିଏ ତିଆରି ହୋଇଗଲା ।',
'createaccount-title' => '{{SITENAME}} ପାଇଁ ଖାତା ଖୋଲା',
'createaccount-text' => 'କେହି ଜଣେ ଆପଣଙ୍କ ଇ-ମେଲ ଠିକଣାରେ {{SITENAME}} ($4) ରେ "$2" ନାମରେ, "$3" ପାସବାର୍ଡ଼ରେ ଖାତାଟିଏ ତିଆରି କରିଅଛି ।
ଆପଣ ଏବେ ଲଗ ଇନ କରି ନିଜର ପାସବାର୍ଡ଼ଟିକୁ ବଦଳାଇଦିଅନ୍ତୁ ।

ଯଦି ଭୁଲରେ ଏହି ଖାତାଟି ତିଆରି କରାଯାଇଥାଏ ତେବେ ଏହି ସୂଚନାଟିକୁ ଅଣଦେଖା କରିବେ ।',
'usernamehasherror' => 'ଇଉଜର ନାମରେ ହାସ ଅକ୍ଷର (hash characters) ରହି ପାରିବନାହିଁ',
'login-throttled' => 'ଆପଣ ବହୁ ଥର ଲଗ ଇନ କରିବାର ଉଦ୍ୟମ କରିଅଛନ୍ତି ।
ଦୟାକରି ଆଉଥରେ ଚେଷ୍ଟା କରିବା ଆଗରୁ କିଛି କାଳ ଅପେକ୍ଷ କରନ୍ତୁ ।',
'login-abort-generic' => 'ଆପଣଙ୍କ ଲଗ ଇନ ଅସଫଳ ହେଲା - ନାକଚ କରିଦିଆଗଲା',
'loginlanguagelabel' => 'ଭାଷା: $1',
'suspicious-userlogout' => 'ଲଗ ଆଉଟ କରିବା ନିମନ୍ତେ ଆପଣ କରିଥିବା ଆବେଦନ ନାକଚ କରିଦିଆଗଲା କାରଣ ଲାଗୁଅଛି ଯେ ଏହା ଏକ ଅସ୍ଥିର ବ୍ରାଉଜରରୁ ପଠାଯାଇଅଛି ଅବା ପ୍ରକ୍ସି ଧରାଯାଇଅଛି ।',

# E-mail sending
'php-mail-error-unknown' => 'PHP ର ମେଲ() କାମରେ ଅଜଣା ଅସୁବିଧା ।',
'user-mail-no-addy' => 'ଏକ ଇ-ମେଲ ଠିକଣା ବିନା ଇ-ମେଲ ପଠାଇବାକୁ ଚେଷ୍ଟା କଲୁଁ ।',

# Change password dialog
'resetpass' => 'ପାସୱାର୍ଡ଼ ବଦଳାନ୍ତୁ',
'resetpass_announce' => 'ଆପଣ ଏକ ଅସ୍ଥାୟୀ ଇ-ମେଲରେ ଯାଇଥିବା କୋଡ଼ ସହାୟତାରେ ଲଗ ଇନ କରିଅଛନ୍ତି ।
ଲଗ ଇନ ଶେଷ କରିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଏହିଠାରେ ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ଦେବାକୁ ପଡ଼ିବ:',
'resetpass_header' => 'ଖାତାର ପାସବାର୍ଡ଼ଟିକୁ ବଦଳାଇ ଦିଅନ୍ତୁ',
'oldpassword' => 'ପୁରୁଣା ପାସୱାର୍ଡ଼:',
'newpassword' => 'ନୂଆ ପାସୱାର୍ଡ଼:',
'retypenew' => 'ପାସୱାର୍ଡ଼ ଆଉଥରେ ଦିଅନ୍ତୁ:',
'resetpass_submit' => 'ପାସବାର୍ଡ଼ଟି ଦେଇ ଲଗ ଇନ କରନ୍ତୁ',
'resetpass_success' => 'ଆପଣଙ୍କ ପାସବାର୍ଡ଼ଟି ବଦଳାଇ ଦିଆଗଲା !
ଏବେ ଲଗ ଇନ କରୁଅଛୁଁ...',
'resetpass_forbidden' => 'ପାସବାର୍ଡ଼ମାନ ବଦଳା ଯାଇପାରିବ ନାହିଁ',
'resetpass-no-info' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ସିଧା ଖୋଲିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଲଗ ଇନ କରିବାକୁ ପଡ଼ିବ ।',
'resetpass-submit-loggedin' => 'ପାସୱାର୍ଡ଼ ବଦଳାନ୍ତୁ',
'resetpass-submit-cancel' => 'ନାକଚ',
'resetpass-wrong-oldpass' => 'ଅସ୍ଥାୟୀ ବା ଏବେକାର ପାସବାର୍ଡ଼ଟି ଭୁଲ ଅଟେ ।
ଆପଣ ବୋଧ ହୁଏ ଆଗରୁ ସଫଳ ଭାବରେ ନିଜର ପାସବାର୍ଡ଼ଟି ବଦଳାଇଦେଇଛନ୍ତି ବା ନୂଆ ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼ଟିଏ ପାଇଁ ଆବେଦନ କରିଅଛନ୍ତି ।',
'resetpass-temp-password' => 'ଅସ୍ଥାୟୀ ପାସୱାର୍ଡ଼:',

# Special:PasswordReset
'passwordreset' => 'ପାସୱାର୍ଡ଼ ପୁନସ୍ଥାପନ କରନ୍ତୁ',
'passwordreset-text' => 'ନିଜ ଖାତାର ସବିଶେଷ ବିବରଣୀ ଏକ ଇ-ମେଲରେ ପାଇବା ପାଇଁ ଏହି ଆବେଦନ ପତ୍ରଟି ପୂରଣ କରନ୍ତୁ ।',
'passwordreset-legend' => 'ପାସୱାର୍ଡ଼ ପୁନସ୍ଥାପନ କରନ୍ତୁ',
'passwordreset-disabled' => 'ପାସବାର୍ଡ଼କୁ ପୁରାପୁରି ମୂଳକୁ ଫେରାଇବା ଏହି ଉଇକିରେ ଅଚଳ କରାଯାଇଅଛି ।',
'passwordreset-pretext' => '{{PLURAL:$1||ତଳେ ଥିବା ତଥ୍ୟସମୂହରୁ କୌଣସି ଗୋଟିଏ ଦିଅନ୍ତୁ}}',
'passwordreset-username' => 'ବ୍ୟବହାରକାରୀଙ୍କ ନାମ:',
'passwordreset-domain' => 'ଡୋମେନ:',
'passwordreset-capture' => 'ଯାଉଥିବା ଇ-ମେଲଟି ଦେଖିବେ?',
'passwordreset-capture-help' => 'ଯଦି ଆପଣ ଘରଟିକୁ ଦେଖନ୍ତି ତେବେ (ଅସ୍ଥାୟୀ ପାସୱାର୍ଡ଼ ସହିତ) ଇ-ମେଲଟି ଆପଣଙ୍କୁ ଦେଖାଯିବ ଓ ବାକି ସଭ୍ୟମାନଙ୍କୁ ଚାଲିଯିବ ।',
'passwordreset-email' => 'ଇ-ମେଲ ଠିକଣା:',
'passwordreset-emailtitle' => '{{SITENAME}} ର ଖାତା ସବିଶେଷ',
'passwordreset-emailtext-ip' => 'କେହି ଜଣେ (ବୋଧେ ଆପଣ, $1 IP ଠିକଣାରୁ) 
{{SITENAME}} ($4) ସାଇଟରେ ଥିବା ଆପଣଙ୍କ ଖାତାର ସବିଶେଷ ଜାଣିବାକୁ ଅନୁରୋଧ କରିଛନ୍ତି । ଏହି ଇମେଲ ଠିକଣା ସହିତ ତଳଲିଖିତ ବ୍ୟବହାରକାରୀଙ୍କ {{PLURAL:$3|ଖାତା|ଖାତାସମୂହ}} ଯୋଡ଼ା:

$2

{{PLURAL:$3|ଏହି ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼ଟି|ଏହି ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼ସବୁ}} {{PLURAL:$5|ଦିନକରେ|$5 ଦିନରେ ଅଚଳ}} ହୋଇଯିବ ।
ଆପଣ ଏବେ ଲଗ ଇନ କରି ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ବାଛନ୍ତୁ । ଯହି ଆଉ କେହି ଜଣେ ଏହି ଅନୁରୋଧ କରିଥାନ୍ତି
କିମ୍ବା ଆପଣ ଏବେ ନିଜର ମୂଳ ପାସବାର୍ଡ଼ ମନେ ପକାଇ ପାରିଥାନ୍ତି ତେବେ ଏହି ପାସବାର୍ଡ଼ଟିକୁ ଆଉ ବଦଳାଇବା ଲୋଡ଼ା ନାହିଁ ।
ଆପଣ ନିଜ ପୁରୁଣା ପାସବାର୍ଡ଼ଟି ଆଗପରି ବ୍ୟବହାର କରିପାରନ୍ତି ।',
'passwordreset-emailtext-user' => '{{SITENAME}}ରେ ଥିବା ବ୍ୟୟବହାରକାରୀ $1 {{SITENAME}} ($4) ସାଇଟରେ ଥିବା ଆପଣଙ୍କ ଖାତାର ସବିଶେଷ ଜାଣିବାକୁ ଅନୁରୋଧ କରିଛନ୍ତି । ଏହି ଇମେଲ ଠିକଣା ସହିତ ତଳଲିଖିତ ବ୍ୟବହାରକାରୀଙ୍କ {{PLURAL:$3|ଖାତା|ଖାତାସମୂହ}} ଯୋଡ଼ା:

$2

{{PLURAL:$3|ଏହି ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼ଟି|ଏହି ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼ସବୁ}} {{PLURAL:$5|ଦିନକରେ|$5 ଦିନରେ ଅଚଳ}} ହୋଇଯିବ ।
ଆପଣ ଏବେ ଲଗ ଇନ କରି ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ବାଛନ୍ତୁ । ଯହି ଆଉ କେହି ଜଣେ ଏହି ଅନୁରୋଧ କରିଥାନ୍ତି
କିମ୍ବା ଆପଣ ଏବେ ନିଜର ମୂଳ ପାସବାର୍ଡ଼ ମନେ ପକାଇ ପାରିଥାନ୍ତି ତେବେ ଏହି ପାସବାର୍ଡ଼ଟିକୁ ଆଉ ବଦଳାଇବା ଲୋଡ଼ା ନାହିଁ ।
ଆପଣ ନିଜ ପୁରୁଣା ପାସବାର୍ଡ଼ଟି ଆଗପରି ବ୍ୟବହାର କରିପାରନ୍ତି ।',
'passwordreset-emailelement' => 'ଇଉଜର ନାମ: $1
ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼: $2',
'passwordreset-emailsent' => 'ଏକ ମନେପକାଇବା ଇ-ମେଲ ପଠାଇଦିଆଯାଇଅଛି ।',
'passwordreset-emailsent-capture' => 'ତଳେ ଦିଆଯାଇଥିବା ଭଳି ମନେପକାଇବା ଇ-ମେଲଟିଏ ପଠାଦିଆଗଲା ।',
'passwordreset-emailerror-capture' => 'ଗୋଟିଏ ସବିଶେଷ ଏମେଲଟିଏ ବାହାରିଛି, ଯାହାକି ତଳେ ଅଛି, କିନ୍ତୁ ଏହାକୁ ବ୍ୟବହାରକାରୀକୁ ପଠାଇବାରେ ଅସଫଳ ହେଲା :$1',

# Special:ChangeEmail
'changeemail' => 'ଇ-ମେଲ ଠିକଣା ବଦଳାଇବେ',
'changeemail-header' => 'ଖାତା ଇ-ମେଲ ଠିକଣା ବଦଳାଇବେ',
'changeemail-text' => 'ଆପଣା ଇ-ମେଲ ଠିକଣା ବଦଳାଇବା ନିମନ୍ତେ ଏହି ଆବେଦନ ପତ୍ରଟି ପୂରଣ କରନ୍ତୁ । ଆପଣଙ୍କୁ ଏହି ବଦଳ ଥୟ କରିବା ପାଇଁ ନିଜର ପାସବାର୍ଡ଼ ଦେବାକୁ ପଡ଼ିବ ।',
'changeemail-no-info' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ସିଧା ଖୋଲିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଲଗ ଇନ କରିବାକୁ ପଡ଼ିବ ।',
'changeemail-oldemail' => 'ଏବେକାର ଇ-ମେଲ ଠିକଣା:',
'changeemail-newemail' => 'ନୂଆ ଇ-ମେଲ ଠିକଣା:',
'changeemail-none' => '(କିଛି ନାହିଁ)',
'changeemail-submit' => 'ଇ-ମେଲ ପରିର୍ବତ୍ତନ କରନ୍ତୁ',
'changeemail-cancel' => 'ନାକଚ',

# Edit page toolbar
'bold_sample' => 'ମୋଟା ଲେଖା',
'bold_tip' => 'ମୋଟା ଲେଖା',
'italic_sample' => 'ତେରେଛା ଲେଖା',
'italic_tip' => 'ତେରେଛା ଲେଖା',
'link_sample' => 'ଲିଙ୍କ ଶିରୋନାମା',
'link_tip' => 'ଭିତର ଲିଙ୍କ',
'extlink_sample' => 'http://www.example.com ଲିଙ୍କ ଶିରୋନାମା',
'extlink_tip' => 'ବାହାର ଲିଙ୍କ (http:// ଆଗରେ ଲଗାଇବାକୁ ମନେରଖିଥିବେ)',
'headline_sample' => 'ଶିରୋନାମା ଲେଖା',
'headline_tip' => '୨କ ଆକାରର ମୂଳଧାଡ଼ି',
'nowiki_sample' => 'ଅସଜଡ଼ା ଲେଖା ଏଠାରେ ଭରିବେ',
'nowiki_tip' => 'ଉଇକି ସଜାଣି ବିନା',
'image_tip' => 'ଏମବେଡ଼ ହୋଇ ଥିବା ଫାଇଲ',
'media_tip' => 'ଫାଇଲର ଲିଙ୍କ',
'sig_tip' => 'ଲେଖାର ସମୟ ସହ ଆପଣଙ୍କ ସନ୍ତକ',
'hr_tip' => 'ସମାନ୍ତରାଳ ରେଖା (ବେଳେବେଳେ ବ୍ୟବହାର କରିବେ)',

# Edit pages
'summary' => 'ସାରକଥା:',
'subject' => 'ବିଷୟ/ମୂଳ ଲେଖା',
'minoredit' => 'ଏହା ଖୁବ ଛୋଟ ବଦଳଟିଏ',
'watchthis' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଦେଖିବେ',
'savearticle' => 'ସାଇତିବେ',
'preview' => 'ସାଇତା ଆଗରୁ ଦେଖଣା',
'showpreview' => 'ଦେଖଣା',
'showlivepreview' => 'ଜୀବନ୍ତ ଦେଖଣା',
'showdiff' => 'ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'anoneditwarning' => "'''ଜାଣିରଖନ୍ତୁ:''' ଆପଣ ଲଗଇନ କରିନାହାନ୍ତି ।
ଏହି ଫରଦର '''ଇତିହାସ''' ପୃଷ୍ଠାରେ ଆପଣଙ୍କ ଆଇପି ଠିକଣାଟି ସାଇତା ହୋଇଯିବ ।",
'anonpreviewwarning' => "''ଆପଣ ଲଗ ଇନ କରି ନାହାନ୍ତି । ଆପଣ ଯେଉଁ ବଦଳସବୁ କରିବେ ଆପଣଙ୍କର IP ଠିକଣା ଏହି ପୃଷ୍ଠାର ଇତିହାସରେ ସାଇତା ହୋଇଯିବ ।''",
'missingsummary' => "'''ଚେତାବନୀ:''' ଆପଣ ଏକ ସମ୍ପାଦନା ସାରକଥା ଦେଇନାହାନ୍ତି ।
ଯଦି ଆପଣ \"{{int:savearticle}}\"ରେ ଆଉଥରେ କ୍ଲିକ କରନ୍ତି, ତେବେ ଆପଣଙ୍କ ବଦଳ ସାରକଥା ବିନା ସାଇତା ହୋଇଯିବ ।",
'missingcommenttext' => 'ଦୟାକରି ତଳେ ଏକ ମତାମତ ଦିଅନ୍ତୁ ।',
'missingcommentheader' => "'''ଚେତାବନୀ:''' ଆପଣ ଏହି ମତଟି ନିମନ୍ତେ ଏକ ଶିରୋନାମା/ମୁଖ୍ୟ ନାମ ଦେଇନାହାନ୍ତି ।
ଯଦି ଆପଣ \"{{int:savearticle}}\"ରେ ଆଉଥରେ କ୍ଲିକ କରନ୍ତି, ତେବେ ଆପଣଙ୍କ ବଦଳ ସାରକଥା ବିନା ସାଇତା ହୋଇଯିବ ।",
'summary-preview' => 'ସାରକଥା ଦେଖଣା:',
'subject-preview' => 'ବିଷୟ/ଶିରୋନାମା ଦେଖଣା:',
'blockedtitle' => 'ସଭ୍ୟଙ୍କୁ ଅଟକାଯାଇଅଛି',
'blockedtext' => "''' ଆପଣଙ୍କ ଇଉସରନେମକୁ ପ୍ରତିରୋଧ କରାଯାଇଛି

$1 ଦ୍ଵାରା ପ୍ରତିରୋଧ କରାଯାଇଛି
ଦିଆଯାଇଥିବା କାରଣଟି ହେଉଛି $2

* ପ୍ରତିରୋଧ ଆରମ୍ଭ : $8
* ପ୍ରତିରୋଧ ଶେଷ : $6
* ଅଭିପ୍ରେତ ପ୍ରତିରୋଧକରି : $7

ଏହି ପ୍ରତିରୋଧ ବିଷୟରେ ଅଧିକ ଜାଣିବାକୁ ଚାହୁଥିଲେ ଆପଣ $1 କିମ୍ବା [[{{MediaWiki:Grouppage-sysop}}|administrator]]ଙ୍କୁ ଯୋଗାଯୋଗ  କରିପାରିବେ ।
ଯେ ପର୍ଯ୍ୟନ୍ତ ଆପଣଙ୍କ [[Special:Preferences|account preferences]]ରେ ଗୋଟିଏ ଉପଲବ୍ଧ ଇମେଲ ଠିକଣା ଦିଆ ଯାଇନାହି ଏବଂ ଆପଣ ଏହାକୁ ବ୍ୟବହାର କରିବାରେ କିଛି ପ୍ରତିରୋଧ ଲଗାଯାଇଅଛି ସେ ପର୍ଯ୍ୟନ୍ତ ଆପଣ 'ଏହି ସଦସ୍ୟଙ୍କୁ ଲେଖନ୍ତୁ ' ବିଭାଗଟିକୁ ବ୍ୟବହାର କରିପାରିବେନି ।
ବର୍ତମାନ ଆପଣଙ୍କ ଆଇପି ଠିକଣା ହେଇଛି $3, ଏବଂ ପ୍ରତିରୋଧ ଆଇଡି ହେଉଛି #$5 ।
ଯଦି ଅପଙ୍କର କିଛି କହିବାକୁ ଥାଏ ତାହେଲେ ଆପଣଙ୍କ ଲେଖାରେ ଉପରୋକ୍ତ ଲେଖାଗୁଡ଼ିକ ଯୋଡିଦେବେ",
'autoblockedtext' => 'ଆପଣଙ୍କ IP ଠିକଣାଟି ଆପେଆପେ ପ୍ରତିରୋଧ କରାଯାଇଛି କାରଣ ଏହା ଆଉ ଜଣେ ବ୍ୟବହାରକାରୀଙ୍କ ଦେଇ ବ୍ୟବହାର କରାଯାଇଛି, ଯେ $1ଙ୍କ ଦେଇ ଅଟକାଯାଇଛନ୍ତି ।
ଦିଆଯାଇଥିବା କାରଣ:

:\'\'$2\'\'

* ଅଟକ ଆରମ୍ଭ: $8
* ଅଟକ ମିଆଦ ପୁରା: $6
* କାହାକୁ ଅଟକାଯାଇଛି: $7

ଆପଣ $1ଙ୍କ ସହିତ ଯୋଗାଯୋଗ କରିପାରିବେ କିମ୍ବା [[{{MediaWiki:Grouppage-sysop}}|ପରିଛାମାନଙ୍କ]] ସହ ଅଟକ ବାବଦରେ ଆଲୋଚନା କରିପାରିବେ ।

ଜାଣିରଖନ୍ତୁ ଯେ [[Special:Preferences|ଆପଣଙ୍କ ପସନ୍ଦ]]ରେ ଏକ ସଠିକ ଇମେଲ ଠିକଣା ନଦେବା ଯାଏଁ ଓ ଅଟକରୁ ଛାଡ଼ ନହେବା ଯାଏଁ "ଏହି ସଭ୍ୟଙ୍କୁ ଇମେଲ କରନ୍ତୁ" ସୁବିଧାଟି ବ୍ୟବହାର କରିପାରିବେ ନାହିଁ ।

ଆପଣଙ୍କ ଏବେକାର IP ଠିକଣା ହେଲା $3, ଆଉ ଅଟକ ID ହେଲା #$5 ।
ଦୟାକରି ଆପଣ ପଚାରୁଥିବା ଯେକୌଣସି ପ୍ରଶ୍ନରେ ଉପରେ ଥିବା ସବିଶେଷ ଯୋଗ କରି ପଚାରିବେ ।',
'blockednoreason' => 'କିଛି କାରଣ ଦିଆଯାଇ ନାହିଁ',
'whitelistedittext' => 'ପୃଷ୍ଠା ସମ୍ପାଦନ ପାଇଁ ଆପଣଙ୍କୁ $1 କରିବାକୁ ପଡ଼ିବ ।',
'confirmedittext' => 'ସମ୍ପାଦନା କରିବା ଆଗରୁ ଆପଣଙ୍କୁ ନିଜର ଇ-ମେଲ ଠିକଣାଟିକୁ ଥୟ କରିବାକୁ ପଡ଼ିବ ।
ଆପଣା [[Special:Preferences|ସଭ୍ୟ ପସନ୍ଦ]] ଭିତରୁ ବାଛି ନିଜ ଇ-ମେଲ ଠିକଣାଟିକୁ ଥୟ କରନ୍ତୁ ।',
'nosuchsectiontitle' => 'ବିଭାଗ ମିଳିଲା ନାହିଁ',
'nosuchsectiontext' => 'ଆପଣ ସମ୍ପାଦନା କରିବାକୁ ଚେଷ୍ଟା କରୁଥିବା ବିଭାଗଟି ଏଯାଏଁ ତିଆରି କରାଯାଇ ନାହିଁ ।
ଆପଣ ଏହି ପୃଷ୍ଠାଟି ଦେଖିବା ବେଳେ ତାହାକୁ ଘୁଞ୍ଚାଇ ବା ଲିଭାଇ ଦିଆଯାଇ ଥାଇପାରେ ।',
'loginreqtitle' => 'ଲଗ ଇନ ଲୋଡ଼ା',
'loginreqlink' => 'ଲଗଇନ',
'loginreqpagetext' => 'ବାକି ପୃଷ୍ଠାମାନ ଦେଖିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ $1 କରିବାକୁ ପଡ଼ିବ ।',
'accmailtitle' => 'ପାସବାର୍ଡ଼ଟି ପଠାଇ ଦିଆଗଲା ।',
'accmailtext' => "[[User talk:$1|$1]]ଙ୍କ ନିମନ୍ତେ ଏକ ଯାହିତାହି ପାସବାର୍ଡ଼ $2ଙ୍କ ନିକଟକୁ ପଠାଗଲା ।

ଏହି ପାସବାର୍ଡ଼ଟି ''[[Special:ChangePassword|ପାସବାର୍ଡ଼  ବଦଳାଇବା]]'' ପୃଷ୍ଠାରେ ଲଗଇନ କରି କରାଯାଇପାରିବ ।",
'newarticle' => '(ନୁଆ)',
'newarticletext' => "ଆପଣ ଖୋଲିଥିବା ଲିଙ୍କଟିରେ ଏଯାଏଁ କିଛିବି ପୃଷ୍ଠା ନାହିଁ ।
ଏହି ପୃଷ୍ଠାଟିକୁ ତିଆରି କରିବା ପାଇଁ ତଳ ବାକ୍ସରେ ଟାଇପ କରନ୍ତୁ (ଅଧିକ ଜାଣିବା ପାଇଁ [[{{MediaWiki:Helppage}}|ସାହାଯ୍ୟ ପୃଷ୍ଠା]] ଦେଖନ୍ତୁ) ।
ଯଦି ଆପଣ ଏଠାକୁ ଭୁଲରେ ଆସିଯାଇଥାନ୍ତି ତେବେ ଆପଣଙ୍କ ବ୍ରାଉଜରର '''Back''' ପତିଟି ଦବାନ୍ତୁ ।",
'anontalkpagetext' => "----''ଏହା ଏକ IP ଖାତା ଖୋଲିନଥିବା ବା ଖାତା ବ୍ୟବହାର କରିନଥିବା ଜଣେ ବେନାମି ସଭ୍ୟଙ୍କର ଆଲୋଚନା ପୃଷ୍ଠା ।
ତେଣୁ ଆମ୍ଭେ ସଙ୍ଖ୍ୟା ଦେଇ ସୂଚିତ IP ଠିକଣା ଦେଇ ତାହାଙ୍କୁ ଜାଣିବା ।
ଏହି ପ୍ରକାରର IP ଠିକଣା ବହୁ ସଭ୍ୟଙ୍କ ଦେଇ ବଣ୍ଟା ବି ଯାଇପାରେ ।
ଯଦି ଆପଣ ଜଣେ ଅଜଣା ସଭ୍ୟ ଓ ଭାବୁଛନ୍ତି ଇଆଡୁ ସିଆଡୁ ମତାମତ ସବୁ ଆପଣଙ୍କ ଉପରେ ଦିଆଯାଇଛି ତେବେ ଦୟାକରି [[Special:UserLogin/signup|ନୂଆ ଖାତାଟିଏ ଖୋଲନ୍ତୁ]] କିମ୍ବା [[Special:UserLogin|ଆଗରୁ ଥିବା ଖାତାରେ ଲଗ ଇନ କରନ୍ତୁ]] ଯାହା ବେନାମି ସଭ୍ୟଙ୍କୁ ନେଇ ଉପୁଜିଥିବା ଦ୍ଵନ୍ଦର ସମାଧାନ କରିବ ।''",
'noarticletext' => 'ଏହି ପୃଷ୍ଠାଟିରେ କିଛି ବି ଲେଖା ନାହିଁ ।
ଆପଣ [[Special:Search/{{PAGENAME}}|ଏହି ଲେଖାଟିର ନାଆଁ]] ବାକି ପୃଷ୍ଠାମାନଙ୍କରେ ଖୋଜି ପାରନ୍ତି,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}ରେ ଯୋଡ଼ାଯାଇଥିବା ବାକି ପୃଷ୍ଠାସବୁକୁ ଖୋଜି ପାରନ୍ତି],
କିମ୍ବା [{{fullurl:{{FULLPAGENAME}}|action=edit}} ଏହି ପୃଷ୍ଠାଟିକୁ ବଦଳାଇ ପାରନ୍ତି]</span> ।',
'noarticletext-nopermission' => 'ଏବେ ଏହି ପୃଷ୍ଠାଟିରେ କିଛି ବି ଲେଖା ନାହିଁ ।
ଆପଣ [[Special:Search/{{PAGENAME}}|ଏହି ଲେଖାଟିର ନାଆଁ]] ବାକି ପୃଷ୍ଠାମାନଙ୍କରେ ଖୋଜି ପାରନ୍ତି, କିମ୍ବା
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}ରେ ଯୋଡ଼ାଯାଇଥିବା ବାକି ପୃଷ୍ଠାସବୁକୁ ଖୋଜି ପାରନ୍ତି]
</span>, କିନ୍ତୁ ଏହି ପୃଷ୍ଠାଟିକୁ ଆପଣ ତିଆରି କରିପାରିବେ ନାହିଁ ।',
'missing-revision' => '"{{PAGENAME}}" ନାମରେ ଥିବା ପୃଷ୍ଠାଟିର #$1 ପୁନରାବୃତ୍ତି ନାହିଁ ।

ପୁରୁଣା ହୋଇଯାଇଥିବା ଇତିହାସ ଲିଙ୍କ ଯାହା ଏକ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାକୁ ଦିଆଯାଇଥିବାରୁ ଏହା ସାଧାରଣତଃ ହୋଇଥାଏ ।
ଅଧିକ ବିବରଣୀ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log]ରେ ମିଳିପାରିବ ।',
'userpage-userdoesnotexist' => 'ଇଉଜର ଖାତା "$1" ଟି ତିଆରି କରାଯାଇନାହିଁ ।
ଆପଣ ଏହି ପୃଷ୍ଠାଟିକୁ ତିଆରି କରିବାକୁ ଚାହାନ୍ତି କି ନାହିଁ ଦୟାକରି ପରଖି ନିଅନ୍ତୁ ।',
'userpage-userdoesnotexist-view' => 'ଇଉଜର ନାମ "$1"ଟି ତିଆରି କରାଯାଇ ନାହିଁ ।',
'blocked-notice-logextract' => 'ଏହି ସଭ୍ୟଙ୍କୁ ଏବେ ପାଇଁ ଅଟକାଯାଇଅଛି ।
ଆପଣଙ୍କ ଜାଣିବା ନିମନ୍ତେ ନଗଦ ଇତିହାସ ତଳେ ଦିଆଗଲା:',
'clearyourcache' => "''' ଜାଣିରଖନ୍ତୁ:''' ସାଇତିବା ପରେ ବଦଳଗୁଡ଼ିକ ଦେଖିବା ନିମନ୍ତେ ଆପଣଙ୍କ ବ୍ରାଉଜରର ଅସ୍ଥାୟୀ ସ୍ମୃତି (cache) କୁ ସଫା କରିଦିଅନ୍ତୁ ।
* '''Firefox / Safari:''' ''Reload'' କଲାବେଳେ ''Shift'' ଧରି, କିମ୍ବା ''Ctrl-F5'' ବା ''Ctrl-R'' (Macରେ ''⌘-R'') ଦବାନ୍ତୁ
* '''Google Chrome:''' ''Ctrl-Shift-R'' (Macରେ ''⌘-Shift-R'') ଦବାନ୍ତୁ
* '''Internet Explorer:'''  ''Refresh'' କଲାବେଳେ ''Ctrl'' ଦବାନ୍ତୁ, କିମ୍ବା ''Ctrl-F5'' ଦବାନ୍ତୁ
* '''Opera:''' ''Tools → Preferences''ରେ ଅସ୍ଥାୟୀ ସ୍ମୃତି ସଫା କରିଦିଅନ୍ତୁ",
'usercssyoucanpreview' => "'''ଜାଣିବା କଥା:''' ଆପଣା ନୂଆ CSS ସାଇତିବା ଆଗରୁ \"{{int:showpreview}}\" ବ୍ୟବହାର କରି ପରଖି ନିଅନ୍ତୁ ।",
'userjsyoucanpreview' => "'''ଜାଣିବା କଥା:''' ଆପଣା ନୂଆ ଜାଭାସ୍କ୍ରିପ୍ଟ (JavaScript) ସାଇତିବା ଆଗରୁ \"{{int:showpreview}}\" ବ୍ୟବହାର କରି ପରଖି ନିଅନ୍ତୁ ।",
'usercsspreview' => "'''ଜାଣି ରଖନ୍ତୁ ଯେ ଆପଣ କେବଳ ନିଜର ସଭ୍ୟ CSS ଦେଖୁଅଛନ୍ତି ।'''
'''ଏହା ଏଯାଏଁ ସାଇତା ଯାଇନାହିଁ!'''",
'userjspreview' => "'''ଜାଣି ରଖନ୍ତୁ ଯେ ଆପଣ କେବଳ ନିଜର ସଭ୍ୟ ଜାଭାସ୍କ୍ରିପ୍ଟ (JavaScript) ଦେଖୁଅଛନ୍ତି ।'''
'''ଏହା ଏଯାଏଁ ସାଇତା ଯାଇନାହିଁ!'''",
'sitecsspreview' => "'''ଜାଣି ରଖନ୍ତୁ ଯେ ଆପଣ କେବଳ ଏହି CSS ଦେଖୁଅଛନ୍ତି ।'''
'''ଏହା ଏଯାଏଁ ସାଇତାଯାଇନାହିଁ!'''",
'sitejspreview' => "'''ଜାଣି ରଖନ୍ତୁ ଯେ ଆପଣ କେବଳ ଏହି ଜାଭାସ୍କ୍ରିପ୍ଟ (JavaScript) ଦେଖୁଅଛନ୍ତି ।'''
'''ଏହା ଏଯାଏଁ ସାଇତା ଯାଇନାହିଁ!'''",
'userinvalidcssjstitle' => "'''ଚେତାବନୀ:''' \"\$1\" ନାମରେ କୌଣସି ବି ଆବରଣ ନାହିଁ ।
ମନମୁତାବକ .css ଓ .js ପୃଷ୍ଠା ଏକ ଛୋଟ ଇଂରାଜୀ ଅକ୍ଷର ଥିବା ନାମ ନେଇଥାନ୍ତି, ଯଥା: {{ns:user}}:Foo/Vector.css ବଦଳରେ {{ns:user}}:Foo/vector.css ର ବ୍ୟବହାର ।",
'updated' => '(ସତେଜ କରିଦିଆଗଲା)',
'note' => "'''ଟୀକା:'''",
'previewnote' => "'''ଜାଣିରଖନ୍ତୁ ଯେ, ଏହା କେବଳ ଏକ ଦେଖଣା ।'''
ଆପଣ କରିଥିବା ବଦଳସବୁ ଏଯାଏଁ ସାଇତା ଯାଇନାହିଁ!",
'continue-editing' => 'ବଦଳାଇବା ଜାଗାକୁ ଯାଅନ୍ତୁ',
'previewconflict' => 'ଉପରେ ଦିଶୁଥିବା ଏହି ଦେଖଣାକୁ ସାଇତିଲେ ଏହା ଏକାପରି ଦେଖାଯିବ ।',
'session_fail_preview' => "'''କ୍ଷମା କରିବେ! ଅବଧି ତଥ୍ୟ ନଷ୍ଟ ହୋଇଯାଇଥିବାରୁ ଆମେ ଆପଣଙ୍କ ବଦଳସବୁକୁ ଗ୍ରହଣ କରିପାରିଲୁ ନାହିଁ ।'''
ଦୟାକରି ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।
ଯଦି ଏହା ଆହୁରି ବି କାମ ନକରେ, ତେବେ [[Special:UserLogout|ଲଗ ଆଉଟ]] କରି ଆଉଥରେ ଲଗ ଇନ କରିବେ ।",
'session_fail_preview_html' => "'''କ୍ଷମା କରିବେ! ଅବଧି ସରି ଯିବାରୁ ଡାଟା ନଷ୍ଟ ହୋଇଥିବା ହେତୁ ଆପଣଙ୍କ ସମ୍ପାଦନା ମିଳିପାରିଲା ନାହିଁ ।'''

''କାରଣ {{SITENAME}} ରେ ଖାଲି HTML ସଚଳ କରାଯାଇଛି, JavaScript ଆକ୍ରମଣରୁ ବଞ୍ଚିବା ପାଇଁ ସାଇତା ଆଗରୁ ଦେଖଣା ଲୁଛାଯାଇଛି''

'''ଯଦି ଏହା ଏକ ବୈଧ ସମ୍ପାଦନା ଚେଷ୍ଟା, ତେବେ ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।'''
ତଥାପି ଯଦି ଏହା କାମ ନକରେ, ତେବେ [[Special:UserLogout|ଲଗଆଉଟ]] କରି ଆଉଥରେ ଲଗ ଇନ କରନ୍ତୁ ।",
'token_suffix_mismatch' => "'''ଆପଣଙ୍କ ସମ୍ପାଦନା ନାକଚ କରିଦିଆଗଲା କାରଣ ଆପଣଙ୍କ ଅପରପକ୍ଷ ସମ୍ପାଦନାରେ ଭୁଲ ବିସ୍ମୟସୂଚକ ଚିହ୍ନ ଦେଇଦେଇଛି ।'''
ପୃଷ୍ଠା ଲେଖାରେ ଭୁଲ ଥିବାରୁ ଆପଣଙ୍କ ସମ୍ପାଦନାକୁ ନାକଚ କରିଦିଆଗଲା ।
ଆପଣ ଏକ ୱେବ-ରେ ଥିବା ଅଜଣା ପ୍ରକ୍ସି ସାଇଟ କରି  ବ୍ୟବହାର କରୁଥିଲେ ଏପରି ହୋଇଥାଏ ।",
'edit_form_incomplete' => "'''ସମ୍ପାଦନାର କେତେକ ଭାଗ ସର୍ଭର ଠେଇଁ ପହଞ୍ଚିଲା ନାହିଁ; ଭଲକରି ପରଖିନିଅନ୍ତୁ ଯେ ନିଜ ସମ୍ପାଦନା ସବୁ ଅକ୍ଷତ କି ନାହିଁ ଓ ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।'''",
'editing' => '$1 କୁ ବଦଳାଉଛି',
'creating' => '$1କୁ ତିଆରି କରୁଛି',
'editingsection' => '$1 (ଭାଗ)କୁ ବଦଳାଇବେ',
'editingcomment' => '$1 (ନୂଆ ଭାଗ)କୁ ବଦଳାଉଛୁ',
'editconflict' => 'ବଦଳାଇବା ଦ୍ଵନ୍ଦ: $1',
'explainconflict' => "ଆପଣ ବଦଳାଇବା ଆରମ୍ଭ କରିବା ଭିତରେ କେହିଜଣେ ଏହି ପୃଷ୍ଠାକୁ ବଦଳାଇଛନ୍ତି ।
ଉପର ଲେଖା ଜାଗାଟି ଏହା ଯେମିତି ଅଛି ସେମିତି ଥିବା ଲେଖାଟି ଦେଖାଉଛି ।
ତଳ ଜାଗାଟିରେ ଆପଣ କରିଥିବା ବଦଳ ଦେଖାଉଛି ।
ଏବେ ଥିବା ଲେଖାରେ ଆପଣଙ୍କୁ ନିଜ ବଦଳକୁ ମିଶାଇବାକୁ ହେବ ।
ଯଦି ଆପଣ \"{{int:savearticle}}\" ଦବାନ୍ତି ତେବେ '''କେବଳ''' ଉପର ଲେଖାଟି ସାଇତା ହୋଇଯିବ ।",
'yourtext' => 'ଆପଣଙ୍କ ଲେଖା',
'storedversion' => 'ସାଇତା ସଙ୍କଳନ',
'nonunicodebrowser' => "'''ଚେତାବନୀ: ଆପଣଙ୍କ ବ୍ରାଉଜରରେ ଇଉନିକୋଡ଼ ସଚଳ କରାଯାଇନାହିଁ ।'''
ଏକ ୱର୍କାଆରାଉଣ୍ଡ ଏକ ଏହିପରି ଜାଗା ଯାହା ଆପଣଙ୍କୁ ନିରାପଦ ଭାବରେ ପୃଷ୍ଠା ସମ୍ପାଦନ କରିବାରେ ସାହାଯ୍ୟ କରିଥାଏ: ଅଣ-ASCII ଅକ୍ଷରସମୂହ ସମ୍ପାଦନା ଘରେ ହେକ୍ସାଡେସିମାଲ କୋଡ଼ ରୂପେ ଦେଖାଯିବ ।",
'editingold' => "'''ଚେତାବନୀ: ଆପଣ ଏହି ପୃଷ୍ଠାର ଏକ ଅଚଳ ପୁରାତନ ସଙ୍କଳନକୁ ବଦଳାଉଛନ୍ତି ।'''
ଯଦି ଆପଣ ଏହାକୁ ସାଇତିବେ, ନୂଆ ସଙ୍କଳନ ଯାଏଁ କରାଯାଇଥିବା ସବୁ ବଦଳ ନଷ୍ଟ ହୋଇଯିବ ।",
'yourdiff' => 'ତଫାତ',
'copyrightwarning' => "ଦୟାକରି ଜାଣିରଖନ୍ତୁ ଯେ {{SITENAME}}କୁ ସବୁଯାକ ଅବଦାନ $2 ଅଧିନରେ ପ୍ରକାଶ କରାଯିବ । (ଅଧିକ ଜାଣିବା ପାଇଁ $1 ଦେଖନ୍ତୁ)
ଯଦି ଆପଣ ନିଜର ଲେଖା ନିର୍ଦୟ ଭାବେ ସମ୍ପାଦିତ ହେଉ ବୋଲି ଚାହୁଁନାହାନ୍ତି ବା ବଣ୍ଟନ କରାଯାଉ ବୋଲି ଚାହୁଁ ନାହାନ୍ତି ତେବେ ତାହା ଏଠାରେ ଦିଅନ୍ତୁ ନାହିଁ ।<br />
ଆପଣ ଆମପକ୍ଷେ ମଧ୍ୟ ପ୍ରତିଜ୍ଞା କରୁଛନ୍ତି ଯେ ଏହା ଆପଣ ନିଜେ ଲେଖିଛନ୍ତି, କିମ୍ବା ଏକ ପବ୍ଲିକ ଡୋମେନରୁ ବା ମାଗଣା ଓ ଖୋଲା ଲାଇସେନ୍ସ ଥିବା ସାଇଟରୁ ନକଲ କରି ଆଣିଛନ୍ତି ।
'''ଅନୁମତି ବିନା ସତ୍ଵାଧିକାର ଥିବା କାମ ଏଠାରେ ଦିଅନ୍ତୁ ନାହିଁ !'''",
'copyrightwarning2' => "ଦୟାକରି ଜାଣିରଖନ୍ତୁ ଯେ {{SITENAME}} ସବୁଯାକ ଅବଦାନ ସମ୍ପାଦିତ ହୋଇପାରିବ, ବଦଳାଯାଇପାରିବ କିମ୍ବା ବାକି ଅବଦାନକାରୀଙ୍କ ଦେଇ କଢ଼ାଯାଇପାରିବ ।
ଯଦି ଆପଣ ନିଜର ଲେଖା ନିର୍ଦୟ ଭାବେ ସମ୍ପାଦିତ ହେଉ ବୋଲି ଚାହୁଁନାହାନ୍ତି ବା ବଣ୍ଟନ କରାଯାଉ ବୋଲି ଚାହୁଁ ନାହାନ୍ତି ତେବେ ତାହା ଏଠାରେ ଦିଅନ୍ତୁ ନାହିଁ ।<br />
ଆପଣ ଆମପକ୍ଷେ ମଧ୍ୟ ପ୍ରତିଜ୍ଞା କରୁଛନ୍ତି ଯେ ଏହା ଆପଣ ନିଜେ ଲେଖିଛନ୍ତି, କିମ୍ବା ଏକ ପବ୍ଲିକ ଡୋମେନରୁ ବା ମାଗଣା ଓ ଖୋଲା ଲାଇସେନ୍ସ ଥିବା ସାଇଟରୁ ନକଲ କରି ଆଣିଛନ୍ତି । (ଦୟାକରି ସବିଶେଷ ପାଇଁ $1 ଦେଖନ୍ତୁ) ।
'''ଅନୁମତି ବିନା ସତ୍ଵାଧିକାର ଥିବା କାମ ଏଠାରେ ଦିଅନ୍ତୁ ନାହିଁ !'''",
'longpageerror' => "'''ଭୁଲ: ଆପଣ ଦେଇଥିବା ଲେଖାଟି {{PLURAL:$1|କିଲୋବାଇଟ|$1 କିଲୋବାଇଟ}} ଲମ୍ବା, ଯାହାକି ସବୁଠାରୁ ଅଧିକ {{PLURAL:$2|କିଲୋବାଇଟ|$2 କିଲୋବାଇଟ}} ଠାରୁ ବି ଅଧିକ ।'''
ଏହା ସାଇତାଯାଇପାରିବ ନାହିଁ ।",
'readonlywarning' => "'''ସୂଚନା: ଏହି ଡାଟାବେସଟି ରକ୍ଷଣାବେକ୍ଷଣା ପାଇଁ କିଳାଯାଇଛି । ତେଣୁ ଆପଣ ଆପଣା ସମ୍ପାଦନା ଏବେ ସାଇତି ପାରିବେ ନାହିଁ ।'''
ଆପଣ ଲେଖାସବୁ ଏକ ଟେକ୍ସଟ ଫାଇଲରେ ନକଲ କରି ପେଷ୍ଟ କରି ଆଗକୁ ବ୍ୟବହାର କରିବା ପାଇଁ ସାଇତି ପାରିବେ ।

ଏହାକୁ କିଳିଥିବା ପରିଛା ଏହି କଇଫତ ଦେଇଛନ୍ତି: $1",
'protectedpagewarning' => "'''ଚେତାବନୀ: ଏହି ପୃଷ୍ଠାଟିକୁ କିଳାଯାଇଅଛି ଯାହା ଫଳରେ କେବଳ ପରିଛାମାନେ ହିଁ ଏହାକୁ ବଦଳାଇ ପାରିବେ ।'''
ଆଧାର ନିମନ୍ତେ ତଳେ ନଗଦ ଇତିହାସ ଦିଆଯାଇଛି:",
'semiprotectedpagewarning' => "'''ଜାଣିରଖନ୍ତୁ:''' ଏହି ପୃଷ୍ଠାଟିକୁ କିଳାଯାଇଅଛି ଯାହା ଫଳରେ କେବଳ ନାମ ଲେଖାଇଥିବା ସଭ୍ୟ ମାନେ ଏହାକୁ ବଦଳାଇପାରିବେ ।
ଆପଣଙ୍କ ଜାଣିବା ନିମନ୍ତେ ନଗଦ ଲଗ ଇତିହାସ ତଳେ ଦିଆଗଲା:",
'cascadeprotectedwarning' => "'''ଚେତାବନୀ:''' ଏହି ପୃଷ୍ଠାଟି କିଳାଯାଇଛି ଯାହାଫଳରେ କେବଳ ପରିଛା ଅଧିକାର ଥିବା ସଭ୍ୟମାନେ ଏହାର ସମ୍ପାଦନା କରିପାରିବେ,  କାରଣ ଏହା ତଳେ ଥିବା କାସକେଡ଼ ସଂରକ୍ଷିତ {{PLURAL:$1|ପୃଷ୍ଠାଟି|ପୃଷ୍ଠାମାନଙ୍କ}} ଭିତରେ ଅଛି:",
'titleprotectedwarning' => "'''ଚେତାବନୀ: ଏହି ପୃଷ୍ଠାଟି କିଳାଯାଇଅଛି ଯାହାକୁ ତିଆରିବା ପାଇଁ [[Special:ListGroupRights|ବିଶେଷ କ୍ଷମତା]] ଥିବା ବ୍ୟବହାରକାରୀ ଲୋଡ଼ା ।'''
ଆପଣଙ୍କ ସୁବିଧା ପାଇଁ ତଳେ ନଗଦ ଲଗ ପ୍ରବେଶ ଦିଆଗଲା:",
'templatesused' => 'ଏହି ପୃଷ୍ଠାରେ ବ୍ୟବହାର କରାଯାଇଥିବା {{PLURAL:$1|ଛାଞ୍ଚ|ଛାଞ୍ଚ ମାନ}}:',
'templatesusedpreview' => 'ଏହି ଦେଖଣାରେ ବ୍ୟବହାର କରାଯାଇଥିବା {{PLURAL:$1|ଛାଞ୍ଚ|ଛାଞ୍ଚ ମାନ}}:',
'templatesusedsection' => 'ଏହି ବିଭାଗରେ ବ୍ୟବହାର କରାଯାଇଥିବା {{PLURAL:$1|ଛାଞ୍ଚ|ଛାଞ୍ଚ ମାନ}}:',
'template-protected' => '(କିଳାଯାଇଥିବା)',
'template-semiprotected' => '(ଅଧା କିଳାଯାଇଥିବା)',
'hiddencategories' => 'ଏହି ପୃଷ୍ଠାଟି {{PLURAL:$1|ଲୁଚାଯାଇଥିବା ଶ୍ରେଣୀ|$1ଟି ଲୁଚାଯାଇଥିବା ଶ୍ରେଣୀସମୂହ}} ଭିତରୁ ଗୋଟିଏ:',
'nocreatetitle' => 'ପୃଷ୍ଠା ଗଢ଼ିବାକୁ ସୀମିତ କରାଯାଇଅଛି',
'nocreatetext' => '{{SITENAME}} ନୂଆ ପୃଷ୍ଠା ତିଆରି କରିବାକୁ ବାରଣ କରିଅଛନ୍ତି ।
ଆପଣ ପଛକୁ ଫେରି ଆଗରୁ ଥିବା ପୃଷ୍ଠାଟିଏର ସମ୍ପାଦନା କରିପାରିବେ କିମ୍ବା [[Special:UserLogin|ଲଗ ଇନ କରିପାରିବେ ବା ନୂଆ ଖାତାଟିଏ ତିଆରି କରିପାରିବେ]] ।',
'nocreate-loggedin' => 'ଆପଣଙ୍କୁ ନୂଆ ପୃଷ୍ଠାଟିଏ ତିଆରିବା ନିମନ୍ତେ ଅନୁମତି ମିଳି ନାହିଁ ।',
'sectioneditnotsupported-title' => 'ବିଭାଗ ସମ୍ପାଦନା କରାଯାଇପାରିବ ନାହିଁ ।',
'sectioneditnotsupported-text' => 'ଏହି ପୃଷ୍ଠାରେ ବିଭାଗ ସମ୍ପାଦନା କାମ କରିବ ନାହିଁ ।',
'permissionserrors' => 'ଅନୁମତି ମିଳିବାରେ ଅସୁବିଧା',
'permissionserrorstext' => 'ତଳଲିଖିତ {{PLURAL:$1|କାରଣ|କାରଣସବୁ}} ପାଇଁ ଆପଣଙ୍କୁ ଏହା କରିବା ନିମନ୍ତେ ଅନୁମତି ନାହିଁ:',
'permissionserrorstext-withaction' => 'ତଳଲିଖିତ {{PLURAL:$1|କାରଣ|କାରଣସବୁ}} ପାଇଁ ଆପଣଙ୍କୁ $2 ଭିତରକୁ ଅନୁମତି ନାହିଁ:',
'recreate-moveddeleted-warn' => "'''ସୂଚନା: ଆଗରୁ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାଟିଏକୁ ଆପଣ ଆଉଥରେ ତିଆରୁଛନ୍ତି ।'''

ଆପଣ ଏହି ପୃଷ୍ଠାଟିକୁ ସମ୍ପାଦନା କରିବା ଉଚିତ କି ନୁହେଁ ବିଚାର କରିବା ଦରକାର ।
ଏହି ପୃଷ୍ଠାର ଲିଭାଇବା ଓ ଘୁଞ୍ଚାଇବା ଇତିହାସ ଏଠାରେ ସୁବିଧା ନିମନ୍ତେ ଦିଆଗଲା ।:",
'moveddeleted-notice' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଲିଭାଇ ଦିଆଯାଇଅଛି ।
ଏହାର ଲିଭାଇବା ଓ ଘୁଞ୍ଚାଇବା ଇତିହାସ ଆଧାର ନିମନ୍ତେ ତଳେ ଦିଆଗଲା ।',
'log-fulllog' => 'ପୁରା ଲଗ ଇତିହାସ ଦେଖନ୍ତୁ',
'edit-hook-aborted' => 'ସମ୍ପାଦନା ଏକ ହୁକ (hook) ଦେଇ ବାରଣ କରାଗଲା ।
ଏହା କିଛି ବି କାରଣ ଦେଇନାହିଁ ।',
'edit-gone-missing' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ସତେଜ କରାଯାଇପାରିବ ନାହିଁ ।
ଏହାକୁ ଲିଭାଇ ଦିଆଗଲା ପରି ମନେ ହେଉଛି ।',
'edit-conflict' => 'ବଦଳାଇବା ଦ୍ଵନ୍ଦ.',
'edit-no-change' => 'ଆପଣଙ୍କ ସମ୍ପାଦନାକୁ ଅଣଦେଖା କରାଗଲା, କାରଣ ଲେଖାରେ କିଛି ବି ବଦଳ କରାଯାଇନଥିଲା ।',
'edit-already-exists' => 'ନୂଆ ପୃଷ୍ଠାଟିଏ ତିଆରି କରିପାରିଲୁଁ ନାହିଁ ।
ଏହା ଅଗରୁ ଅଛି ।',
'defaultmessagetext' => 'ଡିଫଲ୍ଟ ମେସେଜ ଲେଖାଗୁଡିକ',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''ଚେତାବନୀ:''' ଏହି ପୃଷ୍ଠାରେ ଅନେକ ଗୁଡ଼ିଏ ମୂଲ୍ୟବାନ ପାର୍ସର ଫଙ୍କସନ କଲ ଅଛି ।

ଏଥିରେ ଅତି କମରେ $2 ଗୋଟି {{PLURAL:$2|କଲ|କଲ}} ଥିବା ଲୋଡ଼ା, ଏବେ ଏଥିରେ {{PLURAL:$1|$1 ଗୋଟି କଲ ଅଛି|$1 ଗୋଟି କଲ ଅଛି}} ।",
'expensive-parserfunction-category' => 'ଖୁବ ଅଧିକ ମୂଲ୍ୟବାନ ପାର୍ସର ଫଙ୍କସନ କଲ ଥିବା ପୃଷ୍ଠା',
'post-expand-template-inclusion-warning' => "'''ସୂଚନା:''' ଛାଞ୍ଚଟି ଖୁବ ବଡ଼ ଅଟେ ।
କେତେଗୋଟି ଛାଞ୍ଚକୁ ନିଆଯିବ ନାହିଁ ।",
'post-expand-template-inclusion-category' => 'ଛାଞ୍ଚର ଭିତର ଆକାର ଅଧିକଥିବା ପୃଷ୍ଠା',
'post-expand-template-argument-warning' => "'''ସୂଚନା:''' ଏହି ପୃଷ୍ଠାରେ ଅତି କମରେ ଗୋଟିଏ ଯୁକ୍ତି ରହିଅଛି ଯାହାର ଖୋଲା ଆକାର ବହୁତ ବଡ଼ ।
ଏହି ଯୁକ୍ତିସବୁକୁ ନଜର ଆଢୁଆଳ କରାଗଲା ।",
'post-expand-template-argument-category' => 'ଗଣାଯାଉନଥିବା ଛାଞ୍ଚର ଯୁକ୍ତିସବୁକୁ ଧରିଥିବା ପୃଷ୍ଠାସବୁ',
'parser-template-loop-warning' => 'ଛାଞ୍ଚ ଲୁପ (Template loop) ଦେଖିବାକୁ ମିଳିଲା: [[$1]]',
'parser-template-recursion-depth-warning' => 'ଛାଞ୍ଚର ବାରମ୍ବାର ପ୍ରତୀତ ହେବା କ୍ଷମତା ପାର ହୋଇଅଛି ($1)',
'language-converter-depth-warning' => 'ଭାଷା ରୂପାନ୍ତରଣ କ୍ଷମତା ସରିଯାଇଅଛି ($1)',
'node-count-exceeded-category' => 'ପୃଷ୍ଠାଗୁଡିକ ଯେଉଁଠି ନୋଡ-ଗଣନା ଅତ୍ୟଧିକ ହୋଇଯାଇଛି',
'node-count-exceeded-warning' => 'ପୃଷ୍ଠାଟି ନୋଡ-ଗଣନାରୁ ଅଧିକ ହୋଇଗଲା',
'expansion-depth-exceeded-category' => 'ଯେଉଁ ପୃଷ୍ଠାଗୁଡିକରେ ବିସ୍ତ୍ରୁତ ଗଭୀରତା ଅତ୍ୟଧିକ ହୋଇଯାଇଛି',
'expansion-depth-exceeded-warning' => 'ପୃଷ୍ଠାଟି ବିସ୍ତ୍ରୁତ ଗଭୀରତାରୁ ଅଧିକ ହୋଇଗଲା',
'parser-unstrip-loop-warning' => 'ଅଜଣା ଲୁପ ଜଣାପଡିଲା',
'parser-unstrip-recursion-limit' => 'ଅଜଣା ଚକ୍ରର ସୀମା ଅତ୍ୟଧିକ ହୋଇଗଲା ($1)',
'converter-manual-rule-error' => 'ଆପେ ଆପେ ଭାଷା ପରିବର୍ତ୍ତନ ନିଯମରେ ଭୁଲ ଅଛି',

# "Undo" feature
'undo-success' => 'ଏହି ସମ୍ପାଦନା ପଛକୁ ଫେରାଯାଇପାରିବ ନାହିଁ ।
ଦୟାକରି ତୁଳନା କରି ପରଖିନିଅନ୍ତୁ ଯେ ଆପଣ ଏହାହିଁ କରିବାକୁ ଚାହୁଁଥିଲେ, ଆଉ ସମ୍ପାଦନା ଶେଷ କରିବା ପାଇଁ ତଳେ ଥିବା ବଦଳ ସାଇତି ରଖନ୍ତୁ ।',
'undo-failure' => 'ଏହି ସମ୍ପାଦନା ପଛକୁ ଫେରାଯାଇ ପାରିବ ନାହିଁ କାରଣ ମଝିରେ ଘଟିଥିବା ଅନେକ ଛୋଟ ଛୋଟ ବଦଳ ଅସୁବିଧା ତିଆରି କରୁଅଛି ।',
'undo-norev' => 'ଏହି ସମ୍ପାଦନାଟି ପଛକୁ ଫେରାଯାଇପାରିବ ନାହିଁ କାରଣ ଏହା ଆଉ ନାହିଁ ବା ଲିଭାଇଦିଆଯାଇଅଛି ।',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|ଆଲୋଚନା]]) ଙ୍କ ଦେଇ କରାଯାଇଥିବା $1 ସଙ୍କଳନଟି ପଛକୁ ଫେରାଇନିଆଗଲା',

# Account creation failure
'cantcreateaccounttitle' => 'ଖାତାଟିଏ ତିଆରି କରାଯାଇପାରିବ ନାହିଁ',
'cantcreateaccount-text' => "[[User:$3|$3]]ଙ୍କ ଦେଇ ('''$1''') IP ଠିକଣାରୁ ଖାତା ଖୋଲିବାକୁ ବାରଣ କରାଯାଇଅଛି ।

$3ଙ୍କ ଦେଇ ଦିଆଯାଇଥିବା କାରଣ ହେଲା ''$2''",

# History pages
'viewpagelogs' => 'ଏହି ପୃଷ୍ଠା ପାଇଁ ଲଗଗୁଡ଼ିକୁ ଦେଖନ୍ତୁ ।',
'nohistory' => 'ଏହି ପୃଷ୍ଠା ନିମନ୍ତେ କିଛି ବି ସମ୍ପାଦନା ଇତିହାସ ନାହିଁ ।',
'currentrev' => 'ନଗଦ ସଙ୍କଳନ',
'currentrev-asof' => '$1 ହୋଇଥିବା ରିଭିଜନ',
'revisionasof' => '$1 ଅନୁସାରେ କରାଯାଇଥିବା ବଦଳ',
'revision-info' => '$2ଙ୍କ ଦେଇ $1 ସୁଦ୍ଧା ହୋଇଥିବା ସଙ୍କଳନ',
'previousrevision' => 'ପୁରୁଣା ସଙ୍କଳନ',
'nextrevision' => 'ନୂଆ ସଙ୍କଳନ',
'currentrevisionlink' => 'ନଗଦ ସଙ୍କଳନ',
'cur' => 'ପୃଷ୍ଠା ଇତିହାସ ସଙ୍ଗେ ଯୋଗ',
'next' => 'ପର',
'last' => 'ଆଗ',
'page_first' => 'ପ୍ରଥମ',
'page_last' => 'ଶେଷ',
'histlegend' => "ଅଲଗା ବଛା:ସଙ୍କଳନ ସବୁର ରେଡ଼ିଓ ବାକ୍ସକୁ ବାଛି ତୁଳନା କରନ୍ତୁ ଓ ଏଣ୍ଟର ଦବାନ୍ତୁ ବା ତଳେ ଥିବା ବଟନ ଦବାନ୍ତୁ ।<br />
ସାରକଥା: '''({{int:cur}})''' = ନଗଦ ସଙ୍କଳନରେ ଥିବା ତଫାତ, '''({{int:last}})''' = ଆଗ ସଙ୍କଳନ ଭିତରେ ତଫାତ, '''{{int:minoreditletter}}''' = ଟିକେ ବଦଳ ।",
'history-fieldset-title' => 'ଇତିହାସ ଖୋଜିବା',
'history-show-deleted' => 'କେବଳ ଲିଭାଯାଇଥିବା',
'histfirst' => 'ସବୁଠୁ ପୁରୁଣା',
'histlast' => 'ନଗଦ',
'historysize' => '({{PLURAL:$1|1 ବାଇଟ|$1 ବାଇଟ}})',
'historyempty' => '(ଖାଲି)',

# Revision feed
'history-feed-title' => 'ସଙ୍କଳନ ଇତିହାସ',
'history-feed-description' => 'ଉଇକିରେ ଏହି ପୃଷ୍ଠାଟିପାଇଁ ସଙ୍କଳନ ଇତିହାସ',
'history-feed-item-nocomment' => '$2 ଠାରେ $1',
'history-feed-empty' => 'ଅନୁରୋଧ କରାଯାଇଥିବା ପୃଷ୍ଠାଟି ନାହିଁ ।
ଏହା ଏହି ଉଇକିରୁ ଲିଭାଇ ଦିଆଯାଇଅଛି କିମ୍ବା ନୂଆ ନାମ ଦିଆଯାଇଅଛି ।
ପାଖାପାଖି ଏକା ନାମ ଥିବା ନୂଆ ପୃଷ୍ଠାମାନ [[Special:Search|ଏହି ଉଇକିରେ ଖୋଜନ୍ତୁ]] ।',

# Revision deletion
'rev-deleted-comment' => '(ସମ୍ପାଦନା ଇତିହାସ ଲିଭାଇଦିଆଗଲା)',
'rev-deleted-user' => '(ଇଉଜର ନାମ ବାହର କରିଦିଆଗଲା)',
'rev-deleted-event' => '(ଲଗ କାମ ବାହାର କରିଦିଆଗଲା)',
'rev-deleted-user-contribs' => '[ଇଉଜର ନାମ ବା IP ଠିକଣା ବାହାର କରିଦିଆଗଲା - ଅବଦାନସମୂହରୁ ଲୁଚାଯାଇଥିବା ସମ୍ପାଦନା]',
'rev-deleted-text-permission' => "ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣ '''ଲିଭାଇଦିଆଯାଇଛି'''।
ସବିଶେଷ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ଲିଭାଯିବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।",
'rev-deleted-text-unhide' => "ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣ '''ଲିଭାଇଦିଆଯାଇଛି''' ।
ସବିଶେଷ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ଲିଭାଇବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।
ତଥାପି ଆଗକୁ ବଢ଼ିବାକୁ ଚାହୁଁଥିଲେ ଆପଣ [$1 ଏହି ସଂସ୍କରଣଟି] ଦେଖିପାରିବେ ।",
'rev-suppressed-text-unhide' => "ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣ '''ଦବାଇଦିଆଯାଇଛି''' ।
ସବିଶେଷ [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ଦବାଯାଇଥିବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।
ତଥାପି ଆଗକୁ ବଢ଼ିବାକୁ ଚାହୁଁଥିଲେ ଆପଣ [$1 ଏହି ସଂସ୍କରଣଟି] ଦେଖିପାରିବେ ।",
'rev-deleted-text-view' => "ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣ '''ଲିଭାଇଦିଆଯାଇଛି'''।
ଆପଣ ଏହାକୁ ଦେଖିପାରିବେ; ସବିଶେଷ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ଲିଭାଯିବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।",
'rev-suppressed-text-view' => "ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣ '''ଦବାଇଦିଆଯାଇଛି'''।
ଆପଣ ଏହାକୁ ଦେଖିପାରିବେ; ସବିଶେଷ[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ଦବାଯାଇଥିବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।",
'rev-deleted-no-diff' => "ଆପଣ ତୁଳନା କରି ଦେଖିପାରିବେ ନାହିଁ କାରଣ ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣ ଭିତରୁ ଗୋଟିଏ '''ଲିଭାଇଦିଆଯାଇଛି'''।
ସବିଶେଷ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ଲିଭାଯିବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।",
'rev-suppressed-no-diff' => "ଆପଣ ତୂଳନାମାନ ଦେଖିପାରିବେ ନାହିଁ କାରଣ ଏଥି ଭିତରୁ ଗୋଟିଏ ସଙ୍କଳନ '''ଲିଭାଇ ଦିଆଯାଇଅଛି''' ।",
'rev-deleted-unhide-diff' => "ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣମାନଙ୍କ ଭିତରୁ ଗୋଟିଏ '''ଲିଭାଇଦିଆଯାଇଛି''' ।
ସବିଶେଷ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ଲିଭାଇବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।
ତଥାପି ଆଗକୁ ବଢ଼ିବାକୁ ଚାହୁଁଥିଲେ ଆପଣ [$1 ସଂସ୍କରଣମାନଙ୍କ ଭିତରେ ତୁଳନା] ଦେଖିପାରିବେ ।",
'rev-suppressed-unhide-diff' => "ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣମାନଙ୍କ ଭିତରୁ ଗୋଟିଏ '''ଦବାଇଦିଆଯାଇଛି''' ।
ସବିଶେଷ [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ଦବାଇଦିଆଯାଇଥିବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।
ତଥାପି ଆଗକୁ ବଢ଼ିବାକୁ ଚାହୁଁଥିଲେ ଆପଣ [$1 ସଂସ୍କରଣମାନଙ୍କ ଭିତରେ ତୁଳନା] ଦେଖିପାରିବେ ।",
'rev-deleted-diff-view' => "ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣମାନଙ୍କ ଭିତରୁ ଗୋଟିଏ '''ଲିଭାଇଦିଆଯାଇଛି'''।
ଆପଣ ଏହାକୁ ଦେଖିପାରିବେ; ସବିଶେଷ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ଲିଭାଯିବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।",
'rev-suppressed-diff-view' => "ଏହି ପୃଷ୍ଠାର ସଂସ୍କରଣମାନଙ୍କ ଭିତରୁ ଗୋଟିଏ '''ଦବାଇଦିଆଯାଇଛି'''।
ଆପଣ ଏହାକୁ ଦେଖିପାରିବେ; ସବିଶେଷ [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ଦବାଇଦିଆଯାଇଥିବା ଇତିହାସ]ରୁ ମିଳିପାରିବ ।",
'rev-delundel' => 'ଦେଖାଇବା/ଲୁଚାଇବା',
'rev-showdeleted' => 'ଦେଖାଇବେ',
'revisiondelete' => 'ସଙ୍କଳନମାନ ଲିଭାଇଦିଅନ୍ତୁ/ଲିଭାଯାଇଥିଲେ ପଛକୁ ଫେରାଇ ନିଅନ୍ତୁ',
'revdelete-nooldid-title' => 'ଲକ୍ଷ କରାଯାଇଥିବା ସଙ୍କଳନଟି ଭୁଲ ଅଟେ',
'revdelete-nooldid-text' => 'ଆପଣ ବୋଧେ ଏହି କାମ କରିବା ପାଇଁ ଏକ ନିର୍ଦିଷ୍ଟ ସଂସ୍କରଣ ଧାର୍ଯ୍ୟ କରିନାହାନ୍ତି, ଦିଆଯାଇଥିବା ସଂସ୍କରଣଟି ନାହିଁ, ବା ଆପଣ ଏବେକର ସଂସ୍କରଣଟିକୁ ଲୁଚାଇଦେବାକୁ ଚେଷ୍ଟା କରୁଛନ୍ତି ।',
'revdelete-nologtype-title' => 'କିଛି ଲଗ ପ୍ରକାର ଦିଆଯାଇ ନାହିଁ',
'revdelete-nologtype-text' => 'ଆପଣ ଏହି କାମଟି କରିବା ନିମନ୍ତେ ଗୋଟିଏ ନିର୍ଦିଷ୍ଟ ଲଗ ଟାଇପ ବାବଦରେ ଜଣାଇନାହାନ୍ତି ।',
'revdelete-nologid-title' => 'ଭୁଲ ଲଗଟିଏ ଦିଆହୋଇଅଛି',
'revdelete-nologid-text' => 'ଆପଣ ବୋଧେ ଏହି କାମ କରିବା ପାଇଁ ଏକ ନିର୍ଦିଷ୍ଟ ଇତିହାସ ଘଟଣା ଦେଇନାହାନ୍ତି ବା ଦିଆଯାଇଥିବା ନିବେଶ ମୂଳରୁ ନାହିଁ ।',
'revdelete-no-file' => 'ଆପଣ ସୂଚିତ କରିଥିବା ଫାଇଲଟି ନାହିଁ ।',
'revdelete-show-file-confirm' => '$2 ତାରିଖ $3 ବେଳେ "<nowiki>$1</nowiki>" ଫାଇଲର ଏକ ଲିଭାଯାଇଥିବା ସଙ୍କଳନକୁ ଦେଖିବାକୁ ଚାହାନ୍ତି ବୋଲି ଆପଣ ନିଶ୍ଚିତ କି ?',
'revdelete-show-file-submit' => 'ହଁ',
'revdelete-selected' => "'''[[:$1]]ର {{PLURAL:$2|ବଛା ସଙ୍କଳନ|ବଛା ସଙ୍କଳନ}}:'''",
'logdelete-selected' => "'''{{PLURAL:$1|ବଛା ଲଗ ଘଟଣା|ବଛା ଲଗ ଘଟଣାବଳୀ}}:'''",
'revdelete-text' => "'''ଲିଭାଯାଇଥିବା ସଂସ୍କରଣ ଓ ଘଟଣାସମୂହ ଏବେ ବି ପୃଷ୍ଠାର ଇତିହାସରେ ରହିବ, କିନ୍ତୁ ଜନସାଧାରଣଙ୍କୁ ସେସବୁର କିଛି ଭାଗ ଲୁଚାଇ ରଖାଯିବ ।'''
ଏହି {{SITENAME}}ର ବାକି ପରିଚାଳକଗଣ ଲୁଚିରହିଥିବା ବିଷୟବସ୍ତୁ ଦେଖିପାରିବେ ଓ ଅଧିକ ବାରଣ ଥିଲେ ହେଁ ସେହି ଏକା ଇଣ୍ଟରଫେସ ବ୍ୟବହାର କରି ତାହାକୁ ଆଉଥରେ ଲିଭାଯିବାରୁ ଅଟକାଇପାରିବେ ।",
'revdelete-confirm' => 'ଦୟାକରି ଥୟ କରନ୍ତୁ ଯେ ଆପଣ ଏହା କରିବାକୁ ଚାହୁଁଛନ୍ତି, ଆପଣ ଏହାର ପରିଣାମ ଜାଣନ୍ତି ଓ ଆପଣ [[{{MediaWiki:Policy-url}}|ନୀତି]] ଅନୁସାରେ ଏହା କରୁଛନ୍ତି ।',
'revdelete-suppress-text' => "ଦବାଇ ରଖିବା '''କେବଳ''' ଏହି ତଳଲିଖିତ କ୍ଷେତ୍ରରେ ବ୍ୟବହାର କରାଯିବ:
* ସମ୍ଭାବିତ ଅପମାନଜଣକ ତଥ୍ୟ
* ଭୁଲ ବ୍ୟକ୍ତିଗତ ତଥ୍ୟ
*: ''ଘର ଠିକଣା ଓ ଟେଲିଫୋନ ନମ୍ବର, ଭୋଟର ପରିଚୟ ନମ୍ବର, ଆଦି''",
'revdelete-legend' => 'ଦେଖଣା ବାରଣ ସବୁ ଥୟ କରନ୍ତୁ',
'revdelete-hide-text' => 'ସଙ୍କଳନ ଲେଖା ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-image' => 'ଫାଇଲ ଭିତର ପଦାର୍ଥସବୁ ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-name' => 'କାମ ଓ ଲକ୍ଷ ସବୁ ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-comment' => 'ବଦଳ ସାରକଥା ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-user' => 'ସମ୍ପାଦକଙ୍କର ଇଉଜର ନାମ /IP ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-restricted' => 'ପରିଚାଳକ ଓ ବାକିମାନଙ୍କ ଠାରୁ ତଥ୍ୟକୁ ଦବାଇଦିଅନ୍ତୁ',
'revdelete-radio-same' => '(ବଦଳାନ୍ତୁ ନାହିଁ)',
'revdelete-radio-set' => 'ହଁ',
'revdelete-radio-unset' => 'ନାହିଁ',
'revdelete-suppress' => 'ପରିଚାଳକ ଓ ବାକିମାନଙ୍କ ଠାରୁ ତଥ୍ୟକୁ ଦବାଇଦିଅନ୍ତୁ',
'revdelete-unsuppress' => 'ଆଉଥରେ ସ୍ଥାପିତ ସଙ୍କଳନସବୁରେ ଥିବା ବାରଣକୁ ବାହାର କରିଦିଅନ୍ତୁ',
'revdelete-log' => 'କାରଣ:',
'revdelete-submit' => 'ବଛା {{PLURAL:$1|ସଙ୍କଳନ|ସଙ୍କଳନସବୁ}} ପାଇଁ ଲାଗୁ କରନ୍ତୁ',
'revdelete-success' => "'''ସଙ୍କଳନ ଦେଖଣା ଭଲଭାବେ ସତେଜ କରାଗଲା ।'''",
'revdelete-failure' => "'''ସଙ୍କଳନ ଦେଖଣା ସତେଜ କରାଯାଇପାରିଲା ନାହିଁ:'''
$1",
'logdelete-success' => "'''ଲଗ ଦେଖଣା ଠିକ ଭାବରେ ଥୟ କରାଗଲା ।'''",
'logdelete-failure' => "'''ଲଗ ଦେଖଣା ଥୟ କରାଯାଇପାରିଲା ନାହିଁ:'''
$1",
'revdel-restore' => 'ଦେଖଣାକୁ ବଦଳାଇବେ',
'revdel-restore-deleted' => 'ଲିଭାଯାଇଥିବା ସଙ୍କଳନସବୁ',
'revdel-restore-visible' => 'ଦେଖାଯାଉଥିବା ସଙ୍କଳନସବୁ',
'pagehist' => 'ପୃଷ୍ଠାର ଇତିହାସ',
'deletedhist' => 'ଲିଭାଯାଇଥିବା ଇତିହାସ',
'revdelete-hide-current' => '$2,$1 ତାରିଖରେ ହୋଇଥିବା ଲେଖାଗୁଡିକ ଦେଖାଇବାରେ ଅସୁବିଧା ହେଉଛି : ଏହା ହେଉଛି ବର୍ତମାନର ପୁନଃଦେଖା ।
ଏହାକୁ ଲୁଚା ଯାଇପାରିବ ନାହି ।',
'revdelete-show-no-access' => "$2 ଦିନ, $1 ବେଳେ ଥିବା ବସ୍ତୁରେ ଭୁଲ: ଏହି ବସ୍ତୁଟି ''କିଳାଯାଇଛି'' ।
ଆପଣଙ୍କୁ ତାହା ଦେଖିବାକୁ ଅନୁମତି ନାହିଁ ।",
'revdelete-modify-no-access' => "$2 ଦିନ, $1 ବେଳେ ବସ୍ତୁଟିକୁ ବଦଳାଇବା ବେଳେ ଅସୁବିଧା ଘଟିଲା: ଏହି ବସ୍ତୁଟି ଦେଖିବାରୁ ''ବାରଣ କରାଯାଇଛି'' ।
ଆପଣଙ୍କୁ ତାହା ଦେଖିବାକୁ ଅନୁମତି ନାହିଁ ।",
'revdelete-modify-missing' => '$1 ଚିହ୍ନାଙ୍କ ଥିବା ବସ୍ତୁଟି ବଦଳାଇବାରେ ଅସୁବିଧା ହେଲା: ଏହା ଡାଟାବେସରୁ ହଜିଯାଇଛି!',
'revdelete-no-change' => "'''ଚେତାବନୀ:''' $2 ଦିନ, $1 ବେଳେ ବସ୍ତୁଟିର ଅନୁରୋଧ କରାଯାଇଥିବା ଦେଖଣା ଆଗରୁ ଅଛି ।",
'revdelete-concurrent-change' => '$2 ଦିନ, $1 ବେଳେ ବସ୍ତୁଟି ବଦଳାଇବା ବେଳେ ଅସୁବିଧାଟିଏ ଘଟିଲା: ଆପଣ ଏହାକୁ ବଦଳାଇବାକୁ ଚେଷ୍ଟା କରୁଥିବା ବେଳେ ଏହାର ସ୍ଥିତି ଆଉ କାହା ଦେଇ ବଦଳାଯାଇଛି ।
ଦୟାକରି ଇତିହାସ ପରଖିନିଅନ୍ତୁ ।',
'revdelete-only-restricted' => '$2 ଦିନ, $1 ବେଳେ ବସ୍ତୁଟି ଲୁଚାଇବା ବେଳେ ଅସୁବିଧାଟିଏ ଘଟିଲା: ଆପଣ ଦେଖଣା ବିକଳ୍ପମାନ ବ୍ୟବହାର ନକରି ବସ୍ତୁସବୁ ପରିଛାମାନଙ୍କ ଦେଇ ଦେଖାଯିବାରୁ ଅଟକାଇପାରିବେ ନାହିଁ ।',
'revdelete-reason-dropdown' => '*ସାଧାରଣ ଲିଭାଇବା କାରଣମାନ
** ସତ୍ଵାଧିକାର ଉଲ୍ଲଙ୍ଘନ
** ଭୁଲ ଆତ୍ମ ବିବରଣୀ କିମ୍ବା ଖରାପ ମନ୍ତବ୍ୟ
** ଭୁଲ ବ୍ୟବହାରକାରୀ ନାମ
** ପ୍ରାୟ ଭୁଲ ତଥ୍ୟ',
'revdelete-otherreason' => 'ବାକି/ଅଧିକ କାରଣ:',
'revdelete-reasonotherlist' => 'ଅଲଗା କାରଣ',
'revdelete-edit-reasonlist' => 'ଲିଭାଇବା କାରଣମାନ ବଦଳାଇବେ',
'revdelete-offender' => 'ସଙ୍କଳନ ଲେଖକ:',

# Suppression log
'suppressionlog' => 'ଦବାଇବା ଲଗ',
'suppressionlogtext' => 'ଲିଭାଯାଇଥିବା ଓ ଅଟକାଯାଇଥିବା, ଏହା ସହ ପରିଛାମାନଙ୍କଠାରୁ ଲୁଚାଯାଇଥିବା ଲେଖାଗୁଡ଼ିକର ଏକ ତାଲିକା ତଳେ ଦିଆଯାଇଛି ।
ଏବେ କରାଯାଇଥିବା ବାସନ୍ଦ ଓ ବାରଣ ପାଇଁ [[Special:BlockList|block list]] ଦେଖନ୍ତୁ ।',

# History merging
'mergehistory' => 'ପୃଷ୍ଠାର ଇତିହାସ ସବୁ ଯୋଡ଼ିଦେବେ',
'mergehistory-header' => 'ଏହି ପୃଷ୍ଠାଟି ଏକ ମୂଳାଧାର ଥିବା ପୃଷ୍ଠାର ଇତିହାସକୁ ଏକ ନୂଆ ପୃଷ୍ଠାରେ ମିଶାଇଦେବାରେ ଏହି ପୃଷ୍ଠା ଆପଣଙ୍କୁ ଅନୁମତି ଦେଇଥାଏ ।
ମନେ ରଖନ୍ତୁ ଯେ ଏହି ବଦଳ ପୃଷ୍ଠା ଇତିହାସ ନଥିରେ ସାଇତାହୋଇ ରହିବ ।',
'mergehistory-box' => 'ଦୁଇଟି ପୃଷ୍ଠାର ସଙ୍କଳନ ଯୋଡ଼ିଦେବେ:',
'mergehistory-from' => 'ମୂଳାଧାର ପୃଷ୍ଠା:',
'mergehistory-into' => 'ଲକ୍ଷ ପୃଷ୍ଠା:',
'mergehistory-list' => 'ଯୋଡ଼ାଯାଇପାରିବା ଭଳି ସମ୍ପାଦନା ଇତିହାସ',
'mergehistory-merge' => 'ଏହି [[:$1]] ସଂସ୍କରଣଟି [[:$2]] ସହ ଯୋଡ଼ାଯାଇପାରିବ ।
ଜାଣିରଖନ୍ତୁ ଯେ ଦିଗସୂଚକ ଲିଙ୍କସବୁ ଏହି ସ୍ତମ୍ଭକୁ ମୂଳଅବସ୍ଥାକୁ ଫେରାଇଦେବ ।',
'mergehistory-go' => 'ଯୋଡ଼ାଯାଇପାରିବା ଭଳି ସମ୍ପାଦନା',
'mergehistory-submit' => 'ସଙ୍କଳନସବୁକୁ ମିଶାଇଦେବେ',
'mergehistory-empty' => 'କୌଣସିଟି ବି ସଙ୍କଳାନ ମିଶାଯାଇପାରିବ ନାହିଁ ।',
'mergehistory-success' => '[[:$1]]ର $3 {{PLURAL:$3|ଟି ସଙ୍କଳନ|ଟି ସଙ୍କଳନ}} [[:$2]] ସାଙ୍ଗରେ ଠିକଭାବେ ମିଶାଇ ଦିଆଗଲା ।',
'mergehistory-fail' => 'ଇତିହାସ ମିଶାଇବାରେ ବିଫଳ ହେଲୁ, ଦୟାକରି ପୃଷ୍ଠା ଓ  ସମୟ ନିର୍ଣ୍ଣାୟକ ଦେଖନ୍ତୁ ।',
'mergehistory-no-source' => 'ମୂଳ ପୃଷ୍ଠା $1ଟି ନାହିଁ ।',
'mergehistory-no-destination' => 'ଅନ୍ତ ପୃଷ୍ଠା $1 ଟି ନାହିଁ ।',
'mergehistory-invalid-source' => 'ମୂଳ ପୃଷ୍ଠାଟି ଏକ ଠିକ ନାମ ହୋଇଥିବା ଉଚିତ ।',
'mergehistory-invalid-destination' => 'ଅନ୍ତ ପୃଷ୍ଠାର ନାମ ସଠିକ ହୋଇଥିବା ଉଚିତ ।',
'mergehistory-autocomment' => '[[:$2]] ସହିତ [[:$1]]କୁ ଯୋଡ଼ି ଦିଆଗଲା ।',
'mergehistory-comment' => '[[:$2]] ଭିତରେ [[:$1]]କୁ ଯୋଡ଼ି ଦିଆଗଲା: $3',
'mergehistory-same-destination' => 'ମୂଳାଧାର ଓ ଅନ୍ତ ପୃଷ୍ଠା ସମାନ ହୋଇପାରିବ ନାହିଁ',
'mergehistory-reason' => 'କାରଣ:',

# Merge log
'mergelog' => 'ମିଶ୍ରଣ ଲଗ୍',
'pagemerge-logentry' => '[[$2]] ସହିତ [[$1]]କୁ ଯୋଡ଼ି ଦିଆଗଲା ($3 ଯାଏଁ ସଙ୍କଳନ)',
'revertmerge' => 'ମିଶାଇବା ନାହିଁ',
'mergelogpagetext' => 'ତଳେ ସବୁଠାରୁ ନଗଦ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠାର ଇତିହାସ ଆଉ ଗୋଟିଏ ସହ ଦିଆଯାଇଅଛି ।',

# Diffs
'history-title' => '"$1" ର ପୁନରାବୃତି ଇତିହାସ',
'difference-title' => '"$1"ର ପୁନରାବୃତିଗୁଡିକରେ ପାର୍ଥକ୍ୟ',
'difference-title-multipage' => 'ପୃଷ୍ଠା "$1" ଏବଂ "$2" ମଧ୍ୟରେ ଥିବା ପାର୍ଥକ୍ୟ',
'difference-multipage' => '(ପୃଷ୍ଠା ଭିତରେ ଥିବା ତଫାତ)‌',
'lineno' => '$1 କ ଧାଡ଼ି:',
'compareselectedversions' => 'ବଛାହୋଇଥିବା ସଙ୍କଳନ ଗୁଡ଼ିକୁ ତଉଲିବେ',
'showhideselectedversions' => 'ବଛା ହୋଇଥିବା ସଙ୍କଳନ ଗୁଡ଼ିକୁ ଦେଖାଇବେ/ଲୁଚାଇବେ',
'editundo' => 'ପଛକୁ ଫେରିବା',
'diff-multi' => '({{PLURAL:$2|ଜଣେ ବ୍ୟବହାରକାରୀ|$2 ଜଣ ବ୍ୟବହାରକାରୀ}}ଙ୍କ ଦେଇ ହୋଇଥିବା {{PLURAL:$1|ଗୋଟିଏ ମଝି ସଙ୍କଳନ|$1ଟି ମଝି ସଙ୍କଳନ}} ଦେଖାଯାଉନାହିଁ)',
'diff-multi-manyusers' => '($2 {{PLURAL:$2|ଜଣ|ଜଣ}} ସଭ୍ୟଙ୍କ ଦେଇ କରାଯାଇଥିବା {{PLURAL:$1|ଗୋଟିଏ ମଝି ସଂସ୍କରଣ|$1 ଗୋଟି ମଝି ସଂସ୍କରଣମାନ}} ଦେଖାଯାଉ ନାହିଁ)',
'difference-missing-revision' => '($1) {{PLURAL:$2|was|were}}ର ଭିନ୍ନତା {{PLURAL:$2|One revision|$2 revisions}} ମିଳିଲା ନାହିଁ ।

ପୁରୁଣା ହୋଇଯାଇଥିବା ଇତିହାସ ଲିଙ୍କ ଯାହା ଏକ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାକୁ ଦିଆଯାଇଥିବାରୁ ଏହା ସାଧାରଣତଃ ହୋଇଥାଏ ।
ଅଧିକ ବିବରଣୀ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log]ରେ ମିଳିପାରିବ ।',

# Search results
'searchresults' => 'ଖୋଜା ଫଳାଫଳ',
'searchresults-title' => '"$1" ପାଇଁ ଖୋଜିବାରୁ ମିଳିଲା',
'searchresulttext' => '{{SITENAME}} ରେ ଖୋଜିବା ବାବଦରେ ଅଧିକ ଜାଣିବା ପାଇଁ,  [[{{MediaWiki:Helppage}}|{{int:help}}]] ଦେଖନ୍ତୁ',
'searchsubtitle' => 'ଆପଣ  \'\'\'[[:$1]]\'\'\' ପାଇଁ ([[Special:Prefixindex/$1|"$1" ନାଆଁରେ ଆରମ୍ଭ ହୋଇଥିବା ସବୁ ପୃଷ୍ଠା]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" କୁ ଯୋଡ଼ାଥିବା ସବୁତକ ପୃଷ୍ଠା]])',
'searchsubtitleinvalid' => "ଆପଣ '''$1''' ଖୋଜିଥିଲେ",
'toomanymatches' => 'ବହୁଗୁଡ଼ିଏ ମେଳ ଲେଉଟିଆସିଛି, ଦୟାକରି ନୂଆ ପ୍ରଶ୍ନଟିଏ ସହିତ ଖୋଜନ୍ତୁ ।',
'titlematches' => 'ପୃଷ୍ଠାଟିର ନାମ ମିଶୁଅଛି',
'notitlematches' => 'ପୃଷ୍ଠାଟିର ନାମ ମିଶୁନାହିଁ',
'textmatches' => 'ପୃଷ୍ଠାଟିର ଲେଖା ମିଶୁଅଛି',
'notextmatches' => 'ପୃଷ୍ଠାଟିର ନାମ ମିଶୁନାହିଁ',
'prevn' => '{{PLURAL:$1|$1}}ର ଆଗରୁ',
'nextn' => '{{PLURAL:$1|$1}} ପର',
'prevn-title' => 'ଆଗରୁ ମିଳିଥିବା $1ଟି  {{PLURAL:$1|result|ଫଳ}}',
'nextn-title' => 'ଆଗର $1ଟି  {{PLURAL:$1|result|ଫଳସବୁ}}',
'shown-title' => '$1 ପ୍ରତି ପୃଷ୍ଠାର {{PLURAL:$1|ଫଳାଫଳ|ଫଳାଫଳ}} ଦେଖାଇବେ ।',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) ଟି ଦେଖିବେ',
'searchmenu-legend' => 'ଖୋଜିବା ବିକଳ୍ପ',
'searchmenu-exists' => "'''ଏହି ଉଇକିରେ \"[[:\$1]]\" ନାଆଁରେ ପୃଷ୍ଠାଟିଏ ଅଛି ।'''",
'searchmenu-new' => "'''ଏହି ପ୍ରସଙ୍ଗଟି ଆଗରୁ ନାହିଁ, ତେଣୁ ''[[:$1]]'' ନାମରେ ପ୍ରସଙ୍ଗଟିଏ ଏଠାରେ ଗଢ଼ନ୍ତୁ!'''",
'searchhelp-url' => 'Help:ସୂଚୀ',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|ଏହି ନାମ ଆଗରୁ ଥିବା ପୃଷ୍ଠାସବୁ ଖୋଜିବେ]]',
'searchprofile-articles' => 'ସୂଚୀ ପୃଷ୍ଠା',
'searchprofile-project' => 'ସାହାଯ୍ୟ ଓ ପ୍ରକଳ୍ପ ପୃଷ୍ଠା',
'searchprofile-images' => 'ମଲ୍ଟିମିଡ଼ିଆ',
'searchprofile-everything' => 'ସବୁକିଛି',
'searchprofile-advanced' => 'ଉନ୍ନତ',
'searchprofile-articles-tooltip' => '$1ରେ ଖୋଜିବେ',
'searchprofile-project-tooltip' => '$1ରେ ଖୋଜିବେ',
'searchprofile-images-tooltip' => 'ଫାଇଲ ସବୁ ପାଇଁ ଖୋଜିବେ',
'searchprofile-everything-tooltip' => 'ପ୍ରସଙ୍ଗ ସବୁକୁ ଖୋଜିବେ (ଆଲୋଚନା ସହ)',
'searchprofile-advanced-tooltip' => 'ନିଜେ ତିଆରିକରିହେବା ଭଳି ନେମସ୍ପେସରେ ଖୋଜିବେ',
'search-result-size' => '$1 ({{PLURAL:$2|1 ଶବ୍ଦ|$2 ଶବ୍ଦ}})',
'search-result-category-size' => '{{PLURAL:$1|ଜଣେ ସଭ୍ୟ|$1 ଜଣ ସଭ୍ୟ}} ({{PLURAL:$2|ଗୋଟିଏ ଶ୍ରେଣୀy|$2ଟି ଶ୍ରେଣୀ ସମୂହ}}, {{PLURAL:$3|ଗୋଟିଏ ଫାଇଲ|$3ଟି ଫାଇଲ}})',
'search-result-score' => 'ପ୍ରାସଙ୍ଗିକତା: $1%',
'search-redirect' => '($1 କୁ ଆଗକୁ ବଢେଇନିଅ )',
'search-section' => '(ଭାଗ $1)',
'search-suggest' => 'ଆପଣ $1 ଭାବି ଖୋଜିଥିଲେ କି?',
'search-interwiki-caption' => 'ସାଙ୍ଗରେ ଚାଲିଥିବା ବାକି ପ୍ରକଳ୍ପସବୁ',
'search-interwiki-default' => '$1 ଫଳାଫଳ:',
'search-interwiki-more' => '(ଅଧିକ)',
'search-relatedarticle' => 'ଯୋଡ଼ା',
'mwsuggest-disable' => 'AJAX ମତାମତକୁ ଅଚଳ କରାଇବେ',
'searcheverything-enable' => 'ସବୁଗୁଡ଼ିକ ନେମସ୍ପେସରେ ଖୋଜିବେ',
'searchrelated' => 'ଯୋଡ଼ା',
'searchall' => 'ସବୁ',
'showingresults' => "ତଳେ {{PLURAL:$1|'''ଗୋଟିଏ'''  ଫଳାଫଳ|'''$1'''ଟି ଫଳାଫଳ}} ଦେଖାଉଛୁ ଯାହା #'''$2'''ରେ ଆରମ୍ଭ ହୋଇଅଛି ।",
'showingresultsnum' => "ତଳେ {{PLURAL:$3|ଗୋଟିଏ ଫଳାଫଳ|'''$3'''ଟି ଫଳାଫଳ}} ଦେଖାଉଛୁ ଯାହା #'''$2'''ରେ ଆରମ୍ଭ ହୋଇଅଛି ।",
'showingresultsheader' => "'''$4''' ପାଇଁ {{PLURAL:$5|'''$3'''ର '''$1''' ଫଳ |'''$3'''ର '''$1 - $2''' ଫଳ }}",
'nonefound' => "'''ଜାଣି ରଖନ୍ତୁ''': ଆପଣ ଖାଲି କିଛି ନେମସ୍ପେସକୁ ଆପେ ଆପେ ଖୋଜିପାରିବେ ।
ସବୁ ପ୍ରକାରର ଲେଖା (ଆଲୋଚନା ପୃଷ୍ଠା, ଛାଞ୍ଚ ଆଦି) ଖୋଜିବା ପାଇଁ ନିଜ ପ୍ରଶ୍ନ ଆଗରେ ''all:'' ଯୋଡ଼ି ଖୋଜନ୍ତୁ, ନହେଲେ ଦରକାରି ନେମସ୍ପେସକୁ ଲେଖାର ନାମ ଆଗରେ ଯୋଡ଼ି ବ୍ୟବହାର କରନ୍ତୁ ।",
'search-nonefound' => 'ଆପଣ ଖୋଜିଥିବା ପ୍ରଶ୍ନ ପାଇଁ କିଛି ଫଳ ମିଳିଲା ନାହିଁ ।',
'powersearch' => 'ଗହିର ଖୋଜା',
'powersearch-legend' => 'ଗହିର ଖୋଜା',
'powersearch-ns' => 'ନେମସ୍ପେସରେ ଖୋଜିବେ',
'powersearch-redir' => 'ପୁନପ୍ରେରଣ ପୃଷ୍ଠାସମୂହର ତାଲିକା ତିଆରିବେ',
'powersearch-field' => 'ଖୋଜିବେ',
'powersearch-togglelabel' => 'ଯାଞ୍ଚ କରିବା:',
'powersearch-toggleall' => 'ସବୁ',
'powersearch-togglenone' => 'କିଛି ନାହିଁ',
'search-external' => 'ବାହାରେ ଖୋଜା',
'searchdisabled' => '{{SITENAME}} ଖୋଜା ଅଚଳ କରାଗଲା ।
ଆପଣ ଏହି ଭିତରେ ଗୁଗଲ ଦେଖିପାରନ୍ତି ।
ଜାଣିରଖନ୍ତୁ ଯେ {{SITENAME}}ର ବିଷୟ ସୂଚି ପୁରାତନ ହୋଇଥାଇପାରେ ।',

# Quickbar
'qbsettings' => 'ସହଳ ପଟି (Quickbar)',
'qbsettings-none' => 'କିଛି ନାହିଁ',
'qbsettings-fixedleft' => 'ବାମକୁ ଥୟ କରାଗଲା',
'qbsettings-fixedright' => 'ଡାହାଣକୁ ଥୟ କରାଗଲା',
'qbsettings-floatingleft' => 'ବାମରେ ଭାସନ୍ତା',
'qbsettings-floatingright' => 'ଡାହାଣରେ ଭାସନ୍ତା',
'qbsettings-directionality' => 'ଆପଣଙ୍କ ଭାଷାର ବାମ-ଡାହାଣ ଲିଖନ ଶୈଳୀ ଅନୁସାରେ ସଜାଡ଼ି ଦିଆଗଲା',

# Preferences page
'preferences' => 'ପସନ୍ଦ',
'mypreferences' => 'ପସନ୍ଦ',
'prefs-edits' => 'ସମ୍ପାଦନା ସଂଖ୍ୟା:',
'prefsnologin' => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
'prefsnologintext' => 'ବ୍ୟବହାରକାରୀଙ୍କ ପସନ୍ଦସବୁ ବଦଳାଇବା ପାଇଁ ଆପଣଙ୍କୁ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ଲଗ ଇନ]</span> କରିବାକୁ ପଡ଼ିବ ।',
'changepassword' => 'ପାସୱର୍ଡ଼ ବଦଳାନ୍ତୁ',
'prefs-skin' => 'ବହିରାବରଣ',
'skin-preview' => 'ସାଇତା ଆଗରୁ ଦେଖଣା',
'datedefault' => 'କୌଣସି ପସନ୍ଦ ନାହିଁ',
'prefs-beta' => 'ଆଗ ବିଶେଷତାମାନ',
'prefs-datetime' => 'ତାରିଖ ଓ ସମୟ',
'prefs-labs' => 'ପରଖଶାଳା ସୁବିଧାସବୁ',
'prefs-user-pages' => 'ବ୍ୟବହାରକାରୀଙ୍କର ପୃଷ୍ଠାଗୁଡିକ',
'prefs-personal' => 'ସଭ୍ୟ ପ୍ରଫାଇଲ',
'prefs-rc' => 'ନଗଦ ବଦଳ',
'prefs-watchlist' => 'ଦେଖଣା ତାଲିକା',
'prefs-watchlist-days' => 'ଦେଖଣା ତାଲିକାରେ ଦେଖାଯିବା ଦିନ:',
'prefs-watchlist-days-max' => 'ସବୁଠାରୁ ଅଧିକ ହେଲେ $1 {{PLURAL:$1|ଦିନ|ଦିନ}}',
'prefs-watchlist-edits' => 'ବଢ଼ନ୍ତା ଦେଖଣା ତାଲିକାରେ ଦେଖିହେବା ଭଳି ସବୁଠାରୁ ଅଧିକ ବଦଲ:',
'prefs-watchlist-edits-max' => 'ସବୁଠାରୁ ଅଧିକ ସଂଖ୍ୟା: ୧୦୦',
'prefs-watchlist-token' => 'ଦେଖଣା ତାଲିକା ଟୋକନ:',
'prefs-misc' => 'ବିଭିନ୍ନ',
'prefs-resetpass' => 'ପାସୱାର୍ଡ଼ ବଦଳାନ୍ତୁ',
'prefs-changeemail' => 'ଇ-ମେଲ ପରିର୍ବତ୍ତନ କରନ୍ତୁ',
'prefs-setemail' => 'ଇ-ମେଲ ଠିକଣାଟିଏ  ଥୟ କରିବେ',
'prefs-email' => 'ଇ-ମେଲ ବିକଳ୍ପମାନ',
'prefs-rendering' => 'ଦେଖଣା',
'saveprefs' => 'ସାଇତିବେ',
'resetprefs' => 'ସାଇତା ହୋଇନଥିବା ବଦଳ ଲିଭାଇଦେବେ',
'restoreprefs' => 'ଆପେଆପେ ଥିବା ମୂଳ ସଜାଣିକୁ ଫେରିଯିବେ',
'prefs-editing' => 'ସମ୍ପାଦନା',
'prefs-edit-boxsize' => 'ସମ୍ପାଦନା ଘରର ଆକାର ।',
'rows' => 'ଧାଡ଼ି:',
'columns' => 'ସ୍ତମ୍ଭସବୁ:',
'searchresultshead' => 'ଖୋଜିବା',
'resultsperpage' => 'ପୃଷ୍ଠା ପ୍ରତି ହିଟ:',
'stub-threshold' => '<a href="#" class="stub">ଅସମ୍ପୂର୍ଣ ପୃଷ୍ଠା ଲିଙ୍କ</a> ସଜାଣି (ବାଇଟ) ପାଇଁ ସୀମା:',
'stub-threshold-disabled' => 'ଅଚଳ କରିଦିଆଯାଇଛି',
'recentchangesdays' => 'ନଗଦ ବଦଳରେ ଦେଖାଇବା ପାଇଁ ବାକିଥିବା ଦିନ:',
'recentchangesdays-max' => 'ସବୁଠାରୁ ଅଧିକ ହେଲେ $1 {{PLURAL:$1|ଦିନ|ଦିନ}}',
'recentchangescount' => 'ଆପେଆପେ ଦେଖାଯାଉଥିବା ବଦଳର ସଂଖ୍ୟା:',
'prefs-help-recentchangescount' => 'ଏଥିରେ ନଗଦ ବଦଳ, ପୃଷ୍ଠାର ଇତିହାସ ଓ ଲଗ ଇତିହାସ ରହିଅଛି ।',
'prefs-help-watchlist-token' => 'ଏହି ଘରେ ଏକ ଗୋପନ ଲେଖା ଦେଲେ RSS ଫିଡ଼ଟିଏ ଆପଣଙ୍କ ଦେଖଣାତାଲିକାରେ ତିଆରିବ ।
ଗୋପନ ଲେଖା ଜାଣିଥିବା କେହି ବି ଏହି ଘରେ ଆପଣଙ୍କ ଦେଖଣାତାଲିକା ଦେଖିପାରିବ, ତେଣୁ ଏକ ସୁରକ୍ଷିତ ନାମ ଦିଅନ୍ତୁ ।
ଏଠାରେ ଏକ ଇଆଡୁ ସିଆଡୁ ନାମ ଆପଣଙ୍କ ଜାଣିବା ପାଇଁ ଦିଆଗଲା: $1',
'savedprefs' => 'ଆପଣଙ୍କ ପସନ୍ଦସବୁ ସାଇତାଗଲା ।',
'timezonelegend' => 'ସମୟ ମଣ୍ଡଳ:',
'localtime' => 'ସ୍ଥାନୀୟ ସମୟ:',
'timezoneuseserverdefault' => 'ଉଇକିର ଆପେଆପେ ଆସୁଥିବା ($1) ବ୍ୟବହାର କରିବେ',
'timezoneuseoffset' => 'ବାକି (ଅଫସେଟ ସ୍ଥିର କରନ୍ତୁ)',
'timezoneoffset' => 'ଅଫସେଟ¹:',
'servertime' => 'ସର୍ଭର ସମୟ:',
'guesstimezone' => 'ବ୍ରାଉଜରରୁ ଭରିଦିଅନ୍ତୁ',
'timezoneregion-africa' => 'ଆଫ୍ରିକା',
'timezoneregion-america' => 'ଆମେରିକା',
'timezoneregion-antarctica' => 'ଆଣ୍ଟାର୍କଟିକା',
'timezoneregion-arctic' => 'ସୁମେରୁ',
'timezoneregion-asia' => 'ଏସିଆ',
'timezoneregion-atlantic' => 'ଆଟଲାଣ୍ଟିକ ମହାସାଗର',
'timezoneregion-australia' => 'ଅଷ୍ଟ୍ରେଲିଆ',
'timezoneregion-europe' => 'ଇଉରୋପ',
'timezoneregion-indian' => 'ଭାରତୀୟ ମହାସାଗର',
'timezoneregion-pacific' => 'ପ୍ରଶାନ୍ତ ମହାସାଗର',
'allowemail' => 'ବାକି ସଭ୍ୟମାନଙ୍କ ଠାରୁ ଆସିଥିବା ଇ-ମେଲ ସଚଳ କରାଇବେ',
'prefs-searchoptions' => 'ଖୋଜିବେ',
'prefs-namespaces' => 'ନେମସ୍ପେସ',
'defaultns' => 'ନଚେତ ଏହି ନେମସ୍ପେସ ଗୁଡ଼ିକରେ ଖୋଜନ୍ତୁ:',
'default' => 'ପୂର୍ବ ନିର୍ଦ୍ଧାରିତ',
'prefs-files' => 'ଫାଇଲ',
'prefs-custom-css' => 'ମନମୁତାବକ CSS',
'prefs-custom-js' => 'ମନମୁତାବକ JavaScript',
'prefs-common-css-js' => 'ସବୁ ଆବରଣ ପାଇଁ ବଣ୍ଟା ହୋଇଥିବା CSS/JavaScript:',
'prefs-reset-intro' => 'ଆପଣ ଏହି ପୃଷ୍ଠାଟି ବ୍ୟବହାର କରି ଆପଣା ପସନ୍ଦସବୁକୁ ସାଇଟର ଆରମ୍ଭରେ ଥିବା ସଜାଣିକୁ ଲେଉଟାଇଦେଇପାରିବେ ।
ଏହାକୁ ପଛକୁ ଫେରାଯାଇପାରିବ ନାହିଁ',
'prefs-emailconfirm-label' => 'ଇ-ମେଲ ସଜାଣି:',
'prefs-textboxsize' => 'ସମ୍ପାଦନା ଘରର ଆକାର',
'youremail' => 'ଇ-ମେଲ:',
'username' => 'ବ୍ୟବହାରକାରୀଙ୍କ ନାମ:',
'uid' => 'ବ୍ୟବହାରକାରୀ ଆଇଡ଼ି:',
'prefs-memberingroups' => '{{PLURAL:$1|ଗୋଠ|ଗୋଠ ସମୂହ}}ର ସଭ୍ୟ:',
'prefs-registration' => 'ନାମଲେଖା ବେଳା:',
'yourrealname' => 'ପ୍ରକୃତ ନାମ:',
'yourlanguage' => 'ଭାଷା:',
'yourvariant' => 'ଭିତର ଭାଗ ବିବିଧତା:',
'prefs-help-variant' => 'ଏହି ଉଇକିରେ ଥିବା ପୃଷ୍ଠାସବୁର ଦେଖଣାରେ ବ୍ୟବହାର ହୋଇପାରିବା ଭଳି ବନାନ ।',
'yournick' => 'ନୂଆ ସନ୍ତକ:',
'prefs-help-signature' => 'ଆଲୋଚନା ପୃଷ୍ଠାରେ ଦିଆଯାଉଥିବା ମତାମତରେ "<nowiki>~~~~</nowiki>" ଦେଇଦେଲେ ତାହା ସେଠାରେ ଆପେ ଆପେ ଆପଣଙ୍କ ନାମ ଓ ସମୟକୁ ବଦଳିଯିବ ।',
'badsig' => 'ମୂଳ ସନ୍ତକଟି ଅଚଳ ଅଟେ ।
HTML ଟାଗ ପରଖିନିଅନ୍ତୁ ।',
'badsiglength' => 'ଆପଣଙ୍କ ସନ୍ତକଟି ଖୁବ ଲମ୍ବା ।
ଏହା ବୋଧ ହୁଏ $1 {{PLURAL:$1|ଗୋଟି ଅକ୍ଷର|ଗୋଟି ଅକ୍ଷର}}ରୁ ଅଧିକ ।',
'yourgender' => 'ଲିଙ୍ଗ:',
'gender-unknown' => 'ଲୁଚାଯାଇଥିବା',
'gender-male' => 'ପୁରୁଷ',
'gender-female' => 'ନାରୀ',
'prefs-help-gender' => 'ଇଚ୍ଛାଧିନ: ସଫ୍ଟବେରରେ ଲିଙ୍ଗବାଚକ ସମ୍ବୋଧନ ନିମନ୍ତେ ବ୍ୟବହାର କରାଯାଇଥାଏ ।
ଏହି ତଥ୍ୟ ସାଧାରଣରେ ପ୍ରକାଶିତ ।',
'email' => 'ଇ-ମେଲ',
'prefs-help-realname' => 'ପ୍ରକୃତ ନାମ ଦେବା ଆପଣଙ୍କ ଉପରେ ନିର୍ଭର କରେ ।
ଯଦି ଆପଣ ଏହା ଦିଅନ୍ତି, ତେବେ ଏହା ଆପଣଙ୍କ କାମ ପାଇଁ ଶ୍ରେୟ ଦେବାରେ ବ୍ୟବହାର କରାଯାଇପାରିବ ।',
'prefs-help-email' => 'ଇ-ମେଲ ଠିକଣାଟି ଇଚ୍ଛାଧୀନ, କିନ୍ତୁ ଆପଣ ପାସବାର୍ଡ଼ଟି ଯଦି ଭୁଲିଗଲେ ତାହା ଆଉଥରେ ତିଆରିବା ପାଇଁ ଏହା କାମରେ ଲାଗିବ ।',
'prefs-help-email-others' => 'ଆପଣ ନିଜର ଇ-ମେଲଟିଏ ନିଜର ସଭ୍ୟ ବା ଆଲୋଚନା ପୃଷ୍ଠାରେ ଦେଇ ଅନ୍ୟମାନଙ୍କୁ ଇ-ମେଲରେ ଯୋଗଯୋଗ କରିବାର ସୁବିଧା ଦେଇପାରିବେ ।
ଆପଣଙ୍କୁ କେହି ମେଲ କଲେ ଆପଣଙ୍କ ଇ-ମେଲ ତାହାଙ୍କୁ ଦେଖାଯିବ ନାହିଁ ।',
'prefs-help-email-required' => 'ଇ-ମେଲ ଠିକଣାଟି ଲୋଡ଼ା ।',
'prefs-info' => 'ସାଧାରଣ ଜାଣିବା କଥା',
'prefs-i18n' => 'ଜଗତୀକରଣ',
'prefs-signature' => 'ସନ୍ତକ',
'prefs-dateformat' => 'ତାରିଖ ସଜାଣି',
'prefs-timeoffset' => 'ସମୟ ଆରମ୍ଭ',
'prefs-advancedediting' => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-advancedrc' => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-advancedrendering' => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-advancedsearchoptions' => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-advancedwatchlist' => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-displayrc' => 'ଦେଖଣା ବିକଳ୍ପ',
'prefs-displaysearchoptions' => 'ଦେଖଣା ବିକଳ୍ପ',
'prefs-displaywatchlist' => 'ଦେଖଣା ବିକଳ୍ପ',
'prefs-diffs' => 'ତଫାତସବୁ',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'ଇ-ମେଲ ଠିକଣା ବୈଧ ଭଳି ଲାଗୁଅଛି',
'email-address-validity-invalid' => 'ଏକ ସଠିକ ଇ-ମେଲ ଠିକଣା ଦିଅନ୍ତୁ',

# User rights
'userrights' => 'ସଭ୍ୟ ଅଧିକାର ପରିଚାଳନା',
'userrights-lookup-user' => 'ସଭ୍ୟ ଗୋଠ ପରିଚାଳନା କରିବେ',
'userrights-user-editname' => 'ଇଉଜର ନାମଟିଏ ଦିଅନ୍ତୁ:',
'editusergroup' => 'ଇଉଜର ଗୋଠ ସମ୍ପାଦନା କରନ୍ତୁ',
'editinguser' => "'''[[User:$1|$1]]''' $2 ଙ୍କର ସଭ୍ୟ ଅଧିକାର ବଦଳାଉଅଛୁଁ",
'userrights-editusergroup' => 'ଇଉଜର ଗୋଠ ସମ୍ପାଦନା କରନ୍ତୁ',
'saveusergroups' => 'ଇଉଜର ଗୋଠ ସମ୍ପାଦନା କରନ୍ତୁ',
'userrights-groupsmember' => 'ସଭ୍ୟ ଗୋଠ:',
'userrights-groupsmember-auto' => 'ଆପେଆପେ ଥିବା ସଭ୍ୟ ଗୋଠ:',
'userrights-groups-help' => 'ଏହି ସଭ୍ୟ ଥିବା ଗୋଠମାନ ଆପଣ ବଦଳାଇ ପାରିବେ:
* ଏକ ଛକଥିବା ଚାରିକୋଣିଆ ଘର ସଭ୍ୟଜଣକ ସେହି ଗୋଠରେ ଥିବାର ସୂଚାଏ ।
* ଏକ ଛକ ନଥିବା ଚାରିକୋଣିଆ ଘର ସଭ୍ୟଜଣକ ସେହି ଗୋଠରେ ନ ଥିବାର ସୂଚାଏ ।
* ଏକ * ସୂଚାଏ ଯେ ଆପଣ ସେହି ଗୋଠ ସହ ଯୋଡ଼ିହୋଇଗଲେ କେବେ ବାହାରିପାରିବେ ନାହିଁ ।',
'userrights-reason' => 'କାରଣ:',
'userrights-no-interwiki' => 'ଆପଣଙ୍କୁ ବାକି ଉଇକିରେ ସଭ୍ୟ ଅଧିକାର ବଦଳାଇବା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ ।',
'userrights-nodatabase' => '$1 ଡାଟାବେସଟି ନାହିଁ ବା କେବଳ ସ୍ଥାନୀୟ ହୋଇ ରହିଛି ।',
'userrights-nologin' => 'ଆପଣ ପରିଚାଳକ ଖାତାରୁ [[Special:UserLogin|ଲଗ ଇନ]] କରି ସଭ୍ୟ ଅଧିକାରର ସୁବିଧା ଦେଇପାରିବେ ।',
'userrights-notallowed' => 'ଆପଣଙ୍କ ଖାତାରେ ସଭ୍ୟ ଅଧିକାର ଯୋଡ଼ିବା ବା କାଢ଼ିବାର ଅନୁମତି ନାହିଁ ।',
'userrights-changeable-col' => 'ଆପଣ ବଦଳାଇପାରିବା ଗୋଠସମୂହ',
'userrights-unchangeable-col' => 'ଯେଉଁ ଗୋଠସବୁ ଆପଣ ବଦଳାଇପାରିବେ ନାହିଁ',

# Groups
'group' => 'ଗୋଠ:',
'group-user' => 'ବ୍ୟବହାରକାରୀଗଣ',
'group-autoconfirmed' => 'ଆପେଆପେ ଥୟ କରା ସଭ୍ୟ',
'group-bot' => 'ଆପେଆପେ ଚାଳିତ ସଭ୍ୟ',
'group-sysop' => 'ପରିଚାଳକଗଣ',
'group-bureaucrat' => 'ପ୍ରଶାସକ',
'group-suppress' => 'ଅଜାଣତ ଅଣଦେଖା',
'group-all' => '(ସବୁ)',

'group-user-member' => '{{GENDER:$1|ବ୍ୟବହାରକାରୀ}}',
'group-autoconfirmed-member' => '{{GENDER:$1|ଆପେଆପେ ଥୟ କରା ସଭ୍ୟ}}',
'group-bot-member' => '{{GENDER:$1|ଆପେଚାଳିତ ସଭ୍ୟ}}',
'group-sysop-member' => '{{GENDER:$1|ପରିଚାଳକ}}',
'group-bureaucrat-member' => '{{GENDER:$1|ପ୍ରଶାସକ}}',
'group-suppress-member' => '{{GENDER:$1|ଅଜାଣତ ଅଣଦେଖା}}',

'grouppage-user' => '{{ns:project}}:ବ୍ୟବହାରକାରୀ',
'grouppage-autoconfirmed' => '{{ns:project}}:ଆପେଆପେ ଥୟ କରା ସଭ୍ୟ',
'grouppage-bot' => '{{ns:project}}:ଆପେ ଚାଳିତ ସଭ୍ୟଗଣ',
'grouppage-sysop' => '{{ns:project}}:ପରିଚାଳକ',
'grouppage-bureaucrat' => '{{ns:project}}:ପ୍ରଶାସକଗଣ',
'grouppage-suppress' => '{{ns:project}}:ଅଜାଣତ ଅଣଦେଖା',

# Rights
'right-read' => 'ପୃଷ୍ଠାସବୁକୁ ପଢ଼ିବେ',
'right-edit' => 'ପୃଷ୍ଠାସବୁକୁ ବଦଳାଇବେ',
'right-createpage' => 'ପୃଷ୍ଠା ଗଢ଼ିବେ (ଯେଉଁଗୁଡ଼ିକ ଆଲୋଚନା ପୃଷ୍ଠା ହୋଇନଥିବ)',
'right-createtalk' => 'ଆଲୋଚନା ପୃଷ୍ଠା ଗଢ଼ିବେ',
'right-createaccount' => 'ନୂଆ ସଭ୍ୟ ଖାତାମାନ ଗଢ଼ିବେ',
'right-minoredit' => 'ବଦଳକୁ ଟିକେ ବୋଲି ଚିହ୍ନିତ କରିବେ',
'right-move' => 'ପୃଷ୍ଠାସବୁ ଘୁଞ୍ଚେଇବା',
'right-move-subpages' => 'ପୃଷ୍ଠା ସହିତ ସେମାନଙ୍କର ସାନପୃଷ୍ଠାସବୁକୁ ଘୁଞ୍ଚାଇଦେବେ',
'right-move-rootuserpages' => 'ମୂଳ ସଭ୍ୟ ପୃଷ୍ଠାସବୁକୁ ଘୁଞ୍ଚାଇଦେବେ',
'right-movefile' => 'ଫାଇଲସବୁକୁ ଘୁଞ୍ଚାଇଦେବେ',
'right-suppressredirect' => 'ପୃଷ୍ଠାସବୁକୁ ଘୁଞ୍ଚାଇବା ବେଳେ ମୂଳ ପୃଷ୍ଠାର ଫେରନ୍ତା ପୃଷ୍ଠା ତିଆରି କରିବେ ନାହିଁ',
'right-upload' => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'right-reupload' => 'ଆଗରୁ ଥିବା ଫାଇଲ ଉପରେ ମଡ଼ାଇ ଦେବେ',
'right-reupload-own' => 'ଜଣକ ଦେଇ ଅପଲୋଡ଼ କରାଯାଇଥିବା ଆଗରୁ ଥିବା ଫାଇଲ ଉପରେ ମଡ଼ାଇ ଦେବେ',
'right-reupload-shared' => 'ବଣ୍ଟାଯାଇଥିବା ସ୍ଥାନୀୟ ମାଧ୍ୟମ ଭଣ୍ଡାରର ଫାଇଲ ଗୁଡ଼ିକ ଭରିଦେବେ',
'right-upload_by_url' => 'ଏକ URLରୁ ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'right-purge' => 'ଥୟ କରିବା ବିନା ପୃଷ୍ଠାର ସାଇଟ ଅସ୍ଥାୟୀ ସ୍ମୃତିକୁ ସତେଜ କରିବେ',
'right-autoconfirmed' => 'ଅଧା-କିଳାଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ବଦଳାଇବେ',
'right-bot' => 'ଏକ ଆପେଆପେ ହେବା ପ୍ରକ୍ରିୟା ଭାବରେ ଗଣିବେ',
'right-nominornewtalk' => 'ଆଲୋଚନା ପୃଷ୍ଠାସବୁରେ ଛୋଟ ଛୋଟ ବଦଳ ହେଲେ ତାହା ନୂଆ ଚିଟାଉ ପଠାଇବ',
'right-apihighlimits' => 'API ଖୋଜାର ସର୍ବାଧିକ ସୀମା ବ୍ୟବହାର କରିବେ',
'right-writeapi' => 'API ଲେଖାର ବ୍ୟବହାର',
'right-delete' => 'ପୃଷ୍ଠାଟି ଲିଭାଇଦେବେ',
'right-bigdelete' => 'ବଡ଼ ଇତିହାସ ନଥିବା ପୃଷ୍ଠାସବୁକୁ ଲିଭାଇଦେବେ',
'right-deletelogentry' => 'କୌଣସି ଏକ ତାଲିକା ବିବରଣୀକୁ ଲିଭାଇବେ ଏବଂ ଏବଂ ଲିଭାଇବାରୁ ବାରଣ କରିବେ',
'right-deleterevision' => 'ଏକ ପୃଷ୍ଠାର ନିର୍ଦ୍ଦିଷ୍ଟ ସଙ୍କଳନମାନ ଲିଭାଇବେ ଓ ଲିଭାଇବାରୁ ରୋକିବେ',
'right-deletedhistory' => 'ଯୋଡ଼ାଯାଇଥିବା ଲେଖାକୁ ବାଦ ଦେଇ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାର ଇତିହାସ ଦେଖିବେ',
'right-deletedtext' => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ଲେଖା ଓ ଲିଭାଇ ଦିଆଯାଇଥିବା ଲେଖା ଭିତରର ସଙ୍କଳନର ବଦଳ ଦେଖିବେ',
'right-browsearchive' => 'ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ଖୋଜିବେ',
'right-undelete' => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ପୃଷ୍ଠାଟିଏକୁ ଫେରାଇ ଆଣିବେ',
'right-suppressrevision' => 'ପରିଛାମାନଙ୍କଠାରୁ ଲୁଚାଯାଇଥିବା ସଙ୍କଳନ ପରଖିବେ ଓ ଲେଉଟାଇବେ',
'right-suppressionlog' => 'ବ୍ୟକ୍ତିଗତ ଲଗ ଦେଖାଇବେ',
'right-block' => 'ବାକି ସଭ୍ୟମାନଙ୍କୁ ସମ୍ପାଦନାରୁ ବାରଣ କରିବେ',
'right-blockemail' => 'ଇ-ମେଲ ପଠାଇବାରୁ ଜଣେ ସଭ୍ୟଙ୍କୁ ବାରଣ କରିବେ',
'right-hideuser' => 'ସାଧାରଣରୁ ଲୁଚାଇ ଏକ ଇଉଜର ନାମକୁ ଅଟକାଇବେ',
'right-ipblock-exempt' => 'IP ଅଟକ, ଆପେଆପେ-ଅଟକ ଓ ସୀମା ଅଟକସବୁକୁ ଅଲଗା ଦିଗଗାମୀ କରିବେ',
'right-proxyunbannable' => 'ପ୍ରକ୍ସିର ଆପେଆପେ ହେଉଥିବା ଅଟକସବୁକୁ ଅଲଗା ଦିଗଗାମୀ କରିବେ',
'right-unblockself' => 'ସେମାନଙ୍କୁ ଅଟକରୁ ବାହାର କରିବେ',
'right-protect' => 'କିଳିବା ସ୍ତରକୁ ବଦଳାଇବେ ଓ କିଳାଯାଇଥିବା ପୃଷ୍ଠାମାନଙ୍କର ସମ୍ପାଦନା କରିବେ',
'right-editprotected' => 'କିଳାଯାଇଥିବା ପୃଷ୍ଠାମାନଙ୍କର ସମ୍ପାଦନା କରିବେ (କ୍ୟାସକେଡ଼କରା କିଳଣା ବିନା)',
'right-editinterface' => 'ସଭ୍ୟଙ୍କ ଇଣ୍ଟରଫେସ ବଦଳାଇବେ',
'right-editusercssjs' => 'ବାକି ସଭ୍ୟମାନଙ୍କର CSS ଓ ଜାଭାସ୍କ୍ରିପ୍ଟ ଫାଇଲ ସବୁକୁ ବଦଳାଇବେ',
'right-editusercss' => 'ବାକି ସଭ୍ୟମାନଙ୍କ CSS ଫାଇଲସବୁ ବଦଳାଇବେ',
'right-edituserjs' => 'ବାକି ସଭ୍ୟମାନଙ୍କର ଜାଭାସ୍କ୍ରିପ୍ଟ ଫାଇଲ ସବୁକୁ ବଦଳାଇବେ',
'right-rollback' => 'ଏକ ନିର୍ଦ୍ଦିଷ୍ଟ ପୃଷ୍ଠାକୁ ବଦଳାଇଥିବା ଶେଷ ସଭ୍ୟଙ୍କ ସମ୍ପାଦନାକୁ ସଙ୍ଗେସଙ୍ଗେ ପୁରାପୁରି ପଛକୁ ଫେରାଇଦେବେ',
'right-markbotedits' => 'ପୁରାପୁରି ପଛକୁ ଫେରାଇବା ବଦଳଗୁଡ଼ିକ ଆପେ ଆପେ କରା ବଦଳ ବୋଲି ଗଣିବେ',
'right-noratelimit' => 'ବିରଳ ସୀମା ଦେଇ ପ୍ରଭାବିତ ହୋଇ ନଥିବା',
'right-import' => 'ବାକି ଉଇକିରୁ ପୃଷ୍ଠାମାନ ଆମଦାନୀ କରିବେ',
'right-importupload' => 'ଏକ ଫାଇଲ ଅପଲୋଡ଼ରୁ ଏହି ପୃଷ୍ଠାସବୁ ଆଣିବେ',
'right-patrol' => 'ବାକି ମାନଙ୍କ ବଦଳକୁ ଜଗାଯାଇଥିବା ବଦଳ ବୋଲି ଚିହ୍ନିତ କରିବେ',
'right-autopatrol' => 'ଜଣକର ଆପଣା ସମ୍ପାଦନାସବୁ ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ ହୋଇଯାଉ',
'right-patrolmarks' => 'ନଗଦ ବଦଳସବୁ ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କରି ଦେଖାଇବେ',
'right-unwatchedpages' => 'ଦେଖାଯାଇନଥିବା ପୃଷ୍ଠାର ଏକ ତାଲିକା ଦେଖାଇବେ',
'right-mergehistory' => 'ପୃଷ୍ଠାମାନଙ୍କର ଇତିହାସ ମିଶାଇଦେବେ',
'right-userrights' => 'ଇଉଜର ଅଧିକାରସବୁକୁ ବଦଳାଇବେ',
'right-userrights-interwiki' => 'ବାକି ଉଇକିର ସଭ୍ୟମାନଙ୍କ ସଭ୍ୟ ଅଧିକାର ବଦଳାଇବେ',
'right-siteadmin' => 'ଡାଟାବେସକୁ କିଳିବେ ଓ ଖୋଲିବେ',
'right-override-export-depth' => '୫ଟି ଯାଏଁ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠା ସହିତ ସବୁ ପୃଷ୍ଠାକୁ ରପ୍ତାନୀ କରିବେ',
'right-sendemail' => 'ବାକି ସଭ୍ୟ ମାନଙ୍କୁ ଇ-ମେଲ ପଠାଇବେ',
'right-passwordreset' => 'ପାସୱାର୍ଡ଼ ପୁନସ୍ଥାପନ ଇମେଲ କରିବେ',

# User rights log
'rightslog' => 'ସଭ୍ୟଙ୍କ ଅଧିକାରର ଲଗ',
'rightslogtext' => 'ସଭ୍ୟଙ୍କ ଅଧିକାର ବଦଳର ଏହା ଏକ ଇତିହାସ ।',
'rightslogentry' => '$1 ପାଇଁ ଗୋଠ ସଭ୍ୟପଦର ଅବସ୍ଥା $2 ରୁ $3କୁ ବଦଳାଇଦିଆଗଲା',
'rightslogentry-autopromote' => '$2 ରୁ $3କୁ ଆପେଆପେ ଉନ୍ନୀତ କରାଗଲା',
'rightsnone' => '(କିଛି ନାହିଁ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'ଏହି ପୃଷ୍ଠାଟି ପଢ଼ିବେ',
'action-edit' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ବଦଳାଇବେ',
'action-createpage' => 'ପୃଷ୍ଠାଟିଏ ତିଆରିବା',
'action-createtalk' => 'ଆଲୋଚନା ପୃଷ୍ଠାସବୁ ଗଢ଼ିବେ',
'action-createaccount' => 'ଏହି ନୂଆ ସଭ୍ୟ ଖାତାଟିଏ ଗଢ଼ିବେ',
'action-minoredit' => 'ଏହି ବଦଳଟିକୁ ଟିକେ ବଦଳ ଭାବରେ ଚିହ୍ନିତ କରନ୍ତୁ',
'action-move' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'action-move-subpages' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ତାହାର ଉପପୃଷ୍ଠା ସହିତ ଘୁଞ୍ଚାଇବେ ।',
'action-move-rootuserpages' => 'ମୂଳ ସଭ୍ୟ ପୃଷ୍ଠାସବୁକୁ ଘୁଞ୍ଚାଇଦେବେ',
'action-movefile' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'action-upload' => 'ଏହି ଫାଇଲଟି ଅପଲୋଡ଼ କରିବେ',
'action-reupload' => 'ଆଗରୁ ଥିବା ଫାଇଲ ଉପରେ ମଡ଼ାଇ ଦେବେ',
'action-reupload-shared' => 'ଏହି ଫାଇଲଟି ଏକ ବଣ୍ଟା ଭଣ୍ଡାରରେ ମଡ଼ାଇ ଦେବେ',
'action-upload_by_url' => 'ଏକ URLରୁ ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'action-writeapi' => 'API ଲେଖାର ବ୍ୟବହାର',
'action-delete' => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇବେ',
'action-deleterevision' => 'ଏହି ସଙ୍କଳନଟି ଲିଭାଇବେ',
'action-deletedhistory' => 'ପୃଷ୍ଠାର ଲିଭାଯାଇଥିବା ଇତିହାସ ଦେଖିବେ',
'action-browsearchive' => 'ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ଖୋଜିବେ',
'action-undelete' => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ପୃଷ୍ଠାଟିଏକୁ ଫେରାଇ ଆଣିବେ',
'action-suppressrevision' => 'ଲୁଚାଯାଇଥିବା ସଙ୍କଳନକୁ ପରଖି ଆଉଥରେ ସ୍ଥାପନା କରିବେ',
'action-suppressionlog' => 'ବ୍ୟକ୍ତିଗତ ଲଗ ଦେଖାଇବେ',
'action-block' => 'ଏହି ସଭ୍ୟଙ୍କୁ ସମ୍ପାଦନାରୁ ବାରଣ କରିବେ',
'action-protect' => 'ଏହି ପୃଷ୍ଠାର କିଳିବା ସ୍ତରକୁ ବଦଳାଇବେ',
'action-rollback' => 'ଏକ ନିର୍ଦ୍ଦିଷ୍ଟ ପୃଷ୍ଠାକୁ ବଦଳାଇଥିବା ଶେଷ ସଭ୍ୟଙ୍କ ସମ୍ପାଦନାକୁ ସଙ୍ଗେସଙ୍ଗେ ପୁରାପୁରି ପଛକୁ ଫେରାଇଦେବେ',
'action-import' => 'ଆଉ ଏକ ଉଇକିରୁ ଏହି ପୃଷ୍ଠାଟି ଆଣିବେ',
'action-importupload' => 'ଏକ ଫାଇଲ ଅପଲୋଡ଼ରୁ ଏହି ପୃଷ୍ଠାଟି ଆଣିବେ',
'action-patrol' => 'ବାକି ମାନଙ୍କ ବଦଳକୁ ଜଗାଯାଇଥିବା ବଦଳ ବୋଲି ଚିହ୍ନିତ କରିବେ',
'action-autopatrol' => 'ଆପଣା ସମ୍ପାଦନାସବୁକୁ ଜଗାଯାଇଛି ବୋଲି ଚିହ୍ନିତ କରିବେ',
'action-unwatchedpages' => 'ଦେଖାଯାଇନଥିବା ପୃଷ୍ଠାର ଏକ ତାଲିକା ଦେଖାଇବେ',
'action-mergehistory' => 'ପୃଷ୍ଠାର ଇତିହାସ ମିଶାଇଦେବେ',
'action-userrights' => 'ଇଉଜର ଅଧିକାରସବୁକୁ ବଦଳାଇବେ',
'action-userrights-interwiki' => 'ବାକି ଉଇକିର ସଭ୍ୟମାନଙ୍କ ସଭ୍ୟ ଅଧିକାର ବଦଳାଇବେ',
'action-siteadmin' => 'ଡାଟାବେସକୁ କିଳିବେ ଓ ଖୋଲିବେ',
'action-sendemail' => 'ଇ-ମେଲ ପଠାଇବେ',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|ବଦଳ|ବଦଳସବୁ}}',
'recentchanges' => 'ନଗଦ ବଦଳ',
'recentchanges-legend' => 'ଏବେ କରାଯାଇଥିବା ଅଦଳବଦଳ',
'recentchanges-summary' => 'ଏହି ପୃଷ୍ଠାରେ ଏହି ଉଇକିରେ ନଗଦ ବଦଳର ନିଘା ରଖିବେ',
'recentchanges-feed-description' => 'ଏହି ଉଇକିରେ ଏହି ଫିଡ଼ଟିର ନଗଦ ବଦଳ ଦେଖାଇବେ ।',
'recentchanges-label-newpage' => 'ଏହି ବଦଳ ନୂଆ ଫରଦଟିଏ ତିଆରିକଲା',
'recentchanges-label-minor' => 'ଏହା ଗୋଟିଏ ଛୋଟ ବଦଳ',
'recentchanges-label-bot' => "ଏହି ବଦଳଟି ଜଣେ '''ବଟ'''ଙ୍କ ଦେଇ କରାଯାଇଥିଲା",
'recentchanges-label-unpatrolled' => 'ଏହି ବଦଳଟିକୁ ଏ ଯାଏଁ ପରଖା ଯାଇନାହିଁ',
'rcnote' => "ଗତ $5, $4 ସୁଦ୍ଧା {{PLURAL:$2|ଦିନ|'''$2''' ଦିନ}}ରେ ତଳ {{PLURAL:$1|'''ଗୋଟିଏ''' ବଦଳ|'''$1'''ଟି ଶେଷ ବଦଳ}} ହୋଇଅଛି ।",
'rcnotefrom' => "'''$2''' ପରର ବଦଳସବୁ ତଳେ ଦିଆଗଲା ('''$1''' ଯାଏଁ ଦେଖାଯାଇଛି) ।",
'rclistfrom' => '$1ରୁ ଆରମ୍ଭ କରି ନୂଆ ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'rcshowhideminor' => '$1 ଟି ଛୋଟମୋଟ ବଦଳ',
'rcshowhidebots' => '$1 ଜଣ ବଟ',
'rcshowhideliu' => '$1 ଜଣ ନାଆଁ ଲେଖାଇଥିବା ଇଉଜର',
'rcshowhideanons' => '$1 ଜଣ ବେନାମି ସଭ୍ୟ',
'rcshowhidepatr' => '$1ଟି ଜଗାହୋଇଥିବା ବଦଳ',
'rcshowhidemine' => '$1 ମୁଁ କରିଥିବା ବଦଳ',
'rclinks' => 'ଗଲା $2 ଦିନର $1 ବଦଳଗୁଡ଼ିକୁ ଦେଖାଇବେ<br />$3',
'diff' => 'ଅଦଳ ବଦଳ',
'hist' => 'ଇତିହାସ',
'hide' => 'ଲୁଚାନ୍ତୁ',
'show' => 'ଦେଖାଇବେ',
'minoreditletter' => 'ଟିକେ',
'newpageletter' => 'ନୂଆ',
'boteditletter' => 'ବଟ',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|ସଭ୍ୟ|ସଭ୍ୟଗଣା}}ଙ୍କୁ ଦେଖୁଅଛି]',
'rc_categories' => 'ଶ୍ରେଣୀସମୂହ ପାଇଁ ସୀମା ( "|" ଦେଇ ଅଲଗା କରିବେ)',
'rc_categories_any' => 'ଯେ କୌଣସି',
'rc-change-size-new' => 'ବଦଳପରେ $1 {{PLURAL:$1|byte|bytes}}',
'newsectionsummary' => '/* $1 */ ନୂଆ ଭାଗ',
'rc-enhanced-expand' => 'ପୁରା ଦେଖାଇବେ (ଜାଭାସ୍କ୍ରିପ୍ଟ ଦରକାର)',
'rc-enhanced-hide' => 'ବେଶି କଥାସବୁ ଲୁଚାଇଦିଅ',
'rc-old-title' => 'ପ୍ରକୃତରେ "$1" ଭାବେ ତିଆରି କରାଯାଇକଥିଲା',

# Recent changes linked
'recentchangeslinked' => 'ଏଇମାତ୍ର ବଦଳାଯାଇଥିବା ପୃଷ୍ଠାର ଲିଙ୍କ',
'recentchangeslinked-feed' => 'ଯୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-toolbox' => 'ଯୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-title' => '"$1" ସାଁଗରେ ଜୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-noresult' => 'ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠା ସବୁରେ ଏଇ ସମୟସୀମା ଭିତରେ କିଛି ବଦଳାଯାଇନାହିଁ ।',
'recentchangeslinked-summary' => "ଏଇଟି କିଛିସମୟ ଆଗରୁ ନିର୍ଦ୍ଦିଷ୍ଟ ପୃଷ୍ଠାରୁ ଲିଙ୍କ ହୋଇଥିବା ଆଉ ବଦଳାଯାଇଥିବା (ଅବା ଗୋଟିଏ ନିର୍ଦ୍ଦିଷ୍ଟ ଶ୍ରେଣୀର) ପୃଷ୍ଠାସବୁର ତାଲିକା ।  [[Special:Watchlist|ମୋର ଦେଖାତାଲିକା]]ର ପୃଷ୍ଠା ସବୁ '''ବୋଲଡ଼'''।",
'recentchangeslinked-page' => 'ଫରଦର ନାଆଁ',
'recentchangeslinked-to' => 'ଦିଆଯାଇଥିବା ଫରଦରେ ଯୋଡ଼ା ବାକି ଫରଦମାନଙ୍କର ବଦଳ ସବୁ ଦେଖାନ୍ତୁ ।',

# Upload
'upload' => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'uploadbtn' => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'reuploaddesc' => 'ଅପଲୋଡ଼କୁ ନାକଚ କରିବେ ଓ ଅପଲୋଡ଼ ଫର୍ମକୁ ଫେରିବେ',
'upload-tryagain' => 'ବଦଳିଥିବ ଫାଇଲ ବଖାଣ ପଠାଇବା',
'uploadnologin' => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
'uploadnologintext' => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ [[Special:UserLogin|ଲଗ ଇନ]] କରିବାକୁ ପଡ଼ିବ ।',
'upload_directory_missing' => 'ଅପଲୋଡ଼ ସୂଚି ($1)ଟି ମିଳୁନାହିଁ ଓ ୱେବସର୍ଭର ଦେଇ ତିଆରି କରାଯାଇପାରିଲା ନାହିଁ ।',
'upload_directory_read_only' => 'ଅପଲୋଡ଼ ସୂଚି ($1)ଟି ସବୁ ୱେବସର୍ଭରରେ ଲେଖାଯାଇ ପାରିବ ନାହିଁ ।',
'uploaderror' => 'ଅପଲୋଡ଼ କରିବାରେ ଅସୁବିଧା',
'upload-recreate-warning' => "'''ଚେତାବନୀ: ସେହି ନାମରେ ଥିବା ଫାଇଲଟି ଲିଭାଯାଇଅଛି ବା ଘୁଞ୍ଚାଯାଇଅଛି ।'''

ଏହି ପୃଷ୍ଠାର ଲିଭାଇବା ବା ଘୁଞ୍ଚାଇବା ଇତିହାସ ଆପଣଙ୍କର ଅବଗତି ନିମନ୍ତେ ଦିଆଗଲା :",
'uploadtext' => "ତଳ ପତ୍ରଟି ଫାଇଲ ଅପଲୋଡ଼ କରିବା ନିମନ୍ତେ ବ୍ୟବଭାର କରନ୍ତୁ ।
ଆଗରୁ ଅପଲୋଡ଼ କରାଯାଇଥିବା ଫାଇଲ [[Special:FileList|ଅପଲୋଡ଼ ହୋଇସାରିଥିବା ଫାଇଲ]] ଖୋଜିବା ବା ଦେଖିବା ପାଇଁ, (ପୁନ) ଅପଲୋଡ଼ମାନ [[Special:Log/upload|ଅପଲୋଡ଼ ଇତିହାସ]]ରେ ରହିଛି, ଲିଭାଯାଇଥିବା ଇତିହାସ [[Special:Log/delete|ଲିଭାଯାଇଥିବା ଇତିହାସ]]ରେ ରହିଛି ।

ଏକ ପୃଷ୍ଠାରେ ଫାଇଲଟିଏ ଭରିବା ନିମନ୍ତେ ତଳଲିଖିତ ଫର୍ମରେ ଲିଙ୍କଟିଏ ବ୍ୟବହାର କରିବେ:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' ଫାଇଲଟିର ପୁରା ସଂସ୍କରଣ ବ୍ୟବହାର କରିବେ
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></code>''' ବାମ ଏକ ୨୦୦ ପିକ୍ସେଲର ଚଉଡ଼ା ଘରେ ବିବରଣୀ 'alt text' ଥିବା ବର୍ଣ୍ଣନା ରହିବ
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' ଫାଇଲଟି ନ ଦେଖାଇ ଫାଇଲ ସହ ସିଧା ଯୋଡ଼ିବା",
'upload-permitted' => 'ଅନୁମୋଦିତ ଫାଇଲ ପ୍ରକାର: $1 ।',
'upload-preferred' => 'ପସନ୍ଦର ଫାଇଲ ପ୍ରକାର: $1 ।',
'upload-prohibited' => 'ଅନନୁମୋଦିତ ଫାଇଲ ପ୍ରକାର: $1 ।',
'uploadlog' => 'ଅପଲୋଡ଼ ଇତିହାସ',
'uploadlogpage' => 'ଲଗ ଅପଲୋଡ଼ କରିବେ',
'uploadlogpagetext' => 'ତଳେ ନଗଦ ଅତୀତରେ କରାଯାଇଥିବା ଫାଇଲ ଅପଲୋଡ଼ର ତାଲିକା ଦେଖନ୍ତୁ । 
ଅଧିକ ଦେଖଣା ପାଇଁ [[Special:NewFiles|ନୂଆ ଫାଇଲର ଗ୍ୟାଲେରି]] ଦେଖନ୍ତୁ ।',
'filename' => 'ଫାଇଲ ନାମ',
'filedesc' => 'ସାରକଥା',
'fileuploadsummary' => 'ସାରକଥା:',
'filereuploadsummary' => 'ଫାଇଲ ବଦଳ:',
'filestatus' => 'କପିରାଇଟ ସ୍ଥିତି:',
'filesource' => 'ମୂଳାଧାର:',
'uploadedfiles' => 'ଫାଇଲସବୁ ଅପଲୋଡ଼ କରିବେ',
'ignorewarning' => 'ଚେତାବନୀକୁ ଅଣଦେଖା କରି ଫାଇଲତିକୁ ସେହିପରି ସାଇତି ରଖନ୍ତୁ',
'ignorewarnings' => 'ଚେତାବନୀ ସବୁକୁ ଅଣଦେଖା କରନ୍ତୁ',
'minlength1' => 'ଫାଇଲ ନାମଟି ଅତି କମରେ ଗୋଟିଏ ଅକ୍ଷର ହୋଇଥିବା ଲୋଡ଼ା ।',
'illegalfilename' => '"$1" ନାମରେ ଥିବା ଫାଇଲର ନାମରେ ଥିବା ଅକ୍ଷର ପୃଷ୍ଠା ଶିରୋନାମାରେ ରଖିବା ପାଇଁ ଅନୁମୋଦିତ ନୁହେଁ ।
ଦୟାକରି ଫାଇଲର ନାମଟି ବଦଳାନ୍ତୁ ଓ ଆଉଥରେ ଅପଲୋଡ଼ କରନ୍ତୁ ।',
'filename-toolong' => 'ଫାଇଲ ନାମ ୨୦୦ ବାଇଟରୁ ଅଧିକ ନ ହେବା ଉଚିତ ।',
'badfilename' => 'ଫାଇଲ ନାମ "$1"କୁ ବଦଳାଇ ଦିଆଯାଇଛି ।',
'filetype-mime-mismatch' => 'ଫାଇଲ ଏକ୍ସଟେନସନ ".$1" ଚିହ୍ନଟ ହୋଇଥିବା MIME ଫାଇଲ ପ୍ରକାର ସଙ୍ଗେ ମେଳ ଖାଉନାହିଁ ($2) ।',
'filetype-badmime' => '"$1" MIME ପ୍ରକାରର ଫାଇଲ ଅପଲୋଡ଼ କରିବା ଅନୁମୋଦିତ ନୁହେଁ ।',
'filetype-bad-ie-mime' => 'ଏହି ଫାଇଲଟି ଅପଲୋଡ଼ କରାଯାଇପାରିବ ନାହିଁ କାରଣ ଇଣ୍ଟରନେଟ ଏକ୍ସପ୍ଲୋରର ଏହାକୁ "$1" ବୋଲି ଚିହ୍ନଟ କରିବ, ଯାହାକି ଏକ ଅନନୁମୋଦିତ ଓ ସମ୍ଭାବିତ ବିପଦଜନକ ଫାଇଲ ପ୍ରକାର ।',
'filetype-unwanted-type' => "'''\".\$1\"''' ଏକ ଅଦରକାରୀ ଫାଇଲ ପ୍ରକାର ।
ପସନ୍ଦଯୋଗ୍ୟ {{PLURAL:\$3|ଫାଇଲ ପ୍ରକାର|ଫାଇଲ ପ୍ରକାରସବୁ}} ହେଲା \$2 ।",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' {{PLURAL:$4|ଏକ ଅନୁମୋଦିତ ଫାଇଲ ପ୍ରକାର ନୁହେଁ|ମାନ ଅନୁମୋଦିତ ଫାଇଲ ପ୍ରକାର ନୁହଁନ୍ତି}} ।
ଅନୁମୋଦିତ {{PLURAL:$3|ଫାଇଲ ପ୍ରକାର ହେଲା|ଫାଇଲ ପ୍ରକାର ହେଲା}} $2 ।',
'filetype-missing' => 'ଏହି ଫାଇଲଟିର କିଛି ବି ଏକ୍ସଟେନସନ ନାହିଁ (ଯଥା ".jpg") ।',
'empty-file' => 'ଆପଣ ପଠାଇଥିବା ଫାଇଲଟି ଖାଲି ଅଟେ ।',
'file-too-large' => 'ଆପଣ ପଠାଇଥିବା ଫାଇଲଟି ବହୁ ବିରାଟ ଅଟେ ।',
'filename-tooshort' => 'ଫାଇଲ ନାମଟି ଖୁବ ଛୋଟ',
'filetype-banned' => 'ଏହି ପ୍ରକାରର ଫାଇଲ ବାରଣ କରାଯାଇଅଛି ।',
'verification-error' => 'ଏହି ଫାଇଲଟି ଫାଇଲ ପରୀକ୍ଷଣରେ ଅସଫଳ ହେଲା ।',
'hookaborted' => 'ଏକ ଏକ୍ସଟେନସନ ହୁକ ଦେଇ ଆପଣ କରୁଥିବା ବଦଳଟି ବନ୍ଦ କରିଦିଆଗଲା ।',
'illegal-filename' => 'ଏହି ଫାଇଲ ନାମଟି ଅନୁମୋଦିତ ନୁହେଁ ।',
'overwrite' => 'ଆଗରୁଥିବା ଏକ ଫାଇଲ ଉପରେ ମଡ଼ାଇବା ଅନୁମୋଦିତ ନୁହେଁ ।',
'unknown-error' => 'ଏକ ଅଜଣା ଅସୁବିଧା ଘଟିଲା ।',
'tmp-create-error' => 'ଅସ୍ଥାୟୀ ଫାଇଲ ତିଆରି କରିପାରିଲୁଁ ନାହିଁ ।',
'tmp-write-error' => 'ଅସ୍ଥାୟୀ ଫାଇଲ ଲେଖିବାରେ ଅସୁବିଧା ହେଲା ।',
'large-file' => 'ଫାଇଲ ସବୁ $1ରୁ ବଡ଼ ନ ହେବା ଅନୁମୋଦିତ;
ଏହି ଫାଇଲଟି $2 ।',
'largefileserver' => 'ଏହି ସର୍ଭରର ଅନୁମୋଦିତ ସଂରଚନା ଠାରୁ ଏହି ଫାଇଲଟି ବଡ଼ ।',
'emptyfile' => 'ଆପଣ ଅପଲୋଡ଼ କରିଥିବା ଫାଇଲଟି ଫାଙ୍କା ବୋଲି ବୋଧ ହୁଏ ।
ଏହା ହୁଏତ ଫାଇଲ ନାମରେ କିଛି ଭୁଲ ଜନିତ ହୋଇଥାଇପାରେ ।
ସତରେ ଆପଣ ଏହି ଫାଇଲଟି ଅପଲୋଡ଼ କରିବାକୁ ଚାହାନ୍ତି କି ନାଁ ଠାରେ ପରଖି ନିଅନ୍ତୁ ।',
'windows-nonascii-filename' => 'ଏହି ଉଇକି ବିଶେଷ ସଂକେତ ଥିବା ଫାଇଲ ନାମକୁ ଅନୁମତି ଦିଏ ନାହିଁ ।',
'fileexists' => 'ଏହି ଏକା ନାଆଁରେ ଆଗରୁ ଫାଇଲଟିଏ ଅଛି , ସତରେ ଆପଣ ଏହାକୁ ଅପଲୋଡ଼ କରିବାକୁ ଚାହାନ୍ତି କି ନାଁ ଦୟାକରି <strong>[[:$1]]</strong> ପରଖି ନିଅନ୍ତୁ ।
[[$1|thumb]]',
'filepageexists' => 'ଏହି ଫାଇଲର ବିବରଣୀ ପୃଷ୍ଠାଟି <strong>[[:$1]]</strong> ଠାରେ ତିଆରି କରାଯାଇଅଛି, କିନ୍ତୁ ଏହି ନାମରେ ଗୋଟିଏ ବି ଫାଇଲ ନାହିଁ ।
ବିବରଣୀ ପୃଷ୍ଠାରେ ଆପଣ ଦେଇଥିବା ସାରକଥା ଦେଖାଯିବ ନାହିଁ ।
ଆପଣଙ୍କ ବିବରଣୀ ସେଠାରେ ଦେଖାଇବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ନିଜେ ଏହା ବଦଳାଇବାକୁ ପଡ଼ିବ ।
[[$1|thumb]]',
'fileexists-extension' => 'ଏକାପରି ନାଆଁ ଥିବା ଫାଇଲଟିଏ ଆଗରୁ ଅଛି: [[$2|thumb]]
* ଅପଲୋଡ଼ କରାଯାଉଥିବା ଫାଇଲର ନାମ: <strong>[[:$1]]</strong>
* ଆଗରୁ ଥିବା ଫାଇଲର ନାମ: <strong>[[:$2]]</strong>
ଦୟାକରି ଅଲଗା ନାମଟିଏ ବାଛନ୍ତୁ ।',
'fileexists-thumbnail-yes' => "ଫାଇଲଟି ଏକ ସାନ ଆକାରର ଛବି ବୋଲି ବୋଧ ହୁଏ ''(ନଖଦେଖଣା)''.
[[$1|thumb]]
ଦୟାକରି <strong>[[:$1]]</strong> ଫାଇଲଟି ପରଖି ନିଅନ୍ତୁ ।
ଯଦି ବଛା ଫାଇଲଟି ମୂଳ ଫାଇଲ ଆକାରର ହୋଇଥାଏ ତେବେ ଆଉ ଗୋଟିଏ ନଖଦେଖଣା ସାନ ଛବି ଅପଲୋଡ଼ କରିବାକୁ ପଡ଼ିବ ।",
'file-thumbnail-no' => "ଫାଇଲ ନାମ <strong>$1</strong>ରେ ଆରମ୍ଭ ହୋଇଥାଏ ।
ଏହା ଏକ ଛୋଟ ଆକାରର ଛବି ଭଳି ବୋଧ ହୁଏ ''(ଛୋଟଦେଖଣା)'' ।
ଯଦି ଆପଣଙ୍କ ପାଖରେ ପୁରା ରେଜୋଲୁସନର ଛବିଟିଏ ଅଛି ତେବେ ତାହା ଅପଲୋଡ଼ କରନ୍ତୁ କିମ୍ବା ଫାଇଲ ନାମ ବଦଳାଇ ଦିଅନ୍ତୁ ।",
'fileexists-forbidden' => 'ଏହି ନାମରେ ଫାଇଲଟିଏ ଆଗରୁ ଅଛି ଯାହା ଉପରେ ଆଉଥରେ ମଡ଼ାଯାଇପାରିବ ନାହିଁ ।
ତଥାପି ଯଦି ଆପଣ ଫାଇଲ ଅପଲୋଡ଼ କରିବାକୁ ଚାହୁଁଥାନ୍ତି ତେବେ ପଛକୁ ଯାଇ ନୂଆ ନାମଟିଏ ଦିଅନ୍ତୁ ।
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'ଏହି ନାମରେ ଫାଇଲଟିଏ ଆଗରୁ ବଣ୍ଟାଯାଇଥିବା ଫାଇଲ ଭଣ୍ଡାରରେ ଅଛି ।
ଯଦି ଆପଣ ନିଜର ଫାଇଲଟିଏ ଅପଲୋଡ କରିବାକୁ ଚାହୁଁଥିବେ ତାହାହେଲେ ପଛକୁ ଫେରି ନୂଆ ନାମଟିଏ ଦିଅନ୍ତୁ ।
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'ଏହି ଫାଇଲଟି ଏହି {{PLURAL:$1|ଫାଇଲଟି|ଫାଇଲ ମାନଙ୍କ}}ର ଏକ ନକଲ ଅଟେ:',
'file-deleted-duplicate' => '([[:$1]]) ସଙ୍ଗେ ସମାନ ଫାଇଲଟି ଆଗରୁ ଲିଭାଇଦିଆଗଲା ।
ଆପଣ ଫାଇଲଟିକୁ ଆଉଥରେ ଅପଲୋଡ଼ କରିବା ଆଗରୁ ତାହାର ଲିଭାଇବା ଇତିହାସ ଦେଖିନିଅନ୍ତୁ ।',
'uploadwarning' => 'ଅପଲୋଡ଼ ଚେତାବନୀ',
'uploadwarning-text' => 'ତଳେ ଥିବା ଫାଇଲର ବିବରଣୀ ବଦଳାଇ ଆଉ ଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'savefile' => 'ଫାଇଲ ସାଇତିବା',
'uploadedimage' => '"[[$1]]" ଅପଲୋଡ କରାଗଲା',
'overwroteimage' => '"[[$1]]"ର ନୂଆ ସଙ୍କଳନଟିଏ ଅପଲୋଡ଼ କରାଗଲା',
'uploaddisabled' => 'ଅପଲୋଡ଼ କରିବା ଅଚଳ କରାଯାଇଅଛି ।',
'copyuploaddisabled' => 'URL ଦେଇ ଅପଲୋଡ଼ କରିବାକୁ ଅଚଳ କରାଯାଇଅଛି ।',
'uploadfromurl-queued' => 'ଆପଣଙ୍କ ଅପଲୋଡ଼ ଧାଡ଼ିରେ ରହିଲା ।',
'uploaddisabledtext' => 'ଫାଇଲ ଅପଲୋଡ଼  ଅଚଳ କରାଯାଇଅଛି ।',
'php-uploaddisabledtext' => 'PHPରେ ଫାଇଲ ଅପଲୋଡ଼କୁ ଅଚଳ କରାଯାଇଅଛି ।
ଦୟାକରି ଫାଇଲ_ଅପଲୋଡ଼ ସଜାଣିକୁ ପରଖି ନିଅନ୍ତୁ ।',
'uploadscripted' => 'ଏହି ଫାଇଲଟିରେ HTML ବା ସ୍କ୍ରିପ୍ଟ କୋଡ଼ ଥିବାରୁ ଏକ ବେବ ବ୍ରାଉଜରରେ ଅଲଗା ରଖିବେ ।',
'uploadvirus' => 'ଏହି ଫାଇଲଟିରେ ଏକ ଭାଇରସ ରହିଅଛି!
ସବିଶେଷ: $1',
'uploadjava' => 'ଏହି ଫାଇଲଟି ଏକ ZIP ଫାଇଲ ଯେଉଁଥିରେ Java .class ଫାଇଲ ଅଛି ।
Java ଫାଇଲ ଅପଲୋଡ଼ କରିବା ଅନୁମୋଦିତ ନୁହେଁ କାରଣ ସେସବୁ ସୁରକ୍ଷା ବଳୟକୁ ନଷ୍ଟ କରିଦିଅନ୍ତି ।',
'upload-source' => 'ଉତ୍ସ ଫାଇଲ',
'sourcefilename' => 'ମୂଳ ଫାଇଲ ନାମ:',
'sourceurl' => 'ଉତ୍ସ ୟୁ.ଆର୍.ଏଲ୍.:',
'destfilename' => ':ମୁକାମ ଫାଇଲ ନାମ:',
'upload-maxfilesize' => 'ସବୁଠାରୁ ବଡ଼ ଫାଇଲ ଆକାର: $1',
'upload-description' => 'ଫାଇଲ ବିବରଣୀ',
'upload-options' => 'ଅପଲୋଡ଼ ବିକଳ୍ପସମୂହ',
'watchthisupload' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଦେଖିବେ',
'filewasdeleted' => 'ଆଗରୁ ଏହି ନାମରେ ଅପଲୋଡ଼ କରାଯାଇଥିବା ଫାଇଲଟିଏ ଲିଭାଇ ଦିଆଯାଇଅଛି  ।
ଆଉଥରେ ଅପଲୋଡ଼ କରିବା ଆଗରୁ ଆପଣ $1ଟି ଠାରେ ପରଖି ନିଅନ୍ତୁ ।',
'filename-bad-prefix' => "ଆପଣ ଅପଲୋଡ଼ କରୁଥିବା '''\"\$1\"'' ନାମରେ ଆରମ୍ଭ ହେଉଥିବା ଫାଇଲ, ଯାହାକି ଏକ ବଖଣାଯାଇନଥିବା ନାମରେ ନାମିତ ଓ ଆପେଆପେ ଡିଜିଟାଲ କ୍ୟାମେରାରୁ ଆସିଥାଏ ।
ଦୟାକରି ଏହି ଫାଇଲ ପାଇଁ ଏକ ବୁଝାପଡୁଥିବା ନାମଟିଏ ଦିଅନ୍ତୁ ।",
'upload-success-subj' => 'ଅପଲୋଡ଼ ସଫଳ',
'upload-success-msg' => 'ଆପଣଙ୍କ ଅପଲୋଡ଼ ଫର୍ମ [$2] ସଫଳ ହେଲା । ଏହା [[:{{ns:file}}:$1]] ଠାରେ ମିଳୁଅଛି ।',
'upload-failure-subj' => 'ଅପଲୋଡ଼ରେ ଅସୁବିଧା',
'upload-failure-msg' => ' [$2]ରୁ ଆପଣଙ୍କ କରିଥିବା ଅପଲୋଡ଼ରେ ଗୋଟିଏ ଅସୁବିଧା ଥିଲା:

$1',
'upload-warning-subj' => 'ଅପଲୋଡ଼ ଚେତାବନୀ',
'upload-warning-msg' => '[$2]ରୁ ଆପଣ କରିଥିବା ଅପଲୋଡରେ ଗୋଟିଏ ଅସୁବିଧା ଥିଲା । ଆପଣ [[Special:Upload/stash/$1|ଅପଲୋଡ଼ ଫର୍ମ]]କୁ ଫେରି ଏହି ଅସୁବିଧାଟିକୁ ସୁଧାରି ପାରନ୍ତି ।',

'upload-proto-error' => 'ଭୁଲ ପ୍ରଟକଲ',
'upload-proto-error-text' => 'ସୁଦୂରର ଅପଲୋଡ଼ପାଇଁ URL ସବୁ <code>http://</code> କିମ୍ବା <code>ftp://</code> ରେ ଆରମ୍ଭ ହେଉଥିବା ଲୋଡ଼ା ।',
'upload-file-error' => 'ଭିତରର ଭୁଲ',
'upload-file-error-text' => 'ସର୍ଭରରେ ଅସ୍ଥାୟୀ ଫାଇଲଟିଏ ତିଆରିବା ବେଳେ ଏକ ଭିତରର ଅସୁବିଧାଟିଏ ଘଟିଲା ।
ଦୟାକରି ଜଣେ [[Special:ListUsers/sysop|ପରିଛା]]ଙ୍କ ସହ ଯୋଗାଯୋଗ କରନ୍ତୁ ।',
'upload-misc-error' => 'ଅଜଣା ଅପଲୋଡ଼ ଅସୁବିଧା',
'upload-misc-error-text' => 'ଅପଲୋଡ଼ କରିବା ବେଳେ ଅଜଣା ଅସୁବିଧାଟିଏ ଘଟିଲା ।
ଦୟାକରି URL ଟି ଠିକ ଅଛି କି ନାହିଁ ପରଖି ନିଅନ୍ତୁ ଓ ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।
ଯଦି ଅସୁବିଧା ଲାଗିରହେ ତେବେ ଜଣେ [[Special:ListUsers/sysop|ପରିଛା]]ଙ୍କ ସଙ୍ଗେ ଯୋଗାଯୋଗ କରନ୍ତୁ ।',
'upload-too-many-redirects' => 'ଏହି URL ଟିରେ ଅନେକ ଗୁଡ଼ିଏ ଫେରନ୍ତା ଲିଙ୍କ ଅଛି',
'upload-unknown-size' => 'ଅଜଣା ଆକାର',
'upload-http-error' => 'HTTP ଅସୁବିଧାଟିଏ ଘଟିଲା: $1',
'upload-copy-upload-invalid-domain' => 'ନକଲ ଅପଲୋଡଗୁଡିକ ଏହି ଡୋମେନ ରେ ଉପଲବ୍ଧ ନାହିଁ ।',

# File backend
'backend-fail-stream' => 'ଷ୍ଟ୍ରିମ ଫାଇଲ $1ଟି ମିଳିଲା ନାହିଁ ।',
'backend-fail-backup' => 'ଭବିଷ୍ୟତ ପାଇଁ ଥିବା $1 ଫାଇଲଟି ମିଳିଲା ନାହିଁ ।',
'backend-fail-notexists' => '$1 ଫାଇଲଟି ମିଳିଲା ନାହିଁ ।',
'backend-fail-hashes' => 'ତୁଳନା ପାଇଁ ଫାଇଲ ହାସ ମିଳିଲା ନାହିଁ ।',
'backend-fail-notsame' => '$1 ଠାରେ ଏକ ଅସମ ଫାଇଲ ଆଗରୁ ଅଛି ।',
'backend-fail-invalidpath' => '$1 ଏକ ବୈଧ ସାଇତିବା ପଥ ନୁହେଁ ।',
'backend-fail-delete' => '$1 ଫାଇଲଟି ଲିଭାଇ ପାରିବେ ନାହିଁ ।',
'backend-fail-alreadyexists' => '$1 ଫାଇଲଟି ଆଗରୁ ଅଛି ।',
'backend-fail-store' => '$2 ଠାରେ $1 ଫାଇଲଟି ସାଇତାଯାଇ ପାରିଲା ନାହିଁ ।',
'backend-fail-copy' => '$1 ଫାଇଲଟିରୁ $2 କୁ ଫାଇଲ ନକଲ କରାଯାଇପାରିବ ନାହିଁ ।',
'backend-fail-move' => '$1 ରୁ $2 କୁ ଫାଇଲ ନକଲ କରିପାରିଲୁ ନାହିଁ ।',
'backend-fail-opentemp' => 'ଅସ୍ଥାୟୀ ଫାଇଲ ଖୋଲିହେଲା ନାହିଁ ।',
'backend-fail-writetemp' => 'ଅସ୍ଥାୟୀ ଫାଇଲ ତିଆରି କରିପାରିଲୁ ନାହିଁ ।',
'backend-fail-closetemp' => 'ଅସ୍ଥାୟୀ ଫାଇଲ ବନ୍ଦ କରିହେଲା ନାହିଁ ।',
'backend-fail-read' => '$1 ଫାଇଲଟି ପଢ଼ିପାରିଲୁ ନାହିଁ ।',
'backend-fail-create' => '$1 ଫାଇଲରେ କିଛି ଲେଖି ହେଲା ନାହିଁ ।',
'backend-fail-maxsize' => '"$1" ଫାଇଲଟିକୁ ଲେଖି ପାରିଲା ନାହିଁ କାରଣ ଏହା {{PLURAL:$2|one byte|$2 bytes}}ଠାରୁ ବଡ ।',
'backend-fail-readonly' => 'ସାଇତାଲେଖା "$1" କେବଳ ପଢିହେବ । କାରଣଟି ହେଉଛି: "\'\'$2\'\'"',
'backend-fail-synced' => 'ଭିତର ସାଇତା ମଧ୍ୟରେ "$1"ଏକ ଅସ୍ଥାୟୀ ଫାଇଲ',
'backend-fail-connect' => 'ସାଇତିବା ଧାରକ "$1" ସହ ଯୋଗାଯୋଗ ହୋଇ ପାରିଲା ନାହିଁ ।',
'backend-fail-internal' => 'ସାଇତା ଧାରକ "$1" କିଛି ଅଜଣା ଅସୁବିଧା ଉପୁଜିଲା ।',
'backend-fail-contenttype' => '"$1"ରେ ଥିବା ସାଇତା ପାଇଁ ଥିବା ଫାଇଲର ବିଷୟବସ୍ତୁର ପ୍ରକାର ଗୁଡିକ ଜଣାପଡୁନି ।',
'backend-fail-batchsize' => 'ସାଇତା ଧାରକକୁ  $1 ଫାଇଲର {{PLURAL:$1|operation|operations}} ଏକ ଗୋଛା ଦିଆଯାଇଥିଲା; ସୀମା ହେଉଛି $2 {{PLURAL:$2|operation|operations}} ।',
'backend-fail-usable' => 'ଅନୁମତି ନଥିବାରୁ କିମ୍ବା ଧାରକ/ସୂଚୀ ନଥିବାରୁ"$1" ଫାଇଲଟିକୁ ପଢି କିମ୍ବା ଲେଖି ହେଲାନି ।',

# File journal errors
'filejournal-fail-dbconnect' => 'ସାଇତା ଧାରକ "$1" ପାଇଁ ପାଠ୍ୟ ଡାଟାବେସକୁ ସଂଯୋଗ କରିହେଲାନି ।',
'filejournal-fail-dbquery' => 'ସାଇତା ଧାରକ "$1" ପାଇଁ ପାଠ୍ୟ ଡାଟାବେସକୁ ଅପଡେଟ କରିହେଲାନି ।',

# Lock manager
'lockmanager-notlocked' => 'କିଳାଯାଇଥିବା "$1"କୁ ଖୋଲିପାରିଲୁ ନାହିଁ; ଏହା ପ୍ରକୃତରେ କିଳାଯାଇନାହିଁ ।',
'lockmanager-fail-closelock' => '"$1" ପାଇଁ ତାଲା ପରି କାମ କରିବା ଫାଇଲଟି ମିଳିଲାନାହିଁ ।',
'lockmanager-fail-deletelock' => '"$1" ଫାଇଲଟି କିଳାଯାଇଥିବାରୁ ଲିଭାଯାଇପାରିଲା ନାହିଁ ।',
'lockmanager-fail-acquirelock' => '"$1" ଚାବି ପାଇଁ ତାଲା ମିଳିଲା ନାହିଁ ।',
'lockmanager-fail-openlock' => '"$1" ଫାଇଲଟି କିଳାଯାଇଥିବାରୁ ଖୋଲାଯାଇ ପାରିଲା ନାହିଁ ।',
'lockmanager-fail-releaselock' => '"$1" ଚାବି ପାଇଁ ତାଲା ମିଳିଲା ନାହିଁ ।',
'lockmanager-fail-db-bucket' => '$1 ଝୁଡ଼ିରେ ଥିବା ସବୁ କିଳାଯାଇଥିବା ଡାଟାବେସ ସହ ଯୋଗାଯୋଗ କରାଯାଇପାରିଲା ନାହିଁ ।',
'lockmanager-fail-db-release' => '$1 ଡାଟାବେସରେ ଲାଗିଥିବା ତାଲା ଖୋଲାଯାଇପାରିଲା ନାହିଁ ।',
'lockmanager-fail-svr-acquire' => '$1 ସର୍ଭରରେ ଲାଗିଥିବା ତାଲା ଖୋଲାଯାଇପାରିଲା ନାହିଁ ।',
'lockmanager-fail-svr-release' => '$1 ସର୍ଭରରେ ଲାଗିଥିବା ତାଲା ଖୋଲାଯାଇପାରିଲା ନାହିଁ ।',

# ZipDirectoryReader
'zip-file-open-error' => 'ZIP ପରଖିବା ପାଇଁ ଫାଇଲଟି ଖୋଲିଲା ବେଳେ ଅସୁବିଧାଟିଏ ଘଟିଲା ।',
'zip-wrong-format' => 'ଦିଆଯାଇଥିବା ଫାଇଲଟି ଏକ ZIP ଫାଇଲ ନୁହେଁ ।',
'zip-bad' => 'ଏହା ଏକ ବିଗିଡ଼ି ଯାଇଥିବା ଫାଇଲ ବା ପଢ଼ାଯାଇପାରୁନଥିବା ZIP ଫାଇଲ ।
ଏହାକୁ ପ୍ରତିରକ୍ଷା ନିମନ୍ତେ  ଠିକ ଭାବେ ପରଖା ଯାଇପାରିବ ନାହିଁ ।',
'zip-unsupported' => 'ଏହା ଏକ ZIP ଫାଇଲ ଯାହା ZIP ବିଶେଷତା ସବୁ ବ୍ୟବହାର କରିଥାଏ ଓ ମିଡ଼ିଆଉଇକି ଦେଇ ଅନୁମୋଦିତ ନୁହେଁ ।
ଏହା ଠିକ ଭାବେ ପ୍ରତିରକ୍ଷା ପାଇଁ ପରଖା ଯାଇପାରିବ ନାହିଁ ।',

# Special:UploadStash
'uploadstash' => 'ଭଣ୍ଡାର ଅପଲୋଡ଼ କରିବେ',
'uploadstash-summary' => 'ଏହି ପୃଷ୍ଠା ଅପଲୋଡ଼ ହୋଇଥିବା ଫାଇଲସବୁକୁ ଯିବାକୁ ବାଟ କଢ଼ାଇଥାଏ (ବା ଅପଲୋଡ଼ କରିବାରେ) ସହଯୋଗ କରିଥାଏ କିନ୍ତୁ ଏଯାଏଁ ଉଇକିରେ ପ୍ରକାଶିତ ହୋଇନାହିଁ । ଏହି ଫାଇଲ ସବୁକୁ ଅପଲୋଡ଼ କରିଥିବା ଲୋକଙ୍କ ଛଡ଼ା ଆଉକାହାକୁ ତାହା ଦେଖାଯିବ ନାହିଁ ।',
'uploadstash-clear' => 'ଲୁଚାଯାଇଥିବା ଭଣ୍ଡାର ଫାଇଲ ସବୁକୁ ସଫା କରିଦେବେ',
'uploadstash-nofiles' => 'ଆପଣଙ୍କ ପାଖରେ ଗୋଟିଏ ବି ଲୁଚାଯାଇଥିବା ଭଣ୍ଡାରର ଫାଇଲ ନାହିଁ ।',
'uploadstash-badtoken' => 'ସେହି କାମଟି କରିବାରେ ଅସଫଳ ହେଲୁ, ବୋଧେ ଆପଣଙ୍କ ଇଉଜର ନାମ ଓ ପାସବାର୍ଡ଼ ଆଦିର ମିଆଦ ପୁରିଗଲାଣି । ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'uploadstash-errclear' => 'ଫାଇଲମାନଙ୍କର ଲିଭାଇବା ଅସଫଳ ରହିଲା ।',
'uploadstash-refresh' => 'ଫାଇଲମାନଙ୍କର ତାଲିକାକୁ ସତେଜ କରିବେ',
'invalid-chunk-offset' => 'ଅବୈଧ ଖରାପ ଅଫସେଟ',

# img_auth script messages
'img-auth-accessdenied' => 'ଭିତରକୁ ପଶିବାକୁ ବାରଣ କରାଗଲା',
'img-auth-nopathinfo' => 'ପଥ_ବିବରଣୀ ମିଳୁ ନାହିଁ।
ଆପଣଙ୍କ ସର୍ଭରରେ ଏହି ତଥ୍ୟଟିକୁ ପଠାଇବା ନିମନ୍ତେ ବ୍ୟବସ୍ଥା କରାଯାଇନାହିଁ ।
ଏହା ସିଜିଆଇ-ଆଧାରିତ ହୋଇପାରେ ଓ ଆଇଏମଜି_ଅଉଥରେ କାମକରୁନଥାଇପାରେ  ।
https://www.mediawiki.org/wiki/Manual:Image_Authorization ଦେଖନ୍ତୁ ।',
'img-auth-notindir' => 'ଅନୁରୋଧ କରାଯାଇଥିବା ପଥ ସଂରଚିତ ଅପଲୋଡ଼ ତାଲିକା ନୁହେଁ ।',
'img-auth-badtitle' => '"$1"ରୁ ଏକ ସଠିକ ଶିରୋନାମା ଗଠନ କରିବାରେ ଅସଫଳ ହେଲୁଁ ।',
'img-auth-nologinnWL' => 'ଆପଣ ଲଗ ଇନ କରି ନାହାନ୍ତି ଓ "$1" ଏକ ଅନୁମୋଦିତ ତାଲିକାରେ ନାହିଁ ।',
'img-auth-nofile' => '"$1" ଫାଇଲଟି ଏଠାରେ ନାହିଁ ।',
'img-auth-isdir' => 'ଆପଣ "$1" ସୂଚି ତାଲିକାଟି ଦେଖିବାକୁ ଚେଷ୍ଟା କରୁଛନ୍ତି ।
ଏଠାରେ କେବଳ ଫାଇଲ ଦେଖିବା ଅନୁମୋଦିତ ।',
'img-auth-streaming' => '"$1" ନିୟମିତ ଲୋଡ଼ ଚାଲିଅଛି ।',
'img-auth-public' => 'img_auth.php ର କାମ ଏକ ବ୍ୟକ୍ତିଗତ ଉଇକିରେ ଫାଇଲ ଦେଖାଇବା ।
ଏହି ଉଇକିଟି ଏକ ବ୍ୟକ୍ତିଗତ ଉଇକି ଭାବରେ ସଜାଯାଇଛି ।
ଅଧିକ ସୁରକ୍ଷା ନିମନ୍ତେ, img_auth.php କୁ ଅଟକାଯାଇଛି ।',
'img-auth-noread' => '"$1" ପଢ଼ିବା ନିମନ୍ତେ ସଭ୍ୟଙ୍କୁ ଅନୁମତି ମିଳିନାହିଁ ।',
'img-auth-bad-query-string' => 'URL ଟିର ଖୋଜାଯିବା ପ୍ରଶ୍ନଟି ଅଚଳ ଅଟେ ।',

# HTTP errors
'http-invalid-url' => 'ଅଚଳ URL: $1',
'http-invalid-scheme' => '"$1" ପ୍ରକାରର URL ମାନ ଏଠାରେ କାମ କରିବ ନାହିଁ ।',
'http-request-error' => 'ଅଜଣା କାରଣରୁ HTTP ଅନୁରୋଧ ବିଫଳ ହେଲା ।',
'http-read-error' => 'HTTP ପଢ଼ିବା ଭୁଲ ।',
'http-timed-out' => 'HTTP ଅନୁରୋଧ ମିଆଦ ପୁରିଗଲା ।',
'http-curl-error' => '$1 URL କୁ ପାଇବାରେ ବିଫଳ',
'http-host-unreachable' => 'URLଟି ପାଇଲୁ ନାହିଁ ।',
'http-bad-status' => 'HTTP ଅନୁରୋଧ ବେଳେ କିଛି ଅସୁବିଧା ହେଲା : $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL ଠେଇଁ ପହଞ୍ଚି ପାଇଲୁ ନାହିଁ ।',
'upload-curl-error6-text' => 'ଦିଆଯାଇଥିବା URL ଠେଇଁ ପହଞ୍ଚି ପାରିଲୁଁ ନାହିଁ ।
URLଟି ଠିକ ଅଚିକି କି ନାଁ ଓ ସାଇଟଟି ସଚଳ ଅଚିକି କି ନାଁ ଦୟାକରି ଆଉଥରେ ପରଖି ନିଅନ୍ତୁ ।',
'upload-curl-error28' => 'ଅପଲୋଡ଼ କରିବା ମିଆଦ ସରିଗଲା',
'upload-curl-error28-text' => 'ଏହି ୱେବସାଇଟଟି ଜବାବ ଦେବାପାଇଁ ବହୁତ ସମୟ ନେଲା ।
ଦୟାକରି ଦେଖନ୍ତୁ ଯେ ଉକ୍ତ ସାଇଟଟି ସଚଳ ଅଛି, କିଛି ସମୟ ଅପେକ୍ଷା କରନ୍ତୁ ଏବଂ ପୁଣିଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।
ଆପଣ ଟିକେ କମ ବ୍ୟସ୍ତ ସମୟ ଭିତରେ ଚେଷ୍ଟା କରିପାରନ୍ତି ।',

'license' => 'ସତ୍ଵ:',
'license-header' => 'ସତ୍ଵ',
'nolicense' => 'ଗୋଟିଏ ବି ବଛାଯାଇନାହିଁ',
'license-nopreview' => '(ସାଇତିବା ଆଗଦେଖଣା ମିଳୁନାହିଁ)',
'upload_source_url' => '(ଏକ ବୈଧ ସାଧାରଣରେ ଖୋଲାଯାଇପାରୁଥିବା URL)',
'upload_source_file' => '(ଆପଣଙ୍କ କମ୍ପୁଟରରେ ଥିବା ଏକ ଫାଇଲ)‌',

# Special:ListFiles
'listfiles-summary' => 'ଏହି ବିଶେଷ ପୃଷ୍ଠାଟି ସବୁଯାକ ଅପଲୋଡ଼ କରାଯାଇଥିବା ଫାଇଲ ଦେଖାଇଥାଏ ।
ବଛାହେବା ବେଳେ କେବଳ ସଭ୍ୟଙ୍କ ଦେଇ ନଗଦ ଅପଲୋଡ଼ କରାଯାଇଥିବା ଫାଇଲ ଦେଖାଯାଇଥାଏ ।',
'listfiles_search_for' => 'ମାଧ୍ୟମ ନାମଟି ଖୋଜିବେ:',
'imgfile' => 'ଫାଇଲ',
'listfiles' => 'ଫାଇଲ ତାଲିକା',
'listfiles_thumb' => 'ଛୋଟ ଛବି',
'listfiles_date' => 'ତାରିଖ',
'listfiles_name' => 'ନାମ',
'listfiles_user' => 'ବ୍ୟବହାରକାରୀ',
'listfiles_size' => 'ଆକାର',
'listfiles_description' => 'ବିବରଣୀ',
'listfiles_count' => 'ସଂସ୍କରଣ',

# File description page
'file-anchor-link' => 'ଫାଇଲ',
'filehist' => 'ଫାଇଲ ଇତିହାସ',
'filehist-help' => 'ଏହା ଫାଇଲଟି ସେତେବେଳେ ଯେମିତି ଦିଶୁଥିଲା ତାହା ଦେଖିବା ପାଇଁ ତାରିଖ/ବେଳା ଉପରେ କ୍ଲିକ କରନ୍ତୁ',
'filehist-deleteall' => 'ସବୁକିଛି ଲିଭାଇ ଦେବେ',
'filehist-deleteone' => 'ଲିଭାଇବେ',
'filehist-revert' => 'ପଛକୁ ଫେରାଇବେ',
'filehist-current' => 'ଏବେକାର',
'filehist-datetime' => 'ତାରିଖ/ବେଳ',
'filehist-thumb' => 'ନଖ ଦେଖଣା',
'filehist-thumbtext' => '$1 ପରିକା ସଙ୍କଳନର ନଖଦେଖଣା',
'filehist-nothumb' => 'ସାନଦେଖଣା ନାହିଁ',
'filehist-user' => 'ବ୍ୟବହାରକାରୀ',
'filehist-dimensions' => 'ଆକାର',
'filehist-filesize' => 'ଫାଇଲ ଆକାର',
'filehist-comment' => 'ମତାମତ',
'filehist-missing' => 'ଫାଇଲ ମିଳୁନାହିଁ',
'imagelinks' => 'ଫାଇଲ ବ୍ୟବହାର',
'linkstoimage' => 'ଏହି ସବୁ{{PLURAL:$1|ପୃଷ୍ଠା|$1 ପୃଷ୍ଠାସବୁ}} ଏହି ଫାଇଲଟିକୁ ଯୋଡ଼ିଥାନ୍ତି:',
'linkstoimage-more' => '$1 {{PLURAL:$1|ପୃଷ୍ଠା ଲିଙ୍କ|ପୃଷ୍ଠା ଲିଙ୍କ}} ଏହି ଫାଇଲ ସହ ଯୋଡ଼ା ।
ଏହି ତଳ ଲିଙ୍କ ଫାଇଲକୁ {{PLURAL:$1|ପ୍ରଥମ ପୃଷ୍ଠା ଲିଙ୍କ|ପୃଷ୍ଠା $1 ପୃଷ୍ଠା ଲିଙ୍କ}} ଦେଖାଇଥାଏ ।
ଏକ [[Special:WhatLinksHere/$2|ପୁରା ତାଲିକା]] ଏଠାରେ ଅଛି ।',
'nolinkstoimage' => 'ଏହି ଫାଇଲ ସହିତ ଯୋଡ଼ା ଗୋଟିଏ ବି ପୃଷ୍ଠା ନାହିଁ ।',
'morelinkstoimage' => 'ଏହି ଫାଇଲ ସହିତ ଯୋଡ଼ା [[Special:WhatLinksHere/$1|ଆହୁରି ଅଧିକ ଲିଙ୍କ]] ଦେଖନ୍ତୁ ।',
'linkstoimage-redirect' => '$1 (ଫାଇଲ ଅନୁପ୍ରେରଣ) $2',
'duplicatesoffile' => 'ତଳଲିଖିତ {{PLURAL:$1|ଫାଇଲଟି ଏହି ଫାଇଲର ଏକ ନକଲ|$1 ଫାଇଲସବୁ ଏହି ଫାଇଲର ନକଲ ଅଟନ୍ତି}} ([[Special:FileDuplicateSearch/$2|ଅଧିକ ସବିଶେଷ]]):',
'sharedupload' => 'ଏହି ଫାଇଲଟି $1 ରୁ ଆଉ ବାକି ପ୍ରକଳ୍ପରେ ବ୍ୟବହାର କରାଯାଇପାରିବ .',
'sharedupload-desc-there' => 'ଏହି ଫାଇଲଟି $1 ଠାରୁ ଓ ବାକି ପ୍ରକଳ୍ପରେ ବ୍ୟବହାର ହୋଇପାରେ ।
ଅଧିକ ଜାଣିବା ନିମନ୍ତେ ଦୟାକରି [$2 ଫାଇଲ ବିବରଣୀ ପୃଷ୍ଠା ଦେଖନ୍ତୁ] ।',
'sharedupload-desc-here' => 'ଏହି ଫାଇଲଟି $1 ଠାରୁ ଓ ଏହା ଅନ୍ୟ ପ୍ରକଳ୍ପରେ ବ୍ୟବହାର କରାଯାଇପାରିବ ।
ଏହାର ବିବରଣୀ [$2 ଫାଇଲ ବିବରଣୀ ପୃଷ୍ଠାରେ] ତଳେ ରହିଅଛି ।',
'sharedupload-desc-edit' => 'ଏହି ଫାଇଲଟି $1ରୁ ଆସିଛି ଏବଂ ଏହା ଅନ୍ୟ ପ୍ରକଳ୍ପଗୁଡିକରେ ବ୍ୟବହୃତ ହୋଇଥାଇ ପାରେ ।
ଆପଣ ଏହାର ବର୍ଣ୍ଣନାଟିକୁ ଏହାର [$2 file description page]ରେ ବଦଳାଇ ପାରନ୍ତି ।',
'sharedupload-desc-create' => 'ଏହି ଫାଇଲଟି $1ରୁ ଆସିଛି ଏବଂ ଏହା ଅନ୍ୟ ପ୍ରକଳ୍ପଗୁଡିକରେ ବ୍ୟବହୃତ ହୋଇଥାଇ ପାରେ ।
ଆପଣ ଏହାର ବର୍ଣ୍ଣନାଟିକୁ ଏହାର [$2 file description page]ରେ ବଦଳାଇ ପାରନ୍ତି ।',
'filepage-nofile' => 'ଏହି ନାମରେ କୌଣସିଟି ଫାଇଲ ନାହିଁ ।',
'filepage-nofile-link' => 'ଏହି ନାମରେ କିଛି ବି ଫାଇଲ ନାହିଁ, କିନ୍ତୁ ଆପଣ ଏହା [$1 ଅପଲୋଡ଼] କରିପାରିବେ ।',
'uploadnewversion-linktext' => 'ଏହି ଫାଇଲର ନୂଆ ସଙ୍କଳନଟିଏ ଅପଲୋଡ଼ କରିବେ',
'shared-repo-from' => '$1 ଠାରୁ',
'shared-repo' => 'ଏକ ବଣ୍ଟା ଭଣ୍ଡାର',
'upload-disallowed-here' => 'ଆପଣ ଏହି ଫାଇଲଟିକୁ ବଦଳାଇପାରିବେ ନାହିଁ ।',

# File reversion
'filerevert' => '$1କୁ ଫେରାଇଦେବା',
'filerevert-legend' => 'ପଛକୁ ଫେରାଇବା ଫାଇଲ',
'filerevert-intro' => "ଆପଣ '''[[Media:$1|$1]]''' ଫାଇଲଟିକୁ [$4 ତମ ସଙ୍କଳନକୁ $3, $2 ବେଳେ] ଫେରାଇବାକୁ ଯାଉଛନ୍ତି ।",
'filerevert-comment' => 'କାରଣ:',
'filerevert-defaultcomment' => '$2, $1 ବେଳେ ସଙ୍କଳନକୁ ଫେରାଇ ଦିଆଗଲା',
'filerevert-submit' => 'ପଛକୁ ଫେରାଇବେ',
'filerevert-success' => "'''[[Media:$1|$1]]''' [$3, $2 ବେଳେ $4 ତମ ସଙ୍କଳନକୁ] ଫେରାଇଦିଆଗଲା ।",
'filerevert-badversion' => 'ଏହି ଫାଇଲର କିଛି ବି ଆଗର ସ୍ଥାନୀୟ ଫାଇଲ ନାହିଁ ଯେଉଁଥିରେ ଏହି ସମୟ ଛିହ୍ନଟି ଦିଆଯାଇଛି ।',

# File deletion
'filedelete' => 'ଲିଭାଇବା $1',
'filedelete-legend' => 'ଫାଇଲଟି ଲିଭାଇବେ',
'filedelete-intro' => "ଆପଣ '''[[Media:$1|$1]]''' ପୃଷ୍ଠାଟି ଇତିହାସ ସମେତ ଲିଭାଇବାକୁ ଯାଉଛନ୍ତି ।",
'filedelete-intro-old' => "[$4 $3, $2] ବେଳେ ଆପଣ '''[[Media:$1|$1]]''' ସଂସ୍କରଣରୁ ଲିଭାଉ ଅଛନ୍ତି ।",
'filedelete-comment' => 'କାରଣ:',
'filedelete-submit' => 'ଲିଭାଇବେ',
'filedelete-success' => "'''$1''' ପୃଷ୍ଠାଟି ଲିଭାଇ ଦିଆଗଲା ।",
'filedelete-success-old' => "$3, $2 ବେଳର '''[[Media:$1|$1]]''' ଟି ଲିଭାଇଦିଆଗଲା ।",
'filedelete-nofile' => "'''$1'''ଟି ମିଳିଲା ନାହିଁ ।",
'filedelete-nofile-old' => "ଦିଆଯାଇଥିବା ବିଶେଷତା ଠିଆ '''$1'''ର ଗୋଟିଏ ବି ଅଭିଲେଖ ସଂସ୍କରଣ ନାହିଁ ।",
'filedelete-otherreason' => 'ବାକି/ଅଧିକ କାରଣ:',
'filedelete-reason-otherlist' => 'ଅଲଗା କାରଣ',
'filedelete-reason-dropdown' => '*ସାଧାରଣ ଲିଭାଇବା କାରଣମାନ
** ସତ୍ଵାଧିକାର ଉଲ୍ଲଙ୍ଘନ
** ନକଲି ଫାଇଲ',
'filedelete-edit-reasonlist' => 'ଲିଭାଇବା କାରଣମାନ ବଦଳାଇବେ',
'filedelete-maintenance' => 'ରକ୍ଷଣାବେକ୍ଷଣ ନିମନ୍ତେ ଫାଇଲ ଲିଭାଇବା ଓ ପୁନସ୍ଥାପନ କିଛି କାଳ ପାଇଁ ଅଚଳ କରିଦିଆଯାଇଛି ।',
'filedelete-maintenance-title' => 'ଏହି ଫାଇଲକୁ ଲିଭାଯାଇପାରିବ ନାହି',

# MIME search
'mimesearch' => 'MIME ଖୋଜା',
'mimesearch-summary' => 'ଏହି ପୃଷ୍ଠାଟି ଫାଇଲ ମାନଙ୍କର MIME ପ୍ରକାରକୁ ଛଣିବାରେ ସହଯୋଗ କରିଥାଏ ।
ଇନପୁଟ: ବିଷୟ ଶ୍ରେଣୀ/ଉପ ଶ୍ରେଣ, ଯଥା: <code>image/jpeg</code> ।',
'mimetype' => 'MIME ପ୍ରକାର:',
'download' => 'ଡାଉନଲୋଡ଼',

# Unwatched pages
'unwatchedpages' => 'ଦେଖାହୋଇନଥିବା ପୃଷ୍ଠା',

# List redirects
'listredirects' => 'ପୁନପ୍ରେରଣ ପୃଷ୍ଠାସମୂହର ତାଲିକା',

# Unused templates
'unusedtemplates' => 'ବ୍ୟବହାର ହୋଇନଥିବା ଛାଞ୍ଚ',
'unusedtemplatestext' => 'ଏହି ପୃଷ୍ଠାରେ {{ns:template}} ନେମସ୍ପେସରେ ଥିବା ସବୁ ପୃଷ୍ଠାର ତାଲିକା ତିଆରି କରିଥାଏ ଯାହା ଆଉ ଏକ ପୃଷ୍ଠା ଭିତରେ ନାହିଁ ।
ଲିଭାଇବା ଆଗରୁ ଛାଞ୍ଚ ପାଇଁ ଥିବା ବାକି ଲିଙ୍କ ସବୁ ପରଖି ନିଅନ୍ତୁ ।',
'unusedtemplateswlh' => 'ଅନ୍ୟ ସଂଯୋଗ',

# Random page
'randompage' => 'ଯାହିତାହି ପୃଷ୍ଠା',
'randompage-nopages' => 'ତଳେ ଥିବା {{PLURAL:$2|ନେମସ୍ପେସ|ନେମସ୍ପେସ}}: $1ରେ ଗୋଟିଏ ବି ପୃଷ୍ଠା ନାହିଁ ।',

# Random redirect
'randomredirect' => 'ଯାହିତାହି ପୁନପ୍ରେରଣ',
'randomredirect-nopages' => '"$1" ନାମରେ ଗୋଟିଏ ବି ପୁନପ୍ରେରଣ ନାହିଁ ।',

# Statistics
'statistics' => 'ହିସାବ',
'statistics-header-pages' => 'ପୃଷ୍ଠା ପରିସଙ୍ଖ୍ୟାନ',
'statistics-header-edits' => 'ପରିସଙ୍ଖ୍ୟାନ ସମ୍ପାଦନା',
'statistics-header-views' => 'ପରିସଙ୍ଖ୍ୟାନ ଦେଖିବେ',
'statistics-header-users' => 'ସଭ୍ୟ ପରିସଙ୍ଖ୍ୟାନ',
'statistics-header-hooks' => 'ବାକି ପରିସଙ୍ଖ୍ୟାନ',
'statistics-articles' => 'ସୂଚୀ ପୃଷ୍ଠାସମୂହ',
'statistics-pages' => 'ପୃଷ୍ଠା',
'statistics-pages-desc' => 'ଆଲୋଚନା ପୃଷ୍ଠା, ପୁନପ୍ରେରଣକୁ ମିଶାଇ ଏହି ଉଇକିର ପୃଷ୍ଠାମାନ ।',
'statistics-files' => 'ଫାଇଲସବୁ ଅପଲୋଡ଼ କରାଗଲା',
'statistics-edits' => '{{SITENAME}} ତିଆରିହେବା ବେଳରୁ ପୃଷ୍ଠାର ନାମ',
'statistics-edits-average' => 'ପୃଷ୍ଠାପ୍ରତି ହାରାହାରି ସମ୍ପାଦନା',
'statistics-views-total' => 'ମୋଟ ଦେଖଣା',
'statistics-views-total-desc' => 'ଅଚଳ ପୃଷ୍ଠା ଓ ବିଶେଷ ପୃଷ୍ଠାର ଦେଖଣା ଏହା ଭିତରେ ଦିଆଯାଇନାହିଁ',
'statistics-views-peredit' => 'ସମ୍ପାଦନା ପ୍ରତି ଦେଖା',
'statistics-users' => 'ପଞ୍ଜିକରଣ କରିଥିବା [[Special:ListUsers|ସଭ୍ୟଗଣ]]',
'statistics-users-active' => 'ସଚଳ ସଭ୍ୟ',
'statistics-users-active-desc' => 'ବିଗତ {{PLURAL:$1|ଦିନରେ|$1 ଦିନରେ}} କିଛି କାମ କରିଥିବା ସଭ୍ୟଗଣ',
'statistics-mostpopular' => 'ସବୁଠାରୁ ଅଧିକ ଦେଖାଯାଇଥିବା ପୃଷ୍ଠା',

'disambiguations' => 'ବହୁବିକଳ୍ପ ପୃଷ୍ଠାମାନଙ୍କ ସହ ଯୋଡ଼ା ପୃଷ୍ଠା',
'disambiguationspage' => 'Template:ବହୁବିକଳ୍ପ',
'disambiguations-text' => "ତଳେ ଥିବା ପୃଷ୍ଠାଗୁଡିକ ଅତିକମରେ ଗୋଟେ ଗୋଟେ '''ବହୁବିକଳ୍ପ ପୃଷ୍ଠା'''କୁ ଯୋଡ଼ିଥାନ୍ତି ।
ସେସବୁ ଅଧିକ ଉପଯୁକ୍ତ ପ୍ରସଙ୍ଗ ସହ ଯୋଡ଼ାହେବା ଉଚିତ  ।<br />
[[MediaWiki:Disambiguationspage]] ସହ ଯୋଡ଼ାଥିବା ଛାଞ୍ଚ ବ୍ୟବହାର କରୁଥିଲେ ପୃଷ୍ଠାଟିଏକୁ ବହୁବିକଳ୍ପ ପୃଷ୍ଠା ବୋଲି କୁହାଯାଏ",

'doubleredirects' => 'ଯୋଡ଼ା ପୁନପ୍ରେରଣ',
'doubleredirectstext' => 'ଏହି ପୃଷ୍ଠା ବାକି ବହୁବିକଳ୍ପ ପୃଷ୍ଠାମାନଙ୍କ ସହ ଯୋଡ଼ିଥାଏ ।
ପ୍ରତ୍ୟେକ ଧାଡ଼ିରେ ପ୍ରଥମ ଓ ଶେଷ ପୁନପ୍ରେରଣ ସହ ଯୋଡ଼ିବା ଲିଙ୍କ ରହିଥାଏ, ଆହୁରି ମଧ୍ୟ ଏଥିରେ ଦ୍ଵିତୀୟ ପୁନପ୍ରେରଣର ଲକ୍ଷ ସହ ଯୋଡ଼ିବାର ଲିଙ୍କ ଥାଏ , ଯାହାକି ସାଧାରଣତ "ପ୍ରକୃତ" ଲକ୍ଷ ପୃଷ୍ଠା ହୋଇଥାଏ, ଯାହାକୁ ପ୍ରଥମ ପୁନପ୍ରେରଣ ପୃଷ୍ଠା ଯୋଡ଼ିଥାଏ ।
<del>କଟାହୋଇଥିବା</del> ନିବେଶସବୁ ସଜଡ଼ାଗଲା ।',
'double-redirect-fixed-move' => '[[$1]]କୁ ଘୁଞ୍ଚାଯାଇଅଛି ।
ଏବେ ଏହା [[$2]]କୁ ପୁନପ୍ରେରିତ ହୋଇଥାଏ ।',
'double-redirect-fixed-maintenance' => '[[$1]] ରୁ [[$2]] କୁ ଦୁଇଟି ପୁନପ୍ରେରଣରେ ଥିବା ଅସୁବିଧା ସୁଧାରିଦେଲୁଁ ।',
'double-redirect-fixer' => 'ପୁନପ୍ରେରଣ ସୁଧାରକ',

'brokenredirects' => 'ଭଙ୍ଗା ପୁନପ୍ରେରଣ',
'brokenredirectstext' => 'ତଳଲିଖିତ ପୁନପ୍ରେରଣ ସବୁ ସ୍ଥିତିହିନ ପୃଷ୍ଠାମାନଙ୍କୁ ପୁନପ୍ରେରିତ ହୋଇଥାଏ :',
'brokenredirects-edit' => 'ଏହାକୁ ବଦଳାନ୍ତୁ',
'brokenredirects-delete' => 'ଲିଭାଇବେ',

'withoutinterwiki' => 'ଭାଷାର ଲିଙ୍କ ନଥିବା ପୃଷ୍ଠାମାନ',
'withoutinterwiki-summary' => 'ତଳଲିଖିତ ପୃଷ୍ଠାମାନ ଗୋଟିଏ ବି ଅଲଗା ଭାଷାର ସଂସ୍କରଣ ସହ ଯୋଡ଼ା ନୁହନ୍ତି ।',
'withoutinterwiki-legend' => 'ଆଗରେ ଯୋଡ଼ାହେବା ଶବ୍ଦ',
'withoutinterwiki-submit' => 'ଦେଖାଇବେ',

'fewestrevisions' => 'ସବୁଠାରୁ କମ ସଙ୍କଳନ ଥିବା ପୃଷ୍ଠାସମୂହ',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|ବାଇଟ}}',
'ncategories' => '$1 {{PLURAL:$1|ଶ୍ରେଣୀ|ଶ୍ରେଣୀସମୂହ}}',
'ninterwikis' => '{{PLURAL:$1|interwiki|interwikis}} $1',
'nlinks' => '$1 ଟି {{PLURAL:$1|ଲିଙ୍କ|ଲିଙ୍କ}}',
'nmembers' => '$1 {{PLURAL:$1|member|ସଭ୍ୟ}}',
'nrevisions' => '$1 ଗୋଟି {{PLURAL:$1|ସଂସ୍କରଣ|ସଂସ୍କରଣ}}',
'nviews' => '$1 ଟି {{PLURAL:$1|ଦେଖଣା|ଦେଖଣା}}',
'nimagelinks' => '$1 ଟି {{PLURAL:$1|ପୃଷ୍ଠା|ପୃଷ୍ଠା}}ରେ ବ୍ୟବହାର କରାଯାଇଅଛି',
'ntransclusions' => '$1ଟି {{PLURAL:$1|ପୃଷ୍ଠା|ପୃଷ୍ଠା}}ରେ ବ୍ୟବହାର କରାଯାଇଅଛି',
'specialpage-empty' => 'ଏହି ଅନୁରୋଧ ପାଇଁ କିଛି ଫଳାଫଳ ମିଳିଲା ନାହିଁ ।',
'lonelypages' => 'ଅନାଥ ପୃଷ୍ଠା ସବୁ',
'lonelypagestext' => 'ତଲାଲିଖିତ ପୃଷ୍ଠାମାନ {{SITENAME}}ରେ ଥିବା ବାକି ପୃଷ୍ଠାମାନଙ୍କ ସହ ଯୋଡ଼ାଯାଇନାହିଁ ବା କେବଳ ସେଥିରେ ବ୍ୟବହାର କରାଯାଇନାହିଁ ।',
'uncategorizedpages' => 'ଶ୍ରେଣୀହିନ ପୃଷ୍ଠାସମୂହ',
'uncategorizedcategories' => 'ଶ୍ରେଣୀହିନ ଶ୍ରେଣୀସମୂହ',
'uncategorizedimages' => 'ଶ୍ରେଣୀହୀନ ଫାଇଲସମୂହ',
'uncategorizedtemplates' => 'ଶ୍ରେଣୀହିନ ଛାଞ୍ଚସବୁ',
'unusedcategories' => 'ବ୍ୟବହାର ହେଉନଥିବା ଶ୍ରେଣୀସମୂହ',
'unusedimages' => 'ବ୍ୟବହାର ହେଉନଥିବା ଫାଇଲସମୂହ',
'popularpages' => 'ଜଣାଶୁଣା ପୃଷ୍ଠାସମୂହ',
'wantedcategories' => 'ଦରକାରୀ ଶ୍ରେଣୀସମୂହ',
'wantedpages' => 'ଦରକାରି ପୃଷ୍ଠା',
'wantedpages-badtitle' => '$1 ଉତ୍ତରସବୁରେ ଥିବା ଭୁଲ ଟାଇଟଲ',
'wantedfiles' => 'ଦରକାରି ଫାଇଲ',
'wantedfiletext-cat' => 'ନିମ୍ନଲିଖିତ ଫାଇଲଗୁଡିକ ବ୍ୟବହୃତ ହେଇଛି ହଲେ ନାହିଁ । ରହିଥିବା ଫାଇଲ ବଦଳରେ ବାହାରେ ଥିବା ଫାଇଲଗୁଡିକ ତାଲିକାଭୁକ୍ତ ହେଇଛି । ଏହିଭଳି ଭୁଲ ସତ୍ୟଗୁଡିକ <del>struck out</del> ହେଇଯିବ । ଅଧିକନ୍ତୁ, ପ୍ରକୃତରେ ନଥିବା ଫାଇଲଗୁଡିକର ପୃଷ୍ଠାଗୁଡିକ [[:$1]]ରେ ତାଲିକାଭୁକ୍ତ ହୋଇଛି ।',
'wantedfiletext-nocat' => 'ନିମ୍ନଲିଖିତ ଫାଇଲଗୁଡିକ ବ୍ୟବହୃତ ହେଇଛି ହଲେ ନାହିଁ । ରହିଥିବା ଫାଇଲ ବଦଳରେ ବାହାରେ ଥିବା ଫାଇଲଗୁଡିକ ତାଲିକାଭୁକ୍ତ ହେଇଛି । ଏହିଭଳି ଭୁଲ ସତ୍ୟଗୁଡିକ <del>struck out</del> ହେଇଯିବ ।',
'wantedtemplates' => 'ଦରକାରୀ ଛାଞ୍ଚ',
'mostlinked' => 'ଅଧିକ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠା',
'mostlinkedcategories' => 'ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଶ୍ରେଣୀ',
'mostlinkedtemplates' => 'ବେଶୀ ଯୋଡ଼ାଯାଇଥିବା ଛାଞ୍ଚ',
'mostcategories' => 'ଅଧିକ ଶ୍ରେଣୀ ଥିବା ପୃଷ୍ଠା',
'mostimages' => 'ଫାଇଲରେ ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଥିବା',
'mostinterwikis' => 'ସବୁଠାରୁ ଅଧିକ ଉଇକିଥିବା ପୃଷ୍ଠାଗୁଡିକ',
'mostrevisions' => 'ସବୁଠାରୁ ଅଧିକ ସଙ୍କଳନ ଥିବା ପୃଷ୍ଠାସମୂହ',
'prefixindex' => 'ଆଗରୁ କିଛି ଯୋଡ଼ା ସହ ଥିବା ସବୁ ଫରଦସବୁ',
'prefixindex-namespace' => 'ଉପସର୍ଗ ଲାଗିଥିବା ସବୁଯାକ ପୃଷ୍ଠା ($1 ଗୋଟି ନେମସ୍ପେସ)',
'shortpages' => 'ଛୋଟ ପୃଷ୍ଠାସମୂହ',
'longpages' => 'ଲମ୍ବା ପୃଷ୍ଠା',
'deadendpages' => 'ଆଗକୁ ଯାଇପାରୁନଥିବା ପୃଷ୍ଠା',
'deadendpagestext' => 'ଏହି ପୃଷ୍ଠାସବୁ {{SITENAME}}ର ବାକି ପୃଷ୍ଠାମାନଙ୍କ ସଙ୍ଗେ ଯୋଡ଼ା ହୋଇ ନାହାନ୍ତି ।',
'protectedpages' => 'କିଳାଯାଇଥିବା ପୃଷ୍ଠାମାନ',
'protectedpages-indef' => 'କେବଳ ଆସିମୀତ କାଳ ପାଇଁ କିଳିବା',
'protectedpages-cascade' => 'କିଲାଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ଏକାଠି ସଜାଇ ରଖୁଅଛୁଁ',
'protectedpagestext' => 'ଏହି ତଳଲିଖିତ ପୃଷ୍ଠାମାନ ଘୁଞ୍ଚାଇବାରୁ ବା ସମ୍ପାଦନାରୁ କିଳାଯାଇଅଛି',
'protectedpagesempty' => 'ଏହି ସବୁ ସଜାଣି ସହ ଗୋଟିଏ ବି ପୃଷ୍ଠା ଏବେ କିଳାଯାଇ ନାହିଁ ।',
'protectedtitles' => 'କିଳାଯାଇଥିବା ଶିରୋନାମାମାନ',
'protectedtitlestext' => 'ତଳଲିଖିତ ଶିରୋନାମା ସବୁ ତିଆରି କରିବାରୁ କିଳାଯାଇଅଛି ।',
'protectedtitlesempty' => 'ଏହି ସବୁ ସଜାଣି ସହ ଗୋଟିଏ ବି ପୃଷ୍ଠା ଏବେ କିଳାଯାଇ ନାହିଁ ।',
'listusers' => 'ବ୍ୟବହାରକାରୀଙ୍କ ତାଲିକା',
'listusers-editsonly' => 'କେବଳ କିଛି ସମ୍ପାଦନା କରିଥିବା ସଭ୍ୟମାନଙ୍କୁ ଦେଖାଇବେ',
'listusers-creationsort' => 'ତିଆରି ତାରିଖ ଅନୁସାରେ ସଜାଇବେ',
'usereditcount' => '$1 ଟି {{PLURAL:$1|ବଦଳ}}',
'usercreated' => '$1 ତାରିଖ ଦିନ $2 ବେଳେ {{GENDER:$3|ତିଆରି କରାଗଲା}}',
'newpages' => 'ନୂଆ ପୃଷ୍ଠା',
'newpages-username' => 'ବ୍ୟବହାରକାରୀଙ୍କ ନାମ:',
'ancientpages' => 'ସବୁଠୁ ପୁରାତନ ପୃଷ୍ଠା',
'move' => 'ଘୁଞ୍ଚାଇବେ',
'movethispage' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'unusedimagestext' => 'ତଲାଲିଖିତ ଫାଇଲଟି ଅଛି କିନ୍ତୁ ତାହା ତାହା କୌଣସି ଟି ପୃଷ୍ଠାରେ ଦିଆଯାଇ ନାହିଁ ।
ଦୟାକରି ଜାଣିରଖନ୍ତୁ ଯେ ବାକି ସାଇଟମାନ ଏକ ଫାଇଲ ସହ ସିଧାସଳଖ URL ଦେଇ ଯୋଡ଼ିପାରିବେ, ତେଣୁ ସଚଳ ଭାବେ ବ୍ୟବହାରରେ ନ ଲାଗିଲେ ବି ଏଠାରେ ତାଲିକାଭୁକ୍ତ ହୋଇପାରିବେ ।',
'unusedcategoriestext' => 'ତଳଲିଖିତ ଶ୍ରେଣୀର ପୃଷ୍ଠାସବୁ ରହିଅଛି, ଯଦିଓ ବାକି ସବୁ ପୃଷ୍ଠା ବା ଶ୍ରେଣୀ ତାହା ବ୍ୟବହାର କରନ୍ତି ନାହିଁ ।',
'notargettitle' => 'ଲକ୍ଷହୀନ',
'notargettext' => 'ଆପଣ ଏହି କାମଟି କରିବା ନିମନ୍ତେ ଏକ ନିର୍ଦ୍ଦିଷ୍ଟ ପୃଷ୍ଠା ବା ସଭ୍ଯ ଥୟ କରି ନାହାନ୍ତି ।',
'nopagetitle' => 'ସେହିପରି କିଛି ଲକ୍ଷ ପୃଷ୍ଠା ନାହିଁ',
'nopagetext' => 'ଆପଣ ଦେଇଥିବା ଲକ୍ଷ ପୃଷ୍ଠାଟି ମିଳିଲାନାହିଁ ।',
'pager-newer-n' => '{{PLURAL:$1|ନୂଆ 1|ନୂଆ $1}}',
'pager-older-n' => '{{PLURAL:$1|ପୁରୁଣା 1|ପୁରୁଣା $1}}',
'suppress' => 'ଅଜାଣତ ଅଣଦେଖା',
'querypage-disabled' => 'ଏହି ବିଶେଷ ପୃଷ୍ଠାଟି ଦେଖଣା କାରଣରୁ ଅଚଳ କରାଯାଇଅଛି ।',

# Book sources
'booksources' => 'ବହିର ମୁଳାଧାର',
'booksources-search-legend' => 'ବହିର ସ୍ରୋତସବୁକୁ ଖୋଜିବେ',
'booksources-go' => 'ଯିବା',
'booksources-text' => 'ତଳଲିଖିତ ତାଲିକାଟିରେ ନୂଆ ଓ ପୁରୁଣା ବହି ବିକୁଥିବା ସାଇଟମାନଙ୍କର ତାଲିକା ରହିଅଛି, ଆଉ ଆପଣ ଖୋଜୁଥିବା ବହିର ତଥ୍ୟ ବି ଏଥିରେ ଥାଇପାରେ ।',
'booksources-invalid-isbn' => 'ଏହି ISBN ଟି ବୈଧ ବୋଲି ବୋଧ ହେଉନାହିଁ; ନକଲ କରିଥିବା ମୂଳ ସ୍ଥାନରେ ଆଉଥରେ ପରଖିନିଅନ୍ତୁ ।',

# Special:Log
'specialloguserlabel' => 'ଯୋଗଦାନକାରୀ:',
'speciallogtitlelabel' => 'ଲକ୍ଷ (ଶିରୋନାମା ବା ବ୍ୟବହାରକାରୀ):',
'log' => 'ଲଗସବୁ',
'all-logs-page' => 'ସାଧାରଣ ଲଗସବୁ',
'alllogstext' => ' {{SITENAME}} ସହିତ ଯୋଡ଼ା ମିଳୁଥିବା ଲଗସବୁ ।
ଆପଣ ଲଗର ପ୍ରକାର ଅନୁସାରେ ବି ସେସବୁକୁ ବାଛି ପାରିବେ । ଇଉଜରନାଆଁଟି ଛୋଟ ଓ ବଡ଼ ଅକ୍ଷର ଅନୁସାରେ ଅଲଗା ହୋଇଥାଏ, ପୃଷ୍ଠାର ନାଆଁ ସବୁ ବି ଛୋଟ ଓ ବଡ଼ ଇଂରାଜି ଅକ୍ଷର ଅନୁସାରେ ଅଲଗା ହୋଇଥାଏ ।',
'logempty' => 'ଲଗରେ ଥିବା ଲେଖା ସହ ମେଳଖାଉ ନାହିଁ ।',
'log-title-wildcard' => 'ଏହି ଲେଖାରେ ଆରମ୍ଭ ହୋଇଥିବା ଶିରୋନାମାସବୁ ଖୋଜିବେ',
'showhideselectedlogentries' => 'ବାଛିଥିବା ତାଲିକାକୁ ଦେଖାଇବେ/ଲୁଚାଇବେ',

# Special:AllPages
'allpages' => 'ସବୁ ପୃଷ୍ଠା',
'alphaindexline' => '$1 ରୁ $2',
'nextpage' => 'ପର ପୃଷ୍ଠା ($1)',
'prevpage' => 'ଆଗ ପୃଷ୍ଠା ($1)',
'allpagesfrom' => 'ଏହି ନାଆଁରେ ଆରମ୍ଭ ହେଉଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକୁ ଦେଖାଇବେ:',
'allpagesto' => 'ଏହି ନାଆଁରେ ଶେଷ ହେଉଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକୁ ଦେଖାଇବେ:',
'allarticles' => 'ସବୁ ପୃଷ୍ଠା',
'allinnamespace' => 'ସବୁ ପୃଷ୍ଠା ($1 ନେମସ୍ପେସ)',
'allnotinnamespace' => 'ସବୁ ପୃଷ୍ଠା ($1 ନେମସ୍ପେସରେ ନାହିଁ)',
'allpagesprev' => 'ପୂର୍ବବର୍ତ୍ତୀ',
'allpagesnext' => 'ପର',
'allpagessubmit' => 'ଯିବେ',
'allpagesprefix' => 'ଉପସର୍ଗ ଥିବା ପୃଷ୍ଠାସମୂହର ଦେଖଣା:',
'allpagesbadtitle' => 'ଆପଣ ଅନୁରୋଧ କରିଥିବା ପୃଷ୍ଠାଟି ଭୁଲ, ଅଲଗା ଭାଷାର ବ୍ୟବହାର କରାଯାଇଛି ବା ଭୁଲ ଇଣ୍ଟର ଉଇକି ଉପସର୍ଗ ଦିଆଯାଇଛି ।
ଏଥିରେ ଥିବା ଗୋଟିଏ ବା ଦୁଇଟି ଅକ୍ଷର ଶିରୋନାମା ଭାବରେ ବ୍ୟବହାର କରାଯାଇ ପାରିବ ନାହିଁ ।',
'allpages-bad-ns' => '{{SITENAME}}ରେ "$1" ନେମସ୍ପେସଟିଏ ନାହିଁ ।',
'allpages-hide-redirects' => 'ପୁନଃପ୍ରେରଣସମୂହକୁ ଲୁଚାଇବେ',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'ଆପଣ ଏହି ପୃଷ୍ଠାର ଏକ ପୁରୁଣା ସଂସ୍କରଣ ଦେଖୁଛନ୍ତି, ଯାହାକି $1 ପୁରୁଣା ହୋଇଥାଇପାରେ ।',
'cachedspecial-viewing-cached-ts' => 'ଆପଣ ଏହି ପୃଷ୍ଠାର ଏକ ପୁରୁଣା ସଂସ୍କରଣ ଦେଖୁଛନ୍ତି, ଯାହାକି ପ୍ରକୃତରେ ସଂପୂର୍ଣ ନ ହୋଇଥାଇପାରେ ।',
'cachedspecial-refresh-now' => 'ନୂତନତମ ଦେଖନ୍ତୁ ।',

# Special:Categories
'categories' => 'ଶ୍ରେଣୀସମୂହ',
'categoriespagetext' => 'ତଳଲିଖିତ {{PLURAL:$1|ଶ୍ରେଣୀ|ଶ୍ରେଣୀସମୂହ}}ରେ ପୃଷ୍ଠା ବା ମେଡ଼ିଆ ରହିଅଛି ।
[[Special:UnusedCategories|ବ୍ୟବହାର ହୋଇନଥିବା ଶ୍ରେଣୀସବୁ]] ଦେଖାଯାଇନାହିଁ ।
[[Special:WantedCategories|ଦରକାରୀ ଶ୍ରେଣୀସମୂହ]] ସବୁ ଦେଖନ୍ତୁ ।',
'categoriesfrom' => 'ଏହି ନାମରେ ଆରମ୍ଭ ହେଉଥିବା ଶ୍ରେଣୀଗୁଡ଼ିକୁ ଦେଖାଇବେ:',
'special-categories-sort-count' => 'ଗଣନ କରି ସଜାଇବେ',
'special-categories-sort-abc' => 'ଅକ୍ଷରର କ୍ରମ ଅନୁସାରେ ସଜାଇବେ',

# Special:DeletedContributions
'deletedcontributions' => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ସଭ୍ୟଙ୍କ ଅବଦାନ',
'deletedcontributions-title' => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ସଭ୍ୟଙ୍କ ଅବଦାନସମୂହ',
'sp-deletedcontributions-contribs' => 'ଅବଦାନସମୂହ',

# Special:LinkSearch
'linksearch' => 'ବାହାର ଲିଙ୍କ ଖୋଜା',
'linksearch-pat' => 'ଖୋଜିବା ପ୍ରଣାଳୀ:',
'linksearch-ns' => 'ନେମସ୍ପେସ:',
'linksearch-ok' => 'ଖୋଜିବା',
'linksearch-text' => '"*.wikipedia.org" ପରି ୱାଇଲ୍ଡକାର୍ଡ଼ର ବ୍ୟବହାର କରାଯାଇଥାଇ ପାରେ ।
ଏକ ଉଚ୍ଚକୋଟୀର ଡୋମେନ ଲୋଡ଼ା, ଯଥା "*.org".<br />
ଅନୁମୋଦିତ ପ୍ରଟୋକଲ: <code>$1</code> (ନିଜର ଖୋଜିବାରେ ଏହାକୁ ରଖନ୍ତୁ ନାହିଁ) ।',
'linksearch-line' => '$1 ଟି $2ରୁ ଯୋଡ଼ାଯାଇଅଛି ।',
'linksearch-error' => 'କେବଳ ହୋଷ୍ଟ ନାମର ଆରମ୍ଭରେ ୱାଇଲ୍ଡକାର୍ଡ଼ ଦେଖାଯିବ ।',

# Special:ListUsers
'listusersfrom' => 'ଏହି ନାମରେ ସଭ୍ୟ ଜଙ୍କ ନାମ ଦେଖାଇବେ:',
'listusers-submit' => 'ଦେଖାଇବେ',
'listusers-noresult' => 'ଜଣେ ବି ସଭ୍ୟ ମିଳିଲେ ନାହିଁ ।',
'listusers-blocked' => '(ଅଟକାଯାଇଥିବା)',

# Special:ActiveUsers
'activeusers' => 'ସଚଳ ସଭ୍ୟଙ୍କ ତାଲିକା',
'activeusers-intro' => 'ବିଗତ $1 {{PLURAL:$1|ଦିନ|ଦିନ}} ଭିତରେ କିଛି ପ୍ରକାରର କାମ କରିଥିବା ସଭ୍ୟମାନଙ୍କର ତାଲିକା ।',
'activeusers-count' => 'ବିଗତ {{PLURAL:$3|ଦିନ|$3 ଦିନରେ}}ରେ $1ଟି {{PLURAL:$1|ସମ୍ପାଦନା|ସମ୍ପାଦନା}}',
'activeusers-from' => 'ଏହି ନାମରେ ଆରମ୍ଭ ହେଉଥିବା ସଭ୍ୟମାନଙ୍କୁ ଦେଖାଇବେ:',
'activeusers-hidebots' => 'ଆପେଆପେ ଚାଳିତ ସଭ୍ୟମାନଙ୍କୁ ଲୁଚାନ୍ତୁ',
'activeusers-hidesysops' => 'ପରିଚାଳକମାନଙ୍କୁ ଲୁଚାଇବେ',
'activeusers-noresult' => 'ଜଣେ ବି ସଭ୍ୟ ମିଳିଲେ ନାହିଁ ।',

# Special:Log/newusers
'newuserlogpage' => 'ବ୍ୟବହାରକାରୀ ତିଆରି ଲଗ',
'newuserlogpagetext' => 'ସଭ୍ୟଙ୍କର ଖାତା ଗଠନ ପାଇଁ ଏକ ଇତିହାସ ଅଛି ।',

# Special:ListGroupRights
'listgrouprights' => 'ସଭ୍ୟ ଗୋଠ ଅଧିକାରସମୂହ',
'listgrouprights-summary' => 'ତଳେ ଉଇକି ସ୍ଥିର କରାଯାଇଥିବା ଏକ ଏକ ବ୍ୟବହାରକାରୀ ଗୋଠର ତାଲିକା ଦିଆଯାଇଛି, ସେଥିରେ ସେମାନଙ୍କ ବ୍ୟବହାର ଅଧିକାର ବାବଦରେ ମଧ୍ୟ ଦିଆଯାଇଛି ।
ସେଥିରେ ବୋଧେ [[{{MediaWiki:Listgrouprights-helppage}}|ଅଧିକ ବ୍ୟକ୍ତିଗତ ବିବରଣୀ ଥାଇପାରେ]] ।',
'listgrouprights-key' => '* <span class="listgrouprights-granted">ଅନୁମୋଦିତ ଅଧିକାର</span>
* <span class="listgrouprights-revoked">ଫେରାଇ ନିଆଯାଇଥିବା ଅଧିକାର</span>',
'listgrouprights-group' => 'ଗୋଠ',
'listgrouprights-rights' => 'ଅଧିକାର',
'listgrouprights-helppage' => 'Help:ଗୋଠ ଅଧିକାର',
'listgrouprights-members' => '(ସଭ୍ୟମାନଙ୍କର ତାଲିକା)',
'listgrouprights-addgroup' => '{{PLURAL:$2|ଗୋଠ|ଗୋଠସମୂହ}} ଯୋଡ଼ିବେ: $1',
'listgrouprights-removegroup' => '{{PLURAL:$2|ଗୋଠ|ଗୋଠସମୂହ}} ହଟାଇବେ: $1',
'listgrouprights-addgroup-all' => 'ସବୁଯାକ ଗୋଠ ଯୋଡ଼ିବେ',
'listgrouprights-removegroup-all' => 'ଗୋଠସବୁ କାଢ଼ିଦେବେ',
'listgrouprights-addgroup-self' => '{{PLURAL:$2|ଗୋଠଟିଏ|ଗୋଟି ଗୋଠ}} ନିଜ ଖାତାରେ ଯୋଡ଼ିବେ: $1',
'listgrouprights-removegroup-self' => '{{PLURAL:$2|ଗୋଠଟିଏ|ଗୋଟି ଗୋଠ}} ନିଜ ଖାତାରୁ ହଟାଇବେ: $1',
'listgrouprights-addgroup-self-all' => 'ନିଜ ଖାତାରେ ସବୁଯାକ ଗୋଠ ଯୋଡ଼ିବେ',
'listgrouprights-removegroup-self-all' => 'ନିଜ ଖାତାରୁ ସବୁଯାକ ଗୋଠ ହଟାଇଦେବେ',

# E-mail user
'mailnologin' => 'ଗୋଟିଏ ବି ପଠାଇବା ଠିକଣା ନାହିଁ',
'mailnologintext' => 'ଆପଣ ନିଜ [[Special:Preferences|ପସନ୍ଦସବୁ]]ରେ [[Special:UserLogin|ଲଗ ଇନ]] କରିଥିଲେ ଓ ନିଜର ଏକ ସଚଳ ଇ-ମେଲ ଠିକଣା ଥିଲେ ଯାଇ ବାକି ସବୁ ସଭ୍ୟଙ୍କୁ ଇ-ମେଲ ପଠାଇପାରିବେ ।',
'emailuser' => 'ଏହି ସଭ୍ୟଙ୍କୁ ଇମେଲ କରିବେ',
'emailuser-title-target' => '{{GENDER:$1|user}}କୁ ଇ-ମେଲ କରନ୍ତୁ',
'emailuser-title-notarget' => 'ବ୍ୟବହାରକାରୀ କୁ ଇ-ମେଲ',
'emailpage' => 'ଇ-ମେଲ ବ୍ୟବହାରକାରୀ',
'emailpagetext' => 'ଥିବା ଫର୍ମ ବ୍ୟବହାର କରି ଆପଣ ଏହି {{GENDER:$1|user}} ଇ-ମେଲ କରିପାରିବେ ।
[[Special:Preferences|ଆପଣଙ୍କ ପସନ୍ଦ]]ରେ ଥିବା ଇ-ମେଲ ଠିକଣା ପ୍ରେରକ ଭାବରେ ଦେଖାଯିବ, ତେଣୁ ଚିଠି ପାଇଥିବା ସଭ୍ୟ ଆପଣଙ୍କୁ ସିଧା ସଳଖ ଉତ୍ତର ଦେଇପାରିବ ।',
'usermailererror' => 'ମେଲ ଭିତରେ କିଛି ଅସୁବିଧା ଅଛି ବୋଲି ଜାଣିବାକୁ ମିଳିଲା:',
'defemailsubject' => '{{SITENAME}} "$1" ସଭ୍ୟଙ୍କ ଠାରୁ ଇ-ମେଲ କରିବେ',
'usermaildisabled' => 'ବ୍ୟବହାରକାରୀଙ୍କ ଈ-ମେଲ ଅଚଳ କରାଗଲା',
'usermaildisabledtext' => 'ଏହି ଉଇକିରେ ଆପଣ ବାକି ସଭ୍ୟଙ୍କୁ ଇ ମେଲ କରିପାରିବେ ନାହିଁ',
'noemailtitle' => 'ଇ-ମେଲ ଠିକଣା ନାହିଁ',
'noemailtext' => 'ସଭ୍ୟଜଣକ ଏକ ସଠିକ ଇ-ମେଲ ଠିକଣା ଦେଇନାହାନ୍ତି ।',
'nowikiemailtitle' => 'ଇ-ମେଲ ଦେବା ଅନୁମୋଦିତ ନୁହେଁ',
'nowikiemailtext' => 'ଏହି ସଭ୍ୟ ବାକିମାନଙ୍କ ଠାରୁ ଇ-ମେଲ ପାଇବା ବାଚିଛନ୍ତି ।',
'emailnotarget' => 'ପ୍ରାପକ ନିମନ୍ତେ ସ୍ଥିତିହୀନ  ବା ଅବୈଧ ଇଉଜର ନାମ ।',
'emailtarget' => 'ପ୍ରାପକର ଇଉଜର ନାମ ଦିଅନ୍ତୁ',
'emailusername' => 'ବ୍ୟବହାରକାରୀଙ୍କ ନାମ:',
'emailusernamesubmit' => 'ଦାଖଲକରିବା',
'email-legend' => 'ଆଉ ଏକ {{SITENAME}}କୁ ଇ-ମେଲଟିଏ ପଠାଇବେ',
'emailfrom' => 'କାହାଠାରୁ:',
'emailto' => 'କାହାକୁ:',
'emailsubject' => 'ବିଷୟ:',
'emailmessage' => 'ଖବର:',
'emailsend' => 'ପଠାଇବେ',
'emailccme' => 'ଏହି ସନ୍ଦେଶର ଏକ ନକଲ ମୋତେ ଇ-ମେଲ କରି ପଠାଇବେ ।',
'emailccsubject' => ' $1ଙ୍କୁ ପଠାଯାଇଥିବା ଆପଣଙ୍କ ସନ୍ଦେଶର ନକଲ: $2',
'emailsent' => 'ଇ-ମେଲ ପଠାଗଲା',
'emailsenttext' => 'ଆପଣଙ୍କ ଇ-ମେଲ ସନ୍ଦେଶଟି ପଠାଗଲା ।',
'emailuserfooter' => 'ଏହି ଇ-ମେଲଟି $1ଙ୍କ ଦେଇ $2ଙ୍କୁ "ସଭ୍ୟଙ୍କୁ ଇ-ମେଲ" ସୁବିଧାରେ {{SITENAME}}ରେ ପଠାଗଲା ।',

# User Messenger
'usermessage-summary' => 'ସିଷ୍ଟମ ମେସେଜକୁ ଛାଡୁଛି ।',
'usermessage-editor' => 'ସିଷ୍ଟମ ଦୂତ',

# Watchlist
'watchlist' => 'ଦେଖାତାଲିକା',
'mywatchlist' => 'ଦେଖଣାତାଲିକା',
'watchlistfor2' => '$1 $2 ପାଇଁ',
'nowatchlist' => 'ଆପଣଙ୍କ ଦେଖଣା ତାଲିକାରେ କିଛି ବି ଜିନିଷ ନାହିଁ ।',
'watchlistanontext' => 'ଆପଣା ଦେଖଣାତାଲିକାରେ କିଛି ସମ୍ପାଦନା କରିବା ନିମନ୍ତେ ଦୟାକରି  $1 କରନ୍ତୁ ।',
'watchnologin' => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
'watchnologintext' => 'ଆପଣା ଦେଖଣାତାଲିକା ବଦଳାଇବା ନିମନ୍ତେ ଆପଣଙ୍କୁ [[Special:UserLogin|ଲଗ ଇନ]] କରିବାକୁ ପଡ଼ିବ ।',
'addwatch' => 'ଦେଖଣାତାଲିକାରେ ଯୋଡ଼ିବେ',
'addedwatchtext' => '"[[:$1]]" ପୃଷ୍ଠାଟି ଆପଣଙ୍କ [[Special:Watchlist|ଦେଖଣାତାଲିକା]]ରେ ଯୋଡ଼ିଦିଆଗଲା ।
ଏହି ପୃଷ୍ଠାରେ ଭବିଷ୍ୟତର ଅଦଳ ବଦଳ ଓ ତାହା ସହ ଯୋଡ଼ା ଆଲୋଚନା ପୃଷ୍ଠା ସେଠାରେ ଦିଆଯିବ ।',
'removewatch' => 'ଦେଖଣା ତାଲିକାରୁ ହଟାଇବେ',
'removedwatchtext' => '"[[:$1]]" ପୃଷ୍ଠାଟି [[Special:Watchlist|ଆପଣଙ୍କ ଦେଖଣାତାଳିକା]]ରୁ ହଟାଗଲା ।',
'watch' => 'ଦେଖିବେ',
'watchthispage' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଦେଖିବେ',
'unwatch' => 'ଦେଖନାହିଁ',
'unwatchthispage' => 'ଦେଖନ୍ତୁ ନାହିଁ',
'notanarticle' => 'ବିଷୟବସ୍ତୁର ପୃଷ୍ଠାଟିଏ ନୁହେଁ',
'notvisiblerev' => 'ଜଣେ ଅଲଗା ବ୍ୟବହାରକାରୀଙ୍କ ଦେଇ କରାଯାଇଥିବା ସେଶ ସଂସ୍କରଣଟି ଲିଭାଇଦିଆଗଲା ।',
'watchnochange' => 'ଆପଣଙ୍କ ଦେଇ ଦେଖାଯାଇଥିବା ସମୟ ସୀମା ଭିତରେ କୌଣସିଟି ପୃଷ୍ଠାର ବଦଳ କରାଯାଇନାହିଁ ।',
'watchlist-details' => 'ଆପଣଙ୍କ ଦେଖଣା ତାଲିକାରେ ଆଲୋଚନା ପୃଷ୍ଠାକୁ ଛାଡ଼ି {{PLURAL:$1|$1 ଟି ପୃଷ୍ଠା|$1 ଟି ପୃଷ୍ଠା}} ଅଛି ।',
'wlheader-enotif' => '* ଇ-ମେଲ ସୂଚନାକୁ ସଚଳ କରାଗଲା ।',
'wlheader-showupdated' => "* ଆପଣ ଶେଷଥର ଦେଖିଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକ '''ମୋଟା ଅକ୍ଷର'''ରେ ଦେଖାଯାଇଅଛି ।",
'watchmethod-recent' => 'ଏଡଖାଯାଇଥିବା ପୃଷ୍ଠାର ନଗଦ ବଦଳ ପରଖୁଛୁଁ',
'watchmethod-list' => 'ନଗଦ ବଦଳ ନିମନ୍ତେ ଦେଖାଯାଇଥିବା ପୃଷ୍ଠାମାନ ପରଖୁଛୁଁ',
'watchlistcontains' => 'ଆପଣଙ୍କ ଦେଖଣାତାଲିକାରେ $1 {{PLURAL:$1|ଗୋଟି ପୃଷ୍ଠା|ଗୋଟି ପୃଷ୍ଠା}} ରହିଅଛି ।',
'iteminvalidname' => "'$1' ଯୋଗୁଁ କିଛି ଅସୁବିଧା ହେଉଅଛି, ଭୁଲ ନାମ...",
'wlnote' => "$3, $4 ସୁଦ୍ଧା ବିଗତ {{PLURAL:$2|ଘଣ୍ଟେ ଭିତରେ|'''$2''' ଘଣ୍ଟା ଭିତରେ}} ଘଟିଥିବା {{PLURAL:$1|ଶେଷ ବଦଳଟି ଅଛି|ଶେଷ '''$1''' ଟି ବଦଳ}} ତଳେ ଦିଆଯାଇଛି ।",
'wlshowlast' => 'ଶେଷ $1 ଘଣ୍ଟା $2 ଦିନ $3 ଦେଖାଇବେ',
'watchlist-options' => 'ଦେଖଣା ବିକଳ୍ପସବୁ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'ଦେଖୁଛି...',
'unwatching' => 'ଦେଖୁନାହିଁ...',
'watcherrortext' => '"$1" ପାଇଁ ଆପଣଙ୍କ ଦେଖଣାତାଲିକା ସଜାଣି ବଦଳାଇବା ବେଳେ ଏକ ଅସୁବିଧା ହେଲା ।',

'enotif_mailer' => '{{SITENAME}} ସୂଚନା ମେଲ ପ୍ରେରକ',
'enotif_reset' => 'ସବୁଯାକ ଦେଖାଯାଇଥିବା ପୃଷ୍ଠାକୁ ଚିହ୍ନିତ କରିବେ',
'enotif_newpagetext' => 'ଏହା ଏକ ନୂଆ ପୃଷ୍ଠା ।',
'enotif_impersonal_salutation' => '{{SITENAME}} ବ୍ୟବହାରକାରୀ',
'changed' => 'ବଦଳାଗଲା',
'created' => 'ତିଆରି କରାଗଲା',
'enotif_subject' => ' $PAGEEDITORଙ୍କ ଦେଇ {{SITENAME}} ପୃଷ୍ଠାଟି $PAGETITLE  $CHANGEDORCREATED',
'enotif_lastvisited' => 'ଆପଣଙ୍କ ଶେଷ ଦେଖଣା ପରେ ହୋଇଥିବା ବଦଳସବୁକୁ  ଦେଖିବା ନିମନ୍ତେ $1 ଦେଖନ୍ତୁ ।',
'enotif_lastdiff' => 'ଏହି ବଦଳ ଦେଖିବା ପାଇଁ $1 ଦେଖନ୍ତୁ ।',
'enotif_anon_editor' => 'ବେନାମି ସଭ୍ୟ $1',
'enotif_body' => 'ପ୍ରିୟ $WATCHINGUSERNAME,


ଏହି {{SITENAME}} $PAGETITLE ପୃଷ୍ଠାଟି $PAGEEDITOR ଙ୍କ ଦେଇ $PAGEEDITDATE ବେଳେ $CHANGEDORCREATE, ନଗଦ ସଂସ୍କରଣ ପାଇଁ $PAGETITLE_URL  ଦେଖନ୍ତୁ ।

$NEWPAGE

ସମ୍ପାଦକଙ୍କ ସାରକଥା: $PAGESUMMARY $PAGEMINOREDIT

ସମ୍ପାଦକଙ୍କ ସହିତ ଯୋଗାଯୋଗ:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

ଆପଣ ପୃଷ୍ଠାଟିକୁ ଯାଇ ନ ଦେଖିଲେ କିଛି ବି ସୂଚନା ରହିବ ନାହିଁ ।
ଆପଣା ଦେଖଣାତାଲିକାରୁ ଆପଣ ସବୁଯାକ ସୂଚନା ଫଳକକୁ ମୂଳ ଅବସ୍ଥାକୁ ଫେରାଇ ଦେଇପାରିବେ ।

			 ଆପଣଙ୍କର ହିତକାରୀ {{SITENAME}} ସୂଚନା ପ୍ରଣାଳୀ

--
ଆପଣା ଇ-ମେଲ ସୂଚନା ସଜାଣି ଦେଖିବା ନିମନ୍ତେ
{{canonicalurl:{{#special:Preferences}}}} ଦେଖନ୍ତୁ

ଆପଣା ଦେଖଣାତାଲିକା ସଜାଣି ବଦଳାଇବା ନିମନ୍ତେ,
{{canonicalurl:{{#special:EditWatchlist}}}} ଦେଖନ୍ତୁ

ଆପଣା ଦେଖଣାତାଲିକାରୁ ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇବା ନିମନ୍ତେ,
$UNWATCHURL ଦେଖନ୍ତୁ

ମତାମତ ଓ ଅଧିକ ସହଯୋଗ:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'ପୃଷ୍ଠାଟି ଲିଭାଇଦେବେ',
'confirm' => 'ନିଶ୍ଚିତ କରନ୍ତୁ',
'excontent' => 'ଭିତର ଭାଗ ଥିଲା: $1',
'excontentauthor' => 'ଭିତର ଭାଗରେ ଥିଲା: "$1" (ଆଉ "[[Special:Contributions/$2|$2]]" କେବଳ ଜଣେ ମାତ୍ର ଦାତା ଥିଲେ)',
'exbeforeblank' => 'ଖାଲି କରିବା ଆଗରୁ ଭିତରେ "$1" ଥିଲା',
'exblank' => 'ପୃଷ୍ଠାଟି ଖାଲି ଅଛି',
'delete-confirm' => 'ଲିଭେଇବେ $1',
'delete-legend' => 'ଲିଭାଇବେ',
'historywarning' => "'''ଚେତାବନୀ:''' ଆପଣ ଲିଭାଇବାକୁ ଯାଉଥିବା ଏହି ପୃଷ୍ଠାଟିର ପାଖାପାଖି $1 {{PLURAL:$1|ଟି ସଙ୍କଳନ|ଗୋଟି ସଙ୍କଳନ}} ରହିଅଛି:",
'confirmdeletetext' => 'ଆପଣ ଗୋଟିଏ ପୃଷ୍ଠାର ଇତିହାସ ସହ ତାହାକୁ ଲିଭାଇବାକୁ ଯାଉଛନ୍ତି ।
ଏହା ଥୟ କରନ୍ତୁ ଯେ ଆପଣ ଏହାର ପରିଣତି ଜାଣିଛନ୍ତି ଓ ଏହା [[{{MediaWiki:Policy-url}}|ମିଡ଼ିଆଉଇକିର ନିୟମ]] ଅନୁସାରେ କରୁଛନ୍ତି ।',
'actioncomplete' => 'କାମଟି ପୁରା ହେଲା',
'actionfailed' => 'କାମଟି ଅସଫଳ ହୋଇଗଲା',
'deletedtext' => '"$1"କୁ ଲିଭାଇ ଦିଆଗଲା ।
ନଗଦ ଲିଭାଯାଇଥିବା ଫାଇଲର ଇତିହାସ $2ରେ ଦେଖନ୍ତୁ ।',
'dellogpage' => 'ଲିଭାଇବା ଇତିହାସ',
'dellogpagetext' => 'ତଳେ ନଗଦ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାମାନଙ୍କର ତାଲିକା ରହିଅଛି ।',
'deletionlog' => 'ଲିଭାଇବା ଇତିହାସ',
'reverted' => 'ପୁରୁଣା ସଂସ୍କରଣକୁ ଫେରାଇଦିଆଗଲା',
'deletecomment' => 'କାରଣ:',
'deleteotherreason' => 'ବାକି/ଅଧିକ କାରଣ:',
'deletereasonotherlist' => 'ଅଲଗା କାରଣ',
'deletereason-dropdown' => '*ସାଧାରଣ ଲିଭାଇବା କାରଣ
** ଲେଖକ ଅନୁରୋଧ
** ସତ୍ଵାଧିକାର ଉଲଂଘନ
** ଅନୀତିକର କାମ',
'delete-edit-reasonlist' => 'ଲିଭାଇବା କାରଣମାନ ବଦଳାଇବେ',
'delete-toobig' => 'ଏହି ପୃଷ୍ଠାର ଏକ ଲମ୍ବା ସମ୍ପାଦନା ଇତିହାସ ଅଛି, ଯେଉଁଥିରେ $1  {{PLURAL:$1|ଟି ସଂସ୍କରଣ|ଗୋଟି ସଂସ୍କରଣ}} ରହିଛି ।
{{SITENAME}}ରେ ଦୁର୍ଘଟଣାବଶତ ଅସୁବିଧାକୁ ଏଡ଼ାଇବା ପାଇଁ ଏହାକୁ ଲିଭାଇବାରୁ ବାରଣ କରାଯାଇଛି ।',
'delete-warning-toobig' => 'ଏହି ପୃଷ୍ଠାର ଏକ ଲମ୍ବ ସମ୍ପାଦନ ଇତିହାସ ରହିଛି, ଯେଉଁଥିରେ $1 {{PLURAL:$1|ଗୋଟି ସଂସ୍କରଣ|ଗୋଟି ସଂସ୍କରଣ}} ରହିଛି ।
ଏହାକୁ ଲିଭାଇଲେ {{SITENAME}}ରେ ଅସୁବିଧା ହୋଇପାରେ ।
ସାବଧାନତାର ସହ ଆଗକୁ ବଢ଼ନ୍ତୁ ।',

# Rollback
'rollback' => 'ପୁରାପୁରି ପଛକୁ ଫେରିବା ବଦଳ',
'rollback_short' => 'ପୁରାପୁରି ପଛକୁ ଫେରିଯିବେ',
'rollbacklink' => 'ପୁରାପୁରି ପଛକୁ ଫେରିଯିବେ',
'rollbacklinkcount' => '{{PLURAL:$1|edit|edits}} $1 ପଛକୁ ଫେରାଇବେ',
'rollbacklinkcount-morethan' => '{{PLURAL:$1|edit|edits}} $1ରୁ ଅଧିକ ପଛକୁ ଫେରାଇବେ',
'rollbackfailed' => 'ପୁରାପୁରି ପଛକୁ ଫେରିବା ବିଫଳ ହେଲା',
'cantrollback' => 'ବଦଳକୁ ପଛକୁ ଫେରାଇ ପାରିବେ ନାହିଁ;
ଶେଷ ଦାତାଜଣଙ୍କ ଏହି ପୃଷ୍ଠାର ଜଣେମାତ୍ର ଦାତା ।',
'alreadyrolled' => '[[User:$2|$2]]([[User talk:$2|talk]] {{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]])ଙ୍କ ଦେଇ [[:$1]]ରେ ହୋଇଥିବା ଶେଷ ବଦଳକୁ ପଛକୁ ଫେରାଯାଇପାରିବ ନାହିଁ ;
ଏହାକୁ ଆଉ କେହି ସମ୍ପାଦନା କରିଛି ବା ପୁରାପୁରି ପଛକୁ ଫେରାଇଦେଇଛି ।

ଏହି ପୃଷ୍ଠାର ଶେଷ  ସମ୍ପାଦନା  [[User:$3|$3]] ([[User talk:$3|talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) ଙ୍କ ଦେଇ ହୋଇଥିଲା ।',
'editcomment' => "ସମ୍ପାଦନାର ସାରକଥା ଥିଲା: \"''\$1''\" ।",
'revertpage' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|ଆଲୋଚନା]])ଙ୍କ ଦେଇ କରାଯାଇଥିବା ବଦଳକୁ [[User:$1|$1]]ଙ୍କ ଦେଇ କରାଯାଇଥିବା ଶେଷ ବଦଳକୁ ଫେରାଇ ଦିଆଗଲା',
'revertpage-nouser' => '(ଇଉଜର ନାମ ବାହାର କରିଦିଆଯାଇଅଛି)ଙ୍କ ଦେଇ କରାଯାଇଥିବା ବଦଳକୁ [[User:$1|$1]]ଙ୍କ ଦେଇ କରାଯାଇଥିବା ଶେଷ ବଦଳକୁ ଲେଉଟାଇଦିଆଗଲା',
'rollback-success' => '$1ଙ୍କ ଦେଇ ହୋଇଥିବା ସମ୍ପାଦନାକୁ ପୁରାପୁରି ପଛକୁ ଲେଉଟାଇ ଦିଆଗଲା;
$2ଙ୍କ ଦେଇ ଶେଷଥର ହୋଇଥିବା ସଂସ୍କରଣକୁ ବଦଳାଇ ଦିଆଗଲା ।',

# Edit tokens
'sessionfailure-title' => 'ଅବଧି ବିଫଳ',
'sessionfailure' => 'ଆପଣଙ୍କ ଲଗଇନ ଅବଧିରେ କିଛି ଅସୁବିଧା ହୋଇଛି;
ଅବଶ୍ୟ ଚୋରା ବିରୋଧରେ ଏହି କାମଟି ନାକଚ କରିଦିଆଗଲା ।
ଆଗ ପୃଷ୍ଠାକୁ ଲେଉଟିଯାଇ ପୃଷ୍ଠାଟି ଆଉଥରେ ଲୋଡ଼ କରନ୍ତୁ ।',

# Protect
'protectlogpage' => 'କିଳିବା ଲଗ',
'protectlogtext' => 'ତଳେ ପୃଷ୍ଠା ସଂରକ୍ଷଣ ପାଇଁ  ସମ୍ପାଦନାର ତାଲିକାଟିଏ ଦିଆଗଲା ।
ଏବେ ସଚଳ ଥିବା ପୃଷ୍ଠାର ସଂରକ୍ଷଣ ପାଇଁ [[Special:ProtectedPages|ସଂରକ୍ଷିତ ପୃଷ୍ଠା  ତାଲିକା]]ଟି ଦେଖନ୍ତୁ ।',
'protectedarticle' => '"[[$1]]"ଟି କିଳାଗଲା',
'modifiedarticleprotection' => '"[[$1]]" ପାଇଁ ସୁରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇବେ',
'unprotectedarticle' => '"[[$1]]"କୁ କିଳାଯିବାରୁ ବ୍ନାହାର କରାଗଲା',
'movedarticleprotection' => 'ସୁରକ୍ଷା ସ୍ତରକୁ "[[$2]]" ରୁ "[[$1]]"କୁ ଘୁଞ୍ଚାଇଦିଆଗଲା',
'protect-title' => '"$1" ପାଇଁ ସୁରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇବେ',
'protect-title-notallowed' => '"$1" ତମ ପ୍ରତିରକ୍ଷା ସ୍ତର ଦେଖିବେ',
'prot_1movedto2' => '[[$1]] ପୃଷ୍ଠାଟି [[$2]] କୁ ଘୁଞ୍ଚାଇ ଦିଆଗଲା',
'protect-badnamespace-title' => 'କିଳାହୋଇନଥିବା ନେମସ୍ପେସ',
'protect-badnamespace-text' => 'ଏହି ନେମସ୍ପେସଥିବା ପୃଷ୍ଠାସବୁକୁ ସାଇତାଯାଇପାରିବ ନାହିଁ ।',
'protect-legend' => 'କିଳଣାକୁ ଥୟ କରିବେ',
'protectcomment' => 'କାରଣ:',
'protectexpiry' => 'ଅଚଳ ହେବ:',
'protect_expiry_invalid' => 'ଅଚଳ ହେବାର ବେଳା ଭୁଲ ।',
'protect_expiry_old' => 'ଅଚଳ ହେବାର ବେଳ ଅତୀତରେ ଥିଲା.',
'protect-unchain-permissions' => 'ଭବିଷ୍ୟତର ପ୍ରତିରକ୍ଷା ବିକଳ୍ପକୁ କିଳନ୍ତୁନାହିଁ',
'protect-text' => "ଆପଣ '''$1''' ପୃଷ୍ଠାର ପ୍ରତିରକ୍ଷା ସ୍ତର ଦେଖି ପାରିବେ ଓ ବଦଳାଇ ପାରିବେ ।",
'protect-locked-blocked' => "ଆପଣଙ୍କୁ ଅଟକାଯାଇଥିବାରୁ ଆପଣ ପ୍ରତିରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇ ପାରିବେ ନାହିଁ ।
'''$1''' ପୃଷ୍ଠା ପାଇଁ ଏବେକାର ସଜାଣି ଦେଖନ୍ତୁ:",
'protect-locked-dblock' => "ଏକ ସଚଳ ଡାଟାବେସ କିଳାଯାଇଥିବା ହେତୁ ପ୍ରତିରକ୍ଷା ସ୍ତରକୁ ବଦଳଯାଇପାରିବ ନାହିଁ ।
'''$1''' ପୃଷ୍ଠା ପାଇଁ ଏବେକାର ସଜାଣି ଏଠାରେ ଦିଆଗଲା:",
'protect-locked-access' => "ଆପଣଙ୍କ ଖାତାରେ ପ୍ରତିରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇବା ନିମନ୍ତେ ଅନୁମତି ନାହିଁ ।
'''$1''' ପୃଷ୍ଠା ପାଇଁ ଏବେକାର ସଜାଣି ଏଠାରେ ଦିଆଗଲା:",
'protect-cascadeon' => 'ଏହି ପୃଷ୍ଠାଟି ଏବେ ପାଇଁ କିଳାଯାଇଛି {{PLURAL:$1|ପୃଷ୍ଠା, ଯେଉଁଥିରେ|ପୃଷ୍ଠମାନ, ଯେଉଁସବୁରେ}} କାସକେଡ଼କରା ସୁରକ୍ଷା ସଚଳ ଥିଲା ।
ଆପଣ ପୃଷ୍ଠାଟିର ପ୍ରତିରକ୍ଷା ସ୍ତର ବଦଳାଇ ପାରିବେ, କିନ୍ତୁ ଏହା କାସକେଡ଼ ପ୍ରତିରକ୍ଷାକୁ ପ୍ରଭାବିତ କରିନଥାଏ ।',
'protect-default' => 'ସବୁ ଇଉଜରଙ୍କୁ ଅନୁମତି ଦିଅନ୍ତୁ',
'protect-fallback' => '"$1" ବାଲା ଅନୁମତି ଦରକାର',
'protect-level-autoconfirmed' => 'ନୁଆ ଓ ନାଆଁ ଲେଖାଇ ନ ଥିବା ଇଉଜରମାନକୁ ଅଟକାଁତୁ',
'protect-level-sysop' => 'କେବଳ ପରିଛାମାନଁକ ପାଇଁ',
'protect-summary-cascade' => 'କାସକେଡ଼ ହୋଇଥିବା',
'protect-expiring' => '$1 (ଇଉଟିସି)ରେ ଅଚଳ ହୋଇଯିବ',
'protect-expiring-local' => '$1ରେ ଅଚଳ ହୋଇଯିବ',
'protect-expiry-indefinite' => 'ଅସିମୀତ',
'protect-cascade' => 'ଏହି ଫରଦସହ ଜୋଡ଼ା ଫରଦସବୁକୁ କିଳିଦିଅ (କାସକେଡ଼କରା ସୁରକ୍ଷା)',
'protect-cantedit' => 'ଆପଣ ଏହି ସୁରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇ ପାରିବେ ନାହିଁ, କାହିଁକି ନା ଏହାକୁ ବଦଳାଇବା ପାଇଁ ଆପଣଁକୁ ଅନୁମତି ନାହିଁ .',
'protect-othertime' => 'ବାକି ସମୟ:',
'protect-othertime-op' => 'ବାକି ସମୟ',
'protect-existing-expiry' => 'ମିଆଦ ପୁରିବା କାଳ: $3, $2',
'protect-otherreason' => 'ବାକି/ଅଧିକ କାରଣ:',
'protect-otherreason-op' => 'ଅଲଗା କାରଣ',
'protect-dropdown' => '*ସାଧାରଣ ପ୍ରତିରକ୍ଷା କାରଣ
** ଅତି ଅଧିକ ଅପବ୍ୟବହାର
** ଅତି ଅଧିକ ଅଦରକାରୀ ଚିଜ ପୁରାଇବା
** ନକରାତ୍ମକ ସମ୍ପାଦନା ତାଗିଦା
** ଅଧିକ ଦେଖାଯାଉଥିବା ପୃଷ୍ଠା',
'protect-edit-reasonlist' => 'କିଳିବା କାରଣମାନଙ୍କର ସମ୍ପାଦନା କରିବେ',
'protect-expiry-options' => '୧ ଘଣ୍ଟା:1 hour,ଦିନେ:1 day,ସପ୍ତାହେ:1 week,୨ ସପ୍ତାହ:2 weeks,ମସେ:1 month,୩ ମାସ:3 months,୬ ମାସ:6 months,ବର୍ଷେ:1 year,ଅସିମୀତ କାଳ:infinite',
'restriction-type' => 'ଅନୁମତି',
'restriction-level' => 'ପ୍ରତିରକ୍ଷା ସ୍ତର',
'minimum-size' => 'ସବୁଠୁ ସାନ',
'maximum-size' => 'ସବୁଠୁ ବଡ଼ ଆକାର:',
'pagesize' => '(ବାଇଟ)',

# Restrictions (nouns)
'restriction-edit' => 'ଏହାକୁ ବଦଳାନ୍ତୁ',
'restriction-move' => 'ଘୁଞ୍ଚାଇବେ',
'restriction-create' => 'ଗଢ଼ନ୍ତୁ',
'restriction-upload' => 'ଅପଲୋଡ଼ କରନ୍ତୁ',

# Restriction levels
'restriction-level-sysop' => 'ପୁରାପୁରି କିଳାଯାଇଥିବା',
'restriction-level-autoconfirmed' => 'ଅଧା କିଳାଯାଇଥିବା',
'restriction-level-all' => 'ଯେକୌଣସି ସ୍ତର',

# Undelete
'undelete' => 'ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସମୂହ',
'undeletepage' => 'ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସବୁ ଦେଖିବେ ଓ ପୁନସ୍ଥାପନ କରିବେ',
'undeletepagetitle' => "'''ତଳେ [[:$1|$1]]ର ଲିଭାଯାଇଥିବା ସଂସ୍କରଣ ରହିଛି''' ।",
'viewdeletedpage' => 'ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସମୂହ',
'undeletepagetext' => 'ତଳଲିଖିତ {{PLURAL:$1|ପୃଷ୍ଠାଟି ଲିଭାଇ ଦିଆଯାଇଛି କିନ୍ତୁ|$1 ଗୋଟି ପୃଷ୍ଠା ଲିଭାଇ ଦିଆଯାଇଛି କିନ୍ତୁ}} ସେସବୁ ଏବେ ଯାଏଁ ଅଭିଲେଖରେ ରହିଛନ୍ତି ଓ ସେମାନଙ୍କୁ ଆଉଥରେ ଲେଉଟାଇ ଦିଆଯାଇ ପାରିବ ।
ଅଭିଲେଖକୁ ବୋଧେ ସମୟାନୁକ୍ରମେ ସଫା କରାଯାଉଥିବ ।',
'undelete-fieldset-title' => 'ସଂସ୍କରଣ ଆଉଥରେ ଫେରାଇଆଣିବେ',
'undeleteextrahelp' => "ପୃଷ୍ଠାଟିର ସବୁଯାକ ଇତିହାସ ଫେରାଇଆଣିବା ନିମନ୍ତେ ଚାରିକୋଣିଆ ବାଛିବା ଘରକୁ ଖାଲି ଛାଡ଼ିଦେଇ '''''{{int:undeletebtn}}'''''ରେ କ୍ଲିକ କରିବେ ।
ଠିକ ଭାବରେ ପଛକୁ ଫେରାଇବା ନିମନ୍ତେ ପଛକୁ ଫେରାଯିବା ସଂସ୍କରଣର ଘରକୁ ବାଛି '''''{{int:undeletebtn}}'''''ରେ କ୍ଲିକ କରିବେ ।",
'undeleterevisions' => '$1 {{PLURAL:$1|ସଂସ୍କରଣ|ସଂସ୍କରଣସମୂହ}} ସାଇତାଗଲା',
'undeletehistory' => 'ଯଦି ଆପଣ ଏହି ପୃଷ୍ଠାକୁ ପୁନସ୍ଥାପନ କରନ୍ତି ତେବେ ସବୁଯାକ ସଂସ୍କରଣ ଇତିହାସରେ ସାଇତା ହୋଇ ରହିବା ।
ଯଦି ଆଗରୁ ଲିଭାଯାଇଥିବା ନାମରେ ନୂଆ ପୃଷ୍ଠାଟିଏ ତିଆରି କରାଯାଏ ତେବେ ପୁନସ୍ଥାପନ ସଂସ୍କରଣ ଇତିହାସରେ ଦେଖାଯିବ ।',
'undeleterevdel' => 'ଯଦି ଉପର ପୃଷ୍ଠାରେ ଦେଖାଯିବ ବା ଫାଇଲ ନୂଆ ସଂସ୍କରଣର କିଛି ଭାଗ ଲିଭିଯିବ ତାହା ହେଲେ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାକୁ ପୁନସ୍ଥାପନ କରାଯାଇପାରିବ ନାହିଁ ।
ଏପରି କ୍ଷେତ୍ରରେ ଆପଣ ନବୀନତମ ଲିଭାଯାଇଥିବା ସଂସ୍କରଣଟିକୁ ବାଛନ୍ତୁ ନାହିଁ ବା ଲୁଛାଯାଇଥିଲେ ଦେଖାଇଦିଅନ୍ତୁ ।',
'undeletehistorynoadmin' => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇଦିଆଗଲା ।
ଲିଭାଇବା ଆଗରୁ ତଳ ସାରକଥାରେ ଏହି ପୃଷ୍ଠାକୁ ସମ୍ପାଦନା କରିଥିବା ବ୍ୟବହାରକାରୀଙ୍କର ସବିଶେଷ ଦିଆଯାଇଛି ।
ଏହି ଲିଭାଯାଇଥିବା ସଂସ୍କରଣର ଇତିହାସ କେବଳ ପରିଛାମାନଙ୍କ ପାଇଁ ଅଛି ।',
'undelete-revision' => '$3ଙ୍କ ଦେଇ କରାଯାଇଥିବା $1ର ଲିଭାଯାଇଥିବା ସଂସ୍କରଣ ($4 ଅନୁସାରେ, $5 ବେଳେ) :',
'undeleterevision-missing' => 'ଅଚଳ ଅବା ହଜିଲା ସଂସ୍କରଣ ।
ଆପଣଙ୍କ ଲିଙ୍କ ହୁଏତ ଭୁଲ, ଅଥବା ଅଭିଲେଖରୁ ଏହି ସଂସ୍କରଣଟି ହୁଏତ ପୁନସ୍ଥାପନ କରାଯାଇଛି ବା ଲିଭାଦିଆଯାଇଛି ।',
'undelete-nodiff' => 'ଗୋଟିଏ ବି ପୁରୁଣା ସଂସ୍କରଣ ମିଳିଲା ନାହିଁ ।',
'undeletebtn' => 'ପୁନଃସ୍ଥାପନ',
'undeletelink' => 'ଦେଖିବା/ପୁନଃସ୍ଥାପନ',
'undeleteviewlink' => 'ଦେଖଣା',
'undeletereset' => 'ପୁନସ୍ଥାପନ',
'undeleteinvert' => 'ବଛାଯାଇଥିବା ଲେଖାକୁ ଓଲଟେଇଦେବେ',
'undeletecomment' => 'କାରଣ:',
'undeletedrevisions' => '{{PLURAL:$1|ଗୋଟିଏ ସଂକଳନ|$1 ଗୋଟି ସଂକଳନ}} ପୁନସ୍ଥାପନ କରାଗଲା',
'undeletedrevisions-files' => '{{PLURAL:$1|ଗୋଟିଏ ସଂସ୍କରଣ|$1 ଗୋଟି ସଂସ୍କରଣ}} ଓ {{PLURAL:$2|ଗୋଟିଏ ଫାଇଲ|$2 ଗୋଟି ଫାଇଲ}} ପୁନସ୍ଥାପନ କରାଗଲା',
'undeletedfiles' => '{{PLURAL:$1|ଗୋଟିଏ ଫାଇଲ|$1 ଗୋଟି ଫାଇଲ}} ପୁନସ୍ଥାପନ କରାଗଲା',
'cannotundelete' => 'ଲିଭାଇବାରୁ ରୋକିବା ବିଫଳ ହେଲା;
ଏହାକୁ ଆଗରୁ କେହି ଜଣେ ଲିଭାଇବାରୁ ରୋକି ସାରିଅଛି ।',
'undeletedpage' => "'''$1ର ପୁନସ୍ଥାପନ କରାଗଲା'''

ନଗଦ ଲିଭାଇବା ଓ ପୁନସ୍ଥାପନ ପାଇଁ [[Special:Log/delete|ଲିଭାଇବା ଇତିହାସ]] ଦେଖନ୍ତୁ ।",
'undelete-header' => 'ନଗଦ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସବୁ ପାଇଁ [[Special:Log/delete|ଲିଭାଇବା ଇତିହାସ]] ଦେଖନ୍ତୁ ।',
'undelete-search-title' => 'ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ଖୋଜିବେ',
'undelete-search-box' => 'ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ଖୋଜିବେ',
'undelete-search-prefix' => 'ଏହି ଉପସର୍ଗ ସହିତ ଆରମ୍ଭ ହେଉଥିବା ପୃଷ୍ଠାସବୁ ଦେଖାନ୍ତୁ:',
'undelete-search-submit' => 'ଏଠାରେ ଖୋଜନ୍ତୁ',
'undelete-no-results' => 'ଲିଭାଇବା ଅଭିଲେଖରେ ଗୋଟିଏ ବି ପୃଷ୍ଠା ମେଳ ଖାଇଲା ନାହିଁ ।',
'undelete-filename-mismatch' => '$1 ସମୟଚିହ୍ନ ସହ ଥିବା ଫାଇଲ ସଂସ୍କରଣଟି ଲିଭାଯାଇପାରିବ ନାହିଁ: ଫାଇଲନାମ ମେଳ ଖାଉନାହିଁ',
'undelete-bad-store-key' => '$1 ସମୟ ଚିହ୍ନ ଥିବା ସଂସ୍କରଣଟି ଲିଭାଇବାରୁ ରୋକି ପାରିବୁ ନାହିଁ:ଲିଭାଇବା ଆଗରୁ ଫାଇଲଟି ହଜିଯାଇଛି ।',
'undelete-cleanup-error' => 'ବ୍ୟବହାର ହେଉନଥିବା ଅଭିଲେଖ ପୃଷ୍ଠା "$1"କୁ ଲିଭାଇବାରେ ବିଫଳ ହେଲୁଁ ।',
'undelete-missing-filearchive' => 'ଫାଇଲ ଅଭିଲେଖ ID $1 କୁ ପୁନସ୍ଥାପନ କରିବାରେ ଅସଫଳ ହେଲୁ କାରଣ ଏହା ଡାଟାବେସରେ ମିଳିଲା ନାହିଁ ।
ଏହା ବୋଧେ ଆଗରୁ ଲିଭାଯିବାରୁ ରୋକାଯାଇଛି ।',
'undelete-error' => 'ଲିଭିଯାଇଥିବା ପୃଷ୍ଠାକୁ ଲେଉଟାଇବାରେ ଅସୁବିଧା',
'undelete-error-short' => 'ଲିଭାଯାଇଥିବା ଫାଇଲକୁ ଫେରାଇବାରେ ଅସୁବିଧା: $1',
'undelete-error-long' => 'ଲିଭାଯାଇଥିବା ତଳଲିଖିତ ଫାଇଲକୁ ଫେରାଇବାରେ ଅସୁବିଧା:

$1',
'undelete-show-file-confirm' => '$2 ତାରିଖ $3 ବେଳେ "<nowiki>$1</nowiki>" ଏହି ଫାଇଲର ଲିଭାଯାଇଥିବା ସଙ୍କଳନକୁ ଦେଖିବାକୁ ଚାହାନ୍ତି ବୋଲି ଆପଣ ନିଶ୍ଚିତ କି ?',
'undelete-show-file-submit' => 'ହଁ',

# Namespace form on various pages
'namespace' => 'ନେମସ୍ପେସ',
'invert' => 'ବଛାଯାଇଥିବା ଲେଖାକୁ ଓଲଟାଇଦେବେ',
'tooltip-invert' => 'ବଛାଯାଇଥିବା ନେମସ୍ପେସ (ଓ ଏହା ସହ ଯୋଡ଼ା ବାକି ନେମସ୍ପେସ) ଭିତରେ ଥିବା ପୃଷ୍ଠାମାନଙ୍କର ବଦଳକୁ ଲୁଚାଇବା ପାଇଁ ଏହ ଏହିଘରକୁ ବାଛନ୍ତୁ',
'namespace_association' => 'ସମ୍ଭନ୍ଧିତ ନେମସ୍ପେସ',
'tooltip-namespace_association' => 'ବଛାଯାଇଥିବା ନେମ୍ସସ୍ପେସ ସହ ଯୋଡ଼ା ଆଲୋଚନା ବା ବିଷୟ ନେମସ୍ପେସ ଏହା ଅନ୍ତଭୁକ୍ତ କରିବା ନିମନ୍ତେ ଏହି ଘରକୁ ବାଛନ୍ତୁ',
'blanknamespace' => '(ମୂଳ)',

# Contributions
'contributions' => 'ବ୍ୟବହାରକାରୀଙ୍କ ଦାନ',
'contributions-title' => '$1 ପାଇଁ ବ୍ୟବହାରକାରୀଙ୍କ ଦାନ',
'mycontris' => 'ଅବଦାନ',
'contribsub2' => '$1 ($2) ପାଇଁ',
'nocontribs' => 'ଏହି ନିର୍ଣ୍ଣାୟକବଳୀ ନିମନ୍ତେ କିଛି ବି ବଦଳ ମେଳ ଖାଇଲା ନାହିଁ ।',
'uctop' => '(ଉପର)',
'month' => 'ମାସରୁ (ଓ ତା ଆଗରୁ)',
'year' => 'ବର୍ଷରୁ (ଆଉ ତା ଆଗରୁ)',

'sp-contributions-newbies' => 'କେବଳ ନୂଆ ସଭ୍ୟମାନଙ୍କର ଅବଦାନ ଦେଖାଇବେ',
'sp-contributions-newbies-sub' => 'ନୂଆ ଖାତାମାନଙ୍କ ନିମନ୍ତେ',
'sp-contributions-newbies-title' => 'ନୂଆ ଖାତାମାନଙ୍କ ନିମନ୍ତେ ସଭ୍ୟ ଅବଦାନ',
'sp-contributions-blocklog' => 'ଲଗଟିକୁ ଅଟକାଇଦେବେ',
'sp-contributions-deleted' => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ସଭ୍ୟଙ୍କ ଅବଦାନସମୂହ',
'sp-contributions-uploads' => 'ଅପଲୋଡ଼ସବୁ',
'sp-contributions-logs' => 'ଲଗସବୁ',
'sp-contributions-talk' => 'କଥାଭାଷା',
'sp-contributions-userrights' => 'ସଭ୍ୟ ଅଧିକାର ପରିଚାଳନା',
'sp-contributions-blocked-notice' => 'ଏହି ସଭ୍ୟଙ୍କୁ ଏବେ ପାଇଁ ଅଟକାଯାଇଅଛି ।
ଆପଣଙ୍କ ଜାଣିବା ନିମନ୍ତେ ନଗଦ ଇତିହାସ ତଳେ ଦିଆଗଲା:',
'sp-contributions-blocked-notice-anon' => 'ଏହି IP ଠିକଣାକୁ ଏବେ ପାଇଁ ଅଟକାଯାଇଅଛି ।
ଆପଣଙ୍କ ଜାଣିବା ନିମନ୍ତେ ନଗଦ ଇତିହାସ ତଳେ ଦିଆଗଲା:',
'sp-contributions-search' => 'ଅବଦାନ ପାଇଁ ଖୋଜନ୍ତୁ',
'sp-contributions-username' => 'ବ୍ୟବହାରକାରୀଙ୍କର IP ଠିକଣା ବା ଇଉଜର ନାମ:',
'sp-contributions-toponly' => 'ନଗଦ ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'sp-contributions-submit' => 'ଖୋଜିବା',

# What links here
'whatlinkshere' => 'ଏଠାରେ ଥିବା ଲିଙ୍କ',
'whatlinkshere-title' => '"$1" କୁ ପୃଷ୍ଠା ଲିଙ୍କ',
'whatlinkshere-page' => 'ପୃଷ୍ଠା:',
'linkshere' => "ଏହି ପୃଷ୍ଠା ସବୁ  '''[[:$1]]''' ସହ ଯୋଡା ଯାଇଅଛି:",
'nolinkshere' => "'''[[:$1]]''' ସହିତ କୌଣସିଟି ପୃଷ୍ଠା ଯୋଡ଼ାଯାଇନାହିଁ ।",
'nolinkshere-ns' => "ବଛା ଯାଇଥିବା ନେମସ୍ପେସରେ '''[[:$1]]''' ନାଆଁ ସହ କୌଣସି ବି ପୃଷ୍ଠା ଯୋଡ଼ାଯାଇନାହିଁ ।",
'isredirect' => 'ଆଉଥରେ ଫେରିବା ପୃଷ୍ଠା',
'istemplate' => 'ଆଧାର ସହ ଭିତରେ ରଖିବା',
'isimage' => 'ଫାଇଲର ଲିଙ୍କ',
'whatlinkshere-prev' => '{{PLURAL:$1|ଆଗ|ଆଗ $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|ପର|ପର $1}}',
'whatlinkshere-links' => '← ଲିଙ୍କ',
'whatlinkshere-hideredirs' => '$1 କୁ ଲେଉଟାଣି',
'whatlinkshere-hidetrans' => '$1 ଆଧାର ସହ ଭିତରେ ରଖିବା',
'whatlinkshere-hidelinks' => '$1 ଟି ଲିଙ୍କ',
'whatlinkshere-hideimages' => '$1 ଫାଇଲର ଲିଙ୍କସବୁ',
'whatlinkshere-filters' => 'ଛଣା',

# Block/unblock
'autoblockid' => '#$1ଙ୍କୁ ଆପେଆପେ ଅଟକାଇଦେବେ',
'block' => 'ସଭ୍ୟଙ୍କୁ ଅଟକାଇବେ',
'unblock' => 'ବାସନ୍ଦ ହୋଇଥିବା ସଭ୍ୟଙ୍କୁ ମୁକୁଳାଇବେ',
'blockip' => 'ସଭ୍ୟଙ୍କୁ ଅଟକାଇବେ',
'blockip-title' => 'ସଭ୍ୟଙ୍କୁ ବାସନ୍ଦ କରିବେ',
'blockip-legend' => 'ସଭ୍ୟଙ୍କୁ ବାସନ୍ଦ କରିବେ',
'blockiptext' => 'ଏକ ନିର୍ଦିଷ୍ଟ IP ଠିକଣା ବା ବ୍ୟବହାରକାରୀଙ୍କ ଲେଖିବା ସୁବିଧାକୁ ବାରାଁ କରିବା ନିମନ୍ତେ ଏହି ତଳ ଫର୍ମଟି ବ୍ୟବହାର କରନ୍ତୁ ।
ଏହା କେବଳ ଅପବ୍ୟବହାରକୁ ରୋକିବା ନିମନ୍ତେ କରାଯାଇଥାଏ, ଏହା [[{{MediaWiki:Policy-url}}|ନୀତି]] ଅନୁସାରେ କରାଯାଇଥାଏ ।
ଏହା ତଳେ ଏକ ନିର୍ଦିଷ୍ଟ କାରଣ ଦିଅନ୍ତୁ (ଯଥା, ଯେଉଁସବୁ ପୃଷ୍ଠାରେ କିଛି ପ୍ରକାରର ଅପବ୍ୟବହାର କରାଯାଇଛି) ।',
'ipadressorusername' => 'ବ୍ୟବହାରକାରୀଙ୍କର IP ଠିକଣା ବା ଇଉଜର ନାମ:',
'ipbexpiry' => 'ମିଆଦ:',
'ipbreason' => 'କାରଣ:',
'ipbreasonotherlist' => 'ଅଲଗା କାରଣ',
'ipbreason-dropdown' => '*ସାଧାରଣ ଅଟକ କାରଣ
** ଭୁଲ ବିବରଣୀ ଦେବା
** ପୃଷ୍ଠାରୁ ବିବରଣୀ କାଢିବା
** ଅନନୁମୋଦିତ ବାହାର ସାଇଟର ଲିଙ୍କ ସ୍ପାମ କରିବା
** ଅଯଥା କଥା ପୃଷ୍ଠାରେ ପୁରାଇବା
** ଧମକାଣି/ଅପମାନ
** ଏକାଧିକ ଖାତାରେ ଅସଦାଆଚରଣ
** ଗ୍ରହଣ ଅଯୋଗ୍ୟ ଇଉଜର ନାମ',
'ipb-hardblock' => 'ଏହି IP ଠିକଣାରୁ ଲଗ ଇନ କରିଥିବା ସଭ୍ୟମାନଙ୍କୁ ସମ୍ପାଦନା କରିବାରୁ ବାଟ ଓଗାଳନ୍ତୁ',
'ipbcreateaccount' => 'ନୂଆ ଖାତା ଖୋଲାକୁ ଅଟକାଗଲା',
'ipbemailban' => 'ଇ-ମେଲ ପଠାଇବାରୁ ଜଣେ ସଭ୍ୟଙ୍କୁ ବାରଣ କରିବେ',
'ipbenableautoblock' => 'ଏହି ବ୍ୟବହାରକାରୀଙ୍କ ଦେଇ ହୋଇଥିବା ଏକ IP ଠିକଣାରୁ ବା ଯେକୌଣସି IP ଠିକଣାରୁ ସେ ସମ୍ପାଦନା କରିବାକୁ ଚେଷ୍ଟା କରୁଥିଲେ ଶେଷ ବଦଳଟି ଆପେଆପେ ଶେଷ IP ଠିକଣାଟି ଅଟକାଇ ଦିଅନ୍ତୁ ।',
'ipbsubmit' => 'ସଭ୍ୟଙ୍କୁ ଅଟକାଇଦେବେ',
'ipbother' => 'ବାକି ସମୟ:',
'ipboptions' => '୨ ଘଣ୍ଟା:2 hours,୧ ଦିନ:1 day,୩ ଦିନ:3 days,୧ ସପ୍ତାହ:1 week,୨ ସପ୍ତାହ:2 weeks,୧ ମାସ:1 month,୩ ମାସ:3 months,୬ ମାସ:6 months,୧ ବର୍ଷ:1 year,ଅସିମୀତ କାଳ:infinite',
'ipbotheroption' => 'ବାକି',
'ipbotherreason' => 'ବାକି/ଅଧିକ କାରଣ:',
'ipbhidename' => 'ଇଉଜର ନାମକୁ ସମ୍ପାଦନା ଓ ତାଲିକାରୁ ଲୁଚାଇବେ',
'ipbwatchuser' => 'ସଭ୍ୟଙ୍କ ପୃଷ୍ଠା ଓ ତାହାଙ୍କର ଆଲୋଚନା ପୃଷ୍ଠକୁ ଦେଖିବେ',
'ipb-disableusertalk' => 'ଅଟକାଯାଇଥିବା ବେଳେ ଏହି ସଭ୍ୟଙ୍କୁ ତାହାଙ୍କ ନିଜ ଆଲୋଚନା ପୃଷ୍ଠାକୁ ବଦଳାଇବାକୁ ବାରଣ କରନ୍ତୁ',
'ipb-change-block' => 'ସଭ୍ୟଜଣଙ୍କୁ ଏହି ସଜାଣିରେ ଆଉଥରେ ଅଟକାନ୍ତୁ',
'ipb-confirm' => 'ଅଟକ ଥୟ କରିବେ',
'badipaddress' => 'ଭୁଲ IP ଠିକଣା',
'blockipsuccesssub' => 'ବାସନ୍ଦ ସଫଳ ହେଲା',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] ଅଟକାଯାଯାଇଛି.<br />
ଅଟକ ବାବଦରେ ଟିପ୍ପଣୀ ଦେବା ନିମନ୍ତେ [[Special:BlockList|block list]] ଦେଖନ୍ତୁ ।',
'ipb-blockingself' => 'ଆପଣ ନିଜକୁ ଅଟକାଇବାକୁ ଯାଉଛନ୍ତି ! ଆପଣ ପୁରାପୁରି ନିଶ୍ଚିତ ତ?',
'ipb-confirmhideuser' => 'ଆପଣ "ବ୍ୟବହାରକାରୀଙ୍କୁ ଲୁଚାନ୍ତୁ" ସଚଳ କରି ଜଣେ ବ୍ୟବହାରକାରୀଙ୍କୁ ଅଟକାଇବାକୁ ଯାଉଛନ୍ତି । ଏହା ବ୍ୟବହାରକାରୀଙ୍କ ନାମକୁ ସବୁଯାକ ତାଲିକାୟ ଇତିହାସରେ ଲୁଚାଇଦେବ । ତଥାପି ବି ଆପଣ ଏହା କରିବାକୁ ଚାହୁଁଛନ୍ତି କି?',
'ipb-edit-dropdown' => 'ସମ୍ପାଦନା ଅଟକ କାରଣମାନ',
'ipb-unblock-addr' => '$1ଙ୍କୁ କିଳିବେ ନାହି',
'ipb-unblock' => 'ଏକ ଇଉଜର ନାମ ବା IP ଠିକଣାକୁ ବାସନ୍ଦରୁ ମୁକୁଳାଇବେ',
'ipb-blocklist' => 'ଏବେ ଥିବା ଅଟକମାନ ଦେଖାନ୍ତୁ',
'ipb-blocklist-contribs' => '$1 ପାଇଁ ଅବଦାନ',
'unblockip' => 'ବାସନ୍ଦ ହୋଇଥିବା ସଭ୍ୟଙ୍କୁ ମୁକୁଳାଇବେ',
'unblockiptext' => 'ଆଗରୁ ବାସନ୍ଦ କରାଯାଇଥିବା ଇଉଜର ନାମ ବା IP ଠିକଣା ମୁକୁଳାଇବା ନିମନ୍ତେ ତଳେ ଥିବା ଫର୍ମ ବ୍ୟବହାର କରନ୍ତୁ ।',
'ipusubmit' => 'ଏହି ବାସନ୍ଦଟିକୁ ଉଠାଇଦେବେ',
'unblocked' => '[[User:$1|$1]] ନାମକ ସଭ୍ୟଜଣକ ବାସନ୍ଦରୁ ମୁକୁଳିଗଲେ',
'unblocked-range' => '$1 ଅଟକରୁ ବାହାର କରିଦିଆଗଲା',
'unblocked-id' => '$1 ଅଟକଟି ହଟାଇଦିଆଗଲା',
'blocklist' => 'ବାସନ୍ଦ କରାଯାଇଥିବା ସଭ୍ୟ',
'ipblocklist' => 'ଅଟକାଯାଇଥିବା ସଭ୍ୟସମୂହ',
'ipblocklist-legend' => 'ଜଣେ ଅଟକାଯାଇଥିବା ସଭ୍ୟ ଖୋଜିବେ',
'blocklist-userblocks' => 'ଅଟକାଯାଇଥିବା ଖାତାସବୁ ଖୋଜିବେ',
'blocklist-tempblocks' => 'ଅସ୍ଥାୟୀ ଅଟକସବୁ ଲୁଚାଇଦେବେ',
'blocklist-addressblocks' => 'ଗୋଟିକିଆ IP ଅଟକସବୁ ଲୁଚାଇବେ',
'blocklist-rangeblocks' => 'ରେଞ୍ଜ ବ୍ଲକସବୁ ଲୁଚାଇଦେବେ',
'blocklist-timestamp' => 'ସମୟଚିହ୍ନ',
'blocklist-target' => 'ଲକ୍ଷ',
'blocklist-expiry' => 'ଅଚଳ ହେବ',
'blocklist-by' => 'ଅଟକାଇବା ପରିଛା',
'blocklist-params' => 'ଅଟକାଇବା ପାରାମିଟର',
'blocklist-reason' => 'କାରଣ',
'ipblocklist-submit' => 'ଖୋଜିବା',
'ipblocklist-localblock' => 'ସ୍ଥାନୀୟ ଅଟକ',
'ipblocklist-otherblocks' => 'ବାକି {{PLURAL:$1|ଗୋଟି ଅଟକ|ଗୋଟି ଅଟକ}}',
'infiniteblock' => 'ଅସିମୀତ',
'expiringblock' => '$1 ତାରିଖ $2ବେଳେ ମିଆଦ ପୁରିବ',
'anononlyblock' => 'କେବଳ ବେନାମି',
'noautoblockblock' => 'ଆପେଆପେ କରାଯାଇଥିବା ଅଟକ ଅଚଳ କରାଗଲା',
'createaccountblock' => 'ନୂଆ ଖାତା ଖୋଲିବା ଅଚଳ କରାଗଲା',
'emailblock' => 'ଇ-ମେଲ ଅଟକାଗଲା',
'blocklist-nousertalk' => 'ଆପଣା ଆଲୋଚନା ପୃଷ୍ଠାକୁ ବଦଳାଇ ପାରିବେ ନାହିଁ',
'ipblocklist-empty' => 'ଅଟକ ତାଲିକାଟି ଖାଲି ।',
'ipblocklist-no-results' => 'ଅନୁରୋଧ କରାଯାଇଥିବା IP ଠିକଣାଟି ବ ଇଉଜର ନାମଟି ଅଟକାଯାଇନାହିଁ ।',
'blocklink' => 'ଅଟକେଇ ଦେବେ',
'unblocklink' => 'ଛାଡ଼ିବା',
'change-blocklink' => 'ଓଗଳାକୁ ବଦଳାଇବେ',
'contribslink' => 'ଅବଦାନ',
'emaillink' => 'ଇ-ମେଲ ପଠାଇବେ',
'autoblocker' => '"[[User:$1|$1]]"ଙ୍କ ଦେଇ ଏହି ମାତ୍ର ଆପଣଙ୍କ IP ଠିକଣାଟି ଆପେଆପେ ଅଟକାଯାଇଅଛି ।
$1ର ଅଟକ ପାଇଁ ଦିଆଯାଇଥିବା କାରଣଟି ହେଲା: "$2"',
'blocklogpage' => 'ଲଗଟିକୁ ଅଟକାଇଦେବେ',
'blocklog-showlog' => 'ଏହି ସଭ୍ୟଜଣଙ୍କୁକ ଆଗରୁ ଅଟକାଯାଇଛି ।
ତଳେ ଅଟକ ଇତିହାସଟି ଅବଗତି ନିମନ୍ତେ ଦିଆଗଲା:',
'blocklog-showsuppresslog' => 'ଏହି ସଭ୍ୟଜଣଙ୍କୁ ଆଗରୁ ଅଟକାଯାଇଛି ବା ଲୁଚାଯାଇଛି ।
ତଳେ ଲୁଚାଇବା ଇତିହାସ ଅବଗତି ନିମନ୍ତେ ଦିଆଗଲା:',
'blocklogentry' => '[[$1]]ଟିକୁ $2 $3 ଯାଏଁ ଅଟକାଗଲା',
'reblock-logentry' => '[[$1]] ଙ୍କ ନିମନ୍ତେ $2 $3 ମିଆଦର ଅଟକକୁ ବଦଳାଗଲା',
'blocklogtext' => 'ଏହା ଏକ ବ୍ୟବହାରକାରୀ ଅଟକ ଓ ଛାଡ଼ ପାଇଁ ଇତିହାସ ।
ଆପେଆପେ ଅଟକାଯାଇଥିବା IP ଠିକଣା ଏଠାରେ ସ୍ଥାନିତ ହୋଇନାହିଁ ।
ଏବେ ସଚଳ କରାଯାଇଥିବା ଅଟକ ଓ ବାରଣସବୁ ଦେଖବା ନିମନ୍ତେ [[Special:BlockList|block]] ଦେଖନ୍ତୁ ।',
'unblocklogentry' => 'କିଳାଯାଇନଥିବା $1',
'block-log-flags-anononly' => 'କେବଳ ବେନାମି ସଭ୍ୟ',
'block-log-flags-nocreate' => 'ନୂଆ ଖାତା ଖୋଲିବା ଅଚଳ କରାଯାଇଅଛି',
'block-log-flags-noautoblock' => 'ଆପେଆପେ କରାଯାଇଥିବା ଅଟକ ଅଚଳ କରାଗଲା',
'block-log-flags-noemail' => 'ଇ-ମେଲ ଅଟକାଗଲା',
'block-log-flags-nousertalk' => 'ଆପଣା ଆଲୋଚନା ପୃଷ୍ଠାକୁ ବଦଳାଇ ପାରିବେ ନାହିଁ',
'block-log-flags-angry-autoblock' => 'ଉନ୍ନତ ଆପେଆପେ ଅଟକ ସଚଳ କରାଗଲା',
'block-log-flags-hiddenname' => 'ସଭ୍ୟନାମ ଲୁଚାଯାଇଅଛି',
'range_block_disabled' => 'ଏକାଧିକ ଅଟକ ପାଇଁ ପରିଛା ସୁବିଧାଟି ଅଚଳ କରାଯାଇଛି ।',
'ipb_expiry_invalid' => 'ଅଚଳ ହେବାର ବେଳା ଭୁଲ ।',
'ipb_expiry_temp' => 'ଲୁଚାଯାଇଥିବା ବ୍ୟବହାରକାରୀ ନାମ ଅଟକ ସବୁ ସ୍ଥାୟୀ ହେବ ଉଚିତ ।',
'ipb_hide_invalid' => 'ଏହି ଖାତାଟିକୁ ଦବାଇବାରେ ବିଫଳ ହେଲୁ; ଏଥିରେ ଅନେକଗୁଡ଼ିଏ ସମ୍ପାଦନା ଥାଇପାରେ ।',
'ipb_already_blocked' => '"$1" ଆଗରୁ ଅଟକାଯାଇଅଛି',
'ipb-needreblock' => '"$1" ଆଗରୁ ଅଟକାଯାଇଅଛି । ଆପଣ ସଜାଣିସବୁ ବଦଳାଇବାକୁ ଚାହାନ୍ତି କି?',
'ipb-otherblocks-header' => 'ବାକି {{PLURAL:$1|ଗୋଟି ଅଟକ|ଗୋଟି ଅଟକ}}',
'unblock-hideuser' => 'ବ୍ୟବହାରକାରୀଙ୍କ ନାମ ଲୁଚାଯାଇଥିବାରୁ ଆପଣ ଏହି ବ୍ୟବହାରକାରୀଙ୍କୁ ଅଟକାଇବାରୁ ରୋକିପାରିବେ ନାହିଁ ।',
'ipb_cant_unblock' => 'ଭୁଲ: ଅଟକ ID $1 ମିଳିଲା ନାହିଁ । ଏହାକୁ ଆଗରୁ ଅଟକାଯାଇଥାଇପାରେ ।',
'ipb_blocked_as_range' => 'ଭୁଲ: ଏହି IP ଠିକଣା $1ଟି ସିଧା ଅଟକାଯାଇନାହିଁ ଓ ଖୋଲାଯାଇପାରିବ ନାହିଁ ।
ଏହା, $2 ଭିତରେ ଥିବାରୁ ତାହାକୁ ଅଟକରୁ ଛାଡ଼ କରାଯାଇପାରିବ ନାହିଁ ।',
'ip_range_invalid' => 'ଅଚଳ IP ସୀମା ।',
'ip_range_toolarge' => '/$1 ଠାରୁ ବଡ଼ ସୀମା ଅଟକ ଅନୁମୋଦିତ ନୁହେଁ ।',
'blockme' => 'ମୋତେ ଅଟକାଇବେ',
'proxyblocker' => 'ପ୍ରକ୍ସି ଅଟକ',
'proxyblocker-disabled' => 'ଏହି କାମଟି ଅଚଳ କରାଯାଇଅଛି ।',
'proxyblockreason' => 'ଏକ ଖୋଲା ପ୍ରକ୍ସି ହୋଇଥିବାରୁ ଆପଣଙ୍କ IP ଠିକଣାଟିକୁ ଅଟକାଇଦିଆଗଲା ।
ଦୟାକରି ଆପଣଙ୍କ ଇଣ୍ଟରନେଟ ସେବାପ୍ରଦାନକାରୀ, କାରିଗରି ସହଯୋଗ କିମ୍ବା ସଙ୍ଗଠନ ସହିତ କଥା ହୋଇ ଏହି ବିରାଟ ଅସୁବିଧା ବାବଦରେ ବତାଇଦିଅନ୍ତୁ ।',
'proxyblocksuccess' => 'ଶେଷ ହେଲା ।',
'sorbsreason' => '{{SITENAME}} ଦେଇ ଆପଣଙ୍କ IP ଠିକଣାଟି DNSBL ଭିତରେ ଏକ ଖୋଲା ପ୍ରକ୍ସି ଭାବରେ ନଥିଭୁକ୍ତ ହୋଇଅଛି ।',
'sorbs_create_account_reason' => '{{SITENAME}} ଦେଇ ଆପଣଙ୍କ IP ଠିକଣାଟି DNSBL ଭିତରେ ଏକ ଖୋଲା ପ୍ରକ୍ସି ଭାବରେ ନଥିଭୁକ୍ତ ହୋଇଅଛି ।
ଆପଣ ନୂଆ ଖାତାଟିଏ ଖୋଲି ପାରିବେ ନାହିଁ',
'cant-block-while-blocked' => 'ଆପଣ ନିଜେ ଅଟକାଯାଇଥିବା ଯାଏଁ କେବେ ଅନ୍ୟମାନଙ୍କୁ ଅଟକାଇପାରିବେ ନାହିଁ ।',
'cant-see-hidden-user' => 'ଆପଣ ଅଟକାଇବାକୁ ଚାହୁଁଥିବା ସଭ୍ୟଜଣକ ଆଗରୁ ଅଟକାଯାଇଛନ୍ତି ଓ ଲୁଚାଯାଇଛନ୍ତି ।
ଯେହେତୁ ଆପଣଙ୍କ ପାଖରେ ସଭ୍ୟଙ୍କୁ ଲୁଚାଇବା ଅଧିକାର ନାହୀଁ, ଆପଣ ସଭ୍ୟଙ୍କର ଅଟକକୁ ଦେଖିପାରିବେ ବା ବଦଳାଇପାରିବେ ନାହିଁ ।',
'ipbblocked' => 'ଯେହେତୁ ଆପଣଙ୍କୁ ଅଟକାଯାଇଛି ଆପଣ ବାକି ସଭ୍ୟମାନଙ୍କୁ ଅଟକାଇ ବା ଅଟକରୁ ଛାଡ଼ କରିପାରିବେ ନାହିଁ ।',
'ipbnounblockself' => 'ଆପଣ ନିଜକୁ ଅଟକାଇପାରିବେ ନାହିଁ',

# Developer tools
'lockdb' => 'ଡାଟାବେସଟି କିଳିବେ',
'unlockdb' => 'କିଳାଯାଇଥିବା ଡାଟାବେସଟି ଖୋଲିବେ',
'lockdbtext' => 'ଡାଟାବେସକୁ କିଳିବା ଯୋଗୁଁ ତାହା ସଭ୍ୟମାନଙ୍କର ପୃଷ୍ଠା ସମ୍ପାଦନା, ନିଜ ପସନ୍ଦ, ଆପଣା ଦେଖଣାତାଲିକାକୁ ବଦଳାଇବା ଓ ଅନେକ ଡାଟାବେସ ବଦଳରେ ଅସୁବିଧା ଘଟାଇବ ।
ଦୟାକରି ନିଶ୍ଚିତ କରନ୍ତୁ ଯେ ଆପଣ ରକ୍ଷଣାବେକ୍ଷଣା ସରିଲା ପରେ ଏହାକୁ କିଳିବାରୁ ମୁକୁଳାଇଦେବେ ।',
'unlockdbtext' => 'କିଳାହୋଇଥିବା ଡାଟାବେସଟିକୁ ଖୋଲିଲେ ସବୁ ବ୍ୟବହାରକାରୀ ପୃଷ୍ଠା, ନିଜ ପସନ୍ଦ, ଦେଖଣାତାଲିକା ଓ ଡାଟାବେସରେ ହେବାକୁଥିବା ବଦଳର ସମ୍ପାଦନା କରିପାରିବେ ।
ଆପଣ ଏହା ହିଁ ଚାହାଁନ୍ତି ବୋଲି ଥୟ କରନ୍ତୁ ।',
'lockconfirm' => 'ହଁ, ମୁଁ ସତରେ ଡାଟାବେସଟିକୁ କିଳିବାକୁ ଚାହେଁ ।',
'unlockconfirm' => 'ହଁ, ମୁଁ ସତରେ କିଲାଯାଇଥିବା ଡାଟାବେସଟିକୁ ଖୋଲିବାକୁ ଚାହେଁ ।',
'lockbtn' => 'ଡାଟାବେସଟି କିଳିବେ',
'unlockbtn' => 'କିଳାଯାଇଥିବା ଡାଟାବେସଟି ଖୋଲିବେ',
'locknoconfirm' => 'ଆପଣ ନିଶ୍ଚିତ କରା ଘରଟିକୁ ବାଛିନାହାନ୍ତି ।',
'lockdbsuccesssub' => 'ଡାଟାବେସଟି ଠିକ ଭାବେ କିଳାଗଲା',
'unlockdbsuccesssub' => 'ଡାଟାବେସର ତାଲା ଖୋଳାଗଲା',
'lockdbsuccesstext' => 'ଡାଟାବେସଟି କିଳାଯାଇଛି<br />
ମନେରଖନ୍ତୁ ଯେ ରକ୍ଷଣାବେକ୍ଷଣା ସରିଲେ [[Special:UnlockDB|ଅଟକରୁ ଛାଡ଼କରିଦେବେ]] ।',
'unlockdbsuccesstext' => 'ଡାଟାବେସଟି ଅଟକରୁ ଛାଡ଼ କରାଗଲା ।',
'lockfilenotwritable' => 'ଡାଟାବେସର କିଳାଯାଇଥିବା ଫାଇଲ ଲେଖାଯାଇପାରିବ ନାହିଁ ।
ଡାଟାବେସକୁ କିଳିବା ବା କିଳାହେବାରୁ ଛାଡ଼ କରିବା ପାଇଁ ଏହା ଏକ ୱେବ ସର୍ଭର ଦେଇ ଲେଖିବାକୁ ପଡ଼ିବ ।',
'databasenotlocked' => 'ଡାଟାବେସଟି କିଳାଯାଇନାହିଁ ।',
'lockedbyandtime' => '($2 ଦିନ $3ଟା ବେଳେ {{GENDER:$1|$1}}ଙ୍କ ଦେଇ )',

# Move page
'move-page' => '$1କୁ ଘୁଞ୍ଚାଇବେ',
'move-page-legend' => 'ପୃଷ୍ଠା ଘୁଞ୍ଚାଇବେ',
'movepagetext' => "ଏହି ଫର୍ମଟି ବ୍ୟବହାର କରି ଆପଣ ତଳ ପୃଷ୍ଠାଟିକୁ ବଦଳାଇ ପାରିବେ, ଏହାର ସବୁ ଇତିହାସ ଏକ ନୂଆ ନାଆଁକୁ ବଦଳିଯିବ ।
ପୁରୁଣା ନାଆଁଟି ଏକ ପୁରୁଣା ନାଆଁ ଭାବରେ ଏହି ପୃଷ୍ଠା ଭାବରେ ବାଟ କଢ଼ାଇବ ।
ଆପଣ ମୂଳ ଲେଖାକୁ ସେହି ପୁରୁଣା ନାଆଁ ଦେଇ ଆପେଆପେ ପଢ଼ିପାରିବେ ।
ଯଦି ଆପଣ ଏହା ଚାହାନ୍ତି ନାହିଁ ତେବେ [[Special:DoubleRedirects|ଦୁଇଥର ଥିବା ପୃଷ୍ଠା]] ବା [[Special:BrokenRedirects|ଭଙ୍ଗା ଆଗ ପୃଷ୍ଠା]] ଦେଖି ପାରିବେ ।

ଲିଙ୍କସବୁ କେଉଁଠିକୁ ଯାଉଛି ତାହା ପାଇଁ ଆପଣ ଦାୟୀ ନୁହନ୍ତି ।

ମନେ ରଖନ୍ତୁ, ଆଗରୁ ଏହି ଏକା ନାଆଁରେ ପୃଷ୍ଠାଟିଏ ଥିଲେ ଏହି ପୃଷ୍ଠାଟି '''ଘୁଞ୍ଚିବ ନାହିଁ''' ଯେତେ ଯାଏଁ ତାହା ଖାଲି ନାହିଁ ବା ଆଗ ପୃଷ୍ଠାଟିର କୌଣସି ବଦଳ ଇତିହାସ ନାହିଁ ସେତେ ବେଳ ଯାଏଁ ଏହା ଏମିତି ରହିବ । ଏହାର ମାନେ ହେଉଛି, ଆପଣ ଗୋଟିଏ ପୃଷ୍ଠାର ନାଆଁକୁ ତାର ପୁରୁଣା ନାଆଁ ଦେଇପାରିବେ, କିନ୍ତୁ ଆଗରୁ ଥିବା ପୃଷ୍ଠାଟି ଉପରେ ନୂଆ ପୃଷ୍ଠାଟିଏ ଚାପି ଦେଇପାରିବେ ନାହିଁ ।

'''ଜାଣି ରଖନ୍ତୁ!'''
ଏହା ଏକ ଜଣାଶୁଣା ପୃଷ୍ଠାରେ ଆମୂଳଚୂଳ ଓ ଅଜଣା ବଦଳ କରିପାରେ;
ନିଶ୍ଚିତ କରନ୍ତୁ ଆପଣ ଆଗକୁ ବଢ଼ିବା ଆଗରୁ ଏହାର ଫଳ ବାବଦରେ ଜାଣିଛନ୍ତି ।",
'movepagetext-noredirectfixer' => "ଏହି ଫର୍ମଟି ବ୍ୟବହାର କରି ଆପଣ ତଳ ପୃଷ୍ଠାଟିକୁ ବଦଳାଇ ପାରିବେ, ଏହାର ସବୁ ଇତିହାସ ଏକ ନୂଆ ନାଆଁକୁ ବଦଳିଯିବ ।
ପୁରୁଣା ନାଆଁଟି ଏକ ପୁରୁଣା ନାଆଁ ଭାବରେ ଏହି ପୃଷ୍ଠା ଭାବରେ ବାଟ କଢ଼ାଇବ ।
ଆପଣ ମୂଳ ଲେଖାକୁ ସେହି ପୁରୁଣା ନାଆଁ ଦେଇ ଆପେଆପେ ପଢ଼ିପାରିବେ ।
ଯଦି ଆପଣ ଏହା ଚାହାନ୍ତି ନାହିଁ ତେବେ [[Special:DoubleRedirects|ଦୁଇଥର ଥିବା ପୃଷ୍ଠା]] ବା [[Special:BrokenRedirects|ଭଙ୍ଗା ଆଗ ପୃଷ୍ଠା]] ଦେଖି ପାରିବେ ।

ଲିଙ୍କସବୁ କେଉଁଠିକୁ ଯାଉଛି ତାହା ପାଇଁ ଆପଣ ଦାୟୀ ନୁହନ୍ତି ।

ମନେ ରଖନ୍ତୁ, ଆଗରୁ ଏହି ଏକା ନାଆଁରେ ପୃଷ୍ଠାଟିଏ ଥିଲେ ଏହି ପୃଷ୍ଠାଟି '''ଘୁଞ୍ଚିବ ନାହିଁ''' ଯେତେ ଯାଏଁ ତାହା ଖାଲି ନାହିଁ ବା ଆଗ ପୃଷ୍ଠାଟିର କୌଣସି ବଦଳ ଇତିହାସ ନାହିଁ ସେତେ ବେଳ ଯାଏଁ ଏହା ଏମିତି ରହିବ । ଏହାର ମାନେ ହେଉଛି, ଆପଣ ଗୋଟିଏ ପୃଷ୍ଠାର ନାଆଁକୁ ତାର ପୁରୁଣା ନାଆଁ ଦେଇପାରିବେ, କିନ୍ତୁ ଆଗରୁ ଥିବା ପୃଷ୍ଠାଟି ଉପରେ ନୂଆ ପୃଷ୍ଠାଟିଏ ଚାପି ଦେଇପାରିବେ ନାହିଁ ।

'''ଜାଣି ରଖନ୍ତୁ!'''
ଏହା ଏକ ଜଣାଶୁଣା ପୃଷ୍ଠାରେ ଆମୂଳଚୂଳ ଓ ଅଜଣା ବଦଳ କରିପାରେ;
ନିଶ୍ଚିତ କରନ୍ତୁ ଆପଣ ଆଗକୁ ବଢ଼ିବା ଆଗରୁ ଏହାର ଫଳ ବାବଦରେ ଜାଣିଛନ୍ତି ।",
'movepagetalktext' => 'ଯଦି:
*ଗୋଟିଏ ଖାଲି ଆଲୋଚନା ପୃଷ୍ଠା ସେହି ନାଆଁରେ ଥାଏ
*ଆପଣ ତଳ ବାକ୍ସକୁ ନ ବାଛନ୍ତି
ତେବେ ଏହି ପ୍ରୁଷ୍ଠା ସହ ଯୋଡାଯାଇଥିବା ଆଲୋଚନା ପ୍ରୁଷ୍ଠାକୁ ଆପେ ଆପେ ଘୁଞ୍ଚାଇଦିଆଯିବ ।
ସେହି ଯାଗାରେ, ଆପଣଙ୍କୁ ପ୍ରୁଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବାକୁ/ମିଶାଇବାକୁ ପଡ଼ିବ ।',
'movearticle' => 'ପୃଷ୍ଠା ଘୁଞ୍ଚେଇବା:',
'moveuserpage-warning' => "'''ସୂଚନା:''' ଆପଣ ଏକ ବ୍ୟବହାରକାରୀ ପୃଷ୍ଠାକୁ ଘୁଞ୍ଚାଇବାକୁ ଯାଉଛନ୍ତି । ଦୟାକରି ଜାଣିରଖନ୍ତୁ ଯେ ପୃଷ୍ଠାଟି କେବଳ ଘୁଞ୍ଚିଯିବ ଓ ବ୍ୟବହାରକାରୀ ''ଘୁଞ୍ଚିବେ ନାହିଁ'' ।",
'movenologin' => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
'movenologintext' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବା ପାଇଁ ଆପଣ ନିହାତି ଜଣେ ପଞ୍ଜୀକୃତ ସଭ୍ୟ ହୋଇଥିବେ ଏବଂ [[Special:UserLogin|logged in]]',
'movenotallowed' => 'ଆପଣଙ୍କର ପୃଷ୍ଠାଗୁଡିକୁ ଘୁଞ୍ଚାଇବା ଅଧିକାର ନହିଁ ।',
'movenotallowedfile' => 'ଆପଣଙ୍କର ଫାଇଲ ଘୁଞ୍ଚାଇବାର ଅଧିକାର ନହିଁ ।',
'cant-move-user-page' => 'ଆପଣଙ୍କୁ ଏହି ସଭ୍ୟ ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବା ଲାଗି ଅନୁମତି ମିଳିନାହିଁ, କିନ୍ତୁ ନିଜର ଉପପୃଷ୍ଠା ସବୁ ଘୁଞ୍ଚାଇ ପାରିବେ ।',
'cant-move-to-user-page' => 'ଆପଣଙ୍କର ଗୋଟିଏ ପୃଷ୍ଠାକୁ ବ୍ୟବହାରକାରୀ ପୃଷ୍ଠାକୁ ଘୁଞ୍ଚାଇବାର ଅଧିକାର ନହିଁ ।',
'newtitle' => 'ନୂଆ ନାଆଁକୁ:',
'move-watch' => 'ମୂଳ ପୃଷ୍ଠା ଓ ବଦଳାଇବାକୁ ଚାହୁଁଥିବା ପୃଷ୍ଠା ଦେଖାଇବେ',
'movepagebtn' => 'ପୃଷ୍ଠା ଘୁଞ୍ଚେଇବେ',
'pagemovedsub' => 'ଘୁଞ୍ଚାଇବା ସଫଳ ହେଲା',
'movepage-moved' => '\'\'\'"$1"ରୁ "$2"\'\'\'କୁ ଘୁଞ୍ଚାଇ ଦିଆଗଲା ।',
'movepage-moved-redirect' => 'ପୃଷ୍ଠାଟିର ନାଆଁକୁ ଘୁଞ୍ଚାଇଦିଆଗଲା ।',
'movepage-moved-noredirect' => 'ଏକ ପୁନପ୍ରେରଣ ତିଆରି ଦବାଇଦିଆଗଲା ।',
'articleexists' => 'ସେହି ନାମରେ ଆଗରୁ ପୃଷ୍ଠାଟିଏ ଅଛି, କିମ୍ବା ଆପଣ ବାଛିଥିବା ନାମଟି ବୈଧ ନୁହେଁ ।
ଦୟାକରି ଆଉଗୋଟେ ନାମ ବାଛନ୍ତୁ ।',
'cantmove-titleprotected' => 'ଆପଣ ଏହି ଲକ୍ଷସ୍ଥଳକୁ ପୃଷ୍ଠାଟିଏ ଘୁଞ୍ଚାଇପାରିବେ ନାହିଁ, କାରଣ ନୂଆ ନାମ ତିଆରିକୁ ଅଟକାଯାଇଛି ।',
'talkexists' => "'''ଏହି ପୃଷ୍ଠାଟି ସଫଳଭାବେ ଘୁଞ୍ଚାଇଦିଆଗଲା, କିନ୍ତୁ ଆଲୋଚନା ପୃଷ୍ଠାଟି ଘୁଞ୍ଚାଯଇ ପାରିବ ନାହିଁ କାରଣ ନୂଆ ନାମରେ ଆଗରୁ ଆଲୋଚନା ପୃଷ୍ଠାଟିଏ ଅଛି ।
ଦୟାକରି ସେଦୁହିଁଙ୍କୁ ମିଶାଇଦିଅନ୍ତୁ ।'''",
'movedto' => 'ଘୁଞ୍ଚାଗଲା',
'movetalk' => 'ଏଥି ସହିତ ଯୋଡ଼ା ଆଲୋଚନା ପୃଷ୍ଠାସବୁ ଘୁଞ୍ଚାଇବେ',
'move-subpages' => 'ଉପପୃଷ୍ଠା ଗୁଡ଼ିକୁ ଘୁଞ୍ଚାଇବେ ($1 ଯାଏଁ)',
'move-talk-subpages' => 'ଆଲୋଚନାର ଉପପୃଷ୍ଠାକୁ ଘୁଞ୍ଚାଇଦିଅନ୍ତୁ ($1 ଯାଏଁ)',
'movepage-page-exists' => '$1 ପୃଷ୍ଠାଟି ଆଗରୁ ଅଛି ଓ ଆଉଥରେ ଲେଖାଯାଇପାରିବ ନାହିଁ ।',
'movepage-page-moved' => '$1 ପୃଷ୍ଠାଟିକୁ $2କୁ ଘୁଞ୍ଚାଇ ଦିଆଗଲା ।',
'movepage-page-unmoved' => '$1 ପୃଷ୍ଠାରୁ $2କୁ ଘୁଞ୍ଚାଯାଇ ପାରିବ ନାହିଁ ।',
'movepage-max-pages' => 'ସବୁଠାରୁ ଅଧିକ ହେଲେ $1 ଗୋଟି {{PLURAL:$1|ପୃଷ୍ଠା|ପୃଷ୍ଠା}} ଘୁଞ୍ଚାଇଦିଆଯାଇଛି ଓ ଆଉ ଅଧିକ ଆପେଆପେ ଘୁଞ୍ଚାଇଦିଆଯିବ ନାହିଁ ।',
'movelogpage' => 'ଲଗଟିକୁ ଘୁଞ୍ଚାଇବେ',
'movelogpagetext' => 'ତଳେ ଘୁଞ୍ଚାଯାଇଥିବା ସବୁଯାକ ପୃଷ୍ଠାର ତାଲିକା ଦିଆଗଲା ।',
'movesubpage' => '{{PLURAL:$1|ସାନପୃଷ୍ଠା|ସାନପୃଷ୍ଠାମାନ}}',
'movesubpagetext' => 'ଏହି ପୃଷ୍ଠାର $1 {{PLURAL:$1|ଗୋଟି ସାନପୃଷ୍ଠା|ଗୋଟି ସାନପୃଷ୍ଠା}} ତଳେ ଦିଆଗଲା ।',
'movenosubpage' => 'ଏହି ପୃଷ୍ଠାର ଉପପୃଷ୍ଠା ନାହିଁ ।',
'movereason' => 'କାରଣ:',
'revertmove' => 'ପଛକୁ ଫେରାଇବେ',
'delete_and_move' => 'ଲିଭାଇବେ ଓ ଘୁଞ୍ଚାଇବେ',
'delete_and_move_text' => '== ଲିଭାଇବା ଦରକାର ==
ମୁକାମ ପୃଷ୍ଠା "[[:$1]]" ଟି ଆଗରୁ ଅଛି ।
ଆପଣ ଏହାକୁ ଲିଭାଇ ଘୁଞ୍ଚାଇବାକୁ ବାଟ କଢ଼ାଇବାକୁ ଚାହାନ୍ତି କି?',
'delete_and_move_confirm' => 'ହଁ, ଏହି ପୃଷ୍ଠାଟିକୁ ଲିଭାଇଦେବେ',
'delete_and_move_reason' => '"[[$1]]" ପାଇଁ ପଥ ସଳଖ କରିବା ନିମନ୍ତେ ଲିଭାଇଦିଆଗଲା',
'selfmove' => 'ମୂଳ ଓ ମୁକାମ ନାମ ଏକା ଅଟନ୍ତି;
ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇପାରିବୁଁ ନାହିଁ ।',
'immobile-source-namespace' => '"$1" ନେମସ୍ପେସ ଥିବା ପୃଷ୍ଠାକୁ ଘୁଞ୍ଚାଇପାରିବୁଁ ନାହିଁ',
'immobile-target-namespace' => '"$1" ନେମସ୍ପେସ ଥିବା ପୃଷ୍ଠାମାନଙ୍କୁ ଘୁଞ୍ଚାଇପାରିବୁଁ ନାହିଁ',
'immobile-target-namespace-iw' => 'ଇଣ୍ଟରଉଇକି ଲିଙ୍କ ପୃଷ୍ଠା ଘୁଞ୍ଚାଇବା ନିମନ୍ତେ ଏକ ବୈଧ ଲକ୍ଷସ୍ଥଳ ନୁହେଁ ।',
'immobile-source-page' => 'ଏହି ପୃଷ୍ଠାଟି ଘୁଞ୍ଚାଯୋଗ୍ୟ ନୁହେଁ ।',
'immobile-target-page' => 'ଦେହି ମୁକାମ ନାମକୁ ଘୁଞ୍ଚାଇହେବ ନାହିଁ ।',
'imagenocrossnamespace' => 'ଫାଇଲଟିକୁ ଅଣ-ଫାଇଲ ନେମସ୍ପେସକୁ ଘୁଞ୍ଚାଇହେବ ନାହିଁ',
'nonfile-cannot-move-to-file' => 'ଅଣ-ଫାଇଲଟିକୁ ଫାଇଲ ନେମସ୍ପେସକୁ ଘୁଞ୍ଚାଇହେବ ନାହିଁ',
'imagetypemismatch' => 'ନୂଆ ଫାଇଲ ଏକ୍ସଟେନସନ ଏହାର ପ୍ରକାର ସାଙ୍ଗରେ ମେଳ ଖାଉନାହିଁ',
'imageinvalidfilename' => 'ମୁକାମ ପୃଷ୍ଠାର ନାମଟି ଭୁଲ',
'fix-double-redirects' => 'ମୂଳ ନାମ ଆଡ଼କୁ ବାଟକଢ଼ାଉଥିବା ପୁନପ୍ରେରଣକୁ ସତେଜ କରାଇବେ',
'move-leave-redirect' => 'ପଛକୁ ଫେରିବା ପୃଷ୍ଠାଟିଏ ଛାଡ଼ିଯାନ୍ତୁ',
'protectedpagemovewarning' => "'''ଚେତାବନୀ:''' ଏହି ପୃଷ୍ଠାଟିକୁ କିଳାଯାଇଅଛି ଯାହା ଫଳରେ କେବଳ ପରିଛାମାନେ ଏହାକୁ ଘୁଞ୍ଚାଇପାରିବେ ।
ନଗଦ ଇତିହାସ ତଳେ ଆପଣଙ୍କ ଅବଗତି ନିମନ୍ତେ ଦିଆଗଲା ।",
'semiprotectedpagemovewarning' => "'''ଜାଣିରଖନ୍ତୁ:''' ଏହି ପୃଷ୍ଠାଟିକୁ କିଳାଯାଇଅଛି ଯାହା ଫଳରେ କେବଳ ନାମ ଲେଖାଇଥିବା ସଭ୍ୟ ମାନେ ଏହାକୁ ଘୁଞ୍ଚାଇପାରିବେ ।
ଆପଣଙ୍କ ଜାଣିବା ନିମନ୍ତେ ନଗଦ ଲଗ ଇତିହାସ ତଳେ ଦିଆଗଲା:",
'move-over-sharedrepo' => '== ଫାଇଲଟି ଆଗରୁ ଅଛି ==
[[:$1]] ଏକ ବଣ୍ଟାଯାଇଥିବା ଭଣ୍ଡାରରେ ଅଛି । ଏହି ଫାଇଲଟିକୁ ଘୁଞ୍ଚାଇଦେଲେ ତାହା ବଣ୍ଟାଯାଇଥିବା ଫାଇଲ ଉପରେ ମାଡ଼ିଯିବ ।',
'file-exists-sharedrepo' => 'ବ୍ୟବହାର କରାଯାଇଥିବା ଫାଇଲ ନାମଟି, ସଂରକ୍ଷିତ ନାମ ମଧ୍ୟରେ ଆଗରୁ ବ୍ୟବହାର ହୋଇ ସରିଛି ।
ଦୟାକରି  ଅନ୍ୟ ନାମ ବ୍ୟବହାର କରନ୍ତୁ ।',

# Export
'export' => 'ପୃଷ୍ଠାସବୁ ରପ୍ତାନି କରିବେ',
'exporttext' => 'ଆପଣ ଲେଖା ଓ ଏକ ପୃଷ୍ଠାର ସମ୍ପାଦନା ଇତିହାସ ବା ଏକାପ୍ରକାର XML ରେ ଯୋଡ଼ିହୋଇଥିବା ପୃଷ୍ଠାମାନଙ୍କୁ ରପ୍ତାନି କରିପାରିବେ ।
MediaWiki ବ୍ୟବହାର କରି [[Special:Import|ପୃଷ୍ଠା ଆମଦାନି]] ଜରିଆରେ ଏହାକୁ ଆମଦାନି କରାଯାଇପାରିବ ।

ପୃଷ୍ଠା ରପ୍ତାନି କରିବା ନିମନ୍ତେ ତଳେ ଥିବା ଟେକ୍ସଟ ଘରେ ନାମ ଦିଅନ୍ତୁ, ଧାଡ଼ିପ୍ରତି ଗୋଟିଏ ଲେଖାଏଁ ନାମ, ଆଉ ଆପଣ ଏବେକାର ସଂସ୍କରଣ ଚାହୁଁଛନ୍ତି ବା ପୁରୁଣା ସଂସ୍କରଣମାନ ଚାହୁଁଛନ୍ତି, ପୃଷ୍ଠାର ଇତିହାସ ସହ, କିମ୍ବା ଶେଷ ବଦଳ ବାବଦରେ ଏକ ବିବରଣୀ ସହ ଏବେକାର ସଂସ୍କରଣ ଦିଅନ୍ତୁ ।

ଶେଷ କ୍ଷେତ୍ରରେ ଆପଣ ଏକ ଲିଙ୍କ ବ୍ୟବହାର କରିପାରିବେ, ଯଥା ଏକ ପୃଷ୍ଠା ପାଇଁ [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] "[[{{MediaWiki:Mainpage}}]]" ।',
'exportall' => 'ସମସ୍ତ ପୃଷ୍ଠାଗୁଡିକୁ ରପ୍ତାନି କରିବେ',
'exportcuronly' => 'କେବଳ ଏବେକର ସଂସ୍କରଣରେ ଭରିଥାଏ, ପୁରା ଇତିହାସରେ ନୁହେଁ',
'exportnohistory' => "----
'''ସୂଚନା:''' ଦେଖଣାରେ ଅସୁବିଧା ହେବା କାରଣରୁ ପୃଷ୍ଠାର ପୁରା ଇତିହାସ ରପ୍ତାନି କରିବା ଅଚଳ କରାଯାଇଛି ।",
'exportlistauthors' => 'ପ୍ରତି ପୃଷ୍ଠା ନିମନ୍ତେ ଅବଦାନକାରୀଙ୍କର ଏକ ପୁରା ତାଲିକା ରଖିବେ',
'export-submit' => 'ପଠେଇବେ',
'export-addcattext' => 'ଶ୍ରେଣୀରୁ ପୃଷ୍ଠାସବୁକୁ ଯୋଡ଼ନ୍ତୁ:',
'export-addcat' => 'ଯୋଡ଼ିବେ',
'export-addnstext' => 'ଏକ ନେମସ୍ପେସରୁ ପୃଷ୍ଠା ଯୋଡ଼ିବେ:',
'export-addns' => 'ଯୋଡ଼ିବେ',
'export-download' => 'ଫାଇଲ ଭାବରେ ସାଇତିବା',
'export-templates' => 'ଛାଞ୍ଚ ଏହା ଭିତରେ ରଖିବେ',
'export-pagelinks' => 'ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠାର ଗଭୀରତା:',

# Namespace 8 related
'allmessages' => 'ସିଷ୍ଟମ ସନ୍ଦେଶ',
'allmessagesname' => 'ନାମ',
'allmessagesdefault' => 'ଆପେଆପେ ଚିଠିରେ ରହିବା କଥା',
'allmessagescurrent' => 'ଏବେକର ସନ୍ଦେଶ ଲେଖା',
'allmessagestext' => 'ଏଥିରେ ମିଡ଼ିଆଉଇକି ନେମସ୍ପେସରେ ଥିବା ସିଷ୍ଟମ ସନ୍ଦେଶର ଏକ ତାଲିକା ଦିଆଗଲା ।
ଯଦି ଆପଣ ମୂଳ ଦୟାକରି ମିଡ଼ିଆଉଇକି ଆଞ୍ଚଳିକୀକରଣରେ ଭାଗ ନେବା ପାଇଁ ଚାହାନ୍ତି ତେବେ [//www.mediawiki.org/wiki/Localisation ମିଡ଼ିଆଉଇକି ଆଞ୍ଚଳିକୀକରଣ] ଓ [//translatewiki.net translatewiki.net] ଦେଖନ୍ତୁ ।',
'allmessagesnotsupportedDB' => "'''\$wgUseDatabaseMessages''' ଅଚଳ କରାଯାଇଥିବାରୁ ଏହି ପୃଷ୍ଠାଟି ବ୍ୟବହାର କରାଯାଇପାରିବ ନାହିଁ ।",
'allmessages-filter-legend' => 'ଛାଣିବା',
'allmessages-filter' => 'ଆପଣା ପସନ୍ଦରେ ଛାଣିବେ:',
'allmessages-filter-unmodified' => 'ବଦଳାହୋଇନଥିବା',
'allmessages-filter-all' => 'ସବୁ',
'allmessages-filter-modified' => 'ବଦଳାଯାଇଥିବା',
'allmessages-prefix' => 'ଉପସର୍ଗ ଦେଇ ଛଣା:',
'allmessages-language' => 'ଭାଷା:',
'allmessages-filter-submit' => 'ଯିବା',

# Thumbnails
'thumbnail-more' => 'ବିସ୍ତାର',
'filemissing' => 'ଫାଇଲ ମିଳୁନାହିଁ',
'thumbnail_error' => 'ନଖଦେଖଣା ତିଆରିବାରେ ଅସୁବିଧା: $1',
'djvu_page_error' => 'DjVu ପୃଷ୍ଠା ସୀମା ବାହାରେ ରହିଅଛି',
'djvu_no_xml' => 'DjVu ଫାଇଲ ନିମନ୍ତେ XML ଆଣିବାରେ ବିଫଳ ହେଲୁଁ',
'thumbnail-temp-create' => 'ଏକ ଅସ୍ଥାୟୀ ଛୋଟଦେଖଣା ଫାଇଲ ତିଆରି କରିବାରେ ବିଫଳ ହେଲୁ',
'thumbnail-dest-create' => 'ଲକ୍ଷସ୍ଥଳରେ ସାନଦେଖଣା ସାଇତିବାରେ ବିଫଳ ହେଲୁ',
'thumbnail_invalid_params' => 'ଅଚଳ ସାନଦେଖଣା ପାରାମିଟର',
'thumbnail_dest_directory' => 'ମୁକାମ ସୂଚି ତିଆରିବାରେ ବିଫଳ ହେଲୁଁ',
'thumbnail_image-type' => 'ଛବିର ପ୍ରକାର ଅନୁମୋଦିତ ନୁହେଁ',
'thumbnail_gd-library' => 'ଅଧାଗଢ଼ା GD ପାଠାଗାର ସଜାଣି: $1 ମିଳୁନାହିଁ',
'thumbnail_image-missing' => 'ଫାଇଲଟି ନଥିଲା ଭଳି ଲାଗୁଛି : $1',

# Special:Import
'import' => 'ପୃଷ୍ଠା ଆମଦାନି କରିବେ',
'importinterwiki' => 'ଟ୍ରାନ୍ସଉଇକି ଈମ୍ପୋର୍ଟ',
'import-interwiki-text' => 'ଏକ ଉଇକି ଓ ପୃଷ୍ଠା ନାମ ଆମଦାନି କରିବା ନିମନ୍ତେ ଦିଅନ୍ତୁ ।
ସଂସ୍କରଣ ତାରିଖ ଓ ସମ୍ପାଦକଙ୍କ ନାମ ସାଇତା ହୋଇ ରହିବ ।
ଅନ୍ତଉଇକି ଆମଦାନି କାମସବୁ [[Special:Log/import|ଆମଦାନି ଇତିହାସ]]ରେ ସାଇଟ ହୋଇ ରହିଛି ।',
'import-interwiki-source' => 'ମୂଳ ଉଇକି/ପୃଷ୍ଠା',
'import-interwiki-history' => 'ଏହି ପୃଷ୍ଠା ନିମନ୍ତେ ସବୁଯାକ ସଂସ୍କରଣ ଇତିହାସ ନକଲ କରିନିଅନ୍ତୁ',
'import-interwiki-templates' => 'ସବୁଯାକ ଛାଞ୍ଚ ଏହା ଭିତରେ ରଖିବେ',
'import-interwiki-submit' => 'ଆମଦାନୀ',
'import-interwiki-namespace' => 'ଲକ୍ଷ ନେମସ୍ପେସ:',
'import-interwiki-rootpage' => 'ଲକ୍ଷସ୍ଥଳୀର ମୂଳ ପୃଷ୍ଠା(ଇଛାଧୀନ):',
'import-upload-filename' => 'ଫାଇଲ ନାମ:',
'import-comment' => 'ମତାମତ:',
'importtext' => '[[Special:Export|ରପ୍ତାନି ସୁବିଧା]] ବ୍ୟବହାର କରି ମୂଳ ଉଇକିରୁ ଫାଇଲଟି ରପ୍ତାନି କରନ୍ତୁ ।
ତାହାକୁ ଆପଣା କମ୍ପୁଟରରେ ସାଇତି ଏଠାରେ ଅପଲୋଡ଼ କରନ୍ତୁ ।',
'importstart' => 'ପୃଷ୍ଠା ଆମଦାନି କରୁଛୁ...',
'import-revision-count' => '$1 ଗୋଟି {{PLURAL:$1|ସଂସ୍କରଣ|ସଂସ୍କରଣ}}',
'importnopages' => 'ଆମଦାନି କରିବା ନିମନ୍ତେ ପୃଷ୍ଠା ନାହିଁ ।',
'imported-log-entries' => '$1 ଗୋଟି {{PLURAL:$1|ଇତିହାସ|ଇତିହାସ}}ର ନିବେଶ ଆମଦାନୀ କରାଗଲା ।',
'importfailed' => 'ଆମଦାନି ବିଫଳ ହେଲା: <nowiki>$1</nowiki>',
'importunknownsource' => 'ଅଜଣା ଆମଦାନୀ ମୂଳାଧାର ପ୍ରକାର',
'importcantopen' => 'ଆହରଣ ଫାଇଲଟି ଖୋଲି ପାରିଲୁ ନାହିଁ',
'importbadinterwiki' => 'ଖରାପ ଇଣ୍ଟରଉଇକି ଲିଙ୍କ',
'importnotext' => 'ଖାଲି ବା କିଛି ଲେଖା ନାହିଁ',
'importsuccess' => 'ଆହରଣ ଶେଷ ହେଲା!',
'importhistoryconflict' => 'ଦ୍ଵନ୍ଦ ଉପୁଜାଇବା ଭଳି ଇତିହାସ ସଂସ୍କରଣଟିଏ ଅଛି (ବୋଧେ ଏ ପୃଷ୍ଠାଟିକୁ ଆଗରୁ ଆମଦାନୀ କରାଯାଇଥିବ)',
'importnosources' => 'କୌଣସିଟି ଟ୍ରାନ୍ସଉଇକି ଆମଦାନି ମୂଳାଧାର ସ୍ଥିର କରାଯାଇନାହିଁ ଓ  ସିଧା ଇତିହାସ ଅପଲୋଡ଼ କରିବା ଅଚଳ କରାଯାଇଛି ।',
'importnofile' => 'କୌଣସିଟି ଆମଦାନି ଫାଇଲ ଅପଲୋଡ଼ କରାଯାଇନାହିଁ ।',
'importuploaderrorsize' => 'ଫାଇଲ ଆମଦାନି ବିଫଳ ହେଲା ।
ଏହି ଫାଇଲଟି ଅନୁମୋଦିତ ଅପଲୋଡ଼ ଫାଇଲ ଆକାର ଠାରୁ ବଡ଼ ।',
'importuploaderrorpartial' => 'ଫାଇଲ ଆମଦାନି ବିଫଳ ହେଲା ।
ଏହି ଫାଇଲଟିର କିଛି ଭାଗ ଅପଲୋଡ଼ ହୋଇଛି ।',
'importuploaderrortemp' => 'ଫାଇଲ ଆମଦାନି ବିଫଳ ହେଲା ।
ଅସ୍ଥାୟୀ ଫୋଲଡରଟିଏ ନାହିଁ ।',
'import-parse-failure' => 'XML ଆମଦାନି ପାର୍ସ ବିଫଳ',
'import-noarticle' => 'ଆମଦାନି କରିବା ନିମନ୍ତେ ପୃଷ୍ଠା ନାହିଁ !',
'import-nonewrevisions' => 'ସବୁଯାକ ସଂସ୍କରଣ ଆଗରୁ ଆମଦାନି କରାସରିଛି ।',
'xml-error-string' => '$1 $2 ଧାଡ଼ିରେ ଅଛି, $3 ସ୍ତମ୍ଭ ($4 ବାଇଟ): $5',
'import-upload' => 'XML ତଥ୍ୟ ଅପଲୋଡ଼',
'import-token-mismatch' => 'ଅବଧି ତଥ୍ୟ ଲୋପପାଇଗଲାଣି ।
ଦୟାକରି ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'import-invalid-interwiki' => 'ଦିଆଯାଇଥିବା ଉଇକିରୁ ଆହରଣ କରାଯାଇପାରିବ ନାହିଁ ।',
'import-error-edit' => '"$1" ପୃଷ୍ଠାଟି ଅଣାଯାଇନାହିଁ କାରଣ ଆପଣଙ୍କର ଏହାକୁ ବଦଳାଇବା ଅଧିକାର ନାହିଁ ।',
'import-error-create' => '"$1" ପୃଷ୍ଠାଟି ଅଣାଯାଇନାହିଁ କାରଣ ଆପଣଙ୍କର ଏହାକୁ ତିଆରିକରିବା ଅଧିକାର ନାହିଁ ।',
'import-error-interwiki' => '"$1"ପୃଷ୍ଠାକୁ ଆମଦାନୀ କରିହେଲାନି କାରଣ ଏହାର ନାମ ବାହାରଲିଙ୍କରେ ଆଗରୁ ଅଛି(ଉଇକିଗୁଡିକ ମଧ୍ୟରେ) ।',
'import-error-special' => '"$1"ପୃଷ୍ଠାକୁ ଆମଦାନୀ କରିହେଲାନି କାରଣ ଏହା ଏକ ବିଶେଷ ନେମସ୍ପେସରେ ଅଛି ଯାହା ପୃଷ୍ଠାଗୁଡିକୁ ଅନୁମତି ଦିଏ ନାହିଁ ।',
'import-error-invalid' => '"$1"ପୃଷ୍ଠାକୁ ଆମଦାନୀ କରିହେଲାନି କାରଣ ଏହାର ନାମଟି ଅବୈଧ ।',
'import-options-wrong' => 'ଭୁଲ {{PLURAL:$2|option|options}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'ଦିଆଯାଇଥିବା ମୂଳ ପୃଷ୍ଠାର ଶୀର୍ଷକଟି ଅବୈଧ ଅଟେ ।',
'import-rootpage-nosubpage' => 'ମୂଳ ପୃଷ୍ଠାର "$1" ନେମସ୍ପେସ ଉପପୃଷ୍ଠାର ଅନୁମତି ଦିଏନାହିଁ ।',

# Import log
'importlogpage' => 'ଇତିହାସ ଆହରଣ',
'importlogpagetext' => 'ଅନ୍ୟ ଉଇକିରୁ ପରିଛାଙ୍କ ଦେଇ ସମ୍ପାଦନା ଇତିହାସ ସହ କରାହୋଇଥିବା ପୃଷ୍ଠା ଆମଦାନି ।',
'import-logentry-upload' => 'ଫାଇଲ ଅପଲୋଡ଼ ଦେଇ [[$1]] ଆମଦାନି କରାଯାଇଛି',
'import-logentry-upload-detail' => '$1 ଗୋଟି {{PLURAL:$1|ସଂସ୍କରଣ|ସଂସ୍କରଣ}}',
'import-logentry-interwiki' => '$1 କୁ ଟ୍ରାନ୍ସଉଇକି କରାଗଲା',
'import-logentry-interwiki-detail' => '$2 ଭିତରୁ $1 ଗୋଟି {{PLURAL:$1|ସଂସ୍କରଣ|ସଂସ୍କରଣ}}',

# JavaScriptTest
'javascripttest' => 'ଜାଭାସ୍କ୍ରିପ୍ଟ ପରଖ',
'javascripttest-disabled' => 'ଏହି ଉଇକିରେ ଏହି ବ୍ୟବସ୍ଥାଟିକୁ ସଚଳ କରାଯାଇନାହି ।',
'javascripttest-title' => 'ଚାଲୁଥିବା $1 ପରଖଗୁଡିକ',
'javascripttest-pagetext-noframework' => 'ଏହି ପୃଷ୍ଠାଟି ଜାଭାସ୍କ୍ରିପ୍ଟ ପରଖ ପାଇଁ ସଂରକ୍ଷଣ କରି ରଖାଯାଇଛି ।',
'javascripttest-pagetext-unknownframework' => '"$1" ଅଜଣା ପରଖ ଗତିବିଧି ।',
'javascripttest-pagetext-frameworks' => 'ଦୟାକରି ନିମ୍ନରେ ଥିବା ଏକ ପରଖ ପ୍ରକ୍ରିୟାକୁ ବାଛନ୍ତୁ :$1',
'javascripttest-pagetext-skins' => 'ଏହି ପରଖକୁ ଚାଲୁ କରିବା ପାଇଁ ଏକ ଆବରଣ ବାଛନ୍ତୁ ।',
'javascripttest-qunit-intro' => 'mediawiki.orgରେ [$1 testing documentation]କୁ ଦେଖନ୍ତୁ ।',
'javascripttest-qunit-heading' => 'ମେଡିଆଉଇକି ଜାଭାସ୍କ୍ରିପ୍ଟ Qunit ପରଖ ପ୍ରକ୍ରିୟା',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'ଆପଣଙ୍କ ବ୍ୟବହାରକାରୀ ପୃଷ୍ଠା',
'tooltip-pt-anonuserpage' => 'ଆପଣ ଯେଉଁ IP ଠିକଣାର ବ୍ୟବହାରକାରୀ ପୃଷ୍ଠାଟି ବଦଳାଇବା ପାଇଁ ଚେଷ୍ଟା କରୁଛନ୍ତି',
'tooltip-pt-mytalk' => 'ଆପଣଙ୍କ ଆଲୋଚନା ପୃଷ୍ଠା',
'tooltip-pt-anontalk' => 'ଏହି IP ଠିକଣାରୁ କେହିଜଣେ କରିଥିବା ସମ୍ପାଦନାର ଆଲୋଚନା',
'tooltip-pt-preferences' => 'ମୋ ପସନ୍ଦ',
'tooltip-pt-watchlist' => 'ବଦଳ ପାଇଁ ଆପଣ ଦେଖାଶୁଣା କରୁଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକର ତାଲିକା',
'tooltip-pt-mycontris' => 'ଆପଣଙ୍କ ଅବଦାନ',
'tooltip-pt-login' => 'ଆପଣଙ୍କୁ ଲଗଇନ କରିବାକୁ କୁହାଯାଉଅଛି ସିନା, ବାଧ୍ୟ କରାଯାଉନାହିଁ',
'tooltip-pt-anonlogin' => 'ଆପଣଙ୍କୁ ଲଗଇନ କରିବାକୁ କୁହାଯାଉଅଛି ସିନା, ବାଧ୍ୟ କରାଯାଉନାହିଁ',
'tooltip-pt-logout' => 'ଲଗଆଉଟ',
'tooltip-ca-talk' => 'ଏହି ପୃଷ୍ଠାଟି ଉପରେ ଆଲୋଚନା',
'tooltip-ca-edit' => 'ଆପଣ ଏହି ପୃଷ୍ଠାଟିରେ ଅଦଳ ବଦଳ କରିପାରିବେ, ତେବେ ସାଇତିବା ଆଗରୁ ଦେଖଣା ଦେଖନ୍ତୁ ।',
'tooltip-ca-addsection' => 'ନୂଆ ବିଭାଗଟିଏ ଆରମ୍ଭ କରିବେ',
'tooltip-ca-viewsource' => 'ଏହି ପୃଷ୍ଠାଟି କିଳାଯାଇଛି ।
ଆପଣ ଏହାର ମୂଳ ଦେଖିପାରିବେ',
'tooltip-ca-history' => 'ଏହି ପୃଷ୍ଠାର ପୁରୁଣା ସଂସ୍କରଣ',
'tooltip-ca-protect' => 'ଏହି ପୃଷ୍ଠାଟିକୁ କିଳିବେ',
'tooltip-ca-unprotect' => 'ଏହି ପୃଷ୍ଠା ପାଇଁ ସୁରକ୍ଷାର ପ୍ରକାର ବଦଳାଇବେ',
'tooltip-ca-delete' => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇବେ',
'tooltip-ca-undelete' => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇଦିଆଯିବା ଆଗରୁ ଏଥିରେ ହୋଇଥିବା ସମ୍ପାଦନାସମୂହକୁ ପୁନସ୍ଥାପନ କରନ୍ତୁ',
'tooltip-ca-move' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'tooltip-ca-watch' => 'ଆପଣଙ୍କ ଦେଖାତାଲିକାରେ ଏଇ ପୃଷ୍ଠାଟି ମିଶାଇବେ',
'tooltip-ca-unwatch' => 'ନିଜ ଦେଖଣାତାଲିକାରୁ ଏହି ପୃଷ୍ଠାଟି ବାହାର କରିଦେବେ',
'tooltip-search' => '{{SITENAME}} ରେ ଖୋଜିବା',
'tooltip-search-go' => 'ଏହି ଅବିକଳ ନାଆଁଟି ଥିଲେ ସେହି ପୃଷ୍ଠାକୁ ଯିବା',
'tooltip-search-fulltext' => 'ଏହି ଲେଖାଟି ପାଇଁ ପୃଷ୍ଠାସବୁକୁ ଖୋଜିବା',
'tooltip-p-logo' => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'tooltip-n-mainpage' => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'tooltip-n-mainpage-description' => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'tooltip-n-portal' => 'ଏହି ପ୍ରକଳ୍ପଟିରେ ଖୋଜା ଖୋଜି ପାଇଁ ଆପଣ କେମିତି ସାହାଯ୍ୟ କରିପାରିବେ',
'tooltip-n-currentevents' => 'ନଗଦ କାମର ପଛପଟେ ଚାଲିଥିବା କାମର ତଥ୍ୟ',
'tooltip-n-recentchanges' => 'ବିକିରେ ଏହିମାତ୍ର କରାଯାଇଥିବା ଅଦଳ ବଦଳ',
'tooltip-n-randompage' => 'ଯାହିତାହି ପୃଷ୍ଠାଟିଏ ଖୋଲ',
'tooltip-n-help' => 'ଖୋଜି ପାଇବା ଭଳି ଜାଗା',
'tooltip-t-whatlinkshere' => 'ଏଠାରେ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠାସବୁର ତାଲିକା',
'tooltip-t-recentchangeslinked' => 'ଏହି ପୃଷ୍ଠା ସଙ୍ଗେ ଯୋଡ଼ା ଫରଦଗୁଡ଼ିକରେ ଏବେ ଏବେ କରାଯାଇଥିବା ଅଦଳବଦଳ',
'tooltip-feed-rss' => 'ଏହି ପୃଷ୍ଠାଟି ପାଇଁ RSS ଫିଡ଼',
'tooltip-feed-atom' => 'ଏହି ପୃଷ୍ଠାଟି ପାଇଁ ଆଟମ ଫିଡ଼',
'tooltip-t-contributions' => 'ଏହି ଇଉଜରଙ୍କର ଦେଇ କରାଯାଇଥିବା ସବୁଯାକ ଦାନ ଦେଖାଇବା',
'tooltip-t-emailuser' => 'ଏହି ସଭ୍ୟଙ୍କୁ ଇ-ମେଲଟିଏ ପଠାଇବେ',
'tooltip-t-upload' => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'tooltip-t-specialpages' => 'ବିଶେଷ ପୃଷ୍ଠାମାନଙ୍କର ଏକ ତାଲିକା',
'tooltip-t-print' => 'ଏହି ପୃଷ୍ଠାର ଛପାହୋଇପାରିବା ସଙ୍କଳନ',
'tooltip-t-permalink' => 'ବଦଳାଯାଇଥିବା ଏହି ଫରଦଟିର ସ୍ଥାୟୀ ଲିଙ୍କ',
'tooltip-ca-nstab-main' => 'ସୂଚୀ ପୃଷ୍ଠାଟି ଦେଖାଇବେ',
'tooltip-ca-nstab-user' => 'ଫାଇଲ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'tooltip-ca-nstab-media' => 'ମିଡ଼ିଆ ପୃଷ୍ଠାଟି ଦେଖିବେ',
'tooltip-ca-nstab-special' => 'ଏହା ଗୋଟିଏ ବିଶେଷ ପୃଷ୍ଠା, ଆପଣ ଏହାକୁ ବଦଳାଇପାରିବେ ନାହିଁ',
'tooltip-ca-nstab-project' => 'ପ୍ରକଳ୍ପ ପୃଷ୍ଠାଟି ଦେଖାଇବେ',
'tooltip-ca-nstab-image' => 'ଫାଇଲ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'tooltip-ca-nstab-mediawiki' => 'ସିଷ୍ଟମ ମେସେଜ ଦେଖିବେ',
'tooltip-ca-nstab-template' => 'ଟେମ୍ପଲେଟଟି ଦେଖିବା',
'tooltip-ca-nstab-help' => 'ସହାଯୋଗ ପୃଷ୍ଠାଟି ଦେଖନ୍ତୁ',
'tooltip-ca-nstab-category' => 'ଶ୍ରେଣୀ ପୃଷ୍ଠାଟିକୁ ଦେଖାଇବେ',
'tooltip-minoredit' => 'ଏହାକୁ ଏକ ଛୋଟ ବଦଳ ଭାବେ ଗଣିବେ',
'tooltip-save' => 'ବଦଳଗୁଡ଼ିକ ସାଇତିବେ',
'tooltip-preview' => 'ଆପଣନ୍କ ବଦଳ ଦେଖିନିଅନ୍ତୁ, ସାଇତିବା ଆଗରୁ ଏହା ବ୍ୟ୍ଅବହାର କରନ୍ତୁ!',
'tooltip-diff' => 'ଏହି ଲେଖାରେ ଆପଣ କରିଥିବା ବଦଳଗୁଡିକୁ ଦେଖନ୍ତୁ ।',
'tooltip-compareselectedversions' => 'ଏହି ଫରଦର ଦୁଇଟି ବଛାଯାଇଥିବା ସଁକଳନକୁ ତଉଲିବା',
'tooltip-watch' => 'ଆପଣଙ୍କ ଦେଖାତାଲିକାରେ ଏଇ ପୃଷ୍ଠାଟି ମିଶାଇବେ',
'tooltip-watchlistedit-normal-submit' => 'ଶିରୋନାମାଗୁଡିକୁ ଲିଭାଇବେ',
'tooltip-watchlistedit-raw-submit' => 'ଦେଖଣା ତାଲିକାକୁ ଅପଡ଼େଟ କରିବେ',
'tooltip-recreate' => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇଦିଆଯାଇଥିଲେ ବି ଆଉଥରେ ତିଆରି କରନ୍ତୁ',
'tooltip-upload' => 'ଅପଲୋଡ଼ କରନ୍ତୁ',
'tooltip-rollback' => '"ଫେରିବା" ଏହି ଫରଦରେ ଶେଷ ଦାତାଙ୍କ ଦେଇ କରାଯାଇଥିବା ସବୁଯାକ ବଦଳକୁ  ଏକାଥରକରେ ପଛକୁ ଫେରାଇଦେବ',
'tooltip-undo' => '"କରନାହିଁ" ଆଗରୁ କରାଯାଇଥିବା ବଦଳଟିକୁ ପଛକୁ ଲେଉଟାଇଦିଏ ଆଉ ବଦଳ ଫରମଟିକୁ ଦେଖଣା ଭାବରେ ଖୋଲେ । ଏହା ଆପଣଙ୍କୁ ସାରକଥାରେ ଗୋଟିଏ କାରଣ ଲେଖିବାକୁ ଅନୁମତି ଦିଏ ।',
'tooltip-preferences-save' => 'ଆପଣା ପସନ୍ଦ ସାଇତିବେ',
'tooltip-summary' => 'ଛୋଟ ସାରକଥାଟିଏ ଦିଅନ୍ତୁ',

# Metadata
'notacceptable' => 'ଆପଣଙ୍କ ସହଯୋଗୀ ପଢ଼ିପାରିବା ଢଙ୍ଗରେ ଉଇକି ସର୍ଭର ତଥ୍ୟ ଦେଇପାରିବ ନାହିଁ ।',

# Attribution
'anonymous' => '{{SITENAME}}ର ଅଜଣା {{PLURAL:$1|ଜଣ ବ୍ୟବହାରକାରୀ |ଜଣ ବ୍ୟବହାରକାରୀଗଣ}}',
'siteuser' => '{{SITENAME}} ବ୍ୟବହାରକାରୀ $1',
'anonuser' => '{{SITENAME}} ବେନାମି ବ୍ୟବହାରକାରୀ $1',
'lastmodifiedatby' => 'ଏହି ପୃଷ୍ଠାଟି $3ଙ୍କ ଦେଇ $1 ତାରିଖ $2 ବେଳେ ବଦଳାଯାଇଥିଲା ।',
'othercontribs' => '$1ଙ୍କ କାମ ଉପରେ ପର୍ଯ୍ୟବସିତ ।',
'others' => 'ବାକିସବୁ',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|ବ୍ୟବହାରକାରୀ|ବ୍ୟବହାରକାରୀଗଣ}} $1',
'anonusers' => '{{SITENAME}} ବେନାମି {{PLURAL:$2|ଜଣ ବ୍ୟବହାରକାରୀ|ଜଣ ବ୍ୟବହାରକାରୀ}} $1',
'creditspage' => 'ପୃଷ୍ଠା ଶ୍ରେୟ',
'nocredits' => 'ଏହି ପୃଷ୍ଠା ପାଇଁ ଶ୍ରେୟ ତଥ୍ୟ ନାହିଁ ।',

# Spam protection
'spamprotectiontitle' => 'ସ୍ପାମ ସୁରକ୍ଷା ଛଣା ବଳୟ',
'spamprotectiontext' => 'ସ୍ପାମ ଛଣା ବ୍ୟବସ୍ଥା ଦେଇ ଆପଣ ସାଇତିବାକୁ ଚେଷ୍ଟାକରୁଥିବା ଲେଖାଟି ଅଟକାଯାଇଛି ।
ଏହା ବୋଧେ ଏକ ବାସନ୍ଦ କରାଯାଇଥିବା ବାହାର ସାଇଟର ଲିଙ୍କ ପାଇଁ ହୋଇଛି ।',
'spamprotectionmatch' => 'ତଳଲିଖିତ ଲେଖାଟି ଆମ୍ଭର ସ୍ପାମ ଛଣାରେ ଅସୁବିଧା ତିଆରି କରିଥିଲା: $1',
'spambot_username' => 'ମିଡ଼ିଆଉଇକି ସ୍ପାମ ସଫାକରିବା',
'spam_reverting' => '$1 ସହ ଯୋଡ଼ା ନଥିବା ଶେଷ ସଂସ୍କରଣକୁ ଲେଉଟାଇ ଦେଉଅଛୁଁ',
'spam_blanking' => '$1 ସହ ଯୋଡ଼ାଥିବା ସବୁଯାକ ସଂସ୍କରଣ ଖାଲି କରିଦିଆଗଲା',
'spam_deleting' => '$1 ସହ ଯୋଡ଼ାଥିବା ସବୁଯାକ ସଂସ୍କରଣ ଖାଲି କରିଦିଆଗଲା',

# Info page
'pageinfo-title' => '"$1"ର ବିବରଣୀ',
'pageinfo-not-current' => 'ଦୁଖିତଃ, ପୁରୁଣା ସଂସ୍କରଣଗୁଡିକର ଏହି ତଥ୍ୟ ଦେବା ସମ୍ଭବ ନୁହେଁ ।',
'pageinfo-header-basic' => 'ସାଧାରଣ ଜାଣିବା କଥା',
'pageinfo-header-edits' => 'ବଦଳ ଇତିହାସ',
'pageinfo-header-restrictions' => 'ପୃଷ୍ଠା ସୁରକ୍ଷା',
'pageinfo-header-properties' => 'ପୃଷ୍ଠା ସବିଶେଷ',
'pageinfo-display-title' => 'ଶୀର୍ଷକ ଦେଖାଇବେ',
'pageinfo-default-sort' => 'ପୂର୍ବରୁଥିବା ସଜାଇବା ଚାବି',
'pageinfo-length' => 'ପୃଷ୍ଠା ଲମ୍ବ(ବାଇଟରେ)',
'pageinfo-article-id' => 'ପୃଷ୍ଠା ଆଇଡ଼ି',
'pageinfo-robot-policy' => 'ଖୋଜିବା ଇଞ୍ଜିନ ସ୍ଥିତି',
'pageinfo-robot-index' => 'ସୂଚୀପତ୍ର କରିହେଉଥିବା',
'pageinfo-robot-noindex' => 'ସୂଚୀପତ୍ର କରିହେଉନଥିବା',
'pageinfo-views' => 'ଦେଖଣା ସଂଖ୍ୟା',
'pageinfo-watchers' => 'ପୃଷ୍ଠା ଦେଖଣାହାରି ସଂଖ୍ୟା',
'pageinfo-redirects-name' => 'ଏହି ପୃଷ୍ଠାକୁ ଲେଉଟାଣି ଅଛି',
'pageinfo-subpages-name' => 'ଏହି ପୃଷ୍ଠାରେ ଥିବା ଉପପୃଷ୍ଠା',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|redirect|redirects}}; $3 {{PLURAL:$3|non-redirect|non-redirects}})',
'pageinfo-firstuser' => 'ପୃଷ୍ଠା ତିଆରିକରିଛନ୍ତି',
'pageinfo-firsttime' => 'ପୃଷ୍ଠା ତିଆରି କରିବା ତାରିଖ',
'pageinfo-lastuser' => 'ନୂତନତମ ବଦଳକାରୀ',
'pageinfo-lasttime' => 'ନୂତନତମ ବଦଳର ତାରିଖ',
'pageinfo-edits' => 'ସମ୍ପାଦନା ସଂଖ୍ୟା',
'pageinfo-authors' => 'ନିଆରା ଲେଖକଙ୍କ ମୋଟସଂଖ୍ୟା',
'pageinfo-recent-edits' => 'ନଗଦବଦଳ ସଂଖ୍ୟା($1 ମଧ୍ୟରେ)',
'pageinfo-recent-authors' => 'ନିଆରା ଲେଖକଙ୍କ ମୋଟସଂଖ୍ୟା',
'pageinfo-magic-words' => 'ଚମତ୍କାର {{PLURAL:$1|word|words}} ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|category|categories}} ($1) ଲୁଚାଗଲା',
'pageinfo-templates' => '{{PLURAL:$1|template|templates}} ($1) ଯୋଡିହେଇଥିବା',

# Patrolling
'markaspatrolleddiff' => 'ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କରାଗଲା',
'markaspatrolledtext' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କରିବେ',
'markedaspatrolled' => 'ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କରାଗଲା',
'markedaspatrolledtext' => 'ବଛାଯାଇଥିବା [[:$1]]ତମ ସଂସ୍କରଣଟି ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କରାଗଲା ।',
'rcpatroldisabled' => 'ନଗଦ ବଦଳରେ ଜଗିବା ଅଚଳ କରାଯାଇଅଛି',
'rcpatroldisabledtext' => 'ନଗଦ ବଦଳ ଜଗିବା ସୁବିଧାଟି ଏବେ ଅଚଳ କରାଯାଇଅଛି ।',
'markedaspatrollederror' => 'ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କରିପାରୁଲୁଁ ନାହିଁ',
'markedaspatrollederrortext' => 'ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କରିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଏକ ସଂସ୍କରଣ ଦେବାକୁ ପଡ଼ିବ ।',
'markedaspatrollederror-noautopatrol' => 'ଆପଣ ନିଜର ସମ୍ପାଦନାସବୁକୁ ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କରିପାରିବେ ନାହିଁ ।',

# Patrol log
'patrol-log-page' => 'ଜଗିବା ଇତିହାସ',
'patrol-log-header' => 'ଏହା ଏକ ଜଗାଯାଇଥିବା ସଂସ୍କରଣର ଇତିହାସ ।',
'log-show-hide-patrol' => '$1 ଜଗିବା ଇତିହାସ',

# Image deletion
'deletedrevision' => 'ଲିଭାଯାଇଥିବା ପୁରୁଣା $1',
'filedeleteerror-short' => 'ଫାଇଲ ଲିଭାଇବାରେ ଅସୁବିଧା: $1',
'filedeleteerror-long' => 'ତଳଲିଖିତ ଫାଇଲକୁ ଲିଭାଇବାରେ ଅସୁବିଧା:

$1',
'filedelete-missing' => '"$1" ଫାଇଲଟି ଲିଭାଯାଇପାରିବ ନାହି କାରଣ ଏହା ବର୍ତମାନ ନହିଁ ।',
'filedelete-old-unregistered' => 'ପୁନରାବୃତ୍ତି ପାଇଁ  ଦିଆଯାଇଥିବା "$1" ଫାଇଲଟି ସଂଗୃହୀତ ତଥ୍ୟ ଭିତରେ  ନାହିଁ ।',
'filedelete-current-unregistered' => 'ଦିଆଯାଇଥିବା "$1" ଫାଇଲଟି ଡାଟାବେସ ଭିତରେ  ନାହିଁ ।',
'filedelete-archive-read-only' => 'ଅଭିଲେଖ ସୂଚିପତ୍ର "$1" ୱେବସର୍ଭର ଦେଇ ଲେଖିବା ସମ୍ଭବ ନୁହେଁ ।',

# Browsing diffs
'previousdiff' => '← ପୁରୁଣା ବଦଳ',
'nextdiff' => 'ନୂଆ ବଦଳ →',

# Media information
'mediawarning' => "'''ଚେତାବନୀ''': ଏହି ଫାଇଲରେ ଅସୁବିଧାକାରୀ କୋଡ଼ ଥାଇପାରେ ।
ଏହାକୁ ଆଗକୁ ବଢ଼ାଇଲେ ଆପଣଙ୍କ କମ୍ପୁଟରରେ ଅସୁବିଧା ହୋଇପାରେ ।",
'imagemaxsize' => "ଛବି ଆକାର ସୀମା:<br />''(ଫାଇଲ ବିବରଣୀ ପୃଷ୍ଠାମାନଙ୍କ ନିମନ୍ତେ)''",
'thumbsize' => 'ନଖଦେଖଣା ଆକାର:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|ଗୋଟି ପୃଷ୍ଠା|ଗୋଟି ପୃଷ୍ଠା}}',
'file-info' => 'ଫାଇଲ ଆକାର: $1, MIME ପ୍ରକାର: $2',
'file-info-size' => '$1 × $2 ପିକସେଲ, ଫାଇଲ ଆକାର: $3, ଏମ.ଆଇ.ଏମ.ଇର ପ୍ରକାର: $4',
'file-info-size-pages' => '$1 × $2 ପିକ୍ସେଲ, ଫାଇଲ ଆକାର: $3, MIME ପ୍ରକାର: $4, $5 ଗୋଟି {{PLURAL:$5|ପୃଷ୍ଠା|ପୃଷ୍ଠା}}',
'file-nohires' => 'ବଡ଼ ରେଜୋଲୁସନ ନାହିଁ ।',
'svg-long-desc' => 'SVG ଫାଇଲ, ସାଧାରଣ ମାପ $1 × $2 ପିକ୍ସେଲ, ଫାଇଲ ଆକାର: $3',
'svg-long-desc-animated' => 'Animated SVG ଫାଇଲ, ସାଧାରଣ ମାପ $1 × $2 ପିକ୍ସେଲ, ଫାଇଲ ଆକାର: $3',
'show-big-image' => 'ପୁରା ବଡ଼ ଆକାରରେ',
'show-big-image-preview' => 'ଏହି ଦେଖଣାର ଆକାର: $1 ।',
'show-big-image-other' => 'ବାକି {{PLURAL:$2|ରେଜୋଲୁସନ|ରେଜୋଲୁସନ}}: $1.',
'show-big-image-size' => '$1 × $2 ପିକ୍ସେଲ',
'file-info-gif-looped' => 'ଲୁପ',
'file-info-gif-frames' => '$1 ଗୋଟି {{PLURAL:$1|ଫ୍ରେମ|ଫ୍ରେମ}}',
'file-info-png-looped' => 'ଲୁପ ଥିବା',
'file-info-png-repeat' => '$1 {{PLURAL:$1|ଥରେ|ଥର}} ଖେଳିଲେ',
'file-info-png-frames' => '$1 ଗୋଟି {{PLURAL:$1|ଫ୍ରେମ|ଫ୍ରେମ}}',
'file-no-thumb-animation' => "'''ଟୀକା:ଯାନ୍ତ୍ରିକ ସୀମାରେଖା ଯୋଗୁ, ଏହି ଫାଇଲର ଛୋଟଛବିଗୁଡିକ ଦୋଳାୟମାନ ହେବନାହିଁ ।'''",
'file-no-thumb-animation-gif' => "'''ଟୀକା:ଯାନ୍ତ୍ରିକ ସୀମାରେଖା ଯୋଗୁ, ଏହି ଫାଇଲଭଳି ଅଧିକ ଆକାରଥିବା GIF ଛବିଗୁଡିକର ଛୋଟଛବିଗୁଡିକ ଦୋଳାୟମାନ ହେବନାହିଁ ।'''",

# Special:NewFiles
'newimages' => 'ନୁତନ ଫାଇଲଗୁଡିକର ଗ୍ୟାଲେରୀ',
'imagelisttext' => "ତଳେ '''$1''' ଗୋଟି {{PLURAL:$1|ଫାଇଲ|ଫାଇଲ}}ର ତାଲିକା $2 ରେ ଦିଆଯାଇଛି ।",
'newimages-summary' => 'ଏହି ବିଶେଷ ପୃଷ୍ଠାଟି ଶେଷ ଅପଲୋଡ଼ ହୋଇଥିବା ଫାଇଲମାନ ଦେଖାଇଥାଏ ।',
'newimages-legend' => 'ଛାଣିବା',
'newimages-label' => 'ଫାଇଲ ନାମ (କିମ୍ବା ତାହାର ଏକ ଭାଗ):',
'showhidebots' => '($1 ଜଣ ବଟ)',
'noimages' => 'ଦେଖିବାକୁ କିଛି ନାହିଁ ।',
'ilsubmit' => 'ଖୋଜିବା',
'bydate' => 'ତାରିଖ ଅନୁସାରେ',
'sp-newimages-showfrom' => '$2, $1 ରେ ଆରମ୍ଭ ହେଉଥିବା ନୂଆ ଫାଇଲ ସବୁ ଦେଖାଇବେ',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 ସେକେଣ୍ଡ|$1 ସେକେଣ୍ଡ}}',
'minutes' => '{{PLURAL:$1|$1 ମିନିଟ|$1 ମିନିଟ}}',
'hours' => '{{PLURAL:$1|$1 ଘଣ୍ଟା|$1 ଘଣ୍ଟା}}',
'days' => '{{PLURAL:$1|$1 ଦିନ|$1 ଦିନ}}',
'ago' => '$1 ଆଗରୁ',

# Bad image list
'bad_image_list' => 'ଗଢ଼ଣଟି ଏମିତି ହେବ:

କେବଳ (ଯେଉଁ ଧାଡ଼ିଗୁଡ଼ିକ * ରୁ ଆରମ୍ଭ ହୋଇଥାଏ) ସେହି ସବୁକୁ ହିସାବକୁ ନିଆଯିବ ।
ଗୋଟିଏ ଧାଡ଼ିର ପ୍ରଥମ ଲିଙ୍କଟି ଗୋଟିଏ ଖରାପ ଫାଇଲର ଲିଙ୍କ ହୋଇଥିବା ଦରକାର ।
ପ୍ରଥମ ଲିଙ୍କ ପରର ସବୁ ଲିଙ୍କକୁ ନିଆରା ବୋଲି ଧରାଯିବ । ମାନେ, ସେଇସବୁ ପୃଷ୍ଠାଦରେ ଯେଉଁଠି ଫାଇଲଟି ଧାଡ଼ି ଭିତରେ ରହିଥିବ ।',

# Metadata
'metadata' => 'ମେଟାଡାଟା',
'metadata-help' => 'ଏହି ଫରଦଟିରେ ଗୁଡ଼ାଏ ଅଧିକ କଥା ଅଛି, ବୋଧହୁଏ ଡିଜିଟାଲ କାମେରା କିମ୍ବା ସ୍କାନରରେ ନିଆଯାଇଛି । ଯଦି ଫାଇଲଟି ତାର ମୂଳ ଭାଗଠୁ ବଦଳାଜାଇଥାଏ ତେବେ କିଛି ଅଁଶ ଠିକ ଭାବେ ଦେଖାଯାଇ ନପାରେ ।',
'metadata-expand' => 'ଆହୁରି ଖୋଲିକରି ଦେଖାଇବେ',
'metadata-collapse' => 'ଖୋଲାଯାଇଥିବା  ଭାଗକୁ ବୁଜିଦେବେ',
'metadata-fields' => 'ମେଟାଡାଟା ସାରଣୀଟି ବନ୍ଦ ହୋଇରହିଥିଲେ ଏହି ମେସେଜରେ ଥିବା ଛବି ମେଟାଡାଟାସବୁ ଛବିର ପୃଷ୍ଠାରେ ରହିଥିବ ।
ବାକିସବୁ ଆପେଆପେ ଲୁଚି ରହିଥିବ ।
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth' => 'ଓସାର',
'exif-imagelength' => 'ଉଚ୍ଚତା',
'exif-bitspersample' => 'ଉପାଦାନ ପ୍ରତି ବିଟ',
'exif-compression' => 'ସଙ୍କୋଚନ ପ୍ରକାର',
'exif-photometricinterpretation' => 'ପିକ୍ସେଲ ଗଠନ',
'exif-orientation' => 'ଅନୁସ୍ଥାପନ (Orientation)',
'exif-samplesperpixel' => 'ଉପାଦାନମାନଙ୍କର ସଂଖ୍ୟା',
'exif-planarconfiguration' => 'ଡାଟା ସଜାଣି',
'exif-ycbcrsubsampling' => 'Y ସହ C ର ସବସ୍ୟାମ୍ପଲ ଅନୁପାତ',
'exif-ycbcrpositioning' => 'Y ଓ C ର ଅବସ୍ଥାନ',
'exif-xresolution' => 'ଭୂସମାନ୍ତର ରେଜଲୁସନ',
'exif-yresolution' => 'ଭୁଲମ୍ବ ରେଜଲୁସନ',
'exif-stripoffsets' => 'ଛବି ଡାଟା ଅବସ୍ଥାନ',
'exif-rowsperstrip' => 'ପଟି ପିଛା ସ୍ତମ୍ଭ ସଙ୍ଖ୍ୟା',
'exif-stripbytecounts' => 'ସଙ୍କୁଚିତ ପଟି ପିଛା ବାଇଟ',
'exif-jpeginterchangeformat' => 'Offset ରୁ JPEG SOI',
'exif-jpeginterchangeformatlength' => 'JPEG ଡାଟାର ବାଇଟ',
'exif-whitepoint' => 'ଫାଙ୍କା ଜାଗା ବର୍ଣ୍ଣିକତା',
'exif-primarychromaticities' => 'ପ୍ରାଥମିକତାର ବର୍ଣ୍ଣିକତା',
'exif-ycbcrcoefficients' => 'କଲର ସ୍ପେସ ଟ୍ରାନ୍ସଫର ମାଟ୍ରିକ୍ସ କୋଫିସିଏଣ୍ଟ',
'exif-referenceblackwhite' => 'କଳା ଧଳା ଆଧାର ମୂଲ୍ୟର ଯୋଡ଼ାଟିଏ',
'exif-datetime' => 'ଫାଇଲ ବଦଳ ତାରିଖ ଓ ସମୟ',
'exif-imagedescription' => 'ଛବିର ନାମ',
'exif-make' => 'କ୍ୟାମେରା ଉତ୍ପାଦକ',
'exif-model' => 'କ୍ୟାମେରା ମଡ଼େଲ',
'exif-software' => 'ବ୍ୟବହାର କରାଯାଇଥିବା ସଫ୍ଟବେର',
'exif-artist' => 'ଲେଖକ',
'exif-copyright' => 'ସତ୍ଵାଧିକାରୀ',
'exif-exifversion' => 'Exif ସଙ୍କଳନ',
'exif-flashpixversion' => 'ଗ୍ରହଣଯୋଗ୍ୟ ଫ୍ଲାସପିକ୍ସ ସଙ୍କଳନ',
'exif-colorspace' => 'ରଙ୍ଗ ଫାଙ୍କା ଜାଗା',
'exif-componentsconfiguration' => 'ପ୍ରତିତି ଉପାଦାନର ଅର୍ଥ',
'exif-compressedbitsperpixel' => 'ଛବି ସଙ୍କୋଚନ ଅବସ୍ଥା',
'exif-pixelydimension' => 'ଛବି ଓସାର',
'exif-pixelxdimension' => 'ଛବି ଉଚ୍ଚତା',
'exif-usercomment' => 'ସଭ୍ୟ ମତାମତ',
'exif-relatedsoundfile' => 'ସମ୍ବନ୍ଧିତ ଶବ୍ଦ ଫାଇଲ',
'exif-datetimeoriginal' => 'ତଥ୍ୟ ତିଆରିହେବାର ତାରିଖ ଓ ସମୟ',
'exif-datetimedigitized' => 'ଡିଜିଟାଇଜେସନର ତାରିଖ ଓ ସମୟ',
'exif-subsectime' => 'DateTime ସାନସେକେଣ୍ଡ',
'exif-subsectimeoriginal' => 'DateTimeOriginal ସାନ ସେକଣ୍ଡ',
'exif-subsectimedigitized' => 'DateTimeDigitized ସାନ ସେକେଣ୍ଡ',
'exif-exposuretime' => 'ଏକ୍ସପୋଜର କାଳ',
'exif-exposuretime-format' => '$1 ସେକେଣ୍ଡ ($2)',
'exif-fnumber' => 'F ସଙ୍ଖ୍ୟା',
'exif-exposureprogram' => 'ଏକ୍ସପୋଜର ପ୍ରୋଗ୍ରାମ',
'exif-spectralsensitivity' => 'ବର୍ଣ୍ଣାଳି ସମ୍ବେଦନଶୀଳତା',
'exif-isospeedratings' => 'ISO ବେଗ ସୂଚାଙ୍କ',
'exif-shutterspeedvalue' => 'APEX ସଟର ବେଗ',
'exif-aperturevalue' => 'APEX ରନ୍ଧ୍ର',
'exif-brightnessvalue' => 'APEX ଉଜ୍ଜଳିମା',
'exif-exposurebiasvalue' => 'APEX ରନ୍ଧ୍ର ଅଲଗାଭାବ',
'exif-maxaperturevalue' => 'ସର୍ବାଧିକ ଲ୍ୟାଣ୍ଡ ଆପର୍‌ଚର୍',
'exif-subjectdistance' => 'ବସ୍ତୁ ଦୂରତା',
'exif-meteringmode' => 'ମିଟରିଙ୍ଗ ପ୍ରକାର',
'exif-lightsource' => 'ଆଲୁଅର ମୂଳ',
'exif-flash' => 'ଫ୍ଲାସ',
'exif-focallength' => 'ଲେନ୍ସ ଫୋକାଲ ଲମ୍ବ',
'exif-subjectarea' => 'ବସ୍ତୁ କ୍ଷେତ୍ରଫଳ',
'exif-flashenergy' => 'ଫ୍ଲାସ ଶକ୍ତି',
'exif-focalplanexresolution' => 'ଫୋକାଲ ଭୂମି X ରେଜୋଲୁସନ',
'exif-focalplaneyresolution' => 'ଫୋକାଲ ଭୂମି Y ରେଜୋଲୁସନ',
'exif-focalplaneresolutionunit' => 'ଫୋକାଲ ଭୂମି ରେଜୋଲୁସନ ଏକକ',
'exif-subjectlocation' => 'ବସ୍ତୁ ଅବସ୍ଥିତି',
'exif-exposureindex' => 'ଏକ୍ସପୋଜର ଇଣ୍ଡେକ୍ସ',
'exif-sensingmethod' => 'ସେନ୍ସିଙ୍ଗ ପ୍ରଣାଳୀ',
'exif-filesource' => 'ଫାଇଲ ମୂଳାଧାର',
'exif-scenetype' => 'ସିନ ପ୍ରକାର',
'exif-customrendered' => 'କଷ୍ଟମ ଛବି ପ୍ରସେସିଙ୍ଗ',
'exif-exposuremode' => 'ଏକ୍ସପୋଜର ଅବସ୍ଥା',
'exif-whitebalance' => 'ଧଳା ରଙ୍ଗ ସନ୍ତୁଳନ',
'exif-digitalzoomratio' => 'ଡିଟିଟାଲ ଜୁମ ଅନୁପାତ',
'exif-focallengthin35mmfilm' => '୩୫ ମିଲିମିଟର ଫିଲ୍ମରେ ଫୋକାଲ ଲମ୍ବ',
'exif-scenecapturetype' => 'ଦୃଶ୍ୟ ନେବା ପ୍ରକାର',
'exif-gaincontrol' => 'ଦୃଶ୍ୟ ନିୟନ୍ତ୍ରଣ',
'exif-contrast' => 'କଣ୍ଟ୍ରାଷ୍ଟ',
'exif-saturation' => 'ପରିପୃକ୍ତ',
'exif-sharpness' => 'ପ୍ରଖରତା',
'exif-devicesettingdescription' => 'ଉପକରଣ ସଜାଣି ବଖାଣ',
'exif-subjectdistancerange' => 'ବସ୍ତୁର ଦୂରତା ସୀମା',
'exif-imageuniqueid' => 'ଅନନ୍ୟ ଛବି ID',
'exif-gpsversionid' => 'GPS ଚିହ୍ନିତ ସଂସ୍କରଣ',
'exif-gpslatituderef' => 'ଉତ୍ତର ବା ଦକ୍ଷିଣ ଅକ୍ଷାଂଶ',
'exif-gpslatitude' => 'ଅଖ୍ୟାଂଶ',
'exif-gpslongituderef' => 'ପୂର୍ବ ବା ପଶ୍ଚିମ ଅଖ୍ୟାଂଶ',
'exif-gpslongitude' => 'ଦ୍ରାଘିମା',
'exif-gpsaltituderef' => 'ଉଚ୍ଚତା ଆଧାର',
'exif-gpsaltitude' => 'ଉଚ୍ଚତା',
'exif-gpstimestamp' => 'ଜି.ପି.ଏସ. ସମୟ (ଆଣବିକ ଘଡ଼ି)',
'exif-gpssatellites' => 'ମାପ ପାଇଁ ବ୍ୟବହାର କରାଯାଇଥିବା କୃତ୍ରିମ ଉପଗ୍ରହ',
'exif-gpsstatus' => 'ଗ୍ରହଣକାରୀ ସ୍ଥିତି',
'exif-gpsmeasuremode' => 'ମାପ ଅବସ୍ଥା',
'exif-gpsdop' => 'ମାପ ନିର୍ଭୁଲତା',
'exif-gpsspeedref' => 'ବେଗ ମାନକ',
'exif-gpsspeed' => 'ଜି.ପି.ଏସ. ଗ୍ରହଣକାରୀର ବେଗ',
'exif-gpstrackref' => 'ଗତିର ଦିଗ ନିମନ୍ତେ ଆଧାର',
'exif-gpstrack' => 'ଗତିର ଦିଗ',
'exif-gpsimgdirectionref' => 'ଛବିର ଦିଗ ନିମନ୍ତେ ଆଧାର',
'exif-gpsimgdirection' => 'ଛବିର ଦିଗ',
'exif-gpsmapdatum' => 'ଭୁତଥ୍ୟ ଗଣନା ତଥ୍ୟ ବ୍ୟବହାର କରାଯାଇଛି',
'exif-gpsdestlatituderef' => 'ଏକ ଲକ୍ଷସ୍ଥଳର ଅଖ୍ୟାଂଶ ପାଇଁ ଆଧାର',
'exif-gpsdestlatitude' => 'ଅଖ୍ୟାଂଶ ଲକ୍ଷସ୍ଥଳ',
'exif-gpsdestlongituderef' => 'ଏକ ଲକ୍ଷସ୍ଥଳର ଅଖ୍ୟାଂଶ ବା ଦ୍ରାଘିମା ପାଇଁ ଆଧାର',
'exif-gpsdestlongitude' => 'ଲକ୍ଷସ୍ଥଳର ଦ୍ରାଘିମା',
'exif-gpsdestbearingref' => 'ଏକ ଲକ୍ଷସ୍ଥଳର ଧାରଣ ପାଇଁ ଆଧାର',
'exif-gpsdestbearing' => 'ଲକ୍ଷସ୍ଥଳର ଧାରଣ',
'exif-gpsdestdistanceref' => 'ଲକ୍ଷସ୍ଥଳର ଦୂରତା ପାଇଁ ଆଧାର',
'exif-gpsdestdistance' => 'ଲକ୍ଷସ୍ଥଳକୁ ଦୂରତା',
'exif-gpsprocessingmethod' => 'ଜିପିଏସ ପ୍ରକ୍ରିୟାକରଣର ନାମ',
'exif-gpsareainformation' => 'ଜିପିଏସ ଅଞ୍ଚଳର ନାମ',
'exif-gpsdatestamp' => 'ଜିପିଏସ ତାରିଖ',
'exif-gpsdifferential' => 'GPS ତୁଳନାତ୍ମକ ସୁଧାର',
'exif-jpegfilecomment' => 'JPEG ଫାଇଲ ମତାମତ',
'exif-keywords' => 'ସୂଚକ ଶବ୍ଦ',
'exif-worldregioncreated' => 'ଜଗତର କେଉଁ ଜାଗାରେ ଛବିଟି ନିଆଯାଇଛି',
'exif-countrycreated' => 'କେଉଁ ଦେଶରେ ଛବିଟି ନିଆଯାଇଛି',
'exif-countrycodecreated' => 'ଛବିଟି ଉଠାଯାଇଥିବା ଦେଶର କୋଡ଼',
'exif-provinceorstatecreated' => 'ଛବିଟି ନିଆଯାଇଥିବା ରାଜ୍ୟ',
'exif-citycreated' => 'କେଉଁ ନଗରରେ ଛବିଟି ନିଆଯାଇଛି',
'exif-sublocationcreated' => 'ନଗରର କେଉଁ ଭାଗରେ ଛବିଟି ନିଆଯାଇଥିଲା',
'exif-worldregiondest' => 'ପୃଥିବୀର ସ୍ଥାନସବୁ ଦେଖାଇଦିଆଯାଇଛି',
'exif-countrydest' => 'ଦେଶ ଦେଖାଇଦିଆଯାଇଛି',
'exif-countrycodedest' => 'ଦେଶ ପାଇଁ କୋଡ଼ ଦେଖାଇଦିଆଯାଇଛି',
'exif-provinceorstatedest' => 'ରାଜ୍ୟ ଦେଖାଇଦିଆଯାଇଛି',
'exif-citydest' => 'ଦେଖାଯାଇଥିବା ନଗର',
'exif-sublocationdest' => 'ଦେଖାଯାଇଥିବା ନଗରର ଉପଅବସ୍ଥିତି',
'exif-objectname' => 'ଛୋଟ ଶିରୋନାମା',
'exif-specialinstructions' => 'ବିଶେଷ ସୂଚନା',
'exif-headline' => 'ଶିରୋନାମା',
'exif-credit' => 'ଶ୍ରେୟ/ପ୍ରଦାନକାରୀ',
'exif-source' => 'ମୂଳାଧାର',
'exif-editstatus' => 'ଛବିର ସମ୍ପାଦନା ସ୍ଥିତି',
'exif-urgency' => 'ଜରୁରୀକାଳୀନ',
'exif-fixtureidentifier' => 'ଏକ ସ୍ଥାନରେ ଲାଗି ରହିଥିବା ବସ୍ତୁର ନାମ',
'exif-locationdest' => 'ଅବସ୍ଥିତି ଅଙ୍କାଯାଇଛି',
'exif-locationdestcode' => 'ଅଙ୍କାଯାଇଥିବା ସ୍ଥାନର କୋଡ଼',
'exif-objectcycle' => 'ମାଧ୍ୟମଟି ଦିନର କେତେ ବେଳେ ରଖିବାକୁ ସ୍ଥିର କରାଯାଇଛି',
'exif-contact' => 'ଯୋଗାଯୋଗ ସୂଚନାବଳି',
'exif-writer' => 'ଲେଖକ',
'exif-languagecode' => 'ଭାଷା',
'exif-iimversion' => 'IIM ସଂସ୍କରଣ',
'exif-iimcategory' => 'ଶ୍ରେଣୀ',
'exif-iimsupplementalcategory' => 'ସହଯୋଗୀ ଶ୍ରେଣୀସମୂହ',
'exif-datetimeexpires' => 'ଏହାପରେ ବ୍ୟବହାର କରିବେନି',
'exif-datetimereleased' => 'ଦିନ ବାହାରିଛି',
'exif-originaltransmissionref' => 'ପ୍ରାଥମିକ  ଅବସ୍ଥିତି ସଞ୍ଚାରଣ ହୋଇଥିବା ବ୍ୟବସ୍ଥାସମୂହ',
'exif-identifier' => 'ସୂଚକ',
'exif-lens' => 'ଯବକାଚ ବ୍ୟବହାର ହୋଇଛି',
'exif-serialnumber' => 'ଫଟୋଉଠା ଯନ୍ତ୍ରର କ୍ରମିକ ଅଙ୍କ',
'exif-cameraownername' => 'କ୍ୟାମେରାର ମାଲିକ',
'exif-label' => 'ଛାପ',
'exif-datetimemetadata' => 'ଶେଷଥର ବଦଳାଇଯାଇଥିବା ମେଟାଡାଟାର ତାରିଖ ଦେବେ',
'exif-nickname' => 'ଛବିର ସାଧାରଣ ନାମ',
'exif-rating' => 'ବର୍ଗୀକରଣ ( ୫ ରୁ )',
'exif-rightscertificate' => 'ଅଧିକାର ପରିଚାଳନା ପ୍ରମାଣପତ୍ର',
'exif-copyrighted' => 'ପ୍ରତିଲେଖ ଅଧିକାର ଅବସ୍ଥା',
'exif-copyrightowner' => 'ସତ୍ଵାଧିକାରୀ',
'exif-usageterms' => 'ବ୍ୟବହାର କରିବା ନିମନ୍ତେ ସର୍ତ',
'exif-webstatement' => 'ଅନଲାଇନ ସତ୍ଵାଧିକାର ବିବରଣୀ',
'exif-originaldocumentid' => 'ମୂଳ ନଥିର ଅନନ୍ୟ ID',
'exif-licenseurl' => 'ସତ୍ଵାଧିକାର ଲାଇସେନ୍ସ ନିମନ୍ତେ URL',
'exif-morepermissionsurl' => 'ବିକଳ୍ପ ଲାଇସେନ୍ସ ସୂଚନା',
'exif-attributionurl' => 'ଆପଣା କାମର ପୁନବ୍ୟବହାର କଲାବେଳେ ଏହା ସହ ଯୋଡ଼ିବେ',
'exif-preferredattributionname' => 'ଆପଣା କାମର ପୁନବ୍ୟବହାର କଲାବେଳେ ଦୟାକରି ଶ୍ରେୟ ଦିଅନ୍ତୁ',
'exif-pngfilecomment' => 'PNG ଫାଇଲ ମତାମତ',
'exif-disclaimer' => 'ଆମେ ଦାୟୀ ନୋହୁଁ',
'exif-contentwarning' => 'ବିଷୟବସ୍ତୁ ଚେତାବନୀ',
'exif-giffilecomment' => 'GIF ଫାଇଲ ମତାମତ',
'exif-intellectualgenre' => 'ବସ୍ତୁ ପ୍ରକାର',
'exif-subjectnewscode' => 'ବିଷୟ କୋଡ଼',
'exif-scenecode' => 'IPTC ଦୃଶ୍ୟ କୋଡ଼',
'exif-event' => 'ବଖଣାଯାଇଥିବା ଘଟଣା',
'exif-organisationinimage' => 'ବଖଣାଯାଇଥିବା ସଙ୍ଗଠନ',
'exif-personinimage' => 'ବଖଣାଯାଇଥିବା ଲୋକ',
'exif-originalimageheight' => 'ଛୋଟ କରାଯିବ ଆଗରୁ ଛବିର ଉଚ୍ଚତା',
'exif-originalimagewidth' => 'ଛୋଟ କରାଯିବ ଆଗରୁ ଛବିର ଓସାର',

# EXIF attributes
'exif-compression-1' => 'ଅସମ୍ପାଦିତ',
'exif-compression-2' => 'CCITT ଗୋଠ ୩ ୧-ବିମିୟ ବଦଳାଯାଇଥିବା ହଫମାନ ରନ ଲମ୍ବ ଏନକୋଡ଼ିଙ୍ଗ',
'exif-compression-3' => 'CCITT ଗୋଠ ୩ ଫାକ୍ସ ଏନକୋଡ଼ିଙ୍ଗ',
'exif-compression-4' => 'CCITT ଗୋଠ ୪ ଫାକ୍ସ ଏନକୋଡ଼ିଙ୍ଗ',

'exif-copyrighted-true' => 'ସତ୍ଵାଧିକାର ଥିବା',
'exif-copyrighted-false' => 'ପବ୍ଲିକ ଡୋମେନ',

'exif-unknowndate' => 'ଅଜଣା ତାରିଖ',

'exif-orientation-1' => 'ସାଧାରଣ',
'exif-orientation-2' => 'ଭୂସମାନ୍ତର ଭାବେ ବୁଲାଇଦିଆଯାଇଛି',
'exif-orientation-3' => '୧୮୦° ବୁଲାଇଦିଆଯାଇଛି',
'exif-orientation-4' => 'ଭୁଲମ୍ବ ଭାବେ ବୁଲାଇଦିଆଯାଇଛି',
'exif-orientation-5' => 'ଘଣ୍ଟାକଣ୍ଟାର ବିପରୀତ ଦିଗରେ ୯୦° ବୁଲାଇଦିଆଯାଇଛି ଓ ଭୁଲମ୍ବ ଭାବେ ବୁଲାଇଦିଆଯାଇଛି',
'exif-orientation-6' => 'ଘଣ୍ଟାକଣ୍ଟାର ବିପରୀତ ଦିଗରେ ୯୦° ବୁଲାଇ ଦିଆଯାଇଛି',
'exif-orientation-7' => 'ଘଣ୍ଟାକଣ୍ଟାର ଦିଗରେ ୯୦° ବୁଲାଇଦିଆଯାଇଛି ଓ ଭୁଲମ୍ବ ଭାବେ ବୁଲାଇଦିଆଯାଇଛି',
'exif-orientation-8' => 'ଘଣ୍ଟାକଣ୍ଟାର ଦିଗରେ ୯୦° ବୁଲାଇ ଦିଆଯାଇଛି',

'exif-planarconfiguration-1' => 'ବିଗିଡ଼ିଯାଇଥିବା ସଜାଣି',
'exif-planarconfiguration-2' => 'ସମତଳ ସଜାଣି',

'exif-colorspace-65535' => 'କୋଣଅବିଭାଜନ',

'exif-componentsconfiguration-0' => 'ସ୍ଥିତିହୀନ',

'exif-exposureprogram-0' => 'ଦିଆଯାଇନାହିଁ',
'exif-exposureprogram-1' => 'ସହାୟକ ବହି',
'exif-exposureprogram-2' => 'ସାଧାରଣ ପ୍ରୋଗ୍ରାମ',
'exif-exposureprogram-3' => 'ଅପେରଚର ପ୍ରଧାନତା',
'exif-exposureprogram-4' => 'ସଟର ପ୍ରାଥମିକତା',
'exif-exposureprogram-5' => 'ସୃଜନାତ୍ମକ ପ୍ରକ୍ରିୟା (କାମର ଗଭୀରତା ଆଡ଼କୁ ଢଳିପଡ଼ିଥିବା)',
'exif-exposureprogram-6' => 'କରିବାକୁ ଥିବା କାମ (ସଟର ବେଗ ସହ ଯୋଡ଼ା)',
'exif-exposureprogram-7' => 'ପୋଟ୍ରେଟ ଅବସ୍ଥା (ପାଖ ଫଟୋ ନିମନ୍ତେ ଯେଉଁଥିରେ ପଛପଟ ଫୋକସ ବାହାରେ ଥାଏ)',
'exif-exposureprogram-8' => 'ଲାଣ୍ଡସ୍କେପ ଅବସ୍ଥା (ଲଣ୍ଡସ୍କେପ ଫଟୋ ଯେଉଁଥିରେ ପଛପଟ ଫୋକସରେ ଥାଏ)',

'exif-subjectdistance-value' => '$1 ମିଟର',

'exif-meteringmode-0' => 'ଅଜଣା',
'exif-meteringmode-1' => 'ହାରାହାରି',
'exif-meteringmode-2' => 'ହାରାହାରି କେନ୍ଦ୍ର ଓଜନ',
'exif-meteringmode-3' => 'ଚିହ୍ନ',
'exif-meteringmode-4' => 'ବହୁ-ବିନ୍ଦୁ',
'exif-meteringmode-5' => 'ନମୁନା',
'exif-meteringmode-6' => 'କିଛି',
'exif-meteringmode-255' => 'ବାକି',

'exif-lightsource-0' => 'ଅଜଣା',
'exif-lightsource-1' => 'ଦିବାଲୋକ',
'exif-lightsource-2' => 'ଫ୍ଲୋରୋସେଣ୍ଟ',
'exif-lightsource-3' => 'ଟଙ୍ଗଷ୍ଟେନ (ଉତ୍ତପ୍ତ ଆଲୁଅ)',
'exif-lightsource-4' => 'ଫ୍ଲାସ',
'exif-lightsource-9' => 'ଶୁଖିଲା ପାଗ',
'exif-lightsource-10' => 'ମେଘୁଆ ପାଗ',
'exif-lightsource-11' => 'ଛାଇ',
'exif-lightsource-12' => 'ଦିବାଲୋକ ଫ୍ଲୋରୋସେଣ୍ଟ (D 5700 – 7100K)',
'exif-lightsource-13' => 'ଧଳା ଆଲୁଅ ଫ୍ଲୋରୋସେଣ୍ଟ (N 4600 – 5400K)',
'exif-lightsource-14' => 'ଶୀତଳ ଧଳା ଫ୍ଲୋରୋସେଣ୍ଟ (W 3900 – 4500K)',
'exif-lightsource-15' => 'ଧଳା ଫ୍ଲୋରୋସେଣ୍ଟ (WW 3200 – 3700K)',
'exif-lightsource-17' => 'ମାନକ ଆଲୁଅ A',
'exif-lightsource-18' => 'ମାନକ ଆଲୁଅ B',
'exif-lightsource-19' => 'ମାନକ ଆଲୁଅ C',
'exif-lightsource-24' => 'ISO ଷ୍ଟୁଡ଼ିଓ ଟଙ୍ଗଷ୍ଟନ',
'exif-lightsource-255' => 'ବାକି ଲାଇଟ ସୋର୍ସ',

# Flash modes
'exif-flash-fired-0' => 'ଫ୍ଲାସ କାମ କଲାନାହିଁ',
'exif-flash-fired-1' => 'ଫ୍ଲାସ ଦିଆଗଲା',
'exif-flash-return-0' => 'ଗୋଟିଏ ବି ଷ୍ଟ୍ରୋବ ଧରିପାରିବା କାମ ହେଲାନାହିଁ',
'exif-flash-return-2' => 'ଷ୍ଟ୍ରୋବ ଫେରନ୍ତା ଆଲୁଅ ଚିହ୍ନପଡ଼ିଲା ନାହିଁ',
'exif-flash-return-3' => 'ଷ୍ଟ୍ରୋବ ଫେରନ୍ତା ଆଲୁଅ ଚିହ୍ନପଡ଼ିଲା',
'exif-flash-mode-1' => 'ବାଧ୍ୟତାମୂଳକ ଫ୍ଲାସ ପକାଇବା',
'exif-flash-mode-2' => 'ବାଧ୍ୟତାମୂଳକ ଫ୍ଲାସକୁ ଅଟକାଇଦେବା',
'exif-flash-mode-3' => 'ଆପେଆପେ କାମ କରିବା ଅବସ୍ଥା',
'exif-flash-function-1' => 'ବିନା ଫ୍ଲାସରେ କାମ',
'exif-flash-redeye-1' => 'ରେଡ଼-ଆଇ କମାଇବା ସ୍ଥିତି',

'exif-focalplaneresolutionunit-2' => 'ଇଞ୍ଚ',

'exif-sensingmethod-1' => 'ଦିଆଯାଇନଥିବା',
'exif-sensingmethod-2' => 'ୱାନ-ଚିପ କଲର ଏରିଆ ସେନସର',
'exif-sensingmethod-3' => 'ଟୁ-ଚିପ କଲର ଏରିଆ ସେନସର',
'exif-sensingmethod-4' => 'ଥ୍ରି-ଚିପ କଲର ଏରିଆ ସେନସର',
'exif-sensingmethod-5' => 'କଲର ସିକୁଏନସିଆଲ ଏରିଆ ସେନସର',
'exif-sensingmethod-7' => 'ତିନିରୈଖିକ ସେନସର',
'exif-sensingmethod-8' => 'କଲର ସିକୁଏନସିଆଲ ଲିନିଅର ସେନସର',

'exif-filesource-3' => 'ଡିଜିଟାଲ ଷ୍ଟିଲ କ୍ୟାମେରା',

'exif-scenetype-1' => 'ସିଧା ସଳଖ କ୍ୟାମେରାରୁ ନିଆହୋଇଥିବା ଫଟୋ',

'exif-customrendered-0' => 'ସାଧାରଣ ପ୍ରକ୍ରିୟା',
'exif-customrendered-1' => 'ନିର୍ଦିଷ୍ଟ ପ୍ରକ୍ରିୟା',

'exif-exposuremode-0' => 'ଆପେଆପେ ଏକ୍ସପୋଜର',
'exif-exposuremode-1' => 'ମାନୁଆଲ ଏକ୍ସପୋଜର',
'exif-exposuremode-2' => 'ଆପେଆପେ ବନ୍ଧନି ଦେବା',

'exif-whitebalance-0' => 'ଅଟୋ ଧଳା ଅନୁପାତ',
'exif-whitebalance-1' => 'ଅଟୋ ଧଳା ଅନୁପାତ',

'exif-scenecapturetype-0' => 'ମାନକ',
'exif-scenecapturetype-1' => 'ଲାଣ୍ଡସ୍କେପ',
'exif-scenecapturetype-2' => 'ସିଧା',
'exif-scenecapturetype-3' => 'ରାତି ଦୃଶ୍ୟ',

'exif-gaincontrol-0' => 'କିଛି ନାହିଁ',
'exif-gaincontrol-1' => 'କମ ଗେନ ଖାଲି ଜାଗା',
'exif-gaincontrol-2' => 'ଅଧିକ ଗେନ ଅପ',
'exif-gaincontrol-3' => 'କମ ଗେନ ଡାଉନ',
'exif-gaincontrol-4' => 'ଅଧିକ ଗେନ ଡାଉନ',

'exif-contrast-0' => 'ସାଧାରଣ',
'exif-contrast-1' => 'ନରମ',
'exif-contrast-2' => 'ଟାଣ',

'exif-saturation-0' => 'ସାଧାରଣ',
'exif-saturation-1' => 'ଅଳ୍ପ ପରିପୃକ୍ତ',
'exif-saturation-2' => 'ଅଧିକ ପରିପୃକ୍ତ',

'exif-sharpness-0' => 'ସାଧାରଣ',
'exif-sharpness-1' => 'ନରମ',
'exif-sharpness-2' => 'ଟାଣ',

'exif-subjectdistancerange-0' => 'ଅଜଣା',
'exif-subjectdistancerange-1' => 'ବିଶାଳ',
'exif-subjectdistancerange-2' => 'ପାଖ ଦେଖା',
'exif-subjectdistancerange-3' => 'ଦୂରର ଦେଖଣା',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'ଉତ୍ତର ଅକ୍ଷାଂଶ',
'exif-gpslatitude-s' => 'ଦକ୍ଷିଣ ଅକ୍ଷାଂଶ',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'ପୂର୍ବ ଅଖ୍ୟାଂଶ',
'exif-gpslongitude-w' => 'ପଶ୍ଚିମ ଅଖ୍ୟାଂଶ',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => 'ସମୁଦ୍ର ପତ୍ତନଠାରୁ $1 {{PLURAL:$1|ମିଟର|ମିଟର}} ଉଚ୍ଚରେ',
'exif-gpsaltitude-below-sealevel' => 'ସମୁଦ୍ର ପତ୍ତନଠାରୁ $1 {{PLURAL:$1|ମିଟର|ମିଟର}} ତଳେ',

'exif-gpsstatus-a' => 'ମାପ ଚାଲିଛି',
'exif-gpsstatus-v' => 'ମାପ ଇଣ୍ଟର ଅପରେଟେବିଲିଟି',

'exif-gpsmeasuremode-2' => '୨-ଆୟାମୀ ମାପ',
'exif-gpsmeasuremode-3' => '୩-ଆୟାମୀ ମାପ',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'ଘଣ୍ଟା ପ୍ରତି କିଲୋମିଟର',
'exif-gpsspeed-m' => 'ଘଣ୍ଟା ପ୍ରତି ମାଇଲ',
'exif-gpsspeed-n' => 'ଗଣ୍ଠି',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'କିଲୋମିଟର',
'exif-gpsdestdistance-m' => 'ମାଇଲ',
'exif-gpsdestdistance-n' => 'ସାମୁଦ୍ରିକ ମାଇଲ',

'exif-gpsdop-excellent' => 'ଅତିଉନ୍ନତ ($1)',
'exif-gpsdop-good' => 'ଭଲ ($1)',
'exif-gpsdop-moderate' => 'ମଝିମଝିଆ ($1)',
'exif-gpsdop-fair' => 'ଉପଯୁକ୍ତ ($1)',
'exif-gpsdop-poor' => 'ଖରାପ ($1)',

'exif-objectcycle-a' => 'କେବଳ ସକାଳେ',
'exif-objectcycle-p' => 'କେବଳ ସଞ୍ଜରେ',
'exif-objectcycle-b' => 'ଉଭୟ ସକାଳେ ଓ ସଞ୍ଜରେ',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ସଠିକ ଦିଗ',
'exif-gpsdirection-m' => 'ଜ୍ୟାମିତିକ ଦିଗ',

'exif-ycbcrpositioning-1' => 'କୈନ୍ଦ୍ରିକ',
'exif-ycbcrpositioning-2' => 'ସହ-ସାଇଟ',

'exif-dc-contributor' => 'ଅବଦାନକାରୀଗଣ',
'exif-dc-coverage' => 'ସ୍ଥାନିକ ବା ଲୌକିକ',
'exif-dc-date' => 'ତାରିଖ',
'exif-dc-publisher' => 'ପ୍ରକାଶକ',
'exif-dc-relation' => 'ସମ୍ବନ୍ଧିତ ମିଡ଼ିଆ',
'exif-dc-rights' => 'ଅଧିକାର',
'exif-dc-source' => 'ମୂଳାଧାର ମାଧ୍ୟମ',
'exif-dc-type' => 'ମିଡ଼ିଆ ପ୍ରକାର',

'exif-rating-rejected' => 'ନାକଚ କରାଗଲା',

'exif-isospeedratings-overflow' => '୬୫୫୩୫ ରୁ ବଡ଼',

'exif-iimcategory-ace' => 'କଳା, ଚଳଣି, ମନୋରଞ୍ଜନ',
'exif-iimcategory-clj' => 'ଅପରାଧ ଓ ନ୍ୟାୟ',
'exif-iimcategory-dis' => 'ପ୍ରଳୟ ଓ ଦୁର୍ଘଟଣା',
'exif-iimcategory-fin' => 'ଅର୍ଥନୀତି ଓ ବଣିଜ',
'exif-iimcategory-edu' => 'ଶିକ୍ଷା',
'exif-iimcategory-evn' => 'ପରିବେଶ',
'exif-iimcategory-hth' => 'ଦେହପା',
'exif-iimcategory-hum' => 'ମାନବିକ ଇଛା',
'exif-iimcategory-lab' => 'ଶ୍ରମ',
'exif-iimcategory-lif' => 'ଜୀବନଧାରଣ ଓ ଆମୋଦ',
'exif-iimcategory-pol' => 'ରାଜନୀତି',
'exif-iimcategory-rel' => 'ଧର୍ମ ଓ ବିଶ୍ଵାସ',
'exif-iimcategory-sci' => 'ବିଜ୍ଞାନ ଓ କାରିଗରି',
'exif-iimcategory-soi' => 'ସାମାଜିକ ଅସୁବିଧା',
'exif-iimcategory-spo' => 'ଖେଳ',
'exif-iimcategory-war' => 'ଯୁଦ୍ଧ, ବିରୋଧ ଓ ବିପ୍ଲବ',
'exif-iimcategory-wea' => 'ପାଣିପାଗ',

'exif-urgency-normal' => 'ସାଧାରଣ ($1)',
'exif-urgency-low' => 'ଉଣା ($1)',
'exif-urgency-high' => 'ଅଧିକ ($1)',
'exif-urgency-other' => 'ବ୍ୟବହାରକାରୀ ଦେଇ ଦିଆହୋଇଥିବା ଗୁରୁତ୍ଵ ($1)',

# External editor support
'edit-externally' => 'ଏକ ବାହାର ଆପ୍ଲିକେସନ ବ୍ୟବହାର କରି ଏହି ଫାଇଲଟିକୁ ବଦଳାଇବା',
'edit-externally-help' => '(ଆହୁରି ବି [//www.mediawiki.org/wiki/Manual:External_editors ସଜାଡିବା ନିର୍ଦେଶ] ଦେଖନ୍ତୁ)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ସବୁ',
'namespacesall' => 'ସବୁ',
'monthsall' => 'ସବୁ',
'limitall' => 'ସବୁ',

# E-mail address confirmation
'confirmemail' => 'ଆପଣଙ୍କ ଇମେଲ ଠିକଣା ଟି ଠିକ ବୋଲି ଥୟ କରନ୍ତୁ',
'confirmemail_noemail' => '[[Special:Preferences|ଆପଣଙ୍କ ପସନ୍ଦ]] ଭିତରେ  ଏକ ସଠିକ ଇମେଲ ଠିକଣା ଦିଆଯାଇନାହିଁ ।',
'confirmemail_text' => 'ଆପଣା ଇମେଲ ସୁବିଧା ବ୍ୟବହାର କରିବା ଆଗରୁ {{SITENAME}}ରେ ଆପଣଙ୍କର ଇମେଲ ଠିକଣା ଥୟ କରିବାକୁ ପଡ଼ିବ ।
ତଳେ ଥିବା ବୋତାମ ଚିପି ଆପଣା ମେଲ ଠିକଣାକୁ ଏକ ଥୟ କରିବା ମେଲ ପଠାଇପାରିବେ ।
ମେଲଟିରେ ଏକ କୋଡ଼ ଥିବା ଲିଙ୍କ ଥିବ;
ଆପଣା ଇମେଲ ଠିକଣାଟି ଠିକ ବୋଲି ଥୟ କରିବା ପାଇଁ ବ୍ରାଉଜରରେ ସେହି ଲିଙ୍କଟି ଖୋଲିବେ।',
'confirmemail_pending' => 'ଏକ ନିଶ୍ଚିତ କରିବା ଇମେଲ ଆପଣଙ୍କୁ ପଠାଇଦିଆଯାଇଛି;
ଯଦି ଆପଣ ନଗଦ ନିଜର ଖାତା ଖୋଲିଛନ୍ତି ତେବେ ନୂଆ କୋଡ଼ଟିଏ ପାଇଁ ଆବେଦନ କରିବା ଆଗରୁ ଆପଣଙ୍କୁ ଏହା ପାଇଁ କିଛି ମିନିଟ ଅପେକ୍ଷା କରିବାକୁ ପଡ଼ିବ ।',
'confirmemail_send' => 'ଏକ ନିଶ୍ଚିତ କରିବା କୋଡ଼ ପଠାଇବେ',
'confirmemail_sent' => 'ନିଶ୍ଚିତ କରିବା ଇମେଲଟି ପଠାଇଦିଆଗଲା ।',
'confirmemail_oncreate' => 'ଆପଣଙ୍କ ଇମେଲ ଠିକଣାକୁ ଏକ ନିଶ୍ଚିତ କରିବା ଇମେଲ ପଠାଇଦିଆଯାଇଛି ।
ଏହି କୋଡ଼ଟି ଲଗଇନ କରିବା ନିମନ୍ତେ ଲୋଡ଼ା ନାହିଁ, ତେବେ ଏହି ଉଇକିରେ ଯେକୌଣସି ଇମେଲ ଦେଇ ସଚଳ କରାଯିବ ସୁବିଧାକୁ ସଚଳ କରିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଏହା ଦେବାକୁ ପଡ଼ିବ ।',
'confirmemail_sendfailed' => '{{SITENAME}} ଆପଣଙ୍କ ନିଶ୍ଚିତ କରିବା ଇମେଲଟି ପଠାଇପାରିଲା ନାହିଁ ।
ଦୟାକରି ଆପଣଙ୍କ ଇମେଲରେ ଭୁଲ ଅକ୍ଷର ରହିଯାଇଛି କି ନାହିଁ ପରଖିନିଅନ୍ତୁ ।

ଚିଠିଟି ଫେରିଆସିଲା: $1',
'confirmemail_invalid' => 'ଭୁଲ ନିଶ୍ଚିତ କରିବା କୋଡ଼ ।
କୋଡ଼ଟିର ମିଆଦ ପୁରୀ ଯାଇଥାଇପାରେ ।',
'confirmemail_needlogin' => 'ଆପଣଙ୍କୁ ନିଜ ଇମେଲଟିକୁ ଥୟ କରିବା ପାଇଁ $1 କରିବାକୁ ପଡ଼ିବ ।',
'confirmemail_success' => 'ଆପଣଙ୍କ ଇମେଲଟି ଠିକ ବୋଲି ନିଶ୍ଚିତ ହୋଇଗଲା ।
ଆପଣ ଏବେ [[Special:UserLogin|ଲଗ ଇନ]] କରି ଏହି ଉଇକିକୁ ଉପଭୋଗ କରିପାରନ୍ତି ।',
'confirmemail_loggedin' => 'ଆପଣଙ୍କ ଇମେଲ ଠିକଣା ସଠିକ ବୋଲି ପରଖାଗଲା ।',
'confirmemail_error' => 'ଆପଣ ନିଶ୍ଚିତ କରିଲା ବେଳେ କେଉଁ ଏକ ଅଘଟଣ ଘଟିଲା ।',
'confirmemail_subject' => '{{SITENAME}} ଇମେଲ ଠିକଣା ନିଶ୍ଚିତ କରିବା',
'confirmemail_body' => 'କେହିଜଣେ, ବୋଧହୁଏ ଆପଣ ହିଁ $1 ଆଇ.ପି. ଠିକଣାରୁ,
ଏହି ଇ-ମେଲ ଆଇ.ଡି.ରେ "$2" ନାଆଁରେ {{SITENAME}} ଠାରେ ଖାତାଟିଏ ଖୋଲିଛନ୍ତି ।

ଏହି ଖାତାଟି ସତରେ ଆପଣଙ୍କର ବୋଲି ଥୟ କରିବା ପାଇଁ ଓ {{SITENAME}}ରେ ଇ-ମେଲ ସୁବିଧାସବୁ ସଚଳ କରିବାପାଇଁ, ଏହି ଲିଙ୍କ୍ଟିକୁ ଆପଣଙ୍କ ବ୍ରାଉଜରରେ ଖୋଲନ୍ତୁ:

$3

ଯଦି ଆପଣ ଖାତାଟିଏ ଆଗରୁ ଖୋଲି *ନାହାନ୍ତି* ତେବେ ଏହି ଲିଙ୍କକୁ ଯାଇ ଇ-ମେଲ ଆ.ଡି. ଥୟ କରିବାକୁ ନାକଚ କରିଦିଅନ୍ତୁ:

$5

ଏହି କନଫର୍ମେସନ କୋଡ଼ $4 ବେଳେ ଅଚଳ ହୋଇଯିବ ।',
'confirmemail_body_changed' => 'କେହିଜଣେ, ବୋଧହୁଏ ଆପଣ ହିଁ $1 ଆଇ.ପି. ଠିକଣାରୁ,
ଏହି ଇ-ମେଲ ଆଇ.ଡି.ରେ "$2" ନାଆଁରେ {{SITENAME}} ଠାରେ ଖାତାଟିଏ ଖୋଲିଛନ୍ତି ।

ଏହି ଖାତାଟି ସତରେ ଆପଣଙ୍କର ବୋଲି ଥୟ କରିବା ପାଇଁ ଓ {{SITENAME}}ରେ ଇ-ମେଲ ସୁବିଧାସବୁ ସଚଳ କରିବାପାଇଁ, ଏହି ଲିଙ୍କ୍ଟିକୁ ଆପଣଙ୍କ ବ୍ରାଉଜରରେ ଖୋଲନ୍ତୁ:

$3

ଯଦି ଆପଣ ଖାତାଟିଏ ଆଗରୁ ଖୋଲି *ନାହାନ୍ତି* ତେବେ ଏହି ଲିଙ୍କକୁ ଯାଇ ଇ-ମେଲ ଆ.ଡି. ଥୟ କରିବାକୁ ନାକଚ କରିଦିଅନ୍ତୁ:

$5

ଏହି କନଫର୍ମେସନ କୋଡ଼ $4 ବେଳେ ଅଚଳ ହୋଇଯିବ ।',
'confirmemail_body_set' => 'କେହିଜଣେ, ବୋଧହୁଏ ଆପଣ ହିଁ $1 ଆଇ.ପି. ଠିକଣାରୁ,
ଏହି ଇ-ମେଲ ଆଇ.ଡି.ରେ "$2" ନାଆଁରେ {{SITENAME}} ଠାରେ ଖାତାଟିଏ ଖୋଲିଛନ୍ତି ।

ଏହି ଖାତାଟି ସତରେ ଆପଣଙ୍କର ବୋଲି ଥୟ କରିବା ପାଇଁ ଓ {{SITENAME}}ରେ ଇ-ମେଲ ସୁବିଧାସବୁ ସଚଳ କରିବାପାଇଁ, ଏହି ଲିଙ୍କ୍ଟିକୁ ଆପଣଙ୍କ ବ୍ରାଉଜରରେ ଖୋଲନ୍ତୁ:

$3

ଯଦି ଆପଣ ଖାତାଟିଏ ଆଗରୁ ଖୋଲି *ନାହାନ୍ତି* ତେବେ ଏହି ଲିଙ୍କକୁ ଯାଇ ଇ-ମେଲ ଆ.ଡି. ଥୟ କରିବାକୁ ନାକଚ କରିଦିଅନ୍ତୁ:

$5

ଏହି କନଫର୍ମେସନ କୋଡ଼ $4 ବେଳେ ଅଚଳ ହୋଇଯିବ ।',
'confirmemail_invalidated' => 'ଇମେଲ ଠିକଣା ଥୟ କରିବା ନାକଚ କରିଦଗଲା',
'invalidateemail' => 'ଇ-ମେଲ ଠିକଣା ଥୟ କରିବା',

# Scary transclusion
'scarytranscludedisabled' => '[ଉଇକି-ଉଇକି ଭିତରେ ଟ୍ରାନ୍ସକ୍ଲୁଡ଼ିଙ୍ଗ ଅଚଳ କରାଯାଇଛି]',
'scarytranscludefailed' => '[$1 ପାଇଁ ଛାଞ୍ଚକୁ ପାଇବା ସମ୍ଭବ ହେଲାନାହିଁ]',
'scarytranscludetoolong' => '[URLଟି ଖୁବ ଲମ୍ବା]',

# Delete conflict
'deletedwhileediting' => "''' ସାବଧାନ ''' : ଆପଣ ବଦଳାଇବା ପାଇଁ ଆରମ୍ଭ କରିବା ପରେ ପରେ ହିଁ ଏହି ପୃଷ୍ଠାଟିକୁ ଲିଭାଇ ଦିଆଯାଇଛି !",
'confirmrecreate' => "ବ୍ୟବହାରକାରୀ [[User:$1|$1]] ([[User talk:$1|talk]]) ଆପଣ ବଦଳାଇବା ଆରମ୍ଭ କରିବା ପରେ ପରେ ଏହି ପୃଷ୍ଠାଟିକୁ ଲିଭାଇ ଦେଇଛନ୍ତି ଓ ଏହାର କାରଣ ହେଉଛି :
: ''$2''
ଆପଣ ସତରେ ଏହାକୁ ଆଉଥରେ ତିଆରି କରିବାକୁ ଚାହାଁନ୍ତି ବୋଲି ଥୟ କରନ୍ତୁ ।",
'confirmrecreate-noreason' => 'ବ୍ୟବହାରକାରୀ [[User:$1|$1]] ([[User talk:$1|ଆଲୋଚନା]]) ଆପଣ ବଦଳାଇବା ଆରମ୍ଭ କରିବା ପରେ ପରେ ଏହି ପୃଷ୍ଠାଟିକୁ ଲିଭାଇ ଦେଇଛନ୍ତି । ଆପଣ ସତରେ ଏହାକୁ ଆଉଥରେ ତିଆରି କରିବାକୁ ଚାହାଁନ୍ତି ବୋଲି ଥୟ କରନ୍ତୁ ।',
'recreate' => 'ପୁନଗଠନ',

# action=purge
'confirm_purge_button' => 'ଠିକ ଅଛି',
'confirm-purge-top' => 'ଏହି ପୃଷ୍ଠାଟିର ନଗଦ ସଙ୍କଳନଟିକୁ ଦେଖାଇବେ?',
'confirm-purge-bottom' => 'ପୁରୁଣା ସ୍ମୃତିସବୁକୁ ସଫା କରିଦେଲେ ତାହା ପୃଷ୍ଠାଟିର ନଗଦ ସଙ୍କଳନଟି ଦେଖାଇଥାଏ ।',

# action=watch/unwatch
'confirm-watch-button' => 'ଠିକ ଅଛି',
'confirm-watch-top' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଆପଣଙ୍କ ଦେଖିଥିବା ତାଲିକାରେ ଯୋଡନ୍ତୁ ?',
'confirm-unwatch-button' => 'ଠିକ ଅଛି',
'confirm-unwatch-top' => 'ନିଜ ଦେଖଣାତାଲିକାରୁ ଏହି ପୃଷ୍ଠାଟି ବାହାର କରିଦେବେ କି?',

# Multipage image navigation
'imgmultipageprev' => 'ଆଗ ପୃଷ୍ଠା',
'imgmultipagenext' => 'ପର ପୃଷ୍ଠା →',
'imgmultigo' => 'ଯିବା!',
'imgmultigoto' => '$1 ପୃଷ୍ଠାକୁ ଯିବେ',

# Table pager
'ascending_abbrev' => 'ସାନରୁ ବଡ କ୍ରମରେ',
'descending_abbrev' => 'ବଖାଣ',
'table_pager_next' => 'ପର ପୃଷ୍ଠା',
'table_pager_prev' => 'ଆଗ ପୃଷ୍ଠା',
'table_pager_first' => 'ପ୍ରଥମ ପୃଷ୍ଠା',
'table_pager_last' => 'ଶେଷ ପୃଷ୍ଠା',
'table_pager_limit' => 'ପ୍ରତି ପୃଷ୍ଠାରେ $1ଟି ଜିନିଷ ଦେଖାଉଛି',
'table_pager_limit_label' => 'ପୃଷ୍ଠା ପ୍ରତି ବସ୍ତୁ:',
'table_pager_limit_submit' => 'ଯିବା',
'table_pager_empty' => 'ଫଳହୀନ',

# Auto-summaries
'autosumm-blank' => 'ପୃଷ୍ଠାଟିକୁ ଖାଲି କରିଦେଲେ',
'autosumm-replace' => 'ବିଷୟବସ୍ତୁକୁ "$1" ଦେଇ ପ୍ରତିବଦଳ କଲେ',
'autoredircomment' => '[[$1]]କୁ ପୃଷ୍ଠାଟି ଘୁଞ୍ଚାଇଦିଆଗଲା',
'autosumm-new' => '"$1" ନାଆଁରେ ପୃଷ୍ଠାଟିଏ ତିଆରିକଲେ',

# Live preview
'livepreview-loading' => 'ଖୋଲୁଅଛି...',
'livepreview-ready' => 'ଖୋଲୁଅଛି...ଏବେ ସଜିଲ!',
'livepreview-failed' => 'ସିଧା ଦେଖଣା ବିଫଳ ହେଲା!
ସାଧାରଣ ଦେଖଣା ପାଇଁ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'livepreview-error' => 'ଏହିସବୁ କାମ ପାଇଁ ଯୋଡ଼ିପାରୁନାହୁଁ $1 "$2" 
ସାଧାରଣ ଦେଖଣା ପାଇଁ ଚେଷ୍ଟା କରନ୍ତୁ ।',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 {{PLURAL:$1|ସେକେଣ୍ଡ|ସେକେଣ୍ଡ}}ରୁ ନୂଆ ବଦଳ ଏହି ତାଲିକାରେ ଦେଖାଯାଉ ନାହିଁ ।',
'lag-warn-high' => 'ଅଧିକ ଡାଟାବେସ ସର୍ଭର ପଛୁଆ ଅବସ୍ଥା ହେତୁ $1 {{PLURAL:$1|ସେକେଣ୍ଡ|ସେକେଣ୍ଡ}}ରୁ ନୂଆ ବଦଳସବୁ ଏହି ତାଲିକାରେ ଦେଖାଯିବ ନାହିଁ ।',

# Watchlist editor
'watchlistedit-numitems' => 'ଆପଣଙ୍କ ଦେଖଣାତାଲିକାରେ ଅଲୋଚନାକୁ ଛାଡ଼ି {{PLURAL:$1|ନାମଟିଏ|$1 ଗୋଟି ନାମ}} ଅଛି ।',
'watchlistedit-noitems' => 'ଆପଣଙ୍କ ଦେଖଣାତାଲିକାରେ ଗୋଟିଏ ବି ନାମ ନାହିଁ ।',
'watchlistedit-normal-title' => 'ଦେଖଣାତାଲିକା ସମ୍ପାଦନା କରିବେ',
'watchlistedit-normal-legend' => 'ଦେଖିଥିବା ପୃଷ୍ଠାଗୁଡିକରୁ ଶିରୋନାମା ହଟାଇବେ ।',
'watchlistedit-normal-explain' => 'ଆପଣଙ୍କର ଦେଖଣା ତାଲିକାର ଶିରୋନାମାଗୁଡିକ ତଳେ ଦେଖା ଯାଇଛି ।
ଶିରୋନାମା  ହଟାଇବାକୁ ଚାହୁଁଥିଲେ, ଏହାର ପାଖରେ ଥିବା ବାକ୍ସରେ ଟିକ ମାରନ୍ତୁ ଏବଂ "{{int:Watchlistedit-normal-submit}}"ରେ କ୍ଲିକ କରନ୍ତୁ ।
ଆପଣ [[Special:EditWatchlist/raw|edit the raw list]] ମଧ୍ୟ କରିପାରିବେ ।',
'watchlistedit-normal-submit' => 'ଶିରୋନାମାଗୁଡିକୁ ଲିଭାଇବେ',
'watchlistedit-normal-done' => '{{PLURAL:$1|ଗୋଟିଏ ନାମ|$1 ଗୋଟି ନାମ}} ଆପଣଙ୍କ ଦେଖଣାତାଲିକାରୁ କାଢ଼ିଦିଆଗଲା:',
'watchlistedit-raw-title' => 'ଫାଙ୍କା ଦେଖା ତାଲିକାଟିର ସମ୍ପାଦନା କରିବେ',
'watchlistedit-raw-legend' => 'ଫାଙ୍କା ଦେଖା ତାଲିକାଟିର ସମ୍ପାଦନା କରିବେ',
'watchlistedit-raw-explain' => 'ଅପନକ ଦେଖଣାତାଲିକାରେ ଥିବା ନାମସବୁ ତଳେ ଦିଆଯାଇଛି, ସେସବୁକୁ ତାଲିକାରେ ଯୋଗ କରି ବା କାଢ଼ି ବଦଳାଇ ହେବ;
ଧାଡ଼ି ପ୍ରତି ଗୋଟିଏ ନାମ ।
ସରିଲା ପରେ, "{{int:Watchlistedit-raw-submit}}" କ୍ଲିକ କରନ୍ତୁ ।
ଆପଣ ମଧ୍ୟ [[Special:EditWatchlist|ମାନକ ସମ୍ପାଦକ ବ୍ୟବହାର କରିପାରିବେ ]] ।',
'watchlistedit-raw-titles' => 'ଶିରୋନାମା:',
'watchlistedit-raw-submit' => 'ଦେଖଣା ତାଲିକାକୁ ଅପଡେଟ କରିବେ',
'watchlistedit-raw-done' => 'ଆପଣଙ୍କ ଦେଖଣା ତାଲିକାଟି ଅପଡେଟ ହେଇଯାଇଛି ।',
'watchlistedit-raw-added' => '{{PLURAL:$1|ନାମଟିଏ|$1 ଗୋଟି ନାମ}} ଯୋଗ କରାଗଲା:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|ନାମଟିଏ|$1 ଗୋଟି ନାମ}} କାଢ଼ିଦିଆଗଲା:',

# Watchlist editing tools
'watchlisttools-view' => 'ଦରକାରୀ ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'watchlisttools-edit' => 'ଦେଖିବା ତାଲିକାଟିକୁ ଦେଖିବେ ଓ ବଦଳାଇବେ',
'watchlisttools-raw' => 'ଫାଙ୍କା ଦେଖଣା ତାଲିକାଟିର ସମ୍ପାଦନା କରିବେ',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|ମୋ ଆଲୋଚନା]])',

# Core parser functions
'unknown_extension_tag' => 'ଅଜଣା ଏକ୍ସଟେନସନ ଚିହ୍ନ "$1"',
'duplicate-defaultsort' => '\'\'\'ସୂଚନା:\'\'\' ଆପେଆପେ କାମକରୁଥିବା "$2" ଆଗରୁ ଆପେ ଆପେ ସଜାଡୁଥିବା "$1"କୁ ବନ୍ଦ କରିଦେଇଛି ।',

# Special:Version
'version' => 'ସଂସ୍କରଣ',
'version-extensions' => 'ଇନଷ୍ଟଲ କରାହୋଇଥିବା ଏକ୍ସଟେନସନସବୁ',
'version-specialpages' => 'ବିଶେଷ ପୃଷ୍ଠା',
'version-parserhooks' => 'ପାର୍ସର ହୁକ',
'version-variables' => 'ଚଳ',
'version-antispam' => 'ଅଦରକାରୀ ମେଲ ଅଟକ',
'version-skins' => 'ବହିରାବରଣ',
'version-other' => 'ବାକି',
'version-mediahandlers' => 'ମିଡ଼ିଆ ହ୍ୟାଣ୍ଡଲର',
'version-hooks' => 'ହୁକ',
'version-extension-functions' => 'ଏକ୍ସଟେନସନ କାମସବୁ',
'version-parser-extensiontags' => 'ପାର୍ସର ଏକ୍ସଟେନସନ ଚିହ୍ନ',
'version-parser-function-hooks' => 'ପାର୍ସର କାମ ହୁକ',
'version-hook-name' => 'ହୁକ ନାମ',
'version-hook-subscribedby' => 'କାହା ଦେଇ ମଗାଯାଇଛି',
'version-version' => '(ଭାଗ $1)',
'version-license' => 'ଲାଇସେନ୍ସ',
'version-poweredby-credits' => "ଏହି ଉଇକିଟି '''[//www.mediawiki.org/ ମିଡ଼ିଆଉଇକି]''' ଦେଇ ପରିଚାଳିତ, ସତ୍ଵାଧିକାର © ୨୦୦୧-$1 $2 ।",
'version-poweredby-others' => 'ବାକିସବୁ',
'version-license-info' => 'MediaWiki ଏକ ମାଗଣା ସଫ୍ଟୱାର; ଆପଣ ଏହାକୁ ପୁନବଣ୍ଟନ କରିପାରିବେ ବା GNU ଜେନେରାଲ ପବ୍ଲିକ ଲାଇସେନ୍ସ ଅଧିନରେ ବଦଳାଇପାରିବେ ଯାହା ଫ୍ରି ସଫ୍ଟୱାର ଫାଉଣ୍ଡେସନ ଦେଇ ପ୍ରକାଶିତ ହୋଇଥିବ।

MediaWiki ଉପଯୋଗୀ ହେବା ଲକ୍ଷରେ ବଣ୍ଟାଯାଇଥାଏ, କିନ୍ତୁ ଏହା କୌଣସି ଲିଖିତ ପଟା ସହ ଆସିନଥାଏ; ଏହା ବିକ୍ରୟଯୋଗ୍ୟତା ବା ଏକ ନିର୍ଦିଷ୍ଟ କାମପାଇଁ ବାଧ୍ୟତାମୂଳକ ପଟା ସହ ଆସିନଥାଏ । ଅଧିକ ଜାଣିବା ନିମନ୍ତେ ଦୟାକରି GNU ଜେନେରାଲ ପବ୍ଲିକ ଲାଇସେନ୍ସ ଦେଖନ୍ତୁ ।

ଆପଣ [{{SERVER}}{{SCRIPTPATH}}/COPYING GNU ଜେନେରାଲ ପବ୍ଲିକ ଲାଇସେନ୍ସର ନକଲଟିଏ] ଏହି ସଫ୍ଟୱାର ସହିତ ପାଇଥିବା ଜରୁରି; ଯଦି ପାଇନଥିବେ, ଫ୍ରି ସଫ୍ଟୱାର ଫାଉଣ୍ଡେସନ, Inc., ୫୧ ଫ୍ରାଙ୍କଲୀନ ଷ୍ଟ୍ରିଟ, ୫ମ ମହଲା, ବଷ୍ଟନ, ମାସାଚୁସେଟସ ୦୨୧୧୦-୧୩୦୧, ଯୁକ୍ତରାଷ୍ଟ୍ର ଆମେରିକା କିମ୍ବା [//www.gnu.org/licenses/old-licenses/gpl-2.0.html ଅନଲାଇନ] ପଢ଼ିନିଅନ୍ତୁ ।',
'version-software' => 'ଇନଷ୍ଟଲ ହୋଇଥିବା ସଫ୍ଟୱାର',
'version-software-product' => 'ଉତ୍ପାଦ',
'version-software-version' => 'ସଂସ୍କରଣ',
'version-entrypoints' => 'ନିବେଶ ହେଉଥିବା ସ୍ଥାନର URLଗୁଡିକ',
'version-entrypoints-header-entrypoint' => 'ପ୍ରବେଶ ବିନ୍ଦୁ',
'version-entrypoints-header-url' => 'ଇଉଆରଏଲ',

# Special:FilePath
'filepath' => 'ଫାଇଲ ପଥ',
'filepath-page' => 'ଫାଇଲ:',
'filepath-submit' => 'ଯିବା',
'filepath-summary' => 'ଏହି ବିଶେଷ ପୃଷ୍ଠା ଏକ ଫାଇଲର ପୁରା ପଥ ଦେଖାଇଦିଏ ।
ଛବିସବୁ ପୁରା ରେଜୋଲୁସନରେ ଦେଖାଇଦିଆଯାଏ, ବାକି ଫାଇଲସବୁ ସେମାନଙ୍କ ସହଯୋଗୀ ପ୍ରଗାମ ମାନଙ୍କ ଦେଇ ଆରମ୍ଭ ହୋଇଥାନ୍ତି ।',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'ଏହାର ନକଲ ପୃଷ୍ଠା ଖୋଜିବେ ।',
'fileduplicatesearch-summary' => 'ହାସ୍ ମୂଲ୍ୟକୁ ନେଇ ଦୁଇଥର ଥିବା ଫାଇଲ ଖୋଜନ୍ତୁ ।',
'fileduplicatesearch-legend' => 'ନକଲ ପାଇଁ ଖୋଜନ୍ତୁ ।',
'fileduplicatesearch-filename' => 'ଫାଇଲ ନାମ:',
'fileduplicatesearch-submit' => 'ଖୋଜିବା',
'fileduplicatesearch-info' => '$1 × $2 pixel<br />ଫାଇଲ ପ୍ରକାର: $3<br />MIME ପ୍ରକାର: $4',
'fileduplicatesearch-result-1' => '"$1"ର କୌଣସି ଏକାଭଳି ଥିବା ଫାଇଲ ନାହିଁ ।',
'fileduplicatesearch-result-n' => '"$1" ଫାଇଲର {{PLURAL:$2|1 ଗୋଟି ଏକାଭଳି|$2 ଗୋଟି ଏକାଭଳି}} ଫାଇଲ ଅଛି ।',
'fileduplicatesearch-noresults' => '"$1" ନାମରେ ଗୋଟିଏ ବି ଫାଇଲ ମିଳିଲା ନାହିଁ ।',

# Special:SpecialPages
'specialpages' => 'ବିଶେଷ ପୃଷ୍ଠା',
'specialpages-note' => '----
* ସାଧାରଣ ବିଶେଷ ପୃଷ୍ଠାମାନ ।
* <span class="mw-specialpagerestricted">କିଳାଯାଇଥିବା ବିଶେଷ ପୃଷ୍ଠାମାନ ।</span>',
'specialpages-group-maintenance' => 'ରକ୍ଷଣାବେକ୍ଷଣା ବିବରଣୀ',
'specialpages-group-other' => 'ବାକି ବିଶେଷ ପୃଷ୍ଠା',
'specialpages-group-login' => 'ଲଗଇନ / ଖାତା ଖୋଲିବେ',
'specialpages-group-changes' => 'ନଗଦ ବଦଳ ଓ ଇତିହାସ',
'specialpages-group-media' => 'ମିଡ଼ିଆ ବିବରଣୀ ଓ ଅପଲୋଡ଼',
'specialpages-group-users' => 'ବ୍ୟବହାରକାରୀଗଣ ଓ ଅଧିକାରମାନ',
'specialpages-group-highuse' => 'ଅଧିକ ବ୍ୟବହାର ହେଉଥିବା ପୃଷ୍ଠା',
'specialpages-group-pages' => 'ପୃଷ୍ଠାମାନଙ୍କର ତାଲିକା',
'specialpages-group-pagetools' => 'ପୃଷ୍ଠା ଉପକରଣ',
'specialpages-group-wiki' => 'ଉଇକି ଡାଟା ଓ ଉପକରଣ',
'specialpages-group-redirects' => 'ବିଶେଷ ପୃଷ୍ଠାକୁ ପୁନପ୍ରେରଣ କରିବା',
'specialpages-group-spam' => 'ଅଯଥା ଉପକରଣ',

# Special:BlankPage
'blankpage' => 'ଖାଲି ପୃଷ୍ଠା',
'intentionallyblankpage' => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଜାଣିଶୁଣି ଫାଙ୍କା ଛଡା ଯାଇଛି ।',

# External image whitelist
'external_image_whitelist' => ' #ଏହି ଧାଡ଼ିଟି ଯେମିତି ଅଛି ସେମିତି ରଖିଦିଅନ୍ତୁ<pre>
#ସାଧାରଣ ଖଣ୍ଡିତ ଲେଖାସମୂହ (କେବଳ // ଧାଡ଼ି ତଳେ ଥିବା ଭାଗ) ତଳେ ରଖିବେ
#ଏହା ବାହାରେ ଥିବା ଛବି (hotlinked) ସହ ଏହାକୁ ମେଳାଯିବ
#ଯେଉଁସବୁ ମେଳଖାଇବ ତାହା ଛବି ଭାବରେ ଦେଖାଯିବ, ନହେଲେ ଛବିପାଇଁ କେବଳ ଲିଙ୍କଟିଏ ଦେଖାଯିବ
#ଯେଉଁ ଧାଡ଼ିର ଆଗରେ  # ଚିହ୍ନ ଥିବ ତାହାକୁ ମତାମତ ବୋଲି ଗଣାଯିବ
#ଏସବୁ ଇଂରାଜୀ ବଡ଼ ଓ ସାନ ଅକ୍ଷର ପାଇଁ ଅଲଗା

#ସବୁଯାକ ସାଧାରଣ ବିବରଣୀ ଖଣ୍ଡ (regex fragments)ଏହି ରେଖା ଉପରେ ରଖିବେ । ସେସବୁ ଯେମିତି ଅଛି ସେମିତି ହିଁ ରଖିବେ</pre>',

# Special:Tags
'tags' => 'ବୈଧ ସମ୍ପାଦନା ଚିହ୍ନ',
'tag-filter' => '[[Special:Tags|ଟାଗ]] ଛଣା:',
'tag-filter-submit' => 'ଛାଣିବା',
'tags-title' => 'ସୂଚକ',
'tags-intro' => 'ଏହି ପୃଷ୍ଠା ସଫ୍ଟୱାର ଏକ ବଦଳ ଭାବେ ଚିହ୍ନିତ କରୁଥିବା ଚିହ୍ନସବୁର ମାନେ ସହ ତାଲିକା ତିଆରି କରିଥାଏ ।',
'tags-tag' => 'ଚିହ୍ନ ନାମ',
'tags-display-header' => 'ବଦଳ ତାଲିକାରେ ଦେଖଣା',
'tags-description-header' => 'ଅର୍ଥର ପୁରା ବିବରଣୀ',
'tags-hitcount-header' => 'ଚିହ୍ନିତ ବଦଳ',
'tags-edit' => 'ସମ୍ପାଦନା',
'tags-hitcount' => '$1 {{PLURAL:$1|ବଦଳ|ବଦଳସବୁ}}',

# Special:ComparePages
'comparepages' => 'ବଦଳ ତୁଳନା କରିବେ',
'compare-selector' => 'ପୃଷ୍ଠା ସଂସ୍କରଣ ତୁଳନା କରିବେ',
'compare-page1' => 'ପୃଷ୍ଠା ୧',
'compare-page2' => 'ପୃଷ୍ଠା ୨',
'compare-rev1' => 'ପୁନରାବୃତ୍ତି୧',
'compare-rev2' => 'ପୁନରାବୃତ୍ତି ୨',
'compare-submit' => 'ତୁଳନା',
'compare-invalid-title' => 'ଆପଣ ଦର୍ଶାଇଥିବା ଶିରୋନାମା ବୈଧ ନୁହେଁ ।',
'compare-title-not-exists' => 'ଆପଣ ଦର୍ଶାଇଥିବା ଶିରୋନାମାଟି ଆଦୌ ନାହିଁ ।',
'compare-revision-not-exists' => 'ଆପଣ ଦର୍ଶାଇଥିବା ପୁନରାବୃତ୍ତି ଆଦୌ ନାହିଁ ।',

# Database error messages
'dberr-header' => 'ଏହି ଉଇକିରେ କିଛି ଅସୁବିଧା ଅଛି ।',
'dberr-problems' => 'କ୍ଷମାକରିବେ ! 
ଏହି ସାଇଟରେ ଟିକେ ଯାନ୍ତ୍ରିକ',
'dberr-again' => 'କିଛି ମିନିଟ ଅପେକ୍ଷା କରିବା ସହ ଆଉ ଥରେ ଲୋଡ କରନ୍ତୁ ।',
'dberr-info' => '(ଡାଟାବେସ ସର୍ଭର ସହ ଯୋଗାଯୋଗ କରିପାରିଲୁ ନାହିଁ: $1)',
'dberr-usegoogle' => 'ଏହି ସମୟ ଭିତରେ ଆପଣ ଗୁଗଲରେ ଖୋଜି ପାରିବେ ।',
'dberr-outofdate' => 'ଜାଣିରଖନ୍ତୁ ଯେ ଆମ ବିଷୟବସ୍ତୁକୁ ନେଇ ସେମାନେ ତିଆରିଥିବା ସୂଚି ବହୁପୁରାତନ ହୋଇପାରେ ।',
'dberr-cachederror' => 'ଏହା ଅନୁରୋଧ କରାଯାଇଥିବା ପୃଷ୍ଠାର ଏକ ଆଗରୁ ସାଇତାଥିବା ନକଲ ଓ ସତେଜ ହୋଇ ନଥାଇପାରେ ।',

# HTML forms
'htmlform-invalid-input' => 'ଆପଣଙ୍କର କେତେକ ନିବେଶରେ ଅସୁବିଧାମାନ ରହିଅଛି',
'htmlform-select-badoption' => 'ଆପଣ ଦେଇଥିବା ମୂଲ୍ୟଟି ଏକ ବୈଧ ବିକଳ୍ପ ନୁହେଁ ।',
'htmlform-int-invalid' => 'ଆପଣ ଦେଇଥିବା',
'htmlform-float-invalid' => 'ଆପଣ ଦେଇଥିବା ମୁଲ୍ୟ ଏକ ଅଙ୍କ ନୁହେଁ ।',
'htmlform-int-toolow' => 'ଆପଣ ଦେଇଥିବା ମୂଲ୍ୟ ସବୁଠାରୁ କମ ଥିବା ମୂଲ୍ୟ $1ରୁ ଉଣା',
'htmlform-int-toohigh' => 'ଆପଣ ଦେଇଥିବା ମୂଲ୍ୟ ସବୁଠାରୁ ଅଧିକ ଥିବା ମୂଲ୍ୟ $1ରୁ ଅଧିକ',
'htmlform-required' => 'ଏହି ମୂଲ୍ୟ ଦରକାର',
'htmlform-submit' => 'ଦାଖଲକରିବା',
'htmlform-reset' => 'କରାଯାଇଥିବା ବଦଳ ପଛକୁ ଫେରାଇବେ',
'htmlform-selectorother-other' => 'ବାକି',

# SQLite database support
'sqlite-has-fts' => 'ପୁରା ଟେକ୍ସ୍ଟ ଖୋଜା ସହଯୋଗ ସହିତ $1',
'sqlite-no-fts' => 'ପୁରା ଟେକ୍ସ୍ଟ ଖୋଜା ସହଯୋଗ ବିନା $1',

# New logging system
'logentry-delete-delete' => '$1 $3 ପୃଷ୍ଠାଟି ଲିଭାଇଦେଲେ',
'logentry-delete-restore' => '$1 $3 ପୃଷ୍ଠାଟି ପୁନସ୍ଥାପନ କଲେ',
'logentry-delete-event' => '$1 $3 ବେଳେ {{PLURAL:$5|ଏକ ଇତିହାସର ଘଟଣାର|$5 ଇତିହାସର ଘଟଣାମାନଙ୍କର}} ଦେଖଣା ବଦଳାଇ ଦେଲେ: $4',
'logentry-delete-revision' => '$1 $3 ପୃଷ୍ଠାରେ {{PLURAL:$5|ସଙ୍କଳନଟିଏର|$5 ସଙ୍କଳନମାନଙ୍କର}} ଦେଖଣା ବଦଳାଇ ଦେଲେ: $4',
'logentry-delete-event-legacy' => '$1 $3 ରେ ଇତିହାସର ଘଟଣାସବୁର ଦେଖଣା ବଦଳାଇଦେଲେ',
'logentry-delete-revision-legacy' => '$1 $3 ପୃଷ୍ଠାରେ ଇତିହାସର ଘଟଣାସବୁର ଦେଖଣା ବଦଳାଇଦେଲେ',
'logentry-suppress-delete' => '$1 $3 ପୃଷ୍ଠାଟିକୁ ଚପାଇଦେଲେ',
'logentry-suppress-event' => '$3 ବେଳେ $1 ଗୋପନ ଭାବରେ {{PLURAL:$5|ଇତିହାସର ଘଟଣାଟିଏର|$5 ଇତିହାସର ଘଟଣାବଳୀର}} ଦେଖଣା ବଦଳାଇଦେଲେ: $4',
'logentry-suppress-revision' => '$1 ଗୋପନ ଭାବରେ $3 ପୃଷ୍ଠାରେ {{PLURAL:$5|ଇତିହାସର ଘଟଣାଟିଏର|$5 ଇତିହାସର ଘଟଣାବଳୀର}} ଦେଖଣା ବଦଳାଇଦେଲେ: $4',
'logentry-suppress-event-legacy' => '$1 ଗୋପନ ଭାବରେ ବଦଳାଇଦେଲେ $3ରେ ଥିବା ଇତିହାସ ଘଟଣାମାନଙ୍କର ଦେଖଣା ବଦଳାଇଦେଲେ',
'logentry-suppress-revision-legacy' => '$1 ଗୋପନ ଭାବରେ ବଦଳାଇଦେଲେ $3 ପୃଷ୍ଠାରେ ଥିବା ଇତିହାସ ଘଟଣାମାନଙ୍କର ଦେଖଣା ବଦଳାଇଦେଲେ',
'revdelete-content-hid' => 'ଭିତର ଭାଗ ଲୁଚାଯାଇଅଛି',
'revdelete-summary-hid' => 'ସମ୍ପାଦନା ସାରକଥା ଲୁଚାଯାଇଅଛି',
'revdelete-uname-hid' => 'ଇଉଜର ନାମ ଲୁଚାଯାଇଅଛି',
'revdelete-content-unhid' => 'ଲୁଚାଯାଇଥିବା ଭିତର ଭାଗ ଦେଖାଇବେ',
'revdelete-summary-unhid' => 'ଲୁଚାଯାଇଥିବା ସମ୍ପାଦନା ସାରକଥା ଦେଖାଇବେ',
'revdelete-uname-unhid' => 'ଲୁଚାଯାଇଥିବା ଇଉଜର ନାମ ଦେଖାଇବେ',
'revdelete-restricted' => 'ପରିଛାମାନଙ୍କ ନିମନ୍ତେ ଥିବା ବାରଣ',
'revdelete-unrestricted' => 'ପରିଛାମାନଙ୍କ ନିମନ୍ତେ ଥିବା ବାରଣ ବାହାର କରିଦିଆଗଲା',
'logentry-move-move' => '$1 $3 ପୃଷ୍ଠାଟି $4କୁ ଘୁଞ୍ଚାଇଲେ',
'logentry-move-move-noredirect' => '$1 $3 ପୃଷ୍ଠାଟି $4କୁ ପୁନପ୍ରେରଣ ବିନା ଘୁଞ୍ଚାଇଲେ',
'logentry-move-move_redir' => '$1 $3 ପୃଷ୍ଠାଟି $4କୁ ପୁନପ୍ରେରଣ ଛାଡ଼ି ଘୁଞ୍ଚାଇଲେ',
'logentry-move-move_redir-noredirect' => '$1 $3 ପୃଷ୍ଠାଟି $4କୁ ପୁନପ୍ରେରଣକୁ ଛାଡ଼ି ପୁନପ୍ରେରଣ ବିନା ଘୁଞ୍ଚାଇଲେ',
'logentry-patrol-patrol' => '$1 $3 ପୃଷ୍ଠାର $4 ତମ ସଙ୍କଳନକୁ ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କଲେ',
'logentry-patrol-patrol-auto' => '$1 ଆପେଆପେ $3 ପୃଷ୍ଠାର $4 ତମ ସଙ୍କଳନକୁ ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କଲେ',
'logentry-newusers-newusers' => 'ସଭ୍ୟ ଖାତା $1 ତିଆରି କରାଗଲା',
'logentry-newusers-create' => 'ସଭ୍ୟ ଖାତା $1 ତିଆରି କରାଗଲା',
'logentry-newusers-create2' => 'ସଭ୍ୟ ଖାତା $3ଟି $1 ଦ୍ଵାରା ତିଆରି କରାଗଲା',
'logentry-newusers-autocreate' => '$1 ଖାତାଟି ଆପେଆପେ ତିଆରିହେଲା',
'newuserlog-byemail' => 'ଇ-ମେଲରେ ପାସୱାର୍ଡ଼ ପଠାଇଦିଆଗଲା',

# Feedback
'feedback-bugornote' => 'ଦୟାକରି ଆପଣ ଏକ କାରିଗରି ଅସୁବିଧାଟିଏ ଜଣାଇବା ପାଇଁ ଚାହୁଁଥିଲେ ଦୟାକରି [$1 ଏଠାରେ ଅସୁବିଧାଟି ଜଣାନ୍ତୁ] । 
ଅଥବା, ଆପଣ ତଳେ ଠିଆ ସହଜ ଆବେଦନ ପତ୍ରଟି ପୁରଣ କରିପାରିବେ ।  ଆପଣଙ୍କ ବ୍ୟବହାରକାରୀ ନାମ ଓ ଆପଣ ବ୍ୟବହାର କରୁଥିବା ବ୍ରାଉଜର ଅନୁସାରେ ଆପଣଙ୍କ ମତାମତ "[$3 $2]"ରେ ଯୋଡ଼ାଯିବ ।',
'feedback-subject' => 'ବିଷୟ:',
'feedback-message' => 'ଖବର:',
'feedback-cancel' => 'ନାକଚ',
'feedback-submit' => 'ମତାମତ ଦିଅନ୍ତୁ',
'feedback-adding' => 'ପୃଷ୍ଠାରେ ମତାମତ ଦେଉଛି...',
'feedback-error1' => 'ଭୁଲ: API ରୁ ଅଚିହ୍ନା ଫଳାଫଳ',
'feedback-error2' => 'ଅସୁବିଧା: ସମ୍ପାଦନା ବିଫଳ ହେଲା',
'feedback-error3' => 'ଅସୁବିଧା: API ରୁ କିଛି ଉତ୍ତର ମିଳିଲା ନାହିଁ',
'feedback-thanks' => 'ଧନ୍ୟବାଦ ! ଆପଣଙ୍କର ମତାମତ  "[$2 $1]" ପୃଷ୍ଠାରେ ଦର୍ଶାଯାଇଛି ।',
'feedback-close' => 'ହୋଇଗଲା',
'feedback-bugcheck' => 'ବହୁତ ଭଲ ! ଖାଲି ଦେଖିଦିଅନ୍ତୁ ଯେ ଏହା ଆଗରୁ ଥିବା [$1 known bugs] ମଧ୍ୟରୁ ନୁହେଁ ତ ।',
'feedback-bugnew' => 'ମୁଁ ଯାଞ୍ଚ କରିଦେଲି । ନୂତନ ଅସୁବିଧାର ବିବରଣ କରନ୍ତୁ ।',

# Search suggestions
'searchsuggest-search' => 'ଖୋଜିବା',
'searchsuggest-containing' => 'ଧାରଣ ହେଉଛି...',

# API errors
'api-error-badaccess-groups' => 'ଆପଣଙ୍କୁ ଏହି ଉଇକିରେ ଅପଲୋଡ଼ କରିବାକୁ ଅନୁମତି ଦିଆଯାଇନାହିଁ ।',
'api-error-badtoken' => 'ଭିତର ଅସୁବିଧା: ଖରାପ ଟୋକନ ।',
'api-error-copyuploaddisabled' => 'URL ଦେଇ ଅପଲୋଡ଼ କରିବା ଏହି ସର୍ଭରରେ ଅଚଳ କରାଯାଇଅଛି ।',
'api-error-duplicate' => 'ଏହି ସାଇଟରେ ସେହି ଏକା ତଥ୍ୟ ଥିବା {{PLURAL:$1| [$2 ଆଉ ଏକ ଫାଇଲ] ରହିଅଛି|[$2 ଆଉ କିଛି ଫାଇଲ] ରହି ଅଛନ୍ତି}} ।',
'api-error-duplicate-archive' => 'ସେହି ସାଇଟରେ ସେହି ଏକା ଭିତର ଭାଗ ସହିତ ଆଗରୁ {{PLURAL:$1|[$2 ଆଉ ଫାଇଲଟିଏ] ଥିଲା|[$2 ଆଉ କେତେକ ଫାଇଲ] ଥିଲା}}, କିନ୍ତୁ {{PLURAL:$1|ତାହାକୁ|ସେସବୁକୁ}} ଲିଭାଇ ଦିଆଯାଇଅଛି ।',
'api-error-duplicate-archive-popup-title' => 'ଆଗରୁ ଲିଭାଯାଇଥିବା ନକଲି {{PLURAL:$1|ଗୋଟି ଫାଇଲ|ଗୋଟି ଫାଇଲ}}',
'api-error-duplicate-popup-title' => 'ନକଲି {{PLURAL:$1|ଗୋଟି ଫାଇଲ|ଗୋଟି ଫାଇଲ}}',
'api-error-empty-file' => 'ଆପଣ ପଠାଇଥିବା ଫାଇଲଟି ଖାଲି ଅଟେ ।',
'api-error-emptypage' => 'ନୂଆ, ଖାଲି ପୃଷ୍ଠ ତିଆରି କରିବାର ଅନୁମତି ନାହି ।',
'api-error-fetchfileerror' => 'ଭିତର ଅସୁବିଧା: ଏହି ଫାଇଲଟି ପାଖରେ ପହଞ୍ଚିବା ବେଳେ କିଛି ଅସୁବିଧା ହେଲା ।',
'api-error-fileexists-forbidden' => '"$1" ନାମରେ ଗୋଟିଏ ଫାଇଲ ଆଗରୁ ଅଛି, ଏବଂ ଏହା ଉପରେ ଲେଖି ହେବନି ।',
'api-error-fileexists-shared-forbidden' => '"$1" ନାମରେ ଗୋଟିଏ ଫାଇଲ ବଣ୍ଟାଯାଇଥିବା ସାଇତାଗୃହରେ ଅଛି, ଏବଂ ଏହା ବାଲାଯାଇପାରିବ ନାହିଁ ।',
'api-error-file-too-large' => 'ଆପଣ ପଠାଇଥିବା ଫାଇଲଟି ବିରାଟ ଅଟେ ।',
'api-error-filename-tooshort' => 'ଫାଇଲ ନାମଟି ଖୁବ ଛୋଟ ।',
'api-error-filetype-banned' => 'ଏହି ପ୍ରକାରର ଫାଇଲ ବାରଣ କରାଯାଇଅଛି ।',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|ଏକ ଅନୁମୋଦିତ ଫାଇଲ ପ୍ରକାର ନୁହେଁ|ମାନ ଅନୁମୋଦିତ ଫାଇଲ ପ୍ରକାର ନୁହଁନ୍ତି}} ।
ଅନୁମୋଦିତ {{PLURAL:$3|ଫାଇଲ ପ୍ରକାର ହେଲା|ଫାଇଲଗୁଡିକର ପ୍ରକାର ହେଲା}} $2 ।',
'api-error-filetype-missing' => 'ଫାଇଲଟିର ଏକ୍ସଟେନସନ ନାହିଁ ।',
'api-error-hookaborted' => 'ଏକ ଏକ୍ସଟେନସନ ହୁକ ଦେଇ ଆପଣ କରୁଥିବା ବଦଳଟି ବନ୍ଦ କରିଦିଆଗଲା ।',
'api-error-http' => 'ଭିତର ଅସୁବିଧା: ସର୍ଭର ସହ ଯୋଡ଼ି ହେଉନାହିଁ ।',
'api-error-illegal-filename' => 'ଏହି ଫାଇଲ ନାମଟି ଅନୁମୋଦିତ ନୁହେଁ ।',
'api-error-internal-error' => 'ଆଭ୍ୟନ୍ତରୀଣ ଅସୁବିଧା: ଏହି ଉଇକିରେ ଆପଣଙ୍କ ଅପଲୋଡ଼ କରିବା କାଳରେ କିଛି ଅସୁବିଧା ଘଟିଲା ।',
'api-error-invalid-file-key' => 'ଭିତର ଅସୁବିଧା: ଫାଇଲଟି ଅସ୍ଥାୟୀ ସାଇତାଘର ଭିତରୁ ମିଳିଲାନାହିଁ ।',
'api-error-missingparam' => 'ଭିତର ଅସୁବିଧା: ହଜିଯାଇଥିବା ପାରାମିଟର ସବୁକୁ ଅନୁରୋଧ କ୍ରମେ ଦେଖାଇଦିଆଗଲା ।',
'api-error-missingresult' => 'ଭିତର ଅସୁବିଧା: ନକଲ କରିବା ଠିକରେ ହେଲାକି ନାହିଁ  ଜାଣି ପାରିଲା ନାହିଁ ।',
'api-error-mustbeloggedin' => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଲଗ ଇନ କରିବାକୁ ପଡ଼ିବ ।',
'api-error-mustbeposted' => 'ଭିତର ଅସୁବିଧା: କରାଯାଇଥିବା ଅନୁରୋଧ ପାଇଁ HTTP POST ଦରକାର ।',
'api-error-noimageinfo' => 'ଅପଲୋଡ଼ ସଫଳ ହେଲା, କିନ୍ତୁ ସର୍ଭରଟି ଆମ୍ଭଙ୍କୁ ଫାଇଲଟୀ ବାବଦରେ କିଛି ବିବରଣୀ ଦେଲା ନାହିଁ ।',
'api-error-nomodule' => 'ଭିତର ଅସୁବିଧା: ଅପଲୋଡ଼ ମୋଡୁଲ ଠିକ କରାଯାଇନାହିଁ ।',
'api-error-ok-but-empty' => 'ଭିତର ଅସୁବିଧା: ସର୍ଭର ଠାରୁ କିଛି ଖବର ନାହିଁ ।',
'api-error-overwrite' => 'ଆଗରୁଥିବା ଏକ ଫାଇଲ ଉପରେ ମଡ଼ାଇବା ଅନୁମୋଦିତ ନୁହେଁ ।',
'api-error-stashfailed' => 'ଭିତର ଅସୁବିଧା: ସର୍ଭର ଅସ୍ଥାୟୀ ଫାଇଲକୁ ସାଇତି ପାରିଲା ନାହିଁ ।',
'api-error-timeout' => 'ସର୍ଭର ଏକ ସୀମିତ କାଳ ଭିତରେ ଉତ୍ତର ଦେଲାନାହିଁ ।',
'api-error-unclassified' => 'ଏକ ଅଜଣା ଅସୁବିଧା ଘଟିଲା ।',
'api-error-unknown-code' => 'ଅଜଣା ତୃଟି: "$1"',
'api-error-unknown-error' => 'ଆଭ୍ୟନ୍ତରୀଣ ଅସୁବିଧା: ଫାଇଲଟି ଅପଲୋଡ଼ କରିବା କାଳରେ କିଛି ଅସୁବିଧା ଘଟିଲା ।',
'api-error-unknown-warning' => 'ଅଜଣା ଚେତାବନୀ: $1',
'api-error-unknownerror' => 'ଅଜଣା ତୃଟି: "$1"',
'api-error-uploaddisabled' => 'ଉଇକିରେ ଅପଲୋଡ଼ କରିବା ଅଚଳ କରାଯାଇଅଛି ।',
'api-error-verification-error' => 'ଏହି ଫାଇଲଟି ବୋଧ ହୁଏ ନଷ୍ଟ ହୋଇଯାଇଅଛି କିମ୍ବା ଭୁଲ ଏକ୍ସଟେନସନ ଦିଆଯାଇଅଛି ।',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|ସେକଣ୍ଡ|ସେକେଣ୍ଡ}}',
'duration-minutes' => '$1 {{PLURAL:$1|ମିନିଟ|ମିନିଟ}}',
'duration-hours' => '$1 {{PLURAL:$1|ଘଣ୍ଟା|ଘଣ୍ଟା}}',
'duration-days' => '$1 {{PLURAL:$1|ଦିନ|ଦିନଗୁଡିକ}}',
'duration-weeks' => '$1 {{PLURAL: $1|ସପ୍ତାହ|ସପ୍ତାହଗୁଡିକ}}',
'duration-years' => '$1 {{PLURAL:$1|year|years}}',
'duration-decades' => '$1 {{PLURAL:$1|decade|decades}}',
'duration-centuries' => '$1 {{PLURAL:$1|century|centuries}}',
'duration-millennia' => '$1 {{PLURAL:$1|millennium|millennia}}',

);
