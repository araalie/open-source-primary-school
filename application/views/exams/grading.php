<h4>
Grading Schemes
</h4>
<hr/><br/>

<div class="row-fluid">
<div class="span5">
<div class="panel">
<input type="hidden" id="feedback"  value="<?= valueOrBlank($feedback); ?>">
<h4>Create New Scheme</h4>
<?= form_open('grading/', array('id' => 'edit_grading', 'class' => 'form-vertical edit-form')); ?>
<fieldset>
<div class="control-group">
<label class="control-label" for="g_name">Name</label>
<div class="controls">
<input class="{required:true} " type="text" name="g_name" id="g_name" value="<?= valueOrBlank($name); ?>">
<input type="hidden" name="g_id" id="g_id" value="<?= valueOrBlank($gid); ?>">
</div>
</div>
<?= $grade_elements;?>
<div class="control-group">
<label class="control-label">
Remarks/Notes
</label>
<div class="controls">
<textarea class="span4" rows="2"  name='description'><?= valueOrBlank($remarks); ?></textarea>
</div>
</div>
</fieldset>
<?= form_close();?>
<div id='form-errors'></div>
<hr/>
<?=anchor('grading/', 'New Scheme', 'class="btn btn-warning margin-right-1" title="New Scheme"');?>
<button class="btn btn-primary submit" id="save_grading" name="save_grading">Save/Update Scheme</button>
</div>
</div>
<div class="span4">
<div class="panel">
<h4>Available Schemes</h4>
<hr/>
<?php
if(isset($schemes) && is_array($schemes)){
echo '<dl class="collapsy">';
	foreach($schemes as $s){
		echo sprintf('<dt><b>%s</b></dt><dd><ul id="scheme-%s">',$s['name'],$s['id']);
		foreach($s['ranges'] as $r){
			echo sprintf('<li> %s : %2s - %3s</li>',$r['code'],$r['min'],$r['max']);
		}
		echo '</ul></dd>';
	}
echo '</dl>';	
}

?>
</div>
</div>
</div>
<script type="text/javascript">
$.validator.setDefaults({
    ignore: ''
});

$(document).ready(function () {
    $("#edit_grading").validate({
        errorLabelContainer: $("#form-errors")
    });

    $("#save_grading").click(function () {
        if ($("#edit_grading").valid()) {
            if(validateBoundaries() &&
			validareRanges())
			{
				$("#edit_grading").submit();
			}
        }
    });

});

function addErrorMsg(msg) {
    var tmpl = '<label generated="true" class="error" style="display: block; ">' + msg + '</label>';

    $("#form-errors").append(tmpl);
	$("#form-errors").show();
}

function validateBoundaries() {
    //maximum
    var d1X = $('#D1_max').val();
    if (d1X != 100) {
        $('#D1_max').addClass('error');
        addErrorMsg('This value must be 100');
        return false;
    }

    //minimum
    var f9M = $('#F9_min').val();
    if (f9M != 0) {
        $('#F9_min').addClass('error');
        addErrorMsg('This value must be zero.')
        return false;
    }

    //range border 1/2
    var d1M = $('#D1_min').val();
    var d2X = $('#D2_max').val();
    if ((d1M - d2X) != 1) {
        $('#D1_min,#D2_max').addClass('error');
        addErrorMsg('Invalid Range');
        return false;
    }

    //range border 2/3
    var d2M = $('#D2_min').val();
    var c3X = $('#C3_max').val();
    if ((d2M - c3X) != 1) {
        $('#D2_min, #C3_max').addClass('error');
        addErrorMsg('Invalid Range');
        return false;
    }


    //range border 3/4
    var c3M = $('#C3_min').val();
    var c4X = $('#C4_max').val();
    if ((c3M - c4X) != 1) {
        $('#C3_min, #C4_max').addClass('error');
        addErrorMsg('Invalid Range');
        return false;
    }

    //range border 4/5
    var c4M = $('#C4_min').val();
    var c5X = $('#C5_max').val();
    if ((c3M - c4X) != 1) {
        $('#C4_min, #C5_max').addClass('error');
        addErrorMsg('Invalid Range');
        return false;
    }

    //range border 5/6
    var c5M = $('#C5_min').val();
    var c6X = $('#C6_max').val();
    if ((c5M - c6X) != 1) {
        $('#C5_min, #C6_max').addClass('error');
        addErrorMsg('Invalid Range');
        return false;
    }

    //range border 6/7
    var c6M = $('#C6_min').val();
    var p7X = $('#P7_max').val();
    if ((c6M - p7X) != 1) {
        $('#C6_min, #P7_max').addClass('error');
        addErrorMsg('Invalid Range');
        return false;
    }

    //range border 7/8
    var p7M = $('#P7_min').val();
    var p8X = $('#P8_max').val();
    if ((p7M - p8X) != 1) {
        $('#P7_min, #P8_max').addClass('error');
        addErrorMsg('Invalid Range');
        return false;
    }
    //range border 8/9
    var p8M = $('#P8_min').val();
    var f9X = $('#F9_max').val();
    if ((p8M - f9X) != 1) {
        $('#P8_min, #F9_max').addClass('error');
        addErrorMsg('Invalid Range');
        return false;
    }
	
	$("#form-errors").empty();
	$("#form-errors").hide();	
	
	return true;
}

function validareRanges(){

var gradz = ['D1','D2','C3','C4','C5','C6','P7','P8','F9'];

var noError=true;
$.each(gradz, function(index, value) { 
      var d1X = $('#'+value+'_max').val();
    var d1M = $('#'+value+'_min').val();	
    if (!((d1X - d1M)>1)) {
        $('#'+value+'_min, #'+value+'_max').addClass('error');
        addErrorMsg('Invalid Range');
		noError=false;
    }
});
		if(!noError){
			return false;
		}
	
	$("#form-errors").empty();
	$("#form-errors").hide();
	return true;
}

</script>
