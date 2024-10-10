<?php

namespace App\Http\Controllers;

use App\Actions\SearchVaccinationStatusAction;
use App\Http\Requests\SearchRequest;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(private SearchVaccinationStatusAction $action) {}

    public function search(): View
    {
        return view('search.search');
    }

    public function searchStatus(SearchRequest $request): View
    {
        $data = $this->action->execute($request->validated());

        return view('search.search_status', compact('data'));

    }
}
