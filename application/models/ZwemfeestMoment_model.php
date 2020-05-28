<?php
/**
 * Created by PhpStorm.
 * User: Julian
 * Date: 21/02/2019
 * Time: 14:39
 */

/**
 * @class ZwemfeestMoment_model
 * @brief Model-klasse voor zwemfeestmomenten
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel zwemfeestMoment.
 */
class ZwemfeestMoment_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Haalt zwemfeestmomenten uit de database op basis van de Id op ascending volgerde.
     */
    function getAllById(){

        $this->db->order_by('id', 'asc');
        $query = $this->db->get('zwemfeestMoment');
        return $query->result();
    }

    /**
     * Een zwemfeestmoment wordt in de database gestoken.
     * @param $zwemfeestmoment Het zwemfeestmomentobject dat je in de database wilt steken.
     */
    function insert($zwemfeestMoment) {

       $this->db->insert('zwemfeestMoment', $zwemfeestMoment);
       return $this->db->insert_id();
    }

    /**
     * Een zwemfeestmoment wordt in de database geupdate.
     * @param $zwemfeestmoment Het zwemfeestmomentobject dat je in de database wilt updaten.
     */
    function update($zwemfeestMoment)
    {

        $this->db->where('id', $zwemfeestMoment->id);
        $this->db->update('zwemfeestMoment', $zwemfeestMoment);
    }

    /**
     * Een zwemfeestmoment wordt in de database verwijderd.
     * @param $id de id van het zwemfeestmoment dat je in de database wilt verwijderen.
     */
    function delete($id)
    {

        $this->db->where('id', $id);
        $this->db->delete('zwemfeestMoment');
    }

    /**
     * Haalt een zwemfeestmomenten uit de database.
     */
    function getAllZwemfeesten() {

        $query = $this->db->get('zwemfeestMoment');
        return $query->result();
    }

    /** haalt alle dagen uit de database
     * @return mixed
     */
    function getAlleDagen () {
        //$this->db->select('weekdag');
        $query = $this->db->get('zwemfeestMoment');
        return $query->result();
    }

    /** haalt alle sluitingsdagen uit de database
     * @return mixed
     */
    function getSluitingsdagen () {
        $query = $this->db->get('sluiting');
        return $query->result();
    }

}
