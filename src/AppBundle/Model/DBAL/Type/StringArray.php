<?php

/**
 * @author  mfris
 */
namespace AppBundle\Model\DBAL\Type;

/**
 * Point object for spatial mapping
 */
final class StringArray
{

    /**
     * @var string[]
     */
    protected $data;

    /**
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string[]
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        //Output from this is used with POINT_STR in DQL so must be in specific format
        return $this->toString($this->data);
    }

    /**
     *
     * @param array $array
     * @return string
     */
    private function toString(array $array) : string
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $this->toString($value);
            }
        }

        return '{' . implode(',', $array) . '}';
    }
}
