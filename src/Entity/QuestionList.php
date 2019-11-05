<?php
namespace App\Entity;

use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;
use TypeError;

/**
 * @OA\Info(
 *     version="v1",
 *     title="Assessment API",
 *     description="Assessment API"
 * )
 */

/**
 * @OA\Schema
 */
class QuestionList
{
    /**
     * @var array
     * @OA\Property(
     *  @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/Question")
     *  ),
     * @OA\Items(ref="#/components/schemas/Question")
     * )
     *
     * @Serializer\Type("array<App\Entity\Question>")
     */
    private $data;

    /**
     * @return Question[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $dataArray
     */
    public function setData(array $dataArray)
    {
        foreach ($dataArray as $dataItem) {
            if (!($dataItem instanceof Question)) {
                throw new TypeError("Wrong type, should be " . Question::class);
            }
        }
        $this->data = $dataArray;
    }
}