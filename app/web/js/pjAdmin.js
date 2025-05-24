var jQuery_1_8_2 = jQuery_1_8_2 || jQuery.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmLoginAdmin = $("#frmLoginAdmin"),
			$frmForgotAdmin = $("#frmForgotAdmin"),
			$frmUpdateProfile = $("#frmUpdateProfile"),
			validate = ($.fn.validate !== undefined);
		
		if ($frmLoginAdmin.length > 0 && validate) {
			$frmLoginAdmin.validate({
				rules: {
					login_email: {
						required: true,
						email: true
					},
					login_password: "required"
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		if ($frmForgotAdmin.length > 0 && validate) {
			$frmForgotAdmin.validate({
				rules: {
					forgot_email: {
						required: true,
						email: true
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		if ($frmUpdateProfile.length > 0 && validate) {
			$frmUpdateProfile.validate({
				rules: {
					"email": {
						required: true,
						email: true
					},
					"password": "required",
					"name": "required"
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}

		if ($("#stocked").length > 0 && c3) {
            c3.generate({
                bindto: '#stocked',
                data:{
                    x : 'x',
                    columns: [
                        pjChart.columns_labels,
                        pjChart.columns_data
                    ],
                    colors:{
                        Booked: '#12711f'
                    },
                    type: 'bar',
                    groups: [
                        [pjChart.columns_data[0]]
                    ]
                },
                legend: {
                    show: false
                },
                tooltip: {
                    format: {
                        title: function (d) {
                            return  pjChart.tooltips[d] ;
                        }
                    }
                },
                axis: {
                    x: {
                        type: 'category'
                    },
                    y: {
                        min: 1,
                        max: pjChart.y_axis_max,
                        tick: {
                            count: pjChart.y_axis_max,
                            values: pjChart.y_axis_values
                        }
                    }
                }

            });
        }
	});
})(jQuery_1_8_2);