<?php

/**
 * @file ajax_zwemmer.php
 *
 * View die de informatie van een zwemmer toont.
 * - Krijgt een variabele $zwemmer binnen
 */

echo '<table class ="table"><tbody>';


echo '<tr><th scope="row">Voornaam</th><td>'.$zwemmer->voornaam.'</td>';

echo '<tr><th scope="row">Achternaam</th><td>'.$zwemmer->achternaam.'</td>';

echo '<tr><th scope="row">Email</th><td>'.$zwemmer->email.'</td>';
echo '<tr><th scope="row">Telefoon</th><td>'.$zwemmer->telefoon.'</td>';
echo '<tr><th scope="row">Niveau</th><td>'.$zwemmer->niveau->naam.'</td>';
echo '<tr><th scope="row">Inschrijfdatum</th><td>'.$zwemmer->inschrijfdatum.'</td>';

echo '</tbody></table>';


?>