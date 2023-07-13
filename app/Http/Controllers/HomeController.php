<?php

namespace App\Http\Controllers;

use App\Data\ProductData;
use App\Elastics\ElasticSearchBuilder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ElasticSearchBuilder $elasticSearchBuilder
    ) {
    }

    public function index()
    {
        return view('results');
    }

    public function datatable(Request $request)
    {
        $response = $this->elasticSearchBuilder->setModel(new Product())->searchWithPagination($request->get('search'));
        $collection = collect($response['hits']['hits']);
        return DataTables::collection($collection)
            ->editColumn('id', function ($data) {
                return $data['_source']['id'];
            })
            ->editColumn('name', function ($data) {
                return $data['_source']['name'];
            })
            ->editColumn('description', function ($data) {
                return $data['_source']['description'];
            })
            ->editColumn('price', function ($data) {
                return $data['_source']['price'];
            })
            ->make();
    }
}
