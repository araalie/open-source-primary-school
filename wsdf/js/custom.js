function DisplayPopup(msgStr) {

    var type = 'information';
    var title = 'Feedback';
    var msg = '';

if(msgStr==undefined || msgStr==''){
	return;
}
    var detail = msgStr.split('|');

    if (detail.length == 3) {
        type = detail[0];
        title = detail[1];
        msg = detail[2];
    }

    if (detail.length == 2) {
        title = detail[0];
        msg = detail[1];
    }

    if (detail.length == 1) {
        msg = detail[0];
    }

    $.Zebra_Dialog(msg, {
        'type': type,
        'title': title
    });
}

$.ajaxSetup({
    beforeSend: function () {
        $("#loading").show();
    },
    complete: function () {
        $("#loading").hide();
    }
});

function number_format(number, decimals, dec_point, thousands_sep) {
    // http://kevin.vanzonneveld.net
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://getsprink.com)
    // +     bugfix by: Benjamin Lupton
    // +     bugfix by: Allan Jensen (http://www.winternet.no)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +     bugfix by: Howard Yeend
    // +    revised by: Luke Smith (http://lucassmith.name)
    // +     bugfix by: Diogo Resende
    // +     bugfix by: Rival
    // +      input by: Kheang Hok Chin (http://www.distantia.ca/)
    // +   improved by: davook
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Jay Klehr
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Amir Habibi (http://www.residence-mixte.com/)
    // +     bugfix by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // *     example 1: number_format(1234.56);
    // *     returns 1: '1,235'
    // *     example 2: number_format(1234.56, 2, ',', ' ');
    // *     returns 2: '1 234,56'
    // *     example 3: number_format(1234.5678, 2, '.', '');
    // *     returns 3: '1234.57'
    // *     example 4: number_format(67, 2, ',', '.');
    // *     returns 4: '67,00'
    // *     example 5: number_format(1000);
    // *     returns 5: '1,000'
    // *     example 6: number_format(67.311, 2);
    // *     returns 6: '67.31'
    // *     example 7: number_format(1000.55, 1);
    // *     returns 7: '1,000.6'
    // *     example 8: number_format(67000, 5, ',', '.');
    // *     returns 8: '67.000,00000'
    // *     example 9: number_format(0.9, 0);
    // *     returns 9: '1'
    // *    example 10: number_format('1.20', 2);
    // *    returns 10: '1.20'
    // *    example 11: number_format('1.20', 4);
    // *    returns 11: '1.2000'
    // *    example 12: number_format('1.2000', 3);
    // *    returns 12: '1.200'
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

$.views.helpers({

    format: function (val, format) {

        switch (format) {
        case "upper":
            return val.toUpperCase();
        case "lower":
            return val.toLowerCase();
        case "money":
            return number_format(val);
        }
    },
    
    checkBox: function(wasPaid, numBought){
    	
		if(wasPaid==null || wasPaid==false || wasPaid==0)
		{
			if(numBought>0){
    			return "";
    		}			
		}
		
		return "disabled";    	
    },
    bool2Str: function(wasPaid, numBought){
    	
		if(wasPaid==null)
		{
			if(numBought==null || numBought==0){
				return "N/A";
			}
			
			return "No";	
		}
		
		if(wasPaid==false || wasPaid==0)
		{
				return "N/A";
		}
		return "Yes";    	
    }
});


function clearFormElements(ele) {
    $(ele).each(function () {
        switch (this.type) {
        case 'password':
        case 'select-multiple':
        case 'select-one':
        case 'text':
        case 'textarea':
            $(this).val('');
            break;
        case 'checkbox':
        case 'radio':
            this.checked = false;
        }
    });
}

$.validator.methods.number = function (value, element) {
    return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/.test(value);
}


$(".numeric").keypress(function (event) {
    // Backspace, tab, enter, end, home, left, right
    // We don't support the del key in Opera because del == . == 46.
    var controlKeys = [8, 9, 13, 35, 36, 37, 39];
    // IE doesn't support indexOf
    var isControlKey = controlKeys.join(",").match(new RegExp(event.which));
    // Some browsers just don't raise events for control keys. Easy.
    // e.g. Safari backspace.
    if (!event.which || // Control keys in most browsers. e.g. Firefox tab is 0
    (49 <= event.which && event.which <= 57) || // Always 1 through 9
    (48 == event.which && $(this).attr("value")) || // No 0 first digit
    isControlKey) { // Opera assigns values for control keys.
        return;
    } else {
        event.preventDefault();
    }
});

$(document).ready(function () {

    $('.tipsy').gips({ 'theme': 'green'   });

    $('body').on('keypress', '.numeric', function (event) {

        // Backspace, tab, enter, end, home, left, right
        // We don't support the del key in Opera because del == . == 46.
        var controlKeys = [8, 9, 13, 35, 36, 37, 39];
        // IE doesn't support indexOf
        var isControlKey = controlKeys.join(",").match(new RegExp(event.which));
        // Some browsers just don't raise events for control keys. Easy.
        // e.g. Safari backspace.
        if (!event.which || // Control keys in most browsers. e.g. Firefox tab is 0
        (49 <= event.which && event.which <= 57) || // Always 1 through 9
        (48 == event.which && $(this).attr("value")) || // No 0 first digit
        isControlKey) { // Opera assigns values for control keys.
            return;
        } else {
            event.preventDefault();
        }

    });


    $('.can-toggle').click(function () {
        $(this).next(".toggles").toggleClass("show-me");
        $(this).toggleClass("eject-down");
    });


});


function formToObj(form){
	
	var oElements = {};
	
	$(form+' [name]').each(function(){
	    oElements[this.name] = this.value;
	});
	
    return oElements;
}


var AppUniverse = {
    terminalFeesEntryFooter: '<tr>' + '<td colspan="3"><br/></td>' + '</tr><tr>' + '<td colspan="3">Bank Slip No. : <input class="{required:true} input-small payment-info" type="text" name="pay-slip" id="pay-slip" /></td>' + '</tr><tr>' + '<td colspan="3">Date of Payment : <input class="{required:true} span2 payment-info date-done" type="text" name="date-done" id="date-done" readonly /></td>' + '</tr><tr>' + '<td colspan="3">Narrative : <textarea name="narrative" id="narrative" class="payment-info input-small"></textarea></td>' + '</tr><tr><td colspan="3">' + '<a href="#" id="post-fees-terminal" class="btn btn-primary" >Post Fees (Term)</a>' + '</td></tr>' + '<tr><td colspan="3">' + '<div id="form-errors"></div>' + '</td></tr>',
    annualFeesEntryFooter: '<tr>' + '<td colspan="3"><br/></td>' + '</tr><tr>' + '<td colspan="3">Bank Slip No. : <input class="{required:true} input-small payment-info" type="text" name="annual-pay-slip" id="annual-pay-slip" /></td>' + '</tr><tr>' + '<td colspan="3">Date of Payment : <input class="{required:true} span2 payment-info date-done" type="text" name="annual-date-done" id="annual-date-done" readonly /></td>' + '</tr><tr>' + '<td colspan="3">Narrative : <textarea name="annual-narrative" id="annual-narrative" class="payment-info input-small"></textarea></td>' + '</tr><tr><td colspan="3">' + '<a href="#" id="post-fees-annual" class="btn btn-primary" >Post Fees (Annual)</a>' + '</td></tr>' + '<tr><td colspan="3">' + '<div id="form-errors-annual"></div>' + '</td></tr>',
    onceFeesEntryFooter: '<tr>' + '<td colspan="3"><br/></td>' + '</tr><tr>' + '<td colspan="3">Bank Slip No. : <input class="{required:true} input-small payment-info" type="text" name="once-pay-slip" id="once-pay-slip" /></td>' + '</tr><tr>' + '<td colspan="3">Date of Payment : <input class="{required:true} span2 payment-info date-done" type="text" name="once-date-done" id="once-date-done" readonly /></td>' + '</tr><tr>' + '<td colspan="3">Narrative : <textarea name="once-narrative" id="once-narrative" class="payment-info input-small"></textarea></td>' + '</tr><tr><td colspan="3">' + '<a href="#" id="post-fees-once" class="btn btn-primary" >Post Fees (One Time)</a>' + '</td></tr>' + '<tr><td colspan="3">' + '<div id="form-errors-once"></div>' + '</td></tr>',
	adhocFeesEntryFooter: '<tr>' + '<td colspan="3"><br/></td>' + '</tr><tr>' + '<td colspan="3">Bank Slip No. : <input class="{required:true} input-small payment-info" type="text" name="adhoc-pay-slip" id="adhoc-pay-slip" /></td>' + '</tr><tr>' + '<td colspan="3">Date of Payment : <input class="{required:true} span2 payment-info date-done" type="text" name="adhoc-date-done" id="adhoc-date-done" readonly /></td>' + '</tr><tr>' + '<td colspan="3">Narrative : <textarea name="adhoc-narrative" id="adhoc-narrative" class="payment-info input-small"></textarea></td>' + '</tr><tr><td colspan="3">' + '<a href="#" id="post-fees-adhoc" class="btn btn-primary" >Post Fees (Adhoc)</a>' + '</td></tr>' + '<tr><td colspan="3">' + '<div id="form-errors-once"></div>' + '</td></tr>',
	holidayFeesEntryFooter: '<tr>' + '<td colspan="3"><br/></td>' + '</tr><tr>' + '<td colspan="3">Bank Slip No. : <input class="{required:true} input-small payment-info" type="text" name="holiday-pay-slip" id="holiday-pay-slip" /></td>' + '</tr><tr>' + '<td colspan="3">Date of Payment : <input class="{required:true} span2 payment-info date-done" type="text" name="holiday-date-done" id="holiday-date-done" readonly /></td>' + '</tr><tr>' + '<td colspan="3">Narrative : <textarea name="holiday-narrative" id="holiday-narrative" class="payment-info input-small"></textarea></td>' + '</tr><tr><td colspan="3">' + '<a href="#" id="post-fees-holiday" class="btn btn-primary" >Post Fees (Holiday)</a>' + '</td></tr>' + '<tr><td colspan="3">' + '<div id="form-errors-holiday"></div>' + '</td></tr>'    
};


function dynamicIframe(params){
	var tmpName ='tmp-div';
	var iframe = document.getElementById(tmpName);
	if(iframe){
		iframe.parentNode.removeChild(iframe);
	}
	
	iframe = document.createElement("iframe");
	
	iframe.src =params.url;
	iframe.style.display = "none";
	document.body.appendChild(iframe); 
	
}