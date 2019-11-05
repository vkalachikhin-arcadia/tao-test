<?php
namespace App\Controller;

use App\Entity\QuestionList;
use App\Exception\RepositoryException;
use App\Exception\ValidationException;
use App\Repository\Question\QuestionRepositoryInterface;
use App\Repository\QuestionList\QuestionListRepositoryInterface;
use App\Translator\QuestionListTranslatorInterface;
use JMS\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/questions",
     *     description="Returns the list of translated questions and associated choices",
     *     @OA\Parameter(
     *       name="lang",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *      ),
     *     @OA\Response(response="200",
     *       description="List of translated questions and associated choices",
     *       @OA\JsonContent(ref="#/components/schemas/QuestionList")
     *     ),
     *     @OA\Response(response="400",
     *      description="Empty response in case something goes wrong with retrieving data from repository"
     *     )
     * )
     *
     * @param QuestionListRepositoryInterface $questionListRepository
     * @param SerializerInterface $serializer
     * @param QuestionListTranslatorInterface $translator
     * @param Request $request
     *
     * @return Response
     * @Route("/questions", methods={"GET"})
     */
    public function getQuestions(QuestionListRepositoryInterface $questionListRepository, SerializerInterface $serializer,
                                 QuestionListTranslatorInterface $translator, Request $request)
    {
        $response = new JsonResponse();
        try {
            $questionList = $questionListRepository->getQuestionList();
        } catch (RepositoryException $exception) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST); // no data, so bad request and empty content
            return $response;
        }

        $lang = $request->query->get('lang');
        if ($lang) {
            $questionList = $translator->translate($questionList, $lang);
        }

        $response->setContent($serializer->serialize($questionList, 'json'));

        return $response;
    }

    /**
     * @OA\Post(
     *     path="/questions",
     *     summary="Creates a new question and associated choices (the number of associated choices must be exactly equal to 3)",
     *     @OA\RequestBody(
     *      request="Question",
     *      required=true,
     *      description="Question and associated choices",
     *      @OA\JsonContent(ref="#/components/schemas/Question")
     *      ),
     *     @OA\Response(response="200",
     *       description="Question and associated choices (not translated)",
     *       @OA\JsonContent(ref="#/components/schemas/Question")
     *     ),
     *     @OA\Response(response="400",
     *      description="Response in case something goes wrong while saving data to repository",
     *      @OA\JsonContent(
     *          type="string"
     *      )
     *     ),
     *     @OA\Response(response="406",
     *      description="Response in case something user has provided data which did not pass validation",
     *      @OA\JsonContent(
     *          type="string"
     *      )
     *     )
     * )
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param QuestionRepositoryInterface $questionRepository
     *
     * @return JsonResponse
     * @Route("/questions", methods={"POST"})
     */
    public function addQuestion(Request $request, SerializerInterface $serializer, QuestionRepositoryInterface $questionRepository)
    {
        $response = new JsonResponse();

        try {
            /** @var QuestionList $question */
            $question = $serializer->deserialize($request->getContent(), QuestionList::class, 'json');
        } catch (\Exception $exception) {
            $response->setContent($exception->getMessage());
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }

        try {
            $questionRepository->saveQuestion($question);
        } catch (RepositoryException $exception) {
            $response->setContent($exception->getMessage());
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        } catch (ValidationException $exception) {
            $response->setContent($exception->getMessage());
            // data could be good, but does not pass our validation, thus another status
            $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
            return $response;
        }

        // serialize again - to make sure it works both ways correctly
        $response->setContent($serializer->serialize($question, 'json'));

        return $response;
    }
}