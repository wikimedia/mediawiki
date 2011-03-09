<?php
/** Oriya (ଓଡ଼ିଆ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Jose77
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
# Dates
'january'       => 'ଜାନୁଆରି',
'february'      => 'ଫେବୁଆରି',
'march'         => 'ମାର୍ଚ',
'april'         => 'ଅପ୍ରେଲ',
'may_long'      => 'ମେ',
'june'          => 'ଜୁନ',
'july'          => 'ଜୁଲାଇ',
'august'        => 'ଅଗଷ୍ଟ',
'september'     => 'ସେପଟେଁବର',
'october'       => 'ଅକଟୋବର',
'november'      => 'ନଭେଁବର',
'december'      => 'ଡିସେଁବର',
'january-gen'   => 'ଜାନୁଆରି',
'february-gen'  => 'ଫେବ୍ରୁଆରି',
'march-gen'     => 'ମାର୍ଚ',
'april-gen'     => 'ଅପ୍ରେଲ',
'may-gen'       => 'ମଇ',
'june-gen'      => 'ଜୁନ',
'july-gen'      => 'ଜୁଲାଇ',
'august-gen'    => 'ଅଗଷ୍ଟ',
'september-gen' => 'ସେପ୍ଟେଁବର',
'october-gen'   => 'ଅକଟୋବର',
'november-gen'  => 'ନଭେଁବର',
'december-gen'  => 'ଡିସେଁବର',
'jan'           => 'ଜାନୁଆରି',
'feb'           => 'ଫେବ୍ରୁଆରି',
'mar'           => 'ମାର୍ଚ',
'apr'           => 'ଅପ୍ରେଲ',
'may'           => 'ମଇ',
'jun'           => 'ଜୁନ',
'jul'           => 'ଜୁଲାଇ',
'aug'           => 'ଅଗଷ୍ଟ',
'sep'           => 'ସେପ୍ଟେଁବର',
'oct'           => 'ଅକଟୋବର',
'nov'           => 'ନଭେଁବର',
'dec'           => 'ଡିସେଁବର',

# Categories related messages
'pagecategories'  => '{{PLURAL:$1|Category|ବିଭାଗ}}',
'category_header' => '"$1" ବିଭାଗରେ ଥିବା ଫରଦଗୁଡ଼ିକ',
'subcategories'   => 'ଉପଶ୍ରେଣୀଗୁଡ଼ିକ',

'newwindow'  => 'ନୂଆ ଊଇଁଡୋରେ ଖୋଲ',
'cancel'     => 'ନାକଚ କରିଦିଅ',
'mytalk'     => 'ମୋ କଥା',
'navigation' => 'ଦିଗବାରେଣି (ନାଭିଗେସନ)',

'tagline'          => '{{SITENAME}} ରୁ',
'help'             => 'ସାହାଜ୍ୟ',
'search'           => 'ଖୋଜିବା',
'searchbutton'     => 'ଖୋଜିବା',
'searcharticle'    => 'ଯିବା',
'history'          => 'ଫାଇଲ ଇତିହାସ',
'history_short'    => 'ଇତିହାସ',
'printableversion' => 'ଛପାହୋଇପାରିବା ଫରଦ',
'permalink'        => 'ସବୁଦିନିଆ ଲିଁକ',
'edit'             => 'ଏଡ଼ିଟ',
'create'           => 'ତିଆରିକର',
'delete'           => 'ଲିଭେଇବେ',
'protect'          => 'କିଳିବେ',
'protect_change'   => 'ବଦଳାଁତୁ',
'newpage'          => 'ନୂଆ ଫରଦ',
'talkpagelinktext' => 'କଥାଭାଷା',
'personaltools'    => 'ନିଜର ଟୁଲ',
'talk'             => 'ଆଲୋଚନା',
'views'            => 'ଦେଖା',
'toolbox'          => 'ଜନ୍ତ୍ର ପେଡ଼ି',
'otherlanguages'   => 'ଅଲଗା ଭାଷା',
'lastmodifiedat'   => 'ଏହି ଫରଦଟି $1 ତାରିଖ $2 ବେଳେ ବଦଳାଯାଇଥିଲା ।',
'jumpto'           => 'ଡେଇଁବା',
'jumptonavigation' => 'ନାଭିଗେସନକୁ',
'jumptosearch'     => 'ଖୋଜିବା',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} ବାବଦରେ',
'aboutpage'            => 'Project:ବାବଦରେ',
'copyright'            => '$1 ରେ ସର୍ବସ୍ଵତ୍ଵ ସଂରକ୍ଷିତ',
'disclaimers'          => 'ଆମେ ଦାୟୀ ନୋହୁଁ',
'disclaimerpage'       => 'Project:ଆମେ ଦାୟୀ ନୋହୁଁ',
'edithelp'             => 'ଲେଖା ସାହାଜ୍ୟ',
'edithelppage'         => 'Help:ବଦଳା',
'mainpage'             => 'ମୂଳ ଫରଦ',
'mainpage-description' => 'ପ୍ରଧାନ ପ୍ରୁଷ୍ଟ୍ଆ',
'privacy'              => 'ଗୁମର ନୀତି',
'privacypage'          => 'Project:ଗୁମର ନୀତି',

'retrievedfrom'           => '"$1" ରୁ ଅଣାଯାଇଅଛି',
'youhavenewmessages'      => 'ଆପଣଙ୍କର $1 ($2).',
'newmessageslink'         => 'ନୂଆ ମେସେଜ',
'youhavenewmessagesmulti' => '$1 ତାରିଖରେ ନୂଆ ଚିଠିଟିଏ ଆସିଛି',
'editsection'             => 'ଏଡ଼ିଟ',
'editold'                 => 'ବଦଳାଁତୁ',
'editlink'                => 'ଏଡିଟ',
'viewsourcelink'          => 'ଉତ୍ସ ଦେଖ',
'editsectionhint'         => '$1 ଭାଗଟିକୁ ବଦଳାଇବା',
'toc'                     => 'ଭିତର ଚିଜ',
'showtoc'                 => 'ଦେଖାଅ',
'hidetoc'                 => 'ଲୁଚାଅ',
'site-rss-feed'           => '$1 ଆରେସେସ ଫିଡ଼',
'site-atom-feed'          => '$1 ଆଟମ ଫିଡ଼',
'page-rss-feed'           => '$1 ଟି ଆରେସେସ ଫିଡ଼',
'page-atom-feed'          => '$1 ଟି ଆଟମ ଫିଡ଼',
'red-link-title'          => ' $1 (ଫରଦଟି ନାହିଁ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'ଫରଦ',
'nstab-user'     => ' ଇଉଜର ଫରଦ',
'nstab-special'  => 'ବିଶେଷ ଫରଦ',
'nstab-project'  => 'ପ୍ରକଳ୍ପ ଫରଦ',
'nstab-image'    => 'ଫାଇଲ',
'nstab-category' => 'ବିଭାଗ',

# General errors
'missing-article' => 'ଡାଟାବେସଟି ଆପଣ ଲେଖିଥିବା "$1" $2 ଶବଦଟି ପାଇଲା ନାହିଁ । .

ଯଦି ଆପଣ ଖୋଜିଥିବା ଫରଦଟି କେହି ଲିଭାଇ ଦେଇଥାଏ ତେବେ ଏମିତି ହୋଇପାରେ ।

ଯଦି ସେମିତି ହୋଇନଥାଏ ତେବେ ଆପଣ ଏହି ସଫଟଉଏରରେ କିଛି ଅସୁବିଧା ଖୋଜି ପାଇଛଁତି ।
ଦୟାକରି କେହି ଜଣେ [[Special:ListUsers/sysop|ପରିଛା]] ଙ୍କୁ ଏଇ ଇଉ.ଆର.ଏଲ (url) ସହ ଚିଠିଟିଏ ପଠାଇ ଦିଅଁତୁ ।',
'viewsource'      => 'ଉତ୍ସ ଦେଖ',

# Login and logout pages
'nav-login-createaccount' => 'ଲଗିନ / ଖାତା ଖୋଲିବା',
'userlogout'              => 'ଲଗ ଆଉଟ',

# Edit page toolbar
'bold_sample'    => 'ବୋଲ୍ଡ ଲେଖା',
'bold_tip'       => 'ବୋଲ୍ଡ ଲେଖା',
'italic_sample'  => 'ଡାହାଣକୁ ଢଳିଥିବା ଲେଖା',
'italic_tip'     => 'ଡାହାଣକୁ ଢଳିଥିବା ଲେଖା',
'link_sample'    => 'ଲିଁକ ଟାଇଟଲ',
'link_tip'       => 'ଭିତର ଲିଁକ',
'extlink_sample' => 'http://www.example.com ଲିଁକ ଟାଇଟଲ',
'extlink_tip'    => 'ବାହାର ଲିଁକ (http:// ଆଗରେ ଲଗାଇବାକୁ ମନେରଖିଥିବେ)',
'headline_tip'   => '୨କ ଆକାରର ମୂଳଧାଡ଼ି',
'math_tip'       => 'ଗାଣିତିକ ସୁତର (ଲାଟେକ୍ସ)',
'media_tip'      => 'ଫାଇଲର ଲିଁକ',
'sig_tip'        => 'ଲେଖାର ବେଳ ସହ ଆପଣଁକ ହସ୍ତାକ୍ଷର',

# Edit pages
'summary'                => 'ସାରକଥା:',
'subject'                => 'ବିଷୟ/ମୂଳ ଲେଖା',
'minoredit'              => 'ଏହା ଖୁବ ଛୋଟ ବଦଳଟିଏ',
'savearticle'            => 'ସାଇତି ରଖ',
'preview'                => 'ଦେଖଣା',
'showdiff'               => 'ବଦଳଗୁଡିକୁ ଦେଖାଅ',
'editing'                => '$1 କୁ ବଦଳାଉଛି',
'editingsection'         => '$1 (ଭାଗ)କୁ ଏଡ଼ିଟ କରିବେ',
'template-protected'     => '(କିଳାଯାଇଥିବା)',
'template-semiprotected' => '(ଅଧା କିଳାଯାଇଥିବା)',

# History pages
'revisionasof'     => '$1 ଉପରେ କରାଯାଇଥିବା ବଦଳ',
'previousrevision' => 'ପୁରୁଣା ସଁକଳନ',
'cur'              => 'ଦାନକର',
'last'             => 'ଆଗ',
'histfirst'        => 'ସବୁଠୁ ପୁରୁଣା',
'histlast'         => 'ନଗଦ',

# Revision deletion
'rev-delundel'   => 'ଦେଖାଅ/ଲୁଚାଅ',
'revdel-restore' => 'ଦେଖଣାକୁ ବଦଳାଁତୁ',

# Merge log
'revertmerge' => 'ମିଶାଅ ନାହିଁ',

# Diffs
'lineno'                  => '$1 କ ଧାଡ଼ି:',
'compareselectedversions' => 'ବଛାହୋଇଥିବା ସଁକଳନ ଗୁଡ଼ିକୁ ତଉଲ',
'editundo'                => 'ପଛକୁ ଫେରିବା',

# Search results
'searchresults'             => 'ଖୋଜିବାରୁ ମିଳିଲା',
'searchresults-title'       => '"$1" ପାଇଁ ଖୋଜିବାରୁ ମିଳିଲା',
'searchsubtitle'            => 'ଆପଣ  \'\'\'[[:$1]]\'\'\' ପାଇଁ ([[Special:Prefixindex/$1|"$1" ରେ ଆରଭ ହୋଇଥିବା ସବୁ ଫରଦ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" କୁ ଯୋଡ଼ାଥିବା ସବୁତକ ଫରଦ]])',
'notitlematches'            => 'ଫରଦର ଟାଇଟଲ ମିଶୁନାହିଁ',
'notextmatches'             => 'ଫରଦର ଲେଖାସବୁ ମିଶୁନାହିଁ',
'prevn'                     => '{{PLURAL:$1|$1}}ର ଆଗରୁ',
'nextn'                     => '{{PLURAL:$1|$1}} ପର',
'viewprevnext'              => '($1 {{int:pipe-separator}} $2) ($3) ଟି ଦେଖ',
'search-result-size'        => '$1 ({{PLURAL:$2|1 ଶବ୍ଦ|$2 ଶବ୍ଦ}})',
'search-redirect'           => '($1 କୁ ଆଗକୁ ବଢେଇନିଅ )',
'search-section'            => '(ଭାଗ $1)',
'search-suggest'            => 'ଆପଣ $1 ଭାବି ଖୋଜିଥିଲେ କି',
'search-interwiki-caption'  => 'ଭଉଣୀ ପ୍ରକଳ୍ପ',
'search-interwiki-default'  => '$1 ଫଳାଫଳ:',
'search-interwiki-more'     => '(ଅଧିକ)',
'search-mwsuggest-enabled'  => 'ପରାମର୍ଶ ସହ',
'search-mwsuggest-disabled' => 'ମତାମତ ନାହିଁ',
'powersearch'               => 'ଗହିର ଖୋଜା',
'powersearch-legend'        => 'ଗହିର ଖୋଜା',
'powersearch-ns'            => 'ନେମସ୍ପେସରେ ଖୋଜ',
'powersearch-redir'         => 'ପଛକୁ ଫେରାଯାଇଥିବା ଲେଖାଗୁଡ଼ିକର ତାଲିକା',
'powersearch-field'         => 'ଖୋଜ',

# Preferences page
'mypreferences'     => 'ମୋ ପସଁଦସବୁ',
'searchresultshead' => 'ସନ୍ଧାନ',

# Groups
'group-sysop' => 'ପରିଛାଗଣ',

'grouppage-sysop' => '{{ns:project}}:ପରିଛା (ଆଡମିନ)',

# Recent changes
'recentchanges'      => 'ନଗଦ ବଦଳ',
'rcshowhideminor'    => '$1 ଟି ଛୋଟମୋଟ ବଦଳ',
'rcshowhidebots'     => '$1 ଜଣ ବଟ',
'rcshowhideliu'      => '$1 ଜଣ ନାଆଁ ଲେଖାଇଥିବା ଇଉଜର',
'rcshowhideanons'    => '$1 ଜଣ ଅଜଣା ଇଉଜର',
'rcshowhidemine'     => '$1 ମୁଁ କରିଥିବା ବଦଳ',
'diff'               => 'ଅଦଳ ବଦଳ',
'hist'               => 'ଇତିହାସ',
'hide'               => 'ଲୁଚାଅ',
'show'               => 'ଦେଖାଅ',
'minoreditletter'    => 'ଟିକେ',
'newpageletter'      => 'ନୂଆ',
'boteditletter'      => 'ବଟ',
'rc-enhanced-expand' => 'ପୁରା ଦେଖାଅ (ଜାଭାସ୍କ୍ରିପଟ ଦରକାର)',
'rc-enhanced-hide'   => 'ବେଶି କଥାସବୁ ଲୁଚାଇଦିଅ',

# Recent changes linked
'recentchangeslinked'         => 'ଏଇମାତ୍ର ବଦଳାଯାଇଥିବା ଫରଦର ଲିଁକ',
'recentchangeslinked-feed'    => 'ସମ୍ବଧ୍ହିତ ପରିବର୍ତନ',
'recentchangeslinked-toolbox' => 'ସମ୍ବଧ୍ହିତ ପରିବର୍ତନ',
'recentchangeslinked-summary' => "ଏଇଟି ଅଳ୍ପସମୟ ଆଗରୁ ନିର୍ଦିଷ୍ଟ ଫରଦରୁ ଲିଂକ ହୋଇଥିବା ଆଉ ବଦଳାଯାଇଥିବା (ଅବା ଗୋଟିଏ ନିର୍ଦିଷ୍ଟ ବିଭାଗର) ଫରଦସବୁର ତାଲିକା ।  [[Special:Watchlist|ମୋର ଦେଖାତାଲିକା]]ର ଫରଦ ସବୁ '''ବୋଲଡ'''।",
'recentchangeslinked-page'    => 'ଫରଦର ନାଆଁ',

# Upload
'upload'            => 'ଫାଇଲ ଅପଲୋଡ଼ କର',
'filedesc'          => 'ସାରକଥା',
'fileuploadsummary' => 'ସାରକଥା:',

# File description page
'filehist'          => 'ଫାଇଲ ଇତିହାସ',
'filehist-current'  => 'ଏବେକାର',
'filehist-datetime' => 'ତାରିଖ/ବେଳ',
'filehist-thumb'    => 'ନଖ ଦେଖଣା',
'filehist-user'     => 'ଇଉଜର',
'filehist-comment'  => 'ମତାମତ',
'imagelinks'        => 'ଫାଇଲର ଲିଁକସବୁ',

# Miscellaneous special pages
'nbytes'               => '$1 {{PLURAL:$1|byte|ବାଇଟ}}',
'nmembers'             => '$1 {{PLURAL:$1|member|ସଭ୍ୟ}}',
'wantedcategories'     => 'ଦରକାରି ବିଭାଗ',
'wantedpages'          => 'ଦରକାରି ଫରଦ',
'wantedpages-badtitle' => '$1 ଉତ୍ତରସବୁରେ ଥିବା ଭୁଲ ଟାଇଟଲ',
'wantedfiles'          => 'ଦରକାରି ଫାଇଲ',
'wantedtemplates'      => 'ଦରକାରି ଟେଁପଲେଟ',
'mostlinked'           => 'ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଫରଦ',
'mostlinkedcategories' => 'ବେଶି ଯୋଡ଼ାଯାଇଥିବା ବିଭାଗ',
'mostlinkedtemplates'  => 'ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଟେଁପଲେଟ',
'mostcategories'       => 'ବେଶିବିଭାଗ ଥିବା ଫରଦ',
'mostimages'           => 'ଫାଇଲରେ ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଥିବା',
'move'                 => 'ଘୁଁଚାଅ',

# Book sources
'booksources' => 'ବହି ସ୍ରୋତ',

# Special:Log
'log' => 'ଲଗ',

# Special:AllPages
'alphaindexline' => '$1 ରୁ $2',
'allpagessubmit' => 'ଯାଅ',

# Special:LinkSearch
'linksearch-ok' => 'ସନ୍ଧାନ',

# Watchlist
'watchlist'   => 'ଦେଖାତାଲିକା',
'mywatchlist' => 'ମୋର ଦେଖାତାଲିକା',
'watch'       => 'ଦେଖ',
'unwatch'     => 'ଦେଖନାହିଁ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ଦେଖୁଛି...',
'unwatching' => 'ଦେଖୁନାହିଁ...',

# Delete
'actioncomplete' => 'କାମଟି ପୁରା ହେଲା',
'deletedarticle' => '"[[$1]]" ଟି ଲିଭିଗଲା',
'dellogpage'     => 'ଲିଭାଇବା ଲଗ',

# Rollback
'rollbacklink' => 'ପଛକୁ ଫେର',

# Protect
'protectlogpage' => 'କିଳିବା ଲଗ',

# Undelete
'undeletelink'           => 'ଦେଖ/ଆଉଥରେ ଫେରାଇଆଣ',
'undelete-search-submit' => 'ସନ୍ଧାନ',

# Namespace form on various pages
'namespace'      => 'ନେମସ୍ପେସ',
'blanknamespace' => '(ମୂଳ)',

# Contributions
'contributions' => 'ବ୍ଯବହରକାରୀନ୍କ ଅନୁଦାନ',
'mycontris'     => 'ମୋ ଅବଦାନ',
'month'         => 'ମାସରୁ (ଓ ତା ଆଗରୁ)',
'year'          => 'ବରସରୁ (ଓ ତା ଆଗରୁ)',

'sp-contributions-submit' => 'ଖୋଜିବା',

# What links here
'whatlinkshere'           => 'ଏଠି କଣ କଣ ଲିଁକ ଅଛି',
'whatlinkshere-page'      => 'ଫରଦ',
'whatlinkshere-hidelinks' => '$1 ଟି ଲିଁକ',

# Block/unblock
'ipblocklist-submit' => 'ସନ୍ଧାନ',
'blocklink'          => 'ଅଟକେଇ ଦିଅ',
'unblocklink'        => 'ଛାଡ଼',
'change-blocklink'   => 'ଓଗାଳିବାକୁ ବଦଳାଅ',
'contribslink'       => 'ଅବଦାନ',

# Thumbnails
'thumbnail-more' => 'ବଡ଼କର',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'ଆପଣଁକ ଇଉଜର ଫରଦ',
'tooltip-pt-mytalk'              => 'ଆପଣଁକ ଆଲୋଚନା ଫରଦ',
'tooltip-pt-preferences'         => 'ମୋ ପସଁଦସବୁ',
'tooltip-pt-watchlist'           => 'ବଦଳ ପାଇଁ ଆପଣ ଦେଖାଶୁଣା କରୁଥିବା ଫରଦଗୁଡ଼ିକର ତାଲିକା',
'tooltip-pt-mycontris'           => 'ଆପଣଁକ ଅବଦାନ',
'tooltip-pt-login'               => 'ଆପଣଁକୁ ଲଗିନ କରିବାକୁ କୁହାଯାଉଅଛି ସିନା, ବାଧ୍ୟ କରାଯାଉନାହିଁ',
'tooltip-pt-logout'              => 'ଲଗ ଆଉଟ',
'tooltip-ca-talk'                => 'ଏହି ଫରଦଟି ଉପରେ ଆଲୋଚନା',
'tooltip-ca-edit'                => 'ଆପଣ ଏହି ଫରଦଟିରେ ଅଦଳ ବଦଳ କରିପାରିବେ, ତେବେ ସାଇତିବା ଆଗରୁ ପ୍ରିଭିଉ ଦେଖଁତୁ ।',
'tooltip-ca-addsection'          => 'ନୂଆ ନିର୍ଘଁଟଟିଏ ଆଁରଭ କରିବା',
'tooltip-ca-viewsource'          => 'ଏଇ ଫରଦଟି କିଳାଯାଇଛି ।
ଆପଣ ଏହାର ମୂଳ ଦେଖିପାରିବେ',
'tooltip-ca-history'             => 'ଏହି ଫରଦର ପୁରୁଣା ସଁସ୍କରଣ',
'tooltip-ca-move'                => 'ଏଇ ଫରଦଟି ଘୁଁଚାଅ',
'tooltip-ca-watch'               => 'ଆପଣଙ୍କ ଦେଖାତାଲିକାରେ ଏଇ ଫରଦଟି ମିଶାନ୍ତୁ',
'tooltip-ca-unwatch'             => 'ନିଜ ଦେଖଣାତାଲିକାରୁ ଏହି ଫରଦଟି ବାହାର କରିଦିଅଁତୁ',
'tooltip-search'                 => '{{SITENAME}} ରେ ଖୋଜିବା',
'tooltip-search-go'              => 'ଏହି ଅବିକଳ ନାଁଟି ଥିଲେ ସେହି ଫରଦକୁ ଯିବା',
'tooltip-search-fulltext'        => 'ଏହି ଲେଖାଟି ପାଇଁ ଫରଦସବୁକୁ ଖୋଜିବା',
'tooltip-p-logo'                 => 'ପ୍ରଧାନ ପ୍ରୁଷ୍ଟ୍ଆ',
'tooltip-n-mainpage'             => 'ମୁଳ ଫରଦ',
'tooltip-n-mainpage-description' => 'ମୁଳ ଫରଦ',
'tooltip-n-portal'               => 'ଏହି ପ୍ରକଳ୍ପଟିରେ ଖୋଜା ଖୋଜି ପାଇଁ ଆପଣ କେମିତି ସାହାଜ୍ୟ କରିପାରିବେ',
'tooltip-n-currentevents'        => 'ନଗଦ କାମର ପଛପଟେ ଚାଲିଥିବା କାମର ତଥ୍ୟ',
'tooltip-n-recentchanges'        => 'ଉଇକିରେ ଏହିମାତ୍ର କରାଯାଇଥିବା ଅଦଳ ବଦଳ',
'tooltip-n-randompage'           => 'ଯାହିତାହି ଫରଦଟିଏ ଖୋଲ',
'tooltip-n-help'                 => 'ଖୋଜି ପାଇବା ଭଳି ଜାଗା',
'tooltip-t-whatlinkshere'        => 'ଏଇଠି ଯୋଡ଼ାଯାଇଥିବା ଫରଦସବୁର ତାଲିକା',
'tooltip-t-recentchangeslinked'  => 'ଏହି ଫରଦ ସାଗେ ଯୋଡ଼ା ଫରଦଗୁଡ଼ିକରେ ଏଇଲାଗେ କରାଯାଇଥିବା ଅଦଳବଦଳ',
'tooltip-feed-rss'               => 'ଏହି ଫରଦଟି ପାଇଁ ଆରଏସଏସ ଫିଡ',
'tooltip-feed-atom'              => 'ଏହି ଫରଦଟି ପାଇଁ ଆଟମ ଫିଡ',
'tooltip-t-upload'               => 'ଫାଇଲ ଅପଲୋଡ଼ କର',
'tooltip-t-specialpages'         => 'ନିଆରା ଫରଦ ତାଲିକା',
'tooltip-t-print'                => 'ଏହି ଫରଦର ଛପାହୋଇପାରିବା ଭର୍ସନ',
'tooltip-t-permalink'            => 'ସଁଶୋଧିତ ଏହି ଫରଦଟିର ସ୍ଥାୟି ଲିଁକ',
'tooltip-ca-nstab-main'          => 'ସୂଚି ଫରଦଟି ଦେଖଁତୁ',
'tooltip-ca-nstab-user'          => 'ଫାଇଲ ଫରଦଗୁଡ଼ିକ ଦେଖଁତୁ',
'tooltip-ca-nstab-special'       => 'ଏଇଟି ଗୋଟିଏ ବିଶେଷ ଫରଦ, ଆପଣ ଏହାକୁ ବଦଳାଇପାରିବେ ନାହିଁ',
'tooltip-ca-nstab-image'         => 'ଫାଇଲ ଫରଦଗୁଡ଼ିକ ଦେଖଁତୁ',
'tooltip-minoredit'              => 'ଏହାକୁ ଛୋଟ ବଦଳ ଭାବେ ଗଣ',
'tooltip-save'                   => 'ବଦଳଗୁଡ଼ିକ ସାଇତିରଖ',

# Browsing diffs
'previousdiff' => '← ପୁରୁଣା ବଦଳ',

# Media information
'show-big-image'       => 'ପୁରା ବଡ଼ ଆକାରରେ',
'show-big-image-thumb' => '<small>ଦେଖଣା ଚିତ୍ରର ଆକାର: $1 × $2 ପିକସେଲ</small>',

# Special:NewFiles
'ilsubmit' => 'ସନ୍ଧାନ',

# Bad image list
'bad_image_list' => 'ଗଢ଼ଣଟି ଏମିତି ହେବ:

କେବଳ (ଯେଉଁ ଧାଡ଼ିଗୁଡ଼ିକ * ରୁ ଆରଭ ହୋଇଥାଏ) ସେହି ସବୁକୁ ହିସାବକୁ ନିଆଯିବ ।
ଗୋଟିଏ ଧାଡ଼ିର ପ୍ରଥମ ଲିଁକଟି ଗୋଟିଏ ଖରାପ ଫାଇଲର ଲିଁକ ହୋଇଥିବା ଦରକାର ।
ପ୍ରଥମ ଲିକ ପରର ସବୁ ଲିକକୁ ସ୍ଵତଁତ୍ର ବୋଲି ଧରାଯିବ । ମାନେ, ସେଇସବୁ ଫରଦରେ ଯେଉଁଠି ଫାଇଲଟି ଧାଡି ଭିତରେ ରହିଥିବ ।',

# Metadata
'metadata' => 'ମେଟାଡାଟା',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'ସବୁ',
'monthsall'     => 'ସବୁ',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'ସନ୍ଧାନ',

# Special:SpecialPages
'specialpages' => 'ନିଆରା ଫରଦ',

);
