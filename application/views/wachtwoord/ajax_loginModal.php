<?php
/**
 * @file ajax_loginModal.php
 */
?>


<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Aanmelden</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group">
        <?php echo form_label('Gebruikersnaam:', 'email'); ?>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-user-circle"></i></span>
            </div>
            <?php echo form_input(array(
                'name' => 'gebruikersnaam',
                'id' => 'gebruikersnaam',
                'size' => '30',
                'class' => 'form-control')); ?>
        </div>
        <div id="gebruikersnaamMelding" class="text-danger ml-2"></div>
    </div>
    <div class="form-group">
        <?php echo form_label('Wachtwoord:', 'wachtwoord');
        $data = array(
            'name' => 'wachtwoord',
            'id' => 'wachtwoord',
            'size' => '30',
            'class' => 'form-control'); ?>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-key"></i></span>
            </div>
            <?php echo form_password($data); ?>
        </div>
        <div id="wachtwoordMelding" class="text-danger ml-2"></div>
    </div>
    <div id="loginAlert" class="alert alert-danger" role="alert">
    </div>
</div>
<div class="modal-footer">
    <?php
    echo form_button(array('content' => 'Aanmelden', 'id' => 'aanmeldSubmit', 'class' => 'btn btn-success'));
    echo '<button type="button" id="wachtwoordKnop" class="btn btn-info">Wachtwoord herstellen</button>';
    ?>
</div>