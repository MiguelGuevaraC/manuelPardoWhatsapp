
// Form-Component.js
// ====================================================================
// This file should not be included in your project.
// This is just a sample how to initialize plugins or components.
//
// - ThemeOn.net -


$(document).on('nifty.ready', function() {


    // CHOSEN
    // =================================================================
    // Require Chosen
    // http://harvesthq.github.io/chosen/
    // =================================================================
    $('#demo-chosen-select').chosen();
    $('#demo-cs-multiselect').chosen({width:'100%'});



    // SELECT2 PLACEHOLDER
    // =================================================================
    // Require Select2
    // https://github.com/select2/select2
    // =================================================================
    $("#demo-select2-productos").select2({
        placeholder: "Selecciona un Producto",
        allowClear: true
    });

  





});
