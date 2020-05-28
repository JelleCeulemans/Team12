<?php
/**
 * @file home.php
 */
?>
<div class="container-fluid">
    <div class="row">
        <p>Dit project is ontwikkelt voor hotel Kempenrust. Zij beschikken over een zwembad dat gebruikt wordt voor scholen, zwemfeestjes en zelf georganiseerde zwemlessen. Met de applicatie kunnen zwemlessen en zwemfeestjes makkelijk online worden geboekt. Zwemleerkrachten kunnen hun groepen beheren administratoren kunnen bestellingen afhandelen, prijzen aanpassen, scholen factureren en allerlij andere beherende functies.</p>
    </div>
</div>

<h2>Nieuwtjes</h2>
<div class="container small">
    <div class="row">

<?php
foreach ($artikelen as $artikel){
    echo "<div class=\"col-sm m-2\"><div class=\"card h-100\"><div class=\"card-body\">";
    echo " <h5 class=\"card-title\">". $artikel->titel ."</h5>";
    echo "<p class=\"card-text\">" . $artikel->inhoud ."</p>";
    echo "<h6 class=\"card-subtitle mb-2\">" . $artikel->datum ."</h6>";
    echo "</div></div></div>";
}
?>

    </div>
</div>
<br>
<div>
<?php echo $links; ?>
</div>
