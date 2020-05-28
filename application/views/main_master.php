<?php
/**
 * @file main_master.php
 */
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $titel ?></title>
    <!-- Bootstrap CSS -->
    <?php echo pasStylesheetAan("bootstrap.css"); ?>
    <?php echo pasStylesheetAan("simple-sidebar.css"); ?>
    <?php echo pasStylesheetAan("jquery-ui.min.css"); ?>
    <?php echo pasStylesheetAan("Main.css"); ?>

    <?php echo haalJavascriptOp("jquery-3.3.1.js"); ?>
    <?php echo haalJavascriptOp("bootstrap.bundle.js"); ?>
    <?php echo haalJavascriptOp("bs_validator.js"); ?>
    <?php echo haalJavascriptOp("jquery-ui.min.js"); ?>
    <?php echo haalJavascriptOp("datepicker.js"); ?>


    <!-- font awesome (CDN) -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
          integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.css" />
    <link href="<?php echo base_url()?>/assets/images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <script>
        var site_url = '<?php echo site_url(); ?>';
        var base_url = '<?php echo base_url(); ?>';
        function aanmelden(gebruikersnaam, wachtwoord) {
            $.ajax({
                type:"POST",
                url:site_url+"/Login/aanmelden",
                data:{gebruikersnaam: gebruikersnaam, wachtwoord: wachtwoord},
                success: function (result) {
                    //console.log(result);
                    if (result) {
                        $('#loginAlert').text(result).show();
                    }
                    else {
                        location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
                }
            });
        }
        function openModalLogin() {
            $.ajax({
                type:"GET",
                url:site_url+"/login/openModalLogin",
                success: function (result) {
                    $("#resultaat").html(result);
                    $('#aanmeldvenster').modal('show');
                    $('#loginAlert').hide();
                },
                error: function (xhr, status, error) {
                    alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
                }
            });
        }
        function openModalWachtwoord() {
            $.ajax({
                type:"GET",
                url:site_url+"/login/wachtwoordHerstellenPage",
                success: function (result) {
                    $("#resultaat").html(result);
                    $('#aanmeldvenster').modal('show');
                },
                error: function (xhr, status, error) {
                    alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
                }
            });
        }
        function veranderWachtwoord(email) {
            $.ajax({
                type:"GET",
                url:site_url+"/login/wachtwoordHerstellenMail",
                data:{email:email},
                success: function (result) {
                    $("#resultaat").html(result);
                    $('#aanmeldvenster').modal('show');
                },
                error: function (xhr, status, error) {
                    alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
                }
            });
        }
        $(document).ready(function () {
            var rechten = $('#rechten').val();
            $("#leerkracht").hide();
            $(".admin").hide();
            if(rechten != ""){
                $("#leerkracht").show();
                if(rechten == 1){
                    $(".admin").show();
                }
            }
            $('.aanmeldKnop').click(function () {
                openModalLogin();
            });
            $('#resultaat').on('click','#wachtwoordKnop',function () {
                openModalWachtwoord();
            });
            $('#resultaat').on('click','#wachtwoordHerstellen',function () {
                var email = ($('#inputEmail').val());
                veranderWachtwoord(email);
            });
            $('#resultaat').on('click','#aanmeldSubmit',function () {
                var gebruikersnaam = $('#gebruikersnaam').val();
                var wachtwoord = $('#wachtwoord').val();
                if (gebruikersnaam && wachtwoord) {
                    aanmelden(gebruikersnaam, wachtwoord);
                }
                if (!gebruikersnaam) {
                    $('#gebruikersnaamMelding').text('Geef uw gebruikersnaam in!');
                    $('#gebruikersnaam').addClass('border-danger');
                }
                if (!wachtwoord) {
                    $('#wachtwoordMelding').text('Geef uw wachtwoord in!');
                    $('#wachtwoord').addClass('border-danger');
                }
            });

            $('#resultaat').on('change', 'input', function () {
                $(this).removeClass('border-danger').parent().next().text('');
            });
            $('#aanmeldvenster').click(function () {
                $('#loginAlert').hide();
            });
        });
    </script>
</head>
<style>
</style>
<body class="mr-0 p-0">

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-dark text-white" id="sidebar-wrapper">
        <?php if ($gebruikersnaam) {
            echo '<div class="p-1" id="gebruiker"><i class="fab fa-get-pocket"></i> Welkom ' . $gebruikersnaam.'</div>';
        }?>
        <div class="list-group list-group-flush text-white">
            <div class="list-group-item bg-dark">
                <?php
                if ($rechten === '0' || $rechten === '1') {
                    //echo form_button('', 'Afmelden', array('class' => 'btn btn-success', 'id' => 'afmeldKnop'));
                    echo anchor('/login/afmelden', 'Afmelden', array('class' => 'btn btn-success'));
                }
                else {
                    //echo anchor('/login/aanmelden', array('class' => 'btn btn-success'));
                    echo form_button('', 'Aanmelden', array('class' => 'btn btn-success aanmeldKnop'));
                }
                ?>
            </div>

            <?php
            echo navLink('/home/index', 'Home');
            echo navLink('/zwemles/index', 'Zwemles aanvragen');
            echo navLink('/zwemfeest/index', 'Zwemfeest aanvragen');
            echo navLink('/product/index', 'Webshop');
            ?>
            <?php
            if(isset($rechten)){
                echo navLink('/wachtlijst/index', 'Wachtlijst beheren');

                if($rechten == 1){
                echo '<li class="list-group-item list-group-item-action text-light bg-dark border-dark p-0">';
                echo '<a href="#leerkrachtSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle list-group-item list-group-item-action text-light bg-dark border-dark">Admin</a>';
                echo '<ul class="collapse list-unstyled" id="leerkrachtSubmenu">';
                    echo "<li>" . navLink('/Gebruiker/index', 'Gebruikers beheren') . "</li>";
                    echo "<li>" . navLink('/Zwemmer/index', 'Zwemmers beheren') . "</li>";
                    echo "<li>" . navLink('/instelling/index', 'Instellingen beheren') . "</li>";
                    echo "<li>" . navLink('/school/index', 'Scholen beheren') . "</li>";
                    echo "<li>" . navLink('/boeking/index', 'Overzicht boekingen') . "</li>";
                    echo "<li>" . navLink('/moment/index', 'Agenda beheren') . "</li>";
                echo '</ul>';
                echo '</li>';


                }

            }
            ?>


            <div>
            <footer class="m-3 p-2 small footer card border-dark text-dark">
                <?php echo "<h5>Team:</h5>" ?>
                <?php echo "<p>12</p>" ?>
                <?php echo "<h5>Opdrachtgever</h5>" ?>
                <?php echo "<p>Christine Mangelschots</p>" ?>
                <?php echo "<h5>Pagina door:</h5>" ?>
                <?php echo "<p>Ontwerper: $ontwerper</p>" ?>
                <?php echo "<p>Tester: $tester</p>" ?>
            </footer>
            </div>
        </div>
    </div>


    <div id="page-content-wrapper">


        <nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-dark" id ="gsmnav">
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" data-target="#navbarToggler" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarToggler">
                <input type="hidden" id="rechten" value="<?php
                if(isset($rechten)){
                    echo $rechten;
                }
                ?>">
                <li class="nav-item">
                    <?php echo anchor('/home/index', 'Hotel kempenrust', array('class' => 'navbar-brand')); ?>
                </li>
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <?php echo anchor('/zwemles/index', 'Zwemles aanvragen', array('class' => 'nav-link')); ?>
                    </li>
                    <li class="nav-item">
                        <?php echo anchor('/zwemfeest/index', 'Zwemfeest aanvragen', array('class' => 'nav-link')); ?>
                    </li>
                    <li class="nav-item">
                        <?php echo anchor('/product/index', 'Webshop', array('class' => 'nav-link')); ?>
                    </li>
                    <div id="leerkracht">
                        <li class="dropdown leerkracht">
                            <a class="dropdown-toggle nav-link" data-toggle="dropdown" href="#">Leerkracht
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu navbar-dark bg-dark">
                                <li class="nav-item">
                                    <?php echo anchor('/wachtlijst/index', 'Wachtlijst beheren', array('class' => 'nav-link')) ?>
                                </li>
                            </ul>
                        </li>
                    </div>

                    <li class="dropdown admin">
                        <a class="dropdown-toggle nav-link" data-toggle="dropdown" href="#">Administrator
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu navbar-dark bg-dark">
                            <li class="nav-item">
                                <?php echo anchor('/Gebruiker/index', 'Gebruikers beheren', array('class' => 'nav-link')) ?>
                            </li>
                            <li class="nav-item">
                                <?php echo anchor('/Zwemmer/index', 'Zwemmers beheren', array('class' => 'nav-link')) ?>
                            </li>
                            <li class="nav-item">
                                <?php echo anchor('/instelling/index', 'Instellingen beheren', array('class' => 'nav-link')) ?>
                            </li>
                            <li class="nav-item">
                                <?php echo anchor('/school/index', 'Scholen beheren', array('class' => 'nav-link')) ?>
                            </li>
                            <li class="nav-item">
                                <?php echo anchor('/boeking/index', 'Overzicht boekingen', array('class' => 'nav-link')) ?>
                            </li>
                            <li class="nav-item">
                                <?php echo anchor('/moment/index', 'Agenda beheren', array('class' => 'nav-link')) ?>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="align-right">
                <?php
                if ($rechten === '0' || $rechten === '1') {
                    //echo form_button('', 'Afmelden', array('class' => 'btn btn-success', 'id' => 'afmeldKnop'));
                    echo anchor('/login/afmelden', 'Afmelden', array('class' => 'btn btn-success'));
                }
                else {
                    //echo anchor('/login/aanmelden', array('class' => 'btn btn-success'));
                    echo form_button('', 'Aanmelden', array('class' => 'btn btn-success aanmeldKnop'));
                }
                ?>
            </div>
        </nav>

        <div class="container-fluid m-0 p-0">
            <div class="jumbotron bg-success p-3 text-white m-0">
                <h1 class="p-0">Hotel kempenrust</h1>
                <p>Team 12</p>
            </div>
            <div class="container my-4">


                <div class="row">
                    <div class="col-12 mb-2">
                        <h2><?php echo $titel; ?></h2>
                    </div>
                </div>
                <?php echo $inhoud; ?>

                <hr>

            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="aanmeldvenster" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="resultaat"></div>

        </div>
    </div>
</div>
</body>
</html>