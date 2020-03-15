<?php

namespace App\Controller;

use App\Entity\Shop;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ShopController
 * @package App\Controller
 *
 */
class ShopController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/shops")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function getShopsAction(Request $request, PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Shop::class);
        $shops = $repository->findAll();

        $pagination = $paginator->paginate(
            $shops,
            $request->get('page', 1),
            $request->get('limit', 5)
        );

        return $this->handleView($this->view([
                'items' => $pagination->getItems(),
                'total_count' => $pagination->getTotalItemCount(),
                'current_page' => $pagination->getCurrentPageNumber(),
                'items_per_page' => $pagination->getItemNumberPerPage()
            ]
        ));
    }

    /**
     * @Rest\Get(path="/shop/{id}")
     *
     * @param $id
     * @return Response
     */
    public function getShopAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Shop::class);
        $shop = $repository->find($id);

        if (!$shop) {
            throw new HttpException(404,
                'No shop found for id ' . $id
            );
        }

        return $this->handleView($this->view($shop));
    }
}
