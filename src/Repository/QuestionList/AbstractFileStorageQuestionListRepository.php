<?php

namespace App\Repository\QuestionList;

use App\Entity\QuestionList;
use App\Exception\RepositoryException;

abstract class AbstractFileStorageQuestionListRepository implements QuestionListRepositoryInterface
{
    /**
     * @var string
     */
    protected $storageFile;

    /**
     * @param string $storageFile
     */
    public function __construct(string $storageFile)
    {
        $this->storageFile = $storageFile;
    }

    /**
     * @return QuestionList
     *
     * @throws RepositoryException
     */
    public function getQuestionList(): QuestionList
    {
        if (!file_exists($this->storageFile)) {
            throw new RepositoryException('Repository storage file could not be found');
        }

        return $this->retrieveQuestionListFromStorage();
    }

    /**
     * @return QuestionList
     */
    abstract public function retrieveQuestionListFromStorage(): QuestionList;
}