<?php
/**
 * @file ajax_aanwezigheden.php
 */
?>
<div class="table-responsive">
<table class="table">
    <thead>
    <tr>
        <th scope="col">Datum</th>
        <th scope="col">Aantal aanwezigen</th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($zwemtotalen as $zwemtotaal) {
        echo "<tr>\n";
        echo "<td>" . zetOmNaarDDMMYYYY($zwemtotaal->datum) . "</td>";
        echo "<td class=\"kolomgroot\">" . $zwemtotaal->aantal . "</td>";
        echo "<td>";
        $extraButton = array('class' => 'btn btn-success wijzig', 'data-zwemtotaalid' => $zwemtotaal->id, 'data-toggle' => 'tooltip',
            "title" => "aanwezigheid wijzigen");
        echo form_button("knopwijzig" . $zwemtotaal->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
        echo "</td>";

        echo "<td>";
        $extraButton = array('class' => 'btn btn-danger schrap', 'data-zwemtotaalid' => $zwemtotaal->id, 'data-toggle' => 'tooltip',
            "title" => "aanwezigheid verwijderen");
        echo form_button("knopverwijder" . $zwemtotaal->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
        echo "</td>\n";
        echo "</tr>\n";
    }
    ?>
    </tbody>
</table>
</div>