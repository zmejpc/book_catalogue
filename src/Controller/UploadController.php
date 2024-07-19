<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class UploadController extends AbstractController
{
	private const MAX_SIZE = 2000000;
	private const EXTENSIONS = ['jpg', 'png'];

	#[Route('/file-upload', name: 'upload', methods: ['POST'])]
	#
	function upload(Request $request): JsonResponse
	{
		$uploadedFile = $request->files->get('file');
		$extension = $uploadedFile->guessExtension();

		if (!in_array($extension, static::EXTENSIONS)) {
			$error = 'Неправильне розширення файлу';
		} elseif ($uploadedFile->getSize() > static::MAX_SIZE) {
			$error = 'Максимальний розмір файлу - ' . round(static::MAX_SIZE / 1000000) . 'MB';
		}

		if (!isset($error)) {

			$dir = $this->getParameter('kernel.project_dir') . '/public/uploads';
			$file_name = md5(uniqid() . time()) . '.' . $extension;

			$uploadedFile->move($dir, $file_name);
			$path = '/uploads/' . $file_name;
		}

		return new JsonResponse([
			'path' => $path ?? '',
			'error' => $error ?? '',
		]);
	}
}