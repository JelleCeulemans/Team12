<?php
/**
 * @file niveauoverzicht.php
 */
?>
<script>
    function haalNiveausOp() {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/haalAjaxOp_Niveaus",
            success: function (result) {
                $("#lijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalNiveausOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalNiveauOp(id) {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/haalJsonOp_Niveau",
            data: {id: id},
            success: function (niveau) {
                $("#idInvoerveld").val(niveau.id);
                $("#naam").val(niveau.naam);
                $("#prijs").val(niveau.prijs);
                resetValidatie();

                $("#knop").val("Wijzigen");
                $('#modalInvoer').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalNiveauOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrijfNiveauWeg() {
        var dataString = $("#formInvoer").serialize();
        console.log(dataString);
        $.ajax({
            type: "POST",
            url: site_url + "/Instelling/schrijfAjax_Niveau",
            data: dataString,
            success: function (result) {
                console.log(result);
                $('#modalInvoer').modal('hide');
                haalNiveausOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfNiveauWeg) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrapNiveau(id) {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/schrapAjax_Niveau",
            data: {id: id},
            success: function (result) {
                $('#modalBevestiging').modal('hide');
                haalNiveausOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapNiveau) --\n\n" + xhr.responseText);
            }
        });
    }

    function resetValidatie() {
        $("#formInvoer").removeClass("was-validated");
    }

    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
        haalNiveausOp();

        $(".voegtoe").click(function () {
            $("#modaltitel").html("Nieuw niveau toevoegen");
            $("#idInvoerveld").val("0");
            $("#naam").val("");
            $("#prijs").val(0);

            resetValidatie();
            $("#knop").val("Toevoegen");
            $('#modalInvoer').modal('show');
        });

        $("#knop").click(function (e) {
            e.preventDefault();
            schrijfNiveauWeg();
        });

        $("#lijst").on('click', ".wijzig", function () {
            var id = $(this).data('niveauid');
            console.log(id);
            $("#modaltitel").html("Niveau aanpassen");
            haalNiveauOp(id);
        });

        $("#lijst").on('click', ".schrap", function () {
            var id = $(this).data('niveauid');
            $("#idBevestiging").val(id);
            $('#modalBevestiging').modal('show');
        });

        $("#knopNee").click(function () {
            $("#modalBevestiging").modal('hide');
        })

        $("#knopJa").click(function () {
            var id = $("#idBevestiging").val();
            schrapNiveau(id);
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
    $knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "niveau toevoegen");
    echo "<p>" . form_button('niveaunieuw', "<i class='fas fa-plus'></i> Voeg toe", $knop) . "</p>";
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
                        <?php echo form_label('Naam', 'naam'); ?>
                        <?php
                        echo form_input(array('name' => 'naam',
                            'id' => 'naam',
                            'type' => 'string',
                            'class' => 'form-control',
                            'placeholder' => 'Naam',
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Prijs', 'prijs'); ?>
                        <?php
                        echo form_input(array('name' => 'prijs',
                            'id' => 'prijs',
                            'type' => "number",
                            'step' => "0.01",
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
                <div id="boodschap">Weet u zeker dat u dit niveau wilt verwijderen? De zwemmers en zwemmomenten met dit niveau zullen ook verwijderd worden.</div>
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