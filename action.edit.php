<?php
if (!cmsms()) exit;
/** @var $this Paul */
if (!$this->CheckAccess()) exit;

if(isset($params['question_id']) && $params['question_id'] != '')
{
    $question = PQuestion::retrieveByPk($params['question_id']);
}

if(!isset($question) || !$question)
{
    $question = new PQuestion();
}

$form = new CMSForm($this->GetName(), $id, 'edit', $returnid);

if($form->isCancelled())
{
    $this->Redirect($id, 'defaultadmin');
}

$form->setButtons(array('submit', 'apply', 'cancel'));
$form->setWidget('question_id', 'hidden', array('object' => &$question, 'get_method' => 'getId'));
$form->setWidget('name', 'text', array('object' => &$question, 'label' => 'Question'));
$form->setWidget('description', 'textarea', array('object' => &$question, 'label' => 'Description', 'show_wysiwyg' => true));
$form->setWidget('is_multiple', 'checkbox', array('object' => &$question, 'label' => 'Multiple', 'text' => 'Allow multiple answers'));

if($form->isSent())
{
    $form->process();

    if(!$form->hasErrors())
    {
        $question->save();

        if($form->isSubmitted())
        {
            $this->Redirect($id, 'defaultadmin');
        }
    }
}


$smarty->assign('form', $form);
$smarty->assign('question', $question);

echo $this->ProcessTemplate('admin.edit.tpl');