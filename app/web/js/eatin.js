(function($) {
  $('#tableModal').modal({backdrop: 'static', keyboard: false}, 'show');
  $(document).on("click", "#sel_table_name_modal", function() {
    $('#tableModal').modal({backdrop: 'static', keyboard: false}, 'show');
  })
})(jQuery);