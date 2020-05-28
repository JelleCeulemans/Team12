<?php
/**
 * @file ajax_bestellingLijst.php
 */
?>
<script>
    function toonDetails(bestellingId){
        $.ajax({
            type: "GET",
            url: site_url + "/boeking/haalAjaxOp_Bestellingdetails/" + bestellingId,
            success: function (result) {
                $("#boodschap").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalDetailsOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function bestellingAfhandelen(bestellingId){
        $.ajax({
            type: "GET",
            url: site_url + "/boeking/Ajax_BestellingAfhandelen/" + bestellingId,
            success: function (result) {
                $('#modalDetails').modal('hide');
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (BestellingAfhandelen) --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {

        $('#modalDetails').on('hidden.bs.modal', function (e) {
            toonBestellingen()
        })

        $(".detail").click(function () {
            $('#modalDetails').modal('hide');
            var bestellingId = $(this).data('bestellingid');
            $('.afhandel').data('bestellingid', bestellingId);

            toonDetails(bestellingId)

            $('#modalDetails').modal('show');
        })
        $(".afhandel").click(function() {
            var bestellingId = $(this).data('bestellingid');
            bestellingAfhandelen(bestellingId)
        })
    });
</script>

<br>
<h4>Bestellingen</h4>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Voornaam</th>
        <th scope="col">Achternaam</th>
        <th scope="col">Telefoon</th>
        <th scope="col">E-mail</th>
        <th scope="col">Datum</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($bestellingen as $bestelling) {
        echo "<tr>\n";
        echo "<td>" . $bestelling->voornaam . "</td>";
        echo "<td>" . $bestelling->achternaam . "</td>";
        echo "<td>" . $bestelling->telefoon . "</td>";
        echo "<td>" . $bestelling->email . "</td>";
        echo "<td>" . $bestelling->datum . "</td>";
        echo "<td>";
        $detailButton = array('class' => 'btn btn-info detail', 'data-bestellingid' => $bestelling->id, 'data-toggle' => 'tooltip',
            "title" => "Details van de bestelling");
        echo form_button("knopdetail" . $bestelling->id, "Details", $detailButton);
        echo "</td>\n";
    }
    ?>
    </tbody>
</table>
<div class="modal fade" id="modalDetails" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="bestellingIdBevestiging">
                <div id="boodschap"></div>
            </div>
            <div class="modal-footer">
                <?php
                $afhandelButton = array('class' => 'btn btn-danger afhandel', 'data-bestellingid' => 0, 'data-toggle' => 'tooltip',
                    "title" => "De bestelling als afgehandeld beschouwen");
                echo form_button("knopafhandel", "Bestelling is afgehandeld", $afhandelButton);
                ?>
            </div>
        </div>
    </div>
</div>
