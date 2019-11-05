<?php
namespace App\Repository\QuestionList;

use App\Entity\QuestionList;
use App\Exception\RepositoryException;
use Exception;
use JMS\Serializer\SerializerInterface;

class JsonQuestionListRepository extends AbstractFileStorageQuestionListRepository
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param string $storageFile
     * @param SerializerInterface $serializer
     */
    public function __construct(string $storageFile, SerializerInterface $serializer)
    {
        parent::__construct($storageFile);
        $this->serializer = $serializer;
    }

    /**
     * @return QuestionList
     *
     * @throws RepositoryException
     */
    public function retrieveQuestionListFromStorage(): QuestionList
    {
        $jsonString = file_get_contents($this->storageFile);
        try {
            $questions = $this->serializer->deserialize($jsonString, 'array<App\\Entity\\Question>', 'json');
        } catch (Exception $exception) { // we don't know what comes out of this library, but we want to keep our exceptions tidy, thus the type upcast
            throw new RepositoryException('There was an error during deserialization of data from repository', $exception->getCode(), $exception);
        }
        $questionList = new QuestionList();
        $questionList->setData($questions);

        return $questionList;
    }
}