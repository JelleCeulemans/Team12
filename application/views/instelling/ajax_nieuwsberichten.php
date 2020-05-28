<?php
/**
 * @file ajax_nieuwsberichten.php
 */
?>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Titel</th>
            <th scope="col">Datum</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($nieuwsberichten as $nieuwsbericht) {
            echo "<tr>\n";
            echo "<td>" . $nieuwsbericht->titel . "</td>";
            echo "<td >" . $nieuwsbericht->datum . "</td>";
            echo "<td>";
            $extraButton = array('class' => 'btn btn-success wijzig', 'data-nieuwsberichtid' => $nieuwsbericht->id, 'data-toggle' => 'tooltip',
                "title" => "nieuwsbericht wijzigen");
            echo form_button("knopwijzig" . $nieuwsbericht->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
            echo "</td>";

            echo "<td>";
            $extraButton = array('class' => 'btn btn-danger schrap', 'data-nieuwsberichtid' => $nieuwsbericht->id, 'data-toggle' => 'tooltip',
                "title" => "nieuwsbericht verwijderen");
            echo form_button("knopverwijder" . $nieuwsbericht->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
            echo "</td>\n";
            echo "</tr>\n";
        }
        ?>
        </tbody>
    </table>
</div>