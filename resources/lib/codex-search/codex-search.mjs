var Ke = Object.defineProperty, Re = Object.defineProperties;
var Ee = Object.getOwnPropertyDescriptors;
var se = Object.getOwnPropertySymbols;
var Se = Object.prototype.hasOwnProperty, we = Object.prototype.propertyIsEnumerable;
var $e = (e, t, n) => t in e ? Ke(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, Ie = (e, t) => {
  for (var n in t || (t = {}))
    Se.call(t, n) && $e(e, n, t[n]);
  if (se)
    for (var n of se(t))
      we.call(t, n) && $e(e, n, t[n]);
  return e;
}, ke = (e, t) => Re(e, Ee(t));
var ie = (e, t) => {
  var n = {};
  for (var a in e)
    Se.call(e, a) && t.indexOf(a) < 0 && (n[a] = e[a]);
  if (e != null && se)
    for (var a of se(e))
      t.indexOf(a) < 0 && we.call(e, a) && (n[a] = e[a]);
  return n;
};
var pe = (e, t, n) => new Promise((a, o) => {
  var r = (s) => {
    try {
      i(n.next(s));
    } catch (d) {
      o(d);
    }
  }, l = (s) => {
    try {
      i(n.throw(s));
    } catch (d) {
      o(d);
    }
  }, i = (s) => s.done ? a(s.value) : Promise.resolve(s.value).then(r, l);
  i((n = n.apply(e, t)).next());
});
import { ref as y, onMounted as G, defineComponent as E, computed as p, openBlock as c, createElementBlock as g, normalizeClass as A, toDisplayString as T, createCommentVNode as M, resolveComponent as V, createVNode as j, Transition as Ne, withCtx as Q, normalizeStyle as te, createElementVNode as C, createTextVNode as Z, withModifiers as Ce, renderSlot as R, createBlock as B, resolveDynamicComponent as Oe, Fragment as ve, getCurrentInstance as Fe, onUnmounted as Me, watch as Y, toRef as ee, nextTick as ue, withDirectives as xe, mergeProps as W, renderList as qe, vShow as He, Comment as De, warn as Qe, withKeys as ye, vModelDynamic as Ue, toRefs as ze } from "vue";
const Pe = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', je = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', We = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Ge = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', Je = Pe, Xe = je, Ye = We, Ze = Ge;
function et(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const a = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof a == "string" || "path" in a ? a : a.ltr;
}
function tt(e, t) {
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
function nt(e) {
  const t = y(null);
  return G(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function at(e) {
  const t = y("");
  return G(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
function J(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const me = "cdx", lt = [
  "default",
  "progressive",
  "destructive"
], ot = [
  "normal",
  "primary",
  "quiet"
], st = [
  "x-small",
  "small",
  "medium"
], it = [
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
], ut = 120, rt = 500, P = "cdx-menu-footer-item", dt = J(st), ct = E({
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
      validator: dt
    }
  },
  emits: ["click"],
  setup(e, { emit: t }) {
    const n = y(), a = nt(n), o = at(n), r = p(() => e.dir || a.value), l = p(() => e.lang || o.value), i = p(() => ({
      "cdx-icon--flipped": r.value === "rtl" && l.value !== null && tt(e.icon, l.value),
      [`cdx-icon--${e.size}`]: !0
    })), s = p(
      () => et(e.icon, l.value || "", r.value || "ltr")
    ), d = p(() => typeof s.value == "string" ? s.value : ""), f = p(() => typeof s.value != "string" ? s.value.path : "");
    return {
      rootElement: n,
      rootClasses: i,
      iconSvg: d,
      iconPath: f,
      onClick: (b) => {
        t("click", b);
      }
    };
  }
});
const N = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [a, o] of t)
    n[a] = o;
  return n;
}, ht = ["aria-hidden"], ft = { key: 0 }, pt = ["innerHTML"], mt = ["d"];
function gt(e, t, n, a, o, r) {
  return c(), g("span", {
    ref: "rootElement",
    class: A(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...l) => e.onClick && e.onClick(...l))
  }, [
    (c(), g("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": e.iconLabel ? void 0 : !0
    }, [
      e.iconLabel ? (c(), g("title", ft, T(e.iconLabel), 1)) : M("", !0),
      e.iconSvg ? (c(), g("g", {
        key: 1,
        innerHTML: e.iconSvg
      }, null, 8, pt)) : (c(), g("path", {
        key: 2,
        d: e.iconPath
      }, null, 8, mt))
    ], 8, ht))
  ], 2);
}
const ne = /* @__PURE__ */ N(ct, [["render", gt]]), vt = E({
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
      default: Ye
    }
  },
  setup: (e) => {
    const t = y(!1), n = y({}), a = (o) => {
      const r = o.replace(/([\\"\n])/g, "\\$1"), l = new Image();
      l.onload = () => {
        n.value = { backgroundImage: `url("${r}")` }, t.value = !0;
      }, l.onerror = () => {
        t.value = !1;
      }, l.src = r;
    };
    return G(() => {
      var o;
      (o = e.thumbnail) != null && o.url && a(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const yt = { class: "cdx-thumbnail" }, bt = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function Ct(e, t, n, a, o, r) {
  const l = V("cdx-icon");
  return c(), g("span", yt, [
    e.thumbnailLoaded ? M("", !0) : (c(), g("span", bt, [
      j(l, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon--vue"
      }, null, 8, ["icon"])
    ])),
    j(Ne, { name: "cdx-thumbnail__image" }, {
      default: Q(() => [
        e.thumbnailLoaded ? (c(), g("span", {
          key: 0,
          style: te(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : M("", !0)
      ]),
      _: 1
    })
  ]);
}
const _t = /* @__PURE__ */ N(vt, [["render", Ct]]);
function $t(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const St = "[̀-ͯ҃-҉֑-ׇֽֿׁׂׅׄؐ-ًؚ-ٰٟۖ-ۜ۟-۪ۤۧۨ-ܑۭܰ-݊ަ-ް߫-߽߳ࠖ-࠙ࠛ-ࠣࠥ-ࠧࠩ-࡙࠭-࡛࣓-ࣣ࣡-ःऺ-़ा-ॏ॑-ॗॢॣঁ-ঃ়া-ৄেৈো-্ৗৢৣ৾ਁ-ਃ਼ਾ-ੂੇੈੋ-੍ੑੰੱੵઁ-ઃ઼ા-ૅે-ૉો-્ૢૣૺ-૿ଁ-ଃ଼ା-ୄେୈୋ-୍ୖୗୢୣஂா-ூெ-ைொ-்ௗఀ-ఄా-ౄె-ైొ-్ౕౖౢౣಁ-ಃ಼ಾ-ೄೆ-ೈೊ-್ೕೖೢೣഀ-ഃ഻഼ാ-ൄെ-ൈൊ-്ൗൢൣංඃ්ා-ුූෘ-ෟෲෳัิ-ฺ็-๎ັິ-ູົຼ່-ໍ༹༘༙༵༷༾༿ཱ-྄྆྇ྍ-ྗྙ-ྼ࿆ါ-ှၖ-ၙၞ-ၠၢ-ၤၧ-ၭၱ-ၴႂ-ႍႏႚ-ႝ፝-፟ᜒ-᜔ᜲ-᜴ᝒᝓᝲᝳ឴-៓៝᠋-᠍ᢅᢆᢩᤠ-ᤫᤰ-᤻ᨗ-ᨛᩕ-ᩞ᩠-᩿᩼᪰-᪾ᬀ-ᬄ᬴-᭄᭫-᭳ᮀ-ᮂᮡ-ᮭ᯦-᯳ᰤ-᰷᳐-᳔᳒-᳨᳭ᳲ-᳴᳷-᳹᷀-᷹᷻-᷿⃐-⃰⳯-⵿⳱ⷠ-〪ⷿ-゙゚〯꙯-꙲ꙴ-꙽ꚞꚟ꛰꛱ꠂ꠆ꠋꠣ-ꠧꢀꢁꢴ-ꣅ꣠-꣱ꣿꤦ-꤭ꥇ-꥓ꦀ-ꦃ꦳-꧀ꧥꨩ-ꨶꩃꩌꩍꩻ-ꩽꪰꪲ-ꪴꪷꪸꪾ꪿꫁ꫫ-ꫯꫵ꫶ꯣ-ꯪ꯬꯭ﬞ︀-️︠-︯]";
function wt(e, t) {
  if (!e)
    return [t, "", ""];
  const n = $t(e), a = new RegExp(
    // Per https://www.regular-expressions.info/unicode.html, "any code point that is not a
    // combining mark can be followed by any number of combining marks." See also the
    // discussion in https://phabricator.wikimedia.org/T35242.
    n + St + "*",
    "i"
  ).exec(t);
  if (!a || a.index === void 0)
    return [t, "", ""];
  const o = a.index, r = o + a[0].length, l = t.slice(o, r), i = t.slice(0, o), s = t.slice(r, t.length);
  return [i, l, s];
}
const It = E({
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
    titleChunks: p(() => wt(e.searchQuery, String(e.title)))
  })
});
const kt = { class: "cdx-search-result-title" }, Mt = { class: "cdx-search-result-title__match" };
function xt(e, t, n, a, o, r) {
  return c(), g("span", kt, [
    C("bdi", null, [
      Z(T(e.titleChunks[0]), 1),
      C("span", Mt, T(e.titleChunks[1]), 1),
      Z(T(e.titleChunks[2]), 1)
    ])
  ]);
}
const Tt = /* @__PURE__ */ N(It, [["render", xt]]), Vt = E({
  name: "CdxMenuItem",
  components: { CdxIcon: ne, CdxThumbnail: _t, CdxSearchResultTitle: Tt },
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
    }, o = (f) => {
      f.button === 0 && t("change", "active", !0);
    }, r = () => {
      t("change", "selected", !0);
    }, l = p(() => e.searchQuery.length > 0), i = p(() => ({
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
      "cdx-menu-item--highlight-query": l.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), s = p(() => e.url ? "a" : "span"), d = p(() => e.label || String(e.value));
    return {
      onMouseMove: n,
      onMouseLeave: a,
      onMouseDown: o,
      onClick: r,
      highlightQuery: l,
      rootClasses: i,
      contentTag: s,
      title: d
    };
  }
});
const Lt = ["id", "aria-disabled", "aria-selected"], Bt = { class: "cdx-menu-item__text" }, At = ["lang"], Kt = ["lang"], Rt = ["lang"], Et = ["lang"];
function Nt(e, t, n, a, o, r) {
  const l = V("cdx-thumbnail"), i = V("cdx-icon"), s = V("cdx-search-result-title");
  return c(), g("li", {
    id: e.id,
    role: "option",
    class: A(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMousemove: t[0] || (t[0] = (...d) => e.onMouseMove && e.onMouseMove(...d)),
    onMouseleave: t[1] || (t[1] = (...d) => e.onMouseLeave && e.onMouseLeave(...d)),
    onMousedown: t[2] || (t[2] = Ce((...d) => e.onMouseDown && e.onMouseDown(...d), ["prevent"])),
    onClick: t[3] || (t[3] = (...d) => e.onClick && e.onClick(...d))
  }, [
    R(e.$slots, "default", {}, () => [
      (c(), B(Oe(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: Q(() => {
          var d, f, _, b, w, x;
          return [
            e.showThumbnail ? (c(), B(l, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (c(), B(i, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : M("", !0),
            C("span", Bt, [
              e.highlightQuery ? (c(), B(s, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (d = e.language) == null ? void 0 : d.label
              }, null, 8, ["title", "search-query", "lang"])) : (c(), g("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (f = e.language) == null ? void 0 : f.label
              }, [
                C("bdi", null, T(e.title), 1)
              ], 8, At)),
              e.match ? (c(), g(ve, { key: 2 }, [
                Z(T(" ") + " "),
                e.highlightQuery ? (c(), B(s, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: (_ = e.language) == null ? void 0 : _.match
                }, null, 8, ["title", "search-query", "lang"])) : (c(), g("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (b = e.language) == null ? void 0 : b.match
                }, [
                  C("bdi", null, T(e.match), 1)
                ], 8, Kt))
              ], 64)) : M("", !0),
              e.supportingText ? (c(), g(ve, { key: 3 }, [
                Z(T(" ") + " "),
                C("span", {
                  class: "cdx-menu-item__text__supporting-text",
                  lang: (w = e.language) == null ? void 0 : w.supportingText
                }, [
                  C("bdi", null, T(e.supportingText), 1)
                ], 8, Rt)
              ], 64)) : M("", !0),
              e.description ? (c(), g("span", {
                key: 4,
                class: "cdx-menu-item__text__description",
                lang: (x = e.language) == null ? void 0 : x.description
              }, [
                C("bdi", null, T(e.description), 1)
              ], 8, Et)) : M("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, Lt);
}
const Ot = /* @__PURE__ */ N(Vt, [["render", Nt]]), Ft = E({
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
      rootClasses: p(() => ({
        "cdx-progress-bar--block": !e.inline,
        "cdx-progress-bar--inline": e.inline,
        "cdx-progress-bar--enabled": !e.disabled,
        "cdx-progress-bar--disabled": e.disabled
      }))
    };
  }
});
const qt = ["aria-disabled"], Ht = /* @__PURE__ */ C("div", { class: "cdx-progress-bar__bar" }, null, -1), Dt = [
  Ht
];
function Qt(e, t, n, a, o, r) {
  return c(), g("div", {
    class: A(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Dt, 10, qt);
}
const Ut = /* @__PURE__ */ N(Ft, [["render", Qt]]);
let ge = 0;
function Ve(e) {
  const t = Fe(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${me}-${e}-${ge++}` : n ? `${me}-${n}-${ge++}` : `${me}-${ge++}`;
}
function zt(e, t) {
  const n = y(!1);
  let a = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const o = new window.IntersectionObserver(
    (r) => {
      const l = r[0];
      l && (n.value = l.isIntersecting);
    },
    t
  );
  return G(() => {
    a = !0, e.value && o.observe(e.value);
  }), Me(() => {
    a = !1, o.disconnect();
  }), Y(e, (r) => {
    a && (o.disconnect(), n.value = !1, r && o.observe(r));
  }), n;
}
function re(e, t = p(() => ({}))) {
  const n = p(() => {
    const r = ie(t.value, []);
    return e.class && e.class.split(" ").forEach((i) => {
      r[i] = !0;
    }), r;
  }), a = p(() => {
    if ("style" in e)
      return e.style;
  }), o = p(() => {
    const s = e, { class: r, style: l } = s;
    return ie(s, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: a,
    otherAttrs: o
  };
}
const Pt = E({
  name: "CdxMenu",
  components: {
    CdxMenuItem: Ot,
    CdxProgressBar: Ut
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
    const o = p(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((m) => ke(Ie({}, m), {
      id: Ve("menu-item")
    }))), r = p(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : o.value.length === 0 : !1), l = y(null), i = y(!1), s = y(null);
    function d() {
      return o.value.find(
        (u) => u.value === e.selected
      );
    }
    function f(u, m) {
      var v;
      if (!(m && m.disabled))
        switch (u) {
          case "selected":
            t("update:selected", (v = m == null ? void 0 : m.value) != null ? v : null), t("update:expanded", !1), s.value = null;
            break;
          case "highlighted":
            l.value = m || null, i.value = !1;
            break;
          case "highlightedViaKeyboard":
            l.value = m || null, i.value = !0;
            break;
          case "active":
            s.value = m || null;
            break;
        }
    }
    const _ = p(() => {
      if (l.value !== null)
        return o.value.findIndex(
          (u) => (
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            u.value === l.value.value
          )
        );
    });
    function b(u) {
      u && (f("highlightedViaKeyboard", u), t("menu-item-keyboard-navigation", u));
    }
    function w(u) {
      var S;
      const m = (X) => {
        for (let D = X - 1; D >= 0; D--)
          if (!o.value[D].disabled)
            return o.value[D];
      };
      u = u || o.value.length;
      const v = (S = m(u)) != null ? S : m(o.value.length);
      b(v);
    }
    function x(u) {
      const m = (S) => o.value.find((X, D) => !X.disabled && D > S);
      u = u != null ? u : -1;
      const v = m(u) || m(-1);
      b(v);
    }
    function K(u, m = !0) {
      function v() {
        t("update:expanded", !0), f("highlighted", d());
      }
      function S() {
        m && (u.preventDefault(), u.stopPropagation());
      }
      switch (u.key) {
        case "Enter":
        case " ":
          return S(), e.expanded ? (l.value && i.value && t("update:selected", l.value.value), t("update:expanded", !1)) : v(), !0;
        case "Tab":
          return e.expanded && (l.value && i.value && t("update:selected", l.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return S(), e.expanded ? (l.value === null && f("highlightedViaKeyboard", d()), w(_.value)) : v(), q(), !0;
        case "ArrowDown":
          return S(), e.expanded ? (l.value === null && f("highlightedViaKeyboard", d()), x(_.value)) : v(), q(), !0;
        case "Home":
          return S(), e.expanded ? (l.value === null && f("highlightedViaKeyboard", d()), x()) : v(), q(), !0;
        case "End":
          return S(), e.expanded ? (l.value === null && f("highlightedViaKeyboard", d()), w()) : v(), q(), !0;
        case "Escape":
          return S(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function $() {
      f("active");
    }
    const I = [], ae = y(void 0), L = zt(
      ae,
      { threshold: 0.8 }
    );
    Y(L, (u) => {
      u && t("load-more");
    });
    function de(u, m) {
      if (u) {
        I[m] = u.$el;
        const v = e.visibleItemLimit;
        if (!v || e.menuItems.length < v)
          return;
        const S = Math.min(
          v,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        m === e.menuItems.length - S && (ae.value = u.$el);
      }
    }
    function q() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || _.value === void 0)
        return;
      const u = _.value >= 0 ? _.value : 0;
      I[u].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const H = y(null), U = y(null);
    function le() {
      if (U.value = null, !e.visibleItemLimit || I.length <= e.visibleItemLimit) {
        H.value = null;
        return;
      }
      const u = I[0], m = I[e.visibleItemLimit];
      if (H.value = ce(
        u,
        m
      ), e.footer) {
        const v = I[I.length - 1];
        U.value = v.scrollHeight;
      }
    }
    function ce(u, m) {
      const v = u.getBoundingClientRect().top;
      return m.getBoundingClientRect().top - v + 2;
    }
    G(() => {
      document.addEventListener("mouseup", $);
    }), Me(() => {
      document.removeEventListener("mouseup", $);
    }), Y(ee(e, "expanded"), (u) => pe(this, null, function* () {
      const m = d();
      !u && l.value && m === void 0 && f("highlighted"), u && m !== void 0 && f("highlighted", m), u && (yield ue(), le(), yield ue(), q());
    })), Y(ee(e, "menuItems"), (u) => pe(this, null, function* () {
      u.length < I.length && (I.length = u.length), e.expanded && (yield ue(), le(), yield ue(), q());
    }), { deep: !0 });
    const he = p(() => ({
      "max-height": H.value ? `${H.value}px` : void 0,
      "overflow-y": H.value ? "scroll" : void 0,
      "margin-bottom": U.value ? `${U.value}px` : void 0
    })), z = p(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!H.value
    })), {
      rootClasses: O,
      rootStyle: oe,
      otherAttrs: fe
    } = re(a, z);
    return {
      listBoxStyle: he,
      rootClasses: O,
      rootStyle: oe,
      otherAttrs: fe,
      assignTemplateRef: de,
      computedMenuItems: o,
      computedShowNoResultsSlot: r,
      highlightedMenuItem: l,
      highlightedViaKeyboard: i,
      activeMenuItem: s,
      handleMenuItemChange: f,
      handleKeyNavigation: K
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
const jt = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Wt = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function Gt(e, t, n, a, o, r) {
  const l = V("cdx-menu-item"), i = V("cdx-progress-bar");
  return xe((c(), g("div", {
    class: A(["cdx-menu", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    C("ul", W({
      class: "cdx-menu__listbox",
      role: "listbox",
      "aria-multiselectable": "false",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (c(), g("li", jt, [
        R(e.$slots, "pending")
      ])) : M("", !0),
      e.computedShowNoResultsSlot ? (c(), g("li", Wt, [
        R(e.$slots, "no-results")
      ])) : M("", !0),
      (c(!0), g(ve, null, qe(e.computedMenuItems, (s, d) => {
        var f, _;
        return c(), B(l, W({
          key: s.value,
          ref_for: !0,
          ref: (b) => e.assignTemplateRef(b, d)
        }, s, {
          selected: s.value === e.selected,
          active: s.value === ((f = e.activeMenuItem) == null ? void 0 : f.value),
          highlighted: s.value === ((_ = e.highlightedMenuItem) == null ? void 0 : _.value),
          "show-thumbnail": e.showThumbnail,
          "bold-label": e.boldLabel,
          "hide-description-overflow": e.hideDescriptionOverflow,
          "search-query": e.searchQuery,
          onChange: (b, w) => e.handleMenuItemChange(b, w && s),
          onClick: (b) => e.$emit("menu-item-click", s)
        }), {
          default: Q(() => {
            var b, w;
            return [
              R(e.$slots, "default", {
                menuItem: s,
                active: s.value === ((b = e.activeMenuItem) == null ? void 0 : b.value) && s.value === ((w = e.highlightedMenuItem) == null ? void 0 : w.value)
              })
            ];
          }),
          _: 2
        }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
      }), 128)),
      e.showPending ? (c(), B(i, {
        key: 2,
        class: "cdx-menu__progress-bar",
        inline: !0
      })) : M("", !0)
    ], 16)
  ], 6)), [
    [He, e.expanded]
  ]);
}
const Jt = /* @__PURE__ */ N(Pt, [["render", Gt]]), Xt = J(lt), Yt = J(ot), Zt = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && Qe(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/button.html#default-icon-only`);
};
function be(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...be(n)) : typeof n == "object" && n && (// HTML tag
    typeof n.type == "string" || // Component
    typeof n.type == "object" ? t.push(n) : n.type !== De && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...be(n.children))));
  return t;
}
const en = (e, t) => {
  if (!e)
    return !1;
  const n = be(e);
  if (n.length !== 1)
    return !1;
  const a = n[0], o = typeof a == "object" && typeof a.type == "object" && "name" in a.type && a.type.name === ne.name, r = typeof a == "object" && a.type === "svg";
  return o || r ? (Zt(t), !0) : !1;
}, tn = E({
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
      validator: Xt
    },
    /**
     * Visual prominence of the button.
     *
     * @values 'normal', 'primary', 'quiet'
     */
    weight: {
      type: String,
      default: "normal",
      validator: Yt
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: a }) {
    const o = y(!1);
    return {
      rootClasses: p(() => {
        var s;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--weight-${e.weight}`]: !0,
          "cdx-button--framed": e.weight !== "quiet",
          "cdx-button--icon-only": en((s = n.default) == null ? void 0 : s.call(n), a),
          "cdx-button--is-active": o.value
        };
      }),
      onClick: (s) => {
        t("click", s);
      },
      setActive: (s) => {
        o.value = s;
      }
    };
  }
});
function nn(e, t, n, a, o, r) {
  return c(), g("button", {
    class: A(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...l) => e.onClick && e.onClick(...l)),
    onKeydown: t[1] || (t[1] = ye((l) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = ye((l) => e.setActive(!1), ["space", "enter"]))
  }, [
    R(e.$slots, "default")
  ], 34);
}
const an = /* @__PURE__ */ N(tn, [["render", nn]]);
function Le(e, t, n) {
  return p({
    get: () => e.value,
    set: (a) => (
      // If eventName is undefined, then 'update:modelValue' must be a valid EventName,
      // but TypeScript's type analysis isn't clever enough to realize that
      t(n || "update:modelValue", a)
    )
  });
}
const ln = J(it), on = J(Te), sn = E({
  name: "CdxTextInput",
  components: { CdxIcon: ne },
  /**
   * We want the input to inherit attributes, not the root element.
   */
  inheritAttrs: !1,
  expose: ["focus"],
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
      validator: ln
    },
    /**
     * `status` attribute of the input.
     *
     * @values 'default', 'error'
     */
    status: {
      type: String,
      default: "default",
      validator: on
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
    "blur"
  ],
  setup(e, { emit: t, attrs: n }) {
    const a = Le(ee(e, "modelValue"), t), o = p(() => e.clearable && !!a.value && !e.disabled), r = p(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": o.value,
      [`cdx-text-input--status-${e.status}`]: !0
    })), {
      rootClasses: l,
      rootStyle: i,
      otherAttrs: s
    } = re(n, r), d = p(() => ({
      "cdx-text-input__input--has-value": !!a.value
    }));
    return {
      wrappedModel: a,
      isClearable: o,
      rootClasses: l,
      rootStyle: i,
      otherAttrs: s,
      inputClasses: d,
      onClear: () => {
        a.value = "";
      },
      onInput: ($) => {
        t("input", $);
      },
      onChange: ($) => {
        t("change", $);
      },
      onKeydown: ($) => {
        ($.key === "Home" || $.key === "End") && !$.ctrlKey && !$.metaKey || t("keydown", $);
      },
      onFocus: ($) => {
        t("focus", $);
      },
      onBlur: ($) => {
        t("blur", $);
      },
      cdxIconClear: Xe
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
    }
  }
});
const un = ["type", "disabled"];
function rn(e, t, n, a, o, r) {
  const l = V("cdx-icon");
  return c(), g("div", {
    class: A(["cdx-text-input", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    xe(C("input", W({
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
    }), null, 16, un), [
      [Ue, e.wrappedModel]
    ]),
    e.startIcon ? (c(), B(l, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : M("", !0),
    e.endIcon ? (c(), B(l, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : M("", !0),
    e.isClearable ? (c(), B(l, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon-vue cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = Ce(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : M("", !0)
  ], 6);
}
const dn = /* @__PURE__ */ N(sn, [["render", rn]]), cn = J(Te), hn = E({
  name: "CdxSearchInput",
  components: {
    CdxButton: an,
    CdxTextInput: dn
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
      validator: cn
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
    "submit-click"
  ],
  setup(e, { emit: t, attrs: n }) {
    const a = Le(ee(e, "modelValue"), t), o = p(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: r,
      rootStyle: l,
      otherAttrs: i
    } = re(n, o);
    return {
      wrappedModel: a,
      rootClasses: r,
      rootStyle: l,
      otherAttrs: i,
      handleSubmit: () => {
        t("submit-click", a.value);
      },
      searchIcon: Ze
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
const fn = { class: "cdx-search-input__input-wrapper" };
function pn(e, t, n, a, o, r) {
  const l = V("cdx-text-input"), i = V("cdx-button");
  return c(), g("div", {
    class: A(["cdx-search-input", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    C("div", fn, [
      j(l, W({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (s) => e.wrappedModel = s),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon,
        status: e.status
      }, e.otherAttrs, {
        onKeydown: ye(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "status", "onKeydown"]),
      R(e.$slots, "default")
    ]),
    e.buttonLabel ? (c(), B(i, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: Q(() => [
        Z(T(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : M("", !0)
  ], 6);
}
const mn = /* @__PURE__ */ N(hn, [["render", pn]]), gn = E({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: ne,
    CdxMenu: Jt,
    CdxSearchInput: mn
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
      default: ut
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
    const { searchResults: o, searchFooterUrl: r, debounceInterval: l } = ze(e), i = y(), s = y(), d = Ve("typeahead-search-menu"), f = y(!1), _ = y(!1), b = y(!1), w = y(!1), x = y(e.initialInputValue), K = y(""), $ = p(() => {
      var h, k;
      return (k = (h = s.value) == null ? void 0 : h.getHighlightedMenuItem()) == null ? void 0 : k.id;
    }), I = y(null), ae = p(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), L = p(
      () => e.searchResults.find(
        (h) => h.value === I.value
      )
    ), de = p(
      () => r.value ? { value: P, url: r.value } : void 0
    ), q = p(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": f.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: H,
      rootStyle: U,
      otherAttrs: le
    } = re(t, q);
    function ce(h) {
      return h;
    }
    const he = p(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      // In case search queries aren't highlighted, default to a bold label.
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let z, O;
    function oe(h, k = !1) {
      L.value && L.value.label !== h && L.value.value !== h && (I.value = null), O !== void 0 && (clearTimeout(O), O = void 0), h === "" ? f.value = !1 : (_.value = !0, a["search-results-pending"] && (O = setTimeout(() => {
        w.value && (f.value = !0), b.value = !0;
      }, rt))), z !== void 0 && (clearTimeout(z), z = void 0);
      const F = () => {
        n("input", h);
      };
      k ? F() : z = setTimeout(() => {
        F();
      }, l.value);
    }
    function fe(h) {
      if (h === P) {
        I.value = null, x.value = K.value;
        return;
      }
      I.value = h, h !== null && (x.value = L.value ? L.value.label || String(L.value.value) : "");
    }
    function u() {
      w.value = !0, (K.value || b.value) && (f.value = !0);
    }
    function m() {
      w.value = !1, f.value = !1;
    }
    function v(h) {
      const _e = h, { id: k } = _e, F = ie(_e, ["id"]);
      if (F.value === P) {
        n("search-result-click", {
          searchResult: null,
          index: o.value.length,
          numberOfResults: o.value.length
        });
        return;
      }
      S(F);
    }
    function S(h) {
      const k = {
        searchResult: h,
        index: o.value.findIndex(
          (F) => F.value === h.value
        ),
        numberOfResults: o.value.length
      };
      n("search-result-click", k);
    }
    function X(h) {
      if (h.value === P) {
        x.value = K.value;
        return;
      }
      x.value = h.value ? h.label || String(h.value) : "";
    }
    function D(h) {
      var k;
      f.value = !1, (k = s.value) == null || k.clearActive(), v(h);
    }
    function Be(h) {
      if (L.value)
        S(L.value), h.stopPropagation(), window.location.assign(L.value.url), h.preventDefault();
      else {
        const k = {
          searchResult: null,
          index: -1,
          numberOfResults: o.value.length
        };
        n("submit", k);
      }
    }
    function Ae(h) {
      if (!s.value || !K.value || h.key === " ")
        return;
      const k = s.value.getHighlightedMenuItem(), F = s.value.getHighlightedViaKeyboard();
      switch (h.key) {
        case "Enter":
          k && (k.value === P && F ? window.location.assign(r.value) : s.value.delegateKeyNavigation(h, !1)), f.value = !1;
          break;
        case "Tab":
          f.value = !1;
          break;
        default:
          s.value.delegateKeyNavigation(h);
          break;
      }
    }
    return G(() => {
      e.initialInputValue && oe(e.initialInputValue, !0);
    }), Y(ee(e, "searchResults"), () => {
      K.value = x.value.trim(), w.value && _.value && K.value.length > 0 && (f.value = !0), O !== void 0 && (clearTimeout(O), O = void 0), _.value = !1, b.value = !1;
    }), {
      form: i,
      menu: s,
      menuId: d,
      highlightedId: $,
      selection: I,
      menuMessageClass: ae,
      footer: de,
      asSearchResult: ce,
      inputValue: x,
      searchQuery: K,
      expanded: f,
      showPending: b,
      rootClasses: H,
      rootStyle: U,
      otherAttrs: le,
      menuConfig: he,
      onUpdateInputValue: oe,
      onUpdateMenuSelection: fe,
      onFocus: u,
      onBlur: m,
      onSearchResultClick: v,
      onSearchResultKeyboardNavigation: X,
      onSearchFooterClick: D,
      onSubmit: Be,
      onKeydown: Ae,
      MenuFooterValue: P,
      articleIcon: Je
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
const vn = ["id", "action"], yn = { class: "cdx-typeahead-search__menu-message__text" }, bn = { class: "cdx-typeahead-search__menu-message__text" }, Cn = ["href", "onClickCapture"], _n = { class: "cdx-typeahead-search__search-footer__text" }, $n = { class: "cdx-typeahead-search__search-footer__query" };
function Sn(e, t, n, a, o, r) {
  const l = V("cdx-icon"), i = V("cdx-menu"), s = V("cdx-search-input");
  return c(), g("div", {
    class: A(["cdx-typeahead-search", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    C("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...d) => e.onSubmit && e.onSubmit(...d))
    }, [
      j(s, W({
        ref: "searchInput",
        modelValue: e.inputValue,
        "onUpdate:modelValue": t[3] || (t[3] = (d) => e.inputValue = d),
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
        default: Q(() => [
          j(i, W({
            id: e.menuId,
            ref: "menu",
            expanded: e.expanded,
            "onUpdate:expanded": t[0] || (t[0] = (d) => e.expanded = d),
            "show-pending": e.showPending,
            selected: e.selection,
            "menu-items": e.searchResults,
            footer: e.footer,
            "search-query": e.highlightQuery ? e.searchQuery : "",
            "show-no-results-slot": e.searchQuery.length > 0 && e.searchResults.length === 0 && e.$slots["search-no-results-text"] && e.$slots["search-no-results-text"]().length > 0
          }, e.menuConfig, {
            "aria-label": e.searchResultsLabel,
            "onUpdate:selected": e.onUpdateMenuSelection,
            onMenuItemClick: t[1] || (t[1] = (d) => e.onSearchResultClick(e.asSearchResult(d))),
            onMenuItemKeyboardNavigation: e.onSearchResultKeyboardNavigation,
            onLoadMore: t[2] || (t[2] = (d) => e.$emit("load-more"))
          }), {
            pending: Q(() => [
              C("div", {
                class: A(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                C("span", yn, [
                  R(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": Q(() => [
              C("div", {
                class: A(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                C("span", bn, [
                  R(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: Q(({ menuItem: d, active: f }) => [
              d.value === e.MenuFooterValue ? (c(), g("a", {
                key: 0,
                class: A(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": f
                }]),
                href: e.asSearchResult(d).url,
                onClickCapture: Ce((_) => e.onSearchFooterClick(e.asSearchResult(d)), ["stop"])
              }, [
                j(l, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                C("span", _n, [
                  R(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    C("strong", $n, T(e.searchQuery), 1)
                  ])
                ])
              ], 42, Cn)) : M("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      R(e.$slots, "default")
    ], 40, vn)
  ], 6);
}
const kn = /* @__PURE__ */ N(gn, [["render", Sn]]);
export {
  kn as CdxTypeaheadSearch
};
