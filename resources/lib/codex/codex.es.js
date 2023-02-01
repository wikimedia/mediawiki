var vt = Object.defineProperty, ht = Object.defineProperties;
var bt = Object.getOwnPropertyDescriptors;
var ye = Object.getOwnPropertySymbols;
var Re = Object.prototype.hasOwnProperty, Oe = Object.prototype.propertyIsEnumerable;
var Ne = (e, t, n) => t in e ? vt(e, t, { enumerable: !0, configurable: !0, writable: !0, value: n }) : e[t] = n, qe = (e, t) => {
  for (var n in t || (t = {}))
    Re.call(t, n) && Ne(e, n, t[n]);
  if (ye)
    for (var n of ye(t))
      Oe.call(t, n) && Ne(e, n, t[n]);
  return e;
}, je = (e, t) => ht(e, bt(t));
var Ce = (e, t) => {
  var n = {};
  for (var s in e)
    Re.call(e, s) && t.indexOf(s) < 0 && (n[s] = e[s]);
  if (e != null && ye)
    for (var s of ye(e))
      t.indexOf(s) < 0 && Oe.call(e, s) && (n[s] = e[s]);
  return n;
};
var Ie = (e, t, n) => new Promise((s, a) => {
  var u = (o) => {
    try {
      i(n.next(o));
    } catch (d) {
      a(d);
    }
  }, l = (o) => {
    try {
      i(n.throw(o));
    } catch (d) {
      a(d);
    }
  }, i = (o) => o.done ? s(o.value) : Promise.resolve(o.value).then(u, l);
  i((n = n.apply(e, t)).next());
});
import { ref as f, onMounted as ie, defineComponent as L, computed as c, openBlock as r, createElementBlock as b, normalizeClass as K, toDisplayString as z, createCommentVNode as B, Comment as gt, warn as yt, withKeys as J, renderSlot as w, resolveComponent as A, Fragment as ve, renderList as _e, createBlock as D, withCtx as T, createTextVNode as le, createVNode as q, Transition as Se, normalizeStyle as de, resolveDynamicComponent as Xe, createElementVNode as v, getCurrentInstance as Ct, toRef as G, withDirectives as ae, withModifiers as X, vModelCheckbox as Je, onUnmounted as Me, watch as te, nextTick as be, mergeProps as Y, vShow as ge, vModelDynamic as _t, useCssVars as De, vModelRadio as $t, inject as ze, provide as Ue, toRefs as At } from "vue";
function re(e) {
  return (t) => typeof t == "string" && e.indexOf(t) !== -1;
}
const Be = "cdx", It = [
  "default",
  "progressive",
  "destructive"
], Bt = [
  "normal",
  "primary",
  "quiet"
], xt = [
  "notice",
  "warning",
  "error",
  "success"
], Ye = re(xt), kt = [
  "text",
  "search"
], $e = [
  "default",
  "error"
], wt = 120, St = 500, me = "cdx-menu-footer-item", et = Symbol("CdxTabs"), tt = Symbol("CdxActiveTab"), Mt = '<path d="M11.53 2.3A1.85 1.85 0 0010 1.21 1.85 1.85 0 008.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z"/>', Dt = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>', Et = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>', Tt = '<path d="m4.34 2.93 12.73 12.73-1.41 1.41L2.93 4.35z"/><path d="M17.07 4.34 4.34 17.07l-1.41-1.41L15.66 2.93z"/>', Lt = '<path d="M13.728 1H6.272L1 6.272v7.456L6.272 19h7.456L19 13.728V6.272zM11 15H9v-2h2zm0-4H9V5h2z"/>', Ft = '<path d="m17.5 4.75-7.5 7.5-7.5-7.5L1 6.25l9 9 9-9z"/>', Vt = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>', Kt = '<path d="M8 19a1 1 0 001 1h2a1 1 0 001-1v-1H8zm9-12a7 7 0 10-12 4.9S7 14 7 15v1a1 1 0 001 1h4a1 1 0 001-1v-1c0-1 2-3.1 2-3.1A7 7 0 0017 7z"/>', Nt = '<path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zM9 5h2v2H9zm0 4h2v6H9z"/>', Rt = '<path d="M7 1 5.6 2.5 13 10l-7.4 7.5L7 19l9-9z"/>', Ot = '<path d="m4 10 9 9 1.4-1.5L7 10l7.4-7.5L13 1z"/>', qt = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4zM3 8a5 5 0 1010 0A5 5 0 003 8z"/>', jt = '<path fill-rule="evenodd" d="M10 20a10 10 0 100-20 10 10 0 000 20Zm-2-5 9-8.5L15.5 5 8 12 4.5 8.5 3 10l5 5Z" clip-rule="evenodd"/>', nt = Mt, zt = Dt, Ut = Et, ot = Tt, lt = Lt, at = Ft, Ht = Vt, Pt = {
  langCodeMap: {
    ar: Kt
  },
  default: Nt
}, Wt = {
  ltr: Rt,
  shouldFlip: !0
}, Qt = {
  ltr: Ot,
  shouldFlip: !0
}, Gt = qt, st = jt;
function Zt(e, t, n) {
  if (typeof e == "string" || "path" in e)
    return e;
  if ("shouldFlip" in e)
    return e.ltr;
  if ("rtl" in e)
    return n === "rtl" ? e.rtl : e.ltr;
  const s = t in e.langCodeMap ? e.langCodeMap[t] : e.default;
  return typeof s == "string" || "path" in s ? s : s.ltr;
}
function Xt(e, t) {
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
function ut(e) {
  const t = f(null);
  return ie(() => {
    const n = window.getComputedStyle(e.value).direction;
    t.value = n === "ltr" || n === "rtl" ? n : null;
  }), t;
}
function Jt(e) {
  const t = f("");
  return ie(() => {
    let n = e.value;
    for (; n && n.lang === ""; )
      n = n.parentElement;
    t.value = n ? n.lang : null;
  }), t;
}
const Yt = L({
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
    const n = f(), s = ut(n), a = Jt(n), u = c(() => e.dir || s.value), l = c(() => e.lang || a.value), i = c(() => ({
      "cdx-icon--flipped": u.value === "rtl" && l.value !== null && Xt(e.icon, l.value)
    })), o = c(
      () => Zt(e.icon, l.value || "", u.value || "ltr")
    ), d = c(() => typeof o.value == "string" ? o.value : ""), p = c(() => typeof o.value != "string" ? o.value.path : "");
    return {
      rootElement: n,
      rootClasses: i,
      iconSvg: d,
      iconPath: p,
      onClick: (y) => {
        t("click", y);
      }
    };
  }
});
const F = (e, t) => {
  const n = e.__vccOpts || e;
  for (const [s, a] of t)
    n[s] = a;
  return n;
}, en = ["aria-hidden"], tn = { key: 0 }, nn = ["innerHTML"], on = ["d"];
function ln(e, t, n, s, a, u) {
  return r(), b("span", {
    ref: "rootElement",
    class: K(["cdx-icon", e.rootClasses]),
    onClick: t[0] || (t[0] = (...l) => e.onClick && e.onClick(...l))
  }, [
    (r(), b("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": e.iconLabel ? void 0 : !0
    }, [
      e.iconLabel ? (r(), b("title", tn, z(e.iconLabel), 1)) : B("", !0),
      e.iconSvg ? (r(), b("g", {
        key: 1,
        innerHTML: e.iconSvg
      }, null, 8, nn)) : (r(), b("path", {
        key: 2,
        d: e.iconPath
      }, null, 8, on))
    ], 8, en))
  ], 2);
}
const Z = /* @__PURE__ */ F(Yt, [["render", ln]]), an = re(Bt), sn = re(It), un = (e) => {
  !e["aria-label"] && !e["aria-hidden"] && yt(`icon-only buttons require one of the following attribute: aria-label or aria-hidden.
		See documentation on https://doc.wikimedia.org/codex/latest/components/button.html#default-icon-only`);
};
function ke(e) {
  const t = [];
  for (const n of e)
    typeof n == "string" && n.trim() !== "" ? t.push(n) : Array.isArray(n) ? t.push(...ke(n)) : typeof n == "object" && n && (typeof n.type == "string" || typeof n.type == "object" ? t.push(n) : n.type !== gt && (typeof n.children == "string" && n.children.trim() !== "" ? t.push(n.children) : Array.isArray(n.children) && t.push(...ke(n.children))));
  return t;
}
const dn = (e, t) => {
  if (!e)
    return !1;
  const n = ke(e);
  if (n.length !== 1)
    return !1;
  const s = n[0], a = typeof s == "object" && typeof s.type == "object" && "name" in s.type && s.type.name === Z.name, u = typeof s == "object" && s.type === "svg";
  return a || u ? (un(t), !0) : !1;
}, rn = L({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: sn
    },
    type: {
      type: String,
      default: "normal",
      validator: an
    }
  },
  emits: ["click"],
  setup(e, { emit: t, slots: n, attrs: s }) {
    const a = f(!1);
    return {
      rootClasses: c(() => {
        var o;
        return {
          [`cdx-button--action-${e.action}`]: !0,
          [`cdx-button--type-${e.type}`]: !0,
          "cdx-button--framed": e.type !== "quiet",
          "cdx-button--icon-only": dn((o = n.default) == null ? void 0 : o.call(n), s),
          "cdx-button--is-active": a.value
        };
      }),
      onClick: (o) => {
        t("click", o);
      },
      setActive: (o) => {
        a.value = o;
      }
    };
  }
});
function cn(e, t, n, s, a, u) {
  return r(), b("button", {
    class: K(["cdx-button", e.rootClasses]),
    onClick: t[0] || (t[0] = (...l) => e.onClick && e.onClick(...l)),
    onKeydown: t[1] || (t[1] = J((l) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = J((l) => e.setActive(!1), ["space", "enter"]))
  }, [
    w(e.$slots, "default")
  ], 34);
}
const he = /* @__PURE__ */ F(rn, [["render", cn]]);
function it(e) {
  return e.label === void 0 ? e.value : e.label === null ? "" : e.label;
}
const pn = L({
  name: "CdxButtonGroup",
  components: {
    CdxButton: he,
    CdxIcon: Z
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
      getButtonLabel: it
    };
  }
});
const fn = { class: "cdx-button-group" };
function mn(e, t, n, s, a, u) {
  const l = A("cdx-icon"), i = A("cdx-button");
  return r(), b("div", fn, [
    (r(!0), b(ve, null, _e(e.buttons, (o) => (r(), D(i, {
      key: o.value,
      disabled: o.disabled || e.disabled,
      "aria-label": o.ariaLabel,
      onClick: (d) => e.$emit("click", o.value)
    }, {
      default: T(() => [
        w(e.$slots, "default", { button: o }, () => [
          o.icon ? (r(), D(l, {
            key: 0,
            icon: o.icon
          }, null, 8, ["icon"])) : B("", !0),
          le(" " + z(e.getButtonLabel(o)), 1)
        ])
      ]),
      _: 2
    }, 1032, ["disabled", "aria-label", "onClick"]))), 128))
  ]);
}
const Bl = /* @__PURE__ */ F(pn, [["render", mn]]), vn = L({
  name: "CdxThumbnail",
  components: { CdxIcon: Z },
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
    const t = f(!1), n = f({}), s = (a) => {
      const u = a.replace(/([\\"\n])/g, "\\$1"), l = new Image();
      l.onload = () => {
        n.value = { backgroundImage: `url("${u}")` }, t.value = !0;
      }, l.onerror = () => {
        t.value = !1;
      }, l.src = u;
    };
    return ie(() => {
      var a;
      (a = e.thumbnail) != null && a.url && s(e.thumbnail.url);
    }), {
      thumbnailStyle: n,
      thumbnailLoaded: t
    };
  }
});
const hn = { class: "cdx-thumbnail" }, bn = {
  key: 0,
  class: "cdx-thumbnail__placeholder"
};
function gn(e, t, n, s, a, u) {
  const l = A("cdx-icon");
  return r(), b("span", hn, [
    e.thumbnailLoaded ? B("", !0) : (r(), b("span", bn, [
      q(l, {
        icon: e.placeholderIcon,
        class: "cdx-thumbnail__placeholder__icon"
      }, null, 8, ["icon"])
    ])),
    q(Se, { name: "cdx-thumbnail__image" }, {
      default: T(() => [
        e.thumbnailLoaded ? (r(), b("span", {
          key: 0,
          style: de(e.thumbnailStyle),
          class: "cdx-thumbnail__image"
        }, null, 4)) : B("", !0)
      ]),
      _: 1
    })
  ]);
}
const dt = /* @__PURE__ */ F(vn, [["render", gn]]), yn = L({
  name: "CdxCard",
  components: { CdxIcon: Z, CdxThumbnail: dt },
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
    const t = c(() => !!e.url), n = c(() => t.value ? "a" : "span"), s = c(() => t.value ? e.url : void 0);
    return {
      isLink: t,
      contentTag: n,
      cardLink: s
    };
  }
});
const Cn = { class: "cdx-card__text" }, _n = { class: "cdx-card__text__title" }, $n = {
  key: 0,
  class: "cdx-card__text__description"
}, An = {
  key: 1,
  class: "cdx-card__text__supporting-text"
};
function In(e, t, n, s, a, u) {
  const l = A("cdx-thumbnail"), i = A("cdx-icon");
  return r(), D(Xe(e.contentTag), {
    href: e.cardLink,
    class: K(["cdx-card", {
      "cdx-card--is-link": e.isLink,
      "cdx-card--title-only": !e.$slots.description && !e.$slots["supporting-text"]
    }])
  }, {
    default: T(() => [
      e.thumbnail || e.forceThumbnail ? (r(), D(l, {
        key: 0,
        thumbnail: e.thumbnail,
        "placeholder-icon": e.customPlaceholderIcon,
        class: "cdx-card__thumbnail"
      }, null, 8, ["thumbnail", "placeholder-icon"])) : e.icon ? (r(), D(i, {
        key: 1,
        icon: e.icon,
        class: "cdx-card__icon"
      }, null, 8, ["icon"])) : B("", !0),
      v("span", Cn, [
        v("span", _n, [
          w(e.$slots, "title")
        ]),
        e.$slots.description ? (r(), b("span", $n, [
          w(e.$slots, "description")
        ])) : B("", !0),
        e.$slots["supporting-text"] ? (r(), b("span", An, [
          w(e.$slots, "supporting-text")
        ])) : B("", !0)
      ])
    ]),
    _: 3
  }, 8, ["href", "class"]);
}
const xl = /* @__PURE__ */ F(yn, [["render", In]]);
function se(e, t, n) {
  return c({
    get: () => e.value,
    set: (s) => t(n || "update:modelValue", s)
  });
}
let xe = 0;
function ne(e) {
  const t = Ct(), n = (t == null ? void 0 : t.props.id) || (t == null ? void 0 : t.attrs.id);
  return e ? `${Be}-${e}-${xe++}` : n ? `${Be}-${n}-${xe++}` : `${Be}-${xe++}`;
}
const Bn = L({
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
    })), s = f(), a = ne("checkbox"), u = () => {
      s.value.click();
    }, l = se(G(e, "modelValue"), t);
    return {
      rootClasses: n,
      input: s,
      checkboxId: a,
      clickInput: u,
      wrappedModel: l
    };
  }
});
const xn = ["id", "value", "disabled", ".indeterminate"], kn = /* @__PURE__ */ v("span", { class: "cdx-checkbox__icon" }, null, -1), wn = ["for"];
function Sn(e, t, n, s, a, u) {
  return r(), b("span", {
    class: K(["cdx-checkbox", e.rootClasses])
  }, [
    ae(v("input", {
      id: e.checkboxId,
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (l) => e.wrappedModel = l),
      class: "cdx-checkbox__input",
      type: "checkbox",
      value: e.inputValue,
      disabled: e.disabled,
      ".indeterminate": e.indeterminate,
      onKeydown: t[1] || (t[1] = J(X((...l) => e.clickInput && e.clickInput(...l), ["prevent"]), ["enter"]))
    }, null, 40, xn), [
      [Je, e.wrappedModel]
    ]),
    kn,
    v("label", {
      class: "cdx-checkbox__label",
      for: e.checkboxId
    }, [
      w(e.$slots, "default")
    ], 8, wn)
  ], 2);
}
const kl = /* @__PURE__ */ F(Bn, [["render", Sn]]), Mn = {
  error: lt,
  warning: nt,
  success: st
}, Dn = L({
  name: "CdxInfoChip",
  components: { CdxIcon: Z },
  props: {
    status: {
      type: String,
      default: "notice",
      validator: Ye
    },
    icon: {
      type: [String, Object],
      default: null
    }
  },
  setup(e) {
    const t = c(() => ({
      [`cdx-info-chip__icon--${e.status}`]: !0
    })), n = c(
      () => e.status === "notice" ? e.icon : Mn[e.status]
    );
    return {
      iconClass: t,
      computedIcon: n
    };
  }
});
const En = { class: "cdx-info-chip" }, Tn = { class: "cdx-info-chip--text" };
function Ln(e, t, n, s, a, u) {
  const l = A("cdx-icon");
  return r(), b("div", En, [
    e.computedIcon ? (r(), D(l, {
      key: 0,
      class: K(["cdx-info-chip__icon", e.iconClass]),
      icon: e.computedIcon
    }, null, 8, ["class", "icon"])) : B("", !0),
    v("span", Tn, [
      w(e.$slots, "default")
    ])
  ]);
}
const wl = /* @__PURE__ */ F(Dn, [["render", Ln]]);
function rt(e) {
  return e.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
}
const Fn = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
function ct(e, t) {
  if (!e)
    return [t, "", ""];
  const n = rt(e), s = new RegExp(
    n + Fn + "*",
    "i"
  ).exec(t);
  if (!s || s.index === void 0)
    return [t, "", ""];
  const a = s.index, u = a + s[0].length, l = t.slice(a, u), i = t.slice(0, a), o = t.slice(u, t.length);
  return [i, l, o];
}
const Sl = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  regExpEscape: rt,
  splitStringAtMatch: ct
}, Symbol.toStringTag, { value: "Module" })), Vn = L({
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
    titleChunks: c(() => ct(e.searchQuery, String(e.title)))
  })
});
const Kn = { class: "cdx-search-result-title" }, Nn = { class: "cdx-search-result-title__match" };
function Rn(e, t, n, s, a, u) {
  return r(), b("span", Kn, [
    v("bdi", null, [
      le(z(e.titleChunks[0]), 1),
      v("span", Nn, z(e.titleChunks[1]), 1),
      le(z(e.titleChunks[2]), 1)
    ])
  ]);
}
const On = /* @__PURE__ */ F(Vn, [["render", Rn]]), qn = L({
  name: "CdxMenuItem",
  components: { CdxIcon: Z, CdxThumbnail: dt, CdxSearchResultTitle: On },
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
    }, s = () => {
      t("change", "highlighted", !1);
    }, a = (p) => {
      p.button === 0 && t("change", "active", !0);
    }, u = () => {
      t("change", "selected", !0);
    }, l = c(() => e.searchQuery.length > 0), i = c(() => ({
      "cdx-menu-item--selected": e.selected,
      "cdx-menu-item--active": e.active && e.highlighted,
      "cdx-menu-item--highlighted": e.highlighted,
      "cdx-menu-item--enabled": !e.disabled,
      "cdx-menu-item--disabled": e.disabled,
      "cdx-menu-item--highlight-query": l.value,
      "cdx-menu-item--bold-label": e.boldLabel,
      "cdx-menu-item--has-description": !!e.description,
      "cdx-menu-item--hide-description-overflow": e.hideDescriptionOverflow
    })), o = c(() => e.url ? "a" : "span"), d = c(() => e.label || String(e.value));
    return {
      onMouseEnter: n,
      onMouseLeave: s,
      onMouseDown: a,
      onClick: u,
      highlightQuery: l,
      rootClasses: i,
      contentTag: o,
      title: d
    };
  }
});
const jn = ["id", "aria-disabled", "aria-selected"], zn = { class: "cdx-menu-item__text" }, Un = ["lang"], Hn = ["lang"], Pn = ["lang"], Wn = ["lang"];
function Qn(e, t, n, s, a, u) {
  const l = A("cdx-thumbnail"), i = A("cdx-icon"), o = A("cdx-search-result-title");
  return r(), b("li", {
    id: e.id,
    role: "option",
    class: K(["cdx-menu-item", e.rootClasses]),
    "aria-disabled": e.disabled,
    "aria-selected": e.selected,
    onMouseenter: t[0] || (t[0] = (...d) => e.onMouseEnter && e.onMouseEnter(...d)),
    onMouseleave: t[1] || (t[1] = (...d) => e.onMouseLeave && e.onMouseLeave(...d)),
    onMousedown: t[2] || (t[2] = X((...d) => e.onMouseDown && e.onMouseDown(...d), ["prevent"])),
    onClick: t[3] || (t[3] = (...d) => e.onClick && e.onClick(...d))
  }, [
    w(e.$slots, "default", {}, () => [
      (r(), D(Xe(e.contentTag), {
        href: e.url ? e.url : void 0,
        class: "cdx-menu-item__content"
      }, {
        default: T(() => {
          var d, p, $, y, S, I;
          return [
            e.showThumbnail ? (r(), D(l, {
              key: 0,
              thumbnail: e.thumbnail,
              class: "cdx-menu-item__thumbnail"
            }, null, 8, ["thumbnail"])) : e.icon ? (r(), D(i, {
              key: 1,
              icon: e.icon,
              class: "cdx-menu-item__icon"
            }, null, 8, ["icon"])) : B("", !0),
            v("span", zn, [
              e.highlightQuery ? (r(), D(o, {
                key: 0,
                title: e.title,
                "search-query": e.searchQuery,
                lang: (d = e.language) == null ? void 0 : d.label
              }, null, 8, ["title", "search-query", "lang"])) : (r(), b("span", {
                key: 1,
                class: "cdx-menu-item__text__label",
                lang: (p = e.language) == null ? void 0 : p.label
              }, [
                v("bdi", null, z(e.title), 1)
              ], 8, Un)),
              e.match ? (r(), b(ve, { key: 2 }, [
                le(z(" ") + " "),
                e.highlightQuery ? (r(), D(o, {
                  key: 0,
                  title: e.match,
                  "search-query": e.searchQuery,
                  lang: ($ = e.language) == null ? void 0 : $.match
                }, null, 8, ["title", "search-query", "lang"])) : (r(), b("span", {
                  key: 1,
                  class: "cdx-menu-item__text__match",
                  lang: (y = e.language) == null ? void 0 : y.match
                }, [
                  v("bdi", null, z(e.match), 1)
                ], 8, Hn))
              ], 64)) : B("", !0),
              e.supportingText ? (r(), b(ve, { key: 3 }, [
                le(z(" ") + " "),
                v("span", {
                  class: "cdx-menu-item__text__supporting-text",
                  lang: (S = e.language) == null ? void 0 : S.supportingText
                }, [
                  v("bdi", null, z(e.supportingText), 1)
                ], 8, Pn)
              ], 64)) : B("", !0),
              e.description ? (r(), b("span", {
                key: 4,
                class: "cdx-menu-item__text__description",
                lang: (I = e.language) == null ? void 0 : I.description
              }, [
                v("bdi", null, z(e.description), 1)
              ], 8, Wn)) : B("", !0)
            ])
          ];
        }),
        _: 1
      }, 8, ["href"]))
    ])
  ], 42, jn);
}
const Gn = /* @__PURE__ */ F(qn, [["render", Qn]]), Zn = L({
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
      rootClasses: c(() => ({
        "cdx-progress-bar--block": !e.inline,
        "cdx-progress-bar--inline": e.inline,
        "cdx-progress-bar--enabled": !e.disabled,
        "cdx-progress-bar--disabled": e.disabled
      }))
    };
  }
});
const Xn = ["aria-disabled"], Jn = /* @__PURE__ */ v("div", { class: "cdx-progress-bar__bar" }, null, -1), Yn = [
  Jn
];
function eo(e, t, n, s, a, u) {
  return r(), b("div", {
    class: K(["cdx-progress-bar", e.rootClasses]),
    role: "progressbar",
    "aria-disabled": e.disabled,
    "aria-valuemin": "0",
    "aria-valuemax": "100"
  }, Yn, 10, Xn);
}
const to = /* @__PURE__ */ F(Zn, [["render", eo]]);
function we(e, t) {
  const n = f(!1);
  let s = !1;
  if (typeof window != "object" || !("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype))
    return n;
  const a = new window.IntersectionObserver(
    (u) => {
      const l = u[0];
      l && (n.value = l.isIntersecting);
    },
    t
  );
  return ie(() => {
    s = !0, e.value && a.observe(e.value);
  }), Me(() => {
    s = !1, a.disconnect();
  }), te(e, (u) => {
    !s || (a.disconnect(), n.value = !1, u && a.observe(u));
  }), n;
}
function pe(e, t = c(() => ({}))) {
  const n = c(() => {
    const u = Ce(t.value, []);
    return e.class && e.class.split(" ").forEach((i) => {
      u[i] = !0;
    }), u;
  }), s = c(() => {
    if ("style" in e)
      return e.style;
  }), a = c(() => {
    const o = e, { class: u, style: l } = o;
    return Ce(o, ["class", "style"]);
  });
  return {
    rootClasses: n,
    rootStyle: s,
    otherAttrs: a
  };
}
const no = L({
  name: "CdxMenu",
  components: {
    CdxMenuItem: Gn,
    CdxProgressBar: to
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
  setup(e, { emit: t, slots: n, attrs: s }) {
    const a = c(() => (e.footer && e.menuItems ? [...e.menuItems, e.footer] : e.menuItems).map((k) => je(qe({}, k), {
      id: ne("menu-item")
    }))), u = c(() => n["no-results"] ? e.showNoResultsSlot !== null ? e.showNoResultsSlot : a.value.length === 0 : !1), l = f(null), i = f(null);
    function o() {
      return a.value.find(
        (h) => h.value === e.selected
      );
    }
    function d(h, k) {
      var O;
      if (!(k && k.disabled))
        switch (h) {
          case "selected":
            t("update:selected", (O = k == null ? void 0 : k.value) != null ? O : null), t("update:expanded", !1), i.value = null;
            break;
          case "highlighted":
            l.value = k || null;
            break;
          case "active":
            i.value = k || null;
            break;
        }
    }
    const p = c(() => {
      if (l.value !== null)
        return a.value.findIndex(
          (h) => h.value === l.value.value
        );
    });
    function $(h) {
      !h || (d("highlighted", h), t("menu-item-keyboard-navigation", h));
    }
    function y(h) {
      var U;
      const k = (fe) => {
        for (let ue = fe - 1; ue >= 0; ue--)
          if (!a.value[ue].disabled)
            return a.value[ue];
      };
      h = h || a.value.length;
      const O = (U = k(h)) != null ? U : k(a.value.length);
      $(O);
    }
    function S(h) {
      const k = (U) => a.value.find((fe, ue) => !fe.disabled && ue > U);
      h = h != null ? h : -1;
      const O = k(h) || k(-1);
      $(O);
    }
    function I(h, k = !0) {
      function O() {
        t("update:expanded", !0), d("highlighted", o());
      }
      function U() {
        k && (h.preventDefault(), h.stopPropagation());
      }
      switch (h.key) {
        case "Enter":
        case " ":
          return U(), e.expanded ? (l.value && t("update:selected", l.value.value), t("update:expanded", !1)) : O(), !0;
        case "Tab":
          return e.expanded && (l.value && t("update:selected", l.value.value), t("update:expanded", !1)), !0;
        case "ArrowUp":
          return U(), e.expanded ? (l.value === null && d("highlighted", o()), y(p.value)) : O(), P(), !0;
        case "ArrowDown":
          return U(), e.expanded ? (l.value === null && d("highlighted", o()), S(p.value)) : O(), P(), !0;
        case "Home":
          return U(), e.expanded ? (l.value === null && d("highlighted", o()), S()) : O(), P(), !0;
        case "End":
          return U(), e.expanded ? (l.value === null && d("highlighted", o()), y()) : O(), P(), !0;
        case "Escape":
          return U(), t("update:expanded", !1), !0;
        default:
          return !1;
      }
    }
    function V() {
      d("active");
    }
    const C = [], j = f(void 0), E = we(
      j,
      { threshold: 0.8 }
    );
    te(E, (h) => {
      h && t("load-more");
    });
    function N(h, k) {
      if (h) {
        C[k] = h.$el;
        const O = e.visibleItemLimit;
        if (!O || e.menuItems.length < O)
          return;
        const U = Math.min(
          O,
          Math.max(2, Math.floor(0.2 * e.menuItems.length))
        );
        k === e.menuItems.length - U && (j.value = h.$el);
      }
    }
    function P() {
      if (!e.visibleItemLimit || e.visibleItemLimit > e.menuItems.length || p.value === void 0)
        return;
      const h = p.value >= 0 ? p.value : 0;
      C[h].scrollIntoView({
        behavior: "smooth",
        block: "nearest"
      });
    }
    const W = f(null), Q = f(null);
    function oe() {
      if (Q.value = null, !e.visibleItemLimit || C.length <= e.visibleItemLimit) {
        W.value = null;
        return;
      }
      const h = C[0], k = C[e.visibleItemLimit];
      if (W.value = g(
        h,
        k
      ), e.footer) {
        const O = C[C.length - 1];
        Q.value = O.scrollHeight;
      }
    }
    function g(h, k) {
      const O = h.getBoundingClientRect().top;
      return k.getBoundingClientRect().top - O + 2;
    }
    ie(() => {
      document.addEventListener("mouseup", V);
    }), Me(() => {
      document.removeEventListener("mouseup", V);
    }), te(G(e, "expanded"), (h) => Ie(this, null, function* () {
      const k = o();
      !h && l.value && k === void 0 && d("highlighted"), h && k !== void 0 && d("highlighted", k), h && (yield be(), oe(), yield be(), P());
    })), te(G(e, "menuItems"), (h) => Ie(this, null, function* () {
      h.length < C.length && (C.length = h.length), e.expanded && (yield be(), oe(), yield be(), P());
    }), { deep: !0 });
    const m = c(() => ({
      "max-height": W.value ? `${W.value}px` : void 0,
      "overflow-y": W.value ? "scroll" : void 0,
      "margin-bottom": Q.value ? `${Q.value}px` : void 0
    })), x = c(() => ({
      "cdx-menu--has-footer": !!e.footer,
      "cdx-menu--has-sticky-footer": !!e.footer && !!W.value
    })), {
      rootClasses: R,
      rootStyle: M,
      otherAttrs: ee
    } = pe(s, x);
    return {
      listBoxStyle: m,
      rootClasses: R,
      rootStyle: M,
      otherAttrs: ee,
      assignTemplateRef: N,
      computedMenuItems: a,
      computedShowNoResultsSlot: u,
      highlightedMenuItem: l,
      activeMenuItem: i,
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
const oo = {
  key: 0,
  class: "cdx-menu__pending cdx-menu-item"
}, lo = {
  key: 1,
  class: "cdx-menu__no-results cdx-menu-item"
};
function ao(e, t, n, s, a, u) {
  const l = A("cdx-menu-item"), i = A("cdx-progress-bar");
  return ae((r(), b("div", {
    class: K(["cdx-menu", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    v("ul", Y({
      class: "cdx-menu__listbox",
      role: "listbox",
      "aria-multiselectable": "false",
      style: e.listBoxStyle
    }, e.otherAttrs), [
      e.showPending && e.computedMenuItems.length === 0 && e.$slots.pending ? (r(), b("li", oo, [
        w(e.$slots, "pending")
      ])) : B("", !0),
      e.computedShowNoResultsSlot ? (r(), b("li", lo, [
        w(e.$slots, "no-results")
      ])) : B("", !0),
      (r(!0), b(ve, null, _e(e.computedMenuItems, (o, d) => {
        var p, $;
        return r(), D(l, Y({
          key: o.value,
          ref_for: !0,
          ref: (y) => e.assignTemplateRef(y, d)
        }, o, {
          selected: o.value === e.selected,
          active: o.value === ((p = e.activeMenuItem) == null ? void 0 : p.value),
          highlighted: o.value === (($ = e.highlightedMenuItem) == null ? void 0 : $.value),
          "show-thumbnail": e.showThumbnail,
          "bold-label": e.boldLabel,
          "hide-description-overflow": e.hideDescriptionOverflow,
          "search-query": e.searchQuery,
          onChange: (y, S) => e.handleMenuItemChange(y, S && o),
          onClick: (y) => e.$emit("menu-item-click", o)
        }), {
          default: T(() => {
            var y, S;
            return [
              w(e.$slots, "default", {
                menuItem: o,
                active: o.value === ((y = e.activeMenuItem) == null ? void 0 : y.value) && o.value === ((S = e.highlightedMenuItem) == null ? void 0 : S.value)
              })
            ];
          }),
          _: 2
        }, 1040, ["selected", "active", "highlighted", "show-thumbnail", "bold-label", "hide-description-overflow", "search-query", "onChange", "onClick"]);
      }), 128)),
      e.showPending ? (r(), D(i, {
        key: 2,
        class: "cdx-menu__progress-bar",
        inline: !0
      })) : B("", !0)
    ], 16)
  ], 6)), [
    [ge, e.expanded]
  ]);
}
const Ae = /* @__PURE__ */ F(no, [["render", ao]]), so = re(kt), uo = re($e), io = L({
  name: "CdxTextInput",
  components: { CdxIcon: Z },
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
      validator: so
    },
    status: {
      type: String,
      default: "default",
      validator: uo
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
    const s = se(G(e, "modelValue"), t), a = c(() => e.clearable && !!s.value && !e.disabled), u = c(() => ({
      "cdx-text-input--has-start-icon": !!e.startIcon,
      "cdx-text-input--has-end-icon": !!e.endIcon,
      "cdx-text-input--clearable": a.value
    })), {
      rootClasses: l,
      rootStyle: i,
      otherAttrs: o
    } = pe(n, u), d = c(() => ({
      "cdx-text-input__input--has-value": !!s.value,
      [`cdx-text-input__input--status-${e.status}`]: !0
    }));
    return {
      wrappedModel: s,
      isClearable: a,
      rootClasses: l,
      rootStyle: i,
      otherAttrs: o,
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
      cdxIconClear: Ut
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    }
  }
});
const ro = ["type", "disabled"];
function co(e, t, n, s, a, u) {
  const l = A("cdx-icon");
  return r(), b("div", {
    class: K(["cdx-text-input", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    ae(v("input", Y({
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
    }), null, 16, ro), [
      [_t, e.wrappedModel]
    ]),
    e.startIcon ? (r(), D(l, {
      key: 0,
      icon: e.startIcon,
      class: "cdx-text-input__icon cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : B("", !0),
    e.endIcon ? (r(), D(l, {
      key: 1,
      icon: e.endIcon,
      class: "cdx-text-input__icon cdx-text-input__end-icon"
    }, null, 8, ["icon"])) : B("", !0),
    e.isClearable ? (r(), D(l, {
      key: 2,
      icon: e.cdxIconClear,
      class: "cdx-text-input__icon cdx-text-input__clear-icon",
      onMousedown: t[6] || (t[6] = X(() => {
      }, ["prevent"])),
      onClick: e.onClear
    }, null, 8, ["icon", "onClick"])) : B("", !0)
  ], 6);
}
const Ee = /* @__PURE__ */ F(io, [["render", co]]);
function Te(e) {
  const t = f(
    { width: void 0, height: void 0 }
  );
  if (typeof window != "object" || !("ResizeObserver" in window) || !("ResizeObserverEntry" in window))
    return t;
  const n = new window.ResizeObserver(
    (a) => {
      const u = a[0];
      u && (t.value = {
        width: u.borderBoxSize[0].inlineSize,
        height: u.borderBoxSize[0].blockSize
      });
    }
  );
  let s = !1;
  return ie(() => {
    s = !0, e.value && n.observe(e.value);
  }), Me(() => {
    s = !1, n.disconnect();
  }), te(e, (a) => {
    !s || (n.disconnect(), t.value = {
      width: void 0,
      height: void 0
    }, a && n.observe(a));
  }), t;
}
const po = re($e), Le = L({
  name: "CdxCombobox",
  components: {
    CdxButton: he,
    CdxIcon: Z,
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
      validator: po
    }
  },
  emits: [
    "update:selected",
    "load-more"
  ],
  setup(e, { emit: t, attrs: n, slots: s }) {
    const a = f(), u = f(), l = f(), i = ne("combobox"), o = G(e, "selected"), d = se(o, t, "update:selected"), p = f(!1), $ = f(!1), y = c(() => {
      var g, m;
      return (m = (g = l.value) == null ? void 0 : g.getHighlightedMenuItem()) == null ? void 0 : m.id;
    }), S = c(() => ({
      "cdx-combobox--expanded": p.value,
      "cdx-combobox--disabled": e.disabled
    })), I = Te(u), V = c(() => {
      var g;
      return `${(g = I.value.width) != null ? g : 0}px`;
    }), {
      rootClasses: C,
      rootStyle: j,
      otherAttrs: E
    } = pe(n, S);
    function N() {
      $.value && p.value ? p.value = !1 : (e.menuItems.length > 0 || s["no-results"]) && (p.value = !0);
    }
    function P() {
      p.value = $.value && p.value;
    }
    function W() {
      e.disabled || ($.value = !0);
    }
    function Q() {
      var g;
      e.disabled || (g = a.value) == null || g.focus();
    }
    function oe(g) {
      !l.value || e.disabled || e.menuItems.length === 0 || g.key === " " || l.value.delegateKeyNavigation(g);
    }
    return te(p, () => {
      $.value = !1;
    }), {
      input: a,
      inputWrapper: u,
      currentWidthInPx: V,
      menu: l,
      menuId: i,
      modelWrapper: d,
      expanded: p,
      highlightedId: y,
      onInputFocus: N,
      onInputBlur: P,
      onKeydown: oe,
      onButtonClick: Q,
      onButtonMousedown: W,
      cdxIconExpand: at,
      rootClasses: C,
      rootStyle: j,
      otherAttrs: E
    };
  }
}), He = () => {
  De((e) => ({
    bdbba3c2: e.currentWidthInPx
  }));
}, Pe = Le.setup;
Le.setup = Pe ? (e, t) => (He(), Pe(e, t)) : He;
const fo = {
  ref: "inputWrapper",
  class: "cdx-combobox__input-wrapper"
};
function mo(e, t, n, s, a, u) {
  const l = A("cdx-text-input"), i = A("cdx-icon"), o = A("cdx-button"), d = A("cdx-menu");
  return r(), b("div", {
    class: K(["cdx-combobox", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    v("div", fo, [
      q(l, Y({
        ref: "input",
        modelValue: e.modelWrapper,
        "onUpdate:modelValue": t[0] || (t[0] = (p) => e.modelWrapper = p)
      }, e.otherAttrs, {
        class: "cdx-combobox__input",
        "aria-activedescendant": e.highlightedId,
        "aria-expanded": e.expanded,
        "aria-controls": e.menuId,
        "aria-owns": e.menuId,
        disabled: e.disabled,
        status: e.status,
        "aria-autocomplete": "list",
        autocomplete: "off",
        role: "combobox",
        onKeydown: e.onKeydown,
        onFocus: e.onInputFocus,
        onBlur: e.onInputBlur
      }), null, 16, ["modelValue", "aria-activedescendant", "aria-expanded", "aria-controls", "aria-owns", "disabled", "status", "onKeydown", "onFocus", "onBlur"]),
      q(o, {
        class: "cdx-combobox__expand-button",
        "aria-hidden": "true",
        disabled: e.disabled,
        tabindex: "-1",
        onMousedown: e.onButtonMousedown,
        onClick: e.onButtonClick
      }, {
        default: T(() => [
          q(i, {
            class: "cdx-combobox__expand-icon",
            icon: e.cdxIconExpand
          }, null, 8, ["icon"])
        ]),
        _: 1
      }, 8, ["disabled", "onMousedown", "onClick"])
    ], 512),
    q(d, Y({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[1] || (t[1] = (p) => e.modelWrapper = p),
      expanded: e.expanded,
      "onUpdate:expanded": t[2] || (t[2] = (p) => e.expanded = p),
      "menu-items": e.menuItems
    }, e.menuConfig, {
      onLoadMore: t[3] || (t[3] = (p) => e.$emit("load-more"))
    }), {
      default: T(({ menuItem: p }) => [
        w(e.$slots, "menu-item", { menuItem: p })
      ]),
      "no-results": T(() => [
        w(e.$slots, "no-results")
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 6);
}
const Ml = /* @__PURE__ */ F(Le, [["render", mo]]), vo = L({
  name: "CdxDialog",
  components: {
    CdxButton: he,
    CdxIcon: Z
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
    const n = ne("dialog-label"), s = f(), a = f(), u = f(), l = f(), i = f(), o = c(() => !e.hideTitle || !!e.closeButtonLabel), d = c(() => ({
      "cdx-dialog--vertical-actions": e.stackedActions,
      "cdx-dialog--horizontal-actions": !e.stackedActions,
      "cdx-dialog--dividers": e.showDividers
    })), p = f(0);
    function $() {
      t("update:open", !1);
    }
    function y() {
      I(s.value);
    }
    function S() {
      I(s.value, !0);
    }
    function I(V, C = !1) {
      let j = Array.from(
        V.querySelectorAll(`
					input, select, textarea, button, object, a, area,
					[contenteditable], [tabindex]:not([tabindex^="-"])
				`)
      );
      C && (j = j.reverse());
      for (const E of j)
        if (E.focus(), document.activeElement === E)
          return !0;
      return !1;
    }
    return te(G(e, "open"), (V) => {
      V ? (p.value = window.innerWidth - document.documentElement.clientWidth, document.documentElement.style.setProperty("margin-right", `${p.value}px`), document.body.classList.add("cdx-dialog-open"), be(() => {
        var C;
        I(a.value) || (C = u.value) == null || C.focus();
      })) : (document.body.classList.remove("cdx-dialog-open"), document.documentElement.style.removeProperty("margin-right"));
    }), {
      close: $,
      cdxIconClose: ot,
      labelId: n,
      rootClasses: d,
      dialogElement: s,
      focusTrapStart: l,
      focusTrapEnd: i,
      focusFirst: y,
      focusLast: S,
      dialogBody: a,
      focusHolder: u,
      showHeader: o
    };
  }
});
const ho = ["aria-labelledby"], bo = {
  key: 0,
  class: "cdx-dialog__header"
}, go = ["id"], yo = {
  ref: "focusHolder",
  class: "cdx-dialog-focus-trap",
  tabindex: "-1"
}, Co = {
  ref: "dialogBody",
  class: "cdx-dialog__body"
}, _o = {
  key: 1,
  class: "cdx-dialog__footer"
};
function $o(e, t, n, s, a, u) {
  const l = A("cdx-icon"), i = A("cdx-button");
  return r(), D(Se, {
    name: "cdx-dialog-fade",
    appear: ""
  }, {
    default: T(() => [
      e.open ? (r(), b("div", {
        key: 0,
        class: "cdx-dialog-backdrop",
        onClick: t[5] || (t[5] = (...o) => e.close && e.close(...o)),
        onKeyup: t[6] || (t[6] = J((...o) => e.close && e.close(...o), ["escape"]))
      }, [
        v("div", {
          ref: "focusTrapStart",
          tabindex: "0",
          onFocus: t[0] || (t[0] = (...o) => e.focusLast && e.focusLast(...o))
        }, null, 544),
        v("div", {
          ref: "dialogElement",
          class: K(["cdx-dialog", e.rootClasses]),
          role: "dialog",
          "aria-labelledby": e.labelId,
          onClick: t[3] || (t[3] = X(() => {
          }, ["stop"]))
        }, [
          e.showHeader ? (r(), b("div", bo, [
            ae(v("h2", {
              id: e.labelId,
              class: "cdx-dialog__header__title"
            }, z(e.title), 9, go), [
              [ge, !e.hideTitle]
            ]),
            e.closeButtonLabel ? (r(), D(i, {
              key: 0,
              class: "cdx-dialog__header__close-button",
              type: "quiet",
              "aria-label": e.closeButtonLabel,
              onClick: e.close
            }, {
              default: T(() => [
                q(l, {
                  icon: e.cdxIconClose,
                  "icon-label": e.closeButtonLabel
                }, null, 8, ["icon", "icon-label"])
              ]),
              _: 1
            }, 8, ["aria-label", "onClick"])) : B("", !0)
          ])) : B("", !0),
          v("div", yo, null, 512),
          v("div", Co, [
            w(e.$slots, "default")
          ], 512),
          e.primaryAction || e.defaultAction ? (r(), b("div", _o, [
            e.primaryAction ? (r(), D(i, {
              key: 0,
              class: "cdx-dialog__footer__primary-action",
              type: "primary",
              action: e.primaryAction.actionType,
              disabled: e.primaryAction.disabled,
              onClick: t[1] || (t[1] = (o) => e.$emit("primary"))
            }, {
              default: T(() => [
                le(z(e.primaryAction.label), 1)
              ]),
              _: 1
            }, 8, ["action", "disabled"])) : B("", !0),
            e.defaultAction ? (r(), D(i, {
              key: 1,
              class: "cdx-dialog__footer__default-action",
              disabled: e.defaultAction.disabled,
              onClick: t[2] || (t[2] = (o) => e.$emit("default"))
            }, {
              default: T(() => [
                le(z(e.defaultAction.label), 1)
              ]),
              _: 1
            }, 8, ["disabled"])) : B("", !0)
          ])) : B("", !0)
        ], 10, ho),
        v("div", {
          ref: "focusTrapEnd",
          tabindex: "0",
          onFocus: t[4] || (t[4] = (...o) => e.focusFirst && e.focusFirst(...o))
        }, null, 544)
      ], 32)) : B("", !0)
    ]),
    _: 3
  });
}
const Dl = /* @__PURE__ */ F(vo, [["render", $o]]), Ao = re($e), Fe = L({
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
      validator: Ao
    }
  },
  emits: [
    "update:selected",
    "input",
    "load-more"
  ],
  setup: (e, { emit: t, attrs: n, slots: s }) => {
    const a = f(), u = f(), l = ne("lookup-menu"), i = f(!1), o = f(!1), d = f(!1), p = G(e, "selected"), $ = se(p, t, "update:selected"), y = c(
      () => e.menuItems.find((m) => m.value === e.selected)
    ), S = c(() => {
      var m, x;
      return (x = (m = u.value) == null ? void 0 : m.getHighlightedMenuItem()) == null ? void 0 : x.id;
    }), I = f(e.initialInputValue), V = Te(a), C = c(() => {
      var m;
      return `${(m = V.value.width) != null ? m : 0}px`;
    }), j = c(() => ({
      "cdx-lookup--disabled": e.disabled,
      "cdx-lookup--pending": i.value
    })), {
      rootClasses: E,
      rootStyle: N,
      otherAttrs: P
    } = pe(n, j);
    function W(m) {
      y.value && y.value.label !== m && y.value.value !== m && ($.value = null), m === "" ? (o.value = !1, i.value = !1) : i.value = !0, t("input", m);
    }
    function Q() {
      d.value = !0, I.value !== null && I.value !== "" && (e.menuItems.length > 0 || s["no-results"]) && (o.value = !0);
    }
    function oe() {
      d.value = !1, o.value = !1;
    }
    function g(m) {
      !u.value || e.disabled || e.menuItems.length === 0 && !s["no-results"] || m.key === " " || u.value.delegateKeyNavigation(m);
    }
    return te(p, (m) => {
      if (m !== null) {
        const x = y.value ? y.value.label || y.value.value : "";
        I.value !== x && (I.value = x, t("input", I.value));
      }
    }), te(G(e, "menuItems"), (m) => {
      d.value && i.value && (m.length > 0 || s["no-results"]) && (o.value = !0), m.length === 0 && !s["no-results"] && (o.value = !1), i.value = !1;
    }), {
      rootElement: a,
      currentWidthInPx: C,
      menu: u,
      menuId: l,
      highlightedId: S,
      inputValue: I,
      modelWrapper: $,
      expanded: o,
      onInputBlur: oe,
      rootClasses: E,
      rootStyle: N,
      otherAttrs: P,
      onUpdateInput: W,
      onInputFocus: Q,
      onKeydown: g
    };
  }
}), We = () => {
  De((e) => ({
    "23ca6c2a": e.currentWidthInPx
  }));
}, Qe = Fe.setup;
Fe.setup = Qe ? (e, t) => (We(), Qe(e, t)) : We;
function Io(e, t, n, s, a, u) {
  const l = A("cdx-text-input"), i = A("cdx-menu");
  return r(), b("div", {
    ref: "rootElement",
    class: K(["cdx-lookup", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    q(l, Y({
      modelValue: e.inputValue,
      "onUpdate:modelValue": t[0] || (t[0] = (o) => e.inputValue = o)
    }, e.otherAttrs, {
      class: "cdx-lookup__input",
      role: "combobox",
      autocomplete: "off",
      "aria-autocomplete": "list",
      "aria-controls": e.menuId,
      "aria-owns": e.menuId,
      "aria-expanded": e.expanded,
      "aria-activedescendant": e.highlightedId,
      disabled: e.disabled,
      status: e.status,
      "onUpdate:modelValue": e.onUpdateInput,
      onFocus: e.onInputFocus,
      onBlur: e.onInputBlur,
      onKeydown: e.onKeydown
    }), null, 16, ["modelValue", "aria-controls", "aria-owns", "aria-expanded", "aria-activedescendant", "disabled", "status", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
    q(i, Y({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[1] || (t[1] = (o) => e.modelWrapper = o),
      expanded: e.expanded,
      "onUpdate:expanded": t[2] || (t[2] = (o) => e.expanded = o),
      "menu-items": e.menuItems
    }, e.menuConfig, {
      onLoadMore: t[3] || (t[3] = (o) => e.$emit("load-more"))
    }), {
      default: T(({ menuItem: o }) => [
        w(e.$slots, "menu-item", { menuItem: o })
      ]),
      "no-results": T(() => [
        w(e.$slots, "no-results")
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 6);
}
const El = /* @__PURE__ */ F(Fe, [["render", Io]]), Bo = {
  notice: Pt,
  error: lt,
  warning: nt,
  success: st
}, xo = L({
  name: "CdxMessage",
  components: { CdxButton: he, CdxIcon: Z },
  props: {
    type: {
      type: String,
      default: "notice",
      validator: Ye
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
    const n = f(!1), s = c(
      () => e.inline === !1 && e.dismissButtonLabel.length > 0
    ), a = c(() => e.autoDismiss === !1 ? !1 : e.autoDismiss === !0 ? 4e3 : e.autoDismiss), u = c(() => ({
      "cdx-message--inline": e.inline,
      "cdx-message--block": !e.inline,
      "cdx-message--user-dismissable": s.value,
      [`cdx-message--${e.type}`]: !0
    })), l = c(
      () => e.icon && e.type === "notice" ? e.icon : Bo[e.type]
    ), i = f("");
    function o(d) {
      n.value || (i.value = d === "user-dismissed" ? "cdx-message-leave-active-user" : "cdx-message-leave-active-system", n.value = !0, t(d));
    }
    return ie(() => {
      a.value && setTimeout(() => o("auto-dismissed"), a.value);
    }), {
      dismissed: n,
      userDismissable: s,
      rootClasses: u,
      leaveActiveClass: i,
      computedIcon: l,
      onDismiss: o,
      cdxIconClose: ot
    };
  }
});
const ko = ["aria-live", "role"], wo = { class: "cdx-message__content" };
function So(e, t, n, s, a, u) {
  const l = A("cdx-icon"), i = A("cdx-button");
  return r(), D(Se, {
    name: "cdx-message",
    appear: e.fadeIn,
    "leave-active-class": e.leaveActiveClass
  }, {
    default: T(() => [
      e.dismissed ? B("", !0) : (r(), b("div", {
        key: 0,
        class: K(["cdx-message", e.rootClasses]),
        "aria-live": e.type !== "error" ? "polite" : void 0,
        role: e.type === "error" ? "alert" : void 0
      }, [
        q(l, {
          class: "cdx-message__icon--vue",
          icon: e.computedIcon
        }, null, 8, ["icon"]),
        v("div", wo, [
          w(e.$slots, "default")
        ]),
        e.userDismissable ? (r(), D(i, {
          key: 0,
          class: "cdx-message__dismiss-button",
          type: "quiet",
          "aria-label": e.dismissButtonLabel,
          onClick: t[0] || (t[0] = (o) => e.onDismiss("user-dismissed"))
        }, {
          default: T(() => [
            q(l, {
              icon: e.cdxIconClose,
              "icon-label": e.dismissButtonLabel
            }, null, 8, ["icon", "icon-label"])
          ]),
          _: 1
        }, 8, ["aria-label"])) : B("", !0)
      ], 10, ko))
    ]),
    _: 3
  }, 8, ["appear", "leave-active-class"]);
}
const Tl = /* @__PURE__ */ F(xo, [["render", So]]), Mo = L({
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
    })), s = f(), a = ne("radio"), u = () => {
      s.value.focus();
    }, l = se(G(e, "modelValue"), t);
    return {
      rootClasses: n,
      input: s,
      radioId: a,
      focusInput: u,
      wrappedModel: l
    };
  }
});
const Do = ["id", "name", "value", "disabled"], Eo = /* @__PURE__ */ v("span", { class: "cdx-radio__icon" }, null, -1), To = ["for"];
function Lo(e, t, n, s, a, u) {
  return r(), b("span", {
    class: K(["cdx-radio", e.rootClasses])
  }, [
    ae(v("input", {
      id: e.radioId,
      ref: "input",
      "onUpdate:modelValue": t[0] || (t[0] = (l) => e.wrappedModel = l),
      class: "cdx-radio__input",
      type: "radio",
      name: e.name,
      value: e.inputValue,
      disabled: e.disabled
    }, null, 8, Do), [
      [$t, e.wrappedModel]
    ]),
    Eo,
    v("label", {
      class: "cdx-radio__label",
      for: e.radioId,
      onClick: t[1] || (t[1] = (...l) => e.focusInput && e.focusInput(...l))
    }, [
      w(e.$slots, "default")
    ], 8, To)
  ], 2);
}
const Ll = /* @__PURE__ */ F(Mo, [["render", Lo]]), Fo = re($e), Vo = L({
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
      validator: Fo
    }
  },
  emits: [
    "update:modelValue",
    "submit-click"
  ],
  setup(e, { emit: t, attrs: n }) {
    const s = se(G(e, "modelValue"), t), a = c(() => ({
      "cdx-search-input--has-end-button": !!e.buttonLabel
    })), {
      rootClasses: u,
      rootStyle: l,
      otherAttrs: i
    } = pe(n, a);
    return {
      wrappedModel: s,
      rootClasses: u,
      rootStyle: l,
      otherAttrs: i,
      handleSubmit: () => {
        t("submit-click", s.value);
      },
      searchIcon: Gt
    };
  },
  methods: {
    focus() {
      this.$refs.textInput.focus();
    }
  }
});
const Ko = { class: "cdx-search-input__input-wrapper" };
function No(e, t, n, s, a, u) {
  const l = A("cdx-text-input"), i = A("cdx-button");
  return r(), b("div", {
    class: K(["cdx-search-input", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    v("div", Ko, [
      q(l, Y({
        ref: "textInput",
        modelValue: e.wrappedModel,
        "onUpdate:modelValue": t[0] || (t[0] = (o) => e.wrappedModel = o),
        class: "cdx-search-input__text-input",
        "input-type": "search",
        "start-icon": e.searchIcon,
        status: e.status
      }, e.otherAttrs, {
        onKeydown: J(e.handleSubmit, ["enter"])
      }), null, 16, ["modelValue", "start-icon", "status", "onKeydown"]),
      w(e.$slots, "default")
    ]),
    e.buttonLabel ? (r(), D(i, {
      key: 0,
      class: "cdx-search-input__end-button",
      onClick: e.handleSubmit
    }, {
      default: T(() => [
        le(z(e.buttonLabel), 1)
      ]),
      _: 1
    }, 8, ["onClick"])) : B("", !0)
  ], 6);
}
const Ro = /* @__PURE__ */ F(Vo, [["render", No]]), Ve = L({
  name: "CdxSelect",
  components: {
    CdxIcon: Z,
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
    const n = f(), s = f(), a = ne("select-handle"), u = ne("select-menu"), l = f(!1), i = se(G(e, "selected"), t, "update:selected"), o = c(
      () => e.menuItems.find((E) => E.value === e.selected)
    ), d = c(() => o.value ? o.value.label || o.value.value : e.defaultLabel), p = Te(n), $ = c(() => {
      var E;
      return `${(E = p.value.width) != null ? E : 0}px`;
    }), y = c(() => {
      if (e.defaultIcon && !o.value)
        return e.defaultIcon;
      if (o.value && o.value.icon)
        return o.value.icon;
    }), S = c(() => ({
      "cdx-select-vue--enabled": !e.disabled,
      "cdx-select-vue--disabled": e.disabled,
      "cdx-select-vue--expanded": l.value,
      "cdx-select-vue--value-selected": !!o.value,
      "cdx-select-vue--no-selections": !o.value,
      "cdx-select-vue--has-start-icon": !!y.value
    })), I = c(() => {
      var E, N;
      return (N = (E = s.value) == null ? void 0 : E.getHighlightedMenuItem()) == null ? void 0 : N.id;
    });
    function V() {
      l.value = !1;
    }
    function C() {
      var E;
      e.disabled || (l.value = !l.value, (E = n.value) == null || E.focus());
    }
    function j(E) {
      var N;
      e.disabled || (N = s.value) == null || N.delegateKeyNavigation(E);
    }
    return {
      handle: n,
      handleId: a,
      menu: s,
      menuId: u,
      modelWrapper: i,
      selectedMenuItem: o,
      highlightedId: I,
      expanded: l,
      onBlur: V,
      currentLabel: d,
      currentWidthInPx: $,
      rootClasses: S,
      onClick: C,
      onKeydown: j,
      startIcon: y,
      cdxIconExpand: at
    };
  }
}), Ge = () => {
  De((e) => ({
    "3af17e76": e.currentWidthInPx
  }));
}, Ze = Ve.setup;
Ve.setup = Ze ? (e, t) => (Ge(), Ze(e, t)) : Ge;
const Oo = ["aria-disabled"], qo = ["aria-owns", "aria-labelledby", "aria-activedescendant", "aria-expanded"], jo = ["id"];
function zo(e, t, n, s, a, u) {
  const l = A("cdx-icon"), i = A("cdx-menu");
  return r(), b("div", {
    class: K(["cdx-select-vue", e.rootClasses]),
    "aria-disabled": e.disabled
  }, [
    v("div", {
      ref: "handle",
      class: "cdx-select-vue__handle",
      tabindex: "0",
      role: "combobox",
      "aria-autocomplete": "list",
      "aria-owns": e.menuId,
      "aria-labelledby": e.handleId,
      "aria-activedescendant": e.highlightedId,
      "aria-haspopup": "listbox",
      "aria-expanded": e.expanded,
      onClick: t[0] || (t[0] = (...o) => e.onClick && e.onClick(...o)),
      onBlur: t[1] || (t[1] = (...o) => e.onBlur && e.onBlur(...o)),
      onKeydown: t[2] || (t[2] = (...o) => e.onKeydown && e.onKeydown(...o))
    }, [
      v("span", {
        id: e.handleId,
        role: "textbox",
        "aria-readonly": "true"
      }, [
        w(e.$slots, "label", {
          selectedMenuItem: e.selectedMenuItem,
          defaultLabel: e.defaultLabel
        }, () => [
          le(z(e.currentLabel), 1)
        ])
      ], 8, jo),
      e.startIcon ? (r(), D(l, {
        key: 0,
        icon: e.startIcon,
        class: "cdx-select-vue__start-icon"
      }, null, 8, ["icon"])) : B("", !0),
      q(l, {
        icon: e.cdxIconExpand,
        class: "cdx-select-vue__indicator"
      }, null, 8, ["icon"])
    ], 40, qo),
    q(i, Y({
      id: e.menuId,
      ref: "menu",
      selected: e.modelWrapper,
      "onUpdate:selected": t[3] || (t[3] = (o) => e.modelWrapper = o),
      expanded: e.expanded,
      "onUpdate:expanded": t[4] || (t[4] = (o) => e.expanded = o),
      "menu-items": e.menuItems
    }, e.menuConfig, {
      onLoadMore: t[5] || (t[5] = (o) => e.$emit("load-more"))
    }), {
      default: T(({ menuItem: o }) => [
        w(e.$slots, "menu-item", { menuItem: o })
      ]),
      _: 3
    }, 16, ["id", "selected", "expanded", "menu-items"])
  ], 10, Oo);
}
const Fl = /* @__PURE__ */ F(Ve, [["render", zo]]), Uo = L({
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
    const t = ze(et), n = ze(tt);
    if (!t || !n)
      throw new Error("Tab component must be used inside a Tabs component");
    const s = t.value.get(e.name) || {}, a = c(() => e.name === n.value);
    return {
      tab: s,
      isActive: a
    };
  }
});
const Ho = ["id", "aria-hidden", "aria-labelledby"];
function Po(e, t, n, s, a, u) {
  return ae((r(), b("section", {
    id: e.tab.id,
    "aria-hidden": !e.isActive,
    "aria-labelledby": `${e.tab.id}-label`,
    class: "cdx-tab",
    role: "tabpanel",
    tabindex: "-1"
  }, [
    w(e.$slots, "default")
  ], 8, Ho)), [
    [ge, e.isActive]
  ]);
}
const Vl = /* @__PURE__ */ F(Uo, [["render", Po]]), Wo = L({
  name: "CdxTabs",
  components: {
    CdxButton: he,
    CdxIcon: Z
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
    const s = f(), a = f(), u = f(), l = f(), i = f(), o = ut(s), d = c(() => {
      var R;
      const g = [], m = (R = t.default) == null ? void 0 : R.call(t);
      m && m.forEach(x);
      function x(M) {
        M && typeof M == "object" && "type" in M && (typeof M.type == "object" && "name" in M.type && M.type.name === "CdxTab" ? g.push(M) : "children" in M && Array.isArray(M.children) && M.children.forEach(x));
      }
      return g;
    });
    if (!d.value || d.value.length === 0)
      throw new Error("Slot content cannot be empty");
    const p = c(() => d.value.reduce((g, m) => {
      var x;
      if (((x = m.props) == null ? void 0 : x.name) && typeof m.props.name == "string") {
        if (g.get(m.props.name))
          throw new Error("Tab names must be unique");
        g.set(m.props.name, {
          name: m.props.name,
          id: ne(m.props.name),
          label: m.props.label || m.props.name,
          disabled: m.props.disabled
        });
      }
      return g;
    }, /* @__PURE__ */ new Map())), $ = se(G(e, "active"), n, "update:active"), y = c(() => Array.from(p.value.keys())), S = c(() => y.value.indexOf($.value)), I = c(() => {
      var g;
      return (g = p.value.get($.value)) == null ? void 0 : g.id;
    });
    Ue(tt, $), Ue(et, p);
    const V = f(), C = f(), j = we(V, { threshold: 0.95 }), E = we(C, { threshold: 0.95 });
    function N(g, m) {
      const x = g;
      x && (m === 0 ? V.value = x : m === y.value.length - 1 && (C.value = x));
    }
    function P(g) {
      var R;
      const m = g === $.value, x = !!((R = p.value.get(g)) != null && R.disabled);
      return {
        "cdx-tabs__list__item--selected": m,
        "cdx-tabs__list__item--enabled": !x,
        "cdx-tabs__list__item--disabled": x
      };
    }
    const W = c(() => ({
      "cdx-tabs--framed": e.framed,
      "cdx-tabs--quiet": !e.framed
    }));
    function Q(g) {
      if (!a.value || !l.value || !i.value)
        return 0;
      const m = o.value === "rtl" ? i.value : l.value, x = o.value === "rtl" ? l.value : i.value, R = g.offsetLeft, M = R + g.clientWidth, ee = a.value.scrollLeft + m.clientWidth, h = a.value.scrollLeft + a.value.clientWidth - x.clientWidth;
      return R < ee ? R - ee : M > h ? M - h : 0;
    }
    function oe(g) {
      var M;
      if (!a.value || !l.value || !i.value)
        return;
      const m = g === "next" && o.value === "ltr" || g === "prev" && o.value === "rtl" ? 1 : -1;
      let x = 0, R = g === "next" ? a.value.firstElementChild : a.value.lastElementChild;
      for (; R; ) {
        const ee = g === "next" ? R.nextElementSibling : R.previousElementSibling;
        if (x = Q(R), Math.sign(x) === m) {
          ee && Math.abs(x) < 0.25 * a.value.clientWidth && (x = Q(ee));
          break;
        }
        R = ee;
      }
      a.value.scrollBy({
        left: x,
        behavior: "smooth"
      }), (M = u.value) == null || M.focus();
    }
    return te($, () => {
      if (I.value === void 0 || !a.value || !l.value || !i.value)
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
      currentDirection: o,
      rootElement: s,
      listElement: a,
      focusHolder: u,
      prevScroller: l,
      nextScroller: i,
      rootClasses: W,
      tabNames: y,
      tabsData: p,
      firstLabelVisible: j,
      lastLabelVisible: E,
      getLabelClasses: P,
      assignTemplateRefIfNecessary: N,
      scrollTabs: oe,
      cdxIconPrevious: Qt,
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
const Qo = {
  ref: "focusHolder",
  tabindex: "-1"
}, Go = {
  ref: "prevScroller",
  class: "cdx-tabs__prev-scroller"
}, Zo = ["aria-activedescendant"], Xo = ["id"], Jo = ["href", "aria-disabled", "aria-selected", "onClick", "onKeyup"], Yo = {
  ref: "nextScroller",
  class: "cdx-tabs__next-scroller"
}, el = { class: "cdx-tabs__content" };
function tl(e, t, n, s, a, u) {
  const l = A("cdx-icon"), i = A("cdx-button");
  return r(), b("div", {
    ref: "rootElement",
    class: K(["cdx-tabs", e.rootClasses])
  }, [
    v("div", {
      class: "cdx-tabs__header",
      tabindex: "0",
      onKeydown: [
        t[4] || (t[4] = J(X((...o) => e.onRightArrowKeypress && e.onRightArrowKeypress(...o), ["prevent"]), ["right"])),
        t[5] || (t[5] = J(X((...o) => e.onDownArrowKeypress && e.onDownArrowKeypress(...o), ["prevent"]), ["down"])),
        t[6] || (t[6] = J(X((...o) => e.onLeftArrowKeypress && e.onLeftArrowKeypress(...o), ["prevent"]), ["left"]))
      ]
    }, [
      v("div", Qo, null, 512),
      ae(v("div", Go, [
        q(i, {
          class: "cdx-tabs__scroll-button",
          type: "quiet",
          tabindex: "-1",
          "aria-hidden": !0,
          onMousedown: t[0] || (t[0] = X(() => {
          }, ["prevent"])),
          onClick: t[1] || (t[1] = (o) => e.scrollTabs("prev"))
        }, {
          default: T(() => [
            q(l, { icon: e.cdxIconPrevious }, null, 8, ["icon"])
          ]),
          _: 1
        })
      ], 512), [
        [ge, !e.firstLabelVisible]
      ]),
      v("ul", {
        ref: "listElement",
        class: "cdx-tabs__list",
        role: "tablist",
        "aria-activedescendant": e.activeTabId
      }, [
        (r(!0), b(ve, null, _e(e.tabsData.values(), (o, d) => (r(), b("li", {
          id: `${o.id}-label`,
          key: d,
          ref_for: !0,
          ref: (p) => e.assignTemplateRefIfNecessary(p, d),
          class: K([e.getLabelClasses(o.name), "cdx-tabs__list__item"]),
          role: "presentation"
        }, [
          v("a", {
            href: `#${o.id}`,
            role: "tab",
            tabIndex: "-1",
            "aria-disabled": o.disabled,
            "aria-selected": o.name === e.activeTab,
            onClick: X((p) => e.select(o.name), ["prevent"]),
            onKeyup: J((p) => e.select(o.name), ["enter"])
          }, z(o.label), 41, Jo)
        ], 10, Xo))), 128))
      ], 8, Zo),
      ae(v("div", Yo, [
        q(i, {
          class: "cdx-tabs__scroll-button",
          type: "quiet",
          tabindex: "-1",
          "aria-hidden": !0,
          onMousedown: t[2] || (t[2] = X(() => {
          }, ["prevent"])),
          onClick: t[3] || (t[3] = (o) => e.scrollTabs("next"))
        }, {
          default: T(() => [
            q(l, { icon: e.cdxIconNext }, null, 8, ["icon"])
          ]),
          _: 1
        })
      ], 512), [
        [ge, !e.lastLabelVisible]
      ])
    ], 32),
    v("div", el, [
      w(e.$slots, "default")
    ])
  ], 2);
}
const Kl = /* @__PURE__ */ F(Wo, [["render", tl]]), nl = L({
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
    const n = f(!1);
    return {
      rootClasses: c(() => ({
        "cdx-toggle-button--quiet": e.quiet,
        "cdx-toggle-button--framed": !e.quiet,
        "cdx-toggle-button--toggled-on": e.modelValue,
        "cdx-toggle-button--toggled-off": !e.modelValue,
        "cdx-toggle-button--is-active": n.value
      })),
      onClick: () => {
        t("update:modelValue", !e.modelValue);
      },
      setActive: (l) => {
        n.value = l;
      }
    };
  }
});
const ol = ["aria-pressed", "disabled"];
function ll(e, t, n, s, a, u) {
  return r(), b("button", {
    class: K(["cdx-toggle-button", e.rootClasses]),
    "aria-pressed": e.modelValue,
    disabled: e.disabled,
    onClick: t[0] || (t[0] = (...l) => e.onClick && e.onClick(...l)),
    onKeydown: t[1] || (t[1] = J((l) => e.setActive(!0), ["space", "enter"])),
    onKeyup: t[2] || (t[2] = J((l) => e.setActive(!1), ["space", "enter"]))
  }, [
    w(e.$slots, "default")
  ], 42, ol);
}
const al = /* @__PURE__ */ F(nl, [["render", ll]]), sl = L({
  name: "CdxToggleButtonGroup",
  components: {
    CdxIcon: Z,
    CdxToggleButton: al
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
    function n(a) {
      return Array.isArray(e.modelValue) ? e.modelValue.indexOf(a.value) !== -1 : e.modelValue !== null ? e.modelValue === a.value : !1;
    }
    function s(a, u) {
      if (Array.isArray(e.modelValue)) {
        const l = e.modelValue.indexOf(a.value) !== -1;
        u && !l ? t("update:modelValue", e.modelValue.concat(a.value)) : !u && l && t("update:modelValue", e.modelValue.filter((i) => i !== a.value));
      } else
        u && e.modelValue !== a.value && t("update:modelValue", a.value);
    }
    return {
      getButtonLabel: it,
      isSelected: n,
      onUpdate: s
    };
  }
});
const ul = { class: "cdx-toggle-button-group" };
function il(e, t, n, s, a, u) {
  const l = A("cdx-icon"), i = A("cdx-toggle-button");
  return r(), b("div", ul, [
    (r(!0), b(ve, null, _e(e.buttons, (o) => (r(), D(i, {
      key: o.value,
      "model-value": e.isSelected(o),
      disabled: o.disabled || e.disabled,
      "aria-label": o.ariaLabel,
      "onUpdate:modelValue": (d) => e.onUpdate(o, d)
    }, {
      default: T(() => [
        w(e.$slots, "default", {
          button: o,
          selected: e.isSelected(o)
        }, () => [
          o.icon ? (r(), D(l, {
            key: 0,
            icon: o.icon
          }, null, 8, ["icon"])) : B("", !0),
          le(" " + z(e.getButtonLabel(o)), 1)
        ])
      ]),
      _: 2
    }, 1032, ["model-value", "disabled", "aria-label", "onUpdate:modelValue"]))), 128))
  ]);
}
const Nl = /* @__PURE__ */ F(sl, [["render", il]]), dl = L({
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
    const s = f(), a = ne("toggle-switch"), {
      rootClasses: u,
      rootStyle: l,
      otherAttrs: i
    } = pe(t), o = se(G(e, "modelValue"), n);
    return {
      input: s,
      inputId: a,
      rootClasses: u,
      rootStyle: l,
      otherAttrs: i,
      wrappedModel: o,
      clickInput: () => {
        s.value.click();
      }
    };
  }
});
const rl = ["for"], cl = ["id", "disabled"], pl = {
  key: 0,
  class: "cdx-toggle-switch__label-content"
}, fl = /* @__PURE__ */ v("span", { class: "cdx-toggle-switch__switch" }, [
  /* @__PURE__ */ v("span", { class: "cdx-toggle-switch__switch__grip" })
], -1);
function ml(e, t, n, s, a, u) {
  return r(), b("span", {
    class: K(["cdx-toggle-switch", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    v("label", {
      for: e.inputId,
      class: "cdx-toggle-switch__label"
    }, [
      ae(v("input", Y({
        id: e.inputId,
        ref: "input",
        "onUpdate:modelValue": t[0] || (t[0] = (l) => e.wrappedModel = l),
        class: "cdx-toggle-switch__input",
        type: "checkbox",
        disabled: e.disabled
      }, e.otherAttrs, {
        onKeydown: t[1] || (t[1] = J(X((...l) => e.clickInput && e.clickInput(...l), ["prevent"]), ["enter"]))
      }), null, 16, cl), [
        [Je, e.wrappedModel]
      ]),
      e.$slots.default ? (r(), b("span", pl, [
        w(e.$slots, "default")
      ])) : B("", !0),
      fl
    ], 8, rl)
  ], 6);
}
const Rl = /* @__PURE__ */ F(dl, [["render", ml]]), vl = L({
  name: "CdxTypeaheadSearch",
  components: {
    CdxIcon: Z,
    CdxMenu: Ae,
    CdxSearchInput: Ro
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
      default: wt
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
  setup(e, { attrs: t, emit: n, slots: s }) {
    const { searchResults: a, searchFooterUrl: u, debounceInterval: l } = At(e), i = f(), o = f(), d = ne("typeahead-search-menu"), p = f(!1), $ = f(!1), y = f(!1), S = f(!1), I = f(e.initialInputValue), V = f(""), C = c(() => {
      var _, H;
      return (H = (_ = o.value) == null ? void 0 : _.getHighlightedMenuItem()) == null ? void 0 : H.id;
    }), j = f(null), E = c(() => ({
      "cdx-typeahead-search__menu-message--has-thumbnail": e.showThumbnail
    })), N = c(
      () => e.searchResults.find(
        (_) => _.value === j.value
      )
    ), P = c(
      () => u.value ? { value: me, url: u.value } : void 0
    ), W = c(() => ({
      "cdx-typeahead-search--show-thumbnail": e.showThumbnail,
      "cdx-typeahead-search--expanded": p.value,
      "cdx-typeahead-search--auto-expand-width": e.showThumbnail && e.autoExpandWidth
    })), {
      rootClasses: Q,
      rootStyle: oe,
      otherAttrs: g
    } = pe(t, W);
    function m(_) {
      return _;
    }
    const x = c(() => ({
      visibleItemLimit: e.visibleItemLimit,
      showThumbnail: e.showThumbnail,
      boldLabel: !0,
      hideDescriptionOverflow: !0
    }));
    let R, M;
    function ee(_, H = !1) {
      N.value && N.value.label !== _ && N.value.value !== _ && (j.value = null), M !== void 0 && (clearTimeout(M), M = void 0), _ === "" ? p.value = !1 : ($.value = !0, s["search-results-pending"] && (M = setTimeout(() => {
        S.value && (p.value = !0), y.value = !0;
      }, St))), R !== void 0 && (clearTimeout(R), R = void 0);
      const ce = () => {
        n("input", _);
      };
      H ? ce() : R = setTimeout(() => {
        ce();
      }, l.value);
    }
    function h(_) {
      if (_ === me) {
        j.value = null, I.value = V.value;
        return;
      }
      j.value = _, _ !== null && (I.value = N.value ? N.value.label || String(N.value.value) : "");
    }
    function k() {
      S.value = !0, (V.value || y.value) && (p.value = !0);
    }
    function O() {
      S.value = !1, p.value = !1;
    }
    function U(_) {
      const Ke = _, { id: H } = Ke, ce = Ce(Ke, ["id"]);
      if (ce.value === me) {
        n("search-result-click", {
          searchResult: null,
          index: a.value.length,
          numberOfResults: a.value.length
        });
        return;
      }
      fe(ce);
    }
    function fe(_) {
      const H = {
        searchResult: _,
        index: a.value.findIndex(
          (ce) => ce.value === _.value
        ),
        numberOfResults: a.value.length
      };
      n("search-result-click", H);
    }
    function ue(_) {
      if (_.value === me) {
        I.value = V.value;
        return;
      }
      I.value = _.value ? _.label || String(_.value) : "";
    }
    function pt(_) {
      var H;
      p.value = !1, (H = o.value) == null || H.clearActive(), U(_);
    }
    function ft(_) {
      if (N.value)
        fe(N.value), _.stopPropagation(), window.location.assign(N.value.url), _.preventDefault();
      else {
        const H = {
          searchResult: null,
          index: -1,
          numberOfResults: a.value.length
        };
        n("submit", H);
      }
    }
    function mt(_) {
      if (!o.value || !V.value || _.key === " ")
        return;
      const H = o.value.getHighlightedMenuItem();
      switch (_.key) {
        case "Enter":
          H && (H.value === me ? window.location.assign(u.value) : o.value.delegateKeyNavigation(_, !1)), p.value = !1;
          break;
        case "Tab":
          p.value = !1;
          break;
        default:
          o.value.delegateKeyNavigation(_);
          break;
      }
    }
    return ie(() => {
      e.initialInputValue && ee(e.initialInputValue, !0);
    }), te(G(e, "searchResults"), () => {
      V.value = I.value.trim(), S.value && $.value && V.value.length > 0 && (p.value = !0), M !== void 0 && (clearTimeout(M), M = void 0), $.value = !1, y.value = !1;
    }), {
      form: i,
      menu: o,
      menuId: d,
      highlightedId: C,
      selection: j,
      menuMessageClass: E,
      footer: P,
      asSearchResult: m,
      inputValue: I,
      searchQuery: V,
      expanded: p,
      showPending: y,
      rootClasses: Q,
      rootStyle: oe,
      otherAttrs: g,
      menuConfig: x,
      onUpdateInputValue: ee,
      onUpdateMenuSelection: h,
      onFocus: k,
      onBlur: O,
      onSearchResultClick: U,
      onSearchResultKeyboardNavigation: ue,
      onSearchFooterClick: pt,
      onSubmit: ft,
      onKeydown: mt,
      MenuFooterValue: me,
      articleIcon: zt
    };
  },
  methods: {
    focus() {
      this.$refs.searchInput.focus();
    }
  }
});
const hl = ["id", "action"], bl = { class: "cdx-typeahead-search__menu-message__text" }, gl = { class: "cdx-typeahead-search__menu-message__text" }, yl = ["href", "onClickCapture"], Cl = { class: "cdx-typeahead-search__search-footer__text" }, _l = { class: "cdx-typeahead-search__search-footer__query" };
function $l(e, t, n, s, a, u) {
  const l = A("cdx-icon"), i = A("cdx-menu"), o = A("cdx-search-input");
  return r(), b("div", {
    class: K(["cdx-typeahead-search", e.rootClasses]),
    style: de(e.rootStyle)
  }, [
    v("form", {
      id: e.id,
      ref: "form",
      class: "cdx-typeahead-search__form",
      action: e.formAction,
      onSubmit: t[4] || (t[4] = (...d) => e.onSubmit && e.onSubmit(...d))
    }, [
      q(o, Y({
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
          q(i, Y({
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
              v("div", {
                class: K(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                v("span", bl, [
                  w(e.$slots, "search-results-pending")
                ])
              ], 2)
            ]),
            "no-results": T(() => [
              v("div", {
                class: K(["cdx-typeahead-search__menu-message", e.menuMessageClass])
              }, [
                v("span", gl, [
                  w(e.$slots, "search-no-results-text")
                ])
              ], 2)
            ]),
            default: T(({ menuItem: d, active: p }) => [
              d.value === e.MenuFooterValue ? (r(), b("a", {
                key: 0,
                class: K(["cdx-typeahead-search__search-footer", {
                  "cdx-typeahead-search__search-footer__active": p
                }]),
                href: e.asSearchResult(d).url,
                onClickCapture: X(($) => e.onSearchFooterClick(e.asSearchResult(d)), ["stop"])
              }, [
                q(l, {
                  class: "cdx-typeahead-search__search-footer__icon",
                  icon: e.articleIcon
                }, null, 8, ["icon"]),
                v("span", Cl, [
                  w(e.$slots, "search-footer-text", { searchQuery: e.searchQuery }, () => [
                    v("strong", _l, z(e.searchQuery), 1)
                  ])
                ])
              ], 42, yl)) : B("", !0)
            ]),
            _: 3
          }, 16, ["id", "expanded", "show-pending", "selected", "menu-items", "footer", "search-query", "show-no-results-slot", "aria-label", "onUpdate:selected", "onMenuItemKeyboardNavigation"])
        ]),
        _: 3
      }, 16, ["modelValue", "button-label", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
      w(e.$slots, "default")
    ], 40, hl)
  ], 6);
}
const Ol = /* @__PURE__ */ F(vl, [["render", $l]]);
export {
  he as CdxButton,
  Bl as CdxButtonGroup,
  xl as CdxCard,
  kl as CdxCheckbox,
  Ml as CdxCombobox,
  Dl as CdxDialog,
  Z as CdxIcon,
  wl as CdxInfoChip,
  El as CdxLookup,
  Ae as CdxMenu,
  Gn as CdxMenuItem,
  Tl as CdxMessage,
  to as CdxProgressBar,
  Ll as CdxRadio,
  Ro as CdxSearchInput,
  On as CdxSearchResultTitle,
  Fl as CdxSelect,
  Vl as CdxTab,
  Kl as CdxTabs,
  Ee as CdxTextInput,
  dt as CdxThumbnail,
  al as CdxToggleButton,
  Nl as CdxToggleButtonGroup,
  Rl as CdxToggleSwitch,
  Ol as CdxTypeaheadSearch,
  Sl as stringHelpers,
  ut as useComputedDirection,
  Jt as useComputedLanguage,
  ne as useGeneratedId,
  we as useIntersectionObserver,
  se as useModelWrapper,
  Te as useResizeObserver,
  pe as useSplitAttributes
};
