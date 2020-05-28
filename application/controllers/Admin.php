<?php
/**
 * Created by PhpStorm.
 * User: Louis
 * Date: 21/02/2019
 * Time: 13:55
 */

class Admin extends CI_Controller
{
    public function __construct() {
        parent::__construct();

        $this->load->helper("url");
        $this->load->helper("html");
    }

    public function index() {

        $data['ontwerper'] = 'Louis Van Baelen';
        $data['tester'] = 'Julian Droog';
        $data['title'] = 'Admin';


        $this->load->model('product_model');
        $this->load->model('zwemles_model');
        $this->load->model('gerecht_model');
        $this->load->model('zwemfeest_model');

        $data['gebruikersnaam'] = $this->session->userdata('gebruikersnaam');
        $data['rechten'] = $this->session->userdata('rechten');
        $data['titel'] = 'Producten / Instellingen beheren';


        $data['producten']=$this->product_model->getProducten();
        $data['niveaus']=$this->zwemles_model->getAllNiveaus();
        $data['gerechten']=$this->gerecht_model->getGerechten();

        $partials = array('inhoud' => 'instellingenBeheren/instellingen');
        $this->template->load('main_master', $partials, $data);


        //NOG AANPASSEN
        }

    public function controleerJson_DubbelProduct()
    {
        $productNaam = $this->input->post('productNaam');
        $productId = $this->input->post('productId');

        $this->load->model('product_model');

        $product = new stdClass();
        $product = $this->product_model->getWhereNaam($productNaam);

        $isDubbel = false;

        if (count($product) > 0) {
            // product aanpassen en op wijzigen drukken zonder naam aan te passen => $isDubbel = false
            if (($product->id === $productId)) {
                $isDubbel = false;
            } else {
                $isDubbel = true;
            }
        }
        $this->output->set_content_type("application/json");
        echo json_encode($isDubbel);
    }

    public function controleerJson_DubbelNiveau()
    {
        $niveauNaam = $this->input->post('niveauNaam');
        $niveauId = $this->input->post('niveauId');

        $this->load->model('niveau_model');

        $niveau = new stdClass();
        $niveau = $this->niveau_model->getWhereNaam($niveauNaam);

        $isDubbel = false;

        if (count($niveau) > 0) {
            // product aanpassen en op wijzigen drukken zonder naam aan te passen => $isDubbel = false
            if (($niveau->id === $niveauId)) {
                $isDubbel = false;
            } else {
                $isDubbel = true;
            }
        }
        $this->output->set_content_type("application/json");
        echo json_encode($isDubbel);
    }

    public function controleerJson_DubbelGerecht()
    {
        $gerechtNaam = $this->input->post('gerechtNaam');
        $gerechtId = $this->input->post('gerechtId');

        $this->load->model('gerecht_model');

        $gerecht = new stdClass();
        $gerecht = $this->gerecht_model->getWhereNaam($gerechtNaam);

        $isDubbel = false;

        if (count($gerecht) > 0) {
            // product aanpassen en op wijzigen drukken zonder naam aan te passen => $isDubbel = false
            if (($gerecht->id === $gerechtId)) {
                $isDubbel = false;
            } else {
                $isDubbel = true;
            }
        }
        $this->output->set_content_type("application/json");
        echo json_encode($isDubbel);
    }


    //Controleren van type item voor verwijdering plaatsvindt
    public function controleerType() {

        $typeItem = $this->input->post('typeItem');

        if ($typeItem = "niveau") {
            $this->schrapAjax_niveau();
        } else if ($typeItem = "gerecht") {
            $this->schrapAjax_gerecht();
        } else if ($typeItem = "product") {
            $this->schrapAjax_product();
        }

        echo json_encode($typeItem);
    }

//OPHALEN meerdere

    public function haalAjaxOp_Producten()
    {
        $this->load->model('product_model');
        $data['producten'] = $this->product_model->getProducten();

        $this->load->view('instellingenBeheren/ajax_producten', $data);
    }

    public function haalAjaxOp_Niveaus()
    {
        $this->load->model('zwemles_model');
        $data['niveaus'] = $this->zwemles_model->getNiveaus();
        $this->load->view('instellingenBeheren/ajax_niveaus', $data);
    }

    public function haalAjaxOp_Gerechten()
    {

        $this->load->model('gerecht_model');
        $data['gerechten'] = $this->gerecht_model->getGerechten();

        $this->load->view('instellingenBeheren/ajax_gerechten', $data);
    }

    //OPHALEN VOOR VERWIJDEREN OF WIJZIGEN
    public function haalJsonOp_Product()
    {
        $productId = $this->input->get('productId');

        $this->load->model('product_model');
        $object = $this->product_model->get($productId);

        $this->output->set_content_type("application/json");
        echo json_encode($object);
    }

    public function haalJsonOp_Gerecht()
    {
        $gerechtId = $this->input->get('gerechtId');
        $this->load->model('product_model');
        $gerecht = $this->product_model->getGerecht($gerechtId);


        $this->output->set_content_type("application/json");
        echo json_encode($gerecht);
    }

    public function haalJsonOp_Niveau()
    {
        $niveauId = $this->input->get('niveauId');
        $this->load->model('product_model');
        $niveau = $this->product_model->getNiveauById($niveauId);


        $this->output->set_content_type("application/json");
        echo json_encode($niveau);
    }

    //SCHRAPPEN

    public function schrapAjax_product()
    {
        $id = $this->input->get('itemId');
        $this->load->model('product_model');
        $this->product_model->delete($id);
    }

    public function schrapAjax_niveau()
    {
        $id = $this->input->get('itemId');

        $this->load->model('zwemles_model');
        $this->zwemles_model->schrapAjax_Zwemles($id);
    }

    public function schrapAjax_gerecht()
    {
        $id = $this->input->get('itemId');

        $type = "gerecht";


        //product zelf nog verwijderen
        $this->load->model('product_model');
        $this->gerecht_model->delete($id);
    }


    //SCHRIJVEN

    public function schrijfAjax_product()
    {
        $object = new stdClass();
        $object->id = $this->input->post('productId');
        $object->naam = $this->input->post('productNaam');

        $this->load->model('product_model');

        if ($object->id == 0) {
            //nieuw record
            $this->product_model->insert($object);
        } else {
            //bestaand record
            $this->product_model->update($object);
        }
    }


}