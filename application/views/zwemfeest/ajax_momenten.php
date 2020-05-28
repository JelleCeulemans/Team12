<?php
/**
 * @file ajax_momenten.php
 */
?>

<?php echo form_label('Moment'); ?>
    <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend"><i class="far fa-clock"></i></span>
            </div>
            <?php echo form_dropdown('moment', $momenten, '',array('class' => 'form-control', 'id' => 'moment')); ?>
</div>
<div id="meldingMoment" class="ml-2 text-danger"></div>
