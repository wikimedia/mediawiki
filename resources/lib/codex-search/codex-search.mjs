var Te = Object.defineProperty, Ve = Object.defineProperties;
var Le = Object.getOwnPropertyDescriptors;
var oe = Object.getOwnPropertySymbols;
var _e = Object.prototype.hasOwnProperty, $e = Object.prototype.propertyIsEnumerable;
var Be = (e, t, n) => t in e ? Te(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, Se = (e, t) => {
  for (var n in t || (t = {}))
    _e.call(t, n) && Be(e, n, t[n]);
  if (oe)
    for (var n of oe(t))
      $e.call(t, n) && Be(e, n, t[n]);
  return e;
}, Ie = (e, t) => Ve(e, Le(t));
var se = (e, t) => {
  var n = {};
  for (var u in e)
    _e.call(e, u) && t.indexOf(u) < 0 && (n[u] = e[u]);
  if (e != null && oe)
    for (var u of oe(e))
      t.indexOf(u) < 0 && $e.call(e, u) && (n[u] = e[u]);
  return n;
};
var pe = (e, t, n) => new Promise((u, l) => {
  var r = (o) => {
    try {
      s(n.next(o));
    } catch (d) {
      l(d);
    }
  }, a = (o) => {
    try {
      s(n.throw(o));
    } catch (d) {
      l(d);
    }
  }, s = (o) => o.done ? u(o.value) : Promise.resolve(o.value).then(r, a);
  s((n = n.apply(e, t)).next());
});
import { ref as y, onMounted as G, defineComponent as L, computed as p, openBlock as c, createElementBlock as g, normalizeClass as M, toDisplayString as D, createCommentVNode as w, resolveComponent as k, createVNode as j, Transition as Ke, withCtx as Q, normalizeStyle as te, createElementVNode as C, createTextVNode as Z, withModifiers as Ce, renderSlot as V, createBlock as x, resolveDynamicComponent as Re, Fragment as ve, getCurrentInstance as Ne, onUnmounted as we, watch as Y, toRef as ee, nextTick as ie, withDirectives as Ee, mergeProps as W, renderList as Oe, vShow as qe, Comment as He, warn as Qe, withKeys as ye, vModelDynamic as Ue, toRefs as ze } from "vue";
const Pe = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', je = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', We = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Ge = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', Je = Pe, Xe = je, Ye = We, Ze = Ge;
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
  const t = y(null);
  return G(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function ut(e) {
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
const me = "cdx", at = [
  "default",
  "progressive",
  "destructive"
], lt = [
  "normal",
  "primary",
  "quiet"
], ot = [
  "x-small",
  "small",
  "medium"
], st = [
  "text",
  "search"
], De = [
  "default",
  "error"
], it = 120, rt = 500, P = "cdx-menu-footer-item", dt = J(ot), ct = L({
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
    },
    size: {
      type: String,
      default: "medium",
      validator: dt
    }
  },
  emits: ["click"],
  setup(e, { emit: t }) {
    const n = y(), u = nt(n), l = ut(n), r = p(() => e.dir || u.value), a = p(() => e.lang || l.value), s = p(() => ({
      "cdx-icon--flipped": r.value === "rtl" && a.value !== null && tt(e.icon, a.value),
      [`cdx-icon--${e.size}`]: !0
    })), o = p(
      () => et(e.icon, a.value || "", r.value || "ltr")
    ), d = p(() => typeof o.value == "string" ? o.value : ""), f = p(() => typeof o.value != "string" ? o.value.path : "");
    return {
      rootElement: n,
      rootClasses: s,
      iconSvg: d,
      iconPath: f,
      onClick: (b) => {
        t("click", b);
      }
    };
  }
});
const K = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [u, l] of t)
    n[u] = l;
  return n;
}, ht = ["aria-hidden"], ft = { key: 0 }, pt = ["innerHTML"], mt = ["d"];
function gt(e, t, n, u, l, r) {
  return c(), g("span", {
    ref: "rootElement",
    class: M(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...a) => e.onClick && e.onClick(...a))
  }, [
    (c(), g("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": e.iconLabel ? void 0 : !0
    }, [
      e.iconLabel ? (c(), g("title", ft, D(e.iconLabel), 1)) : w("", !0),
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
const ne = /* @__PURE__ */ K(ct, [["render", gt]]), vt = L({
  name: "CdxThumbnail",
  components: { CdxIcon: ne },
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
    const t = y(!1), n = y({}), u = (l) => {
      const r = l.replace(/([\\"\n])/g, "\\$1"), a = new Image();
      a.onload = () => {
        n.value = { backgroundImage: `url("${r}")` }, t.value = !0;
      }, a.onerror = () => {
        t.value = !1;
      }, a.src = r;
    };
    return G(() => {
      var l;
      (l = e.thumbnail) != null && l.url && u(e.thumbnail.url);
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
function Ct(e, t, n, u, l, r) {
  const a = k("cdx-icon");
  return c(), g("span", yt, [
    e.thumbnailLoaded ? w("", !0) : (c(), g("span", bt, [
      j(a, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])),
    j(Ke, { name: "cdx-thumbnail__image" }, {
      default: Q(() => [
        e.thumbnailLoaded ? (c(), g("span", {
          key: 0,
          style: te(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : w("", !0)
      ]),
      _: 1
    })
  ]);
}
const At = /* @__PURE__ */ K(vt, [["render", Ct]]);
function Bt(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const _t = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function $t(e, t) {
  if (!e)
    return [t, "", ""];
  const n = Bt(e), u = new RegExp(
    n + _t + "*",
    "i"
  ).exec(t);
  if (!u || u.index === void 0)
    return [t, "", ""];
  const l = u.index, r = l + u[0].length, a = t.slice(l, r), s = t.slice(0, l), o = t.slice(r, t.length);
  return [s, a, o];
}
const St = L({
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
    titleChunks: p(() => $t(e.searchQuery, String(e.title)))
  })
});
const It = { class: "cdx-search-result-title" }, wt = { class: "cdx-search-result-title__match" };
function Et(e, t, n, u, l, r) {
  return c(), g("span", It, [
    C("bdi", null, [
      Z(D(e.titleChunks[0]), 1),
      C("span", wt, D(e.titleChunks[1]), 1),
      Z(D(e.titleChunks[2]), 1)
    ])
  ]);
}
const Dt = /* @__PURE__ */ K(St, [["render", Et]]), kt = L({
  name: "CdxMenuItem",
  components: { CdxIcon: ne, CdxThumbnail: At, CdxSearchResultTitle: Dt },
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
    supportingText: {
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
    }, l = (f) => {
      f.button === 0 && t("change", "active", !0);
    }, r = () => {
      t("change", "selected", !0);
    }, a = p(() => e.searchQuery.length > 0), s = p(() => ({
      "cdx-menu-item--selected": e.selected,
      "cdx-menu-item--active": e.active && e.highlighted,
      "cdx-menu-item--highlighted": e.highlighted,
      "cdx-menu-item--enabled": !e.disabled,
      "cdx-menu-item--disabled": e.disabled,
      "cdx-menu-item--highlight-query": a.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), o = p(() => e.url ? "a" : "span"), d = p(() => e.label || String(e.value));
    return {
      onMouseEnter: n,
      onMouseLeave: u,
      onMouseDown: l,
      onClick: r,
      highlightQuery: a,
      rootClasses: s,
      contentTag: o,
      title: d
    };
  }
});
const Ft = ["id", "aria-disabled", "aria-selected"], xt = { class: "cdx-menu-item__text" }, Mt = ["lang"], Tt = ["lang"], Vt = ["lang"], Lt = ["lang"];
function Kt(e, t, n, u, l, r) {
  const a = k("cdx-thumbnail"), s = k("cdx-icon"), o = k("cdx-search-result-title");
  return c(), g("li", {
    id: e.id,
    role: "option",
    class: M(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMouseenter: t[0] || (t[0] = (...d) => e.onMouseEnter && e.onMouseEnter(...d)),
    onMouseleave: t[1] || (t[1] = (...d) => e.onMouseLeave && e.onMouseLeave(...d)),
    onMousedown: t[2] || (t[2] = Ce((...d) => e.onMouseDown && e.onMouseDown(...d), ["prevent"])),
    onClick: t[3] || (t[3] = (...d) => e.onClick && e.onClick(...d))
  }, [
    V(e.$slots, "default", {}, () => [
      (c(), x(Re(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: Q(() => {
          var d, f, A, b, $, E;
          return [
            e.showThumbnail ? (c(), x(a, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (c(), x(s, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : w("", !0),
            C("span", xt, [
              e.highlightQuery ? (c(), x(o, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (d = e.language) == null ? void 0 : d.label
              }, null, 8, ["title", "search-query", "lang"])) : (c(), g("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (f = e.language) == null ? void 0 : f.label
              }, [
                C("bdi", null, D(e.title), 1)
              ], 8, Mt)),
              e.match ? (c(), g(ve, { key: 2 }, [
                Z(D(" ") + " "),
                e.highlightQuery ? (c(), x(o, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: (A = e.language) == null ? void 0 : A.match
                }, null, 8, ["title", "search-query", "lang"])) : (c(), g("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (b = e.language) == null ? void 0 : b.match
                }, [
                  C("bdi", null, D(e.match), 1)
                ], 8, Tt))
              ], 64)) : w("", !0),
              e.supportingText ? (c(), g(ve, { key: 3 }, [
                Z(D(" ") + " "),
                C("span", {
                  class: "cdx-menu-item__text__supporting-text",
                  lang: ($ = e.language) == null ? void 0 : $.supportingText
                }, [
                  C("bdi", null, D(e.supportingText), 1)
                ], 8, Vt)
              ], 64)) : w("", !0),
              e.description ? (c(), g("span", {
                key: 4,
                class: "cdx-menu-item__text__description",
                lang: (E = e.language) == null ? void 0 : E.description
              }, [
                C("bdi", null, D(e.description), 1)
              ], 8, Lt)) : w("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, Ft);
}
const Rt = /* @__PURE__ */ K(kt, [["render", Kt]]), Nt = L({
  name: "CdxProgressBar",
  props: {
    inline: {
      type: Boolean,
      default: !1
    },
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
const Ot = ["aria-disabled"], qt = /* @__PURE__ */ C("div", { class: "cdx-progress-bar__bar" }, null, -1), Ht = [
  qt
];
function Qt(e, t, n, u, l, r) {
  return c(), g("div", {
    class: M(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Ht, 10, Ot);
}
const Ut = /* @__PURE__ */ K(Nt, [["render", Qt]]);
let ge = 0;
function ke(e) {
  const t = Ne(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${me}-${e}-${ge++}` : n ? `${me}-${n}-${ge++}` : `${me}-${ge++}`;
}
function zt(e, t) {
  const n = y(!1);
  let u = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const l = new window.IntersectionObserver(
    (r) => {
      const a = r[0];
      a && (n.value = a.isIntersecting);
    },
    t
  );
  return G(() => {
    u = !0, e.value && l.observe(e.value);
  }), we(() => {
    u = !1, l.disconnect();
  }), Y(e, (r) => {
    !u || (l.disconnect(), n.value = !1, r && l.observe(r));
  }), n;
}
function re(e, t = p(() => ({}))) {
  const n = p(() => {
    const r = se(t.value, []);
    return e.class && e.class.split(" ").forEach((s) => {
      r[s] = !0;
    }), r;
  }), u = p(() => {
    if ("style" in e)
      return e.style;
  }), l = p(() => {
    const o = e, { class: r, style: a } = o;
    return se(o, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: u,
    otherAttrs: l
  };
}
const Pt = L({
  name: "CdxMenu",
  components: {
    CdxMenuItem: Rt,
    CdxProgressBar: Ut
  },
  inheritAttrs: !1,
  props: {
    menuItems: {
      type: Array,
      required: !0
    },
    footer: {
      type: Object,
      default: null
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
    "getHighlightedViaKeyboard",
    "delegateKeyNavigation"
  ],
  setup(e, { emit: t, slots: n, attrs: u }) {
    const l = p(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((m) => Ie(Se({}, m), {
      id: ke("menu-item")
    }))), r = p(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : l.value.length === 0 : !1), a = y(null), s = y(!1), o = y(null);
    function d() {
      return l.value.find(
        (i) => i.value === e.selected
      );
    }
    function f(i, m) {
      var v;
      if (!(m && m.disabled))
        switch (i) {
          case "selected":
            t("update:selected", (v = m == null ? void 0 : m.value) != null ? v : null), t("update:expanded", !1), o.value = null;
            break;
          case "highlighted":
            a.value = m || null, s.value = !1;
            break;
          case "highlightedViaKeyboard":
            a.value = m || null, s.value = !0;
            break;
          case "active":
            o.value = m || null;
            break;
        }
    }
    const A = p(() => {
      if (a.value !== null)
        return l.value.findIndex(
          (i) => i.value === a.value.value
        );
    });
    function b(i) {
      !i || (f("highlightedViaKeyboard", i), t("menu-item-keyboard-navigation", i));
    }
    function $(i) {
      var _;
      const m = (X) => {
        for (let H = X - 1; H >= 0; H--)
          if (!l.value[H].disabled)
            return l.value[H];
      };
      i = i || l.value.length;
      const v = (_ = m(i)) != null ? _ : m(l.value.length);
      b(v);
    }
    function E(i) {
      const m = (_) => l.value.find((X, H) => !X.disabled && H > _);
      i = i != null ? i : -1;
      const v = m(i) || m(-1);
      b(v);
    }
    function T(i, m = !0) {
      function v() {
        t("update:expanded", !0), f("highlighted", d());
      }
      function _() {
        m && (i.preventDefault(), i.stopPropagation());
      }
      switch (i.key) {
        case "Enter":
        case " ":
          return _(), e.expanded ? (a.value && s.value && t("update:selected", a.value.value), t("update:expanded", !1)) : v(), !0;
        case "Tab":
          return e.expanded && (a.value && s.value && t("update:selected", a.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return _(), e.expanded ? (a.value === null && f("highlightedViaKeyboard", d()), $(A.value)) : v(), O(), !0;
        case "ArrowDown":
          return _(), e.expanded ? (a.value === null && f("highlightedViaKeyboard", d()), E(A.value)) : v(), O(), !0;
        case "Home":
          return _(), e.expanded ? (a.value === null && f("highlightedViaKeyboard", d()), E()) : v(), O(), !0;
        case "End":
          return _(), e.expanded ? (a.value === null && f("highlightedViaKeyboard", d()), $()) : v(), O(), !0;
        case "Escape":
          return _(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function B() {
      f("active");
    }
    const S = [], ue = y(void 0), F = zt(
      ue,
      { threshold: 0.8 }
    );
    Y(F, (i) => {
      i && t("load-more");
    });
    function de(i, m) {
      if (i) {
        S[m] = i.$el;
        const v = e.visibleItemLimit;
        if (!v || e.menuItems.length < v)
          return;
        const _ = Math.min(
          v,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        m === e.menuItems.length - _ && (ue.value = i.$el);
      }
    }
    function O() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || A.value === void 0)
        return;
      const i = A.value >= 0 ? A.value : 0;
      S[i].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const q = y(null), U = y(null);
    function ae() {
      if (U.value = null, !e.visibleItemLimit || S.length <= e.visibleItemLimit) {
        q.value = null;
        return;
      }
      const i = S[0], m = S[e.visibleItemLimit];
      if (q.value = ce(
        i,
        m
      ), e.footer) {
        const v = S[S.length - 1];
        U.value = v.scrollHeight;
      }
    }
    function ce(i, m) {
      const v = i.getBoundingClientRect().top;
      return m.getBoundingClientRect().top - v + 2;
    }
    G(() => {
      document.addEventListener("mouseup", B);
    }), we(() => {
      document.removeEventListener("mouseup", B);
    }), Y(ee(e, "expanded"), (i) => pe(this, null, function* () {
      const m = d();
      !i && a.value && m === void 0 && f("highlighted"), i && m !== void 0 && f("highlighted", m), i && (yield ie(), ae(), yield ie(), O());
    })), Y(ee(e, "menuItems"), (i) => pe(this, null, function* () {
      i.length < S.length && (S.length = i.length), e.expanded && (yield ie(), ae(), yield ie(), O());
    }), { deep: !0 });
    const he = p(() => ({
      "max-height": q.value ? `${q.value}px` : void 0,
      "overflow-y": q.value ? "scroll" : void 0,
      "margin-bottom": U.value ? `${U.value}px` : void 0
    })), z = p(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!q.value
    })), {
      rootClasses: R,
      rootStyle: le,
      otherAttrs: fe
    } = re(u, z);
    return {
      listBoxStyle: he,
      rootClasses: R,
      rootStyle: le,
      otherAttrs: fe,
      assignTemplateRef: de,
      computedMenuItems: l,
      computedShowNoResultsSlot: r,
      highlightedMenuItem: a,
      highlightedViaKeyboard: s,
      activeMenuItem: o,
      handleMenuItemChange: f,
      handleKeyNavigation: T
    };
  },
  methods: {
    getHighlightedMenuItem() {
      return this.highlightedMenuItem;
    },
    getHighlightedViaKeyboard() {
      return this.highlightedViaKeyboard;
    },
    clearActive() {
      this.handleMenuItemChange("active");
    },
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
function Gt(e, t, n, u, l, r) {
  const a = k("cdx-menu-item"), s = k("cdx-progress-bar");
  return Ee((c(), g("div", {
    class: M(["cdx-menu", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    C("ul", W({
      class: "cdx-menu__listbox",
      role: "listbox",
      "aria-multiselectable": "false",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (c(), g("li", jt, [
        V(e.$slots, "pending")
      ])) : w("", !0),
      e.computedShowNoResultsSlot ? (c(), g("li", Wt, [
        V(e.$slots, "no-results")
      ])) : w("", !0),
      (c(!0), g(ve, null, Oe(e.computedMenuItems, (o, d) => {
        var f, A;
        return c(), x(a, W({
          key: o.value,
          ref_for: !0,
          ref: (b) => e.assignTemplateRef(b, d)
        }, o, {
          selected: o.value === e.selected,
          active: o.value === ((f = e.activeMenuItem) == null ? void 0 : f.value),
          highlighted: o.value === ((A = e.highlightedMenuItem) == null ? void 0 : A.value),
          "show-thumbnail": e.showThumbnail,
          "bold-label": e.boldLabel,
          "hide-description-overflow": e.hideDescriptionOverflow,
          "search-query": e.searchQuery,
          onChange: (b, $) => e.handleMenuItemChange(b, $ && o),
          onClick: (b) => e.$emit("menu-item-click", o)
        }), {
          default: Q(() => {
            var b, $;
            return [
              V(e.$slots, "default", {
                menuItem: o,
                active: o.value === ((b = e.activeMenuItem) == null ? void 0 : b.value) && o.value === (($ = e.highlightedMenuItem) == null ? void 0 : $.value)
              })
            ];
          }),
          _: 2
        }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
      }), 128)),
      e.showPending ? (c(), x(s, {
        key: 2,
        class: "cdx-menu__progress-bar",
        inline: !0
      })) : w("", !0)
    ], 16)
  ], 6)), [
    [qe, e.expanded]
  ]);
}
const Jt = /* @__PURE__ */ K(Pt, [["render", Gt]]), Xt = J(lt), Yt = J(at), Zt = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && Qe(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/button.html#default-icon-only`);
};
function be(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...be(n)) : typeof n == "object" && n && (typeof n.type == "string" || typeof n.type == "object" ? t.push(n) : n.type !== He && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...be(n.children))));
  return t;
}
const en = (e, t) => {
  if (!e)
    return !1;
  const n = be(e);
  if (n.length !== 1)
    return !1;
  const u = n[0], l = typeof u == "object" && typeof u.type == "object" && "name" in u.type && u.type.name === ne.name, r = typeof u == "object" && u.type === "svg";
  return l || r ? (Zt(t), !0) : !1;
}, tn = L({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: Yt
    },
    type: {
      type: String,
      default: "normal",
      validator: Xt
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: u }) {
    const l = y(!1);
    return {
      rootClasses: p(() => {
        var o;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--type-${e.type}`]: !0,
          "cdx-button--framed": e.type !== "quiet",
          "cdx-button--icon-only": en((o = n.default) == null ? void 0 : o.call(n), u),
          "cdx-button--is-active": l.value
        };
      }),
      onClick: (o) => {
        t("click", o);
      },
      setActive: (o) => {
        l.value = o;
      }
    };
  }
});
function nn(e, t, n, u, l, r) {
  return c(), g("button", {
    class: M(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...a) => e.onClick && e.onClick(...a)),
    onKeydown: t[1] || (t[1] = ye((a) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = ye((a) => e.setActive(!1), ["space", "enter"]))
  }, [
    V(e.$slots, "default")
  ], 34);
}
const un = /* @__PURE__ */ K(tn, [["render", nn]]);
function Fe(e, t, n) {
  return p({
    get: () => e.value,
    set: (u) => t(n || "update:modelValue", u)
  });
}
const an = J(st), ln = J(De), on = L({
  name: "CdxTextInput",
  components: { CdxIcon: ne },
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
      validator: an
    },
    status: {
      type: String,
      default: "default",
      validator: ln
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
    "keydown",
    "input",
    "change",
    "focus",
    "blur"
  ],
  setup(e, { emit: t, attrs: n }) {
    const u = Fe(ee(e, "modelValue"), t), l = p(() => e.clearable && !!u.value && !e.disabled), r = p(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": l.value,
      [`cdx-text-input--status-${e.status}`]: !0
    })), {
      rootClasses: a,
      rootStyle: s,
      otherAttrs: o
    } = re(n, r), d = p(() => ({
      "cdx-text-input__input--has-value": !!u.value
    }));
    return {
      wrappedModel: u,
      isClearable: l,
      rootClasses: a,
      rootStyle: s,
      otherAttrs: o,
      inputClasses: d,
      onClear: () => {
        u.value = "";
      },
      onInput: (B) => {
        t("input", B);
      },
      onChange: (B) => {
        t("change", B);
      },
      onKeydown: (B) => {
        (B.key === "Home" || B.key === "End") && !B.ctrlKey && !B.metaKey || t("keydown", B);
      },
      onFocus: (B) => {
        t("focus", B);
      },
      onBlur: (B) => {
        t("blur", B);
      },
      cdxIconClear: Xe
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    }
  }
});
const sn = ["type", "disabled"];
function rn(e, t, n, u, l, r) {
  const a = k("cdx-icon");
  return c(), g("div", {
    class: M(["cdx-text-input", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    Ee(C("input", W({
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (s) => e.wrappedModel = s),
      class: ["cdx-text-input__input", e.inputClasses]
    }, e.otherAttrs, {
      type: e.inputType,
      disabled: e.disabled,
      onInput: t[1] || (t[1] = (...s) => e.onInput && e.onInput(...s)),
      onChange: t[2] || (t[2] = (...s) => e.onChange && e.onChange(...s)),
      onFocus: t[3] || (t[3] = (...s) => e.onFocus && e.onFocus(...s)),
      onBlur: t[4] || (t[4] = (...s) => e.onBlur && e.onBlur(...s)),
      onKeydown: t[5] || (t[5] = (...s) => e.onKeydown && e.onKeydown(...s))
    }), null, 16, sn), [
      [Ue, e.wrappedModel]
    ]),
    e.startIcon ? (c(), x(a, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.endIcon ? (c(), x(a, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.isClearable ? (c(), x(a, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon-vue cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = Ce(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : w("", !0)
  ], 6);
}
const dn = /* @__PURE__ */ K(on, [["render", rn]]), cn = J(De), hn = L({
  name: "CdxSearchInput",
  components: {
    CdxButton: un,
    CdxTextInput: dn
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
    },
    status: {
      type: String,
      default: "default",
      validator: cn
    }
  },
  emits: [
    "update:modelValue",
    "submit-click"
  ],
  setup(e, { emit: t, attrs: n }) {
    const u = Fe(ee(e, "modelValue"), t), l = p(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: r,
      rootStyle: a,
      otherAttrs: s
    } = re(n, l);
    return {
      wrappedModel: u,
      rootClasses: r,
      rootStyle: a,
      otherAttrs: s,
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
const fn = { class: "cdx-search-input__input-wrapper" };
function pn(e, t, n, u, l, r) {
  const a = k("cdx-text-input"), s = k("cdx-button");
  return c(), g("div", {
    class: M(["cdx-search-input", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    C("div", fn, [
      j(a, W({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon,
        status: e.status
      }, e.otherAttrs, {
        onKeydown: ye(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "status", "onKeydown"]),
      V(e.$slots, "default")
    ]),
    e.buttonLabel ? (c(), x(s, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: Q(() => [
        Z(D(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : w("", !0)
  ], 6);
}
const mn = /* @__PURE__ */ K(hn, [["render", pn]]), gn = L({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: ne,
    CdxMenu: Jt,
    CdxSearchInput: mn
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
      default: it
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
    const { searchResults: l, searchFooterUrl: r, debounceInterval: a } = ze(e), s = y(), o = y(), d = ke("typeahead-search-menu"), f = y(!1), A = y(!1), b = y(!1), $ = y(!1), E = y(e.initialInputValue), T = y(""), B = p(() => {
      var h, I;
      return (I = (h = o.value) == null ? void 0 : h.getHighlightedMenuItem()) == null ? void 0 : I.id;
    }), S = y(null), ue = p(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), F = p(
      () => e.searchResults.find(
        (h) => h.value === S.value
      )
    ), de = p(
      () => r.value ? { value: P, url: r.value } : void 0
    ), O = p(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": f.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: q,
      rootStyle: U,
      otherAttrs: ae
    } = re(t, O);
    function ce(h) {
      return h;
    }
    const he = p(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let z, R;
    function le(h, I = !1) {
      F.value && F.value.label !== h && F.value.value !== h && (S.value = null), R !== void 0 && (clearTimeout(R), R = void 0), h === "" ? f.value = !1 : (A.value = !0, u["search-results-pending"] && (R = setTimeout(() => {
        $.value && (f.value = !0), b.value = !0;
      }, rt))), z !== void 0 && (clearTimeout(z), z = void 0);
      const N = () => {
        n("input", h);
      };
      I ? N() : z = setTimeout(() => {
        N();
      }, a.value);
    }
    function fe(h) {
      if (h === P) {
        S.value = null, E.value = T.value;
        return;
      }
      S.value = h, h !== null && (E.value = F.value ? F.value.label || String(F.value.value) : "");
    }
    function i() {
      $.value = !0, (T.value || b.value) && (f.value = !0);
    }
    function m() {
      $.value = !1, f.value = !1;
    }
    function v(h) {
      const Ae = h, { id: I } = Ae, N = se(Ae, ["id"]);
      if (N.value === P) {
        n("search-result-click", {
          searchResult: null,
          index: l.value.length,
          numberOfResults: l.value.length
        });
        return;
      }
      _(N);
    }
    function _(h) {
      const I = {
        searchResult: h,
        index: l.value.findIndex(
          (N) => N.value === h.value
        ),
        numberOfResults: l.value.length
      };
      n("search-result-click", I);
    }
    function X(h) {
      if (h.value === P) {
        E.value = T.value;
        return;
      }
      E.value = h.value ? h.label || String(h.value) : "";
    }
    function H(h) {
      var I;
      f.value = !1, (I = o.value) == null || I.clearActive(), v(h);
    }
    function xe(h) {
      if (F.value)
        _(F.value), h.stopPropagation(), window.location.assign(F.value.url), h.preventDefault();
      else {
        const I = {
          searchResult: null,
          index: -1,
          numberOfResults: l.value.length
        };
        n("submit", I);
      }
    }
    function Me(h) {
      if (!o.value || !T.value || h.key === " ")
        return;
      const I = o.value.getHighlightedMenuItem(), N = o.value.getHighlightedViaKeyboard();
      switch (h.key) {
        case "Enter":
          I && (I.value === P && N ? window.location.assign(r.value) : o.value.delegateKeyNavigation(h, !1)), f.value = !1;
          break;
        case "Tab":
          f.value = !1;
          break;
        default:
          o.value.delegateKeyNavigation(h);
          break;
      }
    }
    return G(() => {
      e.initialInputValue && le(e.initialInputValue, !0);
    }), Y(ee(e, "searchResults"), () => {
      T.value = E.value.trim(), $.value && A.value && T.value.length > 0 && (f.value = !0), R !== void 0 && (clearTimeout(R), R = void 0), A.value = !1, b.value = !1;
    }), {
      form: s,
      menu: o,
      menuId: d,
      highlightedId: B,
      selection: S,
      menuMessageClass: ue,
      footer: de,
      asSearchResult: ce,
      inputValue: E,
      searchQuery: T,
      expanded: f,
      showPending: b,
      rootClasses: q,
      rootStyle: U,
      otherAttrs: ae,
      menuConfig: he,
      onUpdateInputValue: le,
      onUpdateMenuSelection: fe,
      onFocus: i,
      onBlur: m,
      onSearchResultClick: v,
      onSearchResultKeyboardNavigation: X,
      onSearchFooterClick: H,
      onSubmit: xe,
      onKeydown: Me,
      MenuFooterValue: P,
      articleIcon: Je
    };
  },
  methods: {
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const vn = ["id", "action"], yn = { class: "cdx-typeahead-search__menu-message__text" }, bn = { class: "cdx-typeahead-search__menu-message__text" }, Cn = ["href", "onClickCapture"], An = { class: "cdx-typeahead-search__search-footer__text" }, Bn = { class: "cdx-typeahead-search__search-footer__query" };
function _n(e, t, n, u, l, r) {
  const a = k("cdx-icon"), s = k("cdx-menu"), o = k("cdx-search-input");
  return c(), g("div", {
    class: M(["cdx-typeahead-search", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    C("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...d) => e.onSubmit && e.onSubmit(...d))
    }, [
      j(o, W({
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
          j(s, W({
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
                class: M(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                C("span", yn, [
                  V(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": Q(() => [
              C("div", {
                class: M(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                C("span", bn, [
                  V(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: Q(({ menuItem: d, active: f }) => [
              d.value === e.MenuFooterValue ? (c(), g("a", {
                key: 0,
                class: M(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": f
                }]),
                href: e.asSearchResult(d).url,
                onClickCapture: Ce((A) => e.onSearchFooterClick(e.asSearchResult(d)), ["stop"])
              }, [
                j(a, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                C("span", An, [
                  V(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    C("strong", Bn, D(e.searchQuery), 1)
                  ])
                ])
              ], 42, Cn)) : w("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      V(e.$slots, "default")
    ], 40, vn)
  ], 6);
}
const In = /* @__PURE__ */ K(gn, [["render", _n]]);
export {
  In as CdxTypeaheadSearch
};
