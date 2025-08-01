<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Image;
use App\Form\AdvertisementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdvertisementController extends AbstractController
{
    #[Route('/', name: 'advertisement_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $ads = $em->getRepository(Advertisement::class)->findAll();
        return $this->render('advertisement/index.html.twig', ['ads' => $ads]);
    }

    #[Route('/new', name: 'advertisement_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $ad = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagesFiles = $form->get('images')->getData();
            if ($imagesFiles) {
                foreach ($imagesFiles as $uploadedFile) {
                    $image = new Image();
                    $image->setImageFile($uploadedFile); // jeśli VichUploader
                    $ad->addImage($image);
                }
            }
            $em->persist($ad);
            $em->flush();

            return $this->redirectToRoute('advertisement_show', ['id' => $ad->getId()]);
        }

        return $this->render('advertisement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ad/{id}', name: 'advertisement_show')]
    public function show(Advertisement $ad): Response
    {
        return $this->render('advertisement/show.html.twig', ['ad' => $ad]);
    }
}
