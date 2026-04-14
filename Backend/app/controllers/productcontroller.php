<?php

namespace Controllers;

use Exception;
use Services\ProductService;

class ProductController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new ProductService();
    }

    public function getAll()
    {
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        try {
            $products = $this->service->getAll($offset, $limit);
            $this->respond($products);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getOne($id)
    {
        try {
            $product = $this->service->getOne($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        if (!$product) {
            $this->respondWithError(404, "Product not found");
            return;
        }

        $this->respond($product);
    }

    public function create()
    {
        $this->requireRole('admin');
        try {
            $product = $this->createObjectFromPostedJson("Models\\Product");

            if ($product->price < 0 || $product->stock < 0) {
                $this->respondWithError(400, "Price and stock cannot be negative.");
                return;
            }

            $product = $this->service->insert($product);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($product);
    }

    public function update($id)
    {
        $this->requireRole('admin');
        try {
            $product = $this->createObjectFromPostedJson("Models\\Product");

            if ($product->price < 0 || $product->stock < 0) {
                $this->respondWithError(400, "Price and stock cannot be negative.");
                return;
            }

            $product = $this->service->update($product, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($product);
    }

    public function delete($id)
    {
        $this->requireRole('admin');
        try {
            $this->service->delete($id);
            $this->respond(true);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
