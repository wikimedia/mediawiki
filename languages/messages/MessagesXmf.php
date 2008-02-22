<?php
/** მარგალური (მარგალური)
 *
 * @addtogroup Language
 *
 * @author Dato deutschland
 * @author Malafaya
 * @author Alsandro
 */

$fallback = 'ka';

$messages = array(
# User preference toggles
'tog-underline' => 'ხაზ გუსვი ბმულემც:',

'underline-always' => 'ირო',
'underline-never'  => 'შურო',

# Dates
'sunday'        => 'ჟაშხა',
'monday'        => 'თუთაშხა',
'tuesday'       => 'თახაშხა',
'wednesday'     => 'ჯუმაშხა',
'thursday'      => 'ცაშხა',
'friday'        => 'ობიშხა',
'saturday'      => 'საბატონი',
'sun'           => 'ჟაშ',
'mon'           => 'თუთ',
'january'       => 'იანარი',
'february'      => 'ფრევალი',
'march'         => 'მარტი',
'april'         => 'აპრილი',
'may_long'      => 'მეესი',
'june'          => 'ივანობა',
'july'          => 'კვირკვე',
'august'        => 'არგუსო',
'september'     => 'ეკენია',
'october'       => 'გიმათუთა',
'november'      => 'გერგებათუთა',
'december'      => 'ქირსეთუთა',
'january-gen'   => 'იანარი',
'february-gen'  => 'ფრევალი',
'march-gen'     => 'მარტიშ',
'april-gen'     => 'აპრილი',
'may-gen'       => 'მეესიშ',
'june-gen'      => 'ივანობაშ',
'july-gen'      => 'კვირკვეშ',
'august-gen'    => 'არგუსო',
'september-gen' => 'ეკენიაშ',
'october-gen'   => 'გიმათუთაშ',
'november-gen'  => 'გერგებათუთაშ',
'december-gen'  => 'ქირსეთუთაშ',
'jan'           => 'იან',
'feb'           => 'ფრე',
'mar'           => 'მარ',
'apr'           => 'აპრ',
'may'           => 'მეე',
'jun'           => 'იავ',
'jul'           => 'კვი',
'aug'           => 'არგ',
'sep'           => 'ეკე',
'oct'           => 'გიმ',
'nov'           => 'გერ',
'dec'           => 'ქირ',

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|კატეგორია|კატეგორიალეფ}}',
'pagecategories'        => '{{PLURAL:$1|კატეგორია|კატეგორიალეფ}}',
'category_header'       => 'სტატიეფ კატეგორიას "$1"',
'subcategories'         => 'ქვეკატეგორიალეფ',
'category-media-header' => 'მედია კატეგორიას "$1"',
'category-empty'        => "''თენა კატეგორიას ვა რენა გვერდეფ დო მედია''",

'article'        => 'სტატია',
'cancel'         => 'გაუქვება',
'qbfind'         => 'მიგორე',
'qbpageoptions'  => 'თენა გვერდ',
'qbpageinfo'     => 'კონტექსტ',
'qbmyoptions'    => 'ჩქიმ გვერდეფ',
'qbspecialpages' => 'სპეციალურ გვერდეფ',
'moredotdotdot'  => 'სრულო...',
'mypage'         => 'ჩქიმ გვერდ',
'mytalk'         => 'დისკუსია ჩქიმც',
'navigation'     => 'ნავიგაცია',
'and'            => 'დო',

'tagline'          => '{{SITENAME}} გვერდიშე',
'help'             => 'დახვარება',
'search'           => 'გორუა',
'searchbutton'     => 'გორუა',
'go'               => 'სტატია',
'searcharticle'    => 'სტატია',
'history'          => 'გვერდიშ ისტორია',
'history_short'    => 'ისტორია',
'info_short'       => 'ინფორმაცია',
'print'            => 'დობეჭდი',
'edit'             => 'რედაქტირება',
'delete'           => 'წაშალი',
'deletethispage'   => 'წაშალ თე გვერდი',
'newpage'          => 'ახალ გვერდ',
'specialpage'      => 'სპეციალურ გვერდ',
'talk'             => 'დისკუსია',
'toolbox'          => 'ინსტრუმენტეფი',
'otherlanguages'   => 'შხვა ნინალეფს',
'redirectpagesub'  => 'გადამისამართ გვერდი',
'jumptonavigation' => 'ნავიგაცია',
'jumptosearch'     => 'გორუა',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'copyrightpage' => '{{ns:project}}:ავტორიშ უფლებალეფ',
'edithelp'      => 'დახვარება',
'edithelppage'  => 'Help:ტექსტიშ რედაქტირება (იოლი)',
'helppage'      => 'Help:დახვარება',
'mainpage'      => 'თავარ გვერდ',
'portal'        => 'საზოგადოებაშ გვერდეფ',
'portal-url'    => 'Project:საზოგადოებაშ გვერდეფ',
'privacy'       => 'ანონიმურობაშ პოლიტიკა',
'privacypage'   => 'Project:ანონიმურობაშ პოლიტიკა',

