<?php
/**
 * @file ajax_zwemgroepen.php
 */
?>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Weekdag</th>
            <th scope="col">Startuur</th>
            <th scope="col">Stopuur</th>
            <th scope="col">Niveau</th>
            <th scope="col">Maximum aantal</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $weekdagen = array("zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag");
        foreach ($zwemgroepen as $zwemgroep) {
            echo "<tr>\n";
            echo "<td>" . $weekdagen[$zwemgroep->weekdag] . "</td>";
            echo "<td >" . $zwemgroep->startuur . "</td>";
            echo "<td >" . $zwemgroep->stopuur . "</td>";
            echo "<td >" . $zwemgroep->niveau->naam . "</td>";
            echo "<td >" . $zwemgroep->maximumAantal . "</td>";
            echo "<td>";
            $extraButton = array('class' => 'btn btn-success wijzig', 'data-zwemgroepid' => $zwemgroep->id, 'data-toggle' => 'tooltip',
                "title" => "zwemgroep wijzigen");
            echo form_button("knopwijzig" . $zwemgroep->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
            echo "</td>";

            echo "<td>";
            $extraButton = array('class' => 'btn btn-danger schrap', 'data-zwemgroepid' => $zwemgroep->id, 'data-toggle' => 'tooltip',
                "title" => "zwemgroep verwijderen");
            echo form_button("knopverwijder" . $zwemgroep->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
            echo "</td>\n";
            echo "</tr>\n";
        }
        ?>
        </tbody>
    </table>
</div>