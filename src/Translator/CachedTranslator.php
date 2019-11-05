<?php
namespace App\Translator;

use App\Entity\QuestionList;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CachedTranslator implements QuestionListTranslatorInterface
{
    const CACHE_TIMEOUT = 24000;

    /**
     * @var QuestionListTranslatorInterface
     */
    private $translator;
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param QuestionListTranslatorInterface $translator
     * @param CacheInterface $cache
     */
    public function __construct(QuestionListTranslatorInterface $translator, CacheInterface $cache)
    {
        $this->translator = $translator;
        $this->cache = $cache;
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
        $cacheKey = spl_object_id($questionListToTranslate);
        $cachedItem = null;
        try {
            $cachedItem = $this->cache->get($cacheKey, function (ItemInterface $item) use ($questionListToTranslate, $toLang, $fromLang) {
                $item->expiresAfter(self::CACHE_TIMEOUT);
                return $this->translator->translate($questionListToTranslate, $toLang, $fromLang);
            });
        } catch (InvalidArgumentException $exception) {
            return $this->translator->translate($questionListToTranslate, $toLang, $fromLang);
        }

        return $cachedItem;
    }
}