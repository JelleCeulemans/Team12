<?php
/**
 * @file overzicht.php
 *
 * View waar het overzicht van de te kopen producten laat zien en waar deze ook in de winkelwagen kunnen worden geplaatst.
 * - gebruikt een Bootstrap-Card
 * -gebruikt een Bootstrap-Modal
 * -krijgt een $producten-object binnen
 */
?>
<div id="Agenda">

</div>
<!-- Modal dialog Toevoegen -->
<div class="modal fade" id="modalToevoegen" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nieuw Moment</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="alert alert-success" id="aanvraagAlert"></div>
            <input type="hidden" name="momentId" id="momentIdInvoer">
            <?php
            $attributenFormulier = array('id' => 'formInvoer', 'novalidate' => 'novalidate', 'class' => 'needs-validation');
            echo form_open('', $attributenFormulier) . "\n";
            ?>
            <div class="modal-body">
                <div class="col-sm-6">
                  <?php
                  $rechten = array();
                  $rechten[0] = 'Zwemfeest';
                  $rechten[1] = 'Zwemles';
                  echo form_label('Feest of Les?', 'lesOfFeest');
                  echo form_dropdown('lesOfFeest', $rechten, 'large' ,'class ="form-control selectpicker lesOfFeest"');
                  ?>
                  <?php echo form_label('Startuur', 'momentStartuur'); ?>
                  <?php
                  echo form_input(array('name' => 'momentStartuur',
                      'id' => 'momentStartuur',
                      'class' => 'form-control',
                      'placeholder' => 'startuur',
                      'required' => 'required',
                      'type' => 'time',
                  ));
                  ?>
                    <div id="meldingStartuur" class="ml-2 text-danger"></div>
                  <?php echo form_label('Stopuur', 'momentStopuur'); ?>
                  <?php
                  echo form_input(array('name' => 'momentStopuur',
                      'id' => 'momentStopuur',
                      'class' => 'form-control',
                      'placeholder' => 'stopuur',
                      'required' => 'required',
                      'type' => 'time',
                  ));
                  ?>
                    <div id="meldingStopuur" class="ml-2 text-danger"></div>
                  <?php
                  $dagen = array('Zondag', 'Maandag', 'Dindag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag');
                  echo form_label('Dag');

                  $options = array('id' => 'weekDag', 'class' => 'form-control selectpicker');
                  echo form_dropdown('weekDag', $dagen, 'Zondag', $options );
                  ?>
                    <div id="meldingWeekdag" class="ml-2 text-danger"></div>
                  <?php
                  if (isset($_POST['lesOfFeest'])==='Zwemles') {
                    echo form_input(array('name' => 'momentNiveau',
                        'id' => 'momentNiveau',
                        'class' => 'form-control',
                        'placeholder' => 'niveau',
                        'required' => 'required',
                    ));
                    echo form_input(array('name' => 'momentAantal',
                        'id' => 'momentAantal',
                        'class' => 'form-control',
                        'placeholder' => 'aantal',
                        'required' => 'required',
                    ));
                  }
                   ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo form_button(array('content' => "Toevoegen", 'id' => 'knopToevoegen', 'class' => 'btn btn-success'));
                ?>
            </div>
            <?php echo form_close() . "\n"; ?>
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
                <input type="hidden" id="momentIdBevestiging">
                <input type="hidden" id="momentKleurBevestiging">
                <div id="boodschap">Wilt u zeker dit moment verwijderen?</div>
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
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/locale/nl-be.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/gcal.js"></script>
<script>
    var  startuur, stopuur, feestofles, weekdag, moment, weekdagValue;
    function validatie1() {
        startuur = $('#momentStartuur').val();
        stopuur = $('#momentStopuur').val();
        moment = $('#lesOfFeest').val();
        feestofles = $("#lesOfFeest option[value='"+moment+"']").text();
        weekdag = $('#weekDag').val();
        console.log(weekdag);
        weekdagValue = $("#weekDag option[value='"+weekdag+"']").text();
        if (startuur && stopuur && feestofles !==0 && weekdagValue !==0) {
            return true;
        }
        else {
            return false;
        }
    }

    function validatieVerkorter (variabele, selector, tekst) {
        if (!variabele || variabele === '0') {
            $('#'+selector).text(tekst).prev().children().addClass('border-danger');
        }
    }

    function validatie2(){
        validatieVerkorter(startuur, 'meldingStartuur', 'Geef startuur in!');
        validatieVerkorter(stopuur, 'meldingStopuur', 'Geef uw einduur in!');
        validatieVerkorter(weekdag, 'meldingWeekdag', 'Kies een weekdag!');
    }

    function verwijderControle(){
        $('#aanvraagAlert').text("").hide();
        $('#meldingStopuur').hide();
        $('#meldingStartuur').hide();
        $('#meldingWeekdag').hide();
        document.getElementById("formInvoer").reset();
    }

    function controleerMoment (startuur, einduur, weekdag) {

        $.ajax({
            type:"GET",
            url:site_url+ "/Moment/controleMoment",
            data:{startuur: startuur, einduur: einduur, weekdag : weekdag},
            success: function (result) {
                console.log("test");
                if (result.length > '0') {
                    $('#aanvraagAlert').text(result).show().removeClass().addClass('alert alert-danger');
                }
                else {
                    if ($(".lesOfFeest").val() == 0) {
                          schrijfFeestMomentWeg();
                        }else {
                          schrijfLesMomentWeg();
                        }
                }
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }


function schrijfFeestMomentWeg() {
    var dataString = $("#formInvoer").serialize(); //zie ook functie controleerDubbelEnSchrijfWegOfToonFout()
    $.ajax({
        type: "POST", //POST ipv GET (informatie uit formulier)
        url: site_url + "/Moment/schrijfAjax_FeestMoment",
        data: dataString,
        success: function (result) {
            $('#modalToevoegen').modal('hide');
            $('#Agenda').fullCalendar('refetchEvents');
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrijfSoortWeg) --\n\n" + xhr.responseText);
        }
    });
}

function schrijfLesMomentWeg() {
    var dataString = $("#formInvoer").serialize(); //zie ook functie controleerDubbelEnSchrijfWegOfToonFout()
    $.ajax({
        type: "POST", //POST ipv GET (informatie uit formulier)
        url: site_url + "/Moment/schrijfAjax_LesMoment",
        data: dataString,
        success: function (result) {
            $('#modalToevoegen').modal('hide');
            $('#Agenda').fullCalendar('refetchEvents');
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrijfSoortWeg) --\n\n" + xhr.responseText);
        }
    });
}

function schrapFeestMoment(momentId) {
    $.ajax({
        type: "GET",
        url: site_url + "/Moment/schrapAjax_FeestMoment",
        data: {momentId: momentId},
        success: function (result) {
            $('#modalBevestiging').modal('hide');
            $('#Agenda').fullCalendar('refetchEvents');
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrapFeestMoment) --\n\n" + xhr.responseText);
        }
    });
}

