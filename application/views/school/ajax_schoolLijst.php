<?php
/**
 * @file ajax_schoolLijst.php
 */
?>
<script>
    function verwijderKlas(klasId) {
        $.ajax({
            type: "GET",
            url: site_url + "/school/verwijderAjax_Klas",
            data: {klasId: klasId},
            success: function (result) {
                $('#modalKlasBevestiging').modal('hide');
                haalKlassenOp(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapSchool) --\n\n" + xhr.responseText);
            }
        });
    }

    function schrijfKlasWeg() {
        var dataString = $("#formKlasInvoer").serialize();

        $.ajax({
            type: "POST",
            url: site_url + "/school/schrijfAjax_Klas",
            data: dataString,
            success: function (result) {
                $('#modalklas').modal('hide');
                haalKlassenOp(result)
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfKlasWeg) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalKlassenOp(schoolId) {
        $.ajax({
            type: "GET",
            url: site_url + "/school/haalAjaxOp_Klassen/" + schoolId,
            success: function (result) {
                $(".klaslijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalKlassenOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalKlasOp(klasId) {
        $.ajax({
            type: "GET",
            url: site_url + "/school/haalJsonOp_Klas",
            data: {klasId: klasId},
            success: function (klas) {

                $("#schoolIdInvoer").val(klas.schoolId);
                $("#klasIdInvoer").val(klas.id);
                $("#leerjaar").val(klas.leerjaar);
                if(klas.isGesubsidieerd == 0){
                    $("#isGesubsidieerd").prop( "checked", false );
                }
                else{
                    $("#isGesubsidieerd").prop( "checked", true );
                }

                resetValidatie();

                $("#knopKlas").html("Wijzigen");
                $("#modaltitelKlas").html("Klas wijzigen");
                $('#modalklas').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalSchooltOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function resetValidatie() {
        $("#formSchoolInvoer").removeClass("was-validated");

        $("#naam").removeClass("is-invalid");
        $("div.invalid-feedback").html("Vul dit veld in!");
    }

    function controleerDubbelKlas() {
        if($("#leerjaar").val() != ""){
            var isDubbel = false;
            var dataString = $("#formKlasInvoer").serialize();

            $.ajax({
                type: "POST",
                url: site_url + "/school/controleerJson_DubbelKlas",
                data: dataString,
                success: function (result) {
                    isDubbel = result;
                    if (!isDubbel) {
                        schrijfKlasWeg();
                    }
                    else {
                        $("div.invalid-feedback").html("Deze Klas bestaat reeds!");
                        $("#leerjaar").addClass("is-invalid");
                    }
                },
                error: function (xhr, status, error) {
                    alert("-- ERROR IN AJAX (controleerDubbelEnSchrijfWegOfToonFout) --\n\n" + xhr.responseText);
                    return true;
                }
            });
        }
        else{
            $("div.invalid-feedback").html("Vul dit veld in!");
            $("#leerjaar").addClass("is-invalid");
        }

    }

    function controleerKlasEnVraagBevestiging(klasId) {
        $.ajax({
            type: "GET",
            url: site_url + "/school/haalJsonOp_KlasMetMomenten",
            data: {klasId: klasId},
            success: function (klas) {
                if (klas.momenten.length == 0) {
                    $("#boodschapKlas").html("<p>Bent u zeker dat u de klas <b>" + klas.leerjaar + "</b> wil verwijderen?</p>");
                }
                else {
                    $("#boodschapKlas").html("<p>Voor de klas <b>" + klas.leerjaar + "</b> bestaan er nog <b>" + klas.momenten.length
                        + "</b> momenten, namelijk:</p>");

                    var bericht = "<ul>";
                    for (var i = 0; i < klas.momenten.length; i++) {
                        bericht += "<li>" + klas.momenten[i].datum + "</li>\n";
                    }
                    bericht += "</ul>";
                    $("#boodschapKlas").append(bericht);
                    $("#boodschapKlas").append("<p>Bent u zeker dat u de klas <b>" + klas.leerjaar + "</b> (én de bovenstaande momenten) toch wil verwijderen?</p>");
                }
                $("#klasIdBevestiging").val(klasId);

                $('#modalKlasBevestiging').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (controleerKlasEnVraagBevestiging) --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $(".voegKlasToe").click(function () {

            var schoolId = $(this).data('schoolid');
            $("#schoolIdInvoer").val(schoolId);

            $("#klasIdInvoer").val("0");
            $("#leerjaar").val("");
            $("#isGesubsidieerd").prop( "checked", false );

            resetValidatie();

            $("#knopKlas").html("Toevoegen");
            $("#modaltitelKlas").html("Nieuwe Klas toevoegen");
            $('#modalklas').modal('show');
        });

        $("#knopKlas").click(function (e) {
            if ($("#formKlasInvoer")[0].checkValidity()) {
                e.preventDefault();
                controleerDubbelKlas();
            }
        });

        $(".klasknop").on("click" , function () {
            var schoolId = $(this).data('schoolid');
            haalKlassenOp(schoolId);
        });

        $(".klaslijst").on('click', ".wijzigklas", function () {
            var klasId = $(this).data('klasid');
            haalKlasOp(klasId);
        });

        $(".klaslijst").on('click', ".verwijderklas", function () {
            var klasId = $(this).data('klasid');
            controleerKlasEnVraagBevestiging(klasId);
        });

        $("#knopKlasJa").click(function () {
            var klasId = $("#klasIdBevestiging").val();
            verwijderKlas(klasId);
        });

        $("#knopKlasNee").click(function () {
            $("#modalKlasBevestiging").modal('hide');
        });

    });
</script>
<div class="col-12 mt-3" id="accordionScholen">
    <?php

    foreach ($scholen as $school){

        echo '<div class="card"><div class="card-header" id="' . $school->id . '">';

        echo '<div class="row"><div class="col">';
        echo '<h4><a data-schoolid="' . $school->id . '" data-toggle="collapse" class="klas klasknop" href="#collapse'.$school->id.'" value="'.$school->id.'">'.ucwords($school->naam).'</a></h4></div>';
        echo '<div class="col-4">';

        echo form_button("knopwijzigschool" . $school->id, "<i class=\"fas fa-edit\"></i> Wijzig", array('class' => 'btn btn-success wijzigschool', 'data-schoolid' => $school->id, 'data-toggle' => 'tooltip', "title" => "School wijzigen"));
        echo form_button("knopverwijderschool" . $school->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", array('class' => 'btn btn-danger verwijderschool', 'data-schoolid' => $school->id, 'data-toggle' => 'tooltip', "title" => "School verwijderen"));
        echo anchor("factuur/index/" . $school->id, "facturen", array("class" => "btn btn-info"));

        echo '</div></div></div>';



        echo '<div id="collapse'. $school->id .'" class="collapse" data-parent="#accordionScholen">';
        echo '<div class="card-body">';

        echo '<div class="row justify-content-center">';
        echo '<div class="col-12">';
        echo '<div class="row"><h5 class="col-6">Klassen</h5>';
        echo "<p>" . form_button('klasnieuw', "<i class='fas fa-plus'></i> Voeg toe", array("class" => "btn btn-warning text-white voegKlasToe", 'data-schoolid' => $school->id, "data-toggle" => "tooltip", "title" => "Klas toevoegen")) . "</p></div>";
        echo '<div class="klaslijst"></div>';




        echo '</div></div>';


        echo '</div></div></div>';
    }
    ?>

</div>

<div class="modal fade" id="modalklas" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modaltitelKlas"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?php
            echo haalJavascriptOp("bs_validator.js");
            echo form_open('', array('id' => 'formKlasInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation')) . "\n";
            ?>
            <div class="modal-body">
                <input type="hidden" name="klasId" id="klasIdInvoer">
                <input type="hidden" name="schoolId" id = "schoolIdInvoer">
                <div class="form-group">
                    <?php echo form_label('Leerjaar', 'leerjaar'); ?>
                    <?php
                    echo form_input(array('name' => 'leerjaar',
                        'id' => 'leerjaar',
                        'class' => 'form-control',
                        'placeholder' => 'Leerjaar',
                        'required' => 'required',
                    ));
                    ?>
                    <div class="invalid-feedback">Vul dit veld in</div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Is gesubsidiëerd', 'isGesubsidieerd'); ?>
                    <?php
                    echo form_checkbox(array(
                        'name'          => 'isGesubsidieerd[]',
                        'id'            => 'isGesubsidieerd',
                        'value'         => 'ja',
                        'checked'       => FALSE,
                        ));
                    ?>
                    <div class="invalid-feedback">Vul dit veld in</div>
                </div>
            </div>
            <div class="modal-footer">
                <?php
                echo form_button('', '', array('id' => 'knopKlas', 'class' => 'btn'));
                ?>
            </div>
            <?php echo form_close() . "\n"; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKlasBevestiging" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bevestig!</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="klasIdBevestiging">
                <div id="boodschapKlas"></div>
            </div>
            <div class="modal-footer">
                <?php echo form_button(array('content' => "Ja", 'id' => 'knopKlasJa', 'class' => 'btn btn-danger'));
                ?>
                <?php echo form_button(array('content' => "Nee", 'id' => 'knopKlasNee', 'class' => 'btn'));
                ?>
            </div>
        </div>
    </div>
</div>