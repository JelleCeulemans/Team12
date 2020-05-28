<?php
/** De help controller
 * \brief bevat de functies om de gebruiker te ondersteunen
 */
class Help extends CI_Controller
{
    public function __construct()
    {
        /** Controleerd of de gebruiker is aangemeld.
         * Als hij niet is aangemeld wordt hij doorverwezen naar de homepagina.
         */

        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('notation');
        if(! $this->session->userdata('rechten')==1){
            redirect(site_url() . "/home/index");
        }
    }

    public function wachtlijst() {
        /** Weergeven van de wachtlijst beheren helpagina.
         */
        $data['gebruikersnaam']=$this->session->userdata('gebruikersnaam');
        $data['rechten']=$this->session->userdata('rechten');

        $data['titel'] = 'Helppagina wachtlijst beheren';

        $data['ontwerper'] = 'Jeff Vandenbroeck';
        $data['tester'] = 'Julian Droog';

        $partials = array('hoofding' => 'main_header',
            'inhoud' => 'help/wachtlijstbeheren');

        $this->template->load('main_master', $partials, $data);

    }
}