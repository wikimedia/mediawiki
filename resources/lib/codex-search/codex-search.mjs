var Ae = Object.defineProperty, Ke = Object.defineProperties;
var Re = Object.getOwnPropertyDescriptors;
var ie = Object.getOwnPropertySymbols;
var Se = Object.prototype.hasOwnProperty, _e = Object.prototype.propertyIsEnumerable;
var Ie = (e, t, n) => t in e ? Ae(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, we = (e, t) => {
  for (var n in t || (t = {}))
    Se.call(t, n) && Ie(e, n, t[n]);
  if (ie)
    for (var n of ie(t))
      _e.call(t, n) && Ie(e, n, t[n]);
  return e;
}, ke = (e, t) => Ke(e, Re(t));
var X = (e, t) => {
  var n = {};
  for (var a in e)
    Se.call(e, a) && t.indexOf(a) < 0 && (n[a] = e[a]);
  if (e != null && ie)
    for (var a of ie(e))
      t.indexOf(a) < 0 && _e.call(e, a) && (n[a] = e[a]);
  return n;
};
var pe = (e, t, n) => new Promise((a, s) => {
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
import { ref as b, onMounted as W, defineComponent as D, computed as h, openBlock as f, createElementBlock as y, normalizeClass as A, toDisplayString as V, createCommentVNode as w, resolveComponent as B, createVNode as j, Transition as De, withCtx as q, normalizeStyle as te, createElementVNode as I, createTextVNode as ee, withModifiers as Ce, renderSlot as R, createBlock as L, resolveDynamicComponent as Ee, Fragment as ve, getCurrentInstance as Fe, onUnmounted as xe, watch as Y, toRef as H, nextTick as ue, withDirectives as Me, mergeProps as P, renderList as Ne, vShow as Oe, Comment as qe, warn as He, withKeys as ye, inject as re, vModelDynamic as ze } from "vue";
const Ue = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', Qe = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', je = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Pe = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', We = Ue, Ge = Qe, Ze = je, Je = Pe;
function Xe(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const a = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof a == "string" || "path" in a ? a : a.ltr;
}
function Ye(e, t) {
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
  const t = b(null);
  return W(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function tt(e) {
  const t = b("");
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
  emits: ["click"],
  setup(e, { emit: t }) {
    const n = b(), a = et(n), s = tt(n), u = h(() => e.dir || a.value), o = h(() => e.lang || s.value), i = h(() => ({
      "cdx-icon--flipped": u.value === "rtl" && o.value !== null && Ye(e.icon, o.value),
      [`cdx-icon--${e.size}`]: !0
    })), l = h(
      () => Xe(e.icon, o.value || "", u.value || "ltr")
    ), r = h(() => typeof l.value == "string" ? l.value : ""), m = h(() => typeof l.value != "string" ? l.value.path : "");
    return {
      rootElement: n,
      rootClasses: i,
      iconSvg: r,
      iconPath: m,
      onClick: (g) => {
        t("click", g);
      }
    };
  }
});
const E = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [a, s] of t)
    n[a] = s;
  return n;
}, mt = ["aria-hidden"], gt = { key: 0 }, vt = ["innerHTML"], yt = ["d"];
function bt(e, t, n, a, s, u) {
  return f(), y("span", {
    ref: "rootElement",
    class: A(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    (f(), y("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": e.iconLabel ? void 0 : !0
    }, [
      e.iconLabel ? (f(), y("title", gt, V(e.iconLabel), 1)) : w("", !0),
      e.iconSvg ? (f(), y("g", {
        key: 1,
        innerHTML: e.iconSvg
      }, null, 8, vt)) : (f(), y("path", {
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
      default: Ze
    }
  },
  setup: (e) => {
    const t = b(!1), n = b({}), a = (s) => {
      const u = s.replace(/([\\"\n])/g, "\\$1"), o = new Image();
      o.onload = () => {
        n.value = { backgroundImage: `url("${u}")` }, t.value = !0;
      }, o.onerror = () => {
        t.value = !1;
      }, o.src = u;
    };
    return W(() => {
      var s;
      (s = e.thumbnail) != null && s.url && a(e.thumbnail.url);
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
function St(e, t, n, a, s, u) {
  const o = B("cdx-icon");
  return f(), y("span", $t, [
    e.thumbnailLoaded ? w("", !0) : (f(), y("span", It, [
      j(o, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon--vue"
      }, null, 8, ["icon"])
    ])),
    j(De, { name: "cdx-thumbnail__image" }, {
      default: q(() => [
        e.thumbnailLoaded ? (f(), y("span", {
          key: 0,
          style: te(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : w("", !0)
      ]),
      _: 1
    })
  ]);
}
const _t = /* @__PURE__ */ E(Ct, [["render", St]]);
function wt(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const kt = "[̀-ͯ҃-҉֑-ׇֽֿׁׂׅׄؐ-ًؚ-ٰٟۖ-ۜ۟-۪ۤۧۨ-ܑۭܰ-݊ަ-ް߫-߽߳ࠖ-࠙ࠛ-ࠣࠥ-ࠧࠩ-࡙࠭-࡛࣓-ࣣ࣡-ःऺ-़ा-ॏ॑-ॗॢॣঁ-ঃ়া-ৄেৈো-্ৗৢৣ৾ਁ-ਃ਼ਾ-ੂੇੈੋ-੍ੑੰੱੵઁ-ઃ઼ા-ૅે-ૉો-્ૢૣૺ-૿ଁ-ଃ଼ା-ୄେୈୋ-୍ୖୗୢୣஂா-ூெ-ைொ-்ௗఀ-ఄా-ౄె-ైొ-్ౕౖౢౣಁ-ಃ಼ಾ-ೄೆ-ೈೊ-್ೕೖೢೣഀ-ഃ഻഼ാ-ൄെ-ൈൊ-്ൗൢൣංඃ්ා-ුූෘ-ෟෲෳัิ-ฺ็-๎ັິ-ູົຼ່-ໍ༹༘༙༵༷༾༿ཱ-྄྆྇ྍ-ྗྙ-ྼ࿆ါ-ှၖ-ၙၞ-ၠၢ-ၤၧ-ၭၱ-ၴႂ-ႍႏႚ-ႝ፝-፟ᜒ-᜔ᜲ-᜴ᝒᝓᝲᝳ឴-៓៝᠋-᠍ᢅᢆᢩᤠ-ᤫᤰ-᤻ᨗ-ᨛᩕ-ᩞ᩠-᩿᩼᪰-᪾ᬀ-ᬄ᬴-᭄᭫-᭳ᮀ-ᮂᮡ-ᮭ᯦-᯳ᰤ-᰷᳐-᳔᳒-᳨᳭ᳲ-᳴᳷-᳹᷀-᷹᷻-᷿⃐-⃰⳯-⵿⳱ⷠ-〪ⷿ-゙゚〯꙯-꙲ꙴ-꙽ꚞꚟ꛰꛱ꠂ꠆ꠋꠣ-ꠧꢀꢁꢴ-ꣅ꣠-꣱ꣿꤦ-꤭ꥇ-꥓ꦀ-ꦃ꦳-꧀ꧥꨩ-ꨶꩃꩌꩍꩻ-ꩽꪰꪲ-ꪴꪷꪸꪾ꪿꫁ꫫ-ꫯꫵ꫶ꯣ-ꯪ꯬꯭ﬞ︀-️︠-︯]";
function xt(e, t) {
  if (!e)
    return [t, "", ""];
  const n = wt(e), a = new RegExp(
    // Per https://www.regular-expressions.info/unicode.html, "any code point that is not a
    // combining mark can be followed by any number of combining marks." See also the
    // discussion in https://phabricator.wikimedia.org/T35242.
    n + kt + "*",
    "i"
  ).exec(t);
  if (!a || a.index === void 0)
    return [t, "", ""];
  const s = a.index, u = s + a[0].length, o = t.slice(s, u), i = t.slice(0, s), l = t.slice(u, t.length);
  return [i, o, l];
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
    titleChunks: h(() => xt(e.searchQuery, String(e.title)))
  })
});
const Tt = { class: "cdx-search-result-title" }, Vt = { class: "cdx-search-result-title__match" };
function Bt(e, t, n, a, s, u) {
  return f(), y("span", Tt, [
    I("bdi", null, [
      ee(V(e.titleChunks[0]), 1),
      I("span", Vt, V(e.titleChunks[1]), 1),
      ee(V(e.titleChunks[2]), 1)
    ])
  ]);
}
const Lt = /* @__PURE__ */ E(Mt, [["render", Bt]]), At = D({
  name: "CdxMenuItem",
  components: { CdxIcon: ne, CdxThumbnail: _t, CdxSearchResultTitle: Lt },
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
    }, o = h(() => e.searchQuery.length > 0), i = h(() => ({
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
    })), l = h(() => e.url ? "a" : "span"), r = h(() => e.label || String(e.value));
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
const Kt = ["id", "aria-disabled", "aria-selected"], Rt = { class: "cdx-menu-item__text" }, Dt = ["lang"], Et = ["lang"], Ft = ["lang"], Nt = ["lang"];
function Ot(e, t, n, a, s, u) {
  const o = B("cdx-thumbnail"), i = B("cdx-icon"), l = B("cdx-search-result-title");
  return f(), y("li", {
    id: e.id,
    role: "option",
    class: A(["cdx-menu-item", e.rootClasses]),
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
          var r, m, C, g, k, x;
          return [
            e.showThumbnail ? (f(), L(o, {
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
              }, null, 8, ["title", "search-query", "lang"])) : (f(), y("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (m = e.language) == null ? void 0 : m.label
              }, [
                I("bdi", null, V(e.title), 1)
              ], 8, Dt)),
              e.match ? (f(), y(ve, { key: 2 }, [
                ee(V(" ") + " "),
                e.highlightQuery ? (f(), L(l, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: (C = e.language) == null ? void 0 : C.match
                }, null, 8, ["title", "search-query", "lang"])) : (f(), y("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (g = e.language) == null ? void 0 : g.match
                }, [
                  I("bdi", null, V(e.match), 1)
                ], 8, Et))
              ], 64)) : w("", !0),
              e.supportingText ? (f(), y(ve, { key: 3 }, [
                ee(V(" ") + " "),
                I("span", {
                  class: "cdx-menu-item__text__supporting-text",
                  lang: (k = e.language) == null ? void 0 : k.supportingText
                }, [
                  I("bdi", null, V(e.supportingText), 1)
                ], 8, Ft)
              ], 64)) : w("", !0),
              e.description ? (f(), y("span", {
                key: 4,
                class: "cdx-menu-item__text__description",
                lang: (x = e.language) == null ? void 0 : x.description
              }, [
                I("bdi", null, V(e.description), 1)
              ], 8, Nt)) : w("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, Kt);
}
const qt = /* @__PURE__ */ E(At, [["render", Ot]]), Ht = D({
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
const zt = ["aria-disabled"], Ut = /* @__PURE__ */ I("div", { class: "cdx-progress-bar__bar" }, null, -1), Qt = [
  Ut
];
function jt(e, t, n, a, s, u) {
  return f(), y("div", {
    class: A(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Qt, 10, zt);
}
const Pt = /* @__PURE__ */ E(Ht, [["render", jt]]);
let ge = 0;
function Ve(e) {
  const t = Fe(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${me}-${e}-${ge++}` : n ? `${me}-${n}-${ge++}` : `${me}-${ge++}`;
}
function Wt(e, t) {
  const n = b(!1);
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
  return W(() => {
    a = !0, e.value && s.observe(e.value);
  }), xe(() => {
    a = !1, s.disconnect();
  }), Y(e, (u) => {
    a && (s.disconnect(), n.value = !1, u && s.observe(u));
  }), n;
}
function de(e, t = h(() => ({}))) {
  const n = h(() => {
    const u = X(t.value, []);
    return e.class && e.class.split(" ").forEach((i) => {
      u[i] = !0;
    }), u;
  }), a = h(() => {
    if ("style" in e)
      return e.style;
  }), s = h(() => {
    const l = e, { class: u, style: o } = l;
    return X(l, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: a,
    otherAttrs: s
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
    const s = h(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((p) => ke(we({}, p), {
      id: Ve("menu-item")
    }))), u = h(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : s.value.length === 0 : !1), o = b(null), i = b(!1), l = b(null);
    function r() {
      return s.value.find(
        (d) => d.value === e.selected
      );
    }
    function m(d, p) {
      var $;
      if (!(p && p.disabled))
        switch (d) {
          case "selected":
            t("update:selected", ($ = p == null ? void 0 : p.value) != null ? $ : null), t("update:expanded", !1), l.value = null;
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
    const C = h(() => {
      if (o.value !== null)
        return s.value.findIndex(
          (d) => (
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            d.value === o.value.value
          )
        );
    });
    function g(d) {
      d && (m("highlightedViaKeyboard", d), t("menu-item-keyboard-navigation", d));
    }
    function k(d) {
      var S;
      const p = (J) => {
        for (let c = J - 1; c >= 0; c--)
          if (!s.value[c].disabled)
            return s.value[c];
      };
      d = d || s.value.length;
      const $ = (S = p(d)) != null ? S : p(s.value.length);
      g($);
    }
    function x(d) {
      const p = (S) => s.value.find((J, c) => !J.disabled && c > S);
      d = d != null ? d : -1;
      const $ = p(d) || p(-1);
      g($);
    }
    function G(d, p = !0) {
      function $() {
        t("update:expanded", !0), m("highlighted", r());
      }
      function S() {
        p && (d.preventDefault(), d.stopPropagation());
      }
      switch (d.key) {
        case "Enter":
        case " ":
          return S(), e.expanded ? (o.value && i.value && t("update:selected", o.value.value), t("update:expanded", !1)) : $(), !0;
        case "Tab":
          return e.expanded && (o.value && i.value && t("update:selected", o.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return S(), e.expanded ? (o.value === null && m("highlightedViaKeyboard", r()), k(C.value)) : $(), F(), !0;
        case "ArrowDown":
          return S(), e.expanded ? (o.value === null && m("highlightedViaKeyboard", r()), x(C.value)) : $(), F(), !0;
        case "Home":
          return S(), e.expanded ? (o.value === null && m("highlightedViaKeyboard", r()), x()) : $(), F(), !0;
        case "End":
          return S(), e.expanded ? (o.value === null && m("highlightedViaKeyboard", r()), k()) : $(), F(), !0;
        case "Escape":
          return S(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function M() {
      m("active");
    }
    const T = [], Z = b(void 0), ae = Wt(
      Z,
      { threshold: 0.8 }
    );
    Y(ae, (d) => {
      d && t("load-more");
    });
    function le(d, p) {
      if (d) {
        T[p] = d.$el;
        const $ = e.visibleItemLimit;
        if (!$ || e.menuItems.length < $)
          return;
        const S = Math.min(
          $,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        p === e.menuItems.length - S && (Z.value = d.$el);
      }
    }
    function F() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || C.value === void 0)
        return;
      const d = C.value >= 0 ? C.value : 0;
      T[d].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const v = b(null), z = b(null);
    function K() {
      if (z.value = null, !e.visibleItemLimit || T.length <= e.visibleItemLimit) {
        v.value = null;
        return;
      }
      const d = T[0], p = T[e.visibleItemLimit];
      if (v.value = N(
        d,
        p
      ), e.footer) {
        const $ = T[T.length - 1];
        z.value = $.scrollHeight;
      }
    }
    function N(d, p) {
      const $ = d.getBoundingClientRect().top;
      return p.getBoundingClientRect().top - $ + 2;
    }
    W(() => {
      document.addEventListener("mouseup", M);
    }), xe(() => {
      document.removeEventListener("mouseup", M);
    }), Y(H(e, "expanded"), (d) => pe(this, null, function* () {
      const p = r();
      !d && o.value && p === void 0 && m("highlighted"), d && p !== void 0 && m("highlighted", p), d && (yield ue(), K(), yield ue(), F());
    })), Y(H(e, "menuItems"), (d) => pe(this, null, function* () {
      d.length < T.length && (T.length = d.length), e.expanded && (yield ue(), K(), yield ue(), F());
    }), { deep: !0 });
    const oe = h(() => ({
      "max-height": v.value ? `${v.value}px` : void 0,
      "overflow-y": v.value ? "scroll" : void 0,
      "margin-bottom": z.value ? `${z.value}px` : void 0
    })), ce = h(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!v.value
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
      computedMenuItems: s,
      computedShowNoResultsSlot: u,
      highlightedMenuItem: o,
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
const Zt = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Jt = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function Xt(e, t, n, a, s, u) {
  const o = B("cdx-menu-item"), i = B("cdx-progress-bar");
  return Me((f(), y("div", {
    class: A(["cdx-menu", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    I("ul", P({
      class: "cdx-menu__listbox",
      role: "listbox",
      "aria-multiselectable": "false",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (f(), y("li", Zt, [
        R(e.$slots, "pending")
      ])) : w("", !0),
      e.computedShowNoResultsSlot ? (f(), y("li", Jt, [
        R(e.$slots, "no-results")
      ])) : w("", !0),
      (f(!0), y(ve, null, Ne(e.computedMenuItems, (l, r) => {
        var m, C;
        return f(), L(o, P({
          key: l.value,
          ref_for: !0,
          ref: (g) => e.assignTemplateRef(g, r)
        }, l, {
          selected: l.value === e.selected,
          active: l.value === ((m = e.activeMenuItem) == null ? void 0 : m.value),
          highlighted: l.value === ((C = e.highlightedMenuItem) == null ? void 0 : C.value),
          "show-thumbnail": e.showThumbnail,
          "bold-label": e.boldLabel,
          "hide-description-overflow": e.hideDescriptionOverflow,
          "search-query": e.searchQuery,
          onChange: (g, k) => e.handleMenuItemChange(g, k && l),
          onClick: (g) => e.$emit("menu-item-click", l)
        }), {
          default: q(() => {
            var g, k;
            return [
              R(e.$slots, "default", {
                menuItem: l,
                active: l.value === ((g = e.activeMenuItem) == null ? void 0 : g.value) && l.value === ((k = e.highlightedMenuItem) == null ? void 0 : k.value)
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
const Yt = /* @__PURE__ */ E(Gt, [["render", Xt]]), en = U(nt), tn = U(at), nn = U(lt), an = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && He(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
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
  const a = n[0], s = typeof a == "object" && typeof a.type == "object" && "name" in a.type && a.type.name === ne.name, u = typeof a == "object" && a.type === "svg";
  return s || u ? (an(t), !0) : !1;
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
    const s = b(!1);
    return {
      rootClasses: h(() => {
        var l;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--weight-${e.weight}`]: !0,
          [`cdx-button--size-${e.size}`]: !0,
          "cdx-button--framed": e.weight !== "quiet",
          "cdx-button--icon-only": ln((l = n.default) == null ? void 0 : l.call(n), a),
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
function sn(e, t, n, a, s, u) {
  return f(), y("button", {
    class: A(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o)),
    onKeydown: t[1] || (t[1] = ye((o) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = ye((o) => e.setActive(!1), ["space", "enter"]))
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
  const t = re(ht, b(!1));
  return h(() => t.value || e.value);
}
function Le(e, t, n) {
  const a = rn(e), s = re(ct, b("default")), u = h(() => t != null && t.value && t.value !== "default" ? t.value : s.value), o = re(rt, void 0), i = h(() => o || n);
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
      computedDisabled: s,
      computedStatus: u,
      computedInputId: o
    } = Le(
      H(e, "disabled"),
      H(e, "status"),
      a
    ), i = re(dt, void 0), l = Be(H(e, "modelValue"), t), r = h(() => e.clearable && !!l.value && !s.value), m = h(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": r.value,
      [`cdx-text-input--status-${u.value}`]: !0
    })), {
      rootClasses: C,
      rootStyle: g,
      otherAttrs: k
    } = de(n, m), x = h(() => {
      const K = k.value, { id: v } = K;
      return X(K, ["id"]);
    }), G = h(() => ({
      "cdx-text-input__input--has-value": !!l.value
    }));
    return {
      computedInputId: o,
      descriptionId: i,
      wrappedModel: l,
      isClearable: r,
      rootClasses: C,
      rootStyle: g,
      otherAttrsMinusId: x,
      inputClasses: G,
      computedDisabled: s,
      onClear: (v) => {
        l.value = "", t("clear", v);
      },
      onInput: (v) => {
        t("input", v);
      },
      onChange: (v) => {
        t("change", v);
      },
      onKeydown: (v) => {
        (v.key === "Home" || v.key === "End") && !v.ctrlKey && !v.metaKey || t("keydown", v);
      },
      onFocus: (v) => {
        t("focus", v);
      },
      onBlur: (v) => {
        t("blur", v);
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
function pn(e, t, n, a, s, u) {
  const o = B("cdx-icon");
  return f(), y("div", {
    class: A(["cdx-text-input", e.rootClasses]),
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
      [ze, e.wrappedModel]
    ]),
    e.startIcon ? (f(), L(o, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.endIcon ? (f(), L(o, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.isClearable ? (f(), L(o, {
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
    const a = Be(H(e, "modelValue"), t), { computedDisabled: s } = Le(H(e, "disabled")), u = h(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: o,
      rootStyle: i,
      otherAttrs: l
    } = de(n, u);
    return {
      wrappedModel: a,
      computedDisabled: s,
      rootClasses: o,
      rootStyle: i,
      otherAttrs: l,
      handleSubmit: () => {
        t("submit-click", a.value);
      },
      searchIcon: Je
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
function bn(e, t, n, a, s, u) {
  const o = B("cdx-text-input"), i = B("cdx-button");
  return f(), y("div", {
    class: A(["cdx-search-input", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    I("div", yn, [
      j(o, P({
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
    CdxMenu: Yt,
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
    const s = b(), u = b(), o = Ve("typeahead-search-menu"), i = b(!1), l = b(!1), r = b(!1), m = b(!1), C = b(e.initialInputValue), g = b(""), k = h(() => {
      var c, _;
      return (_ = (c = u.value) == null ? void 0 : c.getHighlightedMenuItem()) == null ? void 0 : _.id;
    }), x = b(null), G = h(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), M = h(
      () => e.searchResults.find(
        (c) => c.value === x.value
      )
    ), T = h(
      () => e.searchFooterUrl ? { value: Q, url: e.searchFooterUrl } : void 0
    ), Z = h(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": i.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: ae,
      rootStyle: le,
      otherAttrs: F
    } = de(t, Z);
    function v(c) {
      return c;
    }
    const z = h(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      // In case search queries aren't highlighted, default to a bold label.
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let K, N;
    function oe(c, _ = !1) {
      M.value && M.value.label !== c && M.value.value !== c && (x.value = null), N !== void 0 && (clearTimeout(N), N = void 0), c === "" ? i.value = !1 : (l.value = !0, a["search-results-pending"] && (N = setTimeout(() => {
        m.value && (i.value = !0), r.value = !0;
      }, ut))), K !== void 0 && (clearTimeout(K), K = void 0);
      const O = () => {
        n("input", c);
      };
      _ ? O() : K = setTimeout(() => {
        O();
      }, e.debounceInterval);
    }
    function ce(c) {
      if (c === Q) {
        x.value = null, C.value = g.value;
        return;
      }
      x.value = c, c !== null && (C.value = M.value ? M.value.label || String(M.value.value) : "");
    }
    function he() {
      m.value = !0, (g.value || r.value) && (i.value = !0);
    }
    function fe() {
      m.value = !1, i.value = !1;
    }
    function se(c) {
      const $e = c, { id: _ } = $e, O = X($e, ["id"]);
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
      const _ = {
        searchResult: c,
        index: e.searchResults.findIndex(
          (O) => O.value === c.value
        ),
        numberOfResults: e.searchResults.length
      };
      n("search-result-click", _);
    }
    function p(c) {
      if (c.value === Q) {
        C.value = g.value;
        return;
      }
      C.value = c.value ? c.label || String(c.value) : "";
    }
    function $(c) {
      var _;
      i.value = !1, (_ = u.value) == null || _.clearActive(), se(c);
    }
    function S(c) {
      if (M.value)
        d(M.value), c.stopPropagation(), window.location.assign(M.value.url), c.preventDefault();
      else {
        const _ = {
          searchResult: null,
          index: -1,
          numberOfResults: e.searchResults.length
        };
        n("submit", _);
      }
    }
    function J(c) {
      if (!u.value || !g.value || c.key === " ")
        return;
      const _ = u.value.getHighlightedMenuItem(), O = u.value.getHighlightedViaKeyboard();
      switch (c.key) {
        case "Enter":
          _ && (_.value === Q && O ? window.location.assign(e.searchFooterUrl) : u.value.delegateKeyNavigation(c, !1)), i.value = !1;
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
    }), Y(H(e, "searchResults"), () => {
      g.value = C.value.trim(), m.value && l.value && g.value.length > 0 && (i.value = !0), N !== void 0 && (clearTimeout(N), N = void 0), l.value = !1, r.value = !1;
    }), {
      form: s,
      menu: u,
      menuId: o,
      highlightedId: k,
      selection: x,
      menuMessageClass: G,
      footer: T,
      asSearchResult: v,
      inputValue: C,
      searchQuery: g,
      expanded: i,
      showPending: r,
      rootClasses: ae,
      rootStyle: le,
      otherAttrs: F,
      menuConfig: z,
      onUpdateInputValue: oe,
      onUpdateMenuSelection: ce,
      onFocus: he,
      onBlur: fe,
      onSearchResultClick: se,
      onSearchResultKeyboardNavigation: p,
      onSearchFooterClick: $,
      onSubmit: S,
      onKeydown: J,
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
const In = ["id", "action"], Sn = { class: "cdx-typeahead-search__menu-message__text" }, _n = { class: "cdx-typeahead-search__menu-message__text" }, wn = ["href", "onClickCapture"], kn = { class: "cdx-menu-item__text cdx-typeahead-search__search-footer__text" }, xn = { class: "cdx-typeahead-search__search-footer__query" };
function Mn(e, t, n, a, s, u) {
  const o = B("cdx-icon"), i = B("cdx-menu"), l = B("cdx-search-input");
  return f(), y("div", {
    class: A(["cdx-typeahead-search", e.rootClasses]),
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
        "aria-owns": e.menuId,
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
                class: A(["cdx-menu-item__content cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                I("span", Sn, [
                  R(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": q(() => [
              I("div", {
                class: A(["cdx-menu-item__content cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                I("span", _n, [
                  R(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: q(({ menuItem: r, active: m }) => [
              r.value === e.MenuFooterValue ? (f(), y("a", {
                key: 0,
                class: A(["cdx-menu-item__content cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": m
                }]),
                href: e.asSearchResult(r).url,
                onClickCapture: Ce((C) => e.onSearchFooterClick(e.asSearchResult(r)), ["stop"])
              }, [
                j(o, {
                  class: "cdx-menu-item__thumbnail cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                I("span", kn, [
                  R(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    I("strong", xn, V(e.searchQuery), 1)
                  ])
                ])
              ], 42, wn)) : w("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      R(e.$slots, "default")
    ], 40, In)
  ], 6);
}
const Bn = /* @__PURE__ */ E($n, [["render", Mn]]);
export {
  Bn as CdxTypeaheadSearch
};
