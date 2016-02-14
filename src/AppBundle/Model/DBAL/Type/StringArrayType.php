<?php

/**
 * @author  mfris
 */
namespace AppBundle\Model\DBAL\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use UnexpectedValueException;

/**
 * Description of DbArrayType
 *
 * @author rasta
 */
class StringArrayType extends Type
{
    const STRING_ARRAY = 'StringArray'; // modify to match your type name

    /**
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('varchar[]');
    }

    /**
     *
     * @param string $value
     * @param AbstractPlatform $platform
     * @return array
     * @throws UnexpectedValueException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($platform instanceof PostgreSqlPlatform) {
            $array = $this->toArray($value);

            return new StringArray($array);
        } else {
            throw new UnexpectedValueException('Unsupported platform - ' . get_class($platform));
        }
    }

    /**
     *
     * @param array $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws UnexpectedValueException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        //settype($value, 'array'); // can be called with a scalar or array

        if ($platform instanceof PostgreSqlPlatform) {
            /* @var $value StringArray */
            return (string) $value; // format
        } else {
            throw new UnexpectedValueException('Unsupported platform - ' . get_class($platform));
        }
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return self::STRING_ARRAY; // modify to match your constant name
    }

    /**
     *
     * @param string $str
     * @return array
     */
    private function toArray(string $str) : array
    {
        if (substr($str, 0, 1) === '{') {
            $str = substr($str, 1, strlen($str) - 2);
        }

        if (substr($str, 0, 1) !== '{') {
            return array_map(function ($item) {
                return trim((string) $item);
            }, explode(',', $str));
        } else {
            $array = array();

            $trimmed = trim($str, '{}');
            $tmp = explode('},{', $trimmed);

            foreach ($tmp as $tmpStr) {
                $array[] = $this->toArray($tmpStr);
            }

            return $array;
        }
    }
}
