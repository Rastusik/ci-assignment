<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Import;

use Exception;

/**
 * Class Result
 * @author mfris
 * @package AppBundle\Service\Import
 */
final class Result
{

    /**
     * @var bool
     */
    private $success = true;

    /**
     * @var Exception
     */
    private $exception;

    /**
     * Result constructor.
     * @param Exception $exception
     */
    public function __construct(Exception $exception = null)
    {
        $this->success = ($exception === null);
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
     * @return Exception|null
     */
    public function getException()
    {
        return $this->exception;
    }
}
