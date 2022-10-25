var rt = Object.defineProperty, ct = Object.defineProperties;
var pt = Object.getOwnPropertyDescriptors;
var ce = Object.getOwnPropertySymbols;
var Te = Object.prototype.hasOwnProperty, Le = Object.prototype.propertyIsEnumerable;
var Ee = (e, t, n) => t in e ? rt(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, Fe = (e, t) => {
  for (var n in t || (t = {}))
    Te.call(t, n) && Ee(e, n, t[n]);
  if (ce)
    for (var n of ce(t))
      Le.call(t, n) && Ee(e, n, t[n]);
  return e;
}, Ve = (e, t) => ct(e, pt(t));
var pe = (e, t) => {
  var n = {};
  for (var a in e)
    Te.call(e, a) && t.indexOf(a) < 0 && (n[a] = e[a]);
  if (e != null && ce)
    for (var a of ce(e))
      t.indexOf(a) < 0 && Le.call(e, a) && (n[a] = e[a]);
  return n;
};
var ge = (e, t, n) => new Promise((a, s) => {
  var u = (l) => {
    try {
      i(n.next(l));
    } catch (c) {
      s(c);
    }
  }, o = (l) => {
    try {
      i(n.throw(l));
    } catch (c) {
      s(c);
    }
  }, i = (l) => l.done ? a(l.value) : Promise.resolve(l.value).then(u, o);
  i((n = n.apply(e, t)).next());
});
import { ref as v, onMounted as te, defineComponent as E, computed as f, openBlock as p, createElementBlock as b, normalizeClass as V, toDisplayString as U, createCommentVNode as D, Comment as ft, warn as mt, renderSlot as x, resolveComponent as A, Fragment as ie, renderList as he, createBlock as F, withCtx as K, createTextVNode as oe, createVNode as R, Transition as He, normalizeStyle as ne, resolveDynamicComponent as We, createElementVNode as g, toRef as j, withKeys as ae, withModifiers as P, withDirectives as Z, vModelCheckbox as Pe, getCurrentInstance as ht, onUnmounted as Ae, watch as J, nextTick as fe, mergeProps as Q, vShow as me, vModelDynamic as vt, useCssVars as Ie, vModelRadio as bt, inject as Ke, provide as Re, toRefs as gt } from "vue";
const ye = "cdx", yt = [
  "default",
  "progressive",
  "destructive"
], Ct = [
  "normal",
  "primary",
  "quiet"
], _t = [
  "notice",
  "warning",
  "error",
  "success"
], $t = [
  "text",
  "search"
], At = 120, It = 500, se = "cdx-menu-footer-item", Qe = Symbol("CdxTabs"), Ge = Symbol("CdxActiveTab"), Bt = '<path d="M11.53 2.3A1.85 1.85 0 0010 1.21 1.85 1.85 0 008.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z"/>', xt = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', wt = '<path d="M7 14.17 2.83 10l-1.41 1.41L7 17 19 5l-1.41-1.42z"/>', kt = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', St = '<path d="m4.34 2.93 12.73 12.73-1.41 1.41L2.93 4.35z"/><path d="M17.07 4.34 4.34 17.07l-1.41-1.41L15.66 2.93z"/>', Mt = '<path d="M13.728 1H6.272L1 6.272v7.456L6.272 19h7.456L19 13.728V6.272zM11 15H9v-2h2zm0-4H9V5h2z"/>', Dt = '<path d="m17.5 4.75-7.5 7.5-7.5-7.5L1 6.25l9 9 9-9z"/>', Et = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Tt = '<path d="M8 19a1 1 0 001 1h2a1 1 0 001-1v-1H8zm9-12a7 7 0 10-12 4.9S7 14 7 15v1a1 1 0 001 1h4a1 1 0 001-1v-1c0-1 2-3.1 2-3.1A7 7 0 0017 7z"/>', Lt = '<path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zM9 5h2v2H9zm0 4h2v6H9z"/>', Ft = '<path d="M7 1 5.6 2.5 13 10l-7.4 7.5L7 19l9-9z"/>', Vt = '<path d="m4 10 9 9 1.4-1.5L7 10l7.4-7.5L13 1z"/>', Kt = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', Rt = Bt, Nt = xt, qt = wt, Ot = kt, zt = St, Ut = Mt, Je = Dt, jt = Et, Ht = {
  langCodeMap: {
    ar: Tt
  },
  default: Lt
}, Wt = {
  ltr: Ft,
  shouldFlip: !0
}, Pt = {
  ltr: Vt,
  shouldFlip: !0
}, Qt = Kt;
function Gt(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const a = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof a == "string" || "path" in a ? a : a.ltr;
}
function Jt(e, t) {
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
  const t = v(null);
  return te(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function Xt(e) {
  const t = v("");
  return te(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
const Yt = E({
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
    const n = v(), a = Xe(n), s = Xt(n), u = f(() => e.dir || a.value), o = f(() => e.lang || s.value), i = f(() => ({
      "cdx-icon--flipped": u.value === "rtl" && o.value !== null && Jt(e.icon, o.value)
    })), l = f(
      () => Gt(e.icon, o.value || "", u.value || "ltr")
    ), c = f(() => typeof l.value == "string" ? l.value : ""), m = f(() => typeof l.value != "string" ? l.value.path : "");
    return {
      rootElement: n,
      rootClasses: i,
      iconSvg: c,
      iconPath: m,
      onClick: (y) => {
        t("click", y);
      }
    };
  }
});
const T = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [a, s] of t)
    n[a] = s;
  return n;
}, Zt = ["aria-hidden"], en = { key: 0 }, tn = ["innerHTML"], nn = ["d"];
function ln(e, t, n, a, s, u) {
  return p(), b("span", {
    ref: "rootElement",
    class: V(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    (p(), b("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": !e.iconLabel
    }, [
      e.iconLabel ? (p(), b("title", en, U(e.iconLabel), 1)) : D("", !0),
      e.iconSvg ? (p(), b("g", {
        key: 1,
        fill: "currentColor",
        innerHTML: e.iconSvg
      }, null, 8, tn)) : (p(), b("path", {
        key: 2,
        d: e.iconPath,
        fill: "currentColor"
      }, null, 8, nn))
    ], 8, Zt))
  ], 2);
}
const H = /* @__PURE__ */ T(Yt, [["render", ln]]);
function ve(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const an = ve(Ct), on = ve(yt), sn = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && mt(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/main/components/button.html#default-icon-only`);
};
function _e(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(..._e(n)) : typeof n == "object" && n && (typeof n.type == "string" || typeof n.type == "object" ? t.push(n) : n.type !== ft && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(..._e(n.children))));
  return t;
}
const un = (e, t) => {
  if (!e)
    return !1;
  const n = _e(e);
  if (n.length !== 1)
    return !1;
  const a = n[0], s = typeof a == "object" && typeof a.type == "object" && "name" in a.type && a.type.name === H.name, u = typeof a == "object" && a.type === "svg";
  return s || u ? (sn(t), !0) : !1;
}, dn = E({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: on
    },
    type: {
      type: String,
      default: "normal",
      validator: an
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: a }) {
    return {
      rootClasses: f(() => {
        var o;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--type-${e.type}`]: !0,
          "cdx-button--framed": e.type !== "quiet",
          "cdx-button--icon-only": un((o = n.default) == null ? void 0 : o.call(n), a)
        };
      }),
      onClick: (o) => {
        t("click", o);
      }
    };
  }
});
function rn(e, t, n, a, s, u) {
  return p(), b("button", {
    class: V(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    x(e.$slots, "default")
  ], 2);
}
const de = /* @__PURE__ */ T(dn, [["render", rn]]);
function Ye(e) {
  return e.label === void 0 ? e.value : e.label === null ? "" : e.label;
}
const cn = E({
  name: "CdxButtonGroup",
  components: {
    CdxButton: de,
    CdxIcon: H
  },
  props: {
    buttons: {
      type: Array,
      required: !0,
      validator: (e) => Array.isArray(e) && e.length >= 1
    },
    disabled: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "click"
  ],
  setup() {
    return {
      getButtonLabel: Ye
    };
  }
});
const pn = { class: "cdx-button-group" };
function fn(e, t, n, a, s, u) {
  const o = A("cdx-icon"), i = A("cdx-button");
  return p(), b("div", pn, [
    (p(!0), b(ie, null, he(e.buttons, (l) => (p(), F(i, {
      key: l.value,
      disabled: l.disabled || e.disabled,
      "aria-label": l.ariaLabel,
      onClick: (c) => e.$emit("click", l.value)
    }, {
      default: K(() => [
        x(e.$slots, "default", { button: l }, () => [
          l.icon ? (p(), F(o, {
            key: 0,
            icon: l.icon
          }, null, 8, ["icon"])) : D("", !0),
          oe(" " + U(e.getButtonLabel(l)), 1)
        ])
      ]),
      _: 2
    }, 1032, ["disabled", "aria-label", "onClick"]))), 128))
  ]);
}
const ua = /* @__PURE__ */ T(cn, [["render", fn]]), mn = E({
  name: "CdxThumbnail",
  components: { CdxIcon: H },
  props: {
    thumbnail: {
      type: [Object, null],
      default: null
    },
    placeholderIcon: {
      type: [String, Object],
      default: jt
    }
  },
  setup: (e) => {
    const t = v(!1), n = v({}), a = (s) => {
      const u = s.replace(/([\\"\n])/g, "\\$1"), o = new Image();
      o.onload = () => {
        n.value = { backgroundImage: `url("${u}")` }, t.value = !0;
      }, o.onerror = () => {
        t.value = !1;
      }, o.src = u;
    };
    return te(() => {
      var s;
      (s = e.thumbnail) != null && s.url && a(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const hn = { class: "cdx-thumbnail" }, vn = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function bn(e, t, n, a, s, u) {
  const o = A("cdx-icon");
  return p(), b("span", hn, [
    e.thumbnailLoaded ? D("", !0) : (p(), b("span", vn, [
      R(o, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])),
    R(He, { name: "cdx-thumbnail__image" }, {
      default: K(() => [
        e.thumbnailLoaded ? (p(), b("span", {
          key: 0,
          style: ne(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : D("", !0)
      ]),
      _: 1
    })
  ]);
}
const Ze = /* @__PURE__ */ T(mn, [["render", bn]]), gn = E({
  name: "CdxCard",
  components: { CdxIcon: H, CdxThumbnail: Ze },
  props: {
    url: {
      type: String,
      default: ""
    },
    icon: {
      type: [String, Object],
      default: ""
    },
    thumbnail: {
      type: [Object, null],
      default: null
    },
    forceThumbnail: {
      type: Boolean,
      default: !1
    },
    customPlaceholderIcon: {
      type: [String, Object],
      default: void 0
    }
  },
  setup(e) {
    const t = f(() => !!e.url), n = f(() => t.value ? "a" : "span"), a = f(() => t.value ? e.url : void 0);
    return {
      isLink: t,
      contentTag: n,
      cardLink: a
    };
  }
});
const yn = { class: "cdx-card__text" }, Cn = { class: "cdx-card__text__title" }, _n = {
  key: 0,
  class: "cdx-card__text__description"
}, $n = {
  key: 1,
  class: "cdx-card__text__supporting-text"
};
function An(e, t, n, a, s, u) {
  const o = A("cdx-thumbnail"), i = A("cdx-icon");
  return p(), F(We(e.contentTag), {
    href: e.cardLink,
    class: V(["cdx-card", {
      "cdx-card--is-link": e.isLink,
      "cdx-card--title-only": !e.$slots.description && !e.$slots["supporting-text"]
    }])
  }, {
    default: K(() => [
      e.thumbnail || e.forceThumbnail ? (p(), F(o, {
        key: 0,
        thumbnail: e.thumbnail,
        "placeholder-icon": e.customPlaceholderIcon,
        class: "cdx-card__thumbnail"
      }, null, 8, ["thumbnail", "placeholder-icon"])) : e.icon ? (p(), F(i, {
        key: 1,
        icon: e.icon,
        class: "cdx-card__icon"
      }, null, 8, ["icon"])) : D("", !0),
      g("span", yn, [
        g("span", Cn, [
          x(e.$slots, "title")
        ]),
        e.$slots.description ? (p(), b("span", _n, [
          x(e.$slots, "description")
        ])) : D("", !0),
        e.$slots["supporting-text"] ? (p(), b("span", $n, [
          x(e.$slots, "supporting-text")
        ])) : D("", !0)
      ])
    ]),
    _: 3
  }, 8, ["href", "class"]);
}
const ia = /* @__PURE__ */ T(gn, [["render", An]]);
function X(e, t, n) {
  return f({
    get: () => e.value,
    set: (a) => t(n || "update:modelValue", a)
  });
}
const In = E({
  name: "CdxCheckbox",
  props: {
    modelValue: {
      type: [Boolean, Array],
      default: !1
    },
    inputValue: {
      type: [String, Number, Boolean],
      default: !1
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    indeterminate: {
      type: Boolean,
      default: !1
    },
    inline: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(e, { emit: t }) {
    const n = f(() => ({
      "cdx-checkbox--inline": e.inline
    })), a = v(), s = v(), u = () => {
      a.value.focus();
    }, o = () => {
      s.value.click();
    }, i = X(j(e, "modelValue"), t);
    return {
      rootClasses: n,
      input: a,
      label: s,
      focusInput: u,
      clickLabel: o,
      wrappedModel: i
    };
  }
});
const Bn = ["value", "disabled", ".indeterminate"], xn = /* @__PURE__ */ g("span", { class: "cdx-checkbox__icon" }, null, -1), wn = { class: "cdx-checkbox__label-content" };
function kn(e, t, n, a, s, u) {
  return p(), b("span", {
    class: V(["cdx-checkbox", e.rootClasses])
  }, [
    g("label", {
      ref: "label",
      class: "cdx-checkbox__label",
      onClick: t[1] || (t[1] = (...o) => e.focusInput && e.focusInput(...o)),
      onKeydown: t[2] || (t[2] = ae(P((...o) => e.clickLabel && e.clickLabel(...o), ["prevent"]), ["enter"]))
    }, [
      Z(g("input", {
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-checkbox__input",
        type: "checkbox",
        value: e.inputValue,
        disabled: e.disabled,
        ".indeterminate": e.indeterminate
      }, null, 8, Bn), [
        [Pe, e.wrappedModel]
      ]),
      xn,
      g("span", wn, [
        x(e.$slots, "default")
      ])
    ], 544)
  ], 2);
}
const da = /* @__PURE__ */ T(In, [["render", kn]]);
function et(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const Sn = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function tt(e, t) {
  if (!e)
    return [t, "", ""];
  const n = et(e), a = new RegExp(
    n + Sn + "*",
    "i"
  ).exec(t);
  if (!a || a.index === void 0)
    return [t, "", ""];
  const s = a.index, u = s + a[0].length, o = t.slice(s, u), i = t.slice(0, s), l = t.slice(u, t.length);
  return [i, o, l];
}
const ra = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  regExpEscape: et,
  splitStringAtMatch: tt
}, Symbol.toStringTag, { value: "Module" })), Mn = E({
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
    titleChunks: f(() => tt(e.searchQuery, String(e.title)))
  })
});
const Dn = { class: "cdx-search-result-title" }, En = { class: "cdx-search-result-title__match" };
function Tn(e, t, n, a, s, u) {
  return p(), b("span", Dn, [
    g("bdi", null, [
      oe(U(e.titleChunks[0]), 1),
      g("span", En, U(e.titleChunks[1]), 1),
      oe(U(e.titleChunks[2]), 1)
    ])
  ]);
}
const Ln = /* @__PURE__ */ T(Mn, [["render", Tn]]), Fn = E({
  name: "CdxMenuItem",
  components: { CdxIcon: H, CdxThumbnail: Ze, CdxSearchResultTitle: Ln },
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
    }, a = () => {
      t("change", "highlighted", !1);
    }, s = (m) => {
      m.button === 0 && t("change", "active", !0);
    }, u = () => {
      t("change", "selected", !0);
    }, o = f(() => e.searchQuery.length > 0), i = f(() => ({
      "cdx-menu-item--selected": e.selected,
      "cdx-menu-item--active": e.active && e.highlighted,
      "cdx-menu-item--highlighted": e.highlighted,
      "cdx-menu-item--enabled": !e.disabled,
      "cdx-menu-item--disabled": e.disabled,
      "cdx-menu-item--highlight-query": o.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), l = f(() => e.url ? "a" : "span"), c = f(() => e.label || String(e.value));
    return {
      onMouseEnter: n,
      onMouseLeave: a,
      onMouseDown: s,
      onClick: u,
      highlightQuery: o,
      rootClasses: i,
      contentTag: l,
      title: c
    };
  }
});
const Vn = ["id", "aria-disabled", "aria-selected"], Kn = { class: "cdx-menu-item__text" }, Rn = ["lang"], Nn = /* @__PURE__ */ oe(/* @__PURE__ */ U(" ") + " "), qn = ["lang"], On = ["lang"];
function zn(e, t, n, a, s, u) {
  const o = A("cdx-thumbnail"), i = A("cdx-icon"), l = A("cdx-search-result-title");
  return p(), b("li", {
    id: e.id,
    role: "option",
    class: V(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMouseenter: t[0] || (t[0] = (...c) => e.onMouseEnter && e.onMouseEnter(...c)),
    onMouseleave: t[1] || (t[1] = (...c) => e.onMouseLeave && e.onMouseLeave(...c)),
    onMousedown: t[2] || (t[2] = P((...c) => e.onMouseDown && e.onMouseDown(...c), ["prevent"])),
    onClick: t[3] || (t[3] = (...c) => e.onClick && e.onClick(...c))
  }, [
    x(e.$slots, "default", {}, () => [
      (p(), F(We(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: K(() => {
          var c, m, $, y, M;
          return [
            e.showThumbnail ? (p(), F(o, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (p(), F(i, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : D("", !0),
            g("span", Kn, [
              e.highlightQuery ? (p(), F(l, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (c = e.language) == null ? void 0 : c.label
              }, null, 8, ["title", "search-query", "lang"])) : (p(), b("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (m = e.language) == null ? void 0 : m.label
              }, [
                g("bdi", null, U(e.title), 1)
              ], 8, Rn)),
              e.match ? (p(), b(ie, { key: 2 }, [
                Nn,
                e.highlightQuery ? (p(), F(l, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: ($ = e.language) == null ? void 0 : $.match
                }, null, 8, ["title", "search-query", "lang"])) : (p(), b("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (y = e.language) == null ? void 0 : y.match
                }, [
                  g("bdi", null, U(e.match), 1)
                ], 8, qn))
              ], 64)) : D("", !0),
              e.description ? (p(), b("span", {
                key: 3,
                class: "cdx-menu-item__text__description",
                lang: (M = e.language) == null ? void 0 : M.description
              }, [
                g("bdi", null, U(e.description), 1)
              ], 8, On)) : D("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, Vn);
}
const Un = /* @__PURE__ */ T(Fn, [["render", zn]]), jn = E({
  name: "CdxProgressBar",
  props: {
    inline: {
      type: Boolean,
      default: !1
    }
  },
  setup(e) {
    return {
      rootClasses: f(() => ({
        "cdx-progress-bar--block": !e.inline,
        "cdx-progress-bar--inline": e.inline
      }))
    };
  }
});
const Hn = /* @__PURE__ */ g("div", { class: "cdx-progress-bar__bar" }, null, -1), Wn = [
  Hn
];
function Pn(e, t, n, a, s, u) {
  return p(), b("div", {
    class: V(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Wn, 2);
}
const Qn = /* @__PURE__ */ T(jn, [["render", Pn]]);
let Ce = 0;
function ee(e) {
  const t = ht(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${ye}-${e}-${Ce++}` : n ? `${ye}-${n}-${Ce++}` : `${ye}-${Ce++}`;
}
function $e(e, t) {
  const n = v(!1);
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
  return te(() => {
    a = !0, e.value && s.observe(e.value);
  }), Ae(() => {
    a = !1, s.disconnect();
  }), J(e, (u) => {
    !a || (s.disconnect(), n.value = !1, u && s.observe(u));
  }), n;
}
const Gn = E({
  name: "CdxMenu",
  components: {
    CdxMenuItem: Un,
    CdxProgressBar: Qn
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
    const a = f(() => e.menuItems.map((d) => Ve(Fe({}, d), {
      id: ee("menu-item")
    }))), s = f(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : a.value.length === 0 : !1), u = v(null), o = v(null);
    function i() {
      return a.value.find(
        (d) => d.value === e.selected
      );
    }
    function l(d, r) {
      var h;
      if (!(r && r.disabled))
        switch (d) {
          case "selected":
            t("update:selected", (h = r == null ? void 0 : r.value) != null ? h : null), t("update:expanded", !1), o.value = null;
            break;
          case "highlighted":
            u.value = r || null;
            break;
          case "active":
            o.value = r || null;
            break;
        }
    }
    const c = f(() => {
      if (u.value !== null)
        return a.value.findIndex(
          (d) => d.value === u.value.value
        );
    });
    function m(d) {
      !d || (l("highlighted", d), t("menu-item-keyboard-navigation", d));
    }
    function $(d) {
      var C;
      const r = (B) => {
        for (let N = B - 1; N >= 0; N--)
          if (!a.value[N].disabled)
            return a.value[N];
      };
      d = d || a.value.length;
      const h = (C = r(d)) != null ? C : r(a.value.length);
      m(h);
    }
    function y(d) {
      const r = (C) => a.value.find((B, N) => !B.disabled && N > C);
      d = d != null ? d : -1;
      const h = r(d) || r(-1);
      m(h);
    }
    function M(d, r = !0) {
      function h() {
        t("update:expanded", !0), l("highlighted", i());
      }
      function C() {
        r && (d.preventDefault(), d.stopPropagation());
      }
      switch (d.key) {
        case "Enter":
        case " ":
          return C(), e.expanded ? (u.value && t("update:selected", u.value.value), t("update:expanded", !1)) : h(), !0;
        case "Tab":
          return e.expanded && (u.value && t("update:selected", u.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return C(), e.expanded ? (u.value === null && l("highlighted", i()), $(c.value)) : h(), I(), !0;
        case "ArrowDown":
          return C(), e.expanded ? (u.value === null && l("highlighted", i()), y(c.value)) : h(), I(), !0;
        case "Home":
          return C(), e.expanded ? (u.value === null && l("highlighted", i()), y()) : h(), I(), !0;
        case "End":
          return C(), e.expanded ? (u.value === null && l("highlighted", i()), $()) : h(), I(), !0;
        case "Escape":
          return C(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function k() {
      l("active");
    }
    const S = [], w = v(void 0), q = $e(
      w,
      { threshold: 0.8 }
    );
    J(q, (d) => {
      d && t("load-more");
    });
    function L(d, r) {
      if (d) {
        S[r] = d.$el;
        const h = e.visibleItemLimit;
        if (!h || e.menuItems.length < h)
          return;
        const C = Math.min(
          h,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        r === e.menuItems.length - C && (w.value = d.$el);
      }
    }
    function I() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || c.value === void 0)
        return;
      const d = c.value >= 0 ? c.value : 0;
      S[d].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const O = v(null);
    function G() {
      if (!e.visibleItemLimit || S.length <= e.visibleItemLimit) {
        O.value = null;
        return;
      }
      const d = S[0], r = S[e.visibleItemLimit];
      O.value = W(
        d,
        r
      );
    }
    function W(d, r) {
      const h = d.getBoundingClientRect().top;
      return r.getBoundingClientRect().top - h + 2;
    }
    return te(() => {
      document.addEventListener("mouseup", k);
    }), Ae(() => {
      document.removeEventListener("mouseup", k);
    }), J(j(e, "expanded"), (d) => ge(this, null, function* () {
      const r = i();
      !d && u.value && r === void 0 && l("highlighted"), d && r !== void 0 && l("highlighted", r), d && (yield fe(), G(), yield fe(), I());
    })), J(j(e, "menuItems"), (d) => ge(this, null, function* () {
      d.length < S.length && (S.length = d.length), e.expanded && (yield fe(), G(), yield fe(), I());
    })), {
      rootStyle: f(() => ({
        "max-height": O.value ? `${O.value}px` : void 0,
        "overflow-y": O.value ? "scroll" : void 0
      })),
      assignTemplateRef: L,
      computedMenuItems: a,
      computedShowNoResultsSlot: s,
      highlightedMenuItem: u,
      activeMenuItem: o,
      handleMenuItemChange: l,
      handleKeyNavigation: M
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
const Jn = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Xn = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function Yn(e, t, n, a, s, u) {
  const o = A("cdx-menu-item"), i = A("cdx-progress-bar");
  return Z((p(), b("ul", {
    class: "cdx-menu",
    role: "listbox",
    "aria-multiselectable": "false",
    style: ne(e.rootStyle)
  }, [
    e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (p(), b("li", Jn, [
      x(e.$slots, "pending")
    ])) : D("", !0),
    e.computedShowNoResultsSlot ? (p(), b("li", Xn, [
      x(e.$slots, "no-results")
    ])) : D("", !0),
    (p(!0), b(ie, null, he(e.computedMenuItems, (l, c) => {
      var m, $;
      return p(), F(o, Q({
        key: l.value,
        ref_for: !0,
        ref: (y) => e.assignTemplateRef(y, c)
      }, l, {
        selected: l.value === e.selected,
        active: l.value === ((m = e.activeMenuItem) == null ? void 0 : m.value),
        highlighted: l.value === (($ = e.highlightedMenuItem) == null ? void 0 : $.value),
        "show-thumbnail": e.showThumbnail,
        "bold-label": e.boldLabel,
        "hide-description-overflow": e.hideDescriptionOverflow,
        "search-query": e.searchQuery,
        onChange: (y, M) => e.handleMenuItemChange(y, M && l),
        onClick: (y) => e.$emit("menu-item-click", l)
      }), {
        default: K(() => {
          var y, M;
          return [
            x(e.$slots, "default", {
              menuItem: l,
              active: l.value === ((y = e.activeMenuItem) == null ? void 0 : y.value) && l.value === ((M = e.highlightedMenuItem) == null ? void 0 : M.value)
            })
          ];
        }),
        _: 2
      }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
    }), 128)),
    e.showPending ? (p(), F(i, {
      key: 2,
      class: "cdx-menu__progress-bar",
      inline: !0
    })) : D("", !0)
  ], 4)), [
    [me, e.expanded]
  ]);
}
const be = /* @__PURE__ */ T(Gn, [["render", Yn]]);
function ue(e, t = f(() => ({}))) {
  const n = f(() => {
    const u = pe(t.value, []);
    return e.class && e.class.split(" ").forEach((i) => {
      u[i] = !0;
    }), u;
  }), a = f(() => {
    if ("style" in e)
      return e.style;
  }), s = f(() => {
    const l = e, { class: u, style: o } = l;
    return pe(l, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: a,
    otherAttrs: s
  };
}
const Zn = ve($t), el = E({
  name: "CdxTextInput",
  components: { CdxIcon: H },
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
      validator: Zn
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
    const a = X(j(e, "modelValue"), t), s = f(() => e.clearable && !!a.value && !e.disabled), u = f(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": s.value
    })), {
      rootClasses: o,
      rootStyle: i,
      otherAttrs: l
    } = ue(n, u), c = f(() => ({
      "cdx-text-input__input--has-value": !!a.value
    }));
    return {
      wrappedModel: a,
      isClearable: s,
      rootClasses: o,
      rootStyle: i,
      otherAttrs: l,
      inputClasses: c,
      onClear: () => {
        a.value = "";
      },
      onInput: (w) => {
        t("input", w);
      },
      onChange: (w) => {
        t("change", w);
      },
      onKeydown: (w) => {
        (w.key === "Home" || w.key === "End") && !w.ctrlKey && !w.metaKey || t("keydown", w);
      },
      onFocus: (w) => {
        t("focus", w);
      },
      onBlur: (w) => {
        t("blur", w);
      },
      cdxIconClear: Ot
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    }
  }
});
const tl = ["type", "disabled"];
function nl(e, t, n, a, s, u) {
  const o = A("cdx-icon");
  return p(), b("div", {
    class: V(["cdx-text-input", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    Z(g("input", Q({
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
    }), null, 16, tl), [
      [vt, e.wrappedModel]
    ]),
    e.startIcon ? (p(), F(o, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : D("", !0),
    e.endIcon ? (p(), F(o, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : D("", !0),
    e.isClearable ? (p(), F(o, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = P(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : D("", !0)
  ], 6);
}
const Be = /* @__PURE__ */ T(el, [["render", nl]]);
function xe(e) {
  const t = v(
    { width: void 0, height: void 0 }
  );
  if (typeof window != "object" || !("ResizeObserver" in window) || !("ResizeObserverEntry" in window))
    return t;
  const n = new window.ResizeObserver(
    (s) => {
      const u = s[0];
      u && (t.value = {
        width: u.borderBoxSize[0].inlineSize,
        height: u.borderBoxSize[0].blockSize
      });
    }
  );
  let a = !1;
  return te(() => {
    a = !0, e.value && n.observe(e.value);
  }), Ae(() => {
    a = !1, n.disconnect();
  }), J(e, (s) => {
    !a || (n.disconnect(), t.value = {
      width: void 0,
      height: void 0
    }, s && n.observe(s));
  }), t;
}
const we = E({
  name: "CdxCombobox",
  components: {
    CdxButton: de,
    CdxIcon: H,
    CdxMenu: be,
    CdxTextInput: Be
  },
  inheritAttrs: !1,
  props: {
    menuItems: {
      type: Array,
      required: !0
    },
    selected: {
      type: [String, Number],
      required: !0
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    menuConfig: {
      type: Object,
      default: () => ({})
    }
  },
  emits: [
    "update:selected",
    "load-more"
  ],
  setup(e, { emit: t, attrs: n, slots: a }) {
    const s = v(), u = v(), o = v(), i = ee("combobox"), l = j(e, "selected"), c = X(l, t, "update:selected"), m = v(!1), $ = v(!1), y = f(() => {
      var d, r;
      return (r = (d = o.value) == null ? void 0 : d.getHighlightedMenuItem()) == null ? void 0 : r.id;
    }), M = f(() => ({
      "cdx-combobox--disabled": e.disabled
    })), k = xe(u), S = f(() => {
      var d;
      return `${(d = k.value.width) != null ? d : 0}px`;
    }), {
      rootClasses: w,
      rootStyle: q,
      otherAttrs: L
    } = ue(n, M);
    function I() {
      $.value && m.value ? m.value = !1 : (e.menuItems.length > 0 || a["no-results"]) && (m.value = !0);
    }
    function O() {
      m.value = $.value && m.value;
    }
    function G() {
      e.disabled || ($.value = !0);
    }
    function W() {
      var d;
      e.disabled || (d = s.value) == null || d.focus();
    }
    function Y(d) {
      !o.value || e.disabled || e.menuItems.length === 0 || d.key === " " && m.value || o.value.delegateKeyNavigation(d);
    }
    return J(m, () => {
      $.value = !1;
    }), {
      input: s,
      inputWrapper: u,
      currentWidthInPx: S,
      menu: o,
      menuId: i,
      modelWrapper: c,
      expanded: m,
      highlightedId: y,
      onInputFocus: I,
      onInputBlur: O,
      onKeydown: Y,
      onButtonClick: W,
      onButtonMousedown: G,
      cdxIconExpand: Je,
      rootClasses: w,
      rootStyle: q,
      otherAttrs: L
    };
  }
}), Ne = () => {
  Ie((e) => ({
    "1eb055a5": e.currentWidthInPx
  }));
}, qe = we.setup;
we.setup = qe ? (e, t) => (Ne(), qe(e, t)) : Ne;
const ll = {
  ref: "inputWrapper",
  class: "cdx-combobox__input-wrapper"
};
function al(e, t, n, a, s, u) {
  const o = A("cdx-text-input"), i = A("cdx-icon"), l = A("cdx-button"), c = A("cdx-menu");
  return p(), b("div", {
    class: V(["cdx-combobox", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    g("div", ll, [
      R(o, Q({
        ref: "input",
        modelValue: e.modelWrapper,
        "onUpdate:modelValue": t[0] || (t[0] = (m) => e.modelWrapper = m)
      }, e.otherAttrs, {
        class: "cdx-combobox__input",
        "aria-activedescendant": e.highlightedId,
        "aria-expanded": e.expanded,
        "aria-owns": e.menuId,
        disabled: e.disabled,
        "aria-autocomplete": "list",
        autocomplete: "off",
        role: "combobox",
        onKeydown: e.onKeydown,
        onFocus: e.onInputFocus,
        onBlur: e.onInputBlur
      }), null, 16, ["modelValue", "aria-activedescendant", "aria-expanded", "aria-owns", "disabled", "onKeydown", "onFocus", "onBlur"]),
      R(l, {
        class: "cdx-combobox__expand-button",
        "aria-hidden": "true",
        disabled: e.disabled,
        tabindex: "-1",
        onMousedown: e.onButtonMousedown,
        onClick: e.onButtonClick
      }, {
        default: K(() => [
          R(i, {
            class: "cdx-combobox__expand-icon",
            icon: e.cdxIconExpand
          }, null, 8, ["icon"])
        ]),
        _: 1
      }, 8, ["disabled", "onMousedown", "onClick"])
    ], 512),
    R(c, Q({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[1] || (t[1] = (m) => e.modelWrapper = m),
      expanded: e.expanded,
      "onUpdate:expanded": t[2] || (t[2] = (m) => e.expanded = m),
      "menu-items": e.menuItems
    }, e.menuConfig, {
      onLoadMore: t[3] || (t[3] = (m) => e.$emit("load-more"))
    }), {
      default: K(({ menuItem: m }) => [
        x(e.$slots, "menu-item", { menuItem: m })
      ]),
      "no-results": K(() => [
        x(e.$slots, "no-results")
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 6);
}
const ca = /* @__PURE__ */ T(we, [["render", al]]), ke = E({
  name: "CdxLookup",
  components: {
    CdxMenu: be,
    CdxTextInput: Be
  },
  inheritAttrs: !1,
  props: {
    selected: {
      type: [String, Number, null],
      required: !0
    },
    menuItems: {
      type: Array,
      required: !0
    },
    initialInputValue: {
      type: [String, Number],
      default: ""
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    menuConfig: {
      type: Object,
      default: () => ({})
    }
  },
  emits: [
    "update:selected",
    "input",
    "load-more"
  ],
  setup: (e, { emit: t, attrs: n, slots: a }) => {
    const s = v(), u = v(), o = ee("lookup-menu"), i = v(!1), l = v(!1), c = v(!1), m = j(e, "selected"), $ = X(m, t, "update:selected"), y = f(
      () => e.menuItems.find((r) => r.value === e.selected)
    ), M = f(() => {
      var r, h;
      return (h = (r = u.value) == null ? void 0 : r.getHighlightedMenuItem()) == null ? void 0 : h.id;
    }), k = v(e.initialInputValue), S = xe(s), w = f(() => {
      var r;
      return `${(r = S.value.width) != null ? r : 0}px`;
    }), q = f(() => ({
      "cdx-lookup--disabled": e.disabled,
      "cdx-lookup--pending": i.value
    })), {
      rootClasses: L,
      rootStyle: I,
      otherAttrs: O
    } = ue(n, q);
    function G(r) {
      y.value && y.value.label !== r && y.value.value !== r && ($.value = null), r === "" ? (l.value = !1, i.value = !1) : i.value = !0, t("input", r);
    }
    function W() {
      c.value = !0, k.value !== null && k.value !== "" && (e.menuItems.length > 0 || a["no-results"]) && (l.value = !0);
    }
    function Y() {
      c.value = !1, l.value = !1;
    }
    function d(r) {
      !u.value || e.disabled || e.menuItems.length === 0 && !a["no-results"] || r.key === " " && l.value || u.value.delegateKeyNavigation(r);
    }
    return J(m, (r) => {
      if (r !== null) {
        const h = y.value ? y.value.label || y.value.value : "";
        k.value !== h && (k.value = h, t("input", k.value));
      }
    }), J(j(e, "menuItems"), (r) => {
      c.value && i.value && (r.length > 0 || a["no-results"]) && (l.value = !0), r.length === 0 && !a["no-results"] && (l.value = !1), i.value = !1;
    }), {
      rootElement: s,
      currentWidthInPx: w,
      menu: u,
      menuId: o,
      highlightedId: M,
      inputValue: k,
      modelWrapper: $,
      expanded: l,
      onInputBlur: Y,
      rootClasses: L,
      rootStyle: I,
      otherAttrs: O,
      onUpdateInput: G,
      onInputFocus: W,
      onKeydown: d
    };
  }
}), Oe = () => {
  Ie((e) => ({
    "34b66b04": e.currentWidthInPx
  }));
}, ze = ke.setup;
ke.setup = ze ? (e, t) => (Oe(), ze(e, t)) : Oe;
function ol(e, t, n, a, s, u) {
  const o = A("cdx-text-input"), i = A("cdx-menu");
  return p(), b("div", {
    ref: "rootElement",
    class: V(["cdx-lookup", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    R(o, Q({
      modelValue: e.inputValue,
      "onUpdate:modelValue": t[0] || (t[0] = (l) => e.inputValue = l)
    }, e.otherAttrs, {
      class: "cdx-lookup__input",
      role: "combobox",
      autocomplete: "off",
      "aria-autocomplete": "list",
      "aria-owns": e.menuId,
      "aria-expanded": e.expanded,
      "aria-activedescendant": e.highlightedId,
      disabled: e.disabled,
      "onUpdate:modelValue": e.onUpdateInput,
      onFocus: e.onInputFocus,
      onBlur: e.onInputBlur,
      onKeydown: e.onKeydown
    }), null, 16, ["modelValue", "aria-owns", "aria-expanded", "aria-activedescendant", "disabled", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
    R(i, Q({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[1] || (t[1] = (l) => e.modelWrapper = l),
      expanded: e.expanded,
      "onUpdate:expanded": t[2] || (t[2] = (l) => e.expanded = l),
      "menu-items": e.menuItems
    }, e.menuConfig, {
      onLoadMore: t[3] || (t[3] = (l) => e.$emit("load-more"))
    }), {
      default: K(({ menuItem: l }) => [
        x(e.$slots, "menu-item", { menuItem: l })
      ]),
      "no-results": K(() => [
        x(e.$slots, "no-results")
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 6);
}
const pa = /* @__PURE__ */ T(ke, [["render", ol]]), sl = ve(_t), ul = {
  notice: Ht,
  error: Ut,
  warning: Rt,
  success: qt
}, il = E({
  name: "CdxMessage",
  components: { CdxButton: de, CdxIcon: H },
  props: {
    type: {
      type: String,
      default: "notice",
      validator: sl
    },
    inline: {
      type: Boolean,
      default: !1
    },
    icon: {
      type: [String, Object],
      default: null
    },
    fadeIn: {
      type: Boolean,
      default: !1
    },
    dismissButtonLabel: {
      type: String,
      default: ""
    },
    autoDismiss: {
      type: [Boolean, Number],
      default: !1,
      validator: (e) => typeof e == "boolean" || typeof e == "number" && e > 0
    }
  },
  emits: [
    "user-dismissed",
    "auto-dismissed"
  ],
  setup(e, { emit: t }) {
    const n = v(!1), a = f(
      () => e.inline === !1 && e.dismissButtonLabel.length > 0
    ), s = f(() => e.autoDismiss === !1 ? !1 : e.autoDismiss === !0 ? 4e3 : e.autoDismiss), u = f(() => ({
      "cdx-message--inline": e.inline,
      "cdx-message--block": !e.inline,
      "cdx-message--user-dismissable": a.value,
      [`cdx-message--${e.type}`]: !0
    })), o = f(
      () => e.icon && e.type === "notice" ? e.icon : ul[e.type]
    ), i = v("");
    function l(c) {
      n.value || (i.value = c === "user-dismissed" ? "cdx-message-leave-active-user" : "cdx-message-leave-active-system", n.value = !0, t(c));
    }
    return te(() => {
      s.value && setTimeout(() => l("auto-dismissed"), s.value);
    }), {
      dismissed: n,
      userDismissable: a,
      rootClasses: u,
      leaveActiveClass: i,
      computedIcon: o,
      onDismiss: l,
      cdxIconClose: zt
    };
  }
});
const dl = ["aria-live", "role"], rl = { class: "cdx-message__content" };
function cl(e, t, n, a, s, u) {
  const o = A("cdx-icon"), i = A("cdx-button");
  return p(), F(He, {
    name: "cdx-message",
    appear: e.fadeIn,
    "leave-active-class": e.leaveActiveClass
  }, {
    default: K(() => [
      e.dismissed ? D("", !0) : (p(), b("div", {
        key: 0,
        class: V(["cdx-message", e.rootClasses]),
        "aria-live": e.type !== "error" ? "polite" : void 0,
        role: e.type === "error" ? "alert" : void 0
      }, [
        R(o, {
          class: "cdx-message__icon",
          icon: e.computedIcon
        }, null, 8, ["icon"]),
        g("div", rl, [
          x(e.$slots, "default")
        ]),
        e.userDismissable ? (p(), F(i, {
          key: 0,
          class: "cdx-message__dismiss-button",
          type: "quiet",
          "aria-label": e.dismissButtonLabel,
          onClick: t[0] || (t[0] = (l) => e.onDismiss("user-dismissed"))
        }, {
          default: K(() => [
            R(o, {
              icon: e.cdxIconClose,
              "icon-label": e.dismissButtonLabel
            }, null, 8, ["icon", "icon-label"])
          ]),
          _: 1
        }, 8, ["aria-label"])) : D("", !0)
      ], 10, dl))
    ]),
    _: 3
  }, 8, ["appear", "leave-active-class"]);
}
const fa = /* @__PURE__ */ T(il, [["render", cl]]), pl = E({
  name: "CdxRadio",
  props: {
    modelValue: {
      type: [String, Number, Boolean],
      default: ""
    },
    inputValue: {
      type: [String, Number, Boolean],
      default: !1
    },
    name: {
      type: String,
      default: ""
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    inline: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(e, { emit: t }) {
    const n = f(() => ({
      "cdx-radio--inline": e.inline
    })), a = v(), s = () => {
      a.value.focus();
    }, u = X(j(e, "modelValue"), t);
    return {
      rootClasses: n,
      input: a,
      focusInput: s,
      wrappedModel: u
    };
  }
});
const fl = ["name", "value", "disabled"], ml = /* @__PURE__ */ g("span", { class: "cdx-radio__icon" }, null, -1), hl = { class: "cdx-radio__label-content" };
function vl(e, t, n, a, s, u) {
  return p(), b("span", {
    class: V(["cdx-radio", e.rootClasses])
  }, [
    g("label", {
      class: "cdx-radio__label",
      onClick: t[1] || (t[1] = (...o) => e.focusInput && e.focusInput(...o))
    }, [
      Z(g("input", {
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-radio__input",
        type: "radio",
        name: e.name,
        value: e.inputValue,
        disabled: e.disabled
      }, null, 8, fl), [
        [bt, e.wrappedModel]
      ]),
      ml,
      g("span", hl, [
        x(e.$slots, "default")
      ])
    ])
  ], 2);
}
const ma = /* @__PURE__ */ T(pl, [["render", vl]]), bl = E({
  name: "CdxSearchInput",
  components: {
    CdxButton: de,
    CdxTextInput: Be
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
    const a = X(j(e, "modelValue"), t), s = f(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: u,
      rootStyle: o,
      otherAttrs: i
    } = ue(n, s);
    return {
      wrappedModel: a,
      rootClasses: u,
      rootStyle: o,
      otherAttrs: i,
      handleSubmit: () => {
        t("submit-click", a.value);
      },
      searchIcon: Qt
    };
  },
  methods: {
    focus() {
      this.$refs.textInput.focus();
    }
  }
});
const gl = { class: "cdx-search-input__input-wrapper" };
function yl(e, t, n, a, s, u) {
  const o = A("cdx-text-input"), i = A("cdx-button");
  return p(), b("div", {
    class: V(["cdx-search-input", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    g("div", gl, [
      R(o, Q({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (l) => e.wrappedModel = l),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon
      }, e.otherAttrs, {
        onKeydown: ae(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "onKeydown"]),
      x(e.$slots, "default")
    ]),
    e.buttonLabel ? (p(), F(i, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: K(() => [
        oe(U(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : D("", !0)
  ], 6);
}
const Cl = /* @__PURE__ */ T(bl, [["render", yl]]), Se = E({
  name: "CdxSelect",
  components: {
    CdxIcon: H,
    CdxMenu: be
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
    defaultLabel: {
      type: String,
      default: ""
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    menuConfig: {
      type: Object,
      default: () => ({})
    },
    defaultIcon: {
      type: [String, Object],
      default: void 0
    }
  },
  emits: [
    "update:selected",
    "load-more"
  ],
  setup(e, { emit: t }) {
    const n = v(), a = v(), s = ee("select-handle"), u = ee("select-menu"), o = v(!1), i = X(j(e, "selected"), t, "update:selected"), l = f(
      () => e.menuItems.find((L) => L.value === e.selected)
    ), c = f(() => l.value ? l.value.label || l.value.value : e.defaultLabel), m = xe(n), $ = f(() => {
      var L;
      return `${(L = m.value.width) != null ? L : 0}px`;
    }), y = f(() => {
      if (e.defaultIcon && !l.value)
        return e.defaultIcon;
      if (l.value && l.value.icon)
        return l.value.icon;
    }), M = f(() => ({
      "cdx-select--enabled": !e.disabled,
      "cdx-select--disabled": e.disabled,
      "cdx-select--expanded": o.value,
      "cdx-select--value-selected": !!l.value,
      "cdx-select--no-selections": !l.value,
      "cdx-select--has-start-icon": !!y.value
    })), k = f(() => {
      var L, I;
      return (I = (L = a.value) == null ? void 0 : L.getHighlightedMenuItem()) == null ? void 0 : I.id;
    });
    function S() {
      o.value = !1;
    }
    function w() {
      var L;
      e.disabled || (o.value = !o.value, (L = n.value) == null || L.focus());
    }
    function q(L) {
      var I;
      e.disabled || (I = a.value) == null || I.delegateKeyNavigation(L);
    }
    return {
      handle: n,
      handleId: s,
      menu: a,
      menuId: u,
      modelWrapper: i,
      selectedMenuItem: l,
      highlightedId: k,
      expanded: o,
      onBlur: S,
      currentLabel: c,
      currentWidthInPx: $,
      rootClasses: M,
      onClick: w,
      onKeydown: q,
      startIcon: y,
      cdxIconExpand: Je
    };
  }
}), Ue = () => {
  Ie((e) => ({
    eb4ebc46: e.currentWidthInPx
  }));
}, je = Se.setup;
Se.setup = je ? (e, t) => (Ue(), je(e, t)) : Ue;
const _l = ["aria-disabled"], $l = ["aria-owns", "aria-labelledby", "aria-activedescendant", "aria-expanded"], Al = ["id"];
function Il(e, t, n, a, s, u) {
  const o = A("cdx-icon"), i = A("cdx-menu");
  return p(), b("div", {
    class: V(["cdx-select", e.rootClasses]),
    "aria-disabled": e.disabled
  }, [
    g("div", {
      ref: "handle",
      class: "cdx-select__handle",
      tabindex: "0",
      role: "combobox",
      "aria-autocomplete": "list",
      "aria-owns": e.menuId,
      "aria-labelledby": e.handleId,
      "aria-activedescendant": e.highlightedId,
      "aria-haspopup": "listbox",
      "aria-expanded": e.expanded,
      onClick: t[0] || (t[0] = (...l) => e.onClick && e.onClick(...l)),
      onBlur: t[1] || (t[1] = (...l) => e.onBlur && e.onBlur(...l)),
      onKeydown: t[2] || (t[2] = (...l) => e.onKeydown && e.onKeydown(...l))
    }, [
      g("span", {
        id: e.handleId,
        role: "textbox",
        "aria-readonly": "true"
      }, [
        x(e.$slots, "label", {
          selectedMenuItem: e.selectedMenuItem,
          defaultLabel: e.defaultLabel
        }, () => [
          oe(U(e.currentLabel), 1)
        ])
      ], 8, Al),
      e.startIcon ? (p(), F(o, {
        key: 0,
        icon: e.startIcon,
        class: "cdx-select__start-icon"
      }, null, 8, ["icon"])) : D("", !0),
      R(o, {
        icon: e.cdxIconExpand,
        class: "cdx-select__indicator"
      }, null, 8, ["icon"])
    ], 40, $l),
    R(i, Q({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[3] || (t[3] = (l) => e.modelWrapper = l),
      expanded: e.expanded,
      "onUpdate:expanded": t[4] || (t[4] = (l) => e.expanded = l),
      "menu-items": e.menuItems
    }, e.menuConfig, {
      onLoadMore: t[5] || (t[5] = (l) => e.$emit("load-more"))
    }), {
      default: K(({ menuItem: l }) => [
        x(e.$slots, "menu-item", { menuItem: l })
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 10, _l);
}
const ha = /* @__PURE__ */ T(Se, [["render", Il]]), Bl = E({
  name: "CdxTab",
  props: {
    name: {
      type: String,
      required: !0
    },
    label: {
      type: String,
      default: ""
    },
    disabled: {
      type: Boolean,
      default: !1
    }
  },
  setup(e) {
    const t = Ke(Qe), n = Ke(Ge);
    if (!t || !n)
      throw new Error("Tab component must be used inside a Tabs component");
    const a = t.value.get(e.name) || {}, s = f(() => e.name === n.value);
    return {
      tab: a,
      isActive: s
    };
  }
});
const xl = ["id", "aria-hidden", "aria-labelledby"];
function wl(e, t, n, a, s, u) {
  return Z((p(), b("section", {
    id: e.tab.id,
    "aria-hidden": !e.isActive,
    "aria-labelledby": `${e.tab.id}-label`,
    class: "cdx-tab",
    role: "tabpanel",
    tabindex: "-1"
  }, [
    x(e.$slots, "default")
  ], 8, xl)), [
    [me, e.isActive]
  ]);
}
const va = /* @__PURE__ */ T(Bl, [["render", wl]]), kl = E({
  name: "CdxTabs",
  components: {
    CdxButton: de,
    CdxIcon: H
  },
  props: {
    active: {
      type: String,
      required: !0
    },
    framed: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:active"
  ],
  expose: [
    "select",
    "next",
    "prev"
  ],
  setup(e, { slots: t, emit: n }) {
    const a = v(), s = v(), u = v(), o = v(), i = v(), l = Xe(a), c = f(() => {
      var C;
      const d = [], r = (C = t.default) == null ? void 0 : C.call(t);
      r && r.forEach(h);
      function h(B) {
        B && typeof B == "object" && "type" in B && (typeof B.type == "object" && "name" in B.type && B.type.name === "CdxTab" ? d.push(B) : "children" in B && Array.isArray(B.children) && B.children.forEach(h));
      }
      return d;
    });
    if (!c.value || c.value.length === 0)
      throw new Error("Slot content cannot be empty");
    const m = f(() => c.value.reduce((d, r) => {
      var h;
      if (((h = r.props) == null ? void 0 : h.name) && typeof r.props.name == "string") {
        if (d.get(r.props.name))
          throw new Error("Tab names must be unique");
        d.set(r.props.name, {
          name: r.props.name,
          id: ee(r.props.name),
          label: r.props.label || r.props.name,
          disabled: r.props.disabled
        });
      }
      return d;
    }, /* @__PURE__ */ new Map())), $ = X(j(e, "active"), n, "update:active"), y = f(() => Array.from(m.value.keys())), M = f(() => y.value.indexOf($.value)), k = f(() => {
      var d;
      return (d = m.value.get($.value)) == null ? void 0 : d.id;
    });
    Re(Ge, $), Re(Qe, m);
    const S = v(), w = v(), q = $e(S, { threshold: 0.95 }), L = $e(w, { threshold: 0.95 });
    function I(d, r) {
      const h = d;
      h && (r === 0 ? S.value = h : r === y.value.length - 1 && (w.value = h));
    }
    function O(d) {
      var C;
      const r = d === $.value, h = !!((C = m.value.get(d)) != null && C.disabled);
      return {
        "cdx-tabs__list__item--selected": r,
        "cdx-tabs__list__item--enabled": !h,
        "cdx-tabs__list__item--disabled": h
      };
    }
    const G = f(() => ({
      "cdx-tabs--framed": e.framed,
      "cdx-tabs--quiet": !e.framed
    }));
    function W(d) {
      if (!s.value || !o.value || !i.value)
        return 0;
      const r = l.value === "rtl" ? i.value : o.value, h = l.value === "rtl" ? o.value : i.value, C = d.offsetLeft, B = C + d.clientWidth, N = s.value.scrollLeft + r.clientWidth, re = s.value.scrollLeft + s.value.clientWidth - h.clientWidth;
      return C < N ? C - N : B > re ? B - re : 0;
    }
    function Y(d) {
      var B;
      if (!s.value || !o.value || !i.value)
        return;
      const r = d === "next" && l.value === "ltr" || d === "prev" && l.value === "rtl" ? 1 : -1;
      let h = 0, C = d === "next" ? s.value.firstElementChild : s.value.lastElementChild;
      for (; C; ) {
        const N = d === "next" ? C.nextElementSibling : C.previousElementSibling;
        if (h = W(C), Math.sign(h) === r) {
          N && Math.abs(h) < 0.25 * s.value.clientWidth && (h = W(N));
          break;
        }
        C = N;
      }
      s.value.scrollBy({
        left: h,
        behavior: "smooth"
      }), (B = u.value) == null || B.focus();
    }
    return J($, () => {
      if (k.value === void 0 || !s.value || !o.value || !i.value)
        return;
      const d = document.getElementById(`${k.value}-label`);
      !d || s.value.scrollBy({
        left: W(d),
        behavior: "smooth"
      });
    }), {
      activeTab: $,
      activeTabIndex: M,
      activeTabId: k,
      currentDirection: l,
      rootElement: a,
      listElement: s,
      focusHolder: u,
      prevScroller: o,
      nextScroller: i,
      rootClasses: G,
      tabNames: y,
      tabsData: m,
      firstLabelVisible: q,
      lastLabelVisible: L,
      getLabelClasses: O,
      assignTemplateRefIfNecessary: I,
      scrollTabs: Y,
      cdxIconPrevious: Pt,
      cdxIconNext: Wt
    };
  },
  methods: {
    select(e) {
      const t = this.tabsData.get(e);
      t && !(t != null && t.disabled) && (this.activeTab = e);
    },
    selectNonDisabled(e, t) {
      const n = this.tabsData.get(this.tabNames[e + t]);
      n && (n.disabled ? this.selectNonDisabled(e + t, t) : this.select(n.name));
    },
    next() {
      this.selectNonDisabled(this.activeTabIndex, 1);
    },
    prev() {
      this.selectNonDisabled(this.activeTabIndex, -1);
    },
    onLeftArrowKeypress() {
      this.currentDirection === "rtl" ? this.next() : this.prev();
    },
    onRightArrowKeypress() {
      this.currentDirection === "rtl" ? this.prev() : this.next();
    },
    onDownArrowKeypress() {
      var e;
      this.activeTabId && ((e = document.getElementById(this.activeTabId)) == null || e.focus());
    }
  }
});
const Sl = {
  ref: "focusHolder",
  tabindex: "-1"
}, Ml = {
  ref: "prevScroller",
  class: "cdx-tabs__prev-scroller"
}, Dl = ["aria-activedescendant"], El = ["id"], Tl = ["href", "aria-selected", "onClick", "onKeyup"], Ll = {
  ref: "nextScroller",
  class: "cdx-tabs__next-scroller"
}, Fl = { class: "cdx-tabs__content" };
function Vl(e, t, n, a, s, u) {
  const o = A("cdx-icon"), i = A("cdx-button");
  return p(), b("div", {
    ref: "rootElement",
    class: V(["cdx-tabs", e.rootClasses])
  }, [
    g("div", {
      class: "cdx-tabs__header",
      tabindex: "0",
      onKeydown: [
        t[4] || (t[4] = ae(P((...l) => e.onRightArrowKeypress && e.onRightArrowKeypress(...l), ["prevent"]), ["right"])),
        t[5] || (t[5] = ae(P((...l) => e.onDownArrowKeypress && e.onDownArrowKeypress(...l), ["prevent"]), ["down"])),
        t[6] || (t[6] = ae(P((...l) => e.onLeftArrowKeypress && e.onLeftArrowKeypress(...l), ["prevent"]), ["left"]))
      ]
    }, [
      g("div", Sl, null, 512),
      Z(g("div", Ml, [
        R(i, {
          class: "cdx-tabs__scroll-button",
          type: "quiet",
          tabindex: "-1",
          "aria-hidden": !0,
          onMousedown: t[0] || (t[0] = P(() => {
          }, ["prevent"])),
          onClick: t[1] || (t[1] = (l) => e.scrollTabs("prev"))
        }, {
          default: K(() => [
            R(o, { icon: e.cdxIconPrevious }, null, 8, ["icon"])
          ]),
          _: 1
        })
      ], 512), [
        [me, !e.firstLabelVisible]
      ]),
      g("ul", {
        ref: "listElement",
        class: "cdx-tabs__list",
        role: "tablist",
        "aria-activedescendant": e.activeTabId
      }, [
        (p(!0), b(ie, null, he(e.tabsData.values(), (l, c) => (p(), b("li", {
          id: `${l.id}-label`,
          key: c,
          ref_for: !0,
          ref: (m) => e.assignTemplateRefIfNecessary(m, c),
          class: V([e.getLabelClasses(l.name), "cdx-tabs__list__item"]),
          role: "presentation"
        }, [
          g("a", {
            href: `#${l.id}`,
            role: "tab",
            tabIndex: "-1",
            "aria-selected": l.name === e.activeTab,
            onClick: P((m) => e.select(l.name), ["prevent"]),
            onKeyup: ae((m) => e.select(l.name), ["enter"])
          }, U(l.label), 41, Tl)
        ], 10, El))), 128))
      ], 8, Dl),
      Z(g("div", Ll, [
        R(i, {
          class: "cdx-tabs__scroll-button",
          type: "quiet",
          tabindex: "-1",
          "aria-hidden": !0,
          onMousedown: t[2] || (t[2] = P(() => {
          }, ["prevent"])),
          onClick: t[3] || (t[3] = (l) => e.scrollTabs("next"))
        }, {
          default: K(() => [
            R(o, { icon: e.cdxIconNext }, null, 8, ["icon"])
          ]),
          _: 1
        })
      ], 512), [
        [me, !e.lastLabelVisible]
      ])
    ], 32),
    g("div", Fl, [
      x(e.$slots, "default")
    ])
  ], 2);
}
const ba = /* @__PURE__ */ T(kl, [["render", Vl]]), Kl = E({
  name: "CdxToggleButton",
  props: {
    modelValue: {
      type: Boolean,
      default: !1
    },
    disabled: {
      type: Boolean,
      default: !1
    },
    quiet: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(e, { emit: t }) {
    return {
      rootClasses: f(() => ({
        "cdx-toggle-button--quiet": e.quiet,
        "cdx-toggle-button--framed": !e.quiet,
        "cdx-toggle-button--toggled-on": e.modelValue,
        "cdx-toggle-button--toggled-off": !e.modelValue
      })),
      onClick: () => {
        t("update:modelValue", !e.modelValue);
      }
    };
  }
});
const Rl = ["aria-pressed", "disabled"];
function Nl(e, t, n, a, s, u) {
  return p(), b("button", {
    class: V(["cdx-toggle-button", e.rootClasses]),
    "aria-pressed": e.modelValue,
    disabled: e.disabled,
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    x(e.$slots, "default")
  ], 10, Rl);
}
const ql = /* @__PURE__ */ T(Kl, [["render", Nl]]), Ol = E({
  name: "CdxToggleButtonGroup",
  components: {
    CdxIcon: H,
    CdxToggleButton: ql
  },
  props: {
    buttons: {
      type: Array,
      required: !0,
      validator: (e) => Array.isArray(e) && e.length >= 1
    },
    modelValue: {
      type: [String, Number, null, Array],
      required: !0
    },
    disabled: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(e, { emit: t }) {
    function n(s) {
      return Array.isArray(e.modelValue) ? e.modelValue.indexOf(s.value) !== -1 : e.modelValue !== null ? e.modelValue === s.value : !1;
    }
    function a(s, u) {
      if (Array.isArray(e.modelValue)) {
        const o = e.modelValue.indexOf(s.value) !== -1;
        u && !o ? t("update:modelValue", e.modelValue.concat(s.value)) : !u && o && t("update:modelValue", e.modelValue.filter((i) => i !== s.value));
      } else
        u && e.modelValue !== s.value && t("update:modelValue", s.value);
    }
    return {
      getButtonLabel: Ye,
      isSelected: n,
      onUpdate: a
    };
  }
});
const zl = { class: "cdx-toggle-button-group" };
function Ul(e, t, n, a, s, u) {
  const o = A("cdx-icon"), i = A("cdx-toggle-button");
  return p(), b("div", zl, [
    (p(!0), b(ie, null, he(e.buttons, (l) => (p(), F(i, {
      key: l.value,
      "model-value": e.isSelected(l),
      disabled: l.disabled || e.disabled,
      "aria-label": l.ariaLabel,
      "onUpdate:modelValue": (c) => e.onUpdate(l, c)
    }, {
      default: K(() => [
        x(e.$slots, "default", {
          button: l,
          selected: e.isSelected(l)
        }, () => [
          l.icon ? (p(), F(o, {
            key: 0,
            icon: l.icon
          }, null, 8, ["icon"])) : D("", !0),
          oe(" " + U(e.getButtonLabel(l)), 1)
        ])
      ]),
      _: 2
    }, 1032, ["model-value", "disabled", "aria-label", "onUpdate:modelValue"]))), 128))
  ]);
}
const ga = /* @__PURE__ */ T(Ol, [["render", Ul]]), jl = E({
  name: "CdxToggleSwitch",
  inheritAttrs: !1,
  props: {
    modelValue: {
      type: Boolean,
      default: !1
    },
    disabled: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(e, { attrs: t, emit: n }) {
    const a = v(), s = ee("toggle-switch"), {
      rootClasses: u,
      rootStyle: o,
      otherAttrs: i
    } = ue(t), l = X(j(e, "modelValue"), n);
    return {
      input: a,
      inputId: s,
      rootClasses: u,
      rootStyle: o,
      otherAttrs: i,
      wrappedModel: l,
      clickInput: () => {
        a.value.click();
      }
    };
  }
});
const Hl = ["for"], Wl = ["id", "disabled"], Pl = {
  key: 0,
  class: "cdx-toggle-switch__label-content"
}, Ql = /* @__PURE__ */ g("span", { class: "cdx-toggle-switch__switch" }, [
  /* @__PURE__ */ g("span", { class: "cdx-toggle-switch__switch__grip" })
], -1);
function Gl(e, t, n, a, s, u) {
  return p(), b("span", {
    class: V(["cdx-toggle-switch", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    g("label", {
      for: e.inputId,
      class: "cdx-toggle-switch__label"
    }, [
      Z(g("input", Q({
        id: e.inputId,
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-toggle-switch__input",
        type: "checkbox",
        disabled: e.disabled
      }, e.otherAttrs, {
        onKeydown: t[1] || (t[1] = ae(P((...o) => e.clickInput && e.clickInput(...o), ["prevent"]), ["enter"]))
      }), null, 16, Wl), [
        [Pe, e.wrappedModel]
      ]),
      e.$slots.default ? (p(), b("span", Pl, [
        x(e.$slots, "default")
      ])) : D("", !0),
      Ql
    ], 8, Hl)
  ], 6);
}
const ya = /* @__PURE__ */ T(jl, [["render", Gl]]), Jl = E({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: H,
    CdxMenu: be,
    CdxSearchInput: Cl
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
      default: At
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
  setup(e, { attrs: t, emit: n, slots: a }) {
    const { searchResults: s, searchFooterUrl: u, debounceInterval: o } = gt(e), i = v(), l = v(), c = ee("typeahead-search-menu"), m = v(!1), $ = v(!1), y = v(!1), M = v(!1), k = v(e.initialInputValue), S = v(""), w = f(() => {
      var _, z;
      return (z = (_ = l.value) == null ? void 0 : _.getHighlightedMenuItem()) == null ? void 0 : z.id;
    }), q = v(null), L = f(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), I = f(
      () => e.searchResults.find(
        (_) => _.value === q.value
      )
    ), O = f(
      () => u.value ? s.value.concat([
        { value: se, url: u.value }
      ]) : s.value
    ), G = f(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": m.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: W,
      rootStyle: Y,
      otherAttrs: d
    } = ue(t, G);
    function r(_) {
      return _;
    }
    const h = f(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let C, B;
    function N(_, z = !1) {
      I.value && I.value.label !== _ && I.value.value !== _ && (q.value = null), B !== void 0 && (clearTimeout(B), B = void 0), _ === "" ? m.value = !1 : ($.value = !0, a["search-results-pending"] && (B = setTimeout(() => {
        M.value && (m.value = !0), y.value = !0;
      }, It))), C !== void 0 && (clearTimeout(C), C = void 0);
      const le = () => {
        n("input", _);
      };
      z ? le() : C = setTimeout(() => {
        le();
      }, o.value);
    }
    function re(_) {
      if (_ === se) {
        q.value = null, k.value = S.value;
        return;
      }
      q.value = _, _ !== null && (k.value = I.value ? I.value.label || String(I.value.value) : "");
    }
    function nt() {
      M.value = !0, (S.value || y.value) && (m.value = !0);
    }
    function lt() {
      M.value = !1, m.value = !1;
    }
    function Me(_) {
      const De = _, { id: z } = De, le = pe(De, ["id"]), it = {
        searchResult: le.value !== se ? le : null,
        index: O.value.findIndex(
          (dt) => dt.value === _.value
        ),
        numberOfResults: s.value.length
      };
      n("search-result-click", it);
    }
    function at(_) {
      if (_.value === se) {
        k.value = S.value;
        return;
      }
      k.value = _.value ? _.label || String(_.value) : "";
    }
    function ot(_) {
      var z;
      m.value = !1, (z = l.value) == null || z.clearActive(), Me(_);
    }
    function st() {
      let _ = null, z = -1;
      I.value && (_ = I.value, z = e.searchResults.indexOf(I.value));
      const le = {
        searchResult: _,
        index: z,
        numberOfResults: s.value.length
      };
      n("submit", le);
    }
    function ut(_) {
      if (!l.value || !S.value || _.key === " " && m.value)
        return;
      const z = l.value.getHighlightedMenuItem();
      switch (_.key) {
        case "Enter":
          z && (z.value === se ? window.location.assign(u.value) : l.value.delegateKeyNavigation(_, !1)), m.value = !1;
          break;
        case "Tab":
          m.value = !1;
          break;
        default:
          l.value.delegateKeyNavigation(_);
          break;
      }
    }
    return te(() => {
      e.initialInputValue && N(e.initialInputValue, !0);
    }), J(j(e, "searchResults"), () => {
      S.value = k.value.trim(), M.value && $.value && S.value.length > 0 && (m.value = !0), B !== void 0 && (clearTimeout(B), B = void 0), $.value = !1, y.value = !1;
    }), {
      form: i,
      menu: l,
      menuId: c,
      highlightedId: w,
      selection: q,
      menuMessageClass: L,
      searchResultsWithFooter: O,
      asSearchResult: r,
      inputValue: k,
      searchQuery: S,
      expanded: m,
      showPending: y,
      rootClasses: W,
      rootStyle: Y,
      otherAttrs: d,
      menuConfig: h,
      onUpdateInputValue: N,
      onUpdateMenuSelection: re,
      onFocus: nt,
      onBlur: lt,
      onSearchResultClick: Me,
      onSearchResultKeyboardNavigation: at,
      onSearchFooterClick: ot,
      onSubmit: st,
      onKeydown: ut,
      MenuFooterValue: se,
      articleIcon: Nt
    };
  },
  methods: {
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const Xl = ["id", "action"], Yl = { class: "cdx-typeahead-search__menu-message__text" }, Zl = { class: "cdx-typeahead-search__menu-message__text" }, ea = ["href", "onClickCapture"], ta = { class: "cdx-typeahead-search__search-footer__text" }, na = { class: "cdx-typeahead-search__search-footer__query" };
function la(e, t, n, a, s, u) {
  const o = A("cdx-icon"), i = A("cdx-menu"), l = A("cdx-search-input");
  return p(), b("div", {
    class: V(["cdx-typeahead-search", e.rootClasses]),
    style: ne(e.rootStyle)
  }, [
    g("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...c) => e.onSubmit && e.onSubmit(...c))
    }, [
      R(l, Q({
        ref: "searchInput",
        modelValue: e.inputValue,
        "onUpdate:modelValue": t[3] || (t[3] = (c) => e.inputValue = c),
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
          R(i, Q({
            id: e.menuId,
            ref: "menu",
            expanded: e.expanded,
            "onUpdate:expanded": t[0] || (t[0] = (c) => e.expanded = c),
            "show-pending": e.showPending,
            selected: e.selection,
            "menu-items": e.searchResultsWithFooter,
            "search-query": e.highlightQuery ? e.searchQuery : "",
            "show-no-results-slot": e.searchQuery.length > 0 && e.searchResults.length === 0 && e.$slots["search-no-results-text"] && e.$slots["search-no-results-text"]().length > 0
          }, e.menuConfig, {
            "aria-label": e.searchResultsLabel,
            "onUpdate:selected": e.onUpdateMenuSelection,
            onMenuItemClick: t[1] || (t[1] = (c) => e.onSearchResultClick(e.asSearchResult(c))),
            onMenuItemKeyboardNavigation: e.onSearchResultKeyboardNavigation,
            onLoadMore: t[2] || (t[2] = (c) => e.$emit("load-more"))
          }), {
            pending: K(() => [
              g("div", {
                class: V(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                g("span", Yl, [
                  x(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": K(() => [
              g("div", {
                class: V(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                g("span", Zl, [
                  x(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: K(({ menuItem: c, active: m }) => [
              c.value === e.MenuFooterValue ? (p(), b("a", {
                key: 0,
                class: V(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": m
                }]),
                href: e.asSearchResult(c).url,
                onClickCapture: P(($) => e.onSearchFooterClick(e.asSearchResult(c)), ["stop"])
              }, [
                R(o, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                g("span", ta, [
                  x(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    g("strong", na, U(e.searchQuery), 1)
                  ])
                ])
              ], 42, ea)) : D("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      x(e.$slots, "default")
    ], 40, Xl)
  ], 6);
}
const Ca = /* @__PURE__ */ T(Jl, [["render", la]]);
export {
  de as CdxButton,
  ua as CdxButtonGroup,
  ia as CdxCard,
  da as CdxCheckbox,
  ca as CdxCombobox,
  H as CdxIcon,
  pa as CdxLookup,
  be as CdxMenu,
  Un as CdxMenuItem,
  fa as CdxMessage,
  Qn as CdxProgressBar,
  ma as CdxRadio,
  Cl as CdxSearchInput,
  Ln as CdxSearchResultTitle,
  ha as CdxSelect,
  va as CdxTab,
  ba as CdxTabs,
  Be as CdxTextInput,
  Ze as CdxThumbnail,
  ql as CdxToggleButton,
  ga as CdxToggleButtonGroup,
  ya as CdxToggleSwitch,
  Ca as CdxTypeaheadSearch,
  ra as stringHelpers,
  Xe as useComputedDirection,
  Xt as useComputedLanguage,
  ee as useGeneratedId,
  $e as useIntersectionObserver,
  X as useModelWrapper,
  xe as useResizeObserver,
  ue as useSplitAttributes
};
