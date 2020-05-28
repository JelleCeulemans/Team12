<?php
/**
 * @file ajax_aanwezighedenKlas.php
 */
?>
<?php

echo '<h5>Klas '.$klas->leerjaar.'</h5>';
echo '<table class ="table">';
echo '<thead><tr>';
echo '<th scope="col">#</th>';
echo '<th scope="col">Datum</th>';
echo '<th scope="col">Aanwezig</th>';
echo '</tr></thead><tbody>';
for ($i=0;$i<count($aanwezigheden);$i++)
{
    echo '<tr><th>'.($i+1).'</th>';
    echo '<td>'.$aanwezigheden[$i]->datum.'</td>';
    echo '<td><input type="number" class="form-control klasAanwezig" min="0" value="'.$aanwezigheden[$i]->aantal.'" data-id="'.$aanwezigheden[$i]->id.'"></td></tr>';
}
echo '</tbody></table>';