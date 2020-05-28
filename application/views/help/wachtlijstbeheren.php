<?php
/**
 * @file wachtlijstbeheren.php
 */
?>
<h4 id="top">Inleiding</h4>
<div class="row">
    <p class="col-lg-6">Deze pagina toont een overzicht van alle zwemgroepen en de leerlingen die bij deze groepen zwemmen, of in de wachtlijst van de groep staan.
    Een leerkracht kan hier leerlingen in en uit de wachtlijst plaatsen, een leerling uit de wachtlijst verwijderen of de algemene info van een leerling bekijken.
    Hieronder vindt je een handleiding voor alle verschillende functies die je kan uitvoeren op deze pagina.</p>
    <div class="col-lg-6"><img src="<?php echo base_url() . "assets/images/help1.png"?>" alt="screenshot1" class="img-fluid"></div>
</div>
<br>
<br>
<h4>Inhoudstabel functies</h4>
<ul>
    <?php
    echo "<li><a href='#leerlingInGroep'>Een leerling in een zwempgroep plaatsen</a></li>";
    echo "<li><a href='#leerlingUitGroep'>Een leerling uit een zwempgroep halen</a></li>";
    echo "<li><a href='#leerlingUitWachtlijst'>Een leerling uit de wachtlijst verwijderen</a></li>";
    echo "<li><a href='#leerlingBekijken'>De gegevens van een leerling bekijken</a></li>";
    ?>
</ul>
<br>
<br>
<h4 id="leerlingInGroep">Een leerling in een zwemgroep plaatsen</h4>
<div class="row">
    <ul class="col-lg-6">
        <li>Stap 1: Klik op de naam van de zwemgroep (blauwe link) waar er plek is om een leerling in te zetten. naast de naam kan je zien wanneer deze groep zwemt, hoe vol de groep zit en van welk niveau de zwemgroep is.</li>
        <li>Stap 2: Er verschijnen 2 lijsten: links een lijst met alle leerlingen op de wachtlijst voor de groep en rechts een lijst met alle leden van de groep. Klik op een naam in de linkse lijst.</li>
        <li>Stap 3: Om de leerling van de wachtlijst naar de groep te plaatsen, klik je op de groene knop waar "Voeg toe" op staat.</li>
        <li>Stap 4: Controlleer of de leerling die je verplaatst hebt nu verdwenen is in de linkerlijst en verschenen in de rechterlijst</li>
        <a href='#top'>Terug naar boven</a>
    </ul>
    <div class="col-lg-6"><img src="<?php echo base_url() . "assets/images/help2.png"?>" alt="screenshot2" class="img-fluid"></div>
</div>
<br>
<br>
<h4 id="leerlingUitGroep">Een leerling uit een zwemgroep halen</h4>
<div class="row">
    <ul class="col-lg-6">
        <li>Stap 1: Klik op de naam van de zwemgroep (blauwe link) waar je een leerling uit wilt. naast de naam kan je zien wanneer deze groep zwemt, hoe vol de groep zit en van welk niveau de zwemgroep is.</li>
        <li>Stap 2: Er verschijnen 2 lijsten: links een lijst met alle leerlingen op de wachtlijst voor de groep en rechts een lijst met alle leden van de groep. Klik op een naam in de rechtse lijst.</li>
        <li>Stap 3: Om de leerling van de groep naar de wachtlijst te halen, klik je op de gele knop waar "Wachtlijst" op staat.</li>
        <li>Stap 4: Controlleer of de leerling die je verplaatst hebt nu verdwenen is in de rechterlijst en verschenen in de linkerlijst</li>
        <a href='#top'>Terug naar boven</a>
    </ul>
    <div class="col-lg-6"><img src="<?php echo base_url() . "assets/images/help3.png"?>" alt="screenshot2" class="img-fluid"></div>
</div>
<br>
<br>
<h4 id="leerlingUitWachtlijst">Een leerling uit de wachtlijst verwijderen</h4>
<div class="row">
    <ul class="col-lg-6">
        <li>Stap 1: Klik op de naam van de zwemgroep (blauwe link) waar een leerling in de wachtlijst staat die je wilt verwijderen. naast de naam kan je zien wanneer deze groep zwemt, hoe vol de groep zit en van welk niveau de zwemgroep is.</li>
        <li>Stap 2: Er verschijnen 2 lijsten: links een lijst met alle leerlingen op de wachtlijst voor de groep en rechts een lijst met alle leden van de groep. Klik op een naam in de linkse lijst.</li>
        <li>Stap 3: Om de leerling uit de wachtlijst te verwijderen, klik je op de rode knop waar "Verwijder" op staat.</li>
        <li>Stap 4: Controlleer of de leerling die je verplaatst hebt nu verdwenen is in de linkse lijst</li>
        <a href='#top'>Terug naar boven</a>
    </ul>
    <div class="col-lg-6"><img src="<?php echo base_url() . "assets/images/help4.png"?>" alt="screenshot2" class="img-fluid"></div>
</div>
<br>
<br>
<h4 id="leerlingBekijken">De gegevens van een leerling bekijken</h4>
<div class="row">
    <ul class="col-lg-6">
        <li>Stap 1: Staat de leerling in een wachtlijst? Dan hoef je enkel naar beneden te scrollen en op de naam van de leerling te klikken. Zit de leerling al in een zwemgroep, ga dan naar stap 2</li>
        <li>Stap 2: Klik op de naam van de zwemgroep (blauwe link) waar de leerling waarvan je de gegevens wilt bekijken in zit.</li>
        <li>Stap 3: Er verschijnen 2 lijsten: links een lijst met alle leerlingen op de wachtlijst en rechts een lijst met alle leerlingen in de groep. Klik in de rechtse lijst op de naam van de leerling waar je de gegevens van wilt bekijken.</li>
        <li>Stap 4: Klik op de blauwe knop met "info" en er verschijnt een pop-up met alle gegevens van deze leerling</li>
        <a href='#top'>Terug naar boven</a>
    </ul>
    <div class="col-lg-6"><img src="<?php echo base_url() . "assets/images/help5.png"?>" alt="screenshot2" class="img-fluid"></div>
</div>