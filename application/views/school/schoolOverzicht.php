<?php
/**
 * @file schoolOverzicht.php
 */
?>
<script>

    function schrijfSchoolWeg() {
        var dataString = $("#formSchoolInvoer").serialize();

        $.ajax({
            type: "POST",
            url: site_url + "/school/schrijfAjax_School",
            data: dataString,
            success: function (result) {
                $('#modalschool').modal('hide');
                haalScholenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrijfSchoolWeg) --\n\n" + xhr.responseText);
            }
        });
    }

    function verwijderSchool(schoolId) {
        $.ajax({
            type: "GET",
            url: site_url + "/school/verwijderAjax_School",
            data: {schoolId: schoolId},
            success: function (result) {
                $('#modalBevestiging').modal('hide');
                haalScholenOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapSchool) --\n\n" + xhr.responseText);
            }
        });
    }

    function nooitMeerTonen(loginId) {
        $.ajax({
            type: "GET",
            url: site_url + "/school/ajax_NooitMeerTonen",
            data: {loginId: loginId},
            success: function (result) {
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (schrapSchool) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalScholenOp() {
        $.ajax({
            type: "GET",
            url: site_url + "/school/haalAjaxOp_Scholen",
            success: function (result) {
                $("#schoollijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalScholenOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function haalSchoolOp(schoolId) {
        $.ajax({
            type: "GET",
            url: site_url + "/school/haalJsonOp_School",
            data: {schoolId: schoolId},
            success: function (school) {

                $("#schoolIdInvoerveld").val(schoolId);
                $("#naam").val(school.naam);
                $("#straat").val(school.straat);
                $("#huisnummer").val(school.huisnummer);
                $("#postcode").val(school.postcode);
                $("#telefoon").val(school.telefoon);
                $("#email").val(school.email);

                resetValidatie();

                $("#knopSchool").val("Wijzigen");
                $("#modaltitelSchool").html("School wijzigen");
                $('#modalschool').modal('show');
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

    function controleerDubbelSchool() {
        var isDubbel = false;
        var dataString = $("#formSchoolInvoer").serialize();

        $.ajax({
            type: "POST",
            url: site_url + "/school/controleerJson_DubbelSchool",
            data: dataString,
            success: function (result) {
                isDubbel = result;
                if (!isDubbel) {
                    schrijfSchoolWeg();
                }
                else {
                    $("div.invalid-feedback").html("Deze school bestaat reeds!");
                    $("#naam").addClass("is-invalid");
                }
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (controleerDubbelEnSchrijfWegOfToonFout) --\n\n" + xhr.responseText);
                return true;
            }
        });
    }

    function controleerSchoolEnVraagBevestiging(schoolId) {
        $.ajax({
            type: "GET",
            url: site_url + "/school/haalJsonOp_SchoolMetKlassen",
            data: {schoolId: schoolId},
            success: function (school) {
                if (school.klassen.length == 0) {
                    $("#boodschap").html("<p>Bent u zeker dat u de school <b>" + school.naam + "</b> wil verwijderen?</p>");
                }
                else {
                    $("#boodschap").html("<p>Voor de school <b>" + school.naam + "</b> bestaan er nog <b>" + school.klassen.length
                        + "</b> klassen, namelijk:</p>");

                    var bericht = "<ul>";
                    for (var i = 0; i < school.klassen.length; i++) {
                        bericht += "<li>" + school.klassen[i].leerjaar + "</li>\n";
                    }
                    bericht += "</ul>";
                    $("#boodschap").append(bericht);
                    $("#boodschap").append("<p>Bent u zeker dat u de school <b>" + school.naam + "</b> (én de bovenstaande klassen) toch wil verwijderen?</p>");
                }
                $("#schoolIdBevestiging").val(schoolId);

                $('#modalBevestiging').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (controleerSchoolEnVraagBevestiging) --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();

        haalScholenOp();

        if(<?php echo $videodemo->bekeken ?> == 0){
            $('#modalDemo').modal('show');
        }

        $(".voegSchoolToe").click(function () {

            $("#schoolIdInvoerveld").val("0");
            $("#naam").val("");
            $("#straat").val("");
            $("#huisnummer").val("");
            $("#postcode").val("");
            $("#telefoon").val("");
            $("#email").val("");

            resetValidatie();

            $("#knopSchool").val("Toevoegen");
            $("#modaltitelSchool").html("Nieuwe school toevoegen");
            $('#modalschool').modal('show');
        });

        $("#schoollijst").on('click', ".wijzigschool", function () {
            var schoolId = $(this).data('schoolid');
            haalSchoolOp(schoolId);
        });

        $("#schoollijst").on('click', ".verwijderschool", function () {
            var schoolId = $(this).data('schoolid');
            controleerSchoolEnVraagBevestiging(schoolId);
        });

        $("#knopSchool").click(function (e) {
            if ($("#formSchoolInvoer")[0].checkValidity()) {
                e.preventDefault();
                controleerDubbelSchool();
            }
        });

        $("#knopJa").click(function () {
            var schoolId = $("#schoolIdBevestiging").val();
            verwijderSchool(schoolId);
        });

        $("#knopNee").click(function () {
            $("#modalBevestiging").modal('hide');
            haalScholenOp();
        });
        $("#knopSluit").click(function () {
            $("#modalDemo").modal('hide');
        });

        $("#knopNooitTonen").click(function () {
            nooitMeerTonen(<?php echo $videodemo->loginId ?>)
            $("#modalDemo").modal('hide');
        });
    });
</script>

<div class="col-12 mt-3">
    <?php
    echo "<p>" . form_button('schoolnieuw', "<i class='fas fa-plus'></i> Voeg toe", array("class" => "btn btn-warning text-white voegSchoolToe", "data-toggle" => "tooltip", "title" => "School toevoegen")) . "</p>";

    echo "<div id='schoollijst'></div>\n";
    ?>
</div>
<div class="modal fade" id="modalschool" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modaltitelSchool"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modalresultaat">
                    <?php
                    echo haalJavascriptOp("bs_validator.js");
                    echo form_open('', array('id' => 'formSchoolInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation')) . "\n";
                    ?>
                    <input type="hidden" name="schoolId" id="schoolIdInvoerveld">
                    <div class="form-group">
                        <?php echo form_label('Naam', 'naam'); ?>
                        <?php
                        echo form_input(array('name' => 'schoolNaam',
                            'id' => 'naam',
                            'class' => 'form-control',
                            'placeholder' => 'Naam',
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Straat', 'straat'); ?>
                        <?php
                        echo form_input(array('name' => 'schoolStraat',
                            'id' => 'straat',
                            'class' => 'form-control',
                            'placeholder' => 'Straat',
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Huisnummer', 'huisnummer'); ?>
                        <?php
                        echo form_input(array('name' => 'schoolHuisnummer',
                            'id' => 'huisnummer',
                            'class' => 'form-control',
                            'placeholder' => 'Huisnummer',
                            'required' => 'required',
                        ));
                        ?>


                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Postcode', 'postcode'); ?>
                        <?php
                        echo form_input(array('name' => 'schoolPostcode',
                            'id' => 'postcode',
                            'class' => 'form-control',
                            'placeholder' => 'Postcode',
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Telefoon', 'telefoon'); ?>
                        <?php
                        echo form_input(array('name' => 'schoolTelefoon',
                            'id' => 'telefoon',
                            'class' => 'form-control',
                            'placeholder' => 'Telefoon',
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Email', 'email'); ?>
                        <?php
                        echo form_input(array('name' => 'schoolEmail',
                            'type' => 'email',
                            'id' => 'email',
                            'class' => 'form-control',
                            'placeholder' => 'Email',
                            'required' => 'required',
                        ));
                        ?>

                        <div class="invalid-feedback">Vul dit veld in</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php
                echo form_submit('', '', array('id' => 'knopSchool', 'class' => 'btn'));
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
                <input type="hidden" id="schoolIdBevestiging">
                <div id="boodschap"></div>
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
<div class="modal fade" id="modalDemo" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Demo video</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="gebruikerId">
                <div id="boodschap">
                    <iframe src="https://www.youtube.com/embed/ivu4hSryyl4"
                            width="455" height="315" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo form_button(array('content' => "sluit", 'id' => 'knopSluit', 'class' => 'btn'));
                ?>
                <?php echo form_button(array('content' => "niet meer tonen", 'id' => 'knopNooitTonen', 'class' => 'btn btn-danger'));
                ?>
            </div>
        </div>
    </div>
</div>