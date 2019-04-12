// Validation
var errVar = '';

/* Validation */
function validateForm(form,output){

    //Form = form element
    //Output = true/false - decides if a popup of errors should be shown

    var result = true;
    var err = [];
    var thisErr = false;

    $(form +' input.required, '+ form +' select.required, '+ form +' textarea.required, ' + form +' input.validate, '+ form +' select.validate, '+ form +' textarea.validate').each(function() {

        var validateThis = true;

        if ($(this).hasClass('validate')){
            if (!$(this).val().length){
                validateThis = false;
                $(this).removeClass('error');
				$(this).parent('.selectwrapper').removeClass('error');
                $(this).off("keyup change");
            }
        }

        if (validateThis==true){

            thisErr = false;

            if ($(this).hasClass('validateEmail')) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (!re.test($(this).val())) { thisErr=true; }
            }

            if ($(this).hasClass('validateLength')) {
                var minLen = $(this).attr('data-minlength') ? parseInt($(this).attr('data-minlength')) : 2;
                var maxLen = $(this).attr('data-maxlength') ? parseInt($(this).attr('data-maxlength')) : 9999;
                if ($(this).val()){
                    if ($(this).val().length<minLen || $(this).val().length>maxLen) { thisErr=true; }
                } else {
                    thisErr=true;
                }
            }

            if ($(this).hasClass('validateBarcode')){
                var regex2 = /^\d+$/;
                if (!regex2.test($(this).val()) || $(this).val().length>128) { thisErr=true; }
            }

            if ($(this).hasClass('validateNumber')){
                var regex2 = /^\d+$/;
                var min = $(this).attr('data-min') ? parseInt($(this).attr('data-min')) : -99999999;
                var max = $(this).attr('data-max') ? parseInt($(this).attr('data-max')) : 99999999;
    			if (!regex2.test($(this).val()) || $(this).val().length==0 || parseInt($(this).val())<min || parseInt($(this).val())>max) { thisErr=true; }
    		}

            if ($(this).hasClass('validatePhone')){
    			var regex2 = /^\d+$/;
    			if (!regex2.test($(this).val()) || $(this).val().length<7) { thisErr=true; }
    		}

            if ($(this).hasClass('validateEAN')){
    			var regex2 = /^\d+$/;
    			if (!regex2.test($(this).val()) || $(this).val().length!=13) { thisErr=true; }
    		}

    		if ($(this).hasClass('validateDecimal')){
                // Hits number with max 2 decimal and . as seperator.
                var regex3 = /^[0-9]+(\.[0-9]{1,2})?$/;  // old regex ^[-+]?([0-9]*\.[0-9]{0,2})|([0-9]+)$

    			if (!regex3.test($(this).val())){ thisErr=true; }
    		}

    		if ($(this).hasClass('validateDate')){
    			var regex4 = /^(\d+-?)+\d+$/;
    			if (!regex4.test($(this).val()) || $(this).val().length!=10){ thisErr=true; }
    		}

            if ($(this).hasClass('validateSmallDate')){
    			//var regex5 = /^\d{2}-?\d{2}$/;
                var regex5 = /^\d{2}-\d{2}$/;
    			if (!regex5.test($(this).val()) || $(this).val().length!=5){ thisErr=true; }
    		}

            if ($(this).hasClass('validateTime')){
    			var coloncount = ($(this).val().match(/:/g) || []).length;
    			if (!($(this).val().length==5 && coloncount==1) && !($(this).val().length==8 && coloncount==2)){ thisErr=true; }
    		}

            if ($(this).hasClass('validatePassword')) {

                if ( $(this).val().length<4 ) { thisErr=true; }

            }

            if ($(this).hasClass('validateNoSpecificChar')) {
                var char = $(this).attr('data-validate-char');
                var regex = new RegExp( char, 'g' );
                var count = ($(this).val().match(regex) || []).length;
    			if (count>0){ thisErr=true; }
            }


            if ($(this).hasClass('validateUniqueWithClass')) {
                if ( $(this).val().length>0 ) {
                    var samevalueHits = 0;
                    var tests= $(form +' .'+$(this).attr('data-validate-class'));
                    for (var z = 0; z < tests.length; z++) {
                      if ( $(tests[z]).val()==$(this).val() ) { samevalueHits++; }
                    }
                    if (samevalueHits>1){ thisErr=true; }
                }
            }

            if (thisErr==true){

                var title = ($(this).hasClass('tooltipstered')) ? $(this).tooltipster('content') : $(this).attr('title');

                if ( err.indexOf(title)==-1){
                    err.push(title);
                }
                if (output==true) { $(this).addClass('error'); $(this).parent('.selectwrapper').addClass('error'); }
                $(this).off("keyup.validation change.validation").on("keyup.validation change.validation",function() { validateForm(form,false); });
                $(this).removeClass("valid");
            } else {
                $(this).removeClass('error');
				$(this).parent('.selectwrapper').removeClass('error');
                $(this).off("keyup.validation change.validation");
                $(this).addClass("valid");
            }
        }
    });

    if (err.length==0){
        return true;
    } else {
        if (output==true) {
            var c = 'Følgende er ikke udfyldt korrekt:<br><div style="text-align:left;"><ul>';
            for (i = 0; i < err.length; ++i) {
                c+='<li>'+err[i]+'</li>';
            }
            c+='</ul></div>';
            showToast(c);
			// showModal('<div class="modal_window_titlebar">FEJL<a class="modal_close" href="javascript:hideModal();"><i class="fa fa-times"></i></a></div><div class="modal_error_content">'+c+'</div><div class="modal_window_closebar"><button type="button" class="form-btn" onclick="hideModal();">LUK VINDUE   <i class="fa fa-times"></i></button></div>','',false,'');
            return false;
        } else {
            return false;
        }
    }
}
