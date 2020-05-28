<?php
/**
 * @file ajax_nieuweSchoolModal.php
 */
?>
<?php

echo '<div class="row justify-content-center">';
echo '<div class="col-md-8">';
echo form_open();
echo '<table class ="table"><tbody>';

echo '<tr><th>Naam</th>';
echo '<td>'.form_input(array("name"=>"naam","id"=>"naam","value"=>"",'class' => 'form-control naam', 'placeholder' => 'Naam',"type"=>"text", 'required' => 'required')).'</td>';
echo '</td></tr>';

echo '<tr><th>Straat</th>';
echo '<td>'.form_input(array("name"=>"straat","id"=>"straat","value"=>"",'class' => 'form-control straat', 'placeholder' => 'Straat',"type"=>"text", 'required' => 'required')).'</td>';
echo '</td></tr>';

echo '<tr><th>Huisnr</th>';
echo '<td>'.form_input(array("name"=>"huisnr","id"=>"huisnr","value"=>"",'class' => 'form-control huisnr', 'placeholder' => 'Huisnr',"type"=>"text", 'required' => 'required')).'</td>';
echo '</td></tr>';

echo '<tr><th>Postcode</th>';
echo '<td>'.form_input(array("name"=>"postcode","id"=>"postcode","value"=>"",'class' => 'form-control postcode', 'placeholder' => 'Postcode',"type"=>"text", 'required' => 'required')).'</td>';
echo '</td></tr>';

echo '<tr><th>Telefoonnr</th>';
echo '<td>'.form_input(array("name"=>"telefoon","id"=>"telefoon","value"=>"",'class' => 'form-control telefoon', 'placeholder' => '0455 55 55 55',"tel"=>"text", 'required' => 'required')).'</td>';
echo '</td></tr>';

echo '<tr><th>email</th>';
echo '<td>'.form_input(array("name"=>"email","id"=>"email","value"=>"",'class' => 'form-control email', 'placeholder' => 'jou@email.com',"type"=>"email", 'required' => 'required')).'</td>';
echo '</td></tr>';

echo '</table>';
echo '<input type="submit" id="schoolSubmit" class="form-control btn btn-info" value="Aanmaken">';
echo '</div></div>';
