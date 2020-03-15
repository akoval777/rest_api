<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ReviewController
 * @package App\Controller
 */
class ReviewController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/reviews")
     *
     * @return Response
     */
    public function getReviewsAction()
    {
        $repository = $this->getDoctrine()->getRepository(Review::class);
        $reviews = $repository->findAll();

        return $this->handleView($this->view($reviews));
    }

    /**
     * @Rest\Get(path="/review/{id}")
     *
     * @param $id
     * @return Response
     */
    public function getReviewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Review::class);
        $review = $repository->find($id);

        if (!$review) {
            throw new HttpException(404,
                'No review found for id ' . $id
            );
        }

        return $this->handleView($this->view($review));
    }

    /**
     * @Rest\Post("/review")
     *
     * @param Request $request
     * @return Response
     */
    public function postReviewAction(Request $request)
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $this->processForm($request, $form);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            return $this->handleView($this->view(['status' => 'created'], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * @Rest\Put("/review/{id}")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updateReviewAction($id, Request $request)
    {
        $review = $this->getDoctrine()->getRepository(Review::class)->find($id);

        if (!$review) {
            throw new HttpException(404,
                'No review found for id ' . $id
            );
        }

        $form = $this->createForm(ReviewType::class, $review);
        $this->processForm($request, $form);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            return $this->handleView($this->view($review));
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * @Rest\Delete("/review/{id}")
     *
     * @param $id
     * @return Response
     */
    public function deleteReviewAction($id)
    {
        $review = $this->getDoctrine()->getRepository(Review::class)->find($id);

        if (!$review) {
            throw new HttpException(404,
                'No review found for id ' . $id
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));


    }

    /**
     * @param Request $request
     * @param FormInterface $form
     */
    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);

        $clearMissing = $request->getMethod() != 'PUT';
        $form->submit($data, $clearMissing);
    }
}
