<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/BoletimMMFertDAO.class.php');
require_once('../model/ApontMecanDAO.class.php');
/**
 * Description of MotoMecFert
 *
 * @author anderson
 */
class MotoMecFertCTR {
 
    private $idBolMMArray;
    private $idApontMecanArray;
    
    public function salvarBolAbertoMMFert($info) {

        $dados = $info['dado'];
        $array = explode("_",$dados);

        $jsonObjBoletim = json_decode($array[0]);
        $jsonObjApontMecan = json_decode($array[1]);

        $dadosBoletim = $jsonObjBoletim->boletim;
        $dadosApontMecan = $jsonObjApontMecan->apontmecan;

        $ret = $this->salvarBoletimAbertoMMFert($dadosBoletim, $dadosApontMecan);

        return $ret;

    }
    
    public function salvarBolFechadoMMFert($info) {

        $dados = $info['dado'];
        $array = explode("_",$dados);

        $jsonObjBoletim = json_decode($array[0]);
        $jsonObjApontMecan = json_decode($array[1]);

        $dadosBoletim = $jsonObjBoletim->boletim;
        $dadosApontMecan = $jsonObjApontMecan->apontmecan;

        $ret = $this->salvarBoletimFechMMFert($dadosBoletim, $dadosApontMecan);

        return $ret;

    }

    private function salvarBoletimAbertoMMFert($dadosBoletim, $dadosApontMecan) {
        
        $boletimMMFertDAO = new BoletimMMFertDAO();
        $this->idBolMMArray = array();
        $this->idApontMecanArray = array();
        
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMFertDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMFertDAO->insBoletimMMAberto($bol);
            }
            $idBolBD = $boletimMMFertDAO->idBoletimMM($bol);
            $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
            $this->idBolMMArray[] = array("idBolMMFert" => $bol->idBolMMFert, "idExtBolMMFert" => $idBolBD);
        }
        
        $dadoBol = array("boletim"=>$this->idBolMMArray);
        $retBol = json_encode($dadoBol);
        
        $dadoApontMecan = array("apontmecan"=>$this->idApontMecanArray);
        $retApontMecan = json_encode($dadoApontMecan);
        
        return 'BOLABERTOMM_' . $retBol . "_" . $retApontMecan;
        
    }

    private function salvarBoletimFechMMFert($dadosBoletim, $dadosApontMecan) {
        
        $boletimMMFertDAO = new BoletimMMFertDAO();
        $this->idBolMMArray = array();
        $this->idApontMecanArray = array();
        
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMFertDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMFertDAO->insBoletimMMFechado($bol);
                $idBolBD = $boletimMMFertDAO->idBoletimMM($bol);
            } else {
                $idBolBD = $boletimMMFertDAO->idBoletimMM($bol);
                $boletimMMFertDAO->updateBoletimMMFechado($idBolBD, $bol);
            }
            $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
            $this->idBolMMArray[] = array("idBolMMFert" =>$bol->idBolMMFert);
        }
        
        $dadoBol = array("boletim"=>$this->idBolMMArray);
        $retBol = json_encode($dadoBol);

        $dadoApontMecan = array("apontmecan"=>$this->idApontMecanArray);
        $retApontMecan = json_encode($dadoApontMecan);

        return 'BOLFECHADOMM_' . $retBol . "_" . $retApontMecan;
        
    }

    private function salvarApontMecan($idBolBD, $idBolCel, $dadosApontMecan) {
        $apontMecanDAO = new ApontMecanDAO();
        foreach ($dadosApontMecan as $apontMecan) {
            if ($idBolCel == $apontMecan->idBolApontMecan) {
                $v = $apontMecanDAO->verifApontMecan($idBolBD, $apontMecan);
                if ($v == 0) {
                    if($apontMecan->statusApontMecan == 1){
                        $apontMecanDAO->insApontMecanAberto($idBolBD, $apontMecan);
                    }
                    else if($apontMecan->statusApontMecan == 3){
                        $apontMecanDAO->insApontMecanFechado($idBolBD, $apontMecan);
                    }
                }
                else{
                    if($apontMecan->statusApontMecan == 3){
                        $apontMecanDAO->updateApontMecan($idBolBD, $apontMecan);
                    }
                }
                $this->idApontMecanArray[] = array("idApontMecan" => $apontMecan->idApontMecan);
            }
        }

    }
    
}
