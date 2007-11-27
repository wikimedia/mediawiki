<?php
/** Ancient Greek (Ἀρχαία ἑλληνικὴ)
 *
 * @addtogroup Language
 *
 * @author LeighvsOptimvsMaximvs
 * @author Lefcant
 * @author AndreasJS
 * @author Neachili
 * @author SPQRobin
 * @author Nike
 * @author Yannos
 */

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ὑπογραμμίζειν συνδέσμους:',
'tog-highlightbroken'         => 'Μορφοῦν ἀπωλομένους συνδέσμους <a href="" class="new">οὐτωσί</a> (ἄλλως: οὐτωσί <a href="" class="internal">;</a>).',
'tog-justify'                 => 'Στοιχίζειν παραγράφους',
'tog-hideminor'               => 'Κρύπτειν μικρὰς ἐγγραφὰς ἐν προσφάταις ἀλλαγαῖς',
'tog-extendwatchlist'         => 'Ἐφορώμενα ἐκτείνειν ἵνα ἅπασαι φανῶσιν αἱ ἁρμόδιοι ἀλλαγαὶ',
'tog-usenewrc'                => 'Προσκεκοσμημέναι πρόσφαται ἀλλαγαί (JavaScript)',
'tog-numberheadings'          => 'Ἐξαριθμεῖν τίτλους αὐτομάτως',
'tog-showtoolbar'             => 'Δεικνύναι τὴν τῶν ἐργαλείων ἐπιμελείας μετόπην (JavaScript)',
'tog-editondblclick'          => 'Δέλτους δὶς θλιβείσας ἐπιμελεῖσθαι (JavaScript)',
'tog-editsection'             => 'Τμῆμα διὰ συνδέσμου [μεταγράφειν] μεταγράφειν παρέχειν',
'tog-editsectiononrightclick' => 'Τμῆμα μεταγράφειν παρέχειν <br /> διὰ τίτλον δεξιῷ ὀμφαλῷ θλίβειν (JavaScript)',
'tog-showtoc'                 => 'Δεικνύναι πίνακα περιεχομένων (ἐν δέλτοις περιεχούσαις πλείους τῶν 3 ἐπικεφαλίδων)',
'tog-rememberpassword'        => 'ἐνθυμεῖσθαι ἐγγραφήν μου ἐν τῷδε ὑπολογιστῇ',
'tog-editwidth'               => 'Πλαίσιον ἐπιμελείας εἰς πλήρες πλάτος',
'tog-watchcreations'          => 'Προστινθέναι τὰς δέλτους ἃς ποιῶ τοῖς ἐφορώμενοῖς ἔμου',
'tog-watchdefault'            => 'Προστινθέναι τὰς δέλτους ἃς μεταγράφω τοῖς ἐφορώμενοῖς ἔμου',
'tog-watchmoves'              => 'Προστινθέναι τὰς δέλτους ἃς κινῶ τοῖς ἐφορώμενοῖς ἔμου',
'tog-watchdeletion'           => 'Προστινθέναι τὰς δέλτους ἃς διαγράφω τοῖς ἐφορώμενοῖς ἔμου',
'tog-previewonfirst'          => 'Τῆς πρῶτης μεταγράφης, δεικνύναι τὸ προεπισκοπεῖν',
'tog-shownumberswatching'     => 'Δεικνύναι ἀριθμὸν παρακολουθούντων χρηστῶν',
'tog-watchlisthideown'        => 'Οὐ δηλοῦν τὰς ἐμὰς μεταβολὴς ἐν τὰ ἐφορώμενά μου',
'tog-watchlisthideminor'      => 'Οὐ δηλοῦν τὰς μικρὰς μεταβολὴς ἐν τὰ ἐφορώμενά μου',

'underline-always' => 'Ἀεὶ',
'underline-never'  => 'Οὔποτε',

'skinpreview' => '(Προεπισκοπεῖν)',

