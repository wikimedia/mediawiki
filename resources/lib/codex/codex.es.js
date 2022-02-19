var __defProp = Object.defineProperty;
var __defProps = Object.defineProperties;
var __getOwnPropDescs = Object.getOwnPropertyDescriptors;
var __getOwnPropSymbols = Object.getOwnPropertySymbols;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __propIsEnum = Object.prototype.propertyIsEnumerable;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __spreadValues = (a, b) => {
  for (var prop in b || (b = {}))
    if (__hasOwnProp.call(b, prop))
      __defNormalProp(a, prop, b[prop]);
  if (__getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(b)) {
      if (__propIsEnum.call(b, prop))
        __defNormalProp(a, prop, b[prop]);
    }
  return a;
};
var __spreadProps = (a, b) => __defProps(a, __getOwnPropDescs(b));
var __objRest = (source, exclude) => {
  var target = {};
  for (var prop in source)
    if (__hasOwnProp.call(source, prop) && exclude.indexOf(prop) < 0)
      target[prop] = source[prop];
  if (source != null && __getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(source)) {
      if (exclude.indexOf(prop) < 0 && __propIsEnum.call(source, prop))
        target[prop] = source[prop];
    }
  return target;
};
import { defineComponent, computed, openBlock, createElementBlock, normalizeClass, renderSlot, ref, toRef, createElementVNode, withKeys, withModifiers, withDirectives, vModelCheckbox, onMounted, toDisplayString, createCommentVNode, inject, createTextVNode, resolveComponent, normalizeStyle, mergeProps, vModelDynamic, createBlock, getCurrentInstance, provide, watch, createVNode, withCtx, Fragment, renderList, vShow, vModelRadio, toRefs } from "vue";
const LibraryPrefix = "cdx";
const ButtonActions = [
  "default",
  "progressive",
  "destructive"
];
const ButtonTypes = [
  "normal",
  "primary",
  "quiet"
];
const MessageTypes = [
  "notice",
  "warning",
  "error",
  "success"
];
const TextInputTypes = [
  "text",
  "search"
];
const MenuStateKey = Symbol("CdxMenuState");
const MenuOptionsKey = Symbol("CdxMenuOptions");
const DebounceInterval = 120;
const MenuFooterValue = "cdx-menu-footer-option";
function makeStringTypeValidator(allowedValues) {
  return (s) => typeof s === "string" && allowedValues.indexOf(s) !== -1;
}
var Button_vue_vue_type_style_index_0_lang = "";
var _export_sfc = (sfc, props) => {
  const target = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target[key] = val;
  }
  return target;
};
const buttonTypeValidator = makeStringTypeValidator(ButtonTypes);
const buttonActionValidator = makeStringTypeValidator(ButtonActions);
const _sfc_main$d = defineComponent({
  name: "CdxButton",
  props: {
    action: {
      type: String,
      default: "default",
      validator: buttonActionValidator
    },
    type: {
      type: String,
      default: "normal",
      validator: buttonTypeValidator
    }
  },
  emits: ["click"],
  setup(props, { emit }) {
    const rootClasses = computed(() => ({
      "cdx-button--action-default": props.action === "default",
      "cdx-button--action-progressive": props.action === "progressive",
      "cdx-button--action-destructive": props.action === "destructive",
      "cdx-button--type-primary": props.type === "primary",
      "cdx-button--type-normal": props.type === "normal",
      "cdx-button--type-quiet": props.type === "quiet",
      "cdx-button--framed": props.type !== "quiet"
    }));
    const onClick = (event) => {
      emit("click", event);
    };
    return {
      rootClasses,
      onClick
    };
  }
});
function _sfc_render$d(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("button", {
    class: normalizeClass(["cdx-button", _ctx.rootClasses]),
    onClick: _cache[0] || (_cache[0] = (...args) => _ctx.onClick && _ctx.onClick(...args))
  }, [
    renderSlot(_ctx.$slots, "default")
  ], 2);
}
var CdxButton = /* @__PURE__ */ _export_sfc(_sfc_main$d, [["render", _sfc_render$d]]);
function useModelWrapper(modelValueRef, emit, eventName) {
  return computed({
    get: () => modelValueRef.value,
    set: (value) => emit(eventName || "update:modelValue", value)
  });
}
var Checkbox_vue_vue_type_style_index_0_lang = "";
const _sfc_main$c = defineComponent({
  name: "CdxCheckbox",
  props: {
    modelValue: {
      type: [Boolean, Array],
      default: false
    },
    inputValue: {
      type: [String, Number, Boolean],
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    indeterminate: {
      type: Boolean,
      default: false
    },
    inline: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(props, { emit }) {
    const rootClasses = computed(() => {
      return {
        "cdx-checkbox--inline": !!props.inline
      };
    });
    const input = ref();
    const label = ref();
    const focusInput = () => {
      input.value.focus();
    };
    const clickLabel = () => {
      label.value.click();
    };
    const wrappedModel = useModelWrapper(toRef(props, "modelValue"), emit);
    return {
      rootClasses,
      input,
      label,
      focusInput,
      clickLabel,
      wrappedModel
    };
  }
});
const _hoisted_1$c = ["aria-disabled"];
const _hoisted_2$a = ["value", "disabled", ".indeterminate"];
const _hoisted_3$7 = /* @__PURE__ */ createElementVNode("span", { class: "cdx-checkbox__icon" }, null, -1);
const _hoisted_4$4 = { class: "cdx-checkbox__label-content" };
function _sfc_render$c(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", {
    class: normalizeClass(["cdx-checkbox", _ctx.rootClasses])
  }, [
    createElementVNode("label", {
      ref: "label",
      class: "cdx-checkbox__label",
      "aria-disabled": _ctx.disabled,
      onClick: _cache[1] || (_cache[1] = (...args) => _ctx.focusInput && _ctx.focusInput(...args)),
      onKeydown: _cache[2] || (_cache[2] = withKeys(withModifiers((...args) => _ctx.clickLabel && _ctx.clickLabel(...args), ["prevent"]), ["enter"]))
    }, [
      withDirectives(createElementVNode("input", {
        ref: "input",
        "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.wrappedModel = $event),
        class: "cdx-checkbox__input",
        type: "checkbox",
        value: _ctx.inputValue,
        disabled: _ctx.disabled,
        ".indeterminate": _ctx.indeterminate
      }, null, 8, _hoisted_2$a), [
        [vModelCheckbox, _ctx.wrappedModel]
      ]),
      _hoisted_3$7,
      createElementVNode("span", _hoisted_4$4, [
        renderSlot(_ctx.$slots, "default")
      ])
    ], 40, _hoisted_1$c)
  ], 2);
}
var Checkbox = /* @__PURE__ */ _export_sfc(_sfc_main$c, [["render", _sfc_render$c]]);
var svgAlert = '<path d="M11.53 2.3A1.85 1.85 0 0010 1.21 1.85 1.85 0 008.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z"/>';
var svgArticleSearch = '<path d="M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z"/><circle cx="10" cy="10" r="3"/>';
var svgCheck = '<path d="M7 14.17 2.83 10l-1.41 1.41L7 17 19 5l-1.41-1.42z"/>';
var svgClear = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>';
var svgClose = '<path d="m4.34 2.93 12.73 12.73-1.41 1.41L2.93 4.35z"/><path d="M17.07 4.34 4.34 17.07l-1.41-1.41L15.66 2.93z"/>';
var svgError = '<path d="M13.728 1H6.272L1 6.272v7.456L6.272 19h7.456L19 13.728V6.272zM11 15H9v-2h2zm0-4H9V5h2z"/>';
var svgExpand = '<path d="m17.5 4.75-7.5 7.5-7.5-7.5L1 6.25l9 9 9-9z"/>';
var svgImageLayoutFrameless = '<path d="M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z"/><path d="M19 5H1V3h18zm0 12H1v-2h18z"/>';
var svgLightbulb = '<path d="M8 19a1 1 0 001 1h2a1 1 0 001-1v-1H8zm9-12a7 7 0 10-12 4.9S7 14 7 15v1a1 1 0 001 1h4a1 1 0 001-1v-1c0-1 2-3.1 2-3.1A7 7 0 0017 7z"/>';
var svgInfoFilled = '<path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zM9 5h2v2H9zm0 4h2v6H9z"/>';
var svgSearch = '<path d="M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4-5.4-5.4zM3 8a5 5 0 1010 0A5 5 0 103 8z"/>';
const cdxIconAlert = svgAlert;
const cdxIconArticleSearch = svgArticleSearch;
const cdxIconCheck = svgCheck;
const cdxIconClear = svgClear;
const cdxIconClose = svgClose;
const cdxIconError = svgError;
const cdxIconExpand = svgExpand;
const cdxIconImageLayoutFrameless = svgImageLayoutFrameless;
const cdxIconInfoFilled = {
  langCodeMap: {
    ar: svgLightbulb
  },
  default: svgInfoFilled
};
const cdxIconSearch = svgSearch;
function resolveIcon(icon, langCode, dir) {
  if (typeof icon === "string" || "path" in icon) {
    return icon;
  }
  if ("shouldFlip" in icon) {
    return icon.ltr;
  }
  if ("rtl" in icon) {
    return dir === "rtl" ? icon.rtl : icon.ltr;
  }
  const langCodeIcon = langCode in icon.langCodeMap ? icon.langCodeMap[langCode] : icon.default;
  return typeof langCodeIcon === "string" || "path" in langCodeIcon ? langCodeIcon : langCodeIcon.ltr;
}
function shouldIconFlip(icon, langCode) {
  if (typeof icon === "string") {
    return false;
  }
  if ("langCodeMap" in icon) {
    const langCodeIcon = langCode in icon.langCodeMap ? icon.langCodeMap[langCode] : icon.default;
    if (typeof langCodeIcon === "string") {
      return false;
    }
    icon = langCodeIcon;
  }
  if ("shouldFlipExceptions" in icon && Array.isArray(icon.shouldFlipExceptions)) {
    const exception = icon.shouldFlipExceptions.indexOf(langCode);
    return exception === void 0 || exception === -1;
  }
  if ("shouldFlip" in icon) {
    return icon.shouldFlip;
  }
  return false;
}
function useComputedDirection(root) {
  const computedDir = ref(null);
  onMounted(() => {
    const dir = window.getComputedStyle(root.value).direction;
    computedDir.value = dir === "ltr" || dir === "rtl" ? dir : null;
  });
  return computedDir;
}
function useComputedLanguage(root) {
  const computedLang = ref("");
  onMounted(() => {
    let ancestor = root.value;
    while (ancestor && ancestor.lang === "") {
      ancestor = ancestor.parentElement;
    }
    computedLang.value = ancestor ? ancestor.lang : null;
  });
  return computedLang;
}
var Icon_vue_vue_type_style_index_0_lang = "";
const _sfc_main$b = defineComponent({
  name: "CdxIcon",
  props: {
    icon: {
      type: [String, Object],
      required: true
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
  setup(props, { emit }) {
    const rootElement = ref();
    const computedDir = useComputedDirection(rootElement);
    const computedLang = useComputedLanguage(rootElement);
    const overriddenDir = computed(() => props.dir || computedDir.value);
    const overriddenLang = computed(() => props.lang || computedLang.value);
    const rootClasses = computed(() => ({
      "cdx-icon--flipped": overriddenDir.value === "rtl" && overriddenLang.value !== null && shouldIconFlip(props.icon, overriddenLang.value)
    }));
    const resolvedIcon = computed(() => resolveIcon(props.icon, overriddenLang.value || "", overriddenDir.value || "ltr"));
    const iconSvg = computed(() => typeof resolvedIcon.value === "string" ? resolvedIcon.value : "");
    const iconPath = computed(() => typeof resolvedIcon.value !== "string" ? resolvedIcon.value.path : "");
    const onClick = (event) => {
      emit("click", event);
    };
    return {
      rootElement,
      rootClasses,
      iconSvg,
      iconPath,
      onClick
    };
  }
});
const _hoisted_1$b = ["aria-hidden"];
const _hoisted_2$9 = { key: 0 };
const _hoisted_3$6 = ["innerHTML"];
const _hoisted_4$3 = ["d"];
function _sfc_render$b(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", {
    ref: "rootElement",
    class: normalizeClass(["cdx-icon", _ctx.rootClasses]),
    onClick: _cache[0] || (_cache[0] = (...args) => _ctx.onClick && _ctx.onClick(...args))
  }, [
    (openBlock(), createElementBlock("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "20",
      height: "20",
      viewBox: "0 0 20 20",
      "aria-hidden": !_ctx.iconLabel
    }, [
      _ctx.iconLabel ? (openBlock(), createElementBlock("title", _hoisted_2$9, toDisplayString(_ctx.iconLabel), 1)) : createCommentVNode("", true),
      _ctx.iconSvg ? (openBlock(), createElementBlock("g", {
        key: 1,
        fill: "currentColor",
        innerHTML: _ctx.iconSvg
      }, null, 8, _hoisted_3$6)) : (openBlock(), createElementBlock("path", {
        key: 2,
        d: _ctx.iconPath,
        fill: "currentColor"
      }, null, 8, _hoisted_4$3))
    ], 8, _hoisted_1$b))
  ], 2);
}
var CdxIcon = /* @__PURE__ */ _export_sfc(_sfc_main$b, [["render", _sfc_render$b]]);
var Option_vue_vue_type_style_index_0_lang = "";
const _sfc_main$a = defineComponent({
  name: "CdxOption",
  props: {
    id: {
      type: String,
      required: true
    },
    value: {
      type: [String, Number],
      required: true
    },
    disabled: {
      type: Boolean,
      default: false
    },
    label: {
      type: String,
      default: ""
    }
  },
  emits: [
    "change"
  ],
  setup: (props, { emit }) => {
    const computedOptions = inject(MenuOptionsKey);
    const state = inject(MenuStateKey);
    const thisOption = computed(() => computedOptions == null ? void 0 : computedOptions.value.find((i) => i.id === props.id));
    if (!state || !thisOption.value) {
      throw new Error("Option component must be used with a Menu component");
    }
    const onMousedown = () => {
      emit("change", "active", thisOption.value);
    };
    const onClick = () => {
      emit("change", "selected", thisOption.value);
    };
    const onHover = () => {
      emit("change", "highlighted", thisOption.value);
    };
    const isSelected = computed(() => {
      var _a;
      return props.id === ((_a = state.selected.value) == null ? void 0 : _a.id);
    });
    const isHighlighted = computed(() => {
      var _a;
      return props.id === ((_a = state.highlighted.value) == null ? void 0 : _a.id);
    });
    const isActive = computed(() => {
      var _a;
      return props.id === ((_a = state.active.value) == null ? void 0 : _a.id);
    });
    const rootClasses = computed(() => {
      return {
        "cdx-option--selected": !!isSelected.value,
        "cdx-option--active": !!isActive.value,
        "cdx-option--highlighted": !!isHighlighted.value,
        "cdx-option--enabled": !props.disabled,
        "cdx-option--disabled": !!props.disabled
      };
    });
    return {
      onMousedown,
      onClick,
      onHover,
      rootClasses,
      isSelected
    };
  }
});
const _hoisted_1$a = ["id", "aria-disabled", "aria-selected"];
function _sfc_render$a(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("li", {
    id: _ctx.id,
    role: "option",
    class: normalizeClass(["cdx-option", _ctx.rootClasses]),
    "aria-disabled": _ctx.disabled,
    "aria-selected": _ctx.isSelected,
    onMouseenter: _cache[0] || (_cache[0] = (...args) => _ctx.onHover && _ctx.onHover(...args)),
    onMousedown: _cache[1] || (_cache[1] = withModifiers((...args) => _ctx.onMousedown && _ctx.onMousedown(...args), ["prevent"])),
    onClick: _cache[2] || (_cache[2] = (...args) => _ctx.onClick && _ctx.onClick(...args))
  }, [
    renderSlot(_ctx.$slots, "default", {}, () => [
      createTextVNode(toDisplayString(_ctx.label || _ctx.value), 1)
    ])
  ], 42, _hoisted_1$a);
}
var CdxOption = /* @__PURE__ */ _export_sfc(_sfc_main$a, [["render", _sfc_render$a]]);
function useSplitAttributes(attrs, internalClasses = computed(() => {
  return {};
})) {
  const rootClasses = computed(() => {
    const classRecord = __objRest(internalClasses.value, []);
    if (attrs.class) {
      const providedClasses = attrs.class.split(" ");
      providedClasses.forEach((className) => {
        classRecord[className] = true;
      });
    }
    return classRecord;
  });
  const rootStyle = computed(() => {
    if ("style" in attrs) {
      return attrs.style;
    }
    return void 0;
  });
  const otherAttrs = computed(() => {
    const _a = attrs, { class: _ignoredClass, style: _ignoredStyle } = _a, attrsCopy = __objRest(_a, ["class", "style"]);
    return attrsCopy;
  });
  return {
    rootClasses,
    rootStyle,
    otherAttrs
  };
}
var TextInput_vue_vue_type_style_index_0_lang = "";
const textInputTypeValidator = makeStringTypeValidator(TextInputTypes);
const _sfc_main$9 = defineComponent({
  name: "CdxTextInput",
  components: { CdxIcon },
  inheritAttrs: false,
  expose: ["focus"],
  props: {
    modelValue: {
      type: [String, Number],
      default: ""
    },
    inputType: {
      type: String,
      default: "text",
      validator: textInputTypeValidator
    },
    disabled: {
      type: Boolean,
      default: false
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
      default: false
    }
  },
  emits: [
    "update:modelValue",
    "input",
    "change",
    "focus",
    "blur"
  ],
  setup(props, { emit, attrs }) {
    const wrappedModel = useModelWrapper(toRef(props, "modelValue"), emit);
    const isClearable = computed(() => {
      return props.clearable && !!wrappedModel.value && !props.disabled;
    });
    const internalClasses = computed(() => {
      return {
        "cdx-text-input--has-start-icon": !!props.startIcon,
        "cdx-text-input--has-end-icon": !!props.endIcon || props.clearable,
        "cdx-text-input--clearable": isClearable.value
      };
    });
    const {
      rootClasses,
      rootStyle,
      otherAttrs
    } = useSplitAttributes(attrs, internalClasses);
    const computedEndIcon = computed(() => {
      return isClearable.value ? cdxIconClear : props.endIcon;
    });
    const onEndIconClick = () => {
      if (isClearable.value) {
        wrappedModel.value = "";
      }
    };
    const onInput = (event) => {
      emit("input", event);
    };
    const onChange = (event) => {
      emit("change", event);
    };
    const onFocus = (event) => {
      emit("focus", event);
    };
    const onBlur = (event) => {
      emit("blur", event);
    };
    return {
      wrappedModel,
      isClearable,
      rootClasses,
      rootStyle,
      otherAttrs,
      computedEndIcon,
      onEndIconClick,
      onInput,
      onChange,
      onFocus,
      onBlur
    };
  },
  methods: {
    focus() {
      const input = this.$refs.input;
      input.focus();
    }
  }
});
const _hoisted_1$9 = ["type", "disabled"];
function _sfc_render$9(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_cdx_icon = resolveComponent("cdx-icon");
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-text-input", _ctx.rootClasses]),
    style: normalizeStyle(_ctx.rootStyle)
  }, [
    withDirectives(createElementVNode("input", mergeProps({
      ref: "input",
      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.wrappedModel = $event),
      class: "cdx-text-input__input"
    }, _ctx.otherAttrs, {
      type: _ctx.inputType,
      disabled: _ctx.disabled,
      onInput: _cache[1] || (_cache[1] = (...args) => _ctx.onInput && _ctx.onInput(...args)),
      onChange: _cache[2] || (_cache[2] = (...args) => _ctx.onChange && _ctx.onChange(...args)),
      onFocus: _cache[3] || (_cache[3] = (...args) => _ctx.onFocus && _ctx.onFocus(...args)),
      onBlur: _cache[4] || (_cache[4] = (...args) => _ctx.onBlur && _ctx.onBlur(...args))
    }), null, 16, _hoisted_1$9), [
      [vModelDynamic, _ctx.wrappedModel]
    ]),
    _ctx.startIcon ? (openBlock(), createBlock(_component_cdx_icon, {
      key: 0,
      icon: _ctx.startIcon,
      class: "cdx-text-input__start-icon"
    }, null, 8, ["icon"])) : createCommentVNode("", true),
    _ctx.isClearable || _ctx.endIcon ? (openBlock(), createBlock(_component_cdx_icon, {
      key: 1,
      icon: _ctx.computedEndIcon,
      class: "cdx-text-input__end-icon",
      onMousedown: _cache[5] || (_cache[5] = withModifiers(() => {
      }, ["prevent"])),
      onClick: _ctx.onEndIconClick
    }, null, 8, ["icon", "onClick"])) : createCommentVNode("", true)
  ], 6);
}
var CdxTextInput = /* @__PURE__ */ _export_sfc(_sfc_main$9, [["render", _sfc_render$9]]);
let counter = 0;
function useGeneratedId(identifier) {
  const vm = getCurrentInstance();
  const externalId = (vm == null ? void 0 : vm.props.id) || (vm == null ? void 0 : vm.attrs.id);
  let generatedId;
  if (identifier) {
    generatedId = `${LibraryPrefix}-${identifier}-${counter++}`;
  } else if (externalId) {
    generatedId = `${LibraryPrefix}-${externalId}-${counter++}`;
  } else {
    generatedId = `${LibraryPrefix}-${counter++}`;
  }
  return ref(generatedId);
}
function useMenu(options, modelWrapper, config) {
  const computedOptions = computed(() => {
    const generateOptionId = () => useGeneratedId("option").value;
    return options.value.map((option) => __spreadProps(__spreadValues({}, option), {
      id: generateOptionId()
    }));
  });
  const state = {
    selected: computed(() => computedOptions.value.find((option) => option.value === modelWrapper.value) || null),
    highlighted: ref(null),
    active: ref(null)
  };
  const expanded = ref(false);
  function onBlur() {
    expanded.value = false;
  }
  function handleOptionChange(menuState, option) {
    if (option && option.disabled) {
      return;
    }
    switch (menuState) {
      case "selected":
        modelWrapper.value = (option == null ? void 0 : option.value) || null;
        expanded.value = false;
        state.active.value = null;
        break;
      case "highlighted":
        state.highlighted.value = option || null;
        break;
      case "active":
        state.active.value = option || null;
        break;
    }
  }
  provide(MenuStateKey, state);
  provide(MenuOptionsKey, computedOptions);
  const highlightedOptionIndex = computed(() => {
    const highlightedOption = state.highlighted.value;
    if (highlightedOption === null) {
      return;
    }
    return computedOptions.value.findIndex((option) => highlightedOption.id === option.id);
  });
  function handleHighlight(highlightedOption) {
    if (!highlightedOption) {
      return;
    }
    handleOptionChange("highlighted", highlightedOption);
    if ((config == null ? void 0 : config.footerCallback) && highlightedOption.value === MenuFooterValue) {
      config.footerCallback();
      return;
    }
    if (config == null ? void 0 : config.inputValue) {
      config.inputValue.value = highlightedOption.label || highlightedOption.value;
      modelWrapper.value = highlightedOption.value;
    }
  }
  function highlightPrev() {
    var _a;
    const findPrevEnabled = (startIndex) => {
      let found;
      for (let index = startIndex - 1; index >= 0; index--) {
        if (!computedOptions.value[index].disabled) {
          found = computedOptions.value[index];
          break;
        }
      }
      return found;
    };
    const highlightedIndex = (_a = highlightedOptionIndex.value) != null ? _a : computedOptions.value.length;
    const prev = findPrevEnabled(highlightedIndex) || findPrevEnabled(computedOptions.value.length);
    handleHighlight(prev);
  }
  function highlightNext() {
    var _a;
    const findNextEnabled = (startIndex) => computedOptions.value.find((item, index) => !item.disabled && index > startIndex);
    const highlightedIndex = (_a = highlightedOptionIndex.value) != null ? _a : -1;
    const next = findNextEnabled(highlightedIndex) || findNextEnabled(-1);
    handleHighlight(next);
  }
  function handleKeyNavigation(e) {
    function handleExpandMenu() {
      expanded.value = true;
      if (state.selected.value) {
        handleOptionChange("highlighted", state.selected.value);
      }
    }
    switch (e.key) {
      case "Enter":
      case " ":
        e.preventDefault();
        e.stopPropagation();
        if (expanded.value) {
          if (state.highlighted.value) {
            modelWrapper.value = state.highlighted.value.value;
          }
          expanded.value = false;
        } else {
          handleExpandMenu();
        }
        break;
      case "Tab":
        if (expanded.value) {
          if (state.highlighted.value) {
            modelWrapper.value = state.highlighted.value.value;
          }
          expanded.value = false;
        }
        break;
      case "ArrowUp":
        e.preventDefault();
        e.stopPropagation();
        if (expanded.value) {
          highlightPrev();
        } else {
          handleExpandMenu();
        }
        break;
      case "ArrowDown":
        e.preventDefault();
        e.stopPropagation();
        if (expanded.value) {
          highlightNext();
        } else {
          handleExpandMenu();
        }
        break;
      case "Escape":
        e.preventDefault();
        e.stopPropagation();
        expanded.value = false;
        break;
    }
  }
  watch(expanded, (newVal) => {
    if (!newVal && state.highlighted.value) {
      state.highlighted.value = null;
    }
  });
  return {
    computedOptions,
    state,
    expanded,
    onBlur,
    handleOptionChange,
    handleKeyNavigation
  };
}
var Combobox_vue_vue_type_style_index_0_lang = "";
const _sfc_main$8 = defineComponent({
  name: "CdxCombobox",
  components: {
    CdxButton,
    CdxIcon,
    CdxOption,
    CdxTextInput
  },
  inheritAttrs: false,
  props: {
    options: {
      type: Array,
      required: true
    },
    modelValue: {
      type: [String, Number],
      default: ""
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(props, context) {
    const input = ref();
    const menuId = useGeneratedId("combobox");
    const modelWrapper = useModelWrapper(toRef(props, "modelValue"), context.emit);
    const expanderClicked = ref(false);
    const {
      computedOptions,
      state,
      expanded,
      handleOptionChange,
      handleKeyNavigation
    } = useMenu(toRef(props, "options"), modelWrapper);
    const internalClasses = computed(() => {
      return {
        "cdx-combobox--disabled": props.disabled
      };
    });
    const {
      rootClasses,
      rootStyle,
      otherAttrs
    } = useSplitAttributes(context.attrs, internalClasses);
    function onInputFocus() {
      if (expanderClicked.value && expanded.value) {
        expanded.value = false;
      } else if (computedOptions.value.length > 0 || context.slots.footer) {
        expanded.value = true;
      }
    }
    function onInputBlur() {
      if (expanderClicked.value && expanded.value) {
        expanded.value = true;
      } else {
        expanded.value = false;
      }
    }
    function onButtonMousedown() {
      if (props.disabled) {
        return;
      }
      expanderClicked.value = true;
    }
    function onButtonClick() {
      var _a;
      if (props.disabled) {
        return;
      }
      (_a = input.value) == null ? void 0 : _a.focus();
    }
    function onKeyNavigation(e) {
      if (props.disabled || computedOptions.value.length === 0 || e.key === " " && expanded.value) {
        return;
      }
      handleKeyNavigation(e);
    }
    watch(expanded, () => {
      expanderClicked.value = false;
    });
    return {
      input,
      menuId,
      modelWrapper,
      computedOptions,
      state,
      expanded,
      onInputFocus,
      onInputBlur,
      handleOptionChange,
      onKeyNavigation,
      onButtonClick,
      onButtonMousedown,
      cdxIconExpand,
      rootClasses,
      rootStyle,
      otherAttrs
    };
  }
});
const _hoisted_1$8 = { class: "cdx-combobox__input-wrapper" };
const _hoisted_2$8 = ["id"];
const _hoisted_3$5 = {
  key: 0,
  class: "cdx-option"
};
function _sfc_render$8(_ctx, _cache, $props, $setup, $data, $options) {
  var _a;
  const _component_cdx_text_input = resolveComponent("cdx-text-input");
  const _component_cdx_icon = resolveComponent("cdx-icon");
  const _component_cdx_button = resolveComponent("cdx-button");
  const _component_cdx_option = resolveComponent("cdx-option");
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-combobox", _ctx.rootClasses]),
    style: normalizeStyle(_ctx.rootStyle)
  }, [
    createElementVNode("div", _hoisted_1$8, [
      createVNode(_component_cdx_text_input, mergeProps({
        ref: "input",
        modelValue: _ctx.modelWrapper,
        "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.modelWrapper = $event)
      }, _ctx.otherAttrs, {
        class: "cdx-combobox__input",
        "aria-activedescendant": (_a = _ctx.state.highlighted.value) == null ? void 0 : _a.id,
        "aria-disabled": _ctx.disabled,
        "aria-expanded": _ctx.expanded,
        "aria-owns": _ctx.menuId,
        disabled: _ctx.disabled,
        "aria-autocomplete": "list",
        autocomplete: "off",
        role: "combobox",
        tabindex: "0",
        onKeydown: withKeys(_ctx.onKeyNavigation, ["space", "enter", "up", "down", "tab", "esc"]),
        onFocus: _ctx.onInputFocus,
        onBlur: _ctx.onInputBlur
      }), null, 16, ["modelValue", "aria-activedescendant", "aria-disabled", "aria-expanded", "aria-owns", "disabled", "onKeydown", "onFocus", "onBlur"]),
      createVNode(_component_cdx_button, {
        class: "cdx-combobox__expand-button",
        disabled: _ctx.disabled,
        tabindex: "-1",
        onMousedown: _ctx.onButtonMousedown,
        onClick: _ctx.onButtonClick
      }, {
        default: withCtx(() => [
          createVNode(_component_cdx_icon, {
            class: "cdx-combobox__expand-icon",
            icon: _ctx.cdxIconExpand
          }, null, 8, ["icon"])
        ]),
        _: 1
      }, 8, ["disabled", "onMousedown", "onClick"])
    ]),
    withDirectives(createElementVNode("ul", {
      id: _ctx.menuId,
      class: "cdx-combobox__menu",
      role: "listbox",
      "aria-multiselectable": "false"
    }, [
      (openBlock(true), createElementBlock(Fragment, null, renderList(_ctx.computedOptions, (option, index) => {
        return openBlock(), createBlock(_component_cdx_option, mergeProps(option, {
          key: index,
          onChange: _ctx.handleOptionChange
        }), {
          default: withCtx(() => [
            renderSlot(_ctx.$slots, "menu-option", { option })
          ]),
          _: 2
        }, 1040, ["onChange"]);
      }), 128)),
      _ctx.$slots.footer ? (openBlock(), createElementBlock("li", _hoisted_3$5, [
        renderSlot(_ctx.$slots, "footer")
      ])) : createCommentVNode("", true)
    ], 8, _hoisted_2$8), [
      [vShow, _ctx.expanded]
    ])
  ], 6);
}
var Combobox = /* @__PURE__ */ _export_sfc(_sfc_main$8, [["render", _sfc_render$8]]);
function useStringHelpers() {
  function regExpEscape(value) {
    return value.replace(/([\\{}()|.?*+\-^$[\]])/g, "\\$1");
  }
  const COMBINING_MARK = "[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]";
  function splitStringAtMatch(query, title) {
    if (!query) {
      return [title, "", ""];
    }
    const sanitizedQuery = regExpEscape(query);
    const match = title.match(new RegExp(sanitizedQuery + COMBINING_MARK + "*", "i"));
    if (!match || match.index === void 0) {
      return [title, "", ""];
    }
    const matchStartIndex = match.index;
    const matchEndIndex = matchStartIndex + match[0].length;
    const highlightedTitle = title.slice(matchStartIndex, matchEndIndex);
    const beforeHighlight = title.slice(0, matchStartIndex);
    const afterHighlight = title.slice(matchEndIndex, title.length);
    return [beforeHighlight, highlightedTitle, afterHighlight];
  }
  return {
    regExpEscape,
    splitStringAtMatch
  };
}
var ListTileLabel_vue_vue_type_style_index_0_lang = "";
const _sfc_main$7 = defineComponent({
  name: "CdxListTileLabel",
  props: {
    label: {
      type: [String, Number],
      required: true
    },
    searchQuery: {
      type: String,
      default: ""
    },
    highlightQuery: {
      type: Boolean,
      default: false
    }
  },
  setup: (props) => {
    const { splitStringAtMatch } = useStringHelpers();
    const labelChunks = computed(() => {
      if (props.highlightQuery) {
        return splitStringAtMatch(props.searchQuery, String(props.label));
      }
      return ["", String(props.label), ""];
    });
    return {
      labelChunks
    };
  }
});
const _hoisted_1$7 = { class: "cdx-list-tile-label" };
const _hoisted_2$7 = { class: "cdx-list-tile-label__match" };
function _sfc_render$7(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", _hoisted_1$7, [
    createTextVNode(toDisplayString(_ctx.labelChunks[0]), 1),
    createElementVNode("span", _hoisted_2$7, toDisplayString(_ctx.labelChunks[1]), 1),
    createTextVNode(toDisplayString(_ctx.labelChunks[2]), 1)
  ]);
}
var CdxListTileLabel = /* @__PURE__ */ _export_sfc(_sfc_main$7, [["render", _sfc_render$7]]);
var ListTile_vue_vue_type_style_index_0_lang = "";
const _sfc_main$6 = defineComponent({
  name: "CdxListTile",
  components: {
    CdxIcon,
    CdxListTileLabel
  },
  props: {
    item: {
      type: Object,
      required: true
    },
    searchQuery: {
      type: String,
      default: ""
    },
    highlightQuery: {
      type: Boolean,
      default: false
    },
    hideThumbnail: {
      type: Boolean,
      default: false
    },
    hideDescription: {
      type: Boolean,
      default: false
    }
  },
  setup: (props) => {
    const itemText = computed(() => props.item.label || props.item.value);
    const thumbnailBackgroundImage = computed(() => props.item.thumbnail ? `url(${props.item.thumbnail.url})` : "");
    return {
      itemText,
      thumbnailBackgroundImage,
      defaultThumbnailIcon: cdxIconImageLayoutFrameless
    };
  }
});
const _hoisted_1$6 = ["href"];
const _hoisted_2$6 = {
  key: 1,
  class: "cdx-list-tile__thumbnail-placeholder"
};
const _hoisted_3$4 = { class: "cdx-list-tile__text" };
const _hoisted_4$2 = {
  key: 0,
  class: "cdx-list-tile__description"
};
function _sfc_render$6(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_cdx_icon = resolveComponent("cdx-icon");
  const _component_cdx_list_tile_label = resolveComponent("cdx-list-tile-label");
  return openBlock(), createElementBlock("a", {
    href: _ctx.item.url,
    class: "cdx-list-tile"
  }, [
    !_ctx.hideThumbnail && _ctx.item.thumbnail ? (openBlock(), createElementBlock("span", {
      key: 0,
      style: normalizeStyle({ backgroundImage: _ctx.thumbnailBackgroundImage }),
      class: "cdx-list-tile__thumbnail"
    }, null, 4)) : !_ctx.hideThumbnail ? (openBlock(), createElementBlock("span", _hoisted_2$6, [
      createVNode(_component_cdx_icon, {
        icon: _ctx.defaultThumbnailIcon,
        class: "cdx-list-tile__thumbnail-icon"
      }, null, 8, ["icon"])
    ])) : createCommentVNode("", true),
    createElementVNode("span", _hoisted_3$4, [
      createVNode(_component_cdx_list_tile_label, {
        label: _ctx.itemText,
        "search-query": _ctx.searchQuery,
        "highlight-query": _ctx.highlightQuery
      }, null, 8, ["label", "search-query", "highlight-query"]),
      !_ctx.hideDescription && _ctx.item.description ? (openBlock(), createElementBlock("span", _hoisted_4$2, toDisplayString(_ctx.item.description), 1)) : createCommentVNode("", true)
    ])
  ], 8, _hoisted_1$6);
}
var CdxListTile = /* @__PURE__ */ _export_sfc(_sfc_main$6, [["render", _sfc_render$6]]);
var Lookup_vue_vue_type_style_index_0_lang = "";
const _sfc_main$5 = defineComponent({
  name: "CdxLookup",
  components: {
    CdxTextInput,
    CdxOption
  },
  inheritAttrs: false,
  props: {
    modelValue: {
      type: [String, Number, null],
      required: true
    },
    options: {
      type: Array,
      default: () => []
    },
    initialInputValue: {
      type: [String, Number],
      default: ""
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "update:modelValue",
    "new-input"
  ],
  setup: (props, context) => {
    const menuId = useGeneratedId("lookup-menu");
    const pending = ref(false);
    const modelValueProp = toRef(props, "modelValue");
    const selectionModelWrapper = useModelWrapper(modelValueProp, context.emit);
    const inputValue = ref(props.initialInputValue);
    const {
      computedOptions,
      state,
      expanded,
      onBlur,
      handleOptionChange,
      handleKeyNavigation
    } = useMenu(toRef(props, "options"), selectionModelWrapper);
    const internalClasses = computed(() => {
      return {
        "cdx-lookup--disabled": props.disabled,
        "cdx-lookup--pending": pending.value
      };
    });
    const {
      rootClasses,
      rootStyle,
      otherAttrs
    } = useSplitAttributes(context.attrs, internalClasses);
    function onUpdateInput(value) {
      if (value || value === 0) {
        pending.value = true;
      }
      context.emit("new-input", value);
    }
    function onFocus() {
      if (computedOptions.value.length > 0 || context.slots.footer) {
        expanded.value = true;
      }
    }
    function onKeyNavigation(e) {
      if (props.disabled || computedOptions.value.length === 0 && !context.slots.footer || e.key === " " && expanded.value) {
        return;
      }
      handleKeyNavigation(e);
    }
    watch(computedOptions, (newVal) => {
      pending.value = false;
      function inputValueIsSelection() {
        var _a, _b;
        return ((_a = state.selected.value) == null ? void 0 : _a.label) === inputValue.value || ((_b = state.selected.value) == null ? void 0 : _b.value) === inputValue.value;
      }
      if (newVal.length > 0 && !inputValueIsSelection() || newVal.length === 0 && context.slots.footer) {
        expanded.value = true;
      }
      if (newVal.length === 0 && !context.slots.footer) {
        expanded.value = false;
      }
    });
    watch(modelValueProp, (newVal) => {
      if (newVal !== null) {
        inputValue.value = state.selected.value ? state.selected.value.label || state.selected.value.value : "";
      }
    });
    watch(inputValue, (newVal) => {
      if (state.selected.value && state.selected.value.label !== newVal && state.selected.value.value !== newVal) {
        selectionModelWrapper.value = null;
      }
      if (newVal === "") {
        expanded.value = false;
      }
    });
    return {
      menuId,
      inputValue,
      computedOptions,
      state,
      expanded,
      onBlur,
      handleOptionChange,
      rootClasses,
      rootStyle,
      otherAttrs,
      onUpdateInput,
      onFocus,
      onKeyNavigation
    };
  }
});
const _hoisted_1$5 = ["id"];
const _hoisted_2$5 = {
  key: 0,
  class: "cdx-option"
};
function _sfc_render$5(_ctx, _cache, $props, $setup, $data, $options) {
  var _a;
  const _component_cdx_text_input = resolveComponent("cdx-text-input");
  const _component_cdx_option = resolveComponent("cdx-option");
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-lookup", _ctx.rootClasses]),
    style: normalizeStyle(_ctx.rootStyle)
  }, [
    createVNode(_component_cdx_text_input, mergeProps({
      modelValue: _ctx.inputValue,
      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.inputValue = $event)
    }, _ctx.otherAttrs, {
      role: "combobox",
      autocomplete: "off",
      "aria-autocomplete": "list",
      "aria-owns": _ctx.menuId,
      "aria-expanded": _ctx.expanded,
      "aria-activedescendant": (_a = _ctx.state.highlighted.value) == null ? void 0 : _a.id,
      disabled: _ctx.disabled,
      "onUpdate:modelValue": _ctx.onUpdateInput,
      onFocus: _ctx.onFocus,
      onBlur: _ctx.onBlur,
      onKeydown: withKeys(_ctx.onKeyNavigation, ["space", "enter", "up", "down", "tab", "esc"])
    }), null, 16, ["modelValue", "aria-owns", "aria-expanded", "aria-activedescendant", "disabled", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
    withDirectives(createElementVNode("ul", {
      id: _ctx.menuId,
      class: "cdx-lookup__menu",
      role: "listbox",
      "aria-multiselectable": "false"
    }, [
      (openBlock(true), createElementBlock(Fragment, null, renderList(_ctx.computedOptions, (option, index) => {
        return openBlock(), createBlock(_component_cdx_option, mergeProps(option, {
          key: index,
          onChange: _ctx.handleOptionChange
        }), {
          default: withCtx(() => [
            renderSlot(_ctx.$slots, "menu-option", { option })
          ]),
          _: 2
        }, 1040, ["onChange"]);
      }), 128)),
      _ctx.$slots.footer ? (openBlock(), createElementBlock("li", _hoisted_2$5, [
        renderSlot(_ctx.$slots, "footer")
      ])) : createCommentVNode("", true)
    ], 8, _hoisted_1$5), [
      [vShow, _ctx.expanded]
    ])
  ], 6);
}
var Lookup = /* @__PURE__ */ _export_sfc(_sfc_main$5, [["render", _sfc_render$5]]);
var Message_vue_vue_type_style_index_0_lang = "";
const messageTypeValidator = makeStringTypeValidator(MessageTypes);
const iconMap = {
  notice: cdxIconInfoFilled,
  error: cdxIconError,
  warning: cdxIconAlert,
  success: cdxIconCheck
};
const _sfc_main$4 = defineComponent({
  name: "CdxMessage",
  components: { CdxButton, CdxIcon },
  props: {
    type: {
      type: String,
      default: "notice",
      validator: messageTypeValidator
    },
    inline: {
      type: Boolean,
      default: false
    },
    dismissButtonLabel: {
      type: String,
      default: ""
    },
    icon: {
      type: [String, Object],
      default: null
    }
  },
  emits: [
    "dismissed"
  ],
  setup(props, { emit }) {
    const dismissed = ref(false);
    const dismissable = computed(() => props.type === "notice" && props.inline === false && props.dismissButtonLabel.length > 0);
    const rootClasses = computed(() => {
      return {
        "cdx-message--inline": props.inline,
        "cdx-message--block": !props.inline,
        "cdx-message--dismissable": dismissable.value,
        [`cdx-message--${props.type}`]: true
      };
    });
    const computedIcon = computed(() => props.icon || iconMap[props.type]);
    function onDismiss() {
      dismissed.value = true;
      emit("dismissed");
    }
    return {
      dismissed,
      dismissable,
      rootClasses,
      computedIcon,
      onDismiss,
      cdxIconClose
    };
  }
});
const _hoisted_1$4 = ["aria-live", "role"];
const _hoisted_2$4 = { class: "cdx-message__content" };
function _sfc_render$4(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_cdx_icon = resolveComponent("cdx-icon");
  const _component_cdx_button = resolveComponent("cdx-button");
  return !_ctx.dismissed ? (openBlock(), createElementBlock("div", {
    key: 0,
    class: normalizeClass(["cdx-message", _ctx.rootClasses]),
    "aria-live": _ctx.type !== "error" ? "polite" : void 0,
    role: _ctx.type === "error" ? "alert" : void 0
  }, [
    createVNode(_component_cdx_icon, {
      class: "cdx-message__icon",
      icon: _ctx.computedIcon
    }, null, 8, ["icon"]),
    createElementVNode("div", _hoisted_2$4, [
      renderSlot(_ctx.$slots, "default")
    ]),
    _ctx.dismissable ? (openBlock(), createBlock(_component_cdx_button, {
      key: 0,
      class: "cdx-message__dismiss",
      type: "quiet",
      "aria-label": _ctx.dismissButtonLabel,
      onClick: _ctx.onDismiss
    }, {
      default: withCtx(() => [
        createVNode(_component_cdx_icon, {
          icon: _ctx.cdxIconClose,
          "icon-label": _ctx.dismissButtonLabel
        }, null, 8, ["icon", "icon-label"])
      ]),
      _: 1
    }, 8, ["aria-label", "onClick"])) : createCommentVNode("", true)
  ], 10, _hoisted_1$4)) : createCommentVNode("", true);
}
var Message = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["render", _sfc_render$4]]);
var Radio_vue_vue_type_style_index_0_lang = "";
const _sfc_main$3 = defineComponent({
  name: "CdxRadio",
  props: {
    modelValue: {
      type: [String, Number, Boolean],
      default: ""
    },
    inputValue: {
      type: [String, Number, Boolean],
      default: false
    },
    name: {
      type: String,
      default: ""
    },
    disabled: {
      type: Boolean,
      default: false
    },
    inline: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(props, { emit }) {
    const rootClasses = computed(() => {
      return {
        "cdx-radio--inline": !!props.inline
      };
    });
    const input = ref();
    const focusInput = () => {
      input.value.focus();
    };
    const wrappedModel = useModelWrapper(toRef(props, "modelValue"), emit);
    return {
      rootClasses,
      input,
      focusInput,
      wrappedModel
    };
  }
});
const _hoisted_1$3 = ["aria-disabled"];
const _hoisted_2$3 = ["name", "value", "disabled"];
const _hoisted_3$3 = /* @__PURE__ */ createElementVNode("span", { class: "cdx-radio__icon" }, null, -1);
const _hoisted_4$1 = { class: "cdx-radio__label-content" };
function _sfc_render$3(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", {
    class: normalizeClass(["cdx-radio", _ctx.rootClasses])
  }, [
    createElementVNode("label", {
      class: "cdx-radio__label",
      "aria-disabled": _ctx.disabled,
      onClick: _cache[1] || (_cache[1] = (...args) => _ctx.focusInput && _ctx.focusInput(...args))
    }, [
      withDirectives(createElementVNode("input", {
        ref: "input",
        "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.wrappedModel = $event),
        class: "cdx-radio__input",
        type: "radio",
        name: _ctx.name,
        value: _ctx.inputValue,
        disabled: _ctx.disabled
      }, null, 8, _hoisted_2$3), [
        [vModelRadio, _ctx.wrappedModel]
      ]),
      _hoisted_3$3,
      createElementVNode("span", _hoisted_4$1, [
        renderSlot(_ctx.$slots, "default")
      ])
    ], 8, _hoisted_1$3)
  ], 2);
}
var Radio = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["render", _sfc_render$3]]);
var Select_vue_vue_type_style_index_0_lang = "";
const _sfc_main$2 = defineComponent({
  name: "CdxSelect",
  components: {
    CdxIcon,
    CdxOption
  },
  props: {
    options: {
      type: Array,
      required: true
    },
    modelValue: {
      type: [String, Number, null],
      default: null
    },
    defaultLabel: {
      type: String,
      default: ""
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(props, context) {
    const handle = ref();
    const handleId = useGeneratedId("select-handle");
    const menuId = useGeneratedId("select-menu");
    const modelWrapper = useModelWrapper(toRef(props, "modelValue"), context.emit);
    const {
      computedOptions,
      state,
      expanded,
      onBlur,
      handleOptionChange,
      handleKeyNavigation
    } = useMenu(toRef(props, "options"), modelWrapper);
    const currentLabel = computed(() => {
      return state.selected.value ? state.selected.value.label || state.selected.value.value : props.defaultLabel;
    });
    const rootClasses = computed(() => {
      return {
        "cdx-select--disabled": props.disabled,
        "cdx-select--expanded": expanded.value,
        "cdx-select--value-selected": !!state.selected.value,
        "cdx-select--no-selections": !state.selected.value
      };
    });
    function onHandleClick() {
      var _a;
      if (props.disabled) {
        return;
      }
      expanded.value = !expanded.value;
      (_a = handle.value) == null ? void 0 : _a.focus();
    }
    function onKeyNavigation(e) {
      if (props.disabled) {
        return;
      }
      handleKeyNavigation(e);
    }
    return {
      handle,
      handleId,
      menuId,
      computedOptions,
      state,
      expanded,
      onBlur,
      handleOptionChange,
      currentLabel,
      rootClasses,
      onHandleClick,
      cdxIconExpand,
      onKeyNavigation
    };
  }
});
const _hoisted_1$2 = ["aria-owns", "aria-labelledby", "aria-activedescendant", "aria-expanded", "aria-disabled"];
const _hoisted_2$2 = ["id"];
const _hoisted_3$2 = ["id"];
function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
  var _a;
  const _component_cdx_icon = resolveComponent("cdx-icon");
  const _component_cdx_option = resolveComponent("cdx-option");
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-select", _ctx.rootClasses])
  }, [
    createElementVNode("div", {
      ref: "handle",
      class: "cdx-select__handle",
      tabindex: "0",
      role: "combobox",
      "aria-autocomplete": "list",
      "aria-owns": _ctx.menuId,
      "aria-labelledby": _ctx.handleId,
      "aria-activedescendant": (_a = _ctx.state.highlighted.value) == null ? void 0 : _a.id,
      "aria-haspopup": "listbox",
      "aria-expanded": _ctx.expanded,
      "aria-disabled": _ctx.disabled,
      onClick: _cache[0] || (_cache[0] = (...args) => _ctx.onHandleClick && _ctx.onHandleClick(...args)),
      onBlur: _cache[1] || (_cache[1] = (...args) => _ctx.onBlur && _ctx.onBlur(...args)),
      onKeydown: _cache[2] || (_cache[2] = withKeys((...args) => _ctx.onKeyNavigation && _ctx.onKeyNavigation(...args), ["space", "enter", "up", "down", "tab", "esc"]))
    }, [
      createElementVNode("span", {
        id: _ctx.handleId,
        role: "textbox",
        "aria-readonly": "true"
      }, [
        renderSlot(_ctx.$slots, "label", {
          selectedOption: _ctx.state.selected.value,
          defaultLabel: _ctx.defaultLabel
        }, () => [
          createTextVNode(toDisplayString(_ctx.currentLabel), 1)
        ])
      ], 8, _hoisted_2$2),
      createVNode(_component_cdx_icon, {
        icon: _ctx.cdxIconExpand,
        class: "cdx-select__indicator"
      }, null, 8, ["icon"])
    ], 40, _hoisted_1$2),
    withDirectives(createElementVNode("ul", {
      id: _ctx.menuId,
      class: "cdx-select__menu",
      role: "listbox"
    }, [
      (openBlock(true), createElementBlock(Fragment, null, renderList(_ctx.computedOptions, (option, index) => {
        return openBlock(), createBlock(_component_cdx_option, mergeProps(option, {
          key: index,
          onChange: _ctx.handleOptionChange
        }), {
          default: withCtx(() => [
            renderSlot(_ctx.$slots, "menu-option", { option })
          ]),
          _: 2
        }, 1040, ["onChange"]);
      }), 128))
    ], 8, _hoisted_3$2), [
      [vShow, _ctx.expanded]
    ])
  ], 2);
}
var Select = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["render", _sfc_render$2]]);
var ToggleSwitch_vue_vue_type_style_index_0_lang = "";
const _sfc_main$1 = defineComponent({
  name: "CdxToggleSwitch",
  inheritAttrs: false,
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "update:modelValue"
  ],
  setup(props, context) {
    const input = ref();
    const inputId = useGeneratedId("toggle-switch");
    const {
      rootClasses,
      rootStyle,
      otherAttrs
    } = useSplitAttributes(context.attrs);
    const wrappedModel = useModelWrapper(toRef(props, "modelValue"), context.emit);
    const clickInput = () => {
      input.value.click();
    };
    return {
      input,
      inputId,
      rootClasses,
      rootStyle,
      otherAttrs,
      wrappedModel,
      clickInput
    };
  }
});
const _hoisted_1$1 = ["for", "aria-disabled"];
const _hoisted_2$1 = ["id", "disabled"];
const _hoisted_3$1 = /* @__PURE__ */ createElementVNode("span", { class: "cdx-toggle-switch__switch" }, [
  /* @__PURE__ */ createElementVNode("span", { class: "cdx-toggle-switch__switch__grip" })
], -1);
function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("span", {
    class: normalizeClass(["cdx-toggle-switch", _ctx.rootClasses]),
    style: normalizeStyle(_ctx.rootStyle)
  }, [
    _ctx.$slots.default ? (openBlock(), createElementBlock("label", {
      key: 0,
      for: _ctx.inputId,
      class: "cdx-toggle-switch__label",
      "aria-disabled": _ctx.disabled
    }, [
      renderSlot(_ctx.$slots, "default")
    ], 8, _hoisted_1$1)) : createCommentVNode("", true),
    withDirectives(createElementVNode("input", mergeProps({
      id: _ctx.inputId,
      ref: "input",
      "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.wrappedModel = $event),
      class: "cdx-toggle-switch__input",
      type: "checkbox",
      disabled: _ctx.disabled
    }, _ctx.otherAttrs, {
      onKeydown: _cache[1] || (_cache[1] = withKeys(withModifiers((...args) => _ctx.clickInput && _ctx.clickInput(...args), ["prevent"]), ["enter"]))
    }), null, 16, _hoisted_2$1), [
      [vModelCheckbox, _ctx.wrappedModel]
    ]),
    _hoisted_3$1
  ], 6);
}
var ToggleSwitch = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["render", _sfc_render$1]]);
var TypeaheadSearch_vue_vue_type_style_index_0_lang = "";
const _sfc_main = defineComponent({
  name: "CdxTypeaheadSearch",
  components: {
    CdxButton,
    CdxIcon,
    CdxListTile,
    CdxOption,
    CdxTextInput
  },
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      required: true
    },
    formAction: {
      type: String,
      required: true
    },
    buttonLabel: {
      type: String,
      required: true
    },
    searchResultsLabel: {
      type: String,
      required: true
    },
    searchResults: {
      type: Array,
      default: () => []
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
      default: DebounceInterval
    },
    highlightQuery: {
      type: Boolean,
      default: false
    },
    hideThumbnail: {
      type: Boolean,
      default: false
    },
    hideDescription: {
      type: Boolean,
      default: false
    },
    autoExpandWidth: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    "new-input",
    "search-result-click",
    "submit"
  ],
  setup(props, context) {
    const { searchResults, searchFooterUrl, debounceInterval } = toRefs(props);
    const menuId = useGeneratedId("typeahead-search-menu");
    const isActive = ref(false);
    const inputValue = ref(props.initialInputValue);
    const searchQuery = ref("");
    const internalSelection = ref(null);
    let menuData = null;
    const selection = computed({
      get: () => internalSelection.value,
      set: (value) => {
        internalSelection.value = value;
        if (value !== null && menuData !== null) {
          const selectedOption = menuData.state.selected.value;
          inputValue.value = selectedOption ? selectedOption.label || String(selectedOption.value) : "";
        }
      }
    });
    const searchResultsWithFooter = computed(() => searchFooterUrl.value ? searchResults.value.concat([
      { value: MenuFooterValue, url: searchFooterUrl.value }
    ]) : searchResults.value);
    const {
      computedOptions,
      state,
      expanded,
      handleOptionChange,
      handleKeyNavigation
    } = menuData = useMenu(searchResultsWithFooter, selection, {
      inputValue,
      footerCallback: () => {
        inputValue.value = searchQuery.value;
        selection.value = null;
      }
    });
    const computedSearchResults = computedOptions;
    const internalClasses = computed(() => {
      return {
        "cdx-typeahead-search--active": isActive.value,
        "cdx-typeahead-search--show-thumbnail": !props.hideThumbnail,
        "cdx-typeahead-search--expanded": expanded.value,
        "cdx-typeahead-search--auto-expand-width": !props.hideThumbnail && props.autoExpandWidth
      };
    });
    const {
      rootClasses,
      rootStyle,
      otherAttrs
    } = useSplitAttributes(context.attrs, internalClasses);
    const debounceId = ref();
    function onUpdateInputValue(value, immediate = false) {
      inputValue.value = value;
      const handleUpdateInputValue = () => {
        context.emit("new-input", inputValue.value);
      };
      if (debounceId.value) {
        clearTimeout(debounceId.value);
      }
      if (immediate) {
        handleUpdateInputValue();
      } else {
        debounceId.value = setTimeout(() => {
          handleUpdateInputValue();
        }, debounceInterval.value);
      }
    }
    function onFocus() {
      isActive.value = true;
      if (searchQuery.value) {
        expanded.value = true;
      }
    }
    function onBlur() {
      isActive.value = false;
      expanded.value = false;
    }
    function onSearchResultClick(searchResult) {
      const _a = searchResult, { id } = _a, resultWithoutId = __objRest(_a, ["id"]);
      const emittedResult = resultWithoutId.value !== MenuFooterValue ? resultWithoutId : null;
      const searchResultClickEvent = {
        searchResult: emittedResult,
        index: computedOptions.value.indexOf(searchResult),
        numberOfResults: searchResults.value.length
      };
      context.emit("search-result-click", searchResultClickEvent);
    }
    function onSearchFooterClick(footerOption) {
      expanded.value = false;
      handleOptionChange("active");
      onSearchResultClick(footerOption);
    }
    function onSubmit() {
      let emittedResult = null;
      let selectedResultIndex = -1;
      if (state.selected.value) {
        const _a = state.selected.value, { id } = _a, resultWithoutId = __objRest(_a, ["id"]);
        emittedResult = resultWithoutId;
        selectedResultIndex = computedOptions.value.indexOf(state.selected.value);
      }
      const submitEvent = {
        searchResult: emittedResult,
        index: selectedResultIndex,
        numberOfResults: searchResults.value.length
      };
      context.emit("submit", submitEvent);
    }
    function onKeyNavigation(e) {
      if (!searchQuery.value || e.key === " " && expanded.value) {
        return;
      }
      switch (e.key) {
        case "Enter":
          if (state.highlighted.value) {
            if (state.highlighted.value.value === MenuFooterValue) {
              window.location.assign(searchFooterUrl.value);
            } else {
              handleOptionChange("selected", state.highlighted.value);
            }
          }
          expanded.value = false;
          break;
        case "Tab":
          expanded.value = false;
          break;
        default:
          handleKeyNavigation(e);
          break;
      }
    }
    onMounted(() => {
      if (props.initialInputValue) {
        onUpdateInputValue(props.initialInputValue, true);
      }
    });
    watch(computedOptions, (newVal) => {
      searchQuery.value = inputValue.value.trim();
      function inputValueIsSelection() {
        var _a, _b;
        return ((_a = state.selected.value) == null ? void 0 : _a.label) === inputValue.value || String((_b = state.selected.value) == null ? void 0 : _b.value) === inputValue.value;
      }
      if (newVal.length > 0 && isActive.value && !inputValueIsSelection()) {
        expanded.value = true;
      }
    });
    watch(inputValue, (newVal) => {
      if (state.selected.value && state.selected.value.label !== newVal && state.selected.value.value !== newVal) {
        selection.value = null;
      }
      if (newVal === "") {
        expanded.value = false;
      }
    });
    return {
      menuId,
      inputValue,
      searchQuery,
      computedSearchResults,
      state,
      expanded,
      handleOptionChange,
      rootClasses,
      rootStyle,
      otherAttrs,
      onUpdateInputValue,
      onFocus,
      onBlur,
      onSearchResultClick,
      onSearchFooterClick,
      onSubmit,
      onKeyNavigation,
      MenuFooterValue,
      searchIcon: cdxIconSearch,
      articleIcon: cdxIconArticleSearch
    };
  },
  methods: {
    focus() {
      const textInput = this.$refs.textInput;
      textInput.focus();
    }
  }
});
const _hoisted_1 = ["id", "action"];
const _hoisted_2 = { class: "cdx-typeahead-search__wrapper" };
const _hoisted_3 = ["id", "aria-label"];
const _hoisted_4 = ["href", "onClickCapture"];
const _hoisted_5 = { class: "cdx-typeahead-search__search-footer__text" };
const _hoisted_6 = { class: "cdx-typeahead-search__search-footer__query" };
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
  var _a;
  const _component_cdx_text_input = resolveComponent("cdx-text-input");
  const _component_cdx_list_tile = resolveComponent("cdx-list-tile");
  const _component_cdx_icon = resolveComponent("cdx-icon");
  const _component_cdx_option = resolveComponent("cdx-option");
  const _component_cdx_button = resolveComponent("cdx-button");
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-typeahead-search", _ctx.rootClasses]),
    style: normalizeStyle(_ctx.rootStyle)
  }, [
    createElementVNode("form", {
      id: _ctx.id,
      class: "cdx-typeahead-search__form",
      action: _ctx.formAction,
      onSubmit: _cache[1] || (_cache[1] = (...args) => _ctx.onSubmit && _ctx.onSubmit(...args))
    }, [
      createElementVNode("div", _hoisted_2, [
        createVNode(_component_cdx_text_input, mergeProps({
          ref: "textInput",
          modelValue: _ctx.inputValue,
          "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.inputValue = $event)
        }, _ctx.otherAttrs, {
          "start-icon": _ctx.searchIcon,
          "input-type": "search",
          name: "search",
          role: "combobox",
          autocomplete: "off",
          "aria-autocomplete": "list",
          "aria-owns": _ctx.menuId,
          "aria-expanded": _ctx.expanded,
          "aria-activedescendant": (_a = _ctx.state.highlighted.value) == null ? void 0 : _a.id,
          autocapitalize: "off",
          "onUpdate:modelValue": _ctx.onUpdateInputValue,
          onFocus: _ctx.onFocus,
          onBlur: _ctx.onBlur,
          onKeydown: withKeys(_ctx.onKeyNavigation, ["space", "enter", "up", "down", "tab", "esc"])
        }), null, 16, ["modelValue", "start-icon", "aria-owns", "aria-expanded", "aria-activedescendant", "onUpdate:modelValue", "onFocus", "onBlur", "onKeydown"]),
        withDirectives(createElementVNode("ol", {
          id: _ctx.menuId,
          class: "cdx-typeahead-search__menu",
          role: "listbox",
          "aria-label": _ctx.searchResultsLabel,
          "aria-multiselectable": "false"
        }, [
          (openBlock(true), createElementBlock(Fragment, null, renderList(_ctx.computedSearchResults, (searchResult) => {
            return openBlock(), createBlock(_component_cdx_option, mergeProps({
              key: searchResult.value,
              class: "cdx-typeahead-search__menu__option"
            }, searchResult, { onChange: _ctx.handleOptionChange }), {
              default: withCtx(() => [
                searchResult.value !== _ctx.MenuFooterValue ? (openBlock(), createBlock(_component_cdx_list_tile, {
                  key: 0,
                  "search-query": _ctx.searchQuery,
                  item: searchResult,
                  "highlight-query": _ctx.highlightQuery,
                  "hide-thumbnail": _ctx.hideThumbnail,
                  "hide-description": _ctx.hideDescription,
                  onClick: ($event) => _ctx.onSearchResultClick(searchResult)
                }, null, 8, ["search-query", "item", "highlight-query", "hide-thumbnail", "hide-description", "onClick"])) : (openBlock(), createElementBlock("a", {
                  key: 1,
                  class: "cdx-typeahead-search__search-footer",
                  href: searchResult.url,
                  onClickCapture: withModifiers(($event) => _ctx.onSearchFooterClick(searchResult), ["stop"])
                }, [
                  !_ctx.hideThumbnail ? (openBlock(), createBlock(_component_cdx_icon, {
                    key: 0,
                    class: "cdx-typeahead-search__search-footer__icon",
                    icon: _ctx.articleIcon
                  }, null, 8, ["icon"])) : createCommentVNode("", true),
                  createElementVNode("span", _hoisted_5, [
                    renderSlot(_ctx.$slots, "search-footer-text", { searchQuery: _ctx.searchQuery }, () => [
                      createElementVNode("strong", _hoisted_6, toDisplayString(_ctx.searchQuery), 1)
                    ])
                  ])
                ], 40, _hoisted_4))
              ]),
              _: 2
            }, 1040, ["onChange"]);
          }), 128))
        ], 8, _hoisted_3), [
          [vShow, _ctx.expanded]
        ]),
        renderSlot(_ctx.$slots, "default")
      ]),
      createVNode(_component_cdx_button, { class: "cdx-typeahead-search__submit" }, {
        default: withCtx(() => [
          createTextVNode(toDisplayString(_ctx.buttonLabel), 1)
        ]),
        _: 1
      })
    ], 40, _hoisted_1)
  ], 6);
}
var TypeaheadSearch = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render]]);
export { CdxButton, Checkbox as CdxCheckbox, Combobox as CdxCombobox, CdxIcon, CdxListTile, CdxListTileLabel, Lookup as CdxLookup, Message as CdxMessage, CdxOption, Radio as CdxRadio, Select as CdxSelect, CdxTextInput, ToggleSwitch as CdxToggleSwitch, TypeaheadSearch as CdxTypeaheadSearch, useComputedDirection, useComputedLanguage, useGeneratedId, useModelWrapper };
