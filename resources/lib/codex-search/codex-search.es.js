var xe = Object.defineProperty, Te = Object.defineProperties;
var Le = Object.getOwnPropertyDescriptors;
var Y = Object.getOwnPropertySymbols;
var me = Object.prototype.hasOwnProperty, ve = Object.prototype.propertyIsEnumerable;
var fe = (e, t, n) => t in e ? xe(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, ge = (e, t) => {
  for (var n in t || (t = {}))
    me.call(t, n) && fe(e, n, t[n]);
  if (Y)
    for (var n of Y(t))
      ve.call(t, n) && fe(e, n, t[n]);
  return e;
}, ye = (e, t) => Te(e, Le(t));
var Z = (e, t) => {
  var n = {};
  for (var u in e)
    me.call(e, u) && t.indexOf(u) < 0 && (n[u] = e[u]);
  if (e != null && Y)
    for (var u of Y(e))
      t.indexOf(u) < 0 && ve.call(e, u) && (n[u] = e[u]);
  return n;
};
var le = (e, t, n) => new Promise((u, s) => {
  var a = (l) => {
    try {
      r(n.next(l));
    } catch (d) {
      s(d);
    }
  }, o = (l) => {
    try {
      r(n.throw(l));
    } catch (d) {
      s(d);
    }
  }, r = (l) => l.done ? u(l.value) : Promise.resolve(l.value).then(a, o);
  r((n = n.apply(e, t)).next());
});
import { ref as C, onMounted as P, defineComponent as R, computed as p, openBlock as c, createElementBlock as v, normalizeClass as x, toDisplayString as L, createCommentVNode as E, resolveComponent as D, createVNode as H, Transition as Re, withCtx as K, normalizeStyle as G, createElementVNode as $, createTextVNode as te, withModifiers as ie, renderSlot as T, createBlock as k, resolveDynamicComponent as Ve, Fragment as be, getCurrentInstance as Ne, onUnmounted as Ce, watch as j, toRef as z, nextTick as ee, withDirectives as Ae, renderList as Oe, mergeProps as W, vShow as Ke, Comment as qe, warn as Qe, vModelDynamic as Ue, withKeys as He, toRefs as Pe } from "vue";
const je = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', ze = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', We = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Ge = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', Xe = je, Je = ze, Ye = We, Ze = Ge;
function et(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const u = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof u == "string" || "path" in u ? u : u.ltr;
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
  const t = C(null);
  return P(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function ut(e) {
  const t = C("");
  return P(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
const lt = R({
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
    const n = C(), u = nt(n), s = ut(n), a = p(() => e.dir || u.value), o = p(() => e.lang || s.value), r = p(() => ({
      "cdx-icon--flipped": a.value === "rtl" && o.value !== null && tt(e.icon, o.value)
    })), l = p(
      () => et(e.icon, o.value || "", a.value || "ltr")
    ), d = p(() => typeof l.value == "string" ? l.value : ""), m = p(() => typeof l.value != "string" ? l.value.path : "");
    return {
      rootElement: n,
      rootClasses: r,
      iconSvg: d,
      iconPath: m,
      onClick: (g) => {
        t("click", g);
      }
    };
  }
});
const V = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [u, s] of t)
    n[u] = s;
  return n;
}, at = ["aria-hidden"], ot = { key: 0 }, st = ["innerHTML"], it = ["d"];
function rt(e, t, n, u, s, a) {
  return c(), v("span", {
    ref: "rootElement",
    class: x(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    (c(), v("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": !e.iconLabel
    }, [
      e.iconLabel ? (c(), v("title", ot, L(e.iconLabel), 1)) : E("", !0),
      e.iconSvg ? (c(), v("g", {
        key: 1,
        fill: "currentColor",
        innerHTML: e.iconSvg
      }, null, 8, st)) : (c(), v("path", {
        key: 2,
        d: e.iconPath,
        fill: "currentColor"
      }, null, 8, it))
    ], 8, at))
  ], 2);
}
const X = /* @__PURE__ */ V(lt, [["render", rt]]), dt = R({
  name: "CdxThumbnail",
  components: { CdxIcon: X },
  props: {
    thumbnail: {
      type: [Object, null],
      default: null
    },
    placeholderIcon: {
      type: [String, Object],
      default: Ye
    }
  },
  setup: (e) => {
    const t = C(!1), n = C({}), u = (s) => {
      const a = s.replace(/([\\"\n])/g, "\\$1"), o = new Image();
      o.onload = () => {
        n.value = { backgroundImage: `url("${a}")` }, t.value = !0;
      }, o.onerror = () => {
        t.value = !1;
      }, o.src = a;
    };
    return P(() => {
      var s;
      (s = e.thumbnail) != null && s.url && u(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const ct = { class: "cdx-thumbnail" }, ht = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function pt(e, t, n, u, s, a) {
  const o = D("cdx-icon");
  return c(), v("span", ct, [
    e.thumbnailLoaded ? E("", !0) : (c(), v("span", ht, [
      H(o, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])),
    H(Re, { name: "cdx-thumbnail__image" }, {
      default: K(() => [
        e.thumbnailLoaded ? (c(), v("span", {
          key: 0,
          style: G(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : E("", !0)
      ]),
      _: 1
    })
  ]);
}
const ft = /* @__PURE__ */ V(dt, [["render", pt]]);
function mt(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const vt = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function gt(e, t) {
  if (!e)
    return [t, "", ""];
  const n = mt(e), u = new RegExp(
    n + vt + "*",
    "i"
  ).exec(t);
  if (!u || u.index === void 0)
    return [t, "", ""];
  const s = u.index, a = s + u[0].length, o = t.slice(s, a), r = t.slice(0, s), l = t.slice(a, t.length);
  return [r, o, l];
}
const yt = R({
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
const bt = { class: "cdx-search-result-title" }, Ct = { class: "cdx-search-result-title__match" };
function At(e, t, n, u, s, a) {
  return c(), v("span", bt, [
    $("bdi", null, [
      te(L(e.titleChunks[0]), 1),
      $("span", Ct, L(e.titleChunks[1]), 1),
      te(L(e.titleChunks[2]), 1)
    ])
  ]);
}
const _t = /* @__PURE__ */ V(yt, [["render", At]]), Bt = R({
  name: "CdxMenuItem",
  components: { CdxIcon: X, CdxThumbnail: ft, CdxSearchResultTitle: _t },
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
    }, s = (m) => {
      m.button === 0 && t("change", "active", !0);
    }, a = () => {
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
    })), l = p(() => e.url ? "a" : "span"), d = p(() => e.label || String(e.value));
    return {
      onMouseEnter: n,
      onMouseLeave: u,
      onMouseDown: s,
      onClick: a,
      highlightQuery: o,
      rootClasses: r,
      contentTag: l,
      title: d
    };
  }
});
const $t = ["id", "aria-disabled", "aria-selected"], It = { class: "cdx-menu-item__text" }, St = ["lang"], wt = /* @__PURE__ */ te(/* @__PURE__ */ L(" ") + " "), Et = ["lang"], Dt = ["lang"];
function Ft(e, t, n, u, s, a) {
  const o = D("cdx-thumbnail"), r = D("cdx-icon"), l = D("cdx-search-result-title");
  return c(), v("li", {
    id: e.id,
    role: "option",
    class: x(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMouseenter: t[0] || (t[0] = (...d) => e.onMouseEnter && e.onMouseEnter(...d)),
    onMouseleave: t[1] || (t[1] = (...d) => e.onMouseLeave && e.onMouseLeave(...d)),
    onMousedown: t[2] || (t[2] = ie((...d) => e.onMouseDown && e.onMouseDown(...d), ["prevent"])),
    onClick: t[3] || (t[3] = (...d) => e.onClick && e.onClick(...d))
  }, [
    T(e.$slots, "default", {}, () => [
      (c(), k(Ve(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: K(() => {
          var d, m, I, g, S;
          return [
            e.showThumbnail ? (c(), k(o, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (c(), k(r, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : E("", !0),
            $("span", It, [
              e.highlightQuery ? (c(), k(l, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (d = e.language) == null ? void 0 : d.label
              }, null, 8, ["title", "search-query", "lang"])) : (c(), v("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (m = e.language) == null ? void 0 : m.label
              }, [
                $("bdi", null, L(e.title), 1)
              ], 8, St)),
              e.match ? (c(), v(be, { key: 2 }, [
                wt,
                e.highlightQuery ? (c(), k(l, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: (I = e.language) == null ? void 0 : I.match
                }, null, 8, ["title", "search-query", "lang"])) : (c(), v("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (g = e.language) == null ? void 0 : g.match
                }, [
                  $("bdi", null, L(e.match), 1)
                ], 8, Et))
              ], 64)) : E("", !0),
              e.description ? (c(), v("span", {
                key: 3,
                class: "cdx-menu-item__text__description",
                lang: (S = e.language) == null ? void 0 : S.description
              }, [
                $("bdi", null, L(e.description), 1)
              ], 8, Dt)) : E("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, $t);
}
const kt = /* @__PURE__ */ V(Bt, [["render", Ft]]), Mt = R({
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
const xt = /* @__PURE__ */ $("div", { class: "cdx-progress-bar__bar" }, null, -1), Tt = [
  xt
];
function Lt(e, t, n, u, s, a) {
  return c(), v("div", {
    class: x(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Tt, 2);
}
const Rt = /* @__PURE__ */ V(Mt, [["render", Lt]]), ae = "cdx", Vt = [
  "default",
  "progressive",
  "destructive"
], Nt = [
  "normal",
  "primary",
  "quiet"
], Ot = [
  "text",
  "search"
], Kt = 120, qt = 500, U = "cdx-menu-footer-item";
let oe = 0;
function _e(e) {
  const t = Ne(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${ae}-${e}-${oe++}` : n ? `${ae}-${n}-${oe++}` : `${ae}-${oe++}`;
}
function Qt(e, t) {
  const n = C(!1);
  let u = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const s = new window.IntersectionObserver(
    (a) => {
      const o = a[0];
      o && (n.value = o.isIntersecting);
    },
    t
  );
  return P(() => {
    u = !0, e.value && s.observe(e.value);
  }), Ce(() => {
    u = !1, s.disconnect();
  }), j(e, (a) => {
    !u || (s.disconnect(), n.value = !1, a && s.observe(a));
  }), n;
}
const Ut = R({
  name: "CdxMenu",
  components: {
    CdxMenuItem: kt,
    CdxProgressBar: Rt
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
    visibleItemLimit: {
      type: Number,
      default: null
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
    "menu-item-keyboard-navigation",
    "load-more"
  ],
  expose: [
    "clearActive",
    "getHighlightedMenuItem",
    "delegateKeyNavigation"
  ],
  setup(e, { emit: t, slots: n }) {
    const u = p(() => e.menuItems.map((i) => ye(ge({}, i), {
      id: _e("menu-item")
    }))), s = p(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : u.value.length === 0 : !1), a = C(null), o = C(null);
    function r() {
      return u.value.find(
        (i) => i.value === e.selected
      );
    }
    function l(i, f) {
      var y;
      if (!(f && f.disabled))
        switch (i) {
          case "selected":
            t("update:selected", (y = f == null ? void 0 : f.value) != null ? y : null), t("update:expanded", !1), o.value = null;
            break;
          case "highlighted":
            a.value = f || null;
            break;
          case "active":
            o.value = f || null;
            break;
        }
    }
    const d = p(() => {
      if (a.value !== null)
        return u.value.findIndex(
          (i) => i.value === a.value.value
        );
    });
    function m(i) {
      !i || (l("highlighted", i), t("menu-item-keyboard-navigation", i));
    }
    function I(i) {
      var b;
      const f = (F) => {
        for (let N = F - 1; N >= 0; N--)
          if (!u.value[N].disabled)
            return u.value[N];
      };
      i = i || u.value.length;
      const y = (b = f(i)) != null ? b : f(u.value.length);
      m(y);
    }
    function g(i) {
      const f = (b) => u.value.find((F, N) => !F.disabled && N > b);
      i = i != null ? i : -1;
      const y = f(i) || f(-1);
      m(y);
    }
    function S(i, f = !0) {
      function y() {
        t("update:expanded", !0), l("highlighted", r());
      }
      function b() {
        f && (i.preventDefault(), i.stopPropagation());
      }
      switch (i.key) {
        case "Enter":
        case " ":
          return b(), e.expanded ? (a.value && t("update:selected", a.value.value), t("update:expanded", !1)) : y(), !0;
        case "Tab":
          return e.expanded && (a.value && t("update:selected", a.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return b(), e.expanded ? (a.value === null && l("highlighted", r()), I(d.value)) : y(), B(), !0;
        case "ArrowDown":
          return b(), e.expanded ? (a.value === null && l("highlighted", r()), g(d.value)) : y(), B(), !0;
        case "Home":
          return b(), e.expanded ? (a.value === null && l("highlighted", r()), g()) : y(), B(), !0;
        case "End":
          return b(), e.expanded ? (a.value === null && l("highlighted", r()), I()) : y(), B(), !0;
        case "Escape":
          return b(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function M() {
      l("active");
    }
    const _ = [], A = C(void 0), q = Qt(
      A,
      { threshold: 0.8 }
    );
    j(q, (i) => {
      i && t("load-more");
    });
    function ne(i, f) {
      if (i) {
        _[f] = i.$el;
        const y = e.visibleItemLimit;
        if (!y || e.menuItems.length < y)
          return;
        const b = Math.min(
          y,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        f === e.menuItems.length - b && (A.value = i.$el);
      }
    }
    function B() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || d.value === void 0)
        return;
      const i = d.value >= 0 ? d.value : 0;
      _[i].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const O = C(null);
    function J() {
      if (!e.visibleItemLimit || _.length <= e.visibleItemLimit) {
        O.value = null;
        return;
      }
      const i = _[0], f = _[e.visibleItemLimit];
      O.value = ue(
        i,
        f
      );
    }
    function ue(i, f) {
      const y = i.getBoundingClientRect().top;
      return f.getBoundingClientRect().top - y + 2;
    }
    return P(() => {
      document.addEventListener("mouseup", M);
    }), Ce(() => {
      document.removeEventListener("mouseup", M);
    }), j(z(e, "expanded"), (i) => le(this, null, function* () {
      const f = r();
      !i && a.value && f === void 0 && l("highlighted"), i && f !== void 0 && l("highlighted", f), i && (yield ee(), J(), yield ee(), B());
    })), j(z(e, "menuItems"), (i) => le(this, null, function* () {
      i.length < _.length && (_.length = i.length), e.expanded && (yield ee(), J(), yield ee(), B());
    })), {
      rootStyle: p(() => ({
        "max-height": O.value ? `${O.value}px` : void 0,
        "overflow-y": O.value ? "scroll" : void 0
      })),
      assignTemplateRef: ne,
      computedMenuItems: u,
      computedShowNoResultsSlot: s,
      highlightedMenuItem: a,
      activeMenuItem: o,
      handleMenuItemChange: l,
      handleKeyNavigation: S
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
const Ht = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Pt = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function jt(e, t, n, u, s, a) {
  const o = D("cdx-menu-item"), r = D("cdx-progress-bar");
  return Ae((c(), v("ul", {
    class: "cdx-menu",
    role: "listbox",
    "aria-multiselectable": "false",
    style: G(e.rootStyle)
  }, [
    e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (c(), v("li", Ht, [
      T(e.$slots, "pending")
    ])) : E("", !0),
    e.computedShowNoResultsSlot ? (c(), v("li", Pt, [
      T(e.$slots, "no-results")
    ])) : E("", !0),
    (c(!0), v(be, null, Oe(e.computedMenuItems, (l, d) => {
      var m, I;
      return c(), k(o, W({
        key: l.value,
        ref_for: !0,
        ref: (g) => e.assignTemplateRef(g, d)
      }, l, {
        selected: l.value === e.selected,
        active: l.value === ((m = e.activeMenuItem) == null ? void 0 : m.value),
        highlighted: l.value === ((I = e.highlightedMenuItem) == null ? void 0 : I.value),
        "show-thumbnail": e.showThumbnail,
        "bold-label": e.boldLabel,
        "hide-description-overflow": e.hideDescriptionOverflow,
        "search-query": e.searchQuery,
        onChange: (g, S) => e.handleMenuItemChange(g, S && l),
        onClick: (g) => e.$emit("menu-item-click", l)
      }), {
        default: K(() => {
          var g, S;
          return [
            T(e.$slots, "default", {
              menuItem: l,
              active: l.value === ((g = e.activeMenuItem) == null ? void 0 : g.value) && l.value === ((S = e.highlightedMenuItem) == null ? void 0 : S.value)
            })
          ];
        }),
        _: 2
      }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
    }), 128)),
    e.showPending ? (c(), k(r, {
      key: 2,
      class: "cdx-menu__progress-bar",
      inline: !0
    })) : E("", !0)
  ], 4)), [
    [Ke, e.expanded]
  ]);
}
const zt = /* @__PURE__ */ V(Ut, [["render", jt]]);
function re(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const Wt = re(Nt), Gt = re(Vt), Xt = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && Qe(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/main/components/button.html#default-icon-only`);
};
function se(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...se(n)) : typeof n == "object" && n && (typeof n.type == "string" || typeof n.type == "object" ? t.push(n) : n.type !== qe && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...se(n.children))));
  return t;
}
const Jt = (e, t) => {
  if (!e)
    return !1;
  const n = se(e);
  if (n.length !== 1)
    return !1;
  const u = n[0], s = typeof u == "object" && typeof u.type == "object" && "name" in u.type && u.type.name === X.name, a = typeof u == "object" && u.type === "svg";
  return s || a ? (Xt(t), !0) : !1;
}, Yt = R({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: Gt
    },
    type: {
      type: String,
      default: "normal",
      validator: Wt
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
          "cdx-button--icon-only": Jt((o = n.default) == null ? void 0 : o.call(n), u)
        };
      }),
      onClick: (o) => {
        t("click", o);
      }
    };
  }
});
function Zt(e, t, n, u, s, a) {
  return c(), v("button", {
    class: x(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    T(e.$slots, "default")
  ], 2);
}
const en = /* @__PURE__ */ V(Yt, [["render", Zt]]);
function Be(e, t, n) {
  return p({
    get: () => e.value,
    set: (u) => t(n || "update:modelValue", u)
  });
}
function de(e, t = p(() => ({}))) {
  const n = p(() => {
    const a = Z(t.value, []);
    return e.class && e.class.split(" ").forEach((r) => {
      a[r] = !0;
    }), a;
  }), u = p(() => {
    if ("style" in e)
      return e.style;
  }), s = p(() => {
    const l = e, { class: a, style: o } = l;
    return Z(l, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: u,
    otherAttrs: s
  };
}
const tn = re(Ot), nn = R({
  name: "CdxTextInput",
  components: { CdxIcon: X },
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
      validator: tn
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
    "keydown",
    "focus",
    "blur"
  ],
  setup(e, { emit: t, attrs: n }) {
    const u = Be(z(e, "modelValue"), t), s = p(() => e.clearable && !!u.value && !e.disabled), a = p(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": s.value
    })), {
      rootClasses: o,
      rootStyle: r,
      otherAttrs: l
    } = de(n, a), d = p(() => ({
      "cdx-text-input__input--has-value": !!u.value
    }));
    return {
      wrappedModel: u,
      isClearable: s,
      rootClasses: o,
      rootStyle: r,
      otherAttrs: l,
      inputClasses: d,
      onClear: () => {
        u.value = "";
      },
      onInput: (A) => {
        t("input", A);
      },
      onChange: (A) => {
        t("change", A);
      },
      onKeydown: (A) => {
        (A.key === "Home" || A.key === "End") && !A.ctrlKey && !A.metaKey || t("keydown", A);
      },
      onFocus: (A) => {
        t("focus", A);
      },
      onBlur: (A) => {
        t("blur", A);
      },
      cdxIconClear: Je
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    }
  }
});
const un = ["type", "disabled"];
function ln(e, t, n, u, s, a) {
  const o = D("cdx-icon");
  return c(), v("div", {
    class: x(["cdx-text-input", e.rootClasses]),
    style: G(e.rootStyle)
  }, [
    Ae($("input", W({
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (r) => e.wrappedModel = r),
      class: ["cdx-text-input__input", e.inputClasses]
    }, e.otherAttrs, {
      type: e.inputType,
      disabled: e.disabled,
      onInput: t[1] || (t[1] = (...r) => e.onInput && e.onInput(...r)),
      onChange: t[2] || (t[2] = (...r) => e.onChange && e.onChange(...r)),
      onFocus: t[3] || (t[3] = (...r) => e.onFocus && e.onFocus(...r)),
      onBlur: t[4] || (t[4] = (...r) => e.onBlur && e.onBlur(...r)),
      onKeydown: t[5] || (t[5] = (...r) => e.onKeydown && e.onKeydown(...r))
    }), null, 16, un), [
      [Ue, e.wrappedModel]
    ]),
    e.startIcon ? (c(), k(o, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : E("", !0),
    e.endIcon ? (c(), k(o, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : E("", !0),
    e.isClearable ? (c(), k(o, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = ie(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : E("", !0)
  ], 6);
}
const an = /* @__PURE__ */ V(nn, [["render", ln]]), on = R({
  name: "CdxSearchInput",
  components: {
    CdxButton: en,
    CdxTextInput: an
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
    const u = Be(z(e, "modelValue"), t), s = p(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: a,
      rootStyle: o,
      otherAttrs: r
    } = de(n, s);
    return {
      wrappedModel: u,
      rootClasses: a,
      rootStyle: o,
      otherAttrs: r,
      handleSubmit: () => {
        t("submit-click", u.value);
      },
      searchIcon: Ze
    };
  },
  methods: {
    focus() {
      this.$refs.textInput.focus();
    }
  }
});
const sn = { class: "cdx-search-input__input-wrapper" };
function rn(e, t, n, u, s, a) {
  const o = D("cdx-text-input"), r = D("cdx-button");
  return c(), v("div", {
    class: x(["cdx-search-input", e.rootClasses]),
    style: G(e.rootStyle)
  }, [
    $("div", sn, [
      H(o, W({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (l) => e.wrappedModel = l),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon
      }, e.otherAttrs, {
        onKeydown: He(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "onKeydown"]),
      T(e.$slots, "default")
    ]),
    e.buttonLabel ? (c(), k(r, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: K(() => [
        te(L(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : E("", !0)
  ], 6);
}
const dn = /* @__PURE__ */ V(on, [["render", rn]]), cn = R({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: X,
    CdxMenu: zt,
    CdxSearchInput: dn
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
      default: Kt
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
    },
    visibleItemLimit: {
      type: Number,
      default: null
    }
  },
  emits: [
    "input",
    "search-result-click",
    "submit",
    "load-more"
  ],
  setup(e, { attrs: t, emit: n, slots: u }) {
    const { searchResults: s, searchFooterUrl: a, debounceInterval: o } = Pe(e), r = C(), l = C(), d = _e("typeahead-search-menu"), m = C(!1), I = C(!1), g = C(!1), S = C(!1), M = C(e.initialInputValue), _ = C(""), A = p(() => {
      var h, w;
      return (w = (h = l.value) == null ? void 0 : h.getHighlightedMenuItem()) == null ? void 0 : w.id;
    }), q = C(null), ne = p(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), B = p(
      () => e.searchResults.find(
        (h) => h.value === q.value
      )
    ), O = p(
      () => a.value ? s.value.concat([
        { value: U, url: a.value }
      ]) : s.value
    ), J = p(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": m.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: ue,
      rootStyle: ce,
      otherAttrs: i
    } = de(t, J);
    function f(h) {
      return h;
    }
    const y = p(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let b, F;
    function N(h, w = !1) {
      B.value && B.value.label !== h && B.value.value !== h && (q.value = null), F !== void 0 && (clearTimeout(F), F = void 0), h === "" ? m.value = !1 : (I.value = !0, u["search-results-pending"] && (F = setTimeout(() => {
        S.value && (m.value = !0), g.value = !0;
      }, qt))), b !== void 0 && (clearTimeout(b), b = void 0);
      const Q = () => {
        n("input", h);
      };
      w ? Q() : b = setTimeout(() => {
        Q();
      }, o.value);
    }
    function $e(h) {
      if (h === U) {
        q.value = null, M.value = _.value;
        return;
      }
      q.value = h, h !== null && (M.value = B.value ? B.value.label || String(B.value.value) : "");
    }
    function Ie() {
      S.value = !0, (_.value || g.value) && (m.value = !0);
    }
    function Se() {
      S.value = !1, m.value = !1;
    }
    function he(h) {
      const pe = h, { id: w } = pe, Q = Z(pe, ["id"]), ke = {
        searchResult: Q.value !== U ? Q : null,
        index: O.value.findIndex(
          (Me) => Me.value === h.value
        ),
        numberOfResults: s.value.length
      };
      n("search-result-click", ke);
    }
    function we(h) {
      if (h.value === U) {
        M.value = _.value;
        return;
      }
      M.value = h.value ? h.label || String(h.value) : "";
    }
    function Ee(h) {
      var w;
      m.value = !1, (w = l.value) == null || w.clearActive(), he(h);
    }
    function De() {
      let h = null, w = -1;
      B.value && (h = B.value, w = e.searchResults.indexOf(B.value));
      const Q = {
        searchResult: h,
        index: w,
        numberOfResults: s.value.length
      };
      n("submit", Q);
    }
    function Fe(h) {
      if (!l.value || !_.value || h.key === " " && m.value)
        return;
      const w = l.value.getHighlightedMenuItem();
      switch (h.key) {
        case "Enter":
          w && (w.value === U ? window.location.assign(a.value) : l.value.delegateKeyNavigation(h, !1)), m.value = !1;
          break;
        case "Tab":
          m.value = !1;
          break;
        default:
          l.value.delegateKeyNavigation(h);
          break;
      }
    }
    return P(() => {
      e.initialInputValue && N(e.initialInputValue, !0);
    }), j(z(e, "searchResults"), () => {
      _.value = M.value.trim(), S.value && I.value && _.value.length > 0 && (m.value = !0), F !== void 0 && (clearTimeout(F), F = void 0), I.value = !1, g.value = !1;
    }), {
      form: r,
      menu: l,
      menuId: d,
      highlightedId: A,
      selection: q,
      menuMessageClass: ne,
      searchResultsWithFooter: O,
      asSearchResult: f,
      inputValue: M,
      searchQuery: _,
      expanded: m,
      showPending: g,
      rootClasses: ue,
      rootStyle: ce,
      otherAttrs: i,
      menuConfig: y,
      onUpdateInputValue: N,
      onUpdateMenuSelection: $e,
      onFocus: Ie,
      onBlur: Se,
      onSearchResultClick: he,
      onSearchResultKeyboardNavigation: we,
      onSearchFooterClick: Ee,
      onSubmit: De,
      onKeydown: Fe,
      MenuFooterValue: U,
      articleIcon: Xe
    };
  },
  methods: {
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const hn = ["id", "action"], pn = { class: "cdx-typeahead-search__menu-message__text" }, fn = { class: "cdx-typeahead-search__menu-message__text" }, mn = ["href", "onClickCapture"], vn = { class: "cdx-typeahead-search__search-footer__text" }, gn = { class: "cdx-typeahead-search__search-footer__query" };
function yn(e, t, n, u, s, a) {
  const o = D("cdx-icon"), r = D("cdx-menu"), l = D("cdx-search-input");
  return c(), v("div", {
    class: x(["cdx-typeahead-search", e.rootClasses]),
    style: G(e.rootStyle)
  }, [
    $("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...d) => e.onSubmit && e.onSubmit(...d))
    }, [
      H(l, W({
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
        default: K(() => [
          H(r, W({
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
            onMenuItemKeyboardNavigation: e.onSearchResultKeyboardNavigation,
            onLoadMore: t[2] || (t[2] = (d) => e.$emit("load-more"))
          }), {
            pending: K(() => [
              $("div", {
                class: x(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                $("span", pn, [
                  T(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": K(() => [
              $("div", {
                class: x(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                $("span", fn, [
                  T(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: K(({ menuItem: d, active: m }) => [
              d.value === e.MenuFooterValue ? (c(), v("a", {
                key: 0,
                class: x(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": m
                }]),
                href: e.asSearchResult(d).url,
                onClickCapture: ie((I) => e.onSearchFooterClick(e.asSearchResult(d)), ["stop"])
              }, [
                H(o, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                $("span", vn, [
                  T(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    $("strong", gn, L(e.searchQuery), 1)
                  ])
                ])
              ], 42, mn)) : E("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      T(e.$slots, "default")
    ], 40, hn)
  ], 6);
}
const _n = /* @__PURE__ */ V(cn, [["render", yn]]);
export {
  _n as CdxTypeaheadSearch
};
