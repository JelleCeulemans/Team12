<?php
/**
 * @file ajax_gerechten.php
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
        foreach ($gerechten as $gerecht) {
            echo "<tr>\n";
            echo "<td>" . $gerecht->naam . "</td>";
            echo "<td >" . $gerecht->prijs . "</td>";
            echo "<td>";
            $extraButton = array('class' => 'btn btn-success wijzig', 'data-gerechtid' => $gerecht->id, 'data-toggle' => 'tooltip',
                "title" => "niveau wijzigen");
            echo form_button("knopwijzig" . $gerecht->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
            echo "</td>";

            echo "<td>";
            $extraButton = array('class' => 'btn btn-danger schrap', 'data-gerechtid' => $gerecht->id, 'data-toggle' => 'tooltip',
                "title" => "niveau verwijderen");
            echo form_button("knopverwijder" . $gerecht->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
            echo "</td>\n";
            echo "</tr>\n";
        }
        ?>
        </tbody>
    </table>
</div>