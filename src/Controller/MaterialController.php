<?php

namespace App\Controller;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Material;
use App\Entity\Concept;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MaterialController
 * @package App\Controller
 * @OA\Tag(name="Material")
 */
class MaterialController extends AbstractFOSRestController
{

    /**
     * Search materials.
     * @OA\Response(
     *  response=200,
     *  description="Search materials.",
     *  @OA\JsonContent(type="array",
     *      @OA\Items(allOf={@OA\Schema(ref=@Model(type=Material::class))},
     *      @OA\Property(property="concept", type="array", @OA\Items(ref=@Model(type=Concept::class)))))
     * )
     * @OA\Response(response="404", description="No material found.")
     * 
     * @OA\RequestBody(description="Search parameters",
     *     @OA\Schema(type="object",
     *         @OA\Property(property="conceptFilter", type="array")))
     * 
     * @Rest\Get(path="/material", name="app_material_list")
     * @Rest\RequestParam(name="concept", nullable=true)
     * @Rest\View
     * 
     * @return View
     */
    public function listAction(?array $concept): View
    {
        $repo = $this->getDoctrine()->getRepository(Material::class);
        $materials = $repo->findAllMaterial();

        if (count($materials) === 0) {
            return View::create([], Response::HTTP_NOT_FOUND);
        }

        //$materials = $this->formatMaterial($materials);
        return View::create($materials, Response::HTTP_OK);
    }
}
