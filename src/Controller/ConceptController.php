<?php

namespace App\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Concept;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ConceptController
 * @package App\Controller
 * @OA\Tag(name="Concept")
 */
class ConceptController extends AbstractFOSRestController
{
    /**
     * Get all concepts.
     * @OA\Response(
     *  response=200,
     *  description="Concept list.",
     *  @OA\JsonContent(type="array",
     *      @OA\Items(ref=@Model(type=Concept::class)))
     * )
     * @OA\Response(response="404", description="No concept found.")
     * 
     * @Rest\Get(path="/concept", name="app_concept_list")
     * @Rest\View
     * 
     * @param string[] $rdfAboutConceptFilter
     * @return View
     */
    public function listAction(): View
    {
        $concepts = $this->getDoctrine()->getRepository(Concept::class)->findAll();
        if (count($concepts) === 0) {
            return View::create('No concept found.', Response::HTTP_NOT_FOUND);
        }

        return View::create($concepts, Response::HTTP_OK);
    }
}