function schrapLesMoment(momentId) {
    $.ajax({
        type: "GET",
        url: site_url + "/Moment/schrapAjax_LesMoment",
        data: {momentId: momentId},
        success: function (result) {
            $('#modalBevestiging').modal('hide');
            $('#Agenda').fullCalendar('refetchEvents');
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrapLesMoment) --\n\n" + xhr.responseText);
        }
    });
}

function updateLesMoment(momentId, startuur, stopuur, dow) {
    $.ajax({
        type: "GET",
        url: site_url + "/Moment/updateAjax_LesMoment",
        data: {momentId: momentId, startuur: startuur, stopuur: stopuur, dow: dow},
        success: function (result) {
            $('#Agenda').fullCalendar('refetchEvents');
            console.log("oke");
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrapLesMoment) --\n\n" + xhr.responseText);
        }
    });
}

function updateFeestMoment(momentId, startuur, stopuur, dow) {
    $.ajax({
        type: "GET",
        url: site_url + "/Moment/updateAjax_FeestMoment",
        data: {momentId: momentId, startuur: startuur, stopuur: stopuur, dow: dow},
        success: function (result) {
            $('#Agenda').fullCalendar('refetchEvents');
            console.log("oke");
        },
        error: function (xhr, status, error) {
            alert("-- ERROR IN AJAX (schrapLesMoment) --\n\n" + xhr.responseText);
        }
    });
}

    $(document).ready(function() {
        // var json_str = JSON.stringify(events);
        // console.log(json_str);
        $('#aanvraagAlert').hide();

        $('input, select').on("change", function () {
            $('#aanvraagAlert').hide();
            $(this).removeClass('border-danger').parent().next().text('');
        });

        $("#knopToevoegen").click(function () {
            if (validatie1()) {
                controleerMoment(startuur, stopuur, weekdag);
            }
            else {
                validatie2();
            }
        });

        // //Als men op knop 'Ja' klikt in modal venster '#modalBevestiging'
        $("#knopJa").click(function () {
            var momentId = $("#momentIdBevestiging").val(); //soortId uit hidden field van modal venster '#modalBevestiging' halen om te kunnen doorgeven aan schrapSoort()
            if ($("#momentKleurBevestiging").val() == "green") {
              schrapFeestMoment(momentId);
              console.log(momentId);
            }if ($("#momentKleurBevestiging").val() == "blue") {
              schrapLesMoment(momentId);
              console.log(momentId);
            }
        });

        $('input, select').on("change", function () {
            $('#aanvraagAlert').hide();
            $(this).removeClass('border-danger').parent().next().text('');
        });

        //Als men op knop 'Nee' klikt in modal venster '#modalBevestiging'
        $("#knopNee").click(function () {
            $("#modalBevestiging").modal('hide');
        });


        $('#Agenda').fullCalendar({
            themeSystem: 'bootstrap4',
            defaultView: 'agendaWeek',
            nowIndicator: true,
            minTime: "10:00:00",
            maxTime: "21:00:00",
            header: {
                left:   'addEventButton',
                center: 'title',
                right: ''
            },
            customButtons: {
                addEventButton: {
                    text: 'add event',
                    click: function() {
                        verwijderControle();
                        $('#modalToevoegen').modal('show');
                    }
                }
            },
            contentHeight: "auto",
            editable: true,
            resizable: true,
            eventOverlap: false,
            eventClick:function(event,jsEvent,view){
              $("#momentIdBevestiging").val(event.id);
              $("#momentKleurBevestiging").val(event.color);
              $('#modalBevestiging').modal('show');
            },
            eventDrop: function(event, delta, revertFunc) {
              var momentId = event.id;
              var startuur = event.start.format('HH:mm');
              var stopuur = event.end.format('HH:mm');
              var dow = event.start.day();
              if (event.color == "green") {
                updateFeestMoment(momentId, startuur, stopuur, dow);
                console.log(momentId);
              }if (event.color == "blue") {
                updateLesMoment(momentId, startuur, stopuur, dow);
                console.log(momentId);
              }
            },
            eventResize: function(event, delta, revertFunc) {
                var momentId = event.id;
                var startuur = event.start.format('HH:mm');
                var stopuur = event.end.format('HH:mm');
                var dow = event.start.day();
                if (event.color == "green") {
                    updateFeestMoment(momentId, startuur, stopuur, dow);
                    console.log(momentId);
                }if (event.color == "blue") {
                    updateLesMoment(momentId, startuur, stopuur, dow);
                    console.log(momentId);
                }
            },
            eventSources: [
                {
                    url: site_url + "/Moment/haalOp_Zwemfeestmomenten"
                },
                {
                    url: site_url + "/Moment/haalOp_Zwemmomenten"
                },
                {
                    url: site_url + "/Moment/haalOp_Feestdagen"
                }
            ],
        });
        $( "button" ).addClass( "btn" );
        $( "button" ).removeClass( "fc-state-default" );
    });
</script>
