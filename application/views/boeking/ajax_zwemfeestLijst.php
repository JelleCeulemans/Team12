<?php
/**
 * @file ajax_zwemfeestLijst.php
 */
?>
<br>
<h4>Zwemfeestjes</h4>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Voornaam</th>
        <th scope="col">Achternaam</th>
        <th scope="col">Telefoon</th>
        <th scope="col">Aantal</th>
        <th scope="col">Datum</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($zwemfeestjes as $zwemfeest) {
        echo "<tr>\n";
        echo "<td>" . $zwemfeest->voornaam . "</td>";
        echo "<td>" . $zwemfeest->achternaam . "</td>";
        echo "<td>" . $zwemfeest->telefoon . "</td>";
        echo "<td>" . $zwemfeest->aantal . "</td>";
        echo "<td>" . $zwemfeest->datum . "</td>";

    }
    ?>
    </tbody>

