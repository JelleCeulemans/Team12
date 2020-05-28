<?php
/**
 * @file bestellen.php
 */
?>
<script>
    function validatieMelding (variabele, selector, tekst) {
        if (!variabele) {
            $('#'+selector).text(tekst).prev().children().addClass('border-danger');
        }
    }


    function validatie () {
        var naam = $('#naam').val();
        var voornaam = $('#voornaam').val();
        var telefoon = $('#telefoon').val();
        var email = $('#email').val();

        if (naam && voornaam && telefoon && email && email.indexOf('@') !== -1 && email.indexOf('.') !== -1) {
            return true;
        }
        else {
            validatieMelding(naam, 'meldingNaam', 'Geef uw naam in!');
            validatieMelding(voornaam, 'meldingVoornaam', 'Geef uw voornaam in!');
            validatieMelding(telefoon, 'meldingTelefoon', 'Geef uw telefoonnummer in!');
            if(email) {
                if (email && email.indexOf('@') === -1 || email.indexOf('.') === -1)  {
                    $('#meldingEmail').text('het email moet een \'@\' en een \'.\' bevatten').prev().children().addClass('border-danger');
                }
            }
            else {
                validatieMelding(email, 'meldingEmail', 'Geef uw email op!');
            }
            return false;
        }
    }
    $(document).ready(function () {
        $('#bevestigen').click(function (e) {
            if (!validatie()) {
                e.preventDefault();
            }
        });
        $('input').change(function () {
            $(this).removeClass('border-danger').parent().next().text('');
        });
    });
</script>

<h3>Vul onderstaande gegevens in</h3>

<?php echo form_open('product/bevestiging', array('id' => 'aanvraagFormulier')); ?>

<div class="form-group">
    <?php echo form_label('Naam'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-user-circle"></i></span>
        </div>
        <?php echo form_input(array('name' => 'naam',
            'id' => 'naam',
            'class' => 'form-control' ));
        ?>
    </div>
    <div id="meldingNaam" class="ml-2 text-danger"></div>
</div>

<div class="form-group">
    <?php  echo form_label('Voornaam'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-user-circle"></i></span>
        </div>
        <?php echo form_input(array('name' => 'voornaam',
            'id' => 'voornaam',
            'class' => 'form-control'));
        ?>
    </div>

    <div id="meldingVoornaam" class="ml-2 text-danger"></div>
</div>

<div class="form-group">
    <?php echo form_label('Telefoon'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-phone"></i></span>
        </div>
        <?php echo form_input(array('name' => 'telefoon',
            'type' => 'number',
            'id' => 'telefoon',
            'class' => 'form-control'));
        ?>
    </div>
    <div id="meldingTelefoon" class="ml-2 text-danger"></div>
</div>

<div class="form-group">
    <?php echo form_label('Email'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-envelope"></i></span>
        </div>
        <?php echo form_input(array('name' => 'email',
            'id' => 'email',
            'class' => 'form-control'));
        ?>
    </div>


    <div id="meldingEmail" class="ml-2 text-danger"></div>
</div>

<?php
echo form_submit(array('id' => 'bevestigen', 'class' => 'btn btn-success', 'value' => 'Bevestigen'));
echo form_close();

?>
<p class="mt-5"><?php echo anchor('product/index', 'Terug naar de webshop', array('class' => 'btn btn-success'));?></p>
