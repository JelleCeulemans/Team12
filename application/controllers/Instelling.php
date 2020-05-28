<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*! De instelling controller
* \brief bevat de functies om de instellingen te beheren
 */

/**
 * @property ZwemMoment_model $ZwemMoment_model
 * @property Niveau_model $Niveau_model
 * @property Wachtlijst_model $Wachtlijst_model
 * @property Zwemmer_model $Zwemmer_model
 * @property Gerecht_model $Gerecht_model
 * @property Zwemfeest_model $Zwemfeest_model
 * @property Nieuws_model $Nieuws_model
 */

/**
 * @Class Instelling
 * @brief Controller-klasse voor instelling
 *
 * Controller-klasse met alle methodes die gebruikt worden in instelling
 */
class Instelling extends CI_Controller
{
    /**
     * Instelling constructor.
     * Controlleerd of de gebruiker is aangemeld.
     */
    public function __construct()
    {

        parent::__construct();
        $this->load->model('sluiting_model');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('notation');
        if(! $this->session->userdata('rechten')==1){
            redirect(site_url() . "/home/index");
        }
    }

    /** Weergeven van de instellingen beheerpagina.
     */
    public function index() {

        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $data['titel'] = 'Instellingen beheren';

        $data['ontwerper'] = 'Louis Van Baelen';
        $data['tester'] = 'Julian Droog';

        $this->load->model('Niveau_model');
        $data['niveaus'] = $this->Niveau_model->getAll();

        $partials = array('hoofding' => 'main_header',
            'inhoud' => 'instelling/zwemgroepoverzicht');

        $this->template->load('main_master', $partials, $data);

    }

    /** haalt alle zwemgroepen op.
     */
    public function haalAjaxOp_Zwemgroepen()
    {

        $this->load->model('ZwemMoment_model');
        $zwemgroepen = $this->ZwemMoment_model->getAllById();

        foreach($zwemgroepen as $zwemgroep){
            $this->load->model('Niveau_model');
            $zwemgroep->niveau = $this->Niveau_model->get($zwemgroep->niveauId);
        }

        $data['zwemgroepen'] = $zwemgroepen;

        $this->load->view('instelling/ajax_zwemgroepen', $data);
    }

    /**
     * Schrijft een zwemgroep weg in de database.
     */
    public function schrijfAjax_Zwemgroep()
    {

        $zwemgroep = new stdClass();
        $zwemgroep->id = $this->input->post('id');
        $zwemgroep->weekdag = $this->input->post('weekdag');
        $zwemgroep->startuur = $this->input->post('startuur');
        $zwemgroep->stopuur = $this->input->post('stopuur');
        $zwemgroep->niveauId = $this->input->post('niveau');
        $zwemgroep->maximumAantal = $this->input->post('maximumAantal');

        $this->load->model('ZwemMoment_model');

        if ($zwemgroep->id == 0) {
            $this->ZwemMoment_model->insert($zwemgroep);
        } else {
            $this->ZwemMoment_model->update($zwemgroep);
        }
    }

