<?php
/**
 * @file wachtlijst.php
 */
?>
<script>

    var currentGroep =0;
    var geselecteerdeHuidigeZwemmer =0;
    var geselecteerdeBeschikbareZwemmer=0;

    function haalWachtlijstOp(){
        $.ajax({
            type:"GET",
            url:site_url+"/Wachtlijst/haalAjaxOp_Wachtlijst",
            success: function (result) {
                $("#wachtlijstBox").html(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function haalZwemmerOp(id) {
        $.ajax({
            type:"GET",
            url:site_url+"/Wachtlijst/haalAjaxOp_Zwemmer",
            data:{zwemmerId:id},
            success: function (result) {
                $("#resultaat").html(result);
                $('#mijnDialoogscherm').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function voegZwemmerToe(id) {
        $.ajax({
            type:"GET",
            url:site_url+"/Wachtlijst/haalAjaxOp_VoegZwemmerToe",
            data:{zwemmerId:id,groepId:currentGroep},
            success: function (result) {
                var id = '#collapse'+(currentGroep).toString();
                $(id).html(result);
                haalWachtlijstOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function verwijderZwemmer(id) {
        $.ajax({
            type:"GET",
            url:site_url+"/Wachtlijst/haalAjaxOp_VerwijderZwemmer",
            data:{zwemmerId:id,groepId:currentGroep},
            success: function (result) {
                var id = '#collapse'+(currentGroep).toString();
                $(id).html(result);
                haalWachtlijstOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function wachtlijstZwemmer(id) {
        $.ajax({
            type:"GET",
            url:site_url+"/Wachtlijst/haalAjaxOp_WachtlijstZwemmer",
            data:{zwemmerId:id,groepId:currentGroep},
            success: function (result) {
                var id = '#collapse'+(currentGroep).toString();
                $(id).html(result);
                haalWachtlijstOp();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {

        $("#wachtlijstBox").on('click','option.wachtlijst',function () {
            haalZwemmerOp($(this).val());
        });

        $(".collapse").on('click','.voegZwemmerToe',function() {
            if(geselecteerdeBeschikbareZwemmer>0){
                voegZwemmerToe(geselecteerdeBeschikbareZwemmer);
                geselecteerdeBeschikbareZwemmer=0;
            }
        });

        $(".collapse").on('click','.verwijderZwemmer',function() {
            if(geselecteerdeHuidigeZwemmer>0){
                verwijderZwemmer(geselecteerdeHuidigeZwemmer);
                geselecteerdeHuidigeZwemmer =0;
            }
        });

        $(".collapse").on('click','.wachtlijstZwemmer',function() {
            if(geselecteerdeHuidigeZwemmer>0){
                wachtlijstZwemmer(geselecteerdeHuidigeZwemmer);
                geselecteerdeHuidigeZwemmer =0;
            }
        });
        $(".collapse").on('click','.infoZwemmer',function() {
            if(geselecteerdeHuidigeZwemmer==0 && geselecteerdeBeschikbareZwemmer>0){
                haalZwemmerOp(geselecteerdeBeschikbareZwemmer);
            }
            else if(geselecteerdeBeschikbareZwemmer==0 && geselecteerdeHuidigeZwemmer>0){
                haalZwemmerOp(geselecteerdeHuidigeZwemmer);
            }

        });

        $("a.groep").click(function() {
            currentGroep =this.getAttribute('value');
            geselecteerdeHuidigeZwemmer =0;
            geselecteerdeBeschikbareZwemmer=0;
        });

        $(".collapse").on('click','option.wachtlijstBeschikbare',function() {
            geselecteerdeBeschikbareZwemmer=($(this).val());
        });
        $(".collapse").on('click','option.wachtlijstHuidige',function() {
            geselecteerdeHuidigeZwemmer=($(this).val());
        });

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
    <p><?php echo anchor("help/wachtlijst", "help", array("class"=>"btn btn-info")) ?></p>
    <h3>Beheren zwemgroepen</h3>

    <div class="col-12 mt-3" id="accordionZwemgroepen">
    <?php
    $weekdagen = array("zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag");
    for ($i=0;$i<count($groepen);$i++){

        //var_dump($groepen[$i]);

        echo '<div class="card"><div class="card-header">';

        echo '<div class="row"><div class="col-sm">';
        echo '<h4><a data-toggle="collapse" class="groep" href="#collapse'.$groepen[$i]->id.'" value="'.$groepen[$i]->id.'">Groep '.$groepen[$i]->id.'</a></h4></div>';
        echo '<div class="col-sm">';
        echo ucfirst($weekdagen[$groepen[$i]->weekdag])." ".$groepen[$i]->startuur."u - ".$groepen[$i]->stopuur."u".'</div>';
        echo '<div class="col-sm text-center">';
        echo count($groepen[$i]->huidigeZwemmers)."/".$groepen[$i]->maximumAantal.'</div>';
        echo '<div class="col-sm text-center">';
        echo $groepen[$i]->niveau->naam.'</div></div></div>';


        echo '<div id="collapse'. $groepen[$i]->id .'" class="collapse" data-parent="#accordionZwemgroepen">';
        echo '<div class="card-body">';

        echo '<div class="row justify-content-center">';
        echo '<div class="col-5">';

        echo form_WachtlijstClass('beschikbare','wachtlijstBeschikbare', $groepen[$i]->beschikbareZwemmers, 'id', 'voornaam','achternaam', 0,array('class' => "form-control", "size" => "10", "id" => "test"));

        //var_dump($groepen[$i]);

        echo '</div><div class="col-2">';

        //pijlen tussenin???

        echo '<button type="button" class="btn btn-success voegZwemmerToe">Voeg toe</button>';
        echo '</br></br>';
        echo '<button type="button" class="btn btn-warning wachtlijstZwemmer">Wachtlijst</button>';
        echo '</br></br>';
        echo '<button type="button" class="btn btn-danger verwijderZwemmer">Verwijder</button>';
        echo '</br></br>';
        echo '<button type="button" class="btn btn-info infoZwemmer">Info</button>';


        echo '</div><div class="col-5">';

        echo form_WachtlijstClass('huidige','wachtlijstHuidige', $groepen[$i]->huidigeZwemmers, 'id', 'voornaam','achternaam', 0,array('class' => "form-control", "size" => "10", "id" => "test"));

        echo '</div></div></div></div></div>';
    }

    ?>

    </div>
</br>
    <h3>Wachtlijst</h3>
    <div class="row justify-content-center">
        <div class="col-6 align-self-center" id="wachtlijstBox">
            <?php echo form_WachtlijstClass('wachtlijstId','wachtlijst', $wachtlijst, 'id', 'voornaam','achternaam', 0,
                array('class' => "form-control", "size" => "10", "id" => "wachtlijstId")); ?>
        </div>
    </div>
</div>

<?php //var_dump($groepen)?>
</br>
<?php //var_dump($wachtlijst);

?>

<!-- Dialoogvenster -->
<div class="modal fade" id="mijnDialoogscherm" role="dialog">
    <div class="modal-dialog">

        <!-- Inhoud dialoogvenster-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Zwemmer</h4>
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