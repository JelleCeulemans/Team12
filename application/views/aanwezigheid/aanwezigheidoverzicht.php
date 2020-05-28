<?php
/**
 * @file aanwezigheidoverzicht.php
 */
?>
<script>
    function haalAanwezighedenOp() {
        $.ajax({
            type: "GET",
            url: site_url + "/Aanwezigheid/haalAjaxOp_Aanwezigheden/<?php echo $klasid ?>",
            success: function (result) {
                $("#lijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalGebruikersOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrapAanwezigheid(id) {
        $.ajax({
            type: "GET",
            url: site_url + "/Aanwezigheid/schrapAjax_Aanwezigheid",
            data: {id: id},
            success: function (result) {
                $('#modalBevestiging').modal('hide');
                haalAanwezighedenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapSoort) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalAanwezigheidOp(id) {
        $.ajax({
            type: "GET",
            url: site_url + "/Aanwezigheid/haalJsonOp_Aanwezigheid",
            data: {id: id},
            success: function (aanwezigheid) {
                $("#idInvoerveld").val(aanwezigheid.id);
                console.log($('#idInvoerveld').val());
                $("#datum").val(aanwezigheid.datum);
                $("#aantal").val(aanwezigheid.aantal);
                resetValidatie();
                $("#knop").val("Wijzigen");
                $('#modalInvoer').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalGebruikerOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrijfAanwezigheidWeg() {
        var dataString = $("#formInvoer").serialize();
        console.log(dataString);
        $.ajax({
            type: "POST",
            url: site_url + "/Aanwezigheid/schrijfAjax_Aanwezigheid",
            data: dataString,
            success: function (result) {
                console.log(result);
                $('#modalInvoer').modal('hide');
                haalAanwezighedenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfAanwezigheidWeg) --\n\n" + xhr.responseText);
            }
        });
    }

    function resetValidatie() {
        $("#formInvoer").removeClass("was-validated");
    }

    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
        haalAanwezighedenOp();

        $(".voegtoe").click(function () {
            $("#idInvoerveld").val("0");
            $("#datum").val("");
            $("#aantal").val(0);

            resetValidatie();
            $("#knop").val("Toevoegen");
            $('#modalInvoer').modal('show');
        });

        $("#lijst").on('click', ".wijzig", function () {
            var id = $(this).data('zwemtotaalid');
            console.log(id);
            haalAanwezigheidOp(id);
        });

        $("#knop").click(function (e) {
            e.preventDefault();
            schrijfAanwezigheidWeg();
        });

        $("#lijst").on('click', ".schrap", function () {
            var id = $(this).data('zwemtotaalid');
            $("#idBevestiging").val(id);
            $('#modalBevestiging').modal('show');
        });

        $("#knopNee").click(function () {
            $("#modalBevestiging").modal('hide');
        })

        $("#knopJa").click(function () {
            var id = $("#idBevestiging").val();
            schrapAanwezigheid(id);
        });

    });
</script>
<div class="col-12 mt-3">
    <?php
    $knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "Aanwezigheid toevoegen");
    echo "<p>" . form_button('aanwezigheidnieuw', "<i class='fas fa-plus'></i> Voeg toe", $knop) . "</p>";
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
                    <input type="hidden" name="klasId" value="<?php echo $klasid ?>">
                    <div class="form-group">
                        <?php echo form_label('Datum', 'datum'); ?>
                        <?php
                        echo form_input(array('name' => 'datum',
                            'id' => 'datum',
                            'type' => 'date',
                            'class' => 'form-control',
                            'placeholder' => 'Datum',
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Aantal', 'aantal'); ?>
                        <?php
                        echo form_input(array('name' => 'aantal',
                            'id' => 'aantal',
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
                <div id="boodschap">Weet u zeker dat u deze aanwezigheid wilt verwijderen?</div>
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