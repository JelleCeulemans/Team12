<?php
/**
 * @file ajax_momentModal.php
 */
?>
<?php

echo '<div class="row justify-content-center">';
echo '<div class="col-md-8">';

echo '<table class ="table"><tbody>';
echo '<tr><th>Klas</th>';
echo '<td>'.form_KlassenClass('klassen','klassenMoment',$klassen,'id','leerjaar', 0,array('class' => "form-control", "size" => "1", "id" => "dropdown",'required' => 'required')).'</td>';
echo '</td></tr>';
echo '<tr><th>Datum</th>';
echo '<td>'.form_input(array("name"=>"datum","id"=>"dateInput","value"=>"",'class' => 'form-control', 'placeholder' => 'Datum',"type"=>"date", 'required' => 'required')).'</td>';
echo '</td></tr>';
echo '<tr><th>Aanwezig</th>';
echo '<td>'.form_input(array("name"=>"aanwezig","id"=>"aanwezigInput","value"=>"",'class' => 'form-control', 'placeholder' => 'Aanwezig',"type"=>"number","min"=>"0", 'required' => 'required')).'</td>';
echo '</td></tr>';

echo '</table>';
echo '<input type="submit" id="momentSubmit" class="form-control btn btn-info" value="Aanmaken">';
echo '</div></div>';
