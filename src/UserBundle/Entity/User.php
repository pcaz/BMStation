<?php

namespace UserBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\Column(type="string",length=256, nullable=true)
	 *
	 */
	protected $question;
	/**
	 * @ORM\Column(type="string",length=256, nullable=true)
	 *
	 */
	protected $answer;
	/**
	 * @ORM\Column(type="string",length=256, nullable=true)
	 *
	 */
	protected $answerCanonical;
	

	public function __construct()
	{
		parent::__construct();
	}

	public function toArray(){
		$result=array(
				'id'=>$this->id,
				'username'=> $this->username,
				'email'=>$this->email,
				'enabled'=>$this->enabled,
				'password'=>$this->password,
				'roles'=>$this->roles,
				'last_login'=>$this->getLastLogin(),
		);
		return $result;
	}

    /**
     * Set question
     *
     * @param string $question
     *
     * @return User
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return User
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set canonicalAnswer
     *
     * @param string $canonicalAnswer
     *
     * @return User
     */
    public function setCanonicalAnswer($canonicalAnswer)
    {
        $this->canonicalAnswer = $canonicalAnswer;

        return $this;
    }

    /**
     * Get canonicalAnswer
     *
     * @return string
     */
    public function getCanonicalAnswer()
    {
        return $this->canonicalAnswer;
    }

    /**
     * Set answerCanonical
     *
     * @param string $answerCanonical
     *
     * @return User
     */
    public function setAnswerCanonical($answerCanonical)
    {
        $this->answerCanonical = $answerCanonical;

        return $this;
    }

    /**
     * Get answerCanonical
     *
     * @return string
     */
    public function getAnswerCanonical()
    {
        return $this->answerCanonical;
    }
    
    /**
     * Overridden so that username is now optional
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->setUsername($email);
        return parent::setEmail($email);
    }
}