# Dates
'sunday'        => 'Κυριακή',
'monday'        => 'Δευτέρα',
'tuesday'       => 'Τρίτη',
'wednesday'     => 'Τετάρτη',
'thursday'      => 'Πέμπτη',
'friday'        => 'Παρασκευή',
'saturday'      => 'Σάββατοv',
'sun'           => 'Κυ',
'mon'           => 'Δε',
'tue'           => 'Τρ',
'wed'           => 'Τε',
'thu'           => 'Πε',
'fri'           => 'Πα',
'sat'           => 'Σα',
'january'       => 'Ἰανουάριος',
'february'      => 'Φεβρουάριος',
'march'         => 'Μάρτιος',
'april'         => 'Ἀπρίλιος',
'may_long'      => 'Μάιος',
'june'          => 'Ἰούνιος',
'july'          => 'Ἰούλιος',
'august'        => 'Αὔγουστος',
'september'     => 'Σεπτέμβριος',
'october'       => 'Ὀκτώβριος',
'november'      => 'Νοέμβριος',
'december'      => 'Δεκέμβριος',
'january-gen'   => 'Ἰανουαρίου',
'february-gen'  => 'Φεβρουαρίου',
'march-gen'     => 'Μαρτίου',
'april-gen'     => 'Ἀπριλίου',
'may-gen'       => 'Μαΐου',
'june-gen'      => 'Ἰουνίου',
'july-gen'      => 'Ἰουλίου',
'august-gen'    => 'Αὐγούστου',
'september-gen' => 'Σεπτεμβρίου',
'october-gen'   => 'Ὀκτωβρίου',
'november-gen'  => 'Νοεμβρίου',
'december-gen'  => 'Δεκεμβρίου',
'jan'           => 'Ἰαν',
'feb'           => 'Φεβ',
'mar'           => 'Μάρ',
'apr'           => 'Ἀπρ',
'may'           => 'Μάι',
'jun'           => 'Ἰούν',
'jul'           => 'Ἰούλ',
'aug'           => 'Αὔγ',
'sep'           => 'Σεπτ',
'oct'           => 'Ὀκτ',
'nov'           => 'Νοέμ',
'dec'           => 'Δεκ',

# Bits of text used by many pages
'categories'      => 'Γένη',
'pagecategories'  => '{{PLURAL:$1|Γένος|Γένη}}',
'category_header' => 'Χρήματα ἐν γένει "$1"',
'subcategories'   => 'Ὑπογένη',

'about'          => 'Περὶ',
'article'        => 'ἡ χρῆμα',
'newwindow'      => '(ἀνοίξεται ἐν νέᾳ θυρίδι)',
'cancel'         => 'Ἀκυροῦν',
'qbfind'         => 'Εὑρίσκειν',
'qbbrowse'       => 'Νέμου',
'qbedit'         => 'Μεταγράφειν',
'qbpageoptions'  => 'Ἥδε ἡ δέλτος',
'qbmyoptions'    => 'Δέλτοι μου',
'qbspecialpages' => 'Εἰδικαὶ δέλτοι',
'moredotdotdot'  => 'πλέον...',
'mypage'         => 'Δέλτος μου',
'mytalk'         => 'Διάλεκτός μου',
'anontalk'       => 'Διάλεκτος πρὸ τοῦδε τοῦ IP',
'navigation'     => 'Πλοήγησις',

# Metadata in edit box
'metadata_help' => 'Μεταδεδομένα:',

'errorpagetitle'    => 'Σφάλμα',
'returnto'          => 'Ἐπανέρχεσθαι εἰς $1.',
'tagline'           => 'Ἀπὸ {{SITENAME}}',
'help'              => 'Βοήθεια',
'search'            => 'Ζητεῖν',
'searchbutton'      => 'Ζητεῖν',
'go'                => 'Ἰέναι',
'searcharticle'     => 'Ἰέναι',
'history'           => 'Τὰ τῆς δέλτου πρότερα',
'history_short'     => 'Τὰ πρότερα',
'info_short'        => 'Μάθησις',
'printableversion'  => 'Ἐκτυπωτέα μορφὴ',
'permalink'         => 'Σύνδεσμος βέβαιος',
'print'             => 'Τυποῦν',
'edit'              => 'Μεταγράφειν',
'editthispage'      => 'Μεταγράφειν τήνδε τὴν δέλτον',
'delete'            => 'Σβεννύναι',
'deletethispage'    => 'Διαγράφειν τήνδε τὴν δέλτον',
'undelete_short'    => 'Ἐπανορθοῦν {{PLURAL:$1|ἕνα μεταγραφέν|$1 μεταγραφέντα}}',
'protect'           => 'Φυλλάττειν',
'protectthispage'   => 'Tήνδε τὴν δέλτον φυλάττειν',
'unprotect'         => 'μὴ φυλάττειν',
'unprotectthispage' => 'Tήνδε τὴν δέλτον  μὴ φυλάττειν',
'newpage'           => 'Δέλτος νέα',
'talkpage'          => 'Διαλέγε τήνδε τὴν δέλτον',
'talkpagelinktext'  => 'Διαλέγεσθαι',
'specialpage'       => 'Εἰδικὴ δέλτος',
'articlepage'       => 'Χρήματος δέλτον ὁρᾶν',
'talk'              => 'Διάλεξις',
'userpage'          => 'Ὁρᾶν δέλτον χρωμένου',
'imagepage'         => 'Ὁρᾶν εἰκόνος δέλτον',
'mediawikipage'     => 'Ὁρᾶν δέλτον μηνυμάτων',
'viewhelppage'      => 'Ὁρᾶν βοηθείας δέλτον',
'categorypage'      => 'Ὁρᾶν γένους δέλτον',
'viewtalkpage'      => 'Ὁρᾶν διάλεκτον',
'otherlanguages'    => 'ΒαρβαριστῚ',
'protectedpage'     => 'Πεφυλαγμένη δέλτος',
'jumpto'            => 'Πηδᾶν πρὸς:',
'jumptonavigation'  => 'περιήγησις',
'jumptosearch'      => 'ἐρευνᾶν',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Περὶ {{SITENAME}}',
'aboutpage'         => 'Project:Περί',
'currentevents'     => 'Τὰ γιγνόμενα',
'currentevents-url' => 'Τὰ γιγνόμενα',
'disclaimers'       => 'Ἀποποιήσεις',
'disclaimerpage'    => 'Project:Γενικὴ ἀποποίησις',
'edithelp'          => 'Βοήθεια περὶ τοῦ μεταγράφειν',
'edithelppage'      => 'Help:Βοήθεια περὶ τοῦ μεταγράφειν',
'faq'               => 'Τὰ πολλάκις αἰτηθέντα',
'faqpage'           => 'Project:Πολλάκις αἰτηθέντα',
'helppage'          => 'Help:Περιεχόμενα',
'mainpage'          => 'Κυρία Δέλτος',
'portal'            => 'Πύλη πολιτείας',
'sitesupport'       => 'Δῶρα',

