<?php

namespace App\Http\Controllers;

use App\Data\ProductData;
use App\Elastics\ElasticSearchBuilder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

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
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $response = $this->elasticSearchBuilder->setModel(new Product())->searchWithPagination($currentPage, $perPage, $request->get('search'));
        $totalHits = $response['hits']['total']['value'];
        $collection = collect($response['hits']['hits']);
        Paginator::useBootstrapFive();
        $paginatedResults = new LengthAwarePaginator($collection, $totalHits, $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        return view('results', [
            'products' => $paginatedResults,
        ]);
    }
}
