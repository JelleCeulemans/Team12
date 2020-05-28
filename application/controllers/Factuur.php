<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property School_model $school_model
 * @property Factuur_model $factuur_model
 * @property Klas_model $klas_model
 * @property Zwemtotaal_model $zwemtotaal_model
 */

/**
 * @Class Factuur
 * @brief Controller-klasse voor factuur
 *
 * Controller-klasse met alle methodes die gebruikt worden in factuur
 */
class Factuur extends CI_Controller
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

    /** Weergeven van de factuur beheerpagina.
     * @param $id de id van de school waarvan je de facturen wilt beheren
     */
    public function index($id) {

        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $data['titel'] = 'Facturen genereren';
        $data['schoolid'] = $id;

        $data['ontwerper'] = 'Sebastiaan Bergmans';
        $data['tester'] = 'Robbe Baeyens';

        $partials = array('hoofding' => 'main_header',
            'inhoud' => 'factuur/factuuroverzicht');

        $this->template->load('main_master', $partials, $data);

    }

    /** haalt alle facturen van een bepaalde school op.
     * @param $schoolId de id van de school waarvan je de factuuren wilt weergeven.
     */
    public function haalAjaxOp_Facturen($schoolId)
    {


        $this->load->model('factuur_model');
        $facturen = $this->factuur_model->getAllWhereSchool($schoolId);

        foreach($facturen as $factuur){
            $this->load->model('zwemtotaal_model');
            $factuur->momenten = $this->zwemtotaal_model->getAllWhereFactuur($factuur->id);
        }

        $data['facturen'] = $facturen;

        $this->load->view('factuur/ajax_facturen', $data);
    }

    /** Controlleerd of er nog momenten zijn die niet gefactureerd zijn.
     * @param $schoolid de id van de school waarvoor je de momenten wilt controlleren
     */
    public function controlleerMomenten($schoolid){


        $this->load->model('klas_model');
        $klassen = $this->klas_model->getAllByNaamWhereSchool($schoolid);
        $aantalMomenten = 0;

        foreach($klassen as $klas){
            $this->load->model('zwemtotaal_model');
            $momenten = $this->zwemtotaal_model->getAllWhereKlasAndNoFactuur($klas->id);
            $aantalMomenten += count($momenten);
        }

        echo $aantalMomenten;
    }

    /** haalt alle facturen van een bepaalde school op.
     * @param $schoolId de id van de school waarvan je de factuuren wilt weergeven.
     */
    public function haalAjaxOp_Momenten($schoolId)
    {


        $this->load->model('klas_model');
        $klassen = $this->klas_model->getAllByNaamWhereSchool($schoolId);
        $momenten = array();

            foreach($klassen as $klas){
                $this->load->model('zwemtotaal_model');
                $objecten = $this->zwemtotaal_model->getAllWhereKlasAndNoFactuur($klas->id);
                foreach ($objecten as $object){
                    $momenten[] = $object->datum;
                }
            }

        $data['momenten'] = $momenten;

        $this->load->view('factuur/ajax_momenten', $data);
    }

    /**genereert een nieuwe factuur met alle niet-gefactureerde momenten.
     */
    public function schrijfAjax_Factuur()
    {
        $factuur = new stdClass();

        $schoolId = $this->input->post('id');

        $factuur->schoolId = $schoolId;
        $factuur->prijs = $this->input->post('prijs');

        $this->load->model('factuur_model');
        $factuurId = $this->factuur_model->insert($factuur);

        $this->load->model('klas_model');
        $klassen = $this->klas_model->getAllByNaamWhereSchool($schoolId);

        foreach($klassen as $klas){
            $this->load->model('zwemtotaal_model');
            $objecten = $this->zwemtotaal_model->getAllWhereKlasAndNoFactuur($klas->id);
            foreach ($objecten as $object){
                $object->factuurId = $factuurId;
                $this->load->model('zwemtotaal_model');
                $this->zwemtotaal_model->update($object);
            }
        }

    }

    /** Delete een bepaalde factuur uit de database.
     * @param $factuurId
     */
    public function schrapAjax_Factuur($factuurId)
    {
        $this->load->model('zwemtotaal_model');
        $this->zwemtotaal_model->deleteAllWhereFactuur($factuurId);

        $this->load->model('factuur_model');
        $this->factuur_model->delete($factuurId);


    }
}