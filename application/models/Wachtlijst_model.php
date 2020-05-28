<?php
/**
 * @Class Wachtlijst_model
 * @brief Model-klasse voor het het opvragen van wachtlijsten en zwemgroepen
 */

class Wachtlijst_model extends CI_Model
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    function getGroepenWithZwemmers(){

        $query =$this->db->get('zwemMoment');
        $zwemMomenten = $query->result();

        foreach ($zwemMomenten as $zwemMoment){
            $zwemMoment->niveau = $this->getNiveau($zwemMoment->niveauId);
            $zwemMoment->huidigeZwemmers = $this->getHuidigeZwemmers($zwemMoment->id);
            $zwemMoment->beschikbareZwemmers = $this->getBeschikbareZwemmers($zwemMoment->id);
        }


        return $zwemMomenten;

    }
    /**
     * @param $id de id van de zwemgroep dat opgevragen moet worden
     * @return de huidige zwemmers in deze zwemgroep
     */
    function getHuidigeZwemmers($id){

        $this->db->where('zwemMomentId',$id);
        $this->db->where('statusId',2);

        $query = $this->db->get('beschikbaar');

        $huidigeZwemmers =  $query->result();

        $temp = array();

        foreach ($huidigeZwemmers as $zwemmer){
            array_push($temp, $this->getZwemmer($zwemmer->zwemmerId));
        }

        return $temp;

    }

    /**
     * @param $id de id van de zwemgroep dat opgevragen moet worden
     * @return de beschikbare zwemmers voor deze groep
     */
    function getBeschikbareZwemmers($id){
        $this->db->where('zwemMomentId',$id);
        $this->db->where('statusId',1);

        $query = $this->db->get('beschikbaar');

        $beschikbareZwemmers =  $query->result();

        $temp = array();

        foreach ($beschikbareZwemmers as $zwemmer){
            array_push($temp , $this->getZwemmer($zwemmer->zwemmerId));
        }

        return $temp;
    }

    /**
     * @param $id de id van de zwemmer
     * @return de informatie van de zwemmer
     */
    function getZwemmer($id){
        $this->db->where('id',$id);
        $query = $this->db->get('zwemmer');
        return $query->row();
    }

    /**Verwijderd alle wachtlijsten met een bepaalde zwemmoment uit de database.
     * @param $momentId De id van het moment waarvan je de wachtlijsten wilt verwijderen.
     */
    function deleteWhereMoment($momentId){
        $this->db->where('zwemMomentId', $momentId);
        $this->db->delete('beschikbaar');
    }

    /**Verwijderd alle wachtlijsten met een bepaald niveau uit de database.
     * @param $niveauId de id van het niveau waarvan je de wachtlijst wilt verwijderen.
     */
    function deleteWhereNiveau($niveauId){
        $this->db->where('niveauId', $niveauId);
        $this->db->delete('beschikbaar');
    }

    /** Verwijderd alle wachtlijsten met een bepaalde zwemmer uit de wachtlijst.
     * @param $zwemmerId De id van de zwemmer waarvan je de wachtlijst wilt verwijderen.
     */
    function deleteWhereZwemmer($zwemmerId){

        $this->db->where('zwemmerId', $zwemmerId);
        $this->db->delete('beschikbaar');
    }

    function getWachtlijst(){

        $this->db->where('statusId',1);

        $query = $this->db->get('beschikbaar');

        $wachtlijst =  $query->result();
        $zwemmers = array();

        foreach ($wachtlijst as $zwemmer){
            $zwemmer->zwemmer = $this->getZwemmer($zwemmer->zwemmerId);
            array_push($zwemmers,$zwemmer->zwemmer);
        }

        return $zwemmers;

    }

    /**
     * @param $id de id van de zwemmer
     * @return de zwemmer met het zwemniveau
     */
    function getZwemmerWithNiveau($id){

        $this->db->where('id',$id);
        $query = $this->db->get('zwemmer');
        $zwemmer = $query->row();
        $this->db->where('id',$zwemmer->niveauId);
        $query = $this->db->get('niveau');
        $zwemmer->niveau = $query->row();
        return $zwemmer;
    }

    /**
     * @param $id de id van het zwemniveau
     * @return het zwemniveau
     */
    function getNiveau($id){
        $this->db->where('id',$id);
        $query = $this->db->get('niveau');

        return $query->row();
    }

    /**
     * @param $id de id van de zwemmer
     * @param $zwemmoment de id de zwemgroep
     * @param $status de status
     */
    function updateZwemmerBeschikbaarheid($id,$zwemmoment,$status){

        $this->db->where('zwemmerId', $id);
        $this->db->where('zwemMomentId', $zwemmoment);
        $query = $this->db->get('beschikbaar');
        $beschikbaar = $query->row();

        $beschikbaar->statusId=$status;

        $this->db->where('zwemmerId', $id);
        $this->db->where('zwemMomentId', $zwemmoment);
        $this->db->update('beschikbaar', $beschikbaar);

    }

    /**
     * @param $id de id van de zwemgroep
     * @return het maximum aantal in deze groep
     */
    function getGroep($id){

        $this->db->where('id', $id);
        $query =$this->db->get('zwemMoment');
        $groep =$query->row();

        return $groep->maximumAantal;

    }

    /**
     * @param $id de id van de zwemgroep
     * @return het aantal zwemmers in deze groep
     */
    function getAantalInGroep($id){
        $this->db->where('zwemMomentId', $id);
        $this->db->where('statusId', 2);
        $query =$this->db->get('beschikbaar');

        $zwemmers = $query->result();

        $zwemmers = count($zwemmers);

        return $zwemmers;
    }

    /**
     * @param $id de id van de zwemmer
     */
    function deleteWhereZwemmerId($id) {
        $this->db->where('zwemmerId', $id);
        $this->db->delete('beschikbaar');
    }
}