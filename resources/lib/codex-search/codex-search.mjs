var Le = Object.defineProperty, Ke = Object.defineProperties;
var Re = Object.getOwnPropertyDescriptors;
var oe = Object.getOwnPropertySymbols;
var $e = Object.prototype.hasOwnProperty, Se = Object.prototype.propertyIsEnumerable;
var _e = (e, t, n) => t in e ? Le(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, Ie = (e, t) => {
  for (var n in t || (t = {}))
    $e.call(t, n) && _e(e, n, t[n]);
  if (oe)
    for (var n of oe(t))
      Se.call(t, n) && _e(e, n, t[n]);
  return e;
}, we = (e, t) => Ke(e, Re(t));
var se = (e, t) => {
  var n = {};
  for (var u in e)
    $e.call(e, u) && t.indexOf(u) < 0 && (n[u] = e[u]);
  if (e != null && oe)
    for (var u of oe(e))
      t.indexOf(u) < 0 && Se.call(e, u) && (n[u] = e[u]);
  return n;
};
var pe = (e, t, n) => new Promise((u, l) => {
  var s = (o) => {
    try {
      i(n.next(o));
    } catch (r) {
      l(r);
    }
  }, a = (o) => {
    try {
      i(n.throw(o));
    } catch (r) {
      l(r);
    }
  }, i = (o) => o.done ? u(o.value) : Promise.resolve(o.value).then(s, a);
  i((n = n.apply(e, t)).next());
});
import { ref as y, onMounted as J, defineComponent as L, computed as p, openBlock as h, createElementBlock as g, normalizeClass as x, toDisplayString as k, createCommentVNode as w, resolveComponent as E, createVNode as W, Transition as Ne, withCtx as Q, normalizeStyle as te, createElementVNode as C, createTextVNode as Z, withModifiers as Ae, renderSlot as V, createBlock as M, resolveDynamicComponent as Oe, Fragment as ye, getCurrentInstance as qe, onUnmounted as De, watch as Y, toRef as ee, nextTick as ie, withDirectives as ke, mergeProps as G, renderList as He, vShow as Qe, Comment as Ue, warn as ze, withKeys as be, vModelDynamic as Pe, toRefs as je } from "vue";
const We = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', Ge = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', Je = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Xe = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', Ye = We, Ze = Ge, et = Je, tt = Xe;
function nt(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const u = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof u == "string" || "path" in u ? u : u.ltr;
}
function ut(e, t) {
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
function at(e) {
  const t = y(null);
  return J(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function lt(e) {
  const t = y("");
  return J(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
function U(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const me = "cdx", ot = [
  "default",
  "progressive",
  "destructive"
], Ee = [
  "normal",
  "primary",
  "quiet"
], st = [
  "button",
  "submit",
  "reset"
], it = [
  "x-small",
  "small",
  "medium"
], rt = [
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
], Fe = [
  "default",
  "error"
], dt = 120, ct = 500, j = "cdx-menu-footer-item", ht = U(it), ft = L({
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
      validator: ht
    }
  },
  emits: ["click"],
  setup(e, { emit: t }) {
    const n = y(), u = at(n), l = lt(n), s = p(() => e.dir || u.value), a = p(() => e.lang || l.value), i = p(() => ({
      "cdx-icon--flipped": s.value === "rtl" && a.value !== null && ut(e.icon, a.value),
      [`cdx-icon--${e.size}`]: !0
    })), o = p(
      () => nt(e.icon, a.value || "", s.value || "ltr")
    ), r = p(() => typeof o.value == "string" ? o.value : ""), c = p(() => typeof o.value != "string" ? o.value.path : "");
    return {
      rootElement: n,
      rootClasses: i,
      iconSvg: r,
      iconPath: c,
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
}, pt = ["aria-hidden"], mt = { key: 0 }, gt = ["innerHTML"], vt = ["d"];
function yt(e, t, n, u, l, s) {
  return h(), g("span", {
    ref: "rootElement",
    class: x(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...a) => e.onClick && e.onClick(...a))
  }, [
    (h(), g("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": e.iconLabel ? void 0 : !0
    }, [
      e.iconLabel ? (h(), g("title", mt, k(e.iconLabel), 1)) : w("", !0),
      e.iconSvg ? (h(), g("g", {
        key: 1,
        innerHTML: e.iconSvg
      }, null, 8, gt)) : (h(), g("path", {
        key: 2,
        d: e.iconPath
      }, null, 8, vt))
    ], 8, pt))
  ], 2);
}
const ne = /* @__PURE__ */ K(ft, [["render", yt]]), bt = L({
  name: "CdxThumbnail",
  components: { CdxIcon: ne },
  props: {
    thumbnail: {
      type: [Object, null],
      default: null
    },
    placeholderIcon: {
      type: [String, Object],
      default: et
    }
  },
  setup: (e) => {
    const t = y(!1), n = y({}), u = (l) => {
      const s = l.replace(/([\\"\n])/g, "\\$1"), a = new Image();
      a.onload = () => {
        n.value = { backgroundImage: `url("${s}")` }, t.value = !0;
      }, a.onerror = () => {
        t.value = !1;
      }, a.src = s;
    };
    return J(() => {
      var l;
      (l = e.thumbnail) != null && l.url && u(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const Ct = { class: "cdx-thumbnail" }, At = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function Bt(e, t, n, u, l, s) {
  const a = E("cdx-icon");
  return h(), g("span", Ct, [
    e.thumbnailLoaded ? w("", !0) : (h(), g("span", At, [
      W(a, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])),
    W(Ne, { name: "cdx-thumbnail__image" }, {
      default: Q(() => [
        e.thumbnailLoaded ? (h(), g("span", {
          key: 0,
          style: te(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : w("", !0)
      ]),
      _: 1
    })
  ]);
}
const _t = /* @__PURE__ */ K(bt, [["render", Bt]]);
function $t(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const St = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function It(e, t) {
  if (!e)
    return [t, "", ""];
  const n = $t(e), u = new RegExp(
    n + St + "*",
    "i"
  ).exec(t);
  if (!u || u.index === void 0)
    return [t, "", ""];
  const l = u.index, s = l + u[0].length, a = t.slice(l, s), i = t.slice(0, l), o = t.slice(s, t.length);
  return [i, a, o];
}
const wt = L({
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
    titleChunks: p(() => It(e.searchQuery, String(e.title)))
  })
});
const Dt = { class: "cdx-search-result-title" }, kt = { class: "cdx-search-result-title__match" };
function Et(e, t, n, u, l, s) {
  return h(), g("span", Dt, [
    C("bdi", null, [
      Z(k(e.titleChunks[0]), 1),
      C("span", kt, k(e.titleChunks[1]), 1),
      Z(k(e.titleChunks[2]), 1)
    ])
  ]);
}
const Ft = /* @__PURE__ */ K(wt, [["render", Et]]), Mt = L({
  name: "CdxMenuItem",
  components: { CdxIcon: ne, CdxThumbnail: _t, CdxSearchResultTitle: Ft },
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
      e.highlighted || t("change", "highlighted", !0);
    }, u = () => {
      t("change", "highlighted", !1);
    }, l = (c) => {
      c.button === 0 && t("change", "active", !0);
    }, s = () => {
      t("change", "selected", !0);
    }, a = p(() => e.searchQuery.length > 0), i = p(() => ({
      "cdx-menu-item--selected": e.selected,
      "cdx-menu-item--active": e.active && e.highlighted,
      "cdx-menu-item--highlighted": e.highlighted,
      "cdx-menu-item--enabled": !e.disabled,
      "cdx-menu-item--disabled": e.disabled,
      "cdx-menu-item--highlight-query": a.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), o = p(() => e.url ? "a" : "span"), r = p(() => e.label || String(e.value));
    return {
      onMouseMove: n,
      onMouseLeave: u,
      onMouseDown: l,
      onClick: s,
      highlightQuery: a,
      rootClasses: i,
      contentTag: o,
      title: r
    };
  }
});
const xt = ["id", "aria-disabled", "aria-selected"], Tt = { class: "cdx-menu-item__text" }, Vt = ["lang"], Lt = ["lang"], Kt = ["lang"], Rt = ["lang"];
function Nt(e, t, n, u, l, s) {
  const a = E("cdx-thumbnail"), i = E("cdx-icon"), o = E("cdx-search-result-title");
  return h(), g("li", {
    id: e.id,
    role: "option",
    class: x(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMousemove: t[0] || (t[0] = (...r) => e.onMouseMove && e.onMouseMove(...r)),
    onMouseleave: t[1] || (t[1] = (...r) => e.onMouseLeave && e.onMouseLeave(...r)),
    onMousedown: t[2] || (t[2] = Ae((...r) => e.onMouseDown && e.onMouseDown(...r), ["prevent"])),
    onClick: t[3] || (t[3] = (...r) => e.onClick && e.onClick(...r))
  }, [
    V(e.$slots, "default", {}, () => [
      (h(), M(Oe(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: Q(() => {
          var r, c, A, b, $, D;
          return [
            e.showThumbnail ? (h(), M(a, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (h(), M(i, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : w("", !0),
            C("span", Tt, [
              e.highlightQuery ? (h(), M(o, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (r = e.language) == null ? void 0 : r.label
              }, null, 8, ["title", "search-query", "lang"])) : (h(), g("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (c = e.language) == null ? void 0 : c.label
              }, [
                C("bdi", null, k(e.title), 1)
              ], 8, Vt)),
              e.match ? (h(), g(ye, { key: 2 }, [
                Z(k(" ") + " "),
                e.highlightQuery ? (h(), M(o, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: (A = e.language) == null ? void 0 : A.match
                }, null, 8, ["title", "search-query", "lang"])) : (h(), g("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (b = e.language) == null ? void 0 : b.match
                }, [
                  C("bdi", null, k(e.match), 1)
                ], 8, Lt))
              ], 64)) : w("", !0),
              e.supportingText ? (h(), g(ye, { key: 3 }, [
                Z(k(" ") + " "),
                C("span", {
                  class: "cdx-menu-item__text__supporting-text",
                  lang: ($ = e.language) == null ? void 0 : $.supportingText
                }, [
                  C("bdi", null, k(e.supportingText), 1)
                ], 8, Kt)
              ], 64)) : w("", !0),
              e.description ? (h(), g("span", {
                key: 4,
                class: "cdx-menu-item__text__description",
                lang: (D = e.language) == null ? void 0 : D.description
              }, [
                C("bdi", null, k(e.description), 1)
              ], 8, Rt)) : w("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, xt);
}
const Ot = /* @__PURE__ */ K(Mt, [["render", Nt]]), qt = L({
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
const Ht = ["aria-disabled"], Qt = /* @__PURE__ */ C("div", { class: "cdx-progress-bar__bar" }, null, -1), Ut = [
  Qt
];
function zt(e, t, n, u, l, s) {
  return h(), g("div", {
    class: x(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Ut, 10, Ht);
}
const Pt = /* @__PURE__ */ K(qt, [["render", zt]]);
let ge = 0;
function Me(e) {
  const t = qe(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${me}-${e}-${ge++}` : n ? `${me}-${n}-${ge++}` : `${me}-${ge++}`;
}
function jt(e, t) {
  const n = y(!1);
  let u = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const l = new window.IntersectionObserver(
    (s) => {
      const a = s[0];
      a && (n.value = a.isIntersecting);
    },
    t
  );
  return J(() => {
    u = !0, e.value && l.observe(e.value);
  }), De(() => {
    u = !1, l.disconnect();
  }), Y(e, (s) => {
    !u || (l.disconnect(), n.value = !1, s && l.observe(s));
  }), n;
}
function re(e, t = p(() => ({}))) {
  const n = p(() => {
    const s = se(t.value, []);
    return e.class && e.class.split(" ").forEach((i) => {
      s[i] = !0;
    }), s;
  }), u = p(() => {
    if ("style" in e)
      return e.style;
  }), l = p(() => {
    const o = e, { class: s, style: a } = o;
    return se(o, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: u,
    otherAttrs: l
  };
}
const Wt = L({
  name: "CdxMenu",
  components: {
    CdxMenuItem: Ot,
    CdxProgressBar: Pt
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
    const l = p(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((m) => we(Ie({}, m), {
      id: Me("menu-item")
    }))), s = p(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : l.value.length === 0 : !1), a = y(null), i = y(!1), o = y(null);
    function r() {
      return l.value.find(
        (d) => d.value === e.selected
      );
    }
    function c(d, m) {
      var v;
      if (!(m && m.disabled))
        switch (d) {
          case "selected":
            t("update:selected", (v = m == null ? void 0 : m.value) != null ? v : null), t("update:expanded", !1), o.value = null;
            break;
          case "highlighted":
            a.value = m || null, i.value = !1;
            break;
          case "highlightedViaKeyboard":
            a.value = m || null, i.value = !0;
            break;
          case "active":
            o.value = m || null;
            break;
        }
    }
    const A = p(() => {
      if (a.value !== null)
        return l.value.findIndex(
          (d) => d.value === a.value.value
        );
    });
    function b(d) {
      !d || (c("highlightedViaKeyboard", d), t("menu-item-keyboard-navigation", d));
    }
    function $(d) {
      var _;
      const m = (X) => {
        for (let H = X - 1; H >= 0; H--)
          if (!l.value[H].disabled)
            return l.value[H];
      };
      d = d || l.value.length;
      const v = (_ = m(d)) != null ? _ : m(l.value.length);
      b(v);
    }
    function D(d) {
      const m = (_) => l.value.find((X, H) => !X.disabled && H > _);
      d = d != null ? d : -1;
      const v = m(d) || m(-1);
      b(v);
    }
    function T(d, m = !0) {
      function v() {
        t("update:expanded", !0), c("highlighted", r());
      }
      function _() {
        m && (d.preventDefault(), d.stopPropagation());
      }
      switch (d.key) {
        case "Enter":
        case " ":
          return _(), e.expanded ? (a.value && i.value && t("update:selected", a.value.value), t("update:expanded", !1)) : v(), !0;
        case "Tab":
          return e.expanded && (a.value && i.value && t("update:selected", a.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return _(), e.expanded ? (a.value === null && c("highlightedViaKeyboard", r()), $(A.value)) : v(), O(), !0;
        case "ArrowDown":
          return _(), e.expanded ? (a.value === null && c("highlightedViaKeyboard", r()), D(A.value)) : v(), O(), !0;
        case "Home":
          return _(), e.expanded ? (a.value === null && c("highlightedViaKeyboard", r()), D()) : v(), O(), !0;
        case "End":
          return _(), e.expanded ? (a.value === null && c("highlightedViaKeyboard", r()), $()) : v(), O(), !0;
        case "Escape":
          return _(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function B() {
      c("active");
    }
    const S = [], ue = y(void 0), F = jt(
      ue,
      { threshold: 0.8 }
    );
    Y(F, (d) => {
      d && t("load-more");
    });
    function de(d, m) {
      if (d) {
        S[m] = d.$el;
        const v = e.visibleItemLimit;
        if (!v || e.menuItems.length < v)
          return;
        const _ = Math.min(
          v,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        m === e.menuItems.length - _ && (ue.value = d.$el);
      }
    }
    function O() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || A.value === void 0)
        return;
      const d = A.value >= 0 ? A.value : 0;
      S[d].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const q = y(null), z = y(null);
    function ae() {
      if (z.value = null, !e.visibleItemLimit || S.length <= e.visibleItemLimit) {
        q.value = null;
        return;
      }
      const d = S[0], m = S[e.visibleItemLimit];
      if (q.value = ce(
        d,
        m
      ), e.footer) {
        const v = S[S.length - 1];
        z.value = v.scrollHeight;
      }
    }
    function ce(d, m) {
      const v = d.getBoundingClientRect().top;
      return m.getBoundingClientRect().top - v + 2;
    }
    J(() => {
      document.addEventListener("mouseup", B);
    }), De(() => {
      document.removeEventListener("mouseup", B);
    }), Y(ee(e, "expanded"), (d) => pe(this, null, function* () {
      const m = r();
      !d && a.value && m === void 0 && c("highlighted"), d && m !== void 0 && c("highlighted", m), d && (yield ie(), ae(), yield ie(), O());
    })), Y(ee(e, "menuItems"), (d) => pe(this, null, function* () {
      d.length < S.length && (S.length = d.length), e.expanded && (yield ie(), ae(), yield ie(), O());
    }), { deep: !0 });
    const he = p(() => ({
      "max-height": q.value ? `${q.value}px` : void 0,
      "overflow-y": q.value ? "scroll" : void 0,
      "margin-bottom": z.value ? `${z.value}px` : void 0
    })), P = p(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!q.value
    })), {
      rootClasses: R,
      rootStyle: le,
      otherAttrs: fe
    } = re(u, P);
    return {
      listBoxStyle: he,
      rootClasses: R,
      rootStyle: le,
      otherAttrs: fe,
      assignTemplateRef: de,
      computedMenuItems: l,
      computedShowNoResultsSlot: s,
      highlightedMenuItem: a,
      highlightedViaKeyboard: i,
      activeMenuItem: o,
      handleMenuItemChange: c,
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
const Gt = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Jt = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function Xt(e, t, n, u, l, s) {
  const a = E("cdx-menu-item"), i = E("cdx-progress-bar");
  return ke((h(), g("div", {
    class: x(["cdx-menu", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    C("ul", G({
      class: "cdx-menu__listbox",
      role: "listbox",
      "aria-multiselectable": "false",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (h(), g("li", Gt, [
        V(e.$slots, "pending")
      ])) : w("", !0),
      e.computedShowNoResultsSlot ? (h(), g("li", Jt, [
        V(e.$slots, "no-results")
      ])) : w("", !0),
      (h(!0), g(ye, null, He(e.computedMenuItems, (o, r) => {
        var c, A;
        return h(), M(a, G({
          key: o.value,
          ref_for: !0,
          ref: (b) => e.assignTemplateRef(b, r)
        }, o, {
          selected: o.value === e.selected,
          active: o.value === ((c = e.activeMenuItem) == null ? void 0 : c.value),
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
      e.showPending ? (h(), M(i, {
        key: 2,
        class: "cdx-menu__progress-bar",
        inline: !0
      })) : w("", !0)
    ], 16)
  ], 6)), [
    [Qe, e.expanded]
  ]);
}
const Yt = /* @__PURE__ */ K(Wt, [["render", Xt]]), Zt = U(ot), en = U([...Ee, ...st]), ve = U(Ee), tn = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && ze(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/button.html#default-icon-only`);
};
function Ce(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...Ce(n)) : typeof n == "object" && n && (typeof n.type == "string" || typeof n.type == "object" ? t.push(n) : n.type !== Ue && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...Ce(n.children))));
  return t;
}
const nn = (e, t) => {
  if (!e)
    return !1;
  const n = Ce(e);
  if (n.length !== 1)
    return !1;
  const u = n[0], l = typeof u == "object" && typeof u.type == "object" && "name" in u.type && u.type.name === ne.name, s = typeof u == "object" && u.type === "svg";
  return l || s ? (tn(t), !0) : !1;
}, un = L({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: Zt
    },
    weight: {
      type: String,
      default: "normal",
      validator: ve
    },
    type: {
      type: String,
      default: void 0,
      validator: en
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: u }) {
    const l = y(!1), s = p(
      () => ve(e.type) ? void 0 : e.type
    ), a = p(
      () => ve(e.type) ? e.type : e.weight
    );
    return {
      rootClasses: p(() => {
        var c;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--weight-${a.value}`]: !0,
          "cdx-button--framed": a.value !== "quiet",
          "cdx-button--icon-only": nn((c = n.default) == null ? void 0 : c.call(n), u),
          "cdx-button--is-active": l.value
        };
      }),
      onClick: (c) => {
        t("click", c);
      },
      setActive: (c) => {
        l.value = c;
      },
      computedType: s
    };
  }
});
const an = ["type"];
function ln(e, t, n, u, l, s) {
  return h(), g("button", {
    class: x(["cdx-button", e.rootClasses]),
    type: e.computedType,
    onClick: t[0] || (t[0] = (...a) => e.onClick && e.onClick(...a)),
    onKeydown: t[1] || (t[1] = be((a) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = be((a) => e.setActive(!1), ["space", "enter"]))
  }, [
    V(e.$slots, "default")
  ], 42, an);
}
const on = /* @__PURE__ */ K(un, [["render", ln]]);
function xe(e, t, n) {
  return p({
    get: () => e.value,
    set: (u) => t(n || "update:modelValue", u)
  });
}
const sn = U(rt), rn = U(Fe), dn = L({
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
      validator: sn
    },
    status: {
      type: String,
      default: "default",
      validator: rn
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
    const u = xe(ee(e, "modelValue"), t), l = p(() => e.clearable && !!u.value && !e.disabled), s = p(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": l.value,
      [`cdx-text-input--status-${e.status}`]: !0
    })), {
      rootClasses: a,
      rootStyle: i,
      otherAttrs: o
    } = re(n, s), r = p(() => ({
      "cdx-text-input__input--has-value": !!u.value
    }));
    return {
      wrappedModel: u,
      isClearable: l,
      rootClasses: a,
      rootStyle: i,
      otherAttrs: o,
      inputClasses: r,
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
      cdxIconClear: Ze
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    }
  }
});
const cn = ["type", "disabled"];
function hn(e, t, n, u, l, s) {
  const a = E("cdx-icon");
  return h(), g("div", {
    class: x(["cdx-text-input", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    ke(C("input", G({
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
    }), null, 16, cn), [
      [Pe, e.wrappedModel]
    ]),
    e.startIcon ? (h(), M(a, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.endIcon ? (h(), M(a, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon-vue cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : w("", !0),
    e.isClearable ? (h(), M(a, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon-vue cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = Ae(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : w("", !0)
  ], 6);
}
const fn = /* @__PURE__ */ K(dn, [["render", hn]]), pn = U(Fe), mn = L({
  name: "CdxSearchInput",
  components: {
    CdxButton: on,
    CdxTextInput: fn
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
      validator: pn
    }
  },
  emits: [
    "update:modelValue",
    "submit-click"
  ],
  setup(e, { emit: t, attrs: n }) {
    const u = xe(ee(e, "modelValue"), t), l = p(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: s,
      rootStyle: a,
      otherAttrs: i
    } = re(n, l);
    return {
      wrappedModel: u,
      rootClasses: s,
      rootStyle: a,
      otherAttrs: i,
      handleSubmit: () => {
        t("submit-click", u.value);
      },
      searchIcon: tt
    };
  },
  methods: {
    focus() {
      this.$refs.textInput.focus();
    }
  }
});
const gn = { class: "cdx-search-input__input-wrapper" };
function vn(e, t, n, u, l, s) {
  const a = E("cdx-text-input"), i = E("cdx-button");
  return h(), g("div", {
    class: x(["cdx-search-input", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    C("div", gn, [
      W(a, G({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon,
        status: e.status
      }, e.otherAttrs, {
        onKeydown: be(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "status", "onKeydown"]),
      V(e.$slots, "default")
    ]),
    e.buttonLabel ? (h(), M(i, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: Q(() => [
        Z(k(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : w("", !0)
  ], 6);
}
const yn = /* @__PURE__ */ K(mn, [["render", vn]]), bn = L({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: ne,
    CdxMenu: Yt,
    CdxSearchInput: yn
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
      default: dt
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
    const { searchResults: l, searchFooterUrl: s, debounceInterval: a } = je(e), i = y(), o = y(), r = Me("typeahead-search-menu"), c = y(!1), A = y(!1), b = y(!1), $ = y(!1), D = y(e.initialInputValue), T = y(""), B = p(() => {
      var f, I;
      return (I = (f = o.value) == null ? void 0 : f.getHighlightedMenuItem()) == null ? void 0 : I.id;
    }), S = y(null), ue = p(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), F = p(
      () => e.searchResults.find(
        (f) => f.value === S.value
      )
    ), de = p(
      () => s.value ? { value: j, url: s.value } : void 0
    ), O = p(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": c.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: q,
      rootStyle: z,
      otherAttrs: ae
    } = re(t, O);
    function ce(f) {
      return f;
    }
    const he = p(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let P, R;
    function le(f, I = !1) {
      F.value && F.value.label !== f && F.value.value !== f && (S.value = null), R !== void 0 && (clearTimeout(R), R = void 0), f === "" ? c.value = !1 : (A.value = !0, u["search-results-pending"] && (R = setTimeout(() => {
        $.value && (c.value = !0), b.value = !0;
      }, ct))), P !== void 0 && (clearTimeout(P), P = void 0);
      const N = () => {
        n("input", f);
      };
      I ? N() : P = setTimeout(() => {
        N();
      }, a.value);
    }
    function fe(f) {
      if (f === j) {
        S.value = null, D.value = T.value;
        return;
      }
      S.value = f, f !== null && (D.value = F.value ? F.value.label || String(F.value.value) : "");
    }
    function d() {
      $.value = !0, (T.value || b.value) && (c.value = !0);
    }
    function m() {
      $.value = !1, c.value = !1;
    }
    function v(f) {
      const Be = f, { id: I } = Be, N = se(Be, ["id"]);
      if (N.value === j) {
        n("search-result-click", {
          searchResult: null,
          index: l.value.length,
          numberOfResults: l.value.length
        });
        return;
      }
      _(N);
    }
    function _(f) {
      const I = {
        searchResult: f,
        index: l.value.findIndex(
          (N) => N.value === f.value
        ),
        numberOfResults: l.value.length
      };
      n("search-result-click", I);
    }
    function X(f) {
      if (f.value === j) {
        D.value = T.value;
        return;
      }
      D.value = f.value ? f.label || String(f.value) : "";
    }
    function H(f) {
      var I;
      c.value = !1, (I = o.value) == null || I.clearActive(), v(f);
    }
    function Te(f) {
      if (F.value)
        _(F.value), f.stopPropagation(), window.location.assign(F.value.url), f.preventDefault();
      else {
        const I = {
          searchResult: null,
          index: -1,
          numberOfResults: l.value.length
        };
        n("submit", I);
      }
    }
    function Ve(f) {
      if (!o.value || !T.value || f.key === " ")
        return;
      const I = o.value.getHighlightedMenuItem(), N = o.value.getHighlightedViaKeyboard();
      switch (f.key) {
        case "Enter":
          I && (I.value === j && N ? window.location.assign(s.value) : o.value.delegateKeyNavigation(f, !1)), c.value = !1;
          break;
        case "Tab":
          c.value = !1;
          break;
        default:
          o.value.delegateKeyNavigation(f);
          break;
      }
    }
    return J(() => {
      e.initialInputValue && le(e.initialInputValue, !0);
    }), Y(ee(e, "searchResults"), () => {
      T.value = D.value.trim(), $.value && A.value && T.value.length > 0 && (c.value = !0), R !== void 0 && (clearTimeout(R), R = void 0), A.value = !1, b.value = !1;
    }), {
      form: i,
      menu: o,
      menuId: r,
      highlightedId: B,
      selection: S,
      menuMessageClass: ue,
      footer: de,
      asSearchResult: ce,
      inputValue: D,
      searchQuery: T,
      expanded: c,
      showPending: b,
      rootClasses: q,
      rootStyle: z,
      otherAttrs: ae,
      menuConfig: he,
      onUpdateInputValue: le,
      onUpdateMenuSelection: fe,
      onFocus: d,
      onBlur: m,
      onSearchResultClick: v,
      onSearchResultKeyboardNavigation: X,
      onSearchFooterClick: H,
      onSubmit: Te,
      onKeydown: Ve,
      MenuFooterValue: j,
      articleIcon: Ye
    };
  },
  methods: {
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const Cn = ["id", "action"], An = { class: "cdx-typeahead-search__menu-message__text" }, Bn = { class: "cdx-typeahead-search__menu-message__text" }, _n = ["href", "onClickCapture"], $n = { class: "cdx-typeahead-search__search-footer__text" }, Sn = { class: "cdx-typeahead-search__search-footer__query" };
function In(e, t, n, u, l, s) {
  const a = E("cdx-icon"), i = E("cdx-menu"), o = E("cdx-search-input");
  return h(), g("div", {
    class: x(["cdx-typeahead-search", e.rootClasses]),
    style: te(e.rootStyle)
  }, [
    C("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...r) => e.onSubmit && e.onSubmit(...r))
    }, [
      W(o, G({
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
        default: Q(() => [
          W(i, G({
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
            pending: Q(() => [
              C("div", {
                class: x(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                C("span", An, [
                  V(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": Q(() => [
              C("div", {
                class: x(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                C("span", Bn, [
                  V(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: Q(({ menuItem: r, active: c }) => [
              r.value === e.MenuFooterValue ? (h(), g("a", {
                key: 0,
                class: x(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": c
                }]),
                href: e.asSearchResult(r).url,
                onClickCapture: Ae((A) => e.onSearchFooterClick(e.asSearchResult(r)), ["stop"])
              }, [
                W(a, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                C("span", $n, [
                  V(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    C("strong", Sn, k(e.searchQuery), 1)
                  ])
                ])
              ], 42, _n)) : w("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      V(e.$slots, "default")
    ], 40, Cn)
  ], 6);
}
const kn = /* @__PURE__ */ K(bn, [["render", In]]);
export {
  kn as CdxTypeaheadSearch
};
