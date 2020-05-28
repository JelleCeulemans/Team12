<?php

/**
 * @class Klas_model
 * @brief Model-klasse voor klassen
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel klas.
 */
class Klas_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**Haalt een bepaalde klas uit de database.
     * @param $id De id van de klas die je wilt opvragen.
     * @return mixed
     */
    function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('klas');

        return $query->row();
    }

    /** Haalt alle klassen uit de database van een bepaalde school.
     * @param $schoolId De id van de school waar je de klassen van wilt opvragen.
     * @return array klasobject
     */
    function getAllByNaamWhereSchool($schoolId)
    {
        $this->db->where('schoolId', $schoolId);
        $this->db->order_by('leerjaar', 'asc');
        $query = $this->db->get('klas');
        return $query->result();
    }

    /**Verwijderd alle klassen uit de database van een bepaalde school.
     * @param $schoolId De id van de school waar je de klassen van wilt verwijderen.
     */
    function deleteAllWhereSchool($schoolId)
    {
        $this->db->where('schoolId', $schoolId);
        $this->db->delete('klas');
    }

    /** $Haalt alle klassen uit de database met een bepaalt leerjaar.
     * @param $leerjaar De naam van het leerjaar waar je de klassen van wilt opvragen.
     * @return array
     */
    function getWhereLeerjaar($leerjaar)
    {
        $this->db->where('leerjaar', $leerjaar);
        $query = $this->db->get('klas');
        return $query->result();
    }

    /** Een klas wordt in de database gestoken.
     * @param $klas Het klasobject dat je in de database wilt steken.
     * @return int De id van de geinserte klas.
     */
    function insert($klas)
    {
        $this->db->insert('klas', $klas);
        return $this->db->insert_id();

    }

    /**Een klas wordt in de database geupdate.
     * @param $klas Het klasobject dat je in de database wilt updaten.
     */
    function update($klas)
    {
        $this->db->where('id', $klas->id);
        $this->db->update('klas', $klas);
    }

    /** Een klas wordt in de database verwijderd.
     * @param $id De id van de klas die je wilt verwijderen.
     */
    function delete($id)
    {
        /**
         * Een klas wordt in de database verwijderd.
         * @param id De id van de klas die je wilt verwijderen.
         */
        $this->db->where('id', $id);
        $this->db->delete('klas');
    }
}