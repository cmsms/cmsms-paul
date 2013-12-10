<?php

class PAnswer extends MCFObject
{
    protected $name;
    protected $description;
    protected $question_id;
    protected $position;

    /** @var PQuestion $question */
    protected $question;

    /** @var array $responses */
    protected $responses;
    /**
     * Contain $actions links that can be executed in a template (when loaded)
     * @var
     */
    public $actions = array();

    protected static $table_fields = array(
        'name' => 'C(255)',
        'description' => 'XL',
        'question_id' => 'I',
        'position' => 'I'
    );

    protected static $table_fields_indexes = array('question_id', 'position');

    const TABLE_NAME = 'module_paul_answers';

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
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        if(is_null($this->position)) $this->position = $this->nextPosition();
        return $this->position;
    }

    /**
     * @return int
     */
    private function nextPosition()
    {
        $c = new MCFCriteria();
        $c->add('question_id', $this->getQuestionId());
        $c->addDescendingOrderByColumn('position');
        $last_field = self::doSelectOne($c);
        if ($last_field) {
            return (int)$last_field->getPosition() + 1;
        }
        return 0;
    }

    /**
     * @param int $question_id
     */
    public function setQuestionId($question_id)
    {
        $this->question_id = $question_id;
    }

    /**
     * @return int
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * @param PQuestion $question
     */
    public function setQuestion(PQuestion $question)
    {
        $this->question = $question;
        $this->setQuestionId($question->getId());
    }

    /**
     * @return null|PQuestion
     */
    public function getQuestion()
    {
        if(empty($this->question)) $this->question = PQuestion::retrieveByPk($this->question_id);
        return $this->question;
    }

    /**
     * @param array $responses
     */
    public function setResponses($responses)
    {
        $this->responses = $responses;
    }

    /**
     * @return array
     */
    public function getResponses()
    {
        if(empty($this->responses)) $this->responses = $this->loadResponses();
        return $this->responses;
    }

    /**
     * @return array
     */
    private function loadResponses()
    {
        $c = new MCFCriteria();
        $c->add('answer_id', $this->getId());
        return PResponse::doSelect($c);
    }

    /**
     * @return int
     */
    public function countResponses()
    {
        return count($this->getResponses());
    }

    public function addResponse($response_key = null)
    {
        return PResponse::addResponse($this, $response_key);
    }

}