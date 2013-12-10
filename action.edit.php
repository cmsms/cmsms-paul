<?php
if (!cmsms()) exit;
/** @var $this Paul */
/** @var $smarty Smarty_CMS */

if (!$this->CheckAccess()) exit;

if (isset($params['question_id']) && $params['question_id'] != '') {
    $question = PQuestion::retrieveByPk($params['question_id']);
}

if (!isset($question) || !$question) {
    $question = new PQuestion();
}

$form = new CMSForm($this->GetName(), $id, 'edit', $returnid);

if ($form->isCancelled()) {
    $this->Redirect($id, 'defaultadmin');
}

$form->setButtons(array('submit', 'apply', 'cancel'));
$form->setWidget('question_id', 'hidden', array('object' => &$question, 'get_method' => 'getId'));
$form->setWidget('name', 'text', array('object' => &$question, 'label' => 'Question'));
$form->setWidget('description', 'textarea', array('object' => &$question, 'label' => 'Description', 'show_wysiwyg' => true));
$form->setWidget('is_multiple', 'checkbox', array('object' => &$question, 'label' => 'Multiple', 'text' => 'Allow multiple answers'));

// Answers

$form->setWidget('answer', 'text', array('label' => 'Anwser'));
$form->setWidget('answer_description', 'textarea', array('label' => 'Description'));

if ($form->isSent()) {
    $form->process();

    if (!$form->hasErrors()) {
        $question->save();

        if($answer_value = $form->getWidget('answer')->getValue())
        {
            $answer = new PAnswer();
            $answer->setName($answer_value);
            if($description = $form->getWidget('answer_description')->getValue())
            {
                $answer->setDescription($description);
            }
            $answer->setQuestion($question);
            $answer->save();

            $form->getWidget('answer')->setValue('');
            $form->getWidget('answer_description')->setValue('');

        }
        if ($form->isSubmitted()) {
            $this->Redirect($id, 'defaultadmin');
        }
    }
}

if($question->hasAnswers())
{
    $answers = $question->getAnswers();
    foreach($answers as &$answer)
    {
        /** @var $answer PAnswer */
        $answer->actions['delete'] = $this->CreateLink($id, 'delete', '', '', array('answer_id' => $answer->getId()), '', true);
//        $question->actions['edit'] = $this->CreateLink($id, 'edit', '', '', array('question_id' => $question->getId()), '', true);
    }
}

$smarty->assign('form', $form);
$smarty->assign('question', $question);

echo $this->ProcessTemplate('admin.edit.tpl');