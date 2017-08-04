<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/7
 * Time: 17:24
 */

namespace src\traits;


class Model
{
    private $data = [];
    private $db;
    protected $orm;
    private $module_name = '';
    private $ctrl_name = '';
    private $tb_name = '';
    private $fields = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->tb_name = $data['tb_name'];
        $this->module_name = $data['module_name'];
        $this->ctrl_name = $data['ctrl_name'];
        $this->fields = $data['fields'];
        $this->db = maker()->db($data['db_name']);
        $this->orm = $this->db->getOrm();
    }

    public function getCount($where = null)
    {
        $tb_name = $this->tb_name;
        $count = $this->orm->$tb_name;
        if ($where) {
            $count = $count->where($where[0], $where[1]);
        }
        return $count->count($this->data['pk']);
    }

    public function getRows($config = [])
    {
        $query = [
            'page_index' => 1,
            'page_rows' => 10,
            'where' => '',
            'order' => '',
        ];
        $query = array_merge($query, $config);
        $tb_name = $this->tb_name;
        $offset = $query['page_rows'] * ($query['page_index'] - 1);
        if ($offset < 0) {
            $offset = 0;
        }
        $rows = $this->orm->$tb_name
            ->select(array_keys($this->fields))
            ->order($query['order']);
        switch ($rows->getDriver()) {
            case 'sqlsrv':
                $rows = $rows
                    ->where("{$this->data['pk']} NOT IN (SELECT TOP $offset {$this->data['pk']} FROM {$this->tb_name} ORDER BY {$query['order']})")
                    ->limit($query['page_rows']);
                break;
            default:
                $rows = $rows
                    ->limit($query['page_rows'],$offset);
                break;
        }

        if ($query['where']) {
            $rows->where($query['where'][0], $query['where'][1]);
        }

        $rows = $rows->fetchPairsAsArray();
        return $rows;
    }

    public function getRow($id)
    {
        $tb_name = $this->tb_name;
        /** @var  $row \NotORM_Result */
        $row = $this->orm->$tb_name->whereId($id);
        return $row->fetchAsArray();
    }

    /** Insert row or update if it already exists
     * @param array ($column => $value)
     * @param array ($column => $value)
     * @param array ($column => $value), empty array means use $insert
     * @return int number of affected rows or false in case of an error
     */
    protected function save(array $unique, array $insert, array $update = array())
    {
        $tb_name = $this->tb_name;
        return $this->orm->$tb_name->insert_update($unique, $insert, $update);
    }

    public function saveMany($rows)
    {
        $tb_name = $this->tb_name;
        $pk = $this->orm->$tb_name->getPrimary();
        foreach ($rows as $row) {
            if ($row = $this->validation($row, false))
                $this->save([$pk => $row[$pk]], $row, $row);
        }
    }

    /** Insert row in a table
     * @param mixed array($column => $value)|Traversable for single row insert or NotORM_Result|string for INSERT ... SELECT
     * @param ... used for extended insert
     * @return mixed inserted NotORM_Row or false in case of an error or number of affected rows for INSERT ... SELECT
     */
    public function insert($data)
    {
        foreach ($data as $k => $v) {
            if (!in_array($k, array_keys($this->fields))) {
                unset($data[$k]);
            }
        }
        $data = $this->validation($data);
        $tb_name = $this->tb_name;
        return $this->orm->$tb_name->insert($data);
    }

    /** Update all rows in result set
     * @param array ($column => $value)
     * @return int number of affected rows or false in case of an error
     */
    public function update($data)
    {
        $tb_name = $this->tb_name;
        $pk = $this->orm->$tb_name->getPrimary();
        $row = $this->orm->$tb_name->whereId($data[$pk]);
        foreach ($data as $k => $v) {
            if ($k == $pk or !in_array($k, array_keys($this->fields))) {
                unset($data[$k]);
            }
        }
        $data = $this->validation($data);
        return $row->update($data);
    }

    public function deleteOne($id)
    {
        $tb_name = $this->tb_name;
        return $this->orm->$tb_name->whereId($id)->delete();
    }

    public function deleteMany($ids)
    {
        $tb_name = $this->tb_name;
        return $this->orm->$tb_name->whereId($ids)->delete();
    }

    protected function validation($data, $redirect = true)
    {
        $fields = $this->data['fields'];
        foreach ($data as $key => &$value) {
            if (isset($fields[$key])) {
                $field = $fields[$key];
                if (isset($field['filter'])) {
                    foreach ($field['filter'] as $filter) {
                        $value = preg_replace($filter, '', $value);
                    }
                }
                if (isset($field['verify'])) {
                    if (!preg_match($field['verify'][0], $value)) {
                        if ($redirect)
                            maker()->sender()->warning($field['name'] . ':' . $field['verify'][1], 'back', 3);
                        else
                            return false;
                    }
                }
            }
        }
        return $data;
    }

}