<?php

class PQuestion extends MCFObject
{
    protected $name;
    protected $description;
    protected $is_active = false;
    protected $is_multiple;

    protected $answers;

    /**
     * Contain $actions links that can be executed in a template (when loaded)
     * @var
     */
    public $actions = array();

    public static $table_fields = array(
        'name' => 'C(255)',
        'description' => 'XL',
        'is_active' => 'I',
        'is_multiple' => 'I'
    );

    const TABLE_NAME = 'module_paul_questions';

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }

    /**
     * @return int
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param int $is_multiple
     */
    public function setIsMultiple($is_multiple)
    {
        $this->is_multiple = $is_multiple;
    }

    /**
     * @return int
     */
    public function getIsMultiple()
    {
        return $this->is_multiple;
    }

    /**
     * @param array $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return array
     */
    public function getAnswers()
    {
        if (empty($this->answers)) $this->answers = $this->loadAnswers();
        return $this->answers;
    }

    /**
     * @return array
     */
    private function loadAnswers()
    {
        $c = new MCFCriteria();
        $c->add('question_id', $this->getId());
        $c->addAscendingOrderByColumn('position');
        return PAnswer::doSelect($c);
    }

    /**
     * @return int
     */
    public function countAnswers()
    {
        if ($this->getAnswers()) {
            return count($this->getAnswers());
        } else {
            return 0;
        }
    }

    /**
     * @return bool
     */
    public function hasAnswers()
    {
        return (bool)$this->countAnswers();
    }
}