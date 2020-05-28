<?php
/**
 * @file winkelwagen.php
 */
?>
<script>
    function verwijderProduct (id) {
        $.ajax({
            type:"GET",
            url:site_url+"/product/verwijderProduct",
            data:{id: id},
            success: function (result) {
                location.reload()
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function pasWaardeAan (id, waarde) {
        $.ajax({
            type:"GET",
            url:site_url+"/product/pasWaardeAan",
            data:{id: id, waarde: waarde},
            success: function (result) {
                location.reload();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }
    $(document).ready(function () {
        $('.verwijder').click(function () {
           var id = $(this).data('id');
           verwijderProduct(id);
        });

        $('.hoeveelheid').change(function () {
           var waarde = $(this).val();
           var id = $(this).data('id');
           pasWaardeAan(id, waarde);
        });
    });
</script>
<table class="table table-hover mt-4">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Product</th>
        <th scope="col">Maat</th>
        <th scope="col">Hoeveelheid</th>
        <th scope="col">Prijs</th>
        <th scope="col">Totaal</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $outprint = '';
    foreach ($winkelwagen as $item) {
        $outprint .= '<tr>
                        <th scope="row">'.ucfirst($item->naam).'</th>';
                        if ($item->maat) {
                            $outprint .= '<td>'.$item->maat.'</td>';
                        } else {
                            $outprint .= '<td>/</td>';
                        }

        $outprint .=   '<td>'.form_input(array('type' => 'number', 'class' => 'form-control hoeveelheid', 'value' => $item->aantal,'style' => 'width:10em', 'data-id' => $item->id, 'max' => $item->aantalInStock)).'</td>
                        <td>€'.zetOmNaarKomma($item->prijs).'</td>
                        <td>€'.zetOmNaarKomma($item->totaal).'</td>
                        <td class="verwijder" data-id="'.$item->id.'"><i class="fas fa-times fa-2x"></i></td>
                    </tr>';
    }
    if ($outprint) {
        echo $outprint;
    }
    else {
        echo '<tr><td colspan="5" ><h4>Er zijn geen producten in het winkelwagentje.</h4></td></tr>';
    }

    ?>
    </tbody>
</table>
<?php
$print = '';
if ($totaal) {
    $print = '<h4 id="totaleprijs">Totale prijs: €'. zetOmNaarKomma($totaal).'</h4>'.
        anchor('product/bestellen', 'Bestellen', array('id' => 'bestellen', 'class' => 'btn btn-success'));
} ?>
<div class="text-right">
    <?php echo $print; ?>
</div>

<p class="mt-5"><?php echo anchor('product/index', 'Terug naar de webshop', array('class' => 'btn btn-success'));?></p>