'ok'            => 'ჯგირო',
'retrievedfrom' => 'მიგორილ რე "$1"-იშე',
'editold'       => 'რედაქტირება',
'site-rss-feed' => '$1 RSS Feed',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'სტატია',
'nstab-user'     => 'მომხმარებელიშ გვერდ',
'nstab-media'    => 'მედიაშ გვერდ',
'nstab-special'  => 'სპეციალურ',
'nstab-project'  => 'პროექტიშ გვერდ',
'nstab-image'    => 'ფაილი',
'nstab-category' => 'კატეგორია',

# Main script and global functions
'nosuchspecialpage' => 'სპეციალურ გვერდეფ ვა არსეენც',

# General errors
'viewsourcefor' => '$1-იშ',

# Login and logout pages
'yourdomainname'     => 'თქვან დომენ',
'userlogin'          => 'მინულა',
'userlogout'         => 'გუმულა',
'gotaccountlink'     => 'მინულა',
'yourrealname'       => 'ნამდვილ სახელ *',
'yourlanguage'       => 'ნინა:',
'loginlanguagelabel' => 'ნინა: $1',

# Edit page toolbar
'italic_tip'      => 'კურსივ',
'link_sample'     => 'ბმულიშ სახელ',
'extlink_sample'  => 'http://www.example.com ბმულიშ სახელ',
'headline_sample' => 'სახელიშ ტექსტ',
'math_tip'        => 'მათემატიკურ ფორმულა (LaTeX)',
'nowiki_tip'      => 'ვიკიშ ფორმატირებიშ იგნორირება',
'media_tip'       => 'ბმულ ფაილს',
'sig_tip'         => 'თქვან ხემოჭარა დო ჟამ',
'hr_tip'          => 'ჰორიზონტალურ ღაზ (ნუ გამოიყენებთ ხშირას)',

# Edit pages
'summary'        => 'რეზიუმე',
'subject'        => 'თემა/სახელ',
'watchthis'      => 'თე გვერდიშ კონტროლ',
'editing'        => 'რედაქტირება - $1',
'editingsection' => 'რედაქტირება - $1 (სექცია)',
'yourtext'       => 'თქვან ტექსტ',

# History pages
'viewpagelogs'     => 'თე გვერდიშა სარეგისტრაციე ჟურნალეფიშ ხილუა',
'revisionasof'     => '$1-იშ ვერსია',
'previousrevision' => '←ჯვეშ ვერსია',
'last'             => 'ბოლო',
'orig'             => 'ორიგ',
'page_first'       => 'პირველი',
'page_last'        => 'ბოლო',
'histfirst'        => 'პირველი',
'histlast'         => 'ბოლო',
'historysize'      => '($1 ბაიტ)',
'historyempty'     => '(ცალიერ)',

# Revision feed
'history-feed-title'          => 'რედაქტირებიშ ისტორია',
'history-feed-description'    => 'თენა გვერდიშ რედაქტირებეფიშ ისტორია ვიკის',
'history-feed-item-nocomment' => '$1  $2-ს', # user at time

# Diffs
'history-title'           => '"$1" თირუალეფიშ ისტორია',
'difference'              => '(ვერსიალეფიშ დარება)',
'lineno'                  => 'ღაზი $1:',
'compareselectedversions' => 'გიშაგორილ ვერსიებიშ დარება',
'editundo'                => 'გაუქვება',

# Search results
'noexactmatch' => "'''გვერდ თე სახელით \"\$1\" ვა არსეენც.''' თქვან შეგიძლიათ [[:\$1|შექმნათ თენა გვერდ]].",
'nextn'        => 'უკულ $1',
'powersearch'  => 'გორუა',

# Preferences page
'mypreferences' => 'ჩქიმ კონფიგურაცია',

# Recent changes
'rclistfrom'      => 'ახალ თირუალეფიშ ხილუა დოჭყაფულ $1-იშე',
'hist'            => 'ისტ.',
'minoreditletter' => 'ჭ',

# Image list
'filehist'            => 'ფაილიშ ისტორია',
'filehist-user'       => 'მომხმარებელ',
'filehist-dimensions' => 'განზომილებეფ',
'filehist-filesize'   => 'ფაილიშ ზომა',
'filehist-comment'    => 'კომენტარ',
'imagelinks'          => 'ბმულეფ',
'imagelist_name'      => 'სახელ',

# MIME search
'mimesearch' => 'MIME გორუა',

# Miscellaneous special pages
'nbytes'               => '$1 ბაიტ',
'nmembers'             => '$1 მაკათურ',
'mostlinked'           => 'გვერდეფ, ნამუდგა არძას ბრალ ბმულეფ უღუნთ',
'mostlinkedcategories' => 'კატეგორიეფ, ნამუდგა არძას ბრალ ბმულეფ უღუნთ',
'mostcategories'       => 'სტატიეფ, ნამუდგა არძას ბრალ კატეგორიეფ უღუნთ',
'allpages'             => 'არძა გვერდ',
'specialpages'         => 'სპეციალურ გვერდეფ',
'ancientpages'         => 'ძვაშ გვერდეფ',
'move'                 => 'გინოღალა',

