/*!
 * Food Delivery v3.0
 * https://www.phpjabbers.com/food-delivery-script/
 * 
 * Copyright 2019, StivaSoft Ltd.
 * 
 */
(function (window, undefined){
	"use strict";
	pjQ.$.ajaxSetup({
		xhrFields: {
			withCredentials: true
		}
	});
	var document = window.document,
		myPickupMap = null,
		myPickupMarker = [],
		myPickupMarkers = [],
		myPickupOverlays = [],
		myPickupBounds = null,
		myPickupInfoWindow = false,
		myDeliveryMap = null,
		myDeliveryMarkers = [],
		myDeliveryOverlays = [],
		myDeliveryBounds = null,
		gmail_signin,
		ggl_user,
		gmail_btn,
		logout_btn,
		validate = (pjQ.$.fn.validate !== undefined),
		routes = [
		          	{pattern: /^#!\/loadMain$/, eventName: "loadMain"},
					{pattern: /^#!\/loadMainQr$/, eventName: "loadMainQr"},
		          	{pattern: /^#!\/loadHome$/, eventName: "loadHome"},
		          	{pattern: /^#!\/loadOptions$/, eventName: "loadOptions"},
		          	{pattern: /^#!\/loadTypes$/, eventName: "loadTypes"},
		          	{pattern: /^#!\/loadLogin$/, eventName: "loadLogin"},
		          	{pattern: /^#!\/loadProfile$/, eventName: "loadProfile"},
					{pattern: /^#!\/loadReview$/, eventName: "loadReview"},
					{pattern: /^#!\/loadRating$/, eventName: "loadRating"},
		          	{pattern: /^#!\/loadForgot$/, eventName: "loadForgot"},
		          	{pattern: /^#!\/loadVouchers$/, eventName: "loadVouchers"},
		          	{pattern: /^#!\/loadCheckout$/, eventName: "loadCheckout"},
					{pattern: /^#!\/loadOtpPage$/, eventName: "loadOtpPage"},
		          	{pattern: /^#!\/loadPreview$/, eventName: "loadPreview"},
					{pattern: /^#!\/loadMyOrders$/, eventName: "loadMyOrders"}
		          	//{pattern: /^#!\/loadCart$/, eventName: "loadCart"}
		         ],
		myTmp = {
			cnt: 0, 
			type: ""
		};
	
	function log() {
		if (window.console && window.console.log) {
			for (var x in arguments) {
				if (arguments.hasOwnProperty(x)) {
					window.console.log(arguments[x]);
				}
			}
		}
	}
	
	function assert() {
		if (window && window.console && window.console.assert) {
			window.console.assert.apply(window.console, arguments);
		}
	}
	
	function hashBang(value) {
		if (value !== undefined && value.match(/^#!\//) !== null) {
			if (window.location.hash == value) {
				return false;
			}
			window.location.hash = value;
			return true;
		}
		
		return false;
	}
	
	function onHashChange() {
		var i, iCnt, m;
		for (i = 0, iCnt = routes.length; i < iCnt; i++) {
			m = window.location.hash.match(routes[i].pattern);
			if (m !== null) {
				pjQ.$(window).trigger(routes[i].eventName, m.slice(1));
				break;
			}
		}
		if (m === null) {
			pjQ.$(window).trigger("loadMain");
		}
	}
	pjQ.$(window).on("hashchange", function (e) {
    	onHashChange.call(null);
    });
	
	function FoodDelivery(opts) {
		if (!(this instanceof FoodDelivery)) {
			return new FoodDelivery(opts);
		}
				
		this.reset.call(this);
		this.init.call(this, opts);
		
		return this;
	}
	
	FoodDelivery.inObject = function (val, obj) {
		var key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) {
				if (obj[key] == val) {
					return true;
				}
			}
		}
		return false;
	};
	
	FoodDelivery.size = function(obj) {
		var key,
			size = 0;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) {
				size += 1;
			}
		}
		return size;
	};
	
	FoodDelivery.prototype = {
		reset: function () {
			this.$container = null;			
			this.container = null;
			this.category_id = null; 
			this.opts = {};
			this.type = 'pickup';
			this.location_id = null;
			this.datesOff = [];
			this.datesOn = [];
			this.daysOff = [];
			this.deliveryDate = null;
			this.pickupDate = null;
			this.coord_id = null;
			this.myDeliveryOverlays = null;
			this.myDeliveryMap = null;
			this.cart = '';
			this.review = [];
			
			return this;
		},
		_daysOff: function (date) {
			var self = this,
				isDateOff = self._datesOff(date),
				isDateOn = self._datesOn(date);
			if (isDateOff[0] && !isDateOn[0]) {
				for (var i = 0, len = self.daysOff.length; i < len; i++) {
					if (self.daysOff[i] === date.getDay()) {
						return [false, 'bcal-past'];
					}
				}
				return [true, ''];
			} else {
				return isDateOff[0] ? isDateOff: isDateOn;
			}
		},
		_datesOff: function (date) {
			var d, i, len, dt,
				self = this;
			for (i = 0, len = self.datesOff.length; i < len; i++) {
				dt = self.datesOff[i].split("-");
				d = new Date(parseInt(dt[0], 10), parseInt(dt[1], 10) - 1, parseInt(dt[2], 10));
				if (d.getTime() === date.getTime()) {
					return [false, 'bcal-past'];
				}
			}
			return [true, ''];
		},
		_datesOn: function (date) {
			var d, i, len, dt,
				self = this;
			for (i = 0, len = self.datesOn.length; i < len; i++) {
				dt = self.datesOn[i].split("-");
				d = new Date(parseInt(dt[0], 10), parseInt(dt[1], 10) - 1, parseInt(dt[2], 10));
				if (d.getTime() === date.getTime()) {
					return [true, ''];
				}
			}
			return [false, 'bcal-past'];
		},
		setDays: function (location_id, type) {
			var self = this;
			self.daysOff = [];
			self.datesOff = [];
			self.datesOn = [];
			if (self.opts.daysOff[location_id] && self.opts.daysOff[location_id][type]) 
			{
				self.daysOff = self.opts.daysOff[location_id][type];
			}
			if (self.opts.datesOff[location_id] && self.opts.datesOff[location_id][type]) 
			{
				self.datesOff = self.opts.datesOff[location_id][type];
			}			
			if (self.opts.datesOn[location_id] && self.opts.datesOn[location_id][type]) 
			{
				self.datesOn = self.opts.datesOn[location_id][type];
			}
			return self;
		},
		disableButtons: function () {
			this.$container.find(".btn").each(function (i, el) {
				pjQ.$(el).addClass('fdButtonDisabled').attr("disabled");
			});
		},
		enableButtons: function () {
			this.$container.find(".btn").removeClass('fdButtonDisabled').removeAttr("disabled");
		},
		
		init: function (opts) {
			var self = this;
			this.opts = opts;
			pjQ.opts = opts;

			this.container = document.getElementById("fdContainer_" + self.opts.index);

			self.$container = pjQ.$(self.container);
			
			this.$container.on("click.fd", ".fdBtnHome", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if (!hashBang("#!/loadMain")) {
					pjQ.$(window).trigger("loadMain");
				}
				return false;
			}).on("click.fd", "#bottom-btn", function (e) {
				
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('html, body').animate({
			        scrollTop: pjQ.$('#pjFdCartWrapper_' + self.opts.index).offset().top - 270
			    }, 500);
				return false;
			}).on("click.fd", ".pjFdBtnBrand", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				//console.log("clicked");
				if (!hashBang("#!/loadHome")) {
					pjQ.$(window).trigger("loadHome");
				}
				return false;
			}).on("click.fd", ".pjFdBtnMenu", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var load = pjQ.$(this).attr('data-load');
				if (!hashBang("#!/" + load)) {
					pjQ.$(window).trigger(load);
				}
				return false;
			}).on("click.fd", ".fdBtnAccount", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
			
				if (!hashBang("#!/loadLogin")) {
					pjQ.$(window).trigger("loadLogin");
				}
				return false;
			}).on("click.fd", ".fdBtnOrderTotal", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(pjQ.$('#fdCart_' + self.opts.index).find('.fdEmptyCart').length == 0)
				{
					var logged = pjQ.$(this).attr('data-logged');
					// if (pjQ.$(window).width() <= 767) {
					// 	pjQ.$('.fdLoader').css('top', '-200px');
					// }
					pjQ.$('.fdLoader').css('display', 'block');
					if(logged == 'no')
					{
						if (!hashBang("#!/loadLogin")) {
							pjQ.$(window).trigger("loadLogin");
						}
					}else{
						if (!hashBang("#!/loadTypes")) {
							pjQ.$(window).trigger("loadTypes");
						}
					}
				}

				return false;
			}).on("click.fd", ".fdSelectorLocale", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var locale = pjQ.$(this).data("id");
				self.opts.locale = locale;
				pjQ.$(this).addClass("fdLocaleFocus").parent().parent().find("a.fdSelectorLocale").not(this).removeClass("pcLocaleFocus");
				
				pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionLocale"].join(""), {
					"session_id": self.opts.session_id,
					"locale_id": locale
				}).done(function (data) {
					window.location.reload();
				}).fail(function () {
					log("Deferred is rejected");
				});
				return false;
			}).on("click.fd", ".fdPrev", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var leftPos = pjQ.$('.fdCategoryList').scrollLeft();
				pjQ.$(".fdCategoryList").animate({scrollLeft: leftPos - 100}, 500);
				return false;
			}).on("click.fd", ".fdNext", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var total_width = 0,
				    leftPos = pjQ.$('.fdCategoryList').scrollLeft();
				pjQ.$("#fdCateInner_" + self.opts.index).find(".fdCateItem").each(function (order, item) {
		    		total_width += pjQ.$(item).outerWidth() + 12;
				});
		    	pjQ.$("#fdCateInner_" + self.opts.index).width(total_width);
				pjQ.$(".fdCategoryList").animate({scrollLeft: leftPos + 100}, 500);
				return false;
			}).on("click.fd", ".fdProductTitle", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var product_id = pjQ.$(this).attr('data-id'),
					$fdProduct =  pjQ.$('#fdProductBox_' + product_id),
					$fdSize = pjQ.$('#fdSelectSize_' + product_id);
				if($fdProduct.hasClass('fdProductBoxSelected'))
				{
					$fdProduct.removeClass('fdProductBoxSelected');
				}else{
					$fdProduct.addClass('fdProductBoxSelected');
				}
				if($fdSize.length > 0 && $fdSize.val() == '')
				{
					pjQ.$(pjQ.$('#fdProductOrder_' + product_id)).addClass("fdButtonDisabled");
				}
				return false;
			}).on("change.fd", ".fdSelectSize", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var product_id = pjQ.$(this).attr('data-id'),
					$fdProduct =  pjQ.$('#fdProductBox_' + product_id);
				if(pjQ.$(this).val() != '')
				{
					if(!$fdProduct.hasClass('fdProductBoxSelected'))
					{
						$fdProduct.addClass('fdProductBoxSelected');
					}
					pjQ.$(pjQ.$('#fdProductOrder_' + product_id)).removeClass("fdButtonDisabled");
				}else{
					$fdProduct.removeClass('fdProductBoxSelected');
					pjQ.$(pjQ.$('#fdProductOrder_' + product_id)).addClass("fdButtonDisabled");
				}
				return false;
			}).on("click.fd", ".fdImage", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var product_id = pjQ.$(this).attr('data-id'),
					$fdProduct =  pjQ.$('#fdProductBox_' + product_id);
				if($fdProduct.hasClass('fdProductBoxSelected'))
				{
					$fdProduct.removeClass('fdProductBoxSelected');
				}else{
					$fdProduct.addClass('fdProductBoxSelected');
				}
				return false;
			}).on("click.fd", ".fdCategoryNode", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var category_id = parseInt(pjQ.$(this).attr('data-id'), 10);
				self.loadCategories.call(self, category_id);
				return false;
			}).on("click.fd", ".fdAddExtra", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var index = pjQ.$(this).attr('data-index');
				pjQ.$('#fdQty_' + index).val(1);
				pjQ.$(this).parent().addClass('fdExtraBoxSelected');
				return false;
			}).on("click.fd", ".fdQtyCheck", function (e) {
				// if (e && e.preventDefault) {
				// 	e.preventDefault();
				// }
				var index = pjQ.$(this).attr('data-index'),
				value = 0;
				console.log(index);
				if (pjQ.$(this).is(':checked')) {
					console.log('came here');
					value = 1;
				} else {
					value = 0;
					console.log('came here else');
				}
				pjQ.$('#fdQty_' + index).val(value);
				// return false;
			})

			.on("click.fd", ".fdOperator", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var index = pjQ.$(this).attr('data-index'),
					sign = pjQ.$(this).attr('data-sign'),
					product_id = pjQ.$(this).attr('data-product'),
					value = parseInt(pjQ.$('#fdQty_' + index).val(), 10);
				if(sign == '+')
				{
					value++;
				}else{
					if(value > 0)
					{
						value--;
					}
				}
				pjQ.$('#fdQty_' + index).val(value);
				return false;
			}).on("click.fd", ".navbar-right a", function(e) {
				//alert("Clicked");
				pjQ.$(".navbar-right").find(".active").removeClass("active");
                pjQ.$(this).parent().addClass("active");
			}).on("keypress.fd", ".fdQtyInput", function (e) {
				if(e.which !=8 && isNaN(String.fromCharCode(e.which)))
				{
					e.preventDefault();
				}
			}).on("keyup.fd", ".fdQtyInput", function (e) {
				if(parseInt(pjQ.$(this).val(),10) == 0)
				{
					pjQ.$(this).parent().parent().parent().removeClass('fdExtraBoxSelected');
				}else if(pjQ.$(this).val() == ''){
					pjQ.$(this).val(0);
					pjQ.$(this).parent().parent().parent().removeClass('fdExtraBoxSelected');
				}
					
			}).on("focus.fd", ".fdQtyInput", function (e) {
				pjQ.$(this).select();
			}).on("click.fd", ".fdProductOrder", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $frm = pjQ.$(this).closest("form"),
					addable = true,
					product_id = pjQ.$(this).attr('data-id'),
					$size = pjQ.$('#fdSelectSize_' + product_id);
				//console.log(pjQ.$(this).parent().siblings(".pjFdProductPrice").text());
				//var $type =pjQ.$("#typeToggle").parent(".toggle").hasClass("btn-danger") ? "pickup" : "delivery";
				console.log($frm.serialize());
				var $type =pjQ.$("#orderTypeCounter").val();
				if($size.length > 0 && $size.val() == '')
				{
					addable = false;
				}
				if(addable == true)
				{
					// if (pjQ.$(window).width() <= 767) {
					// 	pjQ.$('.fdLoader').css('top', '-200px');
					// }
					pjQ.$('.fdLoader').css('display', 'block');
					pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontCart&action=pjActionAddProduct", "&session_id=", self.opts.session_id].join(""), $frm.serialize()).done(function (data) {
						console.log('FoodDeliveryJS -459', data);
						self.cart = '';
						self.loadCart.apply(self, ['', function() {
							pjQ.$('.fdLoader').css('display', 'none');
							pjQ.$('.fdQtyInput').val(0);
							pjQ.$('.fdQtyCheck').prop('checked', false);
							// if ($type != 'pickup') {
							// 	pjQ.$("#typeToggle").parent(".toggle").removeClass("btn-danger");
							// 	pjQ.$("#typeToggle").parent(".toggle").addClass("btn-success");
							// }
                            
							//pjQ.$('.fdMyOrderPrice').val(pjQ.$('.fdPriceTotal').val());
							// pjQ.$('html, body').animate({
						 //        scrollTop: pjQ.$('#pjFdCartWrapper_' + self.opts.index).offset().top
						 //    }, 500);
						}]);
						

					});

				}
				

				return false;
			}).on("click.fd", ".fdCartItemRemove", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var hash = pjQ.$(this).attr('data-hash'),
					extra_id = pjQ.$(this).attr('data-extra');
				if(pjQ.$('#fdLoginForm_' + self.opts.index).length > 0)
				{
					self.cart = 'plain';
				}
				self.removeItem.apply(self, [hash, extra_id]);
				return false;
			}).on("click.fd", ".toggle.orderType", function (e) {
				//console.log('clicked');
				self.loadCart.call(self);
			}).on("click.fd", "#orderType input", function (e) {
				var type = $(this).val();
				$("#orderTypeCounter").val(type);
				pjQ.$('.fdLoader').css('display', 'block');
				self.loadCart.call(self);
			    pjQ.$('.fdLoader').css('display', 'none');
			}).on("click.fd", ".fdCartQty", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var hash = pjQ.$(this).attr('data-hash'),
					action = pjQ.$(this).attr('data-action'),
					sign = pjQ.$(this).attr('data-sign');
				pjQ.$('.fdLoader').css('display', 'block');
				pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontCart&action=pjActionUpdateCart", "&session_id=", self.opts.session_id].join(""), {
					"hash": hash,
					"sign": sign
				}).done(function (data) {
					if(pjQ.$('#fdLoginForm_' + self.opts.index).length > 0)
					{
						self.cart = 'plain';
					}
					if(pjQ.$('#fdCheckoutForm_' + self.opts.index).length > 0)
					{
						self.cart = 'total';
					}
					if (pjQ.$(".fdButtonConfirm").length) {
						self.cart = 'total';
					}
					if(action == 'pjActionVouchers')
					{
						if (!hashBang("#!/loadVouchers")) {
							pjQ.$(window).trigger("loadVouchers");
						}
					}else{
						self.loadCart.apply(self, ['', function(){
							pjQ.$('.fdLoader').css('display', 'none');
						}]);
					}
				});
				return false;
			}).on("click.fd", "#cartBtnFindPostCode", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var type =pjQ.$("#orderTypeCounter").val();
				var logged = pjQ.$(this).attr('data-logged');
				var hasPostcode = pjQ.$(this).attr('data-haspostcode');
				var postcode_input = $("#cartInputPostCode");
				var submit = false;
				validatePostcode(postcode_input, logged, self.opts.session_id, type, submit, hasPostcode);
			}).on("click.fd", ".fdButtonCheckout", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var type =pjQ.$("#orderTypeCounter").val();
				var logged = pjQ.$(this).attr('data-logged');
				var hasPostcode = pjQ.$(this).attr('data-haspostcode');
				if (type == 'delivery') {
					var postcode_input = $("#cartInputPostCode");
					var submit = true;
                    validatePostcode(postcode_input, logged, self.opts.session_id, type, submit, hasPostcode);
				} else {
					if (pjQ.$("#post_code").hasClass('has-error')) {
						pjQ.$(".fdBtnOrderTotal").addClass('disabled');
						pjQ.$("#post_code").removeClass('has-error');
						pjQ.$("#postCodeErr").text("");
						pjQ.$("#postCodeErr").css("display", "none");
					}
					pjQ.$('.fdLoader').css('display', 'block');
					pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionSetType", "&session_id=", self.opts.session_id, "&type=", type].join("")).done(function (data) {
						if(logged == 'no')
						{
							hashBang("#!/loadLogin");
						}else{
							
							hashBang("#!/loadTypes");
						}
					});
				}
				return false;
			}).on("click.fd", ".search-me", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				$("#searchInput-group").css("display","flex");
				$(this).css("display", "none");
                $("#searchInput-group").css("width", "90%");
				pjQ.$(".fa-close").css("display", "block");
			}).on("click.fd", "#food-items .fa-close", function (e) {
				$("#searchInput").val(null);
				$('.fdSearchResults').html('');
				$(".search-me").css("display","block");
				$("#search").css('display', 'none');
				pjQ.$(".tab-content").removeClass("d-none");
			}).on("click.fd", ".search-btn", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $keyword = $("#searchInput").val();
				var $page = pjQ.$("input[name=page_type]").val();
				var $hasImg = pjQ.$(".clickCategory.active").children(".has_img").val();
				pjQ.$('.fdSearchResults').html('');
				self.getSearchResults.call(self, $keyword, $page, $hasImg);
			}).on("click.fd", "#product_stars .fa-star", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var logged = pjQ.$(this).attr('data-logged');
				var star = pjQ.$(this).attr('data-star');
				var via = pjQ.$(this).attr('data-via');
				var product = pjQ.$(this).attr('data-product');
				var page = pjQ.$('#fdMain_' + self.opts.index).attr('data-page');
				self.review = [star, via, product, page];
				pjQ.$('.fdLoader').css('display', 'block');
				if(logged == 'no')
				{
					hashBang("#!/loadLogin");
				}else{
					hashBang("#!/loadReview");
				}
				return false;
			}).on("click.fd", ".food-review-text", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var logged = pjQ.$(this).attr('data-logged');
				var via = pjQ.$(this).attr('data-via');
				var product = pjQ.$(this).attr('data-product');
				var page = pjQ.$('#fdMain_' + self.opts.index).attr('data-page');
				self.review = [0, via, product, page];
				pjQ.$('.fdLoader').css('display', 'block');
				if(logged == 'no')
				{
					hashBang("#!/loadLogin");
				}else{
					hashBang("#!/loadReview");
				}
				return false;
			}).on("click.fd", "#write_review", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var logged = pjQ.$(this).attr('data-logged');
				pjQ.$('.fdLoader').css('display', 'block');
				if(logged == 'no')
				{
					hashBang("#!/loadLogin");
					pjQ.$('.fdLoader').css('display', 'none');
				}else{
					hashBang("#!/loadReview");
					pjQ.$('.fdLoader').css('display', 'none');
				}
				return false;
			}).on("click.fd", ".tot-rate", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $p_id = pjQ.$(this).attr("data-product");
				self.review[0] = 0;
				self.review[1] = 'link';
				self.review[2] = $p_id;
				pjQ.$('.fdLoader').css('display', 'block');
				hashBang("#!/loadRating");
				pjQ.$('.fdLoader').css('display', 'none');
				
				return false;
			}).on("click.fd", "#all_reviews", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $p_id = pjQ.$(this).attr("data-product");
				pjQ.$('.fdLoader').css('display', 'block');
				// if(logged == 'no')
				// {
				// 	hashBang("#!/loadLogin");
				// }else{
					hashBang("#!/loadRating");
					pjQ.$('.fdLoader').css('display', 'none');
				//}
				return false;
			}).on("click.fd", ".fdTypeTab", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var type = pjQ.$(this).attr('aria-controls');
				pjQ.$('.fdTabOuter').removeClass('active');
				pjQ.$(this).parent().addClass('active');
				if(type == 'pickup')
				{
					pjQ.$("#fdTypePickup_" + self.opts.index).click();
					self.$container.find('.fdButtonRefDelivery').hide();
					self.$container.find('.fdButtonRefPickup').show();
				}else{
					pjQ.$("#fdTypeDelivery_" + self.opts.index).click();
					self.$container.find('.fdButtonRefPickup').hide();
					self.$container.find('.fdButtonRefDelivery').show();
				}
				return false;
			}).on("click.fd", ".fdButtonRefDelivery", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				return false;
			}).on("click.fd", ".fdBtnLogoutt", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				alert("test");
				return false;
			}).on("click.fd change.fd", "input[name='type']", function (e) {
				var $this = pjQ.$(this);
				switch ($this.filter(":checked").val()) {
					case 'pickup':
						pjQ.$(".fdPickup").show();
						pjQ.$(".fdDelivery").hide();
						self.type = 'pickup';
						if (myPickupMap !== null) {
							google.maps.event.trigger(myPickupMap.map, "resize");
						}
						break;
					case 'delivery':
						pjQ.$(".fdDelivery").show();
						if (myDeliveryMap !== null) {
							if(myTmp.cnt == 1)
							{
								var lastCenter = myDeliveryMap.map.getCenter();
								google.maps.event.trigger(myDeliveryMap.map, "resize");
								myDeliveryMap.map.setCenter(lastCenter);
								myDeliveryMap.map.setZoom(9);
							}else if(myTmp.cnt > 1){
								myDeliveryMap.map.fitBounds(myDeliveryBounds);
								var lastCenter = myDeliveryMap.map.getCenter();
								google.maps.event.trigger(myDeliveryMap.map, "resize");
								myDeliveryMap.map.setCenter(lastCenter);
							}
						}
						pjQ.$(".fdPickup").hide();
						break;
				}
			}).on("change.fd", "select[name='p_location_id']", function (e) {
				var $this = pjQ.$(this),
					location_id = pjQ.$("option:selected", this).val();
				if(location_id == '')
				{
					$this.parent().parent().remove('has-success').addClass('has-error');
				}
				pjQ.$('.fdLoader').css('display', 'block');
				self.location_id = location_id;
				self.getLocation.apply(self, [location_id, true, function(e){
					self.getWTime.apply(self, [pjQ.$("#fd_p_date_" + self.opts.index).val(), location_id, 'pickup', function (data) {
						pjQ.$(".fdPickupTime").html(data);
					}]);
				}]);
				self.setDays.apply(self, [location_id, 'pickup']);
				self.pickupDate.opts.onBeforeShowDay = function (date) 
				{
					return self._daysOff.apply(self, [date]);
				};
				
			}).on("change.fd", "select[name='d_location_id']", function (e) {
				
				var $this = pjQ.$(this), 
					d_location_id = pjQ.$("option:selected", this).val(),
					d_location_name = pjQ.$("option:selected", this).text();
				
				if(d_location_id != '')
				{
					pjQ.$(".fdDeliveryNote").html(self.opts.messages[11].replace('{LOCATION}', d_location_name));
					pjQ.$(".fdDeliveryNote").parent().show();
				}else{
					$this.parent().parent().remove('has-success').addClass('has-error');
				}
				self.setDays.apply(self, [d_location_id, 'delivery']);
				self.deliveryDate.opts.onBeforeShowDay = function (date) {
					return self._daysOff.apply(self, [date]);
				};
				
				if(self.myDeliveryOverlays != null && self.myDeliveryMap != null)
				{
					var shape_focus = null;
					for (var i = 0, len = self.myDeliveryOverlays.length; i < len; i++) {
						self.myDeliveryMap.removeFocus(self.myDeliveryOverlays, self.myDeliveryOverlays[i].myObj.id);
						if (self.myDeliveryOverlays[i].myObj.location_id == d_location_id) {
							shape_focus = self.myDeliveryOverlays[i];
						}
					}
					if(shape_focus != null)
					{
						self.myDeliveryMap.setFocus(shape_focus);
					}
				}
				pjQ.$('.fdLoader').css('display', 'block');
				self.getWTime.apply(self, [pjQ.$("#fd_d_date_" + self.opts.index).val(), d_location_id, 'delivery', function (data) {
					pjQ.$(".fdDeliveryTime").html(data);
				}]);
				
			}).on("click.fd", ".fdButtonGetCategories", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('.fdLoader').css('display', 'block');
				
				window.location.href = $frontend+"menu.php";
				
				return false;
			}).on("click.fd", ".fdButtonPostPrice", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(!pjQ.$(this).hasClass('fdButtonDisabled') && pjQ.$(this).hasClass('fdButtonRefDelivery'))
				{
					var isPostcodeValidated = pjQ.$("#inputPostCode").attr("data-validated");
					if (isPostcodeValidated == 1) {
						if (pjQ.$("#post_code").hasClass('has-error')) {
							pjQ.$("#post_code").removeClass('has-error');
							pjQ.$("#postCodeErr").text("");
						    pjQ.$("#postCodeErr").css("display", "none");
						}
                        if (!self.validateTypes.call(self)) {
							return false;
						}
						var $frm = pjQ.$('#fdMain_' + self.opts.index).find("form");
						// if (pjQ.$(window).width() <= 767) {
						// 	pjQ.$('.fdLoader').css('top', '-200px');
						// }
						pjQ.$('.fdLoader').css('display', 'block');
						pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionSetTypes", "&session_id=", self.opts.session_id].join(""), $frm.serialize()).done(function (data) {
							hashBang("#!/loadCheckout");
							// hashBang("#!/loadVouchers");
							// pjQ.$(this).css("display","none");
							// pjQ.$(".fdButtonPayment").css("display","block");
						});
						// pjQ.$('.fdLoader').css('display', 'none');
					} else {
						pjQ.$("#post_code").addClass('has-error');
						pjQ.$("#postCodeErr").text("Please validate your postcode...");
						pjQ.$("#postCodeErr").css("display", "block");
						$('html,body').animate({
							scrollTop: '0'
						}, 500);
					}
					
				}
				if(!pjQ.$(this).hasClass('fdButtonDisabled') && pjQ.$(this).hasClass('fdButtonRefPickup'))
				{
					if (pjQ.$("#post_code").hasClass('has-error')) {
						pjQ.$("#post_code").removeClass('has-error');
						pjQ.$("#postCodeErr").text("");
						pjQ.$("#postCodeErr").css("display", "none");
					}
					if (!self.validateTypes.call(self)) {
						return false;
					}
					var $frm = pjQ.$('#fdMain_' + self.opts.index).find("form");
					// if (pjQ.$(window).width() <= 767) {
					// 	pjQ.$('.fdLoader').css('top', '-200px');
					// }
					pjQ.$('.fdLoader').css('display', 'block');
					pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionSetTypes", "&session_id=", self.opts.session_id].join(""), $frm.serialize()).done(function (data) {
						hashBang("#!/loadCheckout");
						// hashBang("#!/loadVouchers");
						// pjQ.$(this).css("display","none");
						// pjQ.$(".fdButtonPayment").css("display","block");
					});
					// pjQ.$('.fdLoader').css('display', 'none');
					
				}
				return false;
			}).on("click.fd", ".fdButtonGetTypes", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				// if (pjQ.$(window).width() <= 767) {
				// 	pjQ.$('.fdLoader').css('top', '-200px');
				// }
				pjQ.$('.fdLoader').css('display', 'block');
				hashBang("#!/loadTypes");
				return false;
			}).on("click.fd", ".fdButtonGetVouchers", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				// if (pjQ.$(window).width() <= 767) {
				// 	pjQ.$('.fdLoader').css('top', '-200px');
				// }
				pjQ.$('.fdLoader').css('display', 'block');
				//hashBang("#!/loadVouchers");
				hashBang("#!/loadTypes");
				return false;
			}).on("click.fd", ".fdButtonLogin", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if (!self.validateLogin.call(self)) {
					return false;
				}
				var $frm = pjQ.$('#fdLoginForm_' + self.opts.index);
				// if (pjQ.$(window).width() <= 767) {
				// 	pjQ.$('.fdLoader').css('top', '-200px');
				// }
				pjQ.$('.fdLoader').css('display', 'block');
				pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionCheckLogin", "&session_id=", self.opts.session_id].join(""), $frm.serialize()).done(function (data) {
					if(data.code != '200') {
						if(data.code == 500 ) {
							pjQ.$("#errMsg").text("Please do login with gmail!!");
							pjQ.$("#errMsg").removeClass('d-none');
						}
						pjQ.$('#fdLoginMessage_' + self.opts.index).html(data.text);
						pjQ.$('#fdLoginMessage_' + self.opts.index).parent().css('display', 'block');
						pjQ.$('.fdLoader').css('display', 'none');
					} else {
						$(".fdBtnLogout").css('display','block');
						if(pjQ.$('#fdCart_' + self.opts.index).find('.fdEmptyCart').length > 0) {
							hashBang("#!/loadMain");
							window.location.reload();
						} else {
							hashBang("#!/loadTypes");
							window.location.reload();
						}
					}
				});
				return false;
			}).on("click.fd", ".fdButtonOtp", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if (!self.validateOtp.call(self)) {
					return false;
				}
				var $frm = pjQ.$('#fdOtpForm_' + self.opts.index);
				// if (pjQ.$(window).width() <= 767) {
				// 	pjQ.$('.fdLoader').css('top', '-200px');
				// }
				pjQ.$('.fdLoader').css('display', 'block');
				pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionCheckOtp", "&session_id=", self.opts.session_id].join(""), $frm.serialize()).done(function (data) {
					if(data.code != '200') {
						pjQ.$('.fdLoader').css('display', 'none');
						$("#otpWrong").css('display', 'block');
						
						hashBang("#!/loadOtpPage");
					} else {
						// pjQ.$('.fdLoader').css('display', 'none');
						// console.log("comes to modal");
						
						// $("#previewModal").modal('show');
						self.loadPreview.call(self);
						//hashBang("#!/loadPreview");
					}
				});
				return false;
			}).on("click.fd", ".fdButtonOtpResend", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionResendOtp", "&session_id=", self.opts.session_id].join("")).done(function (data) {
					//console.log(data);
					if (data.code == 200) {
						location.reload();
						hashBang("#!/loadOtpPage");
					}
				});
				return false;
			}).on("click.fd", ".fdContinue", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(pjQ.$('#fdCart_' + self.opts.index).find('.fdEmptyCart').length == 0) {
					// if (pjQ.$(window).width() <= 767) {
					// 	pjQ.$('.fdLoader').css('top', '-200px');
					// }
					pjQ.$('.fdLoader').css('display', 'block');
					hashBang("#!/loadTypes");
				}
				return false;
			}).on("click.fd", ".fdContinueGuest", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$.post(self.opts.folder+"index.php?controller=pjFrontPublic&action=pjActionSetGuest").done(function (data) {
					if (!data.code) {
						return;
					}
					if (data.code == 200) {
						if(pjQ.$('#fdCart_' + self.opts.index).find('.fdEmptyCart').length == 0)
						{
							// if (pjQ.$(window).width() <= 767) {
							// 	pjQ.$('.fdLoader').css('top', '-200px');
							// }
							pjQ.$('.fdLoader').css('display', 'block');
							hashBang("#!/loadTypes");
						}
					}
				});
				
				return false;
			}).on("click.fd", ".g-signin2", function (e) {
				onSignIn();
				// if (e && e.preventDefault) {
				// 	e.preventDefault();
				// }
				// pjQ.$.post("index.php?controller=pjFrontEnd&action=pjActionSetGuest").done(function (data) {
				// 	if (!data.code) {
				// 		return;
				// 	}
				// 	if (data.code == 200) {
				// 		if(pjQ.$('#fdCart_' + self.opts.index).find('.fdEmptyCart').length == 0)
				// 		{
				// 			pjQ.$('.fdLoader').css('display', 'block');
				// 			hashBang("#!/loadTypes");
				// 		}
				// 	}
				// });
				
				// return false;
			}).on("click.fd", ".imageToggle.btn-success", function() {
				var navItems = pjQ.$(".clickCategory").children(".has_img");
				var thisPage = pjQ.$("#fdMain_" + self.opts.index).attr("data-page");
				navItems.each(function() {
					pjQ.$(this).val(1);
				})
				var activeNavItem = pjQ.$(".clickCategory.active");
				activeNavItem.each(function() {
					var thisCategory = pjQ.$(this).children(".cat_id").val();
					self.getProducts.call(self, thisCategory, thisPage, 1);
				})
			}).on("click.fd", ".imageToggle.btn-danger", function() {
				var navItems = pjQ.$(".clickCategory").children(".has_img");
				var thisPage = pjQ.$("#fdMain_" + self.opts.index).attr("data-page");
				navItems.each(function() {
					pjQ.$(this).val(0);
				})
				var activeNavItem = pjQ.$(".clickCategory.active");
				activeNavItem.each(function() {
					var thisCategory = pjQ.$(this).children(".cat_id").val();
					self.getProducts.call(self, thisCategory, thisPage, 0);
				})
			}).on("click.fd", "#fdImgCheck .btn-show", function() {
				$(this).addClass("active");
				$("#fdImgCheck .btn-hide").removeClass("active");
				var navItems = pjQ.$(".clickCategory").children(".has_img");
				var thisPage = pjQ.$("#fdMain_" + self.opts.index).attr("data-page");
				navItems.each(function() {
					pjQ.$(this).val(1);
				})
				if(pjQ.$("#search").css("display") == "block") {
					var $keyword = pjQ.$("#searchInput-group input").val();
                    self.getSearchResults($keyword, thisPage, 1);
				} else {
                    var activeNavItem = pjQ.$(".clickCategory.active");
				    activeNavItem.each(function() {
						var thisCategory = pjQ.$(this).children(".cat_id").val();
						self.getProducts.call(self, thisCategory, thisPage, 1);
					})
				}
				
			}).on("click.fd", "#fdImgCheck .btn-hide", function() {
				$(this).addClass("active");
				$("#fdImgCheck .btn-show").removeClass("active");
				var navItems = pjQ.$(".clickCategory").children(".has_img");
				var thisPage = pjQ.$("#fdMain_" + self.opts.index).attr("data-page");
				navItems.each(function() {
					pjQ.$(this).val(0);
				})
				if(pjQ.$("#search").css("display") == "block") {
					var $keyword = pjQ.$("#searchInput-group input").val();
                    self.getSearchResults($keyword, thisPage, 0);
				} else {
					var activeNavItem = pjQ.$(".clickCategory.active");
					activeNavItem.each(function() {
						var thisCategory = pjQ.$(this).children(".cat_id").val();
						self.getProducts.call(self, thisCategory, thisPage, 0);
					})
			    }
			}).on("change.fd", "#fdPaymentMethod_" + self.opts.index, function (e) {
				pjQ.$('#fdBankData_' + self.opts.index).hide();
				if(pjQ.$(this).val() == 'bank')
				{
					pjQ.$('#fdBankData_' + self.opts.index).show();
				}
			}).on("click.fd", "#fdBtnTerms_" + self.opts.index, function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $terms = pjQ.$("#fdTermContainer_" + self.opts.index);
				if($terms.is(':visible')){
					$terms.css('display', 'none');
				}else{
					$terms.css('display', 'block');
				}
				return false;
			}).on("click.fd", ".fdButtonApply", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(!pjQ.$(this).hasClass('fdButtonDisabled'))
				{
					//var voucher_code = pjQ.$('#fdVoucherCode_' + self.opts.index).val();
					var voucher_code = pjQ.$('#fdVoucherCode').val();
					var cart_total = pjQ.$('#cartTotal').attr('data-totalAmt');
					var min_price = pjQ.$('#fdMinPrice').attr('data-price');
					var min_price_with_sign =  pjQ.$('#fdMinPrice').text();
					if(voucher_code != '')
					{
						if (parseFloat(cart_total) > parseFloat(min_price)) {
							//pjQ.$('#fdVoucherMessage_' + self.opts.index).html("Minimum Price is "+min_price_with_sign).hide();
							pjQ.$('#fdVoucherMessage').html("Minimum Price is "+min_price_with_sign).hide();
							// if (pjQ.$(window).width() <= 767) {
							// 	pjQ.$('.fdLoader').css('top', '-200px');
							// }
							pjQ.$('.fdLoader').css('display', 'block');
							pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontCart&action=pjActionAddPromo", "&session_id=", self.opts.session_id].join(""), {voucher_code: voucher_code}).done(function (data) {
								if (!data.code) {
									return;
								}
								switch (data.code) {
									case 200:
										// if (!hashBang("#!/loadVouchers")) {
										// 	pjQ.$(window).trigger("loadVouchers");
										// }
										// if (!hashBang("#!/loadTypes")) {
										// 	pjQ.$(window).trigger("loadTypes");
										// }
										$("#promoCodeCounter").val("valid");
										self.loadCart.call(self);
										//
										pjQ.$('.fdLoader').css('display', 'none');
										break;
									default:
										$("#promoCodeCounter").val("invalid");
										//pjQ.$('#fdVoucherMessage_' + self.opts.index).html(data.text).parent().parent().show();
										pjQ.$('.fdLoader').css('display', 'none');
										self.loadCart.call(self);
										//pjQ.$('#fdVoucherMessage_' + self.opts.index).html(data.text).show();
										break;
								}
							});
					    } else {
								$("#promoCodeCounter").val("minpriceErr");
								pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontCart&action=pjActionResetPromo", "&session_id=", self.opts.session_id].join("")).done(function (data) {
									self.loadCart.call(self);
								    pjQ.$('.fdLoader').css('display', 'none');
								});
							//pjQ.$('#fdVoucherMessage_' + self.opts.index).html("Minimum Price is "+min_price_with_sign).show();
						}
					} else {
						$("#promoCodeCounter").val("empty");
						pjQ.$('.fdLoader').css('display', 'block');
						pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontCart&action=pjActionResetPromo", "&session_id=", self.opts.session_id].join("")).done(function (data) {
							self.loadCart.call(self);
							pjQ.$('.fdLoader').css('display', 'none');
							
						});
					}
				}
				pjQ.$(this).removeClass('fdButtonDisabled');
				return false;
			}).on("click.fd", ".fdButtonPostcodeValidate", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(!pjQ.$(this).hasClass('fdButtonDisabled'))
				{
					var voucher_code = pjQ.$('#fdVoucherCode_' + self.opts.index).val();
					if(voucher_code != '')
					{
						// if (pjQ.$(window).width() <= 767) {
						// 	pjQ.$('.fdLoader').css('top', '-200px');
						// }
						pjQ.$('.fdLoader').css('display', 'block');
						pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontCart&action=pjActionVoucherValidate", "&session_id=", self.opts.session_id].join(""), {voucher_code: voucher_code}).done(function (data) {
							if (!data.code) {
								return;
							}
							switch (data.code) {
								case 200:
									pjQ.$('.fdLoader').css('display', 'none');
									// pjQ.$('#isPostcodeValidated').val(1);
									pjQ.$('#fdVoucherSuccessMessage_' + self.opts.index).html("Promo code is valid").show();
									break;
								default:
									//console.log("comes");
									pjQ.$('#fdVoucherMessage_' + self.opts.index).html(data.text).show();
									pjQ.$('.fdLoader').css('display', 'none');
									// pjQ.$('#isPostcodeValidated').val(0);
									break;
							}
						});
					}
				}
				return false;
			}).on("click.fd", ".fdTabDelivery", function() {
				var voucherTotal = pjQ.$("#cartTotal").attr('data-totalAmt');
				var minPrice = pjQ.$("#fdMinPrice").attr('data-price');
				if(parseFloat(voucherTotal) < parseFloat(minPrice)) {
					$(".fdButtonRefDelivery").addClass('disabled');
					$("#typesMinPriceErr").css('display', 'block');
				}
			}).on("click.fd", ".fdTabPickup", function() {
				if($("#typesMinPriceErr").css('display') == 'block') {
					$("#typesMinPriceErr").css('display', 'none');
				}
			}).on("click.fd", "#backToMenu", function() {
				hashBang("#!/loadMain");
			}).on("click.fd", ".fdButtonBackTypes", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(!pjQ.$(this).hasClass('fdButtonDisabled'))
				{
					pjQ.$('.fdLoader').css('display', 'block');
					hashBang("#!/loadTypes");
				}
				return false;
			}).on("click.fd", ".fdButtonPayment", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(!pjQ.$(this).hasClass('fdButtonDisabled'))
				{
					pjQ.$('.fdLoader').css('display', 'block');
					hashBang("#!/loadCheckout");
				}
				return false;
			}).on("click.fd", ".fdButtonGetLogin", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('.fdLoader').css('display', 'block');
				hashBang("#!/loadLogin");
				return false;
			}).on("click.fd", ".fdButtonGetPreview", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				//console.log("comes to preview");
				if(!pjQ.$(this).hasClass('fdButtonDisabled'))
				{
					pjQ.$('#fdCheckoutForm_' + self.opts.index).trigger('submit');
				}
				return false;
			}).on("click.fd", ".fdButtonGetCheckout", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(!pjQ.$(this).hasClass('fdButtonDisabled'))
				{
					pjQ.$('.fdLoader').css('display', 'block');
					hashBang("#!/loadCheckout");
				}
				return false;
			}).on("click.fd", ".fdButtonConfirm", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(!pjQ.$(this).hasClass('fdButtonDisabled'))
				{
					self.disableButtons.call(self);
					var $msg_container = pjQ.$('#fdOrderMessage_' + self.opts.index);
					$msg_container.html(self.opts.messages[12]);
					$msg_container.parent().parent().css('display', 'flex');
					pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionSaveOrder", "&session_id=", self.opts.session_id].join("")).done(function (data) {
						if (!data.code) {
							return;
						}
						if(parseInt(data.code, 10) == 200)
						{
							self.getPaymentForm(data);
							
						} else {
							$msg_container.html(data.text);
							self.enableButtons.call(self);
						}
					});
				}
				return false;
			}).on("click.fd", ".fdButtonConfirm", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if(!pjQ.$(this).hasClass('fdButtonDisabled'))
				{
					self.disableButtons.call(self);
					var $msg_container = pjQ.$('#fdOrderMessage_' + self.opts.index);
					$msg_container.html(self.opts.messages[12]);
					$msg_container.parent().parent().css('display', 'flex');
					pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionSaveOrder", "&session_id=", self.opts.session_id].join("")).done(function (data) {
						if (!data.code) {
							return;
						}
						if(parseInt(data.code, 10) == 200)
						{
							self.getPaymentForm(data);
						}else{
							$msg_container.html(data.text);
							self.enableButtons.call(self);
						}
					});
				}
				return false;
			}).on("click.fd", ".fdStartOver", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if (hashBang("#!/loadMain")) {
					window.location.reload();
				}
				return false;
			}).on("click.fd", ".fdChangePersonal", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('.fdPersonalField ').prop("disabled", false);
				return false;
			}).on("click.fd", ".fdChangeAddress", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('.fdAddrField').prop("disabled", false);
				pjQ.$('#fdPreviousAddresses_' + self.opts.index).show();
				return false;
			}).on("change.fd", "#fdPreviousAddr_" + self.opts.index, function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $selected = pjQ.$('option:selected', pjQ.$(this));
				var $form = pjQ.$('#fdTypeForm_' + self.opts.index),
					add1 = $selected.attr('data-add1'),
					add2 = $selected.attr('data-add2'),
					city = $selected.attr('data-city'),
					state = $selected.attr('data-state'),
					zip = $selected.attr('data-zip'),
					country = $selected.attr('data-country');
				
				$form.find("select[name='d_country_id']").val(country);
				$form.find("input[name='d_address_1']").val(add1);
				$form.find("input[name='d_address_2']").val(add2);
				$form.find("input[name='d_city']").val(city);
				$form.find("input[name='d_state']").val(state);
				$form.find("input[name='d_zip']").val(zip);
				return false;
			}).on("click.fd", ".fdButtonSkipStep", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('.fdLoader').css('display', 'block');
				hashBang("#!/loadCheckout");
				return false;
			}).on("click.fd", ".fdForogtPassword", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('.fdLoader').css('display', 'block');
				hashBang("#!/loadForgot");
				return false;
			}).on("click.fd", ".fdButtonSend", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if (!self.validateForgot.call(self)) {
					return false;
				}
				var $frm = pjQ.$('#fdForgotForm_' + self.opts.index);
				pjQ.$('.fdLoader').css('display', 'block');
				pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionSendPassword", "&session_id=", self.opts.session_id].join(""), $frm.serialize()).done(function (data) {
					pjQ.$('#fdForgotMessage_' + self.opts.index).html(self.opts.forgot_messages[parseInt(data.code, 10)]).parent().css('display', 'block');
					pjQ.$('.fdLoader').css('display', 'none');
				});
				return false;
			}).on("click.fd", ".fdValidateLogin", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('.fdLoader').css('display', 'block');
				hashBang("#!/loadLogin");
				return false;
			}).on("click.fd", ".fdBtnLogout", function (e) {

				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('.fdLoader').css('display', 'block');
				pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionLogout", "&session_id=", self.opts.session_id].join("")).done(function (data) {
					if (!hashBang("#!/loadMain")) 
					{
						self.loadMain.call(self);
					}
				});
				return false;
			}).on("click.fd", "#pjFdCaptchaImage_" + self.opts.index, function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('#fdCaptcha_' + self.opts.index).val("").removeData("previousValue");
				pjQ.$(this).attr("src", pjQ.$(this).attr("src").replace(/(&rand=)\d+/g, '\$1' + Math.ceil(Math.random() * 99999)));
				return false;
			}).on("scroll", ".foode-item-box-mob", function() {
				var $val = pjQ.$(this).scrollLeft(); 
	            if(pjQ.$(this).scrollLeft() + pjQ.$(this).innerWidth()>=pjQ.$(this)[0].scrollWidth){
	              pjQ.$(".nav-next").hide();
	            } else {
	              pjQ.$(".nav-next").show();
	            }
	  
	            if($val == 0){
	              pjQ.$(".nav-prev").hide();
	            } else {
	              pjQ.$(".nav-prev").show();
			    }
			}).on("click", ".nav-next", function() {
				pjQ.$(".foode-item-box-mob").animate( { scrollLeft: '+=200' }, 200); 
			}).on("click", ".nav-prev", function() {
				pjQ.$(".foode-item-box-mob").animate( { scrollLeft: '-=200' }, 200);
			}).on("click", "#review-rate .food-review-rate", function() {
				pjQ.$("#hiddenDiv").css("display","block");
			}).on("click", "#logout-btn", function() {
				ggl_user.disconnect();
				console.log('signed out!');
				gmail_btn.style.display = 'block';
				logout_btn.style.display = "none";
			}).on("keyup", "input[name=c_phone]", function() {

				self.validatePhoneNumber($(this));
			});
			
			
			pjQ.$("#pjFdTermsAndConditions").on("show.bs.modal", function () {
				var $modal = pjQ.$(this);
				
				pjQ.$.get(self.opts.folder + "index.php?controller=pjFrontPublic&action=pjActionGetTerms", {
					"session_id": self.opts.session_id,
					"locale": self.opts.locale
				}).done(function (data) {
					$modal.find(".modal-content").html(data);
				});
			});

			pjQ.$("#previewModaal").on("show.bs.modal", function () {
				var $modal = pjQ.$(this);
				
				pjQ.$.get(self.opts.folder + "index.php?controller=pjFrontPublic&action=pjAction", {
					"session_id": self.opts.session_id,
					"locale": self.opts.locale
				}).done(function (data) {
					$modal.find(".modal-content").html(data);
				});
			});
			
			pjQ.$(window).on("loadMain", this.$container, function (e) {
				self.loadMain.call(self, 0);
			}).on("loadMainQr", this.$container, function (e) {
				self.loadMainQr.call(self, 0);
			}).on("loadCart", this.$container, function (e) {
				self.loadCart.call(self);
			}).on("loadOptions", this.$container, function (e) {
				self.loadOptions.call(self);
			}).on("loadTypes", this.$container, function (e) {
				self.loadTypes.call(self);
			}).on("loadLogin", this.$container, function (e) {
				self.loadLogin.call(self);
			}).on("loadHome", this.$container, function (e) {
				self.loadHome.call(self);
			}).on("loadReview", this.$container, function (e) {
				self.loadReview.call(self);
			}).on("loadRating", this.$container, function (e) {
				self.loadRating.call(self);
			}).on("loadProfile", this.$container, function (e) {
				self.loadProfile.call(self);
			}).on("loadForgot", this.$container, function (e) {
				self.loadForgot.call(self);
			}).on("loadVouchers", this.$container, function (e) {
				self.loadVouchers.call(self);
			}).on("loadCheckout", this.$container, function (e) {
				self.loadCheckout.call(self);
			}).on("loadOtpPage", this.$container, function (e) {
				self.loadOtpPage.call(self);
			}).on("loadPreview", this.$container, function (e) {
				self.loadPreview.call(self);
			}).on("loadMyOrders", this.$container, function (e) {
				self.loadMyOrders.call(self);
			}).on("load", this.$container, function() {
			});
			
			if (window.location.hash.length === 0) {
				this.loadMain.call(this);
			} else {
				onHashChange.call(null);
			}
		},
		
		getProducts: function(category_id,page,hasImg){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
						"hide": this.opts.hide,
						"index": this.opts.index,
						"type": ""
					 };
			var $tabPanel = pjQ.$('#tab' + category_id);
			pjQ.$('.fdLoader').css('display', 'block');
			pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionGetProducts", "&session_id=", self.opts.session_id, "&category_id=", category_id, "&page=", page, "&hasImg=", hasImg].join(""), params).done(function (data) {
				$tabPanel.html(data);
				pjQ.$('.fdLoader').css('display', 'none');
			}).fail(function () {
				
			});
		},
		getSearchResults: function($keyword,$page,$hasImg) {
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
						"hide": this.opts.hide,
						"index": this.opts.index,
						"type": ""
					 };
				if ($keyword != '') {
					pjQ.$(".fdSearchResults").html('');
				pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionGetSearchResults", "&key=", $keyword, "&page=", $page, "&hasImg=", $hasImg].join("")).done(function (html) {
					pjQ.$("#search").css('display', 'block');
					pjQ.$(".fdSearchResults").append(html);
					pjQ.$(".tab-content").addClass("d-none");
				});
				
			}
			
		},
		bindMain: function(){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": ""
						 };
			var page = "Main";
			// var hasImg = $hasImg;
			pjQ.$('#pjFdAccordion_' + index).on('shown.bs.collapse', function (e) {
				
				var category_id = pjQ.$('#' + e.target.id).data('id');
				
				if(pjQ.$("#heading" + category_id).length > 0)
				{
					self.getProducts.call(self,category_id,page);
				} 
			});
			pjQ.$('.pjFdProductsType').each(function(e){
				if(pjQ.$(this).attr('aria-expanded') == 'true')
				{
					var category_id = pjQ.$(this).attr('data-cid');
					self.getProducts.call(self,category_id,page);
				} 
			});
			pjQ.$('.clickCategory').each(function(e){
				// console.log(self);
				if(pjQ.$(this).hasClass('active'))
				{
					//var category_id = pjQ.$(this).attr('data-cid');
					var category_id = pjQ.$(this).children(".cat_id").val();
					var hasImg = pjQ.$(this).children(".has_img").val();
					//console.log(category_id);
					self.getProducts.call(self,category_id,page,hasImg);
					return false;
				} 
			});
			pjQ.$('.clickCategory').on('click', function (e) {
				if (pjQ.$("#search").css("display") == "block") {
					pjQ.$("#search").css("display","none");
					pjQ.$(".tab-content").removeClass("d-none");
					$("#searchInput").val('');
				    $('.fdSearchResults').html('');
				}
				// if(pjQ.$(this).hasClass('active'))
				// {
					//var category_id = pjQ.$(this).attr('data-cid');
					var category_id = pjQ.$(this).children("input").val();
					var hasImg = pjQ.$(this).children(".has_img").val();
					console.log(category_id);
					console.log(hasImg);
					self.getProducts.call(self,category_id,page,hasImg);
				//} 
			});
			pjQ.$('.clickCategory').on('click', function (e) {
				if (pjQ.$("#search").css("display") == "block") {
					pjQ.$("#search").css("display","none");
					pjQ.$(".tab-content").removeClass("d-none");
					$("#searchInput").val('');
				    $('.fdSearchResults').html('');
				}
				// if(pjQ.$(this).hasClass('active'))
				// {
					//var category_id = pjQ.$(this).attr('data-cid');
					var category_id = pjQ.$(this).children("input").val();
					var hasImg = pjQ.$(this).children(".has_img").val();
					console.log(category_id);
					console.log(hasImg);
					self.getProducts.call(self,category_id,page,hasImg);
				//} 
			});
			// console.log(pjQ.$("#fdImgCheck").parent());
			// var $dark = pjQ.$("#fdImgCheck").parent();
			// $dark.on("click", function() {
			// 	console.log("clicked");
			// 	if(pjQ.$("#fdImgCheck").parent().hasClass("btn-dark")) {
			// 		hasImg = 1;
			// 		self.getProducts.call(self,1,page,1);
			// 	} else {
			// 		hasImg = 0;
			// 		self.getProducts.call(self,1,page,0);
			// 	}
				
			// 	//console.log(pjQ.$("#fdImgCheck").parent().hasClass("btn-dark"));
				
			// });
		},
		bindMainQr: function(){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": ""
						 };
			var page = "Qr";
			pjQ.$('#pjFdAccordion_' + index).on('shown.bs.collapse', function (e) {
				
				var category_id = pjQ.$('#' + e.target.id).data('id');
				
				
				if(pjQ.$("#heading" + category_id).length > 0)
				{
					self.getProducts.call(self,category_id,page);
				} 
			});
			pjQ.$('.pjFdProductsType').each(function(e){
				if(pjQ.$(this).attr('aria-expanded') == 'true')
				{
					var category_id = pjQ.$(this).attr('data-cid');
					self.getProducts.call(self,category_id,page);
				} 
			});
			pjQ.$('.clickCategory').each(function(e){
				// console.log(self);
				if(pjQ.$(this).hasClass('active'))
				{
					//var category_id = pjQ.$(this).attr('data-cid');
					var category_id = pjQ.$(this).children("input").val();
					//console.log(category_id);
					self.getProducts.call(self,category_id,page);
				} 
			});
			pjQ.$('.clickCategory').on('click', function (e) {
				// console.log("comes");
				// // if(pjQ.$(this).hasClass('active'))
				// // {
				// 	//var category_id = pjQ.$(this).attr('data-cid');
				// 	var category_id = pjQ.$(this).children("input").val();
				// 	//console.log(category_id);
				// 	self.getProducts.call(self,category_id,page);
				// //} 

				/**************/
				// console.log("comes to category");
				if (pjQ.$("#search").css("display") == "block") {
					pjQ.$("#search").css("display","none");
					pjQ.$(".tab-content").removeClass("d-none");
					$("#searchInput").val('');
				    $('.fdSearchResults').html('');
				}
				// if(pjQ.$(this).hasClass('active'))
				// {
					//var category_id = pjQ.$(this).attr('data-cid');
					var category_id = pjQ.$(this).children("input").val();
					var hasImg = pjQ.$(this).children(".has_img").val();
					console.log(category_id);
					// console.log(hasImg);
					self.getProducts.call(self,category_id,page,hasImg);
				//} 
			});
		},
		loadMain: function () {
			// console.log(this.opts.mainCatID);
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "",
							"menu_category": this.opts.menu_category
						 };
			// console.log('test', this.opts);
			pjQ.$("#previewModal").modal("hide"); 
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionMain", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('.fdLoader').css('display', 'none');
				pjQ.$('.pjFdBtnHome').parent().addClass('active');
				self.bindMain();
				
			}).fail(function () {
				
			});
			//self.loadCart.call(self);
			
		},
		loadMainQr: function () {
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": ""
						 };
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionMainQr", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$("#fdMyOrderPrice").text(" "+(pjQ.$('#totalCartPrice').text()));
				pjQ.$('.fdLoader').css('display', 'none');
				pjQ.$('.pjFdBtnHome').parent().addClass('active');
				self.bindMainQr();
			}).fail(function () {
				
			});
		},
		loadHome: function () {
		
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": ""
						 };
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionHome", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('.fdLoader').css('display', 'none');
				self.bindMain();
			}).fail(function () {
				
			});
		},
		loadCategories: function (category_id) {
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": index,
							"category_id": category_id
						 };
			
			
			pjQ.$('.fdLoader').css('display', 'block');
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionCategories", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				pjQ.$('#fdMain_' + index).html(data);
				pjQ.$('.fdLoader').css('display', 'none');
			}).fail(function () {
				
			});
		},
		loadCart: function (type, callback) {
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": index, 
							"type": self.cart
						 }; 
			pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionCart", "&session_id=", self.opts.session_id, type].join(""), params).done(function (data) {
				pjQ.$('#fdCart_' + index).html(data);
				if(pjQ.$('#fdCart_' + self.opts.index).find('.fdEmptyCart').length > 0)
				{
					pjQ.$('.fdButtonPostPrice').addClass('fdButtonDisabled');
					pjQ.$('.fdButtonGetPreview').addClass('fdButtonDisabled');
					pjQ.$('.fdButtonConfirm').addClass('fdButtonDisabled');
				}
				// if(pjQ.$('#fdTypeForm_' + self.opts.index).length > 0)
				// {
				// 	if (pjQ.$(".fdDelivery").css("display") == "block") {
                //        var cartPrice =  pjQ.$('#fdCart_' + self.opts.index).find('#totalCartPrice').attr("data-price");
				//        var minPrice =  pjQ.$('#fdCart_' + self.opts.index).find('#fdMinPrice').attr("data-price");
				// 	   console.log("cart: "+cartPrice+" , min: "+minPrice);
				// 	   if(parseFloat(cartPrice) < parseFloat(minPrice)) {
				// 			$(".fdButtonRefDelivery").addClass('disabled');
				// 			$("#typesMinPriceErr").css('display', 'block');
				// 		} else {
				// 			$(".fdButtonRefDelivery").removeClass('disabled');
				// 			$("#typesMinPriceErr").css('display', 'none');
				// 		}
				// 	}
				// }
				// var cartPrice =  $('<div />').html(data).find('#totalCartPrice').attr("data-price");
				// var minPrice =  $('<div />').html(data).find('#fdMinPrice').attr("data-price");
				// pjQ.$("#fdMyOrderPrice").text(" "+(pjQ.$('#totalCartPrice').text()));
				// // var cartPrice = pjQ.$("#totalCartPrice").attr("data-price");
				// // var minPrice = pjQ.$("#fdMinPrice").attr("data-price");
				// //--------//
				// //var cartPrice = pjQ.$("#totalCartPrice").attr("data-price");
				// //var minPrice = pjQ.$("#fdMinPrice").attr("data-price");
				// // var cartPrice =  $('<div />').html(data).find('#totalCartPrice').attr("data-price");
				// // var minPrice =  $('<div />').html(data).find('#fdMinPrice').attr("data-price");
				// //----------//
				// // console.log(cartPrice+","+minPrice);
				// var $type =pjQ.$("#typeToggle").parent(".toggle").hasClass("btn-danger") ? "pickup" : "delivery";
				// //console.log("cart: "+cartPrice+" , min: "+minPrice);
				// if($type == 'delivery' && parseFloat(cartPrice) < parseFloat(minPrice)) {
				// 	console.log("less than min price");
                //    pjQ.$("#minPriceErr").css('display', 'block');
				//    pjQ.$("#btn-checkout").removeClass("fdButtonCheckout");
				//    pjQ.$("#btn-checkout").addClass("disabled");
				//    pjQ.$(".fdBtnOrderTotal").addClass("disabled");
				   
				// } else {
				// 	pjQ.$("#minPriceErr").css('display', 'none');
				// 	pjQ.$("#btn-checkout").addClass("fdButtonCheckout");
				// 	pjQ.$("#btn-checkout").removeClass("disabled");
				// 	pjQ.$(".fdBtnOrderTotal").removeClass("disabled");
				// }
				// if(pjQ.$('#fdCart_' + self.opts.index).find('.fdEmptyCart').length > 0)
				// {
				// 	pjQ.$('.fdButtonPostPrice').addClass('fdButtonDisabled');
				// 	pjQ.$('.fdButtonGetPreview').addClass('fdButtonDisabled');
				// 	pjQ.$('.fdButtonConfirm').addClass('fdButtonDisabled');
				// }
				// if (pjQ.$("#emptyCart").length > 0) {
				// 	pjQ.$("#btn-cart").css("display", "none");
				// } else {
				// 	pjQ.$("#btn-cart").css("display", "block");
				// 	pjQ.$("#fdMyOrderPrice").text(" "+(pjQ.$('#totalCartPrice').text()));
				// }
				// if($type == 'delivery') {
				// 	pjQ.$("#postCodeSec").css('display', 'block')
				// } else {
				// 	pjQ.$("#postCodeSec").css('display', 'none')
				// }
				// pjQ.$('.fdLoader').css('display', 'none');
				if (typeof callback != "undefined") {
					callback.call(self);
				}
			});
			
		},
		loadTypes: function(callback){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "total"
						 };
			
			if (self.type == "pickup" && self.location_id !== null) {
				self.setDays.apply(self, [self.location_id, "pickup"]);
			}
			if (self.type == "delivery" && self.location_id !== null) {
				self.setDays.apply(self, [self.location_id, "delivery"]);
			}
		
			pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionTypes", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('.pjFdBtnCart').css('display', 'none');
				
				if(pjQ.$('#fdTypeForm_' + self.opts.index).length > 0)
				{
					self.cart = 'total';
					if (typeof window.initializeFD == "undefined") 
					{
						window.initializeFD = function () 
						{
							var id = pjQ.$("option:selected", pjQ.$('#fdMain_' + index).find("select[name='p_location_id']")).val();
							if (typeof id != "undefined" && parseInt(id, 10) > 0) {
								self.getPickupLocations.apply(self, [id, true]);
							}else{
								self.getPickupLocations.apply(self, [null, true]);
							}
							self.getLocations.call(self);
						};
						pjQ.$.getScript("//maps.googleapis.com/maps/api/js?"+self.opts.googleAPIKey+"&libraries=drawing&callback=initializeFD");
					} else {
						var id = pjQ.$("option:selected", pjQ.$('#fdMain_' + index).find("select[name='p_location_id']")).val();
						if (typeof id != "undefined" && parseInt(id, 10) > 0) {
							self.getPickupLocations.apply(self, [id, true]);
						}else{
							self.getPickupLocations.apply(self, [null, true]);
						}
						self.getLocations.call(self);
					}
					
					self.pickupDate = new Calendar({
						element: "fd_p_date_" + self.opts.index,
						dateFormat: self.opts.dateFormat,
						monthNamesFull: self.opts.monthNamesFull,
						dayNames: self.opts.dayNames,
						startDay: self.opts.startDay,
						disablePast: true,
						onBeforeShowDay: function (date) 
						{
							return self._daysOff.apply(self, [date]);
						},
						onSelect: function (element, selectedDate, date, cell){
							if(pjQ.$("select[name='p_location_id']").val() != '')
							{
								pjQ.$('.fdLoader').css('display', 'block');
								self.getWTime.apply(self, [selectedDate, pjQ.$("option:selected", pjQ.$("select[name='p_location_id']")).val(), 'pickup', function (data) {
									pjQ.$(".fdPickupTime").html(data);
								}]);
							}
						}
					});
					self.deliveryDate = new Calendar({
						element: "fd_d_date_" + self.opts.index,
						dateFormat: self.opts.dateFormat,
						monthNamesFull: self.opts.monthNamesFull,
						dayNames: self.opts.dayNames,
						startDay: self.opts.startDay,
						disablePast: true,
						onBeforeShowDay: function (date) {
							return self._daysOff.apply(self, [date]);
						},
						onSelect: function (element, selectedDate, date, cell) {
							pjQ.$('.fdLoader').css('display', 'block');
							self.getWTime.apply(self, [selectedDate, pjQ.$("select[name='d_location_id']").val(), 'delivery', function (data) {
								pjQ.$(".fdDeliveryTime").html(data);
							}]);
						}
					});
				}
			});
			//pjQ.$("#postCodeSec").css('display', 'none');
		},
		loadLogin: function(callback){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "plain"
						 };	
			//this.renderButton();
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionLogin", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				//console.log(data);
				if (data.code != undefined && data.status == 'OK') {
					if (!hashBang("#!/loadProfile")) 
					{
						self.loadProfile.call(self);
					}
				}else{
					self.$container.html(data);
					pjQ.$('.pjFdBtnAcc').parent().addClass('active');
					pjQ.$('.fdLoader').css('display', 'none');
					pjQ.$('.pjFdBtnCart').css('display', 'none');
					pjQ.$('html, body').animate({
				        scrollTop: self.$container.offset().top
				    }, 500);
					console.log("let's check");
					if(pjQ.$("#navbar").hasClass("navbar-mobile")) {
						pjQ.$("#navbar").removeClass("navbar-mobile");
						pjQ.$(".mobile-nav-toggle").removeClass("bi-x");
						pjQ.$(".mobile-nav-toggle").addClass("bi-list");
					}
				}
			}).fail(function () {
				
			});
		},
		loadProfile: function(callback){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "plain"
						 };
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionProfile", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					if (!hashBang("#!/loadLogin")) 
					{
						self.loadLogin.call(self);
					}
				}else{
					self.$container.html(data);
					pjQ.$('.pjFdBtnAcc').parent().addClass('active');
					pjQ.$('.pjFdBtnCart').css('display', 'none');
					pjQ.$('.fdLoader').css('display', 'none');
					self.bindProfile.call(self);
				}
			}).fail(function () {
				
			});
		},
		loadReview: function(callback){
			
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "plain"
						 };
						 //console.log(this.opts.folder);
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontReview&action=pjActionCreate", "&session_id=", self.opts.session_id, "&star=", self.review[0], "&via=", self.review[1], "&p_id=", self.review[2], "&page=", self.review[3]].join(""), params).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					if (!hashBang("#!/loadLogin")) 
					{
						self.loadLogin.call(self);
					}
				}else{
					//console.log(self.$container);
					self.$container.html(data);
					// pjQ.$('.pjFdBtnAcc').parent().addClass('active');
					// pjQ.$('.pjFdBtnCart').css('display', 'none');
					pjQ.$('.fdLoader').css('display', 'none');
					// self.bindReview.call(self);
				}
			}).fail(function () {
				
			});
		},
		loadRating: function(callback){
			//console.log("comes 2");
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "plain"
						 };
						
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionProductRatings", "&session_id=", self.opts.session_id, "&product_id=", self.review[2]].join(""), params).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					if (!hashBang("#!/loadLogin")) 
					{
						self.loadLogin.call(self);
					}
				}else{
					self.$container.html(data);
					// pjQ.$('.pjFdBtnAcc').parent().addClass('active');
					// pjQ.$('.pjFdBtnCart').css('display', 'none');
					// pjQ.$('.fdLoader').css('display', 'none');
					// self.bindReview.call(self);
				}
			}).fail(function () {
				
			});
		},
		
		bindProfile: function(){
			var self = this,
				index = this.opts.index;
		
			if (validate) 
			{				
				var $form = pjQ.$('#fdProfileForm_'+ self.opts.index);
				$form.validate({
					
					onkeyup: false,
					errorElement: 'li',
					errorPlacement: function (error, element) {
						error.appendTo(element.next().find('ul'));
					},
		            highlight: function(ele, errorClass, validClass) {
		            	var element = pjQ.$(ele);
		            	element.parent().addClass('has-error');
		            },
		            unhighlight: function(ele, errorClass, validClass) {
		            	var element = pjQ.$(ele);
		            	element.parent().removeClass('has-error').addClass('has-success');
		            },
					submitHandler: function (form) {
						self.disableButtons.call(self);
						
						var $form = pjQ.$(form);
						var session_id = '';
						if(self.opts.session_id != '')
						{
							session_id += '&session_id=' + self.opts.session_id;
						}
						pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionUpdateProfile"].join("") + session_id, $form.serialize()).done(function (data) {
							if (data.status == "OK") {
								var $profileMsg = pjQ.$('#fdProfileMessage_' + self.opts.index);
								$profileMsg.html(data.text).parent().show().delay(3000).queue(function(e){
									$profileMsg.html("").parent().hide();
								});
								self.enableButtons.call(self);
							}else{
								if (!hashBang("#!/loadMain")) 
								{
									self.loadMain.call(self);
								}
							}
						}).fail(function () {
							self.enableButtons.call(self);
						});
						return false;
					}
				});
			}
		},
		bindReview: function(){
			var self = this,
				index = this.opts.index;
		    
			// if (validate) 
			// {				
			// 	var $form = pjQ.$('#fdProfileForm_'+ self.opts.index);
			// 	$form.validate({
					
			// 		onkeyup: false,
			// 		errorElement: 'li',
			// 		errorPlacement: function (error, element) {
			// 			error.appendTo(element.next().find('ul'));
			// 		},
		    //         highlight: function(ele, errorClass, validClass) {
		    //         	var element = pjQ.$(ele);
		    //         	element.parent().addClass('has-error');
		    //         },
		    //         unhighlight: function(ele, errorClass, validClass) {
		    //         	var element = pjQ.$(ele);
		    //         	element.parent().removeClass('has-error').addClass('has-success');
		    //         },
			// 		submitHandler: function (form) {
			// 			self.disableButtons.call(self);
						
			// 			var $form = pjQ.$(form);
			// 			var session_id = '';
			// 			if(self.opts.session_id != '')
			// 			{
			// 				session_id += '&session_id=' + self.opts.session_id;
			// 			}
			// 			pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionUpdateProfile"].join("") + session_id, $form.serialize()).done(function (data) {
			// 				if (data.status == "OK") {
			// 					var $profileMsg = pjQ.$('#fdProfileMessage_' + self.opts.index);
			// 					$profileMsg.html(data.text).parent().show().delay(3000).queue(function(e){
			// 						$profileMsg.html("").parent().hide();
			// 					});
			// 					self.enableButtons.call(self);
			// 				}else{
			// 					if (!hashBang("#!/loadMain")) 
			// 					{
			// 						self.loadMain.call(self);
			// 					}
			// 				}
			// 			}).fail(function () {
			// 				self.enableButtons.call(self);
			// 			});
			// 			return false;
			// 		}
			// 	});
			// }
		},
		loadForgot: function(callback){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "plain"
						 };
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionForgot", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('.fdLoader').css('display', 'none');
			}).fail(function () {
				
			});
		},
		loadVouchers: function(callback){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "total"
						 };
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionVouchers&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('.pjFdBtnCart').css('display', 'none');
				pjQ.$('.fdLoader').css('display', 'none');
			}).fail(function () {
				
			});
		},
		loadCheckout: function(callback){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "total"
						 };
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionCheckout&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('.pjFdBtnCart').css('display', 'none');
				self.bindCheckout.call(self);
				if (pjQ.$('.input-group.date').length) {
					pjQ.$('.input-group.date').datepicker({
						autoclose: true,
						format: "mm/yy",
						minViewMode: 1,
						startDate: new Date(),
						endDate:'+10y'
					});		
				};
				pjQ.$('.modal-dialog').css("z-index", "9999"); 
				pjQ.$('.fdLoader').css('display', 'none');
			}).fail(function () {
				
			});
		},
		loadOtpPage: function(callback) {
            var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "total"
						 };
			//console.log("hey you came here");
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionSendOtp&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				// if (data.code != undefined && data.status == 'ERR') {
				// 	if (!hashBang("#!/loadCheckout")) 
				// 	{
				// 		self.loadCheckout.call(self);
				// 	}
				// }else{
					//console.log(data);
					self.$container.html(data);
					pjQ.$('.pjFdBtnCart').css('display', 'none');
					pjQ.$('.fdLoader').css('display', 'none');
					//self.bindOtpPage.call(self);
				// }
			}).fail(function () {
				
			});
		},
		loadPreview: function(callback){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "total"
						 };
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionPreview&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					if (!hashBang("#!/loadCheckout")) 
					{
						self.loadCheckout.call(self);
					}
				}else{
					$('#previewModal').modal({backdrop:'static', keyboard:false})
					$("#previewModal .modal-body").html(data);
					$("#previewModal").modal("show");
					//self.$container.html(data);
					pjQ.$('.pjFdBtnCart').css('display', 'none');
					pjQ.$('.fdLoader').css('display', 'none');
				}
			}).fail(function () {
				
			});
		},
		loadMyOrders: function(callback){
			var self = this,
				index = this.opts.index,
				params = { "locale": this.opts.locale,
							"hide": this.opts.hide,
							"index": this.opts.index,
							"type": "plain"
						 };
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionMyOrders", "&session_id=", self.opts.session_id].join(""), params).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					if (!hashBang("#!/loadLogin")) 
					{
						self.loadLogin.call(self);
					}
				}else{
					self.$container.html(data);
					pjQ.$('.pjFdBtnAcc').parent().addClass('active');
					pjQ.$('.pjFdBtnCart').css('display', 'none');
					pjQ.$('.fdLoader').css('display', 'none');
					// self.bindProfile.call(self);
				}
			}).fail(function () {
				
			});
		},
		removeItem: function (hash, extra_id) {
			var self = this;
			pjQ.$('.fdLoader').css('display', 'block');
			pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontCart&action=pjActionRemove", "&session_id=", self.opts.session_id].join(""), {
				"hash": hash,
				"extra_id": extra_id
			}).done(function (data) {
				self.loadCart.apply(self, ['', function(){
					pjQ.$('.fdLoader').css('display', 'none');
				}]);
				//self.loadCart.call(self);
			});
			
		},
		getPickupLocations: function (id, init, callback) {
			var self = this,
				LatLngList = [];
			pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionGetPickupLocations", "&session_id=", self.opts.session_id].join(""), {
				"id": id
			}).done(function (data) {
				pjQ.$("#fdTypeMap_" + self.opts.index).show();
								
				if (myPickupMarkers) {
					for (i in myPickupMarkers) {
						if (myPickupMarkers.hasOwnProperty(i)) {
							myPickupMarkers[i].setMap(null);
						}
					}
					myPickupMarkers.length = 0;
				}
				
				if (data.length > 0) 
				{
					if(data.length == 1)
					{
						var selectedDate = pjQ.$('#fd_p_date_' + self.opts.index).val();
						self.getWTime.apply(self, [selectedDate, pjQ.$("option:selected", pjQ.$("select[name='p_location_id']")).val(), 'pickup', function (data) {
							pjQ.$(".fdPickupTime").html(data);
						}]);
					}
					var selected_index = null;
					
					myPickupMap = new GoogleMaps({
						id: "fdTypeMap_" + self.opts.index,
						icon: self.opts.server + "app/web/img/frontend/pin.png"
					});
					myPickupOverlays = [];
					myPickupMarkers = [];
					myPickupBounds = new google.maps.LatLngBounds();
					for (var index = 0, ilen = data.length; index < ilen; index++) 
					{
						if (!('lat' in data[index]) 
							|| !('lng' in data[index])
							|| data[index].lat === null 
							|| data[index].lng === null) {
							continue;
						}
						var fdPickupLatlng = new google.maps.LatLng(data[index].lat, data[index].lng),
							location_id = data[index].id;
						var marker = new google.maps.Marker({
							map: myPickupMap.map,
							position: fdPickupLatlng,
							icon: self.opts.server + "app/web/img/frontend/pin.png",
							title: data[index].name
						});
						marker.fdObj = {
							"id": data[index].id,
							"name": data[index].name,
							"address": data[index].address
						};
						myPickupMap.map.setCenter(marker.getPosition());
						LatLngList.push(fdPickupLatlng);
						
						if(id == data[index].id)
						{
							selected_index = index;
						}
						if (data[index].name != "") 
						{
							marker.infoWindow = new google.maps.InfoWindow({
								content: data[index].name + "<br/>" + data[index].address
							});
							google.maps.event.addListener(marker, "click", function() {
								for (var i = myPickupMarkers.length - 1; i >= 0; i--) 
								{
									myPickupMarkers[i].infoWindow.close();
								}
								this.infoWindow.open(myPickupMap.map, this);
								if(myPickupMarkers.length > 1)
								{
									self.setPickupLocaton.apply(self, [this.fdObj]);
								}
							});
						}
						myPickupMarkers.push(marker);
					}
					for (var i = myPickupMarkers.length - 1; i >= 0; i--) 
					{
						myPickupMarkers[i].setMap(myPickupMap.map);
					}
					if(selected_index != null)
					{
						google.maps.event.trigger(myPickupMarkers[selected_index], 'click');
					}
					for (var j = 0, len = LatLngList.length; j < len; j++) 
					{
						myPickupBounds.extend(LatLngList[j]);
					}
					if(LatLngList.length == 1)
					{
						myPickupMap.map.setZoom(9);
					}else{
						myPickupMap.map.fitBounds(myPickupBounds);
					}
				}
				if (typeof callback != "undefined") {
					callback.call(self);
				}else{
					pjQ.$('.fdLoader').css('display', 'none');
				}
			});
		},
		setPickupLocaton: function(fdObj)
		{
			var self = this;
			pjQ.$('.fdLoader').css('display', 'block');
			self.getWTime.apply(self, [pjQ.$("#fd_p_date_" + self.opts.index).val(), fdObj.id, 'pickup', function (data) {
				pjQ.$(".fdPickupTime").html(data);
				pjQ.$('#fdMain_' + self.opts.index).find("select[name='p_location_id']").val(fdObj.id);
				pjQ.$("#fdPickupAddressLabel_" + self.opts.index).html(fdObj.address);
				pjQ.$("#fdPickupAddress_" + self.opts.index).val(fdObj.address);
				pjQ.$("#fdPickupAddress_" + self.opts.index).parent().parent().show();
				
			}]);
		},
		getLocation: function (id, init, callback) {
			var self = this;
			
			pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionGetLocation", "&session_id=", self.opts.session_id].join(""), {
				"id": id
			}).done(function (data) {
				if (data && data.status && data.status === "OK") {
					pjQ.$("#fdPickupAddressLabel_" + self.opts.index).html(data.address);
					pjQ.$("#fdPickupAddress_" + self.opts.index).val(data.address);
					pjQ.$("#fdPickupAddress_" + self.opts.index).parent().parent().show();
					
					if (myPickupMarkers) 
					{
						var open_marker = null;
						for (var i = myPickupMarkers.length - 1; i >= 0; i--) 
						{
							myPickupMarkers[i].infoWindow.close();
							myPickupMarkers[i].setMap(null);
							if(myPickupMarkers[i].fdObj.id == id)
							{
								myPickupMarkers[i].setMap(myPickupMap.map);
								open_marker = myPickupMarkers[i];
							}
						}
						if(open_marker != null)
						{
							google.maps.event.trigger(open_marker, 'click');
						}
					}
					if (typeof callback != "undefined") {
						callback.call(self);
					}else{
						pjQ.$('.fdLoader').css('display', 'none');
					}
				} else {
					if (myPickupMarkers) {
						for (var i = myPickupMarkers.length - 1; i >= 0; i--) {
							myPickupMarkers[i].infoWindow.close();
							myPickupMarkers[i].setMap(null);
						}
					}
					pjQ.$('.fdLoader').css('display', 'none');
				}
			});
		},
		getLocations: function () {
			var self = this,
				LatLngList = [],
				latlng;
			
			pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionGetLocations", "&session_id=", self.opts.session_id].join("")).done(function (data) {
				pjQ.$("#fdDeliveryMap_" + self.opts.index).show();
				if (data.length > 0) 
				{
					if(data.length == 1)
					{
						var selectedDate = pjQ.$('#fd_d_date_' + self.opts.index).val();
						self.getWTime.apply(self, [selectedDate, pjQ.$("option:selected", pjQ.$("select[name='d_location_id']")).val(), 'delivery', function (data) {
							pjQ.$(".fdDeliveryTime").html(data);
						}]);
					}
					myDeliveryMap = new GoogleMaps({
						id: "fdDeliveryMap_" + self.opts.index,
						icon: self.opts.server + "app/web/img/frontend/pin.png"
					});
					myDeliveryOverlays = [];
					myDeliveryMarkers = [];
					myDeliveryBounds = new google.maps.LatLngBounds();
					myDeliveryMap.clearOverlays(myDeliveryMarkers);
					for (var index = 0, ilen = data.length; index < ilen; index++) 
					{
						if (typeof data[index].lat != "undefined" && typeof data[index].lng != "undefined" && data[index].lat !== null && data[index].lng !== null) 
						{
							myDeliveryMap.addMarker(myDeliveryMarkers, new google.maps.LatLng(data[index].lat, data[index].lng));
							latlng = new google.maps.LatLng(data[index].lat, data[index].lng);
							LatLngList.push(latlng);
						}
						
						if (data[index].coords && data[index].coords.length > 0) 
						{
							for (var j = 0, jlen = data[index].coords.length; j < jlen; j++) 
							{
								myTmp.cnt += 1;
								switch (data[index].coords[j].type) {
									case 'circle':
										var str = data[index].coords[j].data.replace(/\(|\)|\s+/g, ""),
											arr = str.split("|"),
											center = new google.maps.LatLng(arr[0].split(",")[0], arr[0].split(",")[1]);
										
										var circle = new google.maps.Circle({
											strokeColor: '#008000',
											strokeOpacity: 1,
											strokeWeight: 1,
											fillColor: '#008000',
											fillOpacity: 0.5,
											center: center,								
								            radius: parseFloat(arr[1]),
								            zIndex: 901
										});
										circle.myObj = {
											"id": data[index].coords[j].id,
											"location_id": data[index].coords[j].location_id,
											"name": data[index].name
										};
										circle.setMap(myDeliveryMap.map);
										google.maps.event.addListener(circle, "click", function () {
											myDeliveryMap.removeFocus(myDeliveryOverlays, this.myObj.id);
											myDeliveryMap.setFocus(this);
											self.coord_id = this.myObj.id;
											self.location_id = this.myObj.location_id;
											self.setDeliveryArea.call(self, this.myObj);
										});
										myDeliveryOverlays.push(circle);
										myTmp.type = "circle";
										break;
									case 'polygon':
										var path,
											str = data[index].coords[j].data.replace(/\(|\s+/g, ""),
											arr = str.split("),"),
											paths = [];
										arr[arr.length-1] = arr[arr.length-1].replace(")", "");
										for (var i = 0, len = arr.length; i < len; i++) {
											path = new google.maps.LatLng(arr[i].split(",")[0], arr[i].split(",")[1]);
											paths.push(path);
										}
										var polygon = new google.maps.Polygon({
											paths: paths,
											strokeColor: '#008000',
											strokeOpacity: 1,
											strokeWeight: 1,
											fillColor: '#008000',
											fillOpacity: 0.5,
											zIndex: 901
									    });
										polygon.myObj = {
											"id": data[index].coords[j].id,
											"location_id": data[index].coords[j].location_id,
											"name": data[index].name
										};
										polygon.setMap(myDeliveryMap.map);
										google.maps.event.addListener(polygon, "click", function () {
											myDeliveryMap.removeFocus(myDeliveryOverlays, this.myObj.id);
											myDeliveryMap.setFocus(this);
											self.coord_id = this.myObj.id;
											self.location_id = this.myObj.location_id;
											self.setDeliveryArea.call(self, this.myObj);
										});
										myDeliveryOverlays.push(polygon);
										myTmp.type = "polygon";
										break;
									case 'rectangle':
										var bound,
											str = data[index].coords[j].data.replace(/\(|\s+/g, ""),
											arr = str.split("),"), 
											bounds = [];
										for (var i = 0, len = arr.length; i < len; i++) {
											arr[i] = arr[i].replace(/\)/g, "");
											bound = new google.maps.LatLng(arr[i].split(",")[0], arr[i].split(",")[1]);
											bounds.push(bound);
										}
										var rectangle = new google.maps.Rectangle({
											strokeColor: '#008000',
								            strokeOpacity: 1,
								            strokeWeight: 1,
								            fillColor: '#008000',
								            fillOpacity: 0.5,
								            bounds: new google.maps.LatLngBounds(bounds[0], bounds[1]),
								            zIndex: 901
										});
										rectangle.myObj = {
											"id": data[index].coords[j].id,
											"location_id": data[index].coords[j].location_id,
											"name": data[index].name
										};
										rectangle.setMap(myDeliveryMap.map);
										google.maps.event.addListener(rectangle, "click", function () {
											myDeliveryMap.removeFocus(myDeliveryOverlays, this.myObj.id);
											myDeliveryMap.setFocus(this);
											self.coord_id = this.myObj.id;
											self.location_id = this.myObj.location_id;
											self.setDeliveryArea.call(self, this.myObj);
										});
										myDeliveryOverlays.push(rectangle);
										myTmp.type = "rectangle";
										break;
								}
							}
						}
					}
					
					if (self.coord_id !== null) 
					{
						for (var i = 0, len = myDeliveryOverlays.length; i < len; i++) {
							if (myDeliveryOverlays[i].myObj.id == self.coord_id) {
								myDeliveryMap.setFocus(myDeliveryOverlays[i]);
								self.setDeliveryArea.call(self, {
									name: myDeliveryOverlays[i].myObj.name,
									location_id: self.location_id
								});
								break;
							}
						}
					}
					
					for (var j = 0, len = LatLngList.length; j < len; j++) {
						myDeliveryBounds.extend(LatLngList[j]);
					}
					if(LatLngList.length > 1)
					{
						myDeliveryMap.map.fitBounds(myDeliveryBounds);
					}else{
						myDeliveryMap.map.setZoom(9);
					}
					
					google.maps.event.trigger(myDeliveryMap.map, "resize");
					
					self.myDeliveryOverlays = myDeliveryOverlays;
					self.myDeliveryMap = myDeliveryMap;
					
					pjQ.$('.fdLoader').css('display', 'none');
				}
			});
		},
		setDeliveryArea: function (data) {
			var self = this,
				d_location_id = pjQ.$("select[name='d_location_id']").val();
			
			pjQ.$(".fdDeliveryNote").html(self.opts.messages[11].replace('{LOCATION}', data.name));
			pjQ.$(".fdDeliveryNote").parent().show();
			if(d_location_id != data.location_id)
			{
				pjQ.$("select[name='d_location_id']").val(data.location_id);
				
				self.setDays.apply(this, [data.location_id, 'delivery']);
				self.deliveryDate.opts.onBeforeShowDay = function (date) {
					return self._daysOff.apply(self, [date]);
				};
				
				pjQ.$('.fdLoader').css('display', 'block');
				self.getWTime.apply(self, [pjQ.$("#fd_d_date_" + self.opts.index).val(), data.location_id, 'delivery', function (data) {
					pjQ.$(".fdDeliveryTime").html(data);
				}]);
			}
		},
		getWTime: function (date, location_id, type, callback) {
			var self = this;
			pjQ.$.get([self.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionGetWTime", "&session_id=", self.opts.session_id].join(""), {
				"date": date,
				"location_id": location_id,
				"type": type,
				"index": this.opts.index
			}).done(function (data) {
				var selector = type === 'pickup' ? '.fdButtonPostPrice.fdButtonRefPickup' : '.fdButtonPostPrice.fdButtonRefDelivery';
				if (data.indexOf("fdSelect") == -1)
				{
					pjQ.$(selector).addClass("fdButtonDisabled");
				}else{
					if(pjQ.$('#fdCart_' + self.opts.index).find('.fdEmptyCart').length == 0)
					{
						pjQ.$(selector).removeClass("fdButtonDisabled");
					}
				}
				if(date == '')
				{
					pjQ.$(selector).removeClass("fdButtonDisabled");
				}
				if(location_id == '')
				{
					if(type == 'pickup')
					{
						pjQ.$('#fdPickupAddressLabel_' + self.opts.index).parent().parent().hide();
						pjQ.$('#fdPickupAddress_' + self.opts.index).val("");
						pjQ.$('#fdPickupDateTime_' + self.opts.index).hide();
					}
					if(type == 'delivery')
					{
						pjQ.$('.fdDeliveryNote').html("").parent().hide();
						pjQ.$('#fdDeliveryDateTime_' + self.opts.index).hide();
					}
					pjQ.$(selector).removeClass("fdButtonDisabled");
				}else{
					if(type == 'pickup')
					{
						pjQ.$('#fdPickupDateTime_' + self.opts.index).show();
					}
					if(type == 'delivery')
					{
						pjQ.$('#fdDeliveryDateTime_' + self.opts.index).show();
					}
				}
				callback(data);
				pjQ.$('.fdLoader').css('display', 'none');
			});
		},
		bindCheckout: function() {
			var self = this,
				$form = pjQ.$('#fdCheckoutForm_' + self.opts.index);
			var remote_msg = self.opts.email_exiting_message;
			remote_msg = remote_msg.replace("{STAG}", '<a href="#" class="fdValidateLogin">');
			remote_msg = remote_msg.replace("{ETAG}", '</a>');
			
			var index = this.opts.index;
			//var $reCaptcha = self.$container.find('#g-recaptcha_' + index);
			// if ($reCaptcha.length > 0)
            // {
            //     grecaptcha.render($reCaptcha.attr('id'), {
            //         sitekey: $reCaptcha.data('sitekey'),
            //         callback: function(response) {
            //             var elem = pjQ.$("input[name='recaptcha']");
            //             elem.val(response);
            //             elem.valid();
            //         }
            //     });
            // }
			
			$form.validate({
				rules: {
					// "captcha": {
					// 	remote: self.opts.folder + "index.php?controller=pjFrontEnd&action=pjActionCheckCaptcha&session_id=" + self.opts.session_id
					// },
					// "recaptcha": {
                    //     remote: self.opts.folder + "index.php?controller=pjFrontEnd&action=pjActionCheckReCaptcha&session_id=" + self.opts.session_id
                    // },
					"c_email": {
						remote: self.opts.folder + "index.php?controller=pjFrontEnd&action=pjActionCheckEmail&session_id=" + self.opts.session_id
					},
					"c_phone": {
						required: true,
						minlength: 11,
						maxlength: 11,
            digits: true
					},
					"agreement": {
						required: true
					}
				},
				messages: {
					"c_title": {
						required: $form.find("select[name='c_title']").data("err")
					},
					"c_name": {
						required: $form.find("input[name='c_name']").data("err")
					},
					"c_phone": {
						required: $form.find("input[name='c_phone']").data("err"),
						minlength: "This field must contain {0} digits",
						maxlength: "This field must contain {0} digits",
                        digits: "This field can only contain numbers"
						// phone: $form.find("input[name='c_phone']").attr("data-phone"),
						// remote: remote_msg
					},
					"c_email": {
						required: $form.find("input[name='c_email']").data("err"),
						email: $form.find("input[name='c_email']").attr("data-email"),
						remote: remote_msg
					},
					"c_company": {
						required: $form.find("input[name='c_company']").data("err")
					},
					"c_notes": {
						required: $form.find("textarea[name='c_notes']").data("err")
					},
					"c_address_1": {
						required: $form.find("input[name='c_address_1']").data("err")
					},
					"c_address_2": {
						required: $form.find("input[name='c_address_2']").data("err")
					},
					"c_city": {
						required: $form.find("input[name='c_city']").data("err")
					},
					"c_state": {
						required: $form.find("input[name='c_state']").data("err")
					},
					"c_zip": {
						required: $form.find("input[name='c_zip']").data("err")
					},
					"c_country": {
						required: $form.find("select[name='c_country']").data("err")
					},
					"payment_method": {
						required: $form.find("select[name='payment_method']").data("err")
					},
					"cc_type": {
						required: $form.find("select[name='cc_type']").data("err")
					},
					"cc_num": {
						required: $form.find("input[name='cc_num']").data("err")
					},
					"cc_exp_month": {
						required: $form.find("select[name='cc_exp_month']").data("err")
					},
					"cc_exp_year": {
						required: $form.find("select[name='cc_exp_year']").data("err")
					},
					"cc_code": {
						required: $form.find("input[name='cc_code']").data("err")
					},
					// "captcha": {
					// 	required: $form.find("input[name='captcha']").data("err"),
					// 	remote: $form.find("input[name='captcha']").attr("data-incorrect"),
					// },
					// "recaptcha": {
					// 	required: $form.find("input[name='recaptcha']").data("err"),
					// 	remote: $form.find("input[name='recaptcha']").attr("data-incorrect"),
					// },
					"agreement": {
						required: $form.find("input[name='agreement']").data("err")
					}
				},
				ignore: ".ignore",
				onkeyup: false,
				errorElement: 'li',
				errorPlacement: function (error, element) {
					console.log(element);
					if(element.attr('name') == 'captcha' || element.attr('name') == 'agreement')
					{
						console.log(element);
						element.parent().parent().addClass('required-error');
						//element.parent().parent().parent().parent().addClass('has-error');
						error.appendTo(element.parent().parent().next().find('ul'));
					} else {
						element.parent().parent().addClass('has-error');
						error.appendTo(element.next().find('ul'));
					}
				},
				highlight: function(ele, errorClass, validClass) {
      		var element = pjQ.$(ele);
        	if(element.attr('name') == 'agreement' || element.attr('name') == 'captcha')
					{
        		element.parent().parent().parent().parent().removeClass('has-success').addClass('has-error');
						$('html,body').animate({
							scrollTop: '0'
						}, 500);
					} else {
						element.parent().addClass('has-error');
						$('html,body').animate({
							scrollTop: '0'
						}, 500);
					}
					
          },
          unhighlight: function(ele, errorClass, validClass) {
        	var element = pjQ.$(ele);
        	// if(element.attr('name') == 'agreement' || element.attr('name') == 'captcha')
					// {
        	// 	element.parent().parent().parent().parent().removeClass('has-error').addClass('has-success');
					// } else {
					// 	element.parent().removeClass('has-error').addClass('has-success');
					// }
	            },
				submitHandler: function(form){
					pjQ.$('.fdLoader').css('display', 'block');
					console.log("saving form");
					pjQ.$.post([self.opts.folder, "index.php?controller=pjFrontEnd&action=pjActionSaveForm", "&session_id=", self.opts.session_id].join(""), $form.serialize()).done(function (data) {
					    console.log(data);
						if(data.code == '200' && data.user == "new")
						{
							hashBang("#!/loadOtpPage");
							
						} else if (data.code == '200' && data.user == "old") {
                            
							self.loadPreview.call(self);
            	// hashBang("#!/loadPreview");
						
						} else {
							console.log('Came here');
							pjQ.$('.fdLoader').css('display', 'none');
							pjQ.$('#pjFdWrongCaptchaModal').modal('show');
						}
					});
					return false;
			    }
			});
		},
		validatePhoneNumber: function($this){
			// var ph = $this.val();
			// ph = $.trim(ph);
			// var len = ph.toString().length;
			// if (len == 11 && isNaN(ph) == false) {
			// 	console.log("valid");
			//     //$this.attr("data-wt","valid");
			// 	$this.parent().removeClass("has-error");
			// 	$("#phoneErr").addClass("d-none");
			// } else {
			// 	console.log("invalid");
			//     //$this.attr("data-wt","invalid");
			// 	$this.parent().addClass("has-error");
			// 	$("#phoneErr").removeClass("d-none");
			// }
		},
		bindOtpPage: function() {
      var self = this,
			$form = pjQ.$('#fdOtpForm_' + self.opts.index);
			var valid = true;
			var $d_1 = pjQ.$("#digit-1").val();
			var $d_2 = pjQ.$("#digit-2").val();
			var $d_3 = pjQ.$("#digit-3").val();
			var $d_4 = pjQ.$("#digit-4").val();
			var $d_5 = pjQ.$("#digit-5").val();
			var $d_6 = pjQ.$("#digit-6").val();

			if ($d_1 == '' || $d_2 == '' || $d_3 == '' || $d_4 == '' || $d_5 == '' || $d_6 == '') {
				$("#otpErr").css("display","block");
				valid = false;
			}
			return valid;
		},
		getPaymentForm: function(obj){
			var self = this,
				index = this.opts.index;
			var qs = {
					"cid": this.opts.cid,
					"locale": this.opts.locale,
					"hide": this.opts.hide,
					"index": this.opts.index,
					"order_id": obj.order_id, 
					"payment_method": obj.payment
				};
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFrontPublic&action=pjActionGetPaymentForm", "&session_id=", self.opts.session_id].join(""), qs).done(function (data) {
				var $msg_container = pjQ.$('#fdOrderMessage_' + index);
				$msg_container.html(data);
				$msg_container.parent().parent().css('display', 'flex');
				var $payment_form = self.$container.find("form[name='pjOnlinePaymentForm']").first();
				if ($payment_form.length > 0) {
					$payment_form.trigger('submit');
				}
			}).fail(function () {
				log("Deferred is rejected");
			});
		},
		validateLogin: function () {
			var validator,
				self = this,
				$form = pjQ.$('#fdLoginForm_' + self.opts.index);
			validator = $form.validate({
				ignore: "",
				errorElement: 'li',
				errorPlacement: function (error, element) {
					element.parent().parent().addClass('has-error');
					error.appendTo(element.next().find('ul'));
				},
				success: function(li, element) {
					li.parent().parent().parent().parent().removeClass('has-error').addClass('has-success');
	            }
			});
			var $_email = $form.find("input[name='login_email']"),
				$_password = $form.find("input[name='login_password']");
			if ($_email.length > 0) {
				$_email.rules("add", {
					required: true,
					email: true,
					messages: {
						required: $_email.attr("data-required"),
						email: $_email.attr("data-email")
					}
				});
			}
			if ($_password.length > 0) {
				$_password.rules("add", {
					required: true,
					messages: {
						required: $_password.attr("data-required")
					}
				});
			}
			if (!validator.form()) {
				validator.showErrors();
				return false;
			}
			return true;
		},
		validateOtp: function () {
			
			var self = this,
				$form = pjQ.$('#fdOtpForm_' + self.opts.index);
			var valid = true;
			var $d_1 = pjQ.$("#digit-1").val();
			var $d_2 = pjQ.$("#digit-2").val();
			var $d_3 = pjQ.$("#digit-3").val();
			var $d_4 = pjQ.$("#digit-4").val();
			var $d_5 = pjQ.$("#digit-5").val();
			var $d_6 = pjQ.$("#digit-6").val();

			if ($d_1 == '' || $d_2 == '' || $d_3 == '' || $d_4 == '' || $d_5 == '' || $d_6 == '') {
				$("#otpErr").css("display","block");
				valid = false;
			}
			return valid;
		},
		validateForgot: function () {
			var validator,
				self = this,
				$form = pjQ.$('#fdForgotForm_' + self.opts.index);
			validator = $form.validate({
				ignore: "",
				errorElement: 'li',
				errorPlacement: function (error, element) {
					element.parent().parent().addClass('has-error');
					error.appendTo(element.next().find('ul'));
				},
				success: function(li, element) {
					li.parent().parent().parent().parent().removeClass('has-error').addClass('has-success');
	            }
			});
			var $_email = $form.find("input[name='email']");
			if ($_email.length > 0) {
				$_email.rules("add", {
					required: true,
					email: true,
					messages: {
						required: $_email.attr("data-required"),
						email: $_email.attr("data-email")
					}
				});
			}
			if (!validator.form()) {
				validator.showErrors();
				return false;
			}
			return true;
		},
		validateTypes: function () {
			var validator,
				self = this,
				$form = pjQ.$('#fdMain_' + self.opts.index).find("form");

			validator = $form.validate({
				
				errorElement: 'li',
				errorPlacement: function (error, element) {
					element.parent().parent().addClass('has-error');
					if(element.attr('name') == 'p_date' || element.attr('name') == 'd_date')
					{
						error.appendTo(element.parent().next().find('ul'));
	
					}else{
						error.appendTo(element.next().find('ul'));
						
					}
					
				},
				success: function(li, element) {
					if(li.parent().parent().parent().hasClass('has-error'))
					{
						li.parent().parent().parent().removeClass('has-error').addClass('has-success');
					}
					if(li.parent().parent().parent().parent().hasClass('has-error'))
					{
						li.parent().parent().parent().parent().removeClass('has-error').addClass('has-success');
					}
	            }
			});
			
			
			var $_ploc = $form.find("select[name='p_location_id']"),
				$_pdat = $form.find("input[name='p_date']"),
				$_pnotes = $form.find("textarea[name='p_notes']"),
				
				$_dloc = $form.find("select[name='d_location_id']"),
				$_ddat = $form.find("input[name='d_date']"),
				
				$_dpostcode = $form.find("input[name='post_code']"),
				$_dadd1 = $form.find("input[name='d_address_1']"),
				$_dadd2 = $form.find("input[name='d_address_2']"),
				$_dcity = $form.find("input[name='d_city']"),
				$_dstate = $form.find("input[name='d_state']"),
				$_dcountry = $form.find("select[name='d_country_id']"),
				$_dzip = $form.find("input[name='d_zip']"),
				$_dnotes = $form.find("textarea[name='d_notes']");
			switch (pjQ.$("input[name='type']:checked", $form).val()) {
				case "pickup":
					if ($_ploc.length > 0) {
						$_ploc.rules("add", {
							required: true,
							messages: {
								required: $_ploc.data("err")
							}
						});
					}
					if ($_pdat.length > 0) {
						$_pdat.rules("add", {
							required: true,
							messages: {
								required: $_pdat.data("err")
							}
						});
					}
					if ($_pnotes.length > 0 && $_pnotes.hasClass('fdRequired')) {
						$_pnotes.rules('add', {
							required: true,
							messages: {
								required: $_pnotes.data("err")
							}
						});
					}
					if ($_dloc.length > 0) {
						$_dloc.rules('remove', 'required');
					}
					if ($_ddat.length > 0) {
						$_ddat.rules('remove', 'required');
					}
					if ($_dpostcode.length > 0) {
						$_dpostcode.rules('remove', 'required');
					}
					if ($_dadd1.length > 0) {
						$_dadd1.rules('remove', 'required minlength');
					}
					if ($_dadd2.length > 0) {
						$_dadd2.rules('remove', 'required');
					}
					if ($_dcity.length > 0) {
						$_dcity.rules('remove', 'required');
					}
					if ($_dstate.length > 0) {
						$_dstate.rules('remove', 'required');
					}
					if ($_dcountry.length > 0) {
						$_dcountry.rules('remove', 'required');
					}
					if ($_dzip.length > 0) {
						$_dzip.rules('remove', 'required');
					}
					if ($_dnotes.length > 0) {
						$_dnotes.rules('remove', 'required');
					}
					break;
				case "delivery":
					if ($_ploc.length > 0) {
						$_ploc.rules("remove", "required");
					}
					if ($_pdat.length > 0) {
						$_pdat.rules("remove", "required");
					}
					if ($_dloc.length > 0) {
						$_dloc.rules('add', {
							required: true,
							messages: {
								required: $_dloc.data("err")
							}
						});
					}
					if ($_ddat.length > 0) {
						$_ddat.rules('add', {
							required: true,
							messages: {
								required: $_ddat.data("err")
							}
						});
					}
					if ($_dpostcode.length > 0 && $_dpostcode.hasClass('fdRequired')) {
						$_dpostcode.rules('add', {
							required: true,
							messages: {
								required: $_dpostcode.data("err")
							}
						});
					}
					if ($_dadd1.length > 0 && $_dadd1.hasClass('fdRequired')) {
						$_dadd1.rules('add', {
							required: true,
							messages: {
								required: $_dadd1.data("err")
							}
						});
					}
					if ($_dadd2.length > 0 && $_dadd2.hasClass('fdRequired')) {
						$_dadd2.rules('add', {
							required: true,
							messages: {
								required: $_dadd2.data("err")
							}
						});
					}
					if ($_dcity.length > 0 && $_dcity.hasClass('fdRequired')) {
						$_dcity.rules('add', {
							required: true,
							messages: {
								required: $_dcity.data("err")
							}
						});
					}
					if ($_dstate.length > 0 && $_dstate.hasClass('fdRequired')) {
						$_dstate.rules('add', {
							required: true,
							messages: {
								required: $_dstate.data("err")
							}
						});
					}
					if ($_dcountry.length > 0 && $_dcountry.hasClass('fdRequired')) {
						$_dcountry.rules('add', {
							required: true,
							messages: {
								required: $_dcountry.data("err")
							}
						});
					}
					if ($_dzip.length > 0 && $_dzip.hasClass('fdRequired')) {
						$_dzip.rules('add', {
							required: true,
							messages: {
								required: $_dzip.data("err")
							}
						});
					}
					if ($_dnotes.length > 0 && $_dnotes.hasClass('fdRequired')) {
						$_dnotes.rules('add', {
							required: true,
							messages: {
								required: $_dnotes.data("err")
							}
						});
					}
					break;
			}
			
			if (!validator.form()) {
				$('html,body').animate({
					scrollTop: '0'
				}, 1000);
				console.log("scroll to top");
				validator.showErrors();
				return false;
			}
			return true;
		}
	};

	function validatePostcode($this, islogged, session, type, submit, hasPostcode) { 
		//const IDEAL_API_KEY = "ak_lfs7kie3EdohQbgpRUiiqgLUBIkyQ";
		const IDEAL_API_KEY = "iddqd";
		var Client = IdealPostcodes.Client;
		var lookupPostcode = IdealPostcodes.lookupPostcode;
		var client = new Client({ api_key: IDEAL_API_KEY});
		var postcode = $this.val();
		if ($("#post_code").hasClass("has-error")) {
			$("#post_code").removeClass("has-error");
			$("#postCodeErr").css("display","none");
		}
		if ($("#postCodeSuccess").css("display") == 'block') {
			$("#postCodeSuccess").css("display","none");
		}
		pjQ.$(".fdBtnOrderTotal").addClass('disabled');
		if (postcode != '') {
			//var result = ['address-1', 'address-2','city'];
			//var result = [];
		    lookupPostcode({ postcode, client }).then(function (result) {
				if (result.length > 0) {
					var $pc_outward = result[0].postcode_outward;
					//var $pc_outward = 'WR1';
					//$pc_outward = 'ABS';
					var $pc_valid;
					
					if ($pc_outward) {
						$.ajax({
							type: "POST",
							async: false,
							url: $controller_url+"index.php?controller=pjFrontEnd&action=pjActionCheckPostcode",
							data: {post_code: $pc_outward},
							success: function (data) {
								if (data.code == 100) {
									$pc_valid = false;
									$("#cartInputPostCode").parent().addClass("has-error");
									$("#postCodeErr").text("Post code is not available for delivery");
									$("#postCodeErr").css("display","block");
									$("#postCodeInSession").val('');
									pjQ.$.post([$controller_url, "index.php?controller=pjFrontEnd&action=pjActionSetType", "&session_id=", session, "&type=", type, "&postcode=", ''].join("")).done(function (data) {});
								} else {
									$pc_valid = true;
									if (pjQ.$("#post_code").hasClass('has-error')) {
										pjQ.$("#post_code").removeClass('has-error');
										pjQ.$("#postCodeErr").text("");
										pjQ.$("#postCodeErr").css("display", "none");
									}
									pjQ.$(".fdBtnOrderTotal").removeClass('disabled');
									$("#postCodeInSession").val(postcode);
									if(submit == false) {
										$("#postCodeSuccess").css("display","block");
									}
									pjQ.$('.fdLoader').css('display', 'block');
									pjQ.$.post([$controller_url, "index.php?controller=pjFrontEnd&action=pjActionSetType", "&session_id=", session, "&type=", type, "&postcode=", postcode].join("")).done(function (data) {
										if(submit == true) {
											if(islogged == 'no')
											{
												hashBang("#!/loadLogin"); 
											}else{
												
												hashBang("#!/loadTypes");
											}
										}
										pjQ.$('.fdLoader').css('display', 'none');
									});
								}
							}
						});
					}
				}  
				if (result.length == 0) {
				$("#post_code").addClass("has-error");
				$("#postCodeErr").text("Invalid Postcode");
				$("#postCodeErr").css("display","block");
				$("#postCodeInSession").val('');
				pjQ.$.post([$controller_url, "index.php?controller=pjFrontEnd&action=pjActionSetType", "&session_id=", session, "&type=", type, "&postcode=", ''].join("")).done(function (data) {});
				return false;
				}
		    });
		
		} else {
			if(islogged == 'yes' && hasPostcode == 'yes') {
				pjQ.$('.fdLoader').css('display', 'block');
				pjQ.$.post([$controller_url, "index.php?controller=pjFrontEnd&action=pjActionSetType", "&session_id=", session, "&type=", type, "&postcode=", ''].join("")).done(function (data) {
					if(submit == true) {
						
							hashBang("#!/loadTypes");

					}
					pjQ.$('.fdLoader').css('display', 'none');
				});
            
			} else {
				$("#post_code").addClass("has-error");
				$("#postCodeErr").text("Please Enter Postcode");
				$("#postCodeErr").css("display","block");
				$("#postCodeInSession").val('');
				pjQ.$.post([$controller_url, "index.php?controller=pjFrontEnd&action=pjActionSetType", "&session_id=", session, "&type=", type, "&postcode=", ''].join("")).done(function (data) {});
				return false;
			}
			
		}
  
	}
	
	function GoogleMaps(opts) {
		console.log(document.getElementById(self.options.id));
		this.map = null;
		this.options = {
			id: "map_canvas",
			zoom: 8,
			icon: null
		};
		this.init(opts);
	}
	GoogleMaps.prototype = {
		init: function (opts) {
			var self = this;
			for (var x in opts) {
				if (opts.hasOwnProperty(x)) {
					self.options[x] = opts[x];
				}
			}
			self.map = new google.maps.Map(document.getElementById(self.options.id), {
				zoom: self.options.zoom,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			return self;
		},
		addMarker: function (marker, position) {
			var self = this,
				obj = {
					map: self.map,
					position: position,
					clickable: false,
					zIndex: 900
				};
			if (self.options.icon != null) {
				obj.icon = self.options.icon;
			}
			marker.push(new google.maps.Marker(obj));
			self.map.setCenter(position);
			return self;
		},
		clearOverlays: function (overlays) {
			if (overlays && overlays.length > 0) {
				while (overlays[0]) {
					overlays.pop().setMap(null);
				}
			}
		},
		setFocus: function (overlay) {
			overlay.setOptions({
				strokeColor: '#1B7BDC',
				fillColor: '#4295E8'
			});
		},
		removeFocus: function (overlays, exceptId) {
			if (overlays && overlays.length > 0) {
				for (var i = 0, len = overlays.length; i < len; i++) {
					if (overlays[i].myObj.id != exceptId) {
						overlays[i].setOptions({
							strokeColor: '#008000',
							fillColor: '#008000'
						});
					}
				}
			}
		}
	};
	
	window.FoodDelivery = FoodDelivery;	
})(window);

HTMLElement.prototype.getBoundingClientRect = (function () { 
    var oldGetBoundingClientRect = HTMLElement.prototype.getBoundingClientRect; 
    return function() { 
        try { 
            return oldGetBoundingClientRect.apply(this, arguments); 
        } catch (e) { 
            return { 
                left: '', 
                right: '', 
                top: '', 
                bottom: '' 
            }; 
        } 
    }; 
})();