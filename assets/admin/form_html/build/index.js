/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/elements/formFormsCategory.jsx":
/*!********************************************!*\
  !*** ./src/elements/formFormsCategory.jsx ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   FormsCategory: () => (/* binding */ FormsCategory)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _hooks_formFormsContext__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../hooks/formFormsContext */ "./src/hooks/formFormsContext.jsx");




function FormsCategory(props) {
  console.log(props);
  const formFormsContext = (0,react__WEBPACK_IMPORTED_MODULE_0__.useContext)(_hooks_formFormsContext__WEBPACK_IMPORTED_MODULE_2__.FormFormsContext);
  const [opened, setOpened] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(props.catIndex === 0 ? true : false);
  const toggleOpen = () => {
    setOpened(!opened);
  };
  let elClasses = ["category-container"];
  if (opened) {
    elClasses.push("opened");
  }
  elClasses = elClasses.join(" ");
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: props.catKey,
    className: elClasses
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "category-title",
    onClick: toggleOpen
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)(props.catSettings.title, "btdev_inscriere_text")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "category-content"
  }, "aaaaaaa"));
}


/***/ }),

/***/ "./src/elements/formsForm.jsx":
/*!************************************!*\
  !*** ./src/elements/formsForm.jsx ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ FormsForm)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _hooks_formFormsContext__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../hooks/formFormsContext */ "./src/hooks/formFormsContext.jsx");
/* harmony import */ var _formFormsCategory__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./formFormsCategory */ "./src/elements/formFormsCategory.jsx");
/* harmony import */ var _info_constants__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../info/constants */ "./src/info/constants.js");





function FormsForm() {
  const settingsTextarea = (0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(document.getElementById("btdev_forms_post_content"));
  const [settings, setSettings] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(JSON.parse(settingsTextarea.current.value));
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    settingsTextarea.current.value = JSON.stringify(settings);
  }, [settings]);
  console.log(settings);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_hooks_formFormsContext__WEBPACK_IMPORTED_MODULE_1__.FormFormsContextElement, {
    value: {
      settings,
      setSettings,
      textarea: settingsTextarea
    }
  }, Object.entries(_info_constants__WEBPACK_IMPORTED_MODULE_3__.formStructure).map((element, index) => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_formFormsCategory__WEBPACK_IMPORTED_MODULE_2__.FormsCategory, {
      key: element[0],
      catIndex: index,
      catKey: element[0],
      catSettings: element[1]
    });
  }));
}

/***/ }),

/***/ "./src/hooks/formFormsContext.jsx":
/*!****************************************!*\
  !*** ./src/hooks/formFormsContext.jsx ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   FormFormsContext: () => (/* binding */ FormFormsContext),
/* harmony export */   FormFormsContextElement: () => (/* binding */ FormFormsContextElement)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _info_constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../info/constants */ "./src/info/constants.js");




const defaultValues = {
  textarea: {},
  settings: {},
  setSettings: val => {}
};
const FormFormsContext = (0,react__WEBPACK_IMPORTED_MODULE_0__.createContext)(defaultValues);
const FormFormsContextElement = props => {
  const {
    children,
    value
  } = props;

  // Vars
  const [settings, setSettings] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(value !== undefined ? value?.settings : false);
  let changed = (0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)();
  if (!lodash__WEBPACK_IMPORTED_MODULE_1___default().isEqual(changed.current, value)) {
    changed.current = value;
  }
  let newValue = changed.current;

  // Functions

  // Return Data
  const data = (0,react__WEBPACK_IMPORTED_MODULE_0__.useMemo)(() => {
    return lodash__WEBPACK_IMPORTED_MODULE_1___default().merge({}, defaultValues, newValue, {
      settings,
      setSettings
    });
  }, [settings, setSettings]);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(FormFormsContext.Provider, {
    value: data
  }, children));
};


/***/ }),

/***/ "./src/info/constants.js":
/*!*******************************!*\
  !*** ./src/info/constants.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   currenciesOptions: () => (/* binding */ currenciesOptions),
