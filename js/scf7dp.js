jQuery(document).ready(function($) {
  if (!Modernizr.inputtypes['date']) {
    $('input[type=date]').datepicker( {
      dateFormat: "yy-mm-dd",
      autoclose: true,
      showAnim: setting.effect,
      changeMonth: setting.monyearmenu,
      changeYear: setting.monyearmenu,
      showWeek: setting.showWeek,
    });
  }
});