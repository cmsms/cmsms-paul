<?php

class Paul extends CMSModule {

    public function GetName()
    {
        return 'Paul';
    }

    public function GetFriendlyName()
    {
        return $this->GetName();
    }

    public function GetAuthor()
    {
        return 'Jean-Christophe Cuvelier';
    }

    public function GetAuthorEmail()
    {
        return 'jcc@atomseeds.com';
    }

    public function GetDependencies()
    {
        return array(
            'CMSForms' => '1.10.14',
            'MCFactory' => '3.4.92'
        );
    }

    public function MinimumCMSVersion()
    {
        return '1.11';
    }

    public function HasAdmin()
    {
        return true;
    }

    public function GetAdminSection()
    {
        return 'content';
    }

    public function IsPluginModule()
    {
        return true;
    }

    public function InitializeFrontend()
    {
        $this->RestrictUnknownParams(true);
        $this->SetParameterType('question_id', CLEAN_INT);
        $this->SetParameterType('answer_id', CLEAN_INT);
    }

    public function CheckAccess()
    {
        return $this->CheckPermission('Manage Paul');
    }

    public function Install()
    {
        PQuestion::createTable();
        PAnswer::createTable();
        PResponse::createTable();
        $this->CreatePermission('Manage Paul', 'Allow management of Paul\'s Polls');
        $this->RegisterModulePlugin(true);
//        $this->RegisterSmartyPlugin('Paul_url', 'function', array('Paul', 'CreateUrl'));
    }

    public function Uninstall()
    {
        PResponse::deleteTable();
        PAnswer::deleteTable();
        PQuestion::deleteTable();
        $this->RemovePermission('Manage Paul');
//        $this->RemoveSmartyPlugin('Paul_url');
    }

//    public function CreateUrl($params,$content,$template,&$repeat)
//    {
//        $action = isset($params['action'])?$params['action']:'default';
//        if(isset($params['action'])) unset($params['action']);
//        global $id;
//        global $returnid;
//        $module = cms_utils::get_module('Paul');
//        return $module->CreateLink($id, $action, $returnid, '', $params, '', true);
//    }
}