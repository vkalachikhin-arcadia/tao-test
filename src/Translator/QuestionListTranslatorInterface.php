<?php
namespace App\Translator;

use App\Entity\QuestionList;

interface QuestionListTranslatorInterface
{
    /**
     * @param QuestionList $questionListToTranslate
     * @param string $toLang
     * @param string $fromLang
     *
     * @return QuestionList
     *
     */
    public function translate(QuestionList $questionListToTranslate, string $toLang, string $fromLang = 'en'): QuestionList;
}