<?php
namespace App\Repository\QuestionList;

use App\Entity\QuestionList;
use App\Exception\RepositoryException;

interface QuestionListRepositoryInterface
{
    /**
     * @return QuestionList
     *
     * @throws RepositoryException
     */
    public function getQuestionList(): QuestionList;
}