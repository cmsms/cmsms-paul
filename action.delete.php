<?php
/**
 * Date: 09/12/13
 * Time: 16:23
 * Author: Jean-Christophe Cuvelier <jcc@morris-chapman.com>
 */
if (!cmsms()) exit;
/** @var $this Paul */
/** @var $smarty Smarty_CMS */

if (!$this->CheckAccess()) exit;

if(isset($params['answer_id']))
{
    if($answer = PAnswer::retrieveByPk($params['answer_id']))
    {
        /** @var PAnswer $answer */
        $question_id = $answer->getQuestionId();
        $answer->delete();
        $this->Redirect($id, 'edit', '', array('question_id' => $question_id));
    }
}