<?php
/**
 * Created by PhpStorm.
 * User: Louis
 * Date: 12/03/2019
 * Time: 19:56
 */

/**
 * @Class Nieuwsbericht
 * @brief Controller-klasse voor nieuwsbericht
 *
 * Controller-klasse met alle methodes die gebruikt worden in nieuwsbericht
 */
class Nieuwsberichten extends CI_Controller
{
    /**
     * Nieuwsberichten constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
    }


    /**
     * Laadt de beheerpagina van nieuwsberichten.
     */
    public function index() {
        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        if($this->session->userdata('rechten')==1){
            $data['titel'] = 'Overzicht boekingen';

            $data['ontwerper'] = 'Louis Van Baelen';
            $data['tester'] = 'Jeff Vandenbroeck';

            $partials = array('hoofding' => 'main_header',
                'inhoud' => 'nieuwsberichten/overzicht');

            $this->template->load('main_master', $partials, $data);
        }
        else{
            redirect(site_url() . "/home/index");
        }

    }


    /**
     * Haalt alle nieuwsberichten uit de database
     */
    public function haalAjaxOp_Nieuwsberichten()
    {
        $this->load->model('nieuws_model');
        $data['nieuwsberichten'] = $this->nieuws_model->getAllByDate();

        $this->load->view('nieuwsberichten/ajax_nieuwsberichten', $data);
    }

    /**
     * haalt een nieuwsbericht uit de database.
     */
    public function haalJsonOp_Nieuwsbericht() {
        $id = $this->input->get('nieuwsberichtId');
        $this->load->model('nieuws_model');
        $object = $this->nieuws_model->get($id);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /**
     * verwijderd een nieuwsbericht uit de database.
     */
    public function schrapAjax_Webinhoud()
    {
        $id = $this->input->get('id');
        $this->load->model('nieuws_model');
        $this->nieuws_model->delete($id);
    }
}