<?php
/**
 * @file bevestiging.php
 */
?>

<script>
    $(function(){
        var requiredCheckboxes = $('.options :checkbox[required]');
        requiredCheckboxes.change(function(){
            if(requiredCheckboxes.is(':checked')) {
                requiredCheckboxes.removeAttr('required');
            } else {
                requiredCheckboxes.attr('required', 'required');
            }
        });
    });
</script>

<?php
echo haalJavascriptOp("bs_validator.js");
$attributenFormulier = array(   'id' => 'mijnFormulier2',
    'novalidate' => 'novalidate',
    'class' => 'needs-validation',
    'method' => 'post');
echo form_open('zwemles/bevestigen', $attributenFormulier);
?>
<h4>Zwemmoment kiezen</h4>

<div class="form-group options">
    <?php

    $checkboxId = 0;
    foreach ($momentenIds as $moment)
    {
        $dataMoment = array('id' => $moment,
            'name' => 'momenten[]',
            'class' => 'form-control zwemlesMoment',
            'value' => $moment,
            'required' => 'required',
            'style' => 'height:25px; width:25px;');

        echo form_checkbox($dataMoment, $momenten[$moment]);
        echo form_label($momenten[$moment], $moment, "style='font-size:30px;'") . "\n<br>";
        $checkboxId++;
    }
    $showSubmit = true;
    if(count($momentenIds) == 0)
    {
        echo "<p style='color: red; font-weight: bold; font-size: 30px '>Geen momenten gevonden voor dit niveau.</p>";
        $showSubmit = false;
    }
    ?>
    <div class="invalid-feedback">Duid minstens 1 moment aan</div>
</div>

<div class="card border-secondary mb-3" style="">
    <h3 class="card-header" style="font-size: 20px">Zwemmer</h3>
    <div class="card-body text-secondary">
        <div class="row">
            <div class="col">Zwemervaring:</div>
            <div class="col"><?php echo $niveau->naam?></div>
            <div class="w-100"></div>
            <div class="col">Naam:</div>
            <div class="col"><?php echo $naam?></div>
            <div class="w-100"></div>
            <div class="col">Voornaam:</div>
            <div class="col"><?php echo $voornaam?></div>
            <div class="w-100"></div>
            <div class="col">Telefoon:</div>
            <div class="col">+<?php echo $telefoon_landcode?> <?php echo $telefoon?></div>
            <div class="w-100"></div>
            <div class="col">E-mail:</div>
            <div class="col"><?php echo $email?></div>
            <div class="w-100"></div>
            <div class="col">Geboorte:</div>
            <div class="col"><?php echo zetOmNaarDDMMYYYY($geboorte)?></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <?php echo anchor('zwemles/indexIngevuld', 'Terug', array('class' => 'btn btn-success'));?>
    </div>
    <div class="col text-right">
        <?php
            if ($showSubmit) echo form_submit('knop', 'Bevestigen', "class='btn btn-success bevestigingsknop'")
        ?>
        <?php echo form_close()?>
    </div>
</div>