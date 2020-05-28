

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

<table class="table">
    <thead>
    <tr>
        <th width="50%">Niveau</th>
        <th width="15%">Prijs</th>

        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php

    
    foreach ($niveaus as $niveau) {

        echo "<tr>\n";
        echo "<td>" . $niveau->naam . "</td>";
        echo "<td>" . $niveau->prijs . "</td>";
        echo "<td>";
        $extraButton = array('class' => 'btn btn-success wijzig', 'data-niveauid' => $niveau->id, 'data-toggle' => 'tooltip',
            "title" => "Niveau wijzigen");
        echo form_button("knop" . $niveau->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
        echo "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-danger schrap', 'data-niveauid' => $niveau->id, 'data-toggle' => 'tooltip',
            "title" => "Product verwijderen");
        echo form_button("knop" . $niveau->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
        echo "</td>\n";
        echo "</tr>\n";
    }
    ?>
    </tbody>
</table>

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
    foreach ($gerechten as $gerecht) {

        echo "<tr>\n";
        echo "<td>" . $gerecht->naam . "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-success wijzig', 'data-gerechtid' => $gerecht->id, 'data-toggle' => 'tooltip',
            "title" => "Product wijzigen");
        echo form_button("knopwijzig" . $gerecht->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
        echo "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-danger schrap', 'data-gerecht-id' => $gerecht->id, 'data-toggle' => 'tooltip',
            "title" => "Product verwijderen");
        echo form_button("knopverwijder" . $gerecht->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
        echo "</td>\n";
        echo "</tr>\n";
    }
    ?>
    </tbody>
</table>

<h4>Zwemmoment beheren</h4>
<table class="table">
    <thead>
    <tr>
        <th width="50%">Weekdag</th>
        <th width="15%">Prijs</th>
        <th width="15%">In stock</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($zwemmomenten as $zwemmoment) {

        echo "<tr>\n";
        echo "<td>" . $zwemmoment->weekdag . "</td>";
        echo "<td>" . $zwemmoment->startuur . "</td>";
        echo "<td>" . $zwemmoment->stopuur . "</td>";
        echo "<td>";
        $extraButton = array('class' => 'btn btn-success wijzig', 'data-gerechtid' => $gerecht->id, 'data-toggle' => 'tooltip',
            "title" => "Product wijzigen");
        echo form_button("knopwijzig" . $gerecht->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
        echo "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-danger schrap', 'data-gerecht-id' => $gerecht->id, 'data-toggle' => 'tooltip',
            "title" => "Product verwijderen");
        echo form_button("knopverwijder" . $gerecht->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
        echo "</td>\n";
        echo "</tr>\n";
    }
    ?>
    </tbody>
</table>