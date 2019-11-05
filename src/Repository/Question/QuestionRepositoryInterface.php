<?php
namespace App\Repository\Question;

use App\Entity\QuestionList;
use App\Exception\RepositoryException;
use App\Exception\ValidationException;

interface QuestionRepositoryInterface
{
    /**
     * @param QuestionList $question
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function saveQuestion(QuestionList $question);
}