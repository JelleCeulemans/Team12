<?php
/**
 * @file main_header.php
 */
?>
<h1>Header van de pagina (main_header)</h1>

<?php
if ($rechten) {
    echo 'ingelogt';
}
else {
    echo 'afgemeld';
}
?>