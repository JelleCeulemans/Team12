<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Login_model $login_model
 */

/**
 * @Class Gebruiker
 * @brief Controller-klasse voor gebruiker
 *
 * Controller-klasse met alle methodes die gebruikt worden in gebruiker
 */
class Gebruiker extends CI_Controller {

    /**
     * Gebruiker constructor.
     * Controlleerd of gebruiker is aangemeld.
     */
    public function __construct()
    {

        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        if(! $this->session->userdata('rechten')==1){
            redirect(site_url() . "/home/index");
        }
    }

    /**
     * Weergeven van de gebruiker beheerpagina
     */
    public function index()
    {

        $data['ontwerper'] = 'Julian Droog';
        $data['tester'] = 'Jelle Ceulemans';
        $data['titel'] = 'Gebruikers beheren';
        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');
        $partials = array('inhoud' => 'gebruikersBeheren/overzicht');
        $this->template->load('main_master', $partials, $data);
    }

    /**
     * Haalt de gebruikers op
     * @see Login_model::getAllByNaam()
     * @see gebruikersBeheren/overzicht.php
     */
    public function haalAjaxOp_Gebruikers()
    {
        $this->load->model('Login_model');
        $data['gebruikers'] = $this->Login_model->getAllByNaam();

        $this->load->view('gebruikersBeheren/ajax_gebruikers', $data);
    }
    /**
     * Haalt de gebruiker op
     * @see Login_model::get()
     * @see gebruikersBeheren/overzicht.php
     */
    public function haalJsonOp_Gebruiker()
    {
        $id = $this->input->get('gebruikerId');

        $this->load->model('Login_model');
        $object = $this->Login_model->get($id);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    /**
     * insert of updatedata van gebruiker in database
     * @see Login_model::insert()
     * @see Login_model::update()
     * @see gebruikersBeheren/overzicht.php
     */
    public function schrijfAjax_Gebruiker()
    {
        $gebruiker = new stdClass();
        $gebruiker->id = $this->input->post('gebruikerId');
        $gebruiker->voornaam = $this->input->post('gebruikerVoornaam');
        $gebruiker->achternaam = $this->input->post('gebruikerAchternaam');
        $gebruiker->email = $this->input->post('gebruikerEmail');
        $gebruiker->telefoon = $this->input->post('gebruikerTelefoon');
        $gebruiker->username = $this->input->post('gebruikerUsername');
        $gebruiker->isBeheerder = $this->input->post('isBeheerder');

        $this->load->model('Login_model');

        if ($gebruiker->id == 0) {
            //nieuw record
            $gebruiker->wachtwoord = $this->input->post('gebruikerWW1');
            $this->Login_model->insert($gebruiker);
        } else {
            //bestaand record
            $this->Login_model->update($gebruiker);
        }
    }
    /**
     * Delete gebruiker van database
     * @see Login_model::delete()
     * @see gebruikersBeheren/overzicht.php
     */
    public function schrapAjax_Gebruiker()
    {
        $gebruikerId = $this->input->get('gebruikerId');

        //soort zelf nog verwijderen
        $this->load->model('Login_model');
        $this->Login_model->delete($gebruikerId);
    }
    /**
     * Controleert op dubbel
     * @see gebruikersBeheren/overzicht.php
     * @param controle wordt teruggegeven als er een username of email al bestaat
     */
    public function controleGebruiker() {
        $knop = $this->input->get('knop');
        $username = $this->input->get('username');
        $email = $this->input->get('email');

        $this->load->model('Login_model');
        $gebruikers = $this->Login_model->getAllByNaam();;

        $controle = "";
        if($knop === "Toevoegen"){
            foreach ($gebruikers as $gebruiker) {
                if ($gebruiker->username === $username) {
                    $controle .= "Deze username is al bezet!";
                }
                if($gebruiker->email === $email){
                    $controle .= "|Deze email is al bezet!";
                }
            }
        }
        echo $controle;
    }

}
