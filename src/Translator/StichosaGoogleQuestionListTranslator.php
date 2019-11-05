<?php
namespace App\Translator;

use App\Entity\Choice;
use App\Entity\Question;
use App\Entity\QuestionList;
use Stichoza\GoogleTranslate\GoogleTranslate;

class StichosaGoogleQuestionListTranslator implements QuestionListTranslatorInterface
{
    /**
     * @var GoogleTranslate
     */
    private $translator;

    /**
     * @param GoogleTranslate $translator
     */
    public function __construct(GoogleTranslate $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param QuestionList $questionListToTranslate
     * @param string $toLang
     * @param string $fromLang
     *
     * @return QuestionList
     */
    public function translate(QuestionList $questionListToTranslate, string $toLang, string $fromLang = 'en'): QuestionList
    {
        $this->translator->setSource($fromLang)->setTarget($toLang);
        $translatedQuestionArray = [];
        /** @var Question $originalQuestion */
        foreach ($questionListToTranslate->getData() as $originalQuestion) {
            $translatedChoiceArray = [];
            /** @var Choice $originalChoice */
            foreach ($originalQuestion->getChoices() as $originalChoice) {
                $translatedChoice = new Choice();
                $translatedChoice->setText($this->translateString($originalChoice->getText()));
                $translatedChoiceArray[] = $translatedChoice;
            }
            $translatedQuestion = new Question();
            $translatedQuestion->setText($this->translateString($originalQuestion->getText()));
            $translatedQuestion->setCreatedAt($originalQuestion->getCreatedAt());
            $translatedQuestion->setChoices($translatedChoiceArray);
            $translatedQuestionArray[] = $translatedQuestion;
        }
        $translatedQuestionList = new QuestionList();
        $translatedQuestionList->setData($translatedQuestionArray);

        return $translatedQuestionList;
    }

    /**
     * @param string $stringToTranslate
     *
     * @return string
     */
    private function translateString(string $stringToTranslate): string {
        // if we don't get the result, or result is empty - we fall back to the original string
        try {
            $translatedString = $this->translator->translate($stringToTranslate);
        } catch (\ErrorException $exception) {
            return $stringToTranslate;
        }

        return !empty($translatedString) ? $translatedString : $stringToTranslate;
    }
}