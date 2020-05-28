<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Zwemles_model $Zwemles_model
 * @property Wachtlijst_model $Wachtlijst_model
 */
/**
 * @Class Zwemmer
 * @brief Controller-klasse voor zwemmer
 *
 * Controller-klasse met alle methodes die gebruikt worden in zwemmer
 */
class Zwemmer extends CI_Controller {

    /**
     * Zwemmer constructor.
     * controlleerd of gebruiker is aangemeld.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('notation');
        $this->load->helper('form');
        $this->load->model('Zwemles_model');
        $this->load->model('Wachtlijst_model');
        if(! $this->session->userdata('rechten')==1){
            redirect(site_url() . "/home/index");
        }
    }

    /**
     * Geeft een overzicht van alle zwemmers weer
     * Men kan zwemmersdata aanpassen en zwemmers verwijderen
     * @see Zwemles_model::getAllNiveaus()
     * @see zwemmersBeheren/overzicht.php
     */
    public function index()
    {
        $data['ontwerper'] = 'Robbe Baeyens';
        $data['tester'] = 'Sebastiaan Bergmans';
        $data['titel'] = 'Zwemmers beheren';
        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');

        $data['niveaus'] = $this->Zwemles_model->getAllNiveaus();

        $partials = array('inhoud' => 'zwemmersBeheren/overzicht');
        $this->template->load('main_master', $partials, $data);
    }

    /**
     * Haalt de zwemmers op uit database en toont deze in pagina overzicht
     * @see Zwemles_model::getZwemmers()
     * @see Zwemles_model::getNiveauById()
     * @see zwemmersBeheren/overzicht.php
     * @see zwemmersBeheren/ajax_zwemmers.php
     */
    public function haalAjaxOp_Zwemmers()
    {
        $zwemmers = $this->Zwemles_model->getZwemmers();
        foreach ($zwemmers as $zwemmer)
        {
            $niveau = $this->Zwemles_model->getNiveauById($zwemmer->niveauId);
            $zwemmer->niveauNaam = $niveau->naam;
        }

        $data['zwemmers'] = $zwemmers;

        $this->load->view('zwemmersBeheren/ajax_zwemmers', $data);
    }

    /**
     * Controlleert op dubbel
     * @see zwemmersBeheren/overzicht.php
     */
    public function controleerJson_DubbelZwemmer()
    {
        $isDubbel = false;

        $this->output->set_content_type("application/json");
        echo json_encode($isDubbel);
    }

    /**
     * Haalt de data op van een zwemmer
     * @see Zwemles_model::getZwemmerById()
     * @see zwemmersBeheren/overzicht.php
     */
    public function haalJsonOp_Zwemmer()
    {
        $id = $this->input->get('zwemmerId');

        $this->load->model('Zwemles_model');
        $object = $this->Zwemles_model->getZwemmerById($id);
        $object->inschrijfdatum = zetOmNaarDateTimeLocalValue($object->inschrijfdatum);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /**
     * Update data van aangepaste zwemmers in database
     * @see Zwemles_model::update()
     * @see zwemmersBeheren/overzicht.php
     */
    public function schrijfAjax_Zwemmer()
    {
        $zwemmer = new stdClass();
        $zwemmer->id = $this->input->post('zwemmerId');
        $zwemmer->voornaam = $this->input->post('zwemmerVoornaam');
        $zwemmer->achternaam = $this->input->post('zwemmerAchternaam');
        $zwemmer->email = $this->input->post('zwemmerEmail');
        $zwemmer->telefoon = $this->input->post('zwemmerTelefoon');
        $zwemmer->niveauId = $this->input->post('zwemmerNiveau');
        $zwemmer->geboortedatum = $this->input->post('zwemmerGeboorte');

        $this->Zwemles_model->update($zwemmer);
    }

    /**
     * Delete zwemmers van database
     * Eerst moet hiervoor hun status verwijderd worden
     * @see Wachtlijst_model::deleteWhereZwemmerId()
     * @see Zwemles_model::delete()
     * @see zwemmersBeheren/overzicht.php
     */
    public function schrapAjax_Zwemmer()
    {
        $zwemmerId = $this->input->get('zwemmerId');
        $this->Wachtlijst_model->deleteWhereZwemmerId($zwemmerId);
        $this->Zwemles_model->delete($zwemmerId);
    }
}