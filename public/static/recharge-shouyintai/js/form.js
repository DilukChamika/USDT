/** layui-v2.2.3 MIT License By http://www.layui.com */
;layui.define("layer", function(e) {
    "use strict";
    var i = layui.$
      , t = layui.layer
      , a = layui.hint()
      , n = layui.device()
      , l = "form"
      , s = ".layui-form"
      , r = "layui-this"
      , u = "layui-hide"
      , o = "layui-disabled"
      , c = function() {
        this.config = {
            verify: {
                required: [/[\S]+/, "必填项不能为空"],
                phone: [/^1\d{10}$/, "请输入正确的手机号"],
                email: [/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/, "邮箱格式不正确"],
                url: [/(^#)|(^http(s*):\/\/[^\s]+\.[^\s]+)/, "链接格式不正确"],
                number: function(e) {
                    if (!e || isNaN(e))
                        return "只能填写数字"
                },
                date: [/^(\d{4})[-\/](\d{1}|0\d{1}|1[0-2])([-\/](\d{1}|0\d{1}|[1-2][0-9]|3[0-1]))*$/, "日期格式不正确"],
                identity: [/(^\d{15}$)|(^\d{17}(x|X|\d)$)/, "请输入正确的身份证号"]
            }
        }
    };
    c.prototype.set = function(e) {
        var t = this;
        return i.extend(!0, t.config, e),
        t
    }
    ,
    c.prototype.verify = function(e) {
        var t = this;
        return i.extend(!0, t.config.verify, e),
        t
    }
    ,
    c.prototype.on = function(e, i) {
        return layui.onevent.call(this, l, e, i)
    }
    ,
    c.prototype.render = function(e, t) {
        var n = this
          , c = i(s + function() {
            return t ? '[lay-filter="' + t + '"]' : ""
        }())
          , d = {
            select: function() {
                var e, t = "请选择", a = "layui-form-select", n = "layui-select-title", s = "layui-select-none", d = "", f = c.find("select"), y = function(t, l) {
                    i(t.target).parent().hasClass(n) && !l || (i("." + a).removeClass(a + "ed " + a + "up"),
                    e && d && e.val(d)),
                    e = null
                }, h = function(t, c, f) {
                    var h = i(this)
                      , p = t.find("." + n)
                      , m = p.find("input")
                      , k = t.find("dl")
                      , g = k.children("dd");
                    if (!c) {
                        var b = function() {
                            var e = t.offset().top + t.outerHeight() + 5 - v.scrollTop()
                              , i = k.outerHeight();
                            t.addClass(a + "ed"),
                            g.removeClass(u),
                            e + i > v.height() && e >= i && t.addClass(a + "up")
                        }
                          , x = function(e) {
                            t.removeClass(a + "ed " + a + "up"),
                            m.blur(),
                            e || C(m.val(), function(e) {
                                e && (d = k.find("." + r).html(),
                                m && m.val(d))
                            })
                        };
                        p.on("click", function(e) {
                            t.hasClass(a + "ed") ? x() : (y(e, !0),
                            b()),
                            k.find("." + s).remove()
                        }),
                        p.find(".layui-edge").on("click", function() {
                            m.focus()
                        }),
                        m.on("keyup", function(e) {
                            var i = e.keyCode;
                            9 === i && b()
                        }).on("keydown", function(e) {
                            var i = e.keyCode;
                            9 === i ? x() : 13 === i && e.preventDefault()
                        });
                        var C = function(e, t, a) {
                            var n = 0;
                            layui.each(g, function() {
                                var t = i(this)
                                  , l = t.text()
                                  , s = l.indexOf(e) === -1;
                                ("" === e || "blur" === a ? e !== l : s) && n++,
                                "keyup" === a && t[s ? "addClass" : "removeClass"](u)
                            });
                            var l = n === g.length;
                            return t(l),
                            l
                        }
                          , w = function(e) {
                            var i = this.value
                              , t = e.keyCode;
                            return 9 !== t && 13 !== t && 37 !== t && 38 !== t && 39 !== t && 40 !== t && (C(i, function(e) {
                                e ? k.find("." + s)[0] || k.append('<p class="' + s + '">无匹配项</p>') : k.find("." + s).remove()
                            }, "keyup"),
                            void ("" === i && k.find("." + s).remove()))
                        };
                        f && m.on("keyup", w).on("blur", function(i) {
                            e = m,
                            d = k.find("." + r).html(),
                            setTimeout(function() {
                                C(m.val(), function(e) {
                                    d || m.val("")
                                }, "blur")
                            }, 200)
                        }),
                        g.on("click", function() {
                            var e = i(this)
                              , a = e.attr("lay-value")
                              , n = h.attr("lay-filter");
                            return !e.hasClass(o) && (e.hasClass("layui-select-tips") ? m.val("") : (m.val(e.text()),
                            e.addClass(r)),
                            e.siblings().removeClass(r),
                            h.val(a).removeClass("layui-form-danger"),
                            layui.event.call(this, l, "select(" + n + ")", {
                                elem: h[0],
                                value: a,
                                othis: t
                            }),
                            x(!0),
                            !1)
                        }),
                        t.find("dl>dt").on("click", function(e) {
                            return !1
                        }),
                        i(document).off("click", y).on("click", y)
                    }
                };
                f.each(function(e, l) {
                    var s = i(this)
                      , u = s.next("." + a)
                      , c = this.disabled
                      , d = l.value
                      , f = i(l.options[l.selectedIndex])
                      , y = l.options[0];
                    if ("string" == typeof s.attr("lay-ignore"))
                        return s.show();
                    var v = "string" == typeof s.attr("lay-search")
                      , p = y ? y.value ? t : y.innerHTML || t : t
                      , m = i(['<div class="' + (v ? "" : "layui-unselect ") + a + (c ? " layui-select-disabled" : "") + '">', '<div class="' + n + '"><input type="text" placeholder="' + p + '" value="' + (d ? f.html() : "") + '" ' + (v ? "" : "readonly") + ' class="layui-input' + (v ? "" : " layui-unselect") + (c ? " " + o : "") + '">', '<i class="layui-edge"></i></div>', '<dl class="layui-anim layui-anim-upbit' + (s.find("optgroup")[0] ? " layui-select-group" : "") + '">' + function(e) {
                        var i = [];
                        return layui.each(e, function(e, a) {
                            0 !== e || a.value ? "optgroup" === a.tagName.toLowerCase() ? i.push("<dt>" + a.label + "</dt>") : i.push('<dd lay-value="' + a.value + '" class="' + (d === a.value ? r : "") + (a.disabled ? " " + o : "") + '">' + a.innerHTML + "</dd>") : i.push('<dd lay-value="" class="layui-select-tips">' + (a.innerHTML || t) + "</dd>")
                        }),
                        0 === i.length && i.push('<dd lay-value="" class="' + o + '">没有选项</dd>'),
                        i.join("")
                    }(s.find("*")) + "</dl>", "</div>"].join(""));
                    u[0] && u.remove(),
                    s.after(m),
                    h.call(this, m, c, v)
                })
            },
            checkbox: function() {
                var e = {
                    checkbox: ["layui-form-checkbox", "layui-form-checked", "checkbox"],
                    _switch: ["layui-form-switch", "layui-form-onswitch", "switch"]
                }
                  , t = c.find("input[type=checkbox]")
                  , a = function(e, t) {
                    var a = i(this);
                    e.on("click", function() {
                        var i = a.attr("lay-filter")
                          , n = (a.attr("lay-text") || "").split("|");
                        a[0].disabled || (a[0].checked ? (a[0].checked = !1,
                        e.removeClass(t[1]).find("em").text(n[1])) : (a[0].checked = !0,
                        e.addClass(t[1]).find("em").text(n[0])),
                        layui.event.call(a[0], l, t[2] + "(" + i + ")", {
                            elem: a[0],
                            value: a[0].value,
                            othis: e
                        }))
                    })
                };
                t.each(function(t, n) {
                    var l = i(this)
                      , s = l.attr("lay-skin")
                      , r = (l.attr("lay-text") || "").split("|")
                      , u = this.disabled;
                    "switch" === s && (s = "_" + s);
                    var c = e[s] || e.checkbox;
                    if ("string" == typeof l.attr("lay-ignore"))
                        return l.show();
                    var d = l.next("." + c[0])
                      , f = i(['<div class="layui-unselect ' + c[0] + (n.checked ? " " + c[1] : "") + (u ? " layui-checkbox-disbaled " + o : "") + '" lay-skin="' + (s || "") + '">', {
                        _switch: "<em>" + ((n.checked ? r[0] : r[1]) || "") + "</em><i></i>"
                    }[s] || (n.title.replace(/\s/g, "") ? "<span>" + n.title + "</span>" : "") + '<i class="layui-icon">' + (s ? "&#xe605;" : "&#xe618;") + "</i>", "</div>"].join(""));
                    d[0] && d.remove(),
                    l.after(f),
                    a.call(this, f, c)
                })
            },
            radio: function() {
                var e = "layui-form-radio"
                  , t = ["&#xe643;", "&#xe63f;"]
                  , a = c.find("input[type=radio]")
                  , n = function(a) {
                    var n = i(this)
                      , r = "layui-anim-scaleSpring";
                    a.on("click", function() {
                        var u = n[0].name
                          , o = n.parents(s)
                          , c = n.attr("lay-filter")
                          , d = o.find("input[name=" + u.replace(/(\.|#|\[|\])/g, "\\$1") + "]");
                        n[0].disabled || (layui.each(d, function() {
                            var a = i(this).next("." + e);
                            this.checked = !1,
                            a.removeClass(e + "ed"),
                            a.find(".layui-icon").removeClass(r).html(t[1])
                        }),
                        n[0].checked = !0,
                        a.addClass(e + "ed"),
                        a.find(".layui-icon").addClass(r).html(t[0]),
                        layui.event.call(n[0], l, "radio(" + c + ")", {
                            elem: n[0],
                            value: n[0].value,
                            othis: a
                        }))
                    })
                };
                a.each(function(a, l) {
                    var s = i(this)
                      , r = s.next("." + e)
                      , u = this.disabled;
                    if ("string" == typeof s.attr("lay-ignore"))
                        return s.show();
                    var c = i(['<div class="layui-unselect ' + e + (l.checked ? " " + e + "ed" : "") + (u ? " layui-radio-disbaled " + o : "") + '">', '<i class="layui-anim layui-icon">' + t[l.checked ? 0 : 1] + "</i>", "<span>" + (l.title || "未命名") + "</span>", "</div>"].join(""));
                    r[0] && r.remove(),
                    s.after(c),
                    n.call(this, c)
                })
            }
        };
        return e ? d[e] ? d[e]() : a.error("不支持的" + e + "表单渲染") : layui.each(d, function(e, i) {
            i()
        }),
        n
    }
    ;
    var d = function() {
        var e = i(this)
          , a = f.config.verify
          , r = null
          , u = "layui-form-danger"
          , o = {}
          , c = e.parents(s)
          , d = c.find("*[lay-verify]")
          , y = e.parents("form")[0]
          , v = c.find("input,select,textarea")
          , h = e.attr("lay-filter");
        return layui.each(d, function(e, l) {
            var s = i(this)
              , o = s.attr("lay-verify").split("|")
              , c = s.attr("lay-verType")
              , d = s.val();
            if (s.removeClass(u),
            layui.each(o, function(e, i) {
                var o, f = "", y = "function" == typeof a[i];
                if (a[i]) {
                    var o = y ? f = a[i](d, l) : !a[i][0].test(d);
                    if (f = f || a[i][1],
                    o)
                        return "tips" === c ? t.tips(f, s, {
                            tips: 1
                        }) : "alert" === c ? t.alert(f, {
                            title: "提示",
                            shadeClose: !0
                        }) : t.msg(f, {
                            icon: 5,
                            shift: 6
                        }),
                        n.android || n.ios || l.focus(),
                        s.addClass(u),
                        r = !0
                }
            }),
            r)
                return r
        }),
        !r && (layui.each(v, function(e, i) {
            i.name && (/^checkbox|radio$/.test(i.type) && !i.checked || (o[i.name] = i.value))
        }),
        layui.event.call(this, l, "submit(" + h + ")", {
            elem: this,
            form: y,
            field: o
        }))
    }
      , f = new c
      , y = i(document)
      , v = i(window);
    f.render(),
    y.on("reset", s, function() {
        var e = i(this).attr("lay-filter");
        setTimeout(function() {
            f.render(null, e)
        }, 50)
    }),
    y.on("submit", s, d).on("click", "*[lay-submit]", d),
    e(l, f)
});
