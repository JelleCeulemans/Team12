<?php

/**
 * @file ajax_wachtlijstBeheren.php
 *
 * View waar men de wachtlijsten kan beheren, de inhoud van een zwemgroep dropdown
 * - Krijgt een variabele $groepen binnen
 */

echo '<div class="card-body">';

echo '<div class="row justify-content-center">';
echo '<div class="col-5">';

echo form_WachtlijstClass('test','wachtlijstBeschikbare', $groepen[$i]->beschikbareZwemmers, 'id', 'voornaam','achternaam', 0,array('class' => "form-control", "size" => "10", "id" => "test"));

echo '</div><div class="col-2">';

echo '<button type="button" class="btn btn-success voegZwemmerToe">Voeg toe</button>';
echo '</br></br>';
echo '<button type="button" class="btn btn-warning wachtlijstZwemmer">Wachtlijst</button>';
echo '</br></br>';
echo '<button type="button" class="btn btn-danger verwijderZwemmer">Verwijder</button>';
echo '</br></br>';
echo '<button type="button" class="btn btn-info infoZwemmer">Info</button>';


echo '</div><div class="col-5">';

echo form_WachtlijstClass('test','wachtlijstHuidige', $groepen[$i]->huidigeZwemmers, 'id', 'voornaam','achternaam', 0,array('class' => "form-control", "size" => "10", "id" => "test"));

echo '</div></div></div></div>';

?>