<?php
/**
 * @file nieuwsberichtoverzicht.php
 */
?>
<script>
    function haalNieuwsberichtenOp() {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/haalAjaxOp_Nieuwsberichten",
            success: function (result) {
                $("#lijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalNieuwsberichtenOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalNieuwsberichtOp(id) {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/haalJsonOp_Nieuwsbericht",
            data: {id: id},
            success: function (nieuws) {
                $("#idInvoerveld").val(nieuws.id);
                $("#titel").val(nieuws.titel);
                $("#inhoud").val(nieuws.inhoud);
                resetValidatie();

                $("#knop").val("Wijzigen");
                $('#modalInvoer').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalGerechtOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrijfNieuwsberichtWeg() {
        var dataString = $("#formInvoer").serialize();
        console.log(dataString);
        $.ajax({
            type: "POST",
            url: site_url + "/Instelling/schrijfAjax_Nieuwsbericht",
            data: dataString,
            success: function (result) {
                console.log(result);
                $('#modalInvoer').modal('hide');
                haalNieuwsberichtenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfNieuwsberichtWeg) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrapNieuwsbericht(id) {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/schrapAjax_Nieuwsbericht",
            data: {id: id},
            success: function (result) {
                $('#modalBevestiging').modal('hide');
                haalNieuwsberichtenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapNieuwsbericht) --\n\n" + xhr.responseText);
            }
        });
    }

    function resetValidatie() {
        $("#formInvoer").removeClass("was-validated");
    }

    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
        haalNieuwsberichtenOp();

        $(".voegtoe").click(function () {
            $("#modaltitel").html("Nieuwsbericht toevoegen");
            $("#idInvoerveld").val("0");
            $("#titel").val("");
            $("#inhoud").val("");

            resetValidatie();
            $("#knop").val("Toevoegen");
            $('#modalInvoer').modal('show');
        });

        $("#knop").click(function (e) {
            e.preventDefault();
            schrijfNieuwsberichtWeg();
        });

        $("#lijst").on('click', ".wijzig", function () {
            var id = $(this).data('nieuwsberichtid');
            console.log(id);
            $("#modaltitel").html("Nieuwsbericht aanpassen");
            haalNieuwsberichtOp(id);
        });

        $("#lijst").on('click', ".schrap", function () {
            var id = $(this).data('nieuwsberichtid');
            $("#idBevestiging").val(id);
            $('#modalBevestiging').modal('show');
        });

        $("#knopNee").click(function () {
            $("#modalBevestiging").modal('hide');
        })

        $("#knopJa").click(function () {
            var id = $("#idBevestiging").val();
            schrapNieuwsbericht(id);
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
    $knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "nieuwsbericht toevoegen");
    echo "<p>" . form_button('nieuwsberichtnieuw', "<i class='fas fa-plus'></i> Voeg toe", $knop) . "</p>";
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
                        <?php echo form_label('Titel', 'titel'); ?>
                        <?php
                        echo form_input(array('name' => 'titel',
                            'id' => 'titel',
                            'type' => 'text',
                            'class' => 'form-control',
                            'placeholder' => 'Titel',
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Inhoud', 'inhoud'); ?>
                        <?php
                        echo form_textarea(array('name' => 'inhoud',
                            'id' => 'inhoud',
                            'type' => "text",
                            'cols' => 40,
                            'rows' => 4,
                            'class' => 'form-control',
                            'placeholder' => "",
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
                <div id="boodschap">Weet u zeker dat u dit nieuwtje wilt verwijderen?</div>
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