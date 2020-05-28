<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Zwemfeest_model $zwemfeest_model
 * @property Bestelling_model $bestelling_model
 * @property Bestellijn_model $bestellijn_model
 */

/**
 * @Class Boeking
 * @brief Controller-klasse voor boeking
 *
 * Controller-klasse met alle methodes die gebruikt worden in boeking
 */
class Boeking extends CI_Controller
{
    /**
     * Boeking constructor.
     * Controlleerd of gebruiker is aangemeld.
     */
    public function __construct()
    {
        parent::__construct();
        if(! $this->session->userdata('rechten')==1){
            redirect(site_url() . "/home/index");
        }
    }

    /**
     * Toont de pagina om boekingen te bekijken.
     */
    public function index() {
        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');
        if($this->session->userdata('rechten')==1){
            $data['titel'] = 'Overzicht boekingen';

            $data['ontwerper'] = 'Sebastiaan Bergmans';
            $data['tester'] = 'Jeff Vandenbroeck';

            $partials = array('hoofding' => 'main_header',
                'inhoud' => 'boeking/overzicht');

            $this->template->load('main_master', $partials, $data);
        }
        else{
            redirect(site_url() . "/home/index");
        }

    }

    /**
     * haalt alle zwemfeestjes uit de database.
     */
    public function haalAjaxOp_Zwemfeestjes()
    {
        $this->load->model('zwemfeest_model');
        $data['zwemfeestjes'] = $this->zwemfeest_model->getAllVanafVandaag();

        $this->load->view('boeking/ajax_zwemfeestLijst', $data);
    }

    /**
     * Haalt alle bestellingen uit de database.
     */
    public function haalAjaxOp_Bestellingen()
    {
        $this->load->model('bestelling_model');
        $data['bestellingen'] = $this->bestelling_model->getAllNietAfgehandeld();

        $this->load->view('boeking/ajax_bestellingLijst', $data);
    }

    /** Haalt alle bestelde producten van een bepaalde bestelling uit de database.
     * @param $bestellingId
     */
    public function haalAjaxOp_Bestellingdetails($bestellingId)
    {
        $this->load->model('bestellijn_model');
        $data['bestellingdetails'] = $this->bestellijn_model->getBestellijnWithProduct($bestellingId);

        $this->load->view('boeking/ajax_bestellingdetails', $data);
    }

    /** update een bepaalde bestelling als afgehandeld in de database.
     * @param $bestellingId
     */
    public function Ajax_BestellingAfhandelen($bestellingId){
        $this->load->model('bestelling_model');
        $this->bestelling_model->afhandel($bestellingId);
    }
}