    /** Haalt een zwemgroep uit de database en stuurt deze door naar de view in JSON code.
     */
    public function haalJsonOp_Zwemgroep()
    {

        $id = $this->input->get('id');

        $this->load->model('ZwemMoment_model');
        $object = $this->ZwemMoment_model->get($id);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /** Verwijderd een bepaalde zwemgroep uit de database
     */
    public function schrapAjax_Zwemgroep()
    {

        $id = $this->input->get('id');

        $this->load->model('Wachtlijst_model');
        $this->Wachtlijst_model->deleteWhereMoment($id);

        $this->load->model('ZwemMoment_model');
        $this->ZwemMoment_model->delete($id);
    }

    /** Weergeven van de niveau beheerpagina.
     */
    public function niveau() {

        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $data['titel'] = 'Instellingen beheren';

        $data['ontwerper'] = 'Louis Van Baelen';
        $data['tester'] = 'Julian Droog';

        $partials = array('hoofding' => 'main_header',
            'inhoud' => 'instelling/niveauoverzicht');

        $this->template->load('main_master', $partials, $data);

    }

    /** haalt alle niveaus op.
     */
    public function haalAjaxOp_Niveaus()
    {

        $this->load->model('Niveau_model');
        $data['niveaus'] = $this->Niveau_model->getAll();

        $this->load->view('instelling/ajax_niveaus', $data);
    }

    /**
     * Schrijft een niveau weg in de database.
     */
    public function schrijfAjax_Niveau()
    {

        $niveau = new stdClass();
        $niveau->id = $this->input->post('id');
        $niveau->naam = $this->input->post('naam');
        $niveau->prijs = $this->input->post('prijs');

        $this->load->model('Niveau_model');

        if ($niveau->id == 0) {
            $this->Niveau_model->insert($niveau);
        } else {
            $this->Niveau_model->update($niveau);
        }
    }

    /** Haalt een niveau uit de database en stuurt deze door naar de view in JSON code.
     */
    public function haalJsonOp_Niveau()
    {

        $id = $this->input->get('id');

        $this->load->model('Niveau_model');
        $object = $this->Niveau_model->get($id);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /** Verwijderd een bepaald niveau uit de database
     */
    public function schrapAjax_Niveau()
    {

        $id = $this->input->get('id');

        $this->load->model('ZwemMoment_model');
        $momenten = $this->ZwemMoment_model->getAllWhereNiveau($id);
        foreach ($momenten as $moment){
            $this->load->model('Wachtlijst_model');
            $this->Wachtlijst_model->deleteWhereMoment($moment->id);
        }
        $this->load->model('ZwemMoment_model');
        $this->ZwemMoment_model->deleteWhereNiveau($id);

        $this->load->model('Zwemmer_model');
        $zwemmers = $this->Zwemmer_model->getAllWhereNiveau($id);

        foreach ($zwemmers as $zwemmer){
            $this->load->model('Wachtlijst_model');
            $this->Wachtlijst_model->deleteWhereZwemmer($zwemmer->id);
        }

        $this->load->model('Zwemmer_model');
        $this->Zwemmer_model->deleteWhereNiveau($id);

        $this->load->model('Niveau_model');
        $this->Niveau_model->delete($id);
    }

    /** Weergeven van de gerecht beheerpagina.
     */
    public function gerecht() {

        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $data['titel'] = 'Instellingen beheren';

        $data['ontwerper'] = 'Louis Van Baelen';
        $data['tester'] = 'Julian Droog';

        $partials = array('hoofding' => 'main_header',
            'inhoud' => 'instelling/gerechtoverzicht');

        $this->template->load('main_master', $partials, $data);

    }

    /** haalt alle gerechten op.
     */
    public function haalAjaxOp_Gerechten()
    {

        $this->load->model('Gerecht_model');
        $data['gerechten'] = $this->Gerecht_model->getGerechten();

        $this->load->view('instelling/ajax_gerechten', $data);
    }

    /**
     * Schrijft een gerecht weg in de database.
     */
    public function schrijfAjax_Gerecht()
    {

        $gerecht = new stdClass();
        $gerecht->id = $this->input->post('id');
        $gerecht->naam = $this->input->post('naam');
        $gerecht->prijs = $this->input->post('prijs');

        $this->load->model('Gerecht_model');

        if ($gerecht->id == 0) {
            $this->Gerecht_model->insert($gerecht);
        } else {
            $this->Gerecht_model->update($gerecht);
        }
    }

    /** Haalt een gerecht uit de database en stuurt deze door naar de view in JSON code.
     */
    public function haalJsonOp_Gerecht()
    {

        $id = $this->input->get('id');

        $this->load->model('Gerecht_model');
        $object = $this->Gerecht_model->getGerecht($id);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /** Verwijderd een bepaald gerecht uit de database
     */
    public function schrapAjax_Gerecht()
    {

        $id = $this->input->get('id');

        $this->load->model('Zwemfeest_model');
        $this->Zwemfeest_model->deleteWhereGerecht($id);

        $this->load->model('Gerecht_model');
        $this->Gerecht_model->delete($id);
    }

    /** Weergeven van de gerecht beheerpagina.
     */
    public function nieuwsbericht() {

        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $data['titel'] = 'Instellingen beheren';

        $data['ontwerper'] = 'Louis Van Baelen';
        $data['tester'] = 'Sebastiaan Bergmans';

        $partials = array('hoofding' => 'main_header',
            'inhoud' => 'instelling/nieuwsberichtoverzicht');

        $this->template->load('main_master', $partials, $data);

    }

    /** haalt alle nieuwsberichten op.
     */
    public function haalAjaxOp_Nieuwsberichten()
    {

        $this->load->model('Nieuws_model');
        $data['nieuwsberichten'] = $this->Nieuws_model->getAllByDate();

        $this->load->view('instelling/ajax_nieuwsberichten', $data);
    }

    /**
     * Schrijft een nieuwsbericcht weg in de database.
     */
    public function schrijfAjax_Nieuwsbericht()
    {

        $nieuwsbericht = new stdClass();
        $nieuwsbericht->id = $this->input->post('id');
        $nieuwsbericht->titel = $this->input->post('titel');
        $nieuwsbericht->inhoud = $this->input->post('inhoud');

        $this->load->model('Nieuws_model');

        if ($nieuwsbericht->id == 0) {
            $nieuwsbericht->datum = date("Y-m-d H:i:s");
            $this->Nieuws_model->insert($nieuwsbericht);
        } else {
            $this->Nieuws_model->update($nieuwsbericht);
        }
    }

    /** Haalt een nieuwsbericht uit de database en stuurt deze door naar de view in JSON code.
     */
    public function haalJsonOp_Nieuwsbericht()
    {

        $id = $this->input->get('id');

        $this->load->model('Nieuws_model');
        $object = $this->Nieuws_model->get($id);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /** Verwijderd een bepaald nieuwtje uit de database
     */
    public function schrapAjax_Nieuwsbericht()
    {

        $id = $this->input->get('id');

        $this->load->model('Nieuws_model');
        $this->Nieuws_model->delete($id);

    }

    /**
     * Weergeven van sluitingsdagen beheerpagina.
     */
    public function sluitingsdagen() {
        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $data['titel'] = 'Instellingen beheren';

        $data['ontwerper'] = 'Jelle Ceulemans';
        $data['tester'] = 'Sebastiaan Berghmans';

        $partials = array('hoofding' => 'main_header',
            'inhoud' => 'instelling/sluitingsdagen');

        $this->template->load('main_master', $partials, $data);
    }

    /**
     * haalt alle sluitingsdagen uit de database
     */
    public function haalSluitingsdagenOp(){
        $data["sluitingsdagen"] = $this->sluiting_model->get();
        $this->load->view('instelling/ajax_sluitingsdagen', $data);;
    }

    /**
     * boegt een nieuwe sluitingsdatum toe
     */
    public function addDatum() {
        $sluiting = new stdClass();
        $sluiting->datum = zetOmNaarYYYYMMDD($this->input->get('datum'));
        $this->sluiting_model->insert($sluiting);
    }

    /**
     * Verwijderd een sluitingsdag uit de database.
     */
    public  function schrapSluitingsdag() {
        $id = $this->input->get('id');
        $this->sluiting_model->delete($id);
    }
}