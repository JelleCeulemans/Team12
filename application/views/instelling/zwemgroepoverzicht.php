<?php
/**
 * @file zwemgroepoverzicht.php
 */
?>
<script>
    function haalZwemgroepenOp() {
        $.ajax({
            type: "GET",
            url: site_url + "/instelling/haalAjaxOp_Zwemgroepen",
            success: function (result) {
                $("#lijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalGebruikersOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalZwemgroepOp(id) {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/haalJsonOp_Zwemgroep",
            data: {id: id},
            success: function (zwemgroep) {
                $("#idInvoerveld").val(zwemgroep.id);
                console.log($('#idInvoerveld').val());
                $("#weekdag").val(zwemgroep.weekdag);
                $("#startuur").val(zwemgroep.startuur.replace(".",":").toString());
                $("#stopuur").val(zwemgroep.stopuur.replace(".",":").toString());
                $("#niveau").val(zwemgroep.niveauId);
                $("#maximumAantal").val(zwemgroep.maximumAantal);

                resetValidatie();
                $("#knop").val("Wijzigen");

                $('#modalInvoer').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalGebruikerOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrijfZwemgroepWeg() {
        var dataString = $("#formInvoer").serialize();
        console.log(dataString);
        $.ajax({
            type: "POST",
            url: site_url + "/Instelling/schrijfAjax_Zwemgroep",
            data: dataString,
            success: function (result) {
                console.log(result);
                $('#modalInvoer').modal('hide');
                haalZwemgroepenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfZwemgroepWeg) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrapZwemgroep(id) {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/schrapAjax_Zwemgroep",
            data: {id: id},
            success: function (result) {
                $('#modalBevestiging').modal('hide');
                haalZwemgroepenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapSoort) --\n\n" + xhr.responseText);
            }
        });
    }

    function resetValidatie() {
        $("#formInvoer").removeClass("was-validated");
    }

    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
        haalZwemgroepenOp();

        $(".voegtoe").click(function () {

            $("#modaltitel").html("Nieuwe groep toevoegen");
            $("#idInvoerveld").val("0");
            $("#weekdag").val(0);
            $("#startuur").val(0);
            $("#stopuur").val(0);
            $("#niveau").val(1);
            $("#maximumAantal").val(0);

            resetValidatie();
            $("#knop").val("Toevoegen");
            $('#modalInvoer').modal('show');
        });

        $("#knop").click(function (e) {
            e.preventDefault();
            schrijfZwemgroepWeg();
        });

        $("#lijst").on('click', ".wijzig", function () {
            var id = $(this).data('zwemgroepid');
            console.log(id);
            $("#modaltitel").html("groep aanpassen")
            haalZwemgroepOp(id);
        });

        $("#lijst").on('click', ".schrap", function () {
            var id = $(this).data('zwemgroepid');
            $("#idBevestiging").val(id);
            $('#modalBevestiging').modal('show');
        });

        $("#knopNee").click(function () {
            $("#modalBevestiging").modal('hide');
        })

        $("#knopJa").click(function () {
            var id = $("#idBevestiging").val();
            schrapZwemgroep(id);
        });
    });
</script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <?php
    echo anchor("instelling/index", "Zwemgroepen", array("class"=>"navbar-brand p-3 border-right"));
    echo anchor("instelling/niveau", "Niveaus", array("class"=>"navbar-brand p-3 border-right"));
    echo anchor("instelling/gerecht", "Gerechten", array("class"=>"navbar-brand p-3 border-right"));
    echo anchor("instelling/nieuwsbericht", "Nieuwsberichten", array("class"=>"navbar-brand p-3 border-right"));
    echo anchor("instelling/sluitingsdagen", "Sluitingsdagen", array("class"=>"navbar-brand p-3 border-right"));
    ?>
</nav>
<div class="col-12 mt-3">
    <?php
    $knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "zwemgroep toevoegen");
    echo "<p>" . form_button('zwemgroepnieuw', "<i class='fas fa-plus'></i> Voeg toe", $knop) . "</p>";
    echo "<div id='lijst'></div>\n";
    ?>
</div>
<div class="modal fade" id="modalInvoer" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modaltitel"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modalresultaat">
                    <?php
                    echo haalJavascriptOp("bs_validator.js");
                    echo form_open('', array('id' => 'formInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation')) . "\n";
                    ?>
                    <input type="hidden" name="id" id="idInvoerveld">
                    <div class="form-group">
                        <?php echo form_label('Weekdag', 'weekdag'); ?>
                        <?php
                        echo form_dropdown(array('name' => 'weekdag',
                            'id' => 'weekdag',
                            'options' => $weekdagen = array("zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag"),
                            'class' => 'form-control',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Startuur', 'startuur'); ?>
                        <?php
                        echo form_input(array('name' => 'startuur',
                            'id' => 'startuur',
                            'class' => 'form-control',
                            'required' => 'required',
                            'type' => 'time',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul een tijdstip in van het formaat uu.mm</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Stopuur', 'stopuur'); ?>
                        <?php
                        echo form_input(array('name' => 'stopuur',
                            'id' => 'stopuur',
                            'type' => "time",
                            'class' => 'form-control',
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul een tijdstip in van het formaat uu.mm</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Niveau', 'niveau'); ?>
                        <?php
                        $opties = array();
                        foreach($niveaus as $niveau){
                            $opties[$niveau->id] = $niveau->naam;
                        }
                        echo form_dropdown(array('name' => 'niveau',
                            'id' => 'niveau',
                            'options' => $opties,
                            'class' => 'form-control',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Maximum aantal', 'maximumAantal'); ?>
                        <?php
                        echo form_input(array('name' => 'maximumAantal',
                            'id' => 'maximumAantal',
                            'type' => "number",
                            'min' => 0,
                            'class' => 'form-control',
                            'placeholder' => 0,
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul een positief getal in</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php
                echo form_submit('', '', array('id' => 'knop', 'class' => 'btn'));
                ?>
                <button type="button" class="btn" data-dismiss="modal">Sluit</button>
                <?php
                echo form_close()
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBevestiging" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bevestig!</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="idBevestiging">
                <div id="boodschap">Weet u zeker dat u deze zwemgroep wilt verwijderen? Alle zwemmers die in deze zwemgroep zitten, of in de wachtlijst staan zullen hier uit gezet worden.</div>
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