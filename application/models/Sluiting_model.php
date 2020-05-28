<?php

/**
 * @class sluiting_model
 * @brief Model-klasse voor sluitingen
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel sluiting.
 */
class sluiting_model extends CI_Model
{
    /**
     * sluiting_model constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /** haalt alle sluitingen uit de database
     * @return mixed
     */
    function get() {
        $this->db->order_by("datum");
       return $this->db->get('sluiting')->result();
    }

    /** Insert een bepaalde sluiting in de database
     * @param $sluiting
     * @return mixed
     */
    function insert($sluiting) {
        $this->db->insert('sluiting', $sluiting);
        return $this->db->insert_id();
    }

    /** Delete een bepaalde sluiting in de database
     * @param $id
     */
    function delete ($id) {
        $this->db->where('id', $id);
        $this->db->delete('sluiting');
    }
}