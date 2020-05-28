<?php
/**
 * @file ajax_nieuwsberichten.php
 */
?>
<table class="table">
    <thead>
        <tr>
            <th width="15%">Naam</th>
            <th width="50%">Inhoud</th>
            <th width="15%">Datum</th>
            <th></th>
            <th></th>
        </tr>
    </thead>

    <tbody>
    <?php

    echo var_dump($nieuwsberichten);

    foreach ($nieuwsberichten as $nieuwsbericht) {

        echo "<tr>\n";
        echo "<td>" . $nieuwsbericht->titel . "</td>";
        echo "<td>" . $nieuwsbericht->inhoud . "</td>";
        echo "<td>" . $nieuwsbericht->datum . "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-success wijzig', 'data-nieuwsberichtid' => $nieuwsbericht->id, 'data-toggle' => 'tooltip',
            "title" => "Product wijzigen");
        echo form_button("knopwijzig" . $nieuwsbericht->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
        echo "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-danger schrap', 'data-nieuwsberichtid' => $nieuwsbericht->id, 'data-toggle' => 'tooltip',
            "title" => "Nieuwsbericht verwijderen");
        echo form_button("knopverwijder" . $nieuwsbericht->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
        echo "</td>\n";
        echo "</tr>\n";
    }
    ?>
    </tbody>
</table>