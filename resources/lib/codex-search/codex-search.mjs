var Ke = Object.defineProperty, Ae = Object.defineProperties;
var Re = Object.getOwnPropertyDescriptors;
var ie = Object.getOwnPropertySymbols;
var _e = Object.prototype.hasOwnProperty, Se = Object.prototype.propertyIsEnumerable;
var Ie = (e, t, n) => t in e ? Ke(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, we = (e, t) => {
  for (var n in t || (t = {}))
    _e.call(t, n) && Ie(e, n, t[n]);
  if (ie)
    for (var n of ie(t))
      Se.call(t, n) && Ie(e, n, t[n]);
  return e;
}, xe = (e, t) => Ae(e, Re(t));
var Y = (e, t) => {
  var n = {};
  for (var a in e)
    _e.call(e, a) && t.indexOf(a) < 0 && (n[a] = e[a]);
  if (e != null && ie)
    for (var a of ie(e))
      t.indexOf(a) < 0 && Se.call(e, a) && (n[a] = e[a]);
  return n;
};
var pe = (e, t, n) => new Promise((a, o) => {
  var u = (l) => {
    try {
      i(n.next(l));
    } catch (r) {
      o(r);
    }
  }, s = (l) => {
    try {
      i(n.throw(l));
    } catch (r) {
      o(r);
    }
  }, i = (l) => l.done ? a(l.value) : Promise.resolve(l.value).then(u, s);
  i((n = n.apply(e, t)).next());
});
import { ref as y, onMounted as W, defineComponent as D, computed as h, openBlock as f, createElementBlock as v, normalizeClass as K, toDisplayString as V, createCommentVNode as w, resolveComponent as B, createVNode as j, Transition as De, withCtx as q, normalizeStyle as te, createElementVNode as I, createTextVNode as ee, withModifiers as Ce, renderSlot as R, createBlock as L, resolveDynamicComponent as Ee, Fragment as ve, getCurrentInstance as Ne, onUnmounted as ke, watch as Z, toRef as z, nextTick as ue, withDirectives as Me, mergeProps as P, renderList as Fe, vShow as Oe, Comment as qe, warn as ze, withKeys as ye, inject as re, vModelDynamic as He } from "vue";
const Ue = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', Qe = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', je = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Pe = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', We = Ue, Ge = Qe, Je = je, Xe = Pe;
function Ye(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const a = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof a == "string" || "path" in a ? a : a.ltr;
}
function Ze(e, t) {
  if (typeof e == "string")
    return !1;
  if ("langCodeMap" in e) {
    const n = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
    if (typeof n == "string")
      return !1;
    e = n;
  }
  if ("shouldFlipExceptions" in e && Array.isArray(e.shouldFlipExceptions)) {
    const n = e.shouldFlipExceptions.indexOf(t);
    return n === void 0 || n === -1;
  }
  return "shouldFlip" in e ? e.shouldFlip : !1;
}
function et(e) {
  const t = y(null);
  return W(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function tt(e) {
  const t = y("");
  return W(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
function U(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const me = "cdx", nt = [
  "default",
  "progressive",
  "destructive"
], at = [
  "normal",
  "primary",
  "quiet"
], lt = [
  "medium",
  "large"
], ot = [
  "x-small",
  "small",
  "medium"
], st = [
  "text",
  "search",
  "number",
  "email",
  "month",
  "password",
  "tel",
  "url",
  "week",
  "date",
  "datetime-local",
  "time"
], Te = [
  "default",
  "error"
], it = 120, ut = 500, Q = "cdx-menu-footer-item", rt = Symbol("CdxId"), dt = Symbol("CdxDescriptionId"), ct = Symbol("CdxStatus"), ht = Symbol("CdxDisabled"), ft = U(ot), pt = D({
  name: "CdxIcon",
  props: {
    /** The SVG path or an object containing that path plus other data. */
    icon: {
      type: [String, Object],
      required: !0
    },
    /**
     * Accessible label for the icon. If not included, the icon will be hidden from screen
     * readers via `aria-hidden="true"`. Browsers also display this label as a tooltip when the
     * user hovers over the icon. Note that this label is not rendered as visible text next
     * to the icon.
     */
    iconLabel: {
      type: String,
      default: ""
    },
    /**
     * Explicitly set the language code to use for the icon. See
     * https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/lang.
     * Defaults to the lang attribute of the nearest ancestor at mount time.
     */
    lang: {
      type: String,
      default: null
    },
    /**
     * Explicitly set the direction to use for the icon. See
     * https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/dir.
     * Defaults to the computed direction at mount time.
     */
    dir: {
      type: String,
      default: null
    },
    /**
     * Specify icon size by choosing one of several pre-defined size
     * options. See the type documentation for supported size options.
     * The `medium` size is used by default if no size prop is provided.
     */
    size: {
      type: String,
      default: "medium",
      validator: ft
    }
  },
  setup(e) {
    const t = y(), n = et(t), a = tt(t), o = h(() => e.dir || n.value), u = h(() => e.lang || a.value), s = h(() => ({
      "cdx-icon--flipped": o.value === "rtl" && u.value !== null && Ze(e.icon, u.value),
      [`cdx-icon--${e.size}`]: !0
    })), i = h(
      () => Ye(e.icon, u.value || "", o.value || "ltr")
    ), l = h(() => typeof i.value == "string" ? i.value : ""), r = h(() => typeof i.value != "string" ? i.value.path : "");
    return {
      rootElement: t,
      rootClasses: s,
      iconSvg: l,
      iconPath: r
    };
  }
});
const E = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [a, o] of t)
    n[a] = o;
  return n;
}, mt = ["aria-hidden"], gt = { key: 0 }, vt = ["innerHTML"], yt = ["d"];
function bt(e, t, n, a, o, u) {
  return f(), v("span", {
    ref: "rootElement",
    class: K(["cdx-icon", e.rootClasses])
  }, [
    (f(), v("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      "xmlns:xlink": "http://www.w3.org/1999/xlink",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": e.iconLabel ? void 0 : !0
    }, [
      e.iconLabel ? (f(), v("title", gt, V(e.iconLabel), 1)) : w("", !0),
      e.iconSvg ? (f(), v("g", {
        key: 1,
        innerHTML: e.iconSvg
      }, null, 8, vt)) : (f(), v("path", {
        key: 2,
        d: e.iconPath
      }, null, 8, yt))
    ], 8, mt))
  ], 2);
}
const ne = /* @__PURE__ */ E(pt, [["render", bt]]), Ct = D({
  name: "CdxThumbnail",
  components: { CdxIcon: ne },
  props: {
    /**
     * Thumbnail data.
     */
    thumbnail: {
      type: [Object, null],
      default: null
    },
    /**
     * Thumbnail placeholder icon.
     */
    placeholderIcon: {
      type: [String, Object],
      default: Je
    }
  },
  setup: (e) => {
    const t = y(!1), n = y({}), a = (o) => {
      const u = o.replace(/([\\"\n])/g, "\\$1"), s = new Image();
      s.onload = () => {
        n.value = { backgroundImage: `url("${u}")` }, t.value = !0;
      }, s.onerror = () => {
        t.value = !1;
      }, s.src = u;
    };
    return W(() => {
      var o;
      (o = e.thumbnail) != null && o.url && a(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const $t = { class: "cdx-thumbnail" }, It = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function _t(e, t, n, a, o, u) {
  const s = B("cdx-icon");
  return f(), v("span", $t, [
    e.thumbnailLoaded ? w("", !0) : (f(), v("span", It, [
      j(s, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon--vue"
      }, null, 8, ["icon"])
    ])),
    j(De, { name: "cdx-thumbnail__image" }, {
      default: q(() => [
        e.thumbnailLoaded ? (f(), v("span", {
          key: 0,
          style: te(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : w("", !0)
      ]),
      _: 1
    })
  ]);
}
const St = /* @__PURE__ */ E(Ct, [["render", _t]]);
function wt(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const xt = "[̀-ͯ҃-҉֑-ׇֽֿׁׂׅׄؐ-ًؚ-ٰٟۖ-ۜ۟-۪ۤۧۨ-ܑۭܰ-݊ަ-ް߫-߽߳ࠖ-࠙ࠛ-ࠣࠥ-ࠧࠩ-࡙࠭-࡛࣓-ࣣ࣡-ःऺ-़ा-ॏ॑-ॗॢॣঁ-ঃ়া-ৄেৈো-্ৗৢৣ৾ਁ-ਃ਼ਾ-ੂੇੈੋ-੍ੑੰੱੵઁ-ઃ઼ા-ૅે-ૉો-્ૢૣૺ-૿ଁ-ଃ଼ା-ୄେୈୋ-୍ୖୗୢୣஂா-ூெ-ைொ-்ௗఀ-ఄా-ౄె-ైొ-్ౕౖౢౣಁ-ಃ಼ಾ-ೄೆ-ೈೊ-್ೕೖೢೣഀ-ഃ഻഼ാ-ൄെ-ൈൊ-്ൗൢൣංඃ්ා-ුූෘ-ෟෲෳัิ-ฺ็-๎ັິ-ູົຼ່-ໍ༹༘༙༵༷༾༿ཱ-྄྆྇ྍ-ྗྙ-ྼ࿆ါ-ှၖ-ၙၞ-ၠၢ-ၤၧ-ၭၱ-ၴႂ-ႍႏႚ-ႝ፝-፟ᜒ-᜔ᜲ-᜴ᝒᝓᝲᝳ឴-៓៝᠋-᠍ᢅᢆᢩᤠ-ᤫᤰ-᤻ᨗ-ᨛᩕ-ᩞ᩠-᩿᩼᪰-᪾ᬀ-ᬄ᬴-᭄᭫-᭳ᮀ-ᮂᮡ-ᮭ᯦-᯳ᰤ-᰷᳐-᳔᳒-᳨᳭ᳲ-᳴᳷-᳹᷀-᷹᷻-᷿⃐-⃰⳯-⵿⳱ⷠ-〪ⷿ-゙゚〯꙯-꙲ꙴ-꙽ꚞꚟ꛰꛱ꠂ꠆ꠋꠣ-ꠧꢀꢁꢴ-ꣅ꣠-꣱ꣿꤦ-꤭ꥇ-꥓ꦀ-ꦃ꦳-꧀ꧥꨩ-ꨶꩃꩌꩍꩻ-ꩽꪰꪲ-ꪴꪷꪸꪾ꪿꫁ꫫ-ꫯꫵ꫶ꯣ-ꯪ꯬꯭ﬞ︀-️︠-︯]";
function kt(e, t) {
  if (!e)
    return [t, "", ""];
  const n = wt(e), a = new RegExp(
    // Per https://www.regular-expressions.info/unicode.html, "any code point that is not a
    // combining mark can be followed by any number of combining marks." See also the
    // discussion in https://phabricator.wikimedia.org/T35242.
    n + xt + "*",
    "i"
  ).exec(t);
  if (!a || a.index === void 0)
    return [t, "", ""];
  const o = a.index, u = o + a[0].length, s = t.slice(o, u), i = t.slice(0, o), l = t.slice(u, t.length);
  return [i, s, l];
}
const Mt = D({
  name: "CdxSearchResultTitle",
  props: {
    /**
     * Title text.
     */
    title: {
      type: String,
      required: !0
    },
    /**
     * The current search query.
     */
    searchQuery: {
      type: String,
      default: ""
    }
  },
  setup: (e) => ({
    titleChunks: h(() => kt(e.searchQuery, String(e.title)))
  })
});
const Tt = { class: "cdx-search-result-title" }, Vt = { class: "cdx-search-result-title__match" };
function Bt(e, t, n, a, o, u) {
  return f(), v("span", Tt, [
    I("bdi", null, [
      ee(V(e.titleChunks[0]), 1),
      I("span", Vt, V(e.titleChunks[1]), 1),
      ee(V(e.titleChunks[2]), 1)
    ])
  ]);
}
const Lt = /* @__PURE__ */ E(Mt, [["render", Bt]]), Kt = D({
  name: "CdxMenuItem",
  components: { CdxIcon: ne, CdxThumbnail: St, CdxSearchResultTitle: Lt },
  props: {
    /**
     * ID for HTML `id` attribute.
     */
    id: {
      type: String,
      required: !0
    },
    /**
     * The value provided to the parent menu component when this menu item is selected.
     */
    value: {
      type: [String, Number],
      required: !0
    },
    /**
     * Whether the menu item is disabled.
     */
    disabled: {
      type: Boolean,
      default: !1
    },
    /**
     * Whether this menu item is selected.
     */
    selected: {
      type: Boolean,
      default: !1
    },
    /**
     * Whether this menu item is being pressed.
     */
    active: {
      type: Boolean,
      default: !1
    },
    /**
     * Whether this menu item is visually highlighted due to hover or keyboard navigation.
     */
    highlighted: {
      type: Boolean,
      default: !1
    },
    /**
     * Label for the menu item. If this isn't provided, the value will be displayed instead.
     */
    label: {
      type: String,
      default: ""
    },
    /**
     * Text that matches current search query. Only used for search results and will be
     * displayed after the label.
     */
    match: {
      type: String,
      default: ""
    },
    /**
     * Text that supports the label. Supporting text will appear next to the label in a more
     * subtle color.
     */
    supportingText: {
      type: String,
      default: ""
    },
    /**
     * URL for the menu item. If provided, the content of the menu item will be wrapped in an
     * anchor tag.
     */
    url: {
      type: String,
      default: ""
    },
    /**
     * Icon for the menu item.
     */
    icon: {
      type: [String, Object],
      default: ""
    },
    /**
     * Whether a thumbnail (or a placeholder icon) should be displayed.
     */
    showThumbnail: {
      type: Boolean,
      default: !1
    },
    /**
     * Thumbnail for the menu item.
     */
    thumbnail: {
      type: [Object, null],
      default: null
    },
    /**
     * Description of the menu item.
     */
    description: {
      type: [String, null],
      default: ""
    },
    /**
     * The search query to be highlighted within the menu item's title.
     */
    searchQuery: {
      type: String,
      default: ""
    },
    /**
     * Whether to bold menu item labels.
     */
    boldLabel: {
      type: Boolean,
      default: !1
    },
    /**
     * Whether to hide description text overflow via an ellipsis.
     */
    hideDescriptionOverflow: {
      type: Boolean,
      default: !1
    },
    /**
     * Optional language codes for label, match, supporting text, and description.
     *
     * If included, that language code will be added as a `lang` attribute to the element
     * wrapping that text node.
     */
    language: {
      type: Object,
      default: () => ({})
    }
  },
  emits: [
    /**
     * Emitted when the menu item becomes selected, active or highlighted in response to
     * user interaction. Handled in the Menu component.
     *
     * @property {MenuState} menuState State to change
     * @property {boolean} setState Whether to set that state to this menu item
     */
    "change"
  ],
  setup: (e, { emit: t }) => {
    const n = () => {
      e.highlighted || t("change", "highlighted", !0);
    }, a = () => {
      t("change", "highlighted", !1);
    }, o = (m) => {
      m.button === 0 && t("change", "active", !0);
    }, u = () => {
      t("change", "selected", !0);
    }, s = h(() => e.searchQuery.length > 0), i = h(() => ({
      "cdx-menu-item--selected": e.selected,
      // Only show the active visual state when the menu item is both active and
      // highlighted. This means, on mousedown -> mouseleave, the menu item is still
      // technically tracked by the menu as active, but will not appear active to the
      // user. This also means in the case of mousedown -> mouseleave -> mouseenter, the
      // menu item will appear active again, and on click (releasing the mouse button),
      // the item will be selected.
      "cdx-menu-item--active": e.active && e.highlighted,
      "cdx-menu-item--highlighted": e.highlighted,
      "cdx-menu-item--enabled": !e.disabled,
      "cdx-menu-item--disabled": e.disabled,
      "cdx-menu-item--highlight-query": s.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), l = h(() => e.url ? "a" : "span"), r = h(() => e.label || String(e.value));
    return {
      onMouseMove: n,
      onMouseLeave: a,
      onMouseDown: o,
      onClick: u,
      highlightQuery: s,
      rootClasses: i,
      contentTag: l,
      title: r
    };
  }
});
const At = ["id", "aria-disabled", "aria-selected"], Rt = { class: "cdx-menu-item__text" }, Dt = ["lang"], Et = ["lang"], Nt = ["lang"], Ft = ["lang"];
function Ot(e, t, n, a, o, u) {
  const s = B("cdx-thumbnail"), i = B("cdx-icon"), l = B("cdx-search-result-title");
  return f(), v("li", {
    id: e.id,
    role: "option",
    class: K(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMousemove: t[0] || (t[0] = (...r) => e.onMouseMove && e.onMouseMove(...r)),
    onMouseleave: t[1] || (t[1] = (...r) => e.onMouseLeave && e.onMouseLeave(...r)),
    onMousedown: t[2] || (t[2] = Ce((...r) => e.onMouseDown && e.onMouseDown(...r), ["prevent"])),
    onClick: t[3] || (t[3] = (...r) => e.onClick && e.onClick(...r))
  }, [
    R(e.$slots, "default", {}, () => [
      (f(), L(Ee(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: q(() => {
          var r, m, $, b, x, k;
          return [
            e.showThumbnail ? (f(), L(s, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (f(), L(i, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : w("", !0),
            I("span", Rt, [
              e.highlightQuery ? (f(), L(l, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (r = e.language) == null ? void 0 : r.label
              }, null, 8, ["title", "search-query", "lang"])) : (f(), v("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (m = e.language) == null ? void 0 : m.label
              }, [
                I("bdi", null, V(e.title), 1)
              ], 8, Dt)),
              e.match ? (f(), v(ve, { key: 2 }, [
                ee(V(" ") + " "),
                e.highlightQuery ? (f(), L(l, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: ($ = e.language) == null ? void 0 : $.match
                }, null, 8, ["title", "search-query", "lang"])) : (f(), v("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (b = e.language) == null ? void 0 : b.match
                }, [
                  I("bdi", null, V(e.match), 1)
                ], 8, Et))
              ], 64)) : w("", !0),
              e.supportingText ? (f(), v(ve, { key: 3 }, [
                ee(V(" ") + " "),
                I("span", {
                  class: "cdx-menu-item__text__supporting-text",
                  lang: (x = e.language) == null ? void 0 : x.supportingText
                }, [
                  I("bdi", null, V(e.supportingText), 1)
                ], 8, Nt)
              ], 64)) : w("", !0),
              e.description ? (f(), v("span", {
                key: 4,
                class: "cdx-menu-item__text__description",
                lang: (k = e.language) == null ? void 0 : k.description
              }, [
                I("bdi", null, V(e.description), 1)
              ], 8, Ft)) : w("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, At);
}
const qt = /* @__PURE__ */ E(Kt, [["render", Ot]]), zt = D({
  name: "CdxProgressBar",
  props: {
    /**
     * Whether this is the smaller, inline variant.
     */
    inline: {
      type: Boolean,
      default: !1
    },
    /**
     * Whether the progress bar is disabled.
     */
    disabled: {
      type: Boolean,
      default: !1
    }
  },
  setup(e) {
    return {
      rootClasses: h(() => ({
        "cdx-progress-bar--block": !e.inline,
        "cdx-progress-bar--inline": e.inline,
        "cdx-progress-bar--enabled": !e.disabled,
        "cdx-progress-bar--disabled": e.disabled
      }))
    };
  }
});
const Ht = ["aria-disabled"], Ut = /* @__PURE__ */ I("div", { class: "cdx-progress-bar__bar" }, null, -1), Qt = [
  Ut
];
function jt(e, t, n, a, o, u) {
  return f(), v("div", {
    class: K(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Qt, 10, Ht);
}
const Pt = /* @__PURE__ */ E(zt, [["render", jt]]);
let ge = 0;
function Ve(e) {
  const t = Ne(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${me}-${e}-${ge++}` : n ? `${me}-${n}-${ge++}` : `${me}-${ge++}`;
}
function Wt(e, t) {
  const n = y(!1);
  let a = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const o = new window.IntersectionObserver(
    (u) => {
      const s = u[0];
      s && (n.value = s.isIntersecting);
    },
    t
  );
  return W(() => {
    a = !0, e.value && o.observe(e.value);
  }), ke(() => {
    a = !1, o.disconnect();
  }), Z(e, (u) => {
    a && (o.disconnect(), n.value = !1, u && o.observe(u));
  }), n;
}
function de(e, t = h(() => ({}))) {
  const n = h(() => {
    const u = Y(t.value, []);
    return e.class && e.class.split(" ").forEach((i) => {
      u[i] = !0;
    }), u;
  }), a = h(() => {
    if ("style" in e)
      return e.style;
  }), o = h(() => {
    const l = e, { class: u, style: s } = l;
    return Y(l, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: a,
    otherAttrs: o
  };
}
const Gt = D({
  name: "CdxMenu",
  components: {
    CdxMenuItem: qt,
    CdxProgressBar: Pt
  },
  /**
   * Attributes, besides class and style, will be passed to the <ul> element.
   */
  inheritAttrs: !1,
  props: {
    /** Menu items. See the MenuItemData type. */
    menuItems: {
      type: Array,
      required: !0
    },
    /**
     * Interactive footer item.
     *
     * This is a special menu item which is pinned to the bottom of the menu. When scrolling is
     * enabled within the menu, the footer item will always be visible at the bottom of the
     * menu. When scrolling is not enabled, the footer item will simply appear as the last menu
     * item.
     *
     * The footer item is selectable, like other menu items.
     */
    footer: {
      type: Object,
      default: null
    },
    /**
     * Value of the selected menu item, or undefined if no item is selected.
     *
     * Must be bound with `v-model:selected`.
     *
     * The property should be initialized to `null` rather than using a falsy value.
     */
    selected: {
      type: [String, Number, null],
      required: !0
    },
    /**
     * Whether the menu is expanded. Must be bound with `v-model:expanded`.
     */
    expanded: {
      type: Boolean,
      required: !0
    },
    /**
     * Whether to display pending state indicators. Meant to indicate that new menu items are
     * being fetched or computed.
     *
     * When true, the menu will expand if not already expanded, and an inline progress bar will
     * display. If there are no menu items yet, a message can be displayed in the `pending`
     * slot, e.g. "Loading results".
     */
    showPending: {
      type: Boolean,
      default: !1
    },
    /**
     * Limit the number of menu items to display before scrolling.
     *
     * Setting this prop to anything falsy will show all menu items.
     *
     * By default, all menu items are shown.
     */
    visibleItemLimit: {
      type: Number,
      default: null
    },
    /**
     * Whether menu item thumbnails (or a placeholder icon) should be displayed.
     */
    showThumbnail: {
      type: Boolean,
      default: !1
    },
    /**
     * Whether to bold menu item labels.
     */
    boldLabel: {
      type: Boolean,
      default: !1
    },
    /**
     * Whether to hide description text overflow via an ellipsis.
     */
    hideDescriptionOverflow: {
      type: Boolean,
      default: !1
    },
    /**
     * The search query to be highlighted within the menu items' titles.
     */
    searchQuery: {
      type: String,
      default: ""
    },
    /**
     * Whether to show the `no-results` slot content.
     *
     * The Menu component automatically shows this slot when there is content in the
     * `no-results` slot and there are zero menu items. However, some components may need to
     * customize this behavior, e.g. to show the slot even when there is at least one menu item.
     * This prop can be used to override the default Menu behavior.
     *
     * Possible values:
     * `null` (default): the `no-results` slot will display only if there are zero menu items.
     * `true`: the `no-results` slot will display, regardless of number of menu items.
     * `false`: the `no-results` slot will not display, regardless of number of menu items.
     */
    showNoResultsSlot: {
      type: Boolean,
      default: null
    }
  },
  emits: [
    // Don't remove the spaces in the "string | number | null" type below; removing these
    // spaces causes the documentation to render the type as "union" instead.
    /**
     * When the selected menu item changes.
     *
     * @property {string | number | null} selectedValue The `.value` property of the
     * selected menu item, or null if no item is selected.
     */
    "update:selected",
    /**
     * When the menu opens or closes.
     *
     * @property {boolean} newValue The new expanded state (true for open, false for closed)
     */
    "update:expanded",
    /**
     * When a menu item is clicked.
     *
     * Typically, components with menus will respond to the selected value change, but
     * occasionally, a component might want to react specifically when a menu item is clicked.
     *
     * @property {MenuItemDataWithId} menuItem The menu item that was clicked
     */
    "menu-item-click",
    /**
     * When a menu item is highlighted via keyboard navigation.
     *
     * @property {MenuItemDataWithId} highlightedMenuItem The menu item
     * was highlighted
     */
    "menu-item-keyboard-navigation",
    /**
     * When the user scrolls towards the bottom of the menu.
     *
     * If it is possible to add or load more menu items, then now would be a good moment
     * so that the user can experience infinite scrolling.
     */
    "load-more"
  ],
  expose: [
    "clearActive",
    "getHighlightedMenuItem",
    "getHighlightedViaKeyboard",
    "delegateKeyNavigation"
  ],
  setup(e, { emit: t, slots: n, attrs: a }) {
    const o = h(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((p) => xe(we({}, p), {
      id: Ve("menu-item")
    }))), u = h(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : o.value.length === 0 : !1), s = y(null), i = y(!1), l = y(null);
    function r() {
      return o.value.find(
        (d) => d.value === e.selected
      );
    }
    function m(d, p) {
      var C;
      if (!(p && p.disabled))
        switch (d) {
          case "selected":
            t("update:selected", (C = p == null ? void 0 : p.value) != null ? C : null), t("update:expanded", !1), l.value = null;
            break;
          case "highlighted":
            s.value = p || null, i.value = !1;
            break;
          case "highlightedViaKeyboard":
            s.value = p || null, i.value = !0;
            break;
          case "active":
            l.value = p || null;
            break;
        }
    }
    const $ = h(() => {
      if (s.value !== null)
        return o.value.findIndex(
          (d) => (
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            d.value === s.value.value
          )
        );
    });
    function b(d) {
      d && (m("highlightedViaKeyboard", d), t("menu-item-keyboard-navigation", d));
    }
    function x(d) {
      var _;
      const p = (X) => {
        for (let c = X - 1; c >= 0; c--)
          if (!o.value[c].disabled)
            return o.value[c];
      };
      d = d || o.value.length;
      const C = (_ = p(d)) != null ? _ : p(o.value.length);
      b(C);
    }
    function k(d) {
      const p = (_) => o.value.find((X, c) => !X.disabled && c > _);
      d = d != null ? d : -1;
      const C = p(d) || p(-1);
      b(C);
    }
    function G(d, p = !0) {
      function C() {
        t("update:expanded", !0), m("highlighted", r());
      }
      function _() {
        p && (d.preventDefault(), d.stopPropagation());
      }
      switch (d.key) {
        case "Enter":
        case " ":
          return _(), e.expanded ? (s.value && i.value && t("update:selected", s.value.value), t("update:expanded", !1)) : C(), !0;
        case "Tab":
          return e.expanded && (s.value && i.value && t("update:selected", s.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return _(), e.expanded ? (s.value === null && m("highlightedViaKeyboard", r()), x($.value)) : C(), N(), !0;
        case "ArrowDown":
          return _(), e.expanded ? (s.value === null && m("highlightedViaKeyboard", r()), k($.value)) : C(), N(), !0;
        case "Home":
          return _(), e.expanded ? (s.value === null && m("highlightedViaKeyboard", r()), k()) : C(), N(), !0;
        case "End":
          return _(), e.expanded ? (s.value === null && m("highlightedViaKeyboard", r()), x()) : C(), N(), !0;
        case "Escape":
          return _(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function M() {
      m("active");
    }
    const T = [], J = y(void 0), ae = Wt(
      J,
      { threshold: 0.8 }
    );
    Z(ae, (d) => {
      d && t("load-more");
    });
    function le(d, p) {
      if (d) {
        T[p] = d.$el;
        const C = e.visibleItemLimit;
        if (!C || e.menuItems.length < C)
          return;
        const _ = Math.min(
          C,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        p === e.menuItems.length - _ && (J.value = d.$el);
      }
    }
    function N() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || $.value === void 0)
        return;
      const d = $.value >= 0 ? $.value : 0;
      T[d].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const g = y(null), H = y(null);
    function A() {
      if (H.value = null, !e.visibleItemLimit || T.length <= e.visibleItemLimit) {
        g.value = null;
        return;
      }
      const d = T[0], p = T[e.visibleItemLimit];
      if (g.value = F(
        d,
        p
      ), e.footer) {
        const C = T[T.length - 1];
        H.value = C.scrollHeight;
      }
    }
    function F(d, p) {
      const C = d.getBoundingClientRect().top;
      return p.getBoundingClientRect().top - C + 2;
    }
    W(() => {
      document.addEventListener("mouseup", M);
    }), ke(() => {
      document.removeEventListener("mouseup", M);
    }), Z(z(e, "expanded"), (d) => pe(this, null, function* () {
      const p = r();
      !d && s.value && p === void 0 && m("highlighted"), d && p !== void 0 && m("highlighted", p), d && (yield ue(), A(), yield ue(), N());
    })), Z(z(e, "menuItems"), (d) => pe(this, null, function* () {
      d.length < T.length && (T.length = d.length), e.expanded && (yield ue(), A(), yield ue(), N());
    }), { deep: !0 });
    const oe = h(() => ({
      "max-height": g.value ? `${g.value}px` : void 0,
      "overflow-y": g.value ? "scroll" : void 0,
      "margin-bottom": H.value ? `${H.value}px` : void 0
    })), ce = h(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!g.value
    })), {
      rootClasses: he,
      rootStyle: fe,
      otherAttrs: se
    } = de(a, ce);
    return {
      listBoxStyle: oe,
      rootClasses: he,
      rootStyle: fe,
      otherAttrs: se,
      assignTemplateRef: le,
      computedMenuItems: o,
      computedShowNoResultsSlot: u,
      highlightedMenuItem: s,
      highlightedViaKeyboard: i,
      activeMenuItem: l,
      handleMenuItemChange: m,
      handleKeyNavigation: G
    };
  },
  // Public methods
  // These must be in the methods block, not in the setup function, otherwise their documentation
  // won't be picked up by vue-docgen
  methods: {
    /**
     * Get the highlighted menu item, if any.
     *
     * @public
     * @return {MenuItemDataWithId|null} The highlighted menu item,
     *   or null if no item is highlighted.
     */
    getHighlightedMenuItem() {
      return this.highlightedMenuItem;
    },
    /**
     * Get whether the last highlighted item was highlighted via the keyboard.
     *
     * @public
     * @return {boolean} Whether the last highlighted menu item was highlighted via keyboard.
     */
    getHighlightedViaKeyboard() {
      return this.highlightedViaKeyboard;
    },
    /**
     * Ensure no menu item is active. This unsets the active item if there is one.
     *
     * @public
     */
    clearActive() {
      this.handleMenuItemChange("active");
    },
    /**
     * Handles all necessary keyboard navigation.
     *
     * The parent component should listen for keydown events on its focusable element,
     * and pass those events to this method. Events for arrow keys, tab and enter are handled
     * by this method. If a different key was pressed, this method will return false to indicate
     * that it didn't handle the event.
     *
     * @public
     * @param event {KeyboardEvent} Keydown event object
     * @param prevent {boolean} If false, do not call e.preventDefault() or e.stopPropagation()
     * @return Whether the event was handled
     */
    delegateKeyNavigation(e, t = !0) {
      return this.handleKeyNavigation(e, t);
    }
  }
});
const Jt = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Xt = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function Yt(e, t, n, a, o, u) {
  const s = B("cdx-menu-item"), i = B("cdx-progress-bar");
  return Me((f(), v("div", {
    class: K(["cdx-menu", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    I("ul", P({
      class: "cdx-menu__listbox",
      role: "listbox",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (f(), v("li", Jt, [
        R(e.$slots, "pending")
      ])) : w("", !0),
      e.computedShowNoResultsSlot ? (f(), v("li", Xt, [
        R(e.$slots, "no-results")
      ])) : w("", !0),
      (f(!0), v(ve, null, Fe(e.computedMenuItems, (l, r) => {
        var m, $;
        return f(), L(s, P({
          key: l.value,
          ref_for: !0,
          ref: (b) => e.assignTemplateRef(b, r)
        }, l, {
          selected: l.value === e.selected,
          active: l.value === ((m = e.activeMenuItem) == null ? void 0 : m.value),
          highlighted: l.value === (($ = e.highlightedMenuItem) == null ? void 0 : $.value),
          "show-thumbnail": e.showThumbnail,
          "bold-label": e.boldLabel,
          "hide-description-overflow": e.hideDescriptionOverflow,
          "search-query": e.searchQuery,
          onChange: (b, x) => e.handleMenuItemChange(b, x && l),
          onClick: (b) => e.$emit("menu-item-click", l)
        }), {
          default: q(() => {
            var b, x;
            return [
              R(e.$slots, "default", {
                menuItem: l,
                active: l.value === ((b = e.activeMenuItem) == null ? void 0 : b.value) && l.value === ((x = e.highlightedMenuItem) == null ? void 0 : x.value)
              })
            ];
          }),
          _: 2
        }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
      }), 128)),
      e.showPending ? (f(), L(i, {
        key: 2,
        class: "cdx-menu__progress-bar",
        inline: !0
      })) : w("", !0)
    ], 16)
  ], 6)), [
    [Oe, e.expanded]
  ]);
}
const Zt = /* @__PURE__ */ E(Gt, [["render", Yt]]), en = U(nt), tn = U(at), nn = U(lt), an = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && ze(`CdxButton: Icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/demos/button.html#icon-only-button-1`);
};
function be(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...be(n)) : typeof n == "object" && n && (// HTML tag
    typeof n.type == "string" || // Component
    typeof n.type == "object" ? t.push(n) : n.type !== qe && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...be(n.children))));
  return t;
}
const ln = (e, t) => {
  if (!e)
    return !1;
  const n = be(e);
  if (n.length !== 1)
    return !1;
  const a = n[0], o = typeof a == "object" && typeof a.type == "object" && "name" in a.type && a.type.name === ne.name, u = typeof a == "object" && a.type === "svg";
  return o || u ? (an(t), !0) : !1;
}, on = D({
  name: "CdxButton",
  props: {
    /**
     * The kind of action that will be taken on click.
     *
     * @values 'default', 'progressive', 'destructive'
     */
    action: {
      type: String,
      default: "default",
      validator: en
    },
    /**
     * Visual prominence of the button.
     *
     * @values 'normal', 'primary', 'quiet'
     */
    weight: {
      type: String,
      default: "normal",
      validator: tn
    },
    /**
     * Button size.
     *
     * Most buttons should use the default medium size. In rare cases the large size should
     * be used, for example to make icon-only buttons larger on touchscreens.
     *
     * @values 'medium', 'large'
     */
    size: {
      type: String,
      default: "medium",
      validator: nn
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: a }) {
    const o = y(!1);
    return {
      rootClasses: h(() => {
        var l;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--weight-${e.weight}`]: !0,
          [`cdx-button--size-${e.size}`]: !0,
          "cdx-button--framed": e.weight !== "quiet",
          "cdx-button--icon-only": ln((l = n.default) == null ? void 0 : l.call(n), a),
          "cdx-button--is-active": o.value
        };
      }),
      onClick: (l) => {
        t("click", l);
      },
      setActive: (l) => {
        o.value = l;
      }
    };
  }
});
function sn(e, t, n, a, o, u) {
  return f(), v("button", {
    class: K(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...s) => e.onClick && e.onClick(...s)),
    onKeydown: t[1] || (t[1] = ye((s) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = ye((s) => e.setActive(!1), ["space", "enter"]))
  }, [
    R(e.$slots, "default")
  ], 34);
}
const un = /* @__PURE__ */ E(on, [["render", sn]]);
function Be(e, t, n) {
  return h({
    get: () => e.value,
    set: (a) => (
      // If eventName is undefined, then 'update:modelValue' must be a valid EventName,
      // but TypeScript's type analysis isn't clever enough to realize that
      t(n || "update:modelValue", a)
    )
  });
}
function rn(e) {
  const t = re(ht, y(!1));
  return h(() => t.value || e.value);
}
function Le(e, t, n) {
  const a = rn(e), o = re(ct, y("default")), u = h(() => t != null && t.value && t.value !== "default" ? t.value : o.value), s = re(rt, void 0), i = h(() => s || n);
  return {
    computedDisabled: a,
    computedStatus: u,
    computedInputId: i
  };
}
const dn = U(st), cn = U(Te), hn = D({
  name: "CdxTextInput",
  components: { CdxIcon: ne },
  /**
   * We want the input to inherit attributes, not the root element.
   */
  inheritAttrs: !1,
  expose: [
    "focus",
    "blur"
  ],
  props: {
    /**
     * Current value of the input.
     *
     * Provided by `v-model` binding in the parent component.
     */
    modelValue: {
      type: [String, Number],
      default: ""
    },
    /**
     * `type` attribute of the input.
     *
     * @values 'text', 'search', 'number', 'email', 'password', 'tel', 'url',
     * 'week', 'month', 'date', 'datetime-local', 'time'
     */
    inputType: {
      type: String,
      default: "text",
      validator: dn
    },
    /**
     * `status` attribute of the input.
     *
     * @values 'default', 'error'
     */
    status: {
      type: String,
      default: "default",
      validator: cn
    },
    /**
     * Whether the input is disabled.
     */
    disabled: {
      type: Boolean,
      default: !1
    },
    /**
     * An icon at the start of the input element. Similar to a `::before` pseudo-element.
     */
    startIcon: {
      type: [String, Object],
      default: void 0
    },
    /**
     * An icon at the end of the input element. Similar to an `::after` pseudo-element.
     */
    endIcon: {
      type: [String, Object],
      default: void 0
    },
    /**
     * Add a clear button at the end of the input element.
     *
     * When the clear button is pressed, the input's value is set to an empty string.
     * The clear button is displayed when input text is present.
     */
    clearable: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    /**
     * When the input value changes
     *
     * @property {string | number} modelValue The new model value
     */
    "update:modelValue",
    /**
     * When the user presses a key.
     *
     * This event is not emitted when the user presses the Home or End key (T314728),
     * but is emitted for Ctrl/Cmd+Home and Ctrl/Cmd+End.
     *
     * @property {KeyboardEvent}
     */
    "keydown",
    /**
     * When the input value changes via direct use of the input
     *
     * @property {InputEvent} event
     */
    "input",
    /**
     * When an input value change is committed by the user (e.g. on blur)
     *
     * @property {Event} event
     */
    "change",
    /**
     * When the input comes into focus
     *
     * @property {FocusEvent} event
     */
    "focus",
    /**
     * When the input loses focus
     *
     * @property {FocusEvent} event
     */
    "blur",
    /**
     * When the input value is cleared through the use of the clear button
     *
     * @property {MouseEvent} event
     */
    "clear"
  ],
  setup(e, { emit: t, attrs: n }) {
    const a = n.id, {
      computedDisabled: o,
      computedStatus: u,
      computedInputId: s
    } = Le(
      z(e, "disabled"),
      z(e, "status"),
      a
    ), i = re(dt, void 0), l = Be(z(e, "modelValue"), t), r = h(() => e.clearable && !!l.value && !o.value), m = h(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": r.value,
      [`cdx-text-input--status-${u.value}`]: !0
    })), {
      rootClasses: $,
      rootStyle: b,
      otherAttrs: x
    } = de(n, m), k = h(() => {
      const A = x.value, { id: g } = A;
      return Y(A, ["id"]);
    }), G = h(() => ({
      "cdx-text-input__input--has-value": !!l.value
    }));
    return {
      computedInputId: s,
      descriptionId: i,
      wrappedModel: l,
      isClearable: r,
      rootClasses: $,
      rootStyle: b,
      otherAttrsMinusId: k,
      inputClasses: G,
      computedDisabled: o,
      onClear: (g) => {
        l.value = "", t("clear", g);
      },
      onInput: (g) => {
        t("input", g);
      },
      onChange: (g) => {
        t("change", g);
      },
      onKeydown: (g) => {
        (g.key === "Home" || g.key === "End") && !g.ctrlKey && !g.metaKey || t("keydown", g);
      },
      onFocus: (g) => {
        t("focus", g);
      },
      onBlur: (g) => {
        t("blur", g);
      },
      cdxIconClear: Ge
    };
  },
  // Public methods
  // These must be in the methods block, not in the setup function, otherwise their documentation
  // won't be picked up by vue-docgen
  methods: {
    /**
     * Focus the component's input element.
     *
     * @public
     */
    focus() {
      this.$refs.input.focus();
    },
    /**
     * Blur the component's input element.
     *
     * @public
     */
    blur() {
      this.$refs.input.blur();
    }
  }
});
const fn = ["id", "type", "aria-describedby", "disabled"];
function pn(e, t, n, a, o, u) {
  const s = B("cdx-icon");
  return f(), v("div", {
    class: K(["cdx-text-input", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    Me(I("input", P({
      id: e.computedInputId,
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (i) => e.wrappedModel = i),
      class: ["cdx-text-input__input", e.inputClasses]
    }, e.otherAttrsMinusId, {
      type: e.inputType,
      "aria-describedby": e.descriptionId,
      disabled: e.computedDisabled,
      onInput: t[1] || (t[1] = (...i) => e.onInput && e.onInput(...i)),
      onChange: t[2] || (t[2] = (...i) => e.onChange && e.onChange(...i)),
      onFocus: t[3] || (t[3] = (...i) => e.onFocus && e.onFocus(...i)),
      onBlur: t[4] || (t[4] = (...i) => e.onBlur && e.onBlur(...i)),
      onKeydown: t[5] || (t[5] = (...i) => e.onKeydown && e.onKeydown(...i))
    }), null, 16, fn), [
      [He, e.wrappedModel]
    ]),
    e.startIcon ? (f(), L(s, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.endIcon ? (f(), L(s, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.isClearable ? (f(), L(s, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon-vue cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = Ce(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : w("", !0)
  ], 6);
}
const mn = /* @__PURE__ */ E(hn, [["render", pn]]), gn = U(Te), vn = D({
  name: "CdxSearchInput",
  components: {
    CdxButton: un,
    CdxTextInput: mn
  },
  /**
   * Attributes, besides class, will be passed to the TextInput's input element.
   */
  inheritAttrs: !1,
  props: {
    /**
     * Value of the search input, provided by `v-model` binding in the parent component.
     */
    modelValue: {
      type: [String, Number],
      default: ""
    },
    /**
     * Submit button text.
     *
     * If this is provided, a submit button with this label will be added.
     */
    buttonLabel: {
      type: String,
      default: ""
    },
    /**
     * Whether the search input is disabled.
     */
    disabled: {
      type: Boolean,
      default: !1
    },
    /**
     * `status` property of the TextInput component
     *
     * @values 'default', 'error'
     */
    status: {
      type: String,
      default: "default",
      validator: gn
    }
  },
  emits: [
    /**
     * When the input value changes
     *
     * @property {string | number} value The new value
     */
    "update:modelValue",
    /**
     * When the submit button is clicked.
     *
     * @property {string | number} value The current input
     */
    "submit-click",
    /**
     * When the input value changes via direct use of the input
     *
     * @property {InputEvent} event
     */
    "input",
    /**
     * When an input value change is committed by the user (e.g. on blur)
     *
     * @property {Event} event
     */
    "change",
    /**
     * When the input comes into focus
     *
     * @property {FocusEvent} event
     */
    "focus",
    /**
     * When the input loses focus
     *
     * @property {FocusEvent} event
     */
    "blur"
  ],
  setup(e, { emit: t, attrs: n }) {
    const a = Be(z(e, "modelValue"), t), { computedDisabled: o } = Le(z(e, "disabled")), u = h(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: s,
      rootStyle: i,
      otherAttrs: l
    } = de(n, u);
    return {
      wrappedModel: a,
      computedDisabled: o,
      rootClasses: s,
      rootStyle: i,
      otherAttrs: l,
      handleSubmit: () => {
        t("submit-click", a.value);
      },
      searchIcon: Xe
    };
  },
  methods: {
    /**
     * Focus the component's input element.
     *
     * @public
     */
    focus() {
      this.$refs.textInput.focus();
    }
  }
});
const yn = { class: "cdx-search-input__input-wrapper" };
function bn(e, t, n, a, o, u) {
  const s = B("cdx-text-input"), i = B("cdx-button");
  return f(), v("div", {
    class: K(["cdx-search-input", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    I("div", yn, [
      j(s, P({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (l) => e.wrappedModel = l),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon,
        disabled: e.computedDisabled,
        status: e.status
      }, e.otherAttrs, {
        onKeydown: ye(e.handleSubmit, ["enter"]),
        onInput: t[1] || (t[1] = (l) => e.$emit("input", l)),
        onChange: t[2] || (t[2] = (l) => e.$emit("change", l)),
        onFocus: t[3] || (t[3] = (l) => e.$emit("focus", l)),
        onBlur: t[4] || (t[4] = (l) => e.$emit("blur", l))
      }), null, 16, ["modelValue", "start-icon", "disabled", "status", "onKeydown"]),
      R(e.$slots, "default")
    ]),
    e.buttonLabel ? (f(), L(i, {
      key: 0,
      class: "cdx-search-input__end-button",
      disabled: e.computedDisabled,
      onClick: e.handleSubmit
    }, {
      default: q(() => [
        ee(V(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["disabled", "onClick"])) : w("", !0)
  ], 6);
}
const Cn = /* @__PURE__ */ E(vn, [["render", bn]]), $n = D({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: ne,
    CdxMenu: Zt,
    CdxSearchInput: Cn
  },
  /**
   * Attributes, besides class, will be passed to the TextInput's input element.
   */
  inheritAttrs: !1,
  props: {
    /**
     * ID attribute for the form.
     */
    id: {
      type: String,
      required: !0
    },
    /**
     * Action attribute for form.
     */
    formAction: {
      type: String,
      required: !0
    },
    /**
     * Label attribute for the list of search results.
     */
    searchResultsLabel: {
      type: String,
      required: !0
    },
    /**
     * List of search results. See the SearchResult type.
     */
    searchResults: {
      type: Array,
      required: !0
    },
    /**
     * Label for the submit button.
     *
     * If no label is provided, the submit button will not be displayed.
     */
    buttonLabel: {
      type: String,
      default: ""
    },
    /**
     * Initial value for the text input.
     *
     * Triggers an initial `input` event on mount.
     */
    initialInputValue: {
      type: String,
      default: ""
    },
    /**
     * Link for the final menu item.
     *
     * This will typically be a link to the search page for the current search query.
     */
    searchFooterUrl: {
      type: String,
      default: ""
    },
    /**
     * Time interval for debouncing input events, in ms.
     */
    debounceInterval: {
      type: Number,
      default: it
    },
    /**
     * Whether the search query should be highlighted within a search result's title.
     */
    highlightQuery: {
      type: Boolean,
      default: !1
    },
    /**
     * Whether to show search results' thumbnails (or a placeholder icon).
     */
    showThumbnail: {
      type: Boolean,
      default: !1
    },
    /**
     * Contract the width of the input when unfocused and expand the width of
     * the input when focused to accommodate the extra width of the thumbnails.
     *
     * This prop is ignored if showThumbnail is false.
     */
    autoExpandWidth: {
      type: Boolean,
      default: !1
    },
    /**
     * Limit the number of menu items to display before scrolling.
     *
     * Setting this prop to anything falsy will show all menu items.
     *
     * By default, all menu items are shown.
     */
    visibleItemLimit: {
      type: Number,
      default: null
    }
  },
  emits: [
    /**
     * When the text input value changes. Debounced by default.
     *
     * @property {string} value The new input value
     */
    "input",
    /**
     * When a search result is selected.
     *
     * @property {SearchResultClickEvent} event Data for the selected result
     */
    "search-result-click",
    /**
     * When the form is submitted.
     *
     * @property {SearchResultClickEvent} event Data for the selected result
     */
    "submit",
    /**
     * When the user scrolls towards the bottom of the menu.
     *
     * If it is possible to add or load more menu items, then now would be a good moment
     * so that the user can experience infinite scrolling.
     */
    "load-more"
  ],
  setup(e, { attrs: t, emit: n, slots: a }) {
    const o = y(), u = y(), s = Ve("typeahead-search-menu"), i = y(!1), l = y(!1), r = y(!1), m = y(!1), $ = y(e.initialInputValue), b = y(""), x = h(() => {
      var c, S;
      return (S = (c = u.value) == null ? void 0 : c.getHighlightedMenuItem()) == null ? void 0 : S.id;
    }), k = y(null), G = h(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), M = h(
      () => e.searchResults.find(
        (c) => c.value === k.value
      )
    ), T = h(
      () => e.searchFooterUrl ? { value: Q, url: e.searchFooterUrl } : void 0
    ), J = h(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": i.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: ae,
      rootStyle: le,
      otherAttrs: N
    } = de(t, J);
    function g(c) {
      return c;
    }
    const H = h(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      // In case search queries aren't highlighted, default to a bold label.
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let A, F;
    function oe(c, S = !1) {
      M.value && M.value.label !== c && M.value.value !== c && (k.value = null), F !== void 0 && (clearTimeout(F), F = void 0), c === "" ? i.value = !1 : (l.value = !0, a["search-results-pending"] && (F = setTimeout(() => {
        m.value && (i.value = !0), r.value = !0;
      }, ut))), A !== void 0 && (clearTimeout(A), A = void 0);
      const O = () => {
        n("input", c);
      };
      S ? O() : A = setTimeout(() => {
        O();
      }, e.debounceInterval);
    }
    function ce(c) {
      if (c === Q) {
        k.value = null, $.value = b.value;
        return;
      }
      k.value = c, c !== null && ($.value = M.value ? M.value.label || String(M.value.value) : "");
    }
    function he() {
      m.value = !0, (b.value || r.value) && (i.value = !0);
    }
    function fe() {
      m.value = !1, i.value = !1;
    }
    function se(c) {
      const $e = c, { id: S } = $e, O = Y($e, ["id"]);
      if (O.value === Q) {
        n("search-result-click", {
          searchResult: null,
          index: e.searchResults.length,
          numberOfResults: e.searchResults.length
        });
        return;
      }
      d(O);
    }
    function d(c) {
      const S = {
        searchResult: c,
        index: e.searchResults.findIndex(
          (O) => O.value === c.value
        ),
        numberOfResults: e.searchResults.length
      };
      n("search-result-click", S);
    }
    function p(c) {
      if (c.value === Q) {
        $.value = b.value;
        return;
      }
      $.value = c.value ? c.label || String(c.value) : "";
    }
    function C(c) {
      var S;
      i.value = !1, (S = u.value) == null || S.clearActive(), se(c);
    }
    function _(c) {
      if (M.value)
        d(M.value), c.stopPropagation(), window.location.assign(M.value.url), c.preventDefault();
      else {
        const S = {
          searchResult: null,
          index: -1,
          numberOfResults: e.searchResults.length
        };
        n("submit", S);
      }
    }
    function X(c) {
      if (!u.value || !b.value || c.key === " ")
        return;
      const S = u.value.getHighlightedMenuItem(), O = u.value.getHighlightedViaKeyboard();
      switch (c.key) {
        case "Enter":
          S && (S.value === Q && O ? window.location.assign(e.searchFooterUrl) : u.value.delegateKeyNavigation(c, !1)), i.value = !1;
          break;
        case "Tab":
          i.value = !1;
          break;
        default:
          u.value.delegateKeyNavigation(c);
          break;
      }
    }
    return W(() => {
      e.initialInputValue && oe(e.initialInputValue, !0);
    }), Z(z(e, "searchResults"), () => {
      b.value = $.value.trim(), m.value && l.value && b.value.length > 0 && (i.value = !0), F !== void 0 && (clearTimeout(F), F = void 0), l.value = !1, r.value = !1;
    }), {
      form: o,
      menu: u,
      menuId: s,
      highlightedId: x,
      selection: k,
      menuMessageClass: G,
      footer: T,
      asSearchResult: g,
      inputValue: $,
      searchQuery: b,
      expanded: i,
      showPending: r,
      rootClasses: ae,
      rootStyle: le,
      otherAttrs: N,
      menuConfig: H,
      onUpdateInputValue: oe,
      onUpdateMenuSelection: ce,
      onFocus: he,
      onBlur: fe,
      onSearchResultClick: se,
      onSearchResultKeyboardNavigation: p,
      onSearchFooterClick: C,
      onSubmit: _,
      onKeydown: X,
      MenuFooterValue: Q,
      articleIcon: We
    };
  },
  methods: {
    /**
     * Focus the component's input element.
     *
     * @public
     */
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const In = ["id", "action"], _n = { class: "cdx-typeahead-search__menu-message__text" }, Sn = { class: "cdx-typeahead-search__menu-message__text" }, wn = ["href", "onClickCapture"], xn = { class: "cdx-menu-item__text cdx-typeahead-search__search-footer__text" }, kn = { class: "cdx-typeahead-search__search-footer__query" };
function Mn(e, t, n, a, o, u) {
  const s = B("cdx-icon"), i = B("cdx-menu"), l = B("cdx-search-input");
  return f(), v("div", {
    class: K(["cdx-typeahead-search", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    I("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...r) => e.onSubmit && e.onSubmit(...r))
    }, [
      j(l, P({
        ref: "searchInput",
        modelValue: e.inputValue,
        "onUpdate:modelValue": t[3] || (t[3] = (r) => e.inputValue = r),
        "button-label": e.buttonLabel
      }, e.otherAttrs, {
        class: "cdx-typeahead-search__input",
        name: "search",
        role: "combobox",
        autocomplete: "off",
        "aria-autocomplete": "list",
        "aria-controls": e.menuId,
        "aria-expanded": e.expanded,
        "aria-activedescendant": e.highlightedId,
        "onUpdate:modelValue": e.onUpdateInputValue,
        onFocus: e.onFocus,
        onBlur: e.onBlur,
        onKeydown: e.onKeydown
      }), {
        default: q(() => [
          j(i, P({
            id: e.menuId,
            ref: "menu",
            expanded: e.expanded,
            "onUpdate:expanded": t[0] || (t[0] = (r) => e.expanded = r),
            "show-pending": e.showPending,
            selected: e.selection,
            "menu-items": e.searchResults,
            footer: e.footer,
            "search-query": e.highlightQuery ? e.searchQuery : "",
            "show-no-results-slot": e.searchQuery.length > 0 && e.searchResults.length === 0 && e.$slots["search-no-results-text"] && e.$slots["search-no-results-text"]().length > 0
          }, e.menuConfig, {
            "aria-label": e.searchResultsLabel,
            "onUpdate:selected": e.onUpdateMenuSelection,
            onMenuItemClick: t[1] || (t[1] = (r) => e.onSearchResultClick(e.asSearchResult(r))),
            onMenuItemKeyboardNavigation: e.onSearchResultKeyboardNavigation,
            onLoadMore: t[2] || (t[2] = (r) => e.$emit("load-more"))
          }), {
            pending: q(() => [
              I("div", {
                class: K(["cdx-menu-item__content cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                I("span", _n, [
                  R(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": q(() => [
              I("div", {
                class: K(["cdx-menu-item__content cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                I("span", Sn, [
                  R(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: q(({ menuItem: r, active: m }) => [
              r.value === e.MenuFooterValue ? (f(), v("a", {
                key: 0,
                class: K(["cdx-menu-item__content cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": m
                }]),
                href: e.asSearchResult(r).url,
                onClickCapture: Ce(($) => e.onSearchFooterClick(e.asSearchResult(r)), ["stop"])
              }, [
                j(s, {
                  class: "cdx-menu-item__thumbnail cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                I("span", xn, [
                  R(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    I("strong", kn, V(e.searchQuery), 1)
                  ])
                ])
              ], 42, wn)) : w("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-controls", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      R(e.$slots, "default")
    ], 40, In)
  ], 6);
}
const Bn = /* @__PURE__ */ E($n, [["render", Mn]]);
export {
  Bn as CdxTypeaheadSearch
};
