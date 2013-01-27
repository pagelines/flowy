(function (c) {
	var b = {};
	var a = {
		init: function (f) {
			var g = {
				minfactor: 20,
				distribution: 1.5,
				scalethreshold: 0,
				staticbelowthreshold: false,
				titleclass: "itemTitle",
				selectedclass: "selectedItem",
				scrollactive: true,
				step: {
					limit: 4,
					width: 8,
					scale: true
				},
				bendamount: 2,
				movecallback: function () {},
				clicked: false
			};
			b = g;
			var h = false;
			if (f) {
				c.extend(g, f)
			}
			return this.each(function () {
				var k = c(this);
				if (g.items) {
					var m = k.find(g.items)
				} else {
					var m = k.find("img")
				}
				if (m.length <= g.scalethreshold) {
					g.minfactor = 0;
					g.distribution = 0.95
				}
				k.css({
					position: "relative"
				});
				m.css({
					position: "absolute",
					"-webkit-transition": "all 0.5s ease-in-out",
					"-moz-transition": "all 0.5s ease-in-out",
					"-o-transition": "all 0.5s ease-in-out",
					"-ms-transition": "all 0.5s ease-in-out",
					transition: "all 0.5s ease-in-out"
				});
				var l = Math.ceil(m.length / 2 - 1);
				d(k, m, l);
				if (m.length <= g.scalethreshold && g.staticbelowthreshold) {
					m.each(function (n) {
						c(this).unbind("click.coverscroll");
						c(this).bind("click.coverscroll", function () {
							if (c(this).hasClass(g.selectedclass)) {
								return false
							}
							e(k, this)
						})
					})
				} else {
					m.each(function (n) {
						c(this).unbind("click.coverscroll");
						c(this).bind("click.coverscroll", function () {
							if (c(this).hasClass(g.selectedclass)) {
								return false
							}
							d(k, m, n)
						})
					})
				}
				if (!g.scrollactive) {
					return true
				}
				var j = function (n) {
					var o = n || window.event,
						p = 0;
					o = c.event.fix(o);
					if (!h) {
						if (o.wheelDelta) {
							p = o.wheelDelta / 120
						}
						if (o.detail) {
							p = -o.detail / 3
						}
						if (p > 0) {
							k.find("." + g.selectedclass + ":eq(0)").next().trigger("click")
						} else {
							k.find("." + g.selectedclass + ":eq(0)").prev().trigger("click")
						}
					}
					if (o.preventDefault) {
						o.preventDefault()
					}
					o.returnValue = false
				};
				if (k.get(0).addEventListener && !k.get(0).onmousewheel) {
					k.get(0).removeEventListener("DOMMouseScroll", j, false);
					k.get(0).addEventListener("DOMMouseScroll", j, false)
				}
				k.get(0).onmousewheel = j
			});

			function d(k, o, v) {
				var w = c(o.get(v));
				var s = (k.height() > 250) ? 250 : k.height();
				var p = {
					width: s,
					height: s,
					top: 0,
					left: Math.round(k.width() / 2 - s / 2)
				};
				if (c.browser.msie) {
					h = true;
					w.animate(p, 500, function () {
						h = false
					})
				} else {
					w.css(p)
				}
				w.fadeIn(80);
				var u = g.minfactor === 0 || g.minfactor > 0 ? g.minfactor : 15;
				var j = g.distribution ? g.distribution : 2;
				var m = g.titleclass ? g.titleclass : "itemTitle";
				if (!g.bendamount) {
					g.bendamount = 2
				}
				e(k, w, true);
				var l = s,
					t = 0;
				sf = false;
				var q = true;
				var r = Math.round(k.width() / 2 - s / 2);
				for (i = v - 1; i >= 0; i--) {
					var n = c(o.get(i));
					l = l - u;
					if (!sf) {
						r = Math.round(r - l / j + u)
					} else {
						l = g.step.scale ? l : l + u;
						r = Math.round(r - g.step.width);
						t++
					}
					if (r >= 0 && q && t <= g.step.limit) {
						n.show()
					} else {
						if (!sf) {
							r = Math.round(r + (l / j) - u - g.step.width);
							sf = true;
							t++;
							n.show()
						} else {
							n.hide();
							q = false
						}
					}
					var p = {
						width: l,
						height: l,
						top: Math.round(k.height() / g.bendamount - l / g.bendamount),
						left: r
					};
					if (c.browser.msie) {
						h = true;
						n.animate(p, 500, function () {
							h = false
						})
					} else {
						n.css(p)
					}
				}
				var l = s,
					t = 0;
				sf = false;
				var r = Math.round(k.width() / 2 - s / 2);
				var q = true;
				for (i = v + 1; i < o.length; i++) {
					var n = c(o.get(i));
					l = l - u;
					if (!sf) {
						r = Math.round(r + l / j)
					} else {
						l = g.step.scale ? l : l + u;
						r = Math.round(r + g.step.width + (g.step.scale ? u : 0));
						t++
					}
					if ((r + l) < k.width() && q && t <= g.step.limit) {
						n.show()
					} else {
						if (!sf) {
							sf = true;
							t++;
							r = Math.round((r - l / j) + g.step.width + u);
							n.show()
						} else {
							n.hide();
							q = false
						}
					}
					var p = {
						width: l,
						height: l,
						top: Math.round(k.height() / g.bendamount - l / g.bendamount),
						left: r
					};
					if (c.browser.msie) {
						h = true;
						n.animate(p, 500, function () {
							h = false
						})
					} else {
						n.css(p)
					}
				}
				setTimeout(function () {
					var x = 100;
					o.each(function (y) {
						if (y <= v) {
							x = x + y
						} else {
							x = x - y
						}
						c(this).css("z-index", x)
					})
				}, 100)
			}
			function e(l, m, j) {
				m = c(m);
				var o = (l.outerHeight() > 250) ? 250 : l.outerHeight();
				var n = false;
				if (n = m.attr("desc")) {
					l.find("." + g.titleclass).remove();
					if (j) {
						var k = Math.round(l.width() / 2 - o / 2)
					} else {
						var k = parseInt(m.css("left"))
					}
					c('<div style="position:absolute;text-align:center;top:' + o + "px;" + "width:100" + '%;" class="' + g.titleclass + '">' + n + "</div>").appendTo(l);
					setTimeout(function () {
						g.movecallback.call(this, m)
					}, 600)
				} else {
					if (n = m.find("." + g.titleclass + ":eq(0)")) {
						l.find("." + g.titleclass).hide();
						n.css({
							position: "absolute",
							width: (o * 2),
							"text-align": "center",
							top: o,
							left: Math.round(0 - (o / 2))
						});
						setTimeout(function () {
							n.show();
							g.movecallback.call(this, m)
						}, 500)
					}
				}
				if (g.items) {
					var p = l.find(g.items)
				} else {
					var p = l.find("img")
				}
				setTimeout(function () {
					p.removeClass(g.selectedclass);
					m.addClass(g.selectedclass)
				}, 100)
			}
		},
		next: function (d) {
			return this.each(function () {
				var e = c(this);
				e.find("." + b.selectedclass + ":eq(0)").next().trigger("click")
			})
		},
		prev: function () {
			return this.each(function () {
				var d = c(this);
				d.find("." + b.selectedclass + ":eq(0)").prev().trigger("click")
			})
		}
	};
	c.fn.coverscroll = function (d) {
		if (a[d]) {
			return a[d].apply(this, Array.prototype.slice.call(arguments, 1))
		} else {
			if (typeof d === "object" || !d) {
				return a.init.apply(this, arguments)
			} else {
				c.error("Method " + d + " does not exist on this plugin")
			}
		}
	}
})(jQuery);