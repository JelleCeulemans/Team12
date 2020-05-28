<?php
/**
 * @class ZwemMoment_model
 * @brief Model-klasse voor zwemmomenten
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel zwemMoment.
 */
class ZwemMoment_model extends CI_Model
{
    /**
     * ZwemMoment_model constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Haalt een zwemmoment uit de database.
     * @param $id De id van het zwemmoment dat je wilt opvragen.
     * @return mixed
     */
    function get($id)
    {

        $this->db->where('id', $id);
        $query = $this->db->get('zwemMoment');

        return $query->row();
    }

    /**
     * Haalt een zwemmoment uit de database.
     * @param $id De id van het zwemmoment dat je wilt opvragen.
     * @return mixed
     */
    function getAllById(){

        $this->db->order_by('id', 'asc');
        $query = $this->db->get('zwemMoment');
        return $query->result();
    }

    /**
     * Haalt alle momenten met een bepaald niveau uit de database.
     * @param $niveauId de id van het niveau waarvan je de momenten wilt opvragen.
     * @return mixed
     */
    function getAllWhereNiveau($niveauid){

        $this->db->where('niveauId', $niveauid);
        $query = $this->db->get('zwemMoment');
        return $query->result();
    }

    /**
     * Verwijderd alle momenten met een bepaald niveau uit de database.
     * @param $niveauId de id van het niveau waarvan je de momenten wilt verwijderen.
     */
    function deleteWhereNiveau($niveauId){


        $this->db->where('niveauId', $niveauId);
        $this->db->delete('zwemMoment');
    }

    /**
     * Een zwemmoment wordt in de database gestoken.
     * @param $zwemmoment Het zwemmomentobject dat je in de database wilt steken.
     * @return int insert id
     */
    function insert($zwemmoment){


        $this->db->insert('zwemMoment', $zwemmoment);
        return $this->db->insert_id();

    }

    /**
     * Een zwemmoment wordt in de database geupdate.
     * @param $zwemmoment Het zwemmomentobject dat je in de database wilt updaten.
     */
    function update($zwemmoment)
    {

        $this->db->where('id', $zwemmoment->id);
        $this->db->update('zwemMoment', $zwemmoment);
    }

    /**
     * Een zwemmoment wordt in de database verwijderd.
     * @param $id de id van het zwemmoment dat je in de database wilt verwijderen.
     */
    function delete($id)
    {

        $this->db->where('id', $id);
        $this->db->delete('zwemMoment');
    }

    /**
     * Haalt een zwemmomenten uit de database.
     */
    function getAllZwemlessen() {

        $query = $this->db->get('zwemMoment');
        return $query->result();
    }

}
