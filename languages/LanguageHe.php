<?php

require_once("LanguageUtf8.php");

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesHe = array(
	-2	=> "Media",
	-1	=> "מיוחד",
	0	=> "",
	1	=> "שיחה",
	2	=> "משתמש",
	3	=> "שיחת_משתמש",
	4	=> "ויקיפדיה",
	5	=> "שיחת_ויקיפדיה",
	6	=> "תמונה",
	7	=> "שיחת_תמונה",
	8	=> "MediaWiki",
	9	=> "MediaWiki_talk",
	10	=> "Template",
	11	=> "Template_talk",
	12	=> "Help",
	13	=> "Help_talk",
	14	=> "Category",
	15	=> "Category_talk",

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsHe = array(
	"ללא", "קבוע משמאל", "קבוע מימין", "צף משמאל"
);

/* private */ $wgSkinNamesHe = array(
	'standard' => "רגיל",
	'nostalgia' => "נוסטלגי",
	'cologneblue' => "מים כחולים",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);



/* private */ $wgBookstoreListHe = array(
	"מיתוס" => "http://www.mitos.co.il/ ",
	"ibooks" => "http://www.ibooks.co.il/",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);


# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesHe = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "קבע העדפות משתמש",
	"Watchlist"		=> "רשימת המעקב שלי",
	"Recentchanges" => "דפים שעודכנו לאחרונה",
	"Upload"		=> "העלה קובץ לשרת",
	"Imagelist"		=> "רשימת תמונות",
	"Listusers"		=> "משתמשים רשומים",
	"Statistics"	=> "סטטיסטיקות האתר",
	"Randompage"	=> "מאמר אקראי",

	"Lonelypages"	=> "מאמרים יתומים",
	"Unusedimages"	=> "תמונות יתומות",
	"Popularpages"	=> "מאמרים פופלריים",
	"Wantedpages"	=> "דפים מבוקשים ביותר",
	"Shortpages"	=> "מאמרים קצרים",
	"Longpages"		=> "מאמרים ארוכים",
	"Newpages"		=> "מאמרים חדשים",
	"Ancientpages"		=> "Oldest pages",
	"Allpages"		=> "כל המאמרים לפי כותרת",

	"Ipblocklist"	=> "כתובות IP חסומות",
	"Maintenance" => "דף תחזוקה",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "משאבי ספרות חיצוניים",
	"Export"	=> "XML export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesHe = array(
	"Blockip"		=> "חסום כתובת IP",
	"Asksql"		=> "שאילתא לבסיס-הנתונים",
	"Undelete"		=> "צפה ושחזר דפים מחוקים"
);

/* private */ $wgDeveloperSpecialPagesHe = array(
	"Lockdb"		=> "הפוך את בסיס-הנתונים לקריא-בלבד",
	"Unlockdb"		=> "החזר הרשאת כתיבה לבסיס-הנתונים",
);

/* private */ $wgAllMessagesHe = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# User Toggles
#

