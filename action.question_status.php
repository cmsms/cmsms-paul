<?php
/**
 * Date: 10/12/13
 * Time: 10:11
 * Author: Jean-Christophe Cuvelier <jcc@morris-chapman.com>
 */
if (!cmsms()) exit;
/** @var $this Paul */
/** @var $smarty Smarty_CMS */

if (!$this->CheckAccess()) exit;

if(isset($params['question_id']))
{
    /** @var PQuestion $question */
    $question = PQuestion::retrieveByPk($params['question_id']);
    if($question)
    {
        $question->setIsActive(!$question->getIsActive());
        $question->save();
    }
}

$this->Redirect($id, 'defaultadmin');