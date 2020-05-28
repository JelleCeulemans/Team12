<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Class Login
 * @brief Controller-klasse voor Login
 * @property Login_model $login_model
 * @property Authex $authex
 *
 * Controller-klasse met alle methodes die gebruikt worden in login
 */

/**
 * @Class Login
 * @brief Controller-klasse voor login
 *
 * Controller-klasse met alle methodes die gebruikt worden in login
 */
class Login extends CI_Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authex');
    }

    public function loginAanmaken () {
        $this->authex->registreer ();
        redirect('home/index');

    }

    /**
     * Hier wordt de gebruikersnaam en en wachtwoord opgehaalt, hierna worden ze via de authex controller gecontrolleerd of deze correct zijn.
     * Als het correct is wordt er niets geprint (null via ajax) anders wordt er een error melding geprint voor de ajax call.
     * @see authex::aanmelden()
     */
    public function aanmelden () {
        $username = $this->input->post('gebruikersnaam');
        $wachtwoord = $this->input->post('wachtwoord');
        $login = $this->authex->aanmelden($username, $wachtwoord);

        if(!$login) {
            echo 'Het gebruikersnaam en/of wachtwoord is verkeerd';
        }
    }

    /**
     * Hier wordt via de authex controller de ingelogde persoon uitgelogd
     * @see authex::afmelden()
     */
    public function afmelden() {
        $this->authex->afmelden();
        redirect('/home/index');
    }

    /**
     * Hier wordt de wachtwoord herstel pagina geladen
     */
    public function wachtwoordHerstellenPage(){

        $this->load->view("wachtwoord/wachtwoordHerstellen");
    }

    /**
     * hier wordt een email verstuurd om het wachtwoord te herstellen
     */
    public function wachtwoordHerstellenMail(){

        $email = $this->input->get('email');

        $this->load->model('Login_model');
        $boodschap = $this->Login_model->veranderWachtwoordHerstellen($email);

        $this->load->library("email");
        $this->email->from('admin@kempenrust.be','Kempenrust Zwembad');
        $this->email->to($email);
        $this->email->subject("Wachtwoord herstellen");
        $this->email->message($boodschap);

        $this->load->view("wachtwoord/wachtwoordHersteld");

    }

    /**
     * opent de login modal
     */
    public function openModalLogin(){
        $this->load->view("wachtwoord/ajax_loginModal");
    }
}