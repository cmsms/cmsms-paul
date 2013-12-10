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

    protected static $table_fields_indexes = array('question_id', 'answer_id');

    protected static $table_relations = array(
        'question' => array(
            'local' => 'question_id',
            'remote' => 'id',
            'class' => 'PQuestion'
        ),
        'answer' => array(
            'local' => 'answer_id',
            'remote' => 'id',
            'class' => 'PAnswer'
        )
    );

    const TABLE_NAME = 'module_paul_responses';

    /**
     * @param PAnswer $answer
     */
    public function setAnswer(PAnswer $answer)
    {
        $this->answer = $answer;
        $this->setQuestion($answer->getQuestion());
        $this->answer_id = $answer->getId();
    }

    /**
     * @return PAnswer
     */
    public function getAnswer()
    {
        if (empty($this->answer)) $this->answer = PAnswer::retrieveByPk($this->answer_id);
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
        if (empty($this->question)) {
            $this->question = PQuestion::retrieveByPk($this->question_id);
            if ($this->question->getId() != $this->getAnswer()->getQuestion()->getId()) {
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

    public static function userHasResponses($user_id, $question_id)
    {
        $c = new MCFCriteria();
        $c->add('question_id', $question_id);
        $c->add('response_key', $user_id);

        if ($response = self::doSelectOne($c)) {
            return true;
        }
        return false;
    }

    public static function retrieveCurrentUser()
    {
        // CMS Users
        if ($cmsusers = cms_utils::get_module('CMSUsers')) {
            /** @var CMSUsers $cmsusers */
            if ($user = $cmsusers->getUser()) {
                return 'cmsuser_' . $user->getId();
            }

        }
        // Cookie
        if (!isset($_COOKIE['cmsms_paul_user'])) {
            $key = 'cmsms_paul_' . time() . '_' . uniqid();
            setcookie('cmsms_paul_user', $key, strtotime('+ 10 years'));
            $_COOKIE['cmsms_paul_user'] = $key;
            return $key;
        } else {
            return $_COOKIE['cmsms_paul_user'];
        }
    }

    public static function addResponse(PAnswer $answer, $response_key = null)
    {
        if (is_null($response_key)) {
            $response_key = self::retrieveCurrentUser();
        }

        $c = new MCFCriteria();
        $c->add('answer_id', $answer->getId());
        $c->add('response_key', $response_key);
        $response = self::doSelectOne($c);
        if (!$response) {
            $response = new PResponse();
            $response->setAnswer($answer);
            $response->setResponseKey($response_key);
            $response->save();
        }

        return $response;
    }

    public static function cleanResponses(PQuestion $question, $response_key = null)
    {
        if (is_null($response_key)) {
            $response_key = self::retrieveCurrentUser();
        }

        $c = new MCFCriteria();
        $c->add('question_id', $question->getId());
        $c->add('response_key', $response_key);
        $responses = self::doSelect($c);

        foreach ($responses as $response) {
            /** @var PResponse $response */
            $response->delete();
        }
    }

}