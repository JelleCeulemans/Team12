<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property Wachtlijst_model $Wachtlijst_model
 */

/**
 * @Class Wachtlijst
 * @brief Controller-klasse voor wachtlijst
 *
 * Controller-klasse met alle methodes die gebruikt worden in wachtlijst
 */

class Wachtlijst extends CI_Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }

    /**
     * Haalt alle zwemgroepen op een toont deze in een overzicht in de view.
     * Haalt alle zwemgroepen op via wachtlijst_model en toont dit in de view wachtlijst/wachtlijst.
     * Steekt alle groepen met de bijhorende zwemmers en de algemene wachtlijst in variabelen.
     * @see Wachtlijst_model::getGroepenWithZwemmers()
     * @see Wachtlijst_model::getWachtlijst()
     * @see wachtlijst/wachtlijst.php
     */

    public function index(){

        $data['ontwerper'] = 'Jeff Vandenbroeck';
        $data['tester'] = 'Louis Van Baelen';
        $data['titel'] = 'Wachtlijst beheren ';

        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $this->load->model('Wachtlijst_model');
        $data['groepen'] = $this->Wachtlijst_model->getGroepenWithZwemmers();
        $data['wachtlijst']= $this->Wachtlijst_model->getWachtlijst();

        $partials = array('inhoud' => 'wachtlijst/wachtlijst');

        $this->template->load('main_master', $partials, $data);

    }

    /**
     * Haalt de id van de zwemmer op.
     * Vraagt de informatie van de zwemmer op via wachtlijst_model en steekt deze info in een variabele.
     * @see Wachtlijst_model::getZwemmerWithNiveau()
     * @see wachtlijst/ajax_zwemmer.php
     */

    public function haalAjaxOp_Zwemmer(){

        $zwemmerId = $this->input->get('zwemmerId');

        $this->load->model('Wachtlijst_model');

        $data['zwemmer'] = $this->Wachtlijst_model->getZwemmerWithNiveau($zwemmerId);

        $this->load->view("wachtlijst/ajax_zwemmer", $data);
    }

    /**
     * Haalt de id van de zwemmer en van de groep op.
     * Vraagt het aantal zwemmers en het maxaantal in de groep op via wachtlijst_model en steekt deze info in een variabele.
     * Als het aantal zwemmers lager is dan het maxaantal wordt de zwemmer in de groep gestoken.
     * Als dit gebeurd is worden de groepen opnieuw geladen.
     * @see Wachtlijst_model::getGroep()
     * @see Wachtlijst_model::getAantalInGroep()
     * @see Wachtlijst_model::updateZwemmerBeschikbaarheid()
     * @see Wachtlijst_model::getGroepenWithZwemmers()
     * @see wachtlijst/ajax_wachtlijstBeheren.php
     */

    public function haalAjaxOp_VoegZwemmerToe(){

        $zwemmerId = $this->input->get('zwemmerId');
        $groep = $this->input->get('groepId');

        $this->load->model('Wachtlijst_model');

        $maxAantal = $this->Wachtlijst_model->getGroep($groep);
        $zwemmers =  $this->Wachtlijst_model->getAantalInGroep($groep);

        if($zwemmers<$maxAantal){
            $this->Wachtlijst_model->updateZwemmerBeschikbaarheid($zwemmerId,$groep,2);
        }

        $data['groepen'] = $this->Wachtlijst_model->getGroepenWithZwemmers();
        $data['i'] =$groep-1;


        $this->load->view("wachtlijst/ajax_wachtlijstBeheren", $data);
    }

    /**
     * Haalt de id van de zwemmer en van de groep op.
     * Verwijderd de zwemmer uit de groep en plaatst deze terug op de wachtlijst
     * Als dit gebeurd is worden de groepen opnieuw geladen.
     * @see Wachtlijst_model::updateZwemmerBeschikbaarheid()
     * @see Wachtlijst_model::getGroepenWithZwemmers()
     * @see wachtlijst/ajax_wachtlijstBeheren.php
     */

    public function haalAjaxOp_WachtlijstZwemmer(){

        $zwemmerId = $this->input->get('zwemmerId');
        $groep = $this->input->get('groepId');

        $this->load->model('Wachtlijst_model');
        $this->Wachtlijst_model->updateZwemmerBeschikbaarheid($zwemmerId,$groep,1);
        $data['groepen'] = $this->Wachtlijst_model->getGroepenWithZwemmers();
        $data['i'] =$groep-1;

        $this->load->view("wachtlijst/ajax_wachtlijstBeheren", $data);
    }

    /**
     * Haalt de id van de zwemmer en van de groep op.
     * Verwijderd de zwemmer uit de groep verwijderd deze ook uit de wachtlijst.
     * Als dit gebeurd is worden de groepen opnieuw geladen.
     * @see Wachtlijst_model::updateZwemmerBeschikbaarheid()
     * @see Wachtlijst_model::getGroepenWithZwemmers()
     * @see wachtlijst/ajax_wachtlijstBeheren.php
     */

    public function haalAjaxOp_VerwijderZwemmer(){

        $zwemmerId = $this->input->get('zwemmerId');
        $groep = $this->input->get('groepId');

        $this->load->model('Wachtlijst_model');
        $this->Wachtlijst_model->updateZwemmerBeschikbaarheid($zwemmerId,$groep,3);
        $data['groepen'] = $this->Wachtlijst_model->getGroepenWithZwemmers();
        $data['i'] =$groep-1;

        $this->load->view("wachtlijst/ajax_wachtlijstBeheren", $data);
    }

    /**
     * Haalt de algemene wachtlijst op
     * @see Wachtlijst_model::getWachtlijst()
     * @see wachtlijst/ajax_wachtlijst
     */

    public function haalAjaxOp_Wachtlijst(){

        $this->load->model('Wachtlijst_model');
        $data['wachtlijst']= $this->Wachtlijst_model->getWachtlijst();

        $this->load->view("wachtlijst/ajax_wachtlijst", $data);
    }
}