<?php

class Paul extends CMSModule
{

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

    /**
     * This function return the right template resource regarding default and override parameters
     *
     * @param string $template The default template name
     * @param array $params   The action parameters (to catch the params['template'])
     *
     * @return null|string
     */

    public function getTemplateResource($template, $params)
    {
        if (isset($params['template'])) {
            $template = trim($params['template']);
        }

        if ($this->GetTemplate($template) != '') {
            return $this->GetDatabaseResource($template);
        }

        if ($resource = $this->GetDefaultTemplate($template)) {
            return $this->GetDatabaseResource($resource);
        }

        if (is_file($this->GetModulePath() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'frontend.' . $template . '.tpl')) {
            return $this->GetFileResource('frontend.' . $template . '.tpl');
        }

        return NULL;
    }

    /**
     * Get the list of the default templates
     * @return array
     */

    public function GetDefaultTemplates()
    {
        $array = unserialize($this->GetPreference('default_templates'));
        if (is_array($array)) {
            return $array;
        }

        return array();
    }

    /**
     * Get the default template for an action
     *
     * @param string $action The action who have a default template
     *
     * @return string|bool
     */

    public function GetDefaultTemplate($action)
    {


        $list = $this->GetDefaultTemplates();
        if (!is_array($list)) $list = array();
        if (array_key_exists($action, $list)) {
            return $list[$action];
        } else {
            return false;
        }
    }
}