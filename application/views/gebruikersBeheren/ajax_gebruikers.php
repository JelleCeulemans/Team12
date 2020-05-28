<?php
/**
 * @file ajax_gebruikers.php
 */
?>
<h4>Accounts beheren</h4>
<div class="table-responsive">
<table class="table">
    <thead>
    <tr>
        <th scope="col">Gebruikernaam</th>
        <th scope="col">E-mailadres</th>
        <th scope="col">Rol</th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($gebruikers as $gebruiker) {
        if ($gebruiker->id != 13) {
          echo "<tr>\n";
          echo "<td>" . $gebruiker->username . "</td>";
          echo "<td class=\"kolomgroot\">" . $gebruiker->email . "</td>";
          if($gebruiker->isBeheerder == 1) {
              echo "<td>Administrator</td>";
          }else {
              echo "<td>Zwemleerkracht</td>";
          }

          echo "<td>";
          $extraButton = array('class' => 'btn btn-success wijzig', 'data-gebruikerid' => $gebruiker->id, 'data-toggle' => 'tooltip',
              "title" => "Gebruiker wijzigen");
          echo form_button("knopwijzig" . $gebruiker->id, "<i class=\"fas fa-edit\"></i> Wijzig", $extraButton);
          echo "</td>";

          echo "<td>";
          $extraButton = array('class' => 'btn btn-danger schrap', 'data-gebruikerid' => $gebruiker->id, 'data-toggle' => 'tooltip',
              "title" => "Gebruiker verwijderen");
          echo form_button("knopverwijder" . $gebruiker->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton);
          echo "</td>\n";
          echo "</tr>\n";
        }
    }
    ?>
    </tbody>
</table>
</div>
