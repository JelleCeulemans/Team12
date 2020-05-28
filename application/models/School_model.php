<?php
/**
 * @class School_model
 * @brief Model-klasse voor scholen
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel school.
 */
class School_model extends CI_Model
{
    /**
     * School_model constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /** Haalt een school uit de database.
     * @param $id De id van de school die je wilt opvragen.
     * @return mixed schoolobject.
     */
    function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('school');
        return $query->row();
    }

    /**Haalt alle scholen uit de database samen met klassen.
     * @return mixed array van schoolobjecten met klassen.
     */
    function getAllWithKlassen()
    {
        $query = $this->db->get('school');
        $scholen = $query->result();

        foreach ($scholen as $school) {
            $school->klassen = $this->getKlassen($school->id);
        }

        return $scholen;

    }

    /** Haalt een school uit de database.
     * @param $schoolnaam De naam van de school die je wilt opvragen.
     * @return mixed schoolobject.
     */
    function getWhereNaam($schoolnaam)
    {
        $this->db->where('naam', $schoolnaam);
        $query = $this->db->get('school');
        return $query->row();
    }

    /** Haalt alle klassen uit de database van een bepaalde school.
     * @param $id De schoolid van de klassen die je wilt opvragen
     * @return array van klassen.
     */
    function getKlassen($id)
    {
        $this->db->where('schoolId', $id);
        $query = $this->db->get('klas');

        $temp = array();
        $klassen = $query->result();

        foreach ($klassen as $klas) {
            //$klas->aanwezigheid =$this->getAanwezigheden($klas->id);
            array_push($temp, $klas);

        }

        return $temp;
    }

    /** Haalt alle momenten uit de database van een bepaalde klas.
     * @param $id de klasId van de momenten die je wilt opvragen.
     * @return mixed array van momentenobject.
     */
    function getAanwezigheden($id)
    {
        $this->db->where('klasId', $id);
        $query = $this->db->get('zwemtotaal');

        //$temp = array();
        $aanwezigheden = $query->result();

        //array_push($temp,$aanwezigheden);

        return $aanwezigheden;

    }

    /** Haalt een bepaalde klas uit de database.
     * @param $id De id van de klas die je  wilt ophalen.
     * @return mixed klasobject.
     */
    function getKlas($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('klas');

        return $query->row();
    }

    /**Update een bepaalt moment.
     * @param $aantal Aantal leerlingen van het moment.
     * @param $id id van het moment dat je wilt updaten.
     */
    function updateAanwezigheid($aantal,$id){
        if($aantal>=0){

            $this->db->where('id', $id);

            $query = $this->db->get('zwemtotaal');
            $temp = $query->row();

            $temp->aantal=$aantal;

            $this->db->where('id', $id);
            $this->db->update('zwemtotaal', $temp);

        }

    }

    /** Maakt een leeg moment aan.
     * @return stdClass momentobject.
     */
    function getLeegZwemmomentSchool(){
        $moment = new stdClass();

        $moment->id=0;
        $moment->klasId=0;
        $moment->factuurId=0;
        $moment->datum="";
        $moment->aantal=0;

        return $moment;

    }

    /**Een school wordt in de database gestoken.
     * @param $school Het schoolobject dat je in de database wilt steken.
     * @return int De id van het geinserte object.
     */
    function insert($school){
        $this->db->insert('school', $school);
        return $this->db->insert_id();

    }

    /** Een school wordt in de database geupdate.
     * @param $school het schoolobject dat je in de database wilt updaten.
     */
    function update($school)
    {
        $this->db->where('id', $school->id);
        $this->db->update('school', $school);
    }

    /**Een school wordt in de database verwijderd.
     * @param $id De id van de school die je wilt verwijderen.
     */
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('school');
    }
}