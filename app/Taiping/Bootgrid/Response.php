<?php namespace App\Taiping\Bootgrid;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

abstract class Response
{
    const FETCH_ALL_ROW = -1;

    // jQuery Bootgrid plugin
    protected $currentPage;
    protected $rowCount;
    protected $searchPhrase;
    protected $sort;
    protected $total;

    protected $model;
    protected $request;
    protected $result;

    public function __construct(Model $m, Request $request)
    {
        $this->model = $m;
        $this->request = $request;
    }

    public function response()
    {
        $this->extractBootgridParams();
        $this->query();
        $this->total = $this->result->count();
        if ($this->request->has('sort')) {
            $this->sortResult($this->request->input('sort'));
        }
        $this->pagination();

        return ['current' => $this->currentPage, 'rowCount' => $this->rowCount, 'rows' => $this->result, 'total' => $this->total];
    }

    protected function extractBootgridParams()
    {
        $this->currentPage = intval($this->request->input('current'));
        $this->rowCount = intval($this->request->input('rowCount'));
        $this->searchPhrase = $this->request->input('searchPhrase');
    }

    abstract protected function query();

    protected function sortResult($param)
    {
        $sort = each($param);
        $column = $sort['key'];
        $order = $sort['value'];

        $this->result = $this->result->orderBy($column, $order);
    }

    protected function pagination()
    {
        if ($this->rowCount == self::FETCH_ALL_ROW) {
            $this->result = $this->result->get();
        } else {
            $this->result = $this->result->skip($this->currentPage*$this->rowCount - $this->rowCount)
                            ->take($this->rowCount)
                            ->get();
        }
    }
}
