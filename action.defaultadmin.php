<?php
if (!cmsms()) exit;
/** @var $this Paul */
/** @var $smarty Smarty_CMS  */

if (!$this->CheckAccess()) exit;

$c = new MCFCriteria();
$c->addDescendingOrderByColumn('created_at');
$questions = PQuestion::doSelect($c);

foreach($questions as &$question)
{
    /** @var $question PQuestion */
    $question->actions['edit'] = $this->CreateLink($id, 'edit', '', '', array('question_id' => $question->getId()), '', true);
    if($question->getIsActive())
    {
        $question->actions['activation'] = $this->CreateLink($id, 'question_status', '', '', array('question_id' => $question->getId()), '', true);
    }
    else
    {
        $question->actions['activation'] = $this->CreateLink($id, 'question_status', '', '', array('question_id' => $question->getId()), '', true);
    }
}

$smarty->assign('questions', $questions);

$smarty->assign('add', $this->CreateLink($id, 'edit', '', '', array(), '', true));

echo $this->ProcessTemplate('admin.questions.tpl');