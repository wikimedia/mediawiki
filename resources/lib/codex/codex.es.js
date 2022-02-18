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
import { defineComponent, computed, openBlock, createElementBlock, normalizeClass, renderSlot, ref, toRef, createElementVNode, withKeys, withModifiers, withDirectives, vModelCheckbox, onMounted, toDisplayString, createCommentVNode, inject, createTextVNode, resolveComponent, normalizeStyle, mergeProps, vModelDynamic, createBlock, getCurrentInstance, provide, watch, createVNode, withCtx, Fragment, renderList, vShow, vModelRadio } from "vue";
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
const _sfc_main$9 = defineComponent({
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
function _sfc_render$9(_ctx, _cache, $props, $setup, $data, $options) {
  return openBlock(), createElementBlock("button", {
    class: normalizeClass(["cdx-button", _ctx.rootClasses]),
    onClick: _cache[0] || (_cache[0] = (...args) => _ctx.onClick && _ctx.onClick(...args))
  }, [
    renderSlot(_ctx.$slots, "default")
  ], 2);
}
var CdxButton = /* @__PURE__ */ _export_sfc(_sfc_main$9, [["render", _sfc_render$9]]);
function useModelWrapper(modelValueRef, emit, eventName) {
  return computed({
    get: () => modelValueRef.value,
    set: (value) => emit(eventName || "update:modelValue", value)
  });
}
var Checkbox_vue_vue_type_style_index_0_lang = "";
const _sfc_main$8 = defineComponent({
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
const _hoisted_1$8 = ["aria-disabled"];
const _hoisted_2$6 = ["value", "disabled", ".indeterminate"];
const _hoisted_3$4 = /* @__PURE__ */ createElementVNode("span", { class: "cdx-checkbox__icon" }, null, -1);
const _hoisted_4$2 = { class: "cdx-checkbox__label-content" };
function _sfc_render$8(_ctx, _cache, $props, $setup, $data, $options) {
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
      }, null, 8, _hoisted_2$6), [
        [vModelCheckbox, _ctx.wrappedModel]
      ]),
      _hoisted_3$4,
      createElementVNode("span", _hoisted_4$2, [
        renderSlot(_ctx.$slots, "default")
      ])
    ], 40, _hoisted_1$8)
  ], 2);
}
var Checkbox = /* @__PURE__ */ _export_sfc(_sfc_main$8, [["render", _sfc_render$8]]);
var svgAlert = '<path d="M11.53 2.3A1.85 1.85 0 0010 1.21 1.85 1.85 0 008.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z"/>';
var svgCheck = '<path d="M7 14.17 2.83 10l-1.41 1.41L7 17 19 5l-1.41-1.42z"/>';
var svgClear = '<path d="M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z"/>';
var svgClose = '<path d="m4.34 2.93 12.73 12.73-1.41 1.41L2.93 4.35z"/><path d="M17.07 4.34 4.34 17.07l-1.41-1.41L15.66 2.93z"/>';
var svgError = '<path d="M13.728 1H6.272L1 6.272v7.456L6.272 19h7.456L19 13.728V6.272zM11 15H9v-2h2zm0-4H9V5h2z"/>';
var svgExpand = '<path d="m17.5 4.75-7.5 7.5-7.5-7.5L1 6.25l9 9 9-9z"/>';
var svgLightbulb = '<path d="M8 19a1 1 0 001 1h2a1 1 0 001-1v-1H8zm9-12a7 7 0 10-12 4.9S7 14 7 15v1a1 1 0 001 1h4a1 1 0 001-1v-1c0-1 2-3.1 2-3.1A7 7 0 0017 7z"/>';
var svgInfoFilled = '<path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zM9 5h2v2H9zm0 4h2v6H9z"/>';
const cdxIconAlert = svgAlert;
const cdxIconCheck = svgCheck;
const cdxIconClear = svgClear;
const cdxIconClose = svgClose;
const cdxIconError = svgError;
const cdxIconExpand = svgExpand;
const cdxIconInfoFilled = {
  langCodeMap: {
    ar: svgLightbulb
  },
  default: svgInfoFilled
};
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
const _sfc_main$7 = defineComponent({
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
const _hoisted_1$7 = ["aria-hidden"];
const _hoisted_2$5 = { key: 0 };
const _hoisted_3$3 = ["innerHTML"];
const _hoisted_4$1 = ["d"];
function _sfc_render$7(_ctx, _cache, $props, $setup, $data, $options) {
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
      _ctx.iconLabel ? (openBlock(), createElementBlock("title", _hoisted_2$5, toDisplayString(_ctx.iconLabel), 1)) : createCommentVNode("", true),
      _ctx.iconSvg ? (openBlock(), createElementBlock("g", {
        key: 1,
        fill: "currentColor",
        innerHTML: _ctx.iconSvg
      }, null, 8, _hoisted_3$3)) : (openBlock(), createElementBlock("path", {
        key: 2,
        d: _ctx.iconPath,
        fill: "currentColor"
      }, null, 8, _hoisted_4$1))
    ], 8, _hoisted_1$7))
  ], 2);
}
var CdxIcon = /* @__PURE__ */ _export_sfc(_sfc_main$7, [["render", _sfc_render$7]]);
var Option_vue_vue_type_style_index_0_lang = "";
const _sfc_main$6 = defineComponent({
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
const _hoisted_1$6 = ["id", "aria-disabled", "aria-selected"];
function _sfc_render$6(_ctx, _cache, $props, $setup, $data, $options) {
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
  ], 42, _hoisted_1$6);
}
var CdxOption = /* @__PURE__ */ _export_sfc(_sfc_main$6, [["render", _sfc_render$6]]);
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
const _sfc_main$5 = defineComponent({
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
const _hoisted_1$5 = ["type", "disabled"];
function _sfc_render$5(_ctx, _cache, $props, $setup, $data, $options) {
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
    }), null, 16, _hoisted_1$5), [
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
var CdxTextInput = /* @__PURE__ */ _export_sfc(_sfc_main$5, [["render", _sfc_render$5]]);
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
function useMenu(options, modelWrapper) {
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
    if (!option || option.disabled) {
      return;
    }
    switch (menuState) {
      case "selected":
        modelWrapper.value = option.value;
        expanded.value = false;
        state.active.value = null;
        break;
      case "highlighted":
        state.highlighted.value = option;
        break;
      case "active":
        state.active.value = option;
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
    if (prev) {
      handleOptionChange("highlighted", prev);
    }
  }
  function highlightNext() {
    var _a;
    const findNextEnabled = (startIndex) => computedOptions.value.find((item, index) => !item.disabled && index > startIndex);
    const highlightedIndex = (_a = highlightedOptionIndex.value) != null ? _a : -1;
    const next = findNextEnabled(highlightedIndex) || findNextEnabled(-1);
    if (next) {
      handleOptionChange("highlighted", next);
    }
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
const _sfc_main$4 = defineComponent({
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
const _hoisted_1$4 = { class: "cdx-combobox__input-wrapper" };
const _hoisted_2$4 = ["id"];
const _hoisted_3$2 = {
  key: 0,
  class: "cdx-option"
};
function _sfc_render$4(_ctx, _cache, $props, $setup, $data, $options) {
  var _a;
  const _component_cdx_text_input = resolveComponent("cdx-text-input");
  const _component_cdx_icon = resolveComponent("cdx-icon");
  const _component_cdx_button = resolveComponent("cdx-button");
  const _component_cdx_option = resolveComponent("cdx-option");
  return openBlock(), createElementBlock("div", {
    class: normalizeClass(["cdx-combobox", _ctx.rootClasses]),
    style: normalizeStyle(_ctx.rootStyle)
  }, [
    createElementVNode("div", _hoisted_1$4, [
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
      _ctx.$slots.footer ? (openBlock(), createElementBlock("li", _hoisted_3$2, [
        renderSlot(_ctx.$slots, "footer")
      ])) : createCommentVNode("", true)
    ], 8, _hoisted_2$4), [
      [vShow, _ctx.expanded]
    ])
  ], 6);
}
var Combobox = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["render", _sfc_render$4]]);
var Lookup_vue_vue_type_style_index_0_lang = "";
const _sfc_main$3 = defineComponent({
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
const _hoisted_1$3 = ["id"];
const _hoisted_2$3 = {
  key: 0,
  class: "cdx-option"
};
function _sfc_render$3(_ctx, _cache, $props, $setup, $data, $options) {
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
      _ctx.$slots.footer ? (openBlock(), createElementBlock("li", _hoisted_2$3, [
        renderSlot(_ctx.$slots, "footer")
      ])) : createCommentVNode("", true)
    ], 8, _hoisted_1$3), [
      [vShow, _ctx.expanded]
    ])
  ], 6);
}
var Lookup = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["render", _sfc_render$3]]);
var Message_vue_vue_type_style_index_0_lang = "";
const messageTypeValidator = makeStringTypeValidator(MessageTypes);
const iconMap = {
  notice: cdxIconInfoFilled,
  error: cdxIconError,
  warning: cdxIconAlert,
  success: cdxIconCheck
};
const _sfc_main$2 = defineComponent({
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
const _hoisted_1$2 = ["aria-live", "role"];
const _hoisted_2$2 = { class: "cdx-message__content" };
function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
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
    createElementVNode("div", _hoisted_2$2, [
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
  ], 10, _hoisted_1$2)) : createCommentVNode("", true);
}
var Message = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["render", _sfc_render$2]]);
var Radio_vue_vue_type_style_index_0_lang = "";
const _sfc_main$1 = defineComponent({
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
const _hoisted_1$1 = ["aria-disabled"];
const _hoisted_2$1 = ["name", "value", "disabled"];
const _hoisted_3$1 = /* @__PURE__ */ createElementVNode("span", { class: "cdx-radio__icon" }, null, -1);
const _hoisted_4 = { class: "cdx-radio__label-content" };
function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
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
      }, null, 8, _hoisted_2$1), [
        [vModelRadio, _ctx.wrappedModel]
      ]),
      _hoisted_3$1,
      createElementVNode("span", _hoisted_4, [
        renderSlot(_ctx.$slots, "default")
      ])
    ], 8, _hoisted_1$1)
  ], 2);
}
var Radio = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["render", _sfc_render$1]]);
var Select_vue_vue_type_style_index_0_lang = "";
const _sfc_main = defineComponent({
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
const _hoisted_1 = ["aria-owns", "aria-labelledby", "aria-activedescendant", "aria-expanded", "aria-disabled"];
const _hoisted_2 = ["id"];
const _hoisted_3 = ["id"];
function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
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
      ], 8, _hoisted_2),
      createVNode(_component_cdx_icon, {
        icon: _ctx.cdxIconExpand,
        class: "cdx-select__indicator"
      }, null, 8, ["icon"])
    ], 40, _hoisted_1),
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
    ], 8, _hoisted_3), [
      [vShow, _ctx.expanded]
    ])
  ], 2);
}
var Select = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render]]);
export { CdxButton, Checkbox as CdxCheckbox, Combobox as CdxCombobox, CdxIcon, Lookup as CdxLookup, Message as CdxMessage, CdxOption, Radio as CdxRadio, Select as CdxSelect, CdxTextInput, useComputedDirection, useComputedLanguage, useGeneratedId, useModelWrapper };
