<?php
/**
 * @file ajax_zwemmers.php
 */
?>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Naam</th>
        <th scope="col">E-mail</th>
        <th scope="col">Telefoon</th>
        <th scope="col">Zwemervaring</th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($zwemmers as $zwemmer) {
        echo "<tr>\n";
        echo "<td>" . $zwemmer->achternaam . " " . $zwemmer->voornaam . "</td>";
        echo "<td>" . $zwemmer->email . "</td>";
        echo "<td>" . $zwemmer->telefoon . "</td>";
        echo "<td>" . $zwemmer->niveauNaam . "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-success wijzig', 'data-zwemmerid' => $zwemmer->id, 'data-toggle' => 'tooltip',
            "title" => "Zwemmer wijzigen");
        echo form_button("knopwijzig" . $zwemmer->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
        echo "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-danger schrap', 'data-zwemmerid' => $zwemmer->id, 'data-toggle' => 'tooltip',
            "title" => "Zwemmer verwijderen");
        echo form_button("knopverwijder" . $zwemmer->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
        echo "</td>\n";
        echo "</tr>\n";
    }
    ?>
    </tbody>
</table>

