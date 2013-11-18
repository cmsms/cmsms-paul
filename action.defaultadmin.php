<?php
if (!cmsms()) exit;
/** @var $this Paul */
if (!$this->CheckAccess()) exit;

$c = new MCFCriteria();
$c->addDescendingOrderByColumn('created_at');
$questions = PQuestion::doSelect($c);

$smarty->assign('questions', $questions);

echo $this->ProcessTemplate('admin.default.tpl');