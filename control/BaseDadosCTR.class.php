<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../control/AtualAplicCTR.class.php');
require_once('../model/AtividadeDAO.class.php');
require_once('../model/AtividadeDAO.class.php');
require_once('../model/ComponenteDAO.class.php');
require_once('../model/EquipDAO.class.php');
require_once('../model/FuncionarioDAO.class.php');
require_once('../model/ItemCheckListDAO.class.php');
require_once('../model/ItemOSMecanDAO.class.php');
require_once('../model/OSDAO.class.php');
require_once('../model/REquipAtivDAO.class.php');
require_once('../model/ROSAtivDAO.class.php');
require_once('../model/ServicoDAO.class.php');
require_once('../model/TurnoDAO.class.php');
/**
 * Description of BaseDadosCTR
 *
 * @author anderson
 */
class BaseDadosCTR {

    public function dadosAtiv($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $atividadeDAO = new AtividadeDAO();

            $dados = array("dados" => $atividadeDAO->dados());
            $retJson = json_encode($dados);

            return $retJson;
        
        }

    }
    
    public function pesqAtiv($info) {

        $rEquipAtivDAO = new REquipAtivDAO();
        $rOSAtivDAO = new ROSAtivDAO();
        $atividadeDAO = new AtividadeDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $idEquip = $d->idEquip;
            $nroOS = $d->nroOS;
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {

            $dadosREquipAtiv = array("dados" => $rEquipAtivDAO->dados($idEquip));
            $resREquipAtiv = json_encode($dadosREquipAtiv);

            $dadosROSAtiv = array("dados" => $rOSAtivDAO->pesq($nroOS));
            $resROSAtiv = json_encode($dadosROSAtiv);

            $dadosAtividade = array("dados" => $atividadeDAO->dados());
            $resAtividade = json_encode($dadosAtividade);

            return $resREquipAtiv . "_" . $resROSAtiv . "_" . $resAtividade;
        
        }
  
    }

    public function dadosComponente($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $componenteDAO = new ComponenteDAO();

            $dados = array("dados"=>$componenteDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    
    public function dadosEquip($info) {

        $equipDAO = new EquipDAO();
        $rEquipAtivDAO = new REquipAtivDAO();
        $atualAplicCTR = new AtualAplicCTR();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $nroEquip = $d->nroEquip;
            $versao = $d->versao;
        }
        
        $dadosEquip = array("dados" => $equipDAO->dados($nroEquip));
        $resEquip = json_encode($dadosEquip);

        $dadosREquipAtivDAO = array("dados" => $rEquipAtivDAO->pesqNroEquip($nroEquip));
        $resREquipAtivDAO = json_encode($dadosREquipAtivDAO);

        $v = $equipDAO->verifEquipNro($nroEquip);
        if ($v > 0) {
            $atualAplicCTR->inserirAtualVersao($equipDAO->retEquipNro($nroEquip), $versao);
        }
        
        return $resEquip . "_" . $resREquipAtivDAO;

    }

    public function dadosFunc($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $funcionarioDAO = new FuncionarioDAO();

            $dados = array("dados" => $funcionarioDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    public function dadosItemCheckList($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){

            $itemCheckListDAO = new ItemCheckListDAO();

            $dados = array("dados"=>$itemCheckListDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosItemOSMecan($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){

            $itemOSMecanDAO = new ItemOSMecanDAO();

            $dados = array("dados"=>$itemOSMecanDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    
    public function pesqOS($info) {

        $osDAO = new OSDAO();
        $rOSAtivDAO = new ROSAtivDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $nroOS = $d->nroOS;
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {

            $dadosOS = array("dados" => $osDAO->pesq($nroOS));
            $resOS = json_encode($dadosOS);

            $dadosROSAtiv = array("dados" => $rOSAtivDAO->pesq($nroOS));
            $resROSAtiv = json_encode($dadosROSAtiv);

            return $resOS . "_" . $resROSAtiv;
        
        }
        
    }
        
    public function pesqOSMecan($info) {

        $osDAO = new OSDAO();
        $itemOSMecanDAO = new ItemOSMecanDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $nroOS = $d->nroOS;
            $idEquip = $d->idEquip;
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {

            $dadosOS = array("dados" => $osDAO->pesqMecan($nroOS, $idEquip));
            $resOS = json_encode($dadosOS);

            $dadosItemOSMecan = array("dados" => $itemOSMecanDAO->pesq($nroOS, $idEquip));
            $resItemOSMecan = json_encode($dadosItemOSMecan);

            return $resOS . "_" . $resItemOSMecan;
        
        }

    }
    
    public function dadosOS($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){

            $osDAO = new OSDAO();

            $dadosOS = array("dados" => $osDAO->dados());
            $resOS = json_encode($dadosOS);

            return $resOS;
        
        }

    }
        
    public function dadosROSAtiv($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){

            $rOSAtivDAO = new ROSAtivDAO();

            $dados = array("dados"=>$rOSAtivDAO->dadosECM());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    
    public function dadosServico($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){

            $servicoDAO = new ServicoDAO();

            $dados = array("dados"=>$servicoDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    
    public function dadosTurno($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){

            $turnoDAO = new TurnoDAO();

            $dados = array("dados"=>$turnoDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    
}
