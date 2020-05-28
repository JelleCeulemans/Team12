<?php
/**
 * @file ajax_niveaus.php
 */
?>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Naam</th>
            <th scope="col">Prijs</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($niveaus as $niveau) {
            echo "<tr>\n";
            echo "<td>" . $niveau->naam . "</td>";
            echo "<td >" . $niveau->prijs . "</td>";
            echo "<td>";
            $extraButton = array('class' => 'btn btn-success wijzig', 'data-niveauid' => $niveau->id, 'data-toggle' => 'tooltip',
                "title" => "niveau wijzigen");
            echo form_button("knopwijzig" . $niveau->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
            echo "</td>";

            echo "<td>";
            $extraButton = array('class' => 'btn btn-danger schrap', 'data-niveauid' => $niveau->id, 'data-toggle' => 'tooltip',
                "title" => "niveau verwijderen");
            echo form_button("knopverwijder" . $niveau->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
            echo "</td>\n";
            echo "</tr>\n";
        }
        ?>
        </tbody>
    </table>
</div>