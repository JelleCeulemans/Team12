<?php
/**
 * @file ajax_facturen.php
 */
?>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Prijs</th>
            <th scope="col">Momenten</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($facturen as $factuur) {
            echo "<tr>\n";
            echo "<td>" . $factuur->prijs . "</td>";
            echo "<td><ul>";

            foreach ($factuur->momenten as $moment){
                echo "<li>$moment->datum</li>";
            }

            echo "</ul></td>\n";
            echo "<td>";
            $extraButton = array('class' => 'btn btn-danger schrap', 'data-factuurid' => $factuur->id, 'data-toggle' => 'tooltip',
                "title" => "factuur verwijderen");
            echo form_button("knopVerwijder" . $factuur->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
            echo "</td>\n";
            echo "</tr>\n";
        }
        ?>
        </tbody>
    </table>
</div>