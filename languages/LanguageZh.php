<?
global $IP;
include_once( "$IP/Utf8Case.php" );

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesZh = array(
	-1	=> "特殊", /* Special */
	0	=> "",
	1	=> "对话", /* Talk */
	2	=> "用户", /* User */
	3	=> "用户对话", /* User_talk */
	4	=> "Wikipedia", /* Wikipedia */
	5	=> "Wikipedia_对话", /* Wikipedia_talk */
	6	=> "图像", /* Image */
	7	=> "图像对话" /* Image_talk */
);

/* private */ $wgDefaultUserOptionsZh = array(
	"quickbar" => 1, "underline" => 1, "hover" => 1,
	"cols" => 80, "rows" => 25, "searchlimit" => 20,
	"contextlines" => 5, "contextchars" => 50,
	"skin" => 0, "rcdays" => 3, "rclimit" => 50,
	"highlightbroken" => 1, "stubthreshold" => 0
);

/* private */ $wgQuickbarSettingsZh = array(
	"无", /* "None" */ 
	"左侧固定", /* "Fixed left" */ 
	"右侧固定", /* "Fixed right" */ 
	"左侧漂移" /* "Floating left" */ 
);

/* private */ $wgSkinNamesZh = array(
	"标准",/* "Standard" */ 
	"怀旧",/* "Nostalgia" */ 
	"科隆香水蓝" /* "Cologne Blue" */ 
);

/* private */ $wgUserTogglesZh = array(
	"hover"		=> "滑过连接时显示注释",
	"underline" => "下划连接",
	"highlightbroken" => "高亮空白连接",
	"justify"	=> "段落对齐",
	"hideminor" => "在最近更改页中隐藏细微修改",
	"numberheadings" => "标题自动编号",
	"rememberpassword" => "下次登录时记住密码",
	"editwidth" => "编辑栏最大宽度",
	"editondblclick" => "双击编辑页面(JavaScript)",
        "watchdefault" => "Watch new and modified articles"
);

