<?php
/**
 * @class Zwemtotaal_model
 * @brief Model-klasse voor zwemtotalen
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel zwemtotaal.
 */
class Zwemtotaal_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get($id)
    {
        /**
         * Haalt een zwemtotaal uit de database.
         * @param id De id van het zwemtotaal dat je wilt opvragen.
         */
        $this->db->where('id', $id);
        $query = $this->db->get('zwemtotaal');

        return $query->row();
    }

    function deleteAllWhereKlas($klasId)
    {
        /**
         * Verwijderd alle zwemtotalen uit de database van een bepaalde klas.
         * @param klasid De id van de klas waar je de zwemtotalen van wilt verwijderen.
         */
        $this->db->where('klasId', $klasId);
        $this->db->delete('zwemtotaal');
    }

    function deleteAllWhereFactuur($factuurId)
    {
        $this->db->where('factuurId', $factuurId);
        $this->db->delete('zwemtotaal');
    }

    function getAllByDatumWhereKlas($klasId){
        /**
         * Haalt alle zwemtotalen uit de database van een bepaalde klas.
         * @param klasid De id van de klas waar je de zwemtotalen van wilt ophalen.
         */
        $this->db->where('klasId', $klasId);
        $this->db->order_by('datum', 'asc');
        $query = $this->db->get('zwemtotaal');
        return $query->result();
    }

    function getAllWhereKlasAndNoFactuur($klasId){
        /**
         * Haalt alle zwemtotalen uit de database van een bepaalde klas.
         * @param klasid De id van de klas waar je de zwemtotalen van wilt ophalen.
         */
        $this->db->where('klasId', $klasId);
        $this->db->where('factuurId', NULL);
        $query = $this->db->get('zwemtotaal');
        return $query->result();
    }

    function getAllWhereFactuur($factuurId){
        /**
         * Haalt alle zwemtotalen uit de database van een bepaalde factuur.
         * @param factuurid De id van de factuur waar je de zwemtotalen van wilt ophalen.
         */
        $this->db->where('factuurId', $factuurId);
        $query = $this->db->get('zwemtotaal');
        return $query->result();
    }

    function insert($zwemtotaal){
        /**
         * Een zwemtotaal wordt in de database gestoken.
         * @param zwemtotaal Het zwemtotaalobject dat je in de database wilt steken.
         */

        $this->db->insert('zwemtotaal', $zwemtotaal);
        return $this->db->insert_id();

    }

    function update($zwemtotaal)
    {
        /**
         * Een zwemtotaal wordt in de database geupdate.
         * @param zwemtotaal Het zwemtotaalobject dat je in de database wilt updaten.
         */
        $this->db->where('id', $zwemtotaal->id);
        $this->db->update('zwemtotaal', $zwemtotaal);
    }

    function delete($id)
    {
        /**
         * Een zwemtotaal wordt in de database verwijderd.
         * @param id De id van het zwemtotaal dat je wilt verwijderen.
         */
        $this->db->where('id', $id);
        $this->db->delete('zwemtotaal');
    }
}