<?php
/**
 * @file aanvragen.php
 */
?>
<?php
    echo haalJavascriptOp("bs_validator.js");
    $attributenFormulier = array(   'id' => 'mijnFormulier2',
                                    'novalidate' => 'novalidate',
                                    'class' => 'needs-validation',
                                    'method' => 'post');
    echo form_open('zwemles/controle', $attributenFormulier);
?>
    <h4>Zwemmer</h4>

    <div class="form-group">
        <?php
        echo form_label('Zwemervaring', 'zwemervaring');

        $dataNiveau = array('id' => 'niveau',
            'name' => 'niveau',
            'class' => 'form-control',
            'placeholder' => 'zwemervaring');


        if($ingevuld) {
            $selected = $zwemmerdata["niveau_nr"];
            echo form_dropdown($dataNiveau, $niveaus, $selected) . "\n";
        }
        else {
            echo form_dropdown($dataNiveau, $niveaus) . "\n";
        }
        ?>
    </div>

    <div class="form-group">
        <?php
        echo form_label('Naam', 'achternaam');

        $dataNaam = array('id' => 'achternaam',
            'name' => 'achternaam',
            'class' => 'form-control',
            'placeholder' => 'Achternaam',
            'required' => 'required');
        if($ingevuld) {
            $selected = $zwemmerdata["naam"];
            echo form_input($dataNaam, $selected) . "\n";
        }
        else
        {
            echo form_input($dataNaam) . "\n";
        }
        ?>
        <div class="valid-feedback">Ingevuld</div>
        <div class="invalid-feedback">Niet ingevuld</div>
    </div>

    <div class="form-group">
        <?php
        echo form_label('Voornaam', 'voornaam');

        $dataVoornaam = array('id' => 'voornaam',
            'name' => 'voornaam',
            'class' => 'form-control',
            'placeholder' => 'Voornaam',
            'required' => 'required');
        if($ingevuld) {
            $selected = $zwemmerdata["voornaam"];
            echo form_input($dataVoornaam, $selected) . "\n";
        }
        else
        {
            echo form_input($dataVoornaam) . "\n";
        }
        ?>
        <div class="valid-feedback">Ingevuld</div>
        <div class="invalid-feedback">Niet ingevuld</div>
    </div>

    <div class="form-group">
        <?php echo form_label('Telefoon', 'telefoon'); ?>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">+</span>
                <?php
                $dataTelefoonCountry = array('id' => 'telefoon_landcode',
                    'type' => 'tel',
                    'name' => 'telefoon_landcode',
                    'class' => 'form-control mr-1',
                    'placeholder' => '32',
                    'required' => 'required',
                    'pattern' => '[0-9]{2,3}',
                    'maxlength' => '3',
                    'style' => 'width:100px;');
                if($ingevuld) {
                    $selected = $zwemmerdata["telefoon_landcode"];
                    echo form_input($dataTelefoonCountry, $selected) . "\n";
                }
                else
                {
                    echo form_input($dataTelefoonCountry) . "\n";
                }
                ?>
            </div>
            <?php
            $dataTelefoon = array('id' => 'telefoon',
                'type' => 'tel',
                'name' => 'telefoon',
                'class' => 'form-control',
                'placeholder' => '47XXXXXXX(X)',
                'required' => 'required',
                'pattern' => '[0-9]{9,10}',
                'maxlength' => '10');
            if($ingevuld) {
                $selected = $zwemmerdata["telefoon"];
                echo form_input($dataTelefoon, $selected) . "\n";
            }
            else
            {
                echo form_input($dataTelefoon) . "\n";
            }
            ?>
            <div class="valid-feedback">Geldig telefoonnummer</div>
            <div class="invalid-feedback">Ongeldig telefoonnummer => 32(X) 47XXXXXXX</div>
        </div>
    </div>

    <div class="form-group">
        <?php
        echo form_label('E-mail', 'email');
        $dataEmail = array('id' => 'email',
            'type' => 'email',
            'name' => 'email',
            'class' => 'form-control',
            'placeholder' => 'E-mail',
            'required' => 'required');
        if($ingevuld) {
            $selected = $zwemmerdata["email"];
            echo form_input($dataEmail, $selected) . "\n";
        }
        else
        {
            echo form_input($dataEmail) . "\n";
        }
        ?>
        <div class="valid-feedback">Geldig e-mailadres</div>
        <div class="invalid-feedback">Ongeldig e-mailadres</div>
    </div>

    <div class="form-group">
        <?php echo form_label('Geboorte zwemmer', 'geboortedatum'); ?>
        <div class="input-group">
            <?php
            $dataGeboorte = array('id' => 'geboortedatum',
                'type' => 'date',
                'name' => 'geboortedatum',
                'class' => 'form-control',
                'required' => 'required',
                'max' => Date("Y-m-d"));
            if($ingevuld) {
                $selected = $zwemmerdata["geboorte"];
                echo form_input($dataGeboorte, $selected) . "\n";
            }
            else
            {
                echo form_input($dataGeboorte) . "\n";
            }
            ?>
            <div class="valid-feedback">Ingevuld</div>
            <div class="invalid-feedback">Niet ingevuld</div>
        </div>
    </div>

    <div class="form-group text-right">
        <?php echo form_submit('knop', 'Volgende', "class='btn btn-success'") ?>
    </div>
<?php
    echo form_close();
?>