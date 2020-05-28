<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property ZwemMoment_model_model $zwemmoment_model
 * @property Wachtlijst_model $Wachtlijst_model
 * @property Zwemfeest_model $Zwemfeest_model
 * @property ZwemfeestMoment_model $zwemfeestMoment_model
 * @property sluiting_model $sluiting_model
 */

/**
 * @Class Moment
 * @brief Controller-klasse voor moment
 *
 * Controller-klasse met alle methodes die gebruikt worden in moment
 */class Moment extends CI_Controller
{
    /**
     * Moment constructor.
     * Controlleerd of gebruiker is aangemeld
     */
    public function __construct()
    {

        parent::__construct();
        $this->load->helper('form');
        if(! $this->session->userdata('rechten')==1){
            redirect(site_url() . "/home/index");
        }
    }

    /** Weergeven van de momenten beheerpagina.
     */
    public function index(){

        $data['titel'] = 'Moment beheren';
        $data['ontwerper'] = 'Julian Droog';
        $data['tester'] = ' Louis Van Baelen';
        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $partials = array('inhoud' => 'momentBeheren/momentOverzicht');

        $this->template->load('main_master', $partials, $data);

    }

    /** haalt alle zwemmomenten op.
     */
    public function haalOp_Zwemmomenten()
    {

        $this->load->model('ZwemMoment_model');
        $objecten = $this->ZwemMoment_model->getAllById();
        $data['objecten'] = $objecten;
        $response = array();
        $posts = array();
        foreach ($objecten as $object)
        {
            $posts[] = array(
                "id"                 =>  $object->id,
                "title"             => "Zwemles",
                "start"                  =>  str_replace(".",":",$object->startuur),
                "end"            =>  str_replace(".",":",$object->stopuur),
                "dow" =>  $object->weekdag,
                "color"                  =>  "blue"
            );
        }
        $this->output->set_content_type("application/json");
        echo json_encode($posts);
    }

    /** haalt alle zwemfeestmomenten op.
     */
    public function haalOp_Zwemfeestmomenten()
    {

        $this->load->model('ZwemfeestMoment_model');
        $objecten = $this->ZwemfeestMoment_model->getAllById();
        $data['objecten'] = $objecten;
        $response = array();
        $posts = array();
        foreach ($objecten as $object)
        {
            $posts[] = array(
                "id"                 =>  $object->id,
                "title"             => "Zwemfeest",
                "start"                  =>  str_replace(".",":",$object->startuur),
                "end"            =>  str_replace(".",":",$object->stopuur),
                "dow" =>  $object->weekdag,
                "color"                  =>  "green"
            );
        }
        $this->output->set_content_type("application/json");
        echo json_encode($posts);
    }

    /** haalt alle feestdagen op.
     */
    public function haalOp_Feestdagen()
    {

        $this->load->model('sluiting_model');
        $objecten = $this->sluiting_model->get();
        $data['objecten'] = $objecten;
        $response = array();
        $posts = array();
        foreach ($objecten as $object)
        {
            $posts[] = array(
                "start"                  =>  $object->datum,
                "end"            =>  $object->datum . 'T23:00:00',
                "rendering" => "background",
                "backgroundColor"                  =>  "red"
            );
        }
        $this->output->set_content_type("application/json");
        echo json_encode($posts);
    }
    /**
     * insert data van feestmoment in database
     * @see ZwemfeestMoment_model::insert()
     * @see momentBeheren/momentOverzicht.php
     */
    public function schrijfAjax_FeestMoment()
    {
        $moment = new stdClass();
        $moment->id = $this->input->post('momentId');
        $moment->weekdag = $this->input->post('weekDag');
        $moment->startuur = $this->input->post('momentStartuur');
        $moment->stopuur = $this->input->post('momentStopuur');

        $this->load->model('ZwemfeestMoment_model');
        $this->ZwemfeestMoment_model->insert($moment);
    }
    /**
     * insert data van lesmoment in database
     * @see ZwemMoment_model::insert()
     * @see momentBeheren/momentOverzicht.php
     */
    public function schrijfAjax_LesMoment()
    {
        $moment = new stdClass();
        $moment->id = $this->input->post('momentId');
        $moment->weekdag = $this->input->post('weekDag');
        $moment->startuur = $this->input->post('momentStartuur');
        $moment->stopuur = $this->input->post('momentStopuur');
        $moment->niveauId = 1;
        $moment->maximumAantal = 10;

        $this->load->model('ZwemMoment_model');
        $this->ZwemMoment_model->insert($moment);
    }
    /**
     * Update data van de aangepaste zwemles in database
     * @see ZwemMoment_model::update()
     * @see momentBeheren/momentOverzicht.php
     */
    public function updateAjax_LesMoment()
    {
        $moment = new stdClass();
        $moment->id = $this->input->get('momentId');;
        $moment->weekdag = $this->input->get('dow');;
        $moment->startuur = str_replace(":",".",$this->input->get('startuur'));
        $moment->stopuur = str_replace(":",".",$this->input->get('stopuur'));
        $moment->niveauId = 2;
        $moment->maximumAantal = 10;

        $this->load->model('ZwemMoment_model');
        $this->ZwemMoment_model->update($moment);
    }
    /**
     * Update data van het aangepaste feestmoment in database
     * @see ZwemfeestMoment_model::update()
     * @see momentBeheren/momentOverzicht.php
     */
    public function updateAjax_FeestMoment()
    {
        $moment = new stdClass();
        $moment->id = $this->input->get('momentId');;
        $moment->weekdag = $this->input->get('dow');;
        $moment->startuur = str_replace(":",".",$this->input->get('startuur'));
        $moment->stopuur = str_replace(":",".",$this->input->get('stopuur'));

        $this->load->model('ZwemfeestMoment_model');
        $this->ZwemfeestMoment_model->update($moment);
    }
    /**
     * Delete zwemmers van database
     * Eerst moet hiervoor hun status verwijderd worden
     * @see Wachtlijst_model::deleteWhereMoment()
     * @see ZwemMoment_model::delete()
     * @see momentBeheren/momentOverzicht.php
     */
    public function schrapAjax_LesMoment()
    {
        $momentId = $this->input->get('momentId');

        $this->load->model('Wachtlijst_model');
        $this->Wachtlijst_model->deleteWhereMoment($momentId);

        $this->load->model('ZwemMoment_model');
        $this->ZwemMoment_model->delete($momentId);
    }
    /**
     * Delete feestmoment van database
     * Eerst moet hiervoor hun status verwijderd worden
     * @see Wachtlijst_model::deleteWhereMoment()
     * @see ZwemfeestMoment_model::delete()
     * @see momentBeheren/momentOverzicht.php
     */
    public function schrapAjax_FeestMoment()
    {
        $momentId = $this->input->get('momentId');

        $this->load->model('Zwemfeest_model');
        $this->Zwemfeest_model->deleteWhereMoment($momentId);

        $this->load->model('ZwemfeestMoment_model');
        $this->ZwemfeestMoment_model->delete($momentId);
    }
    /**
     * Controleert op dubbel
     * @see zwemmersBeheren/momentOverzicht.php
     * @param controle wordt teruggegeven als er een zwemles of zwemfeest op hetzelfde moment bestaat
     */
    public function controleMoment () {
        $weekdag = $this->input->get('weekdag');
        $startuur = $this->input->get('startuur');
        $stopuur = $this->input->get('stopuur');

        $this->load->model('ZwemfeestMoment_model');
        $momentenFeesten = $this->ZwemfeestMoment_model->getAllZwemfeesten();

        $this->load->model('ZwemMoment_model');
        $momentenLessen = $this->ZwemMoment_model->getAllZwemlessen();

        $controle = "";

        foreach ($momentenFeesten as $item) {
            if ($item->weekdag === $weekdag && ($item->startuur === str_replace(":",".",$startuur) or $item->stopuur === str_replace(":",".",$stopuur))) {
                $controle = "Dit momenten is al bezet, gelieve een ander dag of uur te kiezen";
            }
        }

        foreach ($momentenLessen as $item) {
            if ($item->weekdag === $weekdag && ($item->startuur === str_replace(":",".",$startuur) or $item->stopuur === str_replace(":",".",$stopuur))) {
                $controle = "Dit momenten is al bezet, gelieve een ander dag of uur te kiezen";
            }
        }

        echo $controle;
    }



}
