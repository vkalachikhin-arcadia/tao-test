<?php
namespace App\Repository\Question;

use App\Entity\QuestionList;
use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NoOpQuestionRepository implements QuestionRepositoryInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param QuestionList $question
     *
     * @throws ValidationException
     */
    public function saveQuestion(QuestionList $question)
    {
        // we don't persist any data here, try to validate and do nothing
        $errors = (string) $this->validator->validate($question);
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        // somewhere here should be persisting to some storage...
    }

}