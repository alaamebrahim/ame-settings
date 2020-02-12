<?php
/**
 * Created by Alaa mohammed.
 * User: alaa
 * Date: 08/08/19
 * Time: 09:22 م
 */

namespace Alaame\Setting\Repositories;


Interface Repository
{

    public function makeModel();

    public function paginate($perPage, $columns = ['*']);

    public function allQuery($search = [], $skip = null, $limit = null);

    public function all($search = [], $skip = null, $limit = null, $columns = ['*']);

    public function create($input);

    public function find($id, $columns = ['*']);

    public function update($input, $id);

    public function delete($id);
}
