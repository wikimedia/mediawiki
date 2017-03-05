// This file stores the output from the PHP parser for various messages, arguments,
// languages, and parser modes. Intended for use by a unit test framework by looping
// through the object and comparing its parser return value with the 'result' property.
// Last generated with generateJqueryMsgData.php at Fri, 10 Jul 2015 11:44:08 +0000
/* eslint-disable */

mediaWiki.libs.phpParserData = {
    "messages": {
        "en_undelete_short": "Undelete {{PLURAL:$1|one edit|$1 edits}}",
        "en_category-subcat-count": "{{PLURAL:$2|This category has only the following subcategory.|This category has the following {{PLURAL:$1|subcategory|$1 subcategories}}, out of $2 total.}}",
        "fr_undelete_short": "Restaurer $1 modification{{PLURAL:$1||s}}",
        "fr_category-subcat-count": "Cette cat\u00e9gorie comprend {{PLURAL:$2|la sous-cat\u00e9gorie|$2 sous-cat\u00e9gories, dont {{PLURAL:$1|celle|les $1}}}} ci-dessous.",
        "ar_undelete_short": "\u0627\u0633\u062a\u0631\u062c\u0627\u0639 {{PLURAL:$1||\u062a\u0639\u062f\u064a\u0644 \u0648\u0627\u062d\u062f|\u062a\u0639\u062f\u064a\u0644\u064a\u0646|$1 \u062a\u0639\u062f\u064a\u0644\u0627\u062a|$1 \u062a\u0639\u062f\u064a\u0644\u0627\u064b|$1 \u062a\u0639\u062f\u064a\u0644}}",
        "ar_category-subcat-count": "{{PLURAL:$2|\u0647\u0630\u0627 \u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u064a\u062d\u0648\u064a \u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u0627\u0644\u0641\u0631\u0639\u064a \u0627\u0644\u062a\u0627\u0644\u064a|\u0647\u0630\u0627 \u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u064a\u062d\u0648\u064a {{PLURAL:$1||\u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u0627\u0644\u0641\u0631\u0639\u064a|\u062a\u0635\u0646\u064a\u0641\u064a\u0646 \u0641\u0631\u0639\u064a\u064a\u0646|$1 \u062a\u0635\u0646\u064a\u0641\u0627\u062a \u0641\u0631\u0639\u064a\u0629}}\u060c \u0645\u0646 \u0625\u062c\u0645\u0627\u0644\u064a $2.}}",
        "jp_undelete_short": "Undelete {{PLURAL:$1|one edit|$1 edits}}",
        "jp_category-subcat-count": "{{PLURAL:$2|This category has only the following subcategory.|This category has the following {{PLURAL:$1|subcategory|$1 subcategories}}, out of $2 total.}}",
        "zh_undelete_short": "\u8fd8\u539f{{PLURAL:$1|$1\u4e2a\u7f16\u8f91}}",
        "zh_category-subcat-count": "{{PLURAL:$2|\u672c\u5206\u7c7b\u53ea\u6709\u4ee5\u4e0b\u5b50\u5206\u7c7b\u3002|\u672c\u5206\u7c7b\u6709\u4ee5\u4e0b$1\u4e2a\u5b50\u5206\u7c7b\uff0c\u5171\u6709$2\u4e2a\u5b50\u5206\u7c7b\u3002}}"
    },
    "tests": [
        {
            "name": "en undelete_short 0",
            "key": "en_undelete_short",
            "args": [
                0
            ],
            "result": "Undelete 0 edits",
            "lang": "en"
        },
        {
            "name": "en undelete_short 1",
            "key": "en_undelete_short",
            "args": [
                1
            ],
            "result": "Undelete one edit",
            "lang": "en"
        },
        {
            "name": "en undelete_short 2",
            "key": "en_undelete_short",
            "args": [
                2
            ],
            "result": "Undelete 2 edits",
            "lang": "en"
        },
        {
            "name": "en undelete_short 5",
            "key": "en_undelete_short",
            "args": [
                5
            ],
            "result": "Undelete 5 edits",
            "lang": "en"
        },
        {
            "name": "en undelete_short 21",
            "key": "en_undelete_short",
            "args": [
                21
            ],
            "result": "Undelete 21 edits",
            "lang": "en"
        },
        {
            "name": "en undelete_short 101",
            "key": "en_undelete_short",
            "args": [
                101
            ],
            "result": "Undelete 101 edits",
            "lang": "en"
        },
        {
            "name": "en category-subcat-count 0,10",
            "key": "en_category-subcat-count",
            "args": [
                0,
                10
            ],
            "result": "This category has the following 0 subcategories, out of 10 total.",
            "lang": "en"
        },
        {
            "name": "en category-subcat-count 1,1",
            "key": "en_category-subcat-count",
            "args": [
                1,
                1
            ],
            "result": "This category has only the following subcategory.",
            "lang": "en"
        },
        {
            "name": "en category-subcat-count 1,2",
            "key": "en_category-subcat-count",
            "args": [
                1,
                2
            ],
            "result": "This category has the following subcategory, out of 2 total.",
            "lang": "en"
        },
        {
            "name": "en category-subcat-count 3,30",
            "key": "en_category-subcat-count",
            "args": [
                3,
                30
            ],
            "result": "This category has the following 3 subcategories, out of 30 total.",
            "lang": "en"
        },
        {
            "name": "fr undelete_short 0",
            "key": "fr_undelete_short",
            "args": [
                0
            ],
            "result": "Restaurer 0 modification",
            "lang": "fr"
        },
        {
            "name": "fr undelete_short 1",
            "key": "fr_undelete_short",
            "args": [
                1
            ],
            "result": "Restaurer 1 modification",
            "lang": "fr"
        },
        {
            "name": "fr undelete_short 2",
            "key": "fr_undelete_short",
            "args": [
                2
            ],
            "result": "Restaurer 2 modifications",
            "lang": "fr"
        },
        {
            "name": "fr undelete_short 5",
            "key": "fr_undelete_short",
            "args": [
                5
            ],
            "result": "Restaurer 5 modifications",
            "lang": "fr"
        },
        {
            "name": "fr undelete_short 21",
            "key": "fr_undelete_short",
            "args": [
                21
            ],
            "result": "Restaurer 21 modifications",
            "lang": "fr"
        },
        {
            "name": "fr undelete_short 101",
            "key": "fr_undelete_short",
            "args": [
                101
            ],
            "result": "Restaurer 101 modifications",
            "lang": "fr"
        },
        {
            "name": "fr category-subcat-count 0,10",
            "key": "fr_category-subcat-count",
            "args": [
                0,
                10
            ],
            "result": "Cette cat\u00e9gorie comprend 10 sous-cat\u00e9gories, dont celle ci-dessous.",
            "lang": "fr"
        },
        {
            "name": "fr category-subcat-count 1,1",
            "key": "fr_category-subcat-count",
            "args": [
                1,
                1
            ],
            "result": "Cette cat\u00e9gorie comprend la sous-cat\u00e9gorie ci-dessous.",
            "lang": "fr"
        },
        {
            "name": "fr category-subcat-count 1,2",
            "key": "fr_category-subcat-count",
            "args": [
                1,
                2
            ],
            "result": "Cette cat\u00e9gorie comprend 2 sous-cat\u00e9gories, dont celle ci-dessous.",
            "lang": "fr"
        },
        {
            "name": "fr category-subcat-count 3,30",
            "key": "fr_category-subcat-count",
            "args": [
                3,
                30
            ],
            "result": "Cette cat\u00e9gorie comprend 30 sous-cat\u00e9gories, dont les 3 ci-dessous.",
            "lang": "fr"
        },
        {
            "name": "ar undelete_short 0",
            "key": "ar_undelete_short",
            "args": [
                0
            ],
            "result": "\u0627\u0633\u062a\u0631\u062c\u0627\u0639 ",
            "lang": "ar"
        },
        {
            "name": "ar undelete_short 1",
            "key": "ar_undelete_short",
            "args": [
                1
            ],
            "result": "\u0627\u0633\u062a\u0631\u062c\u0627\u0639 \u062a\u0639\u062f\u064a\u0644 \u0648\u0627\u062d\u062f",
            "lang": "ar"
        },
        {
            "name": "ar undelete_short 2",
            "key": "ar_undelete_short",
            "args": [
                2
            ],
            "result": "\u0627\u0633\u062a\u0631\u062c\u0627\u0639 \u062a\u0639\u062f\u064a\u0644\u064a\u0646",
            "lang": "ar"
        },
        {
            "name": "ar undelete_short 5",
            "key": "ar_undelete_short",
            "args": [
                5
            ],
            "result": "\u0627\u0633\u062a\u0631\u062c\u0627\u0639 5 \u062a\u0639\u062f\u064a\u0644\u0627\u062a",
            "lang": "ar"
        },
        {
            "name": "ar undelete_short 21",
            "key": "ar_undelete_short",
            "args": [
                21
            ],
            "result": "\u0627\u0633\u062a\u0631\u062c\u0627\u0639 21 \u062a\u0639\u062f\u064a\u0644\u0627\u064b",
            "lang": "ar"
        },
        {
            "name": "ar undelete_short 101",
            "key": "ar_undelete_short",
            "args": [
                101
            ],
            "result": "\u0627\u0633\u062a\u0631\u062c\u0627\u0639 101 \u062a\u0639\u062f\u064a\u0644",
            "lang": "ar"
        },
        {
            "name": "ar category-subcat-count 0,10",
            "key": "ar_category-subcat-count",
            "args": [
                0,
                10
            ],
            "result": "\u0647\u0630\u0627 \u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u064a\u062d\u0648\u064a \u060c \u0645\u0646 \u0625\u062c\u0645\u0627\u0644\u064a 10.",
            "lang": "ar"
        },
        {
            "name": "ar category-subcat-count 1,1",
            "key": "ar_category-subcat-count",
            "args": [
                1,
                1
            ],
            "result": "\u0647\u0630\u0627 \u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u064a\u062d\u0648\u064a \u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u0627\u0644\u0641\u0631\u0639\u064a\u060c \u0645\u0646 \u0625\u062c\u0645\u0627\u0644\u064a 1.",
            "lang": "ar"
        },
        {
            "name": "ar category-subcat-count 1,2",
            "key": "ar_category-subcat-count",
            "args": [
                1,
                2
            ],
            "result": "\u0647\u0630\u0627 \u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u064a\u062d\u0648\u064a \u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u0627\u0644\u0641\u0631\u0639\u064a\u060c \u0645\u0646 \u0625\u062c\u0645\u0627\u0644\u064a 2.",
            "lang": "ar"
        },
        {
            "name": "ar category-subcat-count 3,30",
            "key": "ar_category-subcat-count",
            "args": [
                3,
                30
            ],
            "result": "\u0647\u0630\u0627 \u0627\u0644\u062a\u0635\u0646\u064a\u0641 \u064a\u062d\u0648\u064a 3 \u062a\u0635\u0646\u064a\u0641\u0627\u062a \u0641\u0631\u0639\u064a\u0629\u060c \u0645\u0646 \u0625\u062c\u0645\u0627\u0644\u064a 30.",
            "lang": "ar"
        },
        {
            "name": "jp undelete_short 0",
            "key": "jp_undelete_short",
            "args": [
                0
            ],
            "result": "Undelete 0 edits",
            "lang": "jp"
        },
        {
            "name": "jp undelete_short 1",
            "key": "jp_undelete_short",
            "args": [
                1
            ],
            "result": "Undelete one edit",
            "lang": "jp"
        },
        {
            "name": "jp undelete_short 2",
            "key": "jp_undelete_short",
            "args": [
                2
            ],
            "result": "Undelete 2 edits",
            "lang": "jp"
        },
        {
            "name": "jp undelete_short 5",
            "key": "jp_undelete_short",
            "args": [
                5
            ],
            "result": "Undelete 5 edits",
            "lang": "jp"
        },
        {
            "name": "jp undelete_short 21",
            "key": "jp_undelete_short",
            "args": [
                21
            ],
            "result": "Undelete 21 edits",
            "lang": "jp"
        },
        {
            "name": "jp undelete_short 101",
            "key": "jp_undelete_short",
            "args": [
                101
            ],
            "result": "Undelete 101 edits",
            "lang": "jp"
        },
        {
            "name": "jp category-subcat-count 0,10",
            "key": "jp_category-subcat-count",
            "args": [
                0,
                10
            ],
            "result": "This category has the following 0 subcategories, out of 10 total.",
            "lang": "jp"
        },
        {
            "name": "jp category-subcat-count 1,1",
            "key": "jp_category-subcat-count",
            "args": [
                1,
                1
            ],
            "result": "This category has only the following subcategory.",
            "lang": "jp"
        },
        {
            "name": "jp category-subcat-count 1,2",
            "key": "jp_category-subcat-count",
            "args": [
                1,
                2
            ],
            "result": "This category has the following subcategory, out of 2 total.",
            "lang": "jp"
        },
        {
            "name": "jp category-subcat-count 3,30",
            "key": "jp_category-subcat-count",
            "args": [
                3,
                30
            ],
            "result": "This category has the following 3 subcategories, out of 30 total.",
            "lang": "jp"
        },
        {
            "name": "zh undelete_short 0",
            "key": "zh_undelete_short",
            "args": [
                0
            ],
            "result": "\u8fd8\u539f0\u4e2a\u7f16\u8f91",
            "lang": "zh"
        },
        {
            "name": "zh undelete_short 1",
            "key": "zh_undelete_short",
            "args": [
                1
            ],
            "result": "\u8fd8\u539f1\u4e2a\u7f16\u8f91",
            "lang": "zh"
        },
        {
            "name": "zh undelete_short 2",
            "key": "zh_undelete_short",
            "args": [
                2
            ],
            "result": "\u8fd8\u539f2\u4e2a\u7f16\u8f91",
            "lang": "zh"
        },
        {
            "name": "zh undelete_short 5",
            "key": "zh_undelete_short",
            "args": [
                5
            ],
            "result": "\u8fd8\u539f5\u4e2a\u7f16\u8f91",
            "lang": "zh"
        },
        {
            "name": "zh undelete_short 21",
            "key": "zh_undelete_short",
            "args": [
                21
            ],
            "result": "\u8fd8\u539f21\u4e2a\u7f16\u8f91",
            "lang": "zh"
        },
        {
            "name": "zh undelete_short 101",
            "key": "zh_undelete_short",
            "args": [
                101
            ],
            "result": "\u8fd8\u539f101\u4e2a\u7f16\u8f91",
            "lang": "zh"
        },
        {
            "name": "zh category-subcat-count 0,10",
            "key": "zh_category-subcat-count",
            "args": [
                0,
                10
            ],
            "result": "\u672c\u5206\u7c7b\u6709\u4ee5\u4e0b0\u4e2a\u5b50\u5206\u7c7b\uff0c\u5171\u670910\u4e2a\u5b50\u5206\u7c7b\u3002",
            "lang": "zh"
        },
        {
            "name": "zh category-subcat-count 1,1",
            "key": "zh_category-subcat-count",
            "args": [
                1,
                1
            ],
            "result": "\u672c\u5206\u7c7b\u53ea\u6709\u4ee5\u4e0b\u5b50\u5206\u7c7b\u3002",
            "lang": "zh"
        },
        {
            "name": "zh category-subcat-count 1,2",
            "key": "zh_category-subcat-count",
            "args": [
                1,
                2
            ],
            "result": "\u672c\u5206\u7c7b\u6709\u4ee5\u4e0b1\u4e2a\u5b50\u5206\u7c7b\uff0c\u5171\u67092\u4e2a\u5b50\u5206\u7c7b\u3002",
            "lang": "zh"
        },
        {
            "name": "zh category-subcat-count 3,30",
            "key": "zh_category-subcat-count",
            "args": [
                3,
                30
            ],
            "result": "\u672c\u5206\u7c7b\u6709\u4ee5\u4e0b3\u4e2a\u5b50\u5206\u7c7b\uff0c\u5171\u670930\u4e2a\u5b50\u5206\u7c7b\u3002",
            "lang": "zh"
        }
    ]
};
