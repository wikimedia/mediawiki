var xe = Object.defineProperty, we = Object.defineProperties;
var Me = Object.getOwnPropertyDescriptors;
var j = Object.getOwnPropertySymbols;
var oe = Object.prototype.hasOwnProperty, se = Object.prototype.propertyIsEnumerable;
var le = (e, t, n) => t in e ? xe(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, ie = (e, t) => {
  for (var n in t || (t = {}))
    oe.call(t, n) && le(e, n, t[n]);
  if (j)
    for (var n of j(t))
      se.call(t, n) && le(e, n, t[n]);
  return e;
}, re = (e, t) => we(e, Me(t));
var z = (e, t) => {
  var n = {};
  for (var u in e)
    oe.call(e, u) && t.indexOf(u) < 0 && (n[u] = e[u]);
  if (e != null && j)
    for (var u of j(e))
      t.indexOf(u) < 0 && se.call(e, u) && (n[u] = e[u]);
  return n;
};
import { ref as b, onMounted as U, defineComponent as I, computed as p, openBlock as c, createElementBlock as g, normalizeClass as x, toDisplayString as M, createCommentVNode as B, resolveComponent as D, createVNode as O, Transition as Ie, withCtx as R, normalizeStyle as W, createElementVNode as A, createTextVNode as H, withModifiers as Z, renderSlot as w, createBlock as E, resolveDynamicComponent as Te, Fragment as de, getCurrentInstance as Le, onUnmounted as Re, watch as ce, toRef as G, withDirectives as he, renderList as Ve, mergeProps as Q, vShow as Ne, Comment as qe, warn as Oe, vModelDynamic as Qe, withKeys as Ue, toRefs as Ke } from "vue";
const Pe = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', je = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', ze = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', He = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4-5.4-5.4zM3 8a5 5 0 1010 0A5 5 0 103 8z"/>', We = Pe, Ge = je, Xe = ze, Je = He;
function Ye(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const u = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof u == "string" || "path" in u ? u : u.ltr;
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
  const t = b(null);
  return U(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function tt(e) {
  const t = b("");
  return U(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
const nt = I({
  name: "CdxIcon",
  props: {
    icon: {
      type: [String, Object],
      required: !0
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
  setup(e, { emit: t }) {
    const n = b(), u = et(n), i = tt(n), l = p(() => e.dir || u.value), o = p(() => e.lang || i.value), r = p(() => ({
      "cdx-icon--flipped": l.value === "rtl" && o.value !== null && Ze(e.icon, o.value)
    })), a = p(
      () => Ye(e.icon, o.value || "", l.value || "ltr")
    ), d = p(() => typeof a.value == "string" ? a.value : ""), f = p(() => typeof a.value != "string" ? a.value.path : "");
    return {
      rootElement: n,
      rootClasses: r,
      iconSvg: d,
      iconPath: f,
      onClick: (y) => {
        t("click", y);
      }
    };
  }
});
const T = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [u, i] of t)
    n[u] = i;
  return n;
}, ut = ["aria-hidden"], at = { key: 0 }, lt = ["innerHTML"], ot = ["d"];
function st(e, t, n, u, i, l) {
  return c(), g("span", {
    ref: "rootElement",
    class: x(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    (c(), g("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": !e.iconLabel
    }, [
      e.iconLabel ? (c(), g("title", at, M(e.iconLabel), 1)) : B("", !0),
      e.iconSvg ? (c(), g("g", {
        key: 1,
        fill: "currentColor",
        innerHTML: e.iconSvg
      }, null, 8, lt)) : (c(), g("path", {
        key: 2,
        d: e.iconPath,
        fill: "currentColor"
      }, null, 8, ot))
    ], 8, ut))
  ], 2);
}
const K = /* @__PURE__ */ T(nt, [["render", st]]), it = I({
  name: "CdxThumbnail",
  components: { CdxIcon: K },
  props: {
    thumbnail: {
      type: [Object, null],
      default: null
    },
    placeholderIcon: {
      type: [String, Object],
      default: Xe
    }
  },
  setup: (e) => {
    const t = b(!1), n = b({}), u = (i) => {
      const l = i.replace(/([\\"\n])/g, "\\$1"), o = new Image();
      o.onload = () => {
        n.value = { backgroundImage: `url("${l}")` }, t.value = !0;
      }, o.onerror = () => {
        t.value = !1;
      }, o.src = l;
    };
    return U(() => {
      var i;
      (i = e.thumbnail) != null && i.url && u(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const rt = { class: "cdx-thumbnail" }, dt = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function ct(e, t, n, u, i, l) {
  const o = D("cdx-icon");
  return c(), g("span", rt, [
    e.thumbnailLoaded ? B("", !0) : (c(), g("span", dt, [
      O(o, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])),
    O(Ie, { name: "cdx-thumbnail__image" }, {
      default: R(() => [
        e.thumbnailLoaded ? (c(), g("span", {
          key: 0,
          style: W(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : B("", !0)
      ]),
      _: 1
    })
  ]);
}
const ht = /* @__PURE__ */ T(it, [["render", ct]]);
function pt(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const ft = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function gt(e, t) {
  if (!e)
    return [t, "", ""];
  const n = pt(e), u = new RegExp(
    n + ft + "*",
    "i"
  ).exec(t);
  if (!u || u.index === void 0)
    return [t, "", ""];
  const i = u.index, l = i + u[0].length, o = t.slice(i, l), r = t.slice(0, i), a = t.slice(l, t.length);
  return [r, o, a];
}
const mt = I({
  name: "CdxSearchResultTitle",
  props: {
    title: {
      type: String,
      required: !0
    },
    searchQuery: {
      type: String,
      default: ""
    }
  },
  setup: (e) => ({
    titleChunks: p(() => gt(e.searchQuery, String(e.title)))
  })
});
const vt = { class: "cdx-search-result-title" }, yt = { class: "cdx-search-result-title__match" };
function Ct(e, t, n, u, i, l) {
  return c(), g("span", vt, [
    A("bdi", null, [
      H(M(e.titleChunks[0]), 1),
      A("span", yt, M(e.titleChunks[1]), 1),
      H(M(e.titleChunks[2]), 1)
    ])
  ]);
}
const bt = /* @__PURE__ */ T(mt, [["render", Ct]]), At = I({
  name: "CdxMenuItem",
  components: { CdxIcon: K, CdxThumbnail: ht, CdxSearchResultTitle: bt },
  props: {
    id: {
      type: String,
      required: !0
    },
    value: {
      type: [String, Number],
      required: !0
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    selected: {
      type: Boolean,
      default: !1
    },
    active: {
      type: Boolean,
      default: !1
    },
    highlighted: {
      type: Boolean,
      default: !1
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
      default: !1
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
      default: !1
    },
    hideDescriptionOverflow: {
      type: Boolean,
      default: !1
    },
    language: {
      type: Object,
      default: () => ({})
    }
  },
  emits: [
    "change"
  ],
  setup: (e, { emit: t }) => {
    const n = () => {
      t("change", "highlighted", !0);
    }, u = () => {
      t("change", "highlighted", !1);
    }, i = (f) => {
      f.button === 0 && t("change", "active", !0);
    }, l = () => {
      t("change", "selected", !0);
    }, o = p(() => e.searchQuery.length > 0), r = p(() => ({
      "cdx-menu-item--selected": e.selected,
      "cdx-menu-item--active": e.active && e.highlighted,
      "cdx-menu-item--highlighted": e.highlighted,
      "cdx-menu-item--enabled": !e.disabled,
      "cdx-menu-item--disabled": e.disabled,
      "cdx-menu-item--highlight-query": o.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), a = p(() => e.url ? "a" : "span"), d = p(() => e.label || String(e.value));
    return {
      onMouseEnter: n,
      onMouseLeave: u,
      onMouseDown: i,
      onClick: l,
      highlightQuery: o,
      rootClasses: r,
      contentTag: a,
      title: d
    };
  }
});
const _t = ["id", "aria-disabled", "aria-selected"], Bt = { class: "cdx-menu-item__text" }, $t = ["lang"], St = /* @__PURE__ */ H(/* @__PURE__ */ M(" ") + " "), Dt = ["lang"], Ft = ["lang"];
function Et(e, t, n, u, i, l) {
  const o = D("cdx-thumbnail"), r = D("cdx-icon"), a = D("cdx-search-result-title");
  return c(), g("li", {
    id: e.id,
    role: "option",
    class: x(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMouseenter: t[0] || (t[0] = (...d) => e.onMouseEnter && e.onMouseEnter(...d)),
    onMouseleave: t[1] || (t[1] = (...d) => e.onMouseLeave && e.onMouseLeave(...d)),
    onMousedown: t[2] || (t[2] = Z((...d) => e.onMouseDown && e.onMouseDown(...d), ["prevent"])),
    onClick: t[3] || (t[3] = (...d) => e.onClick && e.onClick(...d))
  }, [
    w(e.$slots, "default", {}, () => [
      (c(), E(Te(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: R(() => {
          var d, f, v, y, F;
          return [
            e.showThumbnail ? (c(), E(o, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (c(), E(r, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : B("", !0),
            A("span", Bt, [
              e.highlightQuery ? (c(), E(a, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (d = e.language) == null ? void 0 : d.label
              }, null, 8, ["title", "search-query", "lang"])) : (c(), g("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (f = e.language) == null ? void 0 : f.label
              }, [
                A("bdi", null, M(e.title), 1)
              ], 8, $t)),
              e.match ? (c(), g(de, { key: 2 }, [
                St,
                e.highlightQuery ? (c(), E(a, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: (v = e.language) == null ? void 0 : v.match
                }, null, 8, ["title", "search-query", "lang"])) : (c(), g("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (y = e.language) == null ? void 0 : y.match
                }, [
                  A("bdi", null, M(e.match), 1)
                ], 8, Dt))
              ], 64)) : B("", !0),
              e.description ? (c(), g("span", {
                key: 3,
                class: "cdx-menu-item__text__description",
                lang: (F = e.language) == null ? void 0 : F.description
              }, [
                A("bdi", null, M(e.description), 1)
              ], 8, Ft)) : B("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, _t);
}
const kt = /* @__PURE__ */ T(At, [["render", Et]]), xt = I({
  name: "CdxProgressBar",
  props: {
    inline: {
      type: Boolean,
      default: !1
    }
  },
  setup(e) {
    return {
      rootClasses: p(() => ({
        "cdx-progress-bar--block": !e.inline,
        "cdx-progress-bar--inline": e.inline
      }))
    };
  }
});
const wt = /* @__PURE__ */ A("div", { class: "cdx-progress-bar__bar" }, null, -1), Mt = [
  wt
];
function It(e, t, n, u, i, l) {
  return c(), g("div", {
    class: x(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Mt, 2);
}
const Tt = /* @__PURE__ */ T(xt, [["render", It]]), X = "cdx", Lt = [
  "default",
  "progressive",
  "destructive"
], Rt = [
  "normal",
  "primary",
  "quiet"
], Vt = [
  "text",
  "search"
], Nt = 120, qt = 500, q = "cdx-menu-footer-item";
let J = 0;
function pe(e) {
  const t = Le(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${X}-${e}-${J++}` : n ? `${X}-${n}-${J++}` : `${X}-${J++}`;
}
const Ot = I({
  name: "CdxMenu",
  components: {
    CdxMenuItem: kt,
    CdxProgressBar: Tt
  },
  props: {
    menuItems: {
      type: Array,
      required: !0
    },
    selected: {
      type: [String, Number, null],
      required: !0
    },
    expanded: {
      type: Boolean,
      required: !0
    },
    showPending: {
      type: Boolean,
      default: !1
    },
    showThumbnail: {
      type: Boolean,
      default: !1
    },
    boldLabel: {
      type: Boolean,
      default: !1
    },
    hideDescriptionOverflow: {
      type: Boolean,
      default: !1
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
  setup(e, { emit: t, slots: n }) {
    const u = p(() => e.menuItems.map((s) => re(ie({}, s), {
      id: pe("menu-item")
    }))), i = p(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : u.value.length === 0 : !1), l = b(null), o = b(null);
    function r() {
      return u.value.find(
        (s) => s.value === e.selected
      );
    }
    function a(s, m) {
      var C;
      if (!(m && m.disabled))
        switch (s) {
          case "selected":
            t("update:selected", (C = m == null ? void 0 : m.value) != null ? C : null), t("update:expanded", !1), o.value = null;
            break;
          case "highlighted":
            l.value = m || null;
            break;
          case "active":
            o.value = m || null;
            break;
        }
    }
    const d = p(() => {
      if (l.value !== null)
        return u.value.findIndex(
          (s) => s.value === l.value.value
        );
    });
    function f(s) {
      !s || (a("highlighted", s), t("menu-item-keyboard-navigation", s));
    }
    function v(s) {
      var $;
      const m = (S) => {
        for (let L = S - 1; L >= 0; L--)
          if (!u.value[L].disabled)
            return u.value[L];
      };
      s = s || u.value.length;
      const C = ($ = m(s)) != null ? $ : m(u.value.length);
      f(C);
    }
    function y(s) {
      const m = ($) => u.value.find((S, L) => !S.disabled && L > $);
      s = s != null ? s : -1;
      const C = m(s) || m(-1);
      f(C);
    }
    function F(s, m = !0) {
      function C() {
        t("update:expanded", !0), a("highlighted", r());
      }
      function $() {
        m && (s.preventDefault(), s.stopPropagation());
      }
      switch (s.key) {
        case "Enter":
        case " ":
          return $(), e.expanded ? (l.value && t("update:selected", l.value.value), t("update:expanded", !1)) : C(), !0;
        case "Tab":
          return e.expanded && (l.value && t("update:selected", l.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return $(), e.expanded ? (l.value === null && a("highlighted", r()), v(d.value)) : C(), !0;
        case "ArrowDown":
          return $(), e.expanded ? (l.value === null && a("highlighted", r()), y(d.value)) : C(), !0;
        case "Home":
          return $(), e.expanded ? (l.value === null && a("highlighted", r()), y()) : C(), !0;
        case "End":
          return $(), e.expanded ? (l.value === null && a("highlighted", r()), v()) : C(), !0;
        case "Escape":
          return $(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function k() {
      a("active");
    }
    return U(() => {
      document.addEventListener("mouseup", k);
    }), Re(() => {
      document.removeEventListener("mouseup", k);
    }), ce(G(e, "expanded"), (s) => {
      const m = r();
      !s && l.value && m === void 0 && a("highlighted"), s && m !== void 0 && a("highlighted", m);
    }), {
      computedMenuItems: u,
      computedShowNoResultsSlot: i,
      highlightedMenuItem: l,
      activeMenuItem: o,
      handleMenuItemChange: a,
      handleKeyNavigation: F
    };
  },
  methods: {
    getHighlightedMenuItem() {
      return this.highlightedMenuItem;
    },
    clearActive() {
      this.handleMenuItemChange("active");
    },
    delegateKeyNavigation(e, t = !0) {
      return this.handleKeyNavigation(e, t);
    }
  }
});
const Qt = {
  class: "cdx-menu",
  role: "listbox",
  "aria-multiselectable": "false"
}, Ut = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Kt = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function Pt(e, t, n, u, i, l) {
  const o = D("cdx-menu-item"), r = D("cdx-progress-bar");
  return he((c(), g("ul", Qt, [
    e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (c(), g("li", Ut, [
      w(e.$slots, "pending")
    ])) : B("", !0),
    e.computedShowNoResultsSlot ? (c(), g("li", Kt, [
      w(e.$slots, "no-results")
    ])) : B("", !0),
    (c(!0), g(de, null, Ve(e.computedMenuItems, (a) => {
      var d, f;
      return c(), E(o, Q({
        key: a.value
      }, a, {
        selected: a.value === e.selected,
        active: a.value === ((d = e.activeMenuItem) == null ? void 0 : d.value),
        highlighted: a.value === ((f = e.highlightedMenuItem) == null ? void 0 : f.value),
        "show-thumbnail": e.showThumbnail,
        "bold-label": e.boldLabel,
        "hide-description-overflow": e.hideDescriptionOverflow,
        "search-query": e.searchQuery,
        onChange: (v, y) => e.handleMenuItemChange(v, y && a),
        onClick: (v) => e.$emit("menu-item-click", a)
      }), {
        default: R(() => {
          var v, y;
          return [
            w(e.$slots, "default", {
              menuItem: a,
              active: a.value === ((v = e.activeMenuItem) == null ? void 0 : v.value) && a.value === ((y = e.highlightedMenuItem) == null ? void 0 : y.value)
            })
          ];
        }),
        _: 2
      }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
    }), 128)),
    e.showPending ? (c(), E(r, {
      key: 2,
      class: "cdx-menu__progress-bar",
      inline: !0
    })) : B("", !0)
  ], 512)), [
    [Ne, e.expanded]
  ]);
}
const jt = /* @__PURE__ */ T(Ot, [["render", Pt]]);
function ee(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const zt = ee(Rt), Ht = ee(Lt), Wt = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && Oe(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/main/components/button.html#default-icon-only`);
};
function Y(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...Y(n)) : typeof n == "object" && n && (typeof n.type == "string" || typeof n.type == "object" ? t.push(n) : n.type !== qe && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...Y(n.children))));
  return t;
}
const Gt = (e, t) => {
  if (!e)
    return !1;
  const n = Y(e);
  if (n.length !== 1)
    return !1;
  const u = n[0], i = typeof u == "object" && typeof u.type == "object" && "name" in u.type && u.type.name === K.name, l = typeof u == "object" && u.type === "svg";
  return i || l ? (Wt(t), !0) : !1;
}, Xt = I({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: Ht
    },
    type: {
      type: String,
      default: "normal",
      validator: zt
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: u }) {
    return {
      rootClasses: p(() => {
        var o;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--type-${e.type}`]: !0,
          "cdx-button--framed": e.type !== "quiet",
          "cdx-button--icon-only": Gt((o = n.default) == null ? void 0 : o.call(n), u)
        };
      }),
      onClick: (o) => {
        t("click", o);
      }
    };
  }
});
function Jt(e, t, n, u, i, l) {
  return c(), g("button", {
    class: x(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    w(e.$slots, "default")
  ], 2);
}
const Yt = /* @__PURE__ */ T(Xt, [["render", Jt]]);
function fe(e, t, n) {
  return p({
    get: () => e.value,
    set: (u) => t(n || "update:modelValue", u)
  });
}
function te(e, t = p(() => ({}))) {
  const n = p(() => {
    const l = z(t.value, []);
    return e.class && e.class.split(" ").forEach((r) => {
      l[r] = !0;
    }), l;
  }), u = p(() => {
    if ("style" in e)
      return e.style;
  }), i = p(() => {
    const a = e, { class: l, style: o } = a;
    return z(a, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: u,
    otherAttrs: i
  };
}
const Zt = ee(Vt), en = I({
  name: "CdxTextInput",
  components: { CdxIcon: K },
  inheritAttrs: !1,
  expose: ["focus"],
  props: {
    modelValue: {
      type: [String, Number],
      default: ""
    },
    inputType: {
      type: String,
      default: "text",
      validator: Zt
    },
    disabled: {
      type: Boolean,
      default: !1
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
      default: !1
    }
  },
  emits: [
    "update:modelValue",
    "input",
    "change",
    "focus",
    "blur"
  ],
  setup(e, { emit: t, attrs: n }) {
    const u = fe(G(e, "modelValue"), t), i = p(() => e.clearable && !!u.value && !e.disabled), l = p(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": i.value
    })), {
      rootClasses: o,
      rootStyle: r,
      otherAttrs: a
    } = te(n, l), d = p(() => ({
      "cdx-text-input__input--has-value": !!u.value
    }));
    return {
      wrappedModel: u,
      isClearable: i,
      rootClasses: o,
      rootStyle: r,
      otherAttrs: a,
      inputClasses: d,
      onClear: () => {
        u.value = "";
      },
      onInput: (s) => {
        t("input", s);
      },
      onChange: (s) => {
        t("change", s);
      },
      onFocus: (s) => {
        t("focus", s);
      },
      onBlur: (s) => {
        t("blur", s);
      },
      cdxIconClear: Ge
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    }
  }
});
const tn = ["type", "disabled"];
function nn(e, t, n, u, i, l) {
  const o = D("cdx-icon");
  return c(), g("div", {
    class: x(["cdx-text-input", e.rootClasses]),
    style: W(e.rootStyle)
  }, [
    he(A("input", Q({
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (r) => e.wrappedModel = r),
      class: ["cdx-text-input__input", e.inputClasses]
    }, e.otherAttrs, {
      type: e.inputType,
      disabled: e.disabled,
      onInput: t[1] || (t[1] = (...r) => e.onInput && e.onInput(...r)),
      onChange: t[2] || (t[2] = (...r) => e.onChange && e.onChange(...r)),
      onFocus: t[3] || (t[3] = (...r) => e.onFocus && e.onFocus(...r)),
      onBlur: t[4] || (t[4] = (...r) => e.onBlur && e.onBlur(...r))
    }), null, 16, tn), [
      [Qe, e.wrappedModel]
    ]),
    e.startIcon ? (c(), E(o, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : B("", !0),
    e.endIcon ? (c(), E(o, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : B("", !0),
    e.isClearable ? (c(), E(o, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon cdx-text-input__clear-icon",
      onMousedown: t[5] || (t[5] = Z(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : B("", !0)
  ], 6);
}
const un = /* @__PURE__ */ T(en, [["render", nn]]), an = I({
  name: "CdxSearchInput",
  components: {
    CdxButton: Yt,
    CdxTextInput: un
  },
  inheritAttrs: !1,
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
  setup(e, { emit: t, attrs: n }) {
    const u = fe(G(e, "modelValue"), t), i = p(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: l,
      rootStyle: o,
      otherAttrs: r
    } = te(n, i);
    return {
      wrappedModel: u,
      rootClasses: l,
      rootStyle: o,
      otherAttrs: r,
      handleSubmit: () => {
        t("submit-click", u.value);
      },
      searchIcon: Je
    };
  },
  methods: {
    focus() {
      this.$refs.textInput.focus();
    }
  }
});
const ln = { class: "cdx-search-input__input-wrapper" };
function on(e, t, n, u, i, l) {
  const o = D("cdx-text-input"), r = D("cdx-button");
  return c(), g("div", {
    class: x(["cdx-search-input", e.rootClasses]),
    style: W(e.rootStyle)
  }, [
    A("div", ln, [
      O(o, Q({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (a) => e.wrappedModel = a),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon
      }, e.otherAttrs, {
        onKeydown: Ue(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "onKeydown"]),
      w(e.$slots, "default")
    ]),
    e.buttonLabel ? (c(), E(r, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: R(() => [
        H(M(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : B("", !0)
  ], 6);
}
const sn = /* @__PURE__ */ T(an, [["render", on]]), rn = I({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: K,
    CdxMenu: jt,
    CdxSearchInput: sn
  },
  inheritAttrs: !1,
  props: {
    id: {
      type: String,
      required: !0
    },
    formAction: {
      type: String,
      required: !0
    },
    searchResultsLabel: {
      type: String,
      required: !0
    },
    searchResults: {
      type: Array,
      required: !0
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
      default: Nt
    },
    highlightQuery: {
      type: Boolean,
      default: !1
    },
    showThumbnail: {
      type: Boolean,
      default: !1
    },
    autoExpandWidth: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "input",
    "search-result-click",
    "submit"
  ],
  setup(e, { attrs: t, emit: n, slots: u }) {
    const { searchResults: i, searchFooterUrl: l, debounceInterval: o } = Ke(e), r = b(), a = b(), d = pe("typeahead-search-menu"), f = b(!1), v = b(!1), y = b(!1), F = b(!1), k = b(e.initialInputValue), s = b(""), m = p(() => {
      var h, _;
      return (_ = (h = a.value) == null ? void 0 : h.getHighlightedMenuItem()) == null ? void 0 : _.id;
    }), C = b(null), $ = p(() => ({
      "cdx-typeahead-search__menu-message--with-thumbnail": e.showThumbnail
    })), S = p(
      () => e.searchResults.find(
        (h) => h.value === C.value
      )
    ), L = p(
      () => l.value ? i.value.concat([
        { value: q, url: l.value }
      ]) : i.value
    ), ge = p(() => ({
      "cdx-typeahead-search--active": F.value,
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": f.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: me,
      rootStyle: ve,
      otherAttrs: ye
    } = te(t, ge);
    function Ce(h) {
      return h;
    }
    const be = p(() => ({
      showThumbnail: e.showThumbnail,
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let P, V;
    function ne(h, _ = !1) {
      S.value && S.value.label !== h && S.value.value !== h && (C.value = null), V !== void 0 && (clearTimeout(V), V = void 0), h === "" ? f.value = !1 : (v.value = !0, u["search-results-pending"] && (V = setTimeout(() => {
        F.value && (f.value = !0), y.value = !0;
      }, qt))), P !== void 0 && (clearTimeout(P), P = void 0);
      const N = () => {
        n("input", h);
      };
      _ ? N() : P = setTimeout(() => {
        N();
      }, o.value);
    }
    function Ae(h) {
      if (h === q) {
        C.value = null, k.value = s.value;
        return;
      }
      C.value = h, h !== null && (k.value = S.value ? S.value.label || String(S.value.value) : "");
    }
    function _e() {
      F.value = !0, (s.value || y.value) && (f.value = !0);
    }
    function Be() {
      F.value = !1, f.value = !1;
    }
    function ue(h) {
      const ae = h, { id: _ } = ae, N = z(ae, ["id"]), Ee = {
        searchResult: N.value !== q ? N : null,
        index: L.value.findIndex(
          (ke) => ke.value === h.value
        ),
        numberOfResults: i.value.length
      };
      n("search-result-click", Ee);
    }
    function $e(h) {
      if (h.value === q) {
        k.value = s.value;
        return;
      }
      k.value = h.value ? h.label || String(h.value) : "";
    }
    function Se(h) {
      var _;
      f.value = !1, (_ = a.value) == null || _.clearActive(), ue(h);
    }
    function De() {
      let h = null, _ = -1;
      S.value && (h = S.value, _ = e.searchResults.indexOf(S.value));
      const N = {
        searchResult: h,
        index: _,
        numberOfResults: i.value.length
      };
      n("submit", N);
    }
    function Fe(h) {
      if (!a.value || !s.value || h.key === " " && f.value)
        return;
      const _ = a.value.getHighlightedMenuItem();
      switch (h.key) {
        case "Enter":
          _ && (_.value === q ? window.location.assign(l.value) : a.value.delegateKeyNavigation(h, !1)), f.value = !1;
          break;
        case "Tab":
          f.value = !1;
          break;
        default:
          a.value.delegateKeyNavigation(h);
          break;
      }
    }
    return U(() => {
      e.initialInputValue && ne(e.initialInputValue, !0);
    }), ce(G(e, "searchResults"), () => {
      s.value = k.value.trim(), F.value && v.value && s.value.length > 0 && (f.value = !0), V !== void 0 && (clearTimeout(V), V = void 0), v.value = !1, y.value = !1;
    }), {
      form: r,
      menu: a,
      menuId: d,
      highlightedId: m,
      selection: C,
      menuMessageClass: $,
      searchResultsWithFooter: L,
      asSearchResult: Ce,
      inputValue: k,
      searchQuery: s,
      expanded: f,
      showPending: y,
      rootClasses: me,
      rootStyle: ve,
      otherAttrs: ye,
      menuConfig: be,
      onUpdateInputValue: ne,
      onUpdateMenuSelection: Ae,
      onFocus: _e,
      onBlur: Be,
      onSearchResultClick: ue,
      onSearchResultKeyboardNavigation: $e,
      onSearchFooterClick: Se,
      onSubmit: De,
      onKeydown: Fe,
      MenuFooterValue: q,
      articleIcon: We
    };
  },
  methods: {
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const dn = ["id", "action"], cn = { class: "cdx-typeahead-search__menu-message__text" }, hn = { class: "cdx-typeahead-search__menu-message__text" }, pn = ["href", "onClickCapture"], fn = { class: "cdx-typeahead-search__search-footer__text" }, gn = { class: "cdx-typeahead-search__search-footer__query" };
function mn(e, t, n, u, i, l) {
  const o = D("cdx-icon"), r = D("cdx-menu"), a = D("cdx-search-input");
  return c(), g("div", {
    class: x(["cdx-typeahead-search", e.rootClasses]),
    style: W(e.rootStyle)
  }, [
    A("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[3] || (t[3] = (...d) => e.onSubmit && e.onSubmit(...d))
    }, [
      O(a, Q({
        ref: "searchInput",
        modelValue: e.inputValue,
        "onUpdate:modelValue": t[2] || (t[2] = (d) => e.inputValue = d),
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
        autocapitalize: "off",
        "onUpdate:modelValue": e.onUpdateInputValue,
        onFocus: e.onFocus,
        onBlur: e.onBlur,
        onKeydown: e.onKeydown
      }), {
        default: R(() => [
          O(r, Q({
            id: e.menuId,
            ref: "menu",
            expanded: e.expanded,
            "onUpdate:expanded": t[0] || (t[0] = (d) => e.expanded = d),
            "show-pending": e.showPending,
            selected: e.selection,
            "menu-items": e.searchResultsWithFooter,
            "search-query": e.highlightQuery ? e.searchQuery : "",
            "show-no-results-slot": e.searchQuery.length > 0 && e.searchResults.length === 0 && e.$slots["search-no-results-text"] && e.$slots["search-no-results-text"]().length > 0
          }, e.menuConfig, {
            "aria-label": e.searchResultsLabel,
            "onUpdate:selected": e.onUpdateMenuSelection,
            onMenuItemClick: t[1] || (t[1] = (d) => e.onSearchResultClick(e.asSearchResult(d))),
            onMenuItemKeyboardNavigation: e.onSearchResultKeyboardNavigation
          }), {
            pending: R(() => [
              A("div", {
                class: x(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                A("span", cn, [
                  w(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": R(() => [
              A("div", {
                class: x(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                A("span", hn, [
                  w(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: R(({ menuItem: d, active: f }) => [
              d.value === e.MenuFooterValue ? (c(), g("a", {
                key: 0,
                class: x(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": f
                }]),
                href: e.asSearchResult(d).url,
                onClickCapture: Z((v) => e.onSearchFooterClick(e.asSearchResult(d)), ["stop"])
              }, [
                O(o, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                A("span", fn, [
                  w(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    A("strong", gn, M(e.searchQuery), 1)
                  ])
                ])
              ], 42, pn)) : B("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      w(e.$slots, "default")
    ], 40, dn)
  ], 6);
}
const bn = /* @__PURE__ */ T(rn, [["render", mn]]);
export {
  bn as CdxTypeaheadSearch
};
