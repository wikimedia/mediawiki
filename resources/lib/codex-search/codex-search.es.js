var __defProp = Object.defineProperty;
var __defProps = Object.defineProperties;
var __getOwnPropDescs = Object.getOwnPropertyDescriptors;
var __getOwnPropSymbols = Object.getOwnPropertySymbols;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __propIsEnum = Object.prototype.propertyIsEnumerable;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __spreadValues = (a, b) => {
  for (var prop in b || (b = {}))
    if (__hasOwnProp.call(b, prop))
      __defNormalProp(a, prop, b[prop]);
  if (__getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(b)) {
      if (__propIsEnum.call(b, prop))
        __defNormalProp(a, prop, b[prop]);
    }
  return a;
};
var __spreadProps = (a, b) => __defProps(a, __getOwnPropDescs(b));
var __objRest = (source, exclude) => {
  var target = {};
  for (var prop in source)
    if (__hasOwnProp.call(source, prop) && exclude.indexOf(prop) < 0)
      target[prop] = source[prop];
  if (source != null && __getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(source)) {
      if (exclude.indexOf(prop) < 0 && __propIsEnum.call(source, prop))
        target[prop] = source[prop];
    }
  return target;
};
import { ref, onMounted, defineComponent, computed, openBlock, createElementBlock, normalizeClass, toDisplayString, createCommentVNode, resolveComponent, createVNode, Transition, withCtx, normalizeStyle, createElementVNode, createTextVNode, withModifiers, renderSlot, createBlock, resolveDynamicComponent, Fragment, getCurrentInstance, onUnmounted, watch, toRef, withDirectives, renderList, mergeProps, vShow, Comment, warn, vModelDynamic, withKeys, toRefs } from "vue";
var svgArticleSearch = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>';
var svgClear = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>';
var svgImageLayoutFrameless = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>';
var svgSearch = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4-5.4-5.4zM3 8a5 5 0 1010 0A5 5 0 103 8z"/>';
const cdxIconArticleSearch = svgArticleSearch;
const cdxIconClear = svgClear;
const cdxIconImageLayoutFrameless = svgImageLayoutFrameless;
const cdxIconSearch = svgSearch;
function resolveIcon(icon, langCode, dir) {
  if (typeof icon === "string" || "path" in icon) {
    return icon;
  }
  if ("shouldFlip" in icon) {
    return icon.ltr;
  }
  if ("rtl" in icon) {
    return dir === "rtl" ? icon.rtl : icon.ltr;
  }
  const langCodeIcon = langCode in icon.langCodeMap ? icon.langCodeMap[langCode] : icon.default;
  return typeof langCodeIcon === "string" || "path" in langCodeIcon ? langCodeIcon : langCodeIcon.ltr;
}
function shouldIconFlip(icon, langCode) {
  if (typeof icon === "string") {
    return false;
  }
  if ("langCodeMap" in icon) {
    const langCodeIcon = langCode in icon.langCodeMap ? icon.langCodeMap[langCode] : icon.default;
    if (typeof langCodeIcon === "string") {
      return false;
    }
    icon = langCodeIcon;
  }
  if ("shouldFlipExceptions" in icon && Array.isArray(icon.shouldFlipExceptions)) {
    const exception = icon.shouldFlipExceptions.indexOf(langCode);
    return exception === void 0 || exception === -1;
  }
  if ("shouldFlip" in icon) {
    return icon.shouldFlip;
  }
  return false;
}
function useComputedDirection(root) {
  const computedDir = ref(null);
  onMounted(() => {
    const dir = window.getComputedStyle(root.value).direction;
    computedDir.value = dir === "ltr" || dir === "rtl" ? dir : null;
  });
  return computedDir;
}
function useComputedLanguage(root) {
  const computedLang = ref("");
  onMounted(() => {
    let ancestor = root.value;
    while (ancestor && ancestor.lang === "") {
      ancestor = ancestor.parentElement;
    }
    computedLang.value = ancestor ? ancestor.lang : null;
  });
  return computedLang;
}
var Icon_vue_vue_type_style_index_0_lang = "";
var _export_sfc = (sfc, props) => {
  const target = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target[key] = val;
  }
  return target;
};
const _sfc_main$9 = defineComponent({
  name: "CdxIcon",
  props: {
    icon: {
      type: [String, Object],
      required: true
    },
    iconLabel: {
      type: String,
      default: ""
    },
    lang: {
      type: String,
      default: null
    },
    dir: {
      type: String,
      default: null
    }
  },
  emits: ["click"],
  setup(props, { emit }) {
    const rootElement = ref();
    const computedDir = useComputedDirection(rootElement);
    const computedLang = useComputedLanguage(rootElement);
    const overriddenDir = computed(() => props.dir || computedDir.value);
    const overriddenLang = computed(() => props.lang || computedLang.value);
    const rootClasses = computed(() => ({
      "cdx-icon--flipped": overriddenDir.value === "rtl" && overriddenLang.value !== null && shouldIconFlip(props.icon, overriddenLang.value)
    }));
    const resolvedIcon = computed(() => resolveIcon(props.icon, overriddenLang.value || "", overriddenDir.value || "ltr"));
    const iconSvg = computed(() => typeof resolvedIcon.value === "string" ? resolvedIcon.value : "");
    const iconPath = computed(() => typeof resolvedIcon.value !== "string" ? resolvedIcon.value.path : "");
    const onClick = (event) => {
      emit("click", event);
    };
    return {
      rootElement,
      rootClasses,
      iconSvg,
      iconPath,
      onClick
    };
  }
});
const _hoisted_1$8 = ["aria-hidden"];
const _hoisted_2$6 = { key: 0 };
const _hoisted_3$3 = ["innerHTML"];
const _hoisted_4$2 = ["d"];
function _sfc_render$9(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", {
    ref: "rootElement",
    class: normalizeClass(["cdx-icon", _ctx.rootClasses]),
    onClick: _cache[0] || (_cache[0] = (...args) => _ctx.onClick && _ctx.onClick(...args))
  }, [
    (openBlock(), createElementBlock("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": !_ctx.iconLabel
    }, [
      _ctx.iconLabel ? (openBlock(), createElementBlock("title", _hoisted_2$6, toDisplayString(_ctx.iconLabel), 1)) : createCommentVNode("", true),
      _ctx.iconSvg ? (openBlock(), createElementBlock("g", {
        key: 1,
        fill: "currentColor",
        innerHTML: _ctx.iconSvg
      }, null, 8, _hoisted_3$3)) : (openBlock(), createElementBlock("path", {
        key: 2,
        d: _ctx.iconPath,
        fill: "currentColor"
      }, null, 8, _hoisted_4$2))
    ], 8, _hoisted_1$8))
  ], 2);
}
var CdxIcon = /* @__PURE__ */ _export_sfc(_sfc_main$9, [["render", _sfc_render$9]]);
var Thumbnail_vue_vue_type_style_index_0_lang = "";
const _sfc_main$8 = defineComponent({
  name: "CdxThumbnail",
  components: { CdxIcon },
  props: {
    thumbnail: {
      type: [Object, null],
      default: null
    },
    placeholderIcon: {
      type: [String, Object],
      default: cdxIconImageLayoutFrameless
    }
  },
  setup: (props) => {
    const thumbnailLoaded = ref(false);
    const thumbnailStyle = ref({});
    const preloadThumbnail = (url) => {
      const escapedUrl = url.replace(/([\\"\n])/g, "\\$1");
      const image = new Image();
      image.onload = () => {
        thumbnailStyle.value = { backgroundImage: `url("${escapedUrl}")` };
        thumbnailLoaded.value = true;
      };
      image.onerror = () => {
        thumbnailLoaded.value = false;
      };
      image.src = escapedUrl;
    };
    onMounted(() => {
      var _a;
      if ((_a = props.thumbnail) == null ? void 0 : _a.url) {
        preloadThumbnail(props.thumbnail.url);
      }
    });
    return {
      thumbnailStyle,
      thumbnailLoaded
    };
  }
});
const _hoisted_1$7 = { class: "cdx-thumbnail" };
const _hoisted_2$5 = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function _sfc_render$8(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_cdx_icon = resolveComponent("cdx-icon");
  return openBlock(), createElementBlock("span", _hoisted_1$7, [
    !_ctx.thumbnailLoaded ? (openBlock(), createElementBlock("span", _hoisted_2$5, [
      createVNode(_component_cdx_icon, {
        icon: _ctx.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])) : createCommentVNode("", true),
    createVNode(Transition, { name: "cdx-thumbnail__image" }, {
      default: withCtx(() => [
        _ctx.thumbnailLoaded ? (openBlock(), createElementBlock("span", {
          key: 0,
          style: normalizeStyle(_ctx.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : createCommentVNode("", true)
      ]),
      _: 1
    })
  ]);
}
var CdxThumbnail = /* @__PURE__ */ _export_sfc(_sfc_main$8, [["render", _sfc_render$8]]);
function regExpEscape(value) {
  return value.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const COMBINING_MARK = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function splitStringAtMatch(query, title) {
  if (!query) {
    return [title, "", ""];
  }
  const sanitizedQuery = regExpEscape(query);
  const match = new RegExp(sanitizedQuery + COMBINING_MARK + "*", "i").exec(title);
  if (!match || match.index === void 0) {
    return [title, "", ""];
  }
  const matchStartIndex = match.index;
  const matchEndIndex = matchStartIndex + match[0].length;
  const highlightedTitle = title.slice(matchStartIndex, matchEndIndex);
  const beforeHighlight = title.slice(0, matchStartIndex);
  const afterHighlight = title.slice(matchEndIndex, title.length);
  return [beforeHighlight, highlightedTitle, afterHighlight];
}
var SearchResultTitle_vue_vue_type_style_index_0_lang = "";
const _sfc_main$7 = defineComponent({
  name: "CdxSearchResultTitle",
  props: {
    title: {
      type: String,
      required: true
    },
    searchQuery: {
      type: String,
      default: ""
    }
  },
  setup: (props) => {
    const titleChunks = computed(() => splitStringAtMatch(props.searchQuery, String(props.title)));
    return {
      titleChunks
    };
  }
});
const _hoisted_1$6 = { class: "cdx-search-result-title" };
const _hoisted_2$4 = { class: "cdx-search-result-title__match" };
function _sfc_render$7(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", _hoisted_1$6, [
    createElementVNode("bdi", null, [
      createTextVNode(toDisplayString(_ctx.titleChunks[0]), 1),
      createElementVNode("span", _hoisted_2$4, toDisplayString(_ctx.titleChunks[1]), 1),
      createTextVNode(toDisplayString(_ctx.titleChunks[2]), 1)
    ])
  ]);
}
var CdxSearchResultTitle = /* @__PURE__ */ _export_sfc(_sfc_main$7, [["render", _sfc_render$7]]);
var MenuItem_vue_vue_type_style_index_0_lang = "";
const _sfc_main$6 = defineComponent({
  name: "CdxMenuItem",
  components: { CdxIcon, CdxThumbnail, CdxSearchResultTitle },
  props: {
    id: {
      type: String,
      required: true
    },
    value: {
      type: [String, Number],
      required: true
    },
    disabled: {
      type: Boolean,
      default: false
    },
    selected: {
      type: Boolean,
      default: false
    },
    active: {
      type: Boolean,
      default: false
    },
    highlighted: {
      type: Boolean,
      default: false
    },
    label: {
      type: String,
      default: ""
    },
    match: {
      type: String,
      default: ""
    },
    url: {
      type: String,
      default: ""
    },
    icon: {
      type: [String, Object],
      default: ""
    },
    showThumbnail: {
      type: Boolean,
      default: false
    },
    thumbnail: {
      type: [Object, null],
      default: null
    },
    description: {
      type: [String, null],
      default: ""
    },
    searchQuery: {
      type: String,
      default: ""
    },
    boldLabel: {
      type: Boolean,
      default: false
    },
    hideDescriptionOverflow: {
      type: Boolean,
      default: false
    },
    language: {
      type: Object,
      default: () => {
        return {};
      }
    }
  },
  emits: [
    "change"
  ],
  setup: (props, { emit }) => {
    const onMouseEnter = () => {
      emit("change", "highlighted", true);
    };
    const onMouseLeave = () => {
      emit("change", "highlighted", false);
    };
    const onMouseDown = (e) => {
      if (e.button === 0) {
        emit("change", "active", true);
      }
    };
    const onClick = () => {
      emit("change", "selected", true);
    };
    const highlightQuery = computed(() => props.searchQuery.length > 0);
    const rootClasses = computed(() => {
      return {
        "cdx-menu-item--selected": props.selected,
        "cdx-menu-item--active": props.active && props.highlighted,
        "cdx-menu-item--highlighted": props.highlighted,
        "cdx-menu-item--enabled": !props.disabled,
        "cdx-menu-item--disabled": props.disabled,
        "cdx-menu-item--highlight-query": highlightQuery.value,
        "cdx-menu-item--bold-label": props.boldLabel,
        "cdx-menu-item--has-description": !!props.description,
        "cdx-menu-item--hide-description-overflow": props.hideDescriptionOverflow
      };
    });
    const contentTag = computed(() => props.url ? "a" : "span");
    const title = computed(() => props.label || String(props.value));
    return {
      onMouseEnter,
      onMouseLeave,
      onMouseDown,
      onClick,
      highlightQuery,
      rootClasses,
      contentTag,
      title
    };
  }
});
const _hoisted_1$5 = ["id", "aria-disabled", "aria-selected"];
const _hoisted_2$3 = { class: "cdx-menu-item__text" };
const _hoisted_3$2 = ["lang"];
const _hoisted_4$1 = /* @__PURE__ */ createTextVNode(/* @__PURE__ */ toDisplayString(" ") + " ");
const _hoisted_5$1 = ["lang"];
const _hoisted_6$1 = ["lang"];
function _sfc_render$6(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_cdx_thumbnail = resolveComponent("cdx-thumbnail");
  const _component_cdx_icon = resolveComponent("cdx-icon");
  const _component_cdx_search_result_title = resolveComponent("cdx-search-result-title");
  return openBlock(), createElementBlock("li", {
    id: _ctx.id,
    role: "option",
    class: normalizeClass(["cdx-menu-item", _ctx.rootClasses]),
    "aria-disabled": _ctx.disabled,
    "aria-selected": _ctx.selected,
    onMouseenter: _cache[0] || (_cache[0] = (...args) => _ctx.onMouseEnter && _ctx.onMouseEnter(...args)),
    onMouseleave: _cache[1] || (_cache[1] = (...args) => _ctx.onMouseLeave && _ctx.onMouseLeave(...args)),
    onMousedown: _cache[2] || (_cache[2] = withModifiers((...args) => _ctx.onMouseDown && _ctx.onMouseDown(...args), ["prevent"])),
    onClick: _cache[3] || (_cache[3] = (...args) => _ctx.onClick && _ctx.onClick(...args))
  }, [
    renderSlot(_ctx.$slots, "default", {}, () => [
      (openBlock(), createBlock(resolveDynamicComponent(_ctx.contentTag), {
        href: _ctx.url ? _ctx.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: withCtx(() => {
          var _a, _b, _c, _d, _e;
          return [
            _ctx.showThumbnail ? (openBlock(), createBlock(_component_cdx_thumbnail, {
              key: 0,
              thumbnail: _ctx.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : _ctx.icon ? (openBlock(), createBlock(_component_cdx_icon, {
              key: 1,
              icon: _ctx.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : createCommentVNode("", true),
            createElementVNode("span", _hoisted_2$3, [
              _ctx.highlightQuery ? (openBlock(), createBlock(_component_cdx_search_result_title, {
                key: 0,
                title: _ctx.title,
                "search-query": _ctx.searchQuery,
                lang: (_a = _ctx.language) == null ? void 0 : _a.label
              }, null, 8, ["title", "search-query", "lang"])) : (openBlock(), createElementBlock("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (_b = _ctx.language) == null ? void 0 : _b.label
              }, [
                createElementVNode("bdi", null, toDisplayString(_ctx.title), 1)
              ], 8, _hoisted_3$2)),
              _ctx.match ? (openBlock(), createElementBlock(Fragment, { key: 2 }, [
                _hoisted_4$1,
                _ctx.highlightQuery ? (openBlock(), createBlock(_component_cdx_search_result_title, {
                  key: 0,
                  title: _ctx.match,
                  "search-query": _ctx.searchQuery,
                  lang: (_c = _ctx.language) == null ? void 0 : _c.match
                }, null, 8, ["title", "search-query", "lang"])) : (openBlock(), createElementBlock("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (_d = _ctx.language) == null ? void 0 : _d.match
                }, [
                  createElementVNode("bdi", null, toDisplayString(_ctx.match), 1)
                ], 8, _hoisted_5$1))
              ], 64)) : createCommentVNode("", true),
              _ctx.description ? (openBlock(), createElementBlock("span", {
                key: 3,
                class: "cdx-menu-item__text__description",
                lang: (_e = _ctx.language) == null ? void 0 : _e.description
              }, [
                createElementVNode("bdi", null, toDisplayString(_ctx.description), 1)
              ], 8, _hoisted_6$1)) : createCommentVNode("", true)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, _hoisted_1$5);
}
var CdxMenuItem = /* @__PURE__ */ _export_sfc(_sfc_main$6, [["render", _sfc_render$6]]);
var ProgressBar_vue_vue_type_style_index_0_lang = "";
const _sfc_main$5 = defineComponent({
  name: "CdxProgressBar",
  props: {
    inline: {
      type: Boolean,
      default: false
    }
  },
  setup(props) {
    const rootClasses = computed(() => {
      return {
        "cdx-progress-bar--block": !props.inline,
        "cdx-progress-bar--inline": props.inline
      };
    });
    return {
      rootClasses
    };
  }
});
const _hoisted_1$4 = /* @__PURE__ */ createElementVNode("div", { class: "cdx-progress-bar__bar" }, null, -1);
const _hoisted_2$2 = [
  _hoisted_1$4
];
function _sfc_render$5(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-progress-bar", _ctx.rootClasses]),
    role: "progressbar",
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, _hoisted_2$2, 2);
}
var CdxProgressBar = /* @__PURE__ */ _export_sfc(_sfc_main$5, [["render", _sfc_render$5]]);
const LibraryPrefix = "cdx";
const ButtonActions = [
  "default",
  "progressive",
  "destructive"
];
const ButtonTypes = [
  "normal",
  "primary",
  "quiet"
];
const TextInputTypes = [
  "text",
  "search"
];
const DebounceInterval = 120;
const PendingDelay = 500;
const MenuFooterValue = "cdx-menu-footer-item";
let counter = 0;
function useGeneratedId(identifier) {
  const vm = getCurrentInstance();
  const externalId = (vm == null ? void 0 : vm.props.id) || (vm == null ? void 0 : vm.attrs.id);
  if (identifier) {
    return `${LibraryPrefix}-${identifier}-${counter++}`;
  } else if (externalId) {
    return `${LibraryPrefix}-${externalId}-${counter++}`;
  } else {
    return `${LibraryPrefix}-${counter++}`;
  }
}
var Menu_vue_vue_type_style_index_0_lang = "";
const _sfc_main$4 = defineComponent({
  name: "CdxMenu",
  components: {
    CdxMenuItem,
    CdxProgressBar
  },
  props: {
    menuItems: {
      type: Array,
      required: true
    },
    selected: {
      type: [String, Number, null],
      required: true
    },
    expanded: {
      type: Boolean,
      required: true
    },
    showPending: {
      type: Boolean,
      default: false
    },
    showThumbnail: {
      type: Boolean,
      default: false
    },
    boldLabel: {
      type: Boolean,
      default: false
    },
    hideDescriptionOverflow: {
      type: Boolean,
      default: false
    },
    searchQuery: {
      type: String,
      default: ""
    },
    showNoResultsSlot: {
      type: Boolean,
      default: null
    }
  },
  emits: [
    "update:selected",
    "update:expanded",
    "menu-item-click",
    "menu-item-keyboard-navigation"
  ],
  expose: [
    "clearActive",
    "getHighlightedMenuItem",
    "delegateKeyNavigation"
  ],
  setup(props, { emit, slots }) {
    const computedMenuItems = computed(() => {
      return props.menuItems.map((menuItem) => __spreadProps(__spreadValues({}, menuItem), {
        id: useGeneratedId("menu-item")
      }));
    });
    const computedShowNoResultsSlot = computed(() => {
      if (!slots["no-results"]) {
        return false;
      }
      if (props.showNoResultsSlot !== null) {
        return props.showNoResultsSlot;
      }
      return computedMenuItems.value.length === 0;
    });
    const highlightedMenuItem = ref(null);
    const activeMenuItem = ref(null);
    function findSelectedMenuItem() {
      return computedMenuItems.value.find((menuItem) => menuItem.value === props.selected);
    }
    function handleMenuItemChange(menuState, menuItem) {
      var _a;
      if (menuItem && menuItem.disabled) {
        return;
      }
      switch (menuState) {
        case "selected":
          emit("update:selected", (_a = menuItem == null ? void 0 : menuItem.value) != null ? _a : null);
          emit("update:expanded", false);
          activeMenuItem.value = null;
          break;
        case "highlighted":
          highlightedMenuItem.value = menuItem || null;
          break;
        case "active":
          activeMenuItem.value = menuItem || null;
          break;
      }
    }
    const highlightedMenuItemIndex = computed(() => {
      if (highlightedMenuItem.value === null) {
        return;
      }
      return computedMenuItems.value.findIndex((menuItem) => menuItem.value === highlightedMenuItem.value.value);
    });
    function handleHighlight(newHighlightedMenuItem) {
      if (!newHighlightedMenuItem) {
        return;
      }
      handleMenuItemChange("highlighted", newHighlightedMenuItem);
      emit("menu-item-keyboard-navigation", newHighlightedMenuItem);
    }
    function highlightPrev(highlightedIndex) {
      var _a;
      const findPrevEnabled = (startIndex) => {
        for (let index = startIndex - 1; index >= 0; index--) {
          if (!computedMenuItems.value[index].disabled) {
            return computedMenuItems.value[index];
          }
        }
      };
      highlightedIndex = highlightedIndex || computedMenuItems.value.length;
      const prev = (_a = findPrevEnabled(highlightedIndex)) != null ? _a : findPrevEnabled(computedMenuItems.value.length);
      handleHighlight(prev);
    }
    function highlightNext(highlightedIndex) {
      const findNextEnabled = (startIndex) => computedMenuItems.value.find((item, index) => !item.disabled && index > startIndex);
      highlightedIndex = highlightedIndex != null ? highlightedIndex : -1;
      const next = findNextEnabled(highlightedIndex) || findNextEnabled(-1);
      handleHighlight(next);
    }
    function handleKeyNavigation(e, prevent = true) {
      function handleExpandMenu() {
        emit("update:expanded", true);
        handleMenuItemChange("highlighted", findSelectedMenuItem());
      }
      function maybePrevent() {
        if (prevent) {
          e.preventDefault();
          e.stopPropagation();
        }
      }
      switch (e.key) {
        case "Enter":
        case " ":
          maybePrevent();
          if (props.expanded) {
            if (highlightedMenuItem.value) {
              emit("update:selected", highlightedMenuItem.value.value);
            }
            emit("update:expanded", false);
          } else {
            handleExpandMenu();
          }
          return true;
        case "Tab":
          if (props.expanded) {
            if (highlightedMenuItem.value) {
              emit("update:selected", highlightedMenuItem.value.value);
            }
            emit("update:expanded", false);
          }
          return true;
        case "ArrowUp":
          maybePrevent();
          if (props.expanded) {
            if (highlightedMenuItem.value === null) {
              handleMenuItemChange("highlighted", findSelectedMenuItem());
            }
            highlightPrev(highlightedMenuItemIndex.value);
          } else {
            handleExpandMenu();
          }
          return true;
        case "ArrowDown":
          maybePrevent();
          if (props.expanded) {
            if (highlightedMenuItem.value === null) {
              handleMenuItemChange("highlighted", findSelectedMenuItem());
            }
            highlightNext(highlightedMenuItemIndex.value);
          } else {
            handleExpandMenu();
          }
          return true;
        case "Home":
          maybePrevent();
          if (props.expanded) {
            if (highlightedMenuItem.value === null) {
              handleMenuItemChange("highlighted", findSelectedMenuItem());
            }
            highlightNext();
          } else {
            handleExpandMenu();
          }
          return true;
        case "End":
          maybePrevent();
          if (props.expanded) {
            if (highlightedMenuItem.value === null) {
              handleMenuItemChange("highlighted", findSelectedMenuItem());
            }
            highlightPrev();
          } else {
            handleExpandMenu();
          }
          return true;
        case "Escape":
          maybePrevent();
          emit("update:expanded", false);
          return true;
        default:
          return false;
      }
    }
    function onMouseUp() {
      handleMenuItemChange("active");
    }
    onMounted(() => {
      document.addEventListener("mouseup", onMouseUp);
    });
    onUnmounted(() => {
      document.removeEventListener("mouseup", onMouseUp);
    });
    watch(toRef(props, "expanded"), (newVal) => {
      const selectedMenuItem = findSelectedMenuItem();
      if (!newVal && highlightedMenuItem.value && selectedMenuItem === void 0) {
        handleMenuItemChange("highlighted");
      }
      if (newVal && selectedMenuItem !== void 0) {
        handleMenuItemChange("highlighted", selectedMenuItem);
      }
    });
    return {
      computedMenuItems,
      computedShowNoResultsSlot,
      highlightedMenuItem,
      activeMenuItem,
      handleMenuItemChange,
      handleKeyNavigation
    };
  },
  methods: {
    getHighlightedMenuItem() {
      return this.highlightedMenuItem;
    },
    clearActive() {
      this.handleMenuItemChange("active");
    },
    delegateKeyNavigation(event, prevent = true) {
      return this.handleKeyNavigation(event, prevent);
    }
  }
});
const _hoisted_1$3 = {
  class: "cdx-menu",
  role: "listbox",
  "aria-multiselectable": "false"
};
const _hoisted_2$1 = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
};
const _hoisted_3$1 = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function _sfc_render$4(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_cdx_menu_item = resolveComponent("cdx-menu-item");
  const _component_cdx_progress_bar = resolveComponent("cdx-progress-bar");
  return withDirectives((openBlock(), createElementBlock("ul", _hoisted_1$3, [
    _ctx.showPending && _ctx.computedMenuItems.length === 0 && _ctx.$slots.pending ? (openBlock(), createElementBlock("li", _hoisted_2$1, [
      renderSlot(_ctx.$slots, "pending")
    ])) : createCommentVNode("", true),
    _ctx.computedShowNoResultsSlot ? (openBlock(), createElementBlock("li", _hoisted_3$1, [
      renderSlot(_ctx.$slots, "no-results")
    ])) : createCommentVNode("", true),
    (openBlock(true), createElementBlock(Fragment, null, renderList(_ctx.computedMenuItems, (menuItem) => {
      var _a, _b;
      return openBlock(), createBlock(_component_cdx_menu_item, mergeProps({
        key: menuItem.value
      }, menuItem, {
        selected: menuItem.value === _ctx.selected,
        active: menuItem.value === ((_a = _ctx.activeMenuItem) == null ? void 0 : _a.value),
        highlighted: menuItem.value === ((_b = _ctx.highlightedMenuItem) == null ? void 0 : _b.value),
        "show-thumbnail": _ctx.showThumbnail,
        "bold-label": _ctx.boldLabel,
        "hide-description-overflow": _ctx.hideDescriptionOverflow,
        "search-query": _ctx.searchQuery,
        onChange: (menuState, setState) => _ctx.handleMenuItemChange(menuState, setState && menuItem),
        onClick: ($event) => _ctx.$emit("menu-item-click", menuItem)
      }), {
        default: withCtx(() => {
          var _a2, _b2;
          return [
            renderSlot(_ctx.$slots, "default", {
              menuItem,
              active: menuItem.value === ((_a2 = _ctx.activeMenuItem) == null ? void 0 : _a2.value) && menuItem.value === ((_b2 = _ctx.highlightedMenuItem) == null ? void 0 : _b2.value)
            })
          ];
        }),
        _: 2
      }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
    }), 128)),
    _ctx.showPending ? (openBlock(), createBlock(_component_cdx_progress_bar, {
      key: 2,
      class: "cdx-menu__progress-bar",
      inline: true
    })) : createCommentVNode("", true)
  ], 512)), [
    [vShow, _ctx.expanded]
  ]);
}
var CdxMenu = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["render", _sfc_render$4]]);
function makeStringTypeValidator(allowedValues) {
  return (s) => typeof s === "string" && allowedValues.indexOf(s) !== -1;
}
var Button_vue_vue_type_style_index_0_lang = "";
const buttonTypeValidator = makeStringTypeValidator(ButtonTypes);
const buttonActionValidator = makeStringTypeValidator(ButtonActions);
const validateIconOnlyButtonAttrs = (attrs) => {
  if (!attrs["aria-label"] && !attrs["aria-hidden"]) {
    warn(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/main/components/button.html#default-icon-only`);
  }
};
function flattenVNodeContents(nodes) {
  const flattenedContents = [];
  for (const node of nodes) {
    if (typeof node === "string" && node.trim() !== "") {
      flattenedContents.push(node);
    } else if (Array.isArray(node)) {
      flattenedContents.push(...flattenVNodeContents(node));
    } else if (typeof node === "object" && node) {
      if (typeof node.type === "string" || typeof node.type === "object") {
        flattenedContents.push(node);
      } else if (node.type !== Comment) {
        if (typeof node.children === "string" && node.children.trim() !== "") {
          flattenedContents.push(node.children);
        } else if (Array.isArray(node.children)) {
          flattenedContents.push(...flattenVNodeContents(node.children));
        }
      }
    }
  }
  return flattenedContents;
}
const isIconOnlyButton = (slotContent, attrs) => {
  if (!slotContent) {
    return false;
  }
  const flattenedContents = flattenVNodeContents(slotContent);
  if (flattenedContents.length !== 1) {
    return false;
  }
  const soleNode = flattenedContents[0];
  const isIconComponent = typeof soleNode === "object" && typeof soleNode.type === "object" && "name" in soleNode.type && soleNode.type.name === CdxIcon.name;
  const isSvgTag = typeof soleNode === "object" && soleNode.type === "svg";
  if (isIconComponent || isSvgTag) {
    validateIconOnlyButtonAttrs(attrs);
    return true;
  }
  return false;
};
const _sfc_main$3 = defineComponent({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: buttonActionValidator
    },
    type: {
      type: String,
      default: "normal",
      validator: buttonTypeValidator
    }
  },
  emits: ["click"],
  setup(props, { emit, slots, attrs }) {
    const rootClasses = computed(() => {
      var _a;
      return {
        [`cdx-button--action-${props.action}`]: true,
        [`cdx-button--type-${props.type}`]: true,
        "cdx-button--framed": props.type !== "quiet",
        "cdx-button--icon-only": isIconOnlyButton((_a = slots.default) == null ? void 0 : _a.call(slots), attrs)
      };
    });
    const onClick = (event) => {
      emit("click", event);
    };
    return {
      rootClasses,
      onClick
    };
  }
});
function _sfc_render$3(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("button", {
    class: normalizeClass(["cdx-button", _ctx.rootClasses]),
    onClick: _cache[0] || (_cache[0] = (...args) => _ctx.onClick && _ctx.onClick(...args))
  }, [
    renderSlot(_ctx.$slots, "default")
  ], 2);
}
var CdxButton = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["render", _sfc_render$3]]);
function useModelWrapper(modelValueRef, emit, eventName) {
  return computed({
    get: () => modelValueRef.value,
    set: (value) => emit(eventName || "update:modelValue", value)
  });
}
function useSplitAttributes(attrs, internalClasses = computed(() => {
  return {};
})) {
  const rootClasses = computed(() => {
    const classRecord = __objRest(internalClasses.value, []);
    if (attrs.class) {
      const providedClasses = attrs.class.split(" ");
      providedClasses.forEach((className) => {
        classRecord[className] = true;
      });
    }
    return classRecord;
  });
  const rootStyle = computed(() => {
    if ("style" in attrs) {
      return attrs.style;
    }
    return void 0;
  });
  const otherAttrs = computed(() => {
    const _a = attrs, { class: _ignoredClass, style: _ignoredStyle } = _a, attrsCopy = __objRest(_a, ["class", "style"]);
    return attrsCopy;
  });
  return {
    rootClasses,
    rootStyle,
    otherAttrs
  };
}
var TextInput_vue_vue_type_style_index_0_lang = "";
const textInputTypeValidator = makeStringTypeValidator(TextInputTypes);
const _sfc_main$2 = defineComponent({
  name: "CdxTextInput",
  components: { CdxIcon },
  inheritAttrs: false,
  expose: ["focus"],
  props: {
    modelValue: {
      type: [String, Number],
      default: ""
    },
    inputType: {
      type: String,
      default: "text",
      validator: textInputTypeValidator
    },
    disabled: {
      type: Boolean,
      default: false
    },
    startIcon: {
      type: [String, Object],
      default: void 0
    },
    endIcon: {
      type: [String, Object],
      default: void 0
    },
    clearable: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "update:modelValue",
    "input",
    "change",
    "focus",
    "blur"
  ],
  setup(props, { emit, attrs }) {
    const wrappedModel = useModelWrapper(toRef(props, "modelValue"), emit);
    const isClearable = computed(() => {
      return props.clearable && !!wrappedModel.value && !props.disabled;
    });
    const internalClasses = computed(() => {
      return {
        "cdx-text-input--has-start-icon": !!props.startIcon,
        "cdx-text-input--has-end-icon": !!props.endIcon,
        "cdx-text-input--clearable": isClearable.value
      };
    });
    const {
      rootClasses,
      rootStyle,
      otherAttrs
    } = useSplitAttributes(attrs, internalClasses);
    const inputClasses = computed(() => {
      return {
        "cdx-text-input__input--has-value": !!wrappedModel.value
      };
    });
    const onClear = () => {
      wrappedModel.value = "";
    };
    const onInput = (event) => {
      emit("input", event);
    };
    const onChange = (event) => {
      emit("change", event);
    };
    const onFocus = (event) => {
      emit("focus", event);
    };
    const onBlur = (event) => {
      emit("blur", event);
    };
    return {
      wrappedModel,
      isClearable,
      rootClasses,
      rootStyle,
      otherAttrs,
      inputClasses,
      onClear,
      onInput,
      onChange,
      onFocus,
      onBlur,
      cdxIconClear
    };
  },
  methods: {
    focus() {
      const input = this.$refs.input;
      input.focus();
    }
  }
});
const _hoisted_1$2 = ["type", "disabled"];
function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_cdx_icon = resolveComponent("cdx-icon");
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-text-input", _ctx.rootClasses]),
    style: normalizeStyle(_ctx.rootStyle)
  }, [
    withDirectives(createElementVNode("input", mergeProps({
      ref: "input",
      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.wrappedModel = $event),
      class: ["cdx-text-input__input", _ctx.inputClasses]
    }, _ctx.otherAttrs, {
      type: _ctx.inputType,
      disabled: _ctx.disabled,
      onInput: _cache[1] || (_cache[1] = (...args) => _ctx.onInput && _ctx.onInput(...args)),
      onChange: _cache[2] || (_cache[2] = (...args) => _ctx.onChange && _ctx.onChange(...args)),
      onFocus: _cache[3] || (_cache[3] = (...args) => _ctx.onFocus && _ctx.onFocus(...args)),
      onBlur: _cache[4] || (_cache[4] = (...args) => _ctx.onBlur && _ctx.onBlur(...args))
    }), null, 16, _hoisted_1$2), [
      [vModelDynamic, _ctx.wrappedModel]
    ]),
    _ctx.startIcon ? (openBlock(), createBlock(_component_cdx_icon, {
      key: 0,
      icon: _ctx.startIcon,
      class: "cdx-text-input__icon cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : createCommentVNode("", true),
    _ctx.endIcon ? (openBlock(), createBlock(_component_cdx_icon, {
      key: 1,
      icon: _ctx.endIcon,
      class: "cdx-text-input__icon cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : createCommentVNode("", true),
    _ctx.isClearable ? (openBlock(), createBlock(_component_cdx_icon, {
      key: 2,
      icon: _ctx.cdxIconClear,
      class: "cdx-text-input__icon cdx-text-input__clear-icon",
      onMousedown: _cache[5] || (_cache[5] = withModifiers(() => {
      }, ["prevent"])),
      onClick: _ctx.onClear
    }, null, 8, ["icon", "onClick"])) : createCommentVNode("", true)
  ], 6);
}
var CdxTextInput = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["render", _sfc_render$2]]);
var SearchInput_vue_vue_type_style_index_0_lang = "";
const _sfc_main$1 = defineComponent({
  name: "CdxSearchInput",
  components: {
    CdxButton,
    CdxTextInput
  },
  inheritAttrs: false,
  props: {
    modelValue: {
      type: [String, Number],
      default: ""
    },
    buttonLabel: {
      type: String,
      default: ""
    }
  },
  emits: [
    "update:modelValue",
    "submit-click"
  ],
  setup(props, { emit, attrs }) {
    const wrappedModel = useModelWrapper(toRef(props, "modelValue"), emit);
    const internalClasses = computed(() => {
      return {
        "cdx-search-input--has-end-button": !!props.buttonLabel
      };
    });
    const {
      rootClasses,
      rootStyle,
      otherAttrs
    } = useSplitAttributes(attrs, internalClasses);
    const handleSubmit = () => {
      emit("submit-click", wrappedModel.value);
    };
    return {
      wrappedModel,
      rootClasses,
      rootStyle,
      otherAttrs,
      handleSubmit,
      searchIcon: cdxIconSearch
    };
  },
  methods: {
    focus() {
      const textInput = this.$refs.textInput;
      textInput.focus();
    }
  }
});
const _hoisted_1$1 = { class: "cdx-search-input__input-wrapper" };
function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_cdx_text_input = resolveComponent("cdx-text-input");
  const _component_cdx_button = resolveComponent("cdx-button");
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-search-input", _ctx.rootClasses]),
    style: normalizeStyle(_ctx.rootStyle)
  }, [
    createElementVNode("div", _hoisted_1$1, [
      createVNode(_component_cdx_text_input, mergeProps({
        ref: "textInput",
        modelValue: _ctx.wrappedModel,
        "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.wrappedModel = $event),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": _ctx.searchIcon
      }, _ctx.otherAttrs, {
        onKeydown: withKeys(_ctx.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "onKeydown"]),
      renderSlot(_ctx.$slots, "default")
    ]),
    _ctx.buttonLabel ? (openBlock(), createBlock(_component_cdx_button, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: _ctx.handleSubmit
    }, {
      default: withCtx(() => [
        createTextVNode(toDisplayString(_ctx.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : createCommentVNode("", true)
  ], 6);
}
var CdxSearchInput = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["render", _sfc_render$1]]);
var TypeaheadSearch_vue_vue_type_style_index_0_lang = "";
const _sfc_main = defineComponent({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon,
    CdxMenu,
    CdxSearchInput
  },
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      required: true
    },
    formAction: {
      type: String,
      required: true
    },
    searchResultsLabel: {
      type: String,
      required: true
    },
    searchResults: {
      type: Array,
      required: true
    },
    buttonLabel: {
      type: String,
      default: ""
    },
    initialInputValue: {
      type: String,
      default: ""
    },
    searchFooterUrl: {
      type: String,
      default: ""
    },
    debounceInterval: {
      type: Number,
      default: DebounceInterval
    },
    highlightQuery: {
      type: Boolean,
      default: false
    },
    showThumbnail: {
      type: Boolean,
      default: false
    },
    autoExpandWidth: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "input",
    "search-result-click",
    "submit"
  ],
  setup(props, { attrs, emit, slots }) {
    const { searchResults, searchFooterUrl, debounceInterval } = toRefs(props);
    const form = ref();
    const menu = ref();
    const menuId = useGeneratedId("typeahead-search-menu");
    const expanded = ref(false);
    const pending = ref(false);
    const showPending = ref(false);
    const isActive = ref(false);
    const inputValue = ref(props.initialInputValue);
    const searchQuery = ref("");
    const highlightedId = computed(() => {
      var _a, _b;
      return (_b = (_a = menu.value) == null ? void 0 : _a.getHighlightedMenuItem()) == null ? void 0 : _b.id;
    });
    const selection = ref(null);
    const menuMessageClass = computed(() => ({
      "cdx-typeahead-search__menu-message--with-thumbnail": props.showThumbnail
    }));
    const selectedResult = computed(() => props.searchResults.find((searchResult) => searchResult.value === selection.value));
    const searchResultsWithFooter = computed(() => searchFooterUrl.value ? searchResults.value.concat([
      { value: MenuFooterValue, url: searchFooterUrl.value }
    ]) : searchResults.value);
    const internalClasses = computed(() => {
      return {
        "cdx-typeahead-search--active": isActive.value,
        "cdx-typeahead-search--show-thumbnail": props.showThumbnail,
        "cdx-typeahead-search--expanded": expanded.value,
        "cdx-typeahead-search--auto-expand-width": props.showThumbnail && props.autoExpandWidth
      };
    });
    const {
      rootClasses,
      rootStyle,
      otherAttrs
    } = useSplitAttributes(attrs, internalClasses);
    function asSearchResult(menuItem) {
      return menuItem;
    }
    const menuConfig = computed(() => {
      return {
        showThumbnail: props.showThumbnail,
        boldLabel: true,
        hideDescriptionOverflow: true
      };
    });
    let debounceId;
    let pendingDelayId;
    function onUpdateInputValue(newVal, immediate = false) {
      if (selectedResult.value && selectedResult.value.label !== newVal && selectedResult.value.value !== newVal) {
        selection.value = null;
      }
      if (pendingDelayId !== void 0) {
        clearTimeout(pendingDelayId);
        pendingDelayId = void 0;
      }
      if (newVal === "") {
        expanded.value = false;
      } else {
        pending.value = true;
        if (slots["search-results-pending"]) {
          pendingDelayId = setTimeout(() => {
            if (isActive.value) {
              expanded.value = true;
            }
            showPending.value = true;
          }, PendingDelay);
        }
      }
      if (debounceId !== void 0) {
        clearTimeout(debounceId);
        debounceId = void 0;
      }
      const handleUpdateInputValue = () => {
        emit("input", newVal);
      };
      if (immediate) {
        handleUpdateInputValue();
      } else {
        debounceId = setTimeout(() => {
          handleUpdateInputValue();
        }, debounceInterval.value);
      }
    }
    function onUpdateMenuSelection(newVal) {
      if (newVal === MenuFooterValue) {
        selection.value = null;
        inputValue.value = searchQuery.value;
        return;
      }
      selection.value = newVal;
      if (newVal !== null) {
        inputValue.value = selectedResult.value ? selectedResult.value.label || String(selectedResult.value.value) : "";
      }
    }
    function onFocus() {
      isActive.value = true;
      if (searchQuery.value || showPending.value) {
        expanded.value = true;
      }
    }
    function onBlur() {
      isActive.value = false;
      expanded.value = false;
    }
    function onSearchResultClick(searchResult) {
      const _a = searchResult, { id } = _a, resultWithoutId = __objRest(_a, ["id"]);
      const emittedResult = resultWithoutId.value !== MenuFooterValue ? resultWithoutId : null;
      const searchResultClickEvent = {
        searchResult: emittedResult,
        index: searchResultsWithFooter.value.findIndex((r) => r.value === searchResult.value),
        numberOfResults: searchResults.value.length
      };
      emit("search-result-click", searchResultClickEvent);
    }
    function onSearchResultKeyboardNavigation(searchResult) {
      if (searchResult.value === MenuFooterValue) {
        inputValue.value = searchQuery.value;
        return;
      }
      inputValue.value = searchResult.value ? searchResult.label || String(searchResult.value) : "";
    }
    function onSearchFooterClick(footerMenuItem) {
      var _a;
      expanded.value = false;
      (_a = menu.value) == null ? void 0 : _a.clearActive();
      onSearchResultClick(footerMenuItem);
    }
    function onSubmit() {
      let emittedResult = null;
      let selectedResultIndex = -1;
      if (selectedResult.value) {
        emittedResult = selectedResult.value;
        selectedResultIndex = props.searchResults.indexOf(selectedResult.value);
      }
      const submitEvent = {
        searchResult: emittedResult,
        index: selectedResultIndex,
        numberOfResults: searchResults.value.length
      };
      emit("submit", submitEvent);
    }
    function onKeydown(e) {
      if (!menu.value || !searchQuery.value || e.key === " " && expanded.value) {
        return;
      }
      const highlightedResult = menu.value.getHighlightedMenuItem();
      switch (e.key) {
        case "Enter":
          if (highlightedResult) {
            if (highlightedResult.value === MenuFooterValue) {
              window.location.assign(searchFooterUrl.value);
            } else {
              menu.value.delegateKeyNavigation(e, false);
            }
          }
          expanded.value = false;
          break;
        case "Tab":
          expanded.value = false;
          break;
        default:
          menu.value.delegateKeyNavigation(e);
          break;
      }
    }
    onMounted(() => {
      if (props.initialInputValue) {
        onUpdateInputValue(props.initialInputValue, true);
      }
    });
    watch(toRef(props, "searchResults"), () => {
      searchQuery.value = inputValue.value.trim();
      if (isActive.value && pending.value && searchQuery.value.length > 0) {
        expanded.value = true;
      }
      if (pendingDelayId !== void 0) {
        clearTimeout(pendingDelayId);
        pendingDelayId = void 0;
      }
      pending.value = false;
      showPending.value = false;
    });
    return {
      form,
      menu,
      menuId,
      highlightedId,
      selection,
      menuMessageClass,
      searchResultsWithFooter,
      asSearchResult,
      inputValue,
      searchQuery,
      expanded,
      showPending,
      rootClasses,
      rootStyle,
      otherAttrs,
      menuConfig,
      onUpdateInputValue,
      onUpdateMenuSelection,
      onFocus,
      onBlur,
      onSearchResultClick,
      onSearchResultKeyboardNavigation,
      onSearchFooterClick,
      onSubmit,
      onKeydown,
      MenuFooterValue,
      articleIcon: cdxIconArticleSearch
    };
  },
  methods: {
    focus() {
      const searchInput = this.$refs.searchInput;
      searchInput.focus();
    }
  }
});
const _hoisted_1 = ["id", "action"];
const _hoisted_2 = { class: "cdx-typeahead-search__menu-message__text" };
const _hoisted_3 = { class: "cdx-typeahead-search__menu-message__text" };
const _hoisted_4 = ["href", "onClickCapture"];
const _hoisted_5 = { class: "cdx-typeahead-search__search-footer__text" };
const _hoisted_6 = { class: "cdx-typeahead-search__search-footer__query" };
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_cdx_icon = resolveComponent("cdx-icon");
  const _component_cdx_menu = resolveComponent("cdx-menu");
  const _component_cdx_search_input = resolveComponent("cdx-search-input");
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-typeahead-search", _ctx.rootClasses]),
    style: normalizeStyle(_ctx.rootStyle)
  }, [
    createElementVNode("form", {
      id: _ctx.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: _ctx.formAction,
      onSubmit: _cache[3] || (_cache[3] = (...args) => _ctx.onSubmit && _ctx.onSubmit(...args))
    }, [
      createVNode(_component_cdx_search_input, mergeProps({
        ref: "searchInput",
        modelValue: _ctx.inputValue,
        "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => _ctx.inputValue = $event),
        "button-label": _ctx.buttonLabel
      }, _ctx.otherAttrs, {
        class: "cdx-typeahead-search__input",
        name: "search",
        role: "combobox",
        autocomplete: "off",
        "aria-autocomplete": "list",
        "aria-owns": _ctx.menuId,
        "aria-expanded": _ctx.expanded,
        "aria-activedescendant": _ctx.highlightedId,
        autocapitalize: "off",
        "onUpdate:modelValue": _ctx.onUpdateInputValue,
        onFocus: _ctx.onFocus,
        onBlur: _ctx.onBlur,
        onKeydown: _ctx.onKeydown
      }), {
        default: withCtx(() => [
          createVNode(_component_cdx_menu, mergeProps({
            id: _ctx.menuId,
            ref: "menu",
            expanded: _ctx.expanded,
            "onUpdate:expanded": _cache[0] || (_cache[0] = ($event) => _ctx.expanded = $event),
            "show-pending": _ctx.showPending,
            selected: _ctx.selection,
            "menu-items": _ctx.searchResultsWithFooter,
            "search-query": _ctx.highlightQuery ? _ctx.searchQuery : "",
            "show-no-results-slot": _ctx.searchQuery.length > 0 && _ctx.searchResults.length === 0 && _ctx.$slots["search-no-results-text"] && _ctx.$slots["search-no-results-text"]().length > 0
          }, _ctx.menuConfig, {
            "aria-label": _ctx.searchResultsLabel,
            "onUpdate:selected": _ctx.onUpdateMenuSelection,
            onMenuItemClick: _cache[1] || (_cache[1] = (menuItem) => _ctx.onSearchResultClick(_ctx.asSearchResult(menuItem))),
            onMenuItemKeyboardNavigation: _ctx.onSearchResultKeyboardNavigation
          }), {
            pending: withCtx(() => [
              createElementVNode("div", {
                class: normalizeClass(["cdx-typeahead-search__menu-message", _ctx.menuMessageClass])
              }, [
                createElementVNode("span", _hoisted_2, [
                  renderSlot(_ctx.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": withCtx(() => [
              createElementVNode("div", {
                class: normalizeClass(["cdx-typeahead-search__menu-message", _ctx.menuMessageClass])
              }, [
                createElementVNode("span", _hoisted_3, [
                  renderSlot(_ctx.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: withCtx(({ menuItem, active }) => [
              menuItem.value === _ctx.MenuFooterValue ? (openBlock(), createElementBlock("a", {
                key: 0,
                class: normalizeClass(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": active
                }]),
                href: _ctx.asSearchResult(menuItem).url,
                onClickCapture: withModifiers(($event) => _ctx.onSearchFooterClick(_ctx.asSearchResult(menuItem)), ["stop"])
              }, [
                createVNode(_component_cdx_icon, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: _ctx.articleIcon
                }, null, 8, ["icon"]),
                createElementVNode("span", _hoisted_5, [
                  renderSlot(_ctx.$slots, "search-footer-text", { searchQuery: _ctx.searchQuery }, () => [
                    createElementVNode("strong", _hoisted_6, toDisplayString(_ctx.searchQuery), 1)
                  ])
                ])
              ], 42, _hoisted_4)) : createCommentVNode("", true)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      renderSlot(_ctx.$slots, "default")
    ], 40, _hoisted_1)
  ], 6);
}
var TypeaheadSearch = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render]]);
export { TypeaheadSearch as CdxTypeaheadSearch };
