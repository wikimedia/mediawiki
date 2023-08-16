var Re = Object.defineProperty, De = Object.defineProperties;
var Ee = Object.getOwnPropertyDescriptors;
var re = Object.getOwnPropertySymbols;
var we = Object.prototype.hasOwnProperty, xe = Object.prototype.propertyIsEnumerable;
var Se = (e, t, n) => t in e ? Re(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, ke = (e, t) => {
  for (var n in t || (t = {}))
    we.call(t, n) && Se(e, n, t[n]);
  if (re)
    for (var n of re(t))
      xe.call(t, n) && Se(e, n, t[n]);
  return e;
}, Me = (e, t) => De(e, Ee(t));
var Z = (e, t) => {
  var n = {};
  for (var l in e)
    we.call(e, l) && t.indexOf(l) < 0 && (n[l] = e[l]);
  if (e != null && re)
    for (var l of re(e))
      t.indexOf(l) < 0 && xe.call(e, l) && (n[l] = e[l]);
  return n;
};
var ve = (e, t, n) => new Promise((l, s) => {
  var r = (o) => {
    try {
      u(n.next(o));
    } catch (d) {
      s(d);
    }
  }, i = (o) => {
    try {
      u(n.throw(o));
    } catch (d) {
      s(d);
    }
  }, u = (o) => o.done ? l(o.value) : Promise.resolve(o.value).then(r, i);
  u((n = n.apply(e, t)).next());
});
import { ref as v, onMounted as W, defineComponent as R, computed as h, openBlock as f, createElementBlock as g, normalizeClass as L, toDisplayString as T, createCommentVNode as w, resolveComponent as B, createVNode as j, Transition as Ne, withCtx as O, normalizeStyle as ne, createElementVNode as _, createTextVNode as te, withModifiers as _e, renderSlot as A, createBlock as V, resolveDynamicComponent as Fe, Fragment as Ce, getCurrentInstance as Oe, onUnmounted as Te, watch as ee, toRef as q, nextTick as de, withDirectives as Be, mergeProps as P, renderList as qe, vShow as ze, Comment as He, warn as Ue, withKeys as $e, inject as ce, vModelDynamic as Qe } from "vue";
const je = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', Pe = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', We = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Ge = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', Je = je, Xe = Pe, Ye = We, Ze = Ge;
function et(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const l = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof l == "string" || "path" in l ? l : l.ltr;
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
  const t = v(null);
  return W(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function at(e) {
  const t = v("");
  return W(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
function z(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const ye = "cdx", lt = [
  "default",
  "progressive",
  "destructive"
], ot = [
  "normal",
  "primary",
  "quiet"
], st = [
  "medium",
  "large"
], it = [
  "x-small",
  "small",
  "medium"
], ut = [
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
], Ve = [
  "default",
  "error"
], rt = 120, dt = 500, Q = "cdx-menu-footer-item", ct = Symbol("CdxId"), ht = Symbol("CdxDescriptionId"), ft = Symbol("CdxStatus"), pt = Symbol("CdxDisabled"), mt = z(it), gt = R({
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
      validator: mt
    }
  },
  setup(e) {
    const t = v(), n = nt(t), l = at(t), s = h(() => e.dir || n.value), r = h(() => e.lang || l.value), i = h(() => ({
      "cdx-icon--flipped": s.value === "rtl" && r.value !== null && tt(e.icon, r.value),
      [`cdx-icon--${e.size}`]: !0
    })), u = h(
      () => et(e.icon, r.value || "", s.value || "ltr")
    ), o = h(() => typeof u.value == "string" ? u.value : ""), d = h(() => typeof u.value != "string" ? u.value.path : "");
    return {
      rootElement: t,
      rootClasses: i,
      iconSvg: o,
      iconPath: d
    };
  }
});
const D = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [l, s] of t)
    n[l] = s;
  return n;
}, vt = ["aria-hidden"], yt = { key: 0 }, bt = ["innerHTML"], Ct = ["d"];
function $t(e, t, n, l, s, r) {
  return f(), g("span", {
    ref: "rootElement",
    class: L(["cdx-icon", e.rootClasses])
  }, [
    (f(), g("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      "xmlns:xlink": "http://www.w3.org/1999/xlink",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": e.iconLabel ? void 0 : !0
    }, [
      e.iconLabel ? (f(), g("title", yt, T(e.iconLabel), 1)) : w("", !0),
      e.iconSvg ? (f(), g("g", {
        key: 1,
        innerHTML: e.iconSvg
      }, null, 8, bt)) : (f(), g("path", {
        key: 2,
        d: e.iconPath
      }, null, 8, Ct))
    ], 8, vt))
  ], 2);
}
const ae = /* @__PURE__ */ D(gt, [["render", $t]]), It = R({
  name: "CdxThumbnail",
  components: { CdxIcon: ae },
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
    const t = v(!1), n = v({}), l = (s) => {
      const r = s.replace(/([\\"\n])/g, "\\$1"), i = new Image();
      i.onload = () => {
        n.value = { backgroundImage: `url("${r}")` }, t.value = !0;
      }, i.onerror = () => {
        t.value = !1;
      }, i.src = r;
    };
    return W(() => {
      var s;
      (s = e.thumbnail) != null && s.url && l(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const _t = { class: "cdx-thumbnail" }, St = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function wt(e, t, n, l, s, r) {
  const i = B("cdx-icon");
  return f(), g("span", _t, [
    e.thumbnailLoaded ? w("", !0) : (f(), g("span", St, [
      j(i, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon--vue"
      }, null, 8, ["icon"])
    ])),
    j(Ne, { name: "cdx-thumbnail__image" }, {
      default: O(() => [
        e.thumbnailLoaded ? (f(), g("span", {
          key: 0,
          style: ne(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : w("", !0)
      ]),
      _: 1
    })
  ]);
}
const xt = /* @__PURE__ */ D(It, [["render", wt]]);
function kt(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const Mt = "[̀-ͯ҃-҉֑-ׇֽֿׁׂׅׄؐ-ًؚ-ٰٟۖ-ۜ۟-۪ۤۧۨ-ܑۭܰ-݊ަ-ް߫-߽߳ࠖ-࠙ࠛ-ࠣࠥ-ࠧࠩ-࡙࠭-࡛࣓-ࣣ࣡-ःऺ-़ा-ॏ॑-ॗॢॣঁ-ঃ়া-ৄেৈো-্ৗৢৣ৾ਁ-ਃ਼ਾ-ੂੇੈੋ-੍ੑੰੱੵઁ-ઃ઼ા-ૅે-ૉો-્ૢૣૺ-૿ଁ-ଃ଼ା-ୄେୈୋ-୍ୖୗୢୣஂா-ூெ-ைொ-்ௗఀ-ఄా-ౄె-ైొ-్ౕౖౢౣಁ-ಃ಼ಾ-ೄೆ-ೈೊ-್ೕೖೢೣഀ-ഃ഻഼ാ-ൄെ-ൈൊ-്ൗൢൣංඃ්ා-ුූෘ-ෟෲෳัิ-ฺ็-๎ັິ-ູົຼ່-ໍ༹༘༙༵༷༾༿ཱ-྄྆྇ྍ-ྗྙ-ྼ࿆ါ-ှၖ-ၙၞ-ၠၢ-ၤၧ-ၭၱ-ၴႂ-ႍႏႚ-ႝ፝-፟ᜒ-᜔ᜲ-᜴ᝒᝓᝲᝳ឴-៓៝᠋-᠍ᢅᢆᢩᤠ-ᤫᤰ-᤻ᨗ-ᨛᩕ-ᩞ᩠-᩿᩼᪰-᪾ᬀ-ᬄ᬴-᭄᭫-᭳ᮀ-ᮂᮡ-ᮭ᯦-᯳ᰤ-᰷᳐-᳔᳒-᳨᳭ᳲ-᳴᳷-᳹᷀-᷹᷻-᷿⃐-⃰⳯-⵿⳱ⷠ-〪ⷿ-゙゚〯꙯-꙲ꙴ-꙽ꚞꚟ꛰꛱ꠂ꠆ꠋꠣ-ꠧꢀꢁꢴ-ꣅ꣠-꣱ꣿꤦ-꤭ꥇ-꥓ꦀ-ꦃ꦳-꧀ꧥꨩ-ꨶꩃꩌꩍꩻ-ꩽꪰꪲ-ꪴꪷꪸꪾ꪿꫁ꫫ-ꫯꫵ꫶ꯣ-ꯪ꯬꯭ﬞ︀-️︠-︯]";
function Tt(e, t) {
  if (!e)
    return [t, "", ""];
  const n = kt(e), l = new RegExp(
    // Per https://www.regular-expressions.info/unicode.html, "any code point that is not a
    // combining mark can be followed by any number of combining marks." See also the
    // discussion in https://phabricator.wikimedia.org/T35242.
    n + Mt + "*",
    "i"
  ).exec(t);
  if (!l || l.index === void 0)
    return [t, "", ""];
  const s = l.index, r = s + l[0].length, i = t.slice(s, r), u = t.slice(0, s), o = t.slice(r, t.length);
  return [u, i, o];
}
const Bt = R({
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
    titleChunks: h(() => Tt(e.searchQuery, String(e.title)))
  })
});
const Vt = { class: "cdx-search-result-title" }, Lt = { class: "cdx-search-result-title__match" };
function Kt(e, t, n, l, s, r) {
  return f(), g("span", Vt, [
    _("bdi", null, [
      te(T(e.titleChunks[0]), 1),
      _("span", Lt, T(e.titleChunks[1]), 1),
      te(T(e.titleChunks[2]), 1)
    ])
  ]);
}
const At = /* @__PURE__ */ D(Bt, [["render", Kt]]), Rt = R({
  name: "CdxMenuItem",
  components: { CdxIcon: ae, CdxThumbnail: xt, CdxSearchResultTitle: At },
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
    }, l = () => {
      t("change", "highlighted", !1);
    }, s = (y) => {
      y.button === 0 && t("change", "active", !0);
    }, r = () => {
      t("change", "selected", !0);
    }, i = h(() => e.searchQuery.length > 0), u = h(() => ({
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
      "cdx-menu-item--highlight-query": i.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), o = h(() => e.url ? "a" : "span"), d = h(() => e.label || String(e.value));
    return {
      onMouseMove: n,
      onMouseLeave: l,
      onMouseDown: s,
      onClick: r,
      highlightQuery: i,
      rootClasses: u,
      contentTag: o,
      title: d
    };
  }
});
const Dt = ["id", "aria-disabled", "aria-selected"], Et = { class: "cdx-menu-item__text" }, Nt = ["lang"], Ft = ["lang"], Ot = ["lang"], qt = ["lang"];
function zt(e, t, n, l, s, r) {
  const i = B("cdx-thumbnail"), u = B("cdx-icon"), o = B("cdx-search-result-title");
  return f(), g("li", {
    id: e.id,
    role: "option",
    class: L(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMousemove: t[0] || (t[0] = (...d) => e.onMouseMove && e.onMouseMove(...d)),
    onMouseleave: t[1] || (t[1] = (...d) => e.onMouseLeave && e.onMouseLeave(...d)),
    onMousedown: t[2] || (t[2] = _e((...d) => e.onMouseDown && e.onMouseDown(...d), ["prevent"])),
    onClick: t[3] || (t[3] = (...d) => e.onClick && e.onClick(...d))
  }, [
    A(e.$slots, "default", {}, () => [
      (f(), V(Fe(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: O(() => {
          var d, y, I, b, S, C;
          return [
            e.showThumbnail ? (f(), V(i, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (f(), V(u, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : w("", !0),
            _("span", Et, [
              e.highlightQuery ? (f(), V(o, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (d = e.language) == null ? void 0 : d.label
              }, null, 8, ["title", "search-query", "lang"])) : (f(), g("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (y = e.language) == null ? void 0 : y.label
              }, [
                _("bdi", null, T(e.title), 1)
              ], 8, Nt)),
              e.match ? (f(), g(Ce, { key: 2 }, [
                te(T(" ") + " "),
                e.highlightQuery ? (f(), V(o, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: (I = e.language) == null ? void 0 : I.match
                }, null, 8, ["title", "search-query", "lang"])) : (f(), g("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (b = e.language) == null ? void 0 : b.match
                }, [
                  _("bdi", null, T(e.match), 1)
                ], 8, Ft))
              ], 64)) : w("", !0),
              e.supportingText ? (f(), g(Ce, { key: 3 }, [
                te(T(" ") + " "),
                _("span", {
                  class: "cdx-menu-item__text__supporting-text",
                  lang: (S = e.language) == null ? void 0 : S.supportingText
                }, [
                  _("bdi", null, T(e.supportingText), 1)
                ], 8, Ot)
              ], 64)) : w("", !0),
              e.description ? (f(), g("span", {
                key: 4,
                class: "cdx-menu-item__text__description",
                lang: (C = e.language) == null ? void 0 : C.description
              }, [
                _("bdi", null, T(e.description), 1)
              ], 8, qt)) : w("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, Dt);
}
const Ht = /* @__PURE__ */ D(Rt, [["render", zt]]), Ut = R({
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
const Qt = ["aria-disabled"], jt = /* @__PURE__ */ _("div", { class: "cdx-progress-bar__bar" }, null, -1), Pt = [
  jt
];
function Wt(e, t, n, l, s, r) {
  return f(), g("div", {
    class: L(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Pt, 10, Qt);
}
const Gt = /* @__PURE__ */ D(Ut, [["render", Wt]]);
let be = 0;
function Le(e) {
  const t = Oe(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${ye}-${e}-${be++}` : n ? `${ye}-${n}-${be++}` : `${ye}-${be++}`;
}
function Jt(e, t) {
  const n = v(!1);
  let l = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const s = new window.IntersectionObserver(
    (r) => {
      const i = r[0];
      i && (n.value = i.isIntersecting);
    },
    t
  );
  return W(() => {
    l = !0, e.value && s.observe(e.value);
  }), Te(() => {
    l = !1, s.disconnect();
  }), ee(e, (r) => {
    l && (s.disconnect(), n.value = !1, r && s.observe(r));
  }), n;
}
function he(e, t = h(() => ({}))) {
  const n = h(() => {
    const r = Z(t.value, []);
    return e.class && e.class.split(" ").forEach((u) => {
      r[u] = !0;
    }), r;
  }), l = h(() => {
    if ("style" in e)
      return e.style;
  }), s = h(() => {
    const o = e, { class: r, style: i } = o;
    return Z(o, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: l,
    otherAttrs: s
  };
}
const Xt = R({
  name: "CdxMenu",
  components: {
    CdxMenuItem: Ht,
    CdxProgressBar: Gt
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
  setup(e, { emit: t, slots: n, attrs: l }) {
    const s = h(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((c) => Me(ke({}, c), {
      id: Le("menu-item")
    }))), r = h(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : s.value.length === 0 : !1), i = v(null), u = v(!1), o = v(null);
    let d = "", y = null;
    function I() {
      d = "", y !== null && (clearTimeout(y), y = null);
    }
    function b() {
      y !== null && clearTimeout(y), y = setTimeout(I, 1500);
    }
    function S() {
      return s.value.find(
        (a) => a.value === e.selected
      ) || null;
    }
    function C(a, c) {
      var p;
      if (!(c && c.disabled))
        switch (a) {
          case "selected":
            t("update:selected", (p = c == null ? void 0 : c.value) != null ? p : null), t("update:expanded", !1), o.value = null;
            break;
          case "highlighted":
            i.value = c || null, u.value = !1;
            break;
          case "highlightedViaKeyboard":
            i.value = c || null, u.value = !0;
            break;
          case "active":
            o.value = c || null;
            break;
        }
    }
    const k = h(() => {
      if (i.value !== null)
        return s.value.findIndex(
          (a) => (
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            a.value === i.value.value
          )
        );
    });
    function x(a) {
      a && (C("highlightedViaKeyboard", a), t("menu-item-keyboard-navigation", a));
    }
    function G(a) {
      var $;
      const c = (U) => {
        for (let M = U - 1; M >= 0; M--)
          if (!s.value[M].disabled)
            return s.value[M];
      };
      a = a || s.value.length;
      const p = ($ = c(a)) != null ? $ : c(s.value.length);
      x(p);
    }
    function J(a) {
      const c = ($) => s.value.find((U, M) => !U.disabled && M > $);
      a = a != null ? a : -1;
      const p = c(a) || c(-1);
      x(p);
    }
    function le(a) {
      if (a.key === "Clear")
        return I(), !0;
      if (a.key === "Backspace")
        return d = d.slice(0, -1), b(), !0;
      if (a.key.length === 1 && !a.metaKey && !a.ctrlKey && !a.altKey) {
        e.expanded || t("update:expanded", !0), d += a.key.toLowerCase();
        const c = d.length > 1 && d.split("").every((M) => M === d[0]);
        let p = s.value, $ = d;
        c && k.value !== void 0 && (p = p.slice(k.value + 1).concat(p.slice(0, k.value)), $ = d[0]);
        const U = p.find(
          (M) => !M.disabled && String(M.label || M.value).toLowerCase().indexOf($) === 0
        );
        return U && (C("highlightedViaKeyboard", U), K()), b(), !0;
      }
      return !1;
    }
    function oe(a, { prevent: c = !0, characterNavigation: p = !1 } = {}) {
      if (p) {
        if (le(a))
          return !0;
        I();
      }
      function $() {
        c && (a.preventDefault(), a.stopPropagation());
      }
      switch (a.key) {
        case "Enter":
        case " ":
          return $(), e.expanded ? (i.value && u.value && t("update:selected", i.value.value), t("update:expanded", !1)) : t("update:expanded", !0), !0;
        case "Tab":
          return e.expanded && (i.value && u.value && t("update:selected", i.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return $(), e.expanded ? (i.value === null && C("highlightedViaKeyboard", S()), G(k.value)) : t("update:expanded", !0), K(), !0;
        case "ArrowDown":
          return $(), e.expanded ? (i.value === null && C("highlightedViaKeyboard", S()), J(k.value)) : t("update:expanded", !0), K(), !0;
        case "Home":
          return $(), e.expanded ? (i.value === null && C("highlightedViaKeyboard", S()), J()) : t("update:expanded", !0), K(), !0;
        case "End":
          return $(), e.expanded ? (i.value === null && C("highlightedViaKeyboard", S()), G()) : t("update:expanded", !0), K(), !0;
        case "Escape":
          return $(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function X() {
      C("active", null);
    }
    const m = [], Y = v(void 0), E = Jt(
      Y,
      { threshold: 0.8 }
    );
    ee(E, (a) => {
      a && t("load-more");
    });
    function N(a, c) {
      if (a) {
        m[c] = a.$el;
        const p = e.visibleItemLimit;
        if (!p || e.menuItems.length < p)
          return;
        const $ = Math.min(
          p,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        c === e.menuItems.length - $ && (Y.value = a.$el);
      }
    }
    function K() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || k.value === void 0)
        return;
      const a = k.value >= 0 ? k.value : 0;
      m[a].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const F = v(null), H = v(null);
    function se() {
      if (H.value = null, !e.visibleItemLimit || m.length <= e.visibleItemLimit) {
        F.value = null;
        return;
      }
      const a = m[0], c = m[e.visibleItemLimit];
      if (F.value = ie(
        a,
        c
      ), e.footer) {
        const p = m[m.length - 1];
        H.value = p.scrollHeight;
      }
    }
    function ie(a, c) {
      const p = a.getBoundingClientRect().top;
      return c.getBoundingClientRect().top - p + 2;
    }
    W(() => {
      document.addEventListener("mouseup", X);
    }), Te(() => {
      document.removeEventListener("mouseup", X);
    }), ee(q(e, "expanded"), (a) => ve(this, null, function* () {
      if (a) {
        const c = S();
        c && !i.value && C("highlighted", c), yield de(), se(), yield de(), K();
      } else
        C("highlighted", null);
    })), ee(q(e, "menuItems"), (a) => ve(this, null, function* () {
      a.length < m.length && (m.length = a.length), e.expanded && (yield de(), se(), yield de(), K());
    }), { deep: !0 });
    const ue = h(() => ({
      "max-height": F.value ? `${F.value}px` : void 0,
      "overflow-y": F.value ? "scroll" : void 0,
      "margin-bottom": H.value ? `${H.value}px` : void 0
    })), fe = h(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!F.value
    })), {
      rootClasses: pe,
      rootStyle: me,
      otherAttrs: ge
    } = he(l, fe);
    return {
      listBoxStyle: ue,
      rootClasses: pe,
      rootStyle: me,
      otherAttrs: ge,
      assignTemplateRef: N,
      computedMenuItems: s,
      computedShowNoResultsSlot: r,
      highlightedMenuItem: i,
      highlightedViaKeyboard: u,
      activeMenuItem: o,
      handleMenuItemChange: C,
      handleKeyNavigation: oe
    };
  },
  // Public methods
  // These must be in the methods block, not in the setup function, otherwise their documentation
  // won't be picked up by vue-docgen
  methods: {
    /**
     * Get the highlighted menu item, if any.
     *
     * The parent component should set `aria-activedescendant` to the `.id` property of the
     * object returned by this method. If this method returns null, `aria-activedescendant`
     * should not be set.
     *
     * @public
     * @return {MenuItemDataWithId|null} The highlighted menu item,
     *   or null if no item is highlighted or if the menu is closed.
     */
    getHighlightedMenuItem() {
      return this.expanded ? this.highlightedMenuItem : null;
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
      this.handleMenuItemChange("active", null);
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
     * @param options
     * @param options.prevent {boolean} If false, do not call e.preventDefault() or
     *   e.stopPropagation()
     * @param options.characterNavigation {boolean}
     * @return Whether the event was handled
     */
    delegateKeyNavigation(e, { prevent: t = !0, characterNavigation: n = !1 } = {}) {
      return this.handleKeyNavigation(e, { prevent: t, characterNavigation: n });
    }
  }
});
const Yt = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Zt = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function en(e, t, n, l, s, r) {
  const i = B("cdx-menu-item"), u = B("cdx-progress-bar");
  return Be((f(), g("div", {
    class: L(["cdx-menu", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    _("ul", P({
      class: "cdx-menu__listbox",
      role: "listbox",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (f(), g("li", Yt, [
        A(e.$slots, "pending")
      ])) : w("", !0),
      e.computedShowNoResultsSlot ? (f(), g("li", Zt, [
        A(e.$slots, "no-results")
      ])) : w("", !0),
      (f(!0), g(Ce, null, qe(e.computedMenuItems, (o, d) => {
        var y, I;
        return f(), V(i, P({
          key: o.value,
          ref_for: !0,
          ref: (b) => e.assignTemplateRef(b, d)
        }, o, {
          selected: o.value === e.selected,
          active: o.value === ((y = e.activeMenuItem) == null ? void 0 : y.value),
          highlighted: o.value === ((I = e.highlightedMenuItem) == null ? void 0 : I.value),
          "show-thumbnail": e.showThumbnail,
          "bold-label": e.boldLabel,
          "hide-description-overflow": e.hideDescriptionOverflow,
          "search-query": e.searchQuery,
          onChange: (b, S) => e.handleMenuItemChange(b, S ? o : null),
          onClick: (b) => e.$emit("menu-item-click", o)
        }), {
          default: O(() => {
            var b, S;
            return [
              A(e.$slots, "default", {
                menuItem: o,
                active: o.value === ((b = e.activeMenuItem) == null ? void 0 : b.value) && o.value === ((S = e.highlightedMenuItem) == null ? void 0 : S.value)
              })
            ];
          }),
          _: 2
        }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
      }), 128)),
      e.showPending ? (f(), V(u, {
        key: 2,
        class: "cdx-menu__progress-bar",
        inline: !0
      })) : w("", !0)
    ], 16)
  ], 6)), [
    [ze, e.expanded]
  ]);
}
const tn = /* @__PURE__ */ D(Xt, [["render", en]]), nn = z(lt), an = z(ot), ln = z(st), on = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && Ue(`CdxButton: Icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/demos/button.html#icon-only-button-1`);
};
function Ie(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...Ie(n)) : typeof n == "object" && n && (// HTML tag
    typeof n.type == "string" || // Component
    typeof n.type == "object" ? t.push(n) : n.type !== He && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...Ie(n.children))));
  return t;
}
const sn = (e, t) => {
  if (!e)
    return !1;
  const n = Ie(e);
  if (n.length !== 1)
    return !1;
  const l = n[0], s = typeof l == "object" && typeof l.type == "object" && "name" in l.type && l.type.name === ae.name, r = typeof l == "object" && l.type === "svg";
  return s || r ? (on(t), !0) : !1;
}, un = R({
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
      validator: nn
    },
    /**
     * Visual prominence of the button.
     *
     * @values 'normal', 'primary', 'quiet'
     */
    weight: {
      type: String,
      default: "normal",
      validator: an
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
      validator: ln
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: l }) {
    const s = v(!1);
    return {
      rootClasses: h(() => {
        var o;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--weight-${e.weight}`]: !0,
          [`cdx-button--size-${e.size}`]: !0,
          "cdx-button--framed": e.weight !== "quiet",
          "cdx-button--icon-only": sn((o = n.default) == null ? void 0 : o.call(n), l),
          "cdx-button--is-active": s.value
        };
      }),
      onClick: (o) => {
        t("click", o);
      },
      setActive: (o) => {
        s.value = o;
      }
    };
  }
});
function rn(e, t, n, l, s, r) {
  return f(), g("button", {
    class: L(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...i) => e.onClick && e.onClick(...i)),
    onKeydown: t[1] || (t[1] = $e((i) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = $e((i) => e.setActive(!1), ["space", "enter"]))
  }, [
    A(e.$slots, "default")
  ], 34);
}
const dn = /* @__PURE__ */ D(un, [["render", rn]]);
function Ke(e, t, n) {
  return h({
    get: () => e.value,
    set: (l) => (
      // If eventName is undefined, then 'update:modelValue' must be a valid EventName,
      // but TypeScript's type analysis isn't clever enough to realize that
      t(n || "update:modelValue", l)
    )
  });
}
function cn(e) {
  const t = ce(pt, v(!1));
  return h(() => t.value || e.value);
}
function Ae(e, t, n) {
  const l = cn(e), s = ce(ft, v("default")), r = h(() => t != null && t.value && t.value !== "default" ? t.value : s.value), i = ce(ct, void 0), u = h(() => i || n);
  return {
    computedDisabled: l,
    computedStatus: r,
    computedInputId: u
  };
}
const hn = z(ut), fn = z(Ve), pn = R({
  name: "CdxTextInput",
  components: { CdxIcon: ae },
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
      validator: hn
    },
    /**
     * `status` attribute of the input.
     *
     * @values 'default', 'error'
     */
    status: {
      type: String,
      default: "default",
      validator: fn
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
    const l = n.id, {
      computedDisabled: s,
      computedStatus: r,
      computedInputId: i
    } = Ae(
      q(e, "disabled"),
      q(e, "status"),
      l
    ), u = ce(ht, void 0), o = Ke(q(e, "modelValue"), t), d = h(() => e.clearable && !!o.value && !s.value), y = h(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": d.value,
      [`cdx-text-input--status-${r.value}`]: !0
    })), {
      rootClasses: I,
      rootStyle: b,
      otherAttrs: S
    } = he(n, y), C = h(() => {
      const E = S.value, { id: m } = E;
      return Z(E, ["id"]);
    }), k = h(() => ({
      "cdx-text-input__input--has-value": !!o.value
    }));
    return {
      computedInputId: i,
      descriptionId: u,
      wrappedModel: o,
      isClearable: d,
      rootClasses: I,
      rootStyle: b,
      otherAttrsMinusId: C,
      inputClasses: k,
      computedDisabled: s,
      onClear: (m) => {
        o.value = "", t("clear", m);
      },
      onInput: (m) => {
        t("input", m);
      },
      onChange: (m) => {
        t("change", m);
      },
      onKeydown: (m) => {
        (m.key === "Home" || m.key === "End") && !m.ctrlKey && !m.metaKey || t("keydown", m);
      },
      onFocus: (m) => {
        t("focus", m);
      },
      onBlur: (m) => {
        t("blur", m);
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
const mn = ["id", "type", "aria-describedby", "disabled"];
function gn(e, t, n, l, s, r) {
  const i = B("cdx-icon");
  return f(), g("div", {
    class: L(["cdx-text-input", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    Be(_("input", P({
      id: e.computedInputId,
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (u) => e.wrappedModel = u),
      class: ["cdx-text-input__input", e.inputClasses]
    }, e.otherAttrsMinusId, {
      type: e.inputType,
      "aria-describedby": e.descriptionId,
      disabled: e.computedDisabled,
      onInput: t[1] || (t[1] = (...u) => e.onInput && e.onInput(...u)),
      onChange: t[2] || (t[2] = (...u) => e.onChange && e.onChange(...u)),
      onFocus: t[3] || (t[3] = (...u) => e.onFocus && e.onFocus(...u)),
      onBlur: t[4] || (t[4] = (...u) => e.onBlur && e.onBlur(...u)),
      onKeydown: t[5] || (t[5] = (...u) => e.onKeydown && e.onKeydown(...u))
    }), null, 16, mn), [
      [Qe, e.wrappedModel]
    ]),
    e.startIcon ? (f(), V(i, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.endIcon ? (f(), V(i, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.isClearable ? (f(), V(i, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon-vue cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = _e(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : w("", !0)
  ], 6);
}
const vn = /* @__PURE__ */ D(pn, [["render", gn]]), yn = z(Ve), bn = R({
  name: "CdxSearchInput",
  components: {
    CdxButton: dn,
    CdxTextInput: vn
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
      validator: yn
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
    const l = Ke(q(e, "modelValue"), t), { computedDisabled: s } = Ae(q(e, "disabled")), r = h(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: i,
      rootStyle: u,
      otherAttrs: o
    } = he(n, r);
    return {
      wrappedModel: l,
      computedDisabled: s,
      rootClasses: i,
      rootStyle: u,
      otherAttrs: o,
      handleSubmit: () => {
        t("submit-click", l.value);
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
const Cn = { class: "cdx-search-input__input-wrapper" };
function $n(e, t, n, l, s, r) {
  const i = B("cdx-text-input"), u = B("cdx-button");
  return f(), g("div", {
    class: L(["cdx-search-input", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    _("div", Cn, [
      j(i, P({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon,
        disabled: e.computedDisabled,
        status: e.status
      }, e.otherAttrs, {
        onKeydown: $e(e.handleSubmit, ["enter"]),
        onInput: t[1] || (t[1] = (o) => e.$emit("input", o)),
        onChange: t[2] || (t[2] = (o) => e.$emit("change", o)),
        onFocus: t[3] || (t[3] = (o) => e.$emit("focus", o)),
        onBlur: t[4] || (t[4] = (o) => e.$emit("blur", o))
      }), null, 16, ["modelValue", "start-icon", "disabled", "status", "onKeydown"]),
      A(e.$slots, "default")
    ]),
    e.buttonLabel ? (f(), V(u, {
      key: 0,
      class: "cdx-search-input__end-button",
      disabled: e.computedDisabled,
      onClick: e.handleSubmit
    }, {
      default: O(() => [
        te(T(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["disabled", "onClick"])) : w("", !0)
  ], 6);
}
const In = /* @__PURE__ */ D(bn, [["render", $n]]), _n = R({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: ae,
    CdxMenu: tn,
    CdxSearchInput: In
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
      default: rt
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
  setup(e, { attrs: t, emit: n, slots: l }) {
    const s = v(), r = v(), i = Le("typeahead-search-menu"), u = v(!1), o = v(!1), d = v(!1), y = v(!1), I = v(e.initialInputValue), b = v(""), S = h(() => {
      var a, c;
      return (c = (a = r.value) == null ? void 0 : a.getHighlightedMenuItem()) == null ? void 0 : c.id;
    }), C = v(null), k = h(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), x = h(
      () => e.searchResults.find(
        (a) => a.value === C.value
      )
    ), G = h(
      () => e.searchFooterUrl ? { value: Q, url: e.searchFooterUrl } : void 0
    ), J = h(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": u.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: le,
      rootStyle: oe,
      otherAttrs: X
    } = he(t, J);
    function m(a) {
      return a;
    }
    const Y = h(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      // In case search queries aren't highlighted, default to a bold label.
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let E, N;
    function K(a, c = !1) {
      x.value && x.value.label !== a && x.value.value !== a && (C.value = null), N !== void 0 && (clearTimeout(N), N = void 0), a === "" ? u.value = !1 : (o.value = !0, l["search-results-pending"] && (N = setTimeout(() => {
        y.value && (u.value = !0), d.value = !0;
      }, dt))), E !== void 0 && (clearTimeout(E), E = void 0);
      const p = () => {
        n("input", a);
      };
      c ? p() : E = setTimeout(() => {
        p();
      }, e.debounceInterval);
    }
    function F(a) {
      if (a === Q) {
        C.value = null, I.value = b.value;
        return;
      }
      C.value = a, a !== null && (I.value = x.value ? x.value.label || String(x.value.value) : "");
    }
    function H() {
      y.value = !0, (b.value || d.value) && (u.value = !0);
    }
    function se() {
      y.value = !1, u.value = !1;
    }
    function ie(a) {
      const $ = a, { id: c } = $, p = Z($, ["id"]);
      if (p.value === Q) {
        n("search-result-click", {
          searchResult: null,
          index: e.searchResults.length,
          numberOfResults: e.searchResults.length
        });
        return;
      }
      ue(p);
    }
    function ue(a) {
      const c = {
        searchResult: a,
        index: e.searchResults.findIndex(
          (p) => p.value === a.value
        ),
        numberOfResults: e.searchResults.length
      };
      n("search-result-click", c);
    }
    function fe(a) {
      if (a.value === Q) {
        I.value = b.value;
        return;
      }
      I.value = a.value ? a.label || String(a.value) : "";
    }
    function pe(a) {
      var c;
      u.value = !1, (c = r.value) == null || c.clearActive(), ie(a);
    }
    function me(a) {
      if (x.value)
        ue(x.value), a.stopPropagation(), window.location.assign(x.value.url), a.preventDefault();
      else {
        const c = {
          searchResult: null,
          index: -1,
          numberOfResults: e.searchResults.length
        };
        n("submit", c);
      }
    }
    function ge(a) {
      if (!r.value || !b.value || a.key === " ")
        return;
      const c = r.value.getHighlightedMenuItem(), p = r.value.getHighlightedViaKeyboard();
      switch (a.key) {
        case "Enter":
          c && (c.value === Q && p ? window.location.assign(e.searchFooterUrl) : r.value.delegateKeyNavigation(a, { prevent: !1 })), u.value = !1;
          break;
        case "Tab":
          u.value = !1;
          break;
        default:
          r.value.delegateKeyNavigation(a);
          break;
      }
    }
    return W(() => {
      e.initialInputValue && K(e.initialInputValue, !0);
    }), ee(q(e, "searchResults"), () => {
      b.value = I.value.trim(), y.value && o.value && b.value.length > 0 && (u.value = !0), N !== void 0 && (clearTimeout(N), N = void 0), o.value = !1, d.value = !1;
    }), {
      form: s,
      menu: r,
      menuId: i,
      highlightedId: S,
      selection: C,
      menuMessageClass: k,
      footer: G,
      asSearchResult: m,
      inputValue: I,
      searchQuery: b,
      expanded: u,
      showPending: d,
      rootClasses: le,
      rootStyle: oe,
      otherAttrs: X,
      menuConfig: Y,
      onUpdateInputValue: K,
      onUpdateMenuSelection: F,
      onFocus: H,
      onBlur: se,
      onSearchResultClick: ie,
      onSearchResultKeyboardNavigation: fe,
      onSearchFooterClick: pe,
      onSubmit: me,
      onKeydown: ge,
      MenuFooterValue: Q,
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
const Sn = ["id", "action"], wn = { class: "cdx-typeahead-search__menu-message__text" }, xn = { class: "cdx-typeahead-search__menu-message__text" }, kn = ["href", "onClickCapture"], Mn = { class: "cdx-menu-item__text cdx-typeahead-search__search-footer__text" }, Tn = { class: "cdx-typeahead-search__search-footer__query" };
function Bn(e, t, n, l, s, r) {
  const i = B("cdx-icon"), u = B("cdx-menu"), o = B("cdx-search-input");
  return f(), g("div", {
    class: L(["cdx-typeahead-search", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    _("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...d) => e.onSubmit && e.onSubmit(...d))
    }, [
      j(o, P({
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
        "aria-controls": e.menuId,
        "aria-expanded": e.expanded,
        "aria-activedescendant": e.highlightedId,
        "onUpdate:modelValue": e.onUpdateInputValue,
        onFocus: e.onFocus,
        onBlur: e.onBlur,
        onKeydown: e.onKeydown
      }), {
        default: O(() => [
          j(u, P({
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
            pending: O(() => [
              _("div", {
                class: L(["cdx-menu-item__content cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                _("span", wn, [
                  A(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": O(() => [
              _("div", {
                class: L(["cdx-menu-item__content cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                _("span", xn, [
                  A(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: O(({ menuItem: d, active: y }) => [
              d.value === e.MenuFooterValue ? (f(), g("a", {
                key: 0,
                class: L(["cdx-menu-item__content cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": y
                }]),
                href: e.asSearchResult(d).url,
                onClickCapture: _e((I) => e.onSearchFooterClick(e.asSearchResult(d)), ["stop"])
              }, [
                j(i, {
                  class: "cdx-menu-item__thumbnail cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                _("span", Mn, [
                  A(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    _("strong", Tn, T(e.searchQuery), 1)
                  ])
                ])
              ], 42, kn)) : w("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-controls", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      A(e.$slots, "default")
    ], 40, Sn)
  ], 6);
}
const Kn = /* @__PURE__ */ D(_n, [["render", Bn]]);
export {
  Kn as CdxTypeaheadSearch
};
