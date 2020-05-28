<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property School_model $school_model
 * @property Factuur_model $factuur_model
 */

class Aanwezigheden extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index(){

        $data['titel'] = 'Aanwezigheden opgeven';

        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $this->load->model('School_model');
        $data['scholen'] = $this->School_model->getScholenWithKlassen();

        $partials = array('inhoud' => 'school/scholen');

        $this->template->load('main_master', $partials, $data);

    }

    public function haalAjaxOp_Klas(){

        $klasId = $this->input->get('klasId');

        $this->load->model('School_model');

        $data['aanwezigheden'] = $this->School_model->getAanwezigheden($klasId);
        $data['klas'] = $this->School_model->getKlas($klasId);

        $this->load->view("school/ajax_aanwezighedenKlas", $data);
    }

    public function haalAjaxOp_updateAanwezigheid(){

        $klasId = $this->input->get('klasId');
        $aantalAanwezig = $this->input->get('aantalAanwezig');

        $this->load->model('School_model');

        $this->School_model->updateAanwezigheid($aantalAanwezig,$klasId);

    }

    public function haalAjaxOp_openMondalZwemmoment(){

        $schoolId = $this->input->get('schoolId');

        $this->load->model('School_model');
        $data['klassen'] = $this->School_model->getKlassen($schoolId);

        $this->load->view("school/ajax_momentModal", $data);
    }

    public function haalAjaxOp_voegMomentToe(){

        $schoolId = $this->input->get('schoolId');
        $klasId = $this->input->get('klasId');
        $aanwezig = $this->input->get('aanwezig');
        $datum = $this->input->get('date');

        $this->load->model('School_model');
        $this->load->model('Factuur_model');

        $factuur = $this->Factuur_model->getLeegFactuur();

        $factuur->schoolId=$schoolId;

        $factuurId =$this->Factuur_model->insert($factuur);

        $moment = $this->School_model->getLeegZwemmomentSchool();

        $moment->klasId=$klasId;
        $moment->factuurId=$factuurId;
        $moment->datum=$datum;
        $moment->aantal=$aanwezig;

        $this->School_model->insert($moment);

        $this->load->view("school/ajax_succesModal");

    }

}