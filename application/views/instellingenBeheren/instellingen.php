<script>
/**
 * Created by PhpStorm.
 * User: Louis
 * Date: 24/02/2019
 * Time: 18:33
 */

    function controleerDubbelEnSchrijfWegOfToonFout() {
        var isDubbel = false;
        var dataString = $("#formInvoer").serialize();

        $.ajax({
            type: "POST",
            url: site_url + "/admin/controleerJson_DubbelProduct",
            data: dataString,
            success: function (result) {
                isDubbel = result;
                if (!isDubbel) {
                    schrijfProductWeg();
                }
                else {
                    //extra/eigen validatie op bootstrap-manier
                    $("div.invalid-feedback").html("Dit product bestaat reeds"); //validatie-boodschap aanpassen
                    $("#naam").addClass("is-invalid"); //met de klasse "is-invalid" aangeven dat validatie mislukt is
                }
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (controleerDubbelEnSchrijfWegOfToonFout) --\n\n" + xhr.responseText);
                return true;
            }
        });
    }


//haal alle records uit een tabel op

function haalGerechtenOp() {
    $.ajax({
        type: "GET",
        url: site_url + "/admin/haalAjaxOp_Gerechten",
        success: function (result) {
            $("#lijstGerechten").html(result);
            $("#lijstProducten").hide();
            $("#lijstNiveaus").hide();
            $('[data-toggle="tooltip"]').tooltip(); //bootstrap-tooltips activeren op dynamisch (mbv AJAX) gegenereerde content
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (haalGerechtenOp) --\n\n" + xhr.responseText);
        }
    });
}

function haalProductenOp() {
    $.ajax({
        type: "GET",
        url: site_url + "/admin/haalAjaxOp_Producten",
        success: function (result) {
            $("#lijstProducten").html(result);
            $("#lijstGerechten").hide();
            $("#lijstNiveaus").hide();
            $('[data-toggle="tooltip"]').tooltip(); //bootstrap-tooltips activeren op dynamisch (mbv AJAX) gegenereerde content
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (haalProductenOp) --\n\n" + xhr.responseText);
        }
    });
}

function haalNiveausOp() {
    $.ajax({
        type: "GET",
        url: site_url + "/admin/haalAjaxOp_Niveaus",
        success: function (result) {
            $("#lijstProducten").fadeOut();
            $("#lijstGerechten").fadeOut();
            $("#lijstNiveaus").html(result);

            $('[data-toggle="tooltip"]').tooltip(); //bootstrap-tooltips activeren op dynamisch (mbv AJAX) gegenereerde content
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (haalNiveausOp) --\n\n" + xhr.responseText);
        }
    });
}

//items individueel ophalen voor wijziging/verwijdering

//PRODUCT WIJZIGEN
function haalProductOp(productId) {
    $.ajax({
        type: "GET",
        url: site_url + "/Admin/haalJsonOp_Product",
        data: {productId: productId},
        success: function (product) {
            $("#itemIdInvoer").val(productId);

            resetValidatie();
            $("#knop").val("Wijzigen");  //tekst op knop aanpassen; knop is van input-type, dus met val() werken
            $('#modalWijzig').modal('show');
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (haalProductOp) --\n\n" + xhr.responseText);
        }
    });
}

//GERECHT WIJZIGEN
function haalGerechtOp(gerechtId) {
    $.ajax({
        type: "GET",
        url: site_url + "/Admin/haalJsonOp_Gerecht",
        data: {gerechtId: gerechtId},
        success: function (gerecht) {
            $("#gerechtIdInvoer").val(gerecht.id);
            $("#naam").val(gerecht.naam);
            resetValidatie();
            $("#knopGerecht").val("Wijzigen");
            $('#modalWijzig').modal('show');
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (haalSoortOp) --\n\n" + xhr.responseText);
        }
    });
}

//NIVEAU WIJZIGEN
function haalNiveauOp(niveauId) {
    $.ajax({
        type: "GET",
        url: site_url + "/Admin/haalJsonOp_Niveau",
        data: {niveauId: niveauId},
        success: function (niveau) {
            //id van soort invullen in hidden field van modal venster '#modalInvoer'
            //om later te kunnen "doorgeven" aan functie controleerDubbelEnSchrijfWegOfToonFout()
            $("#niveauIdInvoer").val(niveau.id);
            $("#naam").val(niveau.naam);
            resetValidatie();
            $("#knop").val("Wijzigen");  //tekst op knop aanpassen; knop is van input-type, dus met val() werken
            $('#modalWijzig').modal('show');
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (haalSoortOp) --\n\n" + xhr.responseText);
        }
    });
}