/* harmony export */   enabledOptions: () => (/* binding */ enabledOptions),
/* harmony export */   formStructure: () => (/* binding */ formStructure),
/* harmony export */   getEnabledOptions: () => (/* binding */ getEnabledOptions),
/* harmony export */   paymentsOptions: () => (/* binding */ paymentsOptions)
/* harmony export */ });
const enabledOptions = [{
  value: "true",
  title: "Enabled"
}, {
  value: "false",
  title: "Disabled"
}];
const getEnabledOptions = function (defaultValue = true) {
  return enabledOptions;
};
const paymentsOptions = [{
  value: "stripe",
  title: "Stripe",
  default: true
}, {
  value: "ordinPlata",
  title: "Ordin de Plata"
}];
const currenciesOptions = [{
  value: "EUR",
  title: "EUR",
  default: true
}, {
  value: "RON",
  title: "RON"
}, {
  value: "USD",
  title: "USD"
}];
const formStructure = {
  mode: {
    singleInput: false,
    title: "Mode",
    type: "select",
    options: [{
      value: "test",
      title: "Test",
      default: true
    }, {
      value: "live",
      title: "Live"
    }]
  },
  table: {
    singleInput: false,
    title: "SQL Table",
    required: true
  },
  tandc: {
    title: "Terms and Conditions",
    fields: {
      enabled: {
        title: "Enabled",
        type: "select",
        options: getEnabledOptions()
      },
      text: {
        title: "Text",
        default: "I agree that my data will be used to better organize this and future events."
      }
    }
  },
  captcha: {
    title: "CAPTCHA",
    fields: {
      enabled: {
        title: "Enabled",
        type: "select",
        options: getEnabledOptions()
      }
    }
  },
  payment: {
    title: "Payments",
    fields: {
      enabled: {
        title: "Enabled",
        type: "select",
        options: getEnabledOptions()
      },
      with: {
        title: "Provider",
        type: "select",
        options: paymentsOptions
      },
      currency: {
        title: "Currency",
        type: "select",
        options: currenciesOptions
      },
      base_price: {
        title: "Base Price",
        default: "0",
        helpText: "Add 2 at the end of value.<br />Eg: 10EUR should write 1000"
      },
      payment_for: {
        title: "Payment description",
        default: "BTDEV Inscrieri"
      },
      description: {
        title: "Description",
        default: "BTDEV Inscrieri - Event register"
      }
    }
  },
  links: {
    title: "Links",
    fields: {
      success: {
        title: "Form URL",
        type: "select",
        options: "pagesPublished"
      },
      cancel: {
        title: "Form URL",
        type: "select",
        options: "pagesPublished"
      }
    }
  },
  emails: {
    title: "Emails",
    fields: {
      description: {
        title: "Subject post text"
      },
      list: {
        title: "Emails list",
        fields: {
          saved: {
            title: "Submission saved",
            fields: {
              enabled: {
                title: "Enabled",
                type: "select",
                options: getEnabledOptions()
              },
              subject: {
                title: "Subject",
                default: "Submission saved"
              },
              content: {
                title: "Content",
                type: "wysiwyg"
              }
            }
          },
          done: {
            title: "Submission added",
            fields: {
              enabled: {
                title: "Enabled",
                type: "select",
                options: getEnabledOptions()
              },
              subject: {
                title: "Subject",
                default: "Submission succesfully added!"
              },
              content: {
                title: "Content",
                type: "wysiwyg"
              }
            }
          },
          not_done: {
            title: "Submission canceled",
            fields: {
              enabled: {
                title: "Enabled",
                type: "select",
                options: getEnabledOptions()
              },
              subject: {
                title: "Subject",
                default: "Submission canceled!"
              },
              content: {
                title: "Content",
                type: "wysiwyg"
              }
            }
          },
          edit: {
            title: "Submission edited",
            fields: {
              enabled: {
                title: "Enabled",
                type: "select",
                options: getEnabledOptions()
              },
              subject: {
                title: "Subject",
                default: "Submission edited!"
              },
              content: {
                title: "Content",
                type: "wysiwyg"
              }
            }
          }
        }
      }
    }
  },
  repeater_fields: {
    title: "Repeater Fields",
    fields: {
      firstname: {
        title: "Firstname",
        width: "half",
        notDeletable: true,
        order: 0
      },
      lastname: {
        title: "Lastname",
        width: "half",
        notDeletable: true,
        order: 1
      },
      total_row: {
        title: "Row total",
        type: "total_row",
        width: "full",
        notDeletable: true,
        order: 1000
      }
    }
  },
  tables: {
    title: "Tables settings",
    entries_public: {
      fields: {
        id: "fields",
        title: "Fields",
        type: "select-fields"
      }
    },
    entries_admin: {
      fields: {
        title: "Fields",
        type: "select-fields"
      }
    },
    submissions: {
      fields: {
        title: "Fields",
        type: "select-fields"
      }
    },
    checkins: {
      fields: {
        title: "Fields",
        type: "select-fields"
      }
    },
    presents: {
      fields: {
        title: "Fields",
        type: "select-fields"
      }
    }
  }
};


/***/ }),

/***/ "./src/index.scss":
/*!************************!*\
  !*** ./src/index.scss ***!
  \************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/***/ ((module) => {

module.exports = window["lodash"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _elements_formsForm__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./elements/formsForm */ "./src/elements/formsForm.jsx");
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./index.scss */ "./src/index.scss");





const domNode = document.getElementById("btdev-inscriere-edit-form");
if (domNode) {
  const root = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createRoot)(domNode);
  root.render((0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_elements_formsForm__WEBPACK_IMPORTED_MODULE_2__["default"], null));
}
})();

/******/ })()
;
//# sourceMappingURL=index.js.map