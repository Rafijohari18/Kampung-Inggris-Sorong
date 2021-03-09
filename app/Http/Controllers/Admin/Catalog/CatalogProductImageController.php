<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\CatalogProductImageRequest;
use App\Services\Catalog\CatalogProductImageService;
use App\Services\Catalog\CatalogProductService;
use Illuminate\Http\Request;

class CatalogProductImageController extends Controller
{
    private $service, $serviceProduct;

    public function __construct(
        CatalogProductImageService $service,
        CatalogProductService $serviceProduct
    )
    {
        $this->service = $service;
        $this->serviceProduct = $serviceProduct;
    }

    public function index(Request $request, $productId)
    {
        $l = '';
        $q = '';
        if (isset($request->l) || isset($request->q)) {
            $l = '?l='.$request->l;
            $q = '&q='.$request->q;
        }

        $data['product_images'] = $this->service->getImageList($request, $productId);
        $data['no'] = $data['product_images']->firstItem();
        $data['product_images']->withPath(url()->current().$l.$q);
        $data['catalog_product'] = $this->serviceProduct->find($productId);

        return view('backend.catalogue.products.images.index', compact('data'), [
            'title' => 'Products - Images',
            'breadcrumbs' => [
                'Catalog' => '',
                'Category' => route('catalog.category.index'),
                'Product' => route('catalog.product.index', [
                    'categoryId' => $data['catalog_product']->catalog_category_id
                ]),
                'Image' => ''
            ]
        ]);
    }

    public function store(CatalogProductImageRequest $request, $productId)
    {
        $this->service->store($request, $productId);

        return back()->with('success', 'image successfully added');
    }

    public function update(CatalogProductImageRequest $request, $productId, $id)
    {
        $this->service->update($request, $id);

        return back()->with('success', 'image successfully updated');
    }

    public function position($productId, $id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position image changed');
    }

    public function sort($productId)
    {
        $i = 0;

        foreach ($_POST['datas'] as $value) {
            $i++;
            $this->service->sort($value, $i);
        }
    }

    public function destroy($productId, $id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }
}
