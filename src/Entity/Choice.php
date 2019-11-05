<?php
namespace App\Entity;

use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(required={"text"})
 */
class Choice
{
    /**
     * @var string
     * @OA\Property()
     * @Serializer\Type("string")
     */
    private $text;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }
}