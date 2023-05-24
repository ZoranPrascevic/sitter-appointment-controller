/*
 Highcharts JS v8.1.0 (2020-05-05)

 Accessibility module

 (c) 2010-2019 Highsoft AS
 Author: Oystein Moseng

 License: www.highcharts.com/license
*/
(function (v) { "object" === typeof module && module.exports ? (v["default"] = v, module.exports = v) : "function" === typeof define && define.amd ? define("highcharts/modules/accessibility", ["highcharts"], function (r) { v(r); v.Highcharts = r; return v }) : v("undefined" !== typeof Highcharts ? Highcharts : void 0) })(function (v) {
    function r(a, f, q, l) { a.hasOwnProperty(f) || (a[f] = l.apply(null, q)) } var a = v ? v._modules : {}; r(a, "modules/accessibility/utils/htmlUtilities.js", [a["parts/Utilities.js"], a["parts/Globals.js"]], function (a, f) {
        function h(a) {
            return a.replace(/&/g,
                "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#x27;").replace(/\//g, "&#x2F;")
        } var l = a.merge, n = f.win, e = n.document; return {
            addClass: function (a, e) { a.classList ? a.classList.add(e) : 0 > a.className.indexOf(e) && (a.className += e) }, escapeStringForHTML: h, getElement: function (a) { return e.getElementById(a) }, getFakeMouseEvent: function (a) {
                if ("function" === typeof n.MouseEvent) return new n.MouseEvent(a); if (e.createEvent) {
                    var t = e.createEvent("MouseEvent"); if (t.initMouseEvent) return t.initMouseEvent(a,
                        !0, !0, n, "click" === a ? 1 : 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, null), t
                } return { type: a }
            }, removeElement: function (a) { a && a.parentNode && a.parentNode.removeChild(a) }, reverseChildNodes: function (a) { for (var e = a.childNodes.length; e--;)a.appendChild(a.childNodes[e]) }, setElAttrs: function (a, e) { Object.keys(e).forEach(function (k) { var d = e[k]; null === d ? a.removeAttribute(k) : (d = h("" + d), a.setAttribute(k, d)) }) }, stripHTMLTagsFromString: function (a) { return "string" === typeof a ? a.replace(/<\/?[^>]+(>|$)/g, "") : a }, visuallyHideElement: function (a) {
                l(!0,
                    a.style, { position: "absolute", width: "1px", height: "1px", overflow: "hidden", whiteSpace: "nowrap", clip: "rect(1px, 1px, 1px, 1px)", marginTop: "-3px", "-ms-filter": "progid:DXImageTransform.Microsoft.Alpha(Opacity=1)", filter: "alpha(opacity=1)", opacity: "0.01" })
            }
        }
    }); r(a, "modules/accessibility/utils/chartUtilities.js", [a["modules/accessibility/utils/htmlUtilities.js"], a["parts/Utilities.js"]], function (a, f) {
        function h(a) { if (a.points && a.points.length && a.points[0].graphic) return a.points[0].graphic.element } function l(a) {
            var d =
                h(a); return d && d.parentNode || a.graph && a.graph.element || a.group && a.group.element
        } function n(a, d) { d.setAttribute("aria-hidden", !1); d !== a.renderTo && d.parentNode && (Array.prototype.forEach.call(d.parentNode.childNodes, function (c) { c.hasAttribute("aria-hidden") || c.setAttribute("aria-hidden", !0) }), n(a, d.parentNode)) } var e = a.stripHTMLTagsFromString, t = f.defined, p = f.find; return {
            getChartTitle: function (a) { return e(a.options.title.text || a.langFormat("accessibility.defaultChartTitle", { chart: a })) }, getAxisDescription: function (a) {
                return e(a &&
                    (a.userOptions && a.userOptions.accessibility && a.userOptions.accessibility.description || a.axisTitle && a.axisTitle.textStr || a.options.id || a.categories && "categories" || a.dateTime && "Time" || "values"))
            }, getPointFromXY: function (a, d, c) { for (var b = a.length, g; b--;)if (g = p(a[b].points || [], function (b) { return b.x === d && b.y === c })) return g }, getSeriesFirstPointElement: h, getSeriesFromName: function (a, d) { return d ? (a.series || []).filter(function (c) { return c.name === d }) : a.series }, getSeriesA11yElement: l, unhideChartElementFromAT: n,
            hideSeriesFromAT: function (a) { (a = l(a)) && a.setAttribute("aria-hidden", !0) }, scrollToPoint: function (a) {
                var d = a.series.xAxis, c = a.series.yAxis, b = (null === d || void 0 === d ? 0 : d.scrollbar) ? d : c; if ((d = null === b || void 0 === b ? void 0 : b.scrollbar) && t(d.to) && t(d.from)) {
                    c = d.to - d.from; if (t(b.dataMin) && t(b.dataMax)) { var g = b.toPixels(b.dataMin), m = b.toPixels(b.dataMax); a = (b.toPixels(a["xAxis" === b.coll ? "x" : "y"] || 0) - g) / (m - g) } else a = 0; d.updatePosition(a - c / 2, a + c / 2); v.fireEvent(d, "changed", {
                        from: d.from, to: d.to, trigger: "scrollbar",
                        DOMEvent: null
                    })
                }
            }
        }
    }); r(a, "modules/accessibility/KeyboardNavigationHandler.js", [a["parts/Utilities.js"]], function (a) {
        function h(a, h) { this.chart = a; this.keyCodeMap = h.keyCodeMap || []; this.validate = h.validate; this.init = h.init; this.terminate = h.terminate; this.response = { success: 1, prev: 2, next: 3, noHandler: 4, fail: 5 } } var q = a.find; h.prototype = {
            run: function (a) {
                var h = a.which || a.keyCode, e = this.response.noHandler, t = q(this.keyCodeMap, function (a) { return -1 < a[0].indexOf(h) }); t ? e = t[1].call(this, h, a) : 9 === h && (e = this.response[a.shiftKey ?
                    "prev" : "next"]); return e
            }
        }; return h
    }); r(a, "modules/accessibility/utils/EventProvider.js", [a["parts/Globals.js"], a["parts/Utilities.js"]], function (a, f) { var h = f.addEvent; f = f.extend; var l = function () { this.eventRemovers = [] }; f(l.prototype, { addEvent: function () { var f = h.apply(a, arguments); this.eventRemovers.push(f); return f }, removeAddedEvents: function () { this.eventRemovers.forEach(function (a) { a() }); this.eventRemovers = [] } }); return l }); r(a, "modules/accessibility/utils/DOMElementProvider.js", [a["parts/Globals.js"],
    a["parts/Utilities.js"], a["modules/accessibility/utils/htmlUtilities.js"]], function (a, f, q) { var h = a.win.document; a = f.extend; var n = q.removeElement; q = function () { this.elements = [] }; a(q.prototype, { createElement: function () { var a = h.createElement.apply(h, arguments); this.elements.push(a); return a }, destroyCreatedElements: function () { this.elements.forEach(function (a) { n(a) }); this.elements = [] } }); return q }); r(a, "modules/accessibility/AccessibilityComponent.js", [a["parts/Globals.js"], a["parts/Utilities.js"], a["modules/accessibility/utils/htmlUtilities.js"],
    a["modules/accessibility/utils/chartUtilities.js"], a["modules/accessibility/utils/EventProvider.js"], a["modules/accessibility/utils/DOMElementProvider.js"]], function (a, f, q, l, n, e) {
        function h() { } var p = a.win, k = p.document; a = f.extend; var d = f.fireEvent, c = f.merge, b = q.removeElement, g = q.getFakeMouseEvent, m = l.unhideChartElementFromAT; h.prototype = {
            initBase: function (b) { this.chart = b; this.eventProvider = new n; this.domElementProvider = new e; this.keyCodes = { left: 37, right: 39, up: 38, down: 40, enter: 13, space: 32, esc: 27, tab: 9 } },
            addEvent: function () { return this.eventProvider.addEvent.apply(this.eventProvider, arguments) }, createElement: function () { return this.domElementProvider.createElement.apply(this.domElementProvider, arguments) }, fireEventOnWrappedOrUnwrappedElement: function (b, c) { var a = c.type; k.createEvent && (b.dispatchEvent || b.fireEvent) ? b.dispatchEvent ? b.dispatchEvent(c) : b.fireEvent(a, c) : d(b, a, c) }, fakeClickEvent: function (b) { if (b) { var c = g("click"); this.fireEventOnWrappedOrUnwrappedElement(b, c) } }, addProxyGroup: function (b) {
                this.createOrUpdateProxyContainer();
                var c = this.createElement("div"); Object.keys(b || {}).forEach(function (a) { null !== b[a] && c.setAttribute(a, b[a]) }); this.chart.a11yProxyContainer.appendChild(c); return c
            }, createOrUpdateProxyContainer: function () { var b = this.chart, c = b.renderer.box; b.a11yProxyContainer = b.a11yProxyContainer || this.createProxyContainerElement(); c.nextSibling !== b.a11yProxyContainer && b.container.insertBefore(b.a11yProxyContainer, c.nextSibling) }, createProxyContainerElement: function () {
                var b = k.createElement("div"); b.className = "highcharts-a11y-proxy-container";
                return b
            }, createProxyButton: function (b, a, g, d, z) { var x = b.element, u = this.createElement("button"), e = c({ "aria-label": x.getAttribute("aria-label") }, g); b = this.getElementPosition(d || b); Object.keys(e).forEach(function (b) { null !== e[b] && u.setAttribute(b, e[b]) }); u.className = "highcharts-a11y-proxy-button"; z && this.addEvent(u, "click", z); this.setProxyButtonStyle(u, b); this.proxyMouseEventsForButton(x, u); a.appendChild(u); e["aria-hidden"] || m(this.chart, u); return u }, getElementPosition: function (b) {
                var c = b.element; return (b =
                    this.chart.renderTo) && c && c.getBoundingClientRect ? (c = c.getBoundingClientRect(), b = b.getBoundingClientRect(), { x: c.left - b.left, y: c.top - b.top, width: c.right - c.left, height: c.bottom - c.top }) : { x: 0, y: 0, width: 1, height: 1 }
            }, setProxyButtonStyle: function (b, a) {
                c(!0, b.style, {
                    "border-width": 0, "background-color": "transparent", cursor: "pointer", outline: "none", opacity: .001, filter: "alpha(opacity=1)", "-ms-filter": "progid:DXImageTransform.Microsoft.Alpha(Opacity=1)", zIndex: 999, overflow: "hidden", padding: 0, margin: 0, display: "block",
                    position: "absolute", width: (a.width || 1) + "px", height: (a.height || 1) + "px", left: (a.x || 0) + "px", top: (a.y || 0) + "px"
                })
            }, proxyMouseEventsForButton: function (b, c) { var a = this; "click touchstart touchend touchcancel touchmove mouseover mouseenter mouseleave mouseout".split(" ").forEach(function (g) { a.addEvent(c, g, function (c) { var g = a.cloneMouseEvent(c); b && a.fireEventOnWrappedOrUnwrappedElement(b, g); c.stopPropagation(); c.preventDefault() }) }) }, cloneMouseEvent: function (b) {
                if ("function" === typeof p.MouseEvent) return new p.MouseEvent(b.type,
                    b); if (k.createEvent) { var c = k.createEvent("MouseEvent"); if (c.initMouseEvent) return c.initMouseEvent(b.type, b.bubbles, b.cancelable, b.view || p, b.detail, b.screenX, b.screenY, b.clientX, b.clientY, b.ctrlKey, b.altKey, b.shiftKey, b.metaKey, b.button, b.relatedTarget), c } return g(b.type)
            }, destroyBase: function () { b(this.chart.a11yProxyContainer); this.domElementProvider.destroyCreatedElements(); this.eventProvider.removeAddedEvents() }
        }; a(h.prototype, {
            init: function () { }, getKeyboardNavigation: function () { }, onChartUpdate: function () { },
            onChartRender: function () { }, destroy: function () { }
        }); return h
    }); r(a, "modules/accessibility/KeyboardNavigation.js", [a["parts/Globals.js"], a["parts/Utilities.js"], a["modules/accessibility/utils/htmlUtilities.js"], a["modules/accessibility/utils/EventProvider.js"]], function (a, f, q, l) {
        function h(c, b) { this.init(c, b) } var e = a.win, t = e.document, p = f.addEvent, k = f.fireEvent, d = q.getElement; p(t, "keydown", function (c) { 27 === (c.which || c.keyCode) && a.charts && a.charts.forEach(function (b) { b && b.dismissPopupContent && b.dismissPopupContent() }) });
        a.Chart.prototype.dismissPopupContent = function () { var c = this; k(this, "dismissPopupContent", {}, function () { c.tooltip && c.tooltip.hide(0); c.hideExportMenu() }) }; h.prototype = {
            init: function (c, b) {
                var a = this, d = this.eventProvider = new l; this.chart = c; this.components = b; this.modules = []; this.currentModuleIx = 0; d.addEvent(c.renderTo, "keydown", function (b) { return a.onKeydown(b) }); d.addEvent(c.container, "focus", function (b) { return a.onFocus(b) }); d.addEvent(t, "mouseup", function () { return a.onMouseUp() }); d.addEvent(c.renderTo,
                    "mousedown", function () { a.isClickingChart = !0 }); d.addEvent(c.renderTo, "mouseover", function () { a.pointerIsOverChart = !0 }); d.addEvent(c.renderTo, "mouseout", function () { a.pointerIsOverChart = !1 }); this.update(); this.modules.length && this.modules[0].init(1)
            }, update: function (c) {
                var b = this.chart.options.accessibility; b = b && b.keyboardNavigation; var a = this.components; this.updateContainerTabindex(); b && b.enabled && c && c.length ? (this.modules = c.reduce(function (b, c) { c = a[c].getKeyboardNavigation(); return b.concat(c) }, []),
                    this.updateExitAnchor()) : (this.modules = [], this.currentModuleIx = 0, this.removeExitAnchor())
            }, onFocus: function (c) { var b, a = this.chart; c = c.relatedTarget && a.container.contains(c.relatedTarget); this.isClickingChart || c || (null === (b = this.modules[0]) || void 0 === b ? void 0 : b.init(1)) }, onMouseUp: function () {
                delete this.isClickingChart; if (!this.keyboardReset && !this.pointerIsOverChart) {
                    var c = this.chart, b = this.modules && this.modules[this.currentModuleIx || 0]; b && b.terminate && b.terminate(); c.focusElement && c.focusElement.removeFocusBorder();
                    this.currentModuleIx = 0; this.keyboardReset = !0
                }
            }, onKeydown: function (c) { c = c || e.event; var b, a = this.modules && this.modules.length && this.modules[this.currentModuleIx]; this.keyboardReset = !1; if (a) { var d = a.run(c); d === a.response.success ? b = !0 : d === a.response.prev ? b = this.prev() : d === a.response.next && (b = this.next()); b && (c.preventDefault(), c.stopPropagation()) } }, prev: function () { return this.move(-1) }, next: function () { return this.move(1) }, move: function (c) {
                var b = this.modules && this.modules[this.currentModuleIx]; b && b.terminate &&
                    b.terminate(c); this.chart.focusElement && this.chart.focusElement.removeFocusBorder(); this.currentModuleIx += c; if (b = this.modules && this.modules[this.currentModuleIx]) { if (b.validate && !b.validate()) return this.move(c); if (b.init) return b.init(c), !0 } this.currentModuleIx = 0; 0 < c ? (this.exiting = !0, this.exitAnchor.focus()) : this.chart.container.focus(); return !1
            }, updateExitAnchor: function () {
                var c = d("highcharts-end-of-chart-marker-" + this.chart.index); this.removeExitAnchor(); c ? (this.makeElementAnExitAnchor(c), this.exitAnchor =
                    c) : this.createExitAnchor()
            }, updateContainerTabindex: function () { var c = this.chart.options.accessibility; c = c && c.keyboardNavigation; c = !(c && !1 === c.enabled); var b = this.chart.container, a = b.getAttribute("tabIndex"); c && !a ? b.setAttribute("tabindex", "0") : c || "0" !== a || b.removeAttribute("tabindex") }, makeElementAnExitAnchor: function (c) { c.setAttribute("class", "highcharts-exit-anchor"); c.setAttribute("tabindex", "0"); c.setAttribute("aria-hidden", !1); this.addExitAnchorEventsToEl(c) }, createExitAnchor: function () {
                var c =
                    this.chart, b = this.exitAnchor = t.createElement("div"); c.renderTo.appendChild(b); this.makeElementAnExitAnchor(b)
            }, removeExitAnchor: function () { this.exitAnchor && this.exitAnchor.parentNode && (this.exitAnchor.parentNode.removeChild(this.exitAnchor), delete this.exitAnchor) }, addExitAnchorEventsToEl: function (c) {
                var b = this.chart, a = this; this.eventProvider.addEvent(c, "focus", function (c) {
                    c = c || e.event; c.relatedTarget && b.container.contains(c.relatedTarget) || a.exiting ? a.exiting = !1 : (b.renderTo.focus(), c.preventDefault(),
                        a.modules && a.modules.length && (a.currentModuleIx = a.modules.length - 1, (c = a.modules[a.currentModuleIx]) && c.validate && !c.validate() ? a.prev() : c && c.init(-1)))
                })
            }, destroy: function () { this.removeExitAnchor(); this.eventProvider.removeAddedEvents(); "0" === this.chart.container.getAttribute("tabindex") && this.chart.container.removeAttribute("tabindex") }
        }; return h
    }); r(a, "modules/accessibility/components/LegendComponent.js", [a["parts/Globals.js"], a["parts/Legend.js"], a["parts/Utilities.js"], a["modules/accessibility/AccessibilityComponent.js"],
    a["modules/accessibility/KeyboardNavigationHandler.js"], a["modules/accessibility/utils/htmlUtilities.js"]], function (a, f, q, l, n, e) {
        var h = q.addEvent, p = q.extend, k = q.fireEvent, d = e.stripHTMLTagsFromString, c = e.removeElement; a.Chart.prototype.highlightLegendItem = function (b) {
            var c = this.legend.allItems, a = this.highlightedLegendItemIx; if (c[b]) {
                c[a] && k(c[a].legendGroup.element, "mouseout"); a = this.legend; var d = a.allItems[b].pageIx, e = a.currentPage; "undefined" !== typeof d && d + 1 !== e && a.scroll(1 + d - e); this.setFocusToElement(c[b].legendItem,
                    c[b].a11yProxyElement); k(c[b].legendGroup.element, "mouseover"); return !0
            } return !1
        }; h(f, "afterColorizeItem", function (b) { var c = b.item; this.chart.options.accessibility.enabled && c && c.a11yProxyElement && c.a11yProxyElement.setAttribute("aria-pressed", b.visible ? "false" : "true") }); a = function () { }; a.prototype = new l; p(a.prototype, {
            init: function () { var b = this; this.addEvent(f, "afterScroll", function () { this.chart === b.chart && b.updateProxies() }) }, updateLegendItemProxyVisibility: function () {
                var b = this.chart.legend, c = b.currentPage ||
                    1, a = b.clipHeight || 0; (b.allItems || []).forEach(function (d) { var g = d.pageIx || 0, m = d._legendItemPos ? d._legendItemPos[1] : 0, u = d.legendItem ? Math.round(d.legendItem.getBBox().height) : 0; g = m + u - b.pages[g] > a || g !== c - 1; d.a11yProxyElement && (d.a11yProxyElement.style.visibility = g ? "hidden" : "visible") })
            }, onChartRender: function () { this.legendProxyButtonClicked ? delete this.legendProxyButtonClicked : this.updateProxies() }, updateProxies: function () {
                c(this.legendProxyGroup); var b = this.chart, a = b.legend && b.legend.allItems, d = b.options.legend.accessibility ||
                    {}; !a || !a.length || b.colorAxis && b.colorAxis.length || !1 === d.enabled || (this.addLegendProxyGroup(), this.proxyLegendItems(), this.updateLegendItemProxyVisibility())
            }, addLegendProxyGroup: function () { var b = this.chart.options.accessibility, c = this.chart.langFormat("accessibility.legend.legendLabel", {}); this.legendProxyGroup = this.addProxyGroup({ "aria-label": c, role: "all" === b.landmarkVerbosity ? "region" : null }) }, proxyLegendItems: function () {
                var b = this; (this.chart.legend && this.chart.legend.allItems || []).forEach(function (c) {
                    c.legendItem &&
                    c.legendItem.element && b.proxyLegendItem(c)
                })
            }, proxyLegendItem: function (b) { var c = this, a = this.chart.langFormat("accessibility.legend.legendItem", { chart: this.chart, itemName: d(b.name) }); b.a11yProxyElement = this.createProxyButton(b.legendItem, this.legendProxyGroup, { tabindex: -1, "aria-pressed": !b.visible, "aria-label": a }, b.legendGroup.div ? b.legendItem : b.legendGroup, function () { c.legendProxyButtonClicked = !0 }) }, getKeyboardNavigation: function () {
                var b = this.keyCodes, c = this; return new n(this.chart, {
                    keyCodeMap: [[[b.left,
                    b.right, b.up, b.down], function (b) { return c.onKbdArrowKey(this, b) }], [[b.enter, b.space], function () { return c.onKbdClick(this) }]], validate: function () { return c.shouldHaveLegendNavigation() }, init: function (b) { return c.onKbdNavigationInit(b) }
                })
            }, onKbdArrowKey: function (b, c) {
                var a = this.keyCodes, d = b.response, g = this.chart, e = g.options.accessibility, u = g.legend.allItems.length; c = c === a.left || c === a.up ? -1 : 1; return g.highlightLegendItem(this.highlightedLegendItemIx + c) ? (this.highlightedLegendItemIx += c, d.success) : 1 < u &&
                    e.keyboardNavigation.wrapAround ? (b.init(c), d.success) : d[0 < c ? "next" : "prev"]
            }, onKbdClick: function (b) { var c = this.chart.legend.allItems[this.highlightedLegendItemIx]; c && c.a11yProxyElement && k(c.a11yProxyElement, "click"); return b.response.success }, shouldHaveLegendNavigation: function () { var b = this.chart, c = b.colorAxis && b.colorAxis.length, a = (b.options.legend || {}).accessibility || {}; return !!(b.legend && b.legend.allItems && b.legend.display && !c && a.enabled && a.keyboardNavigation && a.keyboardNavigation.enabled) }, onKbdNavigationInit: function (b) {
                var c =
                    this.chart, a = c.legend.allItems.length - 1; b = 0 < b ? 0 : a; c.highlightLegendItem(b); this.highlightedLegendItemIx = b
            }
        }); return a
    }); r(a, "modules/accessibility/components/MenuComponent.js", [a["parts/Globals.js"], a["parts/Utilities.js"], a["modules/accessibility/AccessibilityComponent.js"], a["modules/accessibility/KeyboardNavigationHandler.js"], a["modules/accessibility/utils/chartUtilities.js"], a["modules/accessibility/utils/htmlUtilities.js"]], function (a, f, q, l, n, e) {
        function h(c) { return c.exportSVGElements && c.exportSVGElements[0] }
        f = f.extend; var p = n.unhideChartElementFromAT, k = e.removeElement, d = e.getFakeMouseEvent; a.Chart.prototype.showExportMenu = function () { var c = h(this); if (c && (c = c.element, c.onclick)) c.onclick(d("click")) }; a.Chart.prototype.hideExportMenu = function () { var c = this.exportDivElements; c && this.exportContextMenu && (c.forEach(function (b) { if ("highcharts-menu-item" === b.className && b.onmouseout) b.onmouseout(d("mouseout")) }), this.highlightedExportItemIx = 0, this.exportContextMenu.hideMenu(), this.container.focus()) }; a.Chart.prototype.highlightExportItem =
            function (c) { var b = this.exportDivElements && this.exportDivElements[c], a = this.exportDivElements && this.exportDivElements[this.highlightedExportItemIx]; if (b && "LI" === b.tagName && (!b.children || !b.children.length)) { var m = !!(this.renderTo.getElementsByTagName("g")[0] || {}).focus; b.focus && m && b.focus(); if (a && a.onmouseout) a.onmouseout(d("mouseout")); if (b.onmouseover) b.onmouseover(d("mouseover")); this.highlightedExportItemIx = c; return !0 } return !1 }; a.Chart.prototype.highlightLastExportItem = function () {
                var c; if (this.exportDivElements) for (c =
                    this.exportDivElements.length; c--;)if (this.highlightExportItem(c)) return !0; return !1
            }; a = function () { }; a.prototype = new q; f(a.prototype, {
                init: function () { var c = this.chart, b = this; this.addEvent(c, "exportMenuShown", function () { b.onMenuShown() }); this.addEvent(c, "exportMenuHidden", function () { b.onMenuHidden() }) }, onMenuHidden: function () { var c = this.chart.exportContextMenu; c && c.setAttribute("aria-hidden", "true"); this.isExportMenuShown = !1; this.setExportButtonExpandedState("false") }, onMenuShown: function () {
                    var c = this.chart,
                    b = c.exportContextMenu; b && (this.addAccessibleContextMenuAttribs(), p(c, b)); this.isExportMenuShown = !0; this.setExportButtonExpandedState("true")
                }, setExportButtonExpandedState: function (c) { var b = this.exportButtonProxy; b && b.setAttribute("aria-expanded", c) }, onChartRender: function () {
                    var c = this.chart, b = c.options.accessibility; k(this.exportProxyGroup); var a = c.options.exporting, d = h(c); a && !1 !== a.enabled && a.accessibility && a.accessibility.enabled && d && d.element && (this.exportProxyGroup = this.addProxyGroup("all" ===
                        b.landmarkVerbosity ? { "aria-label": c.langFormat("accessibility.exporting.exportRegionLabel", { chart: c }), role: "region" } : {}), b = h(this.chart), this.exportButtonProxy = this.createProxyButton(b, this.exportProxyGroup, { "aria-label": c.langFormat("accessibility.exporting.menuButtonLabel", { chart: c }), "aria-expanded": "false" }))
                }, addAccessibleContextMenuAttribs: function () {
                    var c = this.chart, b = c.exportDivElements; b && b.length && (b.forEach(function (b) {
                        "LI" !== b.tagName || b.children && b.children.length ? b.setAttribute("aria-hidden",
                            "true") : b.setAttribute("tabindex", -1)
                    }), b = b[0].parentNode, b.removeAttribute("aria-hidden"), b.setAttribute("aria-label", c.langFormat("accessibility.exporting.chartMenuLabel", { chart: c })))
                }, getKeyboardNavigation: function () {
                    var c = this.keyCodes, b = this.chart, a = this; return new l(b, {
                        keyCodeMap: [[[c.left, c.up], function () { return a.onKbdPrevious(this) }], [[c.right, c.down], function () { return a.onKbdNext(this) }], [[c.enter, c.space], function () { return a.onKbdClick(this) }], [[c.esc], function () { return this.response.prev }]],
                        validate: function () { return b.exportChart && !1 !== b.options.exporting.enabled && !1 !== b.options.exporting.accessibility.enabled }, init: function () { var c = a.exportButtonProxy, d = b.exportingGroup; d && c && b.setFocusToElement(d, c) }, terminate: function () { b.hideExportMenu() }
                    })
                }, onKbdPrevious: function (c) {
                    var b = this.chart, a = b.options.accessibility; c = c.response; for (var d = b.highlightedExportItemIx || 0; d--;)if (b.highlightExportItem(d)) return c.success; return a.keyboardNavigation.wrapAround ? (b.highlightLastExportItem(), c.success) :
                        c.prev
                }, onKbdNext: function (c) { var b = this.chart, a = b.options.accessibility; c = c.response; for (var d = (b.highlightedExportItemIx || 0) + 1; d < b.exportDivElements.length; ++d)if (b.highlightExportItem(d)) return c.success; return a.keyboardNavigation.wrapAround ? (b.highlightExportItem(0), c.success) : c.next }, onKbdClick: function (c) { var b = this.chart, a = b.exportDivElements[b.highlightedExportItemIx], d = h(b).element; this.isExportMenuShown ? this.fakeClickEvent(a) : (this.fakeClickEvent(d), b.highlightExportItem(0)); return c.response.success }
            });
        return a
    }); r(a, "modules/accessibility/components/SeriesComponent/SeriesKeyboardNavigation.js", [a["parts/Globals.js"], a["parts/Point.js"], a["parts/Utilities.js"], a["modules/accessibility/KeyboardNavigationHandler.js"], a["modules/accessibility/utils/EventProvider.js"], a["modules/accessibility/utils/chartUtilities.js"]], function (a, f, q, l, n, e) {
        function h(b) { var c = b.index, a = b.series.points, d = a.length; if (a[c] !== b) for (; d--;) { if (a[d] === b) return d } else return c } function p(b) {
            var c = b.chart.options.accessibility.keyboardNavigation.seriesNavigation,
            a = b.options.accessibility || {}, d = a.keyboardNavigation; return d && !1 === d.enabled || !1 === a.enabled || !1 === b.options.enableMouseTracking || !b.visible || c.pointNavigationEnabledThreshold && c.pointNavigationEnabledThreshold <= b.points.length
        } function k(b) { var c = b.series.chart.options.accessibility; return b.isNull && c.keyboardNavigation.seriesNavigation.skipNullPoints || !1 === b.visible || p(b.series) } function d(b, c, a, d) {
            var g = Infinity, w = c.points.length, e = function (b) { return !(m(b.plotX) && m(b.plotY)) }; if (!e(b)) {
                for (; w--;) {
                    var u =
                        c.points[w]; if (!e(u) && (u = (b.plotX - u.plotX) * (b.plotX - u.plotX) * (a || 1) + (b.plotY - u.plotY) * (b.plotY - u.plotY) * (d || 1), u < g)) { g = u; var z = w }
                } return m(z) ? c.points[z] : void 0
            }
        } function c(b) { var c = !1; delete b.highlightedPoint; return c = b.series.reduce(function (b, c) { return b || c.highlightFirstValidPoint() }, !1) } function b(b, c) { this.keyCodes = c; this.chart = b } var g = q.extend, m = q.defined, x = e.getPointFromXY, A = e.getSeriesFromName, B = e.scrollToPoint; a.Series.prototype.keyboardMoveVertical = !0;["column", "pie"].forEach(function (b) {
            a.seriesTypes[b] &&
            (a.seriesTypes[b].prototype.keyboardMoveVertical = !1)
        }); f.prototype.highlight = function () { var b = this.series.chart; if (this.isNull) b.tooltip && b.tooltip.hide(0); else this.onMouseOver(); B(this); this.graphic && b.setFocusToElement(this.graphic); b.highlightedPoint = this; return this }; a.Chart.prototype.highlightAdjacentPoint = function (b) {
            var c = this.series, a = this.highlightedPoint, d = a && h(a) || 0, m = a && a.series.points, g = this.series && this.series[this.series.length - 1]; g = g && g.points && g.points[g.points.length - 1]; if (!c[0] ||
                !c[0].points) return !1; if (a) { if (c = c[a.series.index + (b ? 1 : -1)], d = m[d + (b ? 1 : -1)], !d && c && (d = c.points[b ? 0 : c.points.length - 1]), !d) return !1 } else d = b ? c[0].points[0] : g; return k(d) ? (c = d.series, p(c) ? this.highlightedPoint = b ? c.points[c.points.length - 1] : c.points[0] : this.highlightedPoint = d, this.highlightAdjacentPoint(b)) : d.highlight()
        }; a.Series.prototype.highlightFirstValidPoint = function () {
            var b = this.chart.highlightedPoint, c = (b && b.series) === this ? h(b) : 0; b = this.points; var a = b.length; if (b && a) {
                for (var d = c; d < a; ++d)if (!k(b[d])) return b[d].highlight();
                for (; 0 <= c; --c)if (!k(b[c])) return b[c].highlight()
            } return !1
        }; a.Chart.prototype.highlightAdjacentSeries = function (b) {
            var c, a = this.highlightedPoint; var g = (c = this.series && this.series[this.series.length - 1]) && c.points && c.points[c.points.length - 1]; if (!this.highlightedPoint) return c = b ? this.series && this.series[0] : c, (g = b ? c && c.points && c.points[0] : g) ? g.highlight() : !1; c = this.series[a.series.index + (b ? -1 : 1)]; if (!c) return !1; g = d(a, c, 4); if (!g) return !1; if (p(c)) return g.highlight(), b = this.highlightAdjacentSeries(b), b ?
                b : (a.highlight(), !1); g.highlight(); return g.series.highlightFirstValidPoint()
        }; a.Chart.prototype.highlightAdjacentPointVertical = function (b) {
            var c = this.highlightedPoint, a = Infinity, d; if (!m(c.plotX) || !m(c.plotY)) return !1; this.series.forEach(function (g) { p(g) || g.points.forEach(function (w) { if (m(w.plotY) && m(w.plotX) && w !== c) { var e = w.plotY - c.plotY, x = Math.abs(w.plotX - c.plotX); x = Math.abs(e) * Math.abs(e) + x * x * 4; g.yAxis && g.yAxis.reversed && (e *= -1); !(0 >= e && b || 0 <= e && !b || 5 > x || k(w)) && x < a && (a = x, d = w) } }) }); return d ? d.highlight() :
                !1
        }; g(b.prototype, {
            init: function () { var b = this, d = this.chart, g = this.eventProvider = new n; g.addEvent(a.Series, "destroy", function () { return b.onSeriesDestroy(this) }); g.addEvent(d, "afterDrilldown", function () { c(this); this.focusElement && this.focusElement.removeFocusBorder() }); g.addEvent(d, "drilldown", function (c) { c = c.point; var a = c.series; b.lastDrilledDownPoint = { x: c.x, y: c.y, seriesName: a ? a.name : "" } }); g.addEvent(d, "drillupall", function () { setTimeout(function () { b.onDrillupAll() }, 10) }) }, onDrillupAll: function () {
                var b =
                    this.lastDrilledDownPoint, c = this.chart, a = b && A(c, b.seriesName), d; b && a && m(b.x) && m(b.y) && (d = x(a, b.x, b.y)); c.container && c.container.focus(); d && d.highlight && d.highlight(); c.focusElement && c.focusElement.removeFocusBorder()
            }, getKeyboardNavigationHandler: function () {
                var b = this, c = this.keyCodes, a = this.chart, d = a.inverted; return new l(a, {
                    keyCodeMap: [[d ? [c.up, c.down] : [c.left, c.right], function (c) { return b.onKbdSideways(this, c) }], [d ? [c.left, c.right] : [c.up, c.down], function (c) { return b.onKbdVertical(this, c) }], [[c.enter,
                    c.space], function () { a.highlightedPoint && a.highlightedPoint.firePointEvent("click"); return this.response.success }]], init: function (c) { return b.onHandlerInit(this, c) }, terminate: function () { return b.onHandlerTerminate() }
                })
            }, onKbdSideways: function (b, c) { var a = this.keyCodes; return this.attemptHighlightAdjacentPoint(b, c === a.right || c === a.down) }, onKbdVertical: function (b, c) {
                var a = this.chart, d = this.keyCodes; c = c === d.down || c === d.right; d = a.options.accessibility.keyboardNavigation.seriesNavigation; if (d.mode && "serialize" ===
                    d.mode) return this.attemptHighlightAdjacentPoint(b, c); a[a.highlightedPoint && a.highlightedPoint.series.keyboardMoveVertical ? "highlightAdjacentPointVertical" : "highlightAdjacentSeries"](c); return b.response.success
            }, onHandlerInit: function (b, a) { var d = this.chart; if (0 < a) c(d); else { a = d.series.length; for (var g; a-- && !(d.highlightedPoint = d.series[a].points[d.series[a].points.length - 1], g = d.series[a].highlightFirstValidPoint());); } return b.response.success }, onHandlerTerminate: function () {
                var b, c, a = this.chart, d =
                    a.highlightedPoint; null === (b = a.tooltip) || void 0 === b ? void 0 : b.hide(0); null === (c = null === d || void 0 === d ? void 0 : d.onMouseOut) || void 0 === c ? void 0 : c.call(d); delete a.highlightedPoint
            }, attemptHighlightAdjacentPoint: function (b, c) { var a = this.chart, d = a.options.accessibility.keyboardNavigation.wrapAround; return a.highlightAdjacentPoint(c) ? b.response.success : d ? b.init(c ? 1 : -1) : b.response[c ? "next" : "prev"] }, onSeriesDestroy: function (b) {
                var c = this.chart; c.highlightedPoint && c.highlightedPoint.series === b && (delete c.highlightedPoint,
                    c.focusElement && c.focusElement.removeFocusBorder())
            }, destroy: function () { this.eventProvider.removeAddedEvents() }
        }); return b
    }); r(a, "modules/accessibility/components/AnnotationsA11y.js", [a["parts/Utilities.js"], a["modules/accessibility/utils/htmlUtilities.js"]], function (a, f) {
        function h(a) { return (a.annotations || []).reduce(function (c, b) { var a; !1 !== (null === (a = b.options) || void 0 === a ? void 0 : a.visible) && (c = c.concat(b.labels)); return c }, []) } function l(a) {
            var c, b, d, m, e = null === (b = null === (c = a.options) || void 0 ===
                c ? void 0 : c.accessibility) || void 0 === b ? void 0 : b.description; return e ? e : (null === (m = null === (d = a.graphic) || void 0 === d ? void 0 : d.text) || void 0 === m ? void 0 : m.textStr) || ""
        } function n(a) {
            var c, b, d = null === (b = null === (c = a.options) || void 0 === c ? void 0 : c.accessibility) || void 0 === b ? void 0 : b.description; if (d) return d; c = a.chart; b = l(a); d = a.points.filter(function (b) { return !!b.graphic }).map(function (b) {
                var c, a; if (!(a = null === (c = null === b || void 0 === b ? void 0 : b.accessibility) || void 0 === c ? void 0 : c.valueDescription)) {
                    var d, g;
                    a = (null === (g = null === (d = null === b || void 0 === b ? void 0 : b.graphic) || void 0 === d ? void 0 : d.element) || void 0 === g ? void 0 : g.getAttribute("aria-label")) || ""
                } b = (null === b || void 0 === b ? void 0 : b.series.name) || ""; return (b ? b + ", " : "") + "data point " + a
            }).filter(function (b) { return !!b }); var m = d.length; a = "accessibility.screenReaderSection.annotations.description" + (1 < m ? "MultiplePoints" : m ? "SinglePoint" : "NoPoints"); b = { annotationText: b, numPoints: m, annotationPoint: d[0], additionalAnnotationPoints: d.slice(1) }; return c.langFormat(a,
                b)
        } function e(a) { return h(a).map(function (c) { return (c = p(k(n(c)))) ? "<li>" + c + "</li>" : "" }) } var t = a.inArray, p = f.escapeStringForHTML, k = f.stripHTMLTagsFromString; return { getAnnotationsInfoHTML: function (a) { var c = a.annotations; return c && c.length ? "<ul>" + e(a).join(" ") + "</ul>" : "" }, getAnnotationLabelDescription: n, getAnnotationListItems: e, getPointAnnotationTexts: function (a) { var c = h(a.series.chart).filter(function (b) { return -1 < t(a, b.points) }); return c.length ? c.map(function (b) { return "" + l(b) }) : [] } }
    }); r(a, "modules/accessibility/components/SeriesComponent/SeriesDescriber.js",
        [a["parts/Utilities.js"], a["modules/accessibility/components/AnnotationsA11y.js"], a["modules/accessibility/utils/htmlUtilities.js"], a["modules/accessibility/utils/chartUtilities.js"], a["parts/Tooltip.js"]], function (a, f, q, l, n) {
            function e(b) { var c = b.index; return b.series && b.series.data && F(c) ? C(b.series.data, function (b) { return !!(b && "undefined" !== typeof b.index && b.index > c && b.graphic && b.graphic.element) }) || null : null } function h(b) {
                var c = b.chart.options.accessibility.series.pointDescriptionEnabledThreshold;
                return !!(!1 !== c && b.points && b.points.length >= c)
            } function p(b) { var c = b.options.accessibility || {}; return !h(b) && !c.exposeAsGroupOnly } function k(b) { var c = b.chart.options.accessibility.keyboardNavigation.seriesNavigation; return !(!b.points || !(b.points.length < c.pointNavigationEnabledThreshold || !1 === c.pointNavigationEnabledThreshold)) } function d(b, c) {
                var a = b.series.chart, d = a.options.accessibility.point || {}; b = b.series.tooltipOptions || {}; a = a.options.lang; return w(c) ? D(c, d.valueDecimals || b.valueDecimals || -1,
                    a.decimalPoint, a.accessibility.thousandsSep || a.thousandsSep) : c
            } function c(b) { var c = (b.options.accessibility || {}).description; return c && b.chart.langFormat("accessibility.series.description", { description: c, series: b }) || "" } function b(b, c) { return b.chart.langFormat("accessibility.series." + c + "Description", { name: J(b[c]), series: b }) } function g(b) {
                var c = b.series, a = c.chart, d = a.options.accessibility.point || {}; if (c.xAxis && c.xAxis.dateTime) return c = n.prototype.getXDateFormat.call({
                    getDateFormat: n.prototype.getDateFormat,
                    chart: a
                }, b, a.options.tooltip, c.xAxis), d = d.dateFormatter && d.dateFormatter(b) || d.dateFormat || c, a.time.dateFormat(d, b.x, void 0)
            } function m(b) { var c = g(b), a = (b.series.xAxis || {}).categories && F(b.category) && ("" + b.category).replace("<br/>", " "), d = b.id && 0 > b.id.indexOf("highcharts-"), m = "x, " + b.x; return b.name || c || a || (d ? b.id : m) } function x(b, c, a) { var g = c || "", m = a || ""; return b.series.pointArrayMap.reduce(function (c, a) { c += c.length ? ", " : ""; var e = d(b, y(b[a], b.options[a])); return c + (a + ": " + g + e + m) }, "") } function A(b) {
                var c =
                    b.series, a = c.chart.options.accessibility.point || {}, g = c.tooltipOptions || {}, m = a.valuebacker || g.valuebacker || ""; a = a.valueSuffix || g.valueSuffix || ""; g = d(b, b["undefined" !== typeof b.value ? "value" : "y"]); return b.isNull ? c.chart.langFormat("accessibility.series.nullPointValue", { point: b }) : c.pointArrayMap ? x(b, m, a) : m + g + a
            } function B(b) {
                var c = b.series, a = c.chart, d = a.options.accessibility.point.valueDescriptionFormat, g = (c = y(c.xAxis && c.xAxis.options.accessibility && c.xAxis.options.accessibility.enabled, !a.angular)) ?
                    m(b) : ""; b = { point: b, index: F(b.index) ? b.index + 1 : "", xDescription: g, value: A(b), separator: c ? ", " : "" }; return E(d, b, a)
            } function u(b) {
                var c = b.series, a = c.chart, d = B(b), g = b.options && b.options.accessibility && b.options.accessibility.description; g = g ? " " + g : ""; c = 1 < a.series.length && c.name ? " " + c.name + "." : ""; a = b.series.chart; var m = I(b), e = { point: b, annotations: m }; a = m.length ? a.langFormat("accessibility.series.pointAnnotationsDescription", e) : ""; b.accessibility = b.accessibility || {}; b.accessibility.valueDescription = d; return d +
                    g + c + (a ? " " + a : "")
            } function r(b) {
                var c = p(b), a = k(b); (c || a) && b.points.forEach(function (b) {
                    var a; if (!(a = b.graphic && b.graphic.element) && (a = b.series && b.series.is("sunburst"), a = b.isNull && !a)) {
                        var d = b.series, g = e(b); d = (a = g && g.graphic) ? a.parentGroup : d.graph || d.group; g = g ? { x: y(b.plotX, g.plotX, 0), y: y(b.plotY, g.plotY, 0) } : { x: y(b.plotX, 0), y: y(b.plotY, 0) }; g = b.series.chart.renderer.rect(g.x, g.y, 1, 1); g.attr({ "class": "highcharts-a11y-dummy-point", fill: "none", opacity: 0, "fill-opacity": 0, "stroke-opacity": 0 }); d && d.element ?
                            (b.graphic = g, b.hasDummyGraphic = !0, g.add(d), d.element.insertBefore(g.element, a ? a.element : null), a = g.element) : a = void 0
                    } a && (a.setAttribute("tabindex", "-1"), a.style.outline = "0", c ? (g = b.series, d = g.chart.options.accessibility.point || {}, g = g.options.accessibility || {}, b = G(H(g.pointDescriptionFormatter && g.pointDescriptionFormatter(b) || d.descriptionFormatter && d.descriptionFormatter(b) || u(b))), a.setAttribute("role", "img"), a.setAttribute("aria-label", b)) : a.setAttribute("aria-hidden", !0))
                })
            } function v(a) {
                var d = a.chart,
                g = d.types || [], m = c(a), e = function (b) { return d[b] && 1 < d[b].length && a[b] }, w = b(a, "xAxis"), k = b(a, "yAxis"), y = { name: a.name || "", ix: a.index + 1, numSeries: d.series && d.series.length, numPoints: a.points && a.points.length, series: a }; g = 1 < g.length ? "Combination" : ""; return (d.langFormat("accessibility.series.summary." + a.type + g, y) || d.langFormat("accessibility.series.summary.default" + g, y)) + (m ? " " + m : "") + (e("yAxis") ? " " + k : "") + (e("xAxis") ? " " + w : "")
            } var C = a.find, E = a.format, w = a.isNumber, D = a.numberFormat, y = a.pick, F = a.defined, I = f.getPointAnnotationTexts,
                G = q.escapeStringForHTML, K = q.reverseChildNodes, H = q.stripHTMLTagsFromString, J = l.getAxisDescription, L = l.getSeriesFirstPointElement, M = l.getSeriesA11yElement, N = l.unhideChartElementFromAT; return {
                    describeSeries: function (b) {
                        var c = b.chart, a = L(b), d = M(b), g = c.is3d && c.is3d(); if (d) {
                            d.lastChild !== a || g || K(d); r(b); N(c, d); g = b.chart; c = g.options.chart || {}; a = 1 < g.series.length; g = g.options.accessibility.series.describeSingleSeries; var m = (b.options.accessibility || {}).exposeAsGroupOnly; c.options3d && c.options3d.enabled && a ||
                                !(a || g || m || h(b)) ? d.setAttribute("aria-label", "") : (c = b.chart.options.accessibility, a = c.landmarkVerbosity, (b.options.accessibility || {}).exposeAsGroupOnly ? d.setAttribute("role", "img") : "all" === a && d.setAttribute("role", "region"), d.setAttribute("tabindex", "-1"), d.style.outline = "0", d.setAttribute("aria-label", G(H(c.series.descriptionFormatter && c.series.descriptionFormatter(b) || v(b)))))
                        }
                    }, defaultPointDescriptionFormatter: u, defaultSeriesDescriptionFormatter: v, getPointA11yTimeDescription: g, getPointXDescription: m,
                    getPointValue: A, getPointValueDescription: B
                }
        }); r(a, "modules/accessibility/utils/Announcer.js", [a["parts/Globals.js"], a["modules/accessibility/utils/DOMElementProvider.js"], a["modules/accessibility/utils/htmlUtilities.js"]], function (a, f, q) {
            var h = q.visuallyHideElement; q = function () {
                function a(a, h) { this.chart = a; this.domElementProvider = new f; this.announceRegion = this.addAnnounceRegion(h) } a.prototype.destroy = function () { this.domElementProvider.destroyCreatedElements() }; a.prototype.announce = function (a) {
                    var e =
                        this; this.announceRegion.innerHTML = a; this.clearAnnouncementRegionTimer && clearTimeout(this.clearAnnouncementRegionTimer); this.clearAnnouncementRegionTimer = setTimeout(function () { e.announceRegion.innerHTML = ""; delete e.clearAnnouncementRegionTimer }, 1E3)
                }; a.prototype.addAnnounceRegion = function (a) { var e = this.chart.renderTo, f = this.domElementProvider.createElement("div"); f.setAttribute("aria-hidden", !1); f.setAttribute("aria-live", a); h(f); e.insertBefore(f, e.firstChild); return f }; return a
            }(); return a.Announcer =
                q
        }); r(a, "modules/accessibility/components/SeriesComponent/NewDataAnnouncer.js", [a["parts/Globals.js"], a["parts/Utilities.js"], a["modules/accessibility/utils/chartUtilities.js"], a["modules/accessibility/components/SeriesComponent/SeriesDescriber.js"], a["modules/accessibility/utils/Announcer.js"], a["modules/accessibility/utils/EventProvider.js"]], function (a, f, q, l, n, e) {
            function h(b) { var c = b.series.data.filter(function (c) { return b.x === c.x && b.y === c.y }); return 1 === c.length ? c[0] : b } function p(b, c) {
                var a =
                    (b || []).concat(c || []).reduce(function (b, c) { b[c.name + c.index] = c; return b }, {}); return Object.keys(a).map(function (b) { return a[b] })
            } var k = f.extend, d = f.defined, c = q.getChartTitle, b = l.defaultPointDescriptionFormatter, g = l.defaultSeriesDescriptionFormatter; f = function (b) { this.chart = b }; k(f.prototype, {
                init: function () {
                    var b = this.chart, c = b.options.accessibility.announceNewData.interruptUser ? "assertive" : "polite"; this.lastAnnouncementTime = 0; this.dirty = { allSeries: {} }; this.eventProvider = new e; this.announcer = new n(b,
                        c); this.addEventListeners()
                }, destroy: function () { this.eventProvider.removeAddedEvents(); this.announcer.destroy() }, addEventListeners: function () { var b = this, c = this.chart, d = this.eventProvider; d.addEvent(c, "afterDrilldown", function () { b.lastAnnouncementTime = 0 }); d.addEvent(a.Series, "updatedData", function () { b.onSeriesUpdatedData(this) }); d.addEvent(c, "afterAddSeries", function (c) { b.onSeriesAdded(c.series) }); d.addEvent(a.Series, "addPoint", function (c) { b.onPointAdded(c.point) }); d.addEvent(c, "redraw", function () { b.announceDirtyData() }) },
                onSeriesUpdatedData: function (b) { var c = this.chart; b.chart === c && c.options.accessibility.announceNewData.enabled && (this.dirty.hasDirty = !0, this.dirty.allSeries[b.name + b.index] = b) }, onSeriesAdded: function (b) { this.chart.options.accessibility.announceNewData.enabled && (this.dirty.hasDirty = !0, this.dirty.allSeries[b.name + b.index] = b, this.dirty.newSeries = d(this.dirty.newSeries) ? void 0 : b) }, onPointAdded: function (b) {
                    var c = b.series.chart; this.chart === c && c.options.accessibility.announceNewData.enabled && (this.dirty.newPoint =
                        d(this.dirty.newPoint) ? void 0 : b)
                }, announceDirtyData: function () { var b = this; if (this.chart.options.accessibility.announceNewData && this.dirty.hasDirty) { var c = this.dirty.newPoint; c && (c = h(c)); this.queueAnnouncement(Object.keys(this.dirty.allSeries).map(function (c) { return b.dirty.allSeries[c] }), this.dirty.newSeries, c); this.dirty = { allSeries: {} } } }, queueAnnouncement: function (b, c, a) {
                    var d = this, g = this.chart.options.accessibility.announceNewData; if (g.enabled) {
                        var e = +new Date; g = Math.max(0, g.minAnnounceInterval -
                            (e - this.lastAnnouncementTime)); b = p(this.queuedAnnouncement && this.queuedAnnouncement.series, b); if (c = this.buildAnnouncementMessage(b, c, a)) this.queuedAnnouncement && clearTimeout(this.queuedAnnouncementTimer), this.queuedAnnouncement = { time: e, message: c, series: b }, this.queuedAnnouncementTimer = setTimeout(function () { d && d.announcer && (d.lastAnnouncementTime = +new Date, d.announcer.announce(d.queuedAnnouncement.message), delete d.queuedAnnouncement, delete d.queuedAnnouncementTimer) }, g)
                    }
                }, buildAnnouncementMessage: function (d,
                    e, k) { var m = this.chart, f = m.options.accessibility.announceNewData; if (f.announcementFormatter && (d = f.announcementFormatter(d, e, k), !1 !== d)) return d.length ? d : null; d = a.charts && 1 < a.charts.length ? "Multiple" : "Single"; d = e ? "newSeriesAnnounce" + d : k ? "newPointAnnounce" + d : "newDataAnnounce"; f = c(m); return m.langFormat("accessibility.announceNewData." + d, { chartTitle: f, seriesDesc: e ? g(e) : null, pointDesc: k ? b(k) : null, point: k, series: e }) }
            }); return f
        }); r(a, "modules/accessibility/components/SeriesComponent/forcedMarkers.js", [a["parts/Globals.js"],
        a["parts/Utilities.js"]], function (a, f) {
            function h(a) { n(!0, a, { marker: { enabled: !0, states: { normal: { opacity: 0 } } } }) } var l = f.addEvent, n = f.merge; return function () {
                l(a.Series, "render", function () {
                    var a = this.options, f = this.chart.options.accessibility.enabled, p = !1 !== (this.options.accessibility && this.options.accessibility.enabled); var k = this.chart.options.accessibility; k = this.points.length < k.series.pointDescriptionEnabledThreshold || !1 === k.series.pointDescriptionEnabledThreshold; var d = this.chart.options.accessibility.keyboardNavigation.seriesNavigation;
                    d = this.points.length < d.pointNavigationEnabledThreshold || !1 === d.pointNavigationEnabledThreshold; if (f && p && (k || d)) { if (a.marker && !1 === a.marker.enabled && (this.a11yMarkersForced = !0, h(this.options)), this._hasPointMarkers && this.points && this.points.length) for (a = this.points, f = a.length; f--;)p = a[f].options, p.marker && (p.marker.enabled ? n(!0, p.marker, { states: { normal: { opacity: p.marker.states && p.marker.states.normal && p.marker.states.normal.opacity || 1 } } }) : h(p)) } else this.a11yMarkersForced && this.resetMarkerOptions &&
                        (delete this.a11yMarkersForced, a = this.resetA11yMarkerOptions, n(!0, this.options, { marker: { enabled: a.enabled, states: { normal: { opacity: a.states && a.states.normal && a.states.normal.opacity } } } }))
                }); l(a.Series, "afterSetOptions", function (a) { this.resetA11yMarkerOptions = n(a.options.marker || {}, this.userOptions.marker || {}) })
            }
        }); r(a, "modules/accessibility/components/SeriesComponent/SeriesComponent.js", [a["parts/Globals.js"], a["parts/Utilities.js"], a["modules/accessibility/AccessibilityComponent.js"], a["modules/accessibility/components/SeriesComponent/SeriesKeyboardNavigation.js"],
        a["modules/accessibility/components/SeriesComponent/NewDataAnnouncer.js"], a["modules/accessibility/components/SeriesComponent/forcedMarkers.js"], a["modules/accessibility/utils/chartUtilities.js"], a["modules/accessibility/components/SeriesComponent/SeriesDescriber.js"], a["parts/Tooltip.js"]], function (a, f, q, l, n, e, t, p, k) {
            f = f.extend; var d = t.hideSeriesFromAT, c = p.describeSeries; a.SeriesAccessibilityDescriber = p; e(); a = function () { }; a.prototype = new q; f(a.prototype, {
                init: function () {
                    this.newDataAnnouncer = new n(this.chart);
                    this.newDataAnnouncer.init(); this.keyboardNavigation = new l(this.chart, this.keyCodes); this.keyboardNavigation.init(); this.hideTooltipFromATWhenShown(); this.hideSeriesLabelsFromATWhenShown()
                }, hideTooltipFromATWhenShown: function () { var b = this; this.addEvent(k, "refresh", function () { this.chart === b.chart && this.label && this.label.element && this.label.element.setAttribute("aria-hidden", !0) }) }, hideSeriesLabelsFromATWhenShown: function () {
                    this.addEvent(this.chart, "afterDrawSeriesLabels", function () {
                        this.series.forEach(function (b) {
                            b.labelBySeries &&
                            b.labelBySeries.attr("aria-hidden", !0)
                        })
                    })
                }, onChartRender: function () { this.chart.series.forEach(function (b) { !1 !== (b.options.accessibility && b.options.accessibility.enabled) && b.visible ? c(b) : d(b) }) }, getKeyboardNavigation: function () { return this.keyboardNavigation.getKeyboardNavigationHandler() }, destroy: function () { this.newDataAnnouncer.destroy(); this.keyboardNavigation.destroy() }
            }); return a
        }); r(a, "modules/accessibility/components/ZoomComponent.js", [a["parts/Globals.js"], a["parts/Utilities.js"], a["modules/accessibility/AccessibilityComponent.js"],
        a["modules/accessibility/KeyboardNavigationHandler.js"], a["modules/accessibility/utils/chartUtilities.js"], a["modules/accessibility/utils/htmlUtilities.js"]], function (a, f, q, l, n, e) {
            var h = f.extend, p = f.pick, k = n.unhideChartElementFromAT, d = e.setElAttrs, c = e.removeElement; a.Axis.prototype.panStep = function (b, c) { var a = c || 3; c = this.getExtremes(); var d = (c.max - c.min) / a * b; a = c.max + d; d = c.min + d; var g = a - d; 0 > b && d < c.dataMin ? (d = c.dataMin, a = d + g) : 0 < b && a > c.dataMax && (a = c.dataMax, d = a - g); this.setExtremes(d, a) }; a = function () { };
            a.prototype = new q; h(a.prototype, {
                init: function () { var b = this, c = this.chart;["afterShowResetZoom", "afterDrilldown", "drillupall"].forEach(function (a) { b.addEvent(c, a, function () { b.updateProxyOverlays() }) }) }, onChartUpdate: function () { var b = this.chart, c = this; b.mapNavButtons && b.mapNavButtons.forEach(function (a, d) { k(b, a.element); c.setMapNavButtonAttrs(a.element, "accessibility.zoom.mapZoom" + (d ? "Out" : "In")) }) }, setMapNavButtonAttrs: function (b, c) {
                    var a = this.chart; c = a.langFormat(c, { chart: a }); d(b, {
                        tabindex: -1, role: "button",
                        "aria-label": c
                    })
                }, onChartRender: function () { this.updateProxyOverlays() }, updateProxyOverlays: function () {
                    var b = this.chart; c(this.drillUpProxyGroup); c(this.resetZoomProxyGroup); b.resetZoomButton && this.recreateProxyButtonAndGroup(b.resetZoomButton, "resetZoomProxyButton", "resetZoomProxyGroup", b.langFormat("accessibility.zoom.resetZoomButton", { chart: b })); b.drillUpButton && this.recreateProxyButtonAndGroup(b.drillUpButton, "drillUpProxyButton", "drillUpProxyGroup", b.langFormat("accessibility.drillUpButton", {
                        chart: b,
                        buttonText: b.getDrilldownBackText()
                    }))
                }, recreateProxyButtonAndGroup: function (b, a, d, e) { c(this[d]); this[d] = this.addProxyGroup(); this[a] = this.createProxyButton(b, this[d], { "aria-label": e, tabindex: -1 }) }, getMapZoomNavigation: function () {
                    var b = this.keyCodes, c = this.chart, a = this; return new l(c, {
                        keyCodeMap: [[[b.up, b.down, b.left, b.right], function (b) { return a.onMapKbdArrow(this, b) }], [[b.tab], function (b, c) { return a.onMapKbdTab(this, c) }], [[b.space, b.enter], function () { return a.onMapKbdClick(this) }]], validate: function () {
                            return !!(c.mapZoom &&
                                c.mapNavButtons && c.mapNavButtons.length)
                        }, init: function (b) { return a.onMapNavInit(b) }
                    })
                }, onMapKbdArrow: function (b, c) { var a = this.keyCodes; this.chart[c === a.up || c === a.down ? "yAxis" : "xAxis"][0].panStep(c === a.left || c === a.up ? -1 : 1); return b.response.success }, onMapKbdTab: function (b, c) {
                    var a = this.chart; b = b.response; var d = (c = c.shiftKey) && !this.focusedMapNavButtonIx || !c && this.focusedMapNavButtonIx; a.mapNavButtons[this.focusedMapNavButtonIx].setState(0); if (d) return a.mapZoom(), b[c ? "prev" : "next"]; this.focusedMapNavButtonIx +=
                        c ? -1 : 1; c = a.mapNavButtons[this.focusedMapNavButtonIx]; a.setFocusToElement(c.box, c.element); c.setState(2); return b.success
                }, onMapKbdClick: function (b) { this.fakeClickEvent(this.chart.mapNavButtons[this.focusedMapNavButtonIx].element); return b.response.success }, onMapNavInit: function (b) { var c = this.chart, a = c.mapNavButtons[0], d = c.mapNavButtons[1]; a = 0 < b ? a : d; c.setFocusToElement(a.box, a.element); a.setState(2); this.focusedMapNavButtonIx = 0 < b ? 0 : 1 }, simpleButtonNavigation: function (b, c, a) {
                    var d = this.keyCodes, g = this,
                    e = this.chart; return new l(e, { keyCodeMap: [[[d.tab, d.up, d.down, d.left, d.right], function (b, c) { return this.response[b === d.tab && c.shiftKey || b === d.left || b === d.up ? "prev" : "next"] }], [[d.space, d.enter], function () { var b = a(this, e); return p(b, this.response.success) }]], validate: function () { return e[b] && e[b].box && g[c] }, init: function () { e.setFocusToElement(e[b].box, g[c]) } })
                }, getKeyboardNavigation: function () {
                    return [this.simpleButtonNavigation("resetZoomButton", "resetZoomProxyButton", function (b, c) { c.zoomOut() }), this.simpleButtonNavigation("drillUpButton",
                        "drillUpProxyButton", function (b, c) { c.drillUp(); return b.response.prev }), this.getMapZoomNavigation()]
                }
            }); return a
        }); r(a, "modules/accessibility/components/RangeSelectorComponent.js", [a["parts/Globals.js"], a["parts/Utilities.js"], a["modules/accessibility/AccessibilityComponent.js"], a["modules/accessibility/KeyboardNavigationHandler.js"], a["modules/accessibility/utils/chartUtilities.js"], a["modules/accessibility/utils/htmlUtilities.js"]], function (a, f, q, l, n, e) {
            f = f.extend; var h = n.unhideChartElementFromAT,
                p = e.setElAttrs; a.Chart.prototype.highlightRangeSelectorButton = function (a) { var d = this.rangeSelector.buttons, c = this.highlightedRangeSelectorItemIx; "undefined" !== typeof c && d[c] && d[c].setState(this.oldRangeSelectorItemState || 0); this.highlightedRangeSelectorItemIx = a; return d[a] ? (this.setFocusToElement(d[a].box, d[a].element), this.oldRangeSelectorItemState = d[a].state, d[a].setState(2), !0) : !1 }; a = function () { }; a.prototype = new q; f(a.prototype, {
                    onChartUpdate: function () {
                        var a = this.chart, d = this, c = a.rangeSelector;
                        c && (c.buttons && c.buttons.length && c.buttons.forEach(function (b) { h(a, b.element); d.setRangeButtonAttrs(b) }), c.maxInput && c.minInput && ["minInput", "maxInput"].forEach(function (b, g) { if (b = c[b]) h(a, b), d.setRangeInputAttrs(b, "accessibility.rangeSelector." + (g ? "max" : "min") + "InputLabel") }))
                    }, setRangeButtonAttrs: function (a) { var d = this.chart; d = d.langFormat("accessibility.rangeSelector.buttonText", { chart: d, buttonText: a.text && a.text.textStr }); p(a.element, { tabindex: -1, role: "button", "aria-label": d }) }, setRangeInputAttrs: function (a,
                        d) { var c = this.chart; p(a, { tabindex: -1, role: "textbox", "aria-label": c.langFormat(d, { chart: c }) }) }, getRangeSelectorButtonNavigation: function () {
                            var a = this.chart, d = this.keyCodes, c = this; return new l(a, {
                                keyCodeMap: [[[d.left, d.right, d.up, d.down], function (b) { return c.onButtonNavKbdArrowKey(this, b) }], [[d.enter, d.space], function () { return c.onButtonNavKbdClick(this) }]], validate: function () { return a.rangeSelector && a.rangeSelector.buttons && a.rangeSelector.buttons.length }, init: function (b) {
                                    var c = a.rangeSelector.buttons.length -
                                        1; a.highlightRangeSelectorButton(0 < b ? 0 : c)
                                }
                            })
                        }, onButtonNavKbdArrowKey: function (a, d) { var c = a.response, b = this.keyCodes, g = this.chart, e = g.options.accessibility.keyboardNavigation.wrapAround; d = d === b.left || d === b.up ? -1 : 1; return g.highlightRangeSelectorButton(g.highlightedRangeSelectorItemIx + d) ? c.success : e ? (a.init(d), c.success) : c[0 < d ? "next" : "prev"] }, onButtonNavKbdClick: function (a) {
                            a = a.response; var d = this.chart; 3 !== d.oldRangeSelectorItemState && this.fakeClickEvent(d.rangeSelector.buttons[d.highlightedRangeSelectorItemIx].element);
                            return a.success
                        }, getRangeSelectorInputNavigation: function () {
                            var a = this.chart, d = this.keyCodes, c = this; return new l(a, {
                                keyCodeMap: [[[d.tab, d.up, d.down], function (b, a) { return c.onInputKbdMove(this, b === d.tab && a.shiftKey || b === d.up ? -1 : 1) }]], validate: function () { return a.rangeSelector && a.rangeSelector.inputGroup && "hidden" !== a.rangeSelector.inputGroup.element.getAttribute("visibility") && !1 !== a.options.rangeSelector.inputEnabled && a.rangeSelector.minInput && a.rangeSelector.maxInput }, init: function (b) { c.onInputNavInit(b) },
                                terminate: function () { c.onInputNavTerminate() }
                            })
                        }, onInputKbdMove: function (a, d) { var c = this.chart; a = a.response; var b = c.highlightedInputRangeIx += d; if (1 < b || 0 > b) return a[0 < d ? "next" : "prev"]; c.rangeSelector[b ? "maxInput" : "minInput"].focus(); return a.success }, onInputNavInit: function (a) { var d = this.chart; a = 0 < a ? 0 : 1; d.highlightedInputRangeIx = a; d.rangeSelector[a ? "maxInput" : "minInput"].focus() }, onInputNavTerminate: function () { var a = this.chart.rangeSelector || {}; a.maxInput && a.hideInput("max"); a.minInput && a.hideInput("min") },
                    getKeyboardNavigation: function () { return [this.getRangeSelectorButtonNavigation(), this.getRangeSelectorInputNavigation()] }
                }); return a
        }); r(a, "modules/accessibility/components/InfoRegionsComponent.js", [a["parts/Globals.js"], a["parts/Utilities.js"], a["modules/accessibility/AccessibilityComponent.js"], a["modules/accessibility/utils/Announcer.js"], a["modules/accessibility/components/AnnotationsA11y.js"], a["modules/accessibility/utils/chartUtilities.js"], a["modules/accessibility/utils/htmlUtilities.js"]],
            function (a, f, q, l, n, e, t) {
                function h(b) { return b.replace(/&lt;(h[1-7]|p|div|ul|ol|li)&gt;/g, "<$1>").replace(/&lt;&#x2F;(h[1-7]|p|div|ul|ol|li|a|button)&gt;/g, "</$1>").replace(/&lt;(div|a|button) id=&quot;([a-zA-Z\-0-9#]*?)&quot;&gt;/g, '<$1 id="$2">') } var k = a.win.document, d = f.extend, c = f.format, b = f.pick, g = n.getAnnotationsInfoHTML, m = e.unhideChartElementFromAT, r = e.getChartTitle, A = e.getAxisDescription, B = t.addClass, u = t.setElAttrs, z = t.escapeStringForHTML, v = t.stripHTMLTagsFromString, C = t.getElement, E = t.visuallyHideElement;
                a.Chart.prototype.getTypeDescription = function (b) {
                    var a = b[0], c = this.series && this.series[0] || {}; c = { numSeries: this.series.length, numPoints: c.points && c.points.length, chart: this, mapTitle: c.mapTitle }; if (!a) return this.langFormat("accessibility.chartTypes.emptyChart", c); if ("map" === a) return c.mapTitle ? this.langFormat("accessibility.chartTypes.mapTypeDescription", c) : this.langFormat("accessibility.chartTypes.unknownMap", c); if (1 < this.types.length) return this.langFormat("accessibility.chartTypes.combinationChart",
                        c); b = b[0]; a = this.langFormat("accessibility.seriesTypeDescriptions." + b, c); var d = this.series && 2 > this.series.length ? "Single" : "Multiple"; return (this.langFormat("accessibility.chartTypes." + b + d, c) || this.langFormat("accessibility.chartTypes.default" + d, c)) + (a ? " " + a : "")
                }; f = function () { }; f.prototype = new q; d(f.prototype, {
                    init: function () {
                        var b = this.chart, a = this; this.initRegionsDefinitions(); this.addEvent(b, "afterGetTable", function (b) { a.onDataTableCreated(b) }); this.addEvent(b, "afterViewData", function (b) {
                            a.dataTableDiv =
                            b; setTimeout(function () { a.focusDataTable() }, 300)
                        }); this.announcer = new l(b, "assertive")
                    }, initRegionsDefinitions: function () {
                        var b = this; this.screenReaderSections = {
                            before: {
                                element: null, buildContent: function (a) { var c = a.options.accessibility.screenReaderSection.beforeChartFormatter; return c ? c(a) : b.defaultBeforeChartFormatter(a) }, insertIntoDOM: function (b, a) { a.renderTo.insertBefore(b, a.renderTo.firstChild) }, afterInserted: function () {
                                    "undefined" !== typeof b.sonifyButtonId && b.initSonifyButton(b.sonifyButtonId);
                                    "undefined" !== typeof b.dataTableButtonId && b.initDataTableButton(b.dataTableButtonId)
                                }
                            }, after: { element: null, buildContent: function (a) { var c = a.options.accessibility.screenReaderSection.afterChartFormatter; return c ? c(a) : b.defaultAfterChartFormatter() }, insertIntoDOM: function (b, a) { a.renderTo.insertBefore(b, a.container.nextSibling) } }
                        }
                    }, onChartRender: function () { var b = this; this.linkedDescriptionElement = this.getLinkedDescriptionElement(); this.setLinkedDescriptionAttrs(); Object.keys(this.screenReaderSections).forEach(function (a) { b.updateScreenReaderSection(a) }) },
                    getLinkedDescriptionElement: function () { var b = this.chart.options.accessibility.linkedDescription; if (b) { if ("string" !== typeof b) return b; b = c(b, this.chart); b = k.querySelectorAll(b); if (1 === b.length) return b[0] } }, setLinkedDescriptionAttrs: function () { var b = this.linkedDescriptionElement; b && (b.setAttribute("aria-hidden", "true"), B(b, "highcharts-linked-description")) }, updateScreenReaderSection: function (b) {
                        var a = this.chart, c = this.screenReaderSections[b], d = c.buildContent(a), g = c.element = c.element || this.createElement("div"),
                        e = g.firstChild || this.createElement("div"); this.setScreenReaderSectionAttribs(g, b); e.innerHTML = d; g.appendChild(e); c.insertIntoDOM(g, a); E(e); m(a, e); c.afterInserted && c.afterInserted()
                    }, setScreenReaderSectionAttribs: function (b, a) { var c = this.chart, d = c.langFormat("accessibility.screenReaderSection." + a + "RegionLabel", { chart: c }); u(b, { id: "highcharts-screen-reader-region-" + a + "-" + c.index, "aria-label": d }); b.style.position = "relative"; "all" === c.options.accessibility.landmarkVerbosity && d && b.setAttribute("role", "region") },
                    defaultBeforeChartFormatter: function () {
                        var b, c = this.chart, d = c.options.accessibility.screenReaderSection.beforeChartFormat, e = this.getAxesDescription(), f = c.sonify && (null === (b = c.options.sonification) || void 0 === b ? void 0 : b.enabled); b = "highcharts-a11y-sonify-data-btn-" + c.index; var m = "hc-linkto-highcharts-data-table-" + c.index, p = g(c), k = c.langFormat("accessibility.screenReaderSection.annotations.heading", { chart: c }); e = {
                            chartTitle: r(c), typeDescription: this.getTypeDescriptionText(), chartSubtitle: this.getSubtitleText(),
                            chartLongdesc: this.getLongdescText(), xAxisDescription: e.xAxis, yAxisDescription: e.yAxis, playAsSoundButton: f ? this.getSonifyButtonText(b) : "", viewTableButton: c.getCSV ? this.getDataTableButtonText(m) : "", annotationsTitle: p ? k : "", annotationsList: p
                        }; c = a.i18nFormat(d, e, c); this.dataTableButtonId = m; this.sonifyButtonId = b; return h(z(c)).replace(/<(\w+)[^>]*?>\s*<\/\1>/g, "")
                    }, defaultAfterChartFormatter: function () {
                        var b = this.chart, c = b.options.accessibility.screenReaderSection.afterChartFormat, d = { endOfChartMarker: this.getEndOfChartMarkerText() };
                        b = a.i18nFormat(c, d, b); return h(z(b)).replace(/<(\w+)[^>]*?>\s*<\/\1>/g, "")
                    }, getLinkedDescription: function () { var b = this.linkedDescriptionElement; return v(b && b.innerHTML || "") }, getLongdescText: function () { var b = this.chart.options, a = b.caption; a = a && a.text; var c = this.getLinkedDescription(); return b.accessibility.description || c || a || "" }, getTypeDescriptionText: function () { var b = this.chart; return b.types ? b.options.accessibility.typeDescription || b.getTypeDescription(b.types) : "" }, getDataTableButtonText: function (b) {
                        var a =
                            this.chart; a = a.langFormat("accessibility.table.viewAsDataTableButtonText", { chart: a, chartTitle: r(a) }); return '<a id="' + b + '">' + a + "</a>"
                    }, getSonifyButtonText: function (b) { var a, c = this.chart; if (!1 === (null === (a = c.options.sonification) || void 0 === a ? void 0 : a.enabled)) return ""; a = c.langFormat("accessibility.sonification.playAsSoundButtonText", { chart: c, chartTitle: r(c) }); return '<button id="' + b + '">' + a + "</button>" }, getSubtitleText: function () { var b = this.chart.options.subtitle; return v(b && b.text || "") }, getEndOfChartMarkerText: function () {
                        var b =
                            this.chart, a = b.langFormat("accessibility.screenReaderSection.endOfChartMarker", { chart: b }); return '<div id="highcharts-end-of-chart-marker-' + b.index + '">' + a + "</div>"
                    }, onDataTableCreated: function (b) { var a = this.chart; a.options.accessibility.enabled && (this.viewDataTableButton && this.viewDataTableButton.setAttribute("aria-expanded", "true"), b.html = b.html.replace("<table ", '<table tabindex="0" summary="' + a.langFormat("accessibility.table.tableSummary", { chart: a }) + '"')) }, focusDataTable: function () {
                        var b = this.dataTableDiv;
                        (b = b && b.getElementsByTagName("table")[0]) && b.focus && b.focus()
                    }, initSonifyButton: function (b) {
                        var a = this, c = this.sonifyButton = C(b), d = this.chart, g = function (b) {
                            null === c || void 0 === c ? void 0 : c.setAttribute("aria-hidden", "true"); null === c || void 0 === c ? void 0 : c.setAttribute("aria-label", ""); b.preventDefault(); b.stopPropagation(); b = d.langFormat("accessibility.sonification.playAsSoundClickAnnouncement", { chart: d }); a.announcer.announce(b); setTimeout(function () {
                                null === c || void 0 === c ? void 0 : c.removeAttribute("aria-hidden");
                                null === c || void 0 === c ? void 0 : c.removeAttribute("aria-label"); d.sonify && d.sonify()
                            }, 1E3)
                        }; c && d && (u(c, { tabindex: "-1" }), c.onclick = function (b) { var a; ((null === (a = d.options.accessibility) || void 0 === a ? void 0 : a.screenReaderSection.onPlayAsSoundClick) || g).call(this, b, d) })
                    }, initDataTableButton: function (b) {
                        var a = this.viewDataTableButton = C(b), c = this.chart; b = b.replace("hc-linkto-", ""); a && (u(a, { role: "button", tabindex: "-1", "aria-expanded": !!C(b), href: "#" + b }), a.onclick = c.options.accessibility.screenReaderSection.onViewDataTableClick ||
                            function () { c.viewData() })
                    }, getAxesDescription: function () { var a = this.chart, c = function (c, d) { c = a[c]; return 1 < c.length || c[0] && b(c[0].options.accessibility && c[0].options.accessibility.enabled, d) }, d = !!a.types && 0 > a.types.indexOf("map"), g = !!a.hasCartesianSeries, e = c("xAxis", !a.angular && g && d); c = c("yAxis", g && d); d = {}; e && (d.xAxis = this.getAxisDescriptionText("xAxis")); c && (d.yAxis = this.getAxisDescriptionText("yAxis")); return d }, getAxisDescriptionText: function (b) {
                        var a = this, c = this.chart, d = c[b]; return c.langFormat("accessibility.axis." +
                            b + "Description" + (1 < d.length ? "Plural" : "Singular"), { chart: c, names: d.map(function (b) { return A(b) }), ranges: d.map(function (b) { return a.getAxisRangeDescription(b) }), numAxes: d.length })
                    }, getAxisRangeDescription: function (b) { var a = b.options || {}; return a.accessibility && "undefined" !== typeof a.accessibility.rangeDescription ? a.accessibility.rangeDescription : b.categories ? this.getCategoryAxisRangeDesc(b) : !b.dateTime || 0 !== b.min && 0 !== b.dataMin ? this.getAxisFromToDescription(b) : this.getAxisTimeLengthDesc(b) }, getCategoryAxisRangeDesc: function (b) {
                        var a =
                            this.chart; return b.dataMax && b.dataMin ? a.langFormat("accessibility.axis.rangeCategories", { chart: a, axis: b, numCategories: b.dataMax - b.dataMin + 1 }) : ""
                    }, getAxisTimeLengthDesc: function (b) {
                        var a = this.chart, c = {}, d = "Seconds"; c.Seconds = ((b.max || 0) - (b.min || 0)) / 1E3; c.Minutes = c.Seconds / 60; c.Hours = c.Minutes / 60; c.Days = c.Hours / 24;["Minutes", "Hours", "Days"].forEach(function (b) { 2 < c[b] && (d = b) }); var g = c[d].toFixed("Seconds" !== d && "Minutes" !== d ? 1 : 0); return a.langFormat("accessibility.axis.timeRange" + d, {
                            chart: a, axis: b, range: g.replace(".0",
                                "")
                        })
                    }, getAxisFromToDescription: function (b) { var a = this.chart, c = a.options.accessibility.screenReaderSection.axisRangeDateFormat, d = function (d) { return b.dateTime ? a.time.dateFormat(c, b[d]) : b[d] }; return a.langFormat("accessibility.axis.rangeFromTo", { chart: a, axis: b, rangeFrom: d("min"), rangeTo: d("max") }) }, destroy: function () { var b; null === (b = this.announcer) || void 0 === b ? void 0 : b.destroy() }
                }); return f
            }); r(a, "modules/accessibility/components/ContainerComponent.js", [a["parts/Globals.js"], a["parts/Utilities.js"],
            a["modules/accessibility/utils/htmlUtilities.js"], a["modules/accessibility/utils/chartUtilities.js"], a["modules/accessibility/AccessibilityComponent.js"]], function (a, f, q, l, n) {
                var e = a.win.document; a = f.extend; var h = q.stripHTMLTagsFromString, p = l.unhideChartElementFromAT, k = l.getChartTitle; q = function () { }; q.prototype = new n; a(q.prototype, {
                    onChartUpdate: function () { this.handleSVGTitleElement(); this.setSVGContainerLabel(); this.setGraphicContainerAttrs(); this.setRenderToAttrs(); this.makeCreditsAccessible() },
                    handleSVGTitleElement: function () { var a = this.chart, c = "highcharts-title-" + a.index, b = h(a.langFormat("accessibility.svgContainerTitle", { chartTitle: k(a) })); if (b.length) { var g = this.svgTitleElement = this.svgTitleElement || e.createElementNS("http://www.w3.org/2000/svg", "title"); g.textContent = b; g.id = c; a.renderTo.insertBefore(g, a.renderTo.firstChild) } }, setSVGContainerLabel: function () {
                        var a = this.chart, c = h(a.langFormat("accessibility.svgContainerLabel", { chartTitle: k(a) })); a.renderer.box && c.length && a.renderer.box.setAttribute("aria-label",
                            c)
                    }, setGraphicContainerAttrs: function () { var a = this.chart, c = a.langFormat("accessibility.graphicContainerLabel", { chartTitle: k(a) }); c.length && a.container.setAttribute("aria-label", c) }, setRenderToAttrs: function () { var a = this.chart; "disabled" !== a.options.accessibility.landmarkVerbosity ? a.renderTo.setAttribute("role", "region") : a.renderTo.removeAttribute("role"); a.renderTo.setAttribute("aria-label", a.langFormat("accessibility.chartContainerLabel", { title: k(a), chart: a })) }, makeCreditsAccessible: function () {
                        var a =
                            this.chart, c = a.credits; c && (c.textStr && c.element.setAttribute("aria-label", h(a.langFormat("accessibility.credits", { creditsStr: c.textStr }))), p(a, c.element))
                    }, destroy: function () { this.chart.renderTo.setAttribute("aria-hidden", !0) }
                }); return q
            }); r(a, "modules/accessibility/high-contrast-mode.js", [a["parts/Globals.js"]], function (a) {
                var f = a.isMS, h = a.win, l = h.document; return {
                    isHighContrastModeActive: function () {
                        var a = /(Edg)/.test(h.navigator.userAgent); if (h.matchMedia && a) return h.matchMedia("(-ms-high-contrast: active)").matches;
                        if (f && h.getComputedStyle) { a = l.createElement("div"); a.style.backgroundImage = "url(#)"; l.body.appendChild(a); var e = (a.currentStyle || h.getComputedStyle(a)).backgroundImage; l.body.removeChild(a); return "none" === e } return !1
                    }, setHighContrastTheme: function (a) {
                        a.highContrastModeActive = !0; var e = a.options.accessibility.highContrastTheme; a.update(e, !1); a.series.forEach(function (a) {
                            var f = e.plotOptions[a.type] || {}; a.update({ color: f.color || "windowText", colors: [f.color || "windowText"], borderColor: f.borderColor || "window" });
                            a.points.forEach(function (a) { a.options && a.options.color && a.update({ color: f.color || "windowText", borderColor: f.borderColor || "window" }, !1) })
                        }); a.redraw()
                    }
                }
            }); r(a, "modules/accessibility/high-contrast-theme.js", [], function () {
                return {
                    chart: { backgroundColor: "window" }, title: { style: { color: "windowText" } }, subtitle: { style: { color: "windowText" } }, colorAxis: { minColor: "windowText", maxColor: "windowText", stops: [] }, colors: ["windowText"], xAxis: {
                        gridLineColor: "windowText", labels: { style: { color: "windowText" } }, lineColor: "windowText",
                        minorGridLineColor: "windowText", tickColor: "windowText", title: { style: { color: "windowText" } }
                    }, yAxis: { gridLineColor: "windowText", labels: { style: { color: "windowText" } }, lineColor: "windowText", minorGridLineColor: "windowText", tickColor: "windowText", title: { style: { color: "windowText" } } }, tooltip: { backgroundColor: "window", borderColor: "windowText", style: { color: "windowText" } }, plotOptions: {
                        series: {
                            lineColor: "windowText", fillColor: "window", borderColor: "windowText", edgeColor: "windowText", borderWidth: 1, dataLabels: {
                                connectorColor: "windowText",
                                color: "windowText", style: { color: "windowText", textOutline: "none" }
                            }, marker: { lineColor: "windowText", fillColor: "windowText" }
                        }, pie: { color: "window", colors: ["window"], borderColor: "windowText", borderWidth: 1 }, boxplot: { fillColor: "window" }, candlestick: { lineColor: "windowText", fillColor: "window" }, errorbar: { fillColor: "window" }
                    }, legend: { backgroundColor: "window", itemStyle: { color: "windowText" }, itemHoverStyle: { color: "windowText" }, itemHiddenStyle: { color: "#555" }, title: { style: { color: "windowText" } } }, credits: { style: { color: "windowText" } },
                    labels: { style: { color: "windowText" } }, drilldown: { activeAxisLabelStyle: { color: "windowText" }, activeDataLabelStyle: { color: "windowText" } }, navigation: { buttonOptions: { symbolStroke: "windowText", theme: { fill: "window" } } }, rangeSelector: {
                        buttonTheme: { fill: "window", stroke: "windowText", style: { color: "windowText" }, states: { hover: { fill: "window", stroke: "windowText", style: { color: "windowText" } }, select: { fill: "#444", stroke: "windowText", style: { color: "windowText" } } } }, inputBoxBorderColor: "windowText", inputStyle: {
                            backgroundColor: "window",
                            color: "windowText"
                        }, labelStyle: { color: "windowText" }
                    }, navigator: { handles: { backgroundColor: "window", borderColor: "windowText" }, outlineColor: "windowText", maskFill: "transparent", series: { color: "windowText", lineColor: "windowText" }, xAxis: { gridLineColor: "windowText" } }, scrollbar: { barBackgroundColor: "#444", barBorderColor: "windowText", buttonArrowColor: "windowText", buttonBackgroundColor: "window", buttonBorderColor: "windowText", rifleColor: "windowText", trackBackgroundColor: "window", trackBorderColor: "windowText" }
                }
            });
    r(a, "modules/accessibility/options/options.js", [], function () {
        return {
            accessibility: {
                enabled: !0, screenReaderSection: { beforeChartFormat: "<h5>{chartTitle}</h5><div>{typeDescription}</div><div>{chartSubtitle}</div><div>{chartLongdesc}</div><div>{playAsSoundButton}</div><div>{viewTableButton}</div><div>{xAxisDescription}</div><div>{yAxisDescription}</div><div>{annotationsTitle}{annotationsList}</div>", afterChartFormat: "{endOfChartMarker}", axisRangeDateFormat: "%Y-%m-%d %H:%M:%S" }, series: {
                    describeSingleSeries: !1,
                    pointDescriptionEnabledThreshold: 200
                }, point: { valueDescriptionFormat: "{index}. {xDescription}{separator}{value}." }, landmarkVerbosity: "all", linkedDescription: '*[data-highcharts-chart="{index}"] + .highcharts-description', keyboardNavigation: { enabled: !0, focusBorder: { enabled: !0, hideBrowserFocusOutline: !0, style: { color: "#335cad", lineWidth: 2, borderRadius: 3 }, margin: 2 }, order: ["series", "zoom", "rangeSelector", "legend", "chartMenu"], wrapAround: !0, seriesNavigation: { skipNullPoints: !0, pointNavigationEnabledThreshold: !1 } },
                announceNewData: { enabled: !1, minAnnounceInterval: 5E3, interruptUser: !1 }
            }, legend: { accessibility: { enabled: !0, keyboardNavigation: { enabled: !0 } } }, exporting: { accessibility: { enabled: !0 } }
        }
    }); r(a, "modules/accessibility/options/langOptions.js", [], function () {
        return {
            accessibility: {
                defaultChartTitle: "Chart", chartContainerLabel: "{title}. Highcharts interactive chart.", svgContainerLabel: "Interactive chart", drillUpButton: "{buttonText}", credits: "Chart credits: {creditsStr}", thousandsSep: ",", svgContainerTitle: "", graphicContainerLabel: "",
                screenReaderSection: { beforeRegionLabel: "Chart screen reader information.", afterRegionLabel: "", annotations: { heading: "Chart annotations summary", descriptionSinglePoint: "{annotationText}. Related to {annotationPoint}", descriptionMultiplePoints: "{annotationText}. Related to {annotationPoint}{ Also related to, #each(additionalAnnotationPoints)}", descriptionNoPoints: "{annotationText}" }, endOfChartMarker: "End of interactive chart." }, sonification: { playAsSoundButtonText: "Play as sound, {chartTitle}", playAsSoundClickAnnouncement: "Play" },
                legend: { legendLabel: "Toggle series visibility", legendItem: "Toggle visibility of {itemName}" }, zoom: { mapZoomIn: "Zoom chart", mapZoomOut: "Zoom out chart", resetZoomButton: "Reset zoom" }, rangeSelector: { minInputLabel: "Select start date.", maxInputLabel: "Select end date.", buttonText: "Select range {buttonText}" }, table: { viewAsDataTableButtonText: "View as data table, {chartTitle}", tableSummary: "Table representation of chart." }, announceNewData: {
                    newDataAnnounce: "Updated data for chart {chartTitle}", newSeriesAnnounceSingle: "New data series: {seriesDesc}",
                    newPointAnnounceSingle: "New data point: {pointDesc}", newSeriesAnnounceMultiple: "New data series in chart {chartTitle}: {seriesDesc}", newPointAnnounceMultiple: "New data point in chart {chartTitle}: {pointDesc}"
                }, seriesTypeDescriptions: {
                    boxplot: "Box plot charts are typically used to display groups of statistical data. Each data point in the chart can have up to 5 values: minimum, lower quartile, median, upper quartile, and maximum.", arearange: "Arearange charts are line charts displaying a range between a lower and higher value for each point.",
                    areasplinerange: "These charts are line charts displaying a range between a lower and higher value for each point.", bubble: "Bubble charts are scatter charts where each data point also has a size value.", columnrange: "Columnrange charts are column charts displaying a range between a lower and higher value for each point.", errorbar: "Errorbar series are used to display the variability of the data.", funnel: "Funnel charts are used to display reduction of data in stages.", pyramid: "Pyramid charts consist of a single pyramid with item heights corresponding to each point value.",
                    waterfall: "A waterfall chart is a column chart where each column contributes towards a total end value."
                }, chartTypes: {
                    emptyChart: "Empty chart", mapTypeDescription: "Map of {mapTitle} with {numSeries} data series.", unknownMap: "Map of unspecified region with {numSeries} data series.", combinationChart: "Combination chart with {numSeries} data series.", defaultSingle: "Chart with {numPoints} data {#plural(numPoints, points, point)}.", defaultMultiple: "Chart with {numSeries} data series.", splineSingle: "Line chart with {numPoints} data {#plural(numPoints, points, point)}.",
                    splineMultiple: "Line chart with {numSeries} lines.", lineSingle: "Line chart with {numPoints} data {#plural(numPoints, points, point)}.", lineMultiple: "Line chart with {numSeries} lines.", columnSingle: "Bar chart with {numPoints} {#plural(numPoints, bars, bar)}.", columnMultiple: "Bar chart with {numSeries} data series.", barSingle: "Bar chart with {numPoints} {#plural(numPoints, bars, bar)}.", barMultiple: "Bar chart with {numSeries} data series.", pieSingle: "Pie chart with {numPoints} {#plural(numPoints, slices, slice)}.",
                    pieMultiple: "Pie chart with {numSeries} pies.", scatterSingle: "Scatter chart with {numPoints} {#plural(numPoints, points, point)}.", scatterMultiple: "Scatter chart with {numSeries} data series.", boxplotSingle: "Boxplot with {numPoints} {#plural(numPoints, boxes, box)}.", boxplotMultiple: "Boxplot with {numSeries} data series.", bubbleSingle: "Bubble chart with {numPoints} {#plural(numPoints, bubbles, bubble)}.", bubbleMultiple: "Bubble chart with {numSeries} data series."
                }, axis: {
                    xAxisDescriptionSingular: "The chart has 1 X axis displaying {names[0]}. {ranges[0]}",
                    xAxisDescriptionPlural: "The chart has {numAxes} X axes displaying {#each(names, -1) }and {names[-1]}.", yAxisDescriptionSingular: "The chart has 1 Y axis displaying {names[0]}. {ranges[0]}", yAxisDescriptionPlural: "The chart has {numAxes} Y axes displaying {#each(names, -1) }and {names[-1]}.", timeRangeDays: "Range: {range} days.", timeRangeHours: "Range: {range} hours.", timeRangeMinutes: "Range: {range} minutes.", timeRangeSeconds: "Range: {range} seconds.", rangeFromTo: "Range: {rangeFrom} to {rangeTo}.", rangeCategories: "Range: {numCategories} categories."
                },
                exporting: { chartMenuLabel: "Chart menu", menuButtonLabel: "View chart menu", exportRegionLabel: "Chart menu" }, series: {
                    summary: {
                        "default": "{name}, series {ix} of {numSeries} with {numPoints} data {#plural(numPoints, points, point)}.", defaultCombination: "{name}, series {ix} of {numSeries} with {numPoints} data {#plural(numPoints, points, point)}.", line: "{name}, line {ix} of {numSeries} with {numPoints} data {#plural(numPoints, points, point)}.", lineCombination: "{name}, series {ix} of {numSeries}. Line with {numPoints} data {#plural(numPoints, points, point)}.",
                        spline: "{name}, line {ix} of {numSeries} with {numPoints} data {#plural(numPoints, points, point)}.", splineCombination: "{name}, series {ix} of {numSeries}. Line with {numPoints} data {#plural(numPoints, points, point)}.", column: "{name}, bar series {ix} of {numSeries} with {numPoints} {#plural(numPoints, bars, bar)}.", columnCombination: "{name}, series {ix} of {numSeries}. Bar series with {numPoints} {#plural(numPoints, bars, bar)}.", bar: "{name}, bar series {ix} of {numSeries} with {numPoints} {#plural(numPoints, bars, bar)}.",
                        barCombination: "{name}, series {ix} of {numSeries}. Bar series with {numPoints} {#plural(numPoints, bars, bar)}.", pie: "{name}, pie {ix} of {numSeries} with {numPoints} {#plural(numPoints, slices, slice)}.", pieCombination: "{name}, series {ix} of {numSeries}. Pie with {numPoints} {#plural(numPoints, slices, slice)}.", scatter: "{name}, scatter plot {ix} of {numSeries} with {numPoints} {#plural(numPoints, points, point)}.", scatterCombination: "{name}, series {ix} of {numSeries}, scatter plot with {numPoints} {#plural(numPoints, points, point)}.",
                        boxplot: "{name}, boxplot {ix} of {numSeries} with {numPoints} {#plural(numPoints, boxes, box)}.", boxplotCombination: "{name}, series {ix} of {numSeries}. Boxplot with {numPoints} {#plural(numPoints, boxes, box)}.", bubble: "{name}, bubble series {ix} of {numSeries} with {numPoints} {#plural(numPoints, bubbles, bubble)}.", bubbleCombination: "{name}, series {ix} of {numSeries}. Bubble series with {numPoints} {#plural(numPoints, bubbles, bubble)}.", map: "{name}, map {ix} of {numSeries} with {numPoints} {#plural(numPoints, areas, area)}.",
                        mapCombination: "{name}, series {ix} of {numSeries}. Map with {numPoints} {#plural(numPoints, areas, area)}.", mapline: "{name}, line {ix} of {numSeries} with {numPoints} data {#plural(numPoints, points, point)}.", maplineCombination: "{name}, series {ix} of {numSeries}. Line with {numPoints} data {#plural(numPoints, points, point)}.", mapbubble: "{name}, bubble series {ix} of {numSeries} with {numPoints} {#plural(numPoints, bubbles, bubble)}.", mapbubbleCombination: "{name}, series {ix} of {numSeries}. Bubble series with {numPoints} {#plural(numPoints, bubbles, bubble)}."
                    },
                    description: "{description}", xAxisDescription: "X axis, {name}", yAxisDescription: "Y axis, {name}", nullPointValue: "No value", pointAnnotationsDescription: "{Annotation: #each(annotations). }"
                }
            }
        }
    }); r(a, "modules/accessibility/options/deprecatedOptions.js", [a["parts/Utilities.js"]], function (a) {
        function f(a, c, b) { p("Highcharts: Deprecated option " + c + " used. This will be removed from future versions of Highcharts. Use " + b + " instead.", !1, a) } function h(a, c, b) {
            for (var d, e = 0; e < c.length - 1; ++e)d = c[e], a = a[d] = k(a[d],
                {}); a[c[c.length - 1]] = b
        } function l(a, c, b, g) { function d(b, a) { return a.reduce(function (b, a) { return b[a] }, b) } var e = d(a.options, c), p = d(a.options, b); Object.keys(g).forEach(function (d) { var m = e[d]; "undefined" !== typeof m && (h(p, g[d], m), f(a, c.join(".") + "." + d, b.join(".") + "." + g[d].join("."))) }) } function n(a) { var c = a.options.chart || {}, b = a.options.accessibility || {};["description", "typeDescription"].forEach(function (d) { c[d] && (b[d] = c[d], f(a, "chart." + d, "accessibility." + d)) }) } function e(a) {
            a.axes.forEach(function (c) {
                (c =
                    c.options) && c.description && (c.accessibility = c.accessibility || {}, c.accessibility.description = c.description, f(a, "axis.description", "axis.accessibility.description"))
            })
        } function t(a) {
            var c = { description: ["accessibility", "description"], exposeElementToA11y: ["accessibility", "exposeAsGroupOnly"], pointDescriptionFormatter: ["accessibility", "pointDescriptionFormatter"], skipKeyboardNavigation: ["accessibility", "keyboardNavigation", "enabled"] }; a.series.forEach(function (b) {
                Object.keys(c).forEach(function (d) {
                    var e =
                        b.options[d]; "undefined" !== typeof e && (h(b.options, c[d], "skipKeyboardNavigation" === d ? !e : e), f(a, "series." + d, "series." + c[d].join(".")))
                })
            })
        } var p = a.error, k = a.pick; return function (a) {
            n(a); e(a); a.series && t(a); l(a, ["accessibility"], ["accessibility"], {
                pointDateFormat: ["point", "dateFormat"], pointDateFormatter: ["point", "dateFormatter"], pointDescriptionFormatter: ["point", "descriptionFormatter"], pointDescriptionThreshold: ["series", "pointDescriptionEnabledThreshold"], pointNavigationThreshold: ["keyboardNavigation",
                    "seriesNavigation", "pointNavigationEnabledThreshold"], pointValueDecimals: ["point", "valueDecimals"], pointValuebacker: ["point", "valuebacker"], pointValueSuffix: ["point", "valueSuffix"], screenReaderSectionFormatter: ["screenReaderSection", "beforeChartFormatter"], describeSingleSeries: ["series", "describeSingleSeries"], seriesDescriptionFormatter: ["series", "descriptionFormatter"], onTableAnchorClick: ["screenReaderSection", "onViewDataTableClick"], axisRangeDateFormat: ["screenReaderSection", "axisRangeDateFormat"]
            });
            l(a, ["accessibility", "keyboardNavigation"], ["accessibility", "keyboardNavigation", "seriesNavigation"], { skipNullPoints: ["skipNullPoints"], mode: ["mode"] }); l(a, ["lang", "accessibility"], ["lang", "accessibility"], {
                legendItem: ["legend", "legendItem"], legendLabel: ["legend", "legendLabel"], mapZoomIn: ["zoom", "mapZoomIn"], mapZoomOut: ["zoom", "mapZoomOut"], resetZoomButton: ["zoom", "resetZoomButton"], screenReaderRegionLabel: ["screenReaderSection", "beforeRegionLabel"], rangeSelectorButton: ["rangeSelector", "buttonText"],
                rangeSelectorMaxInput: ["rangeSelector", "maxInputLabel"], rangeSelectorMinInput: ["rangeSelector", "minInputLabel"], svgContainerEnd: ["screenReaderSection", "endOfChartMarker"], viewAsDataTable: ["table", "viewAsDataTableButtonText"], tableSummary: ["table", "tableSummary"]
            })
        }
    }); r(a, "modules/accessibility/a11y-i18n.js", [a["parts/Globals.js"], a["parts/Utilities.js"]], function (a, f) {
        function h(a, f) {
            var e = a.indexOf("#each("), h = a.indexOf("#plural("), d = a.indexOf("["), c = a.indexOf("]"); if (-1 < e) {
                d = a.slice(e).indexOf(")") +
                e; var b = a.substring(0, e); h = a.substring(d + 1); d = a.substring(e + 6, d).split(","); e = Number(d[1]); a = ""; if (f = f[d[0]]) for (e = isNaN(e) ? f.length : e, e = 0 > e ? f.length + e : Math.min(e, f.length), d = 0; d < e; ++d)a += b + f[d] + h; return a.length ? a : ""
            } if (-1 < h) { b = a.slice(h).indexOf(")") + h; a = a.substring(h + 8, b).split(","); switch (Number(f[a[0]])) { case 0: a = n(a[4], a[1]); break; case 1: a = n(a[2], a[1]); break; case 2: a = n(a[3], a[1]); break; default: a = a[1] }a ? (f = a, f = f.trim && f.trim() || f.replace(/^\s+|\s+$/g, "")) : f = ""; return f } return -1 < d ? (h = a.substring(0,
                d), a = Number(a.substring(d + 1, c)), f = f[h], !isNaN(a) && f && (0 > a ? (b = f[f.length + a], "undefined" === typeof b && (b = f[0])) : (b = f[a], "undefined" === typeof b && (b = f[f.length - 1]))), "undefined" !== typeof b ? b : "") : "{" + a + "}"
        } var l = f.format, n = f.pick; a.i18nFormat = function (a, f, p) {
            var e = function (a, b) { a = a.slice(b || 0); var c = a.indexOf("{"), d = a.indexOf("}"); if (-1 < c && d > c) return { statement: a.substring(c + 1, d), begin: b + c + 1, end: b + d } }, d = [], c = 0; do {
                var b = e(a, c); var g = a.substring(c, b && b.begin - 1); g.length && d.push({ value: g, type: "constant" });
                b && d.push({ value: b.statement, type: "statement" }); c = b ? b.end + 1 : c + 1
            } while (b); d.forEach(function (a) { "statement" === a.type && (a.value = h(a.value, f)) }); return l(d.reduce(function (a, b) { return a + b.value }, ""), f, p)
        }; a.Chart.prototype.langFormat = function (e, f) { e = e.split("."); for (var h = this.options.lang, k = 0; k < e.length; ++k)h = h && h[e[k]]; return "string" === typeof h ? a.i18nFormat(h, f, this) : "" }
    }); r(a, "modules/accessibility/focusBorder.js", [a["parts/Globals.js"], a["parts/Utilities.js"]], function (a, f) {
        function h(a) {
            if (!a.focusBorderDestroyHook) {
                var c =
                    a.destroy; a.destroy = function () { var b, d; null === (d = null === (b = a.focusBorder) || void 0 === b ? void 0 : b.destroy) || void 0 === d ? void 0 : d.call(b); return c.apply(a, arguments) }; a.focusBorderDestroyHook = c
            }
        } function l(a) { for (var c = [], b = 1; b < arguments.length; b++)c[b - 1] = arguments[b]; a.focusBorderUpdateHooks || (a.focusBorderUpdateHooks = {}, k.forEach(function (b) { b += "Setter"; var d = a[b] || a._defaultSetter; a.focusBorderUpdateHooks[b] = d; a[b] = function () { var b = d.apply(a, arguments); a.addFocusBorder.apply(a, c); return b } })) } function n(a) {
            a.focusBorderUpdateHooks &&
            (Object.keys(a.focusBorderUpdateHooks).forEach(function (c) { var b = a.focusBorderUpdateHooks[c]; b === a._defaultSetter ? delete a[c] : a[c] = b }), delete a.focusBorderUpdateHooks)
        } var e = f.addEvent, r = f.extend, p = f.pick, k = "x y transform width height r d stroke-width".split(" "); r(a.SVGElement.prototype, {
            addFocusBorder: function (d, c) {
                this.focusBorder && this.removeFocusBorder(); var b = this.getBBox(), e = p(d, 3); b.x += this.translateX ? this.translateX : 0; b.y += this.translateY ? this.translateY : 0; var f = b.x - e, k = b.y - e, n = b.width + 2 *
                    e, q = b.height + 2 * e; if ("text" === this.element.nodeName || this.isLabel) { var r = !!this.rotation; if (this.isLabel) var t = { x: r ? 1 : 0, y: 0 }; else f = t = 0, "middle" === this.attr("text-anchor") ? (t = a.isFirefox && this.rotation ? .25 : .5, f = a.isFirefox && !this.rotation ? .75 : .5) : this.rotation ? t = .25 : f = .75, t = { x: t, y: f }; f = +this.attr("x") - b.width * t.x - e; k = +this.attr("y") - b.height * t.y - e; this.isLabel && r && (r = n, n = q, q = r, f = +this.attr("x") - b.height * t.x - e, k = +this.attr("y") - b.width * t.y - e) } this.focusBorder = this.renderer.rect(f, k, n, q, parseInt((c &&
                        c.borderRadius || 0).toString(), 10)).addClass("highcharts-focus-border").attr({ zIndex: 99 }).add(this.parentGroup); this.renderer.styledMode || this.focusBorder.attr({ stroke: c && c.stroke, "stroke-width": c && c.strokeWidth }); l(this, d, c); h(this)
            }, removeFocusBorder: function () { n(this); this.focusBorderDestroyHook && (this.destroy = this.focusBorderDestroyHook, delete this.focusBorderDestroyHook); this.focusBorder && (this.focusBorder.destroy(), delete this.focusBorder) }
        }); a.Chart.prototype.renderFocusBorder = function () {
            var a =
                this.focusElement, c = this.options.accessibility.keyboardNavigation.focusBorder; a && (a.removeFocusBorder(), c.enabled && a.addFocusBorder(c.margin, { stroke: c.style.color, strokeWidth: c.style.lineWidth, borderRadius: c.style.borderRadius }))
        }; a.Chart.prototype.setFocusToElement = function (a, c) {
            var b = this.options.accessibility.keyboardNavigation.focusBorder; (c = c || a.element) && c.focus && (c.hcEvents && c.hcEvents.focusin || e(c, "focusin", function () { }), c.focus(), b.hideBrowserFocusOutline && (c.style.outline = "none")); this.focusElement &&
                this.focusElement.removeFocusBorder(); this.focusElement = a; this.renderFocusBorder()
        }
    }); r(a, "modules/accessibility/accessibility.js", [a["modules/accessibility/utils/chartUtilities.js"], a["parts/Globals.js"], a["modules/accessibility/KeyboardNavigationHandler.js"], a["parts/Point.js"], a["parts/Utilities.js"], a["modules/accessibility/AccessibilityComponent.js"], a["modules/accessibility/KeyboardNavigation.js"], a["modules/accessibility/components/LegendComponent.js"], a["modules/accessibility/components/MenuComponent.js"],
    a["modules/accessibility/components/SeriesComponent/SeriesComponent.js"], a["modules/accessibility/components/ZoomComponent.js"], a["modules/accessibility/components/RangeSelectorComponent.js"], a["modules/accessibility/components/InfoRegionsComponent.js"], a["modules/accessibility/components/ContainerComponent.js"], a["modules/accessibility/high-contrast-mode.js"], a["modules/accessibility/high-contrast-theme.js"], a["modules/accessibility/options/options.js"], a["modules/accessibility/options/langOptions.js"],
    a["modules/accessibility/options/deprecatedOptions.js"]], function (a, f, q, l, n, e, r, p, k, d, c, b, g, m, v, A, B, u, z) {
        function h(a) { this.init(a) } var t = n.addEvent, x = n.extend, w = n.fireEvent, D = n.merge, y = f.win.document; D(!0, f.defaultOptions, B, { accessibility: { highContrastTheme: A }, lang: u }); f.A11yChartUtilities = a; f.KeyboardNavigationHandler = q; f.AccessibilityComponent = e; h.prototype = {
            init: function (a) {
                this.chart = a; y.addEventListener && a.renderer.isSVG ? (z(a), this.initComponents(), this.keyboardNavigation = new r(a, this.components),
                    this.update()) : a.renderTo.setAttribute("aria-hidden", !0)
            }, initComponents: function () { var a = this.chart, e = a.options.accessibility; this.components = { container: new m, infoRegions: new g, legend: new p, chartMenu: new k, rangeSelector: new b, series: new d, zoom: new c }; e.customComponents && x(this.components, e.customComponents); var f = this.components; this.getComponentOrder().forEach(function (b) { f[b].initBase(a); f[b].init() }) }, getComponentOrder: function () {
                if (!this.components) return []; if (!this.components.series) return Object.keys(this.components);
                var a = Object.keys(this.components).filter(function (a) { return "series" !== a }); return ["series"].concat(a)
            }, update: function () {
                var a = this.components, b = this.chart, c = b.options.accessibility; w(b, "beforeA11yUpdate"); b.types = this.getChartTypes(); this.getComponentOrder().forEach(function (c) { a[c].onChartUpdate(); w(b, "afterA11yComponentUpdate", { name: c, component: a[c] }) }); this.keyboardNavigation.update(c.keyboardNavigation.order); !b.highContrastModeActive && v.isHighContrastModeActive() && v.setHighContrastTheme(b);
                w(b, "afterA11yUpdate", { accessibility: this })
            }, destroy: function () { var a = this.chart || {}, b = this.components; Object.keys(b).forEach(function (a) { b[a].destroy(); b[a].destroyBase() }); this.keyboardNavigation && this.keyboardNavigation.destroy(); a.renderTo && a.renderTo.setAttribute("aria-hidden", !0); a.focusElement && a.focusElement.removeFocusBorder() }, getChartTypes: function () { var a = {}; this.chart.series.forEach(function (b) { a[b.type] = 1 }); return Object.keys(a) }
        }; f.Chart.prototype.updateA11yEnabled = function () {
            var a =
                this.accessibility, b = this.options.accessibility; b && b.enabled ? a ? a.update() : this.accessibility = new h(this) : a ? (a.destroy && a.destroy(), delete this.accessibility) : this.renderTo.setAttribute("aria-hidden", !0)
        }; t(f.Chart, "render", function (a) { this.a11yDirty && this.renderTo && (delete this.a11yDirty, this.updateA11yEnabled()); var b = this.accessibility; b && b.getComponentOrder().forEach(function (a) { b.components[a].onChartRender() }) }); t(f.Chart, "update", function (a) {
            if (a = a.options.accessibility) a.customComponents &&
                (this.options.accessibility.customComponents = a.customComponents, delete a.customComponents), D(!0, this.options.accessibility, a), this.accessibility && this.accessibility.destroy && (this.accessibility.destroy(), delete this.accessibility); this.a11yDirty = !0
        }); t(l, "update", function () { this.series.chart.accessibility && (this.series.chart.a11yDirty = !0) });["addSeries", "init"].forEach(function (a) { t(f.Chart, a, function () { this.a11yDirty = !0 }) });["update", "updatedData", "remove"].forEach(function (a) {
            t(f.Series, a, function () {
                this.chart.accessibility &&
                (this.chart.a11yDirty = !0)
            })
        });["afterDrilldown", "drillupall"].forEach(function (a) { t(f.Chart, a, function () { this.accessibility && this.accessibility.update() }) }); t(f.Chart, "destroy", function () { this.accessibility && this.accessibility.destroy() })
    }); r(a, "masters/modules/accessibility.src.js", [], function () { })
});
//# sourceMappingURL=accessibility.js.map