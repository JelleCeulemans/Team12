<?php
/**
 * @file overzicht.php
 */
?>
<script>
    function toonZwemfeestjes(){
        $.ajax({
            type: "GET",
            url: site_url + "/boeking/haalAjaxOp_Zwemfeestjes",
            success: function (result) {
                $("#boekinglijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalScholenOp) --\n\n" + xhr.responseText);
            }
        });
    }

    function toonBestellingen(){
        $.ajax({
            type: "GET",
            url: site_url + "/boeking/haalAjaxOp_Bestellingen",
            success: function (result) {
                $("#boekinglijst").html(result);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX (haalScholenOp) --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        toonZwemfeestjes();
        $('[data-toggle="tooltip"]').tooltip();
        $('#modalDetails').modal('hide');

        $("#knopZwemfeestjes").click(function () {
            toonZwemfeestjes();
        })
        $("#knopBestellingen").click(function () {
            toonBestellingen();
        })
    });
</script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand p-3 border-right" id="knopZwemfeestjes" href="#">Zwemfeestjes</a>
    <a class="navbar-brand" id="knopBestellingen" href="#">Bestellingen</a>
</nav>
<div id='boekinglijst'></div>

