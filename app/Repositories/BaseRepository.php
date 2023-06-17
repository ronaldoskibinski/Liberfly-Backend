<?php

namespace App\Repositories;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

abstract class BaseRepository
{
   /**
   * Model class for repo.
   *
   * @var string
   */
   protected $modelClass;

   public function __construct()
   {

   }

   /**
   * @return EloquentQueryBuilder|QueryBuilder
   */
    protected function newQuery()
    {
        return app($this->modelClass)->newQuery();
    }

    /**
   * @param EloquentQueryBuilder|QueryBuilder $query
   * @param int                               $take
   * @param bool                              $paginate
   *
   * @return EloquentCollection|Paginator
   */
    protected function doQuery($query = null, $page = 1, $take = 15, $paginate = true, $wheres = [], $orderBys = [])
    {
        if (is_null($query)) {
            $query = $this->newQuery();
        }


        $this->buildWheres($query, $wheres);

        foreach ($orderBys as $orderBy) {
            $str_arr = preg_split ("/\,/", $orderBy);


            if (!empty($str_arr[0])) {
                $query->orderBy($str_arr[0], isset($str_arr[1]) ? $str_arr[1] : "ASC");
            }
        }



        if ($paginate) {
            $from = $page == 1 ? 0 : $take * ($page -1);
            $retorno = $query->skip($from)->take($take);
            return $retorno->get();
        }

        if ($take && $take > 0) {
            $query->take($take);
        }

        return $query->get();
    }

   /**
   * Returns all records.
   * If $take is false then brings all records
   * If $paginate is true returns Paginator instance.
   *
   * @param int  $take
   * @param bool $paginate
   *
   * @return EloquentCollection|Paginator
   */
    public function getAll($query = null, $page = 1, $take = 15, $paginate = true, $wheres = [], $with = [], $orderBys = [])
    {
        $query = $this->newQuery();


        $query->select($this->buildColumns($query, $wheres))
            ->with($with);


        return $this->doQuery($query, $page, $take, $paginate, $wheres, $orderBys);
    }

    private function buildColumns(&$query, $wheres) {

        $arrColumns = [];

        array_push($arrColumns, app($this->modelClass)->getTable() . ".*");

        foreach ($wheres as $where) {
            if (isset($where["columns"])) {
                foreach ($where["columns"] as $column) {
                    array_push($arrColumns, DB::raw($column));
                }
            }
        }

        return $arrColumns;
    }

    private function buildWheres(&$query, $wheres) {
        $tabelasJaInseridasNoJoin = [];
        foreach ($wheres as $where) {
            if (isset($where["joins"]) && count($where["joins"]) > 0) {
                foreach ($where["joins"] as $join) {
                    if(!in_array($join["tabela"],$tabelasJaInseridasNoJoin)){
                        $query->join($join["tabela"], $join["coluna_tabela_origem"], $join["condicional"], $join["coluna_tabela_destino"]);
                        array_push($tabelasJaInseridasNoJoin,$join["tabela"]);
                    }
                }
            }

            if (isset($where["whereRaw"])) {
                $query->whereRaw($where["whereRaw"]);
            } else {
                if (isset($where["coluna"])) {
                    if($where["condicional"] == 'IN'){
                        $query->whereIn($where["coluna"], $where["valor"]);
                    }else{
                        $query->where($where["coluna"], $where["condicional"], $where["valor"]);
                    }
                }
            }
        }
        return $query;
    }

    public function count($wheres = [])
    {
        $query = $this->newQuery();

        $this->buildWheres($query, $wheres);

        return $query->count();
    }

   /**
   * @param string      $column
   * @param string|null $key
   *
   * @return \Illuminate\Support\Collection
   */
    public function lists($column, $key = null, $wheres = [])
    {
        $query = $this->newQuery()
            ->select(app($this->modelClass)->getTable() . ".*");

        $this->buildWheres($query, $wheres);

        $query->select($this->buildColumns($query, $wheres));

        return $query->lists($column, $key);
    }

    /**
   * @param string      $column
   * @param string|null $key
   *
   * @return \Illuminate\Support\Collection
   */
    public function where($column, $key = null)
    {
        return $this->newQuery()->where($column, $key);
    }

   /**
   * Retrieves a record by his id
   * If fail is true $ fires ModelNotFoundException.
   *
   * @param int  $id
   * @param bool $fail
   * @param string $with
   *
   * @return BaseModel
   */
    public function findByID($id, $fail = true, $with = [])
    {
        if ($fail) {
            return $this->newQuery()
                ->select(app($this->modelClass)->getTable() . ".*")
                ->with($with)->where("id",$id)->firstOrFail();
        }
        return $this->newQuery()
            ->select(app($this->modelClass)->getTable() . ".*")
            ->with($with)
            ->where("id",$id)->first();
    }
}
