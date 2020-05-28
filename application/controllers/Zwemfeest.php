<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Class Zwemfeest
 * @brief Controller-klasse voor zwemfeest
 *
 * Controller-klasse met alle methodes die gebruikt worden in zwemfeest
 */
class Zwemfeest extends CI_Controller
{
    /**
     * Zwemfeest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('zwemfeest_model');
        $this->load->model('zwemfeestMoment_model');
        $this->load->helper('notation');
    }

    /**
     * Toont de inschrijfpagina voor zwemfeestjes
     */
    public function index () {
        $data['ontwerper'] = 'Jelle Ceulemans';
        $data['tester'] = 'Jeff Vandenbroeck';
        
        $data['titel'] = 'Zwemfeest aanvragen';
        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');
        $data['gerechten'] = $this->zwemfeest_model->getAllgerechten();
        //$data['momenten'] = array();
        //$data['momenten'] = $this->zwemfeest_model->getAllMomenten();
        $partials = array('inhoud' => 'zwemfeest/zwemfeest');
        $this->template->load('main_master', $partials, $data);
    }

    /**
     * insert een zwemfeest in de database
     */
    public function aanvragen() {
        $voornaam = $this->input->get('voornaam');
        $naam = $this->input->get('naam');
        $telefoon = $this->input->get('telefoon');
        $email = $this->input->get('email');
        $aantal = $this->input->get('aantal');
        $datum = $this->input->get('datum');
        $opmerkingen = $this->input->get('opmerkingen');
        $momentTekst = $this->input->get('momentTekst');
        $gerechtTekst = $this->input->get('gerechtTekst');


        $zwemfeest = new stdClass();
        $zwemfeest->gerechtId = $this->input->get('gerecht');
        $zwemfeest->zwemfeestMomentId = $this->input->get('moment');
        $zwemfeest->opmerking = $opmerkingen;
        $zwemfeest->aantal = $aantal;
        $zwemfeest->voornaam = $voornaam;
        $zwemfeest->achternaam = $naam;
        $zwemfeest->telefoon = $telefoon;
        $zwemfeest->email = $email;
        $zwemfeest->datum = zetOmNaarYYYYMMDD($datum);

        $boodschap = '<p><b>Naam: </b> '.$voornaam. ' ' .$naam.'</p>
                <p><b>Telefoon: </b> '.$telefoon.'</p>
                <p><b>Aantal aanwezigen: </b> '.$aantal.'</p>
                <p><b>Datum: </b> '.$datum.'</p>
                <p><b>Moment: </b> '.$momentTekst.'</p>
                <p><b>Gerecht: </b> '.$gerechtTekst.'</p>
                <p><b>Opmerkingen: </b> '.$opmerkingen.'</p>';

        $id = $this->zwemfeest_model->insert($zwemfeest);

        if ($id) {
            echo 'Uw zwemfeest is aangevraagd en er is een e-mail gestuurd met de bevestiging bevestiging!';
            $this->stuurMail($email, $boodschap);
        }


    }

    /** Verstuurt mail naar inschrijver van zwemfeest
     * @param $to
     * @param $boodschap
     */
    public function stuurMail ($to, $boodschap) {
        $this->load->library('email');
        $this->email->from('kempenrust@jelleceulemans.be', 'Hotel kempenrust');
        $this->email->to($to);
        $this->email->subject('Bevestiging aanvraag zwemfeest');
        $this->email->message($boodschap);

        if (!$this->email->send()) {
            show_error($this->email->print_debugger());
        }
    }

    /**
     * controlleerd of zwemmoment nog niet geboekt is
     */
    public function controleBoeking () {
        $datum = $this->input->get('datum');
        $moment = $this->input->get('moment');

        $momenten = $this->zwemfeest_model->getAllZwemfeesten();

        $controle = "";
        foreach ($momenten as $item) {
            if ($item->zwemfeestMomentId === $moment && $item->datum === zetOmNaarYYYYMMDD($datum)) {
                $controle = "Dit momenten is al bezet, gelieve een ander moment te kiezen";
            }
        }

        echo $controle;
    }

    /**
     * haalt alle zwemfeestmomenten uit de database
     */
    public function momentenLaden () {
        $weekdag = $this->input->get('weekdag');

        $momenten = $this->zwemfeest_model->momentenOphalen($weekdag);
        $data['momenten'] = $momenten;

        $this->load->view('zwemfeest/ajax_momenten', $data);

    }

    /**
     * haalt alle dagen uit de database
     */
    public function dagenLaden() {
        $dagen = $this->zwemfeestMoment_model->getAlleDagen();
        $datepicker = "";
        foreach ($dagen as $dag){
            $datepicker .= "date.getDay() == ";
            $weekdag = $dag->weekdag;
            //if ($weekdag == 7) {
                //$weekdag = 0;
            //}
            $datepicker .= $weekdag;
            $datepicker .= " || ";
        }
        echo substr($datepicker, 0, -4);
    }

    /**
     * haalt alle sluitingsdagen uit de database
     */
    public function sluitingsdagen(){
        $dagen = $this->zwemfeestMoment_model->getSluitingsdagen();
        $sluitingsdagen = "";
        foreach ($dagen as $dag){
             $sluitingsdagen .= "\"" .strval($dag->datum)  . "\", ";

        }
        echo substr($sluitingsdagen, 0, -2);

    }

}