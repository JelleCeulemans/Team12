<?php
/**
 * @file sluitingsdagen.php
 */
?>
<script>
    var sluiting, test;
    function haalSluitingsdagenOp () {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/haalSluitingsdagenOp",
            success: function (result) {
                $('#lijstSluitingsdagen').html(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalGerechtOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function voegSluitingsdagToe(datum) {
        $.ajax({
            type: "GET",
            url: site_url + "/Instelling/addDatum",
            data:  {datum: datum},
            success: function (result) {
                haalSluitingsdagenOp();
                getSluitingsdagen();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalGerechtOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function getSluitingsdagen(){
        $.ajax({
            type:"GET",
            url:site_url+ "/zwemfeest/sluitingsdagen",
            success: function (result) {
                sluiting = result;
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function schrapSluitingsdag(id) {
        $.ajax({
            type:"GET",
            url:site_url+ "/instelling/schrapSluitingsdag",
            data: {id: id},
            success: function (result) {
                haalSluitingsdagenOp();
                getSluitingsdagen();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        var today = new Date();
        getSluitingsdagen();
        var array = [eval(sluiting)];

        $("#dp2").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: today,
            beforeShowDay: function(date){
                console.log(test);
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [sluiting.indexOf(string) === -1]
            }
        });
        haalSluitingsdagenOp();

        $('#toevoegen').click(function () {
            voegSluitingsdagToe($('#dp2').val());
            //$('#modalInvoer').modal();
        });

        $('#lijstSluitingsdagen').on('click', ".schrap", function () {
            schrapSluitingsdag($(this).data('id'));
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



<div class="form-group">
    <?php echo form_label('Toevoegen'); ?>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend"><i class="far fa-calendar-alt"></i></span>
        </div>
        <?php echo form_input(array('class' => 'form-control', 'id' => 'dp2')); ?>
    </div>
</div>

<?php
$knop = array("class" => "btn btn-success text-white", "data-toggle" => "tooltip", "title" => "Sluitingsdag toevoegen", "id" => "toevoegen");
echo "<p>" . form_button('sluitingNieuw', "Toevoegen", $knop) . "</p>";
?>


<div id="lijstSluitingsdagen"></div>


<!--<div class="modal fade" id="modalInvoer" role="dialog">-->
<!--    <div class="modal-dialog" role="document">-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header">-->
<!--                <h5 class="modal-title" id="exampleModalLabel">Sluitingsdag toevoegen</h5>-->
<!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--                    <span aria-hidden="true">&times;</span>-->
<!--                </button>-->
<!--            </div>-->
<!--            <div class="modal-body">-->
<!--                <div class="form-group">-->
<!--                    --><?php //echo form_label('Datum'); ?>
<!--                    <div class="input-group">-->
<!--                        <div class="input-group-prepend">-->
<!--                            <span class="input-group-text" id="inputGroupPrepend"><i class="far fa-calendar-alt"></i></span>-->
<!--                        </div>-->
<!--                        --><?php //echo form_input(array('class' => 'form-control', 'type' => 'date')); ?>
<!--                    </div>-->
<!--            </div>-->
<!--            <div class="modal-footer">-->
<!--                --><?php //echo form_button(array('id' => 'verzenden', 'content' => 'Toevoegen', 'class' => 'btn btn-success'));?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
