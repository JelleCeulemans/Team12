<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property School_model $school_model
 * @property Factuur_model $factuur_model
 * @property Klas_model $klas_model
 * @property Zwemtotaal_model $zwemtotaal_model
 */

/**
 * @Class Aanwezigheid
 * @brief Controller-klasse voor aanwezigheid
 *
 * Controller-klasse met alle methodes die gebruikt worden in aanwezigheid
 */
class Aanwezigheid extends CI_Controller
{
    /** Controleerd of de gebruiker is aangemeld.
     * Als hij niet is aangemeld wordt hij doorverwezen naar de homepagina.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('notation');
        if(! $this->session->userdata('rechten')==1){
            redirect(site_url() . "/home/index");
        }
    }

    /** Weergeven van de aanwezigheden beheerpagina.
     * @param id de id van de klas waarvan je de aanwezigheden wilt beheren
     */
    public function index($id) {

        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $data['titel'] = 'Aanwezigheden opgeven';
        $data['klasid'] = $id;

        $data['ontwerper'] = 'Jeff Vandenbroeck';
        $data['tester'] = 'Julian Droog';

        $partials = array('hoofding' => 'main_header',
            'inhoud' => 'aanwezigheid/aanwezigheidoverzicht');

        $this->template->load('main_master', $partials, $data);

    }

    /** haalt alle aanwezigheden van een bepaalde klas op.
     * @param id de id van de klas waarvan je de aanwezigheden wilt weergeven.
     */
    public function haalAjaxOp_Aanwezigheden($klasId)
    {

        $this->load->model('zwemtotaal_model');
        $data['zwemtotalen'] = $this->zwemtotaal_model->getAllByDatumWhereKlas($klasId);

        $this->load->view('aanwezigheid/ajax_aanwezigheden', $data);
    }

    /**
     * Schrijft een aanwezigheid weg in de database.
     */
    public function schrijfAjax_Aanwezigheid()
    {

        $zwemtotaal = new stdClass();
        $zwemtotaal->id = $this->input->post('id');
        $zwemtotaal->klasId = $this->input->post('klasId');
        $zwemtotaal->datum = $this->input->post('datum');
        $zwemtotaal->aantal = $this->input->post('aantal');

        $this->load->model('zwemtotaal_model');

        if ($zwemtotaal->id == 0) {
            $this->zwemtotaal_model->insert($zwemtotaal);
        } else {
            $this->zwemtotaal_model->update($zwemtotaal);
        }
    }

    /** Haalt een aanwezigheid uit de database en stuurt deze door naar de view in JSON code.
     */
    public function haalJsonOp_Aanwezigheid()
    {

        $id = $this->input->get('id');

        $this->load->model('zwemtotaal_model');
        $object = $this->zwemtotaal_model->get($id);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /** Verwijderd een bepaalde aanwezigheid uit de database
     */
    public function schrapAjax_Aanwezigheid()
    {

        $id = $this->input->get('id');

        $this->load->model('zwemtotaal_model');
        $this->zwemtotaal_model->delete($id);
    }
}