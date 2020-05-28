<?php
/**
 * @class Bestellijn_model
 * @brief Model-klasse voor bestellijnen
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel bestellijn.
 */
/**
 * @class Bestellijn_model
 * @brief Model-klasse voor bestellijnen
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel bestellijn.
 */
class Bestellijn_model extends CI_Model
{
    /**
     * Bestellijn_model constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /** haalt alle een bestellijn uit de database met bijhoorend product.
     * @param $bestellingId de id van de bestellijn die je wilt ophalen
     * @return array
     */

    function getBestellijnWithProduct($bestellingId){
        $this->db->where('bestellingId', $bestellingId);
        $query = $this->db->get('bestellijn');
        $bestellingdetails = $query->result();

        foreach($bestellingdetails as $item){
            $this->db->where('id', $item->productId);
            $query = $this->db->get('product');
            $item->product = $query->row();
        }
        return $bestellingdetails;
    }
}