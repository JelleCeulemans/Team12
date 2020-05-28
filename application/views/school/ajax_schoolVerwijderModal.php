<?php
/**
 * @file ajax_schoolVerwijderModal.php
 */
?>
<?php

echo '<div class="row justify-content-center">';
echo '<div class="col-md-8">';
echo "<p>Weet u zeker dat u $school->naam wilt verwijderen?</p>";
echo '<div>';
echo '<button type="button" class="btn btn-success mx-2 verwijderBevestiging" data-dismiss="modal">annuleren</button>';
echo '<button type="button" class="btn btn-danger mx-2" value="'.$school->id.'">verwijderen</button>';
echo '</div></div>';
