<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ErrorMapper;
use App\Dto\ListQuery;
use App\Form\BookType;
use App\Entity\Author;
use App\Entity\Book;

class BookController extends AbstractController
{
	public function __construct(
		public EntityManagerInterface $entityManage,
        private ErrorMapper $errorMapper
	) {}

    #[Route('/book/create', name: 'app_book_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
    	$data = json_decode($request->getContent(), true);
    	$form = $this->createForm(BookType::class, new Book);

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

    #[Route('/book/edit/{book}', name: 'app_book_create', methods: ['PUT'])]
    public function edit(Request $request, Book $book): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(BookType::class, $book);

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

    #[Route('/book/list', name: 'app_book_list', methods: ['GET'])]
    public function getList(
    	#[MapQueryString] ?ListQuery $query
    ): JsonResponse
    {
    	$data = $this->entityManage->getRepository(Book::class)->getList($query ?? new ListQuery);

        return $this->json($data);
    }

    #[Route('/book/show/{book}', name: 'app_book_show', methods: ['GET'])]
    public function show(Request $request, Book $book): JsonResponse
    {
        if ($book) {
            $authors = array_map(function(Author $author) {
                return $author->getDisplayName();
            }, $book->getAuthors()->toArray());

            $data = [
                'authors' => $authors,
                'title' => $book->getTitle(),
                'shortDescription' => $book->getShortDescription(),
                'image' => $request->getSchemeAndHttpHost() . ($book->getImage() ?? '/no-image.jpg'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/book/search', name: 'app_book_search', methods: ['GET'])]
    public function findByAuthorLastname(Request $request): JsonResponse
    {
        $lastname = $request->query->get('lastname', '');
        $data = $this->entityManage->getRepository(Book::class)->findByAuthorLastname($lastname);

        return $this->json($data);
    }
}
