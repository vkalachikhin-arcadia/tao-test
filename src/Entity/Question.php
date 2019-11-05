<?php
namespace App\Entity;

use DateTime;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @OA\Schema
 *
 */
class Question
{
    /**
     * @var string
     * @OA\Property
     *
     * @Serializer\Type("string")
     *
     * @Assert\NotBlank(message="Value should not be empty")
     */
    private $text;

    /**
     * @var DateTime
     * @OA\Property(format="date-time")
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Serializer\SerializedName("createdAt")
     *
     * @Assert\NotBlank(message="Value should not be empty")
     */
    private $createdAt;

    /**
     * @var array
     * @OA\Property(
     *      @OA\JsonContent(
     *          type="array",
     *           @OA\Items(ref="#/components/schemas/Choice")
     *      ),
     *     @OA\Items(ref="#/components/schemas/Choice")
     * )
     *
     * @Serializer\Type("array<App\Entity\Choice>")
     *
     * @Assert\Count(
     *     min=3,
     *     max=3,
     *     maxMessage="There should be exactly 3 choices",
     *     minMessage="There should be exactly 3 choices",
     *     exactMessage="There should be exactly 3 choices"
     * )
     */
    private $choices;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return Choice[]
     */
    public function getChoices(): array
    {
        return $this->choices;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param array $choices
     */
    public function setChoices(array $choices): void
    {
        $this->choices = $choices;
    }
}