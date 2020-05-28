<?php
/**
 * @file overzicht.php
 */
?>
<script>

    var  voornaam, achternaam, username, email, ww1, ww2, telefoon, knop;
    function validatie1() {
        voornaam = $("#gebruikerVoornaam").val();
        achternaam = $("#gebruikerAchternaam").val();
        username = $("#gebruikerUsername").val();
        email = $("#gebruikerEmail").val();
        telefoon = $("#gebruikerTelefoon").val();
        ww1 = $("#gebruikerWW1").val();
        ww2 = $("#gebruikerWW2").val();
        knop = $("#knop").val();
        if(knop === "Wijzigen"){
            ww1 = $("#gebruikerWW1").val("geen");
            ww2 = $("#gebruikerWW2").val("geen");
        }
        if (voornaam && achternaam && username && email && telefoon && ww1 && ww2 !==0) {
            if (email.indexOf('@') === -1 || email.indexOf('.') === -1) {
                $('#meldingEmail').text('het email moet een \'@\' en een \'.\' bevatten').prev().addClass('border-danger');
            }else{
                return true;
            }
        }
        else {
            return false;
        }
    }

    function validateReset(){
        validatieVerkorterReset('meldingVoornaam');
        validatieVerkorterReset('meldingAchternaam');
        validatieVerkorterReset('meldingUsername');
        validatieVerkorterReset('meldingEmail');
        validatieVerkorterReset('meldingWw1');
        validatieVerkorterReset('meldingWw2');
        validatieVerkorterReset('meldingTelefoon');
    }

    function validatieVerkorterReset (selector) {
            $('#'+selector).text("").prev().children().removeClass('border-danger');
    }

    function validatieVerkorter (variabele, selector, tekst) {
        if (!variabele || variabele === '0') {
            $('#'+selector).text(tekst).prev().children().addClass('border-danger');
        }
    }

    function validatie2(){
        validateReset();
        validatieVerkorter(voornaam, 'meldingVoornaam', 'Geef voornaam in!');
        validatieVerkorter(achternaam, 'meldingAchternaam', 'Geef achternaam in!');
        validatieVerkorter(username, 'meldingUsername', 'Geef username in!');
        validatieVerkorter(email, 'meldingEmail', 'Geef email in!');
        validatieVerkorter(ww1, 'meldingWw1', 'Geef wachtwoord in!');
        validatieVerkorter(ww2, 'meldingWw2', 'Geef wachtwoord voor tweede keer in!');
        validatieVerkorter(telefoon, 'meldingTelefoon', 'Geef uw telefoonnummer in!');
    }

    function voegtoeLeeg(){
        $("#gebruikerIdInvoer").val("0"); //id van nieuwe soort (=0) invullen in hidden field van modal venster '#modalInvoer'
        $("#gebruikerVoornaam").val("");
        $("#gebruikerAchternaam").val("");
        $("#gebruikerUsername").val("");
        $("#gebruikerEmail").val("");
        $("#gebruikerTelefoon").val("");
        $("#gebruikerWW1").val("");
        $("#gebruikerWW2").val("");
    }

    function controledubbel(result) {
        if (result.length > '0') {
            var zin = result.split("|");
            if(zin[0] == "Deze username is al bezet!"){
                $('#aanvraagAlert1').text(zin[0]).show().removeClass().addClass('alert alert-danger');
            }else{
                $('#aanvraagAlert1').hide();
            }
            if(zin[1] == "Deze email is al bezet!"){
                $('#aanvraagAlert2').text(zin[1]).show().removeClass().addClass('alert alert-danger');
            }else{
                $('#aanvraagAlert2').hide();
            }
        }
        else {
            if(knop === "Wijzigen"){
                schrijfGebruikerWeg();
            }else{
                if(ww1 != ww2){
                    $('#aanvraagAlert1').text("Wachtwoorden komen niet overeen!").show().removeClass().addClass('alert alert-danger');
                }else{
                    schrijfGebruikerWeg();
                    $('#aanvraagAlert1').hide();

                }
            }
        }
    }

    function controleerGebruiker(username, email, knop) {
        var controle;
        $.ajax({
            type:"GET",
            url:site_url+ "/Gebruiker/controleGebruiker",
            data:{username: username, email: email, knop: knop},
            success: function (result) {
                controledubbel(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }


    function haalGebruikersOp() {
        $.ajax({
            type: "GET",
            url: site_url + "/Gebruiker/haalAjaxOp_Gebruikers",
            success: function (result) {
                $("#lijst").html(result);
                $('[data-toggle="tooltip"]').tooltip(); //bootstrap-tooltips activeren op dynamisch (mbv AJAX) gegenereerde content
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalGebruikersOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalGebruikerOp(gebruikerId) {
        $.ajax({
            type: "GET",
            url: site_url + "/Gebruiker/haalJsonOp_Gebruiker",
            data: {gebruikerId: gebruikerId},
            success: function (gebruiker) {
                $("#gebruikerIdInvoer").val(gebruiker.id);
                $("#gebruikerVoornaam").val(gebruiker.voornaam);
                $("#gebruikerAchternaam").val(gebruiker.achternaam);
                $("#gebruikerUsername").val(gebruiker.username);
                $("#gebruikerEmail").val(gebruiker.email);
                $("#gebruikerTelefoon").val(gebruiker.telefoon);
                $('#isBeheerder').prop('selectedIndex', parseInt(gebruiker.isBeheerder));
                $("#knop").val("Wijzigen");
                $('#modalInvoer').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalGebruikerOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrijfGebruikerWeg() {
        var dataString = $("#formInvoer").serialize();
        $.ajax({
            type: "POST", //POST ipv GET (informatie uit formulier)
            url: site_url + "/Gebruiker/schrijfAjax_Gebruiker",
            data: dataString,
            success: function (result) {
                $('#modalInvoer').modal('hide');
                haalGebruikersOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfSoortWeg) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrapSoort(gebruikerId) {
        $.ajax({
            type: "GET",
            url: site_url + "/Gebruiker/schrapAjax_Gebruiker",
            data: {gebruikerId: gebruikerId},
            success: function (result) {
                $('#modalBevestiging').modal('hide');
                haalGebruikersOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapSoort) --\n\n" + xhr.responseText);
            }
        });
    }



    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
        haalGebruikersOp();

        $('#aanvraagAlert1').hide();
        $('#aanvraagAlert2').hide();

        $(".voegtoe").click(function () {
            validateReset();
            $('#aanvraagAlert1').hide();
            $('#aanvraagAlert2').hide();
            voegtoeLeeg();
            $("#gebruikerWW1").show();
            $('label[for=gebruikerWW1]').show();
            $('label[for=gebruikerWW2]').show();
            $("#gebruikerWW2").show();
            $("#gebruikerUsername").removeAttr("readOnly");
            $("#gebruikerEmail").removeAttr("readOnly");
            //invoervenster voor naam van nieuwe soort leeg maken
            $("#knop").val("Toevoegen"); //tekst op knop aanpassen; knop is van input-type, dus met val() werken
            $('#modalInvoer').modal('show');
        });

        $("#lijst").on('click', ".schrap", function () {
            var gebruikerId = $(this).data('gebruikerid');
            $("#gebruikerIdBevestiging").val(gebruikerId);
            $('#modalBevestiging').modal('show');
        });

        $("#lijst").on('click', ".wijzig", function () {
            validateReset();
            $('#aanvraagAlert1').hide();
            $('#aanvraagAlert2').hide();
            var gebruikerId = $(this).data('gebruikerid');
            $("#gebruikerWW1").hide();
            $('label[for=gebruikerWW1]').hide();
            $('label[for=gebruikerWW2]').hide();
            $("#gebruikerWW2").hide();
            $("#meldingWw1").hide();
            $("#meldingWw2").hide();
            $("#gebruikerUsername").attr("readOnly", "True");
            $("#gebruikerEmail").attr("readOnly", "True");
            haalGebruikerOp(gebruikerId);
        });

        $("#gebruikerWW1").on('click', function () {
            console.log("jeej");
            $("#gebruikerWW1").val('');
            $("#gebruikerWW2").val('');
        });


        //Als men op (submit-)knop (met opschrift 'Wijzigen' of 'Toevoegen') klikt in modal venster '#modalInvoer'
        $("#knop").click(function (e) {
                e.preventDefault(); //niet opnieuw "submitten", anders werkt serialize() later niet mee
                if (validatie1()) {
                    controleerGebruiker(username, email,knop);
                }
                else {
                    validatie2();
                }
        });

        // //Als men op knop 'Ja' klikt in modal venster '#modalBevestiging'
        $("#knopJa").click(function () {
            var soortId = $("#gebruikerIdBevestiging").val(); //soortId uit hidden field van modal venster '#modalBevestiging' halen om te kunnen doorgeven aan schrapSoort()
            schrapSoort(soortId);
        });

        //Als men op knop 'Nee' klikt in modal venster '#modalBevestiging'
        $("#knopNee").click(function () {
            $("#modalBevestiging").modal('hide');
        });
    });

</script>

<div class="col-12 mt-3">
    <?php
    echo "<div id='lijst'></div>\n";
    $knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "Gebruiker toevoegen");
    echo "<p>" . form_button('gebruikernieuw', "<i class='fas fa-plus'></i> Voeg toe", $knop) . "</p>";
    ?>
</div>



<!-- Invoervenster -->
<div class="modal fade" id="modalInvoer" role="dialog">
    <div class="modal-dialog">

        <!-- Inhoud invoervenster-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Gebruiker toevoegen</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?php
            // echo haalJavascriptOp("bs_validator.js"); //bootstrap-validatie
            $attributenFormulier = array('id' => 'formInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation');
            echo form_open('', $attributenFormulier) . "\n";
            ?>
            <div class="modal-body">
              <div class="alert alert-success" id="aanvraagAlert1"></div>
              <div class="alert alert-success" id="aanvraagAlert2"></div>
                <input type="hidden" name="gebruikerId" id="gebruikerIdInvoer">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <?php echo form_label('Voornaam', 'gebruikerVoornaam'); ?>
                        <?php
                        echo form_input(array('name' => 'gebruikerVoornaam',
                            'id' => 'gebruikerVoornaam',
                            'class' => 'form-control',
                            'placeholder' => 'Voornaam',
                            'required' => 'required',
                        ));

                        ?><div id="meldingVoornaam" class="ml-2 text-danger"></div></div><div class="col-sm-6">
                        <?php echo form_label('Achternaam', 'gebruikerAchternaam'); ?>
                        <?php
                        echo form_input(array('name' => 'gebruikerAchternaam',
                            'id' => 'gebruikerAchternaam',
                            'class' => 'form-control',
                            'placeholder' => 'Achternaam',
                            'required' => 'required',
                        ));
                        ?>
                        <div id="meldingAchternaam" class="ml-2 text-danger"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php echo form_label('Username', 'gebruikerUsername'); ?>
                    <?php
                    echo form_input(array('name' => 'gebruikerUsername',
                        'id' => 'gebruikerUsername',
                        'class' => 'form-control',
                        'placeholder' => 'Username',
                        'required' => 'required',
                    ));
                    ?>
                    <div id="meldingUsername" class="ml-2 text-danger"></div>
                    <?php echo form_label('E-mailadres', 'gebruikerEmail'); ?>
                    <?php
                    echo form_input(array('name' => 'gebruikerEmail',
                        'id' => 'gebruikerEmail',
                        'class' => 'form-control',
                        'placeholder' => 'Emailadres',
                        'required' => 'required',
                    ));
                    ?>
                    <div id="meldingEmail" class="ml-2 text-danger"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <?php echo form_label('Wachtwoord', 'gebruikerWW1'); ?>
                        <?php
                        echo form_password(array('name' => 'gebruikerWW1',
                            'id' => 'gebruikerWW1',
                            'class' => 'form-control',
                            'placeholder' => 'Wachtwoord',
                            'required' => 'required',
                        ));

                        ?><div id="meldingWw1" class="ml-2 text-danger"></div>
                      </div>
                        <div class="col-sm-6">
                        <?php echo form_label('Wachtwoord', 'gebruikerWW2'); ?>
                        <?php
                        echo form_password(array('name' => 'gebruikerWW2',
                            'id' => 'gebruikerWW2',
                            'class' => 'form-control',
                            'placeholder' => 'Wachtwoord',
                            'required' => 'required',
                        ));
                        ?>
                            <div id="meldingWw2" class="ml-2 text-danger"></div>
                    </div>
                </div><div class="col-sm-6">

                    <?php echo form_label('Telefoonnummer', 'gebruikerTelefoon'); ?>
                    <?php
                    echo form_input(array('name' => 'gebruikerTelefoon',
                        'id' => 'gebruikerTelefoon',
                        'class' => 'form-control',
                        'placeholder' => '04xxxxxxxx',
                        'required' => 'required',
                        'type' => 'number',
                    ));

                    ?>
                    <div id="meldingTelefoon" class="ml-2 text-danger"></div>
                    <?php
                    $rechten = array();
                    $rechten[0] = 'Zwemleerkracht';
                    $rechten[1] = 'Administrator';
                    echo form_label('Rol', 'isBeheerder');
                    echo form_dropdown(array('name' => 'isBeheerder',
                        'id' => 'isBeheerder',
                        'options' => $rechten,
                        'class' => 'form-control',
                    ));
                    ?>

                    <div id="meldingBeheerder" class="ml-2 text-danger"></div>
                </div>
                <div class="invalid-feedback">Vul dit veld in</div>
            </div>
            <div class="modal-footer">
                <?php
                //met input-type = submit werken; anders (met button) werkt validatie niet
                //deze knop moet voor de form_close() staan!
                echo "<script>console.log();</script>";
                echo form_submit('', '', array('id' => 'knop', 'class' => 'btn'));
                ?>
            </div>
            <?php echo form_close() . "\n"; ?>
        </div>
    </div>
</div>

<!-- Modal dialog Bevestiging -->
<div class="modal fade" id="modalBevestiging" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bevestig!</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="gebruikerIdBevestiging">
                <div id="boodschap">Wilt u zeker deze gebruiker verwijderen?</div>
            </div>
            <div class="modal-footer">
                <?php echo form_button(array('content' => "Ja", 'id' => 'knopJa', 'class' => 'btn btn-danger'));
                ?>
                <?php echo form_button(array('content' => "Nee", 'id' => 'knopNee', 'class' => 'btn'));
                ?>
            </div>
        </div>
    </div>
</div>
