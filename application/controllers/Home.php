<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @mainpage Commentaar bij project APP/BIT team 12
 *
 * # Wat?
 * Je vindt hier onze doxygen-commentaar bij het <b>APP/BIT project van team12</b>.
 * - De commentaar bij onze model-en controllerklassen vindt je onder het menu <em>Klassen</em>
 * - De commentaar bij onze viewbestanden vindt je onder het menu <em>Bestanden</em>
 *
 * # Wie?
 * Dit project is geschreven en becommentarieerd door Sebastiaan Bergmans, Julian Droog, Jelle Ceulemans, Louis Van Baelen, Jeff Vandenbroeck en Robbe Baeyens.
 */


/**
 * @property Nieuws_model $nieuws_model
 */

/**
 * @Class Home
 * @brief Controller-klasse voor home
 *
 * Controller-klasse met alle methodes die gebruikt worden in home
 */
class Home extends CI_Controller {

    /**
     * Home constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('pagination');
    }

    /** Weergeven van homepagina
     * @param int $startRij
     * @param int $aantal
     */
    public function index($startRij = 0, $aantal = 5)
	  {

        $this->load->model('Nieuws_model');

        $config['base_url'] = site_url('home/index/');
        $config['total_rows'] = $this->Nieuws_model->getCountAll();
        $config['per_page'] = $aantal;

        $this->pagination->initialize($config);

        $data['artikelen'] = $this->Nieuws_model->getNextByDate($aantal, $startRij);

        $data['links'] = $this->pagination->create_links();

        $data['titel'] = 'Welkom';
        $data['ontwerper'] = 'Julian Droog';
        $data['tester'] = 'Sebastiaan Bergmans';
	    $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');

        $partials = array('inhoud' => 'Home/home');
        $this->template->load('main_master', $partials, $data);
	}

}
