<?php
/**
 * @file overzicht.php
 */
?>
<script>
    function updateWinkelwagen (id, aantal) {
        $.ajax({
            type:"GET",
            url:site_url+"/product/updateWinkelwagen",
            data:{id: id, aantal: aantal},
            success: function (result) {
                $('#aantalProducten').text(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function winkelmandLeegmaken () {
        $.ajax({
            type:"GET",
            url:site_url+"/product/winkelmandLeegmaken",
            success: function (result) {
                $('#aantalProducten').text(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        $('.toevoegen').click(function () {
            id = $(this).data('id');
            aantal = $('#aantal'+id).val();
            updateWinkelwagen(id, aantal);
            $(window).scrollTop(0);
            $('#aantal').text($(this).prev().val());
            $('#product').text($(this).data('titel'));
            $('#toevoegVenster').modal();
        });
        $('#leegmaken').click(function () {
           winkelmandLeegmaken();
        });

    });
</script>
<style>
    .card-header {
        height: 225px;
    }
    #winkelwagen{
        font-size: 3em;
        margin-right: 100px;
        color: steelblue;
        float: right;
    }
</style>

<div class="dropdown" id="winkelwagen">
    <div data-toggle="dropdown"><i class="fas fa-shopping-cart"></i> <span id="aantalProducten"><?php echo $aantalProducten; ?></span></div>
    <ul class="dropdown-menu">
        <li class="dropdown-item" id="leegmaken">Winkelwagen leegmaken</li>
        <li class="dropdown-divider"></li>
        <li><?php echo anchor('product/toonWinkelwagen', 'Naar winkelwagen', array('class' => 'dropdown-item')); ?></li>
    </ul>
</div>


<?php
$outprint = '';
foreach ($producten as $product) {
    $outprint .= '<div class="col-xl-4 col-md-6 col-sm-12 d-flex align-items-stretch">
                        <div class="card mt-5" style="width: 18em;">'.
                            img(base_url().'assets/images/' . $product->afbeelding, '', array('class' => 'card-header card-img-top bg-white'))
                            .'<div class="card-body">
                            <h5 class="card-title">'.ucfirst($product->naam).'</h5>
                            <hr>
                            <p class="card-text">'.ucfirst($product->beschrijving).'</p>
                            <hr>
                            <p><b>Prijs: </b>€'.zetOmNaarKomma($product->prijs).'</p>';
                            if ($product->maat) {
                                $outprint .= '<p><b>Maat: </b>'.$product->maat.'</p>';
                            }
                            $outprint.= '</div>
                            <div class="card-footer text-center">';
                                if ($product->aantalInStock > 0) {
                                    $outprint .= form_DropdownAantal($product->aantalInStock, $product->id) .
                                    form_button(array('class' => 'btn btn-success toevoegen mt-2', 'data-id' => $product->id, 'data-titel' => ucfirst($product->naam), 'content' => 'Toevoegen'));
                                }
                                else {
                                   $outprint .= '<p class="text-danger">Dit product is tijdelijk niet op voorraad.</p>';
                                }
                                $outprint .= '</div></div></div>';
}

?>
<div class="row container">
   <?php echo $outprint; ?>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="toevoegVenster">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Toevoeging product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>U heeft het volgende product toegevoegd:</p>
                <ul>
                    <li>Product: <b id="product"></b></li>
                    <li>Aantal: <b id="aantal"></b></li>
                </ul>
            </div>
            <div class="modal-footer">
                <?php echo anchor('product/toonWinkelwagen', 'Ga naar winkelwagen', array('class' => 'btn btn-success')); ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Verder winkelen</button>
            </div>
        </div>
    </div>
</div>

