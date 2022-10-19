<?php

namespace App\Controller;

use App\Entity\TarotCard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class ApiCreateTarotImagesController extends AbstractController
{
    public function __invoke(Request $request) : TarotCard
    {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $tarotCard = ($request->attributes->get('data'));

        if (!$tarotCard instanceof TarotCard) {
            throw new \RuntimeException('TarotCard object is expected.');
        }

        $tarotCard->file = $uploadedFile;
        $tarotCard->setUpdatedAt(new \DateTime());

        return $tarotCard;
    }
}