//Haal itemID & type van item op van modalscherm
function bepaalTypeEnSchrap(typeItem, id) {
    typeProduct = typeItem;
    itemId = id;
    if (typeProduct = "product") {
        schrapProduct(id);
    } else if (typeProduct = "niveau") {
        schrapNiveau(id);
    } else if (typeProduct = "gerecht") {
        schrapGerecht(id);
    }
}

//ITEMS INDIVIDUEEL OPVRAGEN & SCHRAPPEN
function schrapProduct(productId) {
    $.ajax({
        type: "GET",
        url: site_url + "/Admin/schrapAjax_Product",
        data: {productId: productId},
        success: function (result) {
            $('#modalBevestiging').modal('hide');
            haalProductenOp();
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrapMeeem) --\n\n" + xhr.responseText);
        }
    });
}

function schrapNiveau(niveauId) {
    $.ajax({
        type: "GET",
        url: site_url + "/admin/schrapAjax_Niveau",
        data: {niveauId: niveauId},
        success: function (result) {
            $('#modalBevestiging').modal('hide');
            haalNiveausOp();
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrapNiveau) --\n\n" + xhr.responseText);
        }
    });
}

function schrapGerecht(gerechtId) {
    $.ajax({
        type: "GET",
        url: site_url + "/admin/schrapAjax_Gerecht",
        data: {gerechtId: gerechtId},
        success: function (result) {
            $('#modalBevestiging').modal('hide');
            haalGerechtenOp();
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrapGerecht) --\n\n" + xhr.responseText);
        }
    });
}

function schrijfProductWeg() {
    var dataString = $("#formInvoer").serialize(); //zie ook functie controleerDubbelEnSchrijfWegOfToonFout()

    $.ajax({
        type: "POST", //POST ipv GET (informatie uit formulier)
        url: site_url + "/admin/schrijfAjax_Soort",
        data: dataString,
        success: function (result) {
            $('#modalInvoer').modal('hide');
            haalSoortenOp();
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrijfSoortWeg) --\n\n" + xhr.responseText);
        }
    });
}

