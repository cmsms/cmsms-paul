<?php
if (!cmsms()) throw new Exception('Cannot be called directly');
/** @var Paul $this */
/** @var Smarty $smarty */

$cache_id = '|' . $this->GetName() . '_poll_' . md5(serialize($params));
$compile_id = '';
$template_resource = $this->getTemplateResource('poll', $params);

if (!$smarty->isCached($template_resource, $cache_id, $compile_id)) {
    $c = new MCFCriteria();
    $c->add('is_active', true);
    if(isset($params['question_id']))
    {
        $c->add('id', $params['question_id']);
    }
    $c->addDescendingOrderByColumn('created_at');
    /** @var PQuestion $question */
    $question = PQuestion::doSelectOne($c);

    if ($question) {
        $user_key = PResponse::retrieveCurrentUser();

        var_dump($user_key);
        var_dump(PResponse::userHasResponses($question, $user_key));

        if (!PResponse::userHasResponses($question, $user_key)) {
            $form = new CMSForm($this->GetName(), $id, 'answer', $returnid);
            $form->setButtons(array('submit'));
            $form->setLabel('submit', 'Participate');
            $form->setWidget('question_id', 'hidden', array('value' => $question->getId()));

            $answers_form = array();
            foreach ($question->getAnswers() as $answer) {
                /** @var PAnswer $answer */
                $answers_form[$answer->getId()] = $answer->getName();
            }

            if ($question->getIsMultiple()) {
                $form->setWidget('answers', 'select', array('label' => '', 'values' => $answers_form, 'expanded' => true, 'multiple' => true));
            } else {
                $form->setWidget('answers', 'select', array('label' => '', 'values' => $answers_form, 'expanded' => true));
            }

            $smarty->assign('form', $form);
        } else {
            $params['question_id'] = $question->getId();
            unset($params['action']);
            echo $this->DoAction('answer', $id, $params, $returnid);
            return;
//            $this->Redirect($id, 'answer', $returnid, array('question_id', $question->getId()), true);
        }


        $smarty->assign('question', $question);
    }
}

echo $smarty->fetch($template_resource, $cache_id, $compile_id);