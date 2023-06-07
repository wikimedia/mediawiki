var Be = Object.defineProperty, Le = Object.defineProperties;
var Ae = Object.getOwnPropertyDescriptors;
var le = Object.getOwnPropertySymbols;
var _e = Object.prototype.hasOwnProperty, Se = Object.prototype.propertyIsEnumerable;
var $e = (e, t, n) => t in e ? Be(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, Ie = (e, t) => {
  for (var n in t || (t = {}))
    _e.call(t, n) && $e(e, n, t[n]);
  if (le)
    for (var n of le(t))
      Se.call(t, n) && $e(e, n, t[n]);
  return e;
}, we = (e, t) => Le(e, Ae(t));
var oe = (e, t) => {
  var n = {};
  for (var a in e)
    _e.call(e, a) && t.indexOf(a) < 0 && (n[a] = e[a]);
  if (e != null && le)
    for (var a of le(e))
      t.indexOf(a) < 0 && Se.call(e, a) && (n[a] = e[a]);
  return n;
};
var fe = (e, t, n) => new Promise((a, s) => {
  var u = (l) => {
    try {
      i(n.next(l));
    } catch (r) {
      s(r);
    }
  }, o = (l) => {
    try {
      i(n.throw(l));
    } catch (r) {
      s(r);
    }
  }, i = (l) => l.done ? a(l.value) : Promise.resolve(l.value).then(u, o);
  i((n = n.apply(e, t)).next());
});
import { ref as C, onMounted as j, defineComponent as K, computed as f, openBlock as h, createElementBlock as v, normalizeClass as L, toDisplayString as M, createCommentVNode as w, resolveComponent as T, createVNode as Q, Transition as Ke, withCtx as q, normalizeStyle as X, createElementVNode as _, createTextVNode as Z, withModifiers as be, renderSlot as A, createBlock as B, resolveDynamicComponent as Re, Fragment as ge, getCurrentInstance as Ee, onUnmounted as ke, watch as G, toRef as J, nextTick as se, withDirectives as xe, mergeProps as P, renderList as Ne, vShow as Fe, Comment as Oe, warn as qe, withKeys as ve, vModelDynamic as He } from "vue";
const ze = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', De = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', Ue = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Qe = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', Pe = ze, je = De, We = Ue, Ge = Qe;
function Ze(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const a = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof a == "string" || "path" in a ? a : a.ltr;
}
function Je(e, t) {
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
function Xe(e) {
  const t = C(null);
  return j(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function Ye(e) {
  const t = C("");
  return j(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
function z(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const pe = "cdx", et = [
  "default",
  "progressive",
  "destructive"
], tt = [
  "normal",
  "primary",
  "quiet"
], nt = [
  "medium",
  "large"
], at = [
  "x-small",
  "small",
  "medium"
], lt = [
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
], Me = [
  "default",
  "error"
], ot = 120, st = 500, U = "cdx-menu-footer-item", it = z(at), ut = K({
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
      validator: it
    }
  },
  emits: ["click"],
  setup(e, { emit: t }) {
    const n = C(), a = Xe(n), s = Ye(n), u = f(() => e.dir || a.value), o = f(() => e.lang || s.value), i = f(() => ({
      "cdx-icon--flipped": u.value === "rtl" && o.value !== null && Je(e.icon, o.value),
      [`cdx-icon--${e.size}`]: !0
    })), l = f(
      () => Ze(e.icon, o.value || "", u.value || "ltr")
    ), r = f(() => typeof l.value == "string" ? l.value : ""), m = f(() => typeof l.value != "string" ? l.value.path : "");
    return {
      rootElement: n,
      rootClasses: i,
      iconSvg: r,
      iconPath: m,
      onClick: (y) => {
        t("click", y);
      }
    };
  }
});
const R = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [a, s] of t)
    n[a] = s;
  return n;
}, rt = ["aria-hidden"], dt = { key: 0 }, ct = ["innerHTML"], ht = ["d"];
function ft(e, t, n, a, s, u) {
  return h(), v("span", {
    ref: "rootElement",
    class: L(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    (h(), v("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": e.iconLabel ? void 0 : !0
    }, [
      e.iconLabel ? (h(), v("title", dt, M(e.iconLabel), 1)) : w("", !0),
      e.iconSvg ? (h(), v("g", {
        key: 1,
        innerHTML: e.iconSvg
      }, null, 8, ct)) : (h(), v("path", {
        key: 2,
        d: e.iconPath
      }, null, 8, ht))
    ], 8, rt))
  ], 2);
}
const Y = /* @__PURE__ */ R(ut, [["render", ft]]), pt = K({
  name: "CdxThumbnail",
  components: { CdxIcon: Y },
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
      default: We
    }
  },
  setup: (e) => {
    const t = C(!1), n = C({}), a = (s) => {
      const u = s.replace(/([\\"\n])/g, "\\$1"), o = new Image();
      o.onload = () => {
        n.value = { backgroundImage: `url("${u}")` }, t.value = !0;
      }, o.onerror = () => {
        t.value = !1;
      }, o.src = u;
    };
    return j(() => {
      var s;
      (s = e.thumbnail) != null && s.url && a(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const mt = { class: "cdx-thumbnail" }, gt = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function vt(e, t, n, a, s, u) {
  const o = T("cdx-icon");
  return h(), v("span", mt, [
    e.thumbnailLoaded ? w("", !0) : (h(), v("span", gt, [
      Q(o, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon--vue"
      }, null, 8, ["icon"])
    ])),
    Q(Ke, { name: "cdx-thumbnail__image" }, {
      default: q(() => [
        e.thumbnailLoaded ? (h(), v("span", {
          key: 0,
          style: X(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : w("", !0)
      ]),
      _: 1
    })
  ]);
}
const yt = /* @__PURE__ */ R(pt, [["render", vt]]);
function bt(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const Ct = "[̀-ͯ҃-҉֑-ׇֽֿׁׂׅׄؐ-ًؚ-ٰٟۖ-ۜ۟-۪ۤۧۨ-ܑۭܰ-݊ަ-ް߫-߽߳ࠖ-࠙ࠛ-ࠣࠥ-ࠧࠩ-࡙࠭-࡛࣓-ࣣ࣡-ःऺ-़ा-ॏ॑-ॗॢॣঁ-ঃ়া-ৄেৈো-্ৗৢৣ৾ਁ-ਃ਼ਾ-ੂੇੈੋ-੍ੑੰੱੵઁ-ઃ઼ા-ૅે-ૉો-્ૢૣૺ-૿ଁ-ଃ଼ା-ୄେୈୋ-୍ୖୗୢୣஂா-ூெ-ைொ-்ௗఀ-ఄా-ౄె-ైొ-్ౕౖౢౣಁ-ಃ಼ಾ-ೄೆ-ೈೊ-್ೕೖೢೣഀ-ഃ഻഼ാ-ൄെ-ൈൊ-്ൗൢൣංඃ්ා-ුූෘ-ෟෲෳัิ-ฺ็-๎ັິ-ູົຼ່-ໍ༹༘༙༵༷༾༿ཱ-྄྆྇ྍ-ྗྙ-ྼ࿆ါ-ှၖ-ၙၞ-ၠၢ-ၤၧ-ၭၱ-ၴႂ-ႍႏႚ-ႝ፝-፟ᜒ-᜔ᜲ-᜴ᝒᝓᝲᝳ឴-៓៝᠋-᠍ᢅᢆᢩᤠ-ᤫᤰ-᤻ᨗ-ᨛᩕ-ᩞ᩠-᩿᩼᪰-᪾ᬀ-ᬄ᬴-᭄᭫-᭳ᮀ-ᮂᮡ-ᮭ᯦-᯳ᰤ-᰷᳐-᳔᳒-᳨᳭ᳲ-᳴᳷-᳹᷀-᷹᷻-᷿⃐-⃰⳯-⵿⳱ⷠ-〪ⷿ-゙゚〯꙯-꙲ꙴ-꙽ꚞꚟ꛰꛱ꠂ꠆ꠋꠣ-ꠧꢀꢁꢴ-ꣅ꣠-꣱ꣿꤦ-꤭ꥇ-꥓ꦀ-ꦃ꦳-꧀ꧥꨩ-ꨶꩃꩌꩍꩻ-ꩽꪰꪲ-ꪴꪷꪸꪾ꪿꫁ꫫ-ꫯꫵ꫶ꯣ-ꯪ꯬꯭ﬞ︀-️︠-︯]";
function $t(e, t) {
  if (!e)
    return [t, "", ""];
  const n = bt(e), a = new RegExp(
    // Per https://www.regular-expressions.info/unicode.html, "any code point that is not a
    // combining mark can be followed by any number of combining marks." See also the
    // discussion in https://phabricator.wikimedia.org/T35242.
    n + Ct + "*",
    "i"
  ).exec(t);
  if (!a || a.index === void 0)
    return [t, "", ""];
  const s = a.index, u = s + a[0].length, o = t.slice(s, u), i = t.slice(0, s), l = t.slice(u, t.length);
  return [i, o, l];
}
const _t = K({
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
    titleChunks: f(() => $t(e.searchQuery, String(e.title)))
  })
});
const St = { class: "cdx-search-result-title" }, It = { class: "cdx-search-result-title__match" };
function wt(e, t, n, a, s, u) {
  return h(), v("span", St, [
    _("bdi", null, [
      Z(M(e.titleChunks[0]), 1),
      _("span", It, M(e.titleChunks[1]), 1),
      Z(M(e.titleChunks[2]), 1)
    ])
  ]);
}
const kt = /* @__PURE__ */ R(_t, [["render", wt]]), xt = K({
  name: "CdxMenuItem",
  components: { CdxIcon: Y, CdxThumbnail: yt, CdxSearchResultTitle: kt },
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
    }, s = (m) => {
      m.button === 0 && t("change", "active", !0);
    }, u = () => {
      t("change", "selected", !0);
    }, o = f(() => e.searchQuery.length > 0), i = f(() => ({
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
      "cdx-menu-item--highlight-query": o.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), l = f(() => e.url ? "a" : "span"), r = f(() => e.label || String(e.value));
    return {
      onMouseMove: n,
      onMouseLeave: a,
      onMouseDown: s,
      onClick: u,
      highlightQuery: o,
      rootClasses: i,
      contentTag: l,
      title: r
    };
  }
});
const Mt = ["id", "aria-disabled", "aria-selected"], Tt = { class: "cdx-menu-item__text" }, Vt = ["lang"], Bt = ["lang"], Lt = ["lang"], At = ["lang"];
function Kt(e, t, n, a, s, u) {
  const o = T("cdx-thumbnail"), i = T("cdx-icon"), l = T("cdx-search-result-title");
  return h(), v("li", {
    id: e.id,
    role: "option",
    class: L(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMousemove: t[0] || (t[0] = (...r) => e.onMouseMove && e.onMouseMove(...r)),
    onMouseleave: t[1] || (t[1] = (...r) => e.onMouseLeave && e.onMouseLeave(...r)),
    onMousedown: t[2] || (t[2] = be((...r) => e.onMouseDown && e.onMouseDown(...r), ["prevent"])),
    onClick: t[3] || (t[3] = (...r) => e.onClick && e.onClick(...r))
  }, [
    A(e.$slots, "default", {}, () => [
      (h(), B(Re(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: q(() => {
          var r, m, $, y, k, x;
          return [
            e.showThumbnail ? (h(), B(o, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (h(), B(i, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : w("", !0),
            _("span", Tt, [
              e.highlightQuery ? (h(), B(l, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (r = e.language) == null ? void 0 : r.label
              }, null, 8, ["title", "search-query", "lang"])) : (h(), v("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (m = e.language) == null ? void 0 : m.label
              }, [
                _("bdi", null, M(e.title), 1)
              ], 8, Vt)),
              e.match ? (h(), v(ge, { key: 2 }, [
                Z(M(" ") + " "),
                e.highlightQuery ? (h(), B(l, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: ($ = e.language) == null ? void 0 : $.match
                }, null, 8, ["title", "search-query", "lang"])) : (h(), v("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (y = e.language) == null ? void 0 : y.match
                }, [
                  _("bdi", null, M(e.match), 1)
                ], 8, Bt))
              ], 64)) : w("", !0),
              e.supportingText ? (h(), v(ge, { key: 3 }, [
                Z(M(" ") + " "),
                _("span", {
                  class: "cdx-menu-item__text__supporting-text",
                  lang: (k = e.language) == null ? void 0 : k.supportingText
                }, [
                  _("bdi", null, M(e.supportingText), 1)
                ], 8, Lt)
              ], 64)) : w("", !0),
              e.description ? (h(), v("span", {
                key: 4,
                class: "cdx-menu-item__text__description",
                lang: (x = e.language) == null ? void 0 : x.description
              }, [
                _("bdi", null, M(e.description), 1)
              ], 8, At)) : w("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, Mt);
}
const Rt = /* @__PURE__ */ R(xt, [["render", Kt]]), Et = K({
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
      rootClasses: f(() => ({
        "cdx-progress-bar--block": !e.inline,
        "cdx-progress-bar--inline": e.inline,
        "cdx-progress-bar--enabled": !e.disabled,
        "cdx-progress-bar--disabled": e.disabled
      }))
    };
  }
});
const Nt = ["aria-disabled"], Ft = /* @__PURE__ */ _("div", { class: "cdx-progress-bar__bar" }, null, -1), Ot = [
  Ft
];
function qt(e, t, n, a, s, u) {
  return h(), v("div", {
    class: L(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Ot, 10, Nt);
}
const Ht = /* @__PURE__ */ R(Et, [["render", qt]]);
let me = 0;
function Te(e) {
  const t = Ee(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${pe}-${e}-${me++}` : n ? `${pe}-${n}-${me++}` : `${pe}-${me++}`;
}
function zt(e, t) {
  const n = C(!1);
  let a = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const s = new window.IntersectionObserver(
    (u) => {
      const o = u[0];
      o && (n.value = o.isIntersecting);
    },
    t
  );
  return j(() => {
    a = !0, e.value && s.observe(e.value);
  }), ke(() => {
    a = !1, s.disconnect();
  }), G(e, (u) => {
    a && (s.disconnect(), n.value = !1, u && s.observe(u));
  }), n;
}
function ie(e, t = f(() => ({}))) {
  const n = f(() => {
    const u = oe(t.value, []);
    return e.class && e.class.split(" ").forEach((i) => {
      u[i] = !0;
    }), u;
  }), a = f(() => {
    if ("style" in e)
      return e.style;
  }), s = f(() => {
    const l = e, { class: u, style: o } = l;
    return oe(l, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: a,
    otherAttrs: s
  };
}
const Dt = K({
  name: "CdxMenu",
  components: {
    CdxMenuItem: Rt,
    CdxProgressBar: Ht
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
    const s = f(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((p) => we(Ie({}, p), {
      id: Te("menu-item")
    }))), u = f(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : s.value.length === 0 : !1), o = C(null), i = C(!1), l = C(null);
    function r() {
      return s.value.find(
        (d) => d.value === e.selected
      );
    }
    function m(d, p) {
      var b;
      if (!(p && p.disabled))
        switch (d) {
          case "selected":
            t("update:selected", (b = p == null ? void 0 : p.value) != null ? b : null), t("update:expanded", !1), l.value = null;
            break;
          case "highlighted":
            o.value = p || null, i.value = !1;
            break;
          case "highlightedViaKeyboard":
            o.value = p || null, i.value = !0;
            break;
          case "active":
            l.value = p || null;
            break;
        }
    }
    const $ = f(() => {
      if (o.value !== null)
        return s.value.findIndex(
          (d) => (
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            d.value === o.value.value
          )
        );
    });
    function y(d) {
      d && (m("highlightedViaKeyboard", d), t("menu-item-keyboard-navigation", d));
    }
    function k(d) {
      var S;
      const p = (W) => {
        for (let c = W - 1; c >= 0; c--)
          if (!s.value[c].disabled)
            return s.value[c];
      };
      d = d || s.value.length;
      const b = (S = p(d)) != null ? S : p(s.value.length);
      y(b);
    }
    function x(d) {
      const p = (S) => s.value.find((W, c) => !W.disabled && c > S);
      d = d != null ? d : -1;
      const b = p(d) || p(-1);
      y(b);
    }
    function ee(d, p = !0) {
      function b() {
        t("update:expanded", !0), m("highlighted", r());
      }
      function S() {
        p && (d.preventDefault(), d.stopPropagation());
      }
      switch (d.key) {
        case "Enter":
        case " ":
          return S(), e.expanded ? (o.value && i.value && t("update:selected", o.value.value), t("update:expanded", !1)) : b(), !0;
        case "Tab":
          return e.expanded && (o.value && i.value && t("update:selected", o.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return S(), e.expanded ? (o.value === null && m("highlightedViaKeyboard", r()), k($.value)) : b(), F(), !0;
        case "ArrowDown":
          return S(), e.expanded ? (o.value === null && m("highlightedViaKeyboard", r()), x($.value)) : b(), F(), !0;
        case "Home":
          return S(), e.expanded ? (o.value === null && m("highlightedViaKeyboard", r()), x()) : b(), F(), !0;
        case "End":
          return S(), e.expanded ? (o.value === null && m("highlightedViaKeyboard", r()), k()) : b(), F(), !0;
        case "Escape":
          return S(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function g() {
      m("active");
    }
    const V = [], te = C(void 0), ue = zt(
      te,
      { threshold: 0.8 }
    );
    G(ue, (d) => {
      d && t("load-more");
    });
    function re(d, p) {
      if (d) {
        V[p] = d.$el;
        const b = e.visibleItemLimit;
        if (!b || e.menuItems.length < b)
          return;
        const S = Math.min(
          b,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        p === e.menuItems.length - S && (te.value = d.$el);
      }
    }
    function F() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || $.value === void 0)
        return;
      const d = $.value >= 0 ? $.value : 0;
      V[d].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const O = C(null), D = C(null);
    function H() {
      if (D.value = null, !e.visibleItemLimit || V.length <= e.visibleItemLimit) {
        O.value = null;
        return;
      }
      const d = V[0], p = V[e.visibleItemLimit];
      if (O.value = E(
        d,
        p
      ), e.footer) {
        const b = V[V.length - 1];
        D.value = b.scrollHeight;
      }
    }
    function E(d, p) {
      const b = d.getBoundingClientRect().top;
      return p.getBoundingClientRect().top - b + 2;
    }
    j(() => {
      document.addEventListener("mouseup", g);
    }), ke(() => {
      document.removeEventListener("mouseup", g);
    }), G(J(e, "expanded"), (d) => fe(this, null, function* () {
      const p = r();
      !d && o.value && p === void 0 && m("highlighted"), d && p !== void 0 && m("highlighted", p), d && (yield se(), H(), yield se(), F());
    })), G(J(e, "menuItems"), (d) => fe(this, null, function* () {
      d.length < V.length && (V.length = d.length), e.expanded && (yield se(), H(), yield se(), F());
    }), { deep: !0 });
    const ne = f(() => ({
      "max-height": O.value ? `${O.value}px` : void 0,
      "overflow-y": O.value ? "scroll" : void 0,
      "margin-bottom": D.value ? `${D.value}px` : void 0
    })), de = f(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!O.value
    })), {
      rootClasses: ce,
      rootStyle: he,
      otherAttrs: ae
    } = ie(a, de);
    return {
      listBoxStyle: ne,
      rootClasses: ce,
      rootStyle: he,
      otherAttrs: ae,
      assignTemplateRef: re,
      computedMenuItems: s,
      computedShowNoResultsSlot: u,
      highlightedMenuItem: o,
      highlightedViaKeyboard: i,
      activeMenuItem: l,
      handleMenuItemChange: m,
      handleKeyNavigation: ee
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
const Ut = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Qt = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function Pt(e, t, n, a, s, u) {
  const o = T("cdx-menu-item"), i = T("cdx-progress-bar");
  return xe((h(), v("div", {
    class: L(["cdx-menu", e.rootClasses]),
    style: X(e.rootStyle)
  }, [
    _("ul", P({
      class: "cdx-menu__listbox",
      role: "listbox",
      "aria-multiselectable": "false",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (h(), v("li", Ut, [
        A(e.$slots, "pending")
      ])) : w("", !0),
      e.computedShowNoResultsSlot ? (h(), v("li", Qt, [
        A(e.$slots, "no-results")
      ])) : w("", !0),
      (h(!0), v(ge, null, Ne(e.computedMenuItems, (l, r) => {
        var m, $;
        return h(), B(o, P({
          key: l.value,
          ref_for: !0,
          ref: (y) => e.assignTemplateRef(y, r)
        }, l, {
          selected: l.value === e.selected,
          active: l.value === ((m = e.activeMenuItem) == null ? void 0 : m.value),
          highlighted: l.value === (($ = e.highlightedMenuItem) == null ? void 0 : $.value),
          "show-thumbnail": e.showThumbnail,
          "bold-label": e.boldLabel,
          "hide-description-overflow": e.hideDescriptionOverflow,
          "search-query": e.searchQuery,
          onChange: (y, k) => e.handleMenuItemChange(y, k && l),
          onClick: (y) => e.$emit("menu-item-click", l)
        }), {
          default: q(() => {
            var y, k;
            return [
              A(e.$slots, "default", {
                menuItem: l,
                active: l.value === ((y = e.activeMenuItem) == null ? void 0 : y.value) && l.value === ((k = e.highlightedMenuItem) == null ? void 0 : k.value)
              })
            ];
          }),
          _: 2
        }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
      }), 128)),
      e.showPending ? (h(), B(i, {
        key: 2,
        class: "cdx-menu__progress-bar",
        inline: !0
      })) : w("", !0)
    ], 16)
  ], 6)), [
    [Fe, e.expanded]
  ]);
}
const jt = /* @__PURE__ */ R(Dt, [["render", Pt]]), Wt = z(et), Gt = z(tt), Zt = z(nt), Jt = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && qe(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/demos/button.html#icon-only-button-1`);
};
function ye(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...ye(n)) : typeof n == "object" && n && (// HTML tag
    typeof n.type == "string" || // Component
    typeof n.type == "object" ? t.push(n) : n.type !== Oe && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...ye(n.children))));
  return t;
}
const Xt = (e, t) => {
  if (!e)
    return !1;
  const n = ye(e);
  if (n.length !== 1)
    return !1;
  const a = n[0], s = typeof a == "object" && typeof a.type == "object" && "name" in a.type && a.type.name === Y.name, u = typeof a == "object" && a.type === "svg";
  return s || u ? (Jt(t), !0) : !1;
}, Yt = K({
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
      validator: Wt
    },
    /**
     * Visual prominence of the button.
     *
     * @values 'normal', 'primary', 'quiet'
     */
    weight: {
      type: String,
      default: "normal",
      validator: Gt
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
      validator: Zt
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: a }) {
    const s = C(!1);
    return {
      rootClasses: f(() => {
        var l;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--weight-${e.weight}`]: !0,
          [`cdx-button--size-${e.size}`]: !0,
          "cdx-button--framed": e.weight !== "quiet",
          "cdx-button--icon-only": Xt((l = n.default) == null ? void 0 : l.call(n), a),
          "cdx-button--is-active": s.value
        };
      }),
      onClick: (l) => {
        t("click", l);
      },
      setActive: (l) => {
        s.value = l;
      }
    };
  }
});
function en(e, t, n, a, s, u) {
  return h(), v("button", {
    class: L(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o)),
    onKeydown: t[1] || (t[1] = ve((o) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = ve((o) => e.setActive(!1), ["space", "enter"]))
  }, [
    A(e.$slots, "default")
  ], 34);
}
const tn = /* @__PURE__ */ R(Yt, [["render", en]]);
function Ve(e, t, n) {
  return f({
    get: () => e.value,
    set: (a) => (
      // If eventName is undefined, then 'update:modelValue' must be a valid EventName,
      // but TypeScript's type analysis isn't clever enough to realize that
      t(n || "update:modelValue", a)
    )
  });
}
const nn = z(lt), an = z(Me), ln = K({
  name: "CdxTextInput",
  components: { CdxIcon: Y },
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
      validator: nn
    },
    /**
     * `status` attribute of the input.
     *
     * @values 'default', 'error'
     */
    status: {
      type: String,
      default: "default",
      validator: an
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
    const a = Ve(J(e, "modelValue"), t), s = f(() => e.clearable && !!a.value && !e.disabled), u = f(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": s.value,
      [`cdx-text-input--status-${e.status}`]: !0
    })), {
      rootClasses: o,
      rootStyle: i,
      otherAttrs: l
    } = ie(n, u), r = f(() => ({
      "cdx-text-input__input--has-value": !!a.value
    }));
    return {
      wrappedModel: a,
      isClearable: s,
      rootClasses: o,
      rootStyle: i,
      otherAttrs: l,
      inputClasses: r,
      onClear: (g) => {
        a.value = "", t("clear", g);
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
      cdxIconClear: je
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
const on = ["type", "disabled"];
function sn(e, t, n, a, s, u) {
  const o = T("cdx-icon");
  return h(), v("div", {
    class: L(["cdx-text-input", e.rootClasses]),
    style: X(e.rootStyle)
  }, [
    xe(_("input", P({
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (i) => e.wrappedModel = i),
      class: ["cdx-text-input__input", e.inputClasses]
    }, e.otherAttrs, {
      type: e.inputType,
      disabled: e.disabled,
      onInput: t[1] || (t[1] = (...i) => e.onInput && e.onInput(...i)),
      onChange: t[2] || (t[2] = (...i) => e.onChange && e.onChange(...i)),
      onFocus: t[3] || (t[3] = (...i) => e.onFocus && e.onFocus(...i)),
      onBlur: t[4] || (t[4] = (...i) => e.onBlur && e.onBlur(...i)),
      onKeydown: t[5] || (t[5] = (...i) => e.onKeydown && e.onKeydown(...i))
    }), null, 16, on), [
      [He, e.wrappedModel]
    ]),
    e.startIcon ? (h(), B(o, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.endIcon ? (h(), B(o, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.isClearable ? (h(), B(o, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon-vue cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = be(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : w("", !0)
  ], 6);
}
const un = /* @__PURE__ */ R(ln, [["render", sn]]), rn = z(Me), dn = K({
  name: "CdxSearchInput",
  components: {
    CdxButton: tn,
    CdxTextInput: un
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
     * `status` property of the TextInput component
     *
     * @values 'default', 'error'
     */
    status: {
      type: String,
      default: "default",
      validator: rn
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
    const a = Ve(J(e, "modelValue"), t), s = f(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: u,
      rootStyle: o,
      otherAttrs: i
    } = ie(n, s);
    return {
      wrappedModel: a,
      rootClasses: u,
      rootStyle: o,
      otherAttrs: i,
      handleSubmit: () => {
        t("submit-click", a.value);
      },
      searchIcon: Ge
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
const cn = { class: "cdx-search-input__input-wrapper" };
function hn(e, t, n, a, s, u) {
  const o = T("cdx-text-input"), i = T("cdx-button");
  return h(), v("div", {
    class: L(["cdx-search-input", e.rootClasses]),
    style: X(e.rootStyle)
  }, [
    _("div", cn, [
      Q(o, P({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (l) => e.wrappedModel = l),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon,
        status: e.status
      }, e.otherAttrs, {
        onKeydown: ve(e.handleSubmit, ["enter"]),
        onInput: t[1] || (t[1] = (l) => e.$emit("input", l)),
        onChange: t[2] || (t[2] = (l) => e.$emit("change", l)),
        onFocus: t[3] || (t[3] = (l) => e.$emit("focus", l)),
        onBlur: t[4] || (t[4] = (l) => e.$emit("blur", l))
      }), null, 16, ["modelValue", "start-icon", "status", "onKeydown"]),
      A(e.$slots, "default")
    ]),
    e.buttonLabel ? (h(), B(i, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: q(() => [
        Z(M(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : w("", !0)
  ], 6);
}
const fn = /* @__PURE__ */ R(dn, [["render", hn]]), pn = K({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: Y,
    CdxMenu: jt,
    CdxSearchInput: fn
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
      default: ot
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
    const s = C(), u = C(), o = Te("typeahead-search-menu"), i = C(!1), l = C(!1), r = C(!1), m = C(!1), $ = C(e.initialInputValue), y = C(""), k = f(() => {
      var c, I;
      return (I = (c = u.value) == null ? void 0 : c.getHighlightedMenuItem()) == null ? void 0 : I.id;
    }), x = C(null), ee = f(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), g = f(
      () => e.searchResults.find(
        (c) => c.value === x.value
      )
    ), V = f(
      () => e.searchFooterUrl ? { value: U, url: e.searchFooterUrl } : void 0
    ), te = f(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": i.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: ue,
      rootStyle: re,
      otherAttrs: F
    } = ie(t, te);
    function O(c) {
      return c;
    }
    const D = f(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      // In case search queries aren't highlighted, default to a bold label.
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let H, E;
    function ne(c, I = !1) {
      g.value && g.value.label !== c && g.value.value !== c && (x.value = null), E !== void 0 && (clearTimeout(E), E = void 0), c === "" ? i.value = !1 : (l.value = !0, a["search-results-pending"] && (E = setTimeout(() => {
        m.value && (i.value = !0), r.value = !0;
      }, st))), H !== void 0 && (clearTimeout(H), H = void 0);
      const N = () => {
        n("input", c);
      };
      I ? N() : H = setTimeout(() => {
        N();
      }, e.debounceInterval);
    }
    function de(c) {
      if (c === U) {
        x.value = null, $.value = y.value;
        return;
      }
      x.value = c, c !== null && ($.value = g.value ? g.value.label || String(g.value.value) : "");
    }
    function ce() {
      m.value = !0, (y.value || r.value) && (i.value = !0);
    }
    function he() {
      m.value = !1, i.value = !1;
    }
    function ae(c) {
      const Ce = c, { id: I } = Ce, N = oe(Ce, ["id"]);
      if (N.value === U) {
        n("search-result-click", {
          searchResult: null,
          index: e.searchResults.length,
          numberOfResults: e.searchResults.length
        });
        return;
      }
      d(N);
    }
    function d(c) {
      const I = {
        searchResult: c,
        index: e.searchResults.findIndex(
          (N) => N.value === c.value
        ),
        numberOfResults: e.searchResults.length
      };
      n("search-result-click", I);
    }
    function p(c) {
      if (c.value === U) {
        $.value = y.value;
        return;
      }
      $.value = c.value ? c.label || String(c.value) : "";
    }
    function b(c) {
      var I;
      i.value = !1, (I = u.value) == null || I.clearActive(), ae(c);
    }
    function S(c) {
      if (g.value)
        d(g.value), c.stopPropagation(), window.location.assign(g.value.url), c.preventDefault();
      else {
        const I = {
          searchResult: null,
          index: -1,
          numberOfResults: e.searchResults.length
        };
        n("submit", I);
      }
    }
    function W(c) {
      if (!u.value || !y.value || c.key === " ")
        return;
      const I = u.value.getHighlightedMenuItem(), N = u.value.getHighlightedViaKeyboard();
      switch (c.key) {
        case "Enter":
          I && (I.value === U && N ? window.location.assign(e.searchFooterUrl) : u.value.delegateKeyNavigation(c, !1)), i.value = !1;
          break;
        case "Tab":
          i.value = !1;
          break;
        default:
          u.value.delegateKeyNavigation(c);
          break;
      }
    }
    return j(() => {
      e.initialInputValue && ne(e.initialInputValue, !0);
    }), G(J(e, "searchResults"), () => {
      y.value = $.value.trim(), m.value && l.value && y.value.length > 0 && (i.value = !0), E !== void 0 && (clearTimeout(E), E = void 0), l.value = !1, r.value = !1;
    }), {
      form: s,
      menu: u,
      menuId: o,
      highlightedId: k,
      selection: x,
      menuMessageClass: ee,
      footer: V,
      asSearchResult: O,
      inputValue: $,
      searchQuery: y,
      expanded: i,
      showPending: r,
      rootClasses: ue,
      rootStyle: re,
      otherAttrs: F,
      menuConfig: D,
      onUpdateInputValue: ne,
      onUpdateMenuSelection: de,
      onFocus: ce,
      onBlur: he,
      onSearchResultClick: ae,
      onSearchResultKeyboardNavigation: p,
      onSearchFooterClick: b,
      onSubmit: S,
      onKeydown: W,
      MenuFooterValue: U,
      articleIcon: Pe
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
const mn = ["id", "action"], gn = { class: "cdx-typeahead-search__menu-message__text" }, vn = { class: "cdx-typeahead-search__menu-message__text" }, yn = ["href", "onClickCapture"], bn = { class: "cdx-menu-item__text cdx-typeahead-search__search-footer__text" }, Cn = { class: "cdx-typeahead-search__search-footer__query" };
function $n(e, t, n, a, s, u) {
  const o = T("cdx-icon"), i = T("cdx-menu"), l = T("cdx-search-input");
  return h(), v("div", {
    class: L(["cdx-typeahead-search", e.rootClasses]),
    style: X(e.rootStyle)
  }, [
    _("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...r) => e.onSubmit && e.onSubmit(...r))
    }, [
      Q(l, P({
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
        "aria-owns": e.menuId,
        "aria-expanded": e.expanded,
        "aria-activedescendant": e.highlightedId,
        "onUpdate:modelValue": e.onUpdateInputValue,
        onFocus: e.onFocus,
        onBlur: e.onBlur,
        onKeydown: e.onKeydown
      }), {
        default: q(() => [
          Q(i, P({
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
              _("div", {
                class: L(["cdx-menu-item__content cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                _("span", gn, [
                  A(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": q(() => [
              _("div", {
                class: L(["cdx-menu-item__content cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                _("span", vn, [
                  A(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: q(({ menuItem: r, active: m }) => [
              r.value === e.MenuFooterValue ? (h(), v("a", {
                key: 0,
                class: L(["cdx-menu-item__content cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": m
                }]),
                href: e.asSearchResult(r).url,
                onClickCapture: be(($) => e.onSearchFooterClick(e.asSearchResult(r)), ["stop"])
              }, [
                Q(o, {
                  class: "cdx-menu-item__thumbnail cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                _("span", bn, [
                  A(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    _("strong", Cn, M(e.searchQuery), 1)
                  ])
                ])
              ], 42, yn)) : w("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      A(e.$slots, "default")
    ], 40, mn)
  ], 6);
}
const In = /* @__PURE__ */ R(pn, [["render", $n]]);
export {
  In as CdxTypeaheadSearch
};
