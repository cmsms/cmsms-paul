<?php
if (!cmsms()) throw new Exception('Cannot be called directly');
/** @var Paul $this */
/** @var Smarty $smarty */

$cache_id = '|' . $this->GetName() . '_answer_' . md5(serialize($params));
$compile_id = '';
$template_resource = $this->getTemplateResource('answer', $params);

if (!$smarty->isCached($template_resource, $cache_id, $compile_id)) {

    if(isset($params['question_id']))
    {
        /** @var PQuestion $question */
        $question = PQuestion::retrieveByPk($params['question_id']);

        if($question)
        {
            if(isset($params['submit']))
            {
                // Clean responses
                PResponse::cleanResponses($question);

            }
            if(isset($params['answers']))
            {
                $answers = $params['answers'];
                if(!is_array($answers)) $answers = array($params['answers']);
                if(!$question->getIsMultiple())
                {
                    $answers = array(current($answers));
                }

                foreach($answers as $answer_id)
                {
                    if($answer = PAnswer::retrieveByPk($answer_id))
                    {
                        /** @var $answer PAnswer */
                        if($answer->getQuestionId() == $question->getId())
                        {
                            $answer->addResponse();
                        }
                    }
                }
            }
            $smarty->assign('question', $question);
        }
    }
}

echo $smarty->fetch($template_resource, $cache_id, $compile_id);