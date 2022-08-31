var Je = Object.defineProperty, Ye = Object.defineProperties;
var Ze = Object.getOwnPropertyDescriptors;
var ce = Object.getOwnPropertySymbols;
var Be = Object.prototype.hasOwnProperty, xe = Object.prototype.propertyIsEnumerable;
var Ae = (e, t, n) => t in e ? Je(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, Ie = (e, t) => {
  for (var n in t || (t = {}))
    Be.call(t, n) && Ae(e, n, t[n]);
  if (ce)
    for (var n of ce(t))
      xe.call(t, n) && Ae(e, n, t[n]);
  return e;
}, ke = (e, t) => Ye(e, Ze(t));
var pe = (e, t) => {
  var n = {};
  for (var a in e)
    Be.call(e, a) && t.indexOf(a) < 0 && (n[a] = e[a]);
  if (e != null && ce)
    for (var a of ce(e))
      t.indexOf(a) < 0 && xe.call(e, a) && (n[a] = e[a]);
  return n;
};
import { ref as m, onMounted as le, defineComponent as M, computed as c, openBlock as r, createElementBlock as h, normalizeClass as T, toDisplayString as j, createCommentVNode as S, Comment as et, warn as tt, renderSlot as A, resolveComponent as _, Fragment as ie, renderList as he, createBlock as F, withCtx as L, createTextVNode as te, createVNode as N, Transition as De, normalizeStyle as ae, resolveDynamicComponent as Ee, createElementVNode as v, toRef as H, withKeys as ee, withModifiers as W, withDirectives as J, vModelCheckbox as Fe, getCurrentInstance as nt, onUnmounted as Te, watch as ne, mergeProps as Q, vShow as fe, vModelDynamic as lt, vModelRadio as at, inject as we, provide as Se, toRefs as st } from "vue";
const be = "cdx", ot = [
  "default",
  "progressive",
  "destructive"
], ut = [
  "normal",
  "primary",
  "quiet"
], it = [
  "notice",
  "warning",
  "error",
  "success"
], dt = [
  "text",
  "search"
], rt = 120, ct = 500, oe = "cdx-menu-footer-item", Le = Symbol("CdxTabs"), Ve = Symbol("CdxActiveTab"), pt = '<path d="M11.53 2.3A1.85 1.85 0 0010 1.21 1.85 1.85 0 008.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z"/>', ft = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', ht = '<path d="M7 14.17 2.83 10l-1.41 1.41L7 17 19 5l-1.41-1.42z"/>', mt = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', vt = '<path d="m4.34 2.93 12.73 12.73-1.41 1.41L2.93 4.35z"/><path d="M17.07 4.34 4.34 17.07l-1.41-1.41L15.66 2.93z"/>', bt = '<path d="M13.728 1H6.272L1 6.272v7.456L6.272 19h7.456L19 13.728V6.272zM11 15H9v-2h2zm0-4H9V5h2z"/>', gt = '<path d="m17.5 4.75-7.5 7.5-7.5-7.5L1 6.25l9 9 9-9z"/>', yt = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Ct = '<path d="M8 19a1 1 0 001 1h2a1 1 0 001-1v-1H8zm9-12a7 7 0 10-12 4.9S7 14 7 15v1a1 1 0 001 1h4a1 1 0 001-1v-1c0-1 2-3.1 2-3.1A7 7 0 0017 7z"/>', _t = '<path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zM9 5h2v2H9zm0 4h2v6H9z"/>', $t = '<path d="M7 1 5.6 2.5 13 10l-7.4 7.5L7 19l9-9z"/>', At = '<path d="m4 10 9 9 1.4-1.5L7 10l7.4-7.5L13 1z"/>', Bt = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4-5.4-5.4zM3 8a5 5 0 1010 0A5 5 0 103 8z"/>', xt = pt, It = ft, kt = ht, wt = mt, St = vt, Mt = bt, Ke = gt, Dt = yt, Et = {
  langCodeMap: {
    ar: Ct
  },
  default: _t
}, Ft = {
  ltr: $t,
  shouldFlip: !0
}, Tt = {
  ltr: At,
  shouldFlip: !0
}, Lt = Bt;
function Vt(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const a = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof a == "string" || "path" in a ? a : a.ltr;
}
function Kt(e, t) {
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
function Ne(e) {
  const t = m(null);
  return le(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function Nt(e) {
  const t = m("");
  return le(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
const Rt = M({
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
    const n = m(), a = Ne(n), o = Nt(n), u = c(() => e.dir || a.value), s = c(() => e.lang || o.value), i = c(() => ({
      "cdx-icon--flipped": u.value === "rtl" && s.value !== null && Kt(e.icon, s.value)
    })), l = c(
      () => Vt(e.icon, s.value || "", u.value || "ltr")
    ), d = c(() => typeof l.value == "string" ? l.value : ""), p = c(() => typeof l.value != "string" ? l.value.path : "");
    return {
      rootElement: n,
      rootClasses: i,
      iconSvg: d,
      iconPath: p,
      onClick: ($) => {
        t("click", $);
      }
    };
  }
});
const D = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [a, o] of t)
    n[a] = o;
  return n;
}, qt = ["aria-hidden"], Ot = { key: 0 }, Ut = ["innerHTML"], zt = ["d"];
function jt(e, t, n, a, o, u) {
  return r(), h("span", {
    ref: "rootElement",
    class: T(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...s) => e.onClick && e.onClick(...s))
  }, [
    (r(), h("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": !e.iconLabel
    }, [
      e.iconLabel ? (r(), h("title", Ot, j(e.iconLabel), 1)) : S("", !0),
      e.iconSvg ? (r(), h("g", {
        key: 1,
        fill: "currentColor",
        innerHTML: e.iconSvg
      }, null, 8, Ut)) : (r(), h("path", {
        key: 2,
        d: e.iconPath,
        fill: "currentColor"
      }, null, 8, zt))
    ], 8, qt))
  ], 2);
}
const P = /* @__PURE__ */ D(Rt, [["render", jt]]);
function me(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const Ht = me(ut), Pt = me(ot), Wt = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && tt(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/main/components/button.html#default-icon-only`);
};
function ye(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...ye(n)) : typeof n == "object" && n && (typeof n.type == "string" || typeof n.type == "object" ? t.push(n) : n.type !== et && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...ye(n.children))));
  return t;
}
const Qt = (e, t) => {
  if (!e)
    return !1;
  const n = ye(e);
  if (n.length !== 1)
    return !1;
  const a = n[0], o = typeof a == "object" && typeof a.type == "object" && "name" in a.type && a.type.name === P.name, u = typeof a == "object" && a.type === "svg";
  return o || u ? (Wt(t), !0) : !1;
}, Gt = M({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: Pt
    },
    type: {
      type: String,
      default: "normal",
      validator: Ht
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: a }) {
    return {
      rootClasses: c(() => {
        var s;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--type-${e.type}`]: !0,
          "cdx-button--framed": e.type !== "quiet",
          "cdx-button--icon-only": Qt((s = n.default) == null ? void 0 : s.call(n), a)
        };
      }),
      onClick: (s) => {
        t("click", s);
      }
    };
  }
});
function Xt(e, t, n, a, o, u) {
  return r(), h("button", {
    class: T(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...s) => e.onClick && e.onClick(...s))
  }, [
    A(e.$slots, "default")
  ], 2);
}
const de = /* @__PURE__ */ D(Gt, [["render", Xt]]);
function Re(e) {
  return e.label === void 0 ? e.value : e.label === null ? "" : e.label;
}
const Jt = M({
  name: "CdxButtonGroup",
  components: {
    CdxButton: de,
    CdxIcon: P
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
      getButtonLabel: Re
    };
  }
});
const Yt = { class: "cdx-button-group" };
function Zt(e, t, n, a, o, u) {
  const s = _("cdx-icon"), i = _("cdx-button");
  return r(), h("div", Yt, [
    (r(!0), h(ie, null, he(e.buttons, (l) => (r(), F(i, {
      key: l.value,
      disabled: l.disabled || e.disabled,
      "aria-label": l.ariaLabel,
      onClick: (d) => e.$emit("click", l.value)
    }, {
      default: L(() => [
        A(e.$slots, "default", { button: l }, () => [
          l.icon ? (r(), F(s, {
            key: 0,
            icon: l.icon
          }, null, 8, ["icon"])) : S("", !0),
          te(" " + j(e.getButtonLabel(l)), 1)
        ])
      ]),
      _: 2
    }, 1032, ["disabled", "aria-label", "onClick"]))), 128))
  ]);
}
const Yl = /* @__PURE__ */ D(Jt, [["render", Zt]]), en = M({
  name: "CdxThumbnail",
  components: { CdxIcon: P },
  props: {
    thumbnail: {
      type: [Object, null],
      default: null
    },
    placeholderIcon: {
      type: [String, Object],
      default: Dt
    }
  },
  setup: (e) => {
    const t = m(!1), n = m({}), a = (o) => {
      const u = o.replace(/([\\"\n])/g, "\\$1"), s = new Image();
      s.onload = () => {
        n.value = { backgroundImage: `url("${u}")` }, t.value = !0;
      }, s.onerror = () => {
        t.value = !1;
      }, s.src = u;
    };
    return le(() => {
      var o;
      (o = e.thumbnail) != null && o.url && a(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const tn = { class: "cdx-thumbnail" }, nn = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function ln(e, t, n, a, o, u) {
  const s = _("cdx-icon");
  return r(), h("span", tn, [
    e.thumbnailLoaded ? S("", !0) : (r(), h("span", nn, [
      N(s, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])),
    N(De, { name: "cdx-thumbnail__image" }, {
      default: L(() => [
        e.thumbnailLoaded ? (r(), h("span", {
          key: 0,
          style: ae(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : S("", !0)
      ]),
      _: 1
    })
  ]);
}
const qe = /* @__PURE__ */ D(en, [["render", ln]]), an = M({
  name: "CdxCard",
  components: { CdxIcon: P, CdxThumbnail: qe },
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
    const t = c(() => !!e.url), n = c(() => t.value ? "a" : "span"), a = c(() => t.value ? e.url : void 0);
    return {
      isLink: t,
      contentTag: n,
      cardLink: a
    };
  }
});
const sn = { class: "cdx-card__text" }, on = { class: "cdx-card__text__title" }, un = {
  key: 0,
  class: "cdx-card__text__description"
}, dn = {
  key: 1,
  class: "cdx-card__text__supporting-text"
};
function rn(e, t, n, a, o, u) {
  const s = _("cdx-thumbnail"), i = _("cdx-icon");
  return r(), F(Ee(e.contentTag), {
    href: e.cardLink,
    class: T(["cdx-card", {
      "cdx-card--is-link": e.isLink,
      "cdx-card--title-only": !e.$slots.description && !e.$slots["supporting-text"]
    }])
  }, {
    default: L(() => [
      e.thumbnail || e.forceThumbnail ? (r(), F(s, {
        key: 0,
        thumbnail: e.thumbnail,
        "placeholder-icon": e.customPlaceholderIcon,
        class: "cdx-card__thumbnail"
      }, null, 8, ["thumbnail", "placeholder-icon"])) : e.icon ? (r(), F(i, {
        key: 1,
        icon: e.icon,
        class: "cdx-card__icon"
      }, null, 8, ["icon"])) : S("", !0),
      v("span", sn, [
        v("span", on, [
          A(e.$slots, "title")
        ]),
        e.$slots.description ? (r(), h("span", un, [
          A(e.$slots, "description")
        ])) : S("", !0),
        e.$slots["supporting-text"] ? (r(), h("span", dn, [
          A(e.$slots, "supporting-text")
        ])) : S("", !0)
      ])
    ]),
    _: 3
  }, 8, ["href", "class"]);
}
const Zl = /* @__PURE__ */ D(an, [["render", rn]]);
function X(e, t, n) {
  return c({
    get: () => e.value,
    set: (a) => t(n || "update:modelValue", a)
  });
}
const cn = M({
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
    const n = c(() => ({
      "cdx-checkbox--inline": e.inline
    })), a = m(), o = m(), u = () => {
      a.value.focus();
    }, s = () => {
      o.value.click();
    }, i = X(H(e, "modelValue"), t);
    return {
      rootClasses: n,
      input: a,
      label: o,
      focusInput: u,
      clickLabel: s,
      wrappedModel: i
    };
  }
});
const pn = ["value", "disabled", ".indeterminate"], fn = /* @__PURE__ */ v("span", { class: "cdx-checkbox__icon" }, null, -1), hn = { class: "cdx-checkbox__label-content" };
function mn(e, t, n, a, o, u) {
  return r(), h("span", {
    class: T(["cdx-checkbox", e.rootClasses])
  }, [
    v("label", {
      ref: "label",
      class: "cdx-checkbox__label",
      onClick: t[1] || (t[1] = (...s) => e.focusInput && e.focusInput(...s)),
      onKeydown: t[2] || (t[2] = ee(W((...s) => e.clickLabel && e.clickLabel(...s), ["prevent"]), ["enter"]))
    }, [
      J(v("input", {
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (s) => e.wrappedModel = s),
        class: "cdx-checkbox__input",
        type: "checkbox",
        value: e.inputValue,
        disabled: e.disabled,
        ".indeterminate": e.indeterminate
      }, null, 8, pn), [
        [Fe, e.wrappedModel]
      ]),
      fn,
      v("span", hn, [
        A(e.$slots, "default")
      ])
    ], 544)
  ], 2);
}
const ea = /* @__PURE__ */ D(cn, [["render", mn]]);
function Oe(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const vn = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function Ue(e, t) {
  if (!e)
    return [t, "", ""];
  const n = Oe(e), a = new RegExp(
    n + vn + "*",
    "i"
  ).exec(t);
  if (!a || a.index === void 0)
    return [t, "", ""];
  const o = a.index, u = o + a[0].length, s = t.slice(o, u), i = t.slice(0, o), l = t.slice(u, t.length);
  return [i, s, l];
}
const ta = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  regExpEscape: Oe,
  splitStringAtMatch: Ue
}, Symbol.toStringTag, { value: "Module" })), bn = M({
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
    titleChunks: c(() => Ue(e.searchQuery, String(e.title)))
  })
});
const gn = { class: "cdx-search-result-title" }, yn = { class: "cdx-search-result-title__match" };
function Cn(e, t, n, a, o, u) {
  return r(), h("span", gn, [
    v("bdi", null, [
      te(j(e.titleChunks[0]), 1),
      v("span", yn, j(e.titleChunks[1]), 1),
      te(j(e.titleChunks[2]), 1)
    ])
  ]);
}
const _n = /* @__PURE__ */ D(bn, [["render", Cn]]), $n = M({
  name: "CdxMenuItem",
  components: { CdxIcon: P, CdxThumbnail: qe, CdxSearchResultTitle: _n },
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
    }, o = (p) => {
      p.button === 0 && t("change", "active", !0);
    }, u = () => {
      t("change", "selected", !0);
    }, s = c(() => e.searchQuery.length > 0), i = c(() => ({
      "cdx-menu-item--selected": e.selected,
      "cdx-menu-item--active": e.active && e.highlighted,
      "cdx-menu-item--highlighted": e.highlighted,
      "cdx-menu-item--enabled": !e.disabled,
      "cdx-menu-item--disabled": e.disabled,
      "cdx-menu-item--highlight-query": s.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), l = c(() => e.url ? "a" : "span"), d = c(() => e.label || String(e.value));
    return {
      onMouseEnter: n,
      onMouseLeave: a,
      onMouseDown: o,
      onClick: u,
      highlightQuery: s,
      rootClasses: i,
      contentTag: l,
      title: d
    };
  }
});
const An = ["id", "aria-disabled", "aria-selected"], Bn = { class: "cdx-menu-item__text" }, xn = ["lang"], In = /* @__PURE__ */ te(/* @__PURE__ */ j(" ") + " "), kn = ["lang"], wn = ["lang"];
function Sn(e, t, n, a, o, u) {
  const s = _("cdx-thumbnail"), i = _("cdx-icon"), l = _("cdx-search-result-title");
  return r(), h("li", {
    id: e.id,
    role: "option",
    class: T(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMouseenter: t[0] || (t[0] = (...d) => e.onMouseEnter && e.onMouseEnter(...d)),
    onMouseleave: t[1] || (t[1] = (...d) => e.onMouseLeave && e.onMouseLeave(...d)),
    onMousedown: t[2] || (t[2] = W((...d) => e.onMouseDown && e.onMouseDown(...d), ["prevent"])),
    onClick: t[3] || (t[3] = (...d) => e.onClick && e.onClick(...d))
  }, [
    A(e.$slots, "default", {}, () => [
      (r(), F(Ee(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: L(() => {
          var d, p, g, $, k;
          return [
            e.showThumbnail ? (r(), F(s, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (r(), F(i, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : S("", !0),
            v("span", Bn, [
              e.highlightQuery ? (r(), F(l, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (d = e.language) == null ? void 0 : d.label
              }, null, 8, ["title", "search-query", "lang"])) : (r(), h("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (p = e.language) == null ? void 0 : p.label
              }, [
                v("bdi", null, j(e.title), 1)
              ], 8, xn)),
              e.match ? (r(), h(ie, { key: 2 }, [
                In,
                e.highlightQuery ? (r(), F(l, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: (g = e.language) == null ? void 0 : g.match
                }, null, 8, ["title", "search-query", "lang"])) : (r(), h("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: ($ = e.language) == null ? void 0 : $.match
                }, [
                  v("bdi", null, j(e.match), 1)
                ], 8, kn))
              ], 64)) : S("", !0),
              e.description ? (r(), h("span", {
                key: 3,
                class: "cdx-menu-item__text__description",
                lang: (k = e.language) == null ? void 0 : k.description
              }, [
                v("bdi", null, j(e.description), 1)
              ], 8, wn)) : S("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, An);
}
const Mn = /* @__PURE__ */ D($n, [["render", Sn]]), Dn = M({
  name: "CdxProgressBar",
  props: {
    inline: {
      type: Boolean,
      default: !1
    }
  },
  setup(e) {
    return {
      rootClasses: c(() => ({
        "cdx-progress-bar--block": !e.inline,
        "cdx-progress-bar--inline": e.inline
      }))
    };
  }
});
const En = /* @__PURE__ */ v("div", { class: "cdx-progress-bar__bar" }, null, -1), Fn = [
  En
];
function Tn(e, t, n, a, o, u) {
  return r(), h("div", {
    class: T(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Fn, 2);
}
const Ln = /* @__PURE__ */ D(Dn, [["render", Tn]]);
let ge = 0;
function Y(e) {
  const t = nt(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${be}-${e}-${ge++}` : n ? `${be}-${n}-${ge++}` : `${be}-${ge++}`;
}
const Vn = M({
  name: "CdxMenu",
  components: {
    CdxMenuItem: Mn,
    CdxProgressBar: Ln
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
    const a = c(() => e.menuItems.map((f) => ke(Ie({}, f), {
      id: Y("menu-item")
    }))), o = c(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : a.value.length === 0 : !1), u = m(null), s = m(null);
    function i() {
      return a.value.find(
        (f) => f.value === e.selected
      );
    }
    function l(f, b) {
      var C;
      if (!(b && b.disabled))
        switch (f) {
          case "selected":
            t("update:selected", (C = b == null ? void 0 : b.value) != null ? C : null), t("update:expanded", !1), s.value = null;
            break;
          case "highlighted":
            u.value = b || null;
            break;
          case "active":
            s.value = b || null;
            break;
        }
    }
    const d = c(() => {
      if (u.value !== null)
        return a.value.findIndex(
          (f) => f.value === u.value.value
        );
    });
    function p(f) {
      !f || (l("highlighted", f), t("menu-item-keyboard-navigation", f));
    }
    function g(f) {
      var R;
      const b = (q) => {
        for (let O = q - 1; O >= 0; O--)
          if (!a.value[O].disabled)
            return a.value[O];
      };
      f = f || a.value.length;
      const C = (R = b(f)) != null ? R : b(a.value.length);
      p(C);
    }
    function $(f) {
      const b = (R) => a.value.find((q, O) => !q.disabled && O > R);
      f = f != null ? f : -1;
      const C = b(f) || b(-1);
      p(C);
    }
    function k(f, b = !0) {
      function C() {
        t("update:expanded", !0), l("highlighted", i());
      }
      function R() {
        b && (f.preventDefault(), f.stopPropagation());
      }
      switch (f.key) {
        case "Enter":
        case " ":
          return R(), e.expanded ? (u.value && t("update:selected", u.value.value), t("update:expanded", !1)) : C(), !0;
        case "Tab":
          return e.expanded && (u.value && t("update:selected", u.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return R(), e.expanded ? (u.value === null && l("highlighted", i()), g(d.value)) : C(), !0;
        case "ArrowDown":
          return R(), e.expanded ? (u.value === null && l("highlighted", i()), $(d.value)) : C(), !0;
        case "Home":
          return R(), e.expanded ? (u.value === null && l("highlighted", i()), $()) : C(), !0;
        case "End":
          return R(), e.expanded ? (u.value === null && l("highlighted", i()), g()) : C(), !0;
        case "Escape":
          return R(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function V() {
      l("active");
    }
    return le(() => {
      document.addEventListener("mouseup", V);
    }), Te(() => {
      document.removeEventListener("mouseup", V);
    }), ne(H(e, "expanded"), (f) => {
      const b = i();
      !f && u.value && b === void 0 && l("highlighted"), f && b !== void 0 && l("highlighted", b);
    }), {
      computedMenuItems: a,
      computedShowNoResultsSlot: o,
      highlightedMenuItem: u,
      activeMenuItem: s,
      handleMenuItemChange: l,
      handleKeyNavigation: k
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
const Kn = {
  class: "cdx-menu",
  role: "listbox",
  "aria-multiselectable": "false"
}, Nn = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, Rn = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function qn(e, t, n, a, o, u) {
  const s = _("cdx-menu-item"), i = _("cdx-progress-bar");
  return J((r(), h("ul", Kn, [
    e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (r(), h("li", Nn, [
      A(e.$slots, "pending")
    ])) : S("", !0),
    e.computedShowNoResultsSlot ? (r(), h("li", Rn, [
      A(e.$slots, "no-results")
    ])) : S("", !0),
    (r(!0), h(ie, null, he(e.computedMenuItems, (l) => {
      var d, p;
      return r(), F(s, Q({
        key: l.value
      }, l, {
        selected: l.value === e.selected,
        active: l.value === ((d = e.activeMenuItem) == null ? void 0 : d.value),
        highlighted: l.value === ((p = e.highlightedMenuItem) == null ? void 0 : p.value),
        "show-thumbnail": e.showThumbnail,
        "bold-label": e.boldLabel,
        "hide-description-overflow": e.hideDescriptionOverflow,
        "search-query": e.searchQuery,
        onChange: (g, $) => e.handleMenuItemChange(g, $ && l),
        onClick: (g) => e.$emit("menu-item-click", l)
      }), {
        default: L(() => {
          var g, $;
          return [
            A(e.$slots, "default", {
              menuItem: l,
              active: l.value === ((g = e.activeMenuItem) == null ? void 0 : g.value) && l.value === (($ = e.highlightedMenuItem) == null ? void 0 : $.value)
            })
          ];
        }),
        _: 2
      }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
    }), 128)),
    e.showPending ? (r(), F(i, {
      key: 2,
      class: "cdx-menu__progress-bar",
      inline: !0
    })) : S("", !0)
  ], 512)), [
    [fe, e.expanded]
  ]);
}
const ve = /* @__PURE__ */ D(Vn, [["render", qn]]);
function ue(e, t = c(() => ({}))) {
  const n = c(() => {
    const u = pe(t.value, []);
    return e.class && e.class.split(" ").forEach((i) => {
      u[i] = !0;
    }), u;
  }), a = c(() => {
    if ("style" in e)
      return e.style;
  }), o = c(() => {
    const l = e, { class: u, style: s } = l;
    return pe(l, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: a,
    otherAttrs: o
  };
}
const On = me(dt), Un = M({
  name: "CdxTextInput",
  components: { CdxIcon: P },
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
      validator: On
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
    const a = X(H(e, "modelValue"), t), o = c(() => e.clearable && !!a.value && !e.disabled), u = c(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": o.value
    })), {
      rootClasses: s,
      rootStyle: i,
      otherAttrs: l
    } = ue(n, u), d = c(() => ({
      "cdx-text-input__input--has-value": !!a.value
    }));
    return {
      wrappedModel: a,
      isClearable: o,
      rootClasses: s,
      rootStyle: i,
      otherAttrs: l,
      inputClasses: d,
      onClear: () => {
        a.value = "";
      },
      onInput: (f) => {
        t("input", f);
      },
      onChange: (f) => {
        t("change", f);
      },
      onFocus: (f) => {
        t("focus", f);
      },
      onBlur: (f) => {
        t("blur", f);
      },
      cdxIconClear: wt
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    }
  }
});
const zn = ["type", "disabled"];
function jn(e, t, n, a, o, u) {
  const s = _("cdx-icon");
  return r(), h("div", {
    class: T(["cdx-text-input", e.rootClasses]),
    style: ae(e.rootStyle)
  }, [
    J(v("input", Q({
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (i) => e.wrappedModel = i),
      class: ["cdx-text-input__input", e.inputClasses]
    }, e.otherAttrs, {
      type: e.inputType,
      disabled: e.disabled,
      onInput: t[1] || (t[1] = (...i) => e.onInput && e.onInput(...i)),
      onChange: t[2] || (t[2] = (...i) => e.onChange && e.onChange(...i)),
      onFocus: t[3] || (t[3] = (...i) => e.onFocus && e.onFocus(...i)),
      onBlur: t[4] || (t[4] = (...i) => e.onBlur && e.onBlur(...i))
    }), null, 16, zn), [
      [lt, e.wrappedModel]
    ]),
    e.startIcon ? (r(), F(s, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : S("", !0),
    e.endIcon ? (r(), F(s, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : S("", !0),
    e.isClearable ? (r(), F(s, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon cdx-text-input__clear-icon",
      onMousedown: t[5] || (t[5] = W(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : S("", !0)
  ], 6);
}
const Ce = /* @__PURE__ */ D(Un, [["render", jn]]), Hn = M({
  name: "CdxCombobox",
  components: {
    CdxButton: de,
    CdxIcon: P,
    CdxMenu: ve,
    CdxTextInput: Ce
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
    "update:selected"
  ],
  setup(e, { emit: t, attrs: n, slots: a }) {
    const o = m(), u = m(), s = Y("combobox"), i = H(e, "selected"), l = X(i, t, "update:selected"), d = m(!1), p = m(!1), g = c(() => {
      var U, x;
      return (x = (U = u.value) == null ? void 0 : U.getHighlightedMenuItem()) == null ? void 0 : x.id;
    }), $ = c(() => ({
      "cdx-combobox--disabled": e.disabled
    })), {
      rootClasses: k,
      rootStyle: V,
      otherAttrs: f
    } = ue(n, $);
    function b() {
      p.value && d.value ? d.value = !1 : (e.menuItems.length > 0 || a["no-results"]) && (d.value = !0);
    }
    function C() {
      d.value = p.value && d.value;
    }
    function R() {
      e.disabled || (p.value = !0);
    }
    function q() {
      var U;
      e.disabled || (U = o.value) == null || U.focus();
    }
    function O(U) {
      !u.value || e.disabled || e.menuItems.length === 0 || U.key === " " && d.value || u.value.delegateKeyNavigation(U);
    }
    return ne(d, () => {
      p.value = !1;
    }), {
      input: o,
      menu: u,
      menuId: s,
      modelWrapper: l,
      expanded: d,
      highlightedId: g,
      onInputFocus: b,
      onInputBlur: C,
      onKeydown: O,
      onButtonClick: q,
      onButtonMousedown: R,
      cdxIconExpand: Ke,
      rootClasses: k,
      rootStyle: V,
      otherAttrs: f
    };
  }
});
const Pn = { class: "cdx-combobox__input-wrapper" };
function Wn(e, t, n, a, o, u) {
  const s = _("cdx-text-input"), i = _("cdx-icon"), l = _("cdx-button"), d = _("cdx-menu");
  return r(), h("div", {
    class: T(["cdx-combobox", e.rootClasses]),
    style: ae(e.rootStyle)
  }, [
    v("div", Pn, [
      N(s, Q({
        ref: "input",
        modelValue: e.modelWrapper,
        "onUpdate:modelValue": t[0] || (t[0] = (p) => e.modelWrapper = p)
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
      N(l, {
        class: "cdx-combobox__expand-button",
        "aria-hidden": "true",
        disabled: e.disabled,
        tabindex: "-1",
        onMousedown: e.onButtonMousedown,
        onClick: e.onButtonClick
      }, {
        default: L(() => [
          N(i, {
            class: "cdx-combobox__expand-icon",
            icon: e.cdxIconExpand
          }, null, 8, ["icon"])
        ]),
        _: 1
      }, 8, ["disabled", "onMousedown", "onClick"])
    ]),
    N(d, Q({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[1] || (t[1] = (p) => e.modelWrapper = p),
      expanded: e.expanded,
      "onUpdate:expanded": t[2] || (t[2] = (p) => e.expanded = p),
      "menu-items": e.menuItems
    }, e.menuConfig), {
      default: L(({ menuItem: p }) => [
        A(e.$slots, "menu-item", { menuItem: p })
      ]),
      "no-results": L(() => [
        A(e.$slots, "no-results")
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 6);
}
const na = /* @__PURE__ */ D(Hn, [["render", Wn]]), Qn = M({
  name: "CdxLookup",
  components: {
    CdxMenu: ve,
    CdxTextInput: Ce
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
    "input"
  ],
  setup: (e, { emit: t, attrs: n, slots: a }) => {
    const o = m(), u = Y("lookup-menu"), s = m(!1), i = m(!1), l = m(!1), d = H(e, "selected"), p = X(d, t, "update:selected"), g = c(
      () => e.menuItems.find((x) => x.value === e.selected)
    ), $ = c(() => {
      var x, se;
      return (se = (x = o.value) == null ? void 0 : x.getHighlightedMenuItem()) == null ? void 0 : se.id;
    }), k = m(e.initialInputValue), V = c(() => ({
      "cdx-lookup--disabled": e.disabled,
      "cdx-lookup--pending": s.value
    })), {
      rootClasses: f,
      rootStyle: b,
      otherAttrs: C
    } = ue(n, V);
    function R(x) {
      g.value && g.value.label !== x && g.value.value !== x && (p.value = null), x === "" ? (i.value = !1, s.value = !1) : s.value = !0, t("input", x);
    }
    function q() {
      l.value = !0, k.value !== null && k.value !== "" && (e.menuItems.length > 0 || a["no-results"]) && (i.value = !0);
    }
    function O() {
      l.value = !1, i.value = !1;
    }
    function U(x) {
      !o.value || e.disabled || e.menuItems.length === 0 && !a["no-results"] || x.key === " " && i.value || o.value.delegateKeyNavigation(x);
    }
    return ne(d, (x) => {
      x !== null && (k.value = g.value ? g.value.label || g.value.value : "", t("input", k.value));
    }), ne(H(e, "menuItems"), (x) => {
      l.value && s.value && (x.length > 0 || a["no-results"]) && (i.value = !0), x.length === 0 && !a["no-results"] && (i.value = !1), s.value = !1;
    }), {
      menu: o,
      menuId: u,
      highlightedId: $,
      inputValue: k,
      modelWrapper: p,
      expanded: i,
      onInputBlur: O,
      rootClasses: f,
      rootStyle: b,
      otherAttrs: C,
      onUpdateInput: R,
      onInputFocus: q,
      onKeydown: U
    };
  }
});
function Gn(e, t, n, a, o, u) {
  const s = _("cdx-text-input"), i = _("cdx-menu");
  return r(), h("div", {
    class: T(["cdx-lookup", e.rootClasses]),
    style: ae(e.rootStyle)
  }, [
    N(s, Q({
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
    N(i, Q({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[1] || (t[1] = (l) => e.modelWrapper = l),
      expanded: e.expanded,
      "onUpdate:expanded": t[2] || (t[2] = (l) => e.expanded = l),
      "menu-items": e.menuItems
    }, e.menuConfig), {
      default: L(({ menuItem: l }) => [
        A(e.$slots, "menu-item", { menuItem: l })
      ]),
      "no-results": L(() => [
        A(e.$slots, "no-results")
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 6);
}
const la = /* @__PURE__ */ D(Qn, [["render", Gn]]), Xn = me(it), Jn = {
  notice: Et,
  error: Mt,
  warning: xt,
  success: kt
}, Yn = M({
  name: "CdxMessage",
  components: { CdxButton: de, CdxIcon: P },
  props: {
    type: {
      type: String,
      default: "notice",
      validator: Xn
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
    const n = m(!1), a = c(
      () => e.inline === !1 && e.dismissButtonLabel.length > 0
    ), o = c(() => e.autoDismiss === !1 ? !1 : e.autoDismiss === !0 ? 4e3 : e.autoDismiss), u = c(() => ({
      "cdx-message--inline": e.inline,
      "cdx-message--block": !e.inline,
      "cdx-message--user-dismissable": a.value,
      [`cdx-message--${e.type}`]: !0
    })), s = c(
      () => e.icon && e.type === "notice" ? e.icon : Jn[e.type]
    ), i = m("");
    function l(d) {
      n.value || (i.value = d === "user-dismissed" ? "cdx-message-leave-active-user" : "cdx-message-leave-active-system", n.value = !0, t(d));
    }
    return le(() => {
      o.value && setTimeout(() => l("auto-dismissed"), o.value);
    }), {
      dismissed: n,
      userDismissable: a,
      rootClasses: u,
      leaveActiveClass: i,
      computedIcon: s,
      onDismiss: l,
      cdxIconClose: St
    };
  }
});
const Zn = ["aria-live", "role"], el = { class: "cdx-message__content" };
function tl(e, t, n, a, o, u) {
  const s = _("cdx-icon"), i = _("cdx-button");
  return r(), F(De, {
    name: "cdx-message",
    appear: e.fadeIn,
    "leave-active-class": e.leaveActiveClass
  }, {
    default: L(() => [
      e.dismissed ? S("", !0) : (r(), h("div", {
        key: 0,
        class: T(["cdx-message", e.rootClasses]),
        "aria-live": e.type !== "error" ? "polite" : void 0,
        role: e.type === "error" ? "alert" : void 0
      }, [
        N(s, {
          class: "cdx-message__icon",
          icon: e.computedIcon
        }, null, 8, ["icon"]),
        v("div", el, [
          A(e.$slots, "default")
        ]),
        e.userDismissable ? (r(), F(i, {
          key: 0,
          class: "cdx-message__dismiss-button",
          type: "quiet",
          "aria-label": e.dismissButtonLabel,
          onClick: t[0] || (t[0] = (l) => e.onDismiss("user-dismissed"))
        }, {
          default: L(() => [
            N(s, {
              icon: e.cdxIconClose,
              "icon-label": e.dismissButtonLabel
            }, null, 8, ["icon", "icon-label"])
          ]),
          _: 1
        }, 8, ["aria-label"])) : S("", !0)
      ], 10, Zn))
    ]),
    _: 3
  }, 8, ["appear", "leave-active-class"]);
}
const aa = /* @__PURE__ */ D(Yn, [["render", tl]]), nl = M({
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
    const n = c(() => ({
      "cdx-radio--inline": e.inline
    })), a = m(), o = () => {
      a.value.focus();
    }, u = X(H(e, "modelValue"), t);
    return {
      rootClasses: n,
      input: a,
      focusInput: o,
      wrappedModel: u
    };
  }
});
const ll = ["name", "value", "disabled"], al = /* @__PURE__ */ v("span", { class: "cdx-radio__icon" }, null, -1), sl = { class: "cdx-radio__label-content" };
function ol(e, t, n, a, o, u) {
  return r(), h("span", {
    class: T(["cdx-radio", e.rootClasses])
  }, [
    v("label", {
      class: "cdx-radio__label",
      onClick: t[1] || (t[1] = (...s) => e.focusInput && e.focusInput(...s))
    }, [
      J(v("input", {
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (s) => e.wrappedModel = s),
        class: "cdx-radio__input",
        type: "radio",
        name: e.name,
        value: e.inputValue,
        disabled: e.disabled
      }, null, 8, ll), [
        [at, e.wrappedModel]
      ]),
      al,
      v("span", sl, [
        A(e.$slots, "default")
      ])
    ])
  ], 2);
}
const sa = /* @__PURE__ */ D(nl, [["render", ol]]), ul = M({
  name: "CdxSearchInput",
  components: {
    CdxButton: de,
    CdxTextInput: Ce
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
    const a = X(H(e, "modelValue"), t), o = c(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: u,
      rootStyle: s,
      otherAttrs: i
    } = ue(n, o);
    return {
      wrappedModel: a,
      rootClasses: u,
      rootStyle: s,
      otherAttrs: i,
      handleSubmit: () => {
        t("submit-click", a.value);
      },
      searchIcon: Lt
    };
  },
  methods: {
    focus() {
      this.$refs.textInput.focus();
    }
  }
});
const il = { class: "cdx-search-input__input-wrapper" };
function dl(e, t, n, a, o, u) {
  const s = _("cdx-text-input"), i = _("cdx-button");
  return r(), h("div", {
    class: T(["cdx-search-input", e.rootClasses]),
    style: ae(e.rootStyle)
  }, [
    v("div", il, [
      N(s, Q({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (l) => e.wrappedModel = l),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon
      }, e.otherAttrs, {
        onKeydown: ee(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "onKeydown"]),
      A(e.$slots, "default")
    ]),
    e.buttonLabel ? (r(), F(i, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: L(() => [
        te(j(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : S("", !0)
  ], 6);
}
const rl = /* @__PURE__ */ D(ul, [["render", dl]]), cl = M({
  name: "CdxSelect",
  components: {
    CdxIcon: P,
    CdxMenu: ve
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
    "update:selected"
  ],
  setup(e, { emit: t }) {
    const n = m(), a = m(), o = Y("select-handle"), u = Y("select-menu"), s = m(!1), i = X(H(e, "selected"), t, "update:selected"), l = c(
      () => e.menuItems.find((b) => b.value === e.selected)
    ), d = c(() => l.value ? l.value.label || l.value.value : e.defaultLabel), p = c(() => {
      if (e.defaultIcon && !l.value)
        return e.defaultIcon;
      if (l.value && l.value.icon)
        return l.value.icon;
    }), g = c(() => ({
      "cdx-select--enabled": !e.disabled,
      "cdx-select--disabled": e.disabled,
      "cdx-select--expanded": s.value,
      "cdx-select--value-selected": !!l.value,
      "cdx-select--no-selections": !l.value,
      "cdx-select--has-start-icon": !!p.value
    })), $ = c(() => {
      var b, C;
      return (C = (b = a.value) == null ? void 0 : b.getHighlightedMenuItem()) == null ? void 0 : C.id;
    });
    function k() {
      s.value = !1;
    }
    function V() {
      var b;
      e.disabled || (s.value = !s.value, (b = n.value) == null || b.focus());
    }
    function f(b) {
      var C;
      e.disabled || (C = a.value) == null || C.delegateKeyNavigation(b);
    }
    return {
      handle: n,
      handleId: o,
      menu: a,
      menuId: u,
      modelWrapper: i,
      selectedMenuItem: l,
      highlightedId: $,
      expanded: s,
      onBlur: k,
      currentLabel: d,
      rootClasses: g,
      onClick: V,
      onKeydown: f,
      startIcon: p,
      cdxIconExpand: Ke
    };
  }
});
const pl = ["aria-disabled"], fl = ["aria-owns", "aria-labelledby", "aria-activedescendant", "aria-expanded"], hl = ["id"];
function ml(e, t, n, a, o, u) {
  const s = _("cdx-icon"), i = _("cdx-menu");
  return r(), h("div", {
    class: T(["cdx-select", e.rootClasses]),
    "aria-disabled": e.disabled
  }, [
    v("div", {
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
      v("span", {
        id: e.handleId,
        role: "textbox",
        "aria-readonly": "true"
      }, [
        A(e.$slots, "label", {
          selectedMenuItem: e.selectedMenuItem,
          defaultLabel: e.defaultLabel
        }, () => [
          te(j(e.currentLabel), 1)
        ])
      ], 8, hl),
      e.startIcon ? (r(), F(s, {
        key: 0,
        icon: e.startIcon,
        class: "cdx-select__start-icon"
      }, null, 8, ["icon"])) : S("", !0),
      N(s, {
        icon: e.cdxIconExpand,
        class: "cdx-select__indicator"
      }, null, 8, ["icon"])
    ], 40, fl),
    N(i, Q({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[3] || (t[3] = (l) => e.modelWrapper = l),
      expanded: e.expanded,
      "onUpdate:expanded": t[4] || (t[4] = (l) => e.expanded = l),
      "menu-items": e.menuItems
    }, e.menuConfig), {
      default: L(({ menuItem: l }) => [
        A(e.$slots, "menu-item", { menuItem: l })
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 10, pl);
}
const oa = /* @__PURE__ */ D(cl, [["render", ml]]), vl = M({
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
    const t = we(Le), n = we(Ve);
    if (!t || !n)
      throw new Error("Tab component must be used inside a Tabs component");
    const a = t.value.get(e.name) || {}, o = c(() => e.name === n.value);
    return {
      tab: a,
      isActive: o
    };
  }
});
const bl = ["id", "aria-hidden", "aria-labelledby"];
function gl(e, t, n, a, o, u) {
  return J((r(), h("section", {
    id: e.tab.id,
    "aria-hidden": !e.isActive,
    "aria-labelledby": `${e.tab.id}-label`,
    class: "cdx-tab",
    role: "tabpanel",
    tabindex: "-1"
  }, [
    A(e.$slots, "default")
  ], 8, bl)), [
    [fe, e.isActive]
  ]);
}
const ua = /* @__PURE__ */ D(vl, [["render", gl]]);
function Me(e, t) {
  const n = m(!1);
  let a = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const o = new window.IntersectionObserver(
    (u) => {
      const s = u[0];
      s && (n.value = s.isIntersecting);
    },
    t
  );
  return le(() => {
    a = !0, e.value && o.observe(e.value);
  }), Te(() => {
    a = !1, o.disconnect();
  }), ne(e, (u) => {
    !a || (o.disconnect(), n.value = !1, u && o.observe(u));
  }), n;
}
const yl = M({
  name: "CdxTabs",
  components: {
    CdxButton: de,
    CdxIcon: P
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
    const a = m(), o = m(), u = m(), s = m(), i = m(), l = Ne(a), d = c(() => {
      var K;
      const B = [], w = (K = t.default) == null ? void 0 : K.call(t);
      w && w.forEach(E);
      function E(I) {
        I && typeof I == "object" && "type" in I && (typeof I.type == "object" && "name" in I.type && I.type.name === "CdxTab" ? B.push(I) : "children" in I && Array.isArray(I.children) && I.children.forEach(E));
      }
      return B;
    });
    if (!d.value || d.value.length === 0)
      throw new Error("Slot content cannot be empty");
    const p = c(() => d.value.reduce((B, w) => {
      var E;
      if (((E = w.props) == null ? void 0 : E.name) && typeof w.props.name == "string") {
        if (B.get(w.props.name))
          throw new Error("Tab names must be unique");
        B.set(w.props.name, {
          name: w.props.name,
          id: Y(w.props.name),
          label: w.props.label || w.props.name,
          disabled: w.props.disabled
        });
      }
      return B;
    }, /* @__PURE__ */ new Map())), g = X(H(e, "active"), n, "update:active"), $ = c(() => Array.from(p.value.keys())), k = c(() => $.value.indexOf(g.value)), V = c(() => {
      var B;
      return (B = p.value.get(g.value)) == null ? void 0 : B.id;
    });
    Se(Ve, g), Se(Le, p);
    const f = m(), b = m(), C = Me(f, { threshold: 0.95 }), R = Me(b, { threshold: 0.95 });
    function q(B, w) {
      const E = B;
      E && (w === 0 ? f.value = E : w === $.value.length - 1 && (b.value = E));
    }
    function O(B) {
      var K;
      const w = B === g.value, E = !!((K = p.value.get(B)) != null && K.disabled);
      return {
        "cdx-tabs__list__item--selected": w,
        "cdx-tabs__list__item--enabled": !E,
        "cdx-tabs__list__item--disabled": E
      };
    }
    const U = c(() => ({
      "cdx-tabs--framed": e.framed,
      "cdx-tabs--quiet": !e.framed
    }));
    function x(B) {
      if (!o.value || !s.value || !i.value)
        return 0;
      const w = l.value === "rtl" ? i.value : s.value, E = l.value === "rtl" ? s.value : i.value, K = B.offsetLeft, I = K + B.clientWidth, G = o.value.scrollLeft + w.clientWidth, re = o.value.scrollLeft + o.value.clientWidth - E.clientWidth;
      return K < G ? K - G : I > re ? I - re : 0;
    }
    function se(B) {
      var I;
      if (!o.value || !s.value || !i.value)
        return;
      const w = B === "next" && l.value === "ltr" || B === "prev" && l.value === "rtl" ? 1 : -1;
      let E = 0, K = B === "next" ? o.value.firstElementChild : o.value.lastElementChild;
      for (; K; ) {
        const G = B === "next" ? K.nextElementSibling : K.previousElementSibling;
        if (E = x(K), Math.sign(E) === w) {
          G && Math.abs(E) < 0.25 * o.value.clientWidth && (E = x(G));
          break;
        }
        K = G;
      }
      o.value.scrollBy({
        left: E,
        behavior: "smooth"
      }), (I = u.value) == null || I.focus();
    }
    return ne(g, () => {
      if (V.value === void 0 || !o.value || !s.value || !i.value)
        return;
      const B = document.getElementById(`${V.value}-label`);
      !B || o.value.scrollBy({
        left: x(B),
        behavior: "smooth"
      });
    }), {
      activeTab: g,
      activeTabIndex: k,
      activeTabId: V,
      currentDirection: l,
      rootElement: a,
      listElement: o,
      focusHolder: u,
      prevScroller: s,
      nextScroller: i,
      rootClasses: U,
      tabNames: $,
      tabsData: p,
      firstLabelVisible: C,
      lastLabelVisible: R,
      getLabelClasses: O,
      assignTemplateRefIfNecessary: q,
      scrollTabs: se,
      cdxIconPrevious: Tt,
      cdxIconNext: Ft
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
const Cl = {
  ref: "focusHolder",
  tabindex: "-1"
}, _l = {
  ref: "prevScroller",
  class: "cdx-tabs__prev-scroller"
}, $l = ["aria-activedescendant"], Al = ["id"], Bl = ["href", "aria-selected", "onClick", "onKeyup"], xl = {
  ref: "nextScroller",
  class: "cdx-tabs__next-scroller"
}, Il = { class: "cdx-tabs__content" };
function kl(e, t, n, a, o, u) {
  const s = _("cdx-icon"), i = _("cdx-button");
  return r(), h("div", {
    ref: "rootElement",
    class: T(["cdx-tabs", e.rootClasses])
  }, [
    v("div", {
      class: "cdx-tabs__header",
      tabindex: "0",
      onKeydown: [
        t[4] || (t[4] = ee(W((...l) => e.onRightArrowKeypress && e.onRightArrowKeypress(...l), ["prevent"]), ["right"])),
        t[5] || (t[5] = ee(W((...l) => e.onDownArrowKeypress && e.onDownArrowKeypress(...l), ["prevent"]), ["down"])),
        t[6] || (t[6] = ee(W((...l) => e.onLeftArrowKeypress && e.onLeftArrowKeypress(...l), ["prevent"]), ["left"]))
      ]
    }, [
      v("div", Cl, null, 512),
      J(v("div", _l, [
        N(i, {
          class: "cdx-tabs__scroll-button",
          type: "quiet",
          tabindex: "-1",
          "aria-hidden": !0,
          onMousedown: t[0] || (t[0] = W(() => {
          }, ["prevent"])),
          onClick: t[1] || (t[1] = (l) => e.scrollTabs("prev"))
        }, {
          default: L(() => [
            N(s, { icon: e.cdxIconPrevious }, null, 8, ["icon"])
          ]),
          _: 1
        })
      ], 512), [
        [fe, !e.firstLabelVisible]
      ]),
      v("ul", {
        ref: "listElement",
        class: "cdx-tabs__list",
        role: "tablist",
        "aria-activedescendant": e.activeTabId
      }, [
        (r(!0), h(ie, null, he(e.tabsData.values(), (l, d) => (r(), h("li", {
          id: `${l.id}-label`,
          key: d,
          ref_for: !0,
          ref: (p) => e.assignTemplateRefIfNecessary(p, d),
          class: T([e.getLabelClasses(l.name), "cdx-tabs__list__item"]),
          role: "presentation"
        }, [
          v("a", {
            href: `#${l.id}`,
            role: "tab",
            tabIndex: "-1",
            "aria-selected": l.name === e.activeTab,
            onClick: W((p) => e.select(l.name), ["prevent"]),
            onKeyup: ee((p) => e.select(l.name), ["enter"])
          }, j(l.label), 41, Bl)
        ], 10, Al))), 128))
      ], 8, $l),
      J(v("div", xl, [
        N(i, {
          class: "cdx-tabs__scroll-button",
          type: "quiet",
          tabindex: "-1",
          "aria-hidden": !0,
          onMousedown: t[2] || (t[2] = W(() => {
          }, ["prevent"])),
          onClick: t[3] || (t[3] = (l) => e.scrollTabs("next"))
        }, {
          default: L(() => [
            N(s, { icon: e.cdxIconNext }, null, 8, ["icon"])
          ]),
          _: 1
        })
      ], 512), [
        [fe, !e.lastLabelVisible]
      ])
    ], 32),
    v("div", Il, [
      A(e.$slots, "default")
    ])
  ], 2);
}
const ia = /* @__PURE__ */ D(yl, [["render", kl]]), wl = M({
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
      rootClasses: c(() => ({
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
const Sl = ["aria-pressed", "disabled"];
function Ml(e, t, n, a, o, u) {
  return r(), h("button", {
    class: T(["cdx-toggle-button", e.rootClasses]),
    "aria-pressed": e.modelValue,
    disabled: e.disabled,
    onClick: t[0] || (t[0] = (...s) => e.onClick && e.onClick(...s))
  }, [
    A(e.$slots, "default")
  ], 10, Sl);
}
const Dl = /* @__PURE__ */ D(wl, [["render", Ml]]), El = M({
  name: "CdxToggleButtonGroup",
  components: {
    CdxIcon: P,
    CdxToggleButton: Dl
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
    function n(o) {
      return Array.isArray(e.modelValue) ? e.modelValue.indexOf(o.value) !== -1 : e.modelValue !== null ? e.modelValue === o.value : !1;
    }
    function a(o, u) {
      if (Array.isArray(e.modelValue)) {
        const s = e.modelValue.indexOf(o.value) !== -1;
        u && !s ? t("update:modelValue", e.modelValue.concat(o.value)) : !u && s && t("update:modelValue", e.modelValue.filter((i) => i !== o.value));
      } else
        u && e.modelValue !== o.value && t("update:modelValue", o.value);
    }
    return {
      getButtonLabel: Re,
      isSelected: n,
      onUpdate: a
    };
  }
});
const Fl = { class: "cdx-toggle-button-group" };
function Tl(e, t, n, a, o, u) {
  const s = _("cdx-icon"), i = _("cdx-toggle-button");
  return r(), h("div", Fl, [
    (r(!0), h(ie, null, he(e.buttons, (l) => (r(), F(i, {
      key: l.value,
      "model-value": e.isSelected(l),
      disabled: l.disabled || e.disabled,
      "aria-label": l.ariaLabel,
      "onUpdate:modelValue": (d) => e.onUpdate(l, d)
    }, {
      default: L(() => [
        A(e.$slots, "default", {
          button: l,
          selected: e.isSelected(l)
        }, () => [
          l.icon ? (r(), F(s, {
            key: 0,
            icon: l.icon
          }, null, 8, ["icon"])) : S("", !0),
          te(" " + j(e.getButtonLabel(l)), 1)
        ])
      ]),
      _: 2
    }, 1032, ["model-value", "disabled", "aria-label", "onUpdate:modelValue"]))), 128))
  ]);
}
const da = /* @__PURE__ */ D(El, [["render", Tl]]), Ll = M({
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
    const a = m(), o = Y("toggle-switch"), {
      rootClasses: u,
      rootStyle: s,
      otherAttrs: i
    } = ue(t), l = X(H(e, "modelValue"), n);
    return {
      input: a,
      inputId: o,
      rootClasses: u,
      rootStyle: s,
      otherAttrs: i,
      wrappedModel: l,
      clickInput: () => {
        a.value.click();
      }
    };
  }
});
const Vl = ["for"], Kl = ["id", "disabled"], Nl = {
  key: 0,
  class: "cdx-toggle-switch__label-content"
}, Rl = /* @__PURE__ */ v("span", { class: "cdx-toggle-switch__switch" }, [
  /* @__PURE__ */ v("span", { class: "cdx-toggle-switch__switch__grip" })
], -1);
function ql(e, t, n, a, o, u) {
  return r(), h("span", {
    class: T(["cdx-toggle-switch", e.rootClasses]),
    style: ae(e.rootStyle)
  }, [
    v("label", {
      for: e.inputId,
      class: "cdx-toggle-switch__label"
    }, [
      J(v("input", Q({
        id: e.inputId,
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (s) => e.wrappedModel = s),
        class: "cdx-toggle-switch__input",
        type: "checkbox",
        disabled: e.disabled
      }, e.otherAttrs, {
        onKeydown: t[1] || (t[1] = ee(W((...s) => e.clickInput && e.clickInput(...s), ["prevent"]), ["enter"]))
      }), null, 16, Kl), [
        [Fe, e.wrappedModel]
      ]),
      e.$slots.default ? (r(), h("span", Nl, [
        A(e.$slots, "default")
      ])) : S("", !0),
      Rl
    ], 8, Vl)
  ], 6);
}
const ra = /* @__PURE__ */ D(Ll, [["render", ql]]), Ol = M({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: P,
    CdxMenu: ve,
    CdxSearchInput: rl
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
      default: rt
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
  setup(e, { attrs: t, emit: n, slots: a }) {
    const { searchResults: o, searchFooterUrl: u, debounceInterval: s } = st(e), i = m(), l = m(), d = Y("typeahead-search-menu"), p = m(!1), g = m(!1), $ = m(!1), k = m(!1), V = m(e.initialInputValue), f = m(""), b = c(() => {
      var y, z;
      return (z = (y = l.value) == null ? void 0 : y.getHighlightedMenuItem()) == null ? void 0 : z.id;
    }), C = m(null), R = c(() => ({
      "cdx-typeahead-search__menu-message--with-thumbnail": e.showThumbnail
    })), q = c(
      () => e.searchResults.find(
        (y) => y.value === C.value
      )
    ), O = c(
      () => u.value ? o.value.concat([
        { value: oe, url: u.value }
      ]) : o.value
    ), U = c(() => ({
      "cdx-typeahead-search--active": k.value,
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": p.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: x,
      rootStyle: se,
      otherAttrs: B
    } = ue(t, U);
    function w(y) {
      return y;
    }
    const E = c(() => ({
      showThumbnail: e.showThumbnail,
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let K, I;
    function G(y, z = !1) {
      q.value && q.value.label !== y && q.value.value !== y && (C.value = null), I !== void 0 && (clearTimeout(I), I = void 0), y === "" ? p.value = !1 : (g.value = !0, a["search-results-pending"] && (I = setTimeout(() => {
        k.value && (p.value = !0), $.value = !0;
      }, ct))), K !== void 0 && (clearTimeout(K), K = void 0);
      const Z = () => {
        n("input", y);
      };
      z ? Z() : K = setTimeout(() => {
        Z();
      }, s.value);
    }
    function re(y) {
      if (y === oe) {
        C.value = null, V.value = f.value;
        return;
      }
      C.value = y, y !== null && (V.value = q.value ? q.value.label || String(q.value.value) : "");
    }
    function ze() {
      k.value = !0, (f.value || $.value) && (p.value = !0);
    }
    function je() {
      k.value = !1, p.value = !1;
    }
    function _e(y) {
      const $e = y, { id: z } = $e, Z = pe($e, ["id"]), Ge = {
        searchResult: Z.value !== oe ? Z : null,
        index: O.value.findIndex(
          (Xe) => Xe.value === y.value
        ),
        numberOfResults: o.value.length
      };
      n("search-result-click", Ge);
    }
    function He(y) {
      if (y.value === oe) {
        V.value = f.value;
        return;
      }
      V.value = y.value ? y.label || String(y.value) : "";
    }
    function Pe(y) {
      var z;
      p.value = !1, (z = l.value) == null || z.clearActive(), _e(y);
    }
    function We() {
      let y = null, z = -1;
      q.value && (y = q.value, z = e.searchResults.indexOf(q.value));
      const Z = {
        searchResult: y,
        index: z,
        numberOfResults: o.value.length
      };
      n("submit", Z);
    }
    function Qe(y) {
      if (!l.value || !f.value || y.key === " " && p.value)
        return;
      const z = l.value.getHighlightedMenuItem();
      switch (y.key) {
        case "Enter":
          z && (z.value === oe ? window.location.assign(u.value) : l.value.delegateKeyNavigation(y, !1)), p.value = !1;
          break;
        case "Tab":
          p.value = !1;
          break;
        default:
          l.value.delegateKeyNavigation(y);
          break;
      }
    }
    return le(() => {
      e.initialInputValue && G(e.initialInputValue, !0);
    }), ne(H(e, "searchResults"), () => {
      f.value = V.value.trim(), k.value && g.value && f.value.length > 0 && (p.value = !0), I !== void 0 && (clearTimeout(I), I = void 0), g.value = !1, $.value = !1;
    }), {
      form: i,
      menu: l,
      menuId: d,
      highlightedId: b,
      selection: C,
      menuMessageClass: R,
      searchResultsWithFooter: O,
      asSearchResult: w,
      inputValue: V,
      searchQuery: f,
      expanded: p,
      showPending: $,
      rootClasses: x,
      rootStyle: se,
      otherAttrs: B,
      menuConfig: E,
      onUpdateInputValue: G,
      onUpdateMenuSelection: re,
      onFocus: ze,
      onBlur: je,
      onSearchResultClick: _e,
      onSearchResultKeyboardNavigation: He,
      onSearchFooterClick: Pe,
      onSubmit: We,
      onKeydown: Qe,
      MenuFooterValue: oe,
      articleIcon: It
    };
  },
  methods: {
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const Ul = ["id", "action"], zl = { class: "cdx-typeahead-search__menu-message__text" }, jl = { class: "cdx-typeahead-search__menu-message__text" }, Hl = ["href", "onClickCapture"], Pl = { class: "cdx-typeahead-search__search-footer__text" }, Wl = { class: "cdx-typeahead-search__search-footer__query" };
function Ql(e, t, n, a, o, u) {
  const s = _("cdx-icon"), i = _("cdx-menu"), l = _("cdx-search-input");
  return r(), h("div", {
    class: T(["cdx-typeahead-search", e.rootClasses]),
    style: ae(e.rootStyle)
  }, [
    v("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[3] || (t[3] = (...d) => e.onSubmit && e.onSubmit(...d))
    }, [
      N(l, Q({
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
        default: L(() => [
          N(i, Q({
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
            pending: L(() => [
              v("div", {
                class: T(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                v("span", zl, [
                  A(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": L(() => [
              v("div", {
                class: T(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                v("span", jl, [
                  A(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: L(({ menuItem: d, active: p }) => [
              d.value === e.MenuFooterValue ? (r(), h("a", {
                key: 0,
                class: T(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": p
                }]),
                href: e.asSearchResult(d).url,
                onClickCapture: W((g) => e.onSearchFooterClick(e.asSearchResult(d)), ["stop"])
              }, [
                N(s, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                v("span", Pl, [
                  A(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    v("strong", Wl, j(e.searchQuery), 1)
                  ])
                ])
              ], 42, Hl)) : S("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      A(e.$slots, "default")
    ], 40, Ul)
  ], 6);
}
const ca = /* @__PURE__ */ D(Ol, [["render", Ql]]);
export {
  de as CdxButton,
  Yl as CdxButtonGroup,
  Zl as CdxCard,
  ea as CdxCheckbox,
  na as CdxCombobox,
  P as CdxIcon,
  la as CdxLookup,
  ve as CdxMenu,
  Mn as CdxMenuItem,
  aa as CdxMessage,
  Ln as CdxProgressBar,
  sa as CdxRadio,
  rl as CdxSearchInput,
  _n as CdxSearchResultTitle,
  oa as CdxSelect,
  ua as CdxTab,
  ia as CdxTabs,
  Ce as CdxTextInput,
  qe as CdxThumbnail,
  Dl as CdxToggleButton,
  da as CdxToggleButtonGroup,
  ra as CdxToggleSwitch,
  ca as CdxTypeaheadSearch,
  ta as stringHelpers,
  Ne as useComputedDirection,
  Nt as useComputedLanguage,
  Y as useGeneratedId,
  Me as useIntersectionObserver,
  X as useModelWrapper,
  ue as useSplitAttributes
};