function resetValidatie() {
    //bestaande bootstrap-validatie-info verwijderen (klasse 'was-validated' geeft aan dat validatie is gebeurd)
    $("#formInvoer").removeClass("was-validated");

    $("#naam").removeClass("is-invalid"); //zelf-toegevoegde validatie-info verwijderen
    $("div.invalid-feedback").html("Vul dit veld in!"); //originele tekst terug in div.invalid-feedback plaatsen
}

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip(); //bootstrap-tooltips activeren


    //VERSCHILLENDE ITEMS LATEN ZIEN
    $("#knopProducten").click(function () {
        haalProductenOp();
        $("#lijstProducten").show();
        $("#lijstNiveaus").hide();
        $("#lijstGerechten").hide();
    })

    $("#knopNiveaus").click(function () {
        haalNiveausOp();
        $("#lijstNiveaus").show();
        $("#lijstProducten").hide();
        $("#lijstGerechten").hide();
    })

    $("#knopGerechten").click(function () {
        haalGerechtenOp();
        $("#lijstGerechten").show();
        $("#lijstProducten").hide();
        $("#lijstNiveaus").hide();
    })


    //items wijzigen
    $("#lijstProducten").on('click', ".wijzig", function () {
        var productId = $(this).data('productid');
        haalProductOp(productId);
    });

    $("#lijstGerechten").on('click', ".wijzig", function () {
        var gerechtId = $(this).data('gerechtid');
        haalGerechtOp(gerechtId);
    });

    $("#lijstNiveaus").on('click', ".wijzig", function () {
        var niveauId = $(this).data('niveauid');
        haalNiveauOp(niveauId);
    });

    //PRODUCT TOEVOEGEN
    $(".voegtoe").click(function () {
        $("#productIdInvoer").val("0");
        $("#productNaam").val("");
        resetValidatie();

        $("#knopProduct").val("Toevoegen");
        $('#modalInvoer').modal('show');
    });

    //NIVEAU TOEVOEGEN
    $(".voegtoe").click(function () {
        $("#niveauIdInvoer").val("0");
        $("#niveauNaam").val("");
        resetValidatie();

        $("#knopNiveau").val("Toevoegen");
        $('#modalInvoer').modal('show');
    });

    //GERECHT TOEVOEGEN
    $(".voegtoe").click(function () {
        $("#gerechtIdInvoer").val("0");
        $("#gerechtNaam").val("");
        resetValidatie();

        $("#knopGerecht").val("Toevoegen");
        $('#modalInvoer').modal('show');
    });

    //ITEMS SCHRAPPEN
    $("#lijstProducten").on('click', ".schrap", function () {
        var itemId = $(this).data('id');
        var typeItem="product";
        $("#itemId").val(itemId);
        $("#typeItem").val(typeItem);
        $('#modalBevestiging').modal('show');
        bepaalTypeEnSchrap(typeItem, itemId);
    });

    $("#lijstNiveaus").on('click', ".schrap", function () {
        var itemId = $(this).data('id');
        var typeItem="niveau";

        $("#itemIdBevestiging").val(itemId);
        $("#typeItem").val(typeItem);
        $('#modalBevestiging').modal('show');
        bepaalTypeEnSchrap(typeItem, itemId);
    });

    $("#lijstGerechten").on('click', ".schrap", function () {
        var itemId = $(this).data('id');
        var typeItem= "gerecht";
        $("#itemIdBevestiging").val(itemId);
        $("#typeItem").val(typeItem);
        $('#modalBevestiging').modal('show');
        bepaalTypeEnSchrap(typeItem, itemId);
    });



    //Als men op (submit-)knop (met opschrift 'Wijzigen' of 'Toevoegen') klikt in modal venster '#modalInvoer'
    $("#knop").click(function (e) {
        if ($("#formInvoer")[0].checkValidity()) { //controleer standaard bootstrap-validatie (leeg naam-veld)
            e.preventDefault(); //niet opnieuw "submitten", anders werkt serialize() later niet meer
            controleerDubbelEnSchrijfWegOfToonFout();
        }
    });

    //Als men op knop 'Ja' klikt in modal venster '#modalBevestiging'
    $("#knopJa").click(function () {
        var itemId = $("#itemIdBevestiging").val(); //soortId uit hidden field van modal venster '#modalBevestiging' halen om te kunnen doorgeven aan schrapSoort()
        var type = $("#type").val();
        schrapItem(itemId, type);
    });

    //Als men op knop 'Nee' klikt in modal venster '#modalBevestiging'
    $("#knopNee").click(function () {
        $("#modalBevestiging").modal('hide');
    });

    //Validatie resetten als invoervak voor naam terug de focus krijgt
    $("#naam").focus(function () {
        resetValidatie();
    });

    //Validatie resetten als toets wordt ingedrukt in invoervak voor naam
    $("#naam").keydown(function () {
        resetValidatie();
    });
});
</script>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand p-3 border-right" id="knopProducten" href="#">Producten</a>
    <a class="navbar-brand" id="knopNiveaus" href="#">Niveaus</a>
    <a class="navbar-brand" id="knopGerechten" href="#">Gerechten</a>
</nav>

<div id="lijst">

<div class="col-12 mt-3">
    <?php
    echo var_dump($niveaus);
    echo "<div id='lijstNiveaus'>\n";
    $knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "Niveau toevoegen");
    echo "<p>" . form_button('niveaunieuw', "<i class='fas fa-plus'></i> Voeg toe", $knop) . "</p>";
    echo "</div>";
    ?>
</div>

<div class="col-12 mt-3">
    <?php
    echo var_dump($producten);
    echo "<div id='lijstProducten'>\n";
    $knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "Product toevoegen");
    echo "<p>" . form_button('productnieuw', "<i class='fas fa-plus'></i> Voeg toe", $knop) . "</p>";
    echo "</div>";
    ?>
</div>

<div class="col-12 mt-3">
    <?php
    echo "<div id='lijstGerechten'>\n";
    $knop = array("class" => "btn btn-success text-white voegtoe", "data-toggle" => "tooltip", "title" => "Gerecht toevoegen");
    echo "<p>" . form_button('gerechtnieuw', "<i class='fas fa-plus'></i> Voeg toe", $knop) . "</p>";
    echo "</div>";
    ?>
</div>
</div>



