<?php
/**
 * @file ajax_sluitingsdagen.php
 */
?>
<?php
    if ($sluitingsdagen) { ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Datum</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($sluitingsdagen as $dag) { ?>
                    <tr>
                        <td><?php echo zetOmNaarDDMMYYYY($dag->datum); ?></td>
                        <td>
                            <?php $extraButton = array('class' => 'btn btn-danger schrap', 'data-id' => $dag->id, 'data-toggle' => 'tooltip',
                                "title" => "Sluitingsdag verwijderen");
                            echo form_button("knopVerwijder" . $dag->id, "<i class=\"fas fa-trash-alt\"></i> Verwijder", $extraButton); ?>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    <?php }
    else { echo "<h3>Geen sluitingsdagen in de toekomst</h3>"; }?>
