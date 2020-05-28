

<table class="table">
    <thead>
    <tr>
        <th width="50%">Product</th>
        <th width="15%">Prijs</th>
        <th width="15%">In stock</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ($producten as $product) {

        echo "<tr>\n";
        echo "<td>" . $product->naam . "</td>";
        echo "<td>" . $product->prijs . "</td>";
        echo "<td>" . $product->aantalInStock . "</td>";
        echo "<td>" . $product->beschrijving . "</td>";
        echo "<td>" . $product->afbeelding . "</td>";
        echo "<td>" . $product->maat . "</td>";
        echo "<td>";
        $extraButton = array('class' => 'btn btn-success wijzig', 'data-productid' => $product->id, 'data-toggle' => 'tooltip',
            "title" => "Product wijzigen");
        echo form_button("knopwijzig" . $product->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
        echo "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-danger schrap', 'data-productid' => $product->id, 'data-toggle' => 'tooltip',
            "title" => "Product verwijderen");
        echo form_button("knopverwijder" . $product->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
        echo "</td>\n";
        echo "</tr>\n";
    }
    ?>
    </tbody>
</table>



