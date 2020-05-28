<?php
/**
 * @file zwemfeest.php
 */
?>

<style>
    #laden {
        margin: 50px 0 50px 180px;
    }
    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>


<script>
    var controle;
    var naam, voornaam, telefoon, email, aantal, datum, moment, momentTekst, gerecht, gerechtTekst, opmerkingen, weekdag, test, sluiting;
    function zwemfeestAanvragen() {
        $.ajax({
            type:"GET",
            url:site_url+ "/zwemfeest/aanvragen",
            data:{naam: naam, voornaam: voornaam, telefoon: telefoon, email: email, aantal: aantal, datum: datum, moment: moment, momentTekst: momentTekst, gerechtTekst: gerechtTekst, gerecht: gerecht, opmerkingen: opmerkingen},
            success: function (result) {
                $('#aanvraagFormulier')[0].reset();
                $('#aanvraagAlert').text(result).show().removeClass().addClass('alert alert-success');
                $(window).scrollTop(0);
                $('#zwemfeestBevestiging').modal('hide');
                $('#controle').show();
                $('#laden').hide();

            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function momentenLaden(weekdag) {
        $.ajax({
            type:"GET",
            url:site_url+ "/zwemfeest/momentenLaden",
            data:{weekdag: weekdag},
            success: function (result) {
                $('#momenten').html(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function controleerBoeking (datum, moment) {
        var controle;
        $.ajax({
            type:"GET",
            url:site_url+ "/zwemfeest/controleBoeking",
            data:{datum: datum, moment: moment},
            success: function (result) {
                if (result.length > '0') {
                    $('#aanvraagAlert').text(result).show().removeClass().addClass('alert alert-danger');
                    $(window).scrollTop(0);
                }
                else {
                    $('#zwemfeestBevestiging').modal();
                    bevestigingsGegevens();
                }
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }


    function validatie1() {
        naam = $('#naam').val();
        voornaam = $('#voornaam').val();
        telefoon = $('#telefoon').val();
        email = $('#email').val();
        aantal = $('#aantal').val();
        datum = $('#dp').val();
        moment = $('#moment').val();
        momentTekst = $("#moment option[value='"+moment+"']").text();
        gerecht = $('#gerecht').val();
        gerechtTekst = $("#gerecht option[value='"+gerecht+"']").text();
        opmerkingen = $('#opmerkingen').val();
        if (naam && voornaam && telefoon && email && aantal && datum && moment !==0 && gerecht !==0) {
            return true;
        }
        else {
            return false;
        }
    }

    function validatieVerkorter (variabele, selector, tekst) {
        if (!variabele || variabele === '0') {
            $('#'+selector).text(tekst).prev().children().addClass('border-danger');
        }
    }

    function validatie2(){
        validatieVerkorter(naam, 'meldingNaam', 'Geef uw naam in!');
        validatieVerkorter(voornaam, 'meldingVoornaam', 'Geef uw voornaam in!');
        validatieVerkorter(telefoon, 'meldingTelefoon', 'Geef uw telefoonnummer in!');
        validatieVerkorter(email, 'meldingEmail', 'Geef uw e-mail in!');
        validatieVerkorter(aantal, 'meldingAantal', 'Geef het aantal aanwezigen in!');
        validatieVerkorter(datum, 'meldingDatum', 'Geef een datum op!');
        validatieVerkorter(moment, 'meldingMoment', 'Duid aan welk moment u verkiest!');
        validatieVerkorter(gerecht, 'meldingGerecht', 'Kies het gewenste gerecht!');

       if (email) {
           if (email.indexOf('@') === -1 || email.indexOf('.') === -1) {
               $('#meldingEmail').text('het email moet een \'@\' en een \'.\' bevatten').prev().addClass('border-danger');
           }
       }
    }

    function bevestigingsGegevens () {
        $('#naamM').text(voornaam + ' ' + naam);
        $('#telefoonM').text(telefoon);
        $('#emailM').text(email);
        $('#aantalM').text(aantal);
        $('#datumM').text(datum);
        $('#momentM').text($("#moment option[value='"+moment+"']").text());
        $('#gerechtM').text($("#gerecht option[value='"+gerecht+"']").text());
        if (opmerkingen) {
            $('#opmerkingenM').html('<b>Opmerkingen: </b><span>'+opmerkingen+'</span>');
        }
    }

    function getDagen () {
        $.ajax({
            type:"GET",
            url:site_url+ "/zwemfeest/dagenLaden",
            success: function (result) {
                test = result;
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function getSluitingsdagen(){
        $.ajax({
            type:"GET",
            url:site_url+ "/zwemfeest/sluitingsdagen",
            success: function (result) {
                sluiting = result;
                console.log(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        getSluitingsdagen();
        getDagen();
        var array = [eval(sluiting)];
        var today = new Date();

        $("#dp").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: today,
            beforeShowDay: function(date){
                console.log(test);
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [eval(test) && sluiting.indexOf(string) === -1]
            }
        });

        $('#momenten').on('change', "#moment", function () {
            $('#aanvraagAlert').hide();
            $('#meldingMoment').text('').prev().children().removeClass('border-danger');
        });

        $('#laden').hide();
        $('input, select').on("change", function () {
            $('#aanvraagAlert').hide();
            $(this).removeClass('border-danger').parent().next().text('');
        });

        $('#aanvraagAlert').hide();
        $('#submitZwemfeest').click(function () {
            if (validatie1()) {
                controleerBoeking(datum, moment);
            }
            else {
                validatie2();
            }
        });

        $('#aanvragen').click(function () {
            $('#controle').hide();
            $('#laden').show();
            zwemfeestAanvragen();
        });

        $('#dp').change(function() {
            datum = $(this).val();
            weekdag = $(this).datepicker('getDate').getUTCDay() + 1;
            if (weekdag == 7) {
                weekdag = 0;
            }
            console.log(weekdag);
            momentenLaden(weekdag);
        });
    });
</script>
<form id="aanvraagFormulier">
<h4>Vul onderstaande gegevens in:</h4>
<div class="alert alert-success" id="aanvraagAlert"></div>
<div >
    <div class="form-group">
    <?php
    echo form_label('Naam'); ?>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-user-circle"></i></span>
            </div>
            <?php echo form_input(array(
                'class' => 'form-control',
                'id' => 'naam'));?>
        </div>
    <div id="meldingNaam" class="ml-2 text-danger"></div>
</div>
<div class="form-group">
    <?php
    echo form_label('Voornaam'); ?>
    <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-user-circle"></i></span>
            </div>
            <?php  echo form_input(array(
                'id' => 'voornaam',
                'class' => 'form-control')); ?>
</div>

    <div id="meldingVoornaam" class="ml-2 text-danger"></div>
</div>
<div class="form-group">
    <?php echo form_label('Telefoon'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-phone"></i></span>
        </div>
        <?php echo form_input(array('type' => 'number',
            'id' => 'telefoon',
            'class' => 'form-control')); ?>
    </div>
    <div id="meldingTelefoon" class="ml-2 text-danger"></div>
</div>
<div class="form-group">
    <?php
    echo form_label('Email'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-envelope"></i></span>
        </div>
        <?php echo form_input(array(
        'id' => 'email',
        'class' => 'form-control')); ?>
    </div>
    <div id="meldingEmail" class="ml-2 text-danger"></div>
</div>
<div class="form-group">
    <?php
    echo form_label('Aantal aanwezigen'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-users"></i></span>
        </div>
        <?php  echo form_input(array('type' => 'number',
            'id' => 'aantal',
            'class' => 'form-control')); ?>
</div>

    <div id="meldingAantal" class="ml-2 text-danger"></div>
</div>
    <div class="form-group">
        <?php echo form_label('Datum'); ?>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend"><i class="far fa-calendar-alt"></i></span>
            </div>
            <?php echo form_input(array('class' => 'form-control', 'id' => 'dp')); ?>
        </div>
    <div id="meldingDatum" class="ml-2 text-danger"></div>
</div>
<div  id="momenten" class="form-group"></div>
<div class="form-group">
    <?php
    echo form_label('Gerecht'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-utensils"></i></span>
        </div>
        <?php  echo form_dropdown('gerecht', $gerechten, '',array('class' => 'form-control', 'id' => 'gerecht')); ?>
    </div>
    <div id="meldingGerecht" class="ml-2 text-danger"></div>
</div>
<div class="form-group" >
    <?php
    echo form_label('Opmerkingen'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-comments"></i></span>
        </div>
        <?php  echo form_textarea('opmerkingen', '', array('class' => 'form-control', 'id' => 'opmerkingen')); ?>
    </div>


</div>
<?php echo form_button(array('id' => 'submitZwemfeest', 'class' => 'btn btn-success mt-2', 'content' => 'Aanvragen')); ?>
</div>
</form>


<div class="modal fade" id="zwemfeestBevestiging" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bevestiging</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="controle">
                <div class="modal-body">
                    <p><b>Naam: </b> <span id="naamM"></span></p>
                    <p><b>Telefoon: </b> <span id="telefoonM"></span></p>
                    <p><b>E-mail: </b> <span id="emailM"></span></p>
                    <p><b>Aantal aanwezigen: </b> <span id="aantalM"></span></p>
                    <p><b>Datum: </b> <span id="datumM"></span></p>
                    <p><b>Moment: </b> <span id="momentM"></span></p>
                    <p><b>Gerecht: </b> <span id="gerechtM"></span></p>
                    <p id="opmerkingenM"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="aanvragen" class="btn btn-primary">Verzenden</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                </div>
            </div>
            <div id="laden">
                <div class="loader"></div>
            </div>
        </div>
    </div>
</div>