/* private */ $wgBookstoreListZh = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgLanguageNamesZh = array(
	"aa"    => "Afar", /* 阿法尔语 */
	"ab"    => "Abkhazian", /* 阿布哈西亚语 */
	"af"	=> "Afrikaans", /* 南非荷兰语 */
	"am"	=> "Amharic",/* 阿姆哈拉语 */
	"ar"    => "&#8238;&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;&#8236;(Araby)", /* 阿拉伯语 */
	"as"	=> "Assamese", /* 阿萨姆语 */
	"ay"	=> "Aymara", /* 艾马拉语 */
	"az"	=> "Azerbaijani", /* 阿塞拜疆语 */
	"ba"	=> "Bashkir", /* 巴什基尔语 */
	"be"    => "(&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1080;)", /* 白俄罗斯语 */
	"bh"	=> "Bihara", /* 比哈尔语 */
	"bi"	=> "Bislama", /* 比斯拉马语 */
	"bn"	=> "Bengali", /* 孟加拉语 */
	"bo"	=> "Tibetan", /* 藏语 */
	"br"    => "Brezhoneg", /* 布列塔尼語 */
	"ca"    => "Catal&#224;", /* 加泰罗尼亚语 */
	"ch"    => "Chamoru", /* 查莫罗语 */
	"co"	=> "Corsican", /* 科西嘉语 */
	"cs"    => "&#268;esk&#225;", /* 捷克语 */
	"cy"    => "Cymraeg", /* 威尔士语 */
	"da"    => "Dansk", # Note two different subdomains. /* 丹麦语 */
	"dk"    => "Dansk", # 'da' is correct for the language. /* 丹麦语 */
	"de"    => "Deutsch", /* 德语 */
	"dz"	=> "Bhutani", /* 不丹语 */
	"el"    => "&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;(Ellenika)",
                /* 希腊语 */
	"en"	=> "English", /* 英语 */
	"eo"	=> "Esperanto",/* 世界语 */
	"es"    => "Espa&#241;ol", /* 西班牙语 */
	"et"    => "Eesti", /* 爱沙尼亚语 */
	"eu"    => "Euskara", /* 巴斯克語 */
	"fa"    => "&#8238;&#1601;&#1585;&#1587;&#1609;&#8236;(Farsi)",
                /* 法尔西语 波斯语 */
	"fi"    => "Suomi", /* 芬兰语 */
	"fj"	=> "Fijian", /* 斐济语 */
	"fo"	=> "Faeroese", /* 法罗语 */
	"fr"    => "Fran&#231;ais", /* 法语 */
	"fy"    => "Frysk", /* 弗里斯兰语 */
	"ga"    => "Gaelige", /* 爱尔兰语 */
	"gl"	=> "Galician", /* 加利西亚语 */
	"gn"	=> "Guarani", /* 瓜拉尼语 */
	"gu"    => "&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;(Gujarati)", 
	        /* 古吉拉特语 */
	"ha"	=> "Hausa", /* 豪萨语 */
	"he"    => "&#1506;&#1489;&#1512;&#1497;&#1514;(Ivrit)",
	        /* 希伯来语 */
	"hi"    => "&#2361;&#2367;&#2344;&#2381;&#2342;&#2368;(Hindi)",
                /* 印地语 */
	"hr"    => "Hrvatski", /* 克罗地亚语 */
	"hu"    => "Magyar", /* 马札尔语 */
	"hy"	=> "Armenian", /* 亚美尼亚语 */
	"ia"	=> "Interlingua", /* 拉丁国际语 */
	"id"	=> "Indonesia", /* 印度尼西亚语 */
	"ik"	=> "Inupiak", /* Inupiak */
	"is"    => "&#205;slenska", /* 冰岛语 */
	"it"    => "Italiano", /* 意大利语 */
	"iu"	=> "Inuktitut",
	"ja"    => "&#26085;&#26412;&#35486;(Nihongo)", /* 日本语 */
	"jv"	=> "Javanese", /* 爪哇语 */
	"ka"    => "&#4325;&#4304;&#4320;&#4311;&#4309;&#4308;&#4314;&#4312;(Kartuli)", 
                /* 格鲁吉亚语 */
	"kk"	=> "Kazakh", /* 哈萨克语 */
	"kl"	=> "Greenlandic", /* 格陵兰语 */
	"km"	=> "Cambodian", /* 柬埔寨语 */
	"kn"	=> "Kannada", /* 卡纳达语 */
	"ko"    => "&#54620;&#44397;&#50612;(Hangukeo)",
	        /* 韩国语 */
	"ks"	=> "Kashmiri", /* 克什米尔语 */
	"kw"    => "Kernewek", /* 康沃尔语 */
	"ky"	=> "Kirghiz", /* 吉尔吉斯语 (柯尔克孜语)*/
	"la"    => "Latina", /* 拉丁语 */
	"ln"	=> "Lingala", /* 林加拉语 */
	"lo"	=> "Laotian", /* 老挝语 */
	"lt"    => "Lietuvi&#371;", /* 立陶宛语 */

	"lv"	=> "Latvian", /* 拉脱维亚语 */
	"mg"    => "Malagasy", /* 马尔加什语 */
	"mi"	=> "Maori", /* 毛利人 */
	"mk"	=> "Macedonian", /* 马其顿语 */
	"ml"	=> "Malayalam", /* 马拉雅拉姆语 ？德拉维语*/
	"mn"	=> "Mongolian", /* 蒙古语 */
	"mo"	=> "Moldavian", /* 摩尔多瓦语 */
	"mr"	=> "Marathi", /* 马拉地语 */
	"ms"    => "Bahasa Melayu", /* 马来语 */
	"my"	=> "Burmese", /* 缅甸语 */
	"na"	=> "Nauru", /* 瑙鲁语 */
	"ne"    => "&#2344;&#2375;&#2346;&#2366;&#2354;&#2368;(Nepali)",
                /* 尼泊尔语 */
	"nl"    => "Nederlands", /* 荷兰语 */
	"no"    => "Norsk", /* 挪威语 */
	"oc"	=> "Occitan", /* 奥克语 */
	"om"	=> "Oromo", /* 奥罗莫语 */
	"or"	=> "Oriya", /* 奥里亚语 */
	"pa"	=> "Punjabi", /* 旁遮普语 */
	"pl"    => "Polski", /* 波兰语 */
	"ps"	=> "Pashto", /* 普什图语 */
	"pt"    => "Portugu&#234;s", /* 葡萄牙语 */
	"qu"	=> "Quechua", /* 盖丘亚语 */
	"rm"	=> "Rhaeto-Romance", /* Rhaeto-Romance */
	"rn"	=> "Kirundi", /* 基隆迪语 */
	"ro"    => "Rom&#226;n&#259;", /* 罗马尼亚语 */
	"ru"    => "&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;(Russkij)",
                /*  俄语 */
	"rw"	=> "Kinyarwanda",
	"sa"    => "&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340;(Samskrta)",
                /* 梵语 */
	"sd"	=> "Sindhi", /* 信德语 */
	"sg"	=> "Sangro", /* 桑戈语 */
	"sh"	=> "Serbocroatian", /* 塞尔维亚-克罗地亚语 */
	"si"	=> "Sinhalese", /* 僧伽罗语 */
	"simple"=> "Simple English", /* 简单英语 */
	"sk"	=> "Slovak", /* 斯洛伐克语 */
	"sl"	=> "Slovensko", /* 斯洛文尼亚语 */
	"sm"	=> "Samoan", /* 萨摩亚语 */
	"sn"	=> "Shona", /* 绍纳语 */
	"so"    => "Soomaali", /* 索马里语 */
	"sq"    => "Shqiptare", /* 阿尔巴尼亚 */
	"sr"    => "Srpski", /* 塞尔维亚语 */
	"ss"	=> "Siswati", /* 西斯瓦提语 */
	"st"	=> "Sesotho", /* 塞索托语 */
	"su"	=> "Sudanese", /* 苏丹语 */
	"sv"    => "Svenska", /* 瑞典语 */
	"sw"    => "Kiswahili", /* 斯瓦希里语 */
	"ta"	=> "Tamil", /* 泰米尔语 */
	"te"	=> "Telugu", /* 泰卢固语 */
	"tg"	=> "Tajik", /* 塔吉克语 */
	"th"	=> "Thai", /* 泰国语 */
	"ti"	=> "Tigrinya", /* 提格里尼亚语 */
	"tk"	=> "Turkmen", /* 土库曼语 */
	"tl"	=> "Tagalog", /* 塔加路语 */
	"tn"	=> "Setswana", /* 茨瓦纳语 */
	"to"	=> "Tonga", /* 汤加语 */
	"tr"    => "T&#252;rk&#231;e", /* 土耳其语 */
	"ts"	=> "Tsonga", /* 通加语 ？聪加语*/
	"tt"	=> "Tatar", /* 鞑靼语 */
	"tw"	=> "Twi", /* 特威语 ？契维、特维*/
	"ug"	=> "Uighur", /* 维吾尔语 */
	"uk"    => "&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;(Ukrayins`ka)", 
	        /*  乌克兰语 */
	"ur"	=> "Urdu", /* 乌尔都语 */
	"uz"	=> "Uzbek", /* 乌兹别克语 */
	"vi"	=> "Vietnamese", /* 越南语 */
	"vo"    => "Volap&#252;k", /* 沃拉卜克语？佛拉普克语 */
	"wo"	=> "Wolof", /* 沃洛夫语 */
	"xh"    => "isiXhosa", /* 科萨语 */
	"yi"	=> "Yiddish", /* 意第绪语 */
	"yo"	=> "Yoruba", /* 约鲁巴语 */
	"za"	=> "Zhuang", /* 壮语 ？ */
	"zh"    => "中文(Zhongwen)", /* Zhongwen */
	"zu"	=> "Zulu" /* 祖鲁语 */
);

/* private */ $wgWeekdayNamesZh = array(
	"星期日", "星期一", "星期二", "星期三", "星期四",
	"星期五", "星期六"
);

/* private */ $wgMonthNamesZh = array(
	"1月", "2月", "3月", "4月", "5月", "6月",
	"7月", "8月", "9月", "10月", "11月",
	"12月"
);