<!-- Wijzigvenster -->
<div class="modal fade" id="modalWijzig" role="dialog">
    <div class="modal-dialog">

        <!-- Inhoud invoervenster-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Item wijzigen</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?php
            echo haalJavascriptOp("bs_validator.js"); //bootstrap-validatie
            $attributenFormulier = array('id' => 'formInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation');
            echo form_open('', $attributenFormulier) . "\n";
            ?>
            <div class="modal-body">
                <input type="hidden" name="itemId" id="itemIdInvoer">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <?php echo form_label('Itemnaam', 'itemNaam'); ?>
                        <?php
                        echo form_input(array('name' => 'item naam',
                            'id' => 'itemNaam',
                            'class' => 'form-control',
                            'placeholder' => 'Naam',
                            'required' => 'required',
                        ));

                        ?></div><div class="col-sm-6">
                        <?php echo form_label('Prijs', 'itemprijs'); ?>
                        <?php
                        echo form_input(array('name' => 'itemprijs',
                            'id' => 'itemPrijs',
                            'class' => 'form-control',
                            'placeholder' => 'Prijs',
                            'required' => 'required',
                        ));
                        ?>
                    </div>

                    <?php
                    $rechten = array();
                    $rechten[0] = 'Zwemleerkracht';
                    $rechten[1] = 'Admin';
                    echo form_label('Rol', 'isBeheerder');
                    echo form_dropdown('isBeheerder', $rechten, 'large' ,'class ="form-control selectpicker"');
                    ?>
                </div>
                <div class="invalid-feedback">Vul dit veld in</div>
            </div>
        </div>
        <div class="modal-footer">
            <?php
            //met input-type = submit werken; anders (met button) werkt validatie niet
            //deze knop moet voor de form_close() staan!
            echo "<script>console.log();</script>";
            echo form_submit('', '', array('id' => 'knop', 'class' => 'btn'));
            ?>
        </div>
        <?php echo form_close() . "\n"; ?>
    </div>
</div>
</div>
</div>




<!-- Invoervenster -->
<div class="modal fade" id="modalInvoer" role="dialog">
    <div class="modal-dialog">

        <!-- Inhoud invoervenster-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Item toevoegen</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?php
            echo haalJavascriptOp("bs_validator.js"); //bootstrap-validatie
            $attributenFormulier = array('id' => 'formInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation');
            echo form_open('', $attributenFormulier) . "\n";
            ?>
            <div class="modal-body">
                <input type="hidden" name="itemId" id="itemIdInvoer">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <?php echo form_label('Itemnaam', 'itemNaam'); ?>
                        <?php
                        echo form_input(array('name' => 'productNaam',
                            'id' => 'productId',
                            'class' => 'form-control',
                            'placeholder' => 'Naam',
                            'required' => 'required',
                        ));

                        ?></div><div class="col-sm-6">
                        <?php echo form_label('Prijs', 'productPrijs'); ?>
                        <?php
                        echo form_input(array('name' => 'productPrijs',
                            'id' => 'productPrijs',
                            'class' => 'form-control',
                            'placeholder' => 'Prijs',
                            'required' => 'required',
                        ));
                        ?>
                    </div>

                        <?php
                        $rechten = array();
                        $rechten[0] = 'Zwemleerkracht';
                        $rechten[1] = 'Admin';
                        echo form_label('Rol', 'isBeheerder');
                        echo form_dropdown('isBeheerder', $rechten, 'large' ,'class ="form-control selectpicker"');
                        ?>
                    </div>
                    <div class="invalid-feedback">Vul dit veld in</div>
                </div>
            </div>
        <div class="modal-footer">
            <?php
            //met input-type = submit werken; anders (met button) werkt validatie niet
            //deze knop moet voor de form_close() staan!
            echo "<script>console.log();</script>";
            echo form_submit('', '', array('id' => 'knop', 'class' => 'btn'));
            ?>
        </div>
        <?php echo form_close() . "\n"; ?>
        </div>
    </div>
</div>
</div>

<!-- Modal dialog Bevestiging -->
<div class="modal fade" id="modalBevestiging" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bevestig!</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="itemIdBevestiging">
                <input type="hidden" id="typeItem">
                <div id="boodschap">Wilt u dit voorwerp zeker verwijderen ?</div>
            </div>
            <div class="modal-footer">
                <?php echo form_button(array('content' => "Ja", 'id' => 'knopJa', 'class' => 'btn btn-danger'));
                ?>
                <?php echo form_button(array('content' => "Nee", 'id' => 'knopNee', 'class' => 'btn'));
                ?>
            </div>
        </div>
    </div>
</div>