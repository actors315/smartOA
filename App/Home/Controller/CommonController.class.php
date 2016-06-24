<?php
namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller
{
    function _initialize() {
    	
    }
		
	/**列表页面 **/
	protected function _index($name = CONTROLLER_NAME) {
		$map = $this -> _search();
		if (method_exists($this, '_search_filter')) {
			$this -> _search_filter($map);
		}
		$model = D($name);
		if (!empty($model)) {
			$this -> _list($model, $map);
		}
		$this -> display();
	}
	
	/** 保存操作  **/
	function save() {
		$this -> _save();
	}
	
	protected function _save($name = CONTROLLER_NAME) {
		$opmode=I('opmode');
		switch($opmode) {
			case "add" :
				$this -> _insert($name);
				break;
			case "edit" :
				$this -> _update($name);
				break;
			default :
				$this -> error("非法操作");
		}
	}
	
	protected function _insert($name = CONTROLLER_NAME) {

		$model = D($name);
		if (false === $model -> create()) {
			$this -> error($model -> getError());
		}

		/*保存当前数据对象 */
		$list = $model -> add();
		if ($list !== false) {//保存成功
			$this -> assign('jumpUrl', get_return_url());
			$this -> success('新增成功!');
		} else {
			$this -> error('新增失败!');
			//失败提示
		}
	}

	/* 更新数据  */
	protected function _update($name = CONTROLLER_NAME) {
		$model = D($name);
		if (false === $model -> create()) {
			$this -> error($model -> getError());
		}
		$list = $model -> save();
		if (false !== $list) {
			$this -> assign('jumpUrl', get_return_url());
			$this -> success('编辑成功!');
			//成功提示
		} else {
			$this -> error('编辑失败!');
			//错误提示
		}
	}
	
	//生成查询条件
	protected function _search($model = null) {
		$map = array();
		//过滤非查询条件
		$request = array_filter(array_keys(array_filter($_REQUEST)), "filter_search_field");
		if (empty($model)) {
			$model = D(CONTROLLER_NAME);
		}
		$fields = get_model_fields($model);

		foreach ($request as $val) {
			$field = substr($val, 3);
			$prefix = substr($val, 0, 3);
			if (in_array($field, $fields)) {
				if ($prefix == "be_") {
					if (isset($_REQUEST["en_" . $field])) {
						if (strpos($field, "time") != false) {
							$start_time = date_to_int(trim($_REQUEST[$val]));
							$end_time = date_to_int(trim($_REQUEST["en_" . $field])) + 86400;
							$map[$field] = array( array('egt', $start_time), array('elt', $end_time));
						}
						if (strpos($field, "date") != false) {
							$start_date = trim($_REQUEST[$val]);
							$end_date = trim($_REQUEST["en_" . substr($val, 3)]);
							$map[$field] = array( array('egt', $start_date), array('elt', $end_date));
						}
					}
				}

				if ($prefix == "li_") {
					$map[$field] = array('like', '%' . trim($_REQUEST[$val]) . '%');
				}
				if ($prefix == "eq_") {
					$map[$field] = array('eq', trim($_REQUEST[$val]));
				}
				if ($prefix == "gt_") {
					$map[$field] = array('egt', trim($_REQUEST[$val]));
				}
				if ($prefix == "lt_") {
					$map[$field] = array('elt', trim($_REQUEST[$val]));
				}
			}
		}
		return $map;
	}
	
	protected function _list($model, $map, $sort = '') {
		//排序字段 默认为主键名
		if (isset($_REQUEST['_sort'])) {
			$sort = $_REQUEST['_sort'];
		} else if (in_array('sort', get_model_fields($model))) {
			$sort = "sort asc";
		} else if (empty($sort)) {
			$sort = "id desc";
		}

		//取得满足条件的记录数
		$count_model = clone $model;
		//取得满足条件的记录数
		$count = $count_model -> where($map) -> count();

		if ($count > 0) {
			//创建分页对象
			if (!empty($_REQUEST['list_rows'])) {
				$list_rows = $_REQUEST['list_rows'];
			} else {
				$list_rows = get_user_config('list_rows');
			}
			import("@.ORG.Util.Page");
			$p = new \Page($count, $list_rows);
			//分页查询数据
			$vo_list = $model -> where($map) -> order($sort) -> limit($p -> firstRow . ',' . $p -> listRows) -> select();

			//echo $model->getlastSql();
			$p -> parameter = $this -> _search($model);
			//分页显示
			$page = $p -> show();
			if ($vo_list) {
				$this -> assign('list', $vo_list);
				$this -> assign('sort', $sort);
				$this -> assign("page", $page);
				return $vo_list;
			}
		}
		return FALSE;
	}
}