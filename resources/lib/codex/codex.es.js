var ct = Object.defineProperty, pt = Object.defineProperties;
var ft = Object.getOwnPropertyDescriptors;
var ye = Object.getOwnPropertySymbols;
var Re = Object.prototype.hasOwnProperty, qe = Object.prototype.propertyIsEnumerable;
var Ne = (e, t, l) => t in e ? ct(e, t, { enumerable: !0, configurable: !0, writable: !0, value: l }) : e[t] = l, Oe = (e, t) => {
  for (var l in t || (t = {}))
    Re.call(t, l) && Ne(e, l, t[l]);
  if (ye)
    for (var l of ye(t))
      qe.call(t, l) && Ne(e, l, t[l]);
  return e;
}, ze = (e, t) => pt(e, ft(t));
var Ce = (e, t) => {
  var l = {};
  for (var s in e)
    Re.call(e, s) && t.indexOf(s) < 0 && (l[s] = e[s]);
  if (e != null && ye)
    for (var s of ye(e))
      t.indexOf(s) < 0 && qe.call(e, s) && (l[s] = e[s]);
  return l;
};
var Ie = (e, t, l) => new Promise((s, a) => {
  var i = (n) => {
    try {
      u(l.next(n));
    } catch (d) {
      a(d);
    }
  }, o = (n) => {
    try {
      u(l.throw(n));
    } catch (d) {
      a(d);
    }
  }, u = (n) => n.done ? s(n.value) : Promise.resolve(n.value).then(i, o);
  u((l = l.apply(e, t)).next());
});
import { ref as f, onMounted as ie, defineComponent as F, computed as p, openBlock as r, createElementBlock as b, normalizeClass as N, toDisplayString as j, createCommentVNode as k, Comment as mt, warn as vt, withKeys as Y, renderSlot as w, resolveComponent as A, Fragment as ve, renderList as _e, createBlock as E, withCtx as T, createTextVNode as le, createVNode as O, Transition as Se, normalizeStyle as de, resolveDynamicComponent as Xe, createElementVNode as m, toRef as G, withModifiers as X, withDirectives as oe, vModelCheckbox as Ye, getCurrentInstance as ht, onUnmounted as Me, watch as te, nextTick as be, mergeProps as Z, vShow as ge, vModelDynamic as bt, useCssVars as De, vModelRadio as gt, inject as je, provide as He, toRefs as yt } from "vue";
const Be = "cdx", Ct = [
  "default",
  "progressive",
  "destructive"
], _t = [
  "normal",
  "primary",
  "quiet"
], $t = [
  "notice",
  "warning",
  "error",
  "success"
], At = [
  "text",
  "search"
], $e = [
  "default",
  "error"
], It = 120, Bt = 500, me = "cdx-menu-footer-item", Ze = Symbol("CdxTabs"), et = Symbol("CdxActiveTab"), xt = '<path d="M11.53 2.3A1.85 1.85 0 0010 1.21 1.85 1.85 0 008.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z"/>', kt = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', wt = '<path d="M7 14.17 2.83 10l-1.41 1.41L7 17 19 5l-1.41-1.42z"/>', St = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', Mt = '<path d="m4.34 2.93 12.73 12.73-1.41 1.41L2.93 4.35z"/><path d="M17.07 4.34 4.34 17.07l-1.41-1.41L15.66 2.93z"/>', Dt = '<path d="M13.728 1H6.272L1 6.272v7.456L6.272 19h7.456L19 13.728V6.272zM11 15H9v-2h2zm0-4H9V5h2z"/>', Et = '<path d="m17.5 4.75-7.5 7.5-7.5-7.5L1 6.25l9 9 9-9z"/>', Tt = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Lt = '<path d="M8 19a1 1 0 001 1h2a1 1 0 001-1v-1H8zm9-12a7 7 0 10-12 4.9S7 14 7 15v1a1 1 0 001 1h4a1 1 0 001-1v-1c0-1 2-3.1 2-3.1A7 7 0 0017 7z"/>', Ft = '<path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zM9 5h2v2H9zm0 4h2v6H9z"/>', Vt = '<path d="M7 1 5.6 2.5 13 10l-7.4 7.5L7 19l9-9z"/>', Kt = '<path d="m4 10 9 9 1.4-1.5L7 10l7.4-7.5L13 1z"/>', Nt = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', Rt = xt, qt = kt, Ot = wt, zt = St, tt = Mt, jt = Dt, nt = Et, Ht = Tt, Ut = {
  langCodeMap: {
    ar: Lt
  },
  default: Ft
}, Wt = {
  ltr: Vt,
  shouldFlip: !0
}, Pt = {
  ltr: Kt,
  shouldFlip: !0
}, Qt = Nt;
function Gt(e, t, l) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return l === "rtl" ? e.rtl : e.ltr;
  const s = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof s == "string" || "path" in s ? s : s.ltr;
}
function Jt(e, t) {
  if (typeof e == "string")
    return !1;
  if ("langCodeMap" in e) {
    const l = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
    if (typeof l == "string")
      return !1;
    e = l;
  }
  if ("shouldFlipExceptions" in e && Array.isArray(e.shouldFlipExceptions)) {
    const l = e.shouldFlipExceptions.indexOf(t);
    return l === void 0 || l === -1;
  }
  return "shouldFlip" in e ? e.shouldFlip : !1;
}
function lt(e) {
  const t = f(null);
  return ie(() => {
    const l = window.getComputedStyle(e.value).direction;
    t.value = l === "ltr" || l === "rtl" ? l : null;
  }), t;
}
function Xt(e) {
  const t = f("");
  return ie(() => {
    let l = e.value;
    for (; l && l.lang === ""; )
      l = l.parentElement;
    t.value = l ? l.lang : null;
  }), t;
}
const Yt = F({
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
    const l = f(), s = lt(l), a = Xt(l), i = p(() => e.dir || s.value), o = p(() => e.lang || a.value), u = p(() => ({
      "cdx-icon--flipped": i.value === "rtl" && o.value !== null && Jt(e.icon, o.value)
    })), n = p(
      () => Gt(e.icon, o.value || "", i.value || "ltr")
    ), d = p(() => typeof n.value == "string" ? n.value : ""), c = p(() => typeof n.value != "string" ? n.value.path : "");
    return {
      rootElement: l,
      rootClasses: u,
      iconSvg: d,
      iconPath: c,
      onClick: (y) => {
        t("click", y);
      }
    };
  }
});
const V = (e, t) => {
  const l = e.__vccOpts || e;
  for (const [s, a] of t)
    l[s] = a;
  return l;
}, Zt = ["aria-hidden"], en = { key: 0 }, tn = ["innerHTML"], nn = ["d"];
function ln(e, t, l, s, a, i) {
  return r(), b("span", {
    ref: "rootElement",
    class: N(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o))
  }, [
    (r(), b("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": !e.iconLabel
    }, [
      e.iconLabel ? (r(), b("title", en, j(e.iconLabel), 1)) : k("", !0),
      e.iconSvg ? (r(), b("g", {
        key: 1,
        innerHTML: e.iconSvg
      }, null, 8, tn)) : (r(), b("path", {
        key: 2,
        d: e.iconPath
      }, null, 8, nn))
    ], 8, Zt))
  ], 2);
}
const J = /* @__PURE__ */ V(Yt, [["render", ln]]);
function re(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const on = re(_t), an = re(Ct), sn = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && vt(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/button.html#default-icon-only`);
};
function ke(e) {
  const t = [];
  for (const l of e)
    typeof l == "string" && l.trim() !== "" ? t.push(l) : Array.isArray(l) ? t.push(...ke(l)) : typeof l == "object" && l && (typeof l.type == "string" || typeof l.type == "object" ? t.push(l) : l.type !== mt && (typeof l.children == "string" && l.children.trim() !== "" ? t.push(l.children) : Array.isArray(l.children) && t.push(...ke(l.children))));
  return t;
}
const un = (e, t) => {
  if (!e)
    return !1;
  const l = ke(e);
  if (l.length !== 1)
    return !1;
  const s = l[0], a = typeof s == "object" && typeof s.type == "object" && "name" in s.type && s.type.name === J.name, i = typeof s == "object" && s.type === "svg";
  return a || i ? (sn(t), !0) : !1;
}, dn = F({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: an
    },
    type: {
      type: String,
      default: "normal",
      validator: on
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: l, attrs: s }) {
    const a = f(!1);
    return {
      rootClasses: p(() => {
        var n;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--type-${e.type}`]: !0,
          "cdx-button--framed": e.type !== "quiet",
          "cdx-button--icon-only": un((n = l.default) == null ? void 0 : n.call(l), s),
          "cdx-button--is-active": a.value
        };
      }),
      onClick: (n) => {
        t("click", n);
      },
      setActive: (n) => {
        a.value = n;
      }
    };
  }
});
function rn(e, t, l, s, a, i) {
  return r(), b("button", {
    class: N(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o)),
    onKeydown: t[1] || (t[1] = Y((o) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = Y((o) => e.setActive(!1), ["space", "enter"]))
  }, [
    w(e.$slots, "default")
  ], 34);
}
const he = /* @__PURE__ */ V(dn, [["render", rn]]);
function ot(e) {
  return e.label === void 0 ? e.value : e.label === null ? "" : e.label;
}
const cn = F({
  name: "CdxButtonGroup",
  components: {
    CdxButton: he,
    CdxIcon: J
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
      getButtonLabel: ot
    };
  }
});
const pn = { class: "cdx-button-group" };
function fn(e, t, l, s, a, i) {
  const o = A("cdx-icon"), u = A("cdx-button");
  return r(), b("div", pn, [
    (r(!0), b(ve, null, _e(e.buttons, (n) => (r(), E(u, {
      key: n.value,
      disabled: n.disabled || e.disabled,
      "aria-label": n.ariaLabel,
      onClick: (d) => e.$emit("click", n.value)
    }, {
      default: T(() => [
        w(e.$slots, "default", { button: n }, () => [
          n.icon ? (r(), E(o, {
            key: 0,
            icon: n.icon
          }, null, 8, ["icon"])) : k("", !0),
          le(" " + j(e.getButtonLabel(n)), 1)
        ])
      ]),
      _: 2
    }, 1032, ["disabled", "aria-label", "onClick"]))), 128))
  ]);
}
const Co = /* @__PURE__ */ V(cn, [["render", fn]]), mn = F({
  name: "CdxThumbnail",
  components: { CdxIcon: J },
  props: {
    thumbnail: {
      type: [Object, null],
      default: null
    },
    placeholderIcon: {
      type: [String, Object],
      default: Ht
    }
  },
  setup: (e) => {
    const t = f(!1), l = f({}), s = (a) => {
      const i = a.replace(/([\\"\n])/g, "\\$1"), o = new Image();
      o.onload = () => {
        l.value = { backgroundImage: `url("${i}")` }, t.value = !0;
      }, o.onerror = () => {
        t.value = !1;
      }, o.src = i;
    };
    return ie(() => {
      var a;
      (a = e.thumbnail) != null && a.url && s(e.thumbnail.url);
    }), {
      thumbnailStyle: l,
      thumbnailLoaded: t
    };
  }
});
const vn = { class: "cdx-thumbnail" }, hn = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function bn(e, t, l, s, a, i) {
  const o = A("cdx-icon");
  return r(), b("span", vn, [
    e.thumbnailLoaded ? k("", !0) : (r(), b("span", hn, [
      O(o, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])),
    O(Se, { name: "cdx-thumbnail__image" }, {
      default: T(() => [
        e.thumbnailLoaded ? (r(), b("span", {
          key: 0,
          style: de(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : k("", !0)
      ]),
      _: 1
    })
  ]);
}
const at = /* @__PURE__ */ V(mn, [["render", bn]]), gn = F({
  name: "CdxCard",
  components: { CdxIcon: J, CdxThumbnail: at },
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
    const t = p(() => !!e.url), l = p(() => t.value ? "a" : "span"), s = p(() => t.value ? e.url : void 0);
    return {
      isLink: t,
      contentTag: l,
      cardLink: s
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
function An(e, t, l, s, a, i) {
  const o = A("cdx-thumbnail"), u = A("cdx-icon");
  return r(), E(Xe(e.contentTag), {
    href: e.cardLink,
    class: N(["cdx-card", {
      "cdx-card--is-link": e.isLink,
      "cdx-card--title-only": !e.$slots.description && !e.$slots["supporting-text"]
    }])
  }, {
    default: T(() => [
      e.thumbnail || e.forceThumbnail ? (r(), E(o, {
        key: 0,
        thumbnail: e.thumbnail,
        "placeholder-icon": e.customPlaceholderIcon,
        class: "cdx-card__thumbnail"
      }, null, 8, ["thumbnail", "placeholder-icon"])) : e.icon ? (r(), E(u, {
        key: 1,
        icon: e.icon,
        class: "cdx-card__icon"
      }, null, 8, ["icon"])) : k("", !0),
      m("span", yn, [
        m("span", Cn, [
          w(e.$slots, "title")
        ]),
        e.$slots.description ? (r(), b("span", _n, [
          w(e.$slots, "description")
        ])) : k("", !0),
        e.$slots["supporting-text"] ? (r(), b("span", $n, [
          w(e.$slots, "supporting-text")
        ])) : k("", !0)
      ])
    ]),
    _: 3
  }, 8, ["href", "class"]);
}
const _o = /* @__PURE__ */ V(gn, [["render", An]]);
function se(e, t, l) {
  return p({
    get: () => e.value,
    set: (s) => t(l || "update:modelValue", s)
  });
}
const In = F({
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
    const l = p(() => ({
      "cdx-checkbox--inline": e.inline
    })), s = f(), a = f(), i = () => {
      s.value.focus();
    }, o = () => {
      a.value.click();
    }, u = se(G(e, "modelValue"), t);
    return {
      rootClasses: l,
      input: s,
      label: a,
      focusInput: i,
      clickLabel: o,
      wrappedModel: u
    };
  }
});
const Bn = ["value", "disabled", ".indeterminate"], xn = /* @__PURE__ */ m("span", { class: "cdx-checkbox__icon" }, null, -1), kn = { class: "cdx-checkbox__label-content" };
function wn(e, t, l, s, a, i) {
  return r(), b("span", {
    class: N(["cdx-checkbox", e.rootClasses])
  }, [
    m("label", {
      ref: "label",
      class: "cdx-checkbox__label",
      onClick: t[1] || (t[1] = (...o) => e.focusInput && e.focusInput(...o)),
      onKeydown: t[2] || (t[2] = Y(X((...o) => e.clickLabel && e.clickLabel(...o), ["prevent"]), ["enter"]))
    }, [
      oe(m("input", {
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-checkbox__input",
        type: "checkbox",
        value: e.inputValue,
        disabled: e.disabled,
        ".indeterminate": e.indeterminate
      }, null, 8, Bn), [
        [Ye, e.wrappedModel]
      ]),
      xn,
      m("span", kn, [
        w(e.$slots, "default")
      ])
    ], 544)
  ], 2);
}
const $o = /* @__PURE__ */ V(In, [["render", wn]]);
function st(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const Sn = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function ut(e, t) {
  if (!e)
    return [t, "", ""];
  const l = st(e), s = new RegExp(
    l + Sn + "*",
    "i"
  ).exec(t);
  if (!s || s.index === void 0)
    return [t, "", ""];
  const a = s.index, i = a + s[0].length, o = t.slice(a, i), u = t.slice(0, a), n = t.slice(i, t.length);
  return [u, o, n];
}
const Ao = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  regExpEscape: st,
  splitStringAtMatch: ut
}, Symbol.toStringTag, { value: "Module" })), Mn = F({
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
    titleChunks: p(() => ut(e.searchQuery, String(e.title)))
  })
});
const Dn = { class: "cdx-search-result-title" }, En = { class: "cdx-search-result-title__match" };
function Tn(e, t, l, s, a, i) {
  return r(), b("span", Dn, [
    m("bdi", null, [
      le(j(e.titleChunks[0]), 1),
      m("span", En, j(e.titleChunks[1]), 1),
      le(j(e.titleChunks[2]), 1)
    ])
  ]);
}
const Ln = /* @__PURE__ */ V(Mn, [["render", Tn]]), Fn = F({
  name: "CdxMenuItem",
  components: { CdxIcon: J, CdxThumbnail: at, CdxSearchResultTitle: Ln },
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
    const l = () => {
      t("change", "highlighted", !0);
    }, s = () => {
      t("change", "highlighted", !1);
    }, a = (c) => {
      c.button === 0 && t("change", "active", !0);
    }, i = () => {
      t("change", "selected", !0);
    }, o = p(() => e.searchQuery.length > 0), u = p(() => ({
      "cdx-menu-item--selected": e.selected,
      "cdx-menu-item--active": e.active && e.highlighted,
      "cdx-menu-item--highlighted": e.highlighted,
      "cdx-menu-item--enabled": !e.disabled,
      "cdx-menu-item--disabled": e.disabled,
      "cdx-menu-item--highlight-query": o.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), n = p(() => e.url ? "a" : "span"), d = p(() => e.label || String(e.value));
    return {
      onMouseEnter: l,
      onMouseLeave: s,
      onMouseDown: a,
      onClick: i,
      highlightQuery: o,
      rootClasses: u,
      contentTag: n,
      title: d
    };
  }
});
const Vn = ["id", "aria-disabled", "aria-selected"], Kn = { class: "cdx-menu-item__text" }, Nn = ["lang"], Rn = ["lang"], qn = ["lang"], On = ["lang"];
function zn(e, t, l, s, a, i) {
  const o = A("cdx-thumbnail"), u = A("cdx-icon"), n = A("cdx-search-result-title");
  return r(), b("li", {
    id: e.id,
    role: "option",
    class: N(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMouseenter: t[0] || (t[0] = (...d) => e.onMouseEnter && e.onMouseEnter(...d)),
    onMouseleave: t[1] || (t[1] = (...d) => e.onMouseLeave && e.onMouseLeave(...d)),
    onMousedown: t[2] || (t[2] = X((...d) => e.onMouseDown && e.onMouseDown(...d), ["prevent"])),
    onClick: t[3] || (t[3] = (...d) => e.onClick && e.onClick(...d))
  }, [
    w(e.$slots, "default", {}, () => [
      (r(), E(Xe(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: T(() => {
          var d, c, $, y, S, I;
          return [
            e.showThumbnail ? (r(), E(o, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (r(), E(u, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : k("", !0),
            m("span", Kn, [
              e.highlightQuery ? (r(), E(n, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (d = e.language) == null ? void 0 : d.label
              }, null, 8, ["title", "search-query", "lang"])) : (r(), b("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (c = e.language) == null ? void 0 : c.label
              }, [
                m("bdi", null, j(e.title), 1)
              ], 8, Nn)),
              e.match ? (r(), b(ve, { key: 2 }, [
                le(j(" ") + " "),
                e.highlightQuery ? (r(), E(n, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: ($ = e.language) == null ? void 0 : $.match
                }, null, 8, ["title", "search-query", "lang"])) : (r(), b("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (y = e.language) == null ? void 0 : y.match
                }, [
                  m("bdi", null, j(e.match), 1)
                ], 8, Rn))
              ], 64)) : k("", !0),
              e.supportingText ? (r(), b(ve, { key: 3 }, [
                le(j(" ") + " "),
                m("span", {
                  class: "cdx-menu-item__text__supporting-text",
                  lang: (S = e.language) == null ? void 0 : S.supportingText
                }, [
                  m("bdi", null, j(e.supportingText), 1)
                ], 8, qn)
              ], 64)) : k("", !0),
              e.description ? (r(), b("span", {
                key: 4,
                class: "cdx-menu-item__text__description",
                lang: (I = e.language) == null ? void 0 : I.description
              }, [
                m("bdi", null, j(e.description), 1)
              ], 8, On)) : k("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, Vn);
}
const jn = /* @__PURE__ */ V(Fn, [["render", zn]]), Hn = F({
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
const Un = ["aria-disabled"], Wn = /* @__PURE__ */ m("div", { class: "cdx-progress-bar__bar" }, null, -1), Pn = [
  Wn
];
function Qn(e, t, l, s, a, i) {
  return r(), b("div", {
    class: N(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Pn, 10, Un);
}
const Gn = /* @__PURE__ */ V(Hn, [["render", Qn]]);
let xe = 0;
function ae(e) {
  const t = ht(), l = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${Be}-${e}-${xe++}` : l ? `${Be}-${l}-${xe++}` : `${Be}-${xe++}`;
}
function we(e, t) {
  const l = f(!1);
  let s = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return l;
  const a = new window.IntersectionObserver(
    (i) => {
      const o = i[0];
      o && (l.value = o.isIntersecting);
    },
    t
  );
  return ie(() => {
    s = !0, e.value && a.observe(e.value);
  }), Me(() => {
    s = !1, a.disconnect();
  }), te(e, (i) => {
    !s || (a.disconnect(), l.value = !1, i && a.observe(i));
  }), l;
}
function pe(e, t = p(() => ({}))) {
  const l = p(() => {
    const i = Ce(t.value, []);
    return e.class && e.class.split(" ").forEach((u) => {
      i[u] = !0;
    }), i;
  }), s = p(() => {
    if ("style" in e)
      return e.style;
  }), a = p(() => {
    const n = e, { class: i, style: o } = n;
    return Ce(n, ["class", "style"]);
  });
  return {
    rootClasses: l,
    rootStyle: s,
    otherAttrs: a
  };
}
const Jn = F({
  name: "CdxMenu",
  components: {
    CdxMenuItem: jn,
    CdxProgressBar: Gn
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
    "delegateKeyNavigation"
  ],
  setup(e, { emit: t, slots: l, attrs: s }) {
    const a = p(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((x) => ze(Oe({}, x), {
      id: ae("menu-item")
    }))), i = p(() => l["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : a.value.length === 0 : !1), o = f(null), u = f(null);
    function n() {
      return a.value.find(
        (h) => h.value === e.selected
      );
    }
    function d(h, x) {
      var q;
      if (!(x && x.disabled))
        switch (h) {
          case "selected":
            t("update:selected", (q = x == null ? void 0 : x.value) != null ? q : null), t("update:expanded", !1), u.value = null;
            break;
          case "highlighted":
            o.value = x || null;
            break;
          case "active":
            u.value = x || null;
            break;
        }
    }
    const c = p(() => {
      if (o.value !== null)
        return a.value.findIndex(
          (h) => h.value === o.value.value
        );
    });
    function $(h) {
      !h || (d("highlighted", h), t("menu-item-keyboard-navigation", h));
    }
    function y(h) {
      var H;
      const x = (fe) => {
        for (let ue = fe - 1; ue >= 0; ue--)
          if (!a.value[ue].disabled)
            return a.value[ue];
      };
      h = h || a.value.length;
      const q = (H = x(h)) != null ? H : x(a.value.length);
      $(q);
    }
    function S(h) {
      const x = (H) => a.value.find((fe, ue) => !fe.disabled && ue > H);
      h = h != null ? h : -1;
      const q = x(h) || x(-1);
      $(q);
    }
    function I(h, x = !0) {
      function q() {
        t("update:expanded", !0), d("highlighted", n());
      }
      function H() {
        x && (h.preventDefault(), h.stopPropagation());
      }
      switch (h.key) {
        case "Enter":
        case " ":
          return H(), e.expanded ? (o.value && t("update:selected", o.value.value), t("update:expanded", !1)) : q(), !0;
        case "Tab":
          return e.expanded && (o.value && t("update:selected", o.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return H(), e.expanded ? (o.value === null && d("highlighted", n()), y(c.value)) : q(), W(), !0;
        case "ArrowDown":
          return H(), e.expanded ? (o.value === null && d("highlighted", n()), S(c.value)) : q(), W(), !0;
        case "Home":
          return H(), e.expanded ? (o.value === null && d("highlighted", n()), S()) : q(), W(), !0;
        case "End":
          return H(), e.expanded ? (o.value === null && d("highlighted", n()), y()) : q(), W(), !0;
        case "Escape":
          return H(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function L() {
      d("active");
    }
    const C = [], z = f(void 0), D = we(
      z,
      { threshold: 0.8 }
    );
    te(D, (h) => {
      h && t("load-more");
    });
    function K(h, x) {
      if (h) {
        C[x] = h.$el;
        const q = e.visibleItemLimit;
        if (!q || e.menuItems.length < q)
          return;
        const H = Math.min(
          q,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        x === e.menuItems.length - H && (z.value = h.$el);
      }
    }
    function W() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || c.value === void 0)
        return;
      const h = c.value >= 0 ? c.value : 0;
      C[h].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const P = f(null), Q = f(null);
    function ne() {
      if (Q.value = null, !e.visibleItemLimit || C.length <= e.visibleItemLimit) {
        P.value = null;
        return;
      }
      const h = C[0], x = C[e.visibleItemLimit];
      if (P.value = g(
        h,
        x
      ), e.footer) {
        const q = C[C.length - 1];
        Q.value = q.scrollHeight;
      }
    }
    function g(h, x) {
      const q = h.getBoundingClientRect().top;
      return x.getBoundingClientRect().top - q + 2;
    }
    ie(() => {
      document.addEventListener("mouseup", L);
    }), Me(() => {
      document.removeEventListener("mouseup", L);
    }), te(G(e, "expanded"), (h) => Ie(this, null, function* () {
      const x = n();
      !h && o.value && x === void 0 && d("highlighted"), h && x !== void 0 && d("highlighted", x), h && (yield be(), ne(), yield be(), W());
    })), te(G(e, "menuItems"), (h) => Ie(this, null, function* () {
      h.length < C.length && (C.length = h.length), e.expanded && (yield be(), ne(), yield be(), W());
    }), { deep: !0 });
    const v = p(() => ({
      "max-height": P.value ? `${P.value}px` : void 0,
      "overflow-y": P.value ? "scroll" : void 0,
      "margin-bottom": Q.value ? `${Q.value}px` : void 0
    })), B = p(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!P.value
    })), {
      rootClasses: R,
      rootStyle: M,
      otherAttrs: ee
    } = pe(s, B);
    return {
      listBoxStyle: v,
      rootClasses: R,
      rootStyle: M,
      otherAttrs: ee,
      assignTemplateRef: K,
      computedMenuItems: a,
      computedShowNoResultsSlot: i,
      highlightedMenuItem: o,
      activeMenuItem: u,
      handleMenuItemChange: d,
      handleKeyNavigation: I
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
const Xn = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Yn = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function Zn(e, t, l, s, a, i) {
  const o = A("cdx-menu-item"), u = A("cdx-progress-bar");
  return oe((r(), b("div", {
    class: N(["cdx-menu", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    m("ul", Z({
      class: "cdx-menu__listbox",
      role: "listbox",
      "aria-multiselectable": "false",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (r(), b("li", Xn, [
        w(e.$slots, "pending")
      ])) : k("", !0),
      e.computedShowNoResultsSlot ? (r(), b("li", Yn, [
        w(e.$slots, "no-results")
      ])) : k("", !0),
      (r(!0), b(ve, null, _e(e.computedMenuItems, (n, d) => {
        var c, $;
        return r(), E(o, Z({
          key: n.value,
          ref_for: !0,
          ref: (y) => e.assignTemplateRef(y, d)
        }, n, {
          selected: n.value === e.selected,
          active: n.value === ((c = e.activeMenuItem) == null ? void 0 : c.value),
          highlighted: n.value === (($ = e.highlightedMenuItem) == null ? void 0 : $.value),
          "show-thumbnail": e.showThumbnail,
          "bold-label": e.boldLabel,
          "hide-description-overflow": e.hideDescriptionOverflow,
          "search-query": e.searchQuery,
          onChange: (y, S) => e.handleMenuItemChange(y, S && n),
          onClick: (y) => e.$emit("menu-item-click", n)
        }), {
          default: T(() => {
            var y, S;
            return [
              w(e.$slots, "default", {
                menuItem: n,
                active: n.value === ((y = e.activeMenuItem) == null ? void 0 : y.value) && n.value === ((S = e.highlightedMenuItem) == null ? void 0 : S.value)
              })
            ];
          }),
          _: 2
        }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
      }), 128)),
      e.showPending ? (r(), E(u, {
        key: 2,
        class: "cdx-menu__progress-bar",
        inline: !0
      })) : k("", !0)
    ], 16)
  ], 6)), [
    [ge, e.expanded]
  ]);
}
const Ae = /* @__PURE__ */ V(Jn, [["render", Zn]]), el = re(At), tl = re($e), nl = F({
  name: "CdxTextInput",
  components: { CdxIcon: J },
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
      validator: el
    },
    status: {
      type: String,
      default: "default",
      validator: tl
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
  setup(e, { emit: t, attrs: l }) {
    const s = se(G(e, "modelValue"), t), a = p(() => e.clearable && !!s.value && !e.disabled), i = p(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": a.value
    })), {
      rootClasses: o,
      rootStyle: u,
      otherAttrs: n
    } = pe(l, i), d = p(() => ({
      "cdx-text-input__input--has-value": !!s.value,
      [`cdx-text-input__input--status-${e.status}`]: !0
    }));
    return {
      wrappedModel: s,
      isClearable: a,
      rootClasses: o,
      rootStyle: u,
      otherAttrs: n,
      inputClasses: d,
      onClear: () => {
        s.value = "";
      },
      onInput: (C) => {
        t("input", C);
      },
      onChange: (C) => {
        t("change", C);
      },
      onKeydown: (C) => {
        (C.key === "Home" || C.key === "End") && !C.ctrlKey && !C.metaKey || t("keydown", C);
      },
      onFocus: (C) => {
        t("focus", C);
      },
      onBlur: (C) => {
        t("blur", C);
      },
      cdxIconClear: zt
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    }
  }
});
const ll = ["type", "disabled"];
function ol(e, t, l, s, a, i) {
  const o = A("cdx-icon");
  return r(), b("div", {
    class: N(["cdx-text-input", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    oe(m("input", Z({
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (u) => e.wrappedModel = u),
      class: ["cdx-text-input__input", e.inputClasses]
    }, e.otherAttrs, {
      type: e.inputType,
      disabled: e.disabled,
      onInput: t[1] || (t[1] = (...u) => e.onInput && e.onInput(...u)),
      onChange: t[2] || (t[2] = (...u) => e.onChange && e.onChange(...u)),
      onFocus: t[3] || (t[3] = (...u) => e.onFocus && e.onFocus(...u)),
      onBlur: t[4] || (t[4] = (...u) => e.onBlur && e.onBlur(...u)),
      onKeydown: t[5] || (t[5] = (...u) => e.onKeydown && e.onKeydown(...u))
    }), null, 16, ll), [
      [bt, e.wrappedModel]
    ]),
    e.startIcon ? (r(), E(o, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : k("", !0),
    e.endIcon ? (r(), E(o, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : k("", !0),
    e.isClearable ? (r(), E(o, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = X(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : k("", !0)
  ], 6);
}
const Ee = /* @__PURE__ */ V(nl, [["render", ol]]);
function Te(e) {
  const t = f(
    { width: void 0, height: void 0 }
  );
  if (typeof window != "object" || !("ResizeObserver" in window) || !("ResizeObserverEntry" in window))
    return t;
  const l = new window.ResizeObserver(
    (a) => {
      const i = a[0];
      i && (t.value = {
        width: i.borderBoxSize[0].inlineSize,
        height: i.borderBoxSize[0].blockSize
      });
    }
  );
  let s = !1;
  return ie(() => {
    s = !0, e.value && l.observe(e.value);
  }), Me(() => {
    s = !1, l.disconnect();
  }), te(e, (a) => {
    !s || (l.disconnect(), t.value = {
      width: void 0,
      height: void 0
    }, a && l.observe(a));
  }), t;
}
const al = re($e), Le = F({
  name: "CdxCombobox",
  components: {
    CdxButton: he,
    CdxIcon: J,
    CdxMenu: Ae,
    CdxTextInput: Ee
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
    },
    status: {
      type: String,
      default: "default",
      validator: al
    }
  },
  emits: [
    "update:selected",
    "load-more"
  ],
  setup(e, { emit: t, attrs: l, slots: s }) {
    const a = f(), i = f(), o = f(), u = ae("combobox"), n = G(e, "selected"), d = se(n, t, "update:selected"), c = f(!1), $ = f(!1), y = p(() => {
      var g, v;
      return (v = (g = o.value) == null ? void 0 : g.getHighlightedMenuItem()) == null ? void 0 : v.id;
    }), S = p(() => ({
      "cdx-combobox--expanded": c.value,
      "cdx-combobox--disabled": e.disabled
    })), I = Te(i), L = p(() => {
      var g;
      return `${(g = I.value.width) != null ? g : 0}px`;
    }), {
      rootClasses: C,
      rootStyle: z,
      otherAttrs: D
    } = pe(l, S);
    function K() {
      $.value && c.value ? c.value = !1 : (e.menuItems.length > 0 || s["no-results"]) && (c.value = !0);
    }
    function W() {
      c.value = $.value && c.value;
    }
    function P() {
      e.disabled || ($.value = !0);
    }
    function Q() {
      var g;
      e.disabled || (g = a.value) == null || g.focus();
    }
    function ne(g) {
      !o.value || e.disabled || e.menuItems.length === 0 || g.key === " " && c.value || o.value.delegateKeyNavigation(g);
    }
    return te(c, () => {
      $.value = !1;
    }), {
      input: a,
      inputWrapper: i,
      currentWidthInPx: L,
      menu: o,
      menuId: u,
      modelWrapper: d,
      expanded: c,
      highlightedId: y,
      onInputFocus: K,
      onInputBlur: W,
      onKeydown: ne,
      onButtonClick: Q,
      onButtonMousedown: P,
      cdxIconExpand: nt,
      rootClasses: C,
      rootStyle: z,
      otherAttrs: D
    };
  }
}), Ue = () => {
  De((e) => ({
    "3630e383": e.currentWidthInPx
  }));
}, We = Le.setup;
Le.setup = We ? (e, t) => (Ue(), We(e, t)) : Ue;
const sl = {
  ref: "inputWrapper",
  class: "cdx-combobox__input-wrapper"
};
function ul(e, t, l, s, a, i) {
  const o = A("cdx-text-input"), u = A("cdx-icon"), n = A("cdx-button"), d = A("cdx-menu");
  return r(), b("div", {
    class: N(["cdx-combobox", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    m("div", sl, [
      O(o, Z({
        ref: "input",
        modelValue: e.modelWrapper,
        "onUpdate:modelValue": t[0] || (t[0] = (c) => e.modelWrapper = c)
      }, e.otherAttrs, {
        class: "cdx-combobox__input",
        "aria-activedescendant": e.highlightedId,
        "aria-expanded": e.expanded,
        "aria-owns": e.menuId,
        disabled: e.disabled,
        status: e.status,
        "aria-autocomplete": "list",
        autocomplete: "off",
        role: "combobox",
        onKeydown: e.onKeydown,
        onFocus: e.onInputFocus,
        onBlur: e.onInputBlur
      }), null, 16, ["modelValue", "aria-activedescendant", "aria-expanded", "aria-owns", "disabled", "status", "onKeydown", "onFocus", "onBlur"]),
      O(n, {
        class: "cdx-combobox__expand-button",
        "aria-hidden": "true",
        disabled: e.disabled,
        tabindex: "-1",
        onMousedown: e.onButtonMousedown,
        onClick: e.onButtonClick
      }, {
        default: T(() => [
          O(u, {
            class: "cdx-combobox__expand-icon",
            icon: e.cdxIconExpand
          }, null, 8, ["icon"])
        ]),
        _: 1
      }, 8, ["disabled", "onMousedown", "onClick"])
    ], 512),
    O(d, Z({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[1] || (t[1] = (c) => e.modelWrapper = c),
      expanded: e.expanded,
      "onUpdate:expanded": t[2] || (t[2] = (c) => e.expanded = c),
      "menu-items": e.menuItems
    }, e.menuConfig, {
      onLoadMore: t[3] || (t[3] = (c) => e.$emit("load-more"))
    }), {
      default: T(({ menuItem: c }) => [
        w(e.$slots, "menu-item", { menuItem: c })
      ]),
      "no-results": T(() => [
        w(e.$slots, "no-results")
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 6);
}
const Io = /* @__PURE__ */ V(Le, [["render", ul]]), il = F({
  name: "CdxDialog",
  components: {
    CdxButton: he,
    CdxIcon: J
  },
  props: {
    open: {
      type: Boolean,
      default: !1
    },
    title: {
      type: String,
      required: !0
    },
    hideTitle: {
      type: Boolean,
      default: !1
    },
    closeButtonLabel: {
      type: String,
      default: ""
    },
    primaryAction: {
      type: Object,
      default: null
    },
    defaultAction: {
      type: Object,
      default: null
    },
    stackedActions: {
      type: Boolean,
      default: !1
    },
    showDividers: {
      type: Boolean,
      default: !1
    }
  },
  emits: [
    "update:open",
    "primary",
    "default"
  ],
  setup(e, { emit: t }) {
    const l = ae("dialog-label"), s = f(), a = f(), i = f(), o = f(), u = f(), n = p(() => !e.hideTitle || !!e.closeButtonLabel), d = p(() => ({
      "cdx-dialog--vertical-actions": e.stackedActions,
      "cdx-dialog--horizontal-actions": !e.stackedActions,
      "cdx-dialog--dividers": e.showDividers
    })), c = f(0);
    function $() {
      t("update:open", !1);
    }
    function y() {
      I(s.value);
    }
    function S() {
      I(s.value, !0);
    }
    function I(L, C = !1) {
      let z = Array.from(
        L.querySelectorAll(`
					input, select, textarea, button, object, a, area,
					[contenteditable], [tabindex]:not([tabindex^="-"])
				`)
      );
      C && (z = z.reverse());
      for (const D of z)
        if (D.focus(), document.activeElement === D)
          return !0;
      return !1;
    }
    return te(G(e, "open"), (L) => {
      L ? (c.value = window.innerWidth - document.documentElement.clientWidth, document.documentElement.style.setProperty("margin-right", `${c.value}px`), document.body.classList.add("cdx-dialog-open"), be(() => {
        var C;
        I(a.value) || (C = i.value) == null || C.focus();
      })) : (document.body.classList.remove("cdx-dialog-open"), document.documentElement.style.removeProperty("margin-right"));
    }), {
      close: $,
      cdxIconClose: tt,
      labelId: l,
      rootClasses: d,
      dialogElement: s,
      focusTrapStart: o,
      focusTrapEnd: u,
      focusFirst: y,
      focusLast: S,
      dialogBody: a,
      focusHolder: i,
      showHeader: n
    };
  }
});
const dl = ["aria-labelledby"], rl = {
  key: 0,
  class: "cdx-dialog__header"
}, cl = ["id"], pl = {
  ref: "focusHolder",
  class: "cdx-dialog-focus-trap",
  tabindex: "-1"
}, fl = {
  ref: "dialogBody",
  class: "cdx-dialog__body"
}, ml = {
  key: 1,
  class: "cdx-dialog__footer"
};
function vl(e, t, l, s, a, i) {
  const o = A("cdx-icon"), u = A("cdx-button");
  return r(), E(Se, {
    name: "cdx-dialog-fade",
    appear: ""
  }, {
    default: T(() => [
      e.open ? (r(), b("div", {
        key: 0,
        class: "cdx-dialog-backdrop",
        onClick: t[5] || (t[5] = (...n) => e.close && e.close(...n)),
        onKeyup: t[6] || (t[6] = Y((...n) => e.close && e.close(...n), ["escape"]))
      }, [
        m("div", {
          ref: "focusTrapStart",
          tabindex: "0",
          onFocus: t[0] || (t[0] = (...n) => e.focusLast && e.focusLast(...n))
        }, null, 544),
        m("div", {
          ref: "dialogElement",
          class: N(["cdx-dialog", e.rootClasses]),
          role: "dialog",
          "aria-labelledby": e.labelId,
          onClick: t[3] || (t[3] = X(() => {
          }, ["stop"]))
        }, [
          e.showHeader ? (r(), b("div", rl, [
            oe(m("h2", {
              id: e.labelId,
              class: "cdx-dialog__header__title"
            }, j(e.title), 9, cl), [
              [ge, !e.hideTitle]
            ]),
            e.closeButtonLabel ? (r(), E(u, {
              key: 0,
              class: "cdx-dialog__header__close-button",
              type: "quiet",
              "aria-label": e.closeButtonLabel,
              onClick: e.close
            }, {
              default: T(() => [
                O(o, {
                  icon: e.cdxIconClose,
                  "icon-label": e.closeButtonLabel
                }, null, 8, ["icon", "icon-label"])
              ]),
              _: 1
            }, 8, ["aria-label", "onClick"])) : k("", !0)
          ])) : k("", !0),
          m("div", pl, null, 512),
          m("div", fl, [
            w(e.$slots, "default")
          ], 512),
          e.primaryAction || e.defaultAction ? (r(), b("div", ml, [
            e.primaryAction ? (r(), E(u, {
              key: 0,
              class: "cdx-dialog__footer__primary-action",
              type: "primary",
              action: e.primaryAction.actionType,
              disabled: e.primaryAction.disabled,
              onClick: t[1] || (t[1] = (n) => e.$emit("primary"))
            }, {
              default: T(() => [
                le(j(e.primaryAction.label), 1)
              ]),
              _: 1
            }, 8, ["action", "disabled"])) : k("", !0),
            e.defaultAction ? (r(), E(u, {
              key: 1,
              class: "cdx-dialog__footer__default-action",
              disabled: e.defaultAction.disabled,
              onClick: t[2] || (t[2] = (n) => e.$emit("default"))
            }, {
              default: T(() => [
                le(j(e.defaultAction.label), 1)
              ]),
              _: 1
            }, 8, ["disabled"])) : k("", !0)
          ])) : k("", !0)
        ], 10, dl),
        m("div", {
          ref: "focusTrapEnd",
          tabindex: "0",
          onFocus: t[4] || (t[4] = (...n) => e.focusFirst && e.focusFirst(...n))
        }, null, 544)
      ], 32)) : k("", !0)
    ]),
    _: 3
  });
}
const Bo = /* @__PURE__ */ V(il, [["render", vl]]), hl = re($e), Fe = F({
  name: "CdxLookup",
  components: {
    CdxMenu: Ae,
    CdxTextInput: Ee
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
    },
    status: {
      type: String,
      default: "default",
      validator: hl
    }
  },
  emits: [
    "update:selected",
    "input",
    "load-more"
  ],
  setup: (e, { emit: t, attrs: l, slots: s }) => {
    const a = f(), i = f(), o = ae("lookup-menu"), u = f(!1), n = f(!1), d = f(!1), c = G(e, "selected"), $ = se(c, t, "update:selected"), y = p(
      () => e.menuItems.find((v) => v.value === e.selected)
    ), S = p(() => {
      var v, B;
      return (B = (v = i.value) == null ? void 0 : v.getHighlightedMenuItem()) == null ? void 0 : B.id;
    }), I = f(e.initialInputValue), L = Te(a), C = p(() => {
      var v;
      return `${(v = L.value.width) != null ? v : 0}px`;
    }), z = p(() => ({
      "cdx-lookup--disabled": e.disabled,
      "cdx-lookup--pending": u.value
    })), {
      rootClasses: D,
      rootStyle: K,
      otherAttrs: W
    } = pe(l, z);
    function P(v) {
      y.value && y.value.label !== v && y.value.value !== v && ($.value = null), v === "" ? (n.value = !1, u.value = !1) : u.value = !0, t("input", v);
    }
    function Q() {
      d.value = !0, I.value !== null && I.value !== "" && (e.menuItems.length > 0 || s["no-results"]) && (n.value = !0);
    }
    function ne() {
      d.value = !1, n.value = !1;
    }
    function g(v) {
      !i.value || e.disabled || e.menuItems.length === 0 && !s["no-results"] || v.key === " " && n.value || i.value.delegateKeyNavigation(v);
    }
    return te(c, (v) => {
      if (v !== null) {
        const B = y.value ? y.value.label || y.value.value : "";
        I.value !== B && (I.value = B, t("input", I.value));
      }
    }), te(G(e, "menuItems"), (v) => {
      d.value && u.value && (v.length > 0 || s["no-results"]) && (n.value = !0), v.length === 0 && !s["no-results"] && (n.value = !1), u.value = !1;
    }), {
      rootElement: a,
      currentWidthInPx: C,
      menu: i,
      menuId: o,
      highlightedId: S,
      inputValue: I,
      modelWrapper: $,
      expanded: n,
      onInputBlur: ne,
      rootClasses: D,
      rootStyle: K,
      otherAttrs: W,
      onUpdateInput: P,
      onInputFocus: Q,
      onKeydown: g
    };
  }
}), Pe = () => {
  De((e) => ({
    "016ac659": e.currentWidthInPx
  }));
}, Qe = Fe.setup;
Fe.setup = Qe ? (e, t) => (Pe(), Qe(e, t)) : Pe;
function bl(e, t, l, s, a, i) {
  const o = A("cdx-text-input"), u = A("cdx-menu");
  return r(), b("div", {
    ref: "rootElement",
    class: N(["cdx-lookup", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    O(o, Z({
      modelValue: e.inputValue,
      "onUpdate:modelValue": t[0] || (t[0] = (n) => e.inputValue = n)
    }, e.otherAttrs, {
      class: "cdx-lookup__input",
      role: "combobox",
      autocomplete: "off",
      "aria-autocomplete": "list",
      "aria-owns": e.menuId,
      "aria-expanded": e.expanded,
      "aria-activedescendant": e.highlightedId,
      disabled: e.disabled,
      status: e.status,
      "onUpdate:modelValue": e.onUpdateInput,
      onFocus: e.onInputFocus,
      onBlur: e.onInputBlur,
      onKeydown: e.onKeydown
    }), null, 16, ["modelValue", "aria-owns", "aria-expanded", "aria-activedescendant", "disabled", "status", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
    O(u, Z({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[1] || (t[1] = (n) => e.modelWrapper = n),
      expanded: e.expanded,
      "onUpdate:expanded": t[2] || (t[2] = (n) => e.expanded = n),
      "menu-items": e.menuItems
    }, e.menuConfig, {
      onLoadMore: t[3] || (t[3] = (n) => e.$emit("load-more"))
    }), {
      default: T(({ menuItem: n }) => [
        w(e.$slots, "menu-item", { menuItem: n })
      ]),
      "no-results": T(() => [
        w(e.$slots, "no-results")
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 6);
}
const xo = /* @__PURE__ */ V(Fe, [["render", bl]]), gl = re($t), yl = {
  notice: Ut,
  error: jt,
  warning: Rt,
  success: Ot
}, Cl = F({
  name: "CdxMessage",
  components: { CdxButton: he, CdxIcon: J },
  props: {
    type: {
      type: String,
      default: "notice",
      validator: gl
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
    const l = f(!1), s = p(
      () => e.inline === !1 && e.dismissButtonLabel.length > 0
    ), a = p(() => e.autoDismiss === !1 ? !1 : e.autoDismiss === !0 ? 4e3 : e.autoDismiss), i = p(() => ({
      "cdx-message--inline": e.inline,
      "cdx-message--block": !e.inline,
      "cdx-message--user-dismissable": s.value,
      [`cdx-message--${e.type}`]: !0
    })), o = p(
      () => e.icon && e.type === "notice" ? e.icon : yl[e.type]
    ), u = f("");
    function n(d) {
      l.value || (u.value = d === "user-dismissed" ? "cdx-message-leave-active-user" : "cdx-message-leave-active-system", l.value = !0, t(d));
    }
    return ie(() => {
      a.value && setTimeout(() => n("auto-dismissed"), a.value);
    }), {
      dismissed: l,
      userDismissable: s,
      rootClasses: i,
      leaveActiveClass: u,
      computedIcon: o,
      onDismiss: n,
      cdxIconClose: tt
    };
  }
});
const _l = ["aria-live", "role"], $l = { class: "cdx-message__content" };
function Al(e, t, l, s, a, i) {
  const o = A("cdx-icon"), u = A("cdx-button");
  return r(), E(Se, {
    name: "cdx-message",
    appear: e.fadeIn,
    "leave-active-class": e.leaveActiveClass
  }, {
    default: T(() => [
      e.dismissed ? k("", !0) : (r(), b("div", {
        key: 0,
        class: N(["cdx-message", e.rootClasses]),
        "aria-live": e.type !== "error" ? "polite" : void 0,
        role: e.type === "error" ? "alert" : void 0
      }, [
        O(o, {
          class: "cdx-message__icon",
          icon: e.computedIcon
        }, null, 8, ["icon"]),
        m("div", $l, [
          w(e.$slots, "default")
        ]),
        e.userDismissable ? (r(), E(u, {
          key: 0,
          class: "cdx-message__dismiss-button",
          type: "quiet",
          "aria-label": e.dismissButtonLabel,
          onClick: t[0] || (t[0] = (n) => e.onDismiss("user-dismissed"))
        }, {
          default: T(() => [
            O(o, {
              icon: e.cdxIconClose,
              "icon-label": e.dismissButtonLabel
            }, null, 8, ["icon", "icon-label"])
          ]),
          _: 1
        }, 8, ["aria-label"])) : k("", !0)
      ], 10, _l))
    ]),
    _: 3
  }, 8, ["appear", "leave-active-class"]);
}
const ko = /* @__PURE__ */ V(Cl, [["render", Al]]), Il = F({
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
    const l = p(() => ({
      "cdx-radio--inline": e.inline
    })), s = f(), a = () => {
      s.value.focus();
    }, i = se(G(e, "modelValue"), t);
    return {
      rootClasses: l,
      input: s,
      focusInput: a,
      wrappedModel: i
    };
  }
});
const Bl = ["name", "value", "disabled"], xl = /* @__PURE__ */ m("span", { class: "cdx-radio__icon" }, null, -1), kl = { class: "cdx-radio__label-content" };
function wl(e, t, l, s, a, i) {
  return r(), b("span", {
    class: N(["cdx-radio", e.rootClasses])
  }, [
    m("label", {
      class: "cdx-radio__label",
      onClick: t[1] || (t[1] = (...o) => e.focusInput && e.focusInput(...o))
    }, [
      oe(m("input", {
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-radio__input",
        type: "radio",
        name: e.name,
        value: e.inputValue,
        disabled: e.disabled
      }, null, 8, Bl), [
        [gt, e.wrappedModel]
      ]),
      xl,
      m("span", kl, [
        w(e.$slots, "default")
      ])
    ])
  ], 2);
}
const wo = /* @__PURE__ */ V(Il, [["render", wl]]), Sl = re($e), Ml = F({
  name: "CdxSearchInput",
  components: {
    CdxButton: he,
    CdxTextInput: Ee
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
      validator: Sl
    }
  },
  emits: [
    "update:modelValue",
    "submit-click"
  ],
  setup(e, { emit: t, attrs: l }) {
    const s = se(G(e, "modelValue"), t), a = p(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: i,
      rootStyle: o,
      otherAttrs: u
    } = pe(l, a);
    return {
      wrappedModel: s,
      rootClasses: i,
      rootStyle: o,
      otherAttrs: u,
      handleSubmit: () => {
        t("submit-click", s.value);
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
const Dl = { class: "cdx-search-input__input-wrapper" };
function El(e, t, l, s, a, i) {
  const o = A("cdx-text-input"), u = A("cdx-button");
  return r(), b("div", {
    class: N(["cdx-search-input", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    m("div", Dl, [
      O(o, Z({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (n) => e.wrappedModel = n),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon,
        status: e.status
      }, e.otherAttrs, {
        onKeydown: Y(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "status", "onKeydown"]),
      w(e.$slots, "default")
    ]),
    e.buttonLabel ? (r(), E(u, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: T(() => [
        le(j(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : k("", !0)
  ], 6);
}
const Tl = /* @__PURE__ */ V(Ml, [["render", El]]), Ve = F({
  name: "CdxSelect",
  components: {
    CdxIcon: J,
    CdxMenu: Ae
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
    const l = f(), s = f(), a = ae("select-handle"), i = ae("select-menu"), o = f(!1), u = se(G(e, "selected"), t, "update:selected"), n = p(
      () => e.menuItems.find((D) => D.value === e.selected)
    ), d = p(() => n.value ? n.value.label || n.value.value : e.defaultLabel), c = Te(l), $ = p(() => {
      var D;
      return `${(D = c.value.width) != null ? D : 0}px`;
    }), y = p(() => {
      if (e.defaultIcon && !n.value)
        return e.defaultIcon;
      if (n.value && n.value.icon)
        return n.value.icon;
    }), S = p(() => ({
      "cdx-select--enabled": !e.disabled,
      "cdx-select--disabled": e.disabled,
      "cdx-select--expanded": o.value,
      "cdx-select--value-selected": !!n.value,
      "cdx-select--no-selections": !n.value,
      "cdx-select--has-start-icon": !!y.value
    })), I = p(() => {
      var D, K;
      return (K = (D = s.value) == null ? void 0 : D.getHighlightedMenuItem()) == null ? void 0 : K.id;
    });
    function L() {
      o.value = !1;
    }
    function C() {
      var D;
      e.disabled || (o.value = !o.value, (D = l.value) == null || D.focus());
    }
    function z(D) {
      var K;
      e.disabled || (K = s.value) == null || K.delegateKeyNavigation(D);
    }
    return {
      handle: l,
      handleId: a,
      menu: s,
      menuId: i,
      modelWrapper: u,
      selectedMenuItem: n,
      highlightedId: I,
      expanded: o,
      onBlur: L,
      currentLabel: d,
      currentWidthInPx: $,
      rootClasses: S,
      onClick: C,
      onKeydown: z,
      startIcon: y,
      cdxIconExpand: nt
    };
  }
}), Ge = () => {
  De((e) => ({
    "2383866c": e.currentWidthInPx
  }));
}, Je = Ve.setup;
Ve.setup = Je ? (e, t) => (Ge(), Je(e, t)) : Ge;
const Ll = ["aria-disabled"], Fl = ["aria-owns", "aria-labelledby", "aria-activedescendant", "aria-expanded"], Vl = ["id"];
function Kl(e, t, l, s, a, i) {
  const o = A("cdx-icon"), u = A("cdx-menu");
  return r(), b("div", {
    class: N(["cdx-select", e.rootClasses]),
    "aria-disabled": e.disabled
  }, [
    m("div", {
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
      onClick: t[0] || (t[0] = (...n) => e.onClick && e.onClick(...n)),
      onBlur: t[1] || (t[1] = (...n) => e.onBlur && e.onBlur(...n)),
      onKeydown: t[2] || (t[2] = (...n) => e.onKeydown && e.onKeydown(...n))
    }, [
      m("span", {
        id: e.handleId,
        role: "textbox",
        "aria-readonly": "true"
      }, [
        w(e.$slots, "label", {
          selectedMenuItem: e.selectedMenuItem,
          defaultLabel: e.defaultLabel
        }, () => [
          le(j(e.currentLabel), 1)
        ])
      ], 8, Vl),
      e.startIcon ? (r(), E(o, {
        key: 0,
        icon: e.startIcon,
        class: "cdx-select__start-icon"
      }, null, 8, ["icon"])) : k("", !0),
      O(o, {
        icon: e.cdxIconExpand,
        class: "cdx-select__indicator"
      }, null, 8, ["icon"])
    ], 40, Fl),
    O(u, Z({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[3] || (t[3] = (n) => e.modelWrapper = n),
      expanded: e.expanded,
      "onUpdate:expanded": t[4] || (t[4] = (n) => e.expanded = n),
      "menu-items": e.menuItems
    }, e.menuConfig, {
      onLoadMore: t[5] || (t[5] = (n) => e.$emit("load-more"))
    }), {
      default: T(({ menuItem: n }) => [
        w(e.$slots, "menu-item", { menuItem: n })
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 10, Ll);
}
const So = /* @__PURE__ */ V(Ve, [["render", Kl]]), Nl = F({
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
    const t = je(Ze), l = je(et);
    if (!t || !l)
      throw new Error("Tab component must be used inside a Tabs component");
    const s = t.value.get(e.name) || {}, a = p(() => e.name === l.value);
    return {
      tab: s,
      isActive: a
    };
  }
});
const Rl = ["id", "aria-hidden", "aria-labelledby"];
function ql(e, t, l, s, a, i) {
  return oe((r(), b("section", {
    id: e.tab.id,
    "aria-hidden": !e.isActive,
    "aria-labelledby": `${e.tab.id}-label`,
    class: "cdx-tab",
    role: "tabpanel",
    tabindex: "-1"
  }, [
    w(e.$slots, "default")
  ], 8, Rl)), [
    [ge, e.isActive]
  ]);
}
const Mo = /* @__PURE__ */ V(Nl, [["render", ql]]), Ol = F({
  name: "CdxTabs",
  components: {
    CdxButton: he,
    CdxIcon: J
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
  setup(e, { slots: t, emit: l }) {
    const s = f(), a = f(), i = f(), o = f(), u = f(), n = lt(s), d = p(() => {
      var R;
      const g = [], v = (R = t.default) == null ? void 0 : R.call(t);
      v && v.forEach(B);
      function B(M) {
        M && typeof M == "object" && "type" in M && (typeof M.type == "object" && "name" in M.type && M.type.name === "CdxTab" ? g.push(M) : "children" in M && Array.isArray(M.children) && M.children.forEach(B));
      }
      return g;
    });
    if (!d.value || d.value.length === 0)
      throw new Error("Slot content cannot be empty");
    const c = p(() => d.value.reduce((g, v) => {
      var B;
      if (((B = v.props) == null ? void 0 : B.name) && typeof v.props.name == "string") {
        if (g.get(v.props.name))
          throw new Error("Tab names must be unique");
        g.set(v.props.name, {
          name: v.props.name,
          id: ae(v.props.name),
          label: v.props.label || v.props.name,
          disabled: v.props.disabled
        });
      }
      return g;
    }, /* @__PURE__ */ new Map())), $ = se(G(e, "active"), l, "update:active"), y = p(() => Array.from(c.value.keys())), S = p(() => y.value.indexOf($.value)), I = p(() => {
      var g;
      return (g = c.value.get($.value)) == null ? void 0 : g.id;
    });
    He(et, $), He(Ze, c);
    const L = f(), C = f(), z = we(L, { threshold: 0.95 }), D = we(C, { threshold: 0.95 });
    function K(g, v) {
      const B = g;
      B && (v === 0 ? L.value = B : v === y.value.length - 1 && (C.value = B));
    }
    function W(g) {
      var R;
      const v = g === $.value, B = !!((R = c.value.get(g)) != null && R.disabled);
      return {
        "cdx-tabs__list__item--selected": v,
        "cdx-tabs__list__item--enabled": !B,
        "cdx-tabs__list__item--disabled": B
      };
    }
    const P = p(() => ({
      "cdx-tabs--framed": e.framed,
      "cdx-tabs--quiet": !e.framed
    }));
    function Q(g) {
      if (!a.value || !o.value || !u.value)
        return 0;
      const v = n.value === "rtl" ? u.value : o.value, B = n.value === "rtl" ? o.value : u.value, R = g.offsetLeft, M = R + g.clientWidth, ee = a.value.scrollLeft + v.clientWidth, h = a.value.scrollLeft + a.value.clientWidth - B.clientWidth;
      return R < ee ? R - ee : M > h ? M - h : 0;
    }
    function ne(g) {
      var M;
      if (!a.value || !o.value || !u.value)
        return;
      const v = g === "next" && n.value === "ltr" || g === "prev" && n.value === "rtl" ? 1 : -1;
      let B = 0, R = g === "next" ? a.value.firstElementChild : a.value.lastElementChild;
      for (; R; ) {
        const ee = g === "next" ? R.nextElementSibling : R.previousElementSibling;
        if (B = Q(R), Math.sign(B) === v) {
          ee && Math.abs(B) < 0.25 * a.value.clientWidth && (B = Q(ee));
          break;
        }
        R = ee;
      }
      a.value.scrollBy({
        left: B,
        behavior: "smooth"
      }), (M = i.value) == null || M.focus();
    }
    return te($, () => {
      if (I.value === void 0 || !a.value || !o.value || !u.value)
        return;
      const g = document.getElementById(`${I.value}-label`);
      !g || a.value.scrollBy({
        left: Q(g),
        behavior: "smooth"
      });
    }), {
      activeTab: $,
      activeTabIndex: S,
      activeTabId: I,
      currentDirection: n,
      rootElement: s,
      listElement: a,
      focusHolder: i,
      prevScroller: o,
      nextScroller: u,
      rootClasses: P,
      tabNames: y,
      tabsData: c,
      firstLabelVisible: z,
      lastLabelVisible: D,
      getLabelClasses: W,
      assignTemplateRefIfNecessary: K,
      scrollTabs: ne,
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
      const l = this.tabsData.get(this.tabNames[e + t]);
      l && (l.disabled ? this.selectNonDisabled(e + t, t) : this.select(l.name));
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
const zl = {
  ref: "focusHolder",
  tabindex: "-1"
}, jl = {
  ref: "prevScroller",
  class: "cdx-tabs__prev-scroller"
}, Hl = ["aria-activedescendant"], Ul = ["id"], Wl = ["href", "aria-selected", "onClick", "onKeyup"], Pl = {
  ref: "nextScroller",
  class: "cdx-tabs__next-scroller"
}, Ql = { class: "cdx-tabs__content" };
function Gl(e, t, l, s, a, i) {
  const o = A("cdx-icon"), u = A("cdx-button");
  return r(), b("div", {
    ref: "rootElement",
    class: N(["cdx-tabs", e.rootClasses])
  }, [
    m("div", {
      class: "cdx-tabs__header",
      tabindex: "0",
      onKeydown: [
        t[4] || (t[4] = Y(X((...n) => e.onRightArrowKeypress && e.onRightArrowKeypress(...n), ["prevent"]), ["right"])),
        t[5] || (t[5] = Y(X((...n) => e.onDownArrowKeypress && e.onDownArrowKeypress(...n), ["prevent"]), ["down"])),
        t[6] || (t[6] = Y(X((...n) => e.onLeftArrowKeypress && e.onLeftArrowKeypress(...n), ["prevent"]), ["left"]))
      ]
    }, [
      m("div", zl, null, 512),
      oe(m("div", jl, [
        O(u, {
          class: "cdx-tabs__scroll-button",
          type: "quiet",
          tabindex: "-1",
          "aria-hidden": !0,
          onMousedown: t[0] || (t[0] = X(() => {
          }, ["prevent"])),
          onClick: t[1] || (t[1] = (n) => e.scrollTabs("prev"))
        }, {
          default: T(() => [
            O(o, { icon: e.cdxIconPrevious }, null, 8, ["icon"])
          ]),
          _: 1
        })
      ], 512), [
        [ge, !e.firstLabelVisible]
      ]),
      m("ul", {
        ref: "listElement",
        class: "cdx-tabs__list",
        role: "tablist",
        "aria-activedescendant": e.activeTabId
      }, [
        (r(!0), b(ve, null, _e(e.tabsData.values(), (n, d) => (r(), b("li", {
          id: `${n.id}-label`,
          key: d,
          ref_for: !0,
          ref: (c) => e.assignTemplateRefIfNecessary(c, d),
          class: N([e.getLabelClasses(n.name), "cdx-tabs__list__item"]),
          role: "presentation"
        }, [
          m("a", {
            href: `#${n.id}`,
            role: "tab",
            tabIndex: "-1",
            "aria-selected": n.name === e.activeTab,
            onClick: X((c) => e.select(n.name), ["prevent"]),
            onKeyup: Y((c) => e.select(n.name), ["enter"])
          }, j(n.label), 41, Wl)
        ], 10, Ul))), 128))
      ], 8, Hl),
      oe(m("div", Pl, [
        O(u, {
          class: "cdx-tabs__scroll-button",
          type: "quiet",
          tabindex: "-1",
          "aria-hidden": !0,
          onMousedown: t[2] || (t[2] = X(() => {
          }, ["prevent"])),
          onClick: t[3] || (t[3] = (n) => e.scrollTabs("next"))
        }, {
          default: T(() => [
            O(o, { icon: e.cdxIconNext }, null, 8, ["icon"])
          ]),
          _: 1
        })
      ], 512), [
        [ge, !e.lastLabelVisible]
      ])
    ], 32),
    m("div", Ql, [
      w(e.$slots, "default")
    ])
  ], 2);
}
const Do = /* @__PURE__ */ V(Ol, [["render", Gl]]), Jl = F({
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
    const l = f(!1);
    return {
      rootClasses: p(() => ({
        "cdx-toggle-button--quiet": e.quiet,
        "cdx-toggle-button--framed": !e.quiet,
        "cdx-toggle-button--toggled-on": e.modelValue,
        "cdx-toggle-button--toggled-off": !e.modelValue,
        "cdx-toggle-button--is-active": l.value
      })),
      onClick: () => {
        t("update:modelValue", !e.modelValue);
      },
      setActive: (o) => {
        l.value = o;
      }
    };
  }
});
const Xl = ["aria-pressed", "disabled"];
function Yl(e, t, l, s, a, i) {
  return r(), b("button", {
    class: N(["cdx-toggle-button", e.rootClasses]),
    "aria-pressed": e.modelValue,
    disabled: e.disabled,
    onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o)),
    onKeydown: t[1] || (t[1] = Y((o) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = Y((o) => e.setActive(!1), ["space", "enter"]))
  }, [
    w(e.$slots, "default")
  ], 42, Xl);
}
const Zl = /* @__PURE__ */ V(Jl, [["render", Yl]]), eo = F({
  name: "CdxToggleButtonGroup",
  components: {
    CdxIcon: J,
    CdxToggleButton: Zl
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
    function l(a) {
      return Array.isArray(e.modelValue) ? e.modelValue.indexOf(a.value) !== -1 : e.modelValue !== null ? e.modelValue === a.value : !1;
    }
    function s(a, i) {
      if (Array.isArray(e.modelValue)) {
        const o = e.modelValue.indexOf(a.value) !== -1;
        i && !o ? t("update:modelValue", e.modelValue.concat(a.value)) : !i && o && t("update:modelValue", e.modelValue.filter((u) => u !== a.value));
      } else
        i && e.modelValue !== a.value && t("update:modelValue", a.value);
    }
    return {
      getButtonLabel: ot,
      isSelected: l,
      onUpdate: s
    };
  }
});
const to = { class: "cdx-toggle-button-group" };
function no(e, t, l, s, a, i) {
  const o = A("cdx-icon"), u = A("cdx-toggle-button");
  return r(), b("div", to, [
    (r(!0), b(ve, null, _e(e.buttons, (n) => (r(), E(u, {
      key: n.value,
      "model-value": e.isSelected(n),
      disabled: n.disabled || e.disabled,
      "aria-label": n.ariaLabel,
      "onUpdate:modelValue": (d) => e.onUpdate(n, d)
    }, {
      default: T(() => [
        w(e.$slots, "default", {
          button: n,
          selected: e.isSelected(n)
        }, () => [
          n.icon ? (r(), E(o, {
            key: 0,
            icon: n.icon
          }, null, 8, ["icon"])) : k("", !0),
          le(" " + j(e.getButtonLabel(n)), 1)
        ])
      ]),
      _: 2
    }, 1032, ["model-value", "disabled", "aria-label", "onUpdate:modelValue"]))), 128))
  ]);
}
const Eo = /* @__PURE__ */ V(eo, [["render", no]]), lo = F({
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
  setup(e, { attrs: t, emit: l }) {
    const s = f(), a = ae("toggle-switch"), {
      rootClasses: i,
      rootStyle: o,
      otherAttrs: u
    } = pe(t), n = se(G(e, "modelValue"), l);
    return {
      input: s,
      inputId: a,
      rootClasses: i,
      rootStyle: o,
      otherAttrs: u,
      wrappedModel: n,
      clickInput: () => {
        s.value.click();
      }
    };
  }
});
const oo = ["for"], ao = ["id", "disabled"], so = {
  key: 0,
  class: "cdx-toggle-switch__label-content"
}, uo = /* @__PURE__ */ m("span", { class: "cdx-toggle-switch__switch" }, [
  /* @__PURE__ */ m("span", { class: "cdx-toggle-switch__switch__grip" })
], -1);
function io(e, t, l, s, a, i) {
  return r(), b("span", {
    class: N(["cdx-toggle-switch", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    m("label", {
      for: e.inputId,
      class: "cdx-toggle-switch__label"
    }, [
      oe(m("input", Z({
        id: e.inputId,
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-toggle-switch__input",
        type: "checkbox",
        disabled: e.disabled
      }, e.otherAttrs, {
        onKeydown: t[1] || (t[1] = Y(X((...o) => e.clickInput && e.clickInput(...o), ["prevent"]), ["enter"]))
      }), null, 16, ao), [
        [Ye, e.wrappedModel]
      ]),
      e.$slots.default ? (r(), b("span", so, [
        w(e.$slots, "default")
      ])) : k("", !0),
      uo
    ], 8, oo)
  ], 6);
}
const To = /* @__PURE__ */ V(lo, [["render", io]]), ro = F({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: J,
    CdxMenu: Ae,
    CdxSearchInput: Tl
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
      default: It
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
  setup(e, { attrs: t, emit: l, slots: s }) {
    const { searchResults: a, searchFooterUrl: i, debounceInterval: o } = yt(e), u = f(), n = f(), d = ae("typeahead-search-menu"), c = f(!1), $ = f(!1), y = f(!1), S = f(!1), I = f(e.initialInputValue), L = f(""), C = p(() => {
      var _, U;
      return (U = (_ = n.value) == null ? void 0 : _.getHighlightedMenuItem()) == null ? void 0 : U.id;
    }), z = f(null), D = p(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), K = p(
      () => e.searchResults.find(
        (_) => _.value === z.value
      )
    ), W = p(
      () => i.value ? { value: me, url: i.value } : void 0
    ), P = p(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": c.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: Q,
      rootStyle: ne,
      otherAttrs: g
    } = pe(t, P);
    function v(_) {
      return _;
    }
    const B = p(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let R, M;
    function ee(_, U = !1) {
      K.value && K.value.label !== _ && K.value.value !== _ && (z.value = null), M !== void 0 && (clearTimeout(M), M = void 0), _ === "" ? c.value = !1 : ($.value = !0, s["search-results-pending"] && (M = setTimeout(() => {
        S.value && (c.value = !0), y.value = !0;
      }, Bt))), R !== void 0 && (clearTimeout(R), R = void 0);
      const ce = () => {
        l("input", _);
      };
      U ? ce() : R = setTimeout(() => {
        ce();
      }, o.value);
    }
    function h(_) {
      if (_ === me) {
        z.value = null, I.value = L.value;
        return;
      }
      z.value = _, _ !== null && (I.value = K.value ? K.value.label || String(K.value.value) : "");
    }
    function x() {
      S.value = !0, (L.value || y.value) && (c.value = !0);
    }
    function q() {
      S.value = !1, c.value = !1;
    }
    function H(_) {
      const Ke = _, { id: U } = Ke, ce = Ce(Ke, ["id"]);
      if (ce.value === me) {
        l("search-result-click", {
          searchResult: null,
          index: a.value.length,
          numberOfResults: a.value.length
        });
        return;
      }
      fe(ce);
    }
    function fe(_) {
      const U = {
        searchResult: _,
        index: a.value.findIndex(
          (ce) => ce.value === _.value
        ),
        numberOfResults: a.value.length
      };
      l("search-result-click", U);
    }
    function ue(_) {
      if (_.value === me) {
        I.value = L.value;
        return;
      }
      I.value = _.value ? _.label || String(_.value) : "";
    }
    function it(_) {
      var U;
      c.value = !1, (U = n.value) == null || U.clearActive(), H(_);
    }
    function dt(_) {
      if (K.value)
        fe(K.value), _.stopPropagation(), window.location.assign(K.value.url), _.preventDefault();
      else {
        const U = {
          searchResult: null,
          index: -1,
          numberOfResults: a.value.length
        };
        l("submit", U);
      }
    }
    function rt(_) {
      if (!n.value || !L.value || _.key === " " && c.value)
        return;
      const U = n.value.getHighlightedMenuItem();
      switch (_.key) {
        case "Enter":
          U && (U.value === me ? window.location.assign(i.value) : n.value.delegateKeyNavigation(_, !1)), c.value = !1;
          break;
        case "Tab":
          c.value = !1;
          break;
        default:
          n.value.delegateKeyNavigation(_);
          break;
      }
    }
    return ie(() => {
      e.initialInputValue && ee(e.initialInputValue, !0);
    }), te(G(e, "searchResults"), () => {
      L.value = I.value.trim(), S.value && $.value && L.value.length > 0 && (c.value = !0), M !== void 0 && (clearTimeout(M), M = void 0), $.value = !1, y.value = !1;
    }), {
      form: u,
      menu: n,
      menuId: d,
      highlightedId: C,
      selection: z,
      menuMessageClass: D,
      footer: W,
      asSearchResult: v,
      inputValue: I,
      searchQuery: L,
      expanded: c,
      showPending: y,
      rootClasses: Q,
      rootStyle: ne,
      otherAttrs: g,
      menuConfig: B,
      onUpdateInputValue: ee,
      onUpdateMenuSelection: h,
      onFocus: x,
      onBlur: q,
      onSearchResultClick: H,
      onSearchResultKeyboardNavigation: ue,
      onSearchFooterClick: it,
      onSubmit: dt,
      onKeydown: rt,
      MenuFooterValue: me,
      articleIcon: qt
    };
  },
  methods: {
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const co = ["id", "action"], po = { class: "cdx-typeahead-search__menu-message__text" }, fo = { class: "cdx-typeahead-search__menu-message__text" }, mo = ["href", "onClickCapture"], vo = { class: "cdx-typeahead-search__search-footer__text" }, ho = { class: "cdx-typeahead-search__search-footer__query" };
function bo(e, t, l, s, a, i) {
  const o = A("cdx-icon"), u = A("cdx-menu"), n = A("cdx-search-input");
  return r(), b("div", {
    class: N(["cdx-typeahead-search", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    m("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...d) => e.onSubmit && e.onSubmit(...d))
    }, [
      O(n, Z({
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
        default: T(() => [
          O(u, Z({
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
            pending: T(() => [
              m("div", {
                class: N(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                m("span", po, [
                  w(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": T(() => [
              m("div", {
                class: N(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                m("span", fo, [
                  w(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: T(({ menuItem: d, active: c }) => [
              d.value === e.MenuFooterValue ? (r(), b("a", {
                key: 0,
                class: N(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": c
                }]),
                href: e.asSearchResult(d).url,
                onClickCapture: X(($) => e.onSearchFooterClick(e.asSearchResult(d)), ["stop"])
              }, [
                O(o, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                m("span", vo, [
                  w(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    m("strong", ho, j(e.searchQuery), 1)
                  ])
                ])
              ], 42, mo)) : k("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      w(e.$slots, "default")
    ], 40, co)
  ], 6);
}
const Lo = /* @__PURE__ */ V(ro, [["render", bo]]);
export {
  he as CdxButton,
  Co as CdxButtonGroup,
  _o as CdxCard,
  $o as CdxCheckbox,
  Io as CdxCombobox,
  Bo as CdxDialog,
  J as CdxIcon,
  xo as CdxLookup,
  Ae as CdxMenu,
  jn as CdxMenuItem,
  ko as CdxMessage,
  Gn as CdxProgressBar,
  wo as CdxRadio,
  Tl as CdxSearchInput,
  Ln as CdxSearchResultTitle,
  So as CdxSelect,
  Mo as CdxTab,
  Do as CdxTabs,
  Ee as CdxTextInput,
  at as CdxThumbnail,
  Zl as CdxToggleButton,
  Eo as CdxToggleButtonGroup,
  To as CdxToggleSwitch,
  Lo as CdxTypeaheadSearch,
  Ao as stringHelpers,
  lt as useComputedDirection,
  Xt as useComputedLanguage,
  ae as useGeneratedId,
  we as useIntersectionObserver,
  se as useModelWrapper,
  Te as useResizeObserver,
  pe as useSplitAttributes
};