'alphaindexline' => '$1-იშე $2-შა',
'version'        => 'ვერსია',

# Special:Log
'log' => 'ჟურნალეფ',

# Watchlist
'watchlist'        => 'ჩქიმ კონტროლიშ გვერდეფ',
'mywatchlist'      => 'ჩქიმ კონტროლიშ გვერდეფ',
'removedwatchtext' => 'ასე გვერდ "[[:$1]]" ვარე თქვან კონტროლიშ გვერდეფც.',
'watch'            => 'კონტროლ',
'unwatch'          => 'კონტროლიშ გაუქვება',

# Delete/protect/revert
'rollbacklink'                => 'გაუქვება',
'protectcomment'              => 'კომენტარ:',
'protectexpiry'               => 'ვადა',
'protect-default'             => '(სტანდარტულ)',
'protect-level-autoconfirmed' => 'ვარეგისტრირებულ მომხმარებელეფიშ დაბლოკვა',
'protect-level-sysop'         => 'ხოლო ადმინისტრატორეფ',

# Undelete
'undelete-search-submit' => 'გორუა',

# Namespace form on various pages
'blanknamespace' => '(თავარ)',

# Contributions
'contributions' => 'მომხმარებელიშ ხანდა',
'mycontris'     => 'ჩქიმ ხანდა',

# What links here
'whatlinkshere-page'  => 'გვერდ:',
'linklistsub'         => '(ბმულეფ)',
'nolinkshere'         => 'თე გვერდც ვარე ბმულ',
'whatlinkshere-next'  => '{{PLURAL:$1|უკულიან|უკულიან $1}}',
'whatlinkshere-links' => '← ბმულეფ',

# Block/unblock
'ipboptions'         => '2 საათი:2 hours,1 დღა:1 დღა,3 დღა:3 დღალეფ,1 მარა:1 week,2 მარა:2 weeks,1 თუთა:1 month,3 თუთა:3 months,6 თუთა:6 months,1 წანა:1 year,განუსაზღვრელი ვადით:infinite', # display1:time1,display2:time2,...
'ipbotheroption'     => 'შხვა',
'ipblocklist-submit' => 'გორუა',
'blocklink'          => 'ბლოკირება',
'unblocklink'        => 'ბლოკიშ მონწყუმა',
'contribslink'       => 'ხანდა',

# Move page
'movepage'       => 'გვერდიშ გინოღალა',
'movearticle'    => 'გვერდიშ გინოღალა',
'newtitle'       => 'ახალ სახელ',
'move-watch'     => 'თე გვერდიშ კონტროლ',
'movepagebtn'    => 'გვერდიშ გინოღალა',
'movepage-moved' => '<big>\'\'\'"$1" გინოღალულ რე "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movedto'        => 'გინაღალულ რე',

# Export
'export' => 'გვერდეფიშ ექსპორტ',

# Tooltip help for the actions
'tooltip-pt-userpage'      => 'ჩქიმ გვერდ',
'tooltip-pt-mytalk'        => 'ჩქიმ დისკუსიაშ გვერდ',
'tooltip-pt-preferences'   => 'ჩქიმ კონფიგურაცია',
'tooltip-pt-mycontris'     => 'ჩქიმ არძა ხანდა',
'tooltip-pt-logout'        => 'გუმულა',
'tooltip-ca-move'          => 'თე გვერდიშ გინოღალა',
'tooltip-search'           => 'გორუა {{SITENAME}}',
'tooltip-n-mainpage'       => 'თავარ გვერდიშ ხილუა',
'tooltip-n-recentchanges'  => 'ვიკის ბოლო თირუალეფ',
'tooltip-n-sitesupport'    => 'ხუჯ დომკინით',
'tooltip-t-contributions'  => 'თე მომხმარებელის ხანდა',
'tooltip-t-specialpages'   => 'არძა სპეციალურ გვერდ',
'tooltip-ca-nstab-user'    => 'მომხმარებელიშ გვერდიშ ხილუა',
'tooltip-ca-nstab-project' => 'პროექტიშ გვერდ',

# Spam protection
'subcategorycount' => 'თე კატეგორიას $1 ქვეკატეგორია რე.',

# Browsing diffs
'previousdiff' => '← ჯვეშ თირუაშა',

# Media information
'file-info-size' => '($1 × $2 პიქსელ, ფაილიშ ზომა: $3, MIME ტიპ: $4)',

# Metadata
'metadata'        => 'მეტამონაცემეფ',
'metadata-expand' => 'დეტალეფიშ ხილუა სრულო',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'არძა',

);
