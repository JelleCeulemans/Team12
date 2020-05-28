<table class="table">
    <thead>
    <tr>
        <th width="50%">Gerechten</th>
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
