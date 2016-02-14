<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\PathFinding;

use JMS\Serializer\Annotation as JMS;
use Exception;

/**
 * Class Result
 * @author mfris
 * @package AppBundle\Service\PathFinding
 */
final class Result
{

    /**
     * @var bool
     * @JMS\Exclude()
     */
    private $success = true;

    /**
     * @var Answer[]
     */
    private $answers;

    /**
     * @var Exception
     * @JMS\Exclude()
     */
    private $exception;

    /**
     * Result constructor.
     * @param Answer[] $answers
     * @param Exception $exception
     */
    public function __construct(array $answers, Exception $exception = null)
    {
        $this->success = ($exception === null);
        $this->answers = $answers;
        $this->exception = $exception;
    }

    /**
     * @return bool
     */
    public function isSuccess() : bool
    {
        return $this->success;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @return Exception|null
     */
    public function getException()
    {
        return $this->exception;
    }
}
