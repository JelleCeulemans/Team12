<?php
/**
 * @class Product_model
 * @brief Model-klasse voor producten
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel product.
 */
class Product_model extends CI_Model
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    function delete($productId)
    {
        $this->db->where('id', $productId);
        $this->db->delete('product');
    }

    /**
     * @param $id de id van het record dat opgevragen wordt
     * @return het opgevraagde record
     */
    function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('product');
        return $query->row();
    }

    /** Update een bepaalt product in de database.
     * @param $product het productobject dat je wilt updaten in de database.
     */
    function update($product)
    {
        $this->db->where('id', $product->id);
        $this->db->update('product', $product);
    }

    /** Insert een bepaalt product in de database.
     * @param $product het productobject dat je in de database wilt steken.
     * @return int De id van het geinserte product.
     */
    function insert($product)
    {
        $this->db->insert('product', $product);
        return $this->db->insert_id();

    }


    /** Haalt een bepaalt product uit de database.
     * @param $productId De id waarvan je het product wilt opvragen.
     * @return mixed van productobject.
     */
    function getProduct($productId)
    {
        $this->db->where('id', $productId);
        $query = $this->db->get('product');
        return $query->row();
    }

    /** Haalt alle producten uit de database
     * @return array van productobjecten.
     */
    function getProducten(){
        $this->db->order_by('naam', 'asc');
        $query = $this->db->get('product');
        return $query->result();
    }

    /** Haalt een product met een bepaalde naam uit de database.
     * @param $product De naam van het product.
     * @return mixed productobject.
     */
    function getWhereNaam($product)
    {
        $this->db->where('naam', $product);
        $query = $this->db->get('product');
        return $query->row();
    }


    /**
     * Voegt een record toe van een bestelling in de tabel bestelling en retourneert een id ter herkenning van de bestelling
     * @param $voornaam de voornaam van de klant
     * @param $achternaam de achternaam van de klant
     * @param $telefoon de telefoonnummer van de klant
     * @param $email het mailadres van de klant
     * @return de id waar het record is toegevoegd
     */
    function insertBestelling ($voornaam, $achternaam, $telefoon, $email) {
        $bestelling = new stdClass();
        $bestelling->voornaam = $voornaam;
        $bestelling->achternaam = $achternaam;
        $bestelling->telefoon = $telefoon;
        $bestelling->email = $email;
        $bestelling->datum = date("Y-m-d");
        $bestelling->isAfgehandeld = 0;

        $this->db->insert('bestelling', $bestelling);
        return $this->db->insert_id();
    }

    /**
     * Voegt een record toe aan de tabel bestellijn
     * @param $bestellingId de id van de bestelling
     * @param $productId de id van het product dat besteld is
     * @param $aantal de hoeveelheid producten dat besteld worden
     */
    function insertBestellijn ($bestellingId, $productId, $aantal) {
        $bestellijn = new stdClass();
        $bestellijn->bestellingId = $bestellingId;
        $bestellijn->productId = $productId;
        $bestellijn->aantal = $aantal;

        $this->db->insert('bestellijn', $bestellijn);
    }

    /**
     * Verminderd het aantal stuks dat er in stock zijn
     * @param $id de id van het product
     * @param $aantal met hoeveel dat het aantal in stock moet vermiderd worden
     */
    function aanpassenVoorraad ($id, $aantal) {
        $this->db->set('aantalInStock', 'aantalInStock-'.$aantal, FALSE);
        $this->db->where('id', $id);
        $this->db->update('product');
    }
}