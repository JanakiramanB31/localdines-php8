var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var $frmCreateLocation = $("#frmCreateLocation"),
			$frmUpdateLocation = $("#frmUpdateLocation"),
			$frmUpdatePrices = $("#frmUpdatePrices"),
			multilang = ($.fn.multilang !== undefined),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			overlays = [];
		
		if (multilang && 'pjCmsLocale' in window) {
			$(".multilang").multilang({
				langs: pjCmsLocale.langs,
				flagPath: pjCmsLocale.flagPath,
				tooltip: "",
				select: function (event, ui) {
					$("input[name='locale_id']").val(ui.index);
				}
			});
		}
		
		if ($frmUpdatePrices.length > 0 && validate) {
			$frmUpdatePrices.validate({
				submitHandler: function(form){
					var ladda_buttons = $(form).find('.ladda-button');
				    if(ladda_buttons.length > 0)
                    {
                        var l = ladda_buttons.ladda();
                        l.ladda('start');
                    }
				    var valid = true;
				    $('#tblPrices tbody').find('tr').each(function(){
				    	var $this = $(this);
				    	var index = $this.attr('data-index');
				    	if (typeof index !== typeof undefined && index !== false && index !== null) 
				    	{
				    		var amount_from = parseFloat($('input[name="total_from['+index+']"]').val());
				    		var amount_to = parseFloat($('input[name="total_to['+index+']"]').val());
				    		if(amount_from > amount_to)
				    		{
				    			valid = false;
				    		}
				    	}
				    });
				    if(valid == false)
				    {
				    	l.ladda('stop');
				    	swal({
							title: myLabel.alert_title,
							text: myLabel.alert_text,
							type: "warning",
							showCancelButton: false,
							confirmButtonColor: "#DD6B55",
							confirmButtonText: myLabel.btn_ok,
							closeOnConfirm: false,
							showLoaderOnConfirm: true
						});
				    }else{
				    	form.submit();
				    }
				    return false;
				}
			});
		}
		
		if ($frmCreateLocation.length > 0 && validate) {
			$frmCreateLocation.validate({
				invalidHandler: function (event, validator) {
				    $(".pj-multilang-wrap").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId)
						{
							$(this).css('display','block');
						}else{
							$(this).css('display','none');
						}
					});
					$(".pj-form-langbar-item").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId)
						{
							$(this).addClass('btn-primary');
						}else{
							$(this).removeClass('btn-primary');
						}
					});
				},
				ignore: ""
			});
		}
		if ($frmUpdateLocation.length > 0 && validate) {
			$frmUpdateLocation.validate({
				invalidHandler: function (event, validator) {
				    $(".pj-multilang-wrap").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId)
						{
							$(this).css('display','block');
						}else{
							$(this).css('display','none');
						}
					});
					$(".pj-form-langbar-item").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId)
						{
							$(this).addClass('btn-primary');
						}else{
							$(this).removeClass('btn-primary');
						}
					});
				},
				ignore: ""
			});
		}
		if ($frmCreateLocation.length > 0 || $frmUpdateLocation.length > 0) 
		{
			var myGoogleMaps = null,
				myGoogleMapsMarker = null;
			
			function GoogleMaps() {
				this.map = null;
				this.drawingManager = null;
				this.init();
			}
			GoogleMaps.prototype = {
				init: function () {
					var self = this;
					self.map = new google.maps.Map(document.getElementById("fd_map_canvas"), {
						zoom: 8,
						center: new google.maps.LatLng(40.65, -73.95),
						mapTypeId: google.maps.MapTypeId.ROADMAP
					});
					return self;
				},
				addMarker: function (position) {
					if (myGoogleMapsMarker != null) {
						myGoogleMapsMarker.setMap(null);
					}
					myGoogleMapsMarker = new google.maps.Marker({
						map: this.map,
						position: position,
						icon: "app/web/img/backend/pin.png"
					});
					this.map.setCenter(position);
					$("#lat").val(position.lat());
					$("#lng").val(position.lng());
					return this;
				},
				draw: function () {
					var $el,
						self = this,
						tmp = {cnt: 0, type: ""},
						mapBounds = new google.maps.LatLngBounds();
					$(".coords").each(function (i, el) {
						$el = $(el);
						tmp.cnt += 1;
						switch ($el.data("type")) {
							case 'circle':
								var str = $el.val().replace(/\(|\)|\s+/g, ""),
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
						            editable: true,
						            center_changed: function ($_el) {
						            	return function () {
						            		self.update.call(self, this, $_el, 'circle');
						            	};
						            }($el),
						            radius_changed: function ($_el) {
						            	return function () {
						            		self.update.call(self, this, $_el, 'circle');
						            	};
						            }($el)
								});
								circle.myObj = {
									"id": $el.data("id")
								};
								circle.setMap(self.map);
								mapBounds.extend(center);
								google.maps.event.addListener(circle, "click", function () {
									self.removeFocus(overlays, this.myObj.id);
									self.setFocus(this);
									selectedShape = this.myObj.id;
								});
								overlays.push(circle);
								tmp.type = "circle";
								break;
							case 'polygon':
								var path,
									str = $el.val().replace(/\(|\s+/g, ""),
									arr = str.split("),"),
									paths = [];
								arr[arr.length-1] = arr[arr.length-1].replace(")", "");
								for (var i = 0, len = arr.length; i < len; i++) {
									path = new google.maps.LatLng(arr[i].split(",")[0], arr[i].split(",")[1]);
									paths.push(path);
									mapBounds.extend(path);
								}
								var polygon = new google.maps.Polygon({
									paths: paths,
									strokeColor: '#008000',
									strokeOpacity: 1,
									strokeWeight: 1,
									fillColor: '#008000',
									fillOpacity: 0.5,
						            editable: true
							    });
								polygon.myObj = {
									"id": $el.data("id")
								};
								polygon.setMap(self.map);

								self.update.call(self, polygon, $el, 'polygon');

								google.maps.event.addListener(polygon, "click", function () {
									self.removeFocus(overlays, this.myObj.id);
									self.setFocus(this);
									selectedShape = this.myObj.id;
								});
								overlays.push(polygon);
								tmp.type = "polygon";
								break;
							case 'rectangle':
								var bound,
									str = $el.val().replace(/\(|\s+/g, ""),
									arr = str.split("),"), 
									bounds = [];
								for (var i = 0, len = arr.length; i < len; i++) {
									arr[i] = arr[i].replace(/\)/g, "");
									bound = new google.maps.LatLng(arr[i].split(",")[0], arr[i].split(",")[1]);
									bounds.push(bound);
									mapBounds.extend(bound);
								}
								var rectangle = new google.maps.Rectangle({
									strokeColor: '#008000',
						            strokeOpacity: 1,
						            strokeWeight: 1,
						            fillColor: '#008000',
						            fillOpacity: 0.5,
						            bounds: new google.maps.LatLngBounds(bounds[0], bounds[1]),
						            editable: true,
						            bounds_changed: function ($_el) {
						            	return function () {
						            		self.update.call(self, this, $_el, 'rectangle');
						            	};
						            }($el)
								});
								
								rectangle.myObj = {
									"id": $el.data("id")
								};
								rectangle.setMap(self.map);
									
								google.maps.event.addListener(rectangle, "click", function () {
									self.removeFocus(overlays, this.myObj.id);
									self.setFocus(this);
									selectedShape = this.myObj.id;
								});
								overlays.push(rectangle);
								tmp.type = "rectangle";
								break;
						}
					});
					
					if (tmp.cnt === 1 && tmp.type === "circle") {
						this.map.setZoom(13);
					} else {
						this.map.fitBounds(mapBounds);
					}
				},
				drawing: function () {
					var self = this;
					this.drawingManager = new google.maps.drawing.DrawingManager({
						drawingMode: google.maps.drawing.OverlayType.POLYGON,
						drawingControl: true,
						drawingControlOptions: {
							position: google.maps.ControlPosition.TOP_CENTER,
							drawingModes: [
					            google.maps.drawing.OverlayType.CIRCLE,
					            google.maps.drawing.OverlayType.POLYGON,
					            google.maps.drawing.OverlayType.RECTANGLE
					        ]
						},
						circleOptions: {
							fillColor: '#008000',
							fillOpacity: 0.5,
						    strokeWeight: 1,
						    strokeColor: '#008000',
						    strokeOpacity: 1,
							editable: true
						},
						polygonOptions: {
							fillColor: '#008000',
							fillOpacity: 0.5,
						    strokeWeight: 1,
						    strokeColor: '#008000',
						    strokeOpacity: 1,
							editable: true
						},
						rectangleOptions: {
							fillColor: '#008000',
							fillOpacity: 0.5,
						    strokeWeight: 1,
						    strokeColor: '#008000',
						    strokeOpacity: 1,
							editable: true
						}
					});
					this.drawingManager.setMap(this.map);
					
					google.maps.event.addListener(this.drawingManager, 'overlaycomplete', function(event) {
						var rand = Math.ceil(Math.random() * 999999),
							$frm = $(".frmLocation").eq(0);
						switch (event.type) {
							case google.maps.drawing.OverlayType.CIRCLE:
								var input = $("<input>", {
									"type": "hidden",
									"name": "data[circle][new_" + rand + "]",
									"class": "coords",
									"data-type": "circle",
									"data-id": "new_" + rand
								}).appendTo($frm);
								self.update.call(self, event.overlay, input, 'circle');
								break;
							case google.maps.drawing.OverlayType.POLYGON:
								var input = $("<input>", {
									"type": "hidden",
									"name": "data[polygon][new_" + rand + "]",
									"class": "coords",
									"data-type": "polygon",
									"data-id": "new_" + rand
								}).appendTo($frm);
								self.update.call(self, event.overlay, input, 'polygon');
								break;
							case google.maps.drawing.OverlayType.RECTANGLE:
								var input = $("<input>", {
									"type": "hidden",
									"name": "data[rectangle][new_" + rand + "]",
									"class": "coords",
									"data-type": "rectangle",
									"data-id": "new_" + rand
								}).appendTo($frm);
								self.update.call(self, event.overlay, input, 'rectangle');
								break;
						}
						
						event.overlay.myObj = {
							id: "new_" + rand
						};
						
						google.maps.event.addListener(event.overlay, "click", function () {
							self.removeFocus(overlays, this.myObj.id);
							self.setFocus(this);
							selectedShape = this.myObj.id;
						});
						
						overlays.push(event.overlay);
					});
				},
				update: function (obj, $el, type) {
					switch (type) {
						case "circle":
							$el.val(obj.getCenter().toString()+"|"+obj.getRadius());
							break;
						case "polygon":
							var str = [];
							var paths = obj.getPaths();

							paths.getArray()[0].forEach(function (el, i) {
								str.push(el.toString());
							});
							$el.val(str.join(", "));

							obj.getPaths().forEach(function(path, index){
								// New point
								google.maps.event.addListener(path, 'insert_at', function(){
									var str = [];
									path.forEach(function (el, i) {
										str.push(el.toString());
									});
									$el.val(str.join(", "));
								});

								// Point was removed
								google.maps.event.addListener(path, 'remove_at', function(){
									var str = [];
									path.forEach(function (el, i) {
										str.push(el.toString());
									});
									$el.val(str.join(", "));
								});

								// Point was moved
								google.maps.event.addListener(path, 'set_at', function(){
									var str = [];
									path.forEach(function (el, i) {
										str.push(el.toString());
									});
									$el.val(str.join(", "));
								});
							});
							break;
						case "rectangle":
							$el.val(obj.getBounds().toString());
							break;
					}
				},
				deleteShape: function (overlays) {
					if (overlays && overlays.length > 0) {
						for (var i = 0, len = overlays.length; i < len; i++) {
							if (overlays[i].myObj.id == selectedShape) {
								overlays[i].setMap(null);
								$(".btnDeleteShape").css('display', 'none');
								$(".coords[data-id='" + selectedShape + "']").remove();
								return true;
								break;
							}
						}
					}
					return false;
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
					$(".btnDeleteShape").css('display', 'inline-block');
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
			if($frmCreateLocation.length > 0)
			{
				myGoogleMaps = new GoogleMaps();
				myGoogleMaps.drawing();
				google.maps.event.trigger(myGoogleMaps.map, 'resize');
				
				google.maps.event.addDomListener($(".btnDeleteShape").get(0), "click", function () {
					myGoogleMaps.deleteShape(overlays);
				});
			}
			if($frmUpdateLocation.length > 0)
			{
				var lat = $("#lat").val(),
					lng = $("#lng").val(),
					isValid = (lat !== undefined && lng !== undefined && lat.length > 0 && lng.length > 0);
				if (myGoogleMaps == null) {
					myGoogleMaps = new GoogleMaps();
				}
				if (isValid) {
					myGoogleMaps.addMarker(new google.maps.LatLng(lat, lng));
				}
				if ($(".coords").length === 0) {
					if (isValid) {
						myGoogleMaps.map.setCenter(new google.maps.LatLng(lat, lng));
					}
				} else {
					myGoogleMaps.draw();
				}
				myGoogleMaps.drawing();
				
				google.maps.event.addDomListener($(".btnDeleteShape").get(0), "click", function () {
					myGoogleMaps.deleteShape(overlays);
				});
			}
		}
		
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminLocations&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminLocations&action=pjActionDeleteLocation&id={:id}"},
				          {type: "clock-o", text: " " + myLabel.working_time,  url: "index.php?controller=pjAdminTime&action=pjActionIndex&id={:id}"},
						  {type: "money", text: " " + myLabel.delivery_fees,  url: "index.php?controller=pjAdminLocations&action=pjActionPrice&id={:id}"}
				          ],
				columns: [{text: myLabel.location_name, type: "text", sortable: true, editable: true},
				          {text: myLabel.address, type: "text", sortable: true, editable: true}],
				dataUrl: "index.php?controller=pjAdminLocations&action=pjActionGetLocation",
				dataType: "json",
				fields: ['name', 'address'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminLocations&action=pjActionDeleteLocationBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminLocations&action=pjActionSaveLocation&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				}
			});
		}
		
		$(document).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminLocations&action=pjActionGetLocation", "name", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".btnGetCoords", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $frm = null;
			if($frmCreateLocation.length > 0)
			{
				$frm = $frmCreateLocation;
			}
			if($frmUpdateLocation.length > 0)
			{
				$frm = $frmUpdateLocation;
			}
			$.post("index.php?controller=pjAdminLocations&action=pjActionGetCoords", $frm.serialize()).done(function (data) {
				$('.pj-loader').css('display', 'none');
				if (data.lat && data.lng) {
					$("#fd_get_coords_error").hide();
					if (myGoogleMaps == null) {
						myGoogleMaps = new GoogleMaps();
					}
					google.maps.event.trigger(myGoogleMaps.map, 'resize');
					myGoogleMaps.map.setCenter(new google.maps.LatLng(data.lat, data.lng));
					myGoogleMaps.addMarker(new google.maps.LatLng(data.lat, data.lng));
				} else {
					$("#fd_get_coords_error").show();
					$("#lat").val("");
					$("#lng").val("");
				}
			});
			return false;
		}).on("click", ".pj-add-price", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var index = Math.ceil(Math.random() * 999999);
			var clone_text = $("#tblClonePrices").find("tbody").html();
			clone_text = clone_text.replace(/\{INDEX\}/g, 'fd_' + index);
			$('#tblPrices tbody').append(clone_text);
			$('.pjFdEmptyRow').hide();
			return false;
		}).on("click", ".pj-remove-price", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).parent().parent().parent().remove();
			if($('#tblPrices tbody').find('tr').length == 1)
			{
				$('.pjFdEmptyRow').show();
			}
			return false;
		});
	});
})(jQuery_1_8_2);