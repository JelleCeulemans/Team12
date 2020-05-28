<?php
/**
 * @file scholen.php
 */
?>
<script>

    var klasId=0;
    var aantalAanwezig =0;
    var schoolId=0;
    var formKlas,formDate,formAanwezig;

    function haalKlasOp(id) {
        $.ajax({
            type:"GET",
            url:site_url+"/School/haalAjaxOp_Klas",
            data:{klasId:id},
            success: function (result) {
                var id ='#klasDetails'+(schoolId).toString();
                $(id).html(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function updateAanwezigheid(aantal,klasId) {
        $.ajax({
            type:"GET",
            url:site_url+"/School/haalAjaxOp_updateAanwezigheid",
            data:{aantalAanwezig:aantal,klasId:klasId},
            success: function (result) {
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function openModalSchoolVerwijderen(id) {

        $.ajax({
            type:"GET",
            url:site_url+"/School/haalAjaxOp_schoolVerwijderen",
            data:{schoolId:id},
            success: function (result) {
                $("#dialoogtitel").html("School verwijderen");
                $("#resultaat").html(result);
                $('#dialoogvenster').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function openModalNieuweSchool() {

        $.ajax({
            type:"GET",
            url:site_url+"/School/haalAjaxOp_nieuweSchool",
            success: function (result) {
                $("#dialoogtitel").html("Nieuwe school toevoegen");
                $("#resultaat").html(result);
                $('#dialoogvenster').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function openMondalZwemmoment(id) {
        $.ajax({
            type:"GET",
            url:site_url+"/School/haalAjaxOp_openMondalZwemmoment",
            data:{schoolId:id},
            success: function (result) {
                $("#dialoogtitel").html("Nieuw zwemmoment");
                $("#resultaat").html(result);
                $('#dialoogvenster').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function maakZwemmoment(school,klas,date,aanwezig){
        $.ajax({
            type:"GET",
            url:site_url+"/School/haalAjaxOp_voegMomentToe",
            data:{schoolId:school,klasId:klas,date:date,aanwezig:aanwezig},
            success: function (result) {
                $("#resultaat").html(result);
                $('#dialoogvenster').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {

        $(".collapse").on('click','option.klassen',function() {
            schoolId=($(this).val());
            if(schoolId>0){
                haalKlasOp(schoolId);
            }
        });
        $('.collapse').on('change','input.klasAanwezig',function () {
            aantalAanwezig=($(this).val());
            klasId=($(this).attr("data-id"));
            updateAanwezigheid(aantalAanwezig,klasId);
        })

        $('#resultaat').on('click','#momentSubmit',function () {


            formKlas=($('option.klassenMoment').val());
            formDate=($('#dateInput').val());
            formAanwezig=($('#aanwezigInput').val());

            console.log(formDate);

            if(formDate!=""){
                maakZwemmoment(schoolId,formKlas,formDate,formAanwezig);
            }
        })

        $('#resultaat').on('click','#schoolSubmit',function () {

            var naam = ($('#naam').val());
            var straat = ($('#straat').val());
            var huisnr = ($('#huisnr').val());
            var postcode = ($('#postcode').val());
            var telefoon = ($('#telefoon').val());
            var email = ($('#email').val());

            $.ajax({
                type:"GET",
                url:site_url+"/School/haalAjaxOp_voegSchoolToe",
                data:{naam:naam,straat:straat,huisnummer:huisnr,postcode:postcode,telefoon:telefoon,email:email},
                success: function (result) {
                    $("#resultaat").html(result);
                    $('#dialoogvenster').modal('show');
                },
                error: function (xhr, status, error) {
                    alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
                }
            });
        })

        $('.addMoment').click(function () {
            schoolId=($(this).val());
            if(schoolId>0){
                openMondalZwemmoment(schoolId);
            }
        })

        $('.schooAapassen').click(function () {
            schoolId=($(this).val());
            if(schoolId>0){
                openModalSchoolAanpassen(schoolId);
            }
        })

        $('.schoolVerwijderen').click(function () {
            schoolId=($(this).val());
            if(schoolId>0){
                openModalSchoolVerwijderen(schoolId);
            }
        })

        $('.verwijderBevestigen').click(function () {
            schoolId=($(this).val());

        })

        $('.nieuweSchool').click(function () {
            openModalNieuweSchool()

        })


    });

</script>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $title; ?></title>
</head>

<body>
<br class="container">

<h3>Aanwezigheden Scholen</h3>

<div class="col-12 mt-3" id="accordionScholen">
    <?php

    //var_dump($scholen);

    for ($i=0;$i<count($scholen);$i++){

        echo '<div class="card"><div class="card-header">';

        echo '<div class="row"><div class="col-sm-8">';
        echo '<h4><a data-toggle="collapse" class="klas" href="#collapse'.$scholen[$i]->id.'" value="'.$scholen[$i]->id.'">'.ucwords($scholen[$i]->naam).'</a></h4></div>';
        echo '<div class="col-sm">';
        echo '<button type="button" class="btn btn-success addMoment mx-2" value="'.$scholen[$i]->id.'">Moment toevoegen</button>';
        echo '<button type="button" class="btn btn-info schoolAanpassen mx-2" value="'.$scholen[$i]->id.'"><i class="fas fa-pencil-alt"></i></button>';
        echo '<button type="button" class="btn btn-danger schoolVerwijderen mx-2" value="'.$scholen[$i]->id.'"><i class="fas fa-trash"></i></button>';
        echo '</div></div></div>';
        


        echo '<div id="collapse'. $scholen[$i]->id .'" class="collapse" data-parent="#accordionScholen">';
        echo '<div class="card-body">';

        echo '<div class="row justify-content-center">';
        echo '<div class="col-6">';
        echo '<h5>Klassen</h5>';
        echo form_KlassenClass('klassen','klassen',$scholen[$i]->klassen,'id','leerjaar', 0,array('class' => "form-control", "size" => "10", "id" => "test"));


        echo '</div><div class="col-6 klasDetails" id="klasDetails'.$scholen[$i]->id.'">';

        echo '</div></div>';


        echo '</div></div></div>';
    }
    echo "<br>";
    echo '<button type="button" class="btn btn-success nieuweSchool float-right">Nieuwe School toevoegen</button>';

    ?>

</div>

<!-- Dialoogvensters -->
<div class="modal fade" id="dialoogvenster" role="dialog">
    <div class="modal-dialog">

        <!-- Inhoud dialoogvenster-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="dialoogtitel"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="resultaat"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Sluit</button>
            </div>
        </div>

    </div>
</div>
</body>
</html>