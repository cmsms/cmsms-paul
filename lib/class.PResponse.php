<?php

class PResponse extends MCFObject
{
    /** @var int $answer_id */
    protected $answer_id;

    /** @var PAnswer $answer */
    protected $answer;

    /** @var int $question_id */
    protected $question_id;

    /** @var PQuestion $question */
    protected $question;

    /** @var string $response_key */
    protected $response_key;

    protected static $table_fields = array(
        'answer_id' => 'I',
        'question_id' => 'I', // Redundant with answer_id, used for search
        'response_key' => 'C(255)' // Key to identify the respondent
    );

    const TABLE_NAME = 'module_paul_responses';

    /**
     * @param PAnswer $answer
     */
    public function setAnswer(PAnswer $answer)
    {
        $this->answer = $answer;
        $this->answer_id = $answer->getId();
    }

    /**
     * @return PAnswer
     */
    public function getAnswer()
    {
        if(empty($this->answer)) $this->answer = PAnswer::retrieveByPk($this->answer_id);
        return $this->answer;
    }

    /**
     * @param int $answer_id
     */
    public function setAnswerId($answer_id)
    {
        $this->answer_id = $answer_id;
    }

    /**
     * @return int
     */
    public function getAnswerId()
    {
        return $this->answer_id;
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
     * @return null|PQuestion
     * @throws Exception
     */
    public function getQuestion()
    {
        if(empty($this->question))
        {
            $this->question = PQuestion::retrieveByPk($this->question_id);
            if($this->question->getId() != $this->getAnswer()->getQuestion()->getId())
            {
                throw new Exception('Inconsistent in the PResponse ' . $this->getId() . ' between response\'s question and answer\'s question');
            }
        }
        return $this->question;
    }

    /**
     * @param PQuestion $question
     */
    public function setQuestion(PQuestion $question)
    {
        $this->question = $question;
        $this->question_id = $question->getId();
    }

    /**
     * @param string $response_key
     */
    public function setResponseKey($response_key)
    {
        $this->response_key = $response_key;
    }

    /**
     * @return string
     */
    public function getResponseKey()
    {
        return $this->response_key;
    }



}