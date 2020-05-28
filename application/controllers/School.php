<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*! De school controller
* \brief bevat de functies om de schoollijst weer te geven
 */

/**
 * @property School_model $school_model
 * @property Factuur_model $factuur_model
 * @property Klas_model $klas_model
 * @property Zwemtotaal_model $zwemtotaal_model
 * @property Login_model $Login_model
 * @property Videodemo_model $Videodemo_model
 */

/**
 * @Class School
 * @brief Controller-klasse voor school
 *
 * Controller-klasse met alle methodes die gebruikt worden in school
 */
class School extends CI_Controller
{
    /**
     * School constructor.
     * controlleerd of de gebruiker is aangemeld
     */
    public function __construct()
    {

        parent::__construct();
        if(! $this->session->userdata('rechten')==1){
            redirect(site_url() . "/home/index");
        }
    }

    /** Weergeven van de school beheerpagina.
     */
    public function index() {

        $gebruikersnaam = $this->session->userdata('gebruikersnaam');
        $data['gebruikersnaam'] = $gebruikersnaam;

        $loginid = $this->session->userdata('userId');

        $this->load->model('Videodemo_model');
        $videodemo = $this->Videodemo_model->getWhereGebruiker($loginid);

        if(!isset($videodemo)){

            $videodemo = new stdClass();
            $videodemo->bekeken = 1;
            $videodemo->loginId = $loginid;
        }

        $data['videodemo'] = $videodemo;

        $data['rechten']=$this->session->userdata('rechten');

        $data['titel'] = 'Scholen beheren';



        $data['ontwerper'] = 'Sebastiaan Bergmans';
        $data['tester'] = 'Robbe Baeyens';

        $partials = array('hoofding' => 'main_header',
            'inhoud' => 'school/schoolOverzicht');

        $this->template->load('main_master', $partials, $data);

    }

    /** Haalt alle scholen op met hun klassen.
     * Een view met de schoollijst wordt geladen.
     */
    public function haalAjaxOp_Scholen()
    {

        $this->load->model('school_model');
        $data['scholen'] = $this->school_model->getAllWithKlassen();

        $this->load->view('school/ajax_schoolLijst', $data);
    }

    /** Haalt alle klassen op.
     * Een view met de klaslijst wordt geladen.
     * @param $schoolId
     */
    public function haalAjaxOp_Klassen($schoolId)
    {

        $this->load->model('klas_model');
        $data['klassen'] = $this->klas_model->getAllByNaamWhereSchool($schoolId);

        $this->load->view('school/ajax_klasLijst', $data);
    }

    /** Laadt een modal venster met de schoolinformatie.
     */
    public function haalAjaxOp_schoolVenster()
    {

        $this->load->view('school/ajax_schoolVenster');
    }

    /** Controleerd of de waarde van het inputveld schoolnaam al bestaat.
     * De returnwaarde wordt teruggestuurd in jsoncode.
     * @return boolean.
     */
    public function controleerJson_DubbelSchool()
    {

        $schoolNaam = $this->input->post('schoolNaam');
        $schoolId = $this->input->post('schoolId');

        $this->load->model('school_model');

        $school = $this->school_model->getWhereNaam($schoolNaam);

        $isDubbel = false;

        if (count($school) > 0) {
            if ($school->id === $schoolId) {
                $isDubbel = false;
            } else {
                $isDubbel = true;
            }
        }
        $this->output->set_content_type("application/json");
        echo json_encode($isDubbel);
    }

    /** Controleerd of de waarde van het inputveld klasnaam al bestaat.
     * Wordt enkel gecontroleerd in dezelfde school.
     * De returnwaarde wordt teruggestuurd in jsoncode.
     */
    public function controleerJson_DubbelKlas()
    {

        $leerjaar = $this->input->post('leerjaar');
        $klasId = $this->input->post('klasId');
        $schoolId = $this->input->post('schoolId');

        $this->load->model('klas_model');

        $klassen = new stdClass();
        $klassen = $this->klas_model->getWhereLeerjaar($leerjaar);

        $errorcount = 0;
        $isDubbel = false;

        if (count($klassen) > 0) {

            foreach ($klassen as $klas){
                if (($klas->schoolId != $schoolId)) {
                } else {
                    if (($klas->id === $klasId)) {
                    } else {
                        $errorcount++;
                    }
                }
            }
        }
        if($errorcount > 0){
            $isDubbel = true;
        }

        $this->output->set_content_type("application/json");
        echo json_encode($isDubbel);
    }

