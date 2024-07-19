<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ErrorMapper;
use App\Form\AuthorType;
use App\Entity\Author;
use App\Dto\ListQuery;

class AuthorController extends AbstractController
{
	public function __construct(
		private EntityManagerInterface $entityManage,
        private ErrorMapper $errorMapper
	) {}

    #[Route('/author/create', name: 'app_author_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
    	$data = json_decode($request->getContent(), true);
    	$form = $this->createForm(AuthorType::class, new Author);

    	$form->submit($data);

    	if ($form->isValid()) {

    		$this->entityManage->persist($form->getData());
    		$this->entityManage->flush();

    	} else {

            $errors = $this->errorMapper->mapErrors($form->getErrors($deep = true));
        }

        return $this->json([
            'errors' => $errors ?? [],
            'status' => $form->isValid(),
        ]);
    }

    #[Route('/author/list', name: 'app_author_list', methods: ['GET'])]
    public function getList(
    	#[MapQueryString] ?ListQuery $query
    ): JsonResponse
    {
    	$data = $this->entityManage->getRepository(Author::class)->getList($query ?? new ListQuery);

        return $this->json($data);
    }
}