/* private */ $wgMonthAbbreviationsZh = array(
	"1月", "2月", "3月", "4月", "5月", "6月",
	"7月", "8月", "9月", "10月", "11月",
	"12月"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesZh = array(
	"Userlogin"	=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "我的设置", /* Set my user preferences */
	"Watchlist"	=> "监视列表", /* My watchlist */
	"Recentchanges" => "最近更改",  /* Recently updated pages */
	"Upload"	=> "上载图像",  /* Upload image files */
	"Imagelist"	=> "图像列表",  /* Image list */
	"Listusers"	=> "注册用户",  /* Registered users */
	"Statistics"	=> "站点统计",  /* Site statistics */
	"Randompage"	=> "随机条目",  /* Random article */

	"Lonelypages"	=> "孤立条目",  /* Orphaned articles */
	"Unusedimages"	=> "孤立图像",  /* Orphaned images */
	"Popularpages"	=> "热点条目",  /* Popular articles */
	"Wantedpages"	=> "待撰页面",  /* Most wanted articles */
	"Shortpages"	=> "短条目",  /* Short articles */
	"Longpages"	=> "长条目",  /* Long articles */
	"Newpages"	=> "新条目",  /* Newly created articles */
	"Allpages"	=> "所有条目",  /* All pages by title */

	"Ipblocklist"	=> "被封 IP 地址",  /* Blocked IP addresses */
	"Maintenance"   => "维护页",  /* Maintenance page */
	"Specialpages"  => "", /* Next few intentionally left blank! 特殊页面 */
	"Contributions" => "", /* 参与者 */
	"Emailuser"	=> "", /* 给用户发信 */
	"Whatlinkshere" => "", /* 链入页面 */
	"Recentchangeslinked" => "", /* 近期链出页面更改 */
	"Movepage"	=> "", /* 移动页面 */
	"Booksources"	=> "站外书源"  /* External book sources */
);

/* private */ $wgSysopSpecialPagesZh = array(
	"Blockip"	=> "禁封一个 IP 地址",  /* Block an IP address */
	"Asksql"	=> "查询数据库",  /* Query the database */
	"Undelete"	=> "查看并恢复被删页面"
        /* View and restore deleted pages */
);

/* private */ $wgDeveloperSpecialPagesZh = array(
	"Lockdb"	=> "使数据库只读",  /* Make database read-only */
	"Unlockdb"	=> "恢复数据库写操作",  /* Restore database write access */
	"Debug"		=> "调试信息"  /* Debugging information */
);

/* private */ $wgAllMessagesZh = array(

# Bits of text used by many pages:
#
"linktrail"	=> "/^([a-z]+)(.*)\$/sD",
"mainpage"	=> "首页", /* Main Page */
"about"		=> "关于", /* About */
"aboutwikipedia" => "关于 Wikipedia", /* About Wikipedia */
"aboutpage"	=> "Wikipedia:关于", /*  */
"help"		=> "帮助", /* Help */
"helppage"	=> "Wikipedia:帮助", /* Wikipedia:Help */
"wikititlesuffix" => "Wikipedia", /* Wikipedia */
"bugreports"	=> "错误报告", /* Bug reports */
"bugreportspage" => "Wikipedia:错误报告", /*  */
"faq"		=> "常见问题解答", /* FAQ */
"faqpage"	=> "Wikipedia:常见问题解答", /* Wikipedia:FAQ */
"edithelp"	=> "编辑帮助", /* Editing help */
"edithelppage"	=> "Wikipedia:如何编辑页面", /* Wikipedia:How_does_one_edit_a_page */
"cancel"	=> "取消", /* Cancel */
"qbfind"	=> "查找", /* Find */
"qbbrowse"	=> "浏览", /* Browser */
"qbedit"	=> "编辑", /* Edit */
"qbpageoptions" => "页面设置", /* Page options */
"qbpageinfo"	=> "页面信息", /* Page info */
"qbmyoptions"	=> "我的设置", /* My options */
"mypage"	=> "我的页面", /* My page */
"mytalk"	=> "我的对话", /* My talk */
"currentevents" => "新闻动态", /* Current events */
"errorpagetitle" => "错误", /* Error */
"returnto"	=> "返回到 $1 ", /* Return to $1. */
"fromwikipedia"	=> "Wikipedia ，自由的百科全书。", /* From Wikipedia, the free encyclopedia. */
"whatlinkshere"	=> "链到本页的页面", /* Pages that link here */
"help"		=> "帮助", /* Help */
"search"	=> "搜索", /* Search */
"history"	=> "修订历史", /* History */
"printableversion" => "可打印版", /* Printable version */
"editthispage"	=> "编辑页面", /* Edit this page */
"deletethispage" => "删除页面", /* Delete this page */
"protectthispage" => "保护页面", /* Protect this page */
"unprotectthispage" => "免除保护", /* Unprotect this page */
"talkpage"	=> "对话页", /* Talk page */
"subjectpage"	=> "主题页", /* Subject page */
"otherlanguages" => "其它语言", /* Other languages */
"redirectedfrom" => "（重新定向自 $1 ）", /* (Redirected from $1) */
"lastmodified"	=> "最后更改于 $1。", /* The page was last modified $1. */
"viewcount"	=> "页面已被浏览 $1 次", /* This page has been accessed $1 times. */
"printsubtitle" => "（自 http://zh.wikipedia.org ）", /* (From http://www.wikipedia.org) */
"protectedpage" => "被保护页", /* Protected page */
"administrators" => "Wikipedia:管理员", /* Wikipedia:Administrators */
"sysoptitle"	=> "需要 sysop 权限", /* Sysop access required */
"sysoptext"	=> "您请求的命令只能被拥有 \"sysop\" 权限的用户执行。请参见 $1 。",
/* The action you have requested can only be performed by users with \"sysop\" status.See $1. */
"developertitle" => "需要 developer 权限", /* Developer access required */
"developertext"	=> "您请求的命令只能被拥有 \"developer\" 权限的用户执行。请参见 $1 。",
/* The action you have requested can only be performed by users with \"developer\" status. See $1.*/
"nbytes"	=> "$1 字节", /* $1 bytes */
"go"		=> "进入", /* Go */
"ok"		=> "确定", /* OK */
"sitetitle"	=> "Wikipedia", /* Wikipedia */
"sitesubtitle"	=> "自由的百科全书", /* The Free Encyclopedia */
"retrievedfrom" => "取自 \"$1\"", /* Retrieved from \"$1\" */

# Main script and global functions
#
"nosuchaction"	=> "没有这个命令。", /* No such action */
"nosuchactiontext" => "URL 请求的命令无法被 Wikipedia 软件识别。",
/* The action specified by the URL is not recognized by the Wikipedia software */
"nosuchspecialpage" => "没有这个特殊页。", /* No such special page */
"nospecialpagetext" => "您请求的页面无法被 Wikipedia 软件识别。",
/* You have requested a special page that is not recognized by the Wikipedia software. */

# General errors
#
"error"			=> "错误",
"databaseerror" => "数据库错误",
"dberrortext"	=> "数据库指令语法错误。
这可能是由于非法搜索指令所引起的(见 $5),
也可能是由于软件自身的错误所引起。
最后一次数据库指令是：
<blockquote><tt>$1</tt></blockquote>
来自于函数 \"<tt>$2</tt>\".
MySQL返回错误 \"<tt>$3: $4</tt>\".",
"noconnect"	=> "无法在 $1上连接数据库",
"nodb"		=> "无法选择数据库 $1",
"readonly"	=> "数据库禁止访问",
"enterlockreason" => "请输入禁止访问原因, 包括估计重新开放的时间",
"readonlytext"	=> "Wikipedia数据库目前禁止输入新内容及更改，
这很可能是由于数据库正在维修，之后即可恢复。
管理员有如下解释:
<p>$1",
"missingarticle" => "数据库找不到文字\"$1\".
这不是一个数据库错误，而可能是由于软件错误所引起。
请将情况连同URL告知管理员。",
"internalerror" => "内部错误",
"filecopyerror" => "无法复制文件\"$1\"到\"$2\".",
"filerenameerror" => "无法重命名文件\"$1\" 到\"$2\".",
"filedeleteerror" => "无法删除文件 \"$1\".",
"filenotfound"	=> "找不到文件 \"$1\".",
"unexpected"	=> "不正常值: \"$1\"=\"$2\".",
"formerror"		=> "错误: 无法提交表单",	
"badarticleerror" => "This action cannot be performed on this page.",
"cannotdelete"	=> "无法删除选定页或图像.",
"badtitle"      => "错误的标题", /* Bad title */
"badtitletext"	=> "所请求页面的标题是无效的或者不存在，或者是错误的跨语言链接标题。",
/* The requested page title was invalid, empty, or an incorrectly linked inter-language or inter-wiki title. */

# Login and logout pages
#
"logouttitle"	=> "用户退出",
"logouttext"	=> "你现在已经退出.
你可以继续以匿名方式使用Wikipeida，或再次以相同或不同用户身份登录。\n",

"welcomecreation" => "<h2>欢迎, $1!</h2><p>你的帐号已经建立，不要忘记设置Wikipedia个人参数。",

"loginpagetitle" => "用户登录",
"yourname"	=> "用户名",
"yourpassword"	=> "密码",
"yourpasswordagain" => "重复密码",
"newusersonly"	=> " (仅限新用户)",
"remembermypassword" => "下次登录记住密码.",
"loginproblem"	=> "<b>登录有问题。</b><br>再试一次！",
"alreadyloggedin" => "<font color=red><b> $1, 你已经登录了!</b></font><br>\n",

"areyounew"	=> "如果你是Wikipedia的新用户并想得到一个用户帐号，
请输入用户名，然后重复输入密码两次。
你可以选择输入电子邮件地址; 这样如果你忘了密码时可以要求将密码寄往你所输入的地址。<br>\n",

"login"		=> "登录",
"userlogin"	=> "用户登录",
"logout"	=> "退出",
"userlogout"	=> "用户退出",
"createaccount"	=> "建立新帐号",
"badretype"	=> "你所输入的密码并不相同。",
"userexists"	=> "你所输入的用户名已有人使用。请另选一个。",

"youremail"	=> "电子邮件",
"yournick"	=> "绰号 (签名时用)",
"emailforlost"	=> "如果你忘了你的密码, 你可以得到一个寄往你的电子邮件地址的新密码。",
"loginerror"	=> "登录错误",
"noname"	=> "你没有输入一个有效的用户名。",
"loginsuccesstitle" => "登录成功",
"loginsuccess"	=> "你现在以 \"$1\"的身份登录Wikipedia。",
"nosuchuser"	=> "找不到用户 \"$1\".
检查是否输入错误,或使用下面的表单创建新帐号。",
"wrongpassword"	=> "你所输入的密码错误。请再试一次。",
"mailmypassword" => "请将密码寄给我。",
"passwordremindertitle" => "Wikipedia密码提醒",
"passwordremindertext" => "有人 (可能是你, 来自 IP 地址 $1)要求我们将新的Wikipedia登录密码寄给你。
用户 \"$2\" 的密码现在是 \"$3\".
请立即登录并更改密码。",
"noemail"	=> "用户\"$1\"没有登记电子邮件地址。",
"passwordsent"	=> "用户\"$1\"的新密码已经寄往所登记的电子邮件地址。
请在收到后再登录。",

# Edit pages
#
"summary"	=> "简述",
"minoredit"	=> "这是一个细微修改",
"savearticle"	=> "保存页面",
"preview"	=> "预览",
"showpreview"	=> "显示预览",
"blockedtitle"	=> "用户被封",
"blockedtext"	=> "你的用户名或IP地址已被$1封。
理由是：<br>$2<p>你可以联系管理员讨论。",
"newarticle"	=> "(新)",
"newarticletext" => "在这里输入新页面内容。",
"noarticletext" => "(本页目前没有文字)",
"updated"	=> "(更新)",
"note"		=> "<strong>注意：</strong> ",
"previewnote"	=> "请记住这只是预览，内容还未保存！",
"previewconflict" => "这个预览显示了上面文字编辑区中的内容。它将在你选择保存后出现。",
"editing"	=> "正在编辑$1",
"editconflict"	=> "编辑冲突： $1",
"explainconflict" => "有人在你开始编辑后更改了页面。
上面的文字框内显示的是目前本页的内容。
你所做的修改显示在下面的文字框中。
你应当将你所做的修改加入现有的内容中。
<b>只有</b>在上面文字框中的内容会在你点击\"保存页面\"后被保存。\n<p>",
"yourtext"	=> "你的文字",
"storedversion" => "已保存版本",
"editingold"	=> "<strong>警告：你正在编辑的是本页的旧版本。
如果你保存它的话，在本版本之后的任何修改都会丢失。</strong>\n",
"yourdiff"	=> "不同",
"copyrightwarning" => "请注意对Wikipedia的任何贡献都将被认为是在GNU自由文档协议证书下发布。
(细节请见$1).
如果你不希望你的文字被任意修改和再散布，请不要提交。<br>
你同时也向我们保证你所提交的内容是你自己所作，或得自一个不受版权保护或相似自由的来源。
<strong>不要在未获授权的情况下发表！</strong>",
/* You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource. */

# History pages
#
"revhistory"	=> "修订历史", /* Revision history */
"nohistory"	=> "没有本页的修订记录。",
/* There is no edit history for this page. */
"revnotfound"	=> "没有找到修订记录", /* Revision not found */
"revnotfoundtext" => "您请求的更早版本的修订记录没有找到。请检查您请求本页面用的 URL 是否正确。\n",
/* The old revision of the page you asked for could not be found.Please check the URL you used to access this page.\n */
"loadhist"	=> "载入页面修订历史", /* Loading page history */
"currentrev"	=> "Current revision", /* 当前修订版本 */
"revisionasof"	=> "$1 的修订版本", /* Revision as of $1 */
"cur"		=> "当前", /* cur */
"next"		=> "后继", /* next */
"last"		=> "先前", /* last */
"orig"		=> "初始", /* orig */
"histlegend"	=> "说明：(当前)指与当前修订版本比较；(先前)指与前一个修订版本比较，小 指细微修改。",
/* Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit */

# Diffs
#
"difference"	=> "（修订版本间差异）", /* Difference between revisions */
"loadingrev"	=> "载入修订版本比较", /* loading revision for diff */
"lineno"	=> "第 $1 行：", /* Line $1:",  */
"editcurrent"	=> "编辑本页的当前修订版本",
/* Edit the current version of this page */

# Search results
#
"searchresults" => "Search results",
"searchhelppage" => "Wikipedia:Searching",
"searchingwikipedia" => "Searching Wikipedia",
"searchresulttext" => "For more information about searching Wikipedia, see $1.",
"searchquery"	=> "For query \"$1\"",
"badquery"		=> "Badly formed search query",
"badquerytext"	=> "We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example \"fish and and scales\".
Please try another query.",
"matchtotals"	=> "The query \"$1\" matched $2 article titles
and the text of $3 articles.",
"titlematches"	=> "Article title matches",
"notitlematches" => "No article title matches",
"textmatches"	=> "Article text matches",
"notextmatches"	=> "No article text matches",
"prevn"		=> "previous $1",
"nextn"		=> "next $1",
"viewprevnext"	=> "View ($1) ($2) ($3).",
"showingresults" => "Showing below <b>$1</b> results starting with #<b>$2</b>.",
"nonefound"		=> "<strong>Note</strong>: unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",
"powersearch" => "Search",
"powersearchtext" => "
Search in namespaces :<br>
$1<br>
$2 List redirects &nbsp; Search for $3 $9",


# Preferences page
#
"preferences"	=> "参数设置",
"prefsnologin" => "还未登录",
"prefsnologintext" => "你必须先<a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">登录</a>才能设置个人参数。",
"prefslogintext" => "你已经以\"$1\"的身份登录。
你的内部ID是$2.",
"prefsreset"	=> "参数重新设置。",
"qbsettings"	=> "快速导航条设置", 
"changepassword" => "更改密码",
"skin"		=> "Skin",
"saveprefs"	=> "保存参数设置",
"resetprefs"	=> "重设参数",
"oldpassword"	=> "旧密码",
"newpassword"	=> "新密码",
"retypenew"	=> "重复新密码",
"textboxsize"	=> "文字框尺寸",
"rows"		=> "行",
"columns"	=> "列",
"searchresultshead" => "搜索结果设定",
"resultsperpage" => "每页显示连接数",
"contextlines"	=> "每连接行数",
"contextchars"	=> "每行字数",
"stubthreshold" => "Threshold for stub display",
"recentchangescount" => "最近更改页行数",
"savedprefs"	=> "你的个人参数设置已经保存。",
"timezonetext"	=> "输入当地时间与服务器时间(UTC)的时差。",
"localtime"	=> "当地时间",
"timezoneoffset" => "差",
"emailflag"	=> "禁止其他用户发e-mail给我",

# Recent changes
#

"recentchanges" => "最近更改", /* Recent changes */

"recentchangestext" =>
"本页用来察看 Wikipedia 最近的更改。
请参看[[wikipedia:欢迎，新来者|欢迎，新来者]]、
[[wikipedia:常见问题解答|常见问题解答]]、
[[Wikipedia:守则与指导|参与者守则与指导]]
（特别是[[Wikipedia:命名常规|命名常规]]、[[Wikipedia:中性的观点|中性的观点]]）
和[[Wikipedia:最常见失礼行为|最常见失礼行为]]。

如果您希望 Wikipedia 成功，那么请您不要增加受其它[[wikipedia:版权信息|版权]]
限制的材料，这一点将非常重要。相关的法律责任会伤害本项工程，所以请不要这样做。
此外请参见
[http://meta.wikipedia.org/wiki/Special:Recentchanges 最近的 meta 讨论]。
",
/*
Track the most recent changes to Wikipedia on this page.
[[Wikipedia:Welcome,_newcomers|Welcome, newcomers]]!
Please have a look at these pages: [[wikipedia:FAQ|Wikipedia FAQ]],
[[Wikipedia:Policies and guidelines|Wikipedia policy]]
(especially [[wikipedia:Naming conventions|naming conventions]],
[[wikipedia:Neutral point of view|neutral point of view]]),
and [[wikipedia:Most common Wikipedia faux pas|most common Wikipedia faux pas]].

If you want to see Wikipedia succeed, it's very important that you don't add
material restricted by others' [[wikipedia:Copyrights|copyrights]].
The legal liability could really hurt the project, so please don't do it.
See also the [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion].
*/

"rcloaderr"	=> "载入最近更改", /* Loading recent changes */
"rcnote"	=> "下面是最近 <strong>$2</strong> 天内最新的 <strong>$1</strong> 次改动。",
/* Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days. */
# "rclinks"	=> "Show last $1 changes in last $2 hours / last $3 days",
"rclinks"	=> "显示最近 $2 天内最新的 $1 次改动。",
/* Show last $1 changes in last $2 days. */
"rchide"	=> "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
/* in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits. */
"diff"		=> "差异", /* diff */
"hist"		=> "历史", /* hist */
"hide"		=> "隐藏", /* hide */
"show"		=> "显示", /* show */
"tableform"	=> "表格", /* table */
"listform"	=> "列表", /* list */
"nchanges"	=> "$1 次更改", /* $1 changes */
"minoreditletter" => "小", /* M */
"newpageletter" => "新", /* N */

# Upload
#
"upload"	=> "上载", /* Upload */
"uploadbtn"	=> "上载文件",
"uploadlink"	=> "上载图像",
"reupload"	=> "重新上载",
"reuploaddesc"	=> "返回上载表单。",
"uploadnologin" => "未登录",
"uploadnologintext"	=> "您必须先<a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">登录</a>
才能上载文件。",
"uploadfile"	=> "上载文件",
"uploaderror"	=> "上载错误",
"uploadtext"	=> "<strong>停止！</strong>在您上载之前，请先阅读并遵守Wikipedia<a href=\"" .
wfLocalUrlE( "Wikipedia:Image_use_policy" ) . "\">图像使用守则</a>。
<p>如果您要查看或搜索之前上载的图像，
请到<a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">已上载图像列表</a>.
所有上载与删除行为都被记录在<a href=\"" .
wfLocalUrlE( "Wikipedia:上载纪录" ) . "\">上载纪录</a>内。
<p>使用下面的表单来上载用在条目内新的图像文件。
在绝大多数浏览器内，你会看到一个\"浏览...\"按钮，点击它后就会跳出一个打开文件对话框。
选择一个文件后文件名将出现在按钮旁边的文字框中。
您也必须点击旁边的复选框确认您所上载的文件并没有违反相关版权法律。
点击\"上载\" 按钮完成上载程序。
如果您使用的是较慢的网络连接的话那么这个上载过程会需要一些时间。
<p>我们建议照相图片使用JPEG格式，绘图及其他图标图像使用PNG格式，音像则使用OGG格式。
请使用具有描述性的语言来命名您的文件以避免混乱。
要在文章中加入图像，使用以下形式的连接：
<b>[[图像:file.jpg]]</b>或者<b>[[图像:file.png|解释文字]]</b>
或<b>[[media:file.ogg]]</b>来连接音像文件。
<p>请注意在Wikipedia页面中，其他人可能会为了百科全书的利益而编辑或删除您的上载文件，
而如果您滥用上载系统，您则有可能被禁止使用上载功能。",
"uploadlog"		=> "上载纪录",
"uploadlogpage" => "上载纪录",
"uploadlogpagetext" => "以下是最近上载的文件的一览表。
所有显示的时间都是服务器时间（UTC）。
<ul>
</ul>
",
"filename"	=> "文件名",
"filedesc"	=> "简述",
"affirmation"	=> "我保证本文件的版权持有人同意将其在$1条款下发布。",
"copyrightpage" => "Wikipedia:版权信息",
"copyrightpagename" => "Wikipedia版权",
"uploadedfiles"	=> "已上载文件",
"noaffirmation" => "您必须保证您所上载的文件没有违反任何版权法律。",
"ignorewarning"	=> "忽略警告并保存文件。",
"minlength"		=> "图像名字必须至少有三个字母。",
"badfilename"	=> "图像名已被改为\"$1\".",
"badfiletype"	=> "\".$1\"不是所推荐的图像文件格式。",
"largefile"		=> "我们建议图像大小不超过100k。",
"successfulupload" => "上载成功",
"fileuploaded"	=> "文件\"$1\"上载成功。
请根据连接($2)到图像描述页添加有关文件信息，例如它的来源，在何时由谁创造，
以及其他任何您知道的关于改图像的信息。",
"uploadwarning" => "上载警告",
"savefile"		=> "保存文件",
"uploadedimage" => "已上载\"$1\"",

# Image list
#
"imagelist"		=> "图像列表",
"imagelisttext"	=> "以下是$1幅图像。",
"getimagelist"	=> "正在获取图像列表",
"ilshowmatch"	=> "显示所有匹对的图像",
"ilsubmit"		=> "搜索",
"showlast"		=> "显示最后$1幅图像。",
"all"			=> "全部",
"byname"		=> "按名字",
"bydate"		=> "按日期",
"bysize"		=> "按大小",
"imgdelete"		=> "删",
"imgdesc"		=> "述",
"imglegend"		=> "图标：(述) = 显示/编辑图像描述页。",
"imghistory"	=> "图像历史",
"revertimg"		=> "恢复rev",
"deleteimg"		=> "删",
"imghistlegend" => "Legend: (现) = 目前的图像，(删) = 删除旧版本，
(恢复) = 恢复到旧版本。
<br><i>点击日期查看当天上载的图像</i>.",
"imagelinks"	=> "图像连接",
"linkstoimage"	=> "以下页面连接到本图像：",
"nolinkstoimage" => "没有页面连接到本图像.",

# Statistics
# 统计
#

"statistics"	=> "统计", /* Statistics */
"sitestats"		=> "站点统计", /* Site statistics */
"userstats"		=> "用户统计", /* User statistics */

"sitestatstext" => "数据库中共有 <b>$1</b> 页页面；
其中包括对话页、关于 Wikipedia 的页面、最少量的\"stub\"页、重定向的页面，
以及未达到条目质量的页面；除此之外还有 <b>$2</b> 页可能是合乎标准的条目。
<p>从系统软件升级（ 2002 年 10 月 27 日）以来，全站点共有页面浏览 <b>$3</b> 次，
页面编辑 <b>$4</b> 次，每页平均编辑 <b>$5</b> 次，
各次编辑后页面的每个版本平均浏览 <b>$6</b> 次。",
/* There are <b>$1</b> total pages in the database.
This includes \"talk\" pages, pages about Wikipedia, minimal \"stub\"
pages, redirects, and others that probably don't qualify as articles.
Excluding those, there are <b>$2</b> pages that are probably legitimate
articles.<p>There have been a total of <b>$3</b> page views, and <b>$4</b> page edits since the software was upgraded (July 20, 2002). That comes to <b>$5</b> average edits per page, and <b>$6</b> views per edit. */

"userstatstext" => "现有 <b>$1</b> 位注册用户，
其中 <b>$2</b> 位是管理员（参见 $3 ）。",
/* There are <b>$1</b> registered users.<b>$2</b> of these are administrators (see $3). */


# Maintenance Page
#
"maintenance"		=> "维护页",
"maintnancepagetext"	=> "这页面提供了几个帮助Wikipedia日常维护的工具。但其中几个会对我们的数据库造成压力，所以请您不要在每修理好几个项目后就按重新载入 ;-)",
"maintenancebacklink"	=> "回去维护页",
"disambiguations"	=> "消含糊页",
"disambiguationspage"	=> "Wikipedia:Links_to_disambiguating_pages",
"disambiguationstext"	=> "以下的条目都有到消含糊页的链接，但它们应该是链到适当的题目。<br>一个页面会被视为消含糊页如果它是链自$1.<br>由其它他名字空间来的链接<i>不会</i>在这儿被列出来。",
"doubleredirects"	=> "雙重重定向",
"doubleredirectstext"	=> "<b>请注意：</b>这列表可能包括不正确的反应。这通常表示在那页面第一个#REDIRECT之下还有文字。<br>\n每一行都包含到第一跟第二个重定向页的链接，以及第二个重定向页的第一行文字，通常显示的都会是\“真正\” 的目标页面，也就是第一个重定向页应该指向的条目。",
"brokenredirects"	=> "损坏的重定向页",
"brokenredirectstext"	=> "以下的重定向页指向的是不存在的条目。",
"selflinks"		=> "有自我链接的页面",
"selflinkstext"		=> "以下的页面都错误地包含了连到自己的链接。",
"mispeelings"           => "Pages with misspellings",
"mispeelingstext"               => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
"mispeelingspage"       => "List of common misspellings",
"missinglanguagelinks"  => "Missing Language Links",
"missinglanguagelinksbutton"    => "Find missing language links for",
"missinglanguagelinkstext"      => "These articles do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
"orphans"	=> "孤立页面", /* Orphaned pages */
"lonelypages"	=> "孤立页面", /* Orphaned pages */
"unusedimages"	=> "未用图像", /* Unused images */
"popularpages"	=> "热点条目", /* Popular pages */
"nviews"	=> "$1 次浏览", /* $1 views */
"wantedpages"	=> "待撰页面", /* Wanted pages */
"nlinks"	=> "$1 个链接", /* $1 links */
"allpages"	=> "所有条目", /* All pages */
"randompage"	=> "随机页面", /* Random page */
"shortpages"	=> "短条目", /* Short pages */
"longpages"	=> "长条目", /* Long pages */
"listusers"	=> "用户列表", /* User list */
"specialpages"	=> "特殊页面", /* Special pages */
"spheading"	=> "特殊页面", /* Special pages */
"sysopspheading" => "Special pages for sysop use", /* Special pages for sysop use */
"developerspheading" => "Special pages for developer use", /* Special pages for developer use */
"protectpage"	=> "保护页面", /* Protect page */
"recentchangeslinked" => "链出更改", /* Watch links */
"rclsub"	=> "(从 \"$1\"链出的页面)", /* to pages linked from \"$1\") */
"debug"		=> "调试", /* Debug */
"newpages"	=> "新页面", /* New pages */
"movethispage"	=> "移动页面", /* Move this page */
"unusedimagestext" => "<p>Please note that other web sites
such as the international Wikipedias may link to an image with
a direct URL, and so may still be listed here despite being
in active use.", /*  */
"booksources"	=> "书目来源", /* Book sources */
"booksourcetext" => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
Wikipedia is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.", 
/*  */

# Email this user
#
"mailnologin"	=> "No send address",
"mailnologintext" => "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
and have a valid e-mail address in your <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">preferences</a>
to send e-mail to other users.",
"emailuser"	=> "给用户发信", /* E-mail this user */
"emailpage"	=> "E-mail user",
"emailpagetext"	=> "If this user has entered a valid e-mail address in
is user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the \"From\" address of the mail, so the recipient will be able
to reply.",
"noemailtitle"	=> "No e-mail address",
"noemailtext"	=> "This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.",
"emailfrom"	=> "发件人", /* From*/
"emailto"	=> "收件人", /* To*/
"emailsubject"	=> "主题", /* Subject */
"emailmessage"	=> "正文", /* Message */
"emailsend"	=> "发送", /* Send */
"emailsent"	=> "邮件发送", /* E-mail sent */
"emailsenttext" => "您的邮件已经被发送",
/* Your e-mail message has been sent. */

# Watchlist
#

"watchlist"	=> "监视列表", /* Watch list */
"watchlistsub"	=> "(用户\"$1\")", /* (for user \"$1\") */
"nowatchlist"	=> "You have no items on your watchlist.", /*  */
"watchnologin"	=> "Not logged in", /*  */
"watchnologintext"	=> "You must be <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to modify your watchlist.",
/*  */
"addedwatch"	=> "Added to watchlist", /*  */
"addedwatchtext" => "The page \"$1\" has been added to your <a href=\"" .
  wfLocalUrl( "Special:Watchlist" ) . "\">watchlist</a>.
Future changes to this page and its associated Talk page will be listed there,
and the page will appear <b>bolded</b> in the <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">list of recent changes</a> to
make it easier to pick out.</p>

<p>If you want to remove the page from your watchlist later, click \"Stop watching\" in the sidebar.",
 /*  */
"removedwatch"	=> "", /* Removed from watchlist */
"removedwatchtext" => "The page \"$1\" has been removed from your watchlist.",
/*  */
"watchthispage"	=> "监视本页", /* Watch this page */
"unwatchthispage" => "停止监视", /* Stop watching */
"notanarticle"	=> "Not an article",

# Delete/protect/revert
#
"deletepage"	=> "Delete page",
"confirm"	=> "Confirm",
"confirmdelete" => "Confirm delete",
"deletesub"	=> "(Deleting \"$1\")",
"confirmdeletetext" => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[Wikipedia:Policy]].",
"confirmcheck"	=> "Yes, I really want to delete this.",
"actioncomplete" => "Action complete",
"deletedtext"	=> "\"$1\" has been deleted.
See $2 for a record of recent deletions.",
"deletedarticle" => "deleted \"$1\"",
"dellogpage"	=> "Deletion_log",
"dellogpagetext" => "Below is a list of the most recent deletions.
All times shown are server time (UTC).
<ul>
</ul>
",
"deletionlog"	=> "deletion log",
"reverted"	=> "Reverted to earlier revision",
"deletecomment"	=> "Reason for deletion",
"imagereverted" => "Revert to earlier version was successful.",

# Undelete
"undelete" => "Restore deleted page",
"undeletepage" => "View and restore deleted pages",
"undeletepagetext" => "The following pages have been deleted but are still in the archive and
can be restored. The archive may be periodically cleaned out.",
"undeletearticle" => "Restore deleted article",
"undeleterevisions" => "$1 revisions archived",
"undeletehistory" => "If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored
revisions will appear in the prior history, and the current revision of the live page
will not be automatically replaced.",
"undeleterevision" => "Deleted revision as of $1",
"undeletebtn" => "Restore!",
"undeletedarticle" => "restored \"$1\"",
"undeletedtext"   => "The article [[$1]] has been successfully restored.
See [[Wikipedia:Deletion_log]] for a record of recent deletions and restorations.",

# Contributions
#
"contributions"	=> "用户贡献", /* User contributions */
"contribsub"	=> "For $1",
"nocontribs"	=> "No changes were found matching these criteria.",
"ucnote"	=> "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
"uclinks"	=> "View the last $1 changes; view the last $2 days.",

# What links here
#
"whatlinkshere"	=> "链入页面", /* What links here */
"notargettitle" => "No target", /*  */
"notargettext"	=> "You have not specified a target page or user
to perform this function on.",
/*  */

"linklistsub"	=> "(链到本页的页面列表)", /* (List of links) */
"linkshere"		=> "下列页面链接到本页：",
/* The following pages link to here:",  */
"nolinkshere"	=> "没有页面连接到这里。", /* No pages link to here. */
"isredirect"	=> "重定向页面", /* redirect page */

# Block/unblock IP
#
"blockip"		=> "Block IP address",
"blockiptext"	=> "Use the form below to block write access
from a specific IP address.
This should be done only only to prevent valndalism, and in
accordance with [[Wikipedia:Policy|Wikipedia policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
"ipaddress"		=> "IP Address",
"ipbreason"		=> "Reason",
"ipbsubmit"		=> "Block this address",
"badipaddress"	=> "The IP address is badly formed.",
"noblockreason" => "You must supply a reason for the block.",
"blockipsuccesssub" => "Block succeeded",
"blockipsuccesstext" => "The IP address \"$1\" has been blocked.
<br>See [[Special:Ipblocklist|IP block list]] to review blocks.",
"unblockip"		=> "Unblock IP address",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address.",
"ipusubmit"		=> "Unblock this address",
"ipusuccess"	=> "IP address \"$1\" unblocked",
"ipblocklist"	=> "List of blocked IP addresses",
"blocklistline"	=> "$1, $2 blocked $3",
"blocklink"		=> "block",
"unblocklink"	=> "unblock",
"contribslink"	=> "contribs",

# Developer tools
#
"lockdb"	=> "Lock database",
"unlockdb"	=> "Unlock database",
"lockdbtext"	=> "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",
"lockconfirm"	=> "Yes, I really want to lock the database.",
"unlockconfirm"	=> "Yes, I really want to unlock the database.",
"lockbtn"	=> "Lock database",
"unlockbtn"	=> "Unlock database",
"locknoconfirm" => "You did not check the confirmation box.",
"lockdbsuccesssub" => "Database lock succeeded",
"unlockdbsuccesssub" => "Database lock removed",
"lockdbsuccesstext" => "The Wikipedia database has been locked.
<br>Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The Wikipedia database has been unlocked.",

# SQL query
#
"asksql"	=> "SQL query",
"asksqltext"	=> "Use the form below to make a direct query of the
Wikipedia database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
"sqlquery"	=> "Enter query",
"querybtn"	=> "Submit query",
"selectonly"	=> "Queries other than \"SELECT\" are restricted to
Wikipedia developers.",
"querysuccessful" => "Query successful",

# Move page
#
"movepage"	=> "移动页面", /* Move page */
"movepagetext"	=> "Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed, and the talk
page, if any, will not be moved.
<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.",
"movearticle"	=> "移动页面", /* Move page */
"movenologin"	=> "Not logged in",
"movenologintext" => "You must be a registered user and <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a>
to move a page.",
"newtitle"	=> "To new title",
"movepagebtn"	=> "移动页面", /* Move page */
"pagemovedsub"	=> "移动成功", /* Move succeeded */
"pagemovedtext" => "Page \"[[$1]]\" moved to \"[[$2]]\".",
"articleexists" => "A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name.",
"movedto"	=> "moved to",
"movetalk"	=> "Move \"talk\" page as well, if applicable.",
"talkpagemoved" => "The corresponding talk page was also moved.",
"talkpagenotmoved" => "The corresponding talk page was <strong>not</strong> moved.",

);

class LanguageZh extends LanguageUtf8 {

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsZh ;
		return $wgDefaultUserOptionsZh ;
		}

	function getBookstoreList () {
		global $wgBookstoreListZh ;
		return $wgBookstoreListZh ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesZh;
		return $wgNamespaceNamesZh;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesZh;
		return $wgNamespaceNamesZh[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesZh;

		foreach ( $wgNamespaceNamesZh as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Aliases
        if ( 0 == strcasecmp( "Special", $text ) ) { return -1; }
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsZh;
		return $wgQuickbarSettingsZh;
	}

	function getSkinNames() {
		global $wgSkinNamesZh;
		return $wgSkinNamesZh;
	}


	function getUserToggles() {
		global $wgUserTogglesZh;
		return $wgUserTogglesZh;
	}

	function getLanguageNames() {
		global $wgLanguageNamesZh;
		return $wgLanguageNamesZh;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesZh;
		if ( ! array_key_exists( $code, $wgLanguageNamesZh ) ) {
			return "";
		}
		return $wgLanguageNamesZh[$code];
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesZh;
		return $wgMonthNamesZh[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsZh;
		return $wgMonthAbbreviationsZh[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesZh;
		return $wgWeekdayNamesZh[$key-1];
	}

	# The date and time functions can be tweaked if need be

	# inherit userAdjust()
	 
    function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = substr( $ts, 0, 4 ) . "年" .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  (0 + substr( $ts, 6, 2 )) . "日";
		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->date( $ts, $adj ) . " " . $this->time( $ts, $adj );
	}

	# inherit default rfc1123()

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesZh;
		return $wgValidSpecialPagesZh;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesZh;
		return $wgSysopSpecialPagesZh;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesZh;
		return $wgDeveloperSpecialPagesZh;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesZh, $wgAllMessagesEn;
		$m = $wgAllMessagesZh[$key];

		if ( "" == $m ) { return $wgAllMessagesEn[$key]; }
		else return $m;
	}
		
	# inherit default iconv(), ucfirst(), checkTitleEncoding()

	function stripForSearch( $string ) {
		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex
		global $wikiLowerChars;
		return preg_replace(
		  "/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
		  "' U8' . bin2hex( strtr( \"\$1\", \$wikiLowerChars ) )",
		  $string );
	}

}

?>
