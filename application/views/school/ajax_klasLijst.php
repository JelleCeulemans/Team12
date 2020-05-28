<?php
/**
 * @file ajax_klasLijst.php
 */
?>
<?php
echo "<table class='table'>\n";
    echo "<thead>\n";
    echo '<th scope="col">leerjaar</th>';
    echo '<th scope="col">Is gesubsidiëerd</th>';
    echo '<th scope="col"></th>';
    echo '<th scope="col"></th>';
    echo "</thead>\n";

    foreach ($klassen as $klas) {


    echo "<tr>\n";
        echo "<td>" . $klas->leerjaar . "</td>";
        if($klas->isGesubsidieerd==0){
        echo "<td>nee</td>";
        }
        else{
        echo "<td>ja</td>";
        }

        echo "<td>";
        echo form_button("knopwijzigklas" . $klas->id, "<i class=\"fas fa-edit\"></i>", array('class' => 'btn btn-success wijzigklas', 'data-klasid' => $klas->id, 'data-toggle' => 'tooltip',
            "title" => "Klas wijzigen"));
        echo "</td>\n";
        echo "<td>";
            echo form_button("knopverwijderklas" . $klas->id, "<i class=\"fas fa-trash-alt\"></i>", array('class' => 'btn btn-danger verwijderklas', 'data-klasid' => $klas->id, 'data-toggle' => 'tooltip',
            "title" => "Klas verwijderen"));
            echo "</td>\n";
        echo "<td>";
            echo anchor("aanwezigheid/index/" . $klas->id, "<i class=\"far fa-clock\"></i>", array('class'=>'btn btn-info'));
            echo "</td>\n";
        echo "</tr>\n";
    }

    echo "</table>\n";
    ?>