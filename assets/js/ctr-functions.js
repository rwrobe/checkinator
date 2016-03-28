;var Checkinator = (function($){

    var $form = jQuery(document.getElementById('check-in'));

    var init = function(){
        /** Add validation */
        jQuery.validator.addMethod("accept", function(value, element, param) {
            return value.match(new RegExp("." + param + "$"));
        });

        $('#check-in').validate({
            rules: {
                firstName: {
                    required: true,
                    minlength: 2,
                    maxlength: 20,
                    accept: "[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$"
                },
                lastName: {
                    required: true,
                    minlength: 2,
                    maxlength: 20,
                    accept: "[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$"
                }
            },

            // Specify the validation error messages
            messages: {
                firstName: "Please enter a valid first name",
                lastName: "Please enter a valid last name",
            }
        });
    };

    return {
        init: init
    }

})(jQuery);

jQuery(document).ready(function(){
    Checkinator.init();
});