'badaccess-group0' => 'Οὐκ ἔξεστί σοι ταῦτα διαπράττειν.',

'ok'                  => 'εἶεν',
'youhavenewmessages'  => 'Ἔχεις $1 ($2).',
'newmessageslink'     => 'νέαι ἀγγελίαι',
'newmessagesdifflink' => 'ἐσχάτη μεταβολή',
'editsection'         => 'μεταγράφειν',
'editold'             => 'μεταγράφειν',
'editsectionhint'     => 'Μεταγράφειν τὸ μέρος: $1',
'toc'                 => 'Περιεχόμενα',
'showtoc'             => 'δεικνύναι',
'hidetoc'             => 'κρύπτειν',
'viewdeleted'         => 'Ὁρᾶν $1;',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Χρῆμα',
'nstab-user'      => 'Δέλτος χρωμένου',
'nstab-mediawiki' => 'Ἀγγελία',
'nstab-category'  => 'Γένος',

# General errors
'error'        => 'Σφάλμα',
'filenotfound' => '"$1" οὐχ ηὑρέθη',

# Login and logout pages
'yourpassword'      => 'Σῆμα:',
'login'             => 'Ἐγγράφεσθαι',
'logout'            => 'Ἐξέρχεσθαι',
'userlogout'        => 'Ἐξέρχεσθαι',
'gotaccountlink'    => 'Ἐγγράφειν',
'createaccountmail' => 'ἠλεκτρονικῇ ἐπιστολῇ',
'youremail'         => 'Ἠλεκτρονικαὶ ἐπιστολαὶ:',
'yourlanguage'      => 'Γλῶττά σου:',
'yournick'          => 'Ἐπωνυμία:',
'email'             => 'ἠλεκτρονική ἐπιστολή',

# Edit page toolbar
'bold_sample' => 'Γράμματα παχέα',
'bold_tip'    => 'Γράμματα παχέα',

# Edit pages
'summary'            => 'Τὸ κεφάλαιον',
'minoredit'          => 'Μικρὰ ἥδε ἡ μεταβολή',
'watchthis'          => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'savearticle'        => 'Γράφειν τὴν δέλτον',
'preview'            => 'Τὸ προεπισκοπεῖν',
'showpreview'        => 'Προεπισκοπεῖν',
'showdiff'           => 'Δεικνύναι τὰς μεταβολάς',
'blockedtitle'       => 'Ἀποκεκλεισμένος ὁ χρώμενος',
'loginreqtitle'      => 'Δεῖ ἐγγράφειν',
'loginreqlink'       => 'Ἐγγράφειν',
'newarticle'         => '(νέα)',
'editing'            => 'Μεταγράφων $1',
'yourtext'           => 'Τὰ ὑπό σου γραφόμενα',
'yourdiff'           => 'Τὰ διαφέροντα',
'template-protected' => '(φυλλάττεται)',

