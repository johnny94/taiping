<?php namespace App\Taiping\Bootgrid;

use App\Taiping\Helper\Helper;

class QueryClassSwitching extends Response
{
    protected function query()
    {
        $filterByDate = $this->request->input('filterByDate');
        $filterFrom = $this->request->input('filterFrom');
        $filterTo = $this->request->input('filterTo');

        // TODO: Refactor Helper method.
        $this->result = Helper::buildClassSwitchingQuery(
                            $this->searchPhrase,
                            $filterByDate,
                            $filterFrom,
                            $filterTo);
    }
}