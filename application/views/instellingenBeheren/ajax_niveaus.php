
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