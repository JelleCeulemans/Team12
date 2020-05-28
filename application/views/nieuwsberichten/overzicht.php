<?php
/**
 * @file overzicht.php
 */
?>
<script>



    function haalNieuwsberichtenOp() {
        $.ajax({
            type: "GET",
            url: site_url + "/nieuwsberichten/haalAjaxOp_Nieuwsberichten",
            success: function (result) {
                $("#lijst").html(result);
                $('[data-toggle="tooltip"]').tooltip(); //bootstrap-tooltips activeren op dynamisch (mbv AJAX) gegenereerde content
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalNieuwsberichtenOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function resetValidatie() {
        //bestaande bootstrap-validatie-info verwijderen (klasse 'was-validated' geeft aan dat validatie is gebeurd)
        $("#formInvoer").removeClass("was-validated");

        $("#naam").removeClass("is-invalid"); //zelf-toegevoegde validatie-info verwijderen
        $("div.invalid-feedback").html("Vul dit veld in!"); //originele tekst terug in div.invalid-feedback plaatsen
    }

    function haalNieuwsberichtOp(nieuwsberichtId) {
        $.ajax({
            type: "GET",
            url: site_url + "/Nieuwsberichten/haalJsonOp_Nieuwsbericht",
            data: {nieuwsberichtId: nieuwsberichtId},
            success: function (nieuwsbericht) {
                $("#nieuwsberichtIdInvoer").val(nieuwsbericht.id);

                $("#nieuwsberichtNaam").val(nieuwsbericht.naam);
                $("#nieuwsberichtDatum").val(nieuwsbericht.datum);

                resetValidatie();
                $("#knop").val("Wijzigen");  //tekst op knop aanpassen; knop is van input-type, dus met val() werken
                $('#modalInvoer').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalNieuwsberichtOp) --\n\n" + xhr.responseText);
            }
        });
    }


    function schrijfNieuwsberichtWeg() {
        var dataString = $("#nieuwtjeInvoer").serialize(); //zie ook functie controleerDubbelEnSchrijfWegOfToonFout()
        console.log(dataString);
        $.ajax({
            type: "POST", //POST ipv GET (informatie uit formulier)
            url: site_url + "/nieuwsbericht/schrijfAjax_Nieuwsbericht",
            data: dataString,
            success: function (result) {
                console.log(result);
                $('#modalInvoer').modal('hide');
                haalNieuwsberichtenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfSoortWeg) --\n\n" + xhr.responseText);
            }
        });
    }


    function schrapNieuwsbericht(nieuwsberichtId) {
        $.ajax({
            type: "GET",
            url: site_url + "/Nieuwsberichten/schrapAjax_webinhoud",
            data: {nieuwsberichtId: nieuwsberichtId},
            success: function (result) {
                $('#modalDelete').modal('hide');
                haalNieuwsberichtenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapSoort) --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
        haalNieuwsberichtenOp();

        $(".voegtoe").click(function () {
            $("#nieuwsberichtIdInvoer").val("0"); //id van nieuwe soort (=0) invullen in hidden field van modal venster '#modalInvoer'

            $("#nieuwsberichtNaam").val("");
            $("#nieuwsberichtInhoud").val("");
            $("#nieuwsberichtDatum").val("");

            //invoervenster voor naam van nieuwe soort leeg maken
            resetValidatie();
            $("#knop").val("Toevoegen"); //tekst op knop aanpassen; knop is van input-type, dus met val() werken
            $('#modalInvoer').modal('show');
        });

        $("#lijst").on('click', ".schrap", function () {
            var nieuwsberichtId = $(this).data('nieuwsberichtenId');
            $("#nieuwsberichtIdBevestiging").val(nieuwsberichtId);
            $('#modalDelete').modal('show');
        });

        $("#lijst").on('click', ".wijzig", function () {
            var nieuwsberichtId = $(this).data('nieuwsberichtid');

            haalNieuwsberichtOp(nieuwsberichtId);
        });


        //Als men op (submit-)knop (met opschrift 'Wijzigen' of 'Toevoegen') klikt in modal venster '#modalInvoer'
        $("#knop").click(function (e) {
            if ($("#formInvoer")[0].checkValidity()) { //controleer standaard bootstrap-validatie (leeg naam-veld)
                e.preventDefault(); //niet opnieuw "submitten", anders werkt serialize() later niet meer
                controleerDubbelEnSchrijfWegOfToonFout();
            }
        });

        // //Als men op knop 'Ja' klikt in modal venster '#modalBevestiging'
        $("#knopJa").click(function () {
            var nieuwsberichtId = $("#nieuwsberichtIdBevestiging").val(); //soortId uit hidden field van modal venster '#modalBevestiging' halen om te kunnen doorgeven aan schrapSoort()
            schrapNieuwsbericht(nieuwsberichtId);
        });

        //Als men op knop 'Nee' klikt in modal venster '#modalBevestiging'
        $("#knopNee").click(function () {
            $("#modalDelete").modal('hide');
            haalSoortenOp();
        });

        //Validatie resetten als invoervak voor naam terug de focus krijgt
        $("#gebruikerUsername").focus(function () {
            resetValidatie();
        });

        //Validatie resetten als toets wordt ingedrukt in invoervak voor naam
        $("#gebruikerUsername").keydown(function () {
            resetValidatie();
        });
    });



</script>



<?php
echo "<div id='lijst'></div>\n";
$knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "Product toevoegen");
echo "<p>" . form_button('nieuwsberichtnieuw', "<i class='fas fa-plus'></i> Voeg toe", $knop) . "</p>";
?>

<div id='boekinglijst'>

</div>
<div class="col-12 mt-3">

</div>



<!-- Invoervenster -->
<div class="modal fade" id="modalInvoer" role="dialog">
    <div class="modal-dialog">

        <!-- Inhoud invoervenster-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nieuwsbericht toevoegen</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?php
            echo haalJavascriptOp("bs_validator.js"); //bootstrap-validatie
            $attributenFormulier = array('id' => 'formInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation');
            echo form_open('', $attributenFormulier) . "\n";
            ?>
            <div class="modal-body">
                <input type="hidden" name="nieuwsberichtId" id="nieuwsberichtIdInvoer">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <?php echo form_label('berichtNaam', 'berichtNaam'); ?>
                        <?php
                        echo form_input(array('name' => 'berichtNaam',
                            'id' => 'berichtNaam',
                            'class' => 'form-control',
                            'placeholder' => 'Naam',
                            'required' => 'required',
                        ));

                        ?></div><div class="col-sm-6">
                        <?php echo form_label('berichtInhoud', 'berichtInhoud'); ?>
                        <?php
                        echo form_input(array('name' => 'berichtInhoud',
                            'id' => 'berichtInhoud',
                            'class' => 'form-control',
                            'placeholder' => 'inhoud',
                            'required' => 'required',
                        ));
                        ?>
                    </div>

                <div class="invalid-feedback">Vul dit veld in</div>
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
<div class="modal fade" id="modalDelete" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bevestig!</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="nieuwsberichtIdBevestiging">
                <div id="boodschap">Wilt u zeker dit nieuwsbericht verwijderen?</div>
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

