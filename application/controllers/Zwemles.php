<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Template $template
 * @property Zwemles_model $zwemles_model
 */

/**
 * @Class Zwemles
 * @brief Controller-klasse voor zwemles
 *
 * Controller-klasse met alle methodes die gebruikt worden in zwemles
 */
class Zwemles extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('zwemles_model');
        $this->load->helper('notation');
        $this->load->library('session');
    }

    /**
     * Zorgt ervoor dat de indexpagina niet ingevuld is.
     * @see Zwemles::indexViewPage()
     * @see zwemles/aanvragen.php
     */
    public function index()
    {
        $data['ingevuld'] = false;
        $this->indexViewPage($data);
    }

    /**
     * Zorgt ervoor dat de indexpagina ingevuld is.
     * Haalt alle data van bevestiging pagina op via sessie.
     * @see Zwemles::controle()
     * @see Zwemles::indexViewPage()
     * @see zwemles/aanvragen.php
     */
    public  function indexIngevuld()
    {

        if($this->session->has_userdata('zwemmerdata'))
        {

            $data['ingevuld'] = true;
            $data['zwemmerdata'] = $this->session->userdata('zwemmerdata');
        }
        else {
            $data['ingevuld'] = false;
        }

        $this->indexViewPage($data);
    }

    /**
     * Laat de indexpagina zien (zwemles aanvragen).
     * Haalt de niveaus op om te selecteren.
     * @param $data de data van die ingevuld wordt op de pagina als $data['ingevuld'] = true
     * @see Zwemles::controle()
     * @see Zwemles::indexViewPage()
     * @see zwemles/aanvragen.php
     */
    public function indexViewPage($data) {

        $data['ontwerper'] = 'Robbe Baeyens';
        $data['tester'] = 'Jelle Ceulemans';

        $data['titel'] = 'Zwemles aanvragen';

        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');

        $data['niveaus'] = $this->zwemles_model->getAllNiveaus();

        $partials = array('inhoud' => 'zwemles/aanvragen');
        $this->template->load('main_master', $partials, $data);
    }

    /**
     * Laat de bevestigings pagina zien
     * Zet alle data die ingevuld werd in een sessie
     * @see Zwemles_model::getNiveauById()
     * @see Zwemles_model::getAllMomentenInfo()
     * @see Zwemles_model::getAllMomenten()
     * @see zwemles/bevestiging.php
     */
    public function controle() {
        $data['ontwerper'] = 'Robbe Baeyens';
        $data['tester'] = 'Jelle Ceulemans';

        $data['titel'] = 'Zwemles aanvraag Bevestigen';

        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');

        $niveau = $this->input->post("niveau");
        $data['niveau_nr'] = $niveau;
        $data['niveau'] = $this->zwemles_model->getNiveauById($niveau);
        $data['naam'] = $this->input->post("achternaam");
        $data['voornaam'] = $this->input->post("voornaam");
        $data['telefoon_landcode'] = $this->input->post("telefoon_landcode");
        $data['telefoon'] = $this->input->post("telefoon");
        $data['email'] = $this->input->post("email");
        $data['geboorte'] = $this->input->post("geboortedatum");

        $this->session->set_userdata('zwemmerdata', $data);

        $data['momenten'] = $this->zwemles_model->getAllMomentenInfo($data['niveau_nr']);
        $data['momentenIds'] = $this->zwemles_model->getAllMomenten($data['niveau_nr']);

        $partials = array('inhoud' => 'zwemles/bevestiging');
        $this->template->load('main_master', $partials, $data);
    }

    /**
     * Gaat terug naar homepage wanneer bevestigen wordt geklikt
     * Zet de ingevoerde zwemmer in de database
     * @see Zwemles_model::insertZwemmer()
     * @see Zwemles_model::insertZwemmerWachtlijst()
     * @see Home/home.php
     */
    public function bevestigen()
    {
        $data['zwemmerdata'] = $this->session->userdata('zwemmerdata');

        $tz = 'Europe/Brussels';
        $dt = new DateTime("now", new DateTimeZone($tz));
        echo $dt->format('d.m.Y, H:i:s');

        $zwemles = new stdClass();
        $zwemles->achternaam = $data["zwemmerdata"]["naam"];
        $zwemles->voornaam = $data["zwemmerdata"]["voornaam"];
        $zwemles->email = $data["zwemmerdata"]["email"];
        $zwemles->telefoon = "+" . $data["zwemmerdata"]["telefoon_landcode"] . " " . $data["zwemmerdata"]["telefoon"];
        $zwemles->niveauId = $data["zwemmerdata"]["niveau_nr"];
        $zwemles->geboortedatum = $data["zwemmerdata"]["geboorte"];
        $zwemles->inschrijfdatum = date("Y-m-d h:m:s");
        $data['zwemmerdata'] = $this->zwemles_model->insertZwemmer($zwemles);

        $zwemmer = new stdClass();
        $zwemmer->statusId = 1;
        $zwemmer->zwemmerId = $data['zwemmerdata'];
        $data['momenten'] = $this->input->post("momenten[]");
        foreach ($data['momenten'] as $moment) {
            $zwemmer->zwemMomentId = $moment;
            $data['beschikbaardata'] = $this->zwemles_model->insertZwemmerWachtlijst($zwemmer);
        }

        redirect(site_url() . "/home/index");
    }
}