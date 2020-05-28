<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Class Product
 * @brief Controller-klasse voor product
 *
 * Controller-klasse met alle methodes die gebruikt worden in product
 */
class Product extends CI_Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->helper('notation');
    }

    /**
     * Haalt het aantal producten in het winkelwagentje op en toont dit in de view webshop/overzicht
     * Haalt alle producten op via product_model en toont dit in de view webshop/overzicht
     * Steekt de voorraad van elk product in een sessie veriabelen
     * @see Product_model::getProducten()
     * @see webshop/overzicht.php
     */
    public function index(){

        $data['ontwerper'] = 'Jelle Ceulemans';
        $data['tester'] = 'Sebastiaan Bergmans';
        $data['titel'] = 'webshop';

        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');
        $data['aantalProducten'] = $this->haalAantalProductenOp();

        $producten = $this->product_model->getProducten();
        $data['producten'] = $producten;
        $voorraad = array();
        foreach ($producten as $product) {
            $voorraad[$product->id] = $product->aantalInStock;
        }
        $this->session->set_userdata('voorraad', $voorraad);

        $partials = array('inhoud' => 'webshop/overzicht');
        $this->template->load('main_master', $partials, $data);
    }

    /**
     * Haalt de id en het aantal op van het product dat wordt toegevoegd aan de winkelwagen
     * Verhoogt of voegt het aantal toe in de winkelwagen en update de sessie met de nieuwe winkelwagen.
     */
    public function updateWinkelwagen () {

        $id = $this->input->get('id');
        $aantal = $this->input->get('aantal');
        $voorraad = $this->session->userdata('voorraad');
        $karretje = $this->haalOpKarretje();
        if (isset($karretje[$id])) {
            $totaal = $karretje[$id] + $aantal;
            if ($totaal <= $voorraad[$id]) {
                $karretje[$id] += $aantal ;
            }
            else {
                $karretje[$id] = $voorraad[$id];
            }
        }
        else {
            $karretje[$id] = $aantal;
        }
        $this->session->set_userdata('karretje', $karretje);

        echo $this->haalAantalProductenOp();

    }

    /**
     * stuurt een lege array als er geen winkelwagen is, als er wel data in de sessievariabele zit wordt dit winkelwagentje geretourneert.
     * @return array karretje
     */
    private function haalOpKarretje()
    {
        if(!$this->session->has_userdata('karretje')){
            return array();
        } else {
            return $this->session->userdata('karretje');
        }
    }

    /**
     * @return Retourneert het aantal producten in het winkelwagentje.
     */
    public function haalAantalProductenOp () {
        $aantalProducten = 0;
        $karretje = $this->haalOpKarretje();
        foreach ($karretje as $item) {
            $aantalProducten++;
        }
        return $aantalProducten;
    }

    /**
     * Haalt het winkelkarretje op en voegt er een  het aantal aan toe en het totale bedrag en geeft dit nieuwe winkelwagentje door naar de view webshop/winkelwagen
     * @see webshop/winkelwagen
     */
    public function toonWinkelwagen () {
        $karretje = $this->haalOpKarretje();

        $winkelkarretje = array();
        foreach ($karretje as $item => $aantal) {
            $winkelkarretje[$item] = $this->product_model->get($item);
        }

        $totaalPrijs = 0;
        foreach ($winkelkarretje as $item) {
            $aantal = ($karretje[$item->id]);
            $totaal = $item->prijs * $aantal;
            $item->aantal = $aantal;
            $item->totaal = $totaal;
            $totaalPrijs += $totaal;
        }

        $this->session->set_userdata('totalePrijs', $totaalPrijs);
        $this->session->set_userdata('winkelkarretje', $winkelkarretje);

        $data['ontwerper'] = 'Jelle Ceulemans';
        $data['tester'] = 'Sebastiaan Bergmans';

        $data['titel'] = 'Winkelwagen';
        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');
        $data['totaal'] = $this->session->userdata('totalePrijs');
        $data['winkelwagen'] = $this->session->userdata('winkelkarretje');


        $partials = array('inhoud' => 'webshop/winkelwagen');
        $this->template->load('main_master', $partials, $data);
    }

    /**
     * Hier wordt de winkelwagen leeggemaakt en wordt de lege winkelmand afgeprint
     */
    public function winkelmandLeegmaken () {
        $this->session->unset_userdata('karretje');
        echo $this->haalAantalProductenOp();
    }

    /**
     * haalt de doorgestuurde id op en het karretje dat in een sessievariabelen zit.
     * Haalt het element op de plaats met de waarde id er uit en update de nieuwe sessie variabelen
     */
    public function verwijderProduct () {
        $id = $this->input->get('id');
        $karretje = $this->haalOpKarretje();

        unset($karretje[$id]);
        $this->session->set_userdata('karretje', $karretje);
    }

    /**
     * Toont de pagina om  de persoonsgegevens voor de bestelling in te vullen
     * @see Webshop/bestellen
     */
    public function bestellen () {

        $data['ontwerper'] = 'Jelle Ceulemans';
        $data['tester'] = 'Sebastiaan Bergmans';
        $data['titel'] = 'Bestellen';

        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');


        $partials = array('inhoud' => 'webshop/bestellen');
        $this->template->load('main_master', $partials, $data);
    }

    /**
     * Haalt alle persoonsgegevens op en plaatst de bestelling in de database.
     * Hierna wordt een bevestiging getoond en ook een bevestigingsmail verzonden.
     * De gebruiker komt nu terecht op de view Webshop/bevestiging
     * @see Product_model:insertBestelling
     * @see Product_model:insertBestellijn
     * @see Webshop/bevestiging
     */
    public function bevestiging() {
        $voornaam = $this->input->post('voornaam');
        $naam = $this->input->post('naam');
        $telefoon = $this->input->post('telefoon');
        $email = $this->input->post('email');

        $id = $this->product_model->insertBestelling ($voornaam, $naam, $telefoon, $email);

        $karretje = $this->session->userdata('karretje');

        $boodschap = '<p>Uw bestelling is goed ontvangen!</p>
                    <p>Bestelnummer: '.$id.'</p>
                    <p>Uw bestelling:</p>';


        foreach ($karretje as $item => $aantal) {
            $this->product_model->insertBestellijn ($id, $item, $aantal);
            $this->product_model->aanpassenVoorraad ($item, $aantal);
            $boodschap .= '<p><b>'.ucfirst($this->product_model->get($item)->naam).':</b> '.$aantal.' stuks</p>';
        }

        $boodschap .= '<p>Totale prijs: €'.zetOmNaarKomma($this->session->userdata('totalePrijs')).'</p>';


        $data['ontwerper'] = 'Jelle Ceulemans';
        $data['tester'] = 'Sebastiaan Bergmans';

        $this->session->unset_userdata('karretje');

        $this->stuurMail($email, $boodschap);

        $data['titel'] = 'Bestellings bevestiging';

        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');


        $partials = array('inhoud' => 'webshop/bevestiging');
        $this->template->load('main_master', $partials, $data);
    }

    /**
     * Haalt de meegestuurde gegevens op. en past aan de hand hiervan de voorraand aan in de sessievariabelen.
     */
    public function pasWaardeAan() {
        $id = $this->input->get('id');
        $waarde = $this->input->get('waarde');
        $voorraad = $this->session->userdata('voorraad');

        $karretje = $this->session->userdata('karretje');

        if ($waarde <= $voorraad[$id]){
            $karretje[$id] = $waarde;
        }
        else {
            $karretje[$id] = $voorraad[$id];
        }
        $this->session->set_userdata('karretje', $karretje);
    }

    /**
     * Met deze functie wordt er een mail verstuurd
     * @param $to Het email waar naar gestuurd moet worden
     * @param $boodschap De boodschap die in de email verstuurd wordt
     */
    public function stuurMail ($to, $boodschap) {
        $this->load->library('email');
        $this->email->from('kempenrust@jelleceulemans.be', 'Hotel kempenrust');
        $this->email->to($to);
        $this->email->subject('Bevestiging bestelling');
        $this->email->message($boodschap);

        if (!$this->email->send()) {
            show_error($this->email->print_debugger());
        }
    }
}