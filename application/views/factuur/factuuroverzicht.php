<?php
/**
 * @file factuuroverzicht.php
 */
?>
<script>
    function haalFacturenOp() {
        $.ajax({
            type: "GET",
            url: site_url + "/Factuur/haalAjaxOp_Facturen/<?php echo $schoolid ?>",
            success: function (result) {
                $("#lijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalFacturenOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrapFactuur(factuurid){
        $.ajax({
            type: "POST",
            url: site_url + "/Factuur/schrapAjax_Factuur/" + factuurid,

            success: function (result) {
                haalFacturenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapFactuur) --\n\n" + xhr.responseText);
            }
        });
    }

    function toonMomenten(){
        $.ajax({
            type: "GET",
            url: site_url + "/Factuur/haalAjaxOp_Momenten/<?php echo $schoolid ?>",
            success: function (result) {
                $("#momentlijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
                $('#modalMomenten').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalMomentenOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function genereerFactuur() {
        var dataString = $("#formFactuurInvoer").serialize();

        $.ajax({
            type: "POST",
            url: site_url + "/Factuur/schrijfAjax_Factuur/",
            data: dataString,

            success: function (result) {
                $('#modalMomenten').modal('hide');
                haalFacturenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfFactuur) --\n\n" + xhr.responseText);
            }
        });
    }

    function resetValidatie() {
        $("#formInvoer").removeClass("was-validated");
    }

    function controlleerMomenten(){
        $.ajax({
            type: "GET",
            url: site_url + "/Factuur/controlleerMomenten/<?php echo $schoolid ?>",
            success: function (result) {
                if(result != 0){
                    toonMomenten()
                }
                else{
                    $('#modalBevestig').modal('show');
                }
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (controlleerMoment) --\n\n" + xhr.responseText);
            }
        });
    }



    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();
        haalFacturenOp();

        $(".voegtoe").click(function () {
            controlleerMomenten()

            resetValidatie();
        });

        $(".voegtoe").click(function () {
            controlleerMomenten()

            resetValidatie();
        });

        $("#knopGenereren").click(function () {
            genereerFactuur();
        });

        $("#lijst").on('click', ".schrap", function(){
            var factuurid = $(this).data('factuurid');
            schrapFactuur(factuurid);
        });
    });
</script>
<div class="col-12 mt-3">
    <?php
    $knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "Nieuwe factuur genereren");
    echo "<p>" . form_button('factuurnieuw', "<i class='fas fa-plus'></i> Nieuwe factuur genereren", $knop) . "</p>";
    echo "<div id='lijst'></div>\n";
    ?>
</div>
<div class="modal fade" id="modalMomenten" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Genereer nieuwe factuur</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div id="momentlijst"></div>
                <div>
                    <?php
                    echo haalJavascriptOp("bs_validator.js");
                    echo form_open('', array('id' => 'formFactuurInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation')) . "\n";
                    ?>
                    <input type="hidden" name="id" id="schoolId" value="<?php echo $schoolid ?>">
                    <div class="form-group">
                        <?php echo form_label('Prijs', 'prijs'); ?>
                        <?php
                        echo form_input(array('name' => 'prijs',
                            'id' => 'prijs',
                            'type' => "double",
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
                <?php echo form_button(array('content' => "Genereren", 'id' => 'knopGenereren', 'class' => 'btn'));
                ?>
            </div>
            <?php
            echo form_close()
            ?>
        </div>
    </div>
</div>
<div class="modal fade" id="modalBevestig" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Foutmelding</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div><p>Er zijn geen niet-gefactureerde momenten. Ga terug naar scholen beheren om momenten toe te voegen.</p></div>

            </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>