    /** De ingevulde waardes in het formulier voor scholen aan te passen/toe te voegen worden weggeschreven.
     */
    public function schrijfAjax_School()
    {

        $object = new stdClass();
        $object->id = $this->input->post('schoolId');
        $object->naam = $this->input->post('schoolNaam');
        $object->straat = $this->input->post('schoolStraat');
        $object->huisnummer = $this->input->post('schoolHuisnummer');
        $object->postcode = $this->input->post('schoolPostcode');
        $object->telefoon = $this->input->post('schoolTelefoon');
        $object->email = $this->input->post('schoolEmail');

        $this->load->model('school_model');

        if ($object->id == 0) {
            $this->school_model->insert($object);
        } else {
            $this->school_model->update($object);
        }
    }

    /** De ingevulde waardes in het formulier voor klassen aan te passen/toe te voegen worden weggeschreven.
     */
    public function schrijfAjax_Klas()
    {

        $object = new stdClass();
        $object->id = $this->input->post('klasId');
        $object->schoolId = $this->input->post('schoolId');
        $object->leerjaar = $this->input->post('leerjaar');
        $gesubsidieerd = $this->input->post('isGesubsidieerd');

        if($gesubsidieerd > 0){
            $object->isGesubsidieerd = 1;
        }
        else{
            $object->isGesubsidieerd = 0;
        }


        $this->load->model('klas_model');

        if ($object->id == 0) {
            $this->klas_model->insert($object);
        } else {
            $this->klas_model->update($object);
        }

        echo $object->schoolId;
    }

    /** De school en alle onderliggende klassen, momenten en facturen worden verwijderd.
     */
    public function verwijderAjax_School()

    {
        $schoolId = $this->input->get('schoolId');

        $this->load->model('klas_model');
        $klassen = $this->klas_model->getAllByNaamWhereSchool($schoolId);

        foreach($klassen as $klas){
            $this->load->model('zwemtotaal_model');
            $this->zwemtotaal_model->deleteAllWhereKlas($klas->id);
        }

        $this->load->model('klas_model');
        $this->klas_model->deleteAllWhereSchool($schoolId);

        $this->load->model('factuur_model');
        $this->factuur_model->deleteAllWhereSchool($schoolId);

        $this->load->model('school_model');
        $this->school_model->delete($schoolId);
    }

    /** De school en alle onderliggende klassen en momenten worden verwijderd.
     */
    public function verwijderAjax_Klas()

    {
        $klasId = $this->input->get('klasId');

        $this->load->model('zwemtotaal_model');
        $this->zwemtotaal_model->deleteAllWhereKlas($klasId);

        $this->load->model('klas_model');
        $klas = $this->klas_model->get($klasId);
        $this->klas_model->delete($klasId);

        echo $klas->schoolId;
    }

    /**
     * de demovideo wortd niet mmeer getoont voor bepaalde gebruiker
     */
    public function ajax_NooitMeerTonen(){
        $loginId = $this->input->get('loginId');

        $this->load->model('Videodemo_model');
        $this->Videodemo_model->nooitTonen($loginId);
    }

    /** Haalt een bepaalde school op en returnt deze als jsoncode.
     * Wordt gebruikt om schoolinfo in het formulier in te vullen.
     */
    public function haalJsonOp_School()
    {

        $id = $this->input->get('schoolId');

        $this->load->model('school_model');
        $object = $this->school_model->get($id);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /** Haalt een bepaalde klas op en returnt deze als jsoncode.
     * Wordt gebruikt om klasinfo in het formulier in te vullen.
     */
    public function haalJsonOp_Klas()
    {

        $id = $this->input->get('klasId');

        $this->load->model('klas_model');
        $object = $this->klas_model->get($id);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /** Haalt een bepaalde school op met klassen en returnt deze als jsoncode.
     * Wordt gebruikt bij de waarschuwing om scholen te verwijderen.
     */
    public function haalJsonOp_SchoolMetKlassen()
    {

        $schoolId = $this->input->get('schoolId');
        $this->load->model('school_model');
        $school = $this->school_model->get($schoolId);

        $this->load->model('klas_model');
        $school->klassen = $this->klas_model->getAllByNaamWhereSchool($schoolId);

        $this->output->set_content_type("application/json");
        echo json_encode($school);
    }

    /** Haalt een bepaalde klas op met momenten en returnt deze als jsoncode.
     * Wordt gebruikt bij de waarschuwing om klassen te verwijderen.
     */
    public function haalJsonOp_KlasMetMomenten()
    {

        $klasId = $this->input->get('klasId');
        $this->load->model('klas_model');
        $klas = $this->klas_model->get($klasId);

        $this->load->model('zwemtotaal_model');
        $klas->momenten = $this->zwemtotaal_model->getAllByDatumWhereKlas($klasId);

        $this->output->set_content_type("application/json");
        echo json_encode($klas);
    }

}