# History pages
'currentrevisionlink' => 'Τὰ νῦν',
'next'                => 'ἡ ἐχομένη',
'last'                => 'ἡ ὑστάτη',
'page_first'          => 'πρώτη',
'page_last'           => 'ἐσχάτη',
'histfirst'           => 'πρώτη',
'histlast'            => 'ἐσχάτη',

# Revision deletion
'rev-delundel' => 'δεικνύναι/κρύπτειν',

# Search results
'powersearch' => 'Ζητεῖν',

# Preferences page
'preferences'        => 'Αἱρέσεις',
'mypreferences'      => 'Αἱρέσεις μου',
'qbsettings-none'    => 'Οὐδέν',
'math'               => 'Τὰ μαθηματικά',
'math_unknown_error' => 'ἄγνωστον σφάλμα',
'prefs-rc'           => 'Αἱ νέαι μεταβολαί',
'prefs-watchlist'    => 'Τὰ ἐφορώμενα',
'saveprefs'          => 'Γράφειν',
'textboxsize'        => 'Τὸ μεταγράφειν',
'searchresultshead'  => 'Ζητεῖν',

# Groups
'group-sysop'      => 'Γέρoντες',
'group-bureaucrat' => 'Ἔφοροι',

'group-sysop-member'      => 'Γέρων',
'group-bureaucrat-member' => 'Ἔφορος',

'grouppage-sysop'      => '{{ns:project}}:Γέροντες',
'grouppage-bureaucrat' => '{{ns:project}}:Ἔφοροι',

# User rights log
'rightsnone' => '(Οὐδέν)',

# Recent changes
'recentchanges'   => 'Αἱ νέαι μεταβολαί',
'diff'            => 'διαφ.',
'hist'            => 'Προτ.',
'hide'            => 'Κρύπτειν',
'show'            => 'Δεικνύναι',
'minoreditletter' => 'μ',
'newpageletter'   => 'Ν',

# Upload
'uploadnologin'     => 'Οὐκ ἐγγεγραμμένος',
'filedesc'          => 'Τὸ κεφάλαιον',
'fileuploadsummary' => 'Τὸ κεφάλαιον:',
'watchthisupload'   => 'Ἐφορᾶν τήνδε τὴν δέλτον',

# Image list
'ilsubmit'              => 'Ζητεῖν',
'imagelinks'            => 'Σύνδεσμοι',
'imagelist_name'        => 'Ὄνομα',
'imagelist_user'        => 'Χρώμενος',
'imagelist_size'        => 'Ὁπόσος',
'imagelist_description' => 'Διέξοδος',

# MIME search
'mimesearch' => 'MIME Ζητεῖν',

# Unused templates
'unusedtemplateswlh' => 'οἱ σύνδεσμοι οἱ ἄλλοι',

# Random page
'randompage' => 'Δέλτος τυχοῦσα',

'brokenredirects-edit'   => '(μεταγράφειν)',
'brokenredirects-delete' => '(διαγράφειν)',

# Miscellaneous special pages
'ncategories'  => '$1 {{PLURAL:$1|Γένος|Γένη}}',
'allpages'     => 'Πᾶσαι αἱ δέλτοι',
'newpages'     => 'Δέλτοι νέαι',
'move'         => 'κινεῖν',
'movethispage' => 'Κινεῖν τήνδε τὴν δέλτον',

# Book sources
'booksources-go' => 'Ἰέναι',

# Special:Log
'log-search-submit' => 'Ἰέναι',

# Special:Allpages
'allpagessubmit' => 'Ἰέναι',

# E-mail user
'emailmessage' => 'Ἀγγελία',
'emailsend'    => 'Πέμπειν',

# Watchlist
'watchlist'            => 'Τὰ ἐφορώμενά μου',
'mywatchlist'          => 'Τὰ ἐφορώμενά μου',
'watchlistfor'         => "(πρό '''$1''')",
'watch'                => 'Ἐφορᾶν',
'watchthispage'        => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'unwatch'              => 'Ἀνεφορᾶν',
'notanarticle'         => 'οὐ χρῆμα δέλτος',
'watchlist-show-own'   => 'Δεικνύναι τοὺς ἐράνους μου',
'watchlist-hide-own'   => 'Κρύπτειν τὰς ὑπ᾿ ἐμοῦ μεταβολάς',
'watchlist-show-minor' => 'Δεικνύναι τὰς μικρὰς μεταβολάς',
'watchlist-hide-minor' => 'Κρύπτειν τὰς μικρὰς μεταβολάς',

# Delete/protect/revert
'deletepage' => 'Διαγράφειν τὴν δέλτον',
'confirm'    => 'Κυροῦν',
'exblank'    => 'δέλτος κενὴ ἦν',

# Restrictions (nouns)
'restriction-edit' => 'Μεταγράφειν',
'restriction-move' => 'Kινεῖν',

# Restriction levels
'restriction-level-sysop'         => 'πλήρως διαφυλαττομένη',
'restriction-level-autoconfirmed' => 'ἡμιδιαφυλαττομένη',

# Undelete
'viewdeletedpage'        => 'Δεικνύναι διαγραφείσας δέλτους',
'undelete-search-submit' => 'Ζητεῖν',

# Contributions
'contributions' => 'Ἔρανοι χρωμένου',
'mycontris'     => 'Ἔρανοί μου',
'month'         => 'Μήν:',
'year'          => 'Ἔτος:',

'sp-contributions-newest' => 'Νεώτατοι',
'sp-contributions-oldest' => 'Ἀρχαιότατοι',
'sp-contributions-newer'  => 'Νεώτεροι $1',
'sp-contributions-older'  => 'Ἀρχαιότεροι $1',
'sp-contributions-submit' => 'Ζητεῖν',

# What links here
'whatlinkshere'       => 'Τὰ ἐνθάδε ἄγοντα',
'whatlinkshere-links' => '← σύνδεσμοι',

# Block/unblock
'ipblocklist-submit' => 'Ζητεῖν',
'infiniteblock'      => 'ἄπειρον',
'blocklink'          => 'ἀποκλῃειν',
'contribslink'       => 'Ἔρανοι',

# Move page
'movepage'    => 'Κινεῖν τὴν δέλτον',
'movearticle' => 'Κινεῖν τὴν δέλτον:',
'move-watch'  => 'Ἑφορᾶν τήνδε τὴν δέλτον',

# Namespace 8 related
'allmessagesname' => 'Ὄνομα',

# Tooltip help for the actions
'tooltip-pt-mytalk'     => 'Διάλεκτός μου',
'tooltip-pt-logout'     => 'Ἐξέρχεσθαι',
'tooltip-ca-talk'       => 'Διάλεκτος περὶ τῆς δέλτου',
'tooltip-ca-protect'    => 'Ἀμύνειν τῇδε τῇ δέλτῳ',
'tooltip-ca-delete'     => 'Διαγράφειν τήνδε τὴν δέλτον',
'tooltip-search'        => 'Ζητεῖν {{SITENAME}}',
'tooltip-p-logo'        => 'Δέλτος Μεγίστη',
'tooltip-ca-nstab-main' => 'χρῆμα δέλτον ὁρᾶν',
'tooltip-ca-nstab-user' => 'Δέλτος χρωμένου ὁρᾶν',
'tooltip-minoredit'     => 'Δεικνύναι ἥδε ἡ μεταβολή μικρά εἴναι',
'tooltip-save'          => 'Γράφειν τὰς μεταβολάς σου',

# Attribution
'and'    => 'καί',
'others' => 'ἄλλοι',

'exif-componentsconfiguration-0' => 'Οὐκ ἔστι',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Πᾶσαι',
'imagelistall'     => 'Πᾶσαι',
'watchlistall2'    => 'Πάντα',

# Trackbacks
'trackbackremove' => ' ([$1 Διαγράφειν])',

# action=purge
'confirm_purge_button' => 'εἶεν',

# Multipage image navigation
'imgmultipageprev' => '← Δέλτος προτέρα',
'imgmultipagenext' => 'Δέλτος ἡ ἐχομένη →',
'imgmultigo'       => 'Ἰέναι!',
'imgmultigotopre'  => 'Προσιέναι πρὸς τὴν δέλτον',

# Table pager
'table_pager_next'         => 'Δέλτος ἡ ἐχομένη',
'table_pager_prev'         => 'Δέλτος προτέρα',
'table_pager_first'        => 'Δέλτος πρώτη',
'table_pager_last'         => 'Δέλτος ἐσχάτη',
'table_pager_limit_submit' => 'Ἰέναι',

# Auto-summaries
'autosumm-new' => 'Δέλτος νέα: $1',

# Size units
'size-bytes'     => '$1 Δ',
'size-kilobytes' => '$1 ΧΔ',
'size-megabytes' => '$1 ΜΔ',
'size-gigabytes' => '$1 ΓΔ',

# Watchlist editor
'watchlistedit-raw-titles' => 'Τίτλοι:',

);
