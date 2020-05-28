<?php
/**
 * @class Niveau_model
 * @brief Model-klasse voor niveaus
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel niveau.
 */
class Niveau_model extends CI_Model
{
    /**
     * Niveau_model constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /** Haalt een niveau uit de database.
     * @param $id De id van het niveau dat je wilt opvragen.
     * @return mixed van niveaus.
     */
    function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('niveau');
        return $query->row();
    }

    /** Haalt alle nieveaus uit de database.
     * @return array van niveaus.
     */
    function getAll()
    {
        $query = $this->db->get('niveau');
        return $query->result();
    }

    /** Een niveau wordt in de database gestoken.
     * @param $niveau Het niveauobject dat je in de database wilt steken.
     * @return int
     */
    function insert($niveau)
    {
        $this->db->insert('niveau', $niveau);
        return $this->db->insert_id();

    }

    /** Een niveau wordt in de database geupdate.
     * @param $niveau Het niveauobject dat je in de database wilt updaten.
     */
    function update($niveau)
    {
        $this->db->where('id', $niveau->id);
        $this->db->update('niveau', $niveau);
    }

    /** Een niveau wordt in de database verwijderd.
     * @param $id De id van het niveau dat je wilt verwijderen.
     */
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('niveau');
    }
}