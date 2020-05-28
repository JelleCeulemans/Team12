<?php
/**
 * @file ajax_wachtlijst.php
 *
 * View waar de algemene wachtlijst wordt getoond
 * - Krijgt een variabele $wachtlijst binnen
 */
echo form_WachtlijstClass('wachtlijstId','wachtlijst', $wachtlijst, 'id', 'voornaam','achternaam', 0,
    array('class' => "form-control", "size" => "10", "id" => "wachtlijstId"));
?>