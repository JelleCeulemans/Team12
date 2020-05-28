<?php
/**
 * @file overzicht.php
 */
?>
<script>

    function controleerDubbelEnSchrijfWegOfToonFout() {
        var isDubbel = false;
        var dataString = $("#formInvoer").serialize(); //invoer uit formulier omzetten in juiste formaat (URL-encoded)

        $.ajax({
            type: "POST", //POST ipv GET (informatie uit formulier)
            url: site_url + "/Zwemmer/controleerJson_DubbelZwemmer",
            data: dataString,
            success: function (result) {
                schrijfZwemmerWeg();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (controleerDubbelEnSchrijfWegOfToonFout) --\n\n" + xhr.responseText);
                return true;
            }
        });
    }



    //jQuery-functie die via AJAX-call alle records uit de tabel bier_zwemmer (met een knop 'Wijzig' en 'Verwijder')
    //weergeeft in de daarvoor voorziene <div> in de CRUD.
    function haalZwemmersOp() {
        $.ajax({
            type: "GET",
            url: site_url + "/zwemmer/haalAjaxOp_Zwemmers",
            success: function (result) {
                $("#lijst").html(result);
                $('[data-toggle="tooltip"]').tooltip(); //bootstrap-tooltips activeren op dynamisch (mbv AJAX) gegenereerde content
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalZwemmersOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalZwemmerOp(zwemmerId) {
        $.ajax({
            type: "GET",
            url: site_url + "/Zwemmer/haalJsonOp_Zwemmer",
            data: {zwemmerId: zwemmerId},
            success: function (zwemmer) {
                //id van soort invullen in hidden field van modal venster '#modalInvoer'
                //om later te kunnen "doorgeven" aan functie controleerDubbelEnSchrijfWegOfToonFout()

                //console.log($('#zwemmerWachtwoord').val());
                //$("#zwemmerIdInvoer").val(zwemmer.id);
                $("#zwemmerIdInvoer").val(zwemmer.id);
                $("#zwemmerVoornaam").val(zwemmer.voornaam);
                $("#zwemmerAchternaam").val(zwemmer.achternaam);
                $("#zwemmerEmail").val(zwemmer.email);
                $("#zwemmerTelefoon").val(zwemmer.telefoon);
                $("#zwemmerNiveau").val(zwemmer.niveauId);
                $("#zwemmerGeboorte").prop("value", zwemmer.geboortedatum);
                $("#zwemmerInschrijf").prop("value", zwemmer.inschrijfdatum);
                resetValidatie();
                $("#knop").val("Wijzigen");  //tekst op knop aanpassen; knop is van input-type, dus met val() werken
                $('#modalInvoer').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalZwemmerOp) --\n\n" + xhr.responseText);
            }
        });
    }

    //jQuery-functie die de validatie (bootstrap validatie én zelf geschreven validatie op dubbele bierzwemmers) reset.
    function resetValidatie() {
        //bestaande bootstrap-validatie-info verwijderen (klasse 'was-validated' geeft aan dat validatie is gebeurd)
        $("#formInvoer").removeClass("was-validated");

        $("#naam").removeClass("is-invalid"); //zelf-toegevoegde validatie-info verwijderen
        $("div.invalid-feedback").html("Vul dit veld in!"); //originele tekst terug in div.invalid-feedback plaatsen
    }

    //jQuery-functie die via AJAX-call de record met id = zwemmerId verwijdert uit de tabel bier_zwemmer. Ook de
    //records uit de tabel bier_product met zwemmerId = zwemmerId worden verwijderd.
    //Daarna wordt de aangepaste lijst bierzwemmers getoond via een oproep van de jQuery-functie haalZwemmersOp()
    //Deze functie wordt opgeroepen nadat er in het modal venster '#modalBevestiging' op 'Ja' is gedrukt.
    function schrapZwemmer(zwemmerId) {
        $.ajax({
            type: "GET",
            url: site_url + "/zwemmer/schrapAjax_Zwemmer",
            data: {zwemmerId: zwemmerId},
            success: function (result) {
                $('#modalBevestiging').modal('hide');
                haalZwemmersOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapZwemmer) --\n\n" + xhr.responseText);
            }
        });
    }

    //jQuery-functie die via AJAX/JSON-call de record met id = zwemmerId uit de tabel bier_zwemmer
    // updatet (als zwemmerId verschillend is van 0) of een nieuwe record toevoegt (als zwemmerId = 0).
    //Daarna wordt de aangepaste lijst bierzwemmers getoond via een oproep van de jQuery-functie haalZwemmersOp().
    //Deze functie wordt opgeroepen vanuit de functie controleerDubbelEnSchrijfWegOfToonFout().
    function schrijfZwemmerWeg() {
        var dataString = $("#formInvoer").serialize(); //zie ook functie controleerDubbelEnSchrijfWegOfToonFout()

        $.ajax({
            type: "POST", //POST ipv GET (informatie uit formulier)
            url: site_url + "/zwemmer/schrijfAjax_Zwemmer",
            data: dataString,
            success: function (result) {
                $('#modalInvoer').modal('hide');
                haalZwemmersOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfZwemmerWeg) --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip(); //bootstrap-tooltips activeren

        haalZwemmersOp();

        $(".voegtoe").click(function () {
            $("#zwemmerIdInvoer").val("0"); //id van nieuwe zwemmer (=0) invullen in hidden field van modal venster '#modalInvoer'
            $("#naam").val(""); //invoervenster voor naam van nieuwe zwemmer leeg maken
            resetValidatie();

            $("#knop").val("Toevoegen"); //tekst op knop aanpassen; knop is van input-type, dus met val() werken
            $('#modalInvoer').modal('show');
        });

        $("#lijst").on('click', ".wijzig", function () {
            var zwemmerId = $(this).data('zwemmerid');
            haalZwemmerOp(zwemmerId);
        });

        //Als men in de CRUD naast een bierzwemmer op knop 'Verwijder' klikt
        //met on werken omdat lijst bierzwemmers dynamisch (mbv Ajax) wordt gegenereerd
        //https://1itf.gitbook.io/jquery/6-events/de-methode-on
        $("#lijst").on('click', ".schrap", function () {
            var zwemmerId = $(this).data('zwemmerid');
            $('#modalBevestiging').modal('show');
            $('#zwemmerIdBevestiging').val(zwemmerId)
        });

        //Als men op (submit-)knop (met opschrift 'Wijzigen' of 'Toevoegen') klikt in modal venster '#modalInvoer'
        $("#knop").click(function (e) {
            if ($("#formInvoer")[0].checkValidity()) { //controleer standaard bootstrap-validatie (leeg naam-veld)
                e.preventDefault(); //niet opnieuw "submitten", anders werkt serialize() later niet meer
                controleerDubbelEnSchrijfWegOfToonFout();
            }
        });

        //Als men op knop 'Ja' klikt in modal venster '#modalBevestiging'
        $("#knopJa").click(function () {
            var zwemmerId = $("#zwemmerIdBevestiging").val(); //zwemmerId uit hidden field van modal venster '#modalBevestiging' halen om te kunnen doorgeven aan schrapZwemmer()
            schrapZwemmer(zwemmerId);
        });

        //Als men op knop 'Nee' klikt in modal venster '#modalBevestiging'
        $("#knopNee").click(function () {
            $("#modalBevestiging").modal('hide');
            haalZwemmersOp();
        });

        //Validatie resetten als invoervak voor naam terug de focus krijgt
        $("#naam").focus(function () {
            resetValidatie();
        });

        //Validatie resetten als toets wordt ingedrukt in invoervak voor naam
        $("#naam").keydown(function () {
            resetValidatie();
        });
    });

</script>

<div class="col-12 mt-3">
    <?php
    echo "<div id='lijst'></div>\n";
    $knop = array("class" => "btn btn-success text-white", "data-toggle" => "tooltip", "title" => "Zwemmer toevoegen");
    echo "<p>" . anchor('zwemles/index', '<i class=\'fas fa-plus\'></i> Voeg toe', $knop) . "</p>";
    ?>
</div>

<!-- Invoervenster -->
<div class="modal fade" id="modalInvoer" role="dialog">
    <div class="modal-dialog">

        <!-- Inhoud invoervenster-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Zwemmer</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?php
            echo haalJavascriptOp("bs_validator.js"); //bootstrap-validatie
            $attributenFormulier = array('id' => 'formInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation');
            echo form_open('', $attributenFormulier) . "\n";
            ?>
            <div class="modal-body">

                <input type="hidden" name="zwemmerId" id="zwemmerIdInvoer">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <?php echo form_label('Voornaam', 'zwemmerVoornaam'); ?>
                        <?php
                        echo form_input(array('name' => 'zwemmerVoornaam',
                            'id' => 'zwemmerVoornaam',
                            'class' => 'form-control',
                            'placeholder' => 'Naam',
                            'required' => 'required',
                        ));

                        ?></div><div class="col-sm-6">
                        <?php echo form_label('Achternaam', 'zwemmerAchternaam'); ?>
                        <?php
                        echo form_input(array('name' => 'zwemmerAchternaam',
                            'id' => 'zwemmerAchternaam',
                            'class' => 'form-control',
                            'placeholder' => 'Naam',
                            'required' => 'required',
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <?php echo form_label('E-mailadres', 'zwemmerEmail'); ?>
                        <?php
                        echo form_input(array('name' => 'zwemmerEmail',
                            'id' => 'zwemmerEmail',
                            'class' => 'form-control',
                            'placeholder' => 'E-mail',
                            'required' => 'required',
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <?php echo form_label('Telefoon', 'zwemmerTelefoon'); ?>
                        <?php
                        echo form_input(array('name' => 'zwemmerTelefoon',
                            'id' => 'zwemmerTelefoon',
                            'class' => 'form-control',
                            'placeholder' => 'Telefoon',
                            'required' => 'required',
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <?php
                        echo form_label('Zwemervaring', 'zwemmerNiveau');

                        echo form_dropdown(array('id' => 'zwemmerNiveau',
                                'name' => 'zwemmerNiveau',
                                'class' => 'form-control',
                                'placeholder' => 'zwemervaring'), $niveaus);
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <?php
                        echo form_label('Geboortedatum', 'zwemmerGeboorte');

                        echo form_input(array('id' => 'zwemmerGeboorte',
                            'name' => 'zwemmerGeboorte',
                            'class' => 'form-control',
                            'placeholder' => 'geboortedatum',
                            'type' => 'date',
                            'required' => 'required',
                            'max' => Date("Y-m-d")
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <?php
                        echo form_label('Inschrijfdatum', 'zwemmerInschrijf');

                        echo form_input(array('id' => 'zwemmerInschrijf',
                            'name' => 'zwemmerInschrijf',
                            'class' => 'form-control',
                            'placeholder' => 'inschrijfdatum',
                            'type' => 'datetime-local',
                            'disabled' => 'disabled'
                        ));
                        ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <?php
                //met input-type = submit werken; anders (met button) werkt validatie niet
                //deze knop moet voor de form_close() staan!
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
                <input type="hidden" id="zwemmerIdBevestiging">
                <div id="boodschap">Wilt u zeker deze zwemmer verwijderen?</div>
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