<?php namespace App\Taiping\Bootgrid;

class QueryByColumn extends Response
{
    protected function query()
    {
        $columnName = $this->request->input('columnName', '');
        $searchText = '%' . $this->searchPhrase . '%';
        $this->result = $this->model->where($columnName, 'like', $searchText);
    }
}