"tog-hover"		=> "הצג טיפ כאשר מצביעים על קישור",
"tog-underline" => "סמן קישורים בקו תחתי",
"tog-highlightbroken" => "סמן קישורים לדפים שלא נכתבו באדום (או: סימן שאלה)",
"tog-justify"	=> "ישר פסקאות",
"tog-hideminor" => "הסתר שינויים משניים ברשימת השינויים האחרונים",
"tog-usenewrc" => "רשימת שינויים אחרונים משופרת (לא מתאים לכל דפדפן)",
"tog-numberheadings" => "מספר ראשי-פרקים אוטומטית",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editondblclick" => "ערוך דפים בלחיצה כפולה (דורש ג'אווה סקריפט)",
"tog-editsection"=>"Enable section editing via [edit] links",
"tog-editsectiononrightclick"=>"Enable section editing by right clicking<br> on section titles (JavaScript)",
"tog-showtoc"=>"Show table of contents<br>(for articles with more than 3 headings",
"tog-rememberpassword" => "זכור את הסיסמא שלי בפעמים הבאות",
"tog-editwidth" => "תיבת העריכה ברוחב מלא",
"tog-watchdefault" => "עקוב אחרי מאמרים שערכתי או יצרתי",
"tog-minordefault" => "הגדר כל פעולת עריכה כמשנית אם לא צויין אחרת",
"tog-previewontop" => "הצג תצוגה מקדימה לפני קופסת העריכה (או: אחריה)",
"tog-nocache" => "נטרל משיכת דפים מזכרון המטמון שבשרת",

# Dates
#

'sunday' => "ראשון",
'monday' => "שני",
'tuesday' => "שלישי",
'wednesday' => "רביעי",
'thursday' => "חמישי",
'friday' => "שישי",
'saturday' => "שבת",
'january' => "ינואר",
'february' => "פברואר",
'march' => "מרץ",
'april' => "אפריל",
'may_long' => "מאי",
'june' => "יוני",
'july' => "יולי",
'august' => "אוגוסט",
'september' => "ספטמבר",
'october' => "אוקטובר",
'november' => "נובמבר",
'december' => "דצמבר",
'jan' => "ינו'",
'feb' => "פבר'",
'mar' => "מרץ",
'apr' => "אפר'",
'may' => "מאי",
'jun' => "יוני",
'jul' => "יולי",
'aug' => "אוג'",
'sep' => "ספט'",
'oct' => "אוק'",
'nov' => "נוב'",
'dec' => "דצמ'",
# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "עמוד ראשי",
"about"			=> "אודות",
"aboutwikipedia" => "אודות ויקיפדיה",
"aboutpage"		=> "ויקיפדיה:אודות",
'article' => 'מאמר', #????
"help"			=> "עזרה",
"helppage"		=> "ויקיפדיה:עזרה",
"wikititlesuffix" => "ויקיפדיה",
"bugreports"	=> "דווח על באגים",
"bugreportspage" => "ויקיפדיה:דווח_על_באגים",
"faq"			=> "שאלות ותשובות",
"faqpage"		=> "ויקיפדיה:שאלות_ותשובות",
"edithelp"		=> "עזרה לעריכה",
"edithelppage"	=> "ויקיפדיה:איך_עורכים_דף",
"cancel"		=> "בטל",
"qbfind"		=> "חפש",
"qbbrowse"		=> "דפדף",
"qbedit"		=> "ערוך",
"qbpageoptions" => "אפשרויות דף",
"qbpageinfo"	=> "מידע על הדף",
"qbmyoptions"	=> "האפשרויות שלי",
"mypage"		=> "הדף שלי",
"mytalk"		=> "דף השיחה שלי",
"currentevents" => "-",
"errorpagetitle" => "שגיאה",
"returnto"		=> "חזור ל-$1",
"fromwikipedia"	=> "מתוך ויקיפדיה, האנציקלופדיה החופשית.",
"whatlinkshere"	=> "דפים המקושרים לכאן",
"help"			=> "עזרה",
"search"		=> "חפש",
"history"		=> "גירסאות קודמות",
"history_short" => "גירסאות קודמות",
"printableversion" => "גירסה להדפסה",
"edit"			=> "עריכה",
"editthispage"	=> "ערוך דף זה",
"deletethispage" => "מחק דף זה",
"protectthispage" => "הפוך דף זה למוגן",
"unprotectthispage" => "הסר הגנה מדף זה",
"newpage" => "דף חדש",
"talkpage"		=> "שוחח על דף זה",
"articlepage"	=> "צפה במאמר",
"subjectpage"	=> "צפה בנושא", # For compatibility
'talk' => 'שיחה',
'toolbox' => 'Toolbox',
"userpage" => "צפה בדף המשתמש",
"wikipediapage" => "צפה בדף המטא",
"imagepage" => 	"צפה בדף התמונה",
"viewtalkpage" => "צפה בדף השיחה",
"otherlanguages" => "שפות אחרות",
"redirectedfrom" => "(הופנה מ - $1)",
"lastmodified"	=> "שונה לאחרונה ב $1.",
"viewcount"		=> "דף זה נצפה $1 פעמים.",
"gnunote" => "מוגש תחת הרשיון לשימוש חופשי <a class=internal href='/wiki/GNU_FDL'>GNU FDL</a>.",
"printsubtitle" => "(מ - http://he.wikipedia.org)",
"protectedpage" => "דף מוגן",
"administrators" => "ויקיפדיה:מפעיל_מערכת",
"sysoptitle"	=> "דרושה הרשאת מפעיל מערכת",
"sysoptext"		=> "בכדי לבצע פעולה זו דרושת הרשאת מפעיל. ראה $1.",
"developertitle" => "דרושה הרשאת מפתח מערכת",
"developertext"	=> "בכדי לבצע פעולה זו דרושת הרשאת מפתח, ראה $1.",

"nbytes"		=> "$1 בתים",
"go"			=> "לך",
"ok"			=> "אישור",
"sitetitle"		=> "ויקיפדיה",
"sitesubtitle"	=> "האנציקלופדיה החופשית",
"retrievedfrom" => "מקור: $1",
"newmessages" => "יש לך $1",
"newmessageslink" => "הודעות חדשות",
"editsection" => "עריכה",

# Main script and global functions
#
"nosuchaction"	=> "אין פעולה כזו",
"nosuchactiontext" => "תוכנת ויקיפדיה אינה מכירה את הפעולה המצויינת ב-URL.",
"nosuchspecialpage" => "אין דף מיוחד בשם זה",
"nospecialpagetext" => "ביקשת דף מיוחד שאינו מוכר למערכת ויקיפדיה.",

# General errors
#
"error"			=> "שגיאה",
"databaseerror" => "שגיאת בסיס-נתונים",
"dberrortext"	=> "ארעה שגיאת תחביר בשאילתא לבסיס-הנתונים.
השאילתה האחרונה שבוצעה לבסיס-הנתונים היתה: 
<blockquote><tt>$1</tt></blockquote>
מהפונקציה \"<tt>$2</tt>\".
בסיס-הנתונים החזיר את השגיאה: \"<tt>$3: $4</tt>\" ",
"noconnect"		=> "לא הצליח נסיון ההתחברות לבסיס-הנתונים על $1",
"nodb"			=> "לא ניתן לבחור את בסיס-הנתונים $1",
"readonly"		=> "בסיס-הנתונים נעול",
"enterlockreason" => "הזן סיבה לנעילת בסיס-הנתונים, וכלול הערכה לגבי מתי הנעילה תשוחרר.",
"readonlytext"	=> "בסיס-נתונים זה של ויקיפדיה נעול ברגע זה להזנת נתונים ושנויים. ככל הנראה מדובר בתחזוקה שותפת, שלאחריה הוא יחזור לפעולתו הרגילה.
מפעיל המערכת שנעל אותו סיפק את ההסבר הבא:
$1",
"missingarticle" => "בסיס-הנתונים לא מצא את הטקסט של דף שאותו הוא אמור היה למצוא, בשם \"$1\".
זוהי אינה שגיאת בסיס-נתונים, אבל סביר להניח שמדובר בבאג בתוכנה.
אנא דווח זאת למפעיל מערכת, תוך שמירת פרטי ה-URL.",
"internalerror" => "שגיאה פנימית",
"filecopyerror" => "העתקת \"$1\" ל-\"$2\" לא הצליחה.",
"filerenameerror" => "שינוי השם של \"$1\" ל-\"$2\" לא הצליח.",
"filedeleteerror" => "מחיקת \"$1\" לא הצליחה.",
"filenotfound"	=> "הקובץ \"$1\" לא נמצא.",
"unexpected"	=> "ערך לא צפוי: \"$1\"=\"$2\"",
"formerror"		=> "שגיאה: לא יכול לשלוח טופס.",	
"badarticleerror" => "לא ניתן לבצע פעולה זו בדף זה.",
"cannotdelete"	=> "מחיקת הדף או קובץ התמונה לא הצליחה. (יתכן שנמחקה כבר על-ידי מישהו אחר)",
"badtitle"		=> "כותרת שגויה",
"badtitletext"	=> "כותרת הדף המבוקש היתה לא-חוקית, ריקה, קישור ויקי פנימי, או פנים שפה שגוי.",
"perfdisabled" => "שירות זה הופסק זמנית בזמן שעות העומס בכדי לא לפגוע בביצועי המערכת, הוא יחזור בין השעות 02:00 ו-14:00(UTC). עמכם הסליחה!",

# Login and logout pages
#
"logouttitle"	=> "יציאה מהחשבון",
"logouttext"	=> "יצאת עתה מהחשבון. את/ה יכול/ה להמשיך ולעשות שימוש בויקיפדיה בצורה אנונימית, או שתוכל/י לשוב ולהכנס לאתר שנית עם שם משתמש זהה או אחר. \n",

"welcomecreation" => "<b>ברוך הבא $1!</b>, חשבונך נפתח. אל תשכח להתאים את הגדרות המשתמש שלך.",
"loginpagetitle" => "כניסת משתמש",
"yourname"		=> "שם משתמש",
"yourpassword"	=> "סיסמא",
"yourpasswordagain" => "הקש סיסמא שנית",
"newusersonly"	=> " (רק אם את/ה משתמש חדש)",
"remembermypassword" => "זכור את הסיסמא שלי בפעם הבאה.",
"loginproblem"	=> "<b>ארעה שגיאה בכניסה לאתר. </b><br>!נסה שנית",
"alreadyloggedin" => "<font color=red><b> משתמש $1, כבר ביצעת כניסה לאתר!</b></font><br>\n",

"login"			=> "כניסה לחשבון",
"userlogin"		=> "כניסה לחשבון",
"logout"		=> "יציאה מהחשבון",
"userlogout"	=> "יציאה מהחשבון",
"notloggedin"	=> "לא בחשבון",
"createaccount"	=> "צור משתמש חדש",
"badretype"		=> "הסיסמאות שהזנת אינן מתאימות.",
"userexists"	=> "שם המשתמש שבחרת נמצא בשימוש. אנא בחר/י שם אחר.",
"youremail"		=> "כתובת הדואר האלקטרוני שלך",
"yournick"		=> "בחר כינוי (לחתימות)",
"emailforlost"	=> "אם תשכח/י את סיסמתך, תוכל/י לבקש שסיסמא חדשה תשלח לך לכתובת הדואר האלקטרוני.",
"loginerror"	=> "שגיאה בכניסה לאתר",
"noname"		=> "לא הזנת שם משתמש חוקי",
"loginsuccesstitle" => "הכניסה הצליחה",
"loginsuccess"	=> "נכנסת לויקיפדיה בשם \"$1\".",
"nosuchuser"	=> "אין משתמש בשם \"$1\".
וודא/י שהאיות נכון, או השתמש/י בטופס שלהלן ליצור חשבון משתמש חדש.",
"wrongpassword"	=> "הסיסמא שהקלדת שגויה. אנא נסה/י שנית.",
"mailmypassword" => "שלחו לי סיסמא חדשה",
"passwordremindertitle" => "תזכורת סיסמא מויקיפדיה",
"passwordremindertext" => "מישהו (ככה\"נ את/ה, מכתובת IP מספר $1) ביקש/ה שנשלח לך סיסמת ויקיפדיה חדשה.
הסיסמא עבור משתמש \"$2\" היא עתה \"$3\".
עליך להכנס לאתר ולשנות את סיסמתך בהקדם האפשרי.",
"noemail"		=> "לא רשומה כתובת דואר אלקטרוני עבור משתמש  \"$1\".",
"passwordsent"	=> "סיסמא חדשה נשלחה לכתובת הדואר האלקטרוני הרשומה עבור \"$1\".
אנא הכנס חזרה לאתר אחרי שתקבל אותה.",

# Edit pages
#
"summary"		=> "תקציר",
"minoredit"		=> "זהו שינוי משני",
"watchthis"		=> "עקוב אחר דף זה",
"savearticle"	=> "שמור דף",
"preview"		=> "תצוגה מקדימה",
"showpreview"	=> "הראה תצוגה מקדימה",
"blockedtitle"	=> "המשתמש חסום",
"blockedtext"	=> "שם המשתמש או כתובת ה-IP שלך נחסמו על-ידי $1.
הסיבה שניתנה היא:<br>''$2''<p>אתה יכול ליצור קשר עם $1 או אחד מ[[ויקיפדיה:מפעילי_מערכת]] כדי לדון בחסימה.",
"newarticle"	=> "(חדש)",
"newarticletext" => "כתוב כאן את הטקסט עבור הדף החדש:",
"anontalkpagetext" => " ---- ''זהו דף שיחה של משתמש/ת שאין לו/ה חשבון במערכת ומזוהה רק לפי כתובת ה-IP שלו/ה. יתכן ודף זה ישותף עם משתמשים אנונימיים אחרים''",
"noarticletext" => "(אין עדיין טקסט בדף זה)",
"updated"		=> "(מעודכן)",
"note"			=> "<strong>הערה:</strong>",
"previewnote"	=> "זכור שזו רק תצוגה מקדימה, והדף עדיין לא נשמר!",
"previewconflict" => "תצוגה מקדימה זו מציגה כיצד יראה הטקסט בחלון העריכה העליון אם תבחר לשמור אותו.",
"editing"		=> "עורך את $1",
"editconflict"	=> "התנגשות עריכה: $1",
"explainconflict" => "משתמש אחר שינה את הדף מאז שהתחלת לערוך אותו.
חלון העריכה העליון מכיל את הטקסט בדף כפי שהוא עתה.
השינויים שלך מוצגים בחלון העריכה התחתון.
עליך למזג את שינוייך לתוך הטקסט הקיים.
<b>רק</b> הטקסט בחלון העריכה העליון ישמר כשתלחץ על \"שמור\".",
"yourtext"		=> "הטקסט שלך",
"storedversion" => "גירסה שמורה",
"editingold"	=> "<strong>זהירות: את/ה עורך/ת גירסה לא עדכנית של הדף הזה.
אם תשמור/י, כל השינויים שנעשו מאז גירסה זו יאבדו. </strong>\n",
"yourdiff"		=> "הבדלים",
"copyrightwarning" => "אנא שים/י לב שכל תרומה לויקיפידיה מוצאת לאור תחת הרשיון לשימוש חופשי במסמכים של GNU (ראה $1 לפרטים). אם אינך רוצה שעבודתך תהיה זמינה לעריכה על ידי אחרים, ותופץ לעיני כל, אל תפרסם/י אותה פה. כמו-כן, את/ה מבטיח/ה לנו כי את/ה כתבת את הטקסט הזה בעצמך, או העתקת אותו ממקור שאינו מוגן על-ידי זכויות יוצרים. 
<strong> אל תעשו שימוש בחומר המוגן בזכויות יוצרים ללא רשות! </strong>",
"longpagewarning" => "אזהרה: גודל דף זה הוא $1 קילובייט. בדפדפנים מסוימים יהיו בעיות בעריכת דף הגדול מ32 קילובייט. אנא שיקלו לחלק דף זה לדפים קטנים יותר",
"readonlywarning" => "אזהרה: בסיס-הנתונים ננעל לצורך תחזוקה. אי אפשר לשמור את העריכות בזמן זה. אתם יכולים להשתמש בעורך חיצוני עד שתסוים התחזוקה. אנו מתנצלים על התקלה.",
"protectedpagewarning" => "אזהרה: דף זה הוא דף מוגן וניתן לעריכה רק על ידי מפעילים, אנא ודאו שאתם פועלים לפי העקרונות לעריכת דפים אלו",


# History pages
#
"revhistory"	=> "היסטוריית שינויים",
"nohistory"		=> "אין היסטוריית שינויים עבור דף זה",
"revnotfound"	=> "גירסה זו לא נמצאה",
"revnotfoundtext" => "הגירסה הישנה של דף זה לא נמצאה. אנא בדוק/י את כתובת הקישור \n",
"loadhist"		=> "טוען את היסטוריית השינויים של הדף",
"currentrev"	=> "גירסה נוכחית",
"revisionasof"	=> "גירסה מתאריך $1",
"cur"			=> "נוכ",
"next"			=> "הבא",
"last"			=> "אחרון",
"orig"			=> "מקור",
"histlegend"	=> "מקרא: (נוכ) = הבדלים עם הגירסה הנוכחית, (אחרון) = הבדלים עם הגירסה הקודמת, מ = שינוי משני",

# Diffs
#
"difference"	=> "(הבדלים בין גירסאות)",
"loadingrev"	=> "טוען גירסה להצגת הבדלים",
"lineno"		=> "שורה $1:",
"editcurrent"	=> "ערוך גירסה נוכחית של הדף",

# Search results
#
"searchresults" => "תוצאות חיפוש",

"searchresulttext" => "למידע נוסף על חיפוש בויקיפדיה, ראה [[ויקיפדיה:חיפוש|חיפוש בויקיפדיה]].",
"searchquery"	=> "לחיפוש \"$1\"",
"badquery"		=> "שגיאה בניסוח שאילתא.",
"badquerytext"	=> " לא הצלחנו לבצע את השאילתא. ככל הנראה כיוון שניסית לחפש מילה בעלת פחות משלוש אותיות. חיפוש כזה עדיין אינו נתמך במערכת. יתכן גם ששגית בהקלדת השאילתא לדוגמה \"דג וגם וגם משקל\".
אנא נסה שאילתא אחרת.",
"matchtotals"	=> "לחיפוש \"$1\" נמצאו $2 מאמרים עם כותרות תואמות ו$3 מאמרים עם תוכן תואם",
"titlematches"	=> "כותרות מאמרים תואמות",
"notitlematches" => "אין כותרות מאמרים תואמות",
"textmatches"	=> "מאמרים עם תוכן תואם",
"notextmatches"	=> "אין מאמרים עם תוכן תואם",
"prevn"			=> "$1 הקודמים",
"nextn"			=> "$1 הבאים",
"viewprevnext"	=> "צפה ב - ($1) ($2) ($3).",
"showingresults" => "מציג <b>$1</b> תוצאות ממספר #$2:",
"showingresultsnum" => "מציג <b>$1</b> תוצאות ממספר #$2:",
"nonefound"		=> "לא נמצאו מאמרים עם תוכן תואם, אנא ודאו שהיקשתם את החיפוש נכונה. אם אכן הקשתם נכונה אז נסו אולי לחפש נושא כללי יותר.",
"powersearch" => "חפש",
"powersearchtext" => "
חפש במרחבי שם:<br>
$1<br>
$2 הצג גם דפי הפנייה
$3 $9",
"blanknamespace" => "(ראשי)",


# Preferences page
#
"preferences"	=> "העדפות",
"prefsnologin" => "לא נרשמת באתר",
"prefsnologintext"	=> "עליך להיכנס לחשבון כדי לשנות העדפות משתמש",
"prefslogintext" => "נכנסת בשם \"$1\", מספרך הסידורי הוא $2.",
"prefsreset"	=> "העדפותיך שוחזרו מברירת המחדל.",
"qbsettings"	=> "הגדרות סרגל מהיר", 
"changepassword" => "שנה סיסמא",
"skin"			=> "רקע",
"math"			=> "תצוגת נוסחאות מתמטיות",
"math_failure"		=> "עיבוד הנוסחה נכשל",
"math_unknown_error"	=> "שגיאה לא ידועה",
"math_unknown_function"	=> "פונקציה לא מוכרת",
"math_lexing_error"	=> "שגיאת לקסינג",
"math_syntax_error"	=> "שגיאת תחביר",
"saveprefs"		=> "שמור העדפות",
"resetprefs"	=> "שחזר ברירת מחדל",
"oldpassword"	=> "סיסמא ישנה",
"newpassword"	=> "סיסמא חדשה",
"retypenew"		=> "הקלד סיסמא חדשה שנית",
"textboxsize"	=> "גודל תיבת טקסט",
"rows"			=> "שורות",
"columns"		=> "עמודות",
"searchresultshead" => "הגדרות לתוצאות החיפוש",
"resultsperpage" => "מס' תוצאות בעמוד",
"contextlines"	=> "שורות לכל תוצאה",
"contextchars"	=> "מס' תווי קונטקסט בשורה",
"stubthreshold" => "סף להצגת מאמרים קצרים (קצרמרים)",
"recentchangescount" => "מס' שינויים שיוצגו בדף שינויים אחרונים",
"savedprefs"	=> "העדפותיך נשמרו.",
"timezonetext"	=> "הפרש השעות בינך לבין השרת (UTC).",
"localtime"	=> "זמן מקומי",
"timezoneoffset" => "הפרש",
"servertime"	=> "השעה הנוכחית בשרת היא",
"guesstimezone" => "מלא מהדפדפן",
"emailflag"		=> "הסתר כתובת דואר-אלקטרוני ממשתמשים אחרים.",

# Recent changes
#
"changes" => "שינויים",
"recentchanges" => "שינויים אחרונים",
"recentchangestext" => "עקבו אחר השינויים האחרונים בויקיפדיה בדף זה.",
"rcloaderr"		=> "טוען שינויים אחרונים",
"rcnote"		=> "להלן <b>$1</b> השינויים האחרונים שבוצעו ב-$2 הימים האחרונים:",
"rcnotefrom"	=> "להלן <b>$1</b> השינויים האחרונים שבוצעו החל מתאריך <b>$2</b>:",
"rclistfrom"	=> "הצג שינויים חדשים החל מ-$1",
# "rclinks"		=> "הצג $1 שינויים אחרונים ב-$2 השעות האחרונות / $3 הימים האחרונים",
"rclinks"		=> "הצג $1 שינויים אחרונים ב-$2 הימים האחרונים.",
"rchide"		=> "ב-$4 טפסים; $1 שינויים משניים; $2 מרחבי שמות מיוחדים; $3 שינויים כפולים.",
"diff"			=> "הבדל",
"hist"			=> "היסטוריה",
"hide"			=> "הסתר",
"show"			=> "הצג",
"tableform"		=> "טבלה",
"listform"		=> "רשימה",
"nchanges"		=> "$1 שינויים",
"minoreditletter" => "מ",
"newpageletter" => "ח",

# Upload
#
"upload"		=> "העלה קובץ לשרת",
"uploadbtn"		=> "העלה קובץ",
"uploadlink"	=> "העלה תמונות",
"reupload"		=> "העלה שנית",
"reuploaddesc"	=> "חזור לטופס העלאת קבצים לשרת.",
"uploadnologin" => "לא נכנסת לאתר",
"uploadnologintext"	=> "עליך להיכנס לחשבון במערכת כדי להעלות קובץ",
"uploadfile"	=> "העלה קובץ",
"uploaderror"	=> "שגיאה בהעלאת הקובץ",
"uploadtext"	=> "'''עצור!''' לפני שאתה מעלה קובץ אנא וודא שקראת ופעלת לפי נהלי השימוש בתמונות של ויקיפדיה.

השתמש בטופס שלהלן להעלות קובץ תמונה חדש לשימוש במאמר שלך.
במרבית הדפדנים תראה כפתור \"Browse...\", שיפתח את חלון פתיחת הקבצים הסטנדרטי של מערכת ההפעלה שלך.
בחירת קובץ תציג את שמו בשדה הטקסט שליד הכפתור.
עליך גם לסמן את התיבה בה אתה מצהיר שאינך מפר זכויות יוצרים בהעלתך את הקובץ לשרת.
לחץ על כפתור \"העלה קובץ\" כדי לסיים את ההעלאה.
התהליך עלול לקחת זמן מה אם אתה גולש בקישור אינטרנט איטי.

הפורמט המועדף הוא JPEG לתצלומים, PNG לאיורים, שרטוטים וסמלים, ו-OGG לקבצי קול.

אנא תן לקובץ שם המייצג היטב את תוכנו כדי למנוע בלבול.
כדי לכלול את התמונה במאמר, צור קישור מסוג 
'''<nowiki>[[image:file.jpg]]</nowiki>''' או '''<nowiki>[[image:file.png|alt text]]</nowiki>'''
או '''<nowiki>[[media:file.ogg]]</nowiki>''' לקבצי קול.

שימו לב בבקשה שבדומה לדפי ויקיפדיה אחרים, אחרים רשאים לערוך או למחוק קבצים שהעלית לשרת אם הם/ן חושבים/ות שהדבר משרת את האנציקלופדיה. ושאת/ה עלול להחסם מלבצע העלאות קבצים אם תעשה פעולות לא חוקיות כנגד המערכת.",
"uploadlog"		=> "יומן העלאות קבצים",
"uploadlogpage" => "יומן_העלאות",
"uploadlogpagetext" => "להלן רשימה של העלאות הקבצים האחרונות שבוצעו.
כל הזמנים לפי שעון השרת (UTC).
<ul>
</ul>
",
"filename"		=> "שם הקובץ",
"filedesc"		=> "תקציר",
"affirmation"	=> "אני מצהיר/ה שבעל זכויות היוצרים על קובץ זה מתיר לי להפיץ אותו תחת תנאי $1.",
"copyrightpage" => "ויקיפדיה:זכויות_יוצרים",
"copyrightpagename" => "זכויות היוצרים של ויקיפדיה",
"uploadedfiles"	=> "קבצים שהועלו",
"noaffirmation" => "עליך להבטיח שהעלאת הקובץ אינה מפירה זכויות יוצרים.",
"ignorewarning"	=> "התעלם מהאזהרה ושמור את הקובץ בכל זאת.",
"minlength"		=> "שמות של קובצי תמונה צריכים להיות בני שלושה תווים לפחות.",
"badfilename"	=> "שם התמונה שונה ל - \"$1\".",
"badfiletype"	=> "\".$1\" אינו פורמט מומלץ לשמירת תמונות.",
"largefile"		=> "מומלץ שגודל התמונה לא יחרוג מ-100 קילובייט.",
"successfulupload" => "העלאת הקובץ הצליחה",
"fileuploaded"	=> "הקובץ \"$1\" הועלה לשרת בהצלחה. אנא השתמש/י בקישור זה: ($2) כדי לעבור לדף התיאור והזן/י בו פרטים אודות הקובץ כדוגמת: מהיכן הגיע, מתי נוצר ועל ידי מי, וכל פרט אחר שאת/ה יודע/ת לגביו. תודה.",
"uploadwarning" => "אזהרת העלאת קבצים",
"savefile"		=> "שמור קובץ",
"uploadedimage" => "העלתי את הקובץ \"$1\"",

# Image list
#
"imagelist"		=> "רשימת תמונות",
"imagelisttext"	=> "להלן רשימה של $1 תמונות, ממוינות $2:",
"getimagelist"	=> "מושך את רשימת התמונות",
"ilshowmatch"	=> "הצג תמונות שבשמן יש",
"ilsubmit"		=> "חפש",
"showlast"		=> "הצג $1 תמונות אחרונות ממוינות $2",
"all"			=> "הכל",
"byname"		=> "לפי שם",
"bydate"		=> "לפי תאריך",
"bysize"		=> "לפי גודל",
"imgdelete"		=> "מחק",
"imgdesc"		=> "תיאור",
"imglegend"		=> "מקרא: (תיאור) הצג/ערוך תיאור התמונה.",
"imghistory"	=> "היסטורית קובץ תמונה",
"revertimg"		=> "חזור",
"deleteimg"		=> "מחק",
"deleteimgcompletely"		=> "מחק",
"imghistlegend" => "מקרא (נוכ) = זו התמונה הנוכחית, (מחק) = מחק גירסה ישנה זו, (חזור) חזור לגירסה ישנה זו.
<br><i>הקש על תאריך לראות את התמונה שהועלתה בתאריך זה</i>.",
"imagelinks"	=> "קישורי תמונות",
"linkstoimage"	=> "הדפים הבאים משתמשים בתמונה זו:",
"nolinkstoimage" => "אין דפים המשתמשים בתמונה זו.",

# Statistics
#
"statistics"	=> "סטטיסטיקות",
"sitestats"		=> "סטטיסטיקות האתר",
"userstats"		=> "סטטיסטיקות משתמשים",
"sitestatstext" => "בבסיס-הנתונים יש <b>$1</b> דפים בסך הכל.
אלה כוללים דפי \"שיחה\", דפים על ויקיפדיה, \"קצרמרים\", הפניות, ודפים אחרים שלא נחשבים כמאמרים אנציקלופדיים.
אם מפחיתים את אלה, ישנם <b>$2</b> דפים שככל הנראה הינם מאמרים לכל דבר.<p>
מאז ששודרגה התוכנה (8/7/2003) בוצעו <b>$3</b> צפיות בדפים, ו-<b>$4</b> עריכות.
כלומר בממוצע <b>$5</b> עריכות לדף, ו-<b>$6</b> צפיות לכל עריכה.",
"userstatstext" => "ישנם <b>$1</b> משתמשים רשומים.
<b>$2</b> מתוכם הם מפעילי מערכת (ראה $3).",

# Maintenance Page
#
"maintenance"		=> "דף תחזוקה",
"maintnancepagetext"	=> "דף זה מכיל מספר כלים שימושים לתחזוקה יומיומית. חלק מהפעולות הללו מעלות מאוד את העומס על בסיס-הנתונים. אנו מבקשים שלא תרעננו את הדף לאחר כל תיקון שאתם מבצעים ;-)",
"maintenancebacklink"	=> "חזרה לדף התחזוקה",
"disambiguations"	=> "דפי רב-משמעות",
"disambiguationspage"	=> "ויקיפדיה:דפי_רב_משמעות",
"disambiguationstext"	=> "המאמרים שלהלן מצביעים אל <i>דפי רב-משמעות</i>. תפקיד דפים אלה הוא להפנות לדף הנושא הרלוונטי.<br>אנו מתייחסים לדף כרב-משמעות אם מצביע אליו $1.<br>קישורים המגיעים אל דף ממרחבי שם אחרים <i>אינם</i> מוצגים כאן.",
"doubleredirects"	=> "הפניות כפולות",
"doubleredirectstext"	=> "<b>שים לב:</b> רשימה זו עלולה לכלול דפים שנמצאו בטעות. זאת אומרת, שבדפים שנמצאו ישנו טקסט נוסף עם קישורים מתחת ל-#REDIRECT הראשון.<br>\nכל שורה מכילה קישור להפנייה הראשונה והשנייה, וכן את שורת הטקסט הראשונה של ההפניה השניה, שלרוב נמצא בה היעד האמיתי של ההפניה, אליו אמורה ההפניה הראשונה להצביע.",
"brokenredirects"	=> "הפניות לא תקינות",
"brokenredirectstext"	=> "ההפניות שלהלן מצביעות למאמרים שאינם קיימים:",
"selflinks"		=> "דפים המקושרים לעצמם",
"selflinkstext"		=> "הדפים שלהלן מקושרים אל עצמם, הם לא אמורים לעשות זאת.",
"mispeelings"           => "דפים עם שגיאות כתיב",
"mispeelingstext"               => "בדפים הבאים קיימות שגיאות כתיב נפוצות, רשימה של שגיאות אלו נמצאת ב-$1. הכתיב הנכון עשוי להיות מוצג (כך).",
"mispeelingspage"       => "רשימת שגיאות כתיב נפוצות",
"missinglanguagelinks"  => "קישורים חסרים בין שפות",
"missinglanguagelinksbutton"    => "מצא קישורי שפה חסרים עבור",
"missinglanguagelinkstext"      => "מאמרים אלו <i>לא</i> מקושרים למאמרים הדומים להם ב-$1. הפניות, ותתי-דפים <i>אינם</i> מוצגים.",


# Miscellaneous special pages
#
"orphans"		=> "מאמרים יתומים",
"lonelypages"	=> "מאמרים יתומים",
"unusedimages"	=> "תמונות לא משומשות",
"popularpages"	=> "מאמרים פופולריים",
"nviews"		=> "$1 צפיות",
"wantedpages"	=> "דפים מבוקשים",
"nlinks"		=> "$1 קישורים",
"allpages"		=> "כל הדפים",
"randompage"	=> "מאמר אקראי",
"shortpages"	=> "מאמרים קצרים",
"longpages"		=> "מאמרים ארוכים",
"listusers"		=> "רשימת משתמשים",
"specialpages"	=> "דפים מיוחדים",
"spheading"		=> "דפים מיוחדים",
"sysopspheading" => "דפים מיוחדים למפעילי מערכת",
"developerspheading" => "דפים מיוחדים למפתחים",
"protectpage"	=> "הפוך דף למוגן",
"recentchangeslinked" => "שינויים בדפים המקושרים",
"rclsub"		=> "(לדפים המקושרים מ-\"$1\")",
"debug"			=> "נפה שגיאות",
"newpages"		=> "מאמרים חדשים",
"movethispage"	=> "העבר דף זה",
"unusedimagestext" => "<p>שים לב בבקשה שאתרים אחרים כדוגמת
ויקיפדיות בשפות אחרות עשויות לבצע קישור לתמונה באמצעות הפניה ישירה לכתובתה, ולכן עלולות להופיע כאן תמונות שנמצאות בשימוש שותף.",
"booksources"	=> "משאבי ספרות חיצוניים",
"booksourcetext" => "להלן רשימה של קישורים לאתרים אחרים המוכרים ספרים חדשים ויד-שניה, ושבהם עשוי להיות מידע נוסף לגבי ספרים שאת/ה מחפש/ת. לויקיפדיה אין קשר לעסקים אלה, ואין לראות ברשימה זו המלצה, פרסום או עידוד לעשות שימוש באתרים אלו ספציפית.",

# Email this user

#
"mailnologin"	=> "אין כתובת לשליחה",
"mailnologintext" => "עליך להיכנס לחשבון ולהגדיר לעצמך כתובת דואר אלקטרוני תקינה (בהעדפות המשתמש) כדי לשלוח דואר למשתמש אחר.",
"emailuser"		=> "שלח דואר-אלקטרוני למשתמש זה",
"emailpage"		=> "שלח דואר למשתמש",
"emailpagetext"	=> "אם המשתמש הזין כתובת דואר-אלקטרוני חוקית בהעדפותיו האישיות, הטופס שלהלן ישלח אליו הודעת דואר אחת. כתובת הדואר האלקטרוני שהזנת בהעדפותיך האישיות תופיע בשדה ה-\"מאת\" של הדואר כדי שהמשתמש יוכל לענות.",
"noemailtitle"	=> "אין כתובת דואר-אלקטרוני",
"noemailtext"	=> "משתמש זה לא הזין כתובת דואר-אלקטרוני חוקית או בחר שלא לקבל דואר אלקטרוני ממשתמשים אחרים.",
"emailfrom"		=> "מאת",
"emailto"		=> "אל",
"emailsubject"	=> "נושא",
"emailmessage"	=> "הודעה",
"emailsend"		=> "שלח",
"emailsent"		=> "הדואר נשלח",
"emailsenttext" => "הודעת הדואר האלקטרוני שלך נשלחה.",

# Watchlist
#
"watchlist"		=> "רשימת המעקב שלי",
"watchlistsub"	=> "(עבור משתמש \"$1\")",
"nowatchlist"	=> "אין לך דפים ברשימת המעקב.",
"watchnologin"	=> "לא נכנסת לאתר",
"watchnologintext"	=> "כדי לערוך את רשימת המעקב, עליך להיכנס לחשבון במערכת",
"addedwatch"	=> "הדף הוסף לרשימת המעקב",
"addedwatchtext" => "הדף הוסף לרשימת המעקב. הוא יפויע ברשימת המעקב שלך וגם יהיה מודגש בדף שינויים אחרונים.
<p>אם ברצונך להסיר את הדף מרשימת המעקב, לחץ/י על \"הפסק לעקוב\" ברשימה שבצד הדף.",
"removedwatch"	=> "הדף הוסר מרשימת המעקב",
"removedwatchtext" => "הדף \"$1\" הוסר מרשימת המעקב שלך.",
"watchthispage"	=> "עקוב אחר דף זה",
"unwatchthispage" => "הפסק לעקוב",
"notanarticle"	=> "זהו אינו מאמר",

# Delete/protect/revert
#
"deletepage"	=> "מחק דף",
"confirm"		=> "אשר",
"excontent" => "תוכן היה:",
"exbeforeblank" => "תוכן לפני שהורק היה:",
"exblank" => "הדף היה ריק",
"confirmdelete" => "אשר מחיקת הדף",
"deletesub"		=> "(מוחק את \"$1\")",
"historywarning" => "אזהרה! לדף זה שהינך עומד/ת למחוק יש היסטוריית שינויים:",

"confirmdeletetext" => "אתה עומד למחוק דף או תמונה יחד עם כל ההיסטוריה שלה מבסיס-הנתונים.
אנא אשר שאכן זה מה שאתה מתכוון לעשות, ושאתה מבין את התוצאות של מעשה כזה, ושאתה מבצע אותו בהתאם ל[[ויקיפדיה:נהלים]].",
"confirmcheck"	=> "כן. אני באמת רוצה למחוק את הדף הזה.",
"actioncomplete" => "הפעולה בוצעה",
"deletedtext"	=> "\"$1\" נמחק.
ראה $2 לרשימת המחיקות האחרונות.",
"deletedarticle" => "מחקתי את \"$1\"",
"dellogpage"	=> "יומן_מחיקות",
"dellogpagetext" => "להלן רשימה של המחיקות האחרונות שבוצעו.
כל הזמנים המוצגים עם לפי שעון השרת (UTC).

<ul>
</ul>
",
"deletionlog"	=> "יומן מחיקות",
"reverted"		=> "שוחזר לגירסה קודמת",
"deletecomment"	=> "סיבת המחיקה",
"imagereverted" => "שיחזור לגירסה קודמת הצליח.",
"rollback"		=> "גלגל עריכות אחורנית",
"rollbacklink"	=> "גלגל אחורנית",
"cantrollback"	=> "לא יכול לגלגל אחורנית, הגירסה הראשונה של דף זה נכתבה על ידי משתמש זה",
"revertpage"	=> "שוחזר לעריכה אחרונה על ידי $1",

# Undelete
"undelete" => "שחזר דף מחוק",
"undeletepage" => "צפה ושחזר דפים מחוקים",
"undeletepagetext" => "הדפים שלהלן נמחקו אך עדיין בארכיון ואפשר לשחזר אותם. הארכיון מנוקה מעת לעת.",
"undeletearticle" => "שחזר מאמר מחוק",
"undeleterevisions" => "$1 גירסאות נשמרו בארכיון",
"undeletehistory" => "אם תשחזר את הדף, כל הגירסאות ישוחזרו בדף ההיסטוריה שלו.
אם כבר יש דף חדש באותו שם, הגירסאות והשינויים יופיעו בהיסטוריה קודמת, והגירסה הנוכחית לא תוחלף אוטומטית.",
"undeleterevision" => ".מחקתי גירסאות החל מ-$1",
"undeletebtn" => "שחזר!",
"undeletedarticle" => "\"$1\" שוחזר",
"undeletedtext"   => "המאמר [[$1]] שוחזר בהצלחה.
ראה את [[ויקיפדיה:יומן_מחיקות]] לרשימה של מחיקות ושיחזורים אחרונים.",

# Contributions
#
"contributions"	=> "תרומות המשתמש",
"mycontris" => "התרומות שלי",
"contribsub"	=> "עבור $1",
"nocontribs"	=> "לא נמצאו שינויים המתאימים לקריטריונים אלו.",
"ucnote"		=> "להלן <b>$1</b> השינויים האחרונים שביצע משתמש זה ב-<b>$2</b> הימים האחרונים:",
"uclinks"		=> "צפה ב-$1 השינויים האחרונים; צפה ב-$2 הימים האחרונים",
"uctop"		=> "(אחרון)" ,

# What links here
#
"whatlinkshere"	=> "דפים המקושרים לכאן",
"notargettitle" => "אין דף מטרה",
"notargettext"	=> "לא ציינת דף מטרה או משתמש לגביו תבוצע פעולה זו.",
"linklistsub"	=> "(רשימת קישורים)",
"linkshere"		=> "הדפים שלהלן מקושרים לכאן:",
"nolinkshere"	=> "אין דפים המקושרים לכאן.",
"isredirect"	=> "דף הפנייה",

# Block/unblock IP
#
"blockip"		=> "חסום כתובת IP",
"blockiptext"	=> "השתמש בטופס שלהלן בכדי לחסום הרשאות כתיבה מכתובת IP ספציפית.
חסימת משתמש צריכה להתבצע אך ורק בכדי למנוע ונדליזם, ובהתאם ל-[[ויקיפדיה:נהלים]]. פרט את הסיבה הספציפית למטה. (לדוגמה - ציין דפים ספציפיים שהושחתו על ידי המשתמש)",
"ipaddress"		=> "כתובת IP",
"ipbreason"		=> "סיבה",
"ipbsubmit"		=> "חסום כתובת זו",
"badipaddress"	=> ".כתובת ה-IP אינה כתובה נכון",
"noblockreason" => ".עליך לציין סיבה לחסימה",
"blockipsuccesssub" => "החסימה הצליחה",
"blockipsuccesstext" => "הכתובת \"$1\" נחסמה.
<br>ראה את [[מיוחד:רשימת כתובות IP חסומות|רשימת הכתובות החסומות]] כדי לצפות בחסימות",
"unblockip"		=> "שחרר כתובת IP",
"unblockiptext"	=> "השתמש בטופס שלהלן בכדי להחזיר הרשאות כתיבה לכתובת IP חסומה.",
"ipusubmit"		=> "שחרר כתובת זו",
"ipusuccess"	=> "הכתובת \"$1\" שוחררה",
"ipblocklist"	=> "רשימת כתובות IP חסומות",
"blocklistline"	=> "$1, $2 חסם את $3",
"blocklink"		=> "חסום",
"unblocklink"	=> "שחרר חסימה",
"contribslink"	=> "תרומות המשתמש",

# Developer tools
#
"lockdb"		=> "נעל בסיס-נתונים",
"unlockdb"		=> "שחרר בסיס-נתונים מנעילה",
"lockdbtext"	=> "נעילת בסיס-הנתונים תמנע ממשתמשים את האפשרות לערוך דפים, לשנות את העדפותיהם, את רשימות המעקב שלהם, ופעולות אחרות הדורשות ביצוע שינויים בבסיס-הנתונים
אנא אשר/י שזה מה שאת/ה מתכוון/ת לעשות, ושתשחרר/י את בסיס-הנתונים מנעילה כאשר פעולת התחזוקה תסתיים.",
"unlockdbtext"	=> "שחרור בסיס-הנתונים מנעילה יחזיר למשתמשים את היכולת לערוך דפים, לשנות את העדפותיהם, לערוך את רשימות המעקב שלהם, ולבצע פעולות אחרות הדורשות ביצוע שינויים בבסיס-הנתונים
אנא אשר/י שזה מה שבכוונתך לעשות.",
"lockconfirm"	=> "כן, אני באמת רוצה לנעול את בסיס-הנתונים.",
"unlockconfirm"	=> "כן, אני באמת רוצה לשחרר את בסיס-הנתונים מנעילה.",
"lockbtn"		=> "נעל בסיס-נתונים",
"unlockbtn"		=> "שחרר בסיס-נתונים מנעילה",
"locknoconfirm" => "לא סימנת את תיבת האישור.",
"lockdbsuccesssub" => "נעילת בסיס-הנתונים הצליחה",
"unlockdbsuccesssub" => "שוחררה הנעילה מבסיס-הנתונים",
"lockdbsuccesstext" => "בסיס-הנתונים של ויקיפדיה ננעל.
<br>זכור לשחרר את הנעילה לאחר שפעולת התחזוקה הסתיימה.",
"unlockdbsuccesstext" => "שוחררה הנעילה מבסיס-הנתונים של ויקיפדיה",

# SQL query
#
"asksql"		=> "שאילתת SQL",
"asksqltext"	=> "השתמש/י בטופס שלהלן לביצוע שאילתא ישירה לבסיס-הנתונים של ויקיפדיה.
אנא עשה/י שימוש בגרשיים בודדים ('כך') כדי לתחום שרשראות תווים. (String literals). פעולה זו יוצרת לעיתים קרובות עומס רב על השרת, אנא השתמשו באפשרות זו כמה שפחות.",
"sqlquery"		=> "הזן שאילתא",
"querybtn"		=> "בצע שאילתא",
"selectonly"	=> "שאילתות שאינן \"SELECT\" אפשריות רק למפתחי ויקיפדיה.",
"querysuccessful" => "השאילתא הצליחה",

# Move page
#
"movepage"		=> "העבר דף",
"movepagetext"	=> "שימוש בטופס שלהלן ישנה את שמו של דף, ויעביר את כל ההיסטוריה שלו לשם חדש.
השם הישן יהפוך לדף הפניה אל הדף עם השם החדש.
קישורים לשם הישן לא ישונו, וודא/י לבצע [[מיוחד:תחזוקה|בדיקה]] שאין הפניות כפולות, או מקולקלות.
את/ה אחראי/ת לוודא שכל הקישורים ממשיכים להצביע למקום שאליו הם אמורים להצביע.

שים/י לב: הדף '''לא''' יועבר אם כבר יש דף תחת השם החדש, אלא אם הדף הזה ריק, או שהוא הפנייה, ואין לו היסטוריה של שינויים. משמעות הדבר, שאפשר לשנות חזרה את שמו של דף לשם המקורי, אם נעשתה טעות, ולא ימחק דף קיים במערכת.

<b>אזהרה!</b>
שינוי זה עשוי להיות שינוי דרסטי ובלתי צפוי לדף פופולרי;
אנא וודא/י שאת/ה מבין/ה את השלכות המעשה לפני שאת/ה ממשיך/ה.",
"movepagetalktext" => "אם קיים לדף זה דף שיחה, הוא יועבר אוטומטית '''אלא אם:'''
*הדף מועבר ממרחב שם אחד לשני,
*קיים דף שיחה שאינו ריק תחת השם החדש אליו מועבר הדף, או
*הורדת את הסימון בתיבה שלהלן.

במקרים אלה, תצטרך/י להעביר או לשלב את הדפים באופן ידני אם תרצה/י.",
"movearticle"	=> "העבר דף",
"movenologin"	=> "לא נכנסת לאתר",
"movenologintext" => "עליך להיכנס לחשבון במערכת כדי להעביר דף",
"newtitle"		=> "לשם החדש",
"movepagebtn"	=> "העבר דף",
"pagemovedsub"	=> "ההעברה הצליחה",
"pagemovedtext" => "הדף [[$1]] הועבר ל [[$2]]",
"articleexists" => "קיים כבר דף עם אותו שם, או שהשם שבחרת אינו חוקי.
אנא  בחר/י שם אחר.",
"talkexists"	=> "הדף עצמו הועבר בהצלחה, אבל דף השיחה לא הועבר כיוון שקיים כבר דף שיחה במיקום החדש. אנא מזג/י אותם ידנית.",
"movedto"		=> "הועבר ל",
"movetalk"		=> "העבר גם את דף השיחה, אם קיים.",
"talkpagemoved" => "דף השיחה המשוייך הועבר גם כן.",
"talkpagenotmoved" => "דף השיחה המשוייך <strong>לא</strong> הועבר.",

# Math

	'mw_math_png' =>  "תמיד הצג כ-PNG",
	'mw_math_simple' => "HTML אם פשוט, אחרת PNG",
	'mw_math_html' => "HTML אם אפשר, אחרת PNG",
	'mw_math_source' => "השאר כקוד TeX",
	'mw_math_modern' => "מומלץ לדפדפנים עדכניים",
	'mw_math_mathml' => 'MathML',

);

class LanguageHe extends LanguageUtf8 {

        function getDefaultUserOptions () {
                $opt = Language::getDefaultUserOptions();
		$opt["quickbar"]=2;
                return $opt;
        }

	function getBookstoreList () {
		global $wgBookstoreListHe ;
		return $wgBookstoreListHe ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesHe;
		return $wgNamespaceNamesHe;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesHe;
		return $wgNamespaceNamesHe[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesHe;

		foreach ( $wgNamespaceNamesHe as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsHe;
		return $wgQuickbarSettingsHe;
	}

	function getSkinNames() {
		global $wgSkinNamesHe;
		return $wgSkinNamesHe;
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesHe;
		return $wgValidSpecialPagesHe;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesHe;
		return $wgSysopSpecialPagesHe;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesHe;
		return $wgDeveloperSpecialPagesHe;
	}

        function getMessage( $key )
        {
                global $wgAllMessagesHe;
                if(array_key_exists($key, $wgAllMessagesHe))
                        return $wgAllMessagesHe[$key];
                else
                        return Language::getMessage($key);
        }

        function isRTL() { return true; }

        function fallback8bitEncoding() { return "iso8859-8"; }                